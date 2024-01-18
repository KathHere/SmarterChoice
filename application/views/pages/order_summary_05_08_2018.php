<?php
  if(!empty($informations)) :
    $information = $informations[0];
    // print_me($information);

    $dme_order_row = get_latest_dme_order_row($information['patientID']);
    $organization_id = $this->session->userdata('group_id');
    if($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $information['type'] == 1)
    {
      $logged_in_account_type = "Company";
      $margin_left_css = "72px";
    }
    else
    {
      $logged_in_account_type = "Hospice";
      $margin_left_css = "63px";
    }
?>
<style type="text/css">

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
.rx-container a{
  font-weight: bold;
}

.popover{
    max-width: 100%; /* Max Width of the popover (depending on the container!) */
}

</style>

<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">Customer Profile</h1>
</div>

<div class="wrapper-md">
  <div class="patient-profile">
    <div class="panel panel-default">
      <div class="panel-body" style="padding-top:0;padding-bottom:0;">
        <div class="row">
          <?php if($this->session->userdata('account_type') == "dme_admin") :?>
            <div class="col-xs-12" style="background-color:#fafafa; padding:20px;">
              <div class="media">
                <div class="media-left pull-left" style="margin-right:20px;">
                  <!-- <a href="javascript:void(0)" class="edit_patient_profile data_tooltip patient-profile-photo" title="Edit Patient Profile" data-organization-id="<?php echo $information['ordered_by'] ?>" data-id="<?php echo $information['medical_record_id'] ?>"> -->
                  <?php if(!empty($information['ordered_by'])){?>
                    <a href="javascript:void(0)" class="edit_patient_profile data_tooltip patient-profile-photo" title="Edit Customer Profile" data-organization-id="<?php echo $information['ordered_by'] ?>" data-id="<?php echo $information['medical_record_id'] ?>">
                      <i class=" icon-user fa fa-2x"></i>
                    </a>
                  <?php } else { ?>
                    <a href="javascript:void(0)" class="edit_patient_profile data_tooltip patient-profile-photo" title="Edit Customer Profile" data-organization-id="<?php echo $information['ordered_by'] ?>" data-id="<?php echo $information['medical_record_id'] ?>">
                      <i class=" icon-user fa fa-2x"></i>
                    </a>
                  <?php } ?>
                </div>
                <div class="media-right mt10">
                  <!-- <a href="javascript:void(0)" class="edit_patient_profile" data-organization-id="<?php echo $information['ordered_by'] ?>" data-id="<?php echo $information['medical_record_id'] ?>"D><i class="icon-pencil"></i> Edit Info</a>&nbsp &nbsp &nbsp <br /> -->
                  <?php if(!empty($information['ordered_by'])){?>
                  <a href="javascript:void(0)" class="edit_patient_profile" data-organization-id="<?php echo $information['ordered_by'] ?>" data-id="<?php echo $information['medical_record_id'] ?>"D><i class="icon-pencil"></i> Edit Info</a>&nbsp &nbsp &nbsp <br />
                  <?php } else { ?>
                  <a href="javascript:void(0)" class="edit_patient_profile" data-organization-id="<?php echo $information['ordered_by'] ?>" data-id="<?php echo $information['medical_record_id'] ?>"D><i class="icon-pencil"></i> Edit Info</a>&nbsp &nbsp &nbsp <br />
                  <?php } ?>
                  <a href="javascript:void(0)" id="add_patient_notes" class="patient_notes_count" data-patient-id="<?php echo $information['patientID'] ?>" data-id="<?php echo $information['medical_record_id'] ?>" data-fname="<?php echo $information['p_fname'] ?>" data-lname="<?php echo $information['p_lname'] ?>" data-hospice="<?php echo $information['hospice_name'] ?>" title=""><i class="icon icon-speech"></i> <?php echo $note_counts ?> Customer Notes</a>
                </div>
              </div>
            </div>
          <?php endif;?>
          <div class="col-xs-12">
            <h4>Customer Medical Record # <?php echo $information['medical_record_id'] ?></h4>
            <h5><?php echo $logged_in_account_type; ?> Provider: <?php echo $information['hospice_name'] ?></h5>
            <?php
              if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "hospice_admin" || $this->session->userdata('account_type') == "company_admin"){
            ?>
                <h6 style="font-size:13px !important;">
                <div style="width:110px !important;"> <span>
                  <?php if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user"){ ?>
                    <i class="fa fa-calendar change_patient_entry_date" aria-hidden="true" data-id="<?php echo $information['patientID'] ?>" style="cursor:pointer;"></i> &nbsp;
                  <?php } ?>
                    LOS: <?php echo $information['length_of_stay']; ?> </span> </div>
                  <div style="width:130px !important;margin-top: -14px;margin-left: <?php echo $margin_left_css; ?>;"><span style="margin-left:44px;"> CUS Days: <?php echo $information['patient_days']; ?> </span> </div>
                </h6>
            <?php
              }
            ?>
          </div>
        </div>
      </div>
    </div>

    <div class="well m-t bg-light lt">
      <div class="row">
        <div class="col-xs-6">

          <strong>Customer Name</strong>
          <h4><?php echo $information['p_lname'] .", ". $information['p_fname'] ?></h4>

          <strong>Gender</strong>
          <p>
            <?php
              if($information['relationship_gender'] == 1)
              {
                echo "Male";
              }
              else
              {
                echo "Female";
              }
            ?>
          </p>

  <?php
          $ptmove = new_ptmove_address($information['patientID']);
          $ptmove_new_phone = get_new_patient_phone($information['patientID']);
          $ptmove_residence = get_new_patient_residence($information['patientID']);
          $ptmove_final = $ptmove[0];

          $response = get_items_for_pickup($information['medical_record_id'],$information['ordered_by']);
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

          $patientID = get_patientID($information['medical_record_id'],$information['ordered_by']);
          $patient_addresses = get_patient_addresses($patientID['patientID']);
          $new_response = array();
          $new_addresses_response = array();
          $data['addressID'] = array();

          foreach ($patient_addresses as $key=>$value) {
            $new_response_query = get_items_for_pickup_other_address($information['medical_record_id'],$information['ordered_by'],$value['id']);
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

          <strong>Customer Address</strong>
          <p>
            <?php echo $information['p_street'] ."<br/> ". $information['p_placenum'] ."<br/> ". $information['p_city'] .", ". $information['p_state'] .", ". $information['p_postalcode'] ?>
          </p>

          <strong>Height(IN) & Weight(lbs)</strong>
  <?php
          if($information['p_weight'] == 0 && $information['p_height'] == 0) :
  ?>
            <p>NA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp NA</p>
  <?php
          else:
  ?>
            <p><?php echo $information['p_height'] ?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <?php echo $information['p_weight'] ?></p>
  <?php
          endif;
  ?>
          <strong>Phone Number</strong>
          <p><?php echo $information['p_phonenum'] ?></p>

          <strong>No. of Location(s): </strong>
          <br /><?php echo $no_of_addresses; ?>

        </div> <!-- .col-xs-6 -->
        <div class="col-xs-6">

          <strong >Emergency Contact</strong><br/><br/>

          <strong>Next of Kin</strong>
          <p><?php echo $information['p_nextofkin'] ?></p>

          <strong>Relationship</strong>
          <?php if($information['p_relationship'] == '') :?>
            <p>N/A</p>
          <?php else:?>
            <p><?php echo $information['p_relationship'] ?></p>
          <?php endif;?>

          <strong>Next of Kin Phone Number</strong>
          <p><?php echo $information['p_nextofkinnum'] ?></p>

          <strong>Alt. Phone Number</strong>
          <?php if($information['p_altphonenum'] == '') :?>
            <p>N/A</p>
          <?php else:?>
            <p><?php echo $information['p_altphonenum'] ?></p>
          <?php endif;?>

          <strong>Residence</strong>
          <?php
            $ptmove_residence = get_new_patient_residence_v3($information['patientID']);
            if(!empty($ptmove_residence)){
              $check_result = check_if_ptmove_confirmed($ptmove_residence['order_uniqueID']);

              if($check_result['order_status'] == "confirmed"){
          ?>
                <p><?php echo $ptmove_residence['ptmove_patient_residence'] ?></p>
          <?php
              }else{
                $ptmove_residence_new = get_new_patient_residence_v2($information['patientID'], $ptmove_residence['order_uniqueID']);
                if(!empty($ptmove_residence_new)){
          ?>
                  <p><?php echo $ptmove_residence_new['ptmove_patient_residence'] ?></p>
          <?php }else{ ?>
                  <p><?php echo $dme_order_row['deliver_to_type'] ?></p>
          <?php
                }
              }
            }else{
          ?>
              <p><?php echo $dme_order_row['deliver_to_type'] ?></p>
          <?php
            }
          ?>

        </div>
        <!-- Settings moved here from the top new change 4-22-16 -->
        <div class="col-xs-12" style="margin-top:10px;">

        <?php
          //get 02 Liter Flow
          $liter_flow = get_liter_flow($information['ordered_by'],$information['medical_record_id']);

          // get the duration of the oxygen concentrator
          // $duration_cnt = get_duration_cnt($information['ordered_by'],$information['medical_record_id']);
          // $duration_prn = get_duration_prn($information['ordered_by'],$information['medical_record_id']);

          /*
          | getting duration based on O2 liter flow result to make data consistent
          | getting O2 duration lists to get in the query based on the parentID provided by O2 liter flow
          */
          $equipmentID = 0;
          if(!in_array($liter_flow['parentID'],array(316,325,334,343,36,30,176,31,174,62)))
          {
            //get latest equipment ordered with those ID's from above and get the parent id
            $parentID = get_parent_id($information['ordered_by'],$information['medical_record_id']);
            $liter_flow['parentID'] = $parentID['parentID'];
            $liter_flow['uniqueID'] = $parentID['uniqueID'];
            $equipmentID = $parentID['parentID'];
          }
          $parentID_duration = get_parent_id_duration($information['ordered_by'],$information['medical_record_id']);
          if(empty($parentID_duration))
          {
            $parentID_duration = get_parent_id_duration_v2($information['ordered_by'],$information['medical_record_id']);
          }

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
          $IPAP = get_ipap($information['ordered_by'],$information['medical_record_id']);
          $EPAP = get_epap($information['ordered_by'],$information['medical_record_id']);
          $rate = get_rate($information['ordered_by'],$information['medical_record_id']);

          //get CIPAP SETTINGS
          $CIPAP = get_cipap($information['ordered_by'],$information['medical_record_id']);
          //get O2 LR Settiings:
          if(!empty($liter_flow) || !empty($IPAP) || !empty($EPAP) || !empty($rate) || !empty($CIPAP))
          {

        ?>
        <?php
          /*
            =============================================================================================================================================
            <!--

              PLEASE APPLY A STANDARD USAGE OF HTML IF POSSIBLE USE CSS !!!!! THANK YOU!
              IT WILL BE HELPFUL LATER ON WHEN WE WILL TRACE BUGS OR ADD FEATURES

              PLEASE! PLEASE! PLEASE! PLEASE! PLEASE! FORMAT CORRECTLY OR ALIGNED THE CODES ACCORDINGLY

              CURRENT CODES WOULD MAKE A PERSON FEEL DISCOURAGE WHEN DOING TRACING. POSSIBLE SOLUTION HE WOULD THINK IS TO RECODE THE WHOLE
              FILE.

              THANK YOU :)
            -->
            ==============================================================================================================================================
          */
        ?>
        <?php
          if(!empty($liter_flow))
          {
            if($liter_flow['parentID'] != "")
            {
        ?>
              <strong>Rx</strong>
        <?php
            }
            else if(!empty($IPAP) || !empty($EPAP) || !empty($rate) || !empty($CIPAP))
            {
                 echo "<strong>Rx</strong>";
            }
          }
        ?>
        <p class="rx-container">
        <?php
          if(!empty($liter_flow))
          {
            if(!empty($liter_flow['equipment_value']))
            {
        ?>

              <label style="padding-right:30px;">O2 Liter Flow: &nbsp;&nbsp;
                <a href="javascript:;"
                    id="equipment_value"
                    data-pk="<?php echo $liter_flow['orderID'] ?>"
                    data-url="<?php echo base_url('order/update_data/number/orderID/1'); ?>"
                    data-title="Enter Liter Flow"
                    data-value="<?php echo $liter_flow['equipment_value']; ?>"
                    data-type="number"
                    class="data_tooltip editable editable-click editable-noreload"
                >
                  <strong class="under-line"> <?php echo $liter_flow['equipment_value']; ?> </strong>
                </a>
                <strong class="visible-print-block"> <?php echo $liter_flow['equipment_value']; ?> </strong>
        <?php
            }
            if(!empty($duration_)) {
              $delivery_device_word = "";
              $latest_delivery_device = get_latest_delivery_device($information['ordered_by'],$information['medical_record_id']);
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
                &nbsp;&nbsp;&nbsp;Duration
                <a href="javascript:;"
                    id="equipmentID"
                    data-source='<?php echo json_encode($duration_options_format);?>'
                    data-pk="<?php echo $duration_array['orderID'] ?>"
                    data-url="<?php echo base_url('order/update_data/number/orderID/1'); ?>"
                    data-title="Select Duration"
                    data-value="<?php echo $duration_array['equipmentID']; ?>"
                    data-type="select"
                    class="data_tooltip editable editable-click editable-noreload "
                >
                  <strong class="under-line"> <?php echo $duration_['key_desc']; ?> W/ <?php echo $delivery_device_word; ?> </strong>
                </a>
                <strong class="visible-print-block"> <?php echo $duration_['key_desc']; ?> W/ <?php echo $delivery_device_word; ?>  </strong>
        <?php
              }
              else
              {
        ?>
                &nbsp;&nbsp;&nbsp;Duration
                <a href="javascript:;"
                    id="equipmentID"
                    data-source='<?php echo json_encode($duration_options_format);?>'
                    data-pk="<?php echo $duration_array['orderID'] ?>"
                    data-url="<?php echo base_url('order/update_data/number/orderID/1'); ?>"
                    data-title="Select Duration"
                    data-value="<?php echo $duration_array['equipmentID']; ?>"
                    data-type="select"
                    class="data_tooltip editable editable-click editable-noreload "
                >
                  <strong class="under-line"> <?php echo $duration_['key_desc']; ?> </strong>
                </a>
                <strong class="visible-print-block"> <?php echo $duration_['key_desc']; ?> </strong>
          <?php
              }
            }
          ?>
            </label>
          <?php
          }
          if(!empty($IPAP) || !empty($EPAP) || !empty($rate)){
          ?>
            <label style="padding-right:30px;">BIPAP Settings: &nbsp;
            <?php
          }
              if(!empty($IPAP)){
            ?>
                <span>IPAP</span>
                <a href="javascript:;"
                  id="equipment_value"
                  data-pk="<?php echo $IPAP['orderID'] ?>"
                  data-url="<?php echo base_url('order/update_data/number/orderID/1'); ?>"
                  data-title="IPAP"
                  data-value="<?php echo $IPAP['equipment_value']; ?>"
                  data-type="number"
                  class="data_tooltip editable editable-click editable-noreload"
                >
                  <strong class="under-line"> <?php echo $IPAP['equipment_value']; ?> </strong>
                </a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong class="visible-print-block"> <?php echo $IPAP['equipment_value']; ?> </strong>
            <?php
              }
              if(!empty($EPAP)){
            ?>
                <span>EPAP</span>
                <a href="javascript:;"
                    id="equipment_value"
                    data-pk="<?php echo $EPAP['orderID'] ?>"
                    data-url="<?php echo base_url('order/update_data/number/orderID/1'); ?>"
                    data-title="EPAP"
                    data-value="<?php echo $EPAP['equipment_value']; ?>"
                    data-type="number"
                    class="data_tooltip editable editable-click editable-noreload"
                >
                  <strong class="under-line"> <?php echo $EPAP['equipment_value']; ?> </strong>
                </a>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong class="visible-print-block"> <?php echo $EPAP['equipment_value']; ?> </strong>
            <?php
              }
              if(!empty($rate)){
            ?>
                <span>RATE</span>
                <a href="javascript:;"
                    id="equipment_value"
                    data-pk="<?php echo $rate['orderID'] ?>"
                    data-url="<?php echo base_url('order/update_data/number/orderID/1'); ?>"
                    data-title="RATE"
                    data-value="<?php echo $rate['equipment_value']; ?>"
                    data-type="number"
                    class="data_tooltip editable editable-click editable-noreload"
                >
                  <strong class="under-line"> <?php echo $rate['equipment_value']; ?> </strong>
                </a>
                <strong class="visible-print-block"> <?php echo $rate['equipment_value']; ?> </strong>
            <?php
              }
            ?>
            </label>
          <?php
          if(!empty($CIPAP)){
          ?>
            <label style="padding-right:30px;">
              CPAP Settings: &nbsp;
              <span>CMH20</span>
              <a href="javascript:;"
                  id="equipment_value"
                  data-pk="<?php echo $CIPAP['orderID'] ?>"
                  data-url="<?php echo base_url('order/update_data/number/orderID/1'); ?>"
                  data-title="CMH20"
                  data-value="<?php echo $CIPAP['equipment_value']; ?>"
                  data-type="number"
                  class="data_tooltip editable editable-click editable-noreload"
              >
                <strong class="under-line"> <?php echo $CIPAP['equipment_value']; ?> </strong>
              </a>
              <strong class="visible-print-block"> <?php echo $CIPAP['equipment_value']; ?> </strong>
          </label>
          <?php
            }
          ?>

        </p>
        <?php } ?>
      </div>
    </div>
  </div>

