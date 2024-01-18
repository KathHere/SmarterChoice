	<style type="text/css">
	.modal-dialog {
		width: 1200px;
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

	.thead_style {
		font-weight: bold;
	}

	.modal-title {
		text-align: center;
		font-size: 16px !important;
	}

	.col-md-6-address {width:40%; float:left;}
	.col-md-6-address-content {width:60%; float:left;}

	@media print{
		@page {
            size: landscape;
            margin-top: 0;
            margin-bottom: 0;
        }

		#account_address_content {
			margin-left: -90px !important;
		}

        .modal-content
        {
            border:0px !important;
        }
        .modal-header
        {
            display:none !important;
        }
        .hidden-header 
        {
        	display: block !important;
        }

        .statement_letter_container {
	        display: none !important;
	    }

	    .modal {
		    position: absolute;
		    left: 0;
		    top: 0;
		    margin: 0;
		    padding: 0;
		    visibility: visible;
		    /**Remove scrollbar for printing.**/
		    overflow: visible !important;
		}
		.modal-dialog {
			/*margin-top: -50px !important;*/
		    visibility: visible !important;
		    /**Remove scrollbar for printing.**/
		    overflow: visible !important;
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

		.date_account_container {
			text-align: center;
		}

		.account_container {
			margin-left: -70px !important;
		}

		.date_container {
			margin-left: -113px !important;
		}

		.date_printed {
			margin-top: -80px;
			margin-bottom: 80px;
			color: #000;
		}
    }

    .date_printed {
    	color: #fff;
    }

	.customized-border {
		border: 0 !important;
    	border-right: 1px #000 solid !important;
	}

	.customized-border-last {
		border: 0 !important;
	}
</style>
<div class="date_printed"><?php echo date("m-d-Y"); ?></div>
<div class="row hidden-header" style="text-align: center; margin-bottom: 20px; font-size: 17px; font-weight: bold;"> Statement</div>
<div class="row" style="margin-bottom: 50px">
	<div class="col-md-4">
		<p class="logo_ahmslv" style="margin-bottom:0px; text-align: center">
			<img class="logo_ahmslv_img" src="<?php echo base_url('assets/img/smarterchoice_logo.png'); ?>" alt="" style="height:50px;width:58px;"/>
		</p>
		<h4 class="work_order_header_first" style="font-weight:bold;margin-top:0px;font-size:17px;margin-bottom:13px;text-align: center"> Advantage Home Medical Services, Inc </h4>	
	</div>
	<div class="col-md-3"></div>
	<div class="col-md-5 address-style" style="text-transform: uppercase">
		<div>
			<span>Advantage Home Medical Services, Inc</span>
		</div>
		<div>
			<span>2915 Losee Rd. Ste. 108</span>
		</div>
		<div>
			<span>North las Vegas, NV 89030</span>
		</div>
		<div>
		<!-- Ph: 702-413-5650 -->
			<span>Ph: 702-248-0056    Fax: 702-889-0059</span>
		</div>
	</div>
	
</div>

<?php 
	if (count($past_due_invoices) > 0) {
		$hospice_info = array(
			'hospiceID' => $past_due_invoices[0]['hospiceID'],
			'hospice_name' => $past_due_invoices[0]['hospice_name'],
			'hospice_address' => $past_due_invoices[0]['hospice_address'],
			'hospice_account_number' => $past_due_invoices[0]['hospice_account_number']
		);
	} else {
		$hospice_info = array(
			'hospiceID' => null,
			'hospice_name' => '',
			'hospice_address' => '',
			'hospice_account_number' => ''
		);
	}

?>
<div class="row" style="margin-bottom: 20px; text-transform: uppercase">
	<div class="col-md-7">
		<div class="statement_letter_label_wrapper">
			<label class="statement_letter_label_tag">Account Name:</label><span><?php echo $hospice_info['hospice_name']; ?></span>
		</div>
		<div class="statement_letter_label_wrapper">
			<div class="row" style="margin-right: 0px; margin-left: 0px;">
				<div class="col-md-6-address" style="font-weight: bold;">Account Address:</div>
				<div id="account_address_content" class="col-md-6-address-content" style="margin-left: -45px">
					<?php
						echo $past_due_invoices[0]['b_street'];
						if($past_due_invoices[0]['b_street'] != null && $past_due_invoices[0]['b_street'] != "") {
							echo ', ';
						}
						echo $past_due_invoices[0]['b_placenum'];
						// if($past_due_invoices[0]['b_placenum'] != null && $past_due_invoices[0]['b_placenum'] != "") {
						// 	echo ', ';
						// }
					?>
					<br>
					<?php
						echo $past_due_invoices[0]['b_city'];
						if($past_due_invoices[0]['b_city'] != null && $past_due_invoices[0]['b_city'] != "") {
							echo ', ';
						}
						echo $past_due_invoices[0]['b_state'];
						if($past_due_invoices[0]['b_state'] != null && $past_due_invoices[0]['b_state'] != "") {
							echo ', ';
						}
						echo $past_due_invoices[0]['b_postalcode'];
					?>
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="col-md-1"></div> -->
	<div class="col-md-5 date_account_container">
		<div class="statement_letter_label_wrapper date_container">
			<label class="statement_letter_label_tag">Date:</label><span><?php echo date('m/d/Y', strtotime($current_date)); ?></span>
		</div>
		<div class="statement_letter_label_wrapper account_container">
			<label class="statement_letter_label_tag">Account Number:</label><span><?php echo $hospice_info['hospice_account_number']; ?></span>
		</div>
	</div>
</div>

<table class="table bg-white b-a datatable_table_statement_bill" style="text-align: center; text-transform: uppercase; border: 1px solid #000; margin-bottom: 0px !important">
	<thead style="background-color: #f6f8f8;">
		<tr>
			<td class="thead_style" style="border-left: 1px solid #000; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;">Invoice Date</td>
			<td class="thead_style" style="border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;">Invoice Number</td>
			<td class="thead_style" style="border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;">Service Date</td>
			<td class="thead_style" style="border-top: 1px solid #000;border-right: 1px solid #000; border-bottom: 1px solid #000;">Charges</td>
			<td class="thead_style" style="border-top: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;">Credits</td>
			<td class="thead_style" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">Total Balance</td>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach($past_due_invoices as $past_due_invoice) {
		?>
				<tr>
					<td class="customized-border"><?php echo date('m/d/Y', strtotime($past_due_invoice['invoice_date'])); ?></td>
					<td class="customized-border"><?php echo substr($past_due_invoice['statement_no'],3, 10); ?></td>
					<td class="customized-border">
						<?php echo date('m/d/Y', strtotime($past_due_invoice['service_date_from'])).' - '.date('m/d/Y', strtotime($past_due_invoice['service_date_to'])); ?>
					</td>
					<td class="customized-border">
						<?php 
							$total_balance_due = 0;
							$total_balance_due += $past_due_invoice['total'];
							$total_balance_due += $past_due_invoice['non_cap'];
							$total_balance_due += $past_due_invoice['purchase_item'];
							$total_balance_due -= $invoices_reconciliation[$key]['credit'];     //Deduct
							$total_balance_due += $invoices_reconciliation[$key]['owe'];        //Add
							echo number_format((float)$total_balance_due, 2, '.', '');
						?>
					</td>
					<td class="customized-border">
						<?php 
						if($past_due_invoice['credit'] != 0) {
							echo number_format((float)$past_due_invoice['credit'], 2, '.', '');
						}
						?>
					</td>
					<td class="customized-border-last">
						<?php
						$total_balance = $total_balance_due+$past_due_invoice['credit'];
						if($total_balance != 0) {
							echo number_format((float)$total_balance, 2, '.', '');
						}
						?>
					</td>
				</tr>
		<?php
			}
		?>

		<?php 
			for ($loop_key=1; $loop_key <= 15 - count($past_due_invoices); $loop_key++) {
		?>
				<tr>
					<td class="customized-border">&nbsp;</td>
					<td class="customized-border">&nbsp;</td>
					<td class="customized-border">&nbsp;</td>
					<td class="customized-border">&nbsp;</td>
					<td class="customized-border">&nbsp;</td>
					<td class="customized-border-last">&nbsp;</td>
				</tr>
		<?php
			}
		?>
	</tbody>
