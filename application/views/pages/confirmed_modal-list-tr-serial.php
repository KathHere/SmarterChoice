       <?php
                if($info['parentID'] != "" ) {
                  if($info['parentID'] == 0){
                    if($info['canceled_order'] == 0){
                      if($info['serial_num'] == "pickup_order_only") {
                        //Miscellaneous CAPPED and NONCAPPED
                        if($info['equipmentID'] == 309 || $info['equipmentID'] == 306)
                        {
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $item_description_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
              <?php
                          $item_description_count++;
                        // Oxygen Cylinder, E Refill - DISPOSABLES
                        }else if($info['equipmentID'] == 11){
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $oxygen_cylinder_e_refill_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
              <?php
                          $oxygen_cylinder_e_refill_count++;
                        // Oxygen Cylinder, M6 Refill - DISPOSABLES
                        }else if($info['equipmentID'] == 170){
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $oxygen_cylinder_m6_refill_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
              <?php
                          $oxygen_cylinder_m6_refill_count++;
                        // Oxygen Cylinder Rack - NONCAPPED
                        }else if($info['equipmentID'] == 32){
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $oxygen_cylinder_rack_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
              <?php
                          $oxygen_cylinder_rack_count++;
                        // Bed and Chair Alarm - NONCAPPED
                        }else if($info['equipmentID'] == 296){
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $bed_chair_alarm_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
              <?php
                          $bed_chair_alarm_count++;
                        // Scale Chair - NONCAPPED
                        }else if($info['equipmentID'] == 181){
              ?>
                          <input type="text" data-id="" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $scale_chair_count) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
              <?php
                          $scale_chair_count++;
                        // Large Mesh Sling - NONCAPPED
                        }else if($info['equipmentID'] == 196){
              ?>
                          <input type="text" data-id="<?php echo $patient_lift_sling_count_equipment; ?>" value="<?php echo get_original_serial_number_v2($info['equipmentID'], $info['medical_record_id'], $info['uniqueID'], $patient_lift_sling_count_equipment) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
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
                            <input type="text" value="<?php echo $queried_serial_number; ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
              <?php
                          }
                          else
                          {
              ?>
                            <input type="text" value="<?php echo get_original_serial_number($info['equipmentID'], $info['medical_record_id'], $info['uniqueID']) ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required  />
              <?php
                          }
                        }
                      }else{
              ?>
                        <input type="text" value="<?php echo $info['serial_num']; ?>" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required />
              <?php
                      }
                    }else{
              ?>
                      <input type="text" value="---" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required />
              <?php
                    }
                  }else{
              ?>
                    <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required />
              <?php
                  }
                }else{
              ?>
                  <input type="text" value="item_options_only" style="width:125px;border-color:#fafafa !important" name="order_summary[<?php echo $info['equipmentID']?>][serial_num]" class="serial_num<?php echo $info['equipmentID'] ?> form-control" required />
              <?php
                }
              ?>
