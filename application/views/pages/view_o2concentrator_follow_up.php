<style type="text/css">
	@media print {
    input::-webkit-outer-spin-button,  
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
		}
		.inactive_patient
		{
			color:rgba(0, 0, 0, 0.45) !important;
		}
		.active_patient
		{
			color: rgba(0, 0, 0, 0.9) !important;
		}
		
		.bootstrap-dt-container {
			display: none !important;
			height: 0px !important;
		}
		.panel-body {
			margin-top: -80px;
		}
		.panel-default {
			border:none !important;
		}
		.orders_by_user_table {
			margin-bottom: 15px !important;
		}
		.orders_by_user_div {
			margin-top: 0px !important;
		}

		.date-time-oxy {
			width: 28% !important;
		}

		.AHMS-oxy {
			width: 45% !important;
		}

		.OCFUP-oxy {
			width: 45% !important;
		}

		table.dataTable thead .sorting:after {
			display: none !important;
		}

		table.dataTable thead .sorting_asc:after {
			display: none !important;
		}

		a[href]:after {
    	content: none !important;
  	}
	}

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
		/* color: #000 !important;
		font-size: 16px; */
	}
	@media (max-width: 580px){
		.panel-report .col-xxs-12{
			width:100%;
		}
	}
	.follow_up_date_calendar
	{
		cursor: pointer;
		font-size:15px !important;
	}
	.follow_up_date_calendar_inactive
	{
		cursor: not-allowed;
		font-size:15px !important;
	}
	.datatable_table_o2_oxygen_follow_up_report
	{
		border:1px solid rgba(0, 0, 0, 0.08) !important;
	}
	.inactive_patient
	{
		color:rgba(0, 0, 0, 0.45) !important;
	}
	.active_patient
	{
		color: rgba(0, 0, 0, 0.9) !important;
	}

	.dataTables_wrapper .dataTables_processing {
		background: #bfbfbff5 !important;
		background-color: #bfbfbff5 !important;
		color:#fff !important;
		height: 60px !important;
		z-index:1;
	}

	.o2_concentrator_follow_up_header
	{
		background-color: rgba(97, 101, 115, 0.05) !important;
	}

</style>