<div class="line"></div>

<?php
  $patient_mr = $information['medical_record_id'];
  $patient_hospice_id = $information['hospiceID'];
  $patient_orders = check_if_all_pickups($patient_mr,$patient_hospice_id);

  $has_delivery = in_multiarray(1, $patient_orders, "activity_typeid");
  $has_exchange = in_multiarray(3, $patient_orders, "activity_typeid");
  $has_ptmove   = in_multiarray(4, $patient_orders, "activity_typeid");
  $has_respite  = in_multiarray(5, $patient_orders, "activity_typeid");

  $disable_button = "";

  if($has_delivery)
  {
    $disable_button = "";
  }
  else if($has_exchange)
  {
    $disable_button = "";
  }
  else if($has_ptmove)
  {
    $disable_button = "";
  }
  else if($has_respite)
  {
    $disable_button = "";
  }
  else
  {
    $patient_capped_noncapped_orders = get_customer_ordered_capped_non_capped_items($information['patientID']);
    if(empty($patient_capped_noncapped_orders))
    {
      $patient_disposable_orders = get_customer_ordered_disposable_items($information['patientID']);
      if(!empty($patient_disposable_orders))
      {
        $disable_button = "";
      }
      else
      {
        $disable_button = "disabled";
      }
    }
    else
    {
      $disable_button = "disabled";
    }

    // $pickups = check_if_all_pickups_v2($patient_mr,$patient_hospice_id);
    // print_me($disable_button);
    // if($pickups)
    // {
    //   $disable_button = "";
    // }
    // else
    // {
    //   $disable_button = "disabled";
    // }
  }
