
            <?php

              $get_noncapped_quantity = get_noncapped_quantity($info['equipmentID'],$info['uniqueID']);
              $get_noncapped_quantity_v2 = get_noncapped_quantity_v2($passed_equip_id,$info['uniqueID'],$item_description_count);
              $get_disposable_quantity = get_disposable_quantity($info['equipmentID'],$info['uniqueID']);
              if($info['categoryID'] != 3){
                if($info['categoryID'] == 2){
                  if($info['equipment_value'] > 1 || $info['equipmentID'] == 176){
            ?>

                    <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
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
                    <input type="text" value="<?php echo $get_noncapped_quantity_v2; ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  // Oxygen Cylinder Rack - NONCAPPED or Bed and Chair Alarm - NONCAPPED  or Scale Chair - NONCAPPED   or  Large Mesh Sling - NONCAPPED
                  }else if($info['equipmentID'] == 32 || $info['equipmentID'] == 296 || $info['equipmentID'] == 181 || $info['equipmentID'] == 196){
            ?>
                    <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  }else{
                    if($get_noncapped_quantity == 0){
            ?>
                      <input type="text" value="1" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                      <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                    }else{

                      if($info['equipmentID'] == 4 || $info['equipmentID'] == 9 || $info['equipmentID'] == 30)
                      {
            ?>
                        <input type="text" value="<?php echo $info['equipment_value']; ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                        <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                      }else{
            ?>
                        <input type="text" value="<?php echo $get_noncapped_quantity; ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
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
                    <input type="text" value="<?php echo $get_noncapped_quantity; ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  }else if($non_capped_copy['noncapped_reference'] == 14){
            ?>
                    <input type="text" value="<?php echo $get_noncapped_quantity; ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  }else{
            ?>
                    <input type="text" value="<?php echo $info['equipment_value'] ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
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
                    if($get_disposable_quantity == 0){
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
                      <input type="text" value="<?php echo $get_disposable_quantity ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
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
                    <input type="text" value="<?php echo $get_disposable_quantity ?>" style="width:45px;border-color:#fafafa !important;text-transform:lowercase" name="order_summary[<?php echo $info['equipmentID']?>][qty]" class="form-control equipment_qty" />
                    <p style="float: right;margin-top: -27px;margin-right:6px">ea</p>
            <?php
                  }
                }
              }
            ?>
