<?php echo form_open("",array("id"=>"edit_bipap_option")) ?>
<div class="row" style="">

<?php if(!empty($original_bipaps)) :?>
		<div class="col-md-12">
			<div class="form-group">
	            <label>IPAP <span style="color:red;">*</span></label>
	            <input type="text" data-desc="IPAP" class="form-control" name="bipap[4][109][]" value="<?php echo $original_bipaps[0]['equipment_value'] ?>">
	        </div>

	        <div class="form-group">
	            <label>EPAP <span style="color:red;">*</span></label>
	            <input type="text" data-desc="EPAP" class="form-control" name="bipap[4][110][]" value="<?php echo $original_bipaps[1]['equipment_value'] ?>">
	        </div>

	        <div class="form-group">
	            <label>Rate <i>(If applicable)</i> </label>
	            <textarea type="text" data-desc="Rate" class="form-control" name="bipap[4][111][]"><?php echo $original_bipaps[2]['equipment_value'] ?></textarea>
	        </div>

	        <button type="button" class="btn btn-primary save_bipap_option" data-id="<?php echo $parent_equipmentID ?>" data-unique-id="<?php echo $unique_id ?>" style="margin-bottom:25px">Save Changes</button>
		</div>
<?php endif;?>
	
</div>
<?php echo form_close() ?>