<?php echo form_open("",array("id"=>"edit_cpap_option")) ?>
<div class="row" style="">

<?php if(!empty($original_cpaps)) :?>
		<div class="col-md-12">

			<div class="form-group">
	            <label>CMH20 <span style="color:red;">*</span></label>
	            <input type="text" data-desc="IPAP" class="form-control" name="cpap_value" value="<?php echo $original_cpaps[0]['equipment_value'] ?>">
	        </div>

	        <button type="button" class="btn btn-primary save_cpap_option" data-id="<?php echo $parent_equipmentID ?>" data-unique-id="<?php echo $unique_id ?>" style="margin-bottom:25px">Save Changes</button>
		</div>
<?php endif;?>
	
</div>
<?php echo form_close() ?>