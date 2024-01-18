<style type="text/css">
.billing_email_cc {
        width: 10%;
        text-align: center;
        padding: 6px;
        border: 1px solid rgb(207, 218, 221);
        border-radius: 0px 2px 2px 0px;
        height: 35px;
        border-left: 0px;
        cursor: pointer;
    }

    .bootstrap-tagsinput input {
        background: none;
        border: none;
    }
input[type="search"]
{
  margin-left: 13px;
}

select.input-sm
{
  margin-left: 11px;
  margin-right: 11px;
}
.col-md-6{
    display: inline-block;
    width:50%;
}
h1{
    top:-10px !important;
    position:relative;
}
.header-filter-option{
    margin-bottom: 0px;
}

.disable-input-tags div {
    cursor: not-allowed;
    background-color: #eee;
    opacity: 1;
}

/********************************************************************************************************
* STYLES FOR PRINTING
********************************************************************************************************/
@media print{

    .create_random_account_number {
        display:none !important;
    }

    .assign_item_href {
        display:none !important;
    }
}

</style>

<div class="bg-light lter b-b wrapper-md">
    <div class="row">
        <div class="col-md-6">
            <div class="" style="padding-top:15px;">
                <h1 class="m-n font-thin h3">Account List</h1>
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-right header-filter ">
                <div class=" row">
                    <label class="col-md-8 col-xs-2 control-label mt10 text-right">Sort:</label>
                    <div class="col-md-4 col-xs-10 col-sm-10 header-filter-option">
                        <select class="form-control sort_account_by_activation_status">
                            <option value="" <?php if ($current_status == '') { echo 'selected'; } ?>> All </option>
                            <option value="active" <?php if ($current_status == 'active') { echo 'selected'; } ?>> Active </option>
                            <option value="inactive" <?php if ($current_status == 'inactive') { echo 'selected'; } ?>> Inactive </option>
                            <option value="suspended" <?php if ($current_status == 'suspended') { echo 'selected'; } ?>> Suspended </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">
      Account List
    </div>

    <div class="table-responsive">
      <table class="table m-b-none <?php if(!empty($hospices)) { echo 'datatable_table'; } ?> account_list">
        <thead>
          <tr>
              <th class="">Account ID</th>
              <th class="">Account #</th>
              <th class="">Account Name</th>
              <th class="">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if(!empty($hospices)){
              foreach ($hospices as $hospice):
          ?>
                <tr>
                  <td class=""><?php echo $hospice->hospiceID ?></td>
                  <td class=""><?php echo $hospice->hospice_account_number ?></td>
                  <td class=""><?php echo $hospice->hospice_name ?></td>
                  
                  <td class="">
                    <button type="button" class="btn btn-info btn-xs create_random_account_number edit_hospice_account_details" data-toggle="modal" data-target="#edit_hospice<?php echo $hospice->hospiceID ?>">
                        <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') { ?>
                            <i class="glyphicon glyphicon-pencil"></i> Edit
                        <?php } else { ?>
                            <i class="glyphicon glyphicon-eye-open"></i> View
                        <?php } ?>
                    </button>
                    <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') { ?>
                        <a class="assign_item_href" href="<?php echo base_url('equipment/list_equipments/'.get_code($hospice->hospiceID)) ?>">
                        <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-file"></i> Assign Item</button>
                        </a>
                        <?php
                            if ($hospice->account_active_sign == 1) {
                        ?>
                                <select class="form-control change_account_status" style="height:23px;margin-top:-4px;width:105px;" data-id="<?php echo $hospice->hospiceID ?>">
                                    <option value="" selected>Select</option>
                                    <option value="0">Make Inactive</option>
                                    <option value="2">Suspend</option>
                                </select>
                        <?php
                            } else if ($hospice->account_active_sign == 0) {
                        ?>
                                <select class="form-control change_account_status" style="height:23px;margin-top:-4px;width:105px;" data-id="<?php echo $hospice->hospiceID ?>">
                                    <option value="" selected>Select</option>
                                    <option value="1">Reactivate</option>
                                    <option value="2">Suspend</option>
                                </select>
                        <?php
                            } else if ($hospice->account_active_sign == 2) {
                        ?>
                                <select class="form-control change_account_status" style="height:23px;margin-top:-4px;width:105px;" data-id="<?php echo $hospice->hospiceID ?>">
                                    <option value="" selected>Select</option>
                                    <option value="0">Make Inactive</option>
                                    <option value="1">Resume</option>
                                </select>
                        <?php
                            }
                        ?>
                    <?php } ?>
                  </td>
                </tr>
          <?php
              endforeach;
            }else{
          ?>
              <tr>
                <td colspan="4" style="text-align: center;">No data.</td>
              </tr>
          <?php
            }
          ?> <!-- End sa condition para sa dili empty nga array :) -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="bg-light lter wrapper-md">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<?php
    $disabled = '';
    $disabled_tagsinput = '';
    if ($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user') {
        $disabled = 'disabled';
        $disabled_tagsinput = 'disable-input-tags';
    }
