<script src="<?php echo base_url() ?>assets/js/inventory.js"></script>

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
	.first_table_td
	{
		padding-left: 25px !important;
	}
	.view_purchase_order, .view_req_receiving_info
	{
		cursor: pointer;
	}
	.change_order_req_status
	{
		width:145px !important;
	}
	.order_inquiry_td
	{
		text-align:center;
	}

</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  <h1 class="m-n font-thin h3">Draft Orders</h1>
</div>

<div class="wrapper-md pb0 hidden-print">
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
			<strong class="purchase_order_inquiry_info" >Location</strong>: <span class="purchase_order_inquiry_info location_info" > <?php echo $location['user_city'].", ".$location['user_state']; ?> </span>
		</div>
  	</div>
</div>

<div class="wrapper-md hidden-print">
  	<div class="panel panel-default">

    	<div class="panel-body" style="overflow-x:auto;">
    		<?php
                if (!empty($order_inquiries)) {
                    ?>
    				<table class="table m-b-none datatable_table_purchase_order_inquiry" style="min-width: 900px !important;">
    		<?php
                } else {
                    ?>
    				<table class="table m-b-none" style="min-width: 900px !important;">
    		<?php
                }
            ?>
		       	<thead>
		          	<tr>
                        <th style="width:10%;text-align:center;">Order Date</th>
			            <th style="width:10%;text-align:center;">PO No.</th>
			            <th style="width:20%;text-align:center;">Vendor</th>
			            <th style="width:14%;text-align:center;">Confirmation No.</th>
			            <th style="width:12%;text-align:center;">Open Balance</th>
			            <th style="width:12%;text-align:center;">Action</th>
		          	</tr>
		        </thead>
		        <tbody class="">
		        	<?php
                        $total_open_balance = 0;
                        $purchase_order_no = array();
                        if (!empty($order_inquiries)) {
                            $req_receiving_batch_no = 0;
                            foreach ($order_inquiries as $key => $value) {
                                if ($value['req_receiving_batch_no'] != $req_receiving_batch_no) {
                                    if ($value['req_receiving_status'] == 'received-partial') {
                                        ?>
		        						<tr style="background-color:rgba(93, 87, 87, 0.07);">

							        		<td class="order_inquiry_td">
							        			<?php echo date("m/d/Y", strtotime($value['order_req_date'])); ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<span
							        				class="view_purchase_order"
							        				data-req-receive-batch-no="<?php echo $value['req_receiving_batch_no']; ?>"
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
							        		<td class="order_inquiry_td"> <?php echo number_format($value['order_req_grand_total'], 2); ?></td>
							        		<td class="order_inquiry_td">
							        			<button type="button"
							        				class="btn btn-danger btn-xs delete_draft_order_req"
							        				data-order-req-id="<?php echo $value['order_req_id']; ?>"
							        			>
							        				Delete
							        			</button>
							        			<button type="button"
							        				class="btn btn-info btn-xs revert_draft_order_req"
							        				data-order-req-id="<?php echo $value['order_req_id']; ?>"
							        			>
							        				Put to PO Inquiry
							        			</button>
							        		</td>
							        	</tr>
		        	<?php
                                    } else {
                                        ?>
		        						<tr>

							        		<td class="order_inquiry_td">
							        			<?php echo date("m/d/Y", strtotime($value['order_req_date'])); ?>
							        		</td>
							        		<td class="order_inquiry_td">
							        			<span
							        				class="view_purchase_order"
							        				data-req-receive-batch-no="<?php echo $value['req_receiving_batch_no']; ?>"
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
							        		<td class="order_inquiry_td"> <?php echo number_format($value['order_req_grand_total'], 2); ?></td>
							        		<td class="order_inquiry_td">
							        			<button type="button"
							        				class="btn btn-danger btn-xs delete_draft_order_req"
							        				data-order-req-id="<?php echo $value['order_req_id']; ?>"
							        			>
							        				Delete
							        			</button>
							        			<button type="button"
							        				class="btn btn-info btn-xs revert_draft_order_req"
							        				data-order-req-id="<?php echo $value['order_req_id']; ?>"
							        			>
							        				Put to PO Inquiry
							        			</button>
							        		</td>
							        	</tr>
		        	<?php
                                    }
                                    if (!in_array($value['purchase_order_no'], $purchase_order_no)) {
                                        $purchase_order_no[] = $value['purchase_order_no'];
                                        $total_open_balance += $value['order_req_grand_total'];
                                    }
                                }
                                $req_receiving_batch_no = $value['req_receiving_batch_no'];
                            }
                        } else {
                            ?>
		        			<tr>
				        		<td colspan="8" style="text-align:center;font-size: 17px;height: 50px;padding-top: 12px !important;"> No data. </td>
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
			        		<td class="order_inquiry_td"> <?php echo number_format($total_open_balance, 2); ?> </td>
			        	</tr>
			    <?php
                    }
                ?>
		    </table>
    	</div>
    </div>

</div>

