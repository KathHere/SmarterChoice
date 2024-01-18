<?php
Class lgsts_driver_order_list extends Ci_Controller
{
	var $response_code = 1; //false or error default
	var $response_message = "";
	var $response_data = array();

	public function __construct()
	{
		parent::__construct();
		is_lgsts_user_logged_in();

		date_default_timezone_set("America/Los_Angeles");
		$this->load->library('encryption');
	}

    public function order_list($routeId = 0)
	{
		$lgsts_user = $this->session->userdata('lgsts_user');
		$current_date = date("Y-m-d");
		$data['driver_order_list_info'] = array();
		$data['driver_order_list_work_orders'] = array();
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.smarterchoice.us/api/v1/']);

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
				$data['driver_order_list_info']['on_call_driver'] = $on_call_users_body->data->onCallUsers;
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
				$data['driver_order_list_info']['on_call_screener'] = $on_call_users_body->data->onCallUsers;
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$on_call_users_error = $e->getResponse()->getBody()->getContents();
		}

		try {
			$orders = $client->request('GET', 'driver/orders', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				],
				'query' => [
					'routeId' => $routeId
				]
			]);
			$orders_body = json_decode($orders->getBody());

			if($orders->getStatusCode() == 200) {
				$data['driver_order_list_work_orders'] = $orders_body->data;
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$orders_error = $e->getResponse()->getBody()->getContents();
		}

		$data['active_nav'] = "daily_routes";
		$this->templating_library->set('title','Driver Order List');
		$this->templating_library->set_view('common/lgsts-header','common/lgsts-header');
		$this->templating_library->set_view('common/lgsts-nav','common/lgsts-nav', $data);
		$this->templating_library->set_view('pages/lgsts/driver_order_list','pages/lgsts/driver_order_list', $data);
		$this->templating_library->set_view('common/lgsts-footer','common/lgsts-footer');
	}
}
