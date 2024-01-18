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

	@media print {
		@page {
			size: portrait;
			/* margin-top: 0mm; */
			margin: 0mm 2mm 10mm 2mm;
		}
		.page {
			font-size: 7px !important;
		}

		.select2-container {
			width: 400px !important;
		}

		.location_container {
			font-size: 7px !important;
            display: block !important;
        }

        .sample_div {
            margin-top: -45px !important;
        }

		#hospice_display_name {
			display: block !important;
		}

		.panel-default {
			border: 0px !important;
			margin-bottom: 0px !important;
			margin-top: -20px !important;
		}

		.panel-body {
			padding: 0px !important;
		}

		.select2 > .selection > .select2-selection {
			border: 0px !important;

		}

		.select2-container--default .select2-selection--single .select2-selection__arrow {
			display: none !important;
		}

		.select2-selection__rendered {
            font-weight: bold !important;
            font-size: 14px !important;
            margin-left: -50px !important;
        }
	}

	.location_container {
		/*position: absolute;*/
		/*margin-top: -10px;*/
		margin-left: 25px;
		font-size: 10px;
		/*top: 0;*/
		left: 0;
		display: none;
	}

	.address-style {
        font-weight: bold;
    }
    .statement_letter_label_tag {
        font-weight: bold;
        margin-right: 6px;
    }
    .statement_letter_label_wrapper {

    }

    .dot {
        height: 18px;
        min-width: 18px;
        margin-left: 5px;
        margin-right: 0px;
        background-color: #bbb;
        border-radius: 20px;
        display: inline-block;
        background-color: #23b7e5;
        color: white;
    }

	.location_container {
        /*position: absolute;*/
        /*margin-top: -10px;*/
        margin-left: 30px;
        font-size: 12px;
        /*top: 0;*/
        /* left: 0; */
        display: none;
    }
</style>

<div class="archive_container">
	
    <div class="page" ng-controller="FlotChartDemoCtrl">
		<div class="bg-light lter b-b wrapper-md">
			<h1 class="m-n font-thin h3">
				<?php if ($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "biller") { ?>
					<span class="pull-right">
						<button class="btn btn-info btn-sm active_census_export_csv" style="font-size:13px !important;">Export as CSV</button>
					</span>
				<?php } ?>
				<span>Active Census</span>
			</h1>
		</div>
		<div class="wrapper-md">
		    <div class="panel-report panel panel-default">
			    <div class="panel-heading hidden-print">
					<span>Active Census</span>
			    </div>
			    <div class="panel-body">
					<div class="location_container">
						<strong>Date:</strong>  <?php echo date("m/d/Y"); ?>
						</br>
						<strong>Location:</strong>
						<?php
							$location = get_login_location($this->session->userdata('user_location'));
						?>
						<span class="location_span">
						<?php
							echo $location['user_city'].', '.$location['user_state'];
						?>
						</span>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 hidden-print" style="margin-top:20px;">
						<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
							<span class="pull-right">
								From
								<input type="text" class="form-control choose_date_census filter_from" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 40px;" >
								<!-- value="<?php echo date("Y-m-d"); ?>" -->
							</span>
						</div>
						<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
							<span class="pull-left">
								To
								<input type="text" class="form-control choose_date_census filter_to" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 25px;" >
								<!-- value="<?php echo date("Y-m-d"); ?>" -->
							</span>
						</div>
					</div>

					<!-- <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:8px;">
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:14px;">
							<?php
								echo date('h:i A');
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
                            Active Census
						</div>
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

						</div>

					</div>
					<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:4px;">
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">
							TOTAL PAYMENT AMOUNT: <span id="total_payment_amount_reconciliation_list_queried"></span>
						</div>
						<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;">
							<span id="viewed_current_date">
							<?php
								$current_date = date('Y-m-d');
								echo date("F d, Y", strtotime($current_date))
							?>
							</span>
						</div>
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

						</div>
					</div> -->

					<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:30px;">
					<input type="hidden" name="activity_status_name" id="activity_status_name" value="<?php echo $activity_status_name_new_v2; ?>">

					<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px; text-align: center;">

					  	<thead style="background-color:rgba(97, 101, 115, 0.05);">
					    	<tr>
					    		<th class="throw" style="text-align: center;">Account #</th>
		                        <th class="throw" style="text-align: center;">Hospice/Account Name</th>
		                        <th class="throw" style="text-align: center;">Count</th>
					    	</tr>
					    </thead>
					    <tbody class="active_census_list_tbody">
					    </tbody>
					</table>
					
				    </div>
				    <div class="pagination-container hidden-print">
						<nav aria-label="...">
							<ul class="pager">
								<li class="previous disabled"><a href="javascript:;"><span aria-hidden="true">&larr;</span> Previous</a></li>
								<li class="next"><a href="javascript:;">Next <span aria-hidden="true">&rarr;</span></a></li>
							</ul>
						</nav>
					</div>
				    <!-- <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:10px;margin-bottom:10px;"> -->
						<!-- <div class="col-xxs-12 col-xs-10 col-sm-10 col-md-10" style="padding-left: 95px">
							TOTAL PAYMENT AMOUNT: <span id="total_payment_amount_reconciliation_list_queried"></span>
						</div> -->
						<div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 hidden-print">
							TOTAL ACCOUNTS: <span id="total_accounts_queried"></span>
						</div>
						<div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12 hidden-print mt5 mb5">
							TOTAL ACTIVE PATIENTS: <span id="total_active_patients_queried"></span>
						</div>
					<!-- </div> -->

				</div>
				<div class="col-md-12 hidden-print" style="padding-bottom: 20px">
			        <!-- <hr /> -->
			        <!-- <button class="btn btn-default print-iframe" onclick="window.print()" style="margin-right:10px;" ><i class="fa fa-print"></i> Print</button> -->
			        <!-- <button type="button" class="btn btn-info pull-right create_reconciliation_modal" >Create Reconciliation</button> -->
			    </div>
			</div>
			<!-- <div class="bg-light lter wrapper-md" style="margin-top:-20px;margin-left:-20px;margin-bottom:-15px;">
			   <button class="btn btn-default print_activity_status" ><i class="fa fa-print"></i> Print</button>
			</div> -->
		</div>

	</div>
