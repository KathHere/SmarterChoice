
    </div>
  </div>
  <!-- /content -->

	<!-- footer -->
	<div class="app-footer wrapper b-t bg-light hidden-print">
	  <span class="pull-right">1.0.0 <a href="#app" class="m-l-sm text-muted"><i class="fa fa-long-arrow-up"></i></a></span>
	  &copy; <?php echo date("Y") ?> Copyright Advantage Home Medical Services
	</div>
	<!-- / footer -->


	<!-- Modal for after hour alert -->
	    <div class="modal" id="after_hour_alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	      <div class="modal-dialog">
	        <div class="modal-content after_hour_alert_content" style="display:none">
	          <div class="modal-header">
	            <h4 class="modal-title" id="myModalLabel">REMINDER</h4>
	          </div>
	          <div class="modal-body">

	            <div class="after_hour_div" style="margin-left:90px;">
	                <span class='fa fa-warning' style='font-size: 80px !important;margin-left: 135px;color: rgb(255, 79, 79);'></span><br/>
	                <h4>Business Hours: Monday - Friday 8:30AM - 5:00PM</h4>
	                <h4>Orders will be processed for the next business day!</h4>
	                <h4>Call 702.248.0056 if emergency. Thank you!</h4>
	            </div>

	          </div>
	          <div class="modal-footer">
	            <button type="button" class="btn btn-primary" data-dismiss="modal">Got it</button>
	          </div>
	        </div><!-- /.modal-content -->
	      </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
	<!-- #m-container -->



	<!-- Modal for ajax loader when submitting order -->
	    <div class="modal" id="submit_order_loader" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	      <div class="modal-dialog">
	        <div class="modal-content submit_order_loader" style="height:350px">
	          <div class="modal-header">
	            <h4 class="modal-title" id="myModalLabel">Please Wait...</h4>
	          </div>
	          <div class="modal-body">

	            <div class="after_hour_div" style="margin-left:180px;">
	               <!-- <img src="<?php echo base_url() ?>assets/img/smiley_loader.GIF" /> -->
	               <p style="margin-left:-65px;margin-top:30px">The system is now processing your request. Thank you!</p>
	            </div>

	          </div>

	        </div><!-- /.modal-content -->
	      </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->
	<!-- #m-container -->

</div>
<!-- <div data-ng-include=" 'tpl/blocks/settings.html' " class="settings panel panel-default">
</div> -->