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

<?php if(!empty($infos)):?>
<?php foreach($infos as $info) :?>

<?php echo form_open("",array("class"=>"edit_patient_profile_form")) ?>
<div class="row">
                        <div class="">
                          <div class="col-md-6" style="padding-left:30px;">
                            
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
                                 <label>Patient First Name <span style="color:red;">*</span></label>
                                    <div class="form-group">
                                        <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="" name="p_fname" style="margin-bottom:10px" value="<?php echo $info['p_fname'] ?>">
                                    </div>
                                </div>
                                 <div class="col-md-6" >
                                 <label>Patient Last Name <span style="color:red;">*</span></label>
                                 <div class="clearfix"></div>
                                    <div class="form-group">
                                        <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="" name="p_lname" style="margin-bottom:10px" value="<?php echo $info['p_lname'] ?>">
                                    </div>
                                </div>
                                <div class="clearfix"></div>

                                  <div class="patient-address-fields" style="margin-left:-16px;">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                          <label>Patient Address <span class="text-danger-dker">*</span></label>
                                  <input type="text" class="form-control" id="" placeholder="Enter Address" name="p_street" style="margin-bottom:10px;" value="<?php echo $info['p_street'] ?>">
                                        </div>
                                    </div>
                            
                               <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Place No. <span class="text-danger-dker">*</span></label>
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

                                  </div>
                          </div>
                          
                          
                            <div class="col-md-6">
                              <div class="col-md-6" >
                              <label>Height(ft)<span class="text-danger-dker">*</span></label>
                              <input type="text" class="form-control" id="" placeholder="Height(ft)" name="height" style="margin-bottom:10px" value="<?php echo $info['p_height'] ?>">
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
                              <div class="col-md-6">
                              <label>Residence<span class="text-danger-dker">*</span></label>
                              <input type="text" class="form-control " id="" placeholder="Residence" name="deliver_to_type" style="margin-bottom:10px" value="<?php echo $info['deliver_to_type'] ?>">
                              </div>

                              <div class="col-md-6">
                              <label>DME Staff Member Delivered Order<span class="text-danger-dker">*</span></label>
                              <input type="text"  class="form-control " id="exampleInputEmail1" placeholder="DME Staff Member Delivered Order" name="driver_name" style="margin-bottom:20px" value="<?php echo $info['driver_name'] ?>">
                              </div>

                              <div style="margin-right:15px;">
                                    <button type="button" class="btn btn-primary pull-right save_edit_changes" data-id="<?php echo $info['medical_record_id'] ?>" name="" style="margin-bottom:10px">Save Changes</button>
                              </div>
                            </div>
                           
                        </div>
                    </div>
 <?php endforeach;?>
  <?php endif;?>
<?php echo form_close() ?>



