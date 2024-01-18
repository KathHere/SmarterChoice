<?php
    if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'distribution_supervisor') :
?>
    <select class="form-control change_order_status" data-id="<?php echo $order['medical_record_id'] ?>" data-organization-id="<?php echo $order['organization_id'] ?>" data-unique-id="<?php echo $order['uniqueID'] ?>" data-act-id="<?php echo $order['status_activity_typeid'] ?>" data-equipment-id="<?php echo $order['equipmentID'] ?>" data-is-new-patient="<?php echo $order['is_new_patient']; ?>">

        <?php if($order['order_status'] == 'pending') :?>
            <option value="active">En route</option>
            <option value="tobe_confirmed">Move to Confirm WO</option>
            <option value="on-hold">On Hold</option>
            <option selected="true" value="pending">Pending</option>
            <option value="re-schedule">Rescheduled</option>
            <option value="dispatch">Dispatch</option>
            <option disabled="disabled" id="demo_divider">---------------------</option>
            <option value="cancel">Cancel</option>
        <?php elseif($order['order_status'] == 'active') :?>
            <option selected="true" value="active">En route</option>
            <option value="tobe_confirmed">Move to Confirm WO</option>
            <option value="on-hold">On Hold</option>
            <option value="pending">Pending</option>
            <option value="re-schedule">Rescheduled</option>
            <option value="dispatch">Dispatch</option>
            <option disabled="disabled" id="demo_divider">---------------------</option>
            <option value="cancel">Cancel</option>
        <?php elseif($order['order_status'] == 'on-hold') :?>
            <option value="active">En route</option>
            <option value="tobe_confirmed">Move to Confirm WO</option>
            <option selected="true" value="on-hold">On Hold</option>
            <option value="pending">Pending</option>
            <option value="re-schedule">Rescheduled</option>
            <option value="dispatch">Dispatch</option>
            <option disabled="disabled" id="demo_divider">---------------------</option>
            <option value="cancel">Cancel</option>
        <?php elseif($order['order_status'] == 're-schedule') :?>
            <option value="active">En route</option>
            <option value="tobe_confirmed">Move to Confirm WO</option>
            <option value="on-hold">On Hold</option>
            <option value="pending">Pending</option>
            <option selected="true" value="re-schedule">Rescheduled</option>
            <option value="dispatch">Dispatch</option>
            <option disabled="disabled" id="demo_divider">---------------------</option>
            <option value="cancel">Cancel</option>
        <?php elseif($order['order_status'] == 'cancel') :?>
            <option value="active">En route</option>
            <option value="tobe_confirmed">Move to Confirm WO</option>
            <option value="on-hold">On Hold</option>
            <option value="pending">Pending</option>
            <option value="re-schedule">Rescheduled</option>
            <option value="dispatch">Dispatch</option>
            <option disabled="disabled" id="demo_divider">---------------------</option>
            <option selected="true" value="cancel">Cancel</option>
        <?php elseif($order['order_status'] == 'dispatch') :?>
            <option value="active">En route</option>
            <option value="tobe_confirmed">Move to Confirm WO</option>
            <option value="on-hold">On Hold</option>
            <option value="pending">Pending</option>
            <option value="re-schedule">Rescheduled</option>
            <option selected="true" value="dispatch">Dispatch</option>
            <option disabled="disabled" id="demo_divider">---------------------</option>
            <option value="cancel">Cancel</option>
        <?php else:?>
            <option value="active">En route</option>
            <option selected="true" value="tobe_confirmed">Move to Confirm WO</option>
            <option value="on-hold">On Hold</option>
            <option value="pending">Pending</option>
            <option value="re-schedule">Rescheduled</option>
            <option value="dispatch">Dispatch</option>
            <option disabled="disabled" id="demo_divider">---------------------</option>
            <option value="cancel">Cancel</option>
        <?php endif;?>
    </select>
    <?php
        if($order['order_status'] == 're-schedule')
        {
            $returned_status_id = get_status_id($order['uniqueID']);
            $returned_date = get_reschreschedule_onhold_date($returned_status_id['statusID']);
            if(!empty($returned_date))
            {
    ?>
                <span class="resceduled_onhold_date_pos" style="margin-left:25px;">
                    <span data-id="<?php echo $order['uniqueID'] ?>" class="set_date_reschedule_onhold" style="cursor:pointer;"><i class="fa fa-calendar"></i></span>&nbsp;&nbsp;
                    <span class="resceduled_onhold_date_container" data-sign="1"><?php echo date("m/d/Y", strtotime($returned_date['date'])) ?></span>
                </span>
    <?php
            }
        }
    ?>

<?php else:?>

    <?php
        if ($order['order_status'] == 'active') {
    ?>
            <p class="fa fa-truck" style="float:left;margin-top:3px;font-size:25px;color:#f0ad4e"></p>
            <p style="float:left;margin-left:5px;margin-top: 5px;"> En route</p>
    <?php
        } else if ($order['order_status'] == 're-schedule') {
            $returned_status_id = get_status_id($order['uniqueID']);
            $returned_date = get_reschreschedule_onhold_date($returned_status_id['statusID']);
            if(!empty($returned_date))
            {
                echo ucfirst($order['order_status']);
    ?>
                <span><?php echo date("m/d/Y", strtotime($returned_date['date'])) ?></span>
    <?php
            }
        } else {
            echo ucfirst($order['order_status']);
        }
    ?>

<?php endif;?>
