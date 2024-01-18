<div class="wrapper-md" ng-controller="FormDemoCtrl">
<div class="row">
<div class="col-sm-12">

<?php
  $organization_id = $this->session->userdata('group_id');
?>


<?php if (!empty($informations)) : ?>
<?php $information = $informations[0];  ?>


<?php /** This is the function to disable PT Move/Respite when there are still items to pickup from previous activity type **/ ?>
<?php 

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

?>


<?php 
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
  <?php $hospice_id = $information['organization_id'] ;?>
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


<?php echo form_open("",array("id"=>"add_additional_equipment_form")) ;?>

  <div class="panel panel-default">
    <div class="panel-heading font-bold clearfix">
      <div class="pull-left">Activity Process</div> 
      <div class="pull-right">
          <div class="pull-right">
          <style type="text/css">
  
  .status-count.status-count-bot{
  margin-left: 30%;
}
.status-count li{
  padding-right: 30px;
  font-weight: normal;
}

</style>
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
          }
          $index=0;
      ?>
      <ul class="status-count" style="list-style-type:none;">
                <?php foreach($activity_counts as $key=>$value): ?>
                  <?php if($value>0): ?>
                        <li class="pull-left">
                             <span><?php echo $label[$key+1]; ?></span>&nbsp;
                             <span><strong><?php echo $value; ?></strong></span>
                        </li>
                  <?php endif; ?>
                <?php 
                      $index++;
                      endforeach; 
                ?>
            </ul>
  </div>
      </div>
    </div>
    <div class="panel-body">
      <form role="form">
        <div class="col-sm-6">

          <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
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
            </div>
          <?php else :?>
              <input type="hidden" style="margin-top: 5px;" class="" name="staff_member_fname"  autocomplete="off" value="NA">
              <input type="hidden" style="margin-top: 5px;" class="" name="staff_member_lname"  autocomplete="off" value="NA">
          <?php endif;?>

          <?php if($this->session->userdata('account_type') != 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
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
          <?php endif;?>

          <?php
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

                
              </div>

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
              </div>
          </div>



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

                <div class="radio data_tooltip" title="<?php echo $disable_title ?>">
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
                <div class="radio data_tooltip" title="<?php echo $disable_title ?>">
                  <label class="i-checks">
                    <input type="radio" name="activity_type" id="radio_pickup2" value="2" class="radio_act_type" <?php //echo $disable_other_act_type ?> ><i></i>Pickup Item(s)
                  </label>
                </div>
                
              </div>
          </div>


          <div class="col-sm-12" style="margin-left: -21px">
              <div class="form-group" style="" id="fordelivery_categories">
                <label> Delivery Date <span class="text-danger-dker">*</span></label>
                <input type="text" class="form-control datepicker" value="" placeholder="Date" name="delivery_date" style="">
              </div>
            </div>


          <div class="col-sm-12" style="margin-left: -21px;margin-top:10px">
            <div class="form-group" style="display:none;" id="forptmove_categories4">
              
                <div class="panel panel-default ng-scope">
                  <div class="panel-heading">
                    Old Item(s) to Pickup 
                  </div>
                  <div class="panel-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="checkbox" style="margin-left: 15px;margin-bottom: 25px;">
                          <label class="i-checks">
                            <input type="checkbox" class="select-all-old-items" value=""><i></i> Select All
                          </label>
                        </div>

                        <?php

                          $categories_equip = array(1, 2);
                          $includes = array("capped item", "non-capped item");

                          ?>

                          <?php foreach ($orders as $keys=>$equip_orders):?>
                              <?php if (in_array(strtolower($keys), $includes)): ?>
                                  <div class="col-md-6">
                                      <label style="margin-bottom:20px"><?php echo $keys; ?></label>
                                  <?php foreach ($equip_orders as $sub_key=>$sub_value): ?>    
                                          <?php if (in_array($sub_value[0]['categoryID'], $categories_equip)): ?>
                                              <?php if (isset($sub_value['children'])): ?>

                                                <?php if(!empty($sub_value[0])) :?>
                                                  <?php if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1) :?>
                                                        <div class="checkbox" style="" >
                                                            <label class="i-checks">
                                                                <input type="checkbox" <?php if($sub_value[0]['initial_order'] == 1) echo "checked" ?> onclick="return false;" name="pickup_ptmove_equipments[]" class="checked_pickup_old_item" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                <i></i>
                                                                <input type="hidden" name="pickup_ptmove_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                <?php echo $sub_key; ?> - <?php echo $sub_value[0]['serial_num'] ?>   
                                                                
                                                            </label>
                                                            <ul>
                                                                <?php 
                                                                  
                                                                  $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                               
                                                                  foreach($item_options as $options)
                                                                  {
                                                                    $sub_equipmentID = $options['equipmentID'];
                                                                    $sub_equipmentID_parentID = $options['parentID'];
                                                                    $item_uniqueID = $sub_value[0]['uniqueID'];

                                                                    if($options['input_type'] == "radio") 
                                                                    {
                                                                      echo "<input type='checkbox' ".(($sub_value[0]['initial_order'] == 1) ? 'checked=checked' : '')." onclick='return false;' class='sub_equip_ptmove_checkbox$sub_equipmentID_parentID$item_uniqueID sub_ptmove_options_checkbox' name='equip_ptmove_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='$disable_unchecking'  >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "text") 
                                                                    {
                                                                      echo "<input type='checkbox' ".(($sub_value[0]['initial_order'] == 1) ? 'checked=checked' : '')." onclick='return false;' class='sub_equip_ptmove_checkbox$sub_equipmentID_parentID$item_uniqueID sub_ptmove_options_checkbox' name='equip_ptmove_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='$disable_unchecking' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "checkbox") 
                                                                    {
                                                                      echo "<input type='checkbox' ".(($sub_value[0]['initial_order'] == 1) ? 'checked=checked' : '')." onclick='return false;' class='sub_equip_ptmove_checkbox$sub_equipmentID_parentID$item_uniqueID sub_ptmove_options_checkbox' name='equip_ptmove_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='$disable_unchecking' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                  }

                                                                ?>
                                                            </ul>
                                                            <input type="hidden" name="ptmove_unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                        </div>
                                                  <?php endif;?>
                                                <?php endif;?>

                                                  <?php if(!empty($sub_value[1])) : ?>
                                                    <?php if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :?>
                                                        <div class="checkbox" style="" >
                                                            <label class="i-checks">
                                                                <input type="checkbox" <?php if($sub_value[1]['initial_order'] == 1) echo "checked" ?> onclick="return false;" name="pickup_ptmove_equipments[]" class="checked_pickup_old_item" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                <i></i>
                                                                <input type="hidden" name="pickup_ptmove_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                <?php echo $sub_key; ?> - <?php echo $sub_value[1]['serial_num'] ?>   
                                                            </label>
                                                            <ul>
                                                                <?php 
                                                                  $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                  $work_order = $sub_value[1]['uniqueID'];

                                                                  foreach($item_options as $options)
                                                                  {
                                                                    $sub_equipmentID = $options['equipmentID'];
                                                                    $sub_equipmentID_parentID = $options['parentID'];
                                                                    $item_uniqueID = $sub_value[1]['uniqueID'];

                                                                    if($options['input_type'] == "radio") 
                                                                    {
                                                                      echo "<input type='checkbox' ".(($sub_value[1]['initial_order'] == 1) ? 'checked=checked' : '')." onclick='return false;' class='sub_equip_ptmove_checkbox$sub_equipmentID_parentID$item_uniqueID sub_ptmove_options_checkbox' name='equip_ptmove_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='$disable_unchecking'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "text") 
                                                                    {
                                                                      echo "<input type='checkbox' ".(($sub_value[1]['initial_order'] == 1) ? 'checked=checked' : '')." onclick='return false;' class='sub_equip_ptmove_checkbox$sub_equipmentID_parentID$item_uniqueID sub_ptmove_options_checkbox'  name='equip_ptmove_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='$disable_unchecking'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "checkbox") 
                                                                    {
                                                                      echo "<input type='checkbox' ".(($sub_value[1]['initial_order'] == 1) ? 'checked=checked' : '')." onclick='return false;' class='sub_equip_ptmove_checkbox$sub_equipmentID_parentID$item_uniqueID sub_ptmove_options_checkbox' name='equip_ptmove_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='$disable_unchecking'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                   
                                                                  }
                                                                ?>

                                                            </ul>
                                                            <input type="hidden" name="ptmove_unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                        </div>
                                                    <?php endif;?>
                                                  <?php endif;?>

                                            <?php else: ?>


                                              <?php if(!empty($sub_value[0])) :?>
                                                <?php if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1) :?>
                                                    <div class="checkbox" style="">
                                                        <label class="i-checks">
                                                            <input type="checkbox" <?php if($sub_value[0]['initial_order'] == 1) echo "checked" ?> onclick="return false;" name="pickup_ptmove_equipments[]" class="checked_pickup_old_item" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                            <i></i>
                                                            <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                            <?php echo $sub_key; ?> - <?php echo $sub_value[0]['serial_num'] ?>
                                                                
                                                        </label>
                                                        <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                    </div>
                                                <?php endif;?>
                                              <?php endif;?>


                                              <?php if(!empty($sub_value[1])) :?>
                                                <?php if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :?>
                                                    <div class="checkbox" style="">
                                                        <label class="i-checks">
                                                            <input type="checkbox" <?php if($sub_value[1]['initial_order'] == 1) echo "checked" ?> onclick="return false;" name="pickup_ptmove_equipments[]" class="checked_pickup_old_item" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                            <i></i>
                                                            <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                            <?php echo $sub_key; ?> - <?php echo $sub_value[1]['serial_num'] ?> 
                                                               
                                                        </label>
                                                        <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                    </div>
                                                <?php endif;?>
                                              <?php endif;?>

                                          
                                          <?php endif; ?>


                                        <?php endif; ?>
                                    <?php endforeach; ?>

                                    <div id="hdn_old_items_checked_unique_div"></div>
                                    <div id="hdn_pickup_ptmove_unique_div"></div>

                                </div>

                            <?php endif; ?>
                          <?php endforeach; ?>
                      </div>

                      <div class="col-sm-12" style="">
                        <div class="form-group" style="display:none;" id="forptmove_categories5">
                          <label>Pickup Date for Old Items <span class="text-danger-dker">*</span></label>
                          <input type="text" class="form-control datepicker" placeholder="Date" name="ptmove_old_items_pickup_date" style="margin-left:0px;" tabindex=""> <br />
                          <!--<label>DME Staff Member Picked Up Old Items <span class="text-danger-dker">*</span></label>
                          <input type="text" class="form-control" placeholder="Driver's Name" name="ptmove_pickup_items_driver" style="" tabindex="">-->
                        </div>
                      </div>

                    </div>
                  </div>
                </div>


            </div>
          </div>



          <div class="col-sm-12" style="margin-left: -21px">
            <div class="form-group" style="display:none;" id="forptmove_categories">
              <label>PT Move Delivery Date <span class="text-danger-dker">*</span></label>
              <input type="text" class="form-control datepicker" placeholder="Date" name="ptmove_delivery_date" tabindex="8">
            </div>
          </div>

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

            </div>


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
          </div>


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



          <div class="col-sm-12" style="margin-left: -12px;margin-top:10px">
            <div class="form-group " style="display:none;" id="forpickup_categories3">
              <div class="panel panel-default ng-scope">
                  <div class="panel-heading">
                    Item(s) to Pickup 
                  </div>
                  <div class="panel-body">
                    <div class="row">
                       
                        <div class="col-sm-12">
                           <div class="checkbox" style="margin-left: 15px;margin-bottom: 25px;">
                            <label class="i-checks">
                              <input type="checkbox" class="select-all-pickup" value=""><i></i> Select All
                            </label>
                          </div>

                         <?php

                          $categories_equip = array(1, 2);
                          $includes = array("capped item", "non-capped item");

                          ?>

                          <?php foreach ($orders as $keys=>$equip_orders):?>
                              <?php if (in_array(strtolower($keys), $includes)): ?>
                                  <div class="col-md-6">
                                      <label style="margin-bottom:20px"><?php echo $keys; ?></label>
                                  <?php foreach ($equip_orders as $sub_key=>$sub_value): ?>    
                                      <?php
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


                                      if ($sub_value[0]['activity_typeid'] == 2 || $sub_value[0]['activity_reference'] == 2 || $sub_value[1]['activity_typeid'] == 2 || $sub_value[1]['activity_reference'] == 2) {
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
                                          <?php if (in_array($sub_value[0]['categoryID'], $categories_equip)): ?>
                                              <?php if (isset($sub_value['children'])): ?>


                                                <?php if(!empty($sub_value[0])) :?>
                                                  <?php if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1) :?>
                                                        <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                            <label class="i-checks">
                                                                <input type="checkbox" <?php echo $checked; ?> onclick="<?php if(!empty($ptmove_address)){ if($sub_value[0]['activity_typeid'] != 4) { echo "return false"; }} ?>" <?php echo $checked_old_items ?> name="pickup_equipments[]" class="checked_pickup_item" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                                <i></i>
                                                                <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                                <?php echo $sub_key; ?> - <?php echo $sub_value[0]['serial_num'] ?>   
                                                                
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
                                                                      echo "<input type='checkbox' $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='$disable_unchecking'  >" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "text") 
                                                                    {
                                                                      echo "<input type='checkbox' $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='$disable_unchecking' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "checkbox") 
                                                                    {
                                                                      echo "<input type='checkbox' $checked_old_items ".(($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  onclick='$disable_unchecking' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                   
                                                                  }

                                                                ?>

                                                                <?php /*
                                                                <?php
                                                                  foreach ($sub_value['children'] as $children)
                                                                  {
                                                                    $sub_equipmentID = $children['equipmentID'];
                                                                    $sub_equipmentID_parentID = $children['parentID'];
                                                                   
                                                                    if($children['input_type'] == "radio") 
                                                                    {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'>" . $children['option_description'] . " : <span class='text-success'>" . trim($children['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($children['input_type'] == "text") 
                                                                    {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $children['key_desc'] . " : <span class='text-success'>" . trim($children['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($children['input_type'] == "checkbox") 
                                                                    {
                                                                      echo "<input type='checkbox' class='sub_equip_checkbox$sub_equipmentID_parentID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $children['option_description'] . " :<span class='text-success'>" . trim($children['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                  }
                                                                    ?>
                                                                  */ ?>

                                                                
                                                            </ul>
                                                            <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                        </div>
                                                  <?php endif;?>
                                                <?php endif;?>

                                                  <?php if(!empty($sub_value[1])) : ?>
                                                    <?php if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :?>
                                                        <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                            <label class="i-checks">
                                                                <input type="checkbox" <?php echo $checked; ?> onclick="<?php if(!empty($ptmove_address)){ if($sub_value[1]['activity_typeid'] != 4) { echo "return false"; }} ?>" <?php echo $checked_old_items ?> name="pickup_equipments[]" class="checked_pickup_item" data-equip-id="<?php echo $sub_value[1]['equipmentID'] ?>" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                                <i></i>
                                                                <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                                <?php echo $sub_key; ?> - <?php echo $sub_value[1]['serial_num'] ?>   
                                                                  
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
                                                                      echo "<input type='checkbox' $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='$disable_unchecking'>" . $options['option_description'] . " : <span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "text") 
                                                                    {
                                                                      echo "<input type='checkbox' $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox'  name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='$disable_unchecking'>" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                      echo "</span><br />";
                                                                    }
                                                                    else if ($options['input_type'] == "checkbox") 
                                                                    {
                                                                      echo "<input type='checkbox' $checked_old_items ".(($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) ? 'checked=checked' : '')." class='sub_equip_checkbox$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' onclick='$disable_unchecking'>" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                      echo "</span><br />";
                                                                    }
                                                                   
                                                                  }
                                                                ?>

                                                            </ul>
                                                            <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                        </div>
                                                    <?php endif;?>
                                                  <?php endif;?>

                                            <?php else: ?>


                                              <?php if(!empty($sub_value[0])) :?>
                                                <?php if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1) :?>
                                                    <div class="checkbox" style="<?php echo $displayer; ?>">
                                                        <label class="i-checks">
                                                            <input type="checkbox" <?php echo $checked; ?> onclick="<?php echo $disabler ?>" <?php echo $checked_old_items ?> name="pickup_equipments[]" class="checked_pickup_item" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" <?php if($sub_value[0]['activity_typeid'] == 5 || $sub_value[0]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>" <?php if($sub_value[0]['activity_typeid'] == 1 && $sub_value[0]['original_activity_typeid'] == 1 && $sub_value[0]['activity_typeid'] != 4 && $sub_value[0]['uniqueID_reference'] != 0) echo "checked" ?>  data-work-order="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                            <i></i>
                                                            <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[0]['serial_num'] ?>" />
                                                            <?php echo $sub_key; ?> - <?php echo $sub_value[0]['serial_num'] ?>
                                                                
                                                        </label>
                                                        <input type="hidden" name="unique_id" value="<?php echo $sub_value[0]['uniqueID'] ?>">
                                                    </div>
                                                <?php endif;?>
                                              <?php endif;?>


                                              <?php if(!empty($sub_value[1])) :?>
                                                <?php if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :?>
                                                    <div class="checkbox" style="<?php echo $displayer; ?>">
                                                        <label class="i-checks">
                                                            <input type="checkbox" <?php echo $checked; ?> onclick="<?php echo $disabler ?>" <?php echo $checked_old_items ?> name="pickup_equipments[]" class="checked_pickup_item" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[1]['uniqueID'] ?>" <?php if($sub_value[1]['activity_typeid'] == 5 || $sub_value[1]['activity_reference'] == 5) echo "checked" ?> data-orig-act-id="<?php echo $sub_value[1]['original_activity_typeid'] ?>" <?php if($sub_value[1]['activity_typeid'] == 1 && $sub_value[1]['original_activity_typeid'] == 1 && $sub_value[1]['activity_typeid'] != 4 && $sub_value[1]['uniqueID_reference'] != 0) echo "checked" ?> data-work-order="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                            <i></i>
                                                            <input type="hidden" name="pickup_serial_num[]" value="<?php echo $sub_value[1]['serial_num'] ?>" />
                                                            <?php echo $sub_key; ?> - <?php echo $sub_value[1]['serial_num'] ?> 
                                                               
                                                        </label>
                                                        <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                    </div>
                                                <?php endif;?>
                                              <?php endif;?>

                                          
                                          <?php endif; ?>


                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                  </div>

                                <?php endif; ?>
                              <?php endforeach; ?>


                          <!-- <button type="submit" class="btn btn-danger pull-right " style="margin-top:-55px;">Save changes</button> -->
                        </div>
                    </div>

                    <div id="hdn_original_act_id_pickup" name="">
                        <!-- Where hidden input for original activity type will appear -->
                    </div>


                    <div id="hdn_pickup_unique_div">
                        <!-- Where hidden input for uniqueID will appear -->
                    </div>

                      <div class="col-sm-12" style="margin-left: -12px;margin-top:45px">
                        <div class="form-group" style="display:none;" id="forpickup_categories">
                          <label>Pickup Reason <span class="text-danger-dker">*</span></label>
                          <select class="form-control pickup-reason" name="pickup_sub_cat" style="">
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
                      </div>

                      <?php if(empty($addresses)) :?>
                        <input type="hidden" name="pickup_respite_address" value="" />
                      <?php endif;?>

                      <?php if(empty($ptmove_address)):?>
                        <input type="hidden" name="pickup_ptmove_address" value="" />
                      <?php endif;?>

                       <?php if(!empty($ptmove_address)): ?>
                          <?php $address = $ptmove_address[0] ?>
                          <?php if(!empty($display_old_address)) :?>
                              <div class="col-sm-12" style="margin-left: -16px;">
                                <div class="form-group" style="" id="">
                                  <label>Address to Pick up ( uncheck this one if necessary ) <span class="text-danger-dker">*</span></label>
                                    <div class="radio">
                                      <label class="i-checks">
                                      
                                      <?php if($address['ptmove_old_address_pickedup'] == 0 && $address['summary_pickup_date'] == '0000-00-00'):?>
                                        <input type="radio" checked name="ptmove_old_address" id="ptmove_checkbox_old_address" value="0" class="" ><i></i>Address - <?php echo $address['p_street']." ".$address['p_placenum']." ".$address['p_city']." ".$address['p_state']." ".$address['p_postalcode'] ?>
                                        <input type="hidden" name="ptmove_new_address" value="<?php echo $address['ptmoveID'] ?>" />
                                      <?php else:?>
                                        <input type="radio" checked name="ptmove_old_address" id="ptmove_checkbox_old_address" value="<?php echo $address['ptmoveID'] ?>"><i></i>Address - <?php echo $address['ptmove_street']." ".$address['ptmove_placenum']." ".$address['ptmove_city']." ".$address['ptmove_state']." ".$address['ptmove_postal'] ?>
                                      
                                      <?php endif;?>

                                      </label>
                                    </div>
                                </div>
                              </div>
                            <?php endif;?>
                            
                            <div class="old_unique_ids">
                                <!-- hidden input for pt move in order to get the unique id of the checked items -->
                            </div>
                            
                      <?php endif;?>


                      <?php if(!empty($addresses)) :?>
                        <?php if($addresses[0]['respite_pickedup'] != 1) :?>
                          <?php $address = $addresses[0] ?>
                            <div class="col-sm-12" style="margin-left: -16px;">
                              <div class="form-group" style="display:none;" id="forpickup_categories5">
                                <label> Pickup item(s) from old address <span class="text-danger-dker">*</span></label>
                                  <div class="radio">
                                    <label class="i-checks">
                                      <input type="radio" checked name="pickup_respite_address" id="" value="<?php echo $address['respite_address'] ?>" class="" ><i></i>RESPITE - <?php echo $address['respite_address']." ".$address['respite_placenum']." ".$address['respite_city']." ".$address['respite_state']." ".$address['respite_postal'] ?>
                                    </label>
                                  </div>
                              </div>
                            </div>
                        <?php endif;?>
                      <?php endif;?>

                     


                      <div class="col-sm-12" style="margin-left: -16px;">
                        <div class="form-group" style="display:none;" id="forpickup_categories2">
                          <label> Pickup Date <span class="text-danger-dker">*</span></label>
                          <input type="text" class="form-control datepicker" value="" placeholder="Date" name="pickup_pickup_date" style="">
                        </div>
                      </div>

                      <div class="" style="margin-top:25px">
                        <div class="">
                          <label>Delivery Instructions</label>
                          <textarea style="margin-top: 5px;" class="form-control " name="new_pickup_notes" placeholder="Delivery Instructions" ></textarea>
                        </div>
                      </div> 

                  </div>
                </div> 
              </div>
           </div> 

           <div class="col-sm-12" style="">
              <div class="form-group" style="display:none;" id="forpickup_categories4">
                <button type="button" class="btn btn-success pull-right save_pickup_data" data-id="<?php echo $information['patientID'] ?>" value="" >Submit Activity</button>
              </div>
            </div>


         <!--  <div class="col-sm-12" style="margin-left: -12px;">
            <div class="form-group" style="display:block;" id="forexchange_categories">
              <label>Reason for Exchange <span class="text-danger-dker">*</span></label>
              <textarea class="form-control" style="width:506px" name="exchange_reason"></textarea>
            </div>
          </div>

          <div class="col-sm-12" style="margin-left: -16px;">
            <div class="form-group" style="display:none;" id="forexchange_categories2">
              <label> Exchange Delivery Date <span class="text-danger-dker">*</span></label>
              <input type="text" class="form-control datepicker" value="" placeholder="Date" name="exchange_date" style="width:506px">
            </div>
          </div> -->


          <div class="col-sm-12" style="margin-left: -12px;margin-top:10px">
            <div class="form-group " style="display:none;" id="forexchange_categories3">
              <div class="panel panel-default ng-scope">
                  <div class="panel-heading">
                    Item(s) to Exchange
                  </div>
                  <div class="panel-body">
                    <div class="row">
                       
                        <div class="col-sm-12">
                           <div class="checkbox" style="margin-left: 15px;margin-bottom: 25px;">
                            <label class="i-checks">
                              <input type="checkbox" class="select-all-exchange" value=""><i></i> Select All
                            </label>
                          </div>
 
                         <?php
                          $categories_equip = array(1, 2);
                          $includes = array("capped item", "non-capped item");

                          ?>
                          <?php foreach ($orders as $keys => $equip_orders): ?>
                              <?php if (in_array(strtolower($keys), $includes)): ?>
                                  <div class="col-md-6">
                                      <label><?php echo $keys; ?></label>
                                  <?php foreach ($equip_orders as $sub_key => $sub_value):?>    
                                      <?php
                                      $checked = "";
                                      $displayer = "";

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
                                          <?php if (in_array($sub_value[0]['categoryID'], $categories_equip)): ?>
                                                <?php if (isset($sub_value['children'])): ?>

                                                  <?php if(!empty($sub_value[0])) : ?>
                                                    <?php if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1) :?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                              <label class="i-checks">
                                                                  <input type="checkbox" <?php echo $checked; ?> name="exchange_equipments[]" class="checked_item" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                                  <i></i>
                                                                  <?php echo $sub_key; ?> - <?php echo $sub_value[0]['serial_num'] ?>       
                                                              </label>
                                                              
                                                              <ul>
                                                                  <?php 
                                                                    
                                                                    $item_options = get_item_option_by_workorder($sub_value[0]['equipmentID'], $sub_value[0]['uniqueID']);
                                                                 
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
                                                                        echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID'  >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                        echo "</span><br />";
                                                                      }
                                                                    }
                                                                  ?>
                                                                  
                                                              </ul>
                                                          </div>
                                                    <?php endif;?>
                                                  <?php endif;?>

                                                    <?php if(!empty($sub_value[1])) : ?>
                                                      <?php if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :?>
                                                          <div class="checkbox" style="<?php echo $displayer; ?>" >
                                                              <label class="i-checks">
                                                                  <input type="checkbox" <?php echo $checked; ?> name="exchange_equipments[]" class="checked_item" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                                  <i></i>
                                                                  <?php echo $sub_key; ?> - <?php echo $sub_value[1]['serial_num'] ?>       
                                                              </label>
                                                              <ul>
                                                                  <?php 
                                                                    
                                                                    $item_options = get_item_option_by_workorder($sub_value[1]['equipmentID'], $sub_value[1]['uniqueID']);
                                                                    $work_order = $sub_value[1]['uniqueID'];

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
                                                                        echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox'  name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['key_desc'] . " : <span class='text-success'>" . trim($options['equipment_value']);
                                                                        echo "</span><br />";
                                                                      }
                                                                      else if ($options['input_type'] == "checkbox") 
                                                                      {
                                                                        echo "<input type='checkbox' class='sub_equip_checkbox_exchange$sub_equipmentID_parentID$item_uniqueID sub_options_checkbox' name='equip_options_exchange[$sub_equipmentID_parentID][options][]' value='$sub_equipmentID' >" . $options['option_description'] . " :<span class='text-success'>" . trim($options['key_desc']);
                                                                        echo "</span><br />";
                                                                      }
                                                                     
                                                                    }
                                                                  ?>

                                                              </ul>
                                                              <input type="hidden" name="unique_id" value="<?php echo $sub_value[1]['uniqueID'] ?>">
                                                          </div>
                                                      <?php endif;?>
                                                    <?php endif;?>
                                              <?php else: ?>


                                                <?php if(!empty($sub_value[0])) :?>
                                                  <?php if($sub_value[0]['canceled_order'] != 1 && $sub_value[0]['pickedup_respite_order'] != 1 && $sub_value[0]['canceled_from_confirming'] != 1) :?>
                                                      <div class="checkbox" style="<?php echo $displayer; ?>">
                                                          <label class="i-checks">
                                                              <input type="checkbox" <?php echo $checked; ?> name="exchange_equipments[]" class="checked_item" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                              <i></i>
                                                              <?php echo $sub_key; ?> - <?php echo $sub_value[0]['serial_num'] ?>       
                                                          </label>
                                                      </div>
                                                  <?php endif;?>
                                                <?php endif;?>


                                                <?php if(!empty($sub_value[1])) :?>
                                                  <?php if($sub_value[1]['canceled_order'] != 1 && $sub_value[1]['pickedup_respite_order'] != 1 && $sub_value[1]['canceled_from_confirming'] != 1) :?>
                                                      <div class="checkbox" style="<?php echo $displayer; ?>">
                                                          <label class="i-checks">
                                                              <input type="checkbox" <?php echo $checked; ?> name="exchange_equipments[]" class="checked_item" value="<?php echo $sub_value[1]['equipmentID'] ?>" data-equip-id="<?php echo $sub_value[0]['equipmentID'] ?>" data-uniqueID="<?php echo $sub_value[0]['uniqueID'] ?>" value="<?php echo $sub_value[0]['equipmentID'] ?>" data-orig-act-id="<?php echo $sub_value[0]['original_activity_typeid'] ?>">
                                                              <i></i>
                                                              <?php echo $sub_key; ?> - <?php echo $sub_value[1]['serial_num'] ?>    
                                                          </label>

                                                      </div>
                                                  <?php endif;?>
                                                <?php endif;?>
                                            <?php endif; ?>
                                          <?php endif; ?>
                                        <!-- END -->


                                    <?php endforeach; ?>
                                  </div>
                                    <?php endif; ?>
                                  <?php ?>
                              <?php endforeach; ?>
                          <!-- <button type="submit" class="btn btn-danger pull-right " style="margin-top:-55px;">Save changes</button> -->
                        </div>
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
                  </div>
                </div>
              </div>
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
  <div class="panel-heading font-bold">Add New Item(s)</div>
  <div class="panel-body">

<div class="col-md-8">
   <?php if (!empty($equipments)) : ?>
      <?php foreach ($equipments as $equipment) :?>
        <div class="form-group col-md-12 wrapper-equipment" data-value="<?php echo $equipment['categoryID'] ?>" id="wrapper_equip_<?php echo $equipment['categoryID'] ?>">

          <label class="btn btn-default data_tooltip" title="Click to Add New Item(s)" style="margin-bottom:20px;margin-top:20px;"  id="equip_<?php echo $equipment['categoryID'] ?>"><?php echo $equipment['type'] ?></label> <br>
          
          <div class="equipment" style="display:none;">
              <label style="margin-top:10px;visibility:hidden"><?php echo $equipment['type'] ?> <span class="text-danger-dker">*</span></label>
              <div class="col-md-4" style="padding-left:15px;">
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
                                 data-reference-id="<?php echo $child['noncapped_reference']; ?>"
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
              <div class="col-md-4" style="padding-left:15px;" id="">
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
                                   data-reference-id="<?php echo $child['noncapped_reference']; ?>"
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
  
  
<div class="col-sm-9" style="padding-left:0px;">
      <div class="form-group">
        <!-- special instructions -->
        <label>Delivery Instructions </label>
          <textarea class="form-control" name="new_order_notes" style=""></textarea>
       
      </div>
    </div>
</div>



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
        <input type="hidden" value="<?php echo $information['organization_id'] ?>" name="organization_id" />
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital Bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Hospital Bed <span style="color:red;">*</span></label>
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

                                <br><br>


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
                                    <textarea type="text" data-desc="Rate" class="form-control" placeholder="" name="subequipment[4][111]"></textarea>
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
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital Bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Hospital Bed <span style="color:red;">*</span></label>
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
                                </div>




                                <label style="margin-top: 20px;">Type of Rails<span style="color:red;">*</span></label>
                               
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
