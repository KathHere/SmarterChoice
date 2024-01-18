<?php
Class photo_gallery extends CI_Controller
{
	var $include_top = 'landingpage_template/include_top';
	var $header = 'main_template/header';
	var $client_header = 'client_header';
	var $gallery = 'photo_gallery';
	var $landingpage_gallery = 'landingpage_gallery';
	var $footer = 'main_template/footer';
	var $include_bottom = 'landingpage_template/include_bottom';
	var $landingpage_header = 'landingpage_template/header';


	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('account_type') == null) redirect('landingpage');
	}

	function gallery()
	{
		$this->templating_library->set('title','DME Photo Gallery');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		
		if($this->session->userdata('account_type') == 'admin')
		{
		$this->templating_library->set_view($this->header, $this->header);
		}
		else
		{
		$this->templating_library->set_view($this->client_header, $this->client_header);
		}
		
		$this->templating_library->set_view($this->gallery, $this->gallery);
		$this->templating_library->set_view($this->footer, $this->footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	}
	
	function landingpage_gallery()
	{
		$this->templating_library->set('title','DME Photo Gallery');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		$this->templating_library->set_view($this->landingpage_header, $this->landingpage_header);
		$this->templating_library->set_view($this->gallery, $this->gallery);
		$this->templating_library->set_view($this->footer, $this->footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	
	}

}