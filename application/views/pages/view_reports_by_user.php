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

	@media (max-width: 767px){
		.sort_by_hospice_word{
			margin-top: 9px !important;
		}

		.sort_by_hospice_html{
			margin-top: 3px !important;
			padding-left: 9px !important;
		}

		.sort_by_hospice_select{
			width: 63% !important;
		}

		.sort_by_date_word{
			margin-top: 12px !important;
		}

		.sort_by_date_html{
			margin-top: 8px !important;
		}
	}

	@media (max-width: 455px){
		.sort_by_hospice_select{
			width: 62% !important;
		}
	}

	@media print{
		.whole-container
	    {
	    	margin-top: -80px;
	    }
	    .panel-default
	    {
	    	border:none !important;
	    }
	    .orders_by_user_table
	    {
	    	margin-bottom: 15px !important;
	    }
	    .orders_by_user_div
	    {
	    	margin-top: 0px !important;
	    }
	    .panel-heading.staff_entries_header
	    {
	    	display: block !important;
	    	visibility: visible !important;
	    	opacity: 1 !important;
	    	position: relative !important;
	    	background: #999 !important;
	    	z-index: 1 !important;
	    	color:#000 !important;
	    	box-shadow: none !important;
	    	padding:0;

	    }
	    .panel-heading.staff_entries_header a{
	    	display:block !important;
	    	color:#000 !important;
	    	position: relative !important;
	    }
	    .staff_entries_table
	    {
	    	border-right: 1px solid #eaeff0;
	    	border-left: 1px solid #eaeff0;
	    	border-bottom: 1px solid #eaeff0;
	    }
	}

	/* Accordion */
	.panel-group > .panel > .panel-heading > a:not(.collapsed) > h4 > span{
		transform:rotate(-180deg);
	}

</style>

