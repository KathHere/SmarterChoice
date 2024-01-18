<?php

class inventory extends Ci_Controller
{
    public $response_code = 1; //false or error default
    public $response_message = '';
    public $response_data = array();

    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        date_default_timezone_set('America/Los_Angeles');
        $this->load->model('inventory_model');
        $this->load->model('item_grouping_model');
        $this->load->model('order_model');
        $this->load->library('encryption');
        $this->load->library('input');
    }

    public function create_view_purchase_order_lookup()
    {
        $this->inventory_model->update_item_location($data);
    }

    // public function update_item_location()
    // {
    //  $data = array(
    //      'item_location' => 1
    //  );
    //  $this->inventory_model->update_item_location($data);
    // }

    // public function update_purchase_order_requisition_receiving()
    // {
    //     $all_purchase_order_requisitions = $this->inventory_model->get_all_purchase_order_requisitions(0,100);

    //     foreach ($all_purchase_order_requisitions as $key => $value) {
    //         $data_inventory_items = $this->inventory_model->get_data_inventory_items($value['order_req_id'],$value['purchase_order_no']);

    //         if(!empty($data_inventory_items))
    //         {
    //             foreach ($data_inventory_items as $inside_key => $inside_value) {
    //                 $data = array(
    //                     'item_batch_no' => $inside_value['item_batch_no'],
    //                     'item_unit_measure' => $inside_value['item_unit_measure'],
    //                     'item_cost' => $inside_value['item_cost'],
    //                     'item_total_cost' => $inside_value['item_total_cost']
    //                 );
    //                 $this->inventory_model->update_purchase_order_requisition_receiving($inside_value['order_req_id'],$inside_value['purchase_order_no'],$inside_value['item_id'],$data);
    //             }
    //         }
    //     }
    // }

    public function copy_item_par_levels()
    {
        $item_par_levels = $this->inventory_model->get_item_par_levels();

        $data_tobe_inserted_array = array();
        foreach ($item_par_levels as $key => $value) {
            $data_tobe_inserted_first = array(
                'item_id' => $value['item_id'],
                'company_item_no' => $value['company_item_no'],
                'item_par_level' => $value['item_par_level'],
                'item_location' => 1,
            );
            $data_tobe_inserted_array[] = $data_tobe_inserted_first;

            $data_tobe_inserted_second = array(
                'item_id' => $value['item_id'],
                'company_item_no' => $value['company_item_no'],
                'item_par_level' => $value['item_par_level'],
                'item_location' => 2,
            );
            $data_tobe_inserted_array[] = $data_tobe_inserted_second;
        }
        $this->inventory_model->insert_item_par_level_batch($data_tobe_inserted_array);
    }

    public function copy_item_warehouse_locations()
    {
        $item_warehouse_locations = $this->inventory_model->get_item_warehouse_locations();

        $data_tobe_inserted_array = array();
        foreach ($item_warehouse_locations as $key => $value) {
            $data_tobe_inserted_first = array(
                'item_id' => $value['item_id'],
                'company_item_no' => $value['company_item_no'],
                'item_warehouse_location' => $value['item_warehouse_location'],
                'item_location' => 1,
            );
            $data_tobe_inserted_array[] = $data_tobe_inserted_first;

            $data_tobe_inserted_second = array(
                'item_id' => $value['item_id'],
                'company_item_no' => $value['company_item_no'],
                'item_warehouse_location' => $value['item_warehouse_location'],
                'item_location' => 2,
            );
            $data_tobe_inserted_array[] = $data_tobe_inserted_second;
        }
        $this->inventory_model->insert_item_warehouse_location_batch($data_tobe_inserted_array);
    }

    public function add_new_item()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');


        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'distribution_supervisor') {
            $data['item_group_list'] = $this->item_grouping_model->get_all_item_groups();
            $data['vendor_list'] = $this->inventory_model->get_vendor_list();
            $data['item_category_list'] = $this->inventory_model->get_item_category_list();

            $this->templating_library->set_view('pages/add_new_item_inventory', 'pages/add_new_item_inventory', $data);
        }
        
        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function save_new_item()
    {
        if ($this->input->post()) {
            $data_post = $this->input->post();

            $this->form_validation->set_rules('company_item_no', 'Company Item No.', 'required');
            $this->form_validation->set_rules('item_description', 'Item Description', 'required');
            $this->form_validation->set_rules('item_vendor', 'Vendor', 'required');
            $this->form_validation->set_rules('item_vendor_acct_no', 'Account No.', 'required');
            $this->form_validation->set_rules('item_reorder_no', 'Re-order No.', 'required');
            $this->form_validation->set_rules('item_warehouse_location', 'Warehouse Location', 'required');
            $this->form_validation->set_rules('item_category', 'Category', 'required');
            $this->form_validation->set_rules('add_to_hospice_item_list', 'Add to Hospice Item List', 'required');

            if ($this->form_validation->run() === true) {
                $vendor_item_exist = $this->inventory_model->get_vendor_item_info($data_post['company_item_no'], $data_post['item_vendor'], $data_post['item_reorder_no']);
                if (empty($vendor_item_exist)) {
                    $item_information = array(
                        'company_item_no' => $data_post['company_item_no'],
                        'item_description' => $data_post['item_description'],
                        'item_vendor' => $data_post['item_vendor'],
                        'item_vendor_acct_no' => $data_post['item_vendor_acct_no'],
                        'item_reorder_no' => $data_post['item_reorder_no'],
                        'item_category' => $data_post['item_category'],
                        // 'item_group_id' => $data_post['item_group_id'],
                        'add_to_hospice_item_list' => $data_post['add_to_hospice_item_list'],
                    );
                    $item_id = $this->inventory_model->save_item_information($item_information);

                    $dme_locations = $this->inventory_model->get_dme_locations();
                    foreach ($dme_locations as $key => $value) {
                        $item_warehouse_location_info = array(
                            'item_id' => $item_id,
                            'company_item_no' => $data_post['company_item_no'],
                            'item_warehouse_location' => strtoupper($data_post['item_warehouse_location']),
                            'item_location' => $value['location_id'],
                        );
                        $this->inventory_model->save_item_warehouse_location_information($item_warehouse_location_info);

                        $item_par_level_info = array(
                            'item_id' => $item_id,
                            'company_item_no' => $data_post['company_item_no'],
                            'item_par_level' => $data_post['item_par_level'],
                            'item_location' => $value['location_id'],
                        );
                        $this->inventory_model->save_item_par_level_information($item_par_level_info);
                    }

                    $unit_measure_type = array('box', 'each', 'case', 'pair', 'pack', 'package', 'roll');
                    $value_name = '';
                    $vendor_cost_name = '';
                    $company_cost_name = '';
                    foreach ($unit_measure_type as $value) {
                        $value_name = 'item_unit_value_'.$value;
                        $vendor_cost_name = 'item_vendor_cost_'.$value;
                        $company_cost_name = 'item_company_cost_'.$value;

                        if (!empty($data_post[$value_name]) && !empty($data_post[$vendor_cost_name]) && !empty($data_post[$company_cost_name])) {
                            $save_item_unit = array(
                                'item_id' => $item_id,
                                'item_unit_measure' => $value,
                                'company_item_no' => $data_post['company_item_no'],
                                'item_unit_value' => $data_post[$value_name],
                                'item_vendor_cost' => $data_post[$vendor_cost_name],
                                'item_company_cost' => $data_post[$company_cost_name],
                            );
                            $this->inventory_model->save_item_unit($save_item_unit);
                        }

                        $data_new_item_cost = array(
                            'item_company_cost' => $data_post[$company_cost_name],
                        );
                        $this->inventory_model->update_data_new_item_cost($data_new_item_cost, $value, $data_post['company_item_no']);
                    }

                    // ADD TO HOSPICE LIST 07/15/2021 ----- START
                    $is_equipment_exist = $this->inventory_model->get_equipment_details($data_post['company_item_no']);
                    if (empty($is_equipment_exist)) {
                        if ($data_post['add_to_hospice_item_list'] == 1) {
                            $equipment_category_id = 2;
                            if ($data_post['disposable_item_list'] != null) {
                                $equipment_category_id = $data_post['disposable_item_list'];
                            }
                            $hospice_list = $this->inventory_model->get_dme_hospices_with_uniqueID($this->session->userdata('user_location'));
    
                            
                            $new_equipment = array(
                                'categoryID'                => $equipment_category_id,
                                'parentID'                  => 0,
                                'key_name'                  => str_replace(' ', '_', $data_post['item_description']),
                                'key_desc'                  => $data_post['item_description'],
                                'input_type'                => 'checkbox',
                                'optionID'                  => 0,
                                'option_order'              => 0,
                                'noncapped_reference'       => 0,
                                'equipment_company_item_no' => $data_post['company_item_no'],
                                'is_recurring'              => 0,
                                'is_package'                => 0
                            );
    
                            $equipment_id = $this->inventory_model->insert_new_equipment($new_equipment);
    
                            if ($equipment_id) {
                                if ($equipment_category_id == 3) {
                                    $new_equipment_quantity = array(
                                        'categoryID'                => $equipment_category_id,
                                        'parentID'                  => $equipment_id,
                                        'key_name'                  => 'quantity',
                                        'key_desc'                  => 'Quantity',
                                        'input_type'                => 'text',
                                        'optionID'                  => 0,
                                        'option_order'              => 0,
                                        'noncapped_reference'       => 0,
                                        'equipment_company_item_no' => 0,
                                        'is_recurring'              => 0,
                                        'is_package'                => 0
                                    );
        
                                    $equipment_id_quantity = $this->inventory_model->insert_new_equipment($new_equipment_quantity);
                                }
    
                                $new_assigned_equipment = array(
                                    'equipmentID'       => $equipment_id,
                                    'hospiceID'         => 0,
                                    'uniqueID'          => 0,
                                    'monthly_rate'      => 0,
                                    'daily_rate'        => 0,
                                    'purchase_price'    => 0,
                                    'is_hidden'         => 0
                                );
    
                                foreach($hospice_list as $value) {
                                    if ($value['uniqueID'] != null) {
                                        $new_assigned_equipment['hospiceID'] = $value['hospiceID'];
                                        $new_assigned_equipment['uniqueID'] = $value['uniqueID'];
    
                                        $this->inventory_model->insert_new_assigned_equipment($new_assigned_equipment);
                                    }
                                }
                            }
                        }
                    } else {
                        if ($data_post['add_to_hospice_item_list'] == 1) {
                            if ($is_equipment_exist['is_deleted'] == 1 || $is_equipment_exist['is_deactivated'] == 1) {
                                $data_exist_equipment = array(
                                    'is_deleted' => 0,
                                    'is_deactivated' => 0,
                                    'is_add_to_hospice_item_list', $data_post['add_to_hospice_item_list']
                                );
                                // $this->inventory_model->update_equipment_company_item_no($data_exist_equipment, $is_equipment_exist['equipmentID']);
                                $this->inventory_model->update_equipment_company_item_no_v2($data_exist_equipment, $data_post['company_item_no']);
                            }
                        }
                    }
                    // ADD TO HOSPICE LIST 07/15/2021 ----- END

                    $this->response_code = 0;
                    $this->response_message = 'Item Added Successfully.';
                } else {
                    $this->response_code = 1;
                    $this->response_message = 'Company Item No. and the Reorder No. is already taken.';
                }

                $data_new_item_details = array(
                    'item_description' => $data_post['item_description'],
                );
                $this->inventory_model->update_item_details($data_new_item_details, $data_post['company_item_no']);

                $data_new_item_par_level_details = array(
                    'item_par_level' => $data_post['item_par_level'],
                );
                $this->inventory_model->update_item_par_level_details($data_new_item_par_level_details, $data_post['company_item_no'], $this->session->userdata('user_location'));

                $data_new_item_location_details = array(
                    'item_warehouse_location' => strtoupper($data_post['item_warehouse_location']),
                );
                $this->inventory_model->update_item_warehouse_location_details($data_new_item_location_details, $data_post['company_item_no'], $this->session->userdata('user_location'));
            } else {
                $this->response_message = validation_errors('<span></span>');
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));

            exit;
        }
    }

    public function delete_vendor_item($sign, $item_id)
    {
        $data = array(
            'item_deleted_sign' => 1,
        );

        // ADD TO HOSPICE LIST 07/15/2021 ----- START
        $item_details = $this->inventory_model->get_item_details($item_id);
        if (!empty($item_details)) {
            $equipment_details = $this->inventory_model->get_equipment_details($item_details['company_item_no']);
            $data_new = array(
                'is_deleted' => 1,
            );
            // $this->inventory_model->update_equipment_company_item_no($data_new, $equipment_details['equipmentID']);
            $this->inventory_model->update_equipment_company_item_no_v2($data_new, $equipment_details['equipment_company_item_no']);
        }
        // ADD TO HOSPICE LIST 07/15/2021 ----- END

        $response = $this->inventory_model->delete_vendor_item($item_id, $data);

        if ($response) {
            $this->response = 0;
            if ($sign == 1) {
                $this->response_message = 'Item deleted successfully.';
            } else {
                $this->response_message = 'Item deleted successfully. You will be redirected to the item vendor.';
            }
        } else {
            $this->response = 1;
            $this->response_message = 'Error deleting the item.';
        }

        echo json_encode(array(
            'error' => $this->response,
            'message' => $this->response_message,
        ));
    }

    public function item_search()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        $data = array();
        $data['search_type'] = 'item_search';

        $this->templating_library->set_view('pages/inventory_search', 'pages/inventory_search', $data);
        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function search_inventory_item($vendor_id)
    {
        $search_string = '';
        $count = 0;
        if (isset($_GET['searchString'])) {
            $search_string = $_GET['searchString'];
        }
        if ($vendor_id == 0 || $vendor_id == '') {
            $vendor_id = '';
        }
        $searches = $this->inventory_model->search_inventory_item_v3($vendor_id, $search_string, '', $this->session->userdata('user_location'));
        $count = $this->inventory_model->search_inventory_item_v3($vendor_id, $search_string, true, $this->session->userdata('user_location'));

        if ($searches) {
            foreach ($searches as $search) {
                if ($search['item_active_sign'] == 1) {
                    echo "<tr style='color:#0E0C0C !important;cursor:pointer;border:1px solid #f5f5f5' class='inventory_item_results form-control' data-company-item-no='".$search['company_item_no']."' data-id='".$search['item_id']."' data-inventory-item-id='".$search['inventory_item_id']."'><td>".$search['item_description'].'</td></tr>';
                } else {
                    echo "<tr style='color:#5F5B5A !important;cursor:pointer;border:1px solid #f5f5f5' class='inventory_item_results form-control' data-company-item-no='".$search['company_item_no']."' data-id='".$search['item_id']."' data-inventory-item-id='".$search['inventory_item_id']."'><td>".$search['item_description'].'</td></tr>';
                }
            }
            if ($count > 5) {
                echo "<tr style='border:1px solid #f5f5f5;background-color: #58666e;color: white;'
                        class='form-control'>
                            <td align='center' style='font-style: italic;font-size: 12px;'>There are <strong>{$count}</strong> items in this result. <a style='color:white;text-decoration:underline;cursor:pointer;' class='result-item-lists' href='javascript:;'>View all results</a></td></tr>";
            }
        } else {
            echo "<tr style='border:1px solid #f5f5f5' class='form-control'><td>No Results Found.</td></tr>";
        }
    }

    public function search_item($vendor_id)
    {
        $search_string = '';
        $count = 0;
        $another_count = 0;
        if (isset($_GET['searchString'])) {
            $search_string = $_GET['searchString'];
        }
        if ($vendor_id == 0 || $vendor_id == '') {
            $vendor_id = '';
        }
        // $searches    = $this->inventory_model->search_item($vendor_id,$search_string);
        // $count   = $this->inventory_model->search_item($vendor_id,$search_string,true);

        $searches = $this->inventory_model->search_item_with_location($vendor_id, $this->session->userdata('user_location'), $search_string);
        $count = $this->inventory_model->search_item_with_location($vendor_id, $this->session->userdata('user_location'), $search_string, true);

        $company_item_no_array = array();
        if ($searches) {
            foreach ($searches as $search) {
                if ($another_count < 5) {
                    if ($search['item_active_sign'] == 1) {
                        echo "<tr style='color:#0E0C0C !important;cursor:pointer;border:1px solid #f5f5f5' class='item_results form-control' data-company-item-no='".$search['company_item_no']."' data-id='".$search['item_id']."'><td>".$search['item_description'].'</td></tr>';
                    } else {
                        echo "<tr style='color:#5F5B5A !important;cursor:pointer;border:1px solid #f5f5f5' class='item_results form-control' data-company-item-no='".$search['company_item_no']."' data-id='".$search['item_id']."'><td>".$search['item_description'].'</td></tr>';
                    }
                    $company_item_no_array[] = $search['company_item_no'];
                    ++$another_count;
                }
            }
            if ($count > 5) {
                echo "<tr style='border:1px solid #f5f5f5;background-color: #58666e;color: white;'
                        class='form-control'>
                            <td align='center' style='font-style: italic;font-size: 12px;'>There are <strong>{$count}</strong> items in this result. <a style='color:white;text-decoration:underline;cursor:pointer;' class='result-lists' href='javascript:;'>View all results</a></td></tr>";
            }
        } else {
            echo "<tr style='border:1px solid #f5f5f5' class='form-control'><td>No Results Found.</td></tr>";
        }
    }

    public function search_item_v2($vendor_id)
    {
        $search_string = '';
        $count = 0;
        $another_count = 0;
        if (isset($_GET['searchString'])) {
            $search_string = $_GET['searchString'];
        }
        if ($vendor_id == 0 || $vendor_id == '') {
            $vendor_id = '';
        }

        $searches = $this->inventory_model->search_item_with_location_v2($vendor_id, $this->session->userdata('user_location'), $search_string);
        // $searches = $this->inventory_model->search_item_v2($vendor_id, $search_string);

        $company_item_no_array = array();
        if ($searches) {
            foreach ($searches as $search) {
                if ($search['item_active_sign'] == 1) {
                    echo "<tr style='color:#0E0C0C !important;cursor:pointer;border:1px solid #f5f5f5' class='item_results form-control' data-company-item-no='".$search['company_item_no']."' data-id='".$search['item_id']."'><td>".$search['item_description'].'</td></tr>';
                } else {
                    echo "<tr style='color:#5F5B5A !important;cursor:pointer;border:1px solid #f5f5f5' class='item_results form-control' data-company-item-no='".$search['company_item_no']."' data-id='".$search['item_id']."'><td>".$search['item_description'].'</td></tr>';
                }
                $company_item_no_array[] = $search['company_item_no'];
            }
        } else {
            echo "<tr style='border:1px solid #f5f5f5' class='form-control'><td>No Results Found.</td></tr>";
        }
    }

    public function get_searched_inventory_item($item_id, $inventory_item_id)
    {
        $response = array('data' => array());

        $response['item_details'] = $this->inventory_model->get_searched_inventory_item_with_item_location($item_id, $inventory_item_id, $this->session->userdata('user_location'));

        echo json_encode($response);
    }

    public function check_item_asset_no_value($item_asset_no_value)
    {
        $response = array('data' => array());

        $response['item_asset_no_value'] = $this->inventory_model->check_item_asset_no_value($item_asset_no_value);

        echo json_encode($response);
    }

    public function get_item_unit_measure_value($item_id)
    {
        $response = array('data' => array());

        $response['item_unit_of_measures'] = $this->inventory_model->get_item_unit_measure_value_v2($item_id);

        echo json_encode($response);
    }

    public function get_searched_item($item_id)
    {
        $response = array('data' => array());

        $response['item_details'] = $this->inventory_model->get_searched_item_with_item_location($item_id, $this->session->userdata('user_location'));
        $response['item_unit_of_measures'] = $this->inventory_model->get_item_unit_measure_value_v2($item_id);
        $data['item_list'] = $this->inventory_model->get_items_through_company_item_no($response['item_details']['company_item_no']);

        $response['total_on_hand'] = 0;
        foreach ($data['item_list'] as $key => $value) {
            $order_req_id_array = array();
            $total_of_two = 0;
            $data['item_on_hand_batch1'] = 0;
            $data['item_on_hand_batch2'] = 0;

            $data['item_on_hand_list'] = $this->inventory_model->get_item_on_hand_list($value['item_id']);
            if (!empty($data['item_on_hand_list'])) {
                foreach ($data['item_on_hand_list'] as $inside_key => $inside_value) {
                    if (!in_array($inside_value['order_req_id'], $order_req_id_array)) {
                        $order_req_id_array[] = $inside_value['order_req_id'];
                    }
                    $data['item_on_hand_batch1'] += $inside_value['req_item_quantity_received'];
                }
            }

            $data['item_on_hand_batch2'] = $this->inventory_model->get_item_on_hand($value['item_id'], $order_req_id_array);
            $total_of_two = $data['item_on_hand_batch1'] + $data['item_on_hand_batch2'];
            $response['total_on_hand'] += $total_of_two;
        }

        echo json_encode($response);
    }

    public function get_searched_item_v2($vendor_id, $item_no)
    {
        $response = array('data' => array());
        $response['item_cost'] = array();

        $response['item_details'] = $this->inventory_model->get_searched_item_v2($vendor_id, $item_no);
        $item_cost_result = $this->inventory_model->get_item_cost($response['item_details']['item_id']);
        foreach ($item_cost_result as $value) {
            if ($value['item_unit_measure'] == 'each') {
                $response['item_cost'] = $value;
                break;
            }
        }

        if (empty($response['item_cost'])) {
            if (!empty($item_cost_result)) {
                $response['item_cost'] = $item_cost_result[0];
            }
        }

        echo json_encode($response);
    }

    public function get_vedor_item_list($vendor_id)
    {
        $response = array('data' => array());

        $response['vendor_items'] = $this->inventory_model->get_vendor_items_v2($vendor_id);
        $response['vendor_details'] = $this->inventory_model->get_searched_vendor($vendor_id);

        echo json_encode($response);
    }

    public function get_searched_item_v3($vendor_id, $item_description)
    {
        $response = array('data' => array());
        $response['item_cost'] = array();
        $item_description = ltrim($item_description, '%20');
        $item_description = str_replace('%20', ' ', $item_description);

        $response['item_details'] = $this->inventory_model->get_searched_item_v3($vendor_id, $item_description);
        $item_cost_result = $this->inventory_model->get_item_cost($response['item_details']['item_id']);
        foreach ($item_cost_result as $value) {
            if ($value['item_unit_measure'] == 'each') {
                $response['item_cost'] = $value;
                break;
            }
        }

        if (empty($response['item_cost'])) {
            if (!empty($item_cost_result)) {
                $response['item_cost'] = $item_cost_result[0];
            }
        }

        echo json_encode($response);
    }

    public function get_searched_item_v4($item_id)
    {
        $response = array('data' => array());
        $response['item_cost'] = array();

        $item_cost_result = $this->inventory_model->get_item_cost($item_id);
        foreach ($item_cost_result as $value) {
            if ($value['item_unit_measure'] == 'each') {
                $response['item_cost'] = $value;
                break;
            }
        }

        if (empty($response['item_cost'])) {
            if (!empty($item_cost_result)) {
                $response['item_cost'] = $item_cost_result[0];
            }
        }

        echo json_encode($response);
    }

    public function view_all_searched_items($vendor_id, $searched_content)
    {
        $new_searched_content = str_replace('%20', ' ', $searched_content);

        $response = array('data' => array());
        $response['item_unit_of_measures'] = array();

        if ($vendor_id == 0 || $vendor_id == '') {
            $vendor_id = '';
        }
        $response['searched_items'] = $this->inventory_model->search_item_v2_with_item_location($vendor_id, $new_searched_content, $this->session->userdata('user_location'));
        foreach ($response['searched_items'] as $key => $value) {
            $response['item_unit_of_measures'][$value['item_id']] = $this->inventory_model->get_item_unit_measure_value_v2($value['item_id']);
            $data['item_list'] = $this->inventory_model->get_items_through_company_item_no($value['company_item_no']);

            $response['total_on_hand'][$value['item_id']] = 0;
            foreach ($data['item_list'] as $key_first => $value_first) {
                $order_req_id_array = array();
                $total_of_two = 0;
                $data['item_on_hand_batch1'] = 0;
                $data['item_on_hand_batch2'] = 0;

                $data['item_on_hand_list'] = $this->inventory_model->get_item_on_hand_list($value_first['item_id']);
                if (!empty($data['item_on_hand_list'])) {
                    foreach ($data['item_on_hand_list'] as $inside_key => $inside_value) {
                        if (!in_array($inside_value['order_req_id'], $order_req_id_array)) {
                            $order_req_id_array[] = $inside_value['order_req_id'];
                        }
                        $data['item_on_hand_batch1'] += $inside_value['req_item_quantity_received'];
                    }
                }

                $data['item_on_hand_batch2'] = $this->inventory_model->get_item_on_hand($value_first['item_id'], $order_req_id_array);
                $total_of_two = $data['item_on_hand_batch1'] + $data['item_on_hand_batch2'];
                $response['total_on_hand'][$value['item_id']] += $total_of_two;
            }
        }
        echo json_encode($response);
    }

    public function view_all_searched_inventory_items($vendor_id, $searched_content)
    {
        $new_searched_content = str_replace('%20', ' ', $searched_content);

        $response = array('data' => array());

        if ($vendor_id == 0 || $vendor_id == '') {
            $vendor_id = '';
        }
        $response['searched_items'] = $this->inventory_model->search_inventory_item_v4($vendor_id, $new_searched_content, $this->session->userdata('user_location'));

        echo json_encode($response);
    }

    public function add_new_vendor()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'distribution_supervisor') {
            $data = array();
            $this->templating_library->set_view('pages/add_new_vendor_inventory', 'pages/add_new_vendor_inventory', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function save_new_vendor()
    {
        if ($this->input->post()) {
            $data_post = $this->input->post();

            $this->form_validation->set_rules('vendor_entry_date', 'Entry Date', 'required');
            $this->form_validation->set_rules('vendor_name', 'Vendor Name', 'required');
            $this->form_validation->set_rules('vendor_acct_no', 'Account No.', 'required');
            $this->form_validation->set_rules('vendor_credit', 'Credit', 'required');
            $this->form_validation->set_rules('vendor_credit_terms', 'Credit Terms', 'required');
            $this->form_validation->set_rules('vendor_credit_limit', 'Credit Limit', 'required');
            $this->form_validation->set_rules('vendor_city', 'City', 'required');
            $this->form_validation->set_rules('vendor_state', 'State/Province', 'required');
            $this->form_validation->set_rules('vendor_postal_code', 'Postal Code', 'required');
            $this->form_validation->set_rules('vendor_phone_no', 'Phone No.', 'required');
            $this->form_validation->set_rules('vendor_fax_no', 'Fax No.', 'required');
            $this->form_validation->set_rules('vendor_email_address', 'Email Address', 'required');
            $this->form_validation->set_rules('vendor_sales_rep', 'Sales Rep.', 'required');
            $this->form_validation->set_rules('vendor_office_no', 'Office No.', 'required');
            $this->form_validation->set_rules('vendor_cell_no', 'Cell No.', 'required');
            $this->form_validation->set_rules('vendor_sales_rep_email_address', 'Sales Rep. Email Address', 'required');
            $this->form_validation->set_rules('vendor_shipping_cost', 'Shipping Cost', 'required');

            if ($this->form_validation->run() === true) {
                $vendor_information = array(
                    'vendor_entry_date' => date('Y-m-d', strtotime($data_post['vendor_entry_date'])),
                    'vendor_name' => $data_post['vendor_name'],
                    'vendor_acct_no' => $data_post['vendor_acct_no'],
                    'vendor_credit' => $data_post['vendor_credit'],
                    'vendor_credit_terms' => $data_post['vendor_credit_terms'],
                    'vendor_credit_limit' => $data_post['vendor_credit_limit'],
                    'vendor_street' => $data_post['vendor_street'],
                    'vendor_city' => $data_post['vendor_city'],
                    'vendor_state' => $data_post['vendor_state'],
                    'vendor_postal_code' => $data_post['vendor_postal_code'],
                    'vendor_phone_no' => $data_post['vendor_phone_no'],
                    'vendor_fax_no' => $data_post['vendor_fax_no'],
                    'vendor_email_address' => $data_post['vendor_email_address'],
                    'vendor_sales_rep' => $data_post['vendor_sales_rep'],
                    'vendor_office_no' => $data_post['vendor_office_no'],
                    'vendor_cell_no' => $data_post['vendor_cell_no'],
                    'vendor_sales_rep_email_address' => $data_post['vendor_sales_rep_email_address'],
                    'vendor_shipping_cost' => $data_post['vendor_shipping_cost'],
                );

                $vendor_id = $this->inventory_model->save_vendor_information($vendor_information);

                $this->returned_id = $vendor_id;
                $this->returned_vendor_name = $data_post['vendor_name'];
                $this->returned_vendor_acct_no = $data_post['vendor_acct_no'];
                $this->response_code = 0;
                $this->response_message = 'Vendor Added Successfully.';
            } else {
                $this->response_message = validation_errors('<span></span>');
            }

            echo json_encode(array(
                'vendor_id' => $this->returned_id,
                'vendor_name' => $this->returned_vendor_name,
                'vendor_acct_no' => $this->returned_vendor_acct_no,
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));

            exit;
        }
    }

    public function vendor_details($vendor_id)
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        $data['vendor_details'] = $this->inventory_model->get_vendor_details($vendor_id);
        $data['vendor_items'] = $this->inventory_model->get_vendor_items_v3_with_item_location($vendor_id, $this->session->userdata('user_location'));
        $data['vendor_items2'] = $this->inventory_model->get_vendor_items_v2($vendor_id);
        $data['order_inquiries_payment'] = $this->inventory_model->get_order_inquiries_bills_specific_vendor($vendor_id);

        $data['open_balance'] = 0;
        if (!empty($data['order_inquiries_payment'])) {
            $req_payment_batch_no = 0;
            foreach ($data['order_inquiries_payment'] as $key => $value) {
                if ($value['req_payment_batch_no'] != $req_payment_batch_no) {
                    if ($value['payment_date'] == '0000-00-00') {
                        if (!in_array($value['purchase_order_no'], $purchase_order_no)) {
                            $data['open_balance'] += $value['ending_balance'];
                        }
                    }
                }
            }
        }

        $this->templating_library->set_view('pages/view_vendor_details', 'pages/view_vendor_details', $data);
        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function get_item_details($company_item_no, $item_vendor = "")
    {
        $response = array('data' => array());

        $response['item_details'] = $this->inventory_model->get_searched_item_v4($item_vendor, $company_item_no, $this->session->userdata('user_location'));
        $response['item_unit_of_measures'] = $this->inventory_model->get_item_unit_measure_value_v2($response['item_details']['item_id']);

        echo json_encode($response);
    }

    public function inventory_item_details($inventory_item_id)
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        $data['inventory_item_details'] = $this->inventory_model->inventory_item_details_with_location($inventory_item_id, $this->session->userdata('user_location'));
        $data['item_unit_of_measures'] = $this->inventory_model->get_item_unit_measure_value_v2($data['inventory_item_details']['item_id']);
        $data['vendor_list'] = $this->inventory_model->get_vendor_list();
        $data['item_category_list'] = $this->inventory_model->get_item_category_list();
        $data['item_list'] = $this->inventory_model->get_items_through_company_item_no($data['item_details']['company_item_no']);
        $data['total_on_hand'] = 0;
        foreach ($data['item_list'] as $key => $value) {
            $order_req_id_array = array();
            $total_of_two = 0;
            $data['item_on_hand_batch1'] = 0;
            $data['item_on_hand_batch2'] = 0;

            $data['item_on_hand_list'] = $this->inventory_model->get_item_on_hand_list($value['item_id']);
            if (!empty($data['item_on_hand_list'])) {
                foreach ($data['item_on_hand_list'] as $inside_key => $inside_value) {
                    if (!in_array($inside_value['order_req_id'], $order_req_id_array)) {
                        $order_req_id_array[] = $inside_value['order_req_id'];
                    }
                    $data['item_on_hand_batch1'] += $inside_value['req_item_quantity_received'];
                }
            }

            $data['item_on_hand_batch2'] = $this->inventory_model->get_item_on_hand($value['item_id'], $order_req_id_array);
            $total_of_two = $data['item_on_hand_batch1'] + $data['item_on_hand_batch2'];
            $data['total_on_hand'] += $total_of_two;
        }

        $this->templating_library->set_view('pages/view_inventory_item_details', 'pages/view_inventory_item_details', $data);
        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function item_details($item_id)
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        $data['item_details'] = $this->inventory_model->get_item_details_with_item_location($item_id, $this->session->userdata('user_location'));
        $data['item_unit_of_measures'] = $this->inventory_model->get_item_unit_measure_value_v2($item_id);
        $data['vendor_list'] = $this->inventory_model->get_vendor_list();
        $data['item_category_list'] = $this->inventory_model->get_item_category_list();
        $data['item_list'] = $this->inventory_model->get_items_through_company_item_no_v3($data['item_details']['company_item_no']);

        $data['total_on_hand'] = 0;
        foreach ($data['item_list'] as $key => $value) {
            $order_req_id_array = array();
            $total_of_two = 0;
            $data['item_on_hand_batch1'] = 0;
            $data['item_on_hand_batch2'] = 0;

            $data['item_on_hand_list'] = $this->inventory_model->get_item_on_hand_list($value['item_id']);
            if (!empty($data['item_on_hand_list'])) {
                foreach ($data['item_on_hand_list'] as $inside_key => $inside_value) {
                    if (!in_array($inside_value['order_req_id'], $order_req_id_array)) {
                        $order_req_id_array[] = $inside_value['order_req_id'];
                    }
                    $data['item_on_hand_batch1'] += $inside_value['req_item_quantity_received'];
                }
            }

            $data['item_on_hand_batch2'] = $this->inventory_model->get_item_on_hand($value['item_id'], $order_req_id_array);
            $total_of_two = $data['item_on_hand_batch1'] + $data['item_on_hand_batch2'];
            $data['total_on_hand'] += $total_of_two;
        }

        $this->templating_library->set_view('pages/view_item_details', 'pages/view_item_details', $data);
        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function item_information_content($vendor_id, $company_item_no, $selected_item_reorder_no)
    {
        $data['item_reorder_nos'] = $this->inventory_model->get_item_reorder_nos_with_company_item_no_v2($vendor_id, $company_item_no);
        $data['company_item_no'] = $company_item_no;
        $data['selected_item_reorder_no'] = $selected_item_reorder_no;

        $data['item_details'] = $this->inventory_model->get_item_details_with_item_location($data['item_reorder_nos'][$data['selected_item_reorder_no']]['item_id'], $this->session->userdata('user_location'));
        $data['item_unit_of_measures'] = $this->inventory_model->get_item_unit_measure_value_v3($data['item_details']['item_id']);
        $data['vendor_list'] = $this->inventory_model->get_vendor_list();
        $data['item_category_list'] = $this->inventory_model->get_item_category_list();
        $data['item_list'] = $this->inventory_model->get_items_through_company_item_no_v3($data['item_details']['company_item_no']);

        $data['total_on_hand'] = 0;
        foreach ($data['item_list'] as $key => $value) {
            $order_req_id_array = array();
            $total_of_two = 0;
            $data['item_on_hand_batch1'] = 0;
            $data['item_on_hand_batch2'] = 0;

            $data['item_on_hand_list'] = $this->inventory_model->get_item_on_hand_list($value['item_id']);
            if (!empty($data['item_on_hand_list'])) {
                foreach ($data['item_on_hand_list'] as $inside_key => $inside_value) {
                    if (!in_array($inside_value['order_req_id'], $order_req_id_array)) {
                        $order_req_id_array[] = $inside_value['order_req_id'];
                    }
                    $data['item_on_hand_batch1'] += $inside_value['req_item_quantity_received'];
                }
            }

            $data['item_on_hand_batch2'] = $this->inventory_model->get_item_on_hand($value['item_id'], $order_req_id_array);
            $total_of_two = $data['item_on_hand_batch1'] + $data['item_on_hand_batch2'];
            $data['total_on_hand'] += $total_of_two;
        }
        $this->templating_library->set_view('pages/view_item_details_content', 'pages/view_item_details_content', $data);
    }

    public function item_information($vendor_id, $company_item_no)
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        $data['item_reorder_nos'] = $this->inventory_model->get_item_reorder_nos_with_company_item_no_v2($vendor_id, $company_item_no);
        $data['company_item_no'] = $company_item_no;
        $data['selected_item_reorder_no'] = 0;

        $data['item_group_list'] = $this->item_grouping_model->get_all_item_groups();
        $data['item_details'] = $this->inventory_model->get_item_details_with_item_location($data['item_reorder_nos'][$data['selected_item_reorder_no']]['item_id'], $this->session->userdata('user_location'));
        $data['item_unit_of_measures'] = $this->inventory_model->get_item_unit_measure_value_v3($data['item_details']['item_id']);
        $data['vendor_list'] = $this->inventory_model->get_vendor_list();
        $data['item_category_list'] = $this->inventory_model->get_item_category_list();
        $data['item_list'] = $this->inventory_model->get_items_through_company_item_no_v3($data['item_details']['company_item_no']);

        $data['total_on_hand'] = 0;
        foreach ($data['item_list'] as $key => $value) {
            $order_req_id_array = array();
            $total_of_two = 0;
            $data['item_on_hand_batch1'] = 0;
            $data['item_on_hand_batch2'] = 0;

            $data['item_on_hand_list'] = $this->inventory_model->get_item_on_hand_list($value['item_id']);
            if (!empty($data['item_on_hand_list'])) {
                foreach ($data['item_on_hand_list'] as $inside_key => $inside_value) {
                    if (!in_array($inside_value['order_req_id'], $order_req_id_array)) {
                        $order_req_id_array[] = $inside_value['order_req_id'];
                    }
                    $data['item_on_hand_batch1'] += $inside_value['req_item_quantity_received'];
                }
            }

            $data['item_on_hand_batch2'] = $this->inventory_model->get_item_on_hand($value['item_id'], $order_req_id_array);
            $total_of_two = $data['item_on_hand_batch1'] + $data['item_on_hand_batch2'];
            $data['total_on_hand'] += $total_of_two;
        }

        $this->templating_library->set_view('pages/view_item_details', 'pages/view_item_details', $data);
        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function cancelled_order_req()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['order_inquiries'] = $this->inventory_model->get_cancelled_order_req_v2($this->session->userdata('user_location'));

            $this->templating_library->set_view('pages/canceled_order_req', 'pages/canceled_order_req', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function draft_orders()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['order_inquiries'] = $this->inventory_model->get_order_inquiries_v3($this->session->userdata('user_location'), 1);
            $data['order_inquiries_payment'] = $this->inventory_model->get_order_inquiries_payment_v3($this->session->userdata('user_location'));

            $data['open_balance'] = 0;
            if (!empty($data['order_inquiries_payment'])) {
                $req_payment_batch_no = 0;
                foreach ($data['order_inquiries_payment'] as $key => $value) {
                    if ($value['req_payment_batch_no'] != $req_payment_batch_no) {
                        if ($value['payment_date'] == '0000-00-00') {
                            if (!in_array($value['purchase_order_no'], $purchase_order_no)) {
                                $data['open_balance'] += $value['ending_balance'];
                            }
                        }
                    }
                }
            }

            $this->templating_library->set_view('pages/draft_order_inventory', 'pages/draft_order_inventory', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function view_req_payment_info($purchase_order_no, $order_req_id, $req_payment_batch_no)
    {
        $data = array();
        $data['order_req_payment_details'] = $this->inventory_model->get_order_req_payment_details($purchase_order_no, $order_req_id, $req_payment_batch_no);
        $data['order_req_payment_list'] = $this->inventory_model->get_order_inquiries_payment_specific_order($purchase_order_no, $order_req_id);

        $this->templating_library->set('title', 'Purchase Order Requisition Payment');
        $this->templating_library->set_view('pages/purchase_order_payment_details', 'pages/purchase_order_payment_details', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
    }

    public function purchase_order_look_up()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['equipment_transfer_receiving'] = $this->inventory_model->get_equipment_transfer_receiving_v3($this->session->userdata('user_location'), 'received');
            $data['order_inquiries'] = $this->inventory_model->get_received_order_inquiries_v2($this->session->userdata('user_location'));
            $data['order_inquiries_payment'] = $this->inventory_model->get_order_inquiries_payment_v3($this->session->userdata('user_location'));

            $data['open_balance'] = 0;
            if (!empty($data['order_inquiries_payment'])) {
                $req_payment_batch_no = 0;
                foreach ($data['order_inquiries_payment'] as $key => $value) {
                    if ($value['req_payment_batch_no'] != $req_payment_batch_no) {
                        if ($value['payment_date'] == '0000-00-00') {
                            if (!in_array($value['purchase_order_no'], $purchase_order_no)) {
                                $data['open_balance'] += $value['ending_balance'];
                            }
                        }
                    }
                }
            }
            $this->templating_library->set_view('pages/purchase_order_look_up', 'pages/purchase_order_look_up', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function purchase_order_lookup_data()
    {
        $response_data = array(
            'data' => array(),
            'draw' => 1,
            'recordsFiltered' => 0,
            'recordsTotal' => 0,
        );

        if ($this->input->is_ajax_request()) {
            $datatable = $this->input->get();
            $start = $datatable['start'];
            $limit = $datatable['length'];

            $filters = array(
                'search_item_fields_purchase_order_lookup_v2' => $datatable['search']['value']
            );
            $column = array(
                'order_by_req_received_date',
                'order_by_po_order_date',
                'order_by_po_no',
                'order_by_name',
                'order_by_order_req_confirmation_no',
            );
            $filters[$column[$datatable['order'][0]['column']]] = $datatable['order'][0]['dir'];
            $result = $this->inventory_model->get_purchase_order_look_up($filters, $this->session->userdata('user_location'), $start, $limit);

            if ($result['totalCount'] > 0) {
                foreach ($result['result'] as $key => $value) {
                    if ($value['req_received_date'] == '0000-00-00') {
                        $value['received_date_col'] = '<span '.
                                                            'class="view_req_receiving_info"'.
                                                            'data-req-receive-batch-no="'.$value['req_receiving_batch_no'].'"'.
                                                            'data-purchase-order-no="'.$value['po_no'].'"'.
                                                            'data-order-req-id="'.$value['req_id'].'">'.
                                                        '</span>';
                    } else {
                        $value['received_date_col'] = '<span '.
                                                            'class="view_req_receiving_info"'.
                                                            'data-req-receive-batch-no="'.$value['req_receiving_batch_no'].'"'.
                                                            'data-purchase-order-no="'.$value['po_no'].'"'.
                                                            'data-order-req-id="'.$value['req_id'].'">'.
                                                                ''.date('m/d/Y', strtotime($value['req_received_date'])).''.
                                                        '</span>';
                    }

                    $value['order_date_col'] = date('m/d/Y', strtotime($value['order_date']));
                    $value['po_number'] = '<span '.
                                                'class="view_purchase_order"'.
                                                'data-req-receive-batch-no="'.$value['req_receiving_batch_no'].'"'.
                                                'data-purchase-order-no="'.$value['po_no'].'"'.
                                                'data-order-req-id="'.$value['req_id'].'">'.
                                                    ''.substr($value['po_no'], 3, 10).''.
                                            '</span>';

                    $value['vendor_name_col'] = strtoupper($value['name']);
                    $value['confirmation_no_col'] = $value['order_req_confirmation_no'];

                    $response_data['data'][] = $value;
                }
            }

            $response_data['draw'] = $datatable['draw'];
            $response_data['recordsFiltered'] = $result['totalCount'];
            $response_data['recordsTotal'] = $result['totalCount'];
        }
        echo json_encode($response_data);
    }

    public function purchase_order_inquiry()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['equipment_transfer_receiving'] = $this->inventory_model->get_equipment_transfer_receiving_v3($this->session->userdata('user_location'), 'pending');
            $data['order_inquiries'] = $this->inventory_model->get_order_inquiries_v3($this->session->userdata('user_location'), 0);
            $data['order_inquiries_payment'] = $this->inventory_model->get_order_inquiries_payment_v3($this->session->userdata('user_location'));
    
            $data['open_balance'] = 0;
            if (!empty($data['order_inquiries_payment'])) {
                $req_payment_batch_no = 0;
                foreach ($data['order_inquiries_payment'] as $key => $value) {
                    if ($value['req_payment_batch_no'] != $req_payment_batch_no) {
                        if ($value['payment_date'] == '0000-00-00') {
                            if (!in_array($value['purchase_order_no'], $purchase_order_no)) {
                                $data['open_balance'] += $value['ending_balance'];
                            }
                        }
                    }
                }
            }
            $this->templating_library->set_view('pages/purchase_order_inquiry', 'pages/purchase_order_inquiry', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function show_all_bills()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['order_inquiries'] = $this->inventory_model->get_order_inquiries_bills_v2($this->session->userdata('user_location'));
            $data['order_inquiries_payment'] = $data['order_inquiries'];

            $data['open_balance'] = 0;
            if (!empty($data['order_inquiries_payment'])) {
                $req_payment_batch_no = 0;
                foreach ($data['order_inquiries_payment'] as $key => $value) {
                    if ($value['req_payment_batch_no'] != $req_payment_batch_no) {
                        if ($value['payment_date'] == '0000-00-00') {
                            if (!in_array($value['purchase_order_no'], $purchase_order_no)) {
                                $data['open_balance'] += $value['ending_balance'];
                            }
                        }
                    }
                }
            }

            $this->templating_library->set_view('pages/show_all_bills', 'pages/show_all_bills', $data);
        }
        
        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function show_all_payments()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['order_inquiries'] = $this->inventory_model->get_order_inquiries_payment_done_v2($this->session->userdata('user_location'));
            $data['order_inquiries_payment'] = $this->inventory_model->get_order_inquiries_payment_v3($this->session->userdata('user_location'));

            $data['open_balance'] = 0;
            if (!empty($data['order_inquiries_payment'])) {
                $req_payment_batch_no = 0;
                foreach ($data['order_inquiries_payment'] as $key => $value) {
                    if ($value['req_payment_batch_no'] != $req_payment_batch_no) {
                        if ($value['payment_date'] == '0000-00-00') {
                            if (!in_array($value['purchase_order_no'], $purchase_order_no)) {
                                $data['open_balance'] += $value['ending_balance'];
                            }
                        }
                    }
                }
            }

            $this->templating_library->set_view('pages/show_all_payments', 'pages/show_all_payments', $data);
        }
        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function remove_inventory_item_reason($inventory_item_id)
    {
        $data['inventory_item_id'] = $inventory_item_id;
        $this->templating_library->set('title', 'Reason for removing the item');
        $this->templating_library->set_view('pages/remove_inventory_item_reason', 'pages/remove_inventory_item_reason', $data);
    }

    public function remove_inventory_item_reorder_no($inventory_item_id)
    {
        $response = array('data' => array());
        $response = $this->inventory_model->delete_item_reorder_no($inventory_item_id);
        echo json_encode($response);
    }

    public function edit_inventory_item($inventory_item_id)
    {
        $data['inventory_item_id'] = $inventory_item_id;
        $data['item_serial_asset_no'] = $this->inventory_model->get_item_serial_asset_no($inventory_item_id);
        $this->templating_library->set('title', 'Edit Inventory Item');
        $this->templating_library->set_view('pages/edit_inventory_item', 'pages/edit_inventory_item', $data);
    }

    public function submit_edit_inventory_item($inventory_item_id)
    {
        if ($this->input->post()) {
            $data_post = $this->input->post();

            $data_tobe_inserted = array(
                'item_serial_no' => $data_post['serial_no'],
                'item_asset_no' => $data_post['asset_no'],
            );
            $return = $this->inventory_model->submit_edit_inventory_item($data_tobe_inserted, $inventory_item_id);

            if ($return) {
                $this->response_code = 0;
                $this->response_message = 'Item serial no. and asset no. successfully changed.';
            } else {
                $this->response_code = 1;
                $this->response_message = 'Error!';
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));

            exit;
        }
    }

    public function submit_inventory_item_removal($inventory_item_id)
    {
        if ($this->input->post()) {
            $data_post = $this->input->post();

            $data_inventory_item = array(
                'inventory_item_status' => 1,
            );
            $this->inventory_model->change_inventory_item_status($inventory_item_id, $data_inventory_item);

            $data_tobe_inserted = array(
                'inventory_item_id' => $inventory_item_id,
                'removal_reason' => $data_post['inventory_item_removal_reason'],
                'date_out_of_service' => date('Y-m-d'),
            );
            $return = $this->inventory_model->submit_inventory_item_removal($data_tobe_inserted);

            if ($return) {
                $this->response_code = 0;
                $this->response_message = 'Item successfully removed.';
            } else {
                $this->response_code = 1;
                $this->response_message = 'Error!';
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));

            exit;
        }
    }

    public function put_item_to_inventory($inventory_item_id)
    {
        $data_inventory_item = array(
            'inventory_item_status' => 0,
        );
        $this->inventory_model->change_inventory_item_status($inventory_item_id, $data_inventory_item);
        $this->inventory_model->put_item_to_inventory($inventory_item_id);

        $this->response_code = 0;
        $this->response_message = 'Item successfully put to inventory.';

        echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));

        exit;
    }

    public function purchase_item()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['all_inventory_item'] = $this->inventory_model->get_inventory_item_list_no_filter_v3($this->session->userdata('user_location'), date('Y-m-01'), date('Y-m-d'));
            $this->templating_library->set_view('pages/purchase_item_report', 'pages/purchase_item_report', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function purchase_item_graph()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['all_inventory_item'] = $this->inventory_model->get_inventory_item_list_no_filter_v3($this->session->userdata('user_location'), date('Y-m-01'), date('Y-m-d'));
            $data['vendor_list'] = $this->inventory_model->get_vendor_list();

            $this->templating_library->set_view('pages/purchase_item_graph_report', 'pages/purchase_item_graph_report', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function item_reconciliation()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['removed_item_list'] = $this->inventory_model->get_removed_item_list_v3($this->session->userdata('user_location'));
            $this->templating_library->set_view('pages/item_reconciliation_report', 'pages/item_reconciliation_report', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function vendor_cost()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['vendor_list'] = $this->inventory_model->get_vendor_list();
            $data['order_requisition_list'] = $this->inventory_model->get_order_requisition_list_v2($this->session->userdata('user_location'));
            $this->templating_library->set_view('pages/vendor_cost_report', 'pages/vendor_cost_report', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function filter_purchase_item($item_id, $from_date, $to_date)
    {
        $response = array('data' => array());
        if ($item_id == 'all_item') {
            $item_id = '';
        } else {
            $response['item_details'] = $this->inventory_model->get_searched_item_details($item_id);
        }
        if ($from_date == 0 && $to_date == 0) {
            $from_date = '';
            $to_date = '';
        }

        $response['all_inventory_item'] = $this->inventory_model->filter_purchase_item_v2($item_id, $from_date, $to_date, $this->session->userdata('user_location'));

        echo json_encode($response);
    }

    public function filter_vendor_cost($vendor_id, $from_date, $to_date)
    {
        $response = array('data' => array());
        if ($vendor_id == 'all_vendors') {
            $vendor_id = '';
            $response['vendor_details'] = '';
        } else {
            $response['vendor_details'] = $this->inventory_model->get_searched_vendor_name($vendor_id);
        }
        if ($from_date == 0 && $to_date == 0) {
            $from_date = '';
            $to_date = '';
        }

        $response['order_requisition_list'] = $this->inventory_model->get_order_requisition_list_filtered_v2($vendor_id, $from_date, $to_date, $this->session->userdata('user_location'));

        echo json_encode($response);
    }

    public function filter_item_reconciliation($reason, $from_date, $to_date)
    {
        $response = array('data' => array());

        if ($reason == 'all_item') {
            $reason = '';
        }

        if ($from_date == 0 && $to_date == 0) {
            $from_date = '';
            $to_date = '';
        }
        $response['removed_item_list'] = $this->inventory_model->get_removed_item_list_filtered_v2($reason, $from_date, $to_date, $this->session->userdata('user_location'));

        echo json_encode($response);
    }

    public function purchase_order_requisition_receiving($purchase_order_no, $order_req_id, $req_receive_batch_no)
    {
        $data = array();
        $data['order_req_details'] = $this->inventory_model->get_order_req_details_v1($purchase_order_no, $order_req_id, $req_receive_batch_no);
        $data['order_req_items'] = $this->inventory_model->get_order_req_items_v3($data['order_req_details']['order_req_id'], $req_receive_batch_no);

        // $data['order_req_items'] = $this->inventory_model->get_order_req_items($data['order_req_details']['order_req_id'], $req_receive_batch_no);
        $data['vendor_list'] = $this->inventory_model->get_vendor_list();
        $data['order_receiving_list'] = $this->inventory_model->get_order_req_receiving_list($purchase_order_no, $order_req_id);

        $this->templating_library->set('title', 'Purchase Order Requisition Receiving');
        $this->templating_library->set_view('pages/purchase_order_requisition_receiving', 'pages/purchase_order_requisition_receiving', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
    }

    public function pay_order_requisition($purchase_order_no, $order_req_id, $req_payment_batch_no)
    {
        $data = array();
        $data['order_req_payment_details'] = $this->inventory_model->get_order_req_payment_details($purchase_order_no, $order_req_id, $req_payment_batch_no);

        $this->templating_library->set('title', 'Purchase Order Requisition Payment');
        $this->templating_library->set_view('pages/purchase_order_requisition_payment', 'pages/purchase_order_requisition_payment', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
    }

    public function add_serial_asset_no($purchase_order_no, $item_id, $qty_ordered)
    {
        $response = array('data' => array());

        $response['item_quantity_ordered'] = $this->inventory_model->get_item_quantity_ordered($purchase_order_no, $item_id, $qty_ordered);

        echo json_encode($response);
    }

    public function add_serial_asset_no_v2($purchase_order_no, $item_id, $qty_ordered, $item_unit_measure)
    {
        $response = array('data' => array());

        $item_unit_measure_value = $this->inventory_model->get_item_unit_measure_value($item_unit_measure, $item_id);
        if (!empty($item_unit_measure_value)) {
            $qty_ordered *= $item_unit_measure_value['item_unit_value'];
        }

        // $response['item_quantity_ordered'] = $this->inventory_model->get_item_quantity_ordered($purchase_order_no, $item_id, $qty_ordered);
        $response['item_quantity_ordered'] = $qty_ordered;

        echo json_encode($response);
    }

    public function change_order_req_status($status_value, $purchase_order_no, $order_req_id)
    {
        $response = array('data' => array());

        $data = array(
            'order_req_status' => $status_value,
        );

        $response = $this->inventory_model->change_order_req_status($data, $purchase_order_no, $order_req_id);

        echo json_encode($response);
    }

    public function revert_draft_order_inventory($order_req_id)
    {
        $response = array('data' => array());

        $data = array(
            'draft_order' => 0,
        );

        $response = $this->inventory_model->revert_draft_order_inventory($order_req_id, $data);

        echo json_encode($response);
    }

    public function delete_draft_order_inventory($order_req_id)
    {
        $response = array('data' => array());

        $response = $this->inventory_model->delete_draft_order_inventory($order_req_id);

        echo json_encode($response);
    }

    public function confirm_order_req_payment($purchase_order_no, $order_req_id, $req_payment_batch_no)
    {
        if ($this->input->post()) {
            $data_post = $this->input->post();

            $this->form_validation->set_rules('order_req_payment_date', 'Date', 'required');
            $this->form_validation->set_rules('method', 'Method', 'required');
            $this->form_validation->set_rules('check_no', 'Check No.', 'required');
            $this->form_validation->set_rules('amount_paid', 'Amount Paid', 'required');
            $this->form_validation->set_rules('credit_used', 'Credit Used', 'required');
            $this->form_validation->set_rules('ending_balance', 'Ending Balance', 'required');

            if ($this->form_validation->run() === true) {
                $payment_status = 'paid-po';
                if ($data_post['ending_balance'] != 0) {
                    $payment_status = 'partial-paid-po';
                    $date_added = strtotime(date('Y-m-d H:i:s'));
                    $new_req_payment_batch_no = '000'.substr($date_added, 4, 10);
                    $new_payment_information = array(
                        'order_req_id' => $order_req_id,
                        'purchase_order_no' => $purchase_order_no,
                        'req_payment_batch_no' => $new_req_payment_batch_no,
                        'ending_balance' => $data_post['ending_balance'],
                        'req_payment_status' => $payment_status,
                        'payment_method' => ''
                    );
                    $this->inventory_model->new_order_req_payment($new_payment_information);
                } else {
                    $data_here = array(
                        'req_payment_status_sign' => 1
                    );
                    $this->inventory_model->update_req_status_sign($purchase_order_no, $order_req_id, $data_here);
                }

                $credit_value = filter_var($data_post['old_vendor_credit'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $credit_used_value = filter_var($data_post['credit_used'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $ending_balance_value = filter_var($data_post['ending_balance'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $payment_amount_value = filter_var($data_post['amount_paid'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                $payment_information = array(
                    'payment_date' => date('Y-m-d', strtotime($data_post['order_req_payment_date'])),
                    'payment_method' => $data_post['method'],
                    'check_no' => $data_post['check_no'],
                    'payment_amount' => $payment_amount_value,
                    'credit' => $credit_value,
                    'credit_used' => $credit_used_value,
                    'ending_balance' => $ending_balance_value,
                    'req_payment_status' => $payment_status
                );
                $this->inventory_model->confirm_order_req_payment($purchase_order_no, $order_req_id, $req_payment_batch_no, $payment_information);

                $data_update_vendor = array(
                    'vendor_credit' => $data_post['vendor_credit'],
                );
                $this->inventory_model->update_vendor_information($data_update_vendor, $data_post['vendor_id']);

                $this->response_code = 0;
                $this->response_message = 'Payment Confirmed Successfully.';
            } else {
                $this->response_message = validation_errors('<span></span>');
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));

            exit;
        }
    }

    public function cancel_inventory_item($purchase_order_no, $item_id)
    {
        $response = array('data' => array());

        $data = array(
            'item_status' => 1,
        );

        $response = $this->inventory_model->update_inventory_item_status($purchase_order_no, $item_id, $data);

        echo json_encode($response);
    }

    public function retrieve_inventory_item($purchase_order_no, $item_id)
    {
        $response = array('data' => array());

        $data = array(
            'item_status' => 0,
        );

        $response = $this->inventory_model->update_inventory_item_status($purchase_order_no, $item_id, $data);

        echo json_encode($response);
    }

    public function order_req_receiving_details($purchase_order_no, $order_req_id, $req_receiving_batch_no)
    {
        $data = array();
        $data['order_req_details'] = $this->inventory_model->get_order_req_details_v1($purchase_order_no, $order_req_id, $req_receiving_batch_no);
        $data['order_req_items'] = $this->inventory_model->get_order_req_items_v4($data['order_req_details']['order_req_id'], $req_receiving_batch_no);

        // $data['order_req_items'] = $this->inventory_model->get_order_req_items($data['order_req_details']['order_req_id'], $req_receiving_batch_no);
        $this->templating_library->set('title', 'Purchase Order Requisition Receiving');
        $this->templating_library->set_view('pages/purchase_order_receiving_details', 'pages/purchase_order_receiving_details', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
    }

    public function get_received_item_serials_assets($item_id, $po_number, $order_req_id, $item_batch_no)
    {
        $response = array('data' => array());

        $response['item_serial_asset_nos'] = $this->inventory_model->get_item_serial_asset_nos($item_id, $po_number, $order_req_id, $item_batch_no);

        echo json_encode($response);
    }

    public function order_req_receiving_details_v2($purchase_order_no, $order_req_id)
    {
        $data = array();
        $data['order_req_details'] = $this->inventory_model->get_order_req_details($purchase_order_no, $order_req_id);
        $data['order_req_items'] = $this->inventory_model->get_order_req_items_v2($data['order_req_details']['order_req_id']);

        $this->templating_library->set('title', 'Purchase Order Requisition Receiving');
        $this->templating_library->set_view('pages/purchase_order_details', 'pages/purchase_order_details', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
    }

    public function inventory_item_list()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['inventory_item_list'] = $this->inventory_model->get_inventory_item_list_no_filter_v4($this->session->userdata('user_location'));

            $this->templating_library->set_view('pages/inventory_item_list', 'pages/inventory_item_list', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function run_item_no()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');


        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['inventory_item_list'] = $this->inventory_model->get_inventory_list($this->session->userdata('user_location'));

            $this->templating_library->set_view('pages/inventory_run_item_no', 'pages/inventory_run_item_no', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function get_inventory_item_list()
    {
        $response_data = array(
            'data' => array(),
            'draw' => 1,
            'recordsFiltered' => 0,
            'recordsTotal' => 0,
        );

        if ($this->input->is_ajax_request()) {
            $datatable = $this->input->get();
            $start = $datatable['start'];
            $limit = $datatable['length'];
            $filters = array(
                'search_item_fields' => $datatable['search']['value'],
            );

            $column = array(
                'order_by_item_no',
                'order_by_item_description',
                'order_by_vendor_name',
                'order_by_item_serial_no',
                'order_by_item_asset_no',
                'order_by_item_warehouse_location',
                'order_by_item_status_location',
            );
            $filters[$column[$datatable['order'][0]['column']]] = $datatable['order'][0]['dir'];
            $result = $this->inventory_model->get_inventory_item_list_v5($filters, $this->session->userdata('user_location'), $start, $limit);

            if ($result['totalCount'] > 0) {
                foreach ($result['result'] as $key => $value) {
                    $editableclick = '';
                    if ($this->session->userdata('account_type') != 'distribution_supervisor') {
                        $editableclick = 'editable-click';
                    }
                    $value['item_serial_no'] = '<a href="javascript:;"'.
                                                    'id="item_serial_no"'.
                                                    'data-pk="'.$value['inventory_item_id'].'"'.
                                                    'data-url="'.base_url('inventory/update_basic').'"'.
                                                    'data-title="Item serial no"'.
                                                    'data-value="'.$value['item_serial_no'].'"'.
                                                    'data-type="text"'.
                                                    'class="'.$editableclick.' editable-itemlist">'.
                                                        ''.$value['item_serial_no'].''.
                                                '</a>';
                    $value['item_asset_no'] = '<a href="javascript:;"'.
                                                    'id="item_asset_no"'.
                                                    'data-pk="'.$value['inventory_item_id'].'"'.
                                                    'data-url="'.base_url('inventory/update_basic').'"'.
                                                    'data-title="Item asset no"'.
                                                    'data-value="'.$value['item_asset_no'].'"'.
                                                    'data-type="text"'.
                                                    'class="'.$editableclick.' editable-itemlist">'.
                                                        ''.$value['item_asset_no'].''.
                                                '</a>';

                    if ($value['item_status_location'] == 'on_hand') {
                        $value['item_status_location'] = 'On Hand';
                    } else {
                        $value['item_status_location'] = 'Active';
                    }

                    if ($this->session->userdata('account_type') != 'distribution_supervisor') {
                        $value['delete_button'] = '<button type="button" data-inventory-item-id="'.$value['inventory_item_id'].'" class="btn btn-xs btn-danger remove_inventory_item"> <i class="fa fa-trash-o"></i> Delete </button>';
                    }
                    

                    $response_data['data'][] = $value;
                }
            }

            $response_data['draw'] = $datatable['draw'];
            $response_data['recordsFiltered'] = $result['totalCount'];
            $response_data['recordsTotal'] = $result['totalCount'];
        }
        echo json_encode($response_data);
    }

    public function get_inventory_run_item_list()
    {
        $response_data = array(
            'data' => array(),
            'draw' => 1,
            'recordsFiltered' => 0,
            'recordsTotal' => 0,
        );

        if ($this->input->is_ajax_request()) {
            $datatable = $this->input->get();
            $start = $datatable['start'];
            $limit = $datatable['length'];
            $filters = array(
                'search_item_fields_run_item_no' => $datatable['search']['value'],
            );

            $column = array(
                'order_by_item_no',
                'order_by_item_description',
                'order_by_vendor_name',
                'order_by_item_warehouse_location',
                'order_by_item_category_name',
            );
            $filters[$column[$datatable['order'][0]['column']]] = $datatable['order'][0]['dir'];
            $result = $this->inventory_model->get_inventory_run_item_list($filters, $this->session->userdata('user_location'), $start, $limit);

            $response_data['data'] = $result['result'];
            $response_data['draw'] = $datatable['draw'];
            $response_data['recordsFiltered'] = $result['totalCount'];
            $response_data['recordsTotal'] = $result['totalCount'];
        }
        echo json_encode($response_data);
    }

    public function show_removed_items()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {

            $data['removed_item_list'] = $this->inventory_model->get_removed_item_list_v3($this->session->userdata('user_location'));

            $this->templating_library->set_view('pages/removed_item_list', 'pages/removed_item_list', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function purchase_order_requisition_details($purchase_order_no, $order_req_id)
    {
        $data = array();

        //Added by Adrian for printing
        $data['purchase_order_no'] = $purchase_order_no;
        $data['order_req_id'] = $order_req_id;
        //----------------------------

        $data['order_req_details'] = $this->inventory_model->get_order_req_details($purchase_order_no, $order_req_id);
        $req_receiving_first_batch = $this->inventory_model->get_req_receiving_first_batch_no($purchase_order_no, $order_req_id);
        $data['order_req_items'] = $this->inventory_model->get_req_receiving_first_batch($purchase_order_no, $order_req_id, $req_receiving_first_batch['req_receiving_batch_no']);
        $this->templating_library->set('title', 'Purchase Order Requisition Receiving');
        $this->templating_library->set_view('pages/purchase_order_details', 'pages/purchase_order_details', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
    }

    public function purchase_order_requisition()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        $data['vendor_list'] = $this->inventory_model->get_vendor_list();

        $this->templating_library->set_view('pages/purchase_order_requisition', 'pages/purchase_order_requisition', $data);
        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function change_vendor_activation($sign, $vendor_id)
    {
        $data = array(
            'vendor_active_sign' => $sign,
        );
        $this->inventory_model->change_vendor_activation($data, $vendor_id);
    }

    public function change_item_activation($sign, $item_id)
    {
        // ADD TO HOSPICE LIST 07/15/2021 ----- START
        $item_details = $this->inventory_model->get_item_details($item_id);
        if (!empty($item_details)) {
            $equipment_details = $this->inventory_model->get_equipment_details($item_details['company_item_no']);

            if ($sign == 1) {
                $data_new = array(
                    'is_deactivated' => 0,
                );
            } else {
                $data_new = array(
                    'is_deactivated' => 1,
                );
            }
            
            // $this->inventory_model->update_equipment_company_item_no($data_new, $equipment_details['equipmentID']);
            $this->inventory_model->update_equipment_company_item_no_v2($data_new, $equipment_details['equipment_company_item_no']);
        }
        // ADD TO HOSPICE LIST 07/15/2021 ----- END

        $data = array(
            'item_active_sign' => $sign,
        );
        $this->inventory_model->change_item_activation($data, $item_id);
    }

    public function confirm_order_requisition($purchase_order_no, $order_req_id)
    {
        if ($this->input->post()) {
            $data_post = $this->input->post();

            $this->form_validation->set_rules('order_req_date', 'Date', 'required');
            $this->form_validation->set_rules('location', 'Location', 'required');
            $this->form_validation->set_rules('vendor_rep_taking_order', 'Vendor Rep. Taking Order', 'required');
            $this->form_validation->set_rules('order_req_confirmation_no', 'Confirmation No.', 'required');
            $this->form_validation->set_rules('person_placing_order', 'Person Placing Order', 'required');
            $this->form_validation->set_rules('order_req_received_date', 'Received Date', 'required');

            if ($this->form_validation->run() === true) {
                $order_req_status = '';
                $item_total_received = 0;
                $item_total_ordered = 0;
                $data_order_receiving = array();
                $item_id_list = array();
                $original_item_quantity_ordered = 0;

                $receiving_datails_list = $this->inventory_model->get_receiving_datails_list($purchase_order_no, $order_req_id);

                foreach ($receiving_datails_list as $key => $value) {
                    if ($value['item_status'] == 0) {
                        $item_total_received += $value['req_item_quantity_received'];

                        if (!in_array($value['item_id'], $item_id_list)) {
                            $original_item_quantity_ordered += $value['req_item_quantity_ordered'];
                            $item_id_list[] = $value['item_id'];
                        }
                    }
                }

                foreach ($data_post['order_inquiry'] as $key => $value) {
                    if ($value['item_status'] == 0) {
                        $item_total_ordered += $value['item_quantity'];
                        $item_total_received += $value['item_quantity_received'];
                    }
                }

                if ($item_total_ordered == $item_total_received) {
                    $order_req_status = 'received';

                    foreach ($data_post['order_inquiry'] as $key => $value) {
                        $item_quantity_received = 0;
                        if (!empty($value['item_quantity_received'])) {
                            $item_quantity_received = $value['item_quantity_received'];
                        }
                        $data_order_receiving_row = array(
                            'req_receiving_id' => $value['req_receiving_id'],
                            'req_item_quantity_received' => $item_quantity_received,
                            'req_receiving_status' => $order_req_status,
                            'req_received_date' => $data_post['order_req_received_date'],
                            'req_staff_member_receiving' => $data_post['order_req_staff_member_receiving']
                        );
                        $data_order_receiving[] = $data_order_receiving_row;
                    }
                } elseif ($item_total_ordered > $item_total_received) {
                    $order_req_status = 'pending';

                    $data_new_order_receiving = array();
                    $temp_item_batch_no = strtotime(date('Y-m-d H:i:s'));
                    $req_receiving_batch_no = substr($temp_item_batch_no, 4, 10).'000';
                    foreach ($data_post['order_inquiry'] as $key => $value) {
                        $item_quantity_received = 0;
                        if (!empty($value['item_quantity_received'])) {
                            $item_quantity_received = $value['item_quantity_received'];
                        }
                        $data_order_receiving_row = array(
                            'req_receiving_id' => $value['req_receiving_id'],
                            'req_item_quantity_received' => $item_quantity_received,
                            'req_receiving_status' => 'received-partial',
                            'req_received_date' => $data_post['order_req_received_date'],
                            'req_staff_member_receiving' => $data_post['order_req_staff_member_receiving']
                        );
                        $data_order_receiving[] = $data_order_receiving_row;

                        $item_cost_value = filter_var($value['item_cost'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $item_total_cost_value = filter_var($value['item_total_cost'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_new_order_receiving_row = array(
                            'order_req_id' => $order_req_id,
                            'purchase_order_no' => $purchase_order_no,
                            'req_receiving_batch_no' => $req_receiving_batch_no,
                            'item_id' => $key,
                            'req_item_quantity_ordered' => $value['item_quantity'],
                            'req_receiving_status' => '',
                            'item_batch_no' => $value['item_batch_no'],
                            'item_unit_measure' => $value['item_unit_measure'],
                            'item_cost' => $item_cost_value,
                            'item_total_cost' => $item_total_cost_value
                        );
                        $data_new_order_receiving[] = $data_new_order_receiving_row;
                    }
                    $this->inventory_model->insert_new_order_req_receiving($data_new_order_receiving);
                }
                $this->inventory_model->update_order_req_receiving($data_order_receiving);

                if ($original_item_quantity_ordered != $item_total_ordered) {
                    $inventory_item_list = $this->inventory_model->get_inventory_item_list($order_req_id, $purchase_order_no);
                    foreach ($data_post['order_inquiry'] as $key => $value) {

                        $item_total_cost_value2 = filter_var($value['item_total_cost'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $data_new_item_quantity_ordered_iventory_items = array(
                            'item_quantity' => $value['item_quantity'],
                            'item_total_cost' => $item_total_cost_value2
                        );
                        $this->inventory_model->update_inventory_item($order_req_id, $purchase_order_no, $key, $data_new_item_quantity_ordered_iventory_items);

                        $data_new_item_quantity_ordered_receiving = array(
                            'req_item_quantity_ordered' => $value['item_quantity'],
                        );
                        $this->inventory_model->update_order_req_receiving_quantity_ordered($order_req_id, $purchase_order_no, $key, $data_new_item_quantity_ordered_receiving);

                        $order_req_payment_list = $this->inventory_model->get_order_req_payment_list($order_req_id, $purchase_order_no);

                        $loop_count = 0;
                        $data_new_order_req_payment_list = array();
                        foreach ($order_req_payment_list as $inside_key => $inside_value) {
                            if ($loop_count == 0) {
                                $current_ending_balance = $data_post['order_req_grand_total'] - $inside_value['payment_amount'];
                                ++$loop_count;
                            } else {
                                $current_ending_balance = $current_ending_balance - $inside_value['payment_amount'];
                                ++$loop_count;
                            }
                            $new_order_req_payment = array(
                                'req_payment_id' => $inside_value['req_payment_id'],
                                'ending_balance' => $current_ending_balance
                            );
                            $data_new_order_req_payment_list[] = $new_order_req_payment;
                        }
                        $this->inventory_model->update_order_req_payment_list_batch($data_new_order_req_payment_list);
                    }
                }

                $order_req_amount = filter_var($data_post['order_req_amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $order_req_shipping_cost = filter_var($data_post['order_req_shipping_cost'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $order_req_grand_total = filter_var($data_post['order_req_grand_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $new_order_req_details = array(
                    'order_req_date' => date('Y-m-d', strtotime($data_post['order_req_date'])),
                    'location' => $this->session->userdata('user_location'),
                    'vendor_rep_taking_order' => $data_post['vendor_rep_taking_order'],
                    'order_req_confirmation_no' => $data_post['order_req_confirmation_no'],
                    'person_placing_order' => $data_post['person_placing_order'],
                    'order_req_amount' => $order_req_amount,
                    'order_req_shipping_cost' => $order_req_shipping_cost,
                    'order_req_grand_total' => $order_req_grand_total,
                    'order_req_status' => $order_req_status
                );
                $response = $this->inventory_model->confirm_order_req($new_order_req_details, $order_req_id);

                if ($response == true) {
                    $this->response_code = 0;
                    $this->response_message = 'Order Requisition Confirmed Successfully.';
                } else {
                    $this->response_code = 1;
                    $this->response_message = 'Error!';
                }
            } else {
                $this->response_message = validation_errors('<span></span>');
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));
            exit;
        }
    }

    public function edit_inventory_item_information()
    {
        if ($this->input->post()) {
            $data_post = $this->input->post();

            $this->form_validation->set_rules('company_item_no', 'Company Item No.', 'required');
            $this->form_validation->set_rules('item_description', 'Item Description', 'required');
            $this->form_validation->set_rules('item_vendor_acct_no', 'Account No.', 'required');
            $this->form_validation->set_rules('item_reorder_no', 'Reorder No.', 'required');
            $this->form_validation->set_rules('item_warehouse_location', 'Warehouse Location', 'required');
            $this->form_validation->set_rules('item_serial_no', 'Serial No.', 'required');
            $this->form_validation->set_rules('item_asset_no', 'Asset No.', 'required');

            if ($this->form_validation->run() === true) {
                $item_information = array(
                    'company_item_no' => $data_post['company_item_no'],
                    'item_description' => $data_post['item_description'],
                    'item_vendor' => $data_post['item_vendor'],
                    'item_vendor_acct_no' => $data_post['item_vendor_acct_no'],
                    'item_reorder_no' => $data_post['item_reorder_no'],
                    'item_category' => $data_post['item_category'],
                    'add_to_hospice_item_list' => $data_post['add_to_hospice_item_list'],
                );
                $item_id = $data_post['viewed_item_id'];
                $this->inventory_model->update_item_information($item_information, $item_id);

                $new_item_warehouse_location = array(
                    'company_item_no' => $data_post['company_item_no'],
                    'item_warehouse_location' => $data_post['item_warehouse_location'],
                );
                $this->inventory_model->update_item_location_information($new_item_warehouse_location, $item_id, $this->session->userdata('user_location'));

                $new_item_par_level = array(
                    'company_item_no' => $data_post['company_item_no'],
                    'item_par_level' => $data_post['item_par_level'],
                );
                $this->inventory_model->update_item_par_level($new_item_par_level, $item_id, $this->session->userdata('user_location'));

                $inventory_item_information = array(
                    'item_serial_no' => $data_post['item_serial_no'],
                    'item_asset_no' => $data_post['item_asset_no'],
                );
                $inventory_item_id = $data_post['viewed_inventory_item_id'];
                $this->inventory_model->update_inventory_item_information($inventory_item_information, $inventory_item_id);

                $this->inventory_model->delete_item_unit_of_measure($item_id);
                $unit_measure_type = array('box', 'each', 'case', 'pair', 'pack', 'package', 'roll');
                $value_name = '';
                $vendor_cost_name = '';
                $company_cost_name = '';
                foreach ($unit_measure_type as $value) {
                    $value_name = 'item_unit_value_'.$value;
                    $vendor_cost_name = 'item_vendor_cost_'.$value;
                    $company_cost_name = 'item_company_cost_'.$value;

                    if (!empty($data_post[$value_name]) && !empty($data_post[$vendor_cost_name]) && !empty($data_post[$company_cost_name])) {
                        $save_item_unit = array(
                            'item_id' => $item_id,
                            'item_unit_measure' => $value,
                            'item_unit_value' => $data_post[$value_name],
                            'item_vendor_cost' => $data_post[$vendor_cost_name],
                            'item_company_cost' => $data_post[$company_cost_name],
                        );
                        $this->inventory_model->save_item_unit($save_item_unit);
                    }
                }

                $view_company_item_no = $data_post['viewed_company_item_no'];
                $equipment_with_same_item_no = $this->inventory_model->get_equipment_with_same_item_no($view_company_item_no);
                if (!empty($equipment_with_same_item_no)) {
                    foreach ($equipment_with_same_item_no as $key => $value) {
                        $data_new_company_item_no = array(
                            'equipment_company_item_no' => $data_post['company_item_no'],
                        );
                        $this->inventory_model->update_equipment_company_item_no($data_new_company_item_no, $value['equipmentID']);
                    }
                }

                $item_with_same_item_no = $this->inventory_model->get_item_with_same_item_no($view_company_item_no);
                if (!empty($item_with_same_item_no)) {
                    foreach ($item_with_same_item_no as $key => $value) {
                        $data_item_new_company_item_no = array(
                            'company_item_no' => $data_post['company_item_no'],
                        );
                        $this->inventory_model->update_item_company_item_no($data_item_new_company_item_no, $value['item_id']);
                    }
                }

                $this->response_code = 0;
                $this->response_message = 'Item Information Updated Successfully.';
            } else {
                $this->response_message = validation_errors('<span></span>');
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));

            exit;
        }
    }

    public function edit_item_information()
    {
        if ($this->input->post()) {
            $data_post = $this->input->post();

            $this->form_validation->set_rules('company_item_no', 'Company Item No.', 'required');
            $this->form_validation->set_rules('item_description', 'Item Description', 'required');
            $this->form_validation->set_rules('item_vendor_acct_no', 'Account No.', 'required');
            $this->form_validation->set_rules('item_warehouse_location', 'Warehouse Location', 'required');

            if ($this->form_validation->run() === true) {
                $item_information = array(
                    'company_item_no' => $data_post['company_item_no'],
                    'item_description' => $data_post['item_description'],
                    'item_vendor' => $data_post['item_vendor'],
                    'item_vendor_acct_no' => $data_post['item_vendor_acct_no'],
                    'item_category' => $data_post['item_category'],
                    'add_to_hospice_item_list' => $data_post['add_to_hospice_item_list'],
                );
                $item_id = $data_post['viewed_item_id'];
                $this->inventory_model->update_item_information($item_information, $item_id);

                $new_item_warehouse_location = array(
                    'company_item_no' => $data_post['company_item_no'],
                    'item_warehouse_location' => $data_post['item_warehouse_location'],
                );
                $this->inventory_model->update_item_location_information($new_item_warehouse_location, $item_id, $this->session->userdata('user_location'));

                $new_item_par_level = array(
                    'company_item_no' => $data_post['company_item_no'],
                    'item_par_level' => $data_post['item_par_level'],
                );
                $this->inventory_model->update_item_par_level($new_item_par_level, $item_id, $this->session->userdata('user_location'));

                $this->inventory_model->delete_item_unit_of_measure($item_id);
                $unit_measure_type = array('box', 'each', 'case', 'pair', 'pack', 'package', 'roll');
                $value_name = '';
                $vendor_cost_name = '';
                $company_cost_name = '';
                foreach ($unit_measure_type as $value) {
                    $value_name = 'item_unit_value_'.$value;
                    $vendor_cost_name = 'item_vendor_cost_'.$value;
                    $company_cost_name = 'item_company_cost_'.$value;

                    if (!empty($data_post[$value_name]) && !empty($data_post[$vendor_cost_name]) && !empty($data_post[$company_cost_name])) {
                        $save_item_unit = array(
                            'item_id' => $item_id,
                            'item_unit_measure' => $value,
                            'item_unit_value' => $data_post[$value_name],
                            'item_vendor_cost' => $data_post[$vendor_cost_name],
                            'item_company_cost' => $data_post[$company_cost_name],
                        );
                        $this->inventory_model->save_item_unit($save_item_unit);
                    }
                }

                $this->response_code = 0;
                $this->response_message = 'Item Information Updated Successfully.';
            } else {
                $this->response_message = validation_errors('<span></span>');
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));

            exit;
        }
    }

    public function edit_item_information_v2()
    {
        if ($this->input->post()) {
            $data_post = $this->input->post();

            $this->form_validation->set_rules('company_item_no', 'Company Item No.', 'required');
            $this->form_validation->set_rules('item_description', 'Item Description', 'required');
            $this->form_validation->set_rules('item_vendor_acct_no', 'Account No.', 'required');
            $this->form_validation->set_rules('item_warehouse_location', 'Warehouse Location', 'required');
            $this->form_validation->set_rules('item_reorder_no', 'Reorder Number', 'required');

            if ($this->form_validation->run() === true) {
                $item_information = array(
                    'company_item_no' => $data_post['company_item_no'],
                    'item_description' => $data_post['item_description'],
                    'item_vendor' => $data_post['item_vendor'],
                    'item_vendor_acct_no' => $data_post['item_vendor_acct_no'],
                    'item_category' => $data_post['item_category'],
                    // 'item_group_id' => $data_post['item_group_id'],
                    'add_to_hospice_item_list' => $data_post['add_to_hospice_item_list'],
                    'item_reorder_no' => $data_post['item_reorder_no'],
                );
                $item_id = $data_post['viewed_item_id'];
                $this->inventory_model->update_item_information($item_information, $item_id);

                $new_item_warehouse_location = array(
                    'company_item_no' => $data_post['company_item_no'],
                    'item_warehouse_location' => $data_post['item_warehouse_location'],
                );
                $this->inventory_model->update_item_location_information($new_item_warehouse_location, $item_id, $this->session->userdata('user_location'));

                $new_item_par_level = array(
                    'company_item_no' => $data_post['company_item_no'],
                    'item_par_level' => $data_post['item_par_level'],
                );
                $this->inventory_model->update_item_par_level($new_item_par_level, $item_id, $this->session->userdata('user_location'));

                $this->inventory_model->delete_item_unit_of_measure($item_id);
                $unit_measure_type = array('box', 'each', 'case', 'pair', 'pack', 'package', 'roll');
                $value_name = '';
                $vendor_cost_name = '';
                $company_cost_name = '';
                foreach ($unit_measure_type as $value) {
                    $value_name = 'item_unit_value_'.$value;
                    $vendor_cost_name = 'item_vendor_cost_'.$value;
                    $company_cost_name = 'item_company_cost_'.$value;
                    if (!empty($data_post[$value_name]) && !empty($data_post[$vendor_cost_name]) && !empty($data_post[$company_cost_name])) {
                        $save_item_unit = array(
                            'item_id' => $item_id,
                            'item_unit_measure' => $value,
                            'item_unit_value' => $data_post[$value_name],
                            'item_vendor_cost' => $data_post[$vendor_cost_name],
                            'item_company_cost' => $data_post[$company_cost_name],
                        );
                        $this->inventory_model->save_item_unit($save_item_unit);
                    }
                }

                $view_company_item_no = $data_post['viewed_company_item_no'];
                $equipment_with_same_item_no = $this->inventory_model->get_equipment_with_same_item_no($view_company_item_no);
                if (!empty($equipment_with_same_item_no)) {
                    foreach ($equipment_with_same_item_no as $key => $value) {
                        $data_new_company_item_no = array(
                            'equipment_company_item_no' => $data_post['company_item_no'],
                        );
                        $this->inventory_model->update_equipment_company_item_no($data_new_company_item_no, $value['equipmentID']);
                    }
                }

                $item_with_same_item_no = $this->inventory_model->get_item_with_same_item_no($view_company_item_no);
                if (!empty($item_with_same_item_no)) {
                    foreach ($item_with_same_item_no as $key => $value) {
                        $data_item_new_company_item_no = array(
                            'company_item_no' => $data_post['company_item_no'],
                        );
                        $this->inventory_model->update_item_company_item_no($data_item_new_company_item_no, $value['item_id']);
                    }
                }


                // ADD TO HOSPICE LIST 07/15/2021 ----- START
                if ($data_post['temp_add_to_hospice_item_list'] != $data_post['add_to_hospice_item_list']) {
                    $is_equipment_exist = $this->inventory_model->get_equipment_details($data_post['company_item_no']);
                    $data_equipment = array();
                    if ($data_post['add_to_hospice_item_list'] == 0) {
                        $data_equipment = array(
                            "is_add_to_hospice_item_list" => 0
                        );
                    }
                    if ($data_post['add_to_hospice_item_list'] == 1) {
                        $data_equipment = array(
                            "is_add_to_hospice_item_list" => 1
                        );

                        // Add to hospice list
                        if (empty($is_equipment_exist)) {
                            if ($data_post['add_to_hospice_item_list'] == 1) {
                                $equipment_category_id = 2;
                                if ($data_post['disposable_item_list'] != null) {
                                    $equipment_category_id = $data_post['disposable_item_list'];
                                }
                                $hospice_list = $this->inventory_model->get_dme_hospices_with_uniqueID($this->session->userdata('user_location'));
        
                                $new_equipment = array(
                                    'categoryID'                => $equipment_category_id,
                                    'parentID'                  => 0,
                                    'key_name'                  => str_replace(' ', '_', $data_post['item_description']),
                                    'key_desc'                  => $data_post['item_description'],
                                    'input_type'                => 'checkbox',
                                    'optionID'                  => 0,
                                    'option_order'              => 0,
                                    'noncapped_reference'       => 0,
                                    'equipment_company_item_no' => $data_post['company_item_no'],
                                    'is_recurring'              => 0,
                                    'is_package'                => 0
                                );
        
                                $equipment_id = $this->inventory_model->insert_new_equipment($new_equipment);
        
                                if ($equipment_id) {
                                    if ($equipment_category_id == 3) {
                                        $new_equipment_quantity = array(
                                            'categoryID'                => $equipment_category_id,
                                            'parentID'                  => $equipment_id,
                                            'key_name'                  => 'quantity',
                                            'key_desc'                  => 'Quantity',
                                            'input_type'                => 'text',
                                            'optionID'                  => 0,
                                            'option_order'              => 0,
                                            'noncapped_reference'       => 0,
                                            'equipment_company_item_no' => 0,
                                            'is_recurring'              => 0,
                                            'is_package'                => 0
                                        );
            
                                        $equipment_id_quantity = $this->inventory_model->insert_new_equipment($new_equipment_quantity);
                                    }
        
                                    $new_assigned_equipment = array(
                                        'equipmentID'       => $equipment_id,
                                        'hospiceID'         => 0,
                                        'uniqueID'          => 0,
                                        'monthly_rate'      => 0,
                                        'daily_rate'        => 0,
                                        'purchase_price'    => 0,
                                        'is_hidden'         => 0
                                    );
        
                                    foreach($hospice_list as $value) {
                                        if ($value['uniqueID'] != null) {
                                            $new_assigned_equipment['hospiceID'] = $value['hospiceID'];
                                            $new_assigned_equipment['uniqueID'] = $value['uniqueID'];
        
                                            $this->inventory_model->insert_new_assigned_equipment($new_assigned_equipment);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (!empty($is_equipment_exist)) {
                        // $this->inventory_model->update_equipment_company_item_no($data_equipment, $is_equipment_exist['equipmentID']);
                        $this->inventory_model->update_equipment_company_item_no_v2($data_equipment, $data_post['company_item_no']);
                    }
                }
                // ADD TO HOSPICE LIST 07/15/2021 ----- END

                $this->response_code = 0;
                $this->response_message = 'Item Information Updated Successfully.';
            } else {
                $this->response_message = validation_errors('<span></span>');
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));

            exit;
        }
    }

    public function edit_vendor_information()
    {
        if ($this->input->post()) {
            $data_post = $this->input->post();

            $this->form_validation->set_rules('vendor_entry_date', 'Entry Date', 'required');
            $this->form_validation->set_rules('vendor_name', 'Vendor Name', 'required');
            $this->form_validation->set_rules('vendor_acct_no', 'Account No.', 'required');
            $this->form_validation->set_rules('vendor_credit', 'Credit', 'required');
            $this->form_validation->set_rules('vendor_credit_terms', 'Credit Terms', 'required');
            $this->form_validation->set_rules('vendor_credit_limit', 'Credit Limit', 'required');
            $this->form_validation->set_rules('vendor_city', 'City', 'required');
            $this->form_validation->set_rules('vendor_state', 'State/Province', 'required');
            $this->form_validation->set_rules('vendor_postal_code', 'Postal Code', 'required');
            $this->form_validation->set_rules('vendor_phone_no', 'Phone No.', 'required');
            $this->form_validation->set_rules('vendor_fax_no', 'Fax No.', 'required');
            $this->form_validation->set_rules('vendor_email_address', 'Email Address', 'required');
            $this->form_validation->set_rules('vendor_sales_rep', 'Sales Rep.', 'required');
            $this->form_validation->set_rules('vendor_office_no', 'Office No.', 'required');
            $this->form_validation->set_rules('vendor_cell_no', 'Cell No.', 'required');
            $this->form_validation->set_rules('vendor_sales_rep_email_address', 'Sales Rep. Email Address', 'required');
            $this->form_validation->set_rules('vendor_shipping_cost', 'Shipping Cost', 'required');

            if ($this->form_validation->run() === true) {
                $vendor_information = array(
                    'vendor_entry_date' => date('Y-m-d', strtotime($data_post['vendor_entry_date'])),
                    'vendor_name' => $data_post['vendor_name'],
                    'vendor_acct_no' => $data_post['vendor_acct_no'],
                    'vendor_credit' => $data_post['vendor_credit'],
                    'vendor_credit_terms' => $data_post['vendor_credit_terms'],
                    'vendor_credit_limit' => $data_post['vendor_credit_limit'],
                    'vendor_street' => $data_post['vendor_street'],
                    'vendor_city' => $data_post['vendor_city'],
                    'vendor_state' => $data_post['vendor_state'],
                    'vendor_postal_code' => $data_post['vendor_postal_code'],
                    'vendor_phone_no' => $data_post['vendor_phone_no'],
                    'vendor_fax_no' => $data_post['vendor_fax_no'],
                    'vendor_email_address' => $data_post['vendor_email_address'],
                    'vendor_sales_rep' => $data_post['vendor_sales_rep'],
                    'vendor_office_no' => $data_post['vendor_office_no'],
                    'vendor_cell_no' => $data_post['vendor_cell_no'],
                    'vendor_sales_rep_email_address' => $data_post['vendor_sales_rep_email_address'],
                    'vendor_shipping_cost' => $data_post['vendor_shipping_cost'],
                );

                $vendor_id = $data_post['viewed_vendor_id'];
                $this->inventory_model->update_vendor_information($vendor_information, $vendor_id);

                $this->response_code = 0;
                $this->response_message = 'Vendor Information Updated Successfully.';
            } else {
                $this->response_message = validation_errors('<span></span>');
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));

            exit;
        }
    }

    public function vendor_search()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data = array();
            $data['search_type'] = 'vendor_search';

            $this->templating_library->set_view('pages/inventory_search', 'pages/inventory_search', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function search_vendor()
    {
        $search_string = '';
        $count = 0;
        if (isset($_GET['searchString'])) {
            $search_string = $_GET['searchString'];
        }
        $searches = $this->inventory_model->search_vendor($search_string);
        $count = $this->inventory_model->search_vendor($search_string, true);

        if ($searches) {
            foreach ($searches as $search) {
                if ($search['vendor_active_sign'] == 1) {
                    echo "<tr style='color:#0E0C0C !important;cursor:pointer;border:1px solid #f5f5f5' class='vendor_results form-control' data-id='".$search['vendor_id']."'><td>".$search['vendor_name'].'</td></tr>';
                } else {
                    echo "<tr style='color:#5F5B5A !important;cursor:pointer;border:1px solid #f5f5f5' class='vendor_results form-control' data-id='".$search['vendor_id']."'><td>".$search['vendor_name'].'</td></tr>';
                }
            }
            if ($count > 5) {
                echo "<tr style='border:1px solid #f5f5f5;background-color: #58666e;color: white;'
                        class='form-control'>
                            <td align='center' style='font-style: italic;font-size: 12px;'>There are <strong>{$count}</strong> vendors in this result. <a style='color:white;text-decoration:underline;cursor:pointer;' class='vendor-result-lists' href='javascript:;'>View all results</a></td></tr>";
            }
        } else {
            echo "<tr style='border:1px solid #f5f5f5' class='form-control'><td>No Results Found.</td></tr>";
        }
    }

    public function search_vendor_v2()
    {
        $search_string = '';
        $count = 0;
        if (isset($_GET['searchString'])) {
            $search_string = $_GET['searchString'];
        }
        $searches = $this->inventory_model->search_vendor_v2($search_string);

        if ($searches) {
            foreach ($searches as $search) {
                if ($search['vendor_active_sign'] == 1) {
                    echo "<tr style='color:#0E0C0C !important;cursor:pointer;border:1px solid #f5f5f5' class='vendor_results form-control' data-id='".$search['vendor_id']."'><td>".$search['vendor_name'].'</td></tr>';
                } else {
                    echo "<tr style='color:#5F5B5A !important;cursor:pointer;border:1px solid #f5f5f5' class='vendor_results form-control' data-id='".$search['vendor_id']."'><td>".$search['vendor_name'].'</td></tr>';
                }
            }
        } else {
            echo "<tr style='border:1px solid #f5f5f5' class='form-control'><td>No Results Found.</td></tr>";
        }
    }

    public function get_searched_vendor($vendor_id)
    {
        $response = array('data' => array());

        $response['vendor_details'] = $this->inventory_model->get_searched_vendor($vendor_id);

        echo json_encode($response);
    }

    public function get_item_unit_of_measure($item_id, $unit_of_measure)
    {
        $response = array('data' => array());

        if ($unit_of_measure == 'bx' || $unit_of_measure == 'BX' || $unit_of_measure == 'box') {
            $unit_of_measure = 'box';
        } elseif ($unit_of_measure == 'each' || $unit_of_measure == 'ea' || $unit_of_measure == 'EA') {
            $unit_of_measure = 'each';
        } elseif ($unit_of_measure == 'case' || $unit_of_measure == 'cs' || $unit_of_measure == 'CS') {
            $unit_of_measure = 'case';
        } elseif ($unit_of_measure == 'pair' || $unit_of_measure == 'pr' || $unit_of_measure == 'PR') {
            $unit_of_measure = 'pair';
        } elseif ($unit_of_measure == 'pack' || $unit_of_measure == 'pk' || $unit_of_measure == 'PK') {
            $unit_of_measure = 'pack';
        } elseif ($unit_of_measure == 'package' || $unit_of_measure == 'pkg' || $unit_of_measure == 'PKG') {
            $unit_of_measure = 'package';
        } elseif ($unit_of_measure == 'roll' || $unit_of_measure == 'rl' || $unit_of_measure == 'RL') {
            $unit_of_measure = 'roll';
        }
        $response['item_details'] = get_item_unit_of_measure_cost($item_id, $unit_of_measure);

        echo json_encode($response);
    }

    public function save_order_requisition($item_count = '')
    {
        if ($this->input->post()) {
            $post_data = $this->input->post();

            $this->form_validation->set_rules('vendor_rep_taking_order_input', 'Vendor Rep. Taking Order', 'required');
            $this->form_validation->set_rules('confirmation_no_input', 'Confirmation No.', 'required');

            if ($item_count == '') {
                $item_count = 14;
            }
            for ($i = 1; $i <= $item_count; ++$i) {
                $name_here = 'order_req_item_id_'.$i;

                if (!empty($post_data[$name_here])) {

                    $this->form_validation->set_rules('order_req_cost_'.$i, 'Cost', 'required');
                    $this->form_validation->set_rules('order_req_quantity_ordered_'.$i, 'Qty. Ordered', 'required');
                    $this->form_validation->set_rules('order_req_total_cost_'.$i, 'Total Cost', 'required');
                }
            }

            if ($this->form_validation->run() === true) {
                $item_id = 0;
                $temp_item_batch_no = strtotime(date('Y-m-d H:i:s'));
                $req_receiving_batch_no = substr($temp_item_batch_no, 4, 10).'000';
                $req_payment_batch_no = '000'.substr($temp_item_batch_no, 4, 10);

                $order_req = array(
                    'order_req_date' => date('Y-m-d', strtotime($post_data['order_req_date'])),
                    'vendor_id' => $post_data['item_vendor'],
                    'location' => $post_data['location'],
                    'person_placing_order' => $post_data['person_placing_order'],
                    'purchase_order_no' => $post_data['purchase_order_no'],
                    'vendor_rep_taking_order' => strtoupper($post_data['vendor_rep_taking_order_input']),
                    'order_req_confirmation_no' => $post_data['confirmation_no_input'],
                    'order_req_amount' => $post_data['order_req_total_amount'],
                    'order_req_shipping_cost' => $post_data['order_req_shipping_cost'],
                    'order_req_grand_total' => $post_data['order_req_grand_total_amount'],
                    'order_req_status' => 'pending',
                );
                $order_req_id = $this->inventory_model->save_order_req($order_req);

                if (!empty($order_req_id)) {
                    $data_order_req_payment = array(
                        'order_req_id' => $order_req_id,
                        'purchase_order_no' => $post_data['purchase_order_no'],
                        'req_payment_batch_no' => $req_payment_batch_no,
                        'req_payment_status' => 'unpaid-po',
                        'ending_balance' => $post_data['order_req_grand_total_amount'],
                        'payment_method' => '',
                    );
                    $this->inventory_model->save_order_req_payment($data_order_req_payment);

                    $ordered_item_sign = 0;
                    for ($i = 1; $i <= $item_count; ++$i) {
                        $order_req_items = array();
                        $name = 'order_req_item_id_'.$i;
                        $new_item_quantity_ordered = 0;

                        if (!empty($post_data[$name])) {
                            ++$ordered_item_sign;
                            $item_batch_no = substr($temp_item_batch_no, 1, 10);
                            $req_item_id = 'order_req_item_id_'.$i;
                            $purchase_order_no = $post_data['purchase_order_no'];
                            $req_item_unit_measure = 'order_req_unit_of_measure_'.$i;
                            $req_item_cost = 'order_req_cost_'.$i;
                            $req_item_total_cost = 'order_req_total_cost_'.$i;
                            $req_item_status = 0;
                            $req_quantity_ordered = 'order_req_quantity_ordered_'.$i;

                            if ($post_data[$req_item_unit_measure] == 'bx' || $post_data[$req_item_unit_measure] == 'box') {
                                $new_unit_of_measure = 'box';
                            } elseif ($post_data[$req_item_unit_measure] == 'each' || $post_data[$req_item_unit_measure] == 'ea') {
                                $new_unit_of_measure = 'each';
                            } elseif ($post_data[$req_item_unit_measure] == 'case' || $post_data[$req_item_unit_measure] == 'cs') {
                                $new_unit_of_measure = 'case';
                            } elseif ($post_data[$req_item_unit_measure] == 'pair' || $post_data[$req_item_unit_measure] == 'pr') {
                                $new_unit_of_measure = 'pair';
                            } elseif ($post_data[$req_item_unit_measure] == 'pack' || $post_data[$req_item_unit_measure] == 'pk') {
                                $new_unit_of_measure = 'pack';
                            } elseif ($post_data[$req_item_unit_measure] == 'package' || $post_data[$req_item_unit_measure] == 'pkg') {
                                $new_unit_of_measure = 'package';
                            } elseif ($post_data[$req_item_unit_measure] == 'roll' || $post_data[$req_item_unit_measure] == 'rl') {
                                $new_unit_of_measure = 'roll';
                            }

                            $item_unit_measure_value_results = $this->inventory_model->get_item_unit_measure_value($new_unit_of_measure, $post_data[$req_item_id]);
                            if (!empty($item_unit_measure_value_results)) {
                                $new_item_quantity_ordered = $post_data[$req_quantity_ordered] * $item_unit_measure_value_results['item_unit_value'];
                            } else {
                                $new_item_quantity_ordered = $post_data[$req_quantity_ordered] * 1;
                            }

                            $data_order_req_receiving = array(
                                'order_req_id' => $order_req_id,
                                'purchase_order_no' => $post_data['purchase_order_no'],
                                'item_batch_no' => $item_batch_no,
                                'item_unit_measure' => $new_unit_of_measure,
                                'req_receiving_batch_no' => $req_receiving_batch_no,
                                'item_id' => $post_data[$req_item_id],
                                'item_cost' => $post_data[$req_item_cost],
                                'item_total_cost' => $post_data[$req_item_total_cost],
                                'req_item_quantity_ordered' => $post_data[$req_quantity_ordered],
                                'req_item_quantity_received' => 0,
                                'req_receiving_status' => '',
                            );
                            $this->inventory_model->save_order_req_receiving($data_order_req_receiving);
                        }
                    }

                    if ($ordered_item_sign == 0) {
                        $this->inventory_model->delete_created_order_req($order_req_id);
                        $this->inventory_model->delete_created_order_req_payment($order_req_id);

                        $this->response_code = 1;
                        $this->response_message = 'Error! No item selected.';
                    } else {
                        $this->response_code = 0;
                        $this->response_message = 'Order Requisition Saved Successfully.';
                    }
                } else {
                    $this->response_code = 1;
                    $this->response_message = 'Error saving order!';
                }
            } else {
                $this->response_message = validation_errors('<span></span>');
            }
            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));
            exit;
        }
    }

    public function draft_order_requisition($item_count = '')
    {
        if ($this->input->post()) {
            $post_data = $this->input->post();

            $this->form_validation->set_rules('vendor_rep_taking_order_input', 'Vendor Rep. Taking Order', 'required');
            $this->form_validation->set_rules('confirmation_no_input', 'Confirmation No.', 'required');

            if ($item_count == '') {
                $item_count = 15;
            }
            if ($item_count == 1) {
                $item_count = 2;
            }
            for ($i = 1; $i < $item_count; ++$i) {
                $name_here = 'order_req_item_id_'.$i;

                if (!empty($post_data[$name_here])) {

                    $this->form_validation->set_rules('order_req_cost_'.$i, 'Cost', 'required');
                    $this->form_validation->set_rules('order_req_quantity_ordered_'.$i, 'Qty. Ordered', 'required');
                    $this->form_validation->set_rules('order_req_total_cost_'.$i, 'Total Cost', 'required');
                }
            }

            if ($this->form_validation->run() === true) {
                $item_id = 0;
                $temp_item_batch_no = strtotime(date('Y-m-d H:i:s'));
                $req_receiving_batch_no = substr($temp_item_batch_no, 4, 10).'000';
                $req_payment_batch_no = '000'.substr($temp_item_batch_no, 4, 10);

                $order_req = array(
                    'order_req_date' => date('Y-m-d', strtotime($post_data['order_req_date'])),
                    'vendor_id' => $post_data['item_vendor'],
                    'location' => $post_data['location'],
                    'person_placing_order' => $post_data['person_placing_order'],
                    'purchase_order_no' => $post_data['purchase_order_no'],
                    'vendor_rep_taking_order' => strtoupper($post_data['vendor_rep_taking_order_input']),
                    'order_req_confirmation_no' => $post_data['confirmation_no_input'],
                    'order_req_amount' => $post_data['order_req_total_amount'],
                    'order_req_shipping_cost' => $post_data['order_req_shipping_cost'],
                    'order_req_grand_total' => $post_data['order_req_grand_total_amount'],
                    'order_req_status' => 'pending',
                    'draft_order' => 1,
                );
                $order_req_id = $this->inventory_model->save_order_req($order_req);

                if (!empty($order_req_id)) {
                    $data_order_req_payment = array(
                        'order_req_id' => $order_req_id,
                        'purchase_order_no' => $post_data['purchase_order_no'],
                        'req_payment_batch_no' => $req_payment_batch_no,
                        'req_payment_status' => 'unpaid-po',
                        'ending_balance' => $post_data['order_req_grand_total_amount'],
                        'payment_method' => '',
                    );
                    $this->inventory_model->save_order_req_payment($data_order_req_payment);

                    $ordered_item_sign = 0;
                    for ($i = 1; $i < $item_count; ++$i) {
                        $order_req_items = array();
                        $name = 'order_req_item_id_'.$i;
                        $new_item_quantity_ordered = 0;

                        if (!empty($post_data[$name])) {
                            ++$ordered_item_sign;
                            $item_batch_no = substr($temp_item_batch_no, 1, 10);
                            $req_item_id = 'order_req_item_id_'.$i;
                            $purchase_order_no = $post_data['purchase_order_no'];
                            $req_item_unit_measure = 'order_req_unit_of_measure_'.$i;
                            $req_item_cost = 'order_req_cost_'.$i;
                            $req_item_total_cost = 'order_req_total_cost_'.$i;
                            $req_item_status = 0;
                            $req_quantity_ordered = 'order_req_quantity_ordered_'.$i;

                            if ($post_data[$req_item_unit_measure] == 'bx' || $post_data[$req_item_unit_measure] == 'box') {
                                $new_unit_of_measure = 'box';
                            } elseif ($post_data[$req_item_unit_measure] == 'each' || $post_data[$req_item_unit_measure] == 'ea') {
                                $new_unit_of_measure = 'each';
                            } elseif ($post_data[$req_item_unit_measure] == 'case' || $post_data[$req_item_unit_measure] == 'cs') {
                                $new_unit_of_measure = 'case';
                            } elseif ($post_data[$req_item_unit_measure] == 'pair' || $post_data[$req_item_unit_measure] == 'pr') {
                                $new_unit_of_measure = 'pair';
                            } elseif ($post_data[$req_item_unit_measure] == 'pack' || $post_data[$req_item_unit_measure] == 'pk') {
                                $new_unit_of_measure = 'pack';
                            } elseif ($post_data[$req_item_unit_measure] == 'package' || $post_data[$req_item_unit_measure] == 'pkg') {
                                $new_unit_of_measure = 'package';
                            } elseif ($post_data[$req_item_unit_measure] == 'roll' || $post_data[$req_item_unit_measure] == 'rl') {
                                $new_unit_of_measure = 'roll';
                            }

                            $item_unit_measure_value_results = $this->inventory_model->get_item_unit_measure_value($new_unit_of_measure, $post_data[$req_item_id]);
                            if (!empty($item_unit_measure_value_results)) {
                                $new_item_quantity_ordered = $post_data[$req_quantity_ordered] * $item_unit_measure_value_results['item_unit_value'];
                            } else {
                                $new_item_quantity_ordered = $post_data[$req_quantity_ordered] * 1;
                            }

                            $data_order_req_receiving = array(
                                'order_req_id' => $order_req_id,
                                'purchase_order_no' => $post_data['purchase_order_no'],
                                'item_batch_no' => $item_batch_no,
                                'item_unit_measure' => $new_unit_of_measure,
                                'req_receiving_batch_no' => $req_receiving_batch_no,
                                'item_id' => $post_data[$req_item_id],
                                'item_cost' => $post_data[$req_item_cost],
                                'item_total_cost' => $post_data[$req_item_total_cost],
                                'req_item_quantity_ordered' => $post_data[$req_quantity_ordered],
                                'req_item_quantity_received' => 0,
                                'req_receiving_status' => '',
                            );
                            $this->inventory_model->save_order_req_receiving($data_order_req_receiving);
                        }
                    }

                    if ($ordered_item_sign == 0) {
                        $this->inventory_model->delete_created_order_req($order_req_id);
                        $this->inventory_model->delete_created_order_req_payment($order_req_id);

                        $this->response_code = 1;
                        $this->response_message = 'Error! No item selected.';
                    } else {
                        $this->response_code = 0;
                        $this->response_message = 'Order Requisition Saved as Draft Successfully.';
                    }
                } else {
                    $this->response_code = 1;
                    $this->response_message = 'Error saving order!';
                }
            } else {
                $this->response_message = validation_errors('<span></span>');
            }
            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));
            exit;
        }
    }

    public function save_inventory_item()
    {
        if ($this->input->post()) {
            $post_data = $this->input->post();

            $update_inventory_item_data = array(
                'item_unit_measure' => $post_data['inventory_item_unit_measure'],
                'item_quantity' => $post_data['inventory_item_quantity'],
            );
            $response = $this->inventory_model->update_inventory_item_data($update_inventory_item_data, $post_data['inventory_item_selected'], $post_data['returned_item_batch_no']);

            if ($response) {
                $this->response_code = 0;
                $this->response_message = 'Data Saved Successfully.';
            } else {
                $this->response_code = 1;
                $this->response_message = 'Error Saving Information.';
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));
            exit;
        }
    }

    public function skip_serial_asset_no_vendor_section($item_id, $item_unit_measure_selected, $item_quantity)
    {
        if ($this->input->post()) {
            $post_data = $this->input->post();

            $data_to_update = array();
            $temp_item_batch_no = strtotime(date('Y-m-d H:i:s'));
            $item_batch_no = substr($temp_item_batch_no, 1, 10);
            foreach ($post_data as $key => $value) {
                $exploded_key = explode('_', $key);

                if ($exploded_key[0] == 'serial') {
                    $new_inventory_item = array(
                        'item_unit_measure' => $item_unit_measure_selected,
                        'item_quantity' => $item_quantity,
                        'item_id' => $item_id,
                        'item_batch_no' => $item_batch_no,
                        'item_serial_no' => 'NA',
                        'item_status_location' => 'on_hand',
                    );
                    $latest_new_inventory_item_id = $this->inventory_model->insert_new_inventory_item($new_inventory_item);
                } elseif ($exploded_key[0] == 'asset') {
                    $array_to_update = array(
                        'inventory_item_id' => $latest_new_inventory_item_id,
                        'item_unit_measure' => $item_unit_measure_selected,
                        'item_quantity' => $item_quantity,
                        'item_id' => $item_id,
                        'item_batch_no' => $item_batch_no,
                        'item_asset_no' => 'NA',
                        'item_status_location' => 'on_hand',
                    );
                    $data_to_update[] = $array_to_update;
                }
            }
            $this->inventory_model->save_serial_asset_no($data_to_update);

            $this->response_code = 0;
            $this->response_message = 'Item Added Successfully.';
            $this->response_item_batch_no = $item_batch_no;

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
                'item_batch_no' => $this->response_item_batch_no,
            ));
            exit;
        }
    }

    public function save_serial_asset_no_vendor_section($item_id, $item_unit_measure_selected, $item_quantity)
    {
        if ($this->input->post()) {
            $post_data = $this->input->post();

            $data_to_update = array();
            $temp_item_batch_no = strtotime(date('Y-m-d H:i:s'));
            $item_batch_no = substr($temp_item_batch_no, 1, 10);
            foreach ($post_data as $key => $value) {
                if (!empty($value)) {
                    $exploded_key = explode('_', $key);

                    if ($exploded_key[0] == 'serial') {
                        $new_inventory_item = array(
                            'item_id' => $item_id,
                            'item_unit_measure' => $item_unit_measure_selected,
                            'item_quantity' => $item_quantity,
                            'item_batch_no' => $item_batch_no,
                            'item_serial_no' => strtoupper($value),
                            'item_status_location' => 'on_hand',
                            'item_location' => $this->session->userdata('user_location'),
                        );
                        $latest_new_inventory_item_id = $this->inventory_model->insert_new_inventory_item($new_inventory_item);
                    } elseif ($exploded_key[0] == 'asset') {
                        $array_to_update = array(
                            'inventory_item_id' => $latest_new_inventory_item_id,
                            'item_unit_measure' => $item_unit_measure_selected,
                            'item_quantity' => $item_quantity,
                            'item_id' => $item_id,
                            'item_batch_no' => $item_batch_no,
                            'item_asset_no' => strtoupper($value),
                            'item_status_location' => 'on_hand',
                        );
                        $data_to_update[] = $array_to_update;
                    }
                }
            }
            $this->inventory_model->save_serial_asset_no($data_to_update);

            $this->response_code = 0;
            $this->response_message = 'Item Added Successfully.';
            $this->response_item_batch_no = $item_batch_no;

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
                'item_batch_no' => $this->response_item_batch_no,
            ));
            exit;
        }
    }

    public function skip_serial_asset_no($purchase_order_no, $item_id, $req_receiving_batch_no, $quantity_ordered)
    {
        if ($this->input->post()) {
            $post_data = $this->input->post();
            $inventory_item_details = $this->inventory_model->get_order_item_details($purchase_order_no, $item_id);

            $count = 0;
            $data_to_update = array();
            $latest_new_inventory_item_id = '';
            foreach ($post_data as $key => $value) {
                $exploded_key = explode('_', $key);

                if ($exploded_key[0] == 'newserial') {
                    if (!empty($inventory_item_details)) {
                        $new_inventory_item = array(
                            'item_id' => $inventory_item_details['item_id'],
                            'order_req_id' => $inventory_item_details['order_req_id'],
                            'purchase_order_no' => $inventory_item_details['purchase_order_no'],
                            'item_batch_no' => $inventory_item_details['item_batch_no'],
                            'item_unit_measure' => $inventory_item_details['item_unit_measure'],
                            'item_quantity' => $inventory_item_details['req_item_quantity_ordered'],
                            'item_cost' => $inventory_item_details['item_cost'],
                            'item_total_cost' => $inventory_item_details['item_total_cost'],
                            'req_receiving_batch_no' => $req_receiving_batch_no,
                            'item_serial_no' => 'NA',
                            'item_status_location' => 'on_hand',
                            'item_location' => $this->session->userdata('user_location')
                        );
                        $latest_new_inventory_item_id = $this->inventory_model->insert_new_inventory_item($new_inventory_item);
                        ++$count;
                    }
                } elseif ($exploded_key[0] == 'newasset') {
                    $array_to_update = array(
                        'inventory_item_id' => $latest_new_inventory_item_id,
                        'item_asset_no' => 'NA',
                        'req_receiving_batch_no' => $req_receiving_batch_no,
                    );
                    $data_to_update[] = $array_to_update;
                }
            }
            $this->inventory_model->save_serial_asset_no($data_to_update);

            // if ($quantity_ordered != $inventory_item_details['req_item_quantity_ordered']) {
            //     $inventory_item_list = $this->inventory_model->get_inventory_specific_item_list($item_id, $purchase_order_no);
            //     if ($quantity_ordered > $inventory_item_details['req_item_quantity_ordered']) {
            //         $new_inventory_item_array = array();
            //         for ($i = 0; $i < $quantity_ordered - $count; ++$i) {
            //             $new_item_row = array(
            //                 'item_id' => $inventory_item_details['item_id'],
            //                 'order_req_id' => $inventory_item_details['order_req_id'],
            //                 'purchase_order_no' => $inventory_item_details['purchase_order_no'],
            //                 'item_batch_no' => $inventory_item_details['item_batch_no'],
            //                 'item_unit_measure' => $inventory_item_details['item_unit_measure'],
            //                 'item_quantity' => $quantity_ordered,
            //                 'item_cost' => $inventory_item_details['item_cost'],
            //                 'item_total_cost' => $inventory_item_details['item_total_cost'],
            //                 'req_receiving_batch_no' => $req_receiving_batch_no,
            //                 'item_serial_no' => 'NA',
            //                 'item_status_location' => 'on_hand',
            //             );
            //             $new_inventory_item_array[] = $new_item_row;
            //         }
            //         $latest_new_inventory_item_id = $this->inventory_model->insert_new_inventory_item_batch($new_inventory_item_array);
            //     }
            // }

            $this->response_code = 0;
            $this->response_message = 'Added Successfully.';

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));
            exit;
        }
    }

    public function save_serial_asset_no($purchase_order_no, $item_id, $req_receiving_batch_no, $quantity_ordered)
    {
        if ($this->input->post()) {
            $post_data = $this->input->post();
            $inventory_item_details = $this->inventory_model->get_order_item_details($purchase_order_no, $item_id);

            $count = 0;
            $data_to_update = array();
            $latest_new_inventory_item_id = '';
            foreach ($post_data as $key => $value) {
                if (!empty($value)) {
                    $exploded_key = explode('_', $key);

                    if ($exploded_key[0] == 'newserial') {
                        if (!empty($inventory_item_details)) {
                            $new_inventory_item = array(
                                'item_id' => $inventory_item_details['item_id'],
                                'order_req_id' => $inventory_item_details['order_req_id'],
                                'purchase_order_no' => $inventory_item_details['purchase_order_no'],
                                'item_batch_no' => $inventory_item_details['item_batch_no'],
                                'item_unit_measure' => $inventory_item_details['item_unit_measure'],
                                'item_quantity' => $inventory_item_details['req_item_quantity_ordered'],
                                'item_cost' => $inventory_item_details['item_cost'],
                                'item_total_cost' => $inventory_item_details['item_total_cost'],
                                'req_receiving_batch_no' => $req_receiving_batch_no,
                                'item_serial_no' => strtoupper($value),
                                'item_status_location' => 'on_hand',
                                'item_location' => $this->session->userdata('user_location')
                            );
                            $latest_new_inventory_item_id = $this->inventory_model->insert_new_inventory_item($new_inventory_item);
                            ++$count;
                        }
                    } elseif ($exploded_key[0] == 'newasset') {
                        $array_to_update = array(
                            'inventory_item_id' => $latest_new_inventory_item_id,
                            'item_asset_no' => strtoupper($value),
                            'req_receiving_batch_no' => $req_receiving_batch_no,
                        );
                        $data_to_update[] = $array_to_update;
                    }
                }
            }
            $this->inventory_model->save_serial_asset_no($data_to_update);

            // if ($quantity_ordered != $inventory_item_details['req_item_quantity_ordered']) {
            //     if ($quantity_ordered > $inventory_item_details['req_item_quantity_ordered']) {
            //         $new_inventory_item_array = array();
            //         for ($i = 0; $i < $quantity_ordered - $count; ++$i) {
            //             $new_item_row = array(
            //                 'item_id' => $inventory_item_details['item_id'],
            //                 'order_req_id' => $inventory_item_details['order_req_id'],
            //                 'purchase_order_no' => $inventory_item_details['purchase_order_no'],
            //                 'item_batch_no' => $inventory_item_details['item_batch_no'],
            //                 'item_unit_measure' => $inventory_item_details['item_unit_measure'],
            //                 'item_quantity' => $quantity_ordered,
            //                 'item_cost' => $inventory_item_details['item_cost'],
            //                 'item_total_cost' => $inventory_item_details['item_total_cost'],
            //                 'req_receiving_batch_no' => $req_receiving_batch_no,
            //                 'item_serial_no' => strtoupper($value),
            //                 'item_status_location' => 'on_hand',
            //                 'item_location' => $this->session->userdata('user_location'),
            //             );
            //             $new_inventory_item_array[] = $new_item_row;
            //         }
            //         $latest_new_inventory_item_id = $this->inventory_model->insert_new_inventory_item_batch($new_inventory_item_array);
            //     }
            // }

            $this->response_code = 0;
            $this->response_message = 'Added Successfully.';

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));
            exit;
        }
    }

    public function view_all_searched_vendors($searched_content)
    {
        $response = array('data' => array());

        $response['searched_vendors'] = $this->inventory_model->search_vendor_v2($searched_content);

        echo json_encode($response);
    }

    public function all_vendors()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            $data['vendor_list'] = $this->inventory_model->get_vendor_list();

            $this->templating_library->set_view('pages/all_vendors_inventory', 'pages/all_vendors_inventory', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function get_purchase_item_graph_today_default_comparison()
    {
        $vendor_id = '';
        $from_date = date('Y-m');
        $from = $from_date.'-01';
        $to = date('Y-m-d');
        $equipment_array = array();
        $graph_data = array();
        $graph = array();
        $data = array();
        $item_unit_measure_values = array();

        $query_result = $this->inventory_model->get_purchase_item_graph_sort_date_type_v2($vendor_id, $from, $to, $this->session->userdata('user_location'));
        if (!empty($query_result)) {
            foreach ($query_result as $key => $value) {
                if (!in_array($value['item_id'], $equipment_array)) {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => $unit_of_measure_value['item_unit_value'],
                                                                'count_second' => 0,
                                                            );
                    } else {
                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => $item_unit_measure_values[$item_id_unit_measure],
                                                                'count_second' => 0,
                                                            );
                    }
                } else {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $graph_data[$value['item_id']]['count'] += $unit_of_measure_value['item_unit_value'];
                    } else {
                        $graph_data[$value['item_id']]['count'] += $item_unit_measure_values[$item_id_unit_measure];
                    }
                }
            }
        }

        $current_month = date('m');
        $current_year = date('Y');
        $from_month = $current_month;
        $from_year = $current_year;
        if ($current_month == 12) {
            $from_month = 01;
        } else {
            $from_month += 1;
            $from_year -= 1;
        }
        if ($from_month > 9) {
            $from_date = $from_year.'-'.$from_month.'-01';
        } else {
            $from_date = $from_year.'-0'.$from_month.'-01';
        }

        if ($current_month < 7) {
            $to_month = ($current_month - 6) + 12;
            $to_year = $current_year - 1;
        } else {
            $to_month = $current_month - 6;
            $to_year = $current_year;
        }
        if ($to_month > 9) {
            $temp_date = $to_year.'-'.$to_month;
            $to_day = date('t', strtotime($temp_date));
            if ($to_day > 9) {
                $to_date = $to_year.'-'.$to_month.'-'.$to_day;
            } else {
                $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
            }
        } else {
            $temp_date = $to_year.'-0'.$to_month;
            $to_day = date('t', strtotime($temp_date));
            if ($to_day > 9) {
                $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
            } else {
                $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
            }
        }
        if ($current_month < 6) {
            $to_month = ($current_month - 5) + 12;
            $to_year = $current_year - 1;
        } else {
            $to_month = $current_month - 5;
            $to_year = $current_year;
        }
        if ($to_month > 9) {
            $temp_date = $to_year.'-'.$to_month;
            $to_day = date('t', strtotime($temp_date));
            if ($to_day > 9) {
                $second_from_date = $to_year.'-'.$to_month.'-01';
            } else {
                $second_from_date = $to_year.'-'.$to_month.'-01';
            }
        } else {
            $temp_date = $to_year.'-0'.$to_month;
            $to_day = date('t', strtotime($temp_date));
            if ($to_day > 9) {
                $second_from_date = $to_year.'-0'.$to_month.'-01';
            } else {
                $second_from_date = $to_year.'-0'.$to_month.'-01';
            }
        }
        $second_to_date = date('Y-m-d');

        $second_date_first_result = $this->inventory_model->get_purchase_item_graph_sort_date_type_second_date_v2($vendor_id, $from_date, $to_date, $this->session->userdata('user_location'));
        if (!empty($second_date_first_result)) {
            foreach ($second_date_first_result as $key => $value) {
                if (!in_array($value['item_id'], $equipment_array)) {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => 0,
                                                                'count_second' => $unit_of_measure_value['item_unit_value'],
                                                            );
                    } else {
                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => 0,
                                                                'count_second' => $unit_of_measure_value['item_unit_value'],
                                                            );
                    }
                } else {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $graph_data[$value['item_id']]['count_second'] += $unit_of_measure_value['item_unit_value'];
                    } else {
                        $graph_data[$value['item_id']]['count_second'] += $item_unit_measure_values[$item_id_unit_measure];
                    }
                }
            }
        }

        $second_date_second_result = $this->inventory_model->get_purchase_item_graph_sort_date_type_second_date_v2($vendor_id, $second_from_date, $second_to_date, $this->session->userdata('user_location'));
        if (!empty($second_date_second_result)) {
            foreach ($second_date_second_result as $key => $value) {
                if (!in_array($value['item_id'], $equipment_array)) {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => 0,
                                                                'count_second' => $unit_of_measure_value['item_unit_value'],
                                                            );
                    } else {
                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => 0,
                                                                'count_second' => $unit_of_measure_value['item_unit_value'],
                                                            );
                    }
                } else {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $graph_data[$value['item_id']]['count_second'] += $unit_of_measure_value['item_unit_value'];
                    } else {
                        $graph_data[$value['item_id']]['count_second'] += $item_unit_measure_values[$item_id_unit_measure];
                    }
                }
            }
        }

        foreach ($graph_data as $key => $value) {
            $graph[] = array('label' => $value['item_description'], 'value' => $value['count'], 'second_value' => $value['count_second']);
        }

        $data = array(
            'date_range_from' => $from,
            'graph' => $graph,
            'date_range_to' => $to,
        );

        $this->common->code = 0;
        $this->common->message = 'Item Usage YTD Comparison This Month to Date';
        $this->common->data = $data;

        $this->common->response(false);
    }

    public function filter_purchase_item_graph_comparison($filter_from, $filter_to, $vendor_id, $sort_dates, $filter_type)
    {
        $equipment_array = array();
        $graph_data = array();
        $graph = array();
        $data = array();
        $item_unit_measure_values = array();

        if ($filter_type == 1 || $filter_type == 3) {
            if ($filter_from != 0 && $filter_to != 0) {
                $from_date = $filter_from;
                $to_date = $filter_to;
            } else {
                $from_date = '';
                $to_date = '';
            }
        } elseif ($filter_type == 2) {
            $second_from_date = '';
            $second_to_date = '';
            if ($sort_dates == 0) {
                if ($filter_from != 0 && $filter_to != 0) {
                    $from_date = $filter_from;
                    $to_date = $filter_to;
                } else {
                    $from_date = '';
                    $to_date = '';
                }
            } elseif ($sort_dates == 1) {
                $from_date = date('Y-m-d');
                $to_date = $from_date;
            } elseif ($sort_dates == 2) {
                $from_date = (new DateTime('last Sunday'))->format('Y-m-d');
                $to_date = (new DateTime('next Saturday'))->format('Y-m-d');
            } elseif ($sort_dates == 3) {
                $from_date = (new DateTime('last Sunday'))->format('Y-m-d');
                $to_date = date('Y-m-d');
            } elseif ($sort_dates == 4) {
                $from_date = date('Y-m').'-01';
                $to_date = date('Y-m-t');
            } elseif ($sort_dates == 5) {
                $from_date = date('Y-m');
                $from_date = $from_date.'-01';
                $to_date = date('Y-m-d');
            } elseif ($sort_dates == 6) {
                $from_month = date('m');
                $from_year = date('Y');
                if ($from_month == 2) {
                    $from_initial_date = 12;
                    $from_year -= 1;
                } elseif ($from_month == 1) {
                    $from_initial_date = 11;
                    $from_year -= 1;
                } else {
                    $from_initial_date = date('m') - 2;
                }
                if ($from_initial_date > 9) {
                    $from_date = $from_year.'-'.$from_initial_date.'-01';
                } else {
                    $from_date = $from_year.'-0'.$from_initial_date.'-01';
                }
                $to_date = date('Y-m-t');
            } elseif ($sort_dates == 7) {
                $from_month = date('m');
                $from_year = date('Y');
                if ($from_month == 2) {
                    $from_initial_date = 12;
                    $from_year -= 1;
                } elseif ($from_month == 1) {
                    $from_initial_date = 11;
                    $from_year -= 1;
                } else {
                    $from_initial_date = date('m') - 2;
                }
                if ($from_initial_date > 9) {
                    $from_date = $from_year.'-'.$from_initial_date.'-01';
                } else {
                    $from_date = $from_year.'-0'.$from_initial_date.'-01';
                }
                $to_date = date('Y-m-d');
            } elseif ($sort_dates == 8) {
                $from_year = date('Y');
                $from_month = date('m');
                $from_month_final = $from_month;
                $from_year_final = $from_year;
                if ($from_month == 12) {
                    $from_month_final = 01;
                } else {
                    $from_month_final += 1;
                    $from_year_final -= 1;
                }
                if ($from_month_final > 9) {
                    $from_date = $from_year_final.'-'.$from_month_final.'-01';
                } else {
                    $from_date = $from_year_final.'-0'.$from_month_final.'-01';
                }

                if ($from_month < 7) {
                    $to_month = ($from_month - 6) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 6;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }

                if ($from_month < 6) {
                    $to_month = ($from_month - 5) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 5;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    }
                }
                $second_to_date = date('Y-m-t');
            } elseif ($sort_dates == 9) {
                $from_year = date('Y');
                $from_month = date('m');
                $from_month_final = $from_month;
                $from_year_final = $from_year;
                if ($from_month == 12) {
                    $from_month_final = 01;
                } else {
                    $from_month_final += 1;
                    $from_year_final -= 1;
                }
                if ($from_month_final > 9) {
                    $from_date = $from_year_final.'-'.$from_month_final.'-01';
                } else {
                    $from_date = $from_year_final.'-0'.$from_month_final.'-01';
                }

                if ($from_month < 7) {
                    $to_month = ($from_month - 6) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 6;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }

                if ($from_month < 6) {
                    $to_month = ($from_month - 5) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 5;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    }
                }
                $second_to_date = date('Y-m-d');
            } elseif ($sort_dates == 10) {
                $from_date = date('Y-m-d', strtotime('-1 days'));
                $to_date = date('Y-m-d', strtotime('-1 days'));
            } elseif ($sort_dates == 11) {
                $from_date = date('Y-m-d', strtotime('last week Monday -1 day'));
                $to_date = date('Y-m-d', strtotime('last week Saturday'));
            } elseif ($sort_dates == 12) {
                $from_date = date('Y-m-d', strtotime('last week Monday -1 day'));
                $to_date = date('Y-m-d', strtotime('-7 days'));
            } elseif ($sort_dates == 13) {
                $from_date = date('Y-m-d', strtotime('first day of previous month'));
                $to_date = date('Y-m-d', strtotime('last day of previous month'));
            } elseif ($sort_dates == 14) {
                $from_date = date('Y-m-d', strtotime('first day of previous month'));
                $to_date = date('Y-m-d');
            } elseif ($sort_dates == 15) {
                $current_month = date('m');
                $current_year = date('Y');
                $from_year = $current_year;
                $to_year = $current_year;
                if ($current_month < 6) {
                    $from_month = ($current_month - 5) + 12;
                    $from_year = $current_year - 1;
                } else {
                    $from_month = $current_month - 5;
                }

                if ($current_month < 4) {
                    $to_month = ($current_month - 3) + 12;
                    $to_year = $current_year - 1;
                } else {
                    $to_month = $current_month - 3;
                }

                if ($from_month > 9) {
                    $from_date = $from_year.'-'.$from_month.'-01';
                } else {
                    $from_date = $from_year.'-0'.$from_month.'-01';
                }

                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }
            } elseif ($sort_dates == 16) {
                $current_month = date('m');
                $current_year = date('Y');
                $from_year = $current_year;
                $to_year = $current_year;
                if ($current_month < 6) {
                    $from_month = ($current_month - 5) + 12;
                    $from_year = $current_year - 1;
                } else {
                    $from_month = $current_month - 5;
                }

                if ($current_month < 4) {
                    $to_month = ($current_month - 3) + 12;
                    $to_year = $current_year - 1;
                } else {
                    $to_month = $current_month - 3;
                }

                if ($from_month > 9) {
                    $from_date = $from_year.'-'.$from_month.'-01';
                } else {
                    $from_date = $from_year.'-0'.$from_month.'-01';
                }

                if ($to_month > 9) {
                    $to_day = date('d');
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $to_day = date('d');
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }
            } elseif ($sort_dates == 17) {
                $from_year = date('Y') - 1;
                $from_month = date('m');
                $from_month_final = $from_month;
                $from_year_final = $from_year;
                if ($from_month == 12) {
                    $from_month_final = 01;
                } else {
                    $from_month_final += 1;
                    $from_year_final -= 1;
                }
                if ($from_month_final > 9) {
                    $from_date = $from_year_final.'-'.$from_month_final.'-01';
                } else {
                    $from_date = $from_year_final.'-0'.$from_month_final.'-01';
                }

                if ($from_month < 7) {
                    $to_month = ($from_month - 6) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 6;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }

                if ($from_month < 6) {
                    $to_month = ($from_month - 5) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 5;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    }
                }
                $second_to_year = date('Y') - 1;
                $second_to_month = date('m');
                if ($second_to_month > 9) {
                    $second_to_day = date('t');
                    if ($second_to_day > 9) {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-'.$second_to_day;
                    } else {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-0'.$second_to_day;
                    }
                } else {
                    $second_to_day = date('t');
                    if ($second_to_day > 9) {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-'.$second_to_day;
                    } else {
                        $second_to_date = $second_to_year.'-0'.$second_to_month.'-0'.$second_to_day;
                    }
                }
            } elseif ($sort_dates == 18) {
                $from_year = date('Y') - 1;
                $from_month = date('m');
                $from_month_final = $from_month;
                $from_year_final = $from_year;
                if ($from_month == 12) {
                    $from_month_final = 01;
                } else {
                    $from_month_final += 1;
                    $from_year_final -= 1;
                }
                if ($from_month_final > 9) {
                    $from_date = $from_year_final.'-'.$from_month_final.'-01';
                } else {
                    $from_date = $from_year_final.'-0'.$from_month_final.'-01';
                }

                if ($from_month < 7) {
                    $to_month = ($from_month - 6) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 6;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }

                if ($from_month < 6) {
                    $to_month = ($from_month - 5) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 5;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    }
                }
                $second_to_year = date('Y') - 1;
                $second_to_month = date('m');
                if ($second_to_month > 9) {
                    $second_to_day = date('d');
                    if ($second_to_day > 9) {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-'.$second_to_day;
                    } else {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-0'.$second_to_day;
                    }
                } else {
                    $second_to_day = date('d');
                    if ($second_to_day > 9) {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-'.$second_to_day;
                    } else {
                        $second_to_date = $second_to_year.'-0'.$second_to_month.'-0'.$second_to_day;
                    }
                }
            }
        }

        if ($vendor_id == 0) {
            $vendor_id = '';
        }

        $query_result = $this->inventory_model->get_purchase_item_graph_sort_date_type_v2($vendor_id, $from_date, $to_date, $this->session->userdata('user_location'));
        if (!empty($query_result)) {
            foreach ($query_result as $key => $value) {
                if (!in_array($value['item_id'], $equipment_array)) {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => $unit_of_measure_value['item_unit_value'],
                                                                'count_second' => 0,
                                                            );
                    } else {
                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => $unit_of_measure_value['item_unit_value'],
                                                                'count_second' => 0,
                                                            );
                    }
                } else {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $graph_data[$value['item_id']]['count'] += $unit_of_measure_value['item_unit_value'];
                    } else {
                        $graph_data[$value['item_id']]['count'] += $item_unit_measure_values[$item_id_unit_measure];
                    }
                }
            }
        }

        if ($second_from_date != '' && $second_to_date != '') {
            $second_query_result = $this->inventory_model->get_purchase_item_graph_sort_date_type_second_date_v2($vendor_id, $second_from_date, $second_to_date, $this->session->userdata('user_location'));
            if (!empty($second_query_result)) {
                foreach ($second_query_result as $key => $value) {
                    if (!in_array($value['item_id'], $equipment_array)) {
                        $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                        if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                            $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                            $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                            $equipment_array[] = $value['item_id'];
                            $graph_data[$value['item_id']] = array(
                                                                    'item_description' => $value['item_description'],
                                                                    'count' => $unit_of_measure_value['item_unit_value'],
                                                                    'count_second' => 0,
                                                                );
                        } else {
                            $equipment_array[] = $value['item_id'];
                            $graph_data[$value['item_id']] = array(
                                                                    'item_description' => $value['item_description'],
                                                                    'count' => $unit_of_measure_value['item_unit_value'],
                                                                    'count_second' => 0,
                                                                );
                        }
                    } else {
                        $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                        if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                            $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                            $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                            $graph_data[$value['item_id']]['count'] += $unit_of_measure_value['item_unit_value'];
                        } else {
                            $graph_data[$value['item_id']]['count'] += $item_unit_measure_values[$item_id_unit_measure];
                        }
                    }
                }
            }
        }

        if ($sort_dates == 0 || $sort_dates == 1 || $sort_dates == 3 || $sort_dates == 5 || $sort_dates == 7 || $sort_dates == 9 || $sort_dates == 14) {
            $current_month = date('m');
            $current_year = date('Y');
            $from_month = $current_month;
            $from_year = $current_year;
            if ($current_month == 12) {
                $from_month = 01;
            } else {
                $from_month += 1;
                $from_year -= 1;
            }
            if ($from_month > 9) {
                $ytd_from_date = $from_year.'-'.$from_month.'-01';
            } else {
                $ytd_from_date = $from_year.'-0'.$from_month.'-01';
            }

            if ($current_month < 7) {
                $to_month = ($current_month - 6) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 6;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                }
            }
            if ($current_month < 6) {
                $to_month = ($current_month - 5) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 5;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                }
            }
            $ytd_second_to_date = date('Y-m-d');
        } elseif ($sort_dates == 2) {
            $current_month = date('m');
            $current_year = date('Y');
            $from_month = $current_month;
            $from_year = $current_year;
            if ($current_month == 12) {
                $from_month = 01;
            } else {
                $from_month += 1;
                $from_year -= 1;
            }
            if ($from_month > 9) {
                $ytd_from_date = $from_year.'-'.$from_month.'-01';
            } else {
                $ytd_from_date = $from_year.'-0'.$from_month.'-01';
            }

            if ($current_month < 7) {
                $to_month = ($current_month - 6) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 6;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                }
            }
            if ($current_month < 6) {
                $to_month = ($current_month - 5) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 5;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                }
            }
            $ytd_second_to_date = (new DateTime('next Saturday'))->format('Y-m-d');
        } elseif ($sort_dates == 4 || $sort_dates == 6 || $sort_dates == 8) {
            $current_month = date('m');
            $current_year = date('Y');
            $from_month = $current_month;
            $from_year = $current_year;
            if ($current_month == 12) {
                $from_month = 01;
            } else {
                $from_month += 1;
                $from_year -= 1;
            }
            if ($from_month > 9) {
                $ytd_from_date = $from_year.'-'.$from_month;
            } else {
                $ytd_from_date = $from_year.'-0'.$from_month;
            }

            if ($current_month < 7) {
                $to_month = ($current_month - 6) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 6;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-'.$to_month;
                } else {
                    $ytd_to_date = $to_year.'-'.$to_month;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-0'.$to_month;
                } else {
                    $ytd_to_date = $to_year.'-0'.$to_month;
                }
            }
            if ($current_month < 6) {
                $to_month = ($current_month - 5) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 5;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-'.$to_month;
                } else {
                    $ytd_second_from_date = $to_year.'-'.$to_month;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-0'.$to_month;
                } else {
                    $ytd_second_from_date = $to_year.'-0'.$to_month;
                }
            }
            $ytd_second_to_date = date('Y-m');
        } elseif ($sort_dates == 10) {
            $current_month = date('m');
            $current_year = date('Y');
            $from_month = $current_month;
            $from_year = $current_year;
            if ($current_month == 12) {
                $from_month = 01;
            } else {
                $from_month += 1;
                $from_year -= 1;
            }
            if ($from_month > 9) {
                $ytd_from_date = $from_year.'-'.$from_month.'-01';
            } else {
                $ytd_from_date = $from_year.'-0'.$from_month.'-01';
            }

            if ($current_month < 7) {
                $to_month = ($current_month - 6) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 6;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                }
            }
            if ($current_month < 6) {
                $to_month = ($current_month - 5) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 5;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                }
            }
            $ytd_second_to_date = date('Y-m-d', strtotime('-1 days'));
        } elseif ($sort_dates == 11) {
            $current_month = date('m');
            $current_year = date('Y');
            $from_month = $current_month;
            $from_year = $current_year;
            if ($current_month == 12) {
                $from_month = 01;
            } else {
                $from_month += 1;
                $from_year -= 1;
            }
            if ($from_month > 9) {
                $ytd_from_date = $from_year.'-'.$from_month.'-01';
            } else {
                $ytd_from_date = $from_year.'-0'.$from_month.'-01';
            }

            if ($current_month < 7) {
                $to_month = ($current_month - 6) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 6;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                }
            }
            if ($current_month < 6) {
                $to_month = ($current_month - 5) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 5;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                }
            }
            $ytd_second_to_date = date('Y-m-d', strtotime('last week Saturday'));
        } elseif ($sort_dates == 12) {
            $current_month = date('m');
            $current_year = date('Y');
            $from_month = $current_month;
            $from_year = $current_year;
            if ($current_month == 12) {
                $from_month = 01;
            } else {
                $from_month += 1;
                $from_year -= 1;
            }
            if ($from_month > 9) {
                $ytd_from_date = $from_year.'-'.$from_month.'-01';
            } else {
                $ytd_from_date = $from_year.'-0'.$from_month.'-01';
            }

            if ($current_month < 7) {
                $to_month = ($current_month - 6) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 6;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                }
            }
            if ($current_month < 6) {
                $to_month = ($current_month - 5) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 5;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                }
            }
            $ytd_second_to_date = date('Y-m-d');
        } elseif ($sort_dates == 13) {
            $current_month = date('m');
            $current_year = date('Y');
            $from_month = $current_month;
            $from_year = $current_year;
            if ($current_month == 12) {
                $from_month = 01;
            } else {
                $from_month += 1;
                $from_year -= 1;
            }
            if ($from_month > 9) {
                $ytd_from_date = $from_year.'-'.$from_month;
            } else {
                $ytd_from_date = $from_year.'-0'.$from_month;
            }

            if ($current_month < 7) {
                $to_month = ($current_month - 6) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 6;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-'.$to_month;
                } else {
                    $ytd_to_date = $to_year.'-'.$to_month;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-0'.$to_month;
                } else {
                    $ytd_to_date = $to_year.'-0'.$to_month;
                }
            }
            if ($current_month < 6) {
                $to_month = ($current_month - 5) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 5;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-'.$to_month;
                } else {
                    $ytd_second_from_date = $to_year.'-'.$to_month;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-0'.$to_month;
                } else {
                    $ytd_second_from_date = $to_year.'-0'.$to_month;
                }
            }
            $ytd_second_to_date = date('Y-m', strtotime('previous month'));
        } elseif ($sort_dates == 15) {
            $current_month = date('m');
            $current_year = date('Y');
            $from_month = $current_month;
            $from_year = $current_year;
            if ($current_month == 12) {
                $from_month = 01;
            } else {
                $from_month += 1;
                $from_year -= 1;
            }
            if ($from_month > 9) {
                $ytd_from_date = $from_year.'-'.$from_month;
            } else {
                $ytd_from_date = $from_year.'-0'.$from_month;
            }

            if ($current_month < 7) {
                $to_month = ($current_month - 6) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 6;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-'.$to_month;
                } else {
                    $ytd_to_date = $to_year.'-'.$to_month;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-0'.$to_month;
                } else {
                    $ytd_to_date = $to_year.'-0'.$to_month;
                }
            }
            if ($current_month < 6) {
                $to_month = ($current_month - 5) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 5;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-'.$to_month;
                } else {
                    $ytd_second_from_date = $to_year.'-'.$to_month;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-0'.$to_month;
                } else {
                    $ytd_second_from_date = $to_year.'-0'.$to_month;
                }
            }
            $ytd_second_to_date = date('Y-m', strtotime('-3 months'));
        } elseif ($sort_dates == 16) {
            $current_month = date('m');
            $current_year = date('Y');
            $from_month = $current_month;
            $from_year = $current_year;
            if ($current_month == 12) {
                $from_month = 01;
            } else {
                $from_month += 1;
                $from_year -= 1;
            }
            if ($from_month > 9) {
                $ytd_from_date = $from_year.'-'.$from_month.'-01';
            } else {
                $ytd_from_date = $from_year.'-0'.$from_month.'-01';
            }

            if ($current_month < 7) {
                $to_month = ($current_month - 6) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 6;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                }
            }
            if ($current_month < 6) {
                $to_month = ($current_month - 5) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 5;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                }
            }
            $ytd_second_to_date_initial = date('Y-m', strtotime('-3 months'));
            $ytd_second_to_date_day = date('d');
            if ($ytd_second_to_date_day > 9) {
                $ytd_second_to_date = $ytd_second_to_date_initial.'-'.$ytd_second_to_date_day;
            } else {
                $ytd_second_to_date = $ytd_second_to_date_initial.'-0'.$ytd_second_to_date_day;
            }
        } elseif ($sort_dates == 17) {
            $current_month = date('m');
            $current_year = date('Y') - 1;
            $from_month = $current_month;
            $from_year = $current_year;
            if ($current_month == 12) {
                $from_month = 01;
            } else {
                $from_month += 1;
                $from_year -= 1;
            }
            if ($from_month > 9) {
                $ytd_from_date = $from_year.'-'.$from_month;
            } else {
                $ytd_from_date = $from_year.'-0'.$from_month;
            }

            if ($current_month < 7) {
                $to_month = ($current_month - 6) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 6;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-'.$to_month;
                } else {
                    $ytd_to_date = $to_year.'-'.$to_month;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-0'.$to_month;
                } else {
                    $ytd_to_date = $to_year.'-0'.$to_month;
                }
            }
            if ($current_month < 6) {
                $to_month = ($current_month - 5) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 5;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-'.$to_month;
                } else {
                    $ytd_second_from_date = $to_year.'-'.$to_month;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-0'.$to_month;
                } else {
                    $ytd_second_from_date = $to_year.'-0'.$to_month;
                }
            }
            $ytd_second_to_year = date('Y') - 1;
            $ytd_second_to_month = date('m');
            $ytd_second_to_date = $ytd_second_to_year.'-'.$ytd_second_to_month;
        } elseif ($sort_dates == 18) {
            $current_month = date('m');
            $current_year = date('Y') - 1;
            $from_month = $current_month;
            $from_year = $current_year;
            if ($current_month == 12) {
                $from_month = 01;
            } else {
                $from_month += 1;
                $from_year -= 1;
            }
            if ($from_month > 9) {
                $ytd_from_date = $from_year.'-'.$from_month.'-01';
            } else {
                $ytd_from_date = $from_year.'-0'.$from_month.'-01';
            }

            if ($current_month < 7) {
                $to_month = ($current_month - 6) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 6;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                } else {
                    $ytd_to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                }
            }
            if ($current_month < 6) {
                $to_month = ($current_month - 5) + 12;
                $to_year = $current_year - 1;
            } else {
                $to_month = $current_month - 5;
                $to_year = $current_year;
            }
            if ($to_month > 9) {
                $temp_date = $to_year.'-'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-'.$to_month.'-01';
                }
            } else {
                $temp_date = $to_year.'-0'.$to_month;
                $to_day = date('t', strtotime($temp_date));
                if ($to_day > 9) {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                } else {
                    $ytd_second_from_date = $to_year.'-0'.$to_month.'-01';
                }
            }
            $ytd_second_to_year = date('Y') - 1;
            $ytd_second_to_date = $ytd_second_to_year.'-'.date('m-d');
        }

        $second_date_first_result = $this->inventory_model->get_purchase_item_graph_sort_date_type_second_date_v2($vendor_id, $ytd_from_date, $ytd_to_date, $this->session->userdata('user_location'));
        if (!empty($second_date_first_result)) {
            foreach ($second_date_first_result as $key => $value) {
                if (!in_array($value['item_id'], $equipment_array)) {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => 0,
                                                                'count_second' => $unit_of_measure_value['item_unit_value'],
                                                            );
                    } else {
                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => 0,
                                                                'count_second' => $unit_of_measure_value['item_unit_value'],
                                                            );
                    }
                } else {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $graph_data[$value['item_id']]['count_second'] += $unit_of_measure_value['item_unit_value'];
                    } else {
                        $graph_data[$value['item_id']]['count_second'] += $item_unit_measure_values[$item_id_unit_measure];
                    }
                }
            }
        }
        $second_date_second_result = $this->inventory_model->get_purchase_item_graph_sort_date_type_second_date_v2($vendor_id, $ytd_second_from_date, $ytd_second_to_date, $this->session->userdata('user_location'));
        if (!empty($second_date_second_result)) {
            foreach ($second_date_second_result as $key => $value) {
                if (!in_array($value['item_id'], $equipment_array)) {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => 0,
                                                                'count_second' => $unit_of_measure_value['item_unit_value'],
                                                            );
                    } else {
                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => 0,
                                                                'count_second' => $unit_of_measure_value['item_unit_value'],
                                                            );
                    }
                } else {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $graph_data[$value['item_id']]['count_second'] += $unit_of_measure_value['item_unit_value'];
                    } else {
                        $graph_data[$value['item_id']]['count_second'] += $item_unit_measure_values[$item_id_unit_measure];
                    }
                }
            }
        }

        foreach ($graph_data as $key => $value) {
            $graph[] = array('label' => $value['item_description'], 'value' => $value['count'], 'second_value' => $value['count_second']);
        }

        if ($second_from_date != '' && $second_to_date != '') {
            $to_date = $second_to_date;
        }

        $data = array(
            'date_range_from' => $from_date,
            'graph' => $graph,
            'date_range_to' => $to_date,
            'ytd_from' => $ytd_from_date,
            'ytd_to' => $ytd_second_to_date,
        );

        $this->common->code = 0;
        $this->common->message = 'Purchase Item Graph YTD Comparison';
        $this->common->data = $data;

        $this->common->response(false);
    }

    public function get_purchase_item_graph_today_default_standard()
    {
        $vendor_id = '';
        $from_date = date('Y-m');
        $from = $from_date.'-01';
        $to = date('Y-m-d');
        $equipment_array = array();
        $graph_data = array();
        $graph = array();
        $data = array();
        $item_unit_measure_values = array();

        $query_result = $this->inventory_model->get_purchase_item_graph_sort_date_type_v2($vendor_id, $from, $to, $this->session->userdata('user_location'));
        if (!empty($query_result)) {
            foreach ($query_result as $key => $value) {
                if (!in_array($value['item_id'], $equipment_array)) {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => $unit_of_measure_value['item_unit_value'],
                                                            );
                    } else {
                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => $item_unit_measure_values[$item_id_unit_measure],
                                                            );
                    }
                } else {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $graph_data[$value['item_id']]['count'] += $unit_of_measure_value['item_unit_value'];
                    } else {
                        $graph_data[$value['item_id']]['count'] += $item_unit_measure_values[$item_id_unit_measure];
                    }
                }
            }
            foreach ($graph_data as $key => $value) {
                $graph[] = array('label' => $value['item_description'], 'value' => $value['count']);
            }
        }

        $data = array(
            'date_range_from' => $from,
            'graph' => $graph,
            'date_range_to' => $to,
        );

        $this->common->code = 0;
        $this->common->message = 'Purhcase Item Today';
        $this->common->data = $data;

        $this->common->response(false);
    }

    public function filter_purchase_item_graph_standard($filter_from, $filter_to, $vendor_id, $sort_dates, $filter_type)
    {
        $equipment_array = array();
        $graph_data = array();
        $graph = array();
        $data = array();
        $item_unit_measure_values = array();

        if ($filter_type == 1 || $filter_type == 3) {
            if ($filter_from != 0 && $filter_to != 0) {
                $from_date = $filter_from;
                $to_date = $filter_to;
            } else {
                $from_date = '';
                $to_date = '';
            }
        } elseif ($filter_type == 2) {
            $second_from_date = '';
            $second_to_date = '';
            if ($sort_dates == 0) {
                if ($filter_from != 0 && $filter_to != 0) {
                    $from_date = $filter_from;
                    $to_date = $filter_to;
                } else {
                    $from_date = '';
                    $to_date = '';
                }
            } elseif ($sort_dates == 1) {
                $from_date = date('Y-m-d');
                $to_date = $from_date;
            } elseif ($sort_dates == 2) {
                $from_date = (new DateTime('last Sunday'))->format('Y-m-d');
                $to_date = (new DateTime('next Saturday'))->format('Y-m-d');
            } elseif ($sort_dates == 3) {
                $from_date = (new DateTime('last Sunday'))->format('Y-m-d');
                $to_date = date('Y-m-d');
            } elseif ($sort_dates == 4) {
                $from_date = date('Y-m').'-01';
                $to_date = date('Y-m-t');
            } elseif ($sort_dates == 5) {
                $from_date = date('Y-m');
                $from_date = $from_date.'-01';
                $to_date = date('Y-m-d');
            } elseif ($sort_dates == 6) {
                $from_month = date('m');
                $from_year = date('Y');
                if ($from_month == 2) {
                    $from_initial_date = 12;
                    $from_year -= 1;
                } elseif ($from_month == 1) {
                    $from_initial_date = 11;
                    $from_year -= 1;
                } else {
                    $from_initial_date = date('m') - 2;
                }
                if ($from_initial_date > 9) {
                    $from_date = $from_year.'-'.$from_initial_date.'-01';
                } else {
                    $from_date = $from_year.'-0'.$from_initial_date.'-01';
                }
                $to_date = date('Y-m-t');
            } elseif ($sort_dates == 7) {
                $from_month = date('m');
                $from_year = date('Y');
                if ($from_month == 2) {
                    $from_initial_date = 12;
                    $from_year -= 1;
                } elseif ($from_month == 1) {
                    $from_initial_date = 11;
                    $from_year -= 1;
                } else {
                    $from_initial_date = date('m') - 2;
                }
                if ($from_initial_date > 9) {
                    $from_date = $from_year.'-'.$from_initial_date.'-01';
                } else {
                    $from_date = $from_year.'-0'.$from_initial_date.'-01';
                }
                $to_date = date('Y-m-d');
            } elseif ($sort_dates == 8) {
                $from_year = date('Y');
                $from_month = date('m');
                $from_month_final = $from_month;
                $from_year_final = $from_year;
                if ($from_month == 12) {
                    $from_month_final = 01;
                } else {
                    $from_month_final += 1;
                    $from_year_final -= 1;
                }
                if ($from_month_final > 9) {
                    $from_date = $from_year_final.'-'.$from_month_final.'-01';
                } else {
                    $from_date = $from_year_final.'-0'.$from_month_final.'-01';
                }

                if ($from_month < 7) {
                    $to_month = ($from_month - 6) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 6;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }

                if ($from_month < 6) {
                    $to_month = ($from_month - 5) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 5;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    }
                }
                $second_to_date = date('Y-m-t');
            } elseif ($sort_dates == 9) {
                $from_year = date('Y');
                $from_month = date('m');
                $from_month_final = $from_month;
                $from_year_final = $from_year;
                if ($from_month == 12) {
                    $from_month_final = 01;
                } else {
                    $from_month_final += 1;
                    $from_year_final -= 1;
                }
                if ($from_month_final > 9) {
                    $from_date = $from_year_final.'-'.$from_month_final.'-01';
                } else {
                    $from_date = $from_year_final.'-0'.$from_month_final.'-01';
                }

                if ($from_month < 7) {
                    $to_month = ($from_month - 6) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 6;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }

                if ($from_month < 6) {
                    $to_month = ($from_month - 5) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 5;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    }
                }
                $second_to_date = date('Y-m-d');
            } elseif ($sort_dates == 10) {
                $from_date = date('Y-m-d', strtotime('-1 days'));
                $to_date = date('Y-m-d', strtotime('-1 days'));
            } elseif ($sort_dates == 11) {
                $from_date = date('Y-m-d', strtotime('last week Monday -1 day'));
                $to_date = date('Y-m-d', strtotime('last week Saturday'));
            } elseif ($sort_dates == 12) {
                $from_date = date('Y-m-d', strtotime('last week Monday -1 day'));
                $to_date = date('Y-m-d', strtotime('-7 days'));
            } elseif ($sort_dates == 13) {
                $from_date = date('Y-m-d', strtotime('first day of previous month'));
                $to_date = date('Y-m-d', strtotime('last day of previous month'));
            } elseif ($sort_dates == 14) {
                $from_date = date('Y-m-d', strtotime('first day of previous month'));
                $to_date = date('Y-m-d');
            } elseif ($sort_dates == 15) {
                $current_month = date('m');
                $current_year = date('Y');
                $from_year = $current_year;
                $to_year = $current_year;
                if ($current_month < 6) {
                    $from_month = ($current_month - 5) + 12;
                    $from_year = $current_year - 1;
                } else {
                    $from_month = $current_month - 5;
                }

                if ($current_month < 4) {
                    $to_month = ($current_month - 3) + 12;
                    $to_year = $current_year - 1;
                } else {
                    $to_month = $current_month - 3;
                }

                if ($from_month > 9) {
                    $from_date = $from_year.'-'.$from_month.'-01';
                } else {
                    $from_date = $from_year.'-0'.$from_month.'-01';
                }

                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }
            } elseif ($sort_dates == 16) {
                $current_month = date('m');
                $current_year = date('Y');
                $from_year = $current_year;
                $to_year = $current_year;
                if ($current_month < 6) {
                    $from_month = ($current_month - 5) + 12;
                    $from_year = $current_year - 1;
                } else {
                    $from_month = $current_month - 5;
                }

                if ($current_month < 4) {
                    $to_month = ($current_month - 3) + 12;
                    $to_year = $current_year - 1;
                } else {
                    $to_month = $current_month - 3;
                }

                if ($from_month > 9) {
                    $from_date = $from_year.'-'.$from_month.'-01';
                } else {
                    $from_date = $from_year.'-0'.$from_month.'-01';
                }

                if ($to_month > 9) {
                    $to_day = date('d');
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $to_day = date('d');
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }
            } elseif ($sort_dates == 17) {
                $from_year = date('Y') - 1;
                $from_month = date('m');
                $from_month_final = $from_month;
                $from_year_final = $from_year;
                if ($from_month == 12) {
                    $from_month_final = 01;
                } else {
                    $from_month_final += 1;
                    $from_year_final -= 1;
                }
                if ($from_month_final > 9) {
                    $from_date = $from_year_final.'-'.$from_month_final.'-01';
                } else {
                    $from_date = $from_year_final.'-0'.$from_month_final.'-01';
                }

                if ($from_month < 7) {
                    $to_month = ($from_month - 6) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 6;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }

                if ($from_month < 6) {
                    $to_month = ($from_month - 5) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 5;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    }
                }
                $second_to_year = date('Y') - 1;
                $second_to_month = date('m');
                if ($second_to_month > 9) {
                    $second_to_day = date('t');
                    if ($second_to_day > 9) {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-'.$second_to_day;
                    } else {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-0'.$second_to_day;
                    }
                } else {
                    $second_to_day = date('t');
                    if ($second_to_day > 9) {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-'.$second_to_day;
                    } else {
                        $second_to_date = $second_to_year.'-0'.$second_to_month.'-0'.$second_to_day;
                    }
                }
            } elseif ($sort_dates == 18) {
                $from_year = date('Y') - 1;
                $from_month = date('m');
                $from_month_final = $from_month;
                $from_year_final = $from_year;
                if ($from_month == 12) {
                    $from_month_final = 01;
                } else {
                    $from_month_final += 1;
                    $from_year_final -= 1;
                }
                if ($from_month_final > 9) {
                    $from_date = $from_year_final.'-'.$from_month_final.'-01';
                } else {
                    $from_date = $from_year_final.'-0'.$from_month_final.'-01';
                }

                if ($from_month < 7) {
                    $to_month = ($from_month - 6) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 6;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-'.$to_month.'-0'.$to_day;
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $to_date = $to_year.'-0'.$to_month.'-'.$to_day;
                    } else {
                        $to_date = $to_year.'-0'.$to_month.'-0'.$to_day;
                    }
                }

                if ($from_month < 6) {
                    $to_month = ($from_month - 5) + 12;
                    $to_year = $from_year - 1;
                } else {
                    $to_month = $current_month - 5;
                    $to_year = $from_year;
                }
                if ($to_month > 9) {
                    $temp_date = $to_year.'-'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-'.$to_month.'-01';
                    }
                } else {
                    $temp_date = $to_year.'-0'.$to_month;
                    $to_day = date('t', strtotime($temp_date));
                    if ($to_day > 9) {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    } else {
                        $second_from_date = $to_year.'-0'.$to_month.'-01';
                    }
                }
                $second_to_year = date('Y') - 1;
                $second_to_month = date('m');
                if ($second_to_month > 9) {
                    $second_to_day = date('d');
                    if ($second_to_day > 9) {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-'.$second_to_day;
                    } else {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-0'.$second_to_day;
                    }
                } else {
                    $second_to_day = date('d');
                    if ($second_to_day > 9) {
                        $second_to_date = $second_to_year.'-'.$second_to_month.'-'.$second_to_day;
                    } else {
                        $second_to_date = $second_to_year.'-0'.$second_to_month.'-0'.$second_to_day;
                    }
                }
            }
        }

        if ($vendor_id == 0) {
            $vendor_id = '';
        }

        $query_result = $this->inventory_model->get_purchase_item_graph_sort_date_type_v2($vendor_id, $from_date, $to_date, $this->session->userdata('user_location'));
        if (!empty($query_result)) {
            foreach ($query_result as $key => $value) {
                if (!in_array($value['item_id'], $equipment_array)) {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => $unit_of_measure_value['item_unit_value'],
                                                            );
                    } else {
                        $equipment_array[] = $value['item_id'];
                        $graph_data[$value['item_id']] = array(
                                                                'item_description' => $value['item_description'],
                                                                'count' => $item_unit_measure_values[$item_id_unit_measure],
                                                            );
                    }
                } else {
                    $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                    if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                        $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                        $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                        $graph_data[$value['item_id']]['count'] += $unit_of_measure_value['item_unit_value'];
                    } else {
                        $graph_data[$value['item_id']]['count'] += $item_unit_measure_values[$item_id_unit_measure];
                    }
                }
            }
            if ($second_from_date == '' && $second_to_date == '') {
                foreach ($graph_data as $key => $value) {
                    $graph[] = array('label' => $value['item_description'], 'value' => $value['count']);
                }
            }
        }

        if ($second_from_date != '' && $second_to_date != '') {
            $second_query_result = $this->inventory_model->get_purchase_item_graph_sort_date_type_second_date_v2($vendor_id, $second_from_date, $second_to_date, $this->session->userdata('user_location'));
            if (!empty($second_query_result)) {
                foreach ($second_query_result as $key => $value) {
                    if (!in_array($value['item_id'], $equipment_array)) {
                        $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                        if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                            $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                            $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                            $equipment_array[] = $value['item_id'];
                            $graph_data[$value['item_id']] = array(
                                                                    'item_description' => $value['item_description'],
                                                                    'count' => $unit_of_measure_value['item_unit_value'],
                                                                );
                        } else {
                            $equipment_array[] = $value['item_id'];
                            $graph_data[$value['item_id']] = array(
                                                                    'item_description' => $value['item_description'],
                                                                    'count' => $item_unit_measure_values[$item_id_unit_measure],
                                                                );
                        }
                    } else {
                        $item_id_unit_measure = $value['item_unit_measure'].'_'.$value['item_id'];
                        if (!in_array($item_id_unit_measure, $item_unit_measure_values)) {
                            $unit_of_measure_value = $this->inventory_model->get_item_unit_measure_value($value['item_unit_measure'], $value['item_id']);
                            $item_unit_measure_values[$item_id_unit_measure] = $unit_of_measure_value['item_unit_value'];

                            $graph_data[$value['item_id']]['count'] += $unit_of_measure_value['item_unit_value'];
                        } else {
                            $graph_data[$value['item_id']]['count'] += $item_unit_measure_values[$item_id_unit_measure];
                        }
                    }
                }
                foreach ($graph_data as $key => $value) {
                    $graph[] = array('label' => $value['item_description'], 'value' => $value['count']);
                }
            }
        }

        if ($second_from_date != '' && $second_to_date != '') {
            $to_date = $second_to_date;
        }
        $data = array(
            'date_range_from' => $from_date,
            'graph' => $graph,
            'date_range_to' => $to_date,
        );

        $this->common->code = 0;
        $this->common->message = 'Item Usage';
        $this->common->data = $data;

        $this->common->response(false);
    }

    /*
     * @function : update_basic()
     * @desc : direct update on the fields of the table
     * @method : POST
                     id,field,value
     * @response : json
     */
    public function update_basic()
    {
        if ($this->input->post()) {
            $allowed_fields = array('item_asset_no', 'item_serial_no');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('value', 'Value', 'required');
            $this->form_validation->set_rules('pk', 'ID', 'required');
            if ($this->form_validation->run()) {
                $field = $this->input->post('name');
                $value = $this->input->post('value');
                $id = $this->input->post('pk');
                if (in_array($field, $allowed_fields)) {
                    $response = $this->inventory_model->mUpdateBasic($id, $field, $value);
                    if ($response) {
                        $this->common->code = 0;
                        $this->common->message = 'Successfully updated.';
                    }
                }
            } else {
                $this->common->message = validation_errors();
            }
        }
        $this->common->response(false);
    }

    public function update_reorder_no()
    {
        if ($this->input->post()) {
            $allowed_fields = array('item_reorder_no');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Name', 'required');
            $this->form_validation->set_rules('value', 'Value', 'required');
            $this->form_validation->set_rules('pk', 'ID', 'required');
            if ($this->form_validation->run()) {
                $field = $this->input->post('name');
                $value = $this->input->post('value');
                $id = $this->input->post('pk');

                if (in_array($field, $allowed_fields)) {
                    $response = $this->inventory_model->mUpdateReorderNumber($id, $field, $value);
                    if ($response) {
                        $this->common->code = 0;
                        $this->common->message = 'Successfully updated.';
                    }
                }
            } else {
                $this->common->message = validation_errors();
            }
        }
        $this->common->response(false);
    }

    public function purchase_order_requisition_details_v2($purchase_order_no, $order_req_id)
    {
        $data = array();

        $data['order_req_details'] = $this->inventory_model->get_order_req_details($purchase_order_no, $order_req_id);
        $req_receiving_first_batch = $this->inventory_model->get_req_receiving_first_batch_no($purchase_order_no, $order_req_id);
        $data['order_req_items'] = $this->inventory_model->get_req_receiving_first_batch($purchase_order_no, $order_req_id, $req_receiving_first_batch['req_receiving_batch_no']);

        $this->templating_library->set('title', 'Purchase Order Requisition Receiving');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('pages/purchase_order_inquiry_iframe', 'pages/purchase_order_inquiry_iframe', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    //@EQUIPMENT TRANSFER REQUISITION
    //Added by: Adrian
    //Added on: 11/19/18
    //--------------------------------------------------------------------------

    //Script for populating inventory_quantit in dme_inventory_item_warehouse_location
    public function set_inventory_quantity()
    {
        $item_inventory_warehouse_location_list = $this->inventory_model->get_all_item_warehouse_location();

        foreach ($item_inventory_warehouse_location_list as $key => $value) {
            $inventory_item_by_location_list = $this->inventory_model->get_inventory_item_by_location($value['company_item_no'], $value['item_location']);
            $value['inventory_quantity'] = count($inventory_item_by_location_list);
            $this->inventory_model->save_inventory_item_quantity($value);
        }
    }

    public function get_searched_item_equip_transfer_by_company_item_no($company_item_no, $item_location)
    {
        $response = array('data' => array());
        $response['item_cost'] = array();

        $response['item_details'] = $this->inventory_model->get_searched_item_equip_transfer_by_company_item_no($company_item_no, $item_location);
        if (!empty($response['item_details'])) {
            $response['item_description'] = $response['item_details'][0]['item_description'];
        }
        //$item_cost_result = $this->inventory_model->get_item_cost($response['item_details']['item_id']);

        echo json_encode($response);
    }

    public function get_searched_item_equip_transfer_by_item_description($item_description, $item_location)
    {
        $response = array('data' => array());
        $response['item_cost'] = array();
        $item_description = ltrim($item_description, '%20');
        $item_description = str_replace('%20', ' ', $item_description);

        $response['item_details'] = $this->inventory_model->get_searched_item_equip_transfer_by_item_description($item_description, $item_location);
        if (!empty($response['item_details'])) {
            $response['item_description'] = $response['item_details'][0]['item_description'];
            $response['company_item_no'] = $response['item_details'][0]['company_item_no'];
        }
        //$item_cost_result = $this->inventory_model->get_item_cost($response['item_details']['item_id']);

        echo json_encode($response);
    }

    public function get_reorder_no_by_vendor($company_item_no, $vendor_id)
    {
        $response = array('data' => array());

        $response['item_reorder_nos'] = $this->inventory_model->get_reorder_no_by_vendor($company_item_no, $vendor_id);

        echo json_encode($response);
    }

    public function get_item_unit_measurement_by_item_id($item_id)
    {
        $response = array('data' => array());

        $response['item_unit_measurements'] = $this->inventory_model->get_item_unit_measurement_by_item_id($item_id);

        echo json_encode($response);
    }

    public function get_serial_asset_no_by_item_id($item_id, $item_unit_measurement, $company_item_no, $item_location)
    {
        $response = array('data' => array());

        $response['serial_asset_nos'] = $this->inventory_model->get_serial_asset_no_by_item_id($item_id, $item_unit_measurement, $item_location);
        $response['equipment_cost'] = $this->inventory_model->get_item_cost_by_item_id($company_item_no, $item_unit_measurement, $item_id);
        echo json_encode($response);
    }

    public function save_equipment_transfer($item_counter)
    {
        if ($this->input->post()) {
            $post_data = $this->input->post();
            $this->form_validation->set_rules('item_location', 'Receiving Location', 'required');
            // $this->form_validation->set_rules('confirmation_no_input', 'Confirmation No.', 'required');
            if ($this->form_validation->run() === true) {
                $equipment_transfer_req = array(
                    'equip_transfer_date' => date('Y-m-d', strtotime($post_data['order_req_date'])),
                    'receiving_location' => $post_data['item_location'],
                    'transferring_location' => $post_data['location'],
                    'transfer_po_no' => $post_data['equip_transfer_order_no'],
                    'equip_req_grand_total' => $post_data['order_req_grand_total_amount'],
                    'person_placing_order' => $post_data['person_placing_order'],
                    'transfer_req_status' => 'pending',
                );
                $save_equip_transfer_order = $this->inventory_model->save_equip_transfer_order($equipment_transfer_req);

                for ($i = 1; $i < 14; ++$i) {
                    $item_id = 'order_req_item_id_'.$i;
                    if (!empty($post_data[$item_id])) {
                        $quantity_ordered = 'order_req_quantity_ordered_'.$i;
                        $temp_item_batch_no = strtotime(date('Y-m-d H:i:s tt'));
                        $req_receiving_batch_no = substr($temp_item_batch_no, 4, 10).'000';
                        $item_status = 'order_req_item_status_'.$i; //not yet
                        $item_cost = 'order_req_equipment_cost_'.$i;
                        $item_total_cost = 'order_req_total_cost_'.$i;
                        $item_batch_no = substr($temp_item_batch_no, 1, 10);
                        $item_unit_measure = 'order_req_item_unit_measurement_'.$i;
                        $equipment_transfer_req_receiving = array(
                            'item_id' => $post_data[$item_id],
                            'transfer_req_id' => $save_equip_transfer_order,
                            'req_item_quantity_ordered' => $post_data[$quantity_ordered],
                            'req_item_quantity_received' => 0,
                            'req_receiving_batch_no' => $req_receiving_batch_no,
                            'item_status' => 0,
                            'item_cost' => $post_data[$item_cost],
                            'item_total_cost' => $post_data[$item_total_cost],
                            'item_batch_no' => $item_batch_no,
                            'item_unit_measure' => $post_data[$item_unit_measure],
                        );

                        $save_equip_transfer_item = $this->inventory_model->save_equip_transfer_item($equipment_transfer_req_receiving);
                    }
                }

                $data = array(
                    'onhold_status' => 1,
                );
                foreach ($post_data['serial_asset_no'] as $key => $value) {
                    foreach ($value as $key2 => $value2) {
                        $inventory_item_temp = $this->inventory_model->get_inventory_item_by_id($value2);
                        $temp_inventory = array(
                            'item_id' => $inventory_item_temp['item_id'],
                            'order_req_id' => $inventory_item_temp['order_req_id'],
                            'transfer_req_id' => $save_equip_transfer_order,
                            'purchase_order_no' => $post_data['equip_transfer_order_no'],
                            'item_batch_no' => $inventory_item_temp['item_batch_no'],
                            'item_unit_measure' => $inventory_item_temp['item_unit_measure'],
                            'item_quantity' => $inventory_item_temp['item_quantity'],
                            'item_cost' => $inventory_item_temp['item_cost'],
                            'item_total_cost' => $inventory_item_temp['item_total_cost'],
                            'req_receiving_batch_no' => $inventory_item_temp['req_receiving_batch_no'],
                            'item_serial_no' => $inventory_item_temp['item_serial_no'],
                            'item_asset_no' => $inventory_item_temp['item_asset_no'],
                            'item_status_location' => $inventory_item_temp['item_status_location'],
                            'item_location' => $post_data['item_location'],
                            // 'inventory_item_status' => $inventory_item_temp['inventory_item_status'],
                            'inventory_item_status' => 1,
                            'onhold_status' => 0,
                        );
                        //$temp_inventory['item_location'] = $post_data['item_location'];
                        $this->inventory_model->save_inventory_item_etr($temp_inventory);
                        $this->inventory_model->update_inventory_item_status_etr($value2, $data);
                    }
                }
                $this->response_code = 0;
                $this->response_message = 'Equipment Transfer Requisition Saved Successfully.';
            } else {
                $this->response_message = validation_errors('<span></span>');
            }
            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));
            exit;
        }
    }

    public function equipment_transfer_status()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt') {
            // $data['equip_transfer_inquiries'] = $this->inventory_model->get_all_equip_transfer_status();
            $data['equip_transfer_inquiries'] = $this->inventory_model->get_all_equip_transfer_status($this->session->userdata('user_location'));
            $data['locations'] = $this->inventory_model->get_location_list();
            $this->templating_library->set_view('pages/equipment_transfer_status', 'pages/equipment_transfer_status', $data);
        }

        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function equipment_transfer_requisition_receiving($transfer_po_no, $transfer_req_id)
    {
        $data = array();

        $data['transfer_req_details'] = $this->inventory_model->get_equip_transfer_by_id($transfer_req_id);
        $transfer_req_receiving_details = $this->inventory_model->get_equip_transfer_receiving_details_by_id($transfer_req_id);
        $data['transfer_req_receiving_details'] = array();
        foreach($transfer_req_receiving_details as $key => $value) {
            $items = $this->inventory_model->get_equip_transfer_items_by_etr_no($transfer_req_id, $transfer_po_no, $value['item_id']);

            $serial_nos = "";
            $asset_nos = "";
            $new_inventory_item_ids = "";
            $old_inventory_item_ids = "";
            $counter = 0;
            foreach($items as $key2 => $value2){
                $transferred_item_no = $this->inventory_model->get_equip_transfer_item_no($value2['item_batch_no'], $value2['item_asset_no'], $value2['item_id']);
                if($counter == 0) {
                    $serial_nos = $value2['item_serial_no'];
                    $asset_nos = $value2['item_asset_no'];
                    $new_inventory_item_ids = $value2['inventory_item_id'];
                    $old_inventory_item_ids = $transferred_item_no['inventory_item_id'];
                } else {
                    $serial_nos = $serial_nos.', '.$value2['item_serial_no'];
                    $asset_nos = $asset_nos.', '.$value2['item_asset_no'];
                    $new_inventory_item_ids = $new_inventory_item_ids.','.$value2['inventory_item_id'];
                    $old_inventory_item_ids = $old_inventory_item_ids.','.$transferred_item_no['inventory_item_id'];
                }

                $counter++;
            }
            $details = array (
                'transfer_req_receiving_id' => $value['transfer_req_receiving_id'],
                'item_id' => $value['item_id'],
                'transfer_req_id' => $value['transfer_req_id'],
                'req_item_quantity_ordered' => $value['req_item_quantity_ordered'],
                'req_item_quantity_received' => $value['req_item_quantity_received'],
                'req_receiving_batch_no' => $value['req_receiving_batch_no'],
                'req_receiving_status' => $value['req_receiving_status'],
                'req_received_date' => $value['req_received_date'],
                'req_staff_member_receiving' => $value['req_staff_member_receiving'],
                'item_status' => $value['item_status'],
                'item_cost' => $value['item_cost'],
                'item_total_cost' => $value['item_total_cost'],
                'item_batch_no' => $value['item_batch_no'],
                'item_unit_measure' => $value['item_unit_measure'],
                'date_added' => $value['date_added'],
                'transfer_req_items' => $items,
                'serial_nos' => $serial_nos,
                'asset_nos' => $asset_nos,
                'inventory_item_ids' => $new_inventory_item_ids,
                'old_inventory_item_ids' => $old_inventory_item_ids
            );
            $data['transfer_req_receiving_details'][] = $details;
        }
        $this->templating_library->set('title', 'Equipment Transfer Requisition Receiving');
        $this->templating_library->set_view('pages/equipment_transfer_requisition_receiving', 'pages/equipment_transfer_requisition_receiving', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
    }

    public function equipment_transfer_requisition()
    {
        $this->templating_library->set('title', 'Inventory');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        $data['vendor_list'] = $this->inventory_model->get_vendor_list();
        $data['location_list'] = $this->inventory_model->get_location_list();

        $this->templating_library->set_view('pages/equipment_transfer_requisition', 'pages/equipment_transfer_requisition', $data);
        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function equipment_transfer_requisition_details($transfer_req_id, $transfer_po_no)
    {
        $data = array();

        $data['transfer_req_details'] = $this->inventory_model->get_equip_transfer_by_id($transfer_req_id);

        $data['transfer_req_id'] = $transfer_req_id;
        $data['transfer_po_no'] = $transfer_po_no;
        //$data['transfer_req_receiving_details'] = $this->inventory_model->get_equip_transfer_receiving_details_by_id($transfer_req_id);
        $transfer_req_receiving_details = $this->inventory_model->get_equip_transfer_receiving_details_by_id($transfer_req_id);
        $data['transfer_req_receiving_details'] = array();
        foreach($transfer_req_receiving_details as $key => $value) {
            $items = $this->inventory_model->get_equip_transfer_items_by_etr_no($transfer_req_id, $transfer_po_no, $value['item_id']);
            $serial_nos = "";
            $asset_nos = "";
            $counter = 0;
            foreach($items as $key2 => $value2){
                if($counter == 0) {
                    $serial_nos = $value2['item_serial_no'];
                    $asset_nos = $value2['item_asset_no'];
                } else {
                    $serial_nos = $serial_nos.', '.$value2['item_serial_no'];
                    $asset_nos = $asset_nos.', '.$value2['item_asset_no'];
                }

                $counter++;
            }
            $details = array (
                'transfer_req_receiving_id' => $value['transfer_req_receiving_id'],
                'item_id' => $value['item_id'],
                'transfer_req_id' => $value['transfer_req_id'],
                'req_item_quantity_ordered' => $value['req_item_quantity_ordered'],
                'req_item_quantity_received' => $value['req_item_quantity_received'],
                'req_receiving_batch_no' => $value['req_receiving_batch_no'],
                'req_receiving_status' => $value['req_receiving_status'],
                'req_received_date' => $value['req_received_date'],
                'req_staff_member_receiving' => $value['req_staff_member_receiving'],
                'item_status' => $value['item_status'],
                'item_cost' => $value['item_cost'],
                'item_total_cost' => $value['item_total_cost'],
                'item_batch_no' => $value['item_batch_no'],
                'item_unit_measure' => $value['item_unit_measure'],
                'date_added' => $value['date_added'],
                'transfer_req_items' => $items,
                'serial_nos' => $serial_nos,
                'asset_nos' => $asset_nos
            );
            $data['transfer_req_receiving_details'][] = $details;
        }

        $this->templating_library->set('title', 'Purchase Order Requisition Receiving');
        $this->templating_library->set_view('pages/equipment_transfer_details', 'pages/equipment_transfer_details', $data);
        $this->templating_library->set_view('common/custom-scripts', 'common/custom-scripts');
    }

    public function cancel_equipment_transfer($transfer_req_id, $transfer_po_no) {
        $transfer_req_receiving_details = $this->inventory_model->get_equip_transfer_receiving_details_by_id($transfer_req_id);
        $data['transfer_req_receiving_details'] = array();
        foreach($transfer_req_receiving_details as $key => $value) {
            $items = $this->inventory_model->get_equip_transfer_items_by_etr_no($transfer_req_id, $transfer_po_no, $value['item_id']);
            $serial_nos = "";
            $asset_nos = "";
            $counter = 0;
            foreach($items as $key2 => $value2){
                // if($counter == 0) {
                //     $serial_nos = $value2['item_serial_no'];
                //     $asset_nos = $value2['item_asset_no'];
                // } else {
                //     $serial_nos = $serial_nos.', '.$value2['item_serial_no'];
                //     $asset_nos = $asset_nos.', '.$value2['item_asset_no'];
                // }

                // $counter++;
                $data_cancelled = array (
                    'onhold_status' => 0
                );
                $this->inventory_model->cancel_equip_transfer_req_items($value['item_id'], $value2['item_asset_no'], $data_cancelled);
            }
        }

        $this->inventory_model->delete_equip_transfer_req_items($transfer_req_id);
        $this->inventory_model->delete_equip_transfer_req_receiving($transfer_req_id);
        $response = $this->inventory_model->delete_equip_transfer_req($transfer_req_id);

        if ($response) {
            $this->response = 0;
            $this->response_message = 'Equipment Transfer Requisition Successfully cancelled.';

        } else {
            $this->response = 1;
            $this->response_message = 'Cancelellation failed.';
        }

        echo json_encode(array(
            'error' => $this->response,
            'message' => $this->response_message,
        ));
    }

    public function confirm_equipment_transfer_order_requisition()
    {
        if ($this->input->post()) {
            $data_post = $this->input->post();

            $this->form_validation->set_rules('order_req_date', 'Date', 'required');
            $this->form_validation->set_rules('location', 'Location', 'required');
            $this->form_validation->set_rules('receiving_location', 'Receiving Location', 'required');
            $this->form_validation->set_rules('person_placing_order', 'Representative Created Order', 'required');
            $this->form_validation->set_rules('transfer_po_no', 'Transfer PO No.', 'required');
            $this->form_validation->set_rules('order_req_received_date', 'Received Date', 'required');
            $this->form_validation->set_rules('order_req_staff_member_receiving', 'Representative Receiving PO Req', 'required');
            $this->form_validation->set_rules('order_req_grand_total', 'Total', 'required');

            if ($this->form_validation->run() === true) {
                $received_all = true;
                $row_id = 1;
                foreach($data_post['qty_received'] as $key => $value){
                    $item_status = 'item_status_'.$row_id;
                    if(($data_post[$item_status] == 0)) {
                        if($value == "") {
                            $received_all = false;
                            break;
                        }
                    }

                    $row_id++;
                }

                if($received_all) {
                    $row_counter = 1;
                    $i_counter = 0;
                    foreach($data_post['etr_inventory_item_ids'] as $key => $value) {
                        $item_status = 'item_status_'.$row_counter;

                        $asset_nos_per = $data_post['etr_asset_nos'][$i_counter];
                        $transferred_inventory_item_ids_per = $data_post['etr_old_inventory_item_ids'][$key];
                        if($data_post[$item_status] == 0) {
                            $inventory_item_nos = explode(",",$value);
                            $asset_nos = explode(",",$asset_nos_per);
                            $transferred_inventory_item_ids = explode(",",$transferred_inventory_item_ids_per);
                            $asset_counter = 0;
                            foreach($inventory_item_nos as $key2 => $value2) {
                                $asset_no = str_replace(' ', '', $asset_nos[$asset_counter]);
                                $inventory_item_id = str_replace(' ', '', $value2);
                                $transferred_inventory_item_id = str_replace(' ','', $transferred_inventory_item_ids[$asset_counter]);
                                $data = array();
                                $data = array (
                                    'inventory_item_status' => 1,
                                    'onhold_status' => 0
                                );
                                $this->inventory_model->update_inventory_item_status_etr_v3($transferred_inventory_item_id, $data);

                                //submit_inventory_item_removal
                                $data = array();
                                $data = array (
                                    'inventory_item_id' => $transferred_inventory_item_id,
                                    'removal_reason' => 'transferred',
                                    'date_out_of_service' => date('Y-m-d')
                                );
                                $this->inventory_model->submit_inventory_item_removal($data);

                                $data = array();
                                $data = array(
                                    'inventory_item_status' => 0
                                );
                                // $this->inventory_model->update_new_inventory_item_status_etr($data_post['transfer_req_id'], $asset_no, $data);
                                $this->inventory_model->update_inventory_item_status_etr_v3($inventory_item_id, $data);
                                $asset_counter++;
                            }

                            $data = array();
                            $data = array (
                                'req_item_quantity_received' => $data_post['qty_received'][$i_counter],
                                'req_receiving_status' => 'received',
                                'req_staff_member_receiving' => $data_post['order_req_staff_member_receiving']
                            );
                            $this->inventory_model->update_equip_transfer_req_receiving($data_post['etr_receiving_id'][$i_counter], $data);


                        } else {
                            $transferred_inventory_item_ids = explode(",",$transferred_inventory_item_ids_per);
                            foreach($transferred_inventory_item_ids as $key2 => $value2) {
                                $inventory_item_id = str_replace(' ', '', $value2);
                                $data = array();
                                $data = array (
                                    'onhold_status' => 0
                                );
                                $this->inventory_model->update_inventory_item_status_etr_v3($inventory_item_id, $data);
                            }
                        }
                        $i_counter++;
                        $row_counter++;
                    }


                    $data = array();
                    $data = array(
                        'transfer_req_status' => 'received',
                        'transfer_received_date' => $data_post['order_req_received_date']
                    );
                    $response = $this->inventory_model->update_equip_transfer_req($data_post['transfer_req_id'], $data);


                    if ($response == true) {
                        $this->response_code = 0;
                        $this->response_message = 'Equipment Transfer Requisition Confirmed Successfully.';
                    } else {
                        $this->response_code = 1;
                        $this->response_message = 'Error!';
                    }

                } else {
                    $this->response_code = 1;
                    $this->response_message = "All items must be received!";
                }
            } else {
                $this->response_code = 1;
                $this->response_message = validation_errors('<span></span>');
            }
        }

        echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
        exit;
    }

    public function cancel_equipment_transfer_item($transfer_req_receiving_id, $data = array()) {
        $response = array('data' => array());

        $data = array(
            'item_status' => 1,
        );

        $response = $this->inventory_model->update_equip_transfer_req_receiving($transfer_req_receiving_id, $data);

        echo json_encode($response);
    }

    public function retrieve_equipment_transfer_item($transfer_req_receiving_id, $data = array()) {
        $response = array('data' => array());

        $data = array(
            'item_status' => 0,
        );

        $response = $this->inventory_model->update_equip_transfer_req_receiving($transfer_req_receiving_id, $data);

        echo json_encode($response);
    }

    public function get_equip_transfer_status_list()
    {
        $response_data = array(
            'data' => array(),
            'draw' => 1,
            'recordsFiltered' => 0,
            'recordsTotal' => 0,
        );

        if ($this->input->is_ajax_request()) {
            $datatable = $this->input->get();
            $start = $datatable['start'];
            $limit = $datatable['length'];
            $filters = array(
                'search_item_fields_equip_transfer_status' => $datatable['search']['value'],
            );

            $column = array(
                'order_date',
                'receiving_branch',
                'transfer_no',
                'order_status',
                'receive_date',
            );
            $filters[$column[$datatable['order'][0]['column']]] = $datatable['order'][0]['dir'];
            $result = $this->inventory_model->get_equip_transfer_list($filters, $this->session->userdata('user_location'), $start, $limit);

            if ($result['totalCount'] > 0) {
                foreach ($result['result'] as $key => $value) {
                    $value['order_date'] = date("m/d/Y", strtotime($value['date_added']));
                    $value['receiving_branch'] = $value['user_city'].', '.$value['user_state'];
                    $value['transfer_no'] = '<span'.
                                ' class="view_transfer_order"'.
                                ' data-transfer-order-no="'.$value['transfer_po_no'].'"'.
                                ' data-transfer-req-id="'.$value['transfer_req_id'].'">'.
                                substr($value['transfer_po_no'], 3, 10).
                                '</span>';
                    $status_val = "";
                    if($value['transfer_req_status'] == 'pending') {
                        $status_val = 'PENDING';

                    } else if($value['transfer_req_status'] == 'received-partial') {
                        $status_val = 'RECEIVED PARTIAL';
                    } else if($value['transfer_req_status'] == 'received') {
                        $status_val = 'RECEIVED';
                    }
                    $value['order_status'] = $status_val;
                    if ($value['transfer_received_date'] != '0000-00-00') {
                        $value['receive_date'] = date("m/d/Y", strtotime($value['transfer_received_date']));
                    } else {
                        $value['receive_date'] = "";
                    }
                    $value['cancel_button'] = '<button'.
                                ' type="button"'.
                                ' class="btn btn-danger  btn-xs cancel_transfer_req"'.
                                ' data-transfer-order-no="'.$value['transfer_po_no'].'"'.
                                ' data-transfer-req-id="'.$value['transfer_req_id'].'"'.
                                ' autocomplete="off">Cancel</button>';
                    $response_data['data'][] = $value;
                }
            }

            $response_data['draw'] = $datatable['draw'];
            $response_data['recordsFiltered'] = $result['totalCount'];
            $response_data['recordsTotal'] = $result['totalCount'];
        }
        echo json_encode($response_data);
    }

    public function get_equipment_transfer_all_items() {
        $response_data = array(
            'data' => array(),
            'draw' => 1,
            'recordsFiltered' => 0,
            'recordsTotal' => 0,
        );

        if ($this->input->is_ajax_request()) {
            $datatable = $this->input->get();
            $start = $datatable['start'];
            $limit = $datatable['length'];
            $filters = array(
                'search_item_fields_all' => $datatable['search']['value'],
            );

            $column = array(
                'company_item_no',
                'item_description',
                'vendor_name',
                'item_reorder_no',
                'item_unit_measure',
                'item_serial_no',
                'item_asset_no',
                'item_cost'
            );
            $filters[$column[$datatable['order'][0]['column']]] = $datatable['order'][0]['dir'];
            $result = $this->inventory_model->get_all_inventory_items($filters, $this->session->userdata('user_location'), $start, $limit);
            $counter = 1;
            if ($result['totalCount'] > 0) {
                foreach ($result['result'] as $key => $value) {
                    $value['checkboxed'] = '<td><label class="i-checks data_tooltip">'.
                                '<input type="checkbox"  name="" class="all_items_checkbox all_items_checkbox_'.$counter.'" data-company-item-no="'.$value['company_item_no'].'"'.
                                'data-item-description="'.$value['item_description'].'"'.
                                'data-vendor-name="'.$value['vendor_name'].'"'.
                                'data-item-reorder-no="'.$value['item_reorder_no'].'"'.
                                'data-item-unit-measure="'.$value['item_unit_measure'].'"'.
                                'data-serial-number="'.$value['item_serial_no'].'"'.
                                'data-asset-number="'.$value['item_asset_no'].'"'.
                                'data-item-cost="'.$value['item_cost'].'"'.
                                'data-inventory-item-id="'.$value['inventory_item_id'].'"'.
                                'data-item-no="'.$value['item_id'].'"'.
                                ' value="'.$value['inventory_item_id'].'"'.
                                '/>'.
                                '<i></i>'.
                                '</label></td>';
                    $response_data['data'][] = $value;
                    $counter++;
                }
            }

            $response_data['draw'] = $datatable['draw'];
            $response_data['recordsFiltered'] = $result['totalCount'];
            $response_data['recordsTotal'] = $result['totalCount'];
        }
        echo json_encode($response_data);
    }

    public function get_all_inventory_items() {
        //get_all_inventory_items
        $all = $this->inventory_model->get_all_inventory_items(2);
        print_me($all);

    }

    //--------------------------------------------------------------------------
    //End of EQUIPMENT TRANSFER REQUISITION

}
