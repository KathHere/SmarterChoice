<section class="">
	<div class="row">
			<div class="col-md-12">
				<div class="inner-form-container" style="margin-top:0px !important;">
				<?php echo form_open("",array("id"=>"add_lot_notes_form")) ;?>
				<?php if(!empty($results)) :?>
					<?php foreach($results as $result) : ?>
						<div class="" style="padding: 14px;text-align:center;padding-bottom:30px;">
							<textarea class="form-control" style="margin-top:15px" name="lot_comment" placeholder="Add Lot # Here"></textarea>
							<div class="comments-main-content">
							<?php if(!empty($comments)):?>
								<?php foreach($comments as $comment) :?>
									<textarea class="form-control" disabled  placeholder="" style="margin-top:25px"><?php echo $comment['lot_comment'] ?></textarea>
									<p class="comment-when-by" style="font-size:12px;margin-top:-3px">Added Note on <?php echo  date("m/d/Y h:ia", strtotime($comment['date_added'])) ?> 
								<?php endforeach;?>
							<?php endif;?>	
							</div>
							
						</div>
						<button type="button" class="btn btn-primary pull-right btn-add-lot-comment" data-id="">Submit</button>
						<?php $userID = $this->session->userdata('userID') ?>
						<input type="hidden" name="userID" value="<?php echo $userID ?>" />
						<input type="hidden" name="equipmentID" value="<?php echo $result['equipmentID'] ?>" />
						<input type="hidden" name="uniqueID" value="<?php echo $result['uniqueID'] ?>" />
					<?php endforeach ;?>
				<?php endif;?>
					
				<?php echo form_close() ;?>
				</div>
				
		</div>
	</div>	
</section>