</table>

<input type="hidden" class="hospice_selected" value="<?php echo $hospice_info['hospiceID']; ?>">

<table class="table bg-white b-a" style="text-align: center; text-transform: uppercase; margin-bottom: 0px !important; border: 1px solid #000;">
	<thead style="background-color: #f6f8f8;">
  		<tr>
  			<th style="text-align: center; border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000;">Current</th>
  			<th style="text-align: center; border-right: 1px solid #000; border-bottom: 1px solid #000;">Past Due 1 - 30 Days</th>
  			<th style="text-align: center; border-right: 1px solid #000; border-bottom: 1px solid #000;">Past Due 31 - 60 Days</th>
  			<th style="text-align: center; border-right: 1px solid #000; border-bottom: 1px solid #000;">Past Due 61 - 90 Days</th>
  			<th style="text-align: center; border-right: 1px solid #000; border-bottom: 1px solid #000;">Past Due 91</th>
  			<th style="text-align: center; border-bottom: 1px solid #000;">Balance Due</th>
  		</tr>
	</thead>
	<tbody>
			<tr>
				<td class="customized-border"><span id="currentheader"><?php echo number_format((float)$current, 2, '.', ''); ?></span></td>
				<td class="customized-border"><span id="past_due_1_30_header"><?php echo number_format((float)$past_due_1_30, 2, '.', ''); ?></span></td>
				<td class="customized-border"><span id="past_due_31_60_header"><?php echo number_format((float)$past_due_31_60, 2, '.', ''); ?></span></td>
				<td class="customized-border"><span id="past_due_61_90_header"><?php echo number_format((float)$past_due_61_90, 2, '.', ''); ?></span></td>
				<td class="customized-border"><span id="past_due_91_header"><?php echo number_format((float)$past_due_91, 2, '.', ''); ?></span></td>
				<td class="customized-border-last"><span id="balance_due_header"><?php echo number_format((float)$balance_due, 2, '.', ''); ?></span></td>
			</tr>
	</tbody>
</table>

<script type="text/javascript">

    // $(document).ready(function(){

	// 		var hospiceID = $('.hospice_selected').val();
	// 		console.log('hospiceID', hospiceID);

	// 		$.get(base_url+'billing_statement/statement_bill_header/'+hospiceID, function(response){
	// 			var obj = JSON.parse(response);
	// 			console.log('responseheader', obj);
	// 			$("#currentheader").html(parseFloat(obj.current).toFixed(2));
	// 			$("#past_due_1_30_header").html(parseFloat(obj.past_due_1_30).toFixed(2));
	// 			$("#past_due_31_60_header").html(parseFloat(obj.past_due_31_60).toFixed(2));
	// 			$("#past_due_61_90_header").html(parseFloat(obj.past_due_61_90).toFixed(2));
	// 			$("#past_due_91_header").html(parseFloat(obj.past_due_91).toFixed(2));
	// 			$("#balance_due_header").html(parseFloat(obj.balance_due).toFixed(2));
	// 		});

    // });

</script>