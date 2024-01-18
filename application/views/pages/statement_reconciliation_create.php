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
</style>
<div class="modal-body" id="create-manual-reconciliation-modal">
    <div class="statement_letter_label_wrapper">
        <label class="statement_letter_label_tag">Account Name: <?php if(empty($reconcile_details)) {?><span class="text-danger-dker">*</span><?php } ?></label>
        <span>
            <!-- <input type="text" name="recon_account_name" class="form-control disabled_tag recon_account_name" id="" placeholder="" style="margin-left:0px" > -->
            <?php 
            $hospices = get_hospices_v2($this->session->userdata('user_location'));
            $companies = get_companies_v2($this->session->userdata('user_location'));
            if(empty($reconcile_details)) {
            ?>
            <select name="hospice_sorting_id" class="form-control m-b select2-ready-modal recon_account_id">
                <option value="select">- Select Account -</option>
                <?php
                    

                    if (!empty($hospices)) {
                ?>
                        <!-- <optgroup label="Hospices"> -->
                    <?php
                        foreach ($hospices as $hospice) :
                            if ($hospice['hospiceID'] != 13) {
                    ?>
                                <option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospice_selected == $hospice['hospiceID']) { echo 'selected'; } ?> >
                                    <?php echo $hospice['hospice_account_number']; ?> - <?php echo $hospice['hospice_name']; ?>
                                </option>
                    <?php
                            }
                        endforeach;
                    ?>
                        <!-- </optgroup> -->
                <?php
                    }
                    if (!empty($companies)) {
                ?>
                        <!-- <optgroup label="Commercial Account"> -->
                <?php
                        foreach ($companies as $company):
                            if ($company['hospiceID'] != 13) {
                ?>
                                <option value="<?php echo $company['hospiceID']; ?>" <?php if ($hospice_selected == $company['hospiceID']) { echo 'selected'; } ?> >
                                    <?php echo $hospice['hospice_account_number']; ?> -  <?php echo $company['hospice_name']; ?>
                                </option>
                <?php
                            }
                        endforeach;
                ?>
                        <!-- </optgroup> -->

                        <option disabled="disabled">----------------------------------------</option>
                <?php
                    }
                    foreach ($hospices as $hospice) :
                        if ($hospice['hospiceID'] == 13) {
                ?>
                            <option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospice_selected == $hospice['hospiceID']) { echo 'selected'; } ?> >
                                <?php echo $hospice['hospice_account_number']; ?> - <?php echo $hospice['hospice_name']; ?>
                            </option>
            <?php
                        }
                    endforeach;
            ?>
            </select>
        <?php } else { ?>
            <input type="text" name="recon_invoice_date" class="form-control disabled_tag" id="" placeholder="" style="margin-left:0px" value="<?php echo $reconcile_details[0]['hospice_name']; ?>" disabled>
        <?php }?>
        </span>
    </div>
    <div class="statement_letter_label_wrapper">
        <label class="statement_letter_label_tag">Invoice Date:</label>
        <span>
            <?php if(empty($reconcile_details)) { ?> 
            <input type="text" name="recon_invoice_date" class="form-control datepicker disabled_tag recon_invoice_date" id="" placeholder="" style="margin-left:0px" >
            <?php } else {
                $recon_invoice_date = "";
                if ($reconcile_details[0]['invoice_date'] !== "" && $reconcile_details[0]['invoice_date'] !== "0000-00-00") {
                    $recon_invoice_date = date("m/d/Y", strtotime($reconcile_details[0]['invoice_date']));
                }
            ?>
            <input type="text" name="recon_invoice_date" class="form-control disabled_tag" id="" placeholder="" style="margin-left:0px" value="<?php echo $recon_invoice_date; ?>" disabled>
            <?php } ?>
        </span>
    </div>
    <div class="statement_letter_label_wrapper">
        <label class="statement_letter_label_tag">Invoice Number:</label>
        <span>
            <?php if(empty($reconcile_details)) { ?>
            <input type="text" name="recon_invoice_number" class="form-control disabled_tag recon_invoice_number" id="" placeholder="" style="margin-left:0px" value="" >
            <?php } else {?>
            <input type="text" name="recon_invoice_number" class="form-control disabled_tag" id="" placeholder="" style="margin-left:0px" value="<?php echo substr($reconcile_details[0]['invoice_no'],3, 10); ?>" disabled>
            <?php } ?>
        </span>
    </div>
    <div class="statement_letter_label_wrapper" style="display: none;">
        <label class="statement_letter_label_tag">Balance Due:</label>
        <span>
            <?php if(empty($reconcile_details)) { ?>
            <input type="text" name="recon_balance_due" class="form-control disabled_tag recon_balance_due" id="" placeholder="" style="margin-left:0px" value="" >
            <?php } else {?>
            <input type="text" name="recon_balance_due" class="form-control disabled_tag" id="" placeholder="" style="margin-left:0px" value="<?php if($reconcile_details[0]['balance_due'] != 0 && $reconcile_details[0]['balance_due'] != null) { echo number_format((float)$reconcile_details[0]['balance_due'], 2, '.', ''); } ?>" disabled>
            <?php } ?>
        </span>
    </div>
    <div class="statement_letter_label_wrapper" style="display: none;">
        <label class="statement_letter_label_tag">Payment Amount:</label>
        <span>
            <?php if(empty($reconcile_details)) { ?>
            <input type="text" name="recon_payment_amount" class="form-control recon_payment_amount" id="" placeholder="" style="margin-left:0px" value="">
            <?php } else {?>
            <input type="text" name="recon_payment_amount" class="form-control disabled_tag" id="" placeholder="" style="margin-left:0px" value="<?php if($reconcile_details[0]['payment_amount'] != 0 && $reconcile_details[0]['payment_amount'] != null) { echo number_format((float)$reconcile_details[0]['payment_amount'], 2, '.', ''); } ?>" disabled>
            <?php } ?>
        </span>
    </div>
    <div class="statement_letter_label_wrapper">
        <label class="statement_letter_label_tag">Owe:</label>
        <span>
            <?php if(empty($reconcile_details)) { ?>
            <input type="text" name="recon_amount_owe" class="form-control recon_amount_owe" id="" placeholder="" style="margin-left:0px" value="">
            <?php } else {?>
            <input type="text" name="recon_amount_owe" class="form-control disabled_tag" id="" placeholder="" style="margin-left:0px" value="<?php if($reconcile_details[0]['balance_owe'] != 0 && $reconcile_details[0]['balance_owe'] != null) { echo number_format((float)$reconcile_details[0]['balance_owe'], 2, '.', ''); } ?>" disabled>
            <?php } ?>
        </span>
    </div>
    <div class="statement_letter_label_wrapper">
        <label class="statement_letter_label_tag">Credit:</label>
        <span>
            <?php if(empty($reconcile_details)) { ?>
            <input type="text" name="recon_credit" class="form-control recon_credit" id="" placeholder="" style="margin-left:0px" value="">
            <?php } else {?>
            <input type="text" name="recon_credit" class="form-control disabled_tag" id="" placeholder="" style="margin-left:0px" value="<?php if($reconcile_details[0]['credit'] != 0 && $reconcile_details[0]['credit'] != null) { echo number_format((float)$reconcile_details[0]['credit'], 2, '.', ''); }  ?>" disabled>
            <?php } ?>
        </span>
    </div>
    <div class="statement_letter_label_wrapper">
        <label class="statement_letter_label_tag">Note: <span style="font-size: 12px; font-style: italic; color: #00000078;"> Restricted "#"$%&'()*+-/:;<=>?@[\]^_`{|}~" </span></label>
        <span>
            <?php if(empty($reconcile_details)) { ?>
            <textarea id="notes" name="recon_notes" class="form-control recon_notes" onkeypress="return (event.charCode == 8 || event.charCode == 13) ? null : ( event.charCode >= 48 && event.charCode <= 57 ) || event.charCode === 46 || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32) || (event.charCode == 33) || (event.charCode == 44) || (event.charCode == 63)" onpaste="return false" style="width: 100%; height: 51px; padding: 10px; resize: none; border-color: #cfdadd;" placeholder="Enter note..."></textarea>
            <?php } else {?>
            <textarea id="notes" name="recon_notes" class="form-control disabled_tag" style="width: 100%; height: 51px; padding: 10px; resize: none; border-color: #cfdadd;" disabled><?php echo $reconcile_details[0]['notes']; ?></textarea>
            <?php } ?>
        </span>
    </div>
