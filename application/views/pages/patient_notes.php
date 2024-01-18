<?php
    $noted_by = $this->session->userdata('userID');
?>

<?php echo form_open("",array("id"=>"insert_patient_notes")) ?>
<div class="row" style="margin-left:15px;">

	<div class="col-md-12 ">
		<strong>Medical Record Number</strong>
		<p><?php echo $id ?></p>
	</div>

	<div class="col-md-12 ">
		<strong>Patient Name</strong>
		<p><?php echo $p_fname ?> <?php echo $p_lname ?></p>
	</div>

	<div class="col-md-12 ">
		<strong>Hospice Provider</strong>
		<p><?php echo $hospice_name ?></p>
	</div>

	<div class="col-md-12">
		<label> </label>
        <textarea style="margin-bottom: 10px;width:508px" class="form-control " name="patient_notes" placeholder="Patient Notes" ></textarea>
		<button type="button" class="btn btn-success save_note_btn" data-reload-sign="<?php echo $reload_sign ?>" data-id="<?php echo $id ?>" style="margin-bottom:25px">Save Note</button>
		<input type="hidden" name="medical_record_id" value="<?php echo $id ?>" />
		<input type="hidden" name="noted_by" value="<?php echo $noted_by ?>" />
		<input type="hidden" name="patientID" value="<?php echo $patientID ?>" />

		<?php if(!empty($notes)) :?>
			<?php foreach($notes as $note) :?>
				<textarea style="width:508px;margin-top:30px !important;" class="form-control " name="" placeholder="Patient Notes"><?php echo $note['notes'] ?></textarea>
				<p><?php echo date("m/d/Y h:i a", strtotime($note['created_on'])) ?> - <?php echo get_noted_by_patient_note($note['noteID'], $note['noted_by']) ?></p>
				<p></p>
			<?php endforeach;?>
		<?php endif;?>

		<?php
		if(!empty($notes_p2)) :
			foreach($notes_p2 as $note_p2) :
		?>
				<textarea style="width:508px;margin-top:30px !important;" class="form-control " name="" placeholder="Patient Notes"><?php echo $note_p2['comment'] ?></textarea>
				<p>WO# <?php echo substr($note_p2['order_uniqueID'],4,10); ?> - <?php echo date("m/d/Y h:i a", strtotime($note_p2['date_commented'])) ?> - <?php echo get_noted_by_order_note($note_p2['commentID'],$note_p2['userID']) ?></p>
				<p></p>
		<?php
			endforeach;
		endif;
		?>
	</div>

</div>
<?php echo form_close() ?>