?>

<!-- <input type="hidden" name="patient_orders_value" value="<?php print_r($pickups); ?>"> -->
<div class="col-sm-12" style="margin-bottom:15px;">
  <div class="pull-right">
    <a class="btn btn-danger btn-xs pull-right additional_equip_button" data-toggle="popover"
      href="<?php echo base_url('order/new_equipment/'.$information['medical_record_id']."/".$information['ordered_by']) ?>" data-value=""
       <?php echo $disable_button ?> >
      <i class="fa fa-plus">
        <span class="font-bold data_tooltip" style="font-family:'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;" title="Click to Add New Activity"> Activity Type</span>
      </i>
    </a>
  </div>

<div class="pull-right">
  <?php
    $activity_counts  = array();
    $label            = array(1=>"Delivery",3=>"Exchange",2=>"Pickup",4=>"CUS Move",5=>"Respite");
    for($i=1;$i<=5;$i++)
    {
      $activity_counts[] = get_count_status("",array(
                              "stats.medical_record_id"       => $patient_mr,
                              "stats.status_activity_typeid"  => $i,
                              "stats.patientID"  => $information['patientID'],
                              // "(orders.order_status != 'confirmed' AND  orders.order_status != 'cancel' AND orders.order_status != 'tobe_confirmed')"         => false
                              "(orders.order_status != 'confirmed' AND  orders.order_status != 'cancel')"         => false
                          ));
      $activities[] = get_status("",array(
                          "stats.medical_record_id"       => $patient_mr,
                          "stats.status_activity_typeid"  => $i,
                          "stats.patientID"  => $information['patientID'],
                          // "(orders.order_status != 'confirmed' AND  orders.order_status != 'cancel' AND orders.order_status != 'tobe_confirmed')"         => false
                          "(orders.order_status != 'confirmed' AND  orders.order_status != 'cancel')"         => false
                      ));
    }
    $index=0;
  ?>
    <ul class="status-count" style="list-style-type:none;">
    <?php
      $count = 0;
      foreach($activity_counts as $key=>$value){
        $count_inside = 0;
        foreach ($activities as $act){
          $another_count = 0;
          if($count == $count_inside){
            if($value > 0){
              if($value > 1){
                $patientID = $this->encryption->encode($information['patientID']);
                if($act[$another_count]['order_status'] != $act[$another_count+1]['order_status'])
                {
                  if($act[$another_count]['order_status'] != "tobe_confirmed" && $act[$another_count+1]['order_status']!= "tobe_confirmed")
                  {
    ?>
                    <li class="pull-left">
                      <span>
                        <a href="<?php echo base_url("order/patient_order_list")."/".$patientID ?>" target="_blank">
                          <?php
                            $address_type = get_address_type($act[$another_count]['addressID']);
                            $address_sequence = 0;
                            $address_count = 1;

                            if(($label[$key+1]) == "Delivery")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Delivery";
                              }
                              else if($address_type['type'] == 1)
                              {
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Delivery (CUS Move)";
                                }
                                else
                                {
                                  echo "Delivery (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Delivery (Respite)";
                                }
                                else
                                {
                                  echo "Delivery (Respite ".$address_sequence.")";
                                }
                              }
                            }
                            else if(($label[$key+1]) == "Exchange")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Exchange";
                              }
                              else if($address_type['type'] == 1)
                              {
                                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Exchange (CUS Move)";
                                }
                                else
                                {
                                  echo "Exchange (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Exchange (Respite)";
                                }
                                else
                                {
                                  echo "Exchange (Respite ".$address_sequence.")";
                                }
                              }
                            }
                            else if(($label[$key+1]) == "CUS Move")
                            {
                              $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "CUS Move";
                              }
                              else
                              {
                                echo "CUS Move ".$address_sequence;
                              }
                            }
                            else if(($label[$key+1]) == "Respite")
                            {
                              $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Respite";
                              }
                              else
                              {
                                echo "Respite ".$address_sequence;
                              }
                            }
                            else if(($label[$key+1]) == "Pickup")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Pickup";
                              }
                              else if($address_type['type'] == 1)
                              {
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Pickup (CUS Move)";
                                }
                                else
                                {
                                  echo "Pickup (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Pickup (Respite)";
                                }
                                else
                                {
                                  echo "Pickup (Respite ".$address_sequence.")";
                                }
                              }
                            }
                          ?>
                        </a>
                      </span>
                      &nbsp;
                      <span>
                        <strong><?php echo $value; ?></strong>
                      </span>
                    </li>
    <?php
                  }else{
                    $pop_over_content_f1 = "<a href='".base_url("order/patient_order_list")."/".$patientID."' target='_blank'> Customer Order Status</a></span>";
                    $pop_over_content_f2 = "<a href='".base_url("order/patient_list_tobe_confirmed")."/".$patientID."' target='_blank'> Confirm Work Orders</a></span>";
                    $pop_over_content = $pop_over_content_f1."<br />".$pop_over_content_f2;
    ?>
                    <li class="pull-left">
                      <span>
                        <a
                          href="javascript:;"
                          rel="popover"
                          data-html="true"
                          data-toggle="popover"
                          data-trigger="focus"
                          data-placement="top"
                          data-content="<?php echo $pop_over_content; ?>"
                        >
                          <?php
                            $address_type = get_address_type($act[$another_count]['addressID']);
                            $address_sequence = 0;
                            $address_count = 1;

                            if(($label[$key+1]) == "Delivery")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Delivery";
                              }
                              else if($address_type['type'] == 1)
                              {
                                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Delivery (CUS Move)";
                                }
                                else
                                {
                                  echo "Delivery (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Delivery (Respite)";
                                }
                                else
                                {
                                  echo "Delivery (Respite ".$address_sequence.")";
                                }
                              }
                            }
                            else if(($label[$key+1]) == "Exchange")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Exchange";
                              }
                              else if($address_type['type'] == 1)
                              {
                                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Exchange (CUS Move)";
                                }
                                else
                                {
                                  echo "Exchange (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Exchange (Respite)";
                                }
                                else
                                {
                                  echo "Exchange (Respite ".$address_sequence.")";
                                }
                              }
                            }
                            else if(($label[$key+1]) == "CUS Move")
                            {
                              $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "CUS Move";
                              }
                              else
                              {
                                echo "CUS Move ".$address_sequence;
                              }
                            }
                            else if(($label[$key+1]) == "Respite")
                            {
                              $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Respite";
                              }
                              else
                              {
                                echo "Respite ".$address_sequence;
                              }
                            }
                            else if(($label[$key+1]) == "Pickup")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Pickup";
                              }
                              else if($address_type['type'] == 1)
                              {
                                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Pickup (CUS Move)";
                                }
                                else
                                {
                                  echo "Pickup (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Pickup (Respite)";
                                }
                                else
                                {
                                  echo "Pickup (Respite ".$address_sequence.")";
                                }
                              }
                            }
                          ?>
                        </a>
                      </span>
                      &nbsp;
                      <span><strong><?php echo $value; ?></strong></span>
                    </li>
    <?php
                  }
                }
                else
                {
                  if($act[0]['order_status'] == "pending" || $act[0]['order_status'] == "on-hold" || $act[0]['order_status'] == "active" || $act[0]['order_status'] == "re-schedule"){
    ?>
                    <li class="pull-left">
                      <span>
                        <a href="<?php echo base_url("order/patient_order_list")."/".$patientID ?>" target="_blank">
                          <?php
                            $address_type = get_address_type($act[0]['addressID']);
                            $address_sequence = 0;
                            $address_count = 1;

                            if(($label[$key+1]) == "Delivery")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Delivery";
                              }
                              else if($address_type['type'] == 1)
                              {
                                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[0]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Delivery (CUS Move)";
                                }
                                else
                                {
                                  echo "Delivery (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[0]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Delivery (Respite)";
                                }
                                else
                                {
                                  echo "Delivery (Respite ".$address_sequence.")";
                                }
                              }
                            }
                            else if(($label[$key+1]) == "Exchange")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Exchange";
                              }
                              else if($address_type['type'] == 1)
                              {
                                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[0]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Exchange (CUS Move)";
                                }
                                else
                                {
                                  echo "Exchange (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[0]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Exchange (Respite)";
                                }
                                else
                                {
                                  echo "Exchange (Respite ".$address_sequence.")";
                                }
                              }
                            }
                            else if(($label[$key+1]) == "CUS Move")
                            {
                              $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "CUS Move";
                              }
                              else
                              {
                                echo "CUS Move ".$address_sequence;
                              }
                            }
                            else if(($label[$key+1]) == "Respite")
                            {
                              $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Respite";
                              }
                              else
                              {
                                echo "Respite ".$address_sequence;
                              }
                            }
                            else if(($label[$key+1]) == "Pickup")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Pickup";
                              }
                              else if($address_type['type'] == 1)
                              {
                                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[0]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Pickup (CUS Move)";
                                }
                                else
                                {
                                  echo "Pickup (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[0]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Pickup (Respite)";
                                }
                                else
                                {
                                  echo "Pickup (Respite ".$address_sequence.")";
                                }
                              }
                            }
                          ?>
                        </a>
                      </span>
                      &nbsp;
                      <span>
                        <strong><?php echo $value; ?></strong>
                      </span>
                    </li>
    <?php
                  }else if($act[$another_count]['order_status'] == "tobe_confirmed"){
    ?>
                    <li class="pull-left">
                      <span>
                        <a href="<?php echo base_url("order/patient_list_tobe_confirmed")."/".$patientID ?>" target="_blank">
                          <?php
                            $address_type = get_address_type($act[$another_count]['addressID']);
                            $address_sequence = 0;
                            $address_count = 1;
                            if(($label[$key+1]) == "Delivery")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Delivery";
                              }
                              else if($address_type['type'] == 1)
                              {
                                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Delivery (CUS Move)";
                                }
                                else
                                {
                                  echo "Delivery (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Delivery (Respite)";
                                }
                                else
                                {
                                  echo "Delivery (Respite ".$address_sequence.")";
                                }
                              }
                            }
                            else if(($label[$key+1]) == "Exchange")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Exchange";
                              }
                              else if($address_type['type'] == 1)
                              {
                                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Exchange (CUS Move)";
                                }
                                else
                                {
                                  echo "Exchange (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Exchange (Respite)";
                                }
                                else
                                {
                                  echo "Exchange (Respite ".$address_sequence.")";
                                }
                              }
                            }
                            else if(($label[$key+1]) == "CUS Move")
                            {
                              $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "CUS Move";
                              }
                              else
                              {
                                echo "CUS Move ".$address_sequence;
                              }
                            }
                            else if(($label[$key+1]) == "Respite")
                            {
                              $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Respite";
                              }
                              else
                              {
                                echo "Respite ".$address_sequence;
                              }
                            }
                            else if(($label[$key+1]) == "Pickup")
                            {
                              if(($address_type['type']) == 0)
                              {
                                echo "Pickup";
                              }
                              else if($address_type['type'] == 1)
                              {
                                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Pickup (CUS Move)";
                                }
                                else
                                {
                                  echo "Pickup (CUS Move ".$address_sequence.")";
                                }
                              }
                              else
                              {
                                $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                                foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                  if($addresses_ID_row['id'] == $act[$another_count]['addressID'])
                                  {
                                    $address_sequence = $address_count;
                                    break;
                                  }
                                  $address_count++;
                                }
                                if($address_sequence == 1)
                                {
                                  echo "Pickup (Respite)";
                                }
                                else
                                {
                                  echo "Pickup (Respite ".$address_sequence.")";
                                }
                              }
                            }
                          ?>
                        </a>
                      </span>
                      &nbsp;
                      <span>
                        <strong><?php echo $value; ?></strong>
                      </span>
                    </li>
    <?php
                  }
                }
                $another_count++;
              }
              else
              {
                $patientID = $this->encryption->encode($information['patientID']);
                if($act[0]['order_status'] == "pending" || $act[0]['order_status'] == "on-hold" || $act[0]['order_status'] == "active" || $act[0]['order_status'] == "re-schedule"){
    ?>
                  <li class="pull-left">
                    <span>
                      <a href="<?php echo base_url("order/patient_order_list")."/".$patientID ?>" target="_blank">
                        <?php
                          $address_type = get_address_type($act[0]['addressID']);
                          $address_sequence = 0;
                          $address_count = 1;
                          if(($label[$key+1]) == "Delivery")
                          {
                            if(($address_type['type']) == 0)
                            {
                              echo "Delivery";
                            }
                            else if($address_type['type'] == 1)
                            {
                              $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Delivery (CUS Move)";
                              }
                              else
                              {
                                echo "Delivery (CUS Move ".$address_sequence.")";
                              }
                            }
                            else
                            {
                              $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Delivery (Respite)";
                              }
                              else
                              {
                                echo "Delivery (Respite ".$address_sequence.")";
                              }
                            }
                          }
                          else if(($label[$key+1]) == "Exchange")
                          {
                            if(($address_type['type']) == 0)
                            {
                              echo "Exchange";
                            }
                            else if($address_type['type'] == 1)
                            {
                              $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Exchange (CUS Move)";
                              }
                              else
                              {
                                echo "Exchange (CUS Move ".$address_sequence.")";
                              }
                            }
                            else
                            {
                              $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Exchange (Respite)";
                              }
                              else
                              {
                                echo "Exchange (Respite ".$address_sequence.")";
                              }
                            }
                          }
                          else if(($label[$key+1]) == "CUS Move")
                          {
                            $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                            foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                              if($addresses_ID_row['id'] == $act[0]['addressID'])
                              {
                                $address_sequence = $address_count;
                                break;
                              }
                              $address_count++;
                            }
                            if($address_sequence == 1)
                            {
                              echo "CUS Move";
                            }
                            else
                            {
                              echo "CUS Move ".$address_sequence;
                            }
                          }
                          else if(($label[$key+1]) == "Respite")
                          {
                            $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                            foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                              if($addresses_ID_row['id'] == $act[0]['addressID'])
                              {
                                $address_sequence = $address_count;
                                break;
                              }
                              $address_count++;
                            }
                            if($address_sequence == 1)
                            {
                              echo "Respite";
                            }
                            else
                            {
                              echo "Respite ".$address_sequence;
                            }
                          }
                          else if(($label[$key+1]) == "Pickup")
                          {
                            if(($address_type['type']) == 0)
                            {
                              echo "Pickup";
                            }
                            else if($address_type['type'] == 1)
                            {
                              $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Pickup (CUS Move)";
                              }
                              else
                              {
                                echo "Pickup (CUS Move ".$address_sequence.")";
                              }
                            }
                            else
                            {
                              $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Pickup (Respite)";
                              }
                              else
                              {
                                echo "Pickup (Respite ".$address_sequence.")";
                              }
                            }
                          }
                        ?>
                      </a>
                    </span>
                    &nbsp;
                    <span>
                      <strong><?php echo $value; ?></strong>
                    </span>
                  </li>
    <?php
                }
                else if($act[0]['order_status'] == "tobe_confirmed")
                {
    ?>
                  <li class="pull-left">
                    <span>
                      <a href="<?php echo base_url("order/patient_list_tobe_confirmed")."/".$patientID ?>" target="_blank">
                        <?php
                          $address_type = get_address_type($act[0]['addressID']);
                          $address_sequence = 0;
                          $address_count = 1;

                          if(($label[$key+1]) == "Delivery")
                          {
                            if(($address_type['type']) == 0)
                            {
                              echo "Delivery";
                            }
                            else if($address_type['type'] == 1)
                            {
                              $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Delivery (CUS Move)";
                              }
                              else
                              {
                                echo "Delivery (CUS Move ".$address_sequence.")";
                              }
                            }
                            else
                            {
                              $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Delivery (Respite)";
                              }
                              else
                              {
                                echo "Delivery (Respite ".$address_sequence.")";
                              }
                            }
                          }
                          else if(($label[$key+1]) == "Exchange")
                          {
                            if(($address_type['type']) == 0)
                            {
                              echo "Exchange";
                            }
                            else if($address_type['type'] == 1)
                            {
                              $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Exchange (CUS Move)";
                              }
                              else
                              {
                                echo "Exchange (CUS Move ".$address_sequence.")";
                              }
                            }
                            else
                            {
                              $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Exchange (Respite)";
                              }
                              else
                              {
                                echo "Exchange (Respite ".$address_sequence.")";
                              }
                            }
                          }
                          else if(($label[$key+1]) == "CUS Move")
                          {
                            $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                            foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                              if($addresses_ID_row['id'] == $act[0]['addressID'])
                              {
                                $address_sequence = $address_count;
                                break;
                              }
                              $address_count++;
                            }
                            if($address_sequence == 1)
                            {
                              echo "CUS Move";
                            }
                            else
                            {
                              echo "CUS Move ".$address_sequence;
                            }
                          }
                          else if(($label[$key+1]) == "Respite")
                          {
                            $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                            foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                              if($addresses_ID_row['id'] == $act[0]['addressID'])
                              {
                                $address_sequence = $address_count;
                                break;
                              }
                              $address_count++;
                            }
                            if($address_sequence == 1)
                            {
                              echo "Respite";
                            }
                            else
                            {
                              echo "Respite ".$address_sequence;
                            }
                          }
                          else if(($label[$key+1]) == "Pickup")
                          {
                            if(($address_type['type']) == 0)
                            {
                              echo "Pickup";
                            }
                            else if($address_type['type'] == 1)
                            {
                              $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($information['patientID']);
                              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Pickup (CUS Move)";
                              }
                              else
                              {
                                echo "Pickup (CUS Move ".$address_sequence.")";
                              }
                            }
                            else
                            {
                              $respite_addresses_ID = get_respite_addresses_ID_v2($information['patientID']);
                              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                                if($addresses_ID_row['id'] == $act[0]['addressID'])
                                {
                                  $address_sequence = $address_count;
                                  break;
                                }
                                $address_count++;
                              }
                              if($address_sequence == 1)
                              {
                                echo "Pickup (Respite)";
                              }
                              else
                              {
                                echo "Pickup (Respite ".$address_sequence.")";
                              }
                            }
                          }
                        ?>
                      </a>
                    </span>
                    &nbsp;
                    <span>
                      <strong><?php echo $value; ?></strong>
                    </span>
                  </li>
    <?php
                }
              }
            }
          }
          // else
          // {
          //   break;
          // }
          $count_inside++;
        }
        $index++;
        $count++;
      }
    ?>
    </ul>
  </div>
