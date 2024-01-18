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
		    <div class="panel-body">

				<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:20px;">
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
						<span class="pull-right">
							From
							<input type="text" class="form-control choose_date_residence filter_from_residence" value="<?php echo $filter_from; ?>" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 40px;">
						</span>
					</div>
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
						<span class="pull-left">
							To
							<input type="text" class="form-control choose_date_residence filter_to_residence" value="<?php echo $filter_to; ?>" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 40px;">
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
				                $hospices = get_hospices_v2($this->session->userdata('user_location'));
                    			$companies = get_companies_v2($this->session->userdata('user_location'));
			            ?>
								<select class="form-control pull-center filter_residence_status_details" name="filter_residence_status_details" style="margin-top: 6px;border: 0px;font-size: 16px;font-weight: 600;text-align-last:center;">
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
						  	if($residence_status_name == "assisted_living")
				      		{
				      			echo $residence_status_name_new = "Assisted Living";
				      			$residence_status_name = "assisted_living";
				      		}
				      		else if($residence_status_name == "group_home")
				      		{
				      			echo $residence_status_name_new = "Group Home";
				      			$residence_status_name = "group_home";
				      		}
				      		else if($residence_status_name == "hic_home")
				      		{
				      			echo $residence_status_name_new = "Hic Home";
				      			$residence_status_name = "hic_home";
				      		}
				      		else if($residence_status_name == "home_care")
				      		{
				      			echo $residence_status_name_new = "Home Care";
				      			$residence_status_name = "home_care";
				      		}
				      		else
				      		{
				      			echo $residence_status_name_new = "Skilled Nursing Facility";
				      			$residence_status_name = "skilled_nursing_facility";
				      		}
						?>
					</div>
					<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

					</div>

				</div>
				<div class="col-xs-12 col-sm-12 col-md-12" style="text-align:center; margin-top:4px;">
					<span id="viewed_current_date_residence">
					<?php
						$current_date = date('Y-m-d');
						echo date("F d, Y", strtotime($current_date))
					?>
					</span>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:30px;">
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
					    </tbody>
					</table>
					<div class="pagination-container">
						<nav aria-label="...">
							<ul class="pager">
								<li class="previous disabled"><a href="javascript:;"><span aria-hidden="true">&larr;</span> Previous</a></li>
								<li class="next"><a href="javascript:;">Next <span aria-hidden="true">&rarr;</span></a></li>
							</ul>
						</nav>
					</div>
				</div>

			    <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:10px;">
			    	<div class="col-xxs-12 col-xs-10 col-sm-10 col-md-10">

					</div>
					<div class="col-xxs-12 col-xs-2 col-sm-2 col-md-2">
						TOTAL: <span id="total_patient_list_queried_residence"></span>
					</div>
			    </div>
	    	</div>
		</div>
		<!-- <div class="bg-light lter wrapper-md" style="margin-top:-20px;margin-left:-20px;margin-bottom:-15px;">
		   <button class="btn btn-default print_residence_status"><i class="fa fa-print"></i> Print</button>
		</div> -->
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(){

			var residence_content = function(page){
				if(typeof(page)=="undefined"){
					page = 1;
				}
				var filter_from_residence = $("body").find(".filter_from_residence").val();
				var filter_to_residence = $("body").find(".filter_to_residence").val();
				var hospiceID = $("body").find(".filter_residence_status_details").val();
				var status_name = $("body").find("#residence_status_name").val();
				var residence_status_tbody = $("body").find(".residence_status_tbody");
				var total_patient_list_queried_residence = $("body").find("#total_patient_list_queried_residence");
				var viewed_current_date_residence = $("body").find("#viewed_current_date_residence");

				if(filter_from_residence == "")
				{
					filter_from_residence = 0;
				}
				if(filter_to_residence == "")
				{
					filter_to_residence = 0;
				}

				if(filter_from_residence != 0 && filter_to_residence != 0)
				{
					residence_status_tbody.html("<tr><td colspan='2' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
					var new_viewed_date = "";
					var month_name = "";
					var separated_from = filter_from_residence.split(/\s*\-\s*/g);
					var separated_to = filter_to_residence.split(/\s*\-\s*/g);

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
						new_viewed_date = month_name+" "+separated_from[2]+", "+separated_from[0] +" - "+month_name_to+" "+separated_to[2]+", "+separated_to[0];
					}
					// new_viewed_date = month_name+" "+separated_from[2]+", "+separated_from[0];
					viewed_current_date_residence.html(new_viewed_date);

					var temp_html = "";
					$.post(base_url+"report/sort_residence_status_details/" + filter_from_residence +"/"+ filter_to_residence +"/"+ hospiceID +"/"+ status_name+"/"+page,"", function(response){
						var obj = $.parseJSON(response);
						var queried_count = 0;
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
						for(var val in obj.patient_list)
						{
							temp_html += '<tr>'+
																		'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+' </a></td>'+
																		'<td> <a class="" href="'+base_url+'order/patient_profile/'+obj.patient_list[val].medical_record_id+'/'+obj.patient_list[val].organization_id+'" target="_blank"> '+obj.patient_list[val].medical_record_id+' </a></td>'+
																	'</tr>';
											queried_count++;
						}
						if(temp_html == "")
						{
							temp_html += '<tr>'+
											'<td colspan="2" style="text-align:center;"> No Customer. </td>'+
										'</tr>';
						}
						residence_status_tbody.html(temp_html);
						total_patient_list_queried_residence.html(obj.pagination_details.total_records);
					});
				}
			}
			residence_content();
	    $('.choose_date_residence').datepicker({
	    	dateFormat: 'yy-mm-dd',
         	onClose: function (dateText, inst) {
            	residence_content();
        	}
      	});

		$('select.filter_residence_status_details').on('change', function (e)
	  	{
	  	  residence_content();
	  	});

	  	$('body').on('click','.print_residence_status',function(){
	  		var filter_from_residence = $("body").find(".filter_from_residence").val();
  			var filter_to_residence = $("body").find(".filter_to_residence").val();
  			var hospiceID = $("body").find(".filter_residence_status_details").val();
  			var status_name = $("body").find("#residence_status_name").val();

  			if(filter_from_residence == "")
	  		{
	  			filter_from_residence = 0;
	  		}
	  		if(filter_to_residence == "")
	  		{
	  			filter_to_residence = 0;
	  		}

          	window.open(base_url+'report/print_residence_status_details/'+filter_from_residence+'/'+'/'+hospiceID+'/'+status_name);
	  	});

			$('.pagination-container').on('click','.pager > li',function(){
					if(!$(this).hasClass("disabled")){
						 var page = $(this).attr("data-page")*1;
						 residence_content(page);
					}
			});
	});

</script>
