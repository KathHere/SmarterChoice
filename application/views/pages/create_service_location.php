<div class="bg-light lter b-b wrapper-md">
<h1 class="m-n font-thin h3">Create New Service Location</h1>
</div>

<div class="col-sm-12">	
  <div class="panel panel-default" style="margin-top:10px">
    <div class="panel-heading font-bold">Account Registration</div>
    	<div class="panel-body">
    		<?php if(!empty($success) && $success == 'true') :?>
				<div class="alert alert-success alert-dismissable fade-alert" style="padding:25px !important;width:99% !important">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <strong>Success!</strong> Service Location added!
				</div>

			<?php elseif(!empty($failed) && $failed == 'true'):?>
				<div class="alert alert-danger alert-dismissable fade-alert" style="padding:25px !important;width:99% !important">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <strong>Failed!</strong> There's a problem processing your request.
				</div>
			<?php else : ?>
			<?php endif ;?>


			<!-- <div class="form-group">
				<div class="col-sm-12" style="margin-left:30px; margin-top:10px; margin-bottom:-10px;">
					<label style="margin-left:-13px">Account Type <span class="text-danger-dker">*</span></label>
				</div>
                <div class="col-sm-12" style="margin-bottom:20px;">
                    <div class="col-sm-2" style="margin-top:5px">
	                    <div class="radio">
	                        <label class="i-checks">
	                            <input type="radio" name="choose_account_type" id="choose_hospice_account" class="choose_account_type" checked="checked" value="0"><i></i>Hospice
	                        </label>
	                    </div>
	                </div>
	                <div class="col-sm-2" style="margin-top:5px;">
	                    <div class="radio">
	                        <label class="i-checks">
	                            <input type="radio" name="choose_account_type" id="choose_company_account" class="choose_account_type" value="1"><i></i>Company
	                        </label>
	                    </div>
	                </div>
                </div>
            </div> -->

            <?php 
            /******************************************
          		Start For Hospice
            ******************************************/
            ?>
			<div class="col-md-12" id="add_hospice_div" style="margin-bottom:25px;">

				<div class="col-md-6">
					<form action="<?php echo base_url('service_location/create') ;?>" method="POST" id="hospice_create_form">
						<input type="hidden" name="choose_account_type_value" value="0">
						<input type="hidden" name="account_location" value="<?php echo $this->session->userdata('user_location'); ?>">
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Date of New Service Location</label>
					    	<input style="margin-left:0px;" type="text" class="form-control datepicker" name="service_location_date" value="<?php echo date("Y-m-d") ?>" />
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Service Location Name</label>
					    	<input type="text" name="service_location_name" class="form-control" id="" placeholder="">
					    </div>
					    <div class="form-group" style="margin-top:35px; margin-bottom: 39px">
					    	<!-- <label for="exampleInputEmail1">Service Location Address</label> -->
					    	<!-- <textarea class="form-control" name="hospice_name" placeholder="" style="height:35px"></textarea> -->
			                <?php
			                $count_hospice_address = count($var_hospice_address);
			                $patient_address_looped = '';
			                foreach ($var_hospice_address as $loop_address_here) {
			                    if ($loop_address_here == 'LAS') {
			                        break;
			                    } else {
			                        $patient_address_looped = $patient_address_looped.' '.$loop_address_here;
			                    }
			                }
			                ?>
			                <label for="exampleInputEmail1">Service Location Address<span class="text-danger-dker">*</span></label>
			                <?php
			                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
			                      ?>
			                    <input type="text" class="form-control" id="p_add" placeholder="Enter Address" name="p_address" style="margin-bottom:20px;" tabindex="19" value="<?php echo $patient_address_looped; ?>" readonly>
			                <?php
			                  } else {
			                      ?>
			                    <input type="text" class="form-control" id="p_add" placeholder="Enter Address" name="p_address" style="margin-bottom:20px;" tabindex="19">
			                <?php
			                  }
			                ?>

					    </div>
			            <div class="form-group pull-in clearfix" style="margin-bottom: 45px">
			                <div class="col-sm-6">
			                  <?php
			                    if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
			                        ?>
			                      <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required" id="p_city" placeholder="City" value="Las Vegas" name="service_city" tabindex="22" readonly>
			                  <?php
			                    } else {
			                        ?>
			                      <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required" id="p_city" placeholder="City" name="service_city" tabindex="22">
			                  <?php
			                    }
			                  ?>

			                </div>
			                <div class="col-sm-6">
			                  <?php
			                    if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
			                        ?>
			                      <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" id="p_state" value="NV" name="patient_state" tabindex="23" readonly>
			                  <?php
			                    } else {
			                        ?>
			                      <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" id="p_state" placeholder="State / Province" name="service_state" tabindex="23">
			                  <?php
			                    }
			                  ?>
			                </div>
			            </div>
			            <div class="form-group">
			                <?php
			                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
			                      ?>
			                    <input type="number" class="form-control grey_inner_shadow" id="p_postal" placeholder="Postal Code" name="service_postalcode" value="<?php echo $var_hospice_address[$count_hospice_address - 1]; ?>" tabindex="24" readonly>
			                    <!--for number only add this= onkeypress="return isNumberKey(event)" -->
			                <?php
			                  } else {
			                      ?>
			                    <input type="number" class="form-control grey_inner_shadow" id="p_postal" placeholder="Postal Code" name="service_postalcode" tabindex="24">
			                <?php
			                  }
			                ?>
			            </div>
					    
					   <hr style="width:530px" />
				</div>

				<div class="col-md-6">
						<div class="form-group">
					    	<label for="exampleInputEmail1">Service Location Contact Person</label>
					    	<input type="text" name="service_location_contact_person" class="form-control" id="" placeholder="">
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Service Location ID No.</label>
					    	<input type="text" name="service_location_id" class="form-control " id="" placeholder="">
					    </div>
					    <div class="form-group" style="margin-top:35px">
					    	<label for="exampleInputEmail1">Service Location Phone No.</label>
					    	<input type="text" name="service_location_phone_no" class="form-control hosp_contact_num" id="" placeholder="">
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Service Location Fax Number</label>
					    	<input type="text" name="service_location_fax_no" class="form-control " id="" placeholder="" style="">
					    </div>
					    <div class="form-group" style="margin-top:20px">
					    	<label for="exampleInputEmail1">Contact Person Title</label>
					    	<input type="text" name="service_location_contact_person_title" class="form-control " id="" placeholder="" style="text-transform:none !important">
					    </div>
					    <hr />
					   <button type="submit" class="btn btn-success btn-block" style="height: 38px;">Register</button>
					</form> 
				</div>
			</div>
			<?php 
              /******************************************
          		End For Hospice
              ******************************************/


              /******************************************
         		Start For Company
              ******************************************/
            ?>
            <div class="col-md-12" id="add_company_div" style="display:none;margin-bottom:25px; ">

				<div class="col-md-6">
					<form action="<?php echo base_url('hospice/create-hospice') ;?>" method="POST" id="company_create_form">
						<input type="hidden" name="choose_account_type_value" value="1">
						<input type="hidden" name="account_location" value="<?php echo $this->session->userdata('user_location'); ?>">
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Company Account Number</label>
					    	<input type="text" class="form-control company_account_num" name="company_account_num" readonly value="" />
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Company Sign On Date</label>
					    	<input type="text" name="company_date_of_service" class="form-control datepicker" id="" placeholder="" style="margin-left:0px">
					    </div>
					    <div class="form-group" style="margin-top:35px">
					    	<label for="exampleInputEmail1">Company Name</label>
					    	<textarea class="form-control" name="company_name" placeholder="Enter Company Name" style="height:35px"></textarea>
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Company Phone Number</label>
					    	<input type="text" name="company_phone_number" class="form-control company_contact_num" id="" placeholder="">
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Company Contact Person</label>
					    	<input type="text" name="company_contact_person" class="form-control " id="" placeholder="">
					    </div>
				        <div class="form-group" >
					    	<label for="exampleInputEmail1">Company Website</label>
					    	<input type="text" name="company_website" class="form-control " id="" placeholder="" style="text-transform:none !important">
					    </div>
					   <hr style="width:530px" />
				</div>

				<div class="col-md-6">
					    <div class="form-group hidden-xs hidden-sm" style="visibility:hidden">
					    	<label for="exampleInputEmail1">Divider</label>
					    	<textarea class="form-control" name="" placeholder=""></textarea>
					    </div>
					    <div class="form-group hidden-xs hidden-sm" style="visibility:hidden">
					    	<label for="exampleInputEmail1">Divider</label>
					    	<textarea class="form-control" name="" placeholder=""></textarea>
					    </div>
					    <div class="form-group" style="margin-top:-20px">
					    	<label for="exampleInputEmail1">Company Address</label>
					    	<input type="text" name="company_address" class="form-control " id="" placeholder="">
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Company Fax Number</label>
					    	<input type="text" name="company_fax_number" class="form-control company_contact_num" id="" placeholder="">
					    </div>
					    <div class="form-group" >
					    	<label for="exampleInputEmail1">Title</label>
					    	<input type="text" name="company_title" class="form-control " id="" placeholder="" style="">
					    </div>
					    <div class="form-group" >
					    	<label for="exampleInputEmail1">Company Email</label>
					    	<input type="email" name="company_email" class="form-control " id="" placeholder="" style="text-transform:none !important">
					    </div>
					    <hr />
					   <button type="submit" class="btn btn-success btn-block" style="height: 38px;">Register</button>
					</form> 
				</div>

			</div>
            <?php 
              /******************************************
                End For Company
              ******************************************/
            ?>
        </div>
    </div>
</div>