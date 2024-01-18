<style type="text/css">
  .modal
  {
    /*left: -689px;
    top: -71px;*/
  }
  #globalModal .modal-dialog
  {
    width:1300px;
    max-width: 90%;
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

<?php $medical_id = $info['medical_record_id'] ?>

<?php echo form_open("",array("class"=>"edit_patient_profile_form")) ?>
      <div class="row">
        <div class="">
          <div class="col-md-6" style="padding-left:30px;">

            <input type="hidden" name="hdn_patient_id" value="<?php echo $info['patientID'] ?>" />
            <input type="hidden" class="hdn_hospice_id" name="" value="<?php echo $info['hospiceID'] ?>" />
            <input type="hidden" name="current_hospiceID" value="<?php echo $info['hospiceID'] ?>" />
            <label>Customer Medical Record # 
              <span style="color:red;">*</span> 
              &nbsp;&nbsp; <span style="font-size: 12px; font-style: italic; color: #00000078;"> Restricted " !"#$%&'()*+,./:;<=>?@[\]^_`{|}~" </span>
            </label>

            <div class="clearfix"></div>

            <div class="form-group">
              <input type="text" class="form-control medical_record_num edit_patient_mr_number_field" data-id="edit_patient_mr_number_field" autocomplete="off"
              id="exampleInputEmail1"  placeholder="" name="medical_record_id" style="margin-bottom:10px" value="<?php echo $info['medical_record_id'] ?>" 
              onkeypress="return event.charCode == 8 ? null : ( event.charCode >= 48 && event.charCode <= 57 ) || event.charCode === 127 || event.charCode === 45 || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)" onpaste="return false">
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
                    <?php if(empty($info['ordered_by'])) :?>
                      <select class="form-control hospice_provider_select" style="margin-bottom:10px" name="organization_id">
                          <option value="">-- Choose Hospice Provider --</option>
                          <?php foreach($companies as $company) :?>
                              <option value="<?php echo $company['hospiceID'] ?>"><?php echo $company['hospice_name'] ?></option>
                          <?php endforeach;?>
                      </select>
                    <?php else :?>
                      <select class="form-control hospice_provider_select" style="margin-bottom:10px" name="organization_id">
                          <option value="<?php echo $info['ordered_by'] ?>">[-- <?php echo $info['hospice_name'] ?> --]</option>

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
                    <?php if(empty($info['ordered_by'])) :?>
                      <select class="form-control hospice_provider_select" style="margin-bottom:10px" name="organization_id">
                          <option value="">-- Choose Hospice Provider --</option>
                          <?php foreach($hospices as $hospice) :?>
                              <option value="<?php echo $hospice['hospiceID'] ?>"><?php echo $hospice['hospice_name'] ?></option>
                          <?php endforeach;?>
                      </select>
                    <?php else :?>
                      <select class="form-control hospice_provider_select" style="margin-bottom:10px" name="organization_id">
                          <option value="<?php echo $info['ordered_by'] ?>">[-- <?php echo $info['hospice_name'] ?> --]</option>

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
              <label>Customer First Name <span style="color:red;">*</span></label>
              <div class="form-group">
                <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="" name="p_fname" style="margin-bottom:10px" value="<?php echo $info['p_fname'] ?>">
              </div>
            </div>

            <div class="col-md-6" >
              <label>Customer Last Name <span style="color:red;">*</span></label>
              <div class="clearfix"></div>
              <div class="form-group">
                <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="" name="p_lname" style="margin-bottom:10px" value="<?php echo $info['p_lname'] ?>">
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="patient-address-fields" style="margin-left:-16px;">

              <div class="col-md-8">
                <div class="form-group">
                  <label>Customer Address <span class="text-danger-dker">*</span></label>
                  <input type="text" class="form-control" id="" placeholder="Enter Address" name="p_street" style="margin-bottom:10px;" value="<?php echo $info['p_street'] ?>">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Apartment # <span class="text-danger-dker">*</span></label>
                  <input type="text" class="form-control" id="" placeholder="Apartment #, Room #" name="p_placenum" style="margin-bottom:10px;" value="<?php echo $info['p_placenum'] ?>">
                </div>
              </div>

              <div class="clearfix"></div>

              <div class="col-md-4">
                <div class="form-group">
                  <input type="text" class="form-control" id="edit_city" placeholder="City" name="p_city" style="margin-bottom:20px" value="<?php echo $info['p_city'] ?>">
                </div>
              </div>

              <div class="col-md-4" >
                <div class="form-group">
                  <input type="text" class="form-control" id="edit_state" placeholder="State" name="p_state" style="margin-bottom:20px" value="<?php echo $info['p_state'] ?>">
                </div>
              </div>

              <div class="col-md-4" >
                <div class="form-group">
                  <input type="text" class="form-control" id="edit_postal" placeholder="Postal" name="p_postalcode" style="margin-bottom:20px" value="<?php echo $info['p_postalcode'] ?>">
                </div>
              </div>

            </div> <!-- .customer-address-fields-->
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
            <?php
              if($sign_noorder == 0)
              {
            ?>
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
                          <?php if(strtolower($patient_residence_array['ptmove_patient_residence']) == "home care") :?>
                            <option value="Assisted Living">Assisted Living</option>
                            <option value="Group Home">Group Home</option>
                            <option value="Hic Home">Hic Home</option>
                            <option selected value="Home Care">Home Care</option>
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php elseif(strtolower($patient_residence_array['ptmove_patient_residence']) == "group home"):?>
                            <option value="Assisted Living">Assisted Living</option>
                            <option selected value="Group Home">Group Home</option>
                            <option value="Hic Home">Hic Home</option>
                            <option value="Home Care">Home Care</option>
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php elseif(strtolower($patient_residence_array['ptmove_patient_residence']) == "assisted living"):?>
                            <option selected value="Assisted Living">Assisted Living</option>
                            <option value="Group Home">Group Home</option>
                            <option value="Hic Home">Hic Home</option>
                            <option value="Home Care">Home Care</option>
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php elseif(strtolower($patient_residence_array['ptmove_patient_residence']) == "skilled nursing facility"):?>
                            <option value="Assisted Living">Assisted Living</option>
                            <option value="Group Home">Group Home</option>
                            <option value="Hic Home">Hic Home</option>
                            <option value="Home Care">Home Care</option>
                            <option selected value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php elseif(strtolower($patient_residence_array['ptmove_patient_residence']) == "hic home"):?>
                            <option value="Assisted Living">Assisted Living</option>
                            <option value="Group Home">Group Home</option>
                            <option selected value="Hic Home">Hic Home</option>
                            <option value="Home Care">Home Care</option>
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php else:?>
                            <option selected value=""></option>
                            <option value="Assisted Living">Assisted Living</option>
                            <option value="Group Home">Group Home</option>
                            <option value="Hic Home">Hic Home</option>
                            <option value="Home Care">Home Care</option>
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php endif;?>

                        </select>
                  <?php
                      }else{
                  ?>
                        <select class="form-control" name="deliver_to_type" style="margin-bottom:10px">
                          <?php if(strtolower($info['deliver_to_type']) == "home care") :?>
                            <option value="Assisted Living">Assisted Living</option>
                            <option value="Group Home">Group Home</option>
                            <option value="Hic Home">Hic Home</option>
                            <option selected value="Home Care">Home Care</option>
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php elseif(strtolower($info['deliver_to_type']) == "group home"):?>
                            <option value="Assisted Living">Assisted Living</option>
                            <option selected value="Group Home">Group Home</option>
                            <option value="Hic Home">Hic Home</option>
                            <option value="Home Care">Home Care</option>
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php elseif(strtolower($info['deliver_to_type']) == "assisted living"):?>
                            <option selected value="Assisted Living">Assisted Living</option>
                            <option value="Group Home">Group Home</option>
                            <option value="Hic Home">Hic Home</option>
                            <option value="Home Care">Home Care</option>
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php elseif(strtolower($info['deliver_to_type']) == "skilled nursing facility"):?>
                            <option value="Assisted Living">Assisted Living</option>
                            <option value="Group Home">Group Home</option>
                            <option value="Hic Home">Hic Home</option>
                            <option value="Home Care">Home Care</option>
                            <option selected value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php elseif(strtolower($info['deliver_to_type']) == "hic home"):?>
                            <option value="Assisted Living">Assisted Living</option>
                            <option value="Group Home">Group Home</option>
                            <option selected value="Hic Home">Hic Home</option>
                            <option value="Home Care">Home Care</option>
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php else :?>
                            <option selected value=""></option>
                            <option value="Assisted Living">Assisted Living</option>
                            <option value="Group Home">Group Home</option>
                            <option value="Hic Home">Hic Home</option>
                            <option value="Home Care">Home Care</option>
                            <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                          <?php endif;?>

                        </select>
                  <?php
                      }
                    }
                  ?>
                </div>
            <?php
              }else{
            ?>
                <div class="col-md-6">
                </div>
            <?php } ?>

            <!-- <input type="text" class="form-control " id="" placeholder="Residence" name="deliver_to_type" style="margin-bottom:10px" value="<?php echo $info['deliver_to_type'] ?>"> -->

            <?php
              $male_checked = "";
              $female_checked = "";

              if($info['relationship_gender'] == 1)
              {
                $male_checked = "checked";
              }
              else
              {
                $female_checked = "checked";
              }
            ?>

            <div class="col-md-6">
              <label>Gender<span class="text-danger-dker">*</span></label>
                <div class="radio" tabindex="13">
                  <label class="i-checks">
                    <input type="radio" style="" class="" id="" name="relationship_gender" value="1" <?php echo $male_checked ?> /><i></i> Male &nbsp &nbsp &nbsp
                  </label>
                   <label class="i-checks">
                    <input type="radio" style="" class="" id="" name="relationship_gender" value="2" <?php echo $female_checked ?> /><i></i> Female
                  </label>
                </div>
            </div>

            <div style="margin-right:15px;">
              <button type="button" class="btn btn-primary pull-right save_edit_changes" data-sign-noorder="<?php echo $sign_noorder; ?>" data-id="<?php echo $info['medical_record_id'] ?>" name="" style="margin-bottom:10px;<?php if($sign_noorder == 1){ echo "margin-top: 30px !important;"; }?>">Save Changes</button>
            </div>
          </div>

        </div>
      </div>
