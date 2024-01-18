<style>
	.cancel_edit_inventory_item_asset_serial_no
	{
		margin-left: 10px;
	}
	.buttons_div
	{
		margin-bottom: -15px !important;
		margin-top: -5px !important;
	}
</style>

<?php 
    echo form_open("",array("class"=>"edit_inventory_item_asset_serial_no"));
?>

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="form-group clearfix" style="">
			<div class="form-group" style="text-align: center;font-weight: bold; margin-bottom:0px !important;padding-bottom:10px; height:20px;">
	            <div class="col-xs-6 col-sm-6">
	                Serial No.
	            </div>
	            <div class="col-xs-6 col-sm-6">
	                Asset No.
	            </div>
	        </div>
	       	<div class="form-group" style="padding-top: 10px !important;height: 40px;margin-bottom: 35px !important;">
			    <div class="col-sm-6 col-xs-6">
			        <input type="text" class="form-control grey_inner_shadow" value="<?php echo $item_serial_asset_no['item_serial_no']?>" name="serial_no">
			    </div>
			    <div class="col-sm-6 col-xs-6">
			        <input type="text" class="form-control grey_inner_shadow" value="<?php echo $item_serial_asset_no['item_asset_no']?>" name="asset_no">
			    </div>
			</div>
			<hr />
	        <div class="col-sm-12 col-xs-12 buttons_div">
	        	<button type="button" class="btn btn-danger pull-right cancel_edit_inventory_item_asset_serial_no" onclick="closeModalbox()">Cancel</button>
	            <button type="button" class="btn btn-success pull-right submit_edit_inventory_item_asset_serial_no" data-inventory-item-id="<?php echo $inventory_item_id; ?>"> Save Changes </button>
	        </div>
	    </div>
	</div>
</div>

<?php 
    echo form_close();
?>

<script type="text/javascript"> 

    $(document).ready(function(){

        $('body').on('click','.submit_edit_inventory_item_asset_serial_no',function(){
            var _this = $(this);
            var form_data = $('.edit_inventory_item_asset_serial_no').serialize();
            var inventory_item_id = $(this).attr("data-inventory-item-id");

            $.post(base_url+"inventory/submit_edit_inventory_item/"+ inventory_item_id +"/",form_data, function(response){
                var obj = $.parseJSON(response); 
                me_message_v2({error:obj['error'],message:obj['message']});
                if(obj['error'] == 0)
                {
                    setTimeout(function(){
                        location.reload();
                    },1500);
                }
            });
        });

    });

</script>