<?php echo form_open("",array("id"=>"edit_liter_flow")) ?>
<div class="row" style="margin-left:15px;">

	<div class="col-md-12 ">
		<strong>Original Liter Flow</strong>
		<p><?php echo $initial_liter_flow ?> LPM</p>
	</div>

	<div class="col-md-12">
		<label> </label>
        <textarea style="margin-bottom: 10px;width:508px" class="form-control" name="liter_flow_qty" placeholder="Liter Flow"></textarea>
		<button type="button" class="btn btn-success save_liter_flow" data-id="<?php echo $liter_flow_id ?>" data-unique-id="<?php echo $unique_id ?>" style="margin-bottom:25px">Save Liter Flow</button>
	</div>
	
</div>
<?php echo form_close() ?>