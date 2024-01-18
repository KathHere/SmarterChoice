<section class="">
	<div class="row">

			<div class="col-md-12">
				<div class="inner-form-container" style="margin-top:0px !important;background-color:#edeff4">
				<?php echo form_open("",array("id"=>"edit_patient_entry_time_form")) ;?>
				
					<div class="form-group" style="height:78px;padding-top:20px;padding-right:11px;" id="fordelivery_categories">
	                    <input type="text" class="form-control datepicker" value="<?php echo date("Y-m-d", strtotime($patient_entry_date)); ?>" placeholder="Date" name="patient_entry_date" style="">
	                </div>

	                <button type="button" class="btn btn-primary pull-right save-changes-entry-time-btn" data-id="<?php echo $patientID ?>">Save Changes</button>
				<?php echo form_close() ;?>
				</div>
			</div>

		</div>	
</section>


<script type="text/javascript">
  	$(document).ready(function(){
	   	$('.datepicker').datepicker({
        	dateFormat: 'yy-mm-dd'
      	});
  	});
</script>