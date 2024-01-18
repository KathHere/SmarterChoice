<style type="text/css">
	table.dataTable tbody td{
		padding: 12px 14px;
		font-size: 13px;
	}
	.glyphicon{
		top: -2px;

	}
	.btn-sm{
		padding: 9px 9px;
	}
	
</style>



<section class="form-container">
	<div class="row">
		<div class="container">
			<div class="col-md-12">
				<div class="inner-form-container page-shadow OpenSans-Reg" style="margin-top: 140px;;min-height: 650px;height:auto;padding: 28px;background-position: 37% 101.4%;">
					<div class="row">
						<div class="">
							<div class="col-md-12" style="padding:25px;">
							<h2 class="OpenSans-Reg" style="margin-top: -26px;text-align:center;margin-bottom: 40px;">Deleted Orders</h2>
						<table id="user_table" class="table table-hover">
							<thead>
								<tr>
									<!--<th class="visible-lg"></th>-->
									<th class="visible-lg"><h5>Medical Record Number</h5></th>
									<th class="visible-lg"><h5>Patient Last Name</h5></th>
									<th class="visible-lg"><h5>Patient First Name</h5></th>
									
									<th class="visible-lg"><h5>Date Deleted</h5></th>
									<th class="visible-lg"><h5>Actions</h5></th>
								
								</tr>
							</thead>
							<tbody>

								<?php if(!empty($trashes)) : ?>	
									<?php foreach ($trashes as $trash) : ?>

													<tr class="active">
														<!--<td class="visible-lg"  style="padding:9px;"><input type="checkbox"></td>-->
														<td class="visible-lg"><?php echo $trash['medical_record_id'] ?></td>
														<td class="visible-lg"><?php echo $trash['p_lname'] ?></td>
														<td class="visible-lg"><?php echo $trash['p_fname'] ?></td>
														
														<td class="visible-lg"><?php echo $trash['date_deleted'] ?></td>
														
														<td class="visible-lg" style="text-align:center;">
																<!--<a href=""><button class="btn btn-primary btn-sm" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-pencil"></i></button></a>-->
																<a href="javascript:void(0)" class="retrieve-orders" data-id="<?php echo $trash['trash_id'] ?>" style="text-decoration:none">
																	<button class="btn btn-primary btn-sm OpenSans-Reg">
																		<i class="glyphicon glyphicon-floppy-open OpenSans-Reg" style=""> Restore</i>
																	</button>
																</a>

																<a href="javascript:void(0)" class="delete-trash" data-id="<?php echo $trash['trash_id'] ?>">
																	<button class="btn btn-danger btn-sm OpenSans-Reg">
																		<i class="glyphicon glyphicon-trash OpenSans-Reg" style=""> Delete</i>
																	</button>
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