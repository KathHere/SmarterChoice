<style type="text/css">
    .disabled_tag {
        background-color: white !important;
    }

    .address-style {
        font-weight: bold;
    }
    .statement_letter_label_tag {
        font-weight: bold !important;
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

    .make_payment_field {
        padding-left: 25px !important;
        padding-right: 25px !important;
    }

    .agreement_header {
        font-weight: bold;
        margin-bottom: 10px;
        margin-left: 30px;
    }

    .agreement_content {
        margin-left: 30px;
        margin-bottom: 7px;
        text-align: justify;
    }

    .agreemnt_separtor {
        text-align: center;
        font-weight: bold;
        margin-bottom: 7px;
    }

    .agreement_wrapper {
        /* height: 400px;
        overflow-y: auto; */
        border: 1px solid #cfdadd;
        padding: 10px;
        border-radius: 5px;
    }

    .align-horizontal {
        display: inline-block;
		position: relative;
    }
</style>

<div class="bg-light lter b-b wrapper-md">
    <h1 class="m-n font-thin h3">
        Make Payment
        <div class="pull-right">
            <a href='<?php echo base_url(); ?>billing_reconciliation/payment_history_by_hospice/<?php echo $hospice_id; ?>' class="btn btn-primary btn-sm" style="font-size:13px !important;">
                Payment History
            </a>
        </div>
    </h1>
</div>


<div class="" id="reconciliation_popup_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  style="z-index: 10010 !important">
    <div class="modal-dialog" style="width: 800px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title row" style="font-weight: bold; text-align: center;">Make Payment</h4>
            </div>
            <div class="modal-body">
                <div class="make_payment_field statement_letter_label_wrapper">
                    <label class="statement_letter_label_tag">
                        <div class="checkbox" style="margin-top:5px">
                            <label class="i-checks statement_letter_label_tag">
                                <input type="checkbox" name="all_radio" id="" class="payment_type payment_type_checkbox"  value="0" checked><i></i>Pay Balance Due:
                            </label>
                        </div>
                    </label>
                   <!--  <span><input type="text" name="" class="form-control disabled_tag payment_paymentDue"  style="margin: 0px !important" value="<?php echo number_format((float)$total_payment_due['total_payment_due'], 2, '.', ''); ?>" disabled></span> -->
                   <span><input type="text" name="" class="form-control disabled_tag payment_paymentDue"  style="margin: 0px !important" value="<?php echo number_format((float)$tbd_total, 2, '.', ''); ?>" disabled></span>
                </div>

                <input type="hidden" id="pay_balance_due" name="" value="<?php echo $total_balance_due['acct_statement_invoice_id']; ?>">

                <?php
                    foreach($statement_invoice_payment_due as $value) {
                ?>
                        <input type="hidden" name="" id="" class="payment_due_invoices" value="<?php echo $value['acct_statement_invoice_id']; ?>">
                <?php
                    }
                ?>

                <div class="make_payment_field statement_letter_label_wrapper">
                    <label class="statement_letter_label_tag">
                        <label class="statement_letter_label_tag">
                            <div class="checkbox" style="margin-top:5px">
                                <label class="i-checks statement_letter_label_tag">
                                    <input type="checkbox" name="all_radio" id="" class="payment_type payment_type_checkbox"  value="1"><i></i>Pay Total Past Due:
                                </label>
                            </div>
                        </label>
                    </label>
                    <!-- <span><input type="text" name="" class="form-control disabled_tag payment_totalBalanceDue"  style="margin: 0px !important" value="<?php echo number_format((float)$total_balance_due['total'], 2, '.', ''); ?>" disabled></span> -->
                    <span><input type="text" name="" class="form-control disabled_tag payment_totalBalanceDue"  style="margin: 0px !important" value="<?php echo number_format((float)$past_due_amount, 2, '.', ''); ?>" disabled></span>                                       
                </div>

                <div class="make_payment_field statement_letter_label_wrapper">
                    <label class="statement_letter_label_tag">
                        <label class="statement_letter_label_tag">
                            <div class="radio" style="margin-top:5px">
                                <label class="i-checks statement_letter_label_tag">
                                    <input type="radio" name="all_radio" id="other_payment_type" class="payment_type payment_type_radio"  value="2"><i></i>Other:
                                </label>
                            </div>
                        </label>
                    </label>
                    <span><input type="text" name="" class="form-control disabled_tag payment_other disabled_tag"  style="margin: 0px !important" disabled></span>
                </div>
                <input type="hidden" name="" class="amount_payment_field" value="0">
                <!-- <input type="hidden" name="" id="total_balance_due_val" value="<?php echo number_format((float)$total_balance_due, 2, '.', ''); ?>"> -->

                <?php 
                    foreach($statement_invoice_inquiry_v2 as $value) {
                ?>
                        <input type="hidden" name="" id="" class="past_due_invoices" value="<?php echo $value['acct_statement_invoice_id']; ?>">
                <?php
                    }
                ?>
                <!-- <div class="row form-group" style="text-align: center">
                    <div class="radio col-md-4" style="margin-top:5px">
                        <label class="i-checks statement_letter_label_tag">
                            <input type="radio" name="all_radio" id="" class="choose_account_type payment_type"  value="0" checked><i></i>Payment Due
                        </label>
                    </div>
                    <div class="radio col-md-4" style="margin-top:5px">
                        <label class="i-checks statement_letter_label_tag">
                            <input type="radio" name="all_radio" id="" class="choose_account_type payment_type"  value="1"><i></i>Total Balance
                        </label>
                    </div>
                    <div class="radio col-md-4" style="margin-top:5px">
                        <label class="i-checks statement_letter_label_tag">
                            <input type="radio" name="all_radio" id="" class="choose_account_type payment_type"  value="2"><i></i>Other
                        </label>
                    </div>
                </div>
                <div class="make_payment_field statement_letter_label_wrapper">
                    <input type="text" name="" class="form-control amount_payment_field disabled_tag" value="" disabled>
                    <input type="hidden" name="" id="total_balance_due_val" value="<?php echo number_format((float)$total_balance_due, 2, '.', ''); ?>">

                    <?php 
                        foreach($statement_invoice_inquiry as $value) {
                    ?>
                    <input type="hidden" name="" id="" class="past_due_invoices" value="<?php echo $value['acct_statement_invoice_id']; ?>">
                    <?php
                        }
                    ?>
                </div> -->
                <hr></hr>
                <div class="make_payment_field statement_letter_label_wrapper">
                    <label class="statement_letter_label_tag">Payment Date:</label><span><input type="text" name="" class="form-control datepicker date_payment"  style="margin: 0px !important"></span>
                </div>
                <div class="make_payment_field statement_letter_label_wrapper">
                    <label class="statement_letter_label_tag">Confirmation Email:</label><span><input type="email" name="" class="form-control email_payment"  style="margin: 0px !important"></span>
                </div>
                <div class="make_payment_field statement_letter_label_wrapper" style="text-align: center">
                    
                    <div class="">
                        <div class="statement_letter_label_wrapper align-horizontal">
                            <div class="checkbox" style="text-align: center;">
                                <label class="i-checks statement_letter_label_tag disable_agree_label" style="">
                                    <input type="checkbox" name="all_radio" id="" class="disable_agree_input agreement_checkbox"  value="0"><i></i>
                                </label>
                            </div>
                        </div>
                        <button class="statement_letter_label_tag btn btn-default align-horizontal" style="text-align: center !important; background-color: #dee5e7 !important" type="button" data-toggle="collapse" data-target="#collapse_payment_agreement" aria-expanded="false" aria-controls="collapse_payment_agreement">
                            I agree to Terms and Conditions
                            <!-- <i class="fa fa-chevron-down" ></i> -->
                        </button>
                    </div>
                    <div class="collapse" id="collapse_payment_agreement">
                        <div class="agreement_wrapper grey_inner_shadow" id="payment_agreement">
                            <div class="" style="font-weight: bold; margin-bottom: 10px;">One-Time Payment Terms of Service:</div>
                            <div class="agreement_content" style="margin-left: 60px !important; margin-right: 60px !important">By clicking Submit, I authorize Advantage Home Medical Services (AHMS) to initiate a one-time charge or debit entry in the amount indicated above on the credit card or banking account I select, as full or partial payment for services provided by AHMS (plus applicable taxes, fees and surcharges). Payments returned to AHMS by your financial institution for any reason will incur a non-sufficient funds (NSF) fee or $25 or up to the maximum allowed by state law payable to AHMS. The use of a paper or electronic check for payment is your acknowledgement that if your payment is returned you expressly authorize your account to be electronically debited for the amount of the payment plus any applicable NSF fees. You agree that AHMS will not be responsible for any expenses that you may incur from exceeding to this payment.</div>
                            <div class="" style="font-weight: bold; margin-bottom: 10px;">Stored Payment Methods:</div>
                            <div class="agreement_content" style="margin-left: 60px !important; margin-right: 60px !important">If you provide AHMS with any account information, such as your bank account and routing numbers of your credit and debit card details, we may store the information and use it to administer your account, confirm charges, detect and prevent fraud, verify your identity, process payments to your account that you request in the future by telephone, mobile app, internet, or otherwise, and comply with the applicable data security protocols, including but not limited to the Payment Card Industry Data Security Standard. Additionally, AHMS may, without prior notice you, use your stored account information to initiate credit or debit entries to your account as necessary to correct any mistakes or amendments in billing, payments, or collection.</div>
                            <!-- <div class="" style="font-weight: bold; margin-bottom: 10px;">KNOW ALL MEN BY THESE PRESENTS:</div>
                            <div class="agreement_content">This Contract is entered by and between:</div>
                            <div class="agreement_content" style="margin-left: 60px !important">{debtorName}, of legal age, {civilStatus}, {citizenship19}, whose principal place of address is at {debtorAddress:addr_line1} {debtorAddress:addr_line2} {debtorAddress:city}, {debtorAddress:state}, {debtorAddress:country}, {debtorAddress:postal}, (hereinafter referred to as "DEBTOR");</div>
                            <div class="agreemnt_separtor">- and -</div>
                            <div class="agreement_content" style="margin-left: 60px !important">{creditorName}, of legal age, {civilStatus17}, {citizenship}, whose principal place of address is at {creditorAddress:addr_line1} {creditorAddress:addr_line2} {creditorAddress:city}, {creditorAddress:state}, {creditorAddress:country}, {creditorAddress:postal}, (hereinafter referred to as "CREDITOR");</div>
                            <div class="agreemnt_separtor">WITNESSETH: That -</div>
                            <div class="agreement_content">WHEREAS, the DEBTOR has an obligation to the creditor for an amount of {totalContract};</div>
                            <div class="agreement_content">WHEREAS, the DEBTOR and the CREDITOR, by the goodwill of both parties, desire to secure the amount of debt by entering into an agreement whereby the sum of {totalContract} shall be set into a payment plan to the terms and conditions herein provided;</div>
                            <div class="agreement_content">NOW, THEREFORE, for and in consideration of the foregoing premises, the parties hereto agree as follows:</div>
                            <div class="agreement_header">1. Acknowledgment of Deficiency</div>
                            <div class="agreement_content">The DEBTOR agrees and acknowledges that a debt exist to an amount stated above. </div>
                            <div class="agreement_header">2. Debtor Representation</div>
                            <div class="agreement_content">The DEBTOR hereby represents and warrants that this Agreement and the payment plan herein has been developed in a manner that the Debtor reasonably believes it can pay the CREDITOR without further interruption notwithstanding an additional charge in circumstances.</div>
                            <div class="agreement_header">3. Payment Plan</div>
                            <div class="agreement_content">The Parties hereby agree to the payment plan as described on Exhibit A attached hereto (the "Payment Plan"). The DEBTOR agrees to make the payments to the CREDITOR associated with the dates as listed on the Schedule of Payments table.</div>
                            <div class="agreement_header">4. Method of Payment </div>
                            <div class="agreement_content">Payment shall be made to the CREDITOR in accordance to the Method as indicated in the Payment Plan.</div>
                            <div class="agreement_header">5. Release and Indemnification</div>
                            <div class="agreement_content">In consideration for agreeing to this Payment Agreement, the CREDITOR hereby releases any claims against the DEBTOR related to the deficiency as to the date of this Agreement. However, nothing in this Agreement is meant to release the DEBTOR from its obligation to pay the deficiency according to the Payment Plan herein or limit the rights of the CREDITOR in collecting said deficiency.</div>
                            <div class="agreement_header">6. Acceleration upon Breach</div>
                            <div class="agreement_content">In the occurrence that the DEBTOR fails to make any payments in accordance to the Payment Plan, upon reaching fifteen (15) days after the failure to make such prescribed payment, the full amount of the deficiency shall come immediately due and payable.</div>
                            <div class="agreement_header">7. Assignment</div>
                            <div class="agreement_content">The CREDITOR may assign this Agreement with written notice to the DEBTOR. In the event of such assignment, the assignee may designate a new method of payment. </div>
                            <div class="agreement_header">8. No Modification Unless in Writing</div>
                            <div class="agreement_content">No modification of this Agreement shall be valid unless in writing and agreed upon by both Parties. </div>
                            <div class="agreement_header">9. Severability</div>
                            <div class="agreement_content">In the event that any provision in this Agreement is held to be invalid, illegal, or unenforceable for any reason, the Parties agree that such provision shall be deemed to be struck and the remaining parts of the Agreement shall be enforced as if the struck provision were never included in the Agreement.</div>
                            <div class="agreement_header">10. Applicable Law</div>
                            <div class="agreement_content">This Agreement and the interpretation of its terms shall be governed by and construed in accordance with the laws of the State of {stateJurisdiction} and subject to the exclusive jurisdiction of the Federal and State Courts located in {countryJurisdiction}, {stateJurisdiction}.</div> -->
                        </div>
                    </div>
                </div>
                <!-- <div class="statement_letter_label_wrapper">
                    <div class="checkbox" style="text-align: center;">
                        <label class="i-checks statement_letter_label_tag disable_agree_label" style="">
                            <input type="checkbox" name="all_radio" id="" class="disable_agree_input agreement_checkbox"  value="0"><i></i>I Agree
                        </label>
                    </div>
                </div> -->
                
            </div>
            
            <div class="modal-footer" style="padding-left: 30px;padding-right: 30px; text-align: center">
                <!-- <button type="button" class="btn btn-default skip_serial_asset_no pull-left"> Skip </button>
                <button type="button" class="btn btn-success save_serial_asset_no" disabled> Save Changes </button> -->
                <!-- <button type="button" class="btn btn-danger" data-dismiss="modal"> No</button>
                <button type="button" class="btn btn-success create_reconciliation" data-dismiss="modal" style="margin-left: 50px"> Yes</button> -->
                <button type="button" class="btn btn-success submit_payment" disabled>Submit</button>
                <button type="button" class="btn btn-primary reset_payment" style="border-radius: 0px">Reset</button>
                <button type="button" class="btn btn-danger">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<input type="hidden" id="hospice_id_value" value="<?php echo $hospice_id; ?>">
<script type="text/javascript">
    $(document).ready(function(){
        var is_recon_details = $('.is_recon_details').val();
        console.log('is_recon_details',is_recon_details);
        var recon_date = $('.recon_details_date').val();
        console.log('recon_date',recon_date);

        if(is_recon_details == 1) {
            $('.recon_date').html(recon_date);
        }

        //FOR DISABLING THE AGREEMENT BUTTON IF NOT IN BOTTOM MOST PART
        // $('#payment_agreement').bind('scroll',chk_scroll);


    });

    function chk_scroll(e)
    {
        // console.log(e);
        var elem = $(e.currentTarget);
        var top = elem[0].scrollHeight - elem.scrollTop();
        var outer = elem.outerHeight() + 5;
        var outer_range = elem.outerHeight() - 5;
        if ((top >= outer_range) && (top <= outer)) 
        {
            console.log("bottom");
            $(".disable_agree_label").css("cursor","pointer");
            $(".disable_agree_input").prop("disabled", false);
        } else {
            $(".disable_agree_label").css("cursor","not-allowed");
            $(".disable_agree_input").prop("disabled", true);
        }
    }

    var globalTimeout = null;
    $('.payment_other').bind('keyup',function(){
        var _this = $(this);
        
        if(globalTimeout != null) clearTimeout(globalTimeout);
            globalTimeout =setTimeout(getInfoFunc,500);

            function getInfoFunc(){
                globalTimeout = null;

                $('.amount_payment_field').val(_this.val());
            }
        
    });

    // PREVIOUS VERSION OF PAYMENT TYPE
    // $('body').on('click','.payment_type',function(){
    //     var _this = $(this);
        
    //     if(_this.val() == 0) {
    //         // $('.amount_payment_field').prop("disabled", true);
    //         $('.amount_payment_field').val($('.payment_paymentDue').val());
    //         $('.payment_other').prop("disabled", true);
    //         $('.payment_other').val("")
    //     }
    //     if(_this.val() == 1) {
    //         // $('.amount_payment_field').prop("disabled", true);
    //         $('.amount_payment_field').val($('.payment_totalBalanceDue').val());
    //         $('.payment_other').prop("disabled", true);
    //         $('.payment_other').val("")
    //     }
    //     if(_this.val() == 2) {
    //         // $('.amount_payment_field').prop("disabled", false);
    //         $('.amount_payment_field').val($('.payment_other').val());
    //         $('.payment_other').prop("disabled", false);
    //     }
    // });

    // NEW VERSION OF PAYMENT TYPE ========================== START
    $('body').on('click','#other_payment_type',function(){
        var _this = $(this);
        
        $('.payment_type_checkbox').each(function(){
            $(this).prop("checked", false);
        });
        $('.amount_payment_field').val($('.payment_other').val());
        $('.payment_other').prop("disabled", false);

    });

    $('body').on('click','.payment_type_checkbox',function(){
        var _this = $(this);
        
        $('#other_payment_type').prop("checked", false);
    });

    // NEW VERSION OF PAYMENT TYPE ========================== END

    //agreement_checkbox
    $('body').on('click','.agreement_checkbox',function(){
        var _this = $(this);
        
        if(_this.is(':checked')) {
            $('.submit_payment').prop("disabled", false);
        } else {
            $('.submit_payment').prop("disabled", true);
        }
    });

    $('body').on('click','.submit_payment',function(){
        var _this = $(this);
        var payment_date = $(".date_payment").val();
        var email = $(".email_payment").val();
        var selected_inv = "";
        var counter = 0;
        var past_due_invoices = $('.past_due_invoices');
        var payment_due_invoices = $('.payment_due_invoices');
        var selected_payment_type = 0;
        var is_other = false;
        var payment_amount = 0;
        var hospiceID = $('#hospice_id_value').val();

        // $('.payment_type').each(function(){
        //     if($(this).is(':checked')) {
        //         selected_payment_type = $(this).val();
        //         return 0;
        //     }
        // });
        
        if(email != "") {
            email = email.replace("@",".-arobase-.");
        }

        if($('#other_payment_type').is(':checked')) {
            is_other = true;
            payment_amount = $('.payment_other').val();
        }

        if(is_other) {
            console.log('payment_other:',$('.payment_other').val());
            $.get(base_url+'billing_statement/make_other_payment_v2/'+hospiceID+'/'+payment_amount+'/'+payment_date+'/'+email, function(response){
                var obj = $.parseJSON(response);            // $('.close').click();
                
                setTimeout(function(){
                    // me_message_v2({error:0,message:obj['message']});
                    if(obj['error'] == 0)
                    {
                        // me_message_v2({error:0,message:obj['message']});
                        jAlert(obj['message'],"Reminder", function(response) {
                            if(response)
                            {
                                setTimeout(function(){
                                    location.reload();
                                },1500);
                            }
                        });
                        
                    } else {
                        me_message_v2({error:1,message:"Error!"});
                    }

                    $('.date_payment').val("");
                    $('.email_payment').val("");
                    $('#total_balance_due_val').val("");
                    $('.amount_payment_field').val("");
                    // $('.agreement_checkbox').prop("checked", false);
                    $('.submit_payment').prop("disabled", true);
                    $('.payment_type').each(function() {
                        if($(this).val() == 0) {
                            $(this).prop("checked", true);
                            return 0;
                        }
                    });
                },1);
            }); 
        } else {
            var counter_type = 0;
            $('.payment_type_checkbox').each(function(){
                if($(this).is(':checked')) {
                    switch(counter_type) {
                        case 0: 
                            selected_inv += $('#pay_balance_due').val();
                            counter++;
                            break;
                        case 1:
                            var selected_invoices = past_due_invoices;
                            selected_invoices.each(function(){
                                if(counter == 0) {
                                    selected_inv = $(this).val();
                                } else {
                                    selected_inv = selected_inv + "-" + $(this).val();
                                }
                                counter++;
                            });
                            break;
                        default: ""
                    }
                }
                counter_type++;
            });
            
            console.log(selected_inv);
            $.get(base_url+'billing_statement/make_payment/'+selected_inv+'/'+payment_date+'/'+email, function(response){
                var obj = $.parseJSON(response);            // $('.close').click();
                
                setTimeout(function(){
                    // me_message_v2({error:0,message:obj['message']});
                    if(obj['error'] == 0)
                    {
                        // me_message_v2({error:0,message:obj['message']});
                        jAlert(obj['message'],"Reminder", function(response) {
                            if(response)
                            {
                                setTimeout(function(){
                                    location.reload();
                                },1500);
                            }
                        });
                        
                    } else {
                        me_message_v2({error:1,message:"Error!"});
                    }

                    $('.date_payment').val("");
                    $('.email_payment').val("");
                    $('#total_balance_due_val').val("");
                    $('.amount_payment_field').val("");
                    // $('.agreement_checkbox').prop("checked", false);
                    $('.submit_payment').prop("disabled", true);
                    $('.payment_type').each(function() {
                        if($(this).val() == 0) {
                            $(this).prop("checked", true);
                            return 0;
                        }
                    });
                },1);
            }); 
        }
    });

    $('body').on('click','.reset_payment',function(){
        var _this = $(this);
        
        $('.date_payment').val("");
        $('.email_payment').val("");
    });

</script>