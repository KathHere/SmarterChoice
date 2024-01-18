<?php
Class gallery extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
	}
	
	public function beds()
	{
		$data['active'] = true;
		$data['active_nav'] = "galleries";
		$this->templating_library->set('title','SmarterChoice - Beds');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('gallery/beds','gallery/beds' ,$data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	
	public function oxygen()
	{
		$data['active'] = true;
		$data['active_nav'] = "galleries";
		$this->templating_library->set('title','SmarterChoice - Oxygen');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('gallery/oxygen','gallery/oxygen',$data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	
	public function wheelchair()
	{
		$data['active'] = true;
		$data['active_nav'] = "galleries";
		$this->templating_library->set('title','SmarterChoice - Oxygen');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('gallery/wheelchair','gallery/wheelchair',$data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	
	public function respiratory()
	{
		$data['active'] = true;
		$data['active_nav'] = "galleries";
		$this->templating_library->set('title','SmarterChoice - Respiratory');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('gallery/respiratory','gallery/respiratory',$data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	
	public function bath_aids()
	{
		$data['active'] = true;
		$data['active_nav'] = "galleries";
		$this->templating_library->set('title','SmarterChoice - Ambulatory');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('gallery/bath_aids','gallery/bath_aids',$data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	 
	public function walkaids()
	{
		$data['active'] = true;
		$data['active_nav'] = "galleries";
		$this->templating_library->set('title','SmarterChoice - Mattress');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav' , $data);
		$this->templating_library->set_view('gallery/walkaids','gallery/walkaids',$data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	
	public function lift_slings()
	{
		$data['active'] = true;
		$data['active_nav'] = "galleries";
		$this->templating_library->set('title','SmarterChoice - Hydraulic');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav' , $data);
		$this->templating_library->set_view('gallery/lift_slings','gallery/lift_slings',$data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	
	public function commode()
	{
		$data['active'] = true;
		$data['active_nav'] = "galleries";
		$this->templating_library->set('title','SmarterChoice - Oxygen');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav' , $data);
		$this->templating_library->set_view('gallery/commode','gallery/commode',$data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

}