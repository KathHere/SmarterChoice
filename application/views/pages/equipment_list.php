<style type="text/css">

input[type="search"]
{
  margin-left: 13px;
}

select.input-sm
{
  margin-left: 11px;
  margin-right: 11px;
}

.circle-btn {
    width: 35px;
    height: 35px;
    position: relative;
    border-radius: 100%;
}

.icon-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    height: 50%;
    transform: translate(-50%, -50%);
    width: 15px;
    height: 15px;
    display: block;
}

.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
	background-color: #C8C8C8 !important;
	color: #fff !important;
	/*font-weight: bold;*/
}

.inputtag {
	background-color: #fff !important;
}

.ghost-button {
  display: inline-block;
  width: 200px;
  padding: 8px;
  color: #23b7e5;
  border: 1px solid #23b7e5;
  text-align: center;
  outline: none;
  text-decoration: none;
  background-color: #fff;
}

.ghost-button:hover,
.ghost-button:active {
  background-color: #23b7e5;
  color: #fff;
}

</style>

<div class="bg-light lter b-b wrapper-md">
	<h1 class="m-n font-thin h3">
		Account Fee Schedule
		<?php //if ($this->session->userdata('userID') == 85) { ?>
		<div class="pull-right">
			<button class="btn btn-info btn-sm change_new_process" style="font-size:13px !important; display: none; margin-top: -8px">
				+/- Item(s)
				<br>
				(<span id="change_new_process_counter">0</span>) Select Date
			</button>
		</div>
		<?php //} ?>
	</h1>
