<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ;?>
<?php
	Class user extends Ci_Controller
	{
		var $include_top = 'landingpage_template/include_top';
		var $header = 'landingpage_template/header';
		var $client_header = 'client_header';
		var $register = 'register_form';
		var $login = 'login';
		var $footer = 'landingpage_template/footer';
		var $include_bottom = 'landingpage_template/include_bottom';
		var $login_header = 'login_header';
		var $main_header = 'main_template/header';
		
		var $response_code = 1;//false or error default
		var $response_message = "";
		var $response_data = array();


		function __construct()
		{
			parent::__construct();
			$this->load->model('user_model');
			$this->load->model('hospice_model');
			//if(!logged_in()) redirect('landingpage'); //if not logged in, redirect 
		}

		public function register()
		{
			$result = $this->hospice_model->list_group();
			
			$data['hospices'] = ($result) ? $result : FALSE;
			
			$this->templating_library->set('title','Register | Advantage Home Medical Services Inc.');
			$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
		 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
			{
				$this->templating_library->set_view($this->main_header, $this->main_header);
			}
			else
			{
				$this->templating_library->set_view($this->client_header, $this->client_header);
			}
		 	$this->templating_library->set_view($this->register , 'register_form' , $data);
		 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
		 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
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
				
				case 'dme_user':
					$this->add_user($data_post);
					break;

				case 'hospice_user':
					$this->add_hospice_user($data_post);
					break;

				default: 
					$data['failed'] = true;
					$this->templating_library->set('title','Register | Advantage Home Medical Services Inc.');
					$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
					{
						$this->templating_library->set_view($this->main_header, $this->main_header);
					}
					else
					{
						$this->templating_library->set_view($this->client_header, $this->client_header);
					}
				 	$this->templating_library->set_view($this->register , 'register_form' , $data);
				 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
				 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
					break;
			}
		}
		
		public function update_user($hash = '')
		{
			$checked = check_code($hash);
		    $userID = get_id_from_code($hash);
		
			$data_post = $this->input->post();
			$this->form_validation->set_rules('email', 'Email Address', 'trim|required');
			
			$common_data = array(
				'email' => $data_post['email'],
				'username' => $data_post['username'],
				'password' => md5($data_post['password']),
				'firstname' => $data_post['firstname'],
				'lastname' => $data_post['lastname'],
				'phone_num' => $data_post['phone'],
				'accnt_balance' => $data_post['balance'],
				'account_type' => $data_post['account_type'],
				'status' => $data_post['status']
			);
			
			
			if($this->form_validation->run() == TRUE)
			{
				$result = $this->hospice_model->list_group();
			
				$data['hospices'] = ($result) ? $result : FALSE;
				$add_admin = $this->user_model->update_user($userID, $common_data);
				if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
				{
					redirect('admin/users','refresh');
				}
				else
				{
					redirect('client/users','refresh');
				}
				
				
			}
			else 
			{
				$result = $this->hospice_model->list_group(); 
			
				$data['hospices'] = ($result) ? $result : FALSE;
				if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
				{
					redirect('admin/users','refresh');
				}
				else
				{
					redirect('client/users','refresh');
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
				$this->response_message		= "Order Status Successfully Updated.";
				
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
			
			$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[dme_user.email]');
			
			$admin_default = 0;
			
			$common_data = array(
				'email' => $data_post['email'],
				'username' => $data_post['username'],
				'password' => md5($data_post['password']),
				'firstname' => $data_post['firstname'],
				'lastname' => $data_post['lastname'],
				'address' => $data_post['address'],
				'mobile_num' => $data_post['mobile'],
				'phone_num' => $data_post['phone'],
				'accnt_balance' => $data_post['balance'],
				'account_type' => $data_post['account_type'],
				'group_id' => $admin_default,
				'group_name' => '',
				'status' => $data_post['status']
			);
	
			
			
			if($this->form_validation->run() == TRUE)
			{
				$result = $this->hospice_model->list_group();
			
				$data['hospices'] = ($result) ? $result : FALSE;
				$add_admin = $this->user_model->user_add($common_data);
				$data['success'] = true;
				$data['firstname'] = $data_post['firstname'];
				$data['lastname'] = $data_post['lastname'];
				$this->templating_library->set('title','Register | Advantage Home Medical Services Inc.');
					$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
					{
						$this->templating_library->set_view($this->main_header, $this->main_header);
					}
					else
					{
						$this->templating_library->set_view($this->client_header, $this->client_header);
					}
				 	$this->templating_library->set_view($this->register , 'register_form' , $data);
				 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
				 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
				
			}
			else
			{
				$result = $this->hospice_model->list_group();
			
				$data['hospices'] = ($result) ? $result : FALSE;
				$data['failed'] = true;
				$this->templating_library->set('title','Register | Advantage Home Medical Services Inc.');
					$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
					{
						$this->templating_library->set_view($this->main_header, $this->main_header);
					}
					else
					{
						$this->templating_library->set_view($this->client_header, $this->client_header);
					}
				 	$this->templating_library->set_view($this->register , 'register_form' , $data);
				 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
				 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
			}
			
		}

		public function add_hospice_admin($user_data)
		{
			$data_post = $this->input->post();
			
			$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[dme_user.email]');
			
			$admin_default = 0;
			
			$common_data = array(
				'email' => $data_post['email'],
				'username' => $data_post['username'],
				'password' => md5($data_post['password']),
				'firstname' => $data_post['firstname'],
				'lastname' => $data_post['lastname'],
				'address' => $data_post['address'],
				'mobile_num' => $data_post['mobile'],
				'phone_num' => $data_post['phone'],
				'accnt_balance' => $data_post['balance'],
				'account_type' => $data_post['account_type'],
				'group_id' => $data_post['group_id'],
				'group_name' => $data_post['group_name'],
				'status' => $data_post['status']
			);
	
			
			
			if($this->form_validation->run() == TRUE)
			{
				$result = $this->hospice_model->list_group();
			
				$data['hospices'] = ($result) ? $result : FALSE;
				$add_admin = $this->user_model->user_add($common_data);
				$data['success'] = true;
				$data['firstname'] = $data_post['firstname'];
				$data['lastname'] = $data_post['lastname'];
				$this->templating_library->set('title','Register | Advantage Home Medical Services Inc.');
					$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
					{
						$this->templating_library->set_view($this->main_header, $this->main_header);
					}
					else
					{
						$this->templating_library->set_view($this->client_header, $this->client_header);
					}
				 	$this->templating_library->set_view($this->register , 'register_form' , $data);
				 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
				 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
				
			}
			else
			{
				$result = $this->hospice_model->list_group();
			
				$data['hospices'] = ($result) ? $result : FALSE;
				$data['failed'] = true;
				$this->templating_library->set('title','Register | Advantage Home Medical Services Inc.');
					$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
					{
						$this->templating_library->set_view($this->main_header, $this->main_header);
					}
					else
					{
						$this->templating_library->set_view($this->client_header, $this->client_header);
					}
				 	$this->templating_library->set_view($this->register , 'register_form' , $data);
				 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
				 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
			}
			
		}

		public function add_user($user_data)
		{
			$data_post = $this->input->post();
			
			$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[dme_user.email]');

			$common_data = array(
				'email' => $data_post['email'],
				'username' => $data_post['username'],
				'password' => md5($data_post['password']),
				'firstname' => $data_post['firstname'],
				'lastname' => $data_post['lastname'],
				'address' => $data_post['address'],
				'mobile_num' => $data_post['mobile'],
				'phone_num' => $data_post['phone'],
				'accnt_balance' => $data_post['balance'],
				'account_type' => $data_post['account_type'],
				'group_id' => $data_post['group_id'],
				'group_name' => $data_post['group_name'],
				'status' => $data_post['status']
			);

			
		
			if($this->form_validation->run() == TRUE)
			{
				$result = $this->hospice_model->list_group();
			
				$data['hospices'] = ($result) ? $result : FALSE;
				$add_user = $this->user_model->user_add($common_data);
				$data['success'] = true;
				$data['firstname'] = $data_post['firstname'];
				$data['lastname'] = $data_post['lastname'];
				$this->templating_library->set('title','Register | Advantage Home Medical Services Inc.');
					$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
					{
						$this->templating_library->set_view($this->main_header, $this->main_header);
					}
					else
					{
						$this->templating_library->set_view($this->client_header, $this->client_header);
					}
				 	$this->templating_library->set_view($this->register , 'register_form' , $data);
				 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
				 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
			}
			else
			{
				$result = $this->hospice_model->list_group();
			
				$data['hospices'] = ($result) ? $result : FALSE;
				$data['failed'] = true;
				$this->templating_library->set('title','Register | Advantage Home Medical Services Inc.');
					$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
					{
						$this->templating_library->set_view($this->main_header, $this->main_header);
					}
					else
					{
						$this->templating_library->set_view($this->client_header, $this->client_header);
					}
				 	$this->templating_library->set_view($this->register , 'register_form' , $data);
				 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
				 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
			}
			
			
		}

		public function add_hospice_user($user_data)
		{
			$data_post = $this->input->post();
			
			$this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[dme_user.email]');

			$common_data = array(
				'email' => $data_post['email'],
				'username' => $data_post['username'],
				'password' => md5($data_post['password']),
				'firstname' => $data_post['firstname'],
				'lastname' => $data_post['lastname'],
				'address' => $data_post['address'],
				'mobile_num' => $data_post['mobile'],
				'phone_num' => $data_post['phone'],
				'accnt_balance' => $data_post['balance'],
				'account_type' => $data_post['account_type'],
				'group_id' => $data_post['group_id'],
				'group_name' => $data_post['group_name'],
				'status' => $data_post['status']
			);

		
			if($this->form_validation->run() == TRUE)
			{
				$result = $this->hospice_model->list_group();
			
				$data['hospices'] = ($result) ? $result : FALSE;
				$add_user = $this->user_model->user_add($common_data);
				$data['success'] = true;
				$data['firstname'] = $data_post['firstname'];
				$data['lastname'] = $data_post['lastname'];
			$this->templating_library->set('title','Register | Advantage Home Medical Services Inc.');
					$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
					{
						$this->templating_library->set_view($this->main_header, $this->main_header);
					}
					else
					{
						$this->templating_library->set_view($this->client_header, $this->client_header);
					}
				 	$this->templating_library->set_view($this->register , 'register_form' , $data);
				 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
				 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
			}
			else
			{
				$result = $this->hospice_model->list_group();
			
				$data['hospices'] = ($result) ? $result : FALSE;
				$data['failed'] = true;
				$this->templating_library->set('title','Register | Advantage Home Medical Services Inc.');
					$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
					{
						$this->templating_library->set_view($this->main_header, $this->main_header);
					}
					else
					{
						$this->templating_library->set_view($this->client_header, $this->client_header);
					}
				 	$this->templating_library->set_view($this->register , 'register_form' , $data);
				 	$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
				 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
			}
			
			
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


		public function process_login()
		{
			$data_post = $this->input->post();

			$type = $this->session->userdata('account_type');
			//$email = $this->session->userdata('email');
			$username = $data_post['username'];
			$password = $data_post['password'];

			$login_result = $this->user_model->login($username, $password, $type);


			$this->check_database($password);
			
        	//if($this->session->userdata('userID') == '') redirect('landingpage');
			if($login_result)
			{
	        	foreach($login_result as $row)
		        	{
		            	if($row->account_type == 'dme_admin' || $row->account_type == 'dme_user')
		            	{
		            		
		            		redirect('client_order/list_orders/');	
		            	}
		            	else
		            	{
		            		redirect('client_order/list_orders');	
		            	}
	            	}
        	}
        	else
        	{
				$data['failed'] = true;
        		$this->templating_library->set('title','Error Login | Advantage Home Medical Services Inc.');
				$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
				$this->templating_library->set_view($this->login_header, 'login_header');
				$this->templating_library->set_view($this->login , 'login', $data);
				$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
				$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
        	}


		}

		public function check_database($password)
		{
			$type = $this->input->post('account_type');
	        //$email = $this->input->post('email');
	        $username = $this->input->post('username');

	        $result = $this->user_model->login($username, $password, $type);
	        if($result) {
	            $sess_array = array();
	            foreach($result as $row) {
	                $sess_array = array(
	                        'userID' 	      => $row->userID,
	                        'email'      	  => $row->email,
	                        'firstname'       => $row->firstname,
	                        'lastname'        => $row->lastname,
	                        'username'        => $row->username,
							 'password'        => $row->password,
	                        'phone_num'        => $row->phone_num,
							 'mobile_num'        => $row->mobile_num,
							  'group_name'        => $row->group_name,
							   'group_id'        => $row->group_id,
							    'status'        => $row->status,
								 'address'        => $row->address,
	                        'account_type'	  => $row->account_type
	                );
  
	                $this->session->set_userdata($sess_array);               
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
                    'account_type'	  => ''
            );

      	    $this->session->set_userdata($sess_array);    
   			$this->session->sess_destroy();
   			redirect('user/user_login','refresh');
   		}
		
		
		// INSERTED for the DEMO of JOTFORM
		public function page()
		{
			$this->templating_library->set('title','Advantage Home Medical Services Inc.');
			$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
			$this->templating_library->set_view($this->login_header, 'login_header');
			$this->templating_library->set_view('page', 'page');
			$this->templating_library->set_view($this->footer, 'landingpage_template/footer');
			$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');
		}



	}