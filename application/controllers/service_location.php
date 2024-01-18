<?php
Class service_location extends Ci_Controller
{
	var $response_code = 1;
	var $response_message = "";


	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		date_default_timezone_set("America/Los_Angeles");
		$this->load->model("service_location_model");
		$this->load->model("hospice_model");
	}

	public function index()
	{
		$this->templating_library->set('title','Create New Service Location');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'distribution_supervisor') {
			$this->templating_library->set_view('pages/create_service_location','pages/create_service_location');
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');

	}

	public function create()
	{

		$this->form_validation->set_rules('service_location_date', 'Date of New Service Location', 'required');
		$this->form_validation->set_rules('service_location_name', 'Service Location Name', 'required');
		$this->form_validation->set_rules('p_address', 'Address', 'required');
		$this->form_validation->set_rules('service_city', 'City', 'required');
		$this->form_validation->set_rules('service_state', 'State / Province', 'required');
		$this->form_validation->set_rules('service_postalcode', 'Postal Code', 'required');
		$this->form_validation->set_rules('service_location_contact_person', 'Service Location Contact Person', 'required');
		$this->form_validation->set_rules('service_location_id', 'Service Location ID no.', 'required');
		$this->form_validation->set_rules('service_location_phone_no', 'Service Location Phone Number', 'required');
		$this->form_validation->set_rules('service_location_fax_no', 'Service Location Fax No.', 'required');
		$this->form_validation->set_rules('service_location_contact_person_title', 'Contact Person Title', 'required');

		if($this->form_validation->run() == TRUE)
		{
			$array = array(
				'user_street' 					=> $this->input->post('p_address'),
				'user_city'  					=> $this->input->post('service_city'),
				'user_state'					=> $this->input->post('service_state'),
				'user_postalcode' 				=> $this->input->post('service_postalcode'),
				'location_phone_no' 			=> $this->input->post('service_location_phone_no'),
				'location_fax_no' 				=> $this->input->post('service_location_fax_no'),
				'location_name'					=> $this->input->post('service_location_name'),
				'location_contact_person' 		=> $this->input->post('service_location_contact_person'),
				'service_location_id' 			=> $this->input->post('service_location_id'),
				'contact_person_title' 			=> $this->input->post('service_location_contact_person_title'),
				'date_added'					=> $this->input->post('service_location_date')
			);
			$add_hospice = $this->service_location_model->create($array);

			$data['success'] = true;
			$this->templating_library->set('title','Create New Hospice');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');
			$this->templating_library->set_view('pages/create_service_location','pages/create_service_location' ,$data);
			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}
		else
		{
			$data['failed'] = true;
			$this->templating_library->set('title','Create New Hospice');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');
			$this->templating_library->set_view('pages/create_service_location','pages/create_service_location', $data);
			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}
	}

	public	function service_location_list()
	{
		$this->templating_library->set('title','List of Service Location');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch'  && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'distribution_supervisor') {
			$data['service_location_list'] = $this->service_location_model->get_all_service_location();

			$this->templating_library->set_view('pages/service_location_list','pages/service_location_list' , $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function get_service_location_list()
    {
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
                'search_item_fields_service_location' => $datatable['search']['value'],
            );

            $column = array(
                'service_location_id',
                'service_location_name'
            );
            $filters[$column[$datatable['order'][0]['column']]] = $datatable['order'][0]['dir'];
            $result = $this->service_location_model->get_service_location_list($filters, $start, $limit);
            //print_me($result);
            if ($result['totalCount'] > 0) {
            	$counter = 0;
                foreach ($result['result'] as $key => $value) {
                    if($value['status'] == 0) {
                    	$edit_button = '<button'.
                                ' type="button"'.
                                ' class="btn btn-info btn-xs edit_button_location_'.$value['location_id'].'"'.
                                ' data-toggle="modal"'.
                                ' data-target="#edit_service_location_'.$value['location_id'].'"'.
                                ' style="margin-right: 10px;">'.
                                '<i class="glyphicon glyphicon-pencil"></i> Edit'.
                                '</button>';
                    	$make_active_button = '<label'.
                    			' class="i-checks data_tooltip"'.
                    			' data-location-id="'.$value['location_id'].'"'.
                    			' title="Active">'.
                    			'<input type="checkbox" class="update_status location_status_'.$value['location_id'].'"'.
                    			' data-row-id="'.$counter.'"'.
                    			' data-status="'.$value['status'].'" data-location-id="'.$value['location_id'].'" />'.
                    			'<i></i> <span style="margin-left: 3px;" class="locate_status_'.$value['location_id'].'">Make Inactive</span>'.
                    			'</label>';
                    } else {
                    	$edit_button = '<button'.
                                ' type="button"'.
                                ' class="btn btn-info btn-xs edit_button_location_'.$value['location_id'].'"'.
                                ' data-toggle="modal"'.
                                ' data-target="#edit_service_location_'.$value['location_id'].'"'.
                                ' style="margin-right: 10px; margin-left: -10px">'.
                                '<i class="glyphicon glyphicon-pencil"></i> Edit'.
                                '</button>';
                    	$make_active_button = '<label'.
                    			' class="i-checks data_tooltip"'.
                    			' data-location-id="'.$value['location_id'].'"'.
                    			' title="Active">'.
                    			'<input type="checkbox" class="update_status location_status_'.$value['location_id'].'"'.
                    			' data-row-id="'.$counter.'"'.
                    			' data-status="'.$value['status'].'" data-location-id="'.$value['location_id'].'" checked />'.
                    			'<i></i> <span style="margin-left: 3px;" class="locate_status_'.$value['location_id'].'">Make Active</span>'.
                    			'</label>';
                    }
                    $value['actions'] = $edit_button.$make_active_button;
                    $response_data['data'][] = $value;
                    $counter++;
                }
            }
            $response_data['draw'] = $datatable['draw'];
            $response_data['recordsFiltered'] = $result['totalCount'];
            $response_data['recordsTotal'] = $result['totalCount'];
        }
        echo json_encode($response_data);
    }

    public function update_service_location () {
    	$checked = check_code($hash);
	    $hospiceID = get_id_from_code($hash);

		$data_post = $this->input->post();

		//$this->form_validation->set_rules('hospice_name', 'Hospice Name', 'xss_clean|required|is_unique[dme_hospice.hospice_name]');

		$array = array(
			'user_street' 					=> $this->input->post('p_address'),
			'user_city'  					=> $this->input->post('service_city'),
			'user_state'					=> $this->input->post('service_state'),
			'user_postalcode' 				=> $this->input->post('service_postalcode'),
			'location_phone_no' 			=> $this->input->post('service_location_phone_no'),
			'location_fax_no' 				=> $this->input->post('service_location_fax_no'),
			'location_name'					=> $this->input->post('service_location_name'),
			'location_contact_person' 		=> $this->input->post('service_location_contact_person'),
			'service_location_id' 			=> $this->input->post('service_location_id'),
			'contact_person_title' 			=> $this->input->post('service_location_contact_person_title'),
			'date_added'					=> $this->input->post('service_location_date')
		);


		$update_service_location = $this->service_location_model->update_service_location($this->input->post('location_id'), $array);

		redirect('service_location/service_location_list','refresh');
    }

    public function update_status($location_id, $status) {
    	$array = array(
    		'status' => $status
    	);
		$update_status = $this->service_location_model->update_service_location($location_id, $array);

		if($update_status) {
			$this->response_code = 0;
        	$this->response_message = 'Updated Service Location Successfully.';
		} else {
			$this->response_code = 1;
        	$this->response_message = 'Updating Service Location Error.';
		}


	    echo json_encode(array(
	        'error' => $this->response_code,
	        'message' => $this->response_message,
	    ));
    }

    public function get_all_service_location()
    {
        $response = array('data' => array());
        $response['service_location_list'] = array();

        $response['service_location_list'] = $this->service_location_model->get_all_service_location();
        echo json_encode($response);
    }

    public function select_service_location($location_id) {
    	$this->session->set_userdata('user_location', $location_id);
    }
}