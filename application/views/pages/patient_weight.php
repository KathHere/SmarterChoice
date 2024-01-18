<?php echo form_open("",array("id"=>"insert_patient_weight")) ?>
<div class="row" style="margin-left:15px;">

	<div class="col-md-12 ">
		<strong>Medical Record Number</strong>
		<p><?php echo $medical_id ?></p>
	</div>


	<div class="col-md-12">
		<label> </label>
        <textarea style="margin-bottom: 10px;width:508px" class="form-control" name="patient_weight" placeholder="Patient Weight" ></textarea>
        <input type="hidden" name="medical_id" value="<?php echo $medical_id ?>" />
        <input type="hidden" name="unique_id" value="<?php echo $unique_id ?>" />
        <input type="hidden" name="equipment_id" value="<?php echo $equipment_id ?>" />
        <input type="hidden" name="patientID" value="<?php echo $patient_id ?>" />
		<button type="button" class="btn btn-success save_weight_btn" data-id="" style="margin-bottom:25px">Save Weight</button>
	</div>
	
</div>
<?php echo form_close() ?>