</div>

<script type="text/javascript">

    $(document).ready(function(){

        $('.choose_date_census').datepicker({
	    	dateFormat: 'mm-dd-yy',
         	onClose: function (dateText, inst) {
				active_census_list_initial();
        	}
      	});

        var totalcount = 0;
        var currenttotal = 0;
        var datatoexport = "";
        var active_census_list_initial = function(page){
            var filter_from = $("body").find(".filter_from").val();
            var filter_to = $("body").find(".filter_to").val();
            var active_census_list_tbody = $("body").find(".active_census_list_tbody");
            var total_accounts_queried = $("body").find("#total_accounts_queried");
			var total_active_patients_queried = $("body").find("#total_active_patients_queried");

            if(filter_from == "")
            {
                filter_from = 0;
            } else {
                var temp_from = filter_from.split(/\s*\-\s*/g);
                filter_from = temp_from[2] + '-' + temp_from[0] + '-' + temp_from[1]
            }

            if(filter_to == "")
            {
                filter_to = 0;
            } else {
                var temp_to = filter_to.split(/\s*\-\s*/g);
                filter_to = temp_to[2] + '-' + temp_to[0] + '-' + temp_to[1]
            }

            if(filter_from != 0 && filter_to != 0)
            {
                active_census_list_tbody.html("<tr><td colspan='13' style='text-align:center;'>Retrieving data. Please wait... <i class='fa fa-spin fa-spinner'></i></td></tr>");

                var temp_html = "";
                var pagenum = 1;
                if(typeof(page)!="undefined"){
                    pagenum = page*1;
                }
                $.post(base_url+"census/load_more_active_census/" + filter_from +"/"+ filter_to +"/"+ pagenum,"", function(response){
                    var obj = $.parseJSON(response);

                    if(obj.pagination_details.total_pages < 2) {
                        $('.pagination-container').addClass("hidden");
                    }
                    else
                    {
                        $('.pagination-container').removeClass("hidden");
                        var current_page = obj.pagination_details.current_page*1;
                        if(current_page-1==0) {
                            $('.pagination-container').find('.previous').addClass("disabled");
                        }
                        else
                        {
                            $('.pagination-container').find('.previous').removeClass("disabled");
                            $('.pagination-container').find('.previous').attr("data-page",(current_page-1));
                        }
                        if(current_page+1>obj.pagination_details.total_pages) {
                            $('.pagination-container').find('.next').addClass("disabled");
                        }
                        else
                        {
                            $('.pagination-container').find('.next').removeClass("disabled");
                            $('.pagination-container').find('.next').attr("data-page",(current_page+1));
                        }
                    }
                    for(var i = 0; i < obj.hospices_data.length; i++) {
                        
                        temp_html += '<tr>';
                        temp_html += '<td>'+obj.hospices_data[i]['hospice_number']+'</td>';
                        temp_html += '<td>'+obj.hospices_data[i]['hospice_name']+'</td>';
                        temp_html += '<td>'+obj.hospices_data[i]['totalCustomerCount']+'</td>';
                        temp_html += '</tr>';
                    }
                    if(temp_html == "") {
                        temp_html = '<td colspan="3" style="text-align:center; padding: 10px"> No Data Retrieved. </td>';
                    }
                    active_census_list_tbody.html(temp_html);
                    total_accounts_queried.html(obj.pagination_details.total_records);
                    totalcount = obj.pagination_details.total_records;      
					
					total_active_patients_queried.html("<i class='fa fa-spin fa-spinner'></i>");

					$.post(base_url+"census/get_total_active_patients/" + filter_from +"/"+ filter_to,"", function(response){
                    	var obj2 = $.parseJSON(response);
						total_active_patients_queried.html(obj2.total_active_patients_count_initial);
					});
                });
            }
        };

        $('.pagination-container').on('click','.pager > li',function(){
            if(!$(this).hasClass("disabled")){
                    var page = $(this).attr("data-page")*1;
                    active_census_list_content(page);
            }
		});

		var active_census_list_content = function(page){
            var filter_from = $("body").find(".filter_from").val();
            var filter_to = $("body").find(".filter_to").val();
            var active_census_list_tbody = $("body").find(".active_census_list_tbody");
            var total_accounts_queried = $("body").find("#total_accounts_queried");

            if(filter_from == "")
            {
                filter_from = 0;
            } else {
                var temp_from = filter_from.split(/\s*\-\s*/g);
                filter_from = temp_from[2] + '-' + temp_from[0] + '-' + temp_from[1]
            }

            if(filter_to == "")
            {
                filter_to = 0;
            } else {
                var temp_to = filter_to.split(/\s*\-\s*/g);
                filter_to = temp_to[2] + '-' + temp_to[0] + '-' + temp_to[1]
            }

            if(filter_from != 0 && filter_to != 0)
            {
                active_census_list_tbody.html("<tr><td colspan='13' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");

                var temp_html = "";
                var pagenum = 1;
                if(typeof(page)!="undefined"){
                    pagenum = page*1;
                }
                $.post(base_url+"census/load_more_active_census/" + filter_from +"/"+ filter_to +"/"+ pagenum,"", function(response){
                    var obj = $.parseJSON(response);

                    if(obj.pagination_details.total_pages < 2) {
                        $('.pagination-container').addClass("hidden");
                    }
                    else
                    {
                        $('.pagination-container').removeClass("hidden");
                        var current_page = obj.pagination_details.current_page*1;
                        if(current_page-1==0) {
                            $('.pagination-container').find('.previous').addClass("disabled");
                        }
                        else
                        {
                            $('.pagination-container').find('.previous').removeClass("disabled");
                            $('.pagination-container').find('.previous').attr("data-page",(current_page-1));
                        }
                        if(current_page+1>obj.pagination_details.total_pages) {
                            $('.pagination-container').find('.next').addClass("disabled");
                        }
                        else
                        {
                            $('.pagination-container').find('.next').removeClass("disabled");
                            $('.pagination-container').find('.next').attr("data-page",(current_page+1));
                        }
                    }
                    for(var i = 0; i < obj.hospices_data.length; i++) {
                        
                        temp_html += '<tr>';
                        temp_html += '<td>'+obj.hospices_data[i]['hospice_number']+'</td>';
                        temp_html += '<td>'+obj.hospices_data[i]['hospice_name']+'</td>';
                        temp_html += '<td>'+obj.hospices_data[i]['totalCustomerCount']+'</td>';
                        temp_html += '</tr>';
                    }
                    if(temp_html == "") {
                        temp_html = '<td colspan="3" style="text-align:center; padding: 10px"> No Data Retrieved. </td>';
                    }
                    active_census_list_tbody.html(temp_html);
                    total_accounts_queried.html(obj.pagination_details.total_records);
                    totalcount = obj.pagination_details.total_records;                    
                });
            }
        };

        // Export as CSV
		$('body').on('click','.active_census_export_csv',function(){
            
            me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Exporting Report ..."});

			var filter_from = $("body").find(".filter_from").val();
			var filter_to = $("body").find(".filter_to").val();
			var reconciliation_list_tbody = $("body").find(".reconciliation_list_tbody");
			var total_reconciliation_list_queried = $("body").find("#total_reconciliation_list_queried");
			var viewed_current_date = $("body").find("#viewed_current_date");

			datatoexport = 'Account #,Hospice/Account Name,Count,\n';
			currenttotal = 0;

			if(filter_from == "")
            {
                filter_from = 0;
            } else {
                var temp_from = filter_from.split(/\s*\-\s*/g);
                filter_from = temp_from[2] + '-' + temp_from[0] + '-' + temp_from[1]
            }

            if(filter_to == "")
            {
                filter_to = 0;
            } else {
                var temp_to = filter_to.split(/\s*\-\s*/g);
                filter_to = temp_to[2] + '-' + temp_to[0] + '-' + temp_to[1]
            }

			if (filter_from != 0 && filter_to != 0) {
				export_active_census(filter_from,filter_to,1);
			} else {
				//export empty csv
				exportAsCSV(datatoexport,filter_from,filter_to);
			}
			
		});

		var export_total_active_patients_count = 0;
        var export_active_census = function (filter_from,filter_to,pagenum) {
            if (currenttotal < totalcount) {
                $.post(base_url+"census/load_more_active_census/" + filter_from +"/"+ filter_to +"/"+ pagenum,"", function(response){
                    var obj = $.parseJSON(response);

                    currenttotal += obj.hospices_data.length;
                    pagenum++;
					export_total_active_patients_count += obj.total_active_patients_count;
                    for(var i = 0; i < obj.hospices_data.length; i++) {
                        
                        datatoexport += obj.hospices_data[i]['hospice_number']+','; // Hospice #
                        datatoexport += '"'+obj.hospices_data[i]['hospice_name']+'",'; // Hospice Name
                        datatoexport += obj.hospices_data[i]['totalCustomerCount']+','; // Count

                        datatoexport += '\n';
                    }
                    export_active_census(filter_from,filter_to,pagenum);
                });	
            } else {
				datatoexport += '\n';
				datatoexport += 'TOTAL ACTIVE PATIENTS: ' + export_total_active_patients_count;
				datatoexport += '\n';
                exportAsCSV(datatoexport,filter_from,filter_to);
            }
        }

        var exportAsCSV = function(csvData,filter_from,filter_to) {
            var blob = new Blob(['\ufeff' + csvData], { type: 'text/csv;charset=utf-8;' });
            var dwldLink = document.createElement('a');
            var url = URL.createObjectURL(blob);
            var isSafariBrowser = navigator.userAgent.indexOf('Safari') !== -1 && navigator.userAgent.indexOf('Chrome') === -1;
            if (isSafariBrowser) {  // if Safari open in new window to save file with random filename.
                dwldLink.setAttribute('target', '_blank');
            }
            var file_name = '';
            if (filter_from != 0 && filter_to != 0) {
                var date_from = filter_from.replaceAll('-', '');
                var date_to = filter_to.replaceAll('-', '');
            file_name = 'Active_Census_' + date_from + '-' + date_to + '.csv';
            } else {
            file_name = 'Active_Census.csv';
            }
            dwldLink.setAttribute('href', url);
            dwldLink.setAttribute('download', file_name);
            dwldLink.style.visibility = 'hidden';
            document.body.appendChild(dwldLink);
            dwldLink.click();
            document.body.removeChild(dwldLink);
            me_message_v2({error:0,message:"Export Done."});
        }

    });

</script>

