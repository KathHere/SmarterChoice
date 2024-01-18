<section class="">
	<div class="row">
		<?php 
		if($modal_sign == 1)
		{
		?>
			<div class="col-md-12">
				<div class="inner-form-container" style="margin-top:0px !important;background-color:#edeff4">
				<?php echo form_open("",array("id"=>"edit_follow_up_date_form")) ;?>

					<div class="form-group" style="height:78px;padding-top:20px;padding-right:11px;padding-left:8px;">
	                    <input type="text" class="form-control datepicker_follow_up_date" value="<?php echo date("Y-m-d", strtotime($follow_up_date['follow_up_date'])); ?>" placeholder="Date" name="follow_up_date">
	                </div>

	                <button type="button" class="btn btn-primary pull-right save-follow-up-date-btn" data-id="<?php echo $follow_up_date['follow_up_id'] ?>">Update</button>
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
	   	$('.datepicker_follow_up_date').datepicker({
        	dateFormat: 'yy-mm-dd'
      	});
  	});
</script>