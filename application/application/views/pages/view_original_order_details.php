
<!-- jQuery -->
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/common.js"></script>


<?php if(!empty($informations)) :?>
<?php $info = $informations[0];?>

<?php if(!empty($activity_fields)):?>
  <?php $fields = $activity_fields[0];?>
<?php endif;?>


<strong>Entry Time</strong>
<p><?php echo date("F j, Y, g:i a", strtotime($info['date_ordered'])) ?></p>

<strong>Work Order #</strong>
<p><?php echo substr($info['uniqueID'],4,10) ?></p>


<?php if($info['initial_order'] == 1 || $info['pickup_order'] == 0 && $info['original_activity_typeid'] != 3 && $info['original_activity_typeid'] != 4 && $info['original_activity_typeid'] != 5) :?>
  <strong>Delivery Date</strong>
  <p><?php echo date("m/d/Y", strtotime($info['pickup_date'])) ?></p>
<?php endif;?>



<?php if($info['activity_typeid'] == 3) :?>
  <strong>Exchange Delivery Date</strong>
  <p><?php echo date("m/d/Y", strtotime($fields['exchange_date'])) ?></p>
  <strong>Reason for Exchange</strong>
  <p><?php echo $fields['exchange_reason'] ?></p>
<?php endif;?>


<?php if($info['original_activity_typeid'] == 4 || $info['activity_typeid'] == 4) :?>
  <strong>PT Move Delivery Date</strong>
  <p><?php echo date("m/d/Y", strtotime($fields['ptmove_delivery_date'])) ?></p>
  <strong>PT Move Address</strong>
  <p><?php echo $fields['ptmove_street'] ?>, <?php echo $fields['ptmove_placenum'] ?>, <?php echo $fields['ptmove_city'] ?>, <?php echo $fields['ptmove_state'] ?>, <?php echo $fields['ptmove_postal'] ?></p>
<?php endif;?>


<?php if($info['activity_typeid'] == 5) :?>
  <strong>Respite Delivery Date</strong>
  <p><?php echo date("m/d/Y", strtotime($fields['respite_delivery_date'])) ?></p>
  <strong>Respite Pickup Date</strong>
  <p><?php echo date("m/d/Y", strtotime($fields['respite_pickup_date'])) ?></p>
  <strong>Respite Patient Residence</strong>
    <p>
      <?php echo $fields['respite_deliver_to_type'] ?>
    </p>
  <strong>Respite Phone Number</strong>
    <p>
      <?php echo $fields['respite_phone_number'] ?>
    </p>
  <strong>Respite Address</strong>
  <p><?php echo $fields['respite_address'] ?>, <?php echo $fields['respite_placenum'] ?>, <?php echo $fields['respite_city'] ?>, <?php echo $fields['respite_state'] ?>, <?php echo $fields['respite_postal'] ?></p>
<?php endif;?>

<hr />

