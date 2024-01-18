<style type="text/css">

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

	.delete_inventory_item_details > i
    {
    	font-size:16px;
		color:#9a0606;
		cursor: pointer;
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

    .edit_inventory_item_information > i
    {
    	color:#0a9ec3;
    }

</style>

<?php
	if(!empty($inventory_item_details))
	{
?>
<div class="bg-light lter b-b wrapper-md hidden-print">
  <h1 class="m-n font-thin h3">Equipment Item Information</h1>
</div>

<input type="hidden" class="viewed_inventory_item_id" value="<?php echo $inventory_item_details['inventory_item_id']; ?>">
<div class="wrapper-md inventory_item_details_container hidden-print">
	<a href="javascript:void(0)" class="edit_inventory_item_information" style="margin-left:5px !important;"><i class="fa fa-pencil"></i> Edit Info</a> &nbsp &nbsp &nbsp
	<a href="javascript:void(0)" class="delete_inventory_item_details" data-vendor-id="<?php echo $inventory_item_details['vendor_id']; ?>" data-inventory-item-id="<?php echo $inventory_item_details['inventory_item_id']; ?>" style="margin-left:5px !important;"><i class="fa fa-times"></i> Delete </a>

	<div class="well m-t bg-light lt">
		<div class="form-group clearfix">
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-address-card-o"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Vendor </label>
	                        <span> <?php echo $inventory_item_details['vendor_name']; ?> </span>
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
	                        <span> <?php echo $inventory_item_details['item_description']; ?> </span>
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
	                        <span> <?php echo $total_on_hand; ?> </span>
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
	                        <span> <?php echo $inventory_item_details['item_vendor_acct_no']; ?> </span>
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
	                        <span> <?php echo $inventory_item_details['company_item_no']; ?> </span>
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
	                        <span> <?php echo $inventory_item_details['item_par_level']; ?> </span>
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
	                        <label> Reorder Number </label>
	                        <span> <?php echo $inventory_item_details['item_reorder_no']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-object-group"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Category </label>
	                        <span> <?php echo $inventory_item_details['item_category_name']; ?> </span>
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
	                        <span> <?php echo $inventory_item_details['item_warehouse_location']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
		</div>
		<div class="form-group clearfix">
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-plus-square"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Added to Hospice Item List </label>
	                        <?php
	                        	$added_to_hospice_list = "No";
	                        	if($inventory_item_details['add_to_hospice_item_list'] == 1)
	                        	{
	                        		$added_to_hospice_list = "Yes";
	                        	}
	                        ?>
	                        <span> <?php echo $added_to_hospice_list; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-hashtag"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Serial No. </label>
	                        <span> <?php echo $inventory_item_details['item_serial_no']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-4">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-hashtag"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Asset No. </label>
	                        <span> <?php echo $inventory_item_details['item_asset_no']; ?> </span>
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
			foreach ($item_unit_of_measures as $key => $value)
			{
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
					<div class="col-sm-4 col-md-4 item_unit_of_measure_selected">
						<?php echo number_format($value['item_vendor_cost'],2); ?>
					</div>
					<div class="col-sm-4 col-md-4 item_unit_of_measure_selected">
						<?php echo number_format($value['item_company_cost'],2); ?>
					</div>
				</div>
				<?php
				if(count($item_unit_of_measures) > 1)
				{
				?>
					<hr class="unit_of_measure_hr" />
				<?php
				}
			}
		}
		?>
		<div style="margin-bottom: 30px;">
		</div>
	</div>
</div>

<!-- Modal Edit Equipment Item Information -->
<div class="modal fade modal_edit_inventory_item_information" id="modal_edit_inventory_item_information" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
        	<div class="modal-header">
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Edit Equipment Item Information</h4>
            </div>
            <div class="modal-body OpenSans-Reg">
            	<form role="form" action="<?php echo base_url('inventory/edit_inventory_item_information'); ?>" method="post" id="edit_inventory_item_information_form_validate" novalidate>
                    <input type="hidden" name="viewed_item_id" value="<?php echo $inventory_item_details['item_id']; ?>">
                    <input type="hidden" name="viewed_inventory_item_id" value="<?php echo $inventory_item_details['inventory_item_id']; ?>">
                    <input type="hidden" name="viewed_company_item_no" value="<?php echo $inventory_item_details['company_item_no']; ?>">

                    <div class="form-group clearfix">
                        <div class="col-sm-3">
                            <label> Company Item No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="company_item_no" class="form-control edit_item_input" value="<?php echo $inventory_item_details['company_item_no']; ?>">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                    	<div class="col-sm-6">
                            <label> Item Description <span class="text-danger-dker">*</span></label>
                            <input type="text" name="item_description" class="form-control edit_item_input" value='<?php echo $inventory_item_details['item_description']; ?>' >
                        </div>
                        <div class="col-sm-6 vendor_div">
                            <label> Vendor <span class="text-danger-dker">*</span></label>
                            <select name="item_vendor" class="form-control choose_item_vendor">
                                <?php
                                    if(!empty($vendor_list))
                                    {
                                        foreach ($vendor_list as $key => $value)
                                        {
                                        	if($inventory_item_details['item_vendor'] == $value['vendor_id'])
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
                            <input type="text" name="item_vendor_acct_no" class="form-control edit_item_input" value="<?php echo $inventory_item_details['item_vendor_acct_no']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> Reorder No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="item_reorder_no" class="form-control edit_item_input" value="<?php echo $inventory_item_details['item_reorder_no']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> Warehouse Location <span class="text-danger-dker">*</span></label>
                            <input type="text" name="item_warehouse_location" class="form-control edit_item_input" value="<?php echo $inventory_item_details['item_warehouse_location']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> Category <span class="text-danger-dker">*</span></label>
                            <select name="item_category" class="form-control">
                                <?php
                                    if($item_category_list)
                                    {
                                        foreach ($item_category_list as $key => $value)
                                        {
                                        	if($inventory_item_details['item_category'] == $value['item_category_id'])
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
	                        <input type="number" name="item_par_level" class="form-control edit_item_input grey_inner_shadow" value="<?php echo $inventory_item_details['item_par_level']; ?>" >
	                    </div>
	                    <div class="col-sm-3">
	                    	<label> Serial No. <span class="text-danger-dker">*</span></label>
	                        <input type="text" name="item_serial_no" class="form-control edit_item_input grey_inner_shadow" value="<?php echo $inventory_item_details['item_serial_no']; ?>" >
	                    </div>
	                    <div class="col-sm-3">
	                    	<label> Asset No. <span class="text-danger-dker">*</span></label>
	                        <input type="text" name="item_asset_no" class="form-control edit_item_input grey_inner_shadow" value="<?php echo $inventory_item_details['item_asset_no']; ?>" >
	                    </div>
	                    <div class="col-sm-3">
                            <label> Add to Hospice Item List <span class="text-danger-dker">*</span></label>
                            <?php
                            	if($inventory_item_details['add_to_hospice_item_list'] == 1)
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
                <button type="button" class="btn btn-default btn-edit-inventory-item-information-close">Cancel</button>
                <button type="button" class="btn btn-success btn-save-edit-inventory-item-information">Save Information</button>
            </div>
        </div>
    </div>
</div>

<?php
	}
?>

<script type="text/javascript">

	$(document).ready(function(){

		// Remove item from inventory
	  	$('body').on('click','.delete_inventory_item_details',function(){
	  		var _this = $(this);
	  		var inventory_item_id = $(this).attr("data-inventory-item-id");

			jConfirm("Remove item from inventory?","Warning", function(response){
		        if(response)
		        {
		        	modalbox(base_url + 'inventory/remove_inventory_item_reason/'+ inventory_item_id ,{
		                header:"Reason for removing the item",
		                button: false,
		            });
		        }
	        });
	  	});

	  	$('body').on('click','.edit_inventory_item_information',function(){
			$('#modal_edit_inventory_item_information').modal('show');
		});

		$('body').on('click','.btn-edit-inventory-item-information-close',function(){
            $('#modal_edit_inventory_item_information').modal('hide');
        });

        $('body').on('click','.btn-save-edit-inventory-item-information',function(){
            var _this_save_btn = $(this);

  	   		jConfirm('<br />Save Changes?', 'Reminder', function(response){
				if(response)
				{
					//disable submit button until the order is process
					$(_this_save_btn).prop('disabled',true);

					$("#edit_inventory_item_information_form_validate").ajaxSubmit({
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

	});

</script>




