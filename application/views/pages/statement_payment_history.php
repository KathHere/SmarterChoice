<style type="text/css">
    .address-style {
        font-weight: bold;
    }
    .statement_letter_label_tag {
        font-weight: bold;
        margin-right: 6px;
    }
    .statement_letter_label_wrapper {

    }

    .dot {
        height: 18px;
        min-width: 18px;
        margin-left: 5px;
        margin-right: 0px;
        background-color: #bbb;
        border-radius: 20px;
        display: inline-block;
        background-color: #23b7e5;
        color: white;
    }

    @media print {
        #DataTables_Table_0_wrapper .bootstrap-dt-container {
            display: none !important;
        }

        @page {
            size: landscape;
            margin: 0mm 2mm 10mm 2mm;
        }

        .payment_history_container {
           font-size: 7px !important;
        }

        .throw {
            padding: 0px !important;
        }

        /*.account_num{
            padding: -20px !important;
        }*/

        .hidden_comments {
            /*display: block !important;*/
        }

        .location_container {
            display: block !important;
        }

        .sample_div {
            margin-top: -45px !important;
        }
    }

    .location_container {
        /*position: absolute;*/
        /*margin-top: -10px;*/
        margin-left: 25px;
        font-size: 10px;
        /*top: 0;*/
        left: 0;
        display: none;
    }
