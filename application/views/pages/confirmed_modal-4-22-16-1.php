<style type="text/css">
  .modal
  {
    left: -689px;
    top: -71px;
  }
  #globalModal .modal-content
  {
    width:1300px;
  }
</style>

<script type="text/javascript" src="<?php echo base_url() ?>assets/js/common.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/equipments.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/order_summary.js"></script>

<?php if(!empty($infos)):?>
<?php foreach($infos as $info) :?>

<?php $medical_id = $info['medical_record_id'] ?>
<?php echo form_open("",array("class"=>"edit_patient_profile_form")) ?>
    <div class="row">
            <div class="">
                <div class="col-md-6" style="padding-left:30px;">
                    <input type="hidden"  class="form-control " id="exampleInputEmail1"  placeholder="" name="update_patient_id" style="margin-bottom:10px" value="<?php echo $info['patientID'] ?>" />

					
                     <label>Patient Medical Record # <span style="color:red;">*</span></label>
                     <div class="clearfix"></div>
                        <div class="form-group">
                            <input type="text"  class="form-control medical_record_num" id="exampleInputEmail1" placeholder="" name="medical_record_id" style="margin-bottom:10px" value="<?php echo $info['medical_record_id'] ?>">
                        </div>

                        <div class="clearfix"></div>
                         <label>Hospice Provider <span style="color:red;">*</span></label>

                      <div class="">
                        <?php $hospices = get_hospices() ;?> 
                          <div class="form-group">
                            <?php if(empty($info['organization_id'])) :?>
                              <select class="form-control hospice_provider_select" style="margin-bottom:10px" name="organization_id">
                                  <option value="">-- Choose Hospice Provider --</option>                                  
                                  <?php foreach($hospices as $hospice) :?>   
                                      <option value="<?php echo $hospice['hospiceID'] ?>"><?php echo $hospice['hospice_name'] ?></option>
                                  <?php endforeach;?>       
                              </select>
                            <?php else :?>
                              <select class="form-control hospice_provider_select" style="margin-bottom:10px" name="organization_id">
                                  <option value="<?php echo $info['organization_id'] ?>">[-- <?php echo $info['hospice_name'] ?> --]</option>
                                  
                                  <?php foreach($hospices as $hospice) :?> 
                                      <option value="<?php echo $hospice['hospiceID'] ?>"><?php echo $hospice['hospice_name'] ?></option>
                                  <?php endforeach;?> 
                              </select>
                            <?php endif;?>
                          </div>
                      </div>
                      <input type="hidden" class="hdn_provider_name" name="organization_name" value="" />
                  

                   <div class="col-md-6" style="padding-left:0px;">
						<label>Patient Last Name <span style="color:red;">*</span></label>
                     <div class="clearfix"></div>
                        <div class="form-group">
                            <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="" name="p_lname" style="margin-bottom:10px" value="<?php echo $info['p_lname'] ?>">
                        </div>
                    </div>
                     <div class="col-md-6" >
						 <label>Patient First Name <span style="color:red;">*</span></label>
                        <div class="form-group">
                            <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="" name="p_fname" style="margin-bottom:10px" value="<?php echo $info['p_fname'] ?>">
                        </div>
                     
                    </div>
                    <div class="clearfix"></div>

                    <div class="patient-address-fields" style="margin-left:-16px;">
                      <div class="col-md-8">
                        <div class="form-group">
                          <label>Patient Address <span class="text-danger-dker">*</span></label>
                          <?php 
                            $ptmove = new_ptmove_address($info['patientID']);
                            $ptmove_new_phone = get_new_patient_phone($info['patientID']);
                            $ptmove_residence = get_new_patient_residence($info['patientID']);
                            $ptmove_final = $ptmove[0];

                            $addressID = 0;
                            $count = 1;
                            foreach($summaries as $equipments)
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
                            <input type="text" class="form-control" id="" placeholder="Enter Address" name="p_street" style="margin-bottom:10px;" value="<?php echo $equpment_location['street'] ?>">
                        </div>
                      </div> <!-- .col-md-8 -->
                
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Apartment # <span class="text-danger-dker">*</span></label>
                          <input type="text" class="form-control" id="" placeholder="Apartment #, Room #" name="p_placenum" style="margin-bottom:10px;" value="<?php echo $equpment_location['placenum'] ?>">
                        </div>
                      </div>
                      <div class="clearfix"></div>
                      <div class="col-md-4">
                          <div class="form-group">
                            <input type="text" class="form-control" id="city_confirm" placeholder="City" name="p_city" style="margin-bottom:20px" value="<?php echo $equpment_location['city'] ?>">
                          </div>
                      </div>
                      <div class="col-md-4" >
                          <div class="form-group">
                             <input type="text" class="form-control" id="state_confirm" placeholder="State" name="p_state" style="margin-bottom:20px" value="<?php echo $equpment_location['state'] ?>">
                          </div>
                      </div>
                      <div class="col-md-4" >
                          <div class="form-group">
                            <input type="text" class="form-control" id="postal_confirm" placeholder="Postal" name="p_postalcode" style="margin-bottom:20px" value="<?php echo $equpment_location['postalcode'] ?>">
                          </div>
                      </div>
                    </div> <!-- .patient-address-fields -->

                    <hr />

              </div>
              
              <div class="col-md-6">
                  <div class="col-md-6" >
                  <label>Height(IN)<span class="text-danger-dker">*</span></label>
                  <input type="text" class="form-control" id="" placeholder="Height(IN)" name="height" style="margin-bottom:10px" value="<?php echo $info['p_height'] ?>">
                  </div>
                  <div class="col-md-6" >
                  <label>Weight(lbs) <span class="text-danger-dker">*</span></label>
                  <input type="text" class="form-control" id="" placeholder="Weight(lbs)" name="weight" style="margin-bottom:10px" value="<?php echo $info['p_weight'] ?>">
                  </div>

                  <div class="col-md-6" >
                  <label>Phone Number<span class="text-danger-dker">*</span></label>
                  <input type="text" class="form-control person_num" id="" placeholder="Phone Number" name="phonenum" style="margin-bottom:10px" value="<?php echo $info['p_phonenum'] ?>">
                  </div>
                  <div class="col-md-6" >
                  <label>Alt. Phone Number</label>
                  <input type="text" class="form-control person_num" id="" placeholder="Alt. Phone Number" name="altphonenum" style="margin-bottom:10px" value="<?php echo $info['p_altphonenum'] ?>">
                  </div>

                   <?php
                    $ptmove_residence     = get_new_patient_residence($medical_id);
                    $ptmove_nextofkin     = get_new_nextofkin($medical_id);
                    $ptmove_relationship  = get_new_relationship($medical_id);
                    $ptmove_phonenum      = get_new_phonenum($medical_id);
                  ?>

                  <div class="col-md-6" >

                    <label>Next of Kin<span class="text-danger-dker">*</span></label>
                    
                    <?php if(!empty($ptmove_nextofkin)) :?> 
                      <input type="text" class="form-control " id="" placeholder="Next of Kin" name="nextofkin" style="margin-bottom:10px" value="<?php echo $ptmove_nextofkin['ptmove_nextofkin'] ?>">
                    <?php else:?>
                      <input type="text" class="form-control " id="" placeholder="Next of Kin" name="nextofkin" style="margin-bottom:10px" value="<?php echo $info['p_nextofkin'] ?>">
                    <?php endif;?>

                  </div>

                  <div class="col-md-6" >
                    <label>Relationship<span class="text-danger-dker">*</span></label>
                    
                    <?php if(!empty($ptmove_relationship)) :?> 
                      <input type="text" class="form-control " id="" placeholder="Relationship" name="relationship" style="margin-bottom:10px" value="<?php echo $ptmove_relationship['ptmove_nextofkinrelation'] ?>">
                    <?php else:?>
                      <input type="text" class="form-control " id="" placeholder="Relationship" name="relationship" style="margin-bottom:10px" value="<?php echo $info['p_relationship'] ?>">
                    <?php endif;?>

                  </div>

                  <div class="col-md-6">
                  <label>Next of Kin Phone No.<span class="text-danger-dker">*</span></label>
                    
                    <?php if(!empty($ptmove_phonenum)) :?> 
                      <input type="text" class="form-control person_num" id="" placeholder="Next of Kin Phone No." name="nextofkinnum" style="margin-bottom:20px" value="<?php echo $ptmove_phonenum['ptmove_nextofkinphone'] ?>">
                    <?php else:?>
                      <input type="text" class="form-control person_num" id="" placeholder="Next of Kin Phone No." name="nextofkinnum" style="margin-bottom:20px" value="<?php echo $info['p_nextofkinnum'] ?>">
                    <?php endif;?>

                  </div>

                 
                  <div class="col-md-6">
                  <label>Residence<span class="text-danger-dker">*</span></label>
                    
                    <?php if(!empty($ptmove_residence)) :?>
                      <input type="text" class="form-control " id="" placeholder="Residence" name="deliver_to_type" style="margin-bottom:10px" value="<?php echo $ptmove_residence['ptmove_patient_residence'] ?>">
                    <?php else:?>
                      <input type="text" class="form-control " id="" placeholder="Residence" name="deliver_to_type" style="margin-bottom:10px" value="<?php echo $info['deliver_to_type'] ?>">
                    <?php endif;?>

                  </div>


                  <?php if($act_id == 4) :?>
                    <?php 
                       $pt_id = $info['patientID'];
                       $ptmove_information = get_ptmove_address_inputted($pt_id);
                       $ptmove_info = $ptmove_information[0];
                    ?>

                    <input type="hidden" name="ptmove_unique_id" value="<?php echo $work_order_number ?>" />
                    <div class="patient-address-fields" style="margin-left:0px;margin-top:10px">
                        <div class="col-md-8">
                            <div class="form-group">
                              <label>New PT Move Address <span class="text-danger-dker">*</span></label>
                      <input type="text" class="form-control ptmove_required" id="" placeholder="Enter Address" name="pt_street" style="margin-bottom:10px;border:1px solid red" value="<?php echo $ptmove_info['ptmove_street'] ?>">
                            </div>
                        </div>
                
                   <div class="col-md-4">
                        <div class="form-group">
                           <label>Apartment # </label>
                          <input type="text" class="form-control ptmove_required2" id="" placeholder="Apartment #, Room #" name="pt_placenum" style="margin-bottom:10px;border:1px solid red" value="<?php echo $ptmove_info['ptmove_placenum'] ?>">
                        </div>
                  </div>
                  <div class="clearfix"></div>
                  <div class="col-md-4">
                      <div class="form-group">
                        <input type="text" class="form-control ptmove_required3" id="city_pt" placeholder="City" name="pt_city" style="margin-bottom:20px;border:1px solid red" value="<?php echo $ptmove_info['ptmove_city'] ?>">
                      </div>
                  </div>
                  <div class="col-md-4" >
                      <div class="form-group">
                         <input type="text" class="form-control ptmove_required4" id="state_pt" placeholder="State" name="pt_state" style="margin-bottom:20px;border:1px solid red" value="<?php echo $ptmove_info['ptmove_state'] ?>">
                      </div>
                  </div>
                  <div class="col-md-4" >
                      <div class="form-group">
                        <input type="text" class="form-control ptmove_required5" id="postal_pt" placeholder="Postal" name="pt_postalcode" style="margin-bottom:20px;border:1px solid red" value="<?php echo $ptmove_info['ptmove_postal'] ?>">
                      </div>
                  </div>
                  <div class="col-md-8">
                            <div class="form-group">
                              <label>Patient Phone Number <span class="text-danger-dker">*</span></label>
                      <input type="text" class="form-control person_num ptmove_required6" id="" placeholder="Phone number" name="pt_phone" style="margin-bottom:10px;border:1px solid red" value="<?php echo $ptmove_info['ptmove_patient_phone'] ?>">
                            </div>
                    </div>

                    </div>

                <?php endif;?>

                 <!--  <div class="col-md-6">
                    <label>DME Staff Member Delivered Order<span class="text-danger-dker">*</span></label>
                    <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="Delivered by" name="driver_name" style="margin-bottom:20px" value="">
                  </div> -->
                  <input type="hidden" value="<?php echo $act_id ?>" name="act_typeid" />
                  <div class="col-md-4">
                    <div style="margin-right:0px;margin-top:25px">
                      <button type="button" class="btn btn-primary pull-right save_edit_changes_confirmed" data-id="<?php echo $info['medical_record_id'] ?>" name="" style="margin-bottom:10px">Save Changes</button>
                    </div>
                  </div>
                </div>
            </div>
        </div>
 <?php endforeach;?>
  <?php endif;?>
