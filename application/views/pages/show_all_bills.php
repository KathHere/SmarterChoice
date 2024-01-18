<style type="text/css">

	.purchase_order_inquiry_info
	{
		font-size: 15px !important;
	}

	.panel-body
	{
		padding-left: 0px!important;
		padding-right: 0px!important;
	}

	.order_inquiry_td
	{
		text-align: center;
	}

	.first_table_td
	{
		padding-left: 25px !important;
	}

	.view_purchase_order, .view_req_payment_info
	{
		cursor: pointer;
	}

</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  <h1 class="m-n font-thin h3">All Bills</h1>
</div>

<div class="wrapper-md pb0 rowX hidden-print">
    <div class="form-group clearfix">
		<div class="col-sm-6" style="padding-left:5px;">
			<strong class="purchase_order_inquiry_info" >Company</strong>: <span class="purchase_order_inquiry_info" > Advantage Home Medical Services</span>
		</div>
		<div class="col-sm-6" style="padding-left:5px;">
			<strong class="purchase_order_inquiry_info" >Open Balance</strong>: <span class="purchase_order_inquiry_info" > <?php echo number_format($open_balance, 2); ?> </span>
		</div>
  	</div>
  	<div class="form-group clearfix" style="margin-bottom:5px !important;">
		<div class="col-sm-6" style="padding-left:5px;">
			<?php
                $location = get_login_location($this->session->userdata('user_location'));
            ?>
			<strong class="purchase_order_inquiry_info" >Location</strong>: <span class="purchase_order_inquiry_info location_info_all_bills" > <?php echo $location['user_city'].", ".$location['user_state']; ?> </span>
		</div>
  	</div>
</div>

