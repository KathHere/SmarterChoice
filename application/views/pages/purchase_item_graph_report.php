<style type="text/css">

</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  	<h1 class="m-n font-thin h3">Purchase Item Graph</h1>
</div>

<div class="page whole-container" ng-controller="FlotChartDemoCtrl">
	<div class="wrapper-md">
				
	    <div class="panel-report panel panel-default">

	    	<div role="tabpanel" class="tab-container ng-isolate-scope" style="margin:3px !important;">
	            <!-- Nav tabs -->
	            <ul class="nav nav-tabs hidden-print" role="tablist" >

	              	<li id="per" role="presentation" class="active">
	                	<a href="#standard" aria-controls="profile" role="tab" data-toggle="tab">
	                  		<span style="font-size:14px;"> Standard </span>
	                	</a>
	              	</li>
	              	<li id="bm" role="presentation" >
	                	<a href="#comparison" class="ytd_comparison_nav_tabs" aria-controls="messages" role="tab" data-toggle="tab">
	                	  <span style="font-size:14px;"> YTD Comparison</span>
	                	</a>
	              	</li>
	            </ul>

	            <!-- Tab panes -->
	            <div class="tab-content">

	            	<div role="tabpanel" class="tab-pane active" id="standard">
	            		<div class="ng-scope">
						    <div class="panel-body" style="padding-left:0px;padding-right:0px;">

						    	<div class="col-xs-12 col-sm-12 col-md-12 hidden-print" style="margin-top:15px;margin-bottom:15px;border-bottom:1px solid rgba(138, 136, 136, 0.12); height:50px;">
						    		<div class="col-sm-4 col-md-4">
															
									</div>
									<div class="col-sm-1 col-md-1" style="margin-top:4px;padding-right: 0px;">
										From
									</div>
									<div class="col-sm-1 col-md-1" style="left: -15px !important;padding-right: 0px !important;padding-left: 0px;">
										<?php 
											$from_date = date('Y-m');
											$from_date = $from_date."-01";
										?>
										<input type="text" class="form-control choose_date_purchase_item_graph_standard" id="search_from_purchase_item_graph_standard" aria-describedby="sizing-addon2" value="<?php echo $from_date; ?>">
									</div>

									<div class="col-sm-1 col-md-1" style="margin-top:4px;text-align: left;">
										To
									</div>
									<div class="col-sm-1 col-md-1" style="padding-right: 0px !important;padding-left: 0px !important;left: -30px;">
										<?php 
											$to_date = date('Y-m-d');
										?>
										<input type="text" class="form-control choose_date_purchase_item_graph_standard" id="search_to_purchase_item_graph_standard" aria-describedby="sizing-addon3" value="<?php echo $to_date; ?>">
									</div>
									<div class="col-sm-2 col-md-2" style="margin-top:4px;padding-right:30px;text-align:right;">
										Sort Dates
				                	</div>
				                	<div class="col-sm-2 col-md-2" style="padding-right:0px;padding-left:0px;margin-left:-15px;">
				                		
										<select class="form-control filter_purchase_item_graph_sort_dates" id="choose_purchase_item_graph_sort_dates_standard" name="filter_purchase_item_graph_sort_dates" style="border: 0px;text-align-last:center;">
											<option value="0">[--Choose to Sort--]</option>
											<option value="1">Today</option>
											<option value="2">This Week</option>
											<option value="3">This Week to Date</option>
											<option value="4">This Month</option>
											<option value="5" selected>This Month to Date</option>
											<option value="6">This Fiscal Quarter</option>
											<option value="7">This Fiscal Quarter to Date</option>
											<option value="8">This Fiscal Year</option>
											<option value="9">This Fiscal Year to Date</option>
											<option value="10">Yesterday</option>
											<option value="11">Last Week</option>
											<option value="12">Last Week to Date</option>
											<option value="13">Last Month</option>
											<option value="14">Last Month to Date</option>
											<option value="15">Last Fiscal Quarter</option>
											<option value="16">Last Fiscal Quarter to Date</option>
											<option value="17">Last Fiscal Year</option>
											<option value="18">Last Fiscal Year to Date</option>
										</select>
										
				                	</div>
				                </div>
				                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:5px;">
						    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:14px;">
										<?php 
											echo date('h:i A');
										?>
									</div>
									<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;">
										<select class="form-control pull-center vendor_filter_purchase_item_graph_standard hidden-print" id="purchase_item_graph_select_vendor_standard" name="vendor_filter_purchase_item_graph_standard" style="margin-top: 6px;border: 0px;font-size: 16px;font-weight: 600;text-align-last:center;">
											<option value="all_vendors"> All Vendors </option>
							                <?php 
							                	foreach($vendor_list as $vendor) :
							                ?>
							                      	<option value="<?php echo $vendor['vendor_id'] ?>" ><?php echo $vendor['vendor_name'] ?></option>
							                <?php 
							                	endforeach;
							               	?>
										</select>										
									</div>
									<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 sort_dates_print_value_div" style="text-align:right;">
									</div>
						    	</div>
						    	<div class="col-xs-12 col-sm-12 col-md-12 purchase_item_graph_labels_div" style="margin-top:11px;">
						    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:-15px;">
										<?php 
											$current_date = date('Y-m-d');
											echo date("m/d/Y", strtotime($current_date));
										?>
									</div>
									<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;margin-top:0px;">
							    		Purchase Item Standard
								    </div>
									<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

									</div>
						    	</div>
							    <div class="col-xs-12 col-sm-12 col-md-12 viewed_current_date_purchase_item_graph_div_standard " style="text-align:center;margin-top:5px;">
									<span id="viewed_current_date_purchase_item_graph_standard">
									<?php
										$first_initial_date = date('Y-m'); 
										$first_final_date = $first_initial_date."-01";
										$second_date = date('Y-m-d');
										if($first_final_date == $second_date)
										{
											echo date("F d, Y", strtotime($first_final_date));
										}
										else
										{
											echo date("F", strtotime($current_date))." 01 - ".date("d, Y", strtotime($current_date));
										}
									?>
									</span>
						    	</div>
						    	<div class="col-xs-12 col-sm-12 col-md-12 purchase_item_graph_bar_charts_div_standard" style="margin-top:40px;margin-bottom:60px;text-align:center;padding-left:0px;padding-right:0px;">
						    		<div id="purchase_item_graph_div" style="width:1150px !important;margin:auto;height:420px;"></div>
						    		<div id="purchase_item_graph_loader_div" style="display:none;"><h1 class='text-center loader text-success'><i class='fa fa-spin fa-spinner'></i></h1></div>
						    		<div id="no_items_div" style="display:none;"> <h4>No items to be displayed.</h4> </div>
						    	</div>

						    </div>	
						</div>
	            	</div>

	            	<div role="tabpanel" class="tab-pane" id="comparison">
	            		<div class="ng-scope">
						    <div class="panel-body" style="padding-left:0px;padding-right:0px;">

						    	<div class="col-xs-12 col-sm-12 col-md-12 hidden-print" style="margin-top:15px;margin-bottom:15px;border-bottom:1px solid rgba(138, 136, 136, 0.12); height:50px;">
						    		<div class="col-sm-4 col-md-4">
															
									</div>
									<div class="col-sm-1 col-md-1" style="margin-top:4px;padding-right: 0px;">
										From
									</div>
									<div class="col-sm-1 col-md-1" style="left: -15px !important;padding-right: 0px !important;padding-left: 0px;">
										<?php 
											$from_date = date('Y-m');
											$from_date = $from_date."-01";
										?>
										<input type="text" class="form-control choose_date_purchase_item_graph_comparison" id="search_from_purchase_item_graph_comparison" aria-describedby="sizing-addon2" value="<?php echo $from_date; ?>">
									</div>

									<div class="col-sm-1 col-md-1" style="margin-top:4px;text-align: left;">
										To
									</div>
									<div class="col-sm-1 col-md-1" style="padding-right: 0px !important;padding-left: 0px !important;left: -30px;">
										<?php 
											$to_date = date('Y-m-d');
										?>
										<input type="text" class="form-control choose_date_purchase_item_graph_comparison" id="search_to_purchase_item_graph_comparison" aria-describedby="sizing-addon3" value="<?php echo $to_date; ?>">
									</div>
									<div class="col-sm-2 col-md-2" style="margin-top:4px;padding-right:30px;text-align:right;">
										Sort Dates
				                	</div>
				                	<div class="col-sm-2 col-md-2" style="padding-right:0px;padding-left:0px;margin-left:-15px;">
				                		
										<select class="form-control filter_purchase_item_graph_sort_dates" id="choose_purchase_item_graph_sort_dates_comparison" name="filter_purchase_item_graph_sort_dates" style="border: 0px;text-align-last:center;">
											<option value="0">[--Choose to Sort--]</option>
											<option value="1">Today</option>
											<option value="2">This Week</option>
											<option value="3">This Week to Date</option>
											<option value="4">This Month</option>
											<option value="5" selected>This Month to Date</option>
											<option value="6">This Fiscal Quarter</option>
											<option value="7">This Fiscal Quarter to Date</option>
											<option value="8">This Fiscal Year</option>
											<option value="9">This Fiscal Year to Date</option>
											<option value="10">Yesterday</option>
											<option value="11">Last Week</option>
											<option value="12">Last Week to Date</option>
											<option value="13">Last Month</option>
											<option value="14">Last Month to Date</option>
											<option value="15">Last Fiscal Quarter</option>
											<option value="16">Last Fiscal Quarter to Date</option>
											<option value="17">Last Fiscal Year</option>
											<option value="18">Last Fiscal Year to Date</option>
										</select>
										
				                	</div>
				                </div>
				                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:5px;">
						    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:14px;">
										<?php 
											echo date('h:i A');
										?>
									</div>
									<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;">
										<select class="form-control pull-center vendor_filter_purchase_item_graph_comparison hidden-print" id="purchase_item_graph_select_vendor_comparison" name="vendor_filter_purchase_item_graph_comparison" style="margin-top: 6px;border: 0px;font-size: 16px;font-weight: 600;text-align-last:center;">
											<option value="all_vendors"> All Vendors </option>
							                <?php 
							                	foreach($vendor_list as $vendor) :
							                ?>
							                      	<option value="<?php echo $vendor['vendor_id'] ?>" ><?php echo $vendor['vendor_name'] ?></option>
							                <?php 
							                	endforeach;
							               	?>
										</select>										
									</div>
									<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 sort_dates_print_value_div" style="text-align:right;">
									</div>
						    	</div>
						    	<div class="col-xs-12 col-sm-12 col-md-12 purchase_item_graph_labels_div" style="margin-top:11px;">
						    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:-15px;">
										<?php 
											$current_date = date('Y-m-d');
											echo date("m/d/Y", strtotime($current_date));
										?>
									</div>
									<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;margin-top:0px;">
							    		Purchase Item YTD Comparison
								    </div>
									<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

									</div>
						    	</div>
						    	<div class="col-xs-12 col-sm-12 col-md-12 viewed_current_date_purchase_item_graph_div_comparison " style="text-align:center;margin-top:5px;">
									<span id="viewed_current_date_purchase_item_graph_comparison">
									<?php
										$first_initial_date = date('Y-m'); 
										$first_final_date = $first_initial_date."-01";
										$second_date = date('Y-m-d');
										if($first_final_date == $second_date)
										{
											echo date("F d, Y", strtotime($first_final_date));
										}
										else
										{
											echo date("F", strtotime($current_date))." 01 - ".date("d, Y", strtotime($current_date));
										}
									?>
									</span>
						    	</div>
						    	<div class="col-xs-12 col-sm-12 col-md-12 viewed_accrual_basis_dates_div_comparison" style="text-align:center;margin-top:5px;">
						    		<?php 
										$first_initial_date = date('Y-m'); 
										$first_final_date = $first_initial_date."-01";
										$second_date = date('Y-m-d');
										if($first_final_date == $second_date)
										{
											echo "* ".date("F d, Y", strtotime($first_final_date))." * ";
										}
										else
										{
											echo "* ".date("F", strtotime($current_date))." 01 - ".date("d, Y", strtotime($current_date))." * ";
										}

										$current_month = date('m');
										$current_year = date('Y');
							            $from_month = $current_month;
							            $from_year = $current_year;
										if($current_month == 12)
							            {
							                $from_month = 01;
							            }
							            else
							            {
							                $from_month += 1;
							                $from_year -= 1;
							            }
							            if($from_month > 9)
							            {
							                $ytd_from_date = $from_year."-".$from_month."-01";
							            }
							            else
							            {
							                $ytd_from_date = $from_year."-0".$from_month."-01";
							            }
							            $ytd_to_date = date('F d, Y');

							            echo date("F d, Y", strtotime($ytd_from_date))." - ".$ytd_to_date." *";
									?>
						    	</div>
						    	<div class="col-xs-12 col-sm-12 col-md-12 purchase_item_graph_bar_charts_div_comparison" style="margin-top:40px;margin-bottom:60px;text-align:center;padding-left:0px;padding-right:0px;">
						    		<div id="purchase_item_graph_div_comparison" style="width:1150px !important;margin:auto;height:420px;"> 
						    			<div class='pull-left' style='margin-top:280px;'> 
						    				<p>
						    					<img style="width:13px !important;" src="<?php echo base_url('assets/img/legend1.png') ;?>" alt="" />
						    					<span>From</span> 
						    				</p> 
						    				<p>
						    					<img class="pull-left" style="width:13px !important;margin-top:4px;margin-right:3px;" src="<?php echo base_url('assets/img/legend2.png') ;?>" alt="" />
						    					<span class="pull-left" >To</span> 
						    				</p> 
						    			</div>
						    		</div>
						    		<div id="purchase_item_graph_loader_div_comparison" style="display:none;"><h1 class='text-center loader text-success'><i class='fa fa-spin fa-spinner'></i></h1></div>
						    		<div id="no_items_div_comparison" style="display:none;"> <h4>No items to be displayed.</h4> </div>
						    	</div>

						    </div>
						</div>
	            	</div>

	            </div>	
	            <input type="hidden" class="ytd_comparison_opened_sign" value="0">
			</div>
		</div>	 
	</div>
