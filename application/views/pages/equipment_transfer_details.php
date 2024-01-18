<style type="text/css">
    .modal
    {
        /*left: -455px;*/
        top: -71px;
    }

    @media (min-width: 768px) 
    {
        .modal-dialog{
            /*width: 250px;*/
        }

    }
    #globalModal .modal-content
    {
        width:755px;
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

    .tooltiptext {
        visibility: hidden;
        width: 120px;
        bottom: 100%;
        left: 50%;
        margin-left: -60px;
        background-color: rgba(43, 40, 40, 0.81);
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        border:1px solid #f1ecec;

        /* Position the tooltip */
        position: absolute;
        z-index: 10000000000;
    }

    .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: rgba(43, 40, 40, 0.81) transparent transparent transparent;
    }

    .all_items_order_req
    {
        margin-top:10px;
    }

    .order_requisition_details_row
    {
        margin-right: 0px !important;
        margin-left: 0px !important;
        font-size: 15px !important;
    }

    .order_requisition_details_row_v2
    {
        margin-right: 0px !important;
        margin-left: 0px !important;
        font-size: 15px !important;
        text-align: center;
    }

    .order_requisition_item_details
    {
        border:1px solid rgba(8, 8, 8, 0.62);
        height:70px;
        padding-left: 2px;
        padding-right: 2px;
    }

    .order_requisition_item_details_v2
    {
        border:1px solid rgba(8, 8, 8, 0.62);
        min-height:40px;
        padding-left: 2px;
        padding-right: 2px;
    }

    .order_requisition_form_textarea_col1
    {
        text-align:center;
        height: 47px;
        margin-top: -2px;
        margin-left: -2px;
        width: 76px;
        border: 0px;
        background-color: #fff;
        resize: none !important;
        box-shadow:inset 0 0 0px rgba(88, 102, 110, 0.13);
    }
    .order_requisition_form_textarea_col1:focus{
       outline:none !important;
       border:0px !important;
    }

    .order_requisition_form_textarea_col2
    {
        text-align:center;
        height: 47px;
        margin-top: -2px;
        margin-left: -2px;
        width: 153px;
        border: 0px;
        background-color: #fff;
        resize: none !important;
        box-shadow:inset 0 0 0px rgba(88, 102, 110, 0.13);
    }
    .order_requisition_form_textarea_col2:focus{
       outline:none !important;
       border:0px !important;
    }

    .order_requisition_form_textarea_col4
    {
        text-align:center;
        height: 47px;
        margin-top: -2px;
        margin-left: -2px;
        width: 313px;
        border: 0px;
        background-color: #fff;
        resize: none !important;
        box-shadow:inset 0 0 0px rgba(88, 102, 110, 0.13);
    }
    .order_requisition_form_textarea_col4:focus{
       outline:none !important;
       border:0px !important;
    }

    .person_taking_order_input{
        background-color: #fff !important;
        width: 200px;
        border-right: 0px;
        border-left: 0px;
        border-top: 0px;
        border-bottom: 1px solid #000;
        box-shadow: none !important;
        outline-style:none !important;
    }

    .person_taking_order_input:focus{
       outline:none !important;
       border:3px !important;
    }

    .confirmation_no_input{
        background-color: #fff !important;
        width: 222px;
        border-right: 0px;
        border-left: 0px;
        border-top: 0px;
        border-bottom: 1px solid #000;
        box-shadow: none !important;
    }

    .confirmation_no_input:focus{
       outline:none !important;
       border:3px !important;
    }

    .vendor_rep_no_input{
        background-color: #fff !important;
        width: 222px;
        border-right: 0px;
        border-left: 0px;
        border-top: 0px;
        border-bottom: 1px solid #000;
        box-shadow: none !important;
    }

    .vendor_rep_no_input:focus{
       outline:none !important;
       border:3px !important;
    }

    .purchase_order_requisition_row
    {
        margin-left: 0px!important;
    }

    .inactive_item
    {
        background-color: #b9b9b929;
    }

    .item_description_col
    {
        width:140px;
    }

    .item_description_col_header {
        width:140px;
    }
    .serial_no_col 
    {
        width:100px;
    }
    .asset_no_col 
    {
        width: 60px;
    }

    @media print{
        @page {
            margin-bottom: 0;
            margin-top: 0;
            /*margin: 0;*/
            /*margin-left:10px;*/
        }

        .inactive_item
        {
            -webkit-print-color-adjust:exact;
            background: #b9b9b929 !important;
        }

        .modal-content
        {
            border:0px !important;
        }

        .order_requisition_item_details_v2
        {
            /*height:36px !important;*/
        }

        .order_requisition_item_details
        {
            height:44px !important;
            padding-top: 3px !important;
        }

        .order_requisition_div
        {
            width:770px !important;
            padding:0px !important;
            margin-left: 10px !important;
            /*margin-right: -14px !important;*/
        }
        .modal-header
        {
            display:none !important;
        }
        .order_req_first_header
        {
            font-size: 16px !important;
        }
        .order_req_second_header
        {
            font-size: 14px !important;
        }
        .order_requisition_details_row
        {
            font-size: 12px !important;
            padding-left:0px !important;
            padding-right:0px !important;
        }
        .order_requisition_details_row_v2
        {
            font-size: 12px !important;
            padding-left:0px !important;
            padding-right:0px !important;
            /*height:44px !important;*/
        }

        .company_item_no_col
        {
            /*width:72px !important;*/

            padding-left:0px !important;
            padding-right:0px !important;
        }
        .item_description_col
        {
            width:140px !important;
            /*height: 120% !important;*/
        }
        .reorder_no_col
        {
            width:80px !important;
        }  

        .serial_no_col 
        {
            width:100px !important;
        }

        .asset_no_col 
        {
            width: 60px !important;
        }

        .unit_of_measure_col
        {
            /*width:60px !important;*/
            padding-left:0px !important;
            padding-right:0px !important;
        }
        .item_cost_col
        {
            /*width:60px !important;*/
            width:60px !important;
            padding-left:0px !important;
            padding-right:0px !important;
        }
        .par_level_col
        {
            width:60px !important;
        }
        .qty_ordered_col
        {
            /*width:60px !important;*/
            padding-left:0px !important;
            padding-right:0px !important;
        }
        .total_cost_col
        {
            /*width:70px !important;*/
            /*margin-left: 0px !important;*/
            /*margin-right: 0px !important;*/
            width:60px !important;
            padding-left:0px !important;
            padding-right:0px !important;
        }
        .item_list_header_one_line
        {
            padding-top: 12px !important;
        }

        #printbreak {
            page-break-before: always;
        }

        #printf {
            display: block !important;
        }

    }

