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

	.inventory_item_list_td
	{
		text-align:center !important;
	}

	.remove_inventory_item
	{
		color:#9a0606;
	}

	.edit_inventory_item
	{
		color:#41aed2;
		margin-right:7px;
		cursor: pointer;
	}

	.item_no_col
	{
		width:14% !important;
	}

	.item_description_col
	{
		width: 30% !important;
	}

</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  	<h1 class="m-n font-thin h3">Inventory Run Item Numbers</h1>
</div>

<div class="wrapper-md pb0 hidden-print">
    <div class="form-group clearfix">
		<div class="col-sm-6" style="padding-left:5px;">
			<strong class="purchase_order_inquiry_info" >Company</strong>: <span class="purchase_order_inquiry_info" > Advantage Home Medical Services</span>
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
    	<div class="panel-heading inventory_item_list_header">
    		<div class="form-group clearfix">
			</div>
    	</div>
    	<div class="panel-body" style="overflow-x:auto;">
    		<?php 
    			if(!empty($inventory_item_list))
    			{
    		?>
    				<table class="table m-b-none datatable_run_item_nos" style="min-width: 900px !important;">
    		<?php
    			}
    			else
    			{
			?>
    				<table class="table m-b-none" style="min-width: 900px !important;">
    		<?php
    			}
    		?>

    		<thead>
	          	<tr style="height:43px;"> 
		            <th style="width:18%;text-align:center;"> Item No.</th>
		            <th style="width:24%;text-align:center;"> Item Description</th>
		            <th style="width:24%;text-align:center;"> Vendor Name</th>
		            <th style="width:17%;text-align:center;"> Warehouse Location</th>
		            <th style="width:17%;text-align:center;"> Category </th>
	          	</tr>
	        </thead>
	        <tbody class="" style="text-align:center;">
	        	
	        </tbody>
    	</div>
    </div>

</div>

<script type="text/javascript"> 

	$(document).ready(function(){

		var datatable = $('.datatable_run_item_nos').DataTable({
		    "lengthMenu": [10,25,50,75,100,200,300,500],
		    "pageLength": 10,
		    "processing": true,
		    "serverSide": true,
		    "responsive": true,
		    "deferRender": true,
		    "ajax": {
		        url: base_url+"inventory/get_inventory_run_item_list"
		    },
		    "columns": [
		        { "data": "company_item_no" },
		        { "data": "item_description" },
		        { "data": "vendor_name" },
		        { "data": "item_warehouse_location" },
		        { "data": "item_category_name" },
		    ],
		    "order": [[ 1, 'asc' ]],
		    "columnDefs":[
		        {
		            "targets": 1,
		            "className": "item_description_col"
		        },
		        {
		            "targets": 0,
		            "className": "item_no_col"
		        }
		    ]
		}); 
	});

</script>