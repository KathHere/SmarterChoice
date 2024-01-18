<section class="form-container">
	<div class="row">
		<div class="container">
			<div class="col-md-10 col-md-offset-1">

				<div class="inner-form-container" style="margin-top:110px;">
					
						<div class="row">
						
							<?php if(!empty($success) && $success == 'true') :?>
								<div class="alert alert-success alert-dismissable fade-alert" style="margin-left:15px;">
								  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								  <strong>Successful!</strong> User <?php echo $lastname?>, <?php echo $firstname?> successfully added!
								</div>

							<?php elseif(!empty($failed) && $failed == 'true'):?>
								<div class="alert alert-danger alert-dismissable fade-alert" style="margin-left:15px;">
								  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
								  <strong>Failed!</strong> Email already used by another account.
								</div>
							<?php else : ?>
							<?php endif ;?>
						
						
							<div class="container">
							<div style="margin-left: 8.4%;">
										<h1 class="OpenSans-Lig" st>Add Client Form</h1>
								<!-- 	<h5 class="OpenSans-Reg">Proin eget tortor risus. Curabitur non nulla sit amet nisl tempus convallis</h5> -->
									</div>
								<div class="col-md-8" style="margin-left: 7%;margin-top: 0px;padding:20px;">
									<form role="form" method="POST" action="<?php echo base_url('user/user_add') ;?>" id="register_form">
										 <div class="form-container" style="background-color: #f2f2f2;padding: 15px;border-radius: 4px;">
										 	
										 	 <div class="col-md-12" style="margin-top:10px;">
										 	 	<div class="col-md-6">
											  	<div class="form-group">
												    <label for="exampleInputEmail1">Email address</label>
												    <input type="email" name="email" class="form-control" id="email_add" placeholder="Email Address">
												  </div>
												 
												  <div class="form-group">
												    <label for="exampleInputPassword1">First Name</label>
												    <input type="text" name="firstname" class="form-control" id="" placeholder="Firstname">
												  </div>
												  <!--<div class="form-group">
												    <label for="exampleInputPassword1">Middle Name</label>
												    <input type="text" name="middlename" class="form-control" id="" placeholder="Middlename">
												  </div>-->
												  <div class="form-group">
												    <label for="exampleInputPassword1">Last Name</label>
												    <input type="text" name="lastname" class="form-control" id="" placeholder="Lastname">
												  </div>
												  <div class="form-group">
												    <label for="exampleInputPassword1">Address</label>
												    <input type="text" name="address" class="form-control" id="" placeholder="Address">
												</div>
												<div class="form-group">
												    <label for="exampleInputPassword1">ZIP Code</label>
												    <input type="text" name="zip" class="form-control" id="" placeholder="Zip Code">
												  </div>
												 <div class="form-group">
												    <label for="exampleInputPassword1">Country</label>
												    <input type="text" name="country" class="form-control" id="" placeholder="Country">
												  </div>
												
										  </div>


										  <div class="col-md-6">
										  	
										  <div class="form-group">
										    <label for="exampleInputPassword1">Phone Number</label>
										    <input type="text" name="phone" class="form-control" id="person_num" placeholder="Phone Number">
										  </div>
										  <div class="form-group">
										    <label for="exampleInputPassword1">Mobile Number</label>
										    <input type="text" name="mobile" class="form-control" id="" placeholder="Mobile Number">
										  </div>
										  <input type="hidden" name="balance" value="0.00" />
										 <!--  <div class="form-group">
										    <label for="exampleInputPassword1">Account Balance</label>
										    <input type="text" name="balance" class="form-control" id="" placeholder="Balance" readonly value="0.00">
										  </div> -->

										  <div class="form-group">
										    <label for="">Status</label>
										    <select class="form-control" name="status">										    
										    	<option value="">- Please choose -</option>
										    	<option value="active">Active</option>
										    	<option value="inactive">Inactive</option>
										    	<option value="closed">Closed</option>
										    </select>
										  </div>

										  <div class="form-group">
										    <label for="exampleInputEmail1">Username</label>
										    <input type="text" name="username" class="form-control" id="username_field" placeholder="Username">
										  </div>
										   <div class="form-group">
										    <label for="exampleInputPassword1">Password</label>
										    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
										  </div>
										  
										  <div class="form-group">
										  	<label for="exampleInputPassword1">Account Type</label>
										    <select class="form-control" id="account_type_dropdown" placeholder="" name="account_type">
										    	<option value="">- Please choose -</option>
										    	<?php if($this->session->userdata('account_type') == 'dme_admin') :?>
											    	<option value="dme_admin">DME Amin</option>
											    	<option value="dme_user">DME User</option>
											    	<option value="hospice_admin">Hospice Admin</option>
										   		 <?php endif ;?>
										    	<option value="hospice_user">Hospice User</option>
										    </select>
										  </div>
										  
										  
										    <div class="form-group" id="group_div" style="display:none">
										  	<label for="exampleInputPassword1">Choose Group</label>

									  		<?php if($this->session->userdata('account_type') == 'hospice_admin') :?>
									  			<input type="text" name="group_name" class="form-control" id="" placeholder="" value="<?php echo $this->session->userdata('group_name') ?>" readonly>
									  			<input type="hidden" id="" name="group_id" value="<?php echo $this->session->userdata('group_id') ?>" />
										    <?php endif ;?>


										  	<?php if($this->session->userdata('account_type') == 'dme_admin') :?>
										    <select class="form-control" id="groupname_select" placeholder="" name="group_id">
										    	<option value=""> - Please Choose -</option>
													<?php foreach($hospices as $hospice) :?>
															<?php
																echo  "<option value='".$hospice->hospiceID."'>".$hospice->hospice_name."</option>";
															;?>
													<?php endforeach ;?>
													<input type="hidden" id="hdnGroup_name" name="group_name" value="" />
										    </select>
										    <?php endif ;?>


										    </div>
										  
 
										 
										  </div>
										 	 </div>

										  <div class="container">
										  	<div class="col-md-5">
										  	 <button type="submit" class="btn btn-success" style="height: 43px;width: 200px;margin-bottom:20px;">Register</button>
										  	</div>
										  </div>

										 </div>
										<!--   <input type="hidden" name="date_created" value="" />

										  <div id="user_spec_data">

										  </div> -->
										 
										
										 
									</form>
								</div>
							</div>
						</div>

				</div>

				<div class="page-shadow" style="width: 101.5%;">
					
				</div>

			</div>
		</div>
	</div>
</section>
