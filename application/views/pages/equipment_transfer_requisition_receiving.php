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
if (!empty($transfer_req_details)):
    echo form_open('', array('class' => 'order_requisition_form'));
?>

<input type="hidden" class="req_receiving_batch_no" name="req_receiving_batch_no" value="<?php echo $order_req_details['req_receiving_batch_no']; ?>">
<div class="row">
    <div class="">

        <div class="form-group clearfix" style="margin-bottom:0px !important;">
            <div class="col-sm-3" style="">
                <label>Date <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control order_req_date_receiving" name="order_req_date" value="<?php echo date("Y-m-d", strtotime($transfer_req_details['equip_transfer_date'])); ?>">
                </div>
            </div>
            <div class="col-sm-6 col-sm-offset-3" style="">
            </div>
        </div>
        <div class="form-group clearfix" style="margin-bottom:0px !important;">
            <div class="col-sm-6" style="">
                <label>Receiving Location <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <?php
                    $location_receiving = get_login_location($transfer_req_details['receiving_location']);
                ?>
                <div class="form-group" style="">
                    <input type="text"  class="form-control disabled_edit_input" name="receiving_location" value="<?php echo $location_receiving['user_city'].', '.$location_receiving['user_state']; ?>" readonly>
                </div>
            </div>
            <div class="col-sm-6" style="">
                <label>Transferring Location <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <?php
                        $location = get_login_location($transfer_req_details['transferring_location']);
                    ?>
                    <input type="text"  class="form-control" name="location" value="<?php echo $location['user_city'].', '.$location['user_state']; ?>">
                </div>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-bottom:10px !important;">
            <div class="col-sm-6" style="">
                <label>Representative Created Order <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control disabled_edit_input" name="person_placing_order" value="<?php echo $transfer_req_details['person_placing_order']; ?>" readonly>
                </div>
            </div>
            <div class="col-sm-6" style="">
                <label>Transfer PO No. <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control disabled_edit_input" name="transfer_po_no" value="<?php echo substr($transfer_req_details['transfer_po_no'], 3, 10);?>" readonly>
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
                if (!empty($transfer_req_receiving_details)) {
                    $i = 1;
                    foreach ($transfer_req_receiving_details as $key => $value) {
                        $received_row = "";
                        if($value['req_received_date'] != "0000-00-00") {
                            // $received_row = "background-color:rgba(93, 87, 87, 0.07);";
                        } else {
                            $received_row = "";
                        }
                        $inactive_class = "";
                        if ($value['item_status'] == "0") {
                            echo "<tr style='".$received_row."' class='".$inactive_class."'>";
                        } else {
                            echo "<tr class='".$inactive_class."' style='text-decoration:line-through;'>";
                        }
            ?>

                            <td> <?php echo $value['transfer_req_items'][0]['company_item_no']; ?> </td>
                            <td> <?php echo $value['transfer_req_items'][0]['item_description']; ?> </td>
                            <td> <?php echo $value['transfer_req_items'][0]['item_reorder_no']; ?> </td>
                            <td> <?php echo $value['item_unit_measure']; ?> </td>
                            <td class="item_cost_td"> <?php echo number_format($value['item_cost'], 2); ?> </td>
                            <td>
                                <input
                                    style="background-color: white"
                                    type="text"
                                    class="form-control input_quantity_ordered qty_ordered_<?php echo $value['item_id']; ?> qty_input_ordered_<?php echo $i; ?>"
                                    value="<?php echo '0'.'/'.$value['req_item_quantity_ordered']; ?>" data-item-id="<?php echo $value['item_id']; ?>"
                                    readonly
                                >
                            </td>
                            <td>
                                <div class="row">
                                    <?php
                                        if($value['item_status'] == "0") {
                                            $disabled = "";
                                            $cancel_button_style = "";
                                        } else {
                                            $disabled = "disabled";
                                            $cancel_button_style = "background-color:rgba(93, 87, 87, 0.07) !important;";
                                        }

                                        $cancel_button_style = "";
                                        if($value['req_received_date'] != "0000-00-00") {
                                            $checked = "checked";
                                            $qtyreceived_val = $value['req_item_quantity_ordered'];
                                            $disabled = "disabled";
                                            $cancel_button_style = "background-color:rgba(93, 87, 87, 0.07) !important;";
                                        } else {
                                            $checked = "";
                                            $qtyreceived_val = "";
                                        }
                                    ?>
                                    <div class="col-xs-8 col-sm-8 col-md-8">
                                        <input style="background-color: white; cursor: pointer !important; " type="text" class=" form-control qty_received_input qty_received_<?php echo $value['item_id']; ?> qty_receiving_<?php echo $i; ?>" value="<?php echo $qtyreceived_val ?>" data-item-id="<?php echo $value['item_id']; ?>"
                                            data-transfer-order-no="<?php echo $transfer_req_details['transfer_po_no']; ?>"
                                            data-transfer-req-id="<?php echo $value['transfer_req_id']; ?>"
                                            data-serial_nos="<?php echo $value['serial_nos']; ?>"
                                            data-asset_nos="<?php echo $value['asset_nos']; ?>"
                                            name="qty_received[]"
                                            readonly>

                                    </div>

                                    <label class="col-xs-4 col-sm-4 col-md-4 i-checks data_tooltip" title="Cancel Item">

                                        <input
                                            type="checkbox"
                                            name=""

                                            class="receive_inventory_item receive_inv_item_<?php echo $i; ?>"
                                            data-row-id="<?php echo $i; ?>"
                                            data-item-id="<?php echo $value['item_id']; ?>"
                                            data-transfer-order-no="<?php echo $transfer_req_details['transfer_po_no']; ?>"
                                            data-transfer-req-id="<?php echo $value['transfer_req_id']; ?>"
                                            data-asset_nos="<?php echo $value['asset_nos']; ?>"
                                            data-qty-ordered="<?php echo $value['req_item_quantity_ordered']; ?>"
                                            data-item-status="<?php echo $value['item_status'];?>"
                                            <?php echo $checked ?>
                                            <?php echo $disabled ?>
                                        />
                                        <i style="<?php echo $cancel_button_style ?>"
                                            data-qty-ordered="<?php echo $value['req_item_quantity_ordered']; ?>"
                                            rel="popover"
                                            data-html="true"
                                            data-toggle="popover"
                                            data-trigger="hover"
                                            data-content="Receive Item"
                                            data-placement="right"
                                            ></i>
                                    </label>
                                </div>

                            </td>

                            <input type="hidden" name="etr_asset_nos[]" value="<?php echo $value['asset_nos']; ?>">
                            <input type="hidden" name="etr_inventory_item_ids[<?php echo $value['item_id']; ?>]" value="<?php echo $value['inventory_item_ids']; ?>">
                            <input type="hidden" name="etr_old_inventory_item_ids[<?php echo $value['item_id']; ?>]" value="<?php echo $value['old_inventory_item_ids']; ?>">
                            <input type="hidden" name="etr_receiving_id[]" value="<?php echo $value['transfer_req_receiving_id'] ?>">
                            <input type="hidden" name="order_inquiry[<?php echo $value['item_id']; ?>][item_quantity_received]" value="<?php echo $value['req_receiving_id']; ?>">

                            <input type="hidden" name="item_status_<?php echo $i; ?>" class="item_status_<?php echo $i; ?>" data-row-id="<?php echo $i; ?>" value="<?php echo $value['item_status']; ?>">

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
                                <?php
                                    if($value['req_received_date'] != "0000-00-00") {
                                        $disabled = "disabled";
                                        $cancel_button_style = "background-color:rgba(93, 87, 87, 0.07) !important;";
                                    } else {
                                        $disabled = "";
                                        $cancel_button_style = "";
                                    }
                                    $checked = "";

                                    if($value['item_status'] == "0") {
                                        $checked = "";
                                    } else {
                                        $checked = "checked";
                                    }

                                ?>
                                <label class="i-checks data_tooltip" title="Cancel Item">
                                    <input
                                        type="checkbox"
                                        name=""
                                        class="cancel_inventory_item cancel_inv_item_<?php echo $i; ?>"
                                        data-row-id = "<?php echo $i; ?>"
                                        data-item-id="<?php echo $value['item_id']; ?>"
                                        data-transfer-req-receiving-id="<?php echo $value['transfer_req_receiving_id']; ?>"
                                        data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
                                        data-item-total-cost="<?php echo $value['item_total_cost']; ?>"
                                        <?php echo $disabled ?>
                                        <?php echo $checked ?>
                                    />
                                    <i
                                    data-row-id = "<?php echo $i; ?>"
                                    style="<?php echo $cancel_button_style?>"></i>
                                </label>
                            </td>
                        </tr>
            <?php
                        $i++;
                    }
                }
            ?>
            </tbody>
        </table>
        <input type="hidden" name="transferring_location" value="<?php echo $transfer_req_details['transferring_location']; ?>">
        <input type="hidden" name="transfer_req_id" value="<?php echo $transfer_req_details['transfer_req_id']; ?>">
        <div class="col-md-12" style="padding:0px;">
            <div class="col-sm-3 col-sm-offset-6" style="">
                <!-- <label style="margin-left:5px;">Amount<span class="text-danger-dker">*</span></label>
                <input type="text" class="form-control order_req_amount_input" name="order_req_amount" style="margin-bottom:5px" value="<?php echo number_format($order_req_details['order_req_amount'], 2); ?>"> -->
            </div>
            <div class="col-sm-3" style="">
                <label style="margin-left:5px;">Received Date<span class="text-danger-dker">*</span></label>
                <input type="text" class="form-control datepicker" name="order_req_received_date" style="margin-bottom:5px" value="">
            </div>
        </div>
        <div class="col-md-12" style="padding:0px;">
            <div class="col-sm-3 col-sm-offset-6" style="">
                <!-- <label style="margin-left:5px;">Shipping Cost<span class="text-danger-dker">*</span></label>
                <input type="text" class="form-control order_req_shipping_cost" name="order_req_shipping_cost" style="margin-bottom:5px" value="<?php echo number_format($order_req_details['order_req_shipping_cost'], 2); ?>"> -->
            </div>
            <div class="col-sm-3" style="padding-left:20px;padding-right:10px;">
                <label style="margin-left:5px;">Representative Receiving PO Req<span class="text-danger-dker">*</span></label>
                <input type="text" class="form-control" name="order_req_staff_member_receiving" style="margin-bottom:5px" value="">
            </div>
        </div>
        <div class="col-md-12" style="padding:0px;">
            <div class="col-sm-3 col-sm-offset-6" style="">
            </div>
            <div class="col-sm-3" style="padding-left:20px;padding-right:10px;">
                <label style="margin-left:5px;">Total<span class="text-danger-dker">*</span></label>
                <?php
                if (!empty($transfer_req_receiving_details)) {
                    $equip_req_grand_total = $transfer_req_details['equip_req_grand_total'];
                    foreach ($transfer_req_receiving_details as $key => $value) {
                        if ($value['item_status'] == "1") {
                            $equip_req_grand_total = $equip_req_grand_total - $value['item_total_cost'];
                        }
                    }
                }
                ?>
                <input type="text" class="form-control order_req_grand_total_input" name="order_req_grand_total" style="margin-bottom:20px" value="<?php echo number_format($equip_req_grand_total, 2); ?>">
            </div>
        </div>
        <div class="col-md-12" style="padding:0px;padding-right:11px;">
            <div class="pull-right">
                <button type="button" class="btn btn-danger pull-right data_tooltip close_order_req_receiving" onclick="closeModalbox()">Close</button>
                <button type="button" class="btn btn-success pull-right confirm_equipment_transfer_order_requisition" data-order-req-id="<?php echo $order_req_details['order_req_id']; ?>" data-purchase-order-no="<?php echo $order_req_details['purchase_order_no']; ?>" style="margin-right:10px;">Submit Req.</button>
            </div>
        </div>
    </div>
