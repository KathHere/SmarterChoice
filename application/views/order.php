<?php
Class order extends Ci_Controller
{
	var $response_code = 1;//false or error default
	var $response_message = "";
	var $response_data = array();

	public function __construct()
	{
		parent::__construct();
		is_logged_in();

		date_default_timezone_set("America/Los_Angeles");
		$this->load->model("hospice_model");
		$this->load->model("order_model");
	}

	public function create_order($hospice_id=0)
	{
		$hospiceID = $this->session->userdata('group_id');
		$user_type = $this->session->userdata('account_type');
		$hospiceID = $this->session->userdata('group_id');

		$data['hospice_selected'] = $hospice_id;
		$data['hospice_phone']	 = json_decode(get_hospice_phone($hospice_id));


		$categories = $this->order_model->get_equipment_category();

		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				if($hospice_id != 0)
				{
					$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospice_id);
				}
				else
				{	
					$children = $this->order_model->get_equipment($cat['categoryID']);
				}	
				
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}

			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);

		}

		$data['equipments'] = $equipment_array;
		$data['hospices']	= $this->hospice_model->list_group();
		$data['active_nav']	= "create_order";
		$this->templating_library->set('title','Create New Order');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/order_form','pages/order_form', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function order_list($view_type='list-view')
	{
		$data['order_status_list'] = true;
		$get_data = $this->input->get();
		
		if($view_type == 'list-view')
		{
			$result = $this->order_model->list_order_status();

			$data['orders'] = ($result) ? $result : FALSE;
			$data['active_nav']	= "order_list";
			$this->templating_library->set('title','PT Order Status');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav',$data);
			$this->templating_library->set_view('pages/patient_order_list','pages/patient_order_list', $data);
		}
		else
		{
			$result = $this->order_model->list_orders();

			$counts = count($this->order_model->list_orders());
			$data['orders'] = ($result) ? $result : FALSE;
			$data['counts'] = $counts;
			$data['active_nav']	= "patient_menu";
			$this->templating_library->set('title','View All Patients');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav',$data);
			$this->templating_library->set_view('pages/patient_order_grid','pages/patient_order_grid', $data);
		}
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}


	public function list_tobe_confirmed()
	{
		$result = $this->order_model->list_tobe_confirmed();

		$data['orders'] = ($result) ? $result : FALSE;
		$data['active_nav']	= "order_list";
		$this->templating_library->set('title','Confirm Work Order');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav',$data);
		$this->templating_library->set_view('pages/list_tobe_confirmed','pages/list_tobe_confirmed', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}


	public function sort_by_hospice($hospiceID)
	{
		$data['hospice_selected'] = $hospiceID;
		$result = $this->order_model->list_orders_sorted($uniqueID="",$hospiceID, $where="");
		
		$counts = count($this->order_model->list_orders_sorted($uniqueID="",$hospiceID, $where=""));
		$data['orders'] = ($result) ? $result : FALSE;
		$data['counts'] = $counts;

	
		$data['active_nav']	= "patient_menu";
		$this->templating_library->set('title','View All Patients');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav',$data);
		$this->templating_library->set_view('pages/patient_order_grid','pages/patient_order_grid', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function patient_profile($uniqueID,$hospiceID='')
	{
		$user_type = $this->session->userdata('account_type');
		
		$returns = $this->order_model->list_orders($uniqueID);
		$informations = $this->order_model->get_order_info($uniqueID,$hospiceID);
		$data['comments'] = $this->order_model->get_all_comments($uniqueID);

		$datas = array();
		$data['summarys'] = $returns;
		$data['informations'] = $informations;

		$data['note_counts'] = $this->order_model->count_patient_comments($uniqueID);

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $uniqueID;

        $response = $this->order_model->get_orders($id);

        $order_summary = $this->order_model->get_orders($id,$hospiceID); //added hospiceID as 2nd parameter. This is added 07/13/2015. Remove it it will cause error.

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        $data['summaries'] = $order_summary;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $uniqueID;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id); 
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }
		$this->templating_library->set('title','Patient Order Summary');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/order_summary','pages/order_summary',$data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function patient_vault_records()
	{
		$result = $this->order_model->list_confirmed_orders();
		$data['orders'] = ($result) ? $result : FALSE;

		$this->templating_library->set('title','Patient Vault');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/patient_vault','pages/patient_vault', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}


	public function greetings()
	{
		$this->templating_library->set('title','Thank you for trusting Us!');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/greeting','pages/greeting');
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	} 

	public function search_patient($searchString='',$hospiceID='')
	{
		$post_data = $this->input->post();
		$createdby = $this->session->userdata('group_id');
		$return = "";

		if($this->session->userdata('account_type') == 'dme_admin')
		{
			$searches = $this->order_model->search_patient($searchString);
		}
		else
		{
			$searches = $this->order_model->search_patient_hospice($searchString,$createdby);
		}
		if($searches)
		{
			foreach ($searches as $search) 
			{
				echo "<tr style='cursor:pointer;' class='pmrn_result form-control' title='".$search['medical_record_id']."' data-id='".$search['medical_record_id']."'><td>Patient MRN: ".$search['medical_record_id']."</td></tr>";
			}
		}
		else
		{
			echo "<tr style='cursor:pointer;' class='form-control'><td>No Results Returned.</td></tr>";
		}
	}

	//** This function kay para sa auto suggest and auto populate sa Patient Info base sa Patient Medical Record Num **//
	function return_patient_info($patientmrn)
	{
		$results = $this->order_model->get_patient_info($patientmrn);
		if($results)
		{
			foreach($results as $result)
			{
				$data = array(
					'p_fname' => $result['p_fname'],
					'p_lname' => $result['p_lname'],
					'p_height' => $result['p_height'],
					'p_weight' => $result['p_weight'],
					'p_street' => $result['p_street'],
					'p_placenum' => $result['p_placenum'],
					'p_city' => $result['p_city'],
					'p_state' => $result['p_state'],
					'p_postalcode' => $result['p_postalcode'],
					'p_phonenum' => $result['p_phonenum'],
					'p_altphonenum' => $result['p_altphonenum'],
					'p_nextofkin' => $result['p_nextofkin'],
					'p_relationship' => $result['p_relationship'],
					'relationship_gender' => $result['relationship_gender'],
					'p_nextofkinnum' => $result['p_nextofkinnum'],
				);
			}
		}
		else
		{
			return FALSE;
		}
		$data_encoded = json_encode($data);
		echo $data_encoded;
	}


	public function add_order()
	{
		$id = $this->session->userdata('userID');
		$account_type_name = $this->session->userdata('account_type');

		$staff_member_fname = $this->session->userdata('firstname');
		$staff_member_lname = $this->session->userdata('lastname');

		if($this->input->post())
		{
			$person_who_ordered = $this->session->userdata('email');
			$data_post = $this->input->post();

			$this->form_validation->set_rules('organization_id','Hospice Provider','required');
			$this->form_validation->set_rules('equipments[]','Items','required');
			$this->form_validation->set_rules('delivery_date','Delivery Date','required');
			$this->form_validation->set_rules('dropdown_deliver_type','Type of Delivery','required');
			$this->form_validation->set_rules('patient_mrn','Patient Medical Record Number','required');
			//$this->form_validation->set_rules('activity_type','Activity Type','required');
			
			$this->form_validation->set_rules('patient_lname','Patient Last Name','required');
			$this->form_validation->set_rules('patient_fname','Patient First Name','required');
			$this->form_validation->set_rules('patient_phone_num','Patient Phone No.','required');
			$this->form_validation->set_rules('patient_city','Patient City','required');
			$this->form_validation->set_rules('patient_state','Patient State','required');
			$this->form_validation->set_rules('patient_postalcode','Patient Postal Code','required');
			$this->form_validation->set_rules('patient_nextofkin','Patient Next of Kin','required');
			$this->form_validation->set_rules('patient_relationship','Relationship to Patient','required');
			$this->form_validation->set_rules('relationship_gender','Gender','required');
			$this->form_validation->set_rules('patient_nextofkinphonenum','Patient Next of Kin Phone No.','required');
			$this->form_validation->set_rules('person_placing_order_lname','Hospice Staff Member Creating Order First Name' ,'required');
			$this->form_validation->set_rules('person_placing_order_fname','Hospice Staff Member Creating Order Last Name','required');
			$this->form_validation->set_rules('email','Email Address','required');
			$this->form_validation->set_rules('patient_height','Patient Height','required');
			$this->form_validation->set_rules('patient_weight','Patient Weight','required');

			if($this->form_validation->run()===TRUE)
			{
				$unique_id = strtotime(date('Y-m-d H:i:s'));
				$medical_id = $data_post['patient_mrn'];

				$patient_info = array(
					'p_fname'		=> $data_post['patient_fname'],
					'p_lname'		=> $data_post['patient_lname'],
					'p_height'		=> $data_post['patient_height'],
					'p_weight'		=> $data_post['patient_weight'],
					'p_street'		=> $data_post['p_address'],
					'p_placenum'	=> $data_post['patient_placenum'],
					'p_city'		=> $data_post['patient_city'],
					'p_state'		=> $data_post['patient_state'],
					'p_postalcode'	=> $data_post['patient_postalcode'],
					'p_phonenum'	=> $data_post['patient_phone_num'],
					'p_altphonenum'	=> $data_post['patient_alt_phonenum'],
					'p_nextofkin'	 => $data_post['patient_nextofkin'],
					'p_relationship'	 => $data_post['patient_relationship'],
					'relationship_gender'	 => $data_post['relationship_gender'],
					'p_nextofkinnum' => $data_post['patient_nextofkinphonenum'],
					'ordered_by' 	 => $data_post['organization_id'],
					'medical_record_id' => $data_post['patient_mrn']
				); 

				$save_patient_info = $this->order_model->save_patientinfo($patient_info);

				if($save_patient_info!=FALSE)
				{	
					/*

					* order information

					*/
					$pickup_date 		= date($data_post['delivery_date']);
					$ordered_by  		= $id;
					$organization_id 	= $data_post['organization_id'];
					$activity_type		= 1; //delivery

					//order items
					$orders = array();
					foreach($data_post['equipments'] as $key=>$value)
					{
							$orders[] = array(				

											"patientID"				=> $save_patient_info,			
											"equipmentID"			=> $value,		
											"equipment_value"		=> 1,
											"pickup_date"			=> $pickup_date,
											"activity_typeid"		=> $activity_type,	
											"organization_id"		=> $organization_id,
											"ordered_by"			=> $id,
											"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
											"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
											"who_ordered_email"		=> $data_post['email'],
											"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
											"staff_member_fname"	=> $staff_member_fname,
											"staff_member_lname"	=> $staff_member_lname,
											"comment"				=> $data_post['comment'],
											"uniqueID"				=> $unique_id,
											"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
											'medical_record_id' 	=> $data_post['patient_mrn'],
											"order_status"	        => "pending"
										);

					}
						/*
						* @sub equipments  
						*
						*/

						foreach($data_post['subequipment'] as $key=>$value)
						{
								if(in_array($key,$data_post['equipments']))
								{
									foreach($value as $sub_key=>$sub_value)
									{
										if($sub_key=="radio")
										{
											foreach($sub_value as $radio_value)
											{
													$orders[] = array(				
															"patientID"				=> $save_patient_info,			
															"equipmentID"			=> $radio_value,		
															"equipment_value"		=> 1,
															"pickup_date"			=> $pickup_date,
															"activity_typeid"		=> $activity_type,	
															"organization_id"		=> $organization_id,
															"ordered_by"			=> $id,
															"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
															"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
															"who_ordered_email"		=> $data_post['email'],
															"who_ordered_cpnum"	=> $data_post['who_ordered_cpnum'],
															"staff_member_fname"	=> $staff_member_fname,
															"staff_member_lname"	=> $staff_member_lname,
															"comment"				=> $data_post['comment'],
															"uniqueID"				=> $unique_id,
															"deliver_to_type"	        => $data_post['dropdown_deliver_type'],
															'medical_record_id' => $data_post['patient_mrn'],
															"order_status"	    => "pending"
														);
											}
										}
										else
										{
											$orders[] = array(				
												"patientID"				=> $save_patient_info,			
												"equipmentID"			=> $sub_key,		
												"equipment_value"		=> $sub_value,
												"pickup_date"			=> $pickup_date,
												"activity_typeid"		=> $activity_type,	
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
												"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
												"who_ordered_email"		=> $data_post['email'],
												"who_ordered_cpnum"	=> $data_post['who_ordered_cpnum'],
												"staff_member_fname"	=> $staff_member_fname,
												"staff_member_lname"	=> $staff_member_lname,
												"comment"				=> $data_post['comment'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	        => $data_post['dropdown_deliver_type'],
												'medical_record_id' => $data_post['patient_mrn'],
												"order_status"	    => "pending"
											);
										}
									}
								}
						}

						$saveorder = $this->order_model->saveorder($orders);

						if($saveorder)
						{

							$insert_to_status = array(
								'order_uniqueID'      => $unique_id,
								'medical_record_id'   => $data_post['patient_mrn'],
								'patientID'			  => $save_patient_info,
								'status_activity_typeid' => $activity_type,
								'order_status'   	  => "pending",
							);

							$this->order_model->insert_to_status($insert_to_status);


							//2 pickup
							//4 PT
							//5 Respite
							// if($activity_type==2)
							// {
							// 	$pickup_type_sub = array(
							// 		'pickup_sub' => $data_post['pickup_sub'],
							// 		'date'	 	 => "{$date_post['pickup_date']}",
							// 		'patientID'	 => $save_patient_info,
							// 		'uniqueID'	 => $unique_id
							// 	);
							// 	$this->order_model->save($pickup_type_sub,'dme_pickup');
							// }
							// else if($activity_type==4)
							// {
							// 	$ptmove_type_sub = array(
							// 		'ptmove_street'   => $data_post['ptmove_address'],
							// 		'ptmove_placenum' => $data_post['ptmove_placenum'],
							// 		'ptmove_city'     => $data_post['ptmove_city'],
							// 		'ptmove_state'    => $data_post['ptmove_state'],
							// 		'ptmove_postal'   => $data_post['ptmove_postalcode'],
							// 		'patientID'	 	  => $save_patient_info
							// 	);
							// 	$this->order_model->save($ptmove_type_sub,'dme_sub_ptmove');
							// }
							// else if($activity_type==5)
							// {
							// 	$respite_type_sub = array(
							// 		'respite_pickup_date'   => $data_post['respite_delivery_date'],
							// 		'respite_address'   	=> $data_post['respite_address'],
							// 		'respite_placenum'		=> $data_post['respite_placenum'],
							// 		'respite_city'     		=> $data_post['respite_city'],
							// 		'respite_state'    		=> $data_post['respite_state'],
							// 		'respite_postal'   		=> $data_post['respite_postalcode'],
							// 		'parentID'	 			=> $save_patient_info
							// 	); 
							// 	$this->order_model->save($respite_type_sub,'dme_sub_respite');
							// }

							$this->response_code = 0;
							$this->response_message = "Patient Created Successfully.";

							/*

							*	For email

							*/
	
							if($account_type_name != "dme_admin" && $account_type_name != "dme_user")
							{
								$email_form = $this->form_email_temp($unique_id,1,TRUE);
								$this->load->config('email');
								$config =   $this->config->item('me_email');
								$this->load->library('email', $config); 

								$this->email->from('orders@smarterchoice.us','AHMSLV');  
								$this->email->to('orders@ahmslv.com');
								$this->email->bcc('saavedra.ted@gmail.com');    
								$this->email->subject('AHMSLV | Order Summary');
								$this->email->message($email_form);
								$this->email->send();
							}
							
								
	      //                   $email_form = $this->form_email_temp($unique_id, TRUE);

							// $this->load->config('email');
	      //                   $config =   $this->config->item('me_email');
	      //                   $this->load->library('email', $config); 
	      //                   $this->email->from('orders@ahmslv.com','AHMSLV');  
	      //                   // $this->email->to('john@smartstartlv.com');  
							// // $this->email->cc('Rchinaadvantage@aol.com');  
							// $this->email->to('saavedra.ted@gmail.com');  
							// $this->email->cc('ivan.kirby.colina@gmail.com');  
	      //                   $this->email->subject('AHMSLV | Order Summary');
	      //                   $this->email->message($email_form);
	      //                  	$this->email->send();
	                        
						}
				}
				else
				{
					$this->response_message = "Error saving patient information.";
				}
			}
			else
			{	
				$this->response_message = validation_errors('<span></span>');
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}


	public function form_email_temp($uniqueID,$act_id,$return=false)
	{
			$data['act_type_id'] = $act_id;

			$informations = $this->order_model->get_order_info_for_email($uniqueID);

			if($act_id == 2)
			{
				$activity_type_data = $this->order_model->get_act_type_pickup($uniqueID);
			}

			if($act_id == 3)
			{
				$activity_type_data = $this->order_model->get_act_type_exchange($uniqueID);
			}

			if($act_id == 4)
			{
				$activity_type_data = $this->order_model->get_act_type_ptmove($uniqueID);
			}

			if($act_id == 5)
			{
				$activity_type_data = $this->order_model->get_act_type_respite($uniqueID);
			}

			$data['informations'] = $informations;
			$data['activity_fields'] = $activity_type_data;

			$id = $uniqueID;
		    $response = $this->order_model->get_orders_email($uniqueID);
	        $orders = array();
	        if(!empty($response))
	        {
	            foreach($response as $key=>$value)
	            {
	                $cat_ = $value['type'];
	                if($value['parentID']==0)
	                {
	                    $orders[$cat_][trim($value['key_desc'])][] = $value;
	                }
	                else
	                {
	                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
	                }
	            }
	        }
			$data['orders'] = $orders;
			$content = ''; 

			$this->templating_library->set('title','Email for Order Summary');

			$top = $this->templating_library->set_view('common/head','common/head', $data, TRUE);
			$header = $this->templating_library->set_view('common/header','common/header', $data , TRUE);
			$content = $this->templating_library->set_view('pages/email_template', 'pages/email_template', $data , TRUE);
			$footer  =	$this->templating_library->set_view('common/footer','common/footer', $data ,TRUE);
			$bottom = $this->templating_library->set_view('common/foot','common/foot', $data ,TRUE);	

			if($return)
			{
				$final = $top.$content; 
				return $final;
			}
			else
			{
				echo $top.$header.$content.$footer.$bottom;
			}
	}


	public function insert_order_comments()
	{
		$this->form_validation->set_rules('comment','Comment','required|trim');
		$post_data = $this->input->post();

		$array_data = array(
			'comment'		 => $post_data['comment'],
			'order_uniqueID' => $post_data['order_uniqueID'],
			'userID'		 => $post_data['commented_by'],
			'userName'       => $post_data['commented_by_name']
		);

		if($this->form_validation->run() == TRUE)
		{
			$this->order_model->insert_order_comments($array_data);

			//** For the response (include_bottom.php)
			$this->response_code 		= 0;
			$this->response_message		= "Comment inserted successfully.";
		}
		else
		{
			$this->response_code 		= 1;
			$this->response_message		= "Please put comment if necessary.";
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function count_order_comments($uniqueID)
	{
		$result = $this->order_model->count_order_comments($uniqueID);
		echo json_encode($result);
	}

	public function get_comments($uniqueID)
	{
		$data['uniqueID'] = $uniqueID;
		$data['comments'] = $this->order_model->get_all_comments($uniqueID);
		$this->load->view('pages/comment_thread', $data);
	}

	public function pickup($unique_id="")
    {
        if(!empty($unique_id))
        {
            if($this->input->post())
            {
                $activity_type = $_POST['activity_type'];
                if($activity_type==2)
                {
                    $this->form_validation->set_rules("pickup_date","Date",'required');
                    $this->form_validation->set_rules("equipments[]","Equipments",'required');
                    $this->form_validation->set_rules("pickup_sub_cat","Pickup reason",'required');
                    if($this->form_validation->run()===TRUE)
                    {
                        $data_post = $this->input->post();
                        $data = array(
                                        "pickup_sub"    => $data_post['pickup_sub_cat'],
                                        "date"          => "{$data_post['pickup_date']}",
                                        "equipments"    => json_encode($data_post['equipments']),
                                        "orderUniqueID" => $unique_id,
                                        "pickup_status" => "pending"
                                    );
                        $save = $this->order_model->save_pickup_data($data,$unique_id);
                        if($save)
                        {
                            $this->response_code = 0;
                            $this->response_message = "Successfully saved.";
                        }
                        else
                        {
                            $this->response_message = "Failed to add. Please contact website administrator.";
                        }
                    }
                    else
                    {
                        $this->response_message = validation_errors("<span></span>");
                    }
                }
                else
                {
                    $this->response_message = "No received data.";
                }
            }
        }
        else
        {
            $this->response_message = "Not allowed";
        }
        echo json_encode(array(
            "error"     => $this->response_code,
            "message"   => $this->response_message
        ));
    }


    public function delete_order($uniqueID)
	{
		$results = $this->order_model->list_orders($uniqueID);

		$array = array();
		foreach($results as $result)
		{
			$array[] = array(
				"patientID"			=> $result['patientID'],
				"equipmentID"		=> $result['equipmentID'],
				"equipment_value"	=> $result['equipment_value'],
				"pickup_date"		=> $result['pickup_date'],
				"activity_typeid"	=> $result['activity_typeid'],
				"organization_id"	=> $result['organization_id'],
				"ordered_by"		=> $result['ordered_by'],
				"comment"			=> $result['comment'],
				"date_ordered"		=> $result['date_ordered'],
				"uniqueID"			=> $result['uniqueID'],
				"order_status"		=> $result['order_status'],
			);
		}

		$from_where_table = "dme_order";
		$array_encoded = json_encode($array);

		$inserted_data = array(
			"from_where_table" => $from_where_table,
			"data_deleted"	   => $array_encoded
		);

		$delete_entry = $this->order_model->delete_order($uniqueID);
		$trash_added = $this->order_model->save_to_trash($inserted_data);

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Successfully deleted.";

		echo json_encode(array(
					"error"		=> $this->response_code,
					"message"	=> $this->response_message
		));
	}



	public function delete_confirmed_order($uniqueID)
	{
		$results = $this->order_model->list_confirmed_orders($uniqueID);

		$array = array();
		foreach($results as $result)
		{
			$array[] = array(
				"patientID"			=> $result['patientID'],
				"equipmentID"		=> $result['equipmentID'],
				"equipment_value"	=> $result['equipment_value'],
				"pickup_date"		=> $result['pickup_date'],
				"activity_typeid"	=> $result['activity_typeid'],
				"organization_id"	=> $result['organization_id'],
				"ordered_by"		=> $result['ordered_by'],
				"comment"			=> $result['comment'],
				"date_ordered"		=> $result['date_ordered'],
				"uniqueID"			=> $result['uniqueID'],
				"order_status"		=> $result['order_status'],
			);
		}

		$from_where_table = "dme_order";
		$array_encoded = json_encode($array);

		$inserted_data = array(
			"from_where_table" => $from_where_table,
			"data_deleted"	   => $array_encoded
		);

		$delete_entry = $this->order_model->delete_order($uniqueID);
		$trash_added = $this->order_model->save_to_trash($inserted_data);

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Successfully deleted.";

		echo json_encode(array(
					"error"		=> $this->response_code,
					"message"	=> $this->response_message
		));
	}

	public function delete_trash($trashID)
	{
		if($trashID != '')
		{
			$this->order_model->delete_from_trash($trashID);
		}

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Permanently Deleted.";
		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function delete_patient_records($patientID,$medical_record_id)
	{
		if($medical_record_id != '')
		{
			$deleted = $this->order_model->delete_patient_records($patientID, $medical_record_id);
			
			if($deleted)
			{
				$this->response_code 		= 0;
				$this->response_message		= "Patient Records Deleted.";
			}
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function update_summary_tbl($uniqueID, $equipmentID)
	{
		$this->form_validation->set_rules("item_num","Item Number","xss_clean");
		$this->form_validation->set_rules("serial_num","Serial Number","xss_clean");
		$this->form_validation->set_rules("lot_num", "Lot Number", "xss_clean");

		$qty = $this->input->post("qty");
		$item_num = $this->input->post("item_num");
		$serial_num = $this->input->post("serial_num");
		$lot_num = $this->input->post("lot_num");
		$pickup_date = date($this->input->post("pickup_date"));
		$order_date = date($this->input->post("order_date"));

		if($this->form_validation->run() == TRUE)
		{
			$array = array(
				'equipment_value'=> $qty,
				'item_num'		 => $item_num,
				'serial_num'	 => $serial_num,
				'lot_num'		 => $lot_num,
				'summary_pickup_date' => $pickup_date,
				'pickup_date' => $order_date
			);

			$insert = $this->order_model->update_order_summary($uniqueID, $equipmentID ,$array);
			if($insert)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Updated Entry.";
			}
			else
			{
				$this->response_message = "Failed to update Entry.";
			}
		}	
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}
		echo json_encode(array(
				"error" 	=> $this->response_code,
				"message"	=> $this->response_message
			));
		exit;
	}

	public function add_additional_equipments($patientID)
	{
		$account_type_name = $this->session->userdata('account_type');
		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_cpnum','Staff Cellphone No.','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('equipments[]','Add new item(s)','required');
		$this->form_validation->set_rules('activity_type','Activity Type','required');
		$this->form_validation->set_rules('delivery_date','Delivery Date','required');
		
		$person_who_ordered = $this->session->userdata('email');

		$id = $this->session->userdata('userID');
		if($this->input->post())
		{
			
			$data_post = $this->input->post();

			if($this->form_validation->run()===TRUE)
			{
				$unique_id = strtotime(date('Y-m-d H:i:s'));
				$medical_id = $data_post['medical_record_id'];

				if($unique_id!="")
				{
					/*
					* order information
					*/
					$delivery_date 		= date($data_post['delivery_date']);
					$ordered_by  		= $id;
					$organization_id 	= $data_post['organization_id'];
					$activity_type		= $data_post['activity_type'];
					
					//order items
					$orders = array();
					foreach($data_post['equipments'] as $key=>$value)
					{
							$orders[] = array(				

											"patientID"				=> $patientID,			
											"equipmentID"			=> $value,		
											"equipment_value"		=> 1,
											"pickup_date"			=> $delivery_date,
											"activity_typeid"		=> $activity_type,	
											"organization_id"		=> $organization_id,
											"ordered_by"			=> $id,
											"who_ordered_fname"		=> $data_post['who_ordered_fname'],
											"who_ordered_lname"		=> $data_post['who_ordered_lname'],
											"staff_member_fname"	=> $data_post['staff_member_fname'],
											"staff_member_lname"	=> $data_post['staff_member_lname'],
											"who_ordered_email"		=> $data_post['who_ordered_email'],
											"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
											"comment"				=> $data_post['new_order_notes'],
											"uniqueID"				=> $unique_id,
											"deliver_to_type"	    => $data_post['delivery_to_type'],
											'medical_record_id' 	=> $data_post['medical_record_id'],
											"order_status"	        => 'pending',
											//"initial_order"			=> 1
										);
						
					}
					/*
					* @sub equipments  
					*
					*/
					foreach($data_post['subequipment'] as $key=>$value)
					{
						
						if(in_array($key,$data_post['equipments']))
						{
							foreach($value as $sub_key=>$sub_value)
							{
								
								if($sub_key=="radio")
								{
									foreach($sub_value as $radio_value)
									{
										
										$orders[] = array(				
											"patientID"				=> $patientID,			
											"equipmentID"			=> $radio_value,		
											"equipment_value"		=> 1,
											"pickup_date"			=> $delivery_date,
											"activity_typeid"		=> $activity_type,	
											"organization_id"		=> $organization_id,
											"ordered_by"			=> $id,
											"who_ordered_fname"		=> $data_post['who_ordered_fname'],
											"who_ordered_lname"		=> $data_post['who_ordered_lname'],
											"staff_member_fname"	=> $data_post['staff_member_fname'],
											"staff_member_lname"	=> $data_post['staff_member_lname'],
											"who_ordered_email"		=> $data_post['who_ordered_email'],
											"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
											"comment"				=> $data_post['new_order_notes'],
											"uniqueID"				=> $unique_id,
											"deliver_to_type"	    => $data_post['delivery_to_type'],
											'medical_record_id' 	=> $data_post['medical_record_id'],
											"order_status"	        => 'pending',
											//"initial_order"			=> 1
										);
									}
								}
								else
								{
									$orders[] = array(				
										"patientID"				=> $patientID,			
										"equipmentID"			=> $sub_key,		
										"equipment_value"		=> $sub_value,
										"pickup_date"			=> $delivery_date,
										"activity_typeid"		=> $activity_type,	
										"organization_id"		=> $organization_id,
										"ordered_by"			=> $id,
										"who_ordered_fname"		=> $data_post['who_ordered_fname'],
										"who_ordered_lname"		=> $data_post['who_ordered_lname'],
										"staff_member_fname"	=> $data_post['staff_member_fname'],
										"staff_member_lname"	=> $data_post['staff_member_lname'],
										"who_ordered_email"		=> $data_post['who_ordered_email'],
										"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
										"comment"				=> $data_post['new_order_notes'],
										"uniqueID"				=> $unique_id,
										"deliver_to_type"	    => $data_post['delivery_to_type'],
										'medical_record_id' 	=> $data_post['medical_record_id'],
										"order_status"	        => 'pending',
										//"initial_order"			=> 1
									);
								}
							}

						}
					}
					
					$saveorder = $this->order_model->saveorder($orders);

					if($saveorder)
					{
						$insert_to_status = array(
							'order_uniqueID'      => $unique_id,
							'medical_record_id'   => $data_post['medical_record_id'],
							'patientID'			  => $patientID,
							'status_activity_typeid' => $activity_type,
							'order_status'   	  => "pending",
						);

						$this->order_model->insert_to_status($insert_to_status);

						//2 pickup
						//4 PT
						//5 Respite
						if($activity_type==2)
						{	
							$pickup_type_sub = array(
								'pickup_sub'   => $data_post['pickup_sub_cat'],
								'pickup_date' => date($data_post['pickup_pickup_date']),
								'medical_record_id'	 => $data_post['medical_record_id'],
								'order_uniqueID'	 => $unique_id,
							);
							$this->order_model->save($pickup_type_sub,'dme_pickup_tbl');
						}
						else if($activity_type==4)
						{
							$ptmove_type_sub = array(
								'ptmove_street'   => $data_post['ptmove_address'],
								'ptmove_placenum' => $data_post['ptmove_placenum'],
								'ptmove_city'     => $data_post['ptmove_city'],
								'ptmove_state'    => $data_post['ptmove_state'],
								'ptmove_postal'   => $data_post['ptmove_postalcode'],
								'medical_record_id'	 	  => $data_post['medical_record_id'],
							);
							$this->order_model->save($ptmove_type_sub,'dme_sub_ptmove');
						}
						else if($activity_type==3)
						{
							$exchange_type_sub = array(
								'exchange_date'		 => date($data_post['exchange_date']),
								'exchange_reason'	 => $data_post['exchange_reason'],
								'medical_record_id'	 => $data_post['medical_record_id'],							
							);
							$this->order_model->save($exchange_type_sub,'dme_sub_exchange');
						}
						else if($activity_type==5)
						{
							$respite_type_sub = array(
								'respite_delivery_date'   => date($data_post['respite_delivery_date']),
								'respite_pickup_date'   => date($data_post['respite_pickup_date']),
								'respite_address'   	=> $data_post['respite_address'],
								'respite_placenum'		=> $data_post['respite_placenum'],
								'respite_city'     		=> $data_post['respite_city'],
								'respite_state'    		=> $data_post['respite_state'],
								'respite_postal'   		=> $data_post['respite_postalcode'],
								'medical_record_id'	 	=> $data_post['medical_record_id'],
							); 
							$this->order_model->save($respite_type_sub,'dme_sub_respite');
						}

						$this->response_code = 0;
						$this->response_message = "Activity submitted successfully.";


						/*
						*	For email
						*/
						
						
						if($account_type_name != "dme_admin" && $account_type_name != "dme_user")
						{
							$email_form = $this->form_email_temp($unique_id,1,TRUE);
							$this->load->config('email');
							$config =   $this->config->item('me_email');
							$this->load->library('email', $config); 

							$this->email->from('orders@smarterchoice.us','AHMSLV');  
							$this->email->to('orders@ahmslv.com');
							$this->email->bcc('saavedra.ted@gmail.com');    
							$this->email->subject('AHMSLV | Order Summary');
							$this->email->message($email_form);
							$this->email->send();
						}
						
						
                        
					}
				}
				else
				{
					$this->response_message = "Error saving information.";
				}
			}
			else
			{	
				$this->response_message = validation_errors('<span></span>');
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}

	public function update_status_to_pickup($patientID)
	{
		$account_type_name = $this->session->userdata('account_type');
		
		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('pickup_sub_cat','Pickup Reason','required');
		$this->form_validation->set_rules('pickup_pickup_date','Pickup Date','required');
		$this->form_validation->set_rules('pickup_equipments[]','Items','required');
		$this->form_validation->set_rules('activity_type','Activity Type','required');

		$id = $this->session->userdata('userID');

		if($this->input->post())
		{
			$person_who_ordered = $this->session->userdata('email');
			$data_post = $this->input->post();

			if($this->form_validation->run()===TRUE)
			{
				$unique_id = strtotime(date('Y-m-d H:i:s'));
				$post_uniqueID  = $data_post['unique_id'];
				
				if(empty($data_post['pickup_respite_address']))
				{
					$pickup_respite_address = "";
				}
				else
				{
					$pickup_respite_address = $data_post['pickup_respite_address'];
				}

			
				if($post_uniqueID!="")
				{
					/*
					* order information
					*/
					$pickup_date 		= date($data_post['pickup_pickup_date']);
					$ordered_by  		= $id;
					$organization_id 	= $data_post['organization_id'];
					$activity_type		= 2;
					$unique_ids			= "";
						
					$equipment_ids = $data_post['pickup_equipments'];

					if(isset($data_post['hdn_pickup_uniqueID']))
					{
						$unique_ids    = $data_post['hdn_pickup_uniqueID'];
					}

					if(isset($data_post['hdn_uniqueid_ptmove']))
					{
						$ptmove_old_uniqueID = $data_post['hdn_uniqueid_ptmove'];
					}

					if(isset($data_post['ptmove_old_address']))
					{
						$ptmove_pickup_address_id = $data_post['ptmove_old_address'];
					}	


					if(empty($data_post['pickup_respite_address']))
					{
						// IF WE WILL NOT PICKUP A RESPITE ORDER THEN GO HERE

						//order items
						$orders = array();
						foreach($data_post['pickup_equipments'] as $key=>$value)
						{
							
								$orders[] = array(				
									"patientID"				=> $patientID,			
									"equipmentID"			=> $value,		
									"equipment_value"		=> 1,
									"pickup_date"			=> $pickup_date,
									"activity_typeid"		=> $activity_type,	
									"organization_id"		=> $organization_id,
									"ordered_by"			=> $id,
									"who_ordered_fname"		=> $data_post['who_ordered_fname'],
									"who_ordered_lname"		=> $data_post['who_ordered_lname'],
									"staff_member_fname"	=> $data_post['staff_member_fname'],
									"staff_member_lname"	=> $data_post['staff_member_lname'],
									"who_ordered_email"		=> $data_post['who_ordered_email'],
									"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
									"comment"				=> $data_post['new_pickup_notes'],
									"uniqueID"				=> $unique_id,
									"deliver_to_type"	    => $data_post['delivery_to_type'],
									'medical_record_id' 	=> $data_post['medical_record_id'],
									"serial_num"			=> "pickup_order_only",
									"order_status"	        => "pending",
									"pickup_order"			=> 1,
									"initial_order"			=> 0,
									"original_activity_typeid" => 2,
									"activity_reference"		=> 2 //remove if it will cause errors (newly added)
								);
						}
						/*
						* @sub equipments  
						*
						*/
						if(!empty($data_post['equip_options']))
						{
							foreach($data_post['equip_options'] as $key=>$value)
							{
								if(in_array($key,$data_post['pickup_equipments']))
								{
									foreach($value as $sub_key=>$sub_value)
	              					{
	              						foreach($sub_value as $val)
	              						{
	              							$orders[] = array(				
												"patientID"				=> $patientID,			
												"equipmentID"			=> $val,		
												"equipment_value"		=> 1,
												"pickup_date"			=> $pickup_date,
												"activity_typeid"		=> $activity_type,	
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['who_ordered_fname'],
												"who_ordered_lname"		=> $data_post['who_ordered_lname'],
												"staff_member_fname"	=> $data_post['staff_member_fname'],
												"staff_member_lname"	=> $data_post['staff_member_lname'],
												"who_ordered_email"		=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
												"comment"				=> $data_post['new_pickup_notes'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['delivery_to_type'],
												'medical_record_id' 	=> $data_post['medical_record_id'],
												"serial_num"			=> "pickup_order_only",
												"order_status"	        => "pending",
												"pickup_order"			=> 1,
												"initial_order"			=> 0,
												"original_activity_typeid" => 2,
												"activity_reference"		=> 2 //remove if it will cause errors (newly added)
											);
	              						}
									}
								}
							}
						}


						$create_new_order = $this->order_model->saveorder($orders);

						$activity_type_todo = 2;

						$updating_data = array(
							"activity_typeid" 		=> $activity_type,
							"date_ordered"			=> date('Y-m-d H:i:s'),
							"activity_reference"	=> 2,
							"pickedup_uniqueID"	 	=> $unique_id
						);

						$updateorder = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids, $unique_ids, $activity_type_todo, $ptmove_old_uniqueID);

						if($updateorder)
						{
							$insert_to_status = array(
								'order_uniqueID'      => $unique_id,
								'medical_record_id'   => $data_post['medical_record_id'],
								'patientID'			 =>  $patientID,
								'status_activity_typeid' => $activity_type,
								'order_status'   	  => "pending",
							);
							$this->order_model->insert_to_status($insert_to_status);

							$pickup_type_sub = array(
								'pickup_sub'  		 => $data_post['pickup_sub_cat'],
								'date_pickedup' 		 => date($data_post['pickup_pickup_date']),
								'medical_record_id'	 => $data_post['medical_record_id'],
								'patientID'			 =>  $patientID,
								'pickup_respite_address' => $pickup_respite_address,
								'order_uniqueID'	 => $unique_id,
							);
							$this->order_model->save($pickup_type_sub,'dme_pickup_tbl');



							if($ptmove_pickup_address_id == 0)
							{
								if(isset($data_post['ptmove_new_address']))
								{
									$ptmove_new_address_id = $data_post['ptmove_new_address'];
								}

								$update_ptmove_entry = array(
									'ptmove_old_address_pickedup' => 1
								);
								$this->order_model->updateptmove_entry($ptmove_new_address_id,$update_ptmove_entry);
							}

							else
							{
								$update_ptmove_entry = array(
									'ptmove_pickedup' => 1,
									'ptmove_old_address_pickedup' => 1
								);
								$this->order_model->updateptmove_entry($ptmove_pickup_address_id,$update_ptmove_entry);
							}
						}

						$this->response_code = 0;
						$this->response_message = "Activity submitted successfully.";

						/*
						*	For email
						*/
						
						if($account_type_name != "dme_admin" && $account_type_name != "dme_user")
						{
							$email_form = $this->form_email_temp($unique_id,2,TRUE);
							$this->load->config('email');
							$config =   $this->config->item('me_email');
							$this->load->library('email', $config); 
							
							$this->email->from('orders@smarterchoice.us','AHMSLV');  
							$this->email->to('orders@ahmslv.com');
							$this->email->bcc('saavedra.ted@gmail.com');    
							$this->email->subject('AHMSLV | Order Summary');
							$this->email->message($email_form);
							$this->email->send();
						}
						
					}

					else
					{
						// IF WE WILL PICKUP A RESPITE ORDER THEN GO HERE

						/*
						* order information
						*/
						$ordered_by  		= $id;
						$organization_id 	= $data_post['organization_id'];
						$activity_type		= 2;

						//order items
						$orders = array();
						foreach($data_post['pickup_equipments'] as $key=>$value)
						{
							$orders[] = array(				
								"patientID"				=> $patientID,			
								"equipmentID"			=> $value,		
								"equipment_value"		=> 1,
								"pickup_date"			=> $pickup_date,
								"activity_typeid"		=> $activity_type,	
								"organization_id"		=> $organization_id,
								"ordered_by"			=> $id,
								"who_ordered_fname"		=> $data_post['who_ordered_fname'],
								"who_ordered_lname"		=> $data_post['who_ordered_lname'],
								"staff_member_fname"	=> $data_post['staff_member_fname'],
								"staff_member_lname"	=> $data_post['staff_member_lname'],
								"who_ordered_email"		=> $data_post['who_ordered_email'],
								"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
								"comment"				=> $data_post['new_pickup_notes'],
								"uniqueID"				=> $unique_id,
								"deliver_to_type"	    => $data_post['delivery_to_type'],
								'medical_record_id' 	=> $data_post['medical_record_id'],
								"order_status"	        => "pending",
								"serial_num"			=> "pickup_order_only",
								"pickup_order"			=> 1,
								"initial_order"			=> 0,
								"pickedup_respite_order" => 1,
								"original_activity_typeid" => 2,
								"activity_reference"		=> 2 //remove if it will cause errors (newly added)
							);
						}
						/*
						* @sub equipments  
						*
						*/
						foreach($data_post['subequipment'] as $key=>$value)
						{
							
							if(in_array($key,$data_post['pickup_equipments']))
							{
								foreach($value as $sub_key=>$sub_value)
								{
									if($sub_key=="radio")
									{
										foreach($sub_value as $radio_value)
										{
											
											$orders[] = array(				
												"patientID"				=> $patientID,			
												"equipmentID"			=> $radio_value,		
												"equipment_value"		=> 1,
												"pickup_date"			=> $pickup_date,
												"activity_typeid"		=> $activity_type,	
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['who_ordered_fname'],
												"who_ordered_lname"		=> $data_post['who_ordered_lname'],
												"staff_member_fname"	=> $data_post['staff_member_fname'],
												"staff_member_lname"	=> $data_post['staff_member_lname'],
												"who_ordered_email"		=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
												"comment"				=> $data_post['new_pickup_notes'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['delivery_to_type'],
												'medical_record_id' 	=> $data_post['medical_record_id'],
												"order_status"	        => "pending",
												"serial_num"			=> "pickup_order_only",
												"pickup_order"			=> 1,
												"initial_order"			=> 0,
												"pickedup_respite_order" => 1,
												"original_activity_typeid" => 2,
												"activity_reference"		=> 2 //remove if it will cause errors (newly added)
											);
										}
									}
									else
									{
											$orders[] = array(				
												"patientID"				=> $patientID,			
												"equipmentID"			=> $sub_key,		
												"equipment_value"		=> $sub_value,
												"pickup_date"			=> $pickup_date,
												"activity_typeid"		=> $activity_type,	
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['who_ordered_fname'],
												"who_ordered_lname"		=> $data_post['who_ordered_lname'],
												"staff_member_fname"	=> $data_post['staff_member_fname'],
												"staff_member_lname"	=> $data_post['staff_member_lname'],
												"who_ordered_email"		=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
												"comment"				=> $data_post['new_pickup_notes'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['delivery_to_type'],
												'medical_record_id' 	=> $data_post['medical_record_id'],
												"order_status"	        => "pending",
												"serial_num"			=> "pickup_order_only",
												"pickup_order"			=> 1,
												"initial_order"			=> 0,
												"pickedup_respite_order" => 1,
												"original_activity_typeid" => 2,
												"activity_reference"		=> 2 //remove if it will cause errors (newly added)
											);
										}
									}
								}
						}	

						

						$create_new_order = $this->order_model->saveorder($orders);
						
						if($create_new_order)
						{
							if(empty($data_post['pickup_respite_address']))
							{

								if(isset($data_post['hdn_uniqueid_ptmove']))
								{
									$ptmove_old_uniqueID = $data_post['hdn_uniqueid_ptmove'];
								}


								$insert_to_status = array(
									'order_uniqueID'      => $unique_id,
									'medical_record_id'   => $data_post['medical_record_id'],
									'patientID'			 =>  $patientID,
									'status_activity_typeid' => $activity_type,
									'order_status'   	  => "pending",
								);
								$this->order_model->insert_to_status($insert_to_status);
								

								$pickup_type_sub = array(
									'pickup_sub'  		 => $data_post['pickup_sub_cat'],
									'date_pickedup' 		 => date($data_post['pickup_pickup_date']),
									'medical_record_id'	 => $data_post['medical_record_id'],
									'patientID'			 =>  $patientID,
									'pickup_respite_address' => $pickup_respite_address,
									'order_uniqueID'	 => $unique_id,
								);

								$inserted_pickup_tbl = $this->order_model->save($pickup_type_sub,'dme_pickup_tbl');

								if($inserted_pickup_tbl)
								{
									$activity_type_todo = 2;
									$equipment_ids = $data_post['pickup_equipments'];

									$updating_data = array(
										"activity_reference"	=> 2,
										"pickedup_uniqueID"	 	=> $unique_id
									);

									$updateorder = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids, $unique_ids , $activity_type_todo, $ptmove_old_uniqueID);

									$this->response_code = 0;
									$this->response_message = "Activity submitted successfully.";
								}
							}


							else
							{
								$activity_type_todo = 2;
								$orig_act_type = !empty($data_post['hdn_orig_act_type_pickup']) ? $data_post['hdn_orig_act_type_pickup'] : "";

								$insert_to_status = array(
									'order_uniqueID'      => $unique_id,
									'medical_record_id'   => $data_post['medical_record_id'],
									'status_activity_typeid' => $activity_type,
									'order_status'   	  => "pending",
								);
								$this->order_model->insert_to_status($insert_to_status);

								$pickup_type_sub = array(
									'pickup_sub'  		 => $data_post['pickup_sub_cat'],
									'date_pickedup' 		 => date($data_post['pickup_pickup_date']),
									'medical_record_id'	 => $data_post['medical_record_id'],
									'patientID'			 =>  $patientID,
									'pickup_respite_address' => $pickup_respite_address,
									'order_uniqueID'	 => $unique_id,
								);
								
								$inserted_pickup_tbl = $this->order_model->save($pickup_type_sub,'dme_pickup_tbl');

								if($inserted_pickup_tbl)
								{
									$equipment_ids = $data_post['pickup_equipments'];

									if($orig_act_type == 3)
									{
										$updating_data = array(
											//"pickedup_respite_order"	=> 1,
											"activity_typeid"		=> 2,
											"activity_reference"		=> 2,
											"pickedup_respite_order" => 1
										);
									}
									else
									{
										$updating_data = array(
											//"pickedup_respite_order"	=> 1,
											"activity_reference"		=> 2,
											"pickedup_respite_order" => 1,
											"uniqueID_reference"     => $unique_id,
											"pickedup_uniqueID"		=> $unique_id
										);
									}

									$updateorder = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids, $unique_ids , $activity_type_todo);

									if($updateorder)
									{
										$respite_data = array(
											"respite_pickedup" => 1
										);

										$this->order_model->update_order_respite($data_post['medical_record_id'], $respite_data);
									}
								}
								$this->response_code = 0;
								$this->response_message = "Activity submitted successfully.";
							}
							

							/*
							*	For email
							*/
							
							if($account_type_name != "dme_admin" && $account_type_name!="dme_user")
							{
								$email_form = $this->form_email_temp($unique_id,2,TRUE);

								$this->load->config('email');
								$config =   $this->config->item('me_email');
								$this->load->library('email', $config); 
								
								$this->email->from('orders@smarterchoice.us','AHMSLV');  
								$this->email->to('orders@ahmslv.com');
								$this->email->bcc('saavedra.ted@gmail.com');    
								$this->email->subject('AHMSLV | Order Summary');
								$this->email->message($email_form);
								$this->email->send();
							}
						}
					}					
				}
				else
				{
					$this->response_message = "Error saving information.";
				}
			}
			else
			{	
				$this->response_message = validation_errors('<span></span>');
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}


	public function change_status_to_exchange($patientID)
	{
		$account_type_name = $this->session->userdata('account_type');
		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_cpnum','Staff Cellphone No.','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('exchange_reason','Reason for Exchange','required');
		$this->form_validation->set_rules('exchange_date','Exchange Delivery Date','required');
		$this->form_validation->set_rules('exchange_equipments[]','Items','required');
		$this->form_validation->set_rules('activity_type','Activity Type','required');

		$id = $this->session->userdata('userID');

		if($this->input->post())
		{
			$person_who_ordered = $this->session->userdata('email');
			$data_post = $this->input->post();

			$original_act_typeid = !empty($data_post['hdn_orig_act_type']) ? $data_post['hdn_orig_act_type'] : ""; //to determine kung respite items ba ang iyang i exchange

			
			if($original_act_typeid == 5)
			{
				$act_reference = 5;
			}
			else
			{
				$act_reference = 3;
			}


			if($this->form_validation->run()===TRUE)
			{
				$unique_id = strtotime(date('Y-m-d H:i:s'));
				$post_uniqueID  = $data_post['uniqueID'];

				if($post_uniqueID!="")
				{
					/*
					* order information
					*/
					$exchange_date 		= date($data_post['exchange_date']);
					$ordered_by  		= $id;
					$organization_id 	= $data_post['organization_id'];
					$activity_type		= 2;
					

					//order items
					$orders = array();
					foreach($data_post['exchange_equipments'] as $key=>$value)
					{
							$orders[] = array(				

								"patientID"				=> $patientID,			
								"equipmentID"			=> $value,		
								"equipment_value"		=> 1,
								"pickup_date"			=> $exchange_date,
								"activity_typeid"		=> 3,	
								"organization_id"		=> $organization_id,
								"ordered_by"			=> $id,
								"who_ordered_fname"		=> $data_post['who_ordered_fname'],
								"who_ordered_lname"		=> $data_post['who_ordered_lname'],
								"staff_member_fname"	=> $data_post['staff_member_fname'],
								"staff_member_lname"	=> $data_post['staff_member_lname'],
								"who_ordered_email"		=> $data_post['who_ordered_email'],
								"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
								"comment"				=> $data_post['new_exchange_notes'],
								"uniqueID"				=> $unique_id,
								"deliver_to_type"	    => $data_post['delivery_to_type'],
								'medical_record_id' 	=> $data_post['medical_record_id'],
								"order_status"	        => 'pending',
								"activity_reference"	=> $act_reference,
								"initial_order"			=> 0,
								"original_activity_typeid" => 3
							);
					}
					/*
					* @sub equipments  
					*
					*/
					if(!empty($data_post['equip_options_exchange']))
					{
						foreach($data_post['equip_options_exchange'] as $key=>$value)
						{
							if(in_array($key,$data_post['exchange_equipments']))
							{
								foreach($value as $sub_key=>$sub_value)
              					{
              						foreach($sub_value as $val)
              						{
              							$orders[] = array(				
											"patientID"				=> $patientID,			
											"equipmentID"			=> $val,		
											"equipment_value"		=> 1,
											"pickup_date"			=> $exchange_date,
											"activity_typeid"		=> 3,	
											"organization_id"		=> $organization_id,
											"ordered_by"			=> $id,
											"who_ordered_fname"		=> $data_post['who_ordered_fname'],
											"who_ordered_lname"		=> $data_post['who_ordered_lname'],
											"staff_member_fname"	=> $data_post['staff_member_fname'],
											"staff_member_lname"	=> $data_post['staff_member_lname'],
											"who_ordered_email"		=> $data_post['who_ordered_email'],
											"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
											"comment"				=> $data_post['new_exchange_notes'],
											"uniqueID"				=> $unique_id,
											"deliver_to_type"	    => $data_post['delivery_to_type'],
											'medical_record_id' 	=> $data_post['medical_record_id'],
											"order_status"	        => 'pending',
											"activity_reference"	=> $act_reference,
											"initial_order"			=> 0,
											"original_activity_typeid" => 3
										);
              						}
								}
							}
						}
					}


					$create_new_order = $this->order_model->saveorder($orders);

					if($create_new_order)
					{

						$activity_type_todo = 3;
						$equipment_ids = $data_post['exchange_equipments'];
						$unique_ids = !empty($data_post['hdn_exchange_uniqueID']) ? $data_post['hdn_exchange_uniqueID'] : "";

						//para ma-consider if ang item nga i exchange kay gikan sa respite nga activity type
						if($original_act_typeid == 5)
						{
							$updating_data = array(
								"pickedup_respite_order"	=> 1,
								"deliver_to_type"			=> $data_post['delivery_to_type'],
								"date_ordered"				=> date('Y-m-d H:i:s'),
								"activity_reference"	    => 3,
								"uniqueID_reference"		=> $unique_id
							);
						} 

						else
						{
							$updating_data = array(
								"activity_typeid" 			=> $activity_type,
								"deliver_to_type"			=> $data_post['delivery_to_type'],
								"date_ordered"				=> date('Y-m-d H:i:s'),
								"activity_reference"	    => 3,
								"uniqueID_reference"		=> $unique_id
							);
						}

						$updateorder = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids, $unique_ids, $activity_type_todo);

						if($updateorder)
						{
							$insert_to_status = array(
								'order_uniqueID'      => $unique_id,
								'medical_record_id'   => $data_post['medical_record_id'],
								'patientID'			 =>  $patientID,
								'status_activity_typeid'   => 3,
								'order_status'   	  => "pending",
							);

							$this->order_model->insert_to_status($insert_to_status);


							$exchange_type_sub = array(
								'exchange_date'		 => date($data_post['exchange_date']),
								'exchange_reason'	 => $data_post['exchange_reason'],
								'medical_record_id'	 => $data_post['medical_record_id'],	
								'patientID'			 =>  $patientID,	
								'order_uniqueID'	 => $unique_id,					
							);
							$this->order_model->save($exchange_type_sub,'dme_sub_exchange');
							$this->response_code = 0;
							$this->response_message = "Activity submitted successfully.";

							/*
							*	For email
							*/
							
							
							if($account_type_name != "dme_admin" && $account_type_name!="dme_user")
							{
								$email_form = $this->form_email_temp($unique_id,3,TRUE);

								$this->load->config('email');
								$config =   $this->config->item('me_email');
								$this->load->library('email', $config); 
								
								$this->email->from('orders@smarterchoice.us','AHMSLV');  
								$this->email->to('orders@ahmslv.com');
								$this->email->bcc('saavedra.ted@gmail.com');    
								$this->email->subject('AHMSLV | Order Summary');
								$this->email->message($email_form);
								$this->email->send();
							}
						}
					}
				}
				else
				{
					$this->response_message = "Error saving information.";
				}
			}
			else
			{	
				$this->response_message = validation_errors('<span></span>');
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}

	public function change_status_to_ptmove($patientID)
	{
		$account_type_name = $this->session->userdata('account_type');
		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_cpnum','Staff Cellphone No.','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('ptmove_delivery_date','PT Move Delivery Date','required');
		$this->form_validation->set_rules('ptmove_address','PT Move Address','required');
		$this->form_validation->set_rules('ptmove_nextofkin','Next of Kin','required');
		$this->form_validation->set_rules('ptmove_nextofkinrelation','Next of Kin Relationship','required');
		$this->form_validation->set_rules('ptmove_nextofkinphone','Next of Kin Phone Number','required');
		$this->form_validation->set_rules('ptmove_patient_residence','Patient Residence','required');


		$this->form_validation->set_rules('equipments[]','Items','required');
		$this->form_validation->set_rules('activity_type','Activity Type','required');

		$id = $this->session->userdata('userID');

		if($this->input->post())
		{
			$person_who_ordered = $this->session->userdata('email');
			$data_post = $this->input->post();

			if($this->form_validation->run()===TRUE)
			{
				$unique_id = strtotime(date('Y-m-d H:i:s'));
				$pickup_old_items_uniqueid = rand(1000000000,9999999999);
				$post_uniqueID  = $data_post['uniqueID'];

				if($post_uniqueID!="")
				{
					/*
					* order information
					*/
					$ptmove_date 		= date($data_post['ptmove_delivery_date']);
					$ordered_by  		= $id;
					$organization_id 	= $data_post['organization_id'];
					$activity_type		= 2;
					

					//order items
					$orders = array();
					foreach($data_post['equipments'] as $key=>$value)
					{
							$orders[] = array(				

							"patientID"				=> $patientID,			
							"equipmentID"			=> $value,		
							"equipment_value"		=> 1,
							"pickup_date"			=> $ptmove_date,
							"activity_typeid"		=> 4,	
							"organization_id"		=> $organization_id,
							"ordered_by"			=> $id,
							"who_ordered_fname"		=> $data_post['who_ordered_fname'],
							"who_ordered_lname"		=> $data_post['who_ordered_lname'],
							"staff_member_fname"	=> $data_post['staff_member_fname'],
							"staff_member_lname"	=> $data_post['staff_member_lname'],
							"who_ordered_email"		=> $data_post['who_ordered_email'],
							"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
							"comment"				=> $data_post['new_order_notes'],
							"uniqueID"				=> $unique_id,
							"deliver_to_type"	    => $data_post['delivery_to_type'],
							'medical_record_id' 	=> $data_post['medical_record_id'],
							"order_status"	        => 'pending',
							"initial_order"			=> 0,
							"original_activity_typeid" => 4
						);
						
					}
					/*
					* @sub equipments  
					*
					*/
					foreach($data_post['subequipment'] as $key=>$value)
					{
						
						if(in_array($key,$data_post['equipments']))
						{
							foreach($value as $sub_key=>$sub_value)
							{
								if($sub_key=="radio")
								{
									foreach($sub_value as $radio_value)
									{
										
										$orders[] = array(				
											"patientID"				=> $patientID,			
											"equipmentID"			=> $radio_value,		
											"equipment_value"		=> 1,
											"pickup_date"			=> $ptmove_date,
											"activity_typeid"		=> 4,	
											"organization_id"		=> $organization_id,
											"ordered_by"			=> $id,
											"who_ordered_fname"		=> $data_post['who_ordered_fname'],
											"who_ordered_lname"		=> $data_post['who_ordered_lname'],
											"staff_member_fname"	=> $data_post['staff_member_fname'],
											"staff_member_lname"	=> $data_post['staff_member_lname'],
											"who_ordered_email"		=> $data_post['who_ordered_email'],
											"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
											"comment"				=> $data_post['new_order_notes'],
											"uniqueID"				=> $unique_id,
											"deliver_to_type"	    => $data_post['delivery_to_type'],
											'medical_record_id' 	=> $data_post['medical_record_id'],
											"order_status"	        => 'pending',
											"initial_order"			=> 0,
											"original_activity_typeid" => 4
										);
									}
								}
								else
								{
									$orders[] = array(				
										"patientID"				=> $patientID,			
										"equipmentID"			=> $sub_key,		
										"equipment_value"		=> $sub_value,
										"pickup_date"			=> $ptmove_date,
										"activity_typeid"		=> 4,	
										"organization_id"		=> $organization_id,
										"ordered_by"			=> $id,
										"who_ordered_fname"		=> $data_post['who_ordered_fname'],
										"who_ordered_lname"		=> $data_post['who_ordered_lname'],
										"staff_member_fname"	=> $data_post['staff_member_fname'],
										"staff_member_lname"	=> $data_post['staff_member_lname'],
										"who_ordered_email"		=> $data_post['who_ordered_email'],
										"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
										"comment"				=> $data_post['new_order_notes'],
										"uniqueID"				=> $unique_id,
										"deliver_to_type"	    => $data_post['delivery_to_type'],
										'medical_record_id' 	=> $data_post['medical_record_id'],
										"order_status"	        => 'pending',
										"initial_order"			=> 0,
										"original_activity_typeid" => 4
									);
								}
							}
						}
					}


					$create_new_order = $this->order_model->saveorder($orders);


					/** Create new ticket for Pickup of Old Items **/
					$pickups = array();
					$pickup_old_items_date = $data_post['ptmove_old_items_pickup_date'];
					//$drivers_name    = $data_post['ptmove_pickup_items_driver'];
					$equipment_ids = $data_post['pickup_ptmove_equipments'];

					if(isset($data_post['hdn_uniqueid_ptmove_old_items']))
					{
						$old_items_uniqueids = $data_post['hdn_uniqueid_ptmove_old_items'];
					}

					foreach($data_post['pickup_ptmove_equipments'] as $key=>$value)
					{
						
							$pickups[] = array(				
								"patientID"				=> $patientID,			
								"equipmentID"			=> $value,		
								"equipment_value"		=> 1,
								"pickup_date"			=> $pickup_old_items_date,
								"activity_typeid"		=> $activity_type,	
								"organization_id"		=> $organization_id,
								"ordered_by"			=> $id,
								"who_ordered_fname"		=> $data_post['who_ordered_fname'],
								"who_ordered_lname"		=> $data_post['who_ordered_lname'],
								"staff_member_fname"	=> $data_post['staff_member_fname'],
								"staff_member_lname"	=> $data_post['staff_member_lname'],
								"who_ordered_email"		=> $data_post['who_ordered_email'],
								"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
								"comment"				=> $data_post['new_pickup_notes'],
								"uniqueID"				=> $pickup_old_items_uniqueid,
								"deliver_to_type"	    => $data_post['delivery_to_type'],
								'medical_record_id' 	=> $data_post['medical_record_id'],
								"serial_num"			=> "pickup_order_only",
								"summary_pickup_date"   => $pickup_old_items_date,
								//"driver_name"			=> $drivers_name,
								"order_status"	        => "pending",
								"pickup_order"			=> 1,
								"initial_order"			=> 0,
								"original_activity_typeid" => 2,
								"activity_reference"		=> 2 //remove if it will cause errors (newly added)
							);
					}
					/*
					* @sub equipments  
					*
					*/
					if(!empty($data_post['equip_options']))
					{
						foreach($data_post['equip_ptmove_options'] as $key=>$value)
						{
							if(in_array($key,$data_post['pickup_ptmove_equipments']))
							{
								foreach($value as $sub_key=>$sub_value)
              					{
              						foreach($sub_value as $val)
              						{
              							$pickups[] = array(				
											"patientID"				=> $patientID,			
											"equipmentID"			=> $val,		
											"equipment_value"		=> 1,
											"pickup_date"			=> $pickup_old_items_date,
											"activity_typeid"		=> $activity_type,	
											"organization_id"		=> $organization_id,
											"ordered_by"			=> $id,
											"who_ordered_fname"		=> $data_post['who_ordered_fname'],
											"who_ordered_lname"		=> $data_post['who_ordered_lname'],
											"staff_member_fname"	=> $data_post['staff_member_fname'],
											"staff_member_lname"	=> $data_post['staff_member_lname'],
											"who_ordered_email"		=> $data_post['who_ordered_email'],
											"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
											"comment"				=> $data_post['new_pickup_notes'],
											"uniqueID"				=> $pickup_old_items_uniqueid,
											"deliver_to_type"	    => $data_post['delivery_to_type'],
											'medical_record_id' 	=> $data_post['medical_record_id'],
											"serial_num"			=> "pickup_order_only",
											"summary_pickup_date"   => $pickup_old_items_date,
											//"driver_name"			=> $drivers_name,
											"order_status"	        => "pending",
											"pickup_order"			=> 1,
											"initial_order"			=> 0,
											"original_activity_typeid" => 2,
											"activity_reference"		=> 2 //remove if it will cause errors (newly added)
										);
              						}
								}
							}
						}
					}
					$save_pickup_old_items = $this->order_model->saveorder($pickups);

					if($save_pickup_old_items)
					{
						$activity_type_todo = 2;

						$updating_data = array(
							"activity_typeid" 		=> $activity_type,
							"date_ordered"			=> date('Y-m-d H:i:s'),
							"activity_reference"	=> 2,
							"summary_pickup_date"   => $pickup_old_items_date,
							//"driver_name"			=> $drivers_name,
							"pickedup_uniqueID"	 	=> $pickup_old_items_uniqueid
						);
						$updateorder = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids, $old_items_uniqueids, $activity_type_todo, $old_items_uniqueids);

						$pickup_type_sub = array(
							'pickup_sub'  		 => "not needed",
							'date_pickedup' 		 => $pickup_old_items_date,
							'medical_record_id'	 => $data_post['medical_record_id'],
							'patientID'			 =>  $patientID,
							'pickup_respite_address' => "",
							'order_uniqueID'	 => $pickup_old_items_uniqueid,
						);
						$this->order_model->save($pickup_type_sub,'dme_pickup_tbl');
					}


					if($create_new_order)
					{
						$activity_type_todo = 4;
						$equipment_ids = $data_post['equipments'];

						$updating_data = array(
							//"activity_typeid" 		=> $activity_type,
							"deliver_to_type"		=> $data_post['delivery_to_type'],
							"date_ordered"			=> date('Y-m-d H:i:s'),
							//"activity_reference"	=> 4,
							"uniqueID_reference"	=> $unique_id
						);
						$updateorder = $this->order_model->updateorder_ptmove($patientID, $updating_data, $post_uniqueID, $activity_type_todo);

						if($updateorder)
						{
							$insert_to_status = array(
								'order_uniqueID'      => $unique_id,
								'medical_record_id'   => $data_post['medical_record_id'],
								'patientID'			 =>  $patientID,
								'status_activity_typeid'   => 4, //tanggala ang comment if mag cause ug error
								'order_status'   	  => "pending",
							);

							$inserted_successful = $this->order_model->insert_to_status($insert_to_status);

							if($inserted_successful)
							{
								$ptmove_type_sub = array(
									'ptmove_delivery_date'	  => $data_post['ptmove_delivery_date'],
									'ptmove_street'   		  => $data_post['ptmove_address'],
									'ptmove_placenum' 		  => $data_post['ptmove_placenum'],
									'ptmove_city'     		  => $data_post['ptmove_city'],
									'ptmove_state'    		  => $data_post['ptmove_state'],
									'ptmove_postal'   		  => $data_post['ptmove_postalcode'],
									'ptmove_patient_residence'     => $data_post['ptmove_patient_residence'],
									'ptmove_nextofkin'         => $data_post['ptmove_nextofkin'],
									'ptmove_nextofkinrelation'  => $data_post['ptmove_nextofkinrelation'],
									'ptmove_nextofkinphone'     => $data_post['ptmove_nextofkinphone'],
									'ptmove_patient_phone'		=> $data_post['ptmove_patient_phone'],
									'medical_record_id'	 	  => $data_post['medical_record_id'],
									'patientID'			 =>  $patientID,
									'order_uniqueID'	 	  => $unique_id,		
								);
								$this->order_model->save($ptmove_type_sub,'dme_sub_ptmove');
								$this->response_code = 0;
								$this->response_message = "Activity submitted successfully.";

								/*
								*	For email
								*/
								
								if($account_type_name!= "dme_admin" && $account_type_name!="dme_user")
								{
									$email_form = $this->form_email_temp($unique_id,4,TRUE);
									$this->load->config('email');
									$config =   $this->config->item('me_email');
									$this->load->library('email', $config); 
									
									$this->email->from('orders@smarterchoice.us','AHMSLV');  
									$this->email->to('orders@ahmslv.com');
									$this->email->bcc('saavedra.ted@gmail.com');    
									$this->email->subject('AHMSLV | Order Summary');
									$this->email->message($email_form);
									$this->email->send();
								}
						    }
						}
					}
				}
				else
				{
					$this->response_message = "Error saving information.";
				}
			}
			else
			{	
				$this->response_message = validation_errors('<span></span>');
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}

	public function change_status_to_respite($patientID)
	{
		$account_type_name = $this->session->userdata('account_type');
		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_cpnum','Staff Cellphone No.','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('respite_delivery_date','Respite Delivery Date','required');
		$this->form_validation->set_rules('respite_pickup_date','Respite Pickup Date','required');
		$this->form_validation->set_rules('respite_phone_number','Respite Phone Number','required');
		$this->form_validation->set_rules('respite_address','Respite Address','required');
		$this->form_validation->set_rules('dropdown_deliver_type','Respite Patient Residence','required');


		$this->form_validation->set_rules('equipments[]','Items','required');
		$this->form_validation->set_rules('activity_type','Activity Type','required');

		$id = $this->session->userdata('userID');
		if($this->input->post())
		{
			$person_who_ordered = $this->session->userdata('email');
			$data_post = $this->input->post();

			if($this->form_validation->run()===TRUE)
			{
				$unique_id = strtotime(date('Y-m-d H:i:s'));

				if($unique_id!="")
				{
					/*
					* order information
					*/
					$delivery_date 		= date($data_post['respite_delivery_date']);
					$ordered_by  		= $id;
					$organization_id 	= $data_post['organization_id'];
					$activity_type		= $data_post['activity_type'];
						
					
					//order items
					$orders = array(); 
					foreach($data_post['equipments'] as $key=>$value)
					{
							$orders[] = array(				
							"patientID"				=> $patientID,			
							"equipmentID"			=> $value,		
							"equipment_value"		=> 1,
							"pickup_date"			=> $delivery_date,
							"activity_typeid"		=> $activity_type,	
							"organization_id"		=> $organization_id,
							"ordered_by"			=> $id,
							"who_ordered_fname"		=> $data_post['who_ordered_fname'],
							"who_ordered_lname"		=> $data_post['who_ordered_lname'],
							"staff_member_fname"	=> $data_post['staff_member_fname'],
							"staff_member_lname"	=> $data_post['staff_member_lname'],
							"who_ordered_email"		=> $data_post['who_ordered_email'],
							"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
							"comment"				=> $data_post['new_order_notes'],
							"uniqueID"				=> $unique_id,
							"deliver_to_type"	    => $data_post['delivery_to_type'],
							'medical_record_id' 	=> $data_post['medical_record_id'],
							"order_status"	        => 'pending',
							"initial_order"			=> 0,
							"original_activity_typeid" => 5
						);
						
					}
					/*
					* @sub equipments  
					*
					*/
					foreach($data_post['subequipment'] as $key=>$value)
					{
						
						if(in_array($key,$data_post['equipments']))
						{
							foreach($value as $sub_key=>$sub_value)
							{
								if($sub_key=="radio")
								{
									foreach($sub_value as $radio_value)
									{
										
										$orders[] = array(				
											"patientID"				=> $patientID,			
											"equipmentID"			=> $radio_value,		
											"equipment_value"		=> 1,
											"pickup_date"			=> $delivery_date,
											"activity_typeid"		=> $activity_type,	
											"organization_id"		=> $organization_id,
											"ordered_by"			=> $id,
											"who_ordered_fname"		=> $data_post['who_ordered_fname'],
											"who_ordered_lname"		=> $data_post['who_ordered_lname'],
											"staff_member_fname"	=> $data_post['staff_member_fname'],
											"staff_member_lname"	=> $data_post['staff_member_lname'],
											"who_ordered_email"		=> $data_post['who_ordered_email'],
											"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
											"comment"				=> $data_post['new_order_notes'],
											"uniqueID"				=> $unique_id,
											"deliver_to_type"	    => $data_post['delivery_to_type'],
											'medical_record_id' 	=> $data_post['medical_record_id'],
											"order_status"	        => 'pending',
											"initial_order"			=> 0,
											"original_activity_typeid" => 5
										);
									}
								}
								else
								{
									$orders[] = array(				
										"patientID"				=> $patientID,			
										"equipmentID"			=> $sub_key,		
										"equipment_value"		=> $sub_value,
										"pickup_date"			=> $delivery_date,
										"activity_typeid"		=> $activity_type,	
										"organization_id"		=> $organization_id,
										"ordered_by"			=> $id,
										"who_ordered_fname"		=> $data_post['who_ordered_fname'],
										"who_ordered_lname"		=> $data_post['who_ordered_lname'],
										"staff_member_fname"	=> $data_post['staff_member_fname'],
										"staff_member_lname"	=> $data_post['staff_member_lname'],
										"who_ordered_email"		=> $data_post['who_ordered_email'],
										"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
										"comment"				=> $data_post['new_order_notes'],
										"uniqueID"				=> $unique_id,
										"deliver_to_type"	    => $data_post['delivery_to_type'],
										'medical_record_id' 	=> $data_post['medical_record_id'],
										"order_status"	        => 'pending',
										"initial_order"			=> 0,
										"original_activity_typeid" => 5
									);
								}
							}
						}
					}

					
					$saveorder = $this->order_model->saveorder($orders);

					if($saveorder)
					{
						$insert_to_status = array(
							'order_uniqueID'      => $unique_id,
							'medical_record_id'   => $data_post['medical_record_id'],
							'patientID'			 =>  $patientID,
							'status_activity_typeid'  => $activity_type,
							'order_status'   	  => "pending",
						);
						$this->order_model->insert_to_status($insert_to_status);

						$respite_type_sub = array(
							'respite_delivery_date' => date($data_post['respite_delivery_date']),
							'respite_pickup_date'   => date($data_post['respite_pickup_date']),
							'respite_address'   	=> $data_post['respite_address'],
							'respite_placenum'		=> $data_post['respite_placenum'],
							'respite_city'     		=> $data_post['respite_city'],
							'respite_state'    		=> $data_post['respite_state'],
							'respite_postal'   		=> $data_post['respite_postalcode'],
							'respite_deliver_to_type' => $data_post['dropdown_deliver_type'],
							'medical_record_id'	 	=> $data_post['medical_record_id'],
							'patientID'			 =>  $patientID,
							'respite_phone_number'	=> $data_post['respite_phone_number'],
							'order_uniqueID'	 	=> $unique_id,
						); 
						$this->order_model->save($respite_type_sub,'dme_sub_respite');

						$this->response_code = 0;
						$this->response_message = "Activity submitted successfully.";
						/*
						*	For email
						*/
						
						if($account_type_name != "dme_admin" && $account_type_name!="dme_user")
						{
							$email_form = $this->form_email_temp($unique_id,5,TRUE);

							$this->load->config('email');
							$config =   $this->config->item('me_email');
							$this->load->library('email', $config); 

							$this->email->from('orders@smarterchoice.us','AHMSLV');  
							$this->email->to('orders@ahmslv.com');
							$this->email->bcc('saavedra.ted@gmail.com');    
							$this->email->subject('AHMSLV | Order Summary');
							$this->email->message($email_form);
							$this->email->send();
						}
					}
				}	
				else
				{
					$this->response_message = "Error saving information.";
				}
			}
			else
			{	
				$this->response_message = validation_errors('<span></span>');
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}	

	public function search()
	{
		$data_get = $this->input->get();

		$data['term'] = (isset($get_data['term'])) ? $get_data['term'] : '';
		$data['active_nav'] = "patient_menu";
		echo $data['term'];	
		$this->templating_library->set('title','Search Patient');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/search_page','pages/search_page', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function search_patients($search_string='', $hospice_id='')
	{
		$post_data = $this->input->post();
		$created_by = $this->session->userdata('group_id');

		if($this->session->userdata('account_type') == 'dme_admin')
		{
			$searches = $this->order_model->search_patients($search_string);
			
		}
		else
		{
			$searches = $this->order_model->search_patients_hospice($search_string, $created_by);
		}
		
		if($searches)
		{
			foreach ($searches as $search) 
			{
				echo "<tr style='cursor:pointer;border:1px solid #f5f5f5' class='patient_results form-control' data-id='".$search['medical_record_id']."' data-value='".$search['medical_record_id']."' data-fname='".$search['p_fname']."' data-lname='".$search['p_lname']."' data-patient-id='".$search['patientID']."'><td>".$search['medical_record_id'].""." - "."".$search['p_lname']."".", "."".$search['p_fname']."</td></tr>";
			}
		}
		else
		{
			echo "<tr style='cursor:pointer;border:1px solid #f5f5f5' class='form-control'><td>No Results Found.</td></tr>";
		}
	}

	public function return_search($view_type = "")
	{
		$data_get = $this->input->get();
		
		$data['term'] = (isset($data_get['term'])) ? $data_get['term'] : '';
		$data['query'] = (isset($data_get['query'])) ? $data_get['query'] : '';
		$data['param'] = (isset($data_get['param'])) ? $data_get['param'] : '';
		$data['param2'] = (isset($data_get['param2'])) ? $data_get['param2'] : '';

		$where = array(
			'term' => str_replace(' ', '%', $data['term']),
			'query' => str_replace(' ', '%', $data['query']),
			'param' => str_replace(' ', '%', $data['param']),
			'param2' => str_replace(' ', '%', $data['param2'])
		);

		$result = $this->order_model->list_orders_for_patient_search($uniqueID="",$where);
		$data['order_status_list'] = true;
		$data['orders'] = ($result) ? $result : FALSE;
		$data['counts'] = count($this->order_model->list_orders_for_patient_search($uniqueID="",$where));

		$this->templating_library->set('title','Patient Order Summary');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		if($view_type == 'list-view')
		{
			$this->templating_library->set_view('pages/patient_order_list','pages/patient_order_list', $data);
		}
		else
		{
			$this->templating_library->set_view('pages/patient_order_grid','pages/patient_order_grid', $data);
		}
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function get_lot_comments($equipmentID, $uniqueID)
	{
		$data['comments'] = $this->order_model->get_comments($equipmentID,$uniqueID);
		// printA($data['comments']);
		// exit;
		$data['results'] = $this->order_model->return_lot_info($equipmentID, $uniqueID);
		$this->load->view('pages/lot_comments', $data);
	}


	public function add_lot_comment()
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('lot_comment','Lot # Comment','required');

		if($data_post)
		{
			if($this->form_validation->run())
			{
				$array = array(
					'userID' => $data_post['userID'] ,
					'equipmentID' => $data_post['equipmentID'],
					'uniqueID'	 => $data_post['uniqueID'],
					'lot_comment'	=> $data_post['lot_comment']
				);

				$success = $this->order_model->add_lot_comment($array);

				$this->response_code = 0;
				$this->response_message = "Successfully Inserted Notes.";
				
			}
			else
			{
				$this->response_message = "Failed to insert notes.";
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}
		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;
	}

	public function new_equipment($uniqueID, $hospice_id)
	{
		$user_type = $this->session->userdata('account_type');
		
		$returns = $this->order_model->list_orders($uniqueID);
		$informations = $this->order_model->get_order_info($uniqueID, $hospice_id); //newly added $hospice_id. 07/13/2015.
		
		$data['comments'] = $this->order_model->get_all_comments($uniqueID);
		$hospiceID = $this->session->userdata('group_id');

		$datas = array();
		$data['summarys'] = $returns;
		$data['informations'] = $informations;

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				if($hospice_id != 0)
				{
					$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospice_id);
				}
				else
				{	
					$children = $this->order_model->get_equipment($cat['categoryID']);
				}	
				
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}

			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
			// if($user_type == 'dme_admin')
			// {
			// 	$children = $this->order_model->get_equipment($cat['categoryID']);
			// }
			// else
			// {
			// 	$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			// }
			// $equipment_array[] = array(
			// 	'categoryID' => $cat['categoryID'],
			// 	'type'		 => $cat['type'],
			// 	'children'	 => $children,
			// 	'division'	 => floor(count($children)/2),
			// 	'last'	 	 => count($children)-1
			// );
		}
		$data['equipments'] = $equipment_array;

	    $id = $uniqueID;

        $response = $this->order_model->get_orders_for_pickup_items($id, $hospice_id);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
             
                if($value['parentID']==0)
                {
                  $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                  $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }

        $data['orders'] = $orders;

        $data['addresses'] = $this->order_model->list_addresses($uniqueID, $hospice_id);

        $data['ptmove_address'] = $this->order_model->get_old_address_ptmove($uniqueID, $hospice_id); //comment if it will cause errors $hospice_id added on 07-13-2015

        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $uniqueID;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id); 
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }


		$this->templating_library->set('title','Activity Type');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/add_new_equipment','pages/add_new_equipment' , $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function edit_patient_info($medical_record_id, $hospice_id='')
	{
		$user_type = $this->session->userdata('account_type');
		
		$returns = $this->order_model->list_orders($medical_record_id);
		$informations = $this->order_model->get_order_info($medical_record_id, $hospice_id);
		$data['comments'] = $this->order_model->get_all_comments($medical_record_id);

		$datas = array();
		$data['summarys'] = $returns;
		$data['informations'] = $informations;

		$data['note_counts'] = $this->order_model->count_patient_comments($medical_record_id);

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $medical_record_id;

        $response = $this->order_model->get_orders($id, $hospice_id);
        $order_summary = $this->order_model->get_orders($id, $hospice_id);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        $data['summaries'] = $order_summary;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_record_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id); 
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }

		$data['infos'] = $this->order_model->get_patient_profile($medical_record_id, $hospice_id);

		if(empty($data['infos']))
		{
			$data['infos'] = $this->order_model->get_patient_profile($medical_record_id, $hospice_id);
		}
        
		$this->templating_library->set('title','Edit Patient Info');
		$this->templating_library->set_view('pages/edit_patient_profile','pages/edit_patient_profile', $data);
		$this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
	}

	public function confirmed_order_details($medical_record_id, $uniqueID, $act_id="", $hospiceID="")
	{
		$user_type = $this->session->userdata('account_type');
		
		$returns = $this->order_model->list_orders($medical_record_id);
		$informations = $this->order_model->get_order_info($medical_record_id, $hospiceID);
		$data['comments'] = $this->order_model->get_all_comments($medical_record_id);

		$datas = array();
		$data['summarys'] = $returns;
		$data['informations'] = $informations;

		$data['medical_record_num'] = $medical_record_id;
		$data['hospice_id'] = $hospiceID;	
		$data['act_id'] = $act_id;

		$data['note_counts'] = $this->order_model->count_patient_comments($medical_record_id);

		$data['work_order_number'] = $uniqueID;

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $medical_record_id;

        $response = $this->order_model->get_orders($id);
        $order_summary = $this->order_model->get_orders_by_workorder($id, $uniqueID, $hospiceID);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        $data['summaries'] = $order_summary;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_record_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id); 
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }
		$data['infos'] = $this->order_model->get_patient_profile($medical_record_id, $hospiceID);
		$data['work_order_number'] = $uniqueID;
		$this->templating_library->set('title','Edit Patient Info');
		$this->templating_library->set_view('pages/confirmed_modal','pages/confirmed_modal', $data);
		//$this->templating_library->set_view('common/foot','common/foot'); //put this back if it causes some errors.
		$this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
	}



	public function confirmed_order_details_exchange($medical_record_id, $uniqueID, $act_id="", $hospiceID="")
	{
		$user_type = $this->session->userdata('account_type');
		
		$returns = $this->order_model->list_orders($medical_record_id);
		$informations = $this->order_model->get_order_info($medical_record_id, $hospiceID);
		$data['comments'] = $this->order_model->get_all_comments($medical_record_id);

		$datas = array();
		$data['summarys'] = $returns;
		$data['informations'] = $informations;

		$data['act_id'] = $act_id;

		$data['note_counts'] = $this->order_model->count_patient_comments($medical_record_id);

		$data['work_order_number'] = $uniqueID;

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $medical_record_id;

        $response = $this->order_model->get_orders($id);
        $order_summary = $this->order_model->get_orders_by_workorder_exchange($id, $uniqueID, $hospiceID);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        $data['summaries'] = $order_summary;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_record_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id); 
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }
		$data['infos'] = $this->order_model->get_patient_profile($medical_record_id, $hospiceID);
		$data['work_order_number'] = $uniqueID;
		$this->templating_library->set('title','Edit Patient Info');
		$this->templating_library->set_view('pages/confirmed_modal_exchange','pages/confirmed_modal_exchange', $data);
		//$this->templating_library->set_view('common/foot','common/foot');
		$this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
	}



	public function update_patient_profile($medical_record_id)
	{
		$data_post = $this->input->post();
		$act_id = $data_post['act_typeid'];
		$patient_id = $data_post['hdn_patient_id'];
		$edited_by = $this->session->userdata('userID');
		
		$this->form_validation->set_rules('organization_id', 'Hospice Provider', 'required');


		if($this->form_validation->run())
		{
			$order_data = array(
				//'patientID'			=> $data_post['update_patient_id'],
				'medical_record_id' => $data_post['medical_record_id'],
				'organization_id' => $data_post['organization_id'],
				'deliver_to_type' => $data_post['deliver_to_type'],
			);

			$saved_data = $this->order_model->update_order_profile($medical_record_id, $order_data);

			if($saved_data)
			{
				$patient_data = array(
					'p_fname' => $data_post['p_fname'],
					'p_lname' => $data_post['p_lname'],
					'p_street' => $data_post['p_street'],
					'p_placenum' => $data_post['p_placenum'],
					'p_city' => $data_post['p_city'],
					'p_state' => $data_post['p_state'],
					'p_postalcode' => $data_post['p_postalcode'],
					'p_height' => $data_post['height'],
					'p_weight' => $data_post['weight'],
					'p_phonenum' => $data_post['phonenum'],
					'p_altphonenum' => $data_post['altphonenum'],
					'p_nextofkin' => $data_post['nextofkin'],
					'p_relationship' => $data_post['relationship'],
					'p_nextofkinnum' => $data_post['nextofkinnum'],
					'relationship_gender' => $data_post['relationship_gender'],
					'medical_record_id' => $data_post['medical_record_id']
				);

				$original_patient_data = $this->order_model->get_original_patient_info($patient_id);

				$logs = array();

				
				foreach($original_patient_data as $key=>$value)
				{
					if(isset($patient_data[$key]))
					{
						if($patient_data[$key] != $value)
						{
							$logs[] = array(
								"patientID" => $patient_id,
								"old_value" => $value,
								"new_value" => $patient_data[$key],
								"edited_column_name" => $key,
								"edited_by"	=> $edited_by
							);
						}
					}
				}  

				if(!empty($logs))
				{
					$this->order_model->insert_to_patient_logs($logs);
				}

				$saved_patient_info = $this->order_model->update_patient_profile($medical_record_id, $patient_data);

				if($saved_patient_info)
				{

					if($act_id == 4)
					{
						$ptmove_address = array(
							"ptmove_medical_record_id" => $data_post['medical_record_id'],
							"patientID" => $data_post['update_patient_id'],
							"ptmove_street"     => $data_post['pt_street'],
							"ptmove_placenum"	=> $data_post['pt_placenum'],
							"ptmove_city"		=> $data_post['pt_city'],
							"ptmove_state"	    => $data_post['pt_state'],
							"ptmove_postal" 	=> $data_post['pt_postalcode'],
							"ptmove_patient_phone" => $data_post['pt_phone'],
							"ptmove_uniqueID"  => $data_post['ptmove_unique_id'],
							"ptmove_patient_residence" => $data_post['deliver_to_type'],
							"ptmove_nextofkin" => $data_post['nextofkin'],
							"ptmove_relationship" => $data_post['relationship'],
							"ptmove_phonenum" => $data_post['nextofkinnum']
						);

						$this->order_model->insert_ptmove_table($ptmove_address);
					}
					
					$this->response_code = 0;
					$this->response_message = "Successfully Updated Information.";
				}
				else
				{
					$this->response_message = "Failed to Update Information.";	
				}
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}
		
		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;

	}

	public function update_patient_profile_toedit($medical_record_id)
	{
		$data_post = $this->input->post();

		
		$act_id = $data_post['act_typeid'];
		$edited_by = $this->session->userdata('userID');
		
		//$driver_name_post = $data_post['driver_name'];
		
		$this->form_validation->set_rules('organization_id', 'Hospice Provider', 'required');


		if($this->form_validation->run())
		{
			$order_data = array(
				'medical_record_id' => $data_post['medical_record_id'],
				'organization_id' => $data_post['organization_id'],
				'deliver_to_type' => $data_post['deliver_to_type'],
				//'driver_name' => $driver_name_post,
			);
			$saved_data = $this->order_model->update_order_profile($medical_record_id, $order_data);

			if($saved_data)
			{
				$patient_data = array(
					'p_fname' => $data_post['p_fname'],
					'p_lname' => $data_post['p_lname'],
					'p_street' => $data_post['p_street'],
					'p_placenum' => $data_post['p_placenum'],
					'p_city' => $data_post['p_city'],
					'p_state' => $data_post['p_state'],
					'p_postalcode' => $data_post['p_postalcode'],
					'p_height' => $data_post['height'],
					'p_weight' => $data_post['weight'],
					'p_phonenum' => $data_post['phonenum'],
					'p_altphonenum' => $data_post['altphonenum'],
					'p_nextofkin' => $data_post['nextofkin'],
					'p_relationship' => $data_post['relationship'],
					'p_nextofkinnum' => $data_post['nextofkinnum'],
					'medical_record_id' => $data_post['medical_record_id'],
					'edited_by'			=> $edited_by,
				);

				$saved_patient_info = $this->order_model->update_patient_profile_toedit($medical_record_id, $patient_data);

				if($saved_patient_info)
				{
					if($act_id == 4)
					{
						$ptmove_address = array(
							"ptmove_medical_record_id" => $data_post['medical_record_id'],
							"ptmove_street"     => $data_post['pt_street'],
							"ptmove_placenum"	=> $data_post['pt_placenum'],
							"ptmove_city"		=> $data_post['pt_city'],
							"ptmove_state"	    => $data_post['pt_state'],
							"ptmove_postal" 	=> $data_post['pt_postalcode'],
						);

						$this->order_model->insert_ptmove_table($ptmove_address);
					}
					
					$this->response_code = 0;
					$this->response_message = "Successfully Updated Information.";
				}
				else
				{
					$this->response_message = "Failed to Update Information.";	
				}
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}
		
		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;

	}


	function show_patient_notes($medical_record_id, $p_fname, $p_lname, $hospice_name, $patientID)
	{
		$data['id']  = $medical_record_id;
		$data['notes'] = $this->order_model->retrieve_patient_notes($medical_record_id, $patientID);
		$data['p_fname'] = $p_fname;
		$data['p_lname'] = $p_lname;
		$data['hospice_name'] = urldecode($hospice_name);
		$data['patientID']	 = $patientID;

		$this->templating_library->set('title','Patient Notes');
		$this->templating_library->set_view('pages/patient_notes','pages/patient_notes', $data);
		$this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
	}

	function save_patient_notes($medical_record_id)
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('patient_notes','Patient Notes','required|xss_clean');
		
		if($this->form_validation->run())
		{
			$array = array(
				'medical_record_id' => $data_post['medical_record_id'],
				'patientID'			=> $data_post['patientID'],
				'notes' => $data_post['patient_notes'],
				'noted_by' => $data_post['noted_by']
			);

			$inserted = $this->order_model->insert_patient_note($array);

			if($inserted)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Inserted Patient Note.";
			}
			else
			{
				$this->response_message = "Failed to Insert Patient Note.";
			}
		}
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}

		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;
	}

	public function update_pickup_order($medical_record_id)
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('who_ordered_cpnum','Cellphone No.','integer');
		$this->form_validation->set_rules('equipments[]','Items','required');

		if($this->form_validation->run())
		{
					$pickup_date 		= date($data_post['pickup_date']);
					$ordered_by  		= $id;
					$organization_id 	= $data_post['organization_id'];
					$activity_type		= $data_post['activity_type'];

					//order items
					$orders = array();
					foreach($data_post['equipments'] as $key=>$value)
					{
							$orders[] = array(				

											"patientID"				=> $patientID,			
											"equipmentID"			=> $value,		
											"equipment_value"		=> 1,
											"pickup_date"			=> $pickup_date,
											"activity_typeid"		=> $activity_type,	
											"organization_id"		=> $organization_id,
											"ordered_by"			=> $id,
											"who_ordered_fname"		=> $data_post['who_ordered_fname'],
											"who_ordered_lname"		=> $data_post['who_ordered_lname'],
											"staff_member_fname"	=> $data_post['staff_member_fname'],
											"staff_member_lname"	=> $data_post['staff_member_lname'],
											"who_ordered_email"		=> $data_post['who_ordered_email'],
											"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
											"comment"				=> $data_post['new_order_notes'],
											"uniqueID"				=> $unique_id,
											"deliver_to_type"	    => $data_post['delivery_to_type'],
											'medical_record_id' 	=> $data_post['medical_record_id'],
											"order_status"	        => $data_post['order_status']
										);
						
					}
						/*
						* @sub equipments  
						*
						*/
						foreach($data_post['subequipment'] as $key=>$value)
						{
								if(in_array($key,$data_post['equipments']))
								{
									foreach($value as $sub_key=>$sub_value)
									{
										if($sub_key=="radio")
										{
											foreach($sub_value as $radio_value)
											{
													$orders[] = array(				
																	"patientID"				=> $patientID,			
																	"equipmentID"			=> $value,		
																	"equipment_value"		=> 1,
																	"pickup_date"			=> $pickup_date,
																	"activity_typeid"		=> $activity_type,	
																	"organization_id"		=> $organization_id,
																	"ordered_by"			=> $id,
																	"who_ordered_fname"		=> $data_post['who_ordered_fname'],
																	"who_ordered_lname"		=> $data_post['who_ordered_lname'],
																	"staff_member_fname"	=> $data_post['staff_member_fname'],
																	"staff_member_lname"	=> $data_post['staff_member_lname'],
																	"who_ordered_email"		=> $data_post['who_ordered_email'],
																	"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
																	"comment"				=> $data_post['new_order_notes'],
																	"uniqueID"				=> $unique_id,
																	"deliver_to_type"	    => $data_post['delivery_to_type'],
																	'medical_record_id' 	=> $data_post['medical_record_id'],
																	"order_status"	        => $data_post['order_status']
																);
											}
										}
										else
										{
											$orders[] = array(				
												"patientID"				=> $patientID,			
												"equipmentID"			=> $value,		
												"equipment_value"		=> 1,
												"pickup_date"			=> $pickup_date,
												"activity_typeid"		=> $activity_type,	
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['who_ordered_fname'],
												"who_ordered_lname"		=> $data_post['who_ordered_lname'],
												"staff_member_fname"	=> $data_post['staff_member_fname'],
												"staff_member_lname"	=> $data_post['staff_member_lname'],
												"who_ordered_email"		=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
												"comment"				=> $data_post['new_order_notes'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['delivery_to_type'],
												'medical_record_id' 	=> $data_post['medical_record_id'],
												"order_status"	        => $data_post['order_status']
											);
										}
									}
								}
						}

						$saveorder = $this->order_model->saveorder($orders);
		}
	}



	public function count_patient_notes($medical_record_id)
	{
		$result = $this->order_model->count_patient_comments($medical_record_id);
		echo json_encode($result);
	}


	public function change_order_status($medical_record_id,$status='', $uniqueID)
	{

		$array = array(
			"order_status" => $status
		);
		

		$this->order_model->change_order_status($medical_record_id, $uniqueID, $array);

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Order Status Successfully Updated.";
		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function move_enroute_orders($medical_record_ids,$status='', $unique_ids)
	{
		$array = array(
			"order_status" => $status
		);
		
		$this->order_model->move_enroute_orders($medical_record_id, $unique_ids, $array);

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Successfully moved to Confirm Work Orders.";
		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function cancel_order($medical_record_id, $uniqueID , $equipmentID="")
	{
		$results = $this->order_model->list_orders_to_cancel($medical_record_id, $uniqueID);

		$array = array();


		foreach($results as $result)
		{
			$array[] = array(
				"patientID"			=> $result['patientID'],
				"medical_record_id" => $result['medical_record_id'],
				"equipmentID"		=> $result['equipmentID'],
				"equipment_value"	=> $result['equipment_value'],
				"pickup_date"		=> $result['pickup_date'],
				"activity_typeid"	=> $result['activity_typeid'],
				"organization_id"	=> $result['organization_id'],
				"ordered_by"		=> $result['ordered_by'],
				"who_ordered_fname"		=> $result['who_ordered_fname'],
				"who_ordered_lname"		=> $result['who_ordered_lname'],
				"staff_member_fname"		=> $result['staff_member_fname'],
				"staff_member_lname"		=> $result['staff_member_lname'],
				"who_ordered_email"		=> $result['who_ordered_email'],
				"who_ordered_cpnum"		=> $result['who_ordered_cpnum'],
				"deliver_to_type"		=> $result['deliver_to_type'],
				"comment"			=> $result['comment'],
				"date_ordered"		=> $result['date_ordered'],
				"uniqueID"			=> $result['uniqueID'],
				"order_status"		=> $result['order_status'],
				"canceled_from_confirming" => 1
			);
		}
		$from_where_table = "dme_order";
		$array_encoded = json_encode($array);

		
		$inserted_data = array(
			"from_where_table" => $from_where_table,
			"data_deleted"	   => $array_encoded,
			"deleted_medical_id" => $medical_record_id,
			"deleted_uniqueID"	=> $uniqueID
		);


		$update_uniqueid_status = array(
			'canceled_from_confirming' => 1
		);

		$revert_pickedup_item_to_delivery = array(
			'activity_typeid'  => 1
		);

		$delete_entry = $this->order_model->delete_order($medical_record_id);
		$trash_added = $this->order_model->save_to_trash($inserted_data);
		$updated_canceled_status = $this->order_model->update_canceled_status($medical_record_id,$uniqueID,$update_uniqueid_status);
		$reverted_item_activty_type = $this->order_model->revert_pickedup_item_to_delivery($equipmentID,$medical_record_id, $revert_pickedup_item_to_delivery);

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Successfully Canceled Order.";

		echo json_encode(array(
					"error"		=> $this->response_code,
					"message"	=> $this->response_message
		));
	}

	public function view_order_details($medical_id,$hospiceID="",$unique_id="",$act_id="",$equipID="", $patientID="")
	{
		$user_type = $this->session->userdata('account_type');
		
		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_order_details($medical_id, $unique_id, $act_id, $patientID);

		if($act_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}

		if($act_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}

		if($act_id == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}

		if($act_id == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		//$pickedup_date_info = $this->order_model->get_pickedup_date_info($medical_id);

		$from_order_status = $this->order_model->get_from_order_status($unique_id,$equipID);

		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id);

		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		//$data['pickedup_date_info']	  = $pickedup_date_info;
		$data['from_order_stats']	  = $from_order_status;

		/** For Equipments **/
		// $categories = $this->order_model->get_equipment_category();
		// $equipment_array = array();

		// foreach($categories as $cat)
		// {
			// if($user_type == 'dme_admin')
			// {
				// $children = $this->order_model->get_equipment($cat['categoryID']);
			// }
			// else
			// {
				// $children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			// }
			// $equipment_array[] = array(
				// 'categoryID' => $cat['categoryID'],
				// 'type'		 => $cat['type'],
				// 'children'	 => $children,
				// 'division'	 => floor(count($children)/2),
				// 'last'	 	 => count($children)-1
			// );
		// }
		// $data['equipments'] = $equipment_array;

	    // $id = $medical_id;

        // $response = $this->order_model->get_orders($id);

        // $orders = array();

        // if(!empty($response))
        // {
            // foreach($response as $key=>$value)
            // {
                // $cat_ = $value['type'];
                // if($value['parentID']==0)
                // {
                    // $orders[$cat_][trim($value['key_desc'])][] = $value;
                // }
                // else
                // {
                    // $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                // }
            // }
        // }
        // $data['orders'] = $orders;
        /*
         * @pickup
         */
        // $data['pickup_sub'] = "";
        // $data['unique_id']	= $medical_id;
        // $data['date']       = "";
        // $data['pickup_equipment'] = array();
        
        // if($informations[0]['activity_typeid']==2)
        // {
            // $pick_data = $this->order_model->get_pickup($id); 
            // if(!empty($pick_data))
            // {
                // $data['pickup_sub'] = $pick_data['pickup_sub'];
                // $data['date']       = $pick_data['date'];
                // $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            // }
        // }
		$this->templating_library->set_view('pages/view_order_details','pages/view_order_details',$data);

	}


	public function view_initial_order_details($medical_id, $hospiceID="", $unique_id="", $act_id="", $equipID="",$patientID="")
	{
		$user_type = $this->session->userdata('account_type');
		
		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_order_details($medical_id, $unique_id, $act_id, $patientID);
		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id);

		if($act_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}

		if($act_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}

		if($act_id == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}

		if($act_id == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}
		
		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['work_order']			  = $unique_id;

		/** For Equipments **/
		// $categories = $this->order_model->get_equipment_category();
		// $equipment_array = array();

		// foreach($categories as $cat)
		// {
			// if($user_type == 'dme_admin')
			// {
				// $children = $this->order_model->get_equipment($cat['categoryID']);
			// }
			// else
			// {
				// $children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			// }
			// $equipment_array[] = array(
				// 'categoryID' => $cat['categoryID'],
				// 'type'		 => $cat['type'],
				// 'children'	 => $children,
				// 'division'	 => floor(count($children)/2),
				// 'last'	 	 => count($children)-1
			// );
		// }
		// $data['equipments'] = $equipment_array;

	    // $id = $medical_id;

        // $response = $this->order_model->get_orders($id);

        // $orders = array();

        // if(!empty($response))
        // {
            // foreach($response as $key=>$value)
            // {
                // $cat_ = $value['type'];
                // if($value['parentID']==0)
                // {
                    // $orders[$cat_][trim($value['key_desc'])][] = $value;
                // }
                // else
                // {
                    // $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                // }
            // }
        // }
        // $data['orders'] = $orders;
        /*
         * @pickup
         */
        // $data['pickup_sub'] = "";
        // $data['unique_id']	= $medical_id;
        // $data['date']       = "";
        // $data['pickup_equipment'] = array();
        // if($informations[0]['activity_typeid']==2)
        // {
            // $pick_data = $this->order_model->get_pickup($id); 
            // if(!empty($pick_data))
            // {
                // $data['pickup_sub'] = $pick_data['pickup_sub'];
                // $data['date']       = $pick_data['date'];
                // $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            // }
        // }
		$this->templating_library->set_view('pages/view_original_order_details','pages/view_original_order_details',$data);
		//$this->templating_library->set_view('common/foot','common/foot');
	}


	public function view_exchange_order_details($medical_id, $hospiceID="", $unique_id="", $act_id="", $patientID="") //removed  $equipID="" (put back if this will cause errors)
	{
		$user_type = $this->session->userdata('account_type');
		
		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_order_details($medical_id, $unique_id, $act_id,$patientID);

		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id);

		if($act_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}

		if($act_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}

		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['work_order']			  = $unique_id;

		// printA($activity_type_data);
		// exit;


		/** For Equipments **/
		// $categories = $this->order_model->get_equipment_category();
		// $equipment_array = array();

		// foreach($categories as $cat)
		// {
			// if($user_type == 'dme_admin')
			// {
				// $children = $this->order_model->get_equipment($cat['categoryID']);
			// }
			// else
			// {
				// $children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			// }
			// $equipment_array[] = array(
				// 'categoryID' => $cat['categoryID'],
				// 'type'		 => $cat['type'],
				// 'children'	 => $children,
				// 'division'	 => floor(count($children)/2),
				// 'last'	 	 => count($children)-1
			// );
		// }
		// $data['equipments'] = $equipment_array;

	    // $id = $medical_id;

        // $response = $this->order_model->get_orders($id);

        // $orders = array();

        // if(!empty($response))
        // {
            // foreach($response as $key=>$value)
            // {
                // $cat_ = $value['type'];
                // if($value['parentID']==0)
                // {
                    // $orders[$cat_][trim($value['key_desc'])][] = $value;
                // }
                // else
                // {
                    // $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                // }
            // }
        // }
        // $data['orders'] = $orders;
        /*
         * @pickup
         */
        // $data['pickup_sub'] = "";
        // $data['unique_id']	= $medical_id;
        // $data['date']       = "";
        // $data['pickup_equipment'] = array();
        // if($informations[0]['activity_typeid']==2)
        // {
            // $pick_data = $this->order_model->get_pickup($id); 
            // if(!empty($pick_data))
            // {
                // $data['pickup_sub'] = $pick_data['pickup_sub'];
                // $data['date']       = $pick_data['date'];
                // $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            // }
        // }

		$this->templating_library->set_view('pages/view_exchange_order_details','pages/view_exchange_order_details',$data);
		//$this->templating_library->set_view('common/foot','common/foot');
	}



	public function view_order_status_details($medical_id, $hospiceID="", $unique_id="", $act_id="", $patientID="") //removed  $equipID="" (put back if this will cause errors)
	{
		
		$user_type = $this->session->userdata('account_type');
		
		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_order_details($medical_id, $unique_id, $act_id, $patientID);
		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id);
		
		if($act_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}

		if($act_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}

		if($act_id == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}

		if($act_id == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['work_order']			  = $unique_id;

		
		$this->templating_library->set_view('pages/view_order_status_details','pages/view_order_status_details',$data);
		//$this->templating_library->set_view('common/foot','common/foot');
	}




	public function canceled()
	{
		$trash = $this->order_model->get_canceled();
		$patients = get_patient();
		$newarray = array();

		foreach($trash as $key=>$first)
		{
			$merge_array  = $first;
			$data = json_decode($first['data_deleted'], true);
			//printA($data[0]);
			$patientID = $data[0]['patientID'];
			$patient_info = array();

			if(isset($patients[$patientID]))
			{
				$patient_info = $patients[$patientID];
			}
			else
			{
				$patient_info['p_lname'] 		= "";
				$patient_info['p_firstname']	= "";
				$patient_info['p_street']		= "";
				$patient_info['p_placenum']		= "";
				$patient_info['p_city']			= "";
				$patient_info['p_state']		= "";
				$patient_info['p_postalcode']	= "";
				$patient_info['p_phonenum']		= "";
				$patient_info['p_altphonenum']  = "";
				$patient_info['p_nextofkin']	= "";
				$patient_info['p_nextofkinnum']	= "";
				$patient_info['med_rec_id']		= "";
			}
			$newarray[] = array_merge($merge_array,$patient_info);
		}
		//$this->order_model->add_order(json_decode($first['data_deleted']));
		$data['trashes'] = $newarray;
		$data['active_nav'] = "canceled";

		$this->templating_library->set('title','Canceled Orders');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/canceled_orders','pages/canceled_orders' , $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function restore_order($trashID, $deleted_uniqueID)
	{
		$trash = $this->order_model->get_spec_trash($trashID);

		$data_canceled_decoded = json_decode($trash[0]['data_deleted']);
		$array = array();

		foreach($data_canceled_decoded as $decoded)
		{	
			 $array[] = array(
			 	"patientID"				=> $decoded->patientID,
			 	"medical_record_id"		=> $decoded->medical_record_id,
			 	"equipmentID"			=> $decoded->equipmentID,
			 	"equipment_value"		=> $decoded->equipment_value,
			 	"pickup_date"			=> $decoded->pickup_date,
			 	"activity_typeid"		=> $decoded->activity_typeid,
			 	"organization_id"		=> $decoded->organization_id,
			 	"ordered_by"			=> $decoded->ordered_by,
			 	"who_ordered_fname"		=> $decoded->who_ordered_fname,
				"who_ordered_lname"		=> $decoded->who_ordered_lname,
				"staff_member_fname"	=> $decoded->staff_member_fname,
				"staff_member_lname"	=> $decoded->staff_member_lname,
				"who_ordered_email"		=> $decoded->who_ordered_email,
				"who_ordered_cpnum"		=> $decoded->who_ordered_cpnum,
				"deliver_to_type"		=> $decoded->deliver_to_type,
			 	"comment"			    => $decoded->comment,
			 	"date_ordered"			=> $decoded->date_ordered,
			 	"uniqueID"			    => $decoded->uniqueID,
			 	"order_status"		    => $decoded->order_status,
			 	"canceled_from_confirming" => 0
			 );
		}
	
		$successful_transfer_to_order = $this->order_model->transfer_to_order($array,$deleted_uniqueID);
		if($successful_transfer_to_order)
		{
			$this->order_model->delete_from_trash($trashID);
		}
		else
		{
			return FALSE;
		}

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Order Form Successfully Restored.";

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function get_equipment_options($equipmentID,$uniqueID)
	{
		$patient_weight = $this->order_model->get_patient_weight($equipmentID,$uniqueID);
		$lot_number = $this->order_model->get_lot_number($equipmentID,$uniqueID);
		$options = $this->order_model->get_equipment_options($equipmentID,$uniqueID);
		$key_desc = "";
		$opt_desc = "";
		$equip_value = "";

		$data['options'] = $options;
		$data['unique_id'] = $uniqueID;
		$data['parent_equipmentID'] = $equipmentID;
		$data['patient_weight'] = $patient_weight;
		$data['lotnumbers'] = $lot_number;

		if(!empty($data['options']))
		{
			$option_view = $this->templating_library->set_view('pages/equipment_options','pages/equipment_options', $data);
		}
		else
		{
			$option_view = "";
		}
		return $option_view;
	}

	public function change_to_confirmed($medical_id,$uniqueID,$activity_typeid="")
	{
		$post_data = $this->input->post();

		/** commented because i reverted the code to the old one
			//$index = 0;
			// if(!empty($post_data))
			// {
			// 	foreach($post_data['order_summary'] as $f_key=>$first_val)
			// 	{
			// 		foreach($first_val as $key=>$value)
			// 		{	
			// 			if(empty($value['pickedup_date']))
			// 			{
			// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
			// 			}
			// 			else
			// 			{
			// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
			// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][pickedup_date]', ''.$value['key_desc'].' Picked Up Date', 'required');
			// 			}

			// 		}
			// 		$index++;
			// 	}
			// }
		**/

		if(!empty($post_data))
		{
			foreach($post_data['order_summary'] as $key=>$value)
			{
				$this->form_validation->set_rules('order_summary[' . $key . '][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
				
				if($activity_typeid == 2)
				{
					$this->form_validation->set_rules('order_summary[' . $key . '][pickedup_date]', ''.$value['key_desc'].' Picked Up Date', 'required');
				}
				
				$this->form_validation->set_rules('order_summary[' . $key . '][driver_name]', 'Driver Name', 'required');
			}
		}

		
		if($this->form_validation->run())
		{

			// printA($post_data);
			// exit;
			$updated = $this->order_model->update_order_summary_confirm_fields($medical_id, $uniqueID, $post_data);

			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Changed Order Status";
			}
			else
			{
				$this->response_message = "Failed to change status.";
			}
		}

		else
		{
			$this->response_message = validation_errors("<span></span>");

		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}


	public function change_to_confirmed_exchange($medical_id,$uniqueID)
	{
		$post_data = $this->input->post();

		$index = 0;
		if(!empty($post_data))
		{
			foreach($post_data['order_summary'] as $f_key=>$first_val)
			{
				foreach($first_val as $key=>$value)
				{	
					if(empty($value['pickedup_date']))
					{
						$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
						// if($value['activity_typeid'] == 2)
						// {
						// 	$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][pickedup_date]', 'Old '.$value['key_desc'].' Picked Up Date', 'required');
						// }
					}
					else
					{
						$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
						$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][driver_name]', 'Driver Name', 'required');
					}

				}
				$index++;
			}
		}
		
		if($this->form_validation->run())
		{
			$updated = $this->order_model->update_order_summary_confirm_fields_exchange($medical_id, $uniqueID, $post_data);

			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Changed Order Status";
			}
			else
			{
				$this->response_message = "Failed to change status.";
			}
		}

		else
		{
			$this->response_message = validation_errors("<span></span>");

		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function update_profile_order_summary($medical_id)
	{
		$post_data = $this->input->post();

		//$index = 0;

		// if(!empty($post_data))
		// {
		// 	foreach($post_data['order_summary'] as $f_key=>$first_val)
		// 	{
		// 		foreach($first_val as $key=>$value)
		// 		{	
		// 			if(empty($value['pickedup_date']))
		// 			{
		// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
		// 			}
		// 			else
		// 			{
		// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
		// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][pickedup_date]', ''.$value['key_desc'].' Picked Up Date', 'required');
		// 			}

		// 			$index++;
		// 		}
		// 	}
		// }
		$index = 0;
		if(!empty($post_data))
		{
			foreach($post_data['order_summary'] as $f_key=>$first_val)
			{
				foreach($first_val as $key=>$value)
				{	
					if(empty($value['pickedup_date']))
					{
						$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
					}
					else
					{
						$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
						$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][driver_name]', 'Driver Name', 'required');
					}

				}
				$index++;
			}
		}



		
		$updated = $this->order_model->update_order_summary_fields($medical_id,$post_data);

		if($updated)
		{
			$this->response_code = 0;
			$this->response_message = "Successfully Changed Order Status";
		}
		else
		{
			$this->response_message = "Failed to change status.";
		}

		
		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}


	public function edit_liter_flow($uniqueID, $equipmentID)
	{
		$original_liter_flow = $this->order_model->get_item_liter_flow($uniqueID, $equipmentID);

		$liter_flow_id = 0;

		if($equipmentID == 61)
  		{
  			$liter_flow_id = 77;
  		}
  		if($equipmentID == 62)
  		{
  			$liter_flow_id = 188;
  		}
  		if($equipmentID == 174)
  		{
  			$liter_flow_id = 189;
  		}
  		if($equipmentID == 29)
  		{
  			$liter_flow_id = 100;
  		}
  		if($equipmentID == 30)
  		{
  			$liter_flow_id = 240;
  		}
  		if($equipmentID == 31)
  		{
  			$liter_flow_id = 190;
  		}
  		if($equipmentID == 176)
  		{
  			$liter_flow_id = 191;
  		}
  		if($equipmentID == 36)
  		{
  			$liter_flow_id = 201;
  		}

  		$data['unique_id'] = $uniqueID;
  		$data['liter_flow_id'] = $liter_flow_id;
		$data['initial_liter_flow'] = $original_liter_flow[0]['equipment_value'];
		$this->templating_library->set('title','Edit Liter Flow');
		$this->templating_library->set_view('pages/change_liter_flow','pages/change_liter_flow', $data);
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function update_liter_flow_value($uniqueID, $equipmentID)
	{
		$this->form_validation->set_rules('liter_flow_qty','Liter Flow Quantity', 'required');

		if($this->form_validation->run())
		{
			$array = array(
				'equipment_value' => $this->input->post('liter_flow_qty')
			);

			$updated = $this->order_model->update_liter_flow_value($uniqueID, $equipmentID, $array);

			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Updated Liter Flow";
			}
			else
			{
				$this->response_message = "Failed.";
			}

		}
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function edit_bipap_option($uniqueID, $equipmentID)
	{
	
		$original_bipaps = $this->order_model->get_bipap_option($uniqueID, $equipmentID);
		
		$data['original_bipaps'] = $original_bipaps;
		$data['unique_id'] = $uniqueID;
		$data['parent_equipmentID'] = $equipmentID;
		$this->templating_library->set('title','Edit Liter Flow');
		$this->templating_library->set_view('pages/change_bipap_option','pages/change_bipap_option', $data);
		$this->templating_library->set_view('common/foot','common/foot');
	}


	public function update_bipap_option($uniqueID, $equipmentID)
	{
		$data_post = $this->input->post();

		foreach($data_post['bipap'] as $key=>$value)
		{
			foreach($value as $f_key=>$f_val)
			{
				$array = array(
					//"equipmentID" => $f_key,
					"equipment_value" => $f_val[0]		
				);
				$updated = $this->order_model->update_bipap_option($uniqueID, $f_key, $array);

				if($updated)
				{
					$this->response_code = 0;
					$this->response_message = "Successfully Updated Liter Flow";
				}

				else
				{
					$this->response_message = "Failed.";
				}
			}
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}


	public function edit_cpap_option($uniqueID, $equipmentID)
	{
		$original_cpaps = $this->order_model->get_cpap_option($uniqueID, $equipmentID);
		
		$data['original_cpaps'] = $original_cpaps;
		$data['unique_id'] = $uniqueID;
		$data['parent_equipmentID'] = $equipmentID;
		$this->templating_library->set('title','Edit Liter Flow');
		$this->templating_library->set_view('pages/change_cpap_option','pages/change_cpap_option', $data);
		$this->templating_library->set_view('common/foot','common/foot');
	}


	public function update_cpap_option($uniqueID, $equipmentID)
	{
		$this->form_validation->set_rules('cpap_value','IPAP value', 'required');

		if($this->form_validation->run())
		{
			$array = array(
				'equipment_value' => $this->input->post('cpap_value')
			);

			$updated = $this->order_model->update_cpap_option($uniqueID, $equipmentID, $array);

			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Updated IPAP.";
			}
			else
			{
				$this->response_message = "Failed.";
			}

		}
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function edit_patient_weight($uniqueID, $equipmentID, $medical_id, $patientID)
	{
		$patient_weights = $this->order_model->get_patient_weight_toedit($uniqueID, $equipmentID, $patientID);

		$data['patient_weights'] = $patient_weights;

		$data['unique_id'] = $uniqueID;
		$data['medical_id'] = $medical_id;
		$data['equipment_id'] = $equipmentID;
		$data['patientID']	= $patientID;
		$this->templating_library->set('title','Edit Liter Flow');
		$this->templating_library->set_view('pages/change_patient_weight','pages/change_patient_weight', $data);
		$this->templating_library->set_view('common/foot','common/foot');
	}


	public function update_patient_weight($uniqueID, $weightID)
	{
		$this->form_validation->set_rules('patient_weight','Patient Weight', 'required');

		if($this->form_validation->run())
		{
			$array = array(
				'patient_weight' => $this->input->post('patient_weight')
			);

			$updated = $this->order_model->update_patient_weight($uniqueID, $weightID, $array);

			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Updated Weight.";
			}
			else
			{
				$this->response_message = "Failed.";
			}

		}
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}


	public function put_patient_weight($uniqueID)
	{
		$this->form_validation->set_rules('patient_weight','Patient Weight', 'required');

		if($this->form_validation->run())
		{
			$array = array(
				'medical_record_num' => $this->input->post('medical_id'),
				'ticket_uniqueID'=> $this->input->post('unique_id'),
				'equipmentID'	 => $this->input->post('equipment_id'),
				'patient_weight' => $this->input->post('patient_weight')
			);

			$updated = $this->order_model->put_patient_weight($array);

			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Inserted Weight.";
			}
			else
			{
				$this->response_message = "Failed.";
			}

		}
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function show_patient_edit_logs($patientID)
	{
		$data['logs'] = $this->order_model->get_patient_logs($patientID);
		$this->templating_library->set_view('pages/patient_editing_logs','pages/patient_editing_logs', $data);
	}


	public function tracking()
	{
		$this->templating_library->set('title','DME Tracking');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/dme_tracking','pages/dme_tracking');
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function lot_number_tracking()
	{
		$this->templating_library->set('title','DME Tracking');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/lot_number_tracking','pages/lot_number_tracking');
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function search_items_tracking($searchString)
	{
		$post_data = $this->input->post();
		$return = "";

		
		$searches = $this->order_model->search_items_tracking($searchString);
		
		if($searches)
		{
			foreach ($searches as $search) 
			{
				echo "<tr style='cursor:pointer;' class='items_result form-control' title='".$search['serial_num']."' data-id='".$search['medical_record_id']."' data-value='".$search['serial_num']."'><td>Serial Number: ".$search['serial_num']."</td></tr>";
			}
		}
		else
		{
			echo "<tr style='cursor:pointer;' class='form-control'><td>No Results Returned.</td></tr>";
		}
	}

	public function return_item_search()
	{
		$data_get = $this->input->get();
		
		$data['serial'] = (isset($data_get['serial'])) ? $data_get['serial'] : '';

		$where = array(
			'serial' => str_replace(' ', '%', $data['serial']),
		);

		$result = $this->order_model->list_patients_with_serial_numbers($uniqueID="",$where);
		$data['orders'] = ($result) ? $result : FALSE;
		$data['counts'] = count($this->order_model->list_orders_for_patient_search($uniqueID="",$where));

		$this->templating_library->set('title','Patient Order Summary');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/patient_order_grid','pages/patient_order_grid', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	
	
	public function search_lot_number($searchString)
	{
		$post_data = $this->input->post();
		$return = "";

		
		$searches = $this->order_model->search_lot_number($searchString);
		
		if($searches)
		{
			foreach ($searches as $search) 
			{
				echo "<tr style='cursor:pointer;height:auto' class='items_result form-control' title='".$search['lot_number_content']."' data-id='".$search['medical_record_id']."' data-value='".$search['lot_number_content']."'><td>Oxygen Lot #: ".$search['lot_number_content']."</td></tr>";
			}
		}
		else
		{
			echo "<tr style='cursor:pointer;' class='form-control'><td>No Results Returned.</td></tr>";
		}
	}

	public function return_lot_number_search()
	{
		$data_get = $this->input->get();
		
		$data['lotNo'] = (isset($data_get['lotNo'])) ? $data_get['lotNo'] : '';

		$where = array(
			'lotNo' => str_replace(' ', '%', $data['lotNo']),
		);

		$result = $this->order_model->list_patients_with_lot_number($uniqueID="",$where);
		$data['orders'] = ($result) ? $result : FALSE;
		$data['counts'] = count($this->order_model->list_patients_with_lot_number($uniqueID="",$where));

		$this->templating_library->set('title','Patient Order Summary');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/patient_order_grid','pages/patient_order_grid', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}


	public function print_order_details($medical_id, $hospiceID="", $unique_id="", $act_id="", $patientID) 
	{
		
		$user_type = $this->session->userdata('account_type');
		
		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_order_details($medical_id, $unique_id, $act_id, $patientID);

		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id);

		if($act_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}

		if($act_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}

		if($act_id == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}

		if($act_id == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['work_order']			  = $unique_id;


		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $medical_id;

        $response = $this->order_model->get_orders($id);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id); 
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }
        $this->templating_library->set('title','Print Order Details');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/print_order_details','pages/print_order_details', $data);
		$this->templating_library->set_view('common/footer','common/footer');
	}
	
	
	public function print_confirm_details($medical_record_id, $uniqueID, $act_id="", $hospiceID="")
	{
		$user_type = $this->session->userdata('account_type');
		
		$returns = $this->order_model->list_orders($medical_record_id);
		$informations = $this->order_model->get_order_info($medical_record_id, $hospiceID);
		$data['comments'] = $this->order_model->get_all_comments($medical_record_id);

		$datas = array();
		$data['summarys'] = $returns;
		$data['informations'] = $informations;
		$data['medical_record_num'] = $medical_record_id;
		$data['act_id'] = $act_id;
		$data['note_counts'] = $this->order_model->count_patient_comments($medical_record_id);
		$data['work_order_number'] = $uniqueID;

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $medical_record_id;

        $response = $this->order_model->get_orders($id);
        $order_summary = $this->order_model->get_orders_by_workorder($id, $uniqueID, $hospiceID);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        $data['summaries'] = $order_summary;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_record_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id); 
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }
		$data['infos'] = $this->order_model->get_patient_profile($medical_record_id, $hospiceID);
		$data['work_order_number'] = $uniqueID;
		$this->templating_library->set('title','Print Confirmed Details');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/print_confirm_details','pages/print_confirm_details', $data);
		$this->templating_library->set_view('common/footer','common/footer');
	}

}