</style>
<div class="payment_history_container">

    <div class="sample_div"></div>
    <div class="location_container">
        <strong>Date:</strong>  <?php echo date("m/d/Y"); ?>
        </br>
        <strong>Location:</strong>
        <?php
            $location = get_login_location($this->session->userdata('user_location'));
        ?>
        <span class="location_span">
        <?php
            echo $location['user_city'].', '.$location['user_state'];
        ?>
        </span>
    </div>
    <div class="bg-light lter b-b wrapper-md">
        <h1 class="m-n font-thin h3">
            Payment History
        </h1>
    </div>

    <div class="wrapper-md pb0">
        <div class="form-group clearfix">
            <div class="col-sm-6" style="padding-left:5px;">
                <strong class="purchase_order_inquiry_info" >Company</strong>: <span class="purchase_order_inquiry_info" > Advantage Home Medical Services</span>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-bottom:5px !important;">
            <div class="col-sm-6" style="padding-left:5px;">
                <?php 
                    $location = get_login_location($this->session->userdata('user_location')); 
                ?>
                <strong class="purchase_order_inquiry_info" >Location</strong>: <span class="purchase_order_inquiry_info location_info" > <?php echo $location['user_city'].", ".$location['user_state']; ?> </span>
            </div>
        </div>
    </div>  
        

    <div class="wrapper-md payment_history_dialog">
        <div class="panel panel-default">
            <div class="panel-heading">
                Payment History List
            </div>
            <div class="panel-body" style="padding: 0px;">
                <table class="table table-hover bg-white b-a datatable_table_statement_draft">
                    <thead >
                        <th class="throw" style="text-align: center;">Receive Date</th>
                        <th class="throw" style="text-align: center;">Invoice Date</th>
                        <th class="throw" style="text-align: center;">Due Date</th>
                        <th class="throw" style="text-align: center;">Invoice Number</th>
                        <th class="throw account_num" style="text-align: center;">Account Number</th>
                        <th class="throw" style="text-align: center;">Account Name</th>
                        <th class="throw" style="text-align: center;">Balance Due</th>
                        <th class="throw" style="text-align: center;">Payment Amount</th>
                        <th class="throw" style="text-align: center;">Payment Type</th>
                        <th class="throw" style="text-align: center;">Check Number</th>
                        <!-- <th class="throw" style="text-align: center;">Receive Date</th> -->
                        <th class="throw" style="text-align: center;">Received By</th>
                        <th class="throw" style="text-align: center;">Action</th>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 0;
                        foreach($statement_paid_invoices as $key => $statement_paid_invoice) {
                        ?>
                        <tr class="tdrow" style="text-align: center; <?php if($statement_paid_invoice['is_reverted']) { echo "background-color: #eaeff0;"; } ?>">
                            <td><?php echo date('m/d/Y', strtotime($statement_paid_invoice['receive_date'])); ?></td>
                            <td><?php echo date('m/d/Y', strtotime($statement_paid_invoice['invoice_date'])); ?></td>
                            <td><?php echo date('m/d/Y', strtotime($statement_paid_invoice['due_date'])); ?></td>
                            <td>
                                <div style="cursor: pointer" class="view_invoice_details" data-invoice-id="<?php echo $statement_paid_invoice['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $statement_paid_invoice['hospiceID']; ?>">
                                   <?php echo substr($statement_paid_invoice['statement_no'],3, 10); ?>
                                </div>
                            </td>
                            <td >
                                <?php echo $statement_paid_invoice['hospice_account_number']; ?>
                                &nbsp;
                                <a href="javascript:void(0)" data-invoice-id="<?php echo $statement_paid_invoice['acct_statement_invoice_id']; ?>" data-statement-no="<?php echo $statement_paid_invoice['statement_no']; ?>" name="comment-modal" style="text-decoration:none;cursor:pointer; text-align: center;" class="hidden-print view_comments notes_count_<?php echo $statement_paid_invoice['acct_statement_invoice_id']; ?>">
                                    <i class="icon-speech "></i>
                                    <p class="dot" style="float: right;margin-top: -3px;margin-right: 11px; <?php if($note_count[$key] == 0) { echo "background-color: #c3c2c2 !important;"; } ?>"><?php echo $note_count[$key]; ?></p>
                                </a>

                                <span style="display: none; text-decoration:none;cursor:pointer; text-align: center;" class="hidden_comments view_comments notes_count_<?php echo $statement_paid_invoice['acct_statement_invoice_id']; ?>">
                                    <i class="icon-speech "></i>
                                    <p class="dot" style="float: right;margin-top: -3px;margin-right: 11px; <?php if($note_count[$key] == 0) { echo "background-color: #c3c2c2 !important;"; } ?>"><?php echo $note_count[$key]; ?></p>
                                </span>
                            </td>
                            <td><?php echo $statement_paid_invoice['hospice_name']; ?></td>
                            <td><?php echo number_format((float)($statement_paid_invoice['total'] + $statement_paid_invoice['non_cap'] + $statement_paid_invoice['purchase_item']), 2, '.', ''); ?></td>
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
                            <!-- <td><?php echo date('m/d/Y', strtotime($statement_paid_invoice['receive_date'])); ?></td> -->
                            <td><?php echo $statement_paid_invoice['received_by']; ?></td>
                            <?php
                                if($statement_paid_invoice['is_reverted']) {
                            ?>
                                    <td>Ck Rtn</td>
                            <?php
                                } else {
                            ?>
                                    <td><button class="hidden-print btn btn-xs btn-danger revert_invoice_btn" data-received-payment-id="<?php echo $statement_paid_invoice['acct_statement_received_payment_id']; ?>" data-invoice-id="<?php echo $statement_paid_invoice['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $statement_paid_invoice['hospiceID']; ?>" data-invoice-number="<?php echo substr($statement_paid_invoice['statement_no'],3, 10); ?>">Revert</button></td>
                        </tr>
                        <?php 
                                }
                        $counter++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="bg-light lter wrapper-md hidden-print">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('.datatable_table_statement_draft').DataTable( {
	        "order": [[ 0, "desc" ]],
            "columnDefs":[
                {
                    "targets": 0,
                    "searchable": false,
                    "orderable": false
                }
            ]
	    } );
	});

    // View Comments
    $('body').on('click','.view_comments',function(){
        var _this = $(this);
        var statement_no = $(this).attr("data-statement-no");
        console.log('statement_no',statement_no);
        var invoice_id = $(this).attr("data-invoice-id");
        var data_hospice_id = $(this).attr("data-hospice-id");
        modalbox(base_url + 'billing_reconciliation/get_comments/'+invoice_id+'/'+statement_no,{
            header:"ACCOUNT INVOICE NOTE",
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

    //Revert Invoice
    $('body').on('click','.revert_invoice_btn',function(){
        var _this = $(this);
        var invoice_id = $(this).attr("data-invoice-id");
        var data_hospice_id = $(this).attr("data-hospice-id");
        var received_payment_id = $(this).attr("data-received-payment-id");
        var invoice_number = $(this).attr("data-invoice-number");
        jConfirm("Revert Invoice No. "+invoice_number+" ?","Reminder",function(response){
            if(response)
            {
                $.get(base_url+'billing_reconciliation/revert_payment_history/'+received_payment_id+'/'+invoice_id, function(response){
                    console.log("resepposnse:", response);
                    var obj = $.parseJSON(response);
                    
                    setTimeout(function(){
                        if(obj['error'] == 0)
                        {
                            me_message_v2({error:0,message:obj['message']});
                            setTimeout(function(){
                                location.reload();
                            },2000);
                        }
                    },1);
                    
                });
            }
        });
    });
</script>