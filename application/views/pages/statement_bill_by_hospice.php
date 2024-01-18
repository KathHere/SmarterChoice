<style type="text/css">
    .disabled_tag {
        background-color: white !important;
    }

    .address-style {
        font-weight: bold;
    }
    .statement_letter_label_tag {
        font-weight: bold;
        margin-right: 6px;
    }
    .statement_letter_label_wrapper {
        margin-bottom: 20px;
    }

    .thead_style {
        font-weight: bold;
    }
    
    .tags {
        border: 1px solid #23ad44; 
        background-color: #23ad44; 
        padding: 1px;
        font-size: 10px; 
        color: white;
        font-weight: bold;
        border-radius: 5px;
    }

    .select2-dropdown.select2-dropdown--below {
        z-index: 10010;
    }

    .sticky {
      position: -webkit-sticky;
      position: sticky;
      top: 0;
      padding-top: 80px;
      margin-top: -50px !important;
      z-index: 1000;
      /*font-size: 20px;*/
    }

    .box-bottom-shadow {
        /*content:"";*/
        /*position:absolute;*/
        width:100%;
        bottom:1px;
        /*z-index:-1;*/
        /*transform:scale(.9);*/
        box-shadow: 0px 0px 8px 2px #A8A8A8;
    }

    .center-header {
        text-align: center;
    }

    .panel_invoice_left {
        padding-left: 50px;
    }
    .panel_invoice_right {
        padding-right: 50px;
    }

    .panel_invoice_row {
        margin-top: 20px !important;
    }

    #past_due_invoice {
        padding: 100px;
        font-size: 50px;
        text-align: center;
    }

    .dot {
        height: 18px;
        min-width: 18px;
        margin-left: 5px;
        margin-right: 0px;
        background-color: #bbb;
        border-radius: 20px;
        display: inline-block;
        background-color: red;
        color: white;
    }

    .paid-tagger {
        background-color: #23b7e5;
        border-radius: 8px;
        padding: 0px 5px 0px 5px;
        color: #fff;
        font-weight: bold;
    }

    .ckrtn-tagger {
        background-color: #f05050;
        border-radius: 8px;
        padding: 0px 5px 0px 5px;
        color: #fff;
        font-weight: bold;
    }

    .view_invoice_details {
        cursor: pointer !important;
    }

    @media print {
        @page {
            size: portrait;
            margin: 0mm 2mm 10mm 2mm;
        }

        .statement_bill_by_hospice_container {
            /* font-size: 5px; */
        }

        .sticky {
            position: relative !important;
            margin-top: -100px !important;
        }

        .row {
          display: flex;
          flex-direction: row;
          flex-wrap: wrap;
          width: 100%;
        }

        .col, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
          display: flex;
          flex-direction: column;
          flex-basis: 100%;
          flex: 1;
        }

        .sticky_print_display {
            display: block !important;
        }

        .sticky_print_hide {
            display: none !important;
        }

        .hidden_panel {
            color: #fff !important;
            background: #fff !important;
            border: 0px !important;
            -webkit-print-color-adjust: exact;
            display: block !important;
        }
    }

    .hidden_panel {
        color: #fff !important;
        background: #fff !important;
        border: 0px !important;
        -webkit-print-color-adjust: exact;
        display: none;
    }
    .sticky_print_display {
        display: none;
    }
</style>

