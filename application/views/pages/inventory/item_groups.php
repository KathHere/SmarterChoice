<style type="text/css">

	.panel-body
	{
		padding-left: 0px!important;
		padding-right: 0px!important;
	}

</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  <h1 class="m-n font-thin h3">Item Groups</h1>
</div>

<div class="wrapper-md hidden-print">
  	<div class="panel panel-default">
    	<div class="panel-heading purchase_order_list_header">
    		<div class="form-group clearfix" style="margin-bottom:0px !important;">
	    		<div class="col-sm-12 col-md-12 text-right">
	                <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#add_new_item_group" data-item-group-id="<?php echo $value['item_group_id']; ?>">
	                	<i class="fa fa-plus"></i> Add New Group
	                </button>
	            </div>
	        </div>
    	</div>
    	<div class="panel-body" style="overflow-x:auto;">
    		<?php
                if (!empty($item_group_list)) {
            ?>
    				<table class="table m-b-none datatable_table_item_groups text-left" style="min-width: 900px !important;">
    		<?php
                } else {
            ?>
    				<table class="table m-b-none text-left" style="min-width: 900px !important;">
    		<?php
                }
            ?>
		       	<thead>
		          	<tr class="text-left">
                        <th style="width:13%;">Group Name</th>
                        <th style="width:13%;">No. of Items</th>
                        <th style="width:12%;">Date Added</th>
                        <th style="width:10%;">Actions</th>
		          	</tr>
		        </thead>
		        <tbody class="">
		        	<?php
	                    if (!empty($item_group_list)) {
	                    	foreach ($item_group_list as $key => $value) {
	                ?>
	                			<tr>
					        		<td class="">
					        			<?php echo $value['item_group_name']; ?>
					        		</td>
					        		<td class="order_inquiry_td">
					        			0
			              			</td>
					        		<td class="order_inquiry_td">
					        			<?php echo date("m/d/Y", strtotime($value['date_added'])); ?>
					        		</td>
					        		<td class="order_inquiry_td">
					        			<button type="button"
					        				class="btn btn-primary btn-xs edit_item_group_button"
					        				data-item-group-id="<?php echo $value['item_group_id']; ?>"
					        				data-item-group-name="<?php echo $value['item_group_name']; ?>"
					        			>
					        			<i class="fa fa-pencil"></i> Edit
					        			</button>
					        			<button type="button"
					        				class="btn btn-danger btn-xs delete_item_group_button"
					        				data-item-group-id="<?php echo $value['item_group_id']; ?>"
					        			>
					        				<i class="fa fa-trash"></i> Delete
					        			</button>
					        		</td>
					        	</tr>
	                <?php
	                		}
	                	} else {
	               	?>
	               		<tr>
			        		<td colspan="4" class="text-center" style="padding-top:18px;">
			        			<span style="font-size: 16px;"> No list yet. </span>
			        		</td>
					    </tr>
	               	<?php
	                	}
	                ?>
		        </tbody>

		    </table>
    	</div>
    </div>

</div>

<div class="modal fade" id="add_new_item_group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
	<div class="modal-dialog" style="">
	    <div class="modal-content">
	      	<div class="modal-header">
	        	<h4 class="modal-title asset_modal">Add New Item Group</h4>
	      	</div>
	      	<div class="modal-body">
	      		<form role="form" action="<?php echo base_url('item_grouping/add_item_group'); ?>" method="post" id="save_new_item_group">
      				<div class="form-group clearfix">
                        <div class="col-sm-12">
                            <label> Group Name <span class="text-danger-dker">*</span></label>
                            <input type="text" name="item_group_name" class="form-control" id="add_item_group_name">
                        </div>
                    </div>
	      		</form>
	      	</div>
	      	<div class="modal-footer" style="padding-left: 30px;padding-right: 30px;">
				<input type="hidden" value="0" class="duplicate_asset_no">
	      		<button type="button" class="btn btn-success save_new_item_group_button" disabled> Save </button>
	        	<button type="button" class="btn btn-danger close_new_item_group_button" data-dismiss="modal"> Close</button>
	    	</div>
	  	</div>
	</div>
</div>

