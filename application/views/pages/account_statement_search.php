<div class="col-md-6 col-md-offset-3" style="margin-bottom:50px;margin-top:150px;">
    <form action="<?php echo base_url()?>billing/return_search" method="POST" id="search_form">
	    <div class="search-bar" style="text-align:center">
		    <i class="fa fa-hospital-o" style="font-size: 102px;"></i>
		    <p style="text-align:center;margin-top: 9px;">Search Account</p>
		    <div class="input-group">
		      <input type="text" class="form-control" id="search-account-statements" name="term" style="text-transform:none" autocomplete="off" value="" placeholder="Search by Account Name, Account #">
		      <input type="hidden" class="form-control" id="hospice_id" name="hospice_id" autocomplete="off" value="">
		      <span class="input-group-btn">
		        <button class="btn btn-default btn-submit-search" type="submit" title="Search"><i class="fa fa-search"></i></button>
		      </span>
		    </div>
	    	<div id="suggestion_container" style="z-index:9999;position:absolute;border:0px solid black;width: calc(100% - 68px);padding-right:0px;max-height: 250px; overflow-y: auto;"></div>
		</div>
	</form>
</div>


<script type="text/javascript">
	$('#search-patients').bind('keyup',function(){
		$(this).css("text-transform","uppercase");

		if($(this).val() == '')
		{
			$(this).css("text-transform","none");
		}
	});

	
</script>