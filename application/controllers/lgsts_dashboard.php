<?php
Class lgsts_dashboard extends Ci_Controller
{
	var $response_code = 1;//false or error default
	var $response_message = "";
	var $response_data = array();

	public function __construct()
	{
		parent::__construct();
		is_lgsts_user_logged_in();

		date_default_timezone_set("America/Los_Angeles");
		$this->load->library('encryption');
	}

    public function main()
	{
		$data_get = $this->input->get();

		$data['active_nav'] = "dashboard";
		$this->templating_library->set('title','Dashboard');
		$this->templating_library->set_view('common/lgsts-header','common/lgsts-header');
		$this->templating_library->set_view('common/lgsts-nav','common/lgsts-nav', $data);
		$this->templating_library->set_view('pages/lgsts/dashboard','pages/lgsts/dashboard', $data);
		$this->templating_library->set_view('common/lgsts-footer','common/lgsts-footer');
	}
}