<?php echo form_close() ?>



<?php $fname = $this->session->userdata('firstname'); ?>
<?php $lname_complete = $this->session->userdata('lastname'); ?>
<?php $lname = substr($this->session->userdata('lastname'),0,1); ?>


<div class="panel panel-default">
<div class="panel-heading font-bold">
<h4>Patient Order Summary</h4>
</div>
<div class="panel-body">

<?php echo form_open("",array("class"=>"update_order_summary")) ;?>  


<div class="col-md-6">
      <div class="col-md-6" style="margin-left:-30px">
        <label>DME Staff Member Confirming Work Order<span class="text-danger-dker">*</span></label>
        <input type="text"  class="form-control confirmed_by" id="exampleInputEmail1" placeholder="<?php echo $fname." ".$lname."." ?>" name="" style="margin-bottom:20px" value="<?php echo $fname." ".$lname_complete ?>" readonly>
      </div>
</div>

<table class="table table-striped bg-white b-a col-md-12 edit_patient_orders" id="confirm_info_table" style="margin-top:0px;margin-left: 0px;">
  <thead>
    <?php $count = count($summaries); ?>
    <tr>
      <th style="width: 40px">WO#</th>
      <th style="width: 60px">Order Date</th>
      <th style="width: 90px">Activity Type</th>
      <th style="width: 60px">Item #</th>
      <th style="width: 90px">Item Description</th>
      <th style="width: 60px">Qty.</th>
      <th style="width: 90px">Serial/Lot #</th>
      <!-- <th style="width: 90px">Lot #</th> -->
      
      <th style="width: 90px">Picked Up Date</th>
      <th style="width: 90px">Capped Type</th>
      <?php if($this->session->userdata('account_type') == 'dme_admin' && $count > 1 || $this->session->userdata('account_type') == 'dme_user' && $count > 1) :?>
        <?php $count = 0;
          foreach($summaries as $info) { 
            $count++;
            if($count == 1){
              if($info['original_activity_typeid'] == 1 && $info['activity_typeid'] == 2)
              {
                $info['activity_name'] = "Delivery";
                $info['activity_typeid'] = 1;
              }
              if($info['activity_typeid'] != 2){

        ?>
        <th style="width: 1px" class="action_data">Cancel Item(s)</th>
        <?php } } } ?>
      <?php endif;?>
    </tr>
  </thead>
  <tbody>

