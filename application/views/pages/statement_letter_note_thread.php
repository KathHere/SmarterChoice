<style type="text/css">
<?php
	if($is_required == "required") {
		echo 
		'
		.close {
			display: none !important;
		}
		';
	}

?>
	@media print{
		@page {
			size: portrait;
			/*margin: 10mm 2mm 10mm 2mm;*/
		}

		.modal-content
	    {
	        border:0px !important;
	    }
	    .modal-header
	    {
	        display:none !important;
	    }

	    .hidden-header {
	    	display: block !important;
	    }

	    .modal {
		    position: absolute;
		    left: 0;
		    top: 0;
		    margin: 0;
		    margin-top: -20px !important;
		    padding: 0;
		    visibility: visible;
		    /**Remove scrollbar for printing.**/
		    overflow: visible !important;
		}
		.modal-dialog {
		    visibility: visible !important;
		    /**Remove scrollbar for printing.**/
		    overflow: visible !important;
		}


		.statement_activity_container, .statement_bill_by_hospice_container, .statement_invoice_inquiry_container, .payment_history_container, .archive_container, .payment_history_by_hospice_container {
			display: none !important;
		}

		.row {
		  display: flex;
		  flex-direction: row;
		  flex-wrap: wrap;
		  width: 100%;
		}

		.col, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
		  display: flex;
		  flex-direction: column;
		  flex-basis: 100%;
		  flex: 1;
		}

		.statement_letter_note {
			display: block !important;
		}
	}
	
	.statement_letter_note {
		text-align: center;
		font-weight: bold;
		display: none;
	}
</style>
<section class="">
	<!-- <div class="account_invoice_note">ACCOUNT INVOICE NOTE</div> -->
	<div class="row">
		<div class="col-md-12">
			<div class="inner-form-container" style="margin-top:0px !important;background-color:#edeff4;">
			<?php echo form_open("",array("id"=>"enter-note-page")) ;?>
				<div class="" style="padding: 14px;text-align:center;padding-top:30px;padding-bottom:50px;">
					<?php
						echo "<p style='font-weight:bold;' class='statement_letter_note'> NOTE </p>";
						// echo "<p style='font-weight:bold;'> Account Number - ".$hospice_details['hospice_account_number']."</p>";
						echo "<p style='font-weight:bold;'> ".$hospice_details['hospice_account_number']." - ".$hospice_details['hospice_name']."</p>";
					?>
					<div class="comments-main-content">
					<?php if(!empty($comments)) :?>
						<?php foreach($comments as $comment) :?>
			      			<div class="comments-area">
			      				<p class="comment-text"><?php echo $comment['note'] ?></p>
			      				<p class="comment-when-by" style="font-size:12px">Commented on <?php echo  date("m/d/Y h:ia", strtotime($comment['date_added'])) ?> by <?php echo $comment['userName'] ?></p>
			      			</div>

		      			<?php endforeach ;?>
		      		<?php endif ;?>
		      		</div>

		      		<?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller') :?>
						<textarea class="form-control comment_content hidden-print" name="comment" style="margin-bottom:15px" placeholder="Add Note..." required></textarea>
						<button class="btn btn-default hidden-print pull-left" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
						<button type="button" class="btn btn-primary pull-right enter-notes-btn" data-id="<?php echo $hospice_details['hospiceID'] ?>">Comment</button>
					<?php endif ;?>
				</div>
				<input type="hidden" name="commented_by" value="<?php echo $this->session->userdata('userID') ?>" />
				<input type="hidden" name="commented_by_name" value="<?php echo $this->session->userdata('lastname') ?> <?php echo $this->session->userdata('firstname') ?>" />
	 			<input type="hidden" name="hospiceID" value="<?php echo $hospice_details['hospiceID'] ?>" />
			</div>
			<?php echo form_close() ;?>

			<div class="page-shadow">

			</div>
		</div>
	</div>
</section>




<script type="text/javascript">
	$(document).ready(function(){
		//for the inserting of threaded comments in modal
		$('body').on('click','.enter-notes-btn',function(){
		    var id = $(this).attr('data-id');
		    var form_data = $('#enter-note-page').serialize();
		    var this_element = $(this);

		    jConfirm('Add Note ?', 'Reminder', function(response){
		    	if(response)
		    	{
		    		$.post(base_url + 'billing_reconciliation/insert_statement_letter_notes/' + id,form_data, function(response){
		        		var obj = $.parseJSON(response);
		        		jAlert(obj['message'],'Response');
			       		if(obj['error'] == 0)
			        	{
				            var comment_count = $('body .notes_count_'+id).find('p').text();
				            $('body .notes_count_'+id).find('p').html(Number(comment_count)+1);

				            closeModalbox();
				            setTimeout(function(){
				              location.reload();
				            },1000);
			        	}
		    		});
		    	}
		    });
		});
	});
</script>