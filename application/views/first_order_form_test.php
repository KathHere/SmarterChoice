<style type="text/css">
	input:focus{
		border:1px solid red !important;
	}
	select:focus{
		border:1px solid red !important;
	}
	textarea:focus{
		border:1px solid red !important;
	}
</style>


<form class="form-horizontal" role="form" action="<?php echo base_url('client_order/add_order') ;?>" method="post" id="order_form_validate">
<section class="form-container" style="width:100%; min-width:1260px;">
<div class="row">
<div class="container">
	<div class="col-md-12 col-xs-12">
		<div class="inner-form-container page-shadow col-md-12" style="margin-top: 100px;">
				<div class="" style="padding: 14px;margin-top: 15px;margin-bottom: 10px;background: rgba(237,237,237,1);background: -moz-linear-gradient(top, rgba(237,237,237,1) 0%, rgba(246,246,246,1) 54%, rgba(255,255,255,1) 100%);background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(237,237,237,1)), color-stop(54%, rgba(246,246,246,1)), color-stop(100%, rgba(255,255,255,1)));background: -webkit-linear-gradient(top, rgba(237,237,237,1) 0%, rgba(246,246,246,1) 54%, rgba(255,255,255,1) 100%);background: -o-linear-gradient(top, rgba(237,237,237,1) 0%, rgba(246,246,246,1) 54%, rgba(255,255,255,1) 100%);background: -ms-linear-gradient(top, rgba(237,237,237,1) 0%, rgba(246,246,246,1) 54%, rgba(255,255,255,1) 100%);background: linear-gradient(to bottom, rgba(237,237,237,1) 0%, rgba(246,246,246,1) 54%, rgba(255,255,255,1) 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ededed', endColorstr='#ffffff', GradientType=0 );">
					
					
					
				
			<div class="col-md-12 col-xs-12" style="margin-top:20px;padding-left:0px;">
			
				<img src="<?php echo base_url('assets/img/black-logo.png') ?>" class="img-responsive" style="margin-bottom:20px;float: left;margin-top: 36px;">
				
			    <div class="alert alert-info fade in" style="height:134px;width: 660px;margin-left: 380px;">
			     
			      <strong class="OpenSans-reg" style="font-size: 16px;">Having a hard time filling out the Patient Order Form ?</strong> 

			      <p class="OpenSans-reg" style="margin-top: 20px;">Please call all orders / pickups to : 
			     	 <strong>(702) 248 - 0056</strong>
			      </p>

			      <p class="OpenSans-reg">Please  fax all Patient Order Forms to : 
			      	<strong>(702) 889 - 0059</strong>
			      </p>

			    </div> 
				
				<h1 class="OpenSans-Reg" style="text-align: center;font-size: 32px;">Patient Order Form</h1>

			</div>	

			<div class="clearfix"></div>

			<div class="col-md-12 col-xs-12" style="margin-top:10px;">

				 

				  <div class="col-md-12">
				  	<div class="form-group col-md-6"  style="padding-left:0px;">

				  	<?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user'):?>
				  		<div class="form-group " >
					    <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Organization <span style="color:red;">*</span></label>
				
						<select type="select" class="form-control" name="organization_id">
							<?php if(!empty($results_all)) :?>
								<?php foreach($results_all as $result_all) :?>
									<option value="<?php echo $result_all->hospiceID ?>"><?php echo $result_all->hospice_name ?></option>
							  <!-- <option>Angel Eye Hospice</option> -->
							  <!-- <option>Compassion Care Hospice</option>
							  <option>Divine Hospice, LLC</option>
							  <option>Fountain of Life Hospice</option>
							  <option>Hospice Del Sol</option>
							  <option>Southern Nevada Home and Hospice</option>
							  <option>Jireh Health Care Services,</option>
							  <option>Nevada Community Hospice, LLC</option> 
							  <option>Nevada Hospice Care</option>
							  <option>Rhythm of Life Hospice</option>
							  <option>Celestia Hospice, Inc.</option>
							  <option>Carepro Hospice</option>
							  <option>Family Care Hospice</option>
							  <option>Hospice Services of</option> -->
								<?php endforeach ;?>
							<?php endif ;?>
					 	</select>

				    </div>

				    <?php else :?>
			  		
			  		<div class="form-group " >
					    <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Organization <span style="color:red;">*</span></label>
					    
					    <?php if(!empty($results)) :?>
							<?php foreach($results as $result) :?>
								<input type="text" class="form-control" readonly value="<?php echo $result->group_name ;?>" name="" />
								<input type="hidden" class="form-control"  value="<?php echo $result->group_id ;?>" name="organization_id" />
							  <!-- <option>Angel Eye Hospice</option> -->
							  <!-- <option>Compassion Care Hospice</option>
							  <option>Divine Hospice, LLC</option>
							  <option>Fountain of Life Hospice</option>
							  <option>Hospice Del Sol</option>
							  <option>Southern Nevada Home and Hospice</option>
							  <option>Jireh Health Care Services,</option>
							  <option>Nevada Community Hospice, LLC</option> 
							  <option>Nevada Hospice Care</option>
							  <option>Rhythm of Life Hospice</option>
							  <option>Celestia Hospice, Inc.</option>
							  <option>Carepro Hospice</option>
							  <option>Family Care Hospice</option>
							  <option>Hospice Services of</option> -->
							<?php endforeach ;?>
						<?php endif ;?>

				    </div>

				    <?php endif ;?>

				  <div class="clearfix"></div>
				   		<div class=" col-md-12 " style="padding-left:0px;margin-bottom:0px;">

				   			
				   			<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-left: -15px;font-weight:normal;margin-bottom:5px;">Person Placing Order <span style="color:red;">*</span></label>
                                                        <div class="clearfix"></div>
					    	<input type="hidden" name="person_placing_order_id" value="<?php echo $this->session->userdata('userID') ?>" />

					    	<div class=" form-group col-md-6" style="padding-left:0px;" >
					    			<?php $lname = $this->session->userdata('lastname') ;?>
					    		<input type="text" class="form-control  " id="" placeholder="Last Name" name="person_placing_order_lname" style="margin-bottom:20px;" value="<?php echo $lname ?>" readonly>
					    	</div>

					    	<div class="col-md-6" style="padding-right:0px;">
					    		<?php $fname = $this->session->userdata('firstname') ;?>
					    		<input type="text" class="form-control  " id="" placeholder="First Name" name="person_placing_order_fname" style="margin-bottom:20px;" value="<?php echo $fname ?>" readonly>
					    	</div>
				   		</div>

				    	<div class="clearfix"></div>

				    	<div class="form-group col-md-12" style="padding-left:0px;">

				    			<!-- <div class="error-container">
				    				<img src="<?php echo base_url('assets/img/error-icon.png') ?>" class="img-responsive" style="float:left">
				    				<p class="error-message" >This Field is Required</p>
				    			</div> -->

				    		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Office Number <span style="color:red;">*</span></label>
                                                 <div class="clearfix"></div>
				    	 <div class="col-md-8" style="padding-left:0px;">
				    	 	<?php $phone = $this->session->userdata('phone_num') ;?>
				    		<input type="text" class="form-control  " id="person_num" placeholder="Phone Number" name="phone_num" style="margin-bottom:20px;" value="<?php echo $phone ?>" readonly>
				    	</div>
				    	</div>

				    	<div class="clearfix"></div>
				    	
				    	
				    	
			 		</div>

				  

				 <div class="col-md-6" style="padding-left: 53px;">
				 	
				 	 <div class="form-group col-md-12" style="padding-left:0px;">
				  	 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Email Address<span style="color:red;">*</span></label>
				  	 <div class="clearfix"></div>
				    	 <div class="col-md-12" style="padding-left:0px;">
				    	 	<?php $email = $this->session->userdata('email') ;?>
				    		<input type="email" class="form-control  " id="" placeholder="Email Address" name="email" style="margin-bottom:20px;" value="<?php echo $email ?>" readonly>
				    	</div>
				  </div>
				  <div class="clearfix"></div>


				  <div class="form-group col-md-12" style="padding-left:0px;margin-top:-15px;">
		 			 <label for="exampleInputEmail1" class="control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Delivery Date <span style="color:red;">*</span></label>
		    		<input type="text" class="form-control datepicker" id="delivery_date" placeholder="Delivery Date" name="delivery_date" style="margin-bottom:20px;">

			 	  </div>

			 		<div class="clearfix"></div>

				   <div class="form-group col-md-12" style="padding-left:0px;margin-top:-15px;">

				    <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Patient Medical Record No. <span style="color:red;">*</span></label>
					
				    <input type="text" class="form-control " id="patient_mrn" placeholder="Patient Medical Record No." name="patient_mrn" disabled="disabled" autocomplete="off">
						<div id="suggestion_container" style="margin-bottom: 90px;z-index:999999;position:absolute;"></div>
				  </div>

				 </div>

				  </div>

				  <div class="col-md-12" style="padding-left:0px;">
				<div class="col-md-8" style="padding-left:0px;">

						<div class="col-md-4"  style="padding-left:0px;" id="activity_type">
							
							<label>Activity Type<span style="color:red;">*</span></label>

							<div class="radio OpenSans-Reg" style="font-weight:normal;">
							  <label>
							    <input type="radio" name="activity_type" id="radio_pickup" value="1" class="radio_act_type" disabled>
							     Delivery
							  </label>
							</div>

							<!-- <div class="radio OpenSans-Reg" style="font-weight:normal;">
							  <label>
							    <input type="radio" name="activity_type" id="radio_pickup" value="2" class="radio_act_type" disabled>
							     Pickup
							  </label>
							</div> -->

							<div class="radio OpenSans-Reg" style="font-weight:normal;">
							  <label>
							    <input type="radio" name="activity_type" id="radio_pickup" value="3" class="radio_act_type" disabled>
							     Exchange
							  </label>
							</div>

							<div class="radio OpenSans-Reg" style="font-weight:normal;">
							  <label>
							    <input type="radio" name="activity_type" id="radio_pickup" value="4" class="radio_act_type" disabled>
							    CUS Move
							  </label>
							</div>

							<div class="radio OpenSans-Reg" style="font-weight:normal;">
							  <label>
							    <input type="radio" name="activity_type" id="radio_pickup" value="5" class="radio_act_type" disabled> 
							     Respite
							  </label>
							</div>

						</div>


						<div class="col-md-6" style="padding-left:0px;">

								<div class="form-group col-md-12" style="display:none;" id="pickup_categories">
						             <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Pickup reason <span style="color:red;">*</span></label>
						            
						            <select class="form-control" name="pickup_sub" id="pickup_select" class="pickup_sub">
						            	 <option value="">- Please Choose -</option>
							             <option value="expired">Expired</option>
							             <option value="discharged">Discharged</option>
							             <option value="revoked">Revoked</option>
						            </select>
						            
						         </div>

								<div class="form-group col-md-12" style="display:none;" id="p_date_expired">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Date <span style="color:red;">*</span></label>
							   		
							    		<input type="text" class="form-control datepicker2 pickup_sub" id="" placeholder="Date" name="pickup_date">
							    	
						 		</div>

						 		<!-- <div class="form-group col-md-12" style="display:none;" id="p_date_discharge">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Date Discharged<span style="color:red;">*</span></label>
							   		
							    	<input type="text" class="form-control datepicker2" id="" placeholder="Date Discharged" name="pickup_date_discharged">
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="p_date_revoked">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Date Revoked <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control datepicker2" id="" placeholder="Date Revoked" name="pickup_date_revoked">
						 		</div> -->


						 		<div class="form-group col-md-12" style="display:none;" id="forptmove_categories">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">New Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control pickup_sub" id="" placeholder="Street Address" name="ptmove_address" style="margin-bottom:20px;">

							    	<input type="text" class="form-control pickup_sub" id="" placeholder="Apartment #, Room # , Unit #" name="ptmove_placenum" style="margin-bottom:20px;">

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control pickup_sub" id="" placeholder="City" name="ptmove_city" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control pickup_sub" id="" placeholder="State / Province" name="ptmove_state" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-5" style="padding-left:0px;">
							    		<input type="text" class="form-control pickup_sub" id="" placeholder="Postal Code" name="ptmove_postalcode" style="margin-bottom:20px;">
							    	</div>
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="forrespite_categories">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Pickup Date <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control datepicker pickup_sub" id="" placeholder="Date" name="respite_delivery_date" style="margin-bottom:20px;">
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="forrespite_categories2">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">New Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control pickup_sub " id="" placeholder="Street Address" name="respite_address" style="margin-bottom:20px;">

							    	<input type="text" class="form-control pickup_sub " id="" placeholder="Apartment #, Room # , Unit #" name="respite_placenum" style="margin-bottom:20px;">

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control pickup_sub " id="" placeholder="City" name="respite_city" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  pickup_sub" id="" placeholder="State / Province" name="respite_state" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-5" style="padding-left:0px;">
							    		<input type="text" class="form-control pickup_sub " id="" placeholder="Postal Code" name="respite_postalcode" style="margin-bottom:20px;">
							    	</div>
							    	
						 		</div>



						 		

						 		<div class="clearfix"></div>


						 		


							</div>

							<div class="clearfix"></div>
							
								
						 		<div class="clearfix"></div>
						 		
							</div>


				</div>


			

			
			</div>



			<div class="clearfix"></div>
			<div class="col-md-8" style="padding-left:0px; ">
				<h3 class="OpenSans-Reg">Patient Information</h3>
							

							<div class="col-md-6" style="padding-left:0px;">
								<div class="form-group col-md-12">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Patient Name <span style="color:red;">*</span></label>
							   		 <div class="clearfix"></div>
							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control patient_info " id="p_lname" placeholder="Last Name" name="patient_lname" disabled>
							    		
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control patient_info " id="p_fname" placeholder="First Name" name="patient_fname" disabled>
							    	</div>
						 		</div>

						 		<div class="form-group col-md-12">
							   		<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control patient_info " id="p_add" placeholder="Enter Address" name="p_address" style="margin-bottom:20px;" >

							    	<input type="text" class="form-control patient_info " id="p_placenum" placeholder="Apartment #, Room # , Unit #" name="patient_placenum" style="margin-bottom:20px;" disabled>

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control patient_info " id="p_city" placeholder="City" name="patient_city" style="margin-bottom:20px;width: 155px;" disabled>
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control patient_info " id="p_state" placeholder="State / Province" name="patient_state" style="margin-bottom:20px;" disabled>
							    	</div>

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control patient_info " id="p_postal" placeholder="Postal Code" name="patient_postalcode" style="margin-bottom:20px;" disabled>
							    	</div>

							    	<!-- <div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control patient_info " id="p_country" placeholder="Country" name="patient_postalcode" style="margin-bottom:20px;" disabled>
							    	</div> -->

							    	
						 		</div>

						 		<div class="clearfix"></div>


						 		


							</div>

						 	<div class="col-md-6">

						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Phone Number <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control patient_info " id="p_phone_number" placeholder="Phone Number" name="patient_phone_num" disabled>
						 		</div>

						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Alt. Phone Number </label>
							    	<input type="text" class="form-control patient_info " id="p_alt_phonenum" placeholder="Phone Number" name="patient_alt_phonenum" disabled>
						 		</div>

						 		<div class="clearfix"></div>

						 		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Emergency Contact </label>

						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Next of Kin <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control patient_info" style="" id="p_nextofkin" placeholder="Full Name" name="patient_nextofkin" disabled>
						 		</div>


						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Next of Kin Phone Number <span style="color:red;">*</span></label>
							    	<input  type="text" class="form-control patient_info"  id="p_nextofkinphone" placeholder="Phone Number" name="patient_nextofkinphonenum" disabled>
						 		</div>

						 	</div>

						 	<div class="clearfix"></div>
						 	<hr>

						 	<div class="col-md-12" >
						 			<?php if(!empty($equipments)) :?>
						    			<?php foreach($equipments as $equipment) :?>

						 		<div class="form-group col-md-12 wrapper-equipment" data-value="<?php echo $equipment['categoryID'] ?>" id="wrapper_equip_<?php echo $equipment['categoryID'] ?>">



						 			<label class="btn btn-default" style="margin-bottom:20px;margin-top:20px;" id="equip_<?php echo $equipment['categoryID'] ?>"><?php echo $equipment['type'] ?></label>

						 		<div class="equipment" style="display:none;">

						 			<div class="clearfix"></div>

							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;" id="capped_label"><?php echo $equipment['type'] ?><span style="color:red;">*</span></label>

							   		 <div class="clearfix"></div>

							    	<div class="col-md-6" style="padding-left:0px;" id="">
							    		<?php foreach($equipment['children'] as $key=>$child) :?>

								    		<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
									 		  <input type="checkbox" id="" value="<?php echo $child['equipmentID'] ?>" 
									 		  		 name="equipments[]" 
									 		  		 data-target="#<?php echo trim($child['key_name']) ?>_<?php echo $equipment['categoryID'];?>" 
									 		  		 data-name="<?php echo trim($child['key_name']);?>"
                                                     data-desc="<?php echo trim($child['key_desc']);?>"
                                                     data-value="<?php echo $child['key_desc'];?>"
									 		  		 data-category="<?php echo $equipment['type'];?>"
									 		  		 data-category-id="<?php echo $equipment['categoryID'];?>"
									 		  		 class="checkboxes c-<?php echo trim($child['key_name']);?>-<?php echo $equipment['categoryID'] ?>"><?php echo $child['key_desc'] ?>
											</label>
											<?php 
												if($key==$equipment['division']-1)
												{
													break;
												}
											?>
										<?php endforeach ;?>

							    	</div>
							    	<div class="col-md-6" style="padding-left:0px;" id="">
							    		<?php for($i=$equipment['division'];$i<=$equipment['last'];$i++) :?>
							    			<?php 
							    				$child = $equipment['children'][$i];
							    			?>
								    		<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
									 		  <input type="checkbox" id="" value="<?php echo $child['equipmentID'] ?>" 
									 		  		 name="equipments[]" 
									 		  		 data-target="#<?php echo trim($child['key_name']) ?>_<?php echo $equipment['categoryID'];?>" 
                                                     data-name="<?php echo trim($child['key_name']);?>"
                                                     data-desc="<?php echo trim($child['key_desc']);?>"
									 		  		 data-value="<?php echo $child['key_desc'];?>"
									 		  		 data-category="<?php echo $equipment['type'];?>"
									 		  		 data-category-id="<?php echo $equipment['categoryID'];?>"
									 		  		 class="checkboxes c-<?php echo trim($child['key_name']);?>-<?php echo $equipment['categoryID'] ?>"><?php echo $child['key_desc'] ?>
											</label>
										<?php endfor ;?>

							    	</div>

							    </div>

					 		</div>
						 				<?php endforeach ;?>
							    	<?php endif ;?>

						 	</div>


						 	<div class="col-md-12" >
						 		<div class="form-group col-md-12">

						 			<label class="btn btn-default" style="margin-bottom:20px;margin-top:20px;" id="disposable_items_btn">Disposable Supplies</label>
									<p class="OpenSans-Reg" style="font-size: 13px;"><i>(Please indicate quantity)</i></p>
						 		<div class="equipment" style="display:none;">

						 			<div class="clearfix"></div>


							   		 <div class="clearfix"></div>

							    	<div class="col-md-6" style="padding-left:0px;" id="">

							    	<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="disposable_label">CPAP/ BIPAP Supplies <span style="color:red;">*</span></label>


											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;"> <i style="font-size:11px;">(Mask, Headgear, Tubing and Chinstrap)</i></label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[139]' value='0' min="0" placeholder="QTY:" />
											</div>
											 <div class="clearfix"></div>

											 <!--<div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">CPAP <i style="font-size:11px;">(Mask, Headgear, Tubing and Chinstrap)</i></label>
												 <input type='number'  class="form-control "  id='numberinput' name='disposable[140]' value='0' min="0" />
											</div>-->
											 <div class="clearfix"></div>




											 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Enteral Supplies <span style="color:red;">*</span></label>


											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Feeding Bags</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[155]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											   <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Nebulizer Supplies <span style="color:red;">*</span></label>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Adult Aerosol Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[156]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Nebulizer Kits (Mouthpiece)</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[157]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>


											 <!--<div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Pediatric Aerosol Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[158]' value='0' min="0" />
											</div>-->
											 <div class="clearfix"></div>


											 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Suction Supplies <span style="color:red;">*</span></label>


											<div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Suction Canister</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[159]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Suction Tubing Long </label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[160]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Suction Tubing Short</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[161]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Yankuer Suction Tubing</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[162]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>




											 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Trach Supplies <span style="color:red;">*</span></label>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Corrugated Tubing (7ft)</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[163]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Jet Nebulizers</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[164]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>
											
											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Trach Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[165]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Pressure Line Adaptor</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[166]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>


												  <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Ventilator Supplies <span style="color:red;">*</span></label>


											  <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Ventilator Circuit <br><i style="font-size:11px;">(Add items will be added)</i></label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[169]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>
							    	</div>


							    	<div class="col-md-6" style="padding-left:0px;" id="disposable_items_list2">

							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Oxygen Supplies <span style="color:red;">*</span></label>
											
											<div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">"E" Cylinder Wrench</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[141]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">"Y" Connector</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[142]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Adult Nasal Cannula</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[143]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">High Flow Nasal Cannula (6L & Higher)</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[144]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">High Flow O2 Humidifier Bottle</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[145]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Non-Rebreather O2 Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[146]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Connector</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[147]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Humidifier Bottle</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[148]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[149]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>


											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Tubing 21FT</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[150]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>


											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Tubing 7FT</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[151]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <!--<div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Pediatric Nasal Cannula</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[152]' value='0' min="0" />
											</div>-->
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Venturi Mask (Vent)</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[153]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											  <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">X-Mas Tree Adaptor</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[154]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>


							    	<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">WheelChair Supplies <span style="color:red;">*</span></label>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Seat Belt</label>
												 <input type='text' class="form-control "  id='numberinput' name='disposable[167]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Anti Tippers</label>
												 <input type='text' class="form-control "  id='numberinput' name='disposable[168]' value='0' min="0" />
											</div>
											 <div class="clearfix"></div>


						    	</div>
							    	</div>
						 		</div>

						 	</div>

					
						 	<div class="col-md-12">
						 		<!-- <button  class="btn btn-default">Done Selecting Equipment</button> -->
								<div class="col-md-12" style="margin-top:20px;">
								<div class="form-group OpenSans-Reg">
									<label>Comment</label>
									<textarea class="form-control" rows="3" name="comment"></textarea>
								</div>
						 	</div>

							
			</div>

			</div>

			<div class="col-md-4 sidebar OpenSans-Reg"  style="background-color:#E6E6E6;margin-top: -180px;overflow-y: scroll;max-height: 1727px;">
					<h4 style="color:#85C879;text-shadow: 1px 0px #B3B3B3;"><strong>Order Summary</strong></h4>
					<!--<button type="button" class="btn btn-primary btn-xs" style="margin-top:10px;margin-bottom:20px;"> View Full Summary</button>-->
					<div class="order-cont"></div>
				</div>



			<div class="clearfix"></div>

			<hr>
			
			<div class="buttons " style="margin-bottom:20px;">
				<button class="btn btn-danger" style="visibility:hidden">Print Form</button>
				<button class="btn btn-warning pull-right " >Submit Form</button>
			</div>
			
			<?php $id = $this->session->userdata('userID') ;?>
			<input type="hidden" name="person_who_ordered" value="<?php echo $id ;?>" />


				</div>
		</div>

		<div class="">
			
		</div>
	</div>
