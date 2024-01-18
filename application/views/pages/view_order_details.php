
<?php 
  if(!empty($informations)) :
    $info = $informations[0];

    if(!empty($activity_fields)) :
      $fields = $activity_fields[0];
    endif;

    $organization_id = $this->session->userdata('group_id');
    $hospice_type = get_hospice_type($organization_id);
    if($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $hospice_type['type'] == 1 || $organization_id == 0)
    {
      $logged_in_account_type = "Company";
    }
    else
    {
      $logged_in_account_type = "Hospice";
    }
?>

<?php /*
<?php if(!empty($pickedup_date_info)):?>
    <?php $pickedup_date_info = $pickedup_date_info[0]; ?>
<?php endif;?>
*/?>

<strong>Entry Time</strong>
<p><?php echo date("F j, Y, g:i a", $info['uniqueID']) ?></p>

<strong>Work Order #</strong>
<p><?php echo substr($info['uniqueID'],4,10) ?></p>

<?php if($info['activity_typeid'] == 2) :?>
  <strong>Scheduled Pickup Date</strong>
  <?php if(strtotime($fields['date_pickedup']) == '') :?>
      <p><?php echo date("m/d/Y", strtotime($fields['pickup_date'])) ?></p>
  <?php else:?>
      <p><?php echo date("m/d/Y", strtotime($fields['date_pickedup'])) ?></p>
  <?php endif;?>
  <strong>Pickup Reason</strong>
  <p><?php echo ucfirst($fields['pickup_sub']) ?></p>
<?php endif;?>
 
<?php if($info['activity_typeid'] == 3) :?>
  <strong>Scheduled Exchange Order Date</strong>
  <p><?php echo date("m/d/Y", strtotime($fields['exchange_date'])) ?></p>
  <strong>Reason for Exchange</strong>
  <p><?php echo $fields['exchange_reason'] ?></p>
<?php endif;?>

<?php if($info['activity_typeid'] == 4) :?>
  <strong>CUS Move Scheduled Order Date</strong>
  <p><?php echo date("m/d/Y", strtotime($fields['ptmove_delivery_date'])) ?></p>
  <strong>CUS Move Address</strong>
  <p><?php echo $fields['ptmove_street'] ?>, <?php echo $fields['ptmove_placenum'] ?>, <?php echo $fields['ptmove_city'] ?>, <?php echo $fields['ptmove_state'] ?>, <?php echo $fields['ptmove_postal'] ?></p>
<?php endif;?>

<?php if($info['activity_typeid'] == 5) :?>
  <strong>Respite Scheduled Order Date</strong>
  <p><?php echo date("m/d/Y", strtotime($fields['respite_delivery_date'])) ?></p>
  <strong>Respite Pickup Date</strong>
  <p><?php echo date("m/d/Y", strtotime($fields['respite_pickup_date'])) ?></p>
  <strong>Respite Customer Residence</strong>
    <p>
      <?php echo $fields['respite_deliver_to_type'] ?>
    </p>
  <strong>Respite Address</strong>
  <p><?php echo $fields['respite_address'] ?>, <?php echo $fields['respite_placenum'] ?>, <?php echo $fields['respite_city'] ?>, <?php echo $fields['respite_state'] ?>, <?php echo $fields['respite_postal'] ?></p>
<?php endif;?>

<hr />

