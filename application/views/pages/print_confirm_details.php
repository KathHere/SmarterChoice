<?php if(!empty($infos)):?>

<?php foreach($infos as $info) :?>



<?php $medical_id = $info['medical_record_id'] ?>

<?php echo form_open("",array("class"=>"edit_patient_profile_form")) ?>



<div class="bg-light lter b-b wrapper-md">

  <h3 class="m-n font-thin h3">Order Details for WO# <?php echo substr($work_order_number,4,10) ?></h3>

  <h3 class="m-n font-thin h3">Patient MR# <?php echo $medical_record_num  ?></h3>

</div>





<div class="wrapper-md">

  <div class="panel panel-default">

    <div class="panel-body">

      <div class="col-sm-12">

          

        <div class="col-sm-6">

            <label>Patient Medical Record #</label>

            <div class="clearfix"></div>

              <div class="form-group">

                <input type="text"  class="form-control medical_record_num" id="exampleInputEmail1" placeholder="" name="medical_record_id" style="margin-bottom:10px" value="<?php echo $info['medical_record_id'] ?>">

              </div>



           

            <label>Hospice Provider</label>

            <div class="clearfix"></div>  

              <?php $hospices = get_hospices() ;?> 

                <div class="form-group">

                  <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="" name="p_lname" style="margin-bottom:10px" value="<?php echo $info['hospice_name'] ?>">

                </div>



          <div class="col-sm-6" style="padding-left:0px;padding-right:0px">

            <label>Patient Last Name</label>

            <div class="clearfix"></div>

            <div class="form-group">

                <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="" name="p_lname" style="margin-bottom:10px" value="<?php echo $info['p_lname'] ?>">

            </div>

          </div>

          <div class="col-sm-6" style="padding-right:0px">

            <label>Patient First Name</label>

            <div class="clearfix"></div>

            <div class="form-group">

                <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="" name="p_fname" style="margin-bottom:10px" value="<?php echo $info['p_fname'] ?>">

            </div>

          </div>





          <div class="clearfix"></div>

            <div class="patient-address-fields" style="margin-left:-16px;">

                <div class="col-md-8" style="">

                    <div class="form-group">

                      <label>Patient Address </label>

                       <?php 

                          $ptmove = new_ptmove_address($info['patientID']);

                          $ptmove_new_phone = get_new_patient_phone($info['patientID']);

                          $ptmove_residence = get_new_patient_residence($info['patientID']);

                          $ptmove_final = $ptmove[0];

                        ?>

              <?php if(!empty($ptmove)) :?>

                  <input type="text" class="form-control" id="" placeholder="Enter Address" name="p_street" style="margin-bottom:10px;" value="<?php echo $ptmove_final['ptmove_street'] ?>">

                      </div>

                  </div>

                   <div class="col-md-4" style="padding-right:0px">

                        <div class="form-group">

                           <label>Apartment # </label>

                          <input type="text" class="form-control" id="" placeholder="Apartment #, Room #" name="p_placenum" style="margin-bottom:10px;" value="<?php echo $ptmove_final['ptmove_placenum'] ?>">

                        </div>

                  </div>

                  <div class="clearfix"></div>

                  <div class="col-md-4" style="padding-right:0px">

                      <div class="form-group" style="padding-right:0px">

                        <input type="text" class="form-control" id="city_confirm" placeholder="City" name="p_city" style="margin-bottom:20px" value="<?php echo $ptmove_final['ptmove_city'] ?>">

                      </div>

                  </div>

                  <div class="col-md-4" style="padding-right:0px">

                      <div class="form-group" style="padding-right:0px">

                         <input type="text" class="form-control" id="state_confirm" placeholder="State" name="p_state" style="margin-bottom:20px" value="<?php echo $ptmove_final['ptmove_state'] ?>">

                      </div>

                  </div>

                  <div class="col-md-4" style="padding-right:0px">

                      <div class="form-group" style="padding-right:0px">

                        <input type="text" class="form-control" id="postal_confirm" placeholder="Postal" name="p_postalcode" style="margin-bottom:20px" value="<?php echo $ptmove_final['ptmove_postal'] ?>">

                      </div>

                  </div>

              

            <?php else:?>

                <input type="text" class="form-control" id="" placeholder="Enter Address" name="p_street" style="margin-bottom:10px;" value="<?php echo $info['p_street'] ?>">

                      </div>

                  </div>

          

                 <div class="col-md-4" style="padding-right:0px">

                      <div class="form-group">

                         <label>Apartment # </label>

                        <input type="text" class="form-control" id="" placeholder="Apartment #, Room #" name="p_placenum" style="margin-bottom:10px;" value="<?php echo $info['p_placenum'] ?>">

                      </div>

                </div>

                <div class="clearfix"></div>

                <div class="col-md-4" style="padding-right:0px">

                    <div class="form-group">

                      <input type="text" class="form-control" id="city_confirm" placeholder="City" name="p_city" style="margin-bottom:20px" value="<?php echo $info['p_city'] ?>">

                    </div>

                </div>

                <div class="col-md-4" style="padding-right:0px">

                    <div class="form-group">

                       <input type="text" class="form-control" id="state_confirm" placeholder="State" name="p_state" style="margin-bottom:20px" value="<?php echo $info['p_state'] ?>">

                    </div>

                </div>

                <div class="col-md-4" style="padding-right:0px">

                    <div class="form-group">

                      <input type="text" class="form-control" id="postal_confirm" placeholder="Postal" name="p_postalcode" style="margin-bottom:20px" value="<?php echo $info['p_postalcode'] ?>">

                    </div>

                </div>

            <?php endif;?>     

            </div>

        </div>



        <div class="col-sm-6">

          

          <div class="col-md-6" >

            <label>Height(IN)</label>

            <input type="text" class="form-control" id="" placeholder="Height(IN)" name="height" style="margin-bottom:10px" value="<?php echo $info['p_height'] ?>">

          </div>

          <div class="col-md-6" >

            <label>Weight(lbs) </label>

            <input type="text" class="form-control" id="" placeholder="Weight(lbs)" name="weight" style="margin-bottom:10px" value="<?php echo $info['p_weight'] ?>">

          </div>





          <div class="col-md-6" >

            <label>Phone Number</label>

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

            <label>Next of Kin</label>

            <?php if(!empty($ptmove_nextofkin)) :?> 

              <input type="text" class="form-control " id="" placeholder="Next of Kin" name="nextofkin" style="margin-bottom:10px" value="<?php echo $ptmove_nextofkin['ptmove_nextofkin'] ?>">

            <?php else:?>

              <input type="text" class="form-control " id="" placeholder="Next of Kin" name="nextofkin" style="margin-bottom:10px" value="<?php echo $info['p_nextofkin'] ?>">

            <?php endif;?>

          </div>



          <div class="col-md-6" >

            <label>Relationship</label>

            <?php if(!empty($ptmove_relationship)) :?> 

              <input type="text" class="form-control " id="" placeholder="Relationship" name="relationship" style="margin-bottom:10px" value="<?php echo $ptmove_relationship['ptmove_nextofkinrelation'] ?>">

            <?php else:?>

              <input type="text" class="form-control " id="" placeholder="Relationship" name="relationship" style="margin-bottom:10px" value="<?php echo $info['p_relationship'] ?>">

            <?php endif;?>

          </div>





          <div class="col-md-6">

            <label>Next of Kin Phone No.</label>

              <?php if(!empty($ptmove_phonenum)) :?> 

                <input type="text" class="form-control person_num" id="" placeholder="Next of Kin Phone No." name="nextofkinnum" style="margin-bottom:20px" value="<?php echo $ptmove_phonenum['ptmove_nextofkinphone'] ?>">

              <?php else:?>

                <input type="text" class="form-control person_num" id="" placeholder="Next of Kin Phone No." name="nextofkinnum" style="margin-bottom:20px" value="<?php echo $info['p_nextofkinnum'] ?>">

              <?php endif;?>

            </div>

            <div class="col-md-6">

            <label>Residence</label>

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

                              <label>New CUS Move Address </label>

                      <input type="text" class="form-control ptmove_required" id="" placeholder="Enter Address" name="pt_street" style="margin-bottom:10px" value="<?php echo $ptmove_info['ptmove_street'] ?>">

                            </div>

                        </div>

                

                   <div class="col-md-4">

                        <div class="form-group">

                           <label>Apartment # </label>

                          <input type="text" class="form-control ptmove_required2" id="" placeholder="Apartment #, Room #" name="pt_placenum" style="margin-bottom:10px" value="<?php echo $ptmove_info['ptmove_placenum'] ?>">

                        </div>

                  </div>

                  <div class="clearfix"></div>

                  <div class="col-md-4">

                      <div class="form-group">

                        <input type="text" class="form-control ptmove_required3" id="city_pt" placeholder="City" name="pt_city" style="margin-bottom:20px" value="<?php echo $ptmove_info['ptmove_city'] ?>">

                      </div>

                  </div>

                  <div class="col-md-4" >

                      <div class="form-group">

                         <input type="text" class="form-control ptmove_required4" id="state_pt" placeholder="State" name="pt_state" style="margin-bottom:20px" value="<?php echo $ptmove_info['ptmove_state'] ?>">

                      </div>

                  </div>

                  <div class="col-md-4" >

                      <div class="form-group">

                        <input type="text" class="form-control ptmove_required5" id="postal_pt" placeholder="Postal" name="pt_postalcode" style="margin-bottom:20px" value="<?php echo $ptmove_info['ptmove_postal'] ?>">

                      </div>

                  </div>

                  <div class="col-md-8">

                            <div class="form-group">

                              <label>Patient Phone Number </label>

                      <input type="text" class="form-control person_num ptmove_required6" id="" placeholder="Phone number" name="pt_phone" style="margin-bottom:10px" value="<?php echo $ptmove_info['ptmove_patient_phone'] ?>">

                            </div>

                    </div>



                    </div>



                <?php endif;?>

        </div>

      </div>

