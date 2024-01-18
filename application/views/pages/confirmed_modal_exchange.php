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

  @media (max-width: 1265px){

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
  if(!empty($infos)):
    foreach($infos as $info) :

      $organization_id = $this->session->userdata('group_id');
      $hospice_type = get_hospice_type($info['ordered_by']);
      if($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $hospice_type['type'] == 1)
      {
        $logged_in_account_type = "Company";
      }
      else
      {
        $logged_in_account_type = "Hospice";
      }
?>

<?php
      $medical_id = $info['medical_record_id'];
      echo form_open("",array("class"=>"edit_patient_profile_form")) ?>
      <div class="row">
        <div class="">
          <div class="col-md-6" style="padding-left:30px;">
            <input type="hidden" id="hdn_patient_id_modal_exchange" name="hdn_patient_id" value="<?php echo $info['patientID'] ?>" />
            <input type="hidden" class="hdn_hospice_id" name="" value="<?php echo $info['hospiceID'] ?>" />
            <input type="hidden" name="current_hospiceID" value="<?php echo $info['hospiceID'] ?>" />

            <label>Customer Medical Record # <span style="color:red;">*</span></label>
            <div class="clearfix"></div>
            <div class="form-group">
              <input type="text"  class="form-control medical_record_num" id="exampleInputEmail1" placeholder="" name="medical_record_id" style="margin-bottom:10px" value="<?php echo $info['medical_record_id'] ?>">
            </div>

            <div class="clearfix"></div>
            <label><?php echo $logged_in_account_type; ?> Provider <span style="color:red;">*</span></label>

            <?php
              if($hospice_type['type'] == 1)
              {
            ?>
                <div class="">
                  <?php $companies = get_companies() ;?>
                    <div class="form-group">
                      <?php if(empty($info['organization_id'])) :?>
                        <select class="form-control hospice_provider_select" style="margin-bottom:10px" name="organization_id">
                            <option value="">-- Choose Hospice Provider --</option>
                            <?php foreach($companies as $company) :?>
                                <option value="<?php echo $company['hospiceID'] ?>"><?php echo $company['hospice_name'] ?></option>
                            <?php endforeach;?>
                        </select>
                      <?php else :?>
                        <select class="form-control hospice_provider_select" style="margin-bottom:10px" name="organization_id">
                            <option value="<?php echo $info['organization_id'] ?>">[-- <?php echo $info['hospice_name'] ?> --]</option>

                            <?php foreach($companies as $company) :?>
                                <option value="<?php echo $company['hospiceID'] ?>"><?php echo $company['hospice_name'] ?></option>
                            <?php endforeach;?>
                        </select>
                      <?php endif;?>
                    </div>
                </div>
                <input type="hidden" class="hdn_provider_name" name="organization_name" value="" />
            <?php
              }
              else
              {
            ?>
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
            <?php
              }
            ?>

            <div class="col-md-6" style="padding-left:0px;">
              <label>Customer Last Name <span style="color:red;">*</span></label>
              <div class="clearfix"></div>
              <div class="form-group">
                <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="" name="p_lname" style="margin-bottom:10px" value="<?php echo $info['p_lname'] ?>">
              </div>
            </div>

            <div class="col-md-6" >
              <label>Customer First Name <span style="color:red;">*</span></label>
              <div class="form-group">
                <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="" name="p_fname" style="margin-bottom:10px" value="<?php echo $info['p_fname'] ?>">
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="patient-address-fields" style="margin-left:-16px;">

              <div class="col-md-8">
                <div class="form-group">
                  <label>Customer Address <span class="text-danger-dker">*</span></label>
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
                    $equipment_phone_number = "";
                    if($equpment_location['type'] == 1)
                    {
                      $equipment_phone_number = get_equipment_phone_number($info['patientID']);
                    }
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
              <div class="clearfix">
              </div>
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
                  <input type="text" class="form-control" id="postal_confirm" placeholder="Postal" name="p_postalcode" style="margin-bottom:20px" value="<?php echo $equpment_location['postal_code'] ?>">
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
              <?php
                if(!empty($equipment_phone_number))
                {
              ?>
                  <input type="text" class="form-control person_num" id="" placeholder="Phone Number" name="phonenum" style="margin-bottom:10px" value="<?php echo $equipment_phone_number['ptmove_patient_phone']; ?>">
              <?php
                }else{
              ?>
                  <input type="text" class="form-control person_num" id="" placeholder="Phone Number" name="phonenum" style="margin-bottom:10px" value="<?php echo $info['p_phonenum']; ?>">
              <?php
                }
              ?>
            </div>
            <div class="col-md-6" >
              <label>Alt. Phone Number</label>
              <input type="text" class="form-control person_num" id="" placeholder="Alt. Phone Number" name="altphonenum" style="margin-bottom:10px" value="<?php echo $info['p_altphonenum'] ?>">
            </div>

            <div class="col-md-6" >
              <label>Next of Kin<span class="text-danger-dker">*</span></label>
              <input type="text" class="form-control " id="" placeholder="Next of Kin" name="nextofkin" style="margin-bottom:10px" value="<?php echo $info['p_nextofkin'] ?>">
            </div>
            <div class="col-md-6" >
              <label>Relationship<span class="text-danger-dker">*</span></label>
              <input type="text" class="form-control " id="" placeholder="Relationship" name="relationship" style="margin-bottom:10px" value="<?php echo $info['p_relationship'] ?>">
            </div>

            <div class="col-md-6">
              <label>Next of Kin Phone No.<span class="text-danger-dker">*</span></label>
              <input type="text" class="form-control person_num" id="" placeholder="Next of Kin Phone No." name="nextofkinnum" style="margin-bottom:20px" value="<?php echo $info['p_nextofkinnum'] ?>">
            </div>
            <div class="col-md-6">
              <label>Residence<span class="text-danger-dker">*</span></label>
              <?php
                if($hospice_type['type'] == 1)
                {
              ?>
                  <select class="form-control" name="deliver_to_type" style="margin-bottom:10px">
                    <option value="Skilled Nursing Facility" selected>Skilled Nursing Facility</option>
                  </select>
              <?php
                }
                else
                {
              ?>
              <?php
                  $patient_residence_array = array();
                  $ptmove_residence = get_new_patient_residence_v3($info['patientID']);
                  if(!empty($ptmove_residence)){
                    $check_result = check_if_ptmove_confirmed($ptmove_residence['order_uniqueID']);

                    if($check_result['order_status'] == "confirmed"){
                      $patient_residence_array = $ptmove_residence;
                    }else{
                      $ptmove_residence_new = get_new_patient_residence_v2($info['patientID'], $ptmove_residence['order_uniqueID']);
                      if(!empty($ptmove_residence_new)){
                        $patient_residence_array = $ptmove_residence_new;
                      }else{
                        $patient_residence_array = "";
                      }
                    }
                  }else{
                    $patient_residence_array = "";
                  }
                  if(!empty($patient_residence_array)){
                ?>
                    <select class="form-control" name="deliver_to_type" style="margin-bottom:10px">
                      <?php if($patient_residence_array['ptmove_patient_residence'] == "Home Care") :?>
                        <option value="Home Care">Home Care</option>
                        <option value="Group Home">Group Home</option>
                        <option value="Assisted Living">Assisted Living</option>
                        <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                        <option value="Hic Home">Hic Home</option>

                      <?php elseif($patient_residence_array['ptmove_patient_residence'] == "Group Home"):?>
                        <option value="Group Home">Group Home</option>
                        <option value="Home Care">Home Care</option>
                        <option value="Assisted Living">Assisted Living</option>
                        <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                        <option value="Hic Home">Hic Home</option>

                      <?php elseif($patient_residence_array['ptmove_patient_residence'] == "Assisted Living"):?>
                        <option value="Assisted Living">Assisted Living</option>
                        <option value="Group Home">Group Home</option>
                        <option value="Home Care">Home Care</option>
                        <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                        <option value="Hic Home">Hic Home</option>

                      <?php elseif($patient_residence_array['ptmove_patient_residence'] == "Skilled Nursing Facility"):?>
                        <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                        <option value="Group Home">Group Home</option>
                        <option value="Home Care">Home Care</option>
                        <option value="Assisted Living">Assisted Living</option>
                        <option value="Hic Home">Hic Home</option>

                      <?php else :?>
                        <option value="Hic Home">Hic Home</option>
                        <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                        <option value="Group Home">Group Home</option>
                        <option value="Home Care">Home Care</option>
                        <option value="Assisted Living">Assisted Living</option>

                      <?php endif;?>

                    </select>
              <?php
                  }else{
              ?>
                    <select class="form-control" name="deliver_to_type" style="margin-bottom:10px">
                      <?php if($info['deliver_to_type'] == "Home Care") :?>
                        <option value="Home Care">Home Care</option>
                        <option value="Group Home">Group Home</option>
                        <option value="Assisted Living">Assisted Living</option>
                        <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                        <option value="Hic Home">Hic Home</option>

                      <?php elseif($info['deliver_to_type'] == "Group Home"):?>
                        <option value="Group Home">Group Home</option>
                        <option value="Home Care">Home Care</option>
                        <option value="Assisted Living">Assisted Living</option>
                        <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                        <option value="Hic Home">Hic Home</option>

                      <?php elseif($info['deliver_to_type'] == "Assisted Living"):?>
                        <option value="Assisted Living">Assisted Living</option>
                        <option value="Group Home">Group Home</option>
                        <option value="Home Care">Home Care</option>
                        <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                        <option value="Hic Home">Hic Home</option>

                      <?php elseif($info['deliver_to_type'] == "Skilled Nursing Facility"):?>
                        <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                        <option value="Group Home">Group Home</option>
                        <option value="Home Care">Home Care</option>
                        <option value="Assisted Living">Assisted Living</option>
                        <option value="Hic Home">Hic Home</option>

                      <?php else :?>
                        <option value="Hic Home">Hic Home</option>
                        <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                        <option value="Group Home">Group Home</option>
                        <option value="Home Care">Home Care</option>
                        <option value="Assisted Living">Assisted Living</option>

                      <?php endif;?>

                    </select>
              <?php
                  }
                }
              ?>
            </div>
            <!-- <input type="text" class="form-control " id="" placeholder="Residence" name="deliver_to_type" style="margin-bottom:10px" value="<?php echo $info['deliver_to_type'] ?>"> -->

               <!--  <div class="col-md-6">
                <label>DME Staff Member Delivered Order<span class="text-danger-dker">*</span></label>
                <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="Delivered by" name="driver_name" style="margin-bottom:20px" value="">
                </div> -->
            <input type="hidden" value="<?php echo $act_id ?>" name="act_typeid" />
            <div style="margin-right:15px;">
              <button type="button" class="btn btn-primary pull-right save_edit_changes_confirmation" data-id="<?php echo $info['medical_record_id'] ?>" data-addressID="<?php echo $addressID; ?>"  name="" style="margin-bottom:10px">Save Changes</button>
            </div>
          </div>
        </div>
      </div>
<?php
    endforeach;
  endif;
  echo form_close()
?>


<?php $fname = $this->session->userdata('firstname'); ?>
<?php $lname_complete = $this->session->userdata('lastname'); ?>
<?php $lname = substr($this->session->userdata('lastname'),0,1); ?>

<div class="panel panel-default">
<div class="panel-heading font-bold">
<h4>Customer Order Summary</h4>
</div>
<div class="panel-body">

<?php echo form_open("",array("class"=>"update_order_summary_exchange")) ;?>

<div class="col-md-6">
      <div class="col-md-6" style="margin-left:-30px">
        <label>DME Staff Member Confirming Work Order<span class="text-danger-dker">*</span></label>
        <input type="text"  class="form-control confirmed_by" id="exampleInputEmail1" placeholder="<?php echo $fname." ".$lname."." ?>" name="" style="margin-bottom:20px" value="<?php echo $fname." ".$lname_complete ?>" readonly>
      </div>
</div>


    <table class="table table-striped bg-white b-a col-md-12 edit_patient_orders" id="confirm_info_table" style="margin-top:0px;margin-left: 0px;">
      <thead>
        <tr>
          <th style="width: 40px">WO#</th>
          <th style="width: 60px">Scheduled Order Date</th>
          <th style="width: 90px">Activity Type</th>
          <th style="width: 60px">Item #</th>
          <th style="width: 90px">Item Description</th>
          <th style="width: 60px">Qty.</th>
          <th style="width: 90px">Serial/Lot #</th>
          <th style="width: 90px">Picked Up Date</th>
          <th style="width: 90px">Capped Type</th>
          <?php
            $counter = 0;
            foreach($summaries as $info_here)
            {
              if($info_here['parentID'] == 0)
              {
                $counter++;
              }
            }

            if(($this->session->userdata('account_type') == 'dme_admin' && $counter > 1) || ($this->session->userdata('account_type') == 'dme_user' && $counter > 1)) :
          ?>
              <th style="width: 1px" class="action_data">Cancel Item(s)</th>
          <?php endif;?>
        </tr>
      </thead>
      <tbody>
        <?php
          $index = 0;
          if(!empty($summaries)) :
            $packaged_items_ids_list = [486,163,164,68,159,160,161,162,316,325,334,343,466,36,178,422,259,415,174,490,492,67,157];
            $packaged_item_sign = 0;
            $packaged_items_list = array();
            $patient_lift_sling_count = 1;
            $patient_lift_sling_count_p2 = 1;
            $patient_lift_sling_loop_count = 0;
            $sequence_count = 0;
            $canceled_equipment = array();
            $o2_concentrator_follow_up_sign = 0;
            $o2_concentrator_follow_up_equipmentID = 0;
            $o2_concentrator_follow_up_uniqueID = 0;
            $o2_concentrator_follow_up_uniqueID_old = 0;
            foreach($summaries as $info) :
              if(!in_array($info['equipmentID'], $packaged_items_ids_list))
              {
                if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343)
                {
                  if($info['uniqueID_reference'] != 0)
                  {
                    $o2_concentrator_follow_up_sign = 1;
                    $o2_concentrator_follow_up_equipmentID = $info['equipmentID'];
                    $o2_concentrator_follow_up_uniqueID = $info['uniqueID_reference'];
                    $o2_concentrator_follow_up_uniqueID_old = $info['uniqueID'];
                  }
                }

                if($info['parentID'] != "" && $info['parentID'] == 0)
                {
                  $hide_style = "";
                }
                else
                {
                  $hide_style = "display:none;";
                }

                if($info['canceled_order'] == 1)
                {
                  $canceled_equipment[] = $info['equipmentID'];
                }
                $sequence_count++;

                $canceled_design = "";
                if($info['canceled_order'] == 1)
                {
                  $canceled_design = 'text-decoration:line-through';
                }
                else
                {
                  if(in_array($info['equipmentID'],$canceled_equipment))
                  {
                    $canceled_design = 'text-decoration:line-through';
                  }
                }

                $visibility_design = "";
                if($info['parentID'] != 0)
                {
                  $visibility_design = 'visibility:hidden;position: fixed;top: 1px;left: 1px;';
                }
          ?>
                <input type="hidden" value="<?php echo $info['activity_typeid']; ?>" class="workorder_activity_type">
                <tr id="confirm_exchange_tr_<?php echo $info['equipmentID'] ?>" style="<?php echo $canceled_design; ?> <?php echo $visibility_design; ?> <?php echo $hide_style; ?>">

                  <!--WO#-->
                  <td>
                    <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][person_confirming_order]" value="<?php echo $fname." ".$lname_complete ?>" class="" />
                    <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][driver_name]" value="" class="name_of_driver" />
                    <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][uniqueID]" value="<?php echo $info['uniqueID'] ?>" class="hdn_unique_id" />
                    <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][key_desc]" value="<?php echo $info['key_desc'] ?>" />
                    <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][activity_typeid]" value="<?php echo $info['activity_typeid'] ?>" />
                    <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_respite_order]" value="<?php echo $info['pickedup_respite_order'] ?>" />
                    <a href="javascript:void(0)"  ><?php echo substr($info['uniqueID'],4,10) ?></a>
                  </td>

                  <!--Order Date-->
                  <td style="width:105px">
                    <?php
                      if($info['activity_typeid'] != 2):
                        if($info['original_activity_typeid'] == 5):
                    ?>
                          <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][order_date]" class="datepicker_order_date_exchange form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo date("m-d-Y", strtotime($info['pickup_date'])); ?>" />
                    <?php
                        else:
                    ?>
                          <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][order_date]" class="datepicker_order_date_exchange form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo date("m-d-Y", strtotime(get_original_order_date($info['equipmentID'], $info['medical_record_id'], $info['uniqueID']))); ?>" />
                    <?php
                        endif;
                      else:
                    ?>
                        <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][order_date]" class="datepicker_order_date_exchange form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo date("m-d-Y", strtotime($info['pickup_date'])); ?>" />
                    <?php
                      endif;
                    ?>
                  </td>

                  <!--Act. Type-->
                  <td>
                    <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][act_name]" value="<?php echo $info['activity_name'] ?>" />
                    <?php
                      if($info['activity_name'] == "Exchange")
                      {
                        $address_sequence = 0;
                        $address_count = 1;
                        $address = get_equipment_location($info['addressID']);
                        if($address['type'] == '2')
                        {
                          $respite_addresses_ID = get_respite_addresses_ID_v2($info['patientID']);
                          if(count($respite_addresses_ID) > 1)
                          {
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
                          }
                          else
                          {
                            $activity_type_display = "Exchange (Respite)";
                          }
                        }else if($address['type'] == '1') {

                          $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
                          if(count($ptmove_addresses_ID) > 1)
                          {
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
                              $activity_type_display = "Exchange (CUS Move)";
                            }
                            else
                            {
                              $activity_type_display = "Exchange (CUS Move ".$address_sequence.")";
                            }
                          }
                          else
                          {
                            $activity_type_display = "Exchange (CUS Move)";
                          }
                        }else{
                          $activity_type_display = "Exchange";
                        }
                      }
                      else
                      {
                        if($info['activity_name'] == "Pickup")
                        {
                          if($info['original_activity_typeid'] == 3)
                          {
                            if ($info['activity_typeid'] == 3) {
                              $activity_type_display = "Exchange";
                            }
                            else
                            {
                              $activity_type_display = "Pickup";
                            }
                          }
                          else
                          {
                            $activity_type_display = $info['activity_name'];
                          }
                        }
                        else
                        {
                          $activity_type_display = $info['activity_name'];
                        }
                      }
                    ?>
                    <?php echo $activity_type_display; ?>
                  </td>

                  <!--Item #-->
                  <td>
                    <?php
                      if($info['equipment_company_item_no'] != "0")
                      {
                        echo $info['equipment_company_item_no'];
                      }
                      else
                      {
                        $subequipments = get_subequipment_id_v2($info['equipmentID']);
                        foreach ($subequipments as $key) {
                          if($key['equipment_company_item_no'] != "0")
                          {
                            $used_subequipment = get_equal_subequipment_order($key['equipmentID'], $info['uniqueID']);
                            if(!empty($used_subequipment))
                            {
                              echo $key['equipment_company_item_no'];
                            }
                          }
                        }
                      }
                    ?>
                  </td>

                  <!--Item Description-->
                  <td style="width:auto;text-transform:uppercase !important;">
                    <?php
                      if($info['equipmentID'] == 181 || $info['equipmentID'] == 182 || $info['equipmentID'] == 170 || $info['equipmentID'] == 11)
                      {
                        if($info['equipmentID'] == 181 || $info['equipmentID'] == 182) :
                          if($info['activity_typeid'] != 2) :
                    ?>
                            <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="patient_weight_required" title="Customer Weight is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />
                    <?php
                            if($info['equipmentID'] == 55 || $info['equipmentID'] == 20)
                            {
                    ?>
                              <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                            }
                            else
                            {
                    ?>
                              <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                            }
                          else:
                            if($info['equipmentID'] == 55 || $info['equipmentID'] == 20)
                            {
                    ?>
                              <a href="javascript:void(0)" style="" class="equipment_options_tooltip" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                            }
                            else
                            {
                    ?>
                              <a href="javascript:void(0)" style="" class="equipment_options_tooltip" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                            }
                          endif;
                        else:
                    ?>
                          <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="lot_number_required" title="Lot Number is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />
                    <?php
                          if($info['equipmentID'] == 55 || $info['equipmentID'] == 20)
                          {
                    ?>
                            <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                          }
                          else
                          {
                    ?>
                            <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                          }
                        endif;
                      }else{
                        if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343)
                        {
                          $value = $info['orderID']+2;
                          $result = get_value_equipment($value);
                    ?>
                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                        }
                        else
                        {
                          //check if naay sub equipment using equipment id, work uniqueId
                          $subequipment_id = get_subequipment_id($info['equipmentID']);
                          //gets all the id's under the order
                          if($subequipment_id)
                          {
                            $count = 0;
                            $my_count_sign = 0;
                            $equipment_count = 0;
                            $my_first_sign = 0;
                            $my_second_sign = 0;
                            foreach ($subequipment_id as $key) {
                              $value = get_equal_subequipment_order($key['equipmentID'], $info['uniqueID']);
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
                                //full electric hospital bed
                                if($info['equipmentID'] == 55 || $info['equipmentID'] == 20){
                    ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
                    <?php
                                }
                                //hi-low full electric hospital bed
                                else if($info['equipmentID'] == 19 || $info['equipmentID'] == 398)
                                {
                    ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
                    <?php
                                }
                                else if($info['equipmentID'] == 56 || $info['equipmentID'] == 21)
                                {
                    ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a>
                    <?php
                                }
                                else if($info['equipmentID'] == 353)
                                {
                    ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a>
                    <?php
                                }
                                else if($info['equipmentID'] == 196)
                                {
                    ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $key['key_desc'].""; ?></a>
                    <?php
                                }
                                else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17 || $info['equipmentID'] == 66 || $info['equipmentID'] == 39)
                                {
                    ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." ".$key['key_desc'].""; ?></a>
                    <?php
                                }
                                // Oxygen E Portable System && Oxygen Liquid Portable
                                else if($info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179 || $info['equipmentID'] == 16)
                                {
                    ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                  break;
                                }
                                //Oxygen Cylinder Rack
                                else if($info['equipmentID'] == 32 || $info['equipmentID'] == 393)
                                {
                    ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">Oxygen <?php echo $key['key_desc']; ?></a>
                    <?php
                                  break;
                                }
                                else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71 || $info['equipmentID'] == 269 || $info['equipmentID'] == 64)
                                {
                                  if($my_count_sign == 0)
                                  {
                                    if($count == 1)
                                    {
                    ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
                    <?php
                                      if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
                                      {
                                        $key['key_desc'] = '16" Narrow';
                                      } else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85) {
                                        $key['key_desc'] = '18" Standard';
                                      } else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126) {
                                        $key['key_desc'] = '20" Wide';
                                      } else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127) {
                                        $key['key_desc'] = '22" Extra Wide';
                                      } else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128) {
                                        $key['key_desc'] = '24" Bariatric';
                                      }
                                      echo $key['key_desc']." ".$info['key_desc'];
                                    }
                                    else
                                    {
                                      echo " With ".$key['key_desc']." </a>";
                                    }
                                  }
                                  else
                                  {
                    ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"> 20" Wide
                    <?php
                                    echo $info['key_desc'];
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
                                else if($info['equipmentID'] == 69 || $info['equipmentID'] == 48)
                                {
                    ?>
                                  <a href="javascript:void(0)" class="">
                    <?php
                                    if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
                                    {
                                      $key['key_desc'] = '17" Narrow';
                                    }
                                    else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
                                    {
                                      $key['key_desc'] = '19" Standard';
                                    }
                                    echo $key['key_desc']." ".$info['key_desc']." </a>";
                                }
                                else if($info['equipmentID'] == 282)
                                {

                                }
                                else if($info['equipmentID'] == 30)
                                {
                                  if(date("Y-m-d", $info['uniqueID']) >= "2016-05-24")
                                  {
                                    if($count == 3)
                                    {
                    ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?>
                    <?php
                                      echo " With ".$key['key_desc']." </a>";
                                    }
                                  }
                                  else
                                  {
                    ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?>
                    <?php
                                  }
                                }
                                else if($info['equipmentID'] == 306 || $info['equipmentID'] == 309 || $info['equipmentID'] == 313 || $info['equipmentID'] == 40 || $info['equipmentID'] == 32 || $info['equipmentID'] == 393 || $info['equipmentID'] == 67 || $info['equipmentID'] == 66 || $info['equipmentID'] == 4 || $info['equipmentID'] == 36)
                                {
                    ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                                  <br />
                    <?php
                                  $samp =  get_misc_item_description($info['equipmentID'],$info['uniqueID']);
                                  if(strlen($samp) > 20)
                                  {
                                    echo "<span style='font-weight:400;color:#696666;'>".substr($samp,0,19)."...</span>";
                                  }
                                  else
                                  {
                                    echo "<span style='font-weight:400;color:#696666;'>".$samp."</span>";
                                  }
                                  break;
                                }
                                else if($info['equipmentID'] == 62 || $info['equipmentID'] == 31)
                                {
                                  $samp_conserving_device =  get_oxygen_conserving_device($info['equipmentID'],$info['uniqueID']);
                                  if($count == 1)
                                  {
                    ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?> <?php echo $samp_conserving_device; ?></a>
                    <?php
                                  }
                                }
                                else
                                {
                                  if($info['categoryID'] == 1)
                                  {
                                    $non_capped_copy = get_non_capped_copy($info['equipmentID']);
                                    if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                                    {
                    ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                      break;
                                    }
                                    else if($non_capped_copy['noncapped_reference'] == 14)
                                    {
                    ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                    }
                                    else if($non_capped_copy['noncapped_reference'] == 282)
                                    {
                                      $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($info['equipmentID'],$info['uniqueID']);
                    ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?></a>
                    <?php
                                      break;
                                    }
                                    else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                                    {
                    ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a>
                    <?php
                                    }
                                    else if($non_capped_copy['noncapped_reference'] == 353)
                                    {
                    ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a>
                    <?php
                                    }
                                    else
                                    {
                    ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                    }
                                  }
                                  else
                                  {
                    ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                  }
                                }
                              }
                              else if($info['equipmentID'] == 62 || $info['equipmentID'] == 31)
                              {
                                break;
                              }
                              //for Oxygen E cylinder do not remove as it will cause errors
                              else if($info['equipmentID'] == 32 || $info['equipmentID'] == 393)
                              {

                              }
                              else if($info['equipmentID'] == 282)
                              {
                                $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($info['equipmentID'],$info['uniqueID']);
                    ?>
                                <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?> With <?php echo $samp_hospital_bed_extra_long;?></a>
                    <?php
                                break;
                              }
                              else if($info['equipmentID'] == 10 || $info['equipmentID'] == 36 || $info['equipmentID'] == 31 || $info['equipmentID'] == 32 || $info['equipmentID'] == 393 || $info['equipmentID'] == 282 || $info['equipmentID'] == 286 || $info['equipmentID'] == 62 || $info['equipmentID'] == 313 || $info['equipmentID'] == 309 || $info['equipmentID'] == 306|| $info['equipmentID'] == 4 )
                              {
                    ?>
                                <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                break;
                              }
                              else if($info['equipmentID'] == 178 || $info['equipmentID'] == 9 || $info['equipmentID'] == 149)
                              {
                    ?>
                                <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php     }
                              //for equipments with subequipment but does not fall in $value
                              else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17 || $info['equipmentID'] == 398 || $info['equipmentID'] == 196 || $info['equipmentID'] == 353 || $info['equipmentID'] == 56 || $info['equipmentID'] == 21 || $info['equipmentID'] == 30 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179 || $info['equipmentID'] == 39 || $info['equipmentID'] == 66 || $info['equipmentID'] == 19 || $info['equipmentID'] == 269 || $info['equipmentID'] == 64 ||$info['equipmentID'] == 49 || $info['equipmentID'] == 20 || $info['equipmentID'] == 55 || $info['equipmentID'] ==71 || $info['equipmentID'] == 69 || $info['equipmentID'] == 48)
                              {
                                if($info['equipmentID'] == 196 || $info['equipmentID'] == 56 || $info['equipmentID'] == 21 || $info['equipmentID'] == 353)
                                {
                                  if($patient_lift_sling_loop_count == 0)
                                  {
                                    $patient_lift_sling_count++;
                                    if($patient_lift_sling_count == 6)
                                    {
                    ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                      $patient_lift_sling_loop_count++;
                                    }
                                  }
                                  else
                                  {
                                    $patient_lift_sling_count_p2++;
                                    if($patient_lift_sling_count_p2 == 6)
                                    {
                    ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                      $patient_lift_sling_loop_count++;
                                    }
                                  }
                                }
                                // Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
                                else if($info['equipmentID'] == 69 || $info['equipmentID'] == 48)
                                {
                                  $equipment_count++;
                                  if($equipment_count == 2)
                                  {
                    ?>
                                    <a href="javascript:void(0)" class="show_on_print" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id'] ?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                  }
                                }
                              }
                              else
                              {
                                if($info['categoryID'] == 1)
                                {
                                  $non_capped_copy = get_non_capped_copy($info['equipmentID']);
                                  if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
                                  {
                    ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                    break;
                                  }
                                  else if($non_capped_copy['noncapped_reference'] == 14)
                                  {
                    ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                  }
                                  else
                                  {

                                  }
                                }
                                else
                                {
                    ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                                }
                              }
                            } //endofforeach
                          }
                          else
                          {
                    ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                    <?php
                          }
                        }
                      }
                    ?>
                  </td>

                  <!--QTY-->
                  <td style="width:75px">
                    <?php
                      if($info['categoryID'] != 3){
                        if($info['categoryID'] == 2){
                          if($info['equipment_value'] > 1){
                    ?>
                            <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                            <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                          }else{
                            if($info['equipmentID'] == 30)
                            {
                    ?>
                              <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                              <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                            }else{
                              if(get_noncapped_quantity($info['equipmentID'],$info['uniqueID']) == 0){
                    ?>
                                <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                              }else{
                    ?>
                                <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                              }
                            }
                          }
                        }else{
                          if($info['equipmentID'] == 313 || $info['equipmentID'] == 206)
                          {
                    ?>
                            <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                            <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                          }
                          else
                          {
                    ?>
                            <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                            <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                          }
                        }
                      }else{
                        if($info['equipment_value'] > 1 || $info['equipment_value'] != ""){
                          if($info['activity_typeid'] == 2){
                            if($info['equipmentID'] == 11 || $info['equipmentID'] == 170){
                    ?>
                              <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                              <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                            }
                          }else if($info['equipmentID'] == 306){
                    ?>
                            <input type="text" value="<?php echo get_misc_quantity($info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                            <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                          }else{
                            if(get_disposable_quantity($info['equipmentID'],$info['uniqueID']) == 0){
                    ?>
                              <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                              <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                            }else{
                    ?>
                              <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                              <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                            }
                          }
                        }else{
                          if($info['equipmentID'] == 7){
                    ?>
                            <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                            <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                          }else{
                    ?>
                            <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                            <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                    <?php
                          }
                        }
                      }
                    ?>
                  </td>

                  <!--serial/lot#-->
                  <td>
                    <?php
                      if($info['parentID'] == 0) :
                        if($info['serial_num'] == "pickup_order_only") :
                    ?>
                          <input type="text" value="<?php echo get_serial_num($info['equipmentID'],$info['medical_record_id']) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
                    <?php
                        else:
                          $canceled_design_serial_num = "";
                          $canceled_design_value = "";
                          if($info['canceled_order'] == 1)
                          {
                            // $canceled_design_serial_num = 'disabled';
                            $canceled_design_value = "---";
                          }
                          else
                          {
                            if(in_array($info['equipmentID'],$canceled_equipment))
                            {
                              // $canceled_design_serial_num = 'disabled';
                              $canceled_design_value = "---";
                            }
                          }
                          if(!empty($info['serial_num']))
                          {
                    ?>
                            <input type="text" value="<?php echo $info['serial_num'] ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required <?php echo $canceled_design_serial_num; ?> />
                    <?php
                          }
                          else
                          {
                    ?>
                            <input type="text" value="<?php echo $canceled_design_value; ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required <?php echo $canceled_design_serial_num; ?> />
                    <?php
                          }
                        endif;
                      else:
                    ?>
                        <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required />
                    <?php
                      endif;
                    ?>
                  </td>

                  <!--pickup date-->
                  <td>
                    <?php
                      if($info['summary_pickup_date'] != '0000-00-00') :
                        if($info['activity_typeid'] == 1 || $info['activity_typeid'] == 3 || $info['activity_typeid'] == 4):
                    ?>
                          <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker_exchange pickup_date_<?php echo $info['equipmentID'] ?> form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
                    <?php
                        else:
                    ?>
                          <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker_exchange pickup_date_<?php echo $info['equipmentID'] ?> form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required data-work-order="<?php echo $info['uniqueID'] ?>" />
                    <?php
                        endif;
                      else:
                        if($info['activity_typeid'] == 1 || $info['activity_typeid'] == 3 || $info['activity_typeid'] == 4):
                    ?>
                          <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker_exchange form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
                    <?php
                        else:
                          if($info['actual_order_date'] == '0000-00-00')
                          {
                    ?>
                            <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker_exchange form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
                    <?php
                          }
                          else
                          {
                            $canceled_design_pickupdate = "";
                            $canceled_design_pickupdate_border = "";
                            if($info['canceled_order'] == 1)
                            {
                              $canceled_design_pickupdate = 'disabled';
                              $canceled_design_pickupdate_border = "border-color: #efebeb !important;";
                            }
                            else
                            {
                              if(in_array($info['equipmentID'],$canceled_equipment))
                              {
                                $canceled_design_pickupdate = 'disabled';
                                $canceled_design_pickupdate_border = "border-color: #efebeb !important;";
                              }
                            }
                    ?>
                            <input type="text" id="" value="" style="width:100px;margin:0px !important;border-color:red !important; <?php echo $canceled_design_pickupdate_border; ?>" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date_exchange pickup_date_<?php echo $info['equipmentID'] ?> datepicker_exchange disabled_pickedup_before_confirming form-control auto_fillout_pickedup" required data-work-order="<?php echo $info['uniqueID'] ?>" <?php echo $canceled_design_pickupdate; ?> />
                    <?php
                          }
                        endif;
                      endif;
                    ?>
                  </td>

                  <!--Item Category-->
                  <td>
                    <?php if($info['type'] == 'Capped Item') :?>
                      <p class="label label-info"><?php echo $info['type'] ?></p>
                    <?php elseif($info['type'] == 'Non-Capped Item') :?>
                       <p class="label label-warning"><?php echo $info['type'] ?></p>
                    <?php else:?>
                      <p class="label label-success"><?php echo $info['type'] ?></p>
                    <?php endif;?>
                  </td>

                  <?php
                    if(($this->session->userdata('account_type') == 'dme_admin' && $counter > 1) || ($this->session->userdata('account_type') == 'dme_user' && $counter > 1)) :
                      $canceled_design_checkbox = "";
                      if($info['canceled_order'] == 1)
                      {
                        $canceled_design_checkbox = 'checked';
                      }
                      else
                      {
                        if(in_array($info['equipmentID'],$canceled_equipment))
                        {
                          $canceled_design_checkbox = 'checked';
                        }
                      }
                  ?>
                      <td>
                        <div class="checkbox" style="margin-top:4px">
                          <label class="i-checks data_tooltip" title="Cancel Item">
                            <input type="checkbox" <?php if($info['canceled_order'] == 1) echo 'checked' ?>
                                    name="canceled_status"
                                    class="cancel_item_checkbox_exchange checkbox_<?php echo $info['equipmentID'] ?>" <?php echo $disable_cancel ?>
                                    data-equipment-id="<?php echo $info['equipmentID'] ?>"
                                    data-id="<?php echo $info['medical_record_id'] ?>"
                                    data-fname="<?php echo $info['p_fname'] ?>"
                                    data-lname="<?php echo $info['p_lname'] ?>"
                                    data-hospice="<?php echo $info['hospice_name'] ?>"
                                    data-patient-id="<?php echo $info['patientID'] ?>"
                                    data-order-unique-id="<?php echo $info['uniqueID'] ?>"
                                    data-cancel-sequence-order="<?php echo $sequence_count ?>"
                                    <?php echo $canceled_design_checkbox; ?>
                            />
                            <i></i>
                          </label>
                        </div>
                      </td>
                      <input type="hidden" id="order_sequence_<?php echo $sequence_count; ?>" value="">
                  <?php
                    endif;
                  ?>
                </tr>
        <?php
                $index++;
              }
              else
              {
                $packaged_item_sign = 1;
                if($info['equipmentID'] == 486 || $info['equipmentID'] == 163 || $info['equipmentID'] == 164)
                {
                  $packaged_items_list[0][] = $info;
                }
                else if($info['equipmentID'] == 68 || $info['equipmentID'] == 159 || $info['equipmentID'] == 160 || $info['equipmentID'] == 161 || $info['equipmentID'] ==162)
                {
                  $packaged_items_list[1][] = $info;
                }
                else if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343 || $info['equipmentID'] == 466)
                {
                  $packaged_items_list[2][] = $info;
                }
                else if($info['equipmentID'] == 36 || $info['equipmentID'] == 466 || $info['equipmentID'] == 178)
                {
                  $packaged_items_list[3][] = $info;
                }
                else if($info['equipmentID'] == 422 || $info['equipmentID'] == 259)
                {
                  $packaged_items_list[4][] = $info;
                }
                else if($info['equipmentID'] == 415 || $info['equipmentID'] == 259)
                {
                  $packaged_items_list[5][] = $info;
                }
                else if($info['equipmentID'] == 174 || $info['equipmentID'] == 490 || $info['equipmentID'] ==492)
                {
                  $packaged_items_list[6][] = $info;
                }
                else if($info['equipmentID'] == 67 || $info['equipmentID'] == 157)
                {
                  $packaged_items_list[7][] = $info;
                }
              }
            endforeach;
            if(!empty($packaged_items_list))
            {
              foreach($packaged_items_list as $new_item_list)
              {
                foreach ($new_item_list as $info)
                {
                  if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343)
                  {
                    if($info['uniqueID_reference'] != 0)
                    {
                      $o2_concentrator_follow_up_sign = 1;
                      $o2_concentrator_follow_up_equipmentID = $info['equipmentID'];
                      $o2_concentrator_follow_up_uniqueID = $info['uniqueID_reference'];
                      $o2_concentrator_follow_up_uniqueID_old = $info['uniqueID'];
                    }
                  }

                  if($info['parentID'] != "" && $info['parentID'] == 0)
                  {
                    $hide_style = "";
                  }
                  else
                  {
                    $hide_style = "display:none;";
                  }

                  if($info['canceled_order'] == 1)
                  {
                    $canceled_equipment[] = $info['equipmentID'];
                  }
                  $sequence_count++;

                  $canceled_design = "";
                  if($info['canceled_order'] == 1)
                  {
                    $canceled_design = 'text-decoration:line-through';
                  }
                  else
                  {
                    if(in_array($info['equipmentID'],$canceled_equipment))
                    {
                      $canceled_design = 'text-decoration:line-through';
                    }
                  }

                  $visibility_design = "";
                  if($info['parentID'] != 0)
                  {
                    $visibility_design = 'visibility:hidden;position: fixed;top: 1px;left: 1px;';
                  }
        ?>
                  <input type="hidden" value="<?php echo $info['activity_typeid']; ?>" class="workorder_activity_type">
                  <tr id="confirm_exchange_tr_<?php echo $info['equipmentID'] ?>" style="<?php echo $canceled_design; ?> <?php echo $visibility_design; ?> <?php echo $hide_style; ?>">

                    <!--WO#-->
                    <td>
                      <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][person_confirming_order]" value="<?php echo $fname." ".$lname_complete ?>" class="" />
                      <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][driver_name]" value="" class="name_of_driver" />
                      <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][uniqueID]" value="<?php echo $info['uniqueID'] ?>" class="hdn_unique_id" />
                      <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][key_desc]" value="<?php echo $info['key_desc'] ?>" />
                      <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][activity_typeid]" value="<?php echo $info['activity_typeid'] ?>" />
                      <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_respite_order]" value="<?php echo $info['pickedup_respite_order'] ?>" />
                      <a href="javascript:void(0)"  ><?php echo substr($info['uniqueID'],4,10) ?></a>
                    </td>

                    <!--Order Date-->
                    <td style="width:105px">
                      <?php
                        if($info['activity_typeid'] != 2):
                          if($info['original_activity_typeid'] == 5):
                      ?>
                            <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][order_date]" class="datepicker_order_date_exchange form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo date("m-d-Y", strtotime($info['pickup_date'])); ?>" />
                      <?php
                          else:
                      ?>
                            <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][order_date]" class="datepicker_order_date_exchange form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo date("m-d-Y", strtotime(get_original_order_date($info['equipmentID'], $info['medical_record_id'], $info['uniqueID']))); ?>" />
                      <?php
                          endif;
                        else:
                      ?>
                          <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][order_date]" class="datepicker_order_date_exchange form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo date("m-d-Y", strtotime($info['pickup_date'])); ?>" />
                      <?php
                        endif;
                      ?>
                    </td>

                    <!--Act. Type-->
                    <td>
                      <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][act_name]" value="<?php echo $info['activity_name'] ?>" />
                      <?php
                        if($info['activity_name'] == "Exchange")
                        {
                          $address_sequence = 0;
                          $address_count = 1;
                          $address = get_equipment_location($info['addressID']);
                          if($address['type'] == '2')
                          {
                            $respite_addresses_ID = get_respite_addresses_ID_v2($info['patientID']);
                            if(count($respite_addresses_ID) > 1)
                            {
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
                            }
                            else
                            {
                              $activity_type_display = "Exchange (Respite)";
                            }
                          }else if($address['type'] == '1') {

                            $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
                            if(count($ptmove_addresses_ID) > 1)
                            {
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
                                $activity_type_display = "Exchange (CUS Move)";
                              }
                              else
                              {
                                $activity_type_display = "Exchange (CUS Move ".$address_sequence.")";
                              }
                            }
                            else
                            {
                              $activity_type_display = "Exchange (CUS Move)";
                            }
                          }else{
                            $activity_type_display = "Exchange";
                          }
                        }
                        else
                        {
                          if($info['activity_name'] == "Pickup")
                          {
                            if($info['original_activity_typeid'] == 3)
                            {
                              $activity_type_display = "Exchange";
                            }
                            else
                            {
                              $activity_type_display = $info['activity_name'];
                            }
                          }
                          else
                          {
                            $activity_type_display = $info['activity_name'];
                          }
                        }
                      ?>
                      <?php echo $activity_type_display; ?>
                    </td>

                    <!--Item #-->
                    <td>
                      <?php
                        if($info['equipment_company_item_no'] != "0")
                        {
                          echo $info['equipment_company_item_no'];
                        }
                        else
                        {
                          $subequipments = get_subequipment_id_v2($info['equipmentID']);
                          foreach ($subequipments as $key) {
                            if($key['equipment_company_item_no'] != "0")
                            {
                              $used_subequipment = get_equal_subequipment_order($key['equipmentID'], $info['uniqueID']);
                              if(!empty($used_subequipment))
                              {
                                echo $key['equipment_company_item_no'];
                              }
                            }
                          }
                        }
                      ?>
                    </td>

                    <!--Item Description-->
                    <td style="width:auto;text-transform:uppercase !important;">
                      <?php
                        if($info['equipmentID'] == 181 || $info['equipmentID'] == 182 || $info['equipmentID'] == 170 || $info['equipmentID'] == 11)
                        {
                          if($info['equipmentID'] == 181 || $info['equipmentID'] == 182) :
                            if($info['activity_typeid'] != 2) :
                      ?>
                              <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="patient_weight_required" title="Customer Weight is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />
                      <?php
                              if($info['equipmentID'] == 55 || $info['equipmentID'] == 20)
                              {
                      ?>
                                <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                              }
                              else
                              {
                      ?>
                                <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                              }
                            else:
                              if($info['equipmentID'] == 55 || $info['equipmentID'] == 20)
                              {
                      ?>
                                <a href="javascript:void(0)" style="" class="equipment_options_tooltip" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                              }
                              else
                              {
                      ?>
                                <a href="javascript:void(0)" style="" class="equipment_options_tooltip" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                              }
                            endif;
                          else:
                      ?>
                            <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="lot_number_required" title="Lot Number is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />
                      <?php
                            if($info['equipmentID'] == 55 || $info['equipmentID'] == 20)
                            {
                      ?>
                              <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                            }
                            else
                            {
                      ?>
                              <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                            }
                          endif;
                        }else{
                          if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343)
                          {
                            $value = $info['orderID']+2;
                            $result = get_value_equipment($value);
                      ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                          }
                          else
                          {
                            //check if naay sub equipment using equipment id, work uniqueId
                            $subequipment_id = get_subequipment_id($info['equipmentID']);
                            //gets all the id's under the order
                            if($subequipment_id)
                            {
                              $count = 0;
                              $my_count_sign = 0;
                              $equipment_count = 0;
                              $my_first_sign = 0;
                              $my_second_sign = 0;
                              foreach ($subequipment_id as $key) {
                                $value = get_equal_subequipment_order($key['equipmentID'], $info['uniqueID']);
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
                                  //full electric hospital bed
                                  if($info['equipmentID'] == 55 || $info['equipmentID'] == 20){
                      ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
                      <?php
                                  }
                                  //hi-low full electric hospital bed
                                  else if($info['equipmentID'] == 19 || $info['equipmentID'] == 398)
                                  {
                      ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
                      <?php
                                  }
                                  else if($info['equipmentID'] == 56 || $info['equipmentID'] == 21)
                                  {
                      ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a>
                      <?php
                                  }
                                  else if($info['equipmentID'] == 353)
                                  {
                      ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a>
                      <?php
                                  }
                                  else if($info['equipmentID'] == 196)
                                  {
                      ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $key['key_desc'].""; ?></a>
                      <?php
                                  }
                                  else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17 || $info['equipmentID'] == 66 || $info['equipmentID'] == 39)
                                  {
                      ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." ".$key['key_desc'].""; ?></a>
                      <?php
                                  }
                                  // Oxygen E Portable System && Oxygen Liquid Portable
                                  else if($info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179 || $info['equipmentID'] == 16)
                                  {
                      ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                    break;
                                  }
                                  //Oxygen Cylinder Rack
                                  else if($info['equipmentID'] == 32 || $info['equipmentID'] == 393)
                                  {
                      ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">Oxygen <?php echo $key['key_desc']; ?></a>
                      <?php
                                    break;
                                  }
                                  else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71 || $info['equipmentID'] == 269 || $info['equipmentID'] == 64)
                                  {
                                    if($my_count_sign == 0)
                                    {
                                      if($count == 1)
                                      {
                      ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
                      <?php
                                        if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
                                        {
                                          $key['key_desc'] = '16" Narrow';
                                        } else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85) {
                                          $key['key_desc'] = '18" Standard';
                                        } else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126) {
                                          $key['key_desc'] = '20" Wide';
                                        } else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127) {
                                          $key['key_desc'] = '22" Extra Wide';
                                        } else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128) {
                                          $key['key_desc'] = '24" Bariatric';
                                        }
                                        echo $key['key_desc']." ".$info['key_desc'];
                                      }
                                      else
                                      {
                                        echo " With ".$key['key_desc']." </a>";
                                      }
                                    }
                                    else
                                    {
                      ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"> 20" Wide
                      <?php
                                      echo $info['key_desc'];
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
                                  else if($info['equipmentID'] == 69 || $info['equipmentID'] == 48)
                                  {
                      ?>
                                    <a href="javascript:void(0)" class="">
                      <?php
                                      if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
                                      {
                                        $key['key_desc'] = '17" Narrow';
                                      }
                                      else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
                                      {
                                        $key['key_desc'] = '19" Standard';
                                      }
                                      echo $key['key_desc']." ".$info['key_desc']." </a>";
                                  }
                                  else if($info['equipmentID'] == 282)
                                  {

                                  }
                                  else if($info['equipmentID'] == 30)
                                  {
                                    if(date("Y-m-d", $info['uniqueID']) >= "2016-05-24")
                                    {
                                      if($count == 3)
                                      {
                      ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?>
                      <?php
                                        echo " With ".$key['key_desc']." </a>";
                                      }
                                    }
                                    else
                                    {
                      ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?>
                      <?php
                                    }
                                  }
                                  else if($info['equipmentID'] == 306 || $info['equipmentID'] == 309 || $info['equipmentID'] == 313 || $info['equipmentID'] == 40 || $info['equipmentID'] == 32 || $info['equipmentID'] == 393 || $info['equipmentID'] == 67 || $info['equipmentID'] == 66 || $info['equipmentID'] == 4 || $info['equipmentID'] == 36)
                                  {
                      ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                                    <br />
                      <?php
                                    $samp =  get_misc_item_description($info['equipmentID'],$info['uniqueID']);
                                    if(strlen($samp) > 20)
                                    {
                                      echo "<span style='font-weight:400;color:#696666;'>".substr($samp,0,19)."...</span>";
                                    }
                                    else
                                    {
                                      echo "<span style='font-weight:400;color:#696666;'>".$samp."</span>";
                                    }
                                    break;
                                  }
                                  else if($info['equipmentID'] == 62 || $info['equipmentID'] == 31)
                                  {
                                    $samp_conserving_device =  get_oxygen_conserving_device($info['equipmentID'],$info['uniqueID']);
                                    if($count == 1)
                                    {
                      ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?> <?php echo $samp_conserving_device; ?></a>
                      <?php
                                    }
                                  }
                                  else
                                  {
                                    if($info['categoryID'] == 1)
                                    {
                                      $non_capped_copy = get_non_capped_copy($info['equipmentID']);
                                      if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                                      {
                      ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                        break;
                                      }
                                      else if($non_capped_copy['noncapped_reference'] == 14)
                                      {
                      ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                      }
                                      else if($non_capped_copy['noncapped_reference'] == 282)
                                      {
                                        $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($info['equipmentID'],$info['uniqueID']);
                      ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?></a>
                      <?php
                                        break;
                                      }
                                      else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                                      {
                      ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a>
                      <?php
                                      }
                                      else if($non_capped_copy['noncapped_reference'] == 353)
                                      {
                      ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a>
                      <?php
                                      }
                                      else
                                      {
                      ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                      }
                                    }
                                    else
                                    {
                      ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                    }
                                  }
                                }
                                else if($info['equipmentID'] == 62 || $info['equipmentID'] == 31)
                                {
                                  break;
                                }
                                //for Oxygen E cylinder do not remove as it will cause errors
                                else if($info['equipmentID'] == 32 || $info['equipmentID'] == 393)
                                {

                                }
                                else if($info['equipmentID'] == 282)
                                {
                                  $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($info['equipmentID'],$info['uniqueID']);
                      ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?> With <?php echo $samp_hospital_bed_extra_long;?></a>
                      <?php
                                  break;
                                }
                                else if($info['equipmentID'] == 10 || $info['equipmentID'] == 36 || $info['equipmentID'] == 31 || $info['equipmentID'] == 32 || $info['equipmentID'] == 393 || $info['equipmentID'] == 282 || $info['equipmentID'] == 286 || $info['equipmentID'] == 62 || $info['equipmentID'] == 313 || $info['equipmentID'] == 309 || $info['equipmentID'] == 306|| $info['equipmentID'] == 4 )
                                {
                      ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                  break;
                                }
                                else if($info['equipmentID'] == 178 || $info['equipmentID'] == 9 || $info['equipmentID'] == 149)
                                {
                      ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php     }
                                //for equipments with subequipment but does not fall in $value
                                else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17 || $info['equipmentID'] == 398 || $info['equipmentID'] == 196 || $info['equipmentID'] == 353 || $info['equipmentID'] == 56 || $info['equipmentID'] == 21 || $info['equipmentID'] == 30 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179 || $info['equipmentID'] == 39 || $info['equipmentID'] == 66 || $info['equipmentID'] == 19 || $info['equipmentID'] == 269 || $info['equipmentID'] == 64 ||$info['equipmentID'] == 49 || $info['equipmentID'] == 20 || $info['equipmentID'] == 55 || $info['equipmentID'] ==71 || $info['equipmentID'] == 69 || $info['equipmentID'] == 48)
                                {
                                  if($info['equipmentID'] == 196 || $info['equipmentID'] == 56 || $info['equipmentID'] == 21 || $info['equipmentID'] == 353)
                                  {
                                    if($patient_lift_sling_loop_count == 0)
                                    {
                                      $patient_lift_sling_count++;
                                      if($patient_lift_sling_count == 6)
                                      {
                      ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                        $patient_lift_sling_loop_count++;
                                      }
                                    }
                                    else
                                    {
                                      $patient_lift_sling_count_p2++;
                                      if($patient_lift_sling_count_p2 == 6)
                                      {
                      ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                        $patient_lift_sling_loop_count++;
                                      }
                                    }
                                  }
                                  // Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
                                  else if($info['equipmentID'] == 69 || $info['equipmentID'] == 48)
                                  {
                                    $equipment_count++;
                                    if($equipment_count == 2)
                                    {
                      ?>
                                      <a href="javascript:void(0)" class="show_on_print" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id'] ?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                    }
                                  }
                                }
                                else
                                {
                                  if($info['categoryID'] == 1)
                                  {
                                    $non_capped_copy = get_non_capped_copy($info['equipmentID']);
                                    if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
                                    {
                      ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                      break;
                                    }
                                    else if($non_capped_copy['noncapped_reference'] == 14)
                                    {
                      ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                    }
                                    else
                                    {

                                    }
                                  }
                                  else
                                  {
                      ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                                  }
                                }
                              } //endofforeach
                            }
                            else
                            {
                      ?>
                              <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                      <?php
                            }
                          }
                        }
                      ?>
                    </td>

                    <!--QTY-->
                    <td style="width:75px">
                      <?php
                        if($info['categoryID'] != 3){
                          if($info['categoryID'] == 2){
                            if($info['equipment_value'] > 1){
                      ?>
                              <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" readonly />
                              <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                            }else{
                              if($info['equipmentID'] == 30)
                              {
                      ?>
                                <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" readonly />
                                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                              }else{
                                if(get_noncapped_quantity($info['equipmentID'],$info['uniqueID']) == 0){
                      ?>
                                  <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" readonly />
                                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                                }else{
                      ?>
                                  <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" readonly />
                                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                                }
                              }
                            }
                          }else{
                            if($info['equipmentID'] == 313 || $info['equipmentID'] == 206)
                            {
                      ?>
                              <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" readonly />
                              <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                            }
                            else
                            {
                      ?>
                              <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" readonly />
                              <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                            }
                          }
                        }else{
                          if($info['equipment_value'] > 1 || $info['equipment_value'] != ""){
                            if($info['activity_typeid'] == 2){
                              if($info['equipmentID'] == 11 || $info['equipmentID'] == 170){
                      ?>
                                <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                              }
                            }else if($info['equipmentID'] == 306){
                      ?>
                              <input type="text" value="<?php echo get_misc_quantity($info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                              <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                            }else{
                              if(get_disposable_quantity($info['equipmentID'],$info['uniqueID']) == 0){
                      ?>
                                <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                              }else{
                      ?>
                                <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                                <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                              }
                            }
                          }else{
                            if($info['equipmentID'] == 7){
                      ?>
                              <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                              <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                            }else{
                      ?>
                              <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][qty]" class="form-control equipment_qty" />
                              <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                      <?php
                            }
                          }
                        }
                      ?>
                    </td>

                    <!--serial/lot#-->
                    <td>
                      <?php
                        if($info['parentID'] == 0) :
                          if($info['serial_num'] == "pickup_order_only") :
                      ?>
                            <input type="text" value="<?php echo get_serial_num($info['equipmentID'],$info['medical_record_id']) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
                      <?php
                          else:
                            $canceled_design_serial_num = "";
                            $canceled_design_value = "";
                            if($info['canceled_order'] == 1)
                            {
                              // $canceled_design_serial_num = 'disabled';
                              $canceled_design_value = "---";
                            }
                            else
                            {
                              if(in_array($info['equipmentID'],$canceled_equipment))
                              {
                                // $canceled_design_serial_num = 'disabled';
                                $canceled_design_value = "---";
                              }
                            }
                            if(!empty($info['serial_num']))
                            {
                      ?>
                              <input type="text" value="<?php echo $info['serial_num'] ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required <?php echo $canceled_design_serial_num; ?> />
                      <?php
                            }
                            else
                            {
                      ?>
                              <input type="text" value="<?php echo $canceled_design_value; ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required <?php echo $canceled_design_serial_num; ?> />
                      <?php
                            }
                          endif;
                        else:
                      ?>
                          <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required />
                      <?php
                        endif;
                      ?>
                    </td>

                    <!--pickup date-->
                    <td>
                      <?php
                        if($info['summary_pickup_date'] != '0000-00-00') :
                          if($info['activity_typeid'] == 1 || $info['activity_typeid'] == 3 || $info['activity_typeid'] == 4):
                      ?>
                            <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker_exchange pickup_date_<?php echo $info['equipmentID'] ?> form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
                      <?php
                          else:
                      ?>
                            <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker_exchange pickup_date_<?php echo $info['equipmentID'] ?> form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required data-work-order="<?php echo $info['uniqueID'] ?>" />
                      <?php
                          endif;
                        else:
                          if($info['activity_typeid'] == 1 || $info['activity_typeid'] == 3 || $info['activity_typeid'] == 4):
                      ?>
                            <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker_exchange form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
                      <?php
                          else:
                            if($info['actual_order_date'] == '0000-00-00')
                            {
                      ?>
                              <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker_exchange form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
                      <?php
                            }
                            else
                            {
                              $canceled_design_pickupdate = "";
                              $canceled_design_pickupdate_border = "";
                              if($info['canceled_order'] == 1)
                              {
                                $canceled_design_pickupdate = 'disabled';
                                $canceled_design_pickupdate_border = "border-color: #efebeb !important;";
                              }
                              else
                              {
                                if(in_array($info['equipmentID'],$canceled_equipment))
                                {
                                  $canceled_design_pickupdate = 'disabled';
                                  $canceled_design_pickupdate_border = "border-color: #efebeb !important;";
                                }
                              }
                      ?>
                              <input type="text" id="" value="" style="width:100px;margin:0px !important;border-color:red !important; <?php echo $canceled_design_pickupdate_border; ?>" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date_exchange pickup_date_<?php echo $info['equipmentID'] ?> datepicker_exchange disabled_pickedup_before_confirming form-control auto_fillout_pickedup" required data-work-order="<?php echo $info['uniqueID'] ?>" <?php echo $canceled_design_pickupdate; ?> />
                      <?php
                            }
                          endif;
                        endif;
                      ?>
                    </td>

                    <!--Item Category-->
                    <td>
                      <?php if($info['type'] == 'Capped Item') :?>
                        <p class="label label-info"><?php echo $info['type'] ?></p>
                      <?php elseif($info['type'] == 'Non-Capped Item') :?>
                         <p class="label label-warning"><?php echo $info['type'] ?></p>
                      <?php else:?>
                        <p class="label label-success"><?php echo $info['type'] ?></p>
                      <?php endif;?>
                    </td>

                    <?php
                      if(($this->session->userdata('account_type') == 'dme_admin' && $counter > 1) || ($this->session->userdata('account_type') == 'dme_user' && $counter > 1)) :
                        $canceled_design_checkbox = "";
                        if($info['canceled_order'] == 1)
                        {
                          $canceled_design_checkbox = 'checked';
                        }
                        else
                        {
                          if(in_array($info['equipmentID'],$canceled_equipment))
                          {
                            $canceled_design_checkbox = 'checked';
                          }
                        }
                    ?>
                        <td>
                          <div class="checkbox" style="margin-top:4px">
                            <label class="i-checks data_tooltip" title="Cancel Item">
                              <input type="checkbox" <?php if($info['canceled_order'] == 1) echo 'checked' ?>
                                      name="canceled_status"
                                      class="cancel_item_checkbox_exchange checkbox_<?php echo $info['equipmentID'] ?>" <?php echo $disable_cancel ?>
                                      data-equipment-id="<?php echo $info['equipmentID'] ?>"
                                      data-id="<?php echo $info['medical_record_id'] ?>"
                                      data-fname="<?php echo $info['p_fname'] ?>"
                                      data-lname="<?php echo $info['p_lname'] ?>"
                                      data-hospice="<?php echo $info['hospice_name'] ?>"
                                      data-patient-id="<?php echo $info['patientID'] ?>"
                                      data-order-unique-id="<?php echo $info['uniqueID'] ?>"
                                      data-cancel-sequence-order="<?php echo $sequence_count ?>"
                                      <?php echo $canceled_design_checkbox; ?>
                              />
                              <i></i>
                            </label>
                          </div>
                        </td>
                        <input type="hidden" id="order_sequence_<?php echo $sequence_count; ?>" value="">
                    <?php
                      endif;
                    ?>
                  </tr>
        <?php
                  $index++;
                }
              }
            }
          endif ;
        ?>
      </tbody>
    </table>

      <div class="col-md-12" style="padding:0px;">
        <div class="pull-right" style="margin-left: 20px; width:17%; margin-right:2px;">
          <label style="margin-left:5px;">Delivery Date<span class="text-danger-dker">*</span></label>
          <input type="hidden" value="<?php echo $patient_first_order['actual_order_date']; ?>" id="initial_actual_order_date_value">
          <input type="text" class="form-control datepicker_2 input_actual_order_date" name="actual_order_date" placeholder="Click to choose date" style="margin-bottom:20px" value="">
        </div>
      </div>

       <div class="pull-right" style="margin-left: 20px;">
        <label>DME Staff Member Delivered Order<span class="text-danger-dker">*</span></label>
        <input type="text"  class="form-control driver_name_to_save" id="exampleInputEmail1" placeholder="Delivered by" name="" style="margin-bottom:20px" value="">
      </div>


      <input type="hidden" name="" class="current_act_type" value="<?php echo $act_id; ?>">
      <input type="hidden" name="" class="o2_concentrator_follow_up_sign" value="<?php echo $o2_concentrator_follow_up_sign; ?>" data-equipmentID="<?php echo $o2_concentrator_follow_up_equipmentID; ?>"  data-uniqueID="<?php echo $o2_concentrator_follow_up_uniqueID; ?>" data-uniqueID_old="<?php echo $o2_concentrator_follow_up_uniqueID_old; ?>">
      <div class="pull-right" style="  margin-top: 70px !important;margin-right: -225px !important;">
         <button type="button" class="btn btn-danger pull-right" onclick="closeModalbox()">Close</button>

        <div class="dummy-wrapper data_tooltip pull-right" title="Picked Up required">
          <button type="button" class="btn btn-success pull-right btn-confirm-exchange-order" data-medical-id="<?php echo $medical_id ?>" data-unique-id="<?php echo $work_order_number ?>" style="margin-right:10px" disabled>Confirm Order</button>&nbsp
        </div>

      <div>


<?php echo form_close() ;?>
</div>
</div>

    <!-- <td >
        <div class="checkbox" style="margin-top:4px">
        <?php echo form_open("",array("id"=>"canceled-order-form")) ?>
           <label class="i-checks data_tooltip" title="Cancel Item">
              <input type="checkbox" <?php if($info['canceled_order'] == 1) echo 'checked' ?> name="canceled_status" class="cancel_item_checkbox" data-equipment-id="<?php echo $info['equipmentID'] ?>" data-id="<?php echo $info['medical_record_id'] ?>" data-fname="<?php echo $info['p_fname'] ?>" data-lname="<?php echo $info['p_lname'] ?>" data-hospice="<?php echo $info['hospice_name'] ?>" data-patient-id="<?php echo $info['patientID'] ?>" /><i></i>
           </label>
        <?php echo form_close() ?>
        </div>
    </td> -->
