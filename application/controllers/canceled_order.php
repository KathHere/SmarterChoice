<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ;?>
<?php
Class canceled_order extends Ci_Controller
{
    public $response_code = 1; //false or error default
    public $response_message = '';
    public $response_data = array();

    public function __construct()
    {
        parent::__construct();
        is_logged_in();

        date_default_timezone_set('America/Los_Angeles');
        $this->load->model('canceled_order_model');
        $this->load->library('encryption');
        $this->load->library('input');
    }

    public function canceled()
    {
        // $trash = $this->canceled_order_model->get_canceled_v2($this->session->userdata('user_location'));
        // $newarray = array();

        // foreach($trash as $key => $value)
        // {
        //     $data = json_decode($value['data_deleted'], true);

        //     $value['equipmentID'] = $data[0]['equipmentID'];
        //     $value['status_activity_typeid'] = $data[0]['activity_typeid'];
        //     $value['uniqueID'] = $data[0]['uniqueID'];
        //     $value['hospiceID'] = $data[0]['organization_id'];
        //     $value['medical_record_id'] = $value['deleted_medical_id'];

        //     $newarray[] = $value;
        // }

        // $data['trashes'] = $newarray;
        $data['active_nav'] = "canceled";

        $this->templating_library->set('title','Canceled Orders');
        $this->templating_library->set_view('common/head','common/head');
        $this->templating_library->set_view('common/header','common/header');
        $this->templating_library->set_view('common/nav','common/nav', $data);

        // DME User Access/Restriction
        if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor') {
            $this->templating_library->set_view('pages/canceled_orders','pages/canceled_orders' , $data);
        }

        $this->templating_library->set_view('common/footer','common/footer');
        $this->templating_library->set_view('common/foot','common/foot');
    }

    public function get_canceled_orders(){
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
                "search_item_fields_canceled_work_orders" => $datatable['search']['value']
            );

            $column = array(
                "canceled_work_order_by_medical_record_no",
                "canceled_work_order_by_last_name",
                "canceled_work_order_by_first_name",
                "canceled_work_order_by_canceled_by",
                "canceled_work_order_by_date_deleted"
            );
            $filters[$column[$datatable["order"][0]["column"]]] = $datatable["order"][0]["dir"];
            $result = $this->canceled_order_model->get_canceled_v2($filters,$this->session->userdata('user_location'),$start,$limit);

            if($result['totalCount']>0)
            {
                foreach ($result['result'] as $key => $value)
                {
                    $data_decoded = json_decode($value['data_deleted'], true);

                    $value['select_work_order'] = '<label class="i-checks"><input type="checkbox" class="form-control delete-work-order-permanently" value="'.
                    ''.$value['trash_id'].''.
                    '" /><i></i></label>';
                    $value['medical_record_id'] = $value['medical_record_id'];
                    $value['customer_last_name'] = $value['p_lname'];
                    $value['customer_first_name'] = $value['p_fname'];
                    $value['customer_canceled_by'] = $value['canceled_by'];
                    $value['date_deleted'] = date("m/d/Y h:i a", strtotime($value['date_deleted']));
                    $value['work_order'] = '<a href="javascript:void(0)" class="view_order_details data_tooltip" title="Click to View Work Order" data-id="'.
                    ''.$value['medical_record_id'].'"'.
                    'data-value="'.$data_decoded[0]['organization_id'].'"'.
                    'data-unique-id="'.$data_decoded[0]['uniqueID'].'"'.
                    'data-act-id="'.$data_decoded[0]['activity_typeid'].'"'.
                    'data-equip-id="'.$data_decoded[0]['equipmentID'].'"'.
                    'data-patient-id="'.$value['patientID'].'">'.
                    '<button class="btn btn-info btn-sm" style="width:70px;">'.
                    ''.substr($data_decoded[0]['uniqueID'],4,10).''.
                    '</button> </a>';

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

}

?>
