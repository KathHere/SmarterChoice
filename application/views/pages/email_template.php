<body class="custom-body" bgcolor="#FFFFFF" style="background-color:#f5f5f5;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;height: 100%;width: 100%!important;">



<!-- HEADER -->

<table class="head-wrap" bgcolor="" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;">

	<tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

		<td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"></td>

		<td class="header container" style="margin: 0 auto!important;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;display: block!important;max-width: 600px!important;clear: both!important;">

			

				<div class="content" style="margin: 0 auto;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;max-width: 600px;display: block;">

					<table bgcolor="" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;">

					<tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

						<td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">
							<img src="http://beta.ahmslv.com/assets/img/smarterchoice1.png" class="custom-img" style="margin: 0 auto;margin-left: 27%;margin-top: 5%;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;max-width: 100%;">
						</td>
						<td align="right" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><h6 class="collapse" style="margin: 0!important;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;, &quot;Helvetica Neue Light&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Lucida Grande&quot;, sans-serif;line-height: 1.1;margin-bottom: 15px;color: #444;font-weight: 900;font-size: 14px;text-transform: uppercase;"></h6></td>

					</tr>

				</table>

				</div>

				

		</td>

		<td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"></td>

	</tr>

</table><!-- /HEADER -->





<!-- BODY -->

<table class="body-wrap" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;">

	<tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

		<td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"></td>

		<td class="container" bgcolor="#FFFFFF" style="margin: 0 auto!important;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;display: block!important;max-width: 600px!important;clear: both!important;">



			<div class="content" style="margin: 0 auto;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;max-width: 600px;display: block;">

			

<?php 
	if(!empty($informations)) :
		$information = $informations[0];

		if($information['type'] == 1)
		{
			$account_type_here = "Company";
		}
		else
		{
			$account_type_here = "Hospice";
		}
