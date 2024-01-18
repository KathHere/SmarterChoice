<style type="text/css">

    .item_unit_of_measure_form_group_header
    {
        margin-left: 15px !important;
        margin-right: 15px !important;
        margin-top: 20px !important;
        font-weight:bold;
    }

    .item_unit_of_measure_form_group_content
    {
        margin-left: 15px !important;
        margin-right: 15px !important;
        margin-top: -15px !important;
    }

    .item_unit_of_measure
    {
        border:1px solid rgba(8, 8, 8, 0.62);
        height:40px;
        text-align: center !important;
    }

    .item_unit_of_measure_input
    {
        background-color: #fff !important;
        border: 0px !important;
        box-shadow: none !important;
        width: 100% !important;
        text-align: center !important;
    }

</style>

<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">Add New Item</h1>
</div>

<div class="wrapper-md" ng-controller="FormDemoCtrl">
    <div class="row">
        <div class="col-sm-12">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="form-group clearfix" style="margin-bottom:0px !important;">
                        <div class="col-sm-12 col-md-12 text-right">
                            <!-- <a href="<?php echo base_url() ?>item_grouping/item_groups"> <i class="fa fa-object-group"></i> Item Groups</a> -->
                        </div>
                    </div>
                </div>
                <div class="panel-body" style="overflow-x:auto;">
                    <form role="form" action="<?php echo base_url('inventory/save_new_item'); ?>" method="post" id="new_item_form_validate" novalidate>
                        <div class="form-group clearfix">
                            <div class="col-sm-3">
                                <label> Company Item No. <span class="text-danger-dker">*</span></label>
                                <input type="text" name="company_item_no" class="form-control company_item_no">
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-sm-6">
                                <label> Item Description <span class="text-danger-dker">*</span></label>
                                <input type="text" name="item_description" class="form-control new_item_input item_description">
                            </div>
                            <div class="col-sm-6 vendor_div">
                                <label> Vendor <span class="text-danger-dker">*</span></label>
                                <select name="item_vendor" class="form-control choose_item_vendor select2-ready">
                                    <option value=""> [--Choose Vendor--] </option>
                                    <?php
                                        if (!empty($vendor_list)) {
                                            foreach ($vendor_list as $key => $value) {
                                                ?>
                                                <option value="<?php echo $value['vendor_id']; ?>" data-vendor-acct-no="<?php echo $value['vendor_acct_no']; ?>" > <?php echo $value['vendor_name']; ?> </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                    <option value="" id="new_vendor_option" data-id="new_vendor"> Add New Vendor </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-sm-3">
                                <label> Account No. <span class="text-danger-dker">*</span></label>
                                <input type="text" name="item_vendor_acct_no" class="form-control item_vendor_acct_no">
                            </div>
                            <div class="col-sm-3">
                                <label> Reorder No. <span class="text-danger-dker">*</span></label>
                                <input type="text" name="item_reorder_no" class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <label> Warehouse Location <span class="text-danger-dker">*</span></label>
                                <input type="text" name="item_warehouse_location" class="form-control item_warehouse_location">
                            </div>
                            <div class="col-sm-3">
                                <label> Category <span class="text-danger-dker">*</span></label>
                                <select name="item_category" class="form-control item_category">
                                    <option value="" > [--Choose Category--] </option>
                                    <?php
                                        if ($item_category_list) {
                                            foreach ($item_category_list as $key => $value) {
                                                ?>
                                                <option value="<?php echo $value['item_category_id']; ?>" > <?php echo $value['item_category_name']; ?> </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-sm-3">
                                <label> Par Level <span class="text-danger-dker">*</span></label>
                                <input type="number" name="item_par_level" class="form-control grey_inner_shadow item_par_level">
                            </div>
                            <!-- <div class="col-sm-3">
                                <label> Item Group </label>
                                <select name="item_group_id" class="form-control item_group">
                                    <option value="" > [--Choose Item Group--] </option>
                                    <?php
                                        if ($item_group_list) {
                                            foreach ($item_group_list as $key => $value) {
                                                ?>
                                                <option value="<?php echo $value['item_group_id']; ?>" > <?php echo $value['item_group_name']; ?> </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div> -->
                            <div class="col-sm-4">
                                <div class="col-sm-6">
                                    <label> Add to Hospice Item List <span class="text-danger-dker">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" class="add_to_hospice_item_list add_to_hospice_item_list_yes" name="add_to_hospice_item_list" value="1"  /><i></i> Yes &nbsp &nbsp &nbsp
                                        </label>
                                        <label class="i-checks">
                                            <input type="radio" class="add_to_hospice_item_list add_to_hospice_item_list_no" name="add_to_hospice_item_list" value="0" /><i></i> No
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <label> Disposables </label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" class="disposable_item_list disposable_item_list_yes" name="disposable_item_list" value="3"  /><i></i> Yes &nbsp &nbsp &nbsp
                                        </label>
                                        <label class="i-checks">
                                            <input type="radio" class="disposable_item_list disposable_item_list_no" name="disposable_item_list" value="2" /><i></i> No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="min-width: 600px !important;">
                            <div class="form-group clearfix item_unit_of_measure_form_group_header">
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-right:0px !important;padding-top:8px;">
                                    Unit of Measure
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-right:0px !important;padding-top:8px;">
                                    Value
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-right:0px !important;padding-top:8px;">
                                    Vendor Cost
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="padding-top:8px;">
                                    Company Cost
                                </div>
                            </div>
                            <div class="form-group clearfix item_unit_of_measure_form_group_content">
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    Box
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_unit_value_box" class="item_unit_of_measure_input value_box">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_vendor_cost_box" class="item_unit_of_measure_input">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
                                    <input type="text" name="item_company_cost_box" class="item_unit_of_measure_input company_cost_box">
                                </div>
                            </div>
                            <div class="form-group clearfix item_unit_of_measure_form_group_content">
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    Each (EA)
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_unit_value_each" class="item_unit_of_measure_input value_each">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_vendor_cost_each" class="item_unit_of_measure_input">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
                                    <input type="text" name="item_company_cost_each" class="item_unit_of_measure_input company_cost_each">
                                </div>
                            </div>
                            <div class="form-group clearfix item_unit_of_measure_form_group_content">
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    Case
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_unit_value_case" class="item_unit_of_measure_input value_case">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_vendor_cost_case" class="item_unit_of_measure_input">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
                                    <input type="text" name="item_company_cost_case" class="item_unit_of_measure_input company_cost_case">
                                </div>
                            </div>
                            <div class="form-group clearfix item_unit_of_measure_form_group_content">
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    Pair (PR)
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_unit_value_pair" class="item_unit_of_measure_input value_pair">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_vendor_cost_pair" class="item_unit_of_measure_input">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
                                    <input type="text" name="item_company_cost_pair" class="item_unit_of_measure_input company_cost_pair">
                                </div>
                            </div>
                            <div class="form-group clearfix item_unit_of_measure_form_group_content">
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    Pack (PK)
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_unit_value_pack" class="item_unit_of_measure_input value_pack">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_vendor_cost_pack" class="item_unit_of_measure_input">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
                                    <input type="text" name="item_company_cost_pack" class="item_unit_of_measure_input company_cost_pack">
                                </div>
                            </div>
                            <div class="form-group clearfix item_unit_of_measure_form_group_content">
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    Package (PKG)
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_unit_value_package" class="item_unit_of_measure_input value_package">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_vendor_cost_package" class="item_unit_of_measure_input">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
                                    <input type="text" name="item_company_cost_package" class="item_unit_of_measure_input company_cost_package">
                                </div>
                            </div>
                            <div class="form-group clearfix item_unit_of_measure_form_group_content">
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    Roll (RL)
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_unit_value_roll" class="item_unit_of_measure_input value_roll">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
                                    <input type="text" name="item_vendor_cost_roll" class="item_unit_of_measure_input">
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
                                    <input type="text" name="item_company_cost_roll" class="item_unit_of_measure_input company_cost_roll">
                                </div>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-success pull-right btn-save-new-item" style="margin-top:30px !important;">Save Information</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add New Vendor -->
<div class="modal fade modal_new_vendor" id="modal_new_vendor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
            <div class="modal-header">
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Add New Vendor Form</h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
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
                            <input type="text" name="vendor_credit_limit" class="form-control new_vendor_input">
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
                            <input type="text" name="vendor_city" id="new_vendor_city_1" class="form-control new_vendor_input">
                        </div>
                        <div class="col-sm-6">
                            <label> Email Address <span class="text-danger-dker">*</span></label>
                            <input type="email" name="vendor_email_address" class="form-control new_vendor_input" style="text-transform:none !important">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-3">
                            <label> State/Province <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_state" id="new_vendor_state_1" class="form-control new_vendor_input" >
                        </div>
                        <div class="col-sm-3">
                            <label> Postal Code <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_postal_code" id="new_vendor_postal_code_1" class="form-control">
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-modal-new-vendor-close">Cancel</button>
                <button type="button" class="btn btn-primary btn-save-new-vendor-information">Save Information</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){

        $('.input_tobe_masked').mask("(999) 999-9999");

        $('.add_new_vendor_date').datepicker({
            dateFormat: 'mm/dd/yy'
        });

        $('.btn-save-new-item').click(function(e){
            var _this_save_btn = $(this);

            jConfirm('<br />Save New Item Information?', 'Reminder', function(response){
                if(response)
                {
                    //disable submit button until the order is process
                    $(_this_save_btn).prop('disabled',true);

                    $("#new_item_form_validate").ajaxSubmit({
                        beforeSend:function()
                        {
                            me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Saving New Item Information ..."});
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
                                        window.location.reload();
                                    },2000);
                                }
                            }
                            catch (err)
                            {
                                me_message_v2({error:1,message:"Failed to save new item information."});
                            }
                            $(_this_save_btn).prop('disabled',false);
                        }
                    });
                }
            });
        });

        $('.vendor_div').on('change','.choose_item_vendor',function(){
            var _value =  $('option:selected', this).attr('data-id');
            var vendor_acct_no =  $('option:selected', this).attr('data-vendor-acct-no');

            if(_value == "new_vendor")
            {
                $('#modal_new_vendor').modal('show');
                $("body").find(".item_vendor_acct_no").val("");
            }
            else
            {
                if(vendor_acct_no != undefined)
                {
                    $("body").find(".item_vendor_acct_no").val(vendor_acct_no);

                    var company_item_no = $(".company_item_no").val();
                    var vendor_id = $('option:selected', this).val();

                    // if(company_item_no.length > 0)
                    // {
                    //     $.post(base_url+"inventory/get_item_details/"+company_item_no+"/"+vendor_id,"", function(response){
                    //         var obj = $.parseJSON(response);

                    //         $(".item_description").val(obj.item_details.item_description);
                    //         $(".item_warehouse_location").val(obj.item_details.item_warehouse_location);
                    //         $(".item_category").val(obj.item_details.item_category).change();
                    //         $(".item_par_level").val(obj.item_details.item_par_level);
                    //         if(obj.item_details.add_to_hospice_item_list == 1)
                    //         {
                    //             $(".add_to_hospice_item_list_yes").prop('checked','true');
                    //         }
                    //         else if(obj.item_details.add_to_hospice_item_list == 0)
                    //         {
                    //             $(".add_to_hospice_item_list_no").prop('checked','true');
                    //         }
                    //         else
                    //         {
                    //             $(".add_to_hospice_item_list_yes").removeAttr('checked');
                    //             $(".add_to_hospice_item_list_no").removeAttr('checked');
                    //         }

                    //         // if(obj.item_unit_of_measures.length > 0)
                    //         // {
                    //         //     for(var val in obj.item_unit_of_measures)
                    //         //     {
                    //         //         var value_name = ".value_"+obj.item_unit_of_measures[val].item_unit_measure;
                    //         //         var company_cost_name = ".company_cost_"+obj.item_unit_of_measures[val].item_unit_measure;

                    //         //         var final_company_cost = Number(obj.item_unit_of_measures[val].item_company_cost);
                    //         //         $(value_name).val(obj.item_unit_of_measures[val].item_unit_value);
                    //         //         $(company_cost_name).val(final_company_cost.toFixed(2));
                    //         //     }
                    //         // }
                    //         // else
                    //         // {
                    //         //     var unit_measure = ['box','each','case','pair','pack','package','roll'];
                    //         //     for(var val in unit_measure)
                    //         //     {
                    //         //         var value_name = ".value_"+unit_measure[val];
                    //         //         var company_cost_name = ".company_cost_"+unit_measure[val];

                    //         //         $(value_name).val("");
                    //         //         $(company_cost_name).val("");
                    //         //     }
                    //         // }
                    //     });
                    // }
                }
                else
                {
                    $("body").find(".item_vendor_acct_no").val("");
                }
            }
        });

        // Autofill the item information
        var globalTimeout = null;
        $('.company_item_no').bind('keyup',function(){
            var _this = $(this);
            var company_item_no = $(this).val();
            var vendor_id = $("body").find(".choose_item_vendor").val();

            if(globalTimeout != null) clearTimeout(globalTimeout);
            globalTimeout =setTimeout(getInfoFunc,1100);

            function getInfoFunc(){
                globalTimeout = null;

                if(company_item_no.length > 0)
                {
                    $.post(base_url+"inventory/get_item_details/"+company_item_no+"/"+"","", function(response){
                        var obj = $.parseJSON(response);

                        if(obj.item_details.item_description != undefined)
                        {
                            $(".item_description").val(obj.item_details.item_description);
                            $(".item_warehouse_location").val(obj.item_details.item_warehouse_location);
                            $(".item_category").val(obj.item_details.item_category).change();
                            $(".item_par_level").val(obj.item_details.item_par_level);
                            $("body").find(".item_vendor_acct_no").val(obj.item_details.item_vendor_acct_no);
                            if(obj.item_details.add_to_hospice_item_list == 1)
                            {
                                $(".add_to_hospice_item_list_yes").prop('checked','true');
                            }
                            else if(obj.item_details.add_to_hospice_item_list == 0)
                            {
                                $(".add_to_hospice_item_list_no").prop('checked','true');
                            }
                            else
                            {
                                $(".add_to_hospice_item_list_yes").removeAttr('checked');
                                $(".add_to_hospice_item_list_no").removeAttr('checked');
                            }
                        }

                        var unit_measure = ['box','each','case','pair','pack','package','roll'];
                        for(var val in unit_measure)
                        {
                            var value_name = ".value_"+unit_measure[val];
                            var company_cost_name = ".company_cost_"+unit_measure[val];

                            $(value_name).val("");
                            $(company_cost_name).val("");
                        }

                        if(obj.item_unit_of_measures.length > 0)
                        {
                            for(var val in obj.item_unit_of_measures)
                            {
                                var value_name = ".value_"+obj.item_unit_of_measures[val].item_unit_measure;
                                var company_cost_name = ".company_cost_"+obj.item_unit_of_measures[val].item_unit_measure;
                                var final_company_cost = Number(obj.item_unit_of_measures[val].item_company_cost);

                                $(value_name).val(obj.item_unit_of_measures[val].item_unit_value);
                                $(company_cost_name).val(final_company_cost.toFixed(2));
                            }
                        }
                    });
                }
            }
        });

        $('.btn-modal-new-vendor-close').click(function(e){
            $('#modal_new_vendor').modal('hide');
        });

        $('.btn-save-new-vendor-information').click(function(e){
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
                                        $('body').find("#new_vendor_option").remove();
                                        $(".choose_item_vendor").append("<option value='"+obj['vendor_id']+"' selected>"+obj['vendor_name']+"</option>");
                                        $(".choose_item_vendor").append("<option id='new_vendor_option' data-id='new_vendor'> Add New Vendor </option>");
                                        $("body").find(".item_vendor_acct_no").val(obj['vendor_acct_no']);
                                        $('#modal_new_vendor').modal('hide');
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
        $('#new_vendor_postal_code_1').bind('keyup', function(){
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
                                $('#new_vendor_state_1').val(state);
                                // get city name
                                city = result[0]['address_components'][1]['long_name'];
                                // Insert city name into some input box
                                $('#new_vendor_city_1').val(city);
                            }
                        }
                    }
                });
            }

            if($this.val() == "")
            {
                $('#new_vendor_state_1').val("");
                $('#new_vendor_city_1').val("");
            }
        });
    });

</script>











