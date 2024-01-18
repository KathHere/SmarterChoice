<style type="text/css">

	.item_details_table
	{
		margin-top:20px;
	}

	.item_details_container
	{
		padding-bottom: 0px !important;
	}

	.dme-listing
    {
        display: block;
        list-style: none;
        list-style-image: none;
        width: 100%;
        padding-left: 0;
        margin: 0;
    }

    .dme-lists
    {
        display: table;
        padding-top: 10px;
        width: 100%;
        padding-bottom: 10px;
    }

    .dme-lists-icon
    {
        display: table-cell;
        font-size: 20px;
        text-align: center;
        width: 40px;
        vertical-align: top;
    }

    .dme-lists-text
    {
        display: table-cell;
        padding-top: 3px;
        padding-right: 10px;
        padding-left: 10px;
        font-size: 14.5px;
    }

    .dme-lists-text > label {
        display: block;
        line-height: 14px;
        color:rgba(0, 0, 0, 0.9);
    }

    .dme-lists-text > span {
        display: block;
    }

    .item_unit_of_measure_selected
    {
    	padding-left: 66px !important;
    }

    .unit_of_measure_hr
    {
	    margin-top: 10px;
	    margin-bottom: 14px;
	    border-top: 1px solid rgba(0, 0, 0, 0.06);
	    width: 94%;
    }

    .item_unit_of_measure_form_group_header
    {
        margin-left: 15px !important;
        margin-right: 15px !important;
        margin-top: 20px !important;
        font-weight:bold;
    }

    .item_unit_of_measure_form_group_content
    {
        margin-left: 15px !important;
        margin-right: 15px !important;
        margin-top: -15px !important;
    }

    .item_unit_of_measure
    {
        border:1px solid rgba(8, 8, 8, 0.62);
        height:40px;
        text-align: center !important;
    }

    .item_unit_of_measure_input
    {
        background-color: #fff !important;
        border: 0px !important;
        box-shadow: none !important;
        width: 100% !important;
        text-align: center !important;
    }

    .delete_item_details > i
    {
    	font-size:16px;
		color:#9a0606;
		cursor: pointer;
    }

    .delete_item_details_disabled
    {
    	cursor: default !important;
    }

    .edit_item_information > i
    {
    	color:#0a9ec3;
    }

    .modal-reorder {
    	width: 450px !important;
    }

    .modal-edit-reorder {
    	width: 350px !important;
    }

    #modal_reorder_item_information .bootstrap-dt-container
    {
    	margin-top: 10px !important;
    }

    @media (max-width: 768px) {
    	.item_reorder_no {
    		width: 100px !important;
    	}
    }
    .div-spinner {
    	margin-top: 20% !important;
    	margin-left: 50% !important;
    }

    #item-details-content {
    	border-bottom: none !important;
    }

</style>

<div  class="bg-light lter b-b wrapper-md hidden-print">
  <h1 class="m-n font-thin h3">Item Information</h1>