</div>
</div>
</section>





<!-- Modal for Oxygen concentrator -->
<div class="modal fade modal_oxygen_concentrator_1" id="oxygen_concentrator_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		<div class="col-md-6">

        				<div class="form-group">
						    <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
						    <input type="text" data-desc="Liter Flow" name="subequipment[61][77]" class="form-control liter_flow_field" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
						  </div>

        				<label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
			        	<div class="checkbox">
						  <label>
						    <input type="checkbox"  class="5_ltr" data-desc="Oxygen Concentrator Type" data-value="5 LPM" name="subequipment[61][80]" id="optionsRadios1" value="5" >
						  5 LPM
						  </label>
						</div>

						<div class="checkbox">
						  <label>
						    <input type="checkbox" class="10_ltr" data-desc="Oxygen Concentrator Type" data-value="10 LPM"name="subequipment[61][81]" id="optionsRadios1" value="10" >
						   10 LPM
						  </label>
						</div>
        		</div>

        		<div class="col-md-6">
        				 

        				<label>Duration <span style="color:red;">*</span></label>
						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[61][radio][]" id="optionsRadios1" value="78" >
						   CONT
						  </label>
						</div>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[61][radio][]" id="optionsRadios1" value="79" >
						  PRN
						  </label>
						</div>
	<br /> <br/>
						  <label>Flow Type <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Flow Type" data-value="Nasal Canula" name="subequipment[61][radio][flt]" id="flowtype" value="82" >
						  Nasal Canula
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Flow Type"  data-value="Oxygen Mask" name="subequipment[61][radio][flt]" id="optionsRadios1" value="83" >
						   Oxygen Mask
						  </label>
						</div>
        		</div>
        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal hi-low -->