?>	

		<?php if(!empty($activity_fields)) :?>
			<?php $fields = $activity_fields[0] ;?>
		<?php endif;?>

			<table style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;">

				<tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

					<td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

						<!-- Callout Panel -->
						<!--
						<div class="callout" style="margin: 0;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;border-radius: 4px;color: #31708f;margin-bottom: 30px;background-color: #7CC9F0!important;">
						
						<p class="OpenSans-reg" style="margin-top: 20px;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">DME Provider : <strong style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Advantage Home Medical Services, Inc.</strong></p>

						<p style="margin-bottom:5px"><strong class="OpenSans-reg" style="font-size: 16px;margin: 0;padding: 0;margin-bottom:5px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Having a hard time in making orders and filling out the forms ?</strong></p><br/>

						<p class="OpenSans-reg" style="margin-top: 20px;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 0px;font-weight: normal;font-size: 14px;line-height: 1.6;">Please call all orders / pickups to : 
					     	<strong style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">(702) 248 - 0056</strong>
					    </p>

					    <p class="OpenSans-reg" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">Please  fax all Patient Order Forms to : 
					      	<strong style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">(702) 889 - 0059</strong>
					    </p>
						


						</div>
						-->
						<!-- /Callout Panel -->
						<?php 
							$queried_data = get_patients_first_order_uniqueID($information['medical_record_id'],$information['organization_id']);
						    if($queried_data['uniqueID'] == $information['uniqueID'])
						    {
						      $returned_result = check_if_new_patient($information['medical_record_id'],$queried_data['uniqueID'],$information['organization_id']);
						    }
						    else
						    {
						      $returned_result = $queried_data;
						    }
						?>
						<h3 style="color: #181A18;font-weight:bold;margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;, &quot;Helvetica Neue Light&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Lucida Grande&quot;, sans-serif;line-height: 1.1;margin-bottom: 15px;font-size: 27px;text-align:center">Order Confirmation<br /> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;font-size: 60%;color: #6f6f6f;line-height: 0;text-transform: none;">WO#<?php echo substr($information['uniqueID'],4,10) ?> <?php if(empty($returned_result)){ ?> N <?php } ?></small></h3>

						<h5 style="color: #85C879;margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;, &quot;Helvetica Neue Light&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Lucida Grande&quot;, sans-serif;line-height: 1.1;margin-bottom: 15px;font-weight: 900;font-size: 17px;"><?php echo $account_type_here; ?> Detail</h5>

						<ul style="list-style-type: none;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $account_type_here; ?> Provider:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['hospice_name'] ?></small></li>

							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $account_type_here; ?> Phone No.:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['phone_num'] ?></small></li>

							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $account_type_here; ?> Staff Member Creating Order:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['who_ordered_lname'] ?></small>, <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['who_ordered_fname'] ?></small></li>

							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $account_type_here; ?> Staff Member Cell No.:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['who_ordered_cpnum'] ?></small></li>

							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $account_type_here; ?> Staff Member Email Address:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['who_ordered_email'] ?></small></li>

						<?php if($information['activity_typeid']==1) :?>
							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Delivery Date:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo date("m/d/Y", strtotime($information['pickup_date'])) ?></small></li>
						<?php endif;?>

							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Patient Residence:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['deliver_to_type'] ?></small></li>

							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;">

									<label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Activity Type:</label>
									<small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['activity_name'] ?></small>
									<div class="col-md-12" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

								    </div>
							</li>


						<?php if($information['activity_typeid']==2): ?>
							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;">
								<label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Pickup Date:<label>
					 			<small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo date("m/d/Y", strtotime($fields['date_pickedup'])) ?></small><br/>
								<label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Pickup Reason:<label>
					 			<small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $fields['pickup_sub'] ?></small><br/>
					 			
					 			<?php if($fields['pickup_respite_address'] != "NA" && $fields['pickup_respite_address'] != "" && $fields['pickup_respite_address'] != NULL) :?>
						 			<label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Respite Address:<label>
						 			<small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $fields['pickup_respite_address'] ?></small><br/>
								<?php endif;?>
								
							</li>
						<?php endif; ?>	


						<?php if($information['activity_typeid']==3): ?>
							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;">
								<label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Exchange Delivery Date:<label>
					 			<small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo date("m/d/Y", strtotime($fields['exchange_date'])) ?></small><br/>
								<label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Reason for Exchange:<label>
					 			<small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $fields['exchange_reason'] ?></small><br/>
							</li>
						<?php endif; ?>


							
						<?php if($information['activity_typeid']==4): ?>
							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;">
								<label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">CUS Move Delivery Date:<label>
					 			<small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo date("m/d/Y", strtotime($fields['ptmove_delivery_date'])) ?></small><br/>
								<label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">CUS Move Address:</label>
								<small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $fields['ptmove_street'] ?>, <?php echo $fields['ptmove_placenum'] ?>, <?php echo $fields['ptmove_city'] ?>, <?php echo $fields['ptmove_state'] ?>, <?php echo $fields['ptmove_postal'] ?></small><br/>
							</li>
						<?php endif; ?>
						 

						<?php if($information['activity_typeid']==5): ?>
					 		<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;">
					 			<label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Respite Delivery Date:<label>
					 			<small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo date("m/d/Y", strtotime($fields['respite_delivery_date'])) ?></small><br/>
					 			<label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Respite Pickup Date:<label>
					 			<small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo date("m/d/Y", strtotime($fields['respite_pickup_date'])) ?></small><br/>

								<label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Respite Address:</label>
								<small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $fields['respite_address'] ?>, <?php echo $fields['respite_placenum'] ?>, <?php echo $fields['respite_city'] ?>, <?php echo $fields['respite_state'] ?>, <?php echo $fields['respite_postal'] ?></small><br/>
							</li>
						<?php endif; ?>

							 
						</ul>


						<hr style="margin-top: 20px;margin-bottom: 20px;border-color: #f5f5f5;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

						<br /><br />

						<ul style="list-style-type: none;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							
							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Patient Medical Record No.:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['medical_record_id'] ?></small></li>


						</ul>

						<br />
						

						<hr style="margin-top: 20px;margin-bottom: 20px;border-color: #f5f5f5;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

						<br /><br />

						<h5 style="color: #85C879;margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;, &quot;Helvetica Neue Light&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Lucida Grande&quot;, sans-serif;line-height: 1.1;margin-bottom: 15px;font-weight: 900;font-size: 17px;">Patient Profile</h5>



						<ul style="list-style-type: none;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">

							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Patient Name:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['p_lname'] ?></small>, <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"> <?php echo $information['p_fname'] ?></small></li>

							<?php 
								$gender = "";
								if($information['relationship_gender'] == 1)
								{
									$gender = "Male";
								} 
								else
								{
									$gender = "Female";
								}
							?>
							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Gender:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $gender ?></small></li>

							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Height & Weight:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['p_height'] ?></small>, <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['p_weight'] ?></small></li>


							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Phone Number:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['p_phonenum'] ?></small></li>



							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Alt. Phone Number:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['p_altphonenum'] ?></small></li>



							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Patient Address:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['p_street'] ?>, <?php echo $information['p_placenum'] ?>, <?php echo $information['p_city'] ?> ,<?php echo $information['p_state'] ?>, <?php echo $information['p_postalcode'] ?></small></li>



							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Next of Kin:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['p_nextofkin'] ?></small></li>

							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Relationship:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['p_relationship'] ?></small></li>

							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Next of Kin Phone Number:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['p_nextofkinnum'] ?></small></li>

						</ul>

						

						<ul style="list-style-type: none;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">

							<li style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-left: 5px;list-style-position: inside;"><label style="font-weight: bold;margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">Delivery Instructions:</label> <small style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><?php echo $information['comment'] ?></small></li>
						</ul>

						<strong>Item(s) Ordered<strong>
						<ul>
							<?php

								$categories_equip = array(1,2,3);

									foreach($orders as $key=>$value)
									{

											echo "<strong><label>".$key."</label></strong>";

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
						</ul>
						

						<!--<a class="btn" href="http://ahmslv.com/ahmslv/user/user_login" style="margin: 0;padding: 10px 16px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;color: #FFF;text-decoration: none;background-color: #3276b1;font-weight: bold;margin-right: 10px;text-align: center;border-radius: 4px;cursor: pointer;display: inline-block;" target="_blank">View form from our Website</a>-->

												

						<br style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

						<br style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">							 

												

						<!-- social & contact -->

						<table class="social" width="100%" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;background-color: #ebebeb;width: 100%;">

							<tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

								<td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"> 

									

									<!--- column 1 -->

									<!--<table align="left" class="column" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 280px;float: left;min-width: 279px;">

										<tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

											<td style="margin: 0;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">				

												

												<h5 class="" style="margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;, &quot;Helvetica Neue Light&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Lucida Grande&quot;, sans-serif;line-height: 1.1;margin-bottom: 15px;color: #000;font-weight: 900;font-size: 17px;">Connect with Us:</h5>

												<p class="" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;"><a href="#" class="soc-btn fb" style="margin: 0;padding: 3px 7px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;color: #FFF;font-size: 12px;margin-bottom: 10px;text-decoration: none;font-weight: bold;display: block;text-align: center;background-color: #3B5998!important;">Facebook</a> <a href="#" class="soc-btn tw" style="margin: 0;padding: 3px 7px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;color: #FFF;font-size: 12px;margin-bottom: 10px;text-decoration: none;font-weight: bold;display: block;text-align: center;background-color: #1daced!important;">Twitter</a> <a href="#" class="soc-btn gp" style="margin: 0;padding: 3px 7px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;color: #FFF;font-size: 12px;margin-bottom: 10px;text-decoration: none;font-weight: bold;display: block;text-align: center;background-color: #DB4A39!important;">Google+</a></p>

						 

												

											</td>

										</tr>

									</table>--> <!-- /column 1 -->	

									

									<!--- column 2 -->

									<table align="left" class="column" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 280px;float: left;min-width: 279px;">

										<tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

											<td style="margin: 0;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">				

																			

												<h5 class="" style="margin: 0;padding: 0;font-family: &quot;HelveticaNeue-Light&quot;, &quot;Helvetica Neue Light&quot;, &quot;Helvetica Neue&quot;, Helvetica, Arial, &quot;Lucida Grande&quot;, sans-serif;line-height: 1.1;margin-bottom: 15px;color: #000;font-weight: 900;font-size: 17px;">Contact Info:</h5>												

												<p style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">Phone: <strong style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"> (702) 248-0056</strong><br style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

												Email: <strong style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"><a href="emailto:hseldon@trantor.com" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;color: #2BA6CB;">orders@ahmslv.com</a></strong></p>
												
									

											</td> 

										</tr> 

									</table><!-- /column 2 -->

									

									<span class="clear" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;display: block;clear: both;"></span>	

									

								</td>

							</tr>

						</table><!-- /social & contact -->

					

					

					</td>

				</tr>

			</table>

			</div>

									

		</td>

		<td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"></td>

	</tr>

</table><!-- /BODY -->

<?php endif ?>





<!-- FOOTER -->

<table class="footer-wrap" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;clear: both!important;">

	<tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

		<td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"></td>

		<td class="container" style="margin: 0 auto!important;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;display: block!important;max-width: 600px!important;clear: both!important;">

			

				<!-- content -->

				<div class="content" style="margin: 0 auto;padding: 15px;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;max-width: 600px;display: block;">

				<table style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;width: 100%;">

				<tr style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

					<td align="center" style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;">

						<p style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 14px;line-height: 1.6;">
							©2015 Advantage Home Medical Services Inc. All rights reserved.
						</p>
						
						<p style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;margin-bottom: 10px;font-weight: normal;font-size: 12px;line-height: 1.6;text-align:justify;">The contents of this e-mail message and any attachments are confidential and are intended solely for addressee. The information may also be legally privileged. This transmission is sent in trust, for the sole purpose of delivery to the intended recipient. If you have received this transmission in error, any use, reproduction or dissemination of this transmission is strictly prohibited. If you are not the intended recipient, please immediately notify the sender by reply e-mail or phone and delete this message and its attachments, if any.</p>

					</td>

				</tr>

			</table>

			</div><!-- /content -->

				

		</td>

		<td style="margin: 0;padding: 0;font-family: &quot;Helvetica Neue&quot;, &quot;Helvetica&quot;, Helvetica, Arial, sans-serif;"></td>

	</tr>

</table><!-- /FOOTER --></body>