<?php endforeach;?>

<?php endif;?>

  

  <div class="col-sm-12">

      

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

        <label>DME Staff Member Confirming Work Order</label>

        <input type="text"  class="form-control confirmed_by" id="exampleInputEmail1" placeholder="<?php echo $fname." ".$lname."." ?>" name="" style="margin-bottom:20px" value="<?php echo $fname." ".$lname_complete ?>" readonly>

      </div>

</div>



<table class="table table-striped bg-white b-a col-md-12 edit_patient_orders" id="confirm_info_table" style="margin-top:0px;margin-left: 0px;">

          <thead>

            <tr>

              <th style="width: 40px" class="hide_on_print">WO#</th>

              <!-- <th style="width: 40px">Date Ordered</th> -->

              <th style="width: 60px" class="hide_on_print">Order Date</th>

              <th style="width: 90px">Activity Type</th>

              <th style="width: 60px" class="hide_on_print">Item #</th>

              <th style="width: 90px">Item Description</th>

              <th style="width: 60px" class="hide_on_print">Qty.</th>

              <th style="width: 90px">Serial/Lot #</th>

              <!-- <th style="width: 90px">Lot #</th> -->

              <th style="width: 90px">Picked Up Date</th>

              <th style="width: 90px">Capped Type</th>

              <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>

                <th style="width: 1px" class="action_data hide_on_print">Cancel Item(s)</th>

              <?php endif;?>

            </tr>

          </thead>

          <tbody>



           

             <?php if(!empty($summaries)) :?>

                <?php foreach($summaries as $info) :?>

                      <?php

                        $disable_cancel = ""; 

                        if($info['pickup_sub'] == "expired" || $info['pickup_sub'] == "discharged" || $info['pickup_sub'] == "revoked" || $info['activity_typeid'] == 2)

                        {

                          $disable_cancel = "disabled";

                        }

                        else

                        {

                          $disable_cancel = "";

                        }



                      ?>

                      <tr style="<?php if($info['canceled_order'] == 1) echo 'text-decoration:line-through' ?> <?php if($info['parentID'] != 0) echo 'visibility:hidden;position: fixed;top: 1px;left: 1px;' ?> " >

                        <td class="hide_on_print">

                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][person_confirming_order]" value="<?php echo $fname." ".$lname_complete ?>" class="" />

                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][driver_name]" value="" class="name_of_driver" />

                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][uniqueID]" value="<?php echo $info['uniqueID'] ?>" class="hdn_unique_id" />

                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][key_desc]" value="<?php echo $info['key_desc'] ?>" />

                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][activity_typeid]" value="<?php echo $info['activity_typeid'] ?>" />

                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_respite_order]" value="<?php echo $info['pickedup_respite_order'] ?>" />



                          <a href="javascript:void(0)"  ><?php echo substr($info['uniqueID'],4,10) ?></a>

                        </td>

                         <td style="width:105px" class="hide_on_print">

                          <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][order_date]" class="datepicker form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo get_original_order_date($info['equipmentID'], $info['medical_record_id'], $info['uniqueID']) ?>" />

                        </td>

                        <td>

                          <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][act_name]" value="<?php echo $info['activity_name'] ?>" />

                          <?php echo $info['activity_name'] ?>

                        </td>

                        <td class="hide_on_print">

                            <input type="text" value="<?php echo $info['item_num'] ?>" style="width:70px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][item_num]" class="item_num form-control" />

                        </td>



                        <td style="width:auto">

                          

                          <?php if($info['equipmentID'] == 181 || $info['equipmentID'] == 182 || $info['equipmentID'] == 170 || $info['equipmentID'] == 11) :?>

                            

                            <?php if($info['equipmentID'] == 181 || $info['equipmentID'] == 182) :?>

                              

                              <?php if($info['activity_typeid'] != 2) :?>

                                <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="patient_weight_required" title="Patient Weight is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />

                                <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>

                              <?php else:?>

                                <a href="javascript:void(0)" style="" class="equipment_options_tooltip" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>"><?php echo $info['key_desc'] ?></a>

                              <?php endif;?>

                            

                            <?php else:?>

                              <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="lot_number_required" title="Lot Number is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />

                              <a href="javascript:void(0)" style="border-bottom:1px solid #51c6ea;border-bottom-style:dotted" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>

                            <?php endif;?>



                          <?php else:?>

                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover add_patient_weight add_lot_number" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" data-medical-id="<?php echo $info['medical_record_id']?>" data-patient-id="<?php echo $info['patientID'] ?>"><?php echo $info['key_desc'] ?></a>

                          <?php endif;?>

                        </td>



                        <?php if($info['categoryID'] != 3) :?>

                           

                            <?php if($info['categoryID'] == 2) :?>

                              

                              <?php if($info['equipment_value'] > 1) :?>

                                <td style="width:75px" class="hide_on_print">

                                 <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />

                                 <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>

                                </td>



                              <?php else:?>

                                <td style="width:75px" class="hide_on_print">

                                 <?php if(get_noncapped_quantity($info['equipmentID'],$info['uniqueID'])==0) :?>

                                   <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />

                                   <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>

                                <?php else:?>

                                   <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />

                                   <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>



                              <?php endif;?>

                              </td>

                            <?php endif;?>



                            <?php else:?>

                              <td style="width:75px" class="hide_on_print">

                               <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />

                               <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>

                              </td>

                            <?php endif;?>



                        <?php else:?>

                          

                          <?php if($info['equipment_value'] > 1) :?>

                            <td style="width:75px" class="hide_on_print">

                               <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />

                               <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>

                            </td>

                          <?php else:?>

                            <?php if($info['equipmentID'] == 7) :?>

                                <td style="width:75px" class="hide_on_print">

                                  <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />

                                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>

                                </td>

                            <?php else:?>  

                                <td style="width:75px" class="hide_on_print">

                                  <input type="text" value="<?php echo get_disposable_quantity($info['equipmentID'],$info['uniqueID']) ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />

                                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>

                                </td>

                            <?php endif;?>

                          <?php endif;?>



                        <?php endif;?>

                        

                        <td>

                          <?php if($info['parentID'] == 0) :?>



                            <?php if($info['serial_num'] == "pickup_order_only") :?>

                              <input type="text" value="<?php echo get_original_serial_number($info['equipmentID'], $info['medical_record_id'], $info['uniqueID']) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />

                            <?php else:?>

                              <input type="text" value="<?php echo $info['serial_num'] ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required />

                            <?php endif;?>



                          <?php else:?>

                            <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required />

                          <?php endif;?>

                        </td>

                      <td>

                        <?php if($info['summary_pickup_date'] != '0000-00-00') :?>



                          <?php if($info['activity_typeid'] == 1 || $info['activity_typeid'] == 3 || $info['activity_typeid'] == 4 || $info['activity_typeid'] == 5):?>

                            <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />

                          <?php else:?>

                            <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required data-work-order="<?php echo $info['uniqueID'] ?>" />

                          <?php endif;?>



                        <?php else :?>

                          

                          <?php if($info['activity_typeid'] == 1 || $info['activity_typeid'] == 3 || $info['activity_typeid'] == 4 || $info['activity_typeid'] == 5):?>

                            <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required disabled data-work-order="<?php echo $info['uniqueID'] ?>" />

                          <?php else:?>

                            <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][pickedup_date]" class="pickup_date datepicker form-control auto_fillout_pickedup<?php echo $info['uniqueID'] ?>" required data-work-order="<?php echo $info['uniqueID'] ?>" />

                          <?php endif;?>



                        <?php endif;?>

                      </td>



                      <td>

                        <?php if($info['type'] == 'Capped Item') :?>

                          <p class="label label-info"><?php echo $info['type'] ?></p>

                        <?php elseif($info['type'] == 'Non-Capped Item') :?>

                           <p class="label label-warning"><?php echo $info['type'] ?></p>

                        <?php else:?>

                          <p class="label label-success"><?php echo $info['type'] ?></p>

                        <?php endif;?>

                      </td>

                  <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>

                    <td class="hide_on_print">

                        <div class="checkbox" style="margin-top:4px">

                        <?php echo form_open("",array("id"=>"canceled-order-form")) ?>

                           <label class="i-checks data_tooltip" title="Cancel Item">

                              <input type="checkbox" <?php if($info['canceled_order'] == 1) echo 'checked' ?> name="canceled_status" class="cancel_item_checkbox" <?php echo $disable_cancel ?> data-equipment-id="<?php echo $info['equipmentID'] ?>" data-id="<?php echo $info['medical_record_id'] ?>" data-fname="<?php echo $info['p_fname'] ?>" data-lname="<?php echo $info['p_lname'] ?>" data-hospice="<?php echo $info['hospice_name'] ?>" data-patient-id="<?php echo $info['patientID'] ?>" /><i></i>

                           </label>

                        <?php echo form_close() ?>

                        </div>

                    </td>

                  <?php endif;?>

                </tr>



                

              <?php endforeach ;?>

            <?php endif ;?>

          </tbody>

      </table>



      <div class="pull-right" style="margin-left: 20px;">

        <label>DME Staff Member Delivered Order</label>

        <input type="text"  class="form-control driver_name_to_save" id="exampleInputEmail1" placeholder="Delivered by" name="" style="margin-bottom:20px" value="">

      </div>

<?php echo form_close() ;?>

</div>   

</div>





    </div>

  </div>

</div>





<div class="bg-light lter wrapper-md">

   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>

</div>