</div>

<div class="modal-footer" style="padding-left: 30px;padding-right: 30px;">
    <!-- <button type="button" class="btn btn-default skip_serial_asset_no pull-left"> Skip </button>
    <button type="button" class="btn btn-success save_serial_asset_no" disabled> Save Changes </button> -->
    <?php if(empty($reconcile_details)) { ?>
    <!-- <button type="button" class="btn btn-danger" data-dismiss="modal"> No</button> -->
    <button type="button" class="btn btn-success create_reconciliation" style="margin-left: 50px"> Submit</button>
    <?php } ?>
</div>
<input type="hidden" name="" class="is_recon_details" value="<?php if(empty($reconcile_details)) { echo "0"; } else { echo "1"; } ?>">
<input type="hidden" name="" class="recon_details_date" value="<?php if(empty($reconcile_details)) { echo ""; } else { echo date("m/d/Y", strtotime($reconcile_details[0]['date_created'])); } ?>">

<script type="text/javascript">
    //create_reconciliation
    $('#loader_modal').modal({
        backdrop: 'static',
        keyboard: false
    });
    $('#loader_modal').modal('hide');

    $(document).ready(function(){
        var is_recon_details = $('.is_recon_details').val();
        console.log('is_recon_details',is_recon_details);
        var recon_date = $('.recon_details_date').val();
        console.log('recon_date',recon_date);

        if(is_recon_details == 1) {
            $('.recon_date').html(recon_date);
        }
    });
    $('body').on('click','.create_reconciliation',function(){
        var _this = $(this);
        var invoice_id = $('.reconciliation_invoice_id').val();
        var recon_account_id = $('.recon_account_id').val();
        var recon_credit = 0;

        if($('.recon_credit').val() != null && $('.recon_credit').val() != "") {
            recon_credit = $('.recon_credit').val();
        }
        var recon_amount_owe = 0;
        if($('.recon_amount_owe').val() != null && $('.recon_amount_owe').val() != "") {
            recon_amount_owe = $('.recon_amount_owe').val();
        }
        var recon_invoice_date = "empty";
        if($('.recon_invoice_date').val() != null && $('.recon_invoice_date').val() != "") {
            recon_invoice_date = $('.recon_invoice_date').val();
            var token = "/";
            var newToken = "-";
            var newoutput = recon_invoice_date.split(token).join(newToken);
            if (recon_invoice_date.split(token).length > 0) {
                recon_invoice_date = recon_invoice_date.split(token).join(newToken);
            }
        }
        var recon_invoice_number = 0;
        if($('.recon_invoice_number').val() != null && $('.recon_invoice_number').val() != "") {
            recon_invoice_number = $('.recon_invoice_number').val();
        }
        var recon_payment_amount = 0;
        if($('.recon_payment_amount').val() != null && $('.recon_payment_amount').val() != "") {
            recon_payment_amount = $('.recon_payment_amount').val();
        }
        var recon_notes = "";
        if($('.recon_notes').val() != null && $('.recon_notes').val() != "") {
            recon_notes = $('.recon_notes').val();
        }
        var recon_balance_due = 0;
        if($('.recon_balance_due').val() != null && $('.recon_balance_due').val() != "") {
            recon_balance_due = $('.recon_balance_due').val();
        }
        var error_message = "";
        var is_error = 0;
        if(recon_account_id == null || recon_account_id == "" || recon_account_id == "select") {
            error_message += "Account Name is required.</br>";
            is_error = 1;
        } 

        if(recon_credit == null || recon_credit == "") {
            if(recon_amount_owe == null || recon_amount_owe == "") {
                error_message += "Credit is required.</br>";
                is_error = 1;
            }
            
        } 

        if(recon_amount_owe == null || recon_amount_owe == "") {
            if(recon_credit == null || recon_credit == "") {
                error_message += "Owe is required.</br>";
                is_error = 1;
            }
            
        } 

        if(is_error) {
            me_message_v2({error:1,message:error_message});
        } else {
        recon_credit = parseFloat(recon_credit);
        recon_amount_owe = parseFloat(recon_amount_owe);

        $(this).attr('disabled', true);
        $('#loader_modal').modal('show');
        $('#globalModal').modal('hide');
        if (typeof invoice_id == 'undefined') {
            invoice_id = 0;
        }
        $.get(base_url+'billing_reconciliation/insert_reconciliation/'+invoice_id+'/'+recon_credit+'/'+recon_amount_owe+'/'+recon_account_id+'/'+recon_invoice_date+'/'+recon_invoice_number+'/'+recon_payment_amount+'/'+recon_notes+'/'+recon_balance_due, function(response){
            console.log('response',response);
                var obj = $.parseJSON(response);            // $('.close').click();
                
                $(this).removeAttr('disabled');
                $('#loader_modal').modal('hide');
                setTimeout(function(){
                    if(obj['error'] == 0)
                    {
                        me_message_v2({error:0,message:obj['message']});
                        // setTimeout(function(){
                        //     location.reload();
                        // },2000);
                    } else {
                        // me_message_v2({error:1,message:"Error!"});
                        jAlert(obj['message'],"Reminder", function(response) {
                            if(response)
                            {
                                setTimeout(function(){
                                    location.reload();
                                },1500);
                            }
                        });
                    }
                },1);
                
            }); 
        }
        
    });
</script>