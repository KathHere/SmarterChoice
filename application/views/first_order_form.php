<section class="form-container" style="width:100%; min-width:1260px;">
<div class="row">
<div class="container">
	<div class="col-md-12 col-xs-12">
		<div class="inner-form-container page-shadow col-md-12" style="margin-top: 100px;">
				<div class="" style="padding: 14px;">
					<form class="form-horizontal" role="form" action="<?php echo base_url('client_order/add_order') ;?>" method="post" id="order_form_validate">
				
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

			<div class="clearfix"></div>

			<div class="col-md-12 col-xs-12" style="margin-top:10px;">

				 

				  <div class="col-md-12">
				  	<div class="form-group col-md-6"  style="padding-left:0px;">

				  	<?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user'):?>
				  		<div class="form-group " >
					    <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Organization <span style="color:red;">*</span></label>
				
						<select type="select" class="form-control" name="hospice_name">
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
								<input type="text" class="form-control" readonly value="<?php echo $result->group_name ;?>" name="hospice_name" />
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

				   			
				   			<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Person Placing Order <span style="color:red;">*</span></label>
				    	
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

				    		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Phone Number <span style="color:red;">*</span></label>
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
				    	 <div class="col-md-8" style="padding-left:0px;">
				    	 	<?php $email = $this->session->userdata('email') ;?>
				    		<input type="email" class="form-control  " id="" placeholder="Email Address" name="email" style="margin-bottom:20px;" value="<?php echo $email ?>">
				    	</div>
				  </div>
				  <div class="clearfix"></div>


				  <div class="form-group col-md-12" style="padding-left:0px;margin-top:-15px;">
		 			 <label for="exampleInputEmail1" class="control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Delivery Date <span style="color:red;">*</span></label>
		    		<input type="text" class="form-control datepicker" id="" placeholder="2/25/2013" name="delivery_date" style="margin-bottom:20px;">

			 	  </div>

			 		<div class="clearfix"></div>

				   <div class="form-group col-md-12" style="padding-left:0px;margin-top:-15px;">
				    <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Patient Medical Record No. <span style="color:red;">*</span></label>
				    <input type="text" class="form-control " id="" placeholder="Record No." name="patient_mrn">
				  </div>

				 </div>

				  </div>

				  <div class="col-md-12" style="padding-left:0px;">
				<div class="col-md-8" style="padding-left:0px;">

						<div class="col-md-4"  style="padding-left:0px;">
							
							<label>Activity Type<span style="color:red;">*</span></label>

							<div class="radio OpenSans-Reg" style="font-weight:normal;">
							  <label>
							    <input type="radio" name="activity_type" id="radio_pickup" value="delivery" class="radio_act_type">
							     Delivery
							  </label>
							</div>

							<div class="radio OpenSans-Reg" style="font-weight:normal;">
							  <label>
							    <input type="radio" name="activity_type" id="radio_pickup" value="pickup" class="radio_act_type">
							     Pickup
							  </label>
							</div>

							<div class="radio OpenSans-Reg" style="font-weight:normal;">
							  <label>
							    <input type="radio" name="activity_type" id="radio_pickup" value="exchange" class="radio_act_type">
							     Exchange
							  </label>
							</div>

							<div class="radio OpenSans-Reg" style="font-weight:normal;">
							  <label>
							    <input type="radio" name="activity_type" id="radio_pickup" value="pt_move" class="radio_act_type">
							    CUS Move
							  </label>
							</div>

							<div class="radio OpenSans-Reg" style="font-weight:normal;">
							  <label>
							    <input type="radio" name="activity_type" id="radio_pickup" value="respite" class="radio_act_type"> 
							     Respite
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
							   		
							    		<input type="text" class="form-control datepicker2" id="" placeholder="Date Expired" name="pickup_date_expired">
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="p_date_discharge">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Date Discharged<span style="color:red;">*</span></label>
							   		
							    	<input type="text" class="form-control datepicker2" id="" placeholder="Date Discharged" name="pickup_date_discharged">
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="p_date_revoked">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Date Revoked <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control datepicker2" id="" placeholder="Date Revoked" name="pickup_date_revoked">
						 		</div>


						 		<div class="form-group col-md-12" style="display:none;" id="forptmove_categories">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">New Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control" id="" placeholder="Street Address" name="ptmove_address" style="margin-bottom:20px;">

							    	<input type="text" class="form-control" id="" placeholder="Apartment #, Room # , Unit #" name="ptmove_placenum" style="margin-bottom:20px;">

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control " id="" placeholder="City" name="ptmove_city" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control" id="" placeholder="State / Province" name="ptmove_state" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-5" style="padding-left:0px;">
							    		<input type="text" class="form-control" id="" placeholder="Postal Code" name="ptmove_postalcode" style="margin-bottom:20px;">
							    	</div>
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="forrespite_categories">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Pickup Date <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control datepicker" id="" placeholder="Street Address" name="respite_delivery_date" style="margin-bottom:20px;">
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="forrespite_categories2">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">New Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="" placeholder="Street Address" name="respite_address" style="margin-bottom:20px;">

							    	<input type="text" class="form-control  " id="" placeholder="Apartment #, Room # , Unit #" name="respite_placenum" style="margin-bottom:20px;">

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="City" name="respite_city" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="State / Province" name="respite_state" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-5" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="Postal Code" name="respite_postalcode" style="margin-bottom:20px;">
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
							    		<input type="text" class="form-control  " id="" placeholder="Last Name" name="patient_lname" >
							    		
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="First Name" name="patient_fname" >
							    	</div>
						 		</div>

						 		<div class="form-group col-md-12">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="" placeholder="Street Address" name="p_address" style="margin-bottom:20px;">

							    	<input type="text" class="form-control  " id="" placeholder="Apartment #, Room # , Unit #" name="patient_placenum" style="margin-bottom:20px;">

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="City" name="patient_city" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="State / Province" name="patient_state" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-5" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="Postal Code" name="patient_postalcode" style="margin-bottom:20px;">
							    	</div>
							    	
						 		</div>

						 		<div class="clearfix"></div>


						 		


							</div>

						 	<div class="col-md-6">

						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Phone Number <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="phone_number" placeholder="Phone Number" name="patient_phone_num" >
						 		</div>

						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Alt. Phone Number <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="alt_phonenum" placeholder="Phone Number" name="patient_alt_phonenum" >
						 		</div>

						 		<div class="clearfix"></div>

						 		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;"><i>Emergency Contact </i></label>

						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Next of Kin <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control" style="" id="" placeholder="Full Name" name="patient_nextofkin" >
						 		</div>


						 		<div class="form-group col-md-10" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Next of Kin Phone Number <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control"  id="" placeholder="Full Name" name="patient_nextofkinphonenum" >
						 		</div>

						 	</div>

						 	<div class="clearfix"></div>
						 	<hr>

						 	<div class="col-md-12" >
						 		<div class="form-group col-md-12">

						 			<label class="btn btn-default" style="margin-bottom:20px;margin-top:20px;" id="capped_items_btn">Capped Equipment</label>

						 			<div class="clearfix"></div>

							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;visibility:hidden" id="capped_label">Capped Equipment <span style="color:red;">*</span></label>

							   		 <div class="clearfix"></div>

							    	<div class="col-md-6" style="padding-left:0px;display:none" id="capped_items_list">
							
								    		<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
									 		  <input type="checkbox" id="" value="APP Pump and Pad" name="equipments[]"  >APP Pump & Pad

											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Bedside Commode" name="equipments[]" >Bedside Commode
	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Front Wheel Walker" name="equipments[]">Front Wheel Walker
	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Geri Chair" name="equipments[]">Geri Chair
	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Hospital Bed" name="equipments[]"  data-toggle="modal" data-target="#hospital-bed">Hospital Bed

											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Hoyer Lift with Sling" name="equipments[]" >Hoyer Lift with Sling

											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="IV Pole" name="equipments[]">IV Pole
	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Low Air Loss Mattress" name="equipments[]">Low Air Loss Mattress
	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="O2 Holder for Wheelchair" name="equipments[]">O2 Holder for Wheelchair

											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Over Bed Table" name="equipments[]">Over Bed Table
	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Oxygen Concentrator" name="equipments[]" data-toggle="modal" data-target="#oxygen-concentrator">Oxygen Concentrator

											</label>


							    	</div>

							    	<div class="col-md-6" style="padding-left:0px;display:none" id="capped_items_list2">
							    		<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
									 		 <input type="checkbox" id="" value="Oxygen Conserving Device" name="equipments[]" >Oxygen Conserving Device

										</label>
										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Quade Cane" name="equipments[]">Quade Cane

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Reclining Wheelchair" name="equipments[]" data-toggle="modal" data-target="#reclining">Reclining Wheelchair

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Rollator" name="equipments[]">Rollator

										</label>


										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Shower Chair" name="equipments[]"  data-toggle="modal" data-target="#shower-chair">Shower Chair

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Small Volume Nebulizer" name="equipments[]" data-toggle="modal" data-target="#nebulizer">Small Volume Nebulizer
	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Suction Machine" name="equipments[]">Suction Machine
	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Transport Wheelchair" name="equipments[]">Transport Wheelchair
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Trapeze Bar" name="equipments[]">Trapeze Bar


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Wheelchair" name="equipments[]" data-toggle="modal" data-target="#wheelchair">Wheelchair

										</label>
							    	</div>
							    	
						 		</div>

						 		<label class="btn btn-default" style="margin-bottom:20px;margin-top:20px;" id="noncapped_items_btn">Non-Capped Equipment</label>

						 		<div class="form-group col-md-12" style="padding-right:0px;">

							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;visibility:hidden" id="noncapped_label">Non-Capped Equipment <span style="color:red;">*</span></label>

							   		 <div class="clearfix"></div>

							    	<div class="col-md-6" style="padding-left:0px;display:none" id="noncapped_items">

							    		<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
									 		 <input type="checkbox" id="" value="2 inches Wheelchair Gel Cushion" name="noncapped_equipments[]">  2" Wheelchair Gel Cushion

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Alternating Pressure Mattress" name="noncapped_equipments[]" data-toggle="modal" data-target="#pressure-mattress" >Alternating Pressure Mattress

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Alternating Pressure Pump" name="noncapped_equipments[]">Alternating Pressure Pump


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="BIPAP" name="noncapped_equipments[]" data-toggle="modal" data-target="#bipap"> BIPAP


										</label>

										

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Commode 3 in 1" name="noncapped_equipments[]">Commode 3 in 1


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Commode Bariatric" name="noncapped_equipments[]">Commode Bariatric


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Commode Pail" name="noncapped_equipments[]">Commode Pail


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Compressor 50 PSI" name="noncapped_equipments[]">Compressor 50 PSI


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="CPAP" name="noncapped_equipments[]" data-toggle="modal" data-target="#cpap">CPAP


										</label>

										

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Deluxe Walker" name="noncapped_equipments[]"  data-toggle="modal" data-target="#deluxe-walker">Deluxe Walker


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="E-cylinder" name="noncapped_equipments[]"   data-toggle="modal" data-target="#e-cylinder">E-cylinder


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Feeding Bags" name="noncapped_equipments[]">Feeding Bags


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Feeding Pump" name="noncapped_equipments[]">Feeding Pump


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Floor Mat" name="noncapped_equipments[]">Floor Mat


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Foot Cradle for Hospital Bed" name="noncapped_equipments[]">Foot Cradle for Hospital Bed


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Gastric Drainage Aspirator" name="noncapped_equipments[]" data-toggle="modal" data-target="#gastric-drainage">Gastric Drainage Aspirator


										</label>


										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Geri Chair 3 Position With Tray" name="noncapped_equipments[]">Geri Chair 3 Position With Tray


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hemi Walker" name="noncapped_equipments[]">Hemi Walker


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hi-Low Electric Hospital Bed" name="noncapped_equipments[]" data-toggle="modal" data-target="#hi-low-bed">Hi-Low Electric Hospital Bed


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hospital Bed" name="noncapped_equipments[]" data-toggle="modal" data-target="#hospital-bed">Hospital Bed


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift Swing" name="noncapped_equipments[]">Hoyer Lift Swing


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="IV Pole" name="noncapped_equipments[]">IV Pole


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Loss Air Loss Mattress" name="noncapped_equipments[]">Loss Air Loss Mattress


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Low Air Loss Mattress Bariatric" name="noncapped_equipments[]">Low Air Loss Mattress Bariatric


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Low Air Loss Mattress Turn Q" name="noncapped_equipments[]">Low Air Loss Mattress Turn Q


										</label>

							    	</div>

							    	<div class="col-md-6" style="padding-left:0px;display:none" id="noncapped_items2">
							    		<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
									 		 <input type="checkbox" id="" value="Low Bed" name="noncapped_equipments[]"  >Low Bed


										</label>
										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="M6-Cylinder" name="noncapped_equipments[]" data-toggle="modal" data-target="#m6-cylinder">M6-Cylinder


										</label>



										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Over Bed Table" name="noncapped_equipments[]">Over Bed Table


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Oxygen Concentrator" name="noncapped_equipments[]" data-toggle="modal" data-target="#oxygen-concentrator" >Oxygen Concentrator


										</label>


										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Oxygen Concentrator Portable" name="noncapped_equipments[]">Oxygen Concentrator Portable


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Oxygen Conserving Device" name="noncapped_equipments[]"  >Oxygen Conserving Device


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Oxygen Cylinder Rack" name="noncapped_equipments[]">Oxygen Cylinder Rack

	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Oxygen Cylinder Regulator with Chart" name="noncapped_equipments[]">Oxygen Cylinder Regulator with Chart


										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Oxygen Holder for Wheelchair" name="noncapped_equipments[]"  >Oxygen Holder for Wheelchair

	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Oxygen Cylinder Rack" name="noncapped_equipments[]">Oxygen Cylinder Rack
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Oxygen Liquid" name="noncapped_equipments[]"  >Oxygen Liquid

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Pulse Oximeter" name="noncapped_equipments[]">Pulse Oximeter

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Quad Cane" name="noncapped_equipments[]" >Quad Cane

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Shower Chair" name="noncapped_equipments[]"   data-toggle="modal" data-target="#shower-chair">Shower Chair

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Small Volume Nebulizer" name="noncapped_equipments[]"  data-toggle="modal" data-target="#nebulizer">Small Volume Nebulizer

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Suction Machine" name="noncapped_equipments[]">Suction Machine

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Tab Alarm" name="noncapped_equipments[]">Tab Alarm

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Transfer Bench" name="noncapped_equipments[]">Transfer Bench

										</label>

										

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Trapeze Bar" name="noncapped_equipments[]"> Trapeze Bar

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Ventilator PLV" name="noncapped_equipments[]"> Ventilator PLV

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Walker with Wheels" name="noncapped_equipments[]">Walker with Wheels

										</label>


										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Wheel Chair Anti-Tippers" name="noncapped_equipments[]">Wheel Chair Anti-Tippers

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Wheel Chair Companion" name="noncapped_equipments[]">Wheel Chair Companion

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Wheelchair" name="noncapped_equipments[]">Wheelchair

										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Wheelchair Seat Belt" name="noncapped_equipments[]">Wheelchair Seat Belt


										</label>
							    	</div>



							    	
						 		</div>

						 	</div>


						 	<div class="col-md-12" >
						 		<div class="form-group col-md-12">

						 			<label class="btn btn-default" style="margin-bottom:20px;margin-top:20px;" id="disposable_items_btn">Disposable Equipment</label>

						 			<div class="clearfix"></div>


							   		 <div class="clearfix"></div>

							    	<div class="col-md-6" style="padding-left:0px;display:none" id="disposable_items_list">

							    	<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="disposable_label">CPAP/ BIPAP Supplies <span style="color:red;">*</span></label>


											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">BIPAP <i style="font-size:11px;">(Mask, Headgear, Tubing and Chinstrap)</i></label>
												 <input type='number' class="form-control "  id='numberinput' name='' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">CPAP <i style="font-size:11px;">(Mask, Headgear, Tubing and Chinstrap)</i></label>
												 <input type='number' class="form-control "  id='numberinput' name='' value='0' />
											</div>
											 <div class="clearfix"></div>




											 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Enteral Supplies <span style="color:red;">*</span></label>


											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Feeding Bags</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											   <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Nebulizer Supplies <span style="color:red;">*</span></label>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Adult Aerosol Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Nebulizer Kits (Mouthpiece)</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>


											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Pediatric Aerosol Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>


											 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Suction Supplies <span style="color:red;">*</span></label>


											<div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Suction Canister</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Suction Tubing Long </label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Suction Tubing Short</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Yankuer Suction Tubing</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>




											 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Trach Supplies <span style="color:red;">*</span></label>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Corrugated Tubing (7ft)</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Jet Nebulizers</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>
											
											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Trach Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='1' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Pressure Line Adaptor</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>


												  <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Ventilator Supplies <span style="color:red;">*</span></label>


											  <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Circuit, Peep Valve, T piece Adaptor <br><i style="font-size:11px;">(Add items will be added)</i></label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>
							    	</div>


							    	<div class="col-md-6" style="padding-left:0px;display:none" id="disposable_items_list2">

							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">Oxygen Supplies <span style="color:red;">*</span></label>
											
											<div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">"E" Cylinder Wrench</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">"Y" Connector</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Adult Nasal Cannula</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-12">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">High Flow Nasal Cannula (6L & Higher)</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">High Flow O2 Humidifier Bottle</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Non-Rebreather O2 Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Connector</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Humidifier Bottle</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Mask</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>


											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Tubing 21FT</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>


											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">O2 Tubing 7FT</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Pediatric Nasal Cannula</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Venturi Mask (Vent)</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>

											  <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">X-Mas Tree Adaptor</label>
												 <input type='number' class="form-control "  id='numberinput' name='mynumber' value='0' />
											</div>
											 <div class="clearfix"></div>


							    	<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="margin-bottom:5px;" id="capped_label">WheelChair Supplies <span style="color:red;">*</span></label>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Seat Belt</label>
												 <input type='text' class="form-control "  id='numberinput' name='mynumber' value=' ' />
											</div>
											 <div class="clearfix"></div>

											 <div class="form-group col-md-10">
												<label class="control-label OpenSans-Reg" style="font-weight: normal;margin-bottom:5px;">Anti Tippers</label>
												 <input type='text' class="form-control "  id='numberinput' name='mynumber' value=' ' />
											</div>
											 <div class="clearfix"></div>


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

			<div class="col-md-4 sidebar OpenSans-Reg" style="display:block">
					<h4 style="color:#85C879;">Order Summary</h4>
					<div class="col-md-12" style="padding-left:0px;">
						<label>Capped Equipment</label>
						
						<ol class=" OpenSans-Reg" style=";margin-left: -20px;">
							<li>
									
								<p class="selected-item">Oxygen Concentrator</p>
								<ul class="OpenSans-Reg selected-item-list" style="list-style-type:none;margin-left: -20px;">
									<li>
										<label>Oxygen Concentrator type:</label>
										<span>10 Liters</span>
									</li>
									<li>
										<label>Duration:</label>
										<span>CONT</span>
									</li>
									<li>
										<label>Liter Flow:</label>
										<span>10 LPM</span>
									</li>
									<li>
										<label>Flow Type:</label>
										<span>Nasal Canula</span>
									</li>
								</ul>	
							</li>

							<li>
								<p class="selected-item">Small Volume Nebulizer</p>
								<ul class="OpenSans-Reg selected-item-list" style="list-style-type:none;margin-left: -20px;">
									<li>
										<label>Aerosol Mask:</label>
										<span>Yes</span>
									</li>
									
								</ul>

							</li>
						</ol>

						
					</div>
					<div class="clearfix"></div>
					<hr>
					<div class="col-md-12" style="padding-left:0px;">
						<label>Non-capped Equipment</label>
						
						<ol class=" OpenSans-Reg" style=";margin-left: -20px;">
							<li>
									
								<p class="selected-item">Oxygen Concentrator</p>
								<ul class="OpenSans-Reg selected-item-list" style="list-style-type:none;margin-left: -20px;">
									<li>
										<label>Oxygen Concentrator type:</label>
										<span>10 Liters</span>
									</li>
									<li>
										<label>Duration:</label>
										<span>CONT</span>
									</li>
									<li>
										<label>Liter Flow:</label>
										<span>10 LPM</span>
									</li>
									<li>
										<label>Flow Type:</label>
										<span>Nasal Canula</span>
									</li>
								</ul>	



							</li>

							<li>
									
								<p class="selected-item">Oxygen Concentrator</p>
								<ul class="OpenSans-Reg selected-item-list" style="list-style-type:none;margin-left: -20px;">
									<li>
										<label>Oxygen Concentrator type:</label>
										<span>10 Liters</span>
									</li>
									<li>
										<label>Duration:</label>
										<span>CONT</span>
									</li>
									<li>
										<label>Liter Flow:</label>
										<span>10 LPM</span>
									</li>
									<li>
										<label>Flow Type:</label>
										<span>Nasal Canula</span>
									</li>
								</ul>	



							</li>

							<li>
								
								<p class="selected-item">Small Volume Nebulizer</p>
								<ul class="OpenSans-Reg selected-item-list" style="list-style-type:none;margin-left: -20px;">
									<li>
										<label>Aerosol Mask:</label>
										<span>Yes</span>
									</li>
									
								</ul>

							</li>
						</ol>


					</div>
				</div>



			<div class="clearfix"></div>

			<hr>
			
			<div class="buttons " style="margin-bottom:20px;">
				<button class="btn btn-danger" style="visibility:hidden">Print Form</button>
				<button class="btn btn-warning pull-right ">Submit Form</button>
			</div>
			
			<?php $id = $this->session->userdata('userID') ;?>
			<input type="hidden" name="person_who_ordered" value="<?php echo $id ;?>" />


			</form>
				</div>
		</div>

		<div class="">
			
		</div>
	</div>
