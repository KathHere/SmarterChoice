<input type="hidden" class="viewed_item_id" value="<?php echo $item_details['item_id']; ?>">
	<div class="wrapper-md item_details_container hidden-print">

		<a href="javascript:void(0)" class="edit_item_information" style="margin-left:5px !important;"><i class="fa fa-pencil"></i> Edit Info</a> &nbsp &nbsp &nbsp &nbsp

		<span id="item_activate_button_span">
		<?php
			if($item_details['item_active_sign'] == 1)
			{
		?>
				<label style="color:rgba(0, 0, 0, 0.75) !important;" class="i-checks"><input type="checkbox" class="item_activate_button" data-sign="0" ><i></i> Make inactive</label>
		<?php
			}
			else
			{
		?>
				<label style="color:rgba(0, 0, 0, 0.75) !important;" class="i-checks"><input type="checkbox" class="item_activate_button" data-sign="1" checked><i></i> Deactivated (Click to activate)</label>
		<?php
			}
		?>
		</span>
		&nbsp &nbsp &nbsp

		<?php
			$item_orders = get_item_orders($item_details['item_id']);
			if(!empty($item_orders))
			{
		?>
				<a href="javascript:void(0)" class="delete_item_details_disabled" title="Disabled" style="margin-left:5px !important;"><i class="fa fa-times"></i> Delete </a>
		<?php
			}
			else
			{
		?>
				<a href="javascript:void(0)" class="delete_item_details" data-vendor-id="<?php echo $item_details['vendor_id']; ?>" data-item-id="<?php echo $item_details['item_id']; ?>" style="margin-left:5px !important;"><i class="fa fa-times"></i> Delete </a>
		<?php
			}
		?>
		&nbsp &nbsp &nbsp &nbsp

	    <select id="item_reorder_no" class="form-control item_reorder_no_select" style="display: inline-block; width: 200px;">
	        <?php
	        	$temp = 0;
	            if($item_reorder_nos)
	            {
	                foreach ($item_reorder_nos as $key => $value)
	                {
	                	if($temp == $selected_item_reorder_no){
	                		$selected = "selected";
	                	}
	                	else
	                	{
	                		$selected = "";
	                	}
	        ?>
	                    <option data-companyno="<?php echo $company_item_no; ?>" data-vendor-id="<?php echo $item_details['vendor_id']; ?>" value="<?php echo $temp ?>"  class="item_reorder_no_option" <?php echo $selected; ?> >
		        			<?php echo $value['item_reorder_no']; ?>
	                    </option>
	        <?php
	        		 	$temp = $temp + 1;
	                }
	            }
	        ?>
	    </select>
	</div>
	<div id="item-details-content" class="well m-t bg-light lt">
		<div class="form-group clearfix">
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-address-card-o"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Vendor </label>
	                        <span id="vendor-name"> <?php echo $item_details['vendor_name']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-file-text-o"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Item Description </label>
	                        <span id="item-description"> <?php echo $item_details['item_description']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-list-ol"></i></div>
	                    <div class="dme-lists-text">
	                        <label> On Hand </label>
	                        <span id="on-hand"> <?php echo $total_on_hand; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
		</div>
		<div class="form-group clearfix">
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-hashtag"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Account Number </label>
	                        <span id="vendor-account-no"> <?php echo $item_details['item_vendor_acct_no']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-hashtag"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Company Item No. </label>
	                        <span id="company-item-no"> <?php echo $item_details['company_item_no']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-database"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Par Level </label>
	                        <span id="par-level"> <?php echo $item_details['item_par_level']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
		</div>
		<div class="form-group clearfix">
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-object-group"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Category </label>
	                        <span id="category-name"> <?php echo $item_details['item_category_name']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-map-marker"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Warehouse Location </label>
	                        <span id="warehouse-location"> <?php echo $item_details['item_warehouse_location']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-plus-square"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Added to Hospice Item List </label>
	                        <?php
	                        	$added_to_hospice_list = "No";
	                        	if($item_details['add_to_hospice_item_list'] == 1)
	                        	{
	                        		$added_to_hospice_list = "Yes";
	                        	}
	                        ?>
	                        <span id="added-hospice-list"> <?php echo $added_to_hospice_list; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
		</div>
		<?php
		$new_set_item_unit_of_measure['box'] = array();
		$new_set_item_unit_of_measure['each'] = array();
		$new_set_item_unit_of_measure['case'] = array();
		$new_set_item_unit_of_measure['pair'] = array();
		$new_set_item_unit_of_measure['pack'] = array();
		$new_set_item_unit_of_measure['package'] = array();
		$new_set_item_unit_of_measure['roll'] = array();
		if(!empty($item_unit_of_measures))
		{
		?>
			<div class="form-group clearfix" style="margin-bottom:0px !important;">
				<div class="col-sm-6 col-md-4">
					<ul class="dme-listing">
		                <li class="dme-lists">
		                    <div class="dme-lists-icon"><i class="fa fa-dropbox"></i></div>
		                    <div class="dme-lists-text">
		                        <label style="padding-top: 5px;"> Unit of Measure </label>
		                    </div>
		                </li>
		            </ul>
				</div>
				<div class="col-sm-6 col-md-4">
					<ul class="dme-listing">
		                <li class="dme-lists">
		                    <div class="dme-lists-icon"><i class="fa fa-usd"></i></div>
		                    <div class="dme-lists-text">
		                        <label style="padding-top: 5px;"> Vendor Cost </label>
		                    </div>
		                </li>
		            </ul>
				</div>
				<div class="col-sm-6 col-md-4">
					<ul class="dme-listing">
		                <li class="dme-lists">
		                    <div class="dme-lists-icon"><i class="fa fa-usd"></i></div>
		                    <div class="dme-lists-text">
		                        <label style="padding-top: 5px;"> Company Cost </label>
		                    </div>
		                </li>
		            </ul>
				</div>
			</div>

			<?php
			$loop_count = 1;
			foreach ($item_unit_of_measures as $key => $value){
				$unit_of_measure = "";
			?>
				<div class="form-group clearfix">
					<div class="col-sm-4 col-md-4 item_unit_of_measure_selected">
						<?php
						$new_set_item_unit_of_measure[$value['item_unit_measure']] = array(
							'item_unit_value' => $value['item_unit_value'],
							'item_vendor_cost' => $value['item_vendor_cost'],
							'item_company_cost' => $value['item_company_cost']
						);
						if($value['item_unit_measure'] == "box")
						{
							$unit_of_measure = "Box";
						}
						else if($value['item_unit_measure'] == "each")
						{
							$unit_of_measure = "Each";
						}
						else if($value['item_unit_measure'] == "case")
						{
							$unit_of_measure = "Case";
						}
						else if($value['item_unit_measure'] == "pair")
						{
							$unit_of_measure = "Pair";
						}
						else if($value['item_unit_measure'] == "pack")
						{
							$unit_of_measure = "Pack";
						}
						else if($value['item_unit_measure'] == "package")
						{
							$unit_of_measure = "Package";
						}
						else if($value['item_unit_measure'] == "roll")
						{
							$unit_of_measure = "Roll";
						}

						echo $unit_of_measure." ".$value['item_unit_value'];
						?>
					</div>
					<div id="vendor-cost" class="col-sm-4 col-md-4 item_unit_of_measure_selected">
						<?php echo number_format($value['item_vendor_cost'],2); ?>
					</div>
					<div id="company-cost" class="col-sm-4 col-md-4 item_unit_of_measure_selected">
						<?php echo number_format($value['item_company_cost'],2); ?>
					</div>
				</div>
			<?php
				if(count($item_unit_of_measures) >= $loop_count)
				{
			?>
					<hr class="unit_of_measure_hr" />
		<?php
				}
				$loop_count++;
			}
		}
		?>
		<div style="margin-bottom: 30px;">
		</div>
	</div>

	<!-- Modal Edit Item Information -->
	<div class="modal fade modal_edit_item_information" id="modal_edit_item_information" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
	        <div class="modal-content" style="border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
	        	<div class="modal-header">
	                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Edit Item Information</h4>
	            </div>
	            <div class="modal-body OpenSans-Reg">
	            	<form role="form" action="<?php echo base_url('inventory/edit_item_information_v2'); ?>" method="post" id="edit_item_information_form_validate" novalidate>
	                    <input type="hidden" name="viewed_item_id" value="<?php echo $item_details['item_id']; ?>">

	                    <div class="form-group clearfix">
	                        <div class="col-sm-3">
	                            <label> Company Item No. <span class="text-danger-dker">*</span></label>
	                            <input type="text" name="company_item_no" class="form-control edit_item_input" value="<?php echo $item_details['company_item_no']; ?>">
	                        </div>
	                    </div>
	                    <div class="form-group clearfix">
	                    	<div class="col-sm-6">
	                            <label> Item Description <span class="text-danger-dker">*</span></label>
	                            <input type="text" name="item_description" class="form-control edit_item_input" value='<?php echo $item_details['item_description']; ?>' >
	                        </div>
	                        <div class="col-sm-6 vendor_div">
	                            <label> Vendor <span class="text-danger-dker">*</span></label>
	                            <select name="item_vendor" class="form-control choose_item_vendor">
	                                <?php
	                                    if(!empty($vendor_list))
	                                    {
	                                        foreach ($vendor_list as $key => $value)
	                                        {
	                                        	if($item_details['item_vendor'] == $value['vendor_id'])
	                                        	{
	                                        		$selected = "selected";
	                                        	}
	                                        	else
	                                        	{
	                                        		$selected = "";
	                                        	}
	                                ?>
	                                            <option value="<?php echo $value['vendor_id']; ?>" <?php echo $selected; ?> > <?php echo $value['vendor_name']; ?> </option>
	                                <?php
	                                        }
	                                    }
	                                ?>
	                            </select>
	                        </div>
	 					</div>
	                    <div class="form-group clearfix">
	                        <div class="col-sm-3">
	                            <label> Account No. <span class="text-danger-dker">*</span></label>
	                            <input type="text" name="item_vendor_acct_no" class="form-control edit_item_input" value="<?php echo $item_details['item_vendor_acct_no']; ?>">
	                        </div>
	                        <div class="col-sm-3">
	                            <label> Warehouse Location <span class="text-danger-dker">*</span></label>
	                            <input type="text" name="item_warehouse_location" class="form-control edit_item_input" value="<?php echo $item_details['item_warehouse_location']; ?>">
	                        </div>
	                        <div class="col-sm-3">
	                            <label> Category <span class="text-danger-dker">*</span></label>
	                            <select name="item_category" class="form-control">
	                                <?php
	                                    if($item_category_list)
	                                    {
	                                        foreach ($item_category_list as $key => $value)
	                                        {
	                                        	if($item_details['item_category'] == $value['item_category_id'])
	                                        	{
	                                        		$item_category = "selected";
	                                        	}
	                                        	else
	                                        	{
	                                        		$item_category = "";
	                                        	}
	                                ?>
	                                            <option value="<?php echo $value['item_category_id']; ?>" <?php echo $item_category; ?>> <?php echo $value['item_category_name']; ?> </option>
	                                <?php
	                                        }
	                                    }
	                                ?>
	                            </select>
	                        </div>
	                        <div class="col-sm-3">
		                    	<label> Par Level <span class="text-danger-dker">*</span></label>
		                        <input type="number" name="item_par_level" class="form-control edit_item_input grey_inner_shadow" value="<?php echo $item_details['item_par_level']; ?>" >
		                    </div>
	                    </div>
	                    <div class="form-group clearfix">
	                    	<div class="col-sm-3">
	                            <label> Reorder Number <span class="text-danger-dker">*</span></label>
	                            <input type="text" name="item_reorder_no" class="form-control edit_item_input" value="<?php echo $item_details['item_reorder_no']; ?>">
	                        </div>
	                        <div class="col-sm-3">
	                            <label> Add to Hospice Item List <span class="text-danger-dker">*</span></label>
	                            <?php
	                            	if($item_details['add_to_hospice_item_list'] == 1)
	                            	{
	                            		$added = "checked";
	                            		$not_added = "";
	                            	}
	                            	else
	                            	{
	                            		$added = "";
	                            		$not_added = "checked";
	                            	}
	                            ?>
	                            <div class="radio">
	                                <label class="i-checks">
	                                    <input type="radio" name="add_to_hospice_item_list" value="1" <?php echo $added;?> /><i></i> Yes &nbsp &nbsp &nbsp
	                                </label>
	                                <label class="i-checks">
	                                    <input type="radio" name="add_to_hospice_item_list" value="0" <?php echo $not_added;?> /><i></i> No
	                                </label>
	                            </div>
	                        </div>
	                    </div>
	                    <div style="min-width: 600px !important;">
	                        <div class="form-group clearfix item_unit_of_measure_form_group_header">
	                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-right:0px !important;padding-top:8px;">
	                                Unit of Measure
	                            </div>
	                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-right:0px !important;padding-top:8px;">
	                                Value
	                            </div>
	                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-right:0px !important;padding-top:8px;">
	                                Vendor Cost
	                            </div>
	                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="padding-top:8px;">
	                                Company Cost
	                            </div>
	                        </div>
	                        <div class="form-group clearfix item_unit_of_measure_form_group_content">
	                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
	                                Box
	                            </div>
	                            <?php
	                            	if(!empty($new_set_item_unit_of_measure['box']))
	                            	{
	                           	?>
	                            		<div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_box" class="item_unit_of_measure_input" value="<?php echo $new_set_item_unit_of_measure['box']['item_unit_value']?>" >
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_box" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['box']['item_vendor_cost'],2); ?>" >
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_box" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['box']['item_company_cost'],2); ?>" >
			                            </div>
	                            <?php
	                            	}
	                            	else
	                            	{
	                            ?>
	                            		<div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_box" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_box" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_box" class="item_unit_of_measure_input">
			                            </div>
	                            <?php
	                            	}
	                            ?>
	                        </div>
	                        <div class="form-group clearfix item_unit_of_measure_form_group_content">
	                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
	                                Each (EA)
	                            </div>
	                            <?php
	                                if(!empty($new_set_item_unit_of_measure['each']))
	                                {
	                            ?>
	                            		<div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_each" class="item_unit_of_measure_input" value="<?php echo $new_set_item_unit_of_measure['each']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_each" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['each']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_each" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['each']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_each" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_each" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_each" class="item_unit_of_measure_input">
			                            </div>
	                            <?php
	                                }
	                            ?>
	                        </div>
	                        <div class="form-group clearfix item_unit_of_measure_form_group_content">
	                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
	                                Case
	                            </div>
	                            <?php
	                                if(!empty($new_set_item_unit_of_measure['case']))
	                                {
	                            ?>
	                            		<div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_case" class="item_unit_of_measure_input" value="<?php echo $new_set_item_unit_of_measure['case']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_case" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['case']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_case" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['case']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_case" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_case" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_case" class="item_unit_of_measure_input">
			                            </div>
	                            <?php
	                                }
	                            ?>
	                        </div>
	                        <div class="form-group clearfix item_unit_of_measure_form_group_content">
	                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
	                                Pair (PR)
	                            </div>
	                            <?php
	                                if(!empty($new_set_item_unit_of_measure['pair']))
	                                {
	                            ?>
	                            		<div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_pair" class="item_unit_of_measure_input" value="<?php echo $new_set_item_unit_of_measure['pair']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_pair" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['pair']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_pair" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['pair']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_pair" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_pair" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_pair" class="item_unit_of_measure_input">
			                            </div>
	                            <?php
	                                }
	                            ?>
	                        </div>
	                        <div class="form-group clearfix item_unit_of_measure_form_group_content">
	                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
	                                Pack (PK)
	                            </div>
	                            <?php
	                                if(!empty($new_set_item_unit_of_measure['pack']))
	                                {
	                            ?>
	                            		<div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_pack" class="item_unit_of_measure_input" value="<?php echo $new_set_item_unit_of_measure['pack']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_pack" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['pack']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_pack" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['pack']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_pack" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_pack" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_pack" class="item_unit_of_measure_input">
			                            </div>
	                            <?php
	                                }
	                            ?>
	                        </div>
	                        <div class="form-group clearfix item_unit_of_measure_form_group_content">
	                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
	                                Package (PKG)
	                            </div>
	                            <?php
	                                if(!empty($new_set_item_unit_of_measure['package']))
	                                {
	                            ?>
	                            		<div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_package" class="item_unit_of_measure_input" value="<?php echo $new_set_item_unit_of_measure['package']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_package" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['package']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_package" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['package']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_package" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_package" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_package" class="item_unit_of_measure_input">
			                            </div>
	                            <?php
	                                }
	                            ?>
	                        </div>
	                        <div class="form-group clearfix item_unit_of_measure_form_group_content">
	                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
	                                Roll (RL)
	                            </div>
	                            <?php
	                                if(!empty($new_set_item_unit_of_measure['roll']))
	                                {
	                            ?>
	                            		<div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_roll" class="item_unit_of_measure_input" value="<?php echo $new_set_item_unit_of_measure['roll']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_roll" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['roll']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_roll" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['roll']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_roll" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_roll" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_roll" class="item_unit_of_measure_input">
			                            </div>
	                            <?php
	                                }
	                            ?>

	                        </div>
	                    </div>
	            	</form>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default btn-edit-item-information-close">Cancel</button>
	                <button type="button" class="btn btn-success btn-save-edit-item-information">Save Information</button>
	            </div>
	        </div>
	    </div>
	</div>

	<!-- <div class="modal fade modal_reorder_item_information" id="modal_reorder_item_information" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-reorder">
	        <div class="modal-content" style="border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
	        	<div class="modal-header">
	                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Item Re-order Numbers</h4>
	            </div>
	            <div class="modal-body OpenSans-Reg">
	            	<div class="panel-body" style="padding-left:0px;padding-right:0px;padding-top:0px !important;padding-bottom:5px !important" >
						<div style="overflow-x: none;">
							<?php
							if(!empty($item_details)){
							?>
								<table class="table bg-white b-a datatable_table_inventory_item_reorder_no table-hover" style="margin-bottom: 0px !important">
							<?php
							}
							else
							{
							?>
								<table class="table bg-white b-a table-hover" style="margin-bottom: 0px !important">
							<?php
							}
							?>
				            	<thead style="background-color:rgba(97, 101, 115, 0.05);">
					            	<tr>
					            		<th style="width:40%;text-align:center;"><h5>Vendor</h5></th>
						            	<th style="width:38%;text-align:center;"><h5>Re-order Number</h5></th>
										<th style="width:22%;text-align:center;"><h5>Action</h5></th>
									</tr>
								</thead>
								<tbody>
					            	<?php
					            		foreach($item_list as $key => $value){
					            	?>
					            		<tr>
					            			<td style="width:40%;text-align:center;">
					            				<?php echo $value['vendor_name']; ?>
					            			</td>
					                    	<td class="" style="width:38%;text-align:center;">
							        			<a href="javascript:;" id="item_reorder_no"
								                    data-pk="<?php echo $value['item_id']; ?>"
								                    data-url="<?php echo base_url('inventory/update_reorder_no'); ?>"
								                    data-title="Item Reorder Number"
								                    data-value="<?php echo $value['item_reorder_no']; ?>"
								                    data-type="text"
								                    class="editable-click editable-itemlist"
							        			>
							        				<?php echo $value['item_reorder_no']; ?>
							        			</a>
							        		</td>
							        		<td style="width:22%;text-align:center;">
						                        <button class="btn btn-danger btn-xs btn-delete-item-reorder-no"
						                        	data-inventory-item-reorder-no="<?php echo $value['item_reorder_no']; ?>"
						                        	data-inventory-item-id="<?php echo $value['item_id']?>"
						                       	>
													<i class="fa fa-times"></i>
													Delete
												</button>
											</td>
					                    </tr>
					                <?php
					                	}
					            	?>
					            </tbody>
				        	</table>
				        </div>
					</div>
				</div>
				<div class="modal-footer">
	            	<button type="button" class="btn btn-default btn-edit-item-reorder-close">Close</button>
	        	</div>
			</div>
	    </div>
	</div> -->