</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">
   		<span class="">Assign Fee Schedule</span>
   	</div>

   		<?php if(!empty($hospices)) : ?>
		<?php $hospices = $hospices[0] ;?>

		<form action="<?php echo base_url('equipment/submit_equipment_rates_autosave/') ;?>" id="fee_schedule_form" method="POST" id="">
		<div class="row" style="padding-left: 15px !important; padding-right: 15px !important">
			<div class="col-md-12 mt20 mb5">
				<strong>Daily Rate:
					<?php
					if ($hospices['daily_rate'] == 0 || $hospices['daily_rate'] == '') {
						echo "0.00";
					} else {
						echo number_format((float)$hospices['daily_rate'], 2, '.', '');
					}
					?>
				</strong>
			</div>
			<div class="col-md-6" style="padding-top:10px;">
				<label>Account Name: </label>
					<input type="text" class="form-control" name="hospice_name" value="<?php echo $hospices['hospice_name'] ?>" style="margin-bottom:15px; background: white;" readonly />
					<input type="hidden" class="form-control" name="hospiceID" value="<?php echo $hospices['hospiceID'] ?>" />
				<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->
			</div>
			<div class="col-md-6" style="padding-top:10px;">
				<label>Account Number: </label>
					<input type="text" class="form-control" name="" value="<?php echo $hospices['hospiceID']?>" style="margin-bottom:15px; background: white;" readonly />
					<!-- <input type="hidden" class="form-control" name="hospiceID" value="<?php echo $hospices['hospiceID'] ?>" /> -->
			</div>
		</div>

		<div class="row" style="margin-bottom:15px;">
			<div class="col-md-3" style="margin-bottom:15px">
			</div>
			<?php
				$hidden_counter = 0;
				foreach ($equipments as $equipment) :
					if($equipment['equipmentID'] != 316 && $equipment['equipmentID'] != 325 && $equipment['equipmentID'] != 334 && $equipment['equipmentID'] != 343 && $equipment['equipmentID'] != 457 && $equipment['equipmentID'] != 458)
					{
						$result = check_if_assigned_v2($equipment['equipmentID'],$hospices['hospiceID']);
						if(empty($result))
						{
							$hidden_counter++;
						}
					}
				endforeach;
			?>
			<div class="col-md-9" style="margin-bottom:15px;margin-top:30px;">
				<label style="font-weight:bold;">Capped: &nbsp;<span id="capped_counter_span"><?php echo count($capped_count); ?></span></label>
				<label style="font-weight:bold;margin-left:20px;">Non-Capped: &nbsp;<span id="non_capped_counter_span"><?php echo count($non_capped_count); ?></span></label>
				<label style="font-weight:bold;margin-left:20px;margin-right:20px;">Disposable: &nbsp;<span id="disposable_counter_span"><?php echo count($disposable_count); ?></span></label>
				<label style="font-weight:bold;">Hidden Item(s): &nbsp;<span id="hidden_items_counter_span"><?php echo $hidden_counter; ?></span></label>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<table id="assign_equip" class="table table-hover datatable_table_equipment" style=""> <!-- id sa table kay assign_equip to make it a datatable -->
					<thead>
						<tr>
							<th style="padding:9px;">Add Capped Items</th>
							<th>Item Description</th>
							<th>Monthly Rate</th>
							<th>Daily Rate</th>
							<th>Purchase Price</th>
							<th>Option</th>
						</tr>
					</thead>
					<tbody id="assign_equipment_tbody">
						<?php if(!empty($equipments_v3)) : ?>
						<?php
							foreach ($equipments_v3 as $equipment) :
								if($equipment['equipmentID'] != 316 && $equipment['equipmentID'] != 325 && $equipment['equipmentID'] != 334 && $equipment['equipmentID'] != 343 && $equipment['equipmentID'] != 457 && $equipment['equipmentID'] != 458)
								{
									$result = check_if_assigned_v2($equipment['equipmentID'],$hospices['hospiceID']);

									$disableMonthly = "";
									$disableDaily = "";
									$disablePurchase = "";

									if($equipment['categoryID'] == 1 || $equipment['categoryID'] == 2) {
										$disablePurchase = "disabled";
									}

									if($equipment['categoryID'] == 3) {
										$disableMonthly = "disabled";
										$disableDaily = "disabled";
									}

									if(!empty($result))
									{
										$capped_copy = check_capped_copy_v2($equipment['equipmentID']);
										$checked = "";
										if(in_array($capped_copy['equipmentID'], $capped_ids))
										{
											$checked = "checked";
										}
						?>
										<tr id="equipment_tr_<?php echo $equipment['equipmentID']; ?>">
											<td id="checkbox_col_<?php echo $equipment['equipmentID']; ?>" style="padding:9px;">

												<label class="i-checks data_tooltip" <?php if($equipment['categoryID'] == 3){ echo "style='cursor:not-allowed'";}?>>
				                                	<input type="checkbox" name="equipment_id[]" <?php echo $checked; ?> class="cb_equipment assign_to_capped <?php if ($equipment['categoryID'] != 3) { echo "changing_new_process"; } ?>" value="<?php echo $equipment['equipmentID'] ?>" data-hospice="<?php echo $hospices['hospiceID'] ?>" data-category="<?php echo $equipment['categoryID']; ?>" data-id="<?php echo $equipment['equipmentID']; ?>" data-key-desc="<?php echo $equipment['key_desc'] ?>" <?php if($equipment['categoryID'] == 3){ echo "disabled";}?>/>
				                                	<i></i>
				                            	</label>
											</td>
											<td ><?php echo $equipment['key_desc'] ?></td>
											<?php
											if($equipment['equipmentID'] == 49 || $equipment['equipmentID'] == 64 || $equipment['equipmentID'] == 32 || $equipment['equipmentID'] == 29) { ?>
												<td style="display:none"></td>
												<td colspan="3">
													<div class="" style="text-align: center">
														<!-- <button class="btn btn-default circle-btn" type="button" data-toggle="collapse" data-target="#collapseExample_<?php echo $equipment['equipmentID']; ?>" aria-expanded="false" aria-controls="collapseExample_<?php echo $equipment['equipmentID']; ?>">
															<i class="glyphicon glyphicon-chevron-down icon-btn"></i>
													  	</button> -->
													  	<button style="width:95%" class="btn ghost-button itemgroup_btn" type="button" data-target="#itemgroup_<?php echo $equipment['equipmentID']; ?>" data-toggle="modal" data-assigned-equipment-id="<?php echo $equipment['ID']; ?>" data-equipment-id="<?php echo $equipment['equipmentID']; ?>" data-category-id="<?php echo $equipment['categoryID']; ?>">
															<i class="glyphicon glyphicon-plus-sign"></i> Add Rate
													  	</button>
													</div>
												</td>
												<td style="display:none"></td>
											<?php } else { ?>
											<td style="text-align: center"><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow fee_schedule" name="monthly_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['monthly_rate'], 2, '.', ''); ?>" <?php echo $disableMonthly; ?> style="margin-bottom:15px; background: white;" /></td>
											<td style="text-align: center"><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow fee_schedule" name="daily_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['daily_rate'], 2, '.', ''); ?>" <?php echo $disableDaily; ?> style="margin-bottom:15px; background: white;" /></td>
											<td style="text-align: center"><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow fee_schedule" name="purchase_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['purchase_price'], 2, '.', ''); ?>" <?php echo $disablePurchase; ?> style="margin-bottom:15px; background: white;" /></td>
											<?php } ?>
											<td id="button_col_<?php echo $equipment['equipmentID']; ?>">
												<div class="col-md-6">
													<button type="button" class="btn btn-info btn-xs hide_item" style="height:25px;" data-hospice="<?php echo $hospices['hospiceID'] ?>" data-category="<?php echo $equipment['categoryID']; ?>" data-id="<?php echo $equipment['equipmentID']; ?>"> <i class="glyphicon glyphicon-eye-close" style=""></i>&nbsp;&nbsp; Hide Item </button>
												</div>
												<?php
													if($equipment['equipmentID'] == 49 || $equipment['equipmentID'] == 64 || $equipment['equipmentID'] == 32 || $equipment['equipmentID'] == 29) { ?>
													<!-- div class="col-md-6">
													  	<button class="btn btn-default circle-btn itemgroup_btn" type="button" data-target="#itemgroup_<?php echo $equipment['equipmentID']; ?>" data-toggle="modal" data-assigned-equipment-id="<?php echo $equipment['ID']; ?>" data-equipment-id="<?php echo $equipment['equipmentID']; ?>" data-category-id="<?php echo $equipment['categoryID']; ?>">
															<i class="glyphicon glyphicon-plus-sign icon-btn"></i>
													  	</button>
													</div> -->
												<?php } ?>
											</td>
											<input type="hidden" name="equipmentItems[<?php echo $equipment['equipmentID']; ?>]" value="0" />
										</tr>
						<?php
									}
									else
									{
						?>
										<tr id="equipment_tr_<?php echo $equipment['equipmentID']; ?>" style="background-color:rgba(138, 124, 124, 0.13);">
											<td id="checkbox_col_<?php echo $equipment['equipmentID']; ?>" style="padding:9px;">
												<label class="i-checks data_tooltip">
													<input type="checkbox" name="equipment_id[]" class="cb_equipment assign_to_capped <?php if ($equipment['categoryID'] != 3) { echo "changing_new_process"; } ?>" value="<?php echo $equipment['equipmentID'] ?>" data-hospice="<?php echo $hospices['hospiceID'] ?>" data-category="<?php echo $equipment['categoryID']; ?>" data-id="<?php echo $equipment['equipmentID']; ?>" data-key-desc="<?php echo $equipment['key_desc'] ?>" disabled />
													<i></i>
				                            	</label>
											</td>
											<td ><?php echo $equipment['key_desc'] ?></td>
											<?php
											if($equipment['equipmentID'] == 49 || $equipment['equipmentID'] == 64 || $equipment['equipmentID'] == 32 || $equipment['equipmentID'] == 29) { ?>
												<td style="display:none"></td>
												<div class="" style="text-align: center">
														<!-- <button class="btn btn-default circle-btn" type="button" data-toggle="collapse" data-target="#collapseExample_<?php echo $equipment['equipmentID']; ?>" aria-expanded="false" aria-controls="collapseExample_<?php echo $equipment['equipmentID']; ?>">
															<i class="glyphicon glyphicon-chevron-down icon-btn"></i>
													  	</button> -->
													  	<button style="width:95%" class="btn ghost-button itemgroup_btn" type="button" data-target="#itemgroup_<?php echo $equipment['equipmentID']; ?>" data-toggle="modal" data-assigned-equipment-id="<?php echo $equipment['ID']; ?>" data-equipment-id="<?php echo $equipment['equipmentID']; ?>" data-category-id="<?php echo $equipment['categoryID']; ?>">
															<i class="glyphicon glyphicon-plus-sign"></i> Add Rate
													  	</button>
													</div>
												</td>
												<td style="display:none"></td>
											<?php } else { ?>
											<td style="text-align: center"><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow fee_schedule" name="monthly_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['monthly_rate'], 2, '.', ''); ?>" <?php echo $disableMonthly; ?> style="margin-bottom:15px; background: white;" /></td>
											<td style="text-align: center"><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow fee_schedule" name="daily_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['daily_rate'], 2, '.', ''); ?>" <?php echo $disableDaily; ?> style="margin-bottom:15px; background: white;" /></td>
											<td style="text-align: center"><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow fee_schedule" name="purchase_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['purchase_price'], 2, '.', ''); ?>" <?php echo $disablePurchase; ?> style="margin-bottom:15px; background: white;" /></td>
											<?php } ?>
											<td id="button_col_<?php echo $equipment['equipmentID']; ?>">
												<div class="col">
													<button type="button" class="btn btn-primary btn-xs show_item" style="height:25px;" data-hospice="<?php echo $hospices['hospiceID'] ?>" data-category="<?php echo $equipment['categoryID']; ?>" data-id="<?php echo $equipment['equipmentID']; ?>"> <i class="glyphicon glyphicon-eye-open" style=""></i>&nbsp;&nbsp; Show Item </button>
												</div>
												<?php
													if($equipment['equipmentID'] == 49 || $equipment['equipmentID'] == 64 || $equipment['equipmentID'] == 32 || $equipment['equipmentID'] == 29) { ?>
													<!-- <div class="col-md-6">
													  	<button class="btn btn-default circle-btn itemgroup_btn" type="button" data-target="#itemgroup_<?php echo $equipment['equipmentID']; ?>" data-toggle="modal" data-assigned-equipment-id="<?php echo $equipment['ID']; ?>" data-equipment-id="<?php echo $equipment['equipmentID']; ?>" data-category-id="<?php echo $equipment['categoryID']; ?>">
															<i class="glyphicon glyphicon-plus-sign icon-btn"></i>
													  	</button>
													</div> -->
												<?php } ?>
											</td>
											<input type="hidden" name="equipmentItems[<?php echo $equipment['equipmentID']; ?>]" value="0" />
										</tr>
						<?php
									}
								}
							endforeach;
						?> <!-- End sa foreach adtu sa taas :) -->
						<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->

					</tbody>
				</table>
			</div>
		</div>
		<!-- <div class="row" style="margin: 50px;">
			<div class="col-md-8"></div>
			<div class="col-md-4 pull-right">
				<button type="submit" class="btn btn-success btn-block" style="height:38px;">
					Save Changes
				</button>
			</div>
		</div> -->
		</form>

  </div>