<?php 
  if(!empty($summaries)) :
    foreach($summaries as $info) :
      
      if($info['parentID'] != "")
      {
        $hide_style = "";
      }
      else
      {
        $hide_style = "display:none;";
      }
      $disable_cancel = ""; 
      if($info['pickup_sub'] == "expired" || $info['pickup_sub'] == "discharged" || $info['pickup_sub'] == "revoked" || $info['activity_typeid'] == 2)
      {
        $disable_cancel = "disabled";
      }
      else
      {
        $disable_cancel = "";
      }
      if($info['original_activity_typeid'] == 1 && $info['activity_typeid'] == 2)
      {
        $info['activity_name'] = "Delivery";
        $info['activity_typeid'] = 1;
      }
?>
      <tr style="<?php if($info['canceled_order'] == 1) echo 'text-decoration:line-through' ?> <?php if($info['parentID'] != 0) echo 'visibility:hidden;position: fixed;top: 1px;left: 1px;' ?> <?php echo $hide_style; ?>" >
      <!--1. WO#-->
      <td>
        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][person_confirming_order]" value="<?php echo $fname." ".$lname_complete ?>" class="" />
        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][driver_name]" value="" class="name_of_driver" />
        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][uniqueID]" value="<?php echo $info['uniqueID'] ?>" class="hdn_unique_id" />
        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][key_desc]" value="<?php echo $info['key_desc'] ?>" />
        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][activity_typeid]" value="<?php echo $info['activity_typeid'] ?>" />
        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_respite_order]" value="<?php echo $info['pickedup_respite_order'] ?>" />
        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][orderID]" value="<?php echo $info['orderID'] ?>" />

        <a href="javascript:void(0)"><?php echo substr($info['uniqueID'],4,10) ?></a>
      </td>
      <!--2. Order Date-->
      <td style="width:105px">
        <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][order_date]" class="datepicker form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo date("m/d/Y", strtotime($info['pickup_date'])) ?>" />
      </td>
      <!--3. Activity Type-->
      <td>
        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][act_name]" value="<?php echo $info['activity_name'] ?>" />
        <?php 
          $activity_type_display = "";
          $address_type = get_address_type($info['addressID']);
          $address_sequence = 0;
          $address_count = 1;
          if($info['activity_name'] == "Delivery")
          {
            if(($address_type['type']) == 0)
            {
              $activity_type_display = "Delivery";
            }
            else if($address_type['type'] == 1)
            { 
              $ptmove_addresses_ID = get_ptmove_addresses_ID($info['patientID']);
              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                if($addresses_ID_row['id'] == $info['addressID'])
                {
                  $address_sequence = $address_count;
                  break;
                }
                $address_count++;
              }
              if($address_sequence == 1)
              {
                $activity_type_display = "Delivery (PT Move)";
              }
              else
              {
                $activity_type_display = "Delivery (PT Move ".$address_sequence.")";
              }
            }
            else 
            {
              $respite_addresses_ID = get_respite_addresses_ID($info['patientID']);
              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                if($addresses_ID_row['id'] == $info['addressID'])
                {
                  $address_sequence = $address_count;
                  break;
                }
                $address_count++;
              }
              if($address_sequence == 1)
              {
                $activity_type_display = "Delivery (Respite)";
              }
              else
              {
                $activity_type_display = "Delivery (Respite ".$address_sequence.")";
              }
            }
          }
          else if($info['activity_name'] == "Exchange")
          {
            if($address_type['type'] == '2')
            {
              $respite_addresses_ID = get_respite_addresses_ID($info['patientID']);
              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                if($addresses_ID_row['id'] == $info['addressID'])
                {
                  $address_sequence = $address_count;
                  break;
                }
                $address_count++;
              }
              if($address_sequence == 1)
              {
                $activity_type_display = "Exchange (Respite)";
              }
              else
              {
                $activity_type_display = "Exchange (Respite ".$address_sequence.")";
              }
            }else if($address_type['type'] == '1') {
              $ptmove_addresses_ID = get_ptmove_addresses_ID($info['patientID']);
              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                if($addresses_ID_row['id'] == $info['addressID'])
                {
                  $address_sequence = $address_count;
                  break;
                }
                $address_count++;
              }
              if($address_sequence == 1)
              {
                $activity_type_display = "Exchange (PT Move)";
              }
              else
              {
                $activity_type_display = "Exchange (PT Move ".$address_sequence.")";
              }
            }else{
              $activity_type_display = "Exchange";
            }
          }
          else if($info['activity_name'] == "PT Move")
          {
            $ptmove_addresses_ID = get_ptmove_addresses_ID($info['patientID']);
            foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
              if($addresses_ID_row['id'] == $info['addressID'])
              {
                $address_sequence = $address_count;
                break;
              }
              $address_count++;
            }
            if($address_sequence == 1)
            {
              $activity_type_display = "PT Move";
            }
            else
            {
              $activity_type_display = "PT Move ".$address_sequence;
            }
          }
          else if($info['activity_name'] == "Respite")
          {
            $respite_addresses_ID = get_respite_addresses_ID($info['patientID']);
            foreach($respite_addresses_ID as $key => $addresses_ID_row) {
              if($addresses_ID_row['id'] == $info['addressID'])
              {
                $address_sequence = $address_count;
                break;
              }
              $address_count++;
            }
            if($address_sequence == 1)
            {
              $activity_type_display = "Respite";
            }
            else
            {
              $activity_type_display = "Respite ".$address_sequence;
            }
          }
          else if($info['activity_name'] == "Pickup")
          {
            if(($address_type['type']) == 0)
            {
              $activity_type_display = "Pickup";
            }
            else if($address_type['type'] == 1)
            { 
              $ptmove_addresses_ID = get_ptmove_addresses_ID($info['patientID']);
              foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                if($addresses_ID_row['id'] == $info['addressID'])
                {
                  $address_sequence = $address_count;
                  break;
                }
                $address_count++;
              }
              if($address_sequence == 1)
              {
                $activity_type_display = "Pickup (PT Move)";
              }
              else
              {
                $activity_type_display = "Pickup (PT Move ".$address_sequence.")";
              }
            }
            else 
            {
              $respite_addresses_ID = get_respite_addresses_ID($info['patientID']);
              foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                if($addresses_ID_row['id'] == $info['addressID'])
                {
                  $address_sequence = $address_count;
                  break;
                }
                $address_count++;
              }
              if($address_sequence == 1)
              {
                $activity_type_display = "Pickup (Respite)";
              }
              else
              {
                $activity_type_display = "Pickup (Respite ".$address_sequence.")";
              }
            }
          }
        ?>
        <?php echo $activity_type_display; ?>
      </td>
      <!--4. Item #-->
      <td>
        <input type="text" value="<?php echo $info['item_num'] ?>" style="width:70px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][item_num]" class="item_num form-control" />
      </td>
      <!--5. Item Description-->
      <td style="width:auto">
        
  <?php 
        if($info['equipmentID'] == 181 || $info['equipmentID'] == 182 || $info['equipmentID'] == 170 || $info['equipmentID'] == 11) {
          if($info['equipmentID'] == 181 || $info['equipmentID'] == 182) :
            if($info['activity_typeid'] != 2) :
  ?>
              <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="patient_weight_required" title="Patient Weight is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />
  <?php

              //check if naay sub equipment using equipment id, work uniqueId
              $subequipment_id = get_subequipment_id($info['equipmentID']);
              //gets all the id's under the order
              if($subequipment_id)
              {
                $count = 0;
                foreach ($subequipment_id as $key) {
                  $value = get_equal_subequipment_order($key['equipmentID'], $info['uniqueID']);
                                         
                  if($value)
                  {
                    $count++;
                    if($info['equipmentID'] == 55 || $info['equipmentID'] == 20){
  ?>
                      <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo "Full Electric ".$info['key_desc']." (".$key['key_desc'].")"; ?></a>
  <?php 
                    } 
                    else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17)
                    { ?>
                      <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." (".$key['key_desc'].")"; ?></a>
   <?php           } else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71) { ?>
   <?php             if($count == 1)
                     { ?>
                     <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
  <?php         
                      echo $key['key_desc']." ".$info['key_desc'];
                      }else{
                        echo " (With ".$key['key_desc'].") </a>";
                      }
                    }  else { ?>
                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
       <?php      }  
                 }else if($info['equipmentID'] == 149 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176) { ?>
                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
      <?php     }
                } //end of foreach
              }else{
  ?>
                <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
  <?php 
              }
            else:

              //check if naay sub equipment using equipment id, work uniqueId
              $subequipment_id = get_subequipment_id($info['equipmentID']);
              //gets all the id's under the order
              if($subequipment_id)
              {
                $count = 0;
                foreach ($subequipment_id as $key) {
                  $value = get_equal_subequipment_order($key['equipmentID'], $info['uniqueID']);
                               
                  if($value)
                  {
                    $count++;
              if($info['equipmentID'] == 55 || $info['equipmentID'] == 20){
  ?>
                <a href="javascript:void(0)" style="" class="equipment_options_tooltip" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>"><?php echo "Full Electric ".$info['key_desc']." (".$key['key_desc'].")"; ?></a>
  <?php 
              } 
              else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17)
              { ?>
                <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." (".$key['key_desc'].")"; ?></a>
    <?php     } else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71) { ?>
     <?php             if($count == 1)
                     { ?>
                     <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
  <?php         
                      echo $key['key_desc']." ".$info['key_desc'];
                      }else{
                        echo " (With ".$key['key_desc'].") </a>";
                      }
                    }   else { ?>
                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
       <?php      } 
            } else if($info['equipmentID'] == 149 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176) { ?>
                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
      <?php     }
          } //end of foreach
              }else{
  ?>
                <a href="javascript:void(0)" style="" class="equipment_options_tooltip" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>"><?php echo $info['key_desc'] ?></a>
  <?php 
              }
            endif;
          else:
  ?>
            <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="lot_number_required" title="Lot Number is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />
  <?php   
          //check if naay sub equipment using equipment id, work uniqueId
        $subequipment_id = get_subequipment_id($info['equipmentID']);
        //gets all the id's under the order
        if($subequipment_id)
        {
          $count = 0;
          foreach ($subequipment_id as $key) {
            $value = get_equal_subequipment_order($key['equipmentID'], $info['uniqueID']);
                                         
            if($value)
            {
              $count++;
              if($info['equipmentID'] == 55 || $info['equipmentID'] == 20)
              {
  ?>
                <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo "Full Electric ".$info['key_desc']." (".$key['key_desc'].")"; ?></a>
  <?php       } 
              else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17)
              { ?>
                <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." (".$key['key_desc'].")"; ?></a>
  <?php       } else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71) { ?>
     <?php      if($count == 1)
                { ?>
                  <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
  <?php           echo $key['key_desc']." ".$info['key_desc'];
                }else{
                  echo " (With ".$key['key_desc'].") </a>";
                }
              }  else { ?>
                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
       <?php      }  
            } else if($info['equipmentID'] == 149 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176) { ?>
                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
      <?php     }
          } //endofforeach
        }else{
  ?>
          <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
  <?php 
        }
          endif;
        }else{
          if($info['equipmentID'] == 61 || $info['equipmentID'] == 29) { 
            $value = $info['orderID']+2; 
            $result = get_value_equipment($value);
            if($result['equipment_value'] == 5)
            {
  ?>
              <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">5L <?php echo $info['key_desc']; ?></a>
  <?php 
            }else if($result['equipment_value'] == 10) { 
  ?>
              <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">10L <?php echo $info['key_desc'] ?></a>
  <?php 
            } 
          }else{ 

            //check if naay sub equipment using equipment id, work uniqueId
            $subequipment_id = get_subequipment_id($info['equipmentID']);
            //gets all the id's under the order
            if($subequipment_id)
            {

              $count = 0;
              foreach ($subequipment_id as $key) {
                $value = get_equal_subequipment_order($key['equipmentID'], $info['uniqueID']);
                                             
                if($value)
                {
                  
                  $count++;
                  if($info['equipmentID'] == 55 || $info['equipmentID'] == 20){
  ?>
              <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo "Full Electric ".$info['key_desc']." (".$key['key_desc'].")"; ?></a>
  <?php 
                  } 
                  else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17)
                  { ?>
                    <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." (".$key['key_desc'].")"; ?></a>
  <?php           } else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71) { ?>
     <?php          if($count == 1)
                    { ?>
                      <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
  <?php               echo $key['key_desc']." ".$info['key_desc'];
                    }else{
                      echo " (With ".$key['key_desc'].") </a>";
                    }
                  } else { ?>
                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
       <?php      } 
                } else if($info['equipmentID'] == 149 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176) { ?>
                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
      <?php     }
              } //endofforeach
            }else{
  ?>
            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
  <?php 
            }
          } 
        }
  ?>
      </td>

        <!--5. Qty-->
