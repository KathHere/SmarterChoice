<style type="text/css">
	.billing_email_cc {
		width: 10%;
		text-align: center;
		padding: 6px;
		border: 1px solid rgb(207, 218, 221);
		border-radius: 0px 2px 2px 0px;
		height: 35px;
		border-left: 0px;
		cursor: pointer;
	}

	.bootstrap-tagsinput input {
		background: none;
		border: none;
	}
</style>
<div class="bg-light lter b-b wrapper-md">
<h1 class="m-n font-thin h3">Register Account</h1>
</div>

<div class="col-sm-12">	
  <div class="panel panel-default" style="margin-top:10px">
    <div class="panel-heading font-bold">Account Registration</div>
    	<div class="panel-body">
    		<?php if (!empty($success) && $success == 'true') :?>
				<div class="alert alert-success alert-dismissable fade-alert" style="padding:25px !important;width:99% !important">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <strong>Success!</strong> Account added!
				</div>

			<?php elseif (!empty($failed) && $failed == 'true'):?>
				<div class="alert alert-danger alert-dismissable fade-alert" style="padding:25px !important;width:99% !important">
				  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				  <!-- <strong>Failed!</strong> There's a problem processing your request. -->
				  <strong>Failed!</strong> </br>
				  <?php echo $message; ?>
				</div>
			<?php else : ?>
			<?php endif; ?>

			<div class="form-group">
				<div class="col-sm-12" style="margin-left:30px; margin-top:10px; margin-bottom:-10px;">
					<label style="margin-left:-13px">Account Type <span class="text-danger-dker">*</span></label>
				</div>
                <div class="col-sm-12" style="margin-bottom:20px;">
	                
                    <div class="col-sm-2" style="margin-top:5px">
	                    <div class="radio">
	                        <label class="i-checks">
	                            <input type="radio" name="all_radio" id="choose_hospice_account" class="choose_account_type track_census_btn"  value="0"><i></i>Track Customer Days
	                        </label>
	                    </div>
	                </div>
	                <!-- <div class="col-sm-2" style="margin-top:5px;">
	                    <div class="radio">
	                        <label class="i-checks">
	                            <input type="radio" name="choose_account_type" id="choose_company_account" class="choose_account_type" value="1"><i></i>Commercial
	                        </label>
	                    </div>
	                </div>
	                <div class="col-sm-2" style="margin-top:5px;">
	                    <div class="radio">
	                        <label class="i-checks">
	                            <input type="radio" name="choose_account_type" id="choose_company_account" class="choose_account_type" value="1"><i></i>Track Customer Days
	                        </label>
	                    </div>
	                </div> -->
                </div>
            </div>

            <?php
            /******************************************
          		Start For Hospice
            ******************************************/
            ?>
			<div class="col-md-12" id="add_hospice_div" style="margin-bottom:25px;">

				<div class="col-md-6">
					<form action="<?php echo base_url('hospice/create-hospice'); ?>" method="POST" id="hospice_create_form">
						<input type="hidden" name="is_track_census" id="choose_track_census" class="hidden_track_census" value="1" />
						<input type="hidden" name="choose_account_type_value" value="0">
						<!-- <input type="hidden" name="account_location" value="<?php echo $this->session->userdata('user_location'); ?>"> -->
						<div class="form-group" >
					    	<label for="exampleInputEmail1">Payment Terms <span class="text-danger-dker">*</span></label>
					    	<div class="col-sm-12" style="margin-bottom:20px;">
			                    <div class="col-sm-4" style="margin-top:5px">
				                    <div class="radio">
				                        <label class="i-checks">
				                            <input type="radio" name="choose_payment_terms" id="" class="choose_account_type" value="0"><i></i>Net 30 Days
				                        </label>
				                    </div>
				                </div>
				                <div class="col-sm-4" style="margin-top:5px;">
				                    <div class="radio">
				                        <label class="i-checks">
				                            <input type="radio" name="choose_payment_terms" id="" class="choose_account_type" value="1"><i></i>Net 60 Days
				                        </label>
				                    </div>
				                </div>
				                <div class="col-sm-4" style="margin-top:5px;">
				                    <div class="radio">
				                        <label class="i-checks">
				                            <input type="radio" name="choose_payment_terms" id="" class="choose_account_type" value="2"><i></i>Net 90 Days
				                        </label>
				                    </div>
				                </div>
			                </div>
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Account Number <span class="text-danger-dker">*</span></label>
					    	<input type="text" class="form-control hospice_account_num" name="hospice_account_num" readonly value="" />
					    </div>
					    <!-- <div class="form-group" style="">
					    	<label for="exampleInputEmail1">Account Phone Number</label>
					    	<input type="text" name="hospice_phone_number" class="form-control hosp_contact_num" id="" placeholder="">
					    </div> -->
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Sign On Date <span class="text-danger-dker">*</span></label>
					    	<input type="text" name="date_of_service" class="form-control datepicker" id="" placeholder="" style="margin-left:0px">
					    </div>
					    <div class="form-group" style="">
					    	<label for="exampleInputEmail1">Name <span class="text-danger-dker">*</span></label>
					    	<textarea class="form-control" name="hospice_name" placeholder="Enter Account Name" style="height:35px"></textarea>
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Phone Number <span class="text-danger-dker">*</span></label>
					    	<input type="text" name="hospice_phone_number" class="form-control hosp_contact_num" id="" placeholder="">
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Billing Address <span class="text-danger-dker">*</span></label>
					    	<!-- <input type="text" name="hospice_billing_address" class="form-control " id="" placeholder=""> -->
							<input type="hidden" name="hospice_billing_address" class="form-control " id="" placeholder="" value=" ">
					    	<input type="text" class="form-control" id="b_add" placeholder="Enter Address" name="b_address" style="margin-bottom:20px;" tabindex="19">
					    	<input type="text" class="form-control" id="b_placenum" placeholder="Apartment No., Room No. , Unit No." name="b_placenum" style="margin-bottom:20px;" tabindex="21">

					    	<div class="row" style="margin-bottom:20px;">
					    		<div class="col-md-6">
					    			<input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required" id="p_city" placeholder="City" name="b_city" tabindex="22">
					    			
					    		</div>
					    		<div class="col-md-6">
		                    		<input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" id="p_state" placeholder="State / Province" name="b_state" tabindex="23">
					    			
					    		</div>
					    	</div>
		                    <input type="number" class="form-control grey_inner_shadow" id="p_postal" onkeypress="return isNumberKey(event)" placeholder="Postal Code" name="b_postalcode" tabindex="24">
					    </div>
					    <div class="form-group billing_email_wrapper" style="">
					    	<label for="exampleInputEmail1">Billing Email <span class="text-danger-dker">*</span></label>
					    	<div class="row billing_email_container" style="margin-left: 0px; margin-right: 0px; ">
					    		<div class="col-md-11 billing_email_input" style="width:90%; padding: 0px">
					    			<input type="text" name="billing_email" class="col-md-11" id="" placeholder="" style="width: 90%" data-role="tagsinput">
					    		</div>
					    		
					    		<div class="col-md-1 billing_email_cc " style="">CC</div>
					    	</div>
					    	

					    </div>
					    <div class="form-group billing_email_cc_input" style="visibility:hidden">
					    	<label for="exampleInputEmail1">Billing Email CC</label>
					    	<div class="col-md-11 billing_email_input" style="width:90%; padding: 0px; margin-bottom: 20px;">
					    		<input type="text" name="billing_email_cc" class="form-control " id="" placeholder="" style="width: 90%" data-role="tagsinput">
					    	</div>
					    </div>
					    
					    <!-- <div class="form-group">
					    	<label for="exampleInputEmail1">Hospice Contact Person</label>
					    	<input type="text" name="hospice_contact_person" class="form-control " id="" placeholder="">
					    </div>
				        <div class="form-group" >
					    	<label for="exampleInputEmail1">Hospice Website</label>
					    	<input type="text" name="hospice_website" class="form-control " id="" placeholder="" style="text-transform:none !important">
					    </div> -->
					   <!-- <hr style="width:560px" /> -->
				</div>

				<div class="col-md-6">
					    <!-- <div class="form-group" style="margin-top: -18.4px; margin-bottom:35.2px">
					    	<label for="exampleInputEmail1">Account Phone Number</label>
					    	<input type="text" name="hospice_phone_number" class="form-control hosp_contact_num" id="" placeholder="">
					    </div> -->
					    <div class="form-group" >
					    	<label for="exampleInputEmail1">Send Invoice to <span class="text-danger-dker">*</span></label>
					    	<div class="col-sm-12" style="margin-bottom:20px;">
			                    <div class="col-sm-4" style="margin-top:5px">
				                    <div class="radio">
				                        <label class="i-checks">
				                            <input type="radio" name="choose_invoice_to" id="" class="choose_account_type"  value="0"><i></i>Shipping Address
				                        </label>
				                    </div>
				                </div>
				                <div class="col-sm-4" style="margin-top:5px;">
				                    <div class="radio">
				                        <label class="i-checks">
				                            <input type="radio" name="choose_invoice_to" id="" class="choose_account_type" value="1"><i></i>Billing Address
				                        </label>
				                    </div>
				                </div>
				                <div class="col-sm-4" style="margin-top:5px;">
				                    
				                </div>
			                </div>
					    </div>
					    
					    <div class="form-group" style="">
					    	<label for="exampleInputEmail1">Associated Service Location <span class="text-danger-dker">*</span></label>
					    	<select name="associated_account_location" class="form-control m-b select2-ready" id="">
			                	<option value="">- Please choose -</option>
			                 <?php
                                 $service_locations = get_service_location();
                                 foreach ($service_locations as $value) {
                                     ?>	
			                 <option value="<?php echo $value['location_id']; ?>">
			                 	<?php echo $value['location_name']; ?>, <?php echo $value['service_location_id']; ?>
			                 </option>
			                 <?php
                                 }
                             ?>
			                </select>
					    </div>
					    <div class="form-group" style="">
					    	<label for="exampleInputEmail1">Shipping Address <span class="text-danger-dker">*</span></label>
					    	<!-- <input type="text" name="hospice_shipping_address" class="form-control " id="" placeholder=""> -->
							<input type="hidden" name="hospice_shipping_address" class="form-control " id="" placeholder="" value=" ">
					    	<input type="text" class="form-control" id="s_add" placeholder="Enter Address" name="s_address" style="margin-bottom:20px;" tabindex="19">
					    	<input type="text" class="form-control" id="s_placenum" placeholder="Apartment No., Room No. , Unit No." name="s_placenum" style="margin-bottom:20px;" tabindex="21">

					    	<div class="row" style="margin-bottom:20px;">
					    		<div class="col-md-6">
					    			<input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required" id="city_ptmove" placeholder="City" name="s_city" tabindex="22">
					    			
					    		</div>
					    		<div class="col-md-6">
		                    		<input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" id="state_ptmove" placeholder="State / Province" name="s_state" tabindex="23">
					    			
					    		</div>
					    	</div>
		                    <input type="number" class="form-control grey_inner_shadow" id="postalcode_ptmove" onkeypress="return isNumberKey(event)" placeholder="Postal Code" name="s_postalcode" tabindex="24">
					    </div>
					    <div class="form-group" style="">
					    	<label for="exampleInputEmail1">Fax Number <span class="text-danger-dker">*</span></label>
					    	<input type="text" name="hospice_fax_number" class="form-control hosp_contact_num" id="" placeholder="">
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Contact Person <span class="text-danger-dker">*</span></label>
					    	<input type="text" name="hospice_contact_person" class="form-control " id="" placeholder="">
					    </div>
					    <div class="form-group" >
					    	<label for="exampleInputEmail1">Email <span class="text-danger-dker">*</span></label>
					    	<input type="text" name="hospice_email" class="form-control " id="" placeholder="" style="text-transform:none !important">
					    </div>
					    <div class="form-group" >
					    	<label for="exampleInputEmail1">Title <span class="text-danger-dker">*</span></label>
					    	<input type="text" name="hospice_title" class="form-control " id="" placeholder="" style="">
					    </div>
						<div class="form-group" >
					    	<label for="exampleInputEmail1">Website</label>
					    	<input type="text" name="hospice_website" class="form-control " id="" placeholder="" style="">
					    </div>
					    <div class="form-group" style="margin-bottom: 35px;">
					    	<label for="exampleInputEmail1">Daily Rate <span class="text-danger-dker">*</span></label>
					    	<input type="text" onkeypress="return isNumberKey(event)" name="account_daily_rate" class="form-control grey_inner_shadow" id="" placeholder="" style="">
					    </div>
					    
					    <!-- <div class="form-group" style="">
					    	<label for="exampleInputEmail1">Hospice Address</label>
					    	<input type="text" name="hospice_address" class="form-control " id="" placeholder="">
					    </div>
					    <div class="form-group">
					    	<label for="exampleInputEmail1">Hospice Fax Number</label>
					    	<input type="text" name="hospice_fax_number" class="form-control hosp_contact_num" id="" placeholder="">
					    </div>
					    <div class="form-group" >
					    	<label for="exampleInputEmail1">Title</label>
					    	<input type="text" name="hospice_title" class="form-control " id="" placeholder="" style="">
					    </div>
					    <div class="form-group" >
					    	<label for="exampleInputEmail1">Hospice Email</label>
					    	<input type="email" name="hospice_email" class="form-control " id="" placeholder="" style="text-transform:none !important">
					    </div> -->
					    <!-- <hr style="width:530px" /> -->
					   <!-- <button type="submit" class="btn btn-success btn-block" style="height: 38px;">Register</button> -->
					   <button type="submit" class="btn btn-success btn-block" style="height: 38px;">Next</button>
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
					<form action="<?php echo base_url('hospice/create-hospice'); ?>" method="POST" id="company_create_form">
						<input type="hidden" name="choose_account_type_value" value="1">
						<!-- <input type="hidden" name="account_location" value="<?php echo $this->session->userdata('user_location'); ?>"> -->
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