</div>

<div class="modal fade modal_item_group" id="itemgroup_49" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
            <div class="modal-header">
              <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">WHEELCHAIR</h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
                <div class="row">
                    <div class="">
                        <div class="col-md-12">
                        	<ul class="nav nav-tabs">
							    <li class="active"><a data-toggle="tab" href="#w16">16" NARROW</a></li>
							    <li><a data-toggle="tab" href="#w18">18" STANDARD</a></li>
							    <li><a data-toggle="tab" href="#w20">20" WIDE</a></li>
							    <li><a data-toggle="tab" href="#w22">22" EXTRA WIDE</a></li>
							    <li><a data-toggle="tab" href="#w24">24" BARIATRIC</a></li>
						  	</ul>
						  	<form action="<?php echo base_url('equipment/submit_itemgroup_equipment_rates/') ;?>" id="itemgoup_form_49" method="POST">
						  		<input type="hidden" id="equipmentID_49" name="equipmentID" value="" />
						  		<input type="hidden" id="assigned_equipmentID_49" name="assigned_equipmentID" value="" />
						  		<div class="tab-content tab-content-49" style="margin-top: 20px;">
						  			<input type="hidden" name="subequipment[]" value="16_inch" />
								    <div id="w16" class="tab-pane fade in active">
							    		<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon 16_inch_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail 16_inch_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch 16_inch_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <input type="hidden" name="subequipment[]" value="18_inch" />
								    <div id="w18" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon 18_inch_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail 18_inch_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch 18_inch_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <input type="hidden" name="subequipment[]" value="20_inch" />
								    <div id="w20" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon 20_inch_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail 20_inch_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch 20_inch_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <input type="hidden" name="subequipment[]" value="22_inch" />
								    <div id="w22" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon 22_inch_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail 22_inch_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch 22_inch_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <input type="hidden" name="subequipment[]" value="24_inch" />
								    <div id="w24" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon 24_inch_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail 24_inch_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch 24_inch_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
									</div>
							  	</div>
						  	</form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                <button type="button" id="submit_itemgroup_49" class="btn btn-primary submit-itemgroup-btn" data-assigned-equipment-id="0" data-equipment-id="0">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal_item_group" id="itemgroup_64" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
            <div class="modal-header">
              <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">WHEELCHAIR RECLINING</h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
                <div class="row">
                    <div class="">
                        <div class="col-md-12">
                        	<ul class="nav nav-tabs">
							    <li class="active"><a data-toggle="tab" href="#wr16">16" NARROW</a></li>
							    <li><a data-toggle="tab" href="#wr18">18" STANDARD</a></li>
							    <li><a data-toggle="tab" href="#wr20">20" WIDE</a></li>
							    <!-- <li><a data-toggle="tab" href="#w22">22" EXTRA WIDE</a></li>
							    <li><a data-toggle="tab" href="#w24">24" BARIATRIC</a></li> -->
						  	</ul>
						  	<form action="<?php echo base_url('equipment/submit_itemgroup_equipment_rates/') ;?>" id="itemgoup_form_64" method="POST">
						  		<input type="hidden" id="equipmentID_64" name="equipmentID" value="" />
						  		<input type="hidden" id="assigned_equipmentID_64" name="assigned_equipmentID" value="" />
						  		<div class="tab-content tab-content-64" style="margin-top: 20px;">
						  			<input type="hidden" name="subequipment[]" value="16_inch" />
								    <div id="wr16" class="tab-pane fade in active">
							    		<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon 16_inchr_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail 16_inchr_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch 16_inchr_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <input type="hidden" name="subequipment[]" value="18_inch" />
								    <div id="wr18" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon 18_inchr_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail 18_inchr_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch 18_inchr_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <input type="hidden" name="subequipment[]" value="20_inch" />
								    <div id="wr20" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon 20_inchr_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail 20_inchr_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch 20_inchr_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <!-- <input type="hidden" name="subequipment[]" value="22_inch" />
								    <div id="w22" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 22_inch_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 22_inch_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 22_inch_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <input type="hidden" name="subequipment[]" value="24_inch" />
								    <div id="w24" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 24_inch_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 24_inch_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 24_inch_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
									</div> -->
							  	</div>
						  	</form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                <button type="button" id="submit_itemgroup_64" class="btn btn-primary submit-itemgroup-btn" data-assigned-equipment-id="0" data-equipment-id="0">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal_item_group" id="itemgroup_32" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 700px !important">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
            <div class="modal-header">
              <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">OXYGEN CYLINDER RACK</h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
                <div class="row">
                    <div class="">
                        <div class="col-md-12">
                        	<ul class="nav nav-tabs">
							    <li class="active"><a data-toggle="tab" href="#ec6r">E CYLINDER - 6 RACK</a></li>
							    <li><a data-toggle="tab" href="#ec12r">E CYLINDER - 12 RACK</a></li>
							    <li><a data-toggle="tab" href="#mc6r">M6 CYLINDER - 6 RACK</a></li>
							    <li><a data-toggle="tab" href="#mc12r">M6 CYLINDER - 12 RACK</a></li>
						  	</ul>
						  	<form action="<?php echo base_url('equipment/submit_itemgroup_equipment_rates/') ;?>" id="itemgoup_form_32" method="POST">
						  		<input type="hidden" id="equipmentID_32" name="equipmentID" value="" />
						  		<input type="hidden" id="assigned_equipmentID_32" name="assigned_equipmentID" value="" />
						  		<div class="tab-content tab-content-32" style="margin-top: 20px;">
						  			<input type="hidden" name="subequipment[]" value="e_cylinder_6_rack" />
								    <div id="ec6r" class="tab-pane fade in active">
							    		<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon e_cylinder_6_rack_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail e_cylinder_6_rack_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch e_cylinder_6_rack_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <input type="hidden" name="subequipment[]" value="e_cylinder_12_rack" />
								    <div id="ec12r" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon e_cylinder_12_rack_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail e_cylinder_12_rack_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch e_cylinder_12_rack_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <input type="hidden" name="subequipment[]" onkeypress="return isNumberKey(event)" value="m6_cylinder_6_rack" />
								    <div id="mc6r" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon m6_cylinder_6_rack_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail m6_cylinder_6_rack_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch m6_cylinder_6_rack_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <input type="hidden" name="subequipment[]" onkeypress="return isNumberKey(event)" value="m6_cylinder_12_rack" />
								    <div id="mc12r" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon m6_cylinder_12_rack_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail m6_cylinder_12_rack_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch m6_cylinder_12_rack_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
							  	</div>
						  	</form>
						  	
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                <button type="button" id="submit_itemgroup_32" class="btn btn-primary submit-itemgroup-btn" data-assigned-equipment-id="0" data-equipment-id="0">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal_item_group" id="itemgroup_29" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
            <div class="modal-header">
              <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">OXYGEN CONCENTRATOR</h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
                <div class="row">
                    <div class="">
                        <div class="col-md-12">
                        	<ul class="nav nav-tabs">
							    <li class="active"><a data-toggle="tab" href="#5L">5L OXYGEN CONCENTRATOR</a></li>
							    <li><a data-toggle="tab" href="#10L">10L OXYGEN CONCENTRATOR</a></li>
						  	</ul>
						  	<form action="<?php echo base_url('equipment/submit_itemgroup_equipment_rates/') ;?>" id="itemgoup_form_29" method="POST">
						  		<input type="hidden" id="equipmentID_29" name="equipmentID" value="" />
						  		<input type="hidden" id="assigned_equipmentID_29" name="assigned_equipmentID" value="" />
						  		<div class="tab-content tab-content-29" style="margin-top: 20px;">
						  			<input type="hidden" name="subequipment[]" value="5_liters" />
								    <div id="5L" class="tab-pane fade in active">
							    		<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon 5_liters_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail 5_liters_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch 5_liters_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
								    <input type="hidden" name="subequipment[]" value="10_liters" />
								    <div id="10L" class="tab-pane fade">
								    	<div class="form-group">
										    <label for="exampleInputEmail1">Monthly Rate</label>
										    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag mon 10_liters_monthlyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Daily Rate</label>
										    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag dail 10_liters_dailyrate" id="" placeholder="" value="0.0">
									  	</div>
									  	<div class="form-group">
										    <label for="exampleInputEmail1">Purchase Price</label>
										    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag purch 10_liters_purchaseprice" id="" placeholder="" value="0.0">
									  	</div>
								    </div>
							  	</div>
						  	</form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                <button type="button" id="submit_itemgroup_29" class="btn btn-primary submit-itemgroup-btn" data-assigned-equipment-id="0" data-equipment-id="0">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changing_new_process_modal" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 600px !important;">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
        <!-- text-transform: uppercase; -->
            <div class="modal-header">
                <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reminder</h4>
            </div>
            <form id="us_mail_form">
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="no_email_content">
                        <input type="hidden" id="us_mail_invoice_id" value="" name="us_mail_invoice_id">
                        <input type="hidden" id="us_mail_invoice_status" value="" name="us_mail_invoice_status">
                        <div id="" style=" margin: 30px">
							<ul id="changing_new_process_modal_list"></ul>
                            <div class="form-group" style="margin-right:0px; text-align:center;" id="">
                                <label style="margin-left:5px;"> Select Date<span class="text-danger-dker">*</span></label>
                                <input type="text" class="form-control changing_new_process_date" value="" placeholder="Date" name="changing_new_process_date" style="">
                            </div>
                        </div>
                    </div>
                    <div class="error_content"></div>
                </div>
                <div class="modal-footer" id="popup_panel">
                    <button type="button" class="btn btn-success save_select_date" id="" style="color:#fff" autocomplete="off" disabled>
                        <span class="glyphicon glyphicon-ok"></span> &nbsp;Save&nbsp;
                    </button>
                    <button type="button" class="btn btn-default"  data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove"></span> &nbsp;Cancel&nbsp;
                    </button>
                </div>
            </form>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                <button type="button" class="btn btn-primary btn-order save_floor_mat_capped">Save changes</button>
            </div> -->
        </div>
    </div>