<div class="panel panel-default">
<div class="panel-heading font-bold">
<h4>Patient Order Summary</h4>
</div>
<div class="panel-body">
  <table class="table table-striped bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">
          <thead>
            <tr>
              <th style="width: 40px">WO#</th>
              <!-- <th style="width: 40px">Date Ordered</th> -->
              <th style="width: 60px">Order Date</th>
              <th style="width: 90px">Activity Type</th>
              <th style="width: 60px">Item #</th>
              <th style="width: 90px">Item Description</th>
              <th style="width: 60px">Qty.</th>
              <th style="width: 90px">Serial #</th>
              
              <th style="width: 90px">Picked Up Date</th>
			        <th style="width: 90px">Capped Type</th>
              <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
                <th style="width: 1px">Action</th>
              <?php endif;?>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($summaries)) :?>
                <?php foreach($summaries as $info) : ?>
                      
                  <?php if($info['categoryID'] != 3) :?>  

                      <tr style="<?php if($info['canceled_order'] == 1) echo 'text-decoration:line-through' ?>">
                        <!-- <td style="width:auto"><?php echo date("m/d/Y", strtotime($info['pickup_date'])) ?></td> -->
                        <td><a href="javascript:void(0)"  ><?php echo substr($info['uniqueID'],4,10) ?></a></td>
                         <td style="width:105px">
                          <input type="text" class="datepicker form-control order_date" value="<?php echo date("m/d/Y", strtotime($info['pickup_date'])) ?>" />
                        </td>
                        <td><?php echo $info['activity_name'] ?></td>
                        <td>
                            <input type="text" value="<?php echo $info['item_num'] ?>" style="width:70px;border-color:#fafafa !important" name="" class="item_num form-control" />
                        </td>
                        <td style="width:auto"><a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a></td>
                        <td style="width:75px">
                           <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="" class="form-control equipment_qty" />
                            <p style="float: right;margin-top: -27px;">ea</p>
                        </td>
                        
                        <td>
                            <input type="text" value="<?php echo $info['serial_num'] ?>" style="width:125px;border-color:#fafafa !important" name="" class="serial_num form-control" />
                        </td>
                        <!-- <td style="">
                            <input type="text" value="<?php echo $info['lot_num'] ?>" style="float:left;width:65px !important;border-color:#fafafa !important" name="lot_num" class="lot_num form-control" />
                            <i class="fa fa-comments notes_help" title="Click to View Notes." data-id="<?php echo $info['equipmentID'] ?>" data-value="<?php echo $info['uniqueID'] ?>" style="float:left;margin-left:4px;margin-top:10px;cursor:pointer;"></i>
                        </td> -->
                        
                         <?php if($info['activity_typeid'] != 1) :?>
                           <td>
                            <?php if($info['summary_pickup_date'] != '0000-00-00') :?>
                              <input type="text" value="<?php echo date("m/d/Y", strtotime($info['summary_pickup_date'])) ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="" class="pickup_date datepicker form-control" />
                            <?php else :?>
                              <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="" class="pickup_date datepicker form-control" />
                            <?php endif;?>
                          </td>
                        <?php else:?>
                          <td><p></p></td>
                        <?php endif;?>
						
						            <td>
                          <?php if($info['type'] == 'Capped Item') :?>
                            <p class="label label-success"><?php echo $info['type'] ?></p>
                          <?php else:?>
                             <p class="label label-warning"><?php echo $info['type'] ?></p>
                          <?php endif;?>
                        </td>

                        <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
                          <td>
                              <?php echo form_open("",array("class"=>"update_summary_confirmed".$info['equipmentID'])) ?>     
                                <input type="hidden" name="hdn_unique_id" value="<?php echo $info['uniqueID'] ?>" class="hdn_unique_id" />
                                <input type="hidden" name="hdn_medical_record_id" value="<?php echo $info['medical_record_id'] ?>" class="hdn_equip_id" />
                                <input type="hidden" name="item_num" value="" class="hdn_item_num" />
                                <input type="hidden" name="qty" value="1" class="hdn_equipment_qty" />
                                <input type="hidden" name="serial_num" value="" class="hdn_serial_num" />
                                <input type="hidden" name="pickup_date" value="" class="hdn_pickup_date" />
                                <input type="hidden" name="order_date" value="<?php echo date("m/d/Y", strtotime($info['pickup_date'])) ?>" class="hdn_order_date" />

                                <a href="javascript:void(0)" class="save_edited_summary data_tooltip" title="Save" data-value="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" style="text-decoration:none">
                                  <button type="button" class="btn btn-info btn-xs">
                                    <i class="glyphicon glyphicon-save" style=""></i> Save
                                  </button>
                                </a>
                              <?php echo form_close() ?> 
                          </td>
                        <?php endif;?>
                      </tr>

                  <?php endif;?>
              <?php endforeach ;?>
            <?php endif ;?>
          </tbody>
      </table>
      <button class="btn btn-danger pull-right" onclick="closeModalbox()">Close</button>
      <button class="btn btn-success pull-right btn-save-order-fields"  style="margin-right:10px">Save Changes</button>&nbsp
</div>   
</div>
 