<div class="modal fade modal_hi-low_electric_hospital_bed_2" id="hi-low_electric_hospital_bed_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hi-Low Full Electric Hospital Bed</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
        				<label>Bed Type <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Full Rails" name="subequipment[55][radio][]" id="optionsRadios1" value="74" >
						 Full Rails
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Half Rails" name="subequipment[55][radio][]" id="optionsRadios1" value="75" >
						  Half Rails
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="No Rails"  name="subequipment[55][radio][]" id="optionsRadios1" value="76" >
						  No Rails
						  </label>
						</div>

        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal gastric-drainage -->
<div class="modal fade modal_gastric_drainage_aspirator_2" id="gastric_drainage_aspirator_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Gastric Drainage Aspirator</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
        				<label>Gastric Drainage Type <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Gastric Drainage Type" data-value="Cont." name="subequipment[16][radio][]" id="optionsRadios1" value="122">
						Cont.
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Gastric Drainage Type" data-value="Intermittant" name="subequipment[16][radio][]" id="optionsRadios1" value="123">
						 Intermittant
						  </label>
						</div>

        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>



<!-- Modal Small volume nebulizer -->
<div class="modal fade modal_small_volume_nebulizer_1" id="small_volume_nebulizer_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Small Volume Nebulizer</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
        				<label>Aerosol Mask <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Aerosol Mask"  data-value="Yes" name="subequipment[67][radio][]" id="optionsRadios1" value="90">
						 Yes
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Aerosol Mask"  data-value="No" name="subequipment[67][radio][]" id="optionsRadios1" value="91">
						  No
						  </label>
						</div>

        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Reclining wheelchair --> 
