<style type="text/css">

  #globalModal
  {
    width:1935px !important;
    left: -520px;
  }

  #globalModal .modal-content
  {
    width:1225px !important;
    background-color:#fff !important;
    color:#58666e !important;
  }

  .status-count.status-count-bot{
    margin-left: 30%;
  }

  .status-count li{
    padding-right: 30px;
  }

  .patient-profile-photo{
      width:64px;
      height:64px;
      display: block;
      overflow: hidden;
      border-radius: 50%;
      -webkit-border-radius: 50%;
      -moz-border-radius: 50%;
      background:#fff;
      border:3px solid rgba(200,200,200,.5);
      text-align: center;
      line-height: 64px;
  }

  .under-line {
    text-decoration: none;
    border-bottom: dashed 1px #0088cc;
    padding-left: 10px;
    padding-right: 10px;
  }

  .new-patient-icon{
    font-family: "Goudy Old Style", Garamond, "Big Caslon", "Times New Roman", serif;
    font-weight: bolder;
    margin-left: 5px;
    font-size: 14px;
  }

  .print_label
  {
    font-weight: bold;
  }

  .activity_type_checkboxes
  {
    margin-left: 10%;
  }

  .print_content_label_div
  {
    margin-bottom: 5px;
    font-size:14px !important;
  }

  .td_content
  {
    border-right:1px solid rgba(0, 0, 0, 0.50);
    border-left:1px solid rgba(0, 0, 0, 0.50);
    border-bottom:1px solid rgba(0, 0, 0, 0.50);
    border-top:1px solid rgba(0, 0, 0, 0.50) !important;
  }

  .td_ordered_items
  {
    text-align: center;
    border-right:1px solid rgba(0, 0, 0, 0.50);
    border-left:1px solid rgba(0, 0, 0, 0.50);
    border-bottom:1px solid rgba(0, 0, 0, 0.50);
    border-top:1px solid rgba(0, 0, 0, 0.50) !important;
  }

  .td_ordered_items_empty
  {
    text-align: center;
    border-right:1px solid rgba(0, 0, 0, 0.50);
    border-left:1px solid rgba(0, 0, 0, 0.50);
    border-bottom:1px solid rgba(0, 0, 0, 0.50);
    border-top:1px solid rgba(0, 0, 0, 0.50) !important;
  }

  .ordered_items_table
  {
    border:1px solid rgba(0, 0, 0, 0.50) !important;
    margin-top:30px;
  }

  .delivery_instructions_table
  {
    border-right:1px solid rgba(0, 0, 0, 0.50) !important;
    border-left:1px solid rgba(0, 0, 0, 0.50) !important;
    border-bottom:1px solid rgba(0, 0, 0, 0.50) !important;
    border-top:1px solid rgba(0, 0, 0, 0.50) !important;
    min-height: 80px !important;
  }

  .td_delivery_instructions
  {
    border-right:1px solid rgba(0, 0, 0, 0.50) !important;
    border-left:1px solid rgba(0, 0, 0, 0.50) !important;
    border-bottom:1px solid rgba(0, 0, 0, 0.50) !important;
    border-top:1px solid rgba(0, 0, 0, 0.50) !important;
  }

  .print_content_label_div_first
  {
    margin-top:10px !important;
  }

  .print_content_label_div_second
  {
    margin-bottom:20px !important;
  }

  .work_order_header_container
  {
    background-color: #fff !important;
    padding-top: 0px !important;
    border-bottom: 0px !important;
    margin-bottom:40px;
  }

  @media (max-width: 1225px){

    #globalModal
    {
      left:0 !important;
    }

    .modal-dialog{
      width:100% !important;
      overflow-x:scroll !important;
    }

  }

  @media (max-width: 770px){
    #globalModal
    {
      top:0 !important;
      right:10px !important;
    }
  }

</style>

<?php
  
  if(!empty($informations)) :
    $info = $informations[0];

    if(!empty($activity_fields)):
      $fields = $activity_fields[0];
    endif;

    $logged_in_account_type = "Company";
    $returned_result = '';
    $queried_data = get_patients_first_order_uniqueID_v2($medical_record_id,$hospiceID);
    if($queried_data['order_uniqueID'] != $work_order)
    {
      $returned_result = $queried_data;
    }

    $addressID = 0;
    $address_type_outside = "";
    $count = 1;
    foreach($equipments_ordered as $equipments)
    {
      if($count == 1)
      {
        $addressID = $equipments['addressID'];
        $count++;
      }
      else
      {
        break;
      }
    }
    $equpment_location = get_equipment_location($addressID);
    $address_type_outside = get_address_type($addressID);

    $use_ptmove_address = array();
    $use_respite_address = array();
    if($address_type_outside['type'] == 1)
    {
      $use_ptmove_address = get_ptmove_address_through_address_name_v2($info['patientID'],$equpment_location['street'],$equpment_location['placenum'],$equpment_location['city'],$equpment_location['state'],$equpment_location['postal_code']);
    }
    $respite_checked = "";
    if($address_type_outside['type'] == 2)
    {
      $respite_checked = "checked";
      $first_row_respite_address = get_first_row_respite_address($addressID,$info['patientID']);
    }
