<style type="text/css">
	table.dataTable tbody td{
		padding: 12px 14px;
		font-size: 13px;
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
							<h2 class="OpenSans-Reg" style="margin-top: -26px;text-align:center;margin-bottom: 40px;">Patient Order Summary</h2>
						<table id="order_table" class="table table-hover">
							<thead>
								<tr>
									<!--<th class="visible-lg"></th>-->
									 <th class="visible-lg"><h5>Date Ordered</h5></th>
									<th class="visible-lg"><h5>Work Order No.</h5></th>
									<th class="visible-lg"><h5>Patient Last Name</h5></th>
									<th class="visible-lg"><h5>Patient First Name</h5></th>
									
									<th class="visible-lg"><h5>Medical Record Number</h5></th>
									<th class="visible-lg"><h5>Activity Type</h5></th>
									
									<?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
										<th class="visible-lg"><h5>Organization Name</h5></th>
									<?php endif ;?>
									<!--<th class="visible-lg"><h5>Added By</h5></th>-->
									
									<th class="visible-lg"><h5>Status</h5></th>
									<th class="visible-lg"></th>
									<th class="visible-lg"><h5>Actions</h5></th>
								 
								</tr>
							</thead> 
							<tbody>
								<?php if(!empty($orders)) : ?>	
								<?php foreach ($orders as $order) :?> 
									
									<tr class="active">
										<!--<td class="visible-lg"  style="padding:9px;"><input type="checkbox"></td>-->
											<td class="visible-lg"><?php echo $order['date_ordered'] ?></td->
										<td class="visible-lg">WO#<?php echo $order['uniqueID'] ?></td>
										<td class="visible-lg"><?php echo $order['p_lname'] ?></td>
										<td class="visible-lg"><?php echo $order['p_fname'] ?></td>
										
										<td class="visible-lg" style="word-wrap:break-word"><?php echo $order['medical_record_id'] ?></td>
										<td class="visible-lg"><?php echo $order['activity_name'] ?></td>
										
										<?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
											<td class="visible-lg"><?php echo $order['hospice_name'] ?></td>
										<?php endif ;?>
										
										<!--<td class="visible-lg"><?php echo $order['creator'] ?></td>--> 
										
									
										
									
										<td class="visible-lg"><?php echo $order['order_status'] ?></td>
										<td class="visible-lg">
											<a href="<?php echo base_url('client_order/order_summary/'.$order['uniqueID']) ?>"><button class="btn btn-primary btn-sm" style="margin-top: 0px;">Details</button></a>
										</td>
										<td class="visible-lg" style="text-align:center;">
												<!--<a href=""><button class="btn btn-primary btn-sm" style="margin-bottom: 10px;"><i class="glyphicon glyphicon-pencil"></i></button></a>-->
											<a href="javascript:void(0)" class="delete-orders" data-id="<?php echo $order['uniqueID'] ?>"><button class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> Delete</button></a>
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