<div class="statement_bill_by_hospice_container">
    <div class="bg-light lter b-b wrapper-md sticky box-bottom-shadow sticky_print_display" style="padding-bottom: 0px !important;">
        <div class="row">
            <div class="col-md-5">
                <div class="wrapper-md pb0">
                    <div class="form-group clearfix">
                        <div class="" style="padding-left:5px;">
                            <strong class="purchase_order_inquiry_info" >Account</strong>: <span class="" > <?php echo $hospice_details['hospice_name'];?></span>
                        </div>
                    </div>
                    <div class="form-group clearfix" style="margin-bottom:5px !important;">
                        <div class="" style="padding-left:5px;">
                            <strong class="purchase_order_inquiry_info" >Account Number</strong>: <span class=" location_info" > <?php echo $hospice_details['hospice_account_number'];?> </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <table class="table table-bordered bg-white b-a" style="text-align: center">
                <thead style="background-color: #f6f8f8; ">
                        <tr>
                            <th class="center-header" style="width: 25%">Current </br> <span style="font-weight: 500">Inv. No:</span>
                                <?php
                                    if($total_balance_due['total'] != null && $total_balance_due['total'] != 0 && $total_balance_due['total'] != "") {
                                ?>
                                <span class="view_invoice_details" style="color: #23b7e5; " data-invoice-id="<?php echo $total_balance_due['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $total_balance_due['hospiceID']; ?>">
                                <?php
                                        echo substr($total_balance_due['statement_no'],3, 10);
                                    }
                                ?>
                                </span>
                            </th>
                            <th class="center-header" style="width: 25%">Past Due</th>
                            <th class="center-header" style="width: 25%">Total Balance Due By: 
                                <?php
                                    if($total_balance_due['total'] != null && $total_balance_due['total'] != 0 && $total_balance_due['total'] != "") {
                                        echo '&nbsp;&nbsp;'.date("m/d/Y", strtotime($total_balance_due['due_date'])).'</br>'; 
                                    }
                                ?>
                            </th>
                            <th class="center-header" style="width: 25%">Last Payment Received:
                                <?php
                                if($last_payment['payment_date'] != null && $last_payment['payment_date'] != "0000-00-00" && $last_payment['payment_date'] != "0000-00-00 00:00:00") {
                                    echo '&nbsp;&nbsp;'.date("m/d/Y", strtotime($last_payment['payment_date'])); 
                                }
                                ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <?php
                                if($total_balance_due['total'] != null && $total_balance_due['total'] != 0 && $total_balance_due['total'] != "") {
                                    echo number_format((float)$tbd_total, 2, '.', '');
                                } else {
                                    echo '0.00';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if($past_due_amount != null && $past_due_amount != 0 && $past_due_amount != "") {
                                    echo number_format((float)$past_due_amount, 2, '.', '');
                                } else {
                                    echo '0.00';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $tbdtotal_past_due = 0;
                                if($total_balance_due['total'] != null && $total_balance_due['total'] != 0 && $total_balance_due['total'] != "") {
                                    if($past_due_amount != null && $past_due_amount != 0 && $past_due_amount != "") {
                                        $tbdtotal_past_due = $tbd_total + $past_due_amount;
                                    } else {
                                        $tbdtotal_past_due = $tbd_total;
                                    }
                                } else {
                                    if($past_due_amount != null && $past_due_amount != 0 && $past_due_amount != "") {
                                        $tbdtotal_past_due = $past_due_amount;
                                    } 
                                }

                                if ($tbdtotal_past_due > 0) {
                                    echo number_format((float)$tbdtotal_past_due, 2, '.', '');
                                } else {
                                    echo '0.00';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if($last_payment['payment_date'] != null && $last_payment['payment_date'] != "0000-00-00" && $last_payment['payment_date'] != "0000-00-00 00:00:00") {
                                    echo number_format((float)$last_payment['payment_amount'], 2, '.', '');
                                } else {
                                    echo '0.00';
                                }
                                
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="bg-light lter b-b wrapper-md sticky box-bottom-shadow sticky_print_hide" style="padding-bottom: 0px !important;">
        <div class="row">
            <div class="col-md-3">
                <div class="wrapper-md pb0">
                    <div class="form-group clearfix">
                        <div class="" style="padding-left:5px;">
                            <strong class="purchase_order_inquiry_info" >Account</strong>: <span class="" > <?php echo $hospice_details['hospice_name'];?></span>
                        </div>
                    </div>
                    <div class="form-group clearfix" style="margin-bottom:5px !important;">
                        <div class="" style="padding-left:5px;">
                            <strong class="purchase_order_inquiry_info" >Account Number</strong>: <span class=" location_info" > <?php echo $hospice_details['hospice_account_number'];?> </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <table class="table table-bordered bg-white b-a" style="text-align: center">
                    <thead style="background-color: #f6f8f8; ">
                        <!-- <tr>
                            <th class="center-header" style="width: 33.33%">Past Due</th>
                            <th class="center-header" style="width: 33.33%">Total Balance Due 
                                <?php
                                    // if($total_balance_due['total'] != null && $total_balance_due['total'] != 0 && $total_balance_due['total'] != "") {
                                    //     echo '&nbsp;&nbsp;'.date("m/d/Y", strtotime($total_balance_due['due_date'])).'</br>'; 
                                ?>
                                Inv No:
                                <span class="view_invoice_details" style="color: #23b7e5; " data-invoice-id="<?php echo $total_balance_due['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $total_balance_due['hospiceID']; ?>">
                                <?php
                                    //     echo substr($total_balance_due['statement_no'],3, 10);
                                    // }
                                ?>
                                </span>
                            </th>
                            <th class="center-header" style="width: 33.33%">Last Payment Received
                                <?php
                                // if($last_payment['payment_date'] != null && $last_payment['payment_date'] != "0000-00-00" && $last_payment['payment_date'] != "0000-00-00 00:00:00") {
                                //     echo '&nbsp;&nbsp;'.date("m/d/Y", strtotime($last_payment['payment_date'])); 
                                // }
                                ?>
                            </th>
                        </tr> -->
                        <tr>
                            <th class="center-header" style="width: 25%">Current </br> <span style="font-weight: 500">Inv. No:</span>
                                <?php
                                    if($total_balance_due['total'] != null && $total_balance_due['total'] != 0 && $total_balance_due['total'] != "") {
                                ?>
                                <span class="view_invoice_details" style="color: #23b7e5; " data-invoice-id="<?php echo $total_balance_due['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $total_balance_due['hospiceID']; ?>">
                                <?php
                                        echo substr($total_balance_due['statement_no'],3, 10);
                                    }
                                ?>
                                </span>
                            </th>
                            <th class="center-header" style="width: 25%">Past Due</th>
                            <th class="center-header" style="width: 25%">Total Balance Due By: 
                                <?php
                                    if($total_balance_due['total'] != null && $total_balance_due['total'] != 0 && $total_balance_due['total'] != "") {
                                        echo '&nbsp;&nbsp;'.date("m/d/Y", strtotime($total_balance_due['due_date'])).'</br>'; 
                                    }
                                ?>
                            </th>
                            <th class="center-header" style="width: 25%">Last Payment Received:
                                <?php
                                if($last_payment['payment_date'] != null && $last_payment['payment_date'] != "0000-00-00" && $last_payment['payment_date'] != "0000-00-00 00:00:00") {
                                    echo '&nbsp;&nbsp;'.date("m/d/Y", strtotime($last_payment['payment_date'])); 
                                }
                                ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <tr>
                            <td>
                                <?php 
                                // if($past_due_amount != null && $past_due_amount != 0 && $past_due_amount != "") {
                                //     echo number_format((float)$past_due_amount, 2, '.', '');
                                // }
                                ?>
                            </td>
                            <td>
                                <?php
                                // if($total_balance_due['total'] != null && $total_balance_due['total'] != 0 && $total_balance_due['total'] != "") {
                                //     echo number_format((float)$tbd_total, 2, '.', '');
                                // }
                                ?>
                            </td>
                            <td>
                                <?php 
                                // if($last_payment['payment_date'] != null && $last_payment['payment_date'] != "0000-00-00" && $last_payment['payment_date'] != "0000-00-00 00:00:00") {
                                //     echo number_format((float)$last_payment['payment_amount'], 2, '.', '');
                                // }
                                
                                ?>
                            </td>
                        </tr> -->
                        <tr>
                            <td>
                                <?php
                                if($total_balance_due['total'] != null && $total_balance_due['total'] != 0 && $total_balance_due['total'] != "") {
                                    echo number_format((float)$tbd_total, 2, '.', '');
                                } else {
                                    echo '0.00';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if($past_due_amount != null && $past_due_amount != 0 && $past_due_amount != "") {
                                    echo number_format((float)$past_due_amount, 2, '.', '');
                                } else {
                                    echo '0.00';
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                $tbdtotal_past_due = 0;
                                if($total_balance_due['total'] != null && $total_balance_due['total'] != 0 && $total_balance_due['total'] != "") {
                                    if($past_due_amount != null && $past_due_amount != 0 && $past_due_amount != "") {
                                        $tbdtotal_past_due = $tbd_total + $past_due_amount;
                                    } else {
                                        $tbdtotal_past_due = $tbd_total;
                                    }
                                } else {
                                    if($past_due_amount != null && $past_due_amount != 0 && $past_due_amount != "") {
                                        $tbdtotal_past_due = $past_due_amount;
                                    } 
                                }

                                if ($tbdtotal_past_due > 0) {
                                    echo number_format((float)$tbdtotal_past_due, 2, '.', '');
                                } else {
                                    echo '0.00';
                                }
                                ?>
                            </td>
                            <td>
                                <?php 
                                if($last_payment['payment_date'] != null && $last_payment['payment_date'] != "0000-00-00" && $last_payment['payment_date'] != "0000-00-00 00:00:00") {
                                    echo number_format((float)$last_payment['payment_amount'], 2, '.', '');
                                } else {
                                    echo '0.00';
                                }
                                
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <div class="wrapper-md hidden-print">
                    <!-- <div class="pull-right">
                        <a href='<?php echo base_url(); ?>billing_statement/statement_make_payment/<?php echo $hospice_id; ?>' class="btn btn-success btn-sm" style="font-size:13px !important;">
                            Make Payment
                        </a>
                    </div> -->
                    <div class="pull-right">
                        <a href='<?php echo base_url(); ?>billing_reconciliation/payment_history_by_hospice/<?php echo $hospice_id; ?>' class="btn btn-primary btn-sm" style="font-size:13px !important;">
                            Payment History
                        </a>
                        &nbsp;&nbsp;
                    </div>
                    <div class="pull-right">
                        <a href='<?php echo base_url(); ?>billing_statement/statement_bill/<?php echo $hospice_id; ?>' class="btn btn-info btn-sm" style="font-size:13px !important;">
                            Current Statement
                        </a>
                        &nbsp;&nbsp;
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row b-b">
        <div class="col-md-4">
            <div class="wrapper-md" >
                <!-- <button class="btn btn-sm btn-default view_pending_payments" style="margin-left: 15px">Pending Payments
                 <?php if($pending_payments_count > 0) {?>
                    <span class="dot" style=""><?php echo $pending_payments_count; ?></span> 
                 <?php } ?>
                </button> -->
                
            <?php 

                $date_today = date("m/d/Y");
                $pay_date = date("m/d/Y", strtotime($pending_payments[count($pending_payments)-1]['payment_date'])); //Remove -1 days in Deployment
                if($date_today == $pay_date) { 
            ?>
                    <div class="col-md-6" style="text-align: right"><span style="font-weight: bold; font-size: 18px">Pending Payment:</span></div> 
                    <div class="col-md-6" style="margin-top: -10px; font-size: 18px;">
                        <span><?php echo date("m/d/Y", strtotime($pending_payments[count($pending_payments)-1]['payment_date'])); ?></span>
                        </br>
                        <span><?php echo number_format((float)$total_pending_payment, 2, '.', ''); ?></span>
                    </div> 
            <?php
                }
             ?>
            </div>
            
        </div>
        <div class="col-md-4">
            <div class="wrapper-md">
                <h1 class="m-n font-bold h3 center-header">
                    Past Due Invoice(s)
                </h1>
            </div>
        </div>
        <div class="col-md-4">
            <div class="wrapper-md" style="text-align: center">
                
                <?php if($this->session->userdata('account_active_sign') == 2) { ?>
                    <span style="font-weight: bold; color: red; font-size: 20px">System Suspended Payment Required</span>
                <?php }?>
            </div>
        </div>
    </div>

    <div id="past_due_invoice">
        <i class="fa fa-spin fa-spinner item_decription_spinner"></i>
    </div>
    <div id="past_due_invoice_v2"></div>

    <div class="bg-light lter wrapper-md">
       <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
    </div>
</div>

<input type="hidden" id="hospice_id_value" value="<?php echo $hospice_id; ?>">
<script type="text/javascript">
    $(document).ready(function(){
        var hospiceID = $('#hospice_id_value').val();
        console.log(hospiceID);
        $.get(base_url+'billing_statement/get_past_due_invoices/'+hospiceID, function(response){
            var obj = $.parseJSON(response);            // $('.close').click();
            console.log(obj);
            if(obj == null) {
                $('#past_due_invoice').html("No Data Found.");
            } else {
                var temp_html = "";
                var counter = 1;
                for(var i = 0; i < obj.statement_invoice_inquiry.length; i++) {
                    if((counter % 2) == 0) {
                        temp_html += '<div class="col-md-6 panel_invoice_right">';
                    } else {
                        temp_html += '<div class="row panel_invoice_row">';
                        temp_html += '<div class="col-md-6 panel_invoice_left">';
                    }
                    var date = new Date(obj.statement_invoice_inquiry[i].due_date);
                    var year = "";
                    var month = "";
                    year = date.getFullYear();
                    if(parseInt(date.getMonth())+1 < 10) {
                        month = "0"+(parseInt(date.getMonth())+1);
                    } else {
                        month = parseInt(date.getMonth())+1;
                    }
                    var day = "";
                    if((date.getDate()) < 10) {
                        day = "0"+(date.getDate());
                    } else {
                        day = date.getDate();
                    }

                    var total_due = 0;
                    total_due += parseFloat(obj.statement_invoice_inquiry[i].total);
                    total_due += parseFloat(obj.statement_invoice_inquiry[i].non_cap);
                    total_due += parseFloat(obj.statement_invoice_inquiry[i].purchase_item);
                    total_due -= parseFloat(obj.invoices_reconciliation[i].credit);     //Deduct;
                    total_due += parseFloat(obj.invoices_reconciliation[i].owe);        //Add;

                    var paid = "";
                    if(obj.statement_invoice_inquiry[i].payment_code != null && obj.statement_invoice_inquiry[i].payment_code != "") {
                        paid = '<span class="pull-right paid-tagger">Pending Payment</span>';
                    }
                    else if(obj.statement_invoice_inquiry[i].is_reverted == 1) {
                        // paid = '<span class="pull-right ckrtn-tagger">Ck Rtn</span>';
                    }
                    var service_date_from = obj.statement_invoice_inquiry[i].service_date_from.split('-');
                    var service_date_to = obj.statement_invoice_inquiry[i].service_date_to.split('-');
                    var service_date = service_date_from[1]+"/"+service_date_from[2]+"/"+service_date_from[0]+" - "+service_date_to[1]+"/"+service_date_to[2]+"/"+service_date_to[0];
                    //data-invoice-id="<?php echo $value['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $value['hospiceID']; ?>"
                    //<div class="statement_label_wrapper"> <label class="statement_label_tag">Invoice Number: </label><span> <input type="text" name="" class="form-control disabled_tag" value="'+obj.statement_invoice_inquiry[i].statement_no.substr(3,7)+'" disabled></span></div>
                    temp_html += '<div class="panel panel-default view_invoice_details" data-invoice-id="'+obj.statement_invoice_inquiry[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_invoice_inquiry[i].hospiceID+'"><div class="panel-heading"><div class="row" style="margin-left: 0px !important; margin-right: 0px !important;"><strong>Invoice Number: '+obj.statement_invoice_inquiry[i].statement_no.substr(3,10)+'</strong>'+paid+'</div><div class="row" style="margin-left: 0px !important; margin-right: 0px !important;">Service Date: '+service_date+'</div></div><div class="panel-body"><div class="statement_label_wrapper">';
                    //temp_html += '<input type="hidden" id="acct_statement_bill_id" value="'+obj.statement_invoice_inquiry[i].acct_statement_bill_id+'">';
                    temp_html += '<input type="hidden" id="statement_no" value="'+obj.statement_invoice_inquiry[i].statement_no+'">';
                    temp_html +=  '<label class="statement_label_tag">Due Date: </label><span data-statement-no=""> <input type="text" name="" class="form-control disabled_tag" value="'+month+'/'+day+'/'+year+'" disabled></span></div><div class="statement_label_wrapper" style="padding-top: 10px !important"><label class="statement_label_tag">Balance Due: </label><span> <input type="text" name="" class="form-control disabled_tag" value="'+parseFloat(total_due).toFixed(2)+'" disabled></span></div></div></div></div>';
                    if((counter % 2) == 0) {
                        temp_html += '</div>';
                    }
                    counter++;
                }

                var testtemp = obj.statement_invoice_inquiry.length % 2;
                console.log('testtemp', testtemp);
                if ((obj.statement_invoice_inquiry.length % 2) == 1) {
                    for(var i = 0; i < obj.statement_invoice_inquiry.length; i++) {
                        if((counter % 2) == 0) {
                            temp_html += '<div class="col-md-6 hidden_panel panel_invoice_right">';
                        } else {
                            temp_html += '<div class="row hidden_panel panel_invoice_row">';
                            temp_html += '<div class="col-md-6 hidden_panel panel_invoice_left">';
                        }
                        var date = new Date(obj.statement_invoice_inquiry[i].due_date);
                        var year = "";
                        var month = "";
                        year = date.getFullYear();
                        if(parseInt(date.getMonth())+1 < 10) {
                            month = "0"+(parseInt(date.getMonth())+1);
                        } else {
                            month = parseInt(date.getMonth())+1;
                        }
                        var day = "";
                        if((date.getDate()) < 10) {
                            day = "0"+(date.getDate());
                        } else {
                            day = date.getDate();
                        }

                        var total_due = 0;
                        total_due += parseFloat(obj.statement_invoice_inquiry[i].total);
                        total_due += parseFloat(obj.statement_invoice_inquiry[i].non_cap);
                        total_due += parseFloat(obj.statement_invoice_inquiry[i].purchase_item);
                        total_due -= parseFloat(obj.invoices_reconciliation[i].credit);     //Deduct;
                        total_due += parseFloat(obj.invoices_reconciliation[i].owe);        //Add;

                        var paid = "";
                        if(obj.statement_invoice_inquiry[i].payment_code != null && obj.statement_invoice_inquiry[i].payment_code != "") {
                            paid = '<span class="pull-right paid-tagger">Pending Payment</span>';
                        }
                        else if(obj.statement_invoice_inquiry[i].is_reverted == 1) {
                            // paid = '<span class="pull-right ckrtn-tagger">Ck Rtn</span>';
                        }
                        var service_date_from = obj.statement_invoice_inquiry[i].service_date_from.split('-');
                        var service_date_to = obj.statement_invoice_inquiry[i].service_date_to.split('-');
                        var service_date = service_date_from[1]+"/"+service_date_from[2]+"/"+service_date_from[0]+" - "+service_date_to[1]+"/"+service_date_to[2]+"/"+service_date_to[0];
                        //data-invoice-id="<?php echo $value['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $value['hospiceID']; ?>"
                        //<div class="statement_label_wrapper"> <label class="statement_label_tag">Invoice Number: </label><span> <input type="text" name="" class="form-control disabled_tag" value="'+obj.statement_invoice_inquiry[i].statement_no.substr(3,7)+'" disabled></span></div>
                        temp_html += '<div class="panel hidden_panel panel-default view_invoice_details" data-invoice-id="'+obj.statement_invoice_inquiry[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_invoice_inquiry[i].hospiceID+'"><div class="panel-heading hidden_panel"><div class="row hidden_panel" style="margin-left: 0px !important; margin-right: 0px !important;"><strong class="hidden_panel">Invoice Number: '+obj.statement_invoice_inquiry[i].statement_no.substr(3,10)+'</strong>'+paid+'</div><div class="row hidden_panel" style="margin-left: 0px !important; margin-right: 0px !important;">Service Date: '+service_date+'</div></div><div class="panel-body"><div class="statement_label_wrapper">';
                        //temp_html += '<input type="hidden" id="acct_statement_bill_id" value="'+obj.statement_invoice_inquiry[i].acct_statement_bill_id+'">';
                        temp_html += '<input type="hidden" id="statement_no" value="'+obj.statement_invoice_inquiry[i].statement_no+'">';
                        temp_html +=  '<label class="statement_label_tag hidden_panel">Due Date: </label><span data-statement-no=""> <input type="text" name="" class="form-control disabled_tag hidden_panel" value="'+month+'/'+day+'/'+year+'" disabled></span></div><div class="statement_label_wrapper" style="padding-top: 10px !important"><label class="statement_label_tag hidden_panel">Balance Due: </label><span> <input type="text" name="" class="form-control disabled_tag hidden_panel" value="'+parseFloat(total_due).toFixed(2)+'" disabled></span></div></div></div></div>';
                        if((counter % 2) == 0) {
                            temp_html += '</div>';
                        }
                        break;
                    }
                }
                $('#past_due_invoice').remove();
                $('#past_due_invoice_v2').html(temp_html);
            }
        });

        $('body').on('click','.view_pending_payments',function(){
            var _this = $(this);
            var invoice_id = $(this).attr("data-invoice-id");
            var data_hospice_id = $(this).attr("data-hospice-id");

            modalbox(base_url + 'billing_reconciliation/statement_pending_payments/',{
                header:"Pending Payments",
                button: false,
            });
        });

        // View statement bill Details
        $('body').on('click','.view_invoice_details',function(){
            var _this = $(this);
            var invoice_id = $(this).attr("data-invoice-id");
            var data_hospice_id = $(this).attr("data-hospice-id");
            var header_logo = "";
            header_logo += '<div class="pull-left" style="margin-left: 30px">';
            header_logo += '<p class="logo_ahmslv" style="margin-bottom:0px; text-align: center">';
            header_logo += '<img class="logo_ahmslv_img" src="<?php echo base_url("assets/img/smarterchoice_logo.png"); ?>" alt="" style="height:50px;width:58px;"/>';
            header_logo += '</p>';
            header_logo += '<h4 class="work_order_header_first" style="font-weight:bold;margin-top:0px;font-size:17px; text-align: center"> Advantage Home Medical Services, Inc </h4>';
            header_logo += '</div>';

            //Printer Loader
            header_logo += '<div class="loader-icon pull-right" style="margin-right: 50px; display: none !important">';
            header_logo += '<i class="printer_loader_icon fa fa-print"></i>';
            header_logo += '<div class="loader"></div>';
            header_logo += '</div>';
            header_logo += '<button class="btn btn-default loaded-icon pull-right" rel="popover" data-html="true" data-toggle="popover" data-trigger="hover" data-replacement="left" data-content="Print" onclick="window.print()" style="margin-right: 50px; display: block !important">';
            header_logo += '<i class="printer_loaded_icon fa fa-print"></i>';
            header_logo += '</button>';
            header_logo += '';
            modalbox(base_url + 'billing_statement/statement_activity_details/'+invoice_id+'/'+data_hospice_id,{
                header:header_logo+'<span style="line-height: 8rem; margin-left: -200px !important;">Invoice</span>',
                button: false,
            });
        });
    });
</script>