<?php 
	if($sign == 1){
?>

		<section class="">
			<div class="row">

					<div class="col-md-12">
						<div class="inner-form-container" style="margin-top:0px !important;background-color:#edeff4">
						<?php echo form_open("",array("id"=>"edit_order_pickup_date_form")) ;?>
						
							<div class="form-group" style="height:78px;padding-top:20px;padding-right:11px;" id="fordelivery_categories">
			                    <input type="text" class="form-control datepicker" value="<?php echo date("Y-m-d", strtotime($patient_pickup_date)); ?>" placeholder="Pickup Date" name="order_pickup_date" style="">
			                </div>

			                <button type="button" class="btn btn-primary pull-right save-changes-pickup-date-btn" data-id="<?php echo $patientID ?>">Save Changes</button>
						<?php echo form_close() ;?>
						</div>
					</div>

				</div>	
		</section>


		<script type="text/javascript">
		  	$(document).ready(function(){
			   	$('.datepicker').datepicker({
		        	dateFormat: 'yy-mm-dd'
		      	});
		  	});
		</script>

<?php 
	}
?>