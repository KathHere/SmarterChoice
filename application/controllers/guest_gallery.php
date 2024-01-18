<?php
Class guest_gallery extends CI_Controller
{
	var $include_top = 'landingpage_template/include_top';
	var $header = 'main_template/header';
	var $client_header = 'client_header';
	var $gallery = 'photo_gallery';
	var $landingpage_gallery = 'landingpage_gallery';
	var $footer = 'main_template/footer';
	var $include_bottom = 'landingpage_template/include_bottom';
	var $landingpage_header = 'landingpage_template/header';
	var $gallery_header = 'landingpage_template/gallery_header';
	var $gallery_footer = 'landingpage_template/gallery_footer';
	
	var $beds = 'beds_gallery';
	var $oxygen_gallery = 'oxygen_gallery';
	var $wheelchair_gallery = 'wheelchair_gallery';
	var $respiratory_gallery = 'respiratory_gallery';
	var $mattress_gallery = 'mattress_gallery';
	var $ambulatory_gallery = 'ambulatory_gallery';
	var $hydraulic_gallery = 'hydraulic_gallery';
	var $commode_gallery = 'commode_gallery';


	function __construct()
	{
		parent::__construct();
		//if($this->session->userdata('account_type') == null) redirect('landingpage');
	}

	
	function landingpage_gallery()
	{
		$this->templating_library->set('title','DME Photo Gallery');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		$this->templating_library->set_view($this->landingpage_header, $this->landingpage_header);
		$this->templating_library->set_view($this->gallery, $this->gallery);
		$this->templating_library->set_view($this->gallery_footer, $this->gallery_footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	
	}
	
	public function beds()
	{
		$this->templating_library->set('title','DME Photo Gallery - Beds');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		$this->templating_library->set_view($this->gallery_header, $this->gallery_header);
		$this->templating_library->set_view($this->beds, $this->beds);
		$this->templating_library->set_view($this->gallery_footer, $this->gallery_footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	}
	
	public function oxygen()
	{
		$this->templating_library->set('title','DME Photo Gallery - Oxygen');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		$this->templating_library->set_view($this->gallery_header, $this->gallery_header);
		$this->templating_library->set_view($this->oxygen_gallery, $this->oxygen_gallery);
		$this->templating_library->set_view($this->gallery_footer, $this->gallery_footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	}
	
	public function wheelchair()
	{
		$this->templating_library->set('title','DME Photo Gallery - Oxygen');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		$this->templating_library->set_view($this->gallery_header, $this->gallery_header);
		$this->templating_library->set_view($this->wheelchair_gallery, $this->wheelchair_gallery);
		$this->templating_library->set_view($this->gallery_footer, $this->gallery_footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	}
	
	public function respiratory()
	{
		$this->templating_library->set('title','DME Photo Gallery - Respiratory');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		$this->templating_library->set_view($this->gallery_header, $this->gallery_header);
		$this->templating_library->set_view($this->respiratory_gallery, $this->respiratory_gallery);
		$this->templating_library->set_view($this->gallery_footer, $this->gallery_footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	}
	
	public function bath_aids()
	{
		$this->templating_library->set('title','DME Photo Gallery - Ambulatory');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		$this->templating_library->set_view($this->gallery_header, $this->gallery_header);
		$this->templating_library->set_view($this->ambulatory_gallery, $this->ambulatory_gallery);
		$this->templating_library->set_view($this->gallery_footer, $this->gallery_footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	}
	 
	public function walkaids()
	{
		$this->templating_library->set('title','DME Photo Gallery - Mattress');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		$this->templating_library->set_view($this->gallery_header, $this->gallery_header);
		$this->templating_library->set_view($this->mattress_gallery, $this->mattress_gallery);
		$this->templating_library->set_view($this->gallery_footer, $this->gallery_footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	}
	
	public function lift_slings()
	{
		$this->templating_library->set('title','DME Photo Gallery - Hydraulic');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		$this->templating_library->set_view($this->gallery_header, $this->gallery_header);
		$this->templating_library->set_view($this->hydraulic_gallery, $this->hydraulic_gallery);
		$this->templating_library->set_view($this->gallery_footer, $this->gallery_footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom); 
	}
	
	public function commode()
	{
		$this->templating_library->set('title','DME Photo Gallery - Oxygen');
		$this->templating_library->set_view($this->include_top, $this->include_top);
		$this->templating_library->set_view($this->gallery_header, $this->gallery_header);
		$this->templating_library->set_view($this->commode_gallery, $this->commode_gallery);
		$this->templating_library->set_view($this->gallery_footer, $this->gallery_footer);
		$this->templating_library->set_view($this->include_bottom, $this->include_bottom);
	}

}