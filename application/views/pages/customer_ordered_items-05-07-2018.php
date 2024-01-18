<?php
  $enableclick = "";
  if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
  {
    $enableclick = "editable-click";
  }

  if(!empty($summaries))
  {
    // 0 = [486,163,164];
    // 1 = [68,159,160,161,162];
    // 2 = [316,325,334,343,466];
    // 3 = [36,466,178];
    // 4 = [422,259];
    // 5 = [415,259];
    // 6 = [174,490,492];
    // 7 = [67,157];
    $packaged_items_ids_list = [486,163,164,68,159,160,161,162,316,325,334,343,466,36,178,422,259,415,174,490,492,67,157];
    $packaged_item_sign = 0;
    $packaged_items_list = array();

    $ptmove_addresses_ID = array();
    $respite_addresses_ID = array();

    $temp_pt_add = get_ptmove_addresses_ID_v2($patientID);
    foreach($temp_pt_add as $key=>$val){
      $val['sequence'] = $key + 1;
      $ptmove_addresses_ID[$val['id']] = $val;
    }

    $temp_rs_add = get_respite_addresses_ID_v2($patientID);
    foreach($temp_rs_add as $key=>$val){
      $val['sequence'] = $key + 1;
      $respite_addresses_ID[$val['id']] = $val;
    }

    //if admin allow update
    foreach($summaries as $summary)
    {
      if($summary['canceled_order'] == 0 && $summary['canceled_from_confirming'] == 0)
      {
        if(!in_array($summary['equipmentID'], $packaged_items_ids_list))
        {
?>
          <tr data-id="Sample data-id" style="<?php if($summary['parentID'] != 0) echo 'display:none' ?>">
            <input type="hidden" name="hdn_act_id_for_checking[]" id="act_id_checking" value="<?php echo $summary['activity_typeid'] ?>" />
            <input type="hidden" name="hdn_unique_id" value="<?php echo $summary['uniqueID'] ?>" class="hdn_unique_id" />
            <input type="hidden" name="hdn_medical_record_id" value="<?php echo $summary['medical_record_id'] ?>" class="hdn_equip_id" />

            <!--DELIVERY DATE-->
            <td style="width:auto">
              <!-- order date of the items regardless of its activities -->
              <?php
                if($summary['pickup_date'] != '0000-00-00' && $summary['pickup_date'] != '')
                {
                  if($summary['order_status'] != "confirmed")
                  {
              ?>
                    <a
                      id="pickup_date"
                      data-pk="<?php echo $summary['uniqueID'] ?>"
                      data-url="<?php echo base_url('order/update_data/date/uniqueID'); ?>"
                      data-title="Enter date"
                      data-value="<?php echo date("Y-m-d", strtotime($summary['pickup_date'])) ?>"
                      data-type="combodate"
                      data-maxYear="<?php echo date("Y"); ?>"
                      data-format="YYYY-MM-DD"
                      data-viewformat="MM/DD/YYYY"
                      data-template="MMM / D / YYYY"
                      href="javascript:void(0)" class="data_tooltip editable <?php echo $enableclick; ?> editable-combodate text-success text-bold"
                    >
                      <?php echo date("m/d/Y", strtotime($summary['pickup_date'])) ?>
                    </a>
              <?php
                  }
                  else
                  {
              ?>
                    <a
                      id="actual_order_date"
                      data-pk="<?php echo $summary['uniqueID'] ?>"
                      data-url="<?php echo base_url('order/update_data/date/uniqueID'); ?>"
                      data-title="Enter date"
                      data-value="<?php echo date("Y-m-d", strtotime($summary['actual_order_date'])) ?>"
                      data-type="combodate"
                      data-maxYear="<?php echo date("Y"); ?>"
                      data-format="YYYY-MM-DD"
                      data-viewformat="MM/DD/YYYY"
                      data-template="MMM / D / YYYY"
                      href="javascript:void(0)" class="data_tooltip editable <?php echo $enableclick; ?> editable-combodate text-success text-bold"
                    >
                      <?php echo date("m/d/Y", strtotime($summary['actual_order_date'])) ?>
                    </a>
              <?php
                  }
                }
              ?>
            </td>

            <!--WORK ORDER #-->
            <td style="width:90px;">
              <?php
                //getting activity type id depending
                // on the activity type
                // cus move and exchange need to be an original activity
                // type

                $get_type = "activity_typeid";
                $allowed_acttypes = array(3,4);
                if(in_array($summary['original_activity_typeid'], $allowed_acttypes))
                {
                    $get_type = "original_activity_typeid";
                }
              ?>

              <a href="javascript:;"
                class="view_original_order_information"
                rel="popover"
                data-toggle="popover"
                title=""
                data-trigger="hover"
                data-placement="top"
                data-html="true"
                data-container="body"
                data-content="Click to view the details"
                style="cursor:pointer"
                data-id="<?php echo $summary['medical_record_id'] ?>"
                data-value="<?php echo $summary['organization_id'] ?>"
                data-unique-id="<?php echo $summary['uniqueID'] ?>"
                data-act-id="<?php echo $summary[$get_type] ?>"
                data-patient-id="<?php echo $summary['patientID'] ?>"
              >
                <?php echo substr($summary['uniqueID'],4,10); ?>
              </a>
            </td>

            <!--ACTIVITY TYPE-->
            <td class="activity_type_column">
              <?php
                $activity_type_display = "";
                $address_sequence = 0;
                $address_count = 1;
                if($summary['activity_name'] == "Delivery")
                {
                  if(($summary['address_type']) == 0)
                  {
                    $activity_type_display = "Delivery";
                  }
                  else if($summary['address_type'] == 1)
                  {
                    $address_sequence = (isset($ptmove_addresses_ID[$summary['addressID']]))?  $ptmove_addresses_ID[$summary['addressID']]['sequence'] : 1;
                    if($address_sequence == 1)
                    {
                      $address_sequence = "";
                    }
                    $activity_type_display = "Delivery ( CUS Move ".$address_sequence.")";
                  }
                  else
                  {
                    $address_sequence = (isset($respite_addresses_ID[$summary['addressID']]))?  $respite_addresses_ID[$summary['addressID']]['sequence'] : 1;
                    if($address_sequence == 1)
                    {
                      $address_sequence = "";
                    }
                    $activity_type_display = "Delivery ( Respite ".$address_sequence.")";
                  }
                }
                else if($summary['activity_name'] == "Exchange")
                {
                  if($summary['address_type'] == '2')
                  {
                    $address_sequence = (isset($respite_addresses_ID[$summary['addressID']]))?  $respite_addresses_ID[$summary['addressID']]['sequence'] : 1;
                    if($address_sequence == 1)
                    {
                      $address_sequence = "";
                    }
                    $activity_type_display = "Exchange ( Respite ".$address_sequence.")";
                  }
                  else if($summary['address_type'] == '1')
                  {
                    $address_sequence = (isset($ptmove_addresses_ID[$summary['addressID']]))?  $ptmove_addresses_ID[$summary['addressID']]['sequence'] : 1;
                    if($address_sequence == 1)
                    {
                      $address_sequence = "";
                    }
                    $activity_type_display = "Exchange ( CUS Move ".$address_sequence.")";
                  }
                  else
                  {
                    $activity_type_display = "Exchange";
                  }
                }
                else if($summary['activity_name'] == "CUS Move")
                {
                  $address_sequence = (isset($ptmove_addresses_ID[$summary['addressID']]))?  $ptmove_addresses_ID[$summary['addressID']]['sequence'] : 1;
                  if($address_sequence == 1)
                  {
                    $address_sequence = "";
                  }
                  $activity_type_display = "CUS Move ".$address_sequence;
                }
                else if($summary['activity_name'] == "Respite")
                {
                  $address_sequence = (isset($respite_addresses_ID[$summary['addressID']]))?  $respite_addresses_ID[$summary['addressID']]['sequence'] : 1;
                  if($address_sequence == 1)
                  {
                    $address_sequence = "";
                  }
                  $activity_type_display = "Respite".$address_sequence;
                }
                else if($summary['activity_name'] == "Pickup")
                {
                  if(($summary['address_type']) == 0)
                  {
                    $activity_type_display = "Pickup";
                  }
                  else if($summary['address_type'] == 1)
                  {
                    $address_sequence = (isset($ptmove_addresses_ID[$summary['addressID']]))?  $ptmove_addresses_ID[$summary['addressID']]['sequence'] : 1;
                    if($address_sequence == 1)
                    {
                      $address_sequence = "";
                    }
                    $activity_type_display = "Pickup ( CUS Move ".$address_sequence.")";
                  }
                  else
                  {
                    $address_sequence = (isset($respite_addresses_ID[$summary['addressID']]))?  $respite_addresses_ID[$summary['addressID']]['sequence'] : 1;
                    if($address_sequence == 1)
                    {
                      $address_sequence = "";
                    }
                    $activity_type_display = "Pickup ( Respite ".$address_sequence.")";
                  }
                }

                echo $activity_type_display;
              ?>
            </td>

            <!--ITEM #-->
            <td class="hide_on_print">
              <?php
                if($summary['equipment_company_item_no'] != "0")
                {
                  echo $summary['equipment_company_item_no'];
                }
                else
                {
                  $subequipments = get_subequipment_id_v2($summary['equipmentID']);
                  foreach ($subequipments as $key) {
                    if($key['equipment_company_item_no'] != "0")
                    {
                      $used_subequipment = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
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
                if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :
                  // Oxygen Concentrator
                  if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343) :
              ?>
                    <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-differ="<?php echo $summary['orderID']; ?>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                  else:
                    //*** DELIVERY not CONFIRMED yet dri mo display ang item ***//
                    //check if naay sub equipment using equipment id, work uniqueId
                    $subequipment_id = get_subequipment_id($summary['equipmentID']);

                    //gets all the id's under the order
                    if($subequipment_id)
                    {
                      $count = 0;
                      $patient_lift_sling_count = 0;
                      $high_low_full_electric_hospital_bed_count = 0;
                      $equipment_count = 0;
                      $my_count_sign = 0;
                      $my_first_sign = 0;
                      $my_second_sign = 0;

                      foreach ($subequipment_id as $key) {
                        $value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);

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
                          //full electric hospital bed equipment
                          if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20){
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?></a></strong>
              <?php
                          }
                          //Hi-Low Full Electric Hospital Bed equipment
                          else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?></a></strong>
              <?php
                          }
                          //Patient Lift with Sling
                          else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a></strong>
              <?php
                          }
                          //Patient Lift Electric with Sling
                          else if($summary['equipmentID'] == 353)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a></strong>
              <?php
                          }
                          //Patient Lift Sling
                          else if($summary['equipmentID'] == 196)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $key['key_desc']; ?></a></strong>
              <?php
                          }
                          //(54 & 17) Geri Chair || (66 & 39) Shower Chair
                          else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']." ".$key['key_desc'].""; ?></a></strong>
              <?php
                          }
                          // Oxygen E Portable System && Oxygen Liquid Portable
                          else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            break;
                          }
                          //Oxygen Cylinder Rack
                          else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">Oxygen  <?php echo $key['key_desc']; ?></a></strong>
              <?php
                            break;
                          }
                          //(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
                          else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
                          {
                            if($my_count_sign == 0)
                            {
                              //wheelchair & wheelchair reclining
                              if($count == 1)
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
              <?php
                                if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
                                {
                                  $key['key_desc'] = '16" Narrow';
                                }
                                else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
                                {
                                  $key['key_desc'] = '18" Standard';
                                }
                                else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
                                {
                                  $key['key_desc'] = '20" Wide';
                                }
                                else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
                                {
                                  $key['key_desc'] = '22" Extra Wide';
                                }
                                else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
                                {
                                  $key['key_desc'] = '24" Bariatric';
                                }
                                echo $key['key_desc']." ".$summary['key_desc'];
                              }
                              else
                              {
                                echo " With ".$key['key_desc']." </a></strong>";
                              }
                            }
                            else
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"> 20" Wide
              <?php
                              echo $summary['key_desc'];
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
                          else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                            <?php
                              if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
                              {
                                $key['key_desc'] = '17" Narrow';
                              }
                              else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
                              {
                                $key['key_desc'] = '19" Standard';
                              }
                              echo $key['key_desc']." ".$summary['key_desc']." </a></strong>";
                          }
                          else if($summary['equipmentID'] == 30)
                          {
                            if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
                            {
                              if($count == 3)
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-differ="<?php echo $summary['orderID']; ?>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?>
              <?php
                                echo " With ".$key['key_desc']." </a></strong>";
                              }
                            }
                            else
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            }
                          }
                          //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
                          else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
                          {
              ?>
                            <strong>
                              <a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                                <?php echo $summary['key_desc']; ?>
                              </a>
                              <br />
                              <?php
                                $samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
                                if(strlen($samp) > 30)
                                {
                                  echo "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
                                }
                                else
                                {
                                  echo "<span style='font-weight:400;color:#000;'>".$samp."</span>";
                                }

                              ?>
                            </strong>
              <?php
                            break;
                          }
                          else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                          {
                            $samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
                            if($count == 1)
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']; ?> <?php echo $samp_conserving_device; ?> </a></strong>
              <?php
                            }
                          }
                          else if($summary['equipmentID'] == 282)
                          {

                          }
                          //equipments that has no subequipment but gets inside the $value if statement
                          else if($summary['equipmentID'] == 14)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                          }
                          else
                          {
                            if($summary['categoryID'] == 1)
                            {
                              $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                              if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                                break;
                              }
                              else if($non_capped_copy['noncapped_reference'] == 14)
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                              }
                              else if($non_capped_copy['noncapped_reference'] == 282)
                              {
                                $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
              ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?></a></strong>
              <?php
                                break;
                              }
                              else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a></strong>
              <?php
                              }
                              else if($non_capped_copy['noncapped_reference'] == 353)
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a></strong>
              <?php
                              }
                              else
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                              }
                            }
                            else
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            }
                          }
                        } //end of $value
                        //for Oxygen E cylinder do not remove as it will cause errors
                        else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                        {
                          break;
                        }
                        else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                        {


                        }
                        else if($summary['equipmentID'] == 282)
                        {
                          $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
              ?>
                          <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?></a></strong>
              <?php
                          break;
                        }
                        //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
                        else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
                        {
              ?>
                          <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                          break;
                        } //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
                        else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
                        {
              ?>
                          <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                        }
                        //for equipments with subequipment but does not fall in $value
                        else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
                        {
                          if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
                          {
                            $patient_lift_sling_count++;
                            if($patient_lift_sling_count == 6)
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            }
                          }
                          else if($summary['equipmentID'] == 398)
                          {
                            if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
                            {
                              $high_low_full_electric_hospital_bed_count++;
                              if($high_low_full_electric_hospital_bed_count == 2){
              ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                              }
                            }
                          }
                          // Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
                          else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                          {
                            $equipment_count++;
                            if($equipment_count == 2){
              ?>
                            <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            }
                          }
                        }
                        else
                        {
                          if($summary['categoryID'] == 1)
                          {
                            $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                            if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
                            {
              ?>
                                <strong>
                                  <a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                                    <?php echo $summary['key_desc']; ?>
                                  </a>
                                </strong>
              <?php
                              break;
                            }
                            else if($non_capped_copy['noncapped_reference'] == 14)
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            }
                            else
                            {

                            }
                          }
                          else
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                          }
                        }
                      }
                    }
                    else
                    {
                      if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
                      {
              ?>
                        <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"> <?php echo $summary['key_desc'] ?></a> <i class='fa fa-pencil edit_patient_weight' style="cursor:pointer;font-size:10px;" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"></i></strong>
              <?php
                      }
                      else
                      {
              ?>
                        <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                      }
                    }
                  endif;
                else:
                  if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
                  {
              ?>
                    <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-differ="<?php echo $summary['orderID']; ?>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                  }
                  else
                  {
                    //check if naay sub equipment using equipment id
                    $subequipment_id = get_subequipment_id($summary['equipmentID']);
                    //gets all the id's under the order
                    if($subequipment_id)
                    {
                      $count = 0;
                      $patient_lift_sling_count = 0;
                      $high_low_full_electric_hospital_bed_count = 0;
                      $equipment_count = 0;
                      $my_count_sign = 0;
                      $my_first_sign = 0;
                      $my_second_sign = 0;
                      foreach ($subequipment_id as $key) {
                        $value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
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
                          //equipment full electric hospital bed
                          if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
                          {
              ?>
                            <strong><a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equip_edit_options equipment_options_hover show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>"><?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?></a></strong>
              <?php
                          }
                          //Hi-Low Full Electric Hospital Bed equipment
                          else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?></a></strong>
              <?php
                          }
                          //Patient Lift with Sling
                          else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a></strong>
              <?php
                          }
                          //Patient Lift Electric with Sling
                          else if($summary['equipmentID'] == 353)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a></strong>
              <?php
                          }
                          //Patient Lift Sling
                          else if($summary['equipmentID'] == 196)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $key['key_desc']; ?></a></strong>
              <?php
                          }
                          //(54 & 17) Geri Chair || (66 & 39) Shower Chair
                          else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
                          {
              ?>
                            <strong><a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']." ".$key['key_desc'].""; ?></a></strong>
              <?php
                          }
                          // Oxygen E Portable System && Oxygen Liquid Portable
                          else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            break;
                          }
                          //Oxygen Cylinder Rack
                          else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">Oxygen  <?php echo $key['key_desc']; ?></a></strong>
              <?php
                            break;
                          }
                          //(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
                          else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
                          {
                            if($my_count_sign == 0)
                            {
                              //wheelchair & wheelchair reclining
                              if($count == 1)
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
              <?php
                                if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
                                {
                                  $key['key_desc'] = '16" Narrow';
                                }
                                else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
                                {
                                  $key['key_desc'] = '18" Standard';
                                }
                                else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126)
                                {
                                  $key['key_desc'] = '20" Wide';
                                }
                                else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
                                {
                                  $key['key_desc'] = '22" Extra Wide';
                                }
                                else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
                                {
                                  $key['key_desc'] = '24" Bariatric';
                                }
                                echo $key['key_desc']." ".$summary['key_desc'];
                              }
                              else
                              {
                                echo " With ".$key['key_desc']." </a></strong>";
                              }
                            }
                            else
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"> 20" Wide
              <?php
                              echo $summary['key_desc'];
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
                          else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                            <?php
                              if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
                              {
                                $key['key_desc'] = '17" Narrow';
                              }
                              else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
                              {
                                $key['key_desc'] = '19" Standard';
                              }
                              echo $key['key_desc']." ".$summary['key_desc']." </a></strong>";
                            ?>
              <?php
                          }
                          else if($summary['equipmentID'] == 30)
                          {
                            if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
                            {
                              if($count == 3)
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-differ="<?php echo $summary['orderID']; ?>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?>
              <?php
                                echo " With ".$key['key_desc']." </a></strong>";
                              }
                            }
                            else
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            }
                          }
                          //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
                          else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
                          {
              ?>
                            <strong>
                              <a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                                <?php echo $summary['key_desc'] ?>
                              </a>
                              <br />
                              <?php
                                $samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
                                if(strlen($samp) > 30)
                                {
                                  echo "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
                                }
                                else
                                {
                                  echo "<span style='font-weight:400;color:#000;'>".$samp."</span>";
                                }

                              ?>
                            </strong>
              <?php
                            break;
                          }
                          else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                          {
                            $samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
                            if($count == 1)
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']; ?> <?php echo $samp_conserving_device; ?> </a></strong>
              <?php
                            }
                          }
                          else if($summary['equipmentID'] == 282)
                          {

                          }
                          else if($summary['equipmentID'] == 14)
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                          }
                          else
                          {
                            if($summary['categoryID'] == 1)
                            {
                              $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                              if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                                break;
                              }
                              else if($non_capped_copy['noncapped_reference'] == 14)
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                              }
                              else if($non_capped_copy['noncapped_reference'] == 282)
                              {
                                $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
              ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?></a></strong>
              <?php
                                break;
                              }
                              else if($non_capped_copy['noncapped_reference'] == 353)
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a></strong>
              <?php
                              }
                              else
                              {
              ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                              }
                            }
                            else
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            }
                          }
                        }
                        //for Oxygen E cylinder do not remove as it will cause errors
                        else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                        {
                          break;
                        }
                        else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                        {

                        }
                        else if($summary['equipmentID'] == 282)
                        {
                          $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
              ?>
                          <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?> With <?php echo $samp_hospital_bed_extra_long;?></a></strong>
              <?php
                          break;
                        }
                        //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
                        else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
                        {
              ?>
                          <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                          break;
                        } //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
                        else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149 )
                        {
              ?>
                          <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                        }
                        //for equipments with subequipment but does not fall in $value
                        else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21  || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 || $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
                        {
                          if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
                          {
                            $patient_lift_sling_count++;
                            if($patient_lift_sling_count == 6)
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            }
                          }
                          else if($summary['equipmentID'] == 398)
                          {
                            if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
                            {
                              $high_low_full_electric_hospital_bed_count++;
                              if($high_low_full_electric_hospital_bed_count == 2){
              ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                              }
                            }
                          }
                          // Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
                          else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                          {
                            $equipment_count++;
                            if($equipment_count == 2){
              ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            }
                          }
                        }
                        else
                        {
                          if($summary['categoryID'] == 1)
                          {
                            $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                            if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
                            {
              ?>
                              <strong>
                                <a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                                  <?php echo $summary['key_desc']; ?>
                                </a>
                              </strong>
              <?php
                              break;
                            }
                            else if($non_capped_copy['noncapped_reference'] == 14)
                            {
              ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                            }
                            else
                            {

                            }
                          }
                          else
                          {
              ?>
                            <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                          }
                        }
                      }
                    }
                    else
                    {
              ?>
                      <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
              <?php
                    }
                  }
                endif; // End for the first condition for the session ID
              ?>
            </td>

            <!--QTY-->
            <td>
              <?php
                //quantity base on the categories
                //there are 3 categories
                //capped,non-capped,disposable
                $quantity = 1;
                if($summary['categoryID']!=3) //cappped=1, noncapped=2
                {
                  //if noncapped get children quantities
                  if($summary['categoryID']==2)
                  {
                    if($summary['parentID']==0 AND $summary['equipment_value']>0)
                    {
                      $quantity = $summary['equipment_value'];
                    }
                    else
                    {
                      if($summary['equipmentID'] == 4 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 30)
                      {
                        if(empty($summary['equipment_value']))
                        {
                          $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                          // $quantity = ($temp>0)? $temp : 1;
                          $quantity = $temp;
                        }
                        else
                        {
                          $quantity = $summary['equipment_value'];
                        }
                      }
                      else if($summary['equipmentID'] == 14)
                      {
                        if($summary['order_status'] == "confirmed")
                        {
                          $quantity = $summary['equipment_value'];
                        }
                        else
                        {
                          $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                          $quantity = $temp;
                        }
                      }
                      else
                      {
                        $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                        // $quantity = ($temp>0)? $temp : 1;
                        $quantity = $temp;
                      }
                    }
                  }
                  else //capped items
                  {
                    $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                    //if the equipment is miscellaneous capped item
                    if($summary['equipmentID'] == 313 || $summary['equipmentID'] == 206)
                    {
                      $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                      // $quantity = ($temp>0)? $temp : 1;
                      $quantity = $temp;
                    }
                    else if($non_capped_copy['noncapped_reference'] == 14)
                    {
                      $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                      // $quantity = ($temp>0)? $temp : 1;
                      $quantity = $temp;
                    }
                    else
                    {
                      $quantity = ($summary['equipment_value']>0)? $summary['equipment_value'] : 1;
                    }
                  }
                }
                else //disposable items
                {
                  if($summary['equipment_value'] > 1)
                  {
                    $quantity = $summary['equipment_value'];
                  }
                  else
                  {
                    $quantity = (get_disposable_quantity($summary['equipmentID'], $summary['uniqueID'])>0)? get_disposable_quantity($summary['equipmentID'], $summary['uniqueID']) : $summary['equipment_value'];
                    if($summary['equipment_value'] == 0)
                    {
                      $quantity = 0;
                    }

                    if($summary['equipment_value'] == "" && $summary['equipment_value'] != 0)
                    {
                      $quantity = get_disposable_quantity($summary['equipmentID'],$summary['uniqueID']);
                      if(quantity == "" && quantity != 0)
                      {
                        $quantity = 1;
                      }
                    }
                  }
                }
              ?>
              <a
                class="editable <?php echo $enableclick; ?> editable-text"
                href="javascript:;"
                id="equipment_value"
                data-type="text"
                data-pk="<?php echo $summary['equipmentID'];?>_SEPERATOR_<?php echo $summary['uniqueID'];?>"
                data-url="<?php echo base_url('order/update_quantity'); ?>"
                data-title="Enter Quantity"
                data-value="<?php echo $quantity;  ?>"
              >
                <?php echo $quantity; ?>
              </a>ea
            </td>

            <!--serial #-->
            <td class="item_serial_number">
              <?php if(combine_name(array($summary['serial_num'],$summary['lot_num']))!=""): ?>
                <p>
                  <a
                    class="editable <?php echo $enableclick; ?> editable-text"
                    href="javascript:;"
                    id="serial_num"
                    data-type="text"
                    data-pk="<?php echo $summary['orderID'];?>"
                    data-url="<?php echo base_url('order/update_data/text/orderID'); ?>"
                    data-title="Enter Serial"
                    data-value="<?php echo combine_name(array($summary['serial_num'],$summary['lot_num'])) ?>"
                  >
                    <?php
                      $serial_lot_no = combine_name(array($summary['serial_num'],$summary['lot_num']));
                      $separated_serial_lot_no = explode(",",$serial_lot_no);
                      if(count($separated_serial_lot_no) > 1)
                      {
                        echo $separated_serial_lot_no[0];
                      }
                      else
                      {
                        echo $serial_lot_no;
                      }
                    ?>
                  </a>
                  <?php
                    if(count($separated_serial_lot_no) > 1)
                    {
                  ?>
                      <a
                        href="javascript:;"
                        rel="popover"
                        data-toggle="popover"
                        title="Oxygen Cylinder Lot #"
                        data-trigger="hover"
                        data-placement="bottom"
                        data-content="<?php echo $serial_lot_no; ?>"
                      >
                          <i class="fa fa-caret-down all_lot_no_show"></i>
                      </a>
                  <?php
                    }
                  ?>
                  &nbsp;&nbsp;
                </p>
              <?php endif; ?>
            </td>

            <!-- PICKUP WORKORDER NUMBER-->
            <td>
              <?php
                $pickdate = "";
                $pickdate_val = "";
                // echo $summary['summary_pickup_date'];
                if($summary['summary_pickup_date']!="" && $summary['activity_typeid'] == 2)
                {
                  $pickdate = date("m/d/Y", strtotime($summary['summary_pickup_date']));
                  $pickdate_val = date("Y-m-d", strtotime($pickdate));

                  if($summary['uniqueID_reference'])
                  {
                    $work_order_no = substr($summary['uniqueID_reference'],4,10);
                    $work_order_val = $summary['uniqueID_reference'];
                  }
                  else
                  {
                    $work_order_no = substr($summary['pickedup_uniqueID'],4,10);
                    $work_order_val = $summary['pickedup_uniqueID'];
                  }
                  if($summary['uniqueID_reference'] != 0)
                  {
                    $data_act_id = 3;
                  }
                  else
                  {
                    $data_act_id = $summary['activity_typeid'];
                  }
                  $work_order_no_a = "<a
                                        href='javascript:;'
                                        class='view_order_information'
                                        rel='popover'
                                        data-toggle='popover'
                                        title=''
                                        data-trigger='hover'
                                        data-placement='top'
                                        data-html='true'
                                        data-container='body'
                                        data-content='Click to view the details'
                                        style='cursor:pointer'

                                        data-id='".$summary['medical_record_id']."'
                                        data-value='".$summary['organization_id']."'
                                        data-unique-id='".$work_order_val."'
                                        data-act-id='".$data_act_id."'
                                        data-equip-id='".$summary['equipmentID']."'
                                        data-patient-id='".$summary['patientID']."'
                                        data-activity-reference-id='".$data_act_id."'
                                        >
                                        ".$work_order_no."
                                        </a>
                                        ";
                  echo $work_order_no_a;
                }
              ?>
            </td>

            <!-- PICKUP DATE-->
            <td>
              <?php
                //pickup date functionalities
                $pickdate = "";
                $pickdate_val = "";
                if($summary['summary_pickup_date'] != '0000-00-00' AND $summary['summary_pickup_date']!="")
                {
                  $pickdate = date("m/d/Y", strtotime($summary['summary_pickup_date']));
                  $pickdate_val = date("Y-m-d", strtotime($pickdate));
                }
                else if($summary['summary_pickup_date'] != '0000-00-00' && $summary['activity_reference'] == 2 && $summary['original_activity_typeid'] == 5)
                {
                  $order_info = get_respite_order_info($summary['uniqueID_reference'],$summary['equipmentID']);
                  $pickdate = date("m/d/Y", strtotime($order_info['summary_pickup_date']));
                  $pickdate_val = date("Y-m-d", strtotime($pickdate));
                }
                //getting activity type id depending
                // on the activity type
                // cus move and exchange need to be an original activity
                // type

                $get_type = "activity_typeid";
                $allowed_acttypes = array(3,5);
                if(in_array($summary['activity_reference'], $allowed_acttypes) OR in_array($summary['activity_typeid'], $allowed_acttypes))
                {
                  $get_type = "activity_reference";
                }
              ?>
              <a
                id="summary_pickup_date"
                data-pk="<?php echo $summary['orderID'] ?>"
                data-url="<?php echo base_url('order/update_data/date/orderID/0'); ?>"
                data-title="Enter date"
                data-value="<?php echo $pickdate_val; ?>"
                data-type="combodate"
                data-maxYear="<?php echo date("Y"); ?>"
                data-format="YYYY-MM-DD"
                data-viewformat="MM/DD/YYYY"
                data-template="MMM / D / YYYY"
                href="javascript:void(0)" class="data_tooltip editable <?php echo $enableclick; ?> editable-combodate-notrequired text-danger text-bold"
              >
                <?php echo $pickdate; ?>
              </a>
            </td>

            <!-- ITEM CATEGORY -->
            <td>
              <?php
                //setting styles for each category
                /*
                | @label-info = Capped Items
                | @label-warning = Non Capped Items
                | @label-success = Disposable
                |
                */
                $types = array(
                              "Capped Item"     => "label-info",
                              "Non-Capped Item" => "label-warning"
                          );
                $capped_type = "";
                $font_size = "";
                if($summary['type'] == "Capped Item")
                {
                  $capped_type = "C";
                  $style = "font-size:12px;display: block;width: 22px;height: 22px;padding-top: 5px;";
                }
                else if($summary['type'] == "Disposable Items")
                {
                  $capped_type = "D";
                  $style = "font-size:12px;display: block;width: 22px;height: 22px;padding-top: 5px;";
                }
                else
                {
                  $capped_type = "NC";
                  $style = "font-size: 11px;line-height: 2;text-align: center;padding: 0;display: block;height: 22px;width: 22px;";
                }
              ?>
              <p
                style="<?php echo $style; ?>"
                rel="popover"
                data-html="true"
                data-toggle="popover"
                data-trigger="hover"
                data-placement="left"
                data-content="<?php echo "<b>".$summary['type']."</b>"; ?>"
                class="label <?php echo isset($types[$summary['type']])? $types[$summary['type']] : "label-success" ?>"
              >
                  <?php echo $capped_type; ?>
              </p>
            </td>

            <!-- EQUIPMENT LOCATION -->
            <td>
              <?php
                $cont = array(
                              $summary['street'],
                              $summary['placenum'],
                              $summary['city'],
                              $summary['state'],
                              $summary['postal']
                            );
              ?>
              <a
                href="javascript:;"
                rel="popover"
                data-toggle="popover"
                title="Equipment Location"
                data-trigger="hover"
                data-placement="left"
                data-content="<?php echo combine_name($cont,','); ?>"
              >
                <i class="fa fa-map-marker text-danger"></i>
              </a>
            </td>

          </tr>
