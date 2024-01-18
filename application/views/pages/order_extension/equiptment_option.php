<style type="text/css">
    #globalModal {
    margin-top: 0%;
}
</style>
<div class="OpenSans-Reg col-xs-12 clearfix">
    <form class="submit-option">
        <input type="hidden" name="equipmentID" value="<?php echo $equipmentID; ?>" />
        <input type="hidden" name="uniqueID" value="<?php echo $uniqueID; ?>" />
    <?php 
        foreach($options as $key=>$value)
        {
            if($key != 1)
            {
                if($key!=0){?><label><?php echo isset($label[$key])? $label[$key] : "" ?><span style="color:red;">*</span></label><?php } 
                foreach($value as $subkey=>$subvalue){
                    if($subvalue['input_type']=="radio"){ 
                        if($subvalue['equipmentID'] != 134 && $subvalue['equipmentID'] != 135)
                        {
    ?>
                            <div class="<?php echo $subvalue['input_type'];?>">
                                <label class="i-checks">
                                    <input type="<?php echo $subvalue['input_type'];?>" <?php echo (isset($orders_simple[$key]) AND in_array($subvalue['equipmentID'],$orders_simple[$key]))? "checked" : '' ?> name="subequipment[<?php echo $key; ?>][radio]" value="<?php echo $subvalue['equipmentID']; ?>">
                                    <i class="grey_inner_shadow"></i> <?php echo $subvalue['key_desc']; ?>
                                    <?php if(isset($orders_simple[$key]) AND in_array($subvalue['equipmentID'],$orders_simple[$key])): ?>
                                        <input type="hidden" name="subequipment[<?php echo $key; ?>][previous_val]" value="<?php echo $subvalue['equipmentID']; ?>" />
                                    <?php endif; ?>
                                </label>
                            </div>
                    <?php 
                        }
                    }elseif($subvalue['input_type']=="checkbox"){
                    ?>
                        <div class="<?php echo $subvalue['input_type'];?>">
                            <?php 
                                $is_check = "";
                            ?>
                            <label class="i-checks">
                                <input type="<?php echo $subvalue['input_type'];?>" name="subequipment[<?php echo $key; ?>][<?php echo $subvalue['input_type'] ?>][]" value="<?php echo $subvalue['equipmentID']; ?>">
                                <i class="grey_inner_shadow"></i> <?php echo $subvalue['key_desc']; ?>
                            </label>
                        </div>
                    <?php 
                    }else{
                    ?>
                         <div class="form-group">
                            <label for="exampleInputEmail1"><?php echo isset($label[$key])? $label[$key] : $subvalue['key_desc'] ?><span style="color:red;">*</span></label>
                            <input type="text" name="subequipment[<?php echo $subvalue['equipmentID']; ?>]" class="form-control liter_flow_field grey_inner_shadow"  value="<?php echo $orders[$key][0]['equipment_value']; ?>" />
                         </div>
                    <?php 
                    } 
                } 
            } 
        }
    ?>
      <div class="form-group" style="margin-top: 20px;">
          <button type="button" class="btn btn-primary pull-right submit-btn-options"> <i class="fa fa-pencil"></i> update</button>
      </div>
    </form>
</div>
<script type="text/javascript">
    
    jQuery(document).ready(function($){
        $('.submit-btn-options').on('click',function(){
            me_message_v2({error:2,message:"Updating equiption option(s)"});
            $.post('<?php echo base_url('order_extend/update_options');?>',$('.submit-option').serialize(),function(data){
                me_message_v2(data);
                setTimeout(function(){
                    if(data.error==0)
                    {
                        window.location.reload();
                    }
                },2000);

            });
        });
    });
</script>