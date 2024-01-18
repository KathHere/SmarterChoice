<?php if(!empty($patient_weights)) :?>

<?php echo form_open("",array("id"=>"update_patient_weight")) ?>
	<div class="row" style="margin-left:15px;">

		<div class="col-md-12 ">
			<strong>Original Patient Weight</strong>
			<p><?php echo $patient_weights['patient_weight'] ?></p>
		</div>


		<div class="col-md-12">
			<label> </label>
	        <textarea style="margin-bottom: 10px;width:508px" class="form-control" name="patient_weight" placeholder="New Patient Weight" ></textarea>
			<button type="button" class="btn btn-success save_updated_weight_btn" data-id="<?php echo $patient_weights['weightID'] ?>" data-unique-id="<?php echo $unique_id ?>" style="margin-bottom:25px">Save Weight</button>
		</div>
	</div>
<?php echo form_close() ?>


<?php else:?>

	<?php echo form_open("",array("id"=>"put_patient_weight")) ?>
		<div class="row" style="margin-left:15px;">
			<div class="col-md-12">
				<label> </label>
				<input type="hidden" name="medical_id" value="<?php echo $medical_id ?>" />
				<input type="hidden" name="equipment_id" value="<?php echo $equipment_id ?>" />
				<input type="hidden" name="unique_id" value="<?php echo $unique_id ?>" />
				<input type="hidden" name="patientID" value="<?php echo $patientID ?>" />
		        <textarea style="margin-bottom: 10px;width:508px" class="form-control" name="patient_weight" placeholder="New Patient Weight" ></textarea>
				<button type="button" class="btn btn-success put_patient_weight" data-unique-id="<?php echo $unique_id ?>" style="margin-bottom:25px">Save Weight</button>
			</div>
		</div>
	<?php echo form_close() ?>
<?php endif;?>