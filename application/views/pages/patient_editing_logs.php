<style type="text/css">
	.popover-content
	{
		width:295px !important;
		overflow-y: scroll;
  		max-height: 325px;
	}

</style>

	<?php if(!empty($logs)) :?>
		<?php foreach($logs as $log) :?>
			<span>- Patient's information changed by <span class="text-danger"><?php echo get_person_name($log['edited_by']) ?></span> from <span class="text-info"><?php echo $log['old_value'] ?></span> to <span class="text-success"><?php echo $log['new_value'] ?></span><br/> Edited on: <strong><span class=""><?php echo date("F j, Y, g:i a", strtotime($log['date_edited'])) ?> </span></strong></span><br /><br />
		<?php endforeach;?>

	<?php else:?>
		<strong><span>We haven't found any history of edits for this patient.</span></strong>

	<?php endif;?>

