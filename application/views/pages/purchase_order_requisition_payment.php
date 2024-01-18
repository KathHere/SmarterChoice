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

    .disabled_edit_input
    {
        background-color: #fefefe !important;
    }


</style>

<?php
if(!empty($order_req_payment_details)):
    echo form_open("",array("class"=>"order_requisition_form_payment"));
?>

<div class="row">
    <div class="">
        <input type="hidden" name="vendor_id" value="<?php echo $order_req_payment_details['vendor_id']; ?>">
        <div class="form-group clearfix" style="margin-bottom:0px !important;">
            <div class="col-sm-4" style="">
                <label>Date <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text" class="form-control order_req_date_payment" name="order_req_payment_date" value="<?php echo date('m/d/Y'); ?>">
                </div>
            </div>
            <div class="col-sm-8" style="">
                <label>Vendor <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control disabled_edit_input" name="vendor_name" value="<?php echo $order_req_payment_details['vendor_name']; ?>" readonly>
                </div>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-bottom:0px !important;">
            <div class="col-sm-4" style="">
                <label>PO No. <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control disabled_edit_input" name="po_number" value="<?php echo $order_req_payment_details['purchase_order_no']; ?>" readonly>
                </div>
            </div>
            <div class="col-sm-4" style="">
                <label>Confimration No.<span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control disabled_edit_input" name="confirmation_no" value="<?php echo $order_req_payment_details['order_req_confirmation_no']; ?>" readonly>
                </div>
            </div>
            <div class="col-sm-4" style="">
                <label>Terms <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control disabled_edit_input" name="terms" value="<?php echo $order_req_payment_details['vendor_credit_terms']; ?>" readonly>
                </div>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-bottom:0px !important;">
            <div class="col-sm-4" style="">
                <label>Credit <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text" id="vendor_credit" class="form-control vendor_credit" name="vendor_credit" data-vendor-credit="<?php echo $order_req_payment_details['vendor_credit']; ?>" value="<?php echo number_format($order_req_payment_details['vendor_credit'],2); ?>" style="background-color: rgba(238, 238, 238, 0.09) !important;" readonly>
                    <input type="hidden" name="old_vendor_credit" value="<?php echo $order_req_payment_details['vendor_credit']; ?>">
                </div>
            </div>
            <div class="col-sm-4" style="">
                <label>Credit Used <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control credit_used" name="credit_used" value="<?php echo number_format($order_req_payment_details['credit_used'],2); ?>">
                </div>
            </div>
            <div class="col-sm-4" style="">
                <label>Method <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <select name="method" class="form-control">
                        <option value=""> [--Choose Method--] </option>
                        <option value="check"> Check </option>
                        <option value="credit_card"> Credit Card </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-bottom:25px !important;">
            <div class="col-sm-4" style="">
                <label>Check No. <span style="color:red;">*</span> </label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control" name="check_no" value="<?php echo $order_req_payment_details['check_no']; ?>">
                </div>
            </div>
            <div class="col-sm-4" style="">
                <label>Amount Paid <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control amount_paid" name="amount_paid" value="<?php echo number_format($order_req_payment_details['payment_amount'],2); ?>">
                </div>
            </div>
            <input type="hidden" class="ending_balance_input" value="<?php echo $order_req_payment_details['ending_balance']; ?>">
            <div class="col-sm-4" style="">
                <label>Ending Balance <span style="color:red;">*</span></label>
                <div class="clearfix"></div>
                <div class="form-group" style="">
                    <input type="text"  class="form-control ending_balance" name="ending_balance" value="<?php echo number_format($order_req_payment_details['ending_balance'],2); ?>">
                </div>
            </div>
        </div>
        <hr />
        <div class="col-md-12" style="padding-right:17px;margin-bottom:5px;">
            <div class="pull-right">
                <button type="button" class="btn btn-danger pull-right close_order_req_payment" onclick="closeModalbox()">Close</button>
                <button type="button" class="btn btn-success pull-right confirm_order_req_payment" data-order-req-id="<?php echo $order_req_payment_details['order_req_id']; ?>" data-purchase-order-no="<?php echo $order_req_payment_details['purchase_order_no']; ?>" data-req-payment-batch-no="<?php echo $order_req_payment_details['req_payment_batch_no']; ?>" style="margin-right:10px;"> Submit </button>
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

        $('.order_req_date_payment').datepicker({
            dateFormat: 'mm/dd/yy'
        });

        $('body').on('click','.confirm_order_req_payment',function(){
            var _this = $(this);
            var form_data = $('.order_requisition_form_payment').serialize();
            var purchase_order_no = $(this).attr('data-purchase-order-no');
            var order_req_id = $(this).attr('data-order-req-id');
            var req_payment_batch_no = $(this).data('req-payment-batch-no');

            $.post(base_url+"inventory/confirm_order_req_payment/"+ purchase_order_no + "/"+ order_req_id +"/"+ req_payment_batch_no +"/",form_data, function(response){
                var obj = $.parseJSON(response);
                me_message_v2({error:obj['error'],message:obj['message']});
                if(obj['error'] == 0)
                {
                    setTimeout(function(){
                        location.reload();
                    },1500);
                }
            });
        });

        $('.credit_used').bind('keyup',function(){
            var _this = $(this);
            var _value = _this.val();
            var old_credit = $("body").find(".vendor_credit").attr('data-vendor-credit');
            var new_credit = 0;

            new_credit = Number(old_credit)-Number(_value);
            $("body").find("#vendor_credit").val(new_credit.toFixed(2));
        });

        $('.amount_paid').bind('keyup',function(){
            var _this = $(this);
            var _value = _this.val();
            var ending_balance = $(".ending_balance_input").val();
            var new_ending_balance = 0;

            setTimeout(function(){
                if(_value != "" && _value != 0)
                {
                    new_ending_balance = ending_balance-_value;
                    $(".ending_balance").val(new_ending_balance.toFixed(2));
                }
            },800);

        });

    });

</script>