</style>

<div class="row print-frame-div">
    <div class="form-group order_requisition_div">

        <h4 class="order_req_first_header" style="text-align:center;"><strong> Advantage Home Medical Services </strong></h4>
        <p class="order_req_second_header" style="text-align:center;font-size:16px;margin-top:-10px;font-weight:bold;"> Equipment Transfer Requisition </p>

        <div class="row order_requisition_details_row" style="margin-top:30px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Date:</strong> <?php echo date('m/d/Y', strtotime($transfer_req_details['equip_transfer_date'])); ?>
            </div>
            <div class="col-xs-6 col-xs-6 col-sm-6 col-md-6" style="">
                <?php
                    $location = get_login_location($this->session->userdata('user_location'));
                ?>
                <strong>Location:</strong> <span> <?php echo $location['user_city'].', '.$location['user_state']; ?> </span>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:6px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Receiving Location: </strong> 
                <?php
                    $location_receiving = get_login_location($transfer_req_details['receiving_location']);
                ?>
                <span class="vendor_account_no"> <?php echo $location_receiving['user_city'].', '.$location_receiving['user_state']; ?> </span>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Transfer PO No.:</strong> <span> <?php echo substr($transfer_req_details['transfer_po_no'], 3, 10); ?> </span>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:6px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Representative Created Order: </strong> <span> <?php echo $transfer_req_details['person_placing_order']; ?> </span>
            </div>
        </div>

        <div id="printbreak" style="position: relative !important;">
            <div class="row order_requisition_details_row_v2 etr_header" style="margin-top:15px !important;margin-right:9px !important;margin-left:10px !important;">
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details company_item_no_col" style="border-right:0px !important;padding-top:10px;">
                    <p> Company</p>
                    <p style="margin-top:-10px;"> Item No. </p>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details item_description_col_header" style="border-right:0px !important;padding-top:20px;">
                    Item Description
                </div>
                  <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details vendor" style="border-right:0px !important;padding-top:20px;">
                    Vendor
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details reorder_no_col" style="border-right:0px !important;padding-top:20px;">
                    Re Order No.
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details unit_of_measure_col" style="border-right:0px !important;padding-top:10px;">
                        <p> Unit of</p>
                    <p style="margin-top:-10px;">Measure</p>
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details qty_ordered_col" style="border-right:0px !important;padding-top:10px;">
                    <p> Qty.</p>
                    <p style="margin-top:-10px;"> Ordered </p>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details serial_no_col" style="border-right:0px !important;padding-top:20px;">
                    Serial No.
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details asset_no_col" style="border-right:0px !important;padding-top:20px;">
                    Asset No.
                </div>  
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details item_cost_col" style="border-right:0px !important;padding-top:20px;">
                    <p> Eqpt.</p>
                    <p style="margin-top:-10px;"> Cost </p>
                </div>                              
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details total_cost_col" style="padding-top:20px;">
                    Total </br> Cost
                </div>
            </div>

            <?php
                if (!empty($transfer_req_receiving_details)) {
                    $i = 1;
                    $ordered_count = 0;
                    foreach ($transfer_req_receiving_details as $key => $value) {
                        ++$ordered_count; ?>
                            <div class="row transfer_req_row order_requisition_details_row_v2 order_requisition_item_box_<?php echo $i; ?> <?php echo $inactive_class; ?>" style="margin-right:9px !important;margin-left:10px !important;" >
                                <div class="text_area_wrapper_height_<?php  echo $i; ?> company_item_no_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 company_item_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
                                    <span><?php echo $value['transfer_req_items'][0]['company_item_no']; ?></span>
                                </div>
                                <div class="text_area_wrapper_height_<?php  echo $i; ?> item_description_wrapper_<?php echo $i; ?> col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 item_description_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
                                    <!-- <span><?php echo $value['transfer_req_items'][0]['item_description']; ?></span> -->
                                    <div data-row-id="<?php  echo $i; ?>" class="row_table_<?php  echo $i; ?>  form-control choose_item_description_order_req order_req_<?php  echo $i; ?> order_req_item_description_<?php  echo $i; ?>" id="" style="padding: 0px !important; cursor: pointer; height:96% !important;width: 100% !important; border: none !important; -webkit-box-shadow: none !important; box-shadow: none !important;">
                                            <textarea style="overflow: hidden; cursor: pointer !important; width: 100% !important; height: 100% !important" class="row_table order_requisition_form_textarea_col2 order_item_description_<?php echo $i; ?>" readonly><?php echo $value['transfer_req_items'][0]['item_description']; ?></textarea>
                                        </div>
                                </div>
                                <div class="text_area_wrapper_height_<?php  echo $i; ?> item_vendor_no_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 vendor_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
                                        <!-- <span><?php echo $value['transfer_req_items'][0]['vendor_name']; ?></span> -->
                                        <div data-row-id="<?php  echo $i; ?>" class="row_table_<?php  echo $i; ?>  form-control choose_vendor_name_order_req order_req_<?php  echo $i; ?> order_req_vendor_name_<?php  echo $i; ?>" id="" style="padding: 0px !important; cursor: pointer; height:96% !important;width: 100% !important; border: none !important; -webkit-box-shadow: none !important; box-shadow: none !important;">
                                            <textarea style="overflow: hidden; cursor: pointer !important; width: 100% !important; height: 100% !important" class="row_table order_requisition_form_textarea_col2 order_vendor_name_<?php echo $i; ?>" readonly><?php echo $value['transfer_req_items'][0]['vendor_name']; ?></textarea>
                                        </div>
                                </div>
                                <div class="text_area_wrapper_height_<?php  echo $i; ?> item_reorder_no_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 reorder_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
                                        <!-- <div class="vendors_item_no_<?php  echo $i; ?>"></div> -->
                                        <span><?php echo $value['transfer_req_items'][0]['item_reorder_no']; ?></span>
                                </div>
                                <div class="text_area_wrapper_height_<?php  echo $i; ?> item_unit_measurement_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 unit_of_measure_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
                                        <span><?php echo $value['item_unit_measure']; ?></span>
                                </div>
                                <div class="text_area_wrapper_height_<?php  echo $i; ?> quantity_ordered_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 qty_ordered_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
                                        <span><?php echo $value['req_item_quantity_ordered']; ?></span>
                                </div>
                                <div class="text_area_wrapper_height_<?php  echo $i; ?> item_serial_no_wrapper_<?php echo $i; ?> col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 serial_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
                                        <div data-row-id="<?php  echo $i; ?>" class="row_table_<?php  echo $i; ?>  form-control choose_serial_no_order_req order_req_<?php  echo $i; ?> order_req_serial_no_<?php  echo $i; ?>" id="" style="padding: 0px !important; cursor: pointer; height:96% !important;width: 100% !important; border: none !important; -webkit-box-shadow: none !important; box-shadow: none !important;">
                                            <textarea style="overflow: hidden; cursor: pointer !important; width: 100% !important; height: 100% !important" class="row_table order_requisition_form_textarea_col2 order_req_serial_number_<?php echo $i; ?> serial_no_col" readonly><?php echo $value['serial_nos']; ?></textarea>
                                        </div>
                                </div>
                                <div class="text_area_wrapper_height_<?php  echo $i; ?> item_asset_no_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 asset_no_col" style="height:48px;border-top:0px !important;border-right:0px !important; padding-top:2px;">
                                        <div data-row-id="<?php  echo $i; ?>" class="row_table_<?php  echo $i; ?>  form-control choose_asset_no_order_req order_req_<?php  echo $i; ?> order_req_asset_no_<?php  echo $i; ?>" id="" style="padding: 0px !important; cursor: pointer; height:96% !important;width: 100% !important; border: none !important; -webkit-box-shadow: none !important; box-shadow: none !important;">
                                            <textarea style="overflow: hidden; cursor: pointer !important; width: 100% !important; height: 100% !important" class="row_table order_requisition_form_textarea_col2 order_req_asset_number_<?php echo $i; ?> asset_no_col" readonly><?php echo $value['asset_nos']; ?></textarea>
                                        </div>
                                </div>
                                    
                                <div class="text_area_wrapper_height_<?php  echo $i; ?> equipment_cost_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 equipment_cost_col item_cost_col" style="height:48px;border-top:0px !important; border-right:0px !important; padding-top:2px;">
                                        <span><?php echo number_format($value['item_cost'], 2); ?></span>
                                </div>
                                <div class="text_area_wrapper_height_<?php  echo $i; ?> total_cost_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 total_cost_col" style="height:48px;border-top:0px !important;padding-top:2px;">
                                    <span><?php echo number_format($value['item_total_cost'], 2); ?></span>
                                </div>
                            </div>

            <?php
                    ++$i;
                    }

                    for ($j = 0; $j < (14 - $ordered_count); ++$j) {
                        ?>
                        
            <?php
                    }
                }
            ?>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:5px !important;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="height:22px;">
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 " style="text-align:right;padding:0;">
                <div class="col-xs-9 col-sm-9 col-md-9">
                    <strong>Total:</strong>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                    <?php echo number_format($transfer_req_details['equip_req_grand_total'], 2); ?>
                </div>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:15px !important;margin-bottom:10px !important;">
            <div class="col-sm-12 col-md-6" style="">
                <strong>Receiving Date:</strong> &nbsp; 
                <span> 
                    <?php
                    if ($transfer_req_details['transfer_received_date'] != '0000-00-00') {
                        echo $transfer_req_details['transfer_received_date'];
                    }
                    ?>
                </span>
            </div>
            </br>
            <div class="col-sm-12 col-md-6" style="">
                <strong>Representative Receiving PO Req:</strong> &nbsp; 
                <span>
                    <?php
                    if (!empty($transfer_req_receiving_details)) {
                        foreach ($transfer_req_receiving_details as $key => $value) {
                            if ($value['req_staff_member_receiving'] != '') {
                                echo $value['req_staff_member_receiving'];
                            }
                        }
                    }
                    ?>
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-12 hidden-print">
        <hr />
        <button class="btn btn-default print-iframe" style="margin-right:10px;" ><i class="fa fa-print"></i> Print</button>
        <button type="button" class="btn btn-danger pull-right" onclick="closeModalbox()">Close</button>
    </div>
    <iframe id="printf" name="printf" src="<?php echo base_url(); ?>inventory/equipment_transfer_requisition_details/<?php echo $purchase_order_no; ?>/<?php echo $order_req_id; ?>" style="visibility: hidden !important; height: 0px !important"></iframe>