<div class="modal fade" id="serial_asset_no_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10010;">
	<div class="modal-dialog" style="top: 100px;left: 345px;">
	    <div class="modal-content">
	      	<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        	<h4 class="modal-title">Add Serial & Asset No.</h4>
	      	</div>
	      	<div class="modal-body">
	      		<?php echo form_open("", array("id"=>"save_serial_asset_no_form")) ?>
	      			<div class="serial_asset_no_modal_content">

	      			</div>
	      		<?php echo form_close() ?>
	      	</div>
	      	<div class="modal-footer" style="padding-left: 30px;padding-right: 30px;">
	      		<button type="button" class="btn btn-default skip_serial_asset_no pull-left"> Skip </button>
	      		<button type="button" class="btn btn-success save_serial_asset_no"> Save Changes </button>
	        	<button type="button" class="btn btn-danger close_serial_asset_no" data-dismiss="modal"> Close</button>
	    	</div>
	  	</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

	$(document).ready(function(){

		$('.datatable_table_purchase_order_inquiry').DataTable( {
	        "order": [[ 0, "desc" ]]
	    } );

		// View Order Requisition Details
		$('body').on('click','.view_purchase_order',function(){
			var _this = $(this);
			var purchase_order_no = $(this).attr("data-purchase-order-no");
			var order_req_id = $(this).attr("data-order-req-id");
			var req_receiving_batch_no = $(this).data("req-receive-batch-no");

			modalbox(base_url + 'inventory/purchase_order_requisition_details/'+ purchase_order_no + "/"+ order_req_id,{
	            header:"Purchase Order Requisition",
	            button: false,
	        });
		});

		// View Order Requisition Receiving Details
		$('body').on('click','.view_req_receiving_info',function(){
			var _this = $(this);
			var purchase_order_no = $(this).attr("data-purchase-order-no");
			var order_req_id = $(this).attr("data-order-req-id");
			var req_receiving_batch_no = $(this).data("req-receive-batch-no");

			modalbox(base_url + 'inventory/order_req_receiving_details/'+ purchase_order_no + "/"+ order_req_id +"/"+ req_receiving_batch_no,{
	            header:"Purchase Order Requisition Receiving Details",
	            button: false,
	        });
		});

		//change purchase order requisition receive status
	  	$('.datatable_table_purchase_order_inquiry').on('change','.change_order_req_status',function(){
	  		var _value = $(this).closest("tr").find("td").find("select").val();
	  		var purchase_order_no = $(this).closest("tr").find("td").find("select").attr("data-purchase-order-no");
	  		var order_req_id = $(this).closest("tr").find("td").find("select").data("order-req-id");

  			jConfirm("Change Purchase Order Requisition Status?","Warning", function(response){
		        if(response)
		        {
		        	$.post(base_url+"inventory/change_order_req_status/"+ _value + "/"+ purchase_order_no +"/"+ order_req_id +"/",'', function(response){
		                var obj = $.parseJSON(response);
		                if(obj)
		                {
		                	me_message_v2({error:0,message:"Order Requisition Status Updated."});
		                	if(_value == "cancelled")
		                	{
		                		setTimeout(function(){
			                        location.reload();
			                    },1200);
		                	}
		                }
		                else
		                {
		                	me_message_v2({error:0,message:"Error Updating Order Requisition Status."});
		                }
		            });
		        }
	        });
	  	});

	  	// Receive Order Requisition
	  	$('body').on('click','.receive_order_req',function(){
	  		var _this = $(this);
	  		var purchase_order_no = $(this).attr("data-purchase-order-no");
			var order_req_id = $(this).attr("data-order-req-id");
			var req_receive_batch_no = $(this).data('req-receive-batch-no');

			jConfirm("Receive Order Requisition?","Warning", function(response){
		        if(response)
		        {
		        	modalbox(base_url + 'inventory/purchase_order_requisition_receiving/'+ purchase_order_no +"/"+ order_req_id +"/"+ req_receive_batch_no,{
		                header:"Purchase Order Requisition Receiving",
		                button: false,
		            });

		            $('body').on('click','.close_order_req_receiving',function(){
		            	_this.prop('checked', false);
		            });
		        }
		        else
		        {
		        	_this.prop('checked', false);
		        }
	        });
	  	});

	  	// Revert draft Order
		$('body').on('click','.revert_draft_order_req',function(){
			var _this = $(this);
			var order_req_id = $(this).data("order-req-id");

			$.post(base_url+"inventory/revert_draft_order_inventory/"+ order_req_id +"/",'', function(response){
                var obj = $.parseJSON(response);
                if(obj)
                {
                	me_message_v2({error:0,message:"Order Requisition Status Updated."});
            		setTimeout(function(){
                        location.reload();
                    },1500);
                }
                else
                {
                	me_message_v2({error:0,message:"Error Updating Order Requisition Status."});
                }
            });
		});

		// Delete draft Order
		$('body').on('click','.delete_draft_order_req',function(){
			var _this = $(this);
			var order_req_id = $(this).data("order-req-id");

			jConfirm("Confirm Delete Order?","Reminder",function(response){
				if(response)
    			{
    				$.post(base_url+"inventory/delete_draft_order_inventory/"+ order_req_id +"/",'', function(response){
		                var obj = $.parseJSON(response);
		                if(obj)
		                {
		                	me_message_v2({error:0,message:"Order Requisition Deleted."});
		            		setTimeout(function(){
		                        location.reload();
		                    },1500);
		                }
		                else
		                {
		                	me_message_v2({error:0,message:"Error Deleting Order Requisition Status."});
		                }
		            });
    			}
			});
		});
	});

</script>


