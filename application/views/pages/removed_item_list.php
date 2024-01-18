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

	.removed_inventory_item_list_td
	{
		text-align:center !important;
	}

	.remove_inventory_item
	{
		font-size:16px;
		color:#9a0606;
		cursor: pointer;
	}

	.removed_item_list_header
	{
		height:40px;
	}

</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  	<h1 class="m-n font-thin h3">Removed Inventory Item List</h1>
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
    	<div class="panel-heading removed_item_list_header">
    	</div>
    	<div class="panel-body" style="overflow-x:scroll;">
    		<?php 
    			if(!empty($removed_item_list))
    			{
    		?>
    				<table class="table m-b-none datatable_table_removed_inventory_item_list" style="min-width: 900px !important;">
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
		          		<th style="width:10%;text-align:center;"> Date out of Service</th>
			            <th style="width:10%;text-align:center;"> Item No.</th>
			            <th style="width:14%;text-align:center;"> Item Description</th>
			            <th style="width:14%;text-align:center;"> Vendor Name</th>
			            <th style="width:15%;text-align:center;"> Serial No.</th>
			            <th style="width:15%;text-align:center;"> Asset No.</th>
			            <th style="width:10%;text-align:center;"> Reason </th>
			            <th style="width:12%;text-align:center;"> Action </th>
		          	</tr>
		        </thead>
		        <tbody class="">
		        	<?php 
		        		if(!empty($removed_item_list))
			        	{ 
			        		foreach ($removed_item_list as $key => $value) 
				        	{
		        	?>
		        				<tr style="height:43px;">
		        					<td class="removed_inventory_item_list_td"> 
					        			<?php echo date("m/d/Y", strtotime($value['date_out_of_service'])); ?>
					        		</td>
			        				<td class="removed_inventory_item_list_td"> 
					        			<?php echo $value['company_item_no']; ?>
					        		</td>
					        		<td class="removed_inventory_item_list_td"> 
					        			<?php echo $value['item_description']; ?>
					        		</td>
					        		<td class="removed_inventory_item_list_td"> 
					        			<?php echo $value['vendor_name']; ?>
					        		</td>
					        		<td class="removed_inventory_item_list_td"> 
					        			<?php echo $value['item_serial_no']; ?>
					        		</td>
					        		<td class="removed_inventory_item_list_td"> 
					        			<?php echo $value['item_asset_no']; ?>
					        		</td>
					        		<td class="removed_inventory_item_list_td"> 
					        			<?php 
					        				if($value['removal_reason'] == "missing_item")
					        				{
					        					echo "Missing Item";
					        				}
					        				else if($value['removal_reason'] == "out_for_repair")
					        				{
					        					echo "Out for Repair";
					        				}
					        				else
					        				{
					        					echo "Thrown Out";
					        				}
					        			?>
					        		</td>
					        		<td class="removed_inventory_item_list_td">
					        			<button type="button" 
					        					class="btn btn-xs btn-info put_to_inventory"
					        					data-inventory-item-id="<?php echo $value['inventory_item_id']; ?>" 
					        			> 
					        				Put to Inventory 
					        			</button>
					        		</td>
					        	</tr>
		        	<?php 
		        			}
		        		}
		        		else
		        		{
		        	?>
		        			<tr>
				        		<td colspan="8" style="text-align:center;font-size: 17px;height: 50px;padding-top: 12px !important;"> No data. </td>
				        	</tr>
		        	<?php 
		        		}
		        	?>
		        </tbody>
	       	</table>
    	</div>
    </div>
</div>

<script type="text/javascript"> 

	$(document).ready(function(){

		$('.datatable_table_removed_inventory_item_list').DataTable({});

		// View Order Requisition Details
		$('body').on('click','.put_to_inventory',function(){
			var _this = $(this);
			var inventory_item_id = $(this).attr("data-inventory-item-id");

			$.post(base_url+"inventory/put_item_to_inventory/"+ inventory_item_id +"/",'', function(response){
                var obj = $.parseJSON(response); 
                if(obj['error'] == 0)
                {	
                	me_message_v2({error:obj['error'],message:obj['message']});
            		setTimeout(function(){
                        location.reload();
                    },1200);
                }
                else
                {
                	me_message_v2({error:obj['error'],message:obj['message']});
                }
            });
		});

	});

</script>

