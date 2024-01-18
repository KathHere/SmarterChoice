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

    @media print{
        @page {
            margin-bottom: 0;
            margin-top: 15px;
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
            height:36px !important;
        }

        .order_requisition_item_details
        {
            height:44px !important;
            padding-top: 3px !important;
        }

        .order_requisition_div
        {
            width:720px !important;
            padding:0px !important;
            margin-left: -14px !important;
            margin-right: -14px !important;
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
        }
        .company_item_no_col
        {
            width:72px !important;
        }
        .item_description_col
        {
            width:232px !important;
        }
        .reorder_no_col
        {
            width:85px !important;
        }
        .unit_of_measure_col
        {
            width:60px !important;
        }
        .item_cost_col
        {
            width:60px !important;
        }
        .par_level_col
        {
            width:60px !important;
        }
        .qty_ordered_col
        {
            width:60px !important;
        }
        .total_cost_col
        {
            width:70px !important;
        }
        .item_list_header_one_line
        {
            padding-top: 12px !important;
        }

        /* #printbreak {
            page-break-before: always;
        } */

        #printf {
            display: block !important;
        }

        .print-frame-div {
          margin-left: 30px !important;
          page-break-after: always;
        }
        
        .special_div {
          margin-top: 2px !important;
        }
    }
    
    /* @media print {
        
    }

    @media screen {
        #printbreak {
            page-break-before: always;
        }
    } */
    

</style>

