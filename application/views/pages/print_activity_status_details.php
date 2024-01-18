<style type="text/css">
	.badge
	{
		background-color: #7E7F80;
	}	
	ul > li.list-group-item
	{
		border:0px !important;
	}
	.badge
	{
		background-color: #fff !important;
		color: #000 !important;
		font-size: 16px;
	}
	@media (max-width: 580px){
		.panel-report .col-xxs-12{
			width:100%;
		}
	}

	@media print{
		.whole-container
	    {
	    	margin-top: -100px;
	    }
	    .panel-default
	    {
	    	border:none !important;
	    }
	    .activity_status_name_div
	    {
	    	margin-top: -12px !important;
	    }
	    .viewed_current_date_activity_status_div
	    {
	    	margin-top: -12px !important;
	    }
	    .activity_status_report_table_div
	    {
	    	margin-top: 12px !important;
	    }
	    .total_count_div
	    {
	    	margin-top: -10px !important;
	    }
	}

</style>

<div class="wrapper-md whole-container">
  	<div class="panel panel-default">
    	<div class="panel-body">

			<div class="page" ng-controller="FlotChartDemoCtrl">
				<div class="wrapper-md">

					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:14px;">
							<?php 
								echo date('h:i A');
							?>
						</div>
						<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;font-size: 16px;font-weight: 600;">
							<?php 
								if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user")
        						{
        							if(!empty($hospice_name))
									{
										echo $hospice_name['hospice_name'];
									}
									else
									{
										echo "Advantage Home Medical Services";
									}
								}
								else
								{
									$hospice = $this->session->userdata('group_id');
									$hospice_info = get_hospice_name($hospice);
									
									echo $hospice_info['hospice_name'];
								}
							?>
						</div>
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

						</div>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">
							<?php 
								$current_date = date('Y-m-d');
								echo date("m/d/Y", strtotime($current_date))
							?>
						</div>
						<div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 activity_status_name_div" style="text-align:center;margin-top:-3px;">
							<?php 
								echo $status_name;
							?>
						</div>
						<div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

						</div>
							
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 viewed_current_date_activity_status_div" style="text-align:center; margin-top:4px;">
						<span id="viewed_current_date_residence">
						<?php 
							$current_date = date('Y-m-d');
							$new_filter_from = explode("-", $filter_from);
							$new_filter_to = explode("-", $filter_to);
						
							if($filter_from == 0 || $filter_to == 0)
							{
								echo date("F d, Y", strtotime($current_date));
							}
							else
							{
								$separated_from = explode("-", $filter_from);
								$separated_to = explode("-", $filter_to);

								if($separated_from[1] == 1)
				  				{
				  					$month_name = "January";
				  				}
				  				else if($separated_from[1] == 2)
				  				{
				  					$month_name = "February";
				  				}
				  				else if($separated_from[1] == 3)
				  				{
				  					$month_name = "March";
				  				}
				  				else if($separated_from[1] == 4)
				  				{
				  					$month_name = "April";
				  				}
				  				else if($separated_from[1] == 5)
				  				{
				  					$month_name = "May";
				  				}
				  				else if($separated_from[1] == 6)
				  				{
				  					$month_name = "June";
				  				}
				  				else if($separated_from[1] == 7)
				  				{
				  					$month_name = "July";
				  				}
				  				else if($separated_from[1] == 8)
				  				{
				  					$month_name = "August";
				  				}
				  				else if($separated_from[1] == 9)
				  				{
				  					$month_name = "September";
				  				}
				  				else if($separated_from[1] == 10)
				  				{
				  					$month_name = "October";
				  				}
				  				else if($separated_from[1] == 11)
				  				{
				  					$month_name = "November";
				  				}
				  				else if($separated_from[1] == 12)
				  				{
				  					$month_name = "December";
				  				}

				  				if($separated_to[1] == 1)
				  				{
				  					$month_name_to = "January";
				  				}
				  				else if($separated_to[1] == 2)
				  				{
				  					$month_name_to = "February";
				  				}
				  				else if($separated_to[1] == 3)
				  				{
				  					$month_name_to = "March";
				  				}
				  				else if($separated_to[1] == 4)
				  				{
				  					$month_name_to = "April";
				  				}
				  				else if($separated_to[1] == 5)
				  				{
				  					$month_name_to = "May";
				  				}
				  				else if($separated_to[1] == 6)
				  				{
				  					$month_name_to = "June";
				  				}
				  				else if($separated_to[1] == 7)
				  				{
				  					$month_name_to = "July";
				  				}
				  				else if($separated_to[1] == 8)
				  				{
				  					$month_name_to = "August";
				  				}
				  				else if($separated_to[1] == 9)
				  				{
				  					$month_name_to = "September";
				  				}
				  				else if($separated_to[1] == 10)
				  				{
				  					$month_name_to = "October";
				  				}
				  				else if($separated_to[1] == 11)
				  				{
				  					$month_name_to = "November";
				  				}
				  				else if($separated_to[1] == 12)
				  				{
				  					$month_name_to = "December";
				  				}

								if(($separated_from[0] == $separated_to[0]) && ($separated_from[1] == $separated_to[1]) && ($separated_from[2] == $separated_to[2]))
								{
									echo $month_name." ".$separated_from[2].", ".$separated_from[0];
								}
								else if(($separated_from[0] == $separated_to[0]) && ($separated_from[1] == $separated_to[1]) && ($separated_from[2] != $separated_to[2]))
								{
									echo $month_name." ".$separated_from[2]." - ".$separated_to[2].", ".$separated_from[0];
								}
								else
								{
									echo $month_name." ".$separated_from[2].", ".$separated_from[0]." - ".$month_name_to." ".$separated_to[2].", ".$separated_to[0]; 
								}
							}
						?>
						</span>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-12 activity_status_report_table_div" style="margin-top:30px;">
						<?php 
							if($sign_here == 1){
						?>
								<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">
						  	
								  	<thead style="background-color:rgba(97, 101, 115, 0.05);">
								    	<tr>
								    		<th style="width: 65%">Customer Name</th>
								      		<th style="width: 35%">MR#</th>
								    	</tr>
								    </thead>
								    <tbody class="activity_status_tbody">
								    <?php 
								    	if(!empty($patient_list)){
								    		foreach($patient_list as $row){
							    	?>
										    	<tr>
										    		<td> <a class=""> <?php echo $row['p_lname']; ?>, <?php echo $row['p_fname']; ?> </a></td>
										    		<td> <a class=""> <?php echo $row['medical_record_id']; ?> </a> </td>
										    	</tr>
							    	<?php 
								    		}
										}else{
									?>
											<tr>
											 	<td colspan="2" style="text-align:center;"> No Customer. </td>
											</tr>
									<?php 
										}
									?>
								    </tbody>
								</table>	
						<?php 
							}else{
								$counter_expired = 0;
								$counter_discharged = 0;
								$counter_not_needed = 0;
								$counter_revoked = 0;
						?>
								<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">
								  	
								  	<thead style="background-color:rgba(97, 101, 115, 0.05);">
								    	<tr>
								    		<th style="width: 45%">Customer Name</th>
								      		<th style="width: 27%">MR#</th>
								      		<?php 
								      			if($sign_here == 3){
								      		?>
								      				<th style="width: 28%">Item(s)</th>
								      		<?php 
								      			}else if($sign_here == 2){
								      		?>
								      				<th style="width: 28%">Pickup Reason</th>
								      		<?php 
								      			}
								      		?>
								    	</tr>
								    </thead>
								    <tbody class="activity_status_tbody">
								    <?php 
								    	if(!empty($patient_list)){
								    		foreach($patient_list as $row){
							    	?>
										    	<tr>
										    		<td> <a class=""> <?php echo $row['p_lname']; ?>, <?php echo $row['p_fname']; ?> </a></td>
										    		<td> <a class=""> <?php echo $row['medical_record_id']; ?> </a> </td>
										    		<td style="color:#374045;">
										    		<?php 
										    			if($sign_here == 2){
										    				if($row['pickup_sub'] == "not needed")
										    				{
										    					echo " Not Needed";
																$counter_not_needed++;
										    				}
										    				else if($row['pickup_sub'] == "expired")
										    				{
										    					echo " Expired";
										    					$counter_expired++;						
										    				}
										    				else if($row['pickup_sub'] == "discharged")
										    				{
										    					echo " Discharged";
										    					$counter_discharged++;
										    				}
										    				else if($row['pickup_sub'] == "revoked")
										    				{
										    					echo " Revoked";
										    					$counter_revoked++;
										    				}
										    			}
										    			else if($sign_here == 3)
										    			{
										    				$count_here = 1;
											    			foreach ($query_result as $key_inside => $value_inside) {
											    				if($row['patientID'] == $value_inside['patientID'] && $row['uniqueID'] == $value_inside['uniqueID']){
											    					if($value_inside['parentID'] == 0){
											    						// Patient Lift Sling, Oxygen Concentrators, Oxygen E Portable System, Oxygen Liquid Portable
											    						if($value_inside['equipmentID'] == 316 || $value_inside['equipmentID'] == 325 || $value_inside['equipmentID'] == 334 || $value_inside['equipmentID'] == 343 || $value_inside['equipmentID'] == 174 || $value_inside['equipmentID'] == 176 || $value_inside['equipmentID'] == 179)
											    						{
											    		 					echo $value_inside['key_desc']." ";
											    		 				}
											    		 				else
											    		 				{
											    		 					$subequipment_id = get_subequipment_id($value_inside['equipmentID']);
											    		 					if($subequipment_id)
										                                  	{
											                                    $count = 0;
											                                    $patient_lift_sling_count = 0;
											                                    $high_low_full_electric_hospital_bed_count = 0;
											                                    $my_count_sign = 0;
											                                    $my_first_sign = 0;
											                                    $my_second_sign = 0;
										                                    
										                                    	foreach ($subequipment_id as $key) {
										                                      		$value = get_equal_subequipment_order($key['equipmentID'], $value_inside['uniqueID']);
										                                      		if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
										                                      		{
												                                        if(empty($value))
												                                        {
												                                          $my_first_sign = 1;
												                                        }
										                                      		}
										                                      		if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
										                                      		{
												                                        if(empty($value))
												                                        {
												                                          $my_second_sign = 1;
												                                        }
										                                      		}
										                                      		if($my_second_sign == 1 && $my_first_sign == 1)
										                                      		{		
										                                        		$my_count_sign = 1;
										                                      		}
										                                      		if($value)
										                                      		{
										                                        		$count++;
											                                        	//full electric hospital bed equipment
						                                        						if($value_inside['equipmentID'] == 55 || $value_inside['equipmentID'] == 20){
					                                        								echo "Full Electric ".$value_inside['key_desc']." With ".$key['key_desc']." ";
											    		 								} 
												                                        //Hi-Low Full Electric Hospital Bed equipment
												                                        else if($value_inside['equipmentID'] == 19 || $value_inside['equipmentID'] == 398)
												                                        {
												                                        	echo $value_inside['key_desc']." With ".$key['key_desc']." ";
												                                        }
												                                        //Patient Lift with Sling
												                                        else if($value_inside['equipmentID'] == 56 || $value_inside['equipmentID'] == 21)
												                                        {
												                                        	echo "Patient Lift With ".$key['key_desc']." ";
												                                        }
												                                        //Patient Lift Electric with Sling
												                                        else if($value_inside['equipmentID'] == 353)
												                                        {
												                                        	echo "Patient Lift Electric With ".$key['key_desc']." ";
												                                        }
												                                        //Patient Lift Sling
												                                        else if($value_inside['equipmentID'] == 196)
												                                        {
												                                        	echo $key['key_desc']." ";
												                                        }
												                                        //(54 & 17) Geri Chair || (66 & 39) Shower Chair
												                                        else if($value_inside['equipmentID'] == 54 || $value_inside['equipmentID'] == 17 || $value_inside['equipmentID'] == 66 || $value_inside['equipmentID'] == 39)
												                                        { 
												                                        	echo $value_inside['key_desc']." ".$key['key_desc']." ";
												                                        }
												                                        //Oxygen Cylinder Rack
												                                        else if($value_inside['equipmentID'] == 32 || $value_inside['equipmentID'] == 393)
												                                        {
												                                        	echo "Oxygen ".$key['key_desc']." ";
												                                        	break;
												                                        }
											                                        	//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining 
												                                        else if($value_inside['equipmentID'] == 49 || $value_inside['equipmentID'] == 71 || $value_inside['equipmentID'] == 269 || $value_inside['equipmentID'] == 64) 
												                                        {         
											                                          		if($my_count_sign == 0)
												                                          	{    
													                                            //wheelchair & wheelchair reclining
													                                            if($count == 1)
													                                            { 
													                                            	if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
													                                              	{
														                                                $key['key_desc'] = '16" Narrow';
													                                              	} 
													                                              	else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85) 
													                                              	{
														                                                $key['key_desc'] = '18" Standard';
													                                              	} 
													                                              	else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392) 
													                                              	{
														                                                $key['key_desc'] = '20" Wide';
													                                              	} 
													                                              	else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127) 
													                                              	{
														                                                $key['key_desc'] = '22" Extra Wide';
													                                              	} 
													                                              	else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128) 
													                                              	{
														                                                $key['key_desc'] = '24" Bariatric';
													                                              	}
													                                              	echo $key['key_desc']." ".$value_inside['key_desc']." ";
												                                              	} 
													                                            else 
													                                            {
													                                              	echo " With ".$key['key_desc']." ";
													                                            }
													                                        }
													                                        else
													                                        {
													                                        	echo ' 20" Wide '.$value_inside['key_desc'];
													                                            if($key['equipmentID'] == 86 || $key['equipmentID'] == 272) 
													                                            {
													                                                echo " With Elevating Legrests ";
													                                                break;
													                                            } 
													                                            else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273) 
													                                            {
													                                                echo " With Footrests ";
													                                                break;
													                                            }
												                                            }
												                                        } 
												                                        else if($value_inside['equipmentID'] == 30)
												                                        {
												                                          	if(strtotime($value_inside['date_ordered']) >= 1464073200 && strtotime($value_inside['pickup_date']) >= 1464073200)
												                                          	{
												                                            	if($count == 3)
												                                            	{
												                                            		echo $value_inside['key_desc']." With ".$key['key_desc']." ";
												                                            	}
												                                          	}
												                                          	else
												                                          	{
												                                          		echo $value_inside['key_desc']." ";
												                                          	}
												                                        }
												                                        //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
												                                        else if($value_inside['equipmentID'] == 306 || $value_inside['equipmentID'] == 309 || $value_inside['equipmentID'] == 313 || $value_inside['equipmentID'] == 40 || $value_inside['equipmentID'] == 32  || $value_inside['equipmentID'] == 393 || $value_inside['equipmentID'] == 16 || $value_inside['equipmentID'] == 67 || $value_inside['equipmentID'] == 4 || $value_inside['equipmentID'] == 36)
												                                        { 
												                                        	echo $value_inside['key_desc'];
												                                        	$samp =  get_misc_item_description($value_inside['equipmentID'],$value_inside['uniqueID']);
										                                                	echo " (".$samp.") ";
					                                          							
					                                          								break;
												                                        } 
												                                        else if($value_inside['equipmentID'] == 62 || $value_inside['equipmentID'] == 31)
												                                        {
												                                            $samp_conserving_device =  get_oxygen_conserving_device($value_inside['equipmentID'],$value_inside['uniqueID']);
												                                            if($count == 1)
												                                            {
												                                            	echo $value_inside['key_desc']." ".$samp_conserving_device." ";
												                                            }
														                                }
														                                else if($value_inside['equipmentID'] == 282)
														                                {
														                                  
														                                }
														                                //equipments that has no subequipment but gets inside the $value if statement
														                                else if($value_inside['equipmentID'] == 14)
														                                {
														                                	echo $value_inside['key_desc']." ";
														                                } 
												                                        else 
												                                        {
											                                          		if($value_inside['categoryID'] == 1)
												                                          	{
													                                            $non_capped_copy = get_non_capped_copy($value_inside['equipmentID']);
													                                            if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
													                                            {
													                                            	echo $value_inside['key_desc']." ";
													                                            	break;
													                                            }
													                                            else if($non_capped_copy['noncapped_reference'] == 14)
													                                            {
													                                            	echo $value_inside['key_desc']." ";
													                                            }
													                                            else if($non_capped_copy['noncapped_reference'] == 282)
													                                            {
													                                              $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($value_inside['equipmentID'],$value_inside['uniqueID']);
													                                              echo $value_inside['key_desc']." With ".$samp_hospital_bed_extra_long." ";
													                                              break;
													                                            }
													                                            else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
													                                            {
													                                            	echo "Patient Lift With ".$key['key_desc']." ";
													                                            }
													                                            else if($non_capped_copy['noncapped_reference'] == 353)
													                                            {
													                                            	echo "Patient Lift Electric With ".$key['key_desc']." ";
													                                            }
													                                            else
													                                            {
													                                            	echo $value_inside['key_desc']." ";
													                                            }
												                                            }
												                                            else
												                                            {
												                                            	echo $value_inside['key_desc']." ";
												                                            }     
												                                        }
												                                    } //end of $value
												                                    // for Oxygen E cylinder do not remove as it will cause errors
												                                    else if($value_inside['equipmentID'] == 62 || $value_inside['equipmentID'] == 31)
												                                    {
												                                        break;
												                                    }
												                                    else if($value_inside['equipmentID'] == 32 || $value_inside['equipmentID'] == 393)
												                                    {

												                                    }
												                                    else if($value_inside['equipmentID'] == 282)
												                                    {
												                                        $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($value_inside['equipmentID'],$value_inside['uniqueID']);
												                                        echo $value_inside['key_desc']." With ".$samp_hospital_bed_extra_long." ";
												                                    	break;
												                                    }
												                                    //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
												                                    else if ($value_inside['equipmentID'] == 10 || $value_inside['equipmentID'] == 36 || $value_inside['equipmentID'] == 31 || $value_inside['equipmentID'] == 32 || $value_inside['equipmentID'] == 393 || $value_inside['equipmentID'] == 282 || $value_inside['equipmentID'] == 286 || $value_inside['equipmentID'] == 62 || $value_inside['equipmentID'] == 313 || $value_inside['equipmentID'] == 309 || $value_inside['equipmentID'] == 306 || $value_inside['equipmentID'] == 4)
												                                    {
												                                    	echo $value_inside['key_desc']." ";
												                                    	break;
												                                    } //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
												                                    else if($value_inside['equipmentID'] == 11 || $value_inside['equipmentID'] == 178 || $value_inside['equipmentID'] == 9 || $value_inside['equipmentID'] == 149) 
												                                    { 
												                                    	echo $value_inside['key_desc']." ";
												                                   	} 
											                                        //for equipments with subequipment but does not fall in $value 
											                                        else if($value_inside['equipmentID'] == 54 || $value_inside['equipmentID'] == 17 || $value_inside['equipmentID'] == 174 || $value_inside['equipmentID'] == 398 || $value_inside['equipmentID'] == 282 || $value_inside['equipmentID'] == 196 || $value_inside['equipmentID'] == 353 || $value_inside['equipmentID'] == 56 || $value_inside['equipmentID'] == 21 || $value_inside['equipmentID'] == 176 || $value_inside['equipmentID'] == 179 ||  $value_inside['equipmentID'] == 30 || $value_inside['equipmentID'] == 40 || $value_inside['equipmentID'] == 67 || $value_inside['equipmentID'] == 39 || $value_inside['equipmentID'] == 66 || $value_inside['equipmentID'] == 19 || $value_inside['equipmentID'] == 269 || $value_inside['equipmentID'] == 49 || $value_inside['equipmentID'] == 20 || $value_inside['equipmentID'] == 55 || $value_inside['equipmentID'] == 71 || $value_inside['equipmentID'] == 64)   
											                                        { 
											                                        	if($value_inside['equipmentID'] == 196 || $value_inside['equipmentID'] == 56 || $value_inside['equipmentID'] == 21 || $value_inside['equipmentID'] == 353)
											                                        	{
										                                          			$patient_lift_sling_count++;
										                                          			if($patient_lift_sling_count == 6)
										                                          			{
										                                          				echo $value_inside['key_desc']." ";
										                                          			}
					                                        							} 
					                                        							else if($value_inside['equipmentID'] == 398)
					                                        							{
					                                          								$high_low_full_electric_hospital_bed_count++;
					                                          								if($high_low_full_electric_hospital_bed_count == 2){
					                                          									echo $value_inside['key_desc']." ";
					                                          								}
										                                        		} 
										                                      		} 
										                                      		else 
										                                      		{ 
												                                        if($value_inside['categoryID'] == 1)
												                                        {
										                                          			$non_capped_copy = get_non_capped_copy($value_inside['equipmentID']);
										                                          			if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
										                                          			{
										                                          				echo $value_inside['key_desc']." ";
										                                          				break;
													                                        }
													                                        else if($non_capped_copy['noncapped_reference'] == 14)
													                                        {
													                                        	echo $value_inside['key_desc']." ";
													                                        }
													                                        else
													                                        {

													                                        }
													                                    }
													                                    else
													                                    {
													                                    	echo $value_inside['key_desc']." ";
													                                    }
												                                    }
												                                }
												                            } 
												                            else 
												                            { 
										                                    	echo $value_inside['key_desc']." ";
													    					}
													    				}

													    				//quantity base on the categories
											                            //there are 3 categories
											                            // capped,non-capped,disposable
											                            $quantity = 1;
											                            if($value_inside['categoryID']!=3) //cappped=1, noncapped=2
											                            {
											                              //if noncapped get children quantities
											                              if($value_inside['categoryID']==2)
											                              {
											                                if($value_inside['parentID']==0 AND $value_inside['equipment_value']>1)
											                                {
											                                  $quantity = $value_inside['equipment_value'];
											                                }
											                                else
											                                {
											                                  if($value_inside['equipmentID'] == 4 || $value_inside['equipmentID'] == 9 || $value_inside['equipmentID'] == 176 || $value_inside['equipmentID'] == 30)
											                                  {
											                                    if(empty($value_inside['equipment_value']))
											                                    {
											                                      $temp = get_noncapped_quantity($value_inside['equipmentID'], $value_inside['uniqueID']);
											                                      $quantity = ($temp>0)? $temp : 1;
											                                    }
											                                    else
											                                    {
											                                      $quantity = $value_inside['equipment_value'];
											                                    }
											                                  } else {
											                                    $temp = get_noncapped_quantity($value_inside['equipmentID'], $value_inside['uniqueID']);
											                                    $quantity = ($temp>0)? $temp : 1;
											                                  }
											                                }
											                              }
											                              else //capped items
											                              {
											                                $non_capped_copy = get_non_capped_copy($value_inside['equipmentID']);
											                                //if the equipment is miscellaneous capped item
											                                if($value_inside['equipmentID'] == 313 || $value_inside['equipmentID'] == 206)
											                                {
											                                  $temp = get_noncapped_quantity($value_inside['equipmentID'], $value_inside['uniqueID']);
											                                  $quantity = ($temp>0)? $temp : 1;
											                                }else if($non_capped_copy['noncapped_reference'] == 14){
											                                  $temp = get_noncapped_quantity($value_inside['equipmentID'], $value_inside['uniqueID']);
											                                  $quantity = ($temp>0)? $temp : 1;
											                                }else {
											                                  $quantity = ($value_inside['equipment_value']>0)? $value_inside['equipment_value'] : 1;
											                                }
											                              }
											                            }
											                            else //disposable items
											                            {
											                              if($value_inside['equipment_value'] > 1)
											                              {
											                                $quantity = $value_inside['equipment_value'];
											                              }
											                              else
											                              {
											                                $quantity = (get_disposable_quantity($value_inside['equipmentID'], $value_inside['uniqueID'])>0)? get_disposable_quantity($value_inside['equipmentID'], $value_inside['uniqueID']) : 0;
											                                if($value_inside['equipment_value'] == 0)
											                                {
											                                  $quantity = 0;
											                                }

											                                if(empty($value_inside['equipment_value']))
											                                {
											                                  $quantity = get_disposable_quantity($value_inside['equipmentID'],$value_inside['uniqueID']);
											                                  if(empty($quantity))
											                                  {
											                                    $quantity = 1;
											                                  }
											                                }
											                              }
											                            }

											                            echo $quantity."ea ";
													    				if($count_here < $row['item_count'])
													    				{
													    					echo "<br />";
													    					$count_here++;
													    				}
													    			}
													    		}
													    	}
										    			}
									    			?>
									    			</td>
										    	</tr>
							    	<?php 
								    		}
										}else{
									?>
											<tr>
											 	<td colspan="3" style="text-align:center;"> No Patient. </td>
											</tr>
									<?php 
										}
									?>
								    </tbody>
								</table>
						<?php 
							}
						?>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-12 total_count_div" style="margin-top:10px;margin-bottom:10px;">
					<?php 
			    		if($sign_here == 2){
			    	?>
					    	<div class="col-xxs-6 col-xs-2 col-sm-2 col-md-2" style="margin-top:-2px;">
					    		Expired: <?php echo $counter_expired; ?>
							</div>
							<div class="col-xxs-6 col-xs-3 col-sm-3 col-md-3" style="margin-top:-2px;">
								Discharged: <?php echo $counter_discharged; ?>
							</div>
							<div class="col-xxs-6 col-xs-3 col-sm-3 col-md-3" style="margin-top:-2px;">
								Not Needed: <?php echo $counter_not_needed; ?>
							</div>
							<div class="col-xxs-6 col-xs-2 col-sm-2 col-md-2" style="margin-top:-2px;">
								Revoked: <?php echo $counter_revoked; ?>
							</div>
							<div class="col-xxs-12 col-xs-2 col-sm-2 col-md-2" style="text-align:right;">
								TOTAL: <span id="total_patient_list_queried_residence"><?php echo count($patient_list); ?></span>
							</div>
					<?php 
						}else{
					?>
							<div class="col-xxs-12 col-xs-10 col-sm-10 col-md-10">
							</div>
							<div class="col-xxs-12 col-xs-2 col-sm-2 col-md-2">
								TOTAL: <span id="total_patient_list_queried_residence"><?php echo count($patient_list); ?></span>
							</div>
					<?php 
						}
					?>
				    </div>

				</div>
			</div>
		</div>
	</div>
</div>

<div class="bg-light lter wrapper-md hidden-print" style="margin-top:-40px;">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