</div>

<?php if($this->session->userdata('account_type') == "dme_admin") :?>
  <div class="col-sm-12">
      <a class="btn btn-success btn-xs pull-right activity_type_section_btn" data-toggle="popover" style="margin-bottom: 29px;margin-right: 1px;color:#fff;margin-top:-5px;display:none">
          <span class="font-bold data_tooltip"> Reactivate Customer</span>
      </a>
  </div>
<?php endif;?>

  <div class="col-md-12">
    <div class="row">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">
          <h4>Customer Order Summary</h4>
        </div>
        <div class="panel-body">

          <div class="table-responsive">
            <table class="table" id="equipment_summary_tbl">
              <thead>
                <tr>
                  <!-- <th style="width: 40px">WO#</th> -->
                  <!-- <th style="width: 40px">Date Ordered</th> -->
                  <th> Delivery Date</th>
                  <th> WO#</th>
                  <th> Activity Type</th>
                  <th class="hide_on_print"> Item #</th>
                  <th> Item Description</th>
                  <th> Qty.</th>
                  <th> Serial/Lot #</th>
                  <th> WO#</th>
                  <th> Picked Up Date</th>
                  <th> Type</th>
                  <th style="width:38px;"> <i class="fa fa-map-marker"></i></th>
                </tr>
              </thead>
              <tbody
                class="customer_ordered_items_tbody"
                data-medical-id="<?php echo $unique_id; ?>"
                data-hospice-id="<?php echo $hospiceID; ?>"
                data-patient-id="<?php echo $information['patientID']; ?>"
              >
                <tr>
                  <td colspan="11" align="center">
                    <i style="font-size:30px;margin-top:30px;" class='fa fa-spin fa-spinner'></i>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>

