<?php

class item_grouping extends Ci_Controller
{
    public $response_code = 1; //false or error default
    public $response_message = '';
    public $response_data = array();

    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        date_default_timezone_set('America/Los_Angeles');
        $this->load->model('item_grouping_model');
        $this->load->library('encryption');
        $this->load->library('input');
    }


    public function item_groups()
    {
        $this->templating_library->set('title', 'Inventory - Item Groups');
        $this->templating_library->set_view('common/head', 'common/head');
        $this->templating_library->set_view('common/header', 'common/header');
        $this->templating_library->set_view('common/nav', 'common/nav');

        $data = array();
        $data['item_group_list'] = $this->item_grouping_model->get_all_item_groups();

        $this->templating_library->set_view('pages/inventory/item_groups', 'pages/inventory/item_groups', $data);
        $this->templating_library->set_view('common/footer', 'common/footer');
        $this->templating_library->set_view('common/foot', 'common/foot');
    }

    public function add_item_group()
    {
        if ($this->input->post()) {
            $post_data = $this->input->post();

            $item_group_data = array(
                'item_group_name' => $post_data['item_group_name']
            );
            $response = $this->item_grouping_model->add_item_group($item_group_data);

            if ($response) {
                $this->response_code = 0;
                $this->response_message = 'Item group added.';
            } else {
                $this->response_code = 1;
                $this->response_message = 'Error adding item group!';
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));
            exit;
        }
    }

    public function update_item_group_details()
    {
        if ($this->input->post()) {
            $post_data = $this->input->post();

            $new_item_group_data = array(
                'item_group_name' => $post_data['item_group_name']
            );
            $response = $this->item_grouping_model->update_item_group_details($new_item_group_data, $post_data['item_group_id']);

            if ($response) {
                $this->response_code = 0;
                $this->response_message = 'Item group updated.';
            } else {
                $this->response_code = 1;
                $this->response_message = 'Error updating item group!';
            }

            echo json_encode(array(
                'error' => $this->response_code,
                'message' => $this->response_message,
            ));
            exit;
        }
    }

    public function delete_item_group($order_req_id="")
    {
        $return = $this->item_grouping_model->delete_item_group($order_req_id);

        if ($return) {
            $this->response_code = 0;
            $this->response_message = 'Item group deleted.';
        } else {
            $this->response_code = 1;
            $this->response_message = 'Error deleting item group!';
        }

        echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
        ));
        exit;
    }

}
