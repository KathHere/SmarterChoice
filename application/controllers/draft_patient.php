<?php
Class draft_patient extends Ci_Controller
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
		$this->load->model("draft_patient_model");
		$this->load->library('encryption');
	}

	public function customers($view_type='grid_view')
	{
		$data['order_status_list'] = true;
		$get_data = $this->input->get();

		$counter = 0;
		$hospiceID = $this->session->userdata('group_id');
		$data['hospice_selected'] = $hospiceID;

		$noorder = $this->draft_patient_model->list_of_noorder_v3($this->session->userdata('user_location'));
		$data['counting'] = count($noorder);

		$arr2 = array_msort($noorder, array('p_lname' => SORT_ASC));
		$new_patients = array_chunk($arr2, 100);
		$data['total_pages'] = count($new_patients);
		$data['orders'] = array();

		if(!empty($new_patients))
		{
			$data['orders'] = $new_patients[0];
		}

		$data['active_nav']	= "patient_menu";
		$this->templating_library->set('title','View All Customers');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav',$data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'rt') {
			$data['location_details'] = $this->draft_patient_model->get_location_details($this->session->userdata('user_location'));
			$this->templating_library->set_view('pages/draft_patient_grid','pages/draft_patient_grid', $data);
		}
		
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function sort_by_hospice($hospiceID)
	{
		$data['hospice_selected'] = $hospiceID;

		$noorder = $this->draft_patient_model->list_of_noorder_byhospice_v3($hospiceID,$this->session->userdata('user_location'));
		$data['counting'] = count($noorder);

		$arr2 = array_msort($noorder, array('p_lname' => SORT_ASC));
		$new_patients = array_chunk($arr2, 100);
		$data['total_pages'] = count($new_patients);
		$data['orders'] = array();

		if(!empty($new_patients))
		{
			$data['orders'] = $new_patients[0];
		}

		$data['location_details'] = $this->draft_patient_model->get_location_details($this->session->userdata('user_location'));

		$data['active_nav']	= "patient_menu";
		$this->templating_library->set('title','View All Customers');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav',$data);
		$this->templating_library->set_view('pages/draft_patient_grid','pages/draft_patient_grid', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	//for the load more
	public function load_more($page=1, $limit=40)
	{
		$reponse = array("data" => array());
		if($this->input->is_ajax_request())
		{
			//list of active patients only
			$result = $this->draft_patient_model->list_of_noorder_v3($this->session->userdata('user_location'));

			$arr2 = array_msort($result, array('p_lname' => SORT_ASC));

			$new_patients = array_chunk($arr2, $limit);

			if(!empty($new_patients))
			{
				$response['data'] = $new_patients[$page-1];
			}
		}
		echo json_encode($response);
	}

	public function update_patient_profile_confirmation($medical_record_id,$passed_addressID)
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
			$saved_data = $this->order_model->update_order_profile_v2($patient_id, $order_data);

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
					'medical_record_id' => $data_post['medical_record_id'],
					'ordered_by' => $data_post['organization_id']
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

				$saved_patient_info = $this->order_model->update_patient_profile_v2($patient_id, $patient_data);

				if($saved_patient_info)
				{
					$patient_addresses = $this->order_model->get_patient_addresses($patient_id);

					if(count($patient_addresses) == 1)
					{
						$edited_patient_address = array(
							'street' => $data_post['p_street'],
							'placenum' => $data_post['p_placenum'],
							'city' => $data_post['p_city'],
							'state' => $data_post['p_state'],
							'postal_code' => $data_post['p_postalcode']
						);
						$this->order_model->update_dme_patient_address($patient_id, $edited_patient_address);
					}
					else
					{
						// FOR THE dme_patient_address TABLE
						$edited_patient_address = array(
							'street' => $data_post['p_street'],
							'placenum' => $data_post['p_placenum'],
							'city' => $data_post['p_city'],
							'state' => $data_post['p_state'],
							'postal_code' => $data_post['p_postalcode']
						);

						$latest_ptmove_address_id = 0;
						foreach ($patient_addresses as $address_loop) {
							if($address_loop['type'] == 1)
							{
								$latest_ptmove_address_id = $address_loop['id'];
							}
						}
						$this->order_model->update_dme_patient_address_v2($passed_addressID, $edited_patient_address);
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

	public function patient_profile($uniqueID,$hospiceID='')
	{
		$user_type = $this->session->userdata('account_type');

		//if patient has orders
		$returns = $this->order_model->list_orders($uniqueID);
		$informations = $this->order_model->get_order_info($uniqueID,$hospiceID);

		//if patient has no orders
		$patient = $this->order_model->get_patient_noorder_info($uniqueID,$hospiceID);

		$data['comments'] = $this->order_model->get_all_comments($uniqueID);

		$datas = array();

		if(!empty($returns) && !empty($informations))
		{
			$data['summarys'] = $returns;
			$data['informations'] = $informations;
		}else{
			$data['summarys'] = "";
			$data['informations'] = $patient;
		}

		$data['note_counts'] = $this->order_model->count_patient_comments($uniqueID);

		/** For Equipments **/
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
		$this->templating_library->set('title','Customer Order Summary');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		
		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'rt') {
			$this->templating_library->set_view('pages/draft_order_summary','pages/draft_order_summary',$data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function delete_selected_draft_customers($list_of_patient_id) {
		if($this->input->is_ajax_request())
		{
			$exploded_list_of_patient_id = explode('-', $list_of_patient_id);

			$this->response_code = 1;
			$this->response_message = 'Error!';

			foreach($exploded_list_of_patient_id as $patient_id) {
				$this->draft_patient_model->delete_draft_patient_by_patientID($patient_id);
			}

			$this->response_code = 0;
			$this->response_message = 'Successfully deleted draft customers.';
		}
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function delete_all_draft_customers($hospiceID = 0) {
		if($this->input->is_ajax_request())
		{
			$list_of_patient_id = $this->draft_patient_model->list_of_noorder_for_delete_draft_customer($this->session->userdata('user_location'), $hospiceID);

			// print_me($list_of_patient_id);
			$this->response_code = 1;
			$this->response_message = 'Error!';

			foreach($list_of_patient_id as $value) {
				$this->draft_patient_model->delete_draft_patient_by_patientID($value['patientID']);
			}

			$this->response_code = 0;
			$this->response_message = 'Successfully deleted draft customers.';
		}
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}
}