</div>
<div id="view-item-content">
	<input type="hidden" class="viewed_item_id" value="<?php echo $item_details['item_id']; ?>">
	<div class="wrapper-md item_details_container hidden-print">
		<?php
		if ($this->session->userdata('account_type') != "distribution_supervisor") {
		?>
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
	                    <input type="hidden" name="viewed_company_item_no" value="<?php echo $item_details['company_item_no']; ?>">
						<input type="hidden" name="temp_add_to_hospice_item_list" value="<?php echo $item_details['add_to_hospice_item_list']; ?>">

	                    <div class="form-group clearfix">
	                        <div class="col-sm-3">
	                            <label> Company Item No. <span class="text-danger-dker">*</span></label>
	                            <input type="text" name="company_item_no" class="edit_item_details_company_item_no form-control edit_item_input" value="<?php echo $item_details['company_item_no']; ?>">
	                        </div>
	                    </div>
	                    <div class="form-group clearfix">
	                    	<div class="col-sm-6">
	                            <label> Item Description <span class="text-danger-dker">*</span></label>
	                            <input type="text" name="item_description" class="edit_item_details_item_description form-control edit_item_input" value='<?php echo $item_details['item_description']; ?>' >
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
	                            <input type="text" name="item_vendor_acct_no" class="edit_item_details_item_vendor_acct_no form-control edit_item_input" value="<?php echo $item_details['item_vendor_acct_no']; ?>">
	                        </div>
	                        <div class="col-sm-3">
	                            <label> Reorder Number <span class="text-danger-dker">*</span></label>
	                            <input type="text" name="item_reorder_no" class="form-control edit_item_input" value="<?php echo $item_details['item_reorder_no']; ?>">
	                        </div>
	                        <div class="col-sm-3">
	                            <label> Warehouse Location <span class="text-danger-dker">*</span></label>
	                            <input type="text" name="item_warehouse_location" class="edit_item_details_item_warehouse_location form-control edit_item_input" value="<?php echo $item_details['item_warehouse_location']; ?>">
	                        </div>
	                        <div class="col-sm-3">
	                            <label> Category <span class="text-danger-dker">*</span></label>
	                            <select name="item_category" class="edit_item_details_item_category form-control">
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
	                    </div>
	                    <div class="form-group clearfix">
	                    	<div class="col-sm-3">
		                    	<label> Par Level <span class="text-danger-dker">*</span></label>
		                        <input type="number" name="item_par_level" class="edit_item_details_item_par_level form-control edit_item_input grey_inner_shadow" value="<?php echo $item_details['item_par_level']; ?>" >
		                    </div>
		                    <!-- <div class="col-sm-3">
		                    	<label> Item Group </label>
                                <select name="item_group_id" class="form-control item_group">
                                    <option value="" > [--Choose Item Group--] </option>
                                    <?php
                                        if ($item_group_list) {
                                            foreach ($item_group_list as $key => $value) {
                                            	if($item_details['item_group_id'] == $value['item_group_id'])
	                                        	{
	                                        		$item_group = "selected";
	                                        	}
	                                        	else
	                                        	{
	                                        		$item_group = "";
	                                        	}
                                    ?>
                                                <option value="<?php echo $value['item_group_id']; ?>" <?php echo $item_group; ?>> <?php echo $value['item_group_name']; ?> </option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
		                    </div> -->
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
	                                    <input type="radio" name="add_to_hospice_item_list" class="edit_item_details_add_to_hospice_item_list_yes" value="1" <?php echo $added;?> /><i></i> Yes &nbsp &nbsp &nbsp
	                                </label>
	                                <label class="i-checks">
	                                    <input type="radio" name="add_to_hospice_item_list" class="edit_item_details_add_to_hospice_item_list_no" value="0" <?php echo $not_added;?> /><i></i> No
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
			                                <input type="text" name="item_unit_value_box" class="item_unit_of_measure_input edit_item_details_value_box" value="<?php echo $new_set_item_unit_of_measure['box']['item_unit_value']?>" >
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_box" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['box']['item_vendor_cost'],2); ?>" >
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_box" class="item_unit_of_measure_input edit_item_details_company_cost_box" value="<?php echo number_format($new_set_item_unit_of_measure['box']['item_company_cost'],2); ?>" >
			                            </div>
	                            <?php
	                            	}
	                            	else
	                            	{
	                            ?>
	                            		<div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_box" class="item_unit_of_measure_input edit_item_details_value_box">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_box" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_box" class="item_unit_of_measure_input edit_item_details_company_cost_box">
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
			                                <input type="text" name="item_unit_value_each" class="item_unit_of_measure_input edit_item_details_value_each" value="<?php echo $new_set_item_unit_of_measure['each']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_each" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['each']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_each" class="item_unit_of_measure_input edit_item_details_company_cost_each" value="<?php echo number_format($new_set_item_unit_of_measure['each']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_each" class="item_unit_of_measure_input edit_item_details_value_each">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_each" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_each" class="item_unit_of_measure_input edit_item_details_company_cost_each">
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
			                                <input type="text" name="item_unit_value_case" class="item_unit_of_measure_input edit_item_details_value_case" value="<?php echo $new_set_item_unit_of_measure['case']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_case" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['case']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_case" class="item_unit_of_measure_input edit_item_details_company_cost_case" value="<?php echo number_format($new_set_item_unit_of_measure['case']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_case" class="item_unit_of_measure_input edit_item_details_value_case">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_case" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_case" class="item_unit_of_measure_input edit_item_details_company_cost_case">
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
			                                <input type="text" name="item_unit_value_pair" class="item_unit_of_measure_input edit_item_details_value_pair" value="<?php echo $new_set_item_unit_of_measure['pair']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_pair" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['pair']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_pair" class="item_unit_of_measure_input edit_item_details_company_cost_pair" value="<?php echo number_format($new_set_item_unit_of_measure['pair']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_pair" class="item_unit_of_measure_input edit_item_details_value_pair">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_pair" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_pair" class="item_unit_of_measure_input edit_item_details_company_cost_pair">
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
			                                <input type="text" name="item_unit_value_pack" class="item_unit_of_measure_input edit_item_details_value_pack" value="<?php echo $new_set_item_unit_of_measure['pack']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_pack" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['pack']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_pack" class="item_unit_of_measure_input edit_item_details_company_cost_pack" value="<?php echo number_format($new_set_item_unit_of_measure['pack']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_pack" class="item_unit_of_measure_input edit_item_details_value_pack">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_pack" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_pack" class="item_unit_of_measure_input edit_item_details_company_cost_pack">
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
			                                <input type="text" name="item_unit_value_package" class="item_unit_of_measure_input edit_item_details_value_package" value="<?php echo $new_set_item_unit_of_measure['package']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_package" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['package']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_package" class="item_unit_of_measure_input edit_item_details_company_cost_package" value="<?php echo number_format($new_set_item_unit_of_measure['package']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_package" class="item_unit_of_measure_input edit_item_details_value_package">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_package" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_package" class="item_unit_of_measure_input edit_item_details_company_cost_package">
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
			                                <input type="text" name="item_unit_value_roll" class="item_unit_of_measure_input edit_item_details_value_roll" value="<?php echo $new_set_item_unit_of_measure['roll']['item_unit_value']?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_roll" class="item_unit_of_measure_input" value="<?php echo number_format($new_set_item_unit_of_measure['roll']['item_vendor_cost'],2); ?>">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_roll" class="item_unit_of_measure_input edit_item_details_company_cost_roll" value="<?php echo number_format($new_set_item_unit_of_measure['roll']['item_company_cost'],2); ?>">
			                            </div>
	                            <?php
	                                }
	                                else
	                                {
	                            ?>
	                                    <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_unit_value_roll" class="item_unit_of_measure_input edit_item_details_value_roll">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;border-right:0px !important;padding-top:8px;">
			                                <input type="text" name="item_vendor_cost_roll" class="item_unit_of_measure_input">
			                            </div>
			                            <div class="col-xs-3 col-sm-3 col-md-3 item_unit_of_measure" style="border-top:0px !important;padding-top:8px;">
			                                <input type="text" name="item_company_cost_roll" class="item_unit_of_measure_input edit_item_details_company_cost_roll">
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
</div>

<script type="text/javascript">

	$(document).ready(function(){

		$('body').on('change','#item_reorder_no',function(){
		    var company_item_no = $( "#item_reorder_no option:selected" ).attr("data-companyno");
		    var selected_item_reorder_no = $(this).val();
		    var vendor_id = $( "#item_reorder_no option:selected" ).attr("data-vendor-id");
		    var parentDiv = document.getElementById("view-item-content");

		    $("body").find("#view-item-content").html('<div class="div-spinner"><i class="fa fa-spin fa-spinner" style="font-size: 30px"></i></div>');
		    setTimeout(function(){
		    	$.post(base_url+"inventory/item_information_content/" + vendor_id + "/" + company_item_no + "/" + selected_item_reorder_no,"", function(response){
			    	parentDiv.innerHTML = response;
				});
			},1500);
		});

		$('.item_reorder_no_select').on('change','.item_reorder_no_option',function(){
			$('#modal_edit_item_information').modal('show');
		});

		$('body').on('click','.item_activate_button',function(){
			var temp_value = $( "#myselect option:selected" ).text();
  	   		var sign = $(this).attr("data-sign");
  	   		var item_id = $("body").find(".viewed_item_id").val();

  	   		$.post(base_url+"inventory/change_item_activation/" + sign + "/" + item_id,"", function(response){
			 	if(sign == 0)
			 	{
			 		$("body").find("#item_activate_button_span").html('<label class="i-checks"><input type="checkbox" class="item_activate_button" data-sign="1" checked><i></i> Deactivated (Click to activate)</label>');
			 		me_message_v2({error:0,message:"Success! Item is already inactive."});
			 	}
			 	else
			 	{
			 		$("body").find("#item_activate_button_span").html('<label class="i-checks"><input type="checkbox" class="item_activate_button" data-sign="0" ><i></i> Make inactive</label>');
			 		me_message_v2({error:0,message:"Success! Item is now active."});
			 	}
			});
  	   	});

  	   	$('body').on('click','.edit_item_information',function(){
			$('#modal_edit_item_information').modal('show');
		});

		$('body').on('click','.reorder_item_information',function(){
			$('#modal_reorder_item_information').modal('show');
		});

		$('body').on('click','.btn-edit-item-reorder-no',function(){
			$('#modal_edit_reorder_no').modal('show');
		});

		$('body').on('click','.btn-edit-item-information-close',function(){
            $('#modal_edit_item_information').modal('hide');
        });

        $('body').on('click','.btn-edit-item-reorder-close',function(){
            $('#modal_reorder_item_information').modal('hide');
        });

         $('body').on('click','.btn-edit-reorder-close',function(){
            $('#modal_edit_reorder_no').modal('hide');
        });


        $('body').on('click','.btn-save-edit-item-information',function(){
            var _this_save_btn = $(this);

  	   		jConfirm('<br />Save Changes?', 'Reminder', function(response){
				if(response)
				{
					//disable submit button until the order is process
					$(_this_save_btn).prop('disabled',true);

					$("#edit_item_information_form_validate").ajaxSubmit({
						beforeSend:function()
						{
							me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Saving Changes ..."});
						},
						success:function(response)
						{
							$('#error-modal .alert').removeClass('alert-danger');
				            $('#error-modal .alert').removeClass('alert-info');
				            $('#error-modal .alert').removeClass('alert-success');

							try
	            			{
				                var obj = $.parseJSON(response);
				                me_message_v2(obj);
				                if(obj['error']==0)
				                {
				                	setTimeout(function(){
										window.location.reload();
									},2000);
				                }
				            }
				            catch (err)
				            {
				            	me_message_v2({error:1,message:"Failed to save changes."});
				            }
							$(_this_save_btn).prop('disabled',false);
						}
					});
				}
			});
        });

        $('body').on('click','.delete_item_details',function(){
        	var _this = $(this);
	  		var item_id = $(this).attr("data-item-id");
	  		var vendor_id = $(this).attr("data-vendor-id");

			jConfirm("Delete inventory item?","Warning", function(response){
		        if(response)
		        {
		        	$.post(base_url+"inventory/delete_vendor_item/"+2+"/"+ item_id,"", function(response){
					 	var obj = $.parseJSON(response);
				        me_message_v2(obj);
				        if(obj['error'] == 0)
				        {
				        	me_message_v2(obj);
				        	setTimeout(function(){
								window.location.href = base_url + "inventory/vendor_details/"+vendor_id;
							},3500);
				        }
					});
		        }
	        });
        });

        // Autofill the item information
        var globalTimeout = null;
        $('.edit_item_details_company_item_no').bind('keyup',function(){
        	var _this = $(this);
            var company_item_no = $(this).val();

            if(globalTimeout != null) clearTimeout(globalTimeout);
            globalTimeout =setTimeout(getInfoFunc,1100);

            function getInfoFunc(){
                globalTimeout = null;

                if(company_item_no.length > 0)
                {
                	$.post(base_url+"inventory/get_item_details/"+company_item_no+"/"+"","", function(response){
                        var obj = $.parseJSON(response);


                        if(obj.item_details.item_description != undefined)
                        {
                            $(".edit_item_details_item_description").val(obj.item_details.item_description);
                            $(".edit_item_details_item_warehouse_location").val(obj.item_details.item_warehouse_location);
                            $(".edit_item_details_item_category").val(obj.item_details.item_category).change();
                            $(".edit_item_details_item_par_level").val(obj.item_details.item_par_level);
                            // $("body").find(".edit_item_details_item_vendor_acct_no").val(obj.item_details.item_vendor_acct_no);
                            if(obj.item_details.add_to_hospice_item_list == 1)
                            {
                                $(".edit_item_details_add_to_hospice_item_list_yes").prop('checked','true');
                            }
                            else if(obj.item_details.add_to_hospice_item_list == 0)
                            {
                                $(".edit_item_details_add_to_hospice_item_list_no").prop('checked','true');
                            }
                            else
                            {
                                $(".edit_item_details_add_to_hospice_item_list_yes").removeAttr('checked');
                                $(".edit_item_details_add_to_hospice_item_list_no").removeAttr('checked');
                            }
                        }

                        var unit_measure = ['box','each','case','pair','pack','package','roll'];
                        for(var val in unit_measure)
                        {
                            var value_name = ".edit_item_details_value_"+unit_measure[val];
                            var company_cost_name = ".edit_item_details_company_cost_"+unit_measure[val];

                            $(value_name).val("");
                            $(company_cost_name).val("");
                        }

                        if(obj.item_unit_of_measures.length > 0)
                        {
                            for(var val in obj.item_unit_of_measures)
                            {
                                var value_name = ".edit_item_details_value_"+obj.item_unit_of_measures[val].item_unit_measure;
                                var company_cost_name = ".edit_item_details_company_cost_"+obj.item_unit_of_measures[val].item_unit_measure;
                                var final_company_cost = Number(obj.item_unit_of_measures[val].item_company_cost);

                                $(value_name).val(obj.item_unit_of_measures[val].item_unit_value);
                                $(company_cost_name).val(final_company_cost.toFixed(2));
                            }
                        }
                    });
                }
            }
        });

        var datatable = $('.datatable_table_inventory_item_reorder_no').DataTable({
			fnDrawCallback: function( oSettings ) {
				$.fn.editable.defaults.mode = 'popover';
		      $('body .editable-click.editable-itemlist').editable({
			          emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
			          validate: function(value) {
			              if($.trim(value) == '') {
			                  return 'This field is required';
			              }
			          },
			          success:function(response,newValue){
			            console.log(response);
			             if(response.error==1) return response.message;
			          }
   				});
		    }
		});

		// $('body').on('click','.btn-delete-item-reorder-no',function(){
	 //  		var _this = $(this);
	 //  		var inventory_item_reorder_no = $(this).attr("data-inventory-item-reorder-no");

		// 	jConfirm("Remove item from inventory?","Warning", function(response){
		//         if(response)
		//         {
		//         	modalbox(base_url + 'inventory/remove_inventory_item_reorder_no/'+ inventory_item_id ,{
		//                 header:"Reason for removing the item",
		//                 button: false,
		//             });
		//         }
	 //        });
	 //  	});

		$('body').on('click','.btn-delete-item-reorder-no',function(){
			var _this = $(this);
			var item_reorder_no = $(this).data("inventory-item-reorder-no");
			var item_id = $(this).data("inventory-item-id");

			jConfirm("Remove Item from Inventory?","Reminder",function(response){
				if(response)
    			{
    				$.post(base_url+"inventory/remove_inventory_item_reorder_no/" + item_id + "/",'', function(response){
		                var obj = $.parseJSON(response);
		                if(obj)
		                {
		                	me_message_v2({error:0,message:"Item Removed."});
		            		setTimeout(function(){
		                        location.reload();
		                    },1500);
		                }
		                else
		                {
		                	me_message_v2({error:0,message:"Error Deleting Item."});
		                }
		            });
    			}
			});
		});
  	});

</script>