<div class="modal fade modal_reclining_wheelchair_2" id="reclining_wheelchair_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reclining wheelchair</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
        				<label>Type of Reclining Wheelchair<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Reclining Wheelchair" data-value='16"'  name="subequipment[64][radio][trw]" id="optionsRadios1" value="84">
						 16"
						  </label> 
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" 
                                                           data-desc="Type of Reclining Wheelchair" data-value='18"'
                                                           name="subequipment[64][radio][trw]" id="optionsRadios1" value="85">
						 18"
						  </label>
						</div>

						<label style="margin-top: 20px;">Type of Legrest (R) <span style="color:red;">*</span></label>
						<div class="radio">
						  <label>
						    <input type="radio"
									   data-desc="Type of Legrest (R)" data-value='Elevating Legrests'
									   name="subequipment[64][radio][tol]" id="optionsRadios1" value="86" >
						 Elevating Legrests
						  </label>
						</div>
			        	<div class="radio">
						  <label>
						    <input type="radio" 
									   data-desc="Type of Legrest (R)" data-value='Footrests'
									   name="subequipment[64][radio][tol]" id="optionsRadios1" value="87" >
						 Footrests
						  </label>
						</div>
        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Shower chair -->
<div class="modal fade modal_shower_chair_1" id="shower_chair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Shower Chair</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
        				<label>Type of Shower Chair<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Shower chair" data-value="With Back" name="subequipment[66][radio][]" id="optionsRadios1" value="88">
						 With Back
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Shower chair" data-value="Without Back" name="subequipment[66][radio][]" id="optionsRadios1" value="89">
						Without Back
						  </label>
						</div>

						
        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal wheelchair -->
