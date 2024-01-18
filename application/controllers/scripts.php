<?php

/*
| @desc : all scripts or commandline related that connects to CI
|		  restricted to run on web browser
| @date : 02.04.2016
|
*/
class Scripts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //disable access from any browser client
        //	echo "entered";
        if (!$this->input->is_cli_request()) {
            echo 'not found.';
            exit;
        }
        // date_default_timezone_set("America/Los_Angeles");
        $this->load->model('order_model');
        $this->load->model('billing_statement_model');
    }

    public function testing2()
    {
        echo 'hello world!'.PHP_EOL;
        exit;
    }

    public function update_dme_order_status_fields()
    {
        $query = 'select distinct pickup_date,addressID,organization_id, uniqueID, medical_record_id,ordered_by,actual_order_date,original_activity_typeid,date_ordered from dme_order';
        $response = $this->db->query($query)->result_array();
        foreach ($response as $key => $val) {
            $pickup_date = $val['pickup_date'];
            $addressID = $val['addressID'];
            $organization_id = $val['organization_id'];
            $uniqueID = $val['uniqueID'];
            $medical_record_id = $val['medical_record_id'];
            $ordered_by = $val['ordered_by'];
            $actual_order_date = $val['actual_order_date'];
            $original_activity_typeid = $val['original_activity_typeid'];
            $date_ordered = $val['date_ordered'];

            $update = "update dme_order_status set ordered_by='{$ordered_by}',actual_order_date='{$actual_order_date}',original_activity_typeid='{$original_activity_typeid}', date_ordered='{$date_ordered}' WHERE order_uniqueID='{$uniqueID}' AND medical_record_id='{$medical_record_id}'";
            echo $update." \n";
            echo "Updating {$uniqueID} with medical record {$medical_record_id} ...";
            if ($this->db->query($update)) {
                echo " successfully updated. \n";
            } else {
                echo " failed to update. \n";
            }
        }
    }

    public function update_order_status_deliver_to_type()
    {
        $query = 'select uniqueID,deliver_to_type,canceled_order,pickup_order,canceled_from_confirming from dme_order group by uniqueID';
        $response = $this->db->query($query)->result_array();
        $countupdated = 0;
        foreach ($response as $key => $val) {
            $this->db->where('order_uniqueID', $val['uniqueID']);
            $update = $this->db->update('dme_order_status', array(
                                                                                                                            'deliver_to_type' => $val['deliver_to_type'],
                                                                                                                            'cancelled_order' => $val['canceled_order'],
                                                                                                                            'pickup_order' => $val['pickup_order'],
                                                                                                                            'canceled_from_confirming' => $val['canceled_from_confirming'],
                                                                                                                        ));
            if ($update) {
                ++$countupdated;
            } else {
                echo "Could not update {$val['uniqueID']} \n";
            }
        }
        echo "{$countupdated} records updated.";
    }

    /*
     * @func        : process_patient_status()
     * @description : description
     * @params      : param1,param2
     * @return      : return
     * @author      : JR Flores
     * @date        : ${-MMMM Do YYYY, h:mm:ss a}
     */
    public function process_patient_status_cron()
    {
        $query = ' SELECT * from dme_patient_processing WHERE for_processing=1';
        $result = $this->db->query($query)->result_array();
        $countupdated = 0;
        $inactive = 0;
        foreach ($result as $val) {
            $patient_id = $val['patientID'];
            $patient_mr = $val['medical_record_id'];
            $patient_hospice_id = $val['hospice_id'];
            ++$countupdated;
            if (!process_patient_deactivation($patient_id, $patient_mr, $patient_hospice_id)) {
                echo "not active \n";

                //save new inactive patient
                $datatosave = array(
                                                    'patientID' => $patient_id,
                                                    'is_active' => 0,
                                                    'date_added' => date('Y-m-d H:i:s'),
                                                );
                $this->db->insert('dme_patient_activation', $datatosave);

                ++$inactive;
            }

            //delete after process
            $this->db->where('patient_processing_id', $val['patient_processing_id']);
            $this->db->delete('dme_patient_processing');
        }

        echo "{$countupdated} total records, {$inactive} inactive patient. \n";
    }

    /* END OF process_patient_status */

    /*
     * @func        : process_patient_status_onetime()
     * @description : description
     * @params      : param1,param2
     * @return      : return
     * @author      : JR Flores
     * @date        : ${-MMMM Do YYYY, h:mm:ss a}
     */
    public function process_patient_status_onetime()
    {
        $query = ' SELECT * from dme_patient';
        $result = $this->db->query($query)->result_array();
        $countupdated = 0;
        $inactive = 0;
        foreach ($result as $val) {
            $patient_id = $val['patientID'];
            $patient_mr = $val['medical_record_id'];
            $patient_hospice_id = $val['ordered_by'];
            ++$countupdated;
            echo "patient id: {$patient_id}, mr id: {$patient_mr}, hospice id : {$patient_hospice_id} \n";
            if (!process_patient_deactivation($patient_id, $patient_mr, $patient_hospice_id)) {
                echo "not active \n";
                ++$inactive;
            }
        }
        echo "{$countupdated} total records, {$inactive} inactive patient. \n";
    }

    /* END OF process_patient_status_onetime */

    /*
    | @func : updatept_creationdate
    | @desc : update existing patient that doesn't have date of creation
    | @date : 02.04.2016
    |
    |
    */
    public function updatept_creationdate()
    {
        $this->db->select('patientID,organization_id,date_ordered');
        $this->db->from('dme_order');
        $this->db->order_by('date_ordered', 'ASC');
        $this->db->group_by('patientID');
        $query = $this->db->get();

        $response = array();

        foreach ($query->result_array() as $key => $value) {
            $response[] = array(
                            'patientID' => $value['patientID'],
                            'date_created' => $value['date_ordered'],
                            'hospice_id' => $value['organization_id'],
                          );
        }
        //check if response is not empty then do update
        if (!empty($response)) {
            //do update batch
            $do_update = $this->db->update_batch('dme_patient', $response, 'patientID');
            if ($do_update) {
                $this->common->code = 0;
                $this->common->message = 'Successfully updated.';
            } else {
                $this->common->message = 'Failed to update';
            }
        }
        $this->common->response(false);
    }

    /*
    *  FOR CHECKING THE PATIENT ACTIVATION AND DEACTIVATION
    */
    public function check_patient_activation_and_deactivation()
    {
        $patients = $this->order_model->get_all_patients();
        $list_active_patients = $this->order_model->list_active_patients_scripts();

        $data_patient_activation_deactivation_array = array();
        $data_to_be_updated = array();
        $data_to_be_updated_patient_days = array();
        foreach ($patients as $looped_patient) {
            $patient_first_order = get_patient_first_order_status($looped_patient['patientID']);
            $returned_data = get_all_patient_pickup($looped_patient['patientID']);

            $patient_los = 1;
            $patient_days = 1;
            if (count($returned_data) == 1) {
                if ($returned_data[0]['pickup_sub'] != 'not needed') {
                    $data_patient_activation_deactivation = array(
                        'patientID' => $looped_patient['patientID'],
                        'is_active' => 0,
                        'date_added' => $returned_data[0]['date_ordered'],
                    );
                    echo 'PatientID = '.$returned_data[0]['patientID'];
                    echo ' Is ActiveS = 0 ';
                    echo "\n";
                    $data_patient_activation_deactivation_array[] = $data_patient_activation_deactivation;

                    $returned_query = check_order_after_all_pickup($returned_data[0]['orderID'], $returned_data[0]['uniqueID'], $returned_data[0]['patientID']);
                    if (!empty($returned_query)) {
                        $data_patient_activation_deactivation = array(
                            'patientID' => $returned_query['patientID'],
                            'is_active' => 1,
                            'date_added' => $returned_query['date_ordered'],
                        );
                        echo 'PatientID = '.$returned_query['patientID'];
                        echo ' Is ActiveSSSS = 1 ';
                        echo ' date_added = '.$returned_query['date_ordered'];
                        echo "\n";
                        $data_patient_activation_deactivation_array[] = $data_patient_activation_deactivation;
                    }
                }
            } elseif (count($returned_data) > 1) {
                $pickup_all_sign = 0;
                $pickup_all_count_sign = 0;
                foreach ($returned_data as $value_first_loop) {
                    if ($value_first_loop['pickup_date'] >= $patient_first_order['actual_order_date']) {
                        if ($value_first_loop['pickup_sub'] != 'not needed') {
                            $pickup_all_sign = 1;
                            ++$pickup_all_count_sign;
                        }
                    }
                }

                if ($pickup_all_sign == 1) {
                    $pickup_order_count = 1;
                    $previous_pickup_indications = 0; // 1 for selected item(s) pickup, 2 for complete pickup
                    foreach ($returned_data as $value) {
                        if ($pickup_order_count == 1) {
                            if ($value['pickup_sub'] != 'not needed') {
                                $data_patient_activation_deactivation = array(
                                    'patientID' => $looped_patient['patientID'],
                                    'is_active' => 0,
                                    'date_added' => $value['date_ordered'],
                                );
                                echo 'PatientID = '.$value['patientID'];
                                echo 'Is ActiveSS = 0';
                                echo "\n";
                                $data_patient_activation_deactivation_array[] = $data_patient_activation_deactivation;

                                $returned_query_inside = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
                                if (!empty($returned_query_inside)) {
                                    $data_patient_activation_deactivation = array(
                                        'patientID' => $returned_query_inside['patientID'],
                                        'is_active' => 1,
                                        'date_added' => $returned_query_inside['date_ordered'],
                                    );
                                    echo 'PatientID = '.$returned_query_inside['patientID'];
                                    echo ' Is ActiveSSS = 1 ';
                                    echo ' date_added = '.$returned_query_inside['date_ordered'];
                                    echo "\n";
                                    $data_patient_activation_deactivation_array[] = $data_patient_activation_deactivation;
                                }
                            }
                        } else {
                            if ($value['pickup_sub'] != 'not needed') {
                                $data_patient_activation_deactivation = array(
                                    'patientID' => $looped_patient['patientID'],
                                    'is_active' => 0,
                                    'date_added' => $value['date_ordered'],
                                );
                                echo 'PatientID = '.$value['patientID'];
                                echo ' Is ActiveSSS = 0';
                                echo "\n";
                                $data_patient_activation_deactivation_array[] = $data_patient_activation_deactivation;

                                $previous_pickup_indications = 2;
                                if (count($returned_data) == $pickup_order_count) {
                                    $returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
                                    if (!empty($returned_query)) {
                                        $data_patient_activation_deactivation = array(
                                            'patientID' => $returned_query['patientID'],
                                            'is_active' => 1,
                                            'date_added' => $returned_query['date_ordered'],
                                        );
                                        echo 'PatientID = '.$returned_query['patientID'];
                                        echo ' Is ActiveSS = 1 ';
                                        echo ' date_added = '.$returned_query['date_ordered'];
                                        echo "\n";
                                        $data_patient_activation_deactivation_array[] = $data_patient_activation_deactivation;
                                    }
                                } else {
                                    $returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
                                    if (!empty($returned_query)) {
                                        $data_patient_activation_deactivation = array(
                                            'patientID' => $returned_query['patientID'],
                                            'is_active' => 1,
                                            'date_added' => $returned_query['date_ordered'],
                                        );
                                        echo 'PatientID = '.$returned_query['patientID'];
                                        echo ' Is ActiveS = 1 ';
                                        echo ' date_added = '.$returned_query['date_ordered'];
                                        echo "\n";
                                        $data_patient_activation_deactivation_array[] = $data_patient_activation_deactivation;
                                    }
                                }
                            }
                        }
                        ++$pickup_order_count;
                    }
                }
            }
            if (!empty($data_patient_activation_deactivation)) {
                $this->order_model->insert_patient_activation_and_deactivation($data_patient_activation_deactivation);
            }
        }
    }

    // Update all order who has enroute status to move to Confirm Work Order section. Time of update is 11:58 PM.
    public function update_enroute_orders_statuses()
    {
        $result = list_order_status_enroute();
        $data['orders'] = ($result) ? $result : false;

        if (!empty($data['orders'])) {
            foreach ($data['orders'] as $key => $value) {
                $data_to_save = array(
                    'order_status' => 'tobe_confirmed',
                );
                update_enroute_orders($data_to_save, $value['uniqueID'], $value['patientID']);
                update_enroute_orders_statuses($data_to_save, $value['uniqueID'], $value['patientID']);
            }
        }
    }

    public function sample_section()
    {
        $current_date = date('Y-m-d');
        $data_to_save = array(
            'date_time' => $current_date,
        );
        insert_sample_function($data_to_save);
        //print_r($data_to_save);
    }

    // 5 AM. Make all the orders with the current date[order date] turn to enroute. For rescheduled, base it on the rescheduled date.
    public function update_scheduled_orders_today()
    {
        $current_date = date('Y-m-d');
        $result = list_orders_scheduled_today($current_date);
        $data['orders'] = ($result) ? $result : false;

        if (!empty($data['orders'])) {
            foreach ($data['orders'] as $key => $value) {
                $data_to_save = array(
                    'order_status' => 'active',
                );
                update_enroute_orders($data_to_save, $value['uniqueID'], $value['patientID']);
                update_enroute_orders_statuses($data_to_save, $value['uniqueID'], $value['patientID']);
            }
        }

        $result = list_order_status_rescheduled();
        $data['orders_list'] = ($result) ? $result : false;

        if (!empty($data['orders_list'])) {
            foreach ($data['orders_list'] as $key => $value) {
                $returned_status_id = get_status_id($value['uniqueID']);
                $returned_date = get_reschreschedule_onhold_date($returned_status_id['statusID']);

                if ($current_date == $returned_date['date']) {
                    $data_to_save = array(
                        'order_status' => 'active',
                    );
                    update_enroute_orders($data_to_save, $value['uniqueID'], $value['patientID']);
                    update_enroute_orders_statuses($data_to_save, $value['uniqueID'], $value['patientID']);
                }
            }
        }
    }

    // For the Patient Length Of Stay
    public function update_patient_los()
    {
        $patients = $this->order_model->get_all_patients();
        $list_active_patients = $this->order_model->list_active_patients_scripts_v2();

        $data_to_be_updated = array();
        $data_to_be_updated_patient_days = array();
        foreach ($patients as $looped_patient) {
            $patient_first_order = get_patient_first_order($looped_patient['patientID']);
            $returned_data = get_all_patient_pickup($looped_patient['patientID']);

            $patient_los = 1;
            $patient_days = 1;
            if (empty($returned_data)) {
                $current_date = date('Y-m-d h:i:s');
                $answer = strtotime($current_date) - strtotime($looped_patient['date_created']);
                $answer_2 = $answer / 86400;
                $patient_los = $patient_los + floor($answer_2);

                $month_created = date('m', strtotime($looped_patient['date_created']));
                $current_month = date('m');
                if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                    if ($current_month == $month_created) {
                        if ($patient_first_order['actual_order_date'] != '0000-00-00') {
                            $patient_days = (date('d') - date('d', strtotime($patient_first_order['actual_order_date'])));
                            // ++$patient_days;
                        } else {
                            $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                            // ++$patient_days;
                        }
                    } elseif ($current_month > $month_created) {
                        $patient_days = date('d');
                    }
                } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                    $patient_days = date('d');
                }
            } elseif (count($returned_data) == 1) {
                if ($returned_data[0]['pickup_sub'] != 'not needed') {
                    $returned_query = check_order_after_all_pickup($returned_data[0]['orderID'], $returned_data[0]['uniqueID'], $returned_data[0]['patientID']);
                    if (!empty($returned_query)) {
                        if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $returned_data[0]['pickup_date']) {
                            $current_date = date('Y-m-d h:i:s');
                            $answer = strtotime($current_date) - strtotime($looped_patient['date_created']);
                            $answer_2 = $answer / 86400;
                            $patient_los = $patient_los + floor($answer_2);

                            $month_created = date('m', strtotime($looped_patient['date_created']));
                            $current_month = date('m');
                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                                if ($current_month == $month_created) {
                                    $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                                    // ++$patient_days;
                                } elseif ($current_month > $month_created) {
                                    $patient_days = date('d');
                                }
                            } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                                $patient_days = date('d');
                            }
                        } else {
                            $answer = strtotime($returned_data[0]['pickup_date']) - strtotime($looped_patient['date_created']);
                            $answer_2 = $answer / 86400;
                            $patient_los = $patient_los + floor($answer_2);

                            $month_created = date('m', strtotime($looped_patient['date_created']));
                            $patient_last_month = date('m', strtotime($returned_data[0]['pickup_date']));
                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($returned_data[0]['pickup_date']))) {
                                if ($patient_last_month == $month_created) {
                                    $patient_days = (date('d', strtotime($returned_data[0]['pickup_date'])) - date('d', strtotime($looped_patient['date_created'])));
                                    // ++$patient_days;
                                } elseif ($patient_last_month > $month_created) {
                                    $patient_days = date('d', strtotime($returned_data[0]['pickup_date']));
                                }
                            } elseif (date('Y', strtotime($returned_data[0]['pickup_date'])) > date('Y', strtotime($looped_patient['date_created']))) {
                                $patient_days = date('d', strtotime($returned_data[0]['pickup_date']));
                            }
                        }
                    } else {
                        $pickupdate = $returned_data[0]['pickup_date'];
                        if ($returned_data[0]['pickup_discharge_date'] != '0000-00-00' && $returned_data[0]['pickup_discharge_date'] != null) {
                            $pickupdate = $returned_data[0]['pickup_discharge_date'];
                        }

                        $answer = strtotime($pickupdate) - strtotime($looped_patient['date_created']);
                        $answer_2 = $answer / 86400;
                        $patient_los = $patient_los + floor($answer_2);

                        $month_created = date('m', strtotime($looped_patient['date_created']));
                        $patient_last_month = date('m', strtotime($pickupdate));
                        if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($pickupdate))) {
                            if ($patient_last_month == $month_created) {
                                $active_patient_sign = 0;
                                foreach ($list_active_patients as $looped_active_patient) {
                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                        $active_patient_sign = 1;
                                    }
                                }
                                if ($active_patient_sign == 1) {
                                    $patient_days = date('d');
                                } else {
                                    $patient_days = (date('d', strtotime($pickupdate)) - date('d', strtotime($looped_patient['date_created'])));
                                    // ++$patient_days;
                                }
                            } elseif ($patient_last_month > $month_created) {
                                $active_patient_sign = 0;
                                foreach ($list_active_patients as $looped_active_patient) {
                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                        $active_patient_sign = 1;
                                    }
                                }
                                if ($active_patient_sign == 1) {
                                    $patient_days = date('d');
                                } else {
                                    $patient_days = date('d', strtotime($pickupdate));
                                }
                            }
                        } elseif (date('Y', strtotime($pickupdate)) > date('Y', strtotime($looped_patient['date_created']))) {
                            $active_patient_sign = 0;
                            foreach ($list_active_patients as $looped_active_patient) {
                                if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                    $active_patient_sign = 1;
                                }
                            }
                            if ($active_patient_sign == 1) {
                                $patient_days = date('d');
                            } else {
                                $patient_days = date('d', strtotime($pickupdate));
                            }
                        }
                    }
                } else {
                    $current_date = date('Y-m-d h:i:s');
                    $answer = strtotime($current_date) - strtotime($looped_patient['date_created']);
                    $answer_2 = $answer / 86400;
                    $patient_los = $patient_los + floor($answer_2);

                    $month_created = date('m', strtotime($looped_patient['date_created']));
                    $current_month = date('m');
                    if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                        if ($current_month == $month_created) {
                            $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                            // ++$patient_days;
                        } elseif ($current_month > $month_created) {
                            $patient_days = date('d');
                        }
                    } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                        $patient_days = date('d');
                    }
                }
            } else {
                $pickup_all_sign = 0;
                $pickup_all_count_sign = 0;
                foreach ($returned_data as $value_first_loop) {
                    if ($value_first_loop['pickup_date'] >= $patient_first_order['actual_order_date']) {
                        if ($value_first_loop['pickup_sub'] != 'not needed') {
                            $pickup_all_sign = 1;
                            ++$pickup_all_count_sign;
                        }
                    }
                }

                if ($pickup_all_sign == 0) {
                    $current_date = date('Y-m-d h:i:s');
                    $answer = strtotime($current_date) - strtotime($looped_patient['date_created']);
                    $answer_2 = $answer / 86400;
                    $patient_los = $patient_los + floor($answer_2);

                    $month_created = date('m', strtotime($looped_patient['date_created']));
                    $current_month = date('m');
                    if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                        if ($current_month == $month_created) {
                            $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                            // ++$patient_days;
                        } elseif ($current_month > $month_created) {
                            $patient_days = date('d');
                        }
                    } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                        $patient_days = date('d');
                    }
                }
                // IF NAAY COMPLETE PICKUP
                else {
                    $pickup_order_count = 1;
                    $previous_pickup_indications = 0; // 1 for selected item(s) pickup, 2 for complete pickup
                    foreach ($returned_data as $value) {
                        if ($pickup_order_count == 1) {
                            if ($value['pickup_sub'] != 'not needed') {
                                if ($pickup_all_count_sign == 1) {
                                    $returned_query_inside = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
                                    if (date('Y-m-d', strtotime($returned_query_inside['date_ordered'])) > $value['pickup_date']) {
                                        $previous_pickup_indications = 2;
                                        $previous_orderID = $value['orderID'];
                                        $previous_uniqueID = $value['uniqueID'];
                                        $previous_ordered_date = $value['pickup_date'];
                                        $previous_date_ordered = $value['date_ordered'];
                                        $partial_patient_los_first = 1; // Back to 1
                                        $partial_patient_days_first = 1;
                                    } else {
                                        $previous_pickup_indications = 1;
                                        $previous_orderID = $value['orderID'];
                                        $previous_uniqueID = $value['uniqueID'];
                                        $previous_ordered_date = $value['pickup_date'];
                                        $previous_date_ordered = $value['date_ordered'];
                                        $answer = strtotime($value['pickup_date']) - strtotime($patient_first_order['actual_order_date']);
                                        $answer_2 = $answer / 86400;
                                        $partial_patient_los_first = $patient_los + floor($answer_2);
                                        $partial_patient_days_first = 1;
                                    }
                                } else {
                                    $previous_pickup_indications = 2;
                                    $previous_orderID = $value['orderID'];
                                    $previous_uniqueID = $value['uniqueID'];
                                    $previous_ordered_date = $value['pickup_date'];
                                    $previous_date_ordered = $value['date_ordered'];
                                    $partial_patient_los_first = 1; // Back to 1
                                    $partial_patient_days_first = 1;
                                }
                            } else {
                                $answer = strtotime($value['pickup_date']) - strtotime($patient_first_order['actual_order_date']);
                                $answer_2 = $answer / 86400;
                                $partial_patient_los_first = $patient_los + floor($answer_2);
                                $previous_pickup_indications = 1;
                                $partial_patient_days_first = 1;
                                $previous_orderID = $value['orderID'];
                                $previous_uniqueID = $value['uniqueID'];
                                $previous_ordered_date = $value['pickup_date'];
                                $previous_date_ordered = $value['date_ordered'];
                            }
                        } else {
                            if ($value['pickup_sub'] != 'not needed') {
                                $previous_pickup_indications = 2;
                                if (count($returned_data) == $pickup_order_count) {
                                    $returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
                                    if (!empty($returned_query)) {
                                        if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $value['pickup_date']) {
                                            $current_date = date('Y-m-d h:i:s');
                                            $answer = strtotime($current_date) - strtotime($looped_patient['date_created']);
                                            $answer_2 = $answer / 86400;
                                            $patient_los = $patient_los + floor($answer_2);

                                            $month_created = date('m', strtotime($looped_patient['date_created']));
                                            $current_month = date('m');
                                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                                                if ($current_month == $month_created) {
                                                    $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                                                    // ++$patient_days;
                                                } elseif ($current_month > $month_created) {
                                                    $patient_days = date('d');
                                                }
                                            } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                                                $patient_days = date('d');
                                            }
                                        } else {
                                            $answer = strtotime($value['pickup_date']) - strtotime($previous_ordered_date);
                                            $answer_2 = $answer / 86400;
                                            $patient_los = $partial_patient_los_first + floor($answer_2);

                                            $month_created = date('m', strtotime($looped_patient['date_created']));
                                            $patient_last_month = date('m', strtotime($value['pickup_date']));
                                            if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($value['pickup_date']))) {
                                                if ($patient_last_month == $month_created) {
                                                    $patient_days = (date('d', strtotime($value['pickup_date'])) - date('d', strtotime($looped_patient['date_created'])));
                                                    // ++$patient_days;
                                                } elseif ($patient_last_month > $month_created) {
                                                    $patient_days = date('d', strtotime($value['pickup_date']));
                                                }
                                            } elseif (date('Y', strtotime($value['pickup_date'])) > date('Y', strtotime($looped_patient['date_created']))) {
                                                $patient_days = date('d', strtotime($value['pickup_date']));
                                            }
                                        }
                                    } else {
                                        $pickupdate = $value['pickup_date'];
                                        if ($value['pickup_discharge_date'] != '0000-00-00' && $value['pickup_discharge_date'] != null) {
                                            $pickupdate = $value['pickup_discharge_date'];
                                        }

                                        $answer = strtotime($pickupdate) - strtotime($looped_patient['date_created']);
                                        $answer_2 = $answer / 86400;
                                        $patient_los = $patient_los + floor($answer_2);

                                        $month_created = date('m', strtotime($looped_patient['date_created']));
                                        $current_month = date('m');
                                        if (date('Y', strtotime($looped_patient['date_created'])) == date('Y', strtotime($pickupdate))) {
                                            if ($current_month == $month_created) {
                                                $active_patient_sign = 0;
                                                foreach ($list_active_patients as $looped_active_patient) {
                                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                        $active_patient_sign = 1;
                                                    }
                                                }
                                                if ($active_patient_sign == 1) {
                                                    $patient_days = date('d');
                                                } else {
                                                    $patient_days = (date('d', strtotime($pickupdate)) - date('d', strtotime($looped_patient['date_created'])));
                                                    // ++$patient_days;
                                                }
                                            } elseif ($current_month > $month_created) {
                                                $active_patient_sign = 0;
                                                foreach ($list_active_patients as $looped_active_patient) {
                                                    if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                        $active_patient_sign = 1;
                                                    }
                                                }
                                                if ($active_patient_sign == 1) {
                                                    $patient_days = date('d');
                                                } else {
                                                    $patient_days = date('d', strtotime($pickupdate));
                                                }
                                            }
                                        } elseif (date('Y', strtotime($pickupdate)) > date('Y', strtotime($looped_patient['date_created']))) {
                                            $active_patient_sign = 0;
                                            foreach ($list_active_patients as $looped_active_patient) {
                                                if ($looped_active_patient['patientID'] == $looped_patient['patientID']) {
                                                    $active_patient_sign = 1;
                                                }
                                            }
                                            if ($active_patient_sign == 1) {
                                                $patient_days = date('d');
                                            } else {
                                                $patient_days = date('d', strtotime($pickupdate));
                                            }
                                        }
                                    }
                                } else {
                                    $returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
                                    if (date('Y-m-d', strtotime($returned_query['date_ordered'])) > $value['pickup_date']) {
                                        $partial_patient_los_first = 1;
                                        $previous_date_ordered = $value['date_ordered'];
                                    } else {
                                        $answer = strtotime($value['pickup_date']) - strtotime($previous_ordered_date);
                                        $answer_2 = $answer / 86400;
                                        $partial_patient_los_first = $partial_patient_los_first + floor($answer_2);
                                    }
                                    $partial_patient_days_first = 1;
                                }
                            } else {
                                $previous_pickup_indications = 1;
                                if (count($returned_data) == $pickup_order_count) {
                                    $current_date = date('Y-m-d h:i:s');
                                    if ($value['pickup_date'] > $current_date) {
                                        $answer = strtotime($current_date) - strtotime($previous_ordered_date);
                                        $answer_2 = $answer / 86400;
                                        $patient_los = $partial_patient_los_first + floor($answer_2);
                                    } else {
                                        $answer = strtotime($value['pickup_date']) - strtotime($previous_ordered_date);
                                        $answer_2 = $answer / 86400;
                                        $partial_patient_los_first = $partial_patient_los_first + floor($answer_2);

                                        $answer_sub = strtotime($current_date) - strtotime($value['pickup_date']);
                                        $answer_2_sub = $answer_sub / 86400;
                                        $patient_los = $partial_patient_los_first + floor($answer_2_sub);
                                    }
                                } else {
                                    if ($previous_pickup_indications == 1) {
                                        $answer = strtotime($value['pickup_date']) - strtotime($previous_ordered_date);
                                        $answer_2 = $answer / 86400;
                                        $partial_patient_los_first = $partial_patient_los_first + floor($answer_2);
                                    } else {
                                        $answer = strtotime($value['pickup_date']) - strtotime($looped_patient['date_created']);
                                        $answer_2 = $answer / 86400;
                                        $partial_patient_los_first = $partial_patient_los_first + floor($answer_2);
                                    }
                                    $previous_date_ordered = $value['date_ordered'];
                                }
                                $month_created = date('m', strtotime($looped_patient['date_created']));
                                $current_month = date('m');
                                if (date('Y', strtotime($looped_patient['date_created'])) == date('Y')) {
                                    if ($current_month == $month_created) {
                                        $patient_days = (date('d') - date('d', strtotime($looped_patient['date_created'])));
                                        // ++$patient_days;
                                    } elseif ($current_month > $month_created) {
                                        $patient_days = date('d');
                                    }
                                } elseif (date('Y') > date('Y', strtotime($looped_patient['date_created']))) {
                                    $patient_days = date('d');
                                }
                            }
                        }
                        ++$pickup_order_count;
                        $previous_ordered_date = $value['pickup_date'];
                    }
                }
            }

            if ($patient_los < 1) {
                $patient_los = 1;
            }
            $data_per_patient = array(
                  'patientID' => $looped_patient['patientID'],
                  'length_of_stay' => $patient_los,
              );
            $data_to_be_updated[] = $data_per_patient;

            if ($patient_days < 1) {
                $patient_days = 1;
            }
            $data_per_patient_pt_days = array(
                  'patientID' => $looped_patient['patientID'],
                  'patient_days' => $patient_days,
              );
            $data_to_be_updated_patient_days[] = $data_per_patient_pt_days;
        }
        $this->order_model->update_patient_los($data_to_be_updated);
        $this->order_model->update_patient_los($data_to_be_updated_patient_days);

        $los_per_hospice = $this->order_model->get_total_patient_los_per_hospice();
        $data_to_be_inserted = array();
        $current_date = date('Y-m-d');

        $current_hospice = 0;
        $count_los_per_hospice = 0;
        $count = 0;
        $total_los_data = array();
        foreach ($los_per_hospice as $value) {
            if ($count == 0) {
                $total_los_data[$value['ordered_by']] = $value['total_patient_los'];
                ++$count;
            } else {
                if ($value['ordered_by'] != $current_hospice) {
                    $total_los_data[$value['ordered_by']] = $value['total_patient_los'];
                } else {
                    $total_los_data[$value['ordered_by']] += $value['total_patient_los'];
                }
            }
            $current_hospice = $value['ordered_by'];
        }

        foreach ($total_los_data as $key => $value) {
            $data = array(
                'date_saved' => $current_date,
                'hospiceID' => $key,
                'patient_total_los' => $value,
            );

            $data_to_be_inserted[] = $data;
        }
        $this->order_model->insert_patient_los_per_hospice($data_to_be_inserted);

        $patient_days_per_hospice = $this->order_model->get_total_patient_days_per_hospice();
        $data_to_be_inserted_patient_days = array();
        $current_date = date('Y-m-d');

        $current_hospice = 0;
        $count_patient_days_per_hospice = 0;
        $count = 0;
        $total_patient_days_data = array();
        foreach ($patient_days_per_hospice as $value) {
            if ($count == 0) {
                $total_patient_days_data[$value['ordered_by']] = $value['total_patient_days'];
                ++$count;
            } else {
                if ($value['ordered_by'] != $current_hospice) {
                    $total_patient_days_data[$value['ordered_by']] = $value['total_patient_days'];
                } else {
                    $total_patient_days_data[$value['ordered_by']] += $value['total_patient_days'];
                }
            }
            $current_hospice = $value['ordered_by'];
        }
        foreach ($total_patient_days_data as $key => $value) {
            $data = array(
                'date_saved' => $current_date,
                'hospiceID' => $key,
                'total_patient_days' => $value,
            );

            $data_to_be_inserted_patient_days[] = $data;
        }
        $this->order_model->insert_patient_days_per_hospice($data_to_be_inserted_patient_days);
    }

    public function create_new_statement_no_all_hospices() {
		// $all_hospices = $this->billing_statement_model->all_list_for_account();
		$all_hospices_with_bill_details = $this->billing_statement_model->get_statement_bill_with_hospice_details();
		foreach($all_hospices_with_bill_details as $hospice_with_bill_details) {
			if($hospice_with_bill_details['hospiceID'] != null && $hospice_with_bill_details['hospiceID'] !== "") {
                $date_from = date('Y-m-01');
                $date_to = date("Y-m-t", strtotime($date_from));
                $has_customer = $this->billing_statement_model->has_customer_for_draft_statement($hospice_with_bill_details['accountID'], $date_from, $date_to);

                if (!empty($has_customer)) {
                    $data = array(
                        "hospiceID" 		=> $hospice_with_bill_details['hospiceID'],
                        "statement_no"		=> $hospice_with_bill_details['statement_no'],
                        "service_date_from"	=> $hospice_with_bill_details['service_date_from'],
                        "service_date_to"	=> date("Y-m-t", strtotime($hospice_with_bill_details['service_date_from'])),
                        "notes"				=> "",
                        "is_manual"			=> 0
                    );
                    $statement_draft = $this->billing_statement_model->insert_statement_draft($data);
        
                    if($statement_draft) {
                        $all_current_reconciliation = $this->billing_statement_model->get_all_current_reconciliation_balance_and_owe($hospice_with_bill_details['hospiceID']);
                        $data_recon = array(
                            "draft_reference" => $hospice_with_bill_details['statement_no']
                        );
                        foreach($all_current_reconciliation as $value) {
                            $this->billing_statement_model->update_statement_reconciliation($value['acct_statement_reconciliation_id'], $data_recon);
                        }
                    } 
                }
			}
		}

		$this->billing_statement_model->remove_statement_bill();
		foreach($all_hospices_with_bill_details as $hospice) {
            $data = array();
            $temp_statement_no = strtotime(date('Y-m-d H:i:s'));
            $statement_no = $temp_statement_no + $hospice['accountID'];
            $service_date = date('Y-m-01');
            $data = array(
                "statement_no"		=> $statement_no,
                "hospiceID"			=> $hospice['accountID'],
                "service_date_from"	=> $service_date
            );
            $this->billing_statement_model->insert_statement_bill($data);
			
		}
	}

    // For recurring order
    public function generate_recurring_orders()
    {
        $current_date = date('Y-m-d');
        $recurring_orders = $this->order_model->get_recurring_schedules($current_date);
        $order_uniqueID_counter = 1;

        foreach ($recurring_orders as $key => $value) {
            $orders = array();
            $data_equipments = $this->order_model->get_recurring_equipments($value['recurring_id']);
            $patient_info = get_patient_info($value['patientID']);

            $cusmove_address = get_cusmove_address_id($value['patientID']);
            $patient_address_id = get_address_id($value['patientID']);
            if (!empty($cusmove_address)) {
                $patient_address_id = $cusmove_address;
            }

            $patient_delivery_to_type = get_delivery_to_type($value['patientID']);
            $unique_id = strtotime(date('Y-m-d H:i:s'));

            $checked_unique_id = $this->order_model->checked_unique_id($unique_id);
            if (!empty($checked_unique_id)) {
                $unique_id = $unique_id + $order_uniqueID_counter;
            }

            if ($unique_id != '') {
                /*
                * order information
                */
                $delivery_date = date('Y-m-d');
                $order_current_status = 'pending';

                foreach ($data_equipments as $key => $equip) {
                    $equip_val = 1;

                    $sub_equip = get_equipmentID_of_parentID($equip['equipmentID']);
                    if (!empty($sub_equip)) {
                        $orders[] = array(
                            'patientID' => $value['patientID'],
                            'equipmentID' => $sub_equip['equipmentID'],
                            'equipment_value' => $equip['equipment_value'],
                            'pickup_date' => $delivery_date,
                            'activity_typeid' => 1,
                            'organization_id' => $patient_info['organization_id'],
                            'ordered_by' => $value['ordered_by'],
                            'who_ordered_fname' => $value['who_ordered_fname'],
                            'who_ordered_lname' => $value['who_ordered_lname'],
                            'staff_member_fname' => $value['staff_member_fname'],
                            'staff_member_lname' => $value['staff_member_lname'],
                            'who_ordered_email' => $value['who_ordered_email'],
                            'who_ordered_cpnum' => $value['who_ordered_cpnum'],
                            'comment' => $value['comment'],
                            'uniqueID' => $unique_id,
                            'deliver_to_type' => $patient_delivery_to_type['deliver_to_type'],
                            'medical_record_id' => $patient_info['medical_record_id'],
                            'order_status' => 'pending',
                            'addressID' => $patient_address_id['id'],
                            'is_recurring' => 1,
                            'is_package' => 0,
                            'equipment_quantity' => $equip_val
                        );
                    } else {
                        $equip_val = $equip['equipment_value'];
                    }
                    $orders[] = array(
                        'patientID' => $value['patientID'],
                        'equipmentID' => $equip['equipmentID'],
                        'equipment_value' => $equip_val,
                        'pickup_date' => $delivery_date,
                        'activity_typeid' => 1,
                        'organization_id' => $patient_info['organization_id'],
                        'ordered_by' => $value['ordered_by'],
                        'who_ordered_fname' => $value['who_ordered_fname'],
                        'who_ordered_lname' => $value['who_ordered_lname'],
                        'staff_member_fname' => $value['staff_member_fname'],
                        'staff_member_lname' => $value['staff_member_lname'],
                        'who_ordered_email' => $value['who_ordered_email'],
                        'who_ordered_cpnum' => $value['who_ordered_cpnum'],
                        'comment' => $value['comment'],
                        'uniqueID' => $unique_id,
                        'deliver_to_type' => $patient_delivery_to_type['deliver_to_type'],
                        'medical_record_id' => $patient_info['medical_record_id'],
                        'order_status' => 'pending',
                        'addressID' => $patient_address_id['id'],
                        'is_recurring' => 1,
                        'is_package' => 0,
                        'equipment_quantity' => $equip['equipment_value']
                    );
                }
                $saveorder = $this->order_model->saveorder($orders);

                if ($saveorder) {
                    $insert_to_status = array(
                        'order_uniqueID' => $unique_id,
                        'medical_record_id' => $patient_info['medical_record_id'],
                        'patientID' => $value['patientID'],
                        'status_activity_typeid' => 1,
                        'order_status' => $order_current_status,
                        'addressID' => $patient_address_id['id'],
                        'pickup_date' => $delivery_date,
                        'organization_id' => $patient_info['organization_id'],
                        'ordered_by' => $value['ordered_by'],
                        'date_ordered' => date('Y-m-d h:i:s'),
                        'original_activity_typeid' => 1,
                        'actual_order_date' => '0000-00-00',
                        'deliver_to_type' => $patient_delivery_to_type['deliver_to_type'],
                        'is_recurring' => 1,
                    );

                    $this->order_model->insert_to_status($insert_to_status);
                }

                ++$order_uniqueID_counter;
                $recurring_sched_days = $value['recurring_schedule_days'];
                $recurring_sched_days_post = explode('-', $recurring_sched_days);
                $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                $weeks = array('first', 'second', 'third', 'fourth', 'fifth');
                $length = count($recurring_sched_days_post);
                $recurring_sched_week = $value['recurring_schedule_weeks'];
                $next_sched = date('Y-m-d');
                $counter = 0;

                $rec_sched = '';
                $temp_week = $recurring_sched_week;
                $recurring_week = $weeks[$temp_week];
                $recurring_day = $days[(int) $value['recurring_schedule_days'][$length - 2]];

                $recurring_month = date('F');
                $temp_next_sched = date('Y-m-d', strtotime($recurring_week.' '.$recurring_day.' of '.$recurring_month));
                $recur_month = false;
                if ((int) (date('j') < (int) date('j', strtotime($temp_next_sched)))) {
                    if ($recurring_sched_week == '0' ||
                    $recurring_sched_week == '1' ||
                    $recurring_sched_week == '2' ||
                    $recurring_sched_week == '3' ||
                    $recurring_sched_week == '4') {
                        $recur_month = true;
                    }
                }
                $rec_sched = '';
                $day_today = date('w');
                $orig_sched = $recurring_sched_days_post;
                sort($orig_sched);

                $day_today_index = 0;
                $check = 0;
                foreach ($orig_sched as $key => $value2) {
                    if ((int) $value2 > (int) $day_today) {
                        if ($check == 1) {
                            $rec_sched = $rec_sched.'-'.$value2;
                        }
                        if ($check == 0) {
                            $rec_sched = $value2;
                            $check = 1;
                        }
                    }
                }
                foreach ($orig_sched as $key => $value2) {
                    if ((int) $value2 <= (int) $day_today) {
                        if ($check == 1) {
                            $rec_sched = $rec_sched.'-'.$value2;
                        }
                        if ($check == 0) {
                            $rec_sched = $value2;
                            $check = 1;
                        }
                    }
                }
                $rec_sched_days = explode('-', $rec_sched);
                if ($recurring_sched_week == 'ew' ||
                    $recurring_sched_week == 'ew2' ||
                    $recurring_sched_week == 'ew3') {
                    if ($orig_sched[count($orig_sched) - 1] == date('w')) {
                        $first_date_day = ((int) date('j') - (int) date('w') + (int) $orig_sched[0]);
                        if ($first_date_day > date('t')) {
                            $first_date_day = $first_date_day - date('t');
                            $next_month_date = strtotime(date('Y-m-d', strtotime('+1 month')));
                            
                            if (((int) $first_date_day) > 9) {
                                $first_date = date('Y', $next_month_date).'-'.date('m', $next_month_date).'-'.$first_date_day;
                            } else {
                                $first_date = date('Y', $next_month_date).'-'.date('m', $next_month_date).'-0'.$first_date_day;
                            }
                        } else {
                            if ($first_date_day > 9) {
                                $first_date = date('Y').'-'.date('m').'-'.$first_date_day;
                            } else {
                                $first_date = date('Y').'-'.date('m').'-0'.$first_date_day;
                            }
                        }

                        if ($recurring_sched_week == 'ew') {
                            $next_sched = date('Y-m-d', strtotime($first_date.' +1 week'));
                        } elseif ($recurring_sched_week == 'ew2') {
                            $next_sched = date('Y-m-d', strtotime($first_date.' +2 week'));
                        } elseif ($recurring_sched_week == 'ew3') {
                            $next_sched = date('Y-m-d', strtotime($first_date.' +3 week'));
                        }
                    } else {
                        $first_date_day = ((int) date('j') - (int) date('w') + (int) $rec_sched_days[0]);
                        if ($first_date_day > date('t')) {
                            $first_date_day = $first_date_day - date('t');
                            $next_month_date = strtotime(date('Y-m-d', strtotime('+1 month')));
                            
                            if (((int) $first_date_day) > 9) {
                                $next_sched = date('Y', $next_month_date).'-'.date('m', $next_month_date).'-'.$first_date_day;
                            } else {
                                $next_sched = date('Y', $next_month_date).'-'.date('m', $next_month_date).'-0'.$first_date_day;
                            }
                        } else {
                            if (((int) $first_date_day) > 9) {
                                $next_sched = date('Y').'-'.date('m').'-'.$first_date_day;
                            } else {
                                $next_sched = date('Y').'-'.date('m').'-0'.$first_date_day;
                            }
                        }
                    }
                } else {
                    $temp_week = $recurring_sched_week;
                    $recurring_week = $weeks[$temp_week];
                    $recurring_day = $days[(int) $rec_sched_days[0]];
                    $recurring_month = date('F');
                    $next_sched = date('Y-m-d', strtotime($recurring_week.' '.$recurring_day.' of '.$recurring_month));
                    $datedatedate = date('Y-m-d');
                    $firstOfMonth = strtotime(date('Y-m-01', $datedatedate));
                    $weekNumber = ((int) strftime('%U', date('Y-m-d'))) - ((int) strftime('%U', $firstOfMonth));
                    if ($weekNumber != 1) {
                        $weekNumber = $weekNumber - 1;
                    }

                    if ($weekNumber <= ($recurring_sched_week + 1)) {
                        $temp_week = $recurring_sched_week;
                        $recurring_week = $weeks[$temp_week];
                        $recurring_day = $days[(int) $rec_sched[0]];
                        $recurring_month = date('F');
                        $next_sched = date('Y-m-d', strtotime($recurring_week.' '.$recurring_day.' of '.$recurring_month));
                    }
                    if ($weekNumber > ($recurring_sched_week + 1)) {
                        $temp_week = $recurring_sched_week;
                        $recurring_week = $weeks[$temp_week];
                        $recurring_day = $days[(int) $rec_sched[0]];
                        $recurring_month = date('F', strtotime(date('Y-m-d', strtotime('+1 month'))));
                        $next_sched = date('Y-m-d', strtotime($recurring_week.' '.$recurring_day.' of '.$recurring_month));
                    }
                }

                if ($rec_sched != '') {
                    $recurring_schedule = $rec_sched;
                } else {
                    $recurring_schedule = $recurring_sched_days;
                }

                $recurring_order = array(
                    'recurring_schedule_days' => $recurring_schedule,
                    'next_date' => $next_sched,
                );

                $saveorder_recurring = $this->order_model->updateorder_recurring($value['recurring_id'], $recurring_order);
            } else {
                echo 'No uniqueID created.';
            }
        }
    }

    // For Billing Cus Days
    public function save_customer_days_for_draft_statement() {
		$tomorrow = date('d', strtotime('1 day'));

		if ($tomorrow*1 == 1) {
			$patients = $this->billing_statement_model->get_all_patient_for_draft_statement();
			$month_date = date('Y-m-01');
			foreach($patients as $value) {
				//By one
				$data = array(
					"patientID" => $value['patientID'],
					"customer_days" => $value['patient_days'],
					"service_date" => $month_date,
					"hospiceID" => $value['ordered_by']
				);
				$this->billing_statement_model->save_customer_days($data);
			}
		}
	}
}