</div>
</div>
</section>





<!-- Modal for Oxygen concentrator -->
<div class="modal fade" id="oxygen-concentrator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		<div class="col-md-6">

        				<div class="form-group">
						    <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
						    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
						  </div>

        				<label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
			        	<div class="checkbox">
						  <label>
						    <input type="checkbox" name="oc_type" id="optionsRadios1" value="5" >
						  5 Liters
						  </label>
						</div>

						<div class="checkbox">
						  <label>
						    <input type="checkbox" name="oc_type" id="optionsRadios1" value="10" >
						   10 Liters
						  </label>
						</div>

						

						

        		</div>

        		<div class="col-md-6">
        				 

        				<label>Duration <span style="color:red;">*</span></label>
						<div class="radio">
						  <label>
						    <input type="radio" name="duration" id="optionsRadios1" value="cont" >
						   CONT
						  </label>
						</div>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="duration" id="optionsRadios1" value="prn" >
						  PRN
						  </label>
						</div>

						  <label>Flow Type <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="flow_type" id="optionsRadios1" value="nasal canula" >
						  Nasal Canula
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="flow_type" id="optionsRadios1" value="oxygen mask" >
						   Oxygen Mask
						  </label>
						</div>
        		</div>
        	</div>
        </form>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal hi-low -->