<script type="text/javascript">
	$(document).ready(function(){
		//track_census_btn
		$('.track_census_btn').bind('click',function(){
			var _this = $(this);
			if($(".hidden_track_census").val() == 1)
			{
				$(".hidden_track_census").val(0);
			}
		});

		//billing_email_cc
		setTimeout(function() {
			$('.billing_email_wrapper').find('.bootstrap-tagsinput').addClass("form-control grey_inner_shadow");
			$('.billing_email_wrapper').find('.bootstrap-tagsinput').css("height", "100%");
			$('.billing_email_cc_input').find('.bootstrap-tagsinput').addClass("form-control grey_inner_shadow");
			$('.billing_email_cc_input').find('.bootstrap-tagsinput').css("height", "100%");
		}, 1);		
		$('.billing_email_cc').bind('click',function(){
			_this = $(this);
			_this.hide();
			$(".billing_email_input").css("width","100%");
			$(".billing_email_cc_input").css("visibility", "visible");

		});

		var allRadios = document.getElementsByName('all_radio');
		var booRadio;
		var x = 0;

		for(x = 0; x < allRadios.length; x++){
		    allRadios[x].onclick = function(){
		      if(booRadio == this){
		          this.checked = false; 
				$(".hidden_track_census").val(1);
		          booRadio = null;
		      }
		      else
		      {
		      booRadio = this;
		      }
		  };
		}


	});

</script>