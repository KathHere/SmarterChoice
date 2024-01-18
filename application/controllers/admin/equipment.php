<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
	Class equipment extends Ci_Controller
	{

		var $include_top = 'landingpage_template/include_top';
		var $header = 'main_template/header';
		var $client_header = 'client_header';
		var $register = 'register_form';
		var $footer = 'main_template/footer';
		var $include_bottom = 'landingpage_template/include_bottom';

		var $response_code = 1; //false or error default
		var $response_message = "";
		var $response_data = array();


		function __construct()
		{
			parent::__construct();
			// if($this->session->userdata('account_type') != 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') != 'hospice_admin') 
			// {
			// 	redirect('landingpage');
			// }
			$this->load->model('equipment_model');
			
		}

		public function index()
		{
			$data['equipments']	= $this->equipment_model->list_equipment();
			$data['categories'] = $this->equipment_model->get_equipment_cat();
			
			// printA($data['categories']);
			// exit;
			$this->templating_library->set('title','Advantage Home Medical Services Inc. | Equipments List');
			$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
		 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
			{
			$this->templating_library->set_view($this->header, $this->header);
			}
			else
			{
			$this->templating_library->set_view($this->client_header, $this->client_header);
			}
		 	$this->templating_library->set_view('admin/equipment_list' , 'admin/equipment_list', $data);
		 	$this->templating_library->set_view($this->footer, 'main_template/footer');
		 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');	
			
		}

		public function list_equipments($hash='')
		{
			$hospiceID = get_id_from_code($hash);

			$data['equipments']	= $this->equipment_model->tobe_assign_equipment();
			$data['hospices']	= $this->equipment_model->get_hospice($hospiceID);

			$data['counts']	= $this->equipment_model->count_results();


			$this->templating_library->set('title','Advantage Home Medical Services Inc.');
			$this->templating_library->set_view($this->include_top, 'landingpage_template/include_top');
		 	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
			{
				$this->templating_library->set_view($this->header, $this->header);
			}
			else
			{
				$this->templating_library->set_view($this->client_header, $this->client_header);
			}
		 	$this->templating_library->set_view('admin/assign_hospice_equipments' , 'admin/assign_hospice_equipments', $data);
		 	$this->templating_library->set_view($this->footer, 'main_template/footer');
		 	$this->templating_library->set_view($this->include_bottom, 'landingpage_template/include_bottom');	
		}

		public function assign_equipment($hospiceID='')
		{
			$unique_id = strtotime(date('Y-m-d H:i:s'));

			$data_post = $this->input->post();
			$data_array = array();

			foreach($data_post['equipment_id'] as $key=>$value){
				$data_array[] = array(
					'hospiceID'		=> $data_post['hospiceID'],
					'equipmentID'	=> $value,
					'uniqueID'		=> $unique_id
				);

			}

			$this->equipment_model->insert_equipments($data_array,$hospiceID);

			//** For the response 
			$this->response_code 		= 0;
			$this->response_message		= "Successfully Assigned Equipments.";
			
			echo json_encode(array(
					"error"		=> $this->response_code,
					"message"	=> $this->response_message
			));

		}

		public function edit($equipID='')
		{	
			$data_post = $this->input->post();

			$array = array(
				'key_desc' => $data_post['description']
			);

			$this->equipment_model->update_equip_name($equipID, $array);

			//** For the response 
			$this->response_code 		= 0;
			$this->response_message		= "Successfully Edited.";
			
			echo json_encode(array(
					"error"		=> $this->response_code,
					"message"	=> $this->response_message
			));

			//redirect('admin/equipment','refresh');
		}

		public function delete_equipment($equipID)
		{

			$this->equipment_model->delete_equipment($equipID);

			//** For the response (include_bottom.php)
			$this->response_code 		= 0;
			$this->response_message		= "Equipment Successfully Deleted.";
			
			echo json_encode(array(
					"error"		=> $this->response_code,
					"message"	=> $this->response_message
			));
		}

		public function add_equipment()
		{
			$this->form_validation->set_rules('cat_id','Category Name', 'required');
			$this->form_validation->set_rules('key_desc','Equipment Name', 'required');

			$data_post = $this->input->post();

			$array = array(
				'categoryID' => $data_post['cat_id'],
				'key_name' => $data_post['key_name'],
				'key_desc' => $data_post['key_desc'],
				'input_type' =>'checkbox'
			);

			if($this->form_validation->run() == TRUE)
			{
				$saved = $this->equipment_model->add_equipment($array);
				
			}
			else
			{
				$this->response_message = validation_errors('<p>');
			}
			
			
			//** For the response (include_bottom.php)
			$this->response_code 		= 0;
			$this->response_message		= "Successfully Added.";
			
			echo json_encode(array(
						"error"		=> $this->response_code,
						"message"	=> $this->response_message
			));
		}


}
