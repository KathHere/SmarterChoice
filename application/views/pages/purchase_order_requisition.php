<style type="text/css">

	.print_order_requisition
	{
		padding-right: 32px !important;
		padding-left: 30px !important;
		padding-top: 30px !important;
		margin-bottom: 10px;
	}

	.order_requisition_div
	{

	}

	.all_items_order_req
	{
		margin-top:10px;
		border-right: 1px solid #eaeeea !important;
		border-left: 1px solid #eaeeea !important;
	}

	.order_requisition_details_row
	{
		margin-right: 0px !important;
		font-size: 15px !important;
	}

	.order_requisition_details_row_v2
	{
		margin-right: 0px !important;
		margin-left: 0px !important;
		font-size: 15px !important;
		text-align: center;
	}

	.order_requisition_item_details
	{
		border:1px solid rgba(8, 8, 8, 0.62);
		height:70px;
		padding-left: 2px;
		padding-right: 2px;
	}

	.order_requisition_item_details_v2
	{
		border:1px solid rgba(8, 8, 8, 0.62);
		min-height:40px;
		padding-left: 2px;
		padding-right: 2px;
	}

	.order_requisition_form_textarea_col1
	{
		text-align:center;
	    height: 47px;
	    margin-top: -2px;
	    margin-left: -2px;
	    width: 76px;
	    border: 0px;
	    background-color: #fff;
	    resize: none !important;
	    box-shadow:inset 0 0 0px rgba(88, 102, 110, 0.13);
	}
	.order_requisition_form_textarea_col1:focus{
	   outline:none !important;
	   border:0px !important;
	}

	.order_requisition_form_textarea_col2
	{
		text-align:center;
	    height: 47px;
	    margin-top: -2px;
	    margin-left: -2px;
	    width: 98px;
	    border: 0px;
	    background-color: #fff;
	    resize: none !important;
	    box-shadow:inset 0 0 0px rgba(88, 102, 110, 0.13);
	}
	.order_requisition_form_textarea_col2:focus{
	   outline:none !important;
	   border:0px !important;
	}

	.order_requisition_form_textarea_col4
	{
		text-align:center;
	    height: 47px;
	    margin-top: -2px;
	    margin-left: -2px;
	    width: 254px;
	    border: 0px;
	    background-color: #fff;
	    resize: none !important;
	    box-shadow:inset 0 0 0px rgba(88, 102, 110, 0.13);
	}
	.order_requisition_form_textarea_col4:focus{
	   outline:none !important;
	   border:0px !important;
	}

	.person_taking_order_input{
	    background-color: #fff !important;
	    width: 200px;
	    border-right: 0px;
	    border-left: 0px;
	    border-top: 0px;
	    border-bottom: 1px solid #000;
	    box-shadow: none !important;
	    outline-style:none !important;
	}

	.person_taking_order_input:focus{
	   outline:none !important;
	   border:3px !important;
	}

	.confirmation_no_input{
	    background-color: #fff !important;
	    width: 205px;
	    border-right: 0px;
	    border-left: 0px;
	    border-top: 0px;
	    border-bottom: 1px solid #000;
	    box-shadow: none !important;
	}

	.confirmation_no_input:focus{
	   outline:none !important;
	   border:3px !important;
	}

	.vendor_rep_no_input{
	    background-color: #fff !important;
	    width: 194px;
	    border-right: 0px;
	    border-left: 0px;
	    border-top: 0px;
	    border-bottom: 1px solid #000;
	    box-shadow: none !important;
	}

	.vendor_rep_no_input:focus{
	   outline:none !important;
	   border:3px !important;
	}

	.purchase_order_requisition_row
	{
		margin-left: 0px!important;
	}

	.btn-draft{
		color: #ffffff;
		background-color: #ecbc41;
		border-color: #ecbc41;
		margin-right: 9px;
	}
	.btn-draft:hover{
		color: #ffffff;
		background-color: #e4b53d;
	}
</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  <h1 class="m-n font-thin h3">Purchase Order Requisition</h1>
</div>

