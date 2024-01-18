<div class="col-md-6 col-md-offset-3" style="margin-bottom:50px;margin-top:150px;">
    <form action="<?php echo base_url()?>order/return_search" method="GET" id="search_form">
	    <div class="search-bar">
		    <i class="icon-user" style="font-size: 102px;margin-left: 42%;"></i>
		    <p style="text-align:center;margin-top: 9px;">Customer Search</p>
		    <div class="input-group">
		      <input type="text" class="form-control" id="search-patients" name="term" style="text-transform:none" autocomplete="off" value="" placeholder="Search by Name , MR #, WO#" data-group="<?php echo $this->session->userdata('group_id') ?>">
		      <input type="hidden" class="form-control" id="pfname" name="query" autocomplete="off" value="">
		      <input type="hidden" class="form-control" id="plname" name="param" autocomplete="off" value="">
		      <input type="hidden" class="form-control" id="medicalid" name="param2" autocomplete="off" value="">
		      <span class="input-group-btn">
		        <button class="btn btn-default btn-submit-search search-patients-button" type="submit" title="Search"><i class="fa fa-search"></i></button>
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