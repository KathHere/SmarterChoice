<?php
Class lgsts_dispatch_order_status extends Ci_Controller
{
	var $response_code = 1;//false or error default
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

  	public function order_list($page = 1)
	{
		$lgsts_user = $this->session->userdata('lgsts_user');
		$current_date = date("Y-m-d");
		$data['dispatch_order_status_info'] = array();
		$data['dispatch_order_status_list'] = array();
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);

		try {
			$order_counters = $client->request('GET', 'dispatch/orderCounters', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				]
			]);
			$order_counters_body = json_decode($order_counters->getBody());

			if($order_counters->getStatusCode() == 200) {
				$data['dispatch_order_status_info']['order_counters'] = $order_counters_body->data;
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$order_counters_error = $e->getResponse()->getBody()->getContents();
		}

		try {
			$on_call_users = $client->request('GET', 'dispatch/getOnCallUsers', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				],
				'query' => [
					'onCallType' => 'driver',
					'date' => $current_date
				]
			]);
			$on_call_users_body = json_decode($on_call_users->getBody());

			if($on_call_users->getStatusCode() == 200) {
				$data['dispatch_order_status_info']['on_call_driver'] = $on_call_users_body->data->onCallUsers;
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$on_call_users_error = $e->getResponse()->getBody()->getContents();
		}

		try {
			$on_call_users = $client->request('GET', 'dispatch/getOnCallUsers', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				],
				'query' => [
					'onCallType' => 'screener',
					'date' => $current_date
				]
			]);
			$on_call_users_body = json_decode($on_call_users->getBody());

			if($on_call_users->getStatusCode() == 200) {
				$data['dispatch_order_status_info']['on_call_screener'] = $on_call_users_body->data->onCallUsers;
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$on_call_users_error = $e->getResponse()->getBody()->getContents();
		}

			try {
			// Get the search string from the query parameters
			$searchString = $this->input->get('searchString');
	
			// Check if a search query is present
			if (!empty($searchString)) {
				$orders = $client->request('GET', 'dispatch/orders', [
					'headers' => [ 
						'Accept' => 'application/json',
						'Authorization' => 'Bearer ' . $lgsts_user['user_token']
					],
					'query' => [
						'page' => $page, // Use the page parameter
						'limit' => 10,
						'searchQuery' => $searchString // Pass search query to the API
					]
				]);
			} else {
				// Make the API request without the search query
				$orders = $client->request('GET', 'dispatch/orders', [
					'headers' => [ 
						'Accept' => 'application/json',
						'Authorization' => 'Bearer ' . $lgsts_user['user_token']
					],
					'query' => [
						'page' => $page, // Use the page parameter
						'limit' => 10
					]
				]);
			}
	
			$orders_body = json_decode($orders->getBody());
	
			if($orders->getStatusCode() == 200) {
				$data['dispatch_order_status_list'] = $orders_body->data;
				$pagination_info = $orders_body->data->paginationInfo;
				$number_of_results = $pagination_info->numberOfResults;
				$number_of_pages = $pagination_info->numberOfPages;
				$current_page = intval($pagination_info->currentPage);
				$results_per_page = intval($pagination_info->resultsPerPage);                
				// Pass pagination data to your view
				$data['pagination'] = [
					'number_of_results' => $number_of_results,
					'number_of_pages' => $number_of_pages,
					'current_page' => $current_page,
					'results_per_page' => $results_per_page
				];
	
			}
			
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$orders_error = $e->getResponse()->getBody()->getContents();
		}

		$data['active_nav'] = "dispatch_order_status";
		$this->templating_library->set('title','Dispatch Order Status');
		$this->templating_library->set_view('common/lgsts-header','common/lgsts-header');
		$this->templating_library->set_view('common/lgsts-nav','common/lgsts-nav', $data);
		$this->templating_library->set_view('pages/lgsts/dispatch_order_status','pages/lgsts/dispatch_order_status', $data);
		$this->templating_library->set_view('common/lgsts-footer','common/lgsts-footer');
	}

	public function search_dispatch_work_orders($search_string= '', $page = 1)
	{
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);
		$lgsts_user = $this->session->userdata('lgsts_user');
		$data['dispatch_order_status_list'] = array();

		$search_string = '';
        if (isset($_GET['searchString'])) {
            $search_string = $_GET['searchString'];
        }
		try {
			$orders = $client->request('GET', 'dispatch/orders', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				],
				'query' => [
					'locationId' => $lgsts_user['account_location'],
					'searchQuery' => $search_string,
					'page' => $page, // Add page parameter
					'limit' => 10
				]
			]);
			$orders_body = json_decode($orders->getBody());
			if($orders->getStatusCode() == 200) {
				$data['dispatch_order_status_list'] = $orders_body->data;
				$pagination_info = $orders_body->data->paginationInfo;
				$number_of_results = $pagination_info->numberOfResults;
				$number_of_pages = $pagination_info->numberOfPages;
				$current_page = intval($pagination_info->currentPage);
				$results_per_page = intval($pagination_info->resultsPerPage);

				
				
				$data['pagination'] = [
                'number_of_results' => $number_of_results,
                'number_of_pages' => $number_of_pages,
                'current_page' => $current_page,
                'results_per_page' => $results_per_page,
				
			];
				echo json_encode(array(
					"data"		=> $data['dispatch_order_status_list'],
					"error" 	=> 0,
					"message" => $orders_body->message,
					"pagination" => $data['pagination']

				));
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$orders_error = $e->getResponse()->getBody()->getContents();
			print_r($orders_error);
		}
	}

	public function update_work_order_urgency($statusId, $uniqueId, $is_urgent) 
	{
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);
		$lgsts_user = $this->session->userdata('lgsts_user');
		
		$is_urgent_final = false;
		if ((int)$is_urgent == 0) {
			$is_urgent_final = true;
		}

		try {
			$urgency = $client->request('PUT', 'dispatch/updateUrgency', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				],
				'json' => [
					'statusId' => $statusId,
					'uniqueId' => $uniqueId,
					'urgency' => $is_urgent_final
				]
			]);

			$urgency_body = json_decode($urgency->getBody());
			if($urgency->getStatusCode() == 201) {
				echo json_encode(array(
					"error" 	=> 0,
					"message"	=> $urgency_body->message
				));
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$urgency_error = $e->getResponse()->getBody()->getContents();
			print_r($urgency_error);
		}
	}

	public function set_work_order_stop_number($statusId, $stopNumber) 
	{
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);
		$lgsts_user = $this->session->userdata('lgsts_user');

		try {
			$stop_number = $client->request('PUT', 'dispatch/setStopNumber', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				],
				'json' => [
					'statusId' => $statusId,
					'stopNumber' => $stopNumber
				]	
			]);

			$stop_number_body = json_decode($stop_number->getBody());
			if($stop_number->getStatusCode() == 200) {
				echo json_encode(array(
					"error" 	=> 0,
					"message"	=> $stop_number_body->message
				));
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$stop_number_error = $e->getResponse()->getBody()->getContents();
			print_r($stop_number_error);
		}
	}

	public function send_work_order_to_COS($statusId) 
	{
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);
		$lgsts_user = $this->session->userdata('lgsts_user');

		try {
			$send_to_cos = $client->request('PUT', 'dispatch/sendToCos', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				],
				'json' => [
					'statusId' => $statusId
				]	
			]);

			$send_to_cos_body = json_decode($send_to_cos->getBody());
			if($send_to_cos->getStatusCode() == 201) {
				echo json_encode(array(
					"error" 	=> 0,
					"message"	=> $send_to_cos_body->message
				));
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$send_to_cos_error = $e->getResponse()->getBody()->getContents();
			print_r($send_to_cos_error);
		}
	}

	public function assign_work_order_to_driver($statusId, $uniqueId, $driverId, $routeName) 
	{
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);
		$lgsts_user = $this->session->userdata('lgsts_user');

		if ($routeName == null) {
			$routeName = '';
		}

		try {
			$assign_driver = $client->request('PUT', 'dispatch/assignDriver', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				],
				'json' => [
					'statusId' => $statusId,
					'uniqueId' => $uniqueId,
					'driverAssignedId' => $driverId,
					'routeName' => $routeName,
					'latitude' => '',
					'longitude' => ''
				]	
			]);

			$assign_driver_body = json_decode($assign_driver->getBody());
			if($assign_driver->getStatusCode() == 201) {
				echo json_encode(array(
					"data" 		=> $assign_driver_body->data,
					"error" 	=> 0,
					"message"	=> $assign_driver_body->message
				));
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$assign_driver_error = $e->getResponse()->getBody()->getContents();
			print_r($assign_driver_error);
		}
	}

	public function view_logistics_work_order_details($medical_id, $hospiceID="", $unique_id="", $act_id="", $patientID="")
	{
		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_patient_noorder_info($medical_id, $hospiceID);
		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered_original($medical_id, $unique_id, $act_id);

		$data['pickup_discharge_date'] = '';
		if($act_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
            $data['pickup_discharge_date'] = $this->order_model->get_act_type_pickup_discharge_date($unique_id);
		}
		else if($act_id == 2 && $informations[0]['activity_typeid'] == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}
		else if($act_id == 2 && $informations[0]['activity_typeid'] == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}
		else if($act_id == 2 && $informations[0]['activity_typeid'] == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		if($act_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}
		if($act_id == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}
		if($act_id == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['work_order']			  = $unique_id;
		$data['medical_record_id']	  = $medical_id;
		$data['hospiceID']	  		  = $hospiceID;
		$data['patientID']	  		  = $informations[0]['patientID'];

		$this->templating_library->set_view('pages/lgsts/logistics_view_work_order_details','pages/lgsts/logistics_view_work_order_details', $data);
	}

}
