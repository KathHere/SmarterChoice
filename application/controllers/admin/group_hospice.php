<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ;?>
<?php
	Class group_hospice extends Ci_Controller
	{

		var $include_top = 'landingpage_template/include_top';
		var $header = 'main_template/header';
		var $client_header = 'client_header';
		var $group_form = 'admin/group_form';
		var $dashboard = 'admin/dashboard';
		var $footer = 'main_template/footer';
		var $include_bottom = 'landingpage_template/include_bottom';

		function __construct()
		{
			parent::__construct();
			$this->load->model('hospice_model');
			//if($this->session->userdata('account_type') != 'admin') redirect('landingpage');
		}

		function index()
		{
			$this->templating_library->set('title','Advantage Home Medical Services Inc. | Dashboard');
			$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
			
		 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
			{
			$this->templating_library->set_view($this->header, $this->header);
			}
			else
			{
			$this->templating_library->set_view($this->client_header, $this->client_header);
			}
		 	$this->templating_library->set_view($this->group_form , 'admin/group_form');
		 	$this->templating_library->set_view($this->footer, 'main_template/footer');
		 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
			
		}
		
		function hospice_list()
		{
			$result['hospices'] = $this->hospice_model->list_group();
			
			// printA($result['hospices']);
			// exit;
			
			$this->templating_library->set('title','Advantage Home Medical Services Inc. | Dashboard');
			$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
			
		 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
			{
				$this->templating_library->set_view($this->header, $this->header);
			}
			else
			{
				$this->templating_library->set_view($this->client_header, $this->client_header);
			}
		 	$this->templating_library->set_view('admin/hospice_list' , 'admin/hospice_list', $result);
		 	$this->templating_library->set_view($this->footer, 'main_template/footer');
		 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
			
		}
		
		function create()
		{
			$this->form_validation->set_rules('hospice_name', 'Hospice Name', 'xss_clean|required|is_unique[dme_hospice.hospice_name]');
			
			$array = array(
				'hospice_name'  =>  $this->input->post('hospice_name')
			);
			
			
			if($this->form_validation->run() == TRUE)
			{
				$add_hospice = $this->hospice_model->create($array);
				
				$data['success'] = true;
				$this->templating_library->set('title','Success! | Advantage Home Medical Services Inc.');
				$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
				{
				$this->templating_library->set_view($this->header, $this->header);
				}
				else
				{
				$this->templating_library->set_view($this->client_header, $this->client_header);
				}
				$this->templating_library->set_view($this->group_form , 'admin/group_form' , $data);
				$this->templating_library->set_view($this->footer, 'main_template/footer');
				$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
			}
			else{
				$data['failed'] = true;
				$this->templating_library->set('title','Error | Advantage Home Medical Services Inc.');
				$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				$this->templating_library->set_view($this->header, 'landingpage_template/header');
				$this->templating_library->set_view($this->group_form , 'admin/group_form' , $data);
				$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
				$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
			
			}
			
		}
		
		
		function update_hospice($hash = '')
		{
			$checked = check_code($hash);
		    $hospiceID = get_id_from_code($hash);
			
			$data_post = $this->input->post();
			
			$this->form_validation->set_rules('hospice_name', 'Hospice Name', 'xss_clean|required|is_unique[dme_hospice.hospice_name]');
			
			$array = array(
				'hospice_name'  =>  $this->input->post('hospice_name')
			);
			
			
			if($this->form_validation->run() == TRUE)
			{
				$add_hospice = $this->hospice_model->update_hospice($hospiceID, $array);
				
				redirect('admin/group_hospice/hospice_list','refresh');
			}
			else{
				redirect('admin/group_hospice/hospice_list','refresh');
			}
			
		}
		
		function delete_hospice($hash = '')
		{
			$checked = check_code($hash);
		    $hospiceID = get_id_from_code($hash);
			
			if($hospiceID != '')
			{
				//$result = $this->hospice_model->list_group();
				$this->hospice_model->delete_hospice($hospiceID);
				redirect('admin/group_hospice/hospice_list','refresh'); 
			}
			else
			{
				redirect(base_url());
			}
		}
		
	}