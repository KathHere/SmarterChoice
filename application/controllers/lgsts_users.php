<?php
Class lgsts_users extends Ci_Controller
{
	var $response_code = 1; //false or error default
	var $response_message = "";
	var $response_data = array();

	public function __construct()
	{
		parent::__construct();
		is_lgsts_user_logged_in();

		date_default_timezone_set("America/Los_Angeles");
		$this->load->model("order_model");
		$this->load->library('encryption');
	}

    public function add_user()
	{
		$lgsts_user = $this->session->userdata('lgsts_user');
		$current_date = date("Y-m-d");

		$data['active_nav'] = "add_user";
		$this->templating_library->set('title','Dispatch Order Status');
		$this->templating_library->set_view('common/lgsts-header','common/lgsts-header');
		$this->templating_library->set_view('common/lgsts-nav','common/lgsts-nav', $data);
		$this->templating_library->set_view('pages/lgsts/add_user','pages/lgsts/add_user', $data);
		$this->templating_library->set_view('common/lgsts-footer','common/lgsts-footer');
	}

}