<?php 
        if($info['categoryID'] != 3){
          if($info['categoryID'] == 2){
            if($info['equipment_value'] > 1){
?>
              <td style="width:75px">
                <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              </td>
<?php 
            }else{
?>
              <td style="width:75px">
<?php 
              if(get_noncapped_quantity($info['equipmentID'],$info['uniqueID']) == 0){
?>

                <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
<?php 
              }else{
                
                if($info['equipmentID'] == 4 || $info['equipmentID'] == 9)
                {
?>             
                <input type="text" value="<?php echo $info['equipment_value']; ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
<?php }else{ ?>
                <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
<?php  }
              }
?>
              </td>
<?php 
            }
          }else{
?>
            <td style="width:75px">
              <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
              <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            </td>
<?php 
          }
        }else{
          if($info['equipment_value'] > 1 || $info['equipment_value'] != ""){
            if($info['activity_typeid'] == 2){
              if($info['equipmentID'] == 11 || $info['equipmentID'] == 170) {
            ?>
              <td style="width:75px">
                  <input type="text" value="<?php echo $info['equipment_value']; ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                </td>
<?php 
              } 
            }else if($info['equipmentID'] == 306){
?>
              <td style="width:75px">
                 <input type="text" value="<?php echo get_misc_quantity($info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                 <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              </td>
<?php 
            }else{
              if(get_disposable_quantity($info['equipmentID'],$info['uniqueID']) == 0){
?>
                <td style="width:75px">
                  <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                </td>
<?php 
              }else{
?>
                <td style="width:75px">
                  <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                </td>
<?php 
                
              }
            }
          }else{
            if($info['equipmentID'] == 7){
?>
              <td style="width:75px">
                <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              </td>
<?php 
            }else{
?>
              <td style="width:75px">
                <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              </td>
<?php 
            }
          }  
        }
