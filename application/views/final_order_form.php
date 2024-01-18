<section class="form-container" style="width:100%; min-width:1260px;">
<div class="row">
<div class="container">
	<div class="col-md-12 col-xs-12">
		<div class="inner-form-container page-shadow col-md-12" style="margin-top: 100px;">
				<div class="" style="padding: 14px;margin-top: 15px;background: rgba(237,237,237,1);
background: -moz-linear-gradient(top, rgba(237,237,237,1) 0%, rgba(246,246,246,1) 54%, rgba(255,255,255,1) 100%);
background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(237,237,237,1)), color-stop(54%, rgba(246,246,246,1)), color-stop(100%, rgba(255,255,255,1)));
background: -webkit-linear-gradient(top, rgba(237,237,237,1) 0%, rgba(246,246,246,1) 54%, rgba(255,255,255,1) 100%);
background: -o-linear-gradient(top, rgba(237,237,237,1) 0%, rgba(246,246,246,1) 54%, rgba(255,255,255,1) 100%);
background: -ms-linear-gradient(top, rgba(237,237,237,1) 0%, rgba(246,246,246,1) 54%, rgba(255,255,255,1) 100%);
background: linear-gradient(to bottom, rgba(237,237,237,1) 0%, rgba(246,246,246,1) 54%, rgba(255,255,255,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ededed', endColorstr='#ffffff', GradientType=0 );">
					
				
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
<?php if(!empty($informations)) :?>
	<?php $information = $informations[0]; ?>
			<h4 class="OpenSans-Reg pull-left" style="text-align: center;margin-left:30px">WO#<?php echo $information['uniqueID'] ?></h4>
		
			
			</div>	


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
				    		<div class="clearfix"></div>
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
							 <div class="clearfix"></div>
				    	 <div class="col-md-8" style="padding-left:0px;">
				    	 	
				    		<input type="text" class="form-control  " id="person_num" placeholder="Phone Number" name="phone_num" style="margin-bottom:20px;" readonly value="<?php echo $information['phone_num'] ?>" readonly>
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
                                            <form class="pickup-form" action="<?php echo base_url('client_order/pickup/'.$unique_id);?>" method="post">
						<div class="col-md-6"  style="padding-left:0px;">
							
							<label style="margin-left: 15px;">Activity Type<span style="color:red;">*</span></label>
   
							<div class="radio OpenSans-Reg" style="font-weight:normal;">
									<?php 
										$disabler = "";
										$displayer = "";
										$buttoner = "";
										if($information['activity_typeid']==2)
										{
											$disabler   = "disabled";
											$buttoner = "display:none;";
										}
										else
										{
											 $displayer  = "display:none;"; 
										}
										
									?>
									<select name="activity_type" class="form-control activity-type" <?php echo $disabler; ?> style="font-weight: bold;margin-left: -5px;">
										<?php if($information['activity_typeid']==2): ?>
													<option value="<?php echo $information['activity_typeid']; ?>"><?php echo $information['activity_name'] ?></option>
										<?php else: ?>
													<option value="<?php echo $information['activity_typeid']; ?>"><?php echo $information['activity_name'] ?></option>
													<option value="2">Pick up</option>
										<?php endif; ?>
									</select>
							</div>
							
						</div>
						
						<?php if($information['activity_typeid']==4): ?>
							<div class="form-group col-md-6" style="" id="forptmove_categories">
										 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">New Patient Address <span style="color:red;">*</span></label>
										<input type="text" class="form-control pickup_sub" id="" placeholder="" name="ptmove_address" style="margin-bottom:20px;" value="<?php echo $information['ptmove_street'] ?>" readonly>

										<input type="text" class="form-control pickup_sub" id="" placeholder="Apartment #, Room # , Unit #" name="ptmove_placenum" style="margin-bottom:20px;" value="<?php echo $information['ptmove_placenum'] ?>" readonly>

										<div class="col-md-6" style="padding-left:0px;">
											<input type="text" class="form-control pickup_sub" id="" placeholder="City" name="ptmove_city" style="margin-bottom:20px;" value="<?php echo $information['ptmove_city'] ?>" readonly>
										</div>

										<div class="col-md-6" style="padding-right:0px;">
											<input type="text" class="form-control pickup_sub" id="" placeholder="State / Province" name="ptmove_state" style="margin-bottom:20px;" value="<?php echo $information['ptmove_state'] ?>" readonly>
										</div>

										<div class="col-md-5" style="padding-left:0px;">
											<input type="text" class="form-control pickup_sub" id="" placeholder="Postal Code" name="ptmove_postalcode" style="margin-bottom:20px;" value="<?php echo $information['ptmove_postal'] ?>" readonly>
										</div>
							</div>
						 <?php endif; ?>
						 
						 <?php if($information['activity_typeid']==5): ?>
								<div class="form-group col-md-12" style="" id="forrespite_categories">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Pickup Date <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control datepicker pickup_sub" id="" placeholder="" name="respite_delivery_date" style="margin-bottom:20px;width: 298px;" value="<?php echo $information['respite_pickup_date'] ?>">
						 		</div>

						 		<div class="form-group col-md-6" style="" id="forrespite_categories2">
							   		<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">New Patient Address <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control pickup_sub " id="" placeholder="Street Address" name="respite_address" style="margin-bottom:20px;" value="<?php echo $information['respite_address'] ?>">

							    	<input type="text" class="form-control pickup_sub " id="" placeholder="Apartment #, Room # , Unit #" name="respite_placenum" style="margin-bottom:20px;" value="<?php echo $information['respite_placenum'] ?>">

							    	<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control pickup_sub " id="" placeholder="City" name="respite_city" style="margin-bottom:20px;" value="<?php echo $information['respite_city'] ?>">
							    	</div>

							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  pickup_sub" id="" placeholder="State / Province" name="respite_state" style="margin-bottom:20px;" value="<?php echo $information['respite_state'] ?>">
							    	</div>

							    	<div class="col-md-5" style="padding-left:0px;">
							    		<input type="text" class="form-control pickup_sub " id="" placeholder="Postal Code" name="respite_postalcode" style="margin-bottom:20px;" value="<?php echo $information['respite_postal'] ?>">
							    	</div>
						 		</div>
						 <?php endif; ?>
						 
						<div class="clearfix"></div>
						<div class="col-md-12 pickup-container" style="<?php echo $displayer; ?>">
                                                        <div>
                                                            <div class="clearfix"></div>
                                                            <div class="form-group col-md-6"  id="pickup_categories" style="margin-top: 3%;">
							   	<label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;font-style: italic;font-size: 11px;margin-top:3%;">Pickup reason <span style="color:red;">*</span></label>	
							    	<select class="form-control pickup-reason" name="pickup_sub_cat" <?php echo $disabler; ?>>
                                                                        <option value="">[--Please select reason --]</option>
                                                                        <option value="expired" <?php if(strtolower($pickup_sub)=="expired"): ?> selected="" <?php endif; ?>>Expired</option>
							    		<option value="discharged" <?php if(strtolower($pickup_sub)=="discharged"): ?> selected="" <?php endif; ?>>Discharged</option>
							    		<option value="revoked" <?php if(strtolower($pickup_sub)=="revoked"): ?> selected="" <?php endif; ?>>Revoked</option>
							    	</select>
                                                                <div class="clearfix"></div>
                                                                <br />
                                                                <input type="text" class="form-control datepicker"  <?php echo $disabler; ?> placeholder="Date" value="<?php echo $date; ?>" name="pickup_date" style="margin-bottom:20px;">
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>
							<div style="background-color:#D8D8D8;padding: 18px;margin-top: 16px;border-radius: 5px;" class="OpenSans-Reg col-md-12">
								<h4 class="OpenSans-Reg">Patient Inventory</h4>
								<div class="checkbox" style="<?php echo $buttoner; ?>">
								  <label>
									<input type="checkbox" class="select-all" value="" />
										Select All
								  </label>
								</div>
								<br><br>
								<div class="row">
				                    <div class="col-md-12" style="padding-left:0px;padding-right:0px;">
				                            <?php
				                                $categories_equip = array(1,2);
				                               $includes = array("capped equipment","non-capped equipment");
				                              // printA($orders);
				                            ?>
				                            <?php foreach($orders as $keys=>$equip_orders): ?>
				                                <?php if(in_array(strtolower($keys),$includes)): ?>
				                                    <div class="col-md-6">
				                                        <label><?php echo $keys; ?></label>
				                                        <?php foreach ($equip_orders as $sub_key=>$sub_value): ?>
				                                            <?php 
				                                                
				                                               $checked = "";
				                                               if(in_array($sub_value[0]['equipmentID'],$pickup_equipment))
				                                               {
				                                                   $checked = "checked";
				                                               }
				                                            ?>
				                                            <?php  if(in_array($sub_value[0]['categoryID'],$categories_equip)): ?>
				                                                        <?php if(isset($sub_value['children'])): ?>
				                                                                    <div class="checkbox">
				                                                                        <label>
				                                                                              <input type="checkbox" <?php echo $checked; ?> name="equipments[]" <?php echo $disabler; ?> value="<?php echo $sub_value[0]['equipmentID'] ?>">
				                                                                               <?php echo $sub_key; ?>       
				                                                                        </label>
				                                                                        <ul>
				                                                                            <?php
				                                                                                foreach($sub_value['children'] as $children)
				                                                                                {
				                                                                                    if($children['input_type']=="radio")
				                                                                                    {
				                                                                                        echo "<li>".$children['option_description']." : <span class='text-success'>".trim($children['key_desc']);
				                                                                                        echo "</span></li>";
				                                                                                    }
				                                                                                    else if($children['input_type']=="text")
				                                                                                    {
                                                                                                          echo "<li>".$children['key_desc']." : <span class='text-success'> ".trim($children['equipment_value']);
				                                                                                          echo "</span></li>";
				                                                                                    }
				                                                                                     else if($children['input_type']=="checkbox")
				                                                                                    {
			                                                                                            echo "<li>".$children['option_description']." :<span class='text-success'> ".trim($children['key_desc']);
				                                                                                        echo "</span></li>";
				                                                                                    }

				                                                                                } 
				                                                                            ?>
				                                                                        </ul>
				                                                                    </div>
				                                                        <?php else: ?>
				                                                                    <div class="checkbox">
				                                                                      <label>
				                                                                            <input type="checkbox" <?php echo $checked; ?> name="equipments[]" <?php echo $disabler; ?> value="<?php echo $sub_value[0]['equipmentID'] ?>">
				                                                                             <?php echo $sub_key; ?>       
				                                                                      </label>
				                                                                    </div>
				                                                        <?php endif; ?>
				                                            <?php endif;?>
				                                        <?php endforeach; ?>
				                                    </div>
				                                <?php endif;?>
				                                <?php ?>
				                            <?php endforeach;?>
				                    </div>
								</div>
                                <div class="clearfix"></div>
                                <div class="col-md-12" style="<?php echo $buttoner; ?>">
                                    <button type="submit" class="btn btn-warning pull-right">Save Changes</button>  
                                </div>
                                                                
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
                                                                </form>
						 		
							</div>


				</div>


			

			
			</div>



			<div class="clearfix"></div>
			<div class="col-md-8" style="padding-left:0px; ">
				<h3 class="OpenSans-Reg" style="margin-left: 13px;">Patient Information</h3>
							

							<div class="col-md-6" style="padding-left:0px;">
								<div class="form-group col-md-12">
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Patient Name <span style="color:red;">*</span></label>
							   		 <div class="clearfix"></div>
							    	
									<div class="col-md-6" style="padding-left:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="Last Name" name="patient_lname" value="<?php echo $information['p_fname'] ?>" readonly>
							    		
							    	</div>
									
							    	<div class="col-md-6" style="padding-right:0px;">
							    		<input type="text" class="form-control  " id="" placeholder="First Name" value="<?php echo $information['p_lname'] ?>" readonly>
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

							    	<div class="col-md-6" style="padding-left:0px;">
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

						 		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;margin-left: 15px;"><i>Emergency Contact </i></label>

						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Next of Kin <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control" style="" id="" placeholder="Full Name" readonly name="patient_nextofkin" value="<?php echo $information['p_nextofkin'] ?>" >
						 		</div>


						 		<div class="form-group col-md-12" >
							   		 <label for="exampleInputEmail1" class=" control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Next of Kin Phone Number <span style="color:red;">*</span></label>
							    	<input type="text" class="form-control"  id="" placeholder="Phone Number" readonly name="patient_nextofkinphonenum" value="<?php echo $information['p_nextofkinnum'] ?>" >
						 		</div>

						 	</div>


						 	<div class="clearfix"></div>

						 	<hr>

						 	<div class="col-md-12" >
						 			

						 		<div class="col-md-12 OpenSans-Reg" >
						 			<?php
						 				$categories_equip = array(1,2);
								            foreach($orders as $key=>$value)
								            {
								                    echo "<label>".$key."</label>";
								                    echo "<br /><ol>";
								                    
								                    foreach($value as $sub_key=>$sub_value)
								                    {
								                        if(in_array($sub_value[0]['categoryID'],$categories_equip))
								                        {
								                            if(isset($sub_value['children']))
								                            {
								                                echo "<li>".$sub_key."<br/><ul>";
								                                foreach($sub_value['children'] as $children)
								                                {
								                                    if($children['input_type']=="radio")
								                                    {
								                                        echo "<li>".$children['option_description']." : <span class='text-success'>".trim($children['key_desc']);
								                                        echo "</span></li>";
								                                    }
								                                    else if($children['input_type']=="text")
								                                    {
																		  echo "<li>".$children['key_desc']." : <span class='text-success'> ".trim($children['equipment_value']);
								                                          echo "</span></li>";
								                                    } 
								                                     else if($children['input_type']=="checkbox")
								                                    {
								                                           echo "<li>".$children['option_description']." :<span class='text-success'> ".trim($children['key_desc']);
								                                        echo "</span></li>";
								                                    }
								                                    
								                                } 
																echo "</ul></li>";
								                            }
								                            else
								                            {
								                                 echo "<li>".$sub_key."</li>";
								                            }
								                        }
								                        else
								                        {
								                            echo "<li>".$sub_key." : ".$sub_value[0]['equipment_value']."</li>";
								                        }
								                        echo "<br />";
								                    }
								                    echo "</ol><br />";
								            }
		

						 			?>




						 		</div>	


						 	</div>

							  <!--<div class="form-group col-md-12" style="padding-left:0px;margin-top:-15px;">
								 <label for="exampleInputEmail1" class="control-label OpenSans-reg" style="font-weight:normal;margin-bottom:5px;">Date Delivered <span style="color:red;">*</span></label>
								<input type="text" class="form-control datepicker" id="" placeholder="Date Delivered" name="date_delivered" style="margin-bottom:20px;"  value="">
	
							</div>-->
						 	
					
						 	<div class="col-md-12">
						 		<!-- <button  class="btn btn-default">Done Selecting Equipment</button> -->
								<div class="col-md-12" style="margin-top:20px;">
								<div class="form-group OpenSans-Reg">
									<label>Comment</label>
									<textarea class="form-control" rows="3" name="comment" readonly><?php echo $information['comment'] ?></textarea>
								</div>
						 	</div>



			</div>

			</div>

			

			<div class="clearfix"></div>

			<hr>
			
			<div class="buttons noprint " style="margin-bottom:20px;">
				<button class="btn btn-danger" style="visibility:visible" onclick="window.print()">Print Form</button>
				
				<?php if($information['order_status'] == 'pending') :?>
					<?php if($this->session->userdata('account_type') == 'dme_admin') :?>
						<a href="javascript:void(0)" class="btn_change_status" data-status="pending" data-id="<?php echo $information['uniqueID'] ?>"><button class="btn btn-primary pull-right" style="visibility:visible">Change Status to Active</button></a>
					<?php endif ;?>
				<?php elseif($information['order_status'] == 'active') :?>
					<?php if($this->session->userdata('account_type') == 'dme_admin') :?>
						<a href="javascript:void(0)" class="btn_change_status" data-status="active" data-id="<?php echo $information['uniqueID'] ?>"><button class="btn btn-primary pull-right" style="visibility:visible">Change Status to Confirmed</button></a>
					<?php endif ;?>
				<?php else :?>
					<a href="javascript:void(0)" class="btn_toDefault" data-id="<?php echo $information['uniqueID'] ?>"><button class="btn btn-primary pull-right" style="visibility:hidden"></button></a>
				<?php endif ;?>
				
			</div>
			
<?php endif ;?>
			
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