<?php
  $row_start = 0;
  $row_counter = 0;
  $order_req_items_length = count($order_req_items);
  $outer_loop_count = $order_req_items_length / 14;
  for ($index_outer = 0; $index_outer <= $outer_loop_count; ++$index_outer) {
      if ($index_outer == 0) {
          ?>
<div class="row print-frame-div">
      <?php
      } else {
          ?>
<div class="row print-frame-div special_div">        
      <?php
      } ?>
    <div class="form-group order_requisition_div">
        
        <h4 class="order_req_first_header" style="text-align:center;"><strong> Advantage Home Medical Services</strong></h4>
        <p class="order_req_second_header" style="text-align:center;font-size:16px;margin-top:-10px;font-weight:bold;"> Purchase Order Requisition</p>

        <div class="row order_requisition_details_row" style="margin-top:30px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Date:</strong> <?php echo date('m/d/Y', strtotime($order_req_details['order_req_date'])); ?>
            </div>
            <div class="col-xs-6 col-xs-6 col-sm-6 col-md-6" style="">
                <?php
                    $location = get_login_location($this->session->userdata('user_location')); ?>
                <strong>Location:</strong> <span> <?php echo $location['user_city'].', '.$location['user_state']; ?> </span>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:6px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Vendor:</strong> <span> <?php echo $order_req_details['vendor_name']; ?> </span>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Vendor Phone:</strong> <span class="vendor_phone_no"> <?php echo $order_req_details['vendor_phone_no']; ?> </span>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:6px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Account No. </strong> <span class="vendor_account_no"> <?php echo $order_req_details['vendor_acct_no']; ?> </span>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Purchase Order No.:</strong> <span> <?php echo substr($order_req_details['purchase_order_no'], 3, 10); ?> </span>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:6px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Vendor Rep. Taking Order:</strong> <span> <?php echo $order_req_details['vendor_rep_taking_order']; ?> </span>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Confirmation No.:</strong> <span> <?php echo $order_req_details['order_req_confirmation_no']; ?> </span>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:6px;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="">
                <strong>Person Placing Order:</strong> <span> <?php echo $order_req_details['person_placing_order']; ?> </span>
            </div>
        </div>

        <div id="printbreak" style="position: relative !important;">
            <div class="row order_requisition_details_row_v2" style="margin-top:15px !important;margin-right:9px !important;margin-left:10px !important;">
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details company_item_no_col" style="border-right:0px !important;padding-top:10px;width:110px;">
                    <p> Company</p>
                    <p style="margin-top:-10px;"> Item No. </p>
                </div>
                <div class="col-xs-4 col-sm-4 col-md-4 order_requisition_item_details item_description_col item_list_header_one_line" style="border-right:0px !important;padding-top:20px;width:300px;">
                    Item Description
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details reorder_no_col item_list_header_one_line" style="border-right:0px !important;padding-top:20px;width:140px;">
                    Re Order No.
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details unit_of_measure_col" style="border-right:0px !important;padding-top:10px;width:85px;">
                    Unit of Measure
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details item_cost_col item_list_header_one_line" style="border-right:0px !important;padding-top:20px;width:85px;">
                    Cost
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details par_level_col" style="border-right:0px !important;padding-top:10px;width:85px;">
                    <p> Par</p>
                    <p style="margin-top:-10px;"> Level </p>
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details qty_ordered_col" style="border-right:0px !important;padding-top:10px;width:85px;">
                    <p> Qty.</p>
                    <p style="margin-top:-10px;"> Ordered </p>
                </div>
                <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details total_cost_col item_list_header_one_line" style="padding-top:20px;width:135px;">
                    Total Cost
                </div>
            </div>

            <?php

      if (!empty($order_req_items)) {
          $ordered_count = 0;

          // foreach ($order_req_items as $key => $value) {
          for ($index = $row_start; $index < $order_req_items_length; ++$index) {
              ++$ordered_count;
              $cancelled_item_css = '';
              $cancelled_class = '';
              $cancelled_span = '';
              $inactive_class = '';
              if ($order_req_items[$index]['item_status'] == 1) {
                  $cancelled_item_css = 'text-decoration:line-through;';
                  $cancelled_class = 'tooltip_class';
                  $cancelled_span = '<span class="tooltiptext"> Cancelled </span>';
              }
              if ($order_req_items[$index]['item_active_sign'] == 0) {
                  $inactive_class = 'inactive_item';
              } ?>
                            <div class="row order_requisition_details_row_v2 order_requisition_item_box_<?php echo $i; ?> <?php echo $inactive_class; ?>" style="margin-right:9px !important;margin-left:10px !important;" >
                                <div class="col-xs-2 col-sm-1 col-md-1 order_requisition_item_details_v2 company_item_no_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:110px;">
                                    <span class="<?php echo $cancelled_class; ?>"> <?php echo $order_req_items[$index]['company_item_no']; ?> </span>  <?php echo $cancelled_span; ?>
                                </div>
                                <div class="col-xs-3 col-sm-4 col-md-4 order_requisition_item_details_v2 item_description_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:300px;">
                                    <span class="<?php echo $cancelled_class; ?>"> <?php echo $order_req_items[$index]['item_description']; ?> </span>  <?php echo $cancelled_span; ?>
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 reorder_no_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:140px;">
                                    <span class="<?php echo $cancelled_class; ?>"> <?php echo $order_req_items[$index]['item_reorder_no']; ?> </span>  <?php echo $cancelled_span; ?>
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 unit_of_measure_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:85px;">
                                    <span class="<?php echo $cancelled_class; ?>"> <?php echo $order_req_items[$index]['item_unit_measure']; ?> </span>  <?php echo $cancelled_span; ?>
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 par_level_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:85px;">
                                    <span class="<?php echo $cancelled_class; ?>"> <?php echo $order_req_items[$index]['item_cost']; ?> </span>  <?php echo $cancelled_span; ?>
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 item_cost_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:85px;">
                                    <span class="<?php echo $cancelled_class; ?>"> <?php echo $order_req_items[$index]['item_par_level']; ?> </span>  <?php echo $cancelled_span; ?>
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 qty_ordered_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:85px;">
                                    <span class="<?php echo $cancelled_class; ?>"> <?php echo $order_req_items[$index]['req_item_quantity_ordered']; ?> </span>  <?php echo $cancelled_span; ?>
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 total_cost_col" style="<?php echo $cancelled_item_css; ?>height:48px;border-top:0px !important;padding-top:2px;width:135px;">
                                    <span class="<?php echo $cancelled_class; ?>"> <?php echo number_format($order_req_items[$index]['item_total_cost'], 2); ?> </span>  <?php echo $cancelled_span; ?>
                                </div>
                            </div>
            <?php

              if ($row_counter == 13) {
                  ++$row_counter;
                  $row_start = $row_counter;

                  break;
              }
              ++$row_counter;
          }
          for ($j = 0; $j < (14 - $ordered_count); ++$j) {
              ?>
                        <div class="row order_requisition_details_row_v2" style="margin-right:9px !important;margin-left:10px !important;" >
                            <div class="col-xs-2 col-sm-1 col-md-1 order_requisition_item_details_v2 company_item_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:110px;">
                            </div>
                            <div class="col-xs-3 col-sm-4 col-md-4 order_requisition_item_details_v2 item_description_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:300px;">
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 reorder_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:140px;">
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 unit_of_measure_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:85px;">
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 item_cost_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:85px;">
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 par_level_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:85px;">
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 qty_ordered_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:85px;">
                            </div>
                            <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 total_cost_col" style="height:48px;border-top:0px !important;padding-top:2px;width:135px;">
                            </div>
                        </div>
            <?php
          }
      } ?>
        </div>

        <div class="row order_requisition_details_row" style="margin-top:15px !important;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="height:22px;">
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 " style="text-align:right;padding:0;">
                <div class="col-xs-9 col-sm-9 col-md-9">
                    <strong>Amount:</strong>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                    <?php echo number_format($order_req_details['order_req_amount'], 2); ?>
                </div>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:5px !important;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="height:22px;">
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 " style="text-align:right;padding:0;">
                <div class="col-xs-9 col-sm-9 col-md-9">
                    <strong>Shipping Cost:</strong>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                    <?php echo number_format($order_req_details['order_req_shipping_cost'], 2); ?>
                </div>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:5px !important;">
            <div class="col-xs-6 col-sm-6 col-md-6" style="height:22px;">
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 " style="text-align:right;padding:0;">
                <div class="col-xs-9 col-sm-9 col-md-9">
                    <strong>Total:</strong>
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3">
                    <?php echo number_format($order_req_details['order_req_grand_total'], 2); ?>
                </div>
            </div>
        </div>
        <div class="row order_requisition_details_row" style="margin-top:15px !important;margin-bottom:10px !important;">
            <div class="col-sm-12 col-md-6" style="">
                <strong>Employee Signature:</strong> &nbsp; <span> <hr style=" width: 220px; border-color: #292626; margin-left: 145px; margin-top: -2px;"/> </span>
            </div>
        </div>
    </div>

    <div class="col-md-12 hidden-print">
        <hr />
        <button class="btn btn-default print-iframe" style="margin-right:10px;" ><i class="fa fa-print"></i> Print</button>
        <button type="button" class="btn btn-danger pull-right" onclick="closeModalbox()">Close</button>
    </div>
    <!-- <iframe id="printf" name="printf" src="<?php echo base_url(); ?>inventory/purchase_order_requisition_details_v2/<?php echo $purchase_order_no; ?>/<?php echo $order_req_id; ?>" style="display: none;"></iframe> -->
</div>
<?php
  }
?>