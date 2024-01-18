<style type="text/css">

input[type="search"]
{
  margin-left: 13px;
}

select.input-sm
{
  margin-left: 11px;
  margin-right: 11px;
}

</style>

<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">Confirm Work Orders</h1>
</div>

<div class="row" style="">
</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">
      Order Status
    </div>
    <div class="table-responsive">
      <table class="table table-striped m-b-none datatable_table">
        <thead>
          <tr>
            <th  style="width:10%">Order Date</th>
            <th  style="width:15%">Patient Last Name</th>
            <th  style="width:15%">Patient First Name</th>
            <th  style="width:10%">Medical Record Number</th>
            <th  style="width:10%">Activity Type</th>
            <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?> 
              <th  style="width:12%">Hospice Provider</th>
            <?php endif ;?>
            
            <th  style="width:15%" class="hide_on_print">Order Details</th>
            <th  style="width:10%" class="hide_on_print">Status Notes</th>
            <th  style="width:15%">Order Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($orders)) :?> 
            <?php foreach ($orders as $order) :
            ?> 

              <tr>
                <td  style="width:10%"><?php echo date("m/d/Y", strtotime($order['pickup_date'])) ?></td>
                <!-- <td  style="width:15%">WO#<?php echo $order['uniqueID'] ?></td> -->
                <td  style="width:15%">
                    <a class="text-bold" href="<?php echo base_url("order/patient_profile/".$order['medical_record_id']."/".$order['organization_id']); ?>" target="_blank"><?php echo $order['p_lname'] ?></a>
                </td>
                <td  style="width:15%">
                    <a class="text-bold" href="<?php echo base_url("order/patient_profile/".$order['medical_record_id']."/".$order['organization_id']); ?>" target="_blank"><?php echo $order['p_fname'] ?></a>
                </td>
                <td  style="width:15%"><?php echo $order['medical_record_id'] ?></td>
                <td  style="width:10%">
                  <?php 
                    $address_type = get_address_type($order['addressID']);
                    $address_sequence = 0;
                    $address_count = 1;

                    if(get_activity_name($order['status_activity_typeid']) == "Delivery")
                    {
                      if(($address_type['type']) == 0)
                      {
                        echo "Delivery";
                      }
                      else if($address_type['type'] == 1)
                      { 
                        $ptmove_addresses_ID = get_ptmove_addresses_ID($order['patientID']);
                        foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                          if($addresses_ID_row['id'] == $order['addressID'])
                          {
                            $address_sequence = $address_count;
                            break;
                          }
                          $address_count++;
                        }
                        if($address_sequence == 1)
                        {
                          echo "Delivery (PT Move)";
                        }
                        else
                        {
                          echo "Delivery (PT Move ".$address_sequence.")";
                        }
                      }
                      else 
                      {
                        $respite_addresses_ID = get_respite_addresses_ID($order['patientID']);
                        foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                          if($addresses_ID_row['id'] == $order['addressID'])
                          {
                            $address_sequence = $address_count;
                            break;
                          }
                          $address_count++;
                        }
                        if($address_sequence == 1)
                        {
                          echo "Delivery (Respite)";
                        }
                        else
                        {
                          echo "Delivery (Respite ".$address_sequence.")";
                        }
                      }
                    }
                    else if(get_activity_name($order['status_activity_typeid']) == "Exchange")
                    {
                      if(($address_type['type']) == 0)
                      {
                        echo "Exchange";
                      }
                      else if($address_type['type'] == 1)
                      { 
                        $ptmove_addresses_ID = get_ptmove_addresses_ID($order['patientID']);
                        foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                          if($addresses_ID_row['id'] == $order['addressID'])
                          {
                            $address_sequence = $address_count;
                            break;
                          }
                          $address_count++;
                        }
                        if($address_sequence == 1)
                        {
                          echo "Exchange (PT Move)";
                        }
                        else
                        {
                          echo "Exchange (PT Move ".$address_sequence.")";
                        }
                      }
                      else 
                      {
                        $respite_addresses_ID = get_respite_addresses_ID($order['patientID']);
                        foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                          if($addresses_ID_row['id'] == $order['addressID'])
                          {
                            $address_sequence = $address_count;
                            break;
                          }
                          $address_count++;
                        }
                        if($address_sequence == 1)
                        {
                          echo "Exchange (Respite)";
                        }
                        else
                        {
                          echo "Exchange (Respite ".$address_sequence.")";
                        }
                      }
                    }
                    else if(get_activity_name($order['status_activity_typeid']) == "PT Move")
                    {
                      $ptmove_addresses_ID = get_ptmove_addresses_ID($order['patientID']);
                      foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                        if($addresses_ID_row['id'] == $order['addressID'])
                        {
                          $address_sequence = $address_count;
                          break;
                        }
                        $address_count++;
                      }
                      if($address_sequence == 1)
                      {
                        echo "PT Move";
                      }
                      else
                      {
                        echo "PT Move ".$address_sequence;
                      }
                    }
                    else if(get_activity_name($order['status_activity_typeid']) == "Respite")
                    {
                      $respite_addresses_ID = get_respite_addresses_ID($order['patientID']);
                        foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                          if($addresses_ID_row['id'] == $order['addressID'])
                          {
                            $address_sequence = $address_count;
                            break;
                          }
                          $address_count++;
                        }
                        if($address_sequence == 1)
                        {
                          echo "Respite";
                        }
                        else
                        {
                          echo "Respite ".$address_sequence;
                        }
                    }
                    else if(get_activity_name($order['status_activity_typeid']) == "Pickup")
                    {
                      if(($address_type['type']) == 0)
                      {
                        echo "Pickup";
                      }
                      else if($address_type['type'] == 1)
                      { 
                        $ptmove_addresses_ID = get_ptmove_addresses_ID($order['patientID']);
                        foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
                          if($addresses_ID_row['id'] == $order['addressID'])
                          {
                            $address_sequence = $address_count;
                            break;
                          }
                          $address_count++;
                        }
                        if($address_sequence == 1)
                        {
                          echo "Pickup (PT Move)";
                        }
                        else
                        {
                          echo "Pickup (PT Move ".$address_sequence.")";
                        }
                      }
                      else 
                      {
                        $respite_addresses_ID = get_respite_addresses_ID($order['patientID']);
                        foreach($respite_addresses_ID as $key => $addresses_ID_row) {
                          if($addresses_ID_row['id'] == $order['addressID'])
                          {
                            $address_sequence = $address_count;
                            break;
                          }
                          $address_count++;
                        }
                        if($address_sequence == 1)
                        {
                          echo "Pickup (Respite)";
                        }
                        else
                        {
                          echo "Pickup (Respite ".$address_sequence.")";
                        }
                      }
                    }
                  ?>
                </td>
                <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
                  <td  style="width:12%"><?php echo $order['hospice_name'] ?></td>
                <?php endif ;?>
                
                <td  style="width:15%" class="hide_on_print">
                  <a href="javascript:void(0)" class="view_order_details data_tooltip" title="Click to View Order Details" data-id="<?php echo $order['medical_record_id'] ?>" data-value="<?php echo $order['hospiceID'] ?>" data-unique-id="<?php echo $order['uniqueID'] ?>" data-act-id="<?php echo $order['status_activity_typeid'] ?>" data-equip-id="<?php echo $order['equipmentID'] ?>" data-patient-id="<?php echo $order['patientID'] ?>">
                    <button class="btn btn-info">Order Details</button>
                  </a>
                </td>
                <td  style="width:10%" class="comment-container hide_on_print">
                  <a href="javascript:void(0)" name="comment-modal" style="text-decoration:none;cursor:pointer" class="comments_link" data-id="<?php echo $order['uniqueID'] ?>">
                    <i class="icon-speech"></i>
                    <p style="float: right;margin-top: -3px;margin-right: 11px;"><?php echo $order['comment_count'] ?></p>
                  </a>
                </td style="width:20%">
                <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>

                  <td>
                  <?php 
                    // echo "<pre>";
                    // print_r($order);
                    // echo "</pre>";
                  ?>
                    <select class="form-control change_order_status" data-id="<?php echo $order['medical_record_id'] ?>" data-organization-id="<?php echo $order['organization_id'] ?>" data-unique-id="<?php echo $order['uniqueID'] ?>" data-act-id="<?php echo $order['status_activity_typeid'] ?>">
                        <?php $get_different = get_different($order['uniqueID']);
                          //print_r($get_different['serial_num']);
                        ?>
                        <?php if($order['order_status'] == 'pending') :?>
                            <option value="cancel">Cancel</option>
                            <?php if($get_different['serial_num'] == 0 && $order['status_activity_typeid'] == 2) {?>
                            <option value="not_confirmed">Confirm</option>
                            <?php }else {?>
                            <option value="confirmed">Confirm</option>
                            <?php }?>
                            <option value="tobe_confirmed">Moved to Confirm WO</option>
                            <option selected="true" value="pending">Revert to POS</option>
                            
                        <?php elseif($order['order_status'] == 'tobe_confirmed') :?>
                            <option value="cancel">Cancel</option>
                            <?php if($get_different['serial_num'] == 0 && $order['status_activity_typeid'] == 2) {?>
                            <option value="not_confirmed">Confirm</option>
                            <?php }else {?>
                            <option value="confirmed">Confirm</option>
                            <?php }?>
                            <option selected="true" value="tobe_confirmed">Moved to Confirm WO</option>
                            <option value="pending">Revert to POS</option>

                        <?php elseif($order['order_status'] == 'cancel') :?>
                            <option value="cancel">Cancel</option>
                            <?php if($get_different['serial_num'] == 0 && $order['status_activity_typeid'] == 2) {?>
                            <option value="not_confirmed">Confirm</option>
                            <?php }else {?>
                            <option value="confirmed">Confirm</option>
                            <?php }?>
                            <option selected="true" value="tobe_confirmed">Moved to Confirm WO</option>
                            <option value="pending">Revert to POS</option>

                        <?php else:?>
                            <option selected="true" value="cancel">Cancel</option>
                            <?php if($get_different['serial_num'] == 0 && $order['status_activity_typeid'] == 2) {?>
                            <option value="not_confirmed">Confirm</option>
                            <?php }else {?>
                            <option value="confirmed">Confirm</option>
                            <?php }?>
                            <option selected="true" value="tobe_confirmed">Moved to Confirm WO</option>
                            <option value="pending">Revert to POS</option>
                        <?php endif;?>
                    </select>
                  </td>
                <?php else:?>
                  <td>
                      <?php if($order['order_status'] == 'active') :?>
                          <p class="fa fa-truck" style="float:left;margin-top:3px;font-size:25px;color:#f0ad4e"></p>
                          <p style="float:left;margin-left:5px;margin-top: 5px;"> En route</p>
                      <?php else:?>
                          <?php echo ucfirst($order['order_status']) ?>
                      <?php endif;?>                      
                  </td>
                <?php endif;?>
              </tr>
            <?php endforeach; ?> <!-- End sa foreach adtu sa taas :) -->
          <?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="bg-light lter wrapper-md">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>


<div class="modal fade" id="reason_for_cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="top: 100px;left: 345px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Reason for Cancel</h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="patient_weight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="top: 100px;left: 345px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Patient Weight</h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="lot_numbers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="top: 100px;left: 345px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Lot Number</h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="not_confirmed_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="top: 100px;left: 345px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Warning</h4>
      </div>
      <div class="modal-body">
      <p class="bold">This item(s) are not yet confirmed. Please confirm it first.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->