<?php echo form_open("",array("id"=>"add_additional_equipment_form")) ;?>
<!-- Modal For Equipments -->
<div class="modal " id="equipments_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg" style="overflow:hidden !important">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Equipment</h4>
      </div>
      <div class="modal-body" style="overflow-y:scroll;max-height:800px;">
          <div class="row">
              <div class="col-md-12" style="margin-bottom:5px">
           <div class="col-sm-3" style="margin-top:15px">
              <label>First Name<span class="text-danger-dker">*</span></label>
              <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_fname"  autocomplete="off" placeholder="Placing Order First Name">
          </div>
           <div class="col-sm-3" style="margin-top:15px">
              <label>Last Name<span class="text-danger-dker">*</span></label>
              <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_lname"  autocomplete="off" placeholder="Placing Order Last Name">
          </div>
      </div>
      <div class="col-md-12" style="margin-bottom:5px">
           <div class="col-sm-6">
              <label>Cellphone Number<span class="text-danger-dker">*</span></label>
              <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_cpnum"  autocomplete="off" placeholder="Cellphone No.">
          </div>
      </div>
      <div class="col-md-12" style="margin-bottom:5px">
           <div class="col-sm-6">
              <label>Email Address<span class="text-danger-dker">*</span></label>
              <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_email"  autocomplete="off" placeholder="Email Address">
          </div>
      </div>

        <div class="col-md-12" style="min-height: 470px;margin-top:40px">
           <?php if (!empty($equipments)) : ?>
              <?php foreach ($equipments as $equipment) :?>
                <div class="form-group col-md-8 wrapper-equipment" data-value="<?php echo $equipment['categoryID'] ?>" id="wrapper_equip_<?php echo $equipment['categoryID'] ?>">
                  <label class="btn btn-default"  style="margin-bottom:20px;margin-top:20px;"  id="equip_<?php echo $equipment['categoryID'] ?>"><?php echo $equipment['type'] ?></label> <br>
                  ttt
                  <div class="equipment" style="display:none;">

                      <label style="margin-top:10px;visibility:hidden"><?php echo $equipment['type'] ?> <span class="text-danger-dker">*</span></label>
                      <div class="col-md-6" style="padding-left:15px;">
                          <?php foreach ($equipment['children'] as $key => $child) : ?>
                            <div class="checkbox">
                              <label class="i-checks">
                                  <input type="checkbox" id="" value="<?php echo $child['equipmentID'] ?>"
                                         name="equipments[]"
                                         data-target="#<?php echo trim($child['key_name']) ?>_<?php echo $equipment['categoryID']; ?>"
                                         data-name="<?php echo trim($child['key_name']); ?>"
                                         data-desc="<?php echo trim($child['key_desc']); ?>"
                                         data-value="<?php echo $child['key_desc']; ?>"
                                         data-category="<?php echo $equipment['type']; ?>"
                                         data-category-id="<?php echo $equipment['categoryID']; ?>"
                                         class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID'] ?>">
                                  <i></i>
                                  <?php echo $child['key_desc'] ?>
                              </label>
                            </div>
                            <?php
                            if ($key == $equipment['division'] - 1) {
                                break;
                            }
                            ?>
                        <?php endforeach; ?>
                      </div>
                      <div class="col-md-6" style="padding-left:15px;margin-top:-38px;" id="">
                          <?php for ($i = $equipment['division']; $i <= $equipment['last']; $i++) : ?>
                              <?php
                              $child = $equipment['children'][$i];
                              ?>
                              <div class="checkbox">
                                <label class="i-checks">
                                    <input type="checkbox" id="" value="<?php echo $child['equipmentID'] ?>"
                                           name="equipments[]"
                                           data-target="#<?php echo trim($child['key_name']) ?>_<?php echo $equipment['categoryID']; ?>"
                                           data-name="<?php echo trim($child['key_name']); ?>"
                                           data-desc="<?php echo trim($child['key_desc']); ?>"
                                           data-value="<?php echo $child['key_desc']; ?>"
                                           data-category="<?php echo $equipment['type']; ?>"
                                           data-category-id="<?php echo $equipment['categoryID']; ?>"
                                           class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID'] ?>">
                                    <i></i>
                                    <?php echo $child['key_desc'] ?>
                                </label>
                              </div>

                          <?php endfor; ?>
                      </div>
                  </div>
                </div>
            <?php endforeach; ?>
          <?php endif; ?>

          <div class="col-md-4 col-md-offset-7" style="position:absolute">
            <div class="panel panel-default" style="margin-top: 15px;margin-left: 65px;">
               <div class="panel-heading font-bold">Order Summary</div>
                <div class="panel-body order-cont" style="max-height: 390px !important;overflow-y:scroll;">

                </div>
            </div>
        </div>

        </div>

        <input type="hidden" value="<?php echo $dme_order_row['pickup_date'] ?>" name="pickup_date" />
        <input type="hidden" value="<?php echo $dme_order_row['activity_typeid'] ?>" name="activity_typeid" />
        <?php
          if(!empty($information['ordered_by']))
          {
        ?>
            <input type="hidden" value="<?php echo $information['ordered_by'] ?>" name="organization_id" />
        <?php
          }
          else
          {
        ?>
            <input type="hidden" value="<?php echo $information['ordered_by'] ?>" name="organization_id" />
        <?php
          }
        ?>

        <input type="hidden" value="<?php echo $information['ordered_by'] ?>" name="ordered_by" />
        <!-- <input type="hidden" value="<?php echo $dme_order_row['who_ordered_fname'] ?>" name="who_ordered_fname" />
        <input type="hidden" value="<?php echo $dme_order_row['who_ordered_lname'] ?>" name="who_ordered_lname" />
        <input type="hidden" value="<?php echo $dme_order_row['who_ordered_email'] ?>" name="who_ordered_email" />
        <input type="hidden" value="<?php echo $dme_order_row['who_ordered_cpnum'] ?>" name="who_ordered_cpnum" /> -->
        <input type="hidden" value="<?php echo $dme_order_row['comment'] ?>" name="comment" />
        <input type="hidden" value="<?php echo $dme_order_row['date_ordered'] ?>" name="date_ordered" />
        <input type="hidden" value="<?php echo $dme_order_row['uniqueID'] ?>" name="uniqueID" />
        <input type="hidden" value="<?php echo $dme_order_row['order_status'] ?>" name="order_status" />
        <input type="hidden" value="<?php echo $dme_order_row['deliver_to_type'] ?>" name="delivery_to_type" />
        <input type="hidden" value="<?php echo $information['medical_record_id'] ?>" name="medical_record_id" />
        <?php $id = $this->session->userdata('userID'); ?>
        <input type="hidden" name="person_who_ordered" value="<?php echo $id; ?>" />


          </div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save_additional_btn" data-id="<?php echo $info['patientID'] ?>">Save Order</button>
      </div>
    </div>
  </div>
