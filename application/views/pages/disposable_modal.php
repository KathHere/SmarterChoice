<?php 
    $id = $equip_id;
    $sub_ids = $quantity_ids;

?>
<div class="row">
    <div class="">
        <div class="col-md-12">
            <label>Quantity <span style="color:red;">*</span></label>
            <div class="">
                <label>
                <?php foreach($sub_ids as $sub_id) :?>
                    <input type="text" data-desc="Quantity" name="subequipment[<?php echo $id ?>][<?php echo $sub_id ?>]" class="form-control " id="" placeholder="Enter Quantity" style="margin-bottom:31px;">
                <?php endforeach ;?>
                </label>
            </div>
            <button class="btn btn-primary pull-right btn-disposable-order">Save Changes</button>
        </div>
    </div>
</div>