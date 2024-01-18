<style type="text/css">
    .modal
    {
        left: -689px;
        top: -71px;
    }
    #globalModal .modal-content
    {
        width:1300px;
    }

    @media (max-width: 1265px){

        #globalModal
        {
            left:0 !important;
        }

        .modal-dialog{
            width:100% !important;
            overflow-x:scroll !important;
        }
    }

    @media (max-width: 770px){
        #globalModal
        {
            top:0 !important;
            right:10px !important;
        }
    }

    .disabled_edit_input
    {
        background-color: #fefefe !important;
    }

    .inactive_item
    {
        background-color: #b9b9b929;
    }

</style>

<?php
if (!empty($order_req_details)):
    echo form_open("", array("class"=>"order_requisition_form"));
?>

<input type="hidden" class="req_receiving_batch_no" name="req_receiving_batch_no" value="<?php echo $order_req_details['req_receiving_batch_no']; ?>">
<div class="row">
    <div class="">

        <div class="form-group clearfix" style="margin-bottom:0px !important;">
            <div class="col-sm-3" style="">
                <label>Date <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control order_req_date_receiving" name="order_req_date" value="<?php echo date("Y-m-d", strtotime($order_req_details['order_req_date'])); ?>">
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-3" style="">
                <label>Location <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <?php
                        $location = get_login_location($this->session->userdata('user_location'));
                    ?>
                    <input type="text"  class="form-control" name="location" value="<?php echo $location['user_city'].", ".$location['user_state']; ?>">
                </div>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-bottom:0px !important;">
            <div class="col-sm-6" style="">
                <label>Vendor <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control disabled_edit_input" value="<?php echo $order_req_details['vendor_name']; ?>" readonly>
                </div>
            </div>
            <div class="col-sm-3" style="">
                <label>Vendor Phone <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control disabled_edit_input" value="<?php echo $order_req_details['vendor_phone_no']; ?>" readonly>
                </div>
            </div>
            <div class="col-sm-3" style="">
                <label>Account No. <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control disabled_edit_input" value="<?php echo $order_req_details['vendor_acct_no']; ?>" readonly>
                </div>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-bottom:10px !important;">
            <div class="col-sm-3" style="">
                <label>Purchase Order No. <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control disabled_edit_input" value="<?php echo substr($order_req_details['purchase_order_no'], 3, 10);?>" readonly>
                </div>
            </div>
            <div class="col-sm-3" style="">
                <label>Vendor Rep. Taking Order <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control" name="vendor_rep_taking_order" value="<?php echo $order_req_details['vendor_rep_taking_order']; ?>">
                </div>
            </div>
            <div class="col-sm-3" style="">
                <label>Confirmation No. <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control" name="order_req_confirmation_no" value="<?php echo $order_req_details['order_req_confirmation_no']; ?>">
                </div>
            </div>
            <div class="col-sm-3" style="">
                <label> Person Placing Order<span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control" name="person_placing_order" value="<?php echo $order_req_details['person_placing_order']; ?>">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading font-bold">
        <h4>Receiving Details</h4>
    </div>
    <div class="panel-body" style="margin-left:5px;margin-right:5px !important; margin-bottom:10px;">
        <table class="table bg-white b-a col-md-12" id="" style="margin-top:-5px;margin-left: 0px;">
            <thead>
                <tr>
                    <th style="width:130px;"> Company Item No.</th>
                    <th style="width:220px !important;"> Item Description</th>
                    <th style="width:90px;"> Re-Order No.</th>
                    <th style="width:70px !important;"> Unit of Measure </th>
                    <th style="width:90px;"> Cost</th>
                    <th style="width:70PX;"> Qty. Ordered</th>
                    <th style="width:70px;"> Qty. Received</th>
                    <th style="width:100px;"> Total Cost</th>
                    <th style="width:70px;"> Cancel Item</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if (!empty($order_req_items)) {
                    $item_received_count = 0;
                    $ordered_item_list_received = array();
                    $ordered_item_list_container = array();
                    foreach ($order_receiving_list as $inside_key => $inside_value) {
                        if (!in_array($inside_value['item_id'], $ordered_item_list_container)) {
                            $ordered_item_list_container[] = $inside_value['item_id'];
                            $ordered_item_list_received[$inside_value['item_id']] = $inside_value['req_item_quantity_received'];
                        } else {
                            $ordered_item_list_received[$inside_value['item_id']] += $inside_value['req_item_quantity_received'];
                        }
                    }
                    $looped_item_list = array();
                    foreach ($order_req_items as $key => $value) {
                        if (!in_array($value['item_id'], $looped_item_list)) {
                            $looped_item_list[] = $value['item_id'];
                            $inactive_class= "";
                            if ($value['item_active_sign'] == 0) {
                                $inactive_class= "inactive_item";
                            }
                            if ($value['item_status'] == "0") {
                                echo "<tr class='".$inactive_class."'>";
                            } else {
                                echo "<tr class='".$inactive_class."' style='text-decoration:line-through;'>";
                            } ?>
                            <td> <?php echo $value['company_item_no']; ?> </td>
                            <td> <?php echo $value['item_description']; ?> </td>
                            <td> <?php echo $value['item_reorder_no']; ?> </td>
                            <td> <?php echo $value['item_unit_measure']; ?> </td>
                            <td class="item_cost_td"> <?php echo number_format($value['item_cost'], 2); ?> </td>

                            <?php
                                if ($value['item_status'] == "0") {
                                    ?>
                                    <td>
                                        <?php
                                            if ($ordered_item_list_received[$value['item_id']] > 0) {
                                                $readonly = "readonly";
                                                echo "<input type='hidden' class='item_edit_sign_".$value['item_id']."' value='1'>";
                                            } else {
                                                $readonly = "";
                                                echo "<input type='hidden' class='item_edit_sign_".$value['item_id']."' value='0'>";
                                            }
                                    $item_remaining = $value['req_item_quantity_ordered'] - $ordered_item_list_received[$value['item_id']];
                                    echo "<input type='hidden' class='item_remaining_value_".$value['item_id']."' value='".$item_remaining."'>"; ?>
                                        <input
                                            type="text"
                                            name=""
                                            class="form-control input_quantity_ordered disabled_edit_input qty_ordered_<?php echo $value['item_id']; ?>"
                                            value="<?php echo $ordered_item_list_received[$value['item_id']]."/".$value['req_item_quantity_ordered']; ?>" data-item-id="<?php echo $value['item_id']; ?>"
                                            <?php echo $readonly; ?>
                                        >
                                        <input
                                            type="hidden"
                                            name="order_inquiry[<?php echo $value['item_id']; ?>][item_quantity]"
                                            class="form-control input_quantity_ordered_hidden qty_ordered_hidden_<?php echo $value['item_id']; ?>"
                                            value="<?php echo $value['req_item_quantity_ordered']; ?>"
                                        >
                                        <input
                                            type="hidden"
                                            name="order_inquiry[<?php echo $value['item_id']; ?>][item_cost]"
                                            value="<?php echo $value['item_cost']; ?>"
                                        >
                                        <input
                                            type="hidden"
                                            name="order_inquiry[<?php echo $value['item_id']; ?>][item_total_cost]"
                                            value="<?php echo $value['item_total_cost']; ?>"
                                        >
                                        <input
                                            type="hidden"
                                            name="order_inquiry[<?php echo $value['item_id']; ?>][item_batch_no]"
                                            value="<?php echo $value['item_batch_no']; ?>"
                                        >
                                        <input
                                            type="hidden"
                                            name="order_inquiry[<?php echo $value['item_id']; ?>][item_unit_measure]"
                                            value="<?php echo $value['item_unit_measure']; ?>"
                                        >
                                    </td>
                                    <td>
                                        <input type="text" name="order_inquiry[<?php echo $value['item_id']; ?>][item_quantity_received]" class="form-control qty_received_input qty_received_<?php echo $value['item_id']; ?>" value="" data-item-id="<?php echo $value['item_id']; ?>" data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>">
                                    </td>
                                    <input type="hidden" name="order_inquiry[<?php echo $value['item_id']; ?>][req_receiving_id]" value="<?php echo $value['req_receiving_id']; ?>">
                                    <input type="hidden" class="item_status_<?php echo $value['item_id']; ?>" name="order_inquiry[<?php echo $value['item_id']; ?>][item_status]" value="0">
                                    <input type="hidden" class="req_item_quantity_received_<?php echo $value['item_id']; ?>" value="<?php echo $value['req_item_quantity_received']; ?>">
                                    <input type="hidden" class="req_item_quantity_unit_measure_<?php echo $value['item_id']; ?>" value="<?php echo $value['item_unit_measure']; ?>">
                                    <td>
                                        <input type="hidden" name="order_inquiry[<?php echo $value['item_id']; ?>][item_total_cost]" class="item_total_cost_input_<?php echo $value['item_id']; ?>" type="order_inquiry[<?php echo $value['item_id']; ?>][item_total_cost]" value="<?php echo number_format($value['item_total_cost'], 2); ?>">
                                        <span class="total_cost_span item_total_cost_<?php echo $value['item_id']; ?>"><?php echo number_format($value['item_total_cost'], 2); ?> </span>
                                    </td>
                                    <td>
                                        <?php
                                            $disable_item_cancelling = "";
                                            $disable_item_style = "";
                                    if ($ordered_item_list_received[$value['item_id']] != 0) {
                                        $disable_item_cancelling = "disabled checked";
                                        $disable_item_style = "style='cursor:default;'";
                                    } ?>
                                        <label class="i-checks data_tooltip" title="Cancel Item">
                                            <input
                                                type="checkbox"
                                                name=""
                                                class="cancel_inventory_item"
                                                data-item-id="<?php echo $value['item_id']; ?>"
                                                data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
                                                data-item-total-cost="<?php echo $value['item_total_cost']; ?>"
                                                <?php echo $disable_item_cancelling; ?>
                                            />
                                            <i></i>
                                        </label>
                                    </td>
                            <?php
                                } else {
                                    ?>
                                    <td>
                                        <?php
                                            if ($ordered_item_list_received[$value['item_id']] > 0) {
                                                $readonly = "readonly";
                                                echo "<input type='hidden' class='item_edit_sign_".$value['item_id']."'' value='1'>";
                                            } else {
                                                $readonly = "";
                                                echo "<input type='hidden' class='item_edit_sign_".$value['item_id']."' value='0'>";
                                            }
                                    $item_remaining = $value['req_item_quantity_ordered'] - $ordered_item_list_received[$value['item_id']];
                                    echo "<input type='hidden' class='item_remaining_value_".$value['item_id']."' value='".$item_remaining."'>"; ?>
                                        <input
                                            type="text"
                                            class="form-control input_quantity_ordered qty_ordered_<?php echo $value['item_id']; ?>"
                                            value="<?php echo $ordered_item_list_received[$value['item_id']]."/".$value['req_item_quantity_ordered']; ?>" data-item-id="<?php echo $value['item_id']; ?>"
                                            <?php echo $readonly; ?>
                                        >
                                    </td>
                                    <td>
                                    <input type="text" class="form-control qty_received_input qty_received_<?php echo $value['item_id']; ?>" value="" data-item-id="<?php echo $value['item_id']; ?>" data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>" readonly>
                                    </td>
                                    <input type="hidden" name="order_inquiry[<?php echo $value['item_id']; ?>][item_quantity_received]" value="<?php echo $value['req_receiving_id']; ?>">
                                    <input type="hidden" class="item_status_<?php echo $value['item_id']; ?>" name="order_inquiry[<?php echo $value['item_id']; ?>][item_status]" value="1">
                                    <input type="hidden" class="req_item_quantity_received_<?php echo $value['item_id']; ?>" value="<?php echo $value['req_item_quantity_received']; ?>">
                                    <input
                                        type="hidden"
                                        name="order_inquiry[<?php echo $value['item_id']; ?>][item_cost]"
                                        value="<?php echo $value['item_cost']; ?>"
                                    >
                                    <input
                                        type="hidden"
                                        name="order_inquiry[<?php echo $value['item_id']; ?>][item_total_cost]"
                                        value="<?php echo $value['item_total_cost']; ?>"
                                    >
                                    <input
                                        type="hidden"
                                        name="order_inquiry[<?php echo $value['item_id']; ?>][item_batch_no]"
                                        value="<?php echo $value['item_batch_no']; ?>"
                                    >
                                    <input
                                        type="hidden"
                                        name="order_inquiry[<?php echo $value['item_id']; ?>][item_unit_measure]"
                                        value="<?php echo $value['item_unit_measure']; ?>"
                                    >
                                    <input type="hidden" class="req_item_quantity_unit_measure_<?php echo $value['item_id']; ?>" value="<?php echo $value['item_unit_measure']; ?>">
                                    <td>
                                        <input type="hidden" name="order_inquiry[<?php echo $value['item_id']; ?>][item_total_cost]" class="item_total_cost_input_<?php echo $value['item_id']; ?>" type="order_inquiry[<?php echo $value['item_id']; ?>][item_total_cost]" value="<?php echo number_format($value['item_total_cost'], 2); ?>">
                                        <span class="total_cost_span item_total_cost_<?php echo $value['item_id']; ?>"><?php echo number_format($value['item_total_cost'], 2); ?> </span>
                                    </td>
                                    <td>
                                        <label class="i-checks data_tooltip" title="Cancel Item">
                                            <input
                                                type="checkbox"
                                                name=""
                                                class="cancel_inventory_item"
                                                data-item-id="<?php echo $value['item_id']; ?>"
                                                data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
                                                data-item-total-cost="<?php echo $value['item_total_cost']; ?>"
                                                checked
                                            />
                                            <i></i>
                                        </label>
                                    </td>
            <?php
                                } ?>
                            </tr>
            <?php
                        }
                    }
                }
            ?>
            </tbody>
        </table>

        <div class="col-md-12" style="padding:0px;">
            <div class="col-sm-3 col-sm-offset-6" style="">
                <label style="margin-left:5px;">Amount<span class="text-danger-dker">*</span></label>
                <input type="text" class="form-control order_req_amount_input" name="order_req_amount" style="margin-bottom:5px" value="<?php echo number_format($order_req_details['order_req_amount'], 2); ?>">
            </div>
            <div class="col-sm-3" style="">
                <label style="margin-left:5px;">Received Date<span class="text-danger-dker">*</span></label>
                <input type="text" class="form-control datepicker" name="order_req_received_date" style="margin-bottom:5px" value="">
            </div>
        </div>
        <div class="col-md-12" style="padding:0px;">
            <div class="col-sm-3 col-sm-offset-6" style="">
                <label style="margin-left:5px;">Shipping Cost<span class="text-danger-dker">*</span></label>
                <input type="text" class="form-control order_req_shipping_cost" name="order_req_shipping_cost" style="margin-bottom:5px" value="<?php echo number_format($order_req_details['order_req_shipping_cost'], 2); ?>">
            </div>
            <div class="col-sm-3" style="padding-left:20px;padding-right:10px;">
                <label style="margin-left:5px;">Staff Member Receiving PO Req.<span class="text-danger-dker">*</span></label>
                <input type="text" class="form-control" name="order_req_staff_member_receiving" style="margin-bottom:5px" value="">
            </div>
        </div>
        <div class="col-md-12" style="padding:0px;">
            <div class="col-sm-3 col-sm-offset-6" style="">
                <label style="margin-left:5px;">Total<span class="text-danger-dker">*</span></label>
                <input type="text" class="form-control order_req_grand_total_input" name="order_req_grand_total" style="margin-bottom:20px" value="<?php echo number_format($order_req_details['order_req_grand_total'], 2); ?>">
            </div>
        </div>
        <div class="col-md-12" style="padding:0px;padding-right:11px;">
            <div class="pull-right">
                <button type="button" class="btn btn-danger pull-right data_tooltip close_order_req_receiving" onclick="closeModalbox()">Close</button>
                <button type="button" class="btn btn-success pull-right confirm_order_requisition" data-order-req-id="<?php echo $order_req_details['order_req_id']; ?>" data-purchase-order-no="<?php echo $order_req_details['purchase_order_no']; ?>" style="margin-right:10px;">Submit Req.</button>
            </div>
        </div>
    </div>
