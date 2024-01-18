<?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller') :

  $get_different = get_different($order['uniqueID']);
  $get_exchange = get_exchange_order($order['uniqueID']);

  if(!empty($get_different))
  {
    if($get_different['serial_num'] == '' && $order['status_activity_typeid'] == 2 && $get_different['original_activity_typeid'] == 3)
    {
?>
      <select class="form-control change_order_status" data-type="3"  data-id="<?php echo $order['medical_record_id'] ?>" data-organization-id="<?php echo $order['organization_id'] ?>" data-unique-id="<?php echo $order['uniqueID'] ?>" data-act-id="<?php echo $order['status_activity_typeid'] ?>" data-is-new-patient="<?php echo $order['is_new_patient']; ?>">
<?php
    }
    else if($get_different['serial_num'] == '' && $order['status_activity_typeid'] == 2 && $get_different['original_activity_typeid'] != 2)
    {
?>
      <select class="form-control change_order_status" data-type="1"  data-id="<?php echo $order['medical_record_id'] ?>" data-organization-id="<?php echo $order['organization_id'] ?>" data-unique-id="<?php echo $order['uniqueID'] ?>" data-act-id="<?php echo $order['status_activity_typeid'] ?>" data-is-new-patient="<?php echo $order['is_new_patient']; ?>">
<?php
    }
    else
    {
?>
      <select class="form-control change_order_status" data-type="0"  data-id="<?php echo $order['medical_record_id'] ?>" data-organization-id="<?php echo $order['organization_id'] ?>" data-unique-id="<?php echo $order['uniqueID'] ?>" data-act-id="<?php echo $order['status_activity_typeid'] ?>" data-is-new-patient="<?php echo $order['is_new_patient']; ?>">
<?php
    }
  }
  else
  {
    if(!empty($get_exchange))
    {
      if(($get_exchange['serial_num'] == '' && $get_exchange['order_status'] == "tobe_confirmed") || ($get_exchange['serial_num'] == '' && $get_exchange['order_status'] == "pending") || ($get_exchange['serial_num'] == '' && $get_exchange['order_status'] == "active") || ($get_exchange['serial_num'] == '' && $get_exchange['order_status'] == "on-hold") || ($get_exchange['serial_num'] == '' && $get_exchange['order_status'] == "re-schedule"))
      {
?>
        <select class="form-control change_order_status" data-type="1"  data-id="<?php echo $order['medical_record_id'] ?>" data-organization-id="<?php echo $order['organization_id'] ?>" data-unique-id="<?php echo $order['uniqueID'] ?>" data-act-id="<?php echo $order['status_activity_typeid'] ?>" data-is-new-patient="<?php echo $order['is_new_patient']; ?>">
<?php
      }
      else
      {
?>
        <select class="form-control change_order_status" data-type="0"  data-id="<?php echo $order['medical_record_id'] ?>" data-organization-id="<?php echo $order['organization_id'] ?>" data-unique-id="<?php echo $order['uniqueID'] ?>" data-act-id="<?php echo $order['status_activity_typeid'] ?>" data-is-new-patient="<?php echo $order['is_new_patient']; ?>">
<?php
      }
    }
    else
    {
?>
      <select class="form-control change_order_status" data-type="0"  data-id="<?php echo $order['medical_record_id'] ?>" data-organization-id="<?php echo $order['organization_id'] ?>" data-unique-id="<?php echo $order['uniqueID'] ?>" data-act-id="<?php echo $order['status_activity_typeid'] ?>" data-is-new-patient="<?php echo $order['is_new_patient']; ?>">
<?php
    }
  }

    if(!empty($get_different))
    {
      if($get_different['serial_num'] == '' && $order['status_activity_typeid'] == 2)
      {
  ?>
        <option value="not_confirmed">Confirm</option>
  <?php
      }
      else
      {
  ?>
        <option value="confirmed">Confirm</option>
  <?php
      }
    }
    else
    {
      if(!empty($get_exchange))
      {
        if(($get_exchange['serial_num'] == '' && $get_exchange['order_status'] == "tobe_confirmed") || ($get_exchange['serial_num'] == '' && $get_exchange['order_status'] == "pending") || ($get_exchange['serial_num'] == '' && $get_exchange['order_status'] == "active") || ($get_exchange['serial_num'] == '' && $get_exchange['order_status'] == "on-hold") || ($get_exchange['serial_num'] == '' && $get_exchange['order_status'] == "re-schedule"))
        {
  ?>
          <option value="not_confirmed">Confirm</option>
  <?php
        }
        else
        {
  ?>
          <option value="confirmed">Confirm</option>
  <?php
        }
      }
      else
      {
  ?>
        <option value="confirmed">Confirm</option>
  <?php
      }
    }
  ?>
  <option selected="true" value="tobe_confirmed">Moved to Confirm WO</option>
  <option value="pending">Revert to POS</option>
  <option disabled="disabled" id="demo_divider">----------------------</option>
  <option value="cancel">Cancel</option>
</select>
<?php else:?>
<?php if($order['order_status'] == 'active') :?>
  <p class="fa fa-truck" style="float:left;margin-top:3px;font-size:25px;color:#f0ad4e"></p>
  <p style="float:left;margin-left:5px;margin-top: 5px;"> En route</p>
<?php else:?>
  <?php echo ucfirst($order['order_status']) ?>
<?php endif;?>
<?php endif;?>