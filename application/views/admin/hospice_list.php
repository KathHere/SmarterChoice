<section class="form-container">
	<div class="row">
		<div class="container">
			<div class="col-md-12">
				<div class="inner-form-container page-shadow OpenSans-Reg" style="margin-top: 140px;;min-height: 650px;padding: 28px;background-position: 37% 101.4%;">
					<div class="col-md-12">
						<table id="user_table" class="table table-hover">
							<thead>
								<tr>
									<!--<th class="visible-lg"></th>-->
									<th class="visible-lg">Hospice ID</th>
									<th class="visible-lg">Hospice Name</th>
									<th class="visible-lg">Actions</th>
									
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($hospices)) : ?>	
								<?php foreach ($hospices as $hospice) : ?>
									
									<tr class="active">
										<!--<td class="visible-lg"  style="padding:9px;"><input type="checkbox"></td>-->
										<td class="visible-lg"><?php echo $hospice->hospiceID ?></td>
										<td class="visible-lg"><?php echo $hospice->hospice_name ?></td>
										<td class="visible-lg">
											<button type="button" class="btn btn-warning btn-xs" data-toggle="modal" data-target="#edit_hospice<?php echo $hospice->hospiceID ?>"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
											<a href="<?php echo base_url('admin/group_hospice/delete_hospice/'.get_code($hospice->hospiceID)) ?>">
												<button type="button" class="btn btn-danger btn-xs">
													<i class="glyphicon glyphicon-trash"></i> Delete
												</button>
											</a>
											<a href="<?php echo base_url('admin/equipment/list_equipments/'.get_code($hospice->hospiceID)) ?>">
												<button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-file"></i> Assign Equipments</button>
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
</section>


<?php if(!empty($hospices)) : ?>	
	<?php foreach ($hospices as $hospice) : ?>

<div class="modal fade edit_hospice" id="edit_hospice<?php echo $hospice->hospiceID ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:90px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Edit Hospice Form</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
				<div class="container">
						<div style="margin-left: 5%;">
							<h2 class="OpenSans-Lig" >Edit Hospice Name</h2>
					<!-- 	<h5 class="OpenSans-Reg">Proin eget tortor risus. Curabitur non nulla sit amet nisl tempus convallis</h5> -->
						</div>
					<div class="col-md-5" style="padding: 25px;margin-left: 3%;">

						<div class="form-container" style="padding: 15px;border-radius:4px;">
							<form action="<?php echo base_url('admin/group_hospice/update_hospice/'.get_code($hospice->hospiceID)) ;?>" method="POST" id="">
							   
							  <div class="form-group">
							    <label for="exampleInputEmail1">Hospice Name</label>
							    <input type="text" name="hospice_name" class="form-control" id="" placeholder="" value="<?php echo $hospice->hospice_name ?>">
							  </div>
							 <hr />
						</div>
				    </div>
				</div>
        	</div>
       			</div>
       	</div>
      </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary btn-order" >Save Changes</button>
		  </div>
		</form> 
    </div>
	
  </div>
</div>


<?php endforeach; ?> <!-- End sa foreach adtu sa taas :) -->
<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->