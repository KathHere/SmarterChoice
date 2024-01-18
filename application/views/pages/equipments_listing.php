<style type="text/css">

#assign_equip_filter input
{
  margin-left: 13px;
}

select.input-sm
{
  margin-left: 11px;
  margin-right: 11px;
}

.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
	background-color: #C8C8C8 !important;
	color: #fff !important;
	/*font-weight: bold;*/
}

</style>

<div class="bg-light lter b-b wrapper-md">
	<div class="row">
		<h1 class="m-n font-thin h3 col-sm-4">Fee Schedule</h1>
	    <div class="pull-right header-filter col-sm-8">
	      <div class="form-group">
	          <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'distribution_supervisor') :?>
	            <label class="col-sm-6 hidden-xs control-label mt10 text-right">Account:</label>
	            <label class="col-sm-2 visible-xs-block control-label mt10 text-right"><i class="fa fa-filter"></i></label>
	            <div class="col-sm-6 header-filter-option hidden-xs">
	                <select name="hospice_sorting_id" class="form-control m-b  select_sort_itemList_by select2-ready" id="">
	                  <?php
	                  $hospices = account_list_by_status($this->session->userdata('user_location'), 1);
	                  ?>
	                  <?php
	                    if (!empty($hospices)) {
	                        ?>
	                        <?php
	                          foreach ($hospices as $hospice) :
	                            if ($hospice['hospiceID'] != 13) {
	                                ?>
	                              <option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospiceID == $hospice['hospiceID']) {
	                                    echo 'selected';
	                                } ?> ><?php echo $hospice['hospice_name']; ?></option>
	                        <?php
	                            }
	                        endforeach; ?>
	                  <?php
	                    }
	          ?>

	                      <option disabled="disabled">----------------------------------------</option>
	                  <?php
	      				foreach ($hospices as $hospice) :
	                      if ($hospice['hospiceID'] == 13) {
	                          ?>
	                        <option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospice_selected == $hospice['hospiceID']) {
	                              echo 'selected';
	                          } ?> ><?php echo $hospice['hospice_name']; ?></option>
	                  <?php
	                      }
	      				endforeach;
	      			?>

	                </select>
	            </div>
	          <?php endif; ?>
	      </div>
	    </div>

	</div>


