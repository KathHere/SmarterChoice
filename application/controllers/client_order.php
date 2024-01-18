<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ;?>
<?php
Class client_order extends CI_Controller
{
	var $include_top = 'landingpage_template/include_top';
	var $header = 'main_template/header';
	var $client_header = 'client_header';
	var $content = 'first_order_form_test';
	var $summary_content = 'final_order_form';
	var $thank_you = 'thank_you';
	var $footer = 'main_template/footer';
	var $include_bottom = 'landingpage_template/include_bottom';

	var $response_code = 1;//false or error default
	var $response_message = "";
	var $response_data = array();
	function __construct()
	{
		parent::__construct();
		$this->load->model('order_model');
		//if($this->session->userdata('account_type') == null) redirect('landingpage');
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
					'p_street' => $result['p_street'],
					'p_placenum' => $result['p_placenum'],
					'p_city' => $result['p_city'],
					'p_state' => $result['p_state'],
					'p_postalcode' => $result['p_postalcode'],
					'p_phonenum' => $result['p_phonenum'],
					'p_altphonenum' => $result['p_altphonenum'],
					'p_nextofkin' => $result['p_nextofkin'],
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
	
	function search_patient($searchString='')
	{
		$post_data = $this->input->post();
		$return = "";

		$searches = $this->order_model->search_patient($searchString);

		if($searches){
			foreach ($searches as $search) {
			
				echo "<tr style='cursor:pointer;' class='pmrn_result form-control' title='".$search['medical_record_id']."' data-id='".$search['medical_record_id']."'><td>Customer MRN: ".$search['medical_record_id']."</td></tr>";
			}
		}
		else
		{
			echo "<tr style='cursor:pointer;' class='form-control'><td>No Results Returned.</td></tr>";
		}

	}
	
	//** End of function **/
	

	function order($hash= '')
	{
		$hospiceID = $this->session->userdata('group_id');
		$user_type = $this->session->userdata('account_type');
		$hospiceID = $this->session->userdata('group_id');

		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();
		
		

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin' || $user_type == 'dme_user')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}

			// printA($cat['categoryID']);
			// exit;
			
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}

		$checked = check_code($hash);
		$id = get_id_from_code($hash);
		
		$result  = $this->order_model->get_spec_hospice($id);
		$result_all_hospice = $this->order_model->get_all_hospice();

		$data['equipments'] = $equipment_array;
		$data['results'] = ($result) ? $result : FALSE;
		$data['results_all'] = ($result_all_hospice) ? $result_all_hospice : FALSE;

		
		$this->templating_library->set('title','Order Form - First Step');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
		{
			$this->templating_library->set_view($this->header, $this->header);
		}
		else
		{
			$this->templating_library->set_view($this->client_header, $this->client_header);
		}
		$this->templating_library->set_view($this->content, $this->content, $data);
		$this->templating_library->set_view($this->footer, $this->footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	}

	function summary($hash = '')
	{
		$checked = check_code($hash);
		$id = get_id_from_code($hash);
		
		$result = $this->order_model->list_order($id);
		$data['results'] = ($result) ? $result : FALSE;
	
		$this->templating_library->set('title','Order Summary');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
		{
			$this->templating_library->set_view($this->header, $this->header);
		}
		else
		{
			$this->templating_library->set_view($this->client_header, $this->client_header);
		}
		$this->templating_library->set_view($this->summary_content, $this->summary_content, $data);
		$this->templating_library->set_view($this->footer, $this->footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	}

	function greetings()
	{
		$this->templating_library->set('title','Thank you for trusting Us!');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
		{
		$this->templating_library->set_view($this->header, $this->header);
		}
		else
		{
		$this->templating_library->set_view($this->client_header, $this->client_header);
		}
		$this->templating_library->set_view($this->thank_you, $this->thank_you);
		$this->templating_library->set_view($this->footer, $this->footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	} 
	
	function add_order()
	{
		if($this->input->post())
		{
			$id = $this->session->userdata('userID');
			$person_who_ordered = $this->session->userdata('email');
			
			$data_post = $this->input->post();
			

			$this->form_validation->set_rules('equipments[]','Orders','required');
			$this->form_validation->set_rules('delivery_date','Delivery Date','required');
			$this->form_validation->set_rules('patient_mrn','Customer Medical Record Number','required');
			$this->form_validation->set_rules('activity_type','Activity Type','required');
			$this->form_validation->set_rules('patient_lname','Customer Last Name','required');
			$this->form_validation->set_rules('patient_fname','Customer First Name','required');
			$this->form_validation->set_rules('patient_phone_num','Customer Phone No.','required');
			$this->form_validation->set_rules('patient_placenum','Place Number','required');
			$this->form_validation->set_rules('patient_city','Customer City','required');
			$this->form_validation->set_rules('patient_state','Customer State','required');
			$this->form_validation->set_rules('patient_postalcode','Customer Postal Code','required');
			$this->form_validation->set_rules('patient_nextofkin','Customer Next of Kin','required');
			$this->form_validation->set_rules('patient_nextofkinphonenum','Customer Next of Kin Phone No.','required');
			


			if($this->form_validation->run()===TRUE)
			{
				$unique_id = strtotime(date('Y-m-d H:i:s'));

				$patient_info = array(
									'p_fname'		=> $data_post['patient_fname'],
									'p_lname'		=> $data_post['patient_lname'],
									'p_street'		=> $data_post['p_address'],
									'p_placenum'	=> $data_post['patient_placenum'],
									'p_city'		=> $data_post['patient_city'],
									'p_state'		=> $data_post['patient_state'],
									'p_postalcode'	=> $data_post['patient_postalcode'],
									'p_phonenum'	=> $data_post['patient_phone_num'],
									'p_altphonenum'	=> $data_post['patient_alt_phonenum'],
									'p_nextofkin'	    => $data_post['patient_nextofkin'],
									'p_nextofkinnum'    => $data_post['patient_nextofkinphonenum'],
									'medical_record_id' => $data_post['patient_mrn']
								); 

				
				$save_patient_info = $this->order_model->save_patientinfo($patient_info);
				if($save_patient_info!=FALSE)
				{
						/*
						* order information
						*/
						$pickup_date 		= $data_post['delivery_date'];
						$ordered_by  		= $id;
						$organization_id 	= $data_post['organization_id'];
						$activity_type		= $data_post['activity_type'];

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
												"comment"				=> $data_post['comment'],
												"uniqueID"				=> $unique_id,
												"order_status"	        => "pending"
											);
						}

						foreach($data_post['disposable'] as $key=>$value)
						{
							if(!empty($value))
							{
								$orders[] = array(				
												"patientID"				=> $save_patient_info,			
												"equipmentID"			=> $key,		
												"equipment_value"		=> $value,
												"pickup_date"			=> $pickup_date,
												"activity_typeid"		=> $activity_type,	
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"comment"				=> $data_post['comment'],
												"uniqueID"				=> $unique_id,
												"order_status"	    => "pending"
											);
							}
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
																	"comment"				=> $data_post['comment'],
																	"uniqueID"				=> $unique_id,
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
												"comment"				=> $data_post['comment'],
												"uniqueID"				=> $unique_id,
												"order_status"	    => "pending"
											);
										}
									}
								}
						}

						$saveorder = $this->order_model->saveorder($orders);
						if($saveorder)
						{

							//2 pickup
							//4 PT
							//5 Respite
							if($activity_type==2)
							{
								$pickup_type_sub = array(
									'pickup_sub' => $data_post['pickup_sub'],
									'date'	 	 => "{$date_post['pickup_date']}",
									'patientID'	 => $save_patient_info,
									'uniqueID'	 => $unique_id
								);
								$this->order_model->save($pickup_type_sub,'dme_pickup');
							}
							else if($activity_type==4)
							{
								$ptmove_type_sub = array(
									'ptmove_street'   => $data_post['ptmove_address'],
									'ptmove_placenum' => $data_post['ptmove_placenum'],
									'ptmove_city'     => $data_post['ptmove_city'],
									'ptmove_state'    => $data_post['ptmove_state'],
									'ptmove_postal'   => $data_post['ptmove_postalcode'],
									'patientID'	 	  => $save_patient_info
								);
								$this->order_model->save($ptmove_type_sub,'dme_sub_ptmove');
							}
							else if($activity_type==5)
							{
								$respite_type_sub = array(
									'respite_pickup_date'   => $data_post['respite_delivery_date'],
									'respite_address'   	=> $data_post['respite_address'],
									'respite_placenum'		=> $data_post['respite_placenum'],
									'respite_city'     		=> $data_post['respite_city'],
									'respite_state'    		=> $data_post['respite_state'],
									'respite_postal'   		=> $data_post['respite_postalcode'],
									'parentID'	 			=> $save_patient_info
								);
								$this->order_model->save($respite_type_sub,'dme_sub_respite');
							}


							$this->response_code = 0;
							$this->response_message = "Successfully saved.";
							
							/*
							*	For email
							*/
							$email_form = $this->form_email_temp($unique_id, TRUE);

							$this->load->config('email');
	                        $config =   $this->config->item('me_email');
	                        $this->load->library('email', $config); 

	                        $this->email->from('orders@ahmslv.com','AHMSLV');  
	                        $this->email->to('orders@ahmslv.com,'.$person_who_ordered.'');  
							$this->email->cc('Rchinaadvantage@aol.com');  
							$this->email->cc('russel@smartstart.us');
	                        $this->email->subject('AHMSLV | Order Summary');
	                        $this->email->message($email_form);
							//echo $email_form;
	                        $this->email->send();
							

						}
				}
				else
				{
					$this->response_message = "Error saving customer information.";
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

	function list_orders()
	{
		$result = $this->order_model->list_orders();
	
		$data['orders'] = ($result) ? $result : FALSE;

		$this->templating_library->set('title','List of Orders | Advantage Home Medical Services Inc.');
		$this->templating_library->set_view($this->include_top, $this->include_top);

		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
		{
			$this->templating_library->set_view($this->header, $this->header);
		}
		else
		{
			$this->templating_library->set_view($this->client_header, $this->client_header);
		}

		$this->templating_library->set_view('admin/order_list', 'admin/order_list', $data);
		$this->templating_library->set_view($this->footer, $this->footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);


	}
	
	function confirmed_orders()
	{
		$result = $this->order_model->list_confirmed_orders();
	
		$data['orders'] = ($result) ? $result : FALSE;

		$this->templating_library->set('title','List of Confirmed Orders | Advantage Home Medical Services Inc.');
		$this->templating_library->set_view($this->include_top, $this->include_top);

		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
		{
			$this->templating_library->set_view($this->header, $this->header);
		}
		else
		{
			$this->templating_library->set_view($this->client_header, $this->client_header);
		}

		$this->templating_library->set_view('admin/confirmed_orders_list', 'admin/confirmed_orders_list', $data);
		$this->templating_library->set_view($this->footer, $this->footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
 
 
	} 


	function order_summary($uniqueID, $return=false)
	{
		$returns = $this->order_model->list_orders($uniqueID);
		$informations = $this->order_model->get_order_info($uniqueID);

		$datas = array();
	

		$data['summarys'] = $returns;
		$data['informations'] = $informations;

		  $id = $uniqueID;
	        
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

		
		$content = '';

		$this->templating_library->set('title','List of Orders | Order Summary');
		
		$top = $this->templating_library->set_view($this->include_top, $this->include_top, $data , TRUE);
		
		$top2 = $this->templating_library->set_view('landingpage_template/custom_include_top', 'landingpage_template/custom_include_top', $data , TRUE);

		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
		{
			$header = $this->templating_library->set_view($this->header, $this->header, $data , TRUE);
		}
		else
		{
			$header = $this->templating_library->set_view($this->client_header, $this->client_header, $data , TRUE);
		}

		$content = $this->templating_library->set_view('final_order_form', 'final_order_form', $data , TRUE);
		$footer  =	$this->templating_library->set_view($this->footer, $this->footer, $data ,TRUE);
		$bottom = $this->templating_library->set_view($this->include_bottom, $this->include_bottom , $data ,TRUE);	
		if($return)
		{
			$final = $top2.$content.$footer;
			return $final;
		}
		else
		{
			echo $top.$header.$content.$footer.$bottom;
		}
	}
	
	
	public function form_email_temp($uniqueID, $return=false)
	{
			$informations = $this->order_model->get_order_info($uniqueID);
			$data['informations'] = $informations;
			
			$id = $uniqueID;
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
			
			$content = ''; 

			$this->templating_library->set('title','List of Orders | Order Summary');
			
			$top = $this->templating_library->set_view($this->include_top, $this->include_top, $data , TRUE);
			
			$top2 = $this->templating_library->set_view('landingpage_template/custom_include_top', 'landingpage_template/custom_include_top', $data , TRUE);

			if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
			{ 
				$header = $this->templating_library->set_view($this->header, $this->header, $data , TRUE);
			}
			else
			{
				$header = $this->templating_library->set_view($this->client_header, $this->client_header, $data , TRUE);
			}

			$content = $this->templating_library->set_view('email_template', 'email_template', $data , TRUE);
			$footer  =	$this->templating_library->set_view($this->footer, $this->footer, $data ,TRUE);
			$bottom = $this->templating_library->set_view($this->include_bottom, $this->include_bottom , $data ,TRUE);	
			if($return)
			{
				$final = $top2.$content; 
				
				return $final;
			}
			else
			{
				echo $top.$header.$content.$footer.$bottom;
			}
	}

	// public function test_email()
	// {
	// 	$this->templating_library->set('title','List of Orders | Order Summary');
			
	// 		$top = $this->templating_library->set_view($this->include_top, $this->include_top, $data , TRUE);
			
	// 		$top2 = $this->templating_library->set_view('landingpage_template/custom_include_top', 'landingpage_template/custom_include_top', $data , TRUE);

	// 		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
	// 		{ 
	// 			$header = $this->templating_library->set_view($this->header, $this->header, $data , TRUE);
	// 		}
	// 		else
	// 		{
	// 			$header = $this->templating_library->set_view($this->client_header, $this->client_header, $data , TRUE);
	// 		}

	// 		$content = $this->templating_library->set_view('email_template', 'email_template', $data , TRUE);
	// 		$footer  =	$this->templating_library->set_view($this->footer, $this->footer, $data ,TRUE);
	// 		$bottom = $this->templating_library->set_view($this->include_bottom, $this->include_bottom , $data ,TRUE);	
	// 		if($return)
	// 		{
	// 			$final = $top2.$content; 
				
	// 			echo $final;
	// 			exit;
	// 			return $final;
	// 		}
	// 		else
	// 		{
	// 			echo $top.$header.$content.$footer.$bottom;
	// 		}
	// }

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
                                            "orderUniqueID" => $unique_id        
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


	function order_summary_hospice($uniqueID)
	{
		$returns = $this->order_model->list_orders($uniqueID);
		$informations = $this->order_model->get_order_info($uniqueID);

		$datas = array();
	

		$data['summarys'] = $returns;
		$data['informations'] = $informations;

		$final = array();


		$this->templating_library->set('title','List of Orders | Order Summary');
		$this->templating_library->set_view($this->include_top, $this->include_top);

		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
		{
			$this->templating_library->set_view($this->header, $this->header);
		}
		else
		{
			$this->templating_library->set_view($this->client_header, $this->client_header);
		}

		$this->templating_library->set_view('hospice_summary_form', 'hospice_summary_form', $data);
		$this->templating_library->set_view($this->footer, $this->footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);	
	}
	


	function hospice_order_summary($hash='')
	{
		$checked = check_code($hash);
		$id = get_id_from_code($hash);

		$data['informations'] = $this->order_model->hospice_order_summary($id);




		$this->templating_library->set('title','List of Users | Order Summary');
		$this->templating_library->set_view($this->include_top, $this->include_top);

		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
		{
			$this->templating_library->set_view($this->header, $this->header);
		}
		else
		{
			$this->templating_library->set_view($this->client_header, $this->client_header);
		}

		$this->templating_library->set_view('hospice_order_list', 'hospice_order_list', $data);
		$this->templating_library->set_view($this->footer, $this->footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);	
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

	public function deleted_orders()
	{
		$trash = $this->order_model->get_trash();
		$patients = get_patient();

		$newarray = array();
		
		
		foreach($trash as $key=>$first)
		{
			
			$merge_array  = $first;
			$data = json_decode($first['data_deleted'], true);

			// printA(json_decode($first['data_deleted']));
			// exit;
		
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

		$this->templating_library->set('title','List of Users | Order Summary');
		$this->templating_library->set_view($this->include_top, $this->include_top);

		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
		{
			$this->templating_library->set_view($this->header, $this->header);
		}
		else
		{
			$this->templating_library->set_view($this->client_header, $this->client_header);
		}

		$this->templating_library->set_view('admin/deleted_list', 'admin/deleted_list', $data);
		$this->templating_library->set_view($this->footer, $this->footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);	
			
	}

	public function restore_order($trashID)
	{
		$trash = $this->order_model->get_spec_trash($trashID);
		$data_deleted_decoded = json_decode($trash[0]['data_deleted']);
		
		$array = array();
		
		foreach($data_deleted_decoded as $decoded)
		{	
			 $array[] = array(
			 	"patientID"				=> $decoded->patientID,
			 	"equipmentID"			=> $decoded->equipmentID,
			 	"equipment_value"		=> $decoded->equipment_value,
			 	"pickup_date"			=> $decoded->pickup_date,
			 	"activity_typeid"		=> $decoded->activity_typeid,
			 	"organization_id"		=> $decoded->organization_id,
			 	"ordered_by"			=> $decoded->ordered_by,
			 	"comment"			    => $decoded->comment,
			 	"date_ordered"			=> $decoded->date_ordered,
			 	"uniqueID"			    => $decoded->uniqueID,
			 	"order_status"		    => $decoded->order_status
			 );
		}

		// foreach($trash as $first)
		// {
			// foreach(json_decode($first['data_deleted']) as $decoded)
			// {	
				
				 // $array = array(
				 	// "patientID"				=> $decoded->patientID,
				 	// "equipmentID"			=> $decoded->equipmentID,
				 	// "equipment_value"		=> $decoded->equipment_value,
				 	// "pickup_date"			=> $decoded->pickup_date,
				 	// "activity_typeid"		=> $decoded->activity_typeid,
				 	// "organization_id"		=> $decoded->organization_id,
				 	// "ordered_by"			=> $decoded->ordered_by,
				 	// "comment"			    => $decoded->comment,
				 	// "date_ordered"			=> $decoded->date_ordered,
				 	// "uniqueID"			    => $decoded->uniqueID,
					// "order_status"		    => $decoded->order_status
				 // );
			// }
		// }

		$successful_transfer_to_order = $this->order_model->transfer_to_order($array);

		if($successful_transfer_to_order)
		{
			$this->order_model->delete_from_trash($trashID);
		}
		else{
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
	
	public function change_order_status($uniqueID='',$status='')
	{

		if($status == 'pending')
		{
			$array = array(
				"order_status"	=> "active"
			);
		}
		else if($status == 'active')
		{
			$array = array(
				"order_status"	=> "confirmed"
			);
		}
		else
		{
			$array = array(
				"order_status"	=> "pending"
			);
		}

		$this->order_model->change_order_status($uniqueID, $array);

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Order Status Successfully Updated.";
		
		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
		
	}
	

}

