<style type="text/css">
    .modal
    {
        left: -400px;
        top: -71px;
    }
    #globalModal .modal-content
    {
        width:1050px;
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

    .dme-listing
    {
        display: block;
        list-style: none;
        list-style-image: none;
        width: 100%;
        padding-left: 0;
        margin: 0;
    }

    .dme-lists
    {
        display: table;
        padding-top: 10px;
        width: 100%;
        padding-bottom: 10px;
    }

    .dme-lists-icon
    {
        display: table-cell;
        font-size: 20px;
        text-align: center;
        width: 40px;
        vertical-align: top;
    }

    .dme-lists-text
    {
        display: table-cell;
        padding-top: 3px;
        padding-right: 10px;
        padding-left: 10px;
        font-size: 14.5px;
    }

    .dme-lists-text > label {
        display: block;
        line-height: 14px;
        color:rgba(0, 0, 0, 0.9);
    }

    .dme-lists-text > span {
        display: block;
    }

    .print_list_option_settings
    {
        cursor:pointer !important;
        color: #0062ad;
        margin-left: 10px;
    }

    .details_row
    {
        margin-bottom: 15px !important;
    }

    .order_req_payment_list
    {
        text-align: center;
    }

    .order_req_payment_list_div
    {
        margin-bottom:25px !important;
        margin-top:5px;
        padding-left:15px;
        padding-right:15px;
        padding-top:10px;
    }

    .paid_date_td
    {
        padding-left: 3px !important;
    }

    .amount_paid_td
    {
        padding-right: 0px !important;
    }

    .confirmation_no_td
    {
        padding-left: 0px !important;
        padding-right: 0px !important;
    }

    @media print{
        @page { 
            margin-bottom: 0; 
            margin-top: 0; 
            margin-left:0;
        }

        .modal-header
        {
            display:none !important;
        }

        .modal-content
        {
            border:0px !important;
        }

        .order_requisition_payment_div
        {
            width:720px !important;
            padding:0px !important;
            margin-left: -14px !important;
            margin-right: -14px !important;
        }

        .details_row
        {
            margin-left: 0px !important;
        }

        .order_req_first_header
        {
            font-size: 15px !important;
        }
        .order_req_second_header
        {
            font-size: 13px !important;
        }

        .dme-lists-text
        {
            font-size: 12px !important;
        }

        .dme-lists-icon > i
        {
            color:rgba(0, 0, 0, 0.75) !important;
            font-size: 18px !important;
        }

        .dme-lists-text > span
        {
            color:rgba(0, 0, 0, 0.75) !important;
        }

        .data_column
        {
            width:260px !important;
        }

        .order_req_payment_list_div
        {
            font-size: 12px !important;
        }

        .order_req_payment_list_table
        {
            width:701px !important;
        }

        .paid_date_td
        {
            width:7% !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .order_date_td
        {
            width:7% !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .po_no_td
        {
            width:6% !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .vendor_td
        {
            width:13% !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .confirmation_no_td
        {
            width:10% !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .credit_td
        {
            width:5% !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .amount_due_td
        {
            width:9% !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .credit_used_td
        {
            width:9% !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }

        .amount_paid_td
        {
            width:9% !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }
    }

</style>

<div class="row details_row">
    <div class="form-group order_requisition_payment_div">
        <h4 class="order_req_first_header" style="text-align:center;"><strong> Advantage Home Medical Services </strong></h4>
        <p class="order_req_second_header" style="text-align:center;font-size:16px;margin-top:-10px;font-weight:bold;"> Purchase Order Requisition Payment Details</p>
    </div>

    <div class="form-group clearfix" style="margin-bottom:0px !important;margin-top:25px;">
        <div class="col-sm-4 col-xs-4 data_column" style="">
            <ul class="dme-listing">
                <li class="dme-lists">
                    <div class="dme-lists-icon"><i class="fa fa-calendar"></i></div>
                    <div class="dme-lists-text">
                        <label> Payment Date </label>
                        <span> <?php echo date('F d, Y'); ?> </span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-sm-4 col-xs-4 data_column" style="">
            <ul class="dme-listing">
                <li class="dme-lists">
                    <div class="dme-lists-icon"><i class="fa fa-address-card-o" aria-hidden="true"></i></div>
                    <div class="dme-lists-text">
                        <label> Vendor </label>
                        <span> <?php echo $order_req_payment_details['vendor_name']; ?> </span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-sm-4 col-xs-4 data_column" style="">
            <ul class="dme-listing">
                <li class="dme-lists">
                    <div class="dme-lists-icon"><i class="fa fa-hashtag"></i></div>
                    <div class="dme-lists-text">
                        <label> PO Number </label>
                        <span> <?php echo $order_req_payment_details['purchase_order_no']; ?> </span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="form-group clearfix" style="margin-bottom:0px !important;margin-top:5px;">
        <div class="col-sm-4 col-xs-4 data_column" style="">
            <ul class="dme-listing">
                <li class="dme-lists">
                    <div class="dme-lists-icon"><i class="fa fa-hashtag"></i></div>
                    <div class="dme-lists-text">
                        <label> Confirmation No. </label>
                        <span> <?php echo $order_req_payment_details['order_req_confirmation_no']; ?> </span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-sm-4 col-xs-4 data_column" style="">
            <ul class="dme-listing">
                <li class="dme-lists">
                    <div class="dme-lists-icon"><i class="fa fa-commenting" aria-hidden="true"></i></div>
                    <div class="dme-lists-text">
                        <label> Terms </label>
                        <span> <?php echo $order_req_payment_details['vendor_credit_terms']; ?> </span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-sm-4 col-xs-4 data_column" style="">
            <ul class="dme-listing">
                <li class="dme-lists">
                    <div class="dme-lists-icon"><i class="fa fa-credit-card"></i></div>
                    <div class="dme-lists-text">
                        <label> Credit </label>
                        <span> <?php echo number_format($order_req_payment_details['credit'],2); ?> </span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="form-group clearfix" style="margin-bottom:0px !important;margin-top:5px;">
        <div class="col-sm-4 col-xs-4 data_column" style="">
            <ul class="dme-listing">
                <li class="dme-lists">
                    <div class="dme-lists-icon"><i class="fa fa-credit-card-alt"></i></div>
                    <div class="dme-lists-text">
                        <label> Method </label>
                        <?php 
                            if($order_req_payment_details['payment_method'] == "credit_card")
                            {
                                $payment_method = "Credit Card";
                            }
                            else
                            {
                                $payment_method = "Check";
                            }
                        ?>
                        <span> <?php echo $payment_method; ?> </span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-sm-4 col-xs-4 data_column" style="">
            <ul class="dme-listing">
                <li class="dme-lists">
                    <div class="dme-lists-icon"><i class="fa fa-hashtag" aria-hidden="true"></i></div>
                    <div class="dme-lists-text">
                        <label> Check No. </label>
                        <span> <?php echo $order_req_payment_details['check_no']; ?> </span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-sm-4 col-xs-4 data_column" style="">
            <ul class="dme-listing">
                <li class="dme-lists">
                    <div class="dme-lists-icon"><i class="fa fa-usd"></i></div>
                    <div class="dme-lists-text">
                        <label> Amount Paid </label>
                        <span> <?php echo number_format($order_req_payment_details['payment_amount'],2); ?> </span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="form-group clearfix" style="margin-bottom:0px !important;margin-top:5px;">
        <div class="col-sm-4 col-xs-4 data_column" style="">
            <ul class="dme-listing">
                <li class="dme-lists">
                    <div class="dme-lists-icon"><i class="fa fa-credit-card"></i></div>
                    <div class="dme-lists-text">
                        <label> Credit Used </label>
                        <span> <?php echo number_format($order_req_payment_details['credit_used'],2); ?> </span>
                    </div>
                </li>
            </ul>
        </div>
        <div class="col-sm-4 col-xs-4 data_column" style="">
            <ul class="dme-listing">
                <li class="dme-lists">
                    <div class="dme-lists-icon"><i class="fa fa-money" aria-hidden="true"></i></div>
                    <div class="dme-lists-text">
                        <label> Ending Balance </label>
                        <span> <?php echo number_format($order_req_payment_details['ending_balance'],2); ?> </span>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="form-group clearfix hidden-print" style="margin-bottom:0px !important;margin-top:10px;">
        <div class="col-sm-12 print_list_option_settings_div" style="margin-top:7px !important;">
            <span class="print_list_option_settings" data-value="1"> <i class="fa fa-caret-down" aria-hidden="true"> </i> &nbsp; Hide List from Print </span>
        </div>
    </div>
    <div class="form-group clearfix order_req_payment_list_div">
        <table class="table m-b-none order_req_payment_list_table" style="border:1px solid rgba(0, 0, 0, 0.11);">
            <thead>
                <tr>
                    <th class="paid_date_td" style="width:7%;text-align:center;">Paid Date</th>
                    <th class="order_date_td" style="width:8%;text-align:center;">Order Date</th>
                    <th class="po_no_td" style="width:10%;text-align:center;">PO No.</th>
                    <th class="vendor_td" style="width:15%;text-align:center;">Vendor</th>
                    <th class="confirmation_no_td" style="width:10%;text-align:center;">Confirmation No.</th>
                    <th class="credit_td" style="width:9%;text-align:center;">Credit</th>
                    <th class="amount_due_td" style="width:10%;text-align:center;">Amount Due</th>
                    <th class="credit_used_td" style="width:10%;text-align:center;">Credit Used</th>
                    <th class="amount_paid_td" style="width:11%;text-align:center;">Amount Paid</th>
                </tr>
            </thead>
            <tbody class="">
                <?php 
                    if(!empty($order_req_payment_list))
                    {
                        foreach ($order_req_payment_list as $key => $value) 
                        {
                ?>
                            <tr>
                <?php
                            if($value['payment_date'] != '0000-00-00')
                            {
                                $payment_date = date("m/d/Y", strtotime($value['payment_date']));
                            }
                            else
                            {
                                $payment_date = "";
                            }
                ?>
                                <td class="order_req_payment_list paid_date_td"> <?php echo $payment_date; ?> </td>
                                <td class="order_req_payment_list order_date_td"> <?php echo date("m/d/Y", strtotime($value['order_req_date'])); ?> </td>
                                <td class="order_req_payment_list po_no_td"> <?php echo substr($value['purchase_order_no'],3,10); ?>  </td>
                                <td class="order_req_payment_list vendor_td"> <?php echo $value['vendor_name']; ?> </td>
                                <td class="order_req_payment_list confirmation_no_td"> <?php echo $value['order_req_confirmation_no']; ?>  </td>
                                <td class="order_req_payment_list credit_td"> <?php echo number_format($value['vendor_credit'],2); ?>  </td>
                                <td class="order_req_payment_list amount_due_td"> <?php echo number_format($value['order_req_grand_total'],2); ?> </td>
                                <td class="order_req_payment_list credit_used_td"> <?php echo number_format($value['credit_used'],2);?> </td>
                                <td class="order_req_payment_list amount_paid_td"> <?php echo number_format($value['payment_amount'],2);?> </td>
                            </tr>
                <?php
                        }
                    }
                    else
                    {
                ?>
                        <tr>
                            <td colspan="9" style="text-align:center;"> No data. </td>
                        </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
    <hr class="hidden-print" />
    <div class="col-md-12" style="padding-right:17px;margin-bottom:-10px;">
        <div class="pull-left">
            <button class="btn btn-default" style="margin-right:10px;" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-danger pull-right" onclick="closeModalbox()">Close</button>
        </div>
    </div>
</div>

<script type="text/javascript"> 

    $(document).ready(function(){

        $('body').on('click','.print_list_option_settings',function(){
            var _this = $(this);
            var value = _this.data('value');
            var container = $(".print_list_option_settings_div");

            if(value == 1)
            {
                container.html('<span class="print_list_option_settings" data-value="0"> <i class="fa fa-caret-right" aria-hidden="true"> </i> &nbsp; Show List from Print </span>');
                $("body").find(".order_req_payment_list_div").slideUp('fast');
            }
            else
            {
                container.html('<span class="print_list_option_settings" data-value="1"> <i class="fa fa-caret-down" aria-hidden="true"> </i> &nbsp; Hide List from Print </span>');
                $("body").find(".order_req_payment_list_div").slideDown('fast');
            }
        });

    });

</script>