<div class="page" ng-controller="FlotChartDemoCtrl">
	<div class="wrapper-md">

	    <div class="panel-report panel panel-default">
		    <div class="panel-heading hidden-print">
		      	Oxygen Concentrator Follow Up
		    </div>
		    <div class="panel-body">

		    	<div class="col-xs-12 col-sm-12 col-md-12 hidden-print" style="margin-top:20px;">
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
						<span class="pull-right">
							From
							<input type="text" class="form-control choose_date_o2concentrator_follow_up filter_from_o2concentrator_follow_up" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 40px;">
						</span>
					</div>
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
						<span class="pull-left">
							To
							<input type="text" class="form-control choose_date_o2concentrator_follow_up filter_to_o2concentrator_follow_up" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 25px;">
						</span>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:8px;">
					<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 date-time-oxy" style="margin-top:14px;">
						<?php
              echo date('h:i A');
            ?>
					</div>
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 AHMS-oxy" style="text-align:center;margin-top:10px;">
						<span style="font-weight:bold; font-size:16px;"> Advantage Home Medical Services </span>
					</div>
					<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:2px;">
					<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 date-time-oxy">
						<?php
                            $current_date = date('Y-m-d');
                            echo date('m/d/Y', strtotime($current_date));
                        ?>
					</div>
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 OCFUP-oxy" style="text-align:center;margin-top:0px;">
						Oxygen Concentrator Follow Up Report
					</div>
					<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12" style="text-align:center; margin-top:4px;">
					<span id="viewed_current_date_o2concentrator_follow_up">
					<?php
                        $from_date = date('Y-m').'-01';
                        $to_date = date('Y-m-t');
                        echo date('F d, Y', strtotime($from_date)).' - '.date('F d, Y', strtotime($to_date));
                    ?>
					</span>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:30px;position:relative !important;padding:0px;">

					<table class="table bg-white b-a col-md-12 datatable_table_o2_oxygen_follow_up_report" id="" style="margin:0px;">
					  	<thead style="background-color:rgba(97, 101, 115, 0.05);">
					    	<tr>
					    		<th style="width: 65%">Customer Name</th>
					      	<th style="width: 35%">Due Date</th>
					    	</tr>
					    </thead>
					    <tbody class="o2concentrator_follow_up_tbody">

					    </tbody>
					</table>

				</div>

		    </div>
		</div>
		<div class="bg-light lter wrapper-md" style="margin-top:-20px;margin-left:-20px;margin-bottom:-15px;">
		   <button class="btn btn-default print_o2concentrator_follow_up" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
		</div>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(){
		$('body').on('click','.follow_up_date_calendar',function(){
			var follow_up_id = $(this).attr("data-follow-up-id");

			modalbox(base_url + 'order/set_o2_concentrator_follow_up_date/'+follow_up_id,{
			    header:"Edit Follow Up Date",
			    button: false
			});

			//this will update the follow up date of the selected concentrator for the specific customer
			$('body').on('click','.save-follow-up-date-btn',function(){
					var form_data = $('#edit_follow_up_date_form').serialize();

				$.post(base_url + 'order/update_o2_concentrator_follow_up_date/' + follow_up_id,form_data, function(response){
					var obj = $.parseJSON(response);

					if(obj['error'] == 0)
					{
						me_message_v2({error:0,message:"Success! Follow up date changed."});
						setTimeout(function(){
							window.location.reload();
						},1500);
					}
				});
			});
		});

		var filter_from_follow_up = "";
		var filter_to_follow_up = "";
		 var table = $('.datatable_table_o2_oxygen_follow_up_report').DataTable({
			"lengthMenu": [10,25,50,75,100,200,300,500],
			"pageLength": 10,
			"processing": true,
			"serverSide": true,
			"responsive": true,
			"deferRender": true,
			"ajax": {
				url: base_url+"report/get_o2_concentrator_follow_up_date/0/"+filter_from_follow_up+"/"+filter_to_follow_up
			},
			"columns": [
				{ "data": "customer_name_data" },
				{ "data": "due_date_data" }
			],
			"order": [[ 1, 'asc' ]],
			"columnDefs":[
				{
					"targets": 0,
					"searchable": true,
					"orderable": true
				},
				{
					"targets": 1,
					"searchable": true,
					"orderable": true
				}
			]
		});

		$('.choose_date_o2concentrator_follow_up').datepicker({
			dateFormat: 'yy-mm-dd',
			onClose: function (dateText, inst) {
				var hospiceID = 0;
				var filter_from_follow_up = $("body").find(".filter_from_o2concentrator_follow_up").val();
				var filter_to_follow_up = $("body").find(".filter_to_o2concentrator_follow_up").val();
				var viewed_current_date_o2concentrator_follow_up = $("body").find("#viewed_current_date_o2concentrator_follow_up");

				var new_viewed_date = "";
				var month_name = "";
				var month_name_to = "";
				var separated_from = filter_from_follow_up.split(/\s*\-\s*/g);
				var separated_to = filter_to_follow_up.split(/\s*\-\s*/g);

				if(filter_from_follow_up != 0 && filter_to_follow_up != 0)
				{
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
					viewed_current_date_o2concentrator_follow_up.html(new_viewed_date);

					table.destroy();
					$('.datatable_table_o2_oxygen_follow_up_report').empty();

					table = $('.datatable_table_o2_oxygen_follow_up_report').DataTable({
						"language": {
               "processing": "Retrieving data. Please wait..."
            },
						"lengthMenu": [10,25,50,75,100,200,300,500],
						"pageLength": 10,
						"processing": true,
						"serverSide": true,
						"responsive": true,
						"deferRender": true,
						"ajax": {
							url: base_url+"report/get_o2_concentrator_follow_up_date/0/"+filter_from_follow_up+"/"+filter_to_follow_up
						},
						"columns": [
							{ "title": "Customer Name", "data": "customer_name_data" },
							{ "title": "Due Date", "data": "due_date_data" }
						],
						"order": [[ 1, 'asc' ]],
						"columnDefs":[
							{
								"targets": 0,
								"searchable": true,
								"orderable": true
							},
							{
								"targets": 1,
								"searchable": true,
								"orderable": true
							}
						]
					});
					$( table.table().header() ).addClass('o2_concentrator_follow_up_header');
				}
			}
		});

		// $('.datatable_table_o2_oxygen_follow_up_report thead').addClass('o2_concentrator_follow_up_header');




				// var o2concentrator_follow_up_tbody = $("body").find(".o2concentrator_follow_up_tbody");
				// var temp_html = "";
				// var viewed_current_date_o2concentrator_follow_up = $("body").find("#viewed_current_date_o2concentrator_follow_up");

				// if(filter_from_follow_up == "")
				// {
				// 	filter_from_follow_up = 0;
				// }
				// if(filter_to_follow_up == "")
				// {
				// 	filter_to_follow_up = 0;
				// }

				// if(filter_from_follow_up != 0 && filter_to_follow_up != 0)
				// {
				// 	o2concentrator_follow_up_tbody.html("<tr><td colspan='2' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
				// 	$.post(base_url+"report/sort_o2_concentrator_follow_up_date/" + hospiceID +"/"+ filter_from_follow_up +"/"+ filter_to_follow_up,"", function(response){
				// 		var obj = $.parseJSON(response);

					// for(var val in obj.follow_up_list)
					// 	{
					// 		var separated_date = obj.follow_up_list[val].follow_up_date.split(/\s*\-\s*/g);
					// 	var dd = Number(separated_date[2]);
					// 	var mm = Number(separated_date[1]);
					// 	var yyyy = separated_date[0];

					// 	if(dd<10){
					// 			dd='0'+dd;
					// 	}
					// 	if(mm<10){
					// 			mm='0'+mm;
					// 	}
					// 	var final_date = mm+'/'+dd+'/'+yyyy;

						// if(obj.follow_up_list[val].patient_active_sign == 1)
						// {
						// 	temp_html += '<tr>'+
						// 													'<td> <a class="active_patient" href="'+base_url+'order/patient_profile/'+obj.follow_up_list[val].medical_record_id+'/'+obj.follow_up_list[val].ordered_by+'" target="_blank"> '+obj.follow_up_list[val].p_fname+' '+obj.follow_up_list[val].p_lname+' </a></td>'+
						// 													'<td class="active_patient"><i class="fa fa-calendar follow_up_date_calendar" data-follow-up-id="'+obj.follow_up_list[val].follow_up_id+'" aria-hidden="true"></i> &nbsp;'+final_date+' </a></td>'+
						// 												'</tr>';
						// }
						// else
						// {
						// 	temp_html += '<tr>'+
						// 													'<td> <a class="inactive_patient" href="'+base_url+'order/patient_profile/'+obj.follow_up_list[val].medical_record_id+'/'+obj.follow_up_list[val].ordered_by+'" target="_blank"> '+obj.follow_up_list[val].p_fname+' '+obj.follow_up_list[val].p_lname+' </a></td>'+
						// 													'<td class="inactive_patient"><i class="fa fa-calendar follow_up_date_calendar_inactive" data-follow-up-id="'+obj.follow_up_list[val].follow_up_id+'" aria-hidden="true"></i> &nbsp;'+final_date+' </a></td>'+
						// 												'</tr>';
						// }
						// }
				// }

		// var datatable_initialized = $('.datatable_table_o2_oxygen_follow_up_report').DataTable( {
	 //        "order": [[ 1, "asc" ]]
	 //    } );

	   //  $('select.filter_o2concentrator_follow_up').on('change', function (e)
	  	// {
	  	// 	var hospiceID = this.value;
	  	// 	var filter_from_follow_up = $("body").find(".filter_from_o2concentrator_follow_up").val();
	  	// 	var filter_to_follow_up = $("body").find(".filter_to_o2concentrator_follow_up").val();
	  	// 	var o2concentrator_follow_up_tbody = $("body").find(".o2concentrator_follow_up_tbody");
	  	// 	var temp_html = "";

	  	// 	if(filter_from_follow_up == "")
	  	// 	{
	  	// 		filter_from_follow_up = 0;
	  	// 	}
	  	// 	if(filter_to_follow_up == "")
	  	// 	{
	  	// 		filter_to_follow_up = 0;
	  	// 	}

	  	// 	$.post(base_url+"report/sort_o2_concentrator_follow_up_date/" + hospiceID +"/"+ filter_from_follow_up +"/"+ filter_to_follow_up,"", function(response){
	  	// 		var obj = $.parseJSON(response);

				// for(var val in obj.follow_up_list)
  		// 		{
  		// 			if(obj.follow_up_list[val].patient_active_sign == 1)
				// 	{
				// 		temp_html += '<tr>'+
	   //                              	'<td> <a class="active_patient" href="'+base_url+'order/patient_profile/'+obj.follow_up_list[val].medical_record_id+'/'+obj.follow_up_list[val].ordered_by+'" target="_blank"> '+obj.follow_up_list[val].p_fname+' '+obj.follow_up_list[val].p_lname+' </a></td>'+
	   //                              	'<td class="active_patient"><i class="fa fa-calendar follow_up_date_calendar" data-follow-up-id="'+obj.follow_up_list[val].follow_up_id+'" aria-hidden="true"></i> &nbsp;'+final_date+' </a></td>'+
	   //                              '</tr>';
				// 	}
				// 	else
				// 	{
				// 		temp_html += '<tr>'+
	   //                              	'<td> <a class="inactive_patient" href="'+base_url+'order/patient_profile/'+obj.follow_up_list[val].medical_record_id+'/'+obj.follow_up_list[val].ordered_by+'" target="_blank"> '+obj.follow_up_list[val].p_fname+' '+obj.follow_up_list[val].p_lname+' </a></td>'+
	   //                              	'<td class="inactive_patient"><i class="fa fa-calendar follow_up_date_calendar_inactive" data-follow-up-id="'+obj.follow_up_list[val].follow_up_id+'" aria-hidden="true"></i> &nbsp;'+final_date+' </a></td>'+
	   //                              '</tr>';
				// 	}
  		// 		}

  		// 		datatable_initialized.destroy();
  		// 		$('.o2concentrator_follow_up_tbody').empty();
  		// 		o2concentrator_follow_up_tbody.html(temp_html);
  		// 		datatable_initialized = $('.datatable_table_o2_oxygen_follow_up_report').DataTable( {
				//     "order": [[ 1, "asc" ]]
				// } );
  		// 	});
	  	// });

	});

</script>
