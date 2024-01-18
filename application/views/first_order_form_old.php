<section class="form-container" style="width:100%; min-width:1260px;">
<div class="row">
<div class="container">
	<div class="col-md-12 col-xs-12">
		<div class="inner-form-container page-shadow col-md-12" style="margin-top: 100px;">
				<div class="" style="padding: 14px;">
					<form class="form-horizontal" role="form" action="<?php echo base_url('client_order/add_order') ;?>" method="post" id="order_form_validate">
				
			<div class="col-md-6 col-xs-6" style="margin-top:20px;padding-left:0px;">
				
			    <div class="alert alert-info fade in" style="height:134px">
			     
			      <strong class="OpenSans-reg" style="font-size: 16px;">Having hard time in making orders and filling out the forms ?</strong> 

			      <p class="OpenSans-reg" style="margin-top: 20px;">Please call all orders / pickups to : 
			     	 <strong>(702) 248 - 0056</strong>
			      </p>

			      <p class="OpenSans-reg">Please  facx all DME Order forms to : 
			      	<strong>(702) 889 - 0059</strong>
			      </p>

			    </div> 

			</div>	

			<div class="col-md-6 col-xs-6" style="margin-top:10px;">

				 <div class="form-group col-md-9">
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

				  <div class="form-group col-md-8" >
				   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Person Placing Order <span style="color:red;">*</span></label>
				    	
				    	<input type="hidden" name="person_placing_order_id" />
				    	<div class="col-md-6" style="padding-left:0px;">
				    		<?php $fname = $this->session->userdata('firstname') ;?>
				    		<input type="text" class="form-control  " id="" placeholder="First Name" name="person_placing_order_fname" style="margin-bottom:20px;" value="<?php echo $fname ?>">
				    	</div>

				    	<div class="col-md-6" style="padding-right:0px;">
				    		<?php $lname = $this->session->userdata('lastname') ;?>
				    		<input type="text" class="form-control  " id="" placeholder="Last Name" name="person_placing_order_lname" style="margin-bottom:20px;" value="<?php echo $lname ?>">
				    	</div>


				    	 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Phone Number <span style="color:red;">*</span></label>
				    	 <div class="col-md-8" style="padding-left:0px;">
				    	 	<?php $phone = $this->session->userdata('phone_num') ;?>
				    		<input type="text" class="form-control  " id="" placeholder="Phone Number" name="phone_num" style="margin-bottom:20px;" value="<?php echo $phone ?>">
				    	</div>

				    	<div class="clearfix"></div>
				    	 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Email Address<span style="color:red;">*</span></label>
				    	 <div class="col-md-8" style="padding-left:0px;">
				    	 	<?php $email = $this->session->userdata('email') ;?>
				    		<input type="email" class="form-control  " id="" placeholder="Email Address" name="email" style="margin-bottom:20px;" value="<?php echo $email ?>">
				    	</div>
				    	
				    	
			 		</div>

				  <div class="clearfix"></div>

				   <div class="form-group col-md-7">
				    <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Patient Medical Record No. <span style="color:red;">*</span></label>
				    <input type="text" class="form-control " id="" placeholder="Record No." name="patient_mrn">
				  </div>





			

			
			</div>

			<div class="clearfix"></div>


			<div class="col-md-12" style="padding-left:0px;">
				<div class="col-md-8" style="padding-left:0px;margin-top: -267px;">

						<div class="col-md-4">
							
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
							   		
							    	<select class="form-control" name="pickup_sub_cat">
							    		<option>Expired</option>
							    		<option>Discharged</option>
							    		<option>Revoked</option>
							    	</select>
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="forptmove_categories">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">New Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="" placeholder="Street Address" name="ptmove_address" style="margin-bottom:20px;">

							    	<input type="text" class="form-control  " id="" placeholder="Apartment #, Room # , Unit #" name="ptmove_placenum" style="margin-bottom:20px;">

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="City" name="ptmove_city" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="State / Province" name="ptmove_state" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-5" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="Postal Code" name="ptmove_postalcode" style="margin-bottom:20px;">
							    	</div>
							    	
						 		</div>

						 		<div class="form-group col-md-12" style="display:none;" id="forrespite_categories">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Delivery Date <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="" placeholder="Street Address" name="respite_delivery_date" style="margin-bottom:20px;">
							    	
						 		</div>

						 		<div class="form-group col-md-12">
						 			 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Pickup Date <span style="color:red;">*</span></label>
							    		<input type="text" class="form-control  " id="" placeholder="Street Address" name="pickup_date" style="margin-bottom:20px;">
						 		</div>


						 		<div class="clearfix"></div>

							</div>

							<div class="clearfix"></div>
							<h3 class="OpenSans-Reg">Patient Information</h3>
							

							<div class="col-md-6" style="padding-left:0px;">
								<div class="form-group col-md-12">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Patient Name <span style="color:red;">*</span></label>
							   		 <div class="clearfix"></div>
							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="First Name" name="p_fname" >
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="Last Name" name="p_lname" >
							    	</div>
						 		</div>

						 		<div class="form-group col-md-12">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="" placeholder="Street Address" name="p_address" style="margin-bottom:20px;">

							    	<input type="text" class="form-control  " id="" placeholder="Apartment #, Room # , Unit #" name="p_placenum" style="margin-bottom:20px;">

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="City" name="p_city" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="State / Province" name="p_state" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-5" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="Postal Code" name="p_postalcode" style="margin-bottom:20px;">
							    	</div>
							    	
						 		</div>

						 		<div class="clearfix"></div>


						 		


							</div>

						 	<div class="col-md-6">

						 		<div class="form-group col-md-8" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Phone Number <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="phone_number" placeholder="Phone Number" name="phone_num" >
						 		</div>

						 		<div class="form-group col-md-8" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Alt. Phone Number <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control  " id="alt_phonenum" placeholder="Phone Number" name="alt_phonenum" >
						 		</div>

						 		<div class="clearfix"></div>

						 		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;"><i>Emergency Contact </i></label>

						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Next of Kin<span style="color:red;">*</span></label>
							    	<input type="text" class="form-control" style="width:175px !important;" id="" placeholder="Full Name" name="nextofkin" >
						 		</div>


						 		<div class="form-group col-md-10" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Next of Kin Phone Number<span style="color:red;">*</span></label>
							    	<input type="text" class="form-control" style="width:175px !important;" id="" placeholder="Full Name" name="nextofkinphonenum" >
						 		</div>

						 	</div>

						 	<div class="clearfix"></div>
						 	<hr>

						 	<div class="col-md-12" style="padding-left:0px;">
						 		<div class="form-group col-md-12">

						 			<label class="btn btn-default" style="margin-bottom:20px;margin-top:20px;" id="capped_items_btn">Capped Equipments</label>

						 			<div class="clearfix"></div>

							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;visibility:hidden" id="capped_label">Capped Equipments <span style="color:red;">*</span></label>

							   		 <div class="clearfix"></div>

							    	<div class="col-md-6" style="padding-left:0px;display:none" id="capped_items_list">
							
								    		<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										 		 <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]" data-toggle="modal" data-target="#oxygen-concentrator"> Oxygen Concentrator
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]" data-toggle="modal" data-target="#nebulizer">Small Volume Nebulizer	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="sample" value="Hoyer Lift W/Sling" name="equipments[]">Low Air Loss Mattress	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Over Bed Table	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Rollator
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]" data-toggle="modal" data-target="#reclining">Reclining Wheelchair	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">IV Pole	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Oxygen Conserving Device	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Suction Machine	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">APP Pump & Pad	
											</label>

											<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
											  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]" data-toggle="modal" data-target="#shower-chair">Shower Chair	
											</label>


							    	</div>

							    	<div class="col-md-6" style="padding-left:0px;display:none" id="capped_items_list2">
							    		<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
									 		 <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]" data-toggle="modal" data-target="#wheelchair">Wheelchair
										</label>
										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Hoyer Lift with Sling	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Quade Cane
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">O2 Holder for Wheelchair
										</label>


										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]" data-toggle="modal" data-target="#hospital-bed">Hospital Bed
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Bedside Commode	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Front Wheel Walker	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Transport Wheelchair
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Geri Chair	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Trapeze Bar	
										</label>
							    	</div>
							    	
						 		</div>

						 		<label class="btn btn-default" style="margin-bottom:20px;margin-top:20px;" id="noncapped_items_btn">Non-Capped Equipments</label>

						 		<div class="form-group col-md-12">

							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;visibility:hidden" id="noncapped_label">Non-Capped Equipments <span style="color:red;">*</span></label>

							   		 <div class="clearfix"></div>

							    	<div class="col-md-6" style="padding-left:0px;display:none" id="noncapped_items">

							    		<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
									 		 <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">  Alternating Pressure Pump
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]" data-toggle="modal" data-target="#bipap-setup">BIPAP Set-up
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Commode Pail
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]" data-toggle="modal" data-target="#cpap-setup"> CPAP Set-up	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Feeding Bags
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Geri Chair 3 Position With Tray
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Hoyer Lift Swing	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Low Air Loss Mattress Turn Q
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Over Bed Table
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Oxygen Conserving Device
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]"  data-toggle="modal" data-target="#e-cylinder">E-cylinder
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Oxygen Liquid
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]"  data-toggle="modal" data-target="#shower-chair">Shower Chair
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Tab Alarm
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Walker with Wheels
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Wheelchair
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Wheelchair Seat Belt
										</label>


										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">BIPAP
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Commode 3 in 1
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">CPAP
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Feeding Pump
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Foot Cradle for Hospital Bed
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Loss Air Loss Mattress
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Low Bed
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Oxygen Concentrator Portable
										</label>

							    	</div>

							    	<div class="col-md-6" style="padding-left:0px;display:none" id="noncapped_items2">
							    		<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
									 		 <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]"  data-toggle="modal" data-target="#pressure-mattress">Alternating Pressure Mattress
										</label>
										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Commode Bariatric (Heavy Duty)
										</label>



										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Compressor 50 PSI (Heavy Duty)
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]"  data-toggle="modal" data-target="#deluxe-walker">Deluxe Walker
										</label>


										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Floor Mat
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]"  data-toggle="modal" data-target="#hospital-bed">Hospital Bed	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">IV Pole	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Low Air Loss Mattress Bariatric
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]"  data-toggle="modal" data-target="#oxygen-concentrator">Oxygen Concentrator	
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Oxygen Cylinder Rack
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]"  data-toggle="modal" data-target="#m6-cylinder">M6-Cylinder
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Pulse Oximeter
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]"  data-toggle="modal" data-target="#nebulizer">Small Volume Nebulizer
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]"> Trapeze Bar
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Hemi Walker
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Wheel Chair Companion
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Ventilator PLV
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Oxygen Cylinder Regulator with Chart
										</label>

										

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]"> Oxygen Holder for Wheelchair
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]"> Quad Cane
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Suction Machine
										</label>


										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Transfer Bench
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">2" Wheelchair Gel Cushion
										</label>

										<label class="checkbox OpenSans-Reg" style="font-weight:normal;">
										  <input type="checkbox" id="" value="Hoyer Lift W/Sling" name="equipments[]">Wheel Chair Anti-Tippers
										</label>
							    	</div>



							    	
						 		</div>

						 	</div>

					
						 	<button  class="btn btn-default">Done Selecting Equipments</button>
							<div class="col-md-12" style="margin-top:20px;">
								<div class="form-group OpenSans-Reg">
									<label>Comment</label>
									<textarea class="form-control" rows="3"></textarea>
								</div>

								<!-- <div class="form-group col-md-8" style="padding-left:0px;">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Person Placing Order <span style="color:red;">*</span></label>
							    	
							    	
							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="First Name" name="order_time" style="margin-bottom:20px;">
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="Last Name" name="order_time" style="margin-bottom:20px;">
							    	</div>


							    	 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Phone Number <span style="color:red;">*</span></label>
							    	 <div class="col-md-8" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="First Name" name="order_time" style="margin-bottom:20px;">
							    	</div>

							    	<div class="clearfix"></div>

							    	 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Email Address<span style="color:red;">*</span></label>
							    	 <div class="col-md-8" style="padding-left:0px;">
							    		<input type="email" class="form-control  " id="" placeholder="Email Address" name="order_time" style="margin-bottom:20px;">
							    	</div>
							    	
							    	
						 		</div> -->

						 		<div class="clearfix"></div>
						 		
							</div>


				</div>

				<div class="col-md-4 sidebar OpenSans-Reg" style="display:none">
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
			</div>



			<div class="clearfix"></div>

			<hr>
			
			<div class="buttons " style="margin-bottom:20px;">
				<button class="btn btn-danger">Print Form</button>
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
        				<label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						  5 Liters
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						   10 Liters
						  </label>
						</div>

						<label>Duration <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						  PRN
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						   CONT
						  </label>
						</div>

        		</div>

        		<div class="col-md-6">
        				 <div class="form-group">
						    <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
						    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
						  </div>

						  <label>Flow Type <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						  Nasal Canula
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
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
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						 Yes
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
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
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						 16"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						 18"
						  </label>
						</div>

						<label>Type of Legrest (R) <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						 Footrests
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						 Elevating Legrests
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
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						 With Back
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
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
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						16"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						18"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						20"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						22"
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						24"
						  </label>
						</div>


						<label>Type of Legrest<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Footrests
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Elevating 	Legrests
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
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Full Electric
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Semi Electric
						  </label>
						</div>

						

						<label>Type of Rails<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Full Rails
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Half Rails
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
<div class="modal fade" id="bipap-setup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">BIPAP Set up</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		
        				<label>BIPAP Set-up NC<span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Mask
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Head Gear
						  </label>
						</div>
						
			        	<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Tubing
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Chinstrap
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


<!-- Modal CPAP setup -->
<div class="modal fade" id="cpap-setup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Set up</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
       				 <form role="form">
        	<div class="col-md-12">
        		
        				<label>CPAP Set-up <span style="color:red;">*</span></label>
			        	<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Mask
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Head Gear
						  </label>
						</div>
						
			        	<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Tubing
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Chinstrap
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
			        		<input type="text" class="form-control" placeholder="ex. 1">
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
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Yes
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
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
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						4 Wheels
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Brakes
						  </label>
						</div>

						<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
						Basket
						  </label>
						</div>
								

								<div class="radio">
						  <label>
						    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
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
			        		<input type="text" class="form-control" placeholder="ex. 1">
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
