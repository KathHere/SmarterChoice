<style type="text/css">

	.purchase_item_panel_header
	{
		height:40px;
	}

	.purchase_item_td
	{
		text-align:center !important;
		height: 30px !important;
	}

	.panel-body
	{
		padding-left: 0px!important;
		padding-right: 0px!important;
	}

	.view_purchase_order
	{
		cursor: pointer;
	}

</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  	<h1 class="m-n font-thin h3">Purchase Item Report</h1>
</div>

<div class="wrapper-md hidden-print">
  	<div class="panel panel-default">
    	<div class="panel-heading purchase_item_panel_header">
    	</div>
    	<div class="panel-body" style="overflow-x:auto;">
    		<div class="col-sm-12 col-md-12 " style="margin-top:15px;margin-bottom:15px;border-bottom:1px solid rgba(138, 136, 136, 0.12); height:50px;">
    			<div class="col-sm-4 col-md-4">

				</div>
				<div class="col-sm-1 col-md-1" style="margin-top:4px;padding-right: 0px;">
					From
				</div>
				<div class="col-sm-1 col-md-1" style="left: -15px !important;padding-right: 0px !important;padding-left: 0px;">
					<input type="text" class="form-control choose_date_purchase_item" id="search_from_purchase_item" aria-describedby="sizing-addon2" value="<?php echo date('Y-m-01'); ?>">
				</div>

				<div class="col-sm-1 col-md-1" style="margin-top:4px;text-align: left;">
					To
				</div>
				<div class="col-sm-1 col-md-1" style="padding-right: 0px !important;padding-left: 0px !important;left: -30px;">
					<input type="text" class="form-control choose_date_purchase_item" id="search_to_purchase_item" aria-describedby="sizing-addon3" value="<?php echo date('Y-m-d'); ?>">
				</div>
				<div class="col-sm-2 col-md-2" style="margin-top:4px;padding-right:30px;text-align:right;">
					Sort by
            	</div>
            	<div class="col-sm-2 col-md-2" style="padding-right:0px;padding-left:0px;margin-left:-15px;">
            		<?php
                        $all_item_description = get_all_inventory_item_po_item_desc_distinct();
                        $all_item_no = get_all_inventory_item_po_item_no_distinct();
                    ?>
					<select class="form-control filter_purchase_item" name="filter_purchase_item" style="border: 0px;text-align-last:center;">
						<option value="all_item"> All Item </option>
						<?php
                            if (!empty($all_item_description) && !empty($all_item_no)) {
                                ?>
								<optgroup label="Item Description">
									<?php
                                        foreach ($all_item_description as $item_desc) {
                                            ?>
					                      	<option value="<?php echo $item_desc['item_id'] ?>"><?php echo $item_desc['item_description'] ?></option>
					                <?php
                                        } ?>
								</optgroup>
								<optgroup label="Item No.">
									<?php
                                        foreach ($all_item_no as $item_no) {
                                            ?>
					                      	<option value="<?php echo $item_no['item_id'] ?>"><?php echo $item_no['company_item_no'] ?></option>
					                <?php
                                        } ?>
								</optgroup>
						<?php
                            }
                        ?>
					</select>
            	</div>
    		</div>
    		<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:5px;">
	    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:3px;">
					<?php
                        echo date('h:i A');
                    ?>
				</div>
				<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;">
					<span style="font-size:16px;font-weight:bold;"> Advantage Home Medical Services </span>
				</div>
				<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:5px;">
	    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:3px;">
					<?php
                        $current_date = date('Y-m-d');
                        echo date("m/d/Y", strtotime($current_date));
                    ?>
				</div>
				<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;">
					<span> Purchase Item Report </span>
				</div>
				<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:2px;">
	    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:5px;">
	    			<?php
                        $location = get_login_location($this->session->userdata('user_location'));
                    ?>
					Location: <span class="location_info" > <?php echo $location['user_city'].", ".$location['user_state']; ?> </span>
				</div>
				<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;">
					<span class="chosen_filter_item"> All Item </span>
				</div>
				<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" style="">
	    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

				</div>
				<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;">
					<span class="chosen_filter_dates_purchase_item"> </span>
				</div>
				<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:25px;margin-bottom:10px;padding:0px;">

				<?php
                    if (!empty($all_inventory_item)) {
                        ?>
	    				<table class="table m-b-none datatable_table_purchase_item" style="min-width: 900px !important;">
	    		<?php
                    } else {
                        ?>
	    				<table class="table m-b-none" style="min-width: 900px !important;">
	    		<?php
                    }
                ?>

	    			<thead>
			          	<tr style="height:43px;">
				            <th style="width:10%;text-align:center;"> Date </th>
				            <th style="width:13%;text-align:center;"> PO No.</th>
				            <th style="width:15%;text-align:center;"> Item No.</th>
				            <th style="width:17%;text-align:center;"> Item Description</th>
				            <th style="width:17%;text-align:center;"> Vendor</th>
				            <th style="width:10%;text-align:center;"> Unit of Measure</th>
				            <th style="width:8%;text-align:center;"> Qty.</th>
				            <th style="width:10%;text-align:center;"> Total Cost</th>
			          	</tr>
			        </thead>
			        <tbody class="purchase_item_tbody">
			        	<?php
                            if (!empty($all_inventory_item)) {
                                $total_cost = 0;
                                $total_qty = 0;
                                $saved_combination = array();
                                foreach ($all_inventory_item as $key => $value) {
                                    $current_combination = $value['purchase_order_no']."-".$value['item_id'];
                                    if (!in_array($current_combination, $saved_combination)) {
                                        $saved_combination[] = $current_combination;
                                        $total_cost += $value['item_total_cost'];
                                        $total_qty += $value['item_quantity']; ?>
				        				<tr style="height:43px;">
					        				<td class="purchase_item_td">
							        			<?php echo date("m/d/Y", strtotime($value['order_req_date'])); ?>
							        		</td>
							        		<td class="purchase_item_td">
							        			<span
							        				class="view_purchase_order"
							        				data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
							        				data-order-req-id="<?php echo $value['order_req_id']; ?>"
								        		>
							        				<?php echo substr($value['purchase_order_no'], 3, 10); ?>
							        			</span>
							        		</td>
							        		<td class="purchase_item_td">
							        			<?php echo $value['company_item_no']; ?>
							        		</td>
							        		<td class="purchase_item_td">
							        			<?php echo $value['item_description']; ?>
							        		</td>
							        		<td class="purchase_item_td">
							        			<?php echo $value['vendor_name']; ?>
							        		</td>
							        		<td class="purchase_item_td">
							        			<?php echo $value['item_unit_measure']; ?>
							        		</td>
							        		<td class="purchase_item_td">
							        			<?php echo $value['item_quantity']; ?>
							        		</td>
							        		<td class="purchase_item_td">
							        			<?php echo number_format($value['item_total_cost'], 2); ?>
							        		</td>
							        	</tr>
			        	<?php
                                    }
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
                        if (!empty($all_inventory_item)) {
                            ?>
		        			<tr class="purchase_item_total_tr" style="font-size:15px !important;">
		        				<td class="purchase_item_td"> </td>
		        				<td class="purchase_item_td"> </td>
		        				<td class="purchase_item_td"> </td>
		        				<td class="purchase_item_td"> </td>
		        				<td class="purchase_item_td"> </td>
		        				<td class="purchase_item_td"> </td>
		        				<td class="purchase_item_td purchase_item_total_qty"> <?php echo $total_qty; ?> </td>
		        				<td class="purchase_item_td purchase_item_total_cost"> <?php echo number_format($total_cost, 2); ?> </td>
		        			</tr>
		        	<?php
                        }
                    ?>
	    		</table>

			</div>
    	</div>
    </div>
</div>

<script type="text/javascript">

	$(document).ready(function(){

		$('.datatable_table_purchase_item').DataTable({
			"order": [[ 0, "desc" ]]
		});

		$('.choose_date_purchase_item').datepicker({
	    	dateFormat: 'yy-mm-dd',
         	onClose: function (dateText, inst) {
         		var from_date = $("body").find("#search_from_purchase_item").val();
	  			var to_date = $("body").find("#search_to_purchase_item").val();
	  			var sort_by_item = $("body").find(".filter_purchase_item").val();
	  			var temp_html = "";
	  			var purchase_item_tbody = $("body").find(".purchase_item_tbody");
	  			var chosen_filter_dates = $("body").find(".chosen_filter_dates_purchase_item");
	  			var total_cost = 0;
		  		var total_qty = 0;

		  		if(from_date == "" && to_date == "" || from_date == "" || to_date == "")
		  		{
		  			from_date = 0;
		  			to_date = 0;
		  		}

		  		if(from_date != 0 && to_date != 0)
			  	{
			  		var new_viewed_date = "";
		  			var month_name = "";
		  			var separated_from = from_date.split(/\s*\-\s*/g);
		  			var separated_to = to_date.split(/\s*\-\s*/g);

		  			if(separated_from[1] == 1)
	  				{
	  					month_name = "January";
	  				}
	  				else if(separated_from[1] == 2)
	  				{
	  					month_name = "February";
	  				}
	  				else if(separated_from[1] == 3)
	  				{
	  					month_name = "March";
	  				}
	  				else if(separated_from[1] == 4)
	  				{
	  					month_name = "April";
	  				}
	  				else if(separated_from[1] == 5)
	  				{
	  					month_name = "May";
	  				}
	  				else if(separated_from[1] == 6)
	  				{
	  					month_name = "June";
	  				}
	  				else if(separated_from[1] == 7)
	  				{
	  					month_name = "July";
	  				}
	  				else if(separated_from[1] == 8)
	  				{
	  					month_name = "August";
	  				}
	  				else if(separated_from[1] == 9)
	  				{
	  					month_name = "September";
	  				}
	  				else if(separated_from[1] == 10)
	  				{
	  					month_name = "October";
	  				}
	  				else if(separated_from[1] == 11)
	  				{
	  					month_name = "November";
	  				}
	  				else if(separated_from[1] == 12)
	  				{
	  					month_name = "December";
	  				}

	  				if(separated_to[1] == 1)
	  				{
	  					month_name_to = "January";
	  				}
	  				else if(separated_to[1] == 2)
	  				{
	  					month_name_to = "February";
	  				}
	  				else if(separated_to[1] == 3)
	  				{
	  					month_name_to = "March";
	  				}
	  				else if(separated_to[1] == 4)
	  				{
	  					month_name_to = "April";
	  				}
	  				else if(separated_to[1] == 5)
	  				{
	  					month_name_to = "May";
	  				}
	  				else if(separated_to[1] == 6)
	  				{
	  					month_name_to = "June";
	  				}
	  				else if(separated_to[1] == 7)
	  				{
	  					month_name_to = "July";
	  				}
	  				else if(separated_to[1] == 8)
	  				{
	  					month_name_to = "August";
	  				}
	  				else if(separated_to[1] == 9)
	  				{
	  					month_name_to = "September";
	  				}
	  				else if(separated_to[1] == 10)
	  				{
	  					month_name_to = "October";
	  				}
	  				else if(separated_to[1] == 11)
	  				{
	  					month_name_to = "November";
	  				}
	  				else if(separated_to[1] == 12)
	  				{
	  					month_name_to = "December";
	  				}

		  			if((separated_from[0] == separated_to[0]) && (separated_from[1] == separated_to[1]) && (separated_from[2] == separated_to[2]))
		  			{
		  				new_viewed_date = month_name+" "+separated_from[2]+", "+separated_from[0];
		  			}
		  			else if((separated_from[0] == separated_to[0]) && (separated_from[1] == separated_to[1]) && (separated_from[2] != separated_to[2]))
		  			{
		  				new_viewed_date = month_name+" "+separated_from[2]+" - "+separated_to[2]+", "+separated_from[0];
		  			}
		  			else
		  			{
		  				new_viewed_date = month_name+" "+separated_from[2]+", "+separated_from[0]+" - "+month_name_to+" "+separated_to[2]+", "+separated_to[0];
		  			}
		  			chosen_filter_dates.html(new_viewed_date);
			  	}

			  	purchase_item_tbody.html("<tr><td colspan='8' style='text-align:center;font-size:15px !important;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
			  	$.post(base_url+"inventory/filter_purchase_item/"+ sort_by_item +"/"+ from_date +"/"+ to_date +"/",'', function(response){
	                var obj = $.parseJSON(response);

	                var item_cost = 0;
	                var saved_combination = new Array();
	                var current_combination = "";
	                var returned_data = "";
					for(var val in obj.all_inventory_item)
	  				{
	  					current_combination = obj.all_inventory_item[val].purchase_order_no+'-'+obj.all_inventory_item[val].item_id;
	  					returned_data = inArray(current_combination,saved_combination);
	  					if(returned_data == false)
	  					{
		        			saved_combination.push(current_combination);

		  					item_cost = Number(obj.all_inventory_item[val].item_total_cost);
		  					total_cost += Number(obj.all_inventory_item[val].item_total_cost);
		  					total_qty += Number(obj.all_inventory_item[val].item_quantity);
		    				var new_date_format = obj.all_inventory_item[val].order_req_date.split("-");
		  					temp_html += '<tr>'+
		  									'<td class="purchase_item_td" style="height:30px !important;">'+new_date_format[1]+'/'+new_date_format[0]+'/'+new_date_format[2]+'</td>'+
		  									'<td class="purchase_item_td" style="height:30px !important;"> <span class="view_purchase_order" data-purchase-order-no="'+obj.all_inventory_item[val].purchase_order_no+'" data-order-req-id="'+obj.all_inventory_item[val].order_req_id+'">'+obj.all_inventory_item[val].purchase_order_no.substr(3, 10)+'</span></td>'+
		  									'<td class="purchase_item_td" style="height:30px !important;">'+obj.all_inventory_item[val].company_item_no+'</td>'+
		  									'<td class="purchase_item_td" style="height:30px !important;">'+obj.all_inventory_item[val].item_description+'</td>'+
		  									'<td class="purchase_item_td" style="height:30px !important;">'+obj.all_inventory_item[val].vendor_name+'</td>'+
		  									'<td class="purchase_item_td" style="height:30px !important;">'+obj.all_inventory_item[val].item_unit_measure+'</td>'+
		  									'<td class="purchase_item_td" style="height:30px !important;">'+obj.all_inventory_item[val].item_quantity+'</td>'+
		  									'<td class="purchase_item_td" style="height:30px !important;">'+item_cost.toFixed(2)+'</td>'+
		                                '</tr>';
		               	}
	  				}
	  				if(temp_html == "")
	  				{
	  					temp_html += '<tr>'+
	  									'<td colspan="7" style="text-align:center;font-size: 17px;height: 30px;padding-top: 12px !important;"> No data. </td>'+
	  								'</tr>';
	  					$("body").find(".purchase_item_total_tr").css("display",'none');
	  				}
	  				else
	  				{
	  					$("body").find(".purchase_item_total_tr").css("display",'table-row');
	  					$("body").find(".purchase_item_total_tr").find(".purchase_item_total_qty").text(total_qty);
  						$("body").find(".purchase_item_total_tr").find(".purchase_item_total_cost").text(total_cost.toFixed(2));
	  				}
	  				purchase_item_tbody.html(temp_html);
	            });
         	}
        });

        //sort purchase item report by item description or item no.
	  	$('body').on('change','.filter_purchase_item',function(){
	  		var _value = $(this).val();
	  		var from_date = $("body").find("#search_from_purchase_item").val();
	  		var to_date = $("body").find("#search_to_purchase_item").val();
	  		var temp_html = "";
	  		var purchase_item_tbody = $("body").find(".purchase_item_tbody");
	  		var total_cost = 0;
	  		var total_qty = 0;
	  		var item_name_header = "";

	  		if(from_date == "" && to_date == "" || from_date == "" || to_date == "")
	  		{
	  			from_date = 0;
	  			to_date = 0;
	  		}

	  		purchase_item_tbody.html("<tr><td colspan='8' style='text-align:center;font-size:15px !important;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
	  		$.post(base_url+"inventory/filter_purchase_item/"+ _value +"/"+ from_date +"/"+ to_date +"/",'', function(response){
                var obj = $.parseJSON(response);

                var item_cost = 0;
                var saved_combination = new Array();
                var current_combination = "";
                var returned_data = "";
				for(var val in obj.all_inventory_item)
  				{
  					current_combination = obj.all_inventory_item[val].purchase_order_no+'-'+obj.all_inventory_item[val].item_id;
  					returned_data = inArray(current_combination,saved_combination);
  					if(returned_data == false)
  					{
	        			saved_combination.push(current_combination);

	  					item_cost = Number(obj.all_inventory_item[val].item_total_cost);
	  					total_cost += Number(obj.all_inventory_item[val].item_total_cost);
	  					total_qty += Number(obj.all_inventory_item[val].item_quantity);
	    				var new_date_format = obj.all_inventory_item[val].order_req_date.split("-");
	  					temp_html += '<tr>'+
	  									'<td class="purchase_item_td" style="height:30px !important;">'+new_date_format[1]+'/'+new_date_format[0]+'/'+new_date_format[2]+'</td>'+
	  									'<td class="purchase_item_td" style="height:30px !important;"> <span class="view_purchase_order" data-purchase-order-no="'+obj.all_inventory_item[val].purchase_order_no+'" data-order-req-id="'+obj.all_inventory_item[val].order_req_id+'">'+obj.all_inventory_item[val].purchase_order_no.substr(3, 10)+'</span></td>'+
	  									'<td class="purchase_item_td" style="height:30px !important;">'+obj.all_inventory_item[val].company_item_no+'</td>'+
	  									'<td class="purchase_item_td" style="height:30px !important;">'+obj.all_inventory_item[val].item_description+'</td>'+
	  									'<td class="purchase_item_td" style="height:30px !important;">'+obj.all_inventory_item[val].vendor_name+'</td>'+
	  									'<td class="purchase_item_td" style="height:30px !important;">'+obj.all_inventory_item[val].item_unit_measure+'</td>'+
	  									'<td class="purchase_item_td" style="height:30px !important;">'+obj.all_inventory_item[val].item_quantity+'</td>'+
	  									'<td class="purchase_item_td" style="height:30px !important;">'+item_cost.toFixed(2)+'</td>'+
	                                '</tr>';
	               	}
  				}
  				if(temp_html == "")
  				{
  					temp_html += '<tr>'+
  									'<td colspan="7" style="text-align:center;font-size: 17px;height: 30px;padding-top: 12px !important;"> No data. </td>'+
  								'</tr>';
  					$("body").find(".purchase_item_total_tr").css("display",'none');
  				}
  				else
  				{
  					$("body").find(".purchase_item_total_tr").css("display",'table-row');
	              	$("body").find(".purchase_item_total_tr").find(".purchase_item_total_qty").text(total_qty);
	              	$("body").find(".purchase_item_total_tr").find(".purchase_item_total_cost").text(total_cost.toFixed(2));
  				}
  				purchase_item_tbody.html(temp_html);
  				item_name_header = obj.item_details.item_description;
  				if(item_name_header == undefined)
  				{
  					item_name_header = "All Item";
  				}
  				$("body").find(".chosen_filter_item").text(item_name_header);
            });
	  	});

        // View Order Requisition Details
		$('body').on('click','.view_purchase_order',function(){
			var _this = $(this);
			var purchase_order_no = $(this).attr("data-purchase-order-no");
			var order_req_id = $(this).attr("data-order-req-id");

			modalbox(base_url + 'inventory/purchase_order_requisition_details/'+ purchase_order_no + "/"+ order_req_id ,{
	            header:"Purchase Order Requisition",
	            button: false,
	        });
		});

		function inArray(needle, haystack) {
		    var length = haystack.length;
		    for(var i = 0; i < length; i++) {
		        if(haystack[i] == needle) return true;
		    }
		    return false;
		}

	});

</script>