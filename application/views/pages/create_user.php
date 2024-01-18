<div class="bg-light lter b-b wrapper-md">
	<h1 class="m-n font-thin h3">User Form</h1>
</div>

<div class="col-sm-12">
  	<div class="panel panel-default" style="margin-top:10px">
    	<div class="panel-heading font-bold">Create User Form
    	</div>
    	<div class="panel-body">
    	<?php /*
    		<?php if(!empty($success) && $success == 'true') :?>
				<div class="alert alert-success alert-dismissable fade-alert" style="padding:0px !important;width:99% !important">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <strong>Successful!</strong> User <?php echo $lastname?>, <?php echo $firstname?> successfully added!
				</div>

			<?php elseif(!empty($failed) && $failed == 'true'):?>
				<div class="alert alert-danger alert-dismissable fade-alert" style="padding:0px !important;width:99% !important">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <strong>Failed!</strong> Email already used by another account.
				</div>
			<?php else : ?>
			<?php endif ;?>
		*/?>
			<div style="margin-left: 8.4%;">
			</div>
			<?php echo form_open("",array("id"=>"register_form")) ;?>

				<input type="hidden" name="user_location_logged_in" value="<?php echo $this->session->userdata('user_location'); ?>">
		 	 	<div class="col-md-12" style="margin-top:10px;margin-bottom:25px;">
			 	 	<div class="col-md-6">
				      	<div class="form-group">
						    <label for="exampleInputPassword1">First Name</label>
						    <input type="text" name="firstname" class="form-control" id="" placeholder="First Name" tabindex="1" style="text-transform:none">
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputPassword1">Last Name</label>
						    <input type="text" name="lastname" class="form-control" id="" placeholder="Last Name" tabindex="3" style="text-transform:none">
					  	</div>
					  	<?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
						  	<div class="form-group">
							  	<label for="exampleInputPassword1">Assign Location</label>
							  	<div class="">
							      <div class="">

							            <!-- <label class="col-sm-6 hidden-xs control-label mt10 text-right">Hospice:</label> -->
							            <!-- <label class="col-sm-2 visible-xs-block control-label mt10 text-right"><i class="fa fa-filter"></i></label> -->
							            <div class="hidden-xs">
							            	<!-- assign_service_location -->
							                <select name="user_location" class="form-control m-b select2-ready" id="assign_location_create_user">
							                	<option value="">- Please choose -</option>
							                 <?php
							                 	$service_locations = get_service_location();
							                 	foreach($service_locations as $value){
							                 ?>
							                 <option value="<?php echo $value['location_id']; ?>">
							                 	<?php echo $value['location_name']; ?>, <?php echo $value['service_location_id']?>
							                 </option>
							                 <?php
							             		}
							                 ?>
							                </select>
							            </div>

							      </div>
							    </div>
							</div>
						<?php endif; ?>
					    <div class="form-group">
						  	<label for="exampleInputPassword1">User Type</label>
						    <select class="form-control select2-ready" id="account_type_dropdown" placeholder="" name="account_type" tabindex="5" data-isSelected="0">
						    	<option value="">- Please choose -</option>
						    	<?php
						    	if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') {
						    	?>
						    		<optgroup label="DME User">

							    		<?php
							    		if($this->session->userdata('account_type') == 'dme_admin') {
							    		?>

								    		<option value="dme_admin">Super Admin</option>
								    		<option value="dme_user">Admin</option>
							    		<?php
							    		}
							    		?>
							    		<option value="biller">Biller</option>
							    		<option value="customer_service">Customer Service</option>
							    		<option value="rt">RT</option>
							    		<option value="distribution_supervisor">Distribution Supervisor</option>
							    		<option value="dispatch">Dispatch</option>
										<option value="sales_rep">Sales Rep</option>
							    	</optgroup>
						    	<?php
						    	}
					    		if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'hospice_admin' || $this->session->userdata('account_type') == 'company_admin') {
						    	?>
							    	<optgroup label="Account User">
							    		<option value="hospice_admin">Admin</option>
							    		<option value="hospice_user">Staff</option>
							    	</optgroup>
						    	<?php
						    	}
						    	?>
						    </select>
					  	</div>

					  	<div class="form-group" id="group_div" style="display:none">
					  		<label for="exampleInputPassword1">Choose Group</label>
					  		<?php if($this->session->userdata('account_type') == 'hospice_admin') :?>
					  			<input type="text" name="group_name" class="form-control" id="" placeholder="" value="<?php echo $this->session->userdata('group_name') ?>" readonly tabindex="7" style="text-transform:none">
					  			<input type="hidden" id="" name="group_id" value="<?php echo $this->session->userdata('group_id') ?>" />
						    <?php endif ;?>

						  	<?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>

						  	<div class="form-control grey_inner_shadow" id="groupname_select_div" style="text-align: center;">Please Select Assign Location First!</div>
						    <select class="form-control select2-ready" id="groupname_select" placeholder="" name="group_id" tabindex="7" style="display:none; width:100%;">

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
					    <div class="form-group" id="group_div_companies" style="display:none">
	          				<label for="exampleInputPassword1">Choose Group</label>

			              	<?php if($this->session->userdata('account_type') == 'company_admin') :?>
			                	<input type="text" name="group_name" class="form-control" id="" placeholder="" value="<?php echo $this->session->userdata('group_name') ?>" readonly tabindex="7" style="text-transform:none">
			                	<input type="hidden" id="" name="group_id" value="<?php echo $this->session->userdata('group_id') ?>" />
			              	<?php endif ;?>

	          				<?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
				              	<select class="form-control select2-ready" id="groupname_select_company" placeholder="" name="group_id_company" tabindex="7">
				                	<option value=""> - Please Choose -</option>
					                <?php foreach($companies as $company) :?>
					                    <?php
					                      echo  "<option value='".$company->hospiceID."'>".$company->hospice_name."</option>";
					                    ;?>
					                <?php endforeach ;?>
				                	<input type="hidden" id="hdnGroup_name_company" name="group_name_company" value="" />
				              	</select>
			                <?php endif ;?>
	          			</div>
	          			<hr />
				    </div> <!-- .col-md-6 -->

				    <div class="col-md-6">
					  	<input type="hidden" name="balance" value="0.00" />
					  	<div class="form-group">
					    	<label for="exampleInputEmail1">Username</label>
					    	<textarea name="username" class="form-control" id="username_field" value="" placeholder="Username" autocomplete="off" tabindex="2" style="text-transform:none;height:35px"></textarea>
					    	<!-- <input type="text" name="username" class="form-control" id="username_field" value="" placeholder="Username" autocomplete="off" tabindex="2" style="text-transform:none"> -->
					  	</div>
					   	<div class="form-group">
					   	 	<label for="exampleInputPassword1">Password</label>
					    	<input type="password" name="password" class="form-control" id="password" placeholder="Password" tabindex="4" autocomplete="off" style="text-transform:none">
					  	</div>
					  	<div class="form-group">
					    	<label for="exampleInputEmail1">Email address</label>
					    	<input type="email" name="email" class="form-control" id="email_add" placeholder="Email Address" tabindex="6" style="text-transform:none">
					  	</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Phone Number</label>
							<input type="text" name="phone" class="form-control person_num" id="person_num" placeholder="Phone Number" tabindex="8" style="text-transform:none">
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Mobile Number</label>
							<input type="text" name="mobile" class="form-control person_num" id="" placeholder="Mobile Number" tabindex="9" style="text-transform:none">
						</div>
						<hr />
						<button type="button" class="btn btn-success register_user_btn" style="height: 38px;width:100%;">Register</button>
					</div>
			 	</div>

			<?php echo form_close() ;?>
    	</div> <!-- .panel-body-->
    </div> <!-- .panel panel-default -->
</div> <!-- .col-sm-12 -->