?>
        <!--6. Serial/Lot #-->
        <td>
<?php 
          if($info['parentID'] != "" ) {
            if($info['parentID'] == 0){
              if($info['serial_num'] == "pickup_order_only") {
          
?>    
                <input type="text" value="<?php echo get_original_serial_number($info['equipmentID'], $info['medical_record_id'], $info['uniqueID']) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
<?php 
              }else{
?>
          	    <input type="text" value="<?php echo $info['serial_num'] ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required />
<?php 
              }
            }else{
?>
              <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required /> 
<?php 
            }
          }else{
?>
      		  <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required />
<?php 
          }
?>
        </td>
        <!--pickedup date-->
        <td>
<?php 
          if($info['summary_pickup_date'] != '0000-00-00') :
            if($info['activity_typeid'] == 1 || $info['activity_typeid'] == 3 || $info['activity_typeid'] == 4 || $info['activity_typeid'] == 5):
?>
              <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
<?php 
            else:
?>
              <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required data-work-order="<?php echo $info['uniqueID'] ?>" />
<?php 
            endif;
          else :
            if($info['activity_typeid'] == 1 || $info['activity_typeid'] == 3 || $info['activity_typeid'] == 4 || $info['activity_typeid'] == 5):
?>
              <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
<?php 
            else:
?>
              <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required data-work-order="<?php echo $info['uniqueID'] ?>" />
<?php 
            endif;
          endif;
?>
        </td>

        <!--8. Capped Type-->
        <td>
<?php 
          if($info['type'] == 'Capped Item') :
?>
				    <p class="label label-info"><?php echo $info['type'] ?></p>
<?php 
          elseif($info['type'] == 'Non-Capped Item') :
?>
				   <p class="label label-warning"><?php echo $info['type'] ?></p>
<?php 
          else:
?>
					  <p class="label label-success"><?php echo $info['type'] ?></p>
<?php 
          endif;
?>
		   </td>

<?php 
        if($this->session->userdata('account_type') == 'dme_admin' && $count > 1 && $info['activity_typeid'] != 2 || $this->session->userdata('account_type') == 'dme_user' && $count > 1 && $info['activity_typeid'] != 2 ) :?>
          <td>
            <div class="checkbox" style="margin-top:4px">
              <?php echo form_open("",array("id"=>"canceled-order-form")); ?>
                 <label class="i-checks data_tooltip" title="Cancel Item">
                    <input type="checkbox" <?php if($info['canceled_order'] == 1) echo 'checked' ?> name="canceled_status" class="cancel_item_checkbox" <?php echo $disable_cancel ?> data-equipment-id="<?php echo $info['equipmentID'] ?>" data-id="<?php echo $info['medical_record_id'] ?>" data-fname="<?php echo $info['p_fname'] ?>" data-lname="<?php echo $info['p_lname'] ?>" data-hospice="<?php echo $info['hospice_name'] ?>" data-patient-id="<?php echo $info['patientID'] ?>" /><i></i>
                 </label>
              <?php echo form_close() ?>
            </div>
          </td>
