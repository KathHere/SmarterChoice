<?php
Class lgsts_daily_routes extends Ci_Controller
{
	var $response_code = 1;//false or error default
	var $response_message = "";
	var $response_data = array();

	public function __construct()
	{
		parent::__construct();
		is_lgsts_user_logged_in();

		date_default_timezone_set("America/Los_Angeles");
		$this->load->library('encryption');
	}

    public function route_list()
	{
		$lgsts_user = $this->session->userdata('lgsts_user');
		$data['daily_routes'] = array();
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.smarterchoice.us/api/v1/']);

		try {
			$daily_routes = $client->request('GET', 'route', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				]
			]);
			$daily_routes_body = json_decode($daily_routes->getBody());

			if($daily_routes->getStatusCode() == 200) {
				$data['daily_routes'] = $daily_routes_body->data->routes;
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$daily_routes_error = $e->getResponse()->getBody()->getContents();
		}

		$data['active_nav'] = "daily_routes";
		$this->templating_library->set('title','Daily Routes');
		$this->templating_library->set_view('common/lgsts-header','common/lgsts-header');
		$this->templating_library->set_view('common/lgsts-nav','common/lgsts-nav', $data);
		$this->templating_library->set_view('pages/lgsts/daily_routes','pages/lgsts/daily_routes', $data);
		$this->templating_library->set_view('common/lgsts-footer','common/lgsts-footer');
	}

	public function submit_route_name_autosave($routeId, $routeName)
	{
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.smarterchoice.us/api/v1/']);
		$lgsts_user = $this->session->userdata('lgsts_user');

		try {
			$route_name_updated = $client->request('PUT', 'route/name', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				],
				'json' => [
					'routeId' => (int)$routeId,
					'routeName' => $routeName
				]	
			]);

			$route_name_updated_body = json_decode($route_name_updated->getBody());
			if($route_name_updated->getStatusCode() == 201) {
				echo json_encode(array(
					"error" 	=> 0,
					"message"	=> $route_name_updated_body->message
				));
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$route_name_error = $e->getResponse()->getBody()->getContents();
			print_r($route_name_error);
		}
	}

}
