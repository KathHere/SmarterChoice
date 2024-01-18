<section class="">
	<div class="row">
			<div class="col-md-12">
				<div class="inner-form-container" style="margin-top:0px !important;background-color:#edeff4;">
				<?php echo form_open("",array("id"=>"enter-comment-page")) ;?>
					<div class="" style="padding: 14px;text-align:center;padding-top:30px;padding-bottom:50px;">
						<?php
							echo "<p style='font-weight:bold;'> WO# - ".substr($uniqueID,4,10)."</p>"; 
						?>	
						<div class="comments-main-content">
						<?php if(!empty($comments)) :?>
							<?php foreach($comments as $comment) :?>
				      			<div class="comments-area">
				      				<p class="comment-text"><?php echo $comment['comment'] ?></p>
				      				<p class="comment-when-by" style="font-size:12px">Commented on <?php echo  date("m/d/Y h:ia", strtotime($comment['date_commented'])) ?> by <?php echo $comment['userName'] ?></p>
				      			</div>

			      			<?php endforeach ;?>
			      		<?php endif ;?>
			      		</div>

			      		<?php 
							if ($page_type == 'work_order_status') {
								if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller') :
						?>
									<textarea class="form-control comment_content" name="comment" style="margin-bottom:15px" placeholder="Enter comment" required></textarea>
									<button type="button" class="btn btn-primary pull-right enter-comments-btn" data-id="<?php echo $uniqueID ?>">Comment</button>
						<?php
								endif ;
							} else if ($page_type == 'customer_order_status') {
								if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'distribution_supervisor') :
						?>
									<textarea class="form-control comment_content" name="comment" style="margin-bottom:15px" placeholder="Enter comment" required></textarea>
									<button type="button" class="btn btn-primary pull-right enter-comments-btn" data-id="<?php echo $uniqueID ?>">Comment</button>
						<?php
								endif ;
							} else if ($page_type == 'patient_profile') {
								if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'distribution_supervisor' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep') :
						?>
									<textarea class="form-control comment_content" name="comment" style="margin-bottom:15px" placeholder="Enter comment" required></textarea>
									<button type="button" class="btn btn-primary pull-right enter-comments-btn" data-id="<?php echo $uniqueID ?>">Comment</button>
						<?php
								endif ;
							} else {
								if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :
						?>
									<textarea class="form-control comment_content" name="comment" style="margin-bottom:15px" placeholder="Enter comment" required></textarea>
									<button type="button" class="btn btn-primary pull-right enter-comments-btn" data-id="<?php echo $uniqueID ?>">Comment</button>
						<?php
								endif ;
							}
						?>
					</div>
					<input type="hidden" name="commented_by" value="<?php echo $this->session->userdata('userID') ?>" />
					<input type="hidden" name="commented_by_name" value="<?php echo $this->session->userdata('lastname') ?> <?php echo $this->session->userdata('firstname') ?>" />
		 			<input type="hidden" name="order_uniqueID" value="<?php echo $uniqueID ?>" />
				</div>
				<?php echo form_close() ;?>

				<div class="page-shadow">
					
				</div>
		</div>
	</div>	
</section>