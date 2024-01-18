<?php
Class census extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		date_default_timezone_set('America/Los_Angeles');
		$this->load->model("billing_statement_model");
		$this->load->model("order_model");
	}

	public function testingfunction () {
        print_me(date('Y-m-d h:i:s'));
	}

    public function active_customers()
	{
        $data['active_nav']	= "active_customers";
		$this->templating_library->set('title','Active Customers');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'hospice_user') {
			$this->templating_library->set_view('pages/report/census','pages/report/census', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

    public function load_more_active_census($filter_from, $filter_to, $page, $limit=25) {
		if($this->input->is_ajax_request())
		{
			if($limit > 25){
				$limit = 25;
			}

            $offset = ($page - 1) * $limit;
			$pagination_details = array(
                "total_records" => 0,
				"total_pages"   => 0,
				"current_page"  => $page
			);
            $data['total_active_patients_count'] = 0;

			$filter_to = ($filter_to==0 || $filter_to=="")? $filter_from : $filter_to;

            $hospices = get_hospices_v3($this->session->userdata('user_location'));
            $pagination_details['total_records'] = count($hospices);
            $total_pages = ($pagination_details['total_records'] > 0)? ceil($pagination_details['total_records'] / $limit) : 0;
            $pagination_details['total_pages'] = $total_pages;
            $data['pagination_details'] = $pagination_details;

            $service_date_from = $filter_from;
			$service_date_to = $filter_to;

            $start = 0;
            $limit = 25;
            $count = 0;
            $data['hospices_data'] = array();
            $current_date = date('Y-m-d');

            $data['offset'] = $offset;
            for ($i=$offset; $i < ($limit + $offset); $i++) { 
                if ($hospices[$i]['hospiceID'] != undefined && $hospices[$i]['hospiceID'] != null) {
                    // if ($service_date_from == $current_date || $service_date_to == $current_date || $service_date_to > $current_date) {
                    //     $result = $this->order_model->list_active_patients_new_v2($hospices[$i]['hospiceID'],$this->session->userdata('user_location'));
                    //     $new_data = Array(
                    //         "hospice_number"  => $hospices[$i]['hospice_account_number'],
                    //         "hospice_name"  => $hospices[$i]['hospice_name'],
                    //         "totalCustomerCount" => count($result)
                    //     );
                    //     $data['total_active_patients_count'] += count($result);
                    // } else {
                        $hospice_data['customers'] = $this->billing_statement_model->get_all_customer_for_draft_statement($hospices[$i]['hospiceID'], 1, 1, $service_date_from, $service_date_to);
                        $new_data = Array(
                            "hospice_number"  => $hospices[$i]['hospice_account_number'],
                            "hospice_name"  => $hospices[$i]['hospice_name'],
                            "totalCustomerCount" => $hospice_data['customers']['totalCustomerCount']
                        );
                        $data['total_active_patients_count'] += $hospice_data['customers']['totalCustomerCount'];
                    // }
                    array_push($data['hospices_data'], $new_data);
                }
            }

            $data['current_date'] = $current_date;
            $data['service_date_from'] = $service_date_from;
            $data['service_date_to'] = $service_date_to;
            $data['hospices'] = $hospices;            
            
		}
		echo json_encode($data);
		exit;
		
	}

    public function get_total_active_patients($filter_from, $filter_to) {
		if($this->input->is_ajax_request())
		{
            $data['hospices_data'] = array();
            $data['total_active_patients_count_initial'] = 0;
            $service_date_from = $filter_from;
			$service_date_to = $filter_to;
            $current_date = date('Y-m-d');

            $hospices = get_hospices_v3($this->session->userdata('user_location'));

            for ($i=0; $i < count($hospices); $i++) { 
                if ($hospices[$i]['hospiceID'] != undefined && $hospices[$i]['hospiceID'] != null) {
                    // if ($service_date_from == $current_date || $service_date_to == $current_date || $service_date_to > $current_date) {
                    //     $result = $this->order_model->list_active_patients_new_v2($hospices[$i]['hospiceID'],$this->session->userdata('user_location'));
                    //     $new_data = Array(
                    //         "hospice_number"  => $hospices[$i]['hospice_account_number'],
                    //         "hospice_name"  => $hospices[$i]['hospice_name'],
                    //         "totalCustomerCount" => count($result)
                    //     );
                    //     $data['total_active_patients_count_initial'] += count($result);
                    // } else {
                        $hospice_data['customers'] = $this->billing_statement_model->get_all_customer_for_draft_statement($hospices[$i]['hospiceID'], 1, 1, $service_date_from, $service_date_to);
                        $new_data = Array(
                            "hospice_number"  => $hospices[$i]['hospice_account_number'],
                            "hospice_name"  => $hospices[$i]['hospice_name'],
                            "totalCustomerCount" => $hospice_data['customers']['totalCustomerCount']
                        );
                        $data['total_active_patients_count_initial'] += $hospice_data['customers']['totalCustomerCount'];
                    // }
                    array_push($data['hospices_data'], $new_data);
                }
            }       
		}
		echo json_encode($data);
		exit;
		
	}

}
