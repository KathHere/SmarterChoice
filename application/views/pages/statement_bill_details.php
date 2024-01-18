<style type="text/css">

	#assign_equip_filter input
	{
	  margin-left: 13px;
	}

	select.input-sm
	{
	  margin-left: 11px;
	  margin-right: 11px;
	}

	.statement_label_wrapper {
		padding:10px;
		padding-bottom: 0px;
		padding-top: 0px;
	}
	.statement_label_tag {
		font-weight: bold;
		margin-right: 10px;
		text-transform: uppercase;
	}

	.statement_label_tag_italize {
		font-weight: bold;
		margin-right: 10px;
		font-style: italic !important;
		padding:10px;
		text-align: center;
	}

	.scroll-horizontal {
		width:1200px;
		overflow-x: scroll;
	    overflow-y: hidden;
	    -webkit-overflow-scrolling: touch;
	}

	.border-right-none {
		border-right: none;
	}

	.border-left-none {
		border-right: none;
	}

	.with-border-right {
		border-right: 1px solid !important;
    	border-color: #000 !important; 
	}

	.with-border-left {
		border-left: 1px solid !important;
    	border-color: #000 !important; 
	}

	.with-border-bottom {
		border-bottom: 1px solid !important;
    	border-color: #000 !important; 
	}

	.with-border-top {
		border-top: 1px solid !important;
    	border-color: #000 !important; 
	}

	.compress-center {
		padding-left:0px !important;
		padding-right:0px !important;
		text-align: center;
	}
	.with-border {
		/* border-right:none !important; */
		border: 1px solid !important;
    	border-color: #000 !important; 
	}
	.td-info-box {
		padding-top: 1px !important;
		padding-bottom: 1px !important;
	}
	.td-info-header {
		text-align: center !important;
	}
	.td-address-box {
		padding-top: 8px !important;
		padding-bottom: 8px !important;
	}
	.td-order-summary {
		padding-top: 1px !important;
		padding-bottom: 1px !important;
	}
	.sticky {
	  position: -webkit-sticky;
	  position: sticky;
	  top: 0;
	  padding-top: 80px;
	  margin-top: -50px !important;
	  z-index: 1000;
	  /*font-size: 20px;*/
	}

	.box-bottom-shadow {
	    /*content:"";*/
	    /*position:absolute;*/
	    width:100%;
	    bottom:1px;
	    /*z-index:-1;*/
	    /*transform:scale(.9);*/
	    box-shadow: 0px 0px 8px 2px #A8A8A8;
	}

	.modal-dialog {
		width: 1200px;
	}

	.panel-heading {
		text-transform: uppercase !important;
	}

	.datatable_table_statement_bill {
		text-transform: uppercase !important;
	}

	.shipping_address {
		text-transform:uppercase !important;
	}

	.billing_address {
		text-transform: uppercase !important;
	}

	.payments_to {
		text-transform: uppercase !important;
	}

	@media print{
		@page {
			size: portrait !important;
			margin: 8mm 12mm 12mm 12mm;
			/* margin-top: 0 !important; 
        	margin-bottom: 0 !important;  */
		}

		.d-flex-address {
			width: 50% !important;
		}

		.d-flex-info-box {
			width: 50% !important;
		}

		.header-style {
			width: 55px !important;
		}

		.header-style-qty {
			width: 45px !important;
		}


		#dashed_lines {
			border-bottom: 1px dashed black !important;
		}

		.modal-content
	    {
	        border:0px !important;
	    }
	    .modal-header
	    {
	        display:none !important;
	    }

	    .hidden-header {
	    	display: block !important;
	    }

	    .modal {
		    position: absolute;
		    left: 0;
		    top: 0;
		    margin: 0;
		    margin-top: -20px !important;
		    padding: 0;
		    visibility: visible;
		    /**Remove scrollbar for printing.**/
		    overflow: visible !important;
		}
		.modal-dialog {
		    visibility: visible !important;
		    /**Remove scrollbar for printing.**/
		    overflow: visible !important;
		}

		.statement_draft_container {
			display: none !important;
		}

		.order_summary_label_container {
			text-align: center;
		}

		.row {
		  display: flex;
		  flex-direction: row;
		  flex-wrap: wrap;
		  width: 100%;
		}

		.col, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
		  display: flex;
		  flex-direction: column;
		  flex-basis: 100%;
		  flex: 1;
		}

		/*.shipping_address_container {
			height: 120px !important;
		}

		.billing_address_container {
			height: 120px !important;
		}*/

		.order_summary_container, .datatable_table_statement_bill, .statement_first_page, .customer_name_container {
			font-size: 10px !important;
		}

		.header_container {
			margin-left: 0px !important;
		}
		.ahms_logo_container2 {
			/*margin-top: -20px !important;*/
			/*text-align: center !important;*/
			/*display: block !important;*/
			margin-left: -350px !important;
		}

		.logo_ahmslv2 {
			text-align: center !important;
			display: block !important;
		}
		.logo_ahmslv_img {
			height: 25px !important;
			width: 33px !important;
		}
		.work_order_header_first2 {
			text-align: center !important;
			font-size: 12px !important;
			display: block !important;
		}
		.account_statement_header {
			margin-left: -600px !important;
		}
		.account_statement_container {
			text-align: center !important;
			font-size: 14px !important;
			margin-left: -150px !important;
			font-weight: bold !important;
			text-transform: uppercase !important;
		}
		.location_container {
			display: block !important;
		}
	}

	.logo_ahmslv2 {
		display: none;
	}

	.work_order_header_first2 {
		display: none;
	}

	.retrieving_data {
		font-size: 11px;
		font-weight: bold;
		/*margin-top: -140px;*/
	}

	.location_container {
		/*position: absolute;*/
		/*margin-top: -10px;*/
		margin-left: 25px;
		font-size: 13px;
		/*top: 0;*/
		left: 0;
		display: none;
	}
</style>

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
<div class="row header_container">
	<div class="col-md-6 ahms_logo_container2">
		<p class="logo_ahmslv2" style="margin-bottom:0px; text-align: center">
			<img class="logo_ahmslv_img" src="<?php echo base_url('assets/img/smarterchoice_logo.png'); ?>" alt="" style="height:50px;width:58px;"/>
		</p>
		<h4 class="work_order_header_first2" style="font-weight:bold;margin-top:0px;font-size:17px; text-align: center"> Advantage Home Medical Services, Inc </h4>
	</div>
	<div class="col-md-6 account_statement_header">
		<div class="row hidden-header" style="text-align: center; margin-bottom: 20px; font-size: 30px; font-weight: bold;display: none;">DRAFT DETAILS</div>
	</div>