<div class="row">
    <div class="">
        <div class="col-md-6">
            <strong>Hospice Provider</strong>
            <p><?php echo $info['hospice_name'] ?></p>

           
            <strong>Hospice Staff Member Cell Phone</strong>
           <?php if($info['who_ordered_cpnum'] == '') :?>
            <p>N/A</p>
          <?php else:?>
            <p><?php echo $info['who_ordered_cpnum'] ?></p>
          <?php endif;?>


            <strong>Delivery Instructions</strong>
           <?php if($info['comment'] == '') :?>
              <p>N/A</p>
           <?php else:?>
              <p><?php echo $info['comment'] ?></p>
           <?php endif;?>

           <hr style="width:312px" />
           <!--  <strong>DME Staff Member Taken Order</strong>
            <?php if($info['staff_member_fname'] == '' && $info['staff_member_lname'] == '') :?>
              <p>NA</p>
            <?php else:?>
              <p><?php echo $info['staff_member_fname']  ." ". substr($info['staff_member_lname'],0,1) ."." ?></p>
            <?php endif;?> -->
            <?php
              $account_type = check_user_account_type($info['userID']);
            ?>

            <strong>DME Staff Member Taken Order</strong>
            <?php if($info['staff_member_fname'] == '' && $info['staff_member_lname'] == '') :?>
              <p>NA</p>
            <?php else:?>
              <?php if($account_type == 'hospice_user' || $account_type == 'hospice_admin') :?>
                <p>NA</p>
              <?php else:?> 
                <p><?php echo $info['staff_member_fname']  ." ". substr($info['staff_member_lname'],0,1) ."." ?></p>
              <?php endif;?>
            <?php endif;?>


            <strong>DME Staff Member Delivered Order</strong>
             <?php if($info['driver_name'] == '' && $info['driver_name'] == '') :?>
              <p style="text-transform:uppercase">NA</p>
            <?php else:?>
              <p style="text-transform:uppercase"><?php echo $info['driver_name'] ?></p>
            <?php endif;?>

            

            <hr style="width:310px" />

            <a href="javascript:void(0)" data-toggle="popover" class="view_editing_logs" data-patient-id="<?php echo $info['patientID'] ?>" style="color:red;">View Edit Logs</a><br/><br >

            <strong>Patient Medical Record #sss</strong>
            <p><?php echo $info['medical_record_id'] ?></p>

            <strong>Patient Name</strong>
            <p><?php echo $info['p_lname'].", ".$info['p_fname'] ?></p>


             <strong>Height(IN), Weight(LBS)</strong>
            <p><?php echo $info['p_height'] ?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <?php echo $info['p_weight'] ?></p>

                <strong>Relationship</strong>
           <?php if($info['p_relationship'] == '') :?>
              <p>N/A</p>
           <?php else:?>
              <p><?php echo $info['p_relationship'] ?></p>
           <?php endif;?>


           <strong>Patient Residence</strong>
           <?php if($info['deliver_to_type'] == '') :?>
              <p>N/A</p>
           <?php else:?>
              <p><?php echo $info['deliver_to_type'] ?></p>
           <?php endif;?>

            <?php 
              if($info['original_activity_typeid'] == 4 || $info['activity_typeid'] == 4) {
                $query = get_patient_move_first_row($info['patientID']);
                if(!empty($query)){
                  $ptmove_details = get_ptmove_address($info['medical_record_id'], $info['uniqueID']);
            ?>
                  <strong>Patient Address</strong>
                  <p><?php echo $ptmove_details['ptmove_street']." ".$ptmove_details['ptmove_placenum']." ".$ptmove_details['ptmove_city']." ".$ptmove_details['ptmove_state']." ".$ptmove_details['ptmove_postal'] ?></p>
            <?php 
                }else{
            ?>
                  <strong>Patient Address</strong>
                  <p><?php echo $info['p_street']." ".$info['p_placenum']." ".$info['p_city']." ".$info['p_state']." ".$info['p_postalcode'] ?></p>
            <?php 
                }
              }else if($info['original_activity_typeid'] == 1 || $info['activity_typeid'] == 1){
                /** Added by Russel **/
                $ptmove = new_ptmove_address($info['patientID']);
                if(!empty($ptmove)){
                  $query = get_patient_move_first_row($info['patientID']);
                  if(!empty($query)){
                    if($info['orderID'] > $query['orderID']){
                      $patientmove_details = $ptmove[0];
            ?>
                      <strong>Patient Address</strong>
                      <p><?php echo $patientmove_details['ptmove_street']." ".$patientmove_details['ptmove_placenum']." ".$patientmove_details['ptmove_city']." ".$patientmove_details['ptmove_state']." ".$patientmove_details['ptmove_postal'] ?></p>
            <?php 
                    }else{
            ?>
                      <strong>Patient Address</strong>
                      <p><?php echo $info['p_street']." ".$info['p_placenum']." ".$info['p_city']." ".$info['p_state']." ".$info['p_postalcode'] ?></p>
            
            <?php 
                    }
                  }else{
            ?>
                    <strong>Patient Address</strong>
                    <p><?php echo $info['p_street']." ".$info['p_placenum']." ".$info['p_city']." ".$info['p_state']." ".$info['p_postalcode'] ?></p>
            <?php
                  }
                }else{ 
            ?>
                  <strong>Patient Address</strong>
                  <p><?php echo $info['p_street']." ".$info['p_placenum']." ".$info['p_city']." ".$info['p_state']." ".$info['p_postalcode'] ?></p>
            <?php 
                } 
              }else{
            ?>
                  <strong>Patient Address</strong>
                  <p><?php echo $info['p_street']." ".$info['p_placenum']." ".$info['p_city']." ".$info['p_state']." ".$info['p_postalcode'] ?></p>
            <?php }?>


          </div>
          <div class="col-md-6">
         

           <strong>Hospice Staff Member Creating Order</strong>
            <?php if($info['who_ordered_lname'] == '' && $info['who_ordered_fname'] == '') :?>
              <p>NA</p>
            <?php else:?>
              <p><?php echo $info['who_ordered_fname']  ." ". $info['who_ordered_lname'] ?></p>
            <?php endif;?>


             <strong>Hospice Staff Member Email Address</strong>
           <?php if($info['who_ordered_email'] == '') :?>
              <p>N/A</p>
           <?php else:?>
              <p><?php echo $info['who_ordered_email'] ?></p>
           <?php endif;?>
           

          <strong style="visibility:hidden">Divider</strong>
          <p style="visibility:hidden">Divider</p>

          <!-- <strong>Special Instructions</strong>
           <?php if($info['comment'] == '') :?>
              <p>N/A</p>
           <?php else:?>
              <p><?php echo $info['comment'] ?></p>
           <?php endif;?>
          
           <strong style="visibility:hidden">test</strong>
           <p style="visibility:hidden">NA</p> -->

           <hr />

           
            
            <strong>DME Staff Member Confirming Work Order</strong>
            <?php if($info['person_confirming_order'] == '' && $info['person_confirming_order'] == '') :?>
              <p style="text-transform:uppercase">NA</p>
            <?php else:?>
              <p style="text-transform:uppercase"><?php echo $info['person_confirming_order'] ?></p>
            <?php endif;?>

             <strong style="visibility:hidden">divider</strong>
            <p style="visibility:hidden">divider</p>

            <hr/>

            <strong style="visibility:hidden">Divider</strong>
            <p style="visibility:hidden">Divider</p>


           <strong>Gender</strong>
           <?php if($info['relationship_gender'] == 1) :?>
            <p>Male</p>
          <?php else:?>
            <p>Female</p>
          <?php endif;?>

           <strong>Next of Kin</strong>
           <?php if($info['p_nextofkin'] == '') :?>
              <p>N/A</p>
           <?php else:?>
              <p><?php echo $info['p_nextofkin'] ?></p>
           <?php endif;?>
           
         

           <strong>Next of Kin Phone No.</strong>
            <?php if($info['p_nextofkinnum'] == '') :?>
              <p>N/A</p>
           <?php else:?>
              <p><?php echo $info['p_nextofkinnum'] ?></p>
           <?php endif;?>

           <strong style="visibility:hidden">Divider</strong>
           <p style="visibility:hidden">Divider</p>


            <?php if($info['original_activity_typeid'] == 4 || $info['activity_typeid'] == 4) :?>
              <?php 
                $ptmove_details = get_ptmove_address($info['medical_record_id'], $info['uniqueID']);
              ?>
              <strong>Patient Phone</strong>
                <?php if($ptmove_details['ptmove_patient_phone'] == '') :?>
                  <p>N/A</p>
               <?php else:?>
                  <p><?php echo $ptmove_details['ptmove_patient_phone'] ?></p>
               <?php endif;?>

            <?php else:?>
               <strong>Patient Phone</strong>
                <?php if($info['p_phonenum'] == '') :?>
                  <p>N/A</p>
               <?php else:?>
                  <p><?php echo $info['p_phonenum'] ?></p>
               <?php endif;?>
            <?php endif;?>

        </div>



