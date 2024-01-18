<div class="container" style="margin-top:2%">
	<div class="row">
	 	<div class="col-sm-12">
			<div class="col-md-6 col-md-offset-3" style="margin-bottom:50px;margin-top:150px;">
			    <form action="<?php echo base_url() ?>order/return_item_search" method="GET" id="item_tracking_search">
				    <div class="search-bar">
				    	<div class="logo-cont" style="text-align:center">
					    	<img src="<?php echo base_url()?>assets/img/item_tracking_logo.png" class="" style="width:170px;" />
					    </div>
					    <p style="text-align:center;margin-top: 9px;">DME ITEM TRACKING</p>
					    <div class="input-group">
					      <input type="text" class="form-control" id="item-tracking-search" name="serial" style="text-transform:none" autocomplete="off" value="" placeholder="Search by Serial #, Asset #" />
					      <span class="input-group-btn">
					        <button class="btn btn-default btn-item-tracking-search" type="submit" title="Search"><i class="fa fa-search"></i></button>
					      </span>
					    </div>
					    <div id="item_tracking_suggestions" style="z-index:9999;position:absolute;border:0px solid black;width: calc(100% - 68px);padding-right:0px;max-height: 250px; overflow-y: auto;"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('#item-tracking-search').bind('keyup',function(){
		$(this).css("text-transform","uppercase");

		if($(this).val() == '')
		{
			$(this).css("text-transform","none");
		}
	});
</script>