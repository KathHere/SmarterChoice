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
		text-transform: uppercase !important;
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
		color: black !important;
		font-size: 8px;
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
		font-size: 8px;
		border: 1px solid !important;
    	border-color: #000 !important;
		color: black !important;
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

	.cut-out-portion {
		font-size: 10px !important
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

	#myModalLabel {
		text-align: center !important;
		font-size: 25px !important;
		height: 70px;
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
			size: portrait;
			margin: 8mm 12mm 12mm 12mm;
		}

		.d-flex-address {
			width: 50% !important;
		}

		.d-flex-info-box {
			width: 50% !important;
		}

		/* .statement_bill_container {
			width: 730px !important;
		} */

		.header-style {
			width: 55px !important;
		}

		.header-style-qty {
			width: 45px !important;
		}


		.show-print {
			display: block !important;
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
		.order_summary_label_container {
			text-align: center;
		}

		.statement_activity_container, .statement_bill_by_hospice_container, .statement_invoice_inquiry_container, .payment_history_container, .archive_container, .payment_history_by_hospice_container {
			display: none !important;
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
			margin-top: 20px !important;
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
			margin-top: 20px !important;
		}
		.account_statement_container {
			text-align: center !important;
			font-size: 14px !important;
			margin-left: -150px !important;
			font-weight: bold !important;
			text-transform: uppercase !important;
			
		}
		.date_printed {
			/*margin-top: -80px;*/
			/*margin-bottom: 80px;*/
			color: #000;
		}

		.location_container {
			display: block !important;
		}
	}

	/*=======================================Printer Loader==============================*/
	.loader {
	  border: 5px solid #f3f3f3;
	  border-radius: 50%;
	  border-top: 5px solid #3498db;
	  width: 35px;
	  height: 35px;
	  -webkit-animation: spin 2s linear infinite; /* Safari */
	  animation: spin 2s linear infinite;
	}

	/* Safari */
	@-webkit-keyframes spin {
	  0% { -webkit-transform: rotate(0deg); }
	  100% { -webkit-transform: rotate(360deg); }
	}

	@keyframes spin {
	  0% { transform: rotate(0deg); }
	  100% { transform: rotate(360deg); }
	}

	.printer_loader_icon {
	    position: absolute;
	    margin: auto;
	    top: 0;
	    width: 15px;
	    height: 15px;
	    bottom: 0;
	    right: 0;
	    left: 0;
	    z-index: 9;
	    font-size: 15px;
	}
	.loader-icon {
		position: relative;
	    height: 35px;
	    margin-top: 20px;
	    /*line-height: 1;*/
	    width: 35px;
	}

	.printer_loaded_icon {
		/*font-size: 20px;*/
	}

	.loaded-icon {
		border-radius: 50%;
		/* margin-top: 22px; */
		display: none;
	}

	.popover {
		font-weight: bold;
		font-size: 10px !important;
	}
	/*=======================================Printer Loader==============================*/

	.logo_ahmslv2 {
		/* display: none; */
	}

	.work_order_header_first2 {
		/* display: none; */
	}

	.retrieving_data {
		font-size: 11px;
		font-weight: bold;
	}

	.date_printed {
    	color: #fff;
    }

    .location_container {
		/*position: absolute;*/
		/*margin-top: -10px;*/
		margin-left: 25px;
		font-size: 13px;
		/*top: 0;*/
		left: 0;
	}

	.whole-body {
		background-color: white !important;
	}

	.wrapper-md {
		background-color: white !important;
	}

	.margin-left-right-email {
		/* padding-left: 70px !important; */
		padding-right: 70px !important;
		background-color: white !important;
	}

	.margin-bottom-email {
		/* padding-bottom: 50px !important; */
	}

	.margin-top-email {
		/* padding-top: 50px !important; */
	}

	.line2 {
		margin:5px 0;
		height:2px;
		background: repeating-linear-gradient(to right,#edf1f2 0,#edf1f2 10px,transparent 10px,transparent 12px)
			/*10px red then 2px transparent -> repeat this!*/
	}

	#globalModal {
		background-color: white !important;
	}

	.customer_name_container {
		font-size: 8px;
		padding-top: 3px;
		padding-bottom: 3px;
	}
	
