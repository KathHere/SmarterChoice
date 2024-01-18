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

<?php if(!empty($infos)):?>
<?php foreach($infos as $info) : ?>

<?php $medical_id = $info['medical_record_id'] ?>

<?php echo form_open("",array("class"=>"edit_patient_profile_form")) ?>
<div class="row">
            <div class="">
              <div class="col-md-6" style="padding-left:30px;">
                     <input type="hidden" name="hdn_patient_id" value="<?php echo $info['patientID'] ?>" />
					 
					 <input type="hidden" class="hdn_hospice_id" name="" value="<?php echo $info['hospiceID'] ?>" />

                     <label>Patient Medical Record # <span style="color:red;">*</span></label>
                     <div class="clearfix"></div>
                        <div class="form-group">
                            <input type="text"  class="form-control medical_record_num" id="exampleInputEmail1"  placeholder="" name="medical_record_id" style="margin-bottom:10px" value="<?php echo $info['medical_record_id'] ?>">
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

                      </div>
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
                  <div class="col-md-6">
                  <label>Residence<span class="text-danger-dker">*</span></label>
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
                  <!-- <input type="text" class="form-control " id="" placeholder="Residence" name="deliver_to_type" style="margin-bottom:10px" value="<?php echo $info['deliver_to_type'] ?>"> -->
                  </div>

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

<?php echo form_open("",array("class"=>"update_order_summary")) ;?>  
  <div class="table-responsive mb15">
  <table class="table table-striped bg-white b-a col-md-12 edit_patient_orders"  style="margin-top:0px;margin-left: 0px;">
          <thead>
            <tr>
              <th style="width: 40px">WO#</th>
              <th style="width: 60px">Order Date</th>
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

            <?php if(!empty($summaries)) :?>
              <input type="hidden" name="count_looped_data" value="<?php echo count($summaries); ?>" />
                <?php foreach($summaries as $info) :?>

                	<?php if($info['parentID'] == 0) :?>
                    <tr style="<?php if($info['canceled_order'] == 1) echo 'text-decoration:line-through' ?> <?php if($info['parentID'] != 0) echo 'visibility:hidden;position: fixed;top: 1px;left: 1px;' ?> ">
                      <td>
                        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][uniqueID]" value="<?php echo $info['uniqueID'] ?>" class="hdn_unique_id" />
                        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][key_desc]" value="<?php echo $info['key_desc'] ?>" />
                        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][activity_typeid]" value="<?php echo $info['activity_typeid'] ?>" />
                        <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_respite_order]" value="<?php echo $info['pickedup_respite_order'] ?>" />

                        <a href="javascript:void(0)"><?php echo substr($info['uniqueID'],4,10) ?></a>
                      </td>
                      <td style="width:105px">
                        <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][order_date]" class="datepicker form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo $info['pickup_date'] ?>" />
                      </td>
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
                      <td>
                          <input type="text" value="<?php echo $info['item_num'] ?>" style="width:70px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][item_num]" class="item_num form-control" />
                      </td>
                      <td style="width:auto">
                        <?php 
                          if($info['equipmentID'] == 55 || $info['equipmentID'] == 20){
                        ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo "Full Electric ".$info['key_desc'] ?></a>
                        <?php 
                          }else{
                        ?>
                            <a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover" data-uniqueid="<?php echo $info['uniqueID'] ?>" data-id="<?php echo $info['equipmentID'] ?>" ><?php echo $info['key_desc'] ?></a>
                        <?php 
                          }
                        ?>
                      </td>
                          	  
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
                                if(get_noncapped_quantity($info['equipmentID'],$info['uniqueID'])==0){
                        ?>
                                  <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                  <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                        <?php 
                                }else{
                        ?>
                                   <input type="text" value="<?php echo get_noncapped_quantity($info['equipmentID'],$info['uniqueID']); ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                                   <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
                        <?php  
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
                              if($info['equipmentID'] == 306){
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

                              <td>
                                <?php if($info['parentID'] == 0) :?>
                                    <input type="text" value="<?php echo $info['serial_num'] ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num form-control" required />
                                <?php else:?>
                                  <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][serial_num]" class="serial_num form-control" required />
                                <?php endif;?>
                              </td>
                              
                               <td>
                              <?php if($info['summary_pickup_date'] != '0000-00-00') :?>
                                <input type="text" value="<?php echo $info['summary_pickup_date'] ?>" style="width:100px;border-color:#fafafa !important;margin:0px !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker form-control" required />
                              <?php else :?>
                                <input type="text" value="" style="width:100px;margin:0px !important;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][<?php echo $index ?>][pickedup_date]" class="pickup_date datepicker form-control" required />
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
                            </tr>
                    <?php endif;?>

                    <?php $index++; ?>
              <?php endforeach ;?>
            <?php endif ;?>
          </tbody>
      </table>
      </div>
      <button type="button" class="btn btn-danger pull-right" onclick="closeModalbox()">Close</button>
      <button type="button" class="btn btn-success pull-right btn-save-order-fields" data-medical-id="<?php echo $medical_id ?>"  style="margin-right:10px">Save Changes</button>&nbsp
<?php echo form_close() ;?>
</div>   
</div>
 