<div class="wrapper-md">
	<input type="hidden" class="current_selected_vendor_id" value="0">
	<div class="panel panel-default" style="overflow-x:scroll;">
		<div class="ng-scope" style="min-width: 1300px !important;">
		    <div class="panel-body" style="padding-left:0px;padding-right:0px;">
		    	<div class="row purchase_order_requisition_row">
		    		<div class="col-xs-4 col-sm-4 col-md-4 hidden-print all_items_order_req_div">

						<table class="table bg-white b-a table-hover all_items_order_req">
						  	<thead style="background-color:rgba(97, 101, 115, 0.05);">
						    	<tr>
						    		<th style="width:9% ;"></th>
						    		<th style="width:27% ;">Company Item No.</th>
						    		<th style="width:25% ;">Reorder No.</th>
						      		<th style="width:39% ;">Item Description</th>
						    	</tr>
						    </thead>
						    <tbody class="company_item_list_tbody">
				    			<tr>
							    	<td colspan="4" style="text-align:center;">
							    		No Items
							    	</td>
							    </tr>
						    </tbody>
						</table>

		   	 		</div>
		   	 		<?php
					    echo form_open("",array("class"=>"purchase_order_req_form"))
					?>
		   	 		<div class="col-xs-8 col-sm-8 col-md-8 order_requisition_parent_div" style="padding-left:0px;">
		   	 			<div class="form-group order_requisition_div">
			   	 			<h4 class="order_req_first_header" style="text-align:center;"><strong> Advantage Home Medical Services </strong></h4>
			   	 			<p class="order_req_second_header" style="text-align:center;font-size:16px;margin-top:-10px;font-weight:bold;"> Purchase Order Requisition </p>

			   	 			<div class="row order_requisition_details_row" style="margin-top:30px;margin-bottom:-10px;">
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="">
				   	 				<strong>Date:</strong> <?php echo date("m/d/Y"); ?>
				   	 				<input type="hidden" name="order_req_date" value="<?php echo date("m/d/Y"); ?>">
				   	 			</div>
				   	 			<div class="col-xs-6 col-xs-6 col-sm-6 col-md-6" style="padding-right:0px;">
									<div class="col-xs-2 col-sm-2 col-md-2" style="padding-left: 0px">
										<strong>Location:</strong>
									</div>
									<div class="col-xs-10 col-sm-10 col-md-10">
										<?php
											$location = get_login_location($this->session->userdata('user_location'));
										?>
										<span class="location_span">
											<?php
												if ($this->session->userdata('user_location') != 0) {
													echo $location['user_city'].", ".$location['user_state'];
											?>
												<input type="hidden" class="location" name="location" value="<?php echo $this->session->userdata('user_location'); ?>">
											<?php
												} else {
											?>

													<select name="location" class="form-control location m-b select2-ready" id="">		
														<option value="">- Please choose -</option>
													<?php
														$service_locations = get_service_location();
														foreach ($service_locations as $value) {
															?>	
													<option value="<?php echo $value['location_id']; ?>">
														<?php echo $value['location_name']; ?>, <?php echo $value['service_location_id']; ?>
													</option>
													<?php
														}
													?>
													</select>
											<?php
												}
											?>
										</span>
									</div>
				   	 				
				   	 			</div>
				   	 		</div>
				   	 		<div class="row order_requisition_details_row" style="margin-top:6px;">
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="padding-left:0px;padding-right:0px;margin-top: 5px;">
				   	 				<div class="col-xs-2 col-sm-2 col-md-2" style="margin-top:4px;">
				   	 					<strong>Vendor:</strong>
				   	 				</div>
				   	 				<div class="col-xs-10 col-sm-10 col-md-10" style="">
					   	 				<select name="item_vendor" class="form-control choose_item_vendor_order_req" id="choose_item_vendor_id_order_req" style="height:30px;width:270px;">
			                                <option value=""> [--Choose Vendor--] </option>
			                                <?php
			                                    if(!empty($vendor_list))
			                                    {
			                                        foreach ($vendor_list as $key => $value)
			                                        {
			                                ?>
			                                            <option value="<?php echo $value['vendor_id']; ?>"> <?php echo $value['vendor_name']; ?> </option>
			                                <?php
			                                        }
			                                    }
			                                ?>
			                            </select>
			                            <input type="hidden" class="viewed_vendor_id" value="">
			                        </div>
				   	 			</div>
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="margin-top:9px;">
				   	 				<strong>Vendor Phone:</strong> <span class="vendor_phone_no"> </span>
				   	 				<input type="hidden" class="vendor_phone_no_input" name="vendor_phone_no" value="">
				   	 			</div>
				   	 		</div>
				   	 		<div class="row order_requisition_details_row" style="margin-top:5px;">
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="">
				   	 				<strong>Account No. </strong> <span class="vendor_account_no"> </span>
				   	 				<input type="hidden" class="vendor_account_no_input" name="vendor_account_no" value="">
				   	 			</div>
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="">
				   	 				<?php
				   	 					$purchase_order_no = strtotime(date('Y-m-d H:i:s'));
				   	 				?>
				   	 				<strong>Purchase Order No.:</strong> <?php echo substr($purchase_order_no,3,10); ?>
				   	 				<input type="hidden" name="purchase_order_no" class="purchase_order_no" value="<?php echo $purchase_order_no; ?>">
				   	 			</div>
				   	 		</div>
				   	 		<div class="row order_requisition_details_row" style="margin-top:8px;">
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="">
				   	 				<strong>Vendor Rep. Taking Order:</strong> <input type="text" class="vendor_rep_no_input" name="vendor_rep_taking_order_input" required>
				   	 			</div>
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="">
				   	 				<strong>Confirmation No.:</strong> <input type="text" class="confirmation_no_input" name="confirmation_no_input" required>
				   	 			</div>
				   	 		</div>
				   	 		<div class="row order_requisition_details_row" style="margin-top:8px;">
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="">
				   	 				<strong>Person Placing Order:</strong> <?php echo $this->session->userdata('lastname'); ?>, <?php echo $this->session->userdata('firstname'); ?>
				   	 				<input type="hidden" name="person_placing_order" value="<?php echo $this->session->userdata('lastname'); ?>, <?php echo $this->session->userdata('firstname'); ?>">
				   	 			</div>
				   	 		</div>
				   	 		<div class="row order_requisition_details_row_v2" style="margin-top:15px !important;margin-right:5px !important;">
				   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details company_item_no_col" style="border-right:0px !important;padding-top:10px;width:78px;">
				   	 				<p> Company</p>
				   	 				<p style="margin-top:-10px;"> Item No. </p>
				   	 			</div>
				   	 			<div class="col-xs-4 col-sm-4 col-md-4 order_requisition_item_details item_description_col" style="border-right:0px !important;padding-top:20px;width:255px;">
				   	 				Item Description
				   	 			</div>
				   	 			<div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details reorder_no_col" style="border-right:0px !important;padding-top:20px;width:100px;">
				   	 				Re Order No.
				   	 			</div>
				   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details unit_of_measure_col" style="border-right:0px !important;padding-top:10px;width:78px;">
				   	 				Unit of Measure
				   	 			</div>
				   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details item_cost_col" style="border-right:0px !important;padding-top:20px;width:78px;">
				   	 				Cost
				   	 			</div>
				   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details par_level_col" style="border-right:0px !important;padding-top:10px;width:78px;">
				   	 				<p> Par</p>
				   	 				<p style="margin-top:-10px;"> Level </p>
				   	 			</div>
				   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details qty_ordered_col" style="border-right:0px !important;padding-top:10px;width:78px;">
				   	 				<p> Qty.</p>
				   	 				<p style="margin-top:-10px;"> Ordered </p>
				   	 			</div>
				   	 			<div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details total_cost_col" style="padding-top:20px;width:100px;">
				   	 				Total Cost
				   	 			</div>
				   	 		</div>

				   	 		<?php
				   	 			for ($i = 1; $i <= 42; ++$i) {
                                    if ($i <= 14) {
                            ?>
							   	 		<div class="row order_requisition_details_row_v2 order_requisition_item_box_<?php echo $i; ?>" style="margin-right:5px !important;" >
							   	 			<input type="hidden" name="order_req_item_id_<?php  echo $i; ?>" class="selected_item_id selected_item_id_row_<?php  echo $i; ?>" value="">
							   	 			<div class="col-xs-2 col-sm-1 col-md-1 order_requisition_item_details_v2 company_item_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:78px;">
							   	 				<textarea name="order_req_company_id_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="1" class="company_item_no_textarea order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_company_id_<?php  echo $i; ?>"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-3 col-sm-4 col-md-4 order_requisition_item_details_v2 item_description_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:255px;">
							   	 				<textarea name="order_req_item_desc_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="2" class="company_item_desc_textarea order_requisition_form_textarea_col4 order_req_<?php  echo $i; ?> order_req_item_desc_<?php  echo $i; ?>" style="display:inline-block !important;vertical-align:middle !important;text-transform: uppercase;"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 reorder_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:100px;">
							   	 				<textarea name="order_req_reorder_no_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="3" class="order_requisition_form_textarea_col2 order_req_<?php  echo $i; ?> order_req_reorder_no_<?php  echo $i; ?> " style="text-transform: uppercase;"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 unit_of_measure_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:78px;">
							   	 				<textarea name="order_req_unit_of_measure_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="4" class="company_item_unit_of_measure order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_unit_of_measure_<?php  echo $i; ?>"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 item_cost_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:78px;">
							   	 				<textarea name="order_req_cost_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="5" class="order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_cost_<?php  echo $i; ?>"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 par_level_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:78px;">
							   	 				<textarea name="order_req_par_level_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="6" class="company_par_level_textarea order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_par_level_<?php  echo $i; ?>" readonly> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 qty_ordered_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:78px;">
							   	 				<textarea name="order_req_quantity_ordered_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="7" class="company_item_quantity_textarea order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_quantity_ordered_<?php  echo $i; ?>"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 total_cost_col" style="height:48px;border-top:0px !important;padding-top:2px;width:100px;">
							   	 				<textarea name="order_req_total_cost_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="8" class="item_total_cost order_requisition_form_textarea_col2 order_req_<?php  echo $i; ?> order_req_total_cost_<?php  echo $i; ?>" readonly> </textarea>
							   	 			</div>
							   	 		</div>
				   	 		<?php
                                	} else {
                            ?>

		                            	<div class="row order_requisition_details_row_v2 order_requisition_item_box_<?php echo $i; ?>" style="margin-right:5px !important; display: none;" >
							   	 			<input type="hidden" name="order_req_item_id_<?php  echo $i; ?>" class="selected_item_id selected_item_id_row_<?php  echo $i; ?>" value="">
							   	 			<div class="col-xs-2 col-sm-1 col-md-1 order_requisition_item_details_v2 company_item_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:78px;">
							   	 				<textarea name="order_req_company_id_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="1" class="company_item_no_textarea order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_company_id_<?php  echo $i; ?>"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-3 col-sm-4 col-md-4 order_requisition_item_details_v2 item_description_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:255px;">
							   	 				<textarea name="order_req_item_desc_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="2" class="company_item_desc_textarea order_requisition_form_textarea_col4 order_req_<?php  echo $i; ?> order_req_item_desc_<?php  echo $i; ?>" style="display:inline-block !important;vertical-align:middle !important;text-transform: uppercase;"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 reorder_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:100px;">
							   	 				<textarea name="order_req_reorder_no_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="3" class="order_requisition_form_textarea_col2 order_req_<?php  echo $i; ?> order_req_reorder_no_<?php  echo $i; ?> " style="text-transform: uppercase;"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 unit_of_measure_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:78px;">
							   	 				<textarea name="order_req_unit_of_measure_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="4" class="company_item_unit_of_measure order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_unit_of_measure_<?php  echo $i; ?>"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 item_cost_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:78px;">
							   	 				<textarea name="order_req_cost_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="5" class="order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_cost_<?php  echo $i; ?>"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 par_level_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:78px;">
							   	 				<textarea name="order_req_par_level_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="6" class="company_par_level_textarea order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_par_level_<?php  echo $i; ?>" readonly> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 qty_ordered_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;width:78px;">
							   	 				<textarea name="order_req_quantity_ordered_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="7" class="company_item_quantity_textarea order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_quantity_ordered_<?php  echo $i; ?>"> </textarea>
							   	 			</div>
							   	 			<div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 total_cost_col" style="height:48px;border-top:0px !important;padding-top:2px;width:100px;">
							   	 				<textarea name="order_req_total_cost_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="8" class="item_total_cost order_requisition_form_textarea_col2 order_req_<?php  echo $i; ?> order_req_total_cost_<?php  echo $i; ?>" readonly> </textarea>
							   	 			</div>
							   	 		</div>
							<?php
                                    }
                                }
                            ?>

				   	 		<div class="row order_requisition_details_row" style="width:875px;margin-top:15px !important;">
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="height:22px;">
				   	 			</div>
				   	 			<div class="col-xs-6 col-sm-6 col-md-6 " style="text-align:right;padding:0;">
				   	 				<div class="col-xs-9 col-sm-9 col-md-9">
				   	 					<strong>Amount:</strong>
				   	 				</div>
				   	 				<div class="col-xs-3 col-sm-3 col-md-3">
				   	 					<input type="text" class="form-control order_req_total_amount" name="order_req_total_amount" value="0.00"  style="text-align:right;background-color:#f7f7f7;" readonly>
				   	 				</div>
				   	 			</div>
				   	 		</div>
				   	 		<div class="row order_requisition_details_row" style="width:875px;margin-top:5px !important;">
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="height:22px;">
				   	 			</div>
				   	 			<div class="col-xs-6 col-sm-6 col-md-6 " style="text-align:right;padding:0;">
				   	 				<div class="col-xs-9 col-sm-9 col-md-9">
				   	 					<strong>Shipping Cost:</strong>
				   	 				</div>
				   	 				<div class="col-xs-3 col-sm-3 col-md-3">
				   	 					<input type="text" class="form-control order_req_shipping_cost" name="order_req_shipping_cost" value="0.00" style="text-align:right;" >
				   	 				</div>
				   	 			</div>
				   	 		</div>
				   	 		<div class="row order_requisition_details_row" style="width:875px;margin-top:5px !important;">
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="height:22px;">
				   	 			</div>
				   	 			<div class="col-xs-6 col-sm-6 col-md-6 " style="text-align:right;padding:0;">
				   	 				<div class="col-xs-9 col-sm-9 col-md-9">
				   	 					<strong>Total:</strong>
				   	 				</div>
				   	 				<div class="col-xs-3 col-sm-3 col-md-3">
				   	 					<input type="text" class="form-control order_req_grand_total_amount" name="order_req_grand_total_amount" value="0.00"  style="text-align:right;background-color:#f7f7f7;" readonly>
				   	 				</div>
				   	 			</div>
				   	 		</div>
				   	 		<div class="row order_requisition_details_row" style="margin-top:15px !important;margin-bottom:10px !important;">
				   	 			<div class="col-sm-12 col-md-6" style="">
				   	 				<strong>Employee Signature:</strong> &nbsp; <span> <hr style=" width: 220px; border-color: #292626; margin-left: 145px; margin-top: -2px;"/> </span>
				   	 			</div>
				   	 		</div>
			   	 		</div>
		   	 		</div>
		    	</div>
		    	<input type="hidden" id="item_count" value="0">
		    	<div class="row print_order_requisition hidden-print">
		    		<div>
		    			<?php
	                      if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') {
	                    ?>
				    		<button type="button" class="btn btn-success pull-right btn-save-order-req" style=""> Submit </button>
				    	<?php }?>

		    		</div>
		    		<div>
			    		<button type="button" class="btn btn-draft pull-right btn-draft-order-req" style=""> Save Order as Draft </button>
		    		</div>
		    	</div>
		    	<?php echo form_close() ;?>
		    </div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(){

		$('.input_tobe_masked').mask("(999) 999-9999");

		$('.edit_vendor_date').datepicker({
        	dateFormat: 'mm/dd/yy'
  	   	});

		$('body').on('change','.choose_item_vendor_order_req',function(){
			var old_select_value = $("body").find(".current_selected_vendor_id").val();
			var value = $(this).val();
			var all_items_order_req_div = $("body").find(".all_items_order_req_div");
			var vendor_account_no_container = $("body").find(".vendor_account_no");
			var temp = "";
			var table = "";
			var search_count = 0;
			var vendor_account_no = "";
			var vendor_phone_no = "";
			var sign_empty = 0;

			for (i = 1; i <= 14; i++) {
				var data = $("body").find(".order_req_company_id_"+i).val();

				if(data != " ")
			    {
			    	sign_empty = 1;
			    }
			}

			if(value.length == 0)
			{
				if(sign_empty == 1)
				{
					jConfirm("You did not choose a vendor. Confirm action?","Reminder",function(response){
						if(response)
		    			{
		    				$("body").find(".current_selected_vendor_id").val(value);
		    				if(value.length > 0)
							{
								$.post(base_url+"inventory/get_vedor_item_list/"+value,"", function(response){
									var obj = $.parseJSON(response);

									all_items_order_req_div.html("");
									table += '<table class="table bg-white b-a table-hover all_items_order_req">'+
											  	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
											    	'<tr>'+
											    		'<th style="width:9% ;"></th>'+
											    		'<th style="width:27% ;">Company Item No.</th>'+
											    		'<th style="width:25% ;">Reorder No.</th>'+
											      		'<th style="width:39% ;">Item Description</th>'+
											    	'</tr>'+
											    '</thead>'+
									    		'<tbody class="company_item_list_tbody">'+
											    '</tbody>'+
											'</table>';
									all_items_order_req_div.html(table);

									var tbody = $("body").find(".all_items_order_req").find(".company_item_list_tbody");

									for(var val in obj.vendor_items)
						  			{
						  				var inactive_class = "";
						  				var inactive_style = "";
						  				if(obj.vendor_items[val].item_active_sign == 0)
						  				{
						  					inactive_class = "inactive_item";
						  					inactive_style = "background-color:#b9b9b929;";
						  				}
						  				else
						  				{
						  					inactive_class = "item_id_checkbox";
						  					inactive_style = "background-color:#fff;";
						  				}
						  				search_count++;
						  				temp += "<tr style='"+inactive_style+"'>"+
					                    			// "<td><input type='checkbox' name='item_id' class='form-control "+inactive_class+"' data-reorderno='"+obj.vendor_items[val].item_reorder_no+"' data-item-desc='"+obj.vendor_items[val].item_description+"' data-company-itemno='"+obj.vendor_items[val].company_item_no+"' data-par-level='"+obj.vendor_items[val].item_par_level+"' value='"+obj.vendor_items[val].item_id+"'/></td>"+
					                    			"<td><label class='i-checks'><input type='checkbox' name='item_id' class='form-control "+inactive_class+"' data-reorderno='"+obj.vendor_items[val].item_reorder_no+"' data-item-desc='"+obj.vendor_items[val].item_description+"' data-company-itemno='"+obj.vendor_items[val].company_item_no+"' data-par-level='"+obj.vendor_items[val].item_par_level+"' value='"+obj.vendor_items[val].item_id+"'/><i></i></label></td>"+
					                    			"<td>"+obj.vendor_items[val].company_item_no+"</td>"+
					                    			"<td>"+obj.vendor_items[val].item_reorder_no+"</td>"+
					                    			"<td>"+obj.vendor_items[val].item_description+"</td>"+
					                    		"</tr>";
						  			}
						  			if(search_count == 0)
						  			{
						  				temp += '<tr>'+
											    	'<td colspan="4" style="text-align:center;">No Items</td>'+
											    '</tr>';
						  			}
						  			tbody.html(temp);

						  			if(search_count != 0)
						  			{
						  				$('.all_items_order_req').DataTable({
						        			"order": [[ 2, "asc" ]],
										    "bInfo": false
						    			});

						    			$("body").find(".dataTables_wrapper").find(".bootstrap-dt-container").find(".paging_simple_numbers").parent(".col-sm-12").removeClass("col-md-6");
						  			}

						  			vendor_account_no = obj.vendor_details.vendor_acct_no;
						  			vendor_account_no_container.html(vendor_account_no);
						  			vendor_phone_no = obj.vendor_details.vendor_phone_no;
						  			$("body").find(".vendor_account_no_input").val(vendor_account_no);
						  			$("body").find(".vendor_phone_no").html(vendor_phone_no);
						  			$("body").find(".vendor_phone_no_input").val(vendor_phone_no);
						  			$("body").find(".viewed_vendor_id").val(value);
								});
							}
							else
							{
								all_items_order_req_div.html("");
								table += '<table class="table bg-white b-a table-hover all_items_order_req">'+
										  	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
										    	'<tr>'+
										    		'<th style="width:9% ;"></th>'+
										    		'<th style="width:27% ;">Company Item No.</th>'+
										    		'<th style="width:25% ;">Reorder No.</th>'+
										      		'<th style="width:39% ;">Item Description</th>'+
										    	'</tr>'+
										    '</thead>'+
								    		'<tbody class="company_item_list_tbody">'+
										    '</tbody>'+
										'</table>';
								all_items_order_req_div.html(table);
								var tbody = $("body").find(".all_items_order_req").find(".company_item_list_tbody");

								temp += '<tr>'+
							    	'<td colspan="4" style="text-align:center;">No Items</td>'+
							    '</tr>';
								tbody.html(temp);
						  		vendor_account_no_container.html(vendor_account_no);
						  		$("body").find(".vendor_account_no_input").val(vendor_account_no);
						  		$("body").find(".vendor_phone_no").html(vendor_phone_no);
						  		$("body").find(".vendor_phone_no_input").val(vendor_phone_no);
						  		$("body").find(".viewed_vendor_id").val(value);
							}

							for (i = 1; i <= 14; i++) {
		      					$("body").find(".order_req_company_id_"+i).val(" ");
					            $("body").find(".order_req_item_desc_"+i).val(" ");
								$("body").find(".order_req_reorder_no_"+i).val(" ");
								$("body").find(".order_req_unit_of_measure_"+i).val(" ");
								$("body").find(".order_req_cost_"+i).val(" ");
								$("body").find(".order_req_quantity_ordered_"+i).val(" ");
								$("body").find(".order_req_par_level_"+i).val(" ");
								$("body").find(".order_req_total_cost_"+i).val("");
								$("body").find(".selected_item_id_row_"+i).val("");

								// CSS
								$("body").find(".order_req_company_id_"+i).css('background-color','#fff');
					            $("body").find(".order_req_item_desc_"+i).css('background-color','#fff');
								$("body").find(".order_req_reorder_no_"+i).css('background-color','#fff');
								$("body").find(".order_req_unit_of_measure_"+i).css('background-color','#fff');
								$("body").find(".order_req_cost_"+i).css('background-color','#fff');
								$("body").find(".order_req_par_level_"+i).css('background-color','#fff');
								$("body").find(".order_req_total_cost_"+i).css('background-color','#fff');
								$("body").find(".selected_item_id_row_"+i).css('background-color','#fff');
								$("body").find(".order_req_quantity_ordered_"+i).css('background-color','#fff');
			      			}
		    			}
		    			else
		    			{
		    				$("body").find("#choose_item_vendor_id_order_req").val(old_select_value);
		    				$('#choose_item_vendor_id_order_req option[value='+old_select_value+']').attr('selected',true);
		    			}
		    		});
				}
				else
				{
					$("body").find(".current_selected_vendor_id").val(value);
					if(value.length > 0)
					{
						$.post(base_url+"inventory/get_vedor_item_list/"+value,"", function(response){
							var obj = $.parseJSON(response);

							all_items_order_req_div.html("");
							table += '<table class="table bg-white b-a table-hover all_items_order_req">'+
									  	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
									    	'<tr>'+
									    		'<th style="width:9% ;"></th>'+
									    		'<th style="width:27% ;">Company Item No.</th>'+
									    		'<th style="width:25% ;">Reorder No.</th>'+
									      		'<th style="width:39% ;">Item Description</th>'+
									    	'</tr>'+
									    '</thead>'+
							    		'<tbody class="company_item_list_tbody">'+
									    '</tbody>'+
									'</table>';
							all_items_order_req_div.html(table);

							var tbody = $("body").find(".all_items_order_req").find(".company_item_list_tbody");

							for(var val in obj.vendor_items)
				  			{
				  				var inactive_class = "";
				  				var inactive_style = "";
				  				if(obj.vendor_items[val].item_active_sign == 0)
				  				{
				  					inactive_class = "inactive_item";
				  					inactive_style = "background-color:#b9b9b929;";
				  				}
				  				else
				  				{
				  					inactive_class = "item_id_checkbox";
				  					inactive_style = "background-color:#fff;";
				  				}
				  				search_count++;
				  				temp += "<tr style='"+inactive_style+"'>"+
			                    			// "<td><input type='checkbox' name='item_id' class='form-control "+inactive_class+"' data-reorderno='"+obj.vendor_items[val].item_reorder_no+"' data-item-desc='"+obj.vendor_items[val].item_description+"' data-company-itemno='"+obj.vendor_items[val].company_item_no+"' data-par-level='"+obj.vendor_items[val].item_par_level+"' value='"+obj.vendor_items[val].item_id+"'/></td>"+
			                    			"<td><label class='i-checks'><input type='checkbox' name='item_id' class='form-control "+inactive_class+"' data-reorderno='"+obj.vendor_items[val].item_reorder_no+"' data-item-desc='"+obj.vendor_items[val].item_description+"' data-company-itemno='"+obj.vendor_items[val].company_item_no+"' data-par-level='"+obj.vendor_items[val].item_par_level+"' value='"+obj.vendor_items[val].item_id+"'/><i></i></label> </td>"+
			                    			"<td>"+obj.vendor_items[val].company_item_no+"</td>"+
			                    			"<td>"+obj.vendor_items[val].item_reorder_no+"</td>"+
			                    			"<td>"+obj.vendor_items[val].item_description+"</td>"+
			                    		"</tr>";
				  			}
				  			if(search_count == 0)
				  			{
				  				temp += '<tr>'+
									    	'<td colspan="4" style="text-align:center;">No Items</td>'+
									    '</tr>';
				  			}
				  			tbody.html(temp);

				  			if(search_count != 0)
				  			{
				  				$('.all_items_order_req').DataTable({
				        			"order": [[ 2, "asc" ]],
								    "bInfo": false
				    			});

				    			$("body").find(".dataTables_wrapper").find(".bootstrap-dt-container").find(".paging_simple_numbers").parent(".col-sm-12").removeClass("col-md-6");
				  			}

				  			vendor_account_no = obj.vendor_details.vendor_acct_no;
				  			vendor_account_no_container.html(vendor_account_no);
				  			vendor_phone_no = obj.vendor_details.vendor_phone_no;
				  			$("body").find(".vendor_account_no_input").val(vendor_account_no);
				  			$("body").find(".vendor_phone_no").html(vendor_phone_no);
				  			$("body").find(".vendor_phone_no_input").val(vendor_phone_no);
				  			$("body").find(".viewed_vendor_id").val(value);
						});
					}
					else
					{
						all_items_order_req_div.html("");
						table += '<table class="table bg-white b-a table-hover all_items_order_req">'+
								  	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
								    	'<tr>'+
								    		'<th style="width:9% ;"></th>'+
								    		'<th style="width:27% ;">Company Item No.</th>'+
								    		'<th style="width:25% ;">Reorder No.</th>'+
								      		'<th style="width:39% ;">Item Description</th>'+
								    	'</tr>'+
								    '</thead>'+
						    		'<tbody class="company_item_list_tbody">'+
								    '</tbody>'+
								'</table>';
						all_items_order_req_div.html(table);
						var tbody = $("body").find(".all_items_order_req").find(".company_item_list_tbody");

						temp += '<tr>'+
					    	'<td colspan="4" style="text-align:center;">No Items</td>'+
					    '</tr>';
						tbody.html(temp);
				  		vendor_account_no_container.html(vendor_account_no);
				  		$("body").find(".vendor_account_no_input").val(vendor_account_no);
				  		$("body").find(".vendor_phone_no").html(vendor_phone_no);
				  		$("body").find(".vendor_phone_no_input").val(vendor_phone_no);
				  		$("body").find(".viewed_vendor_id").val(value);
					}
				}
			}
			else
			{
				if(sign_empty == 1)
				{
					jConfirm("Your data will be discarded if you choose to select another vendor. Confirm action?","Reminder",function(response){
						if(response)
		    			{
		    				$("body").find(".current_selected_vendor_id").val(value);
		    				if(value.length > 0)
							{
								$.post(base_url+"inventory/get_vedor_item_list/"+value,"", function(response){
									var obj = $.parseJSON(response);

									all_items_order_req_div.html("");
									table += '<table class="table bg-white b-a table-hover all_items_order_req">'+
											  	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
											    	'<tr>'+
											    		'<th style="width:9% ;"></th>'+
											    		'<th style="width:27% ;">Company Item No.</th>'+
											    		'<th style="width:25% ;">Reorder No.</th>'+
											      		'<th style="width:39% ;">Item Description</th>'+
											    	'</tr>'+
											    '</thead>'+
									    		'<tbody class="company_item_list_tbody">'+
											    '</tbody>'+
											'</table>';
									all_items_order_req_div.html(table);

									var tbody = $("body").find(".all_items_order_req").find(".company_item_list_tbody");

									for(var val in obj.vendor_items)
						  			{
						  				var inactive_class = "";
						  				var inactive_style = "";
						  				if(obj.vendor_items[val].item_active_sign == 0)
						  				{
						  					inactive_class = "inactive_item";
						  					inactive_style = "background-color:#b9b9b929;";
						  				}
						  				else
						  				{
						  					inactive_class = "item_id_checkbox";
						  					inactive_style = "background-color:#fff;";
				  						}

						  				search_count++;
						  				temp += "<tr style='"+inactive_style+"'>"+
					                    			// "<td><input type='checkbox' name='item_id' class='form-control "+inactive_class+"' data-reorderno='"+obj.vendor_items[val].item_reorder_no+"' data-item-desc='"+obj.vendor_items[val].item_description+"' data-company-itemno='"+obj.vendor_items[val].company_item_no+"' data-par-level='"+obj.vendor_items[val].item_par_level+"' value='"+obj.vendor_items[val].item_id+"'/></td>"+
					                    			"<td><label class='i-checks'><input type='checkbox' name='item_id' class='form-control "+inactive_class+"' data-reorderno='"+obj.vendor_items[val].item_reorder_no+"' data-item-desc='"+obj.vendor_items[val].item_description+"' data-company-itemno='"+obj.vendor_items[val].company_item_no+"' data-par-level='"+obj.vendor_items[val].item_par_level+"' value='"+obj.vendor_items[val].item_id+"'/><i></i></label></td>"+
					                    			"<td>"+obj.vendor_items[val].company_item_no+"</td>"+
					                    			"<td>"+obj.vendor_items[val].item_reorder_no+"</td>"+
					                    			"<td>"+obj.vendor_items[val].item_description+"</td>"+
					                    		"</tr>";
						  			}
						  			if(search_count == 0)
						  			{
						  				temp += '<tr>'+
											    	'<td colspan="4" style="text-align:center;">No Items</td>'+
											    '</tr>';
						  			}
						  			tbody.html(temp);

						  			if(search_count != 0)
						  			{
						  				$('.all_items_order_req').DataTable({
						        			"order": [[ 2, "asc" ]],
										    "bInfo": false
						    			});

						    			$("body").find(".dataTables_wrapper").find(".bootstrap-dt-container").find(".paging_simple_numbers").parent(".col-sm-12").removeClass("col-md-6");
						  			}

						  			vendor_account_no = obj.vendor_details.vendor_acct_no;
						  			vendor_account_no_container.html(vendor_account_no);
						  			vendor_phone_no = obj.vendor_details.vendor_phone_no;
						  			$("body").find(".vendor_account_no_input").val(vendor_account_no);
						  			$("body").find(".vendor_phone_no").html(vendor_phone_no);
						  			$("body").find(".vendor_phone_no_input").val(vendor_phone_no);
						  			$("body").find(".viewed_vendor_id").val(value);
								});
							}
							else
							{
								all_items_order_req_div.html("");
								table += '<table class="table bg-white b-a table-hover all_items_order_req">'+
										  	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
										    	'<tr>'+
										    		'<th style="width:9% ;"></th>'+
										    		'<th style="width:27% ;">Company Item No.</th>'+
										    		'<th style="width:25% ;">Reorder No.</th>'+
										      		'<th style="width:39% ;">Item Description</th>'+
										    	'</tr>'+
										    '</thead>'+
								    		'<tbody class="company_item_list_tbody">'+
										    '</tbody>'+
										'</table>';
								all_items_order_req_div.html(table);
								var tbody = $("body").find(".all_items_order_req").find(".company_item_list_tbody");

								temp += '<tr>'+
							    	'<td colspan="4" style="text-align:center;">No Items</td>'+
							    '</tr>';
								tbody.html(temp);
						  		vendor_account_no_container.html(vendor_account_no);
						  		$("body").find(".vendor_account_no_input").val(vendor_account_no);
						  		$("body").find(".vendor_phone_no").html(vendor_phone_no);
						  		$("body").find(".vendor_phone_no_input").val(vendor_phone_no);
						  		$("body").find(".viewed_vendor_id").val(value);
							}

							for (i = 1; i <= 14; i++) {
		      					$("body").find(".order_req_company_id_"+i).val(" ");
					            $("body").find(".order_req_item_desc_"+i).val(" ");
								$("body").find(".order_req_reorder_no_"+i).val(" ");
								$("body").find(".order_req_unit_of_measure_"+i).val(" ");
								$("body").find(".order_req_cost_"+i).val(" ");
								$("body").find(".order_req_quantity_ordered_"+i).val(" ");
								$("body").find(".order_req_par_level_"+i).val(" ");
								$("body").find(".order_req_total_cost_"+i).val("");
								$("body").find(".selected_item_id_row_"+i).val("");

								// CSS
								$("body").find(".order_req_company_id_"+i).css('background-color','#fff');
					            $("body").find(".order_req_item_desc_"+i).css('background-color','#fff');
								$("body").find(".order_req_reorder_no_"+i).css('background-color','#fff');
								$("body").find(".order_req_unit_of_measure_"+i).css('background-color','#fff');
								$("body").find(".order_req_cost_"+i).css('background-color','#fff');
								$("body").find(".order_req_par_level_"+i).css('background-color','#fff');
								$("body").find(".order_req_total_cost_"+i).css('background-color','#fff');
								$("body").find(".selected_item_id_row_"+i).css('background-color','#fff');
								$("body").find(".order_req_quantity_ordered_"+i).css('background-color','#fff');
			      			}
		    			}
		    			else
		    			{
		    				$("body").find("#choose_item_vendor_id_order_req").val(old_select_value);
		    				$('#choose_item_vendor_id_order_req option[value='+old_select_value+']').attr('selected',true);
		    			}
		    		});
				}
				else
				{
					$("body").find(".current_selected_vendor_id").val(value);
					if(value.length > 0)
					{
						$.post(base_url+"inventory/get_vedor_item_list/"+value,"", function(response){
							var obj = $.parseJSON(response);

							all_items_order_req_div.html("");
							table += '<table class="table bg-white b-a table-hover all_items_order_req">'+
									  	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
									    	'<tr>'+
									    		'<th style="width:9% ;"></th>'+
									    		'<th style="width:27% ;">Company Item No.</th>'+
									    		'<th style="width:25% ;">Reorder No.</th>'+
									      		'<th style="width:39% ;">Item Description</th>'+
									    	'</tr>'+
									    '</thead>'+
							    		'<tbody class="company_item_list_tbody">'+
									    '</tbody>'+
									'</table>';
							all_items_order_req_div.html(table);

							var tbody = $("body").find(".all_items_order_req").find(".company_item_list_tbody");

							for(var val in obj.vendor_items)
				  			{
				  				var inactive_class = "";
				  				var inactive_style = "";
				  				if(obj.vendor_items[val].item_active_sign == 0)
				  				{
				  					inactive_class = "inactive_item";
				  					inactive_style = "background-color:#b9b9b929;";
				  				}
				  				else
				  				{
				  					inactive_class = "item_id_checkbox";
				  					inactive_style = "background-color:#fff;";
				  				}

				  				search_count++;
				  				temp += "<tr style='"+inactive_style+"'>"+
			                    			// "<td><input type='checkbox' name='item_id' class='form-control "+inactive_class+"' data-reorderno='"+obj.vendor_items[val].item_reorder_no+"' data-item-desc='"+obj.vendor_items[val].item_description+"' data-company-itemno='"+obj.vendor_items[val].company_item_no+"' data-par-level='"+obj.vendor_items[val].item_par_level+"' value='"+obj.vendor_items[val].item_id+"'/>SSSS</td>"+
			                    			"<td><label class='i-checks'><input type='checkbox' name='item_id' class='form-control "+inactive_class+"' data-reorderno='"+obj.vendor_items[val].item_reorder_no+"' data-item-desc='"+obj.vendor_items[val].item_description+"' data-company-itemno='"+obj.vendor_items[val].company_item_no+"' data-par-level='"+obj.vendor_items[val].item_par_level+"' value='"+obj.vendor_items[val].item_id+"'/><i></i></label></td>"+
			                    			"<td>"+obj.vendor_items[val].company_item_no+"</td>"+
			                    			"<td>"+obj.vendor_items[val].item_reorder_no+"</td>"+
			                    			"<td>"+obj.vendor_items[val].item_description+"</td>"+
			                    		"</tr>";
				  			}
				  			if(search_count == 0)
				  			{
				  				temp += '<tr>'+
									    	'<td colspan="4" style="text-align:center;">No Items</td>'+
									    '</tr>';
				  			}
				  			tbody.html(temp);

				  			if(search_count != 0)
				  			{
				  				$('.all_items_order_req').DataTable({
				        			"order": [[ 2, "asc" ]],
								    "bInfo": false
				    			});

				    			$("body").find(".dataTables_wrapper").find(".bootstrap-dt-container").find(".paging_simple_numbers").parent(".col-sm-12").removeClass("col-md-6");
				  			}

				  			vendor_account_no = obj.vendor_details.vendor_acct_no;
				  			vendor_account_no_container.html(vendor_account_no);
				  			vendor_phone_no = obj.vendor_details.vendor_phone_no;
				  			$("body").find(".vendor_account_no_input").val(vendor_account_no);
				  			$("body").find(".vendor_phone_no").html(vendor_phone_no);
				  			$("body").find(".vendor_phone_no_input").val(vendor_phone_no);
				  			$("body").find(".viewed_vendor_id").val(value);
						});
					}
					else
					{
						all_items_order_req_div.html("");
						table += '<table class="table bg-white b-a table-hover all_items_order_req">'+
								  	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
								    	'<tr>'+
								    		'<th style="width:9% ;"></th>'+
								    		'<th style="width:27% ;">Company Item No.</th>'+
								    		'<th style="width:25% ;">Reorder No.</th>'+
								      		'<th style="width:39% ;">Item Description</th>'+
								    	'</tr>'+
								    '</thead>'+
						    		'<tbody class="company_item_list_tbody">'+
								    '</tbody>'+
								'</table>';
						all_items_order_req_div.html(table);
						var tbody = $("body").find(".all_items_order_req").find(".company_item_list_tbody");

						temp += '<tr>'+
					    	'<td colspan="4" style="text-align:center;">No Items</td>'+
					    '</tr>';
						tbody.html(temp);
				  		vendor_account_no_container.html(vendor_account_no);
				  		$("body").find(".vendor_account_no_input").val(vendor_account_no);
				  		$("body").find(".vendor_phone_no").html(vendor_phone_no);
				  		$("body").find(".vendor_phone_no_input").val(vendor_phone_no);
				  		$("body").find(".viewed_vendor_id").val(value);
					}
				}
			}
  	   	});

      	$('body').on('click','.item_id_checkbox',function(){
      		var item_id = $(this).val();
      		var item_no = $(this).attr("data-company-itemno");
      		var item_description = $(this).attr("data-item-desc");
      		var item_reorder_no = $(this).attr("data-reorderno");
      		var item_par_level = $(this).attr("data-par-level");
      		var item_counter = $('#item_count').val();

      		for (i = 1; i <= 42; i++) {

			    if($(this).is(':checked'))
	      		{
	      			var data = $("body").find(".order_req_company_id_"+i).val();
	      			if(data == " ")
				    {
				    	item_counter++;
				    	if(i > 14) {
							var row_data = $("body").find(".order_requisition_item_box_"+i).show();
						}

				    	$.post(base_url+"inventory/get_searched_item_v4/"+item_id,"", function(response){
							var obj = $.parseJSON(response);
							var item_unit_of_measure = "";

							if(obj.item_cost.item_unit_measure == "box")
							{
								item_unit_of_measure = "box";
							}
							else if(obj.item_cost.item_unit_measure == "case")
							{
								item_unit_of_measure = "cs";
							}
							else if(obj.item_cost.item_unit_measure == "each")
							{
								item_unit_of_measure = "ea";
							}
							else if(obj.item_cost.item_unit_measure == "pair")
							{
								item_unit_of_measure = "pr";
							}
							else if(obj.item_cost.item_unit_measure == "pack")
							{
								item_unit_of_measure = "pk";
							}
							else if(obj.item_cost.item_unit_measure == "package")
							{
								item_unit_of_measure = "pkg";
							}
							else if(obj.item_cost.item_unit_measure == "roll")
							{
								item_unit_of_measure = "rl";
							}

					    	$("body").find(".order_req_company_id_"+i).val(item_no);
				            $("body").find(".order_req_item_desc_"+i).val(item_description);
							$("body").find(".order_req_reorder_no_"+i).val(item_reorder_no);
							$("body").find(".order_req_unit_of_measure_"+i).val(item_unit_of_measure);
							$("body").find(".order_req_cost_"+i).val(obj.item_cost.item_vendor_cost);
							$("body").find(".order_req_par_level_"+i).val(item_par_level);
							$("body").find(".order_req_total_cost_"+i).val("0");
							$("body").find(".selected_item_id_row_"+i).val(obj.item_cost.item_id);
						});
						break;
				    }
	      		}
	      		else
	      		{
	      			var data = $("body").find(".order_req_company_id_"+i).val();

	      			if(data == item_no)
	      			{
	      				item_counter--;
	      				$("body").find(".order_req_company_id_"+i).val(" ");
			            $("body").find(".order_req_item_desc_"+i).val(" ");
						$("body").find(".order_req_reorder_no_"+i).val(" ");
						$("body").find(".order_req_unit_of_measure_"+i).val(" ");
						$("body").find(".order_req_quantity_ordered_"+i).val(" ");
						$("body").find(".order_req_cost_"+i).val(" ");
						$("body").find(".order_req_par_level_"+i).val(" ");
						$("body").find(".order_req_total_cost_"+i).val("");
						$("body").find(".selected_item_id_row_"+i).val("");
	      				break;
	      			}
	      		}
			}
			$('#item_count').val(item_counter);
        });

      	var globalTimeout = null;
        $('.company_item_no_textarea').bind('keyup',function(){
        	var _this = $(this);
        	var item_no = $(this).val();
        	item_no = item_no.replace(/\s+/g, '');
        	var vendor_id = $("body").find(".viewed_vendor_id").val();
			var row_id = $(this).attr("data-row-id");

			if(vendor_id != "")
			{
				if(globalTimeout != null) clearTimeout(globalTimeout);
			    globalTimeout =setTimeout(getInfoFunc,1100);

			    function getInfoFunc(){
			      	globalTimeout = null;

					$.post(base_url+"inventory/get_searched_item_v2/"+vendor_id+"/"+item_no,"", function(response){
						var obj = $.parseJSON(response);

						if(obj.item_details.item_description != undefined)
						{
							$("body").find(".order_req_item_desc_"+row_id).html(" ");
							$("body").find(".order_req_reorder_no_"+row_id).html(" ");
							$("body").find(".order_req_unit_of_measure_"+row_id).html(" ");
							$("body").find(".order_req_par_level_"+row_id).html(" ");
							$("body").find(".order_req_cost_"+row_id).html(" ");

							var item_unit_of_measure = "";
							if(obj.item_cost.item_unit_measure == "box")
							{
								item_unit_of_measure = "box";
							}
							else if(obj.item_cost.item_unit_measure == "case")
							{
								item_unit_of_measure = "cs";
							}
							else if(obj.item_cost.item_unit_measure == "each")
							{
								item_unit_of_measure = "ea";
							}
							else if(obj.item_cost.item_unit_measure == "pair")
							{
								item_unit_of_measure = "pr";
							}
							else if(obj.item_cost.item_unit_measure == "pack")
							{
								item_unit_of_measure = "pk";
							}
							else if(obj.item_cost.item_unit_measure == "package")
							{
								item_unit_of_measure = "pkg";
							}
							else if(obj.item_cost.item_unit_measure == "roll")
							{
								item_unit_of_measure = "rl";
							}
							$("body").find(".order_req_item_desc_"+row_id).val(obj.item_details.item_description);
							$("body").find(".order_req_reorder_no_"+row_id).val(obj.item_details.item_reorder_no);
							$("body").find(".order_req_unit_of_measure_"+row_id).val(item_unit_of_measure);
							$("body").find(".order_req_cost_"+row_id).val(obj.item_cost.item_vendor_cost);
							$("body").find(".order_req_par_level_"+row_id).val(obj.item_details.item_par_level);
							_this.parent().siblings(".selected_item_id").val(obj.item_details.item_id);
							$("body").find(".order_req_total_cost_"+row_id).val("0");

						}
						else
						{
							$("body").find(".order_req_item_desc_"+row_id).val(" ");
							$("body").find(".order_req_reorder_no_"+row_id).val(" ");
							$("body").find(".order_req_unit_of_measure_"+row_id).val(" ");
							$("body").find(".order_req_cost_"+row_id).val(" ");
							$("body").find(".order_req_par_level_"+row_id).val(" ");
							_this.parent().siblings(".selected_item_id").val("");
							$("body").find(".order_req_total_cost_"+row_id).val("");
						}

						var quantity = $("body").find(".order_req_quantity_ordered_"+row_id).val();
						var cost = $("body").find(".order_req_cost_"+row_id).val();
						var order_req_shipping_cost = $("body").find(".order_req_shipping_cost").val();
						var total_cost = 0;
        				var order_req_total_amount = 0;

        				cost = Number(cost);
		        		quantity = Number(quantity);
		        		order_req_shipping_cost = Number(order_req_shipping_cost);

		        		total_cost = cost*quantity;
		      			$("body").find(".order_req_total_cost_"+row_id).val(total_cost.toFixed(2));

		      			$.each($("body").find(".item_total_cost"), function(){
					        order_req_total_amount += Number($(this).val());
					    });
					    $("body").find(".order_req_total_amount").val(order_req_total_amount.toFixed(2));

				    	order_req_grand_total_amount = Number(order_req_total_amount.toFixed(2)+order_req_shipping_cost);
				      	$("body").find(".order_req_grand_total_amount").val(order_req_grand_total_amount.toFixed(2));
					});
				}
			}
        });

      	var globalTimeout = null;
      	$('.company_item_desc_textarea').bind('keyup',function(){
      		var _this = $(this);
      		var item_description = $(this).val();
        	var vendor_id = $("body").find(".viewed_vendor_id").val();
			var row_id = $(this).attr("data-row-id");

			if(vendor_id != "")
			{
				if(globalTimeout != null) clearTimeout(globalTimeout);
			    globalTimeout =setTimeout(getInfoFunc,1100);

			    function getInfoFunc(){
			      	globalTimeout = null;

			      	$.post(base_url+"inventory/get_searched_item_v3/"+vendor_id+"/"+item_description,"", function(response){
						var obj = $.parseJSON(response);

						if(obj.item_details.item_description != undefined)
						{
							$("body").find(".order_req_company_id_"+row_id).html(" ");
							$("body").find(".order_req_reorder_no_"+row_id).html(" ");
							$("body").find(".order_req_unit_of_measure_"+row_id).html(" ");
							$("body").find(".order_req_cost_"+row_id).html(" ");
							$("body").find(".order_req_par_level_"+row_id).html(" ");

							var item_unit_of_measure = "";
							if(obj.item_cost.item_unit_measure == "box")
							{
								item_unit_of_measure = "box";
							}
							else if(obj.item_cost.item_unit_measure == "case")
							{
								item_unit_of_measure = "cs";
							}
							else if(obj.item_cost.item_unit_measure == "each")
							{
								item_unit_of_measure = "ea";
							}
							else if(obj.item_cost.item_unit_measure == "pair")
							{
								item_unit_of_measure = "pr";
							}
							else if(obj.item_cost.item_unit_measure == "pack")
							{
								item_unit_of_measure = "pk";
							}
							else if(obj.item_cost.item_unit_measure == "package")
							{
								item_unit_of_measure = "pkg";
							}
							else if(obj.item_cost.item_unit_measure == "roll")
							{
								item_unit_of_measure = "rl";
							}
							$("body").find(".order_req_company_id_"+row_id).val(obj.item_details.company_item_no);
							$("body").find(".order_req_reorder_no_"+row_id).val(obj.item_details.item_reorder_no);
							$("body").find(".order_req_unit_of_measure_"+row_id).val(item_unit_of_measure);
							$("body").find(".order_req_par_level_"+row_id).val(obj.item_details.item_par_level);
							$("body").find(".order_req_cost_"+row_id).val(obj.item_cost.item_vendor_cost);
							_this.parent().siblings(".selected_item_id").val(obj.item_details.item_id);
							$("body").find(".order_req_total_cost_"+row_id).val("0");
						}
						else
						{
							$("body").find(".order_req_company_id_"+row_id).val(" ");
							$("body").find(".order_req_reorder_no_"+row_id).val(" ");
							$("body").find(".order_req_unit_of_measure_"+row_id).val(" ");
							$("body").find(".order_req_par_level_"+row_id).val(" ");
							$("body").find(".order_req_cost_"+row_id).val(" ");
							_this.parent().siblings(".selected_item_id").val("");
							$("body").find(".order_req_total_cost_"+row_id).val("");
						}

						var quantity = $("body").find(".order_req_quantity_ordered_"+row_id).val();
						var cost = $("body").find(".order_req_cost_"+row_id).val();
						var order_req_shipping_cost = $("body").find(".order_req_shipping_cost").val();
						var total_cost = 0;
        				var order_req_total_amount = 0;

        				cost = Number(cost);
		        		quantity = Number(quantity);
		        		order_req_shipping_cost = Number(order_req_shipping_cost);

		        		total_cost = cost*quantity;
		      			$("body").find(".order_req_total_cost_"+row_id).val(total_cost.toFixed(2));

		      			$.each($("body").find(".item_total_cost"), function(){
					        order_req_total_amount += Number($(this).val());
					    });
					    $("body").find(".order_req_total_amount").val(order_req_total_amount.toFixed(2));

				    	order_req_grand_total_amount = Number(order_req_total_amount.toFixed(2)+order_req_shipping_cost);
				      	$("body").find(".order_req_grand_total_amount").val(order_req_grand_total_amount.toFixed(2));
					});
			  	}
			}
        });

        var globalTimeout = null;
      	$('.company_item_unit_of_measure').bind('keyup',function(){
      		var _this = $(this);
      		var unit_of_measure = $(this).val();
        	var vendor_id = $("body").find(".viewed_vendor_id").val();
			var row_id = $(this).attr("data-row-id");
			var cost = $("body").find(".order_req_cost_"+row_id).val();
        	var total_cost = 0;
			var item_id = _this.parent().siblings(".selected_item_id").val();

			if(vendor_id != "")
			{
				if(globalTimeout != null) clearTimeout(globalTimeout);
			    globalTimeout =setTimeout(getInfoFunc,1100);

			    function getInfoFunc(){
			      	globalTimeout = null;

			      	$.post(base_url+"inventory/get_item_unit_of_measure/"+item_id+"/"+unit_of_measure+"/","", function(response){
						var obj = $.parseJSON(response);

						if(obj.item_details.item_vendor_cost != undefined)
						{
							$("body").find(".order_req_cost_"+row_id).val(obj.item_details.item_vendor_cost);
						}
						else
						{
							$("body").find(".order_req_cost_"+row_id).val("");
						}

						var quantity = $("body").find(".order_req_quantity_ordered_"+row_id).val();
						var cost = $("body").find(".order_req_cost_"+row_id).val();
						var order_req_shipping_cost = $("body").find(".order_req_shipping_cost").val();
						var total_cost = 0;
        				var order_req_total_amount = 0;

        				cost = Number(cost);
		        		quantity = Number(quantity);
		        		order_req_shipping_cost = Number(order_req_shipping_cost);

		        		total_cost = cost*quantity;
		      			$("body").find(".order_req_total_cost_"+row_id).val(total_cost.toFixed(2));

		      			$.each($("body").find(".item_total_cost"), function(){
					        order_req_total_amount += Number($(this).val());
					    });
					    $("body").find(".order_req_total_amount").val(order_req_total_amount.toFixed(2));

				    	order_req_grand_total_amount = Number(order_req_total_amount.toFixed(2)+order_req_shipping_cost);
				      	$("body").find(".order_req_grand_total_amount").val(order_req_grand_total_amount.toFixed(2));
					});
			    }
			}
      	});

      	var globalTimeout = null;
        $('.company_item_quantity_textarea').bind('keyup',function(){
        	var _this = $(this);
        	var row_id = $(this).attr("data-row-id");
        	var quantity = $(this).val();
        	var cost = $("body").find(".order_req_cost_"+row_id).val();
        	var order_req_shipping_cost = $("body").find(".order_req_shipping_cost").val();
        	var total_cost = 0;
        	var order_req_total_amount = 0;

        	if(globalTimeout != null) clearTimeout(globalTimeout);
		    globalTimeout =setTimeout(getInfoFunc,1000);

		    function getInfoFunc(){
		      	globalTimeout = null;
		      	cost = Number(cost);
        		quantity = Number(quantity);
        		order_req_shipping_cost = Number(order_req_shipping_cost);

		      	total_cost = cost*quantity;
		      	$("body").find(".order_req_total_cost_"+row_id).val(total_cost.toFixed(2));

		    	$.each($("body").find(".item_total_cost"), function(){
			        order_req_total_amount += Number($(this).val());
			    });
			    $("body").find(".order_req_total_amount").val(order_req_total_amount.toFixed(2));

		    	order_req_grand_total_amount = Number(order_req_total_amount+order_req_shipping_cost);
		      	$("body").find(".order_req_grand_total_amount").val(order_req_grand_total_amount.toFixed(2));
		    }
        });

        $('.order_req_shipping_cost').bind('keyup',function(){
			var order_req_shipping_cost = $(this).val();
			var order_req_total_amount = $("body").find(".order_req_total_amount").val();
			var order_req_grand_total_amount = 0;

			order_req_shipping_cost = Number(order_req_shipping_cost);
    		order_req_total_amount = Number(order_req_total_amount);

	    	order_req_grand_total_amount = Number(order_req_total_amount+order_req_shipping_cost);

	      	$("body").find(".order_req_grand_total_amount").val(order_req_grand_total_amount.toFixed(2));
        });

        //Save the Order Requisition
		$('.btn-save-order-req').bind('click',function(){
			var form_data = $('.purchase_order_req_form').serialize();
			var total_item_count = $('#item_count').val();

			jConfirm("Save Order Requisition?","Reminder",function(response){
				if(response)
    			{
    				$(this).prop("disabled","true");
					$.post(base_url+"inventory/save_order_requisition/" + total_item_count,form_data, function(response){

						var obj = $.parseJSON(response);
		                jAlert(obj['message'],"Reminder");
		                if(obj['error'] == 0)
		                {
		                  	setTimeout(function(){
			                	location.reload();
			              	},2000);
		                }
					});
    			}
			});
		});

		//Save the Draft Order Requisition
		$('.btn-draft-order-req').bind('click',function(){
			var form_data = $('.purchase_order_req_form').serialize();
			var total_item_count = $('#item_count').val();

			jConfirm("Save Order Requisition as Draft?","Reminder",function(response){
				if(response)
    			{
    				$(this).prop("disabled","true");
					$.post(base_url+"inventory/draft_order_requisition/" + total_item_count,form_data, function(response){

						var obj = $.parseJSON(response);
		                jAlert(obj['message'],"Reminder");
		                if(obj['error'] == 0)
		                {
		                  	setTimeout(function(){
			                	location.reload();
			              	},2000);
		                }
					});
    			}
			});
		});

		$('body').on('click','.inactive_item',function(){
			var _this = $(this);
			var item_id = _this.val();
      		var item_no = _this.attr("data-company-itemno");
      		var item_description = _this.attr("data-item-desc");
      		var item_reorder_no = _this.attr("data-reorderno");
      		var item_par_level = _this.attr("data-par-level");
			var break_indicator = 0;
			var item_counter = $('#item_count').val();

			if(_this.is(':checked'))
			{
				jConfirm("Confirm adding of inactive item on the order?","Reminder",function(response){
					if(response)
	    			{
	    				item_counter++;
	    				$('#item_count').val(item_counter);
			      		for (i = 1; i <= 42; i++) {

			      			var data = $("body").find(".order_req_company_id_"+i).val();
			      			if(data == " ")
						    {
						    	if(i > 14) {
									var row_data = $("body").find(".order_requisition_item_box_"+i).show();
								}

								$.post(base_url+"inventory/get_searched_item_v4/"+item_id,"", function(response){
									var obj = $.parseJSON(response);
									var item_unit_of_measure = "";

									if(obj.item_cost.item_unit_measure == "box")
									{
										item_unit_of_measure = "box";
									}
									else if(obj.item_cost.item_unit_measure == "case")
									{
										item_unit_of_measure = "cs";
									}
									else if(obj.item_cost.item_unit_measure == "each")
									{
										item_unit_of_measure = "ea";
									}
									else if(obj.item_cost.item_unit_measure == "pair")
									{
										item_unit_of_measure = "pr";
									}
									else if(obj.item_cost.item_unit_measure == "pack")
									{
										item_unit_of_measure = "pk";
									}
									else if(obj.item_cost.item_unit_measure == "package")
									{
										item_unit_of_measure = "pkg";
									}
									else if(obj.item_cost.item_unit_measure == "roll")
									{
										item_unit_of_measure = "rl";
									}

							    	$("body").find(".order_req_company_id_"+i).val(item_no);
						            $("body").find(".order_req_item_desc_"+i).val(item_description);
									$("body").find(".order_req_reorder_no_"+i).val(item_reorder_no);
									$("body").find(".order_req_unit_of_measure_"+i).val(item_unit_of_measure);
									$("body").find(".order_req_cost_"+i).val(obj.item_cost.item_vendor_cost);
									$("body").find(".order_req_par_level_"+i).val(item_par_level);
									$("body").find(".order_req_total_cost_"+i).val("0");
									$("body").find(".selected_item_id_row_"+i).val(obj.item_cost.item_id);

									// CSS
									$("body").find(".order_req_company_id_"+i).css('background-color','rgba(185, 185, 185, 0.18)');
						            $("body").find(".order_req_item_desc_"+i).css('background-color','rgba(185, 185, 185, 0.18)');
									$("body").find(".order_req_reorder_no_"+i).css('background-color','rgba(185, 185, 185, 0.18)');
									$("body").find(".order_req_unit_of_measure_"+i).css('background-color','rgba(185, 185, 185, 0.18)');
									$("body").find(".order_req_cost_"+i).css('background-color','rgba(185, 185, 185, 0.18)');
									$("body").find(".order_req_par_level_"+i).css('background-color','rgba(185, 185, 185, 0.18)');
									$("body").find(".order_req_total_cost_"+i).css('background-color','rgba(185, 185, 185, 0.18)');
									$("body").find(".selected_item_id_row_"+i).css('background-color','rgba(185, 185, 185, 0.18)');
									$("body").find(".order_req_quantity_ordered_"+i).css('background-color','rgba(185, 185, 185, 0.18)');
								});
								break;
						    }
						}
	    			}
	    			else
	    			{
	    				_this.prop('checked', false);

	    				// CSS
						$("body").find(".order_req_company_id_"+i).css('background-color','#fff');
			            $("body").find(".order_req_item_desc_"+i).css('background-color','#fff');
						$("body").find(".order_req_reorder_no_"+i).css('background-color','#fff');
						$("body").find(".order_req_unit_of_measure_"+i).css('background-color','#fff');
						$("body").find(".order_req_cost_"+i).css('background-color','#fff');
						$("body").find(".order_req_par_level_"+i).css('background-color','#fff');
						$("body").find(".order_req_total_cost_"+i).css('background-color','#fff');
						$("body").find(".selected_item_id_row_"+i).css('background-color','#fff');
						$("body").find(".order_req_quantity_ordered_"+i).css('background-color','#fff');
	    			}
				});
			}
      		else
      		{
      			for (i = 1; i <= 14; i++) {
      				var data = $("body").find(".order_req_company_id_"+i).val();

	      			if(data == item_no)
	      			{
	      				item_counter--;
	      				$('#item_count').val(item_counter);

      					$("body").find(".order_req_company_id_"+i).val(" ");
			            $("body").find(".order_req_item_desc_"+i).val(" ");
						$("body").find(".order_req_reorder_no_"+i).val(" ");
						$("body").find(".order_req_unit_of_measure_"+i).val(" ");
						$("body").find(".order_req_quantity_ordered_"+i).val(" ");
						$("body").find(".order_req_cost_"+i).val(" ");
						$("body").find(".order_req_par_level_"+i).val(" ");
						$("body").find(".order_req_total_cost_"+i).val("");
						$("body").find(".selected_item_id_row_"+i).val("");

						// CSS
						$("body").find(".order_req_company_id_"+i).css('background-color','#fff');
			            $("body").find(".order_req_item_desc_"+i).css('background-color','#fff');
						$("body").find(".order_req_reorder_no_"+i).css('background-color','#fff');
						$("body").find(".order_req_unit_of_measure_"+i).css('background-color','#fff');
						$("body").find(".order_req_cost_"+i).css('background-color','#fff');
						$("body").find(".order_req_par_level_"+i).css('background-color','#fff');
						$("body").find(".order_req_total_cost_"+i).css('background-color','#fff');
						$("body").find(".selected_item_id_row_"+i).css('background-color','#fff');
						$("body").find(".order_req_quantity_ordered_"+i).css('background-color','#fff');

						break;
      				}
      			}
      		}
		});
	});

</script>

