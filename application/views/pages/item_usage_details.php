<style type="text/css">
	.badge
	{
		background-color: #7E7F80;
	}	
	ul > li.list-group-item
	{
		border:0px !important;
	}
	.badge
	{
		background-color: #fff !important;
		color: #000 !important;
		font-size: 16px;
	}

	/*.item_usage_legend{
	  float: left;
	  width: 13px;
	  height: 13px;
	  margin: 5px;
	  border: 1px solid rgba(0, 0, 0, .2);
	}*/

	@media (max-width: 580px){
		.panel-report .col-xxs-12{
			width:100%;
		}
	}

	@media print{

		@page { 
			margin-left: 0; 
			margin-right: 15px; 
			margin-bottom: 0;
		}

		.whole-container
	    {
	    	margin-top: -100px;
	    	margin-left: -230px !important;
	    }

	    /* 
	    	STANDARD STYLES
	    */
	    .item_usage_labels_div
	    {
	    	margin-top: -6px !important;
	    }
	    .item_usage_current_date_div
	    {
	    	margin-top: 5px !important;
	    }
	    .viewed_current_date_item_usage_div_standard
	    {
	    	margin-top: -15px !important;
	    }
	    .category_item_usage
	    {
	    	margin-top: -7px;
	    }
	    #item_usage_div
	    {
	    	margin-right: 100px !important;
	    }
	    .sort_dates_print_value_div
	    {
	    	margin-top: 15px !important;
	    }
	    .item_usage_bar_charts_div_standard
	    {
	    	margin-top: 25px !important;
	    }
	    .logged_in_hospice_standard
	    {
	    	margin-bottom: 11px !important;
	    }
	    .logged_in_hospice_item_usage_current_date_div
	    {
	    	margin-top: -5px !important;
	    }
	    .logged_in_hospice_viewed_current_date_item_usage_standard
	    {
	    	margin-top: -5px !important;
	    }

	    /* 
	    	COMPARISON STYLES
	    */
	    .item_usage_labels_div_comparison
	    {
	    	margin-top: -6px !important;
	    }
	    .item_usage_current_date_div_comparison
	    {
	    	margin-top: 5px !important;
	    }
	    .viewed_current_date_item_usage_div_comparison
	    {
	    	margin-top: -15px !important;
	    }
	    .viewed_accrual_basis_div_comparison
	    {
	    	margin-top: 12px !important;
	    }
	    .viewed_accrual_basis_dates_div_comparison
	    {
	    	margin-top: -14px !important;
	    }
	    .sort_dates_comparison_print_value_div
		{
			margin-top: 15px !important;
		}
	    .item_usage_bar_charts_div_comparison
	    {
	    	margin-top: 15px !important;
	    }
	    .logged_in_hospice_comparison
	    {
	    	margin-bottom: 11px !important;
	    }
	    .logged_in_hospice_item_usage_current_date_div_comparison
	    {
	    	margin-top: 0px !important;
	    }
	    .logged_in_hospice_viewed_current_date_item_usage_comparison
	    {
	    	margin-top: -5px !important;
	    }

	    .panel-default
	    {
	    	border:0px !important;
	    }
	    .tab-container
	    {
	    	border:0px !important;
	    }
	    .tab-content
	    {
	    	border:0px !important;
	    }

	    .item_usage_bar_charts_div_comparison
	    {
	    	margin-top: -10px !important;
	    }

	    svg text 
	    {
	    	fill:#0e0e0e !important;
	    }
	}