<div class="page whole-container" ng-controller="FlotChartDemoCtrl">
	<div class="wrapper-md">

	<div class="panel-report panel panel-default">
		    <div class="panel-heading hidden-print">
		      	Staff Entries
		    </div>
		    <div class="panel-body">
		    	<div class="col-xs-12 col-sm-12 col-md-12" style="">
					<div class="col-xxs-12 col-xs-12 col-sm-3 col-md-3" style="text-align:left;margin-top:8px; padding-left:0px;">
						<?php
                            echo date('h:i A');
                        ?>
					</div>
					<div class="col-xxs-12 col-xs-0 col-sm-6 col-md-6" style="text-align:center;">

					</div>
					<div class="col-xxs-12 col-xs-0 col-sm-3 col-md-3">

					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-12" style="">
					<div class="col-xxs-12 col-xs-12 col-sm-3 col-md-3" style="padding-left:0px;">
						<?php
                            $current_date = date('Y-m-d');
                            echo date('m/d/Y', strtotime($current_date));
                        ?>
					</div>
					<?php
                        if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == "biller" || $this->session->userdata('account_type') == "sales_rep" || $this->session->userdata('account_type') == "distribution_supervisor" || $this->session->userdata('account_type') == "rt") {
                            ?>
							<div class="col-xxs-12 col-xs-12 col-sm-2 col-md-2" style="">

							</div>
							<div class="col-xxs-4 col-xs-4 col-sm-1 col-md-1 hidden-print sort_by_hospice_word" style="padding:0;margin:0;text-align:center;margin-top:-8px;">
								Sort:
							</div>
							<div class="col-xxs-8 col-xs-8 col-sm-3 col-md-3 hidden-print sort_by_hospice_html" style="margin-top:-14px;text-align:left;padding:0px;">
								<?php
                                    $hospices = get_hospices_v2($this->session->userdata('user_location'));
                            $companies = get_companies_v2($this->session->userdata('user_location')); ?>
								<select class="form-control filter_staff_entries_by_hospice hidden-print sort_by_hospice_select select2-ready" name="filter_staff_entries_by_hospice" style="border: 0px;text-align-last:center;">
									<option value="all">View All Customers</option>
					                <optgroup label="Hospices">
					                    <?php
                                          foreach ($hospices as $hospice) :
                                            if ($hospice['hospiceID'] != 13) {
                                                ?>
				                          		<option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospice_selected == $hospice['hospiceID']) {
                                                    echo 'selected';
                                                } ?> ><?php echo $hospice['hospice_name']; ?></option>
					                    <?php
                                            }
                            endforeach; ?>
					                </optgroup>

					                <?php
                                    if ($this->session->userdata('user_location') == 1) {
                                        ?>
				                    	<optgroup label="Commercial Account">
					                        <?php
                                              foreach ($companies as $company):
                                                if ($company['hospiceID'] != 13) {
                                                    ?>
				                              		<option value="<?php echo $company['hospiceID']; ?>" <?php if ($hospice_selected == $company['hospiceID']) {
                                                        echo 'selected';
                                                    } ?> ><?php echo $company['hospice_name']; ?></option>
					                        <?php
                                                }
                                        endforeach; ?>
					                    </optgroup>

					                    <option disabled="disabled">----------------------------------------</option>

					                <?php
                                    }
                            foreach ($hospices as $hospice) :
                                          if ($hospice['hospiceID'] == 13) {
                                              ?>
					                        <option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospice_selected == $hospice['hospiceID']) {
                                                  echo 'selected';
                                              } ?> ><?php echo $hospice['hospice_name']; ?></option>
					                <?php
                                          }
                            endforeach; ?>
								</select>
								<?php
                                if ($hospice_selected == 0) {
                                    $hospice_name = 'Advantage Home Medical Services';
                                } else {
                                    $queried_hospice = get_hospice_name($hospice_selected);
                                    $hospice_name = $queried_hospice['hospice_name'];
                                }
                            echo "<span class='visible-print-block'>".$hospice_name.'</span>'; ?>
							</div>
					<?php
                        } else {
                            ?>
							<div class="col-xxs-12 col-xs-12 col-sm-6 col-md-6" style="">

							</div>
					<?php
                        }
                    ?>

					<div class="col-xxs-4 col-xs-4 col-sm-1 col-md-1 hidden-print sort_by_date_word" style="padding:0;margin:0;text-align:center;margin-top:-8px;">
						Date:
					</div>
					<div class="col-xxs-8 col-xs-8 col-sm-2 col-md-2 hidden-print sort_by_date_html" style="margin-top:-14px;text-align:left;padding-left:10px;">
						<?php
                            if (!empty($date_choosen)) {
                                ?>
								<input type="text" value="<?php echo $date_choosen; ?>" class="search_datepicker date_reports_by_user form-control hidden-print" style="width:65%;">
								<?php
                                echo "<span class='visible-print-block'>".date('F d, Y', strtotime($date_choosen)).'</span>'; ?>
						<?php
                            } else {
                                ?>
								<input type="text" class="search_datepicker date_reports_by_user form-control hidden-print" style="width:65%;">
								<?php
                                echo "<span class='visible-print-block'>".date('F d, Y', strtotime($current_date)).'</span>'; ?>
						<?php
                            }
                        ?>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12 orders_by_user_div visible-print-block" style="text-align:center;">
					<?php
                        if ($hospice_selected == 0) {
                            if ($this->session->userdata('account_type') != 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == "biller" || $this->session->userdata('account_type') == "sales_rep" || $this->session->userdata('account_type') == "distribution_supervisor" || $this->session->userdata('account_type') == "rt") {
                                $queried_hospice = get_hospice_name($this->session->userdata('group_id'));
                                $hospice_name = $queried_hospice['hospice_name'];
                            } else {
                                $hospice_name = 'Advantage Home Medical Services';
                            }
                        } else {
                            $queried_hospice = get_hospice_name($hospice_selected);
                            $hospice_name = $queried_hospice['hospice_name'];
                        }
                        echo "<span class='visible-print-block'>".$hospice_name.'</span>';
                        if (!empty($date_choosen)) {
                            echo "<span class='visible-print-block'>".date('F d, Y', strtotime($date_choosen)).'</span>';
                        } else {
                            echo "<span class='visible-print-block'>".date('F d, Y', strtotime($current_date)).'</span>';
                        }
                    ?>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12 orders_by_user_div" style="margin-top:40px;">

					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
						<?php
                            if (!empty($staff_entries)) {
                                foreach ($staff_entries as $key => $value) {
                                    ?>
									<div class="panel panel-default" style="margin-top: 15px !important;">
									    <div class="panel-heading staff_entries_header" role="tab" id="headingOne">
									        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne_<?php echo $key; ?>" aria-expanded="true" aria-controls="collapseOne">
									        	<?php
                                                    if ($this->session->userdata('account_type') != 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == "biller" || $this->session->userdata('account_type') == "sales_rep" || $this->session->userdata('account_type') == "distribution_supervisor" || $this->session->userdata('account_type') == "rt") {
                                                        ?>
									        			<h4 class="panel-title staff_name">
									        				<?php
                                                                echo $value['entry_count'];
                                                        if ($value['entry_count'] == 1) {
                                                            echo ' Entry - ';
                                                        } else {
                                                            echo ' Entries - ';
                                                        }
                                                        echo $value['lastname'].', '.$value['firstname']; ?>
									        				<span class="pull-right hidden-print"><i class="caret"></i></span>
									        			</h4>
									        	<?php
                                                    } else {
                                                        if ($value['group_id'] == 0) {
                                                            ?>
					                            			<h4 class="panel-title staff_name">
										        				<?php
                                                                    echo $value['entry_count'];
                                                            if ($value['entry_count'] == 1) {
                                                                echo ' Entry - ';
                                                            } else {
                                                                echo ' Entries - ';
                                                            }
                                                            echo $value['lastname'].', '.$value['firstname'].' '; ?>
										        				(DME Staff)
										        				<span class="pull-right hidden-print"><i class="caret"></i></span>
										        			</h4>
					                            <?php
                                                        } else {
                                                            $user_queried_hospice = get_hospice_name_v2($value['group_id']);
                                                            $user_hospice_name = $user_queried_hospice['hospice_name']; ?>
									        				<h4 class="panel-title staff_name">
										        				<?php
                                                                    echo $value['entry_count'];
                                                            if ($value['entry_count'] == 1) {
                                                                echo ' Entry - ';
                                                            } else {
                                                                echo ' Entries - ';
                                                            }
                                                            echo $value['lastname'].', '.$value['firstname'].' ('.$user_hospice_name.')'; ?>
										        				<span class="pull-right hidden-print"><i class="caret"></i></span>
										        			</h4>
									        	<?php
                                                        }
                                                    } ?>

									        </a>
									    </div>
									    <div id="collapseOne_<?php echo $key; ?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="headingOne">
									      	<div class="panel-body text-center" style="padding-top:5px !important;padding-bottom:5px !important;">

									      		<table class="table m-b-none staff_entries_table">
										      		<thead>
											          	<tr>
									                        <th style="width:13%;text-align:center;">Customer Name</th>
									                        <th style="width:12%;text-align:center;">MR#</th>
									                        <th style="width:10%;text-align:center;">WO#</th>
												            <th style="width:10%;text-align:center;">Activity Type</th>
											          	</tr>
											        </thead>
											        <tbody class="">
											        	<?php
                                                            foreach ($value['entries'] as $inside_key => $inside_value) {
                                                                ?>
													        	<tr style="">
													        		<td class="">
													        			<a class="hidden-print" href="<?php echo base_url('order/patient_profile/'.$inside_value['medical_record_id'].'/'.$inside_value['organization_id']); ?>" target="_blank">
													        				<?php echo $inside_value['p_lname']; ?>, <?php echo $inside_value['p_fname']; ?>
													        			</a>
												        				<span class="visible-print-block">
												        					<?php echo $inside_value['p_lname']; ?>, <?php echo $inside_value['p_fname']; ?>
												        				</span>
													        		</td>
													        		<td class="order_inquiry_td">
													        			<a class="hidden-print" href="<?php echo base_url('order/patient_profile/'.$inside_value['medical_record_id'].'/'.$inside_value['organization_id']); ?>" target="_blank">
													        				<?php echo $inside_value['medical_record_id']; ?>
													        			</a>
													        			<span class="visible-print-block">
													        				<?php echo $inside_value['medical_record_id']; ?>
													        			</span>
													        		</td>
													        		<td class="order_inquiry_td">
													        			<a class="hidden-print" href="<?php echo base_url('order/patient_profile/'.$inside_value['medical_record_id'].'/'.$inside_value['organization_id']); ?>" target="_blank">
													        				<?php echo substr($inside_value['order_uniqueID'], 4, 10); ?>
													        			</a>
													        			<span class="visible-print-block">
													        				<?php echo substr($inside_value['order_uniqueID'], 4, 10); ?>
													        			</span>
													        		</td>
													        		<td class="order_inquiry_td">
													        			<?php
                                                                            if ($inside_value['activity_name'] == 'Delivery') {
                                                                                $check_first_order = get_patient_first_order_status($inside_value['patientID']);
                                                                                if ($check_first_order['order_uniqueID'] == $inside_value['order_uniqueID']) {
                                                                                    echo 'New Customer';
                                                                                } else {
                                                                                    echo $inside_value['activity_name'];
                                                                                }
                                                                            } else {
                                                                                echo $inside_value['activity_name'];
                                                                            } ?>
													        		</td>
													        	</tr>
											        	<?php
                                                            } ?>
											        </tbody>
											    </table>

									      	</div>
									    </div>
									</div>
						<?php
                                }
                            } else {
                                ?>
								<div colspan="4" style="text-align:center;font-size: 18px;"> No Entry. </div>
						<?php
                            }
                        ?>
					</div>
				</div>
		    </div>
		</div>
		<div class="bg-light lter wrapper-md" style="margin-top:-20px;margin-left:-20px;margin-bottom:-15px;">
		   <button class="btn btn-default" onclick="window.print()" ><i class="fa fa-print"></i> Print</button>
		</div>
	</div>
</div>


<script type="text/javascript">

	$(document).ready(function(){
		$('.date_reports_by_user').datepicker({
			dateFormat: 'yy-mm-dd',
         	onClose: function (dateText, inst) {
         		var date = $("body").find(".date_reports_by_user").val();
         		var hospiceID = $("body").find(".filter_staff_entries_by_hospice").val();

         		if(hospiceID == undefined)
         		{
         			console.log(hospiceID);
         			hospiceID = 0;
         		}
         		window.location.href = base_url+"report/get_reports_by_user_filtered/" + date + "/" + hospiceID;
         	}
        });

        $('body').on('change','.filter_staff_entries_by_hospice',function(){
			var hospiceID = $(this).val();
			var date = $("body").find(".date_reports_by_user").val();
			if(date.length < 1)
			{
				date = "0000-00-00";
			}
         	window.location.href = base_url+"report/get_reports_by_user_filtered/" + date + "/" + hospiceID;
        });

        $('.collapse').collapse();
	});

</script>


