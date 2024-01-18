<?php
              if($info['equipmentID'] == 181 || $info['equipmentID'] == 182 || $info['equipmentID'] == 170 || $info['equipmentID'] == 11) {
                if($info['equipmentID'] == 181 || $info['equipmentID'] == 182) :
                  if($info['activity_typeid'] != 2) :
            ?>
                    <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="patient_weight_required" title="Customer Weight is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />
            <?php
                    //check if naay sub equipment using equipment id, work uniqueId
                    $subequipment_id = $subequipments[$info['equipmentID']];
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
                  $subequipment_id = $subequipments[$info['equipmentID']];
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
                  <img src="<?php echo base_url('assets/img/warning_icon.png') ?>" class="lot_number_required" title="Lot Number is Required" style="width: 15px;height: 15px;margin-right: 7px;cursor:pointer" />
            <?php
                  //check if naay sub equipment using equipment id, work uniqueId
                  $subequipment_id = $subequipments[$info['equipmentID']];
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
                  $subequipment_id = $subequipments[$info['equipmentID']];
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