<div class="modal fade" id="hi-low-bed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hi-Low Electric Bed</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		
        				<label>Hi-Low Electric Bed Type <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="hl_electricbed_type" id="optionsRadios1" value="full rails" checked>
						 Full Rails
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="hl_electricbed_type" id="optionsRadios1" value="half rails" checked>
						  Half Rails
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="hl_electricbed_type" id="optionsRadios1" value="no rails" checked>
						  No Rails
						  </label>
						</div>

						

        		

        	</div>
        </form>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal gastric-drainage -->
<div class="modal fade" id="gastric-drainage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Gastric Drainage Aspirator</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		
        				<label>Gastric Drainage Type <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="gastic_drainage_type" id="optionsRadios1" value="cont">
						Cont.
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="gastic_drainage_type" id="optionsRadios1" value="intermittant">
						 Intermittant
						  </label>
						</div>

					

        	</div>
        </form>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>



<!-- Modal Small volume nebulizer -->
<div class="modal fade" id="nebulizer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Small Volume Nebulizer</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		
        				<label>Aerosol Mask <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="aerosol_mask" id="optionsRadios1" value="yes">
						 Yes
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="aerosol_mask" id="optionsRadios1" value="no">
						  No
						  </label>
						</div>

						

        		

        	</div>
        </form>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Reclining wheelchair -->
