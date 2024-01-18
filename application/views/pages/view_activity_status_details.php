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

</style>

<div class="page" ng-controller="FlotChartDemoCtrl">
	<div class="wrapper-md">

	    <div class="panel-report panel panel-default">
		    <?php /*<div class="panel-heading">
		      	<?php
		      		if($activity_status_name == "new_pt")
		      		{
		      			echo "New Customer";
		      		}
		      		else if($activity_status_name == "new_item")
		      		{
		      			echo "New Item";
		      		}
		      		else if($activity_status_name == "exchange")
		      		{
		      			echo "Exchange";
		      		}
		      		else if($activity_status_name == "pickup")
		      		{
		      			echo "Pickup";
		      		}
		      		else if($activity_status_name == "pt_move")
		      		{
		      			echo "CUS Move";
		      		}
		      		else
		      		{
		      			echo "Respite";
		      		}
		      	?>
		      	Activity Status
		    </div> */ ?>
		    <div class="panel-body">

				<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:20px;">
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
						<span class="pull-right">
							From
							<input type="text" class="form-control choose_date filter_from" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 40px;" value="<?php echo date("Y-m-d",strtotime($date_from)); ?>">
						</span>
					</div>
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
						<span class="pull-left">
							To
							<input type="text" class="form-control choose_date filter_to" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 25px;" value="<?php echo date("Y-m-d",strtotime($date_to)); ?>">
						</span>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:8px;">
					<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:14px;">
						<?php
							echo date('h:i A');
						?>
					</div>
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;">
						<?php
							if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user")
		        			{
		        				$selected = "";
				                $hospices = get_hospices_v2($this->session->userdata('user_location'));
                    			$companies = get_companies_v2($this->session->userdata('user_location'));
			            ?>
								<select class="form-control pull-center filter_activity_status_details" name="filter_activity_status_details" style="margin-top: 6px;border: 0px;font-size: 16px;font-weight: 600;text-align-last:center;">
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
						<?php
							}else{
								$hospice = $this->session->userdata('group_id');
								$hospice_info = get_hospice_name($hospice);
						?>
								<div style="font-weight:600;font-size:16px;margin-top:13px;"> <?php echo $hospice_info['hospice_name']; ?> </div>
						<?php
							}
						?>
					</div>
					<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

					</div>

				</div>
				<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:2px;">
					<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">
						<?php
							$current_date = date('Y-m-d');
							echo date("m/d/Y", strtotime($current_date))
						?>
					</div>
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;margin-top:7px;">
						<?php
							if($activity_status_name == "new_pt")
						  	{
						  		echo $activity_status_name_new = "New Customer";
						  		$activity_status_name_new_v2 = "new_pt";
									$sign_here = 1;
						  	}
						  	else if($activity_status_name == "new_item")
						  	{
						  		echo $activity_status_name_new = "New Items";
						  		$activity_status_name_new_v2 = "new_item";
									$sign_here = 3;
						  	}
						  	else if($activity_status_name == "exchange")
						  	{
						  		echo $activity_status_name_new = "Exchange";
						  		$activity_status_name_new_v2 = "exchange";
									$sign_here = 3;
						  	}
						  	else if($activity_status_name == "pickup")
						  	{
						  		echo $activity_status_name_new = "Pickup";
						  		$activity_status_name_new_v2 = "pickup";
									$sign_here = 2;
						  	}
						  	else if($activity_status_name == "pt_move")
						  	{
						  		echo $activity_status_name_new = "CUS Move";
						  		$activity_status_name_new_v2 = "pt_move";
									$sign_here = 1;
						  	}
						  	else
						  	{
						  		echo $activity_status_name_new = "Respite";
						  		$activity_status_name_new_v2 = "respite";
									$sign_here = 1;
						  	}
						?>
					</div>
					<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

					</div>

				</div>
				<div class="col-xs-12 col-sm-12 col-md-12" style="text-align:center; margin-top:4px;">
					<span id="viewed_current_date">
					<?php
						$current_date = date('Y-m-d');
						echo date("F d, Y", strtotime($current_date))
					?>
					</span>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:30px;">
				<input type="hidden" name="activity_status_name" id="activity_status_name" value="<?php echo $activity_status_name_new_v2; ?>">

				<?php
				  	if($sign_here == 1){
		    	?>
		    			<input type="hidden" class="viewed_activty_status_type" value="1">
				    	<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">

						  	<thead style="background-color:rgba(97, 101, 115, 0.05);">
						    	<tr>
						    		<th style="width: 65%">Customer Name</th>
						      		<th style="width: 35%">MR#</th>
						    	</tr>
						    </thead>
						    <tbody class="activity_status_tbody">
						    </tbody>
						</table>
				<?php
					}else{
				?>
						<input type="hidden" class="viewed_activty_status_type" value="2">
						<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">
						  	<thead style="background-color:rgba(97, 101, 115, 0.05);">
						    	<tr>
						    		<th style="width: 45%">Customer Name</th>
						      		<th style="width: 27%">MR#</th>
						      		<?php
						      			if($sign_here == 3){
						      		?>
						      				<th style="width: 28%">Item(s)</th>
						      		<?php
						      			}else if($sign_here == 2){
						      		?>
						      				<th style="width: 28%">Pickup Reason</th>
						      		<?php
						      			}
						      		?>
						    	</tr>
						    </thead>
						    <tbody class="activity_status_tbody"> </tbody>
						</table>
				<?php
					}
				?>
					<div class="pagination-container">
						<nav aria-label="...">
							<ul class="pager">
								<li class="previous disabled"><a href="javascript:;"><span aria-hidden="true">&larr;</span> Previous</a></li>
								<li class="next"><a href="javascript:;">Next <span aria-hidden="true">&rarr;</span></a></li>
							</ul>
						</nav>
					</div>
				</div>
			    <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:10px;margin-bottom:10px;">
			    	<?php
			    		if($sign_here == 2){
			    	?>
					    	<div class="col-xxs-6 col-xs-2 col-sm-2 col-md-2" style="margin-top:-2px;">
					    		Expired: <span id="total_patient_list_expired"> </span>
							</div>
							<div class="col-xxs-6 col-xs-3 col-sm-3 col-md-3" style="margin-top:-2px;">
								Discharged: <span id="total_patient_list_discharged"> </span>
							</div>
							<div class="col-xxs-6 col-xs-3 col-sm-3 col-md-3" style="margin-top:-2px;">
								Not Needed: <span id="total_patient_list_not_needed"> </span>
							</div>
							<div class="col-xxs-6 col-xs-2 col-sm-2 col-md-2" style="margin-top:-2px;">
								Revoked: <span id="total_patient_list_revoked">  </span>
							</div>
							<div class="col-xxs-12 col-xs-2 col-sm-2 col-md-2" style="text-align:right;">
								TOTAL: <span id="total_patient_list_queried"></span>
							</div>
					<?php
						}else{
					?>
							<div class="col-xxs-12 col-xs-10 col-sm-10 col-md-10">
							</div>
							<div class="col-xxs-12 col-xs-2 col-sm-2 col-md-2">
								TOTAL: <span id="total_patient_list_queried"></span>
							</div>
					<?php
						}
					?>
			    </div>
			</div>
		</div>
		<!-- <div class="lter wrapper-md" style="margin-top:-20px;margin-left:-20px;margin-bottom:-15px;">
		   <button class="btn btn-default print_activity_status" ><i class="fa fa-print"></i> Print</button>
		</div> -->
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(){

			var activity_status_content = function(page){
					var filter_from = $("body").find(".filter_from").val();
					var filter_to = $("body").find(".filter_to").val();
					var hospiceID = $("body").find(".filter_activity_status_details").val();
					var status_name = $("body").find("#activity_status_name").val();
					var activity_status_tbody = $("body").find(".activity_status_tbody");
					var total_patient_list_queried = $("body").find("#total_patient_list_queried");
					var total_patient_list_expired = $("body").find("#total_patient_list_expired");
					var total_patient_list_discharged = $("body").find("#total_patient_list_discharged");
					var total_patient_list_not_needed = $("body").find("#total_patient_list_not_needed");
					var total_patient_list_revoked = $("body").find("#total_patient_list_revoked");
					var viewed_current_date = $("body").find("#viewed_current_date");
					var viewed_activty_status_type = $("body").find(".viewed_activty_status_type").val();

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
						if(viewed_activty_status_type == 1)
						{
							activity_status_tbody.html("<tr><td colspan='2' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
						}
						else
						{
							activity_status_tbody.html("<tr><td colspan='3' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
						}

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

						var temp_html = "";
						var pagenum = 1;
						if(typeof(page)!="undefined"){
							pagenum = page*1;
						}
						$.post(base_url+"report/sort_activity_status_details/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ status_name+"/"+pagenum,"", function(response){
							var obj = $.parseJSON(response);

							obj.pagination_details.total_pages = obj.pagination_details.total_pages*1;
							obj.pagination_details.total_records = obj.pagination_details.total_records*1;
							obj.pagination_details.current_page = obj.pagination_details.current_page*1;
							if(obj.pagination_details.total_pages < 2){
								$('.pagination-container').addClass("hidden");
							}
							else{
									$('.pagination-container').removeClass("hidden");
									var current_page = obj.pagination_details.current_page*1;
									if(current_page-1==0){
										$('.pagination-container').find('.previous').addClass("disabled");
									}
									else{
										$('.pagination-container').find('.previous').removeClass("disabled");
										$('.pagination-container').find('.previous').attr("data-page",(current_page-1));
									}
									if(current_page+1>obj.pagination_details.total_pages){
										$('.pagination-container').find('.next').addClass("disabled");
									}
									else{
										$('.pagination-container').find('.next').removeClass("disabled");
										$('.pagination-container').find('.next').attr("data-page",(current_page+1));
									}
							}
							var queried_count = 0;
							if(obj.sign_here==3){
								obj.patient_list = obj.query_result;
							}

							if(obj.sign_here == 1)
							{
								for(var val in obj.patient_list)
								{
									if(obj.patient_list[val].organization_id != undefined) {
										temp_html += '<tr>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
													'</tr>';
									} else {
										temp_html += '<tr>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
													'</tr>';
									}
									queried_count++;
								}
								if(temp_html == "")
								{
									temp_html += '<tr>'+
													'<td colspan="2" style="text-align:center;"> No Customer. </td>'+
												'</tr>';
								}
								activity_status_tbody.html(temp_html);
								total_patient_list_queried.html(obj.pagination_details.total_records);
							}
							else if(obj.sign_here == 2)
							{
								var pickup_reason = "";
								var expired_count = 0;
								var not_needed_count = 0;
								var discharged_count = 0;
								var revoked_count = 0;
								for(var val in obj.patient_list)
								{
									if(obj.patient_list[val].pickup_sub == "not needed")
									{
										pickup_reason = " Not Needed";
										not_needed_count++;
									}
									else if(obj.patient_list[val].pickup_sub == "expired")
									{
										pickup_reason = " Expired";
										expired_count++;
									}
									else if(obj.patient_list[val].pickup_sub == "discharged")
									{
										pickup_reason = " Discharged";
										discharged_count++;
									}
									else if(obj.patient_list[val].pickup_sub == "revoked")
									{
										pickup_reason = " Revoked";
										revoked_count++;
									}

									if(obj.patient_list[val].organization_id != undefined) {
										temp_html += '<tr>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
														'<td style="color:#374045;"> '+pickup_reason+'</td>'+
													'</tr>';
									} else {
										temp_html += '<tr>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
														'<td style="color:#374045;"> '+pickup_reason+'</td>'+
													'</tr>';
									}
									queried_count++;
								}
								if(temp_html == "")
								{
									temp_html += '<tr>'+
													'<td colspan="3" style="text-align:center;"> No Customer. </td>'+
												'</tr>';
								}
								activity_status_tbody.html(temp_html);
								total_patient_list_queried.html(obj.pagination_details.total_records);
								total_patient_list_expired.html(obj.reasons_stats.expired);
								total_patient_list_discharged.html(obj.reasons_stats.discharged);
								total_patient_list_not_needed.html(obj.reasons_stats.notneed);
								total_patient_list_revoked.html(obj.reasons_stats.revoked);
							}
							else if(obj.sign_here == 3)
							{
								var item_name = "";
								for(var val in obj.patient_list)
								{
									if(obj.patient_list[val].organization_id != undefined) {
										temp_html += '<tr>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
														'<td style="color:#374045;"> '+obj.patient_list[val].total_items+'</td>'+
													'</tr>';
									} else {
										temp_html += '<tr>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
														'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
														'<td style="color:#374045;"> '+obj.patient_list[val].total_items+'</td>'+
													'</tr>';
									}
									queried_count++;
								}
								if(temp_html == "")
								{
									temp_html += '<tr>'+
													'<td colspan="3" style="text-align:center;"> No Customer. </td>'+
												'</tr>';
								}
								activity_status_tbody.html(temp_html);
								total_patient_list_queried.html(obj.pagination_details.total_records);
							}
						});
					}
			};

			activity_status_content();

			$('.pagination-container').on('click','.pager > li',function(){
					if(!$(this).hasClass("disabled")){
						 var page = $(this).attr("data-page")*1;
						 activity_status_content(page);
					}
			});

	    $('.choose_date').datepicker({
	    	dateFormat: 'yy-mm-dd',
         	onClose: function (dateText, inst) {
							activity_status_content();
        	}
      	});

	    $('select.filter_activity_status_details').on('change', function (e)
	  	{
	  		var hospiceID = this.value;
	  		var filter_from = $("body").find(".filter_from").val();
	  		var filter_to = $("body").find(".filter_to").val();
	  		var status_name = $("body").find("#activity_status_name").val();
	  		var activity_status_tbody = $("body").find(".activity_status_tbody");
	  		var total_patient_list_queried = $("body").find("#total_patient_list_queried");
	  		var total_patient_list_expired = $("body").find("#total_patient_list_expired");
			var total_patient_list_discharged = $("body").find("#total_patient_list_discharged");
			var total_patient_list_not_needed = $("body").find("#total_patient_list_not_needed");
			var total_patient_list_revoked = $("body").find("#total_patient_list_revoked");
	  		var viewed_current_date = $("body").find("#viewed_current_date");
	  		var viewed_activty_status_type = $("body").find(".viewed_activty_status_type").val();

	  		if(filter_from == "")
	  		{
	  			filter_from = 0;
	  		}
	  		if(filter_to == "")
	  		{
	  			filter_to = 0;
	  		}
	  		if(viewed_activty_status_type == 1)
  			{
  				activity_status_tbody.html("<tr><td colspan='2' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
  			}
  			else
  			{
  				activity_status_tbody.html("<tr><td colspan='3' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
  			}

	  		var temp_html = "";
	  		$.post(base_url+"report/sort_activity_status_details/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ status_name,"", function(response){
	  			var obj = $.parseJSON(response);
	  			var queried_count = 0;
					console.log(obj);

	  			if(obj.sign_here == 1)
	  			{
  					for(var val in obj.patient_list)
	  				{
	  					if(obj.patient_list[val].organization_id != undefined) {
							temp_html += '<tr>'+
		                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
		                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
		                                '</tr>';
						} else {
							temp_html += '<tr>'+
		                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
		                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
		                                '</tr>';
						}
	                    queried_count++;
	  				}
	  				if(temp_html == "")
	  				{
	  					temp_html += '<tr>'+
	  									'<td colspan="2" style="text-align:center;"> No Customer. </td>'+
	  								'</tr>';
	  				}
	  				activity_status_tbody.html(temp_html);
	  				total_patient_list_queried.html(obj.pagination_details.total_records);
	  			}
	  			else if(obj.sign_here == 2)
	  			{
	  				var pickup_reason = "";
	  				var expired_count = 0;
	  				var not_needed_count = 0;
	  				var discharged_count = 0;
	  				var revoked_count = 0;
	  				for(var val in obj.patient_list)
	  				{
	    				if(obj.patient_list[val].pickup_sub == "not needed")
	    				{
	    					pickup_reason = " Not Needed";
	    					not_needed_count++;
	    				}
	    				else if(obj.patient_list[val].pickup_sub == "expired")
	    				{
	    					pickup_reason = " Expired";
	    					expired_count++;
	    				}
	    				else if(obj.patient_list[val].pickup_sub == "discharged")
	    				{
	    					pickup_reason = " Discharged";
	    					discharged_count++;
	    				}
	    				else if(obj.patient_list[val].pickup_sub == "revoked")
	    				{
	    					pickup_reason = " Revoked";
	    					revoked_count++;
	    				}

	    				if(obj.patient_list[val].organization_id != undefined) {
							temp_html += '<tr>'+
	                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
	                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
	                                	'<td style="color:#374045;"> '+pickup_reason+'</td>'+
	                                '</tr>';
						} else {
							temp_html += '<tr>'+
	                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
	                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
	                                	'<td style="color:#374045;"> '+pickup_reason+'</td>'+
	                                '</tr>';
						}
	                    queried_count++;
	  				}
	  				if(temp_html == "")
	  				{
	  					temp_html += '<tr>'+
	  									'<td colspan="3" style="text-align:center;"> No Customer. </td>'+
	  								'</tr>';
	  				}
	  				activity_status_tbody.html(temp_html);
	  				total_patient_list_queried.html(obj.pagination_details.total_records);
	  				total_patient_list_expired.html(expired_count);
	  				total_patient_list_discharged.html(discharged_count);
	  				total_patient_list_not_needed.html(not_needed_count);
	  				total_patient_list_revoked.html(revoked_count);
	  			}
	  			else if(obj.sign_here == 3)
	  			{
	  				var item_name = "";
						obj.patient_list = obj.query_result;
						console.log(obj.patient_list);
	  				for(var val in obj.patient_list)
	  				{
	  					if(obj.patient_list[val].organization_id != undefined) {
							temp_html += '<tr>'+
	                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
	                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
	                                	'<td style="color:#374045;"> '+obj.patient_list[val].total_items+'</td>'+
	                                '</tr>';
						} else {
							temp_html += '<tr>'+
	                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
	                                	'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].ordered_by+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
	                                	'<td style="color:#374045;"> '+obj.patient_list[val].total_items+'</td>'+
	                                '</tr>';
						}
	                    queried_count++;
	  				}
	  				if(temp_html == "")
	  				{
	  					temp_html += '<tr>'+
	  									'<td colspan="3" style="text-align:center;"> No Customer. </td>'+
	  								'</tr>';
	  				}
	  				activity_status_tbody.html(temp_html);
	  				total_patient_list_queried.html(obj.pagination_details.total_records);
	  			}
  			});

	  	});

		$('body').on('click','.print_activity_status',function(){
	  		var filter_from = $("body").find(".filter_from").val();
  			var filter_to = $("body").find(".filter_to").val();
  			var hospiceID = $("body").find(".filter_activity_status_details").val();
  			var status_name = $("body").find("#activity_status_name").val();

  			if(filter_from == "")
	  		{
	  			filter_from = 0;
	  		}
	  		if(filter_to == "")
	  		{
	  			filter_to = 0;
	  		}

          	window.open(base_url+'report/print_activity_status_details/'+filter_from+'/'+filter_to+'/'+hospiceID+'/'+status_name);
	  	});

	});

</script>
