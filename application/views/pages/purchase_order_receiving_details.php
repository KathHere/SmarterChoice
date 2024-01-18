<style type="text/css">
    .modal
    {
        left: -455px;
        top: -71px;
    }
    #globalModal .modal-content
    {
        width:1060px;
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

    .tooltiptext {
        visibility: hidden;
        width: 120px;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;
        background-color: rgba(43, 40, 40, 0.81);
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        border:1px solid #f1ecec;

        /* Position the tooltip */
        position: absolute;
        z-index: 10000000000;
    }

    .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: rgba(43, 40, 40, 0.81) transparent transparent transparent;
    }

    .all_items_order_req
    {
        margin-top:10px;
    }

    .order_requisition_details_row
    {
        margin-right: 0px !important;
        margin-left: 0px !important;
        font-size: 15px !important;
    }

    .order_requisition_details_row_v2
    {
        margin-right: 0px !important;
        margin-left: 0px !important;
        font-size: 15px !important;
        text-align: center;
    }

    .order_requisition_item_details
    {
        border:1px solid rgba(8, 8, 8, 0.62);
        height:70px;
        padding-left: 2px;
        padding-right: 2px;
    }

    .order_requisition_item_details_v2
    {
        border:1px solid rgba(8, 8, 8, 0.62);
        min-height:40px;
        padding-left: 2px;
        padding-right: 2px;
    }

    .order_requisition_form_textarea_col1
    {
        text-align:center;
        height: 47px;
        margin-top: -2px;
        margin-left: -2px;
        width: 76px;
        border: 0px;
        background-color: #fff;
        resize: none !important;
        box-shadow:inset 0 0 0px rgba(88, 102, 110, 0.13);
    }
    .order_requisition_form_textarea_col1:focus{
       outline:none !important;
       border:0px !important;
    }

    .order_requisition_form_textarea_col2
    {
        text-align:center;
        height: 47px;
        margin-top: -2px;
        margin-left: -2px;
        width: 153px;
        border: 0px;
        background-color: #fff;
        resize: none !important;
        box-shadow:inset 0 0 0px rgba(88, 102, 110, 0.13);
    }
    .order_requisition_form_textarea_col2:focus{
       outline:none !important;
       border:0px !important;
    }

    .order_requisition_form_textarea_col4
    {
        text-align:center;
        height: 47px;
        margin-top: -2px;
        margin-left: -2px;
        width: 313px;
        border: 0px;
        background-color: #fff;
        resize: none !important;
        box-shadow:inset 0 0 0px rgba(88, 102, 110, 0.13);
    }
    .order_requisition_form_textarea_col4:focus{
       outline:none !important;
       border:0px !important;
    }

    .person_taking_order_input{
        background-color: #fff !important;
        width: 200px;
        border-right: 0px;
        border-left: 0px;
        border-top: 0px;
        border-bottom: 1px solid #000;
        box-shadow: none !important;
        outline-style:none !important;
    }

    .person_taking_order_input:focus{
       outline:none !important;
       border:3px !important;
    }

    .confirmation_no_input{
        background-color: #fff !important;
        width: 222px;
        border-right: 0px;
        border-left: 0px;
        border-top: 0px;
        border-bottom: 1px solid #000;
        box-shadow: none !important;
    }

    .confirmation_no_input:focus{
       outline:none !important;
       border:3px !important;
    }

    .vendor_rep_no_input{
        background-color: #fff !important;
        width: 222px;
        border-right: 0px;
        border-left: 0px;
        border-top: 0px;
        border-bottom: 1px solid #000;
        box-shadow: none !important;
    }

    .vendor_rep_no_input:focus{
       outline:none !important;
       border:3px !important;
    }

    .purchase_order_requisition_row
    {
        margin-left: 0px!important;
    }

    .qty_received_span
    {
        cursor:pointer;
    }

    .popover
    {
        min-width:300px !important;
    }

    .inactive_item
    {
        background-color: #b9b9b929;
    }

    .popover-content
    {
        padding-left:0px !important;
        padding-right:0px !important;
    }

    @media print{
        @page {
            margin-bottom: 0;
            margin-top: 0;
        }

        .inactive_item
        {
            -webkit-print-color-adjust:exact;
            background: #b9b9b929 !important;
        }

        .modal-content
        {
            border:0px !important;
        }

        .order_requisition_item_details_v2
        {
            height:36px !important;
        }

        .order_requisition_item_details
        {
            height:44px !important;
            padding-top: 3px !important;
        }

        .order_requisition_div
        {
            /*border:1px solid #000 !important;*/
            width:720px !important;
            padding:0px !important;
            margin-left: -14px !important;
            margin-right: -14px !important;
        }
        .modal-header
        {
            display:none !important;
        }
        .order_req_first_header
        {
            font-size: 16px !important;
        }
        .order_req_second_header
        {
            font-size: 14px !important;
        }
        .order_requisition_details_row
        {
            font-size: 12px !important;
            padding-left:0px !important;
            padding-right:0px !important;
        }
        .order_requisition_details_row_v2
        {
            font-size: 12px !important;
            padding-left:0px !important;
            padding-right:0px !important;
        }
        .company_item_no_col
        {
            width:60px !important;
        }
        .item_description_col
        {
            width:220px !important;
        }
        .reorder_no_col
        {
            width:90px !important;
        }
        .unit_of_measure_col
        {
            width:52px !important;
        }
        .item_cost_col
        {
            width:52px !important;
        }
        .par_level_col
        {
            width:52px !important;
        }
        .qty_ordered_col
        {
            width:52px !important;
        }
        .qty_received_col
        {
            width:52px !important;
        }
        .total_cost_col
        {
            width:70px !important;
        }
        .item_list_header_one_line
        {
            padding-top: 12px !important;
        }
        .first_order_requisition_details_row
        {
            margin-top: 5px !important;
        }
    }