</div>
<?php
    echo form_close();
endif;
?>

<script type="text/javascript">

    $(document).ready(function(){

        $('.order_req_date_receiving').datepicker({
            dateFormat: 'mm/dd/yy'
        });

        $('.qty_received_input').bind('keyup',function(){
            var value_here = $(this).val();
            var _this = $(this);
            var item_id = _this.attr("data-item-id");
            var purchase_order_no = _this.attr("data-purchase-order-no");
            var temp = "";
            var qty_received = $(".qty_received_"+item_id).val();
            var qty_ordered = $(".qty_ordered_hidden_"+item_id).val();
            var req_receiving_batch_no = $(".req_receiving_batch_no").val();
            var item_count = 0;
            var item_remaining = $(".item_remaining_value_"+item_id).val();
            var item_edit_sign = $(".item_edit_sign_"+item_id).val();
            var selected_item_unit_measure = $(".req_item_quantity_unit_measure_"+item_id).val();

            setTimeout(function(){
                if(value_here != "" && value_here != 0)
                {
                    if(Number(qty_ordered) >= Number(qty_received))
                    {
                        if(Number(item_edit_sign) == 0)
                        {
                            $.post(base_url+'inventory/add_serial_asset_no_v2/'+ purchase_order_no + "/"+ item_id+"/" + "/"+ qty_received+"/"+ selected_item_unit_measure,"", function(response){
                                var obj = $.parseJSON(response);

                                temp += '<div class="form-group" style="text-align: center;font-weight: bold; margin-bottom:0px !important;padding-bottom:10px; height:20px;">'+
                                            '<div class="col-sm-6">'+
                                                'Serial No.'+
                                            '</div>'+
                                            '<div class="col-sm-6">'+
                                                'Asset No.'+
                                            '</div>'+
                                        '</div>';

                                for(var i = 1; i <= obj.item_quantity_ordered; i++)
                                {
                                    temp += '<div class="form-group" style="padding-top: 10px !important;height: 40px;">'+
                                                '<div class="col-sm-6">'+
                                                    '<input type="text" class="form-control add_item_serial_no_receiving" value="" name="newserial_'+i+'">'+
                                                '</div>'+
                                                '<div class="col-sm-6">'+
                                                    '<input type="text" class="form-control add_item_asset_no_receiving" value="" name="newasset_'+i+'">'+
                                                '</div>'+
                                            '</div>';
                                }

                                $("#serial_asset_no_modal").find(".serial_asset_no_modal_content").html(temp);
                                $('#serial_asset_no_modal').modal("show");
                            });

                            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").attr('data-purchase-order-no',purchase_order_no);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").attr('data-item-id',item_id);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").attr('data-req-receiving-batch-no',req_receiving_batch_no);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").attr('data-qty-ordered',qty_ordered);

                            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").attr('data-purchase-order-no',purchase_order_no);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").attr('data-item-id',item_id);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").attr('data-req-receiving-batch-no',req_receiving_batch_no);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").attr('data-qty-ordered',qty_ordered);
                        }
                        else if(Number(item_edit_sign) != 0 && Number(qty_received) <= Number(item_remaining))
                        {
                            $.post(base_url+'inventory/add_serial_asset_no_v2/'+ purchase_order_no + "/"+ item_id+"/" + "/"+ qty_received+"/"+ selected_item_unit_measure,"", function(response){
                                var obj = $.parseJSON(response);

                                temp += '<div class="form-group" style="text-align: center;font-weight: bold; margin-bottom:0px !important;padding-bottom:10px; height:20px;">'+
                                            '<div class="col-sm-6">'+
                                                'Serial No.'+
                                            '</div>'+
                                            '<div class="col-sm-6">'+
                                                'Asset No.'+
                                            '</div>'+
                                        '</div>';

                                for(var i = 1; i <= obj.item_quantity_ordered; i++)
                                {
                                    temp += '<div class="form-group" style="padding-top: 10px !important;height: 40px;">'+
                                                '<div class="col-sm-6">'+
                                                    '<input type="text" class="form-control add_item_serial_no_receiving" value="" name="newserial_'+i+'">'+
                                                '</div>'+
                                                '<div class="col-sm-6">'+
                                                    '<input type="text" class="form-control add_item_asset_no_receiving" value="" name="newasset_'+i+'">'+
                                                '</div>'+
                                            '</div>';
                                }

                                $("#serial_asset_no_modal").find(".serial_asset_no_modal_content").html(temp);
                                $('#serial_asset_no_modal').modal("show");
                            });

                            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").attr('data-purchase-order-no',purchase_order_no);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").attr('data-item-id',item_id);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").attr('data-req-receiving-batch-no',req_receiving_batch_no);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").attr('data-qty-ordered',qty_ordered);

                            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").attr('data-purchase-order-no',purchase_order_no);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").attr('data-item-id',item_id);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").attr('data-req-receiving-batch-no',req_receiving_batch_no);
                            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").attr('data-qty-ordered',qty_ordered);
                        }
                        else
                        {
                            me_message_v2({error:1,message:" Item quantity received exceeds item quantity remaining."});
                            $('body').find(".close_serial_asset_no").click();
                        }
                    }
                    else
                    {
                        me_message_v2({error:1,message:" Item quantity received exceeds item quantity ordered. "});
                        $('body').find(".close_serial_asset_no").click();
                    }
                }
            },1000);
        });

        $('body').on('click','.save_serial_asset_no',function(){
            var form_data = $('#save_serial_asset_no_form').serialize();
            var purchase_order_no = $(this).attr("data-purchase-order-no");
            var item_id = $(this).attr("data-item-id");
            var req_receiving_batch_no = $(this).attr("data-req-receiving-batch-no");
            var qty_ordered = $(this).attr("data-qty-ordered");
            var quantity_ordered = Number(qty_ordered);

            $.post(base_url+"inventory/save_serial_asset_no/"+ purchase_order_no + "/"+ item_id+"/"+ req_receiving_batch_no+"/"+ quantity_ordered+"/",form_data, function(response){
                var obj = $.parseJSON(response);
                jAlert(obj['message'],"Reminder");
                if(obj['error'] == 0)
                {
                    setTimeout(function(){
                        $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-purchase-order-no');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-item-id');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-req-receiving-batch-no');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-qty-ordered');

                        $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-purchase-order-no');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-item-id');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-req-receiving-batch-no');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-qty-ordered');

                        $('body').find(".close_serial_asset_no").click();
                    },1500);
                }
            });
        });

        $('body').on('click','.skip_serial_asset_no',function(){
            var form_data = $('#save_serial_asset_no_form').serialize();
            var purchase_order_no = $(this).attr("data-purchase-order-no");
            var item_id = $(this).attr("data-item-id");
            var req_receiving_batch_no = $(this).attr("data-req-receiving-batch-no");
            var qty_ordered = $(this).attr("data-qty-ordered");
            var quantity_ordered = Number(qty_ordered);

            $.post(base_url+"inventory/skip_serial_asset_no/"+ purchase_order_no + "/"+ item_id+"/"+ req_receiving_batch_no+"/"+ quantity_ordered+"/",form_data, function(response){
                var obj = $.parseJSON(response);
                jAlert(obj['message'],"Reminder");
                if(obj['error'] == 0)
                {
                    setTimeout(function(){
                        $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-purchase-order-no');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-item-id');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-req-receiving-batch-no');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-qty-ordered');

                        $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-purchase-order-no');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-item-id');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-req-receiving-batch-no');
                        $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-qty-ordered');

                        $('body').find(".close_serial_asset_no").click();
                    },1500);
                }
            });
        });

        $('body').on('click','.close_serial_asset_no',function(){
            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-purchase-order-no');
            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-item-id');
            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-req-receiving-batch-no');
            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-qty-ordered');

            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-purchase-order-no');
            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-item-id');
            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-req-receiving-batch-no');
            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-qty-ordered');
        });

        $('#serial_asset_no_modal').on('click','.close',function(){
            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-purchase-order-no');
            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-item-id');
            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-req-receiving-batch-no');
            $('#serial_asset_no_modal').find(".modal-footer").find(".save_serial_asset_no").removeAttr('data-qty-ordered');

            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-purchase-order-no');
            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-item-id');
            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-req-receiving-batch-no');
            $('#serial_asset_no_modal').find(".modal-footer").find(".skip_serial_asset_no").removeAttr('data-qty-ordered');
        });

        $('body').on('keyup','#serial_asset_no_modal .add_item_serial_no_receiving',function(){
            var all_filed_up = 1;
            $('body .add_item_asset_no_receiving').each(function(){
                if($(this).val() == ""){
                    all_filed_up = 0;
                }
            });

            $('body .add_item_serial_no_receiving').each(function(){
                if($(this).val() == ""){
                    all_filed_up = 0;
                }
            });
            if(all_filed_up == 1)
            {
                var item_serial_no_duplicate_sign = $("body").find("#serial_asset_no_modal .duplicate_asset_no").val();
                if(item_serial_no_duplicate_sign == 0)
                {
                    $("body").find(".save_serial_asset_no").removeAttr("disabled");
                }
            }
            else
            {
                $("body").find(".save_serial_asset_no").prop("disabled","disabled");
            }
        });

        var globalTimeout = null;
        $('body').on('keyup','#serial_asset_no_modal .add_item_asset_no_receiving',function(){

            if(globalTimeout != null) clearTimeout(globalTimeout);
            globalTimeout =setTimeout(getInfoFunc,1100);

            function getInfoFunc(){
                globalTimeout = null;

                var count_loop_each_sign = 0;
                $('body .add_item_asset_no_receiving').each(function(){
                    var item_asset_no_value = $(this).val();

                    $.post(base_url+"inventory/check_item_asset_no_value/"+ item_asset_no_value,"", function(response){
                        var obj = $.parseJSON(response);

                        if(obj.item_asset_no_value.inventory_item_id != undefined)
                        {
                            $("body").find(".save_serial_asset_no").prop("disabled","disabled");
                            // $("body").find(".skip_serial_asset_no").prop("disabled","disabled");
                            me_message_v2({error:1,message:"Asset No. already exists."});
                            $("body").find("#serial_asset_no_modal .duplicate_asset_no").val(1);

                            count_loop_each_sign++;
                        }
                        else
                        {
                            if(count_loop_each_sign == 0)
                            {
                                $("body").find("#preloader-message").fadeOut(10);
                                var all_filed_up = 1;
                                $('body .add_item_asset_no_receiving').each(function(){
                                    if($(this).val() == ""){
                                        all_filed_up = 0;
                                    }
                                });

                                $('body .add_item_serial_no_receiving').each(function(){
                                    if($(this).val() == ""){
                                        all_filed_up = 0;
                                    }
                                });
                                if(all_filed_up == 1)
                                {
                                    $("body").find("#serial_asset_no_modal .duplicate_asset_no").val(0);
                                    $("body").find(".save_serial_asset_no").removeAttr("disabled");
                                    // $("body").find(".skip_serial_asset_no").removeAttr("disabled");
                                }
                            }
                        }
                    });
                });
            }
        });

        $('.input_quantity_ordered').bind('keyup',function(){
            var _this = $(this);
            var content = $(this).val();
            var temp_content = content.split("/");
            var value_here = temp_content[1];
            var item_id = _this.attr("data-item-id");
            var total = 0;
            var grand_total = "";
            var shipping_cost = $(".order_req_shipping_cost").val();

            setTimeout(function(){
                if(value_here != "" && value_here != 0)
                {
                    var item_cost = _this.parent("td").siblings(".item_cost_td").text();
                    var item_total_cost = Number(item_cost)*value_here;
                    $(".item_total_cost_"+item_id).html(item_total_cost.toFixed(2));
                    $(".item_total_cost_input_"+item_id).html(item_total_cost.toFixed(2));
                    $(".item_total_cost_input_"+item_id).val(item_total_cost.toFixed(2));

                    $('.total_cost_span').each(function(){
                        total += Number($(this).text())
                    });
                    grand_total = Number(total)+Number(shipping_cost);

                    $(".order_req_amount_input").val(total.toFixed(2));
                    $(".order_req_grand_total_input").val(grand_total.toFixed(2));
                    $(".qty_ordered_hidden_"+item_id).val(value_here);
                }
            },1000);
        });

        $('body').on('click','.cancel_inventory_item',function(){
            var _this = $(this);
            var item_id = $(this).attr('data-item-id');
            var purchase_order_no = $(this).attr('data-purchase-order-no');
            var item_total_cost = $(this).data('item-total-cost');
            var total = $(".order_req_amount_input").val();
            var grand_total = $(".order_req_grand_total_input").val();

            if(_this.is(":checked"))
            {
                _this.parents("tr").css('text-decoration','line-through');
                $("body").find('.qty_ordered_'+item_id).attr("readonly","readonly");
                $("body").find('.qty_received_'+item_id).attr("readonly","readonly");
                $("body").find('.back_order_received_'+item_id).attr("readonly","readonly");
                $("body").find('.add_serial_asset_no_button_'+item_id).attr("disabled","disabled");

                $.post(base_url+'inventory/cancel_inventory_item/'+ purchase_order_no + "/"+ item_id+"/","", function(response){
                    var obj = $.parseJSON(response);

                    if(obj == true)
                    {
                        $(".order_req_amount_input").val((total-item_total_cost).toFixed(2));
                        $(".order_req_grand_total_input").val((grand_total-item_total_cost).toFixed(2));
                        $("body").find(".item_status_"+item_id).val(1);
                        me_message_v2({error:0,message:"Item Cancelled."});
                    }
                    else
                    {
                        me_message_v2({error:1,message:"Error Cancelling."});
                    }
                });
            }
            else
            {
                _this.parents("tr").css('text-decoration','none');
                $("body").find('.qty_ordered_'+item_id).attr("readonly",false);
                $("body").find('.qty_received_'+item_id).attr("readonly",false);
                $("body").find('.back_order_received_'+item_id).attr("readonly",false);
                $("body").find('.add_serial_asset_no_button_'+item_id).removeAttr("disabled");

                $.post(base_url+'inventory/retrieve_inventory_item/'+ purchase_order_no + "/"+ item_id+"/","", function(response){
                    var obj = $.parseJSON(response);

                    if(obj == true)
                    {
                        $(".order_req_amount_input").val((Number(total)+Number(item_total_cost)).toFixed(2));
                        $(".order_req_grand_total_input").val((Number(grand_total)+Number(item_total_cost)).toFixed(2));
                        $("body").find(".item_status_"+item_id).val(0);
                        me_message_v2({error:0,message:"Item Retrieved."});
                    }
                    else
                    {
                        me_message_v2({error:1,message:"Error Retrieving."});
                    }
                });
            }
        });

        $('body').on('click','.confirm_order_requisition',function(){
            var _this = $(this);
            var form_data = $('.order_requisition_form').serialize();
            var purchase_order_no = $(this).attr('data-purchase-order-no');
            var order_req_id = $(this).attr('data-order-req-id');

            $.post(base_url+"inventory/confirm_order_requisition/"+ purchase_order_no + "/"+ order_req_id +"/",form_data, function(response){
                var obj = $.parseJSON(response);
                jAlert(obj['message'],"Reminder");
                if(obj['error'] == 0)
                {
                    setTimeout(function(){
                        location.reload();
                    },1500);
                }
            });
        });

    });

</script>