<div class="modal fade" id="reclining" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reclining wheelchair</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		
        				<label>Type of Reclining Wheelchair<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="reclining_wheelchair_type" id="optionsRadios1" value="16">
						 16"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="reclining_wheelchair_type" id="optionsRadios1" value="18">
						 18"
						  </label>
						</div>

						<label>Type of Legrest (R) <span style="color:red;">*</span></label>
						<div class="radio">
						  <label>
						    <input type="radio" name="legrest_type" id="optionsRadios1" value="elevating legrests">
						 Elevating Legrests
						  </label>
						</div>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="legrest_type" id="optionsRadios1" value="footrests">
						 Footrests
						  </label>
						</div>

						


        	</div>
        </form>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Shower chair -->
<div class="modal fade" id="shower-chair" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Shower chair</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		
        				<label>Type of Shower chair<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="shower_chair_type" id="optionsRadios1" value="with back">
						 With Back
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="shower_chair_type" id="optionsRadios1" value="without back">
						Without Back
						  </label>
						</div>

						
        	</div>
        </form>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal wheelchair -->
<div class="modal fade" id="wheelchair" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		
        				<label>Type of Wheelchair<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="wheelchair_type" id="optionsRadios1" value="16" >
						16"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="wheelchair_type" id="optionsRadios1" value="18" >
						18"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="wheelchair_type" id="optionsRadios1" value="20" >
						20"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="wheelchair_type" id="optionsRadios1" value="22" >
						22"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="wheelchair_type" id="optionsRadios1" value="24" >
						24"
						  </label>
						</div>


						<label>Type of Legrest<span style="color:red;">*</span></label>
						<div class="radio">
						  <label>
						    <input type="radio" name="type_of_legrest" id="optionsRadios1" value="elevating legrests" >
						Elevating 	Legrests
						  </label>
						</div>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="type_of_legrest" id="optionsRadios1" value="footrests" >
						Footrests
						  </label>
						</div>

						

						
        	</div>
        </form>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal hospital bed -->
