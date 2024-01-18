<section class="form-container">
	<div class="row">
		<div class="container">
			<div class="col-md-12">
				<div class="inner-form-container page-shadow OpenSans-Reg" style="margin-top: 140px;;min-height: 650px;padding: 28px;background-position: 37% 101.4%;">
					<div class="row">
					<?php if(!empty($hospices)) : ?>	
					<?php $hospices = $hospices[0] ;?>

					<?php echo form_open("",array("id"=>"assign_equip_form".$hospices['hospiceID'])) ?>

					<div class="col-md-6" style="margin-bottom:35px">
						<label>Hospice Name: </label>
						
							<input type="text" class="form-control" name="hospice_name" value="<?php echo $hospices['hospice_name'] ?>" style="margin-bottom:15px" readonly />
							<button type="button" class="btn btn-danger btn-save-equipment" data-id="<?php echo $hospices['hospiceID'] ?>" disabled >Save Changes</button> <span style="color:red;">*</span> Please choose equipments first.
							<input type="hidden" class="form-control" name="hospiceID" value="<?php echo $hospices['hospiceID'] ?>" />
						<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->
					</div>
					
					<div class="col-md-12">
						<table id="assign_equip" class="table table-hover" style="">
							<thead>
								<tr>
									<th class="visible-lg" style="padding:9px;"><input type="checkbox" class="chkAll_equipments" /></th>
									<th class="visible-lg">Equipment Name</th>
									<th class="visible-lg">Equipment Type</th>
									
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($equipments)) : ?>	
								<?php 
									foreach ($equipments as $equipment) : 
										$assigned = get_assigned_equipment($hospices['hospiceID']);
										$checked = "";
										if(in_array($equipment['equipmentID'], $assigned))
										{
											$checked = "checked";
										}
								?>
									
									<tr class="active">
										<td class="visible-lg"  style="padding:9px;"><input type="checkbox" name="equipment_id[]" <?php echo $checked; ?> class="cb_equipment"  value="<?php echo $equipment['equipmentID'] ?>" /></td>
										<td class="visible-lg"><?php echo $equipment['key_desc'] ?></td>
										<td class="visible-lg"><?php echo $equipment['type'] ?></td>
									</tr>

								<?php endforeach; ?> <!-- End sa foreach adtu sa taas :) -->
								<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->

							</tbody>
						</table>
						
					</div>
					<?php echo form_close() ?>	
					</div>
				</div>

				
			</div>
		</div>
	</div>
</section>