</div>
<!-- END -->


 <!-- Modal for Viewing per Work Order Number -->
<?php foreach($orders as $key=>$value) :?>
    <?php foreach($value as $sub_key=>$sub_value) :?>
      <?php $info = $sub_value[0] ; ?>

<div class="modal " id="view_wo_modal<?php echo $info['uniqueID'] ?>" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
            <div class="modal-header">
              <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">WO# <?php echo $info['uniqueID'] ?></h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
                <div class="row">
                    <div class="">
                        <div class="col-md-6">
                            <strong>Work Order #</strong>
                            <p><?php echo $info['uniqueID'] ?></p>

                            <strong>Date Ordered</strong>
                            <p><?php echo date("m/d/Y", strtotime($info['date_ordered'])) ?></p>

                            <strong>Staff Member</strong>
                            <?php if($info['staff_member_fname'] == '' && $info['staff_member_lname'] == '') :?>
                              <p>NA</p>
                            <?php else:?>
                              <p><?php echo $info['staff_member_fname']  ." ". $info['staff_member_lname'] ?></p>
                            <?php endif;?>

                            <strong>Hospice Staff</strong>
                            <?php if($info['who_ordered_lname'] == '' && $info['who_ordered_fname'] == '') :?>
                              <p>NA</p>
                            <?php else:?>
                              <p><?php echo $info['who_ordered_fname']  ." ". $info['who_ordered_lname'] ?></p>
                            <?php endif;?>

                           <strong>Cellphone Number</strong>
                           <?php if($info['who_ordered_cpnum'] == '') :?>
                            <p>N/A</p>
                          <?php else:?>
                            <p><?php echo $info['who_ordered_cpnum'] ?></p>
                          <?php endif;?>

                          <strong>Gender</strong>
                           <?php if($info['relationship_gender'] == 1) :?>
                            <p>Male</p>
                          <?php else:?>
                            <p>Female</p>
                          <?php endif;?>

                          <strong>Height(ft), Weight(lbs)</strong>
                            <p><?php echo $info['p_height'] ?> , <?php echo $info['p_weight'] ?></p>

                        </div>
                        <div class="col-md-6">
                           <strong>Email Address</strong>
                           <?php if($info['who_ordered_email'] == '') :?>
                              <p>N/A</p>
                           <?php else:?>
                              <p><?php echo $info['who_ordered_email'] ?></p>
                           <?php endif;?>

                            <strong>Delivery Date</strong>
                           <?php if($info['pickup_date'] == '') :?>
                              <p>N/A</p>
                           <?php else:?>
                              <p><?php echo $info['pickup_date'] ?></p>
                           <?php endif;?>

                          <strong>Customer Residence</strong>
                          <?php
                            $ptmove_residence = get_new_patient_residence($info['patientID']);
                            if(!empty($ptmove_residence)) :
                          ?>
                            <p><?php echo $ptmove_residence['ptmove_patient_residence'] ?></p>
                          <?php else:?>
                            <p><?php echo $info['deliver_to_type'] ?></p>
                          <?php endif;?>

                           <strong>Notes</strong>
                           <?php if($info['comment'] == '') :?>
                              <p>N/A</p>
                           <?php else:?>
                              <p><?php echo $info['comment'] ?></p>
                           <?php endif;?>

                           <strong>Relationship</strong>
                           <?php if($info['p_relationship'] == '') :?>
                              <p>N/A</p>
                           <?php else:?>
                              <p><?php echo $info['p_relationship'] ?></p>
                           <?php endif;?>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-order-close">Close</button>
            </div>
        </div>
    </div>
</div>
  <?php endforeach ;?>
<?php endforeach ;?>

<!-- End -->



<?php endif; ?>