<div class="modal fade" id="hospital-bed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital bed</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		
        				<label>Type of Hospital Bed <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="hospital_bed_type" id="optionsRadios1" value="full electric" >
						Full Electric
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="hospital_bed_type" id="optionsRadios1" value="semi electric" >
						Semi Electric
						  </label>
						</div>
						

						

						<label>Type of Rails<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="rails_type" id="optionsRadios1" value="full rails" >
						Full Rails
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="rails_type" id="optionsRadios1" value="half rails" >
						Half Rails
						  </label>
						</div>
						<div class="radio">
						  <label>
						    <input type="radio" name="rails_type" id="optionsRadios1" value="no rails" >
								No rails
						  </label>
						</div>
						
        	</div>
        </form>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal BIPAP setup -->
<div class="modal fade" id="bipap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">BIPAP</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		
			        	<div class="form-group">
			        		<label>IPAP <span style="color:red;">*</span></label>
			        		<input type="text" class="form-control" placeholder="e.g 14" name="ipap">
			        	</div>

			        	<div class="form-group">
			        		<label>EPAP <span style="color:red;">*</span></label>
			        		<input type="text" class="form-control" placeholder="e.g5" name="epap">
			        	</div>

			        	<div class="form-group">
			        		<label>Rate <i>(If applicable)</i> </label>
			        		<input type="text" class="form-control" placeholder="" name="rate">
			        	</div>

						
        	</div>
        </form>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal CPAP setup -->