</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">List of All DME Items
    	<!-- <?php if($this->session->userdata("account_type") == "dme_admin" || $this->session->userdata('account_type') == 'dme_user') :?>
	    	<a href="javascript:void(0)" class="" data-id="" style="text-decoration:none">
				<button class="btn btn-info btn-sm pull-right" data-toggle="modal" data-target="#add_equip" style="margin-top:-5px;">
					<i class="glyphicon glyphicon-plus" style=""></i> Add New Item
				</button>
			</a>
		<?php endif;?> -->
    </div>

    <div class="row" style="padding-left: 15px !important; padding-right: 15px !important">
		<div class="col-md-12 mt20 mb5">
			<strong>Daily Rate:
				<?php
				if ($current_hospice[0]['daily_rate'] == 0 || $current_hospice[0]['daily_rate'] == '') {
					echo "0.00";
				} else {
					echo number_format((float)$current_hospice[0]['daily_rate'], 2, '.', '');
				}
				?>
			</strong>
		</div>
	</div>

	<div class="row" style="padding-left: 15px !important; padding-right: 15px !important">
		<div class="col-md-6 mt20 mb5">
			<label>Account Name: </label>
			<input type="text" class="form-control" name="hospice_name" value="<?php echo $current_hospice[0]['hospice_name'] ?>" style="margin-bottom:15px; background: white;" readonly />
			<input type="hidden" class="form-control" name="hospiceID" value="<?php echo $current_hospice[0]['hospiceID'] ?>" />
		</div>
		<div class="col-md-6 mt20 mb5">
			<label>Account Number: </label>
			<input type="text" class="form-control" name="" value="<?php echo $current_hospice[0]['hospiceID']?>" style="margin-bottom:15px; background: white;" readonly />
		</div>
	</div>

    <div class="table-responsive">
      <table id="assign_equip" class="table table-striped m-b-none">
        <thead>
            <tr>
                <tr>
					<th><h5>Item Name</h5></th>
					<th><h5>Category</h5></th>
					<?php if($this->session->userdata("account_type") == "dme_admin" || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata("account_type") == "hospice_admin" || $this->session->userdata("account_type") == "company_admin" || $this->session->userdata("account_type") == "dme_user" || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'distribution_supervisor') :?>
						<!-- <th><h5>Actions</h5></th> -->
						<th><h5>Fee Schedule</h5></th>
					<?php endif;?>
				</tr>
            </tr>
        </thead>
        <tbody>
        	<?php
        		if(!empty($equipments_v3)) :
					foreach ($equipments_v3 as $equipment) :
						if($equipment['equipmentID'] != 316 && $equipment['equipmentID'] != 325 && $equipment['equipmentID'] != 334 && $equipment['equipmentID'] != 343 && $equipment['equipmentID'] != 457 && $equipment['equipmentID'] != 458)
						{
			?>
				       		<tr>
								<td ><?php echo $equipment['key_desc'] ?></td>
								<td ><?php echo $equipment['type'] ?></td>
								<?php if($this->session->userdata("account_type") == "dme_admin" || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata("account_type") == "hospice_admin" || $this->session->userdata("account_type") == "company_admin" || $this->session->userdata("account_type") == "dme_user") :?>
									<!-- <td>
										<a href="javascript:void(0)" class=""  style="text-decoration:none">
											<a href="javascript:void(0)" class="edit-equip" data-id="<?php echo $equipment['equipmentID'] ?>" style="text-decoration:none">
												<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_equip<?php echo $equipment['equipmentID'] ?>">
													<i class="glyphicon glyphicon-pencil" style=""></i> Edit
												</button>
											</a>
										</a>

										<a href="javascript:void(0)" class=""  style="text-decoration:none">
											<a href="javascript:void(0)" class="delete-equip" data-id="<?php echo $equipment['equipmentID'] ?>">
												<button class="btn btn-danger btn-sm">
													<i class="glyphicon glyphicon-trash"></i> Delete
												</button>
											</a>
										</a>
									</td> -->

								<?php endif;?>
								<td>
										<a href="javascript:void(0)" class=""  style="text-decoration:none">
											<a href="javascript:void(0)" class="edit-equip" data-id="<?php echo $equipment['equipmentID'] ?>">
												<button class="btn btn-info btn-sm itemgroup_btn" data-toggle="modal" data-target="#view_equip<?php echo $equipment['equipmentID'] ?>"  data-assigned-equipment-id="<?php echo $equipment['ID']; ?>" data-equipment-id="<?php echo $equipment['equipmentID']; ?>" data-category-id="<?php echo $equipment['categoryID']; ?>">
													<i class="glyphicon glyphicon-eye-open"></i> View
												</button>
											</a>
										</a>
									</td>
							</tr>
			<?php
						}
					endforeach;
			 	endif;
			?> <!-- End sa condition para sa dili empty nga array :) -->
        </tbody>
      </table>
    </div>
  </div>
</div>



<?php if(!empty($equipments)) : ?>
<?php foreach ($equipments as $equipment) : ?>
<!-- Modal -->
<div class="modal fade" id="edit_equip<?php echo $equipment['equipmentID'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top:100px;overflow:hidden">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $equipment['key_desc'] ?></h4>
      </div>
      <?php echo form_open("",array("id"=>"edit-equip-form".$equipment['equipmentID'])) ?>

	      <div class="modal-body">
	        	<div class="form-group">
			    <label for="exampleInputEmail1">Item Name</label>
			    <textarea name="description" class="form-control key_desc_id" id="" placeholder="" value="<?php echo $equipment['key_desc'] ?>"><?php echo $equipment['key_desc'] ?></textarea>
			  </div>

			  <div class="form-group">
			    <label for="exampleInputPassword1">Category</label>
			    <select class="form-control" name="category">
			    	<?php if($equipment['categoryID'] == 1) :?>
			    		<option value="1">Capped Item</option>
			    		<option value="2">Non-Capped Item</option>
			    		<option value="3">Disposable Items</option>
			    	<?php elseif($equipment['categoryID'] == 2):?>
			    		<option value="2">Non-Capped Item</option>
			    		<option value="1">Capped Item</option>
			    		<option value="3">Disposable Items</option>
			    	<?php else:?>
			    		<option value="3">Disposable Items</option>
			    		<option value="1">Capped Item</option>
			    		<option value="2">Non-Capped Item</option>
			    	<?php endif;?>
			    </select>
			    <!-- <input type="text" name="category" class="form-control" id="" placeholder="" value="<?php echo $equipment['type'] ?>" readonly> -->
			  </div>
			  <input type="hidden" name="key_name" value="<?php echo $equipment['key_name'] ?>" class="" />
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary equip_update_btn" data-id="<?php echo $equipment['equipmentID'] ?>">Save changes</button>
	      </div>
	 <?php echo form_close() ?>
    </div>
  </div>
