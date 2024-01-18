<?php
include("order.php");
Class order_extend extends order
{
	var $response_code = 1;//false or error default
	var $response_message = "";
	var $response_data = array();
	var $data = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model("morder");
		$this->load->model("order_model");
	}

	public function update_equipment_option_order()
	{
			if($_GET)
			{
				$equipmentID = $_GET['equipmentID'];
				$uniqueID 	 = $_GET['uniqueID'];
				$orderID 	 = $_GET["orderID"];



				//get equipments
				$result 		= $this->morder->do_get_equip_options($equipmentID);
				$equipment_ids  = array();
				if(empty($result) OR $result==false)
				{
					echo "This item has no options";
				}
				else
				{
					$this->data['equipmentID'] 	= $equipmentID;
					$this->data['uniqueID']		= $uniqueID;
					$this->data['options']		= array();

					//process result
					$temp = array();
					foreach($result as $value)
					{
						$temp[$value['optionID']][] = $value;
						$equipment_ids[] 			= $value['equipmentID'];
					}

					$this->data['options'] = $temp;
					$this->data['label']   = $this->options_label();

					// echo "<pre>";
					// print_r($this->data['options']);
					// echo "</pre>";
					//get order by equipment and uniqueID
					$order = $this->morder->do_get_order_equipment($uniqueID,$equipment_ids);
					$this->data['orders'] = array();
					$this->data['orders_simple'] = array();
					foreach($order as $value)
					{
						$this->data['orders'][$value['optionID']][] = $value;
						$this->data['orders_simple'][$value['optionID']][] = $value['equipmentID'];
					}


					$this->load->view("pages/order_extension/equiptment_option",$this->data);
				}
			}
	}
	private function options_label()
	{
		//array('equipment_parent_id' => array('option' => "Type of Hospital Bed","option2" => ""))

		$options = array();
		$result = $this->morder->do_get_option_desc();
		foreach($result as $value)
		{
			$options[$value['optionID']] = $value["option_description"];
		}

		return $options;
	}
	public function update_options()
	{
		if($_POST)
		{
			$uniqueID 		= $_POST['uniqueID'];
			$subequipments	= $_POST['subequipment'];
			$withvalue 		= array(); //this is for text fields
			$otheroption	= array(); //radio or checkbox
			foreach ($subequipments as $key => $value)
			{
				if(!is_array($value))
				{
					$withvalue[$key] = $value;
				}
				else
				{
					$equipment_value	= 0; //new equiptment to be replaced
					if(isset($value['radio']))
					{
						$equipment_value = $value['radio'];
					}
					else
					{
						$equipment_value = $value['check'];
					}
					$otheroption[] 		= array(
											"equipment" 	=> $value["previous_val"],
											"equipment_new"	=> $equipment_value
										);

				}
			}
			if(!empty($otheroption))
			{
				$do_update = $this->morder->update_equipment_option_rc($otheroption,$uniqueID);
				if($do_update)
				{
					$this->common->code 	= 0;
					$this->common->message  = "Successfully updated";
				}
			}
		}
		$this->common->response(false);
	}

	public function cancel_confirmed_item($order_id, $equipment_id, $unique_id, $patient_id, $activity_typeid, $original_activity_typeid, $patient_address) {

		$this->response_code = 1;
        $this->response_message = 'Error';
        $is_update_patient_profile = 1;
        $is_deactivated_account = 0;
		if($original_activity_typeid == 1 || $original_activity_typeid == 5 || $activity_typeid == 5 || $original_activity_typeid == 4) {
			$data = array(
				"canceled_order"	=> 1
			);
			// $cancel_item = $this->morder->update_order_summary_v2($unique_id, $equipment_id, $patient_id, $data);
			$cancel_item = $this->morder->update_order_summary_v3($order_id, $data);
			if($cancel_item) {
				/*
				| Check if there are still items that are not cancelled in a WorkOrder
				*/
				$work_order_details = $this->morder->get_work_order_items($unique_id, $patient_id);
				$is_empty_work_order_details = 0;
				// $current_patient_address = $this->morder->get_current_patient_address($patient_id);
				if(!empty($work_order_details)) {
					$is_empty_work_order_details = 1;
				} else {
					/*
					| Check if the WorkOrder is a Customer Move,
					| the current location,
					| and if the WorkOrder was cancelled
					| then revert to the previous location
					*/
					if($original_activity_typeid == "4") {
						$current_patient_address = $this->morder->get_current_patient_address($patient_id);
						$testtemptest = "nakasulod kos cusmove";
						if($current_patient_address['id'] == $patient_address) {
							$patient_cusmove_order = $this->morder->check_patient_cusmove_order($patient_id, $unique_id);
							if(empty($patient_cusmove_order)) {
								$deletedAddress = $this->order_model->delete_address($patient_address, $patient_id);
								/*
								| Update the Patient's Address
								| or revert back to the old address
								*/
								$previous_patient_address = $this->morder->get_current_patient_address($patient_id);
								$previous_patient_address_details = array(
									'p_street' 			=> $previous_patient_address['street'],
									'p_placenum' 		=> $previous_patient_address['placenum'],
									'p_city' 			=> $previous_patient_address['city'],
									'p_state' 			=> $previous_patient_address['state'],
									'p_postalcode' 		=> $previous_patient_address['postal_code']
								);
								if($previous_patient_address['phonenum'] != null) {
									$previous_patient_address_details['p_phonenum'] = $previous_patient_address['phonenum'];
								}
								if($previous_patient_address['altphonenum'] != null) {
									$previous_patient_address_details['p_altphonenum'] = $previous_patient_address['altphonenum'];
								}
								if($previous_patient_address['nextofkin'] != null) {
									$previous_patient_address_details['p_nextofkin'] = $previous_patient_address['nextofkin'];
								}
								if($previous_patient_address['relationship'] != null) {
									$previous_patient_address_details['p_relationship'] = $previous_patient_address['relationship'];
								}
								if($previous_patient_address['nextofkinnum'] != null) {
									$previous_patient_address_details['p_nextofkinnum'] = $previous_patient_address['nextofkinnum'];
								}

								$update_patient_profile = $this->order_model->update_patient_profile_v2($patient_id, $previous_patient_address_details);
								$is_update_patient_profile = 0;

								$existing_items = $this->order_model->check_patient_activation($patient_id);
                                if (empty($existing_items)) {
                                    $data_patient_activation_status = array(
                                        'is_active' => 0
                                    );
                                    $this->order_model->update_patient_profile_v2($patient_id,$data_patient_activation_status);
                                    //Added for recurring 03/11/2019 - Adrian --- Start ---
                                    $recurring_order = $this->order_model->get_recurring_order_info($patient_id);
                                    $data_recurring = array('recurring_status' => 0);
                                    $this->order_model->cancel_recurring_order($recurring_order['recurring_id'], $data_recurring);
                                    $this->order_model->cancel_recurring_items($recurring_order['recurring_id'], $data_recurring);
                                    //Added for recurring 03/11/2019 - Adrian ---- End ----
                                    $is_deactivated_account = 1;
                                }
							}
						}
					}
				}
				$this->response_code = 0;
	            $this->response_message = 'Item Cancelled Successfully';
			}
		}


		echo json_encode(array(
			'is_updated_patient_id' => $is_update_patient_profile,
			'updated_patient_id' => $update_patient_profile,
			'patient_cusmove_order' => $patient_cusmove_order,
			'get_work_order_items' => $is_empty_work_order_details,
			'current_patient_address' => $current_patient_address,
			'previous_patient_address' => $previous_patient_address,
			'previous_patient_address_details' => $previous_patient_address_details,
			'testtemptest' => $testtemptest,
			'is_deactivated_account' => $is_deactivated_account,
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}
}