</style>

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
										<input type="text" class="form-control choose_date_item_usage_standard" id="search_from_item_usage_standard" aria-describedby="sizing-addon2" value="<?php echo $from_date; ?>">
									</div>

									<div class="col-sm-1 col-md-1" style="margin-top:4px;text-align: left;">
										To
									</div>
									<div class="col-sm-1 col-md-1" style="padding-right: 0px !important;padding-left: 0px !important;left: -30px;">
										<?php 
											$to_date = date('Y-m-d');
										?>
										<input type="text" class="form-control choose_date_item_usage_standard" id="search_to_item_usage_standard" aria-describedby="sizing-addon3" value="<?php echo $to_date; ?>">
									</div>
									<div class="col-sm-2 col-md-2" style="margin-top:4px;padding-right:30px;text-align:right;">
										Sort Dates
				                	</div>
				                	<div class="col-sm-2 col-md-2" style="padding-right:0px;padding-left:0px;margin-left:-15px;">
				                		
										<select class="form-control filter_item_usage_sort_dates" id="choose_item_usage_sort_dates_standard" name="filter_item_usage_sort_dates" style="border: 0px;text-align-last:center;">
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
										<?php 
											if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "biller" || $this->session->userdata('account_type') == "sales_rep" || $this->session->userdata('account_type') == "distribution_supervisor")
						        			{
						        				$selected = "";
								                $hospices = get_hospices_v2($this->session->userdata('user_location'));
                    							$companies = get_companies_v2($this->session->userdata('user_location'));
							            ?>
												<select class="form-control pull-center hospice_filter_item_usage_standard hidden-print" id="item_usage_select_hospice_standard" name="hospice_filter_item_usage_standard" style="margin-top: 6px;border: 0px;font-size: 16px;font-weight: 600;text-align-last:center;">
													<option value="0">Advantage Home Medical Services</option>
													<optgroup label="Hospices">
									                <?php 
									                	foreach($hospices as $hospice) :
									                     	if($hospice['hospiceID'] != 13) {
									                ?>
									                      		<option value="<?php echo $hospice['hospiceID'] ?>" <?php if($hospiceID == $hospice['hospiceID']){ echo "selected"; } ?> ><?php echo $hospice['hospice_name'] ?></option>
									                <?php 
									                		} 
									                	endforeach;
									               	?>
									                </optgroup>
									                <?php 
									                	if(!empty($companies))
									                	{
									                ?>
									                		<optgroup label="Commercial Account">
											                <?php 
											                	foreach($companies as $company) :
											                		if($company['hospiceID'] != 13) { 
											                			if($hospiceID == $company['hospiceID'])
											                     		{
											                     			$selected = "selected";
											                     		}
											                ?>
											                      		<option value="<?php echo $company['hospiceID'] ?>" <?php if($hospiceID == $hospice['hospiceID']){ echo "selected"; } ?> ><?php echo $company['hospice_name'] ?></option>
											                <?php 
											                    	} 
											                    endforeach;
											                ?>
											                </optgroup> 
									                <?php
									                	}
									                ?>
												</select>
												<div class="visible-print-block chosen_hospice_standard" style="font-size:17px; font-weight:bold;"> Advantage Home Medical Services </div>
										<?php
											}else{
												$hospice = $this->session->userdata('group_id');
												$hospice_info = get_hospice_name($hospice);
										?>
												<div class="logged_in_hospice_standard" style="font-weight:600;font-size:16px;margin-top:13px;"> <?php echo $hospice_info['hospice_name']; ?> </div>
										<?php 
											}
										?>
									</div>
									<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 sort_dates_print_value_div" style="text-align:right;">
										<span class="visible-print-block sort_dates_print_value_span" style="margin-right:-40px !important;">
											This Month to Date
										</span>
									</div>
						    	</div>
						    	<div class="col-xs-12 col-sm-12 col-md-12 item_usage_labels_div" style="margin-top:11px;">
						    		<?php 
										if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "biller" || $this->session->userdata('account_type') == "sales_rep" || $this->session->userdata('account_type') == "distribution_supervisor")
					        			{
					        		?>
								    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 item_usage_current_date_div" style="margin-top:-15px;">
												<?php 
													$current_date = date('Y-m-d');
													echo date("m/d/Y", strtotime($current_date));
												?>
											</div>
									<?php 
										}
										else
										{
									?>
											<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 logged_in_hospice_item_usage_current_date_div" style="margin-top:-11px;">
												<?php 
													$current_date = date('Y-m-d');
													echo date("m/d/Y", strtotime($current_date));
												?>
											</div>
									<?php 
										}
									?>
									<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;margin-top:0px;">
							    		<?php
								    		if($item_category == "capped_item_usage")
								    		{
								    			echo "<div class='category_item_usage'>Capped Item Usage Standard</div>";
								    			$item_category_num = 1;
								    		}
								    		else if($item_category == "noncapped_item_usage")
								    		{
								    			echo "<div class='category_item_usage'>Non-Capped Item Usage Standard</div>";
								    			$item_category_num = 2;
								    		}
								    		else
								    		{
								    			echo "<div class='category_item_usage'>Disposable Item Usage Standard</div>";
								    			$item_category_num = 3;
								    		}
								    	?>
								    	<input type="hidden" id="item_category_num" value="<?php echo $item_category_num; ?>">
								    </div>
									<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

									</div>
						    	</div>
						    	
								<?php 
									if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "biller" || $this->session->userdata('account_type') == "sales_rep" || $this->session->userdata('account_type') == "distribution_supervisor")
				        			{
				        		?>
							    		<div class="col-xs-12 col-sm-12 col-md-12 viewed_current_date_item_usage_div_standard " style="text-align:center;margin-top:5px;">
								<?php 
									}
									else
									{
								?>
										<div class="col-xs-12 col-sm-12 col-md-12 viewed_current_date_item_usage_div_standard logged_in_hospice_viewed_current_date_item_usage_standard" style="text-align:center;margin-top:5px;">
								<?php 
									}
								?>
									<span id="viewed_current_date_item_usage_standard">
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

						    	<div class="col-xs-12 col-sm-12 col-md-12 item_usage_bar_charts_div_standard" style="margin-top:40px;margin-bottom:60px;text-align:center;padding-left:0px;padding-right:0px;">
						    		<div id="item_usage_div" style="width:1150px !important;margin:auto;height:420px;"></div>
						    		<div id="item_usage_loader_div" style="display:none;"><h1 class='text-center loader text-success'><i class='fa fa-spin fa-spinner'></i></h1></div>
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
										<input type="text" class="form-control choose_date_item_usage_comparison" id="search_from_item_usage_comparison" aria-describedby="sizing-addon2" value="<?php echo $from_date; ?>">
									</div>

									<div class="col-sm-1 col-md-1" style="margin-top:4px;text-align: left;">
										To
									</div>
									<div class="col-sm-1 col-md-1" style="padding-right: 0px !important;padding-left: 0px !important;left: -30px;">
										<?php 
											$to_date = date('Y-m-d');
										?>
										<input type="text" class="form-control choose_date_item_usage_comparison" id="search_to_item_usage_comparison" aria-describedby="sizing-addon3" value="<?php echo $to_date; ?>">
									</div>
									
									<div class="col-sm-2 col-md-2" style="margin-top:4px;padding-right:30px;text-align:right;">
										Sort Dates
				                	</div>
				                	<div class="col-sm-2 col-md-2" style="padding-right:0px;padding-left:0px;margin-left:-15px;">
				                		
										<select class="form-control filter_item_usage_sort_dates" id="choose_item_usage_sort_dates_comparison" name="filter_item_usage_sort_dates" style="border: 0px;text-align-last:center;">
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
										<?php 
											if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "biller" || $this->session->userdata('account_type') == "sales_rep" || $this->session->userdata('account_type') == "distribution_supervisor")
						        			{
						        				$selected = "";
								                $hospices = get_hospices_v2($this->session->userdata('user_location'));
                    							$companies = get_companies_v2($this->session->userdata('user_location'));
							            ?>
												<select class="form-control pull-center hospice_filter_item_usage_comparison hidden-print" id="item_usage_select_hospice_comparison" name="hospice_filter_item_usage_comparison" style="margin-top: 6px;border: 0px;font-size: 16px;font-weight: 600;text-align-last:center;">
													<option value="0">Advantage Home Medical Services</option>
													<optgroup label="Hospices">
									                <?php 
									                	foreach($hospices as $hospice) :
									                     	if($hospice['hospiceID'] != 13) {
									                ?>
									                      		<option value="<?php echo $hospice['hospiceID'] ?>" <?php if($hospiceID == $hospice['hospiceID']){ echo "selected"; } ?> ><?php echo $hospice['hospice_name'] ?></option>
									                <?php 
									                		} 
									                	endforeach;
									               	?>
									                </optgroup>
									                <?php 
									                	if(!empty($companies))
									                	{
									                ?>
									                		<optgroup label="Commercial Account">
											                <?php 
											                	foreach($companies as $company) :
											                		if($company['hospiceID'] != 13) { 
											                			if($hospiceID == $company['hospiceID'])
											                     		{
											                     			$selected = "selected";
											                     		}
											                ?>
											                      		<option value="<?php echo $company['hospiceID'] ?>" <?php if($hospiceID == $hospice['hospiceID']){ echo "selected"; } ?> ><?php echo $company['hospice_name'] ?></option>
											                <?php 
											                    	} 
											                    endforeach;
											                ?>
											                </optgroup>  
									                <?php
									                	}
									                ?>
												</select>
												<div class="visible-print-block chosen_hospice_comparison" style="font-size:17px; font-weight:bold;"> Advantage Home Medical Services </div>
										<?php
											}else{
												$hospice = $this->session->userdata('group_id');
												$hospice_info = get_hospice_name($hospice);
										?>
												<div class="logged_in_hospice_comparison" style="font-weight:600;font-size:16px;margin-top:13px;"> <?php echo $hospice_info['hospice_name']; ?> </div>
										<?php 
											}
										?>
									</div>
									<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

									</div>

									<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 sort_dates_comparison_print_value_div" style="text-align:right;">
										<span class="visible-print-block sort_dates_comparison_print_value_span" style="margin-right:-40px !important;">
											This Month to Date
										</span>
									</div>
						    	</div>
						    	<div class="col-xs-12 col-sm-12 col-md-12 item_usage_labels_div_comparison" style="margin-top:11px;">

						    		<?php 
										if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "biller" || $this->session->userdata('account_type') == "sales_rep" || $this->session->userdata('account_type') == "distribution_supervisor")
					        			{
					        		?>
					        				<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 item_usage_current_date_div_comparison" style="margin-top:-15px;">
												<?php 
													$current_date = date('Y-m-d');
													echo date("m/d/Y", strtotime($current_date));
												?>
											</div>
									<?php 
										}
										else
										{
									?>
											<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 logged_in_hospice_item_usage_current_date_div_comparison" style="margin-top:-11px;">
												<?php 
													$current_date = date('Y-m-d');
													echo date("m/d/Y", strtotime($current_date));
												?>
											</div>
									<?php 
										}
									?>
									<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;margin-top:0px;">
							    		<?php
								    		if($item_category == "capped_item_usage")
								    		{
								    			echo "<div class='category_item_usage'>Capped Item Usage YTD Comparison</div>";
								    			$item_category_num = 1;
								    		}
								    		else if($item_category == "noncapped_item_usage")
								    		{
								    			echo "<div class='category_item_usage'>Non-Capped Item Usage YTD Comparison</div>";
								    			$item_category_num = 2;
								    		}
								    		else
								    		{
								    			echo "<div class='category_item_usage'>Disposable Item Usage YTD Comparison</div>";
								    			$item_category_num = 3;
								    		}
								    	?>
								    </div>
									<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

									</div>
						    	</div>

						    	<?php 
									if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "biller" || $this->session->userdata('account_type') == "sales_rep" || $this->session->userdata('account_type') == "distribution_supervisor")
				        			{
				        		?>
				        				<div class="col-xs-12 col-sm-12 col-md-12 viewed_current_date_item_usage_div_comparison" style="text-align:center;margin-top:5px;">
								<?php 
									}
									else
									{
								?>
										<div class="col-xs-12 col-sm-12 col-md-12 viewed_current_date_item_usage_div_comparison logged_in_hospice_viewed_current_date_item_usage_comparison" style="text-align:center;margin-top:5px;">
								<?php 
									}
								?>
						    		<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 viewed_accrual_basis_div_comparison" style="text-align:left;margin-top:-17px;">
						    			Accrual Basis
									</div>
									<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
										<span id="viewed_current_date_item_usage_comparison">
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
									<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

									</div>
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
						    	<div class="col-xs-12 col-sm-12 col-md-12 item_usage_bar_charts_div_comparison" style="margin-top:40px;margin-bottom:60px;text-align:center;padding-left:0px;padding-right:0px;">
						    		<div id="item_usage_div_comparison" style="width:1150px !important;margin:auto;height:420px;"> 
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
						    		<div id="item_usage_loader_div_comparison" style="display:none;"><h1 class='text-center loader text-success'><i class='fa fa-spin fa-spinner'></i></h1></div>
						    		<div id="no_items_div_comparison" style="display:none;"> <h4>No items to be displayed.</h4> </div>
						    	</div>
						   	</div>
						</div>
					</div>
				</div>
			</div>
		</div>	 
		<input type="hidden" class="ytd_comparison_opened_sign" value="0">   
		<div class="bg-light lter wrapper-md" style="margin-top:-20px;margin-left:-20px;margin-bottom:-15px;">
		   <button class="btn btn-default" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
		</div>
	</div>