?>

    <div class="bg-light lter b-b wrapper-md work_order_header_container" style="background-color:#fff !important; color:#58666e !important;">
      <p class="logo_ahmslv" style="margin-bottom:0px;text-align:center;">
        <img class="logo_ahmslv_img" src="<?php echo base_url('assets/img/smarterchoice_logo.png'); ?>" alt="" style="height:50px;width:58px;"/>
      </p>
      <?php
        $location = get_login_location($this->session->userdata('user_location'));
      ?>
      <h4 class="work_order_header_first" style="font-weight:bold;margin-top:0px;font-size:17px;text-align:center;margin-bottom:13px;"> Advantage Home Medical Services, Inc </h4>
      <h4 class="work_order_header work_order_header_second" style="text-align:center;font-weight:bold;margin-top:-6px;font-size:15px;"> <?php echo $location['user_street']; ?> </h4>
      <div class="print_content_label_div" style="margin-top:-13px;">
        <span class="print_label">Entry Time:</span> <?php echo date("F j, Y, g:i a", $work_order) ?>
      </div>
      <h4 class="work_order_header work_order_header_third" style="text-align:center;font-weight:bold;margin-top:-20px;font-size:15px;"> <?php echo $location['user_city']; ?>, <?php echo $location['user_state']; ?> <?php echo $location['user_postalcode']; ?> </h4>
      <div class="print_content_label_div" style="margin-top:-11px;">
        <span class="print_label">Work Order #:</span> <?php echo substr($work_order,4,10) ?> <?php if(empty($returned_result)){ ?><span class="new-patient-icon">N</span><?php } ?>
      </div>
      <h4 class="work_order_header work_order_header_fourth" style="text-align:center;font-weight:bold;margin-top:-21px;font-size:15px;"> Phone: <?php echo $location['location_phone_no']; ?> &nbsp;  Fax: <?php echo $location['location_fax_no']; ?> </h4>
    </div>

    <div class="col-md-12" style="font-size:14px;">

        <div class="print_content_label_div print_content_label_div_first">
            <span class="print_label">Activity Type:

            <?php
            /****** DELIVERY ******/
            ?>
            <?php
            if($equipments_ordered[0]['initial_order'] == 1 || ($equipments_ordered[0]['pickup_order'] == 0 && $equipments_ordered[0]['activity_typeid'] != 3 && $equipments_ordered[0]['activity_typeid'] != 4) || $equipments_ordered[0]['activity_typeid'] == 5){
            ?>
                <span style="margin-left:1%;" class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="1" checked> Delivery </span>
            <?php
            }else{
            ?>
                <span style="margin-left:1%;" class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="1"> Delivery </span>
            <?php
            }
            ?>

            <?php
            /****** EXCHANGE ******/
            ?>
            <?php
            if($equipments_ordered[0]['activity_typeid'] == 3){
            ?>
                <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="3" checked> Exchange </span>
            <?php
            }else{
            ?>
                <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="3"> Exchange </span>
            <?php
            }
            ?>

            <?php
            /****** PICK UP ******/
            ?>
            <?php
            if($equipments_ordered[0]['original_activity_typeid'] == 2){
            ?>
                <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="2" checked> Pick Up </span>
            <?php
            }else{
            ?>
                <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="2"> Pick Up </span>
            <?php
            }
            ?>

            <?php
            /****** CUS MOVE ******/
            ?>
            <?php
            if($equipments_ordered[0]['activity_typeid'] == 4){
            ?>
                <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="4" checked>  CUS Move </span>
            <?php
            }else{
            ?>
                <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="4">  CUS Move </span>
            <?php
            }
            ?>

            <?php
            /****** RESPITE ******/
            ?>
            <?php
            if($equipments_ordered[0]['activity_typeid'] == 5){
            ?>
                <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="5" checked>  Respite </span>
            <?php
            }else{
            ?>
                <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="5" <?php echo $respite_checked; ?>>  Respite </span>
            <?php
            }
            ?>

            </span>
        </div>

        <div class="print_content_label_div print_content_label_div_second">
            <?php
            /****** DELIVERY ******/
            ?>
            <?php if($equipments_ordered[0]['initial_order'] == 1 || $equipments_ordered[0]['pickup_order'] == 0 && $equipments_ordered[0]['activity_typeid'] != 3 && $equipments_ordered[0]['activity_typeid'] != 4 && $equipments_ordered[0]['activity_typeid'] != 5) :?>
            <span class="print_label">Scheduled Order Date:</span>
            <?php echo date("m/d/Y", strtotime($equipments_ordered[0]['pickup_date'])) ?>
            <?php endif;?>

            <?php
            /****** PICK UP ******/
            ?>
            <?php
            if($equipments_ordered[0]['original_activity_typeid'] == 2) :
            ?>
                <span class="print_label">Scheduled Pickup Date:</span>
                <?php
                if(strtotime($fields['date_pickedup']) == ''):
                    echo date("m/d/Y", strtotime($equipments_ordered[0]['pickup_date']));
                else:
                    echo date("m/d/Y", strtotime($fields['date_pickedup']));
                endif;
                ?>
                <span class="print_label" style="margin-left:1%;">Pickup Reason:</span>
            <?php
                echo ucfirst($fields['pickup_sub']);

                $discharge_date_disp = '';
                switch ($fields['pickup_sub']) {
                case 'expired': $discharge_date_disp = 'Expiration'; break;
                case 'discharged': $discharge_date_disp = 'Discharge'; break;
                case 'revoked': $discharge_date_disp = 'Revocation'; break;
                }
                if ($fields['pickup_sub'] !== 'not needed') {
                    if ($pickup_discharge_date['pickup_discharge_date'] != '' && $pickup_discharge_date['pickup_discharge_date'] != '0000-00-00') {
            ?>
                        &nbsp;
            <?php
                        echo date("m/d/Y", strtotime($pickup_discharge_date['pickup_discharge_date']));
                    }
                }
                endif;
            ?>

            <?php
            /****** EXCHANGE ******/
            ?>
            <?php if($equipments_ordered[0]['activity_typeid'] == 3) :?>
            <span class="print_label">Scheduled Exchange Order Date:</span>
            <?php echo date("m/d/Y", strtotime($fields['exchange_date'])) ?>
            <span class="print_label" style="margin-left:1%;">Reason for Exchange:</span>
            <?php echo $fields['exchange_reason'] ?>
            <?php endif;?>

            <?php
            /****** CUS MOVE ******/
            ?>
            <?php if($equipments_ordered[0]['activity_typeid'] == 4) :?>
            <span class="print_label">CUS Move Scheduled Order Date:</span>
            <?php echo date("m/d/Y", strtotime($fields['ptmove_delivery_date'])) ?>
            <span class="print_label" style="margin-left:1%;">CUS Move Address:</span>
            <?php echo $fields['ptmove_street'] ?>, <?php echo $fields['ptmove_placenum'] ?>, <?php echo $fields['ptmove_city'] ?>, <?php echo $fields['ptmove_state'] ?>, <?php echo $fields['ptmove_postal'] ?>
            <?php endif;?>

            <?php
            /****** RESPITE ******/
            ?>
            <?php if($equipments_ordered[0]['activity_typeid'] == 5) :?>
            <span class="print_label">Respite Scheduled Order Date:</span>
            <?php echo date("m/d/Y", strtotime($fields['respite_delivery_date'])) ?>
            <span class="print_label" style="margin-left:1%;">Respite Pickup Date:</span>
            <?php echo date("m/d/Y", strtotime($fields['respite_pickup_date'])) ?>
            <br />
            <?php endif;?>
        </div>

        <?php
            $addressID = 0;
            $count = 1;
            foreach($equipments_ordered as $equipments)
            {
            if($count == 1)
            {
                $addressID = $equipments['addressID'];
                $count++;
            }
            else
            {
                break;
            }
            }
            $equpment_location = get_equipment_location($addressID);
        ?>

        <table class="table order_info_table" style="border:1px solid rgba(0, 0, 0, 0.50) !important; margin-top:15px;">
            <tbody style="border-top:1px solid;border-top-color:rgba(0, 0, 0, 0.50);">
            <tr>
                <td class="td_content" colspan="4">
                <span class="print_label">Customer name: </span> <?php echo $info['p_lname'].", ".$info['p_fname'] ?>
                </td>
                <td class="td_content">
                <span class="print_label">MR#: </span> <?php echo $medical_record_id ?>
                </td>
            </tr>
            <tr>
                <td class="td_content" style="width:28%;">
                <span class="print_label">Street: </span> <?php echo $equpment_location['street']; ?>
                </td>
                <td class="td_content">
                <span class="print_label">Apt.: </span> <?php echo $equpment_location['placenum']; ?>
                </td>
                <td class="td_content">
                <span class="print_label">City: </span> <?php echo $equpment_location['city']; ?>
                </td>
                <td class="td_content" style="width:13%;">
                <span class="print_label">State: </span> <?php echo $equpment_location['state']; ?>
                </td>
                <td class="td_content" style="width:17%;">
                <span class="print_label">Zip: </span> <?php echo $equpment_location['postal_code']; ?>
                </td>
            </tr>
            <tr>
                <td class="td_content" style="width:28%;">
                <span class="print_label">Phone #: </span>
                <?php 
                    if ($equpment_location['phonenum'] == '') {
                    echo 'N/A';
                    } else {
                    echo $equpment_location['phonenum'];
                    }
                ?>
                </td>
                <td class="td_content">
                <span class="print_label">Alt. Phone #: </span>
                <?php 
                    if ($equpment_location['altphonenum'] == '') {
                    echo 'N/A';
                    } else {
                    echo $equpment_location['altphonenum'];
                    }
                ?>
                </td>
                <td class="td_content">
                <span class="print_label">Sex: </span>
                <?php
                    if($info['relationship_gender'] == 1):
                ?>
                    Male
                <?php
                    else:
                ?>
                    Female
                <?php
                    endif;
                ?>
                </td>
                <td class="td_content" style="width:13%;">
                <span class="print_label">Height: </span> <?php echo $info['p_height']; ?>
                </td>
                <td class="td_content" style="width:17%;">
                <span class="print_label">Weight: </span> <?php echo $info['p_weight'] ?>
                </td>
            </tr>
            <tr>
                <td class="td_content" >
                <span class="print_label">Next of Kin: </span>
                <?php 
                    if ($equpment_location['nextofkin'] == '') {
                    echo 'N/A';
                    } else {
                    echo $equpment_location['nextofkin'];
                    }
                ?>
                </td>
                <td class="td_content">
                <span class="print_label">Relationship: </span>
                <?php 
                    if ($equpment_location['relationship'] == '') {
                    echo 'N/A';
                    } else {
                    echo $equpment_location['relationship'];
                    }
                ?>
                </td>
                <td class="td_content">
                <span class="print_label">Phone: </span>
                <?php 
                    if ($equpment_location['nextofkinnum'] == '') {
                    echo 'N/A';
                    } else {
                    echo $equpment_location['nextofkinnum'];
                    }
                ?>
                </td>
                <td class="td_content" colspan="2">
                <span class="print_label">Residence: </span>
                <?php 
                    if ($equpment_location['deliver_to_type'] == '') {
                    echo ' ';
                    } else {
                    echo $equpment_location['deliver_to_type'];
                    }
                ?>
                </td>
            </tr>
            <tr>
                <td class="td_content"">
                <span class="print_label">Provider: </span> <?php echo $info['hospice_name'] ?>
                </td>
                <td class="td_content">
                <span class="print_label">Phone: </span>
                <?php
                    if($info['contact_num'] == ''):
                ?>
                    N/A
                <?php
                    else:
                    echo $info['contact_num'];
                    endif;
                ?>
                </td>
                <td class="td_content">
                <span class="print_label">Ordered By: </span>
                <?php
                    if($equipments_ordered[0]['who_ordered_lname'] == '' && $equipments_ordered[0]['who_ordered_fname'] == ''):
                ?>
                    NA
                <?php
                    else:
                    echo $equipments_ordered[0]['who_ordered_fname']  ." ". $equipments_ordered[0]['who_ordered_lname'];
                    endif;
                ?>
                </td>
                <td class="td_content" colspan="2">
                <span class="print_label">Phone: </span>
                <?php
                    if($equipments_ordered[0]['who_ordered_cpnum'] == ''):
                ?>
                    N/A
                <?php
                    else:
                    echo $equipments_ordered[0]['who_ordered_cpnum'];
                    endif;
                ?>
                </td>
            </tr>
            <tr>
                <td class="td_content last_row_order_info_table" colspan="2">
                <?php
                    $liter_flow = get_liter_flow($hospiceID,$medical_record_id);

                    if($liter_flow['parentID'] != 61 || $liter_flow['parentID'] != 29 || $liter_flow['parentID'] != 36)
                    {
                    //get latest equipment ordered with those ID's from above and get the parent id
                    $parentID = get_parent_id($hospiceID,$medical_record_id);
                    $liter_flow['parentID'] = $parentID['parentID'];
                    $liter_flow['uniqueID'] = $parentID['uniqueID'];
                    }
                    $parentID_duration = get_parent_id_duration($hospiceID,$medical_record_id);
                    $equipmentID_duration = $parentID_duration['parentID'];
                    $getduration_choices = get_equipment_choices($equipmentID_duration,6); //6 is the option ID of Duration refer on the table dme_options

                    //getting options of the equiptment
                    $duration_options_format = array();
                    $get_duration_ids = array();

                    foreach ($getduration_choices as $key => $value)
                    {
                        $duration_options_format[] = array(
                                                        "value" => $value['equipmentID'],
                                                        "text"  => $value['key_desc']
                                                    );
                        $get_duration_ids[] = $value['equipmentID'];
                    }
                    $get_o2_durations   = get_duration_order($get_duration_ids,$parentID_duration['uniqueID']);

                    $duration_ = "";
                    $duration_array = array();
                    $getduration_choices = array();
                    if(!empty($get_o2_durations))
                    {
                    $duration_      = get_equipment_name($get_o2_durations['equipmentID']);
                    $duration_array = $get_o2_durations;
                    }

                    //get BIPAP SETTINGS
                    $IPAP = get_ipap($hospiceID,$medical_record_id);
                    $EPAP = get_epap($hospiceID,$medical_record_id);
                    $rate = get_rate($hospiceID,$medical_record_id);

                    //get CIPAP SETTINGS
                    $CIPAP = get_cipap($hospiceID,$medical_record_id);
                ?>
                <span class="print_label">RX: </span>

                <?php
                    if(!empty($liter_flow))
                    {
                    if($liter_flow['parentID'] != "")
                    {
                ?>
                        <span style="padding-right:20px;">O2 Liter Flow: &nbsp;&nbsp;
                        <strong> <?php echo $liter_flow['equipment_value']; ?> </strong>
                <?php
                        if(!empty($duration_)){
                            $delivery_device_word = "";
                            $latest_delivery_device = get_latest_delivery_device($hospiceID,$medical_record_id);
                            if(!empty($latest_delivery_device))
                            {
                            if($latest_delivery_device['key_name'] == "nasal_canula")
                            {
                                $delivery_device_word = "NC";
                            }
                            else if($latest_delivery_device['key_name'] == "oxygen_mask")
                            {
                                $delivery_device_word = "MSK";
                            }
                            }
                            if(!empty($delivery_device_word))
                            {
                ?>
                            &nbsp;<strong> <?php echo $duration_['key_desc']; ?> W/ <?php echo $delivery_device_word; ?> </strong>
                <?php
                            }
                            else
                            {
                ?>
                            &nbsp;<strong> <?php echo $duration_['key_desc']; ?> </strong>
                <?php
                            }
                        }
                ?>
                        </span>
                <?php
                    }
                    }
                    if(!empty($IPAP) || !empty($EPAP) || !empty($rate))
                    {
                ?>
                    <span style="padding-right:30px;">BIPAP Settings: &nbsp;IPAP <strong> <?php echo $IPAP['equipment_value']; ?> </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EPAP <strong > <?php echo $EPAP['equipment_value']; ?> </strong><?php if(!empty($rate)){ ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RATE <strong> <?php echo $rate['equipment_value']; ?> </strong> <?php } ?></span>
                <?php
                    }
                    if(!empty($CIPAP))
                    {
                ?>
                    <span style="padding-right:30px;">CPAP Settings: &nbsp;CMH20 <strong> <?php echo $CIPAP['equipment_value']; ?> </strong> </span>
                <?php
                    }
                ?>
                </td>
                <td class="td_content last_row_order_info_table">
                <?php
                $response = get_items_for_pickup($medical_record_id,$hospiceID);
                $old_address_result = array();
                if(!empty($response))
                {
                    foreach($response as $key=>$value)
                    {
                        $cat_ = $value['type'];

                        if($value['parentID']==0)
                        {
                            $old_address_result[$cat_][trim($value['key_desc'])][] = $value;
                        }
                        else
                        {
                            $old_address_result[$cat_][trim($value['parent_name'])]['children'][] = $value;
                        }
                    }
                }

                // $patientID = get_patientID($medical_record_id,$hospiceID);
                $patient_addresses = get_patient_addresses($info['patientID']);
                $new_response = array();
                $new_addresses_response = array();
                $data['addressID'] = array();

                foreach ($patient_addresses as $key=>$value) {
                    $new_response_query = get_items_for_pickup_other_address($medical_record_id,$hospiceID,$value['id']);
                    if($new_response_query)
                    {
                    $new_response[] = $new_response_query;
                    $data['addressID'][] = $value['id'];
                    }
                }

                $my_count = 0;
                if(!empty($new_response))
                {
                    foreach ($new_response as $new_response_loop) {
                    foreach($new_response_loop as $key=>$value)
                        {
                            $cat_ = $value['type'];

                            if($value['parentID']==0)
                            {
                            $new_addresses_response[$value['id']][$cat_][trim($value['key_desc'])][] = $value;
                            }
                            else
                            {
                            $new_addresses_response[$value['id']][$cat_][trim($value['parent_name'])]['children'][] = $value;
                            }
                        }
                        $my_count++;
                    }
                }

                // The counting of the no. of addresses will start here
                $no_of_addresses = 0;
                $counter_old_address = 0;
                foreach ($old_address_result as $key => $value) {
                    if($key == "Capped Item" || $key == "Non-Capped Item")
                    {
                    $counter_old_address++;
                    }
                    else
                    {
                    if(isset($value['Oxygen Cylinder, M6 Refill']) || isset($value['Oxygen Cylinder, E Refill']))
                    {
                        $counter_old_address++;
                    }
                    }
                }
                if($counter_old_address > 0)
                {
                    $no_of_addresses++;
                }

                $categories_equip = array(1, 2, 3);
                $counter_patient_move_address = 0;
                $counter_patient_move_address_array = array();
                $counter_respite_address = 0;
                $counter_respite_address_array = array();
                foreach ($new_addresses_response as $key => $value) {
                    $address_type_loop = get_address_type($key);
                    if($address_type_loop['type'] == 1)
                    {
                    if(isset($value['Capped Item']) || isset($value['Non-Capped Item']))
                    {
                        foreach ($value as $keys=>$equip_orders)
                        {
                        foreach ($equip_orders as $sub_key=>$sub_value)
                        {
                            if(in_array($sub_value[0]['categoryID'], $categories_equip))
                            {
                            if(isset($sub_value['children']))
                            {
                                if(!empty($sub_value[0])):
                                if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                    if($sub_value[0]['categoryID'] == 3){
                                    if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170){
                                        $counter_patient_move_address++;
                                    }
                                    }else{
                                    $counter_patient_move_address++;
                                    }
                                endif;
                                endif;

                                if(!empty($sub_value[1])) :
                                if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :
                                    if($sub_value[0]['categoryID'] == 3) {
                                    if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170) {
                                        $counter_patient_move_address++;
                                    }
                                    }else{
                                    $counter_patient_move_address++;
                                    }
                                endif;
                                endif;
                            }
                            else
                            {
                                if(!empty($sub_value[0]))
                                {
                                if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1)
                                {
                                    $counter_patient_move_address++;
                                }
                                }
                                if(!empty($sub_value[1]))
                                {
                                if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1)
                                {
                                    $counter_patient_move_address++;
                                }
                                }
                            }
                            }
                        }
                        }
                    }
                    else
                    {
                        if(isset($value['Disposable Items']['Oxygen Cylinder, M6 Refill']) || isset($value['Disposable Items']['Oxygen Cylinder, E Refill']))
                        {
                        $counter_patient_move_address++;
                        }
                    }
                    if($counter_patient_move_address > 0)
                    {
                        $no_of_addresses++;
                    }
                    $counter_patient_move_address_array[] = array($key => $counter_patient_move_address);
                    }
                    else
                    {
                    if(isset($value['Capped Item']) || isset($value['Non-Capped Item']))
                    {
                        foreach ($value as $keys=>$equip_orders)
                        {
                        foreach ($equip_orders as $sub_key=>$sub_value)
                        {
                            if(in_array($sub_value[0]['categoryID'], $categories_equip))
                            {
                            if(isset($sub_value['children']))
                            {
                                if(!empty($sub_value[0])):
                                if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                    if($sub_value[0]['categoryID'] == 3){
                                    if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170){
                                        $counter_respite_address++;
                                    }
                                    }else{
                                    $counter_respite_address++;
                                    }
                                endif;
                                endif;

                                if(!empty($sub_value[1])) :
                                if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :
                                    if($sub_value[0]['categoryID'] == 3) {
                                    if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170) {
                                        $counter_respite_address++;
                                    }
                                    }else{
                                    $counter_respite_address++;
                                    }
                                endif;
                                endif;
                            }
                            else
                            {
                                if(!empty($sub_value[0]))
                                {
                                if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1)
                                {
                                    $counter_respite_address++;
                                }
                                }
                                if(!empty($sub_value[1]))
                                {
                                if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1)
                                {
                                    $counter_respite_address++;
                                }
                                }
                            }
                            }
                        }
                        }
                    }
                    else
                    {
                        if(isset($value['Disposable Items']['Oxygen Cylinder, M6 Refill']) || isset($value['Disposable Items']['Oxygen Cylinder, E Refill']))
                        {
                        $counter_respite_address++;
                        }
                    }
                    if($counter_respite_address > 0)
                    {
                        $no_of_addresses++;
                    }
                    $counter_respite_address_array[] = array($key => $counter_respite_address);
                    }
                }
                ?>
                <span class="print_label">No. of Location(s): </span> <?php echo $no_of_addresses; ?>
                </td>
                <td class="td_content last_row_order_info_table" colspan="2">
                <span class="print_label">Work Order Confirmed By: </span>
                <?php
                    if($equipments_ordered[0]['person_confirming_order'] == ''):
                    echo "";
                    else:
                    echo $equipments_ordered[0]['person_confirming_order'];
                    endif;
                ?>
                </td>
            </tr>
            </tbody>
        </table>

        <table class="table ordered_items_table">
            <tbody style="border-top:1px solid;border-top-color:rgba(0, 0, 0, 0.50);">
            <tr>
                <td class="td_ordered_items" style="width:7%;">
                <span class="print_label">Del/PU/Ex </span>
                </td>
                <td class="td_ordered_items" style="width:8%;">
                <span class="print_label">Sale/Rental </span>
                </td>
                <td class="td_ordered_items" style="width:8%;">
                <span class="print_label">Item# </span>
                </td>
                <td class="td_ordered_items" style="width:6%;">
                <span class="print_label"> WHSE </span>
                </td>
                <td class="td_ordered_items" style="width:45%;">
                <span class="print_label">Item Description </span>
                </td>
                <td class="td_ordered_items" style="width:6%;">
                <span class="print_label">Qty </span>
                </td>
                <td class="td_ordered_items" style="width:20%;">
                <span class="print_label">Serial / Lot Number </span>
                </td>
            </tr>
            <?php
            $item_in_order_count = 0;
            if(!empty($equipments_ordered)):
                foreach($equipments_ordered as $loop_count):
                if($loop_count['parentID'] == 0)
                {
                    $item_in_order_count++;
                }
                endforeach;
            endif;

            if($act_type_id == 3)
            {
                $item_in_order_count *= 2;
            }

            if(!empty($equipments_ordered)):
                $packaged_items_ids_list = [486,163,164,68,159,160,161,162,316,325,334,343,466,36,178,422,259,415,174,490,492,67,157];
                $packaged_item_sign = 0;
                $packaged_items_list = array();
                $counter_sub_2 = 0;
                $item_description_count = 1;
                $last_equipmentID = 0;
                $last_equipmentID_count = 1;
                $item_description = "";
                foreach($equipments_ordered as $summary):
                if($summary['parentID'] == 0)
                {
                    if(!in_array($summary['equipmentID'], $packaged_items_ids_list))
                    {
                    $line_through = "";
                    if($summary['canceled_order'] == 1) {
                        $line_through = "text-decoration: line-through;";
                    }
            ?>
                    <tr>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                        <?php
                            if($summary['initial_order'] == 1 || $summary['pickup_order'] == 0 && $summary['activity_typeid'] != 3 && $summary['activity_typeid'] != 4 && $summary['activity_typeid'] != 5)
                            {
                            echo "D";
                            }
                            else if($summary['original_activity_typeid'] == 2)
                            {
                            echo "P";
                            }
                            else if($summary['activity_typeid'] == 4 || $summary['activity_typeid'] == 5)
                            {
                            echo "D";
                            }
                            else if($act_type_id == 3)
                            {
                            echo "D";
                            }

                        ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                        <?php
                            if($summary['type'] == "Capped Item" || $summary['type'] == "Non-Capped Item")
                            {
                            echo "R";
                            }
                            else
                            {
                            echo "S";
                            }
                        ?>
                        </td>

                        <?php
                        $display_item_no = "";
                        $display_warehouse_location = array();
                        if($summary['equipment_company_item_no'] != "0")
                        {
                            $display_item_no = $summary['equipment_company_item_no'];
                            // $display_warehouse_location = get_item_warehouse_location($this->session->userdata('user_location'),$summary['equipment_company_item_no']);
                        }
                        else
                        {
                            $subequipments = get_subequipment_id_v2($summary['equipmentID']);
                            foreach ($subequipments as $key) {
                            if($key['equipment_company_item_no'] != "0")
                            {
                                $used_subequipment = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
                                if(!empty($used_subequipment))
                                {
                                $display_item_no = $key['equipment_company_item_no'];
                                // $display_warehouse_location = get_item_warehouse_location($this->session->userdata('user_location'),$summary['equipment_company_item_no']);
                                break;
                                }
                            }
                            }
                        }
                        ?>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                        <?php echo $display_item_no; ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                        <?php
                            // if(!empty($display_warehouse_location))
                            // {
                            //   echo $display_warehouse_location[0]['']
                            // }
                        // print_me($display_warehouse_location);
                        ?>
                        </td>
                        <td class="td_ordered_items" style="text-transform:uppercase !important; <?php echo $line_through; ?>">
                        <?php

                            if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343) :
                            echo $summary['key_desc'];
                            else:
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
                                    if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20){
                                    echo $summary['key_desc']." With ".$key['key_desc']."";
                                    }
                                    //Hi-Low Full Electric Hospital Bed equipment
                                    else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
                                    {
                        ?>
                                    <?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    //Patient Lift with Sling
                                    else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
                                    {
                        ?>
                                    <?php echo "Patient Lift With ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    //Patient Lift Electric with Sling
                                    else if($summary['equipmentID'] == 353)
                                    {
                        ?>
                                    <?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    //Patient Lift Sling
                                    else if($summary['equipmentID'] == 196)
                                    {
                        ?>
                                    <?php echo $key['key_desc']; ?>
                        <?php
                                    }
                                    //(54 & 17) Geri Chair || (66 & 39) Shower Chair
                                    else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
                                    {
                        ?>
                                    <?php echo $summary['key_desc']." ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    // Oxygen E Portable System && Oxygen Liquid Portable
                                    else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
                                    {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                    break;
                                    }
                                    //Oxygen Cylinder Rack
                                    else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                                    {
                        ?>
                                    Oxygen <?php echo $key['key_desc']; ?>
                        <?php
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
                                        echo $key['key_desc']." ".$summary['key_desc'];
                                        }
                                        else
                                        {
                                        echo " With ".$key['key_desc'];
                                        }
                                    }
                                    else
                                    {
                        ?>
                                        20" Wide
                        <?php
                                        echo $summary['key_desc'];
                                        if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
                                        {
                                        echo " With Elevating Legrests";
                                        break;
                                        }
                                        else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
                                        {
                                        echo " With Footrests";
                                        break;
                                        }
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
                                    echo $key['key_desc']." ".$summary['key_desc'];
                                    }
                                    else if($summary['equipmentID'] == 30)
                                    {
                                    if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
                                    {
                                        // if($summary['original_activity_typeid']== 3)
                                        // {
                                        //   echo $summary['key_desc'];
                                        // }
                                        // else
                                        // {
                                        if($count == 3)
                                        {
                        ?>
                                            <?php echo $summary['key_desc'] ?>
                        <?php
                                            echo " With ".$key['key_desc'];
                                        }
                                        //}
                                    }
                                    else
                                    {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                    }
                                    }
                                    //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
                                    else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
                                    {
                        ?>
                                    <?php echo $summary['key_desc']; ?>
                                    <br />
                                    <?php
                                    $samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
                                    if(!empty($samp))
                                    {
                                        echo "<span class='misc_item_description' style='font-weight:400;color:rgba(0, 0, 0, 0.72);font-size:11px;'>".$samp."</span>";
                                    }
                                    else
                                    {
                                        if($summary['original_activity_typeid'] == 2)
                                        {
                                        $equipment_delivery = get_equipment_delivery($summary['equipmentID'],$summary['uniqueID']);
                                        $samp =  get_misc_item_description($summary['equipmentID'],$equipment_delivery['uniqueID']);

                                        echo "<span class='misc_item_description' style='font-weight:400;color:rgba(0, 0, 0, 0.72);font-size:11px;'>".$samp."</span>";
                                        }
                                    }
                                    break;
                                    }
                                    else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                                    {
                                    $samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
                                    if($count == 1)
                                    {
                        ?>
                                        <?php echo $summary['key_desc']; ?> <?php echo $samp_conserving_device; ?>
                        <?php
                                    }
                                    }
                                    else if($summary['equipmentID'] == 282)
                                    {

                                    }
                                    //equipments that has no subequipment but gets inside the $value if statement
                                    else if($summary['equipmentID'] == 14)
                                    {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                    }
                                    else
                                    {
                                    if($summary['categoryID'] == 1)
                                    {
                                        $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                        if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                                        {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 14)
                                        {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 282)
                                        {
                                        $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                        ?>
                                        <?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?>
                        <?php
                                        break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                                        {
                        ?>
                                        <?php echo "Patient Lift With ".$key['key_desc'].""; ?>
                        <?php
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 353)
                                        {
                        ?>
                                        <?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?>
                        <?php
                                        }
                                        else
                                        {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                    }
                                    else
                                    {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
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
                        ?>
                                    <?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?>
                        <?php
                                    break;
                                }
                                //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
                                else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
                                {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                    break;
                                } //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
                                else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
                                {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                }
                                //for equipments with subequipment but does not fall in $value
                                else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
                                {
                                    if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
                                    {
                                    $patient_lift_sling_count++;
                                    if($patient_lift_sling_count == 6)
                                    {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                    }
                                    }
                                    else if($summary['equipmentID'] == 398)
                                    {
                                    if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
                                    {
                                        $high_low_full_electric_hospital_bed_count++;
                                        if($high_low_full_electric_hospital_bed_count == 2){
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                    }
                                    }
                                    else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                                    {
                                    $equipment_count++;
                                    if($equipment_count == 2){
                                        echo $summary['key_desc'];
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
                        ?>
                                        <?php echo $summary['key_desc']; ?>
                        <?php
                                        break;
                                    }
                                    else if($non_capped_copy['noncapped_reference'] == 14)
                                    {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                    }
                                    else
                                    {

                                    }
                                    }
                                    else
                                    {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                    }
                                }
                                }
                            }
                            else
                            {
                                if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
                                {
                        ?>
                                <?php echo $summary['key_desc'] ?>
                        <?php
                                }
                                else
                                {
                        ?>
                                <?php echo $summary['key_desc'] ?>
                        <?php
                                }
                            }
                            endif;
                        ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                        <?php
                            $quantity = 1;
                            if($summary['categoryID']!=3) //cappped=1, noncapped=2
                            {
                            //if noncapped get children quantities
                            if($summary['categoryID']==2)
                            {
                                if($summary['parentID']==0 AND $summary['equipment_value']>1)
                                {
                                $quantity = $summary['equipment_value'];
                                }
                                else
                                {
                                if($summary['equipmentID'] == 4 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 30)
                                {
                                    if(empty($summary['equipment_value']))
                                    {
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = ($temp>0)? $temp : 1;
                                    }
                                    else
                                    {
                                    $quantity = $summary['equipment_value'];
                                    }
                                }
                                else if($summary['equipmentID'] == 14)
                                {
                                    if($summary['order_status'] == "confirmed")
                                    {
                                    $quantity = $summary['equipment_value'];
                                    }
                                    else
                                    {
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = $temp;
                                    }
                                }
                                else
                                {
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = ($temp>0)? $temp : 1;
                                }
                                }
                            }
                            else //capped items
                            {
                                $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                //if the equipment is miscellaneous capped item
                                if($summary['equipmentID'] == 313 || $summary['equipmentID'] == 206)
                                {
                                $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                $quantity = ($temp>0)? $temp : 1;
                                }else if($non_capped_copy['noncapped_reference'] == 14){
                                $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                $quantity = ($temp>0)? $temp : 1;
                                }else {
                                $quantity = ($summary['equipment_value']>0)? $summary['equipment_value'] : 1;
                                }
                            }
                            }
                            else //disposable items
                            {
                            if($summary['equipment_value'] > 1)
                            {
                                $quantity = $summary['equipment_value'];
                            }
                            else
                            {
                                $quantity = (get_disposable_quantity($summary['equipmentID'], $summary['uniqueID'])>0)? get_disposable_quantity($summary['equipmentID'], $summary['uniqueID']) : $summary['equipment_value'];
                                if($summary['equipment_value'] == 0)
                                {
                                $quantity = 0;
                                }

                                if(empty($summary['equipment_value']))
                                {
                                $quantity = get_disposable_quantity($summary['equipmentID'],$summary['uniqueID']);
                                if(empty($quantity))
                                {
                                    $quantity = 1;
                                }
                                }
                            }
                            }
                            echo $quantity;
                        ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                        <?php
                            if($equipments_ordered[0]['original_activity_typeid'] == 2)
                            {
                            if($summary['activity_typeid'] == 2)
                            {
                                $sign_repeated = 0;
                                foreach ($repeating_equipment as $loop_repeating_equipment) {
                                if($loop_repeating_equipment == $summary['equipmentID'])
                                {
                                    $sign_repeated = 1;
                                }
                                }
                                if($sign_repeated == 1)
                                {
                                if($last_equipmentID == 0)
                                {
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                }
                                if($last_equipmentID == $summary['equipmentID'] && $last_equipmentID != 0)
                                {
                                    $last_equipmentID_count++;
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                }
                                else
                                {
                                    $last_equipmentID_count = 1;
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                }
                                $separated_serial_lot_no = explode(",",$queried_serial_number);
                                if(count($separated_serial_lot_no) > 1)
                                {
                                    echo $separated_serial_lot_no[0];
                                }
                                else
                                {
                                    echo $queried_serial_number;
                                }
                                }
                                else
                                {
                                $parentID = get_parental_id($summary['equipmentID']);
                                if(!empty($parentID))
                                {
                                    $get_serial_num = get_serial_num_parent($parentID['parentID'], $summary['uniqueID']);
                                    if($get_serial_num)
                                    {
                                    //Miscellaneous CAPPED and NONCAPPED
                                    if($summary['equipmentID'] == 309 || $summary['equipmentID'] == 306)
                                    {
                                        echo get_original_serial_number_v2($summary['equipmentID'], $summary['medical_record_id'], $summary['uniqueID'], $item_description_count);
                                        $item_description_count++;
                                    }else{
                                        $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                        if(count($separated_serial_lot_no) > 1)
                                        {
                                        echo $separated_serial_lot_no[0];
                                        }
                                        else
                                        {
                                        echo $get_serial_num['serial_num'];
                                        }
                                    }
                                    }else{
                                    $get_serial_num = get_serial_num_parent($summary['equipmentID'], $summary['uniqueID']);
                                    $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                    if(count($separated_serial_lot_no) > 1)
                                    {
                                        echo $separated_serial_lot_no[0];
                                    }
                                    else
                                    {
                                        echo $get_serial_num['serial_num'];
                                    }
                                    }
                                }
                                else
                                {
                                    if($summary['original_activity_typeid'] == 2)
                                    {
                                    if($summary['equipmentID'] == 181)
                                    {
                                        $get_serial_num_chair_scale = get_original_serial_number_v2($summary['equipmentID'], $summary['medical_record_id'], $summary['uniqueID'], $scale_chair_count);
                                    }
                                    else
                                    {
                                        $get_serial_num = get_serial_num_parent_v2($summary['equipmentID'], $summary['uniqueID']);
                                    }
                                    }
                                    else
                                    {
                                    $get_serial_num = get_serial_num_parent($summary['equipmentID'], $summary['uniqueID']);
                                    }
                                    if(!empty($get_serial_num))
                                    {
                                    $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                    if(count($separated_serial_lot_no) > 1)
                                    {
                                        echo $separated_serial_lot_no[0];
                                    }
                                    else
                                    {
                                        echo $get_serial_num['serial_num'];
                                    }
                                    }
                                    else if(!empty($get_serial_num_chair_scale))
                                    {
                                    $separated_serial_lot_no = explode(",",$get_serial_num_chair_scale);
                                    if(count($separated_serial_lot_no) > 1)
                                    {
                                        echo $separated_serial_lot_no[0];
                                    }
                                    else
                                    {
                                        echo $get_serial_num_chair_scale;
                                    }
                                    }
                                }  // ENDS HERE
                                }
                            }
                            }
                            else
                            {
                            if(combine_name(array($summary['serial_num'],$summary['lot_num']))!=""):
                                $serial_lot_no = combine_name(array($summary['serial_num'],$summary['lot_num']));
                                if($serial_lot_no != "pickup_order_only")
                                {
                                $separated_serial_lot_no = explode(",",$serial_lot_no);
                                if(count($separated_serial_lot_no) > 1)
                                {
                                    echo $separated_serial_lot_no[0];
                                }
                                else
                                {
                                    echo $serial_lot_no;
                                }
                                }
                            endif;
                            }
                        ?>
                        </td>
                    </tr>
            <?php
                    }
                    else
                    {
                    $packaged_item_sign = 1;
                    if($summary['equipmentID'] == 486 || $summary['equipmentID'] == 163 || $summary['equipmentID'] == 164)
                    {
                        $packaged_items_list[0][] = $summary;
                    }
                    else if($summary['equipmentID'] == 68 || $summary['equipmentID'] == 159 || $summary['equipmentID'] == 160 || $summary['equipmentID'] == 161 || $summary['equipmentID'] ==162)
                    {
                        $packaged_items_list[1][] = $summary;
                    }
                    else if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343 || $summary['equipmentID'] == 466)
                    {
                        $packaged_items_list[2][] = $summary;
                    }
                    else if($summary['equipmentID'] == 36 || $summary['equipmentID'] == 466 || $summary['equipmentID'] == 178)
                    {
                        $packaged_items_list[3][] = $summary;
                    }
                    else if($summary['equipmentID'] == 422 || $summary['equipmentID'] == 259)
                    {
                        $packaged_items_list[4][] = $summary;
                    }
                    else if($summary['equipmentID'] == 415 || $summary['equipmentID'] == 259)
                    {
                        $packaged_items_list[5][] = $summary;
                    }
                    else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 490 || $summary['equipmentID'] ==492)
                    {
                        $packaged_items_list[6][] = $summary;
                    }
                    else if($summary['equipmentID'] == 67 || $summary['equipmentID'] == 157)
                    {
                        $packaged_items_list[7][] = $summary;
                    }
                    }
                }
                endforeach;
                if(!empty($packaged_items_list))
                {
                foreach($packaged_items_list as $new_item_list)
                {
                    foreach ($new_item_list as  $summary)
                    {
                    if($summary['parentID'] == 0)
                    {
                        $line_through = "";
                        if($summary['canceled_order'] == 1) {
                        $line_through = "text-decoration: line-through;";
                        }
                ?>
                        <tr>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                            if($summary['initial_order'] == 1 || $summary['pickup_order'] == 0 && $summary['activity_typeid'] != 3 && $summary['activity_typeid'] != 4 && $summary['activity_typeid'] != 5)
                            {
                                echo "D";
                            }
                            else if($summary['original_activity_typeid'] == 2)
                            {
                                echo "P";
                            }
                            else if($summary['activity_typeid'] == 4 || $summary['activity_typeid'] == 5)
                            {
                                echo "D";
                            }
                            else if($act_type_id == 3)
                            {
                                echo "D";
                            }
                            ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                            if($summary['type'] == "Capped Item" || $summary['type'] == "Non-Capped Item")
                            {
                                echo "R";
                            }
                            else
                            {
                                echo "S";
                            }
                            ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                            if($summary['equipment_company_item_no'] != "0")
                            {
                                echo $summary['equipment_company_item_no'];
                            }
                            else
                            {
                                $subequipments = get_subequipment_id_v2($summary['equipmentID']);
                                foreach ($subequipments as $key) {
                                if($key['equipment_company_item_no'] != "0")
                                {
                                    $used_subequipment = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
                                    if(!empty($used_subequipment))
                                    {
                                    echo $key['equipment_company_item_no'];
                                    }
                                }
                                }
                            }
                            ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">

                        </td>
                        <td class="td_ordered_items" style="text-transform:uppercase !important; <?php echo $line_through; ?>">
                            <?php

                            if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343) :
                                echo $summary['key_desc'];
                            else:
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
                                    if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20){
                                        echo $summary['key_desc']." With ".$key['key_desc']."";
                                    }
                                    //Hi-Low Full Electric Hospital Bed equipment
                                    else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
                                    {
                        ?>
                                        <?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    //Patient Lift with Sling
                                    else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
                                    {
                        ?>
                                        <?php echo "Patient Lift With ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    //Patient Lift Electric with Sling
                                    else if($summary['equipmentID'] == 353)
                                    {
                        ?>
                                        <?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    //Patient Lift Sling
                                    else if($summary['equipmentID'] == 196)
                                    {
                        ?>
                                        <?php echo $key['key_desc']; ?>
                        <?php
                                    }
                                    //(54 & 17) Geri Chair || (66 & 39) Shower Chair
                                    else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
                                    {
                        ?>
                                        <?php echo $summary['key_desc']." ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    // Oxygen E Portable System && Oxygen Liquid Portable
                                    else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
                                    {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        break;
                                    }
                                    //Oxygen Cylinder Rack
                                    else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                                    {
                        ?>
                                        Oxygen <?php echo $key['key_desc']; ?>
                        <?php
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
                                            echo $key['key_desc']." ".$summary['key_desc'];
                                        }
                                        else
                                        {
                                            echo " With ".$key['key_desc'];
                                        }
                                        }
                                        else
                                        {
                        ?>
                                        20" Wide
                        <?php
                                        echo $summary['key_desc'];
                                        if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
                                        {
                                            echo " With Elevating Legrests";
                                            break;
                                        }
                                        else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
                                        {
                                            echo " With Footrests";
                                            break;
                                        }
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
                                        echo $key['key_desc']." ".$summary['key_desc'];
                                    }
                                    else if($summary['equipmentID'] == 30)
                                    {
                                        if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
                                        {
                                        // if($summary['original_activity_typeid']== 3)
                                        // {
                                        //   echo $summary['key_desc'];
                                        // }
                                        // else
                                        // {
                                            if($count == 3)
                                            {
                        ?>
                                            <?php echo $summary['key_desc'] ?>
                        <?php
                                            echo " With ".$key['key_desc'];
                                            }
                                        //}
                                        }
                                        else
                                        {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                    }
                                    //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
                                    else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
                                    {
                        ?>
                                        <?php echo $summary['key_desc']; ?>
                                        <br />
                                        <?php
                                        $samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
                                        if(!empty($samp))
                                        {
                                        echo "<span class='misc_item_description' style='font-weight:400;color:rgba(0, 0, 0, 0.72);font-size:11px;'>".$samp."</span>";
                                        }
                                        else
                                        {
                                        if($summary['original_activity_typeid'] == 2)
                                        {
                                            $equipment_delivery = get_equipment_delivery($summary['equipmentID'],$summary['uniqueID']);
                                            $samp =  get_misc_item_description($summary['equipmentID'],$equipment_delivery['uniqueID']);

                                            echo "<span class='misc_item_description' style='font-weight:400;color:rgba(0, 0, 0, 0.72);font-size:11px;'>".$samp."</span>";
                                        }
                                        }
                                        break;
                                    }
                                    else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                                    {
                                        $samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
                                        if($count == 1)
                                        {
                        ?>
                                        <?php echo $summary['key_desc']; ?> <?php echo $samp_conserving_device; ?>
                        <?php
                                        }
                                    }
                                    else if($summary['equipmentID'] == 282)
                                    {

                                    }
                                    //equipments that has no subequipment but gets inside the $value if statement
                                    else if($summary['equipmentID'] == 14)
                                    {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                    }
                                    else
                                    {
                                        if($summary['categoryID'] == 1)
                                        {
                                        $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                        if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                                        {
                        ?>
                                            <?php echo $summary['key_desc'] ?>
                        <?php
                                            break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 14)
                                        {
                        ?>
                                            <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 282)
                                        {
                                            $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                        ?>
                                            <?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?>
                        <?php
                                            break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                                        {
                        ?>
                                            <?php echo "Patient Lift With ".$key['key_desc'].""; ?>
                        <?php
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 353)
                                        {
                        ?>
                                            <?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?>
                        <?php
                                        }
                                        else
                                        {
                        ?>
                                            <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                        }
                                        else
                                        {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
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
                        ?>
                                    <?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?>
                        <?php
                                    break;
                                    }
                                    //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
                                    else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
                                    {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                    break;
                                    } //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
                                    else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
                                    {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                    }
                                    //for equipments with subequipment but does not fall in $value
                                    else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
                                    {
                                    if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
                                    {
                                        $patient_lift_sling_count++;
                                        if($patient_lift_sling_count == 6)
                                        {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                    }
                                    else if($summary['equipmentID'] == 398)
                                    {
                                        if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
                                        {
                                        $high_low_full_electric_hospital_bed_count++;
                                        if($high_low_full_electric_hospital_bed_count == 2){
                        ?>
                                            <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                        }
                                    }
                                    else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                                    {
                                        $equipment_count++;
                                        if($equipment_count == 2){
                                        echo $summary['key_desc'];
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
                        ?>
                                        <?php echo $summary['key_desc']; ?>
                        <?php
                                        break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 14)
                                        {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                        else
                                        {

                                        }
                                    }
                                    else
                                    {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                    }
                                    }
                                }
                                }
                                else
                                {
                                if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
                                {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                }
                                else
                                {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                }
                                }
                            endif;
                        ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                        <?php
                            $quantity = 1;
                            if($summary['categoryID']!=3) //cappped=1, noncapped=2
                            {
                            //if noncapped get children quantities
                            if($summary['categoryID']==2)
                            {
                                if($summary['parentID']==0 AND $summary['equipment_value']>1)
                                {
                                $quantity = $summary['equipment_value'];
                                }
                                else
                                {
                                if($summary['equipmentID'] == 4 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 30)
                                {
                                    if(empty($summary['equipment_value']))
                                    {
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = ($temp>0)? $temp : 1;
                                    }
                                    else
                                    {
                                    $quantity = $summary['equipment_value'];
                                    }
                                }
                                else if($summary['equipmentID'] == 14)
                                {
                                    if($summary['order_status'] == "confirmed")
                                    {
                                    $quantity = $summary['equipment_value'];
                                    }
                                    else
                                    {
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = $temp;
                                    }
                                }
                                else
                                {
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = ($temp>0)? $temp : 1;
                                }
                                }
                            }
                            else //capped items
                            {
                                $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                //if the equipment is miscellaneous capped item
                                if($summary['equipmentID'] == 313 || $summary['equipmentID'] == 206)
                                {
                                $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                $quantity = ($temp>0)? $temp : 1;
                                }else if($non_capped_copy['noncapped_reference'] == 14){
                                $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                $quantity = ($temp>0)? $temp : 1;
                                }else {
                                $quantity = ($summary['equipment_value']>0)? $summary['equipment_value'] : 1;
                                }
                            }
                            }
                            else //disposable items
                            {
                            if($summary['equipment_value'] > 1)
                            {
                                $quantity = $summary['equipment_value'];
                            }
                            else
                            {
                                $quantity = (get_disposable_quantity($summary['equipmentID'], $summary['uniqueID'])>0)? get_disposable_quantity($summary['equipmentID'], $summary['uniqueID']) : $summary['equipment_value'];
                                if($summary['equipment_value'] == 0)
                                {
                                $quantity = 0;
                                }

                                if(empty($summary['equipment_value']))
                                {
                                $quantity = get_disposable_quantity($summary['equipmentID'],$summary['uniqueID']);
                                if(empty($quantity))
                                {
                                    $quantity = 1;
                                }
                                }
                            }
                            }
                            echo $quantity;
                        ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                        <?php
                            if($equipments_ordered[0]['original_activity_typeid'] == 2)
                            {
                            if($summary['activity_typeid'] == 2)
                            {
                                $sign_repeated = 0;
                                foreach ($repeating_equipment as $loop_repeating_equipment) {
                                if($loop_repeating_equipment == $summary['equipmentID'])
                                {
                                    $sign_repeated = 1;
                                }
                                }
                                if($sign_repeated == 1)
                                {
                                if($last_equipmentID == 0)
                                {
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                }
                                if($last_equipmentID == $summary['equipmentID'] && $last_equipmentID != 0)
                                {
                                    $last_equipmentID_count++;
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                }
                                else
                                {
                                    $last_equipmentID_count = 1;
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                }
                                $separated_serial_lot_no = explode(",",$queried_serial_number);
                                if(count($separated_serial_lot_no) > 1)
                                {
                                    echo $separated_serial_lot_no[0];
                                }
                                else
                                {
                                    echo $queried_serial_number;
                                }
                                }
                                else
                                {
                                $parentID = get_parental_id($summary['equipmentID']);
                                if(!empty($parentID))
                                {
                                    $get_serial_num = get_serial_num_parent($parentID['parentID'], $summary['uniqueID']);
                                    if($get_serial_num)
                                    {
                                    //Miscellaneous CAPPED and NONCAPPED
                                    if($summary['equipmentID'] == 309 || $summary['equipmentID'] == 306)
                                    {
                                        echo get_original_serial_number_v2($summary['equipmentID'], $summary['medical_record_id'], $summary['uniqueID'], $item_description_count);
                                        $item_description_count++;
                                    }else{
                                        $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                        if(count($separated_serial_lot_no) > 1)
                                        {
                                        echo $separated_serial_lot_no[0];
                                        }
                                        else
                                        {
                                        echo $get_serial_num['serial_num'];
                                        }
                                    }
                                    }else{
                                    $get_serial_num = get_serial_num_parent($summary['equipmentID'], $summary['uniqueID']);
                                    $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                    if(count($separated_serial_lot_no) > 1)
                                    {
                                        echo $separated_serial_lot_no[0];
                                    }
                                    else
                                    {
                                        echo $get_serial_num['serial_num'];
                                    }
                                    }
                                }
                                else
                                {
                                    if($summary['original_activity_typeid'] == 2)
                                    {
                                    if($summary['equipmentID'] == 181)
                                    {
                                        $get_serial_num_chair_scale = get_original_serial_number_v2($summary['equipmentID'], $summary['medical_record_id'], $summary['uniqueID'], $scale_chair_count);
                                    }
                                    else
                                    {
                                        $get_serial_num = get_serial_num_parent_v2($summary['equipmentID'], $summary['uniqueID']);
                                    }
                                    }
                                    else
                                    {
                                    $get_serial_num = get_serial_num_parent($summary['equipmentID'], $summary['uniqueID']);
                                    }
                                    if(!empty($get_serial_num))
                                    {
                                    $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                    if(count($separated_serial_lot_no) > 1)
                                    {
                                        echo $separated_serial_lot_no[0];
                                    }
                                    else
                                    {
                                        echo $get_serial_num['serial_num'];
                                    }
                                    }
                                    else if(!empty($get_serial_num_chair_scale))
                                    {
                                    $separated_serial_lot_no = explode(",",$get_serial_num_chair_scale);
                                    if(count($separated_serial_lot_no) > 1)
                                    {
                                        echo $separated_serial_lot_no[0];
                                    }
                                    else
                                    {
                                        echo $get_serial_num_chair_scale;
                                    }
                                    }
                                }  // ENDS HERE
                                }
                            }
                            }
                            else
                            {
                            if(combine_name(array($summary['serial_num'],$summary['lot_num']))!=""):
                                $serial_lot_no = combine_name(array($summary['serial_num'],$summary['lot_num']));
                                if($serial_lot_no != "pickup_order_only")
                                {
                                $separated_serial_lot_no = explode(",",$serial_lot_no);
                                if(count($separated_serial_lot_no) > 1)
                                {
                                    echo $separated_serial_lot_no[0];
                                }
                                else
                                {
                                    echo $serial_lot_no;
                                }
                                }
                            endif;
                            }
                        ?>
                        </td>
                        </tr>
                <?php
                    }
                    }
                }
                }
            endif;
            if($act_type_id == 3)
            {
                $old_items = get_old_item($work_order);
                if(!empty($old_items)):
                $packaged_items_ids_list = [486,163,164,68,159,160,161,162,316,325,334,343,466,36,178,422,259,415,174,490,492,67,157];
                $packaged_item_sign = 0;
                $packaged_items_list = array();
                $counter_sub_2 = 0;
                $counter_sub = 0;
                $duration_count_2 = 0;
                $delivery_device_count_2 = 0;
                $e_portable_count_2 = 0;
                $o2_type_index = 0;
                foreach($old_items as $summary):
                    if($summary['parentID'] == 0)
                    {
                    if(!in_array($summary['equipmentID'], $packaged_items_ids_list))
                    {
                        $line_through = "";
                        if($summary['canceled_order'] == 1) {
                        $line_through = "text-decoration: line-through;";
                        }
            ?>
                        <tr>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            P
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                            if($summary['type'] == "Capped Item" || $summary['type'] == "Non-Capped Item")
                            {
                                echo "R";
                            }
                            else
                            {
                                echo "S";
                            }
                            ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                            if($summary['equipment_company_item_no'] != "0")
                            {
                                echo $summary['equipment_company_item_no'];
                            }
                            else
                            {
                                $subequipments = get_subequipment_id_v2($summary['equipmentID']);
                                foreach ($subequipments as $key) {
                                if($key['equipment_company_item_no'] != "0")
                                {
                                    $used_subequipment = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
                                    if(!empty($used_subequipment))
                                    {
                                    echo $key['equipment_company_item_no'];
                                    }
                                }
                                }
                            }
                            ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">

                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                            if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343) :
                                echo $summary['key_desc'];
                            else:
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
                                    if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20){
                                        echo $summary['key_desc']." With ".$key['key_desc']."";
                                    }
                                    //Hi-Low Full Electric Hospital Bed equipment
                                    else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
                                    {
                        ?>
                                        <?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    //Patient Lift with Sling
                                    else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
                                    {
                        ?>
                                        <?php echo "Patient Lift With ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    //Patient Lift Electric with Sling
                                    else if($summary['equipmentID'] == 353)
                                    {
                        ?>
                                        <?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    //Patient Lift Sling
                                    else if($summary['equipmentID'] == 196)
                                    {
                        ?>
                                        <?php echo $key['key_desc']; ?>
                        <?php
                                    }
                                    //(54 & 17) Geri Chair || (66 & 39) Shower Chair
                                    else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
                                    {
                        ?>
                                        <?php echo $summary['key_desc']." ".$key['key_desc'].""; ?>
                        <?php
                                    }
                                    // Oxygen E Portable System && Oxygen Liquid Portable
                                    else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
                                    {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        break;
                                    }
                                    //Oxygen Cylinder Rack
                                    else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                                    {
                        ?>
                                        Oxygen <?php echo $key['key_desc']; ?>
                        <?php
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
                                            echo $key['key_desc']." ".$summary['key_desc'];
                                        }
                                        else
                                        {
                                            echo " With ".$key['key_desc'];
                                        }
                                        }
                                        else
                                        {
                        ?>
                                        20" Wide
                        <?php
                                        echo $summary['key_desc'];
                                        if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
                                        {
                                            echo " With Elevating Legrests";
                                            break;
                                        }
                                        else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
                                        {
                                            echo " With Footrests";
                                            break;
                                        }
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
                                        echo $key['key_desc']." ".$summary['key_desc'];
                                    }
                                    else if($summary['equipmentID'] == 30)
                                    {
                                        if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
                                        {
                                        // if($summary['original_activity_typeid']== 3)
                                        // {
                                        //   echo $summary['key_desc'];
                                        // }
                                        // else
                                        // {
                                            if($count == 3)
                                            {
                        ?>
                                            <?php echo $summary['key_desc'] ?>
                        <?php
                                            echo " With ".$key['key_desc'];
                                            }
                                        //}
                                        }
                                        else
                                        {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                    }
                                    //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
                                    else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
                                    {
                        ?>
                                        <?php echo $summary['key_desc']; ?>
                                        <br />
                                        <?php
                                        $samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
                                        if(!empty($samp))
                                        {
                                        echo "<span class='misc_item_description' style='font-weight:400;color:rgba(0, 0, 0, 0.72);font-size:11px;'>".$samp."</span>";
                                        }
                                        else
                                        {
                                        if($summary['original_activity_typeid'] == 2)
                                        {
                                            $equipment_delivery = get_equipment_delivery($summary['equipmentID'],$summary['uniqueID']);
                                            $samp =  get_misc_item_description($summary['equipmentID'],$equipment_delivery['uniqueID']);

                                            echo "<span class='misc_item_description' style='font-weight:400;color:rgba(0, 0, 0, 0.72);font-size:11px;'>".$samp."</span>";
                                        }
                                        }
                                        break;
                                    }
                                    else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                                    {
                                        $samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
                                        if($count == 1)
                                        {
                        ?>
                                        <?php echo $summary['key_desc']; ?> <?php echo $samp_conserving_device; ?>
                        <?php
                                        }
                                    }
                                    else if($summary['equipmentID'] == 282)
                                    {

                                    }
                                    //equipments that has no subequipment but gets inside the $value if statement
                                    else if($summary['equipmentID'] == 14)
                                    {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                    }
                                    else
                                    {
                                        if($summary['categoryID'] == 1)
                                        {
                                        $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                        if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                                        {
                        ?>
                                            <?php echo $summary['key_desc'] ?>
                        <?php
                                            break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 14)
                                        {
                        ?>
                                            <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 282)
                                        {
                                            $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                        ?>
                                            <?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?>
                        <?php
                                            break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                                        {
                        ?>
                                            <?php echo "Patient Lift With ".$key['key_desc'].""; ?>
                        <?php
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 353)
                                        {
                        ?>
                                            <?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?>
                        <?php
                                        }
                                        else
                                        {
                        ?>
                                            <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                        }
                                        else
                                        {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
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
                        ?>
                                    <?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?>
                        <?php
                                    break;
                                    }
                                    //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
                                    else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
                                    {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                    break;
                                    } //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
                                    else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
                                    {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                    }
                                    //for equipments with subequipment but does not fall in $value
                                    else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
                                    {
                                    if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
                                    {
                                        $patient_lift_sling_count++;
                                        if($patient_lift_sling_count == 6)
                                        {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                    }
                                    else if($summary['equipmentID'] == 398)
                                    {
                                        if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
                                        {
                                        $high_low_full_electric_hospital_bed_count++;
                                        if($high_low_full_electric_hospital_bed_count == 2){
                        ?>
                                            <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                        }
                                    }
                                    else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                                    {
                                        $equipment_count++;
                                        if($equipment_count == 2){
                                        echo $summary['key_desc'];
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
                        ?>
                                        <?php echo $summary['key_desc']; ?>
                        <?php
                                        break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 14)
                                        {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                        }
                                        else
                                        {

                                        }
                                    }
                                    else
                                    {
                        ?>
                                        <?php echo $summary['key_desc'] ?>
                        <?php
                                    }
                                    }
                                }
                                }
                                else
                                {
                                if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
                                {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                }
                                else
                                {
                        ?>
                                    <?php echo $summary['key_desc'] ?>
                        <?php
                                }
                                }
                            endif;
                        ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                            $quantity = 1;
                            if($summary['categoryID']!=3) //cappped=1, noncapped=2
                            {
                            //if noncapped get children quantities
                            if($summary['categoryID']==2)
                            {
                                if($summary['parentID']==0 AND $summary['equipment_value']>1)
                                {
                                $quantity = $summary['equipment_value'];
                                }
                                else
                                {
                                if($summary['equipmentID'] == 4 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 30)
                                {
                                    if(empty($summary['equipment_value']))
                                    {
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = ($temp>0)? $temp : 1;
                                    }
                                    else
                                    {
                                    $quantity = $summary['equipment_value'];
                                    }
                                }
                                else if($summary['equipmentID'] == 14)
                                {
                                    if($summary['order_status'] == "confirmed")
                                    {
                                    $quantity = $summary['equipment_value'];
                                    }
                                    else
                                    {
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = $temp;
                                    }
                                }
                                else
                                {
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = ($temp>0)? $temp : 1;
                                }
                                }
                            }
                            else //capped items
                            {
                                $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                //if the equipment is miscellaneous capped item
                                if($summary['equipmentID'] == 313 || $summary['equipmentID'] == 206)
                                {
                                $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                $quantity = ($temp>0)? $temp : 1;
                                }else if($non_capped_copy['noncapped_reference'] == 14){
                                $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                $quantity = ($temp>0)? $temp : 1;
                                }else {
                                $quantity = ($summary['equipment_value']>0)? $summary['equipment_value'] : 1;
                                }
                            }
                            }
                            else //disposable items
                            {
                            if($summary['equipment_value'] > 1)
                            {
                                $quantity = $summary['equipment_value'];
                            }
                            else
                            {
                                $quantity = (get_disposable_quantity($summary['equipmentID'], $summary['uniqueID'])>0)? get_disposable_quantity($summary['equipmentID'], $summary['uniqueID']) : $summary['equipment_value'];
                                if($summary['equipment_value'] == 0)
                                {
                                $quantity = 0;
                                }

                                if(empty($summary['equipment_value']))
                                {
                                $quantity = get_disposable_quantity($summary['equipmentID'],$summary['uniqueID']);
                                if(empty($quantity))
                                {
                                    $quantity = 1;
                                }
                                }
                            }
                            }
                            echo $quantity;
                        ?>
                        </td>
                        <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                        <?php
                            if($equipments_ordered[0]['original_activity_typeid'] == 2)
                            {
                            if($summary['activity_typeid'] == 2)
                            {
                                $sign_repeated = 0;
                                foreach ($repeating_equipment as $loop_repeating_equipment) {
                                if($loop_repeating_equipment == $summary['equipmentID'])
                                {
                                    $sign_repeated = 1;
                                }
                                }
                                if($sign_repeated == 1)
                                {
                                if($last_equipmentID == 0)
                                {
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                }
                                if($last_equipmentID == $summary['equipmentID'] && $last_equipmentID != 0)
                                {
                                    $last_equipmentID_count++;
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                }
                                else
                                {
                                    $last_equipmentID_count = 1;
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                }
                                $separated_serial_lot_no = explode(",",$queried_serial_number);
                                if(count($separated_serial_lot_no) > 1)
                                {
                                    echo $separated_serial_lot_no[0];
                                }
                                else
                                {
                                    echo $queried_serial_number;
                                }
                                }
                                else
                                {
                                $parentID = get_parental_id($summary['equipmentID']);
                                if(!empty($parentID))
                                {
                                    $get_serial_num = get_serial_num_parent($parentID['parentID'], $summary['uniqueID']);
                                    if($get_serial_num)
                                    {
                                    //Miscellaneous CAPPED and NONCAPPED
                                    if($summary['equipmentID'] == 309 || $summary['equipmentID'] == 306)
                                    {
                                        echo get_original_serial_number_v2($summary['equipmentID'], $summary['medical_record_id'], $summary['uniqueID'], $item_description_count);
                                        $item_description_count++;
                                    }else{
                                        $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                        if(count($separated_serial_lot_no) > 1)
                                        {
                                        echo $separated_serial_lot_no[0];
                                        }
                                        else
                                        {
                                        echo $get_serial_num['serial_num'];
                                        }
                                    }
                                    }else{
                                    $get_serial_num = get_serial_num_parent($summary['equipmentID'], $summary['uniqueID']);
                                    $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                    if(count($separated_serial_lot_no) > 1)
                                    {
                                        echo $separated_serial_lot_no[0];
                                    }
                                    else
                                    {
                                        echo $get_serial_num['serial_num'];
                                    }
                                    }
                                }
                                else
                                {
                                    if($summary['original_activity_typeid'] == 2)
                                    {
                                    if($summary['equipmentID'] == 181)
                                    {
                                        $get_serial_num_chair_scale = get_original_serial_number_v2($summary['equipmentID'], $summary['medical_record_id'], $summary['uniqueID'], $scale_chair_count);
                                    }
                                    else
                                    {
                                        $get_serial_num = get_serial_num_parent_v2($summary['equipmentID'], $summary['uniqueID']);
                                    }
                                    }
                                    else
                                    {
                                    $get_serial_num = get_serial_num_parent($summary['equipmentID'], $summary['uniqueID']);
                                    }
                                    if(!empty($get_serial_num))
                                    {
                                    $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                    if(count($separated_serial_lot_no) > 1)
                                    {
                                        echo $separated_serial_lot_no[0];
                                    }
                                    else
                                    {
                                        echo $get_serial_num['serial_num'];
                                    }
                                    }
                                    else if(!empty($get_serial_num_chair_scale))
                                    {
                                    $separated_serial_lot_no = explode(",",$get_serial_num_chair_scale);
                                    if(count($separated_serial_lot_no) > 1)
                                    {
                                        echo $separated_serial_lot_no[0];
                                    }
                                    else
                                    {
                                        echo $get_serial_num_chair_scale;
                                    }
                                    }
                                }  // ENDS HERE
                                }
                            }
                            }
                            else
                            {
                            if(combine_name(array($summary['serial_num'],$summary['lot_num']))!=""):
                                $serial_lot_no = combine_name(array($summary['serial_num'],$summary['lot_num']));
                                if($serial_lot_no != "pickup_order_only")
                                {
                                $separated_serial_lot_no = explode(",",$serial_lot_no);
                                if(count($separated_serial_lot_no) > 1)
                                {
                                    echo $separated_serial_lot_no[0];
                                }
                                else
                                {
                                    echo $serial_lot_no;
                                }
                                }
                            endif;
                            }
                        ?>
                        </td>
                        </tr>
            <?php
                    }
                    else
                    {
                        $packaged_item_sign = 1;
                        if($summary['equipmentID'] == 486 || $summary['equipmentID'] == 163 || $summary['equipmentID'] == 164)
                        {
                        $packaged_items_list[0][] = $summary;
                        }
                        else if($summary['equipmentID'] == 68 || $summary['equipmentID'] == 159 || $summary['equipmentID'] == 160 || $summary['equipmentID'] == 161 || $summary['equipmentID'] ==162)
                        {
                        $packaged_items_list[1][] = $summary;
                        }
                        else if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343 || $summary['equipmentID'] == 466)
                        {
                        $packaged_items_list[2][] = $summary;
                        }
                        else if($summary['equipmentID'] == 36 || $summary['equipmentID'] == 466 || $summary['equipmentID'] == 178)
                        {
                        $packaged_items_list[3][] = $summary;
                        }
                        else if($summary['equipmentID'] == 422 || $summary['equipmentID'] == 259)
                        {
                        $packaged_items_list[4][] = $summary;
                        }
                        else if($summary['equipmentID'] == 415 || $summary['equipmentID'] == 259)
                        {
                        $packaged_items_list[5][] = $summary;
                        }
                        else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 490 || $summary['equipmentID'] ==492)
                        {
                        $packaged_items_list[6][] = $summary;
                        }
                        else if($summary['equipmentID'] == 67 || $summary['equipmentID'] == 157)
                        {
                        $packaged_items_list[7][] = $summary;
                        }
                    }
                    }
                endforeach;
                if(!empty($packaged_items_list))
                {
                    foreach($packaged_items_list as $new_item_list)
                    {
                    foreach ($new_item_list as  $summary)
                    {
                        if($summary['parentID'] == 0)
                        {
                        $line_through = "";
                        if($summary['canceled_order'] == 1) {
                            $line_through = "text-decoration: line-through;";
                        }
                ?>
                        <tr>
                            <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            P
                            </td>
                            <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                                if($summary['type'] == "Capped Item" || $summary['type'] == "Non-Capped Item")
                                {
                                echo "R";
                                }
                                else
                                {
                                echo "S";
                                }
                            ?>
                            </td>
                            <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                                if($summary['equipment_company_item_no'] != "0")
                                {
                                echo $summary['equipment_company_item_no'];
                                }
                                else
                                {
                                $subequipments = get_subequipment_id_v2($summary['equipmentID']);
                                foreach ($subequipments as $key) {
                                    if($key['equipment_company_item_no'] != "0")
                                    {
                                    $used_subequipment = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
                                    if(!empty($used_subequipment))
                                    {
                                        echo $key['equipment_company_item_no'];
                                    }
                                    }
                                }
                                }
                            ?>
                            </td>
                            <td class="td_ordered_items" style="<?php echo $line_through; ?>">

                            </td>
                            <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                                if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343) :
                                echo $summary['key_desc'];
                                else:
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
                                        if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20){
                                        echo $summary['key_desc']." With ".$key['key_desc']."";
                                        }
                                        //Hi-Low Full Electric Hospital Bed equipment
                                        else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
                                        {
                            ?>
                                        <?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?>
                            <?php
                                        }
                                        //Patient Lift with Sling
                                        else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
                                        {
                            ?>
                                        <?php echo "Patient Lift With ".$key['key_desc'].""; ?>
                            <?php
                                        }
                                        //Patient Lift Electric with Sling
                                        else if($summary['equipmentID'] == 353)
                                        {
                            ?>
                                        <?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?>
                            <?php
                                        }
                                        //Patient Lift Sling
                                        else if($summary['equipmentID'] == 196)
                                        {
                            ?>
                                        <?php echo $key['key_desc']; ?>
                            <?php
                                        }
                                        //(54 & 17) Geri Chair || (66 & 39) Shower Chair
                                        else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
                                        {
                            ?>
                                        <?php echo $summary['key_desc']." ".$key['key_desc'].""; ?>
                            <?php
                                        }
                                        // Oxygen E Portable System && Oxygen Liquid Portable
                                        else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
                                        {
                            ?>
                                        <?php echo $summary['key_desc'] ?>
                            <?php
                                        break;
                                        }
                                        //Oxygen Cylinder Rack
                                        else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                                        {
                            ?>
                                        Oxygen <?php echo $key['key_desc']; ?>
                            <?php
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
                                            echo $key['key_desc']." ".$summary['key_desc'];
                                            }
                                            else
                                            {
                                            echo " With ".$key['key_desc'];
                                            }
                                        }
                                        else
                                        {
                            ?>
                                            20" Wide
                            <?php
                                            echo $summary['key_desc'];
                                            if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
                                            {
                                            echo " With Elevating Legrests";
                                            break;
                                            }
                                            else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
                                            {
                                            echo " With Footrests";
                                            break;
                                            }
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
                                        echo $key['key_desc']." ".$summary['key_desc'];
                                        }
                                        else if($summary['equipmentID'] == 30)
                                        {
                                        if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
                                        {
                                            // if($summary['original_activity_typeid']== 3)
                                            // {
                                            //   echo $summary['key_desc'];
                                            // }
                                            // else
                                            // {
                                            if($count == 3)
                                            {
                            ?>
                                                <?php echo $summary['key_desc'] ?>
                            <?php
                                                echo " With ".$key['key_desc'];
                                            }
                                            //}
                                        }
                                        else
                                        {
                            ?>
                                            <?php echo $summary['key_desc'] ?>
                            <?php
                                        }
                                        }
                                        //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
                                        else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
                                        {
                            ?>
                                        <?php echo $summary['key_desc']; ?>
                                        <br />
                                        <?php
                                        $samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
                                        if(!empty($samp))
                                        {
                                            echo "<span class='misc_item_description' style='font-weight:400;color:rgba(0, 0, 0, 0.72);font-size:11px;'>".$samp."</span>";
                                        }
                                        else
                                        {
                                            if($summary['original_activity_typeid'] == 2)
                                            {
                                            $equipment_delivery = get_equipment_delivery($summary['equipmentID'],$summary['uniqueID']);
                                            $samp =  get_misc_item_description($summary['equipmentID'],$equipment_delivery['uniqueID']);

                                            echo "<span class='misc_item_description' style='font-weight:400;color:rgba(0, 0, 0, 0.72);font-size:11px;'>".$samp."</span>";
                                            }
                                        }
                                        break;
                                        }
                                        else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                                        {
                                        $samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
                                        if($count == 1)
                                        {
                            ?>
                                            <?php echo $summary['key_desc']; ?> <?php echo $samp_conserving_device; ?>
                            <?php
                                        }
                                        }
                                        else if($summary['equipmentID'] == 282)
                                        {

                                        }
                                        //equipments that has no subequipment but gets inside the $value if statement
                                        else if($summary['equipmentID'] == 14)
                                        {
                            ?>
                                        <?php echo $summary['key_desc'] ?>
                            <?php
                                        }
                                        else
                                        {
                                        if($summary['categoryID'] == 1)
                                        {
                                            $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                            if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                                            {
                            ?>
                                            <?php echo $summary['key_desc'] ?>
                            <?php
                                            break;
                                            }
                                            else if($non_capped_copy['noncapped_reference'] == 14)
                                            {
                            ?>
                                            <?php echo $summary['key_desc'] ?>
                            <?php
                                            }
                                            else if($non_capped_copy['noncapped_reference'] == 282)
                                            {
                                            $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                            ?>
                                            <?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?>
                            <?php
                                            break;
                                            }
                                            else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                                            {
                            ?>
                                            <?php echo "Patient Lift With ".$key['key_desc'].""; ?>
                            <?php
                                            }
                                            else if($non_capped_copy['noncapped_reference'] == 353)
                                            {
                            ?>
                                            <?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?>
                            <?php
                                            }
                                            else
                                            {
                            ?>
                                            <?php echo $summary['key_desc'] ?>
                            <?php
                                            }
                                        }
                                        else
                                        {
                            ?>
                                            <?php echo $summary['key_desc'] ?>
                            <?php
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
                            ?>
                                        <?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?>
                            <?php
                                        break;
                                    }
                                    //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
                                    else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
                                    {
                            ?>
                                        <?php echo $summary['key_desc'] ?>
                            <?php
                                        break;
                                    } //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
                                    else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
                                    {
                            ?>
                                        <?php echo $summary['key_desc'] ?>
                            <?php
                                    }
                                    //for equipments with subequipment but does not fall in $value
                                    else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
                                    {
                                        if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
                                        {
                                        $patient_lift_sling_count++;
                                        if($patient_lift_sling_count == 6)
                                        {
                            ?>
                                            <?php echo $summary['key_desc'] ?>
                            <?php
                                        }
                                        }
                                        else if($summary['equipmentID'] == 398)
                                        {
                                        if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
                                        {
                                            $high_low_full_electric_hospital_bed_count++;
                                            if($high_low_full_electric_hospital_bed_count == 2){
                            ?>
                                            <?php echo $summary['key_desc'] ?>
                            <?php
                                            }
                                        }
                                        }
                                        else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                                        {
                                        $equipment_count++;
                                        if($equipment_count == 2){
                                            echo $summary['key_desc'];
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
                            ?>
                                            <?php echo $summary['key_desc']; ?>
                            <?php
                                            break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 14)
                                        {
                            ?>
                                            <?php echo $summary['key_desc'] ?>
                            <?php
                                        }
                                        else
                                        {

                                        }
                                        }
                                        else
                                        {
                            ?>
                                        <?php echo $summary['key_desc'] ?>
                            <?php
                                        }
                                    }
                                    }
                                }
                                else
                                {
                                    if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
                                    {
                            ?>
                                    <?php echo $summary['key_desc'] ?>
                            <?php
                                    }
                                    else
                                    {
                            ?>
                                    <?php echo $summary['key_desc'] ?>
                            <?php
                                    }
                                }
                                endif;
                            ?>
                            </td>
                            <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                            $quantity = 1;
                            if($summary['categoryID']!=3) //cappped=1, noncapped=2
                            {
                                //if noncapped get children quantities
                                if($summary['categoryID']==2)
                                {
                                if($summary['parentID']==0 AND $summary['equipment_value']>1)
                                {
                                    $quantity = $summary['equipment_value'];
                                }
                                else
                                {
                                    if($summary['equipmentID'] == 4 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 30)
                                    {
                                    if(empty($summary['equipment_value']))
                                    {
                                        $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                        $quantity = ($temp>0)? $temp : 1;
                                    }
                                    else
                                    {
                                        $quantity = $summary['equipment_value'];
                                    }
                                    }
                                    else if($summary['equipmentID'] == 14)
                                    {
                                    if($summary['order_status'] == "confirmed")
                                    {
                                        $quantity = $summary['equipment_value'];
                                    }
                                    else
                                    {
                                        $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                        $quantity = $temp;
                                    }
                                    }
                                    else
                                    {
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = ($temp>0)? $temp : 1;
                                    }
                                }
                                }
                                else //capped items
                                {
                                $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                //if the equipment is miscellaneous capped item
                                if($summary['equipmentID'] == 313 || $summary['equipmentID'] == 206)
                                {
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = ($temp>0)? $temp : 1;
                                }else if($non_capped_copy['noncapped_reference'] == 14){
                                    $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                    $quantity = ($temp>0)? $temp : 1;
                                }else {
                                    $quantity = ($summary['equipment_value']>0)? $summary['equipment_value'] : 1;
                                }
                                }
                            }
                            else //disposable items
                            {
                                if($summary['equipment_value'] > 1)
                                {
                                $quantity = $summary['equipment_value'];
                                }
                                else
                                {
                                $quantity = (get_disposable_quantity($summary['equipmentID'], $summary['uniqueID'])>0)? get_disposable_quantity($summary['equipmentID'], $summary['uniqueID']) : $summary['equipment_value'];
                                if($summary['equipment_value'] == 0)
                                {
                                    $quantity = 0;
                                }

                                if(empty($summary['equipment_value']))
                                {
                                    $quantity = get_disposable_quantity($summary['equipmentID'],$summary['uniqueID']);
                                    if(empty($quantity))
                                    {
                                    $quantity = 1;
                                    }
                                }
                                }
                            }
                            echo $quantity;
                            ?>
                            </td>
                            <td class="td_ordered_items" style="<?php echo $line_through; ?>">
                            <?php
                            if($equipments_ordered[0]['original_activity_typeid'] == 2)
                            {
                                if($summary['activity_typeid'] == 2)
                                {
                                $sign_repeated = 0;
                                foreach ($repeating_equipment as $loop_repeating_equipment) {
                                    if($loop_repeating_equipment == $summary['equipmentID'])
                                    {
                                    $sign_repeated = 1;
                                    }
                                }
                                if($sign_repeated == 1)
                                {
                                    if($last_equipmentID == 0)
                                    {
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                    }
                                    if($last_equipmentID == $summary['equipmentID'] && $last_equipmentID != 0)
                                    {
                                    $last_equipmentID_count++;
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                    }
                                    else
                                    {
                                    $last_equipmentID_count = 1;
                                    $queried_serial_number = get_equipment_serial_number($summary['equipmentID'], $summary['uniqueID'],$last_equipmentID_count);
                                    }
                                    $separated_serial_lot_no = explode(",",$queried_serial_number);
                                    if(count($separated_serial_lot_no) > 1)
                                    {
                                    echo $separated_serial_lot_no[0];
                                    }
                                    else
                                    {
                                    echo $queried_serial_number;
                                    }
                                }
                                else
                                {
                                    $parentID = get_parental_id($summary['equipmentID']);
                                    if(!empty($parentID))
                                    {
                                    $get_serial_num = get_serial_num_parent($parentID['parentID'], $summary['uniqueID']);
                                    if($get_serial_num)
                                    {
                                        //Miscellaneous CAPPED and NONCAPPED
                                        if($summary['equipmentID'] == 309 || $summary['equipmentID'] == 306)
                                        {
                                        echo get_original_serial_number_v2($summary['equipmentID'], $summary['medical_record_id'], $summary['uniqueID'], $item_description_count);
                                        $item_description_count++;
                                        }else{
                                        $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                        if(count($separated_serial_lot_no) > 1)
                                        {
                                            echo $separated_serial_lot_no[0];
                                        }
                                        else
                                        {
                                            echo $get_serial_num['serial_num'];
                                        }
                                        }
                                    }else{
                                        $get_serial_num = get_serial_num_parent($summary['equipmentID'], $summary['uniqueID']);
                                        $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                        if(count($separated_serial_lot_no) > 1)
                                        {
                                        echo $separated_serial_lot_no[0];
                                        }
                                        else
                                        {
                                        echo $get_serial_num['serial_num'];
                                        }
                                    }
                                    }
                                    else
                                    {
                                    if($summary['original_activity_typeid'] == 2)
                                    {
                                        if($summary['equipmentID'] == 181)
                                        {
                                        $get_serial_num_chair_scale = get_original_serial_number_v2($summary['equipmentID'], $summary['medical_record_id'], $summary['uniqueID'], $scale_chair_count);
                                        }
                                        else
                                        {
                                        $get_serial_num = get_serial_num_parent_v2($summary['equipmentID'], $summary['uniqueID']);
                                        }
                                    }
                                    else
                                    {
                                        $get_serial_num = get_serial_num_parent($summary['equipmentID'], $summary['uniqueID']);
                                    }
                                    if(!empty($get_serial_num))
                                    {
                                        $separated_serial_lot_no = explode(",",$get_serial_num['serial_num']);
                                        if(count($separated_serial_lot_no) > 1)
                                        {
                                        echo $separated_serial_lot_no[0];
                                        }
                                        else
                                        {
                                        echo $get_serial_num['serial_num'];
                                        }
                                    }
                                    else if(!empty($get_serial_num_chair_scale))
                                    {
                                        $separated_serial_lot_no = explode(",",$get_serial_num_chair_scale);
                                        if(count($separated_serial_lot_no) > 1)
                                        {
                                        echo $separated_serial_lot_no[0];
                                        }
                                        else
                                        {
                                        echo $get_serial_num_chair_scale;
                                        }
                                    }
                                    }  // ENDS HERE
                                }
                                }
                            }
                            else
                            {
                                if(combine_name(array($summary['serial_num'],$summary['lot_num']))!=""):
                                $serial_lot_no = combine_name(array($summary['serial_num'],$summary['lot_num']));
                                if($serial_lot_no != "pickup_order_only")
                                {
                                    $separated_serial_lot_no = explode(",",$serial_lot_no);
                                    if(count($separated_serial_lot_no) > 1)
                                    {
                                    echo $separated_serial_lot_no[0];
                                    }
                                    else
                                    {
                                    echo $serial_lot_no;
                                    }
                                }
                                endif;
                            }
                            ?>
                            </td>
                        </tr>
                <?php
                        }
                    }
                    }
                }
                endif;
            }
            if ($item_in_order_count < 12) {
                for($j = 0; $j < (12-$item_in_order_count); $j++)
                {
                if ($j == (11-$item_in_order_count)) {
            ?>
                    <tr class="tr_ordered_items_empty" style="height:35px;">
                    <td class="td_ordered_items_empty" colspan="7">
                        <strong> I understand that the above equipment is a rental and will be picked up once I'm off of Hospice. Initial _______ </strong>
                    </td>
                    </tr>
            <?php
                } else {
            ?>
                    <tr class="tr_ordered_items_empty" style="height:35px;">
                    <td class="td_ordered_items_empty">
                    </td>
                    <td class="td_ordered_items_empty">
                    </td>
                    <td class="td_ordered_items_empty">
                    </td>
                    <td class="td_ordered_items_empty">
                    </td>
                    <td class="td_ordered_items_empty">
                    </td>
                    <td class="td_ordered_items_empty">
                    </td>
                    <td class="td_ordered_items_empty">
                    </td>
                    </tr>
            <?php
                }
                }
            } else {
            ?>
                <tr class="tr_ordered_items_empty" style="height:35px;">
                <td class="td_ordered_items_empty" colspan="7">
                    <strong> I understand that the above equipment is a rental and will be picked up once I'm off of Hospice. Initial _______ </strong>
                </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>

        <strong><span class="delivery_instructions_label">Delivery Instructions</span></strong>
        <table class="table delivery_instructions_table" style="border:1px solid rgba(0, 0, 0, 0.50) !important; margin-top:5px;">
            <tbody style="border-top:1px solid;border-top-color:rgba(0, 0, 0, 0.50);">
                <tr>
                    <td class="td_delivery_instructions">
                    <?php
                        if($equipments_ordered[0]['comment'] == ''):

                        else:
                        echo $equipments_ordered[0]['comment'];
                        endif;
                    ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="col-xs-12 col-sm-12">
            <div class="row order_details_below_row_container" style="text-align:center;margin-top:45px;">
            <div class="col-xs-4 col-sm-4 first_hr">
                <hr style="width:90%;border:1px solid rgba(0, 0, 0, 0.55);" />
            </div>
            <div class="col-xs-4 col-sm-4">
                <hr style="width:60%;border:1px solid rgba(0, 0, 0, 0.55);" />
            </div>
            <div class="col-xs-4 col-sm-4 work_order_created_by_content" style="display:block; margin-bottom:-18px;">
                <?php
                if($equipments_ordered[0]['staff_member_fname'] == '' && $equipments_ordered[0]['staff_member_fname'] == '')
                {
                    echo "<span> NA </span>";
                }
                else if($equipments_ordered[0]['staff_member_fname'] == 'NA' && $equipments_ordered[0]['staff_member_fname'] == 'NA')
                {
                    echo "<span>".$equipments_ordered[0]['who_ordered_fname']  ." ". substr($equipments_ordered[0]['who_ordered_lname'],0,1) .". </span>";
                }
                else
                {
                    echo "<span>".$equipments_ordered[0]['staff_member_fname']  ." ". substr($equipments_ordered[0]['staff_member_lname'],0,1) .". </span>";
                }
                ?>
                <hr style="width:90%;margin-top:0px;border:1px solid rgba(0, 0, 0, 0.55);" />
            </div>
            </div>
            <div class="row " style="text-align:center;">
            <div class="col-xs-4 col-sm-4 order_details_below order_details_label_first">
                Customer Signature or Auhorized Individual
            </div>
            <div class="col-xs-4 col-sm-4 order_details_below order_details_label_first">
                Date
            </div>
            <div class="col-xs-4 col-sm-4 order_details_below order_details_label_first">
                Work Order Created By
            </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12" style="margin-bottom:50px;">
            <div class="row" style="text-align:center;margin-top:25px;">
            <div class="col-xs-4 col-sm-4 fourth_hr">
                <hr style="width:90%;border:1px solid rgba(0, 0, 0, 0.55);" />
            </div>
            <div class="col-xs-4 col-sm-4">
                <hr style="width:60%;border:1px solid rgba(0, 0, 0, 0.55);" />
            </div>

            <?php if($equipments_ordered[0]['driver_name'] == ''): ?>
                <div class="col-xs-4 col-sm-4">
                <hr style="width:90%;border:1px solid rgba(0, 0, 0, 0.55);" />
                </div>
            <?php else:?>
                <div class="col-xs-4 col-sm-4">
                <?php echo $equipments_ordered[0]['driver_name']; ?>
                <hr style="width:90%;border:1px solid rgba(0, 0, 0, 0.55);margin-top:0px;" />
                </div>
            <?php endif;?>

            </div>
            <div class="row" style="text-align:center;">
            <div class="col-xs-4 col-sm-4 order_details_below order_details_label_second">
                Print Name
            </div>
            <div class="col-xs-4 col-sm-4 order_details_below order_details_label_second">
                Relationship
            </div>
            <div class="col-xs-4 col-sm-4 order_details_below order_details_label_second">
                Representative Signature
            </div>
            </div>
        </div>


    </div>

<?php
  endif;
?>