<div class="wrapper-md hidden-print">
  	<div class="panel panel-default">
    	<div class="panel-heading purchase_order_list_header" >
    		<div class="form-group clearfix" style="margin-bottom:0px !important;">
				<div class="col-sm-6">

				</div>
				<div class="col-sm-2" style="text-align:right !important;">
					<a href="<?php echo base_url() ?>inventory/purchase_order_inquiry"> <i class="fa fa-align-right"></i> Purchase Order Inquiry</a>
				</div>
				<div class="col-sm-2" style="text-align:right !important;">
					<a href="<?php echo base_url() ?>inventory/show_all_payments"> <i class="fa fa-money"></i> Show All Payments</a>
				</div>
				<div class="col-sm-2" style="text-align:right !important;">
					<a href="<?php echo base_url() ?>inventory/purchase_order_requisition"> <i class="fa fa-list"></i> Create PO Requisition</a>
				</div>
			</div>
    	</div>
    	<div class="panel-body" style="overflow-x:scroll;">
		    <?php
                if (!empty($order_inquiries)) {
                    ?>
    				<table class="table m-b-none datatable_table" style="min-width: 900px !important;">
    		<?php
                } else {
                    ?>
    				<table class="table m-b-none" style="min-width: 900px !important;">
    		<?php
                }
            ?>
		       	<thead>
		          	<tr>
		          		<th style="width:7%;text-align:center;"> Select</th>
		          		<th style="width:10%;text-align:center;">Paid Date</th>
			            <th style="width:10%;text-align:center;">Order Date</th>
			            <th style="width:10%;text-align:center;">PO No.</th>
			            <th style="width:15%;text-align:center;">Vendor</th>
			            <th style="width:10%;text-align:center;">Confirmation No.</th>
			            <th style="width:9%;text-align:center;">Credit</th>
			            <th style="width:9%;text-align:center;">Amount Due</th>
			            <th style="width:8%;text-align:center;">Credit Used</th>
			            <th style="width:9%;text-align:center;">Amount Paid</th>
		          	</tr>
		        </thead>
		        <tbody class="">
		        	<?php
                        $total_amount_due = 0;
                        $total_credit = 0;
                        $total_credit_used = 0;
                        $total_amount_paid = 0;
                        $purchase_order_no = array();
                        $vendor_list = array();
                        if (!empty($order_inquiries)) {
                            $req_payment_batch_no = 0;
                            foreach ($order_inquiries as $key => $value) {
                                if (!in_array($value['vendor_id'], $vendor_list)) {
                                    $total_credit += $value['vendor_credit'];
                                    $vendor_list[] = $value['vendor_id'];
                                }
                                if ($value['req_payment_batch_no'] != $req_payment_batch_no) {
                                    if ($value['payment_date'] != '0000-00-00') {
                                        ?>
		        						<tr style="background-color:rgba(93, 87, 87, 0.07);">
							        		<td class="first_table_td">
							        			<label class="i-checks data_tooltip" >
					                                <input
					                                    type="checkbox"
					                                    name=""
					                                    class="pay_order_req"
					                                    data-req-payment-batch-no="<?php echo $value['req_payment_batch_no']; ?>"
					                                    data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
					                                    data-order-req-id="<?php echo $value['order_req_id']; ?>"
					                                    onclick="return false"
					                                    checked
																							disabled
					                                />
					                                <i></i>
					                            </label>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<span
							        				class="view_req_payment_info"
							        				data-req-payment-batch-no="<?php echo $value['req_payment_batch_no']; ?>"
							        				data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
							        				data-order-req-id="<?php echo $value['order_req_id']; ?>"
							        			>
								        			<?php
                                                        if ($value['payment_date'] == "0000-00-00") {
                                                            echo "";
                                                        } else {
                                                            echo date("m/d/Y", strtotime($value['payment_date']));
                                                        } ?>
							        			</span>
							        		</td>
							        		<td class="order_inquiry_td"> <?php echo date("m/d/Y", strtotime($value['order_req_date'])); ?> </td>
							        		<td class="order_inquiry_td">
							        			<span
							        				class="view_purchase_order"
							        				data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
							        				data-order-req-id="<?php echo $value['order_req_id']; ?>"
							        			>
							        				<?php echo substr($value['purchase_order_no'], 3, 10); ?>
							        			</span>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo $value['vendor_name']; ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo $value['order_req_confirmation_no']; ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo number_format($value['vendor_credit'], 2); ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo number_format($value['order_req_grand_total'], 2); ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo number_format($value['credit_used'], 2); ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo number_format($value['payment_amount'], 2); ?>
							        		</td>
							        	</tr>
		        	<?php
                                    } else {
                                        ?>
		        						<tr>
							        		<td class="first_table_td">
							        			<?php
                                                if ($value['order_req_status'] != "received") {
                                                    ?>
							        				<label class="i-checks data_tooltip" >
						                                <input
						                                    type="checkbox"
						                                    name=""
						                                    class="pay_order_req"
						                                    data-req-payment-batch-no="<?php echo $value['req_payment_batch_no']; ?>"
						                                    data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
						                                    data-order-req-id="<?php echo $value['order_req_id']; ?>"
						                                    disabled
						                                    checked

						                                />
						                                <i></i>
						                            </label>
							        			<?php
                                                } else {
                                                    ?>
							        				<label class="i-checks data_tooltip" >
						                                <input
						                                    type="checkbox"
						                                    name=""
						                                    class="pay_order_req"
						                                    data-req-payment-batch-no="<?php echo $value['req_payment_batch_no']; ?>"
						                                    data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
						                                    data-order-req-id="<?php echo $value['order_req_id']; ?>"
						                                />
						                                <i></i>
						                            </label>
							        			<?php
                                                } ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<span
							        				class="view_req_payment_info"
							        				data-req-payment-batch-no="<?php echo $value['req_payment_batch_no']; ?>"
							        				data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
							        				data-order-req-id="<?php echo $value['order_req_id']; ?>"
							        			>
								        			<?php
                                                        if ($value['payment_date'] == "0000-00-00") {
                                                            echo "";
                                                        } else {
                                                            echo date("m/d/Y", strtotime($value['payment_date']));
                                                        } ?>
							        			</span>
							        		</td>
							        		<td class="order_inquiry_td"> <?php echo date("m/d/Y", strtotime($value['order_req_date'])); ?> </td>
							        		<td class="order_inquiry_td">
							        			<span
							        				class="view_purchase_order"
							        				data-req-payment-batch-no="<?php echo $value['req_payment_batch_no']; ?>"
							        				data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
							        				data-order-req-id="<?php echo $value['order_req_id']; ?>"
							        			>
							        				<?php echo substr($value['purchase_order_no'], 3, 10); ?>
							        			</span>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo $value['vendor_name']; ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo $value['order_req_confirmation_no']; ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo number_format($value['vendor_credit'], 2); ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo number_format($value['order_req_grand_total'], 2); ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo number_format($value['credit_used'], 2); ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<?php echo number_format($value['payment_amount'], 2); ?>
							        		</td>
							        	</tr>
		        	<?php
                                        if (!in_array($value['purchase_order_no'], $purchase_order_no)) {
                                            $total_amount_due += $value['order_req_grand_total'];
                                            $purchase_order_no[] = $value['purchase_order_no'];
                                        }
                                    }
                                }
                                $req_payment_batch_no = $value['req_payment_batch_no'];
                                $total_credit_used += $value['credit_used'];
                                $total_amount_paid += $value['payment_amount'];
                            }
                        } else {
                            ?>
		        			<tr>
				        		<td colspan="10" style="text-align:center;font-size: 17px;height: 50px;padding-top: 12px !important;"> No data. </td>
				        	</tr>
		    		<?php
                        }
                    ?>
		        </tbody>
		        <?php
                    if (!empty($order_inquiries)) {
                        ?>
	    				<tr style="font-size:15px !important;">
			        		<td class="first_table_td"> </td>
			        		<td class="order_inquiry_td"> </td>
			        		<td class="order_inquiry_td"> </td>
			        		<td class="order_inquiry_td"> </td>
			        		<td class="order_inquiry_td"> </td>
			        		<td class="order_inquiry_td"> </td>
			        		<td class="order_inquiry_td"> <?php echo number_format($total_credit, 2); ?> </td>
			        		<td class="order_inquiry_td"> <?php echo number_format($total_amount_due, 2); ?> </td>
			        		<td class="order_inquiry_td"> <?php echo number_format($total_credit_used, 2); ?></td>
			        		<td class="order_inquiry_td"> <?php echo number_format($total_amount_paid, 2); ?></td>
			        	</tr>
	    		<?php
                    }
                ?>
		    </table>
    	</div>
    </div>
