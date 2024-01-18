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

  /*@media (max-width: 1265px) AND (min-width: 992px){*/
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
      echo form_open("",array("class"=>"edit_patient_profile_form"))
?>

      <div class="row">
        <div class="">
          <div class="col-sm-6" style="padding-left:30px;">

            <input type="hidden" class="form-control " id="exampleInputEmail1"  placeholder="" name="update_patient_id" style="margin-bottom:10px" value="<?php echo $info['patientID'] ?>" />
            <input type="hidden" id="hdn_patient_id_confirmed_modal" name="hdn_patient_id" value="<?php echo $info['patientID'] ?>" />
            <input type="hidden" class="hdn_hospice_id" name="" value="<?php echo $info['hospiceID'] ?>" />
            <input type="hidden" name="current_hospiceID" value="<?php echo $info['hospiceID'] ?>" />

            <label>Customer Medical Record # <span style="color:red;">*</span></label>
            <div class="clearfix"></div>
            <div class="form-group" style="padding-right:14px;">
              <input type="text"  class="form-control medical_record_num" id="exampleInputEmail1" placeholder="" name="medical_record_id" style="margin-bottom:10px" value="<?php echo $info['medical_record_id'] ?>">
            </div>

            <div class="clearfix"></div>
            <label><?php echo $logged_in_account_type; ?> Provider <span style="color:red;">*</span></label>

            <?php
              if($hospice_type['type'] == 1)
              {
            ?>
                <div class="" style="padding-right:14px;">
                  <?php $companies = get_companies(); ?>
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
                <div class="" style="padding-right:14px;">
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

            <div class="col-md-6" style="padding-left:0px;">
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
                    $cus_move_details = "";
                    if($equpment_location['type'] == 1)
                    {
                      $cus_move_details = get_cus_move_details($info['patientID']);
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
                  <input type="text" class="form-control" id="postal_confirm" placeholder="Postal" name="p_postalcode" style="margin-bottom:20px" value="<?php echo $equpment_location['postal_code'] ?>">
                </div>
              </div>
            </div> <!-- .patient-address-fields -->

            <hr />

          </div>

          <div class="col-sm-6">

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
                if(!empty($cus_move_details))
                {
              ?>
                  <input type="text" class="form-control person_num" id="" placeholder="Phone Number" name="phonenum" style="margin-bottom:10px" value="<?php echo $cus_move_details['ptmove_patient_phone']; ?>">
              <?php
                }else{
              ?>
                  <input type="text" class="form-control person_num" id="" placeholder="Phone Number" name="phonenum" style="margin-bottom:10px" value="<?php echo $info['p_phonenum'] ?>">
              <?php
                }
              ?>

            </div>
            <div class="col-md-6" >
              <label>Alt. Phone Number</label>
              <?php
                if(!empty($cus_move_details))
                {
              ?>
                  <input type="text" class="form-control person_num" id="" placeholder="Alt. Phone Number" name="altphonenum" style="margin-bottom:10px" value="<?php echo $cus_move_details['ptmove_alt_patient_phone'] ?>">
              <?php
                }else{
              ?>
                  <input type="text" class="form-control person_num" id="" placeholder="Alt. Phone Number" name="altphonenum" style="margin-bottom:10px" value="<?php echo $info['p_altphonenum'] ?>">
              <?php
                }
              ?>

            </div>

            <div class="col-md-6" >

              <label>Next of Kin<span class="text-danger-dker">*</span></label>

              <?php if(!empty($cus_move_details)) :?>
                <input type="text" class="form-control " id="" placeholder="Next of Kin" name="nextofkin" style="margin-bottom:10px" value="<?php echo $cus_move_details['ptmove_nextofkin'] ?>">
              <?php else:?>
                <input type="text" class="form-control " id="" placeholder="Next of Kin" name="nextofkin" style="margin-bottom:10px" value="<?php echo $info['p_nextofkin'] ?>">
              <?php endif;?>

            </div>

            <div class="col-md-6" >
              <label>Relationship<span class="text-danger-dker">*</span></label>
              <?php if(!empty($cus_move_details)) :?>
                <input type="text" class="form-control " id="" placeholder="Relationship" name="relationship" style="margin-bottom:10px" value="<?php echo $cus_move_details['ptmove_nextofkinrelation'] ?>">
              <?php else:?>
                <input type="text" class="form-control " id="" placeholder="Relationship" name="relationship" style="margin-bottom:10px" value="<?php echo $info['p_relationship'] ?>">
              <?php endif;?>
            </div>

            <div class="col-md-6">
              <label>Next of Kin Phone No.<span class="text-danger-dker">*</span></label>
              <?php if(!empty($cus_move_details)) :?>
                <input type="text" class="form-control person_num" id="" placeholder="Next of Kin Phone No." name="nextofkinnum" style="margin-bottom:20px" value="<?php echo $cus_move_details['ptmove_nextofkinphone'] ?>">
              <?php else:?>
                <input type="text" class="form-control person_num" id="" placeholder="Next of Kin Phone No." name="nextofkinnum" style="margin-bottom:20px" value="<?php echo $info['p_nextofkinnum'] ?>">
              <?php endif;?>
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
                  if(!empty($cus_move_details)){
                    if($act_id == 4)
                    {
                      $patient_residence_array = $cus_move_details;
                    }
                    else
                    {
                      $check_result = check_if_ptmove_confirmed_v2($cus_move_details['order_uniqueID']);

                      if($check_result['order_status'] == "confirmed"){
                        $patient_residence_array = $cus_move_details;
                      }else{
                        $ptmove_residence_new = get_new_patient_residence_v2($info['patientID'], $cus_move_details['order_uniqueID']);
                        if(!empty($ptmove_residence_new)){
                          $patient_residence_array = $ptmove_residence_new;
                        }else{
                          $patient_residence_array = "";
                        }
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

            <?php
              if($act_id == 4) :
                $pt_id = $info['patientID'];
                $ptmove_info = $cus_move_details;
            ?>

                <input type="hidden" name="ptmove_unique_id" value="<?php echo $work_order_number ?>" />
                <div class="patient-address-fields" style="margin-left:0px;margin-top:10px">

                  <div class="col-md-8">
                    <div class="form-group">
                      <label>New CUS Move Address <span class="text-danger-dker">*</span></label>
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
                      <label>Customer Phone Number <span class="text-danger-dker">*</span></label>
                      <input type="text" class="form-control person_num ptmove_required6" id="" placeholder="Phone number" name="pt_phone" style="margin-bottom:10px;border:1px solid red" value="<?php echo $ptmove_info['ptmove_patient_phone'] ?>">
                    </div>
                  </div>

                </div> <!-- .patient-address-fields-->

            <?php
              endif;
            ?>

            <input type="hidden" value="<?php echo $act_id ?>" name="act_typeid" />
            <div class="col-md-4">
              <div style="margin-right:0px;margin-top:25px">
                <?php if($act_id == 4){ ?>
                  <button type="button" class="btn btn-primary pull-right save_edit_changes_confirmed" data-id="<?php echo $info['medical_record_id'] ?>" data-addressID="<?php echo $addressID; ?>" name="" style="margin-bottom:10px">Save Changes</button>
                <?php }else if($act_id == 5){ ?>
                  <button type="button" class="btn btn-primary pull-right save_edit_changes_confirmed_respite" data-id="<?php echo $info['medical_record_id'] ?>" data-addressID="<?php echo $addressID; ?>" name="" style="margin-bottom:10px">Save Changes</button>
                <?php }else{ ?>
                  <button type="button" class="btn btn-primary pull-right save_edit_changes_confirmation" data-id="<?php echo $info['medical_record_id'] ?>" data-addressID="<?php echo $addressID; ?>" name="" style="margin-bottom:10px">Save Changes</button>
                <?php } ?>
              </div>
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

<?php echo form_open("",array("class"=>"update_order_summary")) ;?>


<div class="col-md-6">
  <div class="col-md-6" style="margin-left:-30px">
    <label>DME Staff Member Confirming Work Order <span class="text-danger-dker">*</span></label>
    <input type="text"  class="form-control confirmed_by" id="exampleInputEmail1" placeholder="<?php echo $fname." ".$lname."." ?>" name="" style="margin-bottom:20px" value="<?php echo $fname." ".$lname_complete ?>" readonly>
  </div>
</div>

<table class="table table-striped bg-white b-a col-md-12 edit_patient_orders" id="confirm_info_table" style="margin-top:0px;margin-left: 0px;">
  <thead>
    <?php
      $count = count($summaries);
    ?>
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
      if($this->session->userdata('account_type') == 'dme_admin' && $count > 1 || $this->session->userdata('account_type') == 'dme_user' && $count > 1 || $this->session->userdata('account_type') == 'biller' && $count > 1) :
        $count = 0;
        $counter = 0;
        foreach($summaries as $info) {

          if($info['parentID'] == 0)
          {
            $counter++;
          }

          $count++;
          if($count == 1){
            if($info['original_activity_typeid'] == 1 && $info['activity_typeid'] == 2)
            {
              $info['activity_name'] = "Delivery";
              $info['activity_typeid'] = 1;
            }
            else if($info['original_activity_typeid'] == 5 && $info['activity_typeid'] == 2)
            {
              $info['activity_name'] = "Respite";
              $info['activity_typeid'] = 5;
            }
            else if($info['original_activity_typeid'] == 4 && $info['activity_typeid'] == 2)
            {
              $info['activity_name'] = "CUS Move";
              $info['activity_typeid'] = 4;
            }
          }
        }

        if(($this->session->userdata('account_type') == 'dme_admin' && $info['activity_typeid'] != 3 && $counter > 1) || ($this->session->userdata('account_type') == 'dme_user' && $info['activity_typeid'] != 3 && $counter > 1) || ($this->session->userdata('account_type') == 'biller' && $info['activity_typeid'] != 3 && $counter > 1)){
    ?>
          <th style="width: 1px" class="action_data">Cancel Item(s)</th>
    <?php
        }
      endif;
    ?>
    </tr>
  </thead>
  <tbody>

<?php
  if(!empty($summaries)) :
    $packaged_items_ids_list = [486,163,164,68,159,160,161,162,316,325,334,343,466,36,178,422,259,415,174,490,492,67,157];
    $packaged_item_sign = 0;
    $packaged_items_list = array();
    $commode_pail_count = 0;
    $item_description_count = 1;
    $oxygen_cylinder_rack_count = 1;
    $bed_chair_alarm_count = 1;
    $scale_chair_count = 1;
    $patient_lift_sling_count = 0;
    $patient_lift_sling_count_equipment = 1;
    $oxygen_cylinder_e_refill_count = 1;
    $oxygen_cylinder_m6_refill_count = 1;
    $sequence_count = 0;
    $adding_weight_sign = 0;
    $adding_weight_equipment = 0;
    $last_equipmentID = 0;
    $last_equipmentID_count = 1;
    $o2_concentrator_follow_up_sign = 0;
    $o2_concentrator_follow_up_equipmentID = 0;
    $o2_concentrator_follow_up_uniqueID = 0;
    $o2_concentrator_follow_up_uniqueID_old = 0;
    foreach($summaries as $info) :
      if(!in_array($info['equipmentID'], $packaged_items_ids_list))
      {
        if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343)
        {
          $o2_concentrator_follow_up_sign = 1;
          $o2_concentrator_follow_up_equipmentID = $info['equipmentID'];
          $o2_concentrator_follow_up_uniqueID = $info['uniqueID'];
        }
        else if($info['equipmentID'] == 484)
        {
          $o2_concentrator_follow_up_sign = 2;
          $o2_concentrator_follow_up_equipmentID = $info['equipmentID'];
          $o2_concentrator_follow_up_uniqueID = $info['uniqueID'];
        }

        if($info['equipmentID'] == 181 || $info['equipmentID'] == 182)
        {
          if($info['canceled_order'] == 0)
          {
            $adding_weight_sign = 1;
            $adding_weight_equipment = $info['equipmentID'];
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
        $disable_cancel = "";
        if($info['pickup_sub'] == "expired" || $info['pickup_sub'] == "discharged" || $info['pickup_sub'] == "revoked")
        {
          $result_here = check_if_match($info['uniqueID'],$info['patientID']);
          if(!empty($result_here))
          {
            $disable_cancel = "disabled";
          }
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
        else if($info['original_activity_typeid'] == 5 && $info['activity_typeid'] == 2)
        {
          $info['activity_name'] = "Respite";
          $info['activity_typeid'] = 5;
        }
        else if($info['original_activity_typeid'] == 4 && $info['activity_typeid'] == 2)
        {
          $info['activity_name'] = "CUS Move";
          $info['activity_typeid'] = 4;
        }
        else if($info['original_activity_typeid'] == 2 && $info['activity_typeid'] == 2)
        {
          $adding_weight_sign = 0;
          $adding_weight_equipment = 0;
        }
?>
        <input type="hidden" value="<?php echo $info['activity_typeid']; ?>" class="workorder_activity_type">
        <tr id="confirm_tr_<?php echo $info['equipmentID'] ?>" style="<?php if($info['canceled_order'] == 1) echo 'text-decoration:line-through' ?> <?php if($info['parentID'] != 0) echo 'visibility:hidden;position: fixed;top: 1px;left: 1px;' ?> <?php echo $hide_style; ?>" >

          <!--1. WO#-->
          <td>
            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][equipment_categoryID]" value="<?php echo $info['categoryID']; ?>" class="" />
            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][equipment_parentID]" value="<?php echo $info['parentID']; ?>" class="" />
            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][key_name]" value="<?php echo $info['key_name']; ?>" class="" />
            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][person_confirming_order]" value="<?php echo $fname." ".$lname_complete ?>" class="" />
            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][driver_name]" value="" class="name_of_driver" />
            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][uniqueID]" value="<?php echo $info['uniqueID'] ?>" class="hdn_unique_id" />
            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][key_desc]" value="<?php echo $info['key_desc'] ?>" />
            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][activity_typeid]" value="<?php echo $info['activity_typeid'] ?>" />
            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_respite_order]" value="<?php echo $info['pickedup_respite_order'] ?>" />
            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][orderID]" value="<?php echo $info['orderID'] ?>" />
            <!-- <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][actual_order_date]" value="" class="actual_order_date" /> -->
            <a href="javascript:void(0)"><?php echo substr($info['uniqueID'],4,10) ?></a>
          </td>

          <!--2. Order Date-->
          <td style="width:105px">
            <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][order_date]" class="datepicker form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo date("m-d-Y", strtotime($info['pickup_date'])) ?>" />
          </td>

          <!--3. Activity Type-->
          <td>
            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][act_name]" value="<?php echo $info['activity_name'] ?>" />
            <?php
              $sequence_count++;
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
                  $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
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
                    $activity_type_display = "Delivery (CUS Move)";
                  }
                  else
                  {
                    $activity_type_display = "Delivery (CUS Move ".$address_sequence.")";
                  }
                }
                else
                {
                  $respite_addresses_ID = get_respite_addresses_ID_v2($info['patientID']);
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
                  $respite_addresses_ID = get_respite_addresses_ID_v2($info['patientID']);
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
                  $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
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
                }else{
                  $activity_type_display = "Exchange";
                }
              }
              else if($info['activity_name'] == "CUS Move")
              {
                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
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
                  $activity_type_display = "CUS Move";
                }
                else
                {
                  $activity_type_display = "CUS Move ".$address_sequence;
                }
              }
              else if($info['activity_name'] == "Respite")
              {
                $respite_addresses_ID = get_respite_addresses_ID_v2($info['patientID']);
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
                  $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
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
                    $activity_type_display = "Pickup (CUS Move)";
                  }
                  else
                  {
                    $activity_type_display = "Pickup (CUS Move ".$address_sequence.")";
                  }
                }
                else
                {
                  $respite_addresses_ID = get_respite_addresses_ID_v2($info['patientID']);
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

          <!--5. Item Description-->
          <td style="width:auto;text-transform:uppercase !important;">
            <?php
              if($info['equipmentID'] == 181 || $info['equipmentID'] == 182 || $info['equipmentID'] == 170 || $info['equipmentID'] == 11) {
                if($info['equipmentID'] == 181 || $info['equipmentID'] == 182) :
                  if($info['activity_typeid'] != 2) :
            ?>
                    <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="patient_weight_required" title="Customer Weight is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />
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
                            <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
            <?php
                          }
                          else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17)
                          {
            ?>
                            <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." ".$key['key_desc'].""; ?></a>
            <?php
                          }else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71) {
                            if($count == 1)
                            {
            ?>
                              <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
            <?php
                              echo $key['key_desc']." ".$info['key_desc'];
                            }else{
                              echo " With ".$key['key_desc']." </a>";
                            }
                          }else{
            ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
            <?php
                          }
                        }else if($info['equipmentID'] == 149 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176) {
            ?>
                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
            <?php
                        }
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
                            <a href="javascript:void(0)" style="" class="equipment_options_tooltip" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
            <?php
                          }
                          else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17)
                          {
            ?>
                            <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." ".$key['key_desc'].""; ?></a>
            <?php
                          }else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71){
                            if($count == 1)
                            {
            ?>
                              <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
            <?php
                              echo $key['key_desc']." ".$info['key_desc'];
                            }else{
                              echo " With ".$key['key_desc']." </a>";
                            }
                          }else{
            ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
            <?php
                          }
                        }else if($info['equipmentID'] == 149 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176) {
            ?>
                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
            <?php
                        }
                      } //end of foreach
                    }else{
            ?>
                      <a href="javascript:void(0)" style="" class="equipment_options_tooltip" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>"><?php echo $info['key_desc'] ?></a>
            <?php
                    }
                  endif;
                else:
            ?>
                  <!-- <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="lot_number_required" title="Lot Number is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" /> -->
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
                          <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
            <?php
                        }
                        else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17)
                        {
            ?>
                          <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." ".$key['key_desc'].""; ?></a>
            <?php
                        }
                        else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71)
                        {
                          if($count == 1)
                          {
            ?>
                            <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
            <?php
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
                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
            <?php
                        }
                      }
                      else if($info['equipmentID'] == 11 || $info['equipmentID'] == 149 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 413 || $info['equipmentID'] == 170)
                      {
            ?>
                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
            <?php
                      }
                    } //endofforeach
                  }
                  else
                  {
            ?>
                    <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
            <?php
                  }
                endif;
              }
              else
              {
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
                  #4
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
                    foreach ($subequipment_id as $key)
                    {
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
                        if($info['equipmentID'] == 55 || $info['equipmentID'] == 20)
                        {
            ?>
                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
            <?php
                        } //hi-low full electric hospital bed
                        else if($info['equipmentID'] == 19 || $info['equipmentID'] == 398)
                        {
            ?>
                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
            <?php           }
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
                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $key['key_desc']; ?></a>
            <?php
                        }
                        else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17 || $info['equipmentID'] == 66 || $info['equipmentID'] == 39)
                        {
            ?>
                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." ".$key['key_desc'].""; ?></a>
            <?php
                        }
                        // Oxygen E Portable System && Oxygen Liquid Portable
                        else if($info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179 || $info['equipmentID'] == 4 || $info['equipmentID'] == 16 || $info['equipmentID'] == 36)
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
                        else if($info['equipmentID'] == 306 || $info['equipmentID'] == 309 || $info['equipmentID'] == 313 || $info['equipmentID'] == 40 || $info['equipmentID'] == 32 || $info['equipmentID'] == 393 || $info['equipmentID'] == 67 || $info['equipmentID'] == 66 || $info['equipmentID'] == 4)
                        {
            ?>
                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                          <br />
            <?php
                          $samp =  get_misc_item_description_v2($info['equipmentID'],$info['uniqueID'],$item_description_count);
                          $exploded_description = explode(" ", $samp);

                          if(!empty($samp))
                          {
                            if(strlen($samp) > 20)
                            {
                              $final_description = $exploded_description[0]." ".$exploded_description[1]." ".$exploded_description[2];
                              echo "<span style='font-weight:400;color:#696666;'>".$final_description."</span>";
                            }
                            else
                            {
                              echo "<span style='font-weight:400;color:#696666;'>".$samp."</span>";
                            }
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
                      else if($info['equipmentID'] == 32 || $info['equipmentID'] == 393 || $info['equipmentID'] == 40 || $info['equipmentID'] == 67)
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
                      else if($info['equipmentID'] == 178 || $info['equipmentID'] == 9 || $info['equipmentID'] == 149 )
                      {
            ?>
                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
            <?php
                      }
                      //for equipments with subequipment but does not fall in $value
                      else if($info['equipmentID'] == 55 || $info['equipmentID'] == 282 || $info['equipmentID'] == 54 || $info['equipmentID'] == 196 || $info['equipmentID'] == 398 || $info['equipmentID'] == 56 || $info['equipmentID'] == 353 || $info['equipmentID'] == 21 || $info['equipmentID'] == 17 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179 || $info['equipmentID'] == 30 || $info['equipmentID'] == 39 || $info['equipmentID'] == 66 || $info['equipmentID'] == 19 || $info['equipmentID'] == 269 || $info['equipmentID'] == 64 ||$info['equipmentID'] == 49 || $info['equipmentID'] == 20 || $info['equipmentID'] == 71 || $info['equipmentID'] == 69 || $info['equipmentID'] == 48)
                      {
                        if($info['equipmentID'] == 196 || $info['equipmentID'] == 56 || $info['equipmentID'] == 21 || $info['equipmentID'] == 353)
                        {
                          $patient_lift_sling_count++;
                          if($patient_lift_sling_count == 6)
                          {
            ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
            <?php
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

          <!--5. Qty-->
          <td style="width:75px">
            <?php
              if($info['categoryID'] != 3){
                if($info['categoryID'] == 2){
                  if($info['equipment_value'] > 1 || $info['equipmentID'] == 176){
            ?>

                    <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly/>
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  //Miscellaneous CAPPED and NONCAPPED
                  }else if($info['equipmentID'] == 309 || $info['equipmentID'] == 306){
                    if($info['equipmentID'] == 309)
                    {
                      $passed_equip_id = 312;
                    }
                    else
                    {
                      $passed_equip_id = 308;
                    }
            ?>
                    <input type="text" value="<?php echo get_noncapped_quantity_v2($passed_equip_id,$info['uniqueID'],$item_description_count); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly/>
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  // Oxygen Cylinder Rack - NONCAPPED or Bed and Chair Alarm - NONCAPPED  or Scale Chair - NONCAPPED   or  Large Mesh Sling - NONCAPPED
                  }else if($info['equipmentID'] == 32 || $info['equipmentID'] == 296 || $info['equipmentID'] == 181 || $info['equipmentID'] == 196){
            ?>
                    <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  }else{
                    if(get_noncapped_quantity($info['equipmentID'],$info['uniqueID']) == 0){
            ?>
                      <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                    }else{

                      if($info['equipmentID'] == 4 || $info['equipmentID'] == 9 || $info['equipmentID'] == 30)
                      {
            ?>
                        <input type="text" value="<?php echo $info['equipment_value']; ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                        <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                      }else{
            ?>
                        <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                        <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                      }
                    }
                  }
                }else{
                  $non_capped_copy = get_non_capped_copy($info['equipmentID']);
                  if($info['equipmentID'] == 313 || $info['equipmentID'] == 206)
                  {
            ?>
                    <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  }else if($non_capped_copy['noncapped_reference'] == 14){
            ?>
                    <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  }else{
            ?>
                    <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  }
                }
              }else{
                if($info['equipment_value'] > 1 || $info['equipment_value'] != ""){
                  if($info['activity_typeid'] == 2){
                    if($info['equipmentID'] == 11 || $info['equipmentID'] == 170) {
            ?>
                      <input type="text" value="<?php echo $info['equipment_value']; ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                    } else {
            ?>
                      <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                    }
                  }else if($info['equipmentID'] == 306){
            ?>
                    <input type="text" value="<?php echo get_misc_quantity($info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  }else{
                    if(get_disposable_quantity($info['equipmentID'],$info['uniqueID']) == 0){
                      if($info['equipmentID'] == 11 || $info['equipmentID'] == 170)
                      {
            ?>
                        <input type="text" value="0" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                        <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                      }else{
            ?>
                        <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                        <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                      }
                    }else{
            ?>
                      <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
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
                    <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  }
                }
              }
            ?>
          </td>

          <!--6. Serial/Lot #-->
          <td>
            <?php
              if($info['parentID'] != "" ) {
                if($info['parentID'] == 0){
                  if($info['canceled_order'] == 0){
                    if($info['serial_num'] == "pickup_order_only") {
                      //Miscellaneous CAPPED and NONCAPPED
                      if($info['equipmentID'] == 309 || $info['equipmentID'] == 306)
                      {
            ?>
                        <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $item_description_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
            <?php
                        $item_description_count++;
                      // Oxygen Cylinder, E Refill - DISPOSABLES
                      }else if($info['equipmentID'] == 11){
            ?>
                        <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $oxygen_cylinder_e_refill_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
            <?php
                        $oxygen_cylinder_e_refill_count++;
                      // Oxygen Cylinder, M6 Refill - DISPOSABLES
                      }else if($info['equipmentID'] == 170){
            ?>
                        <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $oxygen_cylinder_m6_refill_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
            <?php
                        $oxygen_cylinder_m6_refill_count++;
                      // Oxygen Cylinder Rack - NONCAPPED
                      }else if($info['equipmentID'] == 32){
            ?>
                        <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $oxygen_cylinder_rack_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
            <?php
                        $oxygen_cylinder_rack_count++;
                      // Bed and Chair Alarm - NONCAPPED
                      }else if($info['equipmentID'] == 296){
            ?>
                        <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $bed_chair_alarm_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
            <?php
                        $bed_chair_alarm_count++;
                      // Scale Chair - NONCAPPED
                      }else if($info['equipmentID'] == 181){
            ?>
                        <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $scale_chair_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
            <?php
                        $scale_chair_count++;
                      // Large Mesh Sling - NONCAPPED
                      }else if($info['equipmentID'] == 196){
            ?>
                        <input type="text" data-id="<?php echo $patient_lift_sling_count_equipment; ?>" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $patient_lift_sling_count_equipment) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
            <?php
                        $patient_lift_sling_count_equipment++;
                      }else{
                        $sign_repeated = 0;
                        foreach ($repeating_equipment as $loop_repeating_equipment) {
                          if($loop_repeating_equipment == $info['equipmentID'])
                          {
                            $sign_repeated = 1;
                          }
                        }
                        if($sign_repeated == 1)
                        {
                          if($last_equipmentID == 0)
                          {
                            $queried_serial_number = get_equipment_serial_number($info['equipmentID'], $info['uniqueID'],$last_equipmentID_count);
                          }
                          if($last_equipmentID == $info['equipmentID'] && $last_equipmentID != 0)
                          {
                            $last_equipmentID_count++;
                            $queried_serial_number = get_equipment_serial_number($info['equipmentID'], $info['uniqueID'],$last_equipmentID_count);
                          }
                          else
                          {
                            $last_equipmentID_count = 1;
                            $queried_serial_number = get_equipment_serial_number($info['equipmentID'], $info['uniqueID'],$last_equipmentID_count);
                          }
            ?>
                          <input type="text" value="<?php echo $queried_serial_number; ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
            <?php
                        }
                        else
                        {
            ?>
                          <input type="text" value="<?php echo get_original_serial_number($info['equipmentID'], $info['medical_record_id'], $info['uniqueID']) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
            <?php
                        }
                      }
                    }else{
                      ?>
                      <input type="text" value="<?php echo $info['serial_num']; ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> item_serial_numbers_confirmation form-control" data-orderID="<?php echo $info['orderID'] ?>" required />
            <?php
                      if ($info['equipmentID'] == 14 || $info['equipmentID'] == 425 || $info['equipmentID'] == 294 || $info['equipmentID'] == 295) {
            ?>
                        <input type="hidden" value="" name="serial_numbers[<?php echo $info['orderID']?>]" class="item_orderID<?php echo $info['orderID'] ?>" />
            <?php 
                      }
                    }
                  }else{
            ?>
                    <input type="text" value="---" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required />
            <?php
                  }
                }else{
            ?>
                  <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required />
            <?php
                }
              }else{
            ?>
                <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required />
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
                  <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker_confirm form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
            <?php
                else:
            ?>
                <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker_confirm form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required data-work-order="<?php echo $info['uniqueID'] ?>" />
            <?php
                endif;
              else :
                if($info['activity_typeid'] == 1 || $info['activity_typeid'] == 3 || $info['activity_typeid'] == 4 || $info['activity_typeid'] == 5):
            ?>
                  <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker_confirm form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
            <?php
                else:
            ?>
                  <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker_confirm form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required data-work-order="<?php echo $info['uniqueID'] ?>" />
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
            if(($this->session->userdata('account_type') == 'dme_admin' && $info['activity_typeid'] != 3 && $counter > 1) || ($this->session->userdata('account_type') == 'dme_user' && $info['activity_typeid'] != 3 && $counter > 1) || ($this->session->userdata('account_type') == 'biller' && $info['activity_typeid'] != 3 && $counter > 1)) :
          ?>
              <td>
                <div class="checkbox" style="margin-top:4px">
                  <?php
                    echo form_open("",array("id"=>"canceled-order-form"));
                  ?>
                      <label class="i-checks data_tooltip" title="Cancel Item">
                        <input type="checkbox" <?php if($info['canceled_order'] == 1) echo 'checked' ?>
                                name="canceled_status"
                                class="cancel_item_checkbox checkbox_<?php echo $info['equipmentID'] ?>" <?php echo $disable_cancel ?>
                                data-equipment-id="<?php echo $info['equipmentID'] ?>"
                                data-id="<?php echo $info['medical_record_id'] ?>"
                                data-fname="<?php echo $info['p_fname'] ?>"
                                data-lname="<?php echo $info['p_lname'] ?>"
                                data-order-id="<?php echo $info['orderID'] ?>"
                                data-hospice="<?php echo $info['hospice_name'] ?>"
                                data-patient-id="<?php echo $info['patientID'] ?>"
                                data-order-unique-id="<?php echo $info['uniqueID'] ?>"
                                data-cancel-sequence-order="<?php echo $sequence_count ?>"
                        />
                        <i></i>
                      </label>
                  <?php
                    echo form_close();
                  ?>
                </div>
              </td>
              <input type="hidden" id="order_sequence_<?php echo $sequence_count; ?>" value="">
          <?php
            endif;
          ?>
        </tr>
<?php
        if($info['equipmentID'] == 7)
        {
          $commode_pail_count++;
        }
        $last_equipmentID = $info['equipmentID'];
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
    endforeach ;
    if(!empty($packaged_items_list))
    {
      foreach($packaged_items_list as $new_item_list)
      {
        foreach ($new_item_list as $info)
        {
          if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343)
          {
            $o2_concentrator_follow_up_sign = 1;
            $o2_concentrator_follow_up_equipmentID = $info['equipmentID'];
            $o2_concentrator_follow_up_uniqueID = $info['uniqueID'];
          }
          else if($info['equipmentID'] == 484)
          {
            $o2_concentrator_follow_up_sign = 2;
            $o2_concentrator_follow_up_equipmentID = $info['equipmentID'];
            $o2_concentrator_follow_up_uniqueID = $info['uniqueID'];
          }

          if($info['equipmentID'] == 181 || $info['equipmentID'] == 182)
          {
            if($info['canceled_order'] == 0)
            {
              $adding_weight_sign = 1;
              $adding_weight_equipment = $info['equipmentID'];
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
          $disable_cancel = "";
          if($info['pickup_sub'] == "expired" || $info['pickup_sub'] == "discharged" || $info['pickup_sub'] == "revoked")
          {
            $result_here = check_if_match($info['uniqueID'],$info['patientID']);
            if(!empty($result_here))
            {
              $disable_cancel = "disabled";
            }
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
          else if($info['original_activity_typeid'] == 5 && $info['activity_typeid'] == 2)
          {
            $info['activity_name'] = "Respite";
            $info['activity_typeid'] = 5;
          }
          else if($info['original_activity_typeid'] == 4 && $info['activity_typeid'] == 2)
          {
            $info['activity_name'] = "CUS Move";
            $info['activity_typeid'] = 4;
          }
          else if($info['original_activity_typeid'] == 2 && $info['activity_typeid'] == 2)
          {
            $adding_weight_sign = 0;
            $adding_weight_equipment = 0;
          }
    ?>
          <input type="hidden" value="<?php echo $info['activity_typeid']; ?>" class="workorder_activity_type">
          <tr id="confirm_tr_<?php echo $info['equipmentID'] ?>" style="<?php if($info['canceled_order'] == 1) echo 'text-decoration:line-through' ?> <?php if($info['parentID'] != 0) echo 'visibility:hidden;position: fixed;top: 1px;left: 1px;' ?> <?php echo $hide_style; ?>" >

            <!--1. WO#-->
            <td>
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][equipment_categoryID]" value="<?php echo $info['categoryID']; ?>" class="" />
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][equipment_parentID]" value="<?php echo $info['parentID']; ?>" class="" />
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][key_name]" value="<?php echo $info['key_name']; ?>" class="" />
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][person_confirming_order]" value="<?php echo $fname." ".$lname_complete ?>" class="" />
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][driver_name]" value="" class="name_of_driver" />
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][uniqueID]" value="<?php echo $info['uniqueID'] ?>" class="hdn_unique_id" />
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][key_desc]" value="<?php echo $info['key_desc'] ?>" />
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][activity_typeid]" value="<?php echo $info['activity_typeid'] ?>" />
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_respite_order]" value="<?php echo $info['pickedup_respite_order'] ?>" />
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][orderID]" value="<?php echo $info['orderID'] ?>" />
              <!-- <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][actual_order_date]" value="" class="actual_order_date" /> -->
              <a href="javascript:void(0)"><?php echo substr($info['uniqueID'],4,10) ?></a>
            </td>

            <!--2. Order Date-->
            <td style="width:105px">
              <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][order_date]" class="datepicker form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo date("m-d-Y", strtotime($info['pickup_date'])) ?>" />
            </td>

            <!--3. Activity Type-->
            <td>
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][act_name]" value="<?php echo $info['activity_name'] ?>" />
              <?php
                $sequence_count++;
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
                    $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
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
                      $activity_type_display = "Delivery (CUS Move)";
                    }
                    else
                    {
                      $activity_type_display = "Delivery (CUS Move ".$address_sequence.")";
                    }
                  }
                  else
                  {
                    $respite_addresses_ID = get_respite_addresses_ID_v2($info['patientID']);
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
                    $respite_addresses_ID = get_respite_addresses_ID_v2($info['patientID']);
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
                    $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
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
                  }else{
                    $activity_type_display = "Exchange";
                  }
                }
                else if($info['activity_name'] == "CUS Move")
                {
                  $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
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
                    $activity_type_display = "CUS Move";
                  }
                  else
                  {
                    $activity_type_display = "CUS Move ".$address_sequence;
                  }
                }
                else if($info['activity_name'] == "Respite")
                {
                  $respite_addresses_ID = get_respite_addresses_ID_v2($info['patientID']);
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
                    $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
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
                      $activity_type_display = "Pickup (CUS Move)";
                    }
                    else
                    {
                      $activity_type_display = "Pickup (CUS Move ".$address_sequence.")";
                    }
                  }
                  else
                  {
                    $respite_addresses_ID = get_respite_addresses_ID_v2($info['patientID']);
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

            <!--5. Item Description-->
            <td style="width:auto;text-transform:uppercase !important;">
              <?php
                if($info['equipmentID'] == 181 || $info['equipmentID'] == 182 || $info['equipmentID'] == 170 || $info['equipmentID'] == 11) {
                  if($info['equipmentID'] == 181 || $info['equipmentID'] == 182) :
                    if($info['activity_typeid'] != 2) :
              ?>
                      <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="patient_weight_required" title="Customer Weight is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />
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
                              <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
              <?php
                            }
                            else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17)
                            {
              ?>
                              <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." ".$key['key_desc'].""; ?></a>
              <?php
                            }else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71) {
                              if($count == 1)
                              {
              ?>
                                <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
              <?php
                                echo $key['key_desc']." ".$info['key_desc'];
                              }else{
                                echo " With ".$key['key_desc']." </a>";
                              }
                            }else{
              ?>
                              <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
              <?php
                            }
                          }else if($info['equipmentID'] == 149 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176) {
              ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
              <?php
                          }
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
                              <a href="javascript:void(0)" style="" class="equipment_options_tooltip" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
              <?php
                            }
                            else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17)
                            {
              ?>
                              <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." ".$key['key_desc'].""; ?></a>
              <?php
                            }else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71){
                              if($count == 1)
                              {
              ?>
                                <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
              <?php
                                echo $key['key_desc']." ".$info['key_desc'];
                              }else{
                                echo " With ".$key['key_desc']." </a>";
                              }
                            }else{
              ?>
                              <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
              <?php
                            }
                          }else if($info['equipmentID'] == 149 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176) {
              ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
              <?php
                          }
                        } //end of foreach
                      }else{
              ?>
                        <a href="javascript:void(0)" style="" class="equipment_options_tooltip" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>"><?php echo $info['key_desc'] ?></a>
              <?php
                      }
                    endif;
                  else:
              ?>
                    <!-- <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="lot_number_required" title="Lot Number is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" /> -->
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
                            <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
              <?php
                          }
                          else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17)
                          {
              ?>
                            <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." ".$key['key_desc'].""; ?></a>
              <?php
                          }
                          else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71)
                          {
                            if($count == 1)
                            {
              ?>
                              <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>">
              <?php
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
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
              <?php
                          }
                        }
                        else if($info['equipmentID'] == 11 || $info['equipmentID'] == 149 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 413 || $info['equipmentID'] == 170)
                        {
              ?>
                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
              <?php
                        }
                      } //endofforeach
                    }
                    else
                    {
              ?>
                      <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
              <?php
                    }
                  endif;
                }
                else
                {
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
                    #4
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
                      foreach ($subequipment_id as $key)
                      {
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
                          if($info['equipmentID'] == 55 || $info['equipmentID'] == 20)
                          {
              ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
              <?php
                          } //hi-low full electric hospital bed
                          else if($info['equipmentID'] == 19 || $info['equipmentID'] == 398)
                          {
              ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." With ".$key['key_desc'].""; ?></a>
              <?php           }
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
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $key['key_desc']; ?></a>
              <?php
                          }
                          else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17 || $info['equipmentID'] == 66 || $info['equipmentID'] == 39)
                          {
              ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc']." ".$key['key_desc'].""; ?></a>
              <?php
                          }
                          // Oxygen E Portable System && Oxygen Liquid Portable
                          else if($info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179 || $info['equipmentID'] == 4 || $info['equipmentID'] == 16 || $info['equipmentID'] == 36)
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
                          else if($info['equipmentID'] == 306 || $info['equipmentID'] == 309 || $info['equipmentID'] == 313 || $info['equipmentID'] == 40 || $info['equipmentID'] == 32 || $info['equipmentID'] == 393 || $info['equipmentID'] == 67 || $info['equipmentID'] == 66 || $info['equipmentID'] == 4)
                          {
              ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
                            <br />
              <?php
                            $samp =  get_misc_item_description_v2($info['equipmentID'],$info['uniqueID'],$item_description_count);
                            $exploded_description = explode(" ", $samp);

                            if(!empty($samp))
                            {
                              if(strlen($samp) > 20)
                              {
                                $final_description = $exploded_description[0]." ".$exploded_description[1]." ".$exploded_description[2];
                                echo "<span style='font-weight:400;color:#696666;'>".$final_description."</span>";
                              }
                              else
                              {
                                echo "<span style='font-weight:400;color:#696666;'>".$samp."</span>";
                              }
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
                        else if($info['equipmentID'] == 32 || $info['equipmentID'] == 393 || $info['equipmentID'] == 40 || $info['equipmentID'] == 67)
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
                        else if($info['equipmentID'] == 178 || $info['equipmentID'] == 9 || $info['equipmentID'] == 149 )
                        {
              ?>
                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
              <?php
                        }
                        //for equipments with subequipment but does not fall in $value
                        else if($info['equipmentID'] == 55 || $info['equipmentID'] == 282 || $info['equipmentID'] == 54 || $info['equipmentID'] == 196 || $info['equipmentID'] == 398 || $info['equipmentID'] == 56 || $info['equipmentID'] == 353 || $info['equipmentID'] == 21 || $info['equipmentID'] == 17 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179 || $info['equipmentID'] == 30 || $info['equipmentID'] == 39 || $info['equipmentID'] == 66 || $info['equipmentID'] == 19 || $info['equipmentID'] == 269 || $info['equipmentID'] == 64 ||$info['equipmentID'] == 49 || $info['equipmentID'] == 20 || $info['equipmentID'] == 71 || $info['equipmentID'] == 69 || $info['equipmentID'] == 48)
                        {
                          if($info['equipmentID'] == 196 || $info['equipmentID'] == 56 || $info['equipmentID'] == 21 || $info['equipmentID'] == 353)
                          {
                            $patient_lift_sling_count++;
                            if($patient_lift_sling_count == 6)
                            {
              ?>
                              <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
              <?php
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

            <!--5. Qty-->
            <td style="width:75px">
              <?php
                if($info['categoryID'] != 3){
                  if($info['categoryID'] == 2){
                    if($info['equipment_value'] > 1 || $info['equipmentID'] == 176){
              ?>

                      <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                    //Miscellaneous CAPPED and NONCAPPED
                    }else if($info['equipmentID'] == 309 || $info['equipmentID'] == 306){
                      if($info['equipmentID'] == 309)
                      {
                        $passed_equip_id = 312;
                      }
                      else
                      {
                        $passed_equip_id = 308;
                      }
              ?>
                      <input type="text" value="<?php echo get_noncapped_quantity_v2($passed_equip_id,$info['uniqueID'],$item_description_count); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                    // Oxygen Cylinder Rack - NONCAPPED or Bed and Chair Alarm - NONCAPPED  or Scale Chair - NONCAPPED   or  Large Mesh Sling - NONCAPPED
                    }else if($info['equipmentID'] == 32 || $info['equipmentID'] == 296 || $info['equipmentID'] == 181 || $info['equipmentID'] == 196){
              ?>
                      <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                    }else{
                      if(get_noncapped_quantity($info['equipmentID'],$info['uniqueID']) == 0){
              ?>
                        <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                        <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                      }else{

                        if($info['equipmentID'] == 4 || $info['equipmentID'] == 9 || $info['equipmentID'] == 30)
                        {
              ?>
                          <input type="text" value="<?php echo $info['equipment_value']; ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                          <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                        }else{
              ?>
                          <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                          <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                        }
                      }
                    }
                  }else{
                    $non_capped_copy = get_non_capped_copy($info['equipmentID']);
                    if($info['equipmentID'] == 313 || $info['equipmentID'] == 206)
                    {
              ?>
                      <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                    }else if($non_capped_copy['noncapped_reference'] == 14){
              ?>
                      <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                    }else{
              ?>
                      <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" readonly />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                    }
                  }
                }else{
                  if($info['equipment_value'] > 1 || $info['equipment_value'] != ""){
                    if($info['activity_typeid'] == 2){
                      if($info['equipmentID'] == 11 || $info['equipmentID'] == 170) {
              ?>
                        <input type="text" value="<?php echo $info['equipment_value']; ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                        <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                      }
                    }else if($info['equipmentID'] == 306){
              ?>
                      <input type="text" value="<?php echo get_misc_quantity($info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                    }else{
                      if(get_disposable_quantity($info['equipmentID'],$info['uniqueID']) == 0){
                        if($info['equipmentID'] == 11 || $info['equipmentID'] == 170)
                        {
              ?>
                          <input type="text" value="0" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                          <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                        }else{
              ?>
                          <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                          <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                        }
                      }else{
              ?>
                        <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
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
                      <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
              <?php
                    }
                  }
                }
              ?>
            </td>

            <!--6. Serial/Lot #-->
            <td>
              <?php
                if($info['parentID'] != "" ) {
                  if($info['parentID'] == 0){
                    if($info['canceled_order'] == 0){
                      if($info['serial_num'] == "pickup_order_only") {
                        //Miscellaneous CAPPED and NONCAPPED
                        if($info['equipmentID'] == 309 || $info['equipmentID'] == 306)
                        {
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $item_description_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
              <?php
                          $item_description_count++;
                        // Oxygen Cylinder, E Refill - DISPOSABLES
                        }else if($info['equipmentID'] == 11){
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $oxygen_cylinder_e_refill_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
              <?php
                          $oxygen_cylinder_e_refill_count++;
                        // Oxygen Cylinder, M6 Refill - DISPOSABLES
                        }else if($info['equipmentID'] == 170){
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $oxygen_cylinder_m6_refill_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
              <?php
                          $oxygen_cylinder_m6_refill_count++;
                        // Oxygen Cylinder Rack - NONCAPPED
                        }else if($info['equipmentID'] == 32){
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $oxygen_cylinder_rack_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
              <?php
                          $oxygen_cylinder_rack_count++;
                        // Bed and Chair Alarm - NONCAPPED
                        }else if($info['equipmentID'] == 296){
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $bed_chair_alarm_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
              <?php
                          $bed_chair_alarm_count++;
                        // Scale Chair - NONCAPPED
                        }else if($info['equipmentID'] == 181){
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $scale_chair_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
              <?php
                          $scale_chair_count++;
                        // Large Mesh Sling - NONCAPPED
                        }else if($info['equipmentID'] == 196){
              ?>
                          <input type="text" data-id="<?php echo $patient_lift_sling_count_equipment; ?>" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $patient_lift_sling_count_equipment) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
              <?php
                          $patient_lift_sling_count_equipment++;
                        }else{
                          $sign_repeated = 0;
                          foreach ($repeating_equipment as $loop_repeating_equipment) {
                            if($loop_repeating_equipment == $info['equipmentID'])
                            {
                              $sign_repeated = 1;
                            }
                          }
                          if($sign_repeated == 1)
                          {
                            if($last_equipmentID == 0)
                            {
                              $queried_serial_number = get_equipment_serial_number($info['equipmentID'], $info['uniqueID'],$last_equipmentID_count);
                            }
                            if($last_equipmentID == $info['equipmentID'] && $last_equipmentID != 0)
                            {
                              $last_equipmentID_count++;
                              $queried_serial_number = get_equipment_serial_number($info['equipmentID'], $info['uniqueID'],$last_equipmentID_count);
                            }
                            else
                            {
                              $last_equipmentID_count = 1;
                              $queried_serial_number = get_equipment_serial_number($info['equipmentID'], $info['uniqueID'],$last_equipmentID_count);
                            }
              ?>
                            <input type="text" value="<?php echo $queried_serial_number; ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
              <?php
                          }
                          else
                          {
              ?>
                            <input type="text" value="<?php echo get_original_serial_number($info['equipmentID'], $info['medical_record_id'], $info['uniqueID']) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required  />
              <?php
                          }
                        }
                      }else{
              ?>
                        <input type="text" value="<?php echo $info['serial_num']; ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control serial_num_order_id<?php echo $info['orderID'] ?>" required />
              <?php
                      }
                    }else{
              ?>
                      <input type="text" value="---" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required />
              <?php
                    }
                  }else{
              ?>
                    <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required />
              <?php
                  }
                }else{
              ?>
                  <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> serial_num_order_id<?php echo $info['orderID'] ?> form-control" required />
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
                    <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker_confirm form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?> serial_num_order_id<?php echo $info['orderID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
              <?php
                  else:
              ?>
                  <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker_confirm form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" serial_num_order_id<?php echo $info['orderID'] ?> required data-work-order="<?php echo $info['uniqueID'] ?>" />
              <?php
                  endif;
                else :
                  if($info['activity_typeid'] == 1 || $info['activity_typeid'] == 3 || $info['activity_typeid'] == 4 || $info['activity_typeid'] == 5):
              ?>
                    <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker_confirm form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?> serial_num_order_id<?php echo $info['orderID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />
              <?php
                  else:
              ?>
                    <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker_confirm form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?> serial_num_order_id<?php echo $info['orderID'] ?>" required data-work-order="<?php echo $info['uniqueID'] ?>" />
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
              if(($this->session->userdata('account_type') == 'dme_admin' && $info['activity_typeid'] != 3 && $counter > 1) || ($this->session->userdata('account_type') == 'dme_user' && $info['activity_typeid'] != 3 && $counter > 1) || ($this->session->userdata('account_type') == 'biller' && $info['activity_typeid'] != 3 && $counter > 1)) :
            ?>
                <td>
                  <div class="checkbox" style="margin-top:4px">
                    <?php
                      echo form_open("",array("id"=>"canceled-order-form"));
                    ?>
                        <label class="i-checks data_tooltip" title="Cancel Item">
                          <input type="checkbox" <?php if($info['canceled_order'] == 1) echo 'checked' ?>
                                  name="canceled_status"
                                  class="cancel_item_checkbox checkbox_<?php echo $info['equipmentID'] ?>" <?php echo $disable_cancel ?>
                                  data-equipment-id="<?php echo $info['equipmentID'] ?>"
                                  data-id="<?php echo $info['medical_record_id'] ?>"
                                  data-fname="<?php echo $info['p_fname'] ?>"
                                  data-lname="<?php echo $info['p_lname'] ?>"
                                  data-order-id="<?php echo $info['orderID'] ?>"
                                  data-hospice="<?php echo $info['hospice_name'] ?>"
                                  data-patient-id="<?php echo $info['patientID'] ?>"
                                  data-order-unique-id="<?php echo $info['uniqueID'] ?>"
                                  data-cancel-sequence-order="<?php echo $sequence_count ?>"
                          />
                          <i></i>
                        </label>
                    <?php
                      echo form_close();
                    ?>
                  </div>
                </td>
                <input type="hidden" id="order_sequence_<?php echo $sequence_count; ?>" value="">
            <?php
              endif;
            ?>
          </tr>
    <?php
        }
      }
    }
  endif ;
?>
  </tbody>
</table>

<input type="hidden" id="" name="hdn_patient_id_submit" value="<?php echo $info['patientID'] ?>" />
<input type="hidden" name="is_initial" value="<?php echo $infos[0]['is_initial'] ?>" />
<input type="hidden" name="adding_weight_sign" id="adding_weight_sign" value="<?php echo $adding_weight_sign; ?>">
<input type="hidden" name="adding_weight_equipment" id="adding_weight_equipment" value="<?php echo $adding_weight_equipment; ?>">
<input type="hidden" name="commode_pail_count" id="commode_pail_count" value="<?php echo $commode_pail_count; ?>">

<div class="col-md-12" style="padding:0px;">
  <div class="pull-right" style="margin-left: 20px; width:17%; margin-right:6px;">
    <label style="margin-left:5px;">Delivery Date<span class="text-danger-dker">*</span></label>
    <input type="hidden" value="<?php echo $patient_first_order['actual_order_date']; ?>" id="initial_actual_order_date_value">
    <input type="hidden" value="<?php echo $summaries[0]['pickup_discharge_date']; ?>" id="pickup_discharge_date">
    <input type="hidden" value="<?php echo $summaries[0]['uniqueID']; ?>" id="billing_credit_uniqueID">
    <input type="hidden" value="<?php echo $pickup_tbl['pickup_sub']; ?>" id="billing_credit_pickupsub">
    <input type="text" class="form-control datepicker_2 input_actual_order_date" name="actual_order_date" placeholder="Click to choose date" style="margin-bottom:20px" value="">
  </div>
</div>

<div class="pull-right" style="margin-left: 20px;">
  <label>DME Staff Member Delivered Order<span class="text-danger-dker">*</span></label>
  <input type="text"  class="form-control driver_name_to_save" id="exampleInputEmail1" placeholder="Delivered by" name="" style="margin-bottom:20px" value="">
</div>

<div class="pull-right" style="  margin-top: 70px !important;margin-right: -225px !important;">
  <button type="button" class="btn btn-danger pull-right data_tooltip" onclick="closeModalbox()">Close</button>

  <input type="hidden" name="" class="current_act_type" value="<?php echo $act_id; ?>">
  <input type="hidden" name="" class="o2_concentrator_follow_up_sign" value="<?php echo $o2_concentrator_follow_up_sign; ?>" data-equipmentID="<?php echo $o2_concentrator_follow_up_equipmentID; ?>"  data-uniqueID="<?php echo $o2_concentrator_follow_up_uniqueID; ?>" data-uniqueID_old="<?php echo $o2_concentrator_follow_up_uniqueID_old; ?>">
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


<div class="col-sm-12 mt30" style="padding-left:0px;">
<div class="pull-left">
    <a href="<?php echo base_url("order/print_confirm_details/".$medical_record_num."/".$work_order_number."/".$act_id."/".$hospice_id) ?>" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> Print</a>
</div>
</div>

<div class="modal fade" id="billing_credit_modal" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="left: 0px !important; right: -400px !important;">
  <div class="modal-dialog" style="width: 600px !important;">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5); width: 100% !important">
    <!-- text-transform: uppercase; -->
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reminder</h4>
      </div>
      <form id="billing_credit_form">
        <div class="modal-body OpenSans-Reg equipments_modal">
          <div class="no_email_content">
            <input type="hidden" id="billing_credit_patientID" value="<?php echo $summaries[0]['patientID']; ?>" name="billing_credit_patientID">
            <input type="hidden" id="billing_credit_hospiceID" value="<?php echo $summaries[0]['ordered_by']; ?>" name="billing_credit_hospiceID">
            <div id="" style="text-align:center; margin: 30px">
              <div class="form-group" style="margin-right:0px;" id="">
                <label style="margin-left:5px;">
                  Notes
                  <!-- <span class="text-danger-dker">*</span> -->
                </label>
                <input type="text" class="form-control billing_credit_notes" value="" placeholder="Enter note..." name="billing_credit_notes" style="">
              </div>
            </div>
          </div>
            <div class="error_content"></div>
        </div>
        <div class="modal-footer" id="popup_panel">
            <button type="button" class="btn btn-success" id="popup_submit_billing_credit" style="color:#fff" autocomplete="off">
                <span class="glyphicon glyphicon-ok"></span> &nbsp;Add Note&nbsp;
            </button>
            <button type="button" class="btn btn-default" id="popup_cancel_billing_credit">
                <span class="glyphicon glyphicon-remove"></span> &nbsp;Cancel&nbsp;
            </button>
        </div>
      </form>
      <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
          <button type="button" class="btn btn-primary btn-order save_floor_mat_capped">Save changes</button>
      </div> -->
    </div>
  </div>
</div>