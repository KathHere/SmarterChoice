<style type="text/css">

</style>

<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">Add Vendor</h1>
</div>

<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">

        <div class="panel panel-default">
            <div class="panel-heading" style="height:34px;" >
            </div>
            <div class="panel-body">
                <form role="form" action="<?php echo base_url('inventory/save_new_vendor'); ?>" method="post" id="new_vendor_form_validate" novalidate>
                    <div class="form-group clearfix">
                        <div class="col-sm-3">
                            <label> Entry Date <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_entry_date" class="form-control add_new_vendor_date">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-6">
                            <label> Vendor Name <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_name" class="form-control new_vendor_input">
                        </div>
                        <div class="col-sm-3">
                            <label> Account No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_acct_no" class="form-control new_vendor_input">
                        </div>
                        <div class="col-sm-3">
                            <label> Credit <span class="text-danger-dker">*</span></label>
                            <input type="number" name="vendor_credit" class="form-control new_vendor_input grey_inner_shadow">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-3">
                            <label> Credit Terms <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_credit_terms" class="form-control new_vendor_input">
                        </div>
                        <div class="col-sm-3">
                            <label> Credit Limit <span class="text-danger-dker">*</span></label>
                            <input type="number" name="vendor_credit_limit" class="form-control new_vendor_input">
                        </div>
                        <div class="col-sm-3">
                            <label> Phone No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_phone_no" class="form-control input_tobe_masked">
                        </div>
                        <div class="col-sm-3">
                            <label> Fax No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_fax_no" class="form-control input_tobe_masked">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-3">
                            <label> Street </label>
                            <input type="text" name="vendor_street" class="form-control new_vendor_input">
                        </div>
                        <div class="col-sm-3">
                            <label> City <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_city" id="new_vendor_city" class="form-control new_vendor_input">
                        </div>
                        <div class="col-sm-6">
                            <label> Email Address <span class="text-danger-dker">*</span></label>
                            <input type="email" name="vendor_email_address" class="form-control new_vendor_input" style="text-transform:none !important">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-3">
                            <label> State/Province <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_state" id="new_vendor_state" class="form-control new_vendor_input">
                        </div>
                        <div class="col-sm-3">
                            <label> Postal Code <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_postal_code" id="new_vendor_postal_code" class="form-control">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-6">
                            <label> Sales Rep. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_sales_rep" class="form-control new_vendor_input">
                        </div>
                        <div class="col-sm-3">
                            <label> Office No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_office_no" class="form-control new_vendor_input input_tobe_masked">
                        </div>
                        <div class="col-sm-3">
                            <label> Cell No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_cell_no" class="form-control input_tobe_masked">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-6">
                            <label> Email Address <span class="text-danger-dker">*</span></label>
                            <input type="email" name="vendor_sales_rep_email_address" class="form-control new_vendor_input" style="text-transform:none !important">
                        </div>
                        <div class="col-sm-3">
                            <label> Shipping Cost <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_shipping_cost" class="form-control new_vendor_input">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-success pull-right btn-save-new-vendor" style="margin-top:30px !important;">Save Information</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> 
</div>

<script type="text/javascript"> 

    $(document).ready(function(){

        $('.input_tobe_masked').mask("(999) 999-9999");

        // $("input[type=text]").blur(function(){
        //     $(this).val($(this).val().css("text-transform","none !important"));
        // });

        $('.add_new_vendor_date').datepicker({
            dateFormat: 'mm/dd/yy'
        });

        $('.btn-save-new-vendor').click(function(e){
            var _this_save_btn = $(this);

            jConfirm('<br />Save New Vendor Information?', 'Reminder', function(response){
                if(response)
                {
                    //disable submit button until the order is process
                    $(_this_save_btn).prop('disabled',true);

                    $("#new_vendor_form_validate").ajaxSubmit({
                        beforeSend:function()
                        {
                            me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Saving New Vendor Information ..."});
                        },
                        success:function(response)
                        {
                            $('#error-modal .alert').removeClass('alert-danger');
                            $('#error-modal .alert').removeClass('alert-info');
                            $('#error-modal .alert').removeClass('alert-success');
                            
                            try
                            {
                                var obj = $.parseJSON(response);
                                me_message_v2(obj);
                                if(obj['error']==0)
                                {
                                    setTimeout(function(){
                                        window.location.href = base_url + "inventory/vendor_details/"+ obj['vendor_id'];
                                    },2000);
                                }
                            }
                            catch (err)
                            {
                                me_message_v2({error:1,message:"Failed to save new vendor information."});
                            }
                            $(_this_save_btn).prop('disabled',false);
                        }
                    });
                }
            });
        });
        
        //Auto Detect City/State base on zip code for Create New Vendor
        var geocoder = new google.maps.Geocoder();
        $('#new_vendor_postal_code').bind('keyup', function(){
            var $this = $(this);
            if ($this.val().length == 5) 
            {
                geocoder.geocode({ 'address': $this.val() }, function (result, status) {
                    var state = "N/A";
                    var city = "N/A";
                    //start loop to get state from zip
                    for (var component in result[0]['address_components']) {
                        for (var i in result[0]['address_components'][component]['types']) {
                            if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1") 
                            {
                                //alert(result[0]['address_components'][1]['long_name']);
                                state = result[0]['address_components'][component]['short_name'];
                                // do stuff with the state here!
                                $('#new_vendor_state').val(state);
                                // get city name
                                city = result[0]['address_components'][1]['long_name'];
                                // Insert city name into some input box
                                $('#new_vendor_city').val(city);
                            }
                        }
                    }
                });
            }

            if($this.val() == "")
            {
                $('#new_vendor_state').val("");
                $('#new_vendor_city').val("");
            }
        }); 
    });

</script>










                        
                        