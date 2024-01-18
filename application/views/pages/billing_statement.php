<?php
Class billing_statement extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		$this->load->model("billing_statement_model");
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
		$this->templating_library->set_view('pages/searched_account_grid','pages/searched_account_grid', $data);
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

	public function statement_bill($hospice_id) {
		$data['statement_bill'] = $this->billing_statement_model->get_statement_bill_by_hospice($hospice_id);
		$data['statement_bill_draft'] = $this->billing_statement_model->get_statement_bill_draft_by_hospice($hospice_id, "DESC");
		$data['statement_bill_invoice'] = $this->billing_statement_model->get_statement_bill_invoice_by_hospice($hospice_id, "DESC");
		// print_me($date['statement_bill_draft']);
		$data['account'] = $this->billing_statement_model->get_account($hospice_id);
		// $data['customer_days'] =$this->billing_statement_model->get_customer_days($hospice_id);
		// print_me($data['customer_days']);
		$service_date_from = $data['statement_bill']['service_date_from'];
		$service_date_to = date("Y-m-t", strtotime($service_date_from));
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
		$this->templating_library->set_view('pages/statement_bill','pages/statement_bill', $data);
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

	public function statement_draft_load_more ($draft_id,$start=0, $limit=10) {
		// $response = array("customers_orders" => array());

		if($this->input->is_ajax_request())
		{
			$data['statement_bill'] = $this->billing_statement_model->get_draft_details($draft_id);
			$hospice_id = $data['statement_bill']['hospiceID'];
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
		$this->templating_library->set_view('pages/statement_draft','pages/statement_draft', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
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
			// print_me($limit);
            if ($result['totalCount'] > 0) {
				$prev_hospiceID = 0;
                foreach ($result['result'] as $key => $value) {
                	$data_temp = array();
					$total_balance = 0;
					$customer_days = $this->billing_statement_model->get_customer_days($value['hospiceID']);
					// print_me($draft['daily_rate']);
					$noncapped = $this->billing_statement_model->get_category_total($value['hospiceID'], 2);
					$purchase = $this->billing_statement_model->get_category_total($value['hospiceID'], 3);
					$total_noncapped = 0;
					$total_purchase = 0;
					// print_me($temp);
					foreach($noncapped as $non) {
						if($non['summary_pickup_date'] == "0000-00-00") {
							$now = time();
						} else {
							$now = strtotime($non['summary_pickup_date']);
						}
						$your_date = strtotime($non['pickup_date']);
						$datediff = $now - $your_date;
						$rounddatediff = round($datediff / (60 * 60 * 24));
						$total_noncapped += $non['purchase_price']*$rounddatediff;
					}

					foreach($purchase as $pur) {
						$total_purchase += $pur['purchase_price']*$pur['equipment_value'];
					}

					$draft_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_draft($value['acct_statement_draft_id']);

					$total_balance += (float) $customer_days[0]['cus_days'] * (float) $value['daily_rate'];
					$total_balance += $total_noncapped;
					$total_balance += $total_purchase;
					
					$total_balance -=  !isset($draft_reconciliation['credit']) ? 0 : (float) $draft_reconciliation['credit']; 	// Deduct
					$total_balance +=  !isset($draft_reconciliation['owe']) ? 0 : (float) $draft_reconciliation['owe']; 		// Add

					$create_inv = 1;
					$is_all_order_confirmed = $this->billing_statement_model->is_all_order_confirmed_v2($value['hospiceID']);
					if(!empty($is_all_order_confirmed)) {
						$create_inv = 0;
					}

					$disable_draft = 1;
					if($key > 0) {
						if($result['result'][$key-1]['hospiceID'] == $value['hospiceID']) {
							$disable_draft = 0;
						} 
						// else {
						// 	$data['statement_draft'][$key-1]['is_disable'] = 0;
						// }
						
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
						"total_balance"				=> $total_balance,
						"is_manual"					=> $value['is_manual'],
						"is_disable"				=> $disable_draft,
						"is_create_inv"				=> $create_inv
					);

                	$disable_checkbox = "";
                    $disable_checkbox_cursor = "";
                    $select_all_tag = "";
                    $is_underline = "";
                    $is_popover = "";
                    if($data_temp['is_disable'] == 0) {
                        $disable_checkbox = "disabled";
                        $disable_checkbox_cursor = "cursor: not-allowed !important;";
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

                    $value['checkbox'] = '<label class="i-checks data_tooltip" style="'.$disable_checkbox_cursor.'"> '.
                    						'<input type="checkbox"  class="create-invoice '.$select_all_tag.'" '.
                    						'data-draft-id="'.$value['acct_statement_draft_id'].'" '.$disable_checkbox.'/>'.
                    						'<i></i>'.
                    						'</label>';
                    $value['hospice_name'] = '<span style="'.$is_underline.'">'.$value['hospice_name'].'</span>';
                    $value['service_date'] =  date('m/d/Y', strtotime($value['service_date_from']))." - ".date('m/d/Y', strtotime($value['service_date_to']));
                    $value['statement_no'] = '<div style="cursor: pointer" class="view_statement_bill_details" data-draft-id="'.$value['acct_statement_draft_id'].'" '.
                    						'data-hospice-id="'.$value['hospiceID'].'">'.substr($value['statement_no'],3, 10).
                    						'</div>';

                    $value['balance_due'] = number_format((float)$data_temp['total_balance'], 2, '.', '');

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
	public function statement_activity() {
		$data['statement_activity'] = $this->billing_statement_model->get_all_statement_activity($this->session->userdata('user_location'));
		$data['invoices_reconciliation'] = array();

		foreach($data['statement_activity'] as $value) {
			$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['acct_statement_invoice_id']);
			$data['invoices_reconciliation'][] = array(
				"credit"	=> !isset($invoice_reconciliation['credit']) ? 0 : (float) $invoice_reconciliation['credit'],
				"owe"		=> !isset($invoice_reconciliation['owe']) ? 0 : (float) $invoice_reconciliation['owe']
			);
		}
		
		// print_me($data['statement_activity']);
		$this->templating_library->set('title','Activity Statements');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/statement_activity','pages/statement_activity', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
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
			foreach($temp as $value) {
				if($value['equip_is_package'] == 0 && $value['is_package'] == 1) {
					continue;
				}

				if($category_id == 2) {
					$your_date = strtotime($value['pickup_date']);
					$new_pickupdate = strtotime(date("Y-m-t", strtotime($value['pickup_date'])));
					$temporary_service_date_to = date("Y-m-t", strtotime($service_date_from));
					$isSummaryPickupDate = false;

					if($value['summary_pickup_date'] == "0000-00-00") {
						$now = time();
					} else {
						// $now = strtotime($value['summary_pickup_date']);
						$isSummaryPickupDate = true;
					}
					if( ((date("Y", strtotime($temporary_service_date_to)) > date("Y", $your_date)) && $isSummaryPickupDate == true) || (((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) < date("m", $your_date))) && $isSummaryPickupDate == true) ) {
						$now = $new_pickupdate;
						$datediff = $now - $your_date;
					} else {
						if(date("m", strtotime($temporary_service_date_to)) == date("m", $your_date)) {
							$temponewdate = $your_date;
						} else {
							$temponewdate = strtotime(date("Y-m-01"));
						}
						// $temponewdate = strtotime(date("Y-m-01", time()));
						$datediff = $now - $temponewdate;
					}

					// $datediff = $now - $your_date;
					$rounddatediff = round($datediff / (60 * 60 * 24)) + 1;

					if($value['daily_rate'] == 0 || $value['daily_rate'] == null) {
						$rounddatediff = 1;
					}
					if($value['equipmentID'] == 176) {
						$rounddatediff = 1;
					}

					$dailratetemporary = 0;
  					//New Calculation 11/15/19 =======> START
  					if($value['daily_rate'] == 0 || $value['daily_rate'] == null) {
  						// $dailratetemporary = $value['monthly_rate'];
  						$total += $value['monthly_rate'];
  					} else {
  						$temptotaldailyrate = $rounddatediff * $value['daily_rate'];
  						if($temptotaldailyrate > $value['monthly_rate']) {
  							if($value['monthly_rate'] == 0 || $value['monthly_rate'] == null) {
  								$total += $temptotaldailyrate;
  							} else {
  								$total += $value['monthly_rate'];
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
					$quan = $value['equipment_value'];
					if($value['equipment_quantity'] !== "" && $value['equipment_quantity'] !== null) {
						$quan = $value['equipment_quantity'];
					}
					$total += $value['purchase_price']*$quan;
				} else {
					$total += $value['purchase_price'];
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
		if($this->input->is_ajax_request())
		{
			$statement_bill = $this->billing_statement_model->get_draft_details($draft_id);
			$hospice_id = $statement_bill['hospiceID'];
			$service_date_from = $statement_bill['service_date_from'];
			$service_date_to = date("Y-m-t", strtotime($service_date_from));
			$temp = $this->billing_statement_model->get_category_total($hospice_id, $category_id, $service_date_from, $service_date_to);
			$total = 0;
			// print_me($temp);
			foreach($temp as $value) {
				if($value['equip_is_package'] == 0 && $value['is_package'] == 1) {
					continue;
				}

				if($category_id == 2) {
					$your_date = strtotime($value['pickup_date']);
					$new_pickupdate = strtotime(date("Y-m-t", strtotime($value['pickup_date'])));
					$temporary_service_date_to = date("Y-m-t", strtotime($service_date_from));
					$isSummaryPickupDate = false;

					if($value['summary_pickup_date'] == "0000-00-00") {
						$now = time();
					} else {
						// $now = strtotime($value['summary_pickup_date']);
						$isSummaryPickupDate = true;
					}
					if( ((date("Y", strtotime($temporary_service_date_to)) > date("Y", $your_date)) && $isSummaryPickupDate == true) || (((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) < date("m", $your_date))) && $isSummaryPickupDate == true) ) {
						$now = $new_pickupdate;
						$datediff = $now - $your_date;
					} else {
						if(date("m", strtotime($temporary_service_date_to)) == date("m", $your_date)) {
							$temponewdate = $your_date;
						} else {
							$temponewdate = strtotime(date("Y-m-01"));
						}
						// $temponewdate = strtotime(date("Y-m-01", time()));
						$datediff = $now - $temponewdate;
					}

					// $datediff = $now - $your_date;
					$rounddatediff = round($datediff / (60 * 60 * 24)) + 1;

					if($value['daily_rate'] == 0 || $value['daily_rate'] == null) {
						$rounddatediff = 1;
					}
					if($value['equipmentID'] == 176) {
						$rounddatediff = 1;
					}

					$dailratetemporary = 0;
  					//New Calculation 11/15/19 =======> START
  					if($value['daily_rate'] == 0 || $value['daily_rate'] == null) {
  						// $dailratetemporary = $value['monthly_rate'];
  						$total += $value['monthly_rate'];
  					} else {
  						$temptotaldailyrate = $rounddatediff * $value['daily_rate'];
  						if($temptotaldailyrate > $value['monthly_rate']) {
  							if($value['monthly_rate'] == 0 || $value['monthly_rate'] == null) {
  								$total += $temptotaldailyrate;
  							} else {
  								$total += $value['monthly_rate'];
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
					$quan = $value['equipment_value'];
					if($value['equipment_quantity'] !== "" && $value['equipment_quantity'] !== null) {
						$quan = $value['equipment_quantity'];
					}
					$total += $value['purchase_price']*$quan;
				} else {
					$total += $value['purchase_price'];
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
					$data['customer_days'] = 0;
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
					"draft_reference" => $statement_draft
				);
				foreach($all_current_reconciliation as $value) {
					$this->billing_statement_model->update_statement_reconciliation($value['acct_statement_reconciliation_id'], $data_recon);
				}
			} 
		}

		$this->billing_statement_model->remove_statement_bill();
		foreach($all_hospices_with_bill_details as $hospice) {
			$data = array();
			$temp_statement_no = strtotime(date('Y-m-d H:i:s'));
            $statement_no = $temp_statement_no + $hospice['hospiceID'];
            $service_date = date('Y-m-01');
            $data = array(
            	"statement_no"		=> $statement_no,
            	"hospiceID"			=> $hospice['hospiceID'],
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
				"draft_reference" => $statement_draft
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

	public function create_invoice_v2($selected_draft_ids) {
		// if($this->input->is_ajax_request())
		// {
			$selected_draft_id_exploded = explode("-",$selected_draft_ids);
			// print_me($selected_draft_id_exploded);
			foreach($selected_draft_id_exploded as $draft_id) {
				$draft_statement = $this->billing_statement_model->get_draft_details($draft_id);
				// print_me($draft_statement);
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
					$temp_invoice_no = (int) strtotime(date('Y-m-d H:i:s A'));
            		$invoice_no = $temp_statement_no + $draft_statement['hospiceID'];
					$data_create_invoice = array(
						"hospiceID" 		=> $draft_statement['hospiceID'],
						"statement_no"		=> $draft_statement['statement_no'],
						"invoice_no"		=> $invoice_no,
						"service_date_from"	=> $draft_statement['service_date_from'],
						"service_date_to"	=> $draft_statement['service_date_to'],
						"due_date"			=> $due_date,
						"customer_days"		=> $cus_days[0]['cus_days'] == null ? 0 : $cus_days[0]['cus_days'],
						"total"				=> $total_invoice,
						"non_cap"			=> $non_cap,
						"purchase_item"		=> $purchase_price,
						"credit"			=> 0,
						"balance_owe"		=> 0,
					);
					// print_me($data_create_invoice);
					$invoice_statement = $this->billing_statement_model->insert_statement_invoice($data_create_invoice);

					if($invoice_statement) {
						//Move Reconciliation to Invoice
						$data_recon = array(
							"invoice_reference"	=> $invoice_statement
						);
						$this->billing_statement_model->update_statement_reconciliation_by_draft($draft_id, $data_recon);

						//Remove Draft Statement
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
									"key_desc"					=> $cus_value['key_desc'],
									"delivery_date"				=> $cus_value['pickup_date'],
									"pickup_date"				=> $cus_value['summary_pickup_date'],
									"quantity"					=> $cus_quantity,
									"cap"						=> $cus_cap,
									"purchase_item"				=> $cus_purchase_item,
									"non_cap"					=> $cus_noncap,
									"total"						=> $cus_total,
									"cus_days"					=> $value['patient_days'],
									"daily_rate"				=> $hospice_data['daily_rate'],
									"addressID"					=> $cus_value['addressID'],
									"original_activity_typeid"	=> $cus_value['original_activity_typeid']
								);
								$this->billing_statement_model->insert_statement_order($statement_order_data);
							}
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
		$data['statement_letters'] = $this->billing_statement_model->get_all_statement_letter($this->session->userdata('user_location'));
		$this->templating_library->set('title','Account Statement Letters');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/statement_letter','pages/statement_letter', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function statement_letter_details($acct_statement_letter_id) {
		$data['statement_letter'] = $this->billing_statement_model->get_statement_letter_details($acct_statement_letter_id);
		$this->templating_library->set('title','Account Statement Letter Details');
		$this->templating_library->set_view('pages/statement_letter_details','pages/statement_letter_details', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
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
			$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['acct_statement_invoice_id']);
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

	public function statement_bill_by_hospice($hospice_id=0) {
		$data['hospice_id'] = $hospice_id;
		$data['hospice_details'] = $this->billing_statement_model->get_hospice_details($hospice_id);
		$hospice_id = 139;
		$data['statement_invoice_inquiry'] = $this->billing_statement_model->get_past_due_invoice_v2($hospice_id);
		// $data['total_balance_due'] = $this->billing_statement_model->get_total_balance_due_by_hospice($hospice_id);
		$data['last_payment'] = $this->billing_statement_model->get_last_payment_by_hospice($hospice_id);
		
		// print_me($data['last_payment']);
		$data['pending_payments_count'] = count($this->billing_statement_model->get_pending_payments_by_hospice($hospice_id));
		$data['pending_payments'] = $this->billing_statement_model->get_pending_payments_by_hospice($hospice_id);
		$data['total_pending_payments'] = 0;
		foreach($data['pending_payments'] as $value) {
			$data['total_pending_payment'] += $value['payment_amount'];
		}
		$all_invoices = $this->billing_statement_model->get_current_invoice_by_hospice($hospice_id);
		$data['total_balance_due_date'] = $all_invoices[count($all_invoices)-1]['due_date'];
		$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($all_invoices[count($all_invoices)-1]['acct_statement_invoice_id']);
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
		$this->templating_library->set_view('pages/statement_bill_by_hospice','pages/statement_bill_by_hospice', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function statement_make_payment($hospice_id=0) {
		$data['hospice_id'] = $hospice_id;
		$hospice_id = 139;

		// <=========> PAST DUE INVOICES OR INVOICES BEFORE THE CURRENT INVOICE (TOTAL BALANCE DUE) <=========>
		// $data['statement_invoice_inquiry'] = $this->billing_statement_model->get_current_invoice_by_hospice($hospice_id);

		// <=======================================> OVERDUE INVOICES <=======================================>
		// $data['statement_invoice_inquiry'] = $this->billing_statement_model->get_past_due_invoice(139); 


		$data['statement_invoice_payment_due'] = $this->billing_statement_model->get_payment_due_invoice(139);
		// $data['total_balance_due'] = $this->billing_statement_model->get_total_balance_due_by_hospice(139);
		$temp_total_payment_due = $this->billing_statement_model->get_total_payment_due_by_hospice(139);
		$data['total_payment_due'] = $temp_total_payment_due['totaltotal'] + $temp_total_payment_due['totalnoncap'] + $temp_total_payment_due['totalpurchaseitem'];
		$all_invoices = $this->billing_statement_model->get_current_invoice_by_hospice($hospice_id);
		$data['total_balance_due_date'] = $all_invoices[count($all_invoices)-1]['due_date'];
		$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($all_invoices[count($all_invoices)-1]['acct_statement_invoice_id']);
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
				$data['statement_invoice_inquiry'] = $this->billing_statement_model->get_past_due_invoice_v3($hospice_id, $total_balance_due['acct_statement_invoice_id']);
				$data['invoices_reconciliation'] = array();
				foreach($data['statement_invoice_inquiry'] as $value) {
					$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['acct_statement_invoice_id']);
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
			$data = array(
				"payment_date" 		=> $payment_date,
				"payment_type"		=> "credit_card",
				"payment_amount"	=> number_format((float)$statement_invoice[0]['total'], 2, '.', ''),
				// "receive_status"	=> 1,
				"payment_code"		=> $payment_code,
				"email"				=> $email
			);
			// print_me($payment_code);
			// print_me($data);

			$make_payment = $this->billing_statement_model->update_statement_bill_invoice($acct_statement_invoice_id, $data);

			if($make_payment) {
				$total_payment_amount += number_format((float)$statement_invoice[0]['total'], 2, '.', '');
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

	public function make_other_payment_v2($payment_amount, $payment_date, $email){
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
		$payment_invoices = $this->billing_statement_model->get_all_invoice_by_hospice(139);

		if(!empty($payment_invoices)) {
			$count_total_payment_due = count($payment_invoices);
			foreach($payment_invoices as $index => $value){
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
					$payment_code = $statement_invoice[0]['hospiceID'].$date_today;
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
}