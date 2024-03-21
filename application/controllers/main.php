<?php
Class main extends Ci_Controller
{
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set("America/Los_Angeles");
		$this->load->model('user_model');
		$this->load->model('hospice_model');
		$this->load->model('patient_model');
	}
	public function index()
	{
		$this->templating_library->set_view('pages/landing_page','pages/landing_page', "");
	}

	public function login()
	{
		$this->templating_library->set('title','SmarterChoice Login');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('pages/login','pages/login');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function lgsts_login()
	{
		$data['lgsts_login_failed'] = false;
		$this->templating_library->set('title','SmarterChoice Logistics Login');
		$this->templating_library->set_view('pages/lgsts/logistics-login','pages/lgsts/logistics-login', $data);
	}

	public function process_lgsts_login()
	{
		$data_post = $this->input->post();

		$username = $data_post['username'];
		$password = $data_post['password'];

		$client = new GuzzleHttp\Client(['base_uri' => 'https://api.dmefy.com/api/v1/']);
		try {
			$login = $client->request('POST', 'auth/login', [
				'headers' => [ 
					'Accept' => 'application/json'
				], 
				'json' => [
					'username' => $username,
					'password' => $password
				]	
			]);

			$login_body = json_decode($login->getBody());

			if($login->getStatusCode() == 201) {
				$sess_array = array(
					'userID' 	      => '',
					'email'      	  => '',
					'firstname'       => '',
					'lastname'        => '',
					'username'        => '',
					'password'        => '',
					'phone_num'       => '',
					'mobile_num'      => '',
					'group_name'      => '',
					'group_id'        => '',
					'address'         => '',
					'user_location'	  => '',
					'default_location'=> '',
					'account_type'	  => '',
					'is_first_loggedin' => '',
					'is_changed_password' => '',
					'location_date_time_zone' => ''
				);
			  	$this->session->set_userdata($sess_array);

				$lgsts_account_type = '';
				if($login_body->data->user->user_type == 'super_admin' || $login_body->data->user->user_type == 'admin' || $login_body->data->user->user_type == 'dispatcher') {
					$lgsts_account_type = 'dme_admin';
				}

				$token = $login_body->data->token;
				$lgsts_sess_array = array(
					'lgsts_user'	=> array(
						'id' => $login_body->data->user->id,
						'first_name' => $login_body->data->user->first_name,
						'last_name' => $login_body->data->user->last_name,
						'username' => $login_body->data->user->username,
						'mobile_number' => $login_body->data->user->mobile_number,
						'user_type' => $login_body->data->user->user_type,
						'account_location' => $login_body->data->user->account_location,
						'is_on_call' => $login_body->data->user->is_on_call,
						'user_token' => $token
					),
					'account_type' => $lgsts_account_type
				);
				$this->session->set_userdata($lgsts_sess_array);
				redirect('lgsts_dashboard/main');
			} else {
				$data['lgsts_login_failed'] = true;
				$this->templating_library->set('title','SmarterChoice Logistics Error Login');
				$this->templating_library->set_view('pages/lgsts/logistics-login','pages/lgsts/logistics-login', $data);
			}
		} catch (GuzzleHttp\Exception\BadResponseException $e) {
			$login = $e->getResponse()->getBody()->getContents();

			$data['lgsts_login_failed'] = true;
			$this->templating_library->set('title','SmarterChoice Logistics Error Login');
			$this->templating_library->set_view('pages/lgsts/logistics-login','pages/lgsts/logistics-login', $data);
		}
	}

	public function lgsts_logout()
	{
  	    $this->session->unset_userdata('lgsts_user');
		$this->session->sess_destroy();
		redirect(base_url()."main/lgsts_login",'refresh');
	}

	public function user_login()
	{
     	$data['sucess'] = false;
		$this->templating_library->set('title','Advantage Home Medical Services Inc. | Login');
		$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
	 	$this->templating_library->set_view($this->login_header, 'login_header');
	 	$this->templating_library->set_view($this->login , 'login',  $data);
	 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
	 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
	}

	public function privacy_policy()
	{
		$this->templating_library->set('title','SmarterChoice Privacy Policy');
		$this->templating_library->set_view('pages/privacy_policy','pages/privacy_policy');
	}

	public function process_login()
	{
		$data_post = $this->input->post();

		$data['account_inactive'] = false;
		$type = $this->session->userdata('account_type');
		//$email = $this->session->userdata('email');
		$username = $data_post['username'];
		$password = $data_post['password'];

		$login_result = $this->user_model->login_v2($username, $password, $type);

		$this->check_database($password);

		if($login_result)
		{
			if ($login_result[0]->group_id == 0) {
				foreach($login_result as $row)
	        	{
	            	if($row->account_type == 'dme_admin' || $row->account_type == 'dme_user')
	            	{
	            		redirect('menu');
	            	}
	            	else
	            	{
	            		redirect('menu');
	            	}
            	}
			} else {
				if ($login_result[0]->account_active_sign == 0) {
					$data['account_inactive'] = true;
					$this->templating_library->set('title','Error Loggin in');
					$this->templating_library->set_view('common/head','common/head');
					$this->templating_library->set_view('pages/login','pages/login', $data);
					$this->templating_library->set_view('common/foot','common/foot');
				} else {
					foreach($login_result as $row)
		        	{
		            	if($row->account_type == 'dme_admin' || $row->account_type == 'dme_user')
		            	{
		            		redirect('menu');
		            	}
		            	else
		            	{
		            		redirect('menu');
		            	}
	            	}
				}
			}
    	}
    	else
    	{
			$data['failed'] = true;
			$this->templating_library->set('title','Error Loggin in');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('pages/login','pages/login', $data);
			$this->templating_library->set_view('common/foot','common/foot');
    	}
	}

	public function check_database($password)
	{
		$type = $this->input->post('account_type');
        $username = $this->input->post('username');

        $result = $this->user_model->login($username, $password, $type);
        if($result)
        {
            $sess_array = array();
            foreach($result as $row)
            {
            	$location_info = $this->user_model->get_user_location_info($row->user_location);

                $sess_array = array(
                        'userID' 	      => $row->userID,
                        'email'      	  => $row->email,
                        'firstname'       => $row->firstname,
                        'lastname'        => $row->lastname,
                        'username'        => $row->username,
						'password'        => $row->password,
                        'phone_num'       => $row->phone_num,
						'mobile_num'      => $row->mobile_num,
					    'group_name'      => $row->group_name,
					    'group_id'        => $row->group_id,
						'address'         => $row->address,
						'user_location'	  => $row->user_location,
						'default_location' => $row->user_location,
                        'account_type'	  => $row->account_type,
                        'is_first_loggedin' => $row->is_first_loggedin,
                        'is_changed_password' => $row->is_changed_password,
                        'location_date_time_zone' => $location_info['location_date_time_zone']
                );

				if ($sess_array['account_type'] == 'dme_admin' || $sess_array['account_type'] == 'dme_user' || $sess_array['account_type'] == 'biller' || $sess_array['account_type'] == 'customer_service') {
					if ($sess_array['default_location'] == 1) {
						$sess_array['user_location'] = 0;
					}
				}

                $this->session->set_userdata($sess_array);
				$this->session->unset_userdata('lgsts_user');
            }
            return TRUE;
        }
        else
        {
            return FALSE;
        }
	}

	public function logout()
	{
        $sess_array = array(
				'userID' 	      => '',
				'email'      	  => '',
				'firstname'       => '',
				'lastname'        => '',
				'username'        => '',
				'password'        => '',
				'phone_num'       => '',
				'mobile_num'      => '',
				'group_name'      => '',
				'group_id'        => '',
				'address'         => '',
				'user_location'	  => '',
				'default_location'=> '',
				'account_type'	  => '',
				'is_first_loggedin' => '',
				'is_changed_password' => '',
				'location_date_time_zone' => ''
        );

  	    $this->session->set_userdata($sess_array);
		$this->session->sess_destroy();
		redirect(base_url()."main/login",'refresh');
	}


	//LISTING OF CUSTOMERS
	public function patient_list()
	{
		$this->templating_library->set('title','List of all Customers');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'distribution_supervisor') {
			$data['patients'] = $this->user_model->list_patients_v2("",$this->session->userdata('user_location'));
			
			$this->templating_library->set_view('pages/patients_list','pages/patients_list', $data);
		}
		
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');

	}

	public function get_patient_list() {
        $response_data = array(
            "data" => array(),
            "draw" => 1,
            "recordsFiltered" => 0,
            "recordsTotal" => 0
        );

        if($this->input->is_ajax_request())
        {
            $datatable = $this->input->get();
            $start = $datatable['start'];
            $limit = $datatable['length'];
            $filters = array(
                "search_item_fields_patient_lists" => $datatable['search']['value']
            );

            $column = array(
                "medical_record_id",
                "p_lname",
                "p_fname",
                "customer_address",
                "p_nextofkin"
            );
            $filters[$column[$datatable["order"][0]["column"]]] = $datatable["order"][0]["dir"];
            $result = $this->user_model->list_patients_v3($filters,$this->session->userdata('user_location'),$start,$limit);

            if($result['totalCount']>0)
            {
                foreach ($result['result'] as $key => $value)
                {
					$value['customer_address'] = $value['p_placenum'].", ".$value['p_street'].", ".$value['p_city'].", ".$value['p_state'].", ".$value['p_postalcode']." ";
                    $value['actions'] = '<button type="button" class="btn btn-danger delete_patient" data-patient-id="'.$value['patientID'].'" data-medical-id="'.$value['medical_record_id'].'">Delete</button>';

                    $response_data['data'][] = $value;
                }
            }
            else
            {
                $response_data['data'] = array();
            }

            $response_data['draw'] = $datatable['draw'];
            $response_data['recordsFiltered'] = $result['totalCount'];
            $response_data['recordsTotal'] = $result['totalCount'];
        }
        echo json_encode($response_data);
    }

	function check_existing_patient_new($searched_string, $hospice_id,$current_mr_no)
	{
		$baseurl = base_url();
		$patient_exists = $this->user_model->search_existing_patient_new($searched_string, $hospice_id,$current_mr_no);

		if($patient_exists)
		{
			echo "<div style='width:130px'><img src='".$baseurl."/assets/img/stop_sign.png' width:60px height:60px style='margin-left:15px !important;margin-bottom:3px !important' /><strong><p>MR# ALREADY EXIST!</p></strong><a href='".base_url()."order/patient_profile/".$searched_string."/".$hospice_id."' style='text-decoration:underline'><p>Go to Customer Profile</p></a></div>";
		}
		else
		{
			echo "";
		}
	}

	function check_existing_patient($searched_string, $hospice_id)
	{
		$baseurl = base_url();
		$patient_exists = $this->user_model->search_existing_patient($searched_string, $hospice_id);

		if($patient_exists)
		{
			echo "<div style='width:130px'><img src='".$baseurl."/assets/img/stop_sign.png' width:60px height:60px style='margin-left:15px !important;margin-bottom:3px !important' /><strong><p>MR# ALREADY EXIST!</p></strong><a href='".base_url()."order/patient_profile/".$searched_string."/".$hospice_id."' style='text-decoration:underline'><p>Go to Customer Profile</p></a></div>";
		}
		else
		{
			echo "";
		}
	}
	/*
	| @func : getusertype()
	|
	*/
	public function getusertype()
	{
		echo get_usertype();
	}



	/*
	| @func : subscribe_notification
	| @desc : this is for web push notification in chrome
	| 		  it saves the channel being subscribed by the user
	|
	| @method : GET
	|			   sid = endpoint or channel of the user
	|			   act = its either do subscribe or unsubscribe
	| @data : 02.01.2015
	*/

	function subscribe_notification()
	{
		$this->load->model("mcommon");
		if(isset($_GET['sid']) AND $_GET['sid']!="" )
		{
			$endpoint 	= $_GET['sid'];
			$action 	= $_GET['act'];
			if($action=="sub")
			{
				$data = array(
							"endpoint" 	=> $endpoint,
							"owner"		=> get_userid()
						);
				$result = $this->mcommon->do_subscribe($data);
				if($result)
				{
					$this->common->code 		= 0;
					$this->common->message 		= "Successfully subscribed.";
				}
			}
			else
			{
				$result = $this->mcommon->do_unsubscribe($endpoint);
				if($result)
				{
					$this->common->code 		= 0;
					$this->common->message 		= "Successfully unsubscribed.";
				}
			}
		}
		$this->common->response(false);
	}
	/*
	| @func : getnofications()
	| @desc : get new notifications  and only admin can receive push notification
	| @response : json
	| 				error : <0=ok|1=fail>
	|				message:
	|				data:
	|					url,tag,icon,title,body
	|
	*/
	function getnotifications()
	{
		$data = array(
					"url"   => "",
					"title" => "",
					"body"  => "",
					"tag"   => "",
					"icon"  => ""
				);
	//	if(get_usertype()=="dme_admin" || get_usertype()=="dme_user")
	//	{
			$this->common->code = 0;
			$this->common->message = "allowed";
			$data = array(
					"url"   =>  base_url("order/order_list"),
					"title" => "SmarterChoice",
					"body"  => "New order received",
					"tag"   => "order",
					"icon"  => base_url('assets/img/smarterchoice.png')
				);
	//	}
		$this->common->data = $data;
		$this->common->response(false);
	}
	function trigger_gcm()
	{
		$this->load->model("mcommon");
		$config =   $this->config->item('pushnotification');

	    // Replace with the real client registration IDs
	    $prep_channels = $this->mcommon->get_subscribers();
	    $registrationIDs = array();
	    foreach($prep_channels as $value)
	    {
	    	$registrationIDs[] = $value['endpoint'];
	    }
	   if(empty($registrationIDs))
	   {
	   		echo "nnothing to trigger";
	   		exit;
	   }
	    $fields = array(
	        'registration_ids' => $registrationIDs,
	        'data' => array( "message" => $config['message'] ),
	    );
	    $headers = array(
	        'Authorization: key=' . $config['API_KEY'],
	        'Content-Type: application/json'
	    );
	    // Open connection
	    $ch = curl_init();
	    // Set the URL, number of POST vars, POST data
	    curl_setopt( $ch, CURLOPT_URL, $config['GCM_URL']);
	    curl_setopt( $ch, CURLOPT_POST, true);
	    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
	    //curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields));

	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    // curl_setopt($ch, CURLOPT_POST, true);
	    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields));
	    // Execute post
	    $result = curl_exec($ch);
	    // Close connection
	    curl_close($ch);
	    echo $result;
	}

	public function delete_inactive_customers() {
		$pickup_results = $this->patient_model->get_pickup_with_params();
		print_me($pickup_results);

		foreach ($pickup_results as $key => $value) {
			$order_status_results = [];
			$order_status_results = $this->patient_model->get_order_status_above_2020($value['patientID']);

			if (empty($order_status_results)) {
				print_me($value);
				$is_customer_active = $this->patient_model->check_if_customer_ative($value['patientID']);
				print_me($is_customer_active);
				if ($is_customer_active['is_active'] == 0) {
					$return_delete_orders = $this->patient_model->delete_customer_orders($value['patientID']);
					$return_delete_notes = $this->patient_model->delete_customer_notes($value['patientID']);
					$return_delete_exchanges = $this->patient_model->delete_customer_exchanges($value['patientID']);
					$return_delete_ptmoves = $this->patient_model->delete_customer_ptmoves($value['patientID']);
					$return_delete_respites = $this->patient_model->delete_customer_respites($value['patientID']);
					$return_delete_pickups = $this->patient_model->delete_customer_pickups($value['patientID']);
					$return_delete_addresses = $this->patient_model->delete_customer_addresses($value['patientID']);
					$return_delete_order_statuses = $this->patient_model->delete_customer_order_statuses($value['patientID']);
					$return_delete_customer = $this->patient_model->delete_customer($value['patientID']);
				}
			}
		}

	}



}