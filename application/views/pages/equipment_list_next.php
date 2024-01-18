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

</style>

<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">Account Fee Schedule</h1>
</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">
   		<span class="">Assign Fee Schedule</span>
   		<span class="pull-right">Daily Rate: <?php echo $hospices[0]['daily_rate']; ?></span>
   	</div>
   		<?php if(!empty($hospices)) : ?>	
		<?php $hospices = $hospices[0] ;?>

		<form action="<?php echo base_url('equipment/submit_equipment_rates/') ;?>" method="POST" id="">
		<div class="row" style="padding-left: 15px !important; padding-right: 15px !important">
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
				                                	<input type="checkbox" name="equipment_id[]" <?php echo $checked; ?> class="cb_equipment assign_to_capped" value="<?php echo $equipment['equipmentID'] ?>" data-hospice="<?php echo $hospices['hospiceID'] ?>" data-category="<?php echo $equipment['categoryID']; ?>" data-id="<?php echo $equipment['equipmentID']; ?>"  <?php if($equipment['categoryID'] == 3){ echo "disabled";}?>/>
				                                	<i></i>
				                            	</label>
											</td>
											<td ><?php echo $equipment['key_desc'] ?></td>
											<td><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow" name="monthly_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['monthly_rate'], 2, '.', ''); ?>" style="margin-bottom:15px; background: white;" /></td>
											<td><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow" name="daily_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['daily_rate'], 2, '.', ''); ?>" style="margin-bottom:15px; background: white;" /></td>
											<td><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow" name="purchase_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['purchase_price'], 2, '.', ''); ?>" style="margin-bottom:15px; background: white;" /></td>
											<td id="button_col_<?php echo $equipment['equipmentID']; ?>">
												<button type="button" class="btn btn-info btn-xs hide_item" style="height:25px;" data-hospice="<?php echo $hospices['hospiceID'] ?>" data-category="<?php echo $equipment['categoryID']; ?>" data-id="<?php echo $equipment['equipmentID']; ?>"> <i class="glyphicon glyphicon-eye-close" style=""></i>&nbsp;&nbsp; Hide Item </button>
											</td>
										</tr>
						<?php
									}
									else
									{
						?>
										<tr id="equipment_tr_<?php echo $equipment['equipmentID']; ?>" style="background-color:rgba(138, 124, 124, 0.13);">
											<td id="checkbox_col_<?php echo $equipment['equipmentID']; ?>" style="padding:9px;"><input type="checkbox" name="equipment_id[]" class="cb_equipment assign_to_capped" value="<?php echo $equipment['equipmentID'] ?>" data-hospice="<?php echo $hospices['hospiceID'] ?>" data-category="<?php echo $equipment['categoryID']; ?>" data-id="<?php echo $equipment['equipmentID']; ?>" disabled /></td>
											<td ><?php echo $equipment['key_desc'] ?></td>
											<td><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow" name="monthly_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['monthly_rate'], 2, '.', ''); ?>" style="margin-bottom:15px; background: white;" /></td>
											<td><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow" name="daily_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['daily_rate'], 2, '.', ''); ?>" style="margin-bottom:15px; background: white;" /></td>
											<td><input type="text" onkeypress="return isNumberKey(event)" class="form-control grey_inner_shadow" name="purchase_rate[<?php echo $equipment['equipmentID']; ?>]" value="<?php echo number_format((float)$equipment['purchase_price'], 2, '.', ''); ?>" style="margin-bottom:15px; background: white;" /></td>
											<td id="button_col_<?php echo $equipment['equipmentID']; ?>">
												<button type="button" class="btn btn-primary btn-xs show_item" style="height:25px;" data-hospice="<?php echo $hospices['hospiceID'] ?>" data-category="<?php echo $equipment['categoryID']; ?>" data-id="<?php echo $equipment['equipmentID']; ?>"> <i class="glyphicon glyphicon-eye-open" style=""></i>&nbsp;&nbsp; Show Item </button>
											</td>
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
		<div class="row" style="margin: 50px;">
			<div class="col-md-8"></div>
			<div class="col-md-4 pull-right">
				<!-- <a href="<?php echo base_url('hospice/hospice_list/') ?>"> -->
					<button type="submit" class="btn btn-success btn-block" style="height:38px;">
						Register
					</button>
				<!-- </a> -->
			</div>
			
		</div>
		</form>

  </div>
</div>