</div>

<script type="text/javascript"> 

	$(document).ready(function(){

		$.getJSON('https://ipinfo.io/json', function(response) {
			var location = response.city+", "+response.region;

		  	$("body").find(".location_info").html(location);
		});

		var purchase_item_graph_chart = new Morris.Bar({
								barGap:4,
					  			barSizeRatio:0.30,
							    element: 'purchase_item_graph_div',
							    // data: [{'label': "", 'value': 0}],
							    hideHover: 'true',
							    resize: true,
							    xLabelAngle: 89.5,
							  	xkey: 'label',
							  	ykeys: ['value'],
							  	labels: ['Value']
		});

		$("body").find("svg").css('width','100%');
		$("body").find("svg").css('overflow','visible');
		$("body").find("svg").find("path").css('stroke','#000');
		$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');

		function get_purchase_item_graph_default()
		{
			$.post(base_url+"inventory/get_purchase_item_graph_today_default_standard/","", function(response){
				if(response.data.graph.length > 0)
				{
					purchase_item_graph_chart.setData(response.data.graph);
					$("body").find("svg").find("path").css('stroke','#000');
					$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
					$("body").find(".purchase_item_graph_bar_charts_div_standard").css('margin-bottom','190px');
				}
				else
				{
					$("body").find("#purchase_item_graph_div").css("display","none");
		  			$("body").find("#no_items_div").css("display","block");
		  			$("body").find("#purchase_item_graph_loader_div").css("display","none");
				}
			});
		}
		get_purchase_item_graph_default();

		$('body').on('click','.ytd_comparison_nav_tabs',function(){
			var ytd_comparison_opened_sign = $("body").find(".ytd_comparison_opened_sign").val();

			if(ytd_comparison_opened_sign == 0)
			{
				var purchase_item_graph_chart_comparison = new Morris.Bar({
											barGap:2,
								  			barSizeRatio:0.45,
										    element: 'purchase_item_graph_div_comparison',
										    hideHover: 'always',
										    resize: true,
										    xLabelAngle: 89.5,
										  	xkey: 'label',
										  	ykeys: ['value','second_value'],
										  	labels: ['First Value','Second Value'],
										  	barColors: ['#0B62A4','#F09F19']
				});
				$("body").find("svg").css('width','100%');
				$("body").find("svg").css('overflow','visible');

				$.post(base_url+"inventory/get_purchase_item_graph_today_default_comparison/","", function(response){
					if(response.data.graph.length > 0)
					{
						purchase_item_graph_chart_comparison.setData(response.data.graph);
						$("body").find("svg").find("path").css('stroke','#000');
						$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
						$("body").find(".purchase_item_graph_bar_charts_div_comparison").css('margin-bottom','-10px');
					}
					else
					{
						$("body").find("#purchase_item_graph_div_comparison").css("display","none");
			  			$("body").find("#no_items_div_comparison").css("display","block");
			  			$("body").find("#purchase_item_graph_loader_div_comparison").css("display","none");
			  			$("body").find(".purchase_item_graph_bar_charts_div_comparison").css('margin-bottom','50px');
					}
				});
				$("body").find(".ytd_comparison_opened_sign").val(1);
			}
		});

		// Filter Type is 1. Select dates. Date filter. Comparison
		$('.choose_date_purchase_item_graph_comparison').datepicker({
	    	dateFormat: 'yy-mm-dd',
         	onClose: function (dateText, inst) {
	  			var filter_from = $("body").find("#search_from_purchase_item_graph_comparison").val();
	  			var filter_to = $("body").find("#search_to_purchase_item_graph_comparison").val();
	  			var vendor_id = $("body").find(".vendor_filter_purchase_item_graph_comparison").val();
	  			var viewed_current_date = $("body").find("#viewed_current_date_purchase_item_graph_comparison");
	  			var viewed_accrual_basis = $("body").find(".viewed_accrual_basis_dates_div_comparison");
	  			var sort_dates = $("body").find("#choose_purchase_item_graph_sort_dates_comparison").val();  
	  			var filter_type = 1;

	  			if(filter_from == "")
		  		{
		  			filter_from = 0;
		  		}
		  		if(filter_to == "")
		  		{
		  			filter_to = 0;
		  		}

		  		if(filter_from != 0 && filter_to != 0)
		  		{
		  			$("body").find("#purchase_item_graph_div_comparison").css("display","none");
		  			$("body").find("#no_items_div_comparison").css("display","none");
		  			$("body").find("#purchase_item_graph_loader_div_comparison").css("display","block");
		  			var new_viewed_date = "";
		  			var month_name = "";
		  			var separated_from = filter_from.split(/\s*\-\s*/g); 
		  			var separated_to = filter_to.split(/\s*\-\s*/g); 

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
		  			viewed_current_date.html(new_viewed_date);

		  			var viewed_accrual_basis_temp = $("body").find(".viewed_accrual_basis_dates_div_comparison").text();
		  			var new_viewed_accrual_basis_temp = viewed_accrual_basis_temp.split("*");
		  			viewed_accrual_basis.html("* "+new_viewed_date+" * "+new_viewed_accrual_basis_temp[2]+" * ");

		  			$('#choose_purchase_item_graph_sort_dates_comparison option[value=0]').attr('selected',true);

			  		$.post(base_url+"inventory/filter_purchase_item_graph_comparison/" + filter_from +"/"+ filter_to +"/"+ vendor_id +"/"+ sort_dates +"/"+ filter_type,"", function(response){

			  			$("body").find("#purchase_item_graph_div_comparison").find("svg").remove();
			  			var purchase_item_graph_chart_comparison = new Morris.Bar({
													barGap:2,
										  			barSizeRatio:0.45,
												    element: 'purchase_item_graph_div_comparison',
												    hideHover: 'always',
												    resize: true,
												    xLabelAngle: 89.5,
												  	xkey: 'label',
												  	ykeys: ['value','second_value'],
												  	labels: ['First Value','Second Value'],
												  	barColors: ['#0B62A4','#F09F19']
						});
						$("body").find("svg").css('width','100%');
						$("body").find("svg").css('overflow','visible');

			  			if(response.data.graph.length > 0)
						{
							$("body").find("#purchase_item_graph_div_comparison").css("display","block");
				  			$("body").find("#no_items_div_comparison").css("display","none");
				  			$("body").find("#purchase_item_graph_loader_div_comparison").css("display","none");
							purchase_item_graph_chart_comparison.setData(response.data.graph);
							$("body").find("svg").find("path").css('stroke','#000');
							$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
							$("body").find(".purchase_item_graph_bar_charts_div_comparison").css('margin-bottom','-10px');
						}
						else
						{
				  			$("body").find("#purchase_item_graph_div_comparison").css("display","none");
				  			$("body").find("#no_items_div_comparison").css("display","block");
				  			$("body").find("#purchase_item_graph_loader_div_comparison").css("display","none");
				  			$("body").find(".purchase_item_graph_bar_charts_div_comparison").css('margin-bottom','50px');
						}
			  		});
		  		}
         	}
      	});	

		// Filter Type is 1. Select dates. Date filter. Standard
		$('.choose_date_purchase_item_graph_standard').datepicker({
			dateFormat: 'yy-mm-dd',
         	onClose: function (dateText, inst) {
         		var filter_from = $("body").find("#search_from_purchase_item_graph_standard").val();
	  			var filter_to = $("body").find("#search_to_purchase_item_graph_standard").val();
	  			var sort_dates = $("body").find("#choose_purchase_item_graph_sort_dates_standard").val();  
	  			var vendor_id = $("body").find(".vendor_filter_purchase_item_graph_standard").val();
	  			var viewed_current_date = $("body").find("#viewed_current_date_purchase_item_graph_standard");
	  			var filter_type = 1;
	
				if(filter_from == "")
		  		{
		  			filter_from = 0;
		  		}
		  		if(filter_to == "")
		  		{
		  			filter_to = 0;
		  		}

		  		if(filter_from != 0 && filter_to != 0)
		  		{ 	
		  			$("body").find("#purchase_item_graph_div").css("display","none");
		  			$("body").find("#no_items_div").css("display","none");
		  			$("body").find("#purchase_item_graph_loader_div").css("display","block");
		  			var new_viewed_date = "";
		  			var month_name = "";
		  			var separated_from = filter_from.split(/\s*\-\s*/g); 
		  			var separated_to = filter_to.split(/\s*\-\s*/g); 

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
		  			viewed_current_date.html(new_viewed_date);
		  			$('#choose_purchase_item_graph_sort_dates_standard option[value=0]').attr('selected',true);
	  			}

	  			$.post(base_url+"inventory/filter_purchase_item_graph_standard/" + filter_from +"/"+ filter_to +"/"+ vendor_id +"/"+ sort_dates +"/"+ filter_type,"", function(response){
		  			if(response.data.graph.length > 0)
					{
						$("body").find("#purchase_item_graph_div").css("display","block");
			  			$("body").find("#no_items_div").css("display","none");
			  			$("body").find("#purchase_item_graph_loader_div").css("display","none");
						purchase_item_graph_chart.setData(response.data.graph);
						$("body").find("svg").find("path").css('stroke','#000');
						$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
						$("body").find(".purchase_item_graph_bar_charts_div_standard").css('margin-bottom','190px');
					}
					else
					{
						$("body").find("#purchase_item_graph_div").css("display","none");
			  			$("body").find("#no_items_div").css("display","block");
			  			$("body").find("#item_usage_div_comparison").css("display","none");
			  			$("body").find("#purchase_item_graph_loader_div").css("display","none");
			  			$("body").find(".purchase_item_graph_bar_charts_div_standard").css('margin-bottom','60px');
					}
		  		});
         	}
		});

		// Filter Type is 2. Sort Dates Filter. Comparison
	  	$('select#choose_purchase_item_graph_sort_dates_comparison').on('change', function (e) 
	  	{
	  		var filter_from = $("body").find("#search_from_purchase_item_graph_comparison").val();
  			var filter_to = $("body").find("#search_to_purchase_item_graph_comparison").val();
  			var vendor_id = $("body").find(".vendor_filter_purchase_item_graph_comparison").val(); 
  			var viewed_current_date = $("body").find("#viewed_current_date_purchase_item_graph_comparison");
  			var viewed_accrual_basis = $("body").find(".viewed_accrual_basis_dates_div_comparison");
  			var sort_dates = this.value; 
  			var filter_type = 2;

  			if(vendor_id == "all_vendors")
  			{
  				vendor_id = 0;
  			}

  			if(filter_from == "")
	  		{
	  			filter_from = 0;
	  		}
	  		if(filter_to == "")
	  		{
	  			filter_to = 0;
	  		}

	  		// Sort Date
  			$("body").find("#purchase_item_graph_div_comparison").css("display","none");
  			$("body").find("#no_items_div_comparison").css("display","none");
  			$("body").find("#purchase_item_graph_loader_div_comparison").css("display","block");

  			$.post(base_url+"inventory/filter_purchase_item_graph_comparison/" + filter_from +"/"+ filter_to +"/"+ vendor_id +"/"+ sort_dates +"/"+ filter_type,"", function(response){
	  			$("body").find("#purchase_item_graph_div_comparison").find("svg").remove();
	  			var purchase_item_graph_chart_comparison = new Morris.Bar({
											barGap:2,
								  			barSizeRatio:0.45,
										    element: 'purchase_item_graph_div_comparison',
										    hideHover: 'always',
										    resize: true,
										    xLabelAngle: 89.5,
										  	xkey: 'label',
										  	ykeys: ['value','second_value'],
										  	labels: ['First Value','Second Value'],
										  	barColors: ['#0B62A4','#F09F19']
				});
				$("body").find("svg").css('width','100%');
				$("body").find("svg").css('overflow','visible');

	  			if(response.data.graph.length > 0)
				{
					$("body").find("#purchase_item_graph_div_comparison").css("display","block");
		  			$("body").find("#no_items_div_comparison").css("display","none");
		  			$("body").find("#purchase_item_graph_loader_div_comparison").css("display","none");
					purchase_item_graph_chart_comparison.setData(response.data.graph);
					$("body").find("svg").find("path").css('stroke','#000');
					$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
					$("body").find(".purchase_item_graph_bar_charts_div_comparison").css('margin-bottom','-10px');
				}
				else
				{
		  			$("body").find("#purchase_item_graph_div_comparison").css("display","none");
		  			$("body").find("#no_items_div_comparison").css("display","block");
		  			$("body").find("#purchase_item_graph_loader_div_comparison").css("display","none");
		  			$("body").find(".purchase_item_graph_bar_charts_div_comparison").css('margin-bottom','50px');
				}
				$("body").find("#search_from_purchase_item_graph_comparison").val(response.data.date_range_from);
  				$("body").find("#search_to_purchase_item_graph_comparison").val(response.data.date_range_to);

  				var new_viewed_date = "";
	  			var month_name = "";
	  			var separated_from = response.data.date_range_from.split(/\s*\-\s*/g); 
	  			var separated_to = response.data.date_range_to.split(/\s*\-\s*/g); 

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
	  			viewed_current_date.html(new_viewed_date);

	  			if(sort_dates == 0 || sort_dates == 1 || sort_dates == 2 || sort_dates == 3 || sort_dates == 5 || sort_dates == 7 || sort_dates == 9 || sort_dates == 10 || sort_dates == 11 || sort_dates == 12 || sort_dates == 14 || sort_dates == 16 || sort_dates == 18)
	  			{
	  				var ytd_new_viewed_date = "";
		  			var month_name = "";
		  			var ytd_separated_from = response.data.ytd_from.split(/\s*\-\s*/g); 
		  			var ytd_separated_to = response.data.ytd_to.split(/\s*\-\s*/g); 

		  			if(ytd_separated_from[1] == 1)
	  				{
	  					month_name = "January";
	  				}
	  				else if(ytd_separated_from[1] == 2)
	  				{
	  					month_name = "February";
	  				}
	  				else if(ytd_separated_from[1] == 3)
	  				{
	  					month_name = "March";
	  				}
	  				else if(ytd_separated_from[1] == 4)
	  				{
	  					month_name = "April";
	  				}
	  				else if(ytd_separated_from[1] == 5)
	  				{
	  					month_name = "May";
	  				}
	  				else if(ytd_separated_from[1] == 6)
	  				{
	  					month_name = "June";
	  				}
	  				else if(ytd_separated_from[1] == 7)
	  				{
	  					month_name = "July";
	  				}
	  				else if(ytd_separated_from[1] == 8)
	  				{
	  					month_name = "August";
	  				}
	  				else if(ytd_separated_from[1] == 9)
	  				{
	  					month_name = "September";
	  				}
	  				else if(ytd_separated_from[1] == 10)
	  				{
	  					month_name = "October";
	  				}
	  				else if(ytd_separated_from[1] == 11)
	  				{
	  					month_name = "November";
	  				}
	  				else if(ytd_separated_from[1] == 12)
	  				{
	  					month_name = "December";
	  				}

	  				if(ytd_separated_to[1] == 1)
	  				{
	  					month_name_to = "January";
	  				}
	  				else if(ytd_separated_to[1] == 2)
	  				{
	  					month_name_to = "February";
	  				}
	  				else if(ytd_separated_to[1] == 3)
	  				{
	  					month_name_to = "March";
	  				}
	  				else if(ytd_separated_to[1] == 4)
	  				{
	  					month_name_to = "April";
	  				}
	  				else if(ytd_separated_to[1] == 5)
	  				{
	  					month_name_to = "May";
	  				}
	  				else if(ytd_separated_to[1] == 6)
	  				{
	  					month_name_to = "June";
	  				}
	  				else if(ytd_separated_to[1] == 7)
	  				{
	  					month_name_to = "July";
	  				}
	  				else if(ytd_separated_to[1] == 8)
	  				{
	  					month_name_to = "August";
	  				}
	  				else if(ytd_separated_to[1] == 9)
	  				{
	  					month_name_to = "September";
	  				}
	  				else if(ytd_separated_to[1] == 10)
	  				{
	  					month_name_to = "October";
	  				}
	  				else if(ytd_separated_to[1] == 11)
	  				{
	  					month_name_to = "November";
	  				}
	  				else if(ytd_separated_to[1] == 12)
	  				{
	  					month_name_to = "December";
	  				}

	  				if((ytd_separated_from[0] == ytd_separated_to[0]) && (ytd_separated_from[1] == ytd_separated_to[1]) && (ytd_separated_from[2] == ytd_separated_to[2]))
		  			{
		  				ytd_new_viewed_date = month_name+" "+ytd_separated_from[2]+", "+ytd_separated_from[0];
		  			}
		  			else if((ytd_separated_from[0] == ytd_separated_to[0]) && (ytd_separated_from[1] == ytd_separated_to[1]) && (ytd_separated_from[2] != ytd_separated_to[2]))
		  			{
		  				ytd_new_viewed_date = month_name+" "+ytd_separated_from[2]+" - "+ytd_separated_to[2]+", "+ytd_separated_from[0];
		  			}
		  			else 
		  			{
		  				ytd_new_viewed_date = month_name+" "+ytd_separated_from[2]+", "+ytd_separated_from[0]+" - "+month_name_to+" "+ytd_separated_to[2]+", "+ytd_separated_to[0];
		  			}
		  			viewed_accrual_basis.html("* "+new_viewed_date+" * "+ytd_new_viewed_date+" * ");
	  			}
	  			else if(sort_dates == 4 || sort_dates == 6 || sort_dates == 8 || sort_dates == 13 || sort_dates == 15 || sort_dates == 17)
	  			{
	  				var ytd_new_viewed_date = "";
		  			var month_name = "";
		  			var ytd_separated_from = response.data.ytd_from.split(/\s*\-\s*/g); 
		  			var ytd_separated_to = response.data.ytd_to.split(/\s*\-\s*/g); 

		  			if(ytd_separated_from[1] == 1)
	  				{
	  					month_name = "January";
	  				}
	  				else if(ytd_separated_from[1] == 2)
	  				{
	  					month_name = "February";
	  				}
	  				else if(ytd_separated_from[1] == 3)
	  				{
	  					month_name = "March";
	  				}
	  				else if(ytd_separated_from[1] == 4)
	  				{
	  					month_name = "April";
	  				}
	  				else if(ytd_separated_from[1] == 5)
	  				{
	  					month_name = "May";
	  				}
	  				else if(ytd_separated_from[1] == 6)
	  				{
	  					month_name = "June";
	  				}
	  				else if(ytd_separated_from[1] == 7)
	  				{
	  					month_name = "July";
	  				}
	  				else if(ytd_separated_from[1] == 8)
	  				{
	  					month_name = "August";
	  				}
	  				else if(ytd_separated_from[1] == 9)
	  				{
	  					month_name = "September";
	  				}
	  				else if(ytd_separated_from[1] == 10)
	  				{
	  					month_name = "October";
	  				}
	  				else if(ytd_separated_from[1] == 11)
	  				{
	  					month_name = "November";
	  				}
	  				else if(ytd_separated_from[1] == 12)
	  				{
	  					month_name = "December";
	  				}

	  				if(ytd_separated_to[1] == 1)
	  				{
	  					month_name_to = "January";
	  				}
	  				else if(ytd_separated_to[1] == 2)
	  				{
	  					month_name_to = "February";
	  				}
	  				else if(ytd_separated_to[1] == 3)
	  				{
	  					month_name_to = "March";
	  				}
	  				else if(ytd_separated_to[1] == 4)
	  				{
	  					month_name_to = "April";
	  				}
	  				else if(ytd_separated_to[1] == 5)
	  				{
	  					month_name_to = "May";
	  				}
	  				else if(ytd_separated_to[1] == 6)
	  				{
	  					month_name_to = "June";
	  				}
	  				else if(ytd_separated_to[1] == 7)
	  				{
	  					month_name_to = "July";
	  				}
	  				else if(ytd_separated_to[1] == 8)
	  				{
	  					month_name_to = "August";
	  				}
	  				else if(ytd_separated_to[1] == 9)
	  				{
	  					month_name_to = "September";
	  				}
	  				else if(ytd_separated_to[1] == 10)
	  				{
	  					month_name_to = "October";
	  				}
	  				else if(ytd_separated_to[1] == 11)
	  				{
	  					month_name_to = "November";
	  				}
	  				else if(ytd_separated_to[1] == 12)
	  				{
	  					month_name_to = "December";
	  				}

	  				if((ytd_separated_from[0] == ytd_separated_to[0]) && (ytd_separated_from[1] == ytd_separated_to[1]))
		  			{
		  				ytd_new_viewed_date = month_name+" "+ytd_separated_from[0];
		  			}
		  			else 
		  			{
		  				ytd_new_viewed_date = month_name+" "+ytd_separated_from[0]+" - "+month_name_to+" "+ytd_separated_to[0];
		  			}
		  			viewed_accrual_basis.html("* "+new_viewed_date+" * "+ytd_new_viewed_date+" * ");
	  			}
	  		});
	  	});

		// Filter Type is 2. Sort Dates Filter. Standard
	  	$('select#choose_purchase_item_graph_sort_dates_standard').on('change', function (e) 
	  	{
	  		var filter_from = $("body").find("#search_from_purchase_item_graph_standard").val();
  			var filter_to = $("body").find("#search_to_purchase_item_graph_standard").val();
  			var sort_dates = this.value;  
  			var vendor_id = $("body").find(".vendor_filter_purchase_item_graph_standard").val();
  			var filter_type = 2;
  			var viewed_current_date = $("body").find("#viewed_current_date_purchase_item_graph_standard");

  			if(vendor_id == "all_vendors")
  			{
  				vendor_id = 0;
  			}

  			if(filter_from == "")
	  		{
	  			filter_from = 0;
	  		}
	  		if(filter_to == "")
	  		{
	  			filter_to = 0;
	  		}
  			$("body").find("#purchase_item_graph_div").css("display","none");
  			$("body").find("#no_items_div").css("display","none");
  			$("body").find("#purchase_item_graph_loader_div").css("display","block");

  			$.post(base_url+"inventory/filter_purchase_item_graph_standard/" + filter_from +"/"+ filter_to +"/"+ vendor_id +"/"+ sort_dates +"/"+ filter_type,"", function(response){
	  			if(response.data.graph.length > 0)
				{
					$("body").find("#purchase_item_graph_div").css("display","block");
		  			$("body").find("#no_items_div").css("display","none");
		  			$("body").find("#purchase_item_graph_loader_div").css("display","none");
					purchase_item_graph_chart.setData(response.data.graph);
					$("body").find("svg").find("path").css('stroke','#000');
					$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
					$("body").find(".purchase_item_graph_bar_charts_div_standard").css('margin-bottom','190px');
				}
				else
				{
					$("body").find("#purchase_item_graph_div").css("display","none");
		  			$("body").find("#no_items_div").css("display","block");
		  			$("body").find("#item_usage_div_comparison").css("display","none");
		  			$("body").find("#purchase_item_graph_loader_div").css("display","none");
		  			$("body").find(".purchase_item_graph_bar_charts_div_standard").css('margin-bottom','60px');
				}
				$("body").find("#search_from_purchase_item_graph_standard").val(response.data.date_range_from);
  				$("body").find("#search_to_purchase_item_graph_standard").val(response.data.date_range_to);

  				var new_viewed_date = "";
	  			var month_name = "";
	  			var separated_from = response.data.date_range_from.split(/\s*\-\s*/g); 
	  			var separated_to = response.data.date_range_to.split(/\s*\-\s*/g); 

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
	  			viewed_current_date.html(new_viewed_date);

	  		});
	  	});

		// Filter Type is 3. Vendor Filter. Comparison
		$('select.vendor_filter_purchase_item_graph_comparison').on('change', function (e) 
	  	{	
	  		var filter_from = $("body").find("#search_from_purchase_item_graph_comparison").val();
  			var filter_to = $("body").find("#search_to_purchase_item_graph_comparison").val();
  			var vendor_id = this.value;  
  			var viewed_current_date = $("body").find("#viewed_current_date_purchase_item_graph_comparison");
  			var viewed_accrual_basis = $("body").find(".viewed_accrual_basis_dates_div_comparison");
  			var sort_dates = $("body").find("#choose_purchase_item_graph_sort_dates_comparison").val();  
  			var filter_type = 3;

  			if(vendor_id == "all_vendors")
  			{
  				vendor_id = 0;
  			}
  			if(filter_from == "")
	  		{
	  			filter_from = 0;
	  		}
	  		if(filter_to == "")
	  		{
	  			filter_to = 0;
	  		}

	  		$("body").find("#purchase_item_graph_div_comparison").css("display","none");
  			$("body").find("#no_items_div_comparison").css("display","none");
  			$("body").find("#purchase_item_graph_loader_div_comparison").css("display","block");

  			$.post(base_url+"inventory/filter_purchase_item_graph_comparison/" + filter_from +"/"+ filter_to +"/"+ vendor_id +"/"+ sort_dates +"/"+ filter_type,"", function(response){
	  			$("body").find("#purchase_item_graph_div_comparison").find("svg").remove();
	  			var purchase_item_graph_chart_comparison = new Morris.Bar({
											barGap:2,
								  			barSizeRatio:0.45,
										    element: 'purchase_item_graph_div_comparison',
										    hideHover: 'always',
										    resize: true,
										    xLabelAngle: 89.5,
										  	xkey: 'label',
										  	ykeys: ['value','second_value'],
										  	labels: ['First Value','Second Value'],
										  	barColors: ['#0B62A4','#F09F19']
				});
				$("body").find("svg").css('width','100%');
				$("body").find("svg").css('overflow','visible');

	  			if(response.data.graph.length > 0)
				{
					$("body").find("#purchase_item_graph_div_comparison").css("display","block");
		  			$("body").find("#no_items_div_comparison").css("display","none");
		  			$("body").find("#purchase_item_graph_loader_div_comparison").css("display","none");
					purchase_item_graph_chart_comparison.setData(response.data.graph);
					$("body").find("svg").find("path").css('stroke','#000');
					$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
					$("body").find(".purchase_item_graph_bar_charts_div_comparison").css('margin-bottom','-10px');
				}
				else
				{
		  			$("body").find("#purchase_item_graph_div_comparison").css("display","none");
		  			$("body").find("#no_items_div_comparison").css("display","block");
		  			$("body").find("#purchase_item_graph_loader_div_comparison").css("display","none");
		  			$("body").find(".purchase_item_graph_bar_charts_div_comparison").css('margin-bottom','50px');
				}
	  		});
	  	});

		// Filter Type is 3. Vendor Filter. Standard
		$('select.vendor_filter_purchase_item_graph_standard').on('change', function (e) 
	  	{
	  		var filter_from = $("body").find("#search_from_purchase_item_graph_standard").val();
  			var filter_to = $("body").find("#search_to_purchase_item_graph_standard").val();
  			var vendor_id = this.value;  
  			var sort_dates = $("body").find("#choose_purchase_item_graph_sort_dates_standard").val();
	  		var filter_type = 3;

  			if(filter_from == "")
	  		{
	  			filter_from = 0;
	  		}
	  		if(filter_to == "")
	  		{
	  			filter_to = 0;
	  		}
  			$("body").find("#purchase_item_graph_div").css("display","none");
  			$("body").find("#no_items_div").css("display","none");
  			$("body").find("#purchase_item_graph_loader_div").css("display","block");

	  		$.post(base_url+"inventory/filter_purchase_item_graph_standard/" + filter_from +"/"+ filter_to +"/"+ vendor_id +"/"+ sort_dates +"/"+ filter_type,"", function(response){
	  			if(response.data.graph.length > 0)
				{
					$("body").find("#purchase_item_graph_div").css("display","block");
		  			$("body").find("#no_items_div").css("display","none");
		  			$("body").find("#purchase_item_graph_loader_div").css("display","none");
					purchase_item_graph_chart.setData(response.data.graph);
					$("body").find("svg").find("path").css('stroke','#000');
					$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
					$("body").find(".purchase_item_graph_bar_charts_div_standard").css('margin-bottom','190px');
				}
				else
				{
					$("body").find("#purchase_item_graph_div").css("display","none");
		  			$("body").find("#no_items_div").css("display","block");
		  			$("body").find("#item_usage_div_comparison").css("display","none");
		  			$("body").find("#purchase_item_graph_loader_div").css("display","none");
		  			$("body").find(".purchase_item_graph_bar_charts_div_standard").css('margin-bottom','60px');
				}
	  		});
	  	});





	});

</script>


