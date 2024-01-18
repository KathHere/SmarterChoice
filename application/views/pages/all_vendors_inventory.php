<style type="text/css">
	@media (max-width: 480px){

		.vendor_boxes
		{
			width: 100% !important;
		}
	}
	
		
</style>

<div class="bg-light lter b-b wrapper-md">
	<h1 class="m-n font-thin h3">View All Vendors (Total: <?php echo count($vendor_list); ?>)</h1>
</div>

<div class="wrapper-md">

	<?php
		if(empty($vendor_list))
		{
	?>
			<img src="<?php echo base_url()?>assets/img/empty_folder.png" />
      		<h4>No Record Found.</h4>
	<?php
		}
		else
		{
	?>
			<div class="row" id="">
	<?php
			foreach ($vendor_list as $key => $value) 
			{
				if($value['vendor_active_sign'] == 1)
				{
	?>
					<div class="col-12 col-xs-6 col-sm-4 col-md-3 vendor_boxes" id="<?php echo $value['vendor_id']; ?>">
	                	<div class="panel wrapper" style="min-height:342px !important;">
	                		<div class="icon-container bg-info">
	                        	<a href="<?php echo base_url('inventory/vendor_details/'.$value['vendor_id']) ?>">
		                        	<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;">
		                        		<i class="fa fa-fw fa-folder-open-o text" style="font-size:65px;"></i>
		                        	</button>
	                        	</a>
	                		</div>
	                		<h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">
	                			<a href="<?php echo base_url('inventory/vendor_details/'.$value['vendor_id']) ?>" style="color:#3498b7 !important;">
	                				<?php echo $value['vendor_name']; ?> 
	                			</a>
	                		</h4>
	                	</div>
	                </div>
	<?php
				}
				else
				{
	?>
					<div class="col-12 col-xs-6 col-sm-4 col-md-3 vendor_boxes" id="<?php echo $value['vendor_id']; ?>">
	                	<div class="panel wrapper" style="min-height:342px !important;">
	                		<div class="icon-container bg-info">
	                        	<a href="<?php echo base_url('inventory/vendor_details/'.$value['vendor_id']) ?>">
		                        	<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;background-color: #7ccfe8;border-color: #7ccfe8;">
		                        		<i class="fa fa-fw fa-folder-open-o text" style="font-size:65px;"></i>
		                        	</button>
	                        	</a>
	                		</div>
	                		<h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">
	                			<a href="<?php echo base_url('inventory/vendor_details/'.$value['vendor_id']) ?>" style="color:#3498b7 !important;">
	                				<?php echo $value['vendor_name']; ?> 
	                			</a>
	                		</h4>
	                	</div>
	                </div>
	<?php

				}
			}
	?>
			</div>
	<?php
		}
	?>


</div>