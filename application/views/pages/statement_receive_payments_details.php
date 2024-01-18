<style type="text/css">
    #globalModal .modal-dialog {
        width: 900px;
    }

    #globalModal .modal-dialog .close {
        padding: 15px !important;
    }

    #globalModal .modal-dialog .modal-header {
        padding: 0px!important;
    }

    #globalModal .modal-dialog .inv-num {
        padding: 15px !important;
    }

    #globalModal .modal-dialog .rec-pay {
        padding: 15px !important;
    }

    #globalModal .modal-dialog .ajax_modal {
        padding-top: 0px !important;
        padding-bottom: 0px !important;
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

    .disabled_tag {
        background-color: white !important;
    }

    .close { display: none !important; }
</style>
<div style="padding-left: 10px; padding-right: 10px;">
    <div class="row">
        <div class="col-md-5" style="padding-left: 0px !important; padding-right: 0px !important; margin-left: -10px">
            <div style="height: 514px; border-bottom: 1px solid #e5e5e5; overflow-y: auto">
                
                <?php
                $is_first = false;
                $row_id = 0;
                foreach($selected_invoices as $value) {
                    $selectedColored = "background-color: white";
                    if($is_first == false) {
                        $selectedColored = "background-color: #eee";
                        $is_first = true;
                    }
                ?>

                <div class="inv_number inv_number_<?php echo $value['acct_statement_invoice_id']; ?> inv_num_<?php echo $row_id; ?>" data-invoice-no="<?php echo $value['acct_statement_invoice_id']; ?>" data-row-id="<?php echo $row_id; ?>" data-is-receive="0" data-hospice-id="<?php echo $value['hospiceID']; ?>" style="height:50px; <?php echo $selectedColored; ?>; padding: 15px; border-bottom: 1px solid #eee; cursor: pointer;">
                    <div class="row">
                        <div class="col-md-7">
                            <i class="fa fa-newspaper-o" style=" margin-right: 15px;"></i>
                            <span style=""><?php echo substr($value['statement_no'],3, 10); ?></span>
                        </div>
                        <div class="col-md-5 text-right receive_tag_<?php echo $row_id; ?>">
                            <!-- <span class="tags">RECEIVED</span> -->
                        </div>
                    </div>
                </div>
                <?php
                    $row_id++;
                }
                ?>
                <!-- <div style="height:50px; background-color: white; padding: 15px; border-bottom: 1px solid #eee; cursor: pointer;">
                    <div class="row">
                        <div class="col-md-7">
                            <i class="fa fa-newspaper-o" style=" margin-right: 15px;"></i>
                            <span style="">7284382</span>
                        </div>
                        <div class="col-md-5 text-right">
                            <span class="tags">RECEIVED</span>
                        </div>
                    </div>
                </div> -->
               
            </div>
            <!-- <label class="statement_letter_label_tag" style=>Account Name:</label> -->
            <div style="text-align: center !important">
                <button class="btn btn-md btn-success close_receive_payment_modal" style="margin-top: 20px; ">Submit</button>
            </div>
            
        </div>

        
        <div class="col-md-7" style="border-left: 1px solid #e5e5e5; padding-left: 30px; padding-top: 20px">
            <?php
                $is_first = false;
                $row_id = 0;
                foreach($selected_invoices as $key => $value) {
                    $displayNone = "display: none";
                    if($is_first == false) {
                        $displayNone = "";
                        $is_first = true;
                    }
            ?>
            <div class="rec_payments receive_payments_<?php echo $value['acct_statement_invoice_id']; ?>" style="<?php echo $displayNone; ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Account Name:</label>
                            <span><input type="text" name="rec_pay_account_name_<?php echo $row_id; ?>" class="form-control disabled_tag account_name_<?php echo $row_id; ?>" id="" placeholder="" style="margin-left:0px" value="<?php echo $value['hospice_name']; ?>" disabled></span>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Account Name:</label>
                            <span><input type="text" name="" class="form-control" id="" placeholder="" style="margin-left:0px" value="" disabled></span>
                        </div> -->
                        <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Account Number:</label><span><input type="text" name="rec_pay_account_number_<?php echo $row_id; ?>" class="form-control disabled_tag account_number_<?php echo $row_id; ?>" id="" placeholder="" style="margin-left:0px" value="<?php echo $value['hospice_account_number']; ?>" disabled></span>
                        </div>
                        <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Invoice Number:</label><span><input type="text" name="rec_pay_invoice_number_<?php echo $row_id; ?>" class="form-control disabled_tag invoice_number_<?php echo $row_id; ?>" id="" placeholder="" style="margin-left:0px" value="<?php echo substr($value['statement_no'],3, 10); ?>" disabled></span>
                            <input type="hidden" name="" class="invoice_number_hidden_<?php echo $row_id; ?>" value="<?php echo $value['statement_no']; ?>">
                            <input type="hidden" name="rec_pay_invoice_date_<?php echo $row_id; ?>" class="form-control disabled_tag invoice_date_<?php echo $row_id; ?>" id="" placeholder="" style="margin-left:0px" value="<?php echo date("m/d/Y", strtotime($value['invoice_date'])); ?>" disabled>
                        </div>
                        <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Service Date:</label><span><input type="text" name="rec_pay_date_of_service_<?php echo $row_id; ?>" class="form-control disabled_tag date_of_service_<?php echo $row_id; ?>" id="" placeholder="" style="margin-left:0px"  value="<?php echo date('m/d/Y', strtotime($value['service_date_from'])); ?> - <?php echo date("m/d/Y", strtotime(date("m/d/Y", strtotime($value['service_date_to']))));?>" disabled></span>
                        </div>
                        <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Sent Date:</label><span><input type="text" name="rec_pay_sent_date_<?php echo $row_id; ?>" class="form-control disabled_tag sent_date_<?php echo $row_id; ?>" id="" placeholder="" style="margin-left:0px" value="<?php echo $value['email_sent_date']?>" disabled></span>
                        </div>
                        <!-- <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Invoice Date:</label><span><input type="text" name="date_of_service" class="form-control datepicker" id="" placeholder="" style="margin-left:0px"></span>
                        </div> -->
                        <!-- <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Credit:</label><span><input type="text" name="" class="form-control" id="" placeholder="" style="margin-left:0px" value=""></span>
                        </div> -->
                        <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Balance Due:</label>
                            <span>
                                <?php 
                                    $total_balance_due = 0; 
                                    $total_balance_due += $value['total']; 
                                    $total_balance_due += $value['non_cap']; 
                                    $total_balance_due += $value['purchase_item']; 
                                    $total_balance_due -= $invoices_reconciliation[$key]['credit'];
                                    $total_balance_due += $invoices_reconciliation[$key]['owe'];
                                ?>
                                <input type="text" name="rec_pay_balance_due_<?php echo $row_id; ?>" class="form-control disabled_tag balance_due_<?php echo $row_id; ?>" id="" placeholder="" style="margin-left:0px" 
                                value="<?php echo number_format((float)$total_balance_due, 2, '.', ''); ?>" disabled>
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Received Date:<span class="text-danger-dker">*</span></label><span><input type="text" name="rec_pay_received_date_<?php echo $row_id; ?>" class="form-control datepicker rec_pay_received_date_<?php echo $row_id; ?>" id="" placeholder="" value="<?php if($value['payment_code'] != "" || $value['payment_code'] != null) { echo date("Y-m-d", strtotime($value['payment_date'])); } ?>" style="margin-left:0px" ></span>
                        </div>
                        <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Payment Type:<span class="text-danger-dker">*</span></label>
                            <span>
                                <?php if($value['payment_code'] == null || $value['payment_code'] == "") {?>
                                <select name="rec_pay_payment_type_<?php echo $row_id; ?>" class="form-control rec_pay_payment_type_<?php echo $row_id; ?> payment_type" data-row-id="<?php echo $row_id; ?>">
                                    <option value="select">- Please Select -</option>
                                    <option value="check">Check</option>
                                    <option value="credit_card">Credit Card</option>
                                </select>
                                <?php } else {
                                    $pay_type = "";
                                    if($value['payment_type'] == "check") {
                                        $pay_type = "check";
                                    } else {
                                        $pay_type = "credit_card";
                                    }
                                    ?>
                                    <input name="rec_pay_payment_type_<?php echo $row_id; ?>" class="form-control disabled_tag rec_pay_payment_type_<?php echo $row_id; ?> payment_type" data-row-id="<?php echo $row_id; ?>" value="<?php echo $pay_type; ?>" disabled>
                                <?php }?>
                                <!-- <input type="text" name="date_of_service" class="form-control" id="" placeholder="" style="margin-left:0px"> -->
                            </span>
                        </div>
                        <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Check Number:</label><span><input type="number" name="rec_pay_check_number_<?php echo $row_id; ?>" class="form-control disabled_tag check_number_<?php echo $row_id; ?>" data-row-id="<?php echo $row_id; ?>" id="" placeholder="" style="margin-left:0px" disabled></span>
                        </div>
                        <div class="statement_letter_label_wrapper">
                            <?php 
                            $disabled_payment = "";
                            $reconciliation_popup_class = "";
                            $payamount = 0;
                            if($value['payment_code'] != "" || $value['payment_code'] != null) {
                                $disabled_payment = "disabled";
                                $payamount = $value['payment_amount'];
                                // $reconciliation_popup_class = "reconciliation_popup";
                            } else {
                                $payamount = number_format((float)$total_balance_due, 2, '.', '');
                            }
                            ?>
                            <label class="statement_letter_label_tag">Payment Amount:<span class="text-danger-dker">*</span></label><span><input type="text" name="rec_pay_payment_amount_<?php echo $row_id; ?>" class="form-control disabled_tag rec_pay_payment_amount_<?php echo $row_id; ?> payment_amount" data-row-id="<?php echo $row_id; ?>" id="" placeholder="" style="margin-left:0px" <?php echo $disabled_payment; ?> value="<?php echo $payamount; ?>"></span>
                        </div>
                        
                        <?php
                        if($value['payment_code'] != "" || $value['payment_code'] != null) {
                            echo 
                            '<script type="text/javascript">
                                console.log("nasulod sa auto 123");
                                $(document).ready(function(){
                                    var row_id = $(".payment_amount").attr("data-row-id");
                                    var payment_amount = parseFloat($(".payment_amount").val());
                                    var balance_due = parseFloat($(".balance_due_"+row_id).val());
                                    var amt = parseFloat(balance_due) - payment_amount;
                                    console.log("amt",amt);
                                    console.log("payment_amount",payment_amount);
                                    var credit = 0;
                                    var amount_owe = 0;

                                    $(".recon_payment_amount").val(payment_amount);
                                    $(".recon_credit").val(credit);
                                    $(".recon_amount_owe").val(amount_owe);
                                    if(amt > 0) {
                                        amount_owe = amt;
                                        $(".rec_inv_btn").addClass("reconciliation_popup");
                                        $(".rec_inv_btn").removeClass("receive_invoice_btn");
                                        $(".recon_amount_owe").val(amount_owe);

                                        console.log("addClass amount_owe");
                                    }
                                    else if (amt < 0) {
                                        credit = Math.abs(amt);
                                        $(".rec_inv_btn").addClass("reconciliation_popup");
                                        $(".rec_inv_btn").removeClass("receive_invoice_btn");
                                        $(".recon_credit").val(credit);
                                        console.log("addClass credit");
                                    }
                                    else {
                                        $(".rec_inv_btn").addClass("receive_invoice_btn");
                                        $(".rec_inv_btn").removeClass("reconciliation_popup");

                                        console.log("removeClass");
                                    }
                                });
                            </script>';
                        } 
                        
                        $fname = $this->session->userdata('firstname');
                        $lname = $this->session->userdata('lastname'); 
                        $lname_cut = substr($this->session->userdata('lastname'),0,1);
                        ?>
                        <div class="statement_letter_label_wrapper">
                            <label class="statement_letter_label_tag">Received By:</label><span><input type="text" name="rec_pay_received_by_<?php echo $row_id; ?>" class="form-control disabled_tag rec_pay_received_by_<?php echo $row_id; ?>" id="" placeholder="" style="margin-left:0px" value="<?php echo $fname." ".$lname_cut."." ?>" disabled></span>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <?php
                    $row_id++;
                }
            ?>
            
            <!-- <div class="statement_letter_label_wrapper">
                <label class="statement_letter_label_tag">Note:</label><span><textarea id="notes" class="form-control" style="width: 100%; height: 51px; border: none; padding: 10px; resize: none" placeholder="Enter note..."></textarea></span>
            </div> -->
            <hr />
            <div class="row">
                <div class="col-md-9">
                    
                </div>
                <div class="col-md-3">
                    <button class="btn btn-md btn-info pull-right rec_inv_btn receive_invoice_btn <?php echo $reconciliation_popup_class; ?>" data-row-id="0" style="margin-bottom: 18px">Receive</button>
                </div>    
            </div>
            
        </div>
    </div>
    
