<style type="text/css">

	.item_reconciliation_panel_header
	{
		height:40px;
	}

	.item_reconciliation_td
	{
		text-align:center !important;
		height: 30px !important;
	}

	.panel-body
	{
		padding-left: 0px!important;
		padding-right: 0px!important;
	}

</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  	<h1 class="m-n font-thin h3">Item Reconciliation Report</h1>
</div>

<div class="wrapper-md hidden-print">
  	<div class="panel panel-default">
    	<div class="panel-heading item_reconciliation_panel_header">
    	</div>
    	<div class="panel-body" style="overflow-x:auto;">
    		<div class="col-sm-12 col-md-12 " style="margin-top:15px;margin-bottom:15px;border-bottom:1px solid rgba(138, 136, 136, 0.12); height:50px;">
    			<div class="col-sm-4 col-md-4">

				</div>
				<div class="col-sm-1 col-md-1" style="margin-top:4px;padding-right: 0px;">
					From
				</div>
				<div class="col-sm-1 col-md-1" style="left: -15px !important;padding-right: 0px !important;padding-left: 0px;">
					<input type="text" class="form-control choose_date_item_reconciliation" id="search_from_item_reconciliation" aria-describedby="sizing-addon2" value="">
				</div>

				<div class="col-sm-1 col-md-1" style="margin-top:4px;text-align: left;">
					To
				</div>
				<div class="col-sm-1 col-md-1" style="padding-right: 0px !important;padding-left: 0px !important;left: -30px;">
					<input type="text" class="form-control choose_date_item_reconciliation" id="search_to_item_reconciliation" aria-describedby="sizing-addon3" value="">
				</div>
				<div class="col-sm-2 col-md-2" style="margin-top:4px;padding-right:30px;text-align:right;">
					Sort by
            	</div>
            	<div class="col-sm-2 col-md-2" style="padding-right:0px;padding-left:0px;margin-left:-15px;">
					<select class="form-control filter_item_reconciliation" name="filter_item_reconciliation" style="border: 0px;text-align-last:center;">
						<option value="all_item"> All Item </option>
						<option value="missing_item"> Missing Item </option>
						<option value="out_for_repair"> Out for Repair </option>
						<option value="thrown_out"> Thrown Out </option>
						<option value="transferred"> Transferred </option>
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
					<span> Item Reconciliation Report </span>
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
					<span class="chosen_filter_reason"> All Item </span>
				</div>
				<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" style="">
	    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

				</div>
				<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;">
					<span class="chosen_filter_dates">  </span>
				</div>
				<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:25px;margin-bottom:10px;padding:0px;">
				<?php
	    			if(!empty($removed_item_list))
	    			{
	    		?>
	    				<table class="table m-b-none datatable_table_item_reconciliation" style="min-width: 900px !important;">
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
				            <th style="width:13%;text-align:center;"> Item No.</th>
				            <th style="width:15%;text-align:center;"> Item Description</th>
				            <th style="width:13%;text-align:center;"> Vendor</th>
				            <th style="width:12%;text-align:center;"> Item Rec. Reason</th>
				            <th style="width:14%;text-align:center;"> Serial No.</th>
				            <th style="width:14%;text-align:center;"> Asset No. </th>
				            <th style="width:9%;text-align:center;"> Cost </th>
			          	</tr>
			        </thead>
			        <tbody class="item_reconciliation_tbody">

				        <?php
			        		if(!empty($removed_item_list))
				        	{
				        		$total_cost = 0;
				        		foreach ($removed_item_list as $key => $value)
					        	{
			        	?>
			        				<tr style="height:43px;">
				        				<td class="item_reconciliation_td">
						        			<?php echo date("m/d/Y", strtotime($value['date_out_of_service'])); ?>
						        		</td>
						        		<td class="item_reconciliation_td">
						        			<?php echo $value['company_item_no']; ?>
						        		</td>
						        		<td class="item_reconciliation_td">
						        			<?php echo $value['item_description']; ?>
						        		</td>
						        		<td class="item_reconciliation_td">
						        			<?php echo $value['vendor_name']; ?>
						        		</td>
						        		<td class="item_reconciliation_td">
						        			<?php
					        				if($value['removal_reason'] == "missing_item")
					        				{
					        					echo "Missing Item";
					        				}
					        				else if($value['removal_reason'] == "out_for_repair")
					        				{
					        					echo "Out for Repair";
					        				}
					        				else if ($value['removal_reason'] == "thrown_out")
					        				{
					        					echo "Thrown Out";
					        				} else {
					        					echo "Transferred";
					        				}
					        			?>
						        		</td>
						        		<td class="item_reconciliation_td">
						        			<?php echo $value['item_serial_no']; ?>
						        		</td>
						        		<td class="item_reconciliation_td">
						        			<?php echo $value['item_asset_no']; ?>
						        		</td>
						        		<td class="item_reconciliation_td">
						        			<?php
						        				if($value['removal_reason'] == "missing_item")
						        				{
						        					echo number_format($value['item_cost'],2);
						        					$total_cost += $value['item_cost'];
						        				}
						        				else if($value['removal_reason'] == "out_for_repair")
						        				{
						        					echo number_format(0,2);
						        				}
						        				else if ($value['removal_reason'] == "thrown_out")
						        				{
						        					echo number_format($value['item_cost'],2);
						        					$total_cost += $value['item_cost'];
						        				} else {
						        					echo number_format(0,2);
						        				}

						        			?>
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
			        <?php
		        		if(!empty($removed_item_list))
		        		{
		        	?>
		        			<tr class="item_reconciliation_total_tr" style="font-size:15px !important;">
		        				<td class="item_reconciliation_td"> </td>
		        				<td class="item_reconciliation_td"> </td>
		        				<td class="item_reconciliation_td"> </td>
		        				<td class="item_reconciliation_td"> </td>
		        				<td class="item_reconciliation_td"> </td>
		        				<td class="item_reconciliation_td"> </td>
		        				<td class="item_reconciliation_td"> </td>
		        				<td class="item_reconciliation_td item_reconciliation_total_cost"> <?php echo number_format($total_cost,2); ?> </td>
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

		$('.datatable_table_item_reconciliation').DataTable({
			"order": [[ 0, "desc" ]]
		});

		//sort items by reason
	  	$('body').on('change','.filter_item_reconciliation',function(){
	  		var _value = $(this).val();
	  		var from_date = $("body").find("#search_from_item_reconciliation").val();
	  		var to_date = $("body").find("#search_to_item_reconciliation").val();
	  		var temp_html = "";
	  		var removal_reason = "";
	  		var item_reconciliation_tbody = $("body").find(".item_reconciliation_tbody");
	  		var total_cost = 0;
	  		var removal_reason_header = "";

	  		if(from_date == "" && to_date == "" || from_date == "" || to_date == "")
	  		{
	  			from_date = 0;
	  			to_date = 0;
	  		}

	  		if(_value == "missing_item")
			{
				removal_reason_header = "Missing Item";
			}
			else if(_value == "out_for_repair")
			{
				removal_reason_header = "Out for Repair";
			}
			else if(_value == "thrown_out")
			{
				removal_reason_header = "Thrown Out";
			}
			else if (_value == "transferred")
			{
				removal_reason_header = "Transferred";
			}
			else
			{
				removal_reason_header = "All Item";
			}
	  		$("body").find(".chosen_filter_reason").html(removal_reason_header);

	  		item_reconciliation_tbody.html("<tr><td colspan='8' style='text-align:center;font-size:15px !important;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
        	$.post(base_url+"inventory/filter_item_reconciliation/"+ _value +"/"+ from_date +"/"+ to_date +"/",'', function(response){
                var obj = $.parseJSON(response);

				for(var val in obj.removed_item_list)
  				{
  					var item_cost = "";
  					if(obj.removed_item_list[val].removal_reason == "missing_item")
    				{
    					removal_reason = "Missing Item";
    					total_cost += Number(obj.removed_item_list[val].item_cost);
    					item_cost = Number(obj.removed_item_list[val].item_cost);
    				}
    				else if(obj.removed_item_list[val].removal_reason == "out_for_repair")
    				{
    					removal_reason = "Out for Repair";
    					item_cost = 0;
    				}
    				else if(obj.removed_item_list[val].removal_reason == "thrown_out")
    				{
    					removal_reason = "Thrown Out";
    					total_cost += Number(obj.removed_item_list[val].item_cost);
    					item_cost = Number(obj.removed_item_list[val].item_cost);
    				}
    				else if (obj.removed_item_list[val].removal_reason == "transferred")
    				{
    					removal_reason = "Transferred";
    					item_cost = 0;
    				}
    				var new_date_format = obj.removed_item_list[val].date_out_of_service.split("-");

  					temp_html += '<tr>'+
  									'<td class="item_reconciliation_td" style="height:30px !important;">'+new_date_format[1]+'/'+new_date_format[0]+'/'+new_date_format[2]+'</td>'+
  									'<td class="item_reconciliation_td" style="height:30px !important;">'+obj.removed_item_list[val].company_item_no+'</td>'+
  									'<td class="item_reconciliation_td" style="height:30px !important;">'+obj.removed_item_list[val].item_description+'</td>'+
  									'<td class="item_reconciliation_td" style="height:30px !important;">'+obj.removed_item_list[val].vendor_name+'</td>'+
  									'<td class="item_reconciliation_td" style="height:30px !important;">'+removal_reason+'</td>'+
  									'<td class="item_reconciliation_td" style="height:30px !important;">'+obj.removed_item_list[val].item_serial_no+'</td>'+
  									'<td class="item_reconciliation_td" style="height:30px !important;">'+obj.removed_item_list[val].item_asset_no+'</td>'+
  									'<td class="item_reconciliation_td" style="height:30px !important;">'+item_cost.toFixed(2)+'</td>'+
                                '</tr>';

  				}
  				if(temp_html == "")
  				{
  					temp_html += '<tr>'+
  									'<td colspan="8" style="text-align:center;font-size: 17px;height: 30px;padding-top: 12px !important;"> No data. </td>'+
  								'</tr>';

  					$("body").find(".item_reconciliation_total_tr").css("display",'none');
  				}
  				else
  				{
  					$("body").find(".item_reconciliation_total_tr").css("display",'table-row');
  					$("body").find(".item_reconciliation_total_tr").find(".item_reconciliation_total_cost").text(total_cost.toFixed(2));
  				}
  				item_reconciliation_tbody.html(temp_html);
            });
	  	});

	  	$('.choose_date_item_reconciliation').datepicker({
	    	dateFormat: 'yy-mm-dd',
         	onClose: function (dateText, inst) {
         		var from_date = $("body").find("#search_from_item_reconciliation").val();
	  			var to_date = $("body").find("#search_to_item_reconciliation").val();
	  			var sort_by_reason = $("body").find(".filter_item_reconciliation").val();
	  			var temp_html = "";
		  		var removal_reason = "";
		  		var item_reconciliation_tbody = $("body").find(".item_reconciliation_tbody");
		  		var chosen_filter_dates = $("body").find(".chosen_filter_dates");
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

		  		item_reconciliation_tbody.html("<tr><td colspan='8' style='text-align:center;font-size:15px !important;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
		  		$.post(base_url+"inventory/filter_item_reconciliation/"+ sort_by_reason +"/"+ from_date +"/"+ to_date +"/",'', function(response){
	                var obj = $.parseJSON(response);

					for(var val in obj.removed_item_list)
	  				{
	  					var item_cost = "";
	  					if(obj.removed_item_list[val].removal_reason == "missing_item")
	    				{
	    					removal_reason = "Missing Item";
	    					total_cost += Number(obj.removed_item_list[val].item_cost);
	    					item_cost = Number(obj.removed_item_list[val].item_cost);
	    				}
	    				else if(obj.removed_item_list[val].removal_reason == "out_for_repair")
	    				{
	    					removal_reason = "Out for Repair";
	    					item_cost = 0;
	    				}
	    				else if(obj.removed_item_list[val].removal_reason == "thrown_out")
	    				{
	    					removal_reason = "Thrown Out";
	    					total_cost += Number(obj.removed_item_list[val].item_cost);
	    					item_cost = Number(obj.removed_item_list[val].item_cost);
	    				} else if(obj.removed_item_list[val].removal_reason == "transferred"){
	    					removal_reason = "Transferred";
	    					item_cost = 0;
	    				}
	    				var new_date_format = obj.removed_item_list[val].date_out_of_service.split("-");

	  					temp_html += '<tr>'+
	  									'<td class="item_reconciliation_td" style="height:30px !important;">'+new_date_format[1]+'/'+new_date_format[0]+'/'+new_date_format[2]+'</td>'+
	  									'<td class="item_reconciliation_td" style="height:30px !important;">'+obj.removed_item_list[val].company_item_no+'</td>'+
	  									'<td class="item_reconciliation_td" style="height:30px !important;">'+obj.removed_item_list[val].item_description+'</td>'+
	  									'<td class="item_reconciliation_td" style="height:30px !important;">'+obj.removed_item_list[val].vendor_name+'</td>'+
	  									'<td class="item_reconciliation_td" style="height:30px !important;">'+removal_reason+'</td>'+
	  									'<td class="item_reconciliation_td" style="height:30px !important;">'+obj.removed_item_list[val].item_serial_no+'</td>'+
	  									'<td class="item_reconciliation_td" style="height:30px !important;">'+obj.removed_item_list[val].item_asset_no+'</td>'+
	  									'<td class="item_reconciliation_td" style="height:30px !important;">'+item_cost.toFixed(2)+'</td>'+
	                                '</tr>';

	  				}
	  				if(temp_html == "")
	  				{
	  					temp_html += '<tr>'+
	  									'<td colspan="8" style="text-align:center;font-size: 17px;height: 30px;padding-top: 12px !important;"> No data. </td>'+
	  								'</tr>';
	  					$("body").find(".item_reconciliation_total_tr").css("display",'none');
	  				}
	  				else
	  				{
	  					$("body").find(".item_reconciliation_total_tr").css("display",'table-row');
  						$("body").find(".item_reconciliation_total_tr").find(".item_reconciliation_total_cost").text(total_cost.toFixed(2));
	  				}
	  				item_reconciliation_tbody.html(temp_html);
	            });
         	}
        });

	});

</script>