</div>
<div class="wrapper-md statement_first_page" style="margin-bottom:-40px">
	<div style="display: flex;">
		<div class="d-flex-address" style="padding-right: 20px; width: 60%">
			<table class="table bg-white b-a" style="text-transform: uppercase !important;">
				<tr>
					<td class="with-border td-info-box td-info-header"><strong>Shipping Address</strong></td>
				</tr>
				<tr>
					<td class="with-border td-address-box">
						<!-- COMPASSIONCARE HOSPICE </br>
						4330 S EASTERN AVE, </br>
						BUILDING #7 APT #216 GC-KEY KEY 0152, </br>
						Las Vegas, NV, 89119 -->
						<?php
							echo $account['hospice_name'];
						?> 
						</br>
						<?php 
							echo $account['s_street'];
							if($account['s_street'] != null && $account['s_street'] != "") {
								echo ', ';
							}
							echo $account['s_placenum'];
							echo '</br>';
							echo $account['s_city'];
							if($account['s_city'] != null && $account['s_city'] != "") {
								echo ', ';
							}
							echo $account['s_state'];
							if($account['s_state'] != null && $account['s_state'] != "") {
								echo ', ';
							}
							echo $account['s_postalcode'];
							// ==================== OLD WAY OF DISPLAYING THE ADDRESS
							// $temp_address = explode(", ", $account['hospice_address']); 
							// $count_address = 0;
							// foreach($temp_address as $key => $address) {
							// 	if($count_address < 1) {
							// 		echo $address.'</br>'; 
							// 	}
							// 	else {
							// 		if($count_address == 1){
							// 			echo $address;
							// 		}
							// 		else {
							// 			echo ', '.$address;
							// 		}
									
							// 	}
							// 	$count_address++;
							// }
							// ==================== OLD WAY OF DISPLAYING THE ADDRESS
						?>
					</td>
				</tr>
			</table>
			<!-- <div class="panel panel-default shipping_address_container">
				<div class="panel-heading">
					<strong>Shipping Address</strong>
				</div>
				<div class="panel-body shipping_address">
					<?php
						echo $account['hospice_name'];
					?> 
					</br>
					<?php 
						echo $account['s_street'];
						if($account['s_street'] != null && $account['s_street'] != "") {
							echo ', ';
						}
						echo $account['s_placenum'];
						echo '</br>';
						echo $account['s_city'];
						if($account['s_city'] != null && $account['s_city'] != "") {
							echo ', ';
						}
						echo $account['s_state'];
						if($account['s_state'] != null && $account['s_state'] != "") {
							echo ', ';
						}
						echo $account['s_postalcode'];
						// ==================== OLD WAY OF DISPLAYING THE ADDRESS
						// $temp_address = explode(", ", $account['hospice_address']); 
						// $count_address = 0;
						// foreach($temp_address as $key => $address) {
						// 	if($count_address < 1) {
						// 		echo $address.'</br>'; 
						// 	}
						// 	else {
						// 		if($count_address == 1){
						// 			echo $address;
						// 		}
						// 		else {
						// 			echo ', '.$address;
						// 		}
								
						// 	}
						// 	$count_address++;
						// }
						// ==================== OLD WAY OF DISPLAYING THE ADDRESS
					?>
				</div>
			</div> -->
			
			<table class="table bg-white b-a" style="text-transform: uppercase !important;">
				<tr>
					<td class="with-border td-info-box td-info-header"><strong>Billing Address</strong></td>
				</tr>
				<tr>
					<td class="with-border td-address-box">
						<!-- COMPASSIONCARE HOSPICE </br>
					3890 NORTH BUFFALO DRIVE, </br>
						OLS BUILDING #1 APT #216 GC-KEY KEY 2012, </br>
						LAS VEGAS, NV, 89129 -->
						<?php
							echo $account['hospice_name'];
						?> 
						</br>
						<?php 

							if(($account['b_placenum'] != null && $account['b_placenum'] != "") || ($account['b_street'] != null && $account['b_street'] != "") || ($account['b_city'] != null && $account['b_city'] != "") || ($account['b_state'] != null && $account['b_state'] != "")) {
								echo $account['b_street'];
								if($account['b_street'] != null && $account['b_street'] != "") {
									echo ', ';
								}
								echo $account['s_placenum'];
								echo '</br>';
								echo $account['b_city'];
								if($account['b_city'] != null && $account['b_city'] != "") {
									echo ', ';
								}
								echo $account['b_state'];
								if($account['b_state'] != null && $account['b_state'] != "") {
									echo ', ';
								}
								echo $account['b_postalcode'];
							} else {
								echo $account['s_street'];
								if($account['s_street'] != null && $account['s_street'] != "") {
									echo ', ';
								}
								echo $account['s_placenum'];
								echo '</br>';
								echo $account['s_city'];
								if($account['s_city'] != null && $account['s_city'] != "") {
									echo ', ';
								}
								echo $account['s_state'];
								if($account['s_state'] != null && $account['s_state'] != "") {
									echo ', ';
								}
								echo $account['s_postalcode'];
							}
							

							// ==================== OLD WAY OF DISPLAYING THE ADDRESS
							// $temp_address = explode(", ", $account['billing_address']); 
							// $count_address = 0;
							// foreach($temp_address as $key => $address) {
							// 	if($count_address < 1) {
							// 		echo $address.'</br>'; 
							// 	}
							// 	else {
							// 		if($count_address == 1){
							// 			echo $address;
							// 		}
							// 		else {
							// 			echo ', '.$address;
							// 		}
									
							// 	}
							// 	$count_address++;
							// }
							// ==================== OLD WAY OF DISPLAYING THE ADDRESS
						?>
					</td>
				</tr>
			</table>
			<!-- <div class="panel panel-default billing_address_container">
				<div class="panel-heading">
					<strong>Billing Address</strong>
				</div>
				<div class="panel-body billing_address">
					<?php
						echo $account['hospice_name'];
					?> 
					</br>
					<?php 

						if(($account['b_placenum'] != null && $account['b_placenum'] != "") || ($account['b_street'] != null && $account['b_street'] != "") || ($account['b_city'] != null && $account['b_city'] != "") || ($account['b_state'] != null && $account['b_state'] != "")) {
							echo $account['b_street'];
							if($account['b_street'] != null && $account['b_street'] != "") {
								echo ', ';
							}
							echo $account['s_placenum'];
							echo '</br>';
							echo $account['b_city'];
							if($account['b_city'] != null && $account['b_city'] != "") {
								echo ', ';
							}
							echo $account['b_state'];
							if($account['b_state'] != null && $account['b_state'] != "") {
								echo ', ';
							}
							echo $account['b_postalcode'];
						} else {
							echo $account['s_street'];
							if($account['s_street'] != null && $account['s_street'] != "") {
								echo ', ';
							}
							echo $account['s_placenum'];
							echo '</br>';
							echo $account['s_city'];
							if($account['s_city'] != null && $account['s_city'] != "") {
								echo ', ';
							}
							echo $account['s_state'];
							if($account['s_state'] != null && $account['s_state'] != "") {
								echo ', ';
							}
							echo $account['s_postalcode'];
						}
						

						// ==================== OLD WAY OF DISPLAYING THE ADDRESS
						// $temp_address = explode(", ", $account['billing_address']); 
						// $count_address = 0;
						// foreach($temp_address as $key => $address) {
						// 	if($count_address < 1) {
						// 		echo $address.'</br>'; 
						// 	}
						// 	else {
						// 		if($count_address == 1){
						// 			echo $address;
						// 		}
						// 		else {
						// 			echo ', '.$address;
						// 		}
								
						// 	}
						// 	$count_address++;
						// }
						// ==================== OLD WAY OF DISPLAYING THE ADDRESS
					?>
				</div>
			</div> -->
			
			<table class="table bg-white b-a" style="text-transform: uppercase !important;">
				<tr>
					<td class="with-border td-info-box td-info-header"><strong>Send all Payments to</strong></td>
				</tr>
				<tr>
					<td class="with-border td-address-box">
						<span>Advantage Home Medical Services, Inc</span> </br>
						<span>2915 Losee Rd Ste. 108</span> </br>
						<span>North Las Vegas, NV 89030</span> </br>
						<!-- <span>Ph. 702-248-0056  Fax: 702-889-0059</span> -->
					</td>
				</tr>
			</table>
			<!-- Version 1 -->
			<!-- <div class="panel panel-default">
				<div class="panel-heading">
					<strong>Notes</strong>
				</div>
				<div class="panel-body">
					<textarea id="notes" style="width: 100%; height: 51px; border: none; padding: 10px; resize: none" placeholder="Add note..."></textarea>
				</div>
			</div> -->

		</div>

		<div class="d-flex-info-box" style="width: 40%">
			<table class="table bg-white b-a" style="">
				<tr>
					<td class="with-border td-info-box" colspan="2" style="text-align: center;">
						<input type="hidden" id="service_date_from" value="<?php echo date('Y-m-d', strtotime($draft_details['service_date_from'])); ?>">
						<input type="hidden" id="service_date_to" value="<?php echo date('Y-m-d', strtotime("-1 days")); ?>">
						<input type="hidden" id="temporary_service_date_to" value="<?php echo date("m/t/Y", strtotime(date("m/d/Y", strtotime($draft_details['service_date_from']))));?>">
						<strong>Service Date:</strong> 
						<?php echo date('m/d/Y', strtotime($draft_details['service_date_from'])); ?> - <?php echo date("m/t/Y", strtotime(date("m/d/Y", strtotime($draft_details['service_date_from']))));?>
					</td>
				</tr>
				<tr>
					<td class="with-border td-info-box" colspan="2" style="text-align: center;">
						<?php
							if($account['payment_terms'] == null) {
								$acc_pay_term = "No Payment Term";
							} else {
								$acc_pay_term = "NET ".str_replace('_', ' ', $account['payment_terms']); 
							}
						?>
						<strong>PAYMENT TERMS:</strong> <?php echo $acc_pay_term;?>
					</td>
				</tr>
				<tr>
					<td class="with-border td-info-box">
						<label class="statement_label_tag">Account Number: </label><span> <?php echo $account['hospice_account_number']; ?></span>
					</td>
					<td class="with-border td-info-box">
						<label class="statement_label_tag">Non Capped: </label><span id="noncaptotal"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</td>
				</tr>
				<tr>
					<td class="with-border td-info-box">
						<input type="hidden" id="acct_statement_bill_id" value="<?php echo $draft_details['acct_statement_bill_id']; ?>">
						<input type="hidden" id="statement_no" value="<?php echo $draft_details['statement_no']; ?>">
						<label class="statement_label_tag">Statement Number: </label><span data-statement-no=""> <?php echo substr($draft_details['statement_no'],3,10); ?></span>
					</td>
					<td class="with-border td-info-box">
						<label class="statement_label_tag">Sale Item: </label><span id="purchasetotal"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</td>
				</tr>
				<tr>
					<td class="with-border td-info-box">
						<label class="statement_label_tag">Invoice Date: </label><span></span>
					</td>
					<td class="with-border td-info-box">
						<label class="statement_label_tag">Subtotal: </label><span id="subtotal" data-total="0"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</td>
				</tr>
				<tr>
					<td class="with-border td-info-box">
						<label class="statement_label_tag">Due Date: </label>
					</td>
					<td class="with-border td-info-box">
						<label class="statement_label_tag">Credit: </label>
						<span id="credittotal" data-total="0">
							<i class="fa fa-spin fa-spinner item_decription_spinner"></i>
						</span>
					</td>
				</tr>
				<tr>
					<td class="with-border td-info-box">
						<label class="statement_label_tag">Daily Rate: </label>
						<span>
							<?php 

								if($account['daily_rate'] != 0 && $account['daily_rate'] != "" && $account['daily_rate'] != null) {
									echo number_format((float)$account['daily_rate'], 2, '.', ''); 
								}
								
							?>
							<input type="hidden" id="account_daily_rate" value="<?php echo $account['daily_rate']; ?>">
						</span>
					</td>
					<td class="with-border td-info-box">
						<label class="statement_label_tag">Owe: </label>
						<span id="balanceowetotal" data-total="0">
							<i class="fa fa-spin fa-spinner item_decription_spinner"></i>
						</span>
					</td>
				</tr>
				<tr>
					<?php
					$addcolspan = '';
					if ($account['track_census'] != 0) {
						$addcolspan = 'colspan="2"';
					}
					?>
					<td class="with-border td-info-box" <?php echo $addcolspan; ?>>
						<?php
							$lineheight = "";
							if($account['track_census'] == 1) {
								$lineheight = "line-height: 2.5";
							}
						?>
						<label class="statement_label_tag" style="padding-right: 0px !important; padding-left: 0px !important; <?php echo $lineheight; ?>">Customer Days: </label>
						<input type="hidden" id="hospice_track_census" value="<?php echo $account['track_census']; ?>" />
						<!-- <div class="" style="padding-left: 0px !important; margin-left: -15px !important"> -->
						<?php
							if($account['track_census'] == 0) {
						?>
							<span id="customerdays" class=""><i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
						<?php
							} else {
						?>
							<div class="row">
								<div class="col-md-8">
									<input type="number" class="form-control" id="customerdays" value="">
								</div>
								<div class="col-md-4" style="padding-left: 0px !important">
									<button type="button" class="btn btn-success btn-md save_customerdays">
										<i class="glyphicon glyphicon-ok"></i>
									</button>
								</div>
							</div>
							
						<?php
							}
						?>
						<!-- </div> -->
					</td>
					<?php if($account['track_census'] == 0) { ?>
						<td class="with-border td-info-box"></td>
					<?php } ?>
				</tr>
				<tr>
					<td class="with-border td-info-box">
						<label class="statement_label_tag">Total: </label><span id="totaltotal" data-total="<?php echo ($account['daily_rate']*$customer_days[0]['cus_days']) ?>"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</td>
					<td class="with-border td-info-box">
						<label class="statement_label_tag">Payment Due: </label><span id="totalbalancedue" data-total="0"><i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</td>
				</tr>
			</table>
			<!-- Version 2 -->
			<!-- <div class="panel panel-default">
				<div class="panel-heading">
					<div style="text-align: center;">
						<input type="hidden" id="service_date_from" value="<?php echo date('Y-m-d', strtotime($draft_details['service_date_from'])); ?>">
						<input type="hidden" id="service_date_to" value="<?php echo date('Y-m-d', strtotime("-1 days")); ?>">
						<input type="hidden" id="temporary_service_date_to" value="<?php echo date("m/t/Y", strtotime(date("m/d/Y", strtotime($draft_details['service_date_from']))));?>">
						<strong>Service Date:</strong> 
						<?php echo date('m/d/Y', strtotime($draft_details['service_date_from'])); ?> - <?php echo date("m/t/Y", strtotime(date("m/d/Y", strtotime($draft_details['service_date_from']))));?>
					</div>
				</div>
				<table class="table bg-white b-a" style="">
					<tr>
						<td class="with-border td-info-box" colspan="2" style="text-align: center;">
							<?php
								if($account['payment_terms'] == null) {
									$acc_pay_term = "No Payment Term";
								} else {
									$acc_pay_term = "NET ".str_replace('_', ' ', $account['payment_terms']); 
								}
							?>
							<strong>PAYMENT TERMS:</strong> <?php echo $acc_pay_term;?>
						</td>
					</tr>
					<tr>
						<td class="with-border td-info-box">
							<label class="statement_label_tag">Account Number: </label><span> <?php echo $account['hospice_account_number']; ?></span>
						</td>
						<td class="with-border td-info-box">
							<label class="statement_label_tag">Non Capped: </label><span id="noncaptotal"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
						</td>
					</tr>
					<tr>
						<td class="with-border td-info-box">
							<input type="hidden" id="acct_statement_bill_id" value="<?php echo $draft_details['acct_statement_bill_id']; ?>">
							<input type="hidden" id="statement_no" value="<?php echo $draft_details['statement_no']; ?>">
							<label class="statement_label_tag">Statement Number: </label><span data-statement-no=""> <?php echo substr($draft_details['statement_no'],3,10); ?></span>
						</td>
						<td class="with-border td-info-box">
							<label class="statement_label_tag">Sale Item: </label><span id="purchasetotal"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
						</td>
					</tr>
					<tr>
						<td class="with-border td-info-box">
							<label class="statement_label_tag">Invoice Date: </label><span></span>
						</td>
						<td class="with-border td-info-box">
							<label class="statement_label_tag">Subtotal: </label><span id="subtotal" data-total="0"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
						</td>
					</tr>
					<tr>
						<td class="with-border td-info-box">
							<label class="statement_label_tag">Due Date: </label>
						</td>
						<td class="with-border td-info-box">
							<label class="statement_label_tag">Credit: </label><span id="credittotal" data-total="0"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
						</td>
					</tr>
					<tr>
						<td class="with-border td-info-box">
							<label class="statement_label_tag">Daily Rate: </label>
							<span>
								<?php 

									if($account['daily_rate'] != 0 && $account['daily_rate'] != "" && $account['daily_rate'] != null) {
										echo number_format((float)$account['daily_rate'], 2, '.', ''); 
									}
									
								?>
							</span>
						</td>
						<td class="with-border td-info-box">
							<label class="statement_label_tag">Owe: </label><span id="balanceowetotal" data-total="0"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
						</td>
					</tr>
					<tr>
						<?php
						$addcolspan = '';
						if ($account['track_census'] != 0) {
							$addcolspan = 'colspan="2"';
						}
						?>
						<td class="with-border td-info-box" <?php echo $addcolspan; ?>>
							<?php
								$lineheight = "";
								if($account['track_census'] == 1) {
									$lineheight = "line-height: 2.5";
								}
							?>
							<label class="statement_label_tag" style="padding-right: 0px !important; padding-left: 0px !important; <?php echo $lineheight; ?>">Customer Days: </label>
							<input type="hidden" id="hospice_track_census" value="<?php echo $account['track_census']; ?>" />
							<?php
								if($account['track_census'] == 0) {
							?>
								<span id="customerdays" class=""><i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
							<?php
								} else {
							?>
								<div class="row">
									<div class="col-md-8">
										<input type="number" class="form-control" id="customerdays" value="">
									</div>
									<div class="col-md-4" style="padding-left: 0px !important">
										<button type="button" class="btn btn-success btn-md save_customerdays">
											<i class="glyphicon glyphicon-ok"></i>
										</button>
									</div>
								</div>
								
							<?php
								}
							?>
						</td>
						<?php if($account['track_census'] == 0) { ?>
							<td></td>
						<?php } ?>
					</tr>
					<tr>
						<td class="with-border td-info-box">
							<label class="statement_label_tag">Total: </label><span id="totaltotal" data-total="<?php echo ($account['daily_rate']*$customer_days[0]['cus_days']) ?>"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
						</td>
						<td class="with-border td-info-box">
							<label class="statement_label_tag">Balance Due: </label><span id="totalbalancedue" data-total="0"><i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
						</td>
					</tr>
				</table>
			</div> -->

			<!-- Version 1 -->
			<!-- <div class="panel panel-default">
				<div class="panel-heading">
					<strong>Information</strong>
					<span class="pull-right">
						
						<input type="hidden" id="service_date_from" value="<?php echo date('Y-m-d', strtotime($draft_details['service_date_from'])); ?>">
						<input type="hidden" id="service_date_to" value="<?php echo date('Y-m-d', strtotime("-1 days")); ?>">
						<input type="hidden" id="temporary_service_date_to" value="<?php echo date("m/t/Y", strtotime(date("m/d/Y", strtotime($draft_details['service_date_from']))));?>">
						<strong>Service Date:</strong> 
						<?php echo date('m/d/Y', strtotime($draft_details['service_date_from'])); ?> - <?php echo date("m/t/Y", strtotime(date("m/d/Y", strtotime($draft_details['service_date_from']))));?>
					</span>
				</div>
				<div class="panel-body">
					<div class="statement_label_wrapper">
						<label class="statement_label_tag">Account Number: </label><span> <?php echo $account['hospice_account_number']; ?></span>
					</div>
					<div class="statement_label_wrapper">
						<input type="hidden" id="acct_statement_bill_id" value="<?php echo $draft_details['acct_statement_bill_id']; ?>">
						<input type="hidden" id="statement_no" value="<?php echo $draft_details['statement_no']; ?>">
						<label class="statement_label_tag">Statement Number: </label><span data-statement-no=""> <?php echo substr($draft_details['statement_no'],3,10); ?></span>
					</div>
					<div class="statement_label_wrapper">
						<label class="statement_label_tag">Invoice Date: </label><span></span>
					</div>
					<div class="statement_label_wrapper">
						<div class="row">
							<label class="statement_label_tag col-md-3">Due Date: </label>
							<span class="col-md-8"></span>
						</div>

					</div>

					<div class="statement_label_tag_italize">
						<?php if($account['payment_terms'] == null) {
							$acc_pay_term = "No Payment Term";
						} else {
							$acc_pay_term = "NET ".str_replace('_', ' ', $account['payment_terms']); 
						}?>
						
						PAYMENT TERMS: <?php echo $acc_pay_term; ?>
					</div>

					<div class="statement_label_wrapper">
						<label class="statement_label_tag">Daily Rate: </label>
						<span>
							<?php 

								if($account['daily_rate'] != 0 && $account['daily_rate'] != "" && $account['daily_rate'] != null) {
									echo number_format((float)$account['daily_rate'], 2, '.', ''); 
								}
								
							?>
						</span>
					</div>
					<div class="statement_label_wrapper">
						<div class="row">
							<?php
								$lineheight = "";
								if($account['track_census'] == 1) {
									$lineheight = "line-height: 2.5";
								}
							?>
							<label class="statement_label_tag col-md-4" style="padding-right: 0px !important; <?php echo $lineheight; ?>">Customer Days: </label>
							<input type="hidden" id="hospice_track_census" value="<?php echo $account['track_census']; ?>" />
							<div class="col-md-7" style="padding-left: 0px !important; margin-left: -15px !important">
							<?php
								if($account['track_census'] == 0) {
							?>
								<span id="customerdays" class="pull-left"><i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
							<?php
								} else {
							?>
								<div class="row">
									<div class="col-md-8">
										<input type="number" class="form-control" id="customerdays" value="">
									</div>
									<div class="col-md-4" style="padding-left: 0px !important">
										<button type="button" class="btn btn-success btn-md save_customerdays">
											<i class="glyphicon glyphicon-ok"></i>
										</button>
									</div>
								</div>
								
							<?php
								}
							?>
							</div>
						</div>
					</div>
					<div class="statement_label_wrapper">
						<label class="statement_label_tag">Total: </label><span id="totaltotal" data-total="<?php echo ($account['daily_rate']*$customer_days[0]['cus_days']) ?>"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</div>
					<div class="statement_label_wrapper">
						<label class="statement_label_tag">Non Capped: </label><span id="noncaptotal"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</div>
					<div class="statement_label_wrapper">
						<label class="statement_label_tag">Sale Item: </label><span id="purchasetotal"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</div>
					<div class="statement_label_wrapper">
						<label class="statement_label_tag">Subtotal: </label><span id="subtotal" data-total="0"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</div>
					<div class="statement_label_wrapper">
						<label class="statement_label_tag">Credit: </label><span id="credittotal" data-total="0"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</div>
					<div class="statement_label_wrapper">
						<label class="statement_label_tag">Owe: </label><span id="balanceowetotal" data-total="0"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</div>
					<div class="statement_label_wrapper">
						<label class="statement_label_tag">Balance Due: </label><span id="totalbalancedue" data-total="0"><i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
					</div>
				</div>
			</div> -->
							
			<table class="table bg-white b-a" style="text-transform: uppercase !important;">
				<tr>
					<td class="with-border td-info-box td-info-header"><strong>Notes</strong></td>
				</tr>
				<tr>
					<td class="with-border td-info-box">
						<textarea id="notes" style="width: 100%; height: 50px; border: none; padding: 10px; resize: none; box-shadow: none !important" placeholder="" readonly></textarea>
					</td>
				</tr>
			</table>
			<!-- Version 1 -->
			<!-- <div class="panel panel-default">
				<div class="panel-heading" style="text-align: center">
					<strong>Send all Payments to</strong>
				</div>
				<div class="panel-body payments_to">
					<span>Advantage Home Medical Services, Inc</span> </br>
					<span>2915 Losee Rd Ste. 108</span> </br>
					<span>North Las Vegas, NV 89030</span> </br>
				</div>
			</div> -->
		</div>
	</div>
	