?>

<?php if(!empty($hospices)) : ?>
  <?php foreach ($hospices as $hospice) : ?>

<div class="modal fade edit_hospice" id="edit_hospice<?php echo $hospice->hospiceID ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 9998 !important">
  <div class="modal-dialog" style="width: 1200px">
    <div class="modal-content" style="width: 1200px; margin-top:90px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title OpenSans-Reg" id="myModalLabel">
                <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') { ?>
                Edit Account Form
                <?php } else { ?>
                Account Information
                <?php } ?>
            </h4>
        </div>
        <div class="modal-body OpenSans-Reg" style="padding:50px; padding-top: 0px !important; padding-bottom: 0px !important">
            <div class="form-group">
                <div class="col-sm-12" style="margin-top:10px; margin-bottom:-10px;">
                    <label style="margin-left:-13px">Account Type <span class="text-danger-dker">*</span></label>
                </div>
                <?php
                    $checked_track = "";
                    if($hospice->track_census == 0) {
                        $checked_track = "checked";
                    }
                ?>
                <div class="col-sm-12" style="margin-bottom:20px;">
                   <form action="<?php echo base_url('hospice/update_hospice/'.get_code($hospice->hospiceID)) ;?>" method="POST" id="">
                    <input type="hidden" value="<?php echo $current_status; ?>" name="current_viewed_status" />
                    <div class="col-sm-2" style="margin-top:5px">
                        <div class="radio">
                            <label class="i-checks">
                                <input <?php echo $disabled; ?> type="radio" name="hospice_rad" id="choose_hospice_account" class="choose_account_type track_census_btn"  value="" <?php echo $checked_track; ?>><i></i>Track Customer Days
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-6" style="">
                    <div class="form-container" style="border-radius:4px;">

                        <input type="hidden" name="is_track_census" id="choose_track_census" class="hidden_track_census" value="<?php echo $hospice->track_census; ?>" />
                        <input type="hidden" name="choose_account_type_value" value="0">
                        <!-- <input type="hidden" name="account_location" value="<?php echo $this->session->userdata('user_location'); ?>"> -->
                        <div class="form-group" >

                            <label for="exampleInputEmail1">Payment Terms <span class="text-danger-dker">*</span></label>
                            <div class="col-sm-12" style="margin-bottom:20px;">
                                <div class="col-sm-4" style="margin-top:5px">
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input <?php echo $disabled; ?> type="radio" name="choose_payment_terms" id="" class="choose_account_type" value="0"
                                            <?php if($hospice->payment_terms == "30_days") {
                                                echo "checked";
                                            }?>
                                            ><i></i>Net 30 Days
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4" style="margin-top:5px;">
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input <?php echo $disabled; ?> type="radio" name="choose_payment_terms" id="" class="choose_account_type" value="1"
                                            <?php if($hospice->payment_terms == "60_days") {
                                                echo "checked";
                                            }?>
                                            ><i></i>Net 60 Days
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4" style="margin-top:5px;">
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input <?php echo $disabled; ?> type="radio" name="choose_payment_terms" id="" class="choose_account_type" value="2"
                                            <?php if($hospice->payment_terms == "90_days") {
                                                echo "checked";
                                            }?>
                                            ><i></i>Net 90 Days
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Account Number</label>

                          <?php if($hospice->hospice_account_number != 0) :?>
                            <input <?php echo $disabled; ?> type="text" name="hosp_acct_number" class="form-control" id="" placeholder="" value="<?php echo $hospice->hospice_account_number ?>" readonly>
                          <?php else:?>
                            <input <?php echo $disabled; ?> type="text" name="hosp_acct_number" class="form-control edit_hospice_account_num" id="" placeholder="" readonly>
                          <?php endif;?>

                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Date of Service</label>
                          <input <?php echo $disabled; ?> type="text" name="date_of_service" class="form-control datepicker" id="" placeholder="" value="<?php echo $hospice->date_of_service ?>" style="margin-left:0px">
                        </div>

                        <div class="form-group">
                          <label for="exampleInputEmail1">Name</label>
                          <textarea <?php echo $disabled; ?> name="hospice_name" class="form-control" id="" placeholder="" style="resize: none"><?php echo $hospice->hospice_name ?></textarea>
                        </div>


                        <div class="form-group">
                          <label for="exampleInputEmail1">Phone Number</label>
                          <input <?php echo $disabled; ?> type="text" name="hospice_contact_num" class="form-control hosp_contact_num" id="" placeholder="" value="<?php echo $hospice->contact_num ?>">
                        </div>


                        <!-- <div class="form-group">
                          <label for="exampleInputEmail1">Account Billing Address</label>
                          <input type="text" name="hospice_address" class="form-control " id="" placeholder="" value="<?php echo $hospice->hospice_address ?>">
                        </div> -->

                        <div class="form-group">
                            <label for="exampleInputEmail1">Billing Address <span class="text-danger-dker">*</span></label>
                            <!-- <input type="text" name="hospice_billing_address" class="form-control " id="" placeholder=""> -->
                            <input <?php echo $disabled; ?> type="text" class="form-control" id="b_add" placeholder="Enter Address" name="b_address" style="margin-bottom:20px;" tabindex="19" value="<?php echo $hospice->b_street?>">
                            <input <?php echo $disabled; ?> type="text" class="form-control" id="b_placenum" placeholder="Apartment No., Room No. , Unit No." name="b_placenum" style="margin-bottom:20px;" tabindex="21" value="<?php echo $hospice->b_placenum; ?>">

                            <div class="row" style="margin-bottom:20px;">
                                <div class="col-md-6">
                                    <input <?php echo $disabled; ?> type="text" class="edit_hospice_b_city_<?php echo $hospice->hospiceID; ?> form-control ng-pristine ng-invalid ng-invalid-required" id="" placeholder="City" name="b_city" tabindex="22" value="<?php echo $hospice->b_city; ?>">

                                </div>
                                <div class="col-md-6">
                                    <input <?php echo $disabled; ?> type="text" class="edit_hospice_b_state_<?php echo $hospice->hospiceID; ?> form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" id="" placeholder="State / Province" name="b_state" tabindex="23" value="<?php echo $hospice->b_state; ?>">

                                </div>
                            </div>
                            <input <?php echo $disabled; ?> type="number" class="form-control grey_inner_shadow edit_hospice_b_postal" id="" data-hospice-id="<?php echo $hospice->hospiceID; ?>" onkeypress="return isNumberKey(event)" placeholder="Postal Code" name="b_postalcode" tabindex="24" value="<?php echo $hospice->b_postalcode; ?>">
                        </div>

                        <div class="form-group billing_email_wrapper" style="">
                            <label for="exampleInputEmail1">Billing Email <span class="text-danger-dker">*</span></label>
                            <div class="row billing_email_container" style="margin-left: 0px; margin-right: 0px; ">
                                <div class="col-md-11 billing_email_input <?php echo $disabled_tagsinput; ?>" style="width:90%; padding: 0px">
                                    <input <?php echo $disabled; ?> type="text" name="billing_email" class="col-md-11 billing_email_input_tag" id="" placeholder="" style="width: 90%" data-role="tagsinput" value="<?php echo $hospice->billing_email;?>">
                                </div>
                                 
                                <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') { ?>
                                <div class="col-md-1 billing_email_cc " style="">CC</div>
                                <?php } ?>
                            </div>


                        </div>
                        <div class="form-group billing_email_cc_input">
                            <label for="exampleInputEmail1">Billing Email CC</label>
                            <div class="col-md-11 billing_email_input <?php echo $disabled_tagsinput; ?>" style="width:90%; padding: 0px; margin-bottom: 20px;">
                                <input <?php echo $disabled; ?> type="text" name="billing_email_cc" class="form-control " id="" placeholder="" style="width: 90%" data-role="tagsinput" value="<?php echo $hospice->billing_email_cc; ?>">
                            </div>
                        </div>

                        <hr />
                    </div>
                </div>
                <div class="col-md-6" style="">

                    <div class="form-container" style="border-radius:4px;">
                        <!-- <form action="<?php echo base_url('hospice/update_hospice/'.get_code($hospice->hospiceID)) ;?>" method="POST" id=""> -->
                        <div class="form-group" >
                            <label for="exampleInputEmail1">Send Invoice to <span class="text-danger-dker">*</span></label>
                            <div class="col-sm-12" style="margin-bottom:20px;">
                                <div class="col-sm-4" style="margin-top:5px">
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input <?php echo $disabled; ?> type="radio" name="choose_invoice_to" id="" class="choose_account_type"  value="0"
                                            <?php
                                                if($hospice->invoice_to == 0) {
                                                    echo "checked";
                                                }
                                            ?>
                                            ><i></i>Shipping Address
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4" style="margin-top:5px;">
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input <?php echo $disabled; ?> type="radio" name="choose_invoice_to" id="" class="choose_account_type" value="1"
                                            <?php
                                                if($hospice->invoice_to == 1) {
                                                    echo "checked";
                                                }
                                            ?>
                                            ><i></i>Billing Address
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4" style="margin-top:5px;">

                                </div>
                            </div>
                        </div>


                        <!-- <div class="form-group">
                          <label for="exampleInputEmail1">Account Shipping Address</label>
                          <input type="text" name="hospice_address" class="form-control " id="" placeholder="" value="<?php echo $hospice->hospice_address ?>">
                        </div> -->
                        <div class="form-group" style="">
                            <label for="exampleInputEmail1">Associated Service Location <span class="text-danger-dker">*</span></label>
                            <select <?php echo $disabled; ?> name="associated_account_location" class="form-control m-b" id="">
                                <option value="">- Please choose -</option>
                             <?php
                                $service_locations = get_service_location();
                                foreach($service_locations as $value){
                                    if($hospice->account_location == $value['location_id']) {
                             ?>
                             <option value="<?php echo $value['location_id']; ?>" selected>
                                <?php echo $value['location_name']; ?>, <?php echo $value['service_location_id']?>
                             </option>
                             <?php
                                    } else {
                            ?>
                            <option value="<?php echo $value['location_id']; ?>">
                                <?php echo $value['location_name']; ?>, <?php echo $value['service_location_id']?>
                             </option>
                            <?php
                                    }
                                }
                             ?>
                            </select>
                        </div>
                        <div class="form-group" style="">
                            <label for="exampleInputEmail1">Shipping Address <span class="text-danger-dker">*</span></label>
                            <!-- <input type="text" name="hospice_shipping_address" class="form-control " id="" placeholder=""> -->
                            <input <?php echo $disabled; ?> type="text" class="form-control" id="s_add" placeholder="Enter Address" name="s_address" style="margin-bottom:20px;" tabindex="19" value="<?php echo $hospice->s_street; ?>">
                            <input <?php echo $disabled; ?> type="text" class="form-control" id="s_placenum" placeholder="Apartment No., Room No. , Unit No." name="s_placenum" style="margin-bottom:20px;" tabindex="21" value="<?php echo $hospice->s_placenum; ?>">

                            <div class="row" style="margin-bottom:20px;">
                                <div class="col-md-6">
                                    <input <?php echo $disabled; ?> type="text" class="edit_hospice_s_city_<?php echo $hospice->hospiceID; ?> form-control ng-pristine ng-invalid ng-invalid-required" id="" placeholder="City" name="s_city" tabindex="22" value="<?php echo $hospice->s_city; ?>">

                                </div>
                                <div class="col-md-6">
                                    <input <?php echo $disabled; ?> type="text" class="edit_hospice_s_state_<?php echo $hospice->hospiceID; ?> form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" id="" placeholder="State / Province" name="s_state" tabindex="23" value="<?php echo $hospice->s_state; ?>">

                                </div>
                            </div>
                            <input <?php echo $disabled; ?> type="number" class="form-control grey_inner_shadow edit_hospice_s_postal" id="" data-hospice-id="<?php echo $hospice->hospiceID; ?>" onkeypress="return isNumberKey(event)" placeholder="Postal Code" name="s_postalcode" tabindex="24" value="<?php echo $hospice->s_postalcode; ?>">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Fax Number</label>
                          <input <?php echo $disabled; ?> type="text" name="hospice_fax_number" class="form-control hosp_contact_num" id="" placeholder="" value="<?php echo $hospice->hospice_fax_number ?>">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Contact Person</label>
                          <input <?php echo $disabled; ?> type="text" name="hospice_cont_person" class="form-control " id="" placeholder="" value="<?php echo $hospice->hospice_contact_person ?>">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Email</label>
                          <input <?php echo $disabled; ?> type="text" name="hospice_email" class="form-control " id="" placeholder="" value="<?php echo $hospice->hospice_email ?>" style="text-transform:none !important">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Title</label>
                          <input <?php echo $disabled; ?> type="text" name="hospice_title" class="form-control " id="" placeholder="" value="<?php echo $hospice->hospice_title ?>" style="text-transform:none !important">
                        </div>

                        <div class="form-group">
                          <label for="exampleInputEmail1">Website</label>
                          <input <?php echo $disabled; ?> type="text" name="hospice_website" class="form-control " id="" placeholder="" value="<?php echo $hospice->hospice_website ?>" style="text-transform:none !important">
                        </div>
                        <div class="form-group" style="margin-bottom: 35px;">
                            <label for="exampleInputEmail1">Daily Rate <span class="text-danger-dker">*</span></label>
                            <input <?php echo $disabled; ?> type="text" onkeypress="return isNumberKey(event)" name="account_daily_rate" class="form-control grey_inner_shadow" id="" placeholder="" style="" value="<?php echo number_format((float)$hospice->daily_rate, 2, '.', ''); ?>">
                        </div>
                        <hr />
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') { ?>
                <button type="submit" class="btn btn-primary btn-order" >Save Changes</button>
            <?php } ?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </form>
    </div>

  </div>