</style>

<div class="row">
    <div class="form-group order_requisition_div">
        <h4 class="order_req_first_header" style="text-align:center;"><strong> Advantage Home Medical Services </strong></h4>
        <p class="order_req_second_header" style="text-align:center;font-size:16px;margin-top:-10px;font-weight:bold;"> Purchase Order Requisition </p>

        <div class="row order_requisition_details_row first_order_requisition_details_row" style="margin-top:30px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Date:</strong> <?php echo date("m/d/Y", strtotime($order_req_details['order_req_date'])); ?>
            </div>
            <div class="col-xs-6 col-xs-6 col-sm-6 col-md-6" style="">
                <?php
                    $location = get_login_location($this->session->userdata('user_location'));
                ?>
                <strong>Location:</strong> <span> <?php echo $location['user_city'].", ".$location['user_state']; ?> </span>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:6px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Vendor:</strong> <span> <?php echo $order_req_details['vendor_name']; ?> </span>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Vendor Phone:</strong> <span class="vendor_phone_no"> <?php echo $order_req_details['vendor_phone_no']; ?> </span>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:6px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Account No. </strong> <span class="vendor_account_no"> <?php echo $order_req_details['vendor_acct_no']; ?> </span>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Purchase Order No.:</strong> <span> <?php echo substr($order_req_details['purchase_order_no'], 3, 10); ?> </span>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:6px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Vendor Rep. Taking Order:</strong> <span> <?php echo $order_req_details['vendor_rep_taking_order']; ?> </span>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Confirmation No.:</strong> <span> <?php echo $order_req_details['order_req_confirmation_no']; ?> </span>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:6px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Person Placing Order:</strong> <span> <?php echo $order_req_details['person_placing_order']; ?> </span>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Received Date:</strong> <span> <?php echo date("m/d/Y", strtotime($order_req_details['req_received_date'])); ?> </span>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:6px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Staff Member Receiving PO Req:</strong> <span> <?php echo $order_req_details['req_staff_member_receiving']; ?> </span>
            </div>
        </div>

        <div class="row order_requisition_details_row_v2" style="margin-top:15px !important;margin-right:9px !important;margin-left:10px !important;">
            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details company_item_no_col" style="border-right:0px !important;padding-top:10px;width:100px;">
                <p> Company</p>
                <p style="margin-top:-10px;"> Item No. </p>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4 order_requisition_item_details item_description_col item_list_header_one_line" style="border-right:0px !important;padding-top:20px;width:280px;">
                Item Description
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details reorder_no_col item_list_header_one_line" style="border-right:0px !important;padding-top:20px;width:160px;">
                Re Order No.
            </div>
            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details unit_of_measure_col" style="border-right:0px !important;padding-top:10px;width:80px;">
                Unit of Measure
            </div>
            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details item_cost_col item_list_header_one_line" style="border-right:0px !important;padding-top:20px;width:75px;">
                Cost
            </div>
            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details par_level_col" style="border-right:0px !important;padding-top:10px;width:75px;">
                <p> Par</p>
                <p style="margin-top:-10px;"> Level </p>
            </div>
            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details qty_ordered_col" style="border-right:0px !important;padding-top:10px;width:75px;">
                <p> Qty.</p>
                <p style="margin-top:-10px;"> Ordered </p>
            </div>
            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details qty_ordered_col" style="border-right:0px !important;padding-top:10px;width:75px;">
                <p> Qty.</p>
                <p style="margin-top:-10px;"> Received </p>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details total_cost_col item_list_header_one_line" style="padding-top:20px;width:100px;">
                Total Cost
            </div>
        </div>

        <?php
            if (!empty($order_req_items)) {
                $ordered_count = 0;
                $looped_item_list = array();
                foreach ($order_req_items as $key => $value) {
                    if (!in_array($value['item_id'], $looped_item_list)) {
                        $ordered_count++;
                        $looped_item_list[] = $value['item_id'];
                        $cancelled_item_css = "";
                        $cancelled_class="";
                        $cancelled_span="";
                        $inactive_class= "";
                        if ($value['item_status'] == 1) {
                            $cancelled_item_css = "text-decoration:line-through;";
                            $cancelled_class="tooltip_class";
                            $cancelled_span='<span class="tooltiptext"> Cancelled </span>';
                        }
                        if ($value['item_active_sign'] == 0) {
                            $inactive_class= "inactive_item";
                        } ?>
                        <div class="row order_requisition_details_row_v2 <?php echo $inactive_class; ?>" style="margin-right:9px !important;margin-left:10px !important;" >
                            <div class="col-xs-2 col-sm-1 col-md-1 order_requisition_item_details_v2 company_item_no_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:100px;">
                                <span class="<?php echo $cancelled_class; ?>"> <?php echo $value['company_item_no']; ?> </span>  <?php echo $cancelled_span; ?>
                            </div>
                            <div class="col-xs-3 col-sm-4 col-md-4 order_requisition_item_details_v2 item_description_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:280px;">
                                <span class="<?php echo $cancelled_class; ?>"> <?php echo $value['item_description']; ?> </span>  <?php echo $cancelled_span; ?>
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 reorder_no_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:160px;">
                                <span class="<?php echo $cancelled_class; ?>"> <?php echo $value['item_reorder_no']; ?> </span>  <?php echo $cancelled_span; ?>
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 unit_of_measure_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:80px;">
                                <span class="<?php echo $cancelled_class; ?>"> <?php echo $value['item_unit_measure']; ?> </span>  <?php echo $cancelled_span; ?>
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 item_cost_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:75px;">
                                <span class="<?php echo $cancelled_class; ?>"> <?php echo $value['item_cost']; ?> </span>  <?php echo $cancelled_span; ?>
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 par_level_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:75px;">
                                <span class="<?php echo $cancelled_class; ?>"> <?php echo $value['item_par_level']; ?> </span>  <?php echo $cancelled_span; ?>
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 qty_ordered_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:75px;">
                                <span class="<?php echo $cancelled_class; ?>"> <?php echo $value['req_item_quantity_ordered']; ?> </span>  <?php echo $cancelled_span; ?>
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 qty_received_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:75px;">
                                <?php
                                    if ($value['item_status'] == 1) {
                                        ?>
                                        <span class="qty_received_span <?php echo $cancelled_class; ?>"> <?php echo $value['req_item_quantity_received']; ?> </span>  <?php echo $cancelled_span; ?>
                                <?php
                                    } else {
                                        if ($value['req_item_quantity_received'] > 0) {
                                            ?>
                                            <a
                                                href="javascript:;"
                                                rel="popover"
                                                data-toggle="popover"
                                                title="Serial & Asset No(s)."
                                                data-placement="top"
                                                data-trigger="focus"
                                                data-item-batch-no = "<?php echo $value['item_batch_no']; ?>";
                                                data-po-no = "<?php echo $value['purchase_order_no']; ?>";
                                                data-order-req-id = "<?php echo $value['order_req_id']; ?>";
                                                data-item-id = "<?php echo $value['item_id']; ?>";
                                                class="qty_received_ahref <?php echo $cancelled_class; ?>"
                                                data-content=""
                                            >
                                                <?php echo $value['req_item_quantity_received']; ?>
                                            </a>
                                <?php
                                        } else {
                                            echo $value['req_item_quantity_received'];
                                        }
                                    } ?>
                            </div>

                            <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 total_cost_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;padding-top:2px;width:100px;">
                                <span class="<?php echo $cancelled_class; ?>"> <?php echo number_format($value['item_total_cost'], 2); ?> </span>  <?php echo $cancelled_span; ?>
                            </div>
                        </div>
        <?php
                    }
                }
                for ($j = 0; $j < (14-$ordered_count); $j++) {
                    ?>
                    <div class="row order_requisition_details_row_v2" style="margin-right:9px !important;margin-left:10px !important;" >
                        <div class="col-xs-2 col-sm-1 col-md-1 order_requisition_item_details_v2 company_item_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:100px;">
                        </div>
                        <div class="col-xs-3 col-sm-4 col-md-4 order_requisition_item_details_v2 item_description_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:280px;">
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 reorder_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:160px;">
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 unit_of_measure_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:80px;">
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 item_cost_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:75px;">
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 par_level_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:75px;">
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 qty_ordered_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:75px;">
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 qty_received_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:75px;">
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 total_cost_col" style="height:48px;border-top:0px !important;padding-top:2px;width:100px;">
                        </div>
                    </div>
        <?php
                }
            }
        ?>


        <div class="row order_requisition_details_row" style="margin-top:15px !important;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="height:22px;">
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 " style="text-align:right;padding:0;">
                <div class="col-xs-9 col-sm-9 col-md-9">
                    <strong>Amount:</strong>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                    <?php echo number_format($order_req_details['order_req_amount'], 2); ?>
                </div>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:5px !important;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="height:22px;">
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 " style="text-align:right;padding:0;">
                <div class="col-xs-9 col-sm-9 col-md-9">
                    <strong>Shipping Cost:</strong>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                    <?php echo number_format($order_req_details['order_req_shipping_cost'], 2); ?>
                </div>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:5px !important;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="height:22px;">
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 " style="text-align:right;padding:0;">
                <div class="col-xs-9 col-sm-9 col-md-9">
                    <strong>Total:</strong>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                    <?php echo number_format($order_req_details['order_req_grand_total'], 2); ?>
                </div>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:15px !important;margin-bottom:10px !important;">
            <div class="col-sm-12 col-md-6" style="">
                <strong>Employee Signature:</strong> &nbsp; <span> <hr style=" width: 220px; border-color: #292626; margin-left: 145px; margin-top: -2px;"/> </span>
            </div>
        </div>
    </div>

    <div class="col-md-12 hidden-print">
        <hr />
        <button class="btn btn-default" style="margin-right:10px;" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
        <button type="button" class="btn btn-danger pull-right" onclick="closeModalbox()">Close</button>
    </div>