</div>

<hr id="dashed_lines" style="border-bottom: 1px dashed #edf1f2" />
<div class="wrapper-md order_summary_container" id="order_list" style="">
	<table class="table bg-white b-a datatable_table_statement_bill" style="text-align: center;  font-size: 12px;">
		<thead  style="background-color: #f6f8f8; ">
			<tr style="background-color: white;">
				<th colspan="5" class="with-border" style="border-right: 0px !important">
					<div class="text-right order_summary_label_container">
						<!-- <span class="order_summary_label" style="font-size: 18px; margin-right: 50px">Order Summary</span> -->
					</div>
				</th>
				<th colspan="7" class="with-border" style="border-left: 0px !important">
					<div class="text-right hidden-print hide_buttons" style="margin-top: 4px">
						Hide: &nbsp;&nbsp;
						<label class="i-checks ">
							<input type="checkbox" id="cap" value="1"
									name="cap"
									data-current-count="<?php echo $customers_orders['limit']; ?>"
									class="hide_items">
							<i></i>
							Capped Item
						</label>
						&nbsp;
						&nbsp;
						<label class="i-checks ">
							<input type="checkbox" id="noncap" value="2"
									name="noncap"
									data-current-count="<?php echo $customers_orders['limit']; ?>"
									class="hide_items">
							<i></i>
							Non Capped Item
						</label>
						&nbsp;
						&nbsp;
						<label class="i-checks ">
							<input type="checkbox" id="purchase" value="3"
									name="purchase"
									data-current-count="<?php echo $customers_orders['limit']; ?>"
									class="hide_items">
							<i></i>
							Sale Item
						</label>
					</div>
				</th>
			</tr>
			<tr style="background-color: white">
				<th class="compress-center with-border">Work </br> Order #</th>
				<!-- <th >Item #</th> -->
				<th class="with-border" style="text-align: center;">Customer Name</th>
				<!-- <th >MR #</th> -->
				<th class="compress-center with-border">DEL. DATE</th>
				<th class="compress-center with-border ">P/U DATE</th>
				<th class="compress-center with-border header-style-qty">QTY</th>
				<th class="compress-center with-border">Capped</th>
				<th class="compress-center with-border header-style" style="width: 77px;">SALE</th>
				<th class="compress-center with-border header-style"style="width: 87px;">Non Capped</th>
				<th class="with-border" style="text-align: center;">Total</th>
				<th class="compress-center with-border">CUS </br>Days</th>
				<th class="compress-center with-border">Daily </br> Rate</th>
				<th class="compress-center with-border">Amount </br> Due</th>
			</tr>
		</thead>
		<tbody id="order_list_tbody">
		</tbody>
	</table>
	<!-- <div class="panel panel-default">
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-7 text-right order_summary_label_container">
					<span class="order_summary_label" style="font-size: 18px; margin-right: 50px">Order Summary</span>
				</div>
				<div class="col-md-5 text-right hidden-print hide_buttons" style="margin-top: 4px">
					Hide: &nbsp;&nbsp;
					<label class="i-checks ">
						<input type="checkbox" id="cap" value="1"
									name="cap"
									data-current-count="<?php echo $customers_orders['limit']; ?>"
									class="hide_items">
							<i></i>
							Capped Item
					</label>
					&nbsp;
					&nbsp;
					<label class="i-checks ">
						<input type="checkbox" id="noncap" value="2"
									name="noncap"
									data-current-count="<?php echo $customers_orders['limit']; ?>"
									class="hide_items">
							<i></i>
							Non Capped Item
					</label>
					&nbsp;
					&nbsp;
					<label class="i-checks ">
						<input type="checkbox" id="purchase" value="3"
									name="purchase"
									data-current-count="<?php echo $customers_orders['limit']; ?>"
									class="hide_items">
							<i></i>
						Sale Item
					</label>
				</div>
			</div>

		</div>
		  <table class="table bg-white b-a datatable_table_statement_bill" style="text-align: center;  font-size: 12px;">
		  	<thead  style="background-color: #f6f8f8; ">
		  		<tr style="background-color: white">
		  			<th class="compress-center with-border td-info-box">Work </br> Order #</th>
		  			<th style="text-align: center;border-right:none">Customer Name</th>
		  			<th class="compress-center with-border td-info-box">DEL. DATE</th>
		  			<th class="compress-center with-border td-info-box">P/U DATE</th>
		  			<th class="compress-center with-border td-info-box header-style-qty">QTY</th>
		  			<th class="compress-center with-border td-info-box">Capped</th>
		  			<th class="compress-center with-border td-info-box header-style" style="width: 77px;">SALE</th>
		  			<th class="compress-center with-border td-info-box header-style"style="width: 87px;">Non Capped</th>
		  			<th style="text-align: center; border-right:none">Total</th>
		  			<th class="compress-center with-border td-info-box">CUS </br>Days</th>
		  			<th class="compress-center with-border td-info-box">Daily </br> Rate</th>
		  			<th class="compress-center">Amount </br> Due</th>
		  		</tr>
		  	</thead>
		  	<tbody id="order_list_tbody">
		  	</tbody>
		  </table>
	</div> -->
	<input type="hidden" id="draft_id_hidden" value="<?php echo $draft_details['acct_statement_draft_id']; ?>">
	<div class="text-center hidden-print" id="load_more_content" style="display: none;">
    	<button class="btn btn-info" style="" id="load_more_patient" data-draft_id="<?php echo $draft_details['acct_statement_draft_id']; ?>" data-hospice-id="<?php echo $account['hospiceID']; ?>" data-current-count="<?php echo $customers_orders['limit'];?>" data-daily-rate="<?php echo $account['daily_rate']?>" data-total-count="<?php echo $customers_orders['totalCustomerCount'];?>" style="text-align:center;">Load More</button>
  	</div>
  	<!-- <div class="pull-right"><span><strong>Total Balance Due: </strong></span>  &nbsp; 34.45</div> -->