</style>
<div style="background-color: white !important; color: black !important;" id="email-to-send">
	<!-- <div class="location_container margin-left-right-email margin-top-email" style="font-size: 18px">
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
	</div> -->
	<div class="row header_container margin-left-right-email" style="padding-right: 20px !important">
		<div class="col-md-12 ahms_logo_container2">
			<p class="logo_ahmslv2" style="margin-bottom:0px; text-align: center">
				<img class="logo_ahmslv_img" src="<?php echo base_url('assets/img/smarterchoice_logo.png'); ?>" alt="" style="height:30px;width:38px;"/>
			</p>
			<h4 class="work_order_header_first2" style="font-weight:bold;margin-top:0px;font-size:14px; text-align: center"> Advantage Home Medical Services, Inc </h4>
		</div>
		<div class="col-md-12 account_statement_header">
			<div class="row hidden-header" style="text-align: center; margin-bottom: 20px; font-size: 12px; font-weight: bold;">INVOICE</div>
		</div>
	</div>
	<!-- <div>
		<button class="send_email_btn btn btn-xs btn-info"><Strong>Email</Strong></button>
	</div> -->

	<div class="wrapper-md statement_first_page margin-left-right-email" style="margin-bottom:-40px; font-size: 18px !important; padding-top: 0px !important; padding-right: 20px !important">
		<div style="display: flex;">
			<div class="d-flex-address" style="padding-right: 20px; width: 50%">
				<table class="table bg-white b-a" style="text-transform: uppercase !important;">
					<tr>
						<td class="with-border td-info-box td-info-box td-info-header cut-out-portion"><strong>Shipping Address</strong></td>
					</tr>
					<tr>
						<td class="with-border td-info-box td-address-box cut-out-portion">
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
				
				<!-- Version 1 -->
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
							$temp_address = explode(", ", $account['hospice_address']); 
							$count_address = 0;
							foreach($temp_address as $key => $address) {
								if($count_address < 1) {
									echo $address.'</br>'; 
								}
								else {
									if($count_address == 1){
										echo $address;
									}
									else {
										echo ', '.$address;
									}
									
								}
								$count_address++;
							}
						?>
					</div>
				</div> -->

				<table class="table bg-white b-a" style="text-transform: uppercase !important;">
					<tr>
						<td class="with-border td-info-box td-info-box td-info-header cut-out-portion"><strong>Billing Address</strong></td>
					</tr>
					<tr>
						<td class="with-border td-info-box td-address-box cut-out-portion">
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

				<!-- Version 1 -->
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
							$temp_address = explode(", ", $account['billing_address']); 
							$count_address = 0;
							foreach($temp_address as $key => $address) {
								if($count_address < 1) {
									echo $address.'</br>'; 
								}
								else {
									if($count_address == 1){
										echo $address;
									}
									else {
										echo ', '.$address;
									}
									
								}
								$count_address++;
							}
						?>
					</div>
				</div> -->

				<table class="table bg-white b-a" style="text-transform: uppercase !important;">
					<tr>
						<td class="with-border td-info-box td-info-box td-info-header cut-out-portion"><strong>Send all Payments to</strong></td>
					</tr>
					<tr>
						<td class="with-border td-info-box td-address-box cut-out-portion">
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
						<textarea id="notes" style="width: 100%; height: 51px; border: none; padding: 10px; resize: none" placeholder="Enter note..."></textarea>
					</div>
				</div> -->

			</div>
			<div class="d-flex-info-box" style="width: 50%">
				<table class="table bg-white b-a" style="">
					<tr>
						<td class="with-border td-info-box cut-out-portion" colspan="2" style="text-align: center;">
							<strong>Service Date:</strong> 
							<?php echo date('m/d/Y', strtotime($invoice_details[0]['service_date_from'])); ?> - <?php echo date("m/t/Y", strtotime(date("m/d/Y", strtotime($invoice_details[0]['service_date_to']))));?>
						</td>
					</tr>
					<tr>
						<td class="with-border td-info-box cut-out-portion" colspan="2" style="text-align: center;">
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
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag">Account Number: </label><span> <?php echo $account['hospice_account_number']; ?></span>
						</td>
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag">Non Capped: </label>
							<span id="noncaptotal" data-total="<?php echo $invoice_details[0]['non_cap']; ?>">
								<?php 
									if($invoice_details[0]['non_cap'] != 0 && $invoice_details[0]['non_cap'] != "" && $invoice_details[0]['non_cap'] != null) {
										echo number_format((float)$invoice_details[0]['non_cap'], 2, '.', ''); 
									}
								?>
							</span>
						</td>
					</tr>
					<tr>
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag">Invoice Number: </label><span data-statement-no=""> <?php echo substr($invoice_details[0]['statement_no'],3,10); ?></span>
						</td>
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag">Sale Item: </label>
							<span id="purchasetotal" data-total="<?php echo $invoice_details[0]['purchase_item']; ?>">
								<?php 
									if($invoice_details[0]['purchase_item'] != 0 && $invoice_details[0]['purchase_item'] != "" && $invoice_details[0]['purchase_item'] != null) {
										echo number_format((float)$invoice_details[0]['purchase_item'], 2, '.', ''); 
									}
								?>
							</span>
						</td>
					</tr>
					<tr>
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag">Invoice Date: </label>
							<span><?php echo date("m/d/Y", strtotime($invoice_details[0]['invoice_date'])); ?></span>
						</td>
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag">Subtotal: </label><span id="subtotal" data-total="<?php echo number_format((float)$subtotal_amount, 2, '.', ''); ?>"> <?php echo number_format((float)$subtotal_amount, 2, '.', ''); ?></span>
						</td>
					</tr>
					<tr>
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag ">Due Date: </label>
							<span class=""><?php echo date("m/d/Y", strtotime($invoice_details[0]['due_date'])); ?></span>
						</td>
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag">Credit: </label><span id="credittotal" data-total="<?php echo $invoice_reconciliation['credit']; ?>">
							<?php
								if($invoice_details[0]['purchase_item'] != 0 && $invoice_reconciliation['credit'] != "" && $invoice_reconciliation['credit'] != null) {
									echo number_format((float)$invoice_reconciliation['credit'], 2, '.', '');
								}
							?>
						</span>
						</td>
					</tr>
					<tr>
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag">Daily Rate: </label>
							<span>
								<?php 

									if($account['daily_rate'] != 0 && $account['daily_rate'] != "" && $account['daily_rate'] != null) {
										echo number_format((float)$account['daily_rate'], 2, '.', ''); 
									}
									
								?>
							</span>
						</td>
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag">Owe: </label><span id="balanceowetotal" data-total="<?php echo $invoice_reconciliation['owe']; ?>">
								<?php
									if($invoice_reconciliation['owe'] != 0 && $invoice_reconciliation['owe'] != "" && $invoice_reconciliation['owe'] != null) {
										echo number_format((float)$invoice_reconciliation['owe'], 2, '.', ''); 
									}
								?>
							</span>
						</td>
					</tr>
					<tr>
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag">Customer Days: </label><span id="customerdays"><?php echo $invoice_details[0]['customer_days']; ?></span>
						</td>
						<td class="with-border td-info-box"></td>
					</tr>
					<tr>
						<td class="with-border td-info-box cut-out-portion">
						<label class="statement_label_tag">Total: </label>
						<span id="totaltotal" data-total="<?php echo $invoice_details[0]['total']; ?>">
							<?php
								if($invoice_details[0]['total'] != 0 && $invoice_details[0]['total'] != "" && $invoice_details[0]['total'] != null) {
									echo number_format((float)$invoice_details[0]['total'], 2, '.', '');
								}
							?>
							</span>
						</td>
						<td class="with-border td-info-box cut-out-portion">
							<label class="statement_label_tag">Payment Due: </label><span id="totalbalancedue" data-total="<?php echo $payment_due_amount; ?>">
								<?php
									if($payment_due_amount != 0 && $payment_due_amount != "" && $payment_due_amount != null) {
										echo number_format((float)$payment_due_amount, 2, '.', ''); 
									}
								?>
							</span>
						</td>
					</tr>
				</table>

				<!-- Version 2 -->
				<!-- <div class="panel panel-default">
					<div class="panel-heading">
						<div style="text-align: center;">
							<strong>Service Date:</strong> 
							<?php echo date('m/d/Y', strtotime($invoice_details[0]['service_date_from'])); ?> - <?php echo date("m/t/Y", strtotime(date("m/d/Y", strtotime($invoice_details[0]['service_date_to']))));?>
						</div>
					</div>
					<table class="table bg-white b-a" style="">
						<tr>
							<td class="with-border td-info-box td-info-box" colspan="2" style="text-align: center;">
								<?php echo date('m/d/Y', strtotime($invoice_details[0]['service_date_from'])); ?> - <?php echo date("m/t/Y", strtotime(date("m/d/Y", strtotime($invoice_details[0]['service_date_to']))));?>
							</td>
						</tr>
						<tr>
							<td class="with-border td-info-box td-info-box" colspan="2" style="text-align: center;">
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
								<label class="statement_label_tag">Non Capped: </label>
								<span id="noncaptotal" data-total="<?php echo $invoice_details[0]['non_cap']; ?>">
									<?php 
										if($invoice_details[0]['non_cap'] != 0 && $invoice_details[0]['non_cap'] != "" && $invoice_details[0]['non_cap'] != null) {
											echo number_format((float)$invoice_details[0]['non_cap'], 2, '.', ''); 
										}
									?>
								</span>
							</td>
						</tr>
						<tr>
							<td class="with-border td-info-box">
								<?php echo date('m/d/Y', strtotime($invoice_details[0]['service_date_from'])); ?> - <?php echo date("m/t/Y", strtotime(date("m/d/Y", strtotime($invoice_details[0]['service_date_to']))));?>
							</td>
							<td class="with-border td-info-box">
								<label class="statement_label_tag">Sale Item: </label>
								<span id="purchasetotal" data-total="<?php echo $invoice_details[0]['purchase_item']; ?>">
									<?php 
										if($invoice_details[0]['purchase_item'] != 0 && $invoice_details[0]['purchase_item'] != "" && $invoice_details[0]['purchase_item'] != null) {
											echo number_format((float)$invoice_details[0]['purchase_item'], 2, '.', ''); 
										}
									?>
								</span>
							</td>
						</tr>
						<tr>
							<td class="with-border td-info-box">
								<label class="statement_label_tag">Invoice Date: </label>
								<span><?php echo date("m/d/Y", strtotime($invoice_details[0]['invoice_date'])); ?></span>
							</td>
							<td class="with-border td-info-box">
								<label class="statement_label_tag">Subtotal: </label><span id="subtotal" data-total="0"> <i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
							</td>
						</tr>
						<tr>
							<td class="with-border td-info-box">
								<label class="statement_label_tag ">Due Date: </label>
								<span class=""><?php echo date("m/d/Y", strtotime($invoice_details[0]['due_date'])); ?></span>
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
							<td class="with-border td-info-box">
								<label class="statement_label_tag">Customer Days: </label><span id="customerdays"><?php echo $invoice_details[0]['customer_days']; ?></span>
							</td>
							<td></td>
						</tr>
						<tr>
							<td class="with-border td-info-box">
							<label class="statement_label_tag">Total: </label>
							<span id="totaltotal" data-total="<?php echo $invoice_details[0]['total']; ?>">
								<?php
									if($invoice_details[0]['total'] != 0 && $invoice_details[0]['total'] != "" && $invoice_details[0]['total'] != null) {
										echo number_format((float)$invoice_details[0]['total'], 2, '.', '');
									}
								?>
								</span>
							</td>
							<td class="with-border td-info-box">
								<label class="statement_label_tag">Balance Due: </label><span id="totalbalancedue" data-total="0"><i class="fa fa-spin fa-spinner item_decription_spinner"></i></span>
							</td>
						</tr>
					</table>
				</div> -->

				<!-- <div class="panel panel-default">
					<div class="panel-heading">
						<strong>Information</strong>
						<span class="pull-right">
							<strong>Service Date:</strong> 
							<?php echo date('m/d/Y', strtotime($invoice_details[0]['service_date_from'])); ?> - <?php echo date("m/t/Y", strtotime(date("m/d/Y", strtotime($invoice_details[0]['service_date_to']))));?>
						</span>
					</div>
					<div class="panel-body">
						<div class="statement_label_wrapper">
							<label class="statement_label_tag">Account Number: </label><span> <?php echo $account['hospice_account_number']; ?></span>
						</div>
						<div class="statement_label_wrapper">
							<label class="statement_label_tag">Invoice Number: </label><span data-statement-no=""> <?php echo substr($invoice_details[0]['statement_no'],3,10); ?></span>
							<input type="hidden" id="statement_invoice_id" value="<?php echo $invoice_details[0]['acct_statement_invoice_id']; ?>">
						</div>
						<div class="statement_label_wrapper">
							<label class="statement_label_tag">Invoice Date: </label>
							<span><?php echo date("m/d/Y", strtotime($invoice_details[0]['invoice_date'])); ?></span>
						</div>
						<div class="statement_label_wrapper">
								<label class="statement_label_tag ">Due Date: </label>
								<span class=""><?php echo date("m/d/Y", strtotime($invoice_details[0]['due_date'])); ?></span>
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
										echo $account['daily_rate']; 
									}
									
								?>
							</span>
						</div>
						<div class="statement_label_wrapper">
							<label class="statement_label_tag">Customer Days: </label><span id="customerdays"><?php echo $invoice_details[0]['customer_days']; ?></span>
						</div>
						<div class="statement_label_wrapper">
							<label class="statement_label_tag">Total: </label>
							<span id="totaltotal" data-total="<?php echo $invoice_details[0]['total']; ?>">
								<?php
									if($invoice_details[0]['total'] != 0 && $invoice_details[0]['total'] != "" && $invoice_details[0]['total'] != null) {
										echo number_format((float)$invoice_details[0]['total'], 2, '.', '');
									}
								?>
							</span>
						</div>
						<div class="statement_label_wrapper">
							<label class="statement_label_tag">Non Capped: </label>
							<span id="noncaptotal" data-total="<?php echo $invoice_details[0]['non_cap']; ?>">
								<?php 
									if($invoice_details[0]['non_cap'] != 0 && $invoice_details[0]['non_cap'] != "" && $invoice_details[0]['non_cap'] != null) {
										echo number_format((float)$invoice_details[0]['non_cap'], 2, '.', ''); 
									}
								?>
							</span>
						</div>
						<div class="statement_label_wrapper">
							<label class="statement_label_tag">Sale Item: </label>
							<span id="purchasetotal" data-total="<?php echo $invoice_details[0]['purchase_item']; ?>">
								<?php 
									if($invoice_details[0]['purchase_item'] != 0 && $invoice_details[0]['purchase_item'] != "" && $invoice_details[0]['purchase_item'] != null) {
										echo number_format((float)$invoice_details[0]['purchase_item'], 2, '.', ''); 
									}
								?>
							</span>
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
						<td class="with-border td-info-box td-info-header cut-out-portion"><strong>Notes</strong></td>
					</tr>
					<tr>
						<td class="with-border td-info-box cut-out-portion">
							<textarea id="notes" style="width: 100%; height: 25px; border: none; padding: 10px; resize: none; box-shadow: none !important; overflow-y: hidden;" placeholder=""></textarea>
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
						<span>Ph. 702-248-0056  Fax: 702-889-0059</span>
					</div>
				</div> -->
			</div>
		</div>
		
	</div>

	<div id="scissors_dashed_lines" class="margin-left-right-email" style="margin-top: 20px">
		<div class="show-print" style="float: left; margin-top: -9px;">
			<i class="fa fa-scissors" style="font-size: 15px;"></i>
		</div>
		<hr id="dashed_lines" style="border-bottom: 1px dashed #edf1f2" />
		<!-- <div class="line2"></div> -->
	</div>
	<input type="hidden" id="statement_invoice_id" value="<?php echo $invoice_details[0]['acct_statement_invoice_id']; ?>">
	<div class="wrapper-md margin-left-right-email margin-bottom-email" id="order_list" style="padding-top: 0px !important; padding-right: 20px !important">
		<table class="bg-white b-a datatable_table_statement_bill table_page_1" style="text-align: center;  font-size: 18px;">
			<thead  style="background-color: #f6f8f8; ">
				<tr style="background-color: white">
					<th class="compress-center with-border" style="width: 45px;">Work </br> Order #</th>
					<!-- <th >Item #</th> -->
					<th class="with-border" style="text-align: center; width: 350px; !important">Customer Name</th>
					<!-- <th >MR #</th> -->
					<th class="compress-center with-border " style="width: 50px;">DEL. DATE</th>
					<th class="compress-center with-border" style="width: 50px;">P/U DATE</th>
					<th class="compress-center with-border header-style-qty" style="width: 32px;">QTY</th>
					<th class="compress-center with-border" style="width: 42px;">Capped</th>
					<th class="compress-center with-border header-style" style="width: 35px;">SALE</th>
					<th class="compress-center with-border header-style"style="width: 68px;">Non Capped</th>
					<th class="with-border" style="text-align: center; width: 41px;">Total</th>
					<th class="compress-center with-border" style="width: 35px;">CUS </br>Days</th>
					<th class="compress-center with-border" style="width: 50px;">Daily </br> Rate</th>
					<th class="compress-center with-border" style="width: 48px;">Amount </br> Due</th>
				</tr>
			</thead>
			<!-- id => order_list_tbody -->
			<tbody id="">
				<?php
				// print_me($customers);
				// foreach($customers as $key => $customer) {
					// print_me($customer);
				?>
					
				<?php
					// foreach($customers_orders['cus_orders'] as $key => $customer) {
					// 	// if($prev_customer != $customer_order['patientID']) {
							$grand_total = 0;
							$prev_customer = $customer_order['patientID'];
							$prev_addressID = 0;
						// }
							$cusmove_count = 1;
							$respite_count = 1;
							$prev_cusmove_count = 1;
							$prev_respite_count = 1;
							$is_add_cusmove = false;
							$is_add_respite = false;
							$activity_type_disp = "";
							$row_count = 0;
						foreach($order_summary['result'] as $co_key => $customer_order){
							if($row_count == 1050) {
								break;
							}
							$row_count++;
							if($customer_order['patientID'] != $prev_customer) {
								$grand_total = 0;
								$prev_customer = $customer_order['patientID'];
								$prev_addressID = 0;
								$cusmove_count = 1;
								$respite_count = 1;
								$prev_cusmove_count = 1;
								$prev_respite_count = 1;
								$is_add_cusmove = false;
								$is_add_respite = false;
								$activity_type_disp = "";
							}
							$total = 0;
							$tr_class = "";
							$check_new_add = false;
							// $activity_type_disp = "";
							if($prev_patientID != $customer_order['patientID']) {
								$prev_activityid = $customer_order['activity_typeid'];
								// $prev_addressID = $customer_order['addressID'];
								$prev_patientID = $customer_order['patientID'];
								$grand_total = 0;
								
				?>
				<!-- background-color: #efefef -->
				<tr style="">
					<td class="compress-center with-border">
						<strong>
						<?php
						// if($customer_order['original_activity_typeid'] == 5) {
						// 	$activity_type_disp = "Respite";
						// 	$prev_respite_count = $respite_count;
						// 	$respite_count++;
						// 	if($prev_respite_count > 1) {
						// 		$activity_type_disp = $activity_type_disp.' '.$prev_respite_count;
						// 	}
						// }
						// else if($customer_order['original_activity_typeid'] == 4) {
						// 	$activity_type_disp =  "CUS Move";
						// 	$prev_cusmove_count = $cusmove_count;
						// 	$cusmove_count++;
						// 	if($prev_cusmove_count > 1) {
						// 		$activity_type_disp = $activity_type_disp.' '.$prev_cusmove_count;
						// 	}
						// } else {
						// 	$activity_type_disp = "";
						// }
						// $is_add_cusmove = false;
						// $is_add_respite = false;
						
						// echo $activity_type_disp;  
						?>
						</strong>

					</td>
					<td class="with-border">
						<div class="customer_name_container" style=""><strong><?php echo $customer_order['p_lname'].', '.$customer_order['p_fname'] ?> - MR# <?php echo $customer_order['medical_record_id']; ?></strong></div>
					</td>
					<td class="compress-center with-border">&nbsp;</td>
					<td class="compress-center with-border">&nbsp;</td>
					<td class="compress-center with-border">&nbsp;</td>
					<td class="compress-center with-border"> &nbsp;</td>
					<td class="compress-center with-border"></td>
					<td class="compress-center with-border"></td>
					<td class="compress-center with-border">&nbsp;</td>
					<?php
						// if($customer_order['original_activity_typeid'] == 4 || $customer_order['original_activity_typeid'] == 5) {
					?>  
					<!-- <td class="compress-center with-border"></td>
					<td class="compress-center with-border"></td>
					<td class="compress-center with-border"></td> -->
					<?php
						// } else {
					?>
					<td class="compress-center with-border"><?php echo $customer_order['cus_days']; ?></td>
					<td class="compress-center with-border"><?php echo number_format((float)$account['daily_rate'], 2, '.', ''); ?></td>
					<td class="compress-center with-border">
					<?php 
						$customer_first_row = ($customer_order['cus_days'] * $account['daily_rate']);
						echo number_format((float)$customer_first_row, 2, '.', '');
						$grand_total += $customer_first_row;
					?>
					</td>
					<?php
						// }
					?>
					
				</tr>
				<?php }
				if($customer_order['equip_is_package'] == 0 && $customer_order['is_package'] == 1) {
					continue;
				}
				?>
				<tr class="<?php echo $tr_class; ?>">
				<?php
						if($customer_order['uniqueID'] == $prev_workorder) {
				?>

					<td class="compress-center with-border td-info-box">&nbsp;</td>
					
				<?php				
						} else {
				?>
					<td class="compress-center with-border td-info-box"><?php echo substr($customer_order['uniqueID'],3,10); ?></td>
				<?php
						}
						$prev_workorder = $customer_order['uniqueID'];
						$prev_customer = $customer_order['patientID'];
						// if($customer_order['original_activity_typeid'] == 5 && $is_add_respite == false) {
						// 	$activity_type_disp = "Respite";
						// 	$prev_respite_count = $respite_count;
						// 	$respite_count++;
						// 	$is_add_respite = true;
						// }
						// else if($customer_order['original_activity_typeid'] == 4 && $is_add_cusmove == false) {
						// 	$activity_type_disp =  "CUS Move";
						// 	$prev_cusmove_count = $cusmove_count;
						// 	$cusmove_count++;
						// 	$is_add_cusmove = true;
						// } 
						// else {
						// 	$activity_type_disp = "";
						// }
				?>
					<td class="with-border td-info-box">
						<div ><?php echo $customer_order['key_desc']?></div>
					</td>
					<td class="compress-center with-border td-info-box">
					<?php 
					if($customer_order['delivery_date'] != "0000-00-00 00:00:00" && $customer_order['delivery_date'] != "0000-00-00") {
						echo date('m/d/Y', strtotime($customer_order['delivery_date']));
					} else {
						echo "&nbsp;";
					}
					?>
					</td>
					<td class="compress-center with-border td-info-box">
					<?php 
					if($customer_order['pickup_date'] != "0000-00-00 00:00:00" && $customer_order['pickup_date'] != "0000-00-00") {
						echo date('m/d/Y', strtotime($customer_order['pickup_date']));
					} else {
						echo "&nbsp;";
					}
					?>
							
					</td>
					<td class="with-border td-info-box">
						<?php 
						echo $customer_order['quantity'];
						?>	
					</td>
					<td class="with-border td-info-box">
						<?php 
						echo $customer_order['cap'];
						?>
					</td>
					<td class="compress-center with-border td-info-box">
						<?php 
						if($customer_order['purchase_item'] != 0) {
							echo number_format((float)$customer_order['purchase_item'], 2, '.', '');
						}
						else {
							echo "&nbsp;";
						}
						?>
							
					</td>
					<td class="compress-center with-border td-info-box">
						<?php 
						if($customer_order['non_cap'] != 0) {
							echo number_format((float)$customer_order['non_cap'], 2, '.', '');
						}
						else {
							echo "&nbsp;";
						}
						?>
							
						</td>
					<td class="compress-center with-border td-info-box">
						<?php 
						if($customer_order['total'] != 0) {
							echo number_format((float)$customer_order['total'], 2, '.', '');
						}
						else {
							echo "&nbsp;";
						}
						?>
					</td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box">
						<?php 
						if($customer_order['total'] != 0) {
							$grand_total = $grand_total + $customer_order['total'];
							echo number_format((float)$customer_order['total'], 2, '.', '');
						}
						else {
							echo "&nbsp;";
						}
						?>
					</td>
				</tr>	
				<?php
						// if(){

						// }
						if($order_summary['result'][$co_key+1]['patientID'] != $customer_order['patientID']) {
						// 	$prev_activityid = $customer_order['activity_typeid'];
							// $prev_addressID = $customer_order['addressID'];
				?>
				<tr>
					<td class="compress-center with-border-bottom with-border-left">&nbsp;</td>
					<td class="compress-center with-border-bottom">&nbsp;</td>
					<td class="compress-center with-border-bottom">
						<strong>
						<?php

						// if($activity_type_disp == "CUS Move") {
						// 	if($prev_cusmove_count == 1) {
						// 		echo $activity_type_disp;
						// 	} else {
						// 		echo $activity_type_disp.' '.$prev_cusmove_count;
						// 	}
							
						// }
						// if($activity_type_disp == "Respite") {
						// 	if($prev_respite_count == 1) {
						// 		echo $activity_type_disp;
						// 	} else {
						// 		echo $activity_type_disp.' '.$prev_respite_count;
						// 	}
							
						// }

						
						?>
						</strong>
					</td>
					<td class="compress-center with-border-bottom">&nbsp;</td>
					<td class="compress-center with-border-bottom">&nbsp;</td>
					<td class="compress-center with-border-bottom"> &nbsp;</td>
					<td class="compress-center with-border-bottom"></td>
					<td class="compress-center with-border-bottom"></td>
					<td class="compress-center with-border-bottom">&nbsp;</td>
					<td class="compress-center with-border-bottom"></td>
					<td class="compress-center with-border-bottom">TOTAL:</td>
					<td class="compress-center with-border-bottom with-border-right"><?php echo number_format((float)$grand_total, 2, '.', ''); ?></td>
				</tr>
				<?php
						}
					}
				?>
				<!-- <tr>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td style="border-right:none">&nbsp;</td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box"> &nbsp;</td>
					<td class="compress-center with-border td-info-box"></td>
					<td class="compress-center with-border td-info-box"></td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box"></td>
					<td class="compress-center with-border td-info-box">TOTAL:</td>
					<td class="compress-center with-border td-info-box"><?php echo $grand_total; ?></td>
				</tr> -->
				<?php
				// }

				?>
				
				
			</tbody>
		</table>

		<?php
			$order_summary_count = count($order_summary['result']);
			$table_pages = ceil($order_summary_count / 1050);
			// echo '<div style="height: 50px; background-color: red">'.$table_pages.'</div>';
			// $table_page_count = 1;
		?>
		<input type="hidden" id="order_summary_count" value="<?php echo $order_summary_count; ?>">
		<input type="hidden" id="table_pages" value="<?php echo $table_pages; ?>">
		<?php
			for ($table_page_count = 1; $table_page_count < $table_pages; $table_page_count++) {
				$row_limit = 1050 * ($table_page_count+1);
				
		?>
		<!-- <div style="height: 50px; background-color: red"></div> -->
		<table class="bg-white b-a datatable_table_statement_bill table_page_<?php echo $table_page_count+1; ?>" style="text-align: center;  font-size: 18px; display: none">
			<thead  style="background-color: #f6f8f8; ">
				<tr style="background-color: white">
					<th class="compress-center with-border" style="width: 45px;">Work </br> Order #</th>
					<!-- <th >Item #</th> -->
					<th class="with-border" style="text-align: center; width: 350px; !important">Customer Name</th>
					<!-- <th >MR #</th> -->
					<th class="compress-center with-border " style="width: 50px;">DEL. DATE</th>
					<th class="compress-center with-border" style="width: 50px;">P/U DATE</th>
					<th class="compress-center with-border header-style-qty" style="width: 32px;">QTY</th>
					<th class="compress-center with-border" style="width: 42px;">Capped</th>
					<th class="compress-center with-border header-style" style="width: 35px;">SALE</th>
					<th class="compress-center with-border header-style"style="width: 68px;">Non Capped</th>
					<th class="with-border" style="text-align: center; width: 41px;">Total</th>
					<th class="compress-center with-border" style="width: 35px;">CUS </br>Days</th>
					<th class="compress-center with-border" style="width: 50px;">Daily </br> Rate</th>
					<th class="compress-center with-border" style="width: 48px;">Amount </br> Due</th>
				</tr>
			</thead>
			<tbody id="">
			<?php
				for ($j_count = $row_count + 1; $j_count < $order_summary_count; $j_count++) {
					$co_key = $j_count;
					$customer_order = $order_summary['result'][$j_count];
				// foreach($order_summary['result'] as $co_key => $customer_order){
					if($row_count == $row_limit) {
						break;
					}
					$row_count++;
					if($customer_order['patientID'] != $prev_customer) {
						$grand_total = 0;
						$prev_customer = $customer_order['patientID'];
						$prev_addressID = 0;
						$cusmove_count = 1;
						$respite_count = 1;
						$prev_cusmove_count = 1;
						$prev_respite_count = 1;
						$is_add_cusmove = false;
						$is_add_respite = false;
						$activity_type_disp = "";
					}
					$total = 0;
					$tr_class = "";
					$check_new_add = false;
					// $activity_type_disp = "";
					if($prev_patientID != $customer_order['patientID']) {
						$prev_activityid = $customer_order['activity_typeid'];
						// $prev_addressID = $customer_order['addressID'];
						$prev_patientID = $customer_order['patientID'];
						$grand_total = 0;
			?>
				<tr style="">
					<td class="compress-center with-border">
						<strong>
						<?php
						// if($customer_order['original_activity_typeid'] == 5) {
						// 	$activity_type_disp = "Respite";
						// 	$prev_respite_count = $respite_count;
						// 	$respite_count++;
						// 	if($prev_respite_count > 1) {
						// 		$activity_type_disp = $activity_type_disp.' '.$prev_respite_count;
						// 	}
						// }
						// else if($customer_order['original_activity_typeid'] == 4) {
						// 	$activity_type_disp =  "CUS Move";
						// 	$prev_cusmove_count = $cusmove_count;
						// 	$cusmove_count++;
						// 	if($prev_cusmove_count > 1) {
						// 		$activity_type_disp = $activity_type_disp.' '.$prev_cusmove_count;
						// 	}
						// } else {
						// 	$activity_type_disp = "";
						// }
						// $is_add_cusmove = false;
						// $is_add_respite = false;
						
						// echo $activity_type_disp;  
						?>
						</strong>

					</td>
					<td class="with-border">
						<div class="customer_name_container" style=""><strong><?php echo $customer_order['p_lname'].', '.$customer_order['p_fname'] ?> - MR# <?php echo $customer_order['medical_record_id']; ?></strong></div>
					</td>
					<td class="compress-center with-border">&nbsp;</td>
					<td class="compress-center with-border">&nbsp;</td>
					<td class="compress-center with-border">&nbsp;</td>
					<td class="compress-center with-border"> &nbsp;</td>
					<td class="compress-center with-border"></td>
					<td class="compress-center with-border"></td>
					<td class="compress-center with-border">&nbsp;</td>
					<?php
						if($customer_order['original_activity_typeid'] == 4 || $customer_order['original_activity_typeid'] == 5) {
					?>  
					<td class="compress-center with-border"></td>
					<td class="compress-center with-border"></td>
					<td class="compress-center with-border"></td>
					<?php
						} else {
					?>
					<td class="compress-center with-border"><?php echo $customer_order['cus_days']; ?></td>
					<td class="compress-center with-border"><?php echo number_format((float)$account['daily_rate'], 2, '.', ''); ?></td>
					<td class="compress-center with-border">
					<?php 
						$customer_first_row = ($customer_order['cus_days'] * $account['daily_rate']);
						echo number_format((float)$customer_first_row, 2, '.', '');
						$grand_total += $customer_first_row;
					?>
					</td>
					<?php
						}
					?>
					
				</tr>
				<?php }
				if($customer_order['equip_is_package'] == 0 && $customer_order['is_package'] == 1) {
					continue;
				}
				?>
				<tr class="<?php echo $tr_class; ?>">
				<?php
						if($customer_order['uniqueID'] == $prev_workorder) {
				?>

					<td class="compress-center with-border td-info-box">&nbsp;</td>
					
				<?php				
						} else {
				?>
					<td class="compress-center with-border td-info-box"><?php echo substr($customer_order['uniqueID'],3,10); ?></td>
				<?php
						}
						$prev_workorder = $customer_order['uniqueID'];
						$prev_customer = $customer_order['patientID'];
						// if($customer_order['original_activity_typeid'] == 5 && $is_add_respite == false) {
						// 	$activity_type_disp = "Respite";
						// 	$prev_respite_count = $respite_count;
						// 	$respite_count++;
						// 	$is_add_respite = true;
						// }
						// else if($customer_order['original_activity_typeid'] == 4 && $is_add_cusmove == false) {
						// 	$activity_type_disp =  "CUS Move";
						// 	$prev_cusmove_count = $cusmove_count;
						// 	$cusmove_count++;
						// 	$is_add_cusmove = true;
						// } 
						// else {
						// 	$activity_type_disp = "";
						// }
				?>
					<td class="with-border td-info-box">
						<div ><?php echo $customer_order['key_desc']?></div>
					</td>
					<td class="compress-center with-border td-info-box">
					<?php 
					if($customer_order['delivery_date'] != "0000-00-00 00:00:00" && $customer_order['delivery_date'] != "0000-00-00") {
						echo date('m/d/Y', strtotime($customer_order['delivery_date']));
					} else {
						echo "&nbsp;";
					}
					?>
					</td>
					<td class="compress-center with-border td-info-box">
					<?php 
					if($customer_order['pickup_date'] != "0000-00-00 00:00:00" && $customer_order['pickup_date'] != "0000-00-00") {
						echo date('m/d/Y', strtotime($customer_order['pickup_date']));
					} else {
						echo "&nbsp;";
					}
					?>
							
					</td>
					<td class="with-border td-info-box">
						<?php 
						echo $customer_order['quantity'];
						?>	
					</td>
					<td class="with-border td-info-box">
						<?php 
						echo $customer_order['cap'];
						?>
					</td>
					<td class="compress-center with-border td-info-box">
						<?php 
						if($customer_order['purchase_item'] != 0) {
							echo number_format((float)$customer_order['purchase_item'], 2, '.', '');
						}
						else {
							echo "&nbsp;";
						}
						?>
							
					</td>
					<td class="compress-center with-border td-info-box">
						<?php 
						if($customer_order['non_cap'] != 0) {
							echo number_format((float)$customer_order['non_cap'], 2, '.', '');
						}
						else {
							echo "&nbsp;";
						}
						?>
							
						</td>
					<td class="compress-center with-border td-info-box">
						<?php 
						if($customer_order['total'] != 0) {
							echo number_format((float)$customer_order['total'], 2, '.', '');
						}
						else {
							echo "&nbsp;";
						}
						?>
					</td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box">
						<?php 
						if($customer_order['total'] != 0) {
							$grand_total = $grand_total + $customer_order['total'];
							echo number_format((float)$customer_order['total'], 2, '.', '');
						}
						else {
							echo "&nbsp;";
						}
						?>
					</td>
				</tr>	
				<?php
						// if(){

						// }
						if($order_summary['result'][$co_key+1]['patientID'] != $customer_order['patientID']) {
						// 	$prev_activityid = $customer_order['activity_typeid'];
							// $prev_addressID = $customer_order['addressID'];
				?>
				<tr>
					<td class="compress-center with-border-bottom with-border-left">&nbsp;</td>
					<td class="compress-center with-border-bottom">&nbsp;</td>
					<td class="compress-center with-border-bottom">
						<strong>
						<?php

						// if($activity_type_disp == "CUS Move") {
						// 	if($prev_cusmove_count == 1) {
						// 		echo $activity_type_disp;
						// 	} else {
						// 		echo $activity_type_disp.' '.$prev_cusmove_count;
						// 	}
							
						// }
						// if($activity_type_disp == "Respite") {
						// 	if($prev_respite_count == 1) {
						// 		echo $activity_type_disp;
						// 	} else {
						// 		echo $activity_type_disp.' '.$prev_respite_count;
						// 	}
							
						// }

						
						?>
						</strong>
					</td>
					<td class="compress-center with-border-bottom">&nbsp;</td>
					<td class="compress-center with-border-bottom">&nbsp;</td>
					<td class="compress-center with-border-bottom"> &nbsp;</td>
					<td class="compress-center with-border-bottom"></td>
					<td class="compress-center with-border-bottom"></td>
					<td class="compress-center with-border-bottom">&nbsp;</td>
					<td class="compress-center with-border-bottom"></td>
					<td class="compress-center with-border-bottom">TOTAL:</td>
					<td class="compress-center with-border-bottom with-border-right"><?php echo number_format((float)$grand_total, 2, '.', ''); ?></td>
				</tr>
				<?php
						}
					}
				?>
				<!-- <tr>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td style="border-right:none">&nbsp;</td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box"> &nbsp;</td>
					<td class="compress-center with-border td-info-box"></td>
					<td class="compress-center with-border td-info-box"></td>
					<td class="compress-center with-border td-info-box">&nbsp;</td>
					<td class="compress-center with-border td-info-box"></td>
					<td class="compress-center with-border td-info-box">TOTAL:</td>
					<td class="compress-center with-border td-info-box"><?php echo $grand_total; ?></td>
				</tr> -->
				<?php
				// }

				?>
			</tbody>
		</table>

		<?php
				$table_page_count++;
			}
		?>
		<!-- <div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-7 text-right order_summary_label_container">
						<span class="order_summary_label" style="font-size: 18px">Order Summary</span>
					</div>
					<div class="col-md-5 text-right hide_buttons" style="margin-top: 4px; display: none;">
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
		</div> -->
		<div class="text-center hidden-print" id="load_more_content" style="display: none;">
			<button class="btn btn-info" style="" id="load_more_patient" data-hospice-id="<?php echo $account['hospiceID']; ?>" data-current-count="<?php echo $customers_orders['limit'];?>" data-daily-rate="<?php echo $account['daily_rate']?>" data-total-count="<?php echo $customers_orders['totalCustomerCount'];?>" style="text-align:center;">Load More</button>
		</div>
		<!-- <div class="pull-right"><span><strong>Total Balance Due: </strong></span>  &nbsp; 34.45</div> -->
		<?php
		$display_payment_due_footer = '';
		if ($table_pages > 1) {
			$display_payment_due_footer = "none";
		} else {
			$display_payment_due_footer = "block";
		}
		?>
		<div id="payment_due_footer" class="wrapper-md" style="padding-top: 0px !important; margin-bottom: 20px !important; font-size: 12px; font-weight: bold; display: <?php echo $display_payment_due_footer; ?>">
			<div class="pull-right">
				Payment Due: <span id="overalltotal"><?php echo number_format((float)$payment_due_amount, 2, '.', ''); ?></span>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/html2pdf.js-master/dist/html2pdf.bundle.min.js" type="text/javascript"></script>

