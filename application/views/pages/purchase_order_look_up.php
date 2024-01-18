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

	.purchase_order_lookup_header
	{
		height: 34px !important;
	}

	.order_inquiry_td
	{
		text-align:center;
	}

	.view_purchase_order, .view_req_receiving_info
	{
		cursor: pointer;
	}

	.purchase_order_lookup_col
	{
		text-align: center !important;
	}
</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  <h1 class="m-n font-thin h3">Purchase Order Look up</h1>
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
    	<div class="panel-heading purchase_order_lookup_header">

    	</div>
    	<div class="panel-body" style="overflow-x:auto; position:relative;">

    		<?php
                if (!empty($order_inquiries) || !empty($equipment_transfer_receiving)) {
                    ?>
    				<table class="table m-b-none datatable_table_purchase_order_lookup" style="min-width: 900px !important;">
    		<?php
                } else {
                    ?>
    				<table class="table m-b-none" style="min-width: 900px !important;">
    		<?php
                }
            ?>
    			<thead>
		          	<tr>
                        <th style="width:15%;text-align:center;">Received Date</th>
                        <th style="width:15%;text-align:center;">Order Date</th>
			            <th style="width:20%;text-align:center;">PO No.</th>
			            <th style="width:30%;text-align:center;">Vendor</th>
			            <th style="width:20%;text-align:center;">Confirmation No.</th>
		          	</tr>
		        </thead>
		        <tbody class="">

		        </tbody>
		    </table>
    	</div>
    </div>

</div>

<script type="text/javascript">

	$(document).ready(function(){

	    var datatable = $('.datatable_table_purchase_order_lookup').DataTable({
		    "lengthMenu": [10,25,50,75,100],
		    "pageLength": 10,
		    "processing": true,
		    "serverSide": true,
		    "responsive": true,
		    "deferRender": true,
		    "ajax": {
		        url: base_url+"inventory/purchase_order_lookup_data"
		    },
		    "columns": [
		        { "data": "received_date_col" },
		        { "data": "order_date_col" },
		        { "data": "po_number" },
		        { "data": "vendor_name_col" },
		        { "data": "confirmation_no_col" }
		    ],
		    "order": [[ 1, 'asc' ]],
		    "columnDefs":[
                {
                    "targets": 0,
                    "className": "purchase_order_lookup_col"
                },
                {
                    "targets": 1,
                    "className": "purchase_order_lookup_col"
                },
                {
                    "targets": 2,
                    "className": "purchase_order_lookup_col"
                },
                {
                    "targets": 3,
                    "className": "purchase_order_lookup_col"
                },
                {
                    "targets": 4,
                    "className": "purchase_order_lookup_col"
                }
            ]
		});

		// View Order Requisition Details
		$('body').on('click','.view_purchase_order',function(){
			var _this = $(this);
			var purchase_order_no = $(this).attr("data-purchase-order-no");
			var order_req_id = $(this).attr("data-order-req-id");
			var req_receiving_batch_no = $(this).data("req-receive-batch-no");

			modalbox(base_url + 'inventory/purchase_order_requisition_details/'+ purchase_order_no + "/"+ order_req_id +"/",{
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



	});

</script>