<div class="modal fade" id="cpap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		
			        	<div class="form-group">
			        		<label>IPAP <span style="color:red;">*</span></label>
			        		<input type="text" class="form-control" placeholder="e.g 14" name="cpap_ipap">
			        	</div>
			        	
						
        	</div>
        </form>
       			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal E-cylinder -->
<div class="modal fade" id="e-cylinder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">E-Cylinder</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
   			<div class="">
   				 <form role="form">
		        	<div class="col-md-12">
		        		
			        	<div class="form-group">
			        		<label>Quantity of E-cylinder NC <span style="color:red;">*</span></label>
			        		<input type="text" class="form-control" placeholder="ex. 1" name="quantity">
			        	</div>
								
		        	</div>
   				 </form>
   			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal pressure-mattress -->
<div class="modal fade" id="pressure-mattress" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Alternating Pressure Mattress</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
   			<div class="">
   				 <form role="form">
		        	<div class="col-md-12">
		        		
			        		<label>Extended? NC <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="extended_nc" id="optionsRadios1" value="yes" >
						Yes
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="extended_nc" id="optionsRadios1" value="no" >
						No
						  </label>
						</div>
								
		        	</div>
   				 </form>
   			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Deluxe walker -->
<div class="modal fade" id="deluxe-walker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Delux Walker</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
   			<div class="">
   				 <form role="form">
		        	<div class="col-md-12">
		        		
			        		<label>Delux Walker Options <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="delux_walker_options" id="optionsRadios1" value="4 wheels" >
						4 Wheels
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="delux_walker_options" id="optionsRadios1" value="brakes" >
						Brakes
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="delux_walker_options" id="optionsRadios1" value="basket" >
						Basket
						  </label>
						</div>
								

								<div class="radio">
						  <label>
						    <input type="radio" name="delux_walker_options" id="optionsRadios1" value="seat" >
						Seat
						  </label>
						</div>
								
								
		        	</div>
   				 </form>
   			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>




<!-- Modal m6-cylinder -->
<div class="modal fade" id="m6-cylinder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">M6-Cylinder</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
   			<div class="">
   				 <form role="form">
		        	<div class="col-md-12">
		        		
			        	<div class="form-group">
			        		<label>Quantity of M6-cylinder NC <span style="color:red;">*</span></label>
			        		<input type="text" class="form-control" placeholder="ex. 1" name="quantity_m6_cylinder">
			        	</div>
								
		        	</div>
   				 </form>
   			</div>
       	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
