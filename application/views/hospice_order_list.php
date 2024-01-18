<section class="form-container">
	<div class="row">
		<div class="container">
			<div class="col-md-12">
				<div class="inner-form-container" style="margin-top: 140px;">
					<div class="col-md-12">
						<table id="user_table" class="table table-hover">
							<thead>
								<tr>
									<th class="visible-lg"></th>
									<th class="visible-lg">Patient Firstname</th>
									<th class="visible-lg">Patient Lastname</th>
									<th class="visible-lg">Medical Record Number</th>
									<th class="visible-lg">Activity Type</th>
									<th class="visible-lg">Organization Name</th>
									<th class="visible-lg">Added By</th>
									<th class="visible-lg">Date Added</th>
									<th class="visible-lg"></th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($informations)) : ?>	
								<?php foreach ($informations as $information) : ?>
									
									<tr class="active">
										<td class="visible-lg"  style="padding:9px;"><input type="checkbox"></td>
										<td class="visible-lg"><?php echo $information['p_fname'] ?></td>
										<td class="visible-lg"><?php echo $information['p_lname'] ?></td>
										<td class="visible-lg"><?php echo $information['medical_record_id'] ?></td>
										<td class="visible-lg"><?php echo $information['activity_name'] ?></td>
										<td class="visible-lg"><?php echo $information['hospice_name'] ?></td>
										<td class="visible-lg"><?php echo $information['creator'] ?></td>
										<td class="visible-lg"><?php echo $information['date_ordered'] ?></td>
										<td class="visible-lg">
											<a href="<?php echo base_url('client_order/order_summary_hospice/'.$information['uniqueID']) ?>"><button class="btn btn-primary">View All Details</button></a>
										</td>
									</tr>

								<?php endforeach; ?> <!-- End sa foreach adtu sa taas :) -->
								<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->
								
							</tbody>
						</table>
					</div>
									
				</div>

				<div class="page-shadow">
					
				</div>
			</div>
		</div>
	</div>
</section>