<div class="modal fade modal_wheelchair_1" id="wheelchair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
        				<label>Type of Wheelchair <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Wheelchair" data-value='16"' name="subequipment[71][radio][]" id="optionsRadios1" value="92" >
						16"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Wheelchair" data-value='18"' name="subequipment[71][radio][]" id="optionsRadios1" value="93" >
						18"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Wheelchair" data-value='20"' name="subequipment[71][radio][]" id="optionsRadios1" value="94" >
						20"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Wheelchair" data-value='22"' name="subequipment[71][radio][]" id="optionsRadios1" value="95" >
						22"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Wheelchair" data-value='24"' name="subequipment[71][radio][]" id="optionsRadios1" value="96" >
						24"
						  </label>
						</div>

						<br>
						<label>Type of Legrest <span style="color:red;">*</span></label>
						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Legrest" data-value='Elevating Legrests' name="subequipment[71][radio][2]" id="optionsRadios1" value="97" >
						Elevating Legrests
						  </label>
						</div>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Legrest" data-value='Footrests' name="subequipment[71][radio][2]" id="optionsRadios1" value="98" checked>
						Footrests
						  </label>
						</div>
						
        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal hospital bed -->
<div class="modal fade modal_hospital_bed_1" id="hospital_bed_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital bed</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
        				<label>Type of Hospital Bed <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Hospital Bed" data-value="Full Electric" name="subequipment[55][radio][]" id="optionsRadios1" value="72" >
						Full Electric
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Hospital Bed" data-value="Semi Electric" name="subequipment[55][radio][]" id="optionsRadios1" value="73" >
						Semi Electric
						  </label>
						</div>
						
						<br><br>
						

						<label>Type of Rails<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Rails" data-value="Full Rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="74" >
						Full Rails
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Rails" data-value="Half Rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="75" >
						Half Rails
						  </label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Rails" data-value="No rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="76" >
								No rails
						  </label>
						</div>
						
        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal BIPAP setup -->
