<?php
Class menu extends CI_Controller
{
	var $response_code = 1;//false or error default
	var $response_message = "";
	var $response_data = array();

	public function __construct()
	{
		parent::__construct();
		is_logged_in();
		date_default_timezone_set("America/Los_Angeles");
	}

	public function index()
	{
		$data['active_nav']	= "menu";
		$this->templating_library->set('title','Main Menu');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav',$data);
		$this->templating_library->set_view('pages/menu','pages/menu');
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function mobile_menu()
	{
		$data['active_nav']	= "menu";
		$this->templating_library->set('title','Main Menu');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav',$data);
		$this->templating_library->set_view('pages/menu','pages/menu');
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

}