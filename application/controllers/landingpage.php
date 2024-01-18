<?php
Class landingpage extends Ci_Controller
{
	
	var $include_top = 'landingpage_template/include_top';
	var $header = 'landingpage_template/header';
	var $landingpage = 'landingpage';
	var $footer = 'landingpage_template/footer';
	var $include_bottom = 'landingpage_template/include_bottom';


	function __construct(){
		parent::__construct();
	}


	function index(){
		$this->templating_library->set('title','Advantage Home Medical Services Inc. - Durable Medical Equipment Las Vegas Nevada.');
		$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
	 	$this->templating_library->set_view($this->header, 'landingpage_template/header');
	 	$this->templating_library->set_view($this->landingpage , 'landingpage');
	 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
	 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
	}
	
		function contact(){
		$this->templating_library->set('title','Advantage Home Medical Services Inc. - Durable Medical Equipment Las Vegas Nevada.');
		$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
	 	$this->templating_library->set_view($this->header, 'landingpage_template/header');
	 	$this->templating_library->set_view('contact', 'contact');
	 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
	 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
	}


}