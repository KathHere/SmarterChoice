<?php
Class users extends Ci_Controller
{
	var $response_code = 1;//false or error default
	var $response_message = "";
	var $response_data = array();

	public function __construct()
	{
		parent::__construct();
		is_logged_in();

		date_default_timezone_set("America/Los_Angeles");
		$this->load->model('user_model');
		$this->load->model('hospice_model');
	}

	public function index()
	{
		$hospiceID = $this->session->userdata('group_id');

		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
		{
			$result = $this->user_model->get_users_v2($this->session->userdata('user_location'));
		}
		else
		{
			$result = $this->user_model->get_hosp_admin_users($hospiceID);
		}

		$data['hospices'] = $this->hospice_model->list_group();
		$data['users'] = ($result) ? $result : FALSE;

		$this->templating_library->set('title','Create New Order');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'distribution_supervisor') {
			$this->templating_library->set_view('pages/user_list','pages/user_list', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function register()
	{
		$result = $this->hospice_model->list_group_v3($this->session->userdata('user_location'));
		$result_2 = $this->hospice_model->list_group_commercial_v2($this->session->userdata('user_location'));

		$data['hospices'] = ($result) ? $result : FALSE;
		$data['companies'] = ($result_2) ? $result_2 : FALSE;

		$this->templating_library->set('title','Create New User');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');

		// DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'distribution_supervisor') {
			$this->templating_library->set_view('pages/create_user','pages/create_user', $data);
		}

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function update_first_loggedin($user_id)
	{
		$data_post = $this->input->post();

		if($data_post['first_loggedin'] != 1 || $data_post['changed_password'] != 1)
		{
			$array = array(
				'is_first_loggedin' => $data_post['first_loggedin'],
				'is_changed_password' => $data_post['changed_password']
			);
		}

		$updated = $this->user_model->update_first_loggedin($user_id, $array);



		if($updated)
		{
			$new_userdata = array(
				'is_first_loggedin' => 0,
				'is_changed_password' => 0,
			);
			$this->session->set_userdata($new_userdata);

			$this->response_code 		= 0;
			$this->response_message		= "You have agreed to the terms and conditions as it pertains to the HIPPA Policy. You can now proceed.";
		}
		else
		{
			$this->response_message		= "Failed to update.";
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function user_add()
	{
		$data_post = $this->input->post();
		$account_type = $data_post['account_type'];

		switch ($account_type)
		{
			case 'dme_admin':
			 	$this->add_admin($data_post);
			 	break;

			case 'hospice_admin':
			 	$this->add_hospice_admin($data_post);
			 	break;

			case 'company_admin':
				$this->add_company_admin($data_post);
				break;

			case 'dme_user': case 'biller': case 'rt': case 'customer_service': case 'distribution_supervisor': case 'dispatch': case 'sales_rep':
				$this->add_user($data_post);
				break;

			case 'hospice_user':
				$this->add_hospice_user($data_post);
				break;

			case 'company_user':
				$this->add_company_user($data_post);
				break;

			default:
				$this->response_message = "Failed to Add User";
				break;
		}
	}

	public function update_user($hash = '')
	{
		$checked = check_code($hash);
	    $userID = get_id_from_code($hash);

		$data_post = $this->input->post();
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required');

		$final_user_location = $data_post['user_location'];
		if ($final_user_location == undefined || $final_user_location == null || $final_user_location == '') {
			$final_user_location = $data_post['user_location_logged_in'];
		}

		if ($data_post['mobile_num'] == null) {
			$data_post['mobile_num'] = '';
		}

		if ($data_post['account_type'] == 'hospice_admin' || $data_post['account_type'] == 'hospice_user') {
			if($data_post['password'] == '')
			{
				$common_data = array(
					'email' => $data_post['email'],
					'username' => $data_post['username'],
					'firstname' => $data_post['firstname'],
					'lastname' => $data_post['lastname'],
					'phone_num' => $data_post['phone'],
					'mobile_num' => $data_post['mobile_num'],
					//'address' => $data_post['address'],
					'accnt_balance' => $data_post['balance'],
					'account_type' => $data_post['account_type'],
					'user_location' => $final_user_location,
					'group_id' 	=> $data_post['group_id'],
					'group_name' 	=> $data_post['group_name_select'],
					//'status' => $data_post['status']
				);
			}
			else
			{
				$common_data = array(
					'email' => $data_post['email'],
					'username' => $data_post['username'],
					'password' => md5($data_post['password']),
					'firstname' => $data_post['firstname'],
					'lastname' => $data_post['lastname'],
					'phone_num' => $data_post['phone'],
					'mobile_num' => $data_post['mobile_num'],
					//'address' => $data_post['address'],
					'accnt_balance' => $data_post['balance'],
					'account_type' => $data_post['account_type'],
					'is_changed_password' => 1,
					'user_location' => $final_user_location,
					'group_id' 	=> $data_post['group_id'],
					'group_name' 	=> $data_post['group_name_select'],
					//'status' => $data_post['status']
				);
			}
		} else {
			if($data_post['password'] == '')
			{
				$common_data = array(
					'email' => $data_post['email'],
					'username' => $data_post['username'],
					'firstname' => $data_post['firstname'],
					'lastname' => $data_post['lastname'],
					'phone_num' => $data_post['phone'],
					'mobile_num' => $data_post['mobile_num'],
					//'address' => $data_post['address'],
					'accnt_balance' => $data_post['balance'],
					'account_type' => $data_post['account_type'],
					'user_location' => $final_user_location,
					'group_id' 	=> 0,
					'group_name' 	=> '',
					//'status' => $data_post['status']
				);
			}
			else
			{
				$common_data = array(
					'email' => $data_post['email'],
					'username' => $data_post['username'],
					'password' => md5($data_post['password']),
					'firstname' => $data_post['firstname'],
					'lastname' => $data_post['lastname'],
					'phone_num' => $data_post['phone'],
					'mobile_num' => $data_post['mobile_num'],
					//'address' => $data_post['address'],
					'accnt_balance' => $data_post['balance'],
					'account_type' => $data_post['account_type'],
					'is_changed_password' => 1,
					'user_location' => $final_user_location,
					'group_id' 	=> 0,
					'group_name' 	=> '',
					//'status' => $data_post['status']
				);
			}
		}
		


		if($this->form_validation->run() == TRUE)
		{
			$result = $this->hospice_model->list_group();

			$data['hospices'] = ($result) ? $result : FALSE;
			$add_admin = $this->user_model->update_user($userID, $common_data);
			if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
			{
				redirect('users','refresh');
			}
			else
			{
				redirect('users','refresh');
			}


		}
		else
		{
			$result = $this->hospice_model->list_group();

			$data['hospices'] = ($result) ? $result : FALSE;
			if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
			{
				redirect('users','refresh');
			}
			else
			{
				redirect('users','refresh');
			}

		}

	}

	public function delete_user($hash = '')
	{
		$checked = check_code($hash);
	    $userID = get_id_from_code($hash);


		if($userID != '')
		{
			$result = $this->hospice_model->list_group();
			$this->user_model->delete_user($userID);
			//** For the response (include_bottom.php)
			$this->response_code 		= 0;
			$this->response_message		= "User Successfully Deleted.";

			echo json_encode(array(
					"error"		=> $this->response_code,
					"message"	=> $this->response_message
			));
			//redirect('admin/users','refresh');
		}
		else
		{
			redirect(base_url());
		}

	}

	public function add_admin($user_data)
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('firstname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('account_type', 'Account Type', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[dme_user.email]');

		$admin_default = 0;
		$final_user_location = $data_post['user_location'];
		if ($final_user_location == undefined || $final_user_location == null || $final_user_location == '') {
			$final_user_location = $data_post['user_location_logged_in'];
		}

		$common_data = array(
			'email' 			=> $data_post['email'],
			'username' 			=> $data_post['username'],
			'password' 			=> md5($data_post['password']),
			'firstname' 		=> $data_post['firstname'],
			'lastname' 			=> $data_post['lastname'],
			'mobile_num' 		=> $data_post['mobile'],
			'phone_num' 		=> $data_post['phone'],
			'accnt_balance' 	=> $data_post['balance'],
			'account_type' 		=> $data_post['account_type'],
			'group_id' 			=> $admin_default,
			'group_name' 		=> '',
			'user_location' 	=> $final_user_location
		);

		if($this->form_validation->run())
		{
			$add_admin = $this->user_model->user_add($common_data);
			if($add_admin)
			{
				$this->response_code = 0;
				$this->response_message = "User Added Successfully.";
			}
			else
			{
				$this->response_message = "Failed to Add User.";
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}

		echo json_encode(array(
				"error" 	=> $this->response_code,
				"message"	=> $this->response_message
			));
		exit;
	}

	public function add_hospice_admin($user_data)
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('firstname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('account_type', 'Account Type', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[dme_user.email]');

		$admin_default = 0;
		$final_user_location = $data_post['user_location'];
		if ($final_user_location == undefined || $final_user_location == null || $final_user_location == '') {
			$final_user_location = $data_post['user_location_logged_in'];
		}

		$common_data = array(
			'email' 			=> $data_post['email'],
			'username' 			=> $data_post['username'],
			'password' 			=> md5($data_post['password']),
			'firstname' 		=> $data_post['firstname'],
			'lastname' 			=> $data_post['lastname'],
			'mobile_num' 		=> $data_post['mobile'],
			'phone_num' 		=> $data_post['phone'],
			'accnt_balance' 	=> $data_post['balance'],
			'account_type' 		=> $data_post['account_type'],
			'group_id' 			=> $data_post['group_id'],
			'group_name' 		=> $data_post['group_name'],
			'is_first_loggedin' => 1,
			'user_location' 	=> $final_user_location
		);

		if($this->form_validation->run())
		{
			$add_admin = $this->user_model->user_add($common_data);
			if($add_admin)
			{
				$this->response_code = 0;
				$this->response_message = "User Added Successfully.";
			}
			else
			{
				$this->response_message = "Failed to Add User.";
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}

		echo json_encode(array(
			"error" 	=> $this->response_code,
			"message"	=> $this->response_message
		));
		exit;
	}

	public function add_company_admin($user_data)
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('firstname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('account_type', 'Account Type', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[dme_user.email]');

		$admin_default = 0;
		$final_user_location = $data_post['user_location'];
		if ($final_user_location == undefined || $final_user_location == null || $final_user_location == '') {
			$final_user_location = $data_post['user_location_logged_in'];
		}

		$common_data = array(
			'email' 			=> $data_post['email'],
			'username' 			=> $data_post['username'],
			'password' 			=> md5($data_post['password']),
			'firstname' 		=> $data_post['firstname'],
			'lastname' 			=> $data_post['lastname'],
			'mobile_num' 		=> $data_post['mobile'],
			'phone_num' 		=> $data_post['phone'],
			'accnt_balance' 	=> $data_post['balance'],
			'account_type' 		=> $data_post['account_type'],
			'group_id' 			=> $data_post['group_id_company'],
			'group_name' 		=> $data_post['group_name_company'],
			'is_first_loggedin' => 1,
			'user_location' 	=> $final_user_location
		);

		if($this->form_validation->run())
		{
			$add_admin = $this->user_model->user_add($common_data);
			if($add_admin)
			{
				$this->response_code = 0;
				$this->response_message = "User Added Successfully.";
			}
			else
			{
				$this->response_message = "Failed to Add User.";
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}

		echo json_encode(array(
			"error" 	=> $this->response_code,
			"message"	=> $this->response_message
		));
		exit;
	}

	public function add_user($user_data)
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('firstname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('account_type', 'Account Type', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|is_unique[dme_user.email]');

		$final_user_location = $data_post['user_location'];
		if ($final_user_location == undefined || $final_user_location == null || $final_user_location == '') {
			$final_user_location = $data_post['user_location_logged_in'];
		}

		$common_data = array(
			'email' 			=> $data_post['email'],
			'username' 			=> $data_post['username'],
			'password' 			=> md5($data_post['password']),
			'firstname' 		=> $data_post['firstname'],
			'lastname' 			=> $data_post['lastname'],
			'mobile_num' 		=> $data_post['mobile'],
			'phone_num' 		=> $data_post['phone'],
			'accnt_balance' 	=> $data_post['balance'],
			'account_type' 		=> $data_post['account_type'],
			'group_id' 			=> 0,
			'group_name' 		=> $data_post['group_name'],
			'is_first_loggedin' => 1,
			'user_location' 	=> $final_user_location
		);

		if($this->form_validation->run())
		{
			$add_user = $this->user_model->user_add($common_data);
			if($add_user)
			{
				$this->response_code = 0;
				$this->response_message = "User Added Successfully.";
			}
			else
			{
				$this->response_message = "Failed to Add User.";
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}

		echo json_encode(array(
			"error" 	=> $this->response_code,
			"message"	=> $this->response_message
		));
		exit;
	}

	public function add_hospice_user($user_data)
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('firstname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('account_type', 'Account Type', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[dme_user.email]');

		$final_user_location = $data_post['user_location'];
		if ($final_user_location == undefined || $final_user_location == null || $final_user_location == '') {
			$final_user_location = $data_post['user_location_logged_in'];
		}

		$common_data = array(
			'email' 			=> $data_post['email'],
			'username' 			=> $data_post['username'],
			'password' 			=> md5($data_post['password']),
			'firstname' 		=> $data_post['firstname'],
			'lastname' 			=> $data_post['lastname'],
			'mobile_num' 		=> $data_post['mobile'],
			'phone_num' 		=> $data_post['phone'],
			'accnt_balance' 	=> $data_post['balance'],
			'account_type' 		=> $data_post['account_type'],
			'group_id' 			=> $data_post['group_id'],
			'group_name' 		=> $data_post['group_name'],
			'is_first_loggedin' => 1,
			'user_location' 	=> $final_user_location
		);

		if($this->form_validation->run())
		{
			$add_user = $this->user_model->user_add($common_data);

			if($add_user)
			{
				$this->response_code = 0;
				$this->response_message = "User Added Successfully.";
			}
			else
			{
				$this->response_message = "Failed to Add User.";
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}
		echo json_encode(array(
			"error" 	=> $this->response_code,
			"message"	=> $this->response_message
		));
		exit;
	}

	public function add_company_user($user_data)
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('firstname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required|xss_clean');
		$this->form_validation->set_rules('account_type', 'Account Type', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[dme_user.email]');

		$final_user_location = $data_post['user_location'];
		if ($final_user_location == undefined || $final_user_location == null || $final_user_location == '') {
			$final_user_location = $data_post['user_location_logged_in'];
		}

		$common_data = array(
			'email' 			=> $data_post['email'],
			'username' 			=> $data_post['username'],
			'password' 			=> md5($data_post['password']),
			'firstname' 		=> $data_post['firstname'],
			'lastname' 			=> $data_post['lastname'],
			'mobile_num' 		=> $data_post['mobile'],
			'phone_num' 		=> $data_post['phone'],
			'accnt_balance' 	=> $data_post['balance'],
			'account_type' 		=> $data_post['account_type'],
			'group_id' 			=> $data_post['group_id_company'],
			'group_name' 		=> $data_post['group_name_company'],
			'is_first_loggedin' => 1,
			'user_location' 	=> $final_user_location
		);

		if($this->form_validation->run())
		{
			$add_user = $this->user_model->user_add($common_data);

			if($add_user)
			{
				$this->response_code = 0;
				$this->response_message = "User Added Successfully.";
			}
			else
			{
				$this->response_message = "Failed to Add User.";
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}
		echo json_encode(array(
			"error" 	=> $this->response_code,
			"message"	=> $this->response_message
		));
		exit;
	}
}