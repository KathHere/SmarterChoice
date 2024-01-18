<?php
Class landing_page extends Ci_Controller
{
	
	var $include_top = 'landingpage_template/include_top';
	var $header = 'landingpage_template/header';
	var $landingpage = 'landingpage';
	var $footer = 'landingpage_template/footer';
	var $include_bottom = 'landingpage_template/include_bottom';


	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Los_Angeles");
	}


	function index(){
	 	$this->templating_library->set_view('pages/landing_page','pages/landing_page', "");
	}


}