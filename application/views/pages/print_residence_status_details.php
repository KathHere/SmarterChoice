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
		.whole-container
	    {
	    	margin-top: -100px;
	    }
	    .panel-default
	    {
	    	border:none !important;
	    }
	    .residence_status_name_div
	    {
	    	margin-top: -12px !important;
	    }
	    .viewed_current_date_residence_status_div
	    {
	    	margin-top: -12px !important;
	    }
	    .residence_status_report_table_div
	    {
	    	margin-top: 12px !important;
	    }
	    .total_count_div
	    {
	    	margin-top: -10px !important;
	    }
	}

</style>

<div class="wrapper-md whole-container">
  	<div class="panel panel-default">
    	<div class="panel-body">

			<div class="page" ng-controller="FlotChartDemoCtrl">
				<div class="wrapper-md">

					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:14px;">
							<?php 
								echo date('h:i A');
							?>
						</div>
						<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;font-size: 16px;font-weight: 600;">
							<?php 
								if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user")
        						{
        							if(!empty($hospice_name))
									{
										echo $hospice_name['hospice_name'];
									}
									else
									{
										echo "Advantage Home Medical Services";
									}
								}
								else
								{
									$hospice = $this->session->userdata('group_id');
									$hospice_info = get_hospice_name($hospice);
									
									echo $hospice_info['hospice_name'];
								}
							?>
						</div>
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

						</div>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">
							<?php 
								$current_date = date('Y-m-d');
								echo date("m/d/Y", strtotime($current_date))
							?>
						</div>
						<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 residence_status_name_div" style="text-align:center;margin-top:-3px;">
							<?php 
								echo $status_name;
							?>
						</div>
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

						</div>
							
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 viewed_current_date_residence_status_div" style="text-align:center; margin-top:4px;">
						<span id="viewed_current_date_residence">
						<?php 
							$current_date = date('Y-m-d');
							$new_filter_from = explode("-", $filter_from);
							$new_filter_to = explode("-", $filter_to);
						
							if($filter_from == 0 || $filter_to == 0)
							{
								echo date("F d, Y", strtotime($current_date));
							}
							else
							{
								$separated_from = explode("-", $filter_from);
								$separated_to = explode("-", $filter_to);

								if($separated_from[1] == 1)
				  				{
				  					$month_name = "January";
				  				}
				  				else if($separated_from[1] == 2)
				  				{
				  					$month_name = "February";
				  				}
				  				else if($separated_from[1] == 3)
				  				{
				  					$month_name = "March";
				  				}
				  				else if($separated_from[1] == 4)
				  				{
				  					$month_name = "April";
				  				}
				  				else if($separated_from[1] == 5)
				  				{
				  					$month_name = "May";
				  				}
				  				else if($separated_from[1] == 6)
				  				{
				  					$month_name = "June";
				  				}
				  				else if($separated_from[1] == 7)
				  				{
				  					$month_name = "July";
				  				}
				  				else if($separated_from[1] == 8)
				  				{
				  					$month_name = "August";
				  				}
				  				else if($separated_from[1] == 9)
				  				{
				  					$month_name = "September";
				  				}
				  				else if($separated_from[1] == 10)
				  				{
				  					$month_name = "October";
				  				}
				  				else if($separated_from[1] == 11)
				  				{
				  					$month_name = "November";
				  				}
				  				else if($separated_from[1] == 12)
				  				{
				  					$month_name = "December";
				  				}

				  				if($separated_to[1] == 1)
				  				{
				  					$month_name_to = "January";
				  				}
				  				else if($separated_to[1] == 2)
				  				{
				  					$month_name_to = "February";
				  				}
				  				else if($separated_to[1] == 3)
				  				{
				  					$month_name_to = "March";
				  				}
				  				else if($separated_to[1] == 4)
				  				{
				  					$month_name_to = "April";
				  				}
				  				else if($separated_to[1] == 5)
				  				{
				  					$month_name_to = "May";
				  				}
				  				else if($separated_to[1] == 6)
				  				{
				  					$month_name_to = "June";
				  				}
				  				else if($separated_to[1] == 7)
				  				{
				  					$month_name_to = "July";
				  				}
				  				else if($separated_to[1] == 8)
				  				{
				  					$month_name_to = "August";
				  				}
				  				else if($separated_to[1] == 9)
				  				{
				  					$month_name_to = "September";
				  				}
				  				else if($separated_to[1] == 10)
				  				{
				  					$month_name_to = "October";
				  				}
				  				else if($separated_to[1] == 11)
				  				{
				  					$month_name_to = "November";
				  				}
				  				else if($separated_to[1] == 12)
				  				{
				  					$month_name_to = "December";
				  				}

								if(($separated_from[0] == $separated_to[0]) && ($separated_from[1] == $separated_to[1]) && ($separated_from[2] == $separated_to[2]))
								{
									echo $month_name." ".$separated_from[2].", ".$separated_from[0];
								}
								else if(($separated_from[0] == $separated_to[0]) && ($separated_from[1] == $separated_to[1]) && ($separated_from[2] != $separated_to[2]))
								{
									echo $month_name." ".$separated_from[2]." - ".$separated_to[2].", ".$separated_from[0];
								}
								else
								{
									echo $month_name." ".$separated_from[2].", ".$separated_from[0]." - ".$month_name_to." ".$separated_to[2].", ".$separated_to[0]; 
								}
							}
						?>
						</span>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-12 residence_status_report_table_div" style="margin-top:30px;">
						<input type="hidden" name="residence_status_name" id="residence_status_name" value="<?php echo $residence_status_name; ?>">
						<input type="hidden" name="residence_status_name_new" id="residence_status_name_new" value="<?php echo $residence_status_name_new; ?>">
						
						<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">
						  	<thead style="background-color:rgba(97, 101, 115, 0.05);">
						    	<tr>
						    		<th style="width: 65%">Customer Name</th>
						      		<th style="width: 35%">MR#</th>
						    	</tr>
						    </thead>
						    <tbody class="residence_status_tbody">
						    <?php 
						    	if(!empty($patient_list)){
						    		foreach($patient_list as $row){
					    	?>
								    	<tr>
								    		<td> <a class="" > <?php echo $row['p_lname']; ?>, <?php echo $row['p_fname']; ?> </a></td>
								    		<td> <a class="" > <?php echo $row['medical_record_id']; ?> </a> </td>
								    	</tr>
					    	<?php 
						    		}
								}else{
							?>
									<tr>
									 	<td colspan="2" style="text-align:center;"> No Customer. </td>
									</tr>
							<?php 
								}
							?>
						    </tbody>
						</table>	

					</div>	

				    <div class="col-xs-12 col-sm-12 col-md-12 total_count_div" style="margin-top:10px;">
				    	<div class="col-xxs-12 col-xs-10 col-sm-10 col-md-10">

						</div>
						<div class="col-xxs-12 col-xs-2 col-sm-2 col-md-2">
							TOTAL: <span id="total_patient_list_queried_residence"><?php echo count($patient_list); ?></span>
						</div>
				    </div>

				</div>
			</div>
		</div>
	</div>
</div>

<div class="bg-light lter wrapper-md">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>