<div class="row">
    <div class="">
        <div class="col-md-6">
          <strong><?php echo $logged_in_account_type; ?> Provider</strong>
          <p><?php echo $info['hospice_name'] ?></p>

          <strong><?php echo $logged_in_account_type; ?> Staff Member Creating Order</strong>
          <?php if($info['who_ordered_lname'] == '' && $info['who_ordered_fname'] == '') :?>
            <p>NA</p>
          <?php else:?>
            <p><?php echo $info['who_ordered_fname']  ." ". $info['who_ordered_lname'] ?></p>
          <?php endif;?>

          <strong><?php echo $logged_in_account_type; ?> Staff Member Email Address</strong>
          <?php if($info['who_ordered_email'] == '') :?>
            <p>N/A</p>
          <?php else:?>
            <p><?php echo $info['who_ordered_email'] ?></p>
          <?php endif;?>
            
           <hr style="width:312px" />
           <!--  <strong>DME Staff Member Taken Order</strong>
            <?php if($info['staff_member_fname'] == '' && $info['staff_member_lname'] == '') :?>
              <p>NA</p>
            <?php else:?>
              <p><?php echo $info['staff_member_fname']  ." ". substr($info['staff_member_lname'],0,1) ."." ?></p>
            <?php endif;?> -->
            <?php
              $account_type = check_user_account_type($info['userID']);
            ?>

            <strong>DME Staff Member Taken Order</strong>
            <?php if($info['staff_member_fname'] == '' && $info['staff_member_lname'] == '') :?>
              <p>NA</p>
            <?php else:?>
              <?php if($account_type == 'hospice_user' || $account_type == 'hospice_admin') :?>
                <p>NA</p>
              <?php else:?> 
                <p><?php echo $info['staff_member_fname']  ." ". substr($info['staff_member_lname'],0,1) ."." ?></p>
              <?php endif;?>
            <?php endif;?>


             <strong>DME Staff Member Delivered Order</strong>
            <?php if($info['driver_name'] == '' && $info['driver_name'] == '') :?>
              <p style="text-transform:uppercase">NA</p>
            <?php else:?>
              <p style="text-transform:uppercase"><?php echo $info['driver_name'] ?></p>
            <?php endif;?>
            

            <hr style="width:310px" />

            <!-- <a href="javascript:void(0)" data-toggle="popover" class="view_editing_logs" data-patient-id="<?php echo $info['patientID'] ?>" style="color:red;">View Edit Logs</a><br/><br > -->

            <strong>Customer Medical Record #</strong>
            <p><?php echo $info['medical_record_id'] ?></p>

             <strong>Customer Name</strong>
            <p><?php echo $info['p_lname'].", ".$info['p_fname'] ?></p>

            <strong>Height(IN), Weight(LBS)</strong>
            <p><?php echo $info['p_height'] ?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <?php echo $info['p_weight'] ?></p>

            <?php 
              $patient_move_info = get_latest_patient_move_info($info['patientID']);
            ?>

            <strong>Relationship</strong>
            <?php
              if(!empty($patient_move_info)){ 
            ?>
                <p><?php echo $patient_move_info['ptmove_nextofkinrelation'] ?></p>
            <?php
              }else{
                if($info['p_relationship'] == '') :
            ?>
                  <p>N/A</p>
            <?php else:?>
                  <p><?php echo $info['p_relationship'] ?></p>
            <?php 
                endif;
              }
            ?>

            <strong>Customer Residence</strong>
            <?php 
              if(!empty($patient_move_info)){
            ?>
                <p><?php echo $patient_move_info['ptmove_patient_residence'] ?></p>
            <?php
                }else{
            ?>
                  <p><?php echo $info['deliver_to_type'] ?></p>
            <?php
              }
            ?>

            <strong>Customer Address</strong>
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
            <p><?php echo $equpment_location['street']." ".$equpment_location['placenum']." ".$equpment_location['city']." ".$equpment_location['state']." ".$equpment_location['postal_code'] ?></p>

            <?php 
            $response = get_items_for_pickup($info['medical_record_id'],$info['organization_id']);
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

            $patientID = get_patientID($info['medical_record_id'],$info['organization_id']);
            $patient_addresses = get_patient_addresses($patientID['patientID']);
            $new_response = array();
            $new_addresses_response = array();
            $data['addressID'] = array();

            foreach ($patient_addresses as $key=>$value) {
              $new_response_query = get_items_for_pickup_other_address($info['medical_record_id'],$info['organization_id'],$value['id']);
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
            <strong>No. of Location(s): </strong> 
            <br /><?php echo $no_of_addresses; ?>

          </div>
          <div class="col-md-6">
         
            <strong><?php echo $logged_in_account_type; ?> Phone No.</strong>
             <?php if($info['contact_num'] == '') :?>
              <p>N/A</p>
            <?php else:?>
              <p><?php echo $info['contact_num'] ?></p>
            <?php endif;?>

            <strong><?php echo $logged_in_account_type; ?> Staff Member Cell Phone</strong>
             <?php if($info['who_ordered_cpnum'] == '') :?>
              <p>N/A</p>
            <?php else:?>
              <p><?php echo $info['who_ordered_cpnum'] ?></p>
            <?php endif;?>

            <strong>Delivery Instructions</strong>
             <?php if($info['comment'] == '') :?>
                <p>N/A</p>
             <?php else:?>
                <p><?php echo $info['comment'] ?></p>
             <?php endif;?>

          <strong style="visibility:hidden">Divider</strong>
          <p style="visibility:hidden">Divider</p>

          <!-- <strong>Special Instructions</strong>
           <?php if($info['comment'] == '') :?>
              <p>N/A</p>
           <?php else:?>
              <p><?php echo $info['comment'] ?></p>
           <?php endif;?>
          
           <strong style="visibility:hidden">test</strong>
           <p style="visibility:hidden">NA</p> -->

           <hr />

             <strong>DME Staff Member Confirming Work Order</strong>
            <?php if($info['person_confirming_order'] == '' && $info['person_confirming_order'] == '') :?>
              <p style="text-transform:uppercase">NA</p>
            <?php else:?>
              <p style="text-transform:uppercase"><?php echo $info['person_confirming_order'] ?></p>
            <?php endif;?>

             <strong style="visibility:hidden">divider</strong>
            <p style="visibility:hidden">divider</p>

            <hr/>

            <strong style="visibility:hidden">Divider</strong>
            <p style="visibility:hidden">Divider</p>


           <strong>Gender</strong>
           <?php if($info['relationship_gender'] == 1) :?>
            <p>Male</p>
          <?php else:?>
            <p>Female</p>
          <?php endif;?>

          <strong>Next of Kin</strong>
          <?php 
            if(!empty($patient_move_info)){
          ?>
              <p><?php echo $patient_move_info['ptmove_nextofkin'] ?></p>
          <?php
            }else{
              if($info['p_nextofkin'] == ''):
          ?>
                <p>N/A</p>
          <?php else:?>
                <p><?php echo $info['p_nextofkin'] ?></p>
          <?php 
              endif;
            }
          ?>

          <strong>Next of Kin Phone No.</strong>
          <?php 
            if(!empty($patient_move_info)){
          ?>
              <p><?php echo $patient_move_info['ptmove_nextofkinphone'] ?></p>
          <?php
            }else{ 
              if($info['p_nextofkinnum'] == '') :
          ?>
                <p>N/A</p>
          <?php else:?>
                <p><?php echo $info['p_nextofkinnum'] ?></p>
          <?php 
              endif;
            }
          ?>

          <strong style="visibility:hidden">Divider</strong>
          <p style="visibility:hidden">Divider</p>

          <strong>Customer Alt. Phone</strong>
          <?php 
            if(!empty($patient_move_info)){
          ?>
              <p><?php echo $patient_move_info['ptmove_alt_patient_phone'] ?></p>
          <?php 
            }else{
              if($info['p_altphonenum'] == '') :
          ?>
                <p>N/A</p>
          <?php else:?>
                <p><?php echo $info['p_altphonenum'] ?></p>
          <?php 
              endif;
            }
          ?>

          <strong>Customer Phone</strong>
          <?php 
            if(!empty($patient_move_info)){
          ?>
              <p><?php echo $patient_move_info['ptmove_patient_phone'] ?></p>
          <?php 
            }else{
              if($info['p_phonenum'] == '') :
          ?>
                <p>N/A</p>
          <?php else: ?>
                <p><?php echo $info['p_phonenum']; ?></p>
          <?php 
              endif;
            }
          ?>
           
        </div>

<?php endif;?>
      

    <!--Settings newly added-->
      <div class="col-xs-12" style="margin-top:10px;">
        <?php 
          //get 02 Liter Flow
          $liter_flow = get_liter_flow($info['organization_id'],$info['medical_record_id']);
          
          //get the duration of the oxygen concentrator
          // $duration_cnt = get_duration_cnt($info['organization_id'],$info['medical_record_id']);
          // $duration_prn = get_duration_prn($info['organization_id'],$info['medical_record_id']);
          // $duration_ = "";

          // if(!empty($duration_cnt))
          // {
          //   $duration_ = get_equipment_name($duration_cnt['equipmentID']);
          // } else 
          // {
          //   $duration_ = get_equipment_name($duration_prn['equipmentID']);
          // }

          if($liter_flow['parentID'] != 61 || $liter_flow['parentID'] != 29 || $liter_flow['parentID'] != 36)
          {
            //get latest equipment ordered with those ID's from above and get the parent id
            $parentID = get_parent_id($info['organization_id'],$info['medical_record_id']);
            $liter_flow['parentID'] = $parentID['parentID'];
            $liter_flow['uniqueID'] = $parentID['uniqueID'];
          }
          $parentID_duration = get_parent_id_duration($info['organization_id'],$info['medical_record_id']);
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
          $IPAP = get_ipap($info['organization_id'],$info['medical_record_id']);
          $EPAP = get_epap($info['organization_id'],$info['medical_record_id']);
          $rate = get_rate($info['organization_id'],$info['medical_record_id']);
                    
          //get CIPAP SETTINGS
          $CIPAP = get_cipap($info['organization_id'],$info['medical_record_id']);
          if(!empty($liter_flow) || !empty($IPAP) || !empty($EPAP) || !empty($rate) || !empty($CIPAP))
          {
             if(!empty($liter_flow))
            { 
              if($liter_flow['parentID'] != "")
              {
                echo "<strong>Rx</strong>";  
              } 
              else if(!empty($IPAP) || !empty($EPAP) || !empty($rate) || !empty($CIPAP))
              {
                echo "<strong>Rx</strong>";
              }
            } 
        ?>
           
          <p>
            <?php if(!empty($liter_flow)){ 
               if($liter_flow['parentID'] != "")
                {
            ?>
            <label style="padding-right:30px;">O2 Liter Flow: &nbsp;&nbsp;
              <a href="javascript:;" id="equipment_value" data-pk="<?php echo $liter_flow['orderID'] ?>"  data-url="<?php echo base_url('order/update_data/number/orderID/1'); ?>"  data-title="Enter Liter Flow" data-value="<?php echo $liter_flow['equipment_value']; ?>" data-type="number" class="data_tooltip editable editable-click editable-noreload" ><strong class="under-line"> <?php echo $liter_flow['equipment_value']; ?> </strong></a>
              <?php if(!empty($duration_)) { ?>
              &nbsp;&nbsp;&nbsp;Duration <strong class="under-line"> <?php echo $duration_['key_desc']; ?> </strong>
              <?php } ?>
            </label>   
            <?php } }
              if(!empty($IPAP) && !empty($EPAP) && !empty($rate)){
            ?>
            <label style="padding-right:30px;">BIPAP Settings: &nbsp;IPAP <strong class="under-line"> <?php echo $IPAP['equipment_value']; ?> </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EPAP <strong class="under-line"> <?php echo $EPAP['equipment_value']; ?> </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RATE <strong class="under-line"> <?php echo $rate['equipment_value']; ?> </strong></label>  
            <?php } 
              if(!empty($CIPAP)){
            ?>
            <label style="padding-right:30px;">CPAP Settings: &nbsp;CMH20 <strong class="under-line"> <?php echo $CIPAP['equipment_value']; ?> </strong> </label> 
            <?php } ?>
          </p>
           <?php } ?>
      </div>






      <div class="col-md-12" style="">

        <hr />
        <h4>Ordered Item(s)</h4>
  <?php 
        if(!empty($equipments_ordered)):
          foreach($equipments_ordered as $equipment) :

            //for Oxygen Concentrator
              if($equipment['equipmentID'] == 316 || $equipment['equipmentID'] == 325 || $equipment['equipmentID'] == 334 || $equipment['equipmentID'] == 343)
              {
                if($equipment['type'] == 'Capped Item')
                {
                  echo '<strong><span class="text-info">'.$equipment['type'].'</span></strong>';
                  echo '<br/>';

                  if($equipment['canceled_order'] == 0)
                  {
                    $subequipment_id = get_oxygen_concentrator_sub_equipment($equipment['uniqueID'],$equipment['organization_id'],$equipment['equipmentID']);

                    echo '<strong><span>Oxygen Concentrator</span></strong><br />';
                    if($equipment['equipmentID'] == 316)
                    {
                      echo '<strong>Oxygen Concentrator Type:  5L</strong>';
                      echo "<br />";
                    } else {
                      echo '<strong>Oxygen Concentrator Type:  10L</strong>';
                      echo "<br />";
                    }

                    if($info['original_activity_typeid'] == 1 || $info['original_activity_typeid'] == 4 || $info['original_activity_typeid'] == 5)
                    {
                      $counter_sub = 0;
                      $duration_count = 0;
                      $delivery_device_count = 0;
                      $e_portable_count = 0;
                      foreach ($subequipment_id as $key => $value) 
                      {
                        //display for liter flow
                        if($value['equipmentID'] == 317 || $value['equipmentID'] == 326)
                        {
                          if($counter_sub == 0)
                          {
                            echo "<strong>Liter Flow: ".$value['equipment_value']." LPM</strong>";
                            echo '<br/>';
                            $counter_sub++;
                          }
                        }

                        //display for duration
                        if($value['equipmentID'] == 318 || $value['equipmentID'] == 327)
                        {
                          if($duration_count == 0)
                          {
                            echo "<strong>Duration: Continuous</strong>";
                            echo '<br/>';
                            $duration_count++;
                          }
                        }
                        else if($value['equipmentID'] == 319 || $value['equipmentID'] == 328)
                        {
                          if($duration_count == 0)
                          {
                            echo "<strong>Duration: PRN</strong>";
                            echo '<br/>';
                            $duration_count++;
                          }
                        }

                        //display for delivery device
                        if($value['equipmentID'] == 320 || $value['equipmentID'] == 329)
                        {
                          if($delivery_device_count == 0)
                          {
                            echo "<strong>Delivery Device: Nasal Cannula</strong>";
                            echo '<br/>';
                            $delivery_device_count++;
                          }
                        }
                        else if($value['equipmentID'] == 321 || $value['equipmentID'] == 330)
                        {
                          if($delivery_device_count == 0)
                          {
                            echo "<strong>Delivery Device: Oxygen Mask</strong>";
                            echo '<br/>';
                            $delivery_device_count++;
                          }
                        }
                        else if($value['equipmentID'] == 322 || $value['equipmentID'] == 331)
                        {
                          if($delivery_device_count == 0)
                          {
                            echo "<strong>Delivery Device: None</strong>";
                            echo '<br/>';
                            $delivery_device_count++;
                          }
                        }

                        //display for e portbale system
                        if($value['equipmentID'] == 323 || $value['equipmentID'] == 332)
                        {
                          if($e_portable_count == 0)
                          {
                            echo "<strong>E Portable System: Yes</strong>";
                            echo '<br/>';
                            $e_portable_count++;
                          }
                        }
                        else if($value['equipmentID'] == 324 || $value['equipmentID'] == 333)
                        {
                          if($e_portable_count == 0)
                          {
                            echo "<strong>E Portable System: No</strong>";
                            echo '<br/>';
                            $e_portable_count++;
                          }
                        }
                      }
                    }
                  }
                } 
                else if($equipment['type'] == 'Non-Capped Item')
                {
                  echo '<strong><span class="text-warning" style="color:#DAB506 !important">'.$equipment['type'].'</span></strong>';
                  echo '<br/>';

                  if($equipment['canceled_order'] == 0)
                  {
                    //check if naay sub equipment using equipment id, work uniqueId
                    $subequipment_id = get_oxygen_concentrator_sub_equipment($equipment['uniqueID'],$equipment['organization_id'],$equipment['equipmentID']);

                    echo '<strong><span>Oxygen Concentrator</span></strong><br />';
                   if($equipment['equipmentID'] == 334)
                    {
                      echo '<strong>Oxygen Concentrator Type:  5L</strong>';
                      echo "<br />";
                    } else {
                      echo '<strong>Oxygen Concentrator Type:  10L</strong>';
                      echo "<br />";
                    }

                    if($info['original_activity_typeid'] == 1 || $info['original_activity_typeid'] == 4 || $info['original_activity_typeid'] == 5)
                    {
                      $counter_sub = 0;
                      $duration_count = 0;
                      $delivery_device_count = 0;
                      $e_portable_count = 0;
                      foreach ($subequipment_id as $key => $value)
                      {
                        //display for liter flow 
                        if($value['equipmentID'] == 335 || $value['equipmentID'] == 344)
                        {
                          if($counter_sub == 0)
                          {
                            echo "<strong>Liter Flow: ".$value['equipment_value']." LPM</strong>";
                            echo '<br/>';
                            $counter_sub++;
                          }
                        }
                        
                        //display for duration
                        if($value['equipmentID'] == 336 || $value['equipmentID'] == 345)
                        {
                          if($duration_count == 0)
                          {
                            echo "<strong>Duration: Continuous</strong>";
                            echo '<br/>';
                            $duration_count++;
                          }
                        }
                        else if($value['equipmentID'] == 337 || $value['equipmentID'] == 346)
                        {
                          if($duration_count == 0)
                          {
                            echo "<strong>Duration: PRN</strong>";
                            echo '<br/>';
                            $duration_count++;
                          }
                        }

                        //display for delivery device
                        if($value['equipmentID'] == 338 || $value['equipmentID'] == 347)
                        {
                          if($delivery_device_count == 0)
                          {
                            echo "<strong>Delivery Device: Nasal Cannula</strong>";
                            echo '<br/>';
                            $delivery_device_count++;
                          }
                        }
                        else if($value['equipmentID'] == 339 || $value['equipmentID'] == 348)
                        {
                          if($delivery_device_count == 0)
                          {
                            echo "<strong>Delivery Device: Oxygen Mask</strong>";
                            echo '<br/>';
                            $delivery_device_count++;
                          }
                        }
                        else if($value['equipmentID'] == 340 || $value['equipmentID'] == 349)
                        {
                          if($delivery_device_count == 0)
                          {
                            echo "<strong>Delivery Device: None</strong>";
                            echo '<br/>';
                            $delivery_device_count++;
                          }
                        }
                        
                        //display for e portbale system
                        if($value['equipmentID'] == 341 || $value['equipmentID'] == 350)
                        {
                          if($e_portable_count == 0)
                          {
                            echo "<strong>E Portable System: Yes</strong>";
                            echo '<br/>';
                            $e_portable_count++;
                          }
                        }
                        else if($value['equipmentID'] == 342 || $value['equipmentID'] == 351)
                        {
                          if($e_portable_count == 0)
                          {
                            echo "<strong>E Portable System: No</strong>";
                            echo '<br/>';
                            $e_portable_count++;
                          }
                        }
                      }
                    }
                  }
                }
            }else{
              if($equipment['type'] == 'Capped Item') :
  ?>
                <strong><span class="text-info"><?php echo $equipment['type'] ?></span></strong><br />
  <?php 
              elseif($equipment['type'] == 'Non-Capped Item') :
  ?>
                <strong><span class="text-warning" style="color:#DAB506 !important"><?php echo $equipment['type'] ?></span></strong><br/>
  <?php 
              else:
                if($act_type_id == 2 && $equipment['type'] == 'Disposable Items') :
  ?>
                  <strong style="display:none"><span class="text-success"><?php echo $equipment['type'] ?></span></strong>
  <?php 
                else:
  ?>
                  <strong><span class="text-success"><?php echo $equipment['type'] ?></span></strong><br  />
  <?php 
                endif;
              endif;
              if($equipment['canceled_order'] == 0) :
                if($act_type_id == 2 && $equipment['type'] == 'Disposable Items') :
                  if($equipment['equipmentID'] == 55 || $equipment['equipmentID'] == 20){
  ?>
                    <strong style="display:none"><span><?php echo "Full Electric ".$equipment['key_desc'] ?></span></strong>
  <?php
                  }else{
  ?>
                    <strong style="display:none"><span><?php echo $equipment['key_desc'] ?></span></strong>
  <?php
                  }
                else:
                  if($equipment['equipmentID'] == 55 || $equipment['equipmentID'] == 20){
  ?>
                    <strong><span><?php echo "Full Electric ".$equipment['key_desc'] ?></span></strong><br />
  <?php
                  }else{
  ?>
                    <strong><span><?php echo $equipment['key_desc'] ?></span></strong><br />
  <?php 
                  }
                endif;

                //check if naay sub equipment using equipment id, work uniqueId
                $subequipment_id = get_subequipment_id($equipment['equipmentID']);

                //check if naay sub equipment using equipment id, uniqueId, and organization_id
                $equipment_subequipment = get_oxygen_concentrator_sub_equipment($equipment['uniqueID'],$equipment['organization_id'],$equipment['equipmentID']);

                //gets all the id's under the order
                if($subequipment_id)
                {
                  $count = 0;
                  foreach ($subequipment_id as $key) {
                    $value = get_equal_subequipment_order($key['equipmentID'], $equipment['uniqueID']);
                    if($value)
                    {
                      $non_capped_copy = get_non_capped_copy($equipment['equipmentID']);
                      $count++;
                      if($equipment['equipmentID'] == 32 || $equipment['equipmentID'] == 393)
                      {
                        echo "<strong><span>Type of Rack: ".$key['key_desc']."</span></strong><br/>";
                      }else
                      if($equipment['equipmentID'] == 55 || $equipment['equipmentID'] == 20 || $equipment['equipmentID'] == 19 || $equipment['equipmentID'] == 398)
                      {
                        echo "<strong><span>Type of Rails: ".$key['key_desc']."</span></strong><br/>";
                      } 
                      else if($equipment['equipmentID'] == 54 || $equipment['equipmentID'] == 17)
                      {
                        echo "<strong><span>Type of Geri Chair: ".$key['key_desc']."</span></strong><br/>";
                      }
                      else if($equipment['equipmentID'] == 49 || $equipment['equipmentID'] == 71 || $equipment['equipmentID'] == 64 || $equipment['equipmentID'] == 269)
                      {
                        if($count == 1)
                        {
                          echo "<strong><span>Type of Wheelchair: ".$key['key_desc']."</span></strong><br/>";
                        }else{
                          echo "<strong><span>Type of Legrest: ".$key['key_desc']."</span></strong><br/>";
                        }
                      }
                      else if($equipment['equipmentID'] == 39 || $equipment['equipmentID'] == 66)
                      {
                        echo "<strong><span>Type of Shower chair: ".$key['key_desc']."</span></strong><br/>";
                      }
                      else if($equipment['equipmentID'] == 36 || $non_capped_copy['noncapped_reference'] == 36)
                      {
                        if($count == 1)
                        {
                          //get liter flow equipment value
                          $value_of_liter_flow = get_value_of_liter_flow($key['equipmentID'],$equipment['uniqueID']);
                          echo "<strong><span>Liter Flow: ".$value_of_liter_flow['equipment_value']." LPM</span></strong><br/>";
                        }
                        else if($count == 2)
                        {
                          echo "<strong><span>Duration: ".$key['key_desc']."</span></strong><br/>";
                        }
                        else if($count == 3)
                        {
                          echo "<strong><span>Delivery Device: ".$key['key_desc']."</span></strong><br/>";
                        }
                      }
                      else if($equipment['equipmentID'] == 4 || $non_capped_copy['noncapped_reference'] == 4)
                      {
                        if($count == 1)
                        {
                          echo "<strong><span>".$key['key_desc'].": ".$equipment_subequipment[0]['equipment_value']."</span></strong><br/>";
                        }
                        else if($count == 2)
                        {
                          echo "<strong><span>".$key['key_desc'].": ".$equipment_subequipment[1]['equipment_value']."</span></strong><br/>";
                        }
                        else if($count == 3)
                        {
                          echo "<strong><span>".$key['key_desc'].": ".$equipment_subequipment[2]['equipment_value']."</span></strong><br/>";
                        }
                      }
                      else if($equipment['equipmentID'] == 9 || $non_capped_copy['noncapped_reference'] == 9)
                      {
                        echo "<strong><span>".$key['key_desc'].": ".$equipment_subequipment[0]['equipment_value']."</span></strong><br/>";
                      }
                      else if($non_capped_copy['noncapped_reference'] == 14)
                      {
                        $floor_mat_quantity = get_quantity_of_floor_mat($key['equipmentID'],$equipment['uniqueID']);
                        echo "<strong><span>Quantity: ".$floor_mat_quantity['equipment_value']."</span></strong><br/>";
                      }
                      else if($equipment['equipmentID'] == 16 || $non_capped_copy['noncapped_reference'] == 16)
                      {
                        echo "<strong><span>Gastric Drainage Type: ".$key['key_desc']."</span></strong><br/>";
                      }
                      else if($equipment['equipmentID'] == 30 || $non_capped_copy['noncapped_reference'] == 30)
                      {
                        if($count == 1)
                        {
                          //get liter flow equipment value
                          $value_of_liter_flow = get_value_of_liter_flow($key['equipmentID'],$equipment['uniqueID']);
                          echo "<strong><span>Liter Flow: ".$value_of_liter_flow['equipment_value']." LPM</span></strong><br/>";
                        }
                        else if($count == 2)
                        {
                          echo "<strong><span>Duration: ".$key['key_desc']."</span></strong><br/>";
                        }
                        else if($count == 3)
                        {
                          echo "<strong><span>Adapter Type: ".$key['key_desc']."</span></strong><br/>";
                        }
                      }
                      else if($equipment['equipmentID'] == 31 || $equipment['equipmentID'] == 62)
                      {
                        if($count == 1)
                        {
                          //get liter flow equipment value
                          $value_of_liter_flow = get_value_of_liter_flow($key['equipmentID'],$equipment['uniqueID']);
                          echo "<strong><span>Liter Flow: ".$value_of_liter_flow['equipment_value']." LPM</span></strong><br/>";
                        }
                        else if($count == 2)
                        {
                          echo "<strong><span>Type: ".$key['key_desc']."</span></strong><br/>";
                        }
                        else if($count == 3)
                        {
                          echo "<strong><span>Duration: ".$key['key_desc']."</span></strong><br/>";
                        }
                      }
                      else if($equipment['equipmentID'] == 174 || $equipment['equipmentID'] == 176)
                      {
                        if($count == 1)
                        {
                          //get liter flow equipment value
                          $value_of_liter_flow = get_value_of_liter_flow($key['equipmentID'],$equipment['uniqueID']);
                          echo "<strong><span>Liter Flow: ".$value_of_liter_flow['equipment_value']." LPM</span></strong><br/>";
                        }
                        else if($count == 2)
                        {
                          echo "<strong><span>Duration: ".$key['key_desc']."</span></strong><br/>";
                        }
                      }
                      else if($equipment['equipmentID'] == 179 || $non_capped_copy['noncapped_reference'] == 179)
                      {
                        echo "<strong><span>Duration: ".$key['key_desc']."</span></strong><br/>";
                      }
                      else if($equipment['equipmentID'] == 286 || $non_capped_copy['noncapped_reference'] == 286 || $equipment['equipmentID'] == 282 || $non_capped_copy['noncapped_reference'] == 282)
                      {
                        echo "<strong><span>Type of Rails: ".$key['key_desc']."</span></strong><br/>";
                      }
                      else if($equipment['equipmentID'] == 56 || $equipment['equipmentID'] == 21)
                      {
                        echo "<strong><span>Type of Sling: ".$key['key_desc']."</span></strong><br/>";
                      }
                      else if($equipment['equipmentID'] == 196)
                      {
                        echo "<strong><span>Type of Sling: ".$key['key_desc']."</span></strong><br/>";
                      }
                      else if($equipment['equipmentID'] == 353 || $non_capped_copy['noncapped_reference'] == 353)
                      {
                        echo "<strong><span>Type of Sling: ".$key['key_desc']."</span></strong><br/>";
                      }
                    }
                  }
                }
                if($equipment['equipmentID'] == 306 || $equipment['equipmentID'] == 309 || $equipment['equipmentID'] == 313)
                {
  ?>
                  <strong>
                    <span>
                      Item Description:
  <?php 
                      $samp =  get_misc_item_description($equipment['equipmentID'],$equipment['uniqueID']);
                      print_r($samp);
  ?>
                    </span>
                  </strong>
                  <br />
  <?php
                }
                if($equipment['categoryID'] == 2):
  ?>
                  <strong>
                    <span>
                      Quantity: 
  <?php 
                      if($equipment['equipmentID'] == 4 || $equipment['equipmentID'] == 30)
                      {
                        echo "1ea";
                      }
                      else if($equipment['equipment_value'] > 1)
                      {
                        echo $equipment['equipment_value']."ea";
                      }
                      else
                      {
                        if(get_noncapped_quantity($equipment['equipmentID'],$equipment['uniqueID']) == 0)
                        {
                          echo "1ea";
                        }
                        else if(get_noncapped_quantity($equipment['equipmentID'],$equipment['uniqueID']) > 0)
                        {
                          echo get_noncapped_quantity($equipment['equipmentID'],$equipment['uniqueID'])."ea";
                        }
                        else if($equipment['equipment_value'] == 1)
                        {
                          echo "1ea"; //newly added
                        }  
                      }
  ?>
                    </span>
                  </strong>
                  <br />
  <?php 
                endif; 
                if($equipment['equipmentID'] == 313 || $equipment['equipmentID'] == 206)
                {
  ?>
                  <strong>
                    <span>
                      Quantity: 
  <?php 
                      if(get_capped_quantity($equipment['equipmentID'],$equipment['uniqueID']) == 0)
                      {
                        echo "1ea";
                      }
                      else if(get_capped_quantity($equipment['equipmentID'],$equipment['uniqueID']) > 0)
                      {
                        echo get_capped_quantity($equipment['equipmentID'],$equipment['uniqueID'])."ea";
                      }
                      else if($equipment['equipment_value'] == 1)
                      {
                        echo "1ea"; //newly added
                      }  
  ?>
                    </span>
                  </strong>
                  <br />
  <?php 
                }
                if($equipment['categoryID'] == 3) :
                  if($act_type_id == 2 && $equipment['type'] == 'Disposable Items') :
  ?>
                    <strong style="display:none"><span><?php echo get_disposable_quantity_name($equipment['equipmentID']) ?>: <?php echo get_disposable_quantity($equipment['equipmentID']) ?>ea</span></strong>
  <?php 
                  else:
                    if(get_disposable_quantity($equipment['equipmentID'],$equipment['uniqueID']) == 0) :
  ?>
                      <strong><span><?php echo get_disposable_quantity_name($equipment['equipmentID']) ?>: 1ea</span></strong><br />
  <?php 
                    else:
  ?>
                      <strong><span><?php echo get_disposable_quantity_name($equipment['equipmentID']) ?>: <?php echo get_disposable_quantity($equipment['equipmentID'],$equipment['uniqueID']) ?>ea</span></strong><br />
  <?php 
                    endif;
                  endif;
                endif;
                if($equipment['serial_num'] != "") :
                  if($act_type_id == 2 && $equipment['type'] == 'Disposable Items') :
  ?>
                    <strong style="display:none"><span>Serial No. <?php echo $equipment['serial_num'] ?></span></strong>
  <?php 
                  else:
  ?>
                    <strong><span>Serial No. <?php echo $equipment['serial_num'] ?></span></strong><br /><br/>
  <?php 
                  endif;
                endif;
              else:
                if($act_type_id == 2 && $equipment['type'] == 'Disposable Items') :
                  if($equipment['equipmentID'] == 55 || $equipment['equipmentID'] == 20){
  ?>
                    <strong style="display:none"><span style="text-decoration:line-through"><?php echo "Full Electric ".$equipment['key_desc'] ?> </span></strong>
  <?php 
                  }else{
  ?>
                    <strong style="display:none"><span style="text-decoration:line-through"><?php echo $equipment['key_desc'] ?> </span></strong>
  <?php
                  } 
                else:
                  if($equipment['equipmentID'] == 55 || $equipment['equipmentID'] == 20){
  ?>
                    <strong><span style="text-decoration:line-through"><?php echo "Full Electric ".$equipment['key_desc'] ?> </span></strong><br />
  <?php 
                  }else{
  ?>
                    <strong><span style="text-decoration:line-through"><?php echo $equipment['key_desc'] ?> </span></strong><br />
  <?php 
                  }
                endif;
                if($equipment['categoryID'] == 3) :
                  if($act_type_id == 2 && $equipment['type'] == 'Disposable Items') :
  ?>
                    <strong style="display:none"><span style="text-decoration:line-through"><?php echo get_disposable_quantity_name($equipment['equipmentID']) ?>: <?php echo get_disposable_quantity($equipment['equipmentID']) ?>ea</span></strong>
  <?php 
                  else:
                    if(get_disposable_quantity($equipment['equipmentID'],$equipment['uniqueID']) == 0) :
  ?>
                      <strong><span><?php echo get_disposable_quantity_name($equipment['equipmentID']) ?>: 1ea</span></strong><br />
  <?php 
                    else:
  ?>
                      <strong><span><?php echo get_disposable_quantity_name($equipment['equipmentID']) ?>: <?php echo get_disposable_quantity($equipment['equipmentID'],$equipment['uniqueID']) ?>ea</span></strong><br />
  <?php 
                    endif;
                  endif;
                endif;
                if($equipment['serial_num'] != "") :
                  if($equipment['categoryID'] == 3) :
  ?>
                    <strong style="display:none"><span>Serial No. <?php echo $equipment['serial_num'] ?></span></strong>
  <?php 
                  else:
  ?>
                    <strong><span>Serial No. <?php echo $equipment['serial_num'] ?></span></strong><br/>
  <?php 
                  endif;
                endif;
              endif;
            }
          endforeach;
        endif;
  ?>
        </div>
        </div>
        
    </div>
</div>