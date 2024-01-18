<style type="text/css">
  .under-line {
  text-decoration: none;
  border-bottom: dashed 1px #0088cc;
      padding-left: 10px;
    padding-right: 10px;
}
</style>
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">

      <?php
        $organization_id = $this->session->userdata('group_id');
        if (!empty($informations)) :  
          $information = $informations[0];  

          /** This is the function to disable PT Move/Respite when there are still items to pickup from previous activity type **/  

          $data_array = check_if_we_can_do_another_ptmove($information['medical_record_id'],$information['patientID']);
          
          $has_ptmove_order = in_multiarray(4, $data_array, "activity_typeid"); //return 1 if it has a pt move activity type = 4
          $needs_to_pickup_first = in_multiarray(1, $data_array, "activity_typeid"); //return 1 kung naay delivery nga need i pickup before muhimo ug pt move nga bag-o = 1
          $has_respite_order = in_multiarray(5, $data_array, "activity_typeid");

          $disable_ptmove = "";

          if($has_ptmove_order && $needs_to_pickup_first)
          {
            $disable_ptmove = "disabled";
            $title = "Pickup the existing items first before you can do another PT Move.";
          }

          if($has_respite_order && $needs_to_pickup_first)
          {
            $disable_respite = "disabled";
            $title = "Pickup the existing items first before you can do another Respite.";
          }

          //function that will check if there are still initial orders that needs to be confirmed
          $order_details = check_intial_orders_to_confirm($information['medical_record_id'],$information['patientID']);

          //if pending is the order status
          $has_pending = in_multiarray("pending",$order_details,"order_status");
          $has_tobeconfirmed = in_multiarray("tobe_confirmed",$order_details,"order_status");
          $has_active = in_multiarray("active",$order_details,"order_status");
          $has_onhold = in_multiarray("on-hold",$order_details,"order_status");

          $is_intial_order = in_multiarray(1,$order_details,"initial_order");

          $disable_other_act_type = "";

          if($has_pending && $is_intial_order)
          {
            $disable_other_act_type = "disabled";
            //$disable_title = "Confirm the initial order first.";
          }

          if($has_tobeconfirmed && $is_intial_order)
          {
            $disable_other_act_type = "disabled";
            //$disable_title = "Confirm the initial order first.";
          }

          if($has_active && $is_intial_order)
          {
            $disable_other_act_type = "disabled";
            //$disable_title = "Confirm the initial order first.";
          }

          if($has_onhold && $is_intial_order)
          {
            $disable_other_act_type = "disabled";
            //$disable_title = "Confirm the initial order first.";
          }
      ?>
          <div class="row pull-right" style="margin-bottom:20px;">
            <?php $patientID = $information['medical_record_id'] ?>
            <?php $groupID = $this->session->userdata('group_id') ?>
            <?php 
              if(!empty($information['organization_id']))
              {
                $hospice_id = $information['organization_id'];
              }else{
                $hospice_id = $information['ordered_by'];
              } 
            ?>
            <a class="data_tooltip" title="Return to Patient Profile" href="<?php echo base_url('order/patient_profile/'.$patientID.'/'.$hospice_id)?>" style="margin-right:15px"><button type="button" class="btn btn-info"><span class="fa fa-arrow-left"></span> Return</button></a>
          </div>
      <?php
          $capped_items = check_patient_capped_items($information['medical_record_id'],$information['patientID']);

          foreach($capped_items as $capped)
          {
            echo "<input type='hidden' class='capped_items' name='ordered_capped_items[]' value='".$capped['equipmentID']."' />";
          }
      ?>
          <input type="hidden" id="selected" name="selected" />
          <input type="hidden" id="selected_activity_type" name="" value="1" />

          <h4>Patient Medical Record # <?php echo $information['medical_record_id']?></h4>
          <h4>Patient Name: <?php echo $information['p_lname'].", ".$information['p_fname'] ?></h4>
          <h5>Hospice Provider: <?php echo $information['hospice_name'] ?></h5>
        <!-- Settings commented because I was ask to hide it-->
          <!-- <h5>          
              <?php //get 02 Liter Flow
                    // $liter_flow = get_liter_flow($information['organization_id'],$information['medical_record_id']);
                    
                    // //get BIPAP SETTINGS
                    // $IPAP = get_ipap($information['organization_id'],$information['medical_record_id']);
                    // $EPAP = get_epap($information['organization_id'],$information['medical_record_id']);
                    // $rate = get_rate($information['organization_id'],$information['medical_record_id']);
                    
                    // //get CIPAP SETTINGS
                    // $CIPAP = get_cipap($information['organization_id'],$information['medical_record_id']);
                    // //get O2 LR Settiings:
                    // $O2LR = get_o2lr($information['organization_id'],$information['medical_record_id']);
              ?>
              <p>
                <?php if(!empty($liter_flow)){ ?>
                <label style="padding-right:30px;">O2 Liter Flow: &nbsp;&nbsp;<strong class="under-line"> <?php echo $liter_flow['equipment_value']; ?> </strong></label>   
                <?php } 
                  if(!empty($IPAP) && !empty($EPAP) && !empty($rate)){
                ?>
                <label style="padding-right:30px;">BIPAP Settings: &nbsp;IPAP <strong class="under-line"> <?php echo $IPAP['equipment_value']; ?> </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EPAP <strong class="under-line"> <?php echo $EPAP['equipment_value']; ?> </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RATE <strong class="under-line"> <?php echo $rate['equipment_value']; ?> </strong></label>  
                <?php } 
                  if(!empty($CIPAP)){
                ?>
                <label style="padding-right:30px;">CPAP Settings: &nbsp;CMH20 <strong class="under-line"> <?php echo $CIPAP['equipment_value']; ?> </strong> </label> 
                <?php } 
                  if(!empty($O2LR)){
                ?>
                <label style="padding-right:30px;">O2 LR Settings: &nbsp;&nbsp;<strong class="under-line"> <?php echo $O2LR['equipment_value']; ?> </strong></label>
                <?php } ?>
              </p>
          </h5> -->

          <?php echo form_open("",array("id"=>"add_additional_equipment_form")) ;?>

            <div class="panel panel-default">
              <div class="panel-heading font-bold clearfix">
                <div class="pull-left">Activity Process</div> 
                <div class="pull-right">
                  <div class="pull-right">
                    
                    <?php
                      $activity_counts  = array();
                      $label            = array(1=>"Delivery",3=>"Exchange",2=>"Pickup",4=>"PT Move",5=>"Respite");
                      for($i=1;$i<=5;$i++)
                      {
                        $activity_counts[] = get_count_status("",array(
                                                  "stats.medical_record_id"       => $information['medical_record_id'],
                                                  "stats.status_activity_typeid"  => $i,
                                                  "(orders.order_status != 'confirmed' AND  orders.order_status != 'cancel')"         => false
                                              ));
                        $activities[] = get_status("",array(
                                                "stats.medical_record_id"       => $information['medical_record_id'],
                                                "stats.status_activity_typeid"  => $i,
                                                "(orders.order_status != 'confirmed' AND  orders.order_status != 'cancel')"         => false
                        ));
                      }
                      $index=0;
                    ?>
                      <ul class="status-count" style="list-style-type:none;">
                        <?php 
                          $count = 0;
                          foreach($activity_counts as $key=>$value): 
                            $count_inside = 0;
                            foreach ($activities as $act){
                              $another_count = 0;
                              if($count == $count_inside){
                                if($value>0): 
                        ?>
                                  <li class="pull-left">
                                    <span>
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
                                            $ptmove_addresses_ID = get_ptmove_addresses_ID($act[$another_count]['patientID']);
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
                                              echo "Delivery (PT Move)";
                                            }
                                            else
                                            {
                                              echo "Delivery (PT Move ".$address_sequence.")";
                                            }
                                          }
                                          else 
                                          {
                                            $respite_addresses_ID = get_respite_addresses_ID($act[$another_count]['patientID']);
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
                                            $ptmove_addresses_ID = get_ptmove_addresses_ID($act[$another_count]['patientID']);
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
                                              echo "Exchange (PT Move)";
                                            }
                                            else
                                            {
                                              echo "Exchange (PT Move ".$address_sequence.")";
                                            }
                                          }
                                          else 
                                          {
                                            $respite_addresses_ID = get_respite_addresses_ID($act[$another_count]['patientID']);
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
                                        else if(($label[$key+1]) == "PT Move")
                                        {
                                          $ptmove_addresses_ID = get_ptmove_addresses_ID($act[$another_count]['patientID']);
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
                                            echo "PT Move";
                                          }
                                          else
                                          {
                                            echo "PT Move ".$address_sequence;
                                          }
                                        }
                                        else if(($label[$key+1]) == "Respite")
                                        {
                                          $respite_addresses_ID = get_respite_addresses_ID($act[$another_count]['patientID']);
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
                                            $ptmove_addresses_ID = get_ptmove_addresses_ID($act[$another_count]['patientID']);
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
                                              echo "Pickup (PT Move)";
                                            }
                                            else
                                            {
                                              echo "Pickup (PT Move ".$address_sequence.")";
                                            }
                                          }
                                          else 
                                          {
                                            $respite_addresses_ID = get_respite_addresses_ID($act[$another_count]['patientID']);
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
                                    </span>&nbsp;
                                    <span><strong><?php echo $value; ?></strong></span>
                                  </li>
                        <?php 
                                endif;  
                              }
                              $count_inside++; 
                            } 
                            $index++;
                            $count++;
                          endforeach; 
                        ?>
                      </ul>
                  </div>
                </div>
              </div> <!-- .panel-heading font-bold clearfix -->

              <div class="panel-body">
                <form role="form">
                  <div class="col-sm-6">

                    <?php 
                      if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :
                    ?>
                        <div class="form-group pull-in clearfix">
                          <div class="col-sm-6">
                            <label>DME Staff Member Taken Order <span class="text-danger-dker">*</span></label>
                  
                            <!--  Newly Added -->
                            <?php $fname = $this->session->userdata('firstname'); ?>
                            <?php $lname = $this->session->userdata('lastname'); ?>
                            <?php $lname_cut = substr($this->session->userdata('lastname'),0,1); ?>
                  
                            <span name="" class="form-control"><?php echo $fname." ".$lname_cut."." ?></span>
                            <input type="hidden" value="<?php echo $fname ?>" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="staff_member_fname"  autocomplete="off" placeholder="First Name" tabindex="1">
                          </div>
                          <div class="col-sm-6" style="margin-top:5px">
                            <label> </label>
                            <input type="hidden" value="<?php echo $lname ?>" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="staff_member_lname"  autocomplete="off" placeholder="Last Name" tabindex="2">
                          </div>
                        </div> <!-- .form-group pull-in clearfix-->
                    <?php else :?>
                        <input type="hidden" style="margin-top: 5px;" class="" name="staff_member_fname"  autocomplete="off" value="NA">
                        <input type="hidden" style="margin-top: 5px;" class="" name="staff_member_lname"  autocomplete="off" value="NA">
                    <?php 
                      endif;
                      if($this->session->userdata('account_type') != 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :
                    ?>
                        <div class="form-group pull-in clearfix">
                          <div class="col-sm-6">
                            <label>Hospice Staff Member Creating Order <span class="text-danger-dker">*</span></label>
                            <input type="text" value="<?php echo $this->session->userdata('firstname') ?>" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_fname"  autocomplete="off" placeholder="Staff First Name" tabindex="4">
                          </div>
                          <div class="col-sm-6" style="margin-top:5px">
                            <label> </label>
                            <input type="text" value="<?php echo $this->session->userdata('lastname') ?>" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_lname"  autocomplete="off" placeholder="Staff Last Name" tabindex="5">
                          </div>
                        </div>
                    <?php else:?>
                        <div class="form-group pull-in clearfix">
                          <div class="col-sm-6">
                            <label>Hospice Staff Member Creating Order <span class="text-danger-dker">*</span></label>
                            <input type="text" value="" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_fname"  autocomplete="off" placeholder="Staff First Name" tabindex="4">
                          </div>
                          <div class="col-sm-6" style="margin-top:5px">
                            <label> </label>
                            <input type="text" value="" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_lname"  autocomplete="off" placeholder="Staff Last Name" tabindex="5">
                          </div>
                        </div>
                    <?php 
                      endif;
                      $hospice_person_mobile = get_person_mobile_num($this->session->userdata('userID')); 
                    ?>

                      <div class="form-group pull-in clearfix">
                        <div class="col-sm-6">
                          <label>Hospice Staff Member Cellphone No. <span class="text-danger-dker">*</span></label>
                          <?php if($this->session->userdata('account_type') == 'dme_admin') :?>
                            <input type="text" style="" class="form-control person_num" name="who_ordered_cpnum"  autocomplete="off" placeholder="Staff Cellphone No." tabindex="6">
                          <?php else:?>
                            <input type="text" style="" class="form-control person_num" name="who_ordered_cpnum"  autocomplete="off" placeholder="Staff Cellphone No." tabindex="6" value="<?php echo $hospice_person_mobile ?>">
                          <?php endif;?>
                        </div> <!-- col-sm-6 -->

                        <?php 
                          $email = $this->session->userdata('email'); 
                        ?>
                        <div class="col-sm-6">
                          <label>Hospice Staff Member Email Address <span class="text-danger-dker">*</span></label>
                          <?php $email = $this->session->userdata('email'); ?>
                          <?php if ($this->session->userdata('account_type') == 'dme_admin') : ?>
                             <input type="email" class="form-control"  name="who_ordered_email" value="" tabindex="7" style="text-transform:none !important" placeholder="STAFF EMAIL ADDRESS">
                          <?php else : ?>
                             <input type="text" class="form-control" name="who_ordered_email"  value="<?php echo $email ?>" readonly tabindex="7" style="text-transform:none !important" placeholder="Staff Email Address">
                          <?php endif; ?>
                        </div><!-- col-sm-6 -->
                      </div> <!-- .form-group pull-in clearfix -->

                      <div class="form-group">
                        <div class="col-sm-4">
                          <label  style="margin-left:-13px">Activity Type <span class="text-danger-dker">*</span></label>
                          <div class="radio">
                            <label class="i-checks">
                              <input type="radio" name="activity_type" id="radio_pickup2" value="1" class="radio_act_type" checked="checked" ><i></i>Add New Item(s)
                            </label>
                          </div>
                          <div class="radio data_tooltip" title="" >
                            <label class="i-checks">
                              <input type="radio" name="activity_type" id="radio_pickup4" value="4" class="radio_act_type data_tooltip" <?php //echo $disable_other_act_type ?> ><i></i>Add PT Move
                            </label>
                          </div>
                        </div>
                        <div class="col-sm-4" style="margin-top:5px">
                          <label> </label>
                          <div class="radio data_tooltip" title="">
                            <label class="i-checks">
                              <input type="radio" name="activity_type" id="radio_pickup3" value="3" class="radio_act_type" <?php //echo $disable_other_act_type ?> ><i></i>Exchange Item(s)
                            </label>
                          </div>
                          <div class="radio data_tooltip" title="">
                            <label class="i-checks">
                              <input type="radio" name="activity_type" id="radio_pickup5" value="5" class="radio_act_type" <?php //echo $disable_other_act_type ?> ><i></i>Add Respite
                            </label>
                          </div>

                        </div>
                        <div class="col-sm-4" style="margin-top:27px">
                          <div class="radio data_tooltip" title="">
                            <label class="i-checks">
                              <input type="radio" name="activity_type" id="radio_pickup2" value="2" class="radio_act_type" <?php //echo $disable_other_act_type ?> ><i></i>Pickup Item(s)
                            </label>
                          </div>
                        </div>
                      </div> <!-- .form-group -->

                      <div class="col-sm-12" style="margin-left: -21px">
                        <div class="form-group" style="" id="fordelivery_categories">
                          <label> Delivery Date <span class="text-danger-dker">*</span></label>
                          <input type="text" class="form-control datepicker" value="" placeholder="Date" name="delivery_date" style="">
                        </div>
                      </div>

                      <?php 
                        /* 
                          0=default address, 1=ptmove, 2=respite
                        */
                        $old_address = get_old_address_new($information['patientID']);
                        $patient_move_address = get_patient_move_address_new_v2($information['patientID']);
                        $respite_address = get_respite_address_new_v2($information['patientID']);
                        if(empty($patient_move_address) && empty($respite_address)){
                      ?>
                          <input type="hidden" name="delivery_address" value="<?php echo $old_address['id'] ?>">
                     
                      <?php }else{ ?>
                          <div class="col-sm-12" style="margin-left: -21px">
                            <div class="form-group" style="" id="fordelivery_categories2">
                              <label> Delivery Address <span class="text-danger-dker">*</span></label>
                              <select name="delivery_address" class="form-control m-b" tabindex="9" style="margin-left:5px"> 
                                  <option value="">[-- Select Patient Address --]</option>
                                <?php 
                                  if($old_address){
                                ?>
                                    <optgroup label="Initial Address">
                                      <option value="<?php echo $old_address['id'] ?>"><?php echo $old_address['street'] .", ". $old_address['placenum'] .", ". $old_address['city'] .", ". $old_address['state'] .", ". $old_address['postal_code'] ?></option>
                                    </optgroup>
                                <?php 
                                  }
                                  if($patient_move_address){ 
                                ?>
                                    <optgroup label="Patient Move Address">
                                <?php 
                                      foreach ($patient_move_address as $patient_move_row) {
                                ?>
                                        <option value="<?php echo $patient_move_row['id'] ?>"><?php echo $patient_move_row['street'] .", ". $patient_move_row['placenum'] .", ". $patient_move_row['city'] .", ". $patient_move_row['state'] .", ". $patient_move_row['postal_code'] ?></option>
                                <?php
                                    } 
                                ?>
                                    </optgroup> 
                                <?php
                                  }
                                  if($respite_address){ 
                                ?>
                                    <optgroup label="Respite Address">
                                <?php
                                      foreach ($respite_address as $respite_row) {
                                ?>
                                        <option value="<?php echo $respite_row['id'] ?>"><?php echo $respite_row['street'] .", ". $respite_row['placenum'] .", ". $respite_row['city'] .", ". $respite_row['state'] .", ". $respite_row['postal_code'] ?></option>
                                <?php 
                                    }
                                ?>
                                    </optgroup> 
                                <?php
                                  }
                                ?>
                              </select>
                            </div>
                          </div> <!-- .col-sm-12 -->
                      <?php } ?>

                      <div class="col-sm-12" style="margin-left: -21px">
                        <div class="form-group" style="display:none;" id="forptmove_categories">
                          <label>PT Move Delivery Date <span class="text-danger-dker">*</span></label>
                          <input type="text" class="form-control datepicker" placeholder="Date" name="ptmove_delivery_date" tabindex="8">
                        </div>
                      </div> <!-- .col-sm-12 -->

                      <div class="col-sm-12" style="margin-left: -21px">
                        <div class="form-group" style="display:none;" id="forptmove_categories2">
                          <label>Patient Residence <span class="text-danger-dker">*</span></label>
                          <select  name="ptmove_patient_residence"  class="form-control m-b" tabindex="9" style="margin-left:5px"> 
                            <option value="">[-- Select Residence --]</option>
                            <option value="Home Care">Home Care</option>
                            <option value="Group Home">Group Home</option>
                            <option value="Assisted Living">Assisted Living</option> 
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option> 
                            <option value="Hic Home">Hic Home</option> 
                          </select>
                          <label>Patient Phone Number <span class="text-danger-dker">*</span></label>
                          <input type="text" class="form-control person_num" name="ptmove_patient_phone" style="margin-left:5px" tabindex="10" />
                        </div>
                      </div> <!-- .col-sm-12 -->

                      <div class="col-sm-12" style="margin-top:10px;margin-left: -16px;"> 
                        <div class="form-group" style="display:none;" id="forptmove_categories3">
                          <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">New Patient Address <span style="color:red;">*</span></label>
                          <input type="text" class="form-control pickup_sub" id="" value="" placeholder="Street Address" name="ptmove_address" style="margin-bottom:20px;" tabindex="11">
                          <input type="text" class="form-control pickup_sub" id="" value="" placeholder="Apartment #, Room # , Unit #" name="ptmove_placenum" style="margin-bottom:20px;" tabindex="12">
                          <div class="col-sm-6" style="padding-left:0px;">
                            <input type="text" class="form-control pickup_sub" id="city_ptmove" value="" placeholder="City" name="ptmove_city" style="margin-bottom:20px;" tabindex="13">
                          </div>
                          <div class="col-sm-6" style="padding-right:0px;">
                            <input type="text" class="form-control pickup_sub" id="state_ptmove" value="" placeholder="State / Province" name="ptmove_state" style="margin-bottom:20px;" tabindex="14">
                          </div>
                          <div class="col-sm-5" style="padding-left:0px;">
                            <input type="text" class="form-control pickup_sub" id="postalcode_ptmove" value="" placeholder="Postal Code" name="ptmove_postalcode" style="margin-bottom:20px;" tabindex="15">
                          </div>
                        </div>
                      </div> <!-- .col-sm-12 -->


                      <div class="col-sm-12" style="margin-left: -16px;">
                        <div class="form-group " style="display:none;" id="forrespite_categories">
                          <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:2px;">Respite Delivery Date <span style="color:red;">*</span></label>
                          <input type="text" class="form-control datepicker pickup_sub" id="" value=""  placeholder="Delivery Date" name="respite_delivery_date" style="margin-bottom:20px;margin:0px !important;" tabindex="8">
                        </div>
                      </div>

                      <div class="col-sm-12" style="margin-left: -16px;">
                        <div class="form-group " style="display:none;" id="forrespite_categories2">
                          <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:2px;">Respite Pickup Date <span style="color:red;">*</span></label>
                          <input type="text" class="form-control datepicker pickup_sub" id="" value=""  placeholder="Pickup Date" name="respite_pickup_date" style="margin-bottom:20px;margin:0px !important;" tabindex="9">
                        </div>
                      </div>

                      <div class="col-sm-12" style="margin-left: -16px;">
                        <div class="form-group " style="display:none;" id="forrespite_categories3">
                          <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:2px;">Respite Phone Number <span style="color:red;">*</span></label>
                          <input type="text" class="form-control person_num pickup_sub" id="" value=""  placeholder="Phone Number" name="respite_phone_number" style="margin-bottom:20px;margin:0px !important;" tabindex="10">
                        </div>
                      </div>

                      <div class="col-sm-12" style="margin-left: -16px;">
                        <div class="form-group " style="display:none;" id="forrespite_categories4">
                          <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:2px;">Patient Residence <span style="color:red;">*</span></label>
                          <select  name="dropdown_deliver_type"  class="form-control m-b" style="" tabindex="11">
                            <option value="">[-- Select Residence --]</option>
                            <option value="Home Care">Home Care</option>
                            <option value="Group Home">Group Home</option>
                            <option value="Assisted Living">Assisted Living</option> 
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option> 
                            <option value="Hic Home">Hic Home</option> 
                          </select>    
                        </div>
                      </div>

                      <div class="col-sm-12" style="margin-left: -16px;">
                        <div class="form-group " style="display:none;" id="forrespite_categories5">
                          <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Respite Address <span style="color:red;">*</span></label>
                          <input type="text" class="form-control pickup_sub " id="" value="" placeholder="Street Address" name="respite_address" style="margin-bottom:20px;" tabindex="12">
                          <input type="text" class="form-control pickup_sub " id="" value="" placeholder="Apartment #, Room # , Unit #" name="respite_placenum" style="margin-bottom:20px;" tabindex="13">
                          <div class="col-sm-6" style="padding-left:0px;">
                              <input type="text" class="form-control pickup_sub " id="city_respite" value="" placeholder="City" name="respite_city" style="margin-bottom:20px;" tabindex="14">
                          </div>
                          <div class="col-sm-6" style="padding-right:0px;">
                              <input type="text" class="form-control  pickup_sub" id="state_respite" value="" placeholder="State / Province" name="respite_state" style="margin-bottom:20px;" tabindex="15">
                          </div>
                          <div class="col-sm-5" style="padding-left:0px;">
                              <input type="text" class="form-control pickup_sub " id="postalcode_respite" value="" placeholder="Postal Code" name="respite_postalcode" style="margin-bottom:20px;" tabindex="16">
                          </div>
                        </div>
                      </div>  


                      <!--********************************* 
                        (marj) Pick up code starts here. 
                      *********************************-->
                      <div class="col-sm-12" style="margin-left: -12px;margin-top:10px;width:110%;">
                        <div class="form-group" style="display:none;" id="forpickup_categories3">
                          <div class="panel panel-default ng-scope">
                            <div class="panel-heading">
                              Item(s) to Pickup
                            </div>
                            <div class="panel-body">

                              <div class="row">
                                <div class="col-sm-12" style="">
                                  <div class="form-group" style="display:none; margin-right:2px;" id="forpickup_categories">
                                    <label>Pickup Reason <span class="text-danger-dker">*</span></label>
                                    <select class="form-control pickup-reason select_pickup_reason" name="pickup_sub_cat" style="">
                                      <!-- <option value="">[--Please select reason --]</option> -->
                                      <?php $address = $ptmove_address[0] ?>
                                        <?php if(!empty($display_old_address)) :?>
                                            <option value="not needed">Not Needed</option> <!-- Make this default when respite pickup soon -->
                                            <option value="expired" >Expired</option>
                                            <option value="discharged">Discharged</option>
                                            <option value="revoked" >Revoked</option>

                                        <?php else:?>
                                            <option value="">[= Choose Reason =]</option>
                                            <option value="not needed">Not Needed</option> <!-- Make this default when respite pickup soon -->
                                            <option value="expired" >Expired</option>
                                            <option value="discharged">Discharged</option>
                                            <option value="revoked" >Revoked</option>
                                      <?php endif;?>
                                    </select>
                                  </div>
                                </div> <!-- .col-sm-12 -->

                                <div class="col-sm-12" style="margin-left:-7px;">
                                  <div class="form-group" style="display:none; margin-right:0px;" id="forpickup_categories2">
                                    <label style="margin-left:5px;"> Pickup Date <span class="text-danger-dker">*</span></label>
                                    <input type="text" class="form-control datepicker" value="" placeholder="Date" name="pickup_pickup_date" style="">
                                  </div>
                                </div>

                                <?php 
                                  /* 
                                    0=default address, 1=ptmove, 2=respite
                                  */
                                  $old_address = get_old_address_new($information['patientID']);
                                  $patient_move_address = get_patient_move_address_new_v2($information['patientID']);
                                  $respite_address = get_respite_address_new_v2($information['patientID']);
                                  if(empty($patient_move_address) && empty($respite_address)){
                                ?>
                                    <input type="hidden" name="pickup_address" value="<?php echo $old_address['id'] ?>">
                                    <input type="hidden" class="multiple_address_sign" value="0">

                                <?php }else{ ?>

                                    <div class="col-sm-12" id="address_to_pickup">
                                      <div class="col-sm-12" style="margin-left: -22px">
                                        <div class="form-group" style="margin-right:-30px;" id="forpickup_categories5">
                                          <label style="margin-left:7px;"> Address to Pick up <span class="text-danger-dker">*</span></label>
                                          <select  name="pickup_address" class="form-control m-b" id="select_pickup_address" tabindex="9" style="margin-left:5px"> 
                                            <option >[-- Select Patient Address --]</option>
                                            <?php 
                                              if(!empty($old_address)) {
                                            ?>
                                                <optgroup label="Initial Address">
                                                  <option value="<?php echo $old_address['id'] ?>"><?php echo $old_address['street'] .", ". $old_address['placenum'] .", ". $old_address['city'] .", ". $old_address['state'] .", ". $old_address['postal_code'] ?></option>
                                                </optgroup>
                                            <?php 
                                              }
                                              if($patient_move_address){ 
                                            ?>
                                                <optgroup label="Patient Move Address">
                                            <?php 
                                                  foreach ($patient_move_address as $patient_move_row) {
                                            ?>
                                                    <option value="<?php echo $patient_move_row['id'] ?>"><?php echo $patient_move_row['street'] .", ". $patient_move_row['placenum'] .", ". $patient_move_row['city'] .", ". $patient_move_row['state'] .", ". $patient_move_row['postal_code'] ?></option>
                                            <?php
                                                } 
                                            ?>
                                                </optgroup> 
                                            <?php
                                              }
                                              if($respite_address){ 
                                            ?>
                                                <optgroup label="Respite Address">
                                            <?php
                                                  foreach ($respite_address as $respite_row) {
                                            ?>
                                                    <option value="<?php echo $respite_row['id'] ?>"><?php echo $respite_row['street'] .", ". $respite_row['placenum'] .", ". $respite_row['city'] .", ". $respite_row['state'] .", ". $respite_row['postal_code'] ?></option>
                                            <?php 
                                                }
                                            ?>
                                                </optgroup> 
                                            <?php
                                              }
                                            ?>
                                          </select>
                                        </div> <!-- #fordelivery_categories2 -->
                                      </div> <!-- .col-sm-12 -->
                                    </div> <!-- .col-sm-12 -->
                                    <input type="hidden" class="multiple_address_sign" value="1">

                                <?php } ?>

                                <div class="col-sm-12 address_equipment_col" <?php if(!empty($patient_move_address) || !empty($respite_address)){ ?> style="display:none;" <?php } ?> id="pickup_div_<?php echo $old_address['id']; ?>">
                                  <div class="checkbox" style="margin-left: 15px;margin-bottom: 25px;">
                                    <label class="i-checks">
                                      <input type="checkbox" class="select_all_from_old_address" value=""><i></i> Select All
                                    </label>
                                  </div>

                                  <?php
                                    $categories_equip = array(1, 2, 3);
                                    $includes = array("capped item", "non-capped item", "disposable items");
                                    $counter_pickup_first_div = 0;

                                    foreach ($orders as $keys=>$equip_orders):
                                      if(in_array(strtolower($keys), $includes)): 
                                        $counter_pickup_first_div++;
                                  ?>
                                        <div class="col-md-6" style="float:right;">
                                          <label style="margin-bottom:20px"><?php echo $keys; ?></label> 
                                          <?php 
                                            foreach ($equip_orders as $sub_key=>$sub_value): 
                                              $checked = "";
                                              $displayer = "";
                                              $display_old_address = "";
                                              $disabler = "";
                                              if($pickup_equipment != "")
                                              {
                                                  if (in_array($sub_value[0]['equipmentID'], $pickup_equipment) || in_array($sub_value[1]['equipmentID'], $pickup_equipment)) 
                                                  {
                                                      $checked = "checked";
                                                  }
                                              }

                                              if ($sub_value[0]['activity_typeid'] == 2 || $sub_value[0]['activity_reference'] == 2 && $sub_value[0]['order_status'] == 'cancel' && $sub_value[0]['canceled_from_confirming'] == 1 || $sub_value[1]['activity_typeid'] == 2 || $sub_value[1]['activity_reference'] == 2 && $sub_value[1]['order_status'] == 'cancel' && $sub_value[1]['canceled_from_confirming'] == 1) {
                                                  $displayer = "display:none";
                                              } 
                                              else 
                                              {
                                                  $displayer = "display:block";
                                              }

                                              if(!empty($ptmove_address))
                                              {
                                                if($sub_value[0]['activity_typeid'] != 4)
                                                {
                                                  $display_old_address = "display:block";
                                                  $disabler = "return false";
                                                }

                                                else
                                                {
                                                  $display_old_address = "display:none";
                                                  $disabler = "";                                          
                                                }
                                              }
                                          ?>

                                          <!-- CAPPED ITEM-->
                                          <?php 
                                              if(in_array($sub_value[0]['categoryID'], $categories_equip)):  
                                                if(isset($sub_value['children'])): 
                                                  if(!empty($sub_value[0])):
                                                    if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                                      if($sub_value[0]['categoryID'] == 3){
                                                        if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170){
                                          ?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                            <label class="i-checks">
                                                              <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_old_address" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                              <i></i>
                                                              <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                              <?php 
                                                                if($sub_key == "Hospital Bed")
                                                                {
                                                                  echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                                else
                                                                {
                                                                  echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                              ?> 
                                                            </label>
                                                            <ul>
                                                              <?php 
                                                                $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                                $disable_unchecking = "";
                                                                $order_oxygen = $sub_value[0]['orderID'] + 2;
                                                                if(!empty($ptmove_address))
                                                                { 
                                                                  if($sub_value[0]['activity_typeid'] != 4)
                                                                  {
                                                                    $disable_unchecking = "return false";
                                                                  }
                                                                  else
                                                                  {
                                                                    $disable_unchecking = "";
                                                                  }
                                                                  
                                                                }
                                                                foreach($item_options as $options)
                                                                {
                                                                  $sub_equipmentID = $options['equipmentID'];
                                                                  $sub_equipmentID_parentID = $options['parentID'];
                                                                  $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                  if($options['input_type'] == "radio") 
                                                                  {

                                                                    echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false'  >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                    echo "</span><br />";
                                                                  }
                                                                  else if ($options['input_type'] == "text") 
                                                                  {
                                                                    if($sub_equipmentID == 121 || $sub_equipmentID == 194)
                                                                    {
                                                                      $get_value = get_value_of_equipment($sub_equipmentID_parentID, $patientID, $item_uniqueID);
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($get_value['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }else{
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }
                                                                  }
                                                                  else if ($options['input_type'] == "checkbox") 
                                                                  {

                                                                    if($options['orderID']==$order_oxygen)
                                                                    {
                                                                        echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                        echo "</span><br />";
                                                                    }
                                                                  }
                                                                 
                                                                }

                                                              ?>
                                                            </ul>
                                                            <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                          </div> <!-- .checkbox -->
                                                  <?php
                                                        }
                                                      }else{ 
                                                  ?>
                                                        <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                          <label class="i-checks">
                                                            <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_old_address" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                            <i></i>
                                                            <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                            <?php 
                                                                if($sub_key == "Hospital Bed")
                                                                {
                                                                  echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                                else
                                                                {
                                                                  echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                              ?> 
                                                          </label>
                                                          <ul>
                                                            <?php 
                                                              $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                              $disable_unchecking = "";
                                                              $order_oxygen = $sub_value[0]['orderID'] + 2;

                                                              if(!empty($ptmove_address))
                                                              { 
                                                                if($sub_value[0]['activity_typeid'] != 4)
                                                                {
                                                                  $disable_unchecking = "return false";
                                                                }
                                                                else
                                                                {
                                                                  $disable_unchecking = "";
                                                                }
                                                                
                                                              }
                                                              $count = 0;
                                                              foreach($item_options as $options)
                                                              {
                                                                $sub_equipmentID = $options['equipmentID'];
                                                                $sub_equipmentID_parentID = $options['parentID'];
                                                                $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                if($options['input_type'] == "radio") 
                                                                {

                                                                  echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false'  >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                  echo "</span><br />";
                                                                }
                                                                else if ($options['input_type'] == "text") 
                                                                {
                                                                  $count++;
                                                                  if($count == 1){
                                                                    echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                    echo "</span><br />";
                                                                  }
                                                                }
                                                                else if ($options['input_type'] == "checkbox") 
                                                                {
                                                                  if($options['orderID']==$order_oxygen)
                                                                  {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                      
                                                                  }
                                                                }
                                                                
                                                              }
                                                            ?>
                                                          </ul>
                                                          <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                        </div>
                                                <?php 
                                                      } 
                                                    endif; 
                                                  endif;
                                                ?>

                                                <!-- NONCAPPED ITEM-->
                                                <?php 
                                                  if(!empty($sub_value[1])) :  
                                                    if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) : 
                                                      if($sub_value[0]['categoryID'] == 3) {
                                                        if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170) {
                                                ?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                            <label class="i-checks">
                                                              <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_old_address" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                              <i></i>
                                                              <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                              <?php 
                                                                if($sub_key == "Hospital Bed")
                                                                {
                                                                  echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                }
                                                                else
                                                                {
                                                                  echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                }
                                                              ?> 
                                                            </label>
                                                            <ul>
                                                              <?php 
                                                                $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                $work_order = $sub_value[1]['uniqueID'];
                                                                
                                                                $disable_unchecking = "";
                                                                $order_oxygen = $sub_value[1]['orderID'] + 2;

                                                                if(!empty($ptmove_address))
                                                                { 
                                                                  if($sub_value[1]['activity_typeid'] != 4)
                                                                  {
                                                                    $disable_unchecking = "return false";
                                                                  }
                                                                  else
                                                                  {
                                                                    $disable_unchecking = "";
                                                                  }
                                                                }

                                                                foreach($item_options as $options)
                                                                {
                                                                  $sub_equipmentID = $options['equipmentID'];
                                                                  $sub_equipmentID_parentID = $options['parentID'];
                                                                  $item_uniqueID = $sub_value[1]['uniqueID'];

                                                                  if($options['input_type'] == "radio") 
                                                                  {
                                                                    echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                    echo "</span><br />";
                                                                  }
                                                                  else if ($options['input_type'] == "text") 
                                                                  {
                                                                    echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address'  name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                    echo "</span><br />";
                                                                  }
                                                                  else if ($options['input_type'] == "checkbox") 
                                                                  {
                                                                    if($options['orderID']==$order_oxygen)
                                                                    {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                  }
                                                                }
                                                              ?>

                                                            </ul>
                                                            <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                          </div>
                                                    <?php
                                                        }
                                                      }else{ 
                                                    ?>
                                                        <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                          <label class="i-checks">
                                                            <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_old_address" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                            <i></i>
                                                            <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                            <?php 
                                                              if($sub_key == "Hospital Bed")
                                                              {
                                                                echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                              }
                                                              else
                                                              {
                                                                echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                              }
                                                            ?> 
                                                          </label>
                                                          <ul>
                                                            <?php 
                                                              $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                              $work_order = $sub_value[1]['uniqueID'];
                                                              $disable_unchecking = "";
                                                              $order_oxygen = $sub_value[1]['orderID'] + 2;
                                                              if(!empty($ptmove_address))
                                                              { 
                                                                if($sub_value[1]['activity_typeid'] != 4)
                                                                {
                                                                  $disable_unchecking = "return false";
                                                                }
                                                                else
                                                                {
                                                                  $disable_unchecking = "";
                                                                }
                                                              }
                                                              $count=0;
                                                              foreach($item_options as $options)
                                                              {
                                                                $sub_equipmentID = $options['equipmentID'];
                                                                $sub_equipmentID_parentID = $options['parentID'];
                                                                $item_uniqueID = $sub_value[1]['uniqueID'];
                                                                
                                                                if($options['input_type'] == "radio") 
                                                                {
                                                                  echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                  echo "</span><br />";
                                                                }
                                                                else if ($options['input_type'] == "text") 
                                                                {
                                                                  $count++;
                                                                  if($count==1){
                                                                    echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address'  name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                    echo "</span><br />";
                                                                  }
                                                                }
                                                                else if ($options['input_type'] == "checkbox") 
                                                                {
                                                                  if($options['orderID']==$order_oxygen)
                                                                  {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox sub_options_checkbox_old_address' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    
                                                                  }
                                                                }
                                                              }
                                                            ?>

                                                          </ul>
                                                          <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                        </div>
                                              <?php 
                                                      } 
                                                    endif; 
                                                  endif;
                                                else: 
                                              ?>

                                                <!-- CAPPED ITEM-->
                                                <?php 
                                                  if(!empty($sub_value[0])) :
                                                    if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                                ?>
                                                      <div class="checkbox" style="<?php echo $displayer; ?>">
                                                        <label class="i-checks">
                                                          <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_old_address" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?>  data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                          <i></i>
                                                          <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                          <?php 
                                                            if($sub_key == "Hospital Bed")
                                                            {
                                                              echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                            }
                                                            else
                                                            {
                                                              echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                            }
                                                          ?> 
                                                        </label>
                                                        <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                      </div>
                                                <?php 
                                                    endif;
                                                  endif;
                                                ?>

                                                <!-- NONCAPPED ITEM-->
                                                <?php 
                                                  if(!empty($sub_value[1])): 
                                                    if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1):
                                                ?>
                                                      <div class="checkbox" style="<?php echo $displayer; ?>">
                                                        <label class="i-checks">
                                                          <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_old_address" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                          <i></i>
                                                          <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                          <?php 
                                                            if($sub_key == "Hospital Bed")
                                                            {
                                                              echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                            }
                                                            else
                                                            {
                                                              echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                            }
                                                          ?> 
                                                        </label>
                                                        <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                      </div>
                                          <?php
                                                    endif; 
                                                  endif;
                                                endif;  
                                              endif;  
                                            endforeach; 
                                          ?>
                                        </div>
                                  <?php 
                                      endif;  
                                    endforeach; 
                                  ?>
                                  <!-- <button type="submit" class="btn btn-danger pull-right " style="margin-top:-55px;">Save changes</button> -->
                                  <div> 
                                    <input type="hidden" class="pickup-counter" value="<?php echo $counter_pickup_first_div; ?>"></input> 
                                  </div>
                                </div> <!-- .col-sm-12-->
                                

                                <?php 
                                /***************************************************************
                                 CODE FOR NEW ADDRESSES EQUIPMENT PICKUP STARTS HERE 
                                ****************************************************************/
                                
                                  $categories_equip = array(1, 2, 3);
                                  $includes = array("capped item", "non-capped item", "disposable items");
                                  $new_counter_pickup_all_div = 0;
                                  $my_count = 0;

                                  foreach ($new_orders as $new_orders_loop):
                                ?>
                                    <div class="col-sm-12 address_equipment_col" style="display:none;" id="pickup_div_<?php echo $addressID[$my_count]; ?>" >
                                      <div class="checkbox" style="margin-left: 15px;margin-bottom: 25px;">
                                        <label class="i-checks">
                                          <input type="checkbox" class="select-all-pickup" data-id="<?php echo $addressID[$my_count]; ?>" value=""><i></i> Select All
                                        </label>
                                      </div>

                                      <?php
                                        foreach ($new_orders_loop as $keys=>$equip_orders):
                                          if(in_array(strtolower($keys), $includes)): 
                                            $new_counter_pickup_all_div++;
                                      ?>
                                            <div class="col-md-6" style="float:right;">
                                              <label style="margin-bottom:20px"><?php echo $keys; ?></label> 
                                              <?php 
                                                foreach ($equip_orders as $sub_key=>$sub_value): 
                                                  $checked = "";
                                                  $displayer = "";
                                                  $display_old_address = "";
                                                  $disabler = "";
                                                  if($pickup_equipment != "")
                                                  {
                                                      if (in_array($sub_value[0]['equipmentID'], $pickup_equipment) || in_array($sub_value[1]['equipmentID'], $pickup_equipment)) 
                                                      {
                                                          $checked = "checked";
                                                      }
                                                  }

                                                  if ($sub_value[0]['activity_typeid'] == 2 || $sub_value[0]['activity_reference'] == 2 && $sub_value[0]['order_status'] == 'cancel' && $sub_value[0]['canceled_from_confirming'] == 1 || $sub_value[1]['activity_typeid'] == 2 || $sub_value[1]['activity_reference'] == 2 && $sub_value[1]['order_status'] == 'cancel' && $sub_value[1]['canceled_from_confirming'] == 1) {
                                                      $displayer = "display:none";
                                                  } 
                                                  else 
                                                  {
                                                      $displayer = "display:block";
                                                  }

                                                  if(!empty($ptmove_address))
                                                  {
                                                    if($sub_value[0]['activity_typeid'] != 4)
                                                    {
                                                      $display_old_address = "display:block";
                                                      $checked_old_items = "checked";
                                                      $disabler = "return false";
                                                    }

                                                    else
                                                    {
                                                      $display_old_address = "display:none";
                                                      $checked_old_items = "";
                                                      $disabler = "";                                          
                                                    }
                                                  }
                                                  else
                                                  { 
                                                    $checked_old_items = "";
                                                  }
                                              ?>

                                              <!-- CAPPED ITEM-->
                                              <?php 
                                                  if(in_array($sub_value[0]['categoryID'], $categories_equip)):  
                                                    if(isset($sub_value['children'])): 
                                                      if(!empty($sub_value[0])):
                                                        if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                                          if($sub_value[0]['categoryID'] == 3){
                                                            if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170){
                                              ?>
                                                              <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                <label class="i-checks">
                                                                  <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_other_address_<?php echo $addressID[$my_count]; ?>" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                  <i></i>
                                                                  <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                  <?php 
                                                                    if($sub_key == "Hospital Bed")
                                                                    {
                                                                      echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                    }
                                                                    else
                                                                    {
                                                                      echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                    }
                                                                  ?> 
                                                                </label>
                                                                <ul>
                                                                  <?php 
                                                                    $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                                    $disable_unchecking = "";

                                                                    if(!empty($ptmove_address))
                                                                    { 
                                                                      if($sub_value[0]['activity_typeid'] != 4)
                                                                      {
                                                                        $disable_unchecking = "return false";
                                                                      }
                                                                      else
                                                                      {
                                                                        $disable_unchecking = "";
                                                                      }
                                                                      
                                                                    }
                                                                    foreach($item_options as $options)
                                                                    {
                                                                      $sub_equipmentID = $options['equipmentID'];
                                                                      $sub_equipmentID_parentID = $options['parentID'];
                                                                      $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                      if($options['input_type'] == "radio") 
                                                                      {
                                                                        echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false'  >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                        echo "</span><br />";
                                                                      }
                                                                      else if ($options['input_type'] == "text") 
                                                                      {
                                                                        echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                        echo "</span><br />";
                                                                      }
                                                                      else if ($options['input_type'] == "checkbox") 
                                                                      {
                                                                        echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                        echo "</span><br />";
                                                                      }
                                                                     
                                                                    }

                                                                  ?>
                                                                </ul>
                                                                <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                              </div> <!-- .checkbox -->
                                                      <?php
                                                            }
                                                          }else{ 
                                                      ?>
                                                            <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                              <label class="i-checks">
                                                                <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_other_address_<?php echo $addressID[$my_count]; ?>" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                <i></i>
                                                                <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                <?php 
                                                                  if($sub_key == "Hospital Bed")
                                                                  {
                                                                    echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                  }
                                                                  else
                                                                  {
                                                                    echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                  }
                                                                ?> 
                                                              </label>
                                                              <ul>
                                                                <?php 
                                                                  $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                                  $disable_unchecking = "";

                                                                  if(!empty($ptmove_address))
                                                                  { 
                                                                    if($sub_value[0]['activity_typeid'] != 4)
                                                                    {
                                                                      $disable_unchecking = "return false";
                                                                    }
                                                                    else
                                                                    {
                                                                      $disable_unchecking = "";
                                                                    }
                                                                    
                                                                  }
                                                                  foreach($item_options as $options)
                                                                  {
                                                                    $sub_equipmentID = $options['equipmentID'];
                                                                    $sub_equipmentID_parentID = $options['parentID'];
                                                                    $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                    if($options['input_type'] == "radio") 
                                                                    {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false'  >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "text") 
                                                                    {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "checkbox") 
                                                                    {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                  }
                                                                ?>
                                                              </ul>
                                                              <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                            </div>
                                                    <?php 
                                                          } 
                                                        endif; 
                                                      endif;
                                                    ?>

                                                    <!-- NONCAPPED ITEM-->
                                                    <?php 
                                                      if(!empty($sub_value[1])) :  
                                                        if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) : 
                                                          if($sub_value[0]['categoryID'] == 3) {
                                                            if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170) {
                                                    ?>
                                                              <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                <label class="i-checks">
                                                                  <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_other_address_<?php echo $addressID[$my_count]; ?>" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                  <i></i>
                                                                  <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                  <?php 
                                                                    if($sub_key == "Hospital Bed")
                                                                    {
                                                                      echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                    }
                                                                    else
                                                                    {
                                                                      echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                    }
                                                                  ?>
                                                                </label>
                                                                <ul>
                                                                  <?php 
                                                                    $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                    $work_order = $sub_value[1]['uniqueID'];
                                                
                                                                    $disable_unchecking = "";

                                                                    if(!empty($ptmove_address))
                                                                    { 
                                                                      if($sub_value[1]['activity_typeid'] != 4)
                                                                      {
                                                                        $disable_unchecking = "return false";
                                                                      }
                                                                      else
                                                                      {
                                                                        $disable_unchecking = "";
                                                                      }
                                                                    }

                                                                    foreach($item_options as $options)
                                                                    {
                                                                      $sub_equipmentID = $options['equipmentID'];
                                                                      $sub_equipmentID_parentID = $options['parentID'];
                                                                      $item_uniqueID = $sub_value[1]['uniqueID'];

                                                                      if($options['input_type'] == "radio") 
                                                                      {
                                                                        echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                        echo "</span><br />";
                                                                      }
                                                                      else if ($options['input_type'] == "text") 
                                                                      {
                                                                        echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox'  name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                        echo "</span><br />";
                                                                      }
                                                                      else if ($options['input_type'] == "checkbox") 
                                                                      {
                                                                        echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                        echo "</span><br />";
                                                                      }
                                                                    }
                                                                  ?>

                                                                </ul>
                                                                <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                              </div>
                                                        <?php
                                                            }
                                                          }else{ 
                                                        ?>
                                                            <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                              <label class="i-checks">
                                                                <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_other_address_<?php echo $addressID[$my_count]; ?>" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                <i></i>
                                                                <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                <?php 
                                                                  if($sub_key == "Hospital Bed")
                                                                  {
                                                                    echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                  }
                                                                  else
                                                                  {
                                                                    echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                  }
                                                                ?>
                                                              </label>
                                                              <ul>
                                                                <?php 
                                                                  $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                  $work_order = $sub_value[1]['uniqueID'];
                                                                  $disable_unchecking = "";

                                                                  if(!empty($ptmove_address))
                                                                  { 
                                                                    if($sub_value[1]['activity_typeid'] != 4)
                                                                    {
                                                                      $disable_unchecking = "return false";
                                                                    }
                                                                    else
                                                                    {
                                                                      $disable_unchecking = "";
                                                                    }
                                                                  }

                                                                  foreach($item_options as $options)
                                                                  {
                                                                    $sub_equipmentID = $options['equipmentID'];
                                                                    $sub_equipmentID_parentID = $options['parentID'];
                                                                    $item_uniqueID = $sub_value[1]['uniqueID'];

                                                                    if($options['input_type'] == "radio") 
                                                                    {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "text") 
                                                                    {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox'  name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "checkbox") 
                                                                    {
                                                                      echo "hello";
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                  }
                                                                ?>

                                                              </ul>
                                                              <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                            </div>
                                                  <?php 
                                                          } 
                                                        endif; 
                                                      endif;
                                                    else: 
                                                  ?>

                                                    <!-- CAPPED ITEM-->
                                                    <?php 
                                                      if(!empty($sub_value[0])) :
                                                        if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                                    ?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>">
                                                            <label class="i-checks">
                                                              <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_other_address_<?php echo $addressID[$my_count]; ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?>  data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                              <i></i>
                                                              <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                              <?php 
                                                                if($sub_key == "Hospital Bed")
                                                                {
                                                                  echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                                else
                                                                {
                                                                  echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                              ?>
                                                            </label>
                                                            <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                          </div>
                                                    <?php 
                                                        endif;
                                                      endif;
                                                    ?>

                                                    <!-- NONCAPPED ITEM-->
                                                    <?php 
                                                      if(!empty($sub_value[1])): 
                                                        if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1):
                                                    ?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>">
                                                            <label class="i-checks">
                                                              <input type="checkbox" name="pickup_equipments[]" class="checked_pickup_item checked_pickup_item_other_address_<?php echo $addressID[$my_count]; ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                              <i></i>
                                                              <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                              <?php 
                                                                if($sub_key == "Hospital Bed")
                                                                {
                                                                  echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                }
                                                                else
                                                                {
                                                                  echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                }
                                                              ?>
                                                            </label>
                                                            <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                          </div>
                                              <?php
                                                        endif; 
                                                      endif;
                                                    endif;  
                                                  endif;  
                                                endforeach; 
                                              ?>
                                            </div>
                                      <?php 
                                          endif;  
                                        endforeach; 
                                      ?>
                                      <!-- <button type="submit" class="btn btn-danger pull-right " style="margin-top:-55px;">Save changes</button> -->
                                    </div> <!-- .col-sm-12-->

                                <?php 
                                    $my_count++;
                                  endforeach;
                                
                                /***************************************************************
                                 CODE FOR NEW ADDRESSES EQUIPMENT PICKUP ENDS HERE 
                                ****************************************************************/
                                ?>
                                <div> 
                                  <input type="hidden" class="new-pickup-counter" value="<?php echo $new_counter_pickup_all_div; ?>"></input> 
                                </div>

                                <?php 
                                /***************************************************************************
                                  CODE FOR PICKUP ALL EQUIPMENT STARTS HERE 
                                ****************************************************************************/ 
                                ?>
                                
                                <div class="col-sm-12" id="pickup_all_div" style="display:none; padding-left: 0px;">
                                  <div class="col-sm-12 pickup_all_div_initial" style="margin-top: 5px;">
                                    <span style="font-weight:bold;">Initial Address</span> <br />
                                    <?php echo $old_address['street'] .", ". $old_address['placenum'] .", ". $old_address['city'] .", ". $old_address['state'] .", ". $old_address['postal_code'] ?>
                                    <div class="col-sm-12 " style="margin-top: 15px; margin-bottom: 15px; margin-left: 5px;">
                                      <?php
                                        $count_for_initial_pickup_all = 0;
                                        $categories_equip = array(1, 2, 3);
                                        $includes = array("capped item", "non-capped item", "disposable items");
                                        $count = 0;

                                        foreach ($orders as $keys=>$equip_orders):
                                          if(in_array(strtolower($keys), $includes)): 
                                            $count++;
                                      ?>
                                            <div class="col-md-6" style="float:right;">
                                              <label style="margin-bottom:20px"><?php echo $keys; ?></label> 
                                              <?php 
                                                foreach ($equip_orders as $sub_key=>$sub_value): 
                                                  $displayer = "";
                                                  $display_old_address = "";
                                                  $disabler = "";
                                                  $count_for_initial_pickup_all++;
                                                  
                                                  if ($sub_value[0]['activity_typeid'] == 2 || $sub_value[0]['activity_reference'] == 2 && $sub_value[0]['order_status'] == 'cancel' && $sub_value[0]['canceled_from_confirming'] == 1 || $sub_value[1]['activity_typeid'] == 2 || $sub_value[1]['activity_reference'] == 2 && $sub_value[1]['order_status'] == 'cancel' && $sub_value[1]['canceled_from_confirming'] == 1) {
                                                    $displayer = "display:none";
                                                  } 
                                                  else 
                                                  {
                                                    $displayer = "display:block";
                                                  }

                                                  if(!empty($ptmove_address))
                                                  {
                                                    if($sub_value[0]['activity_typeid'] != 4)
                                                    {
                                                      $display_old_address = "display:block";
                                                      $checked_old_items = "checked";
                                                      $disabler = "return false";
                                                    }

                                                    else
                                                    {
                                                      $display_old_address = "display:none";
                                                      $checked_old_items = "";
                                                      $disabler = "";                                          
                                                    }
                                                  }
                                                  else
                                                  { 
                                                    $checked_old_items = "";
                                                  }
                                              ?>

                                              <!-- CAPPED ITEM-->
                                              <?php 
                                                  if(in_array($sub_value[0]['categoryID'], $categories_equip)):  
                                                    if(isset($sub_value['children'])): 
                                                      if(!empty($sub_value[0])):
                                                        if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                                          if($sub_value[0]['categoryID'] == 3){
                                                            if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170){
                                              ?>
                                                              <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                <label class="i-checks">
                                                                  <input type="hidden" name="initial_unique_ids[]" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                  <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_initial[]" class="pickup_equipments_initial" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                  <i></i>
                                                                  <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                  <?php 
                                                                    if($sub_key == "Hospital Bed")
                                                                    {
                                                                      echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                    }
                                                                    else
                                                                    {
                                                                      echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                    }
                                                                  ?> 
                                                                </label>
                                                                <ul>
                                                                  <?php 
                                                                    $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                                    $disable_unchecking = "";

                                                                    if(!empty($ptmove_address))
                                                                    { 
                                                                      if($sub_value[0]['activity_typeid'] != 4)
                                                                      {
                                                                        $disable_unchecking = "return false";
                                                                      }
                                                                      else
                                                                      {
                                                                        $disable_unchecking = "";
                                                                      }
                                                                      
                                                                    }
                                                                    foreach($item_options as $options)
                                                                    {
                                                                      $sub_equipmentID = $options['equipmentID'];
                                                                      $sub_equipmentID_parentID = $options['parentID'];
                                                                      $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                      if($options['input_type'] == "radio") 
                                                                      {
                                                                        echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_initial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                        echo "</span><br />";
                                                                      }
                                                                      else if ($options['input_type'] == "text") 
                                                                      {
                                                                        echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_initial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                        echo "</span><br />";
                                                                      }
                                                                      else if ($options['input_type'] == "checkbox") 
                                                                      {
                                                                        echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_initial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                        echo "</span><br />";
                                                                      }
                                                                     
                                                                    }

                                                                  ?>
                                                                </ul>
                                                                <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                              </div> <!-- .checkbox -->
                                                      <?php
                                                            }
                                                          }else{ 
                                                      ?>
                                                            <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                              <label class="i-checks">
                                                                <input type="hidden" name="initial_unique_ids[]" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_initial[]" class="pickup_equipments_initial" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                <i></i>
                                                                <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                <?php 
                                                                  if($sub_key == "Hospital Bed")
                                                                  {
                                                                    echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                  }
                                                                  else
                                                                  {
                                                                    echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                  }
                                                                ?> 
                                                              </label>
                                                              <ul>
                                                                <?php 
                                                                  $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                                  $disable_unchecking = "";

                                                                  if(!empty($ptmove_address))
                                                                  { 
                                                                    if($sub_value[0]['activity_typeid'] != 4)
                                                                    {
                                                                      $disable_unchecking = "return false";
                                                                    }
                                                                    else
                                                                    {
                                                                      $disable_unchecking = "";
                                                                    }
                                                                    
                                                                  }
                                                                  foreach($item_options as $options)
                                                                  {
                                                                    $sub_equipmentID = $options['equipmentID'];
                                                                    $sub_equipmentID_parentID = $options['parentID'];
                                                                    $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                    if($options['input_type'] == "radio") 
                                                                    {
                                                                      echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_initial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false'  >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "text") 
                                                                    {
                                                                      echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_initial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='return false' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "checkbox") 
                                                                    {
                                                                      echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_initial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='return false' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                  }
                                                                ?>
                                                              </ul>
                                                              <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                            </div>
                                                    <?php 
                                                          } 
                                                        endif; 
                                                      endif;
                                                    ?>

                                                    <!-- NONCAPPED ITEM-->
                                                    <?php 
                                                      if(!empty($sub_value[1])) :  
                                                        if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) : 
                                                          if($sub_value[0]['categoryID'] == 3) {
                                                            if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170) {
                                                    ?>
                                                              <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                <label class="i-checks">
                                                                  <input type="hidden" name="initial_unique_ids[]" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                  <input type="checkbox" onclick="return false" <?php echo $checked_old_items ?> name="pickup_equipments_initial[]" class="pickup_equipments_initial" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                  <i></i>
                                                                  <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                  <?php 
                                                                    if($sub_key == "Hospital Bed")
                                                                    {
                                                                      echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                    }
                                                                    else
                                                                    {
                                                                      echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                    }
                                                                  ?> 
                                                                </label>
                                                                <ul>
                                                                  <?php 
                                                                    $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                    $work_order = $sub_value[1]['uniqueID'];
                                                
                                                                    $disable_unchecking = "";

                                                                    if(!empty($ptmove_address))
                                                                    { 
                                                                      if($sub_value[1]['activity_typeid'] != 4)
                                                                      {
                                                                        $disable_unchecking = "return false";
                                                                      }
                                                                      else
                                                                      {
                                                                        $disable_unchecking = "";
                                                                      }
                                                                    }

                                                                    foreach($item_options as $options)
                                                                    {
                                                                      $sub_equipmentID = $options['equipmentID'];
                                                                      $sub_equipmentID_parentID = $options['parentID'];
                                                                      $item_uniqueID = $sub_value[1]['uniqueID'];

                                                                      if($options['input_type'] == "radio") 
                                                                      {
                                                                        echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_optionsinitial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                        echo "</span><br />";
                                                                      }
                                                                      else if ($options['input_type'] == "text") 
                                                                      {
                                                                        echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox'  name='equip_optionsinitial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                        echo "</span><br />";
                                                                      }
                                                                      else if ($options['input_type'] == "checkbox") 
                                                                      {
                                                                        echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_optionsinitial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                        echo "</span><br />";
                                                                      }
                                                                    }
                                                                  ?>

                                                                </ul>
                                                                <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                              </div>
                                                        <?php
                                                            }
                                                          }else{ 
                                                        ?>
                                                            <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                              <label class="i-checks">
                                                                <input type="hidden" name="initial_unique_ids[]" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_initial[]" class="pickup_equipments_initial" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                <i></i>
                                                                <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                <?php 
                                                                  if($sub_key == "Hospital Bed")
                                                                  {
                                                                    echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                  }
                                                                  else
                                                                  {
                                                                    echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                  }
                                                                ?> 
                                                              </label>
                                                              <ul>
                                                                <?php 
                                                                  $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                  $work_order = $sub_value[1]['uniqueID'];
                                                                  $disable_unchecking = "";

                                                                  if(!empty($ptmove_address))
                                                                  { 
                                                                    if($sub_value[1]['activity_typeid'] != 4)
                                                                    {
                                                                      $disable_unchecking = "return false";
                                                                    }
                                                                    else
                                                                    {
                                                                      $disable_unchecking = "";
                                                                    }
                                                                  }

                                                                  foreach($item_options as $options)
                                                                  {
                                                                    $sub_equipmentID = $options['equipmentID'];
                                                                    $sub_equipmentID_parentID = $options['parentID'];
                                                                    $item_uniqueID = $sub_value[1]['uniqueID'];

                                                                    if($options['input_type'] == "radio") 
                                                                    {
                                                                      echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_initial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "text") 
                                                                    {
                                                                      echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox'  name='equip_options_initial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "checkbox") 
                                                                    {
                                                                      echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_initial[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                  }
                                                                ?>

                                                              </ul>
                                                              <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                            </div>
                                                  <?php 
                                                          } 
                                                        endif; 
                                                      endif;
                                                    else: 
                                                  ?>

                                                    <!-- CAPPED ITEM-->
                                                    <?php 
                                                      if(!empty($sub_value[0])) :
                                                        if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                                    ?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>">
                                                            <label class="i-checks">
                                                              <input type="hidden" name="initial_unique_ids[]" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                              <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_initial[]" class="pickup_equipments_initial" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?>  data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                              <i></i>
                                                              <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                              <?php 
                                                                if($sub_key == "Hospital Bed")
                                                                {
                                                                  echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                                else
                                                                {
                                                                  echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                              ?> 
                                                            </label>
                                                            <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                          </div>
                                                    <?php 
                                                        endif;
                                                      endif;
                                                    ?>

                                                    <!-- NONCAPPED ITEM-->
                                                    <?php 
                                                      if(!empty($sub_value[1])): 
                                                        if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1):
                                                    ?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>">
                                                            <label class="i-checks">
                                                              <input type="hidden" name="initial_unique_ids[]" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                              <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_initial[]" class="pickup_equipments_initial" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                              <i></i>
                                                              <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                              <?php 
                                                                if($sub_key == "Hospital Bed")
                                                                {
                                                                  echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                }
                                                                else
                                                                {
                                                                  echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                }
                                                              ?> 
                                                            </label>
                                                            <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                          </div>
                                              <?php
                                                        endif; 
                                                      endif;
                                                    endif;  
                                                  endif;  
                                                endforeach; 
                                              ?>
                                            </div>
                                      <?php 
                                          endif;  
                                        endforeach; 
                                      ?>
                                    </div>
                                    <input type="hidden" value="<?php echo $count_for_initial_pickup_all; ?>" id="count_for_initial_pickup_all">
                                  </div> <!-- .col-sm-12 -->
                                  <?php
                                    if($patient_move_address){ 
                                  ?>
                                      <div class="col-sm-12 pickup_all_div_ptmove" style="margin-top: 5px;">
                                        <span style="font-weight:bold;">Patient Move Address</span> <br />
                                  <?php 
                                        $count_for_ptmove_pickup_all = 0;
                                        foreach ($patient_move_address as $patient_move_row) {
                                          foreach ($orders_patient_move as $key=>$value) {
                                            if($patient_move_row['id'] == $key){
                                              $count_for_ptmove_pickup_all++;
                                  ?>
                                              <?php echo $patient_move_row['street'] .", ". $patient_move_row['placenum'] .", ". $patient_move_row['city'] .", ". $patient_move_row['state'] .", ". $patient_move_row['postal_code'] ?>
                                              <div class="col-sm-12 " style="margin-top: 15px; margin-bottom: 15px; margin-left: 5px;">
                                  <?php
                                                $categories_equip = array(1, 2, 3);
                                                $includes = array("capped item", "non-capped item", "disposable items");
                                                $count = 0;

                                                foreach ($value as $keys=>$equip_orders):
                                                  if(in_array(strtolower($keys), $includes)): 
                                                    $count++;
                                  ?>
                                                    <div class="col-md-6" style="float:right;">
                                                      <label style="margin-bottom:20px"><?php echo $keys; ?></label> 
                                                      <?php 
                                                        foreach ($equip_orders as $sub_key=>$sub_value): 
                                                          $checked = "";
                                                          $displayer = "";
                                                          $display_old_address = "";
                                                          $disabler = "";
                                                          if($pickup_equipment != "")
                                                          {
                                                              if (in_array($sub_value[0]['equipmentID'], $pickup_equipment) || in_array($sub_value[1]['equipmentID'], $pickup_equipment)) 
                                                              {
                                                                  $checked = "checked";
                                                              }
                                                          }

                                                          if ($sub_value[0]['activity_typeid'] == 2 || $sub_value[0]['activity_reference'] == 2 && $sub_value[0]['order_status'] == 'cancel' && $sub_value[0]['canceled_from_confirming'] == 1 || $sub_value[1]['activity_typeid'] == 2 || $sub_value[1]['activity_reference'] == 2 && $sub_value[1]['order_status'] == 'cancel' && $sub_value[1]['canceled_from_confirming'] == 1) {
                                                              $displayer = "display:none";
                                                          } 
                                                          else 
                                                          {
                                                              $displayer = "display:block";
                                                          }

                                                          if(!empty($ptmove_address))
                                                          {
                                                            if($sub_value[0]['activity_typeid'] != 4)
                                                            {
                                                              $display_old_address = "display:block";
                                                              $checked_old_items = "checked";
                                                              $disabler = "return false";
                                                            }

                                                            else
                                                            {
                                                              $display_old_address = "display:none";
                                                              $checked_old_items = "";
                                                              $disabler = "";                                          
                                                            }
                                                          }
                                                          else
                                                          { 
                                                            $checked_old_items = "";
                                                          }
                                                      ?>

                                                      <!-- CAPPED ITEM-->
                                                      <?php 
                                                          if(in_array($sub_value[0]['categoryID'], $categories_equip)):  
                                                            if(isset($sub_value['children'])): 
                                                              if(!empty($sub_value[0])):
                                                                if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                                                  if($sub_value[0]['categoryID'] == 3){
                                                                    if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170){
                                                      ?>
                                                                      <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                        <label class="i-checks">
                                                                          <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_ptmove_<?php echo $patient_move_row['id']; ?>[]" class="pickup_equipments_ptmove" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                          <i></i>
                                                                          <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                          <?php 
                                                                            if($sub_key == "Hospital Bed")
                                                                            {
                                                                              echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                            }
                                                                            else
                                                                            {
                                                                              echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                            }
                                                                          ?> 
                                                                        </label>
                                                                        <ul>
                                                                          <?php 
                                                                            $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                                            $disable_unchecking = "";

                                                                            if(!empty($ptmove_address))
                                                                            { 
                                                                              if($sub_value[0]['activity_typeid'] != 4)
                                                                              {
                                                                                $disable_unchecking = "return false";
                                                                              }
                                                                              else
                                                                              {
                                                                                $disable_unchecking = "";
                                                                              }
                                                                              
                                                                            }
                                                                            foreach($item_options as $options)
                                                                            {
                                                                              $sub_equipmentID = $options['equipmentID'];
                                                                              $sub_equipmentID_parentID = $options['parentID'];
                                                                              $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                              if($options['input_type'] == "radio") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                                echo "</span><br />";
                                                                              }
                                                                              else if ($options['input_type'] == "text") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                                echo "</span><br />";
                                                                              }
                                                                              else if ($options['input_type'] == "checkbox") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                                echo "</span><br />";
                                                                              }
                                                                             
                                                                            }

                                                                          ?>
                                                                        </ul>
                                                                        <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                      </div> <!-- .checkbox -->
                                                              <?php
                                                                    }
                                                                  }else{ 
                                                              ?>
                                                                    <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                      <label class="i-checks">
                                                                        <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_ptmove_<?php echo $patient_move_row['id']; ?>[]" class="pickup_equipments_ptmove" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                        <i></i>
                                                                        <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                        <?php 
                                                                          if($sub_key == "Hospital Bed")
                                                                          {
                                                                            echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                          }
                                                                          else
                                                                          {
                                                                            echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                          }
                                                                        ?>
                                                                      </label>
                                                                      <ul>
                                                                        <?php 
                                                                          $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                                          $disable_unchecking = "";

                                                                          if(!empty($ptmove_address))
                                                                          { 
                                                                            if($sub_value[0]['activity_typeid'] != 4)
                                                                            {
                                                                              $disable_unchecking = "return false";
                                                                            }
                                                                            else
                                                                            {
                                                                              $disable_unchecking = "";
                                                                            }
                                                                            
                                                                          }
                                                                          foreach($item_options as $options)
                                                                          {
                                                                            $sub_equipmentID = $options['equipmentID'];
                                                                            $sub_equipmentID_parentID = $options['parentID'];
                                                                            $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                            if($options['input_type'] == "radio") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                              echo "</span><br />";
                                                                            }
                                                                            else if ($options['input_type'] == "text") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                              echo "</span><br />";
                                                                            }
                                                                            else if ($options['input_type'] == "checkbox") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                              echo "</span><br />";
                                                                            }
                                                                          }
                                                                        ?>
                                                                      </ul>
                                                                      <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                    </div>
                                                            <?php 
                                                                  } 
                                                                endif; 
                                                              endif;
                                                            ?>

                                                            <!-- NONCAPPED ITEM-->
                                                            <?php 
                                                              if(!empty($sub_value[1])) :  
                                                                if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) : 
                                                                  if($sub_value[0]['categoryID'] == 3) {
                                                                    if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170) {
                                                            ?>
                                                                      <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                        <label class="i-checks">
                                                                          <input type="checkbox" onclick="return false" <?php echo $checked_old_items ?> name="pickup_equipments_ptmove_<?php echo $patient_move_row['id']; ?>[]" class="pickup_equipments_ptmove" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                          <i></i>
                                                                          <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                          <?php 
                                                                            if($sub_key == "Hospital Bed")
                                                                            {
                                                                              echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                            }
                                                                            else
                                                                            {
                                                                              echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                            }
                                                                          ?>
                                                                        </label>
                                                                        <ul>
                                                                          <?php 
                                                                            $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                            $work_order = $sub_value[1]['uniqueID'];
                                                        
                                                                            $disable_unchecking = "";

                                                                            if(!empty($ptmove_address))
                                                                            { 
                                                                              if($sub_value[1]['activity_typeid'] != 4)
                                                                              {
                                                                                $disable_unchecking = "return false";
                                                                              }
                                                                              else
                                                                              {
                                                                                $disable_unchecking = "";
                                                                              }
                                                                            }

                                                                            foreach($item_options as $options)
                                                                            {
                                                                              $sub_equipmentID = $options['equipmentID'];
                                                                              $sub_equipmentID_parentID = $options['parentID'];
                                                                              $item_uniqueID = $sub_value[1]['uniqueID'];

                                                                              if($options['input_type'] == "radio") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                                echo "</span><br />";
                                                                              }
                                                                              else if ($options['input_type'] == "text") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox'  name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                                echo "</span><br />";
                                                                              }
                                                                              else if ($options['input_type'] == "checkbox") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                                echo "</span><br />";
                                                                              }
                                                                            }
                                                                          ?>

                                                                        </ul>
                                                                        <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                      </div>
                                                                <?php
                                                                    }
                                                                  }else{ 
                                                                ?>
                                                                    <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                      <label class="i-checks">
                                                                        <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_ptmove_<?php echo $patient_move_row['id']; ?>[]" class="pickup_equipments_ptmove" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                        <i></i>
                                                                        <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                        <?php 
                                                                          if($sub_key == "Hospital Bed")
                                                                          {
                                                                            echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                          }
                                                                          else
                                                                          {
                                                                            echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                          }
                                                                        ?>
                                                                      </label>
                                                                      <ul>
                                                                        <?php 
                                                                          $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                          $work_order = $sub_value[1]['uniqueID'];
                                                                          $disable_unchecking = "";

                                                                          if(!empty($ptmove_address))
                                                                          { 
                                                                            if($sub_value[1]['activity_typeid'] != 4)
                                                                            {
                                                                              $disable_unchecking = "return false";
                                                                            }
                                                                            else
                                                                            {
                                                                              $disable_unchecking = "";
                                                                            }
                                                                          }

                                                                          foreach($item_options as $options)
                                                                          {
                                                                            $sub_equipmentID = $options['equipmentID'];
                                                                            $sub_equipmentID_parentID = $options['parentID'];
                                                                            $item_uniqueID = $sub_value[1]['uniqueID'];

                                                                            if($options['input_type'] == "radio") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                              echo "</span><br />";
                                                                            }
                                                                            else if ($options['input_type'] == "text") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox'  name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                              echo "</span><br />";
                                                                            }
                                                                            else if ($options['input_type'] == "checkbox") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_ptmove_".$patient_move_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                              echo "</span><br />";
                                                                            }
                                                                          }
                                                                        ?>

                                                                      </ul>
                                                                      <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                    </div>
                                                          <?php 
                                                                  } 
                                                                endif; 
                                                              endif;
                                                            else: 
                                                          ?>

                                                            <!-- CAPPED ITEM-->
                                                            <?php 
                                                              if(!empty($sub_value[0])) :
                                                                if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                                            ?>
                                                                  <div class="checkbox" style="<?php echo $displayer; ?>">
                                                                    <label class="i-checks">
                                                                      <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_ptmove_<?php echo $patient_move_row['id']; ?>[]" class="pickup_equipments_ptmove" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?>  data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                      <i></i>
                                                                      <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                      <?php 
                                                                        if($sub_key == "Hospital Bed")
                                                                        {
                                                                          echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                        }
                                                                        else
                                                                        {
                                                                          echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                        }
                                                                      ?>
                                                                    </label>
                                                                    <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                  </div>
                                                            <?php 
                                                                endif;
                                                              endif;
                                                            ?>

                                                            <!-- NONCAPPED ITEM-->
                                                            <?php 
                                                              if(!empty($sub_value[1])): 
                                                                if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1):
                                                            ?>
                                                                  <div class="checkbox" style="<?php echo $displayer; ?>">
                                                                    <label class="i-checks">
                                                                      <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_ptmove_<?php echo $patient_move_row['id']; ?>[]" class="pickup_equipments_ptmove" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                      <i></i>
                                                                      <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                      <?php 
                                                                        if($sub_key == "Hospital Bed")
                                                                        {
                                                                          echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                        }
                                                                        else
                                                                        {
                                                                          echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                        }
                                                                      ?>
                                                                    </label>
                                                                    <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                  </div>
                                                      <?php
                                                                endif; 
                                                              endif;
                                                            endif;  
                                                          endif;  
                                                        endforeach; 
                                                      ?>
                                                    </div>
                                  <?php 
                                                  endif;
                                                endforeach;  
                                  ?>    
                                              </div>
                                  <?php
                                            }
                                          }
                                        }
                                  ?>
                                        <input type="hidden" value="<?php echo $count_for_ptmove_pickup_all; ?>" id="count_for_ptmove_pickup_all">
                                      </div>
                                  <?php 
                                    }
                                    if($respite_address){
                                  ?>
                                      <div class="col-sm-12 pickup_all_div_respite" style="margin-top: 5px;">
                                        <span style="font-weight:bold;">Respite Address</span> <br />
                                  <?php 
                                        $count_for_respite_pickup_all = 0;
                                        foreach ($respite_address as $respite_row) {
                                          foreach ($orders_respite as $key=>$value) {
                                            if($respite_row['id'] == $key){
                                              $count_for_respite_pickup_all++;
                                  ?>
                                              <?php echo $respite_row['street'] .", ". $respite_row['placenum'] .", ". $respite_row['city'] .", ". $respite_row['state'] .", ". $respite_row['postal_code'] ?>
                                              <div class="col-sm-12 " style="margin-top: 15px; margin-bottom: 15px; margin-left: 5px;">
                                  <?php
                                                $categories_equip = array(1, 2, 3);
                                                $includes = array("capped item", "non-capped item", "disposable items");
                                                $count = 0;

                                                foreach ($value as $keys=>$equip_orders):
                                                  if(in_array(strtolower($keys), $includes)): 
                                                    $count++;
                                  ?>
                                                    <div class="col-md-6" style="float:right;">
                                                      <label style="margin-bottom:20px"><?php echo $keys; ?></label> 
                                                      <?php 
                                                        foreach ($equip_orders as $sub_key=>$sub_value): 
                                                          $displayer = "";
                                                          $display_old_address = "";
                                                          $disabler = "";
                                                          if ($sub_value[0]['activity_typeid'] == 2 || $sub_value[0]['activity_reference'] == 2 && $sub_value[0]['order_status'] == 'cancel' && $sub_value[0]['canceled_from_confirming'] == 1 || $sub_value[1]['activity_typeid'] == 2 || $sub_value[1]['activity_reference'] == 2 && $sub_value[1]['order_status'] == 'cancel' && $sub_value[1]['canceled_from_confirming'] == 1) {
                                                              $displayer = "display:none";
                                                          } 
                                                          else 
                                                          {
                                                              $displayer = "display:block";
                                                          }

                                                          if(!empty($ptmove_address))
                                                          {
                                                            if($sub_value[0]['activity_typeid'] != 4)
                                                            {
                                                              $display_old_address = "display:block";
                                                              $checked_old_items = "checked";
                                                              $disabler = "return false";
                                                            }

                                                            else
                                                            {
                                                              $display_old_address = "display:none";
                                                              $checked_old_items = "";
                                                              $disabler = "";                                          
                                                            }
                                                          }
                                                          else
                                                          { 
                                                            $checked_old_items = "";
                                                          }
                                                      ?>

                                                      <!-- CAPPED ITEM-->
                                                      <?php 
                                                          if(in_array($sub_value[0]['categoryID'], $categories_equip)):  
                                                            if(isset($sub_value['children'])): 
                                                              if(!empty($sub_value[0])):
                                                                if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                                                  if($sub_value[0]['categoryID'] == 3){
                                                                    if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170){
                                                      ?>
                                                                      <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                        <label class="i-checks">
                                                                          <input type="hidden" name="respite_unique_ids[]" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                          <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_respite_<?php echo $respite_row['id']; ?>[]" class="pickup_equipments_respite" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                          <i></i>
                                                                          <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                          <?php 
                                                                            if($sub_key == "Hospital Bed")
                                                                            {
                                                                              echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                            }
                                                                            else
                                                                            {
                                                                              echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                            }
                                                                          ?>
                                                                        </label>
                                                                        <ul>
                                                                          <?php 
                                                                            $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                                            $disable_unchecking = "";

                                                                            if(!empty($ptmove_address))
                                                                            { 
                                                                              if($sub_value[0]['activity_typeid'] != 4)
                                                                              {
                                                                                $disable_unchecking = "return false";
                                                                              }
                                                                              else
                                                                              {
                                                                                $disable_unchecking = "";
                                                                              }
                                                                              
                                                                            }
                                                                            foreach($item_options as $options)
                                                                            {
                                                                              $sub_equipmentID = $options['equipmentID'];
                                                                              $sub_equipmentID_parentID = $options['parentID'];
                                                                              $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                              if($options['input_type'] == "radio") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                                echo "</span><br />";
                                                                              }
                                                                              else if ($options['input_type'] == "text") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                                echo "</span><br />";
                                                                              }
                                                                              else if ($options['input_type'] == "checkbox") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                                echo "</span><br />";
                                                                              }
                                                                             
                                                                            }

                                                                          ?>
                                                                        </ul>
                                                                        <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                      </div> <!-- .checkbox -->
                                                              <?php
                                                                    }
                                                                  }else{ 
                                                              ?>
                                                                    <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                      <label class="i-checks">
                                                                        <input type="hidden" name="respite_unique_ids[]" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                        <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_respite_<?php echo $respite_row['id']; ?>[]" class="pickup_equipments_respite" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                        <i></i>
                                                                        <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                        <?php 
                                                                          if($sub_key == "Hospital Bed")
                                                                          {
                                                                            echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                          }
                                                                          else
                                                                          {
                                                                            echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                          }
                                                                        ?>
                                                                      </label>
                                                                      <ul>
                                                                        <?php 
                                                                          $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                                          $disable_unchecking = "";

                                                                          if(!empty($ptmove_address))
                                                                          { 
                                                                            if($sub_value[0]['activity_typeid'] != 4)
                                                                            {
                                                                              $disable_unchecking = "return false";
                                                                            }
                                                                        
                                                                            else
                                                                            {
                                                                              $disable_unchecking = "";
                                                                            }
                                                                            
                                                                          }
                                                                          foreach($item_options as $options)
                                                                          {
                                                                            $sub_equipmentID = $options['equipmentID'];
                                                                            $sub_equipmentID_parentID = $options['parentID'];
                                                                            $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                            if($options['input_type'] == "radio") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                              echo "</span><br />";
                                                                            }
                                                                            else if ($options['input_type'] == "text") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                              echo "</span><br />";
                                                                            }
                                                                            else if ($options['input_type'] == "checkbox") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                              echo "</span><br />";
                                                                            }
                                                                          }
                                                                        ?>
                                                                      </ul>
                                                                      <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                    </div>
                                                            <?php 
                                                                  } 
                                                                endif; 
                                                              endif;
                                                            ?>

                                                            <!-- NONCAPPED ITEM-->
                                                            <?php 
                                                              if(!empty($sub_value[1])) :  
                                                                if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) : 
                                                                  if($sub_value[0]['categoryID'] == 3) {
                                                                    if($sub_value[0]['equipmentID'] == 11 || $sub_value[0]['equipmentID'] == 170) {
                                                            ?>
                                                                      <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                        <label class="i-checks">
                                                                          <input type="hidden" name="respite_unique_ids[]" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                          <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_respite_<?php echo $respite_row['id']; ?>[]" class="pickup_equipments_respite" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                          <i></i>
                                                                          <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                          <?php 
                                                                            if($sub_key == "Hospital Bed")
                                                                            {
                                                                              echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                            }
                                                                            else
                                                                            {
                                                                              echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                            }
                                                                          ?>
                                                                        </label>
                                                                        <ul>
                                                                          <?php 
                                                                            $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                            $work_order = $sub_value[1]['uniqueID'];
                                                        
                                                                            $disable_unchecking = "";

                                                                            if(!empty($ptmove_address))
                                                                            { 
                                                                              if($sub_value[1]['activity_typeid'] != 4)
                                                                              {
                                                                                $disable_unchecking = "return false";
                                                                              }
                                                                              else
                                                                              {
                                                                                $disable_unchecking = "";
                                                                              }
                                                                            }

                                                                            foreach($item_options as $options)
                                                                            {
                                                                              $sub_equipmentID = $options['equipmentID'];
                                                                              $sub_equipmentID_parentID = $options['parentID'];
                                                                              $item_uniqueID = $sub_value[1]['uniqueID'];

                                                                              if($options['input_type'] == "radio") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                                echo "</span><br />";
                                                                              }
                                                                              else if ($options['input_type'] == "text") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                                echo "</span><br />";
                                                                              }
                                                                              else if ($options['input_type'] == "checkbox") 
                                                                              {
                                                                                echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                                echo "</span><br />";
                                                                              }
                                                                            }
                                                                          ?>

                                                                        </ul>
                                                                        <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                      </div>
                                                                <?php
                                                                    }
                                                                  }else{ 
                                                                ?>
                                                                    <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                                      <label class="i-checks">
                                                                        <input type="hidden" name="respite_unique_ids[]" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                        <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_respite_<?php echo $respite_row['id']; ?>[]" class="pickup_equipments_respite" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                        <i></i>
                                                                        <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                        <?php 
                                                                          if($sub_key == "Hospital Bed")
                                                                          {
                                                                            echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                          }
                                                                          else
                                                                          {
                                                                            echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                          }
                                                                        ?>
                                                                      </label>
                                                                      <ul>
                                                                        <?php 
                                                                          $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                          $work_order = $sub_value[1]['uniqueID'];
                                                                          $disable_unchecking = "";

                                                                          if(!empty($ptmove_address))
                                                                          { 
                                                                            if($sub_value[1]['activity_typeid'] != 4)
                                                                            {
                                                                              $disable_unchecking = "return false";
                                                                            }
                                                                            else
                                                                            {
                                                                              $disable_unchecking = "";
                                                                            }
                                                                          }

                                                                          foreach($item_options as $options)
                                                                          {
                                                                            $sub_equipmentID = $options['equipmentID'];
                                                                            $sub_equipmentID_parentID = $options['parentID'];
                                                                            $item_uniqueID = $sub_value[1]['uniqueID'];

                                                                            if($options['input_type'] == "radio") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                              echo "</span><br />";
                                                                            }
                                                                            else if ($options['input_type'] == "text") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox'  name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                              echo "</span><br />";
                                                                            }
                                                                            else if ($options['input_type'] == "checkbox") 
                                                                            {
                                                                              echo "<input type='checkbox' onclick='return false' checked $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_respite_".$respite_row['id']."[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                              echo "</span><br />";
                                                                            }
                                                                          }
                                                                        ?>

                                                                      </ul>
                                                                      <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                    </div>
                                                          <?php 
                                                                  } 
                                                                endif; 
                                                              endif;
                                                            else: 
                                                          ?>

                                                            <!-- CAPPED ITEM-->
                                                            <?php 
                                                              if(!empty($sub_value[0])) :
                                                                if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1):
                                                            ?>
                                                                  <div class="checkbox" style="<?php echo $displayer; ?>">
                                                                    <label class="i-checks">
                                                                      <input type="hidden" name="respite_unique_ids[]" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                      <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_respite_<?php echo $respite_row['id']; ?>[]" class="pickup_equipments_respite" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?>  data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                      <i></i>
                                                                      <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                      <?php 
                                                                        if($sub_key == "Hospital Bed")
                                                                        {
                                                                          echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                        }
                                                                        else
                                                                        {
                                                                          echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                        }
                                                                      ?>
                                                                    </label>
                                                                    <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                  </div>
                                                            <?php 
                                                                endif;
                                                              endif;
                                                            ?>

                                                            <!-- NONCAPPED ITEM-->
                                                            <?php 
                                                              if(!empty($sub_value[1])): 
                                                                if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1):
                                                            ?>
                                                                  <div class="checkbox" style="<?php echo $displayer; ?>">
                                                                    <label class="i-checks">
                                                                      <input type="hidden" name="respite_unique_ids[]" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                      <input type="checkbox" onclick="return false" checked <?php echo $checked_old_items ?> name="pickup_equipments_respite_<?php echo $respite_row['id']; ?>[]" class="pickup_equipments_respite" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                      <i></i>
                                                                      <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                      <?php 
                                                                        if($sub_key == "Hospital Bed")
                                                                        {
                                                                          echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                        }
                                                                        else
                                                                        {
                                                                          echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                        }
                                                                      ?>
                                                                    </label>
                                                                    <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                  </div>
                                                      <?php
                                                                endif; 
                                                              endif;
                                                            endif;  
                                                          endif;  
                                                        endforeach; 
                                                      ?>
                                                    </div>
                                  <?php 
                                                  endif;
                                                endforeach; 
                                  ?>
                                              </div>
                                  <?php 
                                            }
                                          }
                                        }
                                  ?>
                                      <input type="hidden" value="<?php echo $count_for_respite_pickup_all; ?>" id="count_for_respite_pickup_all">
                                      </div>
                                  <?php 
                                    }
                                  ?>
                                </div>

                                <?php /***************************************************************
                                       CODE FOR PICKUP ALL EQUIPMENT ENDS HERE 
                                      ****************************************************************/ ?>

                              </div> <!-- .row-->

                              <div id="hdn_original_act_id_pickup" name="">
                                <!-- Where hidden input for original activity type will appear -->
                              </div>

                              <div id="hdn_pickup_unique_div">
                                <!-- Where hidden input for uniqueID will appear -->
                              </div>

                              <div class="" style="margin-top:25px;">
                                <div class="">
                                  <label>Delivery Instructions</label>
                                  <textarea style="margin-top: 5px;" class="form-control " name="new_pickup_notes" placeholder="Delivery Instructions" ></textarea>
                                </div>
                              </div> 

                            </div> <!-- .panel-body -->
                          </div> <!-- .panel panel-default ng-scope -->
                        </div> <!-- .form-group -->
                        <input type="hidden" value="0" name="pickup_sign" id="pickup_sign">
                      </div> <!-- .col-sm-12 -->

                      <div class="col-sm-12" style="">
                        <div class="form-group" style="display:none; margin-right:-30px;" id="forpickup_categories4">
                          <button type="button" class="btn btn-success pull-right save_pickup_data" data-id="<?php echo $information['patientID'] ?>" value="" >Submit Activity</button>
                        </div>
                      </div>
                      <!-- *******(marj) PICKUP ITEMS end's here ******* -->

                      <div class="col-sm-12" style="margin-left: -12px;margin-top:10px">
                        <div class="form-group " style="display:none;" id="forexchange_categories3">
                          <div class="panel panel-default ng-scope">
                            <div class="panel-heading">
                              Item(s) to Exchange
                            </div>
                            <div class="panel-body">
                              <div class="row">

                                <?php 
                                /* 
                                  0=default address, 1=ptmove, 2=respite
                                */
                                $old_address = get_old_address_new($information['patientID']);
                                $patient_move_address = get_patient_move_address_new($information['patientID']);
                                $respite_address = get_respite_address_new($information['patientID']);
                                if(empty($patient_move_address) && empty($respite_address)){
                                ?>

                                  <input type="hidden" name="exchange_address" value="<?php echo $old_address['id'] ?>">
                                  <input type="hidden" id="exchange_address_id" name="exchange_address_id" value="<?php echo $old_address['id'] ?>"></input>

                                <?php }else{ ?>
                                  
                                  <div class="col-sm-12" id="address_to_exchange">
                                    <div class="col-sm-12" style="margin-left: -22px">
                                      <div class="form-group" style="margin-right:-30px;" id="forexchange_categorie5">
                                        <label style="margin-left:7px;"> Equipment Location <span class="text-danger-dker">*</span></label>
                                        <select name="exchange_address" class="form-control m-b" id="select_exchange_address" tabindex="9" style="margin-left:5px"> 
                                          <option value="">[-- Select Patient Address --]</option>
                                          <?php 
                                            if($old_address){
                                          ?>
                                              <optgroup label="Initial Address">
                                                <option value="<?php echo $old_address['id'] ?>"><?php echo $old_address['street'] .", ". $old_address['placenum'] .", ". $old_address['city'] .", ". $old_address['state'] .", ". $old_address['postal_code'] ?></option>
                                              </optgroup>
                                          <?php 
                                            }
                                            if($patient_move_address){ 
                                          ?>
                                              <optgroup label="Patient Move Address">
                                          <?php 
                                                foreach ($patient_move_address as $patient_move_row) {
                                          ?>
                                                  <option value="<?php echo $patient_move_row['id'] ?>"><?php echo $patient_move_row['street'] .", ". $patient_move_row['placenum'] .", ". $patient_move_row['city'] .", ". $patient_move_row['state'] .", ". $patient_move_row['postal_code'] ?></option>
                                          <?php
                                              } 
                                          ?>
                                              </optgroup> 
                                          <?php
                                            }
                                            if($respite_address){ 
                                          ?>
                                              <optgroup label="Respite Address">
                                          <?php
                                                foreach ($respite_address as $respite_row) {
                                          ?>
                                                  <option value="<?php echo $respite_row['id'] ?>"><?php echo $respite_row['street'] .", ". $respite_row['placenum'] .", ". $respite_row['city'] .", ". $respite_row['state'] .", ". $respite_row['postal_code'] ?></option>
                                          <?php 
                                              }
                                          ?>
                                              </optgroup> 
                                          <?php
                                            }
                                          ?>
                                        </select>
                                      </div> <!-- #forexchange_categorie5 -->
                                    </div> <!-- .col-sm-12 -->
                                  </div> <!-- .col-sm-12 -->
                                  <input type="hidden" id="exchange_address_id" name="exchange_address_id" value="0"></input>
                                  
                                <?php } ?>
                             
                                <div class="col-sm-12 address_equipment_col_exchange" <?php if(!empty($patient_move_address) || !empty($respite_address)){ ?> style="display:none;" <?php } ?> id="exchange_div_<?php echo $old_address['id']; ?>">
                                  <?php
                                  $categories_equip = array(1, 2);
                                  $includes = array("capped item", "non-capped item");
                                  $count = 0;
                                  $counter_exchange_div_first = 0;
                                  foreach ($orders_exchange as $keys => $equip_orders): 
                                    if(in_array(strtolower($keys), $includes)): 
                                      $counter_exchange_div_first++;
                                  ?>
                                      <div class="col-md-6">
                                        <label><?php echo $keys; ?></label>
                                        <?php 
                                        foreach ($equip_orders as $sub_key => $sub_value):
                                          $checked = "";
                                          $displayer = "";
                                          $count++;
                                          if($pickup_equipment != "")
                                          {
                                              if (in_array($sub_value[0]['equipmentID'], $pickup_equipment)) 
                                              {
                                                  $checked = "checked";
                                              }
                                          }
                                          if ($sub_value[0]['activity_typeid'] == 2) {
                                              $displayer = "display:none";
                                          } 
                                          else 
                                          {
                                              $displayer = "display:block";
                                          }
                                          ?>
                                          <!-- NEW PROCESS -->
                                          <?php 
                                          if(in_array($sub_value[0]['categoryID'], $categories_equip)):  
                                            if(isset($sub_value['children'])): 
                                              if(!empty($sub_value[0])) :  
                                                if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1) :
                                          ?>
                                                  <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                    <label class="i-checks">
                                                      <input type="checkbox" <?php echo $checked; ?> name="exchange_equipments[]" class="checked_item" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                      <i></i>
                                                      <?php 
                                                        if($sub_key == "Hospital Bed")
                                                        {
                                                          echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                        }
                                                        else
                                                        {
                                                          echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                        }
                                                      ?>
                                                    </label>
                                                    <ul>
                                                      <?php 
                                                        $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                        $order_oxygen = $sub_value[0]['orderID'] + 2;
                                                        $count=0;
                                                        foreach($item_options as $options)
                                                        {
                                                          $sub_equipmentID = $options['equipmentID'];
                                                          $sub_equipmentID_parentID = $options['parentID'];
                                                          $item_uniqueID = $sub_value[0]['uniqueID'];

                                                          if($options['input_type'] == "radio") 
                                                          {
                                                            echo "<input type='checkbox'  class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                            echo "</span><br />";
                                                          }
                                                          else if ($options['input_type'] == "text") 
                                                          {
                                                            $count++;
                                                            if($count == 1){
                                                              echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                              echo "</span><br />";
                                                            }
                                                          }
                                                          else if ($options['input_type'] == "checkbox") 
                                                          {
                                                            if($options['orderID']==$order_oxygen)
                                                            {
                                                              echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                              echo "</span><br />";
                                                            }
                                                          }
                                                        }
                                                      ?>
                                                    </ul>
                                                  </div>
                                          <?php 
                                                endif;
                                              endif;
                                              if(!empty($sub_value[1])) :  
                                                if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :
                                          ?>
                                                  <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                    <label class="i-checks">
                                                      <input type="checkbox" <?php echo $checked; ?> name="exchange_equipments[]" class="checked_item" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                      <i></i>
                                                      <?php 
                                                        if($sub_key == "Hospital Bed")
                                                        {
                                                          echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                        }
                                                        else
                                                        {
                                                          echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                        }
                                                      ?>     
                                                    </label>
                                                    <ul>
                                                      <?php 
                                                        $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                        $work_order = $sub_value[1]['uniqueID'];
                                                        $order_oxygen = $sub_value[1]['orderID'] + 2;
                                                        $count=0;
                                                        foreach($item_options as $options)
                                                        {
                                                          $sub_equipmentID = $options['equipmentID'];
                                                          $sub_equipmentID_parentID = $options['parentID'];
                                                          $item_uniqueID = $sub_value[1]['uniqueID'];

                                                          if($options['input_type'] == "radio") 
                                                          {
                                                            echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                            echo "</span><br />";
                                                          }
                                                          else if ($options['input_type'] == "text") 
                                                          {
                                                            $count++;
                                                            if($count == 1){
                                                              echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox'  name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                              echo "</span><br />";
                                                            }
                                                          }
                                                          else if ($options['input_type'] == "checkbox") 
                                                          {
                                                            if($options['orderID']==$order_oxygen)
                                                            {
                                                              echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                              echo "</span><br />";
                                                            }
                                                          }
                                                        }
                                                      ?>
                                                    </ul>
                                                    <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                  </div>
                                          <?php 
                                                endif; 
                                              endif;
                                            else: 
                                              if(!empty($sub_value[0])) :
                                                if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1) :
                                          ?>
                                                  <div class="checkbox" style="<?php echo $displayer; ?>">
                                                    <label class="i-checks">
                                                      <input type="checkbox" <?php echo $checked; ?> name="exchange_equipments[]" class="checked_item" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                      <i></i>
                                                      <?php 
                                                        if($sub_key == "Hospital Bed")
                                                        {
                                                          echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                        }
                                                        else
                                                        {
                                                          echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                        }
                                                      ?>   
                                                    </label>
                                                  </div>
                                          <?php 
                                                endif;
                                              endif;
                                              if(!empty($sub_value[1])) :
                                                if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :
                                          ?>
                                                  <div class="checkbox" style="<?php echo $displayer; ?>">
                                                    <label class="i-checks">
                                                      <input type="checkbox" <?php echo $checked; ?> name="exchange_equipments[]" class="checked_item" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                      <i></i>
                                                      <?php 
                                                        if($sub_key == "Hospital Bed")
                                                        {
                                                          echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                        }
                                                        else
                                                        {
                                                          echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                        }
                                                      ?> 
                                                    </label>
                                                  </div>
                                          <?php 
                                                endif;    
                                              endif;
                                            endif;  
                                          endif; 
                                          ?>
                                          <!-- END -->
                                        <?php 
                                        endforeach; 
                                        ?>
                                      </div>
                                  <?php 
                                    endif; 
                                  endforeach; 
                                  ?>
                                  <input type="hidden" class="exchange-counter" value="<?php echo $counter_exchange_div_first; ?>"></input> 
                                </div> <!-- .col-sm-12 -->

                                <?php 
                                /***************************************************************
                                 CODE FOR NEW ADDRESSES EQUIPMENT EXCHANGE STARTS HERE 
                                ****************************************************************/
                                
                                  $categories_equip = array(1, 2, 3);
                                  $includes = array("capped item", "non-capped item", "disposable items");
                                  $counter_exchange_div = 0;
                                  $my_count = 0;

                                  foreach ($new_orders_exchange as $new_orders_loop):
                                ?>
                                    <div class="col-sm-12 address_equipment_col_exchange" style="display:none;" id="exchange_div_<?php echo $addressID_exchange[$my_count]; ?>" >
                                      <?php
                                        foreach ($new_orders_loop as $keys=>$equip_orders):
                                          if(in_array(strtolower($keys), $includes)): 
                                            $counter_exchange_div++;
                                      ?>
                                            <div class="col-md-6" style="float:right;">
                                              <label style="margin-bottom:20px"><?php echo $keys; ?></label> 
                                              <?php 
                                                foreach ($equip_orders as $sub_key=>$sub_value): 
                                                  $checked = "";
                                                  $displayer = "";
                                                  $display_old_address = "";
                                                  $disabler = "";
                                                  if($pickup_equipment != "")
                                                  {
                                                      if (in_array($sub_value[0]['equipmentID'], $pickup_equipment) || in_array($sub_value[1]['equipmentID'], $pickup_equipment)) 
                                                      {
                                                          $checked = "checked";
                                                      }
                                                  }

                                                  if ($sub_value[0]['activity_typeid'] == 2 || $sub_value[0]['activity_reference'] == 2 && $sub_value[0]['order_status'] == 'cancel' && $sub_value[0]['canceled_from_confirming'] == 1 || $sub_value[1]['activity_typeid'] == 2 || $sub_value[1]['activity_reference'] == 2 && $sub_value[1]['order_status'] == 'cancel' && $sub_value[1]['canceled_from_confirming'] == 1) {
                                                      $displayer = "display:none";
                                                  } 
                                                  else 
                                                  {
                                                      $displayer = "display:block";
                                                  }

                                                  if(!empty($ptmove_address))
                                                  {
                                                    if($sub_value[0]['activity_typeid'] != 4)
                                                    {
                                                      $display_old_address = "display:block";
                                                      $checked_old_items = "checked";
                                                      $disabler = "return false";
                                                    }

                                                    else
                                                    {
                                                      $display_old_address = "display:none";
                                                      $checked_old_items = "";
                                                      $disabler = "";                                          
                                                    }
                                                  }
                                                  else
                                                  { 
                                                    $checked_old_items = "";
                                                  }
                                              ?>
                                              <!-- NEW PROCESS -->
                                              <?php 
                                                  if(in_array($sub_value[0]['categoryID'], $categories_equip)):  
                                                    if(isset($sub_value['children'])): 
                                                      if(!empty($sub_value[0])) :  
                                                        if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1) :
                                              ?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                            <label class="i-checks">
                                                              <input type="checkbox" name="exchange_equipments[]" class="checked_item" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                              <i></i>
                                                              <?php 
                                                                if($sub_key == "Hospital Bed")
                                                                {
                                                                  echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                                else
                                                                {
                                                                  echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                              ?> 
                                                            </label>
                                                            <ul>
                                                              <?php 
                                                                $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                                $order_oxygen = $sub_value[0]['orderID'] + 2;
                                                                $count=0;
                                                                foreach($item_options as $options)
                                                                {
                                                                  $sub_equipmentID = $options['equipmentID'];
                                                                  $sub_equipmentID_parentID = $options['parentID'];
                                                                  $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                  if($options['input_type'] == "radio") 
                                                                  {
                                                                    echo "<input type='checkbox'  class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                    echo "</span><br />";
                                                                  }
                                                                  else if ($options['input_type'] == "text") 
                                                                  {
                                                                    echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                    echo "</span><br />";
                                                                  }
                                                                  else if ($options['input_type'] == "checkbox") 
                                                                  {
                                                                    if($options['orderID']==$order_oxygen)
                                                                    {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                  }
                                                                }
                                                              ?>
                                                            </ul>
                                                          </div>
                                              <?php 
                                                        endif;
                                                      endif;
                                                      if(!empty($sub_value[1])) :  
                                                        if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :
                                              ?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                            <label class="i-checks">
                                                              <input type="checkbox" name="exchange_equipments[]" class="checked_item" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                              <i></i>
                                                              <?php 
                                                                if($sub_key == "Hospital Bed")
                                                                {
                                                                  echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                }
                                                                else
                                                                {
                                                                  echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                }
                                                              ?> 
                                                            </label>
                                                            <ul>
                                                              <?php 
                                                                $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                $work_order = $sub_value[1]['uniqueID'];
                                                                $order_oxygen = $sub_value[0]['orderID'] + 2;
                                                                $count=0;
                                                                foreach($item_options as $options)
                                                                {
                                                                  $sub_equipmentID = $options['equipmentID'];
                                                                  $sub_equipmentID_parentID = $options['parentID'];
                                                                  $item_uniqueID = $sub_value[1]['uniqueID'];

                                                                  if($options['input_type'] == "radio") 
                                                                  {
                                                                    echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                    echo "</span><br />";
                                                                  }
                                                                  else if ($options['input_type'] == "text") 
                                                                  {
                                                                    $count++;
                                                                    if($count == 1){
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox'  name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }
                                                                  }
                                                                  else if ($options['input_type'] == "checkbox") 
                                                                  {
                                                                    if($options['orderID']==$order_oxygen)
                                                                    {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                  }
                                                                }
                                                              ?>
                                                            </ul>
                                                            <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                          </div>
                                              <?php 
                                                        endif; 
                                                      endif;
                                                    else: 
                                                      if(!empty($sub_value[0])) :
                                                        if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1) :
                                              ?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>">
                                                            <label class="i-checks">
                                                              <input type="checkbox" name="exchange_equipments[]" class="checked_item" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                              <i></i>
                                                              <?php 
                                                                if($sub_key == "Hospital Bed")
                                                                {
                                                                  echo "Full Electric ".$sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                                else
                                                                {
                                                                  echo $sub_key." - ".$sub_value[0]['serial_num'];
                                                                }
                                                              ?> 
                                                            </label>
                                                          </div>
                                              <?php 
                                                        endif;
                                                      endif;
                                                      if(!empty($sub_value[1])) :
                                                        if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :
                                              ?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>">
                                                            <label class="i-checks">
                                                              <input type="checkbox" name="exchange_equipments[]" class="checked_item" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                              <i></i>
                                                              <?php 
                                                                if($sub_key == "Hospital Bed")
                                                                {
                                                                  echo "Full Electric ".$sub_key." - ".$sub_value[1]['serial_num'];
                                                                }
                                                                else
                                                                {
                                                                  echo $sub_key." - ".$sub_value[1]['serial_num'];
                                                                }
                                                              ?> 
                                                            </label>
                                                          </div>
                                              <?php 
                                                        endif;    
                                                      endif;
                                                    endif;  
                                                  endif; 
                                                endforeach;
                                              ?>
                                              <!-- END -->
                                              
                                            </div>
                                      <?php 
                                          endif;  
                                        endforeach; 
                                      ?>
                                      <!-- <button type="submit" class="btn btn-danger pull-right " style="margin-top:-55px;">Save changes</button> -->
                                    </div> <!-- .col-sm-12-->

                                <?php 
                                    $my_count++;
                                  endforeach;
                                
                                /***************************************************************
                                 CODE FOR NEW ADDRESSES EQUIPMENT EXCHANGE ENDS HERE 
                                ****************************************************************/
                                ?>
                                <input type="hidden" class="new-exchange-counter" value="<?php echo $counter_exchange_div; ?>"></input> 
                              </div> <!-- .row -->
                            <div> <!-- .panel-body -->
                          </div>
                          <div id="hdn_original_act_id" name="">
                            <!-- Where hidden input for original activity type will appear -->
                          </div>

                          <div id="hdn_exchange_unique_div">
                              <!-- Where hidden input for uniqueID will appear -->
                          </div>

                          <div class="form-group" style="display:block;margin-top:25px" id="forexchange_categories">
                            <label>Reason for Exchange <span class="text-danger-dker">*</span></label>
                            <textarea class="form-control" style="" name="exchange_reason"></textarea>
                          </div>

                          <div class="form-group" style="display:none;margin-top:15px" id="forexchange_categories2">
                            <label> Exchange Delivery Date <span class="text-danger-dker">*</span></label>
                            <input type="text" class="form-control datepicker" value="" placeholder="Date" name="exchange_date" style="margin:0px">
                          </div>
                          <div class="" style="margin-top:15px">
                            <div class="">
                              <label>Delivery Instructions</label>
                              <textarea style="margin-top: 5px;" class="form-control " name="new_exchange_notes" placeholder="Delivery Instructions" ></textarea>
                            </div>
                          </div> 
                        </div> <!-- .panel panel-default ng-scope -->
                      </div> <!-- .form-group -->
                    </div> <!-- .col-sm-12 -->
                  </div>
                  <div class="col-sm-12" style="">
                    <div class="form-group" style="display:none;" id="forexchange_categories4">
                      <button type="button" class="btn btn-success pull-right save_exchange_data" data-id="<?php echo $information['patientID'] ?>" value="">Submit Activity</button>
                    </div>
                  </div>

                  <!--  <div class="col-sm-6" id="hdn_submit_btn" style="display:none">
                    <button type="button" class="btn btn-success save_additional_btn" style="margin-left: -11px;margin-top: 25px;" class="form-control " name="" data-id="<?php echo $information['medical_record_id'] ?>">Submit</button>
                  </div> -->
                </div>

                <div class="col-sm-12" style="margin-top:6px">
                      

                      <!-- <div class="form-group pull-in clearfix" style="visibility:hidden">
                        <div class="col-sm-6">
                          <label>DIVIDER ONLY<span class="text-danger-dker"></span></label>
                          <input type="text" style="margin-top: 5px;width:508px" class="form-control " name=""  autocomplete="off" placeholder="" tabindex="6">
                        </div>
                      </div> -->

                      <div class="form-group pull-in clearfix forptmove_emergency_contact" style="margin-top:-15px !important;display:none">
                        <label><i>Emergency Contact <span class="text-danger-dker">*</span></i></label><br>
                        <div class="col-sm-6">
                          <label style="margin-bottom:0px">Next of Kin<span class="text-danger-dker">*</span></label>
                          <input type="text" style="margin-top: 5px;" class="form-control" name="ptmove_nextofkin"  autocomplete="off" placeholder="FULL NAME" tabindex="16">
                        </div>
                      </div>
                      <div class="form-group pull-in clearfix forptmove_emergency_contact" style="display:none">
                        <div class="col-sm-6">
                          <label style="margin-bottom:0px">Relationship<span class="text-danger-dker">*</span></label>
                          <input type="text" style="margin-top: 5px;" class="form-control" name="ptmove_nextofkinrelation"  autocomplete="off" placeholder="Relationship" tabindex="17">
                        </div>
                      </div>
                      <div class="form-group pull-in clearfix forptmove_emergency_contact" style="display:none">
                        <div class="col-sm-6">
                          <label style="margin-bottom:0px">Next of Kin Phone Number<span class="text-danger-dker">*</span></label>
                          <input type="text" style="margin-top: 5px;" class="form-control person_num" name="ptmove_nextofkinphone"  autocomplete="off" placeholder="NEXT OF KIN" tabindex="18">
                        </div>
                      </div>


                      <!--  <div class="form-group pull-in clearfix">
                      
                        <div class="col-sm-6">
                          <label>Patient Notes </label>
                          <textarea style="margin-top: 5px;width:508px" class="form-control " name="comment_notes" placeholder="Patient Notes" ></textarea>
                         
                        </div>
                      </div> -->


                     <!--  <div class="form-group pull-in clearfix">
                      
                        <div class="col-sm-6">
                          <label>Special Instructions</label>
                          <textarea style="margin-top: 5px;width:508px" class="form-control " name="new_order_notes" placeholder="Special Instructions" ></textarea>
                        </div>
                      </div> -->

                </div>

              </div>
            </div>


            <!-- Equipments panel -->
            <div class="panel panel-default equipment_section">
              <div class="panel-heading font-bold">
                Add New Item(s)
              </div>
              <div class="panel-body">

                <div class="col-md-8">
                <?php 
                if(!empty($equipments)) : 
                  $count = 1; 
                  foreach ($equipments as $equipment) :
                ?>
                    <div class="form-group col-md-12 wrapper-equipment" data-value="<?php echo $equipment['categoryID'] ?>" id="wrapper_equip_<?php echo $equipment['categoryID'] ?>">
                      <label class="btn btn-default data_tooltip" title="Click to Add New Item(s)" style="margin-bottom:20px;margin-top:20px;"  id="equip_<?php echo $equipment['categoryID'] ?>"><?php echo $equipment['type'] ?></label> <br>
                      <div class="equipment" style="display:none;">
                        
                        <label style="margin-top:10px;visibility:hidden"><?php echo $equipment['type'] ?> <span class="text-danger-dker">*</span></label>
                        <div class="col-md-4" style="padding-left:15px;">
                          <?php 
                          foreach ($equipment['children'] as $key => $child) : 
                            if($child['equipmentID'] != 55 && $child['equipmentID'] != 20)
                            {
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
                                         data-reference-id="<?php echo $child['noncapped_reference']; ?>"
                                         class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID'] ?>">
                                  <i></i>
                                  <?php echo $child['key_desc'] ?>
                                </label>
                              </div>
                          <?php
                            }
                            else
                            {
                              $temp = '<div class="checkbox"> <label class="i-checks"><input type="checkbox" id="" value="'.$child['equipmentID'].'"name="equipments[]" data-target="#'.trim($child['key_name']).'_'.$equipment['categoryID'].'" data-name="'.trim($child['key_name']).'" data-desc="'.trim($child['key_desc']).'" data-value="'.$child['key_desc'].'" data-category="'.$equipment['type'].'" data-category-id="'.$equipment['categoryID'].'" class="checkboxes c-'.trim($child['key_name']).'-'.$equipment['categoryID'].'"/> <i></i> Full Electric '.$child['key_desc'].' </label></div>';
                            }
                            if ($key == $equipment['division'] - 1){
                              break;
                            }
                          endforeach; 
                          if($count == 1)
                          {
                          ?>
                            <script type="text/javascript">
                              $(document).ready(function(){
                                var elem = $("body").find(".c-front_wheel_walker-1").parent(".i-checks").parent(".checkbox");
                                var hospital_bed = <?php echo json_encode($temp); ?>;
                                elem.append(hospital_bed);
                              });
                            </script>
                          <?php
                          }
                          else if($count == 2)
                          {
                          ?>
                            <script type="text/javascript">
                              $(document).ready(function(){
                                var elem = $("body").find(".c-front_wheel_walker_bariatric-2").parent(".i-checks").parent(".checkbox");
                                var hospital_bed = <?php echo json_encode($temp); ?>;
                                elem.append(hospital_bed);
                              });
                            </script>
                          <?php 
                          }
                          ?>
                        </div> <!-- .col-md-4 -->
                        <div class="col-md-4" style="padding-left:15px;" id="">
                          <?php 
                          for($i = $equipment['division']; $i <= $equipment['last']; $i++) : 
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
                                       data-reference-id="<?php echo $child['noncapped_reference']; ?>"
                                       class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID'] ?>">
                                <i></i>
                                <?php echo $child['key_desc'] ?>
                              </label>
                            </div>
                     
                          <?php endfor; ?>
                        </div> <!-- .col-md-4 -->
                      </div> <!-- .equipment -->
                    </div> <!-- .wrapper-equipment -->
                <?php 
                    $count++;
                  endforeach; 
                endif; 
                ?>
  
                <div class="col-sm-9" style="padding-left:0px;">
                  <div class="form-group">
                    <!-- special instructions -->
                    <label>Delivery Instructions </label>
                    <textarea class="form-control" name="new_order_notes" style=""></textarea>
                  </div>
                </div> <!-- .col-sm-9 -->
              </div> <!-- .panel-body -->

              <div class="col-md-4 " style="">
                <div class="panel panel-default" style="margin-top: 15px;margin-left: 65px;">
                  <div class="panel-heading font-bold">
                    <img src="<?php echo base_url()?>assets/img/shopping_cart.png" class="col-sm-offset-4" style="width: 35px;height: 29px;" /> Cart
                  </div>
                  <div class="panel-body order-cont">
                  </div>
                </div>
              </div>

              <div class="clearfix"></div>

              <!-- submit Order -->
              <div class="clearfix"></div>
              <button type="button" class="btn btn-success pull-right save_additional_btn" data-id="<?php echo $information['patientID'] ?>" id="default_order_btn">Submit Activity</button>
              <button type="button" class="btn btn-success pull-right save_additional_btn_ptmove" style="display:none" data-id="<?php echo $information['patientID'] ?>" id="default_order_btn_ptmove">Submit Activity</button>
              <button type="button" class="btn btn-success pull-right save_additional_btn_respite" style="display:none" data-id="<?php echo $information['patientID'] ?>" id="default_order_btn_respite">Submit Activity</button>
              <?php $id = $this->session->userdata('userID'); ?>
              <input type="hidden" name="person_who_ordered" value="<?php echo $id; ?>" />
              <!-- <input type="hidden" value="<?php echo $information['pickup_date'] ?>" name="pickup_date" /> -->
              <input type="hidden" value="<?php echo $information['activity_typeid'] ?>" name="activity_typeid" />
              <?php 
                if(!empty($information['organization_id']))
                { ?>
                <input type="hidden" value="<?php echo $information['organization_id'] ?>" name="organization_id" />
              <?php  } else { ?>
                <input type="hidden" value="<?php echo $information['ordered_by'] ?>" name="organization_id" />
              <?php } ?>
              <input type="hidden" value="<?php echo $information['ordered_by'] ?>" name="ordered_by" />
              <!-- <input type="hidden" value="<?php echo $information['who_ordered_fname'] ?>" name="who_ordered_fname" />
              <input type="hidden" value="<?php echo $information['who_ordered_lname'] ?>" name="who_ordered_lname" />
              <input type="hidden" value="<?php echo $information['who_ordered_email'] ?>" name="who_ordered_email" />
              <input type="hidden" value="<?php echo $information['who_ordered_cpnum'] ?>" name="who_ordered_cpnum" /> -->
              <!-- <input type="hidden" value="<?php echo $information['comment'] ?>" name="comment" /> -->
              <input type="hidden" value="<?php echo $information['date_ordered'] ?>" name="date_ordered" />
              <input type="hidden" value="<?php echo $information['uniqueID'] ?>" name="uniqueID" />
              <input type="hidden" value="<?php echo $information['deliver_to_type'] ?>" name="delivery_to_type" />
              <input type="hidden" value="<?php echo $information['medical_record_id'] ?>" name="medical_record_id" />
              <?php $id = $this->session->userdata('userID'); ?>
              <input type="hidden" name="person_who_ordered" value="<?php echo $id; ?>" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-light lter wrapper-md">
      <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
    </div>

    <!-- Modal for Oxygen concentrator -->
    <div class="modal fade modal_oxygen_concentrator_1" id="oxygen_concentrator_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:-25px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
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
                                        <input type="text" data-desc="Liter Flow" name="subequipment[61][77]" class="form-control liter_flow_field" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:10px;">
                                    </div>

                                    <label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
                                    <div class="checkbox ">
                                        <label class="i-checks">
                                            <input type="checkbox"  class="5_ltr" data-desc="Oxygen Concentrator Type" data-value="5 LPM" name="subequipment[61][80]" id="optionsRadios1" value="5" >
                                            <i></i>5 LPM
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label class="i-checks">
                                            <input type="checkbox" class="10_ltr" data-desc="Oxygen Concentrator Type" data-value="10 LPM"name="subequipment[61][81]" id="optionsRadios1" value="10" >
                                            <i></i>10 LPM
                                        </label>
                                    </div>

                                    <label>Oxygen E Portable System <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Oxygen E Portable System" data-value="Yes" name="subequipment[61][radio][eps]" id="e_portable_yes_1" value="241" >
                                           <i></i>Yes
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Oxygen E Portable System"  data-value="No" name="subequipment[61][radio][eps]" id="e_portable_no_2" value="242" >
                                            <i></i>No
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[61][radio][]" id="optionsRadios1" value="78" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[61][radio][]" id="optionsRadios1" value="79" >
                                            <i></i>PRN
                                        </label>
                                    </div>
                                    
                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device" data-value="Nasal Cannula" name="subequipment[61][radio][flt]" id="flowtype" value="82" >
                                            <i></i>Nasal Cannula
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" class="oxygen_mask_capped" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[61][radio][flt]" id="optionsRadios1" value="83" >
                                            <i></i>Oxygen Mask
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device"  data-value="None" name="subequipment[61][radio][flt]" id="optionsRadios1" value="280" >
                                            <i></i>None
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
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type" data-value="With Bag" name="subequipment[62][radio][]" id="optionsRadios1" value="197">
                                            <i></i>With Bag
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type" data-value="Without Bag" name="subequipment[62][radio][]" id="optionsRadios1" value="198">
                                            <i></i>Without Bag
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
                                        <input type="text" data-desc="Liter Flow" name="subequipment[174][189]" class="form-control e_portable_qty_1" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
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

                                <label>Type of Rails <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Full Rails" name="subequipment[19][radio][]" id="optionsRadios1" value="129" >
                                        <i></i>Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Half Rails" name="subequipment[19][radio][]" id="optionsRadios1" value="130" >
                                        <i></i>Half Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="No Rails"  name="subequipment[19][radio][]" id="optionsRadios1" value="131" >
                                        <i></i>No Rails
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



     <!-- Modal hi-low extra long -->
    <div class="modal fade modal_hi_low_full_electric_hospital_bed_extra_long_2" id="hi_low_full_electric_hospital_bed_extra_long_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hi-Low Full Electric Hospital Bed (Extra Long)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Rails <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Full Rails" name="subequipment[286][radio][]" id="optionsRadios1" value="287" >
                                        <i></i>Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Half Rails" name="subequipment[286][radio][]" id="optionsRadios1" value="288" >
                                        <i></i>Half Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="No Rails"  name="subequipment[286][radio][]" id="optionsRadios1" value="289" >
                                        <i></i>No Rails
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




     <!-- Modal hospital bed extra long -->
    <div class="modal fade modal_hospital_bed_extra_long_2" id="hospital_bed_extra_long_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital Bed Extra Long</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Rails <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Full Rails" name="subequipment[282][radio][]" id="optionsRadios1" value="283" >
                                        <i></i>Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Half Rails" name="subequipment[282][radio][]" id="optionsRadios1" value="284" >
                                        <i></i>Half Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="No Rails"  name="subequipment[282][radio][]" id="optionsRadios1" value="285" >
                                        <i></i>No Rails
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
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Gastric Drainage Type" data-value="Continuous" name="subequipment[16][radio][]" id="optionsRadios1" value="122">
                                        <i></i>Continuous
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Gastric Drainage Type" data-value="Intermittant" name="subequipment[16][radio][]" id="optionsRadios1" value="123">
                                        <i></i>Intermittant
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
                                    <label class="i-checks">
                                        <input type="radio" class="aero_mask_capped" data-desc="Aerosol Mask"  data-value="Yes" name="subequipment[67][radio][]" id="optionsRadios1" value="90">
                                        <i></i>Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Aerosol Mask"  data-value="No" name="subequipment[67][radio][]" id="optionsRadios1" value="91">
                                        <i></i>No
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
    <div class="modal fade modal_reclining_wheelchair_1" id="reclining_wheelchair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Reclining Wheelchair" data-value='16" Narrow'  name="subequipment[269][radio][trw]" id="optionsRadios1" value="270">
                                       <i></i>16" Narrow
                                    </label> 
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" 
                                               data-desc="Type of Reclining Wheelchair" data-value='18" Standard'
                                               name="subequipment[269][radio][trw]" id="optionsRadios1" value="271">
                                       <i></i>18" Standard
                                    </label>
                                </div>

                                <label style="margin-top: 20px;">Type of Legrest<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Legrest" data-value='Elevating Legrests'
                                               name="subequipment[269][radio][tol]" id="optionsRadios1" value="272" checked>
                                        <i></i>Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" 
                                               data-desc="Type of Legrest" data-value='Footrests'
                                               name="subequipment[269][radio][tol]" id="optionsRadios1" value="273" >
                                        <i></i>Footrests
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
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Reclining Wheelchair" data-value='16" Narrow'  name="subequipment[64][radio][trw]" id="optionsRadios1" value="84">
                                       <i></i>16" Narrow
                                    </label> 
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" 
                                               data-desc="Type of Reclining Wheelchair" data-value='18" Standard'
                                               name="subequipment[64][radio][trw]" id="optionsRadios1" value="85">
                                       <i></i>18" Standard
                                    </label>
                                </div>

                                <label style="margin-top: 20px;">Type of Legrest <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Legrest" data-value='Elevating Legrests'
                                               name="subequipment[64][radio][tol]" id="optionsRadios1" value="86" checked>
                                        <i></i>Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" 
                                               data-desc="Type of Legrest" data-value='Footrests'
                                               name="subequipment[64][radio][tol]" id="optionsRadios1" value="87" >
                                        <i></i>Footrests
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
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Shower Chair" data-value="With Back" name="subequipment[66][radio][]" id="optionsRadios1" value="88">
                                        <i></i>With Back
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Shower Chair" data-value="Without Back" name="subequipment[66][radio][]" id="optionsRadios1" value="89">
                                        <i></i>Without Back
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
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="With Tray" name="subequipment[54][radio][]" id="optionsRadios1" value="192">
                                       <i></i>With Tray
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="Without Tray" name="subequipment[54][radio][]" id="optionsRadios1" value="193">
                                        <i></i>Without Tray
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

  
  <!-- Modal Geri chair NONCAPPED -->
    <div class="modal fade modal_geri_chair_3_position_with_tray_2" id="geri_chair_3_position_with_tray_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="With Tray" name="subequipment[17][radio][]" id="optionsRadios1" value="238">
                                        <i></i>With Tray
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="Without Tray" name="subequipment[17][radio][]" id="optionsRadios1" value="239">
                                        <i></i>Without Tray
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
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='16" Narrow' name="subequipment[71][radio][]" id="optionsRadios1" value="92" >
                                        <i></i>16" Narrow
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='18" Standard' name="subequipment[71][radio][]" id="optionsRadios1" value="93" >
                                        <i></i>18" Standard
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='20" Wide' name="subequipment[71][radio][]" id="optionsRadios1" value="94" >
                                        <i></i>20" Wide
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='22" Extra Wide' name="subequipment[71][radio][]" id="optionsRadios1" value="95" >
                                        <i></i>22" Extra Wide
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='24" Bariatric' name="subequipment[71][radio][]" id="optionsRadios1" value="96" >
                                        <i></i>24" Bariatric
                                    </label>
                                </div>

                                <br>
                                <label>Type of Legrest <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Legrest" data-value='Elevating Legrests' name="subequipment[71][radio][2]" id="optionsRadios1" value="97" >
                                        <i></i>Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Legrest" data-value='Footrests' name="subequipment[71][radio][2]" id="optionsRadios1" value="98" checked>
                                        <i></i>Footrests
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Full Electric Hospital Bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <!-- <label>Type of Hospital Bed <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Full Electric" name="subequipment[55][radio][]" id="optionsRadios1" value="72" >
                                        <i></i>Full Electric
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Semi Electric" name="subequipment[55][radio][]" id="optionsRadios1" value="73" >
                                        <i></i>Semi Electric
                                    </label>
                                </div>

                                <br><br> -->


                                <label>Type of Rails<span style="color:red;">*</span></label>
                                
                                <?php if($organization_id != 15) :?>
                                  <div class="radio">
                                      <label class="i-checks">
                                          <input type="radio" data-desc="Type of Rails" data-value="Full Rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="74" >
                                          <i></i>Full Rails
                                      </label>
                                  </div>
                                <?php endif;?>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Rails" data-value="Half Rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="75" >
                                        <i></i>Half Rails
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Rails" data-value="No rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="76" >
                                        <i></i>No Rails
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


    <!-- Floor Mat CAPPED -->
    <!--  <div class="modal fade modal_floor_mat_1" id="floor_mat_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Floor Mat</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of Floor Mat <span style="color:red;">*</span></label>
                                    <input type="text"  data-desc="Quantity" class="form-control" placeholder="ex. 1" name="subequipment[206][278]">
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
    </div> -->


    <!-- Floor Mat NONCAPPED -->
     <div class="modal fade modal_floor_mat_2" id="floor_mat_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Floor Mat</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of Floor Mat <span style="color:red;">*</span></label>
                                    <input type="text"  data-desc="Quantity" class="form-control" placeholder="ex. 1" name="subequipment[14][279]">
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
    <div class="modal fade modal_e-cylinder_3" id="e-cylinder_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen E-Cylinder</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of Oxygen E-Cylinder <span style="color:red;">*</span></label>
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
    <div class="modal fade modal_cylinder_m6_3" id="cylinder_m6_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen M6 Cylinder</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of Oxygen M6 Cylinder <span style="color:red;">*</span></label>
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


    <!-- Modal Oxygen Liquid -->
    <!-- <div class="modal fade modal_oxygen_liquid_refill_3" id="oxygen_liquid_refill_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Liquid Refill</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of Oxygen Liquid Refill <span style="color:red;">*</span></label>
                                    <input type="text"  data-desc="Quantity" class="form-control" placeholder="ex. 1" name="subequipment[178][290]">
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
    </div> -->


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
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Extended? NC" data-value="Yes" name="subequipment[2][radio][]" id="optionsRadios1" value="107" >
                                        <i></i>Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Extended? NC" data-value="No" name="subequipment[2][radio][]" id="optionsRadios1" value="108" >
                                        <i></i>No
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Full Electric Hospital Bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <!-- <label>Type of Hospital Bed <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Full Electric" name="subequipment[20][radio][]" id="optionsRadios1" value="134" >
                                        <i></i>Full Electric
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Semi Electric" name="subequipment[20][radio][]" id="optionsRadios1" value="135" >
                                        <i></i>Semi Electric
                                    </label>
                                </div> -->




                                <label style="margin-top: 10px;">Type of Rails<span style="color:red;">*</span></label>
                               
                                <?php if($organization_id != 15) :?>
                                  <div class="radio">
                                      <label class="i-checks">
                                          <input type="radio" data-desc="Type of Rails" data-value="Full Rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="136" >
                                          <i></i>Full Rails
                                      </label>
                                  </div>
                                <?php endif;?>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Rails" data-value="Half Rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="137" >
                                        <i></i>Half Rails
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Rails" data-value="No rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="138" >
                                        <i></i>No Rails
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
            <div class="modal-content" style="margin-top:-25px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
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
                                        <input type="text" data-desc="Liter Flow" name="subequipment[29][100]" class="form-control liter_flow_field" id="liter_flow_field_2" placeholder="Enter Liter Flow" style="margin-bottom:10px;">
                                    </div>

                                    <label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
                                    <div class="checkbox">
                                        <label class="i-checks">
                                            <input type="checkbox" class="5_ltr" data-desc="Oxygen Concentrator Type" data-value="5 LPM" name="subequipment[29][101]" id="optionsRadios1" value="5" >
                                            <i></i>5 LPM
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label class="i-checks">
                                            <input type="checkbox" class="10_ltr" data-desc="Oxygen Concentrator Type" data-value="10 LPM"name="subequipment[29][102]" id="optionsRadios1" value="10" >
                                            <i></i>10 LPM
                                        </label>
                                    </div>

                                    <label>Oxygen E Portable System <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Oxygen E Portable System" data-value="Yes" name="subequipment[29][radio][eps]" id="e_portable_yes_2" value="243" >
                                            <i></i>Yes
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Oxygen E Portable System"  data-value="No" name="subequipment[29][radio][eps]" id="e_portable_no_2" value="244" >
                                            <i></i>No
                                        </label>
                                    </div>

                                </div>

                                <div class="col-md-6">


                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[29][radio][]" id="optionsRadios1" value="103" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[29][radio][]" id="optionsRadios1" value="104" >
                                            <i></i>PRN
                                        </label>
                                    </div>
                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device" data-value="Nasal Cannula" name="subequipment[29][radio][flt]" id="flowtype" value="105" >
                                            <i></i>Nasal Cannula
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" class="oxygen_mask_noncapped" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[29][radio][flt]" id="optionsRadios1" value="106" >
                                            <i></i>Oxygen Mask
                                        </label>
                                    </div>


                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device"  data-value="None" name="subequipment[29][radio][flt]" id="optionsRadios1" value="281" >
                                            <i></i>None
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
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="Continuous" name="subequipment[36][radio][]" id="optionsRadios1" value="202" >
                                            <i></i>Continuous
                                        </label>
                                    </div> 
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[36][radio][]" id="optionsRadios1" value="203" >
                                            <i></i>PRN 
                                        </label>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device" data-value="Nasal Cannula" name="subequipment[36][radio][flt]" id="" value="204" >
                                            <i></i>Nasal Cannula
                                        </label>
                                    </div>  

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[36][radio][flt]" id="optionsRadios1" value="205" >
                                            <i></i>Oxygen Mask
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
    <div class="modal fade modal_oxygen_conserving_device_2" id="oxygen_conserving_device_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type" data-value="With Bag" name="subequipment[31][radio][]" id="optionsRadios1" value="199">
                                            <i></i>With Bag
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type" data-value="Without Bag" name="subequipment[31][radio][]" id="optionsRadios1" value="200">
                                            <i></i>Without Bag
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
    <div class="modal fade modal_oxygen_e_portable_system_2" id="oxygen_e_portable_system_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                        <input type="text" data-desc="Liter Flow" name="subequipment[176][191]" class="form-control e_portable_qty_2" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
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
    <div class="modal fade modal_oxygen_concentrator_portable_2" id="oxygen_concentrator_portable_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator Portable</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[30][240]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
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
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Shower chair" data-value="With Back" name="subequipment[39][radio][]" id="optionsRadios1" value="112">
                                        <i></i>With Back
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Shower chair" data-value="Without Back" name="subequipment[39][radio][]" id="optionsRadios1" value="113">
                                        <i></i>Without Back
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
                                    <label class="i-checks">
                                        <input type="radio" class="aero_mask_noncapped"  data-desc="Aerosol Mask"  data-value="Yes" name="subequipment[40][radio][]" id="optionsRadios1" value="115">
                                        <i></i>Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Aerosol Mask"  data-value="No" name="subequipment[40][radio][]" id="optionsRadios1" value="116">
                                        <i></i>No
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
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='16" Narrow' name="subequipment[49][radio][]" id="optionsRadios1" value="124" >
                                        <i></i>16" Narrow
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='18" Standard' name="subequipment[49][radio][]" id="optionsRadios1" value="125" >
                                        <i></i>18" Standard
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='20" Wide' name="subequipment[49][radio][]" id="optionsRadios1" value="126" >
                                        <i></i>20" Wide
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='22" Extra Wide' name="subequipment[49][radio][]" id="optionsRadios1" value="127" >
                                        <i></i>22" Extra Wide
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='24" Bariatric' name="subequipment[49][radio][]" id="optionsRadios1" value="128" >
                                        <i></i>24" Bariatric
                                    </label>
                                </div>


                                <label>Type of Legrest<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Legrest" data-value='Elevating Legrests' name="subequipment[49][radio][2]" id="optionsRadios1" value="132" >
                                        <i></i>Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Legrest" data-value='Footrests' name="subequipment[49][radio][2]" id="optionsRadios1" value="133" checked>
                                        <i></i>Footrests
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



    <!-- Modal Oxygen cylinder rack -->
    <div class="modal fade modal_oxygen_cylinder_rack_2" id="oxygen_cylinder_rack_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Cylinder Rack</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Oxygen Cylinder Rack <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="E Cylinder - 6 Rack" name="subequipment[32][radio][]" id="optionsRadios1" value="297" >
                                        <i></i>E Cylinder - 6 Rack
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="E Cylinder - 12 Rack" name="subequipment[32][radio][]" id="optionsRadios1" value="298" >
                                        <i></i>E Cylinder - 12 Rack
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="M6 Cylinder - 6 Rack" name="subequipment[32][radio][]" id="optionsRadios1" value="299" >
                                        <i></i>M6 Cylinder - 6 Rack
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="M6 Cylinder - 12 Rack" name="subequipment[32][radio][]" id="optionsRadios1" value="300" >
                                        <i></i>M6 Cylinder - 12 Rack
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Adult Nasal Cannula W/7' Tubing</h4>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Anti Tipper</h4>
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
    <div class="modal fade modal_corrugated_tubing_7ft_3" id="corrugated_tubing_7ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Enteral Feeding Bag (Compat)</h4>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">High Flow Oxygen Humidifier Bottle</h4>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Nebulizer Kit (W/Mouthpiece)</h4>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Mask, Oxygen Non-Rebreather Adult W/7' Tubing</h4>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Tubing Connector</h4>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Humidifier Bottle</h4>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Mask, Oxygen Adult W/7' Tubing</h4>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Tubing 21FT</h4>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Tubing 7FT</h4>
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


    <!--O2 Tubing 7FT-->
    <div class="modal fade modal_oxygen_tubing_50ft_3" id="oxygen_tubing_50ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Tubing 50FT</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[245][246]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Pressure Line Adaptor</h4>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Mask, Oxygen Trach Adult W/7' Tubing</h4>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Mask, Oxygen Venturi (Vent) Adult W/7' Tubing</h4>
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



     <!--Aerosol Drainage Bag-->
    <div class="modal fade modal_aerosol_drainage_bag_3" id="aerosol_drainage_bag_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Aerosol Drainage Bag</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[267][268]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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


     <!--cpap_mask_large-->
    <div class="modal fade modal_cpap_mask_large_3" id="cpap_mask_large_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Mask Large</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[257][258]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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



     <!--CPAP Mask Medium-->
    <div class="modal fade modal_cpap_mask_medium_3" id="cpap_mask_medium_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Mask Medium</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[255][256]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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



     <!--CPAP Full Face Mask Large-->
    <div class="modal fade modal_cpap_full_face_mask_large_3" id="cpap_full_face_mask_large_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Full Face Mask Large</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[261][262]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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


     <!--CPAP Full Face Mask Medium-->
    <div class="modal fade modal_cpap_full_face_mask_medium_3" id="cpap_full_face_mask_medium_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Full Face Mask Medium</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[251][252]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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


     <!--CPAP Full Face Mask Small-->
    <div class="modal fade modal_cpap_full_face_mask_small_3" id="cpap_full_face_mask_small_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Full Face Mask Small</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[249][250]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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


     <!--CPAP Mask Small-->
    <div class="modal fade modal_cpap_mask_small_3" id="cpap_mask_small_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Mask Small</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[253][254]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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


     <!--CPAP Mask Small-->
    <div class="modal fade modal_cpap_tubing_7ft_3" id="cpap_tubing_7ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Tubing 7ft</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[259][260]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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


    <!--commode_pail-->
    <div class="modal fade modal_commode_pail_3" id="commode_pail_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Commode Pail</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[7][310]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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

    <!--oxygen_liquid_refill-->
    <div class="modal fade modal_oxygen_liquid_refill_3" id="oxygen_liquid_refill_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Liquid Refill</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[178][311]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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

    <!--wheelchair_2_inches_gel_cushion-->
    <div class="modal fade modal_wheelchair_2_inches_gel_cushion_3" id="3inch_wheelchair_gel_cushion_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair 2 inches Gel Cushion</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[1][309]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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

     <!--oxygen_tubing_water_trap-->
    <div class="modal fade modal_enteral_feeding_bag_kangaroo_3" id="enteral_feeding_bag_kangaroo_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Enteral Feeding Bag (Kangaroo)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[247][248]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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


    <!--Nasal Cannula EZ Wrap Ear Cushion-->
    <div class="modal fade modal_nasal_cannula_ez_wrap_ear_cushion_3" id="nasal_cannula_ez_wrap_ear_cushion_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Nasal Cannula EZ Wrap Ear Cushion</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[265][266]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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



    <!--oxygen_tubing_water_trap-->
    <div class="modal fade modal_oxygen_tubing_water_trap_3" id="oxygen_tubing_water_trap_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Tubing Water Trap</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[263][264]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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




    <!--enteral_feeding_bag_(1000_ml_joey_pump_sets)-->
    <div class="modal fade modal_enteral_feeding_bag_1000_ml_joey_pump_sets_3" id="enteral_feeding_bag_1000_ml_joey_pump_sets_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Enternal Feeding Bag (1000 ML Joey Pump Sets)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[274][275]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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


    <!--miscellaneous-->
    <div class="modal fade modal_miscellaneous_3" id="miscellaneous_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Miscellaneous</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Item Description <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Item Desc." name="subequipment[306][307]" class="form-control " id="exampleInputEmail1" placeholder="Description here" style="margin-bottom:31px;">
                                    </label>
                                </div>


                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[306][308]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
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
 
<?php endif;?>


<!-- Modal for the duplicate capped item -->
<div class="modal fade" id="duplicate_capped_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header" style="border-bottom:0px !important">
        <h4 class="modal-title"><img src="<?php echo base_url()?>assets/img/caution_sign.png" style="width:70px;margin-left:44%" /></h4>
      </div>
      <div class="modal-body">
        <p class="label label-danger" style="font-size:20px;margin-left:33%">Item already exist!</p><br /><br />
        <p class="" style="font-size:25px;margin-left:16%">If approved, item will be Non-Capped!</p>
      <div class="modal-footer" style="border-top:0px !important"> 
          <button type="button" class="btn btn-info btn-approve-choice" data-dismiss="modal">Approve</button>
          <button type="button" class="btn btn-danger btn-close-alert" data-dismiss="modal">Cancel</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php /*
<div class="col-md-12">
<table class="table table-striped bg-white b-a" id="equipment_summary_tbl">
        <thead>
          <tr>
            <th style="width: 40px">WO#</th>
            <!-- <th style="width: 40px">Date Ordered</th> -->
            <th style="width: 60px">Order Date</th>
            <th style="width: 90px">Activity Type</th>
            <th style="width: 60px">Item #</th>
            <th style="width: 90px">Item Description</th>
            <th style="width: 90px">Qty.</th>
            <th style="width: 90px">Serial #</th>
            <th style="width: 90px">Lot #</th>
            <th style="width: 90px">Type</th>
            <th style="width: 60px">Picked up</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($orders as $key=>$value) :?>
            <?php foreach($value as $sub_key=>$sub_value) :?>
              <?php $info = $sub_value[0] ;?>

                <tr>
                  <!-- <td style="width:auto"><?php echo date("m/d/Y", strtotime($info['pickup_date'])) ?></td> -->
                  <td><a href="javascript:void(0)" data-toggle="modal" data-target="#view_wo_modal<?php echo $info['uniqueID'] ?>" style=""><?php echo $info['uniqueID'] ?></a></td>
                  <td style="width:auto"><?php echo date("m/d/Y", strtotime($info['date_ordered'])) ?></td>
                  <td><?php echo $info['activity_name'] ?></td>
                  <td>
                      <?php echo $info['item_num'] ?>
                  </td>
                  <td style="width:auto"><?php echo $info['key_desc'] ?></td>
                  <td style="width:auto">1</td>
                  
                  <td>
                      <?php echo $info['serial_num'] ?>
                  </td>
                  <td style="width:160px !important">
                      <?php echo $info['lot_num'] ?>
                      
                  </td>
                  <td>
                    <?php if($info['type'] == 'Capped Item') :?>
                      <p class="label label-success"><?php echo $info['type'] ?></p>
                    <?php else:?>
                       <p class="label label-warning"><?php echo $info['type'] ?></p>
                    <?php endif;?>
                  </td>
                  
                   <td>
                    <?php if($info['summary_pickup_date'] != '0000-00-00') :?>
                      <?php echo date("m/d/Y", strtotime($info['summary_pickup_date'])) ?>
                    <?php endif;?>
                  </td>
                
                  <input type="hidden" name="hdn_unique_id" value="<?php echo $info['uniqueID'] ?>" class="hdn_unique_id" />
                  <input type="hidden" name="hdn_medical_record_id" value="<?php echo $info['medical_record_id'] ?>" class="hdn_equip_id" />
                </tr>

            <?php endforeach ;?>
          <?php endforeach ;?>
        </tbody>
    </table>   
</div>
*/?>

<style type="text/css">
            
  .status-count.status-count-bot{
    margin-left: 30%;
  }
  .status-count li{
    padding-right: 30px;
    font-weight: normal;
  }

</style>