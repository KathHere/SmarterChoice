<?php
Class hospice extends Ci_Controller
{
	var $response_code = 1;
	var $response_message = "";


	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		date_default_timezone_set("America/Los_Angeles");
		$this->load->model("hospice_model");
		$this->load->model("equipment_model");
		$this->load->model("billing_statement_model");
	}

	public function index()
	{
		$this->templating_library->set('title','Create New Hospice');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'distribution_supervisor') {
			$this->templating_library->set_view('pages/create_hospice','pages/create_hospice');
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');

	}

	public	function hospice_list($status = "")
	{
		$this->templating_library->set('title','List of Accounts');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');

		// DME User Access/Restriction
        // if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'distribution_supervisor') {
			$result['current_status'] = $status;

			if ($status == 'active') {
				$status = 1;
			} else if ($status == 'inactive'){
				$status = 0;
			} else if ($status == 'suspended') {
				$status = 2;
			} else if ($status == '') {
				$status = '';
			}

			$result['hospices'] = $this->hospice_model->account_list_by_status($this->session->userdata('user_location'), $status);

			$this->templating_library->set_view('pages/hospice_list','pages/hospice_list' , $result);
		// }

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public	function company_list()
	{
		$result['companies'] = $this->hospice_model->list_group_commercial_v2($this->session->userdata('user_location'));

		$this->templating_library->set('title','List of Hospices');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/company_list','pages/company_list' , $result);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');

	}

	public function create()
	{
		$account_type = $this->input->post('choose_account_type_value');

		// if($account_type == 0)
		// {
		// 	$this->form_validation->set_rules('hospice_name', 'Hospice Name', 'xss_clean|required|is_unique[dme_hospice.hospice_name]');
		// 	$this->form_validation->set_rules('date_of_service', 'Sign On Date', 'required');
		// 	$this->form_validation->set_rules('hospice_phone_number', 'Hospice Phone Number', 'required');
		// 	$this->form_validation->set_rules('hospice_contact_person', 'Hospice Contact Person', 'required');
		// 	$this->form_validation->set_rules('hospice_website', 'Hospice Website', 'required');
		// 	$this->form_validation->set_rules('hospice_address', 'Hospice Address', 'required');
		// 	$this->form_validation->set_rules('hospice_fax_number', 'Hospice Fax Number', 'required');
		// 	$this->form_validation->set_rules('hospice_title', 'Hospice Title', 'required');
		// 	$this->form_validation->set_rules('hospice_email', 'Hospice Email', 'required');
		//}
		// else
		// {
		// 	$this->form_validation->set_rules('company_name', 'Company Name', 'xss_clean|required|is_unique[dme_hospice.hospice_name]');
		// 	$this->form_validation->set_rules('company_date_of_service', 'Company Sign On Date', 'required');
		// 	$this->form_validation->set_rules('company_phone_number', 'Company Phone Number', 'required');
		// 	$this->form_validation->set_rules('company_contact_person', 'Company Contact Person', 'required');
		// 	$this->form_validation->set_rules('company_website', 'Company Website', 'required');
		// 	$this->form_validation->set_rules('company_address', 'Company Address', 'required');
		// 	$this->form_validation->set_rules('company_fax_number', 'Company Fax Number', 'required');
		// 	$this->form_validation->set_rules('company_title', 'Company Title', 'required');
		// 	$this->form_validation->set_rules('company_email', 'Company Email', 'required');
		// }
		

		// $this->form_validation->set_rules('hospice_name', 'Account Name', 'xss_clean|required|is_unique[dme_hospice.hospice_name]');
		$this->form_validation->set_rules('hospice_name', 'Account Name', 'required');
		$this->form_validation->set_rules('date_of_service', 'Sign On Date', 'required');
		$this->form_validation->set_rules('hospice_phone_number', 'Account Phone Number', 'required');
		$this->form_validation->set_rules('hospice_contact_person', 'Account Contact Person', 'required');
		// $this->form_validation->set_rules('hospice_billing_address', 'Account Billing Address', 'required');
		$this->form_validation->set_rules('hospice_title', 'Account Title', 'required');
		$this->form_validation->set_rules('hospice_email', 'Account Website Email', 'required');
		$this->form_validation->set_rules('associated_account_location', 'Associated Service Location', 'required');
		$this->form_validation->set_rules('hospice_fax_number', 'Account Fax Number', 'required');
		$this->form_validation->set_rules('billing_email', 'Billing Email', 'required');
		// $this->form_validation->set_rules('hospice_shipping_address', 'Account Shipping Address', 'required');
		$this->form_validation->set_rules('choose_invoice_to', 'Send Invoice to', 'required');
		$this->form_validation->set_rules('choose_payment_terms', 'Payment Terms', 'required');
		$this->form_validation->set_rules('account_daily_rate', 'Daily Rate', 'required');

		if($this->form_validation->run() == TRUE)
		{

			$existing = $this->hospice_model->check_hospice_if_existing($this->input->post('hospice_name'), $this->input->post('associated_account_location'));

			if (empty($existing)) {
				$payment_terms = "";
				if($this->input->post('choose_payment_terms') == 0) {
					$payment_terms = "30_days";
				} else if ($this->input->post('choose_payment_terms') == 1) {
					$payment_terms = "60_days";
				} else {
					$payment_terms = "90_days";
				}

				$hopice_shipping_address = $this->input->post('s_placenum').', '.$this->input->post('s_address').', '.$this->input->post('s_city').', '.$this->input->post('s_state').', '.$this->input->post('s_postalcode');
				$hopice_billing_address = $this->input->post('b_placenum').', '.$this->input->post('b_address').', '.$this->input->post('b_city').', '.$this->input->post('b_state').', '.$this->input->post('b_postalcode');

				// if($account_type == 0)
				// {

					$array = array(
						'hospice_account_number' 		=> $this->input->post('hospice_account_num'),
						'hospice_name'  				=> $this->input->post('hospice_name'),
						'hospice_contact_person'		=> $this->input->post('hospice_contact_person'),
						'hospice_address' 				=> $hopice_shipping_address,
						'hospice_fax_number' 			=> $this->input->post('hospice_fax_number'),
						'hospice_email' 				=> $this->input->post('hospice_email'),
						'contact_num'					=> $this->input->post('hospice_phone_number'),
						'date_of_service' 				=> date($this->input->post('date_of_service')),
						'hospice_website' 				=> $this->input->post('hospice_website'),
						'hospice_title' 				=> $this->input->post('hospice_title'),
						'type' 							=> 0,
						'account_location'				=> $this->input->post('associated_account_location'),
						'track_census'					=> $this->input->post('is_track_census'),
						'billing_address'				=> $hopice_billing_address,
						'payment_terms'					=> $payment_terms,
						'invoice_to'					=> $this->input->post('choose_invoice_to'),
						'billing_email'					=> $this->input->post('billing_email'),
						'billing_email_cc'				=> $this->input->post('billing_email_cc'),
						'daily_rate'					=> $this->input->post('account_daily_rate'),
						'b_street'						=> $this->input->post('b_address'),
						'b_placenum'					=> $this->input->post('b_placenum'),
						'b_city'						=> $this->input->post('b_city'),
						'b_state'						=> $this->input->post('b_state'),
						'b_postalcode'					=> $this->input->post('b_postalcode'),
						's_street'						=> $this->input->post('s_address'),
						's_placenum'					=> $this->input->post('s_placenum'),
						's_city'						=> $this->input->post('s_city'),
						's_state'						=> $this->input->post('s_state'),
						's_postalcode'					=> $this->input->post('s_postalcode')
					);
				// }
				// else
				// {
				// 	$array = array(
				// 		'hospice_account_number' 		=> $this->input->post('company_account_num'),
				// 		'hospice_name'  				=> $this->input->post('company_name'),
				// 		'hospice_contact_person'		=> $this->input->post('company_contact_person'),
				// 		'hospice_address' 				=> $this->input->post('company_address'),
				// 		'hospice_fax_number' 			=> $this->input->post('company_fax_number'),
				// 		'hospice_email' 				=> $this->input->post('company_email'),
				// 		'contact_num'					=> $this->input->post('company_phone_number'),
				// 		'date_of_service' 				=> date($this->input->post('company_date_of_service')),
				// 		'hospice_website' 				=> $this->input->post('company_website'),
				// 		'hospice_title' 				=> $this->input->post('company_title'),
				// 		'type' 							=> 1,
				// 		'account_location'				=> $this->input->post('account_location')
				// 	);
				// }
				$add_hospice = $this->hospice_model->create($array);

				$unique_id = strtotime(date('Y-m-d H:i:s'));

				$data['equipments']	= $this->equipment_model->tobe_assign_equipment_v2();

				foreach($data['equipments'] as $key=>$value){
					$data_array[] = array(
						'hospiceID'		=> $add_hospice,
						'equipmentID'	=> $value['equipmentID'],
						'uniqueID'		=> $unique_id
					);
				}
				$this->equipment_model->insert_equipments($data_array,$add_hospice);

				$data['success'] = true;
				// $this->templating_library->set('title','Create New Hospice');
				$this->templating_library->set('title','Assign Items');
				$this->templating_library->set_view('common/head','common/head');
				$this->templating_library->set_view('common/header','common/header');
				$this->templating_library->set_view('common/nav','common/nav');
				// $this->templating_library->set_view('pages/create_hospice','pages/create_hospice' ,$data);

				// ===============> Add Account Statement Bill 12/10/2019 ======= Start
				if($add_hospice) {
					$data = array();
					$temp_statement_no = strtotime(date('Y-m-d H:i:s'));
					$statement_no = $temp_statement_no + $add_hospice;
					$service_date = date('Y-m-01');
					$data = array(
						"statement_no"		=> $statement_no,
						"hospiceID"			=> $add_hospice,
						"service_date_from"	=> $service_date
					);
					$this->billing_statement_model->insert_statement_bill($data);
				}
				// ===============> Add Account Statement Bill 12/10/2019 ======= End

				// -------------- Change for the revised Register Account - 02/21/19 {

				$hospiceID = $add_hospice;
				$data['capped_count']	= $this->equipment_model->assigned_equipment_capped_v2($hospiceID);
				$data['capped_ids'] = array();
				foreach($data['capped_count'] as $value)
				{
					$data['capped_ids'][] = $value['equipmentID'];
				}
				$data['non_capped_count']	= $this->equipment_model->assigned_equipment_non_capped($hospiceID);
				$data['disposable_count']	= $this->equipment_model->assigned_equipment_disposable($hospiceID);
				$data['assigned'] = get_assigned_equipment($hospiceID);
				$data['equipments_v3']	= $this->equipment_model->tobe_assign_equipment_v3($hospiceID);
				$data['hospices']	= $this->equipment_model->get_hospice($hospiceID);
				$data['counts']	= $this->equipment_model->count_results();
				$this->templating_library->set_view('pages/equipment_list','pages/equipment_list' ,$data);
				// $this->templating_library->set_view('pages/equipment_list_next','pages/equipment_list_next', $data);
				// -------------- Change for the revised Register Account - 02/21/19 }

				$this->templating_library->set_view('common/footer','common/footer');
				$this->templating_library->set_view('common/foot','common/foot');
			} else {
				$data['failed'] = true;
				$data['message'] = '<span>The Account Name field must contain a unique value.</span>';
				// print_me($data['message']);
				$this->templating_library->set('title','Create New Hospice');
				$this->templating_library->set_view('common/head','common/head');
				$this->templating_library->set_view('common/header','common/header');
				$this->templating_library->set_view('common/nav','common/nav');
				$this->templating_library->set_view('pages/create_hospice','pages/create_hospice', $data);
				$this->templating_library->set_view('common/footer','common/footer');
				$this->templating_library->set_view('common/foot','common/foot');
			}

			
		}
		else
		{
			$data['failed'] = true;
			$data['message'] = $this->response_message = validation_errors('<span></span>');
			// print_me($data['message']);
			$this->templating_library->set('title','Create New Hospice');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');
			$this->templating_library->set_view('pages/create_hospice','pages/create_hospice', $data);
			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}
	}


	public	function update_hospice($hash = '')
	{
		$checked = check_code($hash);
	    $hospiceID = get_id_from_code($hash);

		$data_post = $this->input->post();

		//$this->form_validation->set_rules('hospice_name', 'Hospice Name', 'xss_clean|required|is_unique[dme_hospice.hospice_name]');

		$payment_terms = "";
		if($this->input->post('choose_payment_terms') == 0) {
			$payment_terms = "30_days";
		} else if ($this->input->post('choose_payment_terms') == 1) {
			$payment_terms = "60_days";
		} else {
			$payment_terms = "90_days";
		}

		$hopice_shipping_address = $this->input->post('s_placenum').', '.$this->input->post('s_address').', '.$this->input->post('s_city').', '.$this->input->post('s_state').', '.$this->input->post('s_postalcode');
		$hopice_billing_address = $this->input->post('b_placenum').', '.$this->input->post('b_address').', '.$this->input->post('b_city').', '.$this->input->post('b_state').', '.$this->input->post('b_postalcode');
		$array = array(
			'hospice_account_number' 		=> $this->input->post('hosp_acct_number'),
			'hospice_name'  			    => $this->input->post('hospice_name'),
			'contact_num'				    => $this->input->post('hospice_contact_num'),
			'hospice_address'               => $hopice_shipping_address,
			'hospice_fax_number' 			=> $this->input->post('hospice_fax_number'),
			'hospice_email' 				=> $this->input->post('hospice_email'),
			'hospice_contact_person' 		=> $this->input->post('hospice_cont_person'),
			'date_of_service' 				=> date($this->input->post('date_of_service')),
			'hospice_website' 				=> $this->input->post('hospice_website'),
			'hospice_title' 				=> $this->input->post('hospice_title'),
			'track_census'					=> $this->input->post('is_track_census'),
			'billing_address'				=> $hopice_billing_address,
			'payment_terms'					=> $payment_terms,
			'invoice_to'					=> $this->input->post('choose_invoice_to'),
			'billing_email'					=> $this->input->post('billing_email'),
			'billing_email_cc'				=> $this->input->post('billing_email_cc'),
			'daily_rate'					=> $this->input->post('account_daily_rate'),
			'b_street'						=> $this->input->post('b_address'),
			'b_placenum'					=> $this->input->post('b_placenum'),
			'b_city'						=> $this->input->post('b_city'),
			'b_state'						=> $this->input->post('b_state'),
			'b_postalcode'					=> $this->input->post('b_postalcode'),
			's_street'						=> $this->input->post('s_address'),
			's_placenum'					=> $this->input->post('s_placenum'),
			's_city'						=> $this->input->post('s_city'),
			's_state'						=> $this->input->post('s_state'),
			's_postalcode'					=> $this->input->post('s_postalcode')
		);


		$add_hospice = $this->hospice_model->update_hospice($hospiceID, $array);

		redirect('hospice/hospice_list/'.$this->input->post('current_viewed_status'),'refresh');
	}

	public	function update_company($hash = '')
	{
		$checked = check_code($hash);
	    $hospiceID = get_id_from_code($hash);

		$data_post = $this->input->post();

		//$this->form_validation->set_rules('hospice_name', 'Hospice Name', 'xss_clean|required|is_unique[dme_hospice.hospice_name]');

		$array = array(
			'hospice_account_number' 		=> $this->input->post('hosp_acct_number'),
			'hospice_name'  			    => $this->input->post('hospice_name'),
			'contact_num'				    => $this->input->post('hospice_contact_num'),
			'hospice_address' 			    => $this->input->post('hospice_address'),
			'hospice_fax_number' 			=> $this->input->post('hospice_fax_number'),
			'hospice_email' 				=> $this->input->post('hospice_email'),
			'hospice_contact_person' 		=> $this->input->post('hospice_cont_person'),
			'date_of_service' 				=> date($this->input->post('date_of_service')),
			'hospice_website' 				=> $this->input->post('hospice_website'),
			'hospice_title' 				=> $this->input->post('hospice_title'),
		);


		$add_hospice = $this->hospice_model->update_hospice($hospiceID, $array);

		redirect('hospice/company-list','refresh');
	}

	public function account_activation($activation, $account_id) {
		$data = array();

		$data = array(
			'account_active_sign' => $activation
		);
		$return = $this->hospice_model->update_hospice($account_id, $data);

		if($return)
		{
			$this->response_code = 0;
			$this->response_message	= "Account status successfully updated.";
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function remove_hospice($hospiceID)
	{
		$deleted = $this->hospice_model->delete_hospice($hospiceID);

		if($deleted)
		{
			$this->response_code 		= 0;
			$this->response_message		= "Successfully Deleted Hospice.";
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function get_hosp_contact($hospiceID)
	{
		$results = $this->hospice_model->get_contact_num($hospiceID);

		foreach($results as $result)
		{
			$array = array(
				'contact_num' => $result['contact_num']
			);

		}
		echo json_encode($array);

	}

	public function get_account_work_orders($hospiceID)
	{
		$data = array();

		$data['work_orders'] = $this->hospice_model->get_account_unconfirmed_work_orders($hospiceID);

		echo json_encode($data);
	}

	public function get_hospices_by_account_location($account_location, $user_group_id=0) {
		if($this->input->is_ajax_request())
		{
			$hospices = $this->hospice_model->list_group_v5($account_location);
		}
		echo json_encode($hospices);
	}

}