</div>
<?php endforeach; ?> <!-- End sa foreach adtu sa taas :) -->
<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->

<?php if(!empty($equipments_v3)) : ?>
<?php foreach ($equipments_v3 as $equipment) : ?>
<!-- Modal -->
<div class="modal fade" id="view_equip<?php echo $equipment['equipmentID'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top:100px;overflow:hidden">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $equipment['key_desc'] ?></h4>
      </div>
      <?php echo form_open("",array("id"=>"edit-equip-form".$equipment['equipmentID'])) ?>

	      <div class="modal-body">
		  <?php
  				if($equipment['equipmentID'] == 49) {
	  		?>
	  			<ul class="nav nav-tabs">
				    <li class="active"><a data-toggle="tab" href="#w16">16" NARROW</a></li>
				    <li><a data-toggle="tab" href="#w18">18" STANDARD</a></li>
				    <li><a data-toggle="tab" href="#w20">20" WIDE</a></li>
				    <li><a data-toggle="tab" href="#w22">22" EXTRA WIDE</a></li>
				    <li><a data-toggle="tab" href="#w24">24" BARIATRIC</a></li>
			  	</ul>
			  	<div class="tab-content" style="margin-top: 20px;">
		  			<input type="hidden" name="subequipment[]" value="16_inch" />
				    <div id="w16" class="tab-pane fade in active">
			    		<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 16_inch_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 16_inch_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 16_inch_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
				    <input type="hidden" name="subequipment[]" value="18_inch" />
				    <div id="w18" class="tab-pane fade">
				    	<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 18_inch_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 18_inch_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 18_inch_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
				    <input type="hidden" name="subequipment[]" value="20_inch" />
				    <div id="w20" class="tab-pane fade">
				    	<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 20_inch_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 20_inch_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 20_inch_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
				    <input type="hidden" name="subequipment[]" value="22_inch" />
				    <div id="w22" class="tab-pane fade">
				    	<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 22_inch_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 22_inch_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 22_inch_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
				    <input type="hidden" name="subequipment[]" value="24_inch" />
				    <div id="w24" class="tab-pane fade">
				    	<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 24_inch_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 24_inch_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 24_inch_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
					</div>
			  	</div>
	  		<?php
	  			}
	  			else if($equipment['equipmentID'] == 64) {
	  		?>
	  			<ul class="nav nav-tabs">
				    <li class="active"><a data-toggle="tab" href="#wr16">16" NARROW</a></li>
				    <li><a data-toggle="tab" href="#wr18">18" STANDARD</a></li>
				    <li><a data-toggle="tab" href="#wr20">20" WIDE</a></li>
				    <!-- <li><a data-toggle="tab" href="#wr22">22" EXTRA WIDE</a></li>
				    <li><a data-toggle="tab" href="#wr24">24" BARIATRIC</a></li> -->
			  	</ul>
			  	<div class="tab-content" style="margin-top: 20px;">
				    <input type="hidden" name="subequipment[]" value="16_inch" />
				    <div id="wr16" class="tab-pane fade in active">
			    		<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 16_inchr_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 16_inchr_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 16_inchr_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
				    <input type="hidden" name="subequipment[]" value="18_inch" />
				    <div id="wr18" class="tab-pane fade">
				    	<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 18_inchr_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 18_inchr_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 18_inchr_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
				    <input type="hidden" name="subequipment[]" value="20_inch" />
				    <div id="wr20" class="tab-pane fade">
				    	<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 20_inchr_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 20_inchr_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 20_inchr_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
			  	</div>
	  		<?php
	  			}
	  			else if($equipment['equipmentID'] == 32){
	  		?>
	  			<ul class="nav nav-tabs">
				    <li class="active"><a data-toggle="tab" href="#ec6r">E CYLINDER - 6 RACK</a></li>
				    <li><a data-toggle="tab" href="#ec12r">E CYLINDER - 12 RACK</a></li>
				    <li><a data-toggle="tab" href="#mc6r">M6 CYLINDER - 6 RACK</a></li>
				    <li><a data-toggle="tab" href="#mc12r">M6 CYLINDER - 12 RACK</a></li>
			  	</ul>
			  	<div class="tab-content" style="margin-top: 20px;">
		  			<input type="hidden" name="subequipment[]" value="e_cylinder_6_rack" />
				    <div id="ec6r" class="tab-pane fade in active">
			    		<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag e_cylinder_6_rack_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag e_cylinder_6_rack_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag e_cylinder_6_rack_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
				    <input type="hidden" name="subequipment[]" value="e_cylinder_12_rack" />
				    <div id="ec12r" class="tab-pane fade">
				    	<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag e_cylinder_12_rack_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag e_cylinder_12_rack_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag e_cylinder_12_rack_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
				    <input type="hidden" name="subequipment[]" onkeypress="return isNumberKey(event)" value="m6_cylinder_6_rack" />
				    <div id="mc6r" class="tab-pane fade">
				    	<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag m6_cylinder_6_rack_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag m6_cylinder_6_rack_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag m6_cylinder_6_rack_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
				    <input type="hidden" name="subequipment[]" onkeypress="return isNumberKey(event)" value="m6_cylinder_12_rack" />
				    <div id="mc12r" class="tab-pane fade" disabled>
				    	<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag m6_cylinder_12_rack_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag m6_cylinder_12_rack_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag m6_cylinder_12_rack_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
			  	</div>
			<?php
	  			}
	  			else if($equipment['equipmentID'] == 29){
	  		?>
	  			<ul class="nav nav-tabs">
				    <li class="active"><a data-toggle="tab" href="#5L">5L OXYGEN CONCENTRATOR</a></li>
				    <li><a data-toggle="tab" href="#10L">10L OXYGEN CONCENTRATOR</a></li>
			  	</ul>
			  	<div class="tab-content" style="margin-top: 20px;">
				    <div id="5L" class="tab-pane fade in active">
			    		<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 5_liters_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 5_liters_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 5_liters_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
				    <input type="hidden" name="subequipment[]" value="10_liters" />
				    <div id="10L" class="tab-pane fade">
				    	<div class="form-group">
						    <label for="exampleInputEmail1">Monthly Rate</label>
						    <input type="text" name="monthlyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 10_liters_monthlyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Daily Rate</label>
						    <input type="text" name="dailyrate[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 10_liters_dailyrate" id="" placeholder="" value="0.0" disabled>
					  	</div>
					  	<div class="form-group">
						    <label for="exampleInputEmail1">Purchase Price</label>
						    <input type="text" name="purchaseprice[]" onkeypress="return isNumberKey(event)" class="form-control inputtag 10_liters_purchaseprice" id="" placeholder="" value="0.0" disabled>
					  	</div>
				    </div>
			  	</div>
	  		<?php
	  			}
	  			else {
	  		?>
	        	<div class="form-group">
				    <label for="exampleInputEmail1">Monthly Rate</label>
				    <input type="text" name="description" class="form-control key_desc_id" id="" placeholder="" value="<?php echo number_format((float)$equipment['monthly_rate'], 2, '.', ''); ?>" disabled>
			  	</div>
			  	<div class="form-group">
				    <label for="exampleInputEmail1">Daily Rate</label>
				    <input type="text" name="description" class="form-control key_desc_id" id="" placeholder="" value="<?php echo number_format((float)$equipment['daily_rate'], 2, '.', ''); ?>" disabled>
			  	</div>
			  	<div class="form-group">
				    <label for="exampleInputEmail1">Purchase Price</label>
				    <input type="text" name="description" class="form-control key_desc_id" id="" placeholder="" value="<?php echo number_format((float)$equipment['purchase_price'], 2, '.', ''); ?>" disabled>
			  	</div>
			<?php } ?>
			    <!-- <input type="text" name="category" class="form-control" id="" placeholder="" value="<?php echo $equipment['type'] ?>" readonly> -->
			  <input type="hidden" name="key_name" value="<?php echo $equipment['key_name'] ?>" class="" />
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <!-- <button type="button" class="btn btn-primary equip_update_btn" data-id="<?php echo $equipment['equipmentID'] ?>">Save changes</button> -->
	      </div>
	 <?php echo form_close() ?>
    </div>
  </div>