<script type="text/javascript">
	
	$(document).ready(function(){
		
		$('body').on('click','.send_email_btn',function(){
            var wholebody = $('.whole-body')[0];
			console.log('wholebody', wholebody);
            var opt = {
                margin:       0.3,
                filename:     'myfile.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 3, allowTaint: true },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(wholebody).save();
        });
		//FOR AUTOLOAD IF THE DOCUMENT IS READY
		// setTimeout(function(){
		// 	$('#load_more_patient').click();
		// },1000);

		// var current_page = 1;
  		var limit = 50;
  		var onscroll = false;
  		var hosID = $('#load_more_patient').attr("data-hospice-id");
  		var dailyrat = parseFloat($('#load_more_patient').attr("data-daily-rate"));
  // 		//get customer days
  // 		$.get(base_url+'billing_statement/get_customer_days/'+hosID, function(response){
	 // 		var jsonparse = JSON.parse(response);
	 // 		$("#customerdays").html(jsonparse[0].cus_days);
	 // 		$("#totaltotal").html(dailyrat*parseInt(jsonparse[0].cus_days));
	 // 		var gtotal = parseFloat($("#totalbalancedue").attr("data-total")) + (dailyrat*parseInt(jsonparse[0].cus_days));
	 // 		$("#totalbalancedue").html(gtotal);
	 // 		$("#totalbalancedue").attr("data-total", gtotal);
	 // 	});

  // 		//get total of purchase and noncap for information panel
	 // 	var purcatID = 3;
	 // 	$.get(base_url+'billing_statement/get_category_total/'+hosID+"/"+purcatID, function(response){
	 // 		$("#purchasetotal").html(response);
	 // 		var gtotal = parseFloat($("#totalbalancedue").attr("data-total")) + parseFloat(response);
	 // 		$("#totalbalancedue").html(gtotal);
	 // 		$("#totalbalancedue").attr("data-total", gtotal);
	 // 	});
		// var noncatID = 2;
	 // 	$.get(base_url+'billing_statement/get_category_total/'+hosID+"/"+noncatID, function(response){
	 // 		$("#noncaptotal").html(response);
	 // 		var gtotal = parseFloat($("#totalbalancedue").attr("data-total")) + parseFloat(response);
	 // 		$("#totalbalancedue").html(gtotal);
	 // 		$("#totalbalancedue").attr("data-total", gtotal);
	 // 	});

	 	// var _total = parseFloat($('#totaltotal').attr('data-total'));
	 	// var _noncaptotal = parseFloat($('#noncaptotal').attr('data-total'));
	 	// var _saleitem = parseFloat($('#purchasetotal').attr('data-total'));
	 	// console.log('_noncaptotal', _noncaptotal);
	 	// console.log('_saleitem', _saleitem);
	 	// var _gtotal = parseFloat(_total + _noncaptotal + _saleitem).toFixed(2);
	 	// console.log('_gtotal', _gtotal);

	 	// if(_gtotal > 0) {
	 	// 	$("#subtotal").html(_gtotal);
	 	// } else {
	 	// 	$("#subtotal").html("");
	 	// }
	 	// $("#subtotal").attr("data-total", _gtotal);

		// //Reconciliation	
		// var _credit = parseFloat($("#credittotal").attr('data-total'));
		// var _owe = parseFloat($("#balanceowetotal").attr('data-total'));
		// _gtotal = _gtotal - Number(_credit);
		// _gtotal = _gtotal + Number(_owe);

	 	// $("#totalbalancedue").html(_gtotal);
	 	// $("#totalbalancedue").attr("data-total", _gtotal);

		

	 	//get the Invoice Reconciliation for information panel
	 	var invoice_id = $('#statement_invoice_id').val();
	 	$.get(base_url+'billing_reconciliation/get_invoice_reconciliation_credit_and_owe/'+invoice_id, function(response){
	 		console.log('response', parseFloat(JSON.parse(response).invoice_reconciliation.owe).toFixed(2));
	 		var credit = isNaN(parseFloat(JSON.parse(response).invoice_reconciliation.credit).toFixed(2)) ? 0 : parseFloat(JSON.parse(response).invoice_reconciliation.credit).toFixed(2);
	 		var owe = isNaN(parseFloat(JSON.parse(response).invoice_reconciliation.owe).toFixed(2)) ? 0 : parseFloat(JSON.parse(response).invoice_reconciliation.owe).toFixed(2);

			$("#notes").val(JSON.parse(response).invoice_reconciliation_comments);

	 		if(credit != 0) {
	 			$("#credittotal").html(credit);
	 		} else {
	 			$("#credittotal").html("");
	 		}

	 		if(owe != 0) {
	 			$("#balanceowetotal").html(owe);
	 		} else {
	 			$("#balanceowetotal").html("");
	 		}

	 		var gtotal = parseFloat($("#totalbalancedue").attr("data-total"));
	 		gtotal = gtotal - Number(credit);
	 		gtotal = gtotal + Number(owe);
	 		
	 		if(gtotal > 0) {
	 			$("#totalbalancedue").html(parseFloat(gtotal).toFixed(2));
				$("#overalltotal").html(parseFloat(gtotal).toFixed(2));
	 		} else {
				$("#totalbalancedue").html('0.00');
				$("#overalltotal").html('0.00');
	 		}
	 		
	 		$("#totalbalancedue").attr("data-total", parseFloat(gtotal).toFixed(2));
	 	});
	 		
	 	// 	$("#totalbalancedue").attr("data-total", parseFloat(gtotal).toFixed(2));
	 	// 	console.log('parseFloat(gtotal).toFixed(2)',parseFloat(gtotal).toFixed(2));
	 	// });
		
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
		console.log('y2k: ', y2k);
		var Jan1st2010 = new Date(y2k.getFullYear(), y2k.getMonth(), y2k.getDate());
		var today= new Date(); //displays 726
		console.log('date: ',Date.daysBetween(Jan1st2010,today));				
		$('#load_more_patient').bind('click',function(){
		 	var _this = $(this);
		 	var order_list = $('#order_list');
		 	var hospiceID = _this.attr("data-hospice-id");
		 	var currentCount = parseInt(_this.attr("data-current-count"));
		 	// console.log(currentCount);
		 	// console.log(limit);
		 	var daily_rate = parseFloat(_this.attr("data-daily-rate")).toFixed(2);
		 	var order_list_tbody = $('#order_list_tbody');
		 	var cap_h = 0;
	 		var noncap_h = 0;
	 		var purcahse_h = 0;
	 		var counterrr = 0;
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
	 		console.log('cap_h: ', cap_h);
		 	// $("#load_more_content").remove();
		 	order_list.append('<div id="loading_more" class="hidden-print" style="text-align:center;font-size:18px;">Retrieving Data... <i class="fa fa-spinner fa-spin fa-2x"></i></div>');
		 	
		 	$.get(base_url+'billing_statement/statement_bill_load_more/'+hospiceID+"/"+currentCount+"/"+limit, function(response){
		 		
	  			var jsonparse = JSON.parse(response);
	  			console.log('THIS IS THE RESPONSE', jsonparse.customers_orders.totalCount);
	  			console.log('THIS IS THE RESPONSE', jsonparse.customers_orders.cus_orders[0].customer_orders.length);
	  			console.log('CUS_orders: ', jsonparse.customers_orders.cus_orders);
	  			// var cus_orders = jsonparse.customers_orders.cus_orders;
	  			console.log('totalCount: ', jsonparse.customers_orders.totalCount);
		 		currentCount += jsonparse.customers_orders.totalCount;
		 		console.log('Current totalCount: ', currentCount);
		 		var final_html = "";
		 		for(var i = 0; i < jsonparse.customers_orders.cus_orders.length; i++) {
		 			// console.log('cus_orders: ', jsonparse.customers_orders.cus_orders[i]);
		 			var grand_total = 0;
		 			var amount_due = daily_rate * jsonparse.customers_orders.cus_orders[i].customer_info.patient_days;
		 			// var customer_info = '<tr style="background-color: #efefef"><td class="compress-center with-border td-info-box">&nbsp;</td><td class="with-border td-info-box"><div style="font-size: 15px;"><strong>'+jsonparse.customers_orders.cus_orders[i].customer_info.p_fname+' '+jsonparse.customers_orders.cus_orders[i].customer_info.p_lname+' - MR # '+jsonparse.customers_orders.cus_orders[i].customer_info.medical_record_id+'</strong></div></td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">'+jsonparse.customers_orders.cus_orders[i].customer_info.patient_days+'</td><td class="compress-center with-border td-info-box">'+daily_rate+'</td><td class="compress-center with-border td-info-box">'+amount_due+'</td></tr>';
		 			// final_html += customer_info;
		 			// grand_total += amount_due;
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
		 			for (var j = 0; j < jsonparse.customers_orders.cus_orders[i].customer_orders.length; j++) {
		 				var activity_type = "";
		 				var customer_info = "";
		 				if(prev_addressID != jsonparse.customers_orders.cus_orders[i].customer_orders[j].addressID) {
		 					grand_total = 0;
		 					is_add_cusmove = false;
		 					is_add_respite = false;
		 					prev_addressID = jsonparse.customers_orders.cus_orders[i].customer_orders[j].addressID;
		 					customer_info = '<tr style="background-color: #efefef"><td class="compress-center with-border td-info-box">&nbsp;</td><td class="with-border td-info-box"><div class="customer_name_container" style="margin-bottom: -20px; font-size: 15px;"><strong>'+jsonparse.customers_orders.cus_orders[i].customer_info.p_fname+' '+jsonparse.customers_orders.cus_orders[i].customer_info.p_lname+' - MR # '+jsonparse.customers_orders.cus_orders[i].customer_info.medical_record_id+'</strong></div></td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">'+jsonparse.customers_orders.cus_orders[i].customer_info.patient_days+'</td><td class="compress-center with-border td-info-box">'+daily_rate+'</td><td class="compress-center with-border td-info-box">'+amount_due+'</td></tr>';
		 					cus_orders_html += customer_info;
		 					grand_total += amount_due;
		 				}
		 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].original_activity_typeid == 5 && is_add_respite == false) {
		 					activity_type = "Respite";
		 					activity_type_disp = "Respite";
		 					respite_count++;
		 					is_add_respite = true;
		 				}
		 				else if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].original_activity_typeid == 4 && is_add_cusmove == false) {
		 					activity_type = "CUS Move";
		 					activity_type_disp = "CUS Move";
		 					cusmove_count++;
		 					is_add_cusmove = true;
		 				} 
		 				// else {
		 				// 	activity_type = "";
		 				// }

		 				var now = new Date();
		 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date != "0000-00-00") {
		 					var now_temp = jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date.split("-");
							now = new Date(now_temp[0], now_temp[1], now_temp[2]);
						}
						var equipment_val = jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipment_value;
		 				
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
		 					tr_class = "cap-item";
		 				}

		 				var purchase = "";
		 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].categoryID == 3) {
		 					if(purcahse_h == 1) {
		 						tr_style_h = ' style="display:none;"';
		 					}
		 					purchase = jsonparse.customers_orders.cus_orders[i].customer_orders[j].purchase_price;
		 					total = jsonparse.customers_orders.cus_orders[i].customer_orders[j].equipment_value * jsonparse.customers_orders.cus_orders[i].customer_orders[j].purchase_price;
		 					grand_total += total;
		 					if(purchase == 0) {
		 						purchase = "";
		 					}
		 					if(total == 0) {
		 						total = "";
		 					}
		 					tr_class = "purchase-item";
		 				}

		 				var noncapped = "";
		 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].categoryID == 2) {
		 					if(noncap_h == 1) {
		 						tr_style_h = ' style="display:none;"';
		 					}
		 					noncapped = jsonparse.customers_orders.cus_orders[i].customer_orders[j].purchase_price;
		 					var pickdate_temp = jsonparse.customers_orders.cus_orders[i].customer_orders[j].pickup_date.split("-");
		 					equipment_val = Date.daysBetween(new Date(pickdate_temp[0], pickdate_temp[1], pickdate_temp[2]),now);
		 					total = equipment_val * jsonparse.customers_orders.cus_orders[i].customer_orders[j].purchase_price;
		 					grand_total += total;
		 					if(noncapped == 0) {
		 						noncapped = "";
		 					}
		 					if(total == 0) {
		 						total = "";
		 					}
		 					tr_class = "noncap-item";
		 					
		 				}
		 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].equip_is_package == 0 && jsonparse.customers_orders.cus_orders[i].customer_orders[j].is_package == 1) {
		 					continue;
		 				}
		 				cus_orders_html += '<tr class="'+tr_class+'"'+tr_style_h+'>';
		 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID == prev_workorder) {
		 					cus_orders_html += '<td class="compress-center with-border td-info-box">&nbsp;</td>';
		 				} else {
		 					cus_orders_html += '<td class="compress-center with-border td-info-box">'+jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID+'</td>';
		 				}
		 				prev_workorder = jsonparse.customers_orders.cus_orders[i].customer_orders[j].uniqueID;
		 				var summary_pickup_date = "";
		 				if(jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date != "0000-00-00") {
		 					summary_pickup_date = jsonparse.customers_orders.cus_orders[i].customer_orders[j].summary_pickup_date;
		 				} else {
		 					summary_pickup_date = '&nbsp;';
		 				}

		 				cus_orders_html += '<td class="compress-center with-border td-info-box">'+jsonparse.customers_orders.cus_orders[i].customer_orders[j].key_desc+'</td><td class="with-border td-info-box">'+jsonparse.customers_orders.cus_orders[i].customer_orders[j].pickup_date+'</td><td class="compress-center with-border td-info-box">'+summary_pickup_date+'</td><td class="compress-center with-border td-info-box">'+equipment_val+'</td><td class="compress-center with-border td-info-box">'+cap+'</td><td class="compress-center with-border td-info-box">'+purchase+'</td><td class="compress-center with-border td-info-box">'+noncapped+'</td><td class="compress-center with-border td-info-box">'+total+'</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box"></td><td class="compress-center with-border td-info-box">'+total+'</td>';
		 				cus_orders_html += '</tr>';

		 				if((jsonparse.customers_orders.cus_orders[i].customer_orders.length - 1) == j) {
 							console.log("nakasulod kos last row:", j);
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
			 				amount_due_grand_total = '<tr><td class="compress-center with-border td-info-box">&nbsp;</td><td class="with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box"><strong>'+activity_type_disp+'</strong></td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">TOTAL:</td><td class="compress-center with-border td-info-box">'+grand_total+'</td></tr>';
			 				cus_orders_html += amount_due_grand_total;
		 				}
 						else if(jsonparse.customers_orders.cus_orders[i].customer_orders[j+1].addressID != jsonparse.customers_orders.cus_orders[i].customer_orders[j].addressID) {
 							console.log("nakasulod kos last row:", j);
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
			 				amount_due_grand_total = '<tr><td class="compress-center with-border td-info-box">&nbsp;</td><td class="with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box"><strong>'+activity_type_disp+'</strong></td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">TOTAL:</td><td class="compress-center with-border td-info-box">'+grand_total+'</td></tr>';
			 				cus_orders_html += amount_due_grand_total;
	 					}
		 			}
		 			final_html += cus_orders_html;
		 			// final_html += cus_orders_html
		 			// var amount_due_grand_total = '<tr><td class="compress-center with-border td-info-box">&nbsp;</td><td class="with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">&nbsp;</td><td class="compress-center with-border td-info-box">TOTAL:</td><td class="compress-center with-border td-info-box">'+grand_total+'</td></tr>';
		 			// final_html += amount_due_grand_total;

		 		}

		 		order_list_tbody.append(final_html);
		 		$("#loading_more").remove();
		 		
		 		$('#load_more_patient').attr("data-current-count", currentCount);

		 		//FOR AUTOLOAD WHEN DOCUMENT IS READY
		 		var current_Count = parseInt($('#load_more_patient').attr("data-current-count"));
	        	var total_CustomerCount = parseInt($('#load_more_patient').attr("data-total-count"));
		 		if(current_Count < total_CustomerCount) {
		 			$('#load_more_patient').click();
		 		} else {
		 			$('.loaded-icon').show();
		 			$('.loader-icon').hide();
		 		}

		 		//FOR AUTOLOAD IF YOU REACH THE BOTTOM MOST OF THE PAGE
		 		onscroll = false;

		 		// order_list.append('<div class="text-center" id="load_more_content" style="display: none"><button class="btn btn-info" style="" id="load_more_patient" data-hospice-id="'+hospiceID+'" data-current-count="'+currentCount+'" data-daily-rate="'+daily_rate+'" style="text-align:center;">Load More</button></div>');
		 		
		 		
		 	});

		 	
		 });
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


	});
</script>