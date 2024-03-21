<?php
Class lgsts_route_sheet extends Ci_Controller
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

    public function details($route_id = 0)
	{
		$lgsts_user = $this->session->userdata('lgsts_user');
		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);

		try {
			$route_details = $client->request('GET', 'route/details', [
				'headers' => [ 
					'Accept' => 'application/json',
					'Authorization' => 'Bearer ' . $lgsts_user['user_token']
				],
				'query' => [
					'routeId' => $route_id
				]
			]);
			$route_details_body = json_decode($route_details->getBody());

			if($route_details->getStatusCode() == 200) {
				$data['route_details'] = $route_details_body->data;
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$route_details_error = $e->getResponse()->getBody()->getContents();
		}

		$data['active_nav'] = "daily_routes";
		$this->templating_library->set('title','Route Sheet / O2 Logs');
		$this->templating_library->set_view('common/lgsts-header','common/lgsts-header');
		$this->templating_library->set_view('common/lgsts-nav','common/lgsts-nav', $data);
		$this->templating_library->set_view('pages/lgsts/route_sheet_o2_logs','pages/lgsts/route_sheet_o2_logs', $data);
		$this->templating_library->set_view('common/lgsts-footer','common/lgsts-footer');
	}
}