<?php endif;?>
      
      <div class="col-md-12" style="">

       <hr />
        <h4>Ordered Item(s)</h4>

        <?php 
          if(!empty($equipments_ordered)){
            foreach($equipments_ordered as $equipment){
              // print_r($equipment);
              if($equipment['type'] == 'Capped Item'){
        ?>
                <strong><span class="text-info"><?php echo $equipment['type'] ?></span></strong>
        <?php 
              }else if($equipment['type'] == 'Non-Capped Item'){
        ?>
                <strong><span class="text-warning" style="color:#DAB506 !important"><?php echo $equipment['type'] ?></span></strong>
        <?php 
              }else{
        ?>
                <strong><span class="text-success"><?php echo $equipment['type'] ?></span></strong>
        <?php 
              }
        ?>

            <br/>

        <?php 
              if($equipment['canceled_order'] == 0){ 
        ?>
                <strong><span><?php echo $equipment['key_desc'] ?></span></strong><br />
        <?php 
                if($equipment['categoryID'] == 2){ 
        ?>
                  <strong>
                    <span>
                      Quantity: 
        <?php 
                      if(get_noncapped_quantity($equipment['equipmentID'],$equipment['uniqueID']) == 0)
                      {
                        echo "1ea";
                      }
                      else if($equipment['equipment_value'] == 1)
                      {
                        echo "1ea"; //newly added
                      }
                      else
                      {
                        echo get_noncapped_quantity($equipment['equipmentID'],$equipment['uniqueID'])."ea";
                      }   
        ?>
                    </span>
                  </strong>
        <?php 
                }  
                if($equipment['categoryID'] == 3){ 
                  if($equipment['equipment_value'] > 1){
        ?>
                    <strong><span><?php echo get_disposable_quantity_name($equipment['equipmentID']) ?>: <?php echo $equipment['equipment_value']; ?>ea</span></strong><br />
        <?php
                  }else{
                    if(get_disposable_quantity($equipment['equipmentID'],$equipment['uniqueID']) == 0){ 
        ?>
						          <strong><span><?php echo get_disposable_quantity_name($equipment['equipmentID']) ?>: 1ea </span></strong><br/>
				<?php 
                    }else{ 
        ?>
					            <strong><span><?php echo get_disposable_quantity_name($equipment['equipmentID']) ?>: <?php echo get_disposable_quantity($equipment['equipmentID'],$equipment['uniqueID']) ?>ea</span></strong><br/>
						
				<?php 
                    }
                  } 
                }
        ?>

                
                
              <!--   <?php if($equipment['serial_num'] != "" && $equipment['initial_order'] != 1) :?>
                  <strong><span>Serial No. <?php echo $equipment['serial_num'] ?></span></strong>
                <?php endif;?> -->


        <?php 
              }else{
        ?>
                <strong class="data_tooltip" title="Item Canceled" style="cursor:pointer"><span style="text-decoration:line-through" ><?php echo $equipment['key_desc'] ?></span></strong><br />

        <?php 
                if($equipment['categoryID'] == 3){
                  if(get_disposable_quantity($equipment['equipmentID'],$equipment['uniqueID']) == 0){ 
        ?>
								    <strong><span><?php echo get_disposable_quantity_name($equipment['equipmentID']) ?>: 1ea</span></strong><br />
				<?php 
                  }else{ 
        ?>
								    <strong><span><?php echo get_disposable_quantity_name($equipment['equipmentID']) ?>: <?php echo get_disposable_quantity($equipment['equipmentID'],$equipment['uniqueID']) ?>ea</span></strong><br />
				<?php 
                  } 
                }
        ?>

               <!--  <?php if($equipment['serial_num'] != "" && $equipment['initial_order'] != 1) :?>
                  <strong><span>Serial No. <?php echo $equipment['serial_num'] ?></span></strong>
                <?php endif;?> -->


        <?php 
              } 
        ?>
              <br/><br/>
        <?php 
            } 
          } 
        ?>
        

        <?php /*
        <?php 
          $old_items = get_old_item($work_order);
        ?>
        <?php if(!empty($old_items)) :?>

          <hr />

          <?php foreach($old_items as $item) :?>
            

            <strong><span class="text-success"><?php echo $item['type'] ?></span></strong>
            
            <br/>
            <?php if($item['canceled_order'] == 0) :?>
              <strong><span><?php echo $item['key_desc'] ?></span></strong><br />
              
                <?php if($item['categoryID'] == 3) :?>
                  <strong><span><?php echo get_disposable_quantity_name($item['equipmentID']) ?>: <?php echo get_disposable_quantity($item['equipmentID']) ?>ea</span></strong><br />
                <?php endif;?>

                <?php if($item['serial_num'] != "") :?>
                  <strong><span>Serial No. <?php echo $item['serial_num'] ?></span></strong>
                <?php endif;?>


            <?php else:?>
              <strong><span style="text-decoration:line-through"><?php echo $item['key_desc'] ?> </span></strong>
                
                <?php if($item['categoryID'] == 3) :?>
                  <strong><span style="text-decoration:line-through"><?php echo get_disposable_quantity_name($item['equipmentID']) ?>: <?php echo get_disposable_quantity($item['equipmentID']) ?>ea</span></strong>
                <?php endif;?>

                <?php if($item['serial_num'] != "") :?>
                  <strong><span>Serial No. <?php echo $item['serial_num'] ?></span></strong>
                <?php endif;?>

            <?php endif;?>
            <br/><br/>

          <?php endforeach;?>
        <?php endif;?>
  */?>

        </div>
    </div>
</div>

