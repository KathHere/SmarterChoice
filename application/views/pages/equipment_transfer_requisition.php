<style type="text/css">

	.print_order_requisition
	{
		padding-right: 32px !important;
		padding-left: 30px !important;
		padding-top: 30px !important;
		margin-bottom: 10px;
	}

	.equipments_modal {
		height: 100% !important;
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
	.row_table {
		overflow: hidden;
	}
</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  	<h1 class="m-n font-thin h3">Equipment Transfer Requisition
  		<div class="pull-right">
			<button data-target="#all_items_modal" data-toggle="modal" type="button" class="btn btn-info"> Select Items </button>
		</div>
  	</h1>

</div>

<div class="wrapper-md">
	<input type="hidden" class="current_selected_vendor_id" value="0">
	<div class="panel panel-default" style="overflow-x:scroll;">
		<div class="ng-scope" style="min-width: 2000px !important;">
		    <div class="panel-body" style="padding-left:0px;padding-right:0px;">
		    	<div class="row purchase_order_requisition_row">
            <div class="col-xs-1 col-sm-1 col-md-1 hidden-print" style="width:1.33333333% !important"> </div>
		    		<!-- <div class="col-xs-4 col-sm-4 col-md-4 hidden-print all_items_order_req_div">

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

		   	 		</div> -->
		   	 		<?php
                        echo form_open('', array('class' => 'equip_transfer_req_form'));
                    ?>
		   	 		<div class="col-xs-10 col-sm-10 col-md-10 order_requisition_parent_div" style="padding-left:0px; width: 97.33333333% !important">
		   	 			<div class="form-group order_requisition_div">
			   	 			<h4 class="order_req_first_header" style="text-align:center;">
			   	 				<strong> Advantage Home Medical Services </strong>
			   	 			</h4>
			   	 			<p class="order_req_second_header" style="text-align:center;font-size:16px;margin-top:-10px;font-weight:bold;"> Equipment Transfer Requisition </p>

			   	 			<div class="row order_requisition_details_row" style="margin-top:30px;margin-bottom: 0px;">
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="">
				   	 				<strong>Date:</strong> <?php echo date('m/d/Y'); ?>
				   	 				<input type="hidden" name="order_req_date" value="<?php echo date('m/d/Y'); ?>">
				   	 			</div>
				   	 			<div class="col-xs-6 col-xs-6 col-sm-6 col-md-6" style="padding-right:0px;">
									<div class="col-md-6" style="padding-left: 0px">
										<strong>Location:</strong>
									</div>
									<div class="col-md-6" style="margin-left: -420px !important">
										<?php
											$location = get_login_location($this->session->userdata('user_location'));
										?>
										<span class="location_span">
											<?php
												if ($this->session->userdata('user_location') != 0) {
													echo $location['user_city'].', '.$location['user_state'];
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
				   	 		<div class="row order_requisition_details_row" style="margin-top:9px;">
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="">
										<div class="col-xs-4 col-sm-4 col-md-4" style="margin-top:4px; padding-left: 0px !important">
				   	 					<strong>Receiving Location:</strong>
										</div>
				   	 				<!-- <input type="text" class="vendor_rep_no_input" name="receiving_location" value=""> -->
										<div class="col-xs-8 col-sm-8 col-md-8" style="margin-left: -190px !important">
											<select name="item_location" class="form-control choose_item_location_order_req" id="choose_item_location_id_order_req" style="height:30px;width:270px;">
												<option value=""> [--Choose Location--] </option>
												<?php
                                                if (!empty($location_list)) {
                                                    foreach ($location_list as $key => $value) {
                                                    	if($location['location_id'] != $value['location_id']) {
                                                        ?>
													<option value="<?php echo $value['location_id']; ?>"> <?php echo $value['user_city']; ?>, <?php echo $value['user_state']; ?>  </option>
												<?php
														}
                                                    }
                                                }
                                                ?>
											</select>
										</div>
				   	 			</div>
				   	 			<div class="col-xs-6 col-sm-6 col-md-6" style="">
				   	 				<?php
				   	 					$temp_equip_transfer_order_no = strtotime(date('Y-m-d H:i:s tt'));
                                        $equip_transfer_order_no = substr($temp_equip_transfer_order_no,1,11);
                                        $equip_transfer_order_no[0] = '1';
                                    ?>
				   	 				<strong>Transfer PO No.:</strong> <?php echo substr($equip_transfer_order_no,3,10); ?>
				   	 				<input type="hidden" name="equip_transfer_order_no" class="equip_transfer_order_no" value="<?php echo $equip_transfer_order_no; ?>">
				   	 			</div>
				   	 		</div>

				   	 		<div class="row order_requisition_details_row_v2" style="margin-top:15px !important;margin-right:5px !important;">
				   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details company_item_no_col" style="border-right:0px !important;padding-top:10px;">
				   	 				<p> Company</p>
				   	 				<p style="margin-top:-10px;"> Item No. </p>
				   	 			</div>
				   	 			<div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details item_description_col" style="border-right:0px !important;padding-top:20px;">
				   	 				Item Description
				   	 			</div>
                  <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details vendor" style="border-right:0px !important;padding-top:20px;">
				   	 				Vendor
				   	 			</div>
				   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details reorder_no_col" style="border-right:0px !important;padding-top:20px;">
				   	 				Re Order No.
				   	 			</div>
				   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details unit_of_measure_col" style="border-right:0px !important;padding-top:10px;">
										<p> Unit of</p>
				   	 				<p style="margin-top:-10px;">Measure</p>
				   	 			</div>
									<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details qty_ordered_col" style="border-right:0px !important;padding-top:10px;">
				   	 				<p> Qty.</p>
				   	 				<p style="margin-top:-10px;"> Ordered </p>
				   	 			</div>
                  <div class="col-xs-2 col-sm-2 col-md-2 order_requisition_item_details reorder_no_col" style="border-right:0px !important;padding-top:20px;">
				   	 				Serial No.
				   	 			</div>
                  <div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details reorder_no_col" style="border-right:0px !important;padding-top:20px;">
				   	 				Asset No.
				   	 			</div>
				   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details item_cost_col" style="border-right:0px !important;padding-top:20px;">
                    <p> Equipment</p>
				   	 				<p style="margin-top:-10px;"> Cost </p>
				   	 			</div>
				   	 			<div class="col-xs-1 col-sm-1 col-md-1 order_requisition_item_details total_cost_col" style="padding-top:20px;">
				   	 				Total Cost
				   	 			</div>
				   	 		</div>

				   	 		<?php
                                for ($i = 1; $i <= 42; ++$i) {
                                    if ($i <= 14) {
                                        ?>
							   	 		<div class="row order_requisition_details_row_v2 order_requisition_item_box_<?php echo $i; ?>" style="margin-right:5px !important;" >
							   	 			<input type="hidden" name="order_req_item_id_<?php  echo $i; ?>" class="selected_item_id selected_item_id_row_<?php  echo $i; ?>" value="">
											<div class="text_area_wrapper_height_<?php  echo $i; ?> company_item_no_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 company_item_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
							   	 				<textarea name="order_req_company_id_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="1" class="row_table_<?php  echo $i; ?> row_table company_item_no_textarea order_requisition_form_textarea_col1 company_item_no_textarea_<?php  echo $i; ?> order_req_company_id_<?php  echo $i; ?>" style="width: 100% !important;"> </textarea>
							   	 			</div>
							   	 			<div class="text_area_wrapper_height_<?php  echo $i; ?> item_description_wrapper_<?php echo $i; ?> col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 item_description_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
							   	 				<textarea name="order_req_item_desc_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="2" class="row_table_<?php  echo $i; ?> row_table company_item_desc_textarea order_requisition_form_textarea_col4 company_item_desc_textarea_<?php  echo $i; ?> order_req_item_desc_<?php  echo $i; ?>" style="width: 100% !important; display:inline-block !important;vertical-align:middle !important;text-transform: uppercase;"> </textarea>
							   	 			</div>
							   	 			<div class="text_area_wrapper_height_<?php  echo $i; ?> item_vendor_no_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 vendor_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
													<div class="vendors_item_no_<?php  echo $i; ?>"></div>
													<select name="order_req_vendor_no_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" class="row_table_<?php  echo $i; ?> row_table form-control choose_item_vendor_order_req order_req_vendor_no_<?php  echo $i; ?> order_req_<?php  echo $i; ?>" id="" style="text-align-last: center; height:96% !important;width: 100% !important; border: none !important; -webkit-box-shadow: none !important; box-shadow: none !important; display: none">
														<option value=""> [--Choose Vendor--] </option>
													</select>
													<textarea name="order_req_vendor_no_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="3" class="order_requisition_form_textarea_col2 order_req_<?php  echo $i; ?> order_vendor_no_<?php  echo $i; ?> " style="text-transform: uppercase; width: 100% !important;height:96% !important; display:none;"> </textarea>
							   	 			</div>
							   	 			<div class="text_area_wrapper_height_<?php  echo $i; ?> item_reorder_no_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 reorder_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
													<!-- <div class="vendors_item_no_<?php  echo $i; ?>"></div> -->
													<input type="hidden" class="reorder_item_no_<?php  echo $i; ?>" value="">
													<select name="order_req_reorder_no_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" class="row_table_<?php  echo $i; ?> row_table form-control choose_reorder_no_order_req order_req_<?php  echo $i; ?> order_req_reorder_no_<?php  echo $i; ?>" id="" style="text-align-last: center; height:96% !important;width: 100% !important; border: none !important; -webkit-box-shadow: none !important; box-shadow: none !important; display: none">
														<option value=""> [--Choose Reorder Number--] </option>
													</select>
													<textarea name="order_req_reorder_no_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="4" class="company_item_reorder_no order_requisition_form_textarea_col1 choose_reorder_no_order_req_<?php  echo $i; ?> item_reorder_no_<?php echo $i; ?>" style="width: 100% !important; height:96% !important; display:none;" readonly> </textarea>
							   	 			</div>
							   	 			<div class="text_area_wrapper_height_<?php  echo $i; ?> item_unit_measurement_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 unit_of_measure_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
													<select name="order_req_item_unit_measurement_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" class="row_table_<?php  echo $i; ?> row_table form-control choose_item_unit_measurement_order_req order_req_<?php  echo $i; ?> order_req_item_unit_measurement_<?php  echo $i; ?>" id="" style="text-align-last: center; height:96% !important;width: 100% !important; border: none !important; -webkit-box-shadow: none !important; box-shadow: none !important; display: none">
														<option value=""> [--Choose Unit of Measurement--] </option>
													</select>
													<textarea name="order_req_item_unit_measurement_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="5" class="order_requisition_form_textarea_col1 item_unit_measurement_<?php  echo $i; ?> order_req_cost_<?php  echo $i; ?> order_req_unit_of_measure_<?php  echo $i; ?>" style="width: 100% !important; height:96% !important; display:none" readonly> </textarea>
							   	 			</div>
												<div class="text_area_wrapper_height_<?php  echo $i; ?> quantity_ordered_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 qty_ordered_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
													<input type="hidden" value="" class="quantity_limit_<?php echo $i; ?>">
														<!-- <textarea rel="popover"
                          data-html="true"
                          data-toggle="popover"
                          data-trigger="focus"
                          data-placement="right"
                          data-quantity-limit="0"
                          data-content="Maximum of 0" style="display: none; width: 100% !important; padding-right: 0px !important" name="order_req_quantity_ordered_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="6" class="row_table_<?php  echo $i; ?> row_table company_quantity_ordered_textarea order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_quantity_ordered_<?php  echo $i; ?>"> </textarea> -->
							                          <textarea rel="popover"
							                           style="display: none; width: 100% !important; padding-right: 0px !important" name="order_req_quantity_ordered_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="6" class="row_table_<?php  echo $i; ?> row_table company_quantity_ordered_textarea order_requisition_form_textarea_col1 order_req_<?php  echo $i; ?> order_req_quantity_ordered_<?php  echo $i; ?>"> </textarea>
														<input type="hidden" class="serial_asset_seletected_<?php echo $i; ?>" value="0">
												</div>
							   	 			<div class="text_area_wrapper_height_<?php  echo $i; ?> item_serial_no_wrapper_<?php echo $i; ?> col-xs-2 col-sm-2 col-md-2 order_requisition_item_details_v2 serial_no_col" style="height:48px;border-top:0px !important;border-right:0px !important;padding-top:2px;">
													<div class="serial_asset_no_selected_<?php  echo $i; ?>"></div>
													<div data-target="#serial_asset_no_modal" data-toggle="modal" data-item-measurement="default" data-row-id="<?php  echo $i; ?>" class="row_table_<?php  echo $i; ?>  form-control choose_serial_no_order_req order_req_<?php  echo $i; ?> order_req_serial_no_<?php  echo $i; ?>" id="" style="padding: 0px !important; cursor: pointer; height:96% !important;width: 100% !important; border: none !important; -webkit-box-shadow: none !important; box-shadow: none !important; display: none;">
														<textarea style="cursor: pointer !important; width: 100% !important; height: 100% !important; " class="row_table order_requisition_form_textarea_col2 order_req_serial_number_<?php echo $i; ?>" readonly></textarea>
													</div>

													<textarea name="order_req_serial_no_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="7" class="company_item_quantity_textarea order_requisition_form_textarea_col1 choose_serial_no_order_req_<?php  echo $i; ?>" style="width: 100% !important; height:96% !important; display:none;" readonly> </textarea>
							   	 			</div>
							   	 			<div class="text_area_wrapper_height_<?php  echo $i; ?> item_asset_no_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 asset_no_col" style="height:48px;border-top:0px !important;border-right:0px !important; padding-top:2px; display:">
													<div data-target="#serial_asset_no_modal" data-toggle="modal" data-item-measurement="default" data-row-id="<?php  echo $i; ?>" class="row_table_<?php  echo $i; ?>  form-control choose_asset_no_order_req order_req_<?php  echo $i; ?> order_req_asset_no_<?php  echo $i; ?>" id="" style="padding: 0px !important; cursor: pointer; height:96% !important;width: 100% !important; border: none !important; -webkit-box-shadow: none !important; box-shadow: none !important; display: none;">
														<textarea style="cursor: pointer !important; width: 100% !important; height: 100% !important; " class="row_table order_requisition_form_textarea_col2 order_req_asset_number_<?php echo $i; ?>" readonly></textarea>
													</div>
														<textarea name="order_req_asset_no_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="8" class="row_table_<?php  echo $i; ?> row_table order_requisition_form_textarea_col2 choose_asset_no_order_req_<?php  echo $i; ?>" style="width: 100% !important; height:96% !important; display:none;" readonly> </textarea>
							   	 			</div>

                        <div class="text_area_wrapper_height_<?php  echo $i; ?> equipment_cost_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 equipment_cost_col" style="height:48px;border-top:0px !important; border-right:0px !important; padding-top:2px;">
							   	 				<textarea name="order_req_equipment_cost_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="8" class="row_table_<?php  echo $i; ?> row_table order_requisition_form_textarea_col2 order_req_<?php  echo $i; ?> order_req_equipment_cost_<?php  echo $i; ?>" style="width: 100% !important;" readonly> </textarea>
							   	 			</div>
                        <div class="text_area_wrapper_height_<?php  echo $i; ?> total_cost_wrapper_<?php echo $i; ?> col-xs-1 col-sm-1 col-md-1 order_requisition_item_details_v2 total_cost_col" style="height:48px;border-top:0px !important;padding-top:2px;">
							   	 				<textarea name="order_req_total_cost_<?php  echo $i; ?>" data-row-id="<?php  echo $i; ?>" data-col-id="8" class="row_table_<?php  echo $i; ?> row_table item_total_cost order_requisition_form_textarea_col2 item_total_cost_<?php  echo $i; ?> order_req_total_cost_<?php  echo $i; ?>" style="width: 100% !important;" readonly> </textarea>
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


				   	 		<div class="row order_requisition_details_row" style="width:1971px;margin-top:5px !important; margin-top: 15px !important">
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
				   	 				<strong>Representative Created Order:</strong> &nbsp; <?php echo $this->session->userdata('lastname'); ?>, <?php echo $this->session->userdata('firstname'); ?>
										<!-- <span> <hr style=" width: 220px; border-color: #292626; margin-left: 213px; margin-top: -2px;"/> </span> -->
										<input type="hidden" name="person_placing_order" value="<?php echo $this->session->userdata('lastname'); ?>, <?php echo $this->session->userdata('firstname'); ?>">
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
          <div class="col-xs-1 col-sm-1 col-md-1 hidden-print" style="width:1.33333333% !important"> </div>
		    	<div class="row print_order_requisition hidden-print">
		    		<div style="">
		    			<?php
                          if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') {
                              ?>
                              <div style="width:1954px;">
				    		<button type="button" class="btn btn-success btn-save-equip-transfer-req pull-right" style=""> Submit </button>
                </div>
				    	<?php
                          }?>

		    		</div>
		    		<!-- <div>
			    		<button type="button" class="btn btn-draft pull-right btn-draft-order-req" style=""> Save Order as Draft </button>
		    		</div> -->
		    	</div>
		    	<?php echo form_close(); ?>
		    </div>
		</div>
	</div>
</div>

<div class="modal fade" id="serial_asset_no_modal" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="width: 700px">
			<div class="modal-content" style="width: 700px !important; margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
					<div class="modal-header">
						<!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
						<div class="row modal-title OpenSans-Reg">
							<h4 class="col-xs-9 col-sm-9 col-md-9 " id="myModalLabel">Select Serial Number and Asset Number</h4>
							<div class="col-xs-3 col-sm-3 col-md-3 pull-right" style="font-size: 18px; margin-top: 10px; font-weight: bold !important">
								<span class="serial_asset_limit_items pull-right"></span>
								<span class="pull-right">&nbsp;/&nbsp;</span>
								<span class="serial_asset_selected_items pull-right"></span>
							</div>
						</div>

					</div>
					<div class="modal-body OpenSans-Reg equipments_modal pt40 pb40" style="">
						<table class="table m-b-none bg-white b-a table-hover datatable_table_equip_transfer_inquiry" style="text-align: center">
							<thead style="background-color:rgba(97, 101, 115, 0.05);">
							  <tr>
									<th style="width:10%; text-align: center;"></th>
									<th style="width:40%; text-align: center;">Serial Number</th>
									<th style="width:40%; text-align: center;">Asset Number</th>
								</tr>
							</thead>
							<tbody class="serial_asset_no_list_body">
								<tr>
									<td><input type="checkbox"></td>
									<td>123</td>
									<td>456</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
							<button type="button" class="btn btn-default btn-order-close" data-dismiss="modal">Close</button>
					</div>
			</div>
	</div>
</div>

<div class="modal fade" id="all_items_modal" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9990">
	<div class="modal-dialog" style="width: 1200px">
		<div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
			<div class="modal-header">
				<!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
				<div class="row modal-title OpenSans-Reg">
					<h4 class="col-xs-9 col-sm-9 col-md-9 " id="myModalLabel">Select Item/s</h4>
					<div class="col-xs-3 col-sm-3 col-md-3 pull-right" style="font-size: 18px; margin-top: 10px; font-weight: bold !important">
<!-- 								<span class="serial_asset_limit_items pull-right"></span>
						<span class="pull-right">&nbsp;/&nbsp;</span>
						<span class="serial_asset_selected_items pull-right"></span>
-->							</div>
				</div>

			</div>
			<div class="modal-body OpenSans-Reg pt40 pb40" style="">
				<table class="table m-b-none bg-white b-a table-hover datatable_table_all_items_inquiry" style="text-align: center">
					<thead style="background-color:rgba(97, 101, 115, 0.05);">
					  <tr>
							<th style="width:10%; text-align: center;"></th>
							<th style="width:10%; text-align: center;"> Company Item No</th>
							<th style="width:20%; text-align: center;">Item Description</th>
							<th style="width:15%; text-align: center;">Vendor</th>
							<th style="width:10%; text-align: center;">Reorder Number</th>
							<th style="width:10%; text-align: center;">Unit of Measure</th>
							<th style="width:10%; text-align: center;">Serial Number</th>
							<th style="width:10%; text-align: center;">Asset Number</th>
							<th style="width:5%; text-align: center;">Equipment Cost</th>
						</tr>
					</thead>
					<tbody class="">
						<!-- <tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>O53208</td>
							<td>OXYGEN E CART</td>
							<td>DRIVE MEDICAL</td>
							<td>1002SV-6</td>
							<td>case</td>
							<td>123</td>
							<td>456</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>O53208</td>
							<td>OXYGEN E CART</td>
							<td>DRIVE MEDICAL</td>
							<td>1002SV-6</td>
							<td>each</td>
							<td>2342</td>
							<td>3545</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>O53208</td>
							<td>OXYGEN E CART</td>
							<td>COMPASS HEALTH</td>
							<td>E CART</td>
							<td>case</td>
							<td>7878</td>
							<td>2342</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>O53208</td>
							<td>OXYGEN E CART</td>
							<td>INVACARE</td>
							<td>E CART</td>
							<td>case</td>
							<td>4353</td>
							<td>5662</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>Y5324</td>
							<td>PATIENT LIFT</td>
							<td>COMPASS HEALTH</td>
							<td>10023-h5</td>
							<td>case</td>
							<td>345</td>
							<td>56622</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>T23432</td>
							<td>OXYGEN E CART</td>
							<td>DRIVE MEDICAL</td>
							<td>10023-h5</td>
							<td>case</td>
							<td>4353</td>
							<td>5662</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>I3423</td>
							<td>SCALE PATIENT LIFT</td>
							<td>PROACTIVE</td>
							<td>10023-h5</td>
							<td>each</td>
							<td>123432</td>
							<td>34645</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>E453534</td>
							<td>SUCTION MACHINE</td>
							<td>INVACARE</td>
							<td>10023-h5</td>
							<td>each</td>
							<td>2342</td>
							<td>3454563</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>H32423</td>
							<td>WHEEL CHAIR</td>
							<td>PROACTIVE</td>
							<td>E CART</td>
							<td>case</td>
							<td>3456</td>
							<td>65875</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>K32423</td>
							<td>VENTILATOR</td>
							<td>PROACTIVE</td>
							<td>50gd-23</td>
							<td>each</td>
							<td>5674</td>
							<td>56575</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>K32423</td>
							<td>TRAPEZE BAR</td>
							<td>COMPASS HEALTH</td>
							<td>1006-V23</td>
							<td>case</td>
							<td>3456</td>
							<td>2345</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>J32423</td>
							<td>GEL MATTRESS</td>
							<td>PROACTIVE</td>
							<td>E CART</td>
							<td>each</td>
							<td>234567</td>
							<td>4564</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>J32423</td>
							<td>BEDSIDE COMMODE 3 IN 1</td>
							<td>DRIVE MEDICAL</td>
							<td>E CART</td>
							<td>case</td>
							<td>233411</td>
							<td>6578</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>J32423</td>
							<td>OVER BED TABLE</td>
							<td>PROACTIVE</td>
							<td>1230-VSD2</td>
							<td>each</td>
							<td>12353</td>
							<td>678755</td>
						</tr>
						<tr>
							<td>
								<label class="i-checks data_tooltip" >
                                	<input type="checkbox"name=""/>
                                	<i></i>
                            	</label>
                            </td>
                            <td>J32423</td>
							<td>BED AND CHAIR ALARM</td>
							<td>COMPASS HEALTH</td>
							<td>23421-VSD2</td>
							<td>case</td>
							<td>45632</td>
							<td>657566</td>
						</tr> -->
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(){

		$('.datatable_all_items').DataTable( {
			"order": [[ 0, "desc" ]],
			"columnDefs": [
				{ "searchable": true,
					"targets": [1]
				}
			],
			"pageLength": 10

		});

		$('.input_tobe_masked').mask("(999) 999-9999");

		$('.edit_vendor_date').datepicker({
   			dateFormat: 'mm/dd/yy'
  		});

		//btn-save-equip-transfer-req
		$('.btn-save-equip-transfer-req').bind('click',function(){
			var form_data = $('.equip_transfer_req_form').serialize();
			var total_item_count = $('#item_count').val();

			jConfirm("Save Equipment Transfer Requisition?","Reminder",function(response){
				if(response)
    			{
    				$(this).prop("disabled","true");
					$.post(base_url+"inventory/save_equipment_transfer/" + total_item_count,form_data, function(response){

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

		//company_item_no_col
		var globalTimeout = null;
		$('.company_item_no_textarea').bind('keyup',function(){
			var _this = $(this);
			var company_no = $(this).val().replace(/\s+/g, '');
			var location = $(".location").val();
			var item_counter = $("#item_count").val();
			//item_no = item_no.replace(/\s+/g, '');
			var row_id = $(this).attr("data-row-id");

			var item_description = $("body").find(".order_req_item_desc_"+row_id).val().replace(/\s+/g, '');
			if(company_no != "" && company_no.length > 2)
			{
				if(globalTimeout != null) clearTimeout(globalTimeout);
			    globalTimeout =setTimeout(getInfoFunc,1100);



			  function getInfoFunc(){
					globalTimeout = null;


					// $("body").find(".order_req_item_desc_"+row_id).hide();
					// $("body").find(".order_req_vendor_no_"+row_id).hide();
					$("body").find(".item_decription_spinner").remove();
					$("body").find(".item_vendor_no_spinner").remove();
					$("body").find(".item_description_wrapper_"+row_id).append('<i class="fa fa-spin fa-spinner item_decription_spinner"></i>');
					$("body").find(".item_vendor_no_wrapper_"+row_id).append('<i class="fa fa-spin fa-spinner item_vendor_no_spinner"></i>');
					$.post(base_url+"inventory/get_searched_item_equip_transfer_by_company_item_no/"+company_no+"/"+location,"", function(response){
						var obj = $.parseJSON(response);
						console.log(obj);
						if(obj.item_details.length != 0) {
							$("body").find(".order_req_item_desc_"+row_id).val(obj.item_description);
							$("body").find(".order_req_item_no_"+row_id).val(obj.item_id);
							var vendor_options = '<option value=""> [--Choose Vendor--] </option>';
							var vendor_item_nos = '';
							for(var i = 0; i < obj.item_details.length; i++){
								vendor_options += '<option value="' + obj.item_details[i].vendor_id + '">' +  obj.item_details[i].vendor_name +'</option>';
								vendor_item_nos +=  '<input type="hidden" class="order_item_no_' + obj.item_details[i].vendor_id + '" value="' + obj.item_details[i].item_id + '">';
							}
							$("body").find(".order_req_vendor_no_"+row_id).html(vendor_options);
							$("body").find(".vendors_item_no_" + row_id).html(vendor_item_nos);
							$("body").find(".order_req_vendor_no_"+row_id).show();
							$("body").find(".order_req_item_desc_"+row_id).show();
							$("body").find(".item_decription_spinner").remove();
							$("body").find(".item_vendor_no_spinner").remove();
							item_counter++;
							$("#item_count").val(item_counter);
						} else {
							$("body").find(".item_decription_spinner").remove();
							$("body").find(".item_vendor_no_spinner").remove();
						}
					});
				}


			} else {

				if(item_description != "") {
					$("body").find(".order_req_item_desc_"+row_id).val(" ");
					item_counter--;
					$("#item_count").val(item_counter);
				}

				$("body").find(".order_req_vendor_no_"+row_id).hide();
				$("body").find(".order_req_reorder_no_"+row_id).hide();
				$("body").find(".order_req_item_unit_measurement_"+row_id).hide();
				$("body").find(".quantity_limit_wrapper_"+row_id).hide();
				$("body").find(".serial_asset_no_selected_"+row_id).html("");
				$("body").find(".serial_asset_seletected_"+row_id).val(0);

				$("body").find(".order_req_quantity_ordered_"+row_id).hide();
				$("body").find(".order_req_serial_no_"+row_id).hide();
				$("body").find(".order_req_asset_no_"+row_id).hide();
				$("body").find(".order_req_equipment_cost_"+row_id).hide();
			}
		});

		var globalTimeout = null;
		$('.company_item_desc_textarea').bind('keyup',function(){
			var _this = $(this);
			var item_description = $(this).val();

			console.log(item_description);
			var location = $(".location").val();
			var item_counter = $("#item_count").val();
			//item_no = item_no.replace(/\s+/g, '');
			var row_id = $(this).attr("data-row-id");
			var company_no = $("body").find(".order_req_company_id_"+row_id).val().replace(/\s+/g, '');
			if(item_description != "" && item_description.length > 3)
			{
				if(globalTimeout != null) clearTimeout(globalTimeout);
			    globalTimeout =setTimeout(getInfoFunc,1100);

			  function getInfoFunc(){
					globalTimeout = null;

					// $("body").find(".order_req_item_desc_"+row_id).hide();
					// $("body").find(".order_req_vendor_no_"+row_id).hide();
					$("body").find(".company_item_no_spinner").remove();
					$("body").find(".item_vendor_no_spinner").remove();
					$("body").find(".company_item_no_wrapper_"+row_id).append('<i class="fa fa-spin fa-spinner company_item_no_spinner"></i>');
					$("body").find(".item_vendor_no_wrapper_"+row_id).append('<i class="fa fa-spin fa-spinner item_vendor_no_spinner"></i>');

					$.post(base_url+"inventory/get_searched_item_equip_transfer_by_item_description/"+item_description+"/"+location,"", function(response){
						var obj = $.parseJSON(response);
						console.log(obj);
						if(obj.item_details.length != 0) {
							console.log('gwapaossadasdsa');
							$("body").find(".order_req_item_desc_"+row_id).val(obj.item_description);
							$("body").find(".order_req_company_id_"+row_id).val(obj.company_item_no);
							$("body").find(".order_req_item_no_"+row_id).val(obj.item_id);
							var vendor_options = '<option value=""> [--Choose Vendor--] </option>';
							var vendor_item_nos = '';
							for(var i = 0; i < obj.item_details.length; i++){
								vendor_options += '<option value="' + obj.item_details[i].vendor_id + '">' +  obj.item_details[i].vendor_name +'</option>';
								vendor_item_nos +=  '<input type="hidden" class="order_item_no_' + obj.item_details[i].vendor_id + '" value="' + obj.item_details[i].item_id + '">';
							}
							$("body").find(".order_req_vendor_no_"+row_id).html(vendor_options);
							$("body").find(".vendors_item_no_" + row_id).html(vendor_item_nos);
							$("body").find(".order_req_vendor_no_"+row_id).show();
							$("body").find(".company_item_no_spinner").remove();
							$("body").find(".item_vendor_no_spinner").remove();

							item_counter++;
							$("#item_count").val(item_counter);

						} else {
							$("body").find(".company_item_no_spinner").remove();
							$("body").find(".item_vendor_no_spinner").remove();
						}

					});
				}


			} else {

				if(company_no != "") {
					$("body").find(".order_req_company_id_"+row_id).val(" ");
					item_counter--;
					$("#item_count").val(item_counter);
				}
				$("body").find(".order_req_vendor_no_"+row_id).hide();
				$("body").find(".order_req_reorder_no_"+row_id).hide();
				$("body").find(".order_req_item_unit_measurement_"+row_id).hide();
				$("body").find(".quantity_limit_wrapper_"+row_id).hide();
				$("body").find(".serial_asset_no_selected_"+row_id).html("");
				$("body").find(".serial_asset_seletected_"+row_id).val(0);

				$("body").find(".order_req_quantity_ordered_"+row_id).hide();
				$("body").find(".order_req_serial_no_"+row_id).hide();
				$("body").find(".order_req_asset_no_"+row_id).hide();
				$("body").find(".order_req_equipment_cost_"+row_id).hide();
			}
		});

		//choose_item_vendor_order_req
		globalTimeout = null;
		$('body').on('change','.choose_item_vendor_order_req',function(){
			var _this = $(this);
			var vendor_id = _this.val();
			var item_no = $(".order_item_no_"+vendor_id).val();
			var location = $(".location").val();
			var row_id = $(this).attr("data-row-id");
			var company_no = $(".order_req_company_id_"+row_id).val().replace(/\s+/g, '');

			if(globalTimeout != null) clearTimeout(globalTimeout);
				globalTimeout =setTimeout(getInfoFunc,1100);

			function getInfoFunc(){
				globalTimeout = null;

				$("body").find(".order_req_reorder_no_"+row_id).hide();
				$("body").find(".item_reorder_no_spinner").remove();
				$("body").find(".item_reorder_no_wrapper_"+row_id).append('<i class="fa fa-spin fa-spinner item_reorder_no_spinner"></i>');

				$.post(base_url+"inventory/get_reorder_no_by_vendor/"+company_no+"/"+vendor_id,"", function(response){
					var obj = $.parseJSON(response);
					console.log(obj);

					$('body').find('.selected_item_id_row_'+row_id).val(item_no);
					var reorder_no_options = '<option value=""> [--Choose Reorder No--] </option>';
					for(var i = 0; i < obj.item_reorder_nos.length; i++){
						reorder_no_options += '<option value="' + obj.item_reorder_nos[i].item_id + '">' +  obj.item_reorder_nos[i].item_reorder_no +'</option>';
					}
					$("body").find(".order_req_reorder_no_"+row_id).html(reorder_no_options);
					$("body").find(".order_req_reorder_no_"+row_id).show();
					$("body").find(".order_req_item_unit_measurement_"+row_id).hide();
					$("body").find(".order_req_quantity_ordered_"+row_id).hide();
					$("body").find(".order_req_serial_no_"+row_id).hide();
					$("body").find(".order_req_asset_no_"+row_id).hide();
					$("body").find(".order_req_equipment_cost_"+row_id).hide();
					$("body").find(".item_reorder_no_spinner").remove();

				});
			}
		});

		//choose_reorder_no_order_req
		globalTimeout = null;
		$('body').on('change','.choose_reorder_no_order_req',function(){
			var _this = $(this);

			var item_no = $(this).val();
			var location = $(".location").val();
			var row_id = $(this).attr("data-row-id");
			var company_no = $(".order_req_company_id_"+row_id).val().replace(/\s+/g, '');

			if(globalTimeout != null) clearTimeout(globalTimeout);
				globalTimeout =setTimeout(getInfoFunc,1100);

			function getInfoFunc(){
				globalTimeout = null;

				$("body").find(".order_req_item_unit_measurement_"+row_id).hide();
				$("body").find(".item_unit_measure_spinner").remove();
				$("body").find(".item_unit_measurement_wrapper_"+row_id).append('<i class="fa fa-spin fa-spinner item_unit_measure_spinner"></i>');

				$.post(base_url+"inventory/get_item_unit_measurement_by_item_id/"+item_no,"", function(response){
					var obj = $.parseJSON(response);
					console.log(obj);
					$('body').find('.selected_item_id_row_'+row_id).val(item_no);
					var item_unit_measurement_options = '<option value=""> [--Choose Unit of Measurement--] </option>';
					for(var i = 0; i < obj.item_unit_measurements.length; i++){
						if(obj.item_unit_measurements[i].item_unit_measure != "") {
							item_unit_measurement_options += '<option value="' + obj.item_unit_measurements[i].item_unit_measure + '">' +  obj.item_unit_measurements[i].item_unit_measure +'</option>';
						}
					}

					$("body").find(".reorder_item_no_"+row_id).val(item_no);
					$("body").find(".order_req_item_unit_measurement_"+row_id).html(item_unit_measurement_options);
					$("body").find(".order_req_item_unit_measurement_"+row_id).show();
					$("body").find(".item_unit_measure_spinner").remove();

				});
			}
		});


		//order_req_item_unit_measurement_
		globalTimeout = null;
		$('body').on('change','.choose_item_unit_measurement_order_req',function(){
			var _this = $(this);
			var item_unit_measurement = _this.val();
			var location = $(".location").val();
			var row_id = $(this).attr("data-row-id");
			var company_no = $(".order_req_company_id_"+row_id).val().replace(/\s+/g, '');
			var item_no = $(".reorder_item_no_"+row_id).val();

			if(globalTimeout != null) clearTimeout(globalTimeout);
				globalTimeout =setTimeout(getInfoFunc,1100);

			function getInfoFunc(){
				globalTimeout = null;

				$("body").find(".order_req_quantity_ordered_"+row_id).hide();
				$("body").find(".order_req_equipment_cost_"+row_id).hide();
				$("body").find(".equipment_cost_spinner").remove();
				$("body").find(".equipment_cost_spinner").remove();
				$("body").find(".quantity_ordered_wrapper_"+row_id).append('<i class="fa fa-spin fa-spinner quantity_ordered_spinner"></i>');
				$("body").find(".equipment_cost_wrapper_"+row_id).append('<i class="fa fa-spin fa-spinner equipment_cost_spinner"></i>');

				$.post(base_url+"inventory/get_serial_asset_no_by_item_id/"+item_no+"/"+item_unit_measurement+"/"+company_no+"/"+location,"", function(response){
					var obj = $.parseJSON(response);
					console.log(obj);

					var serial_asset_list = "";
					// for(var i = 0; i < obj.serial_asset_nos.length; i++){
					// 	if(obj.serial_asset_nos[i].item_unit_measure == item_unit_measurement) {
					// 		serial_asset_list += '<tr>';
					// 		serial_asset_list += '<td><label class="i-checks"><input type="checkbox" data-item-cost="'+ obj.equipment_cost.item_vendor_cost +'" data-row-id="'+ row_id +'" class="serial_asset_checkbox" value="' + obj.serial_asset_nos[i].inventory_item_id + '"><i></i></label></td>';
					// 		serial_asset_list += '<td><input type="hidden" class="serial_no_' + row_id + '_' + obj.serial_asset_nos[i].inventory_item_id + '" value="' + obj.serial_asset_nos[i].item_serial_no + '">' + obj.serial_asset_nos[i].item_serial_no + '</td>';
					// 		serial_asset_list += '<td><input type="hidden" class="asset_no_' + row_id + '_' + obj.serial_asset_nos[i].inventory_item_id + '" value="' + obj.serial_asset_nos[i].item_asset_no + '">' + obj.serial_asset_nos[i].item_asset_no + '</td>';
					// 		serial_asset_list += '</tr>';
					// 	}
					// }


					$('.order_req_quantity_ordered_'+row_id).attr('data-html','true');
					$('.order_req_quantity_ordered_'+row_id).attr('data-toggle','popover');
					$('.order_req_quantity_ordered_'+row_id).attr('data-trigger','focus');
					$('.order_req_quantity_ordered_'+row_id).attr('data-placement','right');
					$('.order_req_quantity_ordered_'+row_id).attr('data-quantity-limit','0');
					$('.order_req_quantity_ordered_'+row_id).attr('data-content','Maximum of 0');
					var et_datatable = $(".datatable_table_equip_transfer_inquiry").dataTable();
					et_datatable.fnDestroy();
					$("body").find(".serial_asset_no_list_body").html(serial_asset_list);
					$("body").find(".order_req_serial_no_"+row_id).attr("data-item-measurement", item_unit_measurement);
					$("body").find(".order_req_asset_number_").attr("data-item-measurement", _this.val());
					// $('body').find('.order_req_serial_number_'+row_id).val('[--Click to select Serial Number--]');
					// $('body').find('.order_req_asset_number_'+row_id).val('[--Click to select Asset Number--]');
					// $("body").find(".order_req_serial_no_"+row_id).show();
					// $("body").find(".order_req_asset_no_"+row_id).show();
					$("body").find(".order_req_quantity_ordered_"+row_id).show();
					$("body").find(".quantity_limit_"+row_id).val(obj.serial_asset_nos.length);
					$("body").find(".quantity_limit_wrapper_"+row_id).show();
					$("body").find(".order_req_equipment_cost_"+row_id).val(obj.equipment_cost.item_vendor_cost);
					$("body").find(".order_req_equipment_cost_"+row_id).show();
					$("body").find(".quantity_ordered_spinner").remove();
					$("body").find(".order_req_quantity_ordered_"+row_id).attr("data-content", "Maximum of "+obj.serial_asset_nos.length);

					$("body").find(".equipment_cost_spinner").remove();
					$('.datatable_table_equip_transfer_inquiry').DataTable( {
						"order": [[ 0, "desc" ]],
						"columnDefs": [
							{ "searchable": true,
								"targets": [1]
							}
						],
						"pageLength": 10

					});
				});
			}
		});

		//company_quantity_ordered_textarea
		globalTimeout = null;
		$('.company_quantity_ordered_textarea').bind('keyup',function(){
			var _this = $(this);
			var quantity_ordered = _this.val().replace(/\s+/g, '');
			var location = $(".location").val();
			var row_id = $(this).attr("data-row-id");
			var company_no = $(".order_req_company_id_"+row_id).val().replace(/\s+/g, '');
			var item_no = $(".reorder_item_no_"+row_id).val();
			var item_cost = $(".order_req_equipment_cost_"+row_id).val();
			var limit = $('body').find('.quantity_limit_'+row_id).val();
			if(parseInt(quantity_ordered) > limit) {
				jAlert("Quantity inputted exceeded!","Reminder");
				_this.val(" ");
				return;
			}


			if(globalTimeout != null) clearTimeout(globalTimeout);
				globalTimeout =setTimeout(getInfoFunc,1100);

			function getInfoFunc(){
				globalTimeout = null;
				var totalCost = 0;

				totalCost = Number(item_cost) * Number(quantity_ordered);
				$("body").find(".order_req_total_cost_"+row_id).val(totalCost);

				var serial_asset_list = "";
				var et_datatable = $(".datatable_table_equip_transfer_inquiry").dataTable();
				et_datatable.fnDestroy();
				$("body").find(".serial_asset_no_list_body").html(serial_asset_list);

				$("body").find(".equipment_cost_spinner").remove();
				$('.datatable_table_equip_transfer_inquiry').DataTable( {
					"order": [[ 0, "desc" ]],
					"columnDefs": [
						{ "searchable": true,
							"targets": [1]
						}
					],
					"pageLength": 10

				});
				var grand_total = 0;
				$.each($("body").find(".item_total_cost"), function(){
					grand_total += Number($(this).val());
				});
				$("body").find(".order_req_grand_total_amount").val(grand_total);

				$('body').find('.order_req_serial_number_'+row_id).val('[--Click to select Serial Number--]');
				$('body').find('.order_req_asset_number_'+row_id).val('[--Click to select Asset Number--]');
				$("body").find(".order_req_serial_no_"+row_id).show();
				$("body").find(".order_req_asset_no_"+row_id).show();

				//serial_asset_no_selected_1
				$("body").find(".serial_asset_no_selected_"+row_id).html("");
			}
		});

		//order_req_serial_no_
		$('body').on('click','.choose_serial_no_order_req', function () {
			var _this = $(this);
			var row_id = $(this).attr("data-row-id");
			var item_unit_measurement = $('body').find('.order_req_serial_no_'+row_id).attr('data-item-measurement');
			var location = $(".location").val();
			var company_no = $(".order_req_company_id_"+row_id).val().replace(/\s+/g, '');
			var item_no = $(".reorder_item_no_"+row_id).val();

			if($('body').find('.order_req_serial_number_'+row_id).val() == "[--Click to select Serial Number--]") {
				$('body').find('.order_req_serial_number_'+row_id).val("");
				$('body').find('.order_req_asset_number_'+row_id).val("");
			}

			if(globalTimeout != null) clearTimeout(globalTimeout);
				globalTimeout =setTimeout(getInfoFunc,1100);

			function getInfoFunc(){
				globalTimeout = null;

				$("body").find(".dataTables_wrapper").hide();
				$("body").find(".datatable_spinner").remove();
				$("body").find(".equipments_modal").append('<div class="datatable_spinner" style="text-align: center; width: 100%; margin: 0; position: absolute;top: 50%; -ms-transform: translateY(-50%); transform: translateY(-50%);"><i class="fa fa-spin  fa-spinner " style="font-size: 50px"></i></div>');
				$.post(base_url+"inventory/get_serial_asset_no_by_item_id/"+item_no+"/"+item_unit_measurement+"/"+company_no+"/"+location,"", function(response){
					var obj = $.parseJSON(response);
					console.log(obj);

					var myForm = document.forms.equip_transfer_req_form;
					var serial_asset_no_list = document.getElementsByClassName('serial_asset_no_list_'+row_id);

					var serial_asset_list = "";
					var checked_serial_asset = false;
					for(var i = 0; i < obj.serial_asset_nos.length; i++){
						checked_serial_asset = false;
						if(obj.serial_asset_nos[i].item_unit_measure == item_unit_measurement) {
							for(var j = 0; j < serial_asset_no_list.length; j++){

								if(serial_asset_no_list[j].value == obj.serial_asset_nos[i].inventory_item_id) {
									checked_serial_asset = true;
									break;
								}
							}

							if(checked_serial_asset) {
								serial_asset_list += '<tr>';
								serial_asset_list += '<td><label class="i-checks"><input type="checkbox" data-item-cost="'+ obj.equipment_cost.item_vendor_cost +'" data-row-id="'+ row_id +'" class="serial_asset_checkbox" value="' + obj.serial_asset_nos[i].inventory_item_id + '" checked><i></i></label></td>';
								serial_asset_list += '<td><input type="hidden" class="serial_no_' + row_id + '_' + obj.serial_asset_nos[i].inventory_item_id + '" value="' + obj.serial_asset_nos[i].item_serial_no + '">' + obj.serial_asset_nos[i].item_serial_no + '</td>';
								serial_asset_list += '<td><input type="hidden" class="asset_no_' + row_id + '_' + obj.serial_asset_nos[i].inventory_item_id + '" value="' + obj.serial_asset_nos[i].item_asset_no + '">' + obj.serial_asset_nos[i].item_asset_no + '</td>';
								serial_asset_list += '</tr>';
							} else {
								serial_asset_list += '<tr>';
								serial_asset_list += '<td><label class="i-checks"><input type="checkbox" data-item-cost="'+ obj.equipment_cost.item_vendor_cost +'" data-row-id="'+ row_id +'" class="serial_asset_checkbox" value="' + obj.serial_asset_nos[i].inventory_item_id + '"><i></i></label></td>';
								serial_asset_list += '<td><input type="hidden" class="serial_no_' + row_id + '_' + obj.serial_asset_nos[i].inventory_item_id + '" value="' + obj.serial_asset_nos[i].item_serial_no + '">' + obj.serial_asset_nos[i].item_serial_no + '</td>';
								serial_asset_list += '<td><input type="hidden" class="asset_no_' + row_id + '_' + obj.serial_asset_nos[i].inventory_item_id + '" value="' + obj.serial_asset_nos[i].item_asset_no + '">' + obj.serial_asset_nos[i].item_asset_no + '</td>';
								serial_asset_list += '</tr>';
							}

						}
					}

					var et_datatable = $(".datatable_table_equip_transfer_inquiry").dataTable();
					et_datatable.fnDestroy();
					//serial_asset_selected_items
					var selected_count = $('body').find('.serial_asset_seletected_'+row_id).val();
					var limit = $('body').find('.order_req_quantity_ordered_'+row_id).val();
					$("body").find('.serial_asset_selected_items').html(selected_count);
					$("body").find('.serial_asset_limit_items').html(limit);
					$("body").find(".serial_asset_no_list_body").html(serial_asset_list);
					$("body").find(".quantity_limit_"+row_id).val(obj.serial_asset_nos.length);
					$("body").find(".quantity_limit_wrapper_"+row_id).show();
					$("body").find(".order_req_equipment_cost_"+row_id).val(obj.equipment_cost.item_vendor_cost);
					$("body").find(".order_req_equipment_cost_"+row_id).show();
					$("body").find(".dataTables_wrapper").show();
					$("body").find(".datatable_spinner").remove();

					$('.datatable_table_equip_transfer_inquiry').DataTable( {
						"order": [[ 0, "desc" ]],
						"columnDefs": [
							{ "searchable": true,
								"targets": [1]
							}
						],
						"pageLength": 10

					});
				});
			}
		});

		$('body').on('click','.choose_asset_no_order_req', function () {
			var _this = $(this);
			var row_id = $(this).attr("data-row-id");
			var item_unit_measurement = $('body').find('.order_req_serial_no_'+row_id).attr('data-item-measurement');
			var location = $(".location").val();
			var company_no = $(".order_req_company_id_"+row_id).val().replace(/\s+/g, '');
			var item_no = $(".reorder_item_no_"+row_id).val();

			if($('body').find('.order_req_asset_number_'+row_id).val() == "[--Click to select Asset Number--]") {
				$('body').find('.order_req_serial_number_'+row_id).val("");
				$('body').find('.order_req_asset_number_'+row_id).val("");
			}

			if(globalTimeout != null) clearTimeout(globalTimeout);
				globalTimeout =setTimeout(getInfoFunc,1100);

			function getInfoFunc(){
				globalTimeout = null;

				$("body").find(".dataTables_wrapper").hide();
				$("body").find(".datatable_spinner").remove();
				$("body").find(".equipments_modal").append('<i class="fa fa-spin fa-spinner datatable_spinner"></i>');
				$.post(base_url+"inventory/get_serial_asset_no_by_item_id/"+item_no+"/"+item_unit_measurement+"/"+company_no+"/"+location,"", function(response){
					var obj = $.parseJSON(response);
					console.log(obj);

					var myForm = document.forms.equip_transfer_req_form;
					var serial_asset_no_list = document.getElementsByClassName('serial_asset_no_list_'+row_id);

					var serial_asset_list = "";
					var checked_serial_asset = false;
					for(var i = 0; i < obj.serial_asset_nos.length; i++){
						checked_serial_asset = false;
						if(obj.serial_asset_nos[i].item_unit_measure == item_unit_measurement) {
							for(var j = 0; j < serial_asset_no_list.length; j++){

								if(serial_asset_no_list[j].value == obj.serial_asset_nos[i].inventory_item_id) {
									checked_serial_asset = true;
									break;
								}
							}

							if(checked_serial_asset) {
								serial_asset_list += '<tr>';
								serial_asset_list += '<td><label class="i-checks"><input type="checkbox" data-item-cost="'+ obj.equipment_cost.item_vendor_cost +'" data-row-id="'+ row_id +'" class="serial_asset_checkbox" value="' + obj.serial_asset_nos[i].inventory_item_id + '" checked><i></i></label></td>';
								serial_asset_list += '<td><input type="hidden" class="serial_no_' + row_id + '_' + obj.serial_asset_nos[i].inventory_item_id + '" value="' + obj.serial_asset_nos[i].item_serial_no + '">' + obj.serial_asset_nos[i].item_serial_no + '</td>';
								serial_asset_list += '<td><input type="hidden" class="asset_no_' + row_id + '_' + obj.serial_asset_nos[i].inventory_item_id + '" value="' + obj.serial_asset_nos[i].item_asset_no + '">' + obj.serial_asset_nos[i].item_asset_no + '</td>';
								serial_asset_list += '</tr>';
							} else {
								serial_asset_list += '<tr>';
								serial_asset_list += '<td><label class="i-checks"><input type="checkbox" data-item-cost="'+ obj.equipment_cost.item_vendor_cost +'" data-row-id="'+ row_id +'" class="serial_asset_checkbox" value="' + obj.serial_asset_nos[i].inventory_item_id + '"><i></i></label></td>';
								serial_asset_list += '<td><input type="hidden" class="serial_no_' + row_id + '_' + obj.serial_asset_nos[i].inventory_item_id + '" value="' + obj.serial_asset_nos[i].item_serial_no + '">' + obj.serial_asset_nos[i].item_serial_no + '</td>';
								serial_asset_list += '<td><input type="hidden" class="asset_no_' + row_id + '_' + obj.serial_asset_nos[i].inventory_item_id + '" value="' + obj.serial_asset_nos[i].item_asset_no + '">' + obj.serial_asset_nos[i].item_asset_no + '</td>';
								serial_asset_list += '</tr>';
							}

						}
					}

					var et_datatable = $(".datatable_table_equip_transfer_inquiry").dataTable();
					et_datatable.fnDestroy();
					var selected_count = $('body').find('.serial_asset_seletected_'+row_id).val();
					var limit = $('body').find('.order_req_quantity_ordered_'+row_id).val();
					$("body").find('.serial_asset_selected_items').html(selected_count);
					$("body").find('.serial_asset_limit_items').html(limit);
					$("body").find(".serial_asset_no_list_body").html(serial_asset_list);
					$("body").find(".quantity_limit_"+row_id).val(obj.serial_asset_nos.length);
					$("body").find(".quantity_limit_wrapper_"+row_id).show();
					$("body").find(".order_req_equipment_cost_"+row_id).val(obj.equipment_cost.item_vendor_cost);
					$("body").find(".order_req_equipment_cost_"+row_id).show();
					$("body").find(".dataTables_wrapper").show();
					$("body").find(".datatable_spinner").remove();

					$('.datatable_table_equip_transfer_inquiry').DataTable( {
						"order": [[ 0, "desc" ]],
						"columnDefs": [
							{ "searchable": true,
								"targets": [1]
							}
						],
						"pageLength": 10

					});
				});
			}
		});



		$('body').on('click','.serial_asset_checkbox', function () {
			var _this = $(this);
			var row_id = $(this).attr("data-row-id");
			var item_cost = $(this).attr("data-item-cost");

			//serial_asset_seletected_
			var selected_count = $('body').find('.serial_asset_seletected_'+row_id).val();

			//order_req_quantity_ordered_1
			var limit = $('body').find('.order_req_quantity_ordered_'+row_id).val();


			if(_this.is(':checked')) {
				selected_count++;
				if(selected_count <= limit) {
					$('body').find('.serial_asset_seletected_'+row_id).val(selected_count);
					$("body").find('.serial_asset_selected_items').html(selected_count);

					$('body').find('.serial_asset_no_selected_'+row_id).append('<input type="hidden" class="serial_asset_no_' + row_id + _this.val() + ' serial_asset_no_list_'+ row_id +'" name="serial_asset_no['+ row_id +'][]" value="'+ _this.val() +'">');

					if($('body').find('.order_req_serial_number_'+row_id).val() == "") {
						$('body').find('.order_req_serial_number_'+row_id).val($('.serial_no_'+row_id+'_'+_this.val()).val());
						$('body').find('.order_req_asset_number_'+row_id).val($('.asset_no_'+row_id+'_'+_this.val()).val());
					} else {
						var serial_no_selected = $('body').find('.order_req_serial_number_'+row_id).val() + ', ' + $('.serial_no_'+row_id+'_'+_this.val()).val();
						$('body').find('.order_req_serial_number_'+row_id).val(serial_no_selected);

						var asset_no_selected = $('body').find('.order_req_asset_number_'+row_id).val() + ', ' + $('.asset_no_'+row_id+'_'+_this.val()).val();
						$('body').find('.order_req_asset_number_'+row_id).val(asset_no_selected);
					}

					//$('body').find('.order_req_serial_number_'+row_id).height(47);
					// console.log('Adrian love Debbie');
					console.log(parseInt($('body').find('.order_req_serial_number_'+row_id).prop('scrollHeight'),10)-6);
					setTimeout(function() {
						var serial_scrollHeight = parseInt($('body').find('.order_req_serial_number_'+row_id).prop('scrollHeight'),10);
						var asset_scrollHeight = parseInt($('body').find('.order_req_asset_number_'+row_id).prop('scrollHeight'),10);
						if(serial_scrollHeight > asset_scrollHeight) {
							$('.text_area_wrapper_height_'+row_id).height(serial_scrollHeight-2);
							$('body').find('.row_table_'+row_id).height(serial_scrollHeight-5);
						} else {
							$('.text_area_wrapper_height_'+row_id).height(asset_scrollHeight-2);
							$('body').find('.row_table_'+row_id).height(asset_scrollHeight-5);
						}

					}, 1);


					// var myForm = document.forms.equip_transfer_req_form;
					// var serial_asset_no = document.getElementsByClassName('serial_asset_no_list_'+row_id);
					// var totalCost = 0;
					// console.log(serial_asset_no.length);

					// totalCost = parseInt(item_cost) * serial_asset_no.length;
					// $("body").find(".order_req_total_cost_"+row_id).val(totalCost);
					// var grand_total = 0;
					// $.each($("body").find(".item_total_cost"), function(){
					// 	grand_total += Number($(this).val());
					// });
					// $("body").find(".order_req_grand_total_amount").val(grand_total);
					// console.log(grand_total);
				} else {
					$(this).prop('checked', false);
					// alert("Reached the maximum number of items!");
					jAlert("You have reached the maximum number of items!","Reminder");
				}
			} else {
				selected_count--;
				$('body').find('.serial_asset_seletected_'+row_id).val(selected_count);
				$("body").find('.serial_asset_selected_items').html(selected_count);
				$('body').find('.serial_asset_no_'+row_id+_this.val()).remove();
			}

		});

		$('.datatable_table_all_items_inquiry').DataTable( {
			'createdRow': function( row, data, dataIndex ) {
                var item = JSON.stringify(data);
                for(var j = 1; j <= 14; j++) {
                	var selected_items = $('.serial_asset_no_list_'+j);
	                for(var k = 0; k < selected_items.length; k++) {
	                	if(selected_items[k].value == data.inventory_item_id) {
	                		$(row).find(".all_items_checkbox_"+(dataIndex+1)).prop('checked',true);

	                		break;
	                	}
	                }
                }
            },
			"lengthMenu": [10,25,50,75,100],
		    "pageLength": 10,
		    "processing": true,
		    "serverSide": true,
		    "responsive": true,
		    "deferRender": true,
		    "ajax": {
		        url: base_url+"inventory/get_equipment_transfer_all_items"
		    },
		    "columns": [
		    	{ "data": "checkboxed" },
		        { "data": "company_item_no" },
		        { "data": "item_description" },
		        { "data": "vendor_name" },
		        { "data": "item_reorder_no" },
		        { "data": "item_unit_measure" },
		        { "data": "item_serial_no" },
		        { "data": "item_asset_no" },
		        { "data": "item_cost" }
		    ],
	        "order": [[ 0, "desc" ]]
	    });

	    $('body').on('click','.all_items_checkbox', function() {
	    	var _this = $(this);
	    	var company_item_no = $(this).attr("data-company-item-no");
	    	var item_description = $(this).attr("data-item-description");
	    	var vendor_name = $(this).attr("data-vendor-name");
	    	var item_reorder_no = $(this).attr("data-item-reorder-no");
	    	var item_unit_measure = $(this).attr("data-item-unit-measure");
	    	var item_serial_no = $(this).attr("data-serial-number");
	    	var item_asset_no = $(this).attr("data-asset-number");
	    	var item_cost = $(this).attr("data-item-cost");
	    	var item_no = $(this).attr("data-item-no");
	    	var inventory_item_id = $(this).val();
	    	var is_existed = 1;

	    	for(i = 1; i <= 14; i++) {
	    		if($('.company_item_no_textarea_'+i).val() == company_item_no) {
	    			console.log("company");
	    			if($('.company_item_desc_textarea_'+i).val() == item_description){
	    				console.log("descrip");
	    				if($('.order_vendor_no_'+i).val() == vendor_name) {
	    					console.log(i+3);
	    					if($('.item_reorder_no_'+i).val() == item_reorder_no) {
	    						console.log('reorder');
	    						if($('.item_unit_measurement_'+i).val() == item_unit_measure) {
	    							console.log('measure');
	    							if($('.order_req_equipment_cost_'+i).val() == item_cost) {
	    								console.log('cost');
										var temp_qty = $('.order_req_quantity_ordered_'+i).val() *1;
										var temp_total_cost = $('.item_total_cost_'+i).val();


										if(_this.is(':checked')) {
											$('.order_req_quantity_ordered_'+i).val(temp_qty+1);
											$('.item_total_cost_'+i).val(((temp_total_cost*1) + (item_cost*1)).toFixed(2));
											var temp_serial = $('.choose_serial_no_order_req_'+i).val();
											var temp_asset = $('.choose_asset_no_order_req_'+i).val();
											$('.choose_serial_no_order_req_'+i).val(temp_serial+', '+item_serial_no);
											$('.choose_asset_no_order_req_'+i).val(temp_asset+', '+item_asset_no);
											setTimeout(function() {
												var serial_scrollHeight = parseInt($('body').find('.choose_serial_no_order_req_'+i).prop('scrollHeight'),10);
												var asset_scrollHeight = parseInt($('body').find('.choose_asset_no_order_req_'+i).prop('scrollHeight'),10);
												if(serial_scrollHeight > asset_scrollHeight) {
													$('.text_area_wrapper_height_'+i).height(serial_scrollHeight-2);
													$('body').find('.row_table_'+i).height(serial_scrollHeight-5);
												} else {
													$('.text_area_wrapper_height_'+i).height(asset_scrollHeight-2);
													$('body').find('.row_table_'+i).height(asset_scrollHeight-5);
												}
											}, 1);
											$('body').find('.serial_asset_no_selected_'+i).append('<input type="hidden" class="serial_asset_no_' + i + _this.val() + ' serial_asset_no_list_'+ i +'" name="serial_asset_no['+ i +'][]" value="'+ _this.val() +'">');
										} else {
											$('.order_req_quantity_ordered_'+i).val(temp_qty-1);
											$('.item_total_cost_'+i).val(((temp_total_cost*1) - (item_cost*1)).toFixed(2));
											if((temp_qty-1) == 0){
												$('.company_item_no_textarea_'+i).val("");
							    				$('.company_item_desc_textarea_'+i).val("");
							    				$('.order_vendor_no_'+i).val("");
							    				$('.item_reorder_no_'+i).val("");
							    				$('.item_unit_measurement_'+i).val("");
							    				$('.order_req_quantity_ordered_'+i).val("");
							    				$('.choose_serial_no_order_req_'+i).val("");
							    				$('.choose_asset_no_order_req_'+i).val("");
							    				$('.order_req_equipment_cost_'+i).val("");
							    				$('.item_total_cost_'+i).val("");
												$('.order_vendor_no_'+i).hide();
							    				$('.item_reorder_no_'+i).hide();
							    				$('.item_unit_measurement_'+i).hide();
							    				$('.order_req_quantity_ordered_'+i).hide();
							    				$('.choose_serial_no_order_req_'+i).hide();
							    				$('.choose_asset_no_order_req_'+i).hide();
							    				$('.order_req_equipment_cost_'+i).hide();
							    				$('.item_total_cost_'+i).hide();
											}

											var serials_temp = $('.choose_serial_no_order_req_'+i).val().split(",");
											var assets_temp = $('.choose_asset_no_order_req_'+i).val().split(",");
											//console.log(serials_temp);
											// for(var j = 0; j< serial) {

											// }
											var serial_asset_counter = 0;
											$.each($("body").find(".serial_asset_no_list_"+i), function(){
												if($(this).val() == inventory_item_id) {
													var new_serial_nos = "";
													var new_asset_nos = "";
													var serial_asset_check = 0;
													for(var j = 0; j < serials_temp.length; j++) {
														if(j != serial_asset_counter) {
															if(serial_asset_check == 0) {
																new_serial_nos = serials_temp[j];
																new_asset_nos = assets_temp[j];
																//console.log(new_serial_asset_nos);
																serial_asset_check = 1;
															} else {
																new_serial_nos = new_serial_nos + ", " + serials_temp[j];
																new_asset_nos = new_asset_nos + ", " + assets_temp[j];
															}
														}
													}
													$('.choose_serial_no_order_req_'+i).val(new_serial_nos);
													$('.choose_asset_no_order_req_'+i).val(new_asset_nos);
													$(this).remove();
												}
												serial_asset_counter++;
											});
										}

										break;
									} else {
				    					is_existed = 0;
				    				}
	    						} else {
			    					is_existed = 0;
			    				}
	    					} else {
		    					is_existed = 0;
		    				}
	    				} else {
	    					is_existed = 0;
	    				}
	    			}  else {
	    				is_existed = 0;
	    			}
	    		} else {
    				is_existed = 0;
    			}

    			if(is_existed == 0 && $('.company_item_no_textarea_'+i).val() == "" || $('.company_item_no_textarea_'+i).val() == " ") {
    				$('body').find('.selected_item_id_row_'+i).val(item_no);
    				$('.company_item_no_textarea_'+i).val(company_item_no);
    				$('.company_item_desc_textarea_'+i).val(item_description);
    				$('.order_vendor_no_'+i).val(vendor_name);
    				$('.item_reorder_no_'+i).val(item_reorder_no);
    				$('.item_unit_measurement_'+i).val(item_unit_measure);
    				$('.order_req_quantity_ordered_'+i).val(1);
    				$('.choose_serial_no_order_req_'+i).val(item_serial_no);
    				$('.choose_asset_no_order_req_'+i).val(item_asset_no);
    				$('.order_req_equipment_cost_'+i).val(item_cost);
    				$('.item_total_cost_'+i).val(item_cost);
    				$('.order_vendor_no_'+i).show();
    				$('.item_reorder_no_'+i).show();
    				$('.item_unit_measurement_'+i).show();
    				$('.order_req_quantity_ordered_'+i).show();
    				$('.choose_serial_no_order_req_'+i).show();
    				$('.choose_asset_no_order_req_'+i).show();
    				$('.order_req_equipment_cost_'+i).show();
    				$('.item_total_cost_'+i).show();
    				$('body').find('.serial_asset_no_selected_'+i).append('<input type="hidden" class="serial_asset_no_' + i + _this.val() + ' serial_asset_no_list_'+ i +'" name="serial_asset_no['+ i +'][]" value="'+ _this.val() +'">');
    				break;
    			}


	    	}

	    	var grand_total = 0;
			$.each($("body").find(".item_total_cost"), function(){
				grand_total += Number($(this).val());
			});
			$("body").find(".order_req_grand_total_amount").val(grand_total);
	    });
	});

</script>