</div>

<div class="modal fade" id="deactivation_not_allowed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10010;">
    <div class="modal-dialog" style="top: 100px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Unable to Deactivate Account</h4>
            </div>
            <div class="modal-body">
                Confirm work order(s).
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php endforeach; ?> <!-- End sa foreach adtu sa taas :) -->
<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->
<script type="text/javascript">
    $(document).ready(function(){
        $('button.edit_hospice_account_details').on('click', function (e) {

        });

        //track_census_btn
        var allRadios = document.getElementsByName('hospice_rad');
        var booRadio;
        var x = 0;
        $('.track_census_btn').bind('click',function(){
            var _this = $(this);
            if($(".hidden_track_census").val() == 1)
            {
                $(".hidden_track_census").val(0);
            }
        });

        for(x = 0; x < allRadios.length; x++){
            allRadios[x].onclick = function(){
              if(booRadio == this){
                  this.checked = false;
                $(".hidden_track_census").val(1);
                  booRadio = null;
              }
              else
              {
              booRadio = this;
              }
          };
        }

        //billing_email_cc
        setTimeout(function() {
            $('.billing_email_wrapper').find('.bootstrap-tagsinput').addClass("form-control grey_inner_shadow");
            $('.billing_email_wrapper').find('.bootstrap-tagsinput').css("height", "100%");
            $('.billing_email_cc_input').find('.bootstrap-tagsinput').addClass("form-control grey_inner_shadow");
            $('.billing_email_cc_input').find('.bootstrap-tagsinput').css("height", "100%");
        }, 1);
        $('.billing_email_cc').bind('click',function(){
            _this = $(this);
            _this.hide();
            $(".billing_email_input").css("width","100%");
            $(".billing_email_cc_input").css("visibility", "visible");

        });

        $('body').on('change','.sort_account_by_activation_status',function(){
            var value = $(this).val();

            window.location.href = base_url + 'hospice/hospice_list/'+value;
        });

        //change purchase order requisition receive status
        $('.account_list').on('change','.change_account_status',function(){
            var account_id = $(this).attr('data-id');
            var activation_sign = $(this).val();

            if (activation_sign == 1 || activation_sign == 0) {
                jConfirm("Change Account Status?","Warning", function(response){
                    if(response)
                    {
                        $.post(base_url+"hospice/account_activation/"+activation_sign+"/"+account_id,'', function(response){
                            var obj = $.parseJSON(response);
                            if(obj)
                            {
                                var obj = $.parseJSON(response);
                                me_message_v2(obj);
                                setTimeout(function(){
                                    window.location.reload();
                                },1500);
                            }
                            else
                            {
                                me_message_v2({error:0,message:"Error Updating Account Status."});
                            }
                        });
                    }
                });
            } else {
                $.ajax({
                    type:"POST",
                    url:base_url+"hospice/get_account_work_orders/"+account_id,
                    success:function(response)
                    {
                        var obj = $.parseJSON(response);

                        if (obj.work_orders.length > 0) {
                            $('#deactivation_not_allowed').modal("show");
                        } else {
                            jConfirm("Change Account Status?","Warning", function(response){
                                if(response)
                                {
                                    $.post(base_url+"hospice/account_activation/"+activation_sign+"/"+account_id,'', function(response){
                                        var obj = $.parseJSON(response);
                                        if(obj)
                                        {
                                            var obj = $.parseJSON(response);
                                            me_message_v2(obj);
                                            setTimeout(function(){
                                                window.location.reload();
                                            },1500);
                                        }
                                        else
                                        {
                                            me_message_v2({error:0,message:"Error Updating Account Status."});
                                        }
                                    });
                                }
                            });
                        }
                    },
                    error:function(jqXHR, textStatus, errorThrown)
                    {
                        console.log(textStatus, errorThrown);
                    }
                });
            }
            
        });

        // $('body').find('.activation-hospice-btn').bind('click',function(){
        //     var account_id = $(this).attr('data-id');
        //     var activation_sign = $(this).attr('data-activation-sign');

        //     $.ajax({
        //         type:"POST",
        //         url:base_url+"hospice/account_activation/"+activation_sign+"/"+account_id,
        //         success:function(response)
        //         {
        //             var obj = $.parseJSON(response);
        //             me_message_v2(obj);
        //             setTimeout(function(){
        //                 window.location.reload();
        //             },1500);
        //         },
        //         error:function(jqXHR, textStatus, errorThrown)
        //         {
        //             console.log(textStatus, errorThrown);
        //         }
        //     });
        // });

        // $('body').on('click','.deactivate_account_button',function(){
        //     var account_id = $(this).attr('data-id');
        //     var activation_sign = $(this).attr('data-activation-sign');

        //     $.ajax({
        //         type:"POST",
        //         url:base_url+"hospice/get_account_work_orders/"+account_id,
        //         success:function(response)
        //         {
        //             var obj = $.parseJSON(response);
        //             console.log('Work Orders', obj.work_orders);

        //             if (obj.work_orders.length > 0) {
        //                 $('#deactivation_not_allowed').modal("show");
        //             } else {
        //                 $.ajax({
        //                     type:"POST",
        //                     url:base_url+"hospice/account_activation/"+activation_sign+"/"+account_id,
        //                     success:function(response)
        //                     {
        //                         var obj = $.parseJSON(response);
        //                         me_message_v2(obj);
        //                         setTimeout(function(){
        //                             window.location.reload();
        //                         },1500);
        //                     },
        //                     error:function(jqXHR, textStatus, errorThrown)
        //                     {
        //                         console.log(textStatus, errorThrown);
        //                     }
        //                 });
        //             }
        //         },
        //         error:function(jqXHR, textStatus, errorThrown)
        //         {
        //             console.log(textStatus, errorThrown);
        //         }
        //     });

        // });

    });

</script>
