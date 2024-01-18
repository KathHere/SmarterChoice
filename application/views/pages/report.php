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
	@media (max-width: 580px){
		.panel-report .col-xxs-12{
			width:100%;
		}
	}

	@media print{
	    .current_date_time
	    {
	   		width: 100px;
	    }
	    .current_hospice
	    {
	    	width:1030px;
	    	margin-top: 40px !important;
	    }
	    .side_data_counts
	    {
	    	width:120px;
	    	height:430px !important;
	    	margin-top: 40px !important;
	    }
	    .activity_status_div
	    {
	    	width:42%;
	    	height:430px !important;
	    	margin-top: 40px !important;
	    }
	    .weekly_activity_status_div
	    {
	    	width:42%;
	    	height:430px !important;
	    	margin-top: 40px !important;
	    }

	    .daily_activity_status_word
	    {
	    	width:60% !important;
	    }

	    .daily_activity_status_graph
	    {
	    	width:40% !important;
	    }

	    .activity_status_div_panel
	    {
	    	margin-top: -28px;
	    }
	    .weekly_activity_status_div_panel
	    {
	    	margin-top: -23px;
	    }
	    .side_data_counts_2
	    {
	    	width:0px !important;
	    }
	    .residence_status_div
	    {
	    	width:42% !important;
	    	margin-top: 10px !important;
	    }
	    .daily_residence_status_word
	    {
	    	width:60% !important;
	    }

	    .daily_residence_status_graph
	    {
	    	width:40% !important;
	    }

	    .weekly_residence_status_div
	    {
	    	width:42%;
	    	margin-top: 10px !important;
	    }
	    .residence_status_div_panel
	    {
	    	margin-top: -28px;
	    }
	    .weekly_residence_status_div_panel
	    {
	    	margin-top: -23px;
	    }
	    .legend_div
	    {
	    	height:5px !important;
	    	margin-top:0px;
	    }

	    .panel-default
	    {
	    	border: none;
	    }

	    .whole-container
	    {
	    	margin-top: -80px;
	    	padding-left: 0px !important;
	    }
	}

</style>

