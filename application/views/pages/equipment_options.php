
	
	<?php if($parent_equipmentID == 181 || $parent_equipmentID == 182): ?>
		<?php if(!empty($patient_weight)) :?>
			<?php foreach($patient_weight as $weight) :?>
				<ul class="list-unstyled">
					<li style="font-weight:bold">Patient Weight</li>
					<li><p class='text-success'><?php echo $weight['patient_weight'] ?> </p></li>
				</ul>
			<?php endforeach;?>
		<?php endif;?>
	<?php endif;?>


	<?php if($parent_equipmentID == 11 || $parent_equipmentID == 170): ?>
		<?php if(!empty($lotnumbers)) :?>
			<?php foreach($lotnumbers as $lot) :?>
				<ul class="list-unstyled">
					<li style="font-weight:bold">Lot Number</li>
					<li><p class='text-success'><?php echo $lot['lot_number_content'] ?> </p></li>
				</ul>
			<?php endforeach;?>
		<?php endif;?>
	<?php endif;?>


	<?php if($parent_equipmentID == 306 || $parent_equipmentID == 309 || $parent_equipmentID == 313): ?>
		<?php if(!empty($options)) :?>
			<?php foreach($options as $option) :?>
				<ul class="list-unstyled">
					<li style="font-weight:bold"><?php echo $option['key_desc'] ?></li>
					<li><p class='text-success'><?php echo $option['equipment_value'] ?> </p></li>
				</ul>
			<?php endforeach;?>
		<?php endif;?>
	<?php endif;?>


<?php 
	if($parent_equipmentID != 11 && $parent_equipmentID != 170 && $parent_equipmentID != 181 && $parent_equipmentID != 182 && $parent_equipmentID != 306 && $parent_equipmentID != 309 && $parent_equipmentID != 313): 
		if(!empty($options)) : 
			if($parent_equipmentID == 316 || $parent_equipmentID == 334)
			{
?>
				<ul class="list-unstyled">
					<li style="font-weight:bold">Oxygen Concentrator Type</li>
					<li><p class='text-success'>5 Liter</p></li>
				</ul>
<?php
			}
			else if($parent_equipmentID == 325 || $parent_equipmentID == 343)
			{
?>
				<ul class="list-unstyled">
					<li style="font-weight:bold">Oxygen Concentrator Type</li>
					<li><p class='text-success'>10 Liter</p></li>
				</ul>
<?php
			}
			foreach($options as $option) :
?>
				<ul class="list-unstyled">
					<?php 
						if($parent_equipmentID != 11 || $parent_equipmentID != 170):
							$non_capped_copy = get_non_capped_copy($parent_equipmentID);
							if($option['equipmentID'] != 283 && $option['equipmentID'] != 284 && $option['equipmentID'] != 285 && $non_capped_copy['noncapped_reference'] != 282)
							{
				 	?>
								<li style="font-weight:bold"><?php echo $option['option_description'] ?></li>
					
					<?php 
								if($option['key_desc'] == "Liter Flow") :
					?>
									<li style="font-weight:bold"><?php echo $option['key_desc'] ?></li>
					<?php 
								else:
									if($option['equipmentID'] != 197 && $option['equipmentID'] != 198 && $option['equipmentID'] != 199 && $option['equipmentID'] != 200 && $option['equipmentID'] != 283 && $option['equipmentID'] != 284 && $option['equipmentID'] != 285):
					?>
										<li><p class='text-success'><?php echo $option['key_desc'] ?> </p></li>
					<?php 
									endif;
								endif;
								if($option['key_desc'] == "Liter Flow") :
					?>
									<li><p class='text-success'><?php echo $option['equipment_value'] ?> LPM</p></li>
					<?php
							 	else:
							 		$non_capped_copy = get_non_capped_copy($parent_equipmentID);
									if($parent_equipmentID == 9 || $parent_equipmentID == 4 || $non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9) :
					?>
										<li><p class='font-weight:bold'><?php echo $option['equipment_value'] ?></p></li>
					<?php 
									endif;
								endif;
							}
						endif;		
					?>
				</ul>
				<?php endforeach;?>
			<?php endif;?>
	<?php endif;?>



























<?php 
// if($parent_equipmentID == 61 || $parent_equipmentID == 29):
// 	if(!empty($options)) :
// 		$queue_key_desc = "";
// 		foreach($options as $option) :
?>
			<!-- <ul class="list-unstyled"> -->
				
<?php 
				// if(!empty($differ)) { 
				// 	if($differ == $option['orderID'])
				// 	{
				// 		if($option['equipment_value'] == 5)
				// 		{	
?>
							<!-- <li style="font-weight:bold"><?php echo $option['option_description'] ?></li>
							<li><p class='text-success'><?php echo $option['key_desc'] ?> </p></li> -->
<?php	
						// } 
						// if($option['equipment_value'] == 10) { 
?>
							<!-- <li style="font-weight:bold"><?php echo $option['option_description'] ?></li>
							<li><p class='text-success'><?php echo $option['key_desc'] ?> </p></li> -->
<?php 
					// 	} 
					// } 
					// $liter_id = $differ - 1;
					// if($liter_id == $option['orderID']) {
					// 	if($option['key_desc'] == "Liter Flow") {  
?>
							<!-- <li style="font-weight:bold"><?php echo $option['key_desc'] ?></li>
							<li><p class='text-success'><?php echo $option['equipment_value'] ?> LPM</p></li> -->
<?php 
					// 	}
					// }
					// if($option['option_description'] != 'Oxygen Concentrator Type') {
					// 	if($queue_key_desc != $option['key_desc']){ 
?>
							<!-- <li style="font-weight:bold"><?php echo $option['option_description'] ?></li> -->
<?php 
							// if($option['key_desc'] != "Liter Flow") :
?>
								<!-- <li><p class='text-success'><?php echo $option['key_desc'] ?> </p></li> -->
<?php 
				// 				$queue_key_desc = $option['key_desc'];
				// 			endif;
				// 		}
				// 	} 
				// }
?>
			<!-- </ul> -->
<?php 
	// 	endforeach;
	// endif;
?>