</div>

<div class="wrapper-md" style="padding-top: 0px !important; margin-bottom: 20px !important">
	<div class="pull-right">
		Payment Due: <span id="overalltotal"></span>
	</div>
</div>

<div class="lter wrapper-md hidden-print">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<script type="text/javascript">
	
	$(document).ready(function(){
		//FOR AUTOLOAD IF THE DOCUMENT IS READY
		setTimeout(function(){
			$('#load_more_patient').click();
		},1000);

		
		// $('body').on('click','.editable-click.editable-cusdays',function(){
		// 	$('.editable-click.editable-cusdays').editable({
		// 		emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
		// 		validate: function(value) {
		// 			if($.trim(value) == '') {
		// 				return 'This field is required';
		// 			}
		// 		},
		// 		success:function(response,newValue){
		// 			console.log('response', response);
		// 			var parse_data = JSON.parse(response);
		// 			console.log('parse_data', parse_data);
					
		// 			me_message_v2({error:parse_data.error, message: parse_data.message});
		// 			// if(response.error==1) return response.message;
		// 		}
		// 	});
    	// });
	
		// var current_page = 1;
		var item_counter = 0;
		var display_item_counter = 0;
		var items_for_get_full_descrip = [];
		var items_for_get_item_group = [];
  		var limit = 7;
  		var onscroll = false;
  		var hosID = $('#load_more_patient').attr("data-hospice-id");
		var draID = $('#draft_id_hidden').val();
  		var dailyrat = parseFloat($('#load_more_patient').attr("data-daily-rate"));

  		//get customer days
  		$.get(base_url+'billing_statement/get_customer_days_v2/'+hosID+'/'+draID, function(response){
			var track_census = $('#hospice_track_census').val();

			if (track_census == 1) {
				var jsonparse = JSON.parse(response);
				// console.log('cusdaysjsonparse', jsonparse);
				if(jsonparse.is_input_field == 1) {
					var cusdays = parseInt(jsonparse.customer_days);
					// console.log('cusdayscusdays', jsonparse.customer_days);
				} else {
					var cusdays = parseInt(jsonparse.customer_days[0].cus_days);
				}
				var totaltotaltotal = isNaN(dailyrat*cusdays) ? 0 : dailyrat*cusdays;
				
				if(cusdays != 0) {
					if(jsonparse.is_input_field == 1) {
						if(jsonparse.customer_days != null) {
							$('#customerdays').val(cusdays);
						}
					} else {
						if(jsonparse.customer_days[0].cus_days != null) {
							$("#customerdays").html(cusdays);
						}
					}
				} else {
					if(jsonparse.is_input_field == 1) {
						if(jsonparse.customer_days != null) {
							$('#customerdays').val(cusdays);
						}
					} else {
						$("#customerdays").html("");
					}
				}
				
				if(totaltotaltotal != 0) {
					$("#totaltotal").html(parseFloat(totaltotaltotal).toFixed(2));
				} else {
					$("#totaltotal").html("");
				}
				
				var gtotal = parseFloat($("#totalbalancedue").attr("data-total")) + (dailyrat*parseInt(cusdays));
				gtotal = isNaN(gtotal) ? 0 : gtotal;
				$("#totalbalancedue").attr("data-total", parseFloat(gtotal).toFixed(2));

				if(gtotal != 0) {
					$("#totalbalancedue").html(parseFloat(gtotal).toFixed(2));
					$("#overalltotal").html(parseFloat(gtotal).toFixed(2));
				} else {
					$("#totalbalancedue").html("0.00");
                	$("#overalltotal").html('0.00');
				}

				var subtotal = parseFloat($("#subtotal").attr("data-total")) + (dailyrat*parseInt(cusdays));
				subtotal = isNaN(subtotal) ? 0 : subtotal;
				$("#subtotal").attr("data-total", parseFloat(subtotal).toFixed(2));

				if(subtotal != 0) {
					$("#subtotal").html(parseFloat(subtotal).toFixed(2));
				} else {
					$("#subtotal").html("");
				}
			}
	 	});

  		//get total of purchase and noncap for information panel
	 	var purcatID = 3;
	 	$.get(base_url+'billing_statement/get_category_total_draft_statement/'+draID+"/"+purcatID, function(response){
	 		var purchasetotal = parseFloat(response).toFixed(2);
	 		// console.log('purchase item',response);
	 		if(purchasetotal != 0) {
	 			$("#purchasetotal").html(purchasetotal);
	 		} else {
	 			$("#purchasetotal").html("");
	 		}
	 		
	 		var gtotal = parseFloat($("#totalbalancedue").attr("data-total")) + parseFloat(response);
	 		gtotal = isNaN(gtotal) ? 0 : gtotal;
	 		$("#totalbalancedue").attr("data-total", parseFloat(gtotal).toFixed(2));

	 		if(gtotal != 0) {
	 			$("#totalbalancedue").html(parseFloat(gtotal).toFixed(2));
				$("#overalltotal").html(parseFloat(gtotal).toFixed(2));
	 		} else {
				$("#totalbalancedue").html("0.00");
                $("#overalltotal").html('0.00');
	 		}

	 		var subtotal = parseFloat($("#subtotal").attr("data-total")) + parseFloat(response);
	 		subtotal = isNaN(subtotal) ? 0 : subtotal;
	 		$("#subtotal").attr("data-total", parseFloat(subtotal).toFixed(2));

	 		if(subtotal != 0) {
	 			$("#subtotal").html(parseFloat(subtotal).toFixed(2));
	 		} else {
	 			$("#subtotal").html("");
	 		}
	 	});
		var noncatID = 2;
	 	$.get(base_url+'billing_statement/get_category_total_draft_statement/'+draID+"/"+noncatID, function(response){
	 		var noncaptotal = parseFloat(response).toFixed(2);
	 		// console.log('noncaptotal',noncaptotal);
	 		if(noncaptotal != 0) {
	 			$("#noncaptotal").html(noncaptotal);
	 		} else {
	 			$("#noncaptotal").html("");
	 		}

	 		var gtotal = parseFloat($("#totalbalancedue").attr("data-total")) + parseFloat(response);
	 		gtotal = isNaN(gtotal) ? 0 : gtotal;
	 		$("#totalbalancedue").attr("data-total", parseFloat(gtotal).toFixed(2));

	 		if(gtotal != 0) {
	 			$("#totalbalancedue").html(parseFloat(gtotal).toFixed(2));
				$("#overalltotal").html(parseFloat(gtotal).toFixed(2));
	 		} else {
				$("#totalbalancedue").html("0.00");
                $("#overalltotal").html('0.00');
	 		}

	 		var subtotal = parseFloat($("#subtotal").attr("data-total")) + parseFloat(response);
	 		subtotal = isNaN(subtotal) ? 0 : subtotal;
	 		$("#subtotal").attr("data-total", parseFloat(subtotal).toFixed(2));

	 		if(subtotal != 0) {
	 			$("#subtotal").html(parseFloat(subtotal).toFixed(2));
	 		} else {
	 			$("#subtotal").html("");
	 		}
	 	});

	 	// get the Current Reconciliation for information panel
	 	$.get(base_url+'billing_reconciliation/get_current_reconciliation_credit_and_owe_by_reference/'+draID, function(response){
	 		// console.log('response', parseFloat(JSON.parse(response).owe).toFixed(2));
	 		var credit = isNaN(parseFloat(JSON.parse(response).invoice_reconciliation.credit).toFixed(2)) ? 0 : parseFloat(JSON.parse(response).invoice_reconciliation.credit).toFixed(2);
	 		var owe = isNaN(parseFloat(JSON.parse(response).invoice_reconciliation.owe).toFixed(2)) ? 0 : parseFloat(JSON.parse(response).invoice_reconciliation.owe).toFixed(2);

			$("#notes").val(JSON.parse(response).invoice_reconciliation_comments);

	 		if(credit != 0) {
	 			$("#credittotal").html(parseFloat(credit).toFixed(2));
	 		} else {
	 			$("#credittotal").html("");
	 		}

	 		if(owe != 0) {
	 			$("#balanceowetotal").html(parseFloat(owe).toFixed(2));
	 		} else {
	 			$("#balanceowetotal").html("");
	 		}
	 		
	 		var gtotal = parseFloat($("#totalbalancedue").attr("data-total"));
	 		// console.log('gtotal', gtotal);
	 		gtotal = gtotal - Number(credit);
	 		gtotal = gtotal + Number(owe);
	 		
	 		if(gtotal > 0) {
	 			$("#totalbalancedue").html(parseFloat(gtotal).toFixed(2));
				$("#overalltotal").html(parseFloat(gtotal).toFixed(2));
	 		} else {
				$("#totalbalancedue").html("0.00");
                $("#overalltotal").html('0.00');
	 		}
	 		
	 		$("#totalbalancedue").attr("data-total", parseFloat(gtotal).toFixed(2));
	 		// console.log('parseFloat(gtotal).toFixed(2)',parseFloat(gtotal).toFixed(2));
	 	});
		
	 	//======FOR AUTOLOAD IF YOU REACH THE BOTTOM MOST OF THE PAGE======
		// $(window).scroll(function() {
		// 	console.log("parseInt($(window).scrollTop() + $(window).height()):  ",(parseInt($(window).scrollTop() + $(window).height()))," == $(document).height(): ", $(document).height());
		// 	var window_height = parseInt($(window).scrollTop() + $(window).height());
		// 	var document_height  = parseInt($(document).height());
	 //    	if((window_height <= document_height && window_height >= document_height-20) && !onscroll) {
	 //           // ajax call get data from server and append to the div
	 //        	onscroll = true;
	 //        	var currentCount = parseInt($('#load_more_patient').attr("data-current-count"));
	 //        	var totalCustomerCount = parseInt($('#load_more_patient').attr("data-total-count"));
		//  		if(currentCount < totalCustomerCount) {
		//  			$('#load_more_patient').click();
		//  		}
	 //    	}
		// });

		Date.daysBetween = function( date1, date2 ) {   //Get 1 day in milliseconds   
			var one_day=1000*60*60*24;    // Convert both dates to milliseconds
			var date1_ms = date1.getTime();   
			var date2_ms = date2.getTime();    // Calculate the difference in milliseconds  
			 var difference_ms = date2_ms - date1_ms;        // Convert back to days and return   
			return Math.round(difference_ms/one_day); 
		}
		var y2k  = new Date(2019, 2, 29); 
		// console.log('y2k: ', y2k);
		var Jan1st2010 = new Date(y2k.getFullYear(), y2k.getMonth(), y2k.getDate());
		var today= new Date(); //displays 726
		// console.log('date: ',Date.daysBetween(Jan1st2010,today));				
		$('#load_more_patient').bind('click',function(){
		 	var _this = $(this);
		 	var order_list = $('#order_list');
			var draftID = _this.attr("data-draft_id");
			var hospice_ID = $('#load_more_patient').attr("data-hospice-id");
		 	var currentCount = parseInt(_this.attr("data-current-count"));
		 	// console.log(currentCount);
		 	// console.log(limit);
		 	var daily_rate = parseFloat(_this.attr("data-daily-rate")).toFixed(2);
		 	var order_list_tbody = $('#order_list_tbody');
		 	var cap_h = 0;
	 		var noncap_h = 0;
	 		var purcahse_h = 0;
	 		var counterrr = 0;
			var cusdaysinfobox = parseInt($('#customerdays').html());
			var track_census = $('#hospice_track_census').val();

			if(isNaN(cusdaysinfobox)) {
				cusdaysinfobox = 0;
			}

	 		$.each($(".hide_buttons").find(".hide_items"), function(){
	 			if($(this).is(':checked')) {
	 				if(counterrr == 0) {
	 					cap_h = 1;
	 				}
	 				if(counterrr == 1) {
	 					noncap_h = 1;
	 				}
	 				if(counterrr == 2) {
	 					purcahse_h = 1;
	 				}
	 			}
	 			counterrr++;
	 		});
	 		// console.log('cap_h: ', cap_h);
		 	// $("#load_more_content").remove();
		 	order_list.append('<div id="loading_more" class="hidden-print" style="text-align:center;font-size:18px;">Retrieving Data... <i class="fa fa-spinner fa-spin fa-2x"></i></div>');
		 	
		 	$.get(base_url+'billing_statement/statement_draft_load_more/'+draftID+"/"+currentCount+"/"+limit, function(response){
		 		
	  			var jsonparse = JSON.parse(response);
	  			console.log('THIS IS THE RESPONSE', jsonparse);
	  			
	  			if(jsonparse.customers_orders.totalCount > 0) {
	  				// console.log('THIS IS THE RESPONSE', jsonparse.customers_orders.cus_orders[0].customer_orders.length);
		  			// console.log('CUS_orders: ', jsonparse.customers_orders.cus_orders);
		  			// var cus_orders = jsonparse.customers_orders.cus_orders;
		  			// console.log('totalCount: ', jsonparse.customers_orders.totalCount);
		  			currentCount += jsonparse.customers_orders.totalCount;
			 		// console.log('Current totalCount: ', currentCount);
			 		
					items_for_get_full_descrip = [];
					items_for_get_item_group = [];
					var item_counter_for_fullDescription = 0;
					var cusdayslooptotal = 0;
			 		for(var i = 0; i < jsonparse.customers_orders.cus_orders.length; i++) {
			 			// console.log('cus_orders: ', jsonparse.customers_orders.cus_orders[i]);
			 			var final_html = "";
			 			var grand_total = 0;
			 			var amount_due = daily_rate * jsonparse.customers_orders.cus_orders[i].customer_info.patient_days;
			 			// var customer_info = '<tr style="background-color: #efefef"><td class="compress-center with-border td-info-box">&nbsp;</td><td class="with-border td-info-box"><div style="font-size: 15px;" onclick="window.open(\''+base_url+'order/patient_profile/'+jsonparse.customers_orders.cus_orders[i].customer_info.medical_record_id+'/'+hosID+'\', \'_blank\')"><strong>'+jsonparse.customers_orders.cus_orders[i].customer_info.p_lname+', '+jsonparse.customers_orders.cus_orders[i].customer_info.p_fname+' - MR # '+jsonparse.customers_orders.cus_orders[i].customer_info.medical_record_id+'</strong></div></td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">'+jsonparse.customers_orders.cus_orders[i].customer_info.patient_days+'</td><td class="compress-center with-border td-info-box">'+daily_rate+'</td><td class="compress-center with-border td-info-box">'+parseFloat(amount_due).toFixed(2)+'</td></tr>';
						 var temporary_service_date_to_value = $('#temporary_service_date_to').val().split('/');
						 var temporary_service_date_from_value = temporary_service_date_to_value[2] + '-' + temporary_service_date_to_value[0] + '-01';
						 var customer_info = '<tr style="background-color: #efefef"><td class="compress-center with-border td-info-box">&nbsp;</td><td class="with-border td-info-box"><div style="font-size: 15px; cursor: pointer" class="data_tooltip customer_name_container" title="View Profile" onclick="window.open(\''+base_url+'order/patient_profile/'+jsonparse.customers_orders.cus_orders[i].customer_info.medical_record_id+'/'+hosID+'\', \'_blank\')"><strong>'+jsonparse.customers_orders.cus_orders[i].customer_info.p_lname+', '+jsonparse.customers_orders.cus_orders[i].customer_info.p_fname+' - MR # '+jsonparse.customers_orders.cus_orders[i].customer_info.medical_record_id+'</strong></div></td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box"><a href="javascript:;" data-pk="'+jsonparse.customers_orders.cus_orders[i].customer_info.patientID+'" data-url="'+base_url+'billing_statement/change_cusdays_draft_statement/'+jsonparse.customers_orders.cus_orders[i].customer_info.patientID+'/'+temporary_service_date_from_value+'" data-value="'+jsonparse.customers_orders.cus_orders[i].customer_info.patient_days+'" data-type="number" class="editable editable-click editable-cusdays"><span class="under-line">'+jsonparse.customers_orders.cus_orders[i].customer_info.patient_days+'</span></a> <span class="visible-print-block">'+jsonparse.customers_orders.cus_orders[i].customer_info.patient_days+'</span></td><td class="compress-center with-border td-info-box">'+daily_rate+'</td><td class="compress-center with-border td-info-box">'+parseFloat(amount_due).toFixed(2)+'</td></tr>';
			 			final_html += customer_info;
			 			grand_total += amount_due;
			 			var customer_order = jsonparse.customers_orders.cus_orders[i].customer_orders;
			 			var prev_workorder = "";
			 			var prev_addressID = 0;
			 			var cus_orders_html = "";
			 			var cusmove_count = 1;
			 			var respite_count = 1;
			 			var prev_cusmove_count = 1;
	  					var prev_respite_count = 1;
	  					var is_add_cusmove = false;
	  					var is_add_respite = false;
	  					var activity_type_disp = "";
	  					var amount_due_grand_total = "";
	  					var is_total_row_displayed = false;
	  					var is_total_row_displayed_counter = 0;
						var is_display_activity_type = false;
						var isuniqueid = "";

						if(jsonparse.customers_orders.cus_orders[i].customer_orders.length > 0) {
	  						// Get the Total Customer Days 
			 				cusdayslooptotal += parseInt(jsonparse.customers_orders.cus_orders[i].customer_info.patient_days);
							 console.log('Get the Total Customer Days ', cusdayslooptotal);
	  					}

			 			for (var j = 0; j < jsonparse.customers_orders.cus_orders[i].customer_orders.length; j++) {
							// console.log('ORDER: ====> ', jsonparse.customers_orders.cus_orders[i].customer_orders[j]);
			 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipment_quantity != null && jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipment_quantity != "") {
								var equipment_val = jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipment_quantity;
							} else {
								var equipment_val = jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipment_value;
							}
							var disp_equip_val = equipment_val;

							// Previous process for Aerosol Mask
			 				// if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].is_package == 1 && jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID == 156) {
			 				// 	if(parseInt(jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipment_quantity) > 1) {
			 				// 		disp_equip_val = parseInt(jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipment_quantity) - 1;
			 				// 		equipment_val = parseInt(jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipment_quantity) - 1;
			 				// 	} else {
			 				// 		continue;
			 				// 	}
			 				// } else

			 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].equip_is_package == 0 && jsonparse.customers_orders.cus_orders[i].customer_orders[j].is_package == 1) {
			 					continue;
			 				}
			 				
			 				// if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID == 176 || jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID == 174) {
							// 	disp_equip_val = 1;
							// }

							// If Item is a Capped then Quantity is always 1
							if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].categoryID == 1) {
								disp_equip_val = 1;
							}


			 				var activity_type = "";
			 				var customer_info = "";
			 				// if(prev_addressID != jsonparse.customers_orders.cus_orders[i].customer_orders[j].addressID) {
			 				// 	grand_total = 0;
			 				// 	is_add_cusmove = false;
			 				// 	is_add_respite = false;
			 				// 	is_display_activity_type = false;

			 				// 	var activity_type_new = "";
			 				// 	var prev_cusmove_count_new = cusmove_count;
			 				// 	var prev_respite_count_new = respite_count;
			 				// 	//For Displaying Respite or Cus Move - 12/27/2019 => START
			 				// 	if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].original_activity_typeid == 5) {
				 			// 		activity_type_new = "Respite";
				 			// 	}
				 			// 	else if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].original_activity_typeid == 4) {
				 			// 		activity_type_new = "CUS Move";
				 			// 	} 

			 				// 	var display_activity_type_new = activity_type_new;
			 				// 	if(activity_type_new == "CUS Move" && is_display_activity_type == false) {
				  			// 		if(prev_cusmove_count_new > 1) {
				  			// 			display_activity_type_new = activity_type_new+" "+prev_cusmove_count_new;
				  			// 		}
				  			// 	} else {
				  			// 		if(activity_type_new == "Respite" && is_display_activity_type == false) {
					  		// 			if(prev_respite_count_new > 1) {
					  		// 				display_activity_type_new = activity_type_new+" "+prev_respite_count_new;
					  		// 			}
					  		// 		} else {
					  		// 			display_activity_type_new = ""
					  		// 		}
				  			// 	}
				  			// 	//For Displaying Respite or Cus Move - 12/27/2019 => END

			 				// 	prev_addressID = jsonparse.customers_orders.cus_orders[i].customer_orders[j].addressID;

			 				// 	//Changes for Respite Orders 12/27/2019 -> Do not include Customer days and the total for the grand total => START
			 				// 	if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].original_activity_typeid == 5 || jsonparse.customers_orders.cus_orders[i].customer_orders[j].original_activity_typeid == 4) {
			 				// 		customer_info = '<tr style="background-color: #efefef"><td class="compress-center with-border"><strong>'+display_activity_type_new+'</strong></td><td class="with-border "><div class="customer_name_container" style="margin-bottom: -20px; font-size: 15px;"><strong><div style="cursor: pointer" class="data_tooltip" title="View Profile" onclick="window.open(\''+base_url+'order/patient_profile/'+jsonparse.customers_orders.cus_orders[i].customer_info.medical_record_id+'/'+hosID+'\', \'_blank\')">'+jsonparse.customers_orders.cus_orders[i].customer_info.p_lname+', '+jsonparse.customers_orders.cus_orders[i].customer_info.p_fname+' - MR # '+jsonparse.customers_orders.cus_orders[i].customer_info.medical_record_id+'</div></strong></div></td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border"></td><td class="compress-center with-border"></td></tr>';
			 				// 	} else {
			 				// 		customer_info = '<tr style="background-color: #efefef"><td class="compress-center with-border"><strong>'+display_activity_type_new+'</strong></td><td class="with-border"><div class="customer_name_container" style="margin-bottom: -20px; font-size: 15px;"><strong><div style="cursor: pointer" class="data_tooltip" title="View Profile" onclick="window.open(\''+base_url+'order/patient_profile/'+jsonparse.customers_orders.cus_orders[i].customer_info.medical_record_id+'/'+hosID+'\', \'_blank\')">'+jsonparse.customers_orders.cus_orders[i].customer_info.p_lname+', '+jsonparse.customers_orders.cus_orders[i].customer_info.p_fname+' - MR # '+jsonparse.customers_orders.cus_orders[i].customer_info.medical_record_id+'</div></strong></div></td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">&nbsp;</td><td class="compress-center with-border">'+jsonparse.customers_orders.cus_orders[i].customer_info.patient_days+'</td><td class="compress-center with-border">'+parseFloat(daily_rate).toFixed(2)+'</td><td class="compress-center with-border">'+parseFloat(amount_due).toFixed(2)+'</td></tr>';
			 				// 		grand_total += parseFloat(amount_due);
			 				// 	}
			 				// 	//Changes for Respite Orders 12/27/2019 -> Do not include Customer days and the total for the grand total => END
			 					
			 				// 	cus_orders_html += customer_info;
			 					
			 				// }
			 				// if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].original_activity_typeid == 5 && is_add_respite == false) {
			 				// 	activity_type = "Respite";
			 				// 	activity_type_disp = "Respite";
			 				// 	prev_respite_count = respite_count;
			 				// 	respite_count++;
			 				// 	is_add_respite = true;
			 				// }
			 				// else if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].original_activity_typeid == 4 && is_add_cusmove == false) {
			 				// 	activity_type = "CUS Move";
			 				// 	activity_type_disp = "CUS Move";
			 				// 	prev_cusmove_count = cusmove_count;
			 				// 	cusmove_count++;
			 				// 	is_add_cusmove = true;
			 				// } 
			 				// else {
			 				// 	activity_type = "";
			 				// }

			 				// var now = new Date();
							var temp_now = $('#temporary_service_date_to').val().split('/');
							if(temp_now[0] > 0) {
								temp_now[0] = temp_now[0] - 1;
							}
							var now = new Date(temp_now[2], temp_now[0], temp_now[1]);
							var now_sum_pick_date = new Date(temp_now[2], temp_now[0], temp_now[1]);
			 				var isSummaryPickupDate = false;
							var sum_pick_date_split = jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date.split("-");
							if(sum_pick_date_split[1] > 0) {
								sum_pick_date_split[1] = sum_pick_date_split[1] - 1;
							}
							var sum_pick_date = new Date(sum_pick_date_split[0], sum_pick_date_split[1], sum_pick_date_split[2]);
							
							var now_other = new Date();
                            var now_temp = [];
                            var now_temp_other = [];
			 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date != "0000-00-00" && (sum_pick_date <= now_sum_pick_date)) {
								if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].pickup_discharge_date != "0000-00-00" && jsonparse.customers_orders.cus_orders[i].customer_orders[j].pickup_discharge_date != null) {
                                    now_temp = jsonparse.customers_orders.cus_orders[i].customer_orders[j].pickup_discharge_date.split("-");
                                    now_temp_other = jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date.split("-");
                                    var now_type = 'discharge';
                                } else {
                                    now_temp = jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date.split("-");
                                    now_temp_other = jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date.split("-");
                                    var now_type = 'summary';
                                }

			 					var temp_now_temp = 0;
                                if(now_temp[1] > 0) {
                                    temp_now_temp = now_temp[1] - 1;
                                }
                                now = new Date(now_temp[0], temp_now_temp, now_temp[2]);

                                var temp_now_temp_other = 0;
                                if(now_temp_other[1] > 0) {
                                    temp_now_temp_other = now_temp_other[1] - 1;
                                }
                                now_other = new Date(now_temp_other[0], temp_now_temp_other, now_temp_other[2]);
								
								isSummaryPickupDate = true;
							}
			 				var total = 0;
			 				var cap = "";
			 				var tr_class = "";
			 				var tr_style_h = "";
			 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].categoryID == 1) {
			 					if(cap_h == 1) {
			 						tr_style_h = ' style="display:none;"';
			 					}
			 					cap = 'X';
			 					if(total == 0) {
			 						total = "";
			 					}
			 					else {
			 						total = parseFloat(total).toFixed(2);
			 					}
			 					tr_class = "cap-item";
			 				}

			 				var purchase = 0;
			 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].categoryID == 3) {
			 					if(purcahse_h == 1) {
			 						tr_style_h = ' style="display:none;"';
			 					}
			 					purchase = jsonparse.customers_orders.cus_orders[i].customer_orders[j].purchase_price;
			 					total = equipment_val * jsonparse.customers_orders.cus_orders[i].customer_orders[j].purchase_price;
			 					grand_total += parseFloat(total);

			 					if(total == 0) {
			 						total = "";
			 					}
			 					else {
			 						total = parseFloat(total).toFixed(2);
			 					}
			 					tr_class = "purchase-item";
			 				}

			 				if(purchase == 0) {
		 						purchase = "";
		 					}
		 					else {
		 						purchase = parseFloat(purchase).toFixed(2);
		 					}

			 				var noncapped = 0;
			 				
			 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].categoryID == 2) {
			 					if(noncap_h == 1) {
			 						tr_style_h = ' style="display:none;"';
			 					}

								var tempo_service_date_to = $('#temporary_service_date_to').val().split('/');
									
								var pickdate_temp = jsonparse.customers_orders.cus_orders[i].customer_orders[j].actual_order_date.split("-");
								// if(pickdate_temp[1] > 0) {
								// 	pickdate_temp[1] = pickdate_temp[1] - 1;
								// }
								var new_pickupdate = new Date(pickdate_temp[0], (pickdate_temp[1] - 1), pickdate_temp[2]);
								// if(tempo_service_date_to[0] > 0) {
								// 	tempo_service_date_to[0] = tempo_service_date_to[0] - 1;
								// }
								var new_service_date_to = new Date(tempo_service_date_to[2], tempo_service_date_to[0], tempo_service_date_to[1]);

								//  ((tempo_service_date_to[2] > pickdate_temp[0]) && isSummaryPickupDate == false) || 
								if((((tempo_service_date_to[2] == pickdate_temp[0]) && (tempo_service_date_to[0] < pickdate_temp[1])) && isSummaryPickupDate == false) ) {
									equipment_val = Date.daysBetween(new_pickupdate,new Date(new_pickupdate.getFullYear(), new_pickupdate.getMonth() + 1, 0)) + 1;
									//new Date(new_pickupdate.getFullYear(), new_pickupdate.getMonth() + 1, 0)
									// console.log('ifstatement', equipment_val, ' - ', new_pickupdate, ' - ', new Date(new_pickupdate.getFullYear(), new_pickupdate.getMonth() + 1, 0), ' - ', tempo_service_date_to, ' - ', pickdate_temp);
								} else {
									if(((tempo_service_date_to[2] == pickdate_temp[0]) && (tempo_service_date_to[0] == pickdate_temp[1]))) {
										equipment_val = Date.daysBetween(new_pickupdate,now);
										// console.log('elseifstatement', equipment_val, ' - ', new_pickupdate, ' - ', now);
									} else if(((now_temp[1] < tempo_service_date_to[0]) && (now_temp[0] == tempo_service_date_to[2])) || (now_temp[0] < tempo_service_date_to[2])) {
                                        var temp_temponewdate = $('#temporary_service_date_to').val().split('/');
										if(temp_temponewdate[0] > 0) {
											temp_temponewdate[0] = temp_temponewdate[0] - 1;
										}
										var temponewdate = new Date(temp_temponewdate[2], temp_temponewdate[0], 1);
										
										equipment_val = Date.daysBetween(temponewdate, now_other);
                                        if (jsonparse.customers_orders.cus_orders[i].customer_info.p_lname == 'KAPTCHAM') {
                                            console.log('else else if qty', equipment_val);
                                            console.log('temponewdate', temponewdate);
                                            console.log('temp_temponewdate', temp_temponewdate);
                                            console.log('now_other', now_other);
                                        }
                                    } else {
										// var temponewdate = new Date();
										var temp_temponewdate = $('#temporary_service_date_to').val().split('/');
										if(temp_temponewdate[0] > 0) {
											temp_temponewdate[0] = temp_temponewdate[0] - 1;
										}
										var temponewdate = new Date(temp_temponewdate[2], temp_temponewdate[0], 1);
										
										equipment_val = Date.daysBetween(temponewdate, now) + 1;
										// if(isSummaryPickupDate == false) {
										// 	equipment_val--;
										// }
										
										// console.log('elseifelsestatement - ', isSummaryPickupDate, ' - ', $('#temporary_service_date_to').val(), '-', equipment_val, ' - ', temponewdate, ' - ', now, ' - ', pickdate_temp);
									}
								}
								
								if (equipment_val == 0) {
									equipment_val = 1;
								}
								
								if (jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID != 49 && jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID != 64 && jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID != 32 && jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID != 29 && jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID != 334 && jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID != 343) {
									if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].daily_rate == 0 || jsonparse.customers_orders.cus_orders[i].customer_orders[j].daily_rate == null) {
										equipment_val = 1;
									}
								}
								// if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].daily_rate == 0 || jsonparse.customers_orders.cus_orders[i].customer_orders[j].daily_rate == null) {
								// 	equipment_val = 1;
								// }

								if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID == 176) {
									equipment_val = 1;
								}

								if (jsonparse.customers_orders.cus_orders[i].customer_info.p_lname == 'APLIN') {
									// console.log('before_equipment_val', equipment_val);
								}

								// noncapped = jsonparse.customers_orders.cus_orders[i].customer_orders[j].purchase_price;
								//New Calculation 11/15/19 =======> START
								var isMonthlyRate = false;
								if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].daily_rate == 0 || jsonparse.customers_orders.cus_orders[i].customer_orders[j].daily_rate == null) {
									total = jsonparse.customers_orders.cus_orders[i].customer_orders[j].monthly_rate;
									noncapped = jsonparse.customers_orders.cus_orders[i].customer_orders[j].monthly_rate;
									isMonthlyRate = true;
									if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].activity_reference == 3 && jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID_reference != 0) {
										noncapped = 0;
										total = 0;
									}
								} else {
									var temptotaldailyrate = equipment_val * jsonparse.customers_orders.cus_orders[i].customer_orders[j].daily_rate;
									noncapped = jsonparse.customers_orders.cus_orders[i].customer_orders[j].daily_rate;
									if(temptotaldailyrate > jsonparse.customers_orders.cus_orders[i].customer_orders[j].monthly_rate) {
										if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].monthly_rate == 0 || jsonparse.customers_orders.cus_orders[i].customer_orders[j].monthly_rate == null) {
											total = temptotaldailyrate;
										} else {
											total = jsonparse.customers_orders.cus_orders[i].customer_orders[j].monthly_rate;
											noncapped = jsonparse.customers_orders.cus_orders[i].customer_orders[j].monthly_rate;
											isMonthlyRate = true;

											if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].activity_reference == 3 && jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID_reference != 0) {
												noncapped = 0;
												total = 0;
											}
										}
										
									} else {
										total = temptotaldailyrate;
									}
								}
								disp_equip_val = equipment_val;

								if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID == 176) {
									disp_equip_val = 1;
								}

								if (isMonthlyRate) {
									disp_equip_val = 1;
								}

								//New Calculation 11/15/19 =======> END

								//total = equipment_val * jsonparse.customers_orders.cus_orders[i].customer_orders[j].purchase_price;
								if (jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID == 30) {
									// console.log('equipment_val', equipment_val);
									// console.log('disp_equip_val', disp_equip_val);
									// console.log('noncapped', noncapped);
								}
								grand_total += parseFloat(total);

								if(total == 0) {
									total = "";
								}
								else {
									total = parseFloat(total).toFixed(2);
								}

								tr_class = "noncap-item";
			 				}

			 				if(noncapped == 0) {
		 						noncapped = "";
		 					} else {
		 						noncapped = parseFloat(noncapped).toFixed(2);
		 					}

		 					var display_activity_type = activity_type_disp;
		 					if(activity_type_disp == "CUS Move" && is_display_activity_type == false) {
			  					if(prev_cusmove_count > 1) {
			  						display_activity_type = activity_type_disp+" "+prev_cusmove_count;
			  					}
			  				} else {
			  					if(activity_type_disp == "Respite" && is_display_activity_type == false) {
				  					if(prev_respite_count > 1) {
				  						display_activity_type = activity_type_disp+" "+prev_respite_count;
				  					}
				  				} else {
				  					display_activity_type = ""
				  				}
			  				}
			 				
			 				cus_orders_html += '<tr class="itemgroup_tr '+tr_class+'"'+tr_style_h+' data-unique-id="'+jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID+'" data-equipment-id="'+jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID+'" data-patient-id="'+jsonparse.customers_orders.cus_orders[i].customer_info.patientID+'" data-address-id="'+jsonparse.customers_orders.cus_orders[i].customer_info.addressID+'">';
			 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID == prev_workorder) {
			 					// cus_orders_html += '<td class="compress-center with-border td-info-box"><strong>'+display_activity_type+'</strong></td>';
			 					cus_orders_html += '<td class="compress-center with-border td-info-box"><strong>&nbsp;</strong></td>';
			 					is_display_activity_type = true;
			 				} else {
			 					cus_orders_html += '<td class="compress-center with-border td-info-box">'+jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID.substr(4,6)+'</td>';
			 				}
			 				prev_workorder = jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID;
							 
			 				var summary_pickup_date = "";
							if (jsonparse.customers_orders.cus_orders[i].customer_info.p_lname == 'CHOE') {
								// console.log('sumpickdate ', sum_pick_date, ' -- temp_now ', now_sum_pick_date);
							}
			 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date != "0000-00-00" && (sum_pick_date <= now_sum_pick_date)) {
								if (jsonparse.customers_orders.cus_orders[i].customer_info.p_lname == 'CHOE') {
									// console.log('CHOE');
								}
			 					// var sumdate = new Date(jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date);
								// var sumyear = "";
								// var summonth = "";
								// sumyear = sumdate.getFullYear();
								// if((parseInt(sumdate.getMonth())+1) < 10) {
								// 	summonth = "0"+(parseInt(sumdate.getMonth())+1);
								// } else {
								// 	summonth = (parseInt(sumdate.getMonth())+1);
								// }
								// var sumday = "";
								// if((sumdate.getDate()) < 10) {
								// 	sumday = "0"+(sumdate.getDate());
								// } else {
								// 	sumday = sumdate.getDate();
								// }
								// var new_date = summonth+"/"+sumday+"/"+sumyear;
			 					// summary_pickup_date = new_date;

								// Version 2 Format Date
								var tempdate = jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date.split('-');
                                var new_date = tempdate[1]+"/"+tempdate[2]+"/"+tempdate[0];
                                summary_pickup_date = new_date;
			 				} else {
			 					summary_pickup_date = '&nbsp;';
			 				}

			 				var pickupdate = "";
			 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].actual_order_date != "0000-00-00") {
			 					// var pickdate = new Date(jsonparse.customers_orders.cus_orders[i].customer_orders[j].actual_order_date);
								// var pickyear = "";
								// var pickmonth = "";
								// pickyear = pickdate.getFullYear();
								// if((parseInt(pickdate.getMonth())+1) < 10) {
								// 	pickmonth = "0"+(parseInt(pickdate.getMonth())+1);
								// } else {
								// 	pickmonth = (parseInt(pickdate.getMonth())+1);
								// }
								// var sumday = "";
								// if((pickdate.getDate()) < 10) {
								// 	pickday = "0"+(pickdate.getDate());
								// } else {
								// 	pickday = pickdate.getDate();
								// }
								// var picknew_date = pickmonth+"/"+pickday+"/"+pickyear;
			 					// pickupdate = picknew_date;
								
								// Version 2 Format Date
								var tempdate = jsonparse.customers_orders.cus_orders[i].customer_orders[j].actual_order_date.split('-');
                                pickupdate = tempdate[1]+"/"+tempdate[2]+"/"+tempdate[0];
			 				} else {
			 					pickupdate = '&nbsp;';
			 				}

							if (jsonparse.customers_orders.cus_orders[i].customer_orders[j].patientID == 23815) {
								// console.log('keydesc', jsonparse.customers_orders.cus_orders[i].customer_orders[j].key_desc);
								// console.log('uniqueID', jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID);
								// console.log('categoryID', jsonparse.customers_orders.cus_orders[i].customer_orders[j].categoryID);
								// console.log('equipmentID', jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID);
								// console.log('total', total);
								// console.log('grand_total', grand_total);
								// console.log('===========================');
							}
			 				cus_orders_html += '<td class="compress-center with-border td-info-box item_description_td item_counter_'+item_counter+'" data-unique-id="'+jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID+'" data-equipment-id="'+jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID+'" data-patient-id="'+jsonparse.customers_orders.cus_orders[i].customer_info.patientID+'">'+jsonparse.customers_orders.cus_orders[i].customer_orders[j].key_desc+'</td><td class="with-border td-info-box">'+pickupdate+'</td><td class="compress-center with-border td-info-box">'+summary_pickup_date+'</td><td class="compress-center with-border td-info-box"><span class="disp_equip_val">'+disp_equip_val+'</span></td><td class="compress-center with-border td-info-box">'+cap+'</td><td class="compress-center with-border td-info-box">'+purchase+'</td><td class="compress-center with-border td-info-box"><span class="noncapped_td">'+noncapped+'</span></td><td class="compress-center with-border td-info-box"><span class="total_td">'+total+'</span></td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box"></td><td class="compress-center with-border td-info-box"><span class="amount_due_td">'+total+'</span></td>';
			 				cus_orders_html += '</tr>';
			 				// items_for_get_full_descrip[item_counter_for_fullDescription] = jsonparse.customers_orders.cus_orders[i].customer_orders[j];
							items_for_get_full_descrip[item_counter_for_fullDescription] = {
								equipmentID: jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID,
								key_desc: jsonparse.customers_orders.cus_orders[i].customer_orders[j].key_desc,
								patientID: jsonparse.customers_orders.cus_orders[i].customer_orders[j].patientID,
								uniqueID: jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID,
								hospiceID: hospice_ID
							};
							items_for_get_item_group[item_counter_for_fullDescription] = {
								equipmentID: jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipmentID,
								assigned_equipmentID: jsonparse.customers_orders.cus_orders[i].customer_orders[j].assigned_equipmentID,
								key_desc: "",
								patientID: jsonparse.customers_orders.cus_orders[i].customer_orders[j].patientID,
								uniqueID: jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID,
								equipmentVal: equipment_val,
								addressID: jsonparse.customers_orders.cus_orders[i].customer_orders[j].addressID,
								hospiceID: hospice_ID
							};
							item_counter_for_fullDescription++;
							item_counter = item_counter + 1;
							isuniqueid = jsonparse.customers_orders.cus_orders[i].customer_orders[j].addressID;

			 				is_total_row_displayed_counter++;
			 				if((jsonparse.customers_orders.cus_orders[i].customer_orders.length - 1) == j) {
	 							// console.log("nakasulod kos last row - 1:", j);
	 							is_total_row_displayed = true;
				 				if(activity_type_disp == "CUS Move") {
				  					if(prev_cusmove_count > 1) {
				  						activity_type_disp = activity_type_disp+" "+prev_cusmove_count;
				  					}
				  				}
				  				if(activity_type_disp == "Respite") {
				  					if(prev_respite_count > 1) {
				  						activity_type_disp = activity_type_disp+" "+prev_respite_count;
				  					}
				  				}
				 				amount_due_grand_total = '<tr><td class="compress-center with-border-left with-border-bottom td-info-box">&nbsp;</td><td class="with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box"><strong>'+''+'</strong></td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">TOTAL:</td><td class="compress-center with-border-right with-border-bottom td-info-box"><span class="grand_total_td_'+jsonparse.customers_orders.cus_orders[i].customer_orders[j].addressID+'">'+parseFloat(grand_total).toFixed(2)+'</span></td></tr>';
								 cus_orders_html += amount_due_grand_total;
								 
								if (jsonparse.customers_orders.cus_orders[i].customer_orders[j].patientID == 23815) {
									// console.log('grandtotal', grand_total);
									// console.log('grandtotal', parseFloat(grand_total).toFixed(2));
								}
			 				}
	 						// else if(jsonparse.customers_orders.cus_orders[i].customer_orders[j+1].addressID != jsonparse.customers_orders.cus_orders[i].customer_orders[j].addressID) {
	 						// 	console.log("nakasulod kos last row + 1:", j);
	 						// 	is_total_row_displayed = true;
				 			// 	if(activity_type_disp == "CUS Move") {
				  			// 		if(prev_cusmove_count > 1) {
				  			// 			activity_type_disp = activity_type_disp+" "+prev_cusmove_count;
				  			// 		}
				  			// 	}
				  			// 	if(activity_type_disp == "Respite") {
				  			// 		if(prev_respite_count > 1) {
				  			// 			activity_type_disp = activity_type_disp+" "+prev_respite_count;
				  			// 		}
				  			// 	}
				 			// 	amount_due_grand_total = '<tr><td class="compress-center with-border-left with-border-bottom td-info-box">&nbsp;</td><td class="with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box"><strong>'+''+'</strong></td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">TOTAL:</td><td class="compress-center with-border-right with-border-bottom"><span class="grand_total_td_'+jsonparse.customers_orders.cus_orders[i].customer_orders[j].addressID+'">'+parseFloat(grand_total).toFixed(2)+'</span></td></tr>';
				 			// 	cus_orders_html += amount_due_grand_total;
		 					// }
			 			}
			 			final_html += cus_orders_html;
			 			// final_html += cus_orders_html
			 			var amount_due_grand_total = "";
			 			if(is_total_row_displayed == false && is_total_row_displayed_counter > 0) {
			 				amount_due_grand_total = '<tr><td class="compress-center with-border-left with-border-bottom td-info-box">&nbsp;</td><td class=" with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center  with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center  with-border-bottom td-info-box">&nbsp;</td><td class="compress-center with-border-bottom td-info-box">&nbsp;</td><td class="compress-center  with-border-bottom td-info-box">&nbsp;</td><td class="compress-center  with-border-bottom td-info-box">TOTAL:</td><td class="compress-center with-border-right with-border-bottom td-info-box"><span class="grand_total_td_'+isuniqueid+'">'+parseFloat(grand_total).toFixed(2)+'</span></td></tr>';
			 				final_html += amount_due_grand_total;
			 			}
			 			order_list_tbody.append(final_html);
			 		}
	  			}
		 		//Customer Days Calculation Info Box
				if(track_census == 0) {
					var finalcusdaysval = cusdaysinfobox + parseInt(cusdayslooptotal);
					var finaltotaltotaltotal = finalcusdaysval * dailyrat;
					var prev_cusdaysinfobox = parseInt($('#customerdays').html());
					if(isNaN(prev_cusdaysinfobox)) {
						prev_cusdaysinfobox = 0;
						console.log('prev_cusdaysinfobox', finalcusdaysval);
					}
					var prev_totaltotal = prev_cusdaysinfobox * dailyrat;
					console.log('prev_totaltotal', prev_totaltotal);
					$('#customerdays').html(finalcusdaysval);
					console.log('finalcusdaysval', finalcusdaysval);
					console.log('customerdays', $('#customerdays').html());
					console.log('cusdaysinfobox', cusdaysinfobox);
					console.log('cusdayslooptotal', cusdayslooptotal);
					if(finaltotaltotaltotal != 0) {
						$("#totaltotal").html(parseFloat(finaltotaltotaltotal).toFixed(2));
					} else {
						$("#totaltotal").html("");
					}
					var gtotal = (parseFloat($("#totalbalancedue").attr("data-total")) - prev_totaltotal) + (finaltotaltotaltotal);
					gtotal = isNaN(gtotal) ? 0 : gtotal;
					$("#totalbalancedue").attr("data-total", parseFloat(gtotal).toFixed(2));

					if(gtotal != 0) {
						$("#totalbalancedue").html(parseFloat(gtotal).toFixed(2));
						$("#overalltotal").html(parseFloat(gtotal).toFixed(2));
					} else {
						$("#totalbalancedue").html("0.00");
                		$("#overalltotal").html('0.00');
					}
					var subtotal = (parseFloat($("#subtotal").attr("data-total")) - prev_totaltotal) + (finaltotaltotaltotal);
					subtotal = isNaN(subtotal) ? 0 : subtotal;
					$("#subtotal").attr("data-total", parseFloat(subtotal).toFixed(2));

					if(subtotal != 0) {
						$("#subtotal").html(parseFloat(subtotal).toFixed(2));
					} else {
						$("#subtotal").html("");
					}
				}
		 		

		 		// order_list_tbody.append(final_html);
		 		// $("#loading_more").remove();
		 		
		 		$('#load_more_patient').attr("data-current-count", currentCount);

		 		//FOR AUTOLOAD WHEN DOCUMENT IS READY
		 		// var current_Count = parseInt($('#load_more_patient').attr("data-current-count"));
				// var total_CustomerCount = parseInt($('#load_more_patient').attr("data-total-count"));
				
				// For Getting the Full Description of the Item
	        	getFullDescription(items_for_get_full_descrip, items_for_get_item_group);
				 // getItemGroupRates(items_for_get_item_group);

		 		// if(current_Count < total_CustomerCount) {
		 		// 	$('#load_more_patient').click();
		 		// } else {
		 		// 	$('.loaded-icon').show();
		 		// 	$('.loader-icon-container').hide();
		 		// }

		 		//FOR AUTOLOAD IF YOU REACH THE BOTTOM MOST OF THE PAGE
		 		onscroll = false;

		 		// order_list.append('<div class="text-center" id="load_more_content" style="display: none"><button class="btn btn-info" style="" id="load_more_patient" data-hospice-id="'+hospiceID+'" data-current-count="'+currentCount+'" data-daily-rate="'+daily_rate+'" style="text-align:center;">Load More</button></div>');
		 		
		 		$('.editable-click.editable-cusdays').editable({
					emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
					validate: function(value) {
						if($.trim(value) == '') {
							return 'This field is required';
						}
					},
					success:function(response,newValue){
						console.log('response', response);
						var parse_data = JSON.parse(response);
						console.log('parse_data', parse_data);
						
						me_message_v2({error:parse_data.error, message: parse_data.message});
						// if(response.error==1) return response.message;
					}
				});
		 	});

		 	
		 });

		function getFullDescription(cusOrders, cusEquipIDs) {
			// console.log('getFullDescription',cusOrders);

			$.post(base_url+'billing_statement/get_item_full_description/', { data: cusOrders }, function (response) {
				// console.log('cusordersreposnse', response);
				if (response != null) {
					
					for (var i = 0; i < JSON.parse(response).length; i++) {
						var temp_parse = JSON.parse(response)[i];
						if (temp_parse.equipmentID == 30) {
							console.log('cusOrders', cusOrders);
							console.log('temp_parse.cusOrders', temp_parse);
						}

						display_item_counter++;
						$(".item_description_td").each( function (index) {
							if($(this).attr('data-patient-id') == temp_parse.patientID && $(this).attr('data-unique-id') == temp_parse.uniqueID && $(this).attr('data-equipment-id') == temp_parse.equipmentID) {
								$(this).html(temp_parse.item_full_description);
							}
						});
						cusEquipIDs[i].key_desc = temp_parse.item_full_description;
					}

					getItemGroupRates(cusEquipIDs);
				}
			});
		 }

		 function getItemGroupRates(cusEquipmentIDs) {
			// console.log('getItemGroupRates',cusEquipmentIDs);

			$.post(base_url+'billing_statement/get_item_group_rates/', { data: cusEquipmentIDs }, function (response) {
				// console.log('cusequipidsreposnse', response);
				if (response != null) {
					// console.log('JSON.parse(response)', JSON.parse(response));
					for (var i = 0; i < JSON.parse(response).length; i++) {
						var temp_parse = JSON.parse(response)[i];
						if (temp_parse.equipmentID == 30) {
							console.log('cusEquipmentIDs', cusEquipmentIDs);
							console.log('temp_parse.cusEquipmentIDs', temp_parse);
						}

						var display_quantity = 0;
						var total = 0;
						var noncapped = 0;

						// console.log('temp_parse.sub_equip_details.length', temp_parse.sub_equip_details.length, temp_parse);
                        if (temp_parse.sub_equip_details.length != 0) {
							if(temp_parse.sub_equip_details.daily_rate == 0 || temp_parse.sub_equip_details.daily_rate == null) {
								total = temp_parse.sub_equip_details.monthly_rate;
								noncapped = temp_parse.sub_equip_details.monthly_rate;
								display_quantity = 1;
							} else {
								var temptotaldailyrate = temp_parse.equipmentVal * temp_parse.sub_equip_details.daily_rate;
								noncapped = temp_parse.sub_equip_details.daily_rate;
								if(temptotaldailyrate > temp_parse.sub_equip_details.monthly_rate) {
									if(temp_parse.sub_equip_details.monthly_rate == 0 || temp_parse.sub_equip_details.monthly_rate == null) {
										total = temptotaldailyrate;
										display_quantity = temp_parse.equipmentVal;
									} else {
										total = temp_parse.sub_equip_details.monthly_rate;
										noncapped = temp_parse.sub_equip_details.monthly_rate;
										display_quantity = 1;
									}
									
								} else {
									total = temptotaldailyrate;
									display_quantity = temp_parse.equipmentVal;
								}
							}
						}

						$(".itemgroup_tr").each( function (index) {
							if($(this).attr('data-patient-id') == temp_parse.patientID && $(this).attr('data-unique-id') == temp_parse.uniqueID && $(this).attr('data-equipment-id') == temp_parse.equipmentID) {
								// $(this).html(temp_parse.item_full_description);

								if(isNaN(parseFloat($(this).find('.noncapped_td').html()))) {
									var findnoncapped = 0;
								} else {
									var findnoncapped = parseFloat($(this).find('.noncapped_td').html());
								}

								if(isNaN(parseFloat($(this).find('.total_td').html()))) {
									var findtotal = 0;
								} else {
									var findtotal = parseFloat($(this).find('.total_td').html());
								}

								if(isNaN(parseFloat($(this).find('.amount_due_td').html()))) {
									var findamountdue = 0;
								} else {
									var findamountdue = parseFloat($(this).find('.amount_due_td').html());
								}

								if(isNaN(parseFloat($('.grand_total_td_'+temp_parse.addressID).html()))) {
									var findgrandtotal = 0;
								} else {
									var findgrandtotal = parseFloat($('.grand_total_td_'+temp_parse.addressID).html());
								}

								var finalgrandtotal = (findgrandtotal - findtotal) + parseFloat(total);
								
								$(this).find('.disp_equip_val').html(display_quantity);
								$(this).find('.noncapped_td').html(parseFloat(noncapped).toFixed(2));
								$(this).find('.total_td').html(parseFloat(total).toFixed(2));
								$(this).find('.amount_due_td').html(parseFloat(total).toFixed(2));
								// console.log('$(this).find(".noncapped_td").html(', $(this).find('.noncapped_td').html());
								// console.log('$(this).find(".total_td").html()', $(this).find('.total_td').html());
								// console.log('$(this).find(".amount_due_td").html()', $(this).find('.amount_due_td').html());
								// console.log('$(".grandtotal_td_"'+temp_parse.addressID+').html()', $('.grand_total_td_'+temp_parse.addressID).html());
								// console.log('findnoncapped', findnoncapped);
								// console.log('findtotal', findtotal);
								// console.log('findamountdue', findamountdue);
								// console.log('findgrandtotal', findgrandtotal);
								// console.log('finalgrandtotal', finalgrandtotal);
								$('.grand_total_td_'+temp_parse.addressID).html(parseFloat(finalgrandtotal).toFixed(2));
							}
						});
					}
				}

				$("#loading_more").remove();

                var current_Count = parseInt($('#load_more_patient').attr("data-current-count"));
                var total_CustomerCount = parseInt($('#load_more_patient').attr("data-total-count"));

				// console.log('current_Count', current_Count);
				// console.log('total_CustomerCount', total_CustomerCount);

                if(current_Count < total_CustomerCount) {
                    $('#load_more_patient').click();
                } else {
                    $('.loaded-icon').show();
                    $('.loader-icon-container').hide();
                }
			});
		 }

		 $('.send_to_draft').bind('click',function(){
		 // 	setTimeout(function(){
			// 	location.reload();
			// },500);
			var hospice_ID = $('#load_more_patient').attr("data-hospice-id");
			var acct_statement_bill_id = $('#acct_statement_bill_id').val();
			var statement_no = $('#statement_no').val();
			var service_date_from = $('#service_date_from').val();
			var service_date_to = $('#service_date_to').val();
			var is_all_confirmed = $(this).attr("data-is-all-order-confirmed");
			var notes = $('#notes').val();
			// var state_no = $().attr();

			if(is_all_confirmed == 1) {
				jConfirm("Send to draft statement?","Reminder",function(response){
					if(response)
		                {
						$.get(base_url+'billing_statement/get_new_statement_no/'+hospice_ID, function(response){
					  		$.get(base_url+'billing_statement/send_to_draft/'+hospice_ID+"/"+statement_no+"/"+acct_statement_bill_id+"/"+response+"/"+service_date_from+"/"+service_date_to+"/"+notes, function(response){
								var obj = $.parseJSON(response);
								// jAlert(obj['message'],"Reminder");

								if(obj['error'] == 0)
								{
									me_message_v2({error:0,message:obj['message']});
									setTimeout(function(){
										location.reload();
									},2000);
								}
						 	});
					  	});
					}
				});
			} else {
				jAlert("Confirm date related W/O.","Reminder");
			}
			
			// jConfirm("Send to draft?","Reminder",function(response){
   //              if(response)
   //              {
			//   		$.get(base_url+'billing_statement/send_to_draft/'+hospice_ID+"/"+statement_no+"/"+service_date_from+"/"+service_date_to+"/"+notes, function(response){
			// 			var obj = $.parseJSON(response);
			// 			// jAlert(obj['message'],"Reminder");

			// 			if(obj['error'] == 0)
			// 			{
			// 				me_message_v2({error:0,message:obj['message']});
			// 				setTimeout(function(){
			// 					location.reload();
			// 				},2000);
			// 			}
			// 	 	});
			//   	}
			// });
		 });

		$('.hide_items').bind('click',function(){
		 	var _this = $(this);
		 	var val = _this.val();
		 	//<div id="DataTables_Table_0_processing" class="dataTables_processing" style="display: block;">Retrieving data. Please wait...</div>
		 	if(_this.is(':checked')) {
		 		if(val == 1) {
		 			$.each($("#order_list_tbody").find(".cap-item"), function(){
		 				$(this).hide();
		 			});
		 		}
		 		if(val == 2) {
		 			$.each($("#order_list_tbody").find(".noncap-item"), function(){
		 				$(this).hide();
		 			});
		 		}
		 		if(val == 3) {
		 			$.each($("#order_list_tbody").find(".purchase-item"), function(){
		 				$(this).hide();
		 			});
		 		}
		 	} else {
		 		if(val == 1) {
		 			$.each($("#order_list_tbody").find(".cap-item"), function(){
		 				$(this).show();
		 			});
		 		}
		 		if(val == 2) {
		 			$.each($("#order_list_tbody").find(".noncap-item"), function(){
		 				$(this).show();
		 			});
		 		}
		 		if(val == 3) {
		 			$.each($("#order_list_tbody").find(".purchase-item"), function(){
		 				$(this).show();
		 			});
		 		}
		 	}
		});

		//Save Custoemr Days
	    $('.save_customerdays').bind('click',function(){
	        jConfirm("<div style='text-align: center;'>Apply?</div>","Reminder",function(response){
	            if(response)
	            {
	                var customdays = $('#customerdays').val();
	                var draftidcustom = $('#draft_id_hidden').val();
					var accountdailyrate = $('#account_daily_rate').val();
	                // console.log('customdayscustomdayscustomdays: ', customdays, draftidcustom);
	                $.get(base_url+'billing_statement/update_customerdays_statement_bill_draft/'+draftidcustom+'/'+customdays, function(response){
	                    // console.log("resepposnse:", response);
	                    var obj = $.parseJSON(response);
	                    // console.log("closecloseclose:", obj);
	                    // $('.close').click();
	                    
	                    setTimeout(function(){
	                        if(obj['error'] == 0)
	                        {
	                            me_message_v2({error:0,message:obj['message']});
								var noncapped = parseFloat($("#noncaptotal").html());
								var saleitem = parseFloat($("#purchasetotal").html());
								var credit = parseFloat($("#credittotal").html());
								var owe = parseFloat($("#balanceowetotal").html());

								var totaltotal = accountdailyrate * customdays;
								$("#totaltotal").attr("data-total", parseFloat(totaltotal).toFixed(2));
								if(subtotal != 0) {
									$('#totaltotal').html(parseFloat(totaltotal).toFixed(2));
								} else {
									$("#totaltotal").html("");
								}

								var subtotal = totaltotal + noncapped + saleitem;
								subtotal = isNaN(subtotal) ? 0 : subtotal;
								$("#subtotal").attr("data-total", parseFloat(subtotal).toFixed(2));

								if(subtotal != 0) {
									$("#subtotal").html(parseFloat(subtotal).toFixed(2));
								} else {
									$("#subtotal").html("");
								}
								
								owe = isNaN(owe) ? 0 : owe;
								credit = isNaN(credit) ? 0 : credit;

								var gtotal = (subtotal - credit) + owe;
								gtotal = isNaN(gtotal) ? 0 : gtotal;
								$("#totalbalancedue").attr("data-total", parseFloat(gtotal).toFixed(2));

								if(gtotal != 0) {
									$("#totalbalancedue").html(parseFloat(gtotal).toFixed(2));
									$("#overalltotal").html(parseFloat(gtotal).toFixed(2));
								} else {
									$("#totalbalancedue").html("0.00");
                					$("#overalltotal").html('0.00');
								}
								
	                            // console.log("closecloseclose_success");
	                        } else {
	                            me_message_v2({error:1,message:"Error!"});
	                            // setTimeout(function(){
	                            //     location.reload();
	                            // },2000);
	                            // console.log("closecloseclose_error");
	                        }
	                    },1);
	                    
	                });
	            }
	        });
	    });
	});
</script>