<div class="page whole-container" ng-controller="FlotChartDemoCtrl">
	<div class="wrapper-md">

	    <div class="panel-report panel panel-default">
		    <div class="panel-heading hidden-print">
		      	Activity Status Reports
		    </div>
		    <div class="panel-body">

		    	<div class="col-sm-12 col-md-12 hidden-print" style="margin-top:20px">
		      		<div class="panel">
			          <div class="panel-body text-center">
							<div class="col-sm-12 col-md-12 nopad">

								<?php
                                    if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') {
                                        ?>
										<input type="hidden" class="account_logged_in" value="0">
										<div class="col-sm-4 col-md-4">

										</div>
										<div class="col-sm-1 col-md-1" style="margin-top:4px;padding-right: 0px;margin-left: -10px;">
											From
										</div>
										<div class="col-sm-1 col-md-1" style="margin-right: 0px !important;padding-right: 0px !important;padding-left: 0px;">
											<input type="text" class="form-control search_datepicker" id="search_from" aria-describedby="sizing-addon2" style="" >
										</div>

										<div class="col-sm-1 col-md-1" style="margin-top:4px;padding-right: 0px;margin-left: -27px;">
											To
										</div>
										<div class="col-sm-1 col-md-1" style="padding-right: 0px !important;padding-left: 0px !important;margin-left: -8px;">
											<input type="text" class="form-control search_datepicker" id="search_to" aria-describedby="sizing-addon3" style="">
										</div>

										<!-- <div class="col-sm-2 col-md-1">

										</div> -->
										<div class="col-sm-1 col-md-1" style="margin-top:4px;padding-right:0px;">
											Sort
					                	</div>
					                	<div class="col-sm-1 col-md-3" style="padding-right:0px;padding-left:0px;margin-left:-5px;">
					                		<?php
                                            $hospices = get_hospices_v2($this->session->userdata('user_location'));
                                        $companies = get_companies_v2($this->session->userdata('user_location')); ?>
											<select class="form-control filter_reports_by_hospice select2-ready" name="filter_reports_by_hospice" style="border: 0px;text-align-last:center;">
												<option value="0">Advantage Home Medical Services</option>
												<?php
                                                    if (!empty($hospices)) {
                                                        ?>
								                		<optgroup label="Hospices">
										                <?php
                                                            foreach ($hospices as $hospice) :
                                                                 if ($hospice['hospiceID'] != 13) {
                                                                     ?>
										                      		<option value="<?php echo $hospice['hospiceID']; ?>"><?php echo $hospice['hospice_name']; ?></option>
										                <?php
                                                                 }
                                                        endforeach; ?>
										                </optgroup>
								                <?php
                                                    }
                                        if (!empty($companies)) {
                                            ?>
								                		<optgroup label="Commercial Account">
										                <?php
                                                            foreach ($companies as $company) :
                                                                if ($company['hospiceID'] != 13) {
                                                                    ?>
										                      		<option value="<?php echo $company['hospiceID']; ?>"><?php echo $company['hospice_name']; ?></option>
										                <?php
                                                                }
                                            endforeach; ?>
										                </optgroup>
								                <?php
                                        } ?>
											</select>

					                	</div>

								<?php
                                    } else {
                                        ?>
										<input type="hidden" class="account_logged_in" value="1">
										<div class="col-sm-4 col-md-4">

										</div>
										<div class="col-sm-1 col-md-1" style="margin-top:4px;">
											From
										</div>
										<div class="col-sm-1 col-md-1" style="padding-right:0px;padding-left:0px;margin-left:-10px;">
											<input type="text" class="form-control search_datepicker" id="search_from" aria-describedby="sizing-addon2" style="">
										</div>

										<div class="col-sm-1 col-md-1" style="margin-top:4px;margin-left:-13px;">
											To
										</div>
										<div class="col-sm-1 col-md-1" style="padding-right:0px;padding-left:0px;margin-left:-15px;">
											<input type="text" class="form-control search_datepicker" id="search_to" aria-describedby="sizing-addon3" style="">
										</div>
										<div class="col-sm-4 col-md-4">

										</div>
								<?php
                                    }
                                ?>
			                </div>

			          </div>
			        </div>
		      	</div>
		      	<!-- <div class="col-sm-12"><h4 class="text-center text-default viewed_hospice_name" style="font-weight: 600;font-size: 16px;">Advantage Home Medical Services</h4></div> -->

		      	<div class="col-sm-12">

			      	<div class="col-xs-12 col-sm-12 col-md-2 mb30 current_date_time">
			      		<div class="mt5">
			      		<input type="hidden" value="<?php echo date('h:m A'); ?>" class="current_time_value">
		      			<?php
                            echo date('h:i A');
                        ?>
						</div>
						<div class="mt5">
		      			<?php
                            $current_date = date('Y-m-d');
                            echo date('m/d/Y', strtotime($current_date));
                        ?>
						<input type="hidden" value="<?php echo date('F d, Y', strtotime($current_date)); ?>" class="current_date_value">
						</div>
			      	</div>

			      	<?php
                        if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') {
                            ?>
			      			<div class="col-xs-12 col-sm-12 col-md-8 current_hospice" style="text-align:center;margin-bottom:35px;margin-top:-5px;">
			      				<h4 class="text-center text-default viewed_hospice_name" style="font-weight: 600;font-size: 16px;margin-bottom:4px;">Advantage Home Medical Services</h4>
								<span class="" style="font-size: 14px; font-weight:500;">Daily Activity Status</span>
								<br />
								<span class="visible-print-block general_report_initial_date" style="font-size: 14px; font-weight:500;"><?php echo date('F d, Y', strtotime($current_date)); ?></span>
			      			</div>
			      	<?php
                        } else {
                            $hospice = $this->session->userdata('group_id');
                            $hospice_info = get_hospice_name($hospice); ?>
							<input type="hidden" id="hospiceID_report_page" value="<?php echo $hospice; ?>">
							<div class="col-xs-12 col-sm-12 col-md-8 current_hospice" style="text-align: center;font-size: 16px;font-weight: 600;margin-bottom: 35px;margin-top: -7px;">
								<h4 class="text-center text-default viewed_hospice_name"></h4> <?php echo $hospice_info['hospice_name']; ?> </h4>
								<br />
								<span class="" style="font-size: 14px; font-weight:500;">Daily Activity Status</span>
								<br />
								<span class="visible-print-block general_report_initial_date" style="font-size: 14px; font-weight:500;"><?php echo date('F d, Y', strtotime($current_date)); ?></span>
							</div>
					<?php
                        }
                    ?>
					<div class="col-xs-12 col-sm-12 col-md-2">
			      	</div>
			    </div>

					<div class="col-sm-12 first_report_charts">
						<div class="col-xs-12 col-sm-12 col-md-2 mb30 side_data_counts">
							<div style="">
								<span class="viewed_total_entries"> Customers: <?php echo $total_patients; ?> </span>
							</div>
							<div style="margin-top:5px;">
								<span class="viewed_patient_los"> LOS: <?php echo $total_patient_los; ?> </span>
							</div>
							<div style="margin-top:5px;">
								<span class="viewed_patient_days"> CUS Days: <?php echo $total_patient_days; ?> </span>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-5 activity_status_div">
							<div class="mb30 panel-hovered">
								<div class="text-center" style="font-size:16px;">
									Activity Status
								</div>
								<div class="panel-body text-center hospice-activity-chart-panel activity_status_div_panel">
									<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 daily_activity_status_word">
										<ul class="list-group activities-report" style="margin-top:10%;cursor:pointer;">
										</ul>
									</div>
									<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 daily_activity_status_graph">
										<div class="box-body chart-responsive">
											<div class="chart" id="hospice-activity-chart" style="height: 300px; position: relative;">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xs-12 col-sm-12 col-md-5 residence_status_div">
							<div class="mb30 panel-hovered">
								<div class="text-center" style="font-size:16px;">
									Residence Status
								</div>
								<div class="panel-body text-center patient-residence-chart-panel residence_status_div_panel">
									<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 daily_residence_status_word">
										<ul class="list-group patienceresidence-report" style="margin-top:10%;cursor:pointer">
										</ul>
									</div>
									<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 daily_residence_status_graph">
										<div class="box-body chart-responsive">
											<div class="chart" id="patient-residence-chart" style="height: 300px; position: relative;">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>



						<!-- <div class="col-xs-12 col-sm-12 col-md-5 weekly_activity_status_div">
							<div class="panel">
								<div class="text-center" style="font-size:16px;">
									Current Week Activity Status
								</div>
								<div class="panel-body text-center activity-status-report-panel weekly_activity_status_div_panel">
									<div class="col-sm-12 col-md-12">
										<div class="box-body chart-responsive">
											<div class="chart" id="activity-status-report" style="height: 300px; position: relative;">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div> -->

					</div>


					<div class="col-sm-12 mb30 high_cost_customers_container" style="display:none;">
						<hr />
						<h4 class="text-center text-default" style="font-weight: 600;font-size: 16px;margin-bottom:4px;"> High Cost Customers</h4>
						
						<div class="row first_five_high_cost_customers" style="margin-top:20px"></div>
						<div class="row second_five_high_cost_customers" style="margin-top:20px"></div>
						<div class="high_cost_customers_loader row col-md-12 col-sm-12 text-success" style="text-align: center; font-size: 30px;display:none;">
							<i class='fa fa-spinner fa-spin' style="font-size: 35px !important;"></i>
						</div>

					</div>

				</div>
	    </div>
	</div>
</div>
<div class="bg-light lter wrapper-md legend_div" style="margin-top:-40px;">
   	<h4 class="m-n font-thin h4" id="bottom_patient_los" style="font-size:16px;font-weight:normal;margin-top:5px !important;">Length Of Stay - LOS</h4>
  	<h4 class="m-n font-thin h4" id="bottom_patient_days" style="font-size:16px;font-weight:normal;margin-top:5px !important;">Customer Days - CUS Days</h4>
</div>
<div class="bg-light lter wrapper-md hidden-print" style="margin-top:-20px;">
   <button class="btn btn-default" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>
<script type="text/javascript" src="<?php echo base_url('assets/js/reports.js')."?_=".date("YmdHis"); ?>"></script>
