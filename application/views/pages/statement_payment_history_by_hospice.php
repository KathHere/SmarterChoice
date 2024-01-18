<style type="text/css">
    .address-style {
        font-weight: bold;
    }
    .statement_letter_label_tag {
        font-weight: bold;
        margin-right: 6px;
    }

    .year_header {
        font-weight: bold;
    }

    @media print {
        .bootstrap-dt-container {
            display: none !important;
        }

        .year_header {
            text-align: center !important;
        }
    }
</style>

<div class="payment_history_by_hospice_container">
    <div class="bg-light lter b-b wrapper-md">
        <h1 class="m-n font-thin h3">
            Payment History
        </h1>
    </div>

    <div class="wrapper-md pb0">
        <div class="form-group clearfix">
            <div class="col-sm-6" style="padding-left:5px;">
                <strong class="purchase_order_inquiry_info" >Account</strong>: <span class="" > <?php echo $hospice_details['hospice_name']; ?></span>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-bottom:5px !important;">
            <div class="col-sm-6" style="padding-left:5px;">
                <strong class="purchase_order_inquiry_info" >Account Number</strong>: <span class=" location_info" > <?php echo $hospice_details['hospice_account_number']; ?> </span>
            </div>
        </div>
    </div>

        

    <div class="wrapper-md">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <span class="col-md-6">Payment History List</span>
                    <span class="col-md-6 year_header">2019</span>
                </div>
                
            </div>
            <div class="panel-body" style="padding: 0px;">
                <table class="table table-hover bg-white b-a datatable_table_statement_draft">
                    <thead >
                        <th style="text-align: center;">Invoice Date</th>
                        <th style="text-align: center;">Due Date</th>
                        <th style="text-align: center;">Invoice Number</th>
                        <!-- <th style="text-align: center;">Account Number</th>
                        <th style="text-align: center;">Account Name</th> -->
                        <th style="text-align: center;">Balance Due</th>
                        <th style="text-align: center;">Payment Amount</th>
                        <th style="text-align: center;">Payment Type</th>
                        <th style="text-align: center;">Check Number</th>
                        <th style="text-align: center;">Receive Date</th>
                        <!-- <th style="text-align: center;">Received By</th> -->
                    </thead>
                    <tbody>
                        <?php
                        foreach($statement_paid_invoices as $statement_paid_invoice) {
                        ?>
                        <tr style="text-align: center">
                            <td><?php echo date('m/d/Y', strtotime($statement_paid_invoice['invoice_date'])); ?></td>
                            <td><?php echo date('m/d/Y', strtotime($statement_paid_invoice['receive_date'])); ?></td>
                            <td>
                                <div style="cursor: pointer" class="view_invoice_details" data-invoice-id="<?php echo $statement_paid_invoices['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $statement_paid_invoices['hospiceID']; ?>">
                                   <?php echo substr($statement_paid_invoice['statement_no'],3, 10); ?>
                                </div>
                            </td>
                            <!-- <td><?php echo $statement_paid_invoice['hospice_account_number']; ?></td>
                            <td><?php echo $statement_paid_invoice['hospice_name']; ?></td> -->
                            <td><?php echo number_format((float)$statement_paid_invoice['total'], 2, '.', ''); ?></td>
                            <td><?php echo number_format((float)$statement_paid_invoice['payment_amount'], 2, '.', ''); ?></td>
                            <td>
                                <?php
                                    if($statement_paid_invoice['payment_type'] == "credit_card") {
                                        echo "Credit Card";
                                    }
                                    if($statement_paid_invoice['payment_type'] == "check") {
                                        echo "Check";
                                    }
                                ?>
                            </td>
                            <td><?php echo $statement_paid_invoice['check_number']; ?></td>
                            <td><?php echo date('m/d/Y'); ?></td>
                            <!-- <td><?php echo $statement_paid_invoice['received_by']; ?></td> -->
                        </tr>
                        <?php    
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="wrapper-md">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <span class="col-md-6">Payment History List</span>
                    <span class="col-md-6 year_header">2018</span>
                </div>
                
            </div>
            <div class="panel-body" style="padding: 0px;">
                <table class="table table-hover bg-white b-a datatable_table_statement_draft">
                    <thead >
                        <th style="text-align: center;">Invoice Date</th>
                        <th style="text-align: center;">Due Date</th>
                        <th style="text-align: center;">Invoice Number</th>
                        <!-- <th style="text-align: center;">Account Number</th>
                        <th style="text-align: center;">Account Name</th> -->
                        <th style="text-align: center;">Balance Due</th>
                        <th style="text-align: center;">Payment Amount</th>
                        <th style="text-align: center;">Payment Type</th>
                        <th style="text-align: center;">Check Number</th>
                        <th style="text-align: center;">Receive Date</th>
                        <!-- <th style="text-align: center;">Received By</th> -->
                    </thead>
                    <tbody>
                        <?php
                        foreach($statement_paid_invoices as $statement_paid_invoice) {
                        ?>
                        <tr style="text-align: center">
                            <td><?php echo date('m/d/Y', strtotime($statement_paid_invoice['invoice_date'])); ?></td>
                            <td><?php echo date('m/d/Y', strtotime($statement_paid_invoice['receive_date'])); ?></td>
                            <td>
                                <div style="cursor: pointer" class="view_invoice_details" data-invoice-id="<?php echo $statement_paid_invoices['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $statement_paid_invoices['hospiceID']; ?>">
                                   <?php echo substr($statement_paid_invoice['statement_no'],3, 10); ?>
                                </div>
                            </td>
                            <!-- <td><?php echo $statement_paid_invoice['hospice_account_number']; ?></td>
                            <td><?php echo $statement_paid_invoice['hospice_name']; ?></td> -->
                            <td><?php echo number_format((float)$statement_paid_invoice['total'], 2, '.', ''); ?></td>
                            <td><?php echo number_format((float)$statement_paid_invoice['payment_amount'], 2, '.', ''); ?></td>
                            <td>
                                <?php
                                    if($statement_paid_invoice['payment_type'] == "credit_card") {
                                        echo "Credit Card";
                                    }
                                    if($statement_paid_invoice['payment_type'] == "check") {
                                        echo "Check";
                                    }
                                ?>
                            </td>
                            <td><?php echo $statement_paid_invoice['check_number']; ?></td>
                            <td><?php echo date('m/d/Y'); ?></td>
                            <!-- <td><?php echo $statement_paid_invoice['received_by']; ?></td> -->
                        </tr>
                        <?php    
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="wrapper-md">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <span class="col-md-6">Payment History List</span>
                    <span class="col-md-6 year_header">2017</span>
                </div>
                
            </div>
            <div class="panel-body" style="padding: 0px;">
                <table class="table table-hover bg-white b-a datatable_table_statement_draft">
                    <thead >
                        <th style="text-align: center;">Invoice Date</th>
                        <th style="text-align: center;">Due Date</th>
                        <th style="text-align: center;">Invoice Number</th>
                        <!-- <th style="text-align: center;">Account Number</th>
                        <th style="text-align: center;">Account Name</th> -->
                        <th style="text-align: center;">Balance Due</th>
                        <th style="text-align: center;">Payment Amount</th>
                        <th style="text-align: center;">Payment Type</th>
                        <th style="text-align: center;">Check Number</th>
                        <th style="text-align: center;">Receive Date</th>
                        <!-- <th style="text-align: center;">Received By</th> -->
                    </thead>
                    <tbody>
                        <?php
                        foreach($statement_paid_invoices as $statement_paid_invoice) {
                        ?>
                        <tr style="text-align: center">
                            <td><?php echo date('m/d/Y', strtotime($statement_paid_invoice['invoice_date'])); ?></td>
                            <td><?php echo date('m/d/Y', strtotime($statement_paid_invoice['receive_date'])); ?></td>
                            <td>
                                <div style="cursor: pointer" class="view_invoice_details" data-invoice-id="<?php echo $statement_paid_invoices['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $statement_paid_invoices['hospiceID']; ?>">
                                   <?php echo substr($statement_paid_invoice['statement_no'],3, 10); ?>
                                </div>
                            </td>
                            <!-- <td><?php echo $statement_paid_invoice['hospice_account_number']; ?></td>
                            <td><?php echo $statement_paid_invoice['hospice_name']; ?></td> -->
                            <td><?php echo number_format((float)$statement_paid_invoice['total'], 2, '.', ''); ?></td>
                            <td><?php echo number_format((float)$statement_paid_invoice['payment_amount'], 2, '.', ''); ?></td>
                            <td>
                                <?php
                                    if($statement_paid_invoice['payment_type'] == "credit_card") {
                                        echo "Credit Card";
                                    }
                                    if($statement_paid_invoice['payment_type'] == "check") {
                                        echo "Check";
                                    }
                                ?>
                            </td>
                            <td><?php echo $statement_paid_invoice['check_number']; ?></td>
                            <td><?php echo date('m/d/Y'); ?></td>
                            <!-- <td><?php echo $statement_paid_invoice['received_by']; ?></td> -->
                        </tr>
                        <?php    
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="lter wrapper-md hidden-print">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.datatable_table_statement_draft').DataTable( {
	        "order": [[ 1, "asc" ]],
            "columnDefs":[
                {
                    "targets": 0,
                    "searchable": false,
                    "orderable": false
                }
            ]
	    } );
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



    //SELECT ALL CHECKBOXES
    $('.datatable_table_statement_draft').on('click','.all-statementLetter-checkbox',function(){
        var statement_bill = $('.statementLetter-checkbox');
        if($(this).is(':checked'))
        {
            statement_bill.each(function(){
                $(this).prop('checked', true);
                $("body").find(".create-statementLetter-btn").removeAttr('disabled');
                $("body").find(".return-to-btn").removeAttr('disabled');
            });
        }
        else
        {
            statement_bill.each(function(){
                $(this).prop('checked', false);
                $("body").find(".create-statementLetter-btn").attr('disabled', true);
                $("body").find(".return-to-btn").attr('disabled', true);
            });
        }
    });

    //Check by one
    $('.datatable_table_statement_draft').on('click','.statementLetter-checkbox',function(){
        var this_element = $(this);
        var value = $(this).val();
        // var _this = $('.create-invoice:checked');

        if(this_element.is(':checked'))
        {
            $("body").find(".create-statementLetter-btn").removeAttr('disabled');
            $("body").find(".return-to-btn").removeAttr('disabled');
        }
        else
        {
            var _this = $('.statementLetter-checkbox:checked');
            if (_this.length == 0) {
                $("body").find(".create-statementLetter-btn").attr('disabled', true);
                $("body").find(".return-to-btn").attr('disabled', true);
            }
        }
    });

    //return to draft by checkboxes
    $('.return-to-btn').bind('click',function(){
        // var draft_id = $(this).attr("data-draft-id");

        jConfirm("Are you sure you want to return statement activity to draft?","Reminder",function(response){
            if(response)
            {

                me_message_v2({error:2,message:"Returning statement activity to draft. Please wait..."});
                var selected_invoices = $('.statementLetter-checkbox');
                var selected_inv = "";
                var counter = 0;
                selected_invoices.each(function(){
                    if($(this).is(':checked')) {
                        if(counter == 0) {
                            selected_inv = $(this).attr('data-invoice-id');
                        } else {
                            selected_inv = selected_inv + "-" + $(this).attr('data-invoice-id');
                        }
                        counter++;
                    }
                });

                console.log('selected_inv: ', selected_inv);
                $.get(base_url+'billing_statement/return_to_draft_statement_activity/'+selected_inv, function(response){
                    console.log("resepposnse:", response);
                    var obj = $.parseJSON(response);
                    console.log("closecloseclose:", obj);
                    // $('.close').click();
                    
                    setTimeout(function(){
                        if(obj['error'] == 0)
                        {
                            me_message_v2({error:0,message:obj['message']});
                            setTimeout(function(){
                                location.reload();
                            },2000);
                            console.log("closecloseclose_success");
                        } else {
                            me_message_v2({error:1,message:"Error!"});
                            // setTimeout(function(){
                            //     location.reload();
                            // },2000);
                            console.log("closecloseclose_error");
                        }
                    },1);
                    
                });
            }
        });
    });

</script>