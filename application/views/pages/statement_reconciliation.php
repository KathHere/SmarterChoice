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
			margin: 0mm 2mm 10mm 2mm;
		}
		.page {
			font-size: 10px !important;
		}

		.select2-container {
			width: 400px !important;
		}

		.location_container {
            display: block !important;
        }

        .sample_div {
            margin-top: -45px !important;
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
</style>
<div class="sample_div"></div>
<div class="location_container">
    <!-- <strong>Date:</strong>  <?php echo date("m/d/Y"); ?> -->
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
<div class="page" ng-controller="FlotChartDemoCtrl">
	<div class="wrapper-md">

	    <div class="panel-report panel panel-default">
		    <div class="panel-heading">
		      	Reconciliation List
		    </div>
		    <div class="panel-body">

				<div class="col-xs-12 col-sm-12 col-md-12 hidden-print" style="margin-top:20px;">
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
						<span class="pull-right">
							From
							<input type="text" class="form-control choose_date filter_from" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 40px;" value="<?php echo date("m-d-Y"); ?>">
						</span>
					</div>
					<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
						<span class="pull-left">
							To
							<input type="text" class="form-control choose_date filter_to" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 25px;" value="<?php echo date("m-d-Y"); ?>">
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
						Reconciliation
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

				<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px; text-align: center;">

				  	<thead style="background-color:rgba(97, 101, 115, 0.05);">
				    	<tr>
				    		<th style="text-align: center; ">Date</th>
				    		<th style="text-align: center; ">Invoice Date</th>
				      		<th style="text-align: center; ">Invoice Number</th>
				      		<th style="text-align: center; ">Account Number</th>
				      		<th style="text-align: center; ">Account Name</th>
				      		<th style="text-align: center; ">Balance Due</th>
				      		<th style="text-align: center; ">Owe</th>
				      		<th style="text-align: center; ">Credit</th>
				      		<th style="text-align: center; ">Payment Amount</th>
				      		<th class="hidden-print" style="text-align: center; ">Action</th>
				    	</tr>
				    </thead>
				    <tbody class="reconciliation_list_tbody">
				    	<!-- <tr>
				    		<td>04/27/2019</td>
				    		<td>05/17/2019</td>
				    		<td><div class="view_statement_reconciliation" style="cursor: pointer">6284389</div></td>
				    		<td>55539</td>
				    		<td>Agape Hospice & Palliative Care</td>
				    		<td>2589.50</td>
				    		<td>589.50</td>
				    		<td></td>
				    		<td>2000.50</td>
				    		<td>
				    			<button class="cancel_recon_btn btn btn-xs btn-danger"><Strong>Cancel</Strong></button>
				    		</td>
				    	</tr>
				    	<tr>
				    		<td>04/27/2019</td>
				    		<td>05/17/2019</td>
				    		<td><div class="view_statement_reconciliation" style="cursor: pointer">6284389</div></td>
				    		<td>55539</td>
				    		<td>Agape Hospice & Palliative Care</td>
				    		<td>2589.50</td>
				    		<td>589.50</td>
				    		<td></td>
				    		<td>2000.50</td>
				    		<td>
				    			<button class="cancel_recon_btn btn btn-xs btn-danger"><Strong>Cancel</Strong></button>
				    		</td>
				    	</tr>
				    	<tr>
				    		<td>04/27/2019</td>
				    		<td>05/17/2019</td>
				    		<td><div class="view_statement_reconciliation" style="cursor: pointer">6284389</div></td>
				    		<td>55539</td>
				    		<td>Agape Hospice & Palliative Care</td>
				    		<td>2589.50</td>
				    		<td>589.50</td>
				    		<td></td>
				    		<td>2000.50</td>
				    		<td>
				    			<button class="cancel_recon_btn btn btn-xs btn-danger"><Strong>Cancel</Strong></button>
				    		</td>
				    	</tr> -->
				    	
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
					<div class="col-xxs-12 col-xs-10 col-sm-10 col-md-10"></div>
					<div class="col-xxs-12 col-xs-2 col-sm-2 col-md-2">
						TOTAL: <span id="total_reconciliation_list_queried"></span>
					</div>
				</div>

			</div>
			<div class="col-md-12 hidden-print">
		        <hr />
		        <button class="btn btn-default print-iframe" onclick="window.print()" style="margin-right:10px;" ><i class="fa fa-print"></i> Print</button>
		        <button type="button" class="btn btn-info pull-right create_reconciliation_modal" >Create Reconciliation</button>
		    </div>
		</div>
		<!-- <div class="bg-light lter wrapper-md" style="margin-top:-20px;margin-left:-20px;margin-bottom:-15px;">
		   <button class="btn btn-default print_activity_status" ><i class="fa fa-print"></i> Print</button>
		</div> -->
	</div>

</div>

<div class="modal fade" id="loader_modal" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 600px !important;">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
            <div class="modal-header">
                <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reminder</h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
                <div style="text-align: center; margin: 20px"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                <div id="loading_more" style="text-align:center;font-size:18px; margin: 30px">Creating reconciliation. Please wait... </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default btn-create-invoice-close pull-left">Cancel</button>
                <button type="button" class="btn btn-primary btn-order save_floor_mat_capped">Save changes</button>
            </div> -->
        </div>
    </div>
</div>

<script type="text/javascript">
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
			reconciliation_list_tbody.html("<tr><td colspan='10' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");

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

			var final_html = "";
			var pagenum = 1;
			if(typeof(page)!="undefined"){
				pagenum = page*1;
			}
			console.log('nasud');
			$.post(base_url+"billing_reconciliation/load_more_reconciliation_list/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ pagenum,"", function(response){
				var obj = JSON.parse(response);

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
				for(var i = 0; i < obj.reconciliation_list.length; i++){
					var new_inv_date = '';

					if(obj.reconciliation_list[i].invoice_date == "0000-00-00" || obj.reconciliation_list[i].invoice_date == null) {
						new_inv_date = "";
					} else {
						var inv_date = obj.reconciliation_list[i].invoice_date.split(" ")[0].split("-");
						new_inv_date = inv_date[1]+"/"+inv_date[2]+"/"+inv_date[0];
					}

					var new_date = '';

					if(obj.reconciliation_list[i].date_created == "0000-00-00 00:00:00" || obj.reconciliation_list[i].date_created == null) {
						new_date = "";
					} else {
						var date = obj.reconciliation_list[i].date_created.split(" ")[0].split("-");
						new_date = date[1]+"/"+date[2]+"/"+date[0];
					}

					console.log("id=",i,": ", obj.reconciliation_list[i]);
					// var temp_inv_number = obj.reconciliation_list[i].statement_no == "" || obj.reconciliation_list[i].statement_no == null ? "" : obj.reconciliation_list[i].statement_no.substr(3, 7);
					if (obj.reconciliation_list[i].statement_no != "" && obj.reconciliation_list[i].statement_no != null) {
						var temp_inv_number = obj.reconciliation_list[i].statement_no == "" || obj.reconciliation_list[i].statement_no == null ? "" : obj.reconciliation_list[i].statement_no.substr(3, 7);
					} else {
						var temp_inv_number = obj.reconciliation_list[i].inv_no;
					}

					if (temp_inv_number == 0 || temp_inv_number == "0") {
						temp_inv_number = '';
					}
					
					var temp_html = '<td class="view_statement_reconciliation" data-invoice-id="'+obj.reconciliation_list[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.reconciliation_list[i].hospiceID+'" data-reconcile-id="'+obj.reconciliation_list[i].acct_statement_reconciliation_id+'" style="cursor: pointer"><span style="border-bottom:dashed 1px #0088cc;">'+new_date+'</span></td><td>'+new_inv_date+'</td><td><div ><span style="border-bottom:dashed 1px #0088cc;">'+temp_inv_number+'</span></div></td><td>'+obj.reconciliation_list[i].hospice_account_number+'</td><td>'+obj.reconciliation_list[i].hospice_name+'</td><td>'+parseInt(obj.reconciliation_list[i].balance_due).toFixed(2)+'</td><td>'+parseFloat(obj.reconciliation_list[i].b_owe).toFixed(2)+'</td><td>'+parseFloat(obj.reconciliation_list[i].b_credit).toFixed(2)+'</td><td>'+parseFloat(obj.reconciliation_list[i].pay_amount).toFixed(2)+'</td><td class="hidden-print"><button class="cancel_recon_btn btn btn-xs btn-danger" data-reconcile-id="'+obj.reconciliation_list[i].acct_statement_reconciliation_id+'"><Strong>Cancel</Strong></button></td>';

					final_html += '<tr>'+temp_html+'</tr>';
				}
				if(final_html == "") {
					final_html = '<td colspan="10" style="text-align:center; padding: 10px"> No Reconciliation. </td>';
				}
				reconciliation_list_tbody.html(final_html);
				total_reconciliation_list_queried.html(obj.pagination_details.total_records);
				// <td>04/27/2019</td>
				// <td>05/17/2019</td>
				// <td><div class="view_statement_reconciliation" style="cursor: pointer">6284389</div></td>
				// <td>55539</td>
				// <td>Agape Hospice & Palliative Care</td>
				// <td>2589.50</td>
				// <td>589.50</td>
				// <td></td>
				// <td>2000.50</td>
				// <td>
				// 	<button class="cancel_recon_btn btn btn-xs btn-danger"><Strong>Cancel</Strong></button>
				// </td>
				
			});
		}
	};

	$(document).ready(function(){
		reconciliation_list_content();

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
					 reconciliation_list_content();
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
				reconciliation_list_tbody.html("<tr><td colspan='10' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");

				var final_html = "";
				var pagenum = 1;
				if(typeof(page)!="undefined"){
					pagenum = page*1;
				}
				console.log('nasud');
				$.post(base_url+"billing_reconciliation/load_more_reconciliation_list/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ pagenum,"", function(response){
					var obj = JSON.parse(response);

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
					for(var i = 0; i < obj.reconciliation_list.length; i++){
						var new_inv_date = '';

						if(obj.reconciliation_list[i].invoice_date == "0000-00-00" || obj.reconciliation_list[i].invoice_date == null) {
							new_inv_date = "";
						} else {
							var inv_date = obj.reconciliation_list[i].invoice_date.split(" ")[0].split("-");
							new_inv_date = inv_date[1]+"/"+inv_date[2]+"/"+inv_date[0];
						}

						var new_date = '';

						if(obj.reconciliation_list[i].date_created == "0000-00-00 00:00:00" || obj.reconciliation_list[i].date_created == null) {
							new_date = "";
						} else {
							var date = obj.reconciliation_list[i].date_created.split(" ")[0].split("-");
							new_date = date[1]+"/"+date[2]+"/"+date[0];
						}

						console.log("id=",i,": ", obj.reconciliation_list[i].statement_no);
						// var temp_inv_number = obj.reconciliation_list[i].statement_no == "" || obj.reconciliation_list[i].statement_no == null ? "" : obj.reconciliation_list[i].statement_no.substr(3, 7);
						if (obj.reconciliation_list[i].statement_no != "" && obj.reconciliation_list[i].statement_no != null) {
							var temp_inv_number = obj.reconciliation_list[i].statement_no == "" || obj.reconciliation_list[i].statement_no == null ? "" : obj.reconciliation_list[i].statement_no.substr(3, 7);
						} else {
							var temp_inv_number = obj.reconciliation_list[i].inv_no;
						}

						if (temp_inv_number == 0 || temp_inv_number == "0") {
							temp_inv_number = '';
						}

						var temp_html = '<td class="view_statement_reconciliation" data-invoice-id="'+obj.reconciliation_list[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.reconciliation_list[i].hospiceID+'" data-reconcile-id="'+obj.reconciliation_list[i].acct_statement_reconciliation_id+'" style="cursor: pointer"><span>'+new_date+'</span></td><td>'+new_inv_date+'</td><td><div >'+temp_inv_number+'</div></td><td>'+obj.reconciliation_list[i].hospice_account_number+'</td><td>'+obj.reconciliation_list[i].hospice_name+'</td><td>'+parseFloat(obj.reconciliation_list[i].balance_due).toFixed(2)+'</td><td>'+parseFloat(obj.reconciliation_list[i].b_owe).toFixed(2)+'</td><td>'+parseFloat(obj.reconciliation_list[i].b_credit).toFixed(2)+'</td><td>'+parseFloat(obj.reconciliation_list[i].pay_amount).toFixed(2)+'</td><td><button class="cancel_recon_btn btn btn-xs btn-danger" data-reconcile-id="'+obj.reconciliation_list[i].acct_statement_reconciliation_id+'"><Strong>Cancel</Strong></button></td>';


						final_html += '<tr>'+temp_html+'</tr>';
					}
					if(final_html == "") {
						final_html = '<td colspan="10" style="text-align:center; padding: 10px"> No Reconciliation. </td>';
					}
					reconciliation_list_tbody.html(final_html);
					total_reconciliation_list_queried.html(obj.pagination_details.total_records);
					// <td>04/27/2019</td>
					// <td>05/17/2019</td>
					// <td><div class="view_statement_reconciliation" style="cursor: pointer">6284389</div></td>
					// <td>55539</td>
					// <td>Agape Hospice & Palliative Care</td>
					// <td>2589.50</td>
					// <td>589.50</td>
					// <td></td>
					// <td>2000.50</td>
					// <td>
					// 	<button class="cancel_recon_btn btn btn-xs btn-danger"><Strong>Cancel</Strong></button>
					// </td>
					
				});
			}
	  	});


		  

		$('body').on('click','.cancel_recon_btn',function(){
			var recon_id = $(this).attr("data-reconcile-id");
			console.log('data-reconcile-id', recon_id);

			jConfirm("Cancel?","Reminder",function(response){
				if (response) {
					console.log('data-reconcile-id', recon_id);
					$.post(base_url + 'billing_reconciliation/cancel_reconcilation/' + recon_id, function(response){
						var parse_data = JSON.parse(response);
						console.log('parse_data', parse_data);

						me_message_v2({error:parse_data.error, message: parse_data.message});
						setTimeout(function(){
							location.reload();
						},1000);    
					});
				}
			});
	  	});
	});

</script>