</div>



<script type="text/javascript">
    var payment_amount_auto = function() {
        var row_id = $(this).attr('data-row-id');
        var payment_amount = parseFloat($(this).val());
        var balance_due = parseFloat($('.balance_due_'+row_id).val());
        var amt = parseFloat(balance_due) - payment_amount;
        console.log('amt',amt);
        console.log('payment_amount',payment_amount);
        var credit = 0;
        var amount_owe = 0;

        $('.recon_payment_amount').val(payment_amount);
        $('.recon_credit').val(credit);
        $('.recon_amount_owe').val(amount_owe);
        if(amt > 0) {
            amount_owe = amt;
            $('.rec_inv_btn').addClass("reconciliation_popup");
            $('.rec_inv_btn').removeClass("receive_invoice_btn");
            $('.recon_amount_owe').val(amount_owe);

            console.log('addClass amount_owe');
        }
        else if (amt < 0) {
            credit = Math.abs(amt);
            $('.rec_inv_btn').addClass("reconciliation_popup");
            $('.rec_inv_btn').removeClass("receive_invoice_btn");
            $('.recon_credit').val(credit);
            console.log('addClass credit');
        }
        else {
            $('.rec_inv_btn').addClass("receive_invoice_btn");
            $('.rec_inv_btn').removeClass("reconciliation_popup");

            console.log('removeClass');
        }
    };
    $(document).ready(function(){
        //receive_invoice_btn
        //change class to rec_inv_btn IF you want to receive payment whether IF with underpay or overpay
        //change class to receive_invoice_btn IF you want to receive payment only without underpay or overpay
        //ALSO need to add functionility to receive when reconciliation popups
        $('body').on('click','.rec_inv_btn',function(){
            var _this = $(this);
            var row_id = $(this).attr('data-row-id');

            //invoice info ==== START
            var invoice_id = $('.inv_num_'+row_id).attr('data-invoice-no'); // invoice id
            var recon_receive_date = $('.rec_pay_received_date_'+row_id).val();
            var recon_payment_type = $('.rec_pay_payment_type_'+row_id).val();
            var recon_check_number = $('.check_number_'+row_id).val();
            var recon_payment_amount = $('.rec_pay_payment_amount_'+row_id).val();
            var recon_received_by = $('.rec_pay_received_by_'+row_id).val();
            //invoice info ==== END
            console.log('recon_payment_type', recon_payment_type);
            var error_message = "";
            var is_error = 0;
            if(recon_receive_date == null || recon_receive_date == "") {
                error_message += "Received Date is required.</br>";
                is_error = 1;
            }
            
            if(recon_payment_amount == null || recon_payment_amount == "") {
                error_message += "Payment Amount is required.</br>";
                is_error = 1;
            }

            if(recon_payment_type == null || recon_payment_type == "" || recon_payment_type == "select") {
                error_message += "Payment Type is required.</br>";
                is_error = 1;
            } else {
                if(recon_payment_type == "check") {
                    if(recon_check_number == null || recon_check_number == "") {
                        error_message += "Check Number is required.</br>";
                        is_error = 1;
                    }
                }
            }

            if(is_error) {
                me_message_v2({error:1,message:error_message});
            } else {
                $.get(base_url+'billing_reconciliation/receive_payment/'+invoice_id+'/'+recon_receive_date+'/'+recon_payment_type+'/'+recon_payment_amount+'/'+recon_received_by+'/'+recon_check_number+'/', function(response){
                    var obj = $.parseJSON(response);            // $('.close').click();
                    
                    setTimeout(function(){
                        if(obj['error'] == 0)
                        {
                            $('.receive_tag_'+row_id).html('<span class="tags">RECEIVED</span>');
                            $('.inv_num_'+row_id).attr('data-is-receive', '1');
                            $('.rec_inv_btn').prop('disabled', true);
                            var selected_count = parseInt($('.selected_count').attr('data-selected-count'));
                            selected_count++;
                            $('.selected_count').html(selected_count);
                            $('.selected_count').attr('data-selected-count', selected_count);

                            me_message_v2({error:0,message:obj['message']});
                            // setTimeout(function(){
                            //     location.reload();
                            // },2000);
                        } else {
                            me_message_v2({error:1,message:obj['message']});
                        }
                    },1);
                });
            }
        });

        //close_receive_payment_modal
        $('body').on('click','.close_receive_payment_modal',function(){
            var selected_count = $('.selected_count').attr('data-selected-count');
            var selected_limit = $('.selected_limit').attr('data-selected-limit');

            if(selected_count < selected_limit) {
                jConfirm("Are you sure you don't want to receive the remaining invoice(s)?", 'Reminder', function(response){
                    if(response)
                    {
                        // $('#globalModal').modal('hide');
                        setTimeout(function(){
                            location.reload();
                        },500);
                    }
                });
            } else {
                // $('#globalModal').modal('hide');
                setTimeout(function(){
                    location.reload();
                },500);
            }
        });

        //reconciliation_popup
        $('body').on('click','.reconciliation_popup',function(){
            var _this = $(this);
            var row_id = $(this).attr('data-row-id');

            //Trapping ==== START
            var recon_receive_date = $('.rec_pay_received_date_'+row_id).val();
            var recon_payment_type = $('.rec_pay_payment_type_'+row_id).val();
            var recon_check_number = $('.check_number_'+row_id).val();
            var recon_payment_amount = $('.rec_pay_payment_amount_'+row_id).val();
            var recon_received_by = $('.rec_pay_received_by_'+row_id).val();
            
            var error_message = "";
            var is_error = 0;
            if(recon_receive_date == null || recon_receive_date == "") {
                error_message += "Received Date is required.</br>";
                is_error = 1;
            }
            
            if(recon_payment_amount == null || recon_payment_amount == "") {
                error_message += "Payment Amount is required.</br>";
                is_error = 1;
            }

            if(recon_payment_type == null || recon_payment_type == "" || recon_payment_type == "select") {
                error_message += "Payment Type is required.</br>";
                is_error = 1;
            } else {
                if(recon_payment_type == "check") {
                    if(recon_check_number == null || recon_check_number == "") {
                        error_message += "Check Number is required.</br>";
                        is_error = 1;
                    }
                }
            }
            //Trapping ==== END

            if(is_error) {
                me_message_v2({error:1,message:error_message});
            } else {
                //invoice info ==== START
                var account_name = "";
                var invoice_date = "";
                var invoice_number = 0;
                var balance_due = 0;
                var invoice_num = $('.inv_num_'+row_id).attr('data-invoice-no');
                var hospiceID = $('.inv_num_'+row_id).attr('data-hospice-id');

                account_name = $('.account_name_'+row_id).val();
                invoice_date = $('.invoice_date_'+row_id).val();
                invoice_number = $('.invoice_number_hidden_'+row_id).val();
                balance_due = $('.balance_due_'+row_id).val();
                //invoice info ==== END

                $('.invoice_notes_invnumber').html(invoice_number.substr(3,7));
                
                $('.recon_account_id').val(hospiceID);
                $('.recon_account_name').val(account_name);
                $('.recon_invoice_date').val(invoice_date);
                $('.sh_recon_invoice_number').val(invoice_number.substr(3,7));
                $('.recon_invoice_number').val(invoice_number);
                $('.recon_balance_due').val(balance_due == 0 ? "" : balance_due);
                $('.reconciliation_invoice_id').val(invoice_num);
                $('#reconciliation_popup_modal').modal("show");

                console.log('account_name',account_name);
                console.log('invoice_date',invoice_date);
                console.log('invoice_number',invoice_number);
            }
            
        });

        var globalTimeout = null;
        $('.payment_amount').bind('keyup',function(){
            var row_id = $(this).attr('data-row-id');
            var payment_amount = parseFloat($(this).val()).toFixed(2);
            var balance_due = parseFloat($('.balance_due_'+row_id).val()).toFixed(2);
            var amt = parseFloat(balance_due - payment_amount).toFixed(2);
            console.log('amt',amt);
            console.log('payment_amount',payment_amount);
            var credit = 0;
            var amount_owe = 0;

            if(globalTimeout != null) clearTimeout(globalTimeout);
            globalTimeout =setTimeout(getInfoFunc,500);
            function getInfoFunc(){
                globalTimeout = null;
                $('.recon_payment_amount').val(payment_amount);
                $('.recon_credit').val(credit < 1 ? "" : credit);
                $('.recon_amount_owe').val(amount_owe < 1 ? "" : amount_owe);
                if(amt > 0) {
                    amount_owe = amt;
                    $('.rec_inv_btn').addClass("reconciliation_popup");
                    $('.rec_inv_btn').removeClass("receive_invoice_btn");
                    $('.recon_amount_owe').val(amount_owe < 1 ? "" : amount_owe);

                    console.log('addClass amount_owe');
                }
                else if (amt < 0) {
                    credit = Math.abs(amt).toFixed(2);
                    $('.rec_inv_btn').addClass("reconciliation_popup");
                    $('.rec_inv_btn').removeClass("receive_invoice_btn");
                    $('.recon_credit').val(credit < 1 ? "" : credit);
                    console.log('addClass credit');
                }
                else {
                    $('.rec_inv_btn').addClass("receive_invoice_btn");
                    $('.rec_inv_btn').removeClass("reconciliation_popup");

                    console.log('removeClass');
                }
            }
        });

        $('body').on('change','.payment_type',function(){
            var row_id = $(this).attr('data-row-id');
            var chk_num = $('.check_number_'+row_id);
            if($(this).val() == "select" || $(this).val() == "check") {
                chk_num.prop('disabled', false);
            } else {
                chk_num.prop('disabled', true);
                chk_num.val("");
            }
            console.log('row_id: ', row_id);
        });
        //
        $('body').on('click','.inv_number',function(){
            var inv_number = $(this).attr('data-invoice-no');
            var inv_nos = $('.rec_payments');
            var sideNav = $('.inv_number');
            var isReceive = $(this).attr('data-is-receive');

            //Disable if received
            if(isReceive == 0) {
                $('.rec_inv_btn').prop('disabled', false);
            } else {
                $('.rec_inv_btn').prop('disabled', true);
            }

            //For Receiving Invoice ======== START
            var row_id = $(this).attr('data-row-id');
            var receive_invoice_button = $('.rec_inv_btn').attr('data-row-id', row_id);
            //For Receiving Invoice ======== END

            sideNav.each(function(){
                $(this).css({"height": "50px", "background-color": "white", "padding": "15px", "border-bottom": "1px solid #eee", "cursor": "pointer"});
            });
            inv_nos.each(function(){
                $(this).hide();
                
            });
            $('.receive_payments_'+inv_number).show();
            $('.inv_number_'+inv_number).css({"height": "50px", "background-color": "#eee", "padding": "15px", "border-bottom": "1px solid #eee", "cursor": "pointer"});
            //height:50px; background-color: white; padding: 15px; border-bottom: 1px solid #eee; cursor: pointer;
        });
    });
</script>