<section class="form-container">
	<div class="row">
		<div class="container">
			<div class="col-md-12">
				<div class="inner-form-container page-shadow OpenSans-Reg" style="margin-top: 140px;;min-height: 650px;height:auto;padding: 28px;background-position: 37% 101.4%;">
					<div class="row">
						<div class="">
							<div class="col-md-12" style="padding:25px;">
								<h2 class="OpenSans-Reg" style="margin-top: -26px;text-align:center;margin-bottom: 40px;">Equipments List</h2>
								<a href="javascript:void(0)" class="" data-id="" style="text-decoration:none">
									<button class="btn btn-warning btn-sm pull-right" data-toggle="modal" data-target="#add_equip" style="margin-bottom:20px">
										<i class="glyphicon glyphicon-plus" style=""></i> Add New Equipment
									</button>
								</a>
						<table id="user_table" class="table table-hover">
							<thead>
								<tr>
									<!--<th class="visible-lg"></th>-->
									<th class="visible-lg"><h5>Equipment Name</h5></th>
									<th class="visible-lg"><h5>Category</h5></th>
									<th class="visible-lg"><h5>Actions</h5></th>
								</tr>
							</thead>
							<tbody>

								<?php if(!empty($equipments)) : ?>	
									<?php foreach ($equipments as $equipment) : ?>

										<tr class="active">
											<!--<td class="visible-lg"  style="padding:9px;"><input type="checkbox"></td>-->
											<td class="visible-lg"><?php echo $equipment['key_desc'] ?></td>
											<td class="visible-lg"><?php echo $equipment['type'] ?></td>
											
											<td class="visible-lg" style="text-align:center;">
													<a href="javascript:void(0)" class=""  style="text-decoration:none">
														<a href="javascript:void(0)" class="edit-equip" data-id="<?php echo $equipment['equipmentID'] ?>" style="text-decoration:none">
															<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_equip<?php echo $equipment['equipmentID'] ?>">
																<i class="glyphicon glyphicon-pencil" style=""></i> Edit
															</button>
														</a>
													</a>

													<a href="javascript:void(0)" class=""  style="text-decoration:none">
														<a href="javascript:void(0)" class="delete-equip" data-id="<?php echo $equipment['equipmentID'] ?>">
															<button class="btn btn-danger btn-sm">
																<i class="glyphicon glyphicon-trash"></i> Delete
															</button>
														</a>
													</a>
													
											</td>
										</tr>

									<?php endforeach; ?> <!-- End sa foreach adtu sa taas :) -->
								<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->
								
							</tbody>
						</table>
					</div>
						
						
						</div>
					
					</div>
									
				</div>

				
			</div>
		</div>
	</div>
</section>



<?php if(!empty($equipments)) : ?>	
	<?php foreach ($equipments as $equipment) : ?>
<!-- Modal -->
<div class="modal fade" id="edit_equip<?php echo $equipment['equipmentID'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top:100px;overflow:hidden">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Equipments</h4>
      </div>
      <?php echo form_open("",array("id"=>"edit-equip-form".$equipment['equipmentID'])) ?>
     
	      <div class="modal-body">
	        	<div class="form-group">
			    <label for="exampleInputEmail1">Equipment Name</label>
			    <input type="text" name="description" class="form-control key_desc_id" id="" placeholder="" value="<?php echo $equipment['key_desc'] ?>">
			  </div>
			 
			  <div class="form-group">
			    <label for="exampleInputPassword1">Category</label>
			    <input type="text" name="category" class="form-control" id="" placeholder="" value="<?php echo $equipment['type'] ?>" readonly>
			  </div>
			  <input type="hidden" name="key_name" value="<?php echo $equipment['key_name'] ?>" class="" />
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary equip_update_btn" data-id="<?php echo $equipment['equipmentID'] ?>">Save changes</button>
	      </div>
	 <?php echo form_close() ?>
    </div>
  </div>
</div>
<?php endforeach; ?> <!-- End sa foreach adtu sa taas :) -->
<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->


<!--Add Equipment Modal -->
<div class="modal fade" id="add_equip" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top:100px;overflow:hidden">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Equipment</h4>
      </div>
     	<?php echo form_open("",array("id"=>"add-equip-form")) ?>
	      <div class="modal-body">
	        	<div class="form-group">
			    <label for="exampleInputEmail1">Equipment Name:</label>
			    <input type="text" name="key_desc" class="form-control" id="equip_name" placeholder="" value="">
			  </div>
			 
			  <div class="form-group">
			  
			    	<label for="exampleInputPassword1">Choose Category:</label>
				    <select class="form-control" name="cat_id" id="cat_select">	
				    	<option value="">- Please Choose -</option>
			    	<?php if(!empty($categories)) :?>
		  			<?php foreach($categories as $category) :?>	

				    	<option value="<?php echo $category['categoryID'] ?>"><?php echo $category['type'] ?></option>

				   	<?php endforeach ?>
					<?php endif ;?>
				    </select>
				
			  </div>
			  <input type="hidden" name="key_name" value="" class="hdn_key_name" />
			  <input type="hidden" name="cat_name" value="" class="hdn_cat_name" />
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary btn-add-equipment">Save changes</button>
	      </div>
	    <?php echo form_close() ?>
    </div>
  </div>
</div>