</div>
<?php endforeach; ?> <!-- End sa foreach adtu sa taas :) -->
<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->


<!--Add Equipment Modal -->
<div class="modal fade" id="add_equip" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-top:75px;">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Item</h4>
      </div>
     	<?php echo form_open("",array("id"=>"add-equip-form")) ?>
	      <div class="modal-body">
        	<div class="form-group">
			    <label for="exampleInputEmail1">Item Name:</label>
			    <textarea name="key_desc" class="form-control" id="equip_name" placeholder="" value="" style="text-transform:none"></textarea>
			</div>
			<div class="form-group">
		    	<label for="exampleInputPassword1">Choose Category:</label>
			    <select class="form-control category_dropdown" name="cat_id" id="cat_select">
			    	<option value="">- Please Choose -</option>
		    	<?php if(!empty($categories)) :?>
	  			<?php foreach($categories as $category) :?>
			    	<option value="<?php echo $category['categoryID'] ?>"><?php echo $category['type'] ?></option>
			   	<?php endforeach ?>
				<?php endif ;?>
			    </select>
			</div>

		  	<input type="hidden" name="key_name" value="" class="hdn_key_name" style="text-transform:none" />
		  	<input type="hidden" name="cat_name" value="" class="hdn_cat_name" style="text-transform:none" />

	      </div>
	      <div class="modal-footer" style="">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary btn-add-equipment">Add Item</button>
	      </div>
	    <?php echo form_close() ?>
    </div>
  </div>
