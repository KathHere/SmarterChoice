<?php
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

            <input type="hidden"  class="form-control " id="exampleInputEmail1"  placeholder="" name="update_patient_id" style="margin-bottom:10px" value="<?php echo $info['patientID'] ?>" />
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
              if($info['type'] == 1)
              {
            ?>
                <div class="" style="padding-right:14px;">
                  <?php
                      $companies = get_companies();
                  ?>
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
                  <?php
                    $hospices = get_hospices();
                  ?>
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
                    $ptmove_final     = $ptmove[0];
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
                      $equipment_phone_number = $cequipment_phone_number;
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
                if(!empty($equipment_phone_number))
                {
              ?>
                  <input type="text" class="form-control person_num" id="" placeholder="Phone Number" name="phonenum" style="margin-bottom:10px" value="<?php echo $equipment_phone_number['ptmove_patient_phone']; ?>">
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
                if(!empty($equipment_phone_number))
                {
              ?>
                  <input type="text" class="form-control person_num" id="" placeholder="Alt. Phone Number" name="altphonenum" style="margin-bottom:10px" value="<?php echo $equipment_phone_number['ptmove_alt_patient_phone'] ?>">
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
                  if(!empty($ptmove_residence)){
                    if($act_id == 4)
                    {
                      $patient_residence_array = $ptmove_residence;
                    }
                    else
                    {
                      if($check_if_ptmove_confirmed['order_status'] == "confirmed"){
                        $patient_residence_array = $ptmove_residence;
                      }else{
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

             <!--  <?php if(!empty($ptmove_residence)) :?>
                <input type="text" class="form-control " id="" placeholder="Residence" name="deliver_to_type" style="margin-bottom:10px" value="<?php echo $ptmove_residence['ptmove_patient_residence'] ?>">
              <?php else:?>
                <input type="text" class="form-control " id="" placeholder="Residence" name="deliver_to_type" style="margin-bottom:10px" value="<?php echo $info['deliver_to_type'] ?>">
              <?php endif;?> -->
            </div>

            <?php
              if($act_id == 4) :
                $pt_id = $info['patientID'];
                $ptmove_info = $ptmove_information[0];
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
  echo form_close()
?>