<?php
        }
        else
        {
          $packaged_item_sign = 1;
          if($summary['equipmentID'] == 486 || $summary['equipmentID'] == 163 || $summary['equipmentID'] == 164)
          {
            $packaged_items_list[0][] = $summary;
          }
          else if($summary['equipmentID'] == 68 || $summary['equipmentID'] == 159 || $summary['equipmentID'] == 160 || $summary['equipmentID'] == 161 || $summary['equipmentID'] ==162)
          {
            $packaged_items_list[1][] = $summary;
          }
          else if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343 || $summary['equipmentID'] == 466)
          {
            $packaged_items_list[2][] = $summary;
          }
          else if($summary['equipmentID'] == 36 || $summary['equipmentID'] == 466 || $summary['equipmentID'] == 178)
          {
            $packaged_items_list[3][] = $summary;
          }
          else if($summary['equipmentID'] == 422 || $summary['equipmentID'] == 259)
          {
            $packaged_items_list[4][] = $summary;
          }
          else if($summary['equipmentID'] == 415 || $summary['equipmentID'] == 259)
          {
            $packaged_items_list[5][] = $summary;
          }
          else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 490 || $summary['equipmentID'] ==492)
          {
            $packaged_items_list[6][] = $summary;
          }
          else if($summary['equipmentID'] == 67 || $summary['equipmentID'] == 157)
          {
            $packaged_items_list[7][] = $summary;
          }
        }
      }
    }
    if(!empty($packaged_items_list))
    {
      foreach($packaged_items_list as $new_item_list)
      {
        foreach ($new_item_list as  $summary)
        {
          if($summary['canceled_order'] == 0 && $summary['canceled_from_confirming'] == 0)
          {
?>
            <tr data-id="Sample data-id" style="<?php if($summary['parentID'] != 0) echo 'display:none' ?>">
              <input type="hidden" name="hdn_act_id_for_checking[]" id="act_id_checking" value="<?php echo $summary['activity_typeid'] ?>" />
              <input type="hidden" name="hdn_unique_id" value="<?php echo $summary['uniqueID'] ?>" class="hdn_unique_id" />
              <input type="hidden" name="hdn_medical_record_id" value="<?php echo $summary['medical_record_id'] ?>" class="hdn_equip_id" />

              <!--DELIVERY DATE-->
              <td style="width:auto">
                <!-- order date of the items regardless of its activities -->
                <?php
                  if($summary['pickup_date'] != '0000-00-00' && $summary['pickup_date'] != '')
                  {
                    if($summary['order_status'] != "confirmed")
                    {
                ?>
                      <a
                        id="pickup_date"
                        data-pk="<?php echo $summary['uniqueID'] ?>"
                        data-url="<?php echo base_url('order/update_data/date/uniqueID'); ?>"
                        data-title="Enter date"
                        data-value="<?php echo date("Y-m-d", strtotime($summary['pickup_date'])) ?>"
                        data-type="combodate"
                        data-maxYear="<?php echo date("Y"); ?>"
                        data-format="YYYY-MM-DD"
                        data-viewformat="MM/DD/YYYY"
                        data-template="MMM / D / YYYY"
                        href="javascript:void(0)" class="data_tooltip editable <?php echo $enableclick; ?> editable-combodate text-success text-bold"
                      >
                        <?php echo date("m/d/Y", strtotime($summary['pickup_date'])) ?>
                      </a>
                <?php
                    }
                    else
                    {
                ?>
                      <a
                        id="actual_order_date"
                        data-pk="<?php echo $summary['uniqueID'] ?>"
                        data-url="<?php echo base_url('order/update_data/date/uniqueID'); ?>"
                        data-title="Enter date"
                        data-value="<?php echo date("Y-m-d", strtotime($summary['actual_order_date'])) ?>"
                        data-type="combodate"
                        data-maxYear="<?php echo date("Y"); ?>"
                        data-format="YYYY-MM-DD"
                        data-viewformat="MM/DD/YYYY"
                        data-template="MMM / D / YYYY"
                        href="javascript:void(0)" class="data_tooltip editable <?php echo $enableclick; ?> editable-combodate text-success text-bold"
                      >
                        <?php echo date("m/d/Y", strtotime($summary['actual_order_date'])) ?>
                      </a>
                <?php
                    }
                  }
                ?>
              </td>

              <!--WORK ORDER #-->
              <td style="width:90px;">
                <?php
                  //getting activity type id depending
                  // on the activity type
                  // cus move and exchange need to be an original activity
                  // type

                  $get_type = "activity_typeid";
                  $allowed_acttypes = array(3,4);
                  if(in_array($summary['original_activity_typeid'], $allowed_acttypes))
                  {
                      $get_type = "original_activity_typeid";
                  }
                ?>

                <a href="javascript:;"
                  class="view_original_order_information"
                  rel="popover"
                  data-toggle="popover"
                  title=""
                  data-trigger="hover"
                  data-placement="top"
                  data-html="true"
                  data-container="body"
                  data-content="Click to view the details"
                  style="cursor:pointer"
                  data-id="<?php echo $summary['medical_record_id'] ?>"
                  data-value="<?php echo $summary['organization_id'] ?>"
                  data-unique-id="<?php echo $summary['uniqueID'] ?>"
                  data-act-id="<?php echo $summary[$get_type] ?>"
                  data-patient-id="<?php echo $summary['patientID'] ?>"
                >
                  <?php echo substr($summary['uniqueID'],4,10); ?>
                </a>
              </td>

              <!--ACTIVITY TYPE-->
              <td class="activity_type_column">
                <?php
                  $activity_type_display = "";
                  $address_sequence = 0;
                  $address_count = 1;
                  if($summary['activity_name'] == "Delivery")
                  {
                    if(($summary['address_type']) == 0)
                    {
                      $activity_type_display = "Delivery";
                    }
                    else if($summary['address_type'] == 1)
                    {
                      $address_sequence = (isset($ptmove_addresses_ID[$summary['addressID']]))?  $ptmove_addresses_ID[$summary['addressID']]['sequence'] : 1;
                      if($address_sequence == 1)
                      {
                        $address_sequence = "";
                      }
                      $activity_type_display = "Delivery ( CUS Move ".$address_sequence.")";
                    }
                    else
                    {
                      $address_sequence = (isset($respite_addresses_ID[$summary['addressID']]))?  $respite_addresses_ID[$summary['addressID']]['sequence'] : 1;
                      if($address_sequence == 1)
                      {
                        $address_sequence = "";
                      }
                      $activity_type_display = "Delivery ( Respite ".$address_sequence.")";
                    }
                  }
                  else if($summary['activity_name'] == "Exchange")
                  {
                    if($summary['address_type'] == '2')
                    {
                      $address_sequence = (isset($respite_addresses_ID[$summary['addressID']]))?  $respite_addresses_ID[$summary['addressID']]['sequence'] : 1;
                      if($address_sequence == 1)
                      {
                        $address_sequence = "";
                      }
                      $activity_type_display = "Exchange ( Respite ".$address_sequence.")";
                    }
                    else if($summary['address_type'] == '1')
                    {
                      $address_sequence = (isset($ptmove_addresses_ID[$summary['addressID']]))?  $ptmove_addresses_ID[$summary['addressID']]['sequence'] : 1;
                      if($address_sequence == 1)
                      {
                        $address_sequence = "";
                      }
                      $activity_type_display = "Exchange ( CUS Move ".$address_sequence.")";
                    }
                    else
                    {
                      $activity_type_display = "Exchange";
                    }
                  }
                  else if($summary['activity_name'] == "CUS Move")
                  {
                    $address_sequence = (isset($ptmove_addresses_ID[$summary['addressID']]))?  $ptmove_addresses_ID[$summary['addressID']]['sequence'] : 1;
                    if($address_sequence == 1)
                    {
                      $address_sequence = "";
                    }
                    $activity_type_display = "CUS Move ".$address_sequence;
                  }
                  else if($summary['activity_name'] == "Respite")
                  {
                    $address_sequence = (isset($respite_addresses_ID[$summary['addressID']]))?  $respite_addresses_ID[$summary['addressID']]['sequence'] : 1;
                    if($address_sequence == 1)
                    {
                      $address_sequence = "";
                    }
                    $activity_type_display = "Respite".$address_sequence;
                  }
                  else if($summary['activity_name'] == "Pickup")
                  {
                    if(($summary['address_type']) == 0)
                    {
                      $activity_type_display = "Pickup";
                    }
                    else if($summary['address_type'] == 1)
                    {
                      $address_sequence = (isset($ptmove_addresses_ID[$summary['addressID']]))?  $ptmove_addresses_ID[$summary['addressID']]['sequence'] : 1;
                      if($address_sequence == 1)
                      {
                        $address_sequence = "";
                      }
                      $activity_type_display = "Pickup ( CUS Move ".$address_sequence.")";
                    }
                    else
                    {
                      $address_sequence = (isset($respite_addresses_ID[$summary['addressID']]))?  $respite_addresses_ID[$summary['addressID']]['sequence'] : 1;
                      if($address_sequence == 1)
                      {
                        $address_sequence = "";
                      }
                      $activity_type_display = "Pickup ( Respite ".$address_sequence.")";
                    }
                  }

                  echo $activity_type_display;
                ?>
              </td>

              <!--ITEM #-->
              <td class="hide_on_print">
                <?php
                  if($summary['equipment_company_item_no'] != "0")
                  {
                    echo $summary['equipment_company_item_no'];
                  }
                  else
                  {
                    $subequipments = get_subequipment_id_v2($summary['equipmentID']);
                    foreach ($subequipments as $key) {
                      if($key['equipment_company_item_no'] != "0")
                      {
                        $used_subequipment = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
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
                  if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :
                    // Oxygen Concentrator
                    if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343) :
                ?>
                      <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-differ="<?php echo $summary['orderID']; ?>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                    else:
                      //*** DELIVERY not CONFIRMED yet dri mo display ang item ***//
                      //check if naay sub equipment using equipment id, work uniqueId
                      $subequipment_id = get_subequipment_id($summary['equipmentID']);

                      //gets all the id's under the order
                      if($subequipment_id)
                      {
                        $count = 0;
                        $patient_lift_sling_count = 0;
                        $high_low_full_electric_hospital_bed_count = 0;
                        $equipment_count = 0;
                        $my_count_sign = 0;
                        $my_first_sign = 0;
                        $my_second_sign = 0;

                        foreach ($subequipment_id as $key) {
                          $value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);

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
                            //full electric hospital bed equipment
                            if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20){
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?></a></strong>
                <?php
                            }
                            //Hi-Low Full Electric Hospital Bed equipment
                            else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?></a></strong>
                <?php
                            }
                            //Patient Lift with Sling
                            else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a></strong>
                <?php
                            }
                            //Patient Lift Electric with Sling
                            else if($summary['equipmentID'] == 353)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a></strong>
                <?php
                            }
                            //Patient Lift Sling
                            else if($summary['equipmentID'] == 196)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $key['key_desc']; ?></a></strong>
                <?php
                            }
                            //(54 & 17) Geri Chair || (66 & 39) Shower Chair
                            else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']." ".$key['key_desc'].""; ?></a></strong>
                <?php
                            }
                            // Oxygen E Portable System && Oxygen Liquid Portable
                            else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              break;
                            }
                            //Oxygen Cylinder Rack
                            else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">Oxygen  <?php echo $key['key_desc']; ?></a></strong>
                <?php
                              break;
                            }
                            //(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
                            else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
                            {
                              if($my_count_sign == 0)
                              {
                                //wheelchair & wheelchair reclining
                                if($count == 1)
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                <?php
                                  if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
                                  {
                                    $key['key_desc'] = '16" Narrow';
                                  }
                                  else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
                                  {
                                    $key['key_desc'] = '18" Standard';
                                  }
                                  else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
                                  {
                                    $key['key_desc'] = '20" Wide';
                                  }
                                  else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
                                  {
                                    $key['key_desc'] = '22" Extra Wide';
                                  }
                                  else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
                                  {
                                    $key['key_desc'] = '24" Bariatric';
                                  }
                                  echo $key['key_desc']." ".$summary['key_desc'];
                                }
                                else
                                {
                                  echo " With ".$key['key_desc']." </a></strong>";
                                }
                              }
                              else
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"> 20" Wide
                <?php
                                echo $summary['key_desc'];
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
                            else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                              <?php
                                if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
                                {
                                  $key['key_desc'] = '17" Narrow';
                                }
                                else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
                                {
                                  $key['key_desc'] = '19" Standard';
                                }
                                echo $key['key_desc']." ".$summary['key_desc']." </a></strong>";
                            }
                            else if($summary['equipmentID'] == 30)
                            {
                              if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
                              {
                                if($count == 3)
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-differ="<?php echo $summary['orderID']; ?>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?>
                <?php
                                  echo " With ".$key['key_desc']." </a></strong>";
                                }
                              }
                              else
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              }
                            }
                            //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
                            else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
                            {
                ?>
                              <strong>
                                <a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                                  <?php echo $summary['key_desc']; ?>
                                </a>
                                <br />
                                <?php
                                  $samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
                                  if(strlen($samp) > 30)
                                  {
                                    echo "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
                                  }
                                  else
                                  {
                                    echo "<span style='font-weight:400;color:#000;'>".$samp."</span>";
                                  }

                                ?>
                              </strong>
                <?php
                              break;
                            }
                            else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                            {
                              $samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
                              if($count == 1)
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']; ?> <?php echo $samp_conserving_device; ?> </a></strong>
                <?php
                              }
                            }
                            else if($summary['equipmentID'] == 282)
                            {

                            }
                            //equipments that has no subequipment but gets inside the $value if statement
                            else if($summary['equipmentID'] == 14)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                            }
                            else
                            {
                              if($summary['categoryID'] == 1)
                              {
                                $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                                  break;
                                }
                                else if($non_capped_copy['noncapped_reference'] == 14)
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                                }
                                else if($non_capped_copy['noncapped_reference'] == 282)
                                {
                                  $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                ?>
                                  <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?></a></strong>
                <?php
                                  break;
                                }
                                else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a></strong>
                <?php
                                }
                                else if($non_capped_copy['noncapped_reference'] == 353)
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a></strong>
                <?php
                                }
                                else
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                                }
                              }
                              else
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              }
                            }
                          } //end of $value
                          //for Oxygen E cylinder do not remove as it will cause errors
                          else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                          {
                            break;
                          }
                          else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                          {


                          }
                          else if($summary['equipmentID'] == 282)
                          {
                            $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                ?>
                            <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?></a></strong>
                <?php
                            break;
                          }
                          //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
                          else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
                          {
                ?>
                            <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                            break;
                          } //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
                          else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
                          {
                ?>
                            <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                          }
                          //for equipments with subequipment but does not fall in $value
                          else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
                          {
                            if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
                            {
                              $patient_lift_sling_count++;
                              if($patient_lift_sling_count == 6)
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              }
                            }
                            else if($summary['equipmentID'] == 398)
                            {
                              if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
                              {
                                $high_low_full_electric_hospital_bed_count++;
                                if($high_low_full_electric_hospital_bed_count == 2){
                ?>
                                  <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                                }
                              }
                            }
                            // Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
                            else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                            {
                              $equipment_count++;
                              if($equipment_count == 2){
                ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              }
                            }
                          }
                          else
                          {
                            if($summary['categoryID'] == 1)
                            {
                              $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                              if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
                              {
                ?>
                                  <strong>
                                    <a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                                      <?php echo $summary['key_desc']; ?>
                                    </a>
                                  </strong>
                <?php
                                break;
                              }
                              else if($non_capped_copy['noncapped_reference'] == 14)
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              }
                              else
                              {

                              }
                            }
                            else
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                            }
                          }
                        }
                      }
                      else
                      {
                        if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
                        {
                ?>
                          <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"> <?php echo $summary['key_desc'] ?></a> <i class='fa fa-pencil edit_patient_weight' style="cursor:pointer;font-size:10px;" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"></i></strong>
                <?php
                        }
                        else
                        {
                ?>
                          <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                        }
                      }
                    endif;
                  else:
                    if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
                    {
                ?>
                      <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-differ="<?php echo $summary['orderID']; ?>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                    }
                    else
                    {
                      //check if naay sub equipment using equipment id
                      $subequipment_id = get_subequipment_id($summary['equipmentID']);
                      //gets all the id's under the order
                      if($subequipment_id)
                      {
                        $count = 0;
                        $patient_lift_sling_count = 0;
                        $high_low_full_electric_hospital_bed_count = 0;
                        $equipment_count = 0;
                        $my_count_sign = 0;
                        $my_first_sign = 0;
                        $my_second_sign = 0;
                        foreach ($subequipment_id as $key) {
                          $value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
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
                            //equipment full electric hospital bed
                            if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
                            {
                ?>
                              <strong><a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equip_edit_options equipment_options_hover show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>"><?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?></a></strong>
                <?php
                            }
                            //Hi-Low Full Electric Hospital Bed equipment
                            else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']." With ".$key['key_desc'].""; ?></a></strong>
                <?php
                            }
                            //Patient Lift with Sling
                            else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift With ".$key['key_desc'].""; ?></a></strong>
                <?php
                            }
                            //Patient Lift Electric with Sling
                            else if($summary['equipmentID'] == 353)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a></strong>
                <?php
                            }
                            //Patient Lift Sling
                            else if($summary['equipmentID'] == 196)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $key['key_desc']; ?></a></strong>
                <?php
                            }
                            //(54 & 17) Geri Chair || (66 & 39) Shower Chair
                            else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
                            {
                ?>
                              <strong><a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']." ".$key['key_desc'].""; ?></a></strong>
                <?php
                            }
                            // Oxygen E Portable System && Oxygen Liquid Portable
                            else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              break;
                            }
                            //Oxygen Cylinder Rack
                            else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">Oxygen  <?php echo $key['key_desc']; ?></a></strong>
                <?php
                              break;
                            }
                            //(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
                            else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
                            {
                              if($my_count_sign == 0)
                              {
                                //wheelchair & wheelchair reclining
                                if($count == 1)
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                <?php
                                  if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
                                  {
                                    $key['key_desc'] = '16" Narrow';
                                  }
                                  else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
                                  {
                                    $key['key_desc'] = '18" Standard';
                                  }
                                  else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126)
                                  {
                                    $key['key_desc'] = '20" Wide';
                                  }
                                  else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
                                  {
                                    $key['key_desc'] = '22" Extra Wide';
                                  }
                                  else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
                                  {
                                    $key['key_desc'] = '24" Bariatric';
                                  }
                                  echo $key['key_desc']." ".$summary['key_desc'];
                                }
                                else
                                {
                                  echo " With ".$key['key_desc']." </a></strong>";
                                }
                              }
                              else
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"> 20" Wide
                <?php
                                echo $summary['key_desc'];
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
                            else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                              <?php
                                if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
                                {
                                  $key['key_desc'] = '17" Narrow';
                                }
                                else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
                                {
                                  $key['key_desc'] = '19" Standard';
                                }
                                echo $key['key_desc']." ".$summary['key_desc']." </a></strong>";
                              ?>
                <?php
                            }
                            else if($summary['equipmentID'] == 30)
                            {
                              if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
                              {
                                if($count == 3)
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-differ="<?php echo $summary['orderID']; ?>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?>
                <?php
                                  echo " With ".$key['key_desc']." </a></strong>";
                                }
                              }
                              else
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              }
                            }
                            //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
                            else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
                            {
                ?>
                              <strong>
                                <a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                                  <?php echo $summary['key_desc'] ?>
                                </a>
                                <br />
                                <?php
                                  $samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
                                  if(strlen($samp) > 30)
                                  {
                                    echo "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
                                  }
                                  else
                                  {
                                    echo "<span style='font-weight:400;color:#000;'>".$samp."</span>";
                                  }

                                ?>
                              </strong>
                <?php
                              break;
                            }
                            else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                            {
                              $samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
                              if($count == 1)
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc']; ?> <?php echo $samp_conserving_device; ?> </a></strong>
                <?php
                              }
                            }
                            else if($summary['equipmentID'] == 282)
                            {

                            }
                            else if($summary['equipmentID'] == 14)
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                            }
                            else
                            {
                              if($summary['categoryID'] == 1)
                              {
                                $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                                if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                                  break;
                                }
                                else if($non_capped_copy['noncapped_reference'] == 14)
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                                }
                                else if($non_capped_copy['noncapped_reference'] == 282)
                                {
                                  $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                ?>
                                  <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?>  With <?php echo $samp_hospital_bed_extra_long;?></a></strong>
                <?php
                                  break;
                                }
                                else if($non_capped_copy['noncapped_reference'] == 353)
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo "Patient Lift Electric With ".$key['key_desc'].""; ?></a></strong>
                <?php
                                }
                                else
                                {
                ?>
                                  <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                                }
                              }
                              else
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              }
                            }
                          }
                          //for Oxygen E cylinder do not remove as it will cause errors
                          else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
                          {
                            break;
                          }
                          else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
                          {

                          }
                          else if($summary['equipmentID'] == 282)
                          {
                            $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
                ?>
                            <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?> With <?php echo $samp_hospital_bed_extra_long;?></a></strong>
                <?php
                            break;
                          }
                          //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
                          else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
                          {
                ?>
                            <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                            break;
                          } //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
                          else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149 )
                          {
                ?>
                            <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                          }
                          //for equipments with subequipment but does not fall in $value
                          else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21  || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 || $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
                          {
                            if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
                            {
                              $patient_lift_sling_count++;
                              if($patient_lift_sling_count == 6)
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              }
                            }
                            else if($summary['equipmentID'] == 398)
                            {
                              if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
                              {
                                $high_low_full_electric_hospital_bed_count++;
                                if($high_low_full_electric_hospital_bed_count == 2){
                ?>
                                  <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                                }
                              }
                            }
                            // Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
                            else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
                            {
                              $equipment_count++;
                              if($equipment_count == 2){
                ?>
                                <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              }
                            }
                          }
                          else
                          {
                            if($summary['categoryID'] == 1)
                            {
                              $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                              if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
                              {
                ?>
                                <strong>
                                  <a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>">
                                    <?php echo $summary['key_desc']; ?>
                                  </a>
                                </strong>
                <?php
                                break;
                              }
                              else if($non_capped_copy['noncapped_reference'] == 14)
                              {
                ?>
                                <strong><a href="javascript:void(0)" class="edit_item_options equip_edit_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                              }
                              else
                              {

                              }
                            }
                            else
                            {
                ?>
                              <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                            }
                          }
                        }
                      }
                      else
                      {
                ?>
                        <strong><a href="javascript:void(0)" class="equipment_options_tooltip equip_edit_options equipment_options_hover edit_item_options equip_edit_options show_on_print" rel="popover" data-toggle="popover" data-trigger="hover" data-toggle="popover" data-html="true" data-content="<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                <?php
                      }
                    }
                  endif; // End for the first condition for the session ID
                ?>
              </td>

              <!--QTY-->
              <td>
                <?php
                  //quantity base on the categories
                  //there are 3 categories
                  // capped,non-capped,disposable
                  $quantity = 1;
                  if($summary['categoryID']!=3) //cappped=1, noncapped=2
                  {
                    //if noncapped get children quantities
                    if($summary['categoryID']==2)
                    {
                      if($summary['parentID']==0 AND $summary['equipment_value']>0)
                      {
                        $quantity = $summary['equipment_value'];
                      }
                      else
                      {
                        if($summary['equipmentID'] == 4 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 30)
                        {
                          if(empty($summary['equipment_value']))
                          {
                            $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                            // $quantity = ($temp>0)? $temp : 1;
                            $quantity = $temp;
                          }
                          else
                          {
                            $quantity = $summary['equipment_value'];
                          }
                        }
                        else if($summary['equipmentID'] == 14)
                        {
                          if($summary['order_status'] == "confirmed")
                          {
                            $quantity = $summary['equipment_value'];
                          }
                          else
                          {
                            $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                            $quantity = $temp;
                          }
                        }
                        else
                        {
                          $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                          // $quantity = ($temp>0)? $temp : 1;
                          $quantity = $temp;
                        }
                      }
                    }
                    else //capped items
                    {
                      $non_capped_copy = get_non_capped_copy($summary['equipmentID']);
                      //if the equipment is miscellaneous capped item
                      if($summary['equipmentID'] == 313 || $summary['equipmentID'] == 206)
                      {
                        $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                        // $quantity = ($temp>0)? $temp : 1;
                        $quantity = $temp;
                      }
                      else if($non_capped_copy['noncapped_reference'] == 14)
                      {
                        $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                        // $quantity = ($temp>0)? $temp : 1;
                        $quantity = $temp;
                      }
                      else
                      {
                        // $quantity = ($summary['equipment_value']>0)? $summary['equipment_value'] : 1;
                        $quantity = $summary['equipment_value'];
                      }
                    }
                  }
                  else //disposable items
                  {
                    if($summary['equipment_value'] > 1)
                    {
                      $quantity = $summary['equipment_value'];
                    }
                    else
                    {
                      $quantity = (get_disposable_quantity($summary['equipmentID'], $summary['uniqueID'])>0)? get_disposable_quantity($summary['equipmentID'], $summary['uniqueID']) : $summary['equipment_value'];
                      if($summary['equipment_value'] == 0)
                      {
                        $quantity = 0;
                      }

                      if($summary['equipment_value'] == "" && $summary['equipment_value'] != 0)
                      {
                        $quantity = get_disposable_quantity($summary['equipmentID'],$summary['uniqueID']);
                        if(quantity == "" && quantity != 0)
                        {
                          $quantity = 1;
                        }
                      }
                    }
                  }
                ?>
                <a
                  class="editable <?php echo $enableclick; ?> editable-text"
                  href="javascript:;"
                  id="equipment_value"
                  data-type="text"
                  data-pk="<?php echo $summary['equipmentID'];?>_SEPERATOR_<?php echo $summary['uniqueID'];?>"
                  data-url="<?php echo base_url('order/update_quantity'); ?>"
                  data-title="Enter Quantity"
                  data-value="<?php echo $quantity;  ?>"
                >
                  <?php echo $quantity; ?>
                </a>ea
              </td>

              <!--serial #-->
              <td class="item_serial_number">
                <?php if(combine_name(array($summary['serial_num'],$summary['lot_num']))!=""): ?>
                  <p>
                    <a
                      class="editable <?php echo $enableclick; ?> editable-text"
                      href="javascript:;"
                      id="serial_num"
                      data-type="text"
                      data-pk="<?php echo $summary['orderID'];?>"
                      data-url="<?php echo base_url('order/update_data/text/orderID'); ?>"
                      data-title="Enter Serial"
                      data-value="<?php echo combine_name(array($summary['serial_num'],$summary['lot_num'])) ?>"
                    >
                      <?php
                        $serial_lot_no = combine_name(array($summary['serial_num'],$summary['lot_num']));
                        $separated_serial_lot_no = explode(",",$serial_lot_no);
                        if(count($separated_serial_lot_no) > 1)
                        {
                          echo $separated_serial_lot_no[0];
                        }
                        else
                        {
                          echo $serial_lot_no;
                        }
                      ?>
                    </a>
                    <?php
                      if(count($separated_serial_lot_no) > 1)
                      {
                    ?>
                        <a
                          href="javascript:;"
                          rel="popover"
                          data-toggle="popover"
                          title="Oxygen Cylinder Lot #"
                          data-trigger="hover"
                          data-placement="bottom"
                          data-content="<?php echo $serial_lot_no; ?>"
                        >
                            <i class="fa fa-caret-down all_lot_no_show"></i>
                        </a>
                    <?php
                      }
                    ?>
                    &nbsp;&nbsp;
                  </p>
                <?php endif; ?>
              </td>

              <!-- PICKUP WORKORDER NUMBER-->
              <td>
                <?php
                  $pickdate = "";
                  $pickdate_val = "";
                  // echo $summary['summary_pickup_date'];
                  if($summary['summary_pickup_date']!="" && $summary['activity_typeid'] == 2)
                  {
                    $pickdate = date("m/d/Y", strtotime($summary['summary_pickup_date']));
                    $pickdate_val = date("Y-m-d", strtotime($pickdate));

                    if($summary['uniqueID_reference'])
                    {
                      $work_order_no = substr($summary['uniqueID_reference'],4,10);
                      $work_order_val = $summary['uniqueID_reference'];
                    }
                    else
                    {
                      $work_order_no = substr($summary['pickedup_uniqueID'],4,10);
                      $work_order_val = $summary['pickedup_uniqueID'];
                    }
                    if($summary['uniqueID_reference'] != 0)
                    {
                      $data_act_id = 3;
                    }
                    else
                    {
                      $data_act_id = $summary['activity_typeid'];
                    }
                    $work_order_no_a = "<a
                                          href='javascript:;'
                                          class='view_order_information'
                                          rel='popover'
                                          data-toggle='popover'
                                          title=''
                                          data-trigger='hover'
                                          data-placement='top'
                                          data-html='true'
                                          data-container='body'
                                          data-content='Click to view the details'
                                          style='cursor:pointer'

                                          data-id='".$summary['medical_record_id']."'
                                          data-value='".$summary['organization_id']."'
                                          data-unique-id='".$work_order_val."'
                                          data-act-id='".$data_act_id."'
                                          data-equip-id='".$summary['equipmentID']."'
                                          data-patient-id='".$summary['patientID']."'
                                          data-activity-reference-id='".$data_act_id."'
                                          >
                                          ".$work_order_no."
                                          </a>
                                          ";
                    echo $work_order_no_a;
                  }
                ?>
              </td>

              <!-- PICKUP DATE-->
              <td>
                <?php
                  //pickup date functionalities
                  $pickdate = "";
                  $pickdate_val = "";
                  if($summary['summary_pickup_date'] != '0000-00-00' AND $summary['summary_pickup_date']!="")
                  {
                    $pickdate = date("m/d/Y", strtotime($summary['summary_pickup_date']));
                    $pickdate_val = date("Y-m-d", strtotime($pickdate));
                  }
                  else if($summary['summary_pickup_date'] != '0000-00-00' && $summary['activity_reference'] == 2 && $summary['original_activity_typeid'] == 5)
                  {
                    $order_info = get_respite_order_info($summary['uniqueID_reference'],$summary['equipmentID']);
                    $pickdate = date("m/d/Y", strtotime($order_info['summary_pickup_date']));
                    $pickdate_val = date("Y-m-d", strtotime($pickdate));
                  }
                  //getting activity type id depending
                  // on the activity type
                  // cus move and exchange need to be an original activity
                  // type

                  $get_type = "activity_typeid";
                  $allowed_acttypes = array(3,5);
                  if(in_array($summary['activity_reference'], $allowed_acttypes) OR in_array($summary['activity_typeid'], $allowed_acttypes))
                  {
                    $get_type = "activity_reference";
                  }
                ?>
                <a
                  id="summary_pickup_date"
                  data-pk="<?php echo $summary['orderID'] ?>"
                  data-url="<?php echo base_url('order/update_data/date/orderID/0'); ?>"
                  data-title="Enter date"
                  data-value="<?php echo $pickdate_val; ?>"
                  data-type="combodate"
                  data-maxYear="<?php echo date("Y"); ?>"
                  data-format="YYYY-MM-DD"
                  data-viewformat="MM/DD/YYYY"
                  data-template="MMM / D / YYYY"
                  href="javascript:void(0)" class="data_tooltip editable <?php echo $enableclick; ?> editable-combodate-notrequired text-danger text-bold"
                >
                  <?php echo $pickdate; ?>
                </a>
              </td>

              <!-- ITEM CATEGORY -->
              <td>
                <?php
                  //setting styles for each category
                  /*
                  | @label-info = Capped Items
                  | @label-warning = Non Capped Items
                  | @label-success = Disposable
                  |
                  */
                  $types = array(
                                "Capped Item"     => "label-info",
                                "Non-Capped Item" => "label-warning"
                            );
                  $capped_type = "";
                  $font_size = "";
                  if($summary['type'] == "Capped Item")
                  {
                    $capped_type = "C";
                    $style = "font-size:12px;display: block;width: 22px;height: 22px;padding-top: 5px;";
                  }
                  else if($summary['type'] == "Disposable Items")
                  {
                    $capped_type = "D";
                    $style = "font-size:12px;display: block;width: 22px;height: 22px;padding-top: 5px;";
                  }
                  else
                  {
                    $capped_type = "NC";
                    $style = "font-size: 11px;line-height: 2;text-align: center;padding: 0;display: block;height: 22px;width: 22px;";
                  }
                ?>
                <p
                  style="<?php echo $style; ?>"
                  rel="popover"
                  data-html="true"
                  data-toggle="popover"
                  data-trigger="hover"
                  data-placement="left"
                  data-content="<?php echo "<b>".$summary['type']."</b>"; ?>"
                  class="label <?php echo isset($types[$summary['type']])? $types[$summary['type']] : "label-success" ?>"
                >
                    <?php echo $capped_type; ?>
                </p>
              </td>

              <!-- EQUIPMENT LOCATION -->
              <td>
                <?php
                  $cont = array(
                                $summary['street'],
                                $summary['placenum'],
                                $summary['city'],
                                $summary['state'],
                                $summary['postal']
                              );
                ?>
                <a
                  href="javascript:;"
                  rel="popover"
                  data-toggle="popover"
                  title="Equipment Location"
                  data-trigger="hover"
                  data-placement="left"
                  data-content="<?php echo combine_name($cont,','); ?>"
                >
                  <i class="fa fa-map-marker text-danger"></i>
                </a>
              </td>

            </tr>
