<section class="form-container">
	<div class="row">
		<div class="container">
			<div class="col-md-12">
				<div class="inner-form-container page-shadow" style="margin-top: 140px;min-height: 250px;padding: 28px;background-position: 37% 106.4%;">
					<div class="row">
						<div class="">
							<div class="col-md-12" style="padding:25px;">
						<table id="user_table" class="table table-hover OpenSans-Reg" style="margin-top:50px;">
							<thead>
								<tr>
									<th class="visible-lg">Firstname</th>
									<th class="visible-lg">Middle Name</th>
									<th class="visible-lg">Lastname</th>
									<th class="visible-lg">Email</th>
									<th class="visible-lg">Address</th>
									<th class="visible-lg">Status</th>
									<th class="visible-lg">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($users)) : ?>	
								<?php foreach ($users as $user) : ?>
									 
								<tr class="active">
									<td class="visible-lg"><?php echo $user['firstname'] ?></td>
									<td class="visible-lg"><?php echo $user['middlename'] ?></td>
									<td class="visible-lg"><?php echo $user['lastname'] ?></td>
									<td class="visible-lg"><?php echo $user['email'] ?></td>
									<td class="visible-lg"><?php echo $user['address'] ?></td>
									<td class="visible-lg"><?php echo $user['status'] ?></td>
									<td class="visible-lg" style="text-align:center;">
											<button type="button" class="btn btn-primary btn-xs"  data-toggle="modal" data-target="#edit_hosp_admin_users<?php echo $user['userID'] ?>"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
											<!-- <a href="javascript:void(0)" class="delete-hosp_admin_users" data-id="<?php echo $user['userID'] ?>"><button type="button" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</button></a> -->
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

<?php if(!empty($users)) : ?>	
	<?php foreach ($users as $user) : ?>

<div class="modal fade" id="edit_hosp_admin_users<?php echo $user['userID'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:90px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Edit User Form</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
					<form action="<?php echo base_url('user/update_user/'.get_code($user['userID'])) ?>" method="POST">  
        				<div class="col-md-6">
							  	<div class="form-group">
								    <label for="exampleInputEmail1">Email address</label>
								    <input type="email" name="email" class="form-control" id="email_add" placeholder="Email Address" value="<?php echo $user['email'] ?>">
								  </div>
								 
								  <div class="form-group">
								    <label for="exampleInputPassword1">Firstname</label>
								    <input type="text" name="firstname" class="form-control" id="" placeholder="Firstname" value="<?php echo $user['firstname'] ?>">
								  </div>
								  <div class="form-group">
								    <label for="exampleInputPassword1">Middlename</label>
								    <input type="text" name="middlename" class="form-control" id="" placeholder="Middlename" value="<?php echo $user['middlename'] ?>">
								  </div>
								  <div class="form-group">
								    <label for="exampleInputPassword1">Lastname</label>
								    <input type="text" name="lastname" class="form-control" id="" placeholder="Lastname" value="<?php echo $user['lastname'] ?>">
								  </div>
								  <div class="form-group">
								    <label for="exampleInputPassword1">Address</label>
								    <input type="text" name="address" class="form-control" id="" placeholder="Address" value="<?php echo $user['address'] ?>">
								</div>
								
								
						  </div>


						  <div class="col-md-6">
						  	
						  <div class="form-group">
						    <label for="exampleInputPassword1">Phone Number</label>
						    <input type="text" name="phone" class="form-control" id="person_num" placeholder="Phone Number" value="<?php echo $user['phone_num'] ?>">
						  </div>
						  <div class="form-group">
						    <label for="exampleInputPassword1">Mobile Number</label>
						    <input type="text" name="mobile" class="form-control" id="" placeholder="Mobile Number" value="<?php echo $user['mobile_num'] ?>">
						  </div>
						  <input type="hidden" name="balance" value="0.00" />
						 <!--  <div class="form-group">
						    <label for="exampleInputPassword1">Account Balance</label>
						    <input type="text" name="balance" class="form-control" id="" placeholder="Balance" readonly value="0.00">
						  </div> -->

						  <div class="form-group">
						    <label for="">Status</label>
						    <input type="text" name="status" class="form-control" id="username" placeholder="Auto Generated Username" value="<?php echo $user['status'] ?>" readonly>
						  </div>

						  <div class="form-group">
						    <label for="exampleInputEmail1">Username</label>
						    <input type="text" name="username" class="form-control" id="username" placeholder="Auto Generated Username" value="<?php echo $user['username'] ?>" readonly>
						  </div>
						   <div class="form-group">
						    <label for="exampleInputPassword1">Password</label>
						    <input type="password" name="password" class="form-control edit_password" id="" placeholder="Password" value="" style="border:1px solid red" required>
						  </div>
						  
						  <div class="form-group">
						  	<label for="exampleInputPassword1">Account Type</label>
						     <input type="text" name="account_type" class="form-control" id="username" placeholder="" value="<?php echo $user['account_type'] ?>" readonly>
						  </div>
						  
						    <div class="form-group" id="group_div" style="display:block">
						  	<label for="exampleInputPassword1">Choose Group</label>
							
							<?php if($user->account_type == 'hospice_admin' || $user->account_type == 'hospice_user') :?>
							<select class="form-control edit_hospicename" placeholder="" name="group_id">
						    	    <option value="<?php echo $user->group_id ?>"><?php echo $user->group_name ?></option>
									<?php foreach($hospices as $hospice) :?>
											<?php
												echo  "<option value='".$hospice->hospiceID."'>".$hospice->hospice_name."</option>";
											;?>
									<?php endforeach ;?>
									<input type="hidden" name="group_name" class="form-control edit_hospice_name" id="" placeholder="This is for hospice users only" value=""> 
									<!--<input type="hidden" id="hdnGroup_name" name="group_name" />-->
						    </select>
							<?php else :?>
									<input type="text" name="group_name" class="form-control" id="" placeholder="This is for hospice users only" value="<?php echo $user['group_name'] ?>" readonly> 
								
							<?php endif ;?>
							
							
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
								
