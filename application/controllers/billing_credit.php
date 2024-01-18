<?php
Class billing_credit extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		is_logged_in();
		date_default_timezone_set('America/Los_Angeles');
		$this->load->model("billing_statement_model");
		$this->load->model("billing_credit_model");
		$this->load->model("order_model");
        $this->load->model("equipment_model");
    }

    function get_total_billing_credit($hospiceID) {
        $statement_bill = $this->billing_statement_model->get_statement_bill_by_hospice($hospiceID);
        $service_date_from = $statement_bill['service_date_from'];
        $service_date_to = date("Y-m-t", strtotime($service_date_from));

        $total_billing_credit = $this->billing_credit_model->get_total_billing_credit($hospiceID, $service_date_from, $service_date_to);
        $billing_credit_notes = $this->billing_credit_model->get_billing_credit_notes($hospiceID, $service_date_from, $service_date_to);
        $temp_notes = '';
        $notes_counter = 0;

        foreach($billing_credit_notes as $value) {
            if ($notes_counter == 0) {
                $temp_notes = $value['notes'];
            } else {
                $temp_notes = $temp_notes.', '.$value['notes'];
            }
            $notes_counter++;
        }

        echo json_encode(array(
            'total_billing_credit' => $total_billing_credit['total_billing_credit'],
            'notes' => $temp_notes
        ));
		exit;
    }

    function insert_billing_credit($schedule_pickup_date, $uniqueID) {
        $data_post = $this->input->post();

        //Format Date
        $exploded_schedule_pickup_date = explode("-", $schedule_pickup_date);
        $schedule_pickup_date = $exploded_schedule_pickup_date[2].'-'.$exploded_schedule_pickup_date[0].'-'.$exploded_schedule_pickup_date[1];


        $patient_info = $this->billing_credit_model->get_patient_info($data_post['billing_credit_patientID']);
        $customer_orders = $this->billing_credit_model->customer_order_list($uniqueID, $data_post['billing_credit_hospiceID'], $data_post['billing_credit_patientID']);

        $cusdays = date('d', strtotime($schedule_pickup_date));
        $cus_days = $cusdays * $patient_info['daily_rate'];
        $credit = $cus_days;

        foreach($customer_orders as $cus_value) {
            if($cus_value['equip_is_package'] == 0 && $cus_value['is_package'] == 1) {
                continue;
            }

            // Get Full Description - Start
            $summary = $cus_value;
            $summary['item_description_data'] = "";
            if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
            {
                if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
                {
                    $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                    $summary['item_description_data'] = $summary['key_desc'];
                }
                else
                {
                    //*** DELIVERY not CONFIRMED yet dri mo display ang item ***//
                    //check if naay sub equipment using equipment id, work uniqueId
                    $subequipment_id = get_subequipment_id($summary['equipmentID']);

                    //gets all the id's under the order
                    if($subequipment_id)
                    {
                        $count = 0;
                        $patient_lift_sling_count = 0;
                        $high_low_full_electric_hospital_bed_count = 0;
                        $equipment_count = 0;
                        $my_count_sign = 0;
                        $my_first_sign = 0;
                        $my_second_sign = 0;

                        foreach ($subequipment_id as $key) {
                            $value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);

                            if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
                            {
                                if(empty($value))
                                {
                                    $my_first_sign = 1;
                                }
                            }
                            if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
                            {
                                if(empty($value))
                                {
                                    $my_second_sign = 1;
                                }
                            }
                            if($my_second_sign == 1 && $my_first_sign == 1)
                            {
                                $my_count_sign = 1;
                            }
                            if($value)
                            {
                                $count++;
                                //full electric hospital bed equipment
                                if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
                                {
                                    $summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
                                }
                                //Hi-Low Full Electric Hospital Bed equipment
                                else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
                                {
                                    $summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
                                }
                                //Patient Lift with Sling
                                else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
                                {
                                    $summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
                                }
                                //Patient Lift Electric with Sling
                                else if($summary['equipmentID'] == 353)
                                {
                                    $summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
                                }
                                //Patient Lift Sling
                                else if($summary['equipmentID'] == 196)
                                {
                                    $summary['item_description_data'] = $key['key_desc'];
                                }
                                //(54 & 17) Geri Chair || (66 & 39) Shower Chair
                                else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
                                {
                                    $summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
                                }
                                // Oxygen E Portable System && Oxygen Liquid Portable
                                else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
                                {
                                    $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                    $summary['item_description_data'] = $summary['key_desc'];
                                break;
                                }
                                //Oxygen Cylinder Rack
                                else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                                {
                                    $summary['item_description_data'] = "Oxygen ".$key['key_desc'];
                                    break;
                                }
                                //(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
                                else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
                                {
                                    if($my_count_sign == 0)
                                    {
                                        //wheelchair & wheelchair reclining
                                        if($count == 1)
                                        {
                                            $temp_item_description = "";
                                            $temp_item_description2 = "";

                                            if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
                                            {
                                                $key['key_desc'] = '16" Narrow';
                                            }
                                            else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
                                            {
                                                $key['key_desc'] = '18" Standard';
                                            }
                                            else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
                                            {
                                                $key['key_desc'] = '20" Wide';
                                            }
                                            else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
                                            {
                                                $key['key_desc'] = '22" Extra Wide';
                                            }
                                            else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
                                            {
                                                $key['key_desc'] = '24" Bariatric';
                                            }
                                            $temp_item_description = $key['key_desc']." ".$summary['key_desc'];
                                        }
                                        else
                                        {
                                            $temp_item_description2 = " With ".$key['key_desc'];
                                        }
                                        $summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
                                    }
                                    else
                                    {
                                        $temp_item_description = $summary['key_desc'];
                                        if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
                                        {
                                            $temp_item_description2 = " With Elevating Legrests";
                                        }
                                        else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
                                        {
                                            $temp_item_description2 = " With Footrests";
                                        }
                                        $initial_temp_item_description = '20" Wide';
                                        $summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
                                        break;
                                    }
                                }
                                else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                                {
                                    if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
                                    {
                                        $key['key_desc'] = '17" Narrow';
                                    }
                                    else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
                                    {
                                        $key['key_desc'] = '19" Standard';
                                    }
                                    $temp_item_description = $key['key_desc']." ".$summary['key_desc'];
                                    $summary['item_description_data'] = $temp_item_description;
                                }
                                else if($summary['equipmentID'] == 30)
                                {
                                    if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
                                    {
                                        if($count == 3)
                                        {
                                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                            $temp_item_description = " With ".$key['key_desc'];
                                            $summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
                                        }
                                    }
                                    else
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc'];
                                    }
                                }
                                //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
                                else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
                                {
                                    $samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
                                    if(strlen($samp) > 30)
                                    {
                                        $temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
                                    }
                                    else
                                    {
                                        $temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
                                    }

                                    $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                    $summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
                                    break;
                                }
                                else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                                {
                                    $samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
                                    if($count == 1)
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
                                    }
                                }
                                else if($summary['equipmentID'] == 282)
                                {

                                }
                                //equipments that has no subequipment but gets inside the $value if statement
                                else if($summary['equipmentID'] == 14)
                                {
                                    $summary['item_description_data'] = $summary['key_desc'];
                                }
                                else
                                {
                                    if($summary['categoryID'] == 1)
                                    {
                                        $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                        if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                                        {
                                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                            $summary['item_description_data'] = $summary['key_desc'];
                                            break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 14)
                                        {
                                            $summary['item_description_data'] = $summary['key_desc'];
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 282)
                                        {
                                            $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                            $summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
                                            break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                                        {
                                            $summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 353)
                                        {
                                            $summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
                                        }
                                        else
                                        {
                                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                            $summary['item_description_data'] = $summary['key_desc'];
                                        }
                                    }
                                    else
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc'];
                                    }
                                }
                            } //end of $value
                            //for Oxygen E cylinder do not remove as it will cause errors
                            else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                            {
                                break;
                            }
                            else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                            {

                            }
                            else if($summary['equipmentID'] == 282)
                            {
                                $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                                $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                $summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
                                break;
                            }
                            //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
                            else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
                            {
                                $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                $summary['item_description_data'] = $summary['key_desc'];
                                break;
                            }
                            //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
                            else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
                            {
                                $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                $summary['item_description_data'] = $summary['key_desc'];
                            }
                            //for equipments with subequipment but does not fall in $value
                            else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
                            {
                                if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
                                {
                                    $patient_lift_sling_count++;
                                    if($patient_lift_sling_count == 6)
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc'];
                                    }
                                }
                                else if($summary['equipmentID'] == 398)
                                {
                                    if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
                                    {
                                        $high_low_full_electric_hospital_bed_count++;
                                        if($high_low_full_electric_hospital_bed_count == 2)
                                        {
                                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                            $summary['item_description_data'] = $summary['key_desc'];
                                        }
                                    }
                                }
                                // Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
                                else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                                {
                                    $equipment_count++;
                                    if($equipment_count == 2)
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc'];
                                    }
                                }
                            }
                            else
                            {
                                if($summary['categoryID'] == 1)
                                {
                                    $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                    if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc'];
                                        break;
                                    }
                                    else if($non_capped_copy['noncapped_reference'] == 14)
                                    {
                                        $summary['item_description_data'] = $summary['key_desc'];
                                    }
                                    else
                                    {

                                    }
                                }
                                else
                                {
                                    $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                    $summary['item_description_data'] = $summary['key_desc'];
                                }
                            }
                        }
                    }
                    else
                    {
                        if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
                        {
                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                            $summary['item_description_data'] = $summary['key_desc'];
                        }
                        else
                        {
                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                            $summary['item_description_data'] = $summary['key_desc'];
                        }
                    }
                }
            }
            else
            {
                $summary['item_description_data'] = "";
                if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
                {
                    $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                    $summary['item_description_data'] = $summary['key_desc'];
                }
                else
                {
                    //check if the item has sub-equipments using equipment id
                    $subequipment_id = get_subequipment_id($summary['equipmentID']);
                    //gets all the id's under the order
                    if($subequipment_id)
                    {
                        $count = 0;
                        $patient_lift_sling_count = 0;
                        $high_low_full_electric_hospital_bed_count = 0;
                        $equipment_count = 0;
                        $my_count_sign = 0;
                        $my_first_sign = 0;
                        $my_second_sign = 0;
                        foreach ($subequipment_id as $key) {
                            $value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
                            if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
                            {
                                if(empty($value))
                                {
                                    $my_first_sign = 1;
                                }
                            }
                            if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
                            {
                                if(empty($value))
                                {
                                    $my_second_sign = 1;
                                }
                            }
                            if($my_second_sign == 1 && $my_first_sign == 1)
                            {
                                $my_count_sign = 1;
                            }
                            if($value)
                            {
                                $count++;
                                //equipment full electric hospital bed
                                if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
                                {
                                    $summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
                                }
                                //Hi-Low Full Electric Hospital Bed equipment
                                else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
                                {
                                    $summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
                                }
                                //Patient Lift with Sling
                                else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
                                {
                                    $summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
                                }
                                //Patient Lift Electric with Sling
                                else if($summary['equipmentID'] == 353)
                                {
                                    $summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
                                }
                                //Patient Lift Sling
                                else if($summary['equipmentID'] == 196)
                                {
                                    $summary['item_description_data'] = $key['key_desc'];
                                }
                                //(54 & 17) Geri Chair || (66 & 39) Shower Chair
                                else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
                                {
                                    $summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
                                }
                                // Oxygen E Portable System && Oxygen Liquid Portable
                                else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
                                {
                                    $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                    $summary['item_description_data'] = $summary['key_desc'];
                                    break;
                                }
                                //Oxygen Cylinder Rack
                                else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                                {
                                    $summary['item_description_data'] = "Oxygen ".$key['key_desc'];
                                    break;
                                }
                                //(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
                                else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
                                {
                                    if($my_count_sign == 0)
                                    {
                                        //wheelchair & wheelchair reclining
                                        if($count == 1)
                                        {
                                            $temp_item_description = "";
                                            $temp_item_description2 = "";

                                            if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
                                            {
                                                $key['key_desc'] = '16" Narrow';
                                            }
                                            else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
                                            {
                                                $key['key_desc'] = '18" Standard';
                                            }
                                            else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
                                            {
                                                $key['key_desc'] = '20" Wide';
                                            }
                                            else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
                                            {
                                                $key['key_desc'] = '22" Extra Wide';
                                            }
                                            else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
                                            {
                                                $key['key_desc'] = '24" Bariatric';
                                            }
                                            $temp_item_description = $key['key_desc']." ".$summary['key_desc'];
                                        }
                                        else
                                        {
                                            $temp_item_description2 = " With ".$key['key_desc'];
                                        }
                                        $summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
                                    }
                                    else
                                    {
                                        $temp_item_description = $summary['key_desc'];
                                        if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
                                        {
                                            $temp_item_description2 = " With Elevating Legrests";
                                        }
                                        else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
                                        {
                                            $temp_item_description2 = " With Footrests";
                                        }
                                        $initial_temp_item_description = '20" Wide';
                                        $summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
                                        break;
                                    }
                                }
                                else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                                {
                                    if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
                                    {
                                        $key['key_desc'] = '17" Narrow';
                                    }
                                    else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
                                    {
                                        $key['key_desc'] = '19" Standard';
                                    }
                                    $temp_item_description = $key['key_desc']." ".$summary['key_desc'];
                                    $summary['item_description_data'] = $temp_item_description;
                                }
                                else if($summary['equipmentID'] == 30)
                                {
                                    if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
                                    {
                                        if($count == 3)
                                        {
                                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                            $temp_item_description = " With ".$key['key_desc'];
                                            $summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
                                        }
                                    }
                                    else
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc'];
                                    }
                                }
                                //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
                                else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
                                {
                                    $samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
                                    if(strlen($samp) > 30)
                                    {
                                        $temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
                                    }
                                    else
                                    {
                                        $temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
                                    }

                                    $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                    $summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
                                    break;
                                }
                                else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                                {
                                    $samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
                                    if($count == 1)
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
                                    }
                                }
                                else if($summary['equipmentID'] == 282)
                                {

                                }
                                //equipments that has no subequipment but gets inside the $value if statement
                                else if($summary['equipmentID'] == 14)
                                {
                                    $summary['item_description_data'] = $summary['key_desc'];
                                }
                                else
                                {
                                    if($summary['categoryID'] == 1)
                                    {
                                        $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                        if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                                        {
                                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                            $summary['item_description_data'] = $summary['key_desc'];
                                            break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 14)
                                        {
                                            $summary['item_description_data'] = $summary['key_desc'];
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 282)
                                        {
                                            $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                            $summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
                                            break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                                        {
                                            $summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 353)
                                        {
                                            $summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
                                        }
                                        else
                                        {
                                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                            $summary['item_description_data'] = $summary['key_desc'];
                                        }
                                    }
                                    else
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc'];
                                    }
                                }
                            } //end of $value
                            //for Oxygen E cylinder do not remove as it will cause errors
                            else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                            {
                                break;
                            }
                            else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                            {

                            }
                            else if($summary['equipmentID'] == 282)
                            {
                                $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                                $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                $summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
                                break;
                            }
                            //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
                            else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
                            {
                                $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                $summary['item_description_data'] = $summary['key_desc'];
                                break;
                            } //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
                            else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
                            {
                                $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                $summary['item_description_data'] = $summary['key_desc'];
                            }
                            //for equipments with subequipment but does not fall in $value
                            else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
                            {
                                if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
                                {
                                    $patient_lift_sling_count++;
                                    if($patient_lift_sling_count == 6)
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc'];
                                    }
                                }
                                else if($summary['equipmentID'] == 398)
                                {
                                    if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
                                    {
                                        $high_low_full_electric_hospital_bed_count++;
                                        if($high_low_full_electric_hospital_bed_count == 2)
                                        {
                                            $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                            $summary['item_description_data'] = $summary['key_desc'];
                                        }
                                    }
                                }
                                // Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
                                else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                                {
                                    $equipment_count++;
                                    if($equipment_count == 2)
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc'];
                                    }
                                }
                            }
                            else
                            {
                                if($summary['categoryID'] == 1)
                                {
                                    $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                    if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
                                    {
                                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                        $summary['item_description_data'] = $summary['key_desc'];
                                        break;
                                    }
                                    else if($non_capped_copy['noncapped_reference'] == 14)
                                    {
                                        $summary['item_description_data'] = $summary['key_desc'];
                                    }
                                    else
                                    {

                                    }
                                }
                                else
                                {
                                    $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                                    $summary['item_description_data'] = $summary['key_desc'];
                                }
                            }
                        }
                    }
                    else
                    {
                        $temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
                        $summary['item_description_data'] = $summary['key_desc'];
                    }
                }
            }
            // Get Full Description - End

            // Get Item Group Rates - Start
            $item = $cus_value;
            $item['key_desc'] = $summary['item_description_data'];
            $item['hospiceID'] = $data_post['billing_credit_hospiceID'];
            switch($item['equipmentID']) {
                case 49:
                $subequipdetails = array();
                $check = "wala";
                if(strpos($item['key_desc'], "16") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
                }
                if(strpos($item['key_desc'], "18") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
                }
                if(strpos($item['key_desc'], "20") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
                }
                if(strpos($item['key_desc'], "22") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "22_inch");
                }
                if(strpos($item['key_desc'], "24") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "24_inch");
                }

                if($subequipdetails != null && count($subequipdetails) > 0) {

                }
                $summary_response[] = array(
                    "sub_equip_details" => $subequipdetails,
                    "patientID" => $item['patientID'],
                    "uniqueID" => $item['uniqueID'],
                    "equipmentID" => $item['equipmentID'],
                    "equipmentVal" => $item['equipmentVal'],
                    "addressID" => $item['addressID'],
                );
                $cus_value['purchase_price'] = $subequipdetails['purchase_price'];
                $cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
                $cus_value['daily_rate'] = $subequipdetails['daily_rate'];
                break;
                case 64:
                $subequipdetails = array();
                if(strpos($item['key_desc'], "16") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "16_inch");
                }
                if(strpos($item['key_desc'], "18") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "18_inch");
                }
                if(strpos($item['key_desc'], "20") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "20_inch");
                }

                if($subequipdetails != null && count($subequipdetails) > 0) {

                }
                $summary_response[] = array(
                    "sub_equip_details" => $subequipdetails,
                    "patientID" => $item['patientID'],
                    "uniqueID" => $item['uniqueID'],
                    "equipmentID" => $item['equipmentID'],
                    "equipmentVal" => $item['equipmentVal'],
                    "addressID" => $item['addressID'],
                );
                $cus_value['purchase_price'] = $subequipdetails['purchase_price'];
                $cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
                $cus_value['daily_rate'] = $subequipdetails['daily_rate'];
                break;
                case 32:
                $subequipdetails = array();
                if(strpos($item['key_desc'], "E Cylinder 6 Rack") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_6_rack");
                }
                if(strpos($item['key_desc'], "E Cylinder 12 Rack") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "e_cylinder_12_rack");
                }
                if(strpos($item['key_desc'], "M6 Cylinder 6 Rack") != false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_6_rack");
                }
                if(strpos($item['key_desc'], "M6 Cylinder 12 Rack") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($item['assigned_equipmentID'], "m6_cylinder_12_rack");
                }

                if($subequipdetails != null && count($subequipdetails) > 0) {

                }
                $summary_response[] = array(
                    "sub_equip_details" => $subequipdetails,
                    "patientID" => $item['patientID'],
                    "uniqueID" => $item['uniqueID'],
                    "equipmentID" => $item['equipmentID'],
                    "equipmentVal" => $item['equipmentVal'],
                    "addressID" => $item['addressID'],
                );
                $cus_value['purchase_price'] = $subequipdetails['purchase_price'];
                $cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
                $cus_value['daily_rate'] = $subequipdetails['daily_rate'];
                break;
                case 29: case 334: case 343:
                $getassignedequipmentid = $this->equipment_model->get_assigned_equipment_details($item['hospiceID'], 29);
                $subequipdetails = array();
                if(strpos($item['key_desc'], "5L") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "5_liters");
                }
                if(strpos($item['key_desc'], "10L") !== false){
                    $subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "10_liters");
                }

                if($subequipdetails != null && count($subequipdetails) > 0) {

                }
                $summary_response[] = array(
                    "sub_equip_details" => $subequipdetails,
                    "patientID" => $item['patientID'],
                    "uniqueID" => $item['uniqueID'],
                    "equipmentID" => $item['equipmentID'],
                    "equipmentVal" => $item['equipmentVal'],
                    "addressID" => $item['addressID'],
                    "getassignedequipmentid" => $getassignedequipmentid
                );
                $cus_value['purchase_price'] = $subequipdetails['purchase_price'];
                $cus_value['monthly_rate'] = $subequipdetails['monthly_rate'];
                $cus_value['daily_rate'] = $subequipdetails['daily_rate'];
                break;
            }
            // Get Item Group Rates - End

            $cus_total = 0;
            if($cus_value['categoryID'] == 2) {
                $your_date = strtotime($cus_value['actual_order_date']);
                $your_date_v2 = new DateTime($cus_value['actual_order_date']);
                $new_pickupdate_v2 = new DateTime(date("Y-m-t", strtotime($cus_value['actual_order_date'])));
                $temporary_service_date_to = $schedule_pickup_date;
                $isSummaryPickupDate = false;
                
                if($cus_value['summary_pickup_date'] != "0000-00-00" && ($cus_value['summary_pickup_date'] <= $temporary_service_date_to)) {
                    
                    if ($cus_value['pickup_discharge_date'] !== '0000-00-00' && $cus_value['pickup_discharge_date'] !== null) {
                        $now_v2 = new DateTime($cus_value['pickup_discharge_date']);
                    } else {
                        $now_v2 = new DateTime($cus_value['summary_pickup_date']);
                    }
                    $isSummaryPickupDate = true;
                } else {
                    $now_v2 = new DateTime($schedule_pickup_date);
                }

                // ((date("Y", strtotime($temporary_service_date_to)) > date("Y", $your_date)) && $isSummaryPickupDate == false) || 
                if((((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) < date("m", $your_date))) && $isSummaryPickupDate == false) ) {
                    $now_v2 = $new_pickupdate_v2;
                    // $datediff = $now - $your_date;
                    $datediff = $now_v2->diff($your_date_v2)->format('%a');
                } else {
                    if(date("m", strtotime($temporary_service_date_to)) == date("m", $your_date)) {
                        $temponewdate_v2 = $your_date_v2;
                        $datediff = $now_v2->diff($temponewdate_v2)->format('%a');
                    } else {
                        $temponewdate_v2 = new DateTime(date('Y-m-01', strtotime($schedule_pickup_date)));
                        $datediff = $now_v2->diff($temponewdate_v2)->format('%a') + 1;
                    }
                    
                }
                $rounddatediff = $datediff;

                if ($rounddatediff == 0) {
                    $rounddatediff = 1;
                }

                if($cus_value['equipmentID'] != 49 && $cus_value['equipmentID'] != 64 && $cus_value['equipmentID'] != 32 && $cus_value['equipmentID'] != 29 && $cus_value['daily_rate'] != 344 && $cus_value['equipmentID'] == 343) {
                    if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
                        $rounddatediff = 1;
                    }
                }
                
                if($cus_value['equipmentID'] == 176) {
                    $rounddatediff = 1;
                }

                //New Calculation 11/15/19 =======> START
                if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
                    // $dailratetemporary = $cus_value['monthly_rate'];
                    $cus_total = $cus_value['monthly_rate'];
                    if ($cus_value['activity_reference'] == 3 && $cus_value['uniqueID_reference'] != 0) {
                        $cus_total = 0;
                    }
                } else {
                    $temptotaldailyrate = $rounddatediff * $cus_value['daily_rate'];
                    if($temptotaldailyrate > $cus_value['monthly_rate']) {
                        if($cus_value['monthly_rate'] == 0 || $cus_value['monthly_rate'] == null) {
                            $cus_total = $temptotaldailyrate;
                        } else {
                            $cus_total = $cus_value['monthly_rate'];
                            if ($cus_value['activity_reference'] == 3 && $cus_value['uniqueID_reference'] != 0) {
                                $cus_total = 0;
                            }
                        }
                    } else {
                        $cus_total = $temptotaldailyrate;
                    }
                }
                //New Calculation 11/15/19 =======> END

            } 

            if($cus_value['categoryID'] == 3) {
                $quan = $cus_value['equipment_value'];
                if($cus_value['equipment_quantity'] !== "" && $cus_value['equipment_quantity'] !== null) {
                    $quan = $cus_value['equipment_quantity'];
                }

                $cus_total = $quan * $cus_value['purchase_price']; 

            }

            $credit += $cus_total;
        }


		$billing_credit = array(
            "patientID" => $data_post['billing_credit_patientID'],
            "hospiceID" => $data_post['billing_credit_hospiceID'],
            "notes" => $data_post['billing_credit_notes'],
            "service_date" => $schedule_pickup_date,
            "credit" => $credit,
            "uniqueID" => $uniqueID,
			"created_by" => $this->session->userdata('userID')
        );
        
        $save_billing_credit = $this->billing_credit_model->insert_billing_credit($billing_credit);
        
        if ($save_billing_credit) {
            $this->response_code = 0;
            $this->response_message = 'Successfully created credit.';
        } else {
            $this->response_code = 1;
            $this->response_message = 'Failed to create credit';
        }
        echo json_encode(array(
            'error' => $this->response_code,
            'message' => $this->response_message,
            'customer_orders' => $customer_orders,
            'patient_info' => $patient_info,
            'cus_days' => $cus_days,
            'credit' => $credit
        ));
		exit;
	}

}