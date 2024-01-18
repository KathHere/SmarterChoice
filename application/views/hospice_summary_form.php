<form class="form-horizontal" role="form" action="<?php echo base_url('client_order/add_order') ;?>" method="post" id="order_form_validate">
<section class="form-container" style="width:100%; min-width:1260px;">
<div class="row">
<div class="container">
	<div class="col-md-12 col-xs-12">
		<div class="inner-form-container page-shadow col-md-12" style="margin-top: 100px;">
				<div class="" style="padding: 14px;">
					
				
			<div class="col-md-12 col-xs-12" style="margin-top:20px;padding-left:0px;">
				
			    <div class="alert alert-info fade in" style="height:134px">
			     
			      <strong class="OpenSans-reg" style="font-size: 16px;">Having a hard time in making orders and filling out the forms ?</strong> 

			      <p class="OpenSans-reg" style="margin-top: 20px;">Please call all Orders / Pickups to : 
			     	 <strong>(702) 248 - 0056</strong>
			      </p>

			      <p class="OpenSans-reg">Please  fax all Patient Order Forms to : 
			      	<strong>(702) 889 - 0059</strong>
			      </p>

			    </div> 

			</div>	


<?php if(!empty($informations)) :?>
	
	<?php $information = $informations[2]; ?>


			<div class="clearfix"></div>

			<div class="col-md-12 col-xs-12" style="margin-top:10px;">

				 

				  <div class="col-md-12">
				  	<div class="form-group col-md-6"  style="padding-left:0px;">
				  	
			  		
			  		<div class="form-group " >
					    <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Organization <span style="color:red;">*</span></label>
					    
					    
								<input type="text" class="form-control" readonly value="<?php echo $information['hospice_name'] ?>" name="organization_id" readonly/>
							
						

				    </div>

				  <div class="clearfix"></div>
				   		<div class=" col-md-12 " style="padding-left:0px;margin-bottom:0px;">

				   			
				   			<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Person Placing Order <span style="color:red;">*</span></label>
				    	
					    	<input type="hidden" name="person_placing_order_id" value="<?php echo $this->session->userdata('userID') ?>" />

					    	<div class=" form-group col-md-6" style="padding-left:0px;" >
					    		<input type="text" class="form-control  " id="" placeholder="Last Name" name="person_placing_order_lname" style="margin-bottom:20px;" readonly value="<?php echo $information['lastname'] ?>" readonly>
					    	</div>

					    	<div class="col-md-6" style="padding-right:0px;">
					    		
					    		<input type="text" class="form-control  " id="" placeholder="First Name" name="person_placing_order_fname" style="margin-bottom:20px;" readonly value="<?php echo $information['firstname'] ?>" readonly>
					    	</div>
				   		</div>

				    	<div class="clearfix"></div>

				    	<div class="form-group col-md-12" style="padding-left:0px;">

				    			<!-- <div class="error-container">
				    				<img src="<?php echo base_url('assets/img/error-icon.png') ?>" class="img-responsive" style="float:left">
				    				<p class="error-message" >This Field is Required</p>
				    			</div> -->

				    		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Phone Number <span style="color:red;">*</span></label>
				    	 <div class="col-md-8" style="padding-left:0px;">
				    	 	
				    		<input type="text" class="form-control  " id="person_num" placeholder="Phone Number" name="phone_num" style="margin-bottom:20px;" readonly value="<?php echo $information['phone_num'] ?>" readonly>
				    	</div>
				    	</div>

				    	<div class="clearfix"></div>
				    	
				    	
				    	
			 		</div>

				  

				 <div class="col-md-6" style="padding-left: 53px;">
				 	
				 	 <div class="form-group col-md-12" style="padding-left:0px;">
				  	 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Email Address<span style="color:red;">*</span></label>
				    	 <div class="col-md-8" style="padding-left:0px;">
				    	 	<?php $email = $this->session->userdata('email') ;?>
				    		<input type="email" class="form-control  " id="" placeholder="Email Address" name="email" style="margin-bottom:20px;" readonly value="<?php echo $information['email'] ?>">
				    	</div>
				  </div>
				  <div class="clearfix"></div>


				  <div class="form-group col-md-12" style="padding-left:0px;margin-top:-15px;">
		 			 <label for="exampleInputEmail1" class="control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Delivery Date <span style="color:red;">*</span></label>
		    		<input type="text" class="form-control " id="" placeholder="2/25/2013" name="delivery_date" style="margin-bottom:20px;" readonly value="<?php echo $information['pickup_date'] ?>">

			 	  </div>

			 		<div class="clearfix"></div>

				   <div class="form-group col-md-12" style="padding-left:0px;margin-top:-15px;">
				    <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Patient Medical Record No. <span style="color:red;">*</span></label>
				    <input type="text" class="form-control " id="" placeholder="Record No." name="patient_mrn" readonly value="<?php echo $information['medical_record_id'] ?>">
				  </div>

				 </div>

				  </div>

				  <div class="col-md-12" style="padding-left:0px;">
				<div class="col-md-8" style="padding-left:0px;">

						<div class="col-md-4"  style="padding-left:0px;">
							
							<label>Activity Type<span style="color:red;">*</span></label>

							<div class="radio OpenSans-Reg" style="font-weight:normal;">
							  <label>
							    <span type="text" name="activity_type" id="radio_pickup" value="" readonly class="radio_act_type"><?php echo $information['activity_name'] ?></span>
							     
							  </label>
							</div>

							
						</div>


						<div class="col-md-6" style="padding-left:0px;">

								<div class="form-group col-md-12" style="display:none;" id="pickup_categories">
						             <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Pickup reason <span style="color:red;">*</span></label>
						            
						            <select class="form-control" name="pickup_sub" id="pickup_select">
						            	 <option value="">- Please Choose -</option>
							             <option value="expired">Expired</option>
							             <option value="discharged">Discharged</option>
							             <option value="revoked">Revoked</option>
						            </select>
						            
						         </div>

								<div class="form-group col-md-12" style="display:none;" id="p_date_expired">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Date Expired <span style="color:red;">*</span></label>
							   		
							    		<input type="text" class="form-control datepicker2" id="" placeholder="Date Expired" name="pickup_date_expired" readonly>
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="p_date_discharge">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Date Discharged<span style="color:red;">*</span></label>
							   		
							    	<input type="text" class="form-control datepicker2" id="" placeholder="Date Discharged" name="pickup_date_discharged" readonly>
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="p_date_revoked">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Date Revoked <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control datepicker2" id="" placeholder="Date Revoked" name="pickup_date_revoked" readonly>
						 		</div>


						 		<div class="form-group col-md-12" style="display:none;" id="forptmove_categories">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">New Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control" id="" placeholder="Street Address" name="ptmove_address" style="margin-bottom:20px;">

							    	<input type="text" class="form-control" id="" placeholder="Apartment #, Room # , Unit #" name="ptmove_placenum" style="margin-bottom:20px;" readonly>

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control " id="" placeholder="City" name="ptmove_city" style="margin-bottom:20px;" readonly>
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control" id="" placeholder="State / Province" name="ptmove_state" style="margin-bottom:20px;" readonly>
							    	</div>

							    	<div class="col-md-5" style="padding-left:0px;">
							    		<input type="text" class="form-control" id="" placeholder="Postal Code" name="ptmove_postalcode" style="margin-bottom:20px;" readonly>
							    	</div>
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="forrespite_categories">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Pickup Date <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control datepicker" id="" placeholder="Date" name="respite_delivery_date" style="margin-bottom:20px;" readonly>
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="forrespite_categories2">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">New Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="" placeholder="Street Address" name="respite_address" style="margin-bottom:20px;" readonly>

							    	<input type="text" class="form-control  " id="" placeholder="Apartment #, Room # , Unit #" name="respite_placenum" style="margin-bottom:20px;" readonly>

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="City" name="respite_city" style="margin-bottom:20px;" readonly>
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="State / Province" name="respite_state" style="margin-bottom:20px;" readonly>
							    	</div>

							    	<div class="col-md-5" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="Postal Code" name="respite_postalcode" style="margin-bottom:20px;" readonly> 
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
							    		<input type="text" class="form-control  " id="" placeholder="Last Name" name="patient_lname" value="<?php echo $information['p_lname'] ?>" readonly>
							    		
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="First Name" value="<?php echo $information['p_fname'] ?>" readonly>
							    	</div>
						 		</div>

						 		<div class="form-group col-md-12">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="" placeholder="Street Address" name="p_address" style="margin-bottom:20px;" value="<?php echo $information['p_street'] ?>">

							    	<input type="text" class="form-control  " id="" placeholder="Apartment #, Room # , Unit #" name="patient_placenum" readonly style="margin-bottom:20px;" value="<?php echo $information['p_placenum'] ?>">

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="City" name="patient_city" style="margin-bottom:20px;" readonly value="<?php echo $information['p_city'] ?>">
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="State / Province" name="patient_state" readonly style="margin-bottom:20px;" value="<?php echo $information['p_state'] ?>">
							    	</div>

							    	<div class="col-md-5" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="Postal Code" name="patient_postalcode" readonly style="margin-bottom:20px;" value="<?php echo $information['p_postalcode'] ?>">
							    	</div>
							    	
						 		</div>

						 		<div class="clearfix"></div>


						 		


							</div>

						 	<div class="col-md-6">

						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Phone Number <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="phone_number" placeholder="Phone Number" readonly name="patient_phone_num" value="<?php echo $information['p_phonenum'] ?>" >
						 		</div>

						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Alt. Phone Number <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="alt_phonenum" placeholder="Phone Number" readonly name="patient_alt_phonenum" value="<?php echo $information['p_altphonenum'] ?>" >
						 		</div>

						 		<div class="clearfix"></div>

						 		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;"><i>Emergency Contact </i></label>

						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Next of Kin <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control" style="" id="" placeholder="Full Name" readonly name="patient_nextofkin" value="<?php echo $information['p_nextofkin'] ?>" >
						 		</div>


						 		<div class="form-group col-md-10" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Next of Kin Phone Number <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control"  id="" placeholder="Phone Number" readonly name="patient_nextofkinphonenum" value="<?php echo $information['p_nextofkinnum'] ?>" >
						 		</div>

						 	</div>


						 	<div class="clearfix"></div>

						 	<hr>

						 	<div class="col-md-12" >
						 			

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
									 		  <span type="checkbox" id="" value="<?php echo $child['equipmentID'] ?>" 
									 		  		 name="equipments[]" 
									 		  		 data-target="#<?php echo trim($child['key_name']) ?>" 
									 		  		 data-value="<?php echo $child['key_desc'];?>"
									 		  		 data-category="<?php echo $equipment['type'];?>"
									 		  		 data-category-id="<?php echo $equipment['categoryID'];?>"
									 		  		 class="checkboxes"><?php echo $child['key_desc'] ?>
									 		    </span>
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
										 		  <span type="" id="" value="<?php echo $child['equipmentID'] ?>" 
										 		  		 name="equipments[]" 
										 		  		 data-target="#<?php echo trim($child['key_name']) ?>" 
										 		  		 data-value="<?php echo $child['key_desc'];?>"
										 		  		 data-category="<?php echo $equipment['type'];?>"
										 		  		 data-category-id="<?php echo $equipment['categoryID'];?>"
										 		  		 class="checkboxes"><?php echo $child['key_desc'] ?>
										 		  </span>
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

						 			<label class="btn btn-default" style="margin-bottom:20px;margin-top:20px;" id="disposable_items_btn">Disposable Equipment</label>
						 		<div class="equipment" style="display:none;">

						 			<div class="clearfix"></div>


							   		 <div class="clearfix"></div>

							    	<div class="col-md-6" style="padding-left:0px;" id="">

							    	<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="disposable_label">CPAP/ BIPAP Supplies <span style="color:red;">*</span></label>


											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">BIPAP <i style="font-size:11px;">(Mask, Headgear, Tubing and Chinstrap)</i></label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[139]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">CPAP <i style="font-size:11px;">(Mask, Headgear, Tubing and Chinstrap)</i></label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[140]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>




											 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Enteral Supplies <span style="color:red;">*</span></label>


											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Feeding Bags</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[155]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											   <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Nebulizer Supplies <span style="color:red;">*</span></label>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Adult Aerosol Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[156]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Nebulizer Kits (Mouthpiece)</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[157]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>


											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Pediatric Aerosol Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[158]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>


											 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Suction Supplies <span style="color:red;">*</span></label>


											<div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Suction Canister</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[159]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Suction Tubing Long </label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[160]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Suction Tubing Short</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[161]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Yankuer Suction Tubing</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[162]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>




											 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Trach Supplies <span style="color:red;">*</span></label>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Corrugated Tubing (7ft)</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[163]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Jet Nebulizers</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[164]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>
											
											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Trach Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[165]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Pressure Line Adaptor</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[166]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>


												  <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Ventilator Supplies <span style="color:red;">*</span></label>


											  <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Circuit, Peep Valve, T piece Adaptor <br><i style="font-size:11px;">(Add items will be added)</i></label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[169]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>
							    	</div>


							    	<div class="col-md-6" style="padding-left:0px;" id="disposable_items_list2">

							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Oxygen Supplies <span style="color:red;">*</span></label>
											
											<div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">"E" Cylinder Wrench</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[141]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">"Y" Connector</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[142]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Adult Nasal Cannula</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[143]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">High Flow Nasal Cannula (6L & Higher)</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[144]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">High Flow O2 Humidifier Bottle</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[145]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Non-Rebreather O2 Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[146]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Connector</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[147]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Humidifier Bottle</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[148]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[149]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>


											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Tubing 21FT</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[150]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>


											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Tubing 7FT</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[151]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Pediatric Nasal Cannula</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[152]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Venturi Mask (Vent)</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[153]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											  <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">X-Mas Tree Adaptor</label>
												 <input type='number' class="form-control "  id='numberinput' name='disposable[154]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>


							    	<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">WheelChair Supplies <span style="color:red;">*</span></label>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Seat Belt</label>
												 <input type='text' class="form-control "  id='numberinput' name='disposable[167]' value='' min="0" />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Anti Tippers</label>
												 <input type='text' class="form-control "  id='numberinput' name='disposable[168]' value='' min="0" />
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
									<textarea class="form-control" rows="3" name="comment" readonly><?php echo $information['comment'] ?></textarea>
								</div>
						 	</div>

<?php endif ;?>

			</div>

			</div>

			

			<div class="clearfix"></div>

			<hr>
			
			<div class="buttons " style="margin-bottom:20px;">
				<button class="btn btn-danger" style="visibility:visible">Print Form</button>
				
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




