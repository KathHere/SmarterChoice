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
		// Validate user inputs
		$this->load->library('form_validation');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('mobile_number', 'Mobile Number', 'required');
		$this->form_validation->set_rules('assignLocation', 'Assign Location', 'required');
		$this->form_validation->set_rules('onCall', 'On Call', 'required');
		$this->form_validation->set_rules('user_type', 'User Type', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('driverSignature', 'Signature', 'required');

		if ($this->form_validation->run() == FALSE) {
			// Handle validation errors
			$data['response_message'] = validation_errors();
		} else {
			// Extract user inputs
			$lastName = $this->input->post('last_name');
			$firstName = $this->input->post('first_name');
			$mobileNumber = $this->input->post('mobile_number');
			$assignLocation = $this->input->post('assignLocation');
			$isOnCall = $this->input->post('onCall') == 'yes' ? true : false;
			$userType = $this->input->post('user_type');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$driverSignature = $this->input->post('driverSignature');

			try {
				// Initialize Guzzle client
				$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);

				// Make API request to create user
				$response = $client->request('POST', 'auth/createUser', [
					'headers' => [
						'Accept' => 'application/json',
						'Authorization' => 'Bearer ' . $lgsts_user['user_token']
					],
					'json' => [
						'date' => $current_date,
						'lastName' => $lastName,
						'firstName' => $firstName,
						'mobileNumber' => $mobileNumber,
						'assignLocation' => $assignLocation,
						'isOnCall' => $isOnCall,
						'userType' => $userType,
						'username' => $username,
						'password' => $password,
						'driverSignature' => $driverSignature
					]
				]);

				$response_data = json_decode($response->getBody(), true);

				if ($response->getStatusCode() == 200) {
					// Success response
					$data['response_message'] = $response_data['message'];

					// Store user data in session
					$this->session->set_userdata('created_user', [
						'last_name' => $lastName,
						'first_name' => $firstName,
						'mobile_number' => $mobileNumber,
						'assignLocation' => $assignLocation,
						'onCall' => $isOnCall,
						'user_type' => $userType,
						'username' => $username,
						'driverSignature' => $driverSignature
					]);

					// Get current user info
					$user_info_response = $client->request('GET', 'user/currentUserInfo', [
						'headers' => [
							'Accept' => 'application/json',
							'Authorization' => 'Bearer ' . $lgsts_user['user_token']
						]
					]);
					$user_info_body = json_decode($user_info_response->getBody());

					if ($user_info_response->getStatusCode() == 200) {
						// Store current user info in session
						$this->session->set_userdata('current_user_info', $user_info_body->data);
					}
				} else {
					// Handle other status codes
					$data['response_message'] = "Error: Unexpected response from server";
				}
			} catch (GuzzleHttp\Exception\BadResponseException $e) {
				// Guzzle HTTP client exception
				$errorBody = $e->getResponse()->getBody()->getContents();
				$data['response_message'] = "Error: " . $errorBody;
			} catch (Exception $e) {
				// Other exceptions
				$data['response_message'] = "Error: " . $e->getMessage();
			}
		}

		// Load views and pass data
		$data['active_nav'] = "add_user";
		$this->templating_library->set('title', 'Dispatch Order Status');
		$this->templating_library->set_view('common/lgsts-header', 'common/lgsts-header');
		$this->templating_library->set_view('common/lgsts-nav', 'common/lgsts-nav', $data);
		$this->templating_library->set_view('pages/lgsts/add_user', 'pages/lgsts/add_user', $data);
		$this->templating_library->set_view('common/lgsts-footer', 'common/lgsts-footer');
	}

	public function profile()
	{
		$lgsts_user = $this->session->userdata('lgsts_user');
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);
		$data = array();
	
		try {
		
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
			$user_info_error = $e->getResponse()->getBody()->getContents();
		}	
		
		$edited_user_info = $this->session->userdata('edited_user_info');
		if (!empty($edited_user_info)) {
			// Merge edited user info with existing user info
			$data['user_info'] = (object) array_merge((array) $data['user_info'], $edited_user_info);
		}
	
		$data['active_nav'] = "profile";
		$this->templating_library->set('title', 'Dispatch Order Status');
		$this->templating_library->set_view('common/lgsts-header', 'common/lgsts-header');
		$this->templating_library->set_view('common/lgsts-nav', 'common/lgsts-nav', $data);
		$this->templating_library->set_view('pages/lgsts/profile', 'pages/lgsts/profile', $data);
		$this->templating_library->set_view('common/lgsts-footer', 'common/lgsts-footer');
	}

	public function update_user_info() 
	{
		$request = \Config\Services::request();
		$data = json_decode($request->getBody()->getContents(), true);
	
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);
		$lgsts_user = $this->session->userdata('lgsts_user');
		
		try {
			$response = $client->request('PUT', 'editUserInfo', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				],
				'json' => $data
			]);
	
			$body = json_decode($response->getBody());
			if($response->getStatusCode() == 200) {
				$this->session->set_userdata('edited_user_info', $data);
				
				return json_encode(array( 
					"error"     => 0,
					"message"   => $body->message
				));
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$error = $e->getResponse()->getBody()->getContents();
			return $error; 
		}
	}
}	