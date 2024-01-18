<?php $fname = $this->session->userdata('firstname'); ?>
<?php $lname_complete = $this->session->userdata('lastname'); ?>
<?php $lname = substr($this->session->userdata('lastname'),0,1); ?>
<?php
      $style = "";
      $style =  ($info['canceled_order'] == 1)? 'text-decoration:line-through' : '';
      $style = $style.($info['parentID']!=0)? 'visibility:hidden;position: fixed;top: 1px;left: 1px;' : '';
      echo $style.$hide_style;

?>
 <tr id="confirm_tr_<?php echo $info['equipmentID']; ?>"
      style="<?php echo $style;?>" >
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
    <td style="width:105px">
        <input type="text" name="order_summary[<?php echo $info['equipmentID']?>][order_date]" class="datepicker form-control order_date looped_order_date<?php echo $info['uniqueID'] ?>" data-order-unique-id="<?php echo $info['uniqueID'] ?>" value="<?php echo date("m/d/Y", strtotime($info['pickup_date'])) ?>" />
    </td>
    <!-- activity name -->
    <td>
              <input type="hidden" name="order_summary[<?php echo $info['equipmentID']?>][act_name]" value="<?php echo $info['activity_name'] ?>" />
              <?php
                $activity_type_display = "";
                $address_type2 = array("type" => 0);
                if(!empty($address_type) && isset($address_type[$info['addressID']])){
                  $address_type2 = $address_type[$info['addressID']];
                }
                $address_sequence = 0;
                $address_count = 1;
                if($info['activity_name'] == "Delivery")
                {
                  if(($address_type2['type']) == 0)
                  {
                    $activity_type_display = "Delivery";
                  }
                  else if($address_type2['type'] == 1)
                  {
                    $address_sequence = (isset($ptmove_addresses_ID[$info['addressID']]))?  $ptmove_addresses_ID[$info['addressID']]['sequence'] : 1;
                    $activity_type_display = "Delivery (CUS Move ".($address_sequence > 1)? " {$address_sequence})" : ")";
                  }
                  else
                  {
                    $address_sequence = (isset($respite_addresses_ID[$info['addressID']]))?  $respite_addresses_ID[$info['addressID']]['sequence'] : 1;
                    $activity_type_display = "Delivery (Respite".($address_sequence > 1)? " {$address_sequence})" : ")";
                  }
                }
                else if($info['activity_name'] == "Exchange")
                {
                  if($address_type2['type'] == '2')
                  {
                    $address_sequence = (isset($respite_addresses_ID[$info['addressID']]))?  $respite_addresses_ID[$info['addressID']]['sequence'] : 1;
                    $activity_type_display = "Exchange (Respite".($address_sequence > 1)? " {$address_sequence})" : ")";
                  }else if($address_type2['type'] == '1') {
                    $address_sequence = (isset($ptmove_addresses_ID[$info['addressID']]))?  $ptmove_addresses_ID[$info['addressID']]['sequence'] : 1;
                    $activity_type_display = "Exchange (CUS Move ".($address_sequence > 1)? " {$address_sequence})" : ")";
                  }else{
                    $activity_type_display = "Exchange";
                  }
                }
                else if($info['activity_name'] == "CUS Move")
                {
                  $address_sequence = (isset($ptmove_addresses_ID[$info['addressID']]))?  $ptmove_addresses_ID[$info['addressID']]['sequence'] : 1;
                  $activity_type_display = "CUS Move ".($address_sequence > 1)? " {$address_sequence}" : "";
                }
                else if($info['activity_name'] == "Respite")
                {
                  $address_sequence = (isset($respite_addresses_ID[$info['addressID']]))?  $respite_addresses_ID[$info['addressID']]['sequence'] : 1;
                  $activity_type_display = "Respite".($address_sequence > 1)? " {$address_sequence}" : "";
                }
                else if($info['activity_name'] == "Pickup")
                {
                  if(($address_type2['type']) == 0)
                  {
                    $activity_type_display = "Pickup";
                  }
                  else if($address_type2['type'] == 1)
                  {
                    $address_sequence = (isset($ptmove_addresses_ID[$info['addressID']]))?  $ptmove_addresses_ID[$info['addressID']]['sequence'] : 1;
                    $activity_type_display = "Pickup (CUS Move ".($address_sequence > 1)? " {$address_sequence})" : ")";
                  }
                  else
                  {
                    $address_sequence = (isset($respite_addresses_ID[$info['addressID']]))?  $respite_addresses_ID[$info['addressID']]['sequence'] : 1;
                    $activity_type_display = "Pickup (Respite".($address_sequence > 1)? " {$address_sequence})" : ")";
                  }
                }
              ?>
              <?php echo $activity_type_display; ?>
    </td>
    <!-- end of activity name -->

    <!-- ITEM NO -->
    <td>
            <?php
              if($info['equipment_company_item_no'] != "0")
              {
                echo $info['equipment_company_item_no'];
              }
              else
              {
                if(isset($subequipments[$info['equipmentID']])){
                  foreach ($subequipments[$info['equipmentID']] as $key) {
                    if($key['equipment_company_item_no'] != "0")
                    {
                      $used_subequipment = get_equal_subequipment_order($key['equipmentID'], $info['uniqueID']);
                      if(!empty($used_subequipment))
                      {
                        echo $key['equipment_company_item_no'];
                        break;
                      }
                    }
                  }
                }
              }
            ?>
    </td>
    <!--  end of ITEM NO -->
    <td style="width:auto;text-transform:uppercase!important;" class="item-description" data-info='<?php echo json_encode($info);?>'><?php echo $item_description; ?></td>
    <td style="width:75px"><?php echo $item_quantity;?></td>
    <td><?php echo $item_serial;?></td>
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
                 <p class="label label-info"><?php echo $info['type'] ;?></p>
             <?php
               elseif($info['type'] == 'Non-Capped Item') :
             ?>
               <p class="label label-warning"><?php echo $info['type'] ;?></p>
             <?php
               else:
             ?>
                 <p class="label label-success"><?php echo $info['type'] ;?></p>
             <?php
               endif;
             ?>
           </td>

           <?php
             if(($info['activity_typeid'] != 3 && $counter > 1) || ($info['activity_typeid'] != 3 && $counter > 1)) :
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