<div class="modal fade modal_bipap_2" id="bipap_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">BIPAP Settings</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
			        	<div class="form-group">
			        		<label>IPAP <span style="color:red;">*</span></label>
			        		<input type="text" data-desc="IPAP" class="form-control" placeholder="e.g 14" name="subequipment[4][109]">
			        	</div>

			        	<div class="form-group">
			        		<label>EPAP <span style="color:red;">*</span></label>
			        		<input type="text" data-desc="EPAP" class="form-control" placeholder="e.g5" name="subequipment[4][110]">
			        	</div>

			        	<div class="form-group">
			        		<label>Rate <i>(If applicable)</i> </label>
			        		<input type="text" data-desc="Rate" class="form-control" placeholder="" name="subequipment[4][111]">
			        	</div>

						
        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal CPAP setup -->
<div class="modal fade modal_cpap_2" id="cpap_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Settings</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
			        	<div class="form-group">
			        		<label>CMH20 <span style="color:red;">*</span></label>
			        		<input type="text" data-desc="IPAP" class="form-control" placeholder="e.g 14" name="subequipment[9][114]">
			        	</div>
			        	
						
        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal E-cylinder -->
<div class="modal fade modal_e-cylinder_2" id="e-cylinder_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">E-Cylinder</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
   			<div class="">
		        	<div class="col-md-12">
		        		
			        	<div class="form-group">
			        		<label>Quantity of E-Cylinder <span style="color:red;">*</span></label>
			        		<input type="text"  data-desc="Quantity of E-cylinder NC" class="form-control" placeholder="ex. 1" name="subequipment[11][121]">
			        	</div>
								
		        	</div>
   			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal pressure-mattress -->