</div>

<script type="text/javascript">

    $(document).ready(function(){

        $('.tooltip_class').hover(function(){
            $(this).siblings(".tooltiptext").css('visibility','visible');
        });

        $('.tooltip_class').mouseout(function(){
            $(this).siblings(".tooltiptext").css('visibility','hidden');
        });

        $(function () {
            $('[data-toggle="popover"]').popover()
        });

        $('.popover-dismiss').popover({
          trigger: 'focus'
        });

        // $('.qty_received_ahref').click(function(){
        //     var _this = $(this);
        //     var item_id = $(this).attr("data-item-id");
        //     var po_number = $(this).attr("data-po-no");
        //     var order_req_id = $(this).attr("data-order-req-id");
        //     var item_batch_no = $(this).attr("data-item-batch-no");
        //     var temp = "";
        //     var temp_container = "";

        //     $.post(base_url+"inventory/get_received_item_serials_assets/"+ item_id +"/"+ po_number +"/"+ order_req_id +"/"+ item_batch_no,"", function(response){
        //         var obj = $.parseJSON(response);
        //         var count = 1;
        //         for(var val in obj.item_serial_asset_nos)
        //         {
        //             if(count == 1)
        //             {
        //                 temp += '<div class="form-group" style="padding-top: 5px !important;padding-bottom: 5px !important;height: 20px !important;">'+
        //                             '<div class="col-xs-6" style="width:50% !important; font-weight:bold;font-size:15px!important;">'+
        //                                 'Serial No(s).'+
        //                             '</div>'+
        //                             '<div class="col-xs-6" style="width:50% !important; font-weight:bold;font-size:15px!important;">'+
        //                                 'Asset No(s).'+
        //                             '</div>'+
        //                         '</div>';
        //             }
        //             temp += '<div class="form-group" style="padding-top: 5px !important;padding-bottom: 5px !important;height: 20px ;">'+
        //                         '<div class="col-xs-6" style="width:50% !important;">'+
        //                             ''+obj.item_serial_asset_nos[val].item_serial_no+''+
        //                         '</div>'+
        //                         '<div class="col-xs-6" style="width:50% !important;">'+
        //                             ''+obj.item_serial_asset_nos[val].item_asset_no+''+
        //                         '</div>'+
        //                     '</div>';
        //             count++;
        //             temp_container = '<div style="max-height: 200px !important;overflow-y:auto !important;">'+temp+'</div>';
        //         }
        //         console.log('sample 2',temp_container);
        //         _this.attr('data-content', temp_container);
        //     });
        // });

        $('body .qty_received_ahref').each(function(){
            $(this).popover({
                html: true
            });
            var _this = $(this);var item_id = $(this).attr("data-item-id");
            var po_number = $(this).attr("data-po-no");
            var order_req_id = $(this).attr("data-order-req-id");
            var item_batch_no = $(this).attr("data-item-batch-no");
            var temp = "";
            var temp_container = "";

            $.post(base_url+"inventory/get_received_item_serials_assets/"+ item_id +"/"+ po_number +"/"+ order_req_id +"/"+ item_batch_no,"", function(response){
                var obj = $.parseJSON(response);
                var count = 1;
                for(var val in obj.item_serial_asset_nos)
                {
                    if(count == 1)
                    {
                        temp += '<div class="form-group" style="padding-top: 5px !important;padding-bottom: 5px !important;height: 20px !important;">'+
                                    '<div class="col-xs-6" style="width:50% !important; font-weight:bold;font-size:15px!important;">'+
                                        'Serial No(s).'+
                                    '</div>'+
                                    '<div class="col-xs-6" style="width:50% !important; font-weight:bold;font-size:15px!important;">'+
                                        'Asset No(s).'+
                                    '</div>'+
                                '</div>';
                    }
                    temp += '<div class="form-group" style="padding-top: 5px !important;padding-bottom: 5px !important;height: 20px ;">'+
                                '<div class="col-xs-6" style="width:50% !important;">'+
                                    ''+obj.item_serial_asset_nos[val].item_serial_no+''+
                                '</div>'+
                                '<div class="col-xs-6" style="width:50% !important;">'+
                                    ''+obj.item_serial_asset_nos[val].item_asset_no+''+
                                '</div>'+
                            '</div>';
                    count++;
                    temp_container = '<div style="max-height: 200px !important;overflow-y:auto !important;">'+temp+'</div>';
                }
                _this.attr('data-content', temp_container);
            });
        });
    });

</script>