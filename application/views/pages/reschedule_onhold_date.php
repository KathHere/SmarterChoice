<section class="">
	<div class="row">
		<?php 
		if($sign == 0)
		{
		?>
			<div class="col-md-12">
				<div class="inner-form-container" style="margin-top:0px !important;background-color:#edeff4">
				<?php echo form_open("",array("id"=>"reschedule_onhold_date_form")) ;?>
				

					<div class="form-group" style="height:78px;padding-top:20px;padding-right:11px;" id="fordelivery_categories">
	                    <input type="text" class="form-control datepicker" value="" placeholder="Date" name="reschedule_onhold_date" style="">
	                </div>

	                <button type="button" class="btn btn-primary pull-right save-date-btn" data-id="<?php echo $uniqueID ?>">Save</button>
				<?php echo form_close() ;?>
				</div>
			</div>
		<?php 
		}
		else if($sign == 1)
		{
		?>
			<div class="col-md-12">
				<div class="inner-form-container" style="margin-top:0px !important;background-color:#edeff4">
				<?php echo form_open("",array("id"=>"reschedule_onhold_date_form")) ;?>
				
					<div class="form-group" style="height:78px;padding-top:20px;padding-right:11px;" id="fordelivery_categories">
	                    <input type="text" class="form-control datepicker" value="<?php echo $returned_date['date']; ?>" placeholder="Date" name="reschedule_onhold_date" style="">
	                </div>

	                <button type="button" class="btn btn-primary pull-right save-changes-date-btn" data-id="<?php echo $uniqueID ?>">Save Changes</button>
				<?php echo form_close() ;?>
				</div>
			</div>

		<?php 
		}
		?>
	</div>	
</section>

<script type="text/javascript">
  	$(document).ready(function(){
	   	$('.datepicker').datepicker({
        	dateFormat: 'yy-mm-dd'
      	});
  	});
</script>