<div class="bg-light lter wrapper-md" style="margin-top:20px">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<!-- Modal for Oxygen concentrator -->
    <div class="modal fade modal_oxygen_concentrator_1" id="oxygen_concentrator_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[61][77]" class="form-control liter_flow_field" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"  class="5_ltr" data-desc="Oxygen Concentrator Type" data-value="5 LPM" name="subequipment[61][80]" id="optionsRadios1" value="5" >
                                            5 LPM
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="10_ltr" data-desc="Oxygen Concentrator Type" data-value="10 LPM"name="subequipment[61][81]" id="optionsRadios1" value="10" >
                                            10 LPM
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">


                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[61][radio][]" id="optionsRadios1" value="78" >
                                            CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[61][radio][]" id="optionsRadios1" value="79" >
                                            PRN
                                        </label>
                                    </div>
                                    <br /> <br/>
                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device" data-value="Nasal Canula" name="subequipment[61][radio][flt]" id="flowtype" value="82" >
                                            Nasal Canula
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[61][radio][flt]" id="optionsRadios1" value="83" >
                                            Oxygen Mask
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Oxygen conserving device -->
    <div class="modal fade modal_oxygen_conserving_device_1" id="oxygen_conserving_device_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Conserving Device</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[62][188]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Type<span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Type" data-value="With Bag" name="subequipment[62][radio][]" id="optionsRadios1" value="197">
                                            With Bag
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Type" data-value="Without Bag" name="subequipment[62][radio][]" id="optionsRadios1" value="198">
                                            Without Bag
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for Oxygen E Portable System CAPPED-->
    <div class="modal fade modal_oxygen_e_portable_system_1" id="oxygen_e_portable_system_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen E Portable System</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[174][189]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal hi-low -->
    <div class="modal fade modal_hi-low_electric_hospital_bed_2" id="hi-low_electric_hospital_bed_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hi-Low Full Electric Hospital Bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Bed Type <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Full Rails" name="subequipment[55][radio][]" id="optionsRadios1" value="74" >
                                        Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Half Rails" name="subequipment[55][radio][]" id="optionsRadios1" value="75" >
                                        Half Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="No Rails"  name="subequipment[55][radio][]" id="optionsRadios1" value="76" >
                                        No Rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal gastric-drainage -->
    <div class="modal fade modal_gastric_drainage_aspirator_2" id="gastric_drainage_aspirator_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Gastric Drainage Aspirator</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Gastric Drainage Type <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Gastric Drainage Type" data-value="Cont." name="subequipment[16][radio][]" id="optionsRadios1" value="122">
                                        Cont.
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Gastric Drainage Type" data-value="Intermittant" name="subequipment[16][radio][]" id="optionsRadios1" value="123">
                                        Intermittant
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Small volume nebulizer -->
    <div class="modal fade modal_small_volume_nebulizer_1" id="small_volume_nebulizer_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Small Volume Nebulizer</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Aerosol Mask <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Aerosol Mask"  data-value="Yes" name="subequipment[67][radio][]" id="optionsRadios1" value="90">
                                        Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Aerosol Mask"  data-value="No" name="subequipment[67][radio][]" id="optionsRadios1" value="91">
                                        No
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reclining wheelchair -->
    <div class="modal fade modal_reclining_wheelchair_2" id="reclining_wheelchair_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reclining wheelchair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Reclining Wheelchair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Reclining Wheelchair" data-value='16"'  name="subequipment[64][radio][trw]" id="optionsRadios1" value="84">
                                        16"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               data-desc="Type of Reclining Wheelchair" data-value='18"'
                                               name="subequipment[64][radio][trw]" id="optionsRadios1" value="85">
                                        18"
                                    </label>
                                </div>

                                <label style="margin-top: 20px;">Type of Legrest (R) <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               data-desc="Type of Legrest (R)" data-value='Elevating Legrests'
                                               name="subequipment[64][radio][tol]" id="optionsRadios1" value="86" >
                                        Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               data-desc="Type of Legrest (R)" data-value='Footrests'
                                               name="subequipment[64][radio][tol]" id="optionsRadios1" value="87" >
                                        Footrests
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Shower chair -->
    <div class="modal fade modal_shower_chair_1" id="shower_chair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Shower Chair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Shower Chair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Shower Chair" data-value="With Back" name="subequipment[66][radio][]" id="optionsRadios1" value="88">
                                        With Back
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Shower Chair" data-value="Without Back" name="subequipment[66][radio][]" id="optionsRadios1" value="89">
                                        Without Back
                                    </label>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Geri chair -->
    <div class="modal fade modal_geri_chair_1" id="geri_chair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Geri Chair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Geri Chair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="With Tray" name="subequipment[54][radio][]" id="optionsRadios1" value="192">
                                        With Tray
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="Without Tray" name="subequipment[54][radio][]" id="optionsRadios1" value="193">
                                        Without Tray
                                    </label>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal wheelchair -->
    <div class="modal fade modal_wheelchair_1" id="wheelchair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Wheelchair <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='16"' name="subequipment[71][radio][]" id="optionsRadios1" value="92" >
                                        16"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='18"' name="subequipment[71][radio][]" id="optionsRadios1" value="93" >
                                        18"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='20"' name="subequipment[71][radio][]" id="optionsRadios1" value="94" >
                                        20"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='22"' name="subequipment[71][radio][]" id="optionsRadios1" value="95" >
                                        22"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='24"' name="subequipment[71][radio][]" id="optionsRadios1" value="96" >
                                        24"
                                    </label>
                                </div>

                                <br>
                                <label>Type of Legrest <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Legrest" data-value='Elevating Legrests' name="subequipment[71][radio][2]" id="optionsRadios1" value="97" >
                                        Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Legrest" data-value='Footrests' name="subequipment[71][radio][2]" id="optionsRadios1" value="98" checked>
                                        Footrests
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal hospital bed -->
    <div class="modal fade modal_hospital_bed_1" id="hospital_bed_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Rails<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="Full Rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="74" >
                                        Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="Half Rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="75" >
                                        Half Rails
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="No rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="76" >
                                        No rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal BIPAP setup -->
    <div class="modal fade modal_bipap_2" id="bipap_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">BIPAP Settings</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>IPAP <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="IPAP" class="form-control" placeholder="e.g 14" name="subequipment[4][109]">
                                </div>

                                <div class="form-group">
                                    <label>EPAP <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="EPAP" class="form-control" placeholder="e.g5" name="subequipment[4][110]">
                                </div>

                                <div class="form-group">
                                    <label>Rate <i>(If applicable)</i> </label>
                                    <input type="text" data-desc="Rate" class="form-control" placeholder="" name="subequipment[4][111]">
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal CPAP setup -->
    <div class="modal fade modal_cpap_2" id="cpap_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg equipments_modal" id="myModalLabel">CPAP Settings</h4>
                </div>
                <div class="modal-body OpenSans-Reg">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>CMH20 <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="IPAP" class="form-control" placeholder="e.g 14" name="subequipment[9][114]">
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal E-cylinder -->
    <div class="modal fade modal_e-cylinder_2" id="e-cylinder_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">E-Cylinder</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of E-Cylinder <span style="color:red;">*</span></label>
                                    <input type="text"  data-desc="Quantity of E-cylinder" class="form-control" placeholder="ex. 1" name="subequipment[11][121]">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal M6-Cylinder -->
    <div class="modal fade modal_cylinder_m6_2" id="cylinder_m6_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">M6 Cylinder</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of M6 Cylinder <span style="color:red;">*</span></label>
                                    <input type="text"  data-desc="Quantity of M6 Cylinder" class="form-control" placeholder="ex. 1" name="subequipment[170][194]">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pressure-mattress -->
    <div class="modal fade modal_alternating_pressure_mattress_2" id="alternating_pressure_mattress_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Alternating Pressure Mattress</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Extended? NC <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Extended? NC" data-value="Yes" name="subequipment[2][radio][]" id="optionsRadios1" value="107" >
                                        Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Extended? NC" data-value="No" name="subequipment[2][radio][]" id="optionsRadios1" value="108" >
                                        No
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal m6-cylinder -->
    <div class="modal fade modal_m6-cylinder_2" id="m6-cylinder_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">M6-Cylinder</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of M6-Cylinder NC <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="Quantity of M6-cylinder NC" class="form-control" placeholder="ex. 1" name="subequipment[27][99]">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- additional -->
    <!-- Modal hospital bed -->
    <div class="modal fade modal_hospital_bed_2" id="hospital_bed_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label style="margin-top: 20px;">Type of Rails<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="Full Rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="136" >
                                        Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="Half Rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="137" >
                                        Half Rails
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="No rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="138" >
                                        No rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Oxygen concentrator -->
    <div class="modal fade modal_oxygen_concentrator_2" id="oxygen_concentrator_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[29][100]" class="form-control liter_flow_field" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="5_ltr" data-desc="Oxygen Concentrator Type" data-value="5 LPM" name="subequipment[29][101]" id="optionsRadios1" value="5" >
                                            5 LPM
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="10_ltr" data-desc="Oxygen Concentrator Type" data-value="10 LPM"name="subequipment[29][102]" id="optionsRadios1" value="10" >
                                            10 LPM
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">


                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[29][radio][]" id="optionsRadios1" value="103" >
                                            CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[29][radio][]" id="optionsRadios1" value="104" >
                                            PRN
                                        </label>
                                    </div>
                                    <br /> <br/>
                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device" data-value="Nasal Canula" name="subequipment[29][radio][flt]" id="flowtype" value="105" >
                                            Nasal Canula
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[29][radio][flt]" id="optionsRadios1" value="106" >
                                            Oxygen Mask
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Oxygen Reservoir -->
    <div class="modal fade modal_oxygen_liquid_2" id="oxygen_liquid_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Liquid Reservoir</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[36][201]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[36][radio][]" id="optionsRadios1" value="202" >
                                            CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[36][radio][]" id="optionsRadios1" value="203" >
                                            PRN
                                        </label>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device" data-value="Nasal Canula" name="subequipment[36][radio][flt]" id="" value="204" >
                                            Nasal Canula
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[36][radio][flt]" id="optionsRadios1" value="205" >
                                            Oxygen Mask
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Oxygen conserving device NONCAPPED-->
    <div class="modal fade modal_oxygen_conserving_device_2" id="oxygen_conserving_device_2" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Conserving Device</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[31][190]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Type<span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Type" data-value="With Bag" name="subequipment[31][radio][]" id="optionsRadios1" value="199">
                                            With Bag
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Type" data-value="Without Bag" name="subequipment[31][radio][]" id="optionsRadios1" value="200">
                                            Without Bag
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for Oxygen E Portable System NONCAPPED-->
    <div class="modal fade modal_oxygen_e_portable_system_2" id="oxygen_e_portable_system_2" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen E Portable System</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[176][191]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal Shower chair -->
    <div class="modal fade modal_shower_chair_2" id="shower_chair_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Shower chair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Shower chair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Shower chair" data-value="With Back" name="subequipment[39][radio][]" id="optionsRadios1" value="112">
                                        With Back
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Shower chair" data-value="Without Back" name="subequipment[39][radio][]" id="optionsRadios1" value="113">
                                        Without Back
                                    </label>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Small volume nebulizer -->
    <div class="modal fade modal_small_volume_nebulizer_2" id="small_volume_nebulizer_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Small Volume Nebulizer</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Aerosol Mask <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Aerosol Mask"  data-value="Yes" name="subequipment[40][radio][]" id="optionsRadios1" value="115">
                                        Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Aerosol Mask"  data-value="No" name="subequipment[40][radio][]" id="optionsRadios1" value="116">
                                        No
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal wheelchair -->
    <div class="modal fade modal_wheelchair_2" id="wheelchair_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Wheelchair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='16"' name="subequipment[49][radio][]" id="optionsRadios1" value="124" >
                                        16"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='18"' name="subequipment[49][radio][]" id="optionsRadios1" value="125" >
                                        18"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='20"' name="subequipment[49][radio][]" id="optionsRadios1" value="126" >
                                        20"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='22"' name="subequipment[49][radio][]" id="optionsRadios1" value="127" >
                                        22"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='24"' name="subequipment[49][radio][]" id="optionsRadios1" value="128" >
                                        24"
                                    </label>
                                </div>


                                <label>Type of Legrest<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Legres" data-value='Elevating Legrests' name="subequipment[49][radio][2]" id="optionsRadios1" value="132" >
                                        Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Legres" data-value='Footrests' name="subequipment[49][radio][2]" id="optionsRadios1" value="133" checked>
                                        Footrests
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- For the DISPOSABLE SUPPPLIES Modals -->

    <!-- Aerosol Mask -->
    <div class="modal fade modal_adult_aerosol_mask_3" id="adult_aerosol_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Adult Aerosol Mask</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[156][209]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Adult Nasal Cannnula -->
    <div class="modal fade modal_adult_nasal_cannula_3" id="adult_nasal_cannula_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Adult Nasal Cannula</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[143][210]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Anti Tippers -->
    <div class="modal fade modal_anti_tippers_3" id="anti_tippers_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Anti Tippers</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[168][211]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!-- Circuit, Peep Valve, T Piece Adaptor -->
    <div class="modal fade modal_circuit_peep_valve_t_piece_adaptor_3" id="circuit_peep_valve_t_piece_adaptor_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Circuit, Peep Valve, T Piece Adaptor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[169][212]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Corrugated Tubing (7ft) -->
    <div class="modal fade modal_circuit_corrugated_tubing_7ft_3" id="corrugated_tubing_7ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Corrugated Tubing (7ft)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                 <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[163][213]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!-- E Cylinder Wrench -->
    <div class="modal fade modal_e_cylinder_wrench_3" id="e_cylinder_wrench_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">E Cylinder Wrench</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[141][214]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!-- Feeding Bags -->
    <div class="modal fade modal_feeding_bags_3" id="feeding_bags_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Feeding Bags</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[155][215]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--High Flow Nasal Cannula (6L & Higher) -->
    <div class="modal fade modal_high_flow_nasal_cannula_3" id="high_flow_nasal_cannula_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">High Flow Nasal Cannula (6L & Higher)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[144][216]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--High Flow O2 Humidifier Bottle -->
    <div class="modal fade modal_high_flow_o2_humidifier_bottle_3" id="high_flow_o2_humidifier_bottle_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">High Flow O2 Humidifier Bottle</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[145][217]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--Jet Nebulizers -->
    <div class="modal fade modal_jet_nebulizers_3" id="jet_nebulizers_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Jet Nebulizers</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[164][218]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

      <!--Nebulizer Kits (Mouthpiece) -->
    <div class="modal fade modal_nebulizer_kits_mouthpiece_3" id="nebulizer_kits_mouthpiece_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Nebulizer Kits (Mouthpiece)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[157][219]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--Non-Rebreather O2 Mask-->
    <div class="modal fade modal_non_rebreather_o2_mask_3" id="non_rebreather_o2_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Non-Rebreather O2 Mask</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[146][220]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--O2 Connector-->
    <div class="modal fade modal_o2_connector_3" id="o2_connector_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">O2 Connector</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[147][221]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--O2 Humidifier Bottle-->
    <div class="modal fade modal_o2_humidifier_bottle_3" id="o2_humidifier_bottle_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">O2 Humidifier Bottle</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[148][222]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--O2 Mask-->
    <div class="modal fade modal_o2_mask_3" id="o2_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">O2 Mask</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[149][223]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--O2 Tubing 21FT-->
    <div class="modal fade modal_o2_tubing_21ft_3" id="o2_tubing_21ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">O2 Tubing 21FT</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[150][224]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--O2 Tubing 7FT-->
    <div class="modal fade modal_o2_tubing_7ft_3" id="o2_tubing_7ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">O2 Tubing 7FT</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[151][225]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Pediatric Aerosol Mask-->
    <div class="modal fade modal_pediatric_aerosol_mask_3" id="pediatric_aerosol_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Pediatric Aerosol Mask</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[158][226]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Pediatric Nasal Cannula-->
    <div class="modal fade modal_pediatric_nasal_cannula_3" id="pediatric_nasal_cannula_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Pediatric Nasal Cannula</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[152][227]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Pressure Line Adaptor-->
    <div class="modal fade modal_pressure_line_adaptor_3" id="pressure_line_adaptor_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Pressure Line Adaptor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[166][228]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Seat Belt-->
    <div class="modal fade modal_seat_belt_3" id="seat_belt_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Seat Belt</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[167][229]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Suction Canister-->
    <div class="modal fade modal_suction_canister_3" id="suction_canister_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Suction Canister</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[159][230]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--Suction Tubing Long-->
    <div class="modal fade modal_suction_tubing_long_3" id="suction_tubing_long_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Suction Tubing Long</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[160][231]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Suction Tubing Short-->
    <div class="modal fade modal_suction_tubing_short_3" id="suction_tubing_short_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Suction Tubing Short</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[161][232]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Trach Mask-->
    <div class="modal fade modal_trach_mask_3" id="trach_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Trach Mask</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[165][233]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--Venturi Mask (Vent)-->
    <div class="modal fade modal_venturi_mask_vent_3" id="venturi_mask_vent_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Venturi Mask (Vent)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[153][234]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--X-Mas Tree Adaptor-->
    <div class="modal fade modal_x_mas_tree_adaptor_3" id="x_mas_tree_adaptor_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">X-Mas Tree Adaptor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[154][235]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

      <!--Y Connector-->
    <div class="modal fade modal_y_connector_3" id="y_connector_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Y Connector</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[142][236]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

      <!--Yankuer Suction Tubing-->
    <div class="modal fade modal_yankuer_suction_tubing_3" id="yankuer_suction_tubing_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Yankuer Suction Tubing</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[162][237]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script type="text/javascript">
  $(document).ready(function(){

    var cus_tbody = $('body .customer_ordered_items_tbody');
    var medical_record_id = cus_tbody.data('medical-id');
    var hospiceID = cus_tbody.data('hospice-id');
    var patientID = cus_tbody.data('patient-id');

    $.post(base_url+"order/get_customer_ordered_items/" + medical_record_id +"/"+ hospiceID +"/"+ patientID,"", function(response){
      cus_tbody.html(response);
    });

  });
</script>