</div>

<script type="text/javascript">
    // console.log("thank you!");
    $(document).ready(function(){
        var row_id = 1;
        // console.log("gwapo");
        $.each($("body").find(".transfer_req_row"), function(){
            var _this = $(this);

            // setTimeout(function() {
                // console.log(row_id);
                // var serial_scrollHeight = parseInt(_this.find('serial_no_col').find('row_table').prop('scrollHeight'),10);
                // var asset_scrollHeight = parseInt($_this.find('asset_no_col').find('row_table').prop('scrollHeight'),10)
                // order_item_description_
                // var vendor_scrollHeight = parseInt($('body').find('.order_item_description_'+row_id).prop('scrollHeight'),10);
                var serial_scrollHeight = parseInt($('body').find('.order_req_serial_number_'+row_id).prop('scrollHeight'),10);
                var asset_scrollHeight = parseInt($('body').find('.order_req_asset_number_'+row_id).prop('scrollHeight'),10);
                var item_description_scrollHeight = parseInt($('body').find('.order_item_description_'+row_id).prop('scrollHeight'),10);
                var vendor_name_scrollHeight = parseInt($('body').find('.order_vendor_name_'+row_id).prop('scrollHeight'),10);
                console.log('vendor_name_scrollHeight: ', vendor_name_scrollHeight);
                if((serial_scrollHeight >= asset_scrollHeight) && (serial_scrollHeight >= item_description_scrollHeight) && (serial_scrollHeight >= vendor_name_scrollHeight)) {
                    $('.text_area_wrapper_height_'+row_id).height(serial_scrollHeight-2);
                    // $('body').find('.row_table_'+row_id).height(serial_scrollHeight-5);
                } else if ((asset_scrollHeight >= serial_scrollHeight) && (asset_scrollHeight >= item_description_scrollHeight) && (asset_scrollHeight >= vendor_name_scrollHeight)) {
                    $('.text_area_wrapper_height_'+row_id).height(asset_scrollHeight-2);
                    // $('body').find('.row_table_'+row_id).height(asset_scrollHeight-5);
                } else if ((item_description_scrollHeight >= serial_scrollHeight) && (item_description_scrollHeight >= asset_scrollHeight) && (item_description_scrollHeight >= vendor_name_scrollHeight)){
                    $('.text_area_wrapper_height_'+row_id).height(item_description_scrollHeight+10);
                    // $('body').find('.row_table_'+row_id).height(item_description_scrollHeight-5);
                } else if ((vendor_name_scrollHeight >= serial_scrollHeight) && (vendor_name_scrollHeight >= asset_scrollHeight) && (vendor_name_scrollHeight >= item_description_scrollHeight)){
                    $('.text_area_wrapper_height_'+row_id).height(vendor_name_scrollHeight+10);
                    // $('body').find('.row_table_'+row_id).height(item_description_scrollHeight-5);
                } else {

                }
                            
            // }, 1);
            row_id++;
        });
        
        var frm = document.getElementById('printf').contentWindow;
        $('.print-frame-div').on('click','.print-iframe',function(){
            // frm.focus(); // focus on contentWindow is needed on some ie versions
            // frm.print();
            window.print();
        });

    });

</script>