<?php
    endforeach;
  endif;
  echo form_close();

  if($sign_noorder == 0)
  {
?>
  <div class="panel panel-default">
    <div class="panel-heading font-bold">
      <h4>Customer Order Summary</h4>
    </div>
    <div class="panel-body">

      <?php echo form_open("",array("class"=>"update_order_summary")) ;?>
      <div class="table-responsive mb15">
        <table class="table table-striped bg-white b-a col-md-12 edit_patient_orders"  style="margin-top:0px;margin-left: 0px;">
          <thead>
            <tr>
              <th style="width: 40px">WO#</th>
              <th style="width: 60px">Delivery Date</th>
              <th style="width: 90px">Activity Type</th>
              <th style="width: 60px">Item #</th>
              <th style="width: 90px">Item Description</th>
              <th style="width: 60px">Qty.</th>
              <th style="width: 90px">Serial #</th>
              <th style="width: 90px">Picked Up Date</th>
              <th style="width: 90px">Capped Type</th>
            </tr>
          </thead>

          <tbody>
            <?php
              $index = 0;
            ?>

            <?php
              if(!empty($summaries)) :
            ?>
              <input type="hidden" name="count_looped_data" value="<?php echo count($summaries); ?>" />
                <?php
                $packaged_items_ids_list = [486,163,164,68,159,160,161,162,316,325,334,343,466,36,178,422,259,415,174,490,492,67,157];
                $packaged_item_sign = 0;
                $packaged_items_list = array();

                foreach($summaries as $info) :
                  if($info['parentID'] == 0) :
                    if(!in_array($info['equipmentID'], $packaged_items_ids_list))
                    {
                ?>
                      <tr style="<?php if($info['canceled_order'] == 1) echo 'text-decoration:line-through' ?> <?php if($info['parentID'] != 0) echo 'visibility:hidden;position: fixed;top: 1px;left: 1px;' ?> ">

                        <!--WO#-->
                        <td>
                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][uniqueID]" value="<?php echo $info['uniqueID'] ?>" class="hdn_unique_id" />
                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][key_desc]" value="<?php echo $info['key_desc'] ?>" />
                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][activity_typeid]" value="<?php echo $info['activity_typeid'] ?>" />
                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_respite_order]" value="<?php echo $info['pickedup_respite_order'] ?>" />
                          <a href="javascript:void(0)"><?php echo substr($info['uniqueID'],4,10) ?></a>
                        </td>

                        <!--DELIVERY DATE-->
                        <td style="width:105px">
                          <?php
                            if($info['order_status'] != "confirmed")
                            {
                          ?>
                              <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][order_date]" class="datepicker form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo $info['pickup_date'] ?>" />
                          <?php
                            }
                            else
                            {
                          ?>
                              <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][order_date]" class="datepicker form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo $info['actual_order_date'] ?>" />
                          <?php
                            }
                          ?>
                        </td>

                        <!--ACTIVITY TYPE-->
                        <td>
                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][act_name]" value="<?php echo $info['activity_name'] ?>" />
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
                                $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row)
                                {
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
                                foreach($respite_addresses_ID as $key => $addresses_ID_row)
                                {
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
                              foreach($ptmove_addresses_ID as $key => $addresses_ID_row)
                              {
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
                              foreach($respite_addresses_ID as $key => $addresses_ID_row)
                              {
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
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row)
                                {
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
                            echo $activity_type_display;
                          ?>
                        </td>

                        <!--ITEM NO-->
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

                        <!--ITEM DESCRIPTION-->
                        <td style="width:auto;text-transform:uppercase !important;">
                          <?php
                            //equipment is oxygen concentrator
                            if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343)
                            {
                          ?>
                              <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
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
                                $patient_lift_sling_count = 0;
                                $high_low_full_electric_hospital_bed_count = 0;
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

                                    //FULL ELECTRIC HOSPITAL BED EQUIPMENT
                                    if($info['equipmentID'] == 55 || $info['equipmentID'] == 20){
                            ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc']." With ".$key['key_desc']; ?></a>
                            <?php
                                    }
                                    //hi-low full electric hospital bed
                                    else if($info['equipmentID'] == 19 || $info['equipmentID'] == 398)
                                    {
                            ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc']." With ".$key['key_desc']; ?></a>
                            <?php
                                    }
                                    //Patient Lift with Sling
                                    else if($info['equipmentID'] == 56 || $info['equipmentID'] == 21)
                                    {
                            ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo "Patient Lift With ".$key['key_desc']; ?></a>
                            <?php
                                    }
                                    //Patient Lift Electric with Sling
                                    else if($info['equipmentID'] == 353)
                                    {
                            ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo "Patient Lift Electric With ".$key['key_desc']; ?></a>
                            <?php
                                    }
                                    //Patient Lift Sling
                                    else if($info['equipmentID'] == 196)
                                    {
                            ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $key['key_desc']; ?></a>
                            <?php
                                    }
                                    // Oxygen E Portable System && Oxygen Liquid Portable
                                    else if($info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179)
                                    {
                            ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                      break;
                                    }
                                    else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17 || $info['equipmentID'] == 66 || $info['equipmentID'] == 39)
                                    {
                            ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc']." ".$key['key_desc']; ?></a>
                            <?php
                                    }
                                    //Oxygen Cylinder Rack
                                    else if($info['equipmentID'] == 32 || $info['equipmentID'] == 393)
                                    {
                                ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" >Oxygen <?php echo $key['key_desc']; ?></a>
                                <?php
                                      break;
                                    }
                                    else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71 || $info['equipmentID'] == 269 || $info['equipmentID'] == 64)
                                    {
                                      if($my_count_sign == 0)
                                      {
                                        //wheelchair & wheelchair reclining
                                        if($count == 1)
                                        {
                            ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" >
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
                                        <a href="javascript:void(0)" data-toggle="popover" class="" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" > 20" Wide
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
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?>
                            <?php
                                          echo " With ".$key['key_desc']." </a>";
                                        }
                                      }
                                      else
                                      {
                            ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?>
                            <?php
                                      }
                                    }
                                    else if($info['equipmentID'] == 306 || $info['equipmentID'] == 309 || $info['equipmentID'] == 313 || $info['equipmentID'] == 64 || $info['equipmentID'] == 40 || $info['equipmentID'] == 32  || $info['equipmentID'] == 393 || $info['equipmentID'] == 16 || $info['equipmentID'] == 67 || $info['equipmentID'] == 66 || $info['equipmentID'] == 4 || $info['equipmentID'] == 36 )
                                    {
                            ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                                        <br />
                                        <?php
                                          $samp =  get_misc_item_description($info['equipmentID'],$info['uniqueID']);
                                          $exploded_description = explode(" ", $samp);
                                          $final_description = $exploded_description[0]." ".$exploded_description[1]." ".$exploded_description[2];
                                          echo "<span style='font-weight:400;color:#696666;'>".$final_description."</span>";
                                        ?>
                            <?php
                                      break;
                                    }
                                    else if($info['equipmentID'] == 62 || $info['equipmentID'] == 31)
                                    {
                                      $samp_conserving_device =  get_oxygen_conserving_device($info['equipmentID'],$info['uniqueID']);
                                      if($count == 1)
                                      {
                            ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?> <?php echo $samp_conserving_device; ?></a>
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
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                          break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 14)
                                        {
                            ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 282)
                                        {
                                          $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($info['equipmentID'],$info['uniqueID']);
                            ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?></a>
                            <?php
                                          break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                                        {
                            ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a>
                            <?php
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 353)
                                        {
                            ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a>
                            <?php
                                        }
                                        else
                                        {
                            ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                        }
                                      }
                                      else
                                      {
                            ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                      }
                                    }
                                  } //end of value
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
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?> With <?php echo $samp_hospital_bed_extra_long;?></a>
                            <?php
                                    break;
                                  }
                                  else if($info['equipmentID'] == 11 || $info['equipmentID'] == 178 || $info['equipmentID'] == 4 || $info['equipmentID'] == 149)
                                  {
                            ?>
                                    <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                  }
                                  else if($info['equipmentID'] == 10 || $info['equipmentID'] == 36 || $info['equipmentID'] == 31 || $info['equipmentID'] == 32  || $info['equipmentID'] == 393 || $info['equipmentID'] == 286 || $info['equipmentID'] == 62 || $info['equipmentID'] == 313 || $info['equipmentID'] == 309 || $info['equipmentID'] == 306)
                                  {
                            ?>
                                   <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                    break;
                                  }
                                  //for equipments with subequipment but does not fall in $value
                                  else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17 || $info['equipmentID'] == 398 || $info['equipmentID'] == 196 || $info['equipmentID'] == 353 || $info['equipmentID'] == 56 || $info['equipmentID'] == 21 || $info['equipmentID'] == 39 || $info['equipmentID'] == 30 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179 || $info['equipmentID'] == 66 || $info['equipmentID'] == 19 || $info['equipmentID'] == 269 || $info['equipmentID'] == 64 || $info['equipmentID'] == 49 || $info['equipmentID'] == 20 || $info['equipmentID'] == 55 || $info['equipmentID'] ==71 || $info['equipmentID'] == 69 || $info['equipmentID'] == 48)
                                  {
                                    if($info['equipmentID'] == 196 || $info['equipmentID'] == 56 || $info['equipmentID'] == 21 || $info['equipmentID'] == 353)
                                    {
                                      $patient_lift_sling_count++;
                                      if($patient_lift_sling_count == 6)
                                      {
                            ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                      }
                                    }
                                    else if($info['equipmentID'] == 398)
                                    {
                                      if(date("Y-m-d", $info['uniqueID']) <= "2016-06-21")
                                      {
                                        $high_low_full_electric_hospital_bed_count++;
                                        if($high_low_full_electric_hospital_bed_count == 2){
                                ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                                <?php
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
                                        <a href="javascript:void(0)" class="show_on_print" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id'] ?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
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
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                        break;
                                      }
                                      else if($non_capped_copy['noncapped_reference'] == 14)
                                      {
                            ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                      }
                                      else
                                      {

                                      }
                                    }
                                    else
                                    {
                            ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                    }
                                  }
                                } //end of foreach
                              } //end of subequipment
                              else
                              {
                          ?>
                                <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                          <?php
                              }
                            }
                          ?>
                        </td>

                        <!--QTY-->
                        <td style="width:75px">
                          <?php
                            if($info['categoryID'] != 3)
                            {
                              if($info['categoryID'] == 2)
                              {
                                if($info['equipment_value'] > 1 || $info['equipmentID'] == 176)
                                {
                          ?>
                                  <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php
                                }
                                else
                                {
                                  if(get_noncapped_quantity($info['equipmentID'],$info['uniqueID'])==0)
                                  {
                          ?>
                                    <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php
                                  }
                                  else
                                  {
                                    if($info['equipmentID'] == 4 || $info['equipmentID'] == 9)
                                    {
                          ?>
                                      <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php
                                    }
                                    else
                                    {
                          ?>
                                      <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php
                                    }
                                  }
                                }
                              }
                              else
                              {
                                $non_capped_copy = get_non_capped_copy($info['equipmentID']);
                                if($info['equipmentID'] == 313 || $info['equipmentID'] == 206)
                                {
                          ?>
                                  <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php
                                }
                                else if($non_capped_copy['noncapped_reference'] == 14)
                                {
                          ?>
                                  <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php
                                }
                                else
                                {
                          ?>
                                  <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php   }
                              }
                            }
                            else
                            {
                              if($info['equipment_value'] > 1 || $info['equipment_value'] != "")
                              {
                                if($info['equipmentID'] == 306)
                                {
                          ?>
                                   <input type="text" value="<?php echo get_misc_quantity($info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                   <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php
                                }
                                else
                                {
                                  if(get_disposable_quantity($info['equipmentID'],$info['uniqueID']) == 0)
                                  {
                          ?>
                                    <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php
                                  }
                                  else
                                  {
                          ?>
                                    <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php
                                  }
                                }
                              }
                              else
                              {
                                if($info['equipmentID'] == 7)
                                {
                          ?>
                                  <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php
                                }
                                else
                                {
                          ?>
                                  <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                          <?php
                                }
                              }
                            }
                          ?>
                        </td>

                        <!--SERIAL NO-->
                        <td>
                          <?php
                            if($info['parentID'] == 0)
                            {
                          ?>
                              <input type="text" value="<?php echo $info['serial_num'] ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num form-control" required />
                          <?php
                            }
                            else
                            {
                          ?>
                              <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num form-control" required />
                          <?php
                            }
                          ?>
                        </td>

                        <!-- PICKUP DATE-->
                        <td>
                          <?php
                            if($info['summary_pickup_date'] != '0000-00-00')
                            {
                          ?>
                              <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker form-control" required />
                          <?php
                            }
                            else
                            {
                          ?>
                              <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker form-control" required />
                          <?php
                            }
                          ?>
                        </td>

                        <!-- ITEM CATEGORY -->
                        <td>
                          <?php
                            if($info['type'] == 'Capped Item')
                            {
                          ?>
                              <p class="label label-info"><?php echo $info['type'] ?></p>
                          <?php
                            }
                            else if($info['type'] == 'Non-Capped Item')
                            {
                          ?>
                              <p class="label label-warning"><?php echo $info['type'] ?></p>
                          <?php
                            }
                            else
                            {
                          ?>
                              <p class="label label-success"><?php echo $info['type'] ?></p>
                          <?php
                            }
                          ?>
                        </td>

                      </tr>
            <?php
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
                  endif;
                  $index++;
                endforeach;
                if(!empty($packaged_items_list))
                {
                  foreach($packaged_items_list as $new_item_list)
                  {
                    foreach ($new_item_list as  $info)
                    {
                      if($info['canceled_order'] == 0 && $info['canceled_from_confirming'] == 0)
                      {
                ?>
                        <tr style="<?php if($info['canceled_order'] == 1) echo 'text-decoration:line-through' ?> <?php if($info['parentID'] != 0) echo 'visibility:hidden;position: fixed;top: 1px;left: 1px;' ?> ">

                          <!--WO#-->
                          <td>
                            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][uniqueID]" value="<?php echo $info['uniqueID'] ?>" class="hdn_unique_id" />
                            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][key_desc]" value="<?php echo $info['key_desc'] ?>" />
                            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][activity_typeid]" value="<?php echo $info['activity_typeid'] ?>" />
                            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_respite_order]" value="<?php echo $info['pickedup_respite_order'] ?>" />
                            <a href="javascript:void(0)"><?php echo substr($info['uniqueID'],4,10) ?></a>
                          </td>

                          <!--DELIVERY DATE-->
                          <td style="width:105px">
                            <?php
                              if($info['order_status'] != "confirmed")
                              {
                            ?>
                                <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][order_date]" class="datepicker form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo $info['pickup_date'] ?>" />
                            <?php
                              }
                              else
                              {
                            ?>
                                <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][order_date]" class="datepicker form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo $info['actual_order_date'] ?>" />
                            <?php
                              }
                            ?>
                          </td>

                          <!--ACTIVITY TYPE-->
                          <td>
                            <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][act_name]" value="<?php echo $info['activity_name'] ?>" />
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
                                  $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($info['patientID']);
                                  foreach($ptmove_addresses_ID as $key => $addresses_ID_row)
                                  {
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
                                  foreach($respite_addresses_ID as $key => $addresses_ID_row)
                                  {
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
                                foreach($ptmove_addresses_ID as $key => $addresses_ID_row)
                                {
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
                                foreach($respite_addresses_ID as $key => $addresses_ID_row)
                                {
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
                                  foreach($ptmove_addresses_ID as $key => $addresses_ID_row)
                                  {
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
                              echo $activity_type_display;
                            ?>
                          </td>

                          <!--ITEM NO-->
                          <td>
                            <?php
                              $company_item_no = "";
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

                          <!--ITEM DESCRIPTION-->
                          <td style="width:auto;text-transform:uppercase !important;">
                            <?php
                              //equipment is oxygen concentrator
                              if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343)
                              {
                            ?>
                                <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
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
                                  $patient_lift_sling_count = 0;
                                  $high_low_full_electric_hospital_bed_count = 0;
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

                                      //FULL ELECTRIC HOSPITAL BED EQUIPMENT
                                      if($info['equipmentID'] == 55 || $info['equipmentID'] == 20){
                              ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc']." With ".$key['key_desc']; ?></a>
                              <?php
                                      }
                                      //hi-low full electric hospital bed
                                      else if($info['equipmentID'] == 19 || $info['equipmentID'] == 398)
                                      {
                              ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc']." With ".$key['key_desc']; ?></a>
                              <?php
                                      }
                                      //Patient Lift with Sling
                                      else if($info['equipmentID'] == 56 || $info['equipmentID'] == 21)
                                      {
                              ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo "Patient Lift With ".$key['key_desc']; ?></a>
                              <?php
                                      }
                                      //Patient Lift Electric with Sling
                                      else if($info['equipmentID'] == 353)
                                      {
                              ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo "Patient Lift Electric With ".$key['key_desc']; ?></a>
                              <?php
                                      }
                                      //Patient Lift Sling
                                      else if($info['equipmentID'] == 196)
                                      {
                              ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $key['key_desc']; ?></a>
                              <?php
                                      }
                                      // Oxygen E Portable System && Oxygen Liquid Portable
                                      else if($info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179)
                                      {
                              ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                              <?php
                                        break;
                                      }
                                      else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17 || $info['equipmentID'] == 66 || $info['equipmentID'] == 39)
                                      {
                              ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc']." ".$key['key_desc']; ?></a>
                              <?php
                                      }
                                      //Oxygen Cylinder Rack
                                      else if($info['equipmentID'] == 32 || $info['equipmentID'] == 393)
                                      {
                                  ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" >Oxygen <?php echo $key['key_desc']; ?></a>
                                  <?php
                                        break;
                                      }
                                      else if($info['equipmentID'] == 49 || $info['equipmentID'] == 71 || $info['equipmentID'] == 269 || $info['equipmentID'] == 64)
                                      {
                                        if($my_count_sign == 0)
                                        {
                                          //wheelchair & wheelchair reclining
                                          if($count == 1)
                                          {
                              ?>
                                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" >
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
                                          <a href="javascript:void(0)" data-toggle="popover" class="" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" > 20" Wide
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
                                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?>
                              <?php
                                            echo " With ".$key['key_desc']." </a>";
                                          }
                                        }
                                        else
                                        {
                              ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?>
                              <?php
                                        }
                                      }
                                      else if($info['equipmentID'] == 306 || $info['equipmentID'] == 309 || $info['equipmentID'] == 313 || $info['equipmentID'] == 64 || $info['equipmentID'] == 40 || $info['equipmentID'] == 32  || $info['equipmentID'] == 393 || $info['equipmentID'] == 16 || $info['equipmentID'] == 67 || $info['equipmentID'] == 66 || $info['equipmentID'] == 4 || $info['equipmentID'] == 36 )
                                      {
                              ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                                          <br />
                                          <?php
                                            $samp =  get_misc_item_description($info['equipmentID'],$info['uniqueID']);
                                            $exploded_description = explode(" ", $samp);
                                            $final_description = $exploded_description[0]." ".$exploded_description[1]." ".$exploded_description[2];
                                            echo "<span style='font-weight:400;color:#696666;'>".$final_description."</span>";
                                          ?>
                              <?php
                                        break;
                                      }
                                      else if($info['equipmentID'] == 62 || $info['equipmentID'] == 31)
                                      {
                                        $samp_conserving_device =  get_oxygen_conserving_device($info['equipmentID'],$info['uniqueID']);
                                        if($count == 1)
                                        {
                              ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?> <?php echo $samp_conserving_device; ?></a>
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
                                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                              <?php
                                            break;
                                          }
                                          else if($non_capped_copy['noncapped_reference'] == 14)
                                          {
                              ?>
                                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                              <?php
                                          }
                                          else if($non_capped_copy['noncapped_reference'] == 282)
                                          {
                                            $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($info['equipmentID'],$info['uniqueID']);
                              ?>
                                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?></a>
                              <?php
                                            break;
                                          }
                                          else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                                          {
                              ?>
                                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a>
                              <?php
                                          }
                                          else if($non_capped_copy['noncapped_reference'] == 353)
                                          {
                              ?>
                                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a>
                              <?php
                                          }
                                          else
                                          {
                              ?>
                                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                              <?php
                                          }
                                        }
                                        else
                                        {
                              ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                              <?php
                                        }
                                      }
                                    } //end of value
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
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?> With <?php echo $samp_hospital_bed_extra_long;?></a>
                              <?php
                                      break;
                                    }
                                    else if($info['equipmentID'] == 11 || $info['equipmentID'] == 178 || $info['equipmentID'] == 4 || $info['equipmentID'] == 149)
                                    {
                              ?>
                                      <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                              <?php
                                    }
                                    else if($info['equipmentID'] == 10 || $info['equipmentID'] == 36 || $info['equipmentID'] == 31 || $info['equipmentID'] == 32  || $info['equipmentID'] == 393 || $info['equipmentID'] == 286 || $info['equipmentID'] == 62 || $info['equipmentID'] == 313 || $info['equipmentID'] == 309 || $info['equipmentID'] == 306)
                                    {
                              ?>
                                     <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                              <?php
                                      break;
                                    }
                                    //for equipments with subequipment but does not fall in $value
                                    else if($info['equipmentID'] == 54 || $info['equipmentID'] == 17 || $info['equipmentID'] == 398 || $info['equipmentID'] == 196 || $info['equipmentID'] == 353 || $info['equipmentID'] == 56 || $info['equipmentID'] == 21 || $info['equipmentID'] == 39 || $info['equipmentID'] == 30 || $info['equipmentID'] == 174 || $info['equipmentID'] == 176 || $info['equipmentID'] == 179 || $info['equipmentID'] == 66 || $info['equipmentID'] == 19 || $info['equipmentID'] == 269 || $info['equipmentID'] == 64 || $info['equipmentID'] == 49 || $info['equipmentID'] == 20 || $info['equipmentID'] == 55 || $info['equipmentID'] ==71 || $info['equipmentID'] == 69 || $info['equipmentID'] == 48)
                                    {
                                      if($info['equipmentID'] == 196 || $info['equipmentID'] == 56 || $info['equipmentID'] == 21 || $info['equipmentID'] == 353)
                                      {
                                        $patient_lift_sling_count++;
                                        if($patient_lift_sling_count == 6)
                                        {
                              ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                              <?php
                                        }
                                      }
                                      else if($info['equipmentID'] == 398)
                                      {
                                        if(date("Y-m-d", $info['uniqueID']) <= "2016-06-21")
                                        {
                                          $high_low_full_electric_hospital_bed_count++;
                                          if($high_low_full_electric_hospital_bed_count == 2){
                                  ?>
                                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                                  <?php
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
                                          <a href="javascript:void(0)" class="show_on_print" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id'] ?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>
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
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                              <?php
                                          break;
                                        }
                                        else if($non_capped_copy['noncapped_reference'] == 14)
                                        {
                              ?>
                                          <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                              <?php
                                        }
                                        else
                                        {

                                        }
                                      }
                                      else
                                      {
                              ?>
                                        <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                              <?php
                                      }
                                    }
                                  } //end of foreach
                                } //end of subequipment
                                else
                                {
                            ?>
                                  <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                            <?php
                                }
                              }
                            ?>
                          </td>

                          <!--QTY-->
                          <td style="width:75px">
                            <?php
                              if($info['categoryID'] != 3)
                              {
                                if($info['categoryID'] == 2)
                                {
                                  if($info['equipment_value'] > 1 || $info['equipmentID'] == 176)
                                  {
                            ?>
                                    <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php
                                  }
                                  else
                                  {
                                    if(get_noncapped_quantity($info['equipmentID'],$info['uniqueID'])==0)
                                    {
                            ?>
                                      <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php
                                    }
                                    else
                                    {
                                      if($info['equipmentID'] == 4 || $info['equipmentID'] == 9)
                                      {
                            ?>
                                        <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                        <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php
                                      }
                                      else
                                      {
                            ?>
                                        <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                        <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php
                                      }
                                    }
                                  }
                                }
                                else
                                {
                                  $non_capped_copy = get_non_capped_copy($info['equipmentID']);
                                  if($info['equipmentID'] == 313 || $info['equipmentID'] == 206)
                                  {
                            ?>
                                    <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php
                                  }
                                  else if($non_capped_copy['noncapped_reference'] == 14)
                                  {
                            ?>
                                    <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php
                                  }
                                  else
                                  {
                            ?>
                                    <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php   }
                                }
                              }
                              else
                              {
                                if($info['equipment_value'] > 1 || $info['equipment_value'] != "")
                                {
                                  if($info['equipmentID'] == 306)
                                  {
                            ?>
                                     <input type="text" value="<?php echo get_misc_quantity($info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                     <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php
                                  }
                                  else
                                  {
                                    if(get_disposable_quantity($info['equipmentID'],$info['uniqueID']) == 0)
                                    {
                            ?>
                                      <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php
                                    }
                                    else
                                    {
                            ?>
                                      <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php
                                    }
                                  }
                                }
                                else
                                {
                                  if($info['equipmentID'] == 7)
                                  {
                            ?>
                                    <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php
                                  }
                                  else
                                  {
                            ?>
                                    <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                            <?php
                                  }
                                }
                              }
                            ?>
                          </td>

                          <!--SERIAL NO-->
                          <td>
                            <?php
                              if($info['parentID'] == 0)
                              {
                            ?>
                                <input type="text" value="<?php echo $info['serial_num'] ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num form-control" required />
                            <?php
                              }
                              else
                              {
                            ?>
                                <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num form-control" required />
                            <?php
                              }
                            ?>
                          </td>

                          <!-- PICKUP DATE-->
                          <td>
                            <?php
                              if($info['summary_pickup_date'] != '0000-00-00')
                              {
                            ?>
                                <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker form-control" required />
                            <?php
                              }
                              else
                              {
                            ?>
                                <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker form-control" required />
                            <?php
                              }
                            ?>
                          </td>

                          <!-- ITEM CATEGORY -->
                          <td>
                            <?php
                              if($info['type'] == 'Capped Item')
                              {
                            ?>
                                <p class="label label-info"><?php echo $info['type'] ?></p>
                            <?php
                              }
                              else if($info['type'] == 'Non-Capped Item')
                              {
                            ?>
                                <p class="label label-warning"><?php echo $info['type'] ?></p>
                            <?php
                              }
                              else
                              {
                            ?>
                                <p class="label label-success"><?php echo $info['type'] ?></p>
                            <?php
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
            ?>
          </tbody>
        </table>
      </div>
      <button type="button" class="btn btn-danger pull-right" onclick="closeModalbox()">Close</button>
      <button type="button" class="btn btn-success pull-right btn-save-order-fields" data-medical-id="<?php echo $medical_id ?>"  style="margin-right:10px">Save Changes</button>&nbsp
      <?php echo form_close() ;?>
    </div>
  </div>

<?php } ?>
<script type="text/javascript">