<div class="modal fade" id="edit_item_group_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="">
	<div class="modal-dialog" style="">
	    <div class="modal-content">
	      	<div class="modal-header">
	        	<h4 class="modal-title asset_modal">Edit Item Group Details</h4>
	      	</div>
	      	<div class="modal-body">
	      		<form role="form" action="<?php echo base_url('item_grouping/update_item_group_details'); ?>" method="post" id="save_item_group_detail_changes">
      				<div class="form-group clearfix">
                        <div class="col-sm-12">
                            <label> Group Name <span class="text-danger-dker">*</span></label>
                            <input type="text" name="item_group_name" class="form-control" id="edit_item_group_name">
                            <input type="hidden" name="item_group_id" class="form-control" id="edit_item_group_id">
                        </div>
                    </div>
	      		</form>
	      	</div>
	      	<div class="modal-footer" style="padding-left: 30px;padding-right: 30px;">
				<input type="hidden" value="0" class="duplicate_asset_no">
	      		<button type="button" class="btn btn-success save_item_group_detail_changes_button"> Save Changes</button>
	        	<button type="button" class="btn btn-danger close_item_group_detail_changes_button" data-dismiss="modal"> Close</button>
	    	</div>
	  	</div>
	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(){

		$('.datatable_table_item_groups').DataTable( {
	        "order": [[ 0, "desc" ]]
	    });

		$('#add_item_group_name').bind('keyup',function(){
            var _this = $(this);

            if (_this.context.value.length > 0) {
            	$('.save_new_item_group_button').removeAttr('disabled');
            } else {
            	$('.save_new_item_group_button').attr('disabled', true);
            }
        });

		$('.save_new_item_group_button').click(function(e){
            var _this_save_btn = $(this);

            jConfirm('<br />Save new item group?', 'Reminder', function(response){
                if(response)
                {
                    //disable submit button until the processing is done
                    $(_this_save_btn).prop('disabled',true);

                    $("#save_new_item_group").ajaxSubmit({
                        beforeSend:function()
                        {
                            me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Saving..."});
                        },
                        success:function(response)
                        {
                            $('#error-modal .alert').removeClass('alert-danger');
                            $('#error-modal .alert').removeClass('alert-info');
                            $('#error-modal .alert').removeClass('alert-success');

                            try
                            {
                                var obj = $.parseJSON(response);
                                me_message_v2(obj);
                                if(obj['error']==0)
                                {
                                    setTimeout(function(){
                                    	window.location.reload();
                                    },2000);
                                }
                            }
                            catch (err)
                            {
                                me_message_v2({error:1,message:"Failed to save new item group."});
                            }
                            $(_this_save_btn).prop('disabled',false);
                        }
                    });
                }
            });
        });

        $('.close_new_item_group_button').click(function(e){
        	$('#add_item_group_name').val('');
        	$('.save_new_item_group_button').attr('disabled', true);
        });

        $('.edit_item_group_button').click(function(e){
        	var _this = $(this);
        	var itemGroupName = _this.attr('data-item-group-name');
        	var itemGroupId = _this.attr('data-item-group-id');

        	$('#edit_item_group_name').val(itemGroupName);
        	$('#edit_item_group_id').val(itemGroupId);

        	$('#edit_item_group_details').modal('show');
        });

        $('#edit_item_group_name').bind('keyup',function(){
            var _this = $(this);

            if (_this.context.value.length > 0) {
            	$('.save_item_group_detail_changes_button').removeAttr('disabled');
            } else {
            	$('.save_item_group_detail_changes_button').attr('disabled', true);
            }
        });

        $('.save_item_group_detail_changes_button').click(function(e){
            var _this_save_btn = $(this);

            jConfirm('<br />Save changes made on item group?', 'Reminder', function(response){
                if(response)
                {
                    //disable submit button until the processing is done
                    $(_this_save_btn).prop('disabled',true);

                    $("#save_item_group_detail_changes").ajaxSubmit({
                        beforeSend:function()
                        {
                            me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Saving..."});
                        },
                        success:function(response)
                        {
                            $('#error-modal .alert').removeClass('alert-danger');
                            $('#error-modal .alert').removeClass('alert-info');
                            $('#error-modal .alert').removeClass('alert-success');

                            try
                            {
                                var obj = $.parseJSON(response);
                                me_message_v2(obj);
                                if(obj['error']==0)
                                {
                                    setTimeout(function(){
                                    	window.location.reload();
                                    },2000);
                                }
                            }
                            catch (err)
                            {
                                me_message_v2({error:1,message:"Failed to save changes."});
                            }
                            $(_this_save_btn).prop('disabled',false);
                        }
                    });
                }
            });
        });

        $('.close_item_group_detail_changes_button').click(function(e){
        	$('#edit_item_group_name').val('');
        });


        $('.delete_item_group_button').click(function(e){
        	var _this = $(this);
        	var itemGroupId = _this.attr('data-item-group-id');

        	jConfirm('<br />Delete item group?', 'Reminder', function(response){
                if(response)
                {
                    $.post(base_url+"item_grouping/delete_item_group/"+itemGroupId,"", function(response){
		                var obj = $.parseJSON(response);
		                me_message_v2(obj);

		                if (obj.error == 0) {
		                	setTimeout(function(){
		                    	window.location.reload();
		                    },1500);
		                }
		            });
                }
            });
        });

	});

</script>