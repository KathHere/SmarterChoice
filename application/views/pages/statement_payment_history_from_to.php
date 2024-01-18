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
						<button class="btn btn-info btn-sm export_csv" style="font-size:13px !important;">Export as CSV</button>
					</span>
				<?php } ?>
				<span>Payment History</span>
			</h1>
		</div>
		<div class="wrapper-md">
		    <div class="panel-report panel panel-default">
			    <div class="panel-heading hidden-print">
					<span>Payment History</span>
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
								<input type="text" class="form-control choose_date filter_from" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 40px;" >
								<!-- value="<?php echo date("Y-m-d"); ?>" -->
							</span>
						</div>
						<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
							<span class="pull-left">
								To
								<input type="text" class="form-control choose_date filter_to" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 25px;" >
								<!-- value="<?php echo date("Y-m-d"); ?>" -->
							</span>
						</div>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:8px;">
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:14px;">
							<?php
								echo date('h:i A');
							?>
						</div>
						<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 select_hospice_container" style="text-align:center;">
							<?php
								if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "biller")
			        			{
			        				$selected = "";
					                $hospices = get_hospices_v2($this->session->userdata('user_location'));
	                    			$companies = get_companies_v2($this->session->userdata('user_location'));
				            ?>
									<select class="form-control pull-center filter_activity_status_details select2-ready" name="filter_activity_status_details" style="margin-top: 6px;border: 0px;font-size: 16px;font-weight: 600;text-align-last:center;">
										<option data-hospice-name="Advantage Home Medical Services" value="0">Advantage Home Medical Services</option>
										<optgroup label="Hospices">
						                <?php
						                	foreach($hospices as $hospice) :
						                     	// if($hospice['hospiceID'] != 13) {
						                ?>
						                      		<option data-hospice-name="<?php echo $hospice['hospice_name'] ?>" value="<?php echo $hospice['hospiceID'] ?>" <?php if($hospiceID == $hospice['hospiceID']){ echo "selected"; } ?> ><?php echo $hospice['hospice_name'] ?></option>
						                <?php
						                		// }
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
									<!-- <div style="font-weight:600;font-size:16px;margin-top:13px;display:none" id="hospice_display_name"> </div> -->
							<?php
								}else{
									$hospice = $this->session->userdata('group_id');
									$hospice_info = get_hospice_name($hospice);
							?>
									<div style="font-weight:600;font-size:16px;margin-top:13px;"> <?php echo $hospice_info['hospice_name']; ?>Advantage Home Medical Services</div>
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
							Payment History
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
					</div>

					<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:30px;">
					<input type="hidden" name="activity_status_name" id="activity_status_name" value="<?php echo $activity_status_name_new_v2; ?>">

					<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px; text-align: center;">

					  	<thead style="background-color:rgba(97, 101, 115, 0.05);">
					    	<tr>
					    		<th class="throw" style="text-align: center;">Receive Date</th>
		                        <th class="throw" style="text-align: center;">Invoice Date</th>
		                        <th class="throw" style="text-align: center;">Due Date</th>
								<th class="throw" style="text-align: center;">Invoice Number</th>
								<th class="throw" style="text-align: center;">Service Date</th>
		                        <th class="throw account_num" style="text-align: center;">Account Number</th>
								<th class="throw" style="text-align: center;">Account Name</th>
		                        <th class="throw" style="text-align: center;">Balance Due</th>
		                        <th class="throw" style="text-align: center;">Payment Amount</th>
		                        <th class="throw" style="text-align: center;">Payment Type</th>
		                        <th class="throw" style="text-align: center;">Check Number</th>
		                        <!-- <th class="throw" style="text-align: center;">Receive Date</th> -->
		                        <th class="throw" style="text-align: center;">Received By</th>
		                        <th class="throw hidden-print" style="text-align: center;">Action</th>
					    	</tr>
					    </thead>
					    <tbody class="reconciliation_list_tbody">
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
				    <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:10px;margin-bottom:10px;">
						<!-- <div class="col-xxs-12 col-xs-10 col-sm-10 col-md-10" style="padding-left: 95px">
							TOTAL PAYMENT AMOUNT: <span id="total_payment_amount_reconciliation_list_queried"></span>
						</div> -->
						<div class="col-xxs-12 col-xs-2 col-sm-2 col-md-2 hidden-print">
							TOTAL INVOICES: <span id="total_reconciliation_list_queried"></span>
						</div>
					</div>

				</div>
				<div class="col-md-12 hidden-print" style="padding-bottom: 20px">
			        <hr />
			        <button class="btn btn-default print-iframe" onclick="window.print()" style="margin-right:10px;" ><i class="fa fa-print"></i> Print</button>
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
	var totalcount = 0;
	var currenttotal = 0;
	var datatoexport = "";
	var reconciliation_list_content = function(page){
		var filter_from = $("body").find(".filter_from").val();
		var filter_to = $("body").find(".filter_to").val();
		var hospiceID = $("body").find(".filter_activity_status_details").val();
		var reconciliation_list_tbody = $("body").find(".reconciliation_list_tbody");
		var total_reconciliation_list_queried = $("body").find("#total_reconciliation_list_queried");
		var viewed_current_date = $("body").find("#viewed_current_date");

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
			reconciliation_list_tbody.html("<tr><td colspan='13' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");

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
			console.log('nasud');
			$.post(base_url+"billing_reconciliation/load_more_payment_history/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ pagenum,"", function(response){
				var obj = $.parseJSON(response);

				console.log(obj);
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
				for(var i = 0; i < obj.statement_paid_invoices.length; i++) {
					var due_date = obj.statement_paid_invoices[i].due_date.split('-');
                    var rec_date = obj.statement_paid_invoices[i].receive_date.split('-');
					var service_date_from = obj.statement_paid_invoices[i].service_date_from.split('-');
					var service_date_to = obj.statement_paid_invoices[i].service_date_to.split('-');

                    var inv_date = new Date(obj.statement_paid_invoices[i].invoice_date);
					console.log('invoice_date', inv_date);
					console.log('invoice_date_month', inv_date.getMonth());
                    var inv_year = "";
                    var inv_month = "";
                    inv_year = inv_date.getFullYear();
					var temp_inv_month = inv_date.getMonth() + 1;
					console.log('temp_inv_month', temp_inv_month);
                    if(temp_inv_month < 10) {
                        inv_month = "0"+temp_inv_month;
                    } else {
                        inv_month = temp_inv_month;
                    }
                    var inv_day = "";
                    if((inv_date.getDate()) < 10) {
                        inv_day = "0"+(inv_date.getDate());
                    } else {
                        inv_day = inv_date.getDate();
                    }

                    var new_date = due_date[1]+"/"+due_date[2]+"/"+due_date[0];
                    var new_inv_date = inv_month+"/"+inv_day+"/"+inv_year;
                    var new_rec_date = rec_date[1]+"/"+rec_date[2]+"/"+rec_date[0];
					var service_date = service_date_from[1]+"/"+service_date_from[2]+"/"+service_date_from[0]+"-"+service_date_to[1]+"/"+service_date_to[2]+"/"+service_date_to[0];

                    if(obj.statement_paid_invoices[i].invoice_date == "0000-00-00 00:00:00" || obj.statement_paid_invoices[i].invoice_date == null) {
                        new_inv_date = "";
                    }
                    if(obj.statement_paid_invoices[i].receive_date == "0000-00-00 00:00:00" || obj.statement_paid_invoices[i].receive_date == null) {
                        new_date = "";
                    }

                    var temp_inv_number = obj.statement_paid_invoices[i].statement_no;
                    var payment_type = "";  
                    if(obj.statement_paid_invoices[i].payment_type == "credit_card") {
                        payment_type = "Credit Card";
                    }
                    if(obj.statement_paid_invoices[i].payment_type == "check") {
                        payment_type = "Check";
                    }

                    var temp_background_color = '';
                    if(obj.note_count[i] == 0) {
                    	temp_background_color = 'background-color: #c3c2c2 !important;';
                    }
                    temp_html += '<tr>';
                    temp_html += '<td>'+new_rec_date+'</td>';
                    temp_html += '<td>'+new_inv_date+'</td>';
                    temp_html += '<td>'+new_date+'</td>';
                    temp_html += '<td><div style="cursor: pointer" class="view_invoice_details" data-invoice-id="'+obj.statement_paid_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_paid_invoices[i].hospiceID+'">'+temp_inv_number.substr(3, 10)+'</div></td>';
					temp_html += '<td>'+service_date+'</td>';
                    temp_html += '<td>'+obj.statement_paid_invoices[i].hospice_account_number+'</td>';

                    temp_html += '<td>';
                    temp_html += obj.statement_paid_invoices[i].hospice_name;
                    temp_html += '</br> <a href="javascript:void(0)" data-invoice-id="'+obj.statement_paid_invoices[i].acct_statement_invoice_id+'"';
                    temp_html += ' data-account-number="'+obj.statement_paid_invoices[i].hospice_account_number+'" data-account="'+obj.statement_paid_invoices[i].hospice_name+'"';
                    temp_html += ' data-statement-no="'+obj.statement_paid_invoices[i].statement_no+'" name="comment-modal"';
                    temp_html += ' data-hospice-id="'+obj.statement_paid_invoices[i].hospiceID+'"';
                    temp_html += ' style="text-decoration:none;cursor:pointer; text-align: center;"';
                    temp_html += ' class="hidden-print view_comments notes_count_'+obj.statement_paid_invoices[i].acct_statement_invoice_id+'">';
                    temp_html += '<div class="row">';
                    temp_html += '<div class="col-md-6" style="padding-right: 0px !important"><i class="icon-speech pull-right"></i></div>';
                    temp_html += '<div class="col-md-6" style="padding-left: 0px !important"><p class="dot pull-left" style="float: right;margin-top: -3px;margin-right: 11px; '+temp_background_color+'">';
                    temp_html += obj.note_count[i];
					temp_html += '</p></div>';
					temp_html += '</div>';
                    temp_html += '</a>';
                    temp_html += '</td>';

					var temptotaltemp = parseFloat(obj.statement_paid_invoices[i].total) + parseFloat(obj.statement_paid_invoices[i].non_cap) + parseFloat(obj.statement_paid_invoices[i].purchase_item);
					temptotaltemp += obj.invoices_reconciliation[i].owe;
					temptotaltemp -= obj.invoices_reconciliation[i].credit;
                    temp_html += '<td>'+parseFloat(temptotaltemp).toFixed(2)+'</td>';
                    temp_html += '<td>'+parseFloat(obj.statement_paid_invoices[i].payment_amount).toFixed(2)+'</td>';
                    temp_html += '<td>'+payment_type+'</td>';
                    temp_html += '<td>'+(obj.statement_paid_invoices[i].check_number == null ? "" : obj.statement_paid_invoices[i].check_number)+'</td>';
                    temp_html += '<td>'+obj.statement_paid_invoices[i].received_by+'</td>';
                    if(obj.statement_paid_invoices[i].is_reverted == 1) {
                    	temp_html += '<td>Chk Rtn</td>';
                    } else {
                    	temp_html += '<td class="hidden-print">';
                    	temp_html += '<button class="hidden-print btn btn-xs btn-danger revert_invoice_btn" data-received-payment-id="'+obj.statement_paid_invoices[i].acct_statement_received_payment_id+'" data-invoice-id="'+obj.statement_paid_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_paid_invoices[i].hospiceID+'" data-invoice-number="'+obj.statement_paid_invoices[i].statement_no+'">';
                    	temp_html += 'Cancel';
                    	temp_html += '</button>';
                    	temp_html += '</td>';
                    }
                    
                    
                    temp_html += '</tr>';
                }
				if(temp_html == "") {
					temp_html = '<td colspan="13" style="text-align:center; padding: 10px"> No Payment. </td>';
				}
				reconciliation_list_tbody.html(temp_html);
				total_reconciliation_list_queried.html(obj.pagination_details.total_records);
				totalcount = obj.pagination_details.total_records;
				
				get_total_payment_amount();
			});
		}
	};

	var get_total_payment_amount = function() {
		var filter_from = $("body").find(".filter_from").val();
		var filter_to = $("body").find(".filter_to").val();
		var hospiceID = $("body").find(".filter_activity_status_details").val();

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

		$.post(base_url+"billing_reconciliation/get_total_payment_amount_payment_history/" + filter_from +"/"+ filter_to +"/"+ hospiceID,"", function(response){
			var obj = $.parseJSON(response);

			console.log('date_total_payment_amount', obj);
			if (obj.total_payment_amount != null) {
				$('body').find('#total_payment_amount_reconciliation_list_queried').html(parseFloat(obj.total_payment_amount).toFixed(2));
			} else {
				$('body').find('#total_payment_amount_reconciliation_list_queried').html('');
			}
		});
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
          file_name = 'Payment_History_' + date_from + '-' + date_to + '.csv';
        } else {
          file_name = 'Payment_History.csv';
        }
        dwldLink.setAttribute('href', url);
        dwldLink.setAttribute('download', file_name);
        dwldLink.style.visibility = 'hidden';
        document.body.appendChild(dwldLink);
        dwldLink.click();
        document.body.removeChild(dwldLink);
	}

	var export_payment_history_recursion = function (filter_from,filter_to,hospiceID,pagenum) {
		if (currenttotal < totalcount) {
			$.post(base_url+"billing_reconciliation/load_more_payment_history/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ pagenum,"", function(response){
				var obj = $.parseJSON(response);
				console.log('export obj', obj);

				currenttotal += obj.statement_paid_invoices.length;
				pagenum++;
				for(var i = 0; i < obj.statement_paid_invoices.length; i++) {
					var due_date = obj.statement_paid_invoices[i].due_date.split('-');
                    var rec_date = obj.statement_paid_invoices[i].receive_date.split('-');
					var service_date_from = obj.statement_paid_invoices[i].service_date_from.split('-');
					var service_date_to = obj.statement_paid_invoices[i].service_date_to.split('-');

                    var inv_date = new Date(obj.statement_paid_invoices[i].invoice_date);
					console.log('invoice_date', inv_date);
					console.log('invoice_date_month', inv_date.getMonth());
                    var inv_year = "";
                    var inv_month = "";
                    inv_year = inv_date.getFullYear();
					var temp_inv_month = inv_date.getMonth() + 1;
					console.log('temp_inv_month', temp_inv_month);
                    if(temp_inv_month < 10) {
                        inv_month = "0"+temp_inv_month;
                    } else {
                        inv_month = temp_inv_month;
                    }
                    var inv_day = "";
                    if((inv_date.getDate()) < 10) {
                        inv_day = "0"+(inv_date.getDate());
                    } else {
                        inv_day = inv_date.getDate();
                    }

                    var new_date = due_date[1]+"/"+due_date[2]+"/"+due_date[0];
                    var new_inv_date = inv_month+"/"+inv_day+"/"+inv_year;
                    var new_rec_date = rec_date[1]+"/"+rec_date[2]+"/"+rec_date[0];
					var service_date = service_date_from[1]+"/"+service_date_from[2]+"/"+service_date_from[0]+"-"+service_date_to[1]+"/"+service_date_to[2]+"/"+service_date_to[0];

                    if(obj.statement_paid_invoices[i].invoice_date == "0000-00-00 00:00:00" || obj.statement_paid_invoices[i].invoice_date == null) {
                        new_inv_date = "";
                    }
                    if(obj.statement_paid_invoices[i].receive_date == "0000-00-00 00:00:00" || obj.statement_paid_invoices[i].receive_date == null) {
                        new_date = "";
                    }

                    var temp_inv_number = obj.statement_paid_invoices[i].statement_no;
                    var payment_type = "";  
                    if(obj.statement_paid_invoices[i].payment_type == "credit_card") {
                        payment_type = "Credit Card";
                    }
                    if(obj.statement_paid_invoices[i].payment_type == "check") {
                        payment_type = "Check";
                    }

					var temptotaltemp = parseFloat(obj.statement_paid_invoices[i].total) + parseFloat(obj.statement_paid_invoices[i].non_cap) + parseFloat(obj.statement_paid_invoices[i].purchase_item);
					temptotaltemp += obj.invoices_reconciliation[i].owe;
					temptotaltemp -= obj.invoices_reconciliation[i].credit;

					datatoexport += new_rec_date+','; // Receive Date
					datatoexport += new_inv_date+','; // Invoice Date
					datatoexport += new_date+','; // Due Date
					datatoexport += temp_inv_number.substr(3, 10)+','; // Invoice Number
					datatoexport += service_date+','; // Service Date

					if (obj.statement_paid_invoices[i].hospice_account_number != null && obj.statement_paid_invoices[i].hospice_account_number != undefined) {
						datatoexport += obj.statement_paid_invoices[i].hospice_account_number+','; // Account Number
					} else {
						datatoexport += ',';
					}

					if (obj.statement_paid_invoices[i].hospice_name != null && obj.statement_paid_invoices[i].hospice_name != undefined) {
						datatoexport += '"'+obj.statement_paid_invoices[i].hospice_name+'",'; // Account Name
					} else {
						datatoexport += ',';
					}

					if (temptotaltemp != null && temptotaltemp != undefined) {
						datatoexport += parseFloat(temptotaltemp).toFixed(2)+','; // Balance Due
					} else {
						datatoexport += ',';
					}
					
					if (obj.statement_paid_invoices[i].payment_amount != null && obj.statement_paid_invoices[i].payment_amount != undefined) {
						datatoexport += parseFloat(obj.statement_paid_invoices[i].payment_amount).toFixed(2)+','; // Payment Amount
					} else {
						datatoexport += ',';
					}

					datatoexport += payment_type+','; // Payment Type
					
					if (obj.statement_paid_invoices[i].check_number != null && obj.statement_paid_invoices[i].check_number != undefined) {
						datatoexport += obj.statement_paid_invoices[i].check_number+','; // Check Number
					} else {
						datatoexport += ',';
					}

					if (obj.statement_paid_invoices[i].received_by != null && obj.statement_paid_invoices[i].received_by != undefined) {
						datatoexport += obj.statement_paid_invoices[i].received_by+','; // Received By
					} else {
						datatoexport += ',';
					}

					datatoexport += '\n';
				}

				export_payment_history_recursion(filter_from,filter_to,hospiceID,pagenum);
			});	
		} else {
			exportAsCSV(datatoexport,filter_from,filter_to);
		}
	}

	$(document).ready(function(){
		// reconciliation_list_content();

		// Export as CSV
		$('body').on('click','.export_csv',function(){
			var filter_from = $("body").find(".filter_from").val();
			var filter_to = $("body").find(".filter_to").val();
			var hospiceID = $("body").find(".filter_activity_status_details").val();
			var reconciliation_list_tbody = $("body").find(".reconciliation_list_tbody");
			var total_reconciliation_list_queried = $("body").find("#total_reconciliation_list_queried");
			var viewed_current_date = $("body").find("#viewed_current_date");
			// console.log('filter_activity_status_details', $("body").find(".filter_activity_status_details").attr('data-hospice-name'));
			// $('body').find('#hospice_display_name').html($("body").find(".filter_activity_status_details").attr('data-hospice-name'));

			datatoexport = 'Receive Date,Invoice Date,Due Date,Invoice Number,Service Date,Account Number,Account Name,Balance Due,Payment Amount,Payment Type,Check Number,Received By,\n';
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
				export_payment_history_recursion(filter_from,filter_to,hospiceID,1);
			} else {
				//export empty csv
				exportAsCSV(datatoexport,filter_from,filter_to);
			}
			
		});

		// View statement bill Details
	    $('body').on('click','.view_invoice_details',function(){
	        var _this = $(this);
	        var invoice_id = $(this).attr("data-invoice-id");
	        var data_hospice_id = $(this).attr("data-hospice-id");
	        var header_logo = "";
	        header_logo += '<div class="row" style="height: 85px !important;">';
			header_logo += '<div class="col-md-4">';
			header_logo += '<p class="logo_ahmslv" style="margin-bottom:0px; text-align: center">';
			header_logo += '<img class="logo_ahmslv_img" src="<?php echo base_url("assets/img/smarterchoice_logo.png"); ?>" alt="" style="height:50px;width:58px;"/>';
			header_logo += '</p>';
			header_logo += '<h4 class="work_order_header_first" style="font-weight:bold;margin-top:0px;font-size:17px; text-align: center"> Advantage Home Medical Services, Inc </h4>';
			header_logo += '</div>';

			//Printer Loader
			header_logo += '<div class="col-md-4 text-center">';
			header_logo += '<span style="line-height: 10rem; font-size: 25px !important;">Invoice</span>';
			header_logo += '</div>';
			header_logo += '<div class="col-md-4 text-center" style="padding-top: 10px;">';
			header_logo += '<div class="loader-icon" style="display: none !important">';
			header_logo += '<i class="printer_loader_icon fa fa-print"></i>';
			header_logo += '<div class="loader"></div>';
			header_logo += '</div>';
			header_logo += '<button class="btn btn-default loaded-icon" rel="popover" data-html="true" data-toggle="popover" data-trigger="hover" data-replacement="left" data-content="Print" onclick="window.print()" style="display:table-column;border-radius:50%;">';
			header_logo += '<i class="printer_loaded_icon fa fa-print"></i>';
			header_logo += '</button>';
			header_logo += '</div>';
			header_logo += '</div>';
	        modalbox(base_url + 'billing_statement/statement_activity_details/'+invoice_id+'/'+data_hospice_id,{
	            header:header_logo,
							button: false,
							width: 1200
	        });
	    });

		//create_reconciliation
		$('body').on('click','.create_reconciliation_modal',function(){
	        var _this = $(this);
	        // var letter_id = $(this).attr("data-letter-id");
	        // var data_hospice_id = $(this).attr("data-hospice-id");

	        // modalbox(base_url + 'billing_statement/statement_letter_details/'+invoice_id+'/'+data_hospice_id,{
	        modalbox(base_url + 'billing_reconciliation/create_reconciliation_index/',{
	            // header:"Reconciliation",
	            header:'<h4 class="modal-title row" style="font-weight: bold"><div class="col-md-4" style="margin-right: 28px"><?php echo date('m/d/Y'); ?></div><div class="col-md-7" >Reconciliation</div></h4>',
	            button: false,
	        });
	    });

	    //View reconciliation Details
		$('body').on('click','.view_statement_reconciliation',function(){
	        var _this = $(this);
	        var recon_id = $(this).attr("data-reconcile-id");
	        // var data_hospice_id = $(this).attr("data-hospice-id");

	        // modalbox(base_url + 'billing_statement/statement_letter_details/'+invoice_id+'/'+data_hospice_id,{
	        modalbox(base_url + 'billing_reconciliation/create_reconciliation_index/'+recon_id,{
	            // header:"Reconciliation",
	            header:'<h4 class="modal-title row" style="font-weight: bold"><div class="col-md-4 recon_date" style="margin-right: 28px"><?php echo date('m/d/Y'); ?></div><div class="col-md-7" >Reconciliation</div></h4>',
	            button: false,
	        });
	    });

		// // View reconciliation Details
	 //    $('body').on('click','.view_statement_reconciliation',function(){
	 //        var _this = $(this);
	 //        // var letter_id = $(this).attr("data-letter-id");
	 //        // var data_hospice_id = $(this).attr("data-hospice-id");

	 //        // modalbox(base_url + 'billing_statement/statement_letter_details/'+invoice_id+'/'+data_hospice_id,{
	 //        modalbox(base_url + 'billing_statement/statement_reconciliation_details/',{
	 //            // header:"Reconciliation",
	 //            header:"Reconciliation",
	 //            button: false,
	 //        });
	 //    });
		

		$('.pagination-container').on('click','.pager > li',function(){
				if(!$(this).hasClass("disabled")){
					 var page = $(this).attr("data-page")*1;
					 reconciliation_list_content(page);
				}
		});

	    $('.choose_date').datepicker({
	    	dateFormat: 'mm-dd-yy',
         	onClose: function (dateText, inst) {
				reconciliation_list_content();
        	}
      	});


		$('body').on('click','.print_activity_status',function(){
	  		var filter_from = $("body").find(".filter_from").val();
  			var filter_to = $("body").find(".filter_to").val();
  			var hospiceID = $("body").find(".filter_activity_status_details").val();
  			var status_name = $("body").find("#activity_status_name").val();

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

          	window.open(base_url+'report/print_activity_status_details/'+filter_from+'/'+filter_to+'/'+hospiceID+'/'+status_name);
	  	});

	  	$('select.filter_activity_status_details').on('change', function (e)
	  	{
	  		var filter_from = $("body").find(".filter_from").val();
			var filter_to = $("body").find(".filter_to").val();
			var hospiceID = $("body").find(".filter_activity_status_details").val();
			var reconciliation_list_tbody = $("body").find(".reconciliation_list_tbody");
			var total_reconciliation_list_queried = $("body").find("#total_reconciliation_list_queried");
			var viewed_current_date = $("body").find("#viewed_current_date");
			// console.log('filter_activity_status_details', $("body").find(".filter_activity_status_details").attr('data-hospice-name'));
			// $('body').find('#hospice_display_name').html($("body").find(".filter_activity_status_details").attr('data-hospice-name'));

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
				reconciliation_list_tbody.html("<tr><td colspan='13' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");

				var temp_html = "";
				var pagenum = 1;
				if(typeof(page)!="undefined"){
					pagenum = page*1;
				}
				console.log('nasud');
				$.post(base_url+"billing_reconciliation/load_more_payment_history/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ pagenum,"", function(response){
					var obj = $.parseJSON(response);

					console.log(obj);
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
					for(var i = 0; i < obj.statement_paid_invoices.length; i++) {
						var due_date = obj.statement_paid_invoices[i].due_date.split('-');
						var rec_date = obj.statement_paid_invoices[i].receive_date.split('-');
						var service_date_from = obj.statement_paid_invoices[i].service_date_from.split('-');
						var service_date_to = obj.statement_paid_invoices[i].service_date_to.split('-');

						var inv_date = new Date(obj.statement_paid_invoices[i].invoice_date);
						var inv_year = "";
						var inv_month = "";
						inv_year = inv_date.getFullYear();
						var temp_inv_month = inv_date.getMonth() + 1;
						if(temp_inv_month < 10) {
							inv_month = "0"+temp_inv_month;
						} else {
							inv_month = temp_inv_month;
						}
						var inv_day = "";
						if((inv_date.getDate()) < 10) {
							inv_day = "0"+(inv_date.getDate());
						} else {
							inv_day = inv_date.getDate();
						}

						var new_date = due_date[1]+"/"+due_date[2]+"/"+due_date[0];
						var new_inv_date = inv_month+"/"+inv_day+"/"+inv_year;
						var new_rec_date = rec_date[1]+"/"+rec_date[2]+"/"+rec_date[0];
						var service_date = service_date_from[1]+"/"+service_date_from[2]+"/"+service_date_from[0]+"-"+service_date_to[1]+"/"+service_date_to[2]+"/"+service_date_to[0];

						if(obj.statement_paid_invoices[i].invoice_date == "0000-00-00 00:00:00" || obj.statement_paid_invoices[i].invoice_date == null) {
							new_inv_date = "";
						}
						if(obj.statement_paid_invoices[i].receive_date == "0000-00-00 00:00:00" || obj.statement_paid_invoices[i].receive_date == null) {
							new_date = "";
						}

						var temp_inv_number = obj.statement_paid_invoices[i].statement_no;
						var payment_type = "";  
						if(obj.statement_paid_invoices[i].payment_type == "credit_card") {
							payment_type = "Credit Card";
						}
						if(obj.statement_paid_invoices[i].payment_type == "check") {
							payment_type = "Check";
						}

						var temp_background_color = '';
						if(obj.note_count[i] == 0) {
							temp_background_color = 'background-color: #c3c2c2 !important;';
						}
						temp_html += '<tr>';
						temp_html += '<td>'+new_rec_date+'</td>';
						temp_html += '<td>'+new_inv_date+'</td>';
						temp_html += '<td>'+new_date+'</td>';
						temp_html += '<td><div style="cursor: pointer" class="view_invoice_details" data-invoice-id="'+obj.statement_paid_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_paid_invoices[i].hospiceID+'">'+temp_inv_number.substr(3, 10)+'</div></td>';
						temp_html += '<td>'+service_date+'</td>';
						temp_html += '<td>'+obj.statement_paid_invoices[i].hospice_account_number+'</td>';

						temp_html += '<td>';
						temp_html += obj.statement_paid_invoices[i].hospice_name;
						temp_html += '</br> <a href="javascript:void(0)" data-invoice-id="'+obj.statement_paid_invoices[i].acct_statement_invoice_id+'"';
						temp_html += ' data-account-number="'+obj.statement_paid_invoices[i].hospice_account_number+'" data-account="'+obj.statement_paid_invoices[i].hospice_name+'"';
						temp_html += ' data-hospice-id="'+obj.statement_paid_invoices[i].hospiceID+'"';
						temp_html += ' data-statement-no="'+obj.statement_paid_invoices[i].statement_no+'" name="comment-modal"';
						temp_html += ' style="text-decoration:none;cursor:pointer; text-align: center;"';
						temp_html += ' class="hidden-print view_comments notes_count_'+obj.statement_paid_invoices[i].acct_statement_invoice_id+'">';
						temp_html += '<div class="row">';
						temp_html += '<div class="col-md-6" style="padding-right: 0px !important"><i class="icon-speech pull-right"></i></div>';
						temp_html += '<div class="col-md-6" style="padding-left: 0px !important"><p class="dot pull-left" style="float: right;margin-top: -3px;margin-right: 11px; '+temp_background_color+'">';
						temp_html += obj.note_count[i];
						temp_html += '</p></div>';
						temp_html += '</div>';
						temp_html += '</a>';
						temp_html += '</td>';

						var temptotaltemp = parseFloat(obj.statement_paid_invoices[i].total) + parseFloat(obj.statement_paid_invoices[i].non_cap) + parseFloat(obj.statement_paid_invoices[i].purchase_item);
						temptotaltemp += obj.invoices_reconciliation[i].owe;
						temptotaltemp -= obj.invoices_reconciliation[i].credit;
						temp_html += '<td>'+parseFloat(temptotaltemp).toFixed(2)+'</td>';
						temp_html += '<td>'+parseFloat(obj.statement_paid_invoices[i].payment_amount).toFixed(2)+'</td>';
						temp_html += '<td>'+payment_type+'</td>';
						temp_html += '<td>'+(obj.statement_paid_invoices[i].check_number == null ? "" : obj.statement_paid_invoices[i].check_number)+'</td>';
						temp_html += '<td>'+obj.statement_paid_invoices[i].received_by+'</td>';
						if(obj.statement_paid_invoices[i].is_reverted == 1) {
							temp_html += '<td>Chk Rtn</td>';
						} else {
							temp_html += '<td class="hidden-print">';
							temp_html += '<button class="hidden-print btn btn-xs btn-danger revert_invoice_btn" data-received-payment-id="'+obj.statement_paid_invoices[i].acct_statement_received_payment_id+'" data-invoice-id="'+obj.statement_paid_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_paid_invoices[i].hospiceID+'" data-invoice-number="'+obj.statement_paid_invoices[i].statement_no+'">';
							temp_html += 'Cancel';
							temp_html += '</button>';
							temp_html += '</td>';
						}
						
						
						temp_html += '</tr>';
					}
					if(temp_html == "") {
						temp_html = '<td colspan="13" style="text-align:center; padding: 10px"> No Payment. </td>';
					}
					reconciliation_list_tbody.html(temp_html);
					total_reconciliation_list_queried.html(obj.pagination_details.total_records);
					totalcount = obj.pagination_details.total_records;			
				});
				
				get_total_payment_amount();
			}

	  	});

	});
	
	// View Comments
    $('body').on('click','.view_comments',function(){
        var _this = $(this);
        var statement_no = $(this).attr("data-statement-no");
        console.log('statement_no',statement_no);
        var invoice_id = $(this).attr("data-invoice-id");
        var data_hospice_id = $(this).attr("data-hospice-id");
        var account_number = $(this).attr("data-account-number");
        var account = $(this).attr("data-account").replace(/ /gi, "_");
        var hospice_id = $(this).attr("data-hospice-id");
        console.log('account', account);
        modalbox(base_url + 'billing_reconciliation/get_comments/'+invoice_id+'/'+statement_no+'/'+hospice_id,{
            header:"<div style='text-align:center'>NOTE</div>",
            button: false,
        });
    });

    // View statement bill Details
    $('body').on('click','.view_invoice_details',function(){
        var _this = $(this);
        var invoice_id = $(this).attr("data-invoice-id");
        var data_hospice_id = $(this).attr("data-hospice-id");
        var header_logo = "";
        header_logo += '<div class="pull-left" style="margin-left: 30px">';
        header_logo += '<p class="logo_ahmslv" style="margin-bottom:0px; text-align: center">';
        header_logo += '<img class="logo_ahmslv_img" src="<?php echo base_url("assets/img/smarterchoice_logo.png"); ?>" alt="" style="height:50px;width:58px;"/>';
        header_logo += '</p>';
        header_logo += '<h4 class="work_order_header_first" style="font-weight:bold;margin-top:0px;font-size:17px; text-align: center"> Advantage Home Medical Services, Inc </h4>';
        header_logo += '</div>';

        //Printer Loader
        header_logo += '<div class="loader-icon pull-right" style="margin-right: 50px; display: none !important">';
        header_logo += '<i class="printer_loader_icon fa fa-print"></i>';
        header_logo += '<div class="loader"></div>';
        header_logo += '</div>';
        header_logo += '<button class="btn btn-default loaded-icon pull-right" rel="popover" data-html="true" data-toggle="popover" data-trigger="hover" data-replacement="left" data-content="Print" onclick="window.print()" style="margin-right: 50px; display: block !important">';
        header_logo += '<i class="printer_loaded_icon fa fa-print"></i>';
        header_logo += '</button>';
        header_logo += '';
        modalbox(base_url + 'billing_statement/statement_activity_details/'+invoice_id+'/'+data_hospice_id,{
            header:header_logo+'<span style="line-height: 8rem; margin-left: -200px !important;">Invoice</span>',
            button: false,
        });
    });

    //Revert Invoice
    $('body').on('click','.revert_invoice_btn',function(){
        var _this = $(this);
        var invoice_id = $(this).attr("data-invoice-id");
        var data_hospice_id = $(this).attr("data-hospice-id");
        var received_payment_id = $(this).attr("data-received-payment-id");
        var invoice_number = $(this).attr("data-invoice-number");
        jConfirm("Cancel Invoice No. "+invoice_number+" ?","Reminder",function(response){
            if(response)
            {
                $.get(base_url+'billing_reconciliation/revert_payment_history/'+received_payment_id+'/'+invoice_id, function(response){
                    console.log("resepposnse:", response);
                    var obj = $.parseJSON(response);
                    
                    setTimeout(function(){
                        if(obj['error'] == 0)
                        {
                            me_message_v2({error:0,message:obj['message']});
                            setTimeout(function(){
                                location.reload();
                            },2000);
                        }
                    },1);
                    
                });
            }
        });
    });

</script>