<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ;?>
<?php
	Class dashboard extends Ci_Controller
	{

		var $include_top = 'landingpage_template/include_top';
		var $header = 'main_template/header';
		var $client_header = 'client_header';
		var $register = 'register_form';
		var $dashboard = 'admin/dashboard';
		var $footer = 'main_template/footer';
		var $include_bottom = 'landingpage_template/include_bottom';

		function __construct()
		{
			parent::__construct();
			// if($this->session->userdata('account_type') != 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') != 'hospice_admin') 
			// {
			// 	redirect('landingpage');
			// }
			
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
		 	$this->templating_library->set_view($this->dashboard , 'admin/dashboard');
		 	$this->templating_library->set_view($this->footer, 'main_template/footer');
		 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
			
			
		}
	}