</div>

<script type="text/javascript"> 

	$(document).ready(function(){

		var item_usage_chart = new Morris.Bar({
								barGap:4,
								data: [
    									{ 'label': '', value: 0}
    							],
					  			barSizeRatio:0.30,
							    element: 'item_usage_div',
							    hideHover: true,
							    resize: true,
							    xLabelAngle: 89.5,
							  	xkey: 'label',
							  	ykeys: ['value'],
							  	labels: ['Value'],
							  	hoverCallback:function (index, options, content, row) {
							  	  	var patients = JSON.stringify(row.patients);
								  	var html = "<div class='morris-hover-row-label'>"+row.label+"</div>"+
								  			 	"<div class='morris-hover-point go-patient' style='cursor:pointer;' data-patients='"+patients+"'>Customers: <a href='javascript:;' style='color: #0b62a4'>"+row.value+"</a></div>";
								   	return html;
								}

		});


		$('body').on('click','.go-patient',function(){
			var patients = $(this).attr("data-patients");
			modalbox('<?php echo base_url(); ?>report/patient_lists/?patients='+patients,
			{
				header:"Customers",
				button: false,
				width:"50%"
			});
		});

		$("body").find("svg").css('width','100%');
		$("body").find("svg").css('overflow','visible');
		$("body").find("svg").find("path").css('stroke','#000');
		$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');

		function get_item_usage_default()
		{
			var item_category_num = $("body").find("#item_category_num").val();
			$.post(base_url+"report/get_item_usage_today_default_standard/" + item_category_num,"", function(response){
				if(response.data.graph.length > 0)
				{
					item_usage_chart.setData(response.data.graph);
					$("body").find("svg").find("path").css('stroke','#000');
					$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
					$("body").find(".item_usage_bar_charts_div_standard").css('margin-bottom','190px');
				}
				else
				{
					$("body").find("#item_usage_div").css("display","none");
		  			$("body").find("#no_items_div").css("display","block");
		  			$("body").find("#item_usage_loader_div").css("display","none");
				}
			});
		}
		get_item_usage_default();
		
		$('body').on('click','.ytd_comparison_nav_tabs',function(){
			var ytd_comparison_opened_sign = $("body").find(".ytd_comparison_opened_sign").val();

			if(ytd_comparison_opened_sign == 0)
			{
				var item_usage_chart_comparison = new Morris.Bar({
											barGap:2,
								  			barSizeRatio:0.45,
								  			data: [
			    									{ 'label': '', value: 0}
			    							],
										    element: 'item_usage_div_comparison',
										    hideHover: true,
										    resize: true,
										    xLabelAngle: 89.5,
										  	xkey: 'label',
										  	ykeys: ['value','second_value'],
										  	labels: ['First Value','Second Value'],
										  	barColors: ['#0B62A4','#F09F19'],
										  	hoverCallback:function (index, options, content, row) {
											  	  var patients = JSON.stringify(row.patients);
												  var html = "<div class='morris-hover-row-label'>"+row.label+"</div>"+
												  			 "<div class='morris-hover-point go-patient' style='cursor:pointer;' data-patients='"+patients+"'>Customers: <a href='javascript:;' style='color: #0b62a4'>"+row.second_value+"</a></div>";
												   return html;
												}
				});
				$("body").find("svg").css('width','100%');
				$("body").find("svg").css('overflow','visible');

				var item_category_num = $("body").find("#item_category_num").val();
				$.post(base_url+"report/get_item_usage_today_default_comparison/" + item_category_num,"", function(response){
					if(response.data.graph.length > 0)
					{
						item_usage_chart_comparison.setData(response.data.graph);
						$("body").find("svg").find("path").css('stroke','#000');
						$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
						$("body").find(".item_usage_bar_charts_div_comparison").css('margin-bottom','-10px');
					}
					else
					{
						$("body").find("#item_usage_div_comparison").css("display","none");
			  			$("body").find("#no_items_div_comparison").css("display","block");
			  			$("body").find("#item_usage_loader_div_comparison").css("display","none");
			  			$("body").find(".item_usage_bar_charts_div_comparison").css('margin-bottom','50px');
					}
				});
				$("body").find(".ytd_comparison_opened_sign").val(1);
			}
		});

		// Filter Type is 2
	  	$('select#choose_item_usage_sort_dates_standard').on('change', function (e) 
	  	{
	  		var filter_from = $("body").find("#search_from_item_usage_standard").val();
  			var filter_to = $("body").find("#search_to_item_usage_standard").val();
  			var hospiceID = $("body").find(".hospice_filter_item_usage_standard").val();
  			var viewed_current_date = $("body").find("#viewed_current_date_item_usage_standard");
  			var item_category_num = $("body").find("#item_category_num").val();
  			var sort_dates = this.value; 
  			var filter_type = 2;

  			if(filter_from == "")
	  		{
	  			filter_from = 0;
	  		}
	  		if(filter_to == "")
	  		{
	  			filter_to = 0;
	  		}

	  		// Sort Date
  			$("body").find("#item_usage_div").css("display","none");
  			$("body").find("#no_items_div").css("display","none");
  			$("body").find("#item_usage_loader_div").css("display","block");

  			var sel = document.getElementById("choose_item_usage_sort_dates_standard");
			var text = sel.options[sel.selectedIndex].text;
			$("body").find(".sort_dates_comparison_print_value_span").html(text);

  			$.post(base_url+"report/filter_item_usage_standard/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ item_category_num +"/"+ sort_dates +"/"+ filter_type,"", function(response){
	  			if(response.data.graph.length > 0)
				{
					$("body").find("#item_usage_div").css("display","block");
		  			$("body").find("#no_items_div").css("display","none");
		  			$("body").find("#item_usage_loader_div").css("display","none");
					item_usage_chart.setData(response.data.graph);
					$("body").find("svg").find("path").css('stroke','#000');
					$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
					$("body").find(".item_usage_bar_charts_div_standard").css('margin-bottom','190px');
				}
				else
				{
		  			$("body").find("#item_usage_div").css("display","none");
		  			$("body").find("#no_items_div").css("display","block");
		  			$("body").find("#item_usage_loader_div").css("display","none");
		  			$("body").find(".item_usage_bar_charts_div_standard").css('margin-bottom','60px');
				}
				$("body").find("#search_from_item_usage_standard").val(response.data.date_range_from);
  				$("body").find("#search_to_item_usage_standard").val(response.data.date_range_to);

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

		// Filter Type is 3
		$('select.hospice_filter_item_usage_standard').on('change', function (e) 
	  	{
	  		var filter_from = $("body").find("#search_from_item_usage_standard").val();
  			var filter_to = $("body").find("#search_to_item_usage_standard").val();
  			var hospiceID = this.value;  
  			var viewed_current_date = $("body").find("#viewed_current_date_item_usage_standard");
  			var item_category_num = $("body").find("#item_category_num").val();
  			var sort_dates = $("body").find("#choose_item_usage_sort_dates_standard").val();
	  		var filter_type = 3;

  			var sel = document.getElementById("item_usage_select_hospice_standard");
			var text = sel.options[sel.selectedIndex].text;
			$("body").find(".chosen_hospice_standard").html(text);

  			if(filter_from == "")
	  		{
	  			filter_from = 0;
	  		}
	  		if(filter_to == "")
	  		{
	  			filter_to = 0;
	  		}
  			$("body").find("#item_usage_div").css("display","none");
  			$("body").find("#no_items_div").css("display","none");
  			$("body").find("#item_usage_loader_div").css("display","block");

	  		$.post(base_url+"report/filter_item_usage_standard/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ item_category_num +"/"+ sort_dates +"/"+ filter_type,"", function(response){
	  			if(response.data.graph.length > 0)
				{
					$("body").find("#item_usage_div").css("display","block");
		  			$("body").find("#no_items_div").css("display","none");
		  			$("body").find("#item_usage_loader_div").css("display","none");
					item_usage_chart.setData(response.data.graph);
					$("body").find("svg").find("path").css('stroke','#000');
					$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
					$("body").find(".item_usage_bar_charts_div_standard").css('margin-bottom','190px');
				}
				else
				{
					$("body").find("#item_usage_div").css("display","none");
		  			$("body").find("#no_items_div").css("display","block");
		  			$("body").find("#item_usage_div_comparison").css("display","none");
		  			$("body").find("#item_usage_loader_div").css("display","none");
		  			$("body").find(".item_usage_bar_charts_div_standard").css('margin-bottom','60px');
				}
	  		});
	  	});

	  	// Filter Type is 1
		$('.choose_date_item_usage_standard').datepicker({
	    	dateFormat: 'yy-mm-dd',
         	onClose: function (dateText, inst) {
            	var filter_from = $("body").find("#search_from_item_usage_standard").val();
	  			var filter_to = $("body").find("#search_to_item_usage_standard").val();
	  			var hospiceID = $("body").find(".hospice_filter_item_usage_standard").val(); 
	  			var viewed_current_date = $("body").find("#viewed_current_date_item_usage_standard");
	  			var item_category_num = $("body").find("#item_category_num").val();
	  			var sort_dates = $("body").find("#choose_item_usage_sort_dates_standard").val();
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
		  			$("body").find("#item_usage_div").css("display","none");
		  			$("body").find("#no_items_div").css("display","none");
		  			$("body").find("#item_usage_loader_div").css("display","block");
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
		  			$('#choose_item_usage_sort_dates_standard option[value=0]').attr('selected',true);

			  		$.post(base_url+"report/filter_item_usage_standard/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ item_category_num +"/"+ sort_dates +"/"+ filter_type,"", function(response){
			  			if(response.data.graph.length > 0)
						{
							$("body").find("#no_items_div").css("display","none");
							$("body").find("#item_usage_div").css("display","block");
				  			$("body").find("#item_usage_loader_div").css("display","none");
							item_usage_chart.setData(response.data.graph);
							$("body").find("svg").find("path").css('stroke','#000');
							$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
							$("body").find(".item_usage_bar_charts_div_standard").css('margin-bottom','190px');
						}
						else
						{
		  					$("body").find("#item_usage_div").css("display","none");
				  			$("body").find("#no_items_div").css("display","block");
				  			$("body").find("#item_usage_loader_div").css("display","none");
				  			$("body").find(".item_usage_bar_charts_div_standard").css('margin-bottom','60px');
						}
			  		});
	  			}
    		}
      	});

	  	// Filter Type is 1
		$('.choose_date_item_usage_comparison').datepicker({
	    	dateFormat: 'yy-mm-dd',
         	onClose: function (dateText, inst) {
	  			var filter_from = $("body").find("#search_from_item_usage_comparison").val();
	  			var filter_to = $("body").find("#search_to_item_usage_comparison").val();
	  			var hospiceID = $("body").find(".hospice_filter_item_usage_comparison").val(); 
	  			var viewed_current_date = $("body").find("#viewed_current_date_item_usage_comparison");
	  			var viewed_accrual_basis = $("body").find(".viewed_accrual_basis_dates_div_comparison");
	  			var item_category_num = $("body").find("#item_category_num").val();
	  			var sort_dates = $("body").find("#choose_item_usage_sort_dates_standard").val();
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
		  			$("body").find("#item_usage_div_comparison").css("display","none");
		  			$("body").find("#no_items_div_comparison").css("display","none");
		  			$("body").find("#item_usage_loader_div_comparison").css("display","block");
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
		  			$('#choose_item_usage_sort_dates_comparison option[value=0]').attr('selected',true);

			  		$.post(base_url+"report/filter_item_usage_comparison/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ item_category_num +"/"+ sort_dates +"/"+ filter_type,"", function(response){
			  			$("body").find("#item_usage_div_comparison").find("svg").remove();
			  			var item_usage_chart_comparison = new Morris.Bar({
													barGap:2,
										  			barSizeRatio:0.45,
												    data: [
			    									{ 'label': '', value: 0}
					    							],
												    element: 'item_usage_div_comparison',
												    hideHover: true,
												    resize: true,
												    xLabelAngle: 89.5,
												  	xkey: 'label',
												  	ykeys: ['value','second_value'],
												  	labels: ['First Value','Second Value'],
												  	barColors: ['#0B62A4','#F09F19'],
												  	hoverCallback:function (index, options, content, row) {
													  	  var patients = JSON.stringify(row.patients);
														  var html = "<div class='morris-hover-row-label'>"+row.label+"</div>"+
														  			 "<div class='morris-hover-point go-patient' style='cursor:pointer;' data-patients='"+patients+"'>Customers: <a href='javascript:;' style='color: #0b62a4'>"+row.second_value+"</a></div>";
														   return html;
														}
						});
						$("body").find("svg").css('width','100%');
						$("body").find("svg").css('overflow','visible');

			  			if(response.data.graph.length > 0)
						{
							$("body").find("#item_usage_div_comparison").css("display","block");
				  			$("body").find("#no_items_div_comparison").css("display","none");
				  			$("body").find("#item_usage_loader_div_comparison").css("display","none");
							item_usage_chart_comparison.setData(response.data.graph);
							$("body").find("svg").find("path").css('stroke','#000');
							$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
							$("body").find(".item_usage_bar_charts_div_comparison").css('margin-bottom','-10px');
						}
						else
						{
		  					$("body").find("#item_usage_div_comparison").css("display","none");
				  			$("body").find("#no_items_div_comparison").css("display","block");
				  			$("body").find("#item_usage_loader_div_comparison").css("display","none");
				  			$("body").find(".item_usage_bar_charts_div_comparison").css('margin-bottom','50px');
						}
			  		});
		  		}
         	}
      	});	

		// Filter Type is 3
	  	$('select.hospice_filter_item_usage_comparison').on('change', function (e) 
	  	{
	  		var filter_from = $("body").find("#search_from_item_usage_comparison").val();
  			var filter_to = $("body").find("#search_to_item_usage_comparison").val();
  			var hospiceID = this.value;  
  			var viewed_current_date = $("body").find("#viewed_current_date_item_usage_comparison");
  			var viewed_accrual_basis = $("body").find(".viewed_accrual_basis_dates_div_comparison");
  			var item_category_num = $("body").find("#item_category_num").val();
  			var sort_dates = $("body").find("#choose_item_usage_sort_dates_comparison").val();
  			var filter_type = 3;

  			var sel = document.getElementById("item_usage_select_hospice_comparison");
			var text = sel.options[sel.selectedIndex].text;
			$("body").find(".chosen_hospice_comparison").html(text);

			if(filter_from == "")
	  		{
	  			filter_from = 0;
	  		}
	  		if(filter_to == "")
	  		{
	  			filter_to = 0;
	  		}
  			$("body").find("#item_usage_div_comparison").css("display","none");
  			$("body").find("#no_items_div_comparison").css("display","none");
  			$("body").find("#item_usage_loader_div_comparison").css("display","block");

  			$.post(base_url+"report/filter_item_usage_comparison/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ item_category_num +"/"+ sort_dates +"/"+ filter_type,"", function(response){
	  			$("body").find("#item_usage_div_comparison").find("svg").remove();
	  			var item_usage_chart_comparison = new Morris.Bar({
											barGap:2,
								  			barSizeRatio:0.45,
										    data: [
			    									{ 'label': '', value: 0}
			    							],
										    element: 'item_usage_div_comparison',
										    hideHover: true,
										    resize: true,
										    xLabelAngle: 89.5,
										  	xkey: 'label',
										  	ykeys: ['value','second_value'],
										  	labels: ['First Value','Second Value'],
										  	barColors: ['#0B62A4','#F09F19'],
										  	hoverCallback:function (index, options, content, row) {
											  	  var patients = JSON.stringify(row.patients);
												  var html = "<div class='morris-hover-row-label'>"+row.label+"</div>"+
												  			 "<div class='morris-hover-point go-patient' style='cursor:pointer;' data-patients='"+patients+"'>Customers: <a href='javascript:;' style='color: #0b62a4'>"+row.second_value+"</a></div>";
												   return html;
												}
				});
				$("body").find("svg").css('width','100%');
				$("body").find("svg").css('overflow','visible');

	  			if(response.data.graph.length > 0)
				{
					$("body").find("#item_usage_div_comparison").css("display","block");
		  			$("body").find("#no_items_div_comparison").css("display","none");
		  			$("body").find("#item_usage_loader_div_comparison").css("display","none");
					item_usage_chart_comparison.setData(response.data.graph);
					$("body").find("svg").find("path").css('stroke','#000');
					$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
					$("body").find(".item_usage_bar_charts_div_comparison").css('margin-bottom','-10px');
				}
				else
				{
					$("body").find("#item_usage_div_comparison").css("display","none");
		  			$("body").find("#no_items_div_comparison").css("display","block");
		  			$("body").find("#item_usage_loader_div_comparison").css("display","none");
		  			$("body").find(".item_usage_bar_charts_div_comparison").css('margin-bottom','50px');
				}
	  		});

	  	});	

	  	// Filter Type is 2
		$('select#choose_item_usage_sort_dates_comparison').on('change', function (e) 
	  	{
	  		var filter_from = $("body").find("#search_from_item_usage_comparison").val();
  			var filter_to = $("body").find("#search_to_item_usage_comparison").val();
  			var hospiceID = $("body").find(".hospice_filter_item_usage_comparison").val(); 
  			var viewed_current_date = $("body").find("#viewed_current_date_item_usage_comparison");
  			var viewed_accrual_basis = $("body").find(".viewed_accrual_basis_dates_div_comparison");
  			var item_category_num = $("body").find("#item_category_num").val();
  			var sort_dates = this.value; 
  			var filter_type = 2;

  			if(hospiceID == undefined)
  			{
  				hospiceID = 0;
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
  			$("body").find("#item_usage_div_comparison").css("display","none");
  			$("body").find("#no_items_div_comparison").css("display","none");
  			$("body").find("#item_usage_loader_div_comparison").css("display","block");

  			var sel = document.getElementById("choose_item_usage_sort_dates_comparison");
			var text = sel.options[sel.selectedIndex].text;
			$("body").find(".sort_dates_comparison_print_value_span").html(text);

  			$.post(base_url+"report/filter_item_usage_comparison/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ item_category_num +"/"+ sort_dates +"/"+ filter_type,"", function(response){
	  			$("body").find("#item_usage_div_comparison").find("svg").remove();
	  			var item_usage_chart_comparison = new Morris.Bar({
											barGap:2,
								  			barSizeRatio:0.45,
										    data: [
			    									{ 'label': '', value: 0}
			    							],
										    element: 'item_usage_div_comparison',
										    hideHover: true,
										    resize: true,
										    xLabelAngle: 89.5,
										  	xkey: 'label',
										  	ykeys: ['value','second_value'],
										  	labels: ['First Value','Second Value'],
										  	barColors: ['#0B62A4','#F09F19'],
										  	hoverCallback:function (index, options, content, row) {
											  	  var patients = JSON.stringify(row.patients);
												  var html = "<div class='morris-hover-row-label'>"+row.label+"</div>"+
												  			 "<div class='morris-hover-point go-patient' style='cursor:pointer;' data-patients='"+patients+"'>Customers: <a href='javascript:;' style='color: #0b62a4'>"+row.second_value+"</a></div>";
												   return html;
												}
				});
				$("body").find("svg").css('width','100%');
				$("body").find("svg").css('overflow','visible');

	  			if(response.data.graph.length > 0)
				{
		  			$("body").find("#item_usage_div_comparison").css("display","block");
		  			$("body").find("#no_items_div_comparison").css("display","none");
		  			$("body").find("#item_usage_loader_div_comparison").css("display","none");
					item_usage_chart_comparison.setData(response.data.graph);
					$("body").find("svg").find("path").css('stroke','#000');
					$("body").find("svg").find("text").css('fill','rgb(99, 95, 95)');
					$("body").find(".item_usage_bar_charts_div_comparison").css('margin-bottom','-10px');
				}
				else
				{
		  			$("body").find("#item_usage_div_comparison").css("display","none");
		  			$("body").find("#no_items_div_comparison").css("display","block");
		  			$("body").find("#item_usage_loader_div_comparison").css("display","none");
		  			$("body").find(".item_usage_bar_charts_div_comparison").css('margin-bottom','50px');
				}
				$("body").find("#search_from_item_usage_comparison").val(response.data.date_range_from);
  				$("body").find("#search_to_item_usage_comparison").val(response.data.date_range_to);

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

	});
		
</script>


