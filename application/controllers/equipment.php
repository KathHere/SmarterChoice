<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php
Class equipment extends Ci_Controller
{
    var $response_code = 1;
    var $response_message = "";

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        date_default_timezone_set("America/Los_Angeles");
        $this->load->model('equipment_model');

    }

    public function index()
    {
        $data['equipments'] = $this->equipment_model->list_equipment();
        $data['categories'] = $this->equipment_model->get_equipment_cat();

        $this->templating_library->set('title','Create New Hospice');
        $this->templating_library->set_view('common/head','common/head');
        $this->templating_library->set_view('common/header','common/header');
        $this->templating_library->set_view('common/nav','common/nav');
        $this->templating_library->set_view('pages/equipment_list','pages/equipment_list' ,$data);
        $this->templating_library->set_view('common/footer','common/footer');
        $this->templating_library->set_view('common/foot','common/foot');

    }

    public function add_equipments_fix() {
		$unique_id = strtotime(date('Y-m-d H:i:s A'));
		$hospice_id = 208; // changes this to specific hospice you want to add equipments
		$data['equipments']	= $this->equipment_model->tobe_assign_equipment_v2();

		foreach($data['equipments'] as $key=>$value){
			$data_array[] = array(
				'hospiceID'		=> $hospice_id,
				'equipmentID'	=> $value['equipmentID'],
				'uniqueID'		=> $unique_id
			);
		}
		$this->equipment_model->insert_equipments($data_array,$hospice_id);
    }
    public function list_equipments($hash='')
    {
        $hospiceID = get_id_from_code($hash);

        $data['capped_count']   = $this->equipment_model->assigned_equipment_capped_v2($hospiceID);
        $data['capped_ids'] = array();
        foreach($data['capped_count'] as $value)
        {
            $data['capped_ids'][] = $value['equipmentID'];
        }
        $data['non_capped_count']   = $this->equipment_model->assigned_equipment_non_capped_v2($hospiceID); // Update: 07/15/2021
        $data['disposable_count']   = $this->equipment_model->assigned_equipment_disposable_v2($hospiceID); // Update: 07/15/2021
        $data['assigned'] = get_assigned_equipment($hospiceID);
        $data['equipments'] = $this->equipment_model->tobe_assign_equipment_v2();
        // $data['equipments_v3']  = $this->equipment_model->tobe_assign_equipment_v3($hospiceID);
        $data['equipments_v3']  = $this->equipment_model->tobe_assign_equipment_v4($hospiceID); // Update: 07/15/2021
        $data['hospices']   = $this->equipment_model->get_hospice($hospiceID);
        $data['counts'] = $this->equipment_model->count_results();

        $data['date_today_3months'] = date('Y-m-d', strtotime('-3 months'));

        $this->templating_library->set('title','Assign Items');
        $this->templating_library->set_view('common/head','common/head');
        $this->templating_library->set_view('common/header','common/header');
        $this->templating_library->set_view('common/nav','common/nav');
        $this->templating_library->set_view('pages/equipment_list','pages/equipment_list' ,$data);
        $this->templating_library->set_view('common/footer','common/footer');
        $this->templating_library->set_view('common/foot','common/foot');
    }

    public function change_item_category() {
        if($this->input->is_ajax_request())
		{
            $data_post = $this->input->post();
            $hospiceID = $data_post['hospiceID'];
            $equipment_ids = explode(',', $data_post['equipment_ids']);
            $temp_date_selected = explode('-', $data_post['date_selected']);
            $date_selected = $temp_date_selected[2].'-'.$temp_date_selected[0].'-'.$temp_date_selected[1];

            $data = array();
            $result = array();
            foreach($equipment_ids as $equipment_id) {
                $result['confirmed_delivery']['orders'] = $this->equipment_model->get_items_for_change_item_category_confirmed_workorders_delivery($equipment_id, $date_selected, $hospiceID);
                
                // Change Item Category of dme_order for Confirmed Delivery, CUS Move, Respite
                foreach($result['confirmed_delivery']['orders']  as $confirmed_delivery) {
                    $result['confirmed_delivery']['capped_copy'] = $this->equipment_model->check_capped_copy_v2($equipment_id);
                    $new_item_category = array(
                        'equipmentID' => $result['confirmed_delivery']['capped_copy']['equipmentID']
                    );
                    $this->equipment_model->change_item_category_for_new_process($confirmed_delivery['orderID'], $new_item_category);

                    // Find subequipments
                    $result['confirmed_delivery']['order_subequipments'] = $this->equipment_model->get_order_subequipments($confirmed_delivery['uniqueID'],$equipment_id);
                    $result['confirmed_delivery']['subequipments'] = array();

                    foreach($result['confirmed_delivery']['order_subequipments'] as $order_subequipments) {
                        $sub_result = $this->equipment_model->get_capped_subequipment($result['confirmed_delivery']['capped_copy']['equipmentID'], $order_subequipments['key_desc']);
                        $result['confirmed_delivery']['subequipments'][] = $sub_result;

                        $new_item_category_subequipment = array(
                            'equipmentID' => $sub_result['equipmentID']
                        );
                        $this->equipment_model->change_item_category_for_new_process($order_subequipments['orderID'], $new_item_category_subequipment);
                    }
                }

                $result['pickup']['orders'] = $this->equipment_model->get_items_for_change_item_category_workorders_pickup($equipment_id, $date_selected, $hospiceID);

                // Change Item Category of dme_order for Pickup
                foreach($result['pickup']['orders'] as $pickup) {
                    $result['pickup']['capped_copy'] = $this->equipment_model->check_capped_copy_v2($equipment_id);
                    $new_item_category = array(
                        'equipmentID' => $result['pickup']['capped_copy']['equipmentID']
                    );
                    $this->equipment_model->change_item_category_for_new_process($pickup['orderID'], $new_item_category);

                    // Get pickup new data
                    $result_new_data = $this->equipment_model->get_items_for_change_item_category_workorders_pickup_new_data($pickup['pickedup_uniqueID'], $pickup['patientID'], $equipment_id);
                    $result['pickup']['orders_new_data'][] = $result_new_data;

                    $new_item_category_new_data = array(
                        'equipmentID' => $result['pickup']['capped_copy']['equipmentID']
                    );
                    $this->equipment_model->change_item_category_for_new_process($result_new_data['orderID'], $new_item_category_new_data);

                    // Find subequipments
                    $pickup_order_subequipments = $this->equipment_model->get_order_subequipments($pickup['uniqueID'],$equipment_id);
                    $result['pickup']['order_subequipments'][] = $pickup_order_subequipments;
                    $result['pickup']['subequipments'] = array();

                    foreach($pickup_order_subequipments as $order_subequipments) {
                        $sub_result = $this->equipment_model->get_capped_subequipment($result['pickup']['capped_copy']['equipmentID'], $order_subequipments['key_desc']);
                        $result['pickup']['subequipments'][] = $sub_result;

                        $new_item_category_subequipment = array(
                            'equipmentID' => $sub_result['equipmentID']
                        );
                        $this->equipment_model->change_item_category_for_new_process($order_subequipments['orderID'], $new_item_category_subequipment);

                        // Get pick new data subequipments
                        $sub_result_new_data = $this->equipment_model->get_order_subequipments_pickup_new($result_new_data['uniqueID'], $order_subequipments['equipmentID']);
                        $result['pickup']['new_data']['subequipment'][] = $sub_result_new_data;

                        $new_item_category_subequipment_new_data = array(
                            'equipmentID' => $sub_result['equipmentID']
                        );
                        $this->equipment_model->change_item_category_for_new_process($sub_result_new_data['orderID'], $new_item_category_subequipment_new_data);
                    }
                }

                $result['confirmed_exchange']['orders'] = $this->equipment_model->get_items_for_change_item_category_confirmed_workorders_exchange($equipment_id, $date_selected, $hospiceID);

                // Change Item Category of dme_order for Confirmed Exchange
                foreach($result['confirmed_exchange']['orders'] as $confirmed_exchange) {
                    $result['confirmed_exchange']['capped_copy'] = $this->equipment_model->check_capped_copy_v2($equipment_id);
                    $new_item_category = array(
                        'equipmentID' => $result['confirmed_exchange']['capped_copy']['equipmentID']
                    );
                    $this->equipment_model->change_item_category_for_new_process($confirmed_exchange['orderID'], $new_item_category);

                    // Get the old data or old row for this equipment
                    $result_old_data = $this->equipment_model->get_items_for_change_item_category_pending_workorders_exchange_old($confirmed_exchange['uniqueID'], $equipment_id);
                    $result['confirmed_exchange']['old_data'][] = $result_old_data;

                    $new_item_category_old_data = array(
                        'equipmentID' => $result['confirmed_exchange']['capped_copy']['equipmentID']
                    );
                    $this->equipment_model->change_item_category_for_new_process($result_old_data['orderID'], $new_item_category_old_data);

                    // Find subequipments
                    $result['confirmed_exchange']['order_subequipments'] = $this->equipment_model->get_order_subequipments($confirmed_exchange['uniqueID'],$equipment_id);
                    $result['confirmed_exchange']['subequipments'] = array();

                    foreach($result['confirmed_exchange']['order_subequipments'] as $order_subequipments) {
                        $sub_result = $this->equipment_model->get_capped_subequipment($result['confirmed_exchange']['capped_copy']['equipmentID'], $order_subequipments['key_desc']);
                        $result['confirmed_exchange']['subequipments'][] = $sub_result;

                        $new_item_category_subequipment = array(
                            'equipmentID' => $sub_result['equipmentID']
                        );
                        $this->equipment_model->change_item_category_for_new_process($order_subequipments['orderID'], $new_item_category_subequipment);

                        // Get the old data or old row for this subequipment
                        $sub_result_old_data = $this->equipment_model->get_order_subequipments_exchange_old($result_old_data['uniqueID'], $order_subequipments['equipmentID']);
                        $result['confirmed_exchange']['old_data']['subequipment'][] = $sub_result_old_data;

                        $new_item_category_subequipment_old_data = array(
                            'equipmentID' => $sub_result['equipmentID']
                        );
                        $this->equipment_model->change_item_category_for_new_process($sub_result_old_data['orderID'], $new_item_category_subequipment_old_data);
                    }
                }

                $result['pending_exchange']['orders'] = $this->equipment_model->get_items_for_change_item_category_pending_workorders_exchange($equipment_id, $date_selected, $hospiceID);

                // Change Item Category of dme_order for Pending Exchange
                foreach($result['pending_exchange']['orders'] as $pending_exchange) {
                    $result['pending_exchange']['capped_copy'] = $this->equipment_model->check_capped_copy_v2($equipment_id);
                    $new_item_category = array(
                        'equipmentID' => $result['pending_exchange']['capped_copy']['equipmentID']
                    );
                    $this->equipment_model->change_item_category_for_new_process($pending_exchange['orderID'], $new_item_category);

                    // Get the old data 
                    $result_old_data = $this->equipment_model->get_items_for_change_item_category_pending_workorders_exchange_old($pending_exchange['uniqueID'], $equipment_id);
                    $result['pending_exchange']['old_data'][] = $result_old_data;

                    $new_item_category_old_data = array(
                        'equipmentID' => $result['pending_exchange']['capped_copy']['equipmentID']
                    );
                    $this->equipment_model->change_item_category_for_new_process($result_old_data['orderID'], $new_item_category_old_data);

                    // Find subequipments
                    $result['pending_exchange']['order_subequipments'] = $this->equipment_model->get_order_subequipments($pending_exchange['uniqueID'], $equipment_id);
                    $result['pending_exchange']['subequipments'] = array();

                    foreach($result['pending_exchange']['order_subequipments'] as $order_subequipments) {
                        $sub_result = $this->equipment_model->get_capped_subequipment($result['pending_exchange']['capped_copy']['equipmentID'], $order_subequipments['key_desc']);
                        $result['pending_exchange']['subequipments'][] = $sub_result;

                        $new_item_category_subequipment = array(
                            'equipmentID' => $sub_result['equipmentID']
                        );
                        $this->equipment_model->change_item_category_for_new_process($order_subequipments['orderID'], $new_item_category_subequipment);

                        // Get the old data or old row for this subequipment
                        $sub_result_old_data = $this->equipment_model->get_order_subequipments_exchange_old($result_old_data['uniqueID'], $order_subequipments['equipmentID']);
                        $result['pending_exchange']['old_data']['subequipment'][] = $sub_result_old_data;

                        $new_item_category_subequipment_old_data = array(
                            'equipmentID' => $sub_result['equipmentID']
                        );
                        $this->equipment_model->change_item_category_for_new_process($sub_result_old_data['orderID'], $new_item_category_subequipment_old_data);
                    }
                }

                $data[] = array(
                    'equipmentID' => $equipment_id,
                    'result' => $result
                );

                
            }

            $this->response_code        = 0;
            $this->response_message     = "Successfull!";
        }
        echo json_encode(array(
			"error"		=> $this->response_code,
			"message"	=> $this->response_message
		));

        // print_me($hospiceID);
        // print_me($temp_date_selected);
        // print_me($date_selected);
        // print_me($equipment_ids);
        // print_me($data);
    }

    public function all_equipments()
    {
        $data['equipments'] = $this->equipment_model->list_equipment();
        $data['categories'] = $this->equipment_model->get_equipment_cat();
        $data['active_nav'] = "equipments";
        $this->templating_library->set('title','Fee Schedule');
        $this->templating_library->set_view('common/head','common/head');
        $this->templating_library->set_view('common/header','common/header');
        $this->templating_library->set_view('common/nav','common/nav', $data);
        $this->templating_library->set_view('pages/equipments_listing','pages/equipments_listing' ,$data);
        $this->templating_library->set_view('common/footer','common/footer');
        $this->templating_library->set_view('common/foot','common/foot');
    }

    public function all_equipments_by_hospice($hospiceID='')
    {
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'hospice_user') {
            $data['equipments'] = $this->equipment_model->list_equipment();
            $data['categories'] = $this->equipment_model->get_equipment_cat();
            $data['active_nav'] = "equipments";

            if($this->session->userdata('account_type') != "dme_admin" && $this->session->userdata('account_type') != "dme_user" && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'distribution_supervisor') {
                $hospiceID = $this->session->userdata('group_id');
            }
            else if($hospiceID == '') {
                $this->load->model("hospice_model");
                // $all_hospices = $this->hospice_model->list_group_v3($this->session->userdata('user_location'));
                // $hospiceID = $all_hospices[0]->hospiceID;
                $all_hospices = account_list_by_status($this->session->userdata('user_location'), 1);
                $hospiceID = $all_hospices[0]['hospiceID'];
            }
            $data['hospiceID'] = $hospiceID;
            $data['equipments_v3']  = $this->equipment_model->tobe_assign_equipment_v3($hospiceID);
            $data['current_hospice']    = $this->equipment_model->get_hospice($hospiceID);
        }
        
        $this->templating_library->set('title','Fee Schedule');
        $this->templating_library->set_view('common/head','common/head');
        $this->templating_library->set_view('common/header','common/header');
        $this->templating_library->set_view('common/nav','common/nav', $data);

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'hospice_user') {
            $this->templating_library->set_view('pages/equipments_listing','pages/equipments_listing' ,$data);
        }
        
        $this->templating_library->set_view('common/footer','common/footer');
        $this->templating_library->set_view('common/foot','common/foot');
    }

    // public function update_old_data_assigned_equipments() {
    //  $equipments = $this->equipment_model->list_equipment();

    //  $this->load->model("hospice_model");
    //  $all_hospices = $this->hospice_model->list_group_v3($this->session->userdata('user_location'));
    //  //$equipments_v3    = $this->equipment_model->tobe_assign_equipment_v3($all_hospices[14]->hospiceID);
    //  $done = 0;
    //  // print_me(count($all_hospices));
    //  foreach($all_hospices as $key => $hospice) {
    //      if($key > 40 && $key < 50){

    //          $equipments_v3  = $this->equipment_model->tobe_assign_equipment_v3($hospice->hospiceID);
    //          foreach($equipments as $index => $equipment) {
    //              // if($index != 3) {
    //              //  break;
    //              // }
    //              $check = false;
    //              foreach($equipments_v3 as $index2 => $equipment_v3) {
    //                  if($equipment['equipmentID'] == $equipment_v3[$index2]['equipmentID']) {
    //                      $check = true;
    //                      break;
    //                  }
    //              }

    //              if($check == false) {
    //                  $data['hospice_uniqueID'] = $this->equipment_model->get_hospice_uniqueID($hospice->hospiceID);
    //                  if(!empty($data['hospice_uniqueID']))
    //                  {
    //                      $data_assigned_equipment = array(
    //                          'equipmentID'   => $equipment['equipmentID'],
    //                          'hospiceID'     => $hospice->hospiceID,
    //                          'uniqueID'      => $data['hospice_uniqueID']['uniqueID'],
    //                          'is_hidden'     => 1
    //                      );
    //                  }
    //                  else
    //                  {
    //                      $unique_id = strtotime(date('Y-m-d H:i:s'));
    //                      $data_assigned_equipment = array(
    //                          'equipmentID'   => $equipment['equipmentID'],
    //                          'hospiceID'     => $hospice->hospiceID,
    //                          'uniqueID'      => $unique_id,
    //                          'is_hidden'     => 1
    //                      );
    //                  }
    //                  $this->equipment_model->insert_assigned_equipment($data_assigned_equipment);
    //              }
    //          }
    //          $done = $key;
    //          // break;
    //      } else {
    //          // break;
    //      }
    //  }
    //  // $equipments_v3   = $this->equipment_model->tobe_assign_equipment_v3($all_hospices[14]->hospiceID);
    //  print_me('done');
    //  print_me($done);
    // }
    public function update_old_data_assigned_equipments() {
        $equipments = $this->equipment_model->list_equipment();
        $this->load->model("hospice_model");
        $all_hospices = $this->hospice_model->list_group_v3($this->session->userdata('user_location'));
        //$equipments_v3    = $this->equipment_model->tobe_assign_equipment_v3($all_hospices[14]->hospiceID);
        $done = 0;
        // print_me(count($all_hospices));
        foreach($all_hospices as $key => $hospice) {
            $equipments_v3  = $this->equipment_model->tobe_assign_equipment_v3($hospice->hospiceID);
            foreach($equipments as $index => $equipment) {
                // if($index != 3) {
                //  break;
                // }
                $check = false;
                foreach($equipments_v3 as $index2 => $value) {
                    if($equipment['equipmentID'] == $value['equipmentID']) {
                        $check = true;
                        break;
                    }
                }

                if($check == false) {
                    $data['hospice_uniqueID'] = $this->equipment_model->get_hospice_uniqueID($hospice->hospiceID);
                    if(!empty($data['hospice_uniqueID']))
                    {
                        $data_assigned_equipment = array(
                            'equipmentID'   => $equipment['equipmentID'],
                            'hospiceID'     => $hospice->hospiceID,
                            'uniqueID'      => $data['hospice_uniqueID']['uniqueID'],
                            'is_hidden'     => 1
                        );
                    }
                    else
                    {
                        $unique_id = strtotime(date('Y-m-d H:i:s'));
                        $data_assigned_equipment = array(
                            'equipmentID'   => $equipment['equipmentID'],
                            'hospiceID'     => $hospice->hospiceID,
                            'uniqueID'      => $unique_id,
                            'is_hidden'     => 1
                        );
                    }
                    $this->equipment_model->insert_assigned_equipment($data_assigned_equipment);
                }
            }
            $done = $key;
        }
        // $equipments_v3   = $this->equipment_model->tobe_assign_equipment_v3($all_hospices[14]->hospiceID);
        print_me('done');
        print_me($done);
    }

    public function assign_equipment($hospiceID='')
    {
        $unique_id = strtotime(date('Y-m-d H:i:s'));

        $data_post = $this->input->post();
        $data_array = array();

        foreach($data_post['equipment_id'] as $key=>$value){
            $data_array[] = array(
                'hospiceID'     => $data_post['hospiceID'],
                'equipmentID'   => $value,
                'uniqueID'      => $unique_id
            );



            $data['equipment_details']   = $this->equipment_model->get_equipment_details($value);
            $data['capped_copy'] = $this->equipment_model->check_capped_copy_v2($data['equipment_details']['equipmentID']);
            if(!empty($data['capped_copy']))
            {
                $data['hospice_uniqueID'] = $this->equipment_model->get_hospice_uniqueID($data_post['hospiceID']);
                $data_assigned_equipment = array(
                    'equipmentID'   => $data['capped_copy']['equipmentID'],
                    'hospiceID'     => $data_post['hospiceID'],
                    'uniqueID'      => $data['hospice_uniqueID']['uniqueID']
                );
                $this->equipment_model->insert_assigned_equipment($data_assigned_equipment);
                $this->response_code = 0;
                $this->response_key_desc = $data['capped_copy']['key_desc'];
                $this->response_equipmentID = $data['capped_copy']['equipmentID'];
            }
            else
            {
                $data_new_equipment_parent = array(
                    'categoryID'            => 1,
                    'parentID'              => $data['equipment_details']['parentID'],
                    'key_name'              => $data['equipment_details']['key_name'],
                    'key_desc'              => $data['equipment_details']['key_desc'],
                    'input_type'            => $data['equipment_details']['input_type'],
                    'optionID'              => $data['equipment_details']['optionID'],
                    'option_order'          => $data['equipment_details']['option_order'],
                    'noncapped_reference'   =>  $data['equipment_details']['equipmentID']
                );
                $result_parent_id = $this->equipment_model->insert_new_equipment_parent($data_new_equipment_parent);
                if($result_parent_id)
                {
                    $data['sub_equipment_details']   = $this->equipment_model->get_sub_equipment_details($value);
                    if(!empty($data['sub_equipment_details']))
                    {
                        foreach ($data['sub_equipment_details'] as $row_sub) {
                            $data_new_equipment_sub = array(
                                'categoryID'            => 1,
                                'parentID'              => $result_parent_id,
                                'key_name'              => $row_sub['key_name'],
                                'key_desc'              => $row_sub['key_desc'],
                                'input_type'            => $row_sub['input_type'],
                                'optionID'              => $row_sub['optionID'],
                                'option_order'          => $row_sub['option_order'],
                                'noncapped_reference'   => 0
                            );
                            $result_sub = $this->equipment_model->insert_new_equipment_sub($data_new_equipment_sub);
                        }
                    }
                    $data['hospice_uniqueID'] = $this->equipment_model->get_hospice_uniqueID($data_post['hospiceID']);
                    $data_assigned_equipment = array(
                        'equipmentID' => $result_parent_id,
                        'hospiceID' => $data_post['hospiceID'],
                        'uniqueID' => $data['hospice_uniqueID']['uniqueID']
                    );
                    $this->equipment_model->insert_assigned_equipment($data_assigned_equipment);
                    $this->response_key_desc = $data['equipment_details']['key_desc'];
                    $this->response_equipmentID = $result_parent_id;
                }
            }
        }

        // $this->equipment_model->insert_equipments($data_array,$hospiceID);

        //** For the response
        $this->response_code        = 0;
        $this->response_message     = "Successfully Assigned Item.";

        echo json_encode(array(
                "error"     => $this->response_code,
                "message"   => $this->response_message
        ));
    }


    public function edit($equipID='')
    {
        $data_post = $this->input->post();

        $array = array(
            'key_desc' => $data_post['description'],
            'categoryID' => $data_post['category']
        );

        $this->equipment_model->update_equip_name($equipID, $array);

        //** For the response
        $this->response_code        = 0;
        $this->response_message     = "Successfully Edited.";

        echo json_encode(array(
                "error"     => $this->response_code,
                "message"   => $this->response_message
        ));

        //redirect('admin/equipment','refresh');
    }

    public function delete_equipment($equipID)
    {

        $this->equipment_model->delete_equipment($equipID);

        //** For the response (include_bottom.php)
        $this->response_code        = 0;
        $this->response_message     = "Item Successfully Deleted.";

        echo json_encode(array(
                "error"     => $this->response_code,
                "message"   => $this->response_message
        ));
    }

    public function add_equipment()
    {
        $this->form_validation->set_rules('cat_id','Category Name', 'required');
        $this->form_validation->set_rules('key_desc','Item Name', 'required');

        $data_post = $this->input->post();


        if($this->form_validation->run())
        {
            if($data_post['cat_id'] == 3)
            {
                $array = array(
                    'categoryID' => $data_post['cat_id'],
                    'key_name' => $data_post['key_name'],
                    'key_desc' => $data_post['key_desc'],
                    'input_type' =>'text'
                );

                $saved = $this->equipment_model->add_equipment($array);

                if($saved)
                {
                    $disposable_array = array(
                        'categoryID' => 3,
                        'parentID'   => $saved,
                        'key_name'   => 'quantity',
                        'key_desc'   => 'Quantity',
                        'input_type' => 'text',
                        'optionID'   => 0
                    );

                    $disposable_saved = $this->equipment_model->add_disposable_quantity($disposable_array);

                    if($disposable_saved)
                    {
                        $this->response_code = 0;
                        $this->response_message = "Successfully Saved Item.";
                    }
                    else
                    {
                        $this->response_message = "Failed to Add Item.";
                    }
                }
            }
            else
            {
                $array = array(
                    'categoryID' => $data_post['cat_id'],
                    'key_name' => $data_post['key_name'],
                    'key_desc' => $data_post['key_desc'],
                    'input_type' =>'checkbox'
                );
                $saved = $this->equipment_model->add_equipment($array);
                if($saved)
                {
                    $this->response_code = 0;
                    $this->response_message = "Successfully Saved Item";
                }
                else
                {
                    $this->response_message = "Failed to Add Item.";
                }
            }
        }

        else
        {
            $this->response_message = validation_errors('<p>');
        }


        //** For the response (include_bottom.php)
        $this->response_code        = 0;
        $this->response_message     = "Successfully Added.";

        echo json_encode(array(
                    "error"     => $this->response_code,
                    "message"   => $this->response_message
        ));
    }

    public function view_disposable_modal($data_name, $equip_id)
    {
        $data['data_name']  = $data_name;
        $data['equip_id']   = $equip_id;
        $sub_ids    = $this->equipment_model->query_sub_equipment_id($equip_id);

        foreach($sub_ids as $sub_id)
        {
            $quantities = array(
                "equipmentID" => $sub_id['equipmentID']
            );
        }

        $data['quantity_ids'] = $quantities;
        $this->templating_library->set_view('pages/disposable_modal','pages/disposable_modal', $data);
        //$this->templating_library->set_view('common/foot','common/foot');
        $this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
    }

    public function cancel_equipment($equipmentID, $medical_id, $canceled_status, $workorder_activity_type, $uniqueID, $orderID, $original_orderID)
    {
        $data_post = $this->input->post();

        if($canceled_status == 0)
        {
            $array = array(
                'canceled_order' => 1
            );
        }
        else
        {
            $array = array(
                'canceled_order' => 0
            );
        }
        // Version 1
        // $updated = $this->equipment_model->cancel_equipment($equipmentID, $medical_id, $uniqueID, $array);

        // Version 2 => Updated 02/05/2021
        $updated = $this->equipment_model->cancel_equipment_v2($orderID, $array);

        // For Adult Aerosol Mask. It will also be cancelled if the Small Volume Nebulizer is cancelled.
        if($equipmentID == 40 || $equipmentID == 67)
        {
            $updated = $this->equipment_model->cancel_equipment(156, $medical_id, $uniqueID, $array);
        }

        if($canceled_status == 0)
        {
            if($workorder_activity_type == 2)
            {
                $original_results = $this->equipment_model->original_equipment_to_cancel_pickup($medical_id, $uniqueID, $equipmentID);
                $this->response_orderID = $original_results['orderID'];
                if($original_results['original_activity_typeid'] == 3)
                {
                    $equipment_before_exchange = $this->equipment_model->get_equipment_before_exchange($medical_id, $original_results['uniqueID'], $equipmentID);
                    if($equipment_before_exchange['original_activity_typeid'] == 5)
                    {
                        $revert_pickedup_item_to_original = array(
                            'pickedup_uniqueID'         => 0,
                            'activity_reference'        => 5,
                            'activity_typeid'           => $original_results['original_activity_typeid']
                        );
                    }
                    else
                    {
                        $revert_pickedup_item_to_original = array(
                            'pickedup_uniqueID'         => 0,
                            'activity_reference'        => 3,
                            'activity_typeid'           => $original_results['original_activity_typeid']
                        );
                    }
                }
                else if($original_results['original_activity_typeid'] == 5)
                {
                    $revert_pickedup_item_to_original = array(
                        'pickedup_uniqueID'         => $original_results['uniqueID'],
                        'activity_reference'        => 1,
                        'activity_typeid'           => $original_results['original_activity_typeid'],
                        'pickedup_respite_order'    => 0
                    );
                }
                else
                {
                    $revert_pickedup_item_to_original = array(
                        'pickedup_uniqueID'         => $original_results['uniqueID'],
                        'activity_reference'        => 1,
                        'activity_typeid'           => $original_results['original_activity_typeid']
                    );
                }
                $reverted_item_activty_type = $this->equipment_model->revert_pickedup_item_to_original($original_results['equipmentID'],$uniqueID,$medical_id,$revert_pickedup_item_to_original);
                $this->equipment_model->delete_equipment_pickup($equipmentID,$uniqueID);
                $equipment_options = get_equipment_options($equipmentID);
                if(!empty($equipment_options))
                {
                    foreach ($equipment_options as $value_equip_options)
                    {
                        $this->equipment_model->delete_equipment_options_pickup($value_equip_options['equipmentID'],$uniqueID);
                    }
                }
            }
            else if($workorder_activity_type == 1 || $workorder_activity_type == 4 || $workorder_activity_type == 5)
            {
                $equipment_pickup_order = get_equipment_order($equipmentID,$uniqueID);
                if($equipment_pickup_order['pickedup_uniqueID'] != 0 && $equipment_pickup_order['pickedup_uniqueID'] != $uniqueID)
                {
                    if($equipment_pickup_order['original_activity_typeid'] == 3)
                    {
                        $equipment_before_exchange = $this->equipment_model->get_equipment_before_exchange($medical_id, $uniqueID, $equipmentID);
                        if($equipment_before_exchange['original_activity_typeid'] == 5)
                        {
                            $revert_pickedup_item_to_original = array(
                                'pickedup_uniqueID'         => 0,
                                'activity_reference'        => 5,
                                'activity_typeid'           => $equipment_pickup_order['original_activity_typeid']
                            );
                        }
                        else
                        {
                            $revert_pickedup_item_to_original = array(
                                'pickedup_uniqueID'         => 0,
                                'activity_reference'        => 3,
                                'activity_typeid'           => $equipment_pickup_order['original_activity_typeid']
                            );
                        }
                    }
                    else if($equipment_pickup_order['original_activity_typeid'] == 5)
                    {
                        $revert_pickedup_item_to_original = array(
                            'pickedup_uniqueID'         => $equipment_pickup_order['uniqueID'],
                            'activity_reference'        => 1,
                            'activity_typeid'           => $equipment_pickup_order['original_activity_typeid'],
                            'pickedup_respite_order'    => 0
                        );
                    }
                    else
                    {
                        $revert_pickedup_item_to_original = array(
                            'pickedup_uniqueID'         => $equipment_pickup_order['uniqueID'],
                            'activity_reference'        => 1,
                            'activity_typeid'           => $equipment_pickup_order['original_activity_typeid']
                        );
                    }
                    $reverted_item_activty_type = $this->equipment_model->revert_pickedup_item_to_original_v2($equipment_pickup_order['equipmentID'],$uniqueID,$medical_id,$revert_pickedup_item_to_original);
                    $this->equipment_model->delete_equipment_pickup($equipmentID,$equipment_pickup_order['pickedup_uniqueID']);
                    $equipment_options = get_equipment_options($equipmentID);
                    if(!empty($equipment_options))
                    {
                        foreach ($equipment_options as $value_equip_options)
                        {
                            $this->equipment_model->delete_equipment_pickup($value_equip_options['equipmentID'],$equipment_pickup_order['pickedup_uniqueID']);
                        }
                    }
                }

                // For Adult Aerosol Mask. It will also be cancelled if the Small Volume Nebulizer is cancelled.
                if($equipmentID == 40 || $equipmentID == 67)
                {
                    $equipment_pickup_order = get_equipment_order(156,$uniqueID);
                    if($equipment_pickup_order['pickedup_uniqueID'] != 0 && $equipment_pickup_order['pickedup_uniqueID'] != $uniqueID)
                    {
                        if($equipment_pickup_order['original_activity_typeid'] == 3)
                        {
                            $equipment_before_exchange = $this->equipment_model->get_equipment_before_exchange($medical_id, $uniqueID, 156);
                            if($equipment_before_exchange['original_activity_typeid'] == 5)
                            {
                                $revert_pickedup_item_to_original = array(
                                    'pickedup_uniqueID'         => 0,
                                    'activity_reference'        => 5,
                                    'activity_typeid'           => $equipment_pickup_order['original_activity_typeid']
                                );
                            }
                            else
                            {
                                $revert_pickedup_item_to_original = array(
                                    'pickedup_uniqueID'         => 0,
                                    'activity_reference'        => 3,
                                    'activity_typeid'           => $equipment_pickup_order['original_activity_typeid']
                                );
                            }
                        }
                        else if($equipment_pickup_order['original_activity_typeid'] == 5)
                        {
                            $revert_pickedup_item_to_original = array(
                                'pickedup_uniqueID'         => $equipment_pickup_order['uniqueID'],
                                'activity_reference'        => 1,
                                'activity_typeid'           => $equipment_pickup_order['original_activity_typeid'],
                                'pickedup_respite_order'    => 0
                            );
                        }
                        else
                        {
                            $revert_pickedup_item_to_original = array(
                                'pickedup_uniqueID'         => $equipment_pickup_order['uniqueID'],
                                'activity_reference'        => 1,
                                'activity_typeid'           => $equipment_pickup_order['original_activity_typeid']
                            );
                        }
                        $reverted_item_activty_type = $this->equipment_model->revert_pickedup_item_to_original_v2($equipment_pickup_order['equipmentID'],$uniqueID,$medical_id,$revert_pickedup_item_to_original);
                        $this->equipment_model->delete_equipment_pickup(156,$equipment_pickup_order['pickedup_uniqueID']);
                        $equipment_options = get_equipment_options(156);
                        if(!empty($equipment_options))
                        {
                            foreach ($equipment_options as $value_equip_options)
                            {
                                $this->equipment_model->delete_equipment_pickup($value_equip_options['equipmentID'],$equipment_pickup_order['pickedup_uniqueID']);
                            }
                        }
                    }
                }
            }
        }
        else
        {
            if($workorder_activity_type == 2)
            {
                $original_results = $this->equipment_model->original_equipment_details($original_orderID);
                if($original_results['original_activity_typeid'] == 5)
                {
                    $revert_to_pickup = array(
                        'pickedup_uniqueID'         => $uniqueID,
                        'activity_reference'        => 2,
                        'activity_typeid'           => 2,
                        'pickedup_respite_order'    => 1
                    );
                }
                else
                {
                    $revert_to_pickup = array(
                        'pickedup_uniqueID'         => $uniqueID,
                        'activity_reference'        => 2,
                        'activity_typeid'           => 2
                    );
                }
                $revert_to_pickup = $this->equipment_model->revert_to_pickup($original_orderID,$revert_to_pickup);
            }
            else if($workorder_activity_type == 1 || $workorder_activity_type == 4 || $workorder_activity_type == 5)
            {
                $equipment_details = get_equipment_details_on_order($equipmentID,$uniqueID,$medical_id);
                $equipment_order = get_equipment_order_v2($medical_id,$uniqueID);
                if($equipment_order['pickedup_uniqueID'] != 0 && $equipment_order['pickedup_uniqueID'] != $equipment_order['uniqueID'])
                {
                    $equipment_pickup_order = get_equipment_pickup_order($medical_id,$equipment_order['pickedup_uniqueID']);
                    if($equipment_details['original_activity_typeid'] == 1 || $equipment_details['original_activity_typeid'] == 4)
                    {
                        $revert_pickedup_item_to_original = array(
                            'pickedup_uniqueID'         => $equipment_pickup_order['uniqueID'],
                            'activity_reference'        => 2,
                            'activity_typeid'           => 2
                        );
                    }
                    else if($equipment_details['original_activity_typeid'] == 5)
                    {
                        $pickedup_respite_order_val = 0;
                        if($equipment_pickup_order['pickedup_respite_order'] == 1)
                        {
                            $pickedup_respite_order_val = 1;
                        }
                        $revert_pickedup_item_to_original = array(
                            'pickedup_uniqueID'         => $equipment_pickup_order['uniqueID'],
                            'activity_reference'        => 2,
                            'activity_typeid'           => 2,
                            'pickedup_respite_order'    => $pickedup_respite_order_val
                        );
                    }
                    $reverted_item_activty_type = $this->equipment_model->revert_pickedup_item_to_original_v2($equipmentID,$uniqueID,$medical_id,$revert_pickedup_item_to_original);
                    $add_equipment_row = array(
                                        "patientID"                 => $equipment_pickup_order['patientID'],
                                        "equipmentID"               => $equipmentID,
                                        "equipment_value"           => $equipment_details['equipment_value'],
                                        "pickup_date"               => $equipment_pickup_order['pickup_date'],
                                        "activity_typeid"           => $equipment_pickup_order['activity_typeid'],
                                        "organization_id"           => $equipment_pickup_order['organization_id'],
                                        "ordered_by"                => $equipment_pickup_order['ordered_by'],
                                        "who_ordered_fname"         => $equipment_pickup_order['who_ordered_fname'],
                                        "who_ordered_lname"         => $equipment_pickup_order['who_ordered_lname'],
                                        "staff_member_fname"        => $equipment_pickup_order['staff_member_fname'],
                                        "staff_member_lname"        => $equipment_pickup_order['staff_member_lname'],
                                        "who_ordered_email"         => $equipment_pickup_order['who_ordered_email'],
                                        "who_ordered_cpnum"         => $equipment_pickup_order['who_ordered_cpnum'],
                                        "comment"                   => $equipment_pickup_order['comment'],
                                        "uniqueID"                  => $equipment_pickup_order['uniqueID'],
                                        "deliver_to_type"           => $equipment_pickup_order['deliver_to_type'],
                                        'medical_record_id'         => $equipment_pickup_order['medical_record_id'],
                                        "serial_num"                => "pickup_order_only",
                                        "order_status"              => $equipment_pickup_order['order_status'],
                                        "pickup_order"              => $equipment_pickup_order['pickup_order'],
                                        "initial_order"             => $equipment_pickup_order['initial_order'],
                                        "original_activity_typeid"  => $equipment_pickup_order['original_activity_typeid'],
                                        "activity_reference"        => $equipment_pickup_order['activity_reference'], //remove if it will cause errors (newly added)
                                        "addressID"                 => $equipment_pickup_order['addressID']
                                    );
                    $this->equipment_model->insert_new_row_pickup($add_equipment_row);
                    $equipment_options = get_equipment_options($equipmentID);
                    if(!empty($equipment_options))
                    {
                        foreach ($equipment_options as $value_equip_options)
                        {
                            $equipment_option_details = get_equipment_details_on_order($value_equip_options['equipmentID'],$uniqueID,$medical_id);
                            if(!empty($equipment_option_details))
                            {
                                $add_equipment_row_options = array(
                                            "patientID"                 => $equipment_pickup_order['patientID'],
                                            "equipmentID"               => $value_equip_options['equipmentID'],
                                            "equipment_value"           => $equipment_option_details['equipment_value'],
                                            "pickup_date"               => $equipment_pickup_order['pickup_date'],
                                            "activity_typeid"           => $equipment_pickup_order['activity_typeid'],
                                            "organization_id"           => $equipment_pickup_order['organization_id'],
                                            "ordered_by"                => $equipment_pickup_order['ordered_by'],
                                            "who_ordered_fname"         => $equipment_pickup_order['who_ordered_fname'],
                                            "who_ordered_lname"         => $equipment_pickup_order['who_ordered_lname'],
                                            "staff_member_fname"        => $equipment_pickup_order['staff_member_fname'],
                                            "staff_member_lname"        => $equipment_pickup_order['staff_member_lname'],
                                            "who_ordered_email"         => $equipment_pickup_order['who_ordered_email'],
                                            "who_ordered_cpnum"         => $equipment_pickup_order['who_ordered_cpnum'],
                                            "comment"                   => $equipment_pickup_order['comment'],
                                            "uniqueID"                  => $equipment_pickup_order['uniqueID'],
                                            "deliver_to_type"           => $equipment_pickup_order['deliver_to_type'],
                                            'medical_record_id'         => $equipment_pickup_order['medical_record_id'],
                                            "serial_num"                => "pickup_order_only",
                                            "order_status"              => $equipment_pickup_order['order_status'],
                                            "pickup_order"              => $equipment_pickup_order['pickup_order'],
                                            "initial_order"             => $equipment_pickup_order['initial_order'],
                                            "original_activity_typeid"  => $equipment_pickup_order['original_activity_typeid'],
                                            "activity_reference"        => $equipment_pickup_order['activity_reference'], //remove if it will cause errors (newly added)
                                            "addressID"                 => $equipment_pickup_order['addressID']
                                        );
                                $this->equipment_model->insert_new_row_pickup($add_equipment_row_options);
                            }
                        }
                    }
                }

                // For Adult Aerosol Mask. It will also be cancelled if the Small Volume Nebulizer is cancelled.
                if($equipmentID == 40 || $equipmentID == 67)
                {
                    $equipment_details = get_equipment_details_on_order(156,$uniqueID,$medical_id);
                    $equipment_order = get_equipment_order_v2($medical_id,$uniqueID);
                    if($equipment_order['pickedup_uniqueID'] != 0 && $equipment_order['pickedup_uniqueID'] != $equipment_order['uniqueID'])
                    {
                        $equipment_pickup_order = get_equipment_pickup_order($medical_id,$equipment_order['pickedup_uniqueID']);
                        if($equipment_details['original_activity_typeid'] == 1 || $equipment_details['original_activity_typeid'] == 4)
                        {
                            $revert_pickedup_item_to_original = array(
                                'pickedup_uniqueID'         => $equipment_pickup_order['uniqueID'],
                                'activity_reference'        => 2,
                                'activity_typeid'           => 2
                            );
                        }
                        else if($equipment_details['original_activity_typeid'] == 5)
                        {
                            $pickedup_respite_order_val = 0;
                            if($equipment_pickup_order['pickedup_respite_order'] == 1)
                            {
                                $pickedup_respite_order_val = 1;
                            }
                            $revert_pickedup_item_to_original = array(
                                'pickedup_uniqueID'         => $equipment_pickup_order['uniqueID'],
                                'activity_reference'        => 2,
                                'activity_typeid'           => 2,
                                'pickedup_respite_order'    => $pickedup_respite_order_val
                            );
                        }

                        $reverted_item_activty_type = $this->equipment_model->revert_pickedup_item_to_original_v2(156,$uniqueID,$medical_id,$revert_pickedup_item_to_original);
                        $add_equipment_row = array(
                                            "patientID"                 => $equipment_pickup_order['patientID'],
                                            "equipmentID"               => 156,
                                            "equipment_value"           => $equipment_details['equipment_value'],
                                            "pickup_date"               => $equipment_pickup_order['pickup_date'],
                                            "activity_typeid"           => $equipment_pickup_order['activity_typeid'],
                                            "organization_id"           => $equipment_pickup_order['organization_id'],
                                            "ordered_by"                => $equipment_pickup_order['ordered_by'],
                                            "who_ordered_fname"         => $equipment_pickup_order['who_ordered_fname'],
                                            "who_ordered_lname"         => $equipment_pickup_order['who_ordered_lname'],
                                            "staff_member_fname"        => $equipment_pickup_order['staff_member_fname'],
                                            "staff_member_lname"        => $equipment_pickup_order['staff_member_lname'],
                                            "who_ordered_email"         => $equipment_pickup_order['who_ordered_email'],
                                            "who_ordered_cpnum"         => $equipment_pickup_order['who_ordered_cpnum'],
                                            "comment"                   => $equipment_pickup_order['comment'],
                                            "uniqueID"                  => $equipment_pickup_order['uniqueID'],
                                            "deliver_to_type"           => $equipment_pickup_order['deliver_to_type'],
                                            'medical_record_id'         => $equipment_pickup_order['medical_record_id'],
                                            "serial_num"                => "pickup_order_only",
                                            "order_status"              => $equipment_pickup_order['order_status'],
                                            "pickup_order"              => $equipment_pickup_order['pickup_order'],
                                            "initial_order"             => $equipment_pickup_order['initial_order'],
                                            "original_activity_typeid"  => $equipment_pickup_order['original_activity_typeid'],
                                            "activity_reference"        => $equipment_pickup_order['activity_reference'], //remove if it will cause errors (newly added)
                                            "addressID"                 => $equipment_pickup_order['addressID']
                                        );
                        $this->equipment_model->insert_new_row_pickup($add_equipment_row);
                        $equipment_options = get_equipment_options(156);
                        if(!empty($equipment_options))
                        {
                            foreach ($equipment_options as $value_equip_options)
                            {
                                $equipment_option_details = get_equipment_details_on_order($value_equip_options['equipmentID'],$uniqueID,$medical_id);
                                if(!empty($equipment_option_details))
                                {
                                    $add_equipment_row_options = array(
                                                "patientID"                 => $equipment_pickup_order['patientID'],
                                                "equipmentID"               => $value_equip_options['equipmentID'],
                                                "equipment_value"           => $equipment_option_details['equipment_value'],
                                                "pickup_date"               => $equipment_pickup_order['pickup_date'],
                                                "activity_typeid"           => $equipment_pickup_order['activity_typeid'],
                                                "organization_id"           => $equipment_pickup_order['organization_id'],
                                                "ordered_by"                => $equipment_pickup_order['ordered_by'],
                                                "who_ordered_fname"         => $equipment_pickup_order['who_ordered_fname'],
                                                "who_ordered_lname"         => $equipment_pickup_order['who_ordered_lname'],
                                                "staff_member_fname"        => $equipment_pickup_order['staff_member_fname'],
                                                "staff_member_lname"        => $equipment_pickup_order['staff_member_lname'],
                                                "who_ordered_email"         => $equipment_pickup_order['who_ordered_email'],
                                                "who_ordered_cpnum"         => $equipment_pickup_order['who_ordered_cpnum'],
                                                "comment"                   => $equipment_pickup_order['comment'],
                                                "uniqueID"                  => $equipment_pickup_order['uniqueID'],
                                                "deliver_to_type"           => $equipment_pickup_order['deliver_to_type'],
                                                'medical_record_id'         => $equipment_pickup_order['medical_record_id'],
                                                "serial_num"                => "pickup_order_only",
                                                "order_status"              => $equipment_pickup_order['order_status'],
                                                "pickup_order"              => $equipment_pickup_order['pickup_order'],
                                                "initial_order"             => $equipment_pickup_order['initial_order'],
                                                "original_activity_typeid"  => $equipment_pickup_order['original_activity_typeid'],
                                                "activity_reference"        => $equipment_pickup_order['activity_reference'], //remove if it will cause errors (newly added)
                                                "addressID"                 => $equipment_pickup_order['addressID']
                                            );
                                    $this->equipment_model->insert_new_row_pickup($add_equipment_row_options);
                                }
                            }
                        }
                    }
                }
            }
        }

        if($updated)
        {
            $this->response_code = 0;
            $this->response_message = "Successfully updated.";
        }
        else
        {
            $this->response_message = "Failed to Cancel Item";
        }

        echo json_encode(array(
                    "response_orderID" => $this->response_orderID,
                    "error"     => $this->response_code,
                    "message"   => $this->response_message
        ));
    }
    // echo "==".$original_results['equipmentID'];
    // echo "==".$uniqueID;
    // echo "==".$medical_id;
    // echo "==".$old_activity_type."==";
    // print_r($revert_pickedup_item_to_original);

    public function cancel_equipment_exchange($equipmentID, $medical_id, $canceled_status, $workorder_activity_type, $uniqueID, $original_orderID)
    {
        $data_post = $this->input->post();

        if($canceled_status == 0)
        {
            $array = array(
                'canceled_order' => 1
            );
        }
        else
        {
            $array = array(
                'canceled_order' => 0
            );
        }

        $queried_data = get_original_activity_typeid($equipmentID, $medical_id, $uniqueID);
        $new_orderID = $original_orderID;
        $uniqueID_tobe_used = 0;
        if($new_orderID == 0)
        {
            if($queried_data['original_activity_typeid'] == 3)
            {
                $uniqueID_tobe_used = $uniqueID;
            }
            else
            {
                $uniqueID_tobe_used = $queried_data['uniqueID_reference'];
            }
        }
        else
        {
            if($queried_data['original_activity_typeid'] == 3)
            {
                $uniqueID_tobe_used = $uniqueID;
            }
            else
            {
                $queried_uniqueID = get_uniqueID_through_orderID($new_orderID);
                $uniqueID_tobe_used = $queried_uniqueID['uniqueID'];
            }
        }
        $updated = $this->equipment_model->cancel_equipment($equipmentID, $medical_id, $uniqueID_tobe_used, $array);

        if($canceled_status == 0)
        {
            $original_results = $this->equipment_model->original_equipment_to_cancel_exchange($medical_id, $uniqueID_tobe_used, $equipmentID);

            if($queried_data['original_activity_typeid'] == 3)
            {
                $this->response_orderID = $queried_data['orderID'];
            }
            else
            {
                $queried_orderID = get_orderID_through_uniqueID($queried_data['uniqueID_reference']);
                $this->response_orderID = $queried_orderID['orderID'];
            }

            if($original_results['original_activity_typeid'] == 3)
            {
                $equipment_before_exchange = $this->equipment_model->get_equipment_before_exchange($medical_id, $original_results['uniqueID'], $equipmentID);
                if($equipment_before_exchange['original_activity_typeid'] == 5)
                {
                    $revert_pickedup_item_to_original = array(
                        'uniqueID_reference'        => 0,
                        'activity_reference'        => 5,
                        'activity_typeid'           => $original_results['original_activity_typeid']
                    );
                }
                else
                {
                    $revert_pickedup_item_to_original = array(
                        'uniqueID_reference'        => 0,
                        'activity_reference'        => 3,
                        'activity_typeid'           => $original_results['original_activity_typeid']
                    );
                }
            }
            else if($original_results['original_activity_typeid'] == 5)
            {
                $revert_pickedup_item_to_original = array(
                    'uniqueID_reference'        => 0,
                    'activity_reference'        => 1,
                    'activity_typeid'           => $original_results['original_activity_typeid'],
                );
            }
            else
            {
                $revert_pickedup_item_to_original = array(
                    'uniqueID_reference'        => 0,
                    'activity_reference'        => 1,
                    'activity_typeid'           => $original_results['original_activity_typeid']
                );
            }
            $reverted_item_activty_type = $this->equipment_model->revert_exchange_item_to_original($original_results['equipmentID'],$original_results['uniqueID'],$medical_id,$revert_pickedup_item_to_original);
        }
        else
        {
            $revert_to_pickup = array(
                'uniqueID_reference'        => $uniqueID_tobe_used,
                'activity_reference'        => 3,
                'activity_typeid'           => 2
            );

            $queried_orderID = get_orderID_through_uniqueID_v2($uniqueID_tobe_used);
            $revert_to_pickup = $this->equipment_model->revert_to_exchange($queried_orderID['orderID'],$revert_to_pickup);
        }

        if($updated)
        {
            $this->response_code = 0;
            $this->response_message = "Successfully updated.";
        }
        else
        {
            $this->response_message = "Failed to Cancel Item";
        }

        echo json_encode(array(
                    "response_orderID" => $this->response_orderID,
                    "error"     => $this->response_code,
                    "message"   => $this->response_message
        ));
    }

    public function view_add_patient_weight($medical_id,$unique_id,$equipmentID,$patientID)
    {
        $data['medical_id'] = $medical_id;
        $data['unique_id'] = $unique_id;
        $data['equipment_id'] = $equipmentID;
        $data['patient_id']  = $patientID;

        $this->templating_library->set('title','Add Customer Weight');
        $this->templating_library->set_view('pages/patient_weight','pages/patient_weight', $data);
        //$this->templating_library->set_view('common/foot','common/foot');
        $this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
    }

    public function insert_patient_weight()
    {
        $this->form_validation->set_rules('patient_weight','Customer Weight','required');

        $data_post = $this->input->post();

        if($this->form_validation->run())
        {
            $data = array(
                "patient_weight" => $data_post['patient_weight'],
                "medical_record_num" => $data_post['medical_id'],
                "patientID"         => $data_post['patientID'],
                "ticket_uniqueID"   => $data_post['unique_id'],
                "equipmentID"       => $data_post['equipment_id']
            );

            $inserted = $this->equipment_model->insert_patient_weight($data);

            if($inserted)
            {
                $this->response_code = 0;
                $this->response_message = "Successfully saved the customer weight.";
            }
            else
            {
                $this->response_message = "Failed to save customer weight.";
            }
        }
        else
        {
            $this->response_message = validation_errors("<span></span>");
        }

        echo json_encode(array(
                    "error"     => $this->response_code,
                    "message"   => $this->response_message
        ));
    }

    public function assign_to_capped($equipmentID,$hospice_id,$category_id)
    {
        if($equipmentID)
        {
            $data['equipment_details']   = $this->equipment_model->get_equipment_details($equipmentID);
            $data['capped_copy'] = $this->equipment_model->check_capped_copy_v2($data['equipment_details']['equipmentID']);
            if(!empty($data['capped_copy']))
            {
                $data['hospice_uniqueID'] = $this->equipment_model->get_hospice_uniqueID($hospice_id);
                $data_assigned_equipment = array(
                    'equipmentID'   => $data['capped_copy']['equipmentID'],
                    'hospiceID'     => $hospice_id,
                    'uniqueID'      => $data['hospice_uniqueID']['uniqueID']
                );
                $this->equipment_model->insert_assigned_equipment($data_assigned_equipment);
                if ($data['capped_copy']['equipmentID'] == 61) {
                    // For 5L Oxygen Concentrator
                    $data_assigned_equipment = array(
                        'equipmentID'   => 316,
                        'hospiceID'     => $hospice_id,
                        'uniqueID'      => $data['hospice_uniqueID']['uniqueID']
                    );
                    $this->equipment_model->insert_assigned_equipment($data_assigned_equipment);

                    // For 10L Oxygen Concentrator
                    $data_assigned_equipment = array(
                        'equipmentID'   => 325,
                        'hospiceID'     => $hospice_id,
                        'uniqueID'      => $data['hospice_uniqueID']['uniqueID']
                    );
                    $this->equipment_model->insert_assigned_equipment($data_assigned_equipment);
                }
                $this->response_code = 0;
                $this->response_key_desc = $data['capped_copy']['key_desc'];
                $this->response_equipmentID = $data['capped_copy']['equipmentID'];
            }
            else
            {
                $data_new_equipment_parent = array(
                    'categoryID'            => 1,
                    'parentID'              => $data['equipment_details']['parentID'],
                    'key_name'              => $data['equipment_details']['key_name'],
                    'key_desc'              => $data['equipment_details']['key_desc'],
                    'input_type'            => $data['equipment_details']['input_type'],
                    'optionID'              => $data['equipment_details']['optionID'],
                    'option_order'          => $data['equipment_details']['option_order'],
                    'noncapped_reference'   => $data['equipment_details']['equipmentID'],
                    'equipment_company_item_no' => $data['equipment_details']['equipment_company_item_no']
                );
                $result_parent_id = $this->equipment_model->insert_new_equipment_parent($data_new_equipment_parent);
                if($result_parent_id)
                {
                    $data['sub_equipment_details']   = $this->equipment_model->get_sub_equipment_details($equipmentID);
                    if(!empty($data['sub_equipment_details']))
                    {
                        foreach ($data['sub_equipment_details'] as $row_sub) {
                            $data_new_equipment_sub = array(
                                'categoryID'                    => 1,
                                'parentID'                      => $result_parent_id,
                                'key_name'                      => $row_sub['key_name'],
                                'key_desc'                      => $row_sub['key_desc'],
                                'input_type'                    => $row_sub['input_type'],
                                'optionID'                      => $row_sub['optionID'],
                                'option_order'                  => $row_sub['option_order'],
                                'noncapped_reference'           => 0,
                                'equipment_company_item_no'     => $row_sub['equipment_company_item_no']
                            );
                            $result_sub = $this->equipment_model->insert_new_equipment_sub($data_new_equipment_sub);
                        }
                    }
                    $data['hospice_uniqueID'] = $this->equipment_model->get_hospice_uniqueID($hospice_id);
                    $data_assigned_equipment = array(
                        'equipmentID' => $result_parent_id,
                        'hospiceID' => $hospice_id,
                        'uniqueID' => $data['hospice_uniqueID']['uniqueID']
                    );
                    $this->equipment_model->insert_assigned_equipment($data_assigned_equipment);
                    $this->response_code = 0;
                    $this->response_key_desc = $data['equipment_details']['key_desc'];
                    $this->response_equipmentID = $result_parent_id;
                }
            }
        }
        else
        {
            $this->response_code = 1;
        }
        echo json_encode(array(
            "error"                 => $this->response_code,
            "key_desc"              => $this->response_key_desc,
            "return_equipmentID"    => $this->response_equipmentID
        ));
    }

    public function remove_from_capped($equipmentID,$hospice_id)
    {
        if($equipmentID && $hospice_id)
        {
            $data['equipment_details']   = $this->equipment_model->get_equipment_details($equipmentID);
            $data['capped_copy'] = $this->equipment_model->check_capped_copy_v2($equipmentID);

            $this->response_equipmentID = $data['capped_copy']['equipmentID'];
            $this->response_code = 1;
            $return = $this->equipment_model->remove_assigned_equipment($data['capped_copy']['equipmentID'],$hospice_id);

            if($return)
            {
                if ($data['capped_copy']['equipmentID'] == 61) {
                    $this->equipment_model->remove_assigned_equipment(316,$hospice_id);
                    $this->equipment_model->remove_assigned_equipment(325,$hospice_id);
                }
                
                $this->response_code = 0;
            }
        }
        echo json_encode(array(
            "error"                 => $this->response_code,
            "return_equipmentID"    => $this->response_equipmentID
        ));
    }

    public function hide_item($equipmentID,$hospice_id)
    {
        if($equipmentID && $hospice_id)
        {
            $data['equipment_details']   = $this->equipment_model->get_equipment_details($equipmentID);
            $data['capped_copy'] = $this->equipment_model->check_capped_copy_v2($equipmentID);
            $this->response_equipmentID = $data['capped_copy']['equipmentID'];
            $this->response_code = 1;
            $data['assigned_capped_copy'] = $this->equipment_model->get_assigned_capped_copy($data['capped_copy']['equipmentID'],$hospice_id);
            $this->response_check_capped_assigned = 0;
            if(!empty($data['assigned_capped_copy']))
            {
                $this->response_check_capped_assigned = 1;
            }
            $data_array = array(
                "is_hidden" => 1
            );
            $this->equipment_model->remove_assigned_equipment_v2($data['capped_copy']['equipmentID'],$hospice_id, $data_array);
            $return = $this->equipment_model->remove_assigned_equipment_v2($equipmentID,$hospice_id, $data_array);
            if($return)
            {
                $this->response_code = 0;
            }
        }
        echo json_encode(array(
            "error"                             => $this->response_code,
            "return_equipmentID"                => $this->response_equipmentID,
            "response_check_capped_assigned"    => $this->response_check_capped_assigned
        ));
    }

    public function show_item($equipmentID,$hospice_id)
	{
		$this->response_code = 1;
		if($equipmentID && $hospice_id)
		{
			$data['hospice_uniqueID'] = $this->equipment_model->get_hospice_uniqueID($hospice_id);
			if(!empty($data['hospice_uniqueID']))
			{
				$data_assigned_equipment = array(
					'equipmentID' 	=> $equipmentID,
					'hospiceID' 	=> $hospice_id,
					'uniqueID' 		=> $data['hospice_uniqueID']['uniqueID'],
					'is_hidden'		=> 0
				);
			}
			else
			{
				$unique_id = strtotime(date('Y-m-d H:i:s'));
				$data_assigned_equipment = array(
					'uniqueID' 		=> $unique_id,
					'is_hidden'		=> 0
				);
			}

			$this->equipment_model->insert_assigned_equipment_v2($equipmentID,$hospice_id,$data_assigned_equipment);
			$this->response_code = 0;
		}
		echo json_encode(array(
			"error"			=> $this->response_code,
			"return_equipmentID"    => $this->response_equipmentID
		));
	}

    public function view_add_lot_number($medical_id,$unique_id,$equipmentID,$patientID)
    {
        $data['medical_id'] = $medical_id;
        $data['unique_id'] = $unique_id;
        $data['equipment_id'] = $equipmentID;
        $data['patient_id']  = $patientID;

        $this->templating_library->set('title','Add Lot Number');
        $this->templating_library->set_view('pages/lot_number_page','pages/lot_number_page', $data);
        //$this->templating_library->set_view('common/foot','common/foot');
        $this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
    }

    public function insert_lot_number()
    {
        $this->form_validation->set_rules('lot_number_value','Lot Number','required');

        $data_post = $this->input->post();

        if($this->form_validation->run())
        {
            $data = array(
                "lot_number_content" => $data_post['lot_number_value'],
                "medical_record_num" => $data_post['medical_id'],
                "ticket_uniqueID"   => $data_post['unique_id'],
                "equipmentID"       => $data_post['equipment_id'],
                "patientID"         => $data_post['patient_id']
            );

            // $inserted = $this->equipment_model->insert_lot_number($data);

            if($inserted)
            {
                $this->response_code = 0;
                $this->response_message = "Successfully saved the lot number for this item.";
            }
            else
            {
                $this->response_message = "Failed to saves lot number for this item.";
            }
        }
        else
        {
            $this->response_message = validation_errors("<span></span>");
        }

        echo json_encode(array(
                    "error"     => $this->response_code,
                    "message"   => $this->response_message
        ));
    }

    public function submit_equipment_rates() {
        $data_post = $this->input->post();
        // print_me($data_post);
        $equipments = $this->equipment_model->tobe_assign_equipment_v3($data_post['hospiceID']);
        foreach($data_post['equipmentItems'] as $index => $value) {
            // print_me($index);
            $data = array(
                'monthly_rate' => (float)$data_post['monthly_rate'][$index],
                'daily_rate' => (float)$data_post['daily_rate'][$index],
                'purchase_price' => (float)$data_post['purchase_rate'][$index],
            );
            $this->equipment_model->update_equip_v2($data_post['hospiceID'],$index, $data);


        }

        $this->load->model("hospice_model");
        $result['hospices'] = $this->hospice_model->list_group_v3($this->session->userdata('user_location'));

        $this->templating_library->set('title','List of Hospices');
        $this->templating_library->set_view('common/head','common/head');
        $this->templating_library->set_view('common/header','common/header');
        $this->templating_library->set_view('common/nav','common/nav');
        $this->templating_library->set_view('pages/hospice_list','pages/hospice_list' , $result);
        $this->templating_library->set_view('common/footer','common/footer');
        $this->templating_library->set_view('common/foot','common/foot');
    }

    public function submit_equipment_rates_autosave() {
		$data_post = $this->input->post();
		// print_me($data_post);
		$equipments = $this->equipment_model->tobe_assign_equipment_v3($data_post['hospiceID']);
		foreach($data_post['equipmentItems'] as $index => $value) {
			// print_me($index);
			$data = array(
				'monthly_rate' => (float)$data_post['monthly_rate'][$index],
				'daily_rate' => (float)$data_post['daily_rate'][$index],
				'purchase_price' => (float)$data_post['purchase_rate'][$index],
			);
			$this->equipment_model->update_equip_v2($data_post['hospiceID'],$index, $data);
		}

		$this->response_code = 0;
		$this->response_message = "Successfully saved.";

		echo json_encode(array(
			"error"		=> $this->response_code,
			"message"	=> $this->response_message
		));
    }
    
    public function submit_itemgroup_equipment_rates() {
		$data_post = $this->input->post();

		foreach($data_post['subequipment'] as $key => $value) {
			$data = array(
				"assigned_equipmentID" 	=> $data_post['assigned_equipmentID'],
				"equipmentID"			=> $data_post['equipmentID'],
				"key_name"				=> $value,
				"monthly_rate"			=> $data_post['monthlyrate'][$key],
				"daily_rate"			=> $data_post['dailyrate'][$key],
				"purchase_price"		=> $data_post['purchaseprice'][$key]
			);

			$existing = $this->equipment_model->get_sub_equipment_rates_by_keyname($data_post['assigned_equipmentID'], $value);

			if (!empty($existing)) {
				$update = $this->equipment_model->update_sub_equipment_rates($existing['ID'], $data);
			} else {
				$insert = $this->equipment_model->insert_sub_equipment_rates($data);
			}
		}

		$this->response_code = 0;
		$this->response_message = "Successfully saved.";

		echo json_encode(array(
			"error"		=> $this->response_code,
			"message"	=> $this->response_message
		));
	}

	public function get_itemgroup_equipment_rates($assigned_equipmentID) {
		$rates = $this->equipment_model->get_sub_equipment_rates($assigned_equipmentID);

		echo json_encode($rates);
    }
    
    public function get_customer_capped_item($patientID, $equipmentID, $customer_hospice_id) {
		if($this->input->is_ajax_request()) {
			if($patientID && $equipmentID)
			{
				$data['item_capped'] = check_capped_copy_v2($equipmentID);
				if(!empty($data['item_capped']))
				{
					$data['hospice_capped_item'] = null;
					$data['customer_ordered_capped_item'] = $this->equipment_model->get_customer_ordered_capped_item($patientID,$data['item_capped']['equipmentID']);
					$data['hospice_capped_item'] = $this->equipment_model->check_if_hospice_assigned_item($data['item_capped']['equipmentID'], $customer_hospice_id);
				}
			}
		}
		echo json_encode($data);
	}
}