</div>
<?php
    echo form_close();
endif;
?>

<div class="modal fade" id="serial_asset_no_modal_etr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10010;">
    <div class="modal-dialog" style="top: 100px;left: 345px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Serial & Asset No.</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('', array('id' => 'save_serial_asset_no_form')); ?>
                    <div class="serial_asset_no_modal_content">

                    </div>
                <?php echo form_close(); ?>
            </div>
            <div class="modal-footer" style="padding-left: 30px;padding-right: 30px;">
                <input type="hidden" value="0" class="duplicate_asset_no">
                <!-- <button type="button" class="btn btn-default skip_serial_asset_no pull-left"> Skip </button>
                <button type="button" class="btn btn-success save_serial_asset_no" disabled> Save Changes </button> -->
                <button type="button" class="btn btn-danger close_serial_asset_no" data-dismiss="modal"> Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

    $(document).ready(function(){

        $('.order_req_date_receiving').datepicker({
            dateFormat: 'mm/dd/yy'
        });

        //receive_inventory_item_
        $('body').on('click','.receive_inventory_item',function(){
            var _this = $(this);
            var row_id = _this.attr("data-row-id");
            var qty_ordered = _this.attr("data-qty-ordered");

            if(_this.is(':checked')) {
                $('body').find('.cancel_inv_item_'+row_id).attr("disabled","disabled");
                $('body').find('.qty_receiving_'+row_id).val(qty_ordered);
                $('body').find('.qty_input_ordered_'+row_id).val(qty_ordered.toString()+"/"+qty_ordered.toString());
            } else {
                $('body').find('.cancel_inv_item_'+row_id).removeAttr("disabled");
                $('body').find('.qty_receiving_'+row_id).val("");
                $('body').find('.qty_input_ordered_'+row_id).val("0/"+qty_ordered.toString());
            }

        });

        //qty_received_input
        $('body').on('click','.qty_received_input',function(){
            var _this = $(this);
            var serial_nos = _this.attr("data-serial_nos").split(",");
            var asset_nos = _this.attr("data-asset_nos").split(",");
            var temp = "";
            temp += '<div class="form-group" style="text-align: center;font-weight: bold; margin-bottom:0px !important;padding-bottom:10px; height:20px;">'+
                                            '<div class="col-sm-6">'+
                                                'Serial No.'+
                                            '</div>'+
                                            '<div class="col-sm-6">'+
                                                'Asset No.'+
                                            '</div>'+
                                        '</div>';
            for(var i = 1; i <= asset_nos.length; i++) {
                temp += '<div class="form-group" style="padding-top: 10px !important;height: 40px;">'+
                                                '<div class="col-sm-6">'+
                                                    '<input type="text" class="form-control add_item_serial_no_receiving" value="'+serial_nos[i-1]+'" name="newserial_'+i+'" readonly>'+
                                                '</div>'+
                                                '<div class="col-sm-6">'+
                                                    '<input type="text" class="form-control add_item_asset_no_receiving" value="'+asset_nos[i-1]+'" name="newasset_'+i+'" readonly>'+
                                                '</div>'+
                                            '</div>';
            }
            $("#serial_asset_no_modal").find(".serial_asset_no_modal_content").html(temp);
            $('#serial_asset_no_modal').modal("show");
        });

        $('body').on('click','.cancel_inventory_item',function(){
            var _this = $(this);
            var row_id = _this.attr("data-row-id");
            var transfer_req_receiving_id = $(this).attr('data-transfer-req-receiving-id');
            var item_total_cost = $(this).data('item-total-cost');
            var grand_total = $(".order_req_grand_total_input").val();

            if(_this.is(":checked"))
            {
                _this.parents("tr").css('text-decoration','line-through');
                // $("body").find('.qty_ordered_'+item_id).attr("readonly","readonly");
                // $("body").find('.qty_received_'+item_id).attr("readonly","readonly");
                // $("body").find('.back_order_received_'+item_id).attr("readonly","readonly");
                // $("body").find('.add_serial_asset_no_button_'+item_id).attr("disabled","disabled");
                $('body').find('.receive_inv_item_'+row_id).attr("disabled","disabled");
                $.post(base_url+'inventory/cancel_equipment_transfer_item/'+ transfer_req_receiving_id+"/","", function(response){
                    var obj = $.parseJSON(response);

                    if(obj == true)
                    {

                        $(".order_req_grand_total_input").val((grand_total-item_total_cost).toFixed(2));
                        $("body").find(".item_status_"+row_id).val(1);
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
                // $("body").find('.qty_ordered_'+item_id).attr("readonly",false);
                // $("body").find('.qty_received_'+item_id).attr("readonly",false);
                // $("body").find('.back_order_received_'+item_id).attr("readonly",false);
                // $("body").find('.add_serial_asset_no_button_'+row_id).removeAttr("disabled");
                $('body').find('.receive_inv_item_'+row_id).removeAttr("disabled");
                $.post(base_url+'inventory/retrieve_equipment_transfer_item/'+ transfer_req_receiving_id+"/","", function(response){
                    var obj = $.parseJSON(response);

                    if(obj == true)
                    {

                        $(".order_req_grand_total_input").val((Number(grand_total)+Number(item_total_cost)).toFixed(2));
                        $("body").find(".item_status_"+row_id).val(0);
                        me_message_v2({error:0,message:"Item Retrieved."});
                    }
                    else
                    {
                        me_message_v2({error:1,message:"Error Retrieving."});
                    }
                });
            }
        });

        $('body').on('click','.confirm_equipment_transfer_order_requisition',function(){
            var _this = $(this);
            var form_data = $('.order_requisition_form').serialize();

            jConfirm("Receive Equipment Transfer Requisition?","Reminder",function(response){
                if(response)
                {
                    $(this).prop("disabled","true");
                    $.post(base_url+"inventory/confirm_equipment_transfer_order_requisition/",form_data, function(response){
                        var obj = $.parseJSON(response);
                        console.log(obj);
                        jAlert(obj['message'],"Reminder");
                        if(obj['error'] == 0)
                        {
                            setTimeout(function(){
                                location.reload();
                            },1500);
                        }
                    });
                }
            });

        });

    });

</script>
