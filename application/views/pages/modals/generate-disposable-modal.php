<!-- Disposable generated -->
<?php 
	foreach($disposal_equipments as $val): 

		$rAttributes = get_equipment_attributes($val['equipmentID']);
		$attributes  = $rAttributes[0];
        if($val['equipmentID'] != 306  && $val['equipmentID'] != 170 && $val['equipmentID'] != 156 && $val['equipmentID'] != 143 && $val['equipmentID'] != 168 && $val['equipmentID'] != 169 && $val['equipmentID'] != 163 && $val['equipmentID'] != 141 && $val['equipmentID'] != 155 && $val['equipmentID'] != 144 && $val['equipmentID'] != 145 && $val['equipmentID'] != 164 && $val['equipmentID'] != 157 && $val['equipmentID'] != 409 && $val['equipmentID'] != 146 && $val['equipmentID'] != 147 && $val['equipmentID'] != 148 && $val['equipmentID'] != 149 && $val['equipmentID'] != 150 && $val['equipmentID'] != 151 && $val['equipmentID'] != 245 && $val['equipmentID'] != 158 && $val['equipmentID'] != 152 && $val['equipmentID'] != 166 && $val['equipmentID'] != 167 && $val['equipmentID'] != 159 && $val['equipmentID'] != 160 && $val['equipmentID'] != 161 && $val['equipmentID'] != 165 && $val['equipmentID'] != 153 && $val['equipmentID'] != 154 && $val['equipmentID'] != 142 && $val['equipmentID'] != 162 && $val['equipmentID'] != 267 && $val['equipmentID'] != 451 && $val['equipmentID'] != 257 && $val['equipmentID'] != 255 && $val['equipmentID'] != 261 && $val['equipmentID'] != 251 && $val['equipmentID'] != 249 && $val['equipmentID'] != 253 && $val['equipmentID'] != 259 && $val['equipmentID'] != 7 && $val['equipmentID'] != 178 && $val['equipmentID'] != 1 && $val['equipmentID'] != 247 && $val['equipmentID'] != 265 && $val['equipmentID'] != 263 && $val['equipmentID'] != 263 && $val['equipmentID'] != 274 && $val['equipmentID'] != 11)
        {
?>
            <div class="modal fade modal_<?php echo $val['key_name'];?>" id="<?php echo $val['key_name'];?>_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                        <div class="modal-header">
                          <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                            <h4 class="modal-title OpenSans-Reg" id="myModalLabel"><?php echo $val['key_desc'];?></h4>
                        </div>
                        <div class="modal-body OpenSans-Reg equipments_modal">
                            <div class="row">
                                <div class="">
                                    <div class="col-md-12"> 
                                        <label>Quantity <span style="color:red;">*</span></label>
                                        <div class="">
                                            <label class="block">
                                                <input type="text" data-desc="Quantity" name="subequipment[<?php echo $val['equipmentID'];?>][<?php echo $attributes['equipmentID']; ?>]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                            <button type="button" class="btn btn-primary btn-order">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
<?php 
    }
endforeach; 
?>