<div class="modal fade modal_alternating_pressure_mattress_2" id="alternating_pressure_mattress_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Alternating Pressure Mattress</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
   			<div class="">
		        	<div class="col-md-12">
		        		
			        		<label>Extended? NC <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Extended? NC" data-value="Yes" name="subequipment[2][radio][]" id="optionsRadios1" value="107" >
						Yes
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Extended? NC" data-value="No" name="subequipment[2][radio][]" id="optionsRadios1" value="108" >
						No
						  </label>
						</div>
								
		        	</div>
   			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>







<!-- Modal m6-cylinder -->
<div class="modal fade modal_m6-cylinder_2" id="m6-cylinder_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">M6-Cylinder</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
   			<div class="">
		        	<div class="col-md-12">
		        		
			        	<div class="form-group">
			        		<label>Quantity of M6-Cylinder NC <span style="color:red;">*</span></label>
			        		<input type="text" data-desc="Quantity of M6-cylinder NC" class="form-control" placeholder="ex. 1" name="subequipment[27][99]">
			        	</div>
								
		        	</div>
   			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div> 

<!-- additional -->
<!-- Modal hospital bed -->
<div class="modal fade modal_hospital_bed_2" id="hospital_bed_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital bed</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
        				<label>Type of Hospital Bed <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Hospital Bed" data-value="Full Electric" name="subequipment[20][radio][]" id="optionsRadios1" value="134" >
						Full Electric
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Hospital Bed" data-value="Semi Electric" name="subequipment[20][radio][]" id="optionsRadios1" value="135" >
						Semi Electric
						  </label>
						</div>
						

						

						<label style="margin-top: 20px;">Type of Rails<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Rails" data-value="Full Rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="136" >
						Full Rails
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Rails" data-value="Half Rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="137" >
						Half Rails
						  </label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Rails" data-value="No rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="138" >
								No rails
						  </label>
						</div>
						
        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Oxygen concentrator -->