<?
          }
        }
      }
    }
  }
  else
  {
?>
    <tr>
      <td colspan="11"> This customer has no orders. </td>
    </tr>
<?php
  }
?>

<script type="text/javascript">
  $(document).ready(function(){

    $.fn.editable.defaults.mode = 'inline';
    $("body").find('[rel="popover"]').popover();

    $('.editable-click.editable-text').editable({
          emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          validate: function(value) {
              if($.trim(value) == '') {
                  return 'This field is required';
              }
          },
          success:function(response,newValue){
            console.log(response);
             if(response.error==1) return response.message;
             else window.location.reload();
          }
    });
    $('.editable-click.editable-selectable-duration').editable({
          emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          validate: function(value) {
              if($.trim(value) == '') {
                  return 'This field is required';
              }
          },
          success:function(response,newValue){
             if(response.error==1) return response.message;
          },
          source:[{value:'CONT',text:'Continous'},{value:'PRN',text:'PRN'}]
    });
    $('.editable-click.editable-noreload').editable({
          emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          validate: function(value) {
              if($.trim(value) == '') {
                  return 'This field is required';
              }
          },
          success:function(response,newValue){
            console.log(response);
             if(response.error==1) return response.message;
          }
    });
     var currdate = new Date();
     var n = currdate.getFullYear();
    $('.editable-click.editable-combodate').editable({
          emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          mode:'popup',
          combodate:{
              minYear: 1990,
              maxYear: n+5,
              minuteStep: 1
          },
          validate: function(value) {
              if($.trim(value) == '') {
                  return 'This field is required';
              }
          },
          success:function(response,newValue){
             if(response.error==1) return response.message;
             else window.location.reload();
          }
    });
    $('.editable-click.editable-combodate-notrequired').editable({
          emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          mode:'popup',
          combodate:{
              minYear: 1990,
              maxYear: n+5,
              minuteStep: 1
          },
          success:function(response,newValue){
             if(response.error==1) return response.message;
             else window.location.reload();
          }
    });

  });
</script>