</div>

<script type="text/javascript">

	$(document).ready(function(){

		// View Order Requisition Details
		$('body').on('click','.view_purchase_order',function(){
			var _this = $(this);
			var purchase_order_no = $(this).attr("data-purchase-order-no");
			var order_req_id = $(this).attr("data-order-req-id");

			modalbox(base_url + 'inventory/purchase_order_requisition_details/'+ purchase_order_no + "/"+ order_req_id,{
	            header:"Purchase Order Requisition",
	            button: false,
	        });
		});

		// View Order Payment Details
		$('body').on('click','.view_req_payment_info',function(){
			var _this = $(this);
			var purchase_order_no = $(this).attr("data-purchase-order-no");
			var order_req_id = $(this).attr("data-order-req-id");
			var req_payment_batch_no = $(this).data('req-payment-batch-no');

			modalbox(base_url + 'inventory/view_req_payment_info/'+ purchase_order_no + "/"+ order_req_id + "/"+ req_payment_batch_no,{
	            header:"Purchase Order Requisition Payment Details",
	            button: false,
	        });
		});

		// Pay Order Requisition
		$('body').on('click','.pay_order_req',function(){
			var _this = $(this);

			if(_this.is(":checked"))
            {
		  		var purchase_order_no = $(this).attr("data-purchase-order-no");
				var order_req_id = $(this).attr("data-order-req-id");
				var req_payment_batch_no = $(this).data('req-payment-batch-no');

	        	modalbox(base_url + 'inventory/pay_order_requisition/'+ purchase_order_no +"/"+ order_req_id +"/"+ req_payment_batch_no,{
	                header:"Purchase Order Requisition Payment",
	                button: false,
	            });

	            $('body').on('click','.close_order_req_payment',function(){
	            	_this.prop('checked', false);
	            });
            }
		});




	});

</script>