$(document).ready(function(){

  var delay = (function(){
    var timer = 0;
    return function(callback, ms){
      clearTimeout (timer);
      timer = setTimeout(callback, ms);
    };
  })();

  /** Auto detect of the patient that is to be input exists in our database already **/
  var check_if_patient_exists_new = function()
  {
    $('.edit_patient_mr_number_field').bind('keyup', function(e){
      var _this = $(this);
      var this_val = $(this).val();
      var hdn_hospice_id = $('.hdn_hospice_id').val(); //newly added. To identify which hospice na belong ang i-create nga patient.

      if(this_val === "")
      {
        $('.edit_patient_mr_number_field').popover("hide");
        _this.attr("data-content","");
      }

      if(this_val.length > 1)
      {
        if(this_val !== "")
        {
          var value = this_val.toUpperCase();
          var current_mr_no = $("body").find(".save_edit_changes").attr('data-id');
          delay(function(){
            $.ajax({
              type:"POST",
              url:base_url+"main/check_existing_patient_new/"+value+"/"+hdn_hospice_id+"/"+current_mr_no,
              success:function(response)
              {
                _this.attr("data-content",response);
                if(response!="")
                {
                  $('.edit_patient_mr_number_field').popover("show");
                  $("body").find(".grey_inner_shadow").each(function(){
                    if($(this).attr("data-id") != "edit_patient_mr_number_field")
                    {
                      $(this).attr('disabled', true);
                    }
                  });
                }
                else
                {
                  $('.edit_patient_mr_number_field').popover("hide");
                  $("body").find(".grey_inner_shadow").each(function(){
                    $(this).removeAttr('disabled');
                  });
                }
              },
              error:function(jqXHR, textStatus, errorThrown)
              {
                console.log(textStatus, errorThrown);
              }

            });
          }, 500 );
        }
      }
    });
  };

  var showPopover_new = function(){
    $(this).popover('show');
  }
  , hidePopover_new = function(){
      $(this).popover('hide');
  };


  $('.edit_patient_mr_number_field').popover({
      trigger:"manual",
      html: true,
      placement:"top",
      content: function()
      {
        check_if_patient_exists_new();
      }
  })
  .focus(showPopover_new)
  .blur(hidePopover_new);

});

</script>
