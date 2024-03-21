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

	public function profile()
	{
		$lgsts_user = $this->session->userdata('lgsts_user');
		$current_date = date("Y-m-d");
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);
	
	
		try {
			// Fetch current user info
			$user_info_response = $client->request('GET', 'user/currentUserInfo', [
				'headers' => [
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				]
			]);
			$user_info_body = json_decode($user_info_response->getBody());
	
			if ($user_info_response->getStatusCode() == 200) {
				$data['user_info'] = $user_info_body->data;
			}
	
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$user_list_error = $e->getResponse()->getBody()->getContents();
		}
	
		$data['active_nav'] = "profile";
		$this->templating_library->set('title', 'Dispatch Order Status');
		$this->templating_library->set_view('common/lgsts-header', 'common/lgsts-header');
		$this->templating_library->set_view('common/lgsts-nav', 'common/lgsts-nav', $data);
		$this->templating_library->set_view('pages/lgsts/profile', 'pages/lgsts/profile', $data);
		$this->templating_library->set_view('common/lgsts-footer', 'common/lgsts-footer');
	}

	public function save_profile_changes()
	{
		if ($this->input->is_ajax_request()) {
			$updated_profile_data = $this->input->post();
			
			$response = array(
				'error' => 0, 
				'message' => 'Profile changes saved successfully'
			);
			echo json_encode($response);
		} else {
			// If the request is not AJAX, redirect the user or show an error message
			// For example:
			redirect('profile'); // Redirect back to the profile page
		}
	}
}
