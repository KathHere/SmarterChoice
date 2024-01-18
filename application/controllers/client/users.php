<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ;?>
<?php
	Class users extends Ci_Controller
	{
		var $include_top = 'landingpage_template/include_top';
		var $header = 'main_template/header';
		var $client_header = 'client_header';
		var $users = 'admin/user_list';
		var $dashboard = 'admin/dashboard';
		var $footer = 'main_template/footer';
		var $include_bottom = 'landingpage_template/include_bottom';

		function __construct()
		{
			parent::__construct();
			$this->load->model('user_model');
			$this->load->model('hospice_model');
			//if(!logged_in()) redirect('landingpage'); //if not logged in, redirect 
		}

		function index()
		{
			$hospiceID = $this->session->userdata('group_id');

			$result = $this->user_model->get_hosp_admin_users($hospiceID);
			$data['hospices'] = $this->hospice_model->list_group();
			
			$data['users'] = ($result) ? $result : FALSE;
			// printA($data['users']);
			// exit;

			$this->templating_library->set('title','List of Users | Advantage Home Medical Services Inc.');
			$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
			$this->templating_library->set_view($this->client_header, 'client_header');
			$this->templating_library->set_view('admin/hosp_admin_created_users' , 'admin/hosp_admin_created_users' , $data);
			$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
			$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
		}
	}
