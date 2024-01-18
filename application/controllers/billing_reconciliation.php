<?php
Class billing_reconciliation extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		date_default_timezone_set('America/Los_Angeles');
		$this->load->model("billing_reconciliation_model");
		$this->load->model("billing_statement_model");
		$this->load->model("equipment_model");
		$this->load->model("order_model");
	}

	public function create_reconciliation_index($recon_id=0) {
		if($recon_id != 0) {
			$data['reconcile_details'] = $this->billing_reconciliation_model->get_reconcile_details($recon_id);
			$this->templating_library->set('title','Reconciliation Details');
		} else {
			$this->templating_library->set('title','Create Reconciliation');
		}
		
		$this->templating_library->set_view('pages/statement_reconciliation_create','pages/statement_reconciliation_create', $data);
		$this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
	}

	public function reconciliation_history() {
		$this->templating_library->set('title','Reconciliation History');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$this->templating_library->set_view('pages/statement_reconciliation','pages/statement_reconciliation', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function payment_history() {
		// $data['statement_paid_invoices'] = $this->billing_reconciliation_model->get_received_payments();

		// foreach($data['statement_paid_invoices'] as $value) {
		// 	$data['note_count'][] = $this->billing_reconciliation_model->count_invoice_comments($value['acct_statement_invoice_id']);
		// }
		$this->templating_library->set('title','Payment History');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$this->templating_library->set_view('pages/statement_payment_history_from_to','pages/statement_payment_history_from_to', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function load_more_payment_history($filter_from, $filter_to, $hospiceID=null, $page, $limit=10) {
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
			$reconcile_list = get_payment_history_list($filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'), $pagination_details);
			$results_info['total_records'] = $reconcile_list['total'];
			$query_result = $reconcile_list['data'];

			//Results
			$results_info['total_records'] = $results_info['total_records']==null? 0 : $results_info['total_records'];
			$total_pages = ($results_info['total_records'] > 0)? ceil($results_info['total_records'] / $limit) : 0;
			$results_info['total_pages'] = $total_pages;
			$data['pagination_details'] = $results_info;
			$data['statement_paid_invoices'] = $query_result;
			$data['invoices_reconciliation'] = array();
			foreach($data['statement_paid_invoices'] as $value) {
				$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['statement_no']);
				$data['invoices_reconciliation'][] = array(
					"credit"	=> !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'],
					"owe"		=> !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe']
				);
				$data['note_count'][] = $this->billing_reconciliation_model->count_invoice_comments($value['acct_statement_invoice_id']);
			}
			
		}
		echo json_encode($data);
		exit;
		
	}

	public function load_more_payment_history_hospice_side($filter_from, $filter_to, $hospiceID=null, $page, $limit=10) {
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
			$reconcile_list = get_payment_history_list($filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'), $pagination_details, true);
			$results_info['total_records'] = $reconcile_list['total'];
			$query_result = $reconcile_list['data'];

			//Results
			$results_info['total_records'] = $results_info['total_records']==null? 0 : $results_info['total_records'];
			$total_pages = ($results_info['total_records'] > 0)? ceil($results_info['total_records'] / $limit) : 0;
			$results_info['total_pages'] = $total_pages;
			$data['pagination_details'] = $results_info;
			$data['statement_paid_invoices'] = $query_result;
			$data['invoices_reconciliation'] = array();
			foreach($data['statement_paid_invoices'] as $value) {
				$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['statement_no']);
				$data['invoices_reconciliation'][] = array(
					"credit"	=> !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'],
					"owe"		=> !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe']
				);
				$data['note_count'][] = $this->billing_reconciliation_model->count_invoice_comments($value['acct_statement_invoice_id']);
			}
			
		}
		echo json_encode($data);
		exit;
		
	}

	public function get_total_payment_amount_payment_history($filter_from, $filter_to, $hospiceID=null) {
		if($this->input->is_ajax_request())
		{
			$filter_to = ($filter_to==0 || $filter_to=="")? $filter_from." 23:59:59" : $filter_to." 23:59:59";
			$total_payment_amount = get_total_payment_amount_history_list($filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
			echo json_encode($total_payment_amount);
		}
		exit;
	}

	public function payment_history_by_hospice($hospice_id=0) {
		// $hospice_id = 139;
		$data['hospice_details'] = $this->billing_reconciliation_model->get_hospice_details($hospice_id);
		$data['statement_paid_invoices'] = $this->billing_reconciliation_model->get_all_paid_invoices();
		$this->templating_library->set('title','Payment History');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		// $this->templating_library->set_view('pages/statement_payment_history_by_hospice','pages/statement_payment_history_by_hospice', $data);
		if (hasAcccessHospiceBilling($hospice_id)) {
			if ($hospice_id === $this->session->userdata("group_id")) {
				if ($this->session->userdata('account_type') != 'hospice_user') {
					$this->templating_library->set_view('pages/statement_payment_history_by_hospiceV2','pages/statement_payment_history_by_hospiceV2', $data);
				}
			}
		}
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function receive_payments($acct_statement_invoice_ids) {
		$selected_acct_statement_invoice_id_exploded = explode("-",$acct_statement_invoice_ids);
		$data['invoices_reconciliation'] = array();
		
		foreach($selected_acct_statement_invoice_id_exploded as $acct_statement_invoice_id) {
			$invoice_details = $this->billing_statement_model->get_statement_activity_details($acct_statement_invoice_id);
			$statement_no = $invoice_details[0]['statement_no'];
			$invoice_reconciliation = $this->billing_reconciliation_model->get_invoice_reconciliation_balance_and_owe_v2($statement_no);
			$data['invoices_reconciliation'][] = array(
				"credit"	=> !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'],
				"owe"		=> !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe']
			);
			$data['selected_invoices'][] = $this->billing_reconciliation_model->get_statement_activity_details($acct_statement_invoice_id)[0];
		}
		$this->templating_library->set('title','Account Invoice Inquiry');
		$this->templating_library->set_view('pages/statement_receive_payments_details','pages/statement_receive_payments_details', $data);
		$this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
	}

	public function insert_reconciliation_v2($acct_statement_invoice_id=0, $credit=0, $balance_owe=0, $hospiceID, $invoice_date="", $invoice_number=0, $payment_amount=0, $notes="", $balance_due=0) {
		if($this->input->is_ajax_request())
		{
			// $tempinvdate = explode("-", $invoice_date);
			// $invoice_date = $tempinvdate[2].$tempinvdate[0].$tempinvdate[1];
			$notes = str_replace('%20', ' ', $notes);
			// print_me($acct_statement_invoice_id);
			// print_me($credit);
			// print_me($balance_owe);
			// print_me($hospiceID);
			// print_me($invoice_date);
			// print_me($invoice_number);
			// print_me($payment_amount);
			// print_me($notes);
			// print_me($balance_due);

			$data = array(
            	"acct_statement_invoice_id"	=> $acct_statement_invoice_id == 'undefined' ? 0 : $acct_statement_invoice_id,
            	"credit"					=> $credit,
            	"balance_owe"				=> $balance_owe,
            	"hospiceID"					=> $hospiceID,
            	"invoice_date"				=> $invoice_date,
            	"invoice_no"				=> $invoice_number,
            	"payment_amount"			=> $payment_amount,
            	"notes"						=> $notes,
            	"balance_due"				=> $balance_due,
            	"invoice_reference"			=> null,
            );

			$is_insert_reconcile = true;
			if($acct_statement_invoice_id != 0) {
				$invoice = $this->billing_reconciliation_model->get_next_invoice_by_hospice($hospiceID, $acct_statement_invoice_id);
				if(!empty($invoice)) {
					$data['invoice_reference'] = $invoice['acct_statement_invoice_id'];
				}
			} else {
				// print_me($data);
				
				$cus_days = $this->billing_statement_model->get_customer_days($hospiceID);
				
				$daily_rate = $this->billing_statement_model->get_account($hospiceID)['daily_rate'];

				$totaltotal = $cus_days[0]['cusdays'] * $daily_rate;
				//print_me($totaltotal);
				$noncapped = $this->billing_statement_model->get_category_total($hospiceID, 2);
				$purchase = $this->billing_statement_model->get_category_total($hospiceID, 3);
				$total_noncapped = 0;
				$total_purchase = 0;
				foreach($noncapped as $value) {
					if($value['summary_pickup_date'] == "0000-00-00") {
						$now = time();
					} else {
						$now = strtotime($value['summary_pickup_date']);
					}
					$your_date = strtotime($value['pickup_date']);
					$datediff = $now - $your_date;
					$rounddatediff = round($datediff / (60 * 60 * 24));
					$total_noncapped += $value['purchase_price']*$rounddatediff;
				}

				foreach($purchase as $value) {
					$total_purchase += $value['purchase_price']*$value['equipment_value'];
				}

				$subtotal = $totaltotal + $total_noncapped + $total_purchase;

				if($credit > $subtotal) {
					$is_insert_reconcile = false;
					$this->response_code = 1;
	            	$this->response_message = 'Credit must not exceed to the subtotal of the current statement! ($'.number_format((float)$subtotal, 2, '.', '').')';
				}
				

			}
			if($is_insert_reconcile) {
				$reconciliation = $this->billing_reconciliation_model->insert_reconciliation($data);

	            if($reconciliation) {
					$this->response_code = 0;
		            $this->response_message = 'Successfully created reconciliation.';
	            }
			}
		}
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function insert_reconciliation($acct_statement_invoice_id=0, $credit=0, $balance_owe=0, $hospiceID, $invoice_date="", $invoice_number=0, $payment_amount=0, $notes="", $balance_due=0) {
		if($this->input->is_ajax_request())
		{
			// $tempinvdate = explode("-", $invoice_date);
			// $invoice_date = $tempinvdate[2].$tempinvdate[0].$tempinvdate[1];
			$notes = str_replace('%20', ' ', $notes);
			// print_me($acct_statement_invoice_id);
			// print_me($credit);
			// print_me($balance_owe);
			// print_me($hospiceID);
			// print_me($invoice_date);
			// print_me($invoice_number);
			// print_me($payment_amount);
			// print_me($notes);
			// print_me($balance_due);

			if ($invoice_date == "empty" || $invoice_date == "") {
				$invoice_date = "0000-00-00";
			}

			$data_reconcile = array(
            	"acct_statement_invoice_id"	=> $acct_statement_invoice_id == 'undefined' ? 0 : $acct_statement_invoice_id,
            	"credit"					=> $credit,
            	"balance_owe"				=> $balance_owe,
            	"hospiceID"					=> $hospiceID,
            	"invoice_date"				=> $invoice_date,
            	"invoice_no"				=> $invoice_number,
            	"payment_amount"			=> $payment_amount,
            	"notes"						=> $notes,
            	"balance_due"				=> $balance_due,
            	"invoice_reference"			=> null,
				"draft_reference"			=> null
            );

			$is_insert_reconcile = true;
			// if($acct_statement_invoice_id != 0) {
			// 	$invoice = $this->billing_reconciliation_model->get_next_invoice_by_hospice($hospiceID, $acct_statement_invoice_id);
			// 	if(!empty($invoice)) {
			// 		$data_reconcile['invoice_reference'] = $invoice['acct_statement_invoice_id'];
			// 	}
			// } else {
				// print_me($data);
				$totaltotal = 0;
				$total_noncapped = 0;
				$total_purchase = 0;
				
				$data['statement_bill'] = $this->billing_statement_model->get_statement_bill_by_hospice($hospiceID);
				$service_date_from = $data['statement_bill']['service_date_from'];
				$service_date_to = date("Y-m-t", strtotime($service_date_from));

				$hospice_data = $this->billing_statement_model->get_account($hospiceID);
				$data['customers'] = $this->billing_statement_model->get_all_customer($hospiceID, 0, -1, $service_date_from, $service_date_to);

				$cusdayslooptotal = 0;
				foreach($data['customers']['result'] as $data_value) {
					$customer_orders = $this->billing_statement_model->customer_order_list_for_draft_statement($hospiceID, $data_value['patientID'], $service_date_from, $service_date_to);
					$cus_days_los = $this->billing_statement_model->get_customer_days_length_of_stay($data_value['patientID'], $service_date_from, $service_date_to);
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
						$item['hospiceID'] = $hospiceID;
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
							$your_date = strtotime($cus_value['actual_order_date']);
							$your_date_v2 = new DateTime($cus_value['actual_order_date']);
							$new_pickupdate = strtotime(date("Y-m-t", strtotime($cus_value['actual_order_date'])));
							$new_pickupdate_v2 = new DateTime(date("Y-m-t", strtotime($cus_value['actual_order_date'])));
							$temporary_service_date_to = date("Y-m-t", strtotime($service_date_from));
							$isSummaryPickupDate = false;
							
							if($cus_value['summary_pickup_date'] != "0000-00-00" && ($cus_value['summary_pickup_date'] <= $temporary_service_date_to)) {
								
								if ($cus_value['pickup_discharge_date'] !== '0000-00-00' && $cus_value['pickup_discharge_date'] !== null) {
									$now = strtotime($cus_value['pickup_discharge_date']);
									$now_v2 = new DateTime($cus_value['pickup_discharge_date']);
								} else {
									$now = strtotime($cus_value['summary_pickup_date']);
									$now_v2 = new DateTime($cus_value['summary_pickup_date']);
								}
								$isSummaryPickupDate = true;
							} else {
								// $now = time();
								$current_date = date('Y-m-d');
								$now_v2 = new DateTime($current_date);
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
								} else {
									$temponewdate = strtotime($service_date_from);
									$temponewdate_v2 = new DateTime(date("Y-m-01"));
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
									// $total_noncapped += $cus_total;
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
											// $total_noncapped += $cus_total;
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
							$total_noncapped += $cus_total;
							
						} else {
							$cus_quantity = $cus_value['equipment_value'];
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
							$total_purchase += $cus_total;
						}
						
					}
				} // End of Insert Statement Order

				$totaltotal = $cusdayslooptotal * $hospice_data['daily_rate'];
				$subtotal = $totaltotal + $total_noncapped + $total_purchase;

				if($credit > $subtotal) {
					$is_insert_reconcile = false;
					$this->response_code = 1;
					// $this->response_message = 'Totaltotal = '.number_format((float)$totaltotal, 2, '.', '').' -- total_noncapped'.number_format((float)$total_noncapped, 2, '.', '').' -- total_purchase'.number_format((float)$total_purchase, 2, '.', '');
	            	$this->response_message = 'Credit must not exceed to the subtotal of the current statement! ($'.number_format((float)$subtotal, 2, '.', '').')';
				}
			// }

			if($is_insert_reconcile) {
				$draft = $this->billing_reconciliation_model->get_oldest_draft_by_hospice($hospiceID);

				if (!empty($draft)) {
					$data_reconcile['draft_reference'] = $draft['statement_no'];
				}
				$reconciliation = $this->billing_reconciliation_model->insert_reconciliation($data_reconcile);

	            if($reconciliation) {
					$this->response_code = 0;
		            $this->response_message = 'Successfully created reconciliation.';
	            }
			}
		}
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	// public function insert_reconciliation_v2() {
	// 	$acct_statement_invoice_id = $_POST['reconciliation_invoice_id'];
	// 	$credit = $_POST['recon_credit'];
	// 	$balance_owe = $_POST['recon_amount_owe'];
	// 	$hospiceID = $_POST['recon_account_id'];
	// 	$invoice_date = $_POST['recon_invoice_date'];
	// 	$invoice_number = $_POST['recon_invoice_number'];
	// 	$payment_amount = $_POST['recon_payment_amount'];
	// 	$notes = $_POST['recon_notes'];
	// 	$balance_due = $_POST['recon_balance_due'];

	// 	$data_get = $this->input->get();
	// 	print_me($data_get);
	// 	$data = array(
 //        	"acct_statement_invoice_id"	=> $acct_statement_invoice_id,
 //        	"credit"					=> $credit,
 //        	"balance_owe"				=> $balance_owe,
 //        	"hospiceID"					=> $hospiceID,
 //        	"invoice_date"				=> $invoice_date,
 //        	"invoice_no"				=> $invoice_number,
 //        	"payment_amount"			=> $payment_amount,
 //        	"notes"						=> $notes,
 //        	"balance_due"				=> $balance_due,
 //        	"invoice_reference"			=> null,
 //        );

	// 	$is_insert_reconcile = true;
	// 	if($acct_statement_invoice_id != 0) {
	// 		$invoice = $this->billing_reconciliation_model->get_next_invoice_by_hospice($hospiceID, $acct_statement_invoice_id);
	// 		if(!empty($invoice)) {
	// 			$data['invoice_reference'] = $invoice['acct_statement_invoice_id'];
	// 		}
	// 	} else {
	// 		// print_me($data);
			
	// 		$cus_days = $this->billing_statement_model->get_customer_days($hospiceID);
			
	// 		$daily_rate = $this->billing_statement_model->get_account($hospiceID)['daily_rate'];

	// 		$totaltotal = $cus_days[0]['cusdays'] * $daily_rate;
	// 		//print_me($totaltotal);
	// 		$noncapped = $this->billing_statement_model->get_category_total($hospiceID, 2);
	// 		$purchase = $this->billing_statement_model->get_category_total($hospiceID, 3);
	// 		$total_noncapped = 0;
	// 		$total_purchase = 0;
	// 		foreach($noncapped as $value) {
	// 			if($value['summary_pickup_date'] == "0000-00-00") {
	// 				$now = time();
	// 			} else {
	// 				$now = strtotime($value['summary_pickup_date']);
	// 			}
	// 			$your_date = strtotime($value['pickup_date']);
	// 			$datediff = $now - $your_date;
	// 			$rounddatediff = round($datediff / (60 * 60 * 24));
	// 			$total_noncapped += $value['purchase_price']*$rounddatediff;
	// 		}

	// 		foreach($purchase as $value) {
	// 			$total_purchase += $value['purchase_price']*$value['equipment_value'];
	// 		}

	// 		$subtotal = $totaltotal + $total_noncapped + $total_purchase;

	// 		if($credit > $subtotal) {
	// 			$is_insert_reconcile = false;
	// 			$this->response_code = 1;
 //            	$this->response_message = 'Credit must not exceed to the subtotal of the current statement! ($'.number_format((float)$subtotal, 2, '.', '').')';
	// 		}
			

	// 	}
	// 	if($is_insert_reconcile) {
	// 		$reconciliation = $this->billing_reconciliation_model->insert_reconciliation($data);

 //            if($reconciliation) {
	// 			$this->response_code = 0;
	//             $this->response_message = 'Successfully created reconciliation.';
 //            }
	// 	}
	// }

	public function receive_payment($acct_statement_invoice_id,$receive_date,$payment_type,$payment_amount,$received_by,$check_number) {
		if($this->input->is_ajax_request())
		{
			$owe_and_credit = $this->billing_reconciliation_model->get_invoice_reconciliation_balance_and_owe($acct_statement_invoice_id);
			// print_me($owe_and_credit);
			$owe = !empty($owe_and_credit) &&  $owe_and_credit['owe'] != null  ? $owe_and_credit['owe'] : 0;
			$credit = !empty($owe_and_credit) && $owe_and_credit['credit'] != null ? $owe_and_credit['credit'] : 0;
			$received_by = str_replace('%20', ' ', $received_by);
			$data = array(
            	"receive_date"		=> $receive_date,
            	"payment_type"		=> $payment_type,
            	"payment_amount"	=> (float) $payment_amount,
            	"check_number"		=> $check_number,
            	"received_by"		=> $received_by,
            	"receive_status"	=> 1,
            	"balance_owe"		=> $owe,
            	"credit"			=> $credit
            );
            $this->response_code = 1;
            // $this->response_code = 0;
            $this->response_message = 'Error receiving payment!';
            $update_statement_bill_invoice = $this->billing_reconciliation_model->update_statement_bill_invoice($acct_statement_invoice_id, $data);
            

            if($update_statement_bill_invoice) {
            	$data = array(
					"acct_statement_invoice_id"	=> $acct_statement_invoice_id,
					"receive_date"				=> $receive_date,
	            	"payment_type"				=> $payment_type,
	            	"payment_amount"			=> (float) $payment_amount,
	            	"check_number"				=> $check_number,
	            	"received_by"				=> $received_by
				);
				$insert_received_payment = $this->billing_reconciliation_model->insert_received_payment($data);

				$this->response_code = 0;
	        	$this->response_message = 'Successfully received payment.';
            }
		}
		echo json_encode(array(
			'data' => $data,
			'payment_data' => $payment_amount,
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function load_more_reconciliation_list($filter_from, $filter_to, $hospiceID=null, $page, $limit=10) {
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
			// $current_date = 
			$reconcile_list = get_reconciliation_list($filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'), $pagination_details);
			$results_info['total_records'] = $reconcile_list['total'];
			$query_result = $reconcile_list['data'];

			//Results
			$results_info['total_records'] = $results_info['total_records']==null? 0 : $results_info['total_records'];
			$total_pages = ($results_info['total_records'] > 0)? ceil($results_info['total_records'] / $limit) : 0;
			$results_info['total_pages'] = $total_pages;
			$data['pagination_details'] = $results_info;
			$data['reconciliation_list'] = $query_result;
			
		}
		echo json_encode($data);
		exit;
		
	}

	public function load_more_archive_list($filter_from, $filter_to, $hospiceID=null, $page, $limit=10) {
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
			$current_date = 
			$reconcile_list = get_archive_list($filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'), $pagination_details);
			$results_info['total_records'] = $reconcile_list['total'];
			$query_result = $reconcile_list['data'];

			//Results
			$results_info['total_records'] = $results_info['total_records']==null? 0 : $results_info['total_records'];
			$total_pages = ($results_info['total_records'] > 0)? ceil($results_info['total_records'] / $limit) : 0;
			$results_info['total_pages'] = $total_pages;
			$data['pagination_details'] = $results_info;
			$data['statement_paid_invoices'] = $query_result;
			
		}
		echo json_encode($data);
		exit;
	}

	public function statement_archive() {
		// $data['statement_paid_invoices'] = $this->billing_reconciliation_model->get_all_paid_invoices();
		$this->templating_library->set('title','Payment Archive');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$this->templating_library->set_view('pages/statement_archive_from_to','pages/statement_archive_from_to', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function statement_archive_on_change_year($year) {
		if($this->input->is_ajax_request())
		{
			$data['statement_paid_invoices'] = $this->billing_reconciliation_model->get_all_paid_invoices($year);
		}
		echo json_encode($data);
		exit;
	}

	public function statement_pending_payments($hospice_id=0) {
		$data['pending_payments'] = $this->billing_reconciliation_model->get_pending_payments_by_hospice(139);
		$this->templating_library->set('title','Pending Payments');
		$this->templating_library->set_view('pages/statement_pending_payments','pages/statement_pending_payments', $data);
		$this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
	}

	public function revert_payment_history($acct_statement_received_payment_id, $acct_statement_invoice_id) {
		if($this->input->is_ajax_request())
		{
			$fname = $this->session->userdata('firstname');
            $lname = $this->session->userdata('lastname'); 
            $lname_cut = substr($this->session->userdata('lastname'),0,1);
            $date_today = date('Y-m-d');

            $this->response_code = 1;
            $this->response_message = 'Error Reverting Invoice!';
            $data = array(
            	"reverted_by"		=> $fname.' '.$lname,
            	"reverted_date"		=> $date_today,
            	"is_reverted"		=> 1,
            	"payment_amount"	=> 0
            	// "payment_date"		=> null
            );
            $update_statement_bill_received_payment = $this->billing_reconciliation_model->update_statement_bill_received_payment($acct_statement_received_payment_id, $data);
			

            if($update_statement_bill_received_payment) {
            	$data = array(
	            	"reverted_by"		=> $fname.' '.$lname,
	            	"reverted_date"		=> $date_today,
	            	"is_reverted"		=> 1,
	            	"payment_code"		=> null,
	            	"receive_status"	=> 0,
	            	"payment_amount"	=> null
	            );
	            $update_statement_bill_invoice = $this->billing_reconciliation_model->update_statement_bill_invoice($acct_statement_invoice_id, $data);

				$this->response_code = 0;
	        	$this->response_message = 'Successfully Reverted Invoice.';
            }
            

		}
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
		exit;
	}

	public function get_draft_reconciliation_credit_and_owe($draft_id) {
		if($this->input->is_ajax_request())
		{
			$draft_reconciliation = $this->billing_reconciliation_model->get_draft_reconciliation_balance_and_owe($draft_id);
		}
		echo json_encode($draft_reconciliation);

	}

	public function get_invoice_reconciliation_credit_and_owe($invoice_id) {
		if($this->input->is_ajax_request())
		{
			$invoice_details = $this->billing_statement_model->get_statement_activity_details($invoice_id);
			$statement_no = $invoice_details[0]['statement_no'];
			$data['invoice_reconciliation_comments'] = '';
			$comments = $this->billing_reconciliation_model->get_current_reconciliation_notes_by_invoice_reference($statement_no);
			$counter = 0;
			foreach($comments as $value) {
				if ($counter == 0) {
					$data['invoice_reconciliation_comments'] = $value['notes'];
				} else {
					$data['invoice_reconciliation_comments'] = $data['invoice_reconciliation_comments'].', '.$value['notes'];
				}
				$counter++;
			}

			// $invoice_reconciliation = $this->billing_reconciliation_model->get_invoice_reconciliation_balance_and_owe($invoice_id);
			$data['invoice_reconciliation'] = $this->billing_reconciliation_model->get_invoice_reconciliation_balance_and_owe_v2($statement_no);
		}
		echo json_encode($data);

	}

	public function get_current_reconciliation_credit_and_owe($hospice_id) {
		if($this->input->is_ajax_request())
		{
			$data['invoice_reconciliation_comments'] = '';
			$comments = $this->billing_reconciliation_model->get_current_reconciliation_notes($hospice_id);
			$counter = 0;
			foreach($comments as $value) {
				if ($counter == 0) {
					$data['invoice_reconciliation_comments'] = $value['notes'];
				} else {
					$data['invoice_reconciliation_comments'] = $data['invoice_reconciliation_comments'].', '.$value['notes'];
				}
				$counter++;
			}
			
			$data['invoice_reconciliation'] = $this->billing_reconciliation_model->get_current_reconciliation_balance_and_owe($hospice_id);
		}
		echo json_encode($data);

	}

	public function get_current_reconciliation_credit_and_owe_by_reference($draft_id) {
		if($this->input->is_ajax_request())
		{
			$draft_details = $this->billing_statement_model->get_draft_details($draft_id);
			$statement_no = $draft_details['statement_no'];
			$data['invoice_reconciliation_comments'] = '';
			$comments = $this->billing_reconciliation_model->get_current_reconciliation_notes_by_reference($statement_no);
			$counter = 0;
			foreach($comments as $value) {
				if ($counter == 0) {
					$data['invoice_reconciliation_comments'] = $value['notes'];
				} else {
					$data['invoice_reconciliation_comments'] = $data['invoice_reconciliation_comments'].', '.$value['notes'];
				}
				$counter++;
			}
			
			$data['invoice_reconciliation'] = $this->billing_reconciliation_model->get_current_reconciliation_balance_and_owe_by_reference($statement_no);
		}
		echo json_encode($data);

	}

	public function insert_invoice_comments()
	{
		$this->form_validation->set_rules('comment','Comment','required|trim');
		$post_data = $this->input->post();

		$array_data = array(
			'comment'		 => $post_data['comment'],
			'acct_statement_invoice_id' => $post_data['invoice_id'],
			'userID'		 => $post_data['commented_by'],
			'userName'       => $post_data['commented_by_name']
		);

		if($this->form_validation->run() == TRUE)
		{
			$this->billing_reconciliation_model->insert_invoice_comments($array_data);

			//** For the response (include_bottom.php)
			$this->response_code 		= 0;
			$this->response_message		= "Note inserted successfully.";
		}
		else
		{
			$this->response_code 		= 1;
			$this->response_message		= "Please put Note if necessary.";
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function get_comments($acct_statement_invoice_id, $statement_no, $hospiceID, $is_required="")
	{
		$data['is_required'] = $is_required;
		$data['account_number'] = $account_number;
		$data['account'] = $account;
		$data['statement_no'] = $statement_no;
		$data['acct_statement_invoice_id'] = $acct_statement_invoice_id;
		$data['hospice_details'] = $this->billing_reconciliation_model->get_hospice_details($hospiceID);
		$data['comments'] = $this->billing_reconciliation_model->get_all_comments($acct_statement_invoice_id);
		$this->load->view('pages/statement_comment_thread', $data);
	}

	public function insert_statement_letter_notes()
	{
		$this->form_validation->set_rules('comment','Comment','required|trim');
		$post_data = $this->input->post();

		$array_data = array(
			'note'	=> $post_data['comment'],
			'hospiceID'	=> $post_data['hospiceID'],
			'userID'	=> $post_data['commented_by'],
			'userName'	=> $post_data['commented_by_name']
		);

		if($this->form_validation->run() == TRUE)
		{
			$this->billing_reconciliation_model->insert_statement_letter_note($array_data);

			//** For the response (include_bottom.php)
			$this->response_code 		= 0;
			$this->response_message		= "Note inserted successfully.";
		}
		else
		{
			$this->response_code 		= 1;
			$this->response_message		= "Please put Note if necessary.";
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function get_statement_letter_notes($hospiceID, $is_required="")
	{
		$data['is_required'] = $is_required;
		$data['hospice_details'] = $this->billing_reconciliation_model->get_hospice_details($hospiceID);
		$data['comments'] = $this->billing_reconciliation_model->get_statement_letter_notes($hospiceID);
		$this->load->view('pages/statement_letter_note_thread', $data);
	}

	public function cancel_reconcilation($reconcile_id) {
		if($this->input->is_ajax_request())
		{
			$this->response_code = 1;
			$this->response_message = 'Failed to cancel reconciliation.';

			$reconcile = $this->billing_reconciliation_model->delete_reconciliation($reconcile_id);

			if ($reconcile) {
				$this->response_code = 0;
				$this->response_message = 'Successfully canceled reconciliation.';
			}
			

		}
		echo json_encode(array(
			'error' => $this->response_code,
			'message' => $this->response_message,
		));
		exit;
	}

	// public function migrate_received_payments(){
	// 	$received_payments = $this->billing_reconciliation_model->get_all_paid_invoices();
	// 	foreach($received_payments as $value) {
	// 		$data = array(
	// 			"acct_statement_invoice_id"	=> $value['acct_statement_invoice_id'],
	// 			"receive_date"				=> $value['receive_date'],
 //            	"payment_type"				=> $value['payment_type'],
 //            	"payment_amount"			=> $value['payment_amount'],
 //            	"check_number"				=> $value['check_number'],
 //            	"received_by"				=> $value['received_by']
	// 		);
	// 		$migrate = $this->billing_reconciliation_model->insert_received_payment($data);
	// 		print_me($migrate);
	// 	}
	// }
}