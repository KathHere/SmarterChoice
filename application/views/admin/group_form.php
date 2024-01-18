<section class="form-container">

	<div class="row">
		<div class="container">
			<div class="col-md-6 col-md-offset-3">
				<div class="inner-form-container" style="margin-top:125px;">
						<div class="row">
						
						
							<?php if(!empty($success) && $success == 'true') :?>
								<div class="alert alert-success alert-dismissable fade-alert" style="margin-left:15px;">
								  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								 <strong>Successful!</strong>. Hospice Successfully Created.
								</div>

							<?php elseif(!empty($failed) && $failed == 'true'):?>
								<div class="alert alert-danger alert-dismissable fade-alert" style="margin-left:15px;">
								  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								  <strong>Failed!</strong>. Failed to create. Hospice Name already exists.
								</div>
							<?php else : ?>
							<?php endif ;?>
							
						
						
							<div class="container">
									<div style="margin-left: 5%;">
										<h2 class="OpenSans-Lig" >Create Hospice</h2>
								<!-- 	<h5 class="OpenSans-Reg">Proin eget tortor risus. Curabitur non nulla sit amet nisl tempus convallis</h5> -->
									</div>
								<div class="col-md-5" style="padding: 25px;margin-left: 3%;">

									<div class="form-container" style="padding: 15px;border-radius:4px;">
										<form action="<?php echo base_url('admin/group_hospice/create') ;?>" method="POST" id="hospice_create_form">
										   
										  <div class="form-group">
										    <label for="exampleInputEmail1">Hospice Name</label>
										    <input type="text" name="hospice_name" class="form-control" id="" placeholder="Enter Hospice Name">
										  </div>
										 <hr />
										  <button type="submit" class="btn btn-success btn-block" style="height: 43px;">Submit</button>
  
									</form> 
									</div>
							    </div>
							</div>
						</div>
				</div>

				<div class="page-shadow" style="width: 102.8%;">
					
				</div>
			</div>
		</div>
	</div>
</section>