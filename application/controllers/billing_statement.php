<?php
Class billing_statement extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		date_default_timezone_set('America/Los_Angeles');
		$this->load->model("billing_statement_model");
		$this->load->model("equipment_model");
		$this->load->model("billing_reconciliation_model");
		$this->load->model("order_model");
	}

	public function testingfunction () {
        print_me(date('Y-m-d h:i:s'));
	}

	public function billing_list()
	{
		$data['accounts'] = $this->billing_statement_model->list_for_account_v2($this->session->userdata('user_location'), 0, 20);
		$data['counting'] = $data['accounts']['totalAccountCount'];
		$data['search_type'] = "account_search";
		$this->templating_library->set('title','View All Accounts');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$this->templating_library->set_view('pages/searched_account_grid','pages/searched_account_grid', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function billing_list_load_more($start=0, $limit=10) {
		if($this->input->is_ajax_request())
		{
			$response = $this->billing_statement_model->list_for_account_v2($this->session->userdata('user_location'),$start,$limit);
		}
		
		echo json_encode($response);
	}

	public function statement_bill_header($hospice_id) {
		if($this->input->is_ajax_request())
		{
			$current = $this->billing_statement_model->get_past_due_amount_v2($hospice_id, 'current');
			$past_due_1_30 = $this->billing_statement_model->get_past_due_amount_v2($hospice_id, '30');
			$past_due_31_60 = $this->billing_statement_model->get_past_due_amount_v2($hospice_id, '60');
			$past_due_61_90 = $this->billing_statement_model->get_past_due_amount_v2($hospice_id, '90');
			$past_due_91 = $this->billing_statement_model->get_past_due_amount_v2($hospice_id, '91');
			$data['last_payment'] = $this->billing_statement_model->get_last_payment_by_current_v2($hospice_id);

			$data['current'] = $current['totaltotal'] +  $current['totalnoncap'] + $current['totalpurchaseitem'];
			$data['past_due_1_30'] = $past_due_1_30['totaltotal'] +  $past_due_1_30['totalnoncap'] + $past_due_1_30['totalpurchaseitem'];
			$data['past_due_31_60'] = $past_due_31_60['totaltotal'] +  $past_due_31_60['totalnoncap'] + $past_due_31_60['totalpurchaseitem'];
			$data['past_due_61_90'] = $past_due_61_90['totaltotal'] +  $past_due_61_90['totalnoncap'] + $past_due_61_90['totalpurchaseitem'];
			$data['past_due_91'] = $past_due_91['totaltotal'] +  $past_due_91['totalnoncap'] + $past_due_91['totalpurchaseitem'];
			$data['balance_due'] = $data['current'] + $data['past_due_1_30'] + $data['past_due_31_60'] + $data['past_due_61_90'] + $data['past_due_91'];
		}
		echo json_encode($data);
	}

	public function statement_bill($hospice_id) {
		$data['hospice_id'] = $hospice_id;
		$data['statement_bill'] = $this->billing_statement_model->get_statement_bill_by_hospice($hospice_id);
		$data['statement_bill_draft'] = $this->billing_statement_model->get_statement_bill_draft_by_hospice($hospice_id, "DESC");
		$data['statement_bill_invoice'] = $this->billing_statement_model->get_statement_bill_invoice_by_hospice($hospice_id, "DESC");
		// print_me($date['statement_bill_draft']);
		$data['account'] = $this->billing_statement_model->get_account($hospice_id);
		// $data['customer_days'] =$this->billing_statement_model->get_customer_days($hospice_id);
		// print_me($data['customer_days']);
		$service_date_from = $data['statement_bill']['service_date_from'];
		$service_date_to = date("Y-m-t", strtotime($service_date_from));

		// =================> Added 05/05/2021 ====> Start
		$data['has_customer'] = $this->billing_statement_model->has_customer_for_draft_statement($hospice_id, $service_date_from, $service_date_to);
		$data['service_date_from'] = $service_date_from;
		$data['service_date_to'] = $service_date_to;
		// =================> Added 05/05/2021 ====> End

		$data['customers'] = $this->billing_statement_model->get_all_customer($hospice_id, 0, 0, $service_date_from, $service_date_to);
		$data['is_all_order_confirmed'] = $this->billing_statement_model->is_all_order_confirmed($hospice_id);
		// print_me($data['customers']);
		$data['customers_orders'] = Array(
			"cus_orders"	=> Array(),
			"limit"			=> $data['customers']['limit'],
			"start"			=> $data['customers']['start'],
			"totalCount"	=> $data['customers']['totalCount'],
			"totalCustomerCount"	=> $data['customers']['totalCustomerCount']
		);
		
		$new_data = Array();
		foreach($data['customers']['result'] as $key => $value) {
			$customer_orders = $this->billing_statement_model->customer_order_list($hospice_id, $value['patientID'], $service_date_from, $service_date_to);
			$new_data = Array(
				"customer_info"  => $value,
				"customer_orders" => $customer_orders
			);
			$data['customers_orders']['cus_orders'][$key] = $new_data;
		}
		// print_me($data);	
		$this->templating_library->set('title','Account Statement');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') {
			$this->templating_library->set_view('pages/statement_bill','pages/statement_bill', $data);
		} else if (hasAcccessHospiceBilling($hospice_id)) {
			if ($hospice_id === $this->session->userdata("group_id")) {
				$this->templating_library->set_view('pages/statement_bill','pages/statement_bill', $data);
			}
		} else if ($this->session->userdata('account_type') == 'biller') {
			$this->templating_library->set_view('pages/statement_bill','pages/statement_bill', $data);
		}
		
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function statement_bill_load_more ($hospice_id,$start=0, $limit=10) {
		// $response = array("customers_orders" => array());

		if($this->input->is_ajax_request())
		{
			$data['statement_bill'] = $this->billing_statement_model->get_statement_bill_by_hospice($hospice_id);
			$service_date_from = $data['statement_bill']['service_date_from'];
			$service_date_to = date("Y-m-t", strtotime($service_date_from));
			$data['customers'] = $this->billing_statement_model->get_all_customer($hospice_id, $start, $limit, $service_date_from, $service_date_to);
			// $current_month = date('Y-m');
			$response['customers_orders'] = array(
				"cus_orders"			=> array(),
				"limit"					=> $data['customers']['limit'],
				"start"					=> $data['customers']['start'],
				"totalCount"			=> $data['customers']['totalCount'],
				"totalCustomerCount"	=> $data['customers']['totalCustomerCount']
			);
			
			$new_data = array();
			foreach($data['customers']['result'] as $key => $value) {
				$customer_orders = $this->billing_statement_model->customer_order_list($hospice_id, $value['patientID'], $service_date_from, $service_date_to);
				$new_data = array(
					"customer_info"  => $value,
					"customer_orders" => $customer_orders
				);
				$response['customers_orders']['cus_orders'][$key] = $new_data;
			}
		}
		
		//print_me($response);
		echo json_encode($response);
	}

	public function get_item_full_description () {
		$data = $_POST['data'];
		// print_me($data);
		$item_full_description = [];
		$summary_response = [];
		// foreach($data as $value) {
			foreach($data as $key => $summary) {
				$summary['item_description_data'] = "";
				if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
				{
					if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
					{
						$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
						$summary['item_description_data'] = $summary['key_desc'];
					}
					else
					{
						//*** DELIVERY not CONFIRMED yet dri mo display ang item ***//
						//check if naay sub equipment using equipment id, work uniqueId
						$subequipment_id = get_subequipment_id($summary['equipmentID']);

						//gets all the id's under the order
						if($subequipment_id)
						{
							$count = 0;
							$patient_lift_sling_count = 0;
							$high_low_full_electric_hospital_bed_count = 0;
							$equipment_count = 0;
							$my_count_sign = 0;
							$my_first_sign = 0;
							$my_second_sign = 0;

							foreach ($subequipment_id as $key) {
								$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);

								if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
								{
									if(empty($value))
									{
										$my_first_sign = 1;
									}
								}
								if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
								{
									if(empty($value))
									{
										$my_second_sign = 1;
									}
								}
								if($my_second_sign == 1 && $my_first_sign == 1)
								{
									$my_count_sign = 1;
								}
								if($value)
								{
									$count++;
									//full electric hospital bed equipment
									if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Hi-Low Full Electric Hospital Bed equipment
									else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Patient Lift with Sling
									else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
									{
										$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
									}
									//Patient Lift Electric with Sling
									else if($summary['equipmentID'] == 353)
									{
										$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
									}
									//Patient Lift Sling
									else if($summary['equipmentID'] == 196)
									{
										$summary['item_description_data'] = $key['key_desc'];
									}
									//(54 & 17) Geri Chair || (66 & 39) Shower Chair
									else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
									{
										$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
									}
									// Oxygen E Portable System && Oxygen Liquid Portable
									else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									break;
									}
									//Oxygen Cylinder Rack
									else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
									{
										$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
										break;
									}
									//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
									else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
									{
										if($my_count_sign == 0)
										{
											//wheelchair & wheelchair reclining
											if($count == 1)
											{
												$temp_item_description = "";
												$temp_item_description2 = "";

												if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
												{
													$key['key_desc'] = '16" Narrow';
												}
												else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
												{
													$key['key_desc'] = '18" Standard';
												}
												else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
												{
													$key['key_desc'] = '20" Wide';
												}
												else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
												{
													$key['key_desc'] = '22" Extra Wide';
												}
												else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
												{
													$key['key_desc'] = '24" Bariatric';
												}
												$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
											}
											else
											{
												$temp_item_description2 = " With ".$key['key_desc'];
											}
											$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
										}
										else
										{
											$temp_item_description = $summary['key_desc'];
											if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
											{
												$temp_item_description2 = " With Elevating Legrests";
											}
											else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
											{
												$temp_item_description2 = " With Footrests";
											}
											$initial_temp_item_description = '20" Wide';
											$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
											break;
										}
									}
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
										{
											$key['key_desc'] = '17" Narrow';
										}
										else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
										{
											$key['key_desc'] = '19" Standard';
										}
										$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
										$summary['item_description_data'] = $temp_item_description;
									}
									else if($summary['equipmentID'] == 30)
									{
										if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
										{
											if($count == 3)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$temp_item_description = " With ".$key['key_desc'];
												$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
									else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
									{
										$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
										if(strlen($samp) > 30)
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
										}
										else
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
										}

										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
										break;
									}
									else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
									{
										$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
										if($count == 1)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
										}
									}
									else if($summary['equipmentID'] == 282)
									{

									}
									//equipments that has no subequipment but gets inside the $value if statement
									else if($summary['equipmentID'] == 14)
									{
										$summary['item_description_data'] = $summary['key_desc'];
									}
									else
									{
										if($summary['categoryID'] == 1)
										{
											$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
											if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 14)
											{
												$summary['item_description_data'] = $summary['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
											{
												$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 353)
											{
												$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
											}
											else
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								} //end of $value
								//for Oxygen E cylinder do not remove as it will cause errors
								else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
								{
									break;
								}
								else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
								{

								}
								else if($summary['equipmentID'] == 282)
								{
									$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
									break;
								}
								//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
								else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
									break;
								}
								//equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
								else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								//for equipments with subequipment but does not fall in $value
								else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
								{
									if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
									{
										$patient_lift_sling_count++;
										if($patient_lift_sling_count == 6)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									else if($summary['equipmentID'] == 398)
									{
										if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
										{
											$high_low_full_electric_hospital_bed_count++;
											if($high_low_full_electric_hospital_bed_count == 2)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
									}
									// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										$equipment_count++;
										if($equipment_count == 2)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								}
								else
								{
									if($summary['categoryID'] == 1)
									{
										$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
										if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
											break;
										}
										else if($non_capped_copy['noncapped_reference'] == 14)
										{
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{

										}
									}
									else
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									}
								}
							}
						}
						else
						{
							if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
							{
								$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
								$summary['item_description_data'] = $summary['key_desc'];
							}
							else
							{
								$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
								$summary['item_description_data'] = $summary['key_desc'];
							}
						}
					}
				}
				else
				{
					$summary['item_description_data'] = "";
					if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
					{
						$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
						$summary['item_description_data'] = $summary['key_desc'];
					}
					else
					{
						//check if the item has sub-equipments using equipment id
						$subequipment_id = get_subequipment_id($summary['equipmentID']);
						//gets all the id's under the order
						if($subequipment_id)
						{
							$count = 0;
							$patient_lift_sling_count = 0;
							$high_low_full_electric_hospital_bed_count = 0;
							$equipment_count = 0;
							$my_count_sign = 0;
							$my_first_sign = 0;
							$my_second_sign = 0;
							foreach ($subequipment_id as $key) {
								$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
								if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
								{
									if(empty($value))
									{
										$my_first_sign = 1;
									}
								}
								if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
								{
									if(empty($value))
									{
										$my_second_sign = 1;
									}
								}
								if($my_second_sign == 1 && $my_first_sign == 1)
								{
									$my_count_sign = 1;
								}
								if($value)
								{
									$count++;
									//equipment full electric hospital bed
									if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Hi-Low Full Electric Hospital Bed equipment
									else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Patient Lift with Sling
									else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
									{
										$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
									}
									//Patient Lift Electric with Sling
									else if($summary['equipmentID'] == 353)
									{
										$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
									}
									//Patient Lift Sling
									else if($summary['equipmentID'] == 196)
									{
										$summary['item_description_data'] = $key['key_desc'];
									}
									//(54 & 17) Geri Chair || (66 & 39) Shower Chair
									else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
									{
										$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
									}
									// Oxygen E Portable System && Oxygen Liquid Portable
									else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
										break;
									}
									//Oxygen Cylinder Rack
									else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
									{
										$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
										break;
									}
									//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
									else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
									{
										if($my_count_sign == 0)
										{
											//wheelchair & wheelchair reclining
											if($count == 1)
											{
												$temp_item_description = "";
												$temp_item_description2 = "";

												if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
												{
													$key['key_desc'] = '16" Narrow';
												}
												else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
												{
													$key['key_desc'] = '18" Standard';
												}
												else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
												{
													$key['key_desc'] = '20" Wide';
												}
												else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
												{
													$key['key_desc'] = '22" Extra Wide';
												}
												else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
												{
													$key['key_desc'] = '24" Bariatric';
												}
												$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
											}
											else
											{
												$temp_item_description2 = " With ".$key['key_desc'];
											}
											$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
										}
										else
										{
											$temp_item_description = $summary['key_desc'];
											if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
											{
												$temp_item_description2 = " With Elevating Legrests";
											}
											else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
											{
												$temp_item_description2 = " With Footrests";
											}
											$initial_temp_item_description = '20" Wide';
											$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
											break;
										}
									}
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
										{
											$key['key_desc'] = '17" Narrow';
										}
										else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
										{
											$key['key_desc'] = '19" Standard';
										}
										$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
										$summary['item_description_data'] = $temp_item_description;
									}
									else if($summary['equipmentID'] == 30)
									{
										if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
										{
											if($count == 3)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$temp_item_description = " With ".$key['key_desc'];
												$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
									else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
									{
										$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
										if(strlen($samp) > 30)
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
										}
										else
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
										}

										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
										break;
									}
									else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
									{
										$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
										if($count == 1)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
										}
									}
									else if($summary['equipmentID'] == 282)
									{

									}
									//equipments that has no subequipment but gets inside the $value if statement
									else if($summary['equipmentID'] == 14)
									{
										$summary['item_description_data'] = $summary['key_desc'];
									}
									else
									{
										if($summary['categoryID'] == 1)
										{
											$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
											if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 14)
											{
												$summary['item_description_data'] = $summary['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
											{
												$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 353)
											{
												$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
											}
											else
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								} //end of $value
								//for Oxygen E cylinder do not remove as it will cause errors
								else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
								{
									break;
								}
								else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
								{

								}
								else if($summary['equipmentID'] == 282)
								{
									$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
									break;
								}
								//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
								else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
									break;
								} //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
								else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								//for equipments with subequipment but does not fall in $value
								else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
								{
									if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
									{
										$patient_lift_sling_count++;
										if($patient_lift_sling_count == 6)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									else if($summary['equipmentID'] == 398)
									{
										if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
										{
											$high_low_full_electric_hospital_bed_count++;
											if($high_low_full_electric_hospital_bed_count == 2)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
									}
									// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										$equipment_count++;
										if($equipment_count == 2)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								}
								else
								{
									if($summary['categoryID'] == 1)
									{
										$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
										if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
											break;
										}
										else if($non_capped_copy['noncapped_reference'] == 14)
										{
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{

										}
									}
									else
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									}
								}
							}
						}
						else
						{
							$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
							$summary['item_description_data'] = $summary['key_desc'];
						}
					}
				}

				$summary_response[] = array(
					"item_full_description" => $summary['item_description_data'],
					"patientID" => $summary['patientID'],
					"uniqueID" => $summary['uniqueID'],
					"equipmentID" => $summary['equipmentID']
				);
				$item_full_description[] = $summary['item_description_data'];
				// print_me($summary['item_description_data']);
			}
		// }
		// print_me($item_full_description);
		
		echo json_encode($summary_response);
	}

	public function get_item_group_rates () {
		$data = $_POST['data'];
		// print_me($data);
		$summary_response = [];
		foreach($data as $key => $item) {
			switch($item['equipmentID']) {
				case 49:
				$subequipdetails = array();
				$check = "wala";
				if(strpos($item['key_desc'], "16") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
				}
				if(strpos($item['key_desc'], "18") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
				}
				if(strpos($item['key_desc'], "20") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
				}
				if(strpos($item['key_desc'], "22") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "22_inch");
				}
				if(strpos($item['key_desc'], "24") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "24_inch");
				}

				if($subequipdetails != null && count($subequipdetails) > 0) {

				}
				$summary_response[] = array(
					"sub_equip_details" => $subequipdetails,
					"patientID" => $item['patientID'],
					"uniqueID" => $item['uniqueID'],
					"equipmentID" => $item['equipmentID'],
					"equipmentVal" => $item['equipmentVal'],
					"addressID" => $item['addressID'],
				);
				break;
				case 64:
				$subequipdetails = array();
				if(strpos($item['key_desc'], "16") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
				}
				if(strpos($item['key_desc'], "18") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
				}
				if(strpos($item['key_desc'], "20") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
				}

				if($subequipdetails != null && count($subequipdetails) > 0) {

				}
				$summary_response[] = array(
					"sub_equip_details" => $subequipdetails,
					"patientID" => $item['patientID'],
					"uniqueID" => $item['uniqueID'],
					"equipmentID" => $item['equipmentID'],
					"equipmentVal" => $item['equipmentVal'],
					"addressID" => $item['addressID'],
				);
				break;
				case 32:
				$subequipdetails = array();
				if(strpos($item['key_desc'], "E Cylinder 6 Rack") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_6_rack");
				}
				if(strpos($item['key_desc'], "E Cylinder 12 Rack") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_12_rack");
				}
				if(strpos($item['key_desc'], "M6 Cylinder 6 Rack") != false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_6_rack");
				}
				if(strpos($item['key_desc'], "M6 Cylinder 12 Rack") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_12_rack");
				}

				if($subequipdetails != null && count($subequipdetails) > 0) {

				}
				$summary_response[] = array(
					"sub_equip_details" => $subequipdetails,
					"patientID" => $item['patientID'],
					"uniqueID" => $item['uniqueID'],
					"equipmentID" => $item['equipmentID'],
					"equipmentVal" => $item['equipmentVal'],
					"addressID" => $item['addressID'],
				);
				break;
				case 29: case 334: case 343:
				$getassignedequipmentid = $this->equipment_model->get_assigned_equipment_details($item['hospiceID'], 29);
				$subequipdetails = array();
				if(strpos($item['key_desc'], "5L") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "5_liters");
				}
				if(strpos($item['key_desc'], "10L") !== false){
					$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "10_liters");
				}

				if($subequipdetails != null && count($subequipdetails) > 0) {

				}
				$summary_response[] = array(
					"sub_equip_details" => $subequipdetails,
					"patientID" => $item['patientID'],
					"uniqueID" => $item['uniqueID'],
					"equipmentID" => $item['equipmentID'],
					"equipmentVal" => $item['equipmentVal'],
					"addressID" => $item['addressID'],
					"getassignedequipmentid" => $getassignedequipmentid
				);
				break;
			}
		}

		echo json_encode($summary_response);
	}

	public function change_cusdays_draft_statement($patientID, $service_date) {
		if ($_POST) {
			$newValue = $this->input->post("value");

			$data = array(
				"customer_days" => $newValue
			);
			$service_date_to = date("Y-m-t", strtotime($service_date));
			$is_existing = $this->billing_statement_model->get_customer_days_length_of_stay($patientID, $service_date_to, $service_date);

			if (!empty($is_existing)) {
				$response = $this->billing_statement_model->change_cus_days_draft_statement($data, $patientID, $service_date);
				$tempstatus = 'existing';
			} else {
				$patient_info = $this->billing_statement_model->get_patients_info($patientID);
				$temp_data_cus_day_los = array(
					'patientID' => $patientID,
					'customer_days' => $newValue,
					'length_of_stay' => 0,
					'service_date' => $service_date,
					'hospiceID' => $patient_info['ordered_by']
				);
				$data_cus_day_los[] = $temp_data_cus_day_los;
				$response = $this->billing_statement_model->insert_customer_length_of_stay($data_cus_day_los);
				$tempstatus = 'not existing';
			}
			

			if ($response) {
				$error     = 0;
            	$message  = "Successfully updated";
			} else {
				$this->common->code     = 1;
				$this->common->message	= "Failed to update.";
			}
			
			echo json_encode(array(
				// "status"	=> $tempstatus,
				// "is_existing"	=> $is_existing,
				"error"		=> $error,
				"message"	=> $message
			));
		}
	}

	public function statement_draft_load_more ($draft_id,$start=0, $limit=10) {
		// $response = array("customers_orders" => array());

		if($this->input->is_ajax_request())
		{
			$data['statement_bill'] = $this->billing_statement_model->get_draft_details($draft_id);
			$hospice_id = $data['statement_bill']['hospiceID'];
			$service_date_from = $data['statement_bill']['service_date_from'];
			$service_date_to = date("Y-m-t", strtotime($service_date_from));
			$data['customers'] = $this->billing_statement_model->get_all_customer_for_draft_statement($hospice_id, $start, $limit, $service_date_from, $service_date_to);
			// $current_month = date('Y-m');
			$response['customers_orders'] = array(
				"cus_orders"			=> array(),
				"limit"					=> $data['customers']['limit'],
				"start"					=> $data['customers']['start'],
				"totalCount"			=> $data['customers']['totalCount'],
				"totalCustomerCount"	=> $data['customers']['totalCustomerCount']
			);
			
			$new_data = array();
			foreach($data['customers']['result'] as $key => $value) {
				$customer_orders = $this->billing_statement_model->customer_order_list_for_draft_statement($hospice_id, $value['patientID'], $service_date_from, $service_date_to);
				$cus_days_los = $this->billing_statement_model->get_customer_days_length_of_stay($value['patientID'], $service_date_to, $service_date_from);
				$value['patient_days'] = $cus_days_los['customer_days'];
				$new_data = array(
					"customer_info"  => $value,
					"customer_orders" => $customer_orders
				);
				$response['customers_orders']['cus_orders'][$key] = $new_data;
			}
		}
		
		//print_me($response);
		echo json_encode($response);
	}


	public function statement_draft() {
		$temp = $this->billing_statement_model->get_all_statement_bill_draft($this->session->userdata('user_location'));
		// print_me($temp);
		$data['statement_draft'] = array();
		// $disable_draft = false;
		// $create_inv = false;
		// foreach($temp as $key => $draft) {
		// 	$data_temp = array();
		// 	$total_balance = 0;
		// 	$customer_days = $this->billing_statement_model->get_customer_days($draft['hospiceID']);
		// 	// print_me($draft['daily_rate']);
		// 	$noncapped = $this->billing_statement_model->get_category_total($draft['hospiceID'], 2);
		// 	$purchase = $this->billing_statement_model->get_category_total($draft['hospiceID'], 3);
		// 	$total_noncapped = 0;
		// 	$total_purchase = 0;
		// 	// print_me($temp);
		// 	foreach($noncapped as $value) {
		// 		if($value['summary_pickup_date'] == "0000-00-00") {
		// 			$now = time();
		// 		} else {
		// 			$now = strtotime($value['summary_pickup_date']);
		// 		}
		// 		$your_date = strtotime($value['pickup_date']);
		// 		$datediff = $now - $your_date;
		// 		$rounddatediff = round($datediff / (60 * 60 * 24));
		// 		$total_noncapped += $value['purchase_price']*$rounddatediff;
		// 	}

		// 	foreach($purchase as $value) {
		// 		$total_purchase += $value['purchase_price']*$value['equipment_value'];
		// 	}

		// 	$draft_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_draft($draft['acct_statement_draft_id']);

		// 	$total_balance += (float) $customer_days[0]['cus_days'] * (float) $draft['daily_rate'];
		// 	$total_balance += $total_noncapped;
		// 	$total_balance += $total_purchase;
			
		// 	$total_balance -=  !isset($draft_reconciliation['credit']) ? 0 : (float) $draft_reconciliation['credit']; 	// Deduct
		// 	$total_balance +=  !isset($draft_reconciliation['owe']) ? 0 : (float) $draft_reconciliation['owe']; 		// Add

		// 	$create_inv = 1;
		// 	$is_all_order_confirmed = $this->billing_statement_model->is_all_order_confirmed_v2($draft['hospiceID']);
		// 	if(!empty($is_all_order_confirmed)) {
		// 		$create_inv = 0;
		// 	}

		// 	$disable_draft = 1;
		// 	if($key > 0) {
		// 		if($temp[$key-1]['hospiceID'] == $draft['hospiceID']) {
		// 			$disable_draft = 0;
		// 		} 
		// 		// else {
		// 		// 	$data['statement_draft'][$key-1]['is_disable'] = 0;
		// 		// }
				
		// 	}
		// 	// if($key == count($temp)-1 && $temp[$key-1]['hospiceID'] != $draft['hospiceID']) {
		// 	// 	$disable_draft = 0;
		// 	// }
		// 	$data_temp = array(
		// 		"acct_statement_draft_id"	=> $draft['acct_statement_draft_id'],
		// 		"hospiceID" 				=> $draft['hospiceID'],
		// 		"hospice_name" 				=> $draft['hospice_name'],
		// 		"service_date_from"			=> $draft['service_date_from'],
		// 		"service_date_to"			=> $draft['service_date_to'],
		// 		"statement_no"				=> $draft['statement_no'],
		// 		"total_balance"				=> $total_balance,
		// 		"is_manual"					=> $draft['is_manual'],
		// 		"is_disable"				=> $disable_draft,
		// 		"is_create_inv"				=> $create_inv
		// 	);
		// 	$data['statement_draft'][$key] = $data_temp;
		// 	// print_me($data['statement_draft']);
		// }
		
		$this->templating_library->set('title','Draft Account Statement');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$this->templating_library->set_view('pages/statement_draft','pages/statement_draft', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function test_lot_search() {
		$data_get = $this->input->get();
		$data['lotNo'] = (isset($data_get['lotNo'])) ? $data_get['lotNo'] : '';
		$where = array(
			'lotNo' => str_replace(' ', '%', $data['lotNo']),
		);

		print_me($data['lotNo']);
		print_me($where);
	}

	public function is_all_order_confirmed_v2_testing_2021() {
		$is_all_order_confirmed = $this->billing_statement_model->is_all_order_confirmed_v2(190, '2021-07-01', '2021-07-31');

		print_me($is_all_order_confirmed);
	}

	public function get_statement_draft() {
		$response_data = array(
            'data' => array(),
            'draw' => 1,
            'recordsFiltered' => 0,
            'recordsTotal' => 0,
        );

        if ($this->input->is_ajax_request()) {
            $datatable = $this->input->get();
            $start = $datatable['start'];
            $limit = $datatable['length'];
            $filters = array(
                'search_item_fields_statement_draft' => $datatable['search']['value'],
            );

            $column = array(
                'hospice_name',
                'statement_no'
            );
			$filters[$column[$datatable['order'][0]['column']]] = $datatable['order'][0]['dir'];
            $result = $this->billing_statement_model->get_all_statement_bill_draft_by_hospice_v2($filters, $this->session->userdata('user_location'), $start, $limit);
            $index_counter = 1;
            $create_invoice_counter = 0;
			// print_me($limit);
            if ($result['totalCount'] > 0) {
				$prev_hospiceID = 0;
                foreach ($result['result'] as $key => $value) {
                	$data_temp = array();
					// $total_balance = 0;

					// // $customer_days = $this->billing_statement_model->get_customer_days($value['hospiceID']);
					// $customer_days_list = $this->billing_statement_model->get_customer_days_v2($value['hospiceID'], $value['service_date_from'], $value['service_date_to']);
					// $customer_days = 0;
					// foreach($customer_days_list as $cusdays_key => $cusdayval) {
					// 	// $customer_orders = $this->billing_statement_model->customer_order_list($value['hospiceID'], $cusdayval['patientID'], $value['service_date_from'], $value['service_date_to']);
					// 	// if(!empty($customer_orders)) {
					// 		$customer_days += $cusdayval['cus_days'];
					// 	// }
						
					// }
					// // $customer_days = $this->billing_statement_model->get_customer_days_v2($value['hospiceID'], $value['service_date_from'], $value['service_date_to']);
					
					// // print_me($draft['daily_rate']);
					// $noncapped = $this->billing_statement_model->get_category_total($value['hospiceID'], 2);
					// $purchase = $this->billing_statement_model->get_category_total($value['hospiceID'], 3);
					// $total_noncapped = 0;
					// $total_purchase = 0;
					// // print_me($temp);
					// foreach($noncapped as $non) {
					// 	if($non['summary_pickup_date'] == "0000-00-00") {
					// 		$now = time();
					// 	} else {
					// 		$now = strtotime($non['summary_pickup_date']);
					// 	}
					// 	$your_date = strtotime($non['pickup_date']);
					// 	$datediff = $now - $your_date;
					// 	$rounddatediff = round($datediff / (60 * 60 * 24));
					// 	$total_noncapped += $non['purchase_price']*$rounddatediff;
					// }

					// foreach($purchase as $pur) {
					// 	$total_purchase += $pur['purchase_price']*$pur['equipment_value'];
					// }

					// $draft_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_draft($value['acct_statement_draft_id']);

					// $total_balance += (float) $customer_days * (float) $value['daily_rate'];
					// $total_balance += $total_noncapped;
					// $total_balance += $total_purchase;
					
					// $total_balance -=  !isset($draft_reconciliation['credit']) ? 0 : (float) $draft_reconciliation['credit']; 	// Deduct
					// $total_balance +=  !isset($draft_reconciliation['owe']) ? 0 : (float) $draft_reconciliation['owe']; 		// Add

					$create_inv = 1;
					$is_all_order_confirmed = $this->billing_statement_model->is_all_order_confirmed_v2($value['hospiceID'], $value['service_date_from'], $value['service_date_to']);
					if(!empty($is_all_order_confirmed)) {
						$create_inv = 0;
					}

					$disable_draft = 1;
					if($key > 0) {
						if($result['result'][$key-1]['hospiceID'] == $value['hospiceID']) {
							$disable_draft = 0;
							$create_invoice_counter++;
						} 
						else {
							$create_invoice_counter = 0;
							// $data['statement_draft'][$key-1]['is_disable'] = 0;
						}
						
					}
					// if($key == count($temp)-1 && $temp[$key-1]['hospiceID'] != $draft['hospiceID']) {
					// 	$disable_draft = 0;
					// }

					$data_temp = array(
						"acct_statement_draft_id"	=> $value['acct_statement_draft_id'],
						"hospiceID" 				=> $value['hospiceID'],
						"hospice_name" 				=> $value['hospice_name'],
						"service_date_from"			=> $value['service_date_from'],
						"service_date_to"			=> $value['service_date_to'],
						"statement_no"				=> $value['statement_no'],
						// "total_balance"				=> $total_balance,
						"is_manual"					=> $value['is_manual'],
						"is_disable"				=> $disable_draft,
						"is_create_inv"				=> $create_inv
					);

                	$disable_checkbox = "";
                    $disable_checkbox_cursor = "";
                    $select_all_tag = "";
                    $is_underline = "";
                    $is_popover = "";
                    $disable_customerdays = "";
                    $create_first_message = "";
                    if($data_temp['is_disable'] == 0) {
                        $disable_checkbox = "disabled";
                        $disable_checkbox_cursor = "cursor: not-allowed !important;";
                        $create_first_message = "Create Invoice ".$create_invoice_counter;
                    } else {
                        if($data_temp['is_create_inv'] == 0) {
                            $is_underline = "border-bottom: 1px dotted red; text-decoration: none;";
                            $is_popover = "class='data_tooltip' title='Confirm Work Order(s).'";
                            $disable_checkbox = "disabled";
                            $disable_checkbox_cursor = "cursor: not-allowed !important;";
                        } else {
                            $select_all_tag = "select_all_create_invoice";
                        }
                    }
                	if($value['track_census'] == 1 && $value['customer_days'] == null) {
                		if($data_temp['is_disable'] != 0) {
                			$disable_customerdays = "Customer Days Needed!";
                		}
                		if($disable_checkbox == "") {
                			$disable_checkbox = "disabled";
                            $disable_checkbox_cursor = "cursor: not-allowed !important;";
                		}
                	}

                    $value['checkbox'] = '<label class="i-checks data_tooltip" title="'.$disable_customerdays.' '.$create_first_message.'" style="'.$disable_checkbox_cursor.'"> '.
                    						'<input type="checkbox"  class="create-invoice '.$select_all_tag.'"'.
											' data-draft-id="'.$value['acct_statement_draft_id'].'" '.
											' data-hospice-name="'.$value['hospice_name'].'" '.$disable_checkbox.'/>'.
                    						'<i></i>'.
                    						'</label>';
                    $value['hospice_name'] = '<span '.$is_popover.' style="'.$is_underline.'">'.$value['hospice_name'].'</span>';
                    $value['service_date'] =  date('m/d/Y', strtotime($value['service_date_from']))." - ".date('m/d/Y', strtotime($value['service_date_to']));
                    $value['statement_no'] = '<div style="cursor: pointer" class="view_statement_bill_details row_index_"'.$index_counter.' data-draft-id="'.$value['acct_statement_draft_id'].'" '.
                    						'data-hospice-id="'.$value['hospiceID'].'">'.substr($value['statement_no'],3, 10).
                    						'</div>';

                    // $value['balance_due'] = number_format((float)$data_temp['total_balance'], 2, '.', '');

                    $hide_button = "";
                    if($data_temp['is_manual'] == 1) {
                        $hide_button = "display: none;";
                    }
                    $disable_button = "";
                    $disable_style = "";
                    $button_class_color = "danger";
                    $button_class_color_inv = "info";
                    if($data_temp['is_disable'] == 0) {
                        $disable_button = "disabled";
                        $disable_style = "background-color: #f6f8f8;";
                        $button_class_color = "default";
                        $button_class_color_inv = "default";
                    }

                    if($data_temp['hospiceID'] != $prev_hospiceID) {
                        if($result['result'][$key+1]['hospiceID'] != $value['hospiceID']) {
                            $disable_button = "disabled";
                            $disable_style = "background-color: #f6f8f8;";
                            $button_class_color = "default";
                            $button_class_color_inv = "default";
                        }
                    }

                    $value['action'] = '<button class="cancel_draft_btn btn btn-xs btn-'.$button_class_color.'"'.
                    					'style="'.$hide_button.' '.$disable_style.'" '.
                    					'data-draft-id="'.$value['acct_statement_draft_id'].'" data-hospice-id="'.$value['hospiceID'].'" '.$disable_button.'>'.
                    					'<Strong>Cancel</Strong></button>';

                    $response_data['data'][] = $value;
                }
            }

            $response_data['draw'] = $datatable['draw'];
            $response_data['recordsFiltered'] = $result['totalCount'];
            $response_data['recordsTotal'] = $result['totalCount'];
        }
        echo json_encode($response_data);
	}

	//billing_statement_details
	public function billing_statement_details($draft_id, $hospice_id) {
		$data['draft_details'] = $this->billing_statement_model->get_draft_details($draft_id);
		// print_me($data['draft_details']);
		$data['account'] = $this->billing_statement_model->get_account($hospice_id);
		$data['customer_days'] = $this->billing_statement_model->get_customer_days($hospice_id);
		// print_me($data['customer_days']);
		$service_date_from = $data['draft_details']['service_date_from'];
		$service_date_to = date("Y-m-t", strtotime($service_date_from));
		$data['customers'] = $this->billing_statement_model->get_all_customer($hospice_id, 0, 0, $service_date_from, $service_date_to);
		// print_me($data['customers']);
		$data['customers_orders'] = Array(
			"cus_orders"	=> Array(),
			"limit"			=> $data['customers']['limit'],
			"start"			=> $data['customers']['start'],
			"totalCount"	=> $data['customers']['totalCount'],
			"totalCustomerCount"	=> $data['customers']['totalCustomerCount']
		);
		$new_data = Array();
		foreach($data['customers']['result'] as $key => $value) {
			$customer_orders = $this->billing_statement_model->customer_order_list($hospice_id, $value['patientID']);
			$cus_days_los = $this->billing_statement_model->get_customer_days_length_of_stay($value['patientID'], $service_date_to, $service_date_from);
			$value['patient_days'] = $cus_days_los['customer_days'];
			// print_me($customer_orders);
			$new_data = Array(
				"customer_info"  => $value,
				"customer_orders" => $customer_orders
			);
			$data['customers_orders']['cus_orders'][$key] = $new_data;
		}
		$this->templating_library->set('title', 'Account Statement Details');
        $this->templating_library->set_view('pages/statement_bill_details', 'pages/statement_bill_details', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
	}

	//statement_activity
	// public function statement_activity() {
	// 	$data['statement_activity'] = $this->billing_statement_model->get_all_statement_activity($this->session->userdata('user_location'));
	// 	$data['invoices_reconciliation'] = array();

	// 	foreach($data['statement_activity'] as $value) {
	// 		$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['acct_statement_invoice_id']);
	// 		$data['invoices_reconciliation'][] = array(
	// 			"credit"	=> !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'],
	// 			"owe"		=> !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe']
	// 		);
	// 	}
		
	// 	// print_me($data['statement_activity']);
	// 	$this->templating_library->set('title','Activity Statements');
	// 	$this->templating_library->set_view('common/head','common/head');
	// 	$this->templating_library->set_view('common/header','common/header');
	// 	$this->templating_library->set_view('common/nav','common/nav', $data);
	// 	$this->templating_library->set_view('pages/statement_activity','pages/statement_activity', $data);
	// 	$this->templating_library->set_view('common/footer','common/footer');
	// 	$this->templating_library->set_view('common/foot','common/foot');
	// }
	public function get_category_total_testing($hospice_id, $category_id) {
		// if($this->input->is_ajax_request())
		// {
			$statement_bill = $this->billing_statement_model->get_statement_bill_by_hospice($hospice_id);
			$service_date_from = "2021-04-01";
			$service_date_to = "2021-04-30";
			$temp = $this->billing_statement_model->get_category_total($hospice_id, $category_id, $service_date_from, $service_date_to);
			$total = 0;
			// print_me($temp);
			foreach($temp as $cus_value) {
				if($cus_value['equip_is_package'] == 0 && $cus_value['is_package'] == 1) {
					continue;
				}

				// Get Full Description - Start
				$summary = $cus_value;
				$summary['item_description_data'] = "";
				if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
				{
					if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
					{
						$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
						$summary['item_description_data'] = $summary['key_desc'];
					}
					else
					{
						//*** DELIVERY not CONFIRMED yet dri mo display ang item ***//
						//check if naay sub equipment using equipment id, work uniqueId
						$subequipment_id = get_subequipment_id($summary['equipmentID']);

						//gets all the id's under the order
						if($subequipment_id)
						{
							$count = 0;
							$patient_lift_sling_count = 0;
							$high_low_full_electric_hospital_bed_count = 0;
							$equipment_count = 0;
							$my_count_sign = 0;
							$my_first_sign = 0;
							$my_second_sign = 0;

							foreach ($subequipment_id as $key) {
								$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);

								if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
								{
									if(empty($value))
									{
										$my_first_sign = 1;
									}
								}
								if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
								{
									if(empty($value))
									{
										$my_second_sign = 1;
									}
								}
								if($my_second_sign == 1 && $my_first_sign == 1)
								{
									$my_count_sign = 1;
								}
								if($value)
								{
									$count++;
									//full electric hospital bed equipment
									if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Hi-Low Full Electric Hospital Bed equipment
									else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Patient Lift with Sling
									else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
									{
										$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
									}
									//Patient Lift Electric with Sling
									else if($summary['equipmentID'] == 353)
									{
										$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
									}
									//Patient Lift Sling
									else if($summary['equipmentID'] == 196)
									{
										$summary['item_description_data'] = $key['key_desc'];
									}
									//(54 & 17) Geri Chair || (66 & 39) Shower Chair
									else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
									{
										$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
									}
									// Oxygen E Portable System && Oxygen Liquid Portable
									else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									break;
									}
									//Oxygen Cylinder Rack
									else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
									{
										$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
										break;
									}
									//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
									else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
									{
										if($my_count_sign == 0)
										{
											//wheelchair & wheelchair reclining
											if($count == 1)
											{
												$temp_item_description = "";
												$temp_item_description2 = "";

												if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
												{
													$key['key_desc'] = '16" Narrow';
												}
												else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
												{
													$key['key_desc'] = '18" Standard';
												}
												else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
												{
													$key['key_desc'] = '20" Wide';
												}
												else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
												{
													$key['key_desc'] = '22" Extra Wide';
												}
												else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
												{
													$key['key_desc'] = '24" Bariatric';
												}
												$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
											}
											else
											{
												$temp_item_description2 = " With ".$key['key_desc'];
											}
											$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
										}
										else
										{
											$temp_item_description = $summary['key_desc'];
											if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
											{
												$temp_item_description2 = " With Elevating Legrests";
											}
											else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
											{
												$temp_item_description2 = " With Footrests";
											}
											$initial_temp_item_description = '20" Wide';
											$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
											break;
										}
									}
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
										{
											$key['key_desc'] = '17" Narrow';
										}
										else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
										{
											$key['key_desc'] = '19" Standard';
										}
										$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
										$summary['item_description_data'] = $temp_item_description;
									}
									else if($summary['equipmentID'] == 30)
									{
										if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
										{
											if($count == 3)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$temp_item_description = " With ".$key['key_desc'];
												$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
									else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
									{
										$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
										if(strlen($samp) > 30)
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
										}
										else
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
										}

										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
										break;
									}
									else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
									{
										$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
										if($count == 1)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
										}
									}
									else if($summary['equipmentID'] == 282)
									{

									}
									//equipments that has no subequipment but gets inside the $value if statement
									else if($summary['equipmentID'] == 14)
									{
										$summary['item_description_data'] = $summary['key_desc'];
									}
									else
									{
										if($summary['categoryID'] == 1)
										{
											$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
											if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 14)
											{
												$summary['item_description_data'] = $summary['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
											{
												$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 353)
											{
												$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
											}
											else
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								} //end of $value
								//for Oxygen E cylinder do not remove as it will cause errors
								else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
								{
									break;
								}
								else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
								{

								}
								else if($summary['equipmentID'] == 282)
								{
									$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
									break;
								}
								//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
								else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
									break;
								}
								//equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
								else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								//for equipments with subequipment but does not fall in $value
								else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
								{
									if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
									{
										$patient_lift_sling_count++;
										if($patient_lift_sling_count == 6)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									else if($summary['equipmentID'] == 398)
									{
										if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
										{
											$high_low_full_electric_hospital_bed_count++;
											if($high_low_full_electric_hospital_bed_count == 2)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
									}
									// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										$equipment_count++;
										if($equipment_count == 2)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								}
								else
								{
									if($summary['categoryID'] == 1)
									{
										$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
										if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
											break;
										}
										else if($non_capped_copy['noncapped_reference'] == 14)
										{
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{

										}
									}
									else
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									}
								}
							}
						}
						else
						{
							if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
							{
								$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
								$summary['item_description_data'] = $summary['key_desc'];
							}
							else
							{
								$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
								$summary['item_description_data'] = $summary['key_desc'];
							}
						}
					}
				}
				else
				{
					$summary['item_description_data'] = "";
					if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
					{
						$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
						$summary['item_description_data'] = $summary['key_desc'];
					}
					else
					{
						//check if the item has sub-equipments using equipment id
						$subequipment_id = get_subequipment_id($summary['equipmentID']);
						//gets all the id's under the order
						if($subequipment_id)
						{
							$count = 0;
							$patient_lift_sling_count = 0;
							$high_low_full_electric_hospital_bed_count = 0;
							$equipment_count = 0;
							$my_count_sign = 0;
							$my_first_sign = 0;
							$my_second_sign = 0;
							foreach ($subequipment_id as $key) {
								$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
								if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
								{
									if(empty($value))
									{
										$my_first_sign = 1;
									}
								}
								if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
								{
									if(empty($value))
									{
										$my_second_sign = 1;
									}
								}
								if($my_second_sign == 1 && $my_first_sign == 1)
								{
									$my_count_sign = 1;
								}
								if($value)
								{
									$count++;
									//equipment full electric hospital bed
									if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Hi-Low Full Electric Hospital Bed equipment
									else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Patient Lift with Sling
									else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
									{
										$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
									}
									//Patient Lift Electric with Sling
									else if($summary['equipmentID'] == 353)
									{
										$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
									}
									//Patient Lift Sling
									else if($summary['equipmentID'] == 196)
									{
										$summary['item_description_data'] = $key['key_desc'];
									}
									//(54 & 17) Geri Chair || (66 & 39) Shower Chair
									else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
									{
										$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
									}
									// Oxygen E Portable System && Oxygen Liquid Portable
									else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
										break;
									}
									//Oxygen Cylinder Rack
									else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
									{
										$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
										break;
									}
									//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
									else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
									{
										if($my_count_sign == 0)
										{
											//wheelchair & wheelchair reclining
											if($count == 1)
											{
												$temp_item_description = "";
												$temp_item_description2 = "";

												if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
												{
													$key['key_desc'] = '16" Narrow';
												}
												else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
												{
													$key['key_desc'] = '18" Standard';
												}
												else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
												{
													$key['key_desc'] = '20" Wide';
												}
												else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
												{
													$key['key_desc'] = '22" Extra Wide';
												}
												else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
												{
													$key['key_desc'] = '24" Bariatric';
												}
												$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
											}
											else
											{
												$temp_item_description2 = " With ".$key['key_desc'];
											}
											$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
										}
										else
										{
											$temp_item_description = $summary['key_desc'];
											if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
											{
												$temp_item_description2 = " With Elevating Legrests";
											}
											else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
											{
												$temp_item_description2 = " With Footrests";
											}
											$initial_temp_item_description = '20" Wide';
											$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
											break;
										}
									}
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
										{
											$key['key_desc'] = '17" Narrow';
										}
										else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
										{
											$key['key_desc'] = '19" Standard';
										}
										$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
										$summary['item_description_data'] = $temp_item_description;
									}
									else if($summary['equipmentID'] == 30)
									{
										if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
										{
											if($count == 3)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$temp_item_description = " With ".$key['key_desc'];
												$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
									else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
									{
										$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
										if(strlen($samp) > 30)
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
										}
										else
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
										}

										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
										break;
									}
									else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
									{
										$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
										if($count == 1)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
										}
									}
									else if($summary['equipmentID'] == 282)
									{

									}
									//equipments that has no subequipment but gets inside the $value if statement
									else if($summary['equipmentID'] == 14)
									{
										$summary['item_description_data'] = $summary['key_desc'];
									}
									else
									{
										if($summary['categoryID'] == 1)
										{
											$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
											if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 14)
											{
												$summary['item_description_data'] = $summary['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
											{
												$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 353)
											{
												$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
											}
											else
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								} //end of $value
								//for Oxygen E cylinder do not remove as it will cause errors
								else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
								{
									break;
								}
								else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
								{

								}
								else if($summary['equipmentID'] == 282)
								{
									$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
									break;
								}
								//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
								else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
									break;
								} //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
								else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								//for equipments with subequipment but does not fall in $value
								else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
								{
									if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
									{
										$patient_lift_sling_count++;
										if($patient_lift_sling_count == 6)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									else if($summary['equipmentID'] == 398)
									{
										if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
										{
											$high_low_full_electric_hospital_bed_count++;
											if($high_low_full_electric_hospital_bed_count == 2)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
									}
									// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										$equipment_count++;
										if($equipment_count == 2)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								}
								else
								{
									if($summary['categoryID'] == 1)
									{
										$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
										if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
											break;
										}
										else if($non_capped_copy['noncapped_reference'] == 14)
										{
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{

										}
									}
									else
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									}
								}
							}
						}
						else
						{
							$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
							$summary['item_description_data'] = $summary['key_desc'];
						}
					}
				}
				// Get Full Description - End

				// Get Item Group Rates - Start
				$item = $cus_value;
				$item['key_desc'] = $summary['item_description_data'];
				$item['hospiceID'] = $hospice_id;
				switch($item['equipmentID']) {
					case 49:
					$subequipdetails = array();
					$check = "wala";
					if(strpos($item['key_desc'], "16") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
					}
					if(strpos($item['key_desc'], "18") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
					}
					if(strpos($item['key_desc'], "20") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
					}
					if(strpos($item['key_desc'], "22") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "22_inch");
					}
					if(strpos($item['key_desc'], "24") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "24_inch");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
					case 64:
					$subequipdetails = array();
					if(strpos($item['key_desc'], "16") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
					}
					if(strpos($item['key_desc'], "18") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
					}
					if(strpos($item['key_desc'], "20") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
					case 32:
					$subequipdetails = array();
					if(strpos($item['key_desc'], "E Cylinder 6 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_6_rack");
					}
					if(strpos($item['key_desc'], "E Cylinder 12 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_12_rack");
					}
					if(strpos($item['key_desc'], "M6 Cylinder 6 Rack") != false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_6_rack");
					}
					if(strpos($item['key_desc'], "M6 Cylinder 12 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_12_rack");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
					case 29: case 334: case 343:
					$getassignedequipmentid = $this->equipment_model->get_assigned_equipment_details($item['hospiceID'], 29);
					$subequipdetails = array();
					if(strpos($item['key_desc'], "5L") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "5_liters");
					}
					if(strpos($item['key_desc'], "10L") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "10_liters");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
						"getassignedequipmentid" => $getassignedequipmentid
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
				}
				// Get Item Group Rates - End

				if($category_id == 2) {
					$your_date = strtotime($cus_value['actual_order_date']);
					$your_date_v2 = new DateTime($cus_value['actual_order_date']);
					$new_pickupdate = strtotime(date("Y-m-t", strtotime($cus_value['actual_order_date']))); //2020-06-26
					$new_pickupdate_v2 = new DateTime(date("Y-m-t", strtotime($cus_value['actual_order_date']))); //2020-06-26
					$temporary_service_date_to = date("Y-m-t", strtotime($service_date_from)); // 2020-07-01
					$isSummaryPickupDate = false;

					// if($cus_value['summary_pickup_date'] == "0000-00-00") {
					// 	$now = time();
					// 	$current_date = date('Y-m-d');
					// 	$now_v2 = new DateTime($current_date);
					// } else {
					// 	// $now = strtotime($cus_value['summary_pickup_date']);
					// 	if ($cus_value['pickup_discharge_date'] !== '0000-00-00' && $cus_value['pickup_discharge_date'] !== null) {
					// 		$now = strtotime($cus_value['pickup_discharge_date']);
					// 		$now_v2 = new DateTime($cus_value['pickup_discharge_date']);
					// 	} else {
					// 		$now = strtotime($cus_value['summary_pickup_date']);
					// 		$now_v2 = new DateTime($cus_value['summary_pickup_date']);
					// 	}
					// 	$isSummaryPickupDate = true;
					// }
					$current_date = "2021-04-30";
					if($cus_value['summary_pickup_date'] != "0000-00-00" && ($cus_value['summary_pickup_date'] <= $temporary_service_date_to)) {
								
						if ($cus_value['pickup_discharge_date'] !== '0000-00-00' && $cus_value['pickup_discharge_date'] !== null) {
							$now = strtotime($cus_value['pickup_discharge_date']);
							$now_v2 = new DateTime($cus_value['pickup_discharge_date']);
							$now_v2_other = new DateTime($cus_value['summary_pickup_date']);
						} else {
							$now = strtotime($cus_value['summary_pickup_date']);
							$now_v2 = new DateTime($cus_value['summary_pickup_date']);
							$now_v2_other = new DateTime($cus_value['summary_pickup_date']);
						}
						$isSummaryPickupDate = true;
					} else {
						// $now = time();
						$current_date = "2021-04-30";
						$now_v2 = new DateTime($current_date);
						$now_v2_other = new DateTime($current_date);
					}

					// ((date("Y", strtotime($temporary_service_date_to)) > date("Y", $your_date)) && $isSummaryPickupDate == false) ||
					if((((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) < date("m", $your_date))) && $isSummaryPickupDate == false) ) {
						$now = $new_pickupdate;
						$now_v2 = $new_pickupdate_v2;
						// $datediff = $now - $your_date;
						$datediff = $now_v2->diff($your_date_v2)->format('%a');
					} else {
						if(date("m", strtotime($temporary_service_date_to)) == date("m", $your_date)) {
							$temponewdate = $your_date;
							$temponewdate_v2 = $your_date_v2;
							$datediff = $now_v2->diff($temponewdate_v2)->format('%a');
						} else if ((date("m", $now) < date("m", strtotime($temporary_service_date_to)) && date("Y", $now) == date("Y", strtotime($temporary_service_date_to))) || (date("Y", $now) < date("Y", strtotime($temporary_service_date_to)))) {
							$temponewdate = strtotime($statement_bill['service_date_from']);
							$temponewdate_v2 = new DateTime($statement_bill['service_date_from']);
							$datediff = $now_v2_other->diff($temponewdate_v2)->format('%a') + 1;
						} else {
							$temponewdate = strtotime(date("Y-m-01", strtotime($current_date)));
							$temponewdate_v2 = new DateTime(date("Y-m-01", strtotime($current_date)));
							$datediff = $now_v2->diff($temponewdate_v2)->format('%a') + 1;
						}
						// $temponewdate = strtotime(date("Y-m-01", time()));
						// $datediff = $now - $temponewdate;
					}

					// $datediff = $now - $your_date;
					// $rounddatediff = round($datediff / (60 * 60 * 24)) + 1;
					$rounddatediff = $datediff;


					if ($rounddatediff == 0) {
						$rounddatediff = 1;
					}

					if($cus_value['equipmentID'] != 49 && $cus_value['equipmentID'] != 64 && $cus_value['equipmentID'] != 32 && $cus_value['equipmentID'] != 29 && $cus_value['daily_rate'] != 344 && $cus_value['equipmentID'] == 343) {
						if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
							$rounddatediff = 1;
						}
					}
					// if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
					// 	$rounddatediff = 1;
					// }
					if($cus_value['equipmentID'] == 176) {
						$rounddatediff = 1;
					}

					$dailratetemporary = 0;
  					//New Calculation 11/15/19 =======> START
  					if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
  						// $dailratetemporary = $cus_value['monthly_rate'];
  						
						if ($cus_value['activity_reference'] == 3 && $cus_value['uniqueID_reference'] != 0) {

						} else {
							$total += $cus_value['monthly_rate'];
						}
  					} else {
  						$temptotaldailyrate = $rounddatediff * $cus_value['daily_rate'];
  						if($temptotaldailyrate > $cus_value['monthly_rate']) {
  							if($cus_value['monthly_rate'] == 0 || $cus_value['monthly_rate'] == null) {
  								$total += $temptotaldailyrate;
  							} else {
								if ($cus_value['activity_reference'] == 3 && $cus_value['uniqueID_reference'] != 0) {

								} else {
									$total += $cus_value['monthly_rate'];
								}
  							}
  							// $dailratetemporary = $value['monthly_rate'];
  							// $total += $dailratetemporary;
  						} else {
  							$total += $temptotaldailyrate;
  						}
  					}
  					//New Calculation 11/15/19 =======> END

					// $total += $dailratetemporary*$rounddatediff;
				} else if($category_id == 3) {
					$quan = $cus_value['equipment_value'];
					if($cus_value['equipment_quantity'] !== "" && $cus_value['equipment_quantity'] !== null) {
						$quan = $cus_value['equipment_quantity'];
					}
					$total += $cus_value['purchase_price']*$quan;
				} else {
					$total += $cus_value['purchase_price'];
				}
			}
		// }
		// print_me($total);
		// if($category_id == 2) {
		// 			if($value['summary_pickup_date'] == "0000-00-00") {
		// 				$now = time();
		// 			} else {
		// 				$now = strtotime($value['summary_pickup_date']);
		// 			}
		// 			$your_date = strtotime($value['pickup_date']);
		// 			$datediff = $now - $your_date;
		// 			$rounddatediff = round($datediff / (60 * 60 * 24));

		// 			$total += $value['purchase_price']*$rounddatediff;
		// 		} else {
		// 			$total += $value['purchase_price'];
		// 		}
		echo json_encode($total);

	}
	public function get_category_total($hospice_id, $category_id) {
		if($this->input->is_ajax_request())
		{
			$statement_bill = $this->billing_statement_model->get_statement_bill_by_hospice($hospice_id);
			$service_date_from = $statement_bill['service_date_from'];
			$service_date_to = date("Y-m-t", strtotime($service_date_from));
			$temp = $this->billing_statement_model->get_category_total($hospice_id, $category_id, $service_date_from, $service_date_to);
			$total = 0;
			// print_me($temp);
			foreach($temp as $cus_value) {
				if($cus_value['equip_is_package'] == 0 && $cus_value['is_package'] == 1) {
					continue;
				}

				// Get Full Description - Start
				$summary = $cus_value;
				$summary['item_description_data'] = "";
				if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
				{
					if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
					{
						$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
						$summary['item_description_data'] = $summary['key_desc'];
					}
					else
					{
						//*** DELIVERY not CONFIRMED yet dri mo display ang item ***//
						//check if naay sub equipment using equipment id, work uniqueId
						$subequipment_id = get_subequipment_id($summary['equipmentID']);

						//gets all the id's under the order
						if($subequipment_id)
						{
							$count = 0;
							$patient_lift_sling_count = 0;
							$high_low_full_electric_hospital_bed_count = 0;
							$equipment_count = 0;
							$my_count_sign = 0;
							$my_first_sign = 0;
							$my_second_sign = 0;

							foreach ($subequipment_id as $key) {
								$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);

								if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
								{
									if(empty($value))
									{
										$my_first_sign = 1;
									}
								}
								if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
								{
									if(empty($value))
									{
										$my_second_sign = 1;
									}
								}
								if($my_second_sign == 1 && $my_first_sign == 1)
								{
									$my_count_sign = 1;
								}
								if($value)
								{
									$count++;
									//full electric hospital bed equipment
									if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Hi-Low Full Electric Hospital Bed equipment
									else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Patient Lift with Sling
									else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
									{
										$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
									}
									//Patient Lift Electric with Sling
									else if($summary['equipmentID'] == 353)
									{
										$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
									}
									//Patient Lift Sling
									else if($summary['equipmentID'] == 196)
									{
										$summary['item_description_data'] = $key['key_desc'];
									}
									//(54 & 17) Geri Chair || (66 & 39) Shower Chair
									else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
									{
										$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
									}
									// Oxygen E Portable System && Oxygen Liquid Portable
									else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									break;
									}
									//Oxygen Cylinder Rack
									else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
									{
										$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
										break;
									}
									//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
									else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
									{
										if($my_count_sign == 0)
										{
											//wheelchair & wheelchair reclining
											if($count == 1)
											{
												$temp_item_description = "";
												$temp_item_description2 = "";

												if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
												{
													$key['key_desc'] = '16" Narrow';
												}
												else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
												{
													$key['key_desc'] = '18" Standard';
												}
												else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
												{
													$key['key_desc'] = '20" Wide';
												}
												else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
												{
													$key['key_desc'] = '22" Extra Wide';
												}
												else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
												{
													$key['key_desc'] = '24" Bariatric';
												}
												$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
											}
											else
											{
												$temp_item_description2 = " With ".$key['key_desc'];
											}
											$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
										}
										else
										{
											$temp_item_description = $summary['key_desc'];
											if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
											{
												$temp_item_description2 = " With Elevating Legrests";
											}
											else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
											{
												$temp_item_description2 = " With Footrests";
											}
											$initial_temp_item_description = '20" Wide';
											$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
											break;
										}
									}
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
										{
											$key['key_desc'] = '17" Narrow';
										}
										else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
										{
											$key['key_desc'] = '19" Standard';
										}
										$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
										$summary['item_description_data'] = $temp_item_description;
									}
									else if($summary['equipmentID'] == 30)
									{
										if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
										{
											if($count == 3)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$temp_item_description = " With ".$key['key_desc'];
												$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
									else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
									{
										$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
										if(strlen($samp) > 30)
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
										}
										else
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
										}

										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
										break;
									}
									else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
									{
										$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
										if($count == 1)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
										}
									}
									else if($summary['equipmentID'] == 282)
									{

									}
									//equipments that has no subequipment but gets inside the $value if statement
									else if($summary['equipmentID'] == 14)
									{
										$summary['item_description_data'] = $summary['key_desc'];
									}
									else
									{
										if($summary['categoryID'] == 1)
										{
											$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
											if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 14)
											{
												$summary['item_description_data'] = $summary['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
											{
												$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 353)
											{
												$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
											}
											else
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								} //end of $value
								//for Oxygen E cylinder do not remove as it will cause errors
								else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
								{
									break;
								}
								else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
								{

								}
								else if($summary['equipmentID'] == 282)
								{
									$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
									break;
								}
								//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
								else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
									break;
								}
								//equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
								else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								//for equipments with subequipment but does not fall in $value
								else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
								{
									if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
									{
										$patient_lift_sling_count++;
										if($patient_lift_sling_count == 6)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									else if($summary['equipmentID'] == 398)
									{
										if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
										{
											$high_low_full_electric_hospital_bed_count++;
											if($high_low_full_electric_hospital_bed_count == 2)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
									}
									// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										$equipment_count++;
										if($equipment_count == 2)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								}
								else
								{
									if($summary['categoryID'] == 1)
									{
										$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
										if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
											break;
										}
										else if($non_capped_copy['noncapped_reference'] == 14)
										{
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{

										}
									}
									else
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									}
								}
							}
						}
						else
						{
							if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
							{
								$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
								$summary['item_description_data'] = $summary['key_desc'];
							}
							else
							{
								$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
								$summary['item_description_data'] = $summary['key_desc'];
							}
						}
					}
				}
				else
				{
					$summary['item_description_data'] = "";
					if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
					{
						$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
						$summary['item_description_data'] = $summary['key_desc'];
					}
					else
					{
						//check if the item has sub-equipments using equipment id
						$subequipment_id = get_subequipment_id($summary['equipmentID']);
						//gets all the id's under the order
						if($subequipment_id)
						{
							$count = 0;
							$patient_lift_sling_count = 0;
							$high_low_full_electric_hospital_bed_count = 0;
							$equipment_count = 0;
							$my_count_sign = 0;
							$my_first_sign = 0;
							$my_second_sign = 0;
							foreach ($subequipment_id as $key) {
								$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
								if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
								{
									if(empty($value))
									{
										$my_first_sign = 1;
									}
								}
								if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
								{
									if(empty($value))
									{
										$my_second_sign = 1;
									}
								}
								if($my_second_sign == 1 && $my_first_sign == 1)
								{
									$my_count_sign = 1;
								}
								if($value)
								{
									$count++;
									//equipment full electric hospital bed
									if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Hi-Low Full Electric Hospital Bed equipment
									else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Patient Lift with Sling
									else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
									{
										$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
									}
									//Patient Lift Electric with Sling
									else if($summary['equipmentID'] == 353)
									{
										$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
									}
									//Patient Lift Sling
									else if($summary['equipmentID'] == 196)
									{
										$summary['item_description_data'] = $key['key_desc'];
									}
									//(54 & 17) Geri Chair || (66 & 39) Shower Chair
									else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
									{
										$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
									}
									// Oxygen E Portable System && Oxygen Liquid Portable
									else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
										break;
									}
									//Oxygen Cylinder Rack
									else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
									{
										$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
										break;
									}
									//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
									else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
									{
										if($my_count_sign == 0)
										{
											//wheelchair & wheelchair reclining
											if($count == 1)
											{
												$temp_item_description = "";
												$temp_item_description2 = "";

												if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
												{
													$key['key_desc'] = '16" Narrow';
												}
												else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
												{
													$key['key_desc'] = '18" Standard';
												}
												else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
												{
													$key['key_desc'] = '20" Wide';
												}
												else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
												{
													$key['key_desc'] = '22" Extra Wide';
												}
												else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
												{
													$key['key_desc'] = '24" Bariatric';
												}
												$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
											}
											else
											{
												$temp_item_description2 = " With ".$key['key_desc'];
											}
											$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
										}
										else
										{
											$temp_item_description = $summary['key_desc'];
											if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
											{
												$temp_item_description2 = " With Elevating Legrests";
											}
											else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
											{
												$temp_item_description2 = " With Footrests";
											}
											$initial_temp_item_description = '20" Wide';
											$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
											break;
										}
									}
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
										{
											$key['key_desc'] = '17" Narrow';
										}
										else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
										{
											$key['key_desc'] = '19" Standard';
										}
										$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
										$summary['item_description_data'] = $temp_item_description;
									}
									else if($summary['equipmentID'] == 30)
									{
										if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
										{
											if($count == 3)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$temp_item_description = " With ".$key['key_desc'];
												$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
									else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
									{
										$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
										if(strlen($samp) > 30)
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
										}
										else
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
										}

										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
										break;
									}
									else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
									{
										$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
										if($count == 1)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
										}
									}
									else if($summary['equipmentID'] == 282)
									{

									}
									//equipments that has no subequipment but gets inside the $value if statement
									else if($summary['equipmentID'] == 14)
									{
										$summary['item_description_data'] = $summary['key_desc'];
									}
									else
									{
										if($summary['categoryID'] == 1)
										{
											$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
											if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 14)
											{
												$summary['item_description_data'] = $summary['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
											{
												$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 353)
											{
												$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
											}
											else
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								} //end of $value
								//for Oxygen E cylinder do not remove as it will cause errors
								else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
								{
									break;
								}
								else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
								{

								}
								else if($summary['equipmentID'] == 282)
								{
									$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
									break;
								}
								//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
								else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
									break;
								} //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
								else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								//for equipments with subequipment but does not fall in $value
								else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
								{
									if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
									{
										$patient_lift_sling_count++;
										if($patient_lift_sling_count == 6)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									else if($summary['equipmentID'] == 398)
									{
										if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
										{
											$high_low_full_electric_hospital_bed_count++;
											if($high_low_full_electric_hospital_bed_count == 2)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
									}
									// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										$equipment_count++;
										if($equipment_count == 2)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								}
								else
								{
									if($summary['categoryID'] == 1)
									{
										$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
										if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
											break;
										}
										else if($non_capped_copy['noncapped_reference'] == 14)
										{
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{

										}
									}
									else
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									}
								}
							}
						}
						else
						{
							$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
							$summary['item_description_data'] = $summary['key_desc'];
						}
					}
				}
				// Get Full Description - End

				// Get Item Group Rates - Start
				$item = $cus_value;
				$item['key_desc'] = $summary['item_description_data'];
				$item['hospiceID'] = $hospice_id;
				switch($item['equipmentID']) {
					case 49:
					$subequipdetails = array();
					$check = "wala";
					if(strpos($item['key_desc'], "16") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
					}
					if(strpos($item['key_desc'], "18") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
					}
					if(strpos($item['key_desc'], "20") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
					}
					if(strpos($item['key_desc'], "22") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "22_inch");
					}
					if(strpos($item['key_desc'], "24") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "24_inch");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
					case 64:
					$subequipdetails = array();
					if(strpos($item['key_desc'], "16") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
					}
					if(strpos($item['key_desc'], "18") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
					}
					if(strpos($item['key_desc'], "20") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
					case 32:
					$subequipdetails = array();
					if(strpos($item['key_desc'], "E Cylinder 6 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_6_rack");
					}
					if(strpos($item['key_desc'], "E Cylinder 12 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_12_rack");
					}
					if(strpos($item['key_desc'], "M6 Cylinder 6 Rack") != false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_6_rack");
					}
					if(strpos($item['key_desc'], "M6 Cylinder 12 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_12_rack");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
					case 29: case 334: case 343:
					$getassignedequipmentid = $this->equipment_model->get_assigned_equipment_details($item['hospiceID'], 29);
					$subequipdetails = array();
					if(strpos($item['key_desc'], "5L") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "5_liters");
					}
					if(strpos($item['key_desc'], "10L") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "10_liters");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
						"getassignedequipmentid" => $getassignedequipmentid
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
				}
				// Get Item Group Rates - End

				if($category_id == 2) {
					$your_date = strtotime($cus_value['actual_order_date']);
					$your_date_v2 = new DateTime($cus_value['actual_order_date']);
					$new_pickupdate = strtotime(date("Y-m-t", strtotime($cus_value['actual_order_date']))); //2020-06-26
					$new_pickupdate_v2 = new DateTime(date("Y-m-t", strtotime($cus_value['actual_order_date']))); //2020-06-26
					$temporary_service_date_to = date("Y-m-t", strtotime($service_date_from)); // 2020-07-01
					$isSummaryPickupDate = false;

					// if($cus_value['summary_pickup_date'] == "0000-00-00") {
					// 	$now = time();
					// 	$current_date = date('Y-m-d');
					// 	$now_v2 = new DateTime($current_date);
					// } else {
					// 	// $now = strtotime($cus_value['summary_pickup_date']);
					// 	if ($cus_value['pickup_discharge_date'] !== '0000-00-00' && $cus_value['pickup_discharge_date'] !== null) {
					// 		$now = strtotime($cus_value['pickup_discharge_date']);
					// 		$now_v2 = new DateTime($cus_value['pickup_discharge_date']);
					// 	} else {
					// 		$now = strtotime($cus_value['summary_pickup_date']);
					// 		$now_v2 = new DateTime($cus_value['summary_pickup_date']);
					// 	}
					// 	$isSummaryPickupDate = true;
					// }

					if($cus_value['summary_pickup_date'] != "0000-00-00" && ($cus_value['summary_pickup_date'] <= $temporary_service_date_to)) {
								
						if ($cus_value['pickup_discharge_date'] !== '0000-00-00' && $cus_value['pickup_discharge_date'] !== null) {
							$now = strtotime($cus_value['pickup_discharge_date']);
							$now_v2 = new DateTime($cus_value['pickup_discharge_date']);
							$now_v2_other = new DateTime($cus_value['summary_pickup_date']);
						} else {
							$now = strtotime($cus_value['summary_pickup_date']);
							$now_v2 = new DateTime($cus_value['summary_pickup_date']);
							$now_v2_other = new DateTime($cus_value['summary_pickup_date']);
						}
						$isSummaryPickupDate = true;
					} else {
						// $now = time();
						$current_date = date('Y-m-d');
						$now_v2 = new DateTime($current_date);
						$now_v2_other = new DateTime($current_date);
					}

					// ((date("Y", strtotime($temporary_service_date_to)) > date("Y", $your_date)) && $isSummaryPickupDate == false) ||
					if((((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) < date("m", $your_date))) && $isSummaryPickupDate == false) ) {
						$now = $new_pickupdate;
						$now_v2 = $new_pickupdate_v2;
						// $datediff = $now - $your_date;
						$datediff = $now_v2->diff($your_date_v2)->format('%a');
					} else {
						if(date("m", strtotime($temporary_service_date_to)) == date("m", $your_date)) {
							$temponewdate = $your_date;
							$temponewdate_v2 = $your_date_v2;
							$datediff = $now_v2->diff($temponewdate_v2)->format('%a');
						} else if ((date("m", $now) < date("m", strtotime($temporary_service_date_to)) && date("Y", $now) == date("Y", strtotime($temporary_service_date_to))) || (date("Y", $now) < date("Y", strtotime($temporary_service_date_to)))) {
							$temponewdate = strtotime($statement_bill['service_date_from']);
							$temponewdate_v2 = new DateTime($statement_bill['service_date_from']);
							$datediff = $now_v2_other->diff($temponewdate_v2)->format('%a') + 1;
						} else {
							$temponewdate = strtotime(date("Y-m-01"));
							$temponewdate_v2 = new DateTime(date("Y-m-01"));
							$datediff = $now_v2->diff($temponewdate_v2)->format('%a') + 1;
						}
						// $temponewdate = strtotime(date("Y-m-01", time()));
						// $datediff = $now - $temponewdate;
					}

					// $datediff = $now - $your_date;
					// $rounddatediff = round($datediff / (60 * 60 * 24)) + 1;
					$rounddatediff = $datediff;


					if ($rounddatediff == 0) {
						$rounddatediff = 1;
					}

					if($cus_value['equipmentID'] != 49 && $cus_value['equipmentID'] != 64 && $cus_value['equipmentID'] != 32 && $cus_value['equipmentID'] != 29 && $cus_value['daily_rate'] != 344 && $cus_value['equipmentID'] == 343) {
						if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
							$rounddatediff = 1;
						}
					}
					// if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
					// 	$rounddatediff = 1;
					// }
					if($cus_value['equipmentID'] == 176) {
						$rounddatediff = 1;
					}

					$dailratetemporary = 0;
  					//New Calculation 11/15/19 =======> START
  					if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
  						// $dailratetemporary = $cus_value['monthly_rate'];
  						if ($cus_value['activity_reference'] == 3 && $cus_value['uniqueID_reference'] != 0) {

						} else {
							$total += $cus_value['monthly_rate'];
						}
  					} else {
  						$temptotaldailyrate = $rounddatediff * $cus_value['daily_rate'];
  						if($temptotaldailyrate > $cus_value['monthly_rate']) {
  							if($cus_value['monthly_rate'] == 0 || $cus_value['monthly_rate'] == null) {
  								$total += $temptotaldailyrate;
  							} else {
								if ($cus_value['activity_reference'] == 3 && $cus_value['uniqueID_reference'] != 0) {

								} else {
									$total += $cus_value['monthly_rate'];
								}
  							}
  							// $dailratetemporary = $value['monthly_rate'];
  							// $total += $dailratetemporary;
  						} else {
  							$total += $temptotaldailyrate;
  						}
  					}
  					//New Calculation 11/15/19 =======> END

					// $total += $dailratetemporary*$rounddatediff;
				} else if($category_id == 3) {
					$quan = $cus_value['equipment_value'];
					if($cus_value['equipment_quantity'] !== "" && $cus_value['equipment_quantity'] !== null) {
						$quan = $cus_value['equipment_quantity'];
					}
					$total += $cus_value['purchase_price']*$quan;
				} else {
					$total += $cus_value['purchase_price'];
				}
			}
		}
		// print_me($total);
		// if($category_id == 2) {
		// 			if($value['summary_pickup_date'] == "0000-00-00") {
		// 				$now = time();
		// 			} else {
		// 				$now = strtotime($value['summary_pickup_date']);
		// 			}
		// 			$your_date = strtotime($value['pickup_date']);
		// 			$datediff = $now - $your_date;
		// 			$rounddatediff = round($datediff / (60 * 60 * 24));

		// 			$total += $value['purchase_price']*$rounddatediff;
		// 		} else {
		// 			$total += $value['purchase_price'];
		// 		}
		echo json_encode($total);

	}

	public function get_category_total_draft_statement($draft_id, $category_id) {
		// if($this->input->is_ajax_request())
		// {
			$statement_bill = $this->billing_statement_model->get_draft_details($draft_id);
			$hospice_id = $statement_bill['hospiceID'];
			$service_date_from = $statement_bill['service_date_from'];
			$service_date_to = date("Y-m-t", strtotime($service_date_from));
			$temp = $this->billing_statement_model->get_category_total_for_draft_statement($hospice_id, $category_id, $service_date_from, $service_date_to);
			$total = 0;
			// print_me($temp);
			foreach($temp as $cat_value) {
				if($cat_value['equip_is_package'] == 0 && $cat_value['is_package'] == 1) {
					continue;
				}

				// Get Full Description - Start
				$summary = $cat_value;
				$summary['item_description_data'] = "";
				if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
				{
					if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
					{
						$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
						$summary['item_description_data'] = $summary['key_desc'];
					}
					else
					{
						//*** DELIVERY not CONFIRMED yet dri mo display ang item ***//
						//check if naay sub equipment using equipment id, work uniqueId
						$subequipment_id = get_subequipment_id($summary['equipmentID']);

						//gets all the id's under the order
						if($subequipment_id)
						{
							$count = 0;
							$patient_lift_sling_count = 0;
							$high_low_full_electric_hospital_bed_count = 0;
							$equipment_count = 0;
							$my_count_sign = 0;
							$my_first_sign = 0;
							$my_second_sign = 0;

							foreach ($subequipment_id as $key) {
								$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);

								if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
								{
									if(empty($value))
									{
										$my_first_sign = 1;
									}
								}
								if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
								{
									if(empty($value))
									{
										$my_second_sign = 1;
									}
								}
								if($my_second_sign == 1 && $my_first_sign == 1)
								{
									$my_count_sign = 1;
								}
								if($value)
								{
									$count++;
									//full electric hospital bed equipment
									if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Hi-Low Full Electric Hospital Bed equipment
									else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Patient Lift with Sling
									else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
									{
										$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
									}
									//Patient Lift Electric with Sling
									else if($summary['equipmentID'] == 353)
									{
										$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
									}
									//Patient Lift Sling
									else if($summary['equipmentID'] == 196)
									{
										$summary['item_description_data'] = $key['key_desc'];
									}
									//(54 & 17) Geri Chair || (66 & 39) Shower Chair
									else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
									{
										$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
									}
									// Oxygen E Portable System && Oxygen Liquid Portable
									else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									break;
									}
									//Oxygen Cylinder Rack
									else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
									{
										$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
										break;
									}
									//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
									else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
									{
										if($my_count_sign == 0)
										{
											//wheelchair & wheelchair reclining
											if($count == 1)
											{
												$temp_item_description = "";
												$temp_item_description2 = "";

												if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
												{
													$key['key_desc'] = '16" Narrow';
												}
												else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
												{
													$key['key_desc'] = '18" Standard';
												}
												else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
												{
													$key['key_desc'] = '20" Wide';
												}
												else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
												{
													$key['key_desc'] = '22" Extra Wide';
												}
												else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
												{
													$key['key_desc'] = '24" Bariatric';
												}
												$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
											}
											else
											{
												$temp_item_description2 = " With ".$key['key_desc'];
											}
											$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
										}
										else
										{
											$temp_item_description = $summary['key_desc'];
											if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
											{
												$temp_item_description2 = " With Elevating Legrests";
											}
											else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
											{
												$temp_item_description2 = " With Footrests";
											}
											$initial_temp_item_description = '20" Wide';
											$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
											break;
										}
									}
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
										{
											$key['key_desc'] = '17" Narrow';
										}
										else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
										{
											$key['key_desc'] = '19" Standard';
										}
										$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
										$summary['item_description_data'] = $temp_item_description;
									}
									else if($summary['equipmentID'] == 30)
									{
										if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
										{
											if($count == 3)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$temp_item_description = " With ".$key['key_desc'];
												$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
									else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
									{
										$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
										if(strlen($samp) > 30)
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
										}
										else
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
										}

										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
										break;
									}
									else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
									{
										$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
										if($count == 1)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
										}
									}
									else if($summary['equipmentID'] == 282)
									{

									}
									//equipments that has no subequipment but gets inside the $value if statement
									else if($summary['equipmentID'] == 14)
									{
										$summary['item_description_data'] = $summary['key_desc'];
									}
									else
									{
										if($summary['categoryID'] == 1)
										{
											$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
											if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 14)
											{
												$summary['item_description_data'] = $summary['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
											{
												$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 353)
											{
												$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
											}
											else
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								} //end of $value
								//for Oxygen E cylinder do not remove as it will cause errors
								else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
								{
									break;
								}
								else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
								{

								}
								else if($summary['equipmentID'] == 282)
								{
									$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
									break;
								}
								//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
								else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
									break;
								}
								//equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
								else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								//for equipments with subequipment but does not fall in $value
								else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
								{
									if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
									{
										$patient_lift_sling_count++;
										if($patient_lift_sling_count == 6)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									else if($summary['equipmentID'] == 398)
									{
										if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
										{
											$high_low_full_electric_hospital_bed_count++;
											if($high_low_full_electric_hospital_bed_count == 2)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
									}
									// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										$equipment_count++;
										if($equipment_count == 2)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								}
								else
								{
									if($summary['categoryID'] == 1)
									{
										$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
										if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
											break;
										}
										else if($non_capped_copy['noncapped_reference'] == 14)
										{
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{

										}
									}
									else
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									}
								}
							}
						}
						else
						{
							if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
							{
								$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
								$summary['item_description_data'] = $summary['key_desc'];
							}
							else
							{
								$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
								$summary['item_description_data'] = $summary['key_desc'];
							}
						}
					}
				}
				else
				{
					$summary['item_description_data'] = "";
					if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
					{
						$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
						$summary['item_description_data'] = $summary['key_desc'];
					}
					else
					{
						//check if the item has sub-equipments using equipment id
						$subequipment_id = get_subequipment_id($summary['equipmentID']);
						//gets all the id's under the order
						if($subequipment_id)
						{
							$count = 0;
							$patient_lift_sling_count = 0;
							$high_low_full_electric_hospital_bed_count = 0;
							$equipment_count = 0;
							$my_count_sign = 0;
							$my_first_sign = 0;
							$my_second_sign = 0;
							foreach ($subequipment_id as $key) {
								$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
								if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
								{
									if(empty($value))
									{
										$my_first_sign = 1;
									}
								}
								if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
								{
									if(empty($value))
									{
										$my_second_sign = 1;
									}
								}
								if($my_second_sign == 1 && $my_first_sign == 1)
								{
									$my_count_sign = 1;
								}
								if($value)
								{
									$count++;
									//equipment full electric hospital bed
									if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Hi-Low Full Electric Hospital Bed equipment
									else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Patient Lift with Sling
									else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
									{
										$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
									}
									//Patient Lift Electric with Sling
									else if($summary['equipmentID'] == 353)
									{
										$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
									}
									//Patient Lift Sling
									else if($summary['equipmentID'] == 196)
									{
										$summary['item_description_data'] = $key['key_desc'];
									}
									//(54 & 17) Geri Chair || (66 & 39) Shower Chair
									else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
									{
										$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
									}
									// Oxygen E Portable System && Oxygen Liquid Portable
									else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
										break;
									}
									//Oxygen Cylinder Rack
									else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
									{
										$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
										break;
									}
									//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
									else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
									{
										if($my_count_sign == 0)
										{
											//wheelchair & wheelchair reclining
											if($count == 1)
											{
												$temp_item_description = "";
												$temp_item_description2 = "";

												if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
												{
													$key['key_desc'] = '16" Narrow';
												}
												else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
												{
													$key['key_desc'] = '18" Standard';
												}
												else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
												{
													$key['key_desc'] = '20" Wide';
												}
												else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
												{
													$key['key_desc'] = '22" Extra Wide';
												}
												else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
												{
													$key['key_desc'] = '24" Bariatric';
												}
												$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
											}
											else
											{
												$temp_item_description2 = " With ".$key['key_desc'];
											}
											$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
										}
										else
										{
											$temp_item_description = $summary['key_desc'];
											if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
											{
												$temp_item_description2 = " With Elevating Legrests";
											}
											else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
											{
												$temp_item_description2 = " With Footrests";
											}
											$initial_temp_item_description = '20" Wide';
											$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
											break;
										}
									}
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
										{
											$key['key_desc'] = '17" Narrow';
										}
										else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
										{
											$key['key_desc'] = '19" Standard';
										}
										$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
										$summary['item_description_data'] = $temp_item_description;
									}
									else if($summary['equipmentID'] == 30)
									{
										if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
										{
											if($count == 3)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$temp_item_description = " With ".$key['key_desc'];
												$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
									else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
									{
										$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
										if(strlen($samp) > 30)
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
										}
										else
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
										}

										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
										break;
									}
									else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
									{
										$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
										if($count == 1)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
										}
									}
									else if($summary['equipmentID'] == 282)
									{

									}
									//equipments that has no subequipment but gets inside the $value if statement
									else if($summary['equipmentID'] == 14)
									{
										$summary['item_description_data'] = $summary['key_desc'];
									}
									else
									{
										if($summary['categoryID'] == 1)
										{
											$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
											if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 14)
											{
												$summary['item_description_data'] = $summary['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
											{
												$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 353)
											{
												$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
											}
											else
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								} //end of $value
								//for Oxygen E cylinder do not remove as it will cause errors
								else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
								{
									break;
								}
								else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
								{

								}
								else if($summary['equipmentID'] == 282)
								{
									$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
									break;
								}
								//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
								else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
									break;
								} //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
								else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								//for equipments with subequipment but does not fall in $value
								else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
								{
									if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
									{
										$patient_lift_sling_count++;
										if($patient_lift_sling_count == 6)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									else if($summary['equipmentID'] == 398)
									{
										if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
										{
											$high_low_full_electric_hospital_bed_count++;
											if($high_low_full_electric_hospital_bed_count == 2)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
									}
									// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										$equipment_count++;
										if($equipment_count == 2)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								}
								else
								{
									if($summary['categoryID'] == 1)
									{
										$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
										if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
											break;
										}
										else if($non_capped_copy['noncapped_reference'] == 14)
										{
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{

										}
									}
									else
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									}
								}
							}
						}
						else
						{
							$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
							$summary['item_description_data'] = $summary['key_desc'];
						}
					}
				}
				// Get Full Description - End

				// Get Item Group Rates - Start
				$item = $cat_value;
				$item['key_desc'] = $summary['item_description_data'];
				$item['hospiceID'] = $statement_bill['hospiceID'];
				switch($item['equipmentID']) {
					case 49:
					$subequipdetails = array();
					$check = "wala";
					if(strpos($item['key_desc'], "16") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
					}
					if(strpos($item['key_desc'], "18") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
					}
					if(strpos($item['key_desc'], "20") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
					}
					if(strpos($item['key_desc'], "22") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "22_inch");
					}
					if(strpos($item['key_desc'], "24") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "24_inch");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cat_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cat_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cat_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
					case 64:
					$subequipdetails = array();
					if(strpos($item['key_desc'], "16") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
					}
					if(strpos($item['key_desc'], "18") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
					}
					if(strpos($item['key_desc'], "20") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cat_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cat_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cat_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
					case 32:
					$subequipdetails = array();
					if(strpos($item['key_desc'], "E Cylinder 6 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_6_rack");
					}
					if(strpos($item['key_desc'], "E Cylinder 12 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_12_rack");
					}
					if(strpos($item['key_desc'], "M6 Cylinder 6 Rack") != false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_6_rack");
					}
					if(strpos($item['key_desc'], "M6 Cylinder 12 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_12_rack");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cat_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cat_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cat_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
					case 29: case 334: case 343:
					$getassignedequipmentid = $this->equipment_model->get_assigned_equipment_details($item['hospiceID'], 29);
					$subequipdetails = array();
					if(strpos($item['key_desc'], "5L") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "5_liters");
					}
					if(strpos($item['key_desc'], "10L") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "10_liters");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
						"getassignedequipmentid" => $getassignedequipmentid
					);
					$cat_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cat_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cat_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
				}
				// Get Item Group Rates - End
				
				if($category_id == 2) {
					$your_date = strtotime($cat_value['actual_order_date']);
					$your_date_v2 = new DateTime($cat_value['actual_order_date']);
					$new_pickupdate = strtotime(date("Y-m-t", strtotime($cat_value['actual_order_date'])));
					$new_pickupdate_v2 = new DateTime(date("Y-m-t", strtotime($cat_value['actual_order_date'])));
					$temporary_service_date_to = date("Y-m-t", strtotime($service_date_from));
					$isSummaryPickupDate = false;

					// if($cat_value['summary_pickup_date'] == "0000-00-00") {
					// 	// $now = time();
					// 	$now = strtotime($statement_bill['service_date_to']);
					// 	$now_v2 = new DateTime($statement_bill['service_date_to']);
					// } else {
					// 	if ($cat_value['pickup_discharge_date'] !== '0000-00-00' && $cat_value['pickup_discharge_date'] !== null) {
					// 		$now = strtotime($cat_value['pickup_discharge_date']);
					// 		$now_v2 = new DateTime($cat_value['pickup_discharge_date']);
					// 	} else {
					// 		$now = strtotime($cat_value['summary_pickup_date']);
					// 		$now_v2 = new DateTime($cat_value['summary_pickup_date']);
					// 	}
					// 	// $now = strtotime($cat_value['summary_pickup_date']);
					// 	$isSummaryPickupDate = true;
					// }

					if($cat_value['summary_pickup_date'] != "0000-00-00" && ($cat_value['summary_pickup_date'] <= $temporary_service_date_to)) {
										
						if ($cat_value['pickup_discharge_date'] !== '0000-00-00' && $cat_value['pickup_discharge_date'] !== null) {
							$now = strtotime($cat_value['pickup_discharge_date']);
							$now_v2 = new DateTime($cat_value['pickup_discharge_date']);
							$now_v2_other = new DateTime($cat_value['summary_pickup_date']);
						} else {
							$now = strtotime($cat_value['summary_pickup_date']);
							$now_v2 = new DateTime($cat_value['summary_pickup_date']);
							$now_v2_other = new DateTime($cat_value['summary_pickup_date']);
						}
						$isSummaryPickupDate = true;
					} else {
						// $now = time();
						$now = strtotime($statement_bill['service_date_to']);
						$now_v2 = new DateTime($statement_bill['service_date_to']);
						$now_v2_other = new DateTime($statement_bill['service_date_to']);
					}

					// ((date("Y", strtotime($temporary_service_date_to)) > date("Y", $your_date)) && $isSummaryPickupDate == false) ||
					if((((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) < date("m", $your_date))) && $isSummaryPickupDate == false) ) {
						$now = $new_pickupdate;
						$now_v2 = $new_pickupdate_v2;
						// $datediff = $now - $your_date;
						$datediff = $now_v2->diff($your_date_v2)->format('%a');
					} else {
						if(date("m", strtotime($temporary_service_date_to)) == date("m", $your_date)) {
							$temponewdate = $your_date;
							$temponewdate_v2 = $your_date_v2;
							$datediff = $now_v2->diff($temponewdate_v2)->format('%a');
						} else if ((date("m", $now) < date("m", strtotime($temporary_service_date_to)) && date("Y", $now) == date("Y", strtotime($temporary_service_date_to))) || (date("Y", $now) < date("Y", strtotime($temporary_service_date_to)))) {
							$temponewdate = strtotime($statement_bill['service_date_from']);
							$temponewdate_v2 = new DateTime($statement_bill['service_date_from']);
							$datediff = $now_v2_other->diff($temponewdate_v2)->format('%a') + 1;
						} else {
							$temponewdate = strtotime($statement_bill['service_date_from']);
							$temponewdate_v2 = new DateTime($statement_bill['service_date_from']);
							$datediff = $now_v2->diff($temponewdate_v2)->format('%a') + 1;
						}
						// $temponewdate = strtotime(date("Y-m-01", time()));
						// $datediff = $now - $temponewdate;
						
					}

					// $datediff = $now - $your_date;
					// $rounddatediff = round($datediff / (60 * 60 * 24)) + 1;
					$rounddatediff = $datediff;

					if ($rounddatediff == 0) {
						$rounddatediff = 1;
					}

					if($cat_value['equipmentID'] != 49 && $cat_value['equipmentID'] != 64 && $cat_value['equipmentID'] != 32 && $cat_value['equipmentID'] != 29 && $cat_value['daily_rate'] != 344 && $cat_value['equipmentID'] == 343) {
						if($cat_value['daily_rate'] == 0 || $cat_value['daily_rate'] == null) {
							$rounddatediff = 1;
						}
					}

					// if($cat_value['daily_rate'] == 0 || $cat_value['daily_rate'] == null) {
					// 	$rounddatediff = 1;
					// }
					if($cat_value['equipmentID'] == 176) {
						$rounddatediff = 1;
					}

					$dailratetemporary = 0;
  					//New Calculation 11/15/19 =======> START
  					if($cat_value['daily_rate'] == 0 || $cat_value['daily_rate'] == null) {
  						// $dailratetemporary = $value['monthly_rate'];
  						if ($cat_value['activity_reference'] == 3 && $cat_value['uniqueID_reference'] != 0) {

						} else {
							$total += $cat_value['monthly_rate'];
						}
  					} else {
  						$temptotaldailyrate = $rounddatediff * $cat_value['daily_rate'];
  						if($temptotaldailyrate > $cat_value['monthly_rate']) {
  							if($cat_value['monthly_rate'] == 0 || $cat_value['monthly_rate'] == null) {
  								$total += $temptotaldailyrate;
  							} else {
								if ($cat_value['activity_reference'] == 3 && $cat_value['uniqueID_reference'] != 0) {

								} else {
									$total += $cat_value['monthly_rate'];
								}
  							}
  							// $dailratetemporary = $value['monthly_rate'];
  							// $total += $dailratetemporary;
  						} else {
  							$total += $temptotaldailyrate;
  						}
  					}
  					//New Calculation 11/15/19 =======> END

					// $total += $dailratetemporary*$rounddatediff;
				} else if($category_id == 3) {
					$quan = $cat_value['equipment_value'];
					if($cat_value['equipment_quantity'] !== "" && $cat_value['equipment_quantity'] !== null) {
						$quan = $cat_value['equipment_quantity'];
					}
					$total += $cat_value['purchase_price']*$quan;
				} else {
					$total += $cat_value['purchase_price'];
				}
			}
		// }
		// print_me($total);
		// if($category_id == 2) {
		// 			if($value['summary_pickup_date'] == "0000-00-00") {
		// 				$now = time();
		// 			} else {
		// 				$now = strtotime($value['summary_pickup_date']);
		// 			}
		// 			$your_date = strtotime($value['pickup_date']);
		// 			$datediff = $now - $your_date;
		// 			$rounddatediff = round($datediff / (60 * 60 * 24));

		// 			$total += $value['purchase_price']*$rounddatediff;
		// 		} else {
		// 			$total += $value['purchase_price'];
		// 		}
		echo json_encode($total);

	}

	public function get_customer_days($hospice_id) {
		if($this->input->is_ajax_request()) {
			$customer_days = $this->billing_statement_model->get_customer_days($hospice_id);
		}
		echo json_encode($customer_days);
	}

	public function get_customer_days_v2($hospice_id, $draft_id) {
		if($this->input->is_ajax_request()) {
			$draft_details = $this->billing_statement_model->get_draft_details($draft_id);
			$hospice_details = $this->billing_statement_model->get_account($hospice_id);
			$data = array(
				'customer_days' => 0,
				'is_input_field' => 0
			);
			if($draft_details['customer_days'] > 0) {
				$data['customer_days'] = $draft_details['customer_days'];
				$data['is_input_field'] = 1;
			} else {
				if($hospice_details['track_census'] == 1) {
					$data['customer_days'] = $draft_details['customer_days'];
					$data['is_input_field'] = 1;
				} else {
					$data['customer_days'] = $this->billing_statement_model->get_customer_days($hospice_id);
				}
			}
		}
		echo json_encode($data);
	}

	public function get_new_statement_no($hospice_id) {
		if($this->input->is_ajax_request()) {
			$temp_statement_no = (int) strtotime(date('Y-m-d H:i:s A'));
            $statement_no = $temp_statement_no + $hospice_id;
		}
		echo json_encode($statement_no);
	}

	public function create_new_statement_no_all_hospices() {
		$all_hospices = $this->billing_statement_model->all_list_for_account();
		$this->billing_statement_model->remove_statement_bill();
		foreach($all_hospices as $hospice) {
			$data = array();
			$temp_statement_no = strtotime(date('Y-m-d H:i:s'));
            $statement_no = $temp_statement_no + $hospice['hospiceID'];
            $service_date = date('Y-m-01');
            $data = array(
            	"statement_no"		=> $temp_statement_no,
            	"hospiceID"			=> $hospice['hospiceID'],
            	"service_date_from"	=> $service_date
            );
            $this->billing_statement_model->insert_statement_bill($data);
		}
	}

	public function create_new_statement_no_all_hospices_v2() {
		// $all_hospices = $this->billing_statement_model->all_list_for_account();
		$all_hospices_with_bill_details = $this->billing_statement_model->get_statement_bill_with_hospice_details();
		foreach($all_hospices_with_bill_details as $hospice_with_bill_details) {
			if($hospice_with_bill_details['hospiceID'] != null && $hospice_with_bill_details['hospiceID'] !== "") {
				$data = array(
					"hospiceID" 		=> $hospice_with_bill_details['hospiceID'],
					"statement_no"		=> $hospice_with_bill_details['statement_no'],
					"service_date_from"	=> $hospice_with_bill_details['service_date_from'],
					"service_date_to"	=> date("Y-m-t", strtotime($hospice_with_bill_details['service_date_from'])),
					"notes"				=> "",
					"is_manual"			=> 0
				);
				$statement_draft = $this->billing_statement_model->insert_statement_draft($data);
	
				if($statement_draft) {
					$all_current_reconciliation = $this->billing_statement_model->get_all_current_reconciliation_balance_and_owe($hospice_with_bill_details['hospiceID']);
					$data_recon = array(
						"draft_reference" => $hospice_with_bill_details['statement_no']
					);
					foreach($all_current_reconciliation as $value) {
						$this->billing_statement_model->update_statement_reconciliation($value['acct_statement_reconciliation_id'], $data_recon);
					}
				} 
			}
			
		}

		$this->billing_statement_model->remove_statement_bill();
		foreach($all_hospices_with_bill_details as $hospice) {
			$data = array();
			$temp_statement_no = strtotime(date('Y-m-d H:i:s'));
            $statement_no = $temp_statement_no + $hospice['accountID'];
            $service_date = date('Y-m-01');
            $data = array(
            	"statement_no"		=> $statement_no,
            	"hospiceID"			=> $hospice['accountID'],
            	"service_date_from"	=> $service_date
            );
            $this->billing_statement_model->insert_statement_bill($data);
		}
	}

	public function send_to_draft($hospice_id, $statement_no, $acct_statement_bill_id, $new_statement_no,  $service_date_from, $service_date_to, $notes="") {

		$data_bill = array(
			"statement_no"	=> $new_statement_no
		);

		//Update the statement Number for billing statement
		$this->billing_statement_model->update_statement_bill($acct_statement_bill_id, $data_bill);


		$data = array(
			"hospiceID" 		=> $hospice_id,
			"statement_no"		=> $statement_no,
			"service_date_from"	=> $service_date_from,
			"service_date_to"	=> $service_date_to,
			"notes"				=> $notes,
			"is_manual"			=> 0
		);

		$statement_draft = $this->billing_statement_model->insert_statement_draft($data);

		if($statement_draft) {
			$all_current_reconciliation = $this->billing_statement_model->get_all_current_reconciliation_balance_and_owe($hospice_id);
			$data_recon = array(
				"draft_reference" => $statement_no
			);
			foreach($all_current_reconciliation as $value) {
				$this->billing_statement_model->update_statement_reconciliation($value['acct_statement_reconciliation_id'], $data_recon);
			}
			$this->response_code = 0;
            $this->response_message = 'Account Statement successfully sent to draft.';
		} 
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function merge_draft_statement($hospice_id) {
		$statement_draft = $this->billing_statement_model->get_all_statement_bill_draft_by_hospice($hospice_id, "ASC");
		$service_date_from = $statement_draft[0]['service_date_from'];
		//update_statement_bill_draft
		// print_me($statement_draft);
		$data = array(
			"service_date_from"	=> $service_date_from,
		);
		if(count($statement_draft) != 1) {
			$update_statement_draft = $this->billing_statement_model->update_statement_bill_draft($statement_draft[1]['acct_statement_draft_id'], $data);
			if($update_statement_draft) {
				$this->billing_statement_model->remove_statement_bill_draft($statement_draft[0]['acct_statement_draft_id']);
				$this->response_code = 0;
	            $this->response_message = 'Account Statement successfully cancelled.';
			}
		} else {
			$this->billing_statement_model->remove_statement_bill_draft($statement_draft[0]['acct_statement_draft_id']);
			$this->response_code = 0;
	        $this->response_message = 'Account Statement successfully cancelled.';
		}

		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function update_customerdays_statement_bill_draft($draft_id, $customer_days) {
		$data = array(
			"customer_days"	=> $customer_days
		);
		$update = $this->billing_statement_model->update_statement_bill_draft($draft_id, $data);

		if($update) {
			$this->response_code = 0;
	        $this->response_message = 'Customer Days successfully updated.';
		} else {
			$this->response_code = 1;
	        $this->response_message = 'Error please try again.';
		}

		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function create_invoice($draft_id) {
		// if($this->input->is_ajax_request())
		// {
			$draft_statement = $this->billing_statement_model->get_draft_details($draft_id);

			if(!empty($draft_statement)) {
				$this->response_code = 1;
		        $this->response_message = 'Error in creating invoice statement.';
				//Create the Invoice Statement
				$hospice_data = $this->billing_statement_model->get_account($draft_statement['hospiceID']);
				$temp_date = date('Y-m-d');
				$payment_terms = explode("_",$hospice_data['payment_terms']);
				$due_date = date('Y-m-d', strtotime($temp_date.' + '.$payment_terms[0].' days'));
				$cus_days = $this->billing_statement_model->get_customer_days($draft_statement['hospiceID']);

				$temp = $this->billing_statement_model->get_category_total_invoice($draft_statement['hospiceID']);
				$cap = 0;
				$non_cap = 0;
				$purchase_price = 0;
				// print_me($temp);
				foreach($temp as $value) {
					if($value['categoryID'] == 2) {
						if($value['summary_pickup_date'] == "0000-00-00") {
							$now = time();
						} else {
							$now = strtotime($value['summary_pickup_date']);
						}
						// $your_date = strtotime($value['pickup_date']);
						// $datediff = $now - $your_date;
						// $rounddatediff = round($datediff / (60 * 60 * 24));
						// $non_cap += $value['purchase_price']*$rounddatediff;
					} else if($value['categoryID'] == 3) {
						$purchase_price += $value['purchase_price']*$value['equipment_value'];
					} else {
						$cap += $value['purchase_price'];
					}
				}
				$total_invoice = ($cus_days[0]['cus_days'] * $hospice_data['daily_rate']);
				// print_me($total_invoice);
				$data_create_invoice = array(
					"hospiceID" 		=> $draft_statement['hospiceID'],
					"statement_no"		=> $draft_statement['statement_no'],
					"service_date_from"	=> $draft_statement['service_date_from'],
					"service_date_to"	=> $draft_statement['service_date_to'],
					"due_date"			=> $due_date,
					"customer_days"		=> $cus_days[0]['cus_days'] == null ? 0 : $cus_days[0]['cus_days'],
					"total"				=> $total_invoice,
					"non_cap"			=> $non_cap,
					"purchase_item"		=> $purchase_price,
					"credit"			=> 0,
					"balance_owe"		=> 0
				);
				// print_me($data_create_invoice);
				$invoice_statement = $this->billing_statement_model->insert_statement_invoice($data_create_invoice);

				if($invoice_statement) {
					$this->billing_statement_model->remove_statement_bill_draft($draft_id);
					$this->response_code = 0;
		            $this->response_message = 'Successfully created invoice statement.';

		            $data['customers'] = $this->billing_statement_model->get_all_customer($draft_statement['hospiceID'], 0, -1);
		            // print_me($data['customers']);
					$data['is_all_order_confirmed'] = $this->billing_statement_model->is_all_order_confirmed($hospice_id);
					$data['customers_orders'] = Array(
						"cus_orders"	=> Array(),
						"limit"			=> $data['customers']['limit'],
						"start"			=> $data['customers']['start'],
						"totalCount"	=> $data['customers']['totalCount'],
						"totalCustomerCount"	=> $data['customers']['totalCustomerCount']
					);
					// $new_data = Array();
					foreach($data['customers']['result'] as $key => $value) {
						$customer_orders = $this->billing_statement_model->customer_order_list($draft_statement['hospiceID'], $value['patientID']);
						foreach($customer_orders as $cus_key => $cus_value) {
							$cus_quantity = 0;
							if($cus_value['categoryID'] == 2) {
		  						if($cus_value['summary_pickup_date'] == "0000-00-00") {
		  							$now = time();
		  						} else {
		  							$now = strtotime($cus_value['summary_pickup_date']);
		  						}
								$your_date = strtotime($cus_value['pickup_date']);
								$datediff = $now - $your_date;
								$rounddatediff = round($datediff / (60 * 60 * 24));
								$cus_quantity = $rounddatediff;
		  					} else {
		  						$cus_quantity = $cus_value['equipment_value'];
		  					}
		  					$cus_total = 0;
		  					$cus_cap = "";
		  					if($cus_value['categoryID'] == 1) { 
		  						$cus_cap = "X";
		  					}
		  					$cus_purchase_item = 0;
		  					if($cus_value['categoryID'] == 3) { 
		  						$cus_purchase_item = $cus_value['purchase_price'];
		  						$cus_total = $cus_value['equipment_value'] * $cus_value['purchase_price']; 
		  					}
		  					$cus_noncap = 0;
							if($cus_value['categoryID'] == 2) { 
		  						$cus_noncap = $cus_value['purchase_price'];
		  						$cus_total = $cus_quantity * $cus_value['purchase_price'];
		  					}

							$statement_order_data = array (
								"acct_statement_invoice_id"	=> $invoice_statement,
								"patientID"					=> $value['patientID'],
								"uniqueID"					=> $cus_value['uniqueID'],
								"delivery_date"				=> $cus_value['pickup_date'],
								"pickup_date"				=> $cus_value['summary_pickup_date'],
								"quantity"					=> $cus_quantity,
								"cap"						=> $cus_cap,
								"purchase_item"				=> $cus_purchase_item,
								"non_cap"					=> $cus_noncap,
								"total"						=> $cus_total,
								"cus_days"					=> $value['patient_days'],
								"daily_rate"				=> $hospice_data['daily_rate']
							);
							$this->billing_statement_model->insert_statement_order($statement_order_data);
						}
					}
				}
				
			}
		// }
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function test_create_invoice () {
		$hospiceID = 30;
		$date_from = "2020-01-01";
		$date_to = "2020-01-31";
		$temp = $this->billing_statement_model->get_all_customer($hospiceID, 0, -1, $date_from, $date_to);
		$sample = array();
		foreach($temp['result'] as $key => $value) {
			$customer_orders = $this->billing_statement_model->get_category_total_v2_highcost_patients($hospiceID, $value['patientID'], $date_from, $date_to);
			$sample[] = array(
				"patient_info" => $value,
				"customer_orders" => $customer_orders
			);
		}
		print_me($sample);
	}

	public function test_get_customer_order_status_2021() {
		// $customer_orders = $this->billing_statement_model->customer_order_list_for_draft_statement(139, 36187, '2021-08-01', '2021-08-31');

		// print_me($customer_orders);


		$draft_statement = array(
			'hospiceID' => 51,
			'service_date_from' => '2021-08-01',
			'service_date_to' => '2021-08-31',
		);

		$data['customers'] = $this->billing_statement_model->get_all_customer_for_draft_statement(51, 0, -1, '2021-08-01', '2021-08-31');
		$hospice_data = $this->billing_statement_model->get_account($draft_statement['hospiceID']);
		// print_me($data['customers']);
		// $data['is_all_order_confirmed'] = $this->billing_statement_model->is_all_order_confirmed($hospice_id);
		
		// $new_data = Array();
		$cusdayslooptotal = 0;
		foreach($data['customers']['result'] as $data_value) {
			$customer_orders = $this->billing_statement_model->customer_order_list_for_draft_statement($draft_statement['hospiceID'], $data_value['patientID'], $draft_statement['service_date_from'], $draft_statement['service_date_to']);
			$cus_days_los = $this->billing_statement_model->get_customer_days_length_of_stay($data_value['patientID'], $draft_statement['service_date_to'], $draft_statement['service_date_from']);
			if (!empty($cus_days_los)) {
				$data_value['patient_days'] = $cus_days_los['customer_days'];
			}
			if (!empty($customer_orders)) {
				$cusdayslooptotal += $data_value['patient_days'];
			}
			$non_cap = 0;
			$purchase_price = 0;
			foreach($customer_orders as $cus_value) {
				if($cus_value['equip_is_package'] == 0 && $cus_value['is_package'] == 1) {
					continue;
				}

				// Get Full Description - Start
				$summary = $cus_value;
				$summary['item_description_data'] = "";
				if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
				{
					if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
					{
						$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
						$summary['item_description_data'] = $summary['key_desc'];
					}
					else
					{
						//*** DELIVERY not CONFIRMED yet dri mo display ang item ***//
						//check if naay sub equipment using equipment id, work uniqueId
						$subequipment_id = get_subequipment_id($summary['equipmentID']);

						//gets all the id's under the order
						if($subequipment_id)
						{
							$count = 0;
							$patient_lift_sling_count = 0;
							$high_low_full_electric_hospital_bed_count = 0;
							$equipment_count = 0;
							$my_count_sign = 0;
							$my_first_sign = 0;
							$my_second_sign = 0;

							foreach ($subequipment_id as $key) {
								$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);

								if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
								{
									if(empty($value))
									{
										$my_first_sign = 1;
									}
								}
								if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
								{
									if(empty($value))
									{
										$my_second_sign = 1;
									}
								}
								if($my_second_sign == 1 && $my_first_sign == 1)
								{
									$my_count_sign = 1;
								}
								if($value)
								{
									$count++;
									//full electric hospital bed equipment
									if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Hi-Low Full Electric Hospital Bed equipment
									else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Patient Lift with Sling
									else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
									{
										$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
									}
									//Patient Lift Electric with Sling
									else if($summary['equipmentID'] == 353)
									{
										$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
									}
									//Patient Lift Sling
									else if($summary['equipmentID'] == 196)
									{
										$summary['item_description_data'] = $key['key_desc'];
									}
									//(54 & 17) Geri Chair || (66 & 39) Shower Chair
									else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
									{
										$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
									}
									// Oxygen E Portable System && Oxygen Liquid Portable
									else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									break;
									}
									//Oxygen Cylinder Rack
									else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
									{
										$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
										break;
									}
									//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
									else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
									{
										if($my_count_sign == 0)
										{
											//wheelchair & wheelchair reclining
											if($count == 1)
											{
												$temp_item_description = "";
												$temp_item_description2 = "";

												if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
												{
													$key['key_desc'] = '16" Narrow';
												}
												else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
												{
													$key['key_desc'] = '18" Standard';
												}
												else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
												{
													$key['key_desc'] = '20" Wide';
												}
												else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
												{
													$key['key_desc'] = '22" Extra Wide';
												}
												else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
												{
													$key['key_desc'] = '24" Bariatric';
												}
												$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
											}
											else
											{
												$temp_item_description2 = " With ".$key['key_desc'];
											}
											$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
										}
										else
										{
											$temp_item_description = $summary['key_desc'];
											if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
											{
												$temp_item_description2 = " With Elevating Legrests";
											}
											else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
											{
												$temp_item_description2 = " With Footrests";
											}
											$initial_temp_item_description = '20" Wide';
											$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
											break;
										}
									}
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
										{
											$key['key_desc'] = '17" Narrow';
										}
										else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
										{
											$key['key_desc'] = '19" Standard';
										}
										$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
										$summary['item_description_data'] = $temp_item_description;
									}
									else if($summary['equipmentID'] == 30)
									{
										if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
										{
											if($count == 3)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$temp_item_description = " With ".$key['key_desc'];
												$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
									else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
									{
										$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
										if(strlen($samp) > 30)
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
										}
										else
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
										}

										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
										break;
									}
									else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
									{
										$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
										if($count == 1)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
										}
									}
									else if($summary['equipmentID'] == 282)
									{

									}
									//equipments that has no subequipment but gets inside the $value if statement
									else if($summary['equipmentID'] == 14)
									{
										$summary['item_description_data'] = $summary['key_desc'];
									}
									else
									{
										if($summary['categoryID'] == 1)
										{
											$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
											if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 14)
											{
												$summary['item_description_data'] = $summary['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
											{
												$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 353)
											{
												$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
											}
											else
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								} //end of $value
								//for Oxygen E cylinder do not remove as it will cause errors
								else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
								{
									break;
								}
								else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
								{

								}
								else if($summary['equipmentID'] == 282)
								{
									$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
									break;
								}
								//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
								else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
									break;
								}
								//equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
								else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								//for equipments with subequipment but does not fall in $value
								else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
								{
									if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
									{
										$patient_lift_sling_count++;
										if($patient_lift_sling_count == 6)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									else if($summary['equipmentID'] == 398)
									{
										if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
										{
											$high_low_full_electric_hospital_bed_count++;
											if($high_low_full_electric_hospital_bed_count == 2)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
									}
									// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										$equipment_count++;
										if($equipment_count == 2)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								}
								else
								{
									if($summary['categoryID'] == 1)
									{
										$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
										if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
											break;
										}
										else if($non_capped_copy['noncapped_reference'] == 14)
										{
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{

										}
									}
									else
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									}
								}
							}
						}
						else
						{
							if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
							{
								$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
								$summary['item_description_data'] = $summary['key_desc'];
							}
							else
							{
								$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
								$summary['item_description_data'] = $summary['key_desc'];
							}
						}
					}
				}
				else
				{
					$summary['item_description_data'] = "";
					if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
					{
						$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
						$summary['item_description_data'] = $summary['key_desc'];
					}
					else
					{
						//check if the item has sub-equipments using equipment id
						$subequipment_id = get_subequipment_id($summary['equipmentID']);
						//gets all the id's under the order
						if($subequipment_id)
						{
							$count = 0;
							$patient_lift_sling_count = 0;
							$high_low_full_electric_hospital_bed_count = 0;
							$equipment_count = 0;
							$my_count_sign = 0;
							$my_first_sign = 0;
							$my_second_sign = 0;
							foreach ($subequipment_id as $key) {
								$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
								if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
								{
									if(empty($value))
									{
										$my_first_sign = 1;
									}
								}
								if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
								{
									if(empty($value))
									{
										$my_second_sign = 1;
									}
								}
								if($my_second_sign == 1 && $my_first_sign == 1)
								{
									$my_count_sign = 1;
								}
								if($value)
								{
									$count++;
									//equipment full electric hospital bed
									if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Hi-Low Full Electric Hospital Bed equipment
									else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
									{
										$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
									}
									//Patient Lift with Sling
									else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
									{
										$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
									}
									//Patient Lift Electric with Sling
									else if($summary['equipmentID'] == 353)
									{
										$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
									}
									//Patient Lift Sling
									else if($summary['equipmentID'] == 196)
									{
										$summary['item_description_data'] = $key['key_desc'];
									}
									//(54 & 17) Geri Chair || (66 & 39) Shower Chair
									else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
									{
										$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
									}
									// Oxygen E Portable System && Oxygen Liquid Portable
									else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
										break;
									}
									//Oxygen Cylinder Rack
									else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
									{
										$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
										break;
									}
									//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
									else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
									{
										if($my_count_sign == 0)
										{
											//wheelchair & wheelchair reclining
											if($count == 1)
											{
												$temp_item_description = "";
												$temp_item_description2 = "";

												if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
												{
													$key['key_desc'] = '16" Narrow';
												}
												else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
												{
													$key['key_desc'] = '18" Standard';
												}
												else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
												{
													$key['key_desc'] = '20" Wide';
												}
												else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
												{
													$key['key_desc'] = '22" Extra Wide';
												}
												else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
												{
													$key['key_desc'] = '24" Bariatric';
												}
												$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
											}
											else
											{
												$temp_item_description2 = " With ".$key['key_desc'];
											}
											$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
										}
										else
										{
											$temp_item_description = $summary['key_desc'];
											if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
											{
												$temp_item_description2 = " With Elevating Legrests";
											}
											else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
											{
												$temp_item_description2 = " With Footrests";
											}
											$initial_temp_item_description = '20" Wide';
											$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
											break;
										}
									}
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
										{
											$key['key_desc'] = '17" Narrow';
										}
										else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
										{
											$key['key_desc'] = '19" Standard';
										}
										$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
										$summary['item_description_data'] = $temp_item_description;
									}
									else if($summary['equipmentID'] == 30)
									{
										if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
										{
											if($count == 3)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$temp_item_description = " With ".$key['key_desc'];
												$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
									else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
									{
										$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
										if(strlen($samp) > 30)
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
										}
										else
										{
											$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
										}

										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
										break;
									}
									else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
									{
										$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
										if($count == 1)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
										}
									}
									else if($summary['equipmentID'] == 282)
									{

									}
									//equipments that has no subequipment but gets inside the $value if statement
									else if($summary['equipmentID'] == 14)
									{
										$summary['item_description_data'] = $summary['key_desc'];
									}
									else
									{
										if($summary['categoryID'] == 1)
										{
											$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
											if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 14)
											{
												$summary['item_description_data'] = $summary['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
											{
												$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
											}
											else if($non_capped_copy['noncapped_reference'] == 353)
											{
												$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
											}
											else
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								} //end of $value
								//for Oxygen E cylinder do not remove as it will cause errors
								else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
								{
									break;
								}
								else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
								{

								}
								else if($summary['equipmentID'] == 282)
								{
									$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
									break;
								}
								//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
								else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
									break;
								} //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
								else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								//for equipments with subequipment but does not fall in $value
								else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
								{
									if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
									{
										$patient_lift_sling_count++;
										if($patient_lift_sling_count == 6)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
									else if($summary['equipmentID'] == 398)
									{
										if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
										{
											$high_low_full_electric_hospital_bed_count++;
											if($high_low_full_electric_hospital_bed_count == 2)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
									}
									// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
									else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
									{
										$equipment_count++;
										if($equipment_count == 2)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								}
								else
								{
									if($summary['categoryID'] == 1)
									{
										$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
										if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
											break;
										}
										else if($non_capped_copy['noncapped_reference'] == 14)
										{
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{

										}
									}
									else
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									}
								}
							}
						}
						else
						{
							$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
							$summary['item_description_data'] = $summary['key_desc'];
						}
					}
				}
				// Get Full Description - End

				// Get Item Group Rates - Start
				$item = $cus_value;
				$item['key_desc'] = $summary['item_description_data'];
				$item['hospiceID'] = $draft_statement['hospiceID'];
				switch($item['equipmentID']) {
					case 49:
					$subequipdetails = array();
					$check = "wala";
					if(strpos($item['key_desc'], "16") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
					}
					if(strpos($item['key_desc'], "18") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
					}
					if(strpos($item['key_desc'], "20") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
					}
					if(strpos($item['key_desc'], "22") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "22_inch");
					}
					if(strpos($item['key_desc'], "24") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "24_inch");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
					case 64:
					$subequipdetails = array();
					if(strpos($item['key_desc'], "16") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
					}
					if(strpos($item['key_desc'], "18") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
					}
					if(strpos($item['key_desc'], "20") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
					case 32:
					$subequipdetails = array();
					if(strpos($item['key_desc'], "E Cylinder 6 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_6_rack");
					}
					if(strpos($item['key_desc'], "E Cylinder 12 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_12_rack");
					}
					if(strpos($item['key_desc'], "M6 Cylinder 6 Rack") != false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_6_rack");
					}
					if(strpos($item['key_desc'], "M6 Cylinder 12 Rack") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_12_rack");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					if ($cus_value['patientID'] == 36187) {
						print_me($item);
						print_me('==============================================');
						print_me($subequipdetails);
						print_me('++++++++++++++++++++++++++++++++++++++++++++++');
					}
					break;
					case 29: case 334: case 343:
					$getassignedequipmentid = $this->equipment_model->get_assigned_equipment_details($item['hospiceID'], 29);
					$subequipdetails = array();
					if(strpos($item['key_desc'], "5L") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "5_liters");
					}
					if(strpos($item['key_desc'], "10L") !== false){
						$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "10_liters");
					}
	
					if($subequipdetails != null && count($subequipdetails) > 0) {
	
					}
					$summary_response[] = array(
						"sub_equip_details" => $subequipdetails,
						"patientID" => $item['patientID'],
						"uniqueID" => $item['uniqueID'],
						"equipmentID" => $item['equipmentID'],
						"equipmentVal" => $item['equipmentVal'],
						"addressID" => $item['addressID'],
						"getassignedequipmentid" => $getassignedequipmentid
					);
					$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
					$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
					$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
					break;
				}
				// Get Item Group Rates - End

				$cus_quantity = 0;
				$cus_noncap = 0;
				$cus_total = 0;
				if($cus_value['categoryID'] == 2) {
					// ===================== DEPRECATED VERSION 1 ================= START
					// if($cus_value['summary_pickup_date'] == "0000-00-00") {
					// 	$now = time();
					// } else {
					// 	$now = strtotime($cus_value['summary_pickup_date']);
					// }
					// $your_date = strtotime($cus_value['pickup_date']);
					// $datediff = $now - $your_date;
					// $rounddatediff = round($datediff / (60 * 60 * 24));
					// $cus_quantity = $rounddatediff;
					// ===================== DEPRECATED VERSION 1 ================= END

					$your_date = strtotime($cus_value['actual_order_date']);
					$your_date_v2 = new DateTime($cus_value['actual_order_date']);
					$new_pickupdate = strtotime(date("Y-m-t", strtotime($cus_value['actual_order_date'])));
					$new_pickupdate_v2 = new DateTime(date("Y-m-t", strtotime($cus_value['actual_order_date'])));
					$temporary_service_date_to = date("Y-m-t", strtotime($draft_statement['service_date_from']));
					$isSummaryPickupDate = false;
					
					if($cus_value['summary_pickup_date'] != "0000-00-00" && ($cus_value['summary_pickup_date'] <= $temporary_service_date_to)) {
						
						if ($cus_value['pickup_discharge_date'] !== '0000-00-00' && $cus_value['pickup_discharge_date'] !== null) {
							$now = strtotime($cus_value['pickup_discharge_date']);
							$now_v2 = new DateTime($cus_value['pickup_discharge_date']);
							$now_v2_other = new DateTime($cus_value['summary_pickup_date']);
						} else {
							$now = strtotime($cus_value['summary_pickup_date']);
							$now_v2 = new DateTime($cus_value['summary_pickup_date']);
							$now_v2_other = new DateTime($cus_value['summary_pickup_date']);
						}
						$isSummaryPickupDate = true;
					} else {
						// $now = time();
						$now = strtotime($draft_statement['service_date_to']);
						$now_v2 = new DateTime($draft_statement['service_date_to']);
						$now_v2_other = new DateTime($draft_statement['service_date_to']);
					}

					// ((date("Y", strtotime($temporary_service_date_to)) > date("Y", $your_date)) && $isSummaryPickupDate == false) || 
					if((((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) < date("m", $your_date))) && $isSummaryPickupDate == false) ) {
						$now = $new_pickupdate;
						$now_v2 = $new_pickupdate_v2;
						// $datediff = $now - $your_date;
						$datediff = $now_v2->diff($your_date_v2)->format('%a');
					} else {
						if((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) == date("m", $your_date))) {
							$temponewdate = $your_date;
							$temponewdate_v2 = $your_date_v2;
							$datediff = $now_v2->diff($temponewdate_v2)->format('%a');
						} else if ((date("m", $now) < date("m", strtotime($temporary_service_date_to)) && date("Y", $now) == date("Y", strtotime($temporary_service_date_to))) || (date("Y", $now) < date("Y", strtotime($temporary_service_date_to)))) {
							$temponewdate = strtotime($draft_statement['service_date_from']);
							$temponewdate_v2 = new DateTime($draft_statement['service_date_from']);
							$datediff = $now_v2_other->diff($temponewdate_v2)->format('%a') + 1;
						} else {
							$temponewdate = strtotime($draft_statement['service_date_from']);
							$temponewdate_v2 = new DateTime($draft_statement['service_date_from']);
							$datediff = $now_v2->diff($temponewdate_v2)->format('%a') + 1;
							if ($data_value['patientID'] == 29935 && $cus_value['equipmentID'] == 19) {
								print_me("+_+_+_+_+_++_+_+_+_+_+_+_+_+_+_+_+_+_+_++_+_+_+_+_+_ START");
								print_me($temponewdate_v2);
								print_me($now_v2);
								print_me($datediff);
								print_me($temporary_service_date_to);
								print_me($cus_value['actual_order_date']);
								print_me($cus_value);
								print_me("+_+_+_+_+_++_+_+_+_+_+_+_+_+_+_+_+_+_+_++_+_+_+_+_+_ END");
							}
						}
						// $temponewdate = strtotime(date("Y-m-01", time()));
						// $datediff = $now - $temponewdate;
						
					}

					// $datediff = $now - $your_date;
					
					// $rounddatediff = round($datediff / (60 * 60 * 24)) + 1;
					$rounddatediff = $datediff;

					// if ($isSummaryPickupDate == false) {
					// 	$rounddatediff--;
					// }

					if ($rounddatediff == 0) {
						$rounddatediff = 1;
					}

					if($cus_value['equipmentID'] != 49 && $cus_value['equipmentID'] != 64 && $cus_value['equipmentID'] != 32 && $cus_value['equipmentID'] != 29 && $cus_value['daily_rate'] != 344 && $cus_value['equipmentID'] == 343) {
						if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
							$rounddatediff = 1;
						}
					}
					
					if($cus_value['equipmentID'] == 176) {
						$rounddatediff = 1;
					}

					$cus_quantity = $rounddatediff;

					$dailratetemporary = 0;
					//New Calculation 11/15/19 =======> START
					if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
						// $dailratetemporary = $cus_value['monthly_rate'];
						$cus_total = $cus_value['monthly_rate'];
						$cus_noncap = $cus_value['monthly_rate'];
						$cus_quantity = 1;
						if ($cus_value['activity_reference'] == 3 && $cus_value['uniqueID_reference'] != 0) {
							$cus_total = 0;
							$non_cap += $cus_total;
						}
					} else {
						$temptotaldailyrate = $rounddatediff * $cus_value['daily_rate'];
						if($temptotaldailyrate > $cus_value['monthly_rate']) {
							if($cus_value['monthly_rate'] == 0 || $cus_value['monthly_rate'] == null) {
								$cus_total = $temptotaldailyrate;
								$cus_noncap = $cus_value['daily_rate'];
							} else {
								$cus_total = $cus_value['monthly_rate'];
								$cus_noncap = $cus_value['monthly_rate'];
								$cus_quantity = 1;
								if ($cus_value['activity_reference'] == 3 && $cus_value['uniqueID_reference'] != 0) {
									$cus_total = 0;
									$non_cap += $cus_total;
								}
							}
							// $dailratetemporary = $cus_value['monthly_rate'];
							// $total += $dailratetemporary;
						} else {
							$cus_total = $temptotaldailyrate;
							$cus_noncap = $cus_value['daily_rate'];
						}
					}
					//New Calculation 11/15/19 =======> END

					// $total += $dailratetemporary*$rounddatediff;

					//Increment values for Information Box
					$non_cap += $cus_total;
					
				} else {
					$cus_quantity = $cus_value['equipment_value'];
				}
				$cus_cap = "";
				if($cus_value['categoryID'] == 1) { 
					$cus_cap = "X";
					$cus_quantity = 1;
				}
				$cus_purchase_item = 0;
				if($cus_value['categoryID'] == 3) {
					$quan = $cus_value['equipment_value'];
					if($cus_value['equipment_quantity'] !== "" && $cus_value['equipment_quantity'] !== null) {
						$quan = $cus_value['equipment_quantity'];
					}

					$cus_quantity = $quan;
					$cus_purchase_item = $cus_value['purchase_price'];
					$cus_total = $quan * $cus_value['purchase_price']; 

					//Increment values for Information Box
					$purchase_price += $cus_total;
				}
				
				// $cus_noncap = 0;
				// if($cus_value['categoryID'] == 2) { 
				// 	$cus_noncap = $cus_value['purchase_price'];
				// 	$cus_total = $cus_quantity * $cus_value['purchase_price'];
				// }
				$disp_sum_pick_date = '0000-00-00';
				
				$tempo_service_date_to = date("Y-m-t", strtotime($draft_statement['service_date_from']));
				if ($cus_value['summary_pickup_date'] <= $tempo_service_date_to) {
					$disp_sum_pick_date = $cus_value['summary_pickup_date'];
				}

				$statement_order_data = array (
					"acct_statement_invoice_id"	=> 123,
					"patientID"					=> $data_value['patientID'],
					"uniqueID"					=> $cus_value['uniqueID'],
					"key_desc"					=> $summary['item_description_data'],
					"delivery_date"				=> $cus_value['actual_order_date'],
					"pickup_date"				=> $disp_sum_pick_date,
					"quantity"					=> $cus_quantity,
					"cap"						=> $cus_cap,
					"purchase_item"				=> $cus_purchase_item,
					"non_cap"					=> $cus_noncap,
					"total"						=> $cus_total,
					"cus_days"					=> $data_value['patient_days'],
					"daily_rate"				=> $hospice_data['daily_rate'],
					"addressID"					=> $cus_value['addressID'],
					"original_activity_typeid"	=> $cus_value['original_activity_typeid']
				);
				// $this->billing_statement_model->insert_statement_order($statement_order_data);

				if ($data_value['patientID'] == 29935 && $cus_value['equipmentID'] == 19) {
					print_me($statement_order_data);
					print_me('--------------------------------------------');
					print_me($rounddatediff);
					print_me('********************************************');
					print_me($cus_value);
					print_me('&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&');
					print_me($datediff);

					break;
				}
			}
		} // End of Insert Statement Order
	}

	public function create_invoice_v2($selected_draft_ids) {
		if($this->input->is_ajax_request())
		{
			$selected_draft_id_exploded = explode("-",$selected_draft_ids);
			$customerdaysmessage = "";
			$customerdaysmessage_counter = 0;
			// print_me($selected_draft_id_exploded);
			foreach($selected_draft_id_exploded as $draft_id) {
				$draft_statement = $this->billing_statement_model->get_draft_details($draft_id);
				// print_me($draft_statement);
				if(!empty($draft_statement)) {
					if($draft_statement['track_census'] == 1 && $draft_statement['customer_days'] == null) {
						if($customerdaysmessage_counter > 0) {
							$customerdaysmessage += "," + $draft_statement['statement_no'];
						} else {
							$customerdaysmessage += $draft_statement['statement_no'] + "";
						}
						continue;
					}
					$customerdaysmessage_counter++;

					$this->response_code = 1;
			        $this->response_message = 'Error in creating invoice statement.';
					//Create the Invoice Statement
					$hospice_data = $this->billing_statement_model->get_account($draft_statement['hospiceID']);
					$temp_date = date('Y-m-d');
					$payment_terms = explode("_",$hospice_data['payment_terms']);
					$due_date = date('Y-m-d', strtotime($temp_date.' + '.$payment_terms[0].' days'));
					// $cus_days = $this->billing_statement_model->get_customer_days($draft_statement['hospiceID']);
					$cus_days_v2 = 0;

					// $temp = $this->billing_statement_model->get_category_total_invoice($draft_statement['hospiceID'], $draft_statement['service_date_from'], $draft_statement['service_date_to']);
					$cap = 0;
					$non_cap = 0;
					$purchase_price = 0;

					// print_me($temp);
					// foreach($temp as $value) {
					// 	// ===================== For Calculation of Customer Days ===================== START
					// 	$cus_days_v2 += 0;
					// 	// ===================== For Calculation of Customer Days ===================== END

					// 	if($value['categoryID'] == 2) {
					// 		// ===================== DEPRECATED VERSION 1 ================= START
					// 		// if($value['summary_pickup_date'] == "0000-00-00") {
					// 		// 	$now = time();
					// 		// } else {
					// 		// 	$now = strtotime($value['summary_pickup_date']);
					// 		// }
					// 		// // $your_date = strtotime($value['pickup_date']);
					// 		// // $datediff = $now - $your_date;
					// 		// // $rounddatediff = round($datediff / (60 * 60 * 24));
					// 		// // $non_cap += $value['purchase_price']*$rounddatediff;
					// 		// ===================== DEPRECATED VERSION 1 ================= END

					// 		$your_date = strtotime($value['actual_order_date']);
					// 		$new_pickupdate = strtotime(date("Y-m-t", strtotime($value['actual_order_date'])));
					// 		$temporary_service_date_to = date("Y-m-t", strtotime($draft_statement['service_date_from']));
					// 		$isSummaryPickupDate = false;

					// 		if($value['summary_pickup_date'] == "0000-00-00") {
					// 			$now = time();
					// 		} else {
					// 			// $now = strtotime($value['summary_pickup_date']);
					// 			$isSummaryPickupDate = true;
					// 		}
					// 		if( ((date("Y", strtotime($temporary_service_date_to)) > date("Y", $your_date)) && $isSummaryPickupDate == true) || (((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) < date("m", $your_date))) && $isSummaryPickupDate == true) ) {
					// 			$now = $new_pickupdate;
					// 			$datediff = $now - $your_date;
					// 		} else {
					// 			if(date("m", strtotime($temporary_service_date_to)) == date("m", $your_date)) {
					// 				$temponewdate = $your_date;
					// 			} else {
					// 				$temponewdate = strtotime(date("Y-m-01"));
					// 			}
					// 			// $temponewdate = strtotime(date("Y-m-01", time()));
					// 			$datediff = $now - $temponewdate;
					// 		}

					// 		// $datediff = $now - $your_date;
					// 		$rounddatediff = round($datediff / (60 * 60 * 24)) + 1;

					// 		if($value['daily_rate'] == 0 || $value['daily_rate'] == null) {
					// 			$rounddatediff = 1;
					// 		}
					// 		if($value['equipmentID'] == 176) {
					// 			$rounddatediff = 1;
					// 		}

					// 		$dailratetemporary = 0;
					// 		//New Calculation 11/15/19 =======> START
					// 		if($value['daily_rate'] == 0 || $value['daily_rate'] == null) {
					// 			// $dailratetemporary = $value['monthly_rate'];
					// 			$non_cap += $value['monthly_rate'];
					// 		} else {
					// 			$temptotaldailyrate = $rounddatediff * $value['daily_rate'];
					// 			if($temptotaldailyrate > $value['monthly_rate']) {
					// 				if($value['monthly_rate'] == 0 || $value['monthly_rate'] == null) {
					// 					$non_cap += $temptotaldailyrate;
					// 				} else {
					// 					$non_cap += $value['monthly_rate'];
					// 				}
					// 				// $dailratetemporary = $value['monthly_rate'];
					// 				// $total += $dailratetemporary;
					// 			} else {
					// 				$non_cap += $temptotaldailyrate;
					// 			}
					// 		}
					// 		//New Calculation 11/15/19 =======> END

					// 		// $total += $dailratetemporary*$rounddatediff;
					// 	} else if($value['categoryID'] == 3) {
					// 		$quan = $value['equipment_value'];
					// 		if($value['equipment_quantity'] !== "" && $value['equipment_quantity'] !== null) {
					// 			$quan = $value['equipment_quantity'];
					// 		}
					// 		$purchase_price += $value['purchase_price'] * $quan;
					// 	} else {
					// 		$cap += $value['purchase_price'];
					// 	}
					// }
					// $total_invoice = ($cus_days[0]['cus_days'] * $hospice_data['daily_rate']);
					// print_me($total_invoice);

					$is_exist = $this->billing_statement_model->get_current_statement_invoice($draft_statement['hospiceID'], $draft_statement['statement_no'], $draft_statement['service_date_from'], $draft_statement['service_date_to']);

					if (empty($is_exist)) {
						$temp_invoice_no = (int) strtotime(date('Y-m-d H:i:s A'));
						$invoice_no = $temp_invoice_no + $draft_statement['hospiceID'];
						$data_create_invoice = array(
							"hospiceID" 		=> $draft_statement['hospiceID'],
							"statement_no"		=> $draft_statement['statement_no'],
							"invoice_no"		=> $invoice_no,
							"service_date_from"	=> $draft_statement['service_date_from'],
							"service_date_to"	=> $draft_statement['service_date_to'],
							"due_date"			=> $due_date,
							"customer_days"		=> 0,
							"total"				=> 0,
							"non_cap"			=> 0,
							"purchase_item"		=> 0,
							"credit"			=> 0,
							"balance_owe"		=> 0,
							"receive_status"	=> 0
						);
						// print_me($data_create_invoice);
						$invoice_statement = $this->billing_statement_model->insert_statement_invoice($data_create_invoice);

						if($invoice_statement) {
							//Move Reconciliation to Invoice
							$data_recon = array(
								"invoice_reference"	=> $draft_statement['statement_no']
							);
							$this->billing_statement_model->update_statement_reconciliation_by_draft_reference($draft_statement['statement_no'], $data_recon);

							//Remove Draft Statement
							$this->billing_statement_model->remove_statement_bill_draft($draft_id);

							$this->response_code = 0;
							$this->response_message = 'Successfully created invoice statement.';

							$data['customers'] = $this->billing_statement_model->get_all_customer_for_draft_statement($draft_statement['hospiceID'], 0, -1, $draft_statement['service_date_from'], $draft_statement['service_date_to']);
							// print_me($data['customers']);
							// $data['is_all_order_confirmed'] = $this->billing_statement_model->is_all_order_confirmed($hospice_id);
							$data['customers_orders'] = Array(
								"cus_orders"	=> Array(),
								"limit"			=> $data['customers']['limit'],
								"start"			=> $data['customers']['start'],
								"totalCount"	=> $data['customers']['totalCount'],
								"totalCustomerCount"	=> $data['customers']['totalCustomerCount']
							);
							// $new_data = Array();
							$cusdayslooptotal = 0;
							foreach($data['customers']['result'] as $data_value) {
								$customer_orders = $this->billing_statement_model->customer_order_list_for_draft_statement($draft_statement['hospiceID'], $data_value['patientID'], $draft_statement['service_date_from'], $draft_statement['service_date_to']);
								$cus_days_los = $this->billing_statement_model->get_customer_days_length_of_stay($data_value['patientID'], $draft_statement['service_date_to'], $draft_statement['service_date_from']);
								if (!empty($cus_days_los)) {
									$data_value['patient_days'] = $cus_days_los['customer_days'];
								}
								if (!empty($customer_orders)) {
									$cusdayslooptotal += $data_value['patient_days'];
								}
								foreach($customer_orders as $cus_value) {
									if($cus_value['equip_is_package'] == 0 && $cus_value['is_package'] == 1) {
										continue;
									}

									// Get Full Description - Start
									$summary = $cus_value;
									$summary['item_description_data'] = "";
									if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
									{
										if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{
											//*** DELIVERY not CONFIRMED yet dri mo display ang item ***//
											//check if naay sub equipment using equipment id, work uniqueId
											$subequipment_id = get_subequipment_id($summary['equipmentID']);
					
											//gets all the id's under the order
											if($subequipment_id)
											{
												$count = 0;
												$patient_lift_sling_count = 0;
												$high_low_full_electric_hospital_bed_count = 0;
												$equipment_count = 0;
												$my_count_sign = 0;
												$my_first_sign = 0;
												$my_second_sign = 0;
					
												foreach ($subequipment_id as $key) {
													$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
					
													if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
													{
														if(empty($value))
														{
															$my_first_sign = 1;
														}
													}
													if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
													{
														if(empty($value))
														{
															$my_second_sign = 1;
														}
													}
													if($my_second_sign == 1 && $my_first_sign == 1)
													{
														$my_count_sign = 1;
													}
													if($value)
													{
														$count++;
														//full electric hospital bed equipment
														if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
														{
															$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
														}
														//Hi-Low Full Electric Hospital Bed equipment
														else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
														{
															$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
														}
														//Patient Lift with Sling
														else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
														{
															$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
														}
														//Patient Lift Electric with Sling
														else if($summary['equipmentID'] == 353)
														{
															$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
														}
														//Patient Lift Sling
														else if($summary['equipmentID'] == 196)
														{
															$summary['item_description_data'] = $key['key_desc'];
														}
														//(54 & 17) Geri Chair || (66 & 39) Shower Chair
														else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
														{
															$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
														}
														// Oxygen E Portable System && Oxygen Liquid Portable
														else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'];
														break;
														}
														//Oxygen Cylinder Rack
														else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
														{
															$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
															break;
														}
														//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
														else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
														{
															if($my_count_sign == 0)
															{
																//wheelchair & wheelchair reclining
																if($count == 1)
																{
																	$temp_item_description = "";
																	$temp_item_description2 = "";
					
																	if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
																	{
																		$key['key_desc'] = '16" Narrow';
																	}
																	else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
																	{
																		$key['key_desc'] = '18" Standard';
																	}
																	else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
																	{
																		$key['key_desc'] = '20" Wide';
																	}
																	else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
																	{
																		$key['key_desc'] = '22" Extra Wide';
																	}
																	else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
																	{
																		$key['key_desc'] = '24" Bariatric';
																	}
																	$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
																}
																else
																{
																	$temp_item_description2 = " With ".$key['key_desc'];
																}
																$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
															}
															else
															{
																$temp_item_description = $summary['key_desc'];
																if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
																{
																	$temp_item_description2 = " With Elevating Legrests";
																}
																else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
																{
																	$temp_item_description2 = " With Footrests";
																}
																$initial_temp_item_description = '20" Wide';
																$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
																break;
															}
														}
														else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
														{
															if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
															{
																$key['key_desc'] = '17" Narrow';
															}
															else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
															{
																$key['key_desc'] = '19" Standard';
															}
															$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
															$summary['item_description_data'] = $temp_item_description;
														}
														else if($summary['equipmentID'] == 30)
														{
															if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
															{
																if($count == 3)
																{
																	$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																	$temp_item_description = " With ".$key['key_desc'];
																	$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
																}
															}
															else
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc'];
															}
														}
														//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
														else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
														{
															$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
															if(strlen($samp) > 30)
															{
																$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
															}
															else
															{
																$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
															}
					
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
															break;
														}
														else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
														{
															$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
															if($count == 1)
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
															}
														}
														else if($summary['equipmentID'] == 282)
														{
					
														}
														//equipments that has no subequipment but gets inside the $value if statement
														else if($summary['equipmentID'] == 14)
														{
															$summary['item_description_data'] = $summary['key_desc'];
														}
														else
														{
															if($summary['categoryID'] == 1)
															{
																$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
																if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
																{
																	$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																	$summary['item_description_data'] = $summary['key_desc'];
																	break;
																}
																else if($non_capped_copy['noncapped_reference'] == 14)
																{
																	$summary['item_description_data'] = $summary['key_desc'];
																}
																else if($non_capped_copy['noncapped_reference'] == 282)
																{
																	$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
																	$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																	$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
																	break;
																}
																else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
																{
																	$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
																}
																else if($non_capped_copy['noncapped_reference'] == 353)
																{
																	$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
																}
																else
																{
																	$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																	$summary['item_description_data'] = $summary['key_desc'];
																}
															}
															else
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc'];
															}
														}
													} //end of $value
													//for Oxygen E cylinder do not remove as it will cause errors
													else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
													{
														break;
													}
													else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
													{
					
													}
													else if($summary['equipmentID'] == 282)
													{
														$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
														break;
													}
													//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
													else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
														break;
													}
													//equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
													else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
													}
													//for equipments with subequipment but does not fall in $value
													else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
													{
														if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
														{
															$patient_lift_sling_count++;
															if($patient_lift_sling_count == 6)
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc'];
															}
														}
														else if($summary['equipmentID'] == 398)
														{
															if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
															{
																$high_low_full_electric_hospital_bed_count++;
																if($high_low_full_electric_hospital_bed_count == 2)
																{
																	$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																	$summary['item_description_data'] = $summary['key_desc'];
																}
															}
														}
														// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
														else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
														{
															$equipment_count++;
															if($equipment_count == 2)
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc'];
															}
														}
													}
													else
													{
														if($summary['categoryID'] == 1)
														{
															$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
															if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc'];
																break;
															}
															else if($non_capped_copy['noncapped_reference'] == 14)
															{
																$summary['item_description_data'] = $summary['key_desc'];
															}
															else
															{
					
															}
														}
														else
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'];
														}
													}
												}
											}
											else
											{
												if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
												{
													$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
													$summary['item_description_data'] = $summary['key_desc'];
												}
												else
												{
													$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
													$summary['item_description_data'] = $summary['key_desc'];
												}
											}
										}
									}
									else
									{
										$summary['item_description_data'] = "";
										if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{
											//check if the item has sub-equipments using equipment id
											$subequipment_id = get_subequipment_id($summary['equipmentID']);
											//gets all the id's under the order
											if($subequipment_id)
											{
												$count = 0;
												$patient_lift_sling_count = 0;
												$high_low_full_electric_hospital_bed_count = 0;
												$equipment_count = 0;
												$my_count_sign = 0;
												$my_first_sign = 0;
												$my_second_sign = 0;
												foreach ($subequipment_id as $key) {
													$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
													if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
													{
														if(empty($value))
														{
															$my_first_sign = 1;
														}
													}
													if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
													{
														if(empty($value))
														{
															$my_second_sign = 1;
														}
													}
													if($my_second_sign == 1 && $my_first_sign == 1)
													{
														$my_count_sign = 1;
													}
													if($value)
													{
														$count++;
														//equipment full electric hospital bed
														if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
														{
															$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
														}
														//Hi-Low Full Electric Hospital Bed equipment
														else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
														{
															$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
														}
														//Patient Lift with Sling
														else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
														{
															$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
														}
														//Patient Lift Electric with Sling
														else if($summary['equipmentID'] == 353)
														{
															$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
														}
														//Patient Lift Sling
														else if($summary['equipmentID'] == 196)
														{
															$summary['item_description_data'] = $key['key_desc'];
														}
														//(54 & 17) Geri Chair || (66 & 39) Shower Chair
														else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
														{
															$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
														}
														// Oxygen E Portable System && Oxygen Liquid Portable
														else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'];
															break;
														}
														//Oxygen Cylinder Rack
														else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
														{
															$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
															break;
														}
														//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
														else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
														{
															if($my_count_sign == 0)
															{
																//wheelchair & wheelchair reclining
																if($count == 1)
																{
																	$temp_item_description = "";
																	$temp_item_description2 = "";
					
																	if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
																	{
																		$key['key_desc'] = '16" Narrow';
																	}
																	else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
																	{
																		$key['key_desc'] = '18" Standard';
																	}
																	else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
																	{
																		$key['key_desc'] = '20" Wide';
																	}
																	else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
																	{
																		$key['key_desc'] = '22" Extra Wide';
																	}
																	else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
																	{
																		$key['key_desc'] = '24" Bariatric';
																	}
																	$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
																}
																else
																{
																	$temp_item_description2 = " With ".$key['key_desc'];
																}
																$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
															}
															else
															{
																$temp_item_description = $summary['key_desc'];
																if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
																{
																	$temp_item_description2 = " With Elevating Legrests";
																}
																else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
																{
																	$temp_item_description2 = " With Footrests";
																}
																$initial_temp_item_description = '20" Wide';
																$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
																break;
															}
														}
														else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
														{
															if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
															{
																$key['key_desc'] = '17" Narrow';
															}
															else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
															{
																$key['key_desc'] = '19" Standard';
															}
															$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
															$summary['item_description_data'] = $temp_item_description;
														}
														else if($summary['equipmentID'] == 30)
														{
															if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
															{
																if($count == 3)
																{
																	$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																	$temp_item_description = " With ".$key['key_desc'];
																	$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
																}
															}
															else
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc'];
															}
														}
														//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
														else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
														{
															$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
															if(strlen($samp) > 30)
															{
																$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
															}
															else
															{
																$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
															}
					
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
															break;
														}
														else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
														{
															$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
															if($count == 1)
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
															}
														}
														else if($summary['equipmentID'] == 282)
														{
					
														}
														//equipments that has no subequipment but gets inside the $value if statement
														else if($summary['equipmentID'] == 14)
														{
															$summary['item_description_data'] = $summary['key_desc'];
														}
														else
														{
															if($summary['categoryID'] == 1)
															{
																$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
																if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
																{
																	$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																	$summary['item_description_data'] = $summary['key_desc'];
																	break;
																}
																else if($non_capped_copy['noncapped_reference'] == 14)
																{
																	$summary['item_description_data'] = $summary['key_desc'];
																}
																else if($non_capped_copy['noncapped_reference'] == 282)
																{
																	$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
																	$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																	$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
																	break;
																}
																else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
																{
																	$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
																}
																else if($non_capped_copy['noncapped_reference'] == 353)
																{
																	$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
																}
																else
																{
																	$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																	$summary['item_description_data'] = $summary['key_desc'];
																}
															}
															else
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc'];
															}
														}
													} //end of $value
													//for Oxygen E cylinder do not remove as it will cause errors
													else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
													{
														break;
													}
													else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
													{
					
													}
													else if($summary['equipmentID'] == 282)
													{
														$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
														break;
													}
													//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
													else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
														break;
													} //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
													else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
													}
													//for equipments with subequipment but does not fall in $value
													else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
													{
														if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
														{
															$patient_lift_sling_count++;
															if($patient_lift_sling_count == 6)
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc'];
															}
														}
														else if($summary['equipmentID'] == 398)
														{
															if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
															{
																$high_low_full_electric_hospital_bed_count++;
																if($high_low_full_electric_hospital_bed_count == 2)
																{
																	$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																	$summary['item_description_data'] = $summary['key_desc'];
																}
															}
														}
														// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
														else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
														{
															$equipment_count++;
															if($equipment_count == 2)
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc'];
															}
														}
													}
													else
													{
														if($summary['categoryID'] == 1)
														{
															$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
															if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
															{
																$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
																$summary['item_description_data'] = $summary['key_desc'];
																break;
															}
															else if($non_capped_copy['noncapped_reference'] == 14)
															{
																$summary['item_description_data'] = $summary['key_desc'];
															}
															else
															{
					
															}
														}
														else
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'];
														}
													}
												}
											}
											else
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
										}
									}
									// Get Full Description - End

									// Get Item Group Rates - Start
									$item = $cus_value;
									$item['key_desc'] = $summary['item_description_data'];
									$item['hospiceID'] = $draft_statement['hospiceID'];
									switch($item['equipmentID']) {
										case 49:
										$subequipdetails = array();
										$check = "wala";
										if(strpos($item['key_desc'], "16") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
										}
										if(strpos($item['key_desc'], "18") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
										}
										if(strpos($item['key_desc'], "20") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
										}
										if(strpos($item['key_desc'], "22") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "22_inch");
										}
										if(strpos($item['key_desc'], "24") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "24_inch");
										}
						
										if($subequipdetails != null && count($subequipdetails) > 0) {
						
										}
										$summary_response[] = array(
											"sub_equip_details" => $subequipdetails,
											"patientID" => $item['patientID'],
											"uniqueID" => $item['uniqueID'],
											"equipmentID" => $item['equipmentID'],
											"equipmentVal" => $item['equipmentVal'],
											"addressID" => $item['addressID'],
										);
										$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
										$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
										$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
										break;
										case 64:
										$subequipdetails = array();
										if(strpos($item['key_desc'], "16") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
										}
										if(strpos($item['key_desc'], "18") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
										}
										if(strpos($item['key_desc'], "20") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
										}
						
										if($subequipdetails != null && count($subequipdetails) > 0) {
						
										}
										$summary_response[] = array(
											"sub_equip_details" => $subequipdetails,
											"patientID" => $item['patientID'],
											"uniqueID" => $item['uniqueID'],
											"equipmentID" => $item['equipmentID'],
											"equipmentVal" => $item['equipmentVal'],
											"addressID" => $item['addressID'],
										);
										$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
										$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
										$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
										break;
										case 32:
										$subequipdetails = array();
										if(strpos($item['key_desc'], "E Cylinder 6 Rack") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_6_rack");
										}
										if(strpos($item['key_desc'], "E Cylinder 12 Rack") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_12_rack");
										}
										if(strpos($item['key_desc'], "M6 Cylinder 6 Rack") != false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_6_rack");
										}
										if(strpos($item['key_desc'], "M6 Cylinder 12 Rack") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_12_rack");
										}
						
										if($subequipdetails != null && count($subequipdetails) > 0) {
						
										}
										$summary_response[] = array(
											"sub_equip_details" => $subequipdetails,
											"patientID" => $item['patientID'],
											"uniqueID" => $item['uniqueID'],
											"equipmentID" => $item['equipmentID'],
											"equipmentVal" => $item['equipmentVal'],
											"addressID" => $item['addressID'],
										);
										$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
										$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
										$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
										break;
										case 29: case 334: case 343:
										$getassignedequipmentid = $this->equipment_model->get_assigned_equipment_details($item['hospiceID'], 29);
										$subequipdetails = array();
										if(strpos($item['key_desc'], "5L") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "5_liters");
										}
										if(strpos($item['key_desc'], "10L") !== false){
											$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "10_liters");
										}
						
										if($subequipdetails != null && count($subequipdetails) > 0) {
						
										}
										$summary_response[] = array(
											"sub_equip_details" => $subequipdetails,
											"patientID" => $item['patientID'],
											"uniqueID" => $item['uniqueID'],
											"equipmentID" => $item['equipmentID'],
											"equipmentVal" => $item['equipmentVal'],
											"addressID" => $item['addressID'],
											"getassignedequipmentid" => $getassignedequipmentid
										);
										$cus_value['purchase_price'] = $subequipdetails['purchase_price'];
										$cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
										$cus_value['daily_rate'] = $subequipdetails['daily_rate'];
										break;
									}
									// Get Item Group Rates - End

									$cus_quantity = 0;
									$cus_noncap = 0;
									$cus_total = 0;
									if($cus_value['categoryID'] == 2) {
										// ===================== DEPRECATED VERSION 1 ================= START
										// if($cus_value['summary_pickup_date'] == "0000-00-00") {
										// 	$now = time();
										// } else {
										// 	$now = strtotime($cus_value['summary_pickup_date']);
										// }
										// $your_date = strtotime($cus_value['pickup_date']);
										// $datediff = $now - $your_date;
										// $rounddatediff = round($datediff / (60 * 60 * 24));
										// $cus_quantity = $rounddatediff;
										// ===================== DEPRECATED VERSION 1 ================= END

										$your_date = strtotime($cus_value['actual_order_date']);
										$your_date_v2 = new DateTime($cus_value['actual_order_date']);
										$new_pickupdate = strtotime(date("Y-m-t", strtotime($cus_value['actual_order_date'])));
										$new_pickupdate_v2 = new DateTime(date("Y-m-t", strtotime($cus_value['actual_order_date'])));
										$temporary_service_date_to = date("Y-m-t", strtotime($draft_statement['service_date_from']));
										$isSummaryPickupDate = false;
										
										if($cus_value['summary_pickup_date'] != "0000-00-00" && ($cus_value['summary_pickup_date'] <= $temporary_service_date_to)) {
											
											if ($cus_value['pickup_discharge_date'] !== '0000-00-00' && $cus_value['pickup_discharge_date'] !== null) {
												$now = strtotime($cus_value['pickup_discharge_date']);
												$now_v2 = new DateTime($cus_value['pickup_discharge_date']);
												$now_v2_other = new DateTime($cus_value['summary_pickup_date']);
											} else {
												$now = strtotime($cus_value['summary_pickup_date']);
												$now_v2 = new DateTime($cus_value['summary_pickup_date']);
												$now_v2_other = new DateTime($cus_value['summary_pickup_date']);
											}
											$isSummaryPickupDate = true;
										} else {
											// $now = time();
											$now = strtotime($draft_statement['service_date_to']);
											$now_v2 = new DateTime($draft_statement['service_date_to']);
											$now_v2_other = new DateTime($draft_statement['service_date_to']);
										}

										// ((date("Y", strtotime($temporary_service_date_to)) > date("Y", $your_date)) && $isSummaryPickupDate == false) || 
										if((((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) < date("m", $your_date))) && $isSummaryPickupDate == false) ) {
											$now = $new_pickupdate;
											$now_v2 = $new_pickupdate_v2;
											// $datediff = $now - $your_date;
											$datediff = $now_v2->diff($your_date_v2)->format('%a');
										} else {
											if((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) == date("m", $your_date))) {
												$temponewdate = $your_date;
												$temponewdate_v2 = $your_date_v2;
												$datediff = $now_v2->diff($temponewdate_v2)->format('%a');
											} else if ((date("m", $now) < date("m", strtotime($temporary_service_date_to)) && date("Y", $now) == date("Y", strtotime($temporary_service_date_to))) || (date("Y", $now) < date("Y", strtotime($temporary_service_date_to)))) {
												$temponewdate = strtotime($draft_statement['service_date_from']);
												$temponewdate_v2 = new DateTime($draft_statement['service_date_from']);
												$datediff = $now_v2_other->diff($temponewdate_v2)->format('%a') + 1;
											} else {
												$temponewdate = strtotime($draft_statement['service_date_from']);
												$temponewdate_v2 = new DateTime($draft_statement['service_date_from']);
												$datediff = $now_v2->diff($temponewdate_v2)->format('%a') + 1;
											}
											// $temponewdate = strtotime(date("Y-m-01", time()));
											// $datediff = $now - $temponewdate;
											
										}

										// $datediff = $now - $your_date;
										
										// $rounddatediff = round($datediff / (60 * 60 * 24)) + 1;
										$rounddatediff = $datediff;

										// if ($isSummaryPickupDate == false) {
										// 	$rounddatediff--;
										// }

										if ($rounddatediff == 0) {
											$rounddatediff = 1;
										}

										if($cus_value['equipmentID'] != 49 && $cus_value['equipmentID'] != 64 && $cus_value['equipmentID'] != 32 && $cus_value['equipmentID'] != 29 && $cus_value['daily_rate'] != 344 && $cus_value['equipmentID'] == 343) {
											if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
												$rounddatediff = 1;
											}
										}
										
										if($cus_value['equipmentID'] == 176) {
											$rounddatediff = 1;
										}

										$cus_quantity = $rounddatediff;

										$dailratetemporary = 0;
										//New Calculation 11/15/19 =======> START
										if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
											// $dailratetemporary = $cus_value['monthly_rate'];
											$cus_total = $cus_value['monthly_rate'];
											$cus_noncap = $cus_value['monthly_rate'];
											$cus_quantity = 1;
											if ($cus_value['activity_reference'] == 3 && $cus_value['uniqueID_reference'] != 0) {
												$cus_total = 0;
												$non_cap += $cus_total;
											}
										} else {
											$temptotaldailyrate = $rounddatediff * $cus_value['daily_rate'];
											if($temptotaldailyrate > $cus_value['monthly_rate']) {
												if($cus_value['monthly_rate'] == 0 || $cus_value['monthly_rate'] == null) {
													$cus_total = $temptotaldailyrate;
													$cus_noncap = $cus_value['daily_rate'];
												} else {
													$cus_total = $cus_value['monthly_rate'];
													$cus_noncap = $cus_value['monthly_rate'];
													$cus_quantity = 1;
													if ($cus_value['activity_reference'] == 3 && $cus_value['uniqueID_reference'] != 0) {
														$cus_total = 0;
														$non_cap += $cus_total;
													}
												}
												// $dailratetemporary = $cus_value['monthly_rate'];
												// $total += $dailratetemporary;
											} else {
												$cus_total = $temptotaldailyrate;
												$cus_noncap = $cus_value['daily_rate'];
											}
										}
										//New Calculation 11/15/19 =======> END

										// $total += $dailratetemporary*$rounddatediff;

										//Increment values for Information Box
										$non_cap += $cus_total;
										
									} else {
										$cus_quantity = $cus_value['equipment_value'];
									}
									$cus_cap = "";
									if($cus_value['categoryID'] == 1) { 
										$cus_cap = "X";
										$cus_quantity = 1;
									}
									$cus_purchase_item = 0;
									if($cus_value['categoryID'] == 3) {
										$quan = $cus_value['equipment_value'];
										if($cus_value['equipment_quantity'] !== "" && $cus_value['equipment_quantity'] !== null) {
											$quan = $cus_value['equipment_quantity'];
										}

										$cus_quantity = $quan;
										$cus_purchase_item = $cus_value['purchase_price'];
										$cus_total = $quan * $cus_value['purchase_price']; 

										//Increment values for Information Box
										$purchase_price += $cus_total;
									}
									
									// $cus_noncap = 0;
									// if($cus_value['categoryID'] == 2) { 
									// 	$cus_noncap = $cus_value['purchase_price'];
									// 	$cus_total = $cus_quantity * $cus_value['purchase_price'];
									// }
									$disp_sum_pick_date = '0000-00-00';
									
									$tempo_service_date_to = date("Y-m-t", strtotime($draft_statement['service_date_from']));
									if ($cus_value['summary_pickup_date'] <= $tempo_service_date_to) {
										$disp_sum_pick_date = $cus_value['summary_pickup_date'];
									}

									$statement_order_data = array (
										"acct_statement_invoice_id"	=> $invoice_statement,
										"patientID"					=> $data_value['patientID'],
										"uniqueID"					=> $cus_value['uniqueID'],
										"key_desc"					=> $summary['item_description_data'],
										"delivery_date"				=> $cus_value['actual_order_date'],
										"pickup_date"				=> $disp_sum_pick_date,
										"quantity"					=> $cus_quantity,
										"cap"						=> $cus_cap,
										"purchase_item"				=> $cus_purchase_item,
										"non_cap"					=> $cus_noncap,
										"total"						=> $cus_total,
										"cus_days"					=> $data_value['patient_days'],
										"daily_rate"				=> $hospice_data['daily_rate'],
										"addressID"					=> $cus_value['addressID'],
										"original_activity_typeid"	=> $cus_value['original_activity_typeid']
									);
									$this->billing_statement_model->insert_statement_order($statement_order_data);
								}
							} // End of Insert Statement Order

							// Calculated Cus Days and Update other fields
							if ($draft_statement['customer_days'] != '' && $draft_statement['customer_days'] !== null) {
								if ($hospice_data['track_census'] == 0) {
									$customerdaysfinal = $cusdayslooptotal;
								} else {
									$customerdaysfinal = $draft_statement['customer_days'];
								}
							} else {
								$customerdaysfinal = $cusdayslooptotal;
							}

							
							$calculatedtotal = $customerdaysfinal * $hospice_data['daily_rate'];
							$new_array_data = array(
								"customer_days"		=> $customerdaysfinal,
								"total"				=> $calculatedtotal,
								"non_cap"			=> $non_cap,
								"purchase_item"		=> $purchase_price,

							);
							
							$this->billing_statement_model->update_statement_bill_invoice($invoice_statement, $new_array_data);
						}
					} else {
						$this->response_code = 1;
			        	$this->response_message = 'Invoice Statement already created.';
					}
					
				}
			}
			$this->customer_days_message = $customerdaysmessage;
		}
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
            'customer_days_message' => $this->customer_days_message
        ));
		exit;
	}

	// function convert_to_pdf($data=array()){
	// 	$top = $this->templating_library->set_view('common/head','common/head', $data, TRUE);
	// 	$header = $this->templating_library->set_view('common/header','common/header', $data , TRUE);
	// 	$content = $this->templating_library->set_view('pages/statement_activity_details','pages/statement_activity_details', $data, TRUE);
	// 	$footer  =	$this->templating_library->set_view('common/footer','common/footer', $data ,TRUE);
	// 	$js_files =  $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
	// 	$bottom = $this->templating_library->set_view('common/foot','common/foot', $data ,TRUE);	

	// 	$final_html = $top.$content.$bottom;
	// 	// print_me($final_html);

	// 	$filename = "test.pdf";
		
	// 	// $html = $this->load->view('pages/statement_activity_details','pages/statement_activity_details',$data,true);
	// 	$html = "<h1>testing pdf converter</h1>";
		
	// 	// unpaid_voucher is unpaid_voucher.php file in view directory and $data variable has information that you want to render on view.
		
	// 	// print_me('work');
	// 	$this->load->library('pdf');
	// 	print_me('work home');
	// 	$this->pdf->loadHtml($html);
	// 	$this->pdf->render();
	// 	// $this->pdf->stream($filename, array("Attachment"=>0));
	// 	// $outp =$this->pdf->Output();
	// 	file_put_contents(base_url().'assets/invoices/'.$filename, $this->pdf->output());
	// 	print_me($this->pdf->output());
	// 	return $filename;
	// }

	function insert_invoice_email_log($invoice_id) {
		$invoice_email_logs = array(
			"acct_statement_invoice_id" => $invoice_id,
			"user_id" => $this->session->userdata('userID')
		);
		$this->billing_statement_model->insert_invoice_statement_logs($invoice_email_logs);
	}

	function upload_pdf($hospiceID, $invoice_id) {
		$mimeinfo = pathinfo($_FILES['pdf']['tmp_name']);
		if ($mimeinfo['extension'] != 'php' && $mimeinfo['extension'] != 'exe' && $mimeinfo['extension'] != 'dll') {
			if ($_FILES['pdf']['type'] == 'application/pdf') {
				$is_uploaded = move_uploaded_file(
					$_FILES['pdf']['tmp_name'], 
					$_SERVER['DOCUMENT_ROOT'] . "/assets/invoices/invoice.pdf"
				);
				if($is_uploaded) {
					$hospice_details = $this->billing_statement_model->get_hospice_details($hospiceID);
					$billing_email = explode(",", $hospice_details['billing_email']);
					$billing_email_cc = explode(",", $hospice_details['billing_email_cc']);
		
					$bill_email = '';
					$counter_email = 0;
					foreach($billing_email as $value) {
						if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
							if ($counter_email == 0) {
								$bill_email = $value;
							} else {
								$bill_email = $bill_email.','.$value;
							}
						}
						$counter_email++;
					}
					
					$bill_email_cc = '';
					$counter_email_cc = 0;
					foreach($billing_email_cc as $value) {
						if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
							if ($counter_email_cc == 0) {
								$bill_email_cc = $value;
							} else {
								$bill_email_cc = $bill_email_cc.','.$value;
							}
						}
						$counter_email_cc++;
					}
					
					if ($bill_email != '') {
						$bill_email_cc = $bill_email_cc.',jmauga@ahmslv.com,orders@ahmslv.com';
						// searesrusseljhon@gmail.com
						$email_form = $this->format_email_invoice();
						$this->load->config('email');
						$config =   $this->config->item('billing_email'); 
						$this->load->library('email', $config);
		
						$this->email->from('billing@ahmslv.com','');
						$this->email->to($billing_email);
						// $this->email->to('adrianrafaellucero9@gmail.com,searesrusseljhon@gmail.com');
						$this->email->cc($bill_email_cc);
						// $this->email->cc('rchin@ahmslv.com');
						$this->email->subject('Billing at Advantage Home Medical Services, Inc.');
						$this->email->message($email_form);
						$this->email->attach($_SERVER["DOCUMENT_ROOT"].'/assets/invoices/invoice.pdf');
						// $this->email->attach($_SERVER["DOCUMENT_ROOT"].'/assets/invoices/'.$filename);
						// $this->email->send();
						$email_message = '';
						if ($this->email->send()) {
							$email_message = 'Successfuly sent invoice.';
							
							$data_ill_invoice = array(
								"email_sent_date"	=> date('Y-m-d'),
								"invoice_status"	=> 'email'
							);
							$this->billing_statement_model->update_statement_bill_invoice($invoice_id, $data_ill_invoice);
						} else {
							$email_message = 'Failed to send invoice.';
						}
		
						$this->response_code = 0;
						$this->response_message = 'Successfuly uploaded invoice.';
					} else {
						$this->response_code = 1;
						$this->response_message = 'Invoice was not sent. Please check the billing email for this hospice.';
					}
					
				} else {
					$this->response_code = 1;
					$this->response_message = 'Failed to upload invoice.';
				}
				// if(!empty($_POST['data'])){
				// 	$data = $_POST['data'];
				// 	$fname = "test2.pdf"; // name the file
				// 	$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/assets/invoices/" .$fname, 'w'); // open the file path
				// 	fwrite($file, $data); //save data
				// 	fclose($file);
				// 	// file_put_contents($_SERVER["DOCUMENT_ROOT"].'/assets/invoices/'.$fname, $data);
		
				// 	$this->response_code = 0;
				// 	$this->response_message = 'Successfuly uploaded invoice.';
				// } else {
				// 	$this->response_code = 1;
				// 	$this->response_message = 'Failed to upload invoice.';
				// }
			}
	
			echo json_encode(array(
				'error' => $this->response_code,
				'message' => $this->response_message,
				'blob' => $_FILES['pdf']['tmp_name'],
				'email_message' => $email_message
			));
		}
	}

	function upload_statement_letter_pdf($hospiceID) {
		$mimeinfo = pathinfo($_FILES['pdf']['tmp_name']);
		if ($mimeinfo['extension'] != 'php' && $mimeinfo['extension'] != 'exe' && $mimeinfo['extension'] != 'dll') {
			if ($_FILES['pdf']['type'] == 'application/pdf') {
				$is_uploaded = move_uploaded_file(
					$_FILES['pdf']['tmp_name'], 
					$_SERVER['DOCUMENT_ROOT'] . "/assets/statement_letters/statement_letter.pdf"
				);
				if($is_uploaded) {
					$hospice_details = $this->billing_statement_model->get_hospice_details($hospiceID);
					$billing_email = explode(",", $hospice_details['billing_email']);
					$billing_email_cc = explode(",", $hospice_details['billing_email_cc']);
		
					$bill_email = '';
					$counter_email = 0;
					foreach($billing_email as $value) {
						if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
							if ($counter_email == 0) {
								$bill_email = $value;
							} else {
								$bill_email = $bill_email.','.$value;
							}
						}
						$counter_email++;
					}
					
					$bill_email_cc = '';
					$counter_email_cc = 0;
					foreach($billing_email_cc as $value) {
						if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
							if ($counter_email_cc == 0) {
								$bill_email_cc = $value;
							} else {
								$bill_email_cc = $bill_email_cc.','.$value;
							}
						}
						$counter_email_cc++;
					}
					
					if ($bill_email != '') {
						$bill_email_cc = $bill_email_cc.',jmauga@ahmslv.com,orders@ahmslv.com';
						// searesrusseljhon@gmail.com
						$email_form = $this->format_email_invoice();
						$this->load->config('email');
						$config =   $this->config->item('billing_email'); 
						$this->load->library('email', $config);
		
						$this->email->from('billing@ahmslv.com','');
						$this->email->to($billing_email);
						// $this->email->to('adrianrafaellucero9@gmail.com,searesrusseljhon@gmail.com');
						// ,searesrusseljhon@gmail.com,jmauga@ahmslv.com,orders@ahmslv.com
						$this->email->cc($bill_email_cc);
						// $this->email->cc('rchin@ahmslv.com');
						$this->email->subject('Billing at Advantage Home Medical Services, Inc.');
						$this->email->message($email_form);
						$this->email->attach($_SERVER["DOCUMENT_ROOT"].'/assets/statement_letters/statement_letter.pdf');
						// $this->email->attach($_SERVER["DOCUMENT_ROOT"].'/assets/invoices/'.$filename);
						// $this->email->send();
						$email_message = '';
						if ($this->email->send()) {
							$email_message = 'Successfuly sent statement letter.';
		
							// Save Statement Letter Sent Date
							$data_letter_email = array(
								"hospiceID"	=> $hospiceID,
								"userID"	=>  $this->session->userdata('userID')
							);
							$this->billing_reconciliation_model->insert_statement_letter_email($data_letter_email);
		
							// Create Statement Letter Note
							$username = $this->session->userdata('lastname').' '.$this->session->userdata('firstname');
							$data_letter_note = array(
								'note'	=> 'Statement Letter Sent Via Email',
								'hospiceID'	=> $hospiceID,
								'userID'	=> $this->session->userdata('userID'),
								'userName'	=> $username
							);
							$this->billing_reconciliation_model->insert_statement_letter_note($data_letter_note);
						} else {
							$email_message = 'Failed to send invoice.';
						}
		
						$this->response_code = 0;
						$this->response_message = 'Successfuly uploaded invoice.';
					} else {
						$this->response_code = 1;
						$this->response_message = 'Statement Letter was not sent. Please check the billing email for this hospice.';
					}
					
				} else {
					$this->response_code = 1;
					$this->response_message = 'Failed to upload invoice.';
				}
				// if(!empty($_POST['data'])){
				// 	$data = $_POST['data'];
				// 	$fname = "test2.pdf"; // name the file
				// 	$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/assets/invoices/" .$fname, 'w'); // open the file path
				// 	fwrite($file, $data); //save data
				// 	fclose($file);
				// 	// file_put_contents($_SERVER["DOCUMENT_ROOT"].'/assets/invoices/'.$fname, $data);
		
				// 	$this->response_code = 0;
				// 	$this->response_message = 'Successfuly uploaded invoice.';
				// } else {
				// 	$this->response_code = 1;
				// 	$this->response_message = 'Failed to upload invoice.';
				// }
			} else {
				$this->response_code = 1;
				$this->response_message = 'Invalide file type.';
			}
			echo json_encode(array(
				'error' => $this->response_code,
				'message' => $this->response_message,
				'blob' => $_FILES['pdf']['tmp_name'],
				'email_message' => $email_message
			));
		}
	}

	// function upload_pdf_v2() {
	// 	if(!empty($_POST['data'])) {
	// 		$data = $_POST['data'];
	// 		$fname = "test.pdf"; // name the file
	// 		$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/assets/invoices/" .$fname, 'w'); // open the file path
	// 		fwrite($file, $data); //save data
	// 		fclose($file);

	// 		$this->response_code = 0;
	// 		$this->response_message = 'Successfuly uploaded invoice.';
	// 	} else {
	// 		$this->response_code = 1;
	// 		$this->response_message = 'Failed to upload invoice.';
	// 	}

	// 	echo json_encode(array(
	// 		'error' => $this->response_code,
	// 		'message' => $this->response_message,
	// 		'data' =>$_POST['data']
	// 	));
	// }

	// public function send_invoice_via_email_v2 () {
	// 	$selected_invoice_id_exploded = explode("-",$selected_invoice_ids);
	// 	$account_type_name = $this->session->userdata('account_type');
	// 	$no_email = [];
	// 	$this->response_code = 1;
	// 	$this->response_message = 'Failed sending invoice via email.';
						
	// 	$this->email->from('russel@smartstart.us','');
	// 	$this->email->to('adrianrafaellucero9@gmail.com');
	// 	$this->email->cc('rchin@ahmslv.com');
	// 	$this->email->subject('Billing at Advantage Home Medical Services, Inc.');
	// 	$this->email->message($email_form);
	// 	$this->email->attach($_SERVER["DOCUMENT_ROOT"].'/assets/img/oxygen_tank.png');
	// 	// $this->email->attach($_SERVER["DOCUMENT_ROOT"].'/assets/invoices/'.$filename);
	// 	$this->email->send();

			
	// 	}

	// 	$this->response_code = 0;
	// 	$this->response_message = 'Successfuly sent invoice via email.';

	// 	echo json_encode(array(
	// 		'error' => $this->response_code,
	// 		'message' => $this->response_message,
	// 		'no_email' => $no_email
	// 	));
	// 	exit;
	// }

	// public function send_invoice_via_email ($selected_invoice_ids) {
	// 	$selected_invoice_id_exploded = explode("-",$selected_invoice_ids);
	// 	$account_type_name = $this->session->userdata('account_type');
	// 	$no_email = [];
	// 	$this->response_code = 1;
	// 	$this->response_message = 'Failed sending invoice via email.';
						
	// 	foreach($selected_invoice_id_exploded as $invoice_id) {
	// 		if($account_type_name == "dme_admin")
	// 		{	
	// 			$invoice_statement = $this->billing_statement_model->get_statement_activity_details($invoice_id);

	// 			$data['account'] = $this->billing_statement_model->get_account($invoice_statement[0]['hospiceID']);
	// 			$data['customer_days'] = $this->billing_statement_model->get_customer_days($invoice_statement[0]['hospiceID']);
	// 			$data['invoice_details'] = $this->billing_statement_model->get_statement_activity_details($invoice_id);
	// 			$data['order_summary'] = $this->billing_statement_model->get_all_orderSummary_statement_activity($invoice_id, $invoice_statement[0]['hospiceID'], 0, -1);
	// 			// print_me($_SERVER["DOCUMENT_ROOT"].'/assets/invoices/');
	// 			// print_me(base_url().'assets/invoices');
	// 			// print_me(dirname(__FILE__));
	// 			// print_me($invoice_statement);
	// 			// $filename = $this->convert_to_pdf($data);
	// 			// print_me($filename);
				
	// 			$email_form = $this->format_email_invoice();
	// 			$this->load->config('email');
	// 			$config =   $this->config->item('me_email');
	// 			$this->load->library('email', $config);

	// 			$this->email->from('russel@smartstart.us','');
	// 			$this->email->to('adrianrafaellucero9@gmail.com');
	// 			$this->email->cc('searesrusseljhon@gmail.com');
	// 			$this->email->cc('rchin@ahmslv.com');
	// 			$this->email->cc('jmauga@ahmslv.com');
	// 			$this->email->subject('Billing at Advantage Home Medical Services, Inc.');
	// 			$this->email->message($email_form);
	// 			$this->email->attach($_SERVER["DOCUMENT_ROOT"].'/assets/img/oxygen_tank.png');
	// 			// $this->email->attach($_SERVER["DOCUMENT_ROOT"].'/assets/invoices/'.$filename);
	// 			$this->email->send();

	// 			if ($invoice_statement[0]['billing_email'] === null || $invoice_statement[0]['billing_email'] === '') {
	// 				$no_email[] = $invoice_statement[0];
	// 			} else {
	// 				$new_array_data = array(
	// 					"email_sent_date"	=> date('Y-m-d')
	// 				);
					
	// 				$this->billing_statement_model->update_statement_bill_invoice($invoice_statement[0]['acct_statement_invoice_id'], $new_array_data);
	// 			}

				
	// 		}

			
	// 	}

	// 	$this->response_code = 0;
	// 	$this->response_message = 'Successfuly sent invoice via email.';

	// 	echo json_encode(array(
	// 		'error' => $this->response_code,
	// 		'message' => $this->response_message,
	// 		'no_email' => $no_email
	// 	));
	// 	exit;
	// }

	public function format_email_invoice()
	{
		$disclaimer_text = "DISCLAIMER: The contents of this e-mail message and any attachments are confidential and are intended solely for addressee. The information may also be legally privileged. This transmission is sent in trust, for the sole purpose of delivery to the intended recipient. If you have received this transmission in error, any use, reproduction or dissemination of this transmission is strictly prohibited. If you are not the intended recipient, please immediately notify the sender by reply e-mail or phone and delete this message and its attachments, if any."; 
		return $disclaimer_text;
	}


	public function statement_activity_details($acct_statement_invoice_id, $hospice_id) {
		$data['account'] = $this->billing_statement_model->get_account($hospice_id);
		$data['customer_days'] = $this->billing_statement_model->get_customer_days($hospice_id);
		$data['invoice_details'] = $this->billing_statement_model->get_statement_activity_details($acct_statement_invoice_id);
		$data['order_summary'] = $this->billing_statement_model->get_all_orderSummary_statement_activity($acct_statement_invoice_id, $hospice_id, 0, -1);
		
		// $data['order_summary'] = Array(
		// 	"cus_orders"	=> $order_summary,
		// 	"limit"			=> $data['customers']['limit'],
		// 	"start"			=> $data['customers']['start'],
		// 	"totalCount"	=> $data['customers']['totalCount'],
		// 	"totalCustomerCount"	=> $data['customers']['totalCustomerCount']
		// );
		// print_me($data['order_summary']);
		$this->templating_library->set('title','Account Statement Activity Details');
		$this->templating_library->set_view('pages/statement_activity_details','pages/statement_activity_details', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
	}

	public function iframe_statement_activity_details($acct_statement_invoice_id, $hospice_id) {
		$data['account'] = $this->billing_statement_model->get_account($hospice_id);
		$data['customer_days'] = $this->billing_statement_model->get_customer_days($hospice_id);
		$data['invoice_details'] = $this->billing_statement_model->get_statement_activity_details($acct_statement_invoice_id);
		$data['order_summary'] = $this->billing_statement_model->get_all_orderSummary_statement_activity($acct_statement_invoice_id, $hospice_id, 0, -1);
		$data['invoice_reconciliation'] = $this->billing_reconciliation_model->get_invoice_reconciliation_balance_and_owe($acct_statement_invoice_id);
		$data['subtotal_amount'] = $data['invoice_details'][0]['total'] + $data['invoice_details'][0]['non_cap'] + $data['invoice_details'][0]['purchase_item'];
		$data['payment_due_amount'] = ($data['subtotal_amount'] - $data['invoice_reconciliation']['credit']) + $data['invoice_reconciliation']['owe'];
		// $data['order_summary'] = Array(
		// 	"cus_orders"	=> $order_summary,
		// 	"limit"			=> $data['customers']['limit'],
		// 	"start"			=> $data['customers']['start'],
		// 	"totalCount"	=> $data['customers']['totalCount'],
		// 	"totalCustomerCount"	=> $data['customers']['totalCustomerCount']
		// );
		// print_me($data['order_summary']);
		$this->templating_library->set('title','Account Statement Activity Details');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('pages/statement_activity_details_email','pages/statement_activity_details_email', $data);
		$this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
		$this->templating_library->set_view('common/foot','common/foot');

		// $this->templating_library->set('title','View All Accounts');
		// $this->templating_library->set_view('common/head','common/head');
		// $this->templating_library->set_view('common/header','common/header');
		// $this->templating_library->set_view('common/nav','common/nav', $data);
		// $this->templating_library->set_view('pages/searched_account_grid','pages/searched_account_grid', $data);
		// $this->templating_library->set_view('common/footer','common/footer');
		// $this->templating_library->set_view('common/foot','common/foot');
	}

	public function load_more_orderSummary_statement_activity($acct_statement_invoice_id, $hospice_id, $start=0, $limit=50) {
		
		if($this->input->is_ajax_request())
		{
			$response = $this->billing_statement_model->get_all_orderSummary_statement_activity($start, $limit);
			// print_me($order_summary);
		}
		
		// print_me($response);
		echo json_encode($response);

	}

	public function return_to_draft_statement_activity($acct_statement_invoice_ids){
		$selected_acct_statement_invoice_id_exploded = explode("-",$acct_statement_invoice_ids);
		foreach($selected_acct_statement_invoice_id_exploded as $acct_statement_invoice_id) {
			$statement_activity = $this->billing_statement_model->get_statement_activity_details($acct_statement_invoice_id);
			$data = array(
				"hospiceID" 		=> $statement_activity[0]['hospiceID'],
				"statement_no"		=> $statement_activity[0]['statement_no'],
				"service_date_from"	=> $statement_activity[0]['service_date_from'],
				"service_date_to"	=> $statement_activity[0]['service_date_to'],
				"notes"				=> "",
				"is_manual"			=> 0
			);

			$statement_draft = $this->billing_statement_model->insert_statement_draft($data);

			if($statement_draft) {
				$this->billing_statement_model->remove_statement_activity($acct_statement_invoice_id);
				$this->billing_statement_model->remove_statement_activity_orderSummary($acct_statement_invoice_id);
				$this->response_code = 0;
	            $this->response_message = 'Statement Activity successfully returned to draft.';
			} 
		}
		
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function return_to_draft_statement_invoice_inquiry($acct_statement_invoice_id){
		$statement_activity = $this->billing_statement_model->get_statement_activity_details($acct_statement_invoice_id);
		$data = array(
			"hospiceID" 		=> $statement_activity[0]['hospiceID'],
			"statement_no"		=> $statement_activity[0]['statement_no'],
			"service_date_from"	=> $statement_activity[0]['service_date_from'],
			"service_date_to"	=> $statement_activity[0]['service_date_to'],
			"notes"				=> "",
			"is_manual"			=> 0
		);

		$statement_draft = $this->billing_statement_model->insert_statement_draft($data);

		if($statement_draft) {
			$this->billing_statement_model->remove_statement_activity($acct_statement_invoice_id);
			$this->billing_statement_model->remove_statement_activity_orderSummary($acct_statement_invoice_id);

			// Remove invoice reference on reconciliation table
			$data_reconcile = array(
				"invoice_reference" => "0"
			);
			$this->billing_statement_model->remove_invoice_reference($statement_activity[0]['statement_no'], $data_reconcile);

			$this->response_code = 0;
            $this->response_message = 'Statement Activity successfully returned to draft.';
		} 
		
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function statement_letter() {
		// $data['statement_letters'] = $this->billing_statement_model->get_all_statement_letter($this->session->userdata('user_location'));

		$data['statement_letters'] = $this->billing_statement_model->get_all_statement_letter_list($this->session->userdata('user_location'));

		foreach($data['statement_letters'] as $value) {
			$data['statement_letters_note_count'][] = $this->billing_reconciliation_model->count_statement_letter_notes($value['hospiceID']);
			$data['statement_letters_email'][] = $this->billing_reconciliation_model->get_statement_letter_email($value['hospiceID']);
		}

		$this->templating_library->set('title','Account Statement Letters');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$this->templating_library->set_view('pages/statement_letter','pages/statement_letter', $data);
		}
		
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function statement_letter_details($hospiceID) {
		// $data['statement_letter'] = $this->billing_statement_model->get_statement_letter_details($acct_statement_letter_id);

		$data['past_due_invoices'] = $this->billing_statement_model->get_all_statement_letter_list($this->session->userdata('user_location'), $hospiceID);
		$data['current_date'] = date("Y-m-d");

		$temp = $data['past_due_invoices'];
		foreach($data['past_due_invoices'] as $key => $value) {
			$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['statement_no']);
			$data['invoices_reconciliation'][] = array(
				"credit"	=> !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'],
				"owe"		=> !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe']
			);
			$disable_draft = 1;
			if($key > 0) {
				if($temp[$key-1]['hospiceID'] == $value['hospiceID']) {
					$disable_draft = 0;
				}
			}
			$data['is_disabled_invoice_cancel'][] = $disable_draft;
		}

		$this->templating_library->set('title','Account Statement Letter Details');
		$this->templating_library->set_view('pages/statement_letter_details','pages/statement_letter_details', $data);
		$this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
	}

	public function iframe_statement_letter_details($hospiceID) {
		// $data['statement_letter'] = $this->billing_statement_model->get_statement_letter_details($acct_statement_letter_id);

		$data['past_due_invoices'] = $this->billing_statement_model->get_all_statement_letter_list($this->session->userdata('user_location'), $hospiceID);
		$data['current_date'] = date("Y-m-d");

		$temp = $data['past_due_invoices'];
		foreach($data['past_due_invoices'] as $key => $value) {
			$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['statement_no']);
			$data['invoices_reconciliation'][] = array(
				"credit"	=> !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'],
				"owe"		=> !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe']
			);
			$disable_draft = 1;
			if($key > 0) {
				if($temp[$key-1]['hospiceID'] == $value['hospiceID']) {
					$disable_draft = 0;
				}
			}
			$data['is_disabled_invoice_cancel'][] = $disable_draft;
		}
		
		$current = $this->billing_statement_model->get_past_due_amount_v2($hospiceID, 'current');
		$past_due_1_30 = $this->billing_statement_model->get_past_due_amount_v2($hospiceID, '30');
		$past_due_31_60 = $this->billing_statement_model->get_past_due_amount_v2($hospiceID, '60');
		$past_due_61_90 = $this->billing_statement_model->get_past_due_amount_v2($hospiceID, '90');
		$past_due_91 = $this->billing_statement_model->get_past_due_amount_v2($hospiceID, '91');
		$data['last_payment'] = $this->billing_statement_model->get_last_payment_by_current_v2($hospiceID);
		
		$data['current'] = $current['totaltotal'] +  $current['totalnoncap'] +  $current['totalpurchaseitem'];
		$data['past_due_1_30'] = $past_due_1_30['totaltotal'] +  $past_due_1_30['totalnoncap'] +  $past_due_1_30['totalpurchaseitem'];
		$data['past_due_31_60'] = $past_due_31_60['totaltotal'] +  $past_due_31_60['totalnoncap'] +  $past_due_31_60['totalpurchaseitem'];
		$data['past_due_61_90'] = $past_due_61_90['totaltotal'] +  $past_due_61_90['totalnoncap'] +  $past_due_61_90['totalpurchaseitem'];
		$data['past_due_91'] = $past_due_91['totaltotal'] +  $past_due_91['totalnoncap'] +  $past_due_91['totalpurchaseitem'];
		$data['balance_due'] = $data['current'] + $data['past_due_1_30'] + $data['past_due_31_60'] + $data['past_due_61_90'] + $data['past_due_91'];


		$this->templating_library->set('title','Account Statement Letter Details');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('pages/statement_letter_details_email','pages/statement_letter_details_email', $data);
		$this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function create_statement_letter($acct_statement_invoice_ids){
		$selected_acct_statement_invoice_id_exploded = explode("-",$acct_statement_invoice_ids);
		foreach($selected_acct_statement_invoice_id_exploded as $acct_statement_invoice_id) {
			$statement_activity = $this->billing_statement_model->get_statement_activity_details($acct_statement_invoice_id);
			$total_balance_due = $statement_activity[0]['total'] + $statement_activity[0]['non_cap'] + $statement_activity[0]['purchase_item'] + $statement_activity[0]['credit'] + $statement_activity[0]['balance_owe'];
			$data = array(
				"hospiceID" 	=> $statement_activity[0]['hospiceID'],
				"invoice_no"	=> $statement_activity[0]['statement_no'], //change to invoice_no
				"invoice_date"	=> $statement_activity[0]['invoice_date'],
				"charges"		=> $total_balance_due,
				"credit"		=> $statement_activity[0]['credit'],
				"balance"		=> $statement_activity[0]['balance_owe'],
				"current_days"	=> 0,
				"past_due_1_days"	=> 0,
				"past_due_31_days"	=> 0,
				"past_due_61_days"	=> 0,
				"past_due_91_days"	=> 0,
				"balance_due"	=> 0
			);

			$statement_letter = $this->billing_statement_model->insert_statement_letter($data);

			if($statement_letter) {
				// $this->billing_statement_model->remove_statement_activity($acct_statement_invoice_id);
				// $this->billing_statement_model->remove_statement_activity_orderSummary($acct_statement_invoice_id);
				$this->response_code = 0;
	            $this->response_message = 'Created Statement Letter Successfully.';
			} 
		}
		
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}
	
	public function delete_statement_letter($acct_statement_letter_id, $hospiceID) {

		$return = $this->billing_statement_model->delete_statement_letter($acct_statement_letter_id, $hospiceID);

		if($return) {
			$this->response_code = 0;
			$this->response_message = 'Statement letter successfully deleted.';
		} 
		
		echo json_encode(array(
			'error' => $this->response_code,
			'message' => $this->response_message,
		));
		exit;
	}

	public function statement_reconciliation_details($acct_statement_letter_id) {
		$this->templating_library->set('title','Account Reconcilition Details');
		$this->templating_library->set_view('pages/statement_reconciliation_details','pages/statement_reconciliation_details', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
	}

	public function statement_invoice_inquiry() {
		$data['statement_invoice_inquiry'] = $this->billing_statement_model->get_all_statement_activity($this->session->userdata('user_location'));
		$data['invoices_reconciliation'] = array();
		$data['is_disabled_invoice_cancel'] = array();

		$temp = $data['statement_invoice_inquiry'];
		foreach($data['statement_invoice_inquiry'] as $key => $value) {
			$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['statement_no']);
			$data['invoices_reconciliation'][] = array(
				"credit"	=> !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'],
				"owe"		=> !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe']
			);
			$disable_draft = 1;
			if($key > 0) {
				if($temp[$key-1]['hospiceID'] == $value['hospiceID']) {
					$disable_draft = 0;
				} 
				// else {
				// 	$data['statement_draft'][$key-1]['is_disable'] = 0;
				// }
			}
			$data['is_disabled_invoice_cancel'][] = $disable_draft;
		}
		
		$this->templating_library->set('title','Invoice Inquiry');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/statement_invoice_inquiry','pages/statement_invoice_inquiry', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function get_total_payment_amount_invoice($invoice_status, $filter_from, $filter_to, $is_collection, $hospiceID=null) {
		if($this->input->is_ajax_request())
		{
			$filter_to = ($filter_to==0 || $filter_to=="")? $filter_from." 23:59:59" : $filter_to." 23:59:59";
			$invoices = get_total_payment_amount_invoice_list($filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$invoice_status,$is_collection);

			$total_payment_amount['total_payment_amount'] = 0;
			$temp = $invoices;
			foreach($invoices as $key => $value) {
				$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['statement_no']);

				$total_payment_amount['total_payment_amount'] += $value['total'];
				$total_payment_amount['total_payment_amount'] += $value['non_cap'];
				$total_payment_amount['total_payment_amount'] += $value['purchase_item'];
				$total_payment_amount['total_payment_amount'] -= $invoice_reconciliation['credit'];
				$total_payment_amount['total_payment_amount'] += $invoice_reconciliation['owe'];
			}
			echo json_encode($total_payment_amount);
		}
		exit;
	}

	public function get_status_total_count_invoice($filter_from, $filter_to, $hospiceID=null) {
		if($this->input->is_ajax_request())
		{
			$filter_to = ($filter_to==0 || $filter_to=="")? $filter_from." 23:59:59" : $filter_to." 23:59:59";
			$data['pending_count'] = get_status_count_amount_invoice_list($filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'), 'pending');
			$data['email_count'] = get_status_count_amount_invoice_list($filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'), 'email');
			$data['us_mail_count'] = get_status_count_amount_invoice_list($filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'), 'us_mail');
			$data['fax_count'] = get_status_count_amount_invoice_list($filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'), 'fax');

			echo json_encode($data);
		}
		exit;
	}

	public function statement_invoice_inquiry_from_to() {
		$data = array();
		$this->templating_library->set('title','Invoice Inquiry');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$this->templating_library->set_view('pages/statement_invoice_inquiry_from_to','pages/statement_invoice_inquiry_from_to', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function load_more_invoice_inquiry($filter_from, $filter_to, $hospiceID=null, $page, $invoice_status = '', $is_colletion=0, $limit=10) {
		if($this->input->is_ajax_request())
		{
			if($limit > 10){
				$limit = 10;
			}
			$offset = ($page - 1) * $limit;
			$results_info = array(
				"total_records" => 0,
				"total_pages"   => 0,
				"current_page"  => $page
		 	);
			$pagination_details = array(
				"offset" => $offset,
				"limit"  => $limit
			);

			if($hospiceID == 0)
	  		{
	  			$hospiceID = null;
	  		}

			$filter_to = ($filter_to==0 || $filter_to=="")? $filter_from." 23:59:59" : $filter_to." 23:59:59";
			$reconcile_list = get_invoice_inquiry_list($filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'), $pagination_details, $invoice_status, $is_colletion);
			$results_info['total_records'] = $reconcile_list['total'];
			$query_result = $reconcile_list['data'];

			//Results
			$results_info['total_records'] = $results_info['total_records']==null? 0 : $results_info['total_records'];
			$total_pages = ($results_info['total_records'] > 0)? ceil($results_info['total_records'] / $limit) : 0;
			$results_info['total_pages'] = $total_pages;
			$data['pagination_details'] = $results_info;
			$data['statement_invoices'] = $query_result;

			$data['invoices_reconciliation'] = array();
			$data['is_disabled_invoice_cancel'] = array();
			$temp = $data['statement_invoices'];
			foreach($data['statement_invoices'] as $key => $value) {
				$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['statement_no']);
				$data['invoices_reconciliation'][] = array(
					"credit"	=> !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'],
					"owe"		=> !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe']
				);
				$disable_draft = 1;
				if($key > 0) {
					if($temp[$key-1]['hospiceID'] == $value['hospiceID']) {
						$disable_draft = 0;
					} 
					// else {
					// 	$data['statement_draft'][$key-1]['is_disable'] = 0;
					// }
				}
				$data['is_disabled_invoice_cancel'][] = $disable_draft;
			}
		}
		echo json_encode($data);
		exit;
		
	}

	public function statement_bill_by_hospice($hospice_id=0) {
		$data['hospice_id'] = $hospice_id;
		$data['hospice_details'] = $this->billing_statement_model->get_hospice_details($hospice_id);
		// $hospice_id = 139;
		$data['statement_invoice_inquiry'] = $this->billing_statement_model->get_past_due_invoice_v2($hospice_id);
		// $data['total_balance_due'] = $this->billing_statement_model->get_total_balance_due_by_hospice($hospice_id);
		
		// $data['last_payment'] = $this->billing_statement_model->get_last_payment_by_hospice($hospice_id); // Old Process
		$data['last_payment'] = $this->billing_statement_model->get_last_payment_by_current_v2($hospice_id); // New Process
		
		// print_me($data['last_payment']);
		$data['pending_payments_count'] = count($this->billing_statement_model->get_pending_payments_by_hospice($hospice_id));
		$data['pending_payments'] = $this->billing_statement_model->get_pending_payments_by_hospice($hospice_id);
		$data['total_pending_payments'] = 0;
		foreach($data['pending_payments'] as $value) {
			$data['total_pending_payment'] += $value['payment_amount'];
		}
		$all_invoices = $this->billing_statement_model->get_current_invoice_by_hospice($hospice_id);
		$data['total_balance_due_date'] = $all_invoices[count($all_invoices)-1]['due_date'];
		$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($all_invoices[count($all_invoices)-1]['statement_no']);
		$tbd_credit = !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'];
		$tbd_owe = !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe'];
		$data['total_balance_due'] = $all_invoices[count($all_invoices)-1];
		$data['tbd_total'] += $data['total_balance_due']['total'];
		$data['tbd_total'] += $data['total_balance_due']['non_cap'];
		$data['tbd_total'] += $data['total_balance_due']['purchase_item'];
		$data['tbd_total'] -= $tbd_credit;
		$data['tbd_total'] += $tbd_owe;

		$temp_past_due_amount = $this->billing_statement_model->get_past_due_amount($hospice_id, $data['total_balance_due']['acct_statement_invoice_id']);
		$data['past_due_amount'] = $temp_past_due_amount['totaltotal'] + $temp_past_due_amount['totalnoncap'] + $temp_past_due_amount['totalpurchaseitem'];
		// print_me($all_invoices);
		// print_me($data['past_due_amount']);
		$this->templating_library->set('title','Billing');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		if (hasAcccessHospiceBilling($hospice_id)) {
			if ($hospice_id === $this->session->userdata("group_id")) {
				if ($this->session->userdata('account_type') != 'hospice_user') {
					$this->templating_library->set_view('pages/statement_bill_by_hospice','pages/statement_bill_by_hospice', $data);
				}
			}
		}
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function statement_make_payment($hospice_id=0) {
		$data['hospice_id'] = $hospice_id;
		// $hospice_id = 139;

		// <=========> PAST DUE INVOICES OR INVOICES BEFORE THE CURRENT INVOICE (TOTAL BALANCE DUE) <=========>
		// $data['statement_invoice_inquiry'] = $this->billing_statement_model->get_current_invoice_by_hospice($hospice_id);

		// <=======================================> OVERDUE INVOICES <=======================================>
		// $data['statement_invoice_inquiry'] = $this->billing_statement_model->get_past_due_invoice(139); 


		$data['statement_invoice_payment_due'] = $this->billing_statement_model->get_payment_due_invoice($hospice_id);
		// $data['total_balance_due'] = $this->billing_statement_model->get_total_balance_due_by_hospice(139);
		$temp_total_payment_due = $this->billing_statement_model->get_total_payment_due_by_hospice($hospice_id);
		$data['total_payment_due'] = $temp_total_payment_due['totaltotal'] + $temp_total_payment_due['totalnoncap'] + $temp_total_payment_due['totalpurchaseitem'];
		$all_invoices = $this->billing_statement_model->get_current_invoice_by_hospice($hospice_id);
		$data['total_balance_due_date'] = $all_invoices[count($all_invoices)-1]['due_date'];
		$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($all_invoices[count($all_invoices)-1]['statement_no']);
		$tbd_credit = !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'];
		$tbd_owe = !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe'];
		$data['total_balance_due'] = $all_invoices[count($all_invoices)-1];
		$data['tbd_total'] += $data['total_balance_due']['total'];
		$data['tbd_total'] += $data['total_balance_due']['non_cap'];
		$data['tbd_total'] += $data['total_balance_due']['purchase_item'];
		$data['tbd_total'] -= $tbd_credit;
		$data['tbd_total'] += $tbd_owe;
		
		$data['statement_invoice_inquiry_v2'] = $this->billing_statement_model->get_past_due_invoice_v3($hospice_id, $data['total_balance_due']['acct_statement_invoice_id']);
		$temp_past_due_amount = $this->billing_statement_model->get_past_due_amount($hospice_id, $data['total_balance_due']['acct_statement_invoice_id']);
		$data['past_due_amount'] = $temp_past_due_amount['totaltotal'] + $temp_past_due_amount['totalnoncap'] + $temp_past_due_amount['totalpurchaseitem'];
		// print_me($this->billing_statement_model->get_all_invoice_by_hospice(139));
		$this->templating_library->set('title','Make Payment');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/statement_make_payment','pages/statement_make_payment', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function get_past_due_invoices($hospice_id) {
		if($this->input->is_ajax_request())
		{
			if($hospice_id != 0) {
				// $data['statement_invoice_inquiry'] = $this->billing_statement_model->get_past_due_invoice_v2($hospice_id);
				$all_invoices = $this->billing_statement_model->get_current_invoice_by_hospice($hospice_id);
				$total_balance_due = $all_invoices[count($all_invoices)-1];
				$data['statement_invoice_inquiry'] = $this->billing_statement_model->get_past_due_invoice_v4($hospice_id, $total_balance_due['acct_statement_invoice_id']);
				$data['invoices_reconciliation'] = array();
				foreach($data['statement_invoice_inquiry'] as $value) {
					$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['statement_no']);
					$data['invoices_reconciliation'][] = array(
						"credit"	=> !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'],
						"owe"		=> !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe']
					);
				}
			} 
		}
		echo json_encode($data);
	}

	//$acct_statement_invoice_ids, $receive_date, $email
	public function make_payment($acct_statement_invoice_ids, $payment_date, $email){
		$selected_acct_statement_invoice_id_exploded = explode("-",$acct_statement_invoice_ids);
		$year = date("Y");
		$month = date("m");
		$day = date("d");
		$hours = date("H");
		$minutes = date("i");
		$seconds = date("s");
		$date_today = "";
		$date_today = $year.$month.$day.$hours.$minutes.$seconds;
		$email = str_replace('.-arobase-.', '@', $email);
		$payment_date = $payment_date.' '.$hours.':'.$minutes.':'.$seconds;
		$total_payment_amount = 0;
		foreach($selected_acct_statement_invoice_id_exploded as $acct_statement_invoice_id) {
			$statement_invoice = $this->billing_statement_model->get_statement_activity_details($acct_statement_invoice_id);
			$payment_code = "";
			$payment_code = $statement_invoice[0]['hospiceID'].$date_today;
			$total_balance_amount = $statement_invoice[0]['total'] + $statement_invoice[0]['non_cap'] + $statement_invoice[0]['purchase_item'];
			$data = array(
				"payment_date" 		=> $payment_date,
				"payment_type"		=> "credit_card",
				"payment_amount"	=> number_format((float)$total_balance_amount, 2, '.', ''),
				// "receive_status"	=> 1,
				"payment_code"		=> $payment_code,
				"email"				=> $email
			);
			// print_me($payment_code);
			// print_me($data);

			$make_payment = $this->billing_statement_model->update_statement_bill_invoice($acct_statement_invoice_id, $data);

			if($make_payment) {
				$total_payment_amount += number_format((float)$total_balance_amount, 2, '.', '');
			}
		}
		$this->response_code = 0;
	    $this->response_message = 'Your payment of $'.number_format((float)$total_payment_amount, 2, '.', '').' can take up to 48 hours to process.';
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function make_other_payment_v2($hopiceID, $payment_amount, $payment_date, $email){
		$year = date("Y");
		$month = date("m");
		$day = date("d");
		$hours = date("H");
		$minutes = date("i");
		$seconds = date("s");
		$date_today = "";
		$date_today = $year.$month.$day.$hours.$minutes.$seconds;
		$email = str_replace('.-arobase-.', '@', $email);
		$payment_date = $payment_date.' '.$hours.':'.$minutes.':'.$seconds;
		$payment_invoices = array();

		$temp_payment_amount = $payment_amount;
		$prev_temp_payment_amount = $payment_amount;
		$payment_invoices = $this->billing_statement_model->get_all_invoice_by_hospice($hopiceID);

		if(!empty($payment_invoices)) {
			$count_total_payment_due = count($payment_invoices);
			foreach($payment_invoices as $paid_index => $value){
				$temp_payment_amount = $temp_payment_amount - $value['total'];
				if($temp_payment_amount < 0) {
					$payment_invoice[] = array(
						"acct_statement_invoice_id"	=> $value['acct_statement_invoice_id'],
						"payment_amount" => $prev_temp_payment_amount
					);
					break;
				} else if($temp_payment_amount == 0) {
					$payment_invoice[] = array(
						"acct_statement_invoice_id"	=> $value['acct_statement_invoice_id'],
						"payment_amount" => $value['total']
					);
					break;
				} else {
					$payment_invoice[] = array(
						"acct_statement_invoice_id"	=> $value['acct_statement_invoice_id'],
						"payment_amount" => $value['total']
					);
					
					if($count_total_payment_due - 1 == $paid_index) {
						$payment_invoice[$paid_index]['payment_amount'] += $temp_payment_amount;
					}
					$prev_temp_payment_amount = $temp_payment_amount;
					$paid_index++;
				}
			}
			
			$temp_payment_amount = $payment_amount;
			foreach($payment_invoice as $value){
				if($temp_payment_amount > 0) {
					$payment_code = "";
					$payment_code = $hopiceID.$date_today;
					$data = array(
						"payment_date" 		=> $payment_date,
						"payment_type"		=> "credit_card",
						"payment_amount"	=> $value['payment_amount'],
						// "receive_status"	=> 1,
						"payment_code"		=> $payment_code,
						"email"				=> $email
					);
					// print_me($data);
					$paid_invoice = $this->billing_statement_model->update_statement_bill_invoice($value['acct_statement_invoice_id'], $data);

					if($paid_invoice) {
						$temp_payment_amount = $temp_payment_amount - $value['total'];
					}
				}
			}

			$this->response_code = 0;
	        $this->response_message = 'Your payment of $'.number_format((float)$payment_amount, 2, '.', '').' can take up to 48 hours to process.';
		}

		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function make_other_payment($payment_amount, $payment_date, $email){
		$year = date("Y");
		$month = date("m");
		$day = date("d");
		$hours = date("H");
		$minutes = date("i");
		$seconds = date("s");
		$date_today = "";
		$date_today = $year.$month.$day.$hours.$minutes.$seconds;
		$email = str_replace('.-arobase-.', '@', $email);
		$payment_date = $payment_date.' '.$hours.':'.$minutes.':'.$seconds;
		$payment_invoices = array();

		$temp_payment_amount = $payment_amount;

		$total_payment_due = $this->billing_statement_model->get_all_payment_due_by_hospice(139);
		$total_balance_due = $this->billing_statement_model->get_all_balance_due_by_hospice(139);
		
		$paid_index = -1;
		if(!empty($total_payment_due)) {
			$payment_invoices = $total_payment_due;
		} else {
			$payment_invoices = $total_balance_due;
		}

		if(!empty($payment_invoices)) {
			$count_total_payment_due = count($payment_invoices);
			foreach($payment_invoices as $index => $value){
				$temp_payment_amount = $temp_payment_amount - $value['total'];
				if($temp_payment_amount < 0) {
					$payment_invoices[] = array(
						"acct_statement_invoice_id"	=> $value['acct_statement_invoice_id'],
						"payment_amount" => $value['total']
					);
					$paid_index++;
					if($count_total_payment_due - 1 == $index) {
						$payment_invoices[$paid_index]['payment_amount'] += $temp_payment_amount;
					}
				} else if($temp_payment_amount == 0) {
					$payment_invoices[] = array(
						"acct_statement_invoice_id"	=> $value['acct_statement_invoice_id'],
						"payment_amount" => $value['total']
					);
					break;
				} else {
					$payment_invoices[] = array(
						"acct_statement_invoice_id"	=> $value['acct_statement_invoice_id'],
						"payment_amount" => $value['total']
					);
					$paid_index++;
					if($count_paid_index - 1 <= $total_payment_due) {
						if($temp_payment_amount < $total_payment_due[$index]['total']) {
							$payment_invoices[$paid_index]['payment_amount'] += $temp_payment_amount;
							break;
						}
					}
				}
			}

			foreach($payment_invoice as $key => $value) {
				$data = array(
					"payment_date" 		=> $payment_date,
					"payment_type"		=> "credit_card",
					"payment_amount"	=> $value['payment_amount'],
					// "receive_status"	=> 1,
					"payment_code"		=> $payment_code,
					"email"				=> $email
				);
				$this->billing_statement_model->update_statement_bill_invoice($value['acct_statement_invoice_id'], $data);
			}

			$this->response_code = 0;
	        $this->response_message = 'Payment can take up to 2 days to process.';
		}

		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function update_invoice_status ($invoice_id, $invoice_status = '') {
		if ($invoice_status == 'pending') {
			$data = array(
				"invoice_status" => $invoice_status,
				"email_sent_date" => '0000-00-00'
			);
		} else {
			$data = array(
				"invoice_status" => $invoice_status
			);
		}
		$update = $this->billing_statement_model->update_statement_bill_invoice($invoice_id, $data);

		if ($update) {
			$this->response_code = 0;
	        $this->response_message = 'Updated invoice status succesfully.';
		} else {
			$this->response_code = 1;
	        $this->response_message = 'Update Failed.';
		}

		echo json_encode(array(
			'error' => $this->response_code,
			'message' => $this->response_message,
		));
		exit;
	}

	public function update_invoice_status_v2 () {

		$data_post = $this->input->post();
		$data = array(
			"invoice_status" => $data_post['us_mail_invoice_status'],
			"email_sent_date" => $data_post['us_mail_sent_date']
		);
		$update = $this->billing_statement_model->update_statement_bill_invoice($data_post['us_mail_invoice_id'], $data);
		
		if ($update) {
			$this->response_code = 0;
	        $this->response_message = 'Updated invoice status succesfully.';
		} else {
			$this->response_code = 1;
	        $this->response_message = 'Update Failed.';
		}

		echo json_encode(array(
			'error' => $this->response_code,
			'message' => $this->response_message,
		));
		exit;
	}

	public function update_invoice_sent_date ($invoice_id) {
		if ($_POST) {
			$newValue = $this->input->post("value");

			$data = array(
				"email_sent_date" => $newValue
			);
			$update = $this->billing_statement_model->update_statement_bill_invoice($invoice_id, $data);
		}
	}
	

	// For Customer Days Script Draft Statement
    public function insert_customer_patient_days_script($hospiceID)
    {
		$patients = $this->billing_statement_model->get_all_patients_for_script($hospiceID);
		// print_me($patients); 
        $list_active_patients = $this->billing_statement_model->list_active_patients_scripts_for_script($hospiceID);
        $data_to_be_updated = array();
		$data_to_be_updated_patient_days = array();
		
		$service_date_from = "2020-08-01";
		$service_date_to = "2020-08-31";
        foreach ($patients as $looped_patient) {
            $patient_first_order = get_patient_first_order($looped_patient['patientID']);
            $returned_data = $this->billing_statement_model->get_all_patient_pickup($looped_patient['patientID'], $service_date_from, $service_date_to);

            $patient_los = 1;
            $patient_days = 1;
            if (empty($returned_data)) {
                $current_date = date('Y-m-d h:i:s');

                $month_created = date('m', strtotime($looped_patient['date_created']));
                $current_month = date('m', strtotime($service_date_to));
                if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($service_date_to))) {
                    if ($current_month == $month_created) {
                        if ($patient_first_order['actual_order_date'] != '0000-00-00') {
                            $patient_days = (date('d', strtotime($service_date_to)) - date('d', strtotime($patient_first_order['actual_order_date'])));
                        } else {
                            $patient_days = (date('d', strtotime($service_date_to)) - date('d', strtotime($looped_patient['date_created'])));
                        }
                    } elseif ($current_month > $month_created) {
                        $patient_days = date('d', strtotime($service_date_to));
                    }
                } elseif (date('Y', strtotime($service_date_to)) > date('Y', strtotime($looped_patient['date_created']))) {
                    $patient_days = date('d', strtotime($service_date_to));
                }
            } elseif (count($returned_data) == 1) {
                if ($returned_data[0]['pickup_sub'] != 'not needed') {
                    $returned_query = $this->billing_statement_model->check_order_after_all_pickup($returned_data[0]['orderID'], $returned_data[0]['uniqueID'], $returned_data[0]['patientID'], $service_date_from, $service_date_to);
                    if (!empty($returned_query)) {
                        if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $returned_data[0]['pickup_date']) {
                            $month_created = date('m', strtotime($looped_patient['date_created']));
                            $current_month = date('m', strtotime($service_date_to));
                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($service_date_to))) {
                                if ($current_month == $month_created) {
                                    $patient_days = (date('d', strtotime($service_date_to)) - date('d', strtotime($looped_patient['date_created'])));
                                } elseif ($current_month > $month_created) {
                                    $patient_days = date('d', strtotime($service_date_to));
                                }
                            } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                                $patient_days = date('d', strtotime($service_date_to));
                            }
                        } else {
                            $month_created = date('m', strtotime($looped_patient['date_created']));
                            $patient_last_month = date('m', strtotime($returned_data[0]['pickup_date']));
                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($returned_data[0]['pickup_date']))) {
                                if ($patient_last_month == $month_created) {
                                    $patient_days = (date('d', strtotime($returned_data[0]['pickup_date'])) - date('d', strtotime($looped_patient['date_created'])));
                                } elseif ($patient_last_month > $month_created) {
                                    $patient_days = date('d', strtotime($returned_data[0]['pickup_date']));
                                }
                            } elseif (date('Y', strtotime($returned_data[0]['pickup_date'])) > date('Y', strtotime($looped_patient['date_created']))) {
                                $patient_days = date('d', strtotime($returned_data[0]['pickup_date']));
                            }
                        }
                    } else {
                        $month_created = date('m', strtotime($looped_patient['date_created']));
                        $patient_last_month = date('m', strtotime($returned_data[0]['pickup_date']));
                        if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($returned_data[0]['pickup_date']))) {
                            if ($patient_last_month == $month_created) {
                                $active_patient_sign = 0;
                                foreach ($list_active_patients as $looped_active_patient) {
                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                        $active_patient_sign = 1;
                                    }
                                }
                                if ($active_patient_sign == 1) {
                                    $patient_days = date('d');
                                } else {
                                    $patient_days = (date('d', strtotime($returned_data[0]['pickup_date'])) - date('d', strtotime($looped_patient['date_created'])));
                                }
                            } elseif ($patient_last_month > $month_created) {
                                $active_patient_sign = 0;
                                foreach ($list_active_patients as $looped_active_patient) {
                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                        $active_patient_sign = 1;
                                    }
                                }
                                if ($active_patient_sign == 1) {
                                    $patient_days = date('d', strtotime($service_date_to));
                                } else {
                                    $patient_days = date('d', strtotime($returned_data[0]['pickup_date']));
                                }
                            }
                        } elseif (date('Y', strtotime($returned_data[0]['pickup_date'])) > date('Y', strtotime($looped_patient['date_created']))) {
                            $active_patient_sign = 0;
                            foreach ($list_active_patients as $looped_active_patient) {
                                if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                    $active_patient_sign = 1;
                                }
                            }
                            if ($active_patient_sign == 1) {
                                $patient_days = date('d', strtotime($service_date_to));
                            } else {
                                $patient_days = date('d', strtotime($returned_data[0]['pickup_date']));
                            }
                        }
                    }
                } else {
                    $month_created = date('m', strtotime($looped_patient['date_created']));
                    $current_month = date('m', strtotime($service_date_to));
                    if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                        if ($current_month == $month_created) {
                            $patient_days = (date('d', strtotime($service_date_to)) - date('d', strtotime($looped_patient['date_created'])));
                        } elseif ($current_month > $month_created) {
                            $patient_days = date('d', strtotime($service_date_to));
                        }
                    } elseif (date('Y', strtotime($service_date_to)) > date('Y', strtotime($looped_patient['date_created']))) {
                        $patient_days = date('d', strtotime($service_date_to));
                    }
                }
            } else {
                $pickup_all_sign = 0;
                $pickup_all_count_sign = 0;
                foreach ($returned_data as $value_first_loop) {
                    if ($value_first_loop['pickup_date'] >= $patient_first_order['actual_order_date']) {
                        if ($value_first_loop['pickup_sub'] != 'not needed') {
                            $pickup_all_sign = 1;
                            ++$pickup_all_count_sign;
                        }
                    }
                }

                if ($pickup_all_sign == 0) {
                    $month_created = date('m', strtotime($looped_patient['date_created']));
                    $current_month = date('m', strtotime($service_date_to));
                    if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                        if ($current_month == $month_created) {
                            $patient_days = (date('d', strtotime($service_date_to)) - date('d', strtotime($looped_patient['date_created'])));
                        } elseif ($current_month > $month_created) {
                            $patient_days = date('d', strtotime($service_date_to));
                        }
                    } elseif (date('Y', strtotime($service_date_to)) > date('Y', strtotime($looped_patient['date_created']))) {
                        $patient_days = date('d', strtotime($service_date_to));
                    }
                }
                // IF NAAY COMPLETE PICKUP
                else {
                    $pickup_order_count = 1;
                    $previous_pickup_indications = 0; // 1 for selected item(s) pickup, 2 for complete pickup
                    foreach ($returned_data as $value) {
                        if ($pickup_order_count == 1) {
                            if ($value['pickup_sub'] != 'not needed') {
                                if ($pickup_all_count_sign == 1) {
                                    $returned_query_inside = $this->billing_statement_model->check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID'], $service_date_from, $service_date_to);
                                    if (date('Y-m-d', strtotime($returned_query_inside['date_ordered'])) > $value['pickup_date']) {
                                        $previous_pickup_indications = 2;
                                        $previous_orderID = $value['orderID'];
                                        $previous_uniqueID = $value['uniqueID'];
                                        $previous_ordered_date = $value['pickup_date'];
                                        $previous_date_ordered = $value['date_ordered'];
                                        $partial_patient_los_first = 1; // Back to 1
                                        $partial_patient_days_first = 1;
                                    } else {
                                        $previous_pickup_indications = 1;
                                        $previous_orderID = $value['orderID'];
                                        $previous_uniqueID = $value['uniqueID'];
                                        $previous_ordered_date = $value['pickup_date'];
                                        $previous_date_ordered = $value['date_ordered'];
                                        $partial_patient_days_first = 1;
                                    }
                                } else {
                                    $previous_pickup_indications = 2;
                                    $previous_orderID = $value['orderID'];
                                    $previous_uniqueID = $value['uniqueID'];
                                    $previous_ordered_date = $value['pickup_date'];
                                    $previous_date_ordered = $value['date_ordered'];
                                    $partial_patient_los_first = 1; // Back to 1
                                    $partial_patient_days_first = 1;
                                }
                            } else {
                                $previous_pickup_indications = 1;
                                $partial_patient_days_first = 1;
                                $previous_orderID = $value['orderID'];
                                $previous_uniqueID = $value['uniqueID'];
                                $previous_ordered_date = $value['pickup_date'];
                                $previous_date_ordered = $value['date_ordered'];
                            }
                        } else {
                            if ($value['pickup_sub'] != 'not needed') {
                                $previous_pickup_indications = 2;
                                if (count($returned_data) == $pickup_order_count) {
                                    $returned_query = $this->billing_statement_model->check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID'], $service_date_from, $service_date_to);
                                    if (!empty($returned_query)) {
                                        if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $value['pickup_date']) {
                                            $month_created = date('m', strtotime($looped_patient['date_created']));
                                            $current_month = date('m', strtotime($service_date_to));
                                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                                                if ($current_month == $month_created) {
                                                    $patient_days = (date('d', strtotime($service_date_to)) - date('d', strtotime($looped_patient['date_created'])));
                                                } elseif ($current_month > $month_created) {
                                                    $patient_days = date('d', strtotime($service_date_to));
                                                }
                                            } elseif (date('Y', strtotime($service_date_to)) > date('Y', strtotime($looped_patient['date_created']))) {
                                                $patient_days = date('d', strtotime($service_date_to));
                                            }
                                        } else {
                                            $month_created = date('m', strtotime($looped_patient['date_created']));
                                            $patient_last_month = date('m', strtotime($value['pickup_date']));
                                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($value['pickup_date']))) {
                                                if ($patient_last_month == $month_created) {
                                                    $patient_days = (date('d', strtotime($value['pickup_date'])) - date('d', strtotime($looped_patient['date_created'])));
                                                } elseif ($patient_last_month > $month_created) {
                                                    $patient_days = date('d', strtotime($value['pickup_date']));
                                                }
                                            } elseif (date('Y', strtotime($value['pickup_date'])) > date('Y', strtotime($looped_patient['date_created']))) {
                                                $patient_days = date('d', strtotime($value['pickup_date']));
                                            }
                                        }
                                    } else {
                                        $month_created = date('m', strtotime($looped_patient['date_created']));
                                        $current_month = date('m', strtotime($service_date_to));
                                        if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($value['pickup_date']))) {
                                            if ($current_month == $month_created) {
                                                $active_patient_sign = 0;
                                                foreach ($list_active_patients as $looped_active_patient) {
                                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                        $active_patient_sign = 1;
                                                    }
                                                }
                                                if ($active_patient_sign == 1) {
                                                    $patient_days = date('d');
                                                } else {
                                                    $patient_days = (date('d', strtotime($value['pickup_date'])) - date('d', strtotime($looped_patient['date_created'])));
                                                }
                                            } elseif ($current_month > $month_created) {
                                                $active_patient_sign = 0;
                                                foreach ($list_active_patients as $looped_active_patient) {
                                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                        $active_patient_sign = 1;
                                                    }
                                                }
                                                if ($active_patient_sign == 1) {
                                                    $patient_days = date('d', strtotime($service_date_to));
                                                } else {
                                                    $patient_days = date('d', strtotime($value['pickup_date']));
                                                }
                                            }
                                        } elseif (date('Y', strtotime($value['pickup_date'])) > date('Y', strtotime($looped_patient['date_created']))) {
                                            $active_patient_sign = 0;
                                            foreach ($list_active_patients as $looped_active_patient) {
                                                if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                    $active_patient_sign = 1;
                                                }
                                            }
                                            if ($active_patient_sign == 1) {
                                                $patient_days = date('d', strtotime($service_date_to));
                                            } else {
                                                $patient_days = date('d', strtotime($value['pickup_date']));
                                            }
                                        }
                                    }
                                } else {
                                    $returned_query = $this->billing_statement_model->check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID'], $service_date_from, $service_date_to);
                                    if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $value['pickup_date']) {
                                        $partial_patient_los_first = 1;
                                        $previous_date_ordered = $value['date_ordered'];
                                    } else {
                                    }
                                    $partial_patient_days_first = 1;
                                }
                            } else {
                                $previous_pickup_indications = 1;
                                if (count($returned_data) == $pickup_order_count) {
                                    $current_date = date('Y-m-d h:i:s');
                                    if ($value['pickup_date'] > $current_date) {
                                    } else {
                                    }
                                } else {
                                    if ($previous_pickup_indications == 1) {
                                    } else {
                                    }
                                    $previous_date_ordered = $value['date_ordered'];
                                }
                                $month_created = date('m', strtotime($looped_patient['date_created']));
                                $current_month = date('m', strtotime($service_date_to));
                                if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($service_date_to))) {
                                    if ($current_month == $month_created) {
                                        $patient_days = (date('d', strtotime($service_date_to)) - date('d', strtotime($looped_patient['date_created'])));
                                    } elseif ($current_month > $month_created) {
                                        $patient_days = date('d', strtotime($service_date_to));
                                    }
                                } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                                    $patient_days = date('d', strtotime($service_date_to));
                                }
                            }
                        }
                        ++$pickup_order_count;
                        $previous_ordered_date = $value['pickup_date'];
                    }
                }
            }

            if ($patient_days < 1) {
                $patient_days = 1;
            }
            $data_per_patient_pt_days = array(
                  'patientID' => $looped_patient['patientID'],
				  'customer_days' => $patient_days,
				  'length_of_stay' => 0,
				  'service_date' => $service_date_to
              );
            $data_to_be_updated_patient_days[] = $data_per_patient_pt_days;
        }
		$save_patient_days = $this->billing_statement_model->insert_customer_length_of_stay($data_to_be_updated_patient_days);
		print_me($data_to_be_updated_patient_days);
		print_me($save_patient_days);
		print_me($hospiceID);
	}

	// For Customer Days Script Draft Statement Kani gamita
    public function insert_customer_patient_days_script_v2($hospiceID)
    {
		$patients = $this->billing_statement_model->get_all_patients_for_script($hospiceID);
		// print_me($patients); 
        $list_active_patients = $this->billing_statement_model->list_active_patients_scripts_for_script($hospiceID);
        $data_to_be_updated = array();
		$data_to_be_updated_patient_days = array();
		
		$service_date_from = "2021-07-01";
		$service_date_to = "2021-07-31";
        foreach ($patients as $looped_patient) {
            $patient_first_order = get_patient_first_order($looped_patient['patientID']);
            $returned_data = $this->billing_statement_model->get_all_patient_pickup($looped_patient['patientID'], $service_date_from, $service_date_to);

            $patient_los = 1;
            $patient_days = 1;
            if (empty($returned_data)) {
                $current_date = date('Y-m-d h:i:s');

                $month_created = date('m', strtotime($looped_patient['date_created']));
                $current_month = date('m', strtotime($service_date_to));
                if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($service_date_to))) {
                    if ($current_month == $month_created) {
                        if ($patient_first_order['actual_order_date'] != '0000-00-00') {
                            $patient_days = (date('d', strtotime($service_date_to)) - date('d', strtotime($patient_first_order['actual_order_date'])));
                        } else {
                            $patient_days = (date('d', strtotime($service_date_to)) - date('d', strtotime($looped_patient['date_created'])));
                        }
                    } elseif ($current_month > $month_created) {
                        $patient_days = date('d', strtotime($service_date_to));
                    }
                } elseif (date('Y', strtotime($service_date_to)) > date('Y', strtotime($looped_patient['date_created']))) {
                    $patient_days = date('d', strtotime($service_date_to));
                }
            } elseif (count($returned_data) == 1) {
                if ($returned_data[0]['pickup_sub'] != 'not needed') {
					$returned_query = $this->billing_statement_model->check_order_after_all_pickup($returned_data[0]['orderID'], $returned_data[0]['uniqueID'], $returned_data[0]['patientID'], $service_date_from, $service_date_to);
					
					$pickupdate = $returned_data[0]['pickup_date'];
					if ($returned_data[0]['pickup_discharge_date'] != '0000-00-00' && $returned_data[0]['pickup_discharge_date'] != null) {
						$pickupdate = $returned_data[0]['pickup_discharge_date'];
					}

                    if (!empty($returned_query)) {
                        if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $pickupdate) {
                            $month_created = date('m', strtotime($looped_patient['date_created']));
                            $current_month = date('m', strtotime($pickupdate));
                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($pickupdate))) {
                                if ($current_month == $month_created) {
                                    $patient_days = (date('d', strtotime($pickupdate)) - date('d', strtotime($looped_patient['date_created'])));
                                } elseif ($current_month > $month_created) {
                                    $patient_days = date('d', strtotime($pickupdate));
                                }
                            } elseif (date('Y', strtotime($pickupdate)) > date('Y', strtotime($looped_patient['date_created']))) {
                                $patient_days = date('d', strtotime($pickupdate));
                            }
                        } else {
                            $month_created = date('m', strtotime($looped_patient['date_created']));
                            $patient_last_month = date('m', strtotime($pickupdate));
                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($pickupdate))) {
                                if ($patient_last_month == $month_created) {
                                    $patient_days = (date('d', strtotime($pickupdate)) - date('d', strtotime($looped_patient['date_created'])));
                                } elseif ($patient_last_month > $month_created) {
                                    $patient_days = date('d', strtotime($pickupdate));
                                }
                            } elseif (date('Y', strtotime($pickupdate)) > date('Y', strtotime($looped_patient['date_created']))) {
                                $patient_days = date('d', strtotime($pickupdate));
                            }
                        }
                    } else {
                        $pickupdate = $returned_data[0]['pickup_date'];
                        if ($returned_data[0]['pickup_discharge_date'] != '0000-00-00' && $returned_data[0]['pickup_discharge_date'] != null) {
                            $pickupdate = $returned_data[0]['pickup_discharge_date'];
                        }

                        $month_created = date('m', strtotime($looped_patient['date_created']));
                        $patient_last_month = date('m', strtotime($pickupdate));
                        if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($pickupdate))) {
                            if ($patient_last_month == $month_created) {
                                $active_patient_sign = 0;
                                foreach ($list_active_patients as $looped_active_patient) {
                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                        $active_patient_sign = 1;
                                    }
                                }
                                if ($active_patient_sign == 1) {
                                    $patient_days = date('d', strtotime($service_date_to));
                                } else {
                                    $patient_days = (date('d', strtotime($pickupdate)) - date('d', strtotime($looped_patient['date_created'])));
                                }
                            } elseif ($patient_last_month > $month_created) {
                                $active_patient_sign = 0;
                                foreach ($list_active_patients as $looped_active_patient) {
                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                        $active_patient_sign = 1;
                                    }
                                }
                                if ($active_patient_sign == 1) {
									$patient_days = date('d', strtotime($service_date_to));
                                } else {
                                    $patient_days = date('d', strtotime($pickupdate));
                                }
                            }
                        } elseif (date('Y', strtotime($pickupdate)) > date('Y', strtotime($looped_patient['date_created']))) {
                            $active_patient_sign = 0;
                            foreach ($list_active_patients as $looped_active_patient) {
                                if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                    $active_patient_sign = 1;
                                }
                            }
                            if ($active_patient_sign == 1) {
                                $patient_days = date('d', strtotime($service_date_to));
                            } else {
                                $patient_days = date('d', strtotime($pickupdate));
                            }
                        }
                    }
                } else {
                    $month_created = date('m', strtotime($looped_patient['date_created']));
                    $current_month = date('m', strtotime($service_date_to));
                    if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($service_date_to))) {
                        if ($current_month == $month_created) {
                            $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                        } elseif ($current_month > $month_created) {
                            $patient_days = date('d', strtotime($service_date_to));
                        }
                    } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                        $patient_days = date('d', strtotime($service_date_to));
                    }
                }
            } else {
                $pickup_all_sign = 0;
                $pickup_all_count_sign = 0;
                foreach ($returned_data as $value_first_loop) {
                    if ($value_first_loop['pickup_date'] >= $patient_first_order['actual_order_date']) {
                        if ($value_first_loop['pickup_sub'] != 'not needed') {
                            $pickup_all_sign = 1;
                            ++$pickup_all_count_sign;
                        }
                    }
                }

                if ($pickup_all_sign == 0) {
                    $month_created = date('m', strtotime($looped_patient['date_created']));
                    $current_month = date('m', strtotime($service_date_to));
                    if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($service_date_to))) {
                        if ($current_month == $month_created) {
                            $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                        } elseif ($current_month > $month_created) {
                            $patient_days = date('d', strtotime($service_date_to));
                        }
                    } elseif (date('Y', strtotime($service_date_to)) > date('Y', strtotime($looped_patient['date_created']))) {
                        $patient_days = date('d', strtotime($service_date_to));
                    }
                }
                // IF NAAY COMPLETE PICKUP
                else {
                    $pickup_order_count = 1;
                    $previous_pickup_indications = 0; // 1 for selected item(s) pickup, 2 for complete pickup
                    foreach ($returned_data as $value) {
                        if ($pickup_order_count == 1) {
                            if ($value['pickup_sub'] != 'not needed') {
                                if ($pickup_all_count_sign == 1) {
                                    $returned_query_inside = $this->billing_statement_model->check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID'], $service_date_from, $service_date_to);
                                    if (date('Y-m-d', strtotime($returned_query_inside['date_ordered'])) > $value['pickup_date']) {
                                        $previous_pickup_indications = 2;
                                    } else {
                                        $previous_pickup_indications = 1;
                                    }
                                } else {
                                    $previous_pickup_indications = 2;
                                }
                            } else {
                                $previous_pickup_indications = 1;
                            }
                        } else {
                            if ($value['pickup_sub'] != 'not needed') {
                                $previous_pickup_indications = 2;
                                if (count($returned_data) == $pickup_order_count) {
                                    $returned_query = $this->billing_statement_model->check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID'], $service_date_from, $service_date_to);
                                    if (!empty($returned_query)) {
                                        if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $value['pickup_date']) {
                                            $month_created = date('m', strtotime($looped_patient['date_created']));
											$current_month = date('m', strtotime($service_date_to));
                                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($service_date_to))) {
                                                if ($current_month == $month_created) {
                                                    $patient_days = (date('d', strtotime($service_date_to)) - date('d', strtotime($looped_patient['date_created'])));
                                                } elseif ($current_month > $month_created) {
                                                    $patient_days = date('d', strtotime($service_date_to));
                                                }
                                            } elseif (date('Y', strtotime($service_date_to)) > date('Y', strtotime($looped_patient['date_created']))) {
                                                $patient_days = date('d', strtotime($service_date_to));
                                            }
                                        } else {
                                            $month_created = date('m', strtotime($looped_patient['date_created']));
                                            $patient_last_month = date('m', strtotime($value['pickup_date']));
                                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($value['pickup_date']))) {
                                                if ($patient_last_month == $month_created) {
                                                    $patient_days = (date('d', strtotime($value['pickup_date'])) - date('d', strtotime($looped_patient['date_created'])));
                                                } elseif ($patient_last_month > $month_created) {
                                                    $patient_days = date('d', strtotime($value['pickup_date']));
                                                }
                                            } elseif (date('Y', strtotime($value['pickup_date'])) > date('Y', strtotime($looped_patient['date_created']))) {
                                                $patient_days = date('d', strtotime($value['pickup_date']));
                                            }
                                        }
                                    } else {
                                        $pickupdate = $value['pickup_date'];
                                        if ($value['pickup_discharge_date'] != '0000-00-00' && $value['pickup_discharge_date'] != null) {
                                            $pickupdate = $value['pickup_discharge_date'];
                                        }

                                        $month_created = date('m', strtotime($looped_patient['date_created']));
                                        $current_month = date('m', strtotime($service_date_to));
                                        if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($pickupdate))) {
                                            if ($current_month == $month_created) {
                                                $active_patient_sign = 0;
                                                foreach ($list_active_patients as $looped_active_patient) {
                                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                        $active_patient_sign = 1;
                                                    }
                                                }
                                                if ($active_patient_sign == 1) {
                                                    $patient_days = date('d', strtotime($service_date_to));
                                                } else {
                                                    $patient_days = (date('d', strtotime($pickupdate)) - date('d', strtotime($looped_patient['date_created'])));
                                                }
                                            } elseif ($current_month > $month_created) {
                                                $active_patient_sign = 0;
                                                foreach ($list_active_patients as $looped_active_patient) {
                                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                        $active_patient_sign = 1;
                                                    }
                                                }
                                                if ($active_patient_sign == 1) {
                                                    $patient_days = date('d', strtotime($service_date_to));
                                                } else {
                                                    $patient_days = date('d', strtotime($pickupdate));
                                                }
                                            }
                                        } elseif (date('Y', strtotime($pickupdate)) > date('Y', strtotime($looped_patient['date_created']))) {
                                            $active_patient_sign = 0;
                                            foreach ($list_active_patients as $looped_active_patient) {
                                                if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                    $active_patient_sign = 1;
                                                }
                                            }
                                            if ($active_patient_sign == 1) {
                                                $patient_days = date('d', strtotime($service_date_to));
                                            } else {
                                                $patient_days = date('d', strtotime($pickupdate));
                                            }
                                        }
                                    }
                                } else {
                                    $returned_query = $this->billing_statement_model->check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID'], $service_date_from, $service_date_to);
                                    if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $value['pickup_date']) {
                                        $partial_patient_los_first = 1;
                                        $previous_date_ordered = $value['date_ordered'];
                                    } else {
                                    }
                                    $partial_patient_days_first = 1;
                                }
                            } else {
                                $previous_pickup_indications = 1;
                                if (count($returned_data) == $pickup_order_count) {
                                    $current_date = date('Y-m-d h:i:s');
                                    if ($value['pickup_date'] > $current_date) {
                                    } else {
                                    }
                                } else {
                                    if ($previous_pickup_indications == 1) {
                                    } else {
                                    }
                                    $previous_date_ordered = $value['date_ordered'];
                                }
                                $month_created = date('m', strtotime($looped_patient['date_created']));
                                $current_month = date('m');
                                if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                                    if ($current_month == $month_created) {
                                        $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                                    } elseif ($current_month > $month_created) {
                                        $patient_days = date('d', strtotime($service_date_to));
                                    }
                                } elseif (date('Y', strtotime($service_date_to)) > date('Y', strtotime($looped_patient['date_created']))) {
                                    $patient_days = date('d', strtotime($service_date_to));
                                }
                            }
                        }
                        ++$pickup_order_count;
                        $previous_ordered_date = $value['pickup_date'];
                    }
                }
            }

			if ($patient_days < 1) {
                $patient_days = 1;
            }
            $data_per_patient_pt_days = array(
                  'patientID' => $looped_patient['patientID'],
				  'customer_days' => $patient_days,
				  'length_of_stay' => 0,
				  'service_date' => $service_date_from,
				  'hospiceID' => $hospiceID
              );
            $data_to_be_updated_patient_days[] = $data_per_patient_pt_days;
        }
		
		$save_patient_days = $this->billing_statement_model->insert_customer_length_of_stay($data_to_be_updated_patient_days);
		print_me($hospiceID);
		print_me($data_to_be_updated_patient_days);
		print_me($save_patient_days);
	}
	
	public function save_customer_days($hospiceID) {
		// $hospiceID = 13;
		$patients = $this->billing_statement_model->get_all_patients_for_script($hospiceID);
		$next_month_date = date('Y-m-01');
		foreach($patients as $value) {
			//By one
			$data = array(
				"patientID" => $value['patientID'],
				"customer_days" => $value['patient_days'],
				"service_date" => $next_month_date,
				"hospiceID" => $hospiceID
			);
			$this->billing_statement_model->save_customer_days($data);

			//By batch
			$data_batch[] = array(
				"patientID" => $value['patientID'],
				"customer_days" => $value['patient_days'],
				"service_date" => $next_month_date,
				"hospiceID" => $hospiceID
			);
		}
		// print_me(date('Y-m-01', strtotime('+1 month')));
		print_me($hospiceID);
		print_me(count($patients));
		//By batch
		// $this->billing_statement_model->save_customer_days_batch($data);
	}

	public function fix_service_date ($hospiceID) {
		$data = array(
			"service_date" => "2020-09-01"
		);
		$update = $this->billing_statement_model->fix_customer_days_length_of_stay_fix($hospiceID, $data);
		print_me($update);
	}

	public function customer_days_length_of_stay_script($hospiceID) {
		$patients = $this->billing_statement_model->get_all_patients_for_script_inactive($hospiceID);
		$service_date_from = '2021-01-01';
		$service_date_to = '2021-01-31';
		$data_cusdays_los = array();

		foreach($patients as $value) {
			$pick_order = $this->billing_statement_model->get_pickuporder($value['patientID'], $service_date_from, $service_date_to);
			$first_workorder = get_patient_first_order($value['patientID']);
			$cusdays_los = $this->billing_statement_model->get_customer_days_length_of_stay($value['patientID'], $service_date_to, $service_date_from);
			if (!empty($pick_order)) {
				if ($pick_order['pickup_discharge_date'] != '0000-00-00' && $pick_order['pickup_discharge_date'] != null) {
					$customer_days = 0;
					if($first_workorder['actual_order_date'] >= $service_date_from) {
						$customer_days = (date('d', strtotime($pick_order['pickup_discharge_date'])) - date('d', strtotime($first_workorder['actual_order_date'])));
					} else {
						$customer_days = date('d', strtotime($pick_order['pickup_discharge_date']));
					}

					if (!empty($cusdays_los)) {
						if ($customer_days != $cusdays_los['customer_days']) {
							// $data_cusdays_los[] = array(
							// 	'patient_info' => $value,
							// 	'customer_days' => $customer_days,
							// 	'is_correct' => 'false',
							// 	'pickup_discharge_date' => $pick_order['pickup_discharge_date'],
							// 	'actual_order_date' => $first_workorder['actual_order_date'],
							// 	'cus_days_los' => $cusdays_los
							// );
							$data_cusdays_los = array(
								'customer_days' => $customer_days
							);
							$this->billing_statement_model->update_customer_days_length_of_stay($cusdays_los['cus_days_los_id'], $data_cusdays_los);
						} else {
							// $data_cusdays_los[] = array(
							// 	'patient_info' => $value,
							// 	'customer_days' => $customer_days,
							// 	'is_correct' => 'true',
							// 	'pickup_discharge_date' => $pick_order['pickup_discharge_date'],
							// 	'actual_order_date' => $first_workorder['actual_order_date'],
							// 	'cus_days_los' => $cusdays_los
							// );
						}
					} else {
						// $data_cusdays_los[] = array(
						// 	'patient_info' => $value,
						// 	'customer_days' => $customer_days,
						// 	'is_correct' => 'walay cusdays_los',
						// 	'pickup_discharge_date' => $pick_order['pickup_discharge_date'],
						// 	'actual_order_date' => $first_workorder['actual_order_date'],
						// 	'cus_days_los' => 0
						// );
						$data_cusdays_los = array(
							"patientID" => $value['patientID'],
							"customer_days" => $customer_days,
							"service_date" => $service_date_from,
							"hospiceID" => $hospiceID
						);
						$this->billing_statement_model->save_customer_days($data_cusdays_los);
					}
					
				}
			}
		}
		print_me($hospiceID);
		print_me(count($patients));
		print_me($data_cusdays_los);
	}

	public function save_customer_days_for_draft_statement() {
		$tomorrow = date('d', strtotime('1 day'));

		if ($tomorrow*1 == 1) {
			$patients = $this->billing_statement_model->get_all_patient_for_draft_statement();
			$month_date = date('Y-m-01');
			foreach($patients as $value) {
				//By one
				$data = array(
					"patientID" => $value['patientID'],
					"customer_days" => $value['patient_days'],
					"service_date" => $month_date,
					"hospiceID" => $value['ordered_by']
				);
				$this->billing_statement_model->save_customer_days($data);
			}
		}
	}

	// For the Patient Length Of Stay
    public function update_patient_los($hospiceID)
    {
        $patients = $this->billing_statement_model->get_all_patients_for_script_inactive($hospiceID);
        $list_active_patients = $this->billing_statement_model->list_active_patients_scripts_for_script($hospiceID);

        $data_to_be_updated = array();
        $data_to_be_updated_patient_days = array();
        foreach ($patients as $looped_patient) {
            $patient_first_order = get_patient_first_order($looped_patient['patientID']);
            $returned_data = get_all_patient_pickup($looped_patient['patientID']);

            $patient_los = 1;
            $patient_days = 1;
            if (empty($returned_data)) {
                $current_date = date('Y-m-d h:i:s');
                $answer = strtotime($current_date) - strtotime($looped_patient['date_created']);
                $answer_2 = $answer / 86400;
                $patient_los = $patient_los + floor($answer_2);

                $month_created = date('m', strtotime($looped_patient['date_created']));
                $current_month = date('m');
                if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                    if ($current_month == $month_created) {
                        if ($patient_first_order['actual_order_date'] != '0000-00-00') {
                            $patient_days = (date('d') - date('d', strtotime($patient_first_order['actual_order_date'])));
                            // ++$patient_days;
                        } else {
                            $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                            // ++$patient_days;
                        }
                    } elseif ($current_month > $month_created) {
                        $patient_days = date('d');
                    }
                } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                    $patient_days = date('d');
                }
            } elseif (count($returned_data) == 1) {
                if ($returned_data[0]['pickup_sub'] != 'not needed') {
                    $returned_query = check_order_after_all_pickup($returned_data[0]['orderID'], $returned_data[0]['uniqueID'], $returned_data[0]['patientID']);
                    if (!empty($returned_query)) {
                        if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $returned_data[0]['pickup_date']) {
                            $current_date = date('Y-m-d h:i:s');
                            $answer = strtotime($current_date) - strtotime($looped_patient['date_created']);
                            $answer_2 = $answer / 86400;
                            $patient_los = $patient_los + floor($answer_2);

                            $month_created = date('m', strtotime($looped_patient['date_created']));
                            $current_month = date('m');
                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                                if ($current_month == $month_created) {
                                    $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                                    // ++$patient_days;
                                } elseif ($current_month > $month_created) {
                                    $patient_days = date('d');
                                }
                            } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                                $patient_days = date('d');
                            }
                        } else {
                            $answer = strtotime($returned_data[0]['pickup_date']) - strtotime($looped_patient['date_created']);
                            $answer_2 = $answer / 86400;
                            $patient_los = $patient_los + floor($answer_2);

                            $month_created = date('m', strtotime($looped_patient['date_created']));
                            $patient_last_month = date('m', strtotime($returned_data[0]['pickup_date']));
                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($returned_data[0]['pickup_date']))) {
                                if ($patient_last_month == $month_created) {
                                    $patient_days = (date('d', strtotime($returned_data[0]['pickup_date'])) - date('d', strtotime($looped_patient['date_created'])));
                                    // ++$patient_days;
                                } elseif ($patient_last_month > $month_created) {
                                    $patient_days = date('d', strtotime($returned_data[0]['pickup_date']));
                                }
                            } elseif (date('Y', strtotime($returned_data[0]['pickup_date'])) > date('Y', strtotime($looped_patient['date_created']))) {
                                $patient_days = date('d', strtotime($returned_data[0]['pickup_date']));
                            }
                        }
                    } else {
                        $pickupdate = $returned_data[0]['pickup_date'];
                        if ($returned_data[0]['pickup_discharge_date'] != '0000-00-00' && $returned_data[0]['pickup_discharge_date'] != null) {
                            $pickupdate = $returned_data[0]['pickup_discharge_date'];
                        }

                        $answer = strtotime($pickupdate) - strtotime($looped_patient['date_created']);
                        $answer_2 = $answer / 86400;
                        $patient_los = $patient_los + floor($answer_2);

                        $month_created = date('m', strtotime($looped_patient['date_created']));
                        $patient_last_month = date('m', strtotime($pickupdate));
                        if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($pickupdate))) {
                            if ($patient_last_month == $month_created) {
                                $active_patient_sign = 0;
                                foreach ($list_active_patients as $looped_active_patient) {
                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                        $active_patient_sign = 1;
                                    }
                                }
                                if ($active_patient_sign == 1) {
                                    $patient_days = date('d');
                                } else {
                                    $patient_days = (date('d', strtotime($pickupdate)) - date('d', strtotime($looped_patient['date_created'])));
                                    // ++$patient_days;
                                }
                            } elseif ($patient_last_month > $month_created) {
                                $active_patient_sign = 0;
                                foreach ($list_active_patients as $looped_active_patient) {
                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                        $active_patient_sign = 1;
                                    }
                                }
                                if ($active_patient_sign == 1) {
                                    $patient_days = date('d');
                                } else {
                                    $patient_days = date('d', strtotime($pickupdate));
                                }
                            }
                        } elseif (date('Y', strtotime($pickupdate)) > date('Y', strtotime($looped_patient['date_created']))) {
                            $active_patient_sign = 0;
                            foreach ($list_active_patients as $looped_active_patient) {
                                if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                    $active_patient_sign = 1;
                                }
                            }
                            if ($active_patient_sign == 1) {
                                $patient_days = date('d');
                            } else {
                                $patient_days = date('d', strtotime($pickupdate));
                            }
                        }
                    }
                } else {
                    $current_date = date('Y-m-d h:i:s');
                    $answer = strtotime($current_date) - strtotime($looped_patient['date_created']);
                    $answer_2 = $answer / 86400;
                    $patient_los = $patient_los + floor($answer_2);

                    $month_created = date('m', strtotime($looped_patient['date_created']));
                    $current_month = date('m');
                    if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                        if ($current_month == $month_created) {
                            $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                            // ++$patient_days;
                        } elseif ($current_month > $month_created) {
                            $patient_days = date('d');
                        }
                    } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                        $patient_days = date('d');
                    }
                }
            } else {
                $pickup_all_sign = 0;
                $pickup_all_count_sign = 0;
                foreach ($returned_data as $value_first_loop) {
                    if ($value_first_loop['pickup_date'] >= $patient_first_order['actual_order_date']) {
                        if ($value_first_loop['pickup_sub'] != 'not needed') {
                            $pickup_all_sign = 1;
                            ++$pickup_all_count_sign;
                        }
                    }
                }

                if ($pickup_all_sign == 0) {
                    $current_date = date('Y-m-d h:i:s');
                    $answer = strtotime($current_date) - strtotime($looped_patient['date_created']);
                    $answer_2 = $answer / 86400;
                    $patient_los = $patient_los + floor($answer_2);

                    $month_created = date('m', strtotime($looped_patient['date_created']));
                    $current_month = date('m');
                    if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                        if ($current_month == $month_created) {
                            $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                            // ++$patient_days;
                        } elseif ($current_month > $month_created) {
                            $patient_days = date('d');
                        }
                    } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                        $patient_days = date('d');
                    }
                }
                // IF NAAY COMPLETE PICKUP
                else {
                    $pickup_order_count = 1;
                    $previous_pickup_indications = 0; // 1 for selected item(s) pickup, 2 for complete pickup
                    foreach ($returned_data as $value) {
                        if ($pickup_order_count == 1) {
                            if ($value['pickup_sub'] != 'not needed') {
                                if ($pickup_all_count_sign == 1) {
                                    $returned_query_inside = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
                                    if (date('Y-m-d', strtotime($returned_query_inside['date_ordered'])) > $value['pickup_date']) {
                                        $previous_pickup_indications = 2;
                                        $previous_orderID = $value['orderID'];
                                        $previous_uniqueID = $value['uniqueID'];
                                        $previous_ordered_date = $value['pickup_date'];
                                        $previous_date_ordered = $value['date_ordered'];
                                        $partial_patient_los_first = 1; // Back to 1
                                        $partial_patient_days_first = 1;
                                    } else {
                                        $previous_pickup_indications = 1;
                                        $previous_orderID = $value['orderID'];
                                        $previous_uniqueID = $value['uniqueID'];
                                        $previous_ordered_date = $value['pickup_date'];
                                        $previous_date_ordered = $value['date_ordered'];
                                        $answer = strtotime($value['pickup_date']) - strtotime($patient_first_order['actual_order_date']);
                                        $answer_2 = $answer / 86400;
                                        $partial_patient_los_first = $patient_los + floor($answer_2);
                                        $partial_patient_days_first = 1;
                                    }
                                } else {
                                    $previous_pickup_indications = 2;
                                    $previous_orderID = $value['orderID'];
                                    $previous_uniqueID = $value['uniqueID'];
                                    $previous_ordered_date = $value['pickup_date'];
                                    $previous_date_ordered = $value['date_ordered'];
                                    $partial_patient_los_first = 1; // Back to 1
                                    $partial_patient_days_first = 1;
                                }
                            } else {
                                $answer = strtotime($value['pickup_date']) - strtotime($patient_first_order['actual_order_date']);
                                $answer_2 = $answer / 86400;
                                $partial_patient_los_first = $patient_los + floor($answer_2);
                                $previous_pickup_indications = 1;
                                $partial_patient_days_first = 1;
                                $previous_orderID = $value['orderID'];
                                $previous_uniqueID = $value['uniqueID'];
                                $previous_ordered_date = $value['pickup_date'];
                                $previous_date_ordered = $value['date_ordered'];
                            }
                        } else {
                            if ($value['pickup_sub'] != 'not needed') {
                                $previous_pickup_indications = 2;
                                if (count($returned_data) == $pickup_order_count) {
                                    $returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
                                    if (!empty($returned_query)) {
                                        if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $value['pickup_date']) {
                                            $current_date = date('Y-m-d h:i:s');
                                            $answer = strtotime($current_date) - strtotime($looped_patient['date_created']);
                                            $answer_2 = $answer / 86400;
                                            $patient_los = $patient_los + floor($answer_2);

                                            $month_created = date('m', strtotime($looped_patient['date_created']));
                                            $current_month = date('m');
                                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                                                if ($current_month == $month_created) {
                                                    $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                                                    // ++$patient_days;
                                                } elseif ($current_month > $month_created) {
                                                    $patient_days = date('d');
                                                }
                                            } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                                                $patient_days = date('d');
                                            }
                                        } else {
                                            $answer = strtotime($value['pickup_date']) - strtotime($previous_ordered_date);
                                            $answer_2 = $answer / 86400;
                                            $patient_los = $partial_patient_los_first + floor($answer_2);

                                            $month_created = date('m', strtotime($looped_patient['date_created']));
                                            $patient_last_month = date('m', strtotime($value['pickup_date']));
                                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($value['pickup_date']))) {
                                                if ($patient_last_month == $month_created) {
                                                    $patient_days = (date('d', strtotime($value['pickup_date'])) - date('d', strtotime($looped_patient['date_created'])));
                                                    // ++$patient_days;
                                                } elseif ($patient_last_month > $month_created) {
                                                    $patient_days = date('d', strtotime($value['pickup_date']));
                                                }
                                            } elseif (date('Y', strtotime($value['pickup_date'])) > date('Y', strtotime($looped_patient['date_created']))) {
                                                $patient_days = date('d', strtotime($value['pickup_date']));
                                            }
                                        }
                                    } else {
                                        $pickupdate = $value['pickup_date'];
                                        if ($value['pickup_discharge_date'] != '0000-00-00' && $value['pickup_discharge_date'] != null) {
                                            $pickupdate = $value['pickup_discharge_date'];
                                        }

                                        $answer = strtotime($pickupdate) - strtotime($looped_patient['date_created']);
                                        $answer_2 = $answer / 86400;
                                        $patient_los = $patient_los + floor($answer_2);

                                        $month_created = date('m', strtotime($looped_patient['date_created']));
                                        $current_month = date('m');
                                        if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($pickupdate))) {
                                            if ($current_month == $month_created) {
                                                $active_patient_sign = 0;
                                                foreach ($list_active_patients as $looped_active_patient) {
                                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                        $active_patient_sign = 1;
                                                    }
                                                }
                                                if ($active_patient_sign == 1) {
                                                    $patient_days = date('d');
                                                } else {
                                                    $patient_days = (date('d', strtotime($pickupdate)) - date('d', strtotime($looped_patient['date_created'])));
                                                    // ++$patient_days;
                                                }
                                            } elseif ($current_month > $month_created) {
                                                $active_patient_sign = 0;
                                                foreach ($list_active_patients as $looped_active_patient) {
                                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                        $active_patient_sign = 1;
                                                    }
                                                }
                                                if ($active_patient_sign == 1) {
                                                    $patient_days = date('d');
                                                } else {
                                                    $patient_days = date('d', strtotime($pickupdate));
                                                }
                                            }
                                        } elseif (date('Y', strtotime($pickupdate)) > date('Y', strtotime($looped_patient['date_created']))) {
                                            $active_patient_sign = 0;
                                            foreach ($list_active_patients as $looped_active_patient) {
                                                if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                    $active_patient_sign = 1;
                                                }
                                            }
                                            if ($active_patient_sign == 1) {
                                                $patient_days = date('d');
                                            } else {
                                                $patient_days = date('d', strtotime($pickupdate));
                                            }
                                        }
                                    }
                                } else {
                                    $returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
                                    if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $value['pickup_date']) {
                                        $partial_patient_los_first = 1;
                                        $previous_date_ordered = $value['date_ordered'];
                                    } else {
                                        $answer = strtotime($value['pickup_date']) - strtotime($previous_ordered_date);
                                        $answer_2 = $answer / 86400;
                                        $partial_patient_los_first = $partial_patient_los_first + floor($answer_2);
                                    }
                                    $partial_patient_days_first = 1;
                                }
                            } else {
                                $previous_pickup_indications = 1;
                                if (count($returned_data) == $pickup_order_count) {
                                    $current_date = date('Y-m-d h:i:s');
                                    if ($value['pickup_date'] > $current_date) {
                                        $answer = strtotime($current_date) - strtotime($previous_ordered_date);
                                        $answer_2 = $answer / 86400;
                                        $patient_los = $partial_patient_los_first + floor($answer_2);
                                    } else {
                                        $answer = strtotime($value['pickup_date']) - strtotime($previous_ordered_date);
                                        $answer_2 = $answer / 86400;
                                        $partial_patient_los_first = $partial_patient_los_first + floor($answer_2);

                                        $answer_sub = strtotime($current_date) - strtotime($value['pickup_date']);
                                        $answer_2_sub = $answer_sub / 86400;
                                        $patient_los = $partial_patient_los_first + floor($answer_2_sub);
                                    }
                                } else {
                                    if ($previous_pickup_indications == 1) {
                                        $answer = strtotime($value['pickup_date']) - strtotime($previous_ordered_date);
                                        $answer_2 = $answer / 86400;
                                        $partial_patient_los_first = $partial_patient_los_first + floor($answer_2);
                                    } else {
                                        $answer = strtotime($value['pickup_date']) - strtotime($looped_patient['date_created']);
                                        $answer_2 = $answer / 86400;
                                        $partial_patient_los_first = $partial_patient_los_first + floor($answer_2);
                                    }
                                    $previous_date_ordered = $value['date_ordered'];
                                }
                                $month_created = date('m', strtotime($looped_patient['date_created']));
                                $current_month = date('m');
                                if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                                    if ($current_month == $month_created) {
                                        $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                                        // ++$patient_days;
                                    } elseif ($current_month > $month_created) {
                                        $patient_days = date('d');
                                    }
                                } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                                    $patient_days = date('d');
                                }
                            }
                        }
                        ++$pickup_order_count;
                        $previous_ordered_date = $value['pickup_date'];
                    }
                }
            }

            if ($patient_los < 1) {
                $patient_los = 1;
            }
            $data_per_patient = array(
                  'patientID' => $looped_patient['patientID'],
                  'length_of_stay' => $patient_los,
              );
            $data_to_be_updated[] = $data_per_patient;

            if ($patient_days < 1) {
                $patient_days = 1;
            }
            $data_per_patient_pt_days = array(
                  'patientID' => $looped_patient['patientID'],
                  'patient_days' => $patient_days,
              );
            $data_to_be_updated_patient_days[] = $data_per_patient_pt_days;
        }
        $this->order_model->update_patient_los($data_to_be_updated);
        $this->order_model->update_patient_los($data_to_be_updated_patient_days);

		print_me('data_to_be_updated');
		print_me($data_to_be_updated);
		print_me('data_to_be_updated_patient_days');
		print_me($data_to_be_updated);

        $los_per_hospice = $this->order_model->get_total_patient_los_per_hospice();
        $data_to_be_inserted = array();
        $current_date = date('Y-m-d');

        $current_hospice = 0;
        $count_los_per_hospice = 0;
        $count = 0;
        $total_los_data = array();
        foreach ($los_per_hospice as $value) {
            if ($count == 0) {
                $total_los_data[$value['ordered_by']] = $value['total_patient_los'];
                ++$count;
            } else {
                if ($value['ordered_by'] != $current_hospice) {
                    $total_los_data[$value['ordered_by']] = $value['total_patient_los'];
                } else {
                    $total_los_data[$value['ordered_by']] += $value['total_patient_los'];
                }
            }
            $current_hospice = $value['ordered_by'];
        }

        foreach ($total_los_data as $key => $value) {
            $data = array(
                'date_saved' => $current_date,
                'hospiceID' => $key,
                'patient_total_los' => $value,
            );

            $data_to_be_inserted[] = $data;
		}
		print_me('insert_patient_los_per_hospice');
		print_me($data);
        $this->order_model->insert_patient_los_per_hospice($data_to_be_inserted);

        $patient_days_per_hospice = $this->order_model->get_total_patient_days_per_hospice();
        $data_to_be_inserted_patient_days = array();
        $current_date = date('Y-m-d');

        $current_hospice = 0;
        $count_patient_days_per_hospice = 0;
        $count = 0;
        $total_patient_days_data = array();
        foreach ($patient_days_per_hospice as $value) {
            if ($count == 0) {
                $total_patient_days_data[$value['ordered_by']] = $value['total_patient_days'];
                ++$count;
            } else {
                if ($value['ordered_by'] != $current_hospice) {
                    $total_patient_days_data[$value['ordered_by']] = $value['total_patient_days'];
                } else {
                    $total_patient_days_data[$value['ordered_by']] += $value['total_patient_days'];
                }
            }
            $current_hospice = $value['ordered_by'];
        }
        foreach ($total_patient_days_data as $key => $value) {
            $data = array(
                'date_saved' => $current_date,
                'hospiceID' => $key,
                'total_patient_days' => $value,
            );

            $data_to_be_inserted_patient_days[] = $data;
        }
        $this->order_model->insert_patient_days_per_hospice($data_to_be_inserted_patient_days);
    }

	public function test_has_customer_for_draft_statement($hospiceID) {
		$date_from = date('Y-m-01');
		$date_to = date("Y-m-t", strtotime($date_from));

		$has_customer = $this->billing_statement_model->has_customer_for_draft_statement($hospiceID, $date_from, $date_to);

		print_me($has_customer);
	}

	public function test_draft_statement_customers($hospiceID) {
		$service_date_from = '2021-04-01';
		$service_date_to = '2021-04-30';
		$customers  = $this->billing_statement_model->get_all_customer_for_draft_statement($hospiceID, 0, -1, $service_date_from, $service_date_to);
		print_me($customers);
	}

	public function create_draft_statement_for_a_hospice() {
		$hospiceID = 0; // hospiceID for the Hospice you want to create	<==== SET THIS ONE
		$statement_no = 0; // computed statement_no from (default_strtotime+hospiceID) <==== SET THIS ONE
		$date_from = date('Y-m-01'); // Service date from <==== SET THIS ONE
		$date_to = date("Y-m-t", strtotime($date_from)); // Service date to <==== SET THIS ONE

		$has_customer = $this->billing_statement_model->has_customer_for_draft_statement($hospiceID, $date_from, $date_to);

		// This if statement is to check if naa siya customer for the specified service date
		// If wala kay dili ma createan og draft_statement
		if (!empty($has_customer)) { 
			$data = array(
				"hospiceID" 		=> $hospiceID,
				"statement_no"		=> $statement_no,
				"service_date_from"	=> $date_from,
				"service_date_to"	=> $date_to,
				"notes"				=> "",
				"is_manual"			=> 0
			);
			$statement_draft = $this->billing_statement_model->insert_statement_draft($data);

			if($statement_draft) {
				$all_current_reconciliation = $this->billing_statement_model->get_all_current_reconciliation_balance_and_owe($hospiceID);
				$data_recon = array(
					"draft_reference" => $statement_no
				);
				foreach($all_current_reconciliation as $value) {
					$this->billing_statement_model->update_statement_reconciliation($value['acct_statement_reconciliation_id'], $data_recon);
				}
			} 
		}
	}
}