</div>

<input type="hidden" id="date_today_3months" value="<?php echo $date_today_3months; ?>">
<input type="hidden" id="get_base_url" value="<?php echo base_url(); ?>">
<input type="hidden" id="hospice_id_selected" value="<?php echo $hospices['hospiceID']; ?>">

<script type="text/javascript">
	$(document).ready(function(){
		var list_of_selected = [];
		var list_of_selected_id = [];
		var date_today_3months = $('#date_today_3months').val();
		console.log('date_today_3months', date_today_3months);
		$('.changing_new_process_date').datepicker({
            dateFormat: 'mm-dd-yy',
        	minDate: new Date(date_today_3months)
        });

		$('body').on('click','.save_select_date',function(){
			var hospiceID = $('#hospice_id_selected').val();
			var baseurl = $('#get_base_url').val();
			var temp_date_selected = $('.changing_new_process_date').val();

			jConfirm('<br />Confirm?', 'Reminder', function(response){
                if(response)
                {
					var formData = new FormData();
					formData.append('hospiceID', hospiceID);
					formData.append('date_selected', temp_date_selected);
					formData.append('equipment_ids', list_of_selected_id);

					$.ajax(base_url+'equipment/change_item_category/',
					{
						method: 'POST',
						data: formData,
						processData: false,
						contentType: false,
						success: function(data){
							console.log('change_item_category', data);
							var parse_data = $.parseJSON(data);
							
							me_message_v2({error:parse_data.error, message: parse_data.message});
							if (parse_data.error == 0) {
								setTimeout(function(){
									location.reload();
								},1000); 
							}
						},
						error: function(data){console.log(data)}
					});
				}
			});
			
		});

		$('body').on('click','.datatable_table_equipment .changing_new_process',function(){
			var _this = $(this);
			var equipment_id = _this.attr('data-id');
			var data_key_desc = _this.attr('data-key-desc');
			var changeNewProcessCounter = parseInt($('#change_new_process_counter').html());
    		console.log('changeNewProcessCounter', changeNewProcessCounter);

			if (_this.is(":checked")) {
				changeNewProcessCounter++;
				list_of_selected.push(data_key_desc);
				list_of_selected_id.push(equipment_id);
			} else {
				var index = list_of_selected.indexOf(data_key_desc);
				console.log('index', index);
				if (index > -1) {
					list_of_selected.splice(index, 1);
					list_of_selected_id.splice(index, 1);
					changeNewProcessCounter--;
				}
				
			}
			

			$('#change_new_process_counter').html(changeNewProcessCounter);
			console.log('list_of_selected', list_of_selected);
			console.log('list_of_selected_id', list_of_selected_id);
			console.log('data_key_desc', data_key_desc);

			if (changeNewProcessCounter > 0 ) {
				// $("body").find(".change_new_process").removeAttr('disabled');
				var list_of_selected_html = '';
				for(var i = 0; i < list_of_selected.length; i++) {
					list_of_selected_html += '<li>' + list_of_selected[i] + '</li>';
				}

				$('#changing_new_process_modal_list').html(list_of_selected_html);
				
				$("body").find(".change_new_process").show();
			} else {
				// $("body").find(".change_new_process").attr('disabled', true);
				$("body").find(".change_new_process").hide();
			}
		});

		$('body').on('click','.change_new_process',function(){
			var _this = $(this);
			
			$('#changing_new_process_modal').modal('show');
		});

		$('body').on('change','.changing_new_process_date',function(){
			var _this = $(this);

			if (_this.val() != "" && typeof _this.val() != undefined && _this.val() != null) {
				$("body").find(".save_select_date").removeAttr('disabled');
			} else {
				$("body").find(".save_select_date").attr('disabled', true);
			}
		});

		$(".fee_schedule").focusout(function(){
			var post_url = $('#fee_schedule_form').attr('action');
		    $.post(post_url, $('#fee_schedule_form').serialize(), function (response) {
		    	console.log("responseresponseresponse:", response);
		    	var obj = $.parseJSON(response);
                console.log("closecloseclose:", obj);
                me_message_v2({error:obj['error'],message:obj['message']});
            });
		});
	});

	$('.itemgroup_btn').bind('click',function(){
	    	var equipment_id = $(this).attr('data-equipment-id');
	    	var assigned_equipment_id = $(this).attr('data-assigned-equipment-id');
	    	var category_id = parseInt($(this).attr('data-category-id'));
	    	var disablePurchase = false;
	    	var disableMonthly = false;
	    	var disableDaily = false;

	    	if(category_id == 1 || category_id == 2) {
				disablePurchase = true;
			}

			if(category_id == 3) {
				disableMonthly = true;
				disableDaily = true;
			}

	    	$('#equipmentID_'+equipment_id).val(equipment_id);
	    	$('#assigned_equipmentID_'+equipment_id).val(assigned_equipment_id);
	    	$('#submit_itemgroup_'+equipment_id).attr('data-assigned-equipment-id', assigned_equipment_id);
	    	$('#submit_itemgroup_'+equipment_id).attr('data-equipment-id', equipment_id);

	    	
	    	$.get(base_url+'equipment/get_itemgroup_equipment_rates/'+assigned_equipment_id, function(response){
                console.log("resepposnse:", response);
                var obj = $.parseJSON(response);
                console.log("closecloseclose:", obj);
                // $('.close').click();
                
                if(obj.length > 0) {
                	for(var i = 0; i < obj.length; i++) {
	            		var val = obj[i];
	            		var reclining = "";

	            		if(val.equipmentID == 64) {
	            			reclining = "r";
	            		}

	            		$('.' + val.key_name + reclining + "_monthlyrate").val(parseFloat(val.monthly_rate).toFixed(2));
	            		$('.' + val.key_name + reclining + "_monthlyrate").attr("readonly", disableMonthly);

	            		$('.' + val.key_name + reclining + "_dailyrate").val(parseFloat(val.daily_rate).toFixed(2));
	            		$('.' + val.key_name + reclining + "_dailyrate").attr("readonly", disableDaily);

	            		$('.' + val.key_name + reclining + "_purchaseprice").val(parseFloat(val.purchase_price).toFixed(2));
	            		$('.' + val.key_name + reclining + "_purchaseprice").attr("readonly", disablePurchase);
	            	}
                	
                } else {
                	var mon = $('.tab-content-'+equipment_id).find('.mon');
                	var dail = $('.tab-content-'+equipment_id).find('.dail');
                	var purch = $('.tab-content-'+equipment_id).find('.purch');

                	mon.each(function() {
						console.log('mon', $(this).val());
						$(this).val("0.0");
                		$(this).attr("readonly", disableMonthly);
                	});

                	dail.each(function() {
						console.log('dail', $(this).val());
						$(this).val("0.0");
                		$(this).attr("readonly", disableDaily);
                	});

                	purch.each(function() {
						console.log('purch', $(this).val());
						$(this).val("0.0");
                		$(this).attr("readonly", disablePurchase);
                	});
                }
               
                
            });
	    });

	    $('.submit-itemgroup-btn').bind('click',function(){
	    	var equipment_id = $(this).attr('data-equipment-id');
	    	var assigned_equipment_id = $(this).attr('data-assigned-equipment-id');

	    	console.log('checkcheck');
	    	me_message_v2({error:2,message:"Saving data..."});
	    	var post_url = $('#itemgoup_form_'+equipment_id).attr('action');
	    	console.log('post_url', post_url);
	    	console.log('equipment_id', equipment_id);
	    	console.log('assigned_equipment_id', assigned_equipment_id);
	    	$.post(post_url, $('#itemgoup_form_'+equipment_id).serialize(), function (response) {
		    	console.log("resepposnse:", response);
                var obj = $.parseJSON(response);
                console.log("parseJSON:", obj);
                
                setTimeout(function(){
                    if(obj['error'] == 0)
                    {
                        me_message_v2({error:0,message:obj['message']});
                        // setTimeout(function(){
                        //     location.reload();
                        // },2000);
                    } else {
                        me_message_v2({error:1,message:"Error!"});
                    }
                },1);
            });
	    });
</script>