<?php 
        endif;
?>
      </tr>
<?php 
    endforeach ;
  endif ;
?>
  </tbody>
</table>

<div class="pull-right" style="margin-left: 20px;">
  <label>DME Staff Member Delivered Order<span class="text-danger-dker">*</span></label>
  <input type="text"  class="form-control driver_name_to_save" id="exampleInputEmail1" placeholder="Delivered by" name="" style="margin-bottom:20px" value="">
</div>



<div class="pull-right" style="  margin-top: 70px !important;margin-right: -225px !important;">
  <button type="button" class="btn btn-danger pull-right data_tooltip" onclick="closeModalbox()">Close</button>
  
  <?php if($act_id == 4) :?>
    <div class="dummy-wrapper data_tooltip pull-right" title="Click save changes button first">
      <button type="button" class="btn btn-success pull-right btn-confirm-order " data-act-id="<?php echo $info['activity_typeid'] ?>" data-medical-id="<?php echo $medical_id ?>" data-unique-id="<?php echo $work_order_number ?>" style="margin-right:10px" disabled >Confirm Order</button>&nbsp
    </div>
  <?php else: ?>
    <button type="button" class="btn btn-success pull-right btn-confirm-order" data-act-id="<?php echo $info['activity_typeid'] ?>" data-medical-id="<?php echo $medical_id ?>" data-unique-id="<?php echo $work_order_number ?>" style="margin-right:10px">Confirm Order</button>&nbsp
  <?php endif;?>
<div>
<?php echo form_close() ;?>
</div>   
</div>


<div class="col-sm-12" style="padding-left:0px;">
<div class="pull-left">
    <a href="<?php echo base_url("order/print_confirm_details/".$medical_record_num."/".$work_order_number."/".$act_id."/".$hospice_id) ?>" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> Print</a>
</div>
</div>
