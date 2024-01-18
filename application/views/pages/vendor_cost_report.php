<style type="text/css">

	.vendor_cost_panel_header
	{
		height:40px;
	}

	.vendor_cost_td
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
  	<h1 class="m-n font-thin h3">Vendor Cost Report</h1>
</div>

<div class="wrapper-md hidden-print">
  	<div class="panel panel-default">
    	<div class="panel-heading vendor_cost_panel_header">
    	</div>
    	<div class="panel-body" style="overflow-x:auto;">
    		<div class="col-sm-12 col-md-12 " style="margin-top:15px;margin-bottom:15px;border-bottom:1px solid rgba(138, 136, 136, 0.12); height:50px;">
    			<div class="col-sm-4 col-md-4">

				</div>
				<div class="col-sm-1 col-md-1" style="margin-top:4px;padding-right: 0px;">
					From
				</div>
				<div class="col-sm-1 col-md-1" style="left: -15px !important;padding-right: 0px !important;padding-left: 0px;">
					<input type="text" class="form-control choose_date_vendor_cost" id="search_from_vendor_cost" aria-describedby="sizing-addon2" value="">
				</div>

				<div class="col-sm-1 col-md-1" style="margin-top:4px;text-align: left;">
					To
				</div>
				<div class="col-sm-1 col-md-1" style="padding-right: 0px !important;padding-left: 0px !important;left: -30px;">
					<input type="text" class="form-control choose_date_vendor_cost" id="search_to_vendor_cost" aria-describedby="sizing-addon3" value="">
				</div>
				<div class="col-sm-2 col-md-2" style="margin-top:4px;padding-right:30px;text-align:right;">
					Sort by
            	</div>
            	<div class="col-sm-2 col-md-2" style="padding-right:0px;padding-left:0px;margin-left:-15px;">
					<select class="form-control filter_vendor_cost" name="filter_vendor_cost" style="border: 0px;text-align-last:center;">
						<option value="all_vendors"> All Vendors </option>
		                <?php
                            foreach ($vendor_list as $vendor) :
                        ?>
		                      	<option value="<?php echo $vendor['vendor_id'] ?>" ><?php echo $vendor['vendor_name'] ?></option>
		                <?php
                            endforeach;
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
					<span> Vendor Cost Report </span>
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
					<span class="chosen_filter_vendor"> All Vendors </span>
				</div>
				<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" style="">
	    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

				</div>
				<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;">
					<span class="chosen_filter_dates_vendor_cost"> </span>
				</div>
				<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:25px;margin-bottom:10px;padding:0px;">
				<?php
                    if (!empty($order_requisition_list)) {
                        ?>
	    				<table class="table m-b-none datatable_table_vendor_cost" style="min-width: 900px !important;">
	    		<?php
                    } else {
                        ?>
	    				<table class="table m-b-none" style="min-width: 900px !important;">
	    		<?php
                    }
                ?>
	    			<thead>
			          	<tr style="height:43px;">
				            <th style="width:15%;text-align:center;"> Date </th>
				            <th style="width:20%;text-align:center;"> Purchase Order No.</th>
				            <th style="width:20%;text-align:center;"> Confirmation No.</th>
				            <th style="width:30%;text-align:center;"> Vendor Name</th>
				            <th style="width:15%;text-align:center;"> Total Cost</th>
			          	</tr>
			        </thead>
			        <tbody class="vendor_cost_tbody">
			        	<?php
                            if (!empty($order_requisition_list)) {
                                $total_cost = 0;
                                foreach ($order_requisition_list as $key => $value) {
                                    $total_cost += $value['order_req_grand_total']; ?>
			        				<tr style="height:43px;">
				        				<td class="vendor_cost_td">
						        			<?php echo date("m/d/Y", strtotime($value['order_req_date'])); ?>
						        		</td>
						        		<td class="vendor_cost_td">
						        			<span
						        				class="view_purchase_order"
						        				data-purchase-order-no="<?php echo $value['purchase_order_no']; ?>"
						        				data-order-req-id="<?php echo $value['order_req_id']; ?>"
							        		>
						        				<?php echo substr($value['purchase_order_no'], 3, 10); ?>
						        			</span>
						        		</td>
						        		<td class="vendor_cost_td">
						        			<?php echo $value['order_req_confirmation_no']; ?>
						        		</td>
						        		<td class="vendor_cost_td">
						        			<?php echo $value['vendor_name']; ?>
						        		</td>
						        		<td class="vendor_cost_td">
						        			<?php echo number_format($value['order_req_grand_total'], 2); ?>
						        		</td>
						        	</tr>
			        	<?php
                                }
                            } else {
                                ?>
			        			<tr>
					        		<td colspan="5" style="text-align:center;font-size: 17px;height: 50px;padding-top: 12px !important;"> No data. </td>
					        	</tr>
			        	<?php
                            }
                        ?>
			        </tbody>
			        <?php
                        if (!empty($order_requisition_list)) {
                            ?>
		        			<tr class="vendor_cost_total_tr" style="font-size:15px !important;">
		        				<td class="vendor_cost_td"> </td>
		        				<td class="vendor_cost_td"> </td>
		        				<td class="vendor_cost_td"> </td>
		        				<td class="vendor_cost_td"> </td>
		        				<td class="vendor_cost_td vendor_cost_total_cost"> <?php echo number_format($total_cost, 2); ?> </td>
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

		$('.datatable_table_vendor_cost').DataTable({
			"order": [[ 0, "desc" ]]
		});

        //sort vendor cost report by vendor
	  	$('body').on('change','.filter_vendor_cost',function(){
	  		var _value = $(this).val();
	  		var from_date = $("body").find("#search_from_vendor_cost").val();
	  		var to_date = $("body").find("#search_to_vendor_cost").val();
	  		var temp_html = "";
	  		var vendor_cost_tbody = $("body").find(".vendor_cost_tbody");
	  		var total_cost = 0;
	  		var vendor_name_header = "";

	  		if(from_date == "" && to_date == "" || from_date == "" || to_date == "")
	  		{
	  			from_date = 0;
	  			to_date = 0;
	  		}

	  		vendor_cost_tbody.html("<tr><td colspan='8' style='text-align:center;font-size:15px !important;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
	  		$.post(base_url+"inventory/filter_vendor_cost/"+ _value +"/"+ from_date +"/"+ to_date +"/",'', function(response){
                var obj = $.parseJSON(response);

                var item_cost = 0;
				for(var val in obj.order_requisition_list)
  				{
  					item_cost = Number(obj.order_requisition_list[val].order_req_grand_total);
  					total_cost += Number(obj.order_requisition_list[val].order_req_grand_total);
    				var new_date_format = obj.order_requisition_list[val].order_req_date.split("-");
  					temp_html += '<tr>'+
  									'<td class="vendor_cost_td" style="height:30px !important;">'+new_date_format[1]+'/'+new_date_format[0]+'/'+new_date_format[2]+'</td>'+
  									'<td class="vendor_cost_td" style="height:30px !important;"> <span class="view_purchase_order" data-purchase-order-no="'+obj.order_requisition_list[val].purchase_order_no+'" data-order-req-id="'+obj.order_requisition_list[val].order_req_id+'">'+obj.order_requisition_list[val].purchase_order_no.substr(3, 10)+'</span></td>'+
  									'<td class="vendor_cost_td" style="height:30px !important;">'+obj.order_requisition_list[val].order_req_confirmation_no+'</td>'+
  									'<td class="vendor_cost_td" style="height:30px !important;">'+obj.order_requisition_list[val].vendor_name+'</td>'+
  									'<td class="vendor_cost_td" style="height:30px !important;">'+item_cost.toFixed(2)+'</td>'+
                                '</tr>';

  				}
  				if(temp_html == "")
  				{
  					temp_html += '<tr>'+
  									'<td colspan="5" style="text-align:center;font-size: 17px;height: 30px;padding-top: 12px !important;"> No data. </td>'+
  								'</tr>';
  					$("body").find(".vendor_cost_total_tr").css("display",'none');
  				}
  				else
  				{
  					$("body").find(".vendor_cost_total_tr").css("display",'table-row');
              		$("body").find(".vendor_cost_total_tr").find(".vendor_cost_total_cost").text(total_cost.toFixed(2));
  				}
  				vendor_cost_tbody.html(temp_html);
  				vendor_name_header = obj.vendor_details.vendor_name;
  				if(vendor_name_header == undefined)
  				{
  					vendor_name_header = "All Vendors";
  				}
  				$("body").find(".chosen_filter_vendor").text(vendor_name_header);
            });
		});

	  	$('.choose_date_vendor_cost').datepicker({
	    	dateFormat: 'yy-mm-dd',
         	onClose: function (dateText, inst) {
         		var from_date = $("body").find("#search_from_vendor_cost").val();
	  			var to_date = $("body").find("#search_to_vendor_cost").val();
	  			var sort_by_vendor = $("body").find(".filter_vendor_cost").val();
	  			var temp_html = "";
	  			var vendor_cost_tbody = $("body").find(".vendor_cost_tbody");
		  		var chosen_filter_dates = $("body").find(".chosen_filter_dates_vendor_cost");
	  			var total_cost = 0;

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

			  	vendor_cost_tbody.html("<tr><td colspan='8' style='text-align:center;font-size:15px !important;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
			  	$.post(base_url+"inventory/filter_vendor_cost/"+ sort_by_vendor +"/"+ from_date +"/"+ to_date +"/",'', function(response){
	                var obj = $.parseJSON(response);

	                var item_cost = 0;
					for(var val in obj.order_requisition_list)
	  				{
	  					item_cost = Number(obj.order_requisition_list[val].order_req_grand_total);
	  					total_cost += Number(obj.order_requisition_list[val].order_req_grand_total);
	    				var new_date_format = obj.order_requisition_list[val].order_req_date.split("-");
	  					temp_html += '<tr>'+
	  									'<td class="vendor_cost_td" style="height:30px !important;">'+new_date_format[1]+'/'+new_date_format[0]+'/'+new_date_format[2]+'</td>'+
	  									'<td class="vendor_cost_td" style="height:30px !important;"> <span class="view_purchase_order" data-purchase-order-no="'+obj.order_requisition_list[val].purchase_order_no+'" data-order-req-id="'+obj.order_requisition_list[val].order_req_id+'">'+obj.order_requisition_list[val].purchase_order_no.substr(3, 10)+'</span></td>'+
	  									'<td class="vendor_cost_td" style="height:30px !important;">'+obj.order_requisition_list[val].order_req_confirmation_no+'</td>'+
	  									'<td class="vendor_cost_td" style="height:30px !important;">'+obj.order_requisition_list[val].vendor_name+'</td>'+
	  									'<td class="vendor_cost_td" style="height:30px !important;">'+item_cost.toFixed(2)+'</td>'+
	                                '</tr>';

	  				}
	  				if(temp_html == "")
	  				{
	  					temp_html += '<tr>'+
	  									'<td colspan="5" style="text-align:center;font-size: 17px;height: 30px;padding-top: 12px !important;"> No data. </td>'+
	  								'</tr>';
	  					$("body").find(".vendor_cost_total_tr").css("display",'none');
	  				}
	  				else
	  				{
	  					$("body").find(".vendor_cost_total_tr").css("display",'table-row');
                    	$("body").find(".vendor_cost_total_tr").find(".vendor_cost_total_cost").text(total_cost.toFixed(2));
	  				}
	  				vendor_cost_tbody.html(temp_html);
	            });
	  		}
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


	});

</script>