</div>


<div class="bg-light lter wrapper-md">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<script type="text/javascript">
	$(document).ready(function(){
	    $('.itemgroup_btn').bind('click',function(){
	    	var equipment_id = $(this).attr('data-equipment-id');
	    	var assigned_equipment_id = $(this).attr('data-assigned-equipment-id');
	    	var category_id = parseInt($(this).attr('data-category-id'));
	    	var disablePurchase = false;
	    	var disableMonthly = false;
	    	var disableDaily = false;

	    	if(category_id == 1 || category_id == 2) {
				disablePurchase = true;
			}

			if(category_id == 3) {
				disableMonthly = true;
				disableDaily = true;
			}

	    	$('#equipmentID_'+equipment_id).val(equipment_id);
	    	$('#assigned_equipmentID_'+equipment_id).val(assigned_equipment_id);
	    	$('#submit_itemgroup_'+equipment_id).attr('data-assigned-equipment-id', assigned_equipment_id);
	    	$('#submit_itemgroup_'+equipment_id).attr('data-equipment-id', equipment_id);

	    	
	    	$.get(base_url+'equipment/get_itemgroup_equipment_rates/'+assigned_equipment_id, function(response){
                console.log("resepposnse:", response);
                var obj = $.parseJSON(response);
                console.log("closecloseclose:", obj);
                // $('.close').click();
                
                if(obj.length > 0) {
                	for(var i = 0; i < obj.length; i++) {
	            		var val = obj[i];
	            		var reclining = "";

	            		if(val.equipmentID == 64) {
	            			reclining = "r";
	            		}

	            		$('.' + val.key_name + reclining + "_monthlyrate").val(parseFloat(val.monthly_rate).toFixed(2));
	            		$('.' + val.key_name + reclining + "_monthlyrate").attr("readonly", disableMonthly);

	            		$('.' + val.key_name + reclining + "_dailyrate").val(parseFloat(val.daily_rate).toFixed(2));
	            		$('.' + val.key_name + reclining + "_dailyrate").attr("readonly", disableDaily);

	            		$('.' + val.key_name + reclining + "_purchaseprice").val(parseFloat(val.purchase_price).toFixed(2));
	            		$('.' + val.key_name + reclining + "_purchaseprice").attr("readonly", disablePurchase);
	            	}
                	
                } else {
                	var mon = $('.tab-content-'+equipment_id).find('.mon');
                	var dail = $('.tab-content-'+equipment_id).find('.dail');
                	var purch = $('.tab-content-'+equipment_id).find('.purch');

                	mon.each(function() {
                		console.log('mon', $(this).val());
                		$(this).val("0.0");
                		$(this).attr("readonly", disableMonthly);
                	});

                	dail.each(function() {
                		console.log('dail', $(this).val());
                		$(this).val("0.0");
                		$(this).attr("readonly", disableDaily);
                	});

                	purch.each(function() {
                		console.log('purch', $(this).val());
                		$(this).val("0.0");
                		$(this).attr("readonly", disablePurchase);
                	});
                }
               
                
            });
	    });
	});
</script>