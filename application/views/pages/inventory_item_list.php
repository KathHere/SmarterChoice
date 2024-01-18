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

</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  	<h1 class="m-n font-thin h3">Inventory Item List</h1>
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
    		<div class="form-group clearfix" style="margin-bottom:0px !important;">
				<div class="col-sm-10">
					
				</div>
				<div class="col-sm-2" style="text-align:right !important;">			
					<a href="<?php echo base_url() ?>inventory/show_removed_items"> <i class="fa fa-trash-o"></i> Removed Items</a>
				</div>
			</div>
    	</div>
    	<div class="panel-body" style="overflow-x:auto;">
    		<?php 
    			if(!empty($inventory_item_list))
    			{
    		?>
    				<table class="table m-b-none datatable_table_inventory_item_list" style="min-width: 900px !important;">
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
		            <th style="width:10%;text-align:center;"> Item No.</th>
		            <th style="width:16%;text-align:center;"> Item Description</th>
		            <th style="width:16%;text-align:center;"> Vendor Name</th>
		            <th style="width:15%;text-align:center;"> Serial No.</th>
		            <th style="width:15%;text-align:center;"> Asset No.</th>
		            <th style="width:10%;text-align:center;"> Warehouse Location</th>
		            <th style="width:10%;text-align:center;"> Status </th>
					<?php if ($this->session->userdata('account_type') != "distribution_supervisor") { ?>
		            <th style="width:8%;text-align:center;"> Delete </th>
					<?php } ?>
	          	</tr>
	        </thead>
	        <tbody class="">
	        	
	        </tbody>
    	</div>
    </div>

</div>
<input type="hidden" id="user_account_type" value="<?php echo $this->session->userdata('account_type'); ?>">
<script type="text/javascript"> 

	$(document).ready(function(){
		var useraccounttype = $('#user_account_type').val();
		var columndata = [];
		var columndefs = [];
		if (useraccounttype != 'distribution_supervisor') {
			columndata = [
		        { "data": "company_item_no" },
		        { "data": "item_description" },
		        { "data": "vendor_name" },
		        { "data": "item_serial_no" },
		        { "data": "item_asset_no" },
		        { "data": "item_warehouse_location" },
		        { "data": "item_status_location" },
		        { "data": "delete_button" }
		    ];
			columndefs = [
		        {
		            "targets": 7,
		            "searchable": false,
		            "orderable": false
		        }
		    ];
		} else {
			columndata = [
		        { "data": "company_item_no" },
		        { "data": "item_description" },
		        { "data": "vendor_name" },
		        { "data": "item_serial_no" },
		        { "data": "item_asset_no" },
		        { "data": "item_warehouse_location" },
		        { "data": "item_status_location" }
		    ];
			columndefs = [];
		}

		var datatable = $('.datatable_table_inventory_item_list').DataTable({
			fnDrawCallback: function( oSettings ) {
				$.fn.editable.defaults.mode = 'popover';
		      	$('body .editable-click.editable-itemlist').editable({
			        emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
			        validate: function(value) {
			            if($.trim(value) == '') {
			                return 'This field is required';
			            }
			        },
			        success:function(response,newValue){
			            if(response.error==1) return response.message;
			        }
   				});
		    },
		    "lengthMenu": [10,25,50,75,100,200,300,500],
		    "pageLength": 10,
		    "processing": true,
		    "serverSide": true,
		    "responsive": true,
		    "deferRender": true,
		    "ajax": {
		        url: base_url+"inventory/get_inventory_item_list"
		    },
		    "columns": columndata,
		    "order": [[ 1, 'asc' ]],
		    "columnDefs": columndefs
		}); 

		// Remove item from inventory
	  	$('body').on('click','.remove_inventory_item',function(){
	  		var _this = $(this);
	  		var inventory_item_id = $(this).attr("data-inventory-item-id");

			jConfirm("Remove item from inventory?","Warning", function(response){
		        if(response)
		        {
		        	modalbox(base_url + 'inventory/remove_inventory_item_reason/'+ inventory_item_id ,{
		                header:"Reason for removing the item",
		                button: false,
		            });
		        }
	        });
	  	});
	});

</script>