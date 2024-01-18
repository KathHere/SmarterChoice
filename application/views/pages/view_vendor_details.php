<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>

<style type="text/css">

	.vendor_details_container
	{
		padding-bottom: 0px !important;
	}

	.vendor_options_div
	{
		padding-top: 10px !important;
	}

	.view_all_item_table
	{
		margin-top:10px !important;
		border-right:1px solid rgba(72, 70, 70, 0.11)!important;
		border-left:1px solid rgba(72, 70, 70, 0.11)!important;
		text-align: center;
	}

	.search-vendor-item-div
	{
		margin-top: 15px !important;
		margin-bottom: 0px !important;
	}

	.search-inventory-item-div
	{
		margin-top: 15px !important;
		margin-bottom: 0px !important;
	}

	.search_items_result_div
	{
		margin-top:60px !important;
	}

	.search_inventory_items_result_div
	{
		margin-top:60px !important;
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

    .delete_vendor_item
	{
		color:#9a0606;
		cursor: pointer;
	}

	.select2-container--default
	{
		width:100% !important;
	}

	.search_inventory_item_td
	{
		text-align: center !important;
	}

	.edit_inventory_item
	{
		color:#41aed2;
		margin-right:7px;
		cursor: pointer;
	}

	.item_inactive
	{
		color:rgba(0, 0, 0, 0.45) !important;
	}

	.search_inventory_items_result_table
	{
		border:1px solid #eaeeea !important;
	}

	.select2-container .select2-selection--single
	{
		margin-top: 1px !important;
		height:32px !important;
		background-color: rgba(253, 253, 253, 0.7);
	    -moz-box-shadow: inset 0 0 10px rgba(88, 102, 110, 0.13);
	    -webkit-box-shadow: inset 0 0 10px rgba(88, 102, 110, 0.13);
	    box-shadow: inset 0 0 10px rgba(88, 102, 110, 0.13);
	}

	.select2-container--default .select2-selection--single {
	    border: 1px solid rgba(0, 0, 0, 0.18) !important;
	}

	.select2-results__option {
    	font-family: "Lucida Console", Monaco, monospace !important;
    	font-size: 12.5px !important;
	}

	@media (max-width: 767px){
		.open_balance_div
		{
			text-align: left !important;
		}
		.item_unit_measure_div
		{
			margin-top: 15px !important;
		}
		.item_quantity_div_manual_adding_item
		{
			padding-top: 0px !important;
		}

	}

	.vendor_details_p
	{
		font-size: 15px !important;
	}

	@media (max-width: 1000px){

		.search_items_result_table
		{
			width: 800px !important;
		}

		#DataTables_Table_0_wrapper
		{
			width: 800px !important;
		}

		#DataTables_Table_0_info
		{
			text-align: right !important;
		}

		#DataTables_Table_0_paginate
		{
			text-align: right !important;
		}

		.active > .view_vendor_details_menu
		{
			font-weight: bold !important;
		}
	}
		

</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  <h1 class="m-n font-thin h3">Vendor Information</h1>
</div>
<input type="hidden" class="viewed_vendor_id" value="<?php echo $vendor_details['vendor_id']; ?>">
<div class="wrapper-md vendor_details_container hidden-print">
	<?php
	if ($this->session->userdata('account_type') != "distribution_supervisor") {
	?>
	<a href="javascript:void(0)" class="edit_vendor_information" style="margin-left:5px !important;"><i class="icon-pencil"></i> Edit Info</a> &nbsp &nbsp &nbsp &nbsp
	<span id="vendor_activate_button_span">
	<?php 
		if($vendor_details['vendor_active_sign'] == 1)
		{
	?>
			<label style="color:rgba(0, 0, 0, 0.75) !important;" class="i-checks"><input type="checkbox" class="vendor_activate_button" data-sign="0" ><i></i> Make inactive</label>
	<?php
		}
		else
		{
	?>
			<label style="color:rgba(0, 0, 0, 0.75) !important;" class="i-checks"><input type="checkbox" class="vendor_activate_button" data-sign="1" checked><i></i> Deactivated (Click to activate)</label>
	<?php
		} 
	}
	?>
	</span>
	<div class="well m-t bg-light lt">
	    <div class="form-group clearfix">
			<div class="col-sm-6 col-md-7">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-calendar"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Entry Date </label>
	                        <span> <?php echo date("m/d/Y", strtotime($vendor_details['vendor_entry_date'])); ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-2">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-credit-card-alt" style="font-size: 18px !important;"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Credit </label>
	                        <span> <?php echo number_format($vendor_details['vendor_credit'],2);  ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-3 open_balance_div">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-money"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Open Balance </label>
	                        <span> <?php echo number_format($open_balance,2);  ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
		</div>
		<div class="form-group clearfix">
			<div class="col-sm-6 col-md-3">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-address-card-o" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Vendor </label>
	                        <span> <?php echo $vendor_details['vendor_name']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-2">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-hashtag" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Account No. </label>
	                        <span> <?php echo $vendor_details['vendor_acct_no']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-2">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-commenting" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Credit Term </label>
	                        <span> <?php echo $vendor_details['vendor_credit_terms']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-2">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-credit-card" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Credit Limit </label>
	                        <span> <?php echo number_format($vendor_details['vendor_credit_limit'],2); ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-3">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-usd" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Shipping Cost </label>
	                        <span> <?php echo $vendor_details['vendor_shipping_cost']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
		</div>
		<div class="form-group clearfix">
			<div class="col-sm-6 col-md-3">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-map-marker" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Address </label>
	                        <span> <?php echo $vendor_details['vendor_street']; ?> </span>
	                        <span> <?php echo $vendor_details['vendor_city']; ?>, <?php echo $vendor_details['vendor_state']; ?>, <?php echo $vendor_details['vendor_postal_code']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-2">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-phone" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Phone No. </label>
	                        <span> <?php echo $vendor_details['vendor_phone_no']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-2">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-fax" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Fax No. </label>
	                        <span> <?php echo $vendor_details['vendor_fax_no']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-5">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-envelope" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Email Address </label>
	                        <span> <?php echo $vendor_details['vendor_email_address']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
		</div>
		<div class="form-group clearfix">
			<div class="col-sm-6 col-md-3">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Sales Rep. </label>
	                        <span> <?php echo $vendor_details['vendor_sales_rep']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-2">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-phone-square" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Office No. </label>
	                        <span> <?php echo $vendor_details['vendor_office_no']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-2">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-mobile" aria-hidden="true" style="font-size: 28px !important;"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Cell No. </label>
	                        <span> <?php echo $vendor_details['vendor_cell_no']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
			<div class="col-sm-6 col-md-5">
				<ul class="dme-listing">
	                <li class="dme-lists">
	                    <div class="dme-lists-icon"><i class="fa fa-envelope" aria-hidden="true"></i></div>
	                    <div class="dme-lists-text">
	                        <label> Email Address </label>
	                        <span> <?php echo $vendor_details['vendor_sales_rep_email_address']; ?> </span>
	                    </div>
	                </li>
	            </ul>
			</div>
		</div>
	</div>
</div>

<div class="page whole-container" ng-controller="FlotChartDemoCtrl">
	<div class="wrapper-md vendor_options_div">
				
	    <div class="panel-report panel panel-default">

		    <div role="tabpanel" class="tab-container ng-isolate-scope" style="margin:3px !important;">
	            <!-- Nav tabs -->
	            <ul class="nav nav-tabs hidden-print" role="tablist" >

	              	<!-- <li role="presentation" class="active">
	                	<a href="#item_search" aria-controls="profile" role="tab" data-toggle="tab">
	                  		<span style="font-size:14px;"> Item Search </span>
	                	</a>
	              	</li> -->
	              	<li role="presentation" class="active view_vendor_details_menu" style="font-weight: bold;">
	                	<a href="#view_all_item" role="tab" data-toggle="tab">
	                	  <span style="font-size:14px;"> View All Item</span>
	                	</a>
	              	</li>
	              	<li role="presentation" class="view_vendor_details_menu" >
	                	<a href="#equipment_item_search" role="tab" data-toggle="tab">
	                	  <span style="font-size:14px;"> Equipment Item Search</span>
	                	</a>
	              	</li>
	              	<li role="presentation" class="view_vendor_details_menu">
	                	<a href="#add_equipment_item" role="tab" data-toggle="tab">
	                	  <span style="font-size:14px;"> Add Equipment Item</span>
	                	</a>
	              	</li>
	            </ul>

	            <!-- Tab panes -->
	            <div class="tab-content">

	            	<div role="tabpanel" class="tab-pane active" id="view_all_item">
	            		<div class="ng-scope">
						    <div class="panel-body" style="padding-left:0px;padding-right:0px;">
								<div style="overflow-x: auto;">   
									<?php 
									if(!empty($vendor_items)){
									?>
										<table class="table bg-white b-a datatable_table table-hover view_all_item_table">
									<?php 
									}
									else
									{
									?>
										<table class="table bg-white b-a table-hover view_all_item_table">
									<?php 
									}
									?>
									  	<thead style="background-color:rgba(97, 101, 115, 0.05);">
									    	<tr>
									    		<th style="width:14%;text-align:center;">Company Item No.</th>
									      		<th style="width:30%;text-align:center;">Item Description</th>
									      		<th style="width:12%;text-align:center;">Category</th>
									      		<th style="width:15%;text-align:center;">Warehouse Location</th>
									      		<th style="width:10%;text-align:center;">Status</th>
												<?php if ($this->session->userdata('account_type') != "distribution_supervisor") { ?>
									      		<th style="width:9%;text-align:center;">Delete</th>
												<?php } ?>
									    	</tr>
									    </thead>
									    <tbody class="activity_status_tbody">
									    	<?php 
									    		if(!empty($vendor_items)){
									    			$temp = 0;
									    			foreach($vendor_items as $value){
									    				if($value['item_active_sign'] == 1)
									    				{
									    					$active_sign =" ";
									    				}
									    				else
									    				{
									    					$active_sign ="item_inactive";
									    				}
									    	?>
									    				<tr>
													    	<td>
													    		<a target="_blank" class="<?php echo $active_sign; ?>" href="<?php echo base_url('inventory/item_information/'.$vendor_details['vendor_id'].'/'.$value['company_item_no']); ?>">
													    			<?php echo $value['company_item_no']; ?>
													    		</a>
													    	</td>
													    	<td>
													    		<a target="_blank" class="<?php echo $active_sign; ?>" href="<?php echo base_url('inventory/item_information/'.$vendor_details['vendor_id'].'/'.$value['company_item_no']); ?>">
													    			<?php echo $value['item_description']; ?>
													    		</a>
													    	</td>
													    	<td>
													    		<a target="_blank" class="<?php echo $active_sign; ?>" href="<?php echo base_url('inventory/item_information/'.$vendor_details['vendor_id'].'/'.$value['company_item_no']); ?>">
													    			<?php echo $value['item_category_name']; ?>
													    		</a>
													    	</td>
													    	<td>
													    		<a target="_blank" class="<?php echo $active_sign; ?>" href="<?php echo base_url('inventory/item_information/'.$vendor_details['vendor_id'].'/'.$value['company_item_no']); ?>">
													    			<?php echo $value['item_warehouse_location']; ?>
													    		</a>
													    	</td>
													    	<td>
													    		<a target="_blank" class="<?php echo $active_sign; ?>" href="<?php echo base_url('inventory/item_information/'.$vendor_details['vendor_id'].'/'.$value['company_item_no']); ?>">
													    		<?php 
													    			if($value['item_active_sign'] == 1)
													    			{
													    				echo "Active";
													    			} 
													    			else
													    			{
													    				echo "Inactive";
													    			}
													    		?>
													    		</a>
													    	</td>
															<?php if ($this->session->userdata('account_type') != "distribution_supervisor") { ?>
													    	<td>
													    		<?php 
													    			$item_orders = get_item_orders($value['item_id']);
													    			if(!empty($item_orders))
													    			{
													    		?>
													    				<!-- <span class="delete_vendor_item_disabled" title="Delete disabled" data-item-id="<?php echo $value['item_id']; ?>" > <i class="fa fa-times"></i> </span> -->
													    				<button 
													    						type="button" 
													    						title="Delete disabled"
													        					data-item-id="<?php echo $value['item_id']; ?>"
													        					class="btn btn-xs btn-danger delete_vendor_item_disabled"
													        					disabled
													        			> 
													        				<i class="fa fa-trash-o"></i> Delete
													        			</button>
													    		<?php 
													    			}
													    			else
													    			{
													    		?>
													    				<!-- <span class="delete_vendor_item" title="Delete Vendor Item" data-item-id="<?php echo $value['item_id']; ?>" > <i class="fa fa-times"></i> </span> -->
													    				<button 
													    						type="button" 
													    						title="Delete Vendor Item"
													        					data-item-id="<?php echo $value['item_id']; ?>"
													        					class="btn btn-xs btn-danger delete_vendor_item"
													        			> 
													        				<i class="fa fa-trash-o"></i> Delete
													        			</button>
													    		<?php 
													    			}
													    		?>
													    	</td>
															<?php } ?>
													    </tr> 	
									    	<?php 
									    			}
									    		}else{
									    	?>
									    			<tr>
												    	<td colspan="6" style="text-align:center;">
												    		No Items
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
	            	</div>

	            	<div role="tabpanel" class="tab-pane" id="equipment_item_search">
	            		<div class="ng-scope">
						    <div class="panel-body" style="padding-left:0px;padding-right:0px;">
						    	<div class="form-group clearfix search-inventory-item-div">
									<div class="col-sm-6 col-sm-offset-3" style="text-align:center;">
										<i class="icon-social-dropbox" style="font-size: 75px;"></i>
										<div class="input-group" style="margin-top:10px !important;">
							   	 			<input type="text" class="form-control" id="search-inventory-item" name="term" style="text-transform:none !important;" autocomplete="off" value="" placeholder="Search by Company Item No., Item Description, Asset No., Serial No., Reorder No.">
							   	 			<span class="input-group-btn">
										        <button class="btn btn-default btn-submit-inventory-item-search" type="button" title="Search"><i class="fa fa-search"></i></button>
										    </span>
						   	 			</div>
						   	 			<div id="suggestion_container_inventory_item_search" style="z-index:9999;position:absolute;border:0px solid black;width:100%;padding-right:68px">
						   	 			</div>
						   	 		</div>
						   	 		<div class="col-sm-12 search_inventory_items_result_div" style="overflow-x: auto;">
						   	 		</div>
						   	 	</div>
						    </div>
						</div>
	            	</div>

	            	<div role="tabpanel" class="tab-pane" id="add_equipment_item">
	            		<div class="ng-scope">
						    <div class="panel-body" style="padding-left:0px;padding-right:0px;margin-top:5px;margin-bottom: 35px;">
						    	<?php 
								    echo form_open("",array("class"=>"new_inventory_item_form"))
								?>
							    	<div class="row">
							    		<div class="col-sm-12">
			                                <label style="display:block;"> Item <span class="text-danger-dker">*</span></label>
			                                <select name="inventory_item_selected" class="form-control inventory_item_selected" required>
			                                    <option value=""> [--Choose Item--] </option>
			                                    <?php 
			                                        if(!empty($vendor_items2))
			                                        {
			                                            foreach ($vendor_items2 as $key => $value) 
			                                            {
			                                    ?>
			                                    			<option value="<?php echo $value['item_id']; ?>"><?php echo $value['company_item_no']."   ".$value['item_description']."   ".$value['item_reorder_no']; ?></option>
			                                    <?php 
			                                            }
			                                        }
			                                    ?>
			                                </select>
			                            </div>
			                           	
							    	</div>
							    	<div class="row ">
							    		<div class="col-sm-6 item_unit_measure_div" style="padding-top:12px;">
			                                <label> Item Unit of Measure <span class="text-danger-dker">*</span></label>
			                                <select name="inventory_item_unit_measure" class="form-control choose_inventory_item_unit_measure" required>
			                                    <option value=""> [--Choose Option--] </option>
			                                    <option value="box"> Box </option>
			                                    <option value="each"> Each (EA) </option>
			                                    <option value="case"> Case </option>
			                                    <option value="pair"> Pair (PR) </option>
			                                    <option value="pack"> Pack (PK) </option>
			                                    <option value="package"> Package (PKG) </option>
			                                    <option value="roll"> Roll (RL) </option>
			                                </select>
			                            </div>
							    		<div class="col-sm-6 item_quantity_div_manual_adding_item" style="padding-top:12px;">
			                                <label> Item Quantity <span class="text-danger-dker">*</span></label>
			                                <input type="text" name="inventory_item_quantity" class="form-control inventory_item_quantity" required>
			                            </div>
							    	</div>
							    	<input type="hidden" class="returned_item_batch_no" name="returned_item_batch_no" value="">
			                    <?php echo form_close() ;?>
						    </div>
						</div>
	            	</div>
	            </div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Edit Vendor Information -->
<div class="modal fade modal_edit_vendor_information" id="modal_edit_vendor_information" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
            <div class="modal-header">
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Edit Vendor Information</h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
                <form role="form" action="<?php echo base_url('inventory/edit_vendor_information'); ?>" method="post" id="edit_vendor_information_form_validate" novalidate>
                    <input type="hidden" name="viewed_vendor_id" value="<?php echo $vendor_details['vendor_id']; ?>">
                    <div class="form-group clearfix">
                        <div class="col-sm-3">
                            <label> Entry Date <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_entry_date" class="form-control edit_vendor_date" value="<?php echo date("m/d/Y", strtotime($vendor_details['vendor_entry_date'])); ?>">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-6">
                            <label> Vendor Name <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_name" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_name']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> Account No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_acct_no" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_acct_no']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> Credit <span class="text-danger-dker">*</span></label>
                            <input type="number" name="vendor_credit" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_credit']; ?>">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-3">
                            <label> Credit Terms <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_credit_terms" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_credit_terms']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> Credit Limit <span class="text-danger-dker">*</span></label>
                            <input type="number" name="vendor_credit_limit" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_credit_limit']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> Phone No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_phone_no" class="form-control input_tobe_masked" value="<?php echo $vendor_details['vendor_phone_no']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> Fax No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_fax_no" class="form-control input_tobe_masked" value="<?php echo $vendor_details['vendor_fax_no']; ?>">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-3">
                            <label> Street </label>
                            <input type="text" name="vendor_street" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_street']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> City <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_city" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_city']; ?>">
                        </div>
                        <div class="col-sm-6">
                            <label> Email Address <span class="text-danger-dker">*</span></label>
                            <input type="email" name="vendor_email_address" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_email_address']; ?>" style="text-transform:none !important">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-3">
                            <label> State/Province <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_state" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_state']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> Postal Code <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_postal_code" class="form-control" value="<?php echo $vendor_details['vendor_postal_code']; ?>">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-6">
                            <label> Sales Rep. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_sales_rep" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_sales_rep']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> Office No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_office_no" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_office_no']; ?>">
                        </div>
                        <div class="col-sm-3">
                            <label> Cell No. <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_cell_no" class="form-control input_tobe_masked" value="<?php echo $vendor_details['vendor_cell_no']; ?>">
                        </div>
                    </div>
                    <div class="form-group clearfix">
                        <div class="col-sm-6">
                            <label> Email Address <span class="text-danger-dker">*</span></label>
                            <input type="email" name="vendor_sales_rep_email_address" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_sales_rep_email_address']; ?>" style="text-transform:none !important">
                        </div>
                        <div class="col-sm-3">
                            <label> Shipping Cost <span class="text-danger-dker">*</span></label>
                            <input type="text" name="vendor_shipping_cost" class="form-control edit_vendor_input" value="<?php echo $vendor_details['vendor_shipping_cost']; ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-edit-vendor-information-close">Cancel</button>
                <button type="button" class="btn btn-success btn-save-edit-vendor-information">Save Information</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="serial_asset_no_modal_vendor_section" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10010;">
	<div class="modal-dialog" style="">
	    <div class="modal-content">
	      	<div class="modal-header">
	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        	<h4 class="modal-title">Add Serial & Asset No.</h4>
	      	</div>
	      	<div class="modal-body">
	      		<?php echo form_open("",array("id"=>"save_serial_asset_no_form_vendor_section")) ?>
	      			<div class="serial_asset_no_modal_content_vendor_section">

	      			</div>	
	      		<?php echo form_close() ?>
	      	</div>
	      	<div class="modal-footer" style="padding-left: 30px;padding-right: 30px;">
	      		<button type="button" class="btn btn-default skip_serial_asset_no_vendor_section pull-left"> Skip </button>
	      		<button type="button" class="btn btn-success save_serial_asset_no_vendor_section"> Save Changes </button>
	        	<button type="button" class="btn btn-danger close_serial_asset_no_vendor_section" data-dismiss="modal"> Close</button>
	    	</div>
	  	</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- 	
	<div role="tabpanel" class="tab-pane active" id="item_search">
		<div class="ng-scope">
		    <div class="panel-body" style="padding-left:0px;padding-right:0px;">
		    	<div class="form-group clearfix search-vendor-item-div">
					<div class="col-sm-6 col-sm-offset-3" style="text-align:center;">
						<i class="icon-social-dropbox" style="font-size: 75px;"></i>
						<div class="input-group" style="margin-top:10px !important;">
			   	 			<input type="text" class="form-control" id="search-item" name="term" style="text-transform:none !important;" autocomplete="off" value="" placeholder="Search by Company Item No., Item Description, Reorder No., Category">
			   	 			<span class="input-group-btn">
						        <button class="btn btn-default btn-submit-item-search" type="button" title="Search"><i class="fa fa-search"></i></button>
						    </span>
		   	 			</div>
		   	 			<div id="suggestion_container_item_search" style="z-index:9999;position:absolute;border:0px solid black;width:100%;padding-right:68px">
		   	 			</div>
		   	 		</div>
		   	 		<div class="col-sm-12 search_items_result_div" style="overflow-x: auto;">
		   	 		</div>
		   	 	</div>
		    </div>
		</div>
	</div> 
-->

<script type="text/javascript"> 

	$(document).ready(function(){

		$('.input_tobe_masked').mask("(999) 999-9999");

		$('.edit_vendor_date').datepicker({
        	dateFormat: 'mm/dd/yy'
  	   	});

		// Make the select html into select2
		$('.inventory_item_selected').select2();

		$(".inventory_item_selected").on("select2:open", function() {
		    $(".select2-search__field").attr("placeholder", "Item Search");
		});
		$(".inventory_item_selected").on("select2:close", function() {
		    $(".select2-search__field").attr("placeholder", null);
		});

		var spacesToAdd = 6;
		var biggestLength1 = 0;
		var biggestLength2 = 0;
		$(".inventory_item_selected option").each(function(){
			var parts = $(this).text().split('   ');
			if(parts[1] != undefined)
			{
				var len1 = parts[0].length;
			    if(len1 > biggestLength1){
			        biggestLength1 = len1;

			    }
			    var len2 = parts[1].length;
			    if(len2 > biggestLength2){
			        biggestLength2 = len2;
			    }
			}
		});

		var padLength1 = biggestLength1 + spacesToAdd;
		var padLength2 = biggestLength2 + spacesToAdd;
		$(".inventory_item_selected option").each(function(){
		    var parts = $(this).text().split('   ');
		    if(parts[1] != undefined)
		    {
		    	var strLength = parts[0].length;
			    for(var x=0; x<(padLength1-strLength); x++){
			        parts[0] = parts[0]+' '; 
			    }
			    var strLength2 = parts[1].length;
			    for(var x=0; x<(padLength2-strLength2); x++){
			        parts[1] = parts[1]+' '; 
			    }
			    $(this).text(parts[0].replace(/ /g, '\u00a0')+'  '+parts[1].replace(/ /g, '\u00a0')+' '+parts[2]).text;
		    }
		});

		$('body').on('click','.vendor_activate_button',function(){
  	   		var sign = $(this).attr("data-sign");
  	   		var vendor_id = $("body").find(".viewed_vendor_id").val();
  	   		
  	   		$.post(base_url+"inventory/change_vendor_activation/" + sign + "/" + vendor_id,"", function(response){
			 	if(sign == 0)
			 	{
			 		$("body").find("#vendor_activate_button_span").html('<label class="i-checks"><input type="checkbox" class="vendor_activate_button" data-sign="1" checked><i></i> Deactivated (Click to activate)</label>');
			 		me_message_v2({error:0,message:"Success! Vendor is already inactive."});
			 	}
			 	else
			 	{
			 		$("body").find("#vendor_activate_button_span").html('<label class="i-checks"><input type="checkbox" class="vendor_activate_button" data-sign="0" ><i></i> Make inactive</label>');
			 		me_message_v2({error:0,message:"Success! Vendor is now active."});
			 	}
			});
  	   	});

		$('body').on('click','.edit_vendor_information',function(){
			$('#modal_edit_vendor_information').modal('show');
		});

		$('body').on('click','.btn-edit-vendor-information-close',function(){
            $('#modal_edit_vendor_information').modal('hide');
        });

        // Delete the item from the system
	  	$('body').on('click','.delete_vendor_item',function(){
	  		var _this = $(this);
	  		var item_id = $(this).attr("data-item-id");

			jConfirm("Delete inventory item?","Warning", function(response){
		        if(response)
		        {
		        	$.post(base_url+"inventory/delete_vendor_item/"+1+"/"+item_id,"", function(response){
					 	var obj = $.parseJSON(response);
				        me_message_v2(obj);
				        if(obj['error'] == 0)
				        {
				        	setTimeout(function(){
								_this.parent("td").parent("tr").remove();
							},1000);
				        }
					});
		        }
	        });
	  	});

        $('body').on('click','.btn-save-edit-vendor-information',function(){
            var _this_save_btn = $(this);

  	   		jConfirm('<br />Save Changes?', 'Reminder', function(response){
				if(response)
				{
					//disable submit button until the order is process
					$(_this_save_btn).prop('disabled',true);

					$("#edit_vendor_information_form_validate").ajaxSubmit({
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

        /** Search Vendor Inventory Items **/
        $('#search-inventory-item').bind('keyup',function(){
        	var searchString = $(this).val();
			var vendor_id = $("body").find(".viewed_vendor_id").val();

		    if(searchString.length > 0)
		    {
		    	$.ajax({
		           	type:"POST",
		           	url:base_url+"inventory/search_inventory_item/"+vendor_id+"/?searchString="+searchString,
		           	success:function(response)
		           	{
		           		$("body").find(".search-inventory-item-div").css("height","260px");
		              	$('#suggestion_container_inventory_item_search').show();
		              	$('#suggestion_container_inventory_item_search').html(response);

		              	$('.editable-click.editable-itemlist').editable({
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

		              	$(".inventory_item_results").bind('click', function(){ 
		              		var item_id = $(this).attr('data-id');
		              		var inventory_item_id = $(this).attr('data-inventory-item-id');

		              		$.post(base_url+"inventory/get_searched_inventory_item/" + item_id +"/"+ inventory_item_id,"", function(response){
		              			var obj = $.parseJSON(response);
		              			var temp = "";
							 	var item_status = "";

							 	if(obj.item_details.item_status_location == "on_hand")
							 	{
							 		item_status = "On Hand";
							 	}
							 	else
							 	{
							 		item_status = "Active";
							 	}

				                temp = 	'<table class="table bg-white b-a table-hover search_inventory_items_result_table">'+
				                        	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
				                        		'<tr>'+
				                        			'<th class="search_inventory_item_td" style="width:10%;line-height:37px;">Item No.</th>'+
				                        			'<th class="search_inventory_item_td" style="width:15%;line-height:37px;">Item Description</th>'+
				                        			'<th class="search_inventory_item_td" style="width:14%;line-height:37px;">Item Reorder No.</th>'+
				                        			'<th class="search_inventory_item_td" style="width:15%;line-height:37px;">Serial No. </th>'+
				                        			'<th class="search_inventory_item_td" style="width:15%;line-height:37px;">Asset No.</th>'+
				                        			'<th class="search_inventory_item_td" style="width:11% ;">Warehouse Location</th>'+
				                        			'<th class="search_inventory_item_td" style="width:10%;line-height:37px;">Status</th>'+
				                        			'<th class="search_inventory_item_td" style="width:10%;line-height:37px;">Delete</th>'+
				                        		'</tr>'+
				                        	'</thead>'+
				                        	'<tbody class="">'+
				                        		'<tr>'+
				                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.item_details.inventory_item_id+'">'+obj.item_details.company_item_no +'</a></td>'+
				                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.item_details.inventory_item_id+'">'+obj.item_details.item_description +'</a></td>'+
				                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.item_details.inventory_item_id+'">'+obj.item_details.item_reorder_no +'</a></td>'+
				                        			'<td class="search_inventory_item_td">'+
				                        				'<a href="javascript:;"'+
					 										'id="item_serial_no"'+ 
										                    'data-pk="'+obj.item_details.inventory_item_id +'"'+  
										                    'data-url="'+base_url+'inventory/update_basic"'+  
										                    'data-title="Item serial no"'+ 
										                    'data-value="'+obj.item_details.item_serial_no +'"'+ 
										                    'data-type="text"'+ 
										                    'class="editable-click editable-noreload"'+
									        			   '>'+
									        			   	obj.item_details.item_serial_no+
									        			'</a>'+ 
				        							'</td>'+
				        							'<td class="search_inventory_item_td">'+
				                        				'<a href="javascript:;"'+
					 										'id="item_asset_no"'+ 
										                    'data-pk="'+obj.item_details.inventory_item_id +'"'+  
										                    'data-url="'+base_url+'inventory/update_basic"'+  
										                    'data-title="Item asset no"'+ 
										                    'data-value="'+obj.item_details.item_asset_no +'"'+ 
										                    'data-type="text"'+ 
										                    'class="editable-click editable-noreload"'+
									        			   '>'+
									        			   	obj.item_details.item_asset_no+
									        			'</a>'+ 
				        							'</td>'+
				                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.item_details.inventory_item_id+'">'+obj.item_details.item_warehouse_location+'</a></td>'+
				                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.item_details.inventory_item_id+'">'+item_status+'</a></td>'+
				                        			'<td class="search_inventory_item_td">'+
				                        				'<button type="button" data-inventory-item-id="'+obj.item_details.inventory_item_id+'" class="btn btn-xs btn-danger remove_inventory_item">'+ 
									        				'<i class="fa fa-trash-o"></i> Delete'+
									        			'</button>'+
				                        			'</td>'+
				                        		'</tr> '+
				                        	'</tbody>'+
				                      	'</table>';

							 	$("body").find(".search_inventory_items_result_div").html(temp);
							 	$("body").find(".search_inventory_items_result_table").DataTable({
			  																		fnDrawCallback: function( oSettings ) {
																						$.fn.editable.defaults.mode = 'popover';
																				      $('body .editable-click.editable-noreload').editable({
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
							});
		                 	$('#suggestion_container_inventory_item_search').hide();
		              	});
		           	},
		           	error:function(jqXHR, textStatus, errorThrown)
		          	{
		            	console.log(textStatus, errorThrown);
		          	}
		        });
		    }
		    else
		    {
		    	$("body").find(".search-inventory-item-div").css("height","34px");
		      	$('#suggestion_container_inventory_item_search').hide();
		    }
        });

		$('.btn-submit-inventory-item-search').bind('click',function(){
			var final_content = $("body").find("#search-inventory-item").val();
      		var vendor_id = $("body").find(".viewed_vendor_id").val();

      		if(final_content.length > 0)
      		{
      			$.post(base_url+"inventory/view_all_searched_inventory_items/"+vendor_id+"/"+final_content,"", function(response){
	      			var obj = $.parseJSON(response);
	      			var temp = "";
	      			var another_temp = "";
				 	var item_status = "";

				 	temp = 	'<table class="table bg-white b-a table-hover search_inventory_items_result_table">'+
	                        	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
	                        		'<tr>'+
	                        			'<th class="search_inventory_item_td" style="width:10%;line-height:37px;">Item No.</th>'+
	                        			'<th class="search_inventory_item_td" style="width:15%;line-height:37px;">Item Description</th>'+
	                        			'<th class="search_inventory_item_td" style="width:14%;line-height:37px;">Item Reorder No.</th>'+
	                        			'<th class="search_inventory_item_td" style="width:15%;line-height:37px;">Serial No. </th>'+
	                        			'<th class="search_inventory_item_td" style="width:15%;line-height:37px;">Asset No.</th>'+
	                        			'<th class="search_inventory_item_td" style="width:11% ;">Warehouse Location</th>'+
	                        			'<th class="search_inventory_item_td" style="width:10%;line-height:37px;">Status</th>'+
	                        			'<th class="search_inventory_item_td" style="width:10%;line-height:37px;">Delete</th>'+
	                        		'</tr>'+
	                        	'</thead>'+
	                        	'<tbody class="search_inventory_items_result_tbody">'+
	                        	'</tbody>'+
	                      	'</table>';

	                $("body").find(".search_inventory_items_result_div").html(temp);

	                if(obj.searched_items.length > 0)
	                {
	                	for(var val in obj.searched_items)
			  			{
			  				if(obj.searched_items[val].item_status_location == "on_hand")
						 	{
						 		item_status = "On Hand";
						 	}
						 	else
						 	{
						 		item_status = "Active";
						 	}

						 	another_temp += '<tr>'+
			                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.searched_items[val].inventory_item_id+'">'+obj.searched_items[val].company_item_no +'</a></td>'+
			                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.searched_items[val].inventory_item_id+'">'+obj.searched_items[val].item_description +'</a></td>'+
			                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.searched_items[val].inventory_item_id+'">'+obj.searched_items[val].item_reorder_no +'</a></td>'+
			                        			'<td class="search_inventory_item_td">'+
			                        				'<a href="javascript:;"'+
				 										'id="item_serial_no"'+ 
									                    'data-pk="'+obj.searched_items[val].inventory_item_id +'"'+  
									                    'data-url="'+base_url+'inventory/update_basic"'+  
									                    'data-title="Item serial no"'+ 
									                    'data-value="'+obj.searched_items[val].item_serial_no +'"'+ 
									                    'data-type="text"'+ 
									                    'class="editable-click editable-noreload"'+
								        			   '>'+
								        			   	obj.searched_items[val].item_serial_no+
								        			'</a>'+ 
			        							'</td>'+
			        							'<td class="search_inventory_item_td">'+
			                        				'<a href="javascript:;"'+
				 										'id="item_asset_no"'+ 
									                    'data-pk="'+obj.searched_items[val].inventory_item_id +'"'+  
									                    'data-url="'+base_url+'inventory/update_basic"'+  
									                    'data-title="Item asset no"'+ 
									                    'data-value="'+obj.searched_items[val].item_asset_no +'"'+ 
									                    'data-type="text"'+ 
									                    'class="editable-click editable-noreload"'+
								        			   '>'+
								        			   	obj.searched_items[val].item_asset_no+
								        			'</a>'+ 
			        							'</td>'+
			                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.searched_items[val].inventory_item_id+'">'+obj.searched_items[val].item_warehouse_location+'</a></td>'+
			                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.searched_items[val].inventory_item_id+'">'+item_status+'</a></td>'+
			                        			'<td class="search_inventory_item_td">'+
			                        				'<button type="button" data-inventory-item-id="'+obj.searched_items[val].inventory_item_id+'" class="btn btn-xs btn-danger remove_inventory_item">'+ 
								        				'<i class="fa fa-trash-o"></i> Delete'+
								        			'</button>'+
			                        			'</td>'+
			                        		'</tr>';
			  			}
			  			$("body").find(".search_inventory_items_result_tbody").html(another_temp);
			  			$("body").find(".search_inventory_items_result_table").DataTable({
			  																		fnDrawCallback: function( oSettings ) {
																						$.fn.editable.defaults.mode = 'popover';
																				      $('body .editable-click.editable-noreload').editable({
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
	                }
	                else
	                {	
	                	another_temp += '<p style="text-align:center;font-size:17px !important;">'+
				                			'No Result Found.'+
				                		'</p>';
				       	$("body").find(".search_inventory_items_result_div").html(another_temp);
	                }
				});
      		}
      		else
      		{
      			$("body").find(".search_inventory_items_result_div").html("");
      		}

      		$('#suggestion_container_inventory_item_search').hide();
		});

		$('body').on('click','.result-item-lists',function(){
			var final_content = $("body").find("#search-inventory-item").val();
      		var vendor_id = $("body").find(".viewed_vendor_id").val();
      		
      		if(final_content.length > 0)
      		{
      			$.post(base_url+"inventory/view_all_searched_inventory_items/"+vendor_id+"/"+final_content,"", function(response){
	      			var obj = $.parseJSON(response);
	      			var temp = "";
	      			var another_temp = "";
				 	var item_status = "";

				 	temp = 	'<table class="table bg-white b-a table-hover search_inventory_items_result_table">'+
	                        	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
	                        		'<tr>'+
	                        			'<th class="search_inventory_item_td" style="width:10%;line-height:37px;">Item No.</th>'+
	                        			'<th class="search_inventory_item_td" style="width:15%;line-height:37px;">Item Description</th>'+
	                        			'<th class="search_inventory_item_td" style="width:14%;line-height:37px;">Item Reorder No.</th>'+
	                        			'<th class="search_inventory_item_td" style="width:15%;line-height:37px;">Serial No. </th>'+
	                        			'<th class="search_inventory_item_td" style="width:15%;line-height:37px;">Asset No.</th>'+
	                        			'<th class="search_inventory_item_td" style="width:11% ;">Warehouse Location</th>'+
	                        			'<th class="search_inventory_item_td" style="width:10%;line-height:37px;">Status</th>'+
	                        			'<th class="search_inventory_item_td" style="width:10%;line-height:37px;">Delete</th>'+
	                        		'</tr>'+
	                        	'</thead>'+
	                        	'<tbody class="search_inventory_items_result_tbody">'+
	                        	'</tbody>'+
	                      	'</table>';

	                $("body").find(".search_inventory_items_result_div").html(temp);

	                if(obj.searched_items.length > 0)
	                {
	                	for(var val in obj.searched_items)
			  			{
			  				if(obj.searched_items[val].item_status_location == "on_hand")
						 	{
						 		item_status = "On Hand";
						 	}
						 	else
						 	{
						 		item_status = "Active";
						 	}

						 	another_temp += '<tr>'+
			                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.searched_items[val].inventory_item_id+'">'+obj.searched_items[val].company_item_no +'</a></td>'+
			                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.searched_items[val].inventory_item_id+'">'+obj.searched_items[val].item_description +'</a></td>'+
			                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.searched_items[val].inventory_item_id+'">'+obj.searched_items[val].item_reorder_no +'</a></td>'+
			                        			'<td class="search_inventory_item_td">'+
			                        				'<a href="javascript:;"'+
				 										'id="item_serial_no"'+ 
									                    'data-pk="'+obj.searched_items[val].inventory_item_id +'"'+  
									                    'data-url="'+base_url+'inventory/update_basic"'+  
									                    'data-title="Item serial no"'+ 
									                    'data-value="'+obj.searched_items[val].item_serial_no +'"'+ 
									                    'data-type="text"'+ 
									                    'class="editable-click editable-noreload"'+
								        			   '>'+
								        			   	obj.searched_items[val].item_serial_no+
								        			'</a>'+ 
			        							'</td>'+
			        							'<td class="search_inventory_item_td">'+
			                        				'<a href="javascript:;"'+
				 										'id="item_asset_no"'+ 
									                    'data-pk="'+obj.searched_items[val].inventory_item_id +'"'+  
									                    'data-url="'+base_url+'inventory/update_basic"'+  
									                    'data-title="Item asset no"'+ 
									                    'data-value="'+obj.searched_items[val].item_asset_no +'"'+ 
									                    'data-type="text"'+ 
									                    'class="editable-click editable-noreload"'+
								        			   '>'+
								        			   	obj.searched_items[val].item_asset_no+
								        			'</a>'+ 
			        							'</td>'+
			                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.searched_items[val].inventory_item_id+'">'+obj.searched_items[val].item_warehouse_location+'</a></td>'+
			                        			'<td class="search_inventory_item_td"><a target="_blank" href="'+base_url+'inventory/inventory_item_details/'+obj.searched_items[val].inventory_item_id+'">'+item_status+'</a></td>'+
			                        			'<td class="search_inventory_item_td">'+
			                        				'<button type="button" data-inventory-item-id="'+obj.searched_items[val].inventory_item_id+'" class="btn btn-xs btn-danger remove_inventory_item">'+ 
								        				'<i class="fa fa-trash-o"></i> Delete'+
								        			'</button>'+
			                        			'</td>'+
			                        		'</tr>';
			  			}
			  			$("body").find(".search_inventory_items_result_tbody").html(another_temp);
			  			$("body").find(".search_inventory_items_result_table").DataTable({
			  																		fnDrawCallback: function( oSettings ) {
																						$.fn.editable.defaults.mode = 'popover';
																				      $('body .editable-click.editable-noreload').editable({
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
	                }
	                else
	                {	
	                	another_temp += '<p style="text-align:center;font-size:17px !important;">'+
				                			'No Result Found.'+
				                		'</p>';
				       	$("body").find(".search_inventory_items_result_div").html(another_temp);
	                }
				});
      		}
      		else
      		{
      			$("body").find(".search_inventory_items_result_div").html("");
      		}

      		$('#suggestion_container_inventory_item_search').hide();
		});
      	
      	var globalTimeout = null;
      	$('.inventory_item_quantity').bind('keyup',function(){
      		var value_here = $(this).val();
      		var original_value_here = $(this).val();
            var _this = $(this);
            var temp = "";
            var item_id = $('body').find('select[name=inventory_item_selected]').val();
            var item_unit_measure_selected = $('body').find('select[name=inventory_item_unit_measure]').val();
            var unit_measure_value = 1;

            if(globalTimeout != null) clearTimeout(globalTimeout);  
			globalTimeout =setTimeout(getInfoFunc,1100);

			function getInfoFunc(){  
				globalTimeout = null;

				if(item_id != "" && item_unit_measure_selected != "")
	            {
	            	$.post(base_url+"inventory/get_item_unit_measure_value/"+ item_id +"/","", function(response){
			           	var obj_2 = $.parseJSON(response); 
	                
		                if(obj_2.item_unit_of_measures.length > 0)
		                {
		                	for(var val in obj_2.item_unit_of_measures)
			                {
			                	if(obj_2.item_unit_of_measures[val].item_unit_measure == item_unit_measure_selected)
			                	{
			                		unit_measure_value = obj_2.item_unit_of_measures[val].item_unit_value;
			                	}
			                }
		                }
			            
		                value_here *= unit_measure_value; 
		                setTimeout(function(){
			            	if(value_here != "" && value_here != 0)
			                {
			                	temp += '<div class="form-group" style="text-align: center;font-weight: bold; margin-bottom:0px !important;padding-bottom:10px; height:20px;">'+
			                                '<div class="col-sm-6">'+
			                                    'Serial No.'+
			                                '</div>'+
			                                '<div class="col-sm-6">'+
			                                    'Asset No.'+
			                                '</div>'+
			                            '</div>';

			                    for(var i = 1; i <= value_here; i++)
			                    {
			                        temp += '<div class="form-group" style="padding-top: 10px !important;height: 40px;">'+
				                                '<div class="col-sm-6">'+
				                                    '<input type="text" class="form-control " value="" name="serial_'+i+'">'+
				                                '</div>'+
				                                '<div class="col-sm-6">'+
				                                    '<input type="text" class="form-control add_item_asset_no" value="" name="asset_'+i+'">'+
				                                '</div>'+
				                            '</div>';
			                    }

			                    $("#serial_asset_no_modal_vendor_section").find(".serial_asset_no_modal_content_vendor_section").html(temp);
			                	$('#serial_asset_no_modal_vendor_section').modal("show");

			                    var second_globalTimeout = null; 
						        $('body').on('keyup','#serial_asset_no_modal_vendor_section .add_item_asset_no',function(){

						        	if(second_globalTimeout != null) clearTimeout(second_globalTimeout);  
									second_globalTimeout =setTimeout(getInfoFunc,1100); 

									function getInfoFunc(){  
										second_globalTimeout = null;

										var count_loop_each_sign = 0;
										$('body .add_item_asset_no').each(function(){
									      	var item_asset_no_value = $(this).val();
									      
									      	$.post(base_url+"inventory/check_item_asset_no_value/"+ item_asset_no_value,"", function(response){
							                    var obj = $.parseJSON(response); 

							                   	if(obj.item_asset_no_value.inventory_item_id != undefined)
							                   	{
							                   		$("body").find(".save_serial_asset_no_vendor_section").prop("disabled","disabled");
							                   		$("body").find(".skip_serial_asset_no_vendor_section").prop("disabled","disabled");
							                   		me_message_v2({error:1,message:"Asset No. already exists."});
							                   		count_loop_each_sign++;
							                   	}
							                   	else
							                   	{
						                   			if(count_loop_each_sign == 0)
							                   		{
							                   			$("body").find("#preloader-message").fadeOut(10);
							                   			$("body").find(".save_serial_asset_no_vendor_section").removeAttr("disabled");
							                   			$("body").find(".skip_serial_asset_no_vendor_section").removeAttr("disabled");
							                   		}
							                   	}
							                });
									    });
									}
								});
			                }
			           	},1000); 
				    });
	            }
	            else
	            {
	            	me_message_v2({error:1,message:"Need to select Item and Unit of Measure first."});
	            }
			}   
      	});

		$('body').on('click','.save_serial_asset_no_vendor_section',function(){
			var item_id = $('body').find('select[name=inventory_item_selected]').val();
			var item_unit_measure_selected = $('body').find('select[name=inventory_item_unit_measure]').val();
			var original_value_here = $('body').find(".inventory_item_quantity").val(); 
            var form_data = $('#save_serial_asset_no_form_vendor_section').serialize();

            $.post(base_url+"inventory/save_serial_asset_no_vendor_section/"+ item_id +"/"+ item_unit_measure_selected +"/"+ original_value_here ,form_data, function(response){

                var obj = $.parseJSON(response); 
                jAlert(obj['message'],"Reminder");
                if(obj['error'] == 0)
                {
                    setTimeout(function(){
                        $('body').find(".close_serial_asset_no_vendor_section").click();
                        location.reload();
                    },2500);
                    $("body").find(".returned_item_batch_no").val(obj['item_batch_no']);
                }
            });
        });

        $('body').on('click','.skip_serial_asset_no_vendor_section',function(){
        	var item_id = $('body').find('select[name=inventory_item_selected]').val();
			var item_unit_measure_selected = $('body').find('select[name=inventory_item_unit_measure]').val();
			var original_value_here = $('body').find(".inventory_item_quantity").val(); 
            var form_data = $('#save_serial_asset_no_form_vendor_section').serialize();

            $.post(base_url+"inventory/skip_serial_asset_no_vendor_section/"+ item_id +"/"+ item_unit_measure_selected +"/"+ original_value_here ,form_data, function(response){
                var obj = $.parseJSON(response); 
                jAlert(obj['message'],"Reminder");
                if(obj['error'] == 0)
                {
                    setTimeout(function(){
                        $('body').find(".close_serial_asset_no_vendor_section").click();
						location.reload();
                    },2500);
                    $("body").find(".returned_item_batch_no").val(obj['item_batch_no']);
                }
            });
        });

      	// Remove item from inventory
	  	$('body').on('click','.remove_inventory_item',function(){
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

	  	// Edit item from inventory
	  	$('body').on('click','.edit_inventory_item',function(){
	  		var _this = $(this);
	  		var inventory_item_id = $(this).attr("data-inventory-item-id");

        	modalbox(base_url + 'inventory/edit_inventory_item/'+ inventory_item_id ,{
                header:"Edit Inventory Item",
                button: false,
            });
	  	});

	  	$('body').on('click','.view_vendor_details_menu',function(){
	  		var active_tab = $(this);
	  		$('.view_vendor_details_menu').each(function() {
	  			$(this).css('font-weight','normal');
	  		});
	  		active_tab.css("font-weight","bold");
	  	});
	  	
	  	


	  	/////////////////////////////////////////////////////////////////
	  	/** Search Vendor Items **/
		// $('#search-item').bind('keyup',function(){
		//     var searchString = $(this).val();
		// 	var vendor_id = $("body").find(".viewed_vendor_id").val();

		//     if(searchString.length > 0)
		//     {
		//       	$.ajax({
		//            	type:"POST",
		//            	url:base_url+"inventory/search_item/"+vendor_id+"/?searchString="+searchString,
		//            	success:function(response)
		//            	{
		//            		$("body").find(".search-vendor-item-div").css("height","260px");
		//               	$('#suggestion_container_item_search').show();
		//               	$('#suggestion_container_item_search').html(response);

		//               	$(".item_results").bind('click', function(){ 
		//               		var item_id = $(this).attr('data-id');

		//               		$.post(base_url+"inventory/get_searched_item/" + item_id,"", function(response){
		//               			var obj = $.parseJSON(response);
		//               			var temp = "";
		// 					 	var item_status = "";

		// 					 	if(obj.item_details.item_active_sign == 1)
		// 					 	{
		// 					 		item_status = "Active";
		// 					 	}
		// 					 	else
		// 					 	{
		// 					 		item_status = "Inactive";
		// 					 	}

		// 		                temp = 	'<table class="table bg-white b-a table-hover search_items_result_table">'+
		// 		                        	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
		// 		                        		'<tr>'+
		// 		                        			'<th style="width:20% ;">Company Item No.</th>'+
		// 		                        			'<th style="width:28% ;">Item Description</th>'+
		// 		                        			'<th style="width:12% ;">Reorder No.</th>'+
		// 		                        			'<th style="width:20% ;">Warehouse Location</th>'+
		// 		                        			'<th style="width:10% ;">Status</th>'+
		// 		                        		'</tr>'+
		// 		                        	'</thead>'+
		// 		                        	'<tbody class="">'+
		// 		                        		'<tr>'+
		// 		                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.item_details.item_id+'" target="_blank">'+obj.item_details.company_item_no +'</a></td>'+
		// 		                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.item_details.item_id+'" target="_blank">'+obj.item_details.item_description +'</a></td>'+
		// 		                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.item_details.item_id+'" target="_blank">'+obj.item_details.item_reorder_no +'</a></td>'+
		// 		                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.item_details.item_id+'" target="_blank">'+obj.item_details.item_warehouse_location +'</a></td>'+
		// 		                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.item_details.item_id+'" target="_blank">'+item_status+'</a></td>'+
		// 		                        		'</tr> '+
		// 		                        	'</tbody>'+
		// 		                      	'</table>';

		// 					 	$("body").find(".search_items_result_div").html(temp);
		// 					});
		//                  	$('#suggestion_container_item_search').hide();
		//               	});
		//           	},
		//           	error:function(jqXHR, textStatus, errorThrown)
		//           	{
		//             	console.log(textStatus, errorThrown);
		//           	}
		//       	});
		//     }
		//     else
		//     {
		//     	$("body").find(".search-vendor-item-div").css("height","34px");
		//       	$('#suggestion_container_item_search').hide();
		//     }
		// });

		// $('body').on('click','#search-item',function(){
		// 	var final_content = $("body").find("#search-item").val();

		// 	if(final_content.length > 0)
		// 	{
		// 		$('#suggestion_container_item_search').show();
		// 	}
		// });

		// $('body').on('click','.result-lists',function(){
		// 	var final_content = $("body").find("#search-item").val();
		// 	var vendor_id = $("body").find(".viewed_vendor_id").val();

  //     		$.post(base_url+"inventory/view_all_searched_items/"+vendor_id+"/"+final_content,"", function(response){
  //     			var obj = $.parseJSON(response);
  //     			var temp = "";
  //     			var another_temp = "";
		// 	 	var item_status = "";

		// 	 	temp = 	'<table class="table bg-white b-a table-hover search_items_result_table">'+
  //                       	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
  //                       		'<tr>'+
  //                       			'<th style="width:20% ;">Company Item No.</th>'+
  //                       			'<th style="width:28% ;">Item Description</th>'+
  //                       			'<th style="width:12% ;">Reorder No.</th>'+
  //                       			'<th style="width:20% ;">Warehouse Location</th>'+
  //                       			'<th style="width:10% ;">Status</th>'+
  //                       		'</tr>'+
  //                       	'</thead>'+
  //                       	'<tbody class="search_items_result_tbody">'+
  //                       	'</tbody>'+
  //                     	'</table>';

  //               $("body").find(".search_items_result_div").html(temp);

  //           	for(var val in obj.searched_items)
	 //  			{
	 //  				if(obj.searched_items[val].item_active_sign == 1)
		// 		 	{
		// 		 		item_status = "Active";
		// 		 	}
		// 		 	else
		// 		 	{
		// 		 		item_status = "Inactive";
		// 		 	}

		// 		 	another_temp += '<tr>'+
		//                     			'<td><a href="'+base_url+'inventory/item_details/'+obj.searched_items[val].item_id+'" target="_blank">'+obj.searched_items[val].company_item_no +'</a></td>'+
	 //                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.searched_items[val].item_id+'" target="_blank">'+obj.searched_items[val].item_description +'</a></td>'+
	 //                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.searched_items[val].item_id+'" target="_blank">'+obj.searched_items[val].item_reorder_no +'</a></td>'+
	 //                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.searched_items[val].item_id+'" target="_blank">'+obj.searched_items[val].item_warehouse_location +'</a></td>'+
	 //                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.searched_items[val].item_id+'" target="_blank">'+item_status+'</a></td>'+
		//                     		'</tr>';
	 //  			}
	 //  			$("body").find(".search_items_result_tbody").html(another_temp);
		// 	});

  //     		$('#suggestion_container_item_search').hide();
  //     	});

  //    	$('.btn-submit-item-search').bind('click',function(){
  //     		var final_content = $("body").find("#search-item").val();
  //     		var vendor_id = $("body").find(".viewed_vendor_id").val();

  //     		if(final_content.length > 0)
  //     		{
  //     			$.post(base_url+"inventory/view_all_searched_items/"+vendor_id+"/"+final_content,"", function(response){
	 //      			var obj = $.parseJSON(response);
	 //      			var temp = "";
	 //      			var another_temp = "";
		// 		 	var item_status = "";

		// 		 	temp = 	'<table class="table bg-white b-a table-hover search_items_result_table">'+
	 //                        	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
	 //                        		'<tr>'+
	 //                        			'<th style="width:20% ;">Company Item No.</th>'+
	 //                        			'<th style="width:28% ;">Item Description</th>'+
	 //                        			'<th style="width:12% ;">Reorder No.</th>'+
	 //                        			'<th style="width:20% ;">Warehouse Location</th>'+
	 //                        			'<th style="width:10% ;">Status</th>'+
	 //                        		'</tr>'+
	 //                        	'</thead>'+
	 //                        	'<tbody class="search_items_result_tbody">'+
	 //                        	'</tbody>'+
	 //                      	'</table>';

	 //                $("body").find(".search_items_result_div").html(temp);

	 //                if(obj.searched_items.length > 0)
	 //                {
	 //                	for(var val in obj.searched_items)
		// 	  			{
		// 	  				if(obj.searched_items[val].item_active_sign == 1)
		// 				 	{
		// 				 		item_status = "Active";
		// 				 	}
		// 				 	else
		// 				 	{
		// 				 		item_status = "Inactive";
		// 				 	}

		// 				 	another_temp += '<tr>'+
		// 		                    			'<td><a href="'+base_url+'inventory/item_details/'+obj.searched_items[val].item_id+'" target="_blank">'+obj.searched_items[val].company_item_no +'</a></td>'+
		// 	                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.searched_items[val].item_id+'" target="_blank">'+obj.searched_items[val].item_description +'</a></td>'+
		// 	                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.searched_items[val].item_id+'" target="_blank">'+obj.searched_items[val].item_reorder_no +'</a></td>'+
		// 	                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.searched_items[val].item_id+'" target="_blank">'+obj.searched_items[val].item_warehouse_location +'</a></td>'+
		// 	                        			'<td><a href="'+base_url+'inventory/item_details/'+obj.searched_items[val].item_id+'" target="_blank">'+item_status+'</a></td>'+
		// 		                    		'</tr>';
		// 	  			}
		// 	  			$("body").find(".search_items_result_tbody").html(another_temp);
	 //                }
	 //                else
	 //                {	
	 //                	another_temp += '<p style="text-align:center;font-size:17px !important;">'+
		// 		                			'No Result Found.'+
		// 		                		'</p>';
		// 		       	$("body").find(".search_items_result_div").html(another_temp);
	 //                }
		// 		});
  //     		}
  //     		else
  //     		{
  //     			$("body").find(".search_items_result_div").html("");
  //     		}

  //     		$('#suggestion_container_item_search').hide();
  //     	});
	});

		


</script>




