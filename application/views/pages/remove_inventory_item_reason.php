<style>
	.buttons_div
	{
		margin-top: 30px;
	}

	.cancel_inventory_item_removal
	{
		margin-left: 10px;
	}
</style>

<?php 
    echo form_open("",array("class"=>"inventory_item_removal_form"));
?>

<div class="row">
	<div class="form-group clearfix" style="margin-top:25px;">
        <div class="col-sm-12 col-xs-12">
        	<select class="form-control grey_inner_shadow" name="inventory_item_removal_reason"> 
				<option value="missing_item"> Missing Item</option>
				<option value="out_for_repair"> Out for Repair</option>
                <option value="thrown_out" > Thrown Out</option>
			</select>
        </div>
        <div class="col-sm-12 col-xs-12 buttons_div">
        	<button type="button" class="btn btn-danger pull-right cancel_inventory_item_removal" onclick="closeModalbox()">Cancel</button>
            <button type="button" class="btn btn-success pull-right submit_inventory_item_removal" data-inventory-item-id="<?php echo $inventory_item_id; ?>"> Submit </button>
        </div>
    </div>
</div>

<?php 
    echo form_close();
?>


<script type="text/javascript"> 

    $(document).ready(function(){

        $('body').on('click','.submit_inventory_item_removal',function(){
            var _this = $(this);
            var form_data = $('.inventory_item_removal_form').serialize();
            var inventory_item_id = $(this).attr("data-inventory-item-id");

            $.post(base_url+"inventory/submit_inventory_item_removal/"+ inventory_item_id +"/",form_data, function(response){
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