<div class="modal fade modal_oxygen_concentrator_2" id="oxygen_concentrator_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		<div class="col-md-6">

        				<div class="form-group">
						    <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
						    <input type="text" data-desc="Liter Flow" name="subequipment[29][100]" class="form-control liter_flow_field" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
						  </div>

        				<label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
			        	<div class="checkbox">
						  <label>
						    <input type="checkbox" class="5_ltr" data-desc="Oxygen Concentrator Type" data-value="5 LPM" name="subequipment[29][101]" id="optionsRadios1" value="5" >
						  5 LPM
						  </label>
						</div>

						<div class="checkbox">
						  <label>
						    <input type="checkbox" class="10_ltr" data-desc="Oxygen Concentrator Type" data-value="10 LPM"name="subequipment[29][102]" id="optionsRadios1" value="10" >
						   10 LPM
						  </label>
						</div>
        		</div>

        		<div class="col-md-6">
        				 

        				<label>Duration <span style="color:red;">*</span></label>
						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[29][radio][]" id="optionsRadios1" value="103" >
						   CONT
						  </label>
						</div>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[29][radio][]" id="optionsRadios1" value="104" >
						  PRN
						  </label>
						</div>
<br /> <br/>
						  <label>Flow Type <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Flow Type" data-value="Nasal Canula" name="subequipment[29][radio][flt]" id="flowtype" value="105" >
						  Nasal Canula
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Flow Type"  data-value="Oxygen Mask" name="subequipment[29][radio][flt]" id="optionsRadios1" value="106" >
						   Oxygen Mask
						  </label>
						</div>
        		</div>
        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Shower chair -->
<div class="modal fade modal_shower_chair_2" id="shower_chair_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Shower chair</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
        				<label>Type of Shower chair<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Shower chair" data-value="With Back" name="subequipment[39][radio][]" id="optionsRadios1" value="112">
						 With Back
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Shower chair" data-value="Without Back" name="subequipment[39][radio][]" id="optionsRadios1" value="113">
						Without Back
						  </label>
						</div>

						
        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Small volume nebulizer -->
<div class="modal fade modal_small_volume_nebulizer_2" id="small_volume_nebulizer_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Small Volume Nebulizer</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
        				<label>Aerosol Mask <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Aerosol Mask"  data-value="Yes" name="subequipment[40][radio][]" id="optionsRadios1" value="115">
						 Yes
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Aerosol Mask"  data-value="No" name="subequipment[40][radio][]" id="optionsRadios1" value="116">
						  No
						  </label>
						</div>

        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal wheelchair -->
<div class="modal fade modal_wheelchair_2" id="wheelchair_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
        		
        				<label>Type of Wheelchair<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Wheelchair" data-value='16"' name="subequipment[49][radio][]" id="optionsRadios1" value="124" >
						16"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Wheelchair" data-value='18"' name="subequipment[49][radio][]" id="optionsRadios1" value="125" >
						18"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Wheelchair" data-value='20"' name="subequipment[49][radio][]" id="optionsRadios1" value="126" >
						20"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Wheelchair" data-value='22"' name="subequipment[49][radio][]" id="optionsRadios1" value="127" >
						22"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Wheelchair" data-value='24"' name="subequipment[49][radio][]" id="optionsRadios1" value="128" >
						24"
						  </label>
						</div>


						<label>Type of Legrest<span style="color:red;">*</span></label>
						<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Legres" data-value='Elevating Legrests' name="subequipment[49][radio][2]" id="optionsRadios1" value="132" >
						Elevating Legrests
						  </label>
						</div>
			        	<div class="radio">
						  <label>
						    <input type="radio" data-desc="Type of Legres" data-value='Footrests' name="subequipment[49][radio][2]" id="optionsRadios1" value="133" checked>
						Footrests
						  </label>
						</div>
						
        	</div>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-order-close">Cancel</button>
        <button type="button" class="btn btn-primary btn-order">Save changes</button>
      </div>
    </div>
  </div>
</div>

</form>


<!-- Modal -->
<div class="modal fade" id="error-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:90px;">
     <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
         
      </div>
      <div class="modal-body OpenSans-Reg" style="padding-bottom: 0px;">


       <div class="alert  fade in" role="alert">
        <!-- <button type="button" class="close" data-dismiss="alert"><span class="sr-only">Close</span></button> -->
       	<h4><strong class="message-title"></strong></h4>
        <strong><p class="message-body"></p></strong> 
       
   	  </div>



    </div>
  </div>
</div>

