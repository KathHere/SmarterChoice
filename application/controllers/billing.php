<?php
Class billing extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		date_default_timezone_set('America/Los_Angeles');
		$this->load->model("billing_model");
		$this->load->model("billing_statement_model");
	}
	
	// public function index()
	// {
	// 	$this->templating_library->set('title','Account Statement Search');
	// 	$this->templating_library->set_view('common/head','common/head');
	// 	$this->templating_library->set_view('common/header','common/header');
	// 	$this->templating_library->set_view('common/nav','common/nav');
	// 	// $this->templating_library->set_view('pages/create_service_location','pages/create_service_location');
	// 	$this->templating_library->set_view('common/footer','common/footer');
	// 	$this->templating_library->set_view('common/foot','common/foot');

	// }

	public function search()
	{
		$data_get = $this->input->get();

		// $data['term'] = (isset($get_data['term'])) ? $get_data['term'] : '';
		// $data['active_nav'] = "patient_menu";
		$this->templating_library->set('title','Account Statement Search');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$this->templating_library->set_view('pages/account_statement_search','pages/account_statement_search', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function search_accounts()
    {
        $post_data = $this->input->post();
        $created_by = $this->session->userdata('group_id');
        $search_string = '';
        if (isset($_POST['term'])) {
            $search_string = $_POST['term'];
        }
        $count = 0;
        // print_me($search_string);
        $searches = $this->billing_model->search_all_accounts($this->session->userdata('user_location'), $search_string);
        // print_me($searches);
        if ($searches) {
            foreach ($searches as $search) {
                // $patient_order = get_patient_order_v2($search['patientID']);
                // if (!empty($patient_order)) {
                    echo "<tr style='color:#808080 !important;cursor:pointer;border:1px solid #f5f5f5' class='form-control' data-account_number='".$search['hospice_account_number']."' data-hospice-id='".$search['hospiceID']."'><td><a href=".base_url()."billing_statement/statement_bill/".$search['hospiceID'].">".$search['hospice_account_number'].' - '.$search['hospice_name'].'</a></td></tr>';
                // }
            }
        } else {
            echo "<tr style='cursor:pointer;border:1px solid #f5f5f5' class='form-control'><td>No Results Found.</td></tr>";
        }
    }

	public function return_search($view_type = "")
	{
		$data_get = $this->input->get();

		$data['term'] = (isset($_POST['term'])) ? $_POST['term'] : '';
		$data['hospice_id'] = (isset($_POST['hospice_id'])) ? $_POST['hospice_id'] : 0;
		
		$where = array(
			'term' => str_replace(' ', '%', $data['term']),
			'query' => str_replace(' ', '%', $data['hospice_id'])
		);
		$data['accounts'] = $this->billing_model->list_for_account_search($this->session->userdata('user_location'), $data['term'], 0);
		// print_me($data['accounts']);
		$data['counting'] = count($data['accounts']);
		$data['search_type'] = "account_search";

		$this->templating_library->set('title','Account Statement');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		if($view_type == 'list-view')
		{
			$this->templating_library->set_view('pages/searched_account_grid','pages/searched_account_grid', $data);
		}
		else
		{
			$this->templating_library->set_view('pages/searched_account_grid','pages/searched_account_grid', $data);
		}
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	// public function load_more_new($page=1, $limit=40, $hospiceID)
	// {
	// 	$reponse = array("data" => array());
	// 	if($this->input->is_ajax_request())
	// 	{
	// 		//list of active patients only
	// 		// $result = $this->order_model->list_active_patients_new($hospiceID);
	// 		$result = $this->order_model->list_active_patients_new_v2($hospiceID,$this->session->userdata('user_location'));
	// 		$arr2 = array_msort($result, array('p_lname' => SORT_ASC,'p_fname' => SORT_ASC));

	// 		$new_patients = array_chunk($arr2, $limit);

	// 		if(!empty($new_patients))
	// 		{
	// 			$response['data'] = $new_patients[$page-1];
	// 		}
	// 	}
	// 	echo json_encode($response);
	// }

	public function billing_list()
	{
		
		$this->templating_library->set('title','View All Accounts');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$data['accounts'] = $this->billing_model->list_for_account($this->session->userdata('user_location'));
			$data['counting'] = count($data['accounts']);
			$data['search_type'] = "account_search";
			$this->templating_library->set_view('pages/searched_account_grid','pages/searched_account_grid', $data);
		}
		
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function statement_draft() {
		$data['statement_draft'] = $this->billing_model->get_all_statement_bill_draft();
		//print_me($data['statement_draft']);
		$this->templating_library->set('title','Draft Account Statement');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/statement_draft','pages/statement_draft', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	public function statement_activity() {
		$this->templating_library->set('title','Draft Account Statement');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/statement_activity','pages/statement_activity', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function statement_service_date_report() {
		$data = array();
		$this->templating_library->set('title','Service Date Report');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$this->templating_library->set_view('pages/statement_billing_report','pages/statement_billing_report', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function filter_billing_report($filter_type, $filter_by, $hospiceID, $start, $limit, $input_date_from, $input_date_to) {
		if($this->input->is_ajax_request())
		{
			$temp_year = date('Y');
			$date_from = '';
			$date_to = '';
			switch ($filter_type) {
				case 'Monthly':
					$date_from = $temp_year.'-'.$filter_by.'-01';
					$date_to = date('Y-m-t', strtotime($date_from));

					break;

				case 'Quarterly':
					if ($filter_by == 'first_quarter') {
						$date_from = $temp_year.'-01-01';
						$date_to = $temp_year.'-03-31';
					}

					if ($filter_by == 'second_quarter') {
						$date_from = $temp_year.'-04-01';
						$date_to = $temp_year.'-06-30';
					}

					if ($filter_by == 'third_quarter') {
						$date_from = $temp_year.'-07-01';
						$date_to = $temp_year.'-09-30';
					}

					if ($filter_by == 'fourth_quarter') {
						$date_from = $temp_year.'-10-01';
						$date_to = $temp_year.'-12-31';
					}
					break;

				case 'Yearly':
					$date_from = $filter_by.'-01-01';
					$date_to = $filter_by.'-12-31';
					break;

				case 'Custom':
					$date_from = $input_date_from;
					$date_to = $input_date_to;
					break;
			}

			if ($filter_type == 'Custom' && $date_from == 0 && $date_to == 0) {
				$data['statement_invoices'] = [];
				$data['invoices_reconciliation'] = [];
				$data['is_disabled_invoice_cancel'] = [];
				$data['totalCount'] = 1;
				$data['totalBillingReportCount'] = 0;
				$data['service_date'] = '';
			} else if ($filter_by == 'select_one') {
				$data['statement_invoices'] = [];
				$data['invoices_reconciliation'] = [];
				$data['is_disabled_invoice_cancel'] = [];
				$data['totalCount'] = 1;
				$data['totalBillingReportCount'] = 0;
				$data['service_date'] = '';
			} else {
				$data = $this->billing_model->get_billing_report($date_from, $date_to, $hospiceID, $start, $limit, $this->session->userdata('user_location'));
				$disp_date_from = '';
				$disp_date_to = '';
				if ($date_from != 0) {
					$disp_date_from = date('m/d/Y', strtotime($date_from));
				}
				if ($date_to != 0) {
					$disp_date_to = date('m/d/Y', strtotime($date_to));
				}
				$data['service_date'] = $disp_date_from.' - '.$disp_date_to;
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

			
		}

		echo json_encode($data);
	}

	public function get_billing_report_total_payment($filter_type, $filter_by, $hospiceID, $input_date_from, $input_date_to) {
		if($this->input->is_ajax_request())
		{
			// $data['input_date_from'] = $input_date_from;
			// $data['input_date_to'] = $input_date_to;

			$temp_year = date('Y');
			$date_from = '';
			$date_to = '';
			switch ($filter_type) {
				case 'Monthly':
					$date_from = $temp_year.'-'.$filter_by.'-01';
					$date_to = date('Y-m-t', strtotime($date_from));

					break;

				case 'Quarterly':
					if ($filter_by == 'first_quarter') {
						$date_from = $temp_year.'-01-01';
						$date_to = $temp_year.'-03-31';
					}

					if ($filter_by == 'second_quarter') {
						$date_from = $temp_year.'-04-01';
						$date_to = $temp_year.'-06-30';
					}

					if ($filter_by == 'third_quarter') {
						$date_from = $temp_year.'-07-01';
						$date_to = $temp_year.'-09-30';
					}

					if ($filter_by == 'fourth_quarter') {
						$date_from = $temp_year.'-10-01';
						$date_to = $temp_year.'-12-31';
					}
					break;

				case 'Yearly':
					$date_from = $filter_by.'-01-01';
					$date_to = $filter_by.'-12-31';
					break;

				case 'Custom':
					$date_from = $input_date_from;
					$date_to = $input_date_to;
					break;
			}

			if ($filter_type == 'Custom' && $date_from == 0 && $date_to == 0) {
				$data['total_payment_amount'] = 0;
			} else if ($filter_type == 'select_one') {
				$data['total_payment_amount'] = 0;
			} else {
				$invoices = $this->billing_model->get_billing_report_total_payment($date_from, $date_to, $hospiceID, $this->session->userdata('user_location'));

				$data['total_count'] = $invoices['total_count'];
				$data['total_payment_amount'] = 0;
				foreach($invoices['result'] as $key => $value) {
					$invoice_reconciliation = $this->billing_statement_model->get_invoice_reconciliation_balance_and_owe_by_invoice($value['statement_no']);
					
					$data['total_payment_amount'] += $value['total'];
					$data['total_payment_amount'] += $value['non_cap'];
					$data['total_payment_amount'] += $value['purchase_item'];
					$data['total_payment_amount'] -= $invoice_reconciliation['credit'];
					$data['total_payment_amount'] += $invoice_reconciliation['owe'];
				}
			}
			

			echo json_encode($data);
		}
	}

	public function send_to_collection($acct_statement_invoice_ids) {
		if($this->input->is_ajax_request()) {
			$invoice_ids = explode("-", $acct_statement_invoice_ids);
			$data = array(
				'is_collection' => 1
			);
			
			$update =$this->billing_model->update_collection_where_in($invoice_ids, $data);

			if ($update) {
				$this->response_code = 0;
				$this->response_message = 'Successfully sent to collections.';
			} else {
				$this->response_code = 1;
				$this->response_message = 'Update Failed.';
			}
		}
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
			'invoice_ids' => $invoice_ids
        ));
		exit;
	}

	public function send_to_invoice($acct_statement_invoice_ids) {
		if($this->input->is_ajax_request()) {
			$invoice_ids = explode("-", $acct_statement_invoice_ids);
			$data = array(
				'is_collection' => 0
			);
			
			$update =$this->billing_model->update_collection_where_in($invoice_ids, $data);

			if ($update) {
				$this->response_code = 0;
				$this->response_message = 'Successfully sent to invoice inquiry.';
			} else {
				$this->response_code = 1;
				$this->response_message = 'Update Failed.';
			}
		}
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
			'invoice_ids' => $invoice_ids
        ));
		exit;
	}

	public function collection_list() {
		$data = array();
		$this->templating_library->set('title','Collections');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$this->templating_library->set_view('pages/statement_collection','pages/statement_collection', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function delete_collection_invoice() {
		if($this->input->is_ajax_request()) {
			$data_post = $this->input->post();

			if ($data_post['invoice_number_input'] == substr($data_post['delete_invoice_number'],3, 10)) {
				$delete_invoice = true;
				if ($delete_invoice) {
					// First delete dme_account_statement_order_summary
					$order_summary = $this->billing_model->delete_invoice_order_summary($data_post['delete_invoice_id']);

					// Then delete dme_account_statement_invoice
					if ($order_summary) {
						$invoice_details = $this->billing_model->delete_invoice_details($data_post['delete_invoice_id']);

						if ($invoice_details) {
							$this->response_code = 0;
							$this->response_message = 'Invoice successfully deleted.';
						} else {
							$this->response_code = 1;
							$this->response_message = 'Deleting of invoice failed. Please try again.';
						}
					} else {
						$this->response_code = 1;
						$this->response_message = 'Deleting of invoice failed. Please try again.';
					}
				} else {
					$this->response_code = 1;
					$this->response_message = 'Deleting of invoice failed. Please try again.';
				}
				
			} else {
				$this->response_code = 1;
				$this->response_message = 'Input did not match invoice number. Please try again.';
			}
			
		}
		echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
			'data_post' => $data_post
        ));
		exit;
	}
}