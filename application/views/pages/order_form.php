<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">Customer Order Form</h1>
</div>

<?php
  $organization_id = $this->session->userdata('group_id');
  if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user'  || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') {
      echo "<input type='hidden' class='activity_type_sessioned_account_new_patient' value='dme_user'>";
      $hospice_type = get_chosen_hospice($hospice_selected);

      if ($hospice_type['type'] == 1) {
          $logged_in_account_type = 'Company';
      } else {
          $logged_in_account_type = 'Hospice';
      }
      if ($hospice_selected == 0) {
          $logged_in_account_type = '';
      }
  } else {
      echo "<input type='hidden' class='activity_type_sessioned_account_new_patient' value='hospice_user'>";
      $hospice_type = get_chosen_hospice($organization_id);

      if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $hospice_type['type'] == 1) {
          $logged_in_account_type = 'Company';
      } else {
          $logged_in_account_type = 'Hospice';
      }
  }

  if (!empty($hospice_address)) {
      $var_hospice_address = explode(' ', $hospice_address);
  } else {
      $result_query_address = $hospice_type;
      $var_hospice_address = explode(' ', $result_query_address['hospice_address']);
  }
?>

<form class="" role="form" action="<?php echo base_url('order/add_order'); ?>" method="post" id="order_form_validate" novalidate>

<?php
  echo "<input type='hidden' class='send_to_confirm_work_order_sign_new_patient' name='send_to_confirm_work_order_sign_new_patient' value='0'>";
  echo "<input type='hidden' class='hospice_service_location' name='hospice_service_location' value='".$hospice_type['account_location']."'>";
?>
<div class="col-sm-7 " style="margin-top:25px;">
  <div class="alert ng-isolate-scope alert-info alert-dismissable" >
    <button ng-show="closeable" type="button" class="close" ng-click="close()">
      <!-- <span aria-hidden="true">×</span> -->
      <span class="sr-only">Close</span>
    </button>
    <div ng-transclude=""><span class="ng-binding ng-scope">

      <h2 class="m-n font-thin h3" style="margin-bottom:-15px !important">Having a hard time filling out the Customer Order Form?</h2><br>
      Please call all orders / pickups to : (702) 248 - 0056 <br>
      Please fax all Customer Order Forms to : (702) 889 - 0059</span>
    </div>
  </div>
</div>

<?php if ($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user') : ?>
    <input type="hidden" name="created_by" class="hospice_id" value="<?php echo $this->session->userdata('group_id'); ?>" />
<?php else: ?>
    <input type="hidden" name="created_by" class="hospice_id" value="dme_admin" />
<?php endif; ?>

<input type="hidden" class="sessionID" value="<?php echo get_code($this->session->userdata('userID')); ?>" />
<input type="hidden" class="user_account_type" value="<?php echo $this->session->userdata('account_type'); ?>" />
<div class="wrapper-md" ng-controller="FormDemoCtrl">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading font-bold"><?php echo $logged_in_account_type; ?> Detail
        </div>
        <div class="panel-body">
          <form role="form">
            <div class="col-sm-6">
            <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user'  || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') : ?>
              <div class="form-group pull-in clearfix">
                <div class="col-sm-6">
                  <label>DME Staff Member Taken Order <span class="text-danger-dker">*</span></label>
                  <?php $fname = $this->session->userdata('firstname'); ?>
                  <?php $lname = substr($this->session->userdata('lastname'), 0, 1); ?>

                  <span name="" class="form-control grey_inner_shadow"><?php echo $fname.' '.$lname.'.'; ?></span>
                </div>
                  <!-- <div class="col-sm-6">
                    <label> </label>
                    <?php $fname = $this->session->userdata('firstname'); ?>
                    <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="staff_fname"  placeholder="First Name" value="<?php echo $fname; ?>" readonly="">
                  </div> -->
              </div>
            <?php else :?>
              <div class="form-group pull-in hidden clearfix" style="visibility:hidden">
                <div class="col-sm-6">
                  <label>Customer Service Representative <span class="text-danger-dker">*</span></label>
                  <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required" name=""  required="" placeholder="First Name">
                </div>
                <div class="col-sm-6">
                  <label> </label>
                  <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name=""  placeholder="Last Name">
                </div>
              </div>
            <?php endif; ?>


              <div class="form-group">
                <label><?php echo $logged_in_account_type; ?> Provider <span class="text-danger-dker">*</span></label>
                <?php
                if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') :
                ?>
                  <select name="organization_id" class="form-control m-b hospice_select select2-ready" tabindex="1" data-toggle="popover">
                    <option value="0">- Please Select -</option>

                    <!-- <optgroup label="Hospices"> -->
                      <?php
                        /****************** OPENING of !empty($hospices) *******************/
                        if (!empty($hospices)):
                          foreach ($hospices as $hospice):
                            if ($hospice->hospiceID != 13) {
                                ?>
                              <option value="<?php echo $hospice->hospiceID; ?>" <?php if ($hospice_selected == $hospice->hospiceID) {
                                    echo 'selected';
                                } ?> ><?php echo $hospice->hospice_name; ?></option>
                      <?php
                            } else {
                                if ($hospice_selected == $hospice->hospiceID) {
                                    $temp_demo = '<option value="'.$hospice->hospiceID.'" selected>'.$hospice->hospice_name.'</option>';
                                } else {
                                    $temp_demo = '<option value="'.$hospice->hospiceID.'">'.$hospice->hospice_name.'</option>';
                                }
                            }
                          endforeach;
                      ?>
                          <script type="text/javascript">
                            $(document).ready(function(){
                              var elem = $("body").find(".hospice_select");
                              var demo_option = <?php echo json_encode($temp_demo); ?>;
                              elem.append(demo_option);
                            });
                          </script>
                      <?php
                        endif;
                        /****************** CLOSING of !empty($hospices) *******************/
                      ?>
                    <!-- </optgroup> -->

                    <option disabled="disabled" id="demo_divider">--------------------------------------------------------------------------------------------------------------</option>

                </select>
              <?php
              else:
              ?>
                <input class="form-control" type="text" value="<?php echo $this->session->userdata('group_name'); ?>" readonly />
                <input class="form-control" id="organization_id_not_select" type="hidden" value="<?php echo $this->session->userdata('group_id'); ?>" name="organization_id" />
              <?php
              endif;
              ?>
              </div>

              <div class="form-group pull-in clearfix">
                <div class="col-sm-6">
                  <label><?php echo $logged_in_account_type; ?> Staff Member Creating Order <span class="text-danger-dker">*</span></label>
                  <?php $fname = $this->session->userdata('firstname'); ?>
                  <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') : ?>
                     <input type="text"  class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" placeholder="Staff First Name" name="person_placing_order_fname"  value="" tabindex="3">
                  <?php else : ?>
                     <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" placeholder="Staff First Name" name="person_placing_order_fname"  value="<?php echo $fname; ?>" readonly tabindex="3">
                  <?php endif; ?>
                </div>
                <div class="col-sm-6">
                  <label> </label>
                  <?php $lname = $this->session->userdata('lastname'); ?>
                  <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') : ?>
                    <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required" placeholder="Staff Last Name" name="person_placing_order_lname" value="" tabindex="4">
                  <?php else : ?>
                    <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required" placeholder="Staff Last Name" name="person_placing_order_lname" value="<?php echo $lname; ?>" readonly="" tabindex="4">
                  <?php endif; ?>
                </div>
              </div>


              <div class="form-group ">
                <label>Scheduled Order Date <span class="text-danger-dker">*</span></label>
                <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required datepicker" id="delivery_date" style="margin-left: 0px;" placeholder="Delivery Date" name="delivery_date" tabindex="7">
              </div>
            </div> <!-- .col-sm-6 -->

            <div class="col-sm-6">
              <div class="form-group">
                <label><?php echo $logged_in_account_type; ?> Staff Member Email Address</label>
                <?php
                  $email = $this->session->userdata('email');
                  if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') :
                ?>
                    <input type="email" class="form-control"  name="email" value="" tabindex="2" style="text-transform:none !important" placeholder="STAFF EMAIL ADDRESS">
                <?php
                  else:
                ?>
                    <input type="email" class="form-control" name="email"  value="<?php echo $email; ?>" readonly tabindex="2" style="text-transform:none !important" placeholder="STAFF EMAIL ADDRESS">
                <?php
                  endif;
                ?>
              </div>

              <div class="form-group pull-in clearfix">
                <div class="col-sm-6 clearfix">
                  <label><?php echo $logged_in_account_type; ?> Phone No. <span class="text-danger-dker">*</span></label>
                   <?php $phone = $this->session->userdata('phone_num'); ?>

                   <?php
                      $hospice_contact_num = get_hospice_contact($this->session->userdata('group_id'));
                      $hospice_person_mobile = get_person_mobile_num($this->session->userdata('userID'));
                   ?>

                  <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') :?>
                    <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required hosp_office_num hosp_contact_num" id="person_num" placeholder="Phone Number" name="phone_num" value="<?php echo $hospice_phone->contact_num; ?>" tabindex="5">
                  <?php else:?>
                    <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required hosp_office_num hosp_contact_num" id="person_num" placeholder="Phone Number" name="phone_num" value="<?php echo $hospice_contact_num; ?>" tabindex="5">
                  <?php endif; ?>
                </div>
                <div class="col-sm-6 clearfix">
                  <label><?php echo $logged_in_account_type; ?> Staff Member Cellphone No. <span class="text-danger-dker">*</span></label>

                <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') :?>
                  <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator person_num" name="who_ordered_cpnum"  autocomplete="off" placeholder="Cellphone No." tabindex="6">
                <?php else :?>
                  <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator person_num" name="who_ordered_cpnum"  autocomplete="off" placeholder="Cellphone No." tabindex="6" value="<?php echo $hospice_person_mobile; ?> ">
                <?php endif; ?>

                </div>
              </div>
          <!-- <div class="form-group">
            <label>Patient Medical Record No. <span class="text-danger-dker">*</span></label>
             <input type="text" class="form-control" id="patient_mrn" placeholder="Patient Medical Record No." name="patient_mrn"  autocomplete="off" tabindex="9">
             <div id="suggestion_container" style="margin-bottom: 90px;z-index:999999;position:absolute;"></div>
          </div> -->
            </div> <!-- .col-sm-6 -->
       <!-- <div class="col-sm-12">
          <div class="col-sm-6" style="padding-left:0px;padding-right:0px;">
            <div class="col-sm-6" style="padding-left:0px;">
              <div class="form-group">
               <label>Activity Type <span class="text-danger-dker">*</span></label>
                <div class="radio">
                  <label class="i-checks">
                    <input type="radio" name="activity_type" id="radio_pickup" value="1" class="radio_act_type" checked="checked"><i></i>Delivery
                  </label>
                </div>
            </div>
            </div>
          </div>
       </div> -->
            <div id="patient-profile-container"></div>
        </div>
      </div> <!-- .panel panel-default -->


      <!-- Patiner Info panel -->
      <div class="panel panel-default" >
        <div class="panel-heading font-bold" >Customer Profile</div>
        <div class="panel-body">
          <form role="form">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Customer Medical Record No. <span class="text-danger-dker">*</span> &nbsp;&nbsp; <span style="font-size: 12px; font-style: italic; color: #00000078;"> Restricted " !"#$%&'()*+,./:;<=>?@[\]^_`{|}~" </span></label>
                 <input type="text" class="form-control" id="patient_mrn" placeholder="Customer Medical Record No." name="patient_mrn" autocomplete="off" tabindex="8" data-toggle="popover" readonly="readonly" onkeypress="return event.charCode == 8 ? null : ( event.charCode >= 48 && event.charCode <= 57 ) || event.charCode === 127 || event.charCode === 45 || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)" onpaste="return false">
                 <input type="hidden" id="hdn_hospice_id" value="" />
              </div>

              <div class="form-group pull-in clearfix">
                <div class="col-sm-6">
                  <label>Customer Name<span class="text-danger-dker">*</span></label>
                  <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required" id="p_lname" placeholder="Last Name" name="patient_lname"  tabindex="10">
                </div>
                <div class="col-sm-6">
                  <label> </label>
                  <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" id="p_fname" placeholder="First Name" name="patient_fname"  tabindex="11">
                </div>
              </div>

              <div class="form-group">
                <label> Gender <span class="text-danger-dker">*</span></label> &nbsp
                  <div class="radio" tabindex="13">
                    <label class="i-checks">
                      <input type="radio" style="" class="p_gender" id="male_gender" name="relationship_gender" value="1"  /><i></i> Male &nbsp &nbsp &nbsp
                    </label>
                     <label class="i-checks">
                      <input type="radio" style="" class="p_gender" id="female_gender" name="relationship_gender" value="2" /><i></i> Female
                    </label>
                  </div>
                <!--  <input type="radio" style="" class="p_gender" id="male_gender" name="relationship_gender" value="1" /> Male &nbsp &nbsp &nbsp
                 <input type="radio" style="" class="p_gender" id="female_gender" name="relationship_gender" value="2" /> Female -->
              </div>

              <div class="form-group pull-in clearfix">
                <div class="col-sm-6">
                  <label>Height (IN)<span class="text-danger-dker">*</span></label>
                  <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required" id="p_height" placeholder="Height (IN)" name="patient_height"  tabindex="14">
                </div>
                <div class="col-sm-6">
                  <label> Weight (lbs) <span class="text-danger-dker">*</span></label>
                  <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" id="p_weight" placeholder="Weight (lbs)" name="patient_weight"  tabindex="15">

                </div>
              </div>

              <div class="form-group">
                <label>Customer Residence<span class="text-danger-dker">*</span></label>
                <?php
                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                      ?>
                    <select  name="dropdown_deliver_type"  class="form-control m-b" tabindex="17">
                      <option value="Skilled Nursing Facility" selected>Skilled Nursing Facility</option>
                    </select>
                <?php
                  } else {
                      ?>
                    <select  name="dropdown_deliver_type"  class="form-control m-b" tabindex="17">
                      <option value="">[-- Select Residence --]</option>
                      <option value="Assisted Living">Assisted Living</option>
                      <option value="Group Home">Group Home</option>
                      <option value="Hic Home">Hic Home</option>
                      <option value="Home Care">Home Care</option>
                      <option value="Skilled Nursing Facility">Skilled Nursing Facility</option>
                    </select>
                <?php
                  }
                ?>
              </div>

              <div class="form-group">
                <?php
                $count_hospice_address = count($var_hospice_address);
                $patient_address_looped = '';
                foreach ($var_hospice_address as $loop_address_here) {
                    if ($loop_address_here == 'LAS') {
                        break;
                    } else {
                        $patient_address_looped = $patient_address_looped.' '.$loop_address_here;
                    }
                }
                ?>
                <label>Customer Address <span class="text-danger-dker">*</span></label>
                <?php
                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                      ?>
                    <input type="text" class="form-control" id="p_add" placeholder="Enter Address" name="p_address" style="margin-bottom:20px;" tabindex="19" value="<?php echo $patient_address_looped; ?>" readonly>
                <?php
                  } else {
                      ?>
                    <input type="text" class="form-control" id="p_add" placeholder="Enter Address" name="p_address" style="margin-bottom:20px;" tabindex="19">
                <?php
                  }
                ?>

              </div>
              <div class="form-group">
                <?php
                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                      ?>
                    <input type="text" class="form-control" id="p_placenum" placeholder="Room #" name="patient_placenum" tabindex="21" required>
                    <input type="hidden" name="account_type_sign" value="1">
                <?php
                  } else {
                      ?>
                    <input type="text" class="form-control" id="p_placenum" placeholder="Apartment No., Room No. , Unit No." name="patient_placenum" tabindex="21">
                    <input type="hidden" name="account_type_sign" value="0">
                <?php
                  }
                ?>
              </div>

              <div class="form-group pull-in clearfix">
                <div class="col-sm-6">
                  <?php
                    if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                        ?>
                      <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required" id="p_city" placeholder="City" value="Las Vegas" name="patient_city" tabindex="22" readonly>
                  <?php
                    } else {
                        ?>
                      <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required" id="p_city" placeholder="City" name="patient_city" tabindex="22">
                  <?php
                    }
                  ?>

                </div>
                <div class="col-sm-6">
                  <?php
                    if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                        ?>
                      <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" id="p_state" value="NV" name="patient_state" tabindex="23" readonly>
                  <?php
                    } else {
                        ?>
                      <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" id="p_state" placeholder="State / Province" name="patient_state" tabindex="23">
                  <?php
                    }
                  ?>
                </div>
              </div>

              <div class="form-group">
                <?php
                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                      ?>
                    <input type="number" class="form-control grey_inner_shadow" id="p_postal" onkeypress="return isNumberKey(event)" placeholder="Postal Code" name="patient_postalcode" value="<?php echo $var_hospice_address[$count_hospice_address - 1]; ?>" tabindex="24" readonly>
                <?php
                  } else {
                      ?>
                    <input type="number" class="form-control grey_inner_shadow" id="p_postal" onkeypress="return isNumberKey(event)" placeholder="Postal Code" name="patient_postalcode" tabindex="24">
                <?php
                  }
                ?>
              </div>

            </div> <!-- .col-sm-6 -->

            <div class="col-sm-6">
              <div class="form-group">
                <label>Phone Number  <span class="text-danger-dker">*</span></label>
                <?php
                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                      if (!empty($result_query_address)) {
                          ?>
                      <input type="text" class="form-control" id="p_phone_number" placeholder="Phone Number" name="patient_phone_num" value="<?php echo $result_query_address['contact_num']; ?>" tabindex="9" readonly>
                <?php
                      } else {
                          ?>
                      <input type="text" class="form-control" id="p_phone_number" placeholder="Phone Number" name="patient_phone_num" value="<?php echo $hospice_contact_num; ?>" tabindex="9" readonly>
                <?php
                      }
                  } else {
                      ?>
                    <input type="text" class="form-control" id="p_phone_number" placeholder="Phone Number" name="patient_phone_num" tabindex="9">
                <?php
                  }
                ?>
              </div>

              <div class="form-group">
                <label>Alt. Phone Number <span class="text-danger-dker">*</span></label>
                <?php
                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                      ?>
                    <input type="text" class="form-control" id="p_alt_phonenum" placeholder="Alt. Phone Number" name="patient_alt_phonenum" value="(000) 000-0000" tabindex="12" readonly>
                <?php
                  } else {
                      ?>
                    <input type="text" class="form-control" id="p_alt_phonenum" placeholder="Alt. Phone Number" name="patient_alt_phonenum" tabindex="12">
                <?php
                  }
                ?>
              </div>

              <div class="form-group" style="margin-top:66px">
                <label><i>Emergency Contact </i></label><br>
                <label>Next of Kin <span class="text-danger-dker">*</span></label>
                <?php
                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                      if (!empty($result_query_address)) {
                          ?>
                      <input type="text" class="form-control" id="p_nextofkin" placeholder="Full Name" name="patient_nextofkin" value="<?php echo $result_query_address['hospice_name']; ?>" tabindex="16" readonly>
                <?php
                      } else {
                          ?>
                      <input type="text" class="form-control" id="p_nextofkin" placeholder="Full Name" name="patient_nextofkin" value="<?php echo $this->session->userdata('group_name'); ?>" tabindex="16" readonly>
                <?php
                      }
                  } else {
                      ?>
                    <input type="text" class="form-control" id="p_nextofkin" placeholder="Full Name" name="patient_nextofkin" tabindex="16">
                <?php
                  }
                ?>
              </div>

              <div class="form-group" >
                <label>Relationship <span class="text-danger-dker">*</span></label>
                <?php
                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                      ?>
                    <input type="text" class="form-control" id="p_relationship" placeholder="Relationship" name="patient_relationship" value="N/A" tabindex="18" readonly>
                <?php
                  } else {
                      ?>
                    <input type="text" class="form-control" id="p_relationship" placeholder="Relationship" name="patient_relationship" tabindex="18">
                <?php
                  }
                ?>
              </div>

              <div class="form-group">
                <label>Next of Kin Phone Number <span class="text-danger-dker">*</span></label>
                <?php
                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                      ?>
                    <input type="text" class="form-control" id="p_nextofkinphone" placeholder="Phone Number" name="patient_nextofkinphonenum" value="(000) 000-0000" tabindex="20" readonly>
                <?php
                  } else {
                      ?>
                    <input type="text" class="form-control" id="p_nextofkinphone" placeholder="Phone Number" name="patient_nextofkinphonenum" tabindex="20">
                <?php
                  }
                ?>
              </div>

              <div class="form-group">
                <button type="button" class="btn btn-save-draft" style="float:right;margin-top:110px;background-color:#ecbc41;color:#fff;">Save Customer as Draft</button>
              </div>
            </div>

          </div>
        </div> <!-- .panel-body -->

        <!-- Equipments panel -->
        <div class="panel panel-default">
          <div class="panel-heading font-bold">Add New Item(s)</div>
          <div class="panel-body order_form_panel_body">

            <div class="col-md-8 clearfix">
            <?php
              if (!empty($equipments)) :
                $count = 1;
                foreach ($equipments as $equipment) :
                  if ($this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'company_user' || $logged_in_account_type == 'Company') {
                      if ($equipment['categoryID'] != 1) {
                          ?>
                      <div class="form-group col-md-12 wrapper-equipment" data-value="<?php echo $equipment['categoryID']; ?>" id="wrapper_equip_<?php echo $equipment['categoryID']; ?>">

                        <label class="btn btn-default data_tooltip" title="Click to Add New Item(s)"  style="margin-bottom:20px;margin-top:20px;"  id="equip_<?php echo $equipment['categoryID']; ?>"><?php echo $equipment['type']; ?></label> <br>
                        <div class="equipment" style="display:none;font-size: 13px !important;">
                          <label style="margin-top:10px;visibility:hidden"><?php echo $equipment['type']; ?> <span class="text-danger-dker">*</span></label>
                          <div class="col-md-4" style="padding-left:15px;">
                          <?php
                            foreach ($equipment['children'] as $key => $child) :
                              if ($child['equipmentID'] != 484) {
                                  if ($child['equipmentID'] != 316 && $child['equipmentID'] != 325 && $child['equipmentID'] != 334 && $child['equipmentID'] != 343) {
                                      ?>
                                  <div class="checkbox">
                                    <label class="i-checks">
                                        <input type="checkbox"  id="" value="<?php echo $child['equipmentID']; ?>"
                                               name="equipments[]"
                                               data-target="#<?php echo trim($child['key_name']); ?>_<?php echo $equipment['categoryID']; ?>"
                                               data-name="<?php echo trim($child['key_name']); ?>"
                                               data-desc="<?php echo trim($child['key_desc']); ?>"
                                               data-value="<?php echo $child['key_desc']; ?>"
                                               data-category="<?php echo $equipment['type']; ?>"
                                               data-category-id="<?php echo $equipment['categoryID']; ?>"
                                               class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID']; ?>"
                                        />
                                        <i></i>
                                    </label>
                                    <?php
                                    	$item_picture_name = str_replace("/", ":", $child['key_name']);
                                    ?>
                                    <a
                                      href="javascript:void;"
                                      rel="popover"
                                      data-placement="auto"
                                      data-trigger="hover"
                                      class="hover_item_photo"
                                      data-img-sign=""
                                      data-img="<?php echo base_url(); ?>assets/img/item_photos/<?php echo trim($item_picture_name); ?>.png"
                                    >
                                      <?php echo $child['key_desc']; ?>
                                    </a>
                                  </div>
                          <?php
                                  }
                              }
                          if ($key == $equipment['division'] - 1) {
                              break;
                          }
                          endforeach; ?>

                          </div>
                          <div class="col-md-4" style="padding-left:15px;" id="">
                            <?php for ($i = $equipment['division']; $i <= $equipment['last']; ++$i) : ?>
                                <?php
                                $child = $equipment['children'][$i]; ?>
                                <div class="checkbox">
                                  <label class="i-checks">
                                      <input type="checkbox" id="" value="<?php echo $child['equipmentID']; ?>"
                                             name="equipments[]"
                                             data-target="#<?php echo trim($child['key_name']); ?>_<?php echo $equipment['categoryID']; ?>"
                                             data-name="<?php echo trim($child['key_name']); ?>"
                                             data-desc="<?php echo trim($child['key_desc']); ?>"
                                             data-value="<?php echo $child['key_desc']; ?>"
                                             data-category="<?php echo $equipment['type']; ?>"
                                             data-category-id="<?php echo $equipment['categoryID']; ?>"
                                             class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID']; ?> <?php if ($equipment['categoryID'] == 3) {
                                    echo 'checkbox_modal';
                                } ?>"

                                             />
                                      <i></i>
                                    <?php
                                    	$item_picture_name = str_replace("/", ":", $child['key_name']);
                                    ?>
                                    <a
                                      	href="javascript:void;"
                                      	rel="popover"
                                      	data-placement="auto"
                                      	data-trigger="hover"
                                      	class="hover_item_photo"
                                      	data-img-sign=""
                                      	data-img="<?php echo base_url(); ?>assets/img/item_photos/<?php echo trim($item_picture_name); ?>.png"
                                    >
                                        <?php echo $child['key_desc']; ?>
                                    </a>
                                  </label>
                                </div>

                            <?php endfor; ?>
                          </div>
                        </div>
                      </div>
            <?php
                      }
                  } else {
                      if ($chosen_hospiceID != 0) {
                          if ($equipment['categoryID'] == 1) {
                              if ($capped_count != 0) {
                                  ?>
                          <div class="form-group col-md-12 wrapper-equipment" data-value="<?php echo $equipment['categoryID']; ?>" id="wrapper_equip_<?php echo $equipment['categoryID']; ?>">

                            <label class="btn btn-default data_tooltip" title="Click to Add New Item(s)"  style="margin-bottom:20px;margin-top:20px;"  id="equip_<?php echo $equipment['categoryID']; ?>"><?php echo $equipment['type']; ?></label> <br>
                            <div class="equipment test" style="display:none;font-size: 13px !important;">
                              <label style="margin-top:10px;visibility:hidden"><?php echo $equipment['type']; ?> <span class="text-danger-dker">*</span></label>
                              <div class="col-md-4" style="padding-left:15px;">
                                <?php
                                  foreach ($equipment['children'] as $key => $child) :
                                    if ($child['equipmentID'] != 316 && $child['equipmentID'] != 325 && $child['equipmentID'] != 334 && $child['equipmentID'] != 343) {
                                        if ($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor') {
                                            if ($child['equipmentID'] != 313 && $child['equipmentID'] != 309 && $child['equipmentID'] != 306 && $child['equipmentID'] != 484) {

                                                ?>
                                          <div class="checkbox">
                                            <label class="i-checks">
                                                <input type="checkbox"  id="" value="<?php echo $child['equipmentID']; ?>"
                                                       name="equipments[]"
                                                       data-target="#<?php echo trim($child['key_name']); ?>_<?php echo $equipment['categoryID']; ?>"
                                                       data-name="<?php echo trim($child['key_name']); ?>"
                                                       data-desc="<?php echo trim($child['key_desc']); ?>"
                                                       data-value="<?php echo $child['key_desc']; ?>"
                                                       data-category="<?php echo $equipment['type']; ?>"
                                                       data-category-id="<?php echo $equipment['categoryID']; ?>"
                                                       class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID']; ?>"
                                                />
                                                <i></i>
                                            </label>
                                            <?php
                                    			$item_picture_name = str_replace("/", ":", $child['key_name']);
		                                    ?>
		                                    <a
		                                      	href="javascript:void;"
		                                      	rel="popover"
		                                      	data-placement="auto"
		                                      	data-trigger="hover"
		                                      	class="hover_item_photo"
		                                      	data-img-sign=""
		                                      	data-img="<?php echo base_url(); ?>assets/img/item_photos/<?php echo trim($item_picture_name); ?>.png"
		                                    >
                                              	<?php echo $child['key_desc']; ?>
                                            </a>
                                          </div>
                                <?php
                                            }
                                        } else {
                                            ?>
                                        <div class="checkbox">
                                          <label class="i-checks">
                                              <input type="checkbox"  id="" value="<?php echo $child['equipmentID']; ?>"
                                                     name="equipments[]"
                                                     data-target="#<?php echo trim($child['key_name']); ?>_<?php echo $equipment['categoryID']; ?>"
                                                     data-name="<?php echo trim($child['key_name']); ?>"
                                                     data-desc="<?php echo trim($child['key_desc']); ?>"
                                                     data-value="<?php echo $child['key_desc']; ?>"
                                                     data-category="<?php echo $equipment['type']; ?>"
                                                     data-category-id="<?php echo $equipment['categoryID']; ?>"
                                                     class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID']; ?>"
                                              />
                                              <i></i>
                                          </label>
                                          	<?php
                                    			$item_picture_name = str_replace("/", ":", $child['key_name']);
		                                    ?>
		                                    <a
		                                      	href="javascript:void;"
		                                      	rel="popover"
		                                      	data-placement="auto"
		                                      	data-trigger="hover"
		                                      	class="hover_item_photo"
		                                      	data-img-sign=""
		                                      	data-img="<?php echo base_url(); ?>assets/img/item_photos/<?php echo trim($item_picture_name); ?>.png"
		                                    >
                                            	<?php echo $child['key_desc']; ?>
                                          	</a>
                                        </div>
                                <?php
                                        }
                                    }
                                  if ($key == $equipment['division'] - 1) {
                                      break;
                                  }
                                  endforeach; ?>
                              </div>
                              <div class="col-md-4" style="padding-left:15px;" id="">
                                <?php for ($i = $equipment['division']; $i <= $equipment['last']; ++$i) : ?>
                                    <?php
                                    $child = $equipment['children'][$i]; ?>
                                    <div class="checkbox">
                                      <label class="i-checks">
                                          <input type="checkbox" id="" value="<?php echo $child['equipmentID']; ?>"
                                                 name="equipments[]"
                                                 data-target="#<?php echo trim($child['key_name']); ?>_<?php echo $equipment['categoryID']; ?>"
                                                 data-name="<?php echo trim($child['key_name']); ?>"
                                                 data-desc="<?php echo trim($child['key_desc']); ?>"
                                                 data-value="<?php echo $child['key_desc']; ?>"
                                                 data-category="<?php echo $equipment['type']; ?>"
                                                 data-category-id="<?php echo $equipment['categoryID']; ?>"
                                                 class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID']; ?> <?php if ($equipment['categoryID'] == 3) {
                                        echo 'checkbox_modal';
                                    } ?>"

                                                 />
                                          <i></i>
                                          	<a
	                                            href="javascript:void;"
	                                            rel="popover"
	                                            data-placement="auto"
	                                            data-trigger="hover"
	                                            class="hover_item_photo"
	                                            data-img-sign=""
	                                            data-img="<?php echo base_url(); ?>assets/img/item_photos/<?php echo trim($child['key_name']); ?>.png"
                                          	>
                                            	<?php echo $child['key_desc']; ?>
                                          	</a>
                                      </label>
                                    </div>

                                <?php endfor; ?>
                              </div>
                            </div>
                          </div>
            <?php
                              }
                          } else {
                              ?>
                        <div class="form-group col-md-12 wrapper-equipment" data-value="<?php echo $equipment['categoryID']; ?>" id="wrapper_equip_<?php echo $equipment['categoryID']; ?>">

                          <label class="btn btn-default data_tooltip" title="Click to Add New Item(s)"  style="margin-bottom:20px;margin-top:20px;"  id="equip_<?php echo $equipment['categoryID']; ?>"><?php echo $equipment['type']; ?></label> <br>
                          <div class="equipment" style="display:none;font-size: 13px !important;">
                            <label style="margin-top:10px;visibility:hidden"><?php echo $equipment['type']; ?> <span class="text-danger-dker">*</span></label>
                            <div class="col-md-4" style="padding-left:15px;">
                              <?php
                                foreach ($equipment['children'] as $key => $child) :
                                  // ==================> Added 12/10/2019 ======== Start
                                  $searchword = rtrim($child['key_desc'], " ");
                                  $temp_child = array_search($child['equipmentID'], array_column($equipments[0]['children'], 'noncapped_reference'));
                                  $hide_checkbox = "";
                                  // $matches = array_filter(array_column($equipments[0]['children'], 'key_desc'), function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); });
                                  if($temp_child !== false) {
                                    // continue;
                                    $hide_checkbox = "style='display: none'";
                                  }
                                  // if(count($matches) > 0) {
                                  //   continue;
                                  // }
                                  // ==================> Added 12/10/2019 ======== End

                                  if ($child['equipmentID'] != 316 && $child['equipmentID'] != 325 && $child['equipmentID'] != 334 && $child['equipmentID'] != 343) {
                                      if ($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor') {
                                          if ($child['equipmentID'] != 313 && $child['equipmentID'] != 309 && $child['equipmentID'] != 306 && $child['equipmentID'] != 484) {
                                              ?>
                                        <div class="checkbox" <?php echo $hide_checkbox; ?>>
                                          <label class="i-checks">
                                              <input type="checkbox"  id="" value="<?php echo $child['equipmentID']; ?>"
                                                     name="equipments[]"
                                                     data-target="#<?php echo trim($child['key_name']); ?>_<?php echo $equipment['categoryID']; ?>"
                                                     data-name="<?php echo trim($child['key_name']); ?>"
                                                     data-desc="<?php echo trim($child['key_desc']); ?>"
                                                     data-value="<?php echo $child['key_desc']; ?>"
                                                     data-category="<?php echo $equipment['type']; ?>"
                                                     data-category-id="<?php echo $equipment['categoryID']; ?>"
                                                     class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID']; ?>"
                                              />
                                              <i></i>
                                          </label>
                                          	<a
                                            	href="javascript:void;"
                                            	rel="popover"
                                            	data-placement="auto"
                                            	data-trigger="hover"
                                            	class="hover_item_photo"
                                            	data-img-sign=""
                                          	  	data-img="<?php echo base_url(); ?>assets/img/item_photos/<?php echo trim($child['key_name']); ?>.png"
                                          	>
                                            	<?php echo $child['key_desc']; ?>
                                        	</a>
                                        </div>
                              <?php
                                          }
                                      } else {
                                          ?>
                                      <div class="checkbox" <?php echo $hide_checkbox; ?>>
                                        <label class="i-checks">
                                            <input type="checkbox"  id="" value="<?php echo $child['equipmentID']; ?>"
                                                   name="equipments[]"
                                                   data-target="#<?php echo trim($child['key_name']); ?>_<?php echo $equipment['categoryID']; ?>"
                                                   data-name="<?php echo trim($child['key_name']); ?>"
                                                   data-desc="<?php echo trim($child['key_desc']); ?>"
                                                   data-value="<?php echo $child['key_desc']; ?>"
                                                   data-category="<?php echo $equipment['type']; ?>"
                                                   data-category-id="<?php echo $equipment['categoryID']; ?>"
                                                   class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID']; ?>"
                                            />
                                            <i></i>
                                        </label>
                                        <?php
                                    		$item_picture_name = str_replace("/", ":", $child['key_name']);
		                                ?>
		                                <a
		                                    href="javascript:void;"
		                                    rel="popover"
		                                    data-placement="auto"
		                                    data-trigger="hover"
		                                    class="hover_item_photo"
		                                    data-img-sign=""
		                                    data-img="<?php echo base_url(); ?>assets/img/item_photos/<?php echo trim($item_picture_name); ?>.png"
		                                >
                                          	<?php echo $child['key_desc']; ?>
                                      	</a>
                                      </div>
                              <?php
                                      }
                                  }
                              if ($key == $equipment['division'] - 1) {
                                  break;
                              }
                              endforeach; ?>
                            </div>
                            <div class="col-md-4" style="padding-left:15px;" id="">
                              <?php for ($i = $equipment['division']; $i <= $equipment['last']; ++$i) : ?>
                                  <?php
                                  $child = $equipment['children'][$i];
                                  // ==================> Added 12/10/2019 ======== Start
                                  $searchword = rtrim($child['key_desc'], " ");
                                  $temp_child = array_search($child['equipmentID'], array_column($equipments[0]['children'], 'noncapped_reference'));
                                  $hide_checkbox = "";
                                  // $matches = array_filter(array_column($equipments[0]['children'], 'key_desc'), function($var) use ($searchword) { return preg_match("/\b$searchword\b/i", $var); });
                                  if($temp_child !== false) {
                                    // continue;
                                    $hide_checkbox = "style='display: none'";
                                  }
                                  // if(count($matches) > 0) {
                                  //   continue;
                                  // }
                                  // ==================> Added 12/10/2019 ======== End

                                  ?>
                                <div class="checkbox" <?php echo $hide_checkbox; ?>>
                                    <label class="i-checks">
                                        <input type="checkbox" id="" value="<?php echo $child['equipmentID']; ?>"
                                               name="equipments[]"
                                               data-target="#<?php echo trim($child['key_name']); ?>_<?php echo $equipment['categoryID']; ?>"
                                               data-name="<?php echo trim($child['key_name']); ?>"
                                               data-desc="<?php echo trim($child['key_desc']); ?>"
                                               data-value="<?php echo $child['key_desc']; ?>"
                                               data-category="<?php echo $equipment['type']; ?>"
                                               data-category-id="<?php echo $equipment['categoryID']; ?>"
                                               class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID']; ?> <?php if ($equipment['categoryID'] == 3) {
                                      echo 'checkbox_modal';
                                  } ?>"

                                               />
                                        <i></i>
                                        <?php
	                                    	$item_picture_name = str_replace("/", ":", $child['key_name']);
	                                    ?>
	                                    <a
	                                      	href="javascript:void;"
	                                      	rel="popover"
	                                      	data-placement="auto"
	                                      	data-trigger="hover"
	                                      	class="hover_item_photo"
	                                      	data-img-sign=""
	                                      	data-img="<?php echo base_url(); ?>assets/img/item_photos/<?php echo trim($item_picture_name); ?>.png"
	                                    >
                                          	<?php echo $child['key_desc']; ?>
                                      	</a>
                                    </label>
                                  </div>

                              <?php endfor; ?>
                            </div>
                          </div>
                        </div>
            <?php
                          }
                      } else {
                          ?>
                      <div class="form-group col-md-12 wrapper-equipment" data-value="<?php echo $equipment['categoryID']; ?>" id="wrapper_equip_<?php echo $equipment['categoryID']; ?>">

                        <label class="btn btn-default data_tooltip" title="Click to Add New Item(s)"  style="margin-bottom:20px;margin-top:20px;"  id="equip_<?php echo $equipment['categoryID']; ?>"><?php echo $equipment['type']; ?></label> <br>
                        <div class="equipment" style="display:none;font-size: 13px !important;">
                          <label style="margin-top:10px;visibility:hidden"><?php echo $equipment['type']; ?> <span class="text-danger-dker">*</span></label>
                          <div class="col-md-4" style="padding-left:15px;">
                            <?php
                              foreach ($equipment['children'] as $key => $child) :
                                if ($child['equipmentID'] != 316 && $child['equipmentID'] != 325 && $child['equipmentID'] != 334 && $child['equipmentID'] != 343) {
                                    if ($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor') {
                                        if ($child['equipmentID'] != 313 && $child['equipmentID'] != 309 && $child['equipmentID'] != 306 && $child['equipmentID'] != 484) {
                                            ?>
                                      <div class="checkbox">
                                        <label class="i-checks">
                                            <input type="checkbox"  id="" value="<?php echo $child['equipmentID']; ?>"
                                                   name="equipments[]"
                                                   data-target="#<?php echo trim($child['key_name']); ?>_<?php echo $equipment['categoryID']; ?>"
                                                   data-name="<?php echo trim($child['key_name']); ?>"
                                                   data-desc="<?php echo trim($child['key_desc']); ?>"
                                                   data-value="<?php echo $child['key_desc']; ?>"
                                                   data-category="<?php echo $equipment['type']; ?>"
                                                   data-category-id="<?php echo $equipment['categoryID']; ?>"
                                                   class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID']; ?>"
                                            />
                                            <i></i>
                                        </label>

                                        <?php
	                                    	$item_picture_name = str_replace("/", ":", $child['key_name']);
	                                    ?>
	                                    <a
	                                      	href="javascript:void;"
	                                      	rel="popover"
	                                      	data-placement="auto"
	                                      	data-trigger="hover"
	                                      	class="hover_item_photo"
	                                      	data-img-sign=""
	                                      	data-img="<?php echo base_url(); ?>assets/img/item_photos/<?php echo trim($item_picture_name); ?>.png"
	                                    >
                                          <?php echo $child['key_desc']; ?>
                                        </a>
                                      </div>
                            <?php
                                        }
                                    } else {
                                        ?>
                                    <div class="checkbox">
                                      <label class="i-checks">
                                          <input type="checkbox"  id="" value="<?php echo $child['equipmentID']; ?>"
                                                 name="equipments[]"
                                                 data-target="#<?php echo trim($child['key_name']); ?>_<?php echo $equipment['categoryID']; ?>"
                                                 data-name="<?php echo trim($child['key_name']); ?>"
                                                 data-desc="<?php echo trim($child['key_desc']); ?>"
                                                 data-value="<?php echo $child['key_desc']; ?>"
                                                 data-category="<?php echo $equipment['type']; ?>"
                                                 data-category-id="<?php echo $equipment['categoryID']; ?>"
                                                 class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID']; ?>"
                                          />
                                          <i></i>
                                      	</label>
                                      	<?php
	                                    	$item_picture_name = str_replace("/", ":", $child['key_name']);
	                                    ?>
	                                    <a
	                                      	href="javascript:void;"
	                                      	rel="popover"
	                                      	data-placement="auto"
	                                      	data-trigger="hover"
	                                      	class="hover_item_photo"
	                                      	data-img-sign=""
	                                      	data-img="<?php echo base_url(); ?>assets/img/item_photos/<?php echo trim($item_picture_name); ?>.png"
	                                    >
                                        	<?php echo $child['key_desc']; ?>
                                      	</a>
                                    </div>
                            <?php
                                    }
                                }
                          if ($key == $equipment['division'] - 1) {
                              break;
                          }
                          endforeach; ?>
                          </div>
                          <div class="col-md-4" style="padding-left:15px;" id="">
                            <?php for ($i = $equipment['division']; $i <= $equipment['last']; ++$i) : ?>
                                <?php
                                $child = $equipment['children'][$i]; ?>
                                <div class="checkbox">
                                  <label class="i-checks">
                                      <input type="checkbox" id="" value="<?php echo $child['equipmentID']; ?>"
                                             name="equipments[]"
                                             data-target="#<?php echo trim($child['key_name']); ?>_<?php echo $equipment['categoryID']; ?>"
                                             data-name="<?php echo trim($child['key_name']); ?>"
                                             data-desc="<?php echo trim($child['key_desc']); ?>"
                                             data-value="<?php echo $child['key_desc']; ?>"
                                             data-category="<?php echo $equipment['type']; ?>"
                                             data-category-id="<?php echo $equipment['categoryID']; ?>"
                                             class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID']; ?> <?php if ($equipment['categoryID'] == 3) {
                                    echo 'checkbox_modal';
                                } ?>"

                                             />
                                      <i></i>
                                    </label>
                                    <?php
                                    	$item_picture_name = str_replace("/", ":", $child['key_name']);
                                    ?>
                                    <a
                                      	href="javascript:void;"
                                      	rel="popover"
                                      	data-placement="auto"
                                      	data-trigger="hover"
                                      	class="hover_item_photo"
                                      	data-img-sign=""
                                      	data-img="<?php echo base_url(); ?>assets/img/item_photos/<?php echo trim($item_picture_name); ?>.png"
                                    >
                                        <?php echo $child['key_desc']; ?>
                                    </a>

                                </div>

                            <?php endfor; ?>
                          </div>
                        </div>
                      </div>
            <?php
                      }
                  }
                  ++$count;
                endforeach;
              endif;
            ?>

<div class="clearfix"></div>
<div class="col-sm-8" style="padding-left:0px;">
      <div class="form-group">
        <!-- special instructions -->
        <label>Delivery Instructions</label>
          <textarea class="form-control" name="comment" style=""></textarea>
      </div>
</div>


</div>

<div class="col-md-4 " style="">
    <div class="panel panel-default" style="margin-top: 15px;margin-left: 65px;text-transform:uppercase !important; ">
        <div class="panel-heading font-bold">
          <img src="<?php echo base_url(); ?>assets/img/shopping_cart.png" class="col-sm-offset-4" style="width: 35px;height: 29px;" /> Cart
        </div>

        <div class="panel-body order-cont">

        </div>

        <div class="panel-body order-disposable-cont">

        </div>

    </div>
</div>

<div class="oxygen_concentrator_div">

</div>

    <div class="clearfix"></div>

      <!-- submit Order -->
       <div class="clearfix"></div>
       <div><a href="#patient-profile-container" class="goto-patient-profile-container"></a></div>
       <button type="button" class="btn btn-success pull-right btn-save-order" >Submit Order</button>
       <?php $id = $this->session->userdata('userID'); ?>
        <input type="hidden" name="person_who_ordered" value="<?php echo $id; ?>" />
      </div>
    </div>
  </div>
</div>
</div>

<div class="bg-light lter wrapper-md">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>


    <!-- Modal for Oxygen concentrator -->
    <div class="modal fade modal_oxygen_concentrator_1" id="oxygen_concentrator_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:-25px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-id="61" data-desc="Liter Flow" name="subequipment[61][77]" class="form-control liter_flow_field_hidden" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:10px; display: none">
                                        <input type="text" data-id="61" data-desc="Liter Flow disp" name="" class="form-control liter_flow_field total_liter_flow" id="" placeholder="Enter Liter Flow" style="margin-bottom:10px;">
                                    </div>

                                    <label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
                                    <div class="checkbox capdisp5">
                                        <label class="i-checks">
                                            <input type="checkbox"  class="5_ltr capped_5liter" data-desc="Oxygen Concentrator Type" data-value="5 LPM" name="subequipment[61][80]" id="optionsRadios1" value="5" >
                                            <i></i>5 LPM
                                        </label>
                                    </div>

                                    <div class="checkbox nondisp5" style="display: none">
                                        <label class="i-checks">
                                            <input type="checkbox" class="nondisp5_input" data-desc="Oxygen Concentrator Type" data-value="5 LPM" name="disp5" id="optionsRadios1" value="5">
                                            <i></i>5 LPM
                                        </label>
                                    </div>

                                    <div class="checkbox capdisp10">
                                        <label class="i-checks">
                                            <input type="checkbox" class="10_ltr capped_10liter" data-desc="Oxygen Concentrator Type" data-value="10 LPM" name="subequipment[61][81]" id="optionsRadios1" value="10" >
                                            <i></i>10 LPM
                                        </label>
                                    </div>

                                    <div class="checkbox nondisp10" style="display: none">
                                        <label class="i-checks">
                                            <input type="checkbox" class="nondisp10_input" data-desc="Oxygen Concentrator Type" data-value="10 LPM" name="disp10" id="optionsRadios1" value="10">
                                            <i></i>10 LPM
                                        </l>
                                      </label>
                                    </div>

                                    <label>Oxygen E Portable System <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Oxygen E Portable System" data-value="Yes" name="subequipment[61][radio][eps]" id="e_portable_yes_1" class="e_portable_system_option_yes" value="241" >
                                           <i></i>Yes
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Oxygen E Portable System"  data-value="No" name="subequipment[61][radio][eps]" id="e_portable_no_2" class="e_portable_system_option_no" value="242" >
                                            <i></i>No
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[61][radio][]" id="optionsRadios1" class="oxygen_concentrator_duration_1" value="78" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[61][radio][]" id="optionsRadios1" class="oxygen_concentrator_duration_1" value="79" >
                                            <i></i>PRN
                                        </label>
                                    </div>

                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device" data-value="Oxygen Supply Kit" name="subequipment[61][radio][flt]" id="flowtype oxygen_supply_kit_device" value="82" checked="checked">
                                            <i></i>Oxygen Supply Kit
                                        </label>
                                    </div>

                                    <!-- <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" class="oxygen_mask_capped oxymask_device" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[61][radio][flt]" id="optionsRadios1" value="83" >
                                            <i></i>Oxygen Mask
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" class="none_device" data-desc="Delivery Device"  data-value="None" name="subequipment[61][radio][flt]" id="optionsRadios1" value="280" >
                                            <i></i>None
                                        </label>
                                    </div> -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close cancel_oxygen_concentrator pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Oxygen concentrator -->
    <div class="modal fade modal_oxygen_concentrator_2" id="oxygen_concentrator_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:-25px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[29][100]" class="form-control liter_flow_field" id="liter_flow_field_2" placeholder="Enter Liter Flow" style="margin-bottom:10px;">
                                    </div>

                                    <!-- <input type="text" data-desc="Quantity" name="oxygencontratorquantity" class="form-control" id="" placeholder="Enter Liter Flow" style="display:none;" value="2"> -->

                                    <label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
                                    <div class="checkbox">
                                        <label class="i-checks">
                                            <input type="checkbox" class="5_ltr noncapped_5liter" data-desc="Oxygen Concentrator Type" data-value="5 LPM" name="subequipment[29][101]" id="optionsRadios1" value="5" >
                                            <i></i>5 LPM
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label class="i-checks">
                                            <input type="checkbox" class="10_ltr noncapped_10liter" data-desc="Oxygen Concentrator Type" data-value="10 LPM" name="subequipment[29][102]" id="optionsRadios1" value="10" >
                                            <i></i>10 LPM
                                        </label>
                                    </div>

                                    <label>Oxygen E Portable System <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Oxygen E Portable System" data-value="Yes" name="subequipment[29][radio][eps]" class="oxygen_e_portable_yes_2 total_liter_portable_yes" id="e_portable_yes_2" value="243" >
                                            <i></i>Yes
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Oxygen E Portable System"  data-value="No" name="subequipment[29][radio][eps]" class="oxygen_e_portable_no_2 total_liter_portable_no" id="e_portable_no_2" value="244" >
                                            <i></i>No
                                        </label>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[29][radio][]" id="optionsRadios1" class="oxygen_concentrator_duration_2 cont_duration"value="103" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[29][radio][]" id="optionsRadios1" class="oxygen_concentrator_duration_2 prn_duration"value="104" >
                                            <i></i>PRN
                                        </label>
                                    </div>
                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device" data-value="Oxygen Supply Kit" name="subequipment[29][radio][flt]" id="flowtype oxygen_supply_kit_device_2" value="105" checked="checked">
                                            <i></i>Oxygen Supply Kit
                                        </label>
                                    </div>

                                    <!-- <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" class="oxygen_mask_noncapped oxymask_device_2" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[29][radio][flt]" id="optionsRadios1" value="106" >
                                            <i></i>Oxygen Mask
                                        </label>
                                    </div>


                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" class="none_device_2" data-desc="Delivery Device"  data-value="None" name="subequipment[29][radio][flt]" id="optionsRadios1" value="281" >
                                            <i></i>None
                                        </label>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default cancel_oxygen_concentrator_2 btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Oxygen conserving device -->
    <div class="modal fade modal_oxygen_conserving_device_1" id="oxygen_conserving_device_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Conserving Device</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[62][188]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Type<span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type" data-value="With Bag" name="subequipment[62][radio][type]" id="optionsRadios1" value="197">
                                            <i></i>With Bag
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type" data-value="Without Bag" name="subequipment[62][radio][type]" id="optionsRadios1" value="198">
                                            <i></i>Without Bag
                                        </label>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[62][radio][]" id="optionsRadios1" value="360" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[62][radio][]" id="optionsRadios1" value="361" >
                                            <i></i>PRN
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal Oxygen cylinder rack -->
    <div class="modal fade modal_oxygen_cylinder_rack_1" id="oxygen_cylinder_rack_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Cylinder Rack</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Oxygen Cylinder Rack <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="E Cylinder - 6 Rack" name="subequipment[393][radio][]" id="optionsRadios1" value="394" >
                                        <i></i>E Cylinder - 6 Rack
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="E Cylinder - 12 Rack" name="subequipment[393][radio][]" id="optionsRadios1" value="395" >
                                        <i></i>E Cylinder - 12 Rack
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="M6 Cylinder - 6 Rack" name="subequipment[393][radio][]" id="optionsRadios1" value="396" >
                                        <i></i>M6 Cylinder - 6 Rack
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="M6 Cylinder - 12 Rack" name="subequipment[393][radio][]" id="optionsRadios1" value="397" >
                                        <i></i>M6 Cylinder - 12 Rack
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Oxygen E Portable System CAPPED-->
    <div class="modal fade modal_oxygen_e_portable_system_1" id="oxygen_e_portable_system_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen E Portable System</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[174][189]" class="form-control e_portable_qty_1" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[174][radio][]" class="oxygen_e_portable_system_duration oxygen_e_portable_system_cont" id="optionsRadios1" value="364" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[174][radio][]" class="oxygen_e_portable_system_duration oxygen_e_portable_system_prn" id="optionsRadios1" value="365" >
                                            <i></i>PRN
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for Patient Lift with Sling CAPPED-->
    <div class="modal fade modal_hoyer_lift_swing_1" id="hoyer_lift_swing_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Patient Lift with Sling</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <label>Type of Sling <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Mesh Sling" name="subequipment[439][radio][]" id="optionsRadios1" value="440" >
                                            <i></i>Standard Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Mesh Sling" name="subequipment[439][radio][]" id="optionsRadios1" value="441" >
                                            <i></i>Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Mesh Sling" name="subequipment[439][radio][]" id="optionsRadios1" value="442" >
                                            <i></i>X-Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Sling/Commode Cutout" name="subequipment[439][radio][]" id="optionsRadios1" value="443" >
                                            <i></i>Standard Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Sling/Commode Cutout" name="subequipment[439][radio][]" id="optionsRadios1" value="444" >
                                            <i></i>Large Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Sling/Commode Cutout" name="subequipment[439][radio][]" id="optionsRadios1" value="445" >
                                            <i></i>X-Large Sling/Commode Cutout
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for Patient Lift with Sling CAPPED-->
    <div class="modal fade modal_hoyer_lift_with_sling_1" id="hoyer_lift_with_sling_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Patient Lift with Sling</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <label>Type of Sling <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Mesh Sling" name="subequipment[56][radio][]" id="optionsRadios1" value="370" >
                                            <i></i>Standard Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Mesh Sling" name="subequipment[56][radio][]" id="optionsRadios1" value="371" >
                                            <i></i>Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Mesh Sling" name="subequipment[56][radio][]" id="optionsRadios1" value="372" >
                                            <i></i>X-Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Sling/Commode Cutout" name="subequipment[56][radio][]" id="optionsRadios1" value="373" >
                                            <i></i>Standard Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Sling/Commode Cutout" name="subequipment[56][radio][]" id="optionsRadios1" value="374" >
                                            <i></i>Large Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Sling/Commode Cutout" name="subequipment[56][radio][]" id="optionsRadios1" value="375" >
                                            <i></i>X-Large Sling/Commode Cutout
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for Oxygen Liquid Portable NON-CAPPED-->
    <div class="modal fade modal_oxygen_liquid_portable_2" id="oxygen_liquid_portable_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Liquid Portable</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[179][radio][]" id="optionsRadios1" value="368" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[179][radio][]" id="optionsRadios1" value="369" >
                                            <i></i>PRN
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal hi-low -->
    <div class="modal fade modal_hi-low_full_electric_hospital_bed_1" id="hi-low_full_electric_hospital_bed_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hi-Low Full Electric Hospital Bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Rails <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Full Rails" name="subequipment[398][radio][]" id="optionsRadios1" value="399" >
                                        <i></i>Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Half Rails" name="subequipment[398][radio][]" id="optionsRadios1" value="400" >
                                        <i></i>Half Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="No Rails"  name="subequipment[398][radio][]" id="optionsRadios1" value="401" >
                                        <i></i>No Rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal hi-low -->
    <div class="modal fade modal_hi-low_electric_hospital_bed_2" id="hi-low_electric_hospital_bed_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hi-Low Full Electric Hospital Bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Rails <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Full Rails" name="subequipment[19][radio][]" id="optionsRadios1" value="129" >
                                        <i></i>Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Half Rails" name="subequipment[19][radio][]" id="optionsRadios1" value="130" >
                                        <i></i>Half Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="No Rails"  name="subequipment[19][radio][]" id="optionsRadios1" value="131" >
                                        <i></i>No Rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal hi-low extra long -->
    <div class="modal fade modal_hi_low_full_electric_hospital_bed_extra_long_2" id="hi_low_full_electric_hospital_bed_extra_long_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hi-Low Full Electric Hospital Bed (Extra Long)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Rails <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Full Rails" name="subequipment[286][radio][]" id="optionsRadios1" value="287" >
                                        <i></i>Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Half Rails" name="subequipment[286][radio][]" id="optionsRadios1" value="288" >
                                        <i></i>Half Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="No Rails"  name="subequipment[286][radio][]" id="optionsRadios1" value="289" >
                                        <i></i>No Rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal hospital bed extra long -->
    <div class="modal fade modal_hospital_bed_extra_long_2" id="hospital_bed_extra_long_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital Bed Extra Long</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Rails <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Full Rails" name="subequipment[282][radio][]" id="optionsRadios1" value="283" >
                                        <i></i>Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Half Rails" name="subequipment[282][radio][]" id="optionsRadios1" value="284" >
                                        <i></i>Half Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="No Rails"  name="subequipment[282][radio][]" id="optionsRadios1" value="285" >
                                        <i></i>No Rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal gastric-drainage -->
    <div class="modal fade modal_gastric_drainage_aspirator_2" id="gastric_drainage_aspirator_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Gastric Drainage Aspirator</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Gastric Drainage Type <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Gastric Drainage Type" data-value="Continuous" name="subequipment[16][radio][]" id="optionsRadios1" value="122">
                                        <i></i>Continuous
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Gastric Drainage Type" data-value="Intermittant" name="subequipment[16][radio][]" id="optionsRadios1" value="123">
                                        <i></i>Intermittant
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Small volume nebulizer Compressor -->
    <div class="modal fade modal_small_volume_nebulizer_1" id="small_volume_nebulizer_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Small Volume Nebulizer Compressor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Aerosol Mask Adult <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" class="aero_mask_capped" data-desc="Aerosol Mask Adult"  data-value="Yes" name="subequipment[67][radio][]" id="optionsRadios1" value="90">
                                        <i></i>Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Aerosol Mask Adult"  data-value="No" name="subequipment[67][radio][]" id="optionsRadios1" value="91">
                                        <i></i>No
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close cancel-small-volume-nebulizer-item pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reclining wheelchair -->
    <div class="modal fade modal_reclining_wheelchair_1" id="reclining_wheelchair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reclining wheelchair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Reclining Wheelchair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Reclining Wheelchair" data-value='16" Narrow'  name="subequipment[269][radio][trw]" id="optionsRadios1" value="270">
                                       <i></i>16" Narrow
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Reclining Wheelchair" data-value='18" Standard'
                                               name="subequipment[269][radio][trw]" id="optionsRadios1" value="271">
                                       <i></i>18" Standard
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Reclining Wheelchair" data-value='20" Wide'
                                               name="subequipment[269][radio][trw]" id="optionsRadios1" value="391">
                                       <i></i>20" Wide
                                    </label>
                                </div>

                                <label style="margin-top: 20px;">Type of Legrest<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Legrest" data-value='Elevating Legrests'
                                               name="subequipment[269][radio][tol]" id="optionsRadios1" value="272" checked>
                                        <i></i>Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Legrest" data-value='Footrests'
                                               name="subequipment[269][radio][tol]" id="optionsRadios1" value="273" >
                                        <i></i>Footrests
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reclining wheelchair -->
    <div class="modal fade modal_reclining_wheelchair_2" id="reclining_wheelchair_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reclining wheelchair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Reclining Wheelchair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Reclining Wheelchair" data-value='16" Narrow'  name="subequipment[64][radio][trw]" id="optionsRadios1" value="84">
                                       <i></i>16" Narrow
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Reclining Wheelchair" data-value='18" Standard'
                                               name="subequipment[64][radio][trw]" id="optionsRadios1" value="85">
                                       <i></i>18" Standard
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Reclining Wheelchair" data-value='20" Wide'
                                               name="subequipment[64][radio][trw]" id="optionsRadios1" value="392">
                                       <i></i>20" Wide
                                    </label>
                                </div>

                                <label style="margin-top: 20px;">Type of Legrest <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Legrest" data-value='Elevating Legrests'
                                               name="subequipment[64][radio][tol]" id="optionsRadios1" value="86" checked>
                                        <i></i>Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Legrest" data-value='Footrests'
                                               name="subequipment[64][radio][tol]" id="optionsRadios1" value="87" >
                                        <i></i>Footrests
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Wheelchair Transport -->
    <!-- <div class="modal fade modal_transport_wheelchair_1" id="transport_wheelchair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair Transport</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Wheelchair Transport<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair Transport" data-value='17" Narrow'  name="subequipment[69][radio][]" id="optionsRadios1" value="478">
                                       <i></i>17" Narrow
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Wheelchair Transport" data-value='19" Standard'
                                               name="subequipment[69][radio][]" id="optionsRadios1" value="479">
                                       <i></i>19" Standard
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Modal Wheelchair Transport -->
    <!-- <div class="modal fade modal_wheel_chair_companion_2" id="wheel_chair_companion_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair Transport</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Wheelchair Transport<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair Transport" data-value='17" Narrow'  name="subequipment[48][radio][]" id="optionsRadios1" value="480">
                                       <i></i>17" Narrow
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio"
                                               data-desc="Type of Wheelchair Transport" data-value='19" Standard'
                                               name="subequipment[48][radio][]" id="optionsRadios1" value="481">
                                       <i></i>19" Standard
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Modal Geri chair -->
    <div class="modal fade modal_geri_chair_1" id="geri_chair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Geri Chair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Geri Chair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="With Tray" name="subequipment[54][radio][]" id="optionsRadios1" value="192">
                                       <i></i>With Tray
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="Without Tray" name="subequipment[54][radio][]" id="optionsRadios1" value="193">
                                        <i></i>Without Tray
                                    </label>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


  <!-- Modal Geri chair NONCAPPED -->
    <div class="modal fade modal_geri_chair_3_position_with_tray_2" id="geri_chair_3_position_with_tray_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Geri Chair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Geri Chair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="With Tray" name="subequipment[17][radio][]" id="optionsRadios1" value="238">
                                        <i></i>With Tray
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="Without Tray" name="subequipment[17][radio][]" id="optionsRadios1" value="239">
                                        <i></i>Without Tray
                                    </label>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal wheelchair -->
    <div class="modal fade modal_wheelchair_1" id="wheelchair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Wheelchair <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='16" Narrow' name="subequipment[71][radio][]" id="optionsRadios1" value="92" >
                                        <i></i>16" Narrow
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='18" Standard' name="subequipment[71][radio][]" id="optionsRadios1" value="93" >
                                        <i></i>18" Standard
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='20" Wide' name="subequipment[71][radio][]" id="optionsRadios1" value="94" >
                                        <i></i>20" Wide
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='22" Extra Wide' name="subequipment[71][radio][]" id="optionsRadios1" value="95" >
                                        <i></i>22" Extra Wide
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='24" Bariatric' name="subequipment[71][radio][]" id="optionsRadios1" value="96" >
                                        <i></i>24" Bariatric
                                    </label>
                                </div>

                                <br>
                                <label>Type of Legrest <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Legrest" data-value='Elevating Legrests' name="subequipment[71][radio][2]" id="optionsRadios1" value="97" >
                                        <i></i>Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Legrest" data-value='Footrests' name="subequipment[71][radio][2]" id="optionsRadios1" value="98" checked>
                                        <i></i>Footrests
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal hospital bed -->
    <div class="modal fade modal_hospital_bed_1" id="hospital_bed_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital Bed Full Electric</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                               <!--  <label>Type of Hospital Bed <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Full Electric" name="subequipment[55][radio][]" id="optionsRadios1" value="72" >
                                        <i></i>Full Electric
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Semi Electric" name="subequipment[55][radio][]" id="optionsRadios1" value="73" >
                                        <i></i>Semi Electric
                                    </label>
                                </div>

                                <br><br>
 -->

                                <label>Type of Rails<span style="color:red;">*</span></label>

                                <?php if ($organization_id != 15) :?>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Rails" data-value="Full Rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="74" >
                                        <i></i>Full Rails
                                    </label>
                                </div>
                                <?php endif; ?>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Rails" data-value="Half Rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="75" >
                                        <i></i>Half Rails
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Rails" data-value="No rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="76" >
                                        <i></i>No Rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Oxygen cylinder rack NON-CAPPED -->
    <div class="modal fade modal_oxygen_cylinder_rack_2" id="oxygen_cylinder_rack_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Cylinder Rack</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Oxygen Cylinder Rack <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="E Cylinder - 6 Rack" name="subequipment[32][radio][]" id="optionsRadios1" value="297" >
                                        <i></i>E Cylinder - 6 Rack
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="E Cylinder - 12 Rack" name="subequipment[32][radio][]" id="optionsRadios1" value="298" >
                                        <i></i>E Cylinder - 12 Rack
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="M6 Cylinder - 6 Rack" name="subequipment[32][radio][]" id="optionsRadios1" value="299" >
                                        <i></i>M6 Cylinder - 6 Rack
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Oxygen Cylinder Rack" data-value="M6 Cylinder - 12 Rack" name="subequipment[32][radio][]" id="optionsRadios1" value="300" >
                                        <i></i>M6 Cylinder - 12 Rack
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal BIPAP setup -->
    <div class="modal fade modal_bipap_2" id="bipap_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">BIPAP Settings</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>IPAP <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="IPAP" class="form-control" name="subequipment[4][109]">
                                </div>

                                <div class="form-group">
                                    <label>EPAP <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="EPAP" class="form-control" name="subequipment[4][110]">
                                </div>

                                <div class="form-group">
                                    <label>Rate <i>(If applicable)</i> </label>
                                    <input type="text" data-desc="Rate" class="form-control" name="subequipment[4][111]">
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close cancel-bipap-item_2 pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal CPAP setup -->
    <div class="modal fade modal_cpap_2" id="cpap_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg equipments_modal" id="myModalLabel">CPAP Settings</h4>
                </div>
                <div class="modal-body OpenSans-Reg">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>CMH20 <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="IPAP" class="form-control" name="subequipment[9][114]">
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close cancel-cpap-item_2 pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal E-cylinder -->
    <div class="modal fade modal_e-cylinder_3" id="e-cylinder_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen E-Cylinder</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="block">Quantity of Oxygen E-Cylinder <span style="color:red;">*</span>
                                        <input type="number" data-desc="Quantity of E-cylinder" class="form-control grey_inner_shadow" placeholder="ex. 1" name="subequipment[11][121]">
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->


    <!-- Modal M6-Cylinder -->
    <div class="modal fade modal_cylinder_m6_3" id="cylinder_m6_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen M6 Cylinder</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="block">Quantity of Oxygen M6 Cylinder <span style="color:red;">*</span>
                                        <input type="number" data-desc="Quantity of M6 Cylinder" class="form-control grey_inner_shadow" placeholder="ex. 1" name="subequipment[170][194]">
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Oxygen Liquid -->
    <!-- <div class="modal fade modal_oxygen_liquid_refill_3" id="oxygen_liquid_refill_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">

                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Liquid Refill</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of Oxygen Liquid Refill <span style="color:red;">*</span></label>
                                    <input type="text"  data-desc="Quantity" class="form-control" placeholder="ex. 1" name="subequipment[178][290]">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div> -->


    <!-- Modal pressure-mattress -->
    <div class="modal fade modal_alternating_pressure_mattress_2" id="alternating_pressure_mattress_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Alternating Pressure Mattress</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Extended? NC <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Extended? NC" data-value="Yes" name="subequipment[2][radio][]" id="optionsRadios1" value="107" >
                                        <i></i>Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Extended? NC" data-value="No" name="subequipment[2][radio][]" id="optionsRadios1" value="108" >
                                        <i></i>No
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>







    <!-- Modal m6-cylinder -->
    <div class="modal fade modal_m6-cylinder_2" id="m6-cylinder_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">M6-Cylinder</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="block">Quantity of M6-Cylinder NC <span style="color:red;">*</span>
                                        <input type="number" data-desc="Quantity of M6-cylinder NC" class="form-control grey_inner_shadow" placeholder="ex. 1" name="subequipment[27][99]">
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- additional -->
    <!-- Modal hospital bed -->
    <div class="modal fade modal_hospital_bed_2" id="hospital_bed_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital Bed Full Electric</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <!-- <label>Type of Hospital Bed <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Full Electric" name="subequipment[20][radio][]" id="optionsRadios1" value="134" >
                                        <i></i>Full Electric
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Semi Electric" name="subequipment[20][radio][]" id="optionsRadios1" value="135" >
                                        <i></i>Semi Electric
                                    </label>
                                </div> -->




                                <label style="margin-top: 20px;">Type of Rails<span style="color:red;">*</span></label>

                                <?php if ($organization_id != 15) :?>
                                  <div class="radio">
                                      <label class="i-checks">
                                          <input type="radio" data-desc="Type of Rails" data-value="Full Rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="136" >
                                          <i></i>Full Rails
                                      </label>
                                  </div>
                                <?php endif; ?>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Rails" data-value="Half Rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="137" >
                                        <i></i>Half Rails
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Rails" data-value="No rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="138" >
                                        <i></i>No Rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Oxygen HomeFill System NON-CAPPED-->
    <div class="modal fade modal_oxygen_homeFill_compressor_2" id="oxygen_homeFill_compressor_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen HomeFill Compressor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <label>Type of Cylinder <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Cylinder" data-value="HomeFill D Cylinder with Conserver" name="subequipment[456][radio][cyl]" id="optionsRadios1" value="457" >
                                            <i></i>HomeFill D Cylinder with Conserver
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Cylinder" data-value="HomeFill M6 Cylinder with Conserver" name="subequipment[456][radio][cyl]" id="optionsRadios1" value="458" >
                                            <i></i>HomeFill M6 Cylinder with Conserver
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for Oxygen Reservoir -->
    <div class="modal fade modal_oxygen_liquid_2" id="oxygen_liquid_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Liquid Reservoir</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[36][201]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="Continuous" name="subequipment[36][radio][]" id="optionsRadios1" value="202" >
                                            <i></i>Continuous
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[36][radio][]" id="optionsRadios1" value="203" >
                                            <i></i>PRN
                                        </label>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device" data-value="Nasal Cannula" name="subequipment[36][radio][flt]" id="" value="204" >
                                            <i></i>Nasal Cannula
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[36][radio][flt]" id="optionsRadios1" value="205" >
                                            <i></i>Oxygen Mask
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close cancel-oxygen-liquid-reservoir pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Patient Lift with Sling NON-CAPPED-->
    <div class="modal fade modal_hoyer_lift_swing_2" id="hoyer_lift_swing_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Patient Lift with Sling</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <label>Type of Sling <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Mesh Sling" name="subequipment[21][radio][]" id="optionsRadios1" value="376" >
                                            <i></i>Standard Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Mesh Sling" name="subequipment[21][radio][]" id="optionsRadios1" value="377" >
                                            <i></i>Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Mesh Sling" name="subequipment[21][radio][]" id="optionsRadios1" value="378" >
                                            <i></i>X-Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Sling/Commode Cutout" name="subequipment[21][radio][]" id="optionsRadios1" value="379" >
                                            <i></i>Standard Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Sling/Commode Cutout" name="subequipment[21][radio][]" id="optionsRadios1" value="380" >
                                            <i></i>Large Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Sling/Commode Cutout" name="subequipment[21][radio][]" id="optionsRadios1" value="381" >
                                            <i></i>X-Large Sling/Commode Cutout
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for Patient Lift Sling NON-CAPPED-->
    <div class="modal fade modal_patient_lift_sling_2" id="patient_lift_sling_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Patient Lift Sling</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <label>Type of Sling <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Mesh Sling" name="subequipment[196][radio][]" id="optionsRadios1" value="382" >
                                            <i></i>Standard Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Mesh Sling" name="subequipment[196][radio][]" id="optionsRadios1" value="383" >
                                            <i></i>Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Mesh Sling" name="subequipment[196][radio][]" id="optionsRadios1" value="384" >
                                            <i></i>X-Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Sling/Commode Cutout" name="subequipment[196][radio][]" id="optionsRadios1" value="385" >
                                            <i></i>Standard Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Sling/Commode Cutout" name="subequipment[196][radio][]" id="optionsRadios1" value="386" >
                                            <i></i>Large Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Sling/Commode Cutout" name="subequipment[196][radio][]" id="optionsRadios1" value="387" >
                                            <i></i>X-Large Sling/Commode Cutout
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for Patient Lift Electric with Sling NON-CAPPED-->
    <div class="modal fade modal_patient_lift_electric_2" id="patient_lift_electric_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Patient Lift Electric with Sling</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <label>Type of Sling <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Mesh Sling" name="subequipment[353][radio][]" id="optionsRadios1" value="403" >
                                            <i></i>Standard Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Mesh Sling" name="subequipment[353][radio][]" id="optionsRadios1" value="404" >
                                            <i></i>Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Mesh Sling" name="subequipment[353][radio][]" id="optionsRadios1" value="405" >
                                            <i></i>X-Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Sling/Commode Cutout" name="subequipment[353][radio][]" id="optionsRadios1" value="406" >
                                            <i></i>Standard Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Sling/Commode Cutout" name="subequipment[353][radio][]" id="optionsRadios1" value="407" >
                                            <i></i>Large Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Sling/Commode Cutout" name="subequipment[353][radio][]" id="optionsRadios1" value="408" >
                                            <i></i>X-Large Sling/Commode Cutout
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for Oxygen conserving device NONCAPPED-->
    <div class="modal fade modal_oxygen_conserving_device_2" id="oxygen_conserving_device_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Conserving Device</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[31][190]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Type<span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type" data-value="With Bag" name="subequipment[31][radio][type]" id="optionsRadios1" value="199">
                                            <i></i>With Bag
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type" data-value="Without Bag" name="subequipment[31][radio][type]" id="optionsRadios1" value="200">
                                            <i></i>Without Bag
                                        </label>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[31][radio][]" id="optionsRadios1" value="362" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[31][radio][]" id="optionsRadios1" value="363" >
                                            <i></i>PRN
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for Oxygen E Portable System NONCAPPED-->
    <div class="modal fade modal_oxygen_e_portable_system_2" id="oxygen_e_portable_system_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen E Portable System</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[176][191]" class="form-control e_portable_qty_2" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[176][radio][]" class="oxygen_e_portable_system_cont_2" id="optionsRadios1" value="366" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[176][radio][]" class="oxygen_e_portable_system_prn_2" id="optionsRadios1" value="367" >
                                            <i></i>PRN
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->


    <!-- Modal for Oxygen E Portable System NONCAPPED-->
    <div class="modal fade modal_oxygen_concentrator_portable_2" id="oxygen_concentrator_portable_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator Portable</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[30][240]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>
                                    <label> Adapter Type <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Adapter Type" data-value="AC Adapter" name="subequipment[30][radio][adpt]" id="optionsRadios1" value="357" >
                                            <i></i>AC Adapter
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Adapter Type" data-value="DC Adapter" name="subequipment[30][radio][adpt]" id="optionsRadios1" value="358" >
                                            <i></i>DC Adapter
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Adapter Type" data-value="AC and DC Adapter" name="subequipment[30][radio][adpt]" id="optionsRadios1" value="359" >
                                            <i></i>AC and DC Adapter
                                        </label>
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[30][radio][]" id="optionsRadios1" value="355" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[30][radio][]" id="optionsRadios1" value="356" >
                                            <i></i>PRN
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal Small volume nebulizer Compressor -->
    <div class="modal fade modal_small_volume_nebulizer_2" id="small_volume_nebulizer_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Small Volume Nebulizer Compressor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Aerosol Mask Adult <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" class="aero_mask_noncapped"  data-desc="Aerosol Mask Adult"  data-value="Yes" name="subequipment[40][radio][]" id="optionsRadios1" value="115">
                                        <i></i>Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Aerosol Mask Adult"  data-value="No" name="subequipment[40][radio][]" id="optionsRadios1" value="116">
                                        <i></i>No
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close cancel-small-volume-nebulizer-item-2 pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal wheelchair -->
    <div class="modal fade modal_wheelchair_2" id="wheelchair_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Wheelchair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='16" Narrow' name="subequipment[49][radio][]" id="optionsRadios1" value="124" >
                                        <i></i>16" Narrow
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='18" Standard' name="subequipment[49][radio][]" id="optionsRadios1" value="125" >
                                        <i></i>18" Standard
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='20" Wide' name="subequipment[49][radio][]" id="optionsRadios1" value="126" >
                                        <i></i>20" Wide
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='22" Extra Wide' name="subequipment[49][radio][]" id="optionsRadios1" value="127" >
                                        <i></i>22" Extra Wide
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='24" Bariatric' name="subequipment[49][radio][]" id="optionsRadios1" value="128" >
                                        <i></i>24" Bariatric
                                    </label>
                                </div>


                                <label>Type of Legrest<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Legrest" data-value='Elevating Legrests' name="subequipment[49][radio][2]" id="optionsRadios1" value="132" >
                                        <i></i>Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type of Legrest" data-value='Footrests' name="subequipment[49][radio][2]" id="optionsRadios1" value="133" checked>
                                        <i></i>Footrests
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- For the DISPOSABLE SUPPPLIES Modals -->

    <!-- Aerosol Mask Adult-->
    <div class="modal fade modal_adult_aerosol_mask_3" id="adult_aerosol_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Aerosol Mask Adult</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[156][209]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Adult Nasal Cannnula -->
    <div class="modal fade modal_adult_nasal_cannula_3" id="adult_nasal_cannula_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Adult Nasal Cannula W/7' Tubing</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[143][210]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Anti Tippers -->
    <div class="modal fade modal_anti_tippers_3" id="anti_tippers_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Anti Tipper</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[168][211]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



     <!-- Circuit, Peep Valve, T Piece Adaptor -->
    <div class="modal fade modal_circuit_peep_valve_t_piece_adaptor_3" id="circuit_peep_valve_t_piece_adaptor_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Circuit, Peep Valve, T Piece Adaptor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[169][212]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Corrugated Tubing (7ft) -->
    <div class="modal fade modal_corrugated_tubing_7ft_3" id="corrugated_tubing_7ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Corrugated Tubing (7ft)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                 <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[163][213]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!-- E Cylinder Wrench -->
    <div class="modal fade modal_e_cylinder_wrench_3" id="e_cylinder_wrench_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">E Cylinder Wrench</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[141][214]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!-- Feeding Bags -->
    <div class="modal fade modal_feeding_bags_3" id="feeding_bags_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Enteral Feeding Bag (Compat)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[155][215]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--High Flow Nasal Cannula (6L & Higher) -->
    <div class="modal fade modal_high_flow_nasal_cannula_3" id="high_flow_nasal_cannula_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">High Flow Nasal Cannula (6L & Higher)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[144][216]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--High Flow O2 Humidifier Bottle -->
    <div class="modal fade modal_high_flow_o2_humidifier_bottle_3" id="high_flow_o2_humidifier_bottle_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">High Flow Oxygen Humidifier Bottle</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[145][217]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--Jet Nebulizers -->
    <div class="modal fade modal_jet_nebulizers_3" id="jet_nebulizers_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Jet Nebulizers</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[164][218]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Nebulizer Kits (Mouthpiece) -->
    <div class="modal fade modal_nebulizer_kits_mouthpiece_3" id="nebulizer_kits_mouthpiece_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Nebulizer Kit (W/Mouthpiece)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[157][219]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- NO SMOKING SIGN -->
    <div class="modal fade modal_no_smoking_sign_3" id="no_smoking_sign_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">NO SMOKING SIGN</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[409][410]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--Non-Rebreather O2 Mask-->
    <div class="modal fade modal_non_rebreather_o2_mask_3" id="non_rebreather_o2_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Mask, Oxygen Non-Rebreather Adult W/7' Tubing</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[146][220]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--O2 Connector-->
    <div class="modal fade modal_o2_connector_3" id="o2_connector_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Tubing Connector</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[147][221]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--O2 Humidifier Bottle-->
    <div class="modal fade modal_o2_humidifier_bottle_3" id="o2_humidifier_bottle_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Humidifier Bottle</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[148][222]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!--O2 Mask-->
    <div class="modal fade modal_o2_mask_3" id="o2_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Mask, Oxygen Adult W/7' Tubing</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[149][223]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--O2 Tubing 21FT-->
    <div class="modal fade modal_o2_tubing_21ft_3" id="o2_tubing_21ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Tubing 21FT</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[150][224]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--O2 Tubing 7FT-->
    <div class="modal fade modal_o2_tubing_7ft_3" id="o2_tubing_7ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Tubing 7FT</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[151][225]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--O2 Tubing 7FT-->
    <div class="modal fade modal_oxygen_tubing_50ft_3" id="oxygen_tubing_50ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Tubing 50FT</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[245][246]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Pediatric Aerosol Mask-->
    <div class="modal fade modal_pediatric_aerosol_mask_3" id="pediatric_aerosol_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Pediatric Aerosol Mask</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[158][226]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Pediatric Nasal Cannula-->
    <div class="modal fade modal_pediatric_nasal_cannula_3" id="pediatric_nasal_cannula_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Pediatric Nasal Cannula</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[152][227]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Pressure Line Adaptor-->
    <div class="modal fade modal_pressure_line_adaptor_3" id="pressure_line_adaptor_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Pressure Line Adaptor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[166][228]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!--Seat Belt-->
    <div class="modal fade modal_seat_belt_3" id="seat_belt_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Seat Belt</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[167][229]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Suction Canister-->
    <div class="modal fade modal_suction_canister_3" id="suction_canister_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Suction Canister</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[159][230]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



     <!--Suction Tubing Long-->
    <div class="modal fade modal_suction_tubing_long_3" id="suction_tubing_long_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Suction Tubing Long</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[160][231]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Suction Tubing Short-->
    <div class="modal fade modal_suction_tubing_short_3" id="suction_tubing_short_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Suction Tubing Short</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[161][232]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Trach Mask-->
    <div class="modal fade modal_trach_mask_3" id="trach_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Mask, Oxygen Trach Adult W/7' Tubing</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[165][233]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



     <!--Venturi Mask (Vent)-->
    <div class="modal fade modal_venturi_mask_vent_3" id="venturi_mask_vent_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Mask, Oxygen Venturi (Vent) Adult W/7' Tubing</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[153][234]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--X-Mas Tree Adaptor-->
    <div class="modal fade modal_x_mas_tree_adaptor_3" id="x_mas_tree_adaptor_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">X-Mas Tree Adaptor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[154][235]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


      <!--Y Connector-->
    <div class="modal fade modal_y_connector_3" id="y_connector_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Y Connector</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[142][236]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


      <!--Yankuer Suction Tubing-->
    <div class="modal fade modal_yankuer_suction_tubing_3" id="yankuer_suction_tubing_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Yankuer Suction Tubing</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[162][237]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Aerosol Drainage Bag-->
    <div class="modal fade modal_aerosol_drainage_bag_3" id="aerosol_drainage_bag_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Aerosol Drainage Bag</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[267][268]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--Bed Wedge 12"-->
    <div class="modal fade modal_12_bed_wedge_3" id="12_bed_wedge_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Bed Wedge 12"</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[451][452]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--cpap_mask_large-->
    <div class="modal fade modal_cpap_mask_large_3" id="cpap_mask_large_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Mask Large</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[257][258]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--commode_pail-->
    <div class="modal fade modal_commode_pail_3" id="commode_pail_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Commode Pail</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[7][310]" class="form-control commode_pail_quantity grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--oxygen_liquid_refill-->
    <div class="modal fade modal_oxygen_liquid_refill_3" id="oxygen_liquid_refill_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Liquid Refill</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[178][290]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--wheelchair_2_inches_gel_cushion-->
    <div class="modal fade modal_wheelchair_2_inches_gel_cushion_3" id="3inch_wheelchair_gel_cushion_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair 2 inches Gel Cushion</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[1][352]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--CPAP Mask Medium-->
    <div class="modal fade modal_cpap_mask_medium_3" id="cpap_mask_medium_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Mask Medium</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[255][256]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--CPAP Full Face Mask Large-->
    <div class="modal fade modal_cpap_full_face_mask_large_3" id="cpap_full_face_mask_large_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Full Face Mask Large</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[261][262]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--CPAP Full Face Mask Medium-->
    <div class="modal fade modal_cpap_full_face_mask_medium_3" id="cpap_full_face_mask_medium_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Full Face Mask Medium</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[251][252]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--CPAP Full Face Mask Small-->
    <div class="modal fade modal_cpap_full_face_mask_small_3" id="cpap_full_face_mask_small_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Full Face Mask Small</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[249][250]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--CPAP Mask Small-->
    <div class="modal fade modal_cpap_mask_small_3" id="cpap_mask_small_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Mask Small</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[253][254]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--CPAP Mask Small-->
    <div class="modal fade modal_cpap_tubing_7ft_3" id="cpap_tubing_7ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">CPAP Tubing 7ft</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[259][260]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--oxygen_tubing_water_trap-->
    <div class="modal fade modal_enteral_feeding_bag_kangaroo_3" id="enteral_feeding_bag_kangaroo_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Enteral Feeding Bag (Kangaroo)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[247][248]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Nasal Cannula EZ Wrap Ear Cushion-->
    <div class="modal fade modal_nasal_cannula_ez_wrap_ear_cushion_3" id="nasal_cannula_ez_wrap_ear_cushion_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Nasal Cannula EZ Wrap Ear Cushion</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[265][266]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!--oxygen_tubing_water_trap-->
    <div class="modal fade modal_oxygen_tubing_water_trap_3" id="oxygen_tubing_water_trap_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Tubing Water Trap</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[263][264]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>




    <!--enteral_feeding_bag_(1000_ml_joey_pump_sets)-->
    <div class="modal fade modal_enteral_feeding_bag_1000_ml_joey_pump_sets_3" id="enteral_feeding_bag_1000_ml_joey_pump_sets_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Enternal Feeding Bag (1000 ML Joey Pump Sets)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[274][275]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--miscellaneous dispossable-->
    <div class="modal fade modal_miscellaneous_3" id="miscellaneous_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Miscellaneous</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <label>Item Description <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                      <input type="text" data-desc="Item Description" data-uni="1" name="subequipment[306][307]" class="form-control miscellaneous_item_description" id="exampleInputEmail1" placeholder="DESCRIPTION HERE" style=text-transform:none !important;">
                                    </label>
                                </div>
                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[306][308]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--miscellaneous non-capped-->
    <div class="modal fade modal_miscellaneous_2" id="miscellaneous_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Miscellaneous</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Item Description <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="text" data-desc="Item Description" data-uni="1" name="subequipment[309][311]" class="form-control " id="exampleInputEmail1" placeholder="DESCRIPTION HERE" style="margin-bottom:31px;text-transform:none !important;">
                                    </label>
                                </div>

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                      <input type="number" data-desc="Quantity" name="subequipment[309][312]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--miscellaneous capped-->
    <div class="modal fade modal_miscellaneous_1" id="miscellaneous_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Miscellaneous</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Item Description <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="text" data-desc="Item Description" data-uni="1" name="subequipment[313][314]" class="form-control " id="exampleInputEmail1" placeholder="DESCRIPTION HERE" style="margin-bottom:31px;text-transform:none !important;">
                                    </label>
                                </div>

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label class="block">
                                        <input type="number" data-desc="Quantity" name="subequipment[313][315]" class="form-control grey_inner_shadow" id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Capped BIPAP setup -->
    <div class="modal fade modal_bipap_1" id="bipap_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">BIPAP Settings</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                  <?php
                    $capped_copy = check_capped_copy_v2(4);
                    $capped_sub_equipment = get_capped_sub_equipment($capped_copy['equipmentID']);
                  ?>
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>IPAP <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="IPAP" class="form-control" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][<?php echo $capped_sub_equipment[0]['equipmentID']; ?>]">
                                </div>

                                <div class="form-group">
                                    <label>EPAP <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="EPAP" class="form-control" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][<?php echo $capped_sub_equipment[1]['equipmentID']; ?>]">
                                </div>

                                <div class="form-group">
                                    <label>Rate <i>(If applicable)</i> </label>
                                    <input type="text" data-desc="Rate" class="form-control" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][<?php echo $capped_sub_equipment[2]['equipmentID']; ?>]">
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close cancel-bipap-item pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Capped CPAP setup -->
    <div class="modal fade modal_cpap_1" id="cpap_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg equipments_modal" id="myModalLabel">CPAP Settings</h4>
                </div>
                <div class="modal-body OpenSans-Reg">
                  <?php
                    $capped_copy = check_capped_copy_v2(9);
                    $capped_sub_equipment = get_capped_sub_equipment($capped_copy['equipmentID']);
                  ?>
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>CMH20 <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="IPAP" class="form-control" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][<?php echo $capped_sub_equipment[0]['equipmentID']; ?>]">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close cancel-cpap-item pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal gastric-drainage -->
    <div class="modal fade modal_gastric_drainage_aspirator_1" id="gastric_drainage_aspirator_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Gastric Drainage Aspirator</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                  <?php
                    $capped_copy = check_capped_copy_v2(16);
                    $capped_sub_equipment = get_capped_sub_equipment($capped_copy['equipmentID']);
                  ?>
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Gastric Drainage Type <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Gastric Drainage Type" data-value="Continuous" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[0]['equipmentID']; ?>">
                                        <i></i>Continuous
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Gastric Drainage Type" data-value="Intermittant" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[1]['equipmentID']; ?>">
                                        <i></i>Intermittant
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Oxygen E Portable System CAPPED-->
    <div class="modal fade modal_oxygen_concentrator_portable_1" id="oxygen_concentrator_portable_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator Portable</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                  <?php
                    $capped_copy = check_capped_copy_v2(30);
                    $capped_sub_equipment = get_capped_sub_equipment($capped_copy['equipmentID']);
                  ?>
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][<?php echo $capped_sub_equipment[0]['equipmentID']; ?>]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>
                                    <label> Adapter Type <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Adapter Type" data-value="AC Adapter" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][adpt]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[3]['equipmentID']; ?>" >
                                            <i></i>AC Adapter
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Adapter Type" data-value="DC Adapter" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][adpt]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[4]['equipmentID']; ?>" >
                                            <i></i>DC Adapter
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Adapter Type" data-value="AC and DC Adapter" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][adpt]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[5]['equipmentID']; ?>" >
                                            <i></i>AC and DC Adapter
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[1]['equipmentID']; ?>" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[2]['equipmentID']; ?>" >
                                            <i></i>PRN
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for Capped Oxygen Reservoir -->
    <div class="modal fade modal_oxygen_liquid_1" id="oxygen_liquid_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Liquid Reservoir</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                  <?php
                    $capped_copy = check_capped_copy_v2(36);
                    $capped_sub_equipment = get_capped_sub_equipment($capped_copy['equipmentID']);
                  ?>
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][<?php echo $capped_sub_equipment[0]['equipmentID']; ?>]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="Continuous" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[1]['equipmentID']; ?>" >
                                            <i></i>Continuous
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[2]['equipmentID']; ?>" >
                                            <i></i>PRN
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device" data-value="Nasal Cannula" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][flt]" id="" value="<?php echo $capped_sub_equipment[3]['equipmentID']; ?>" >
                                            <i></i>Nasal Cannula
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][flt]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[4]['equipmentID']; ?>" >
                                            <i></i>Oxygen Mask
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Oxygen Liquid Portable CAPPED-->
    <div class="modal fade modal_oxygen_liquid_portable_1" id="oxygen_liquid_portable_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Liquid Portable</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                  <?php
                    $capped_copy = check_capped_copy_v2(179);
                    $capped_sub_equipment = get_capped_sub_equipment($capped_copy['equipmentID']);
                  ?>
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[0]['equipmentID']; ?>" >
                                            <i></i>CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[1]['equipmentID']; ?>" >
                                            <i></i>PRN
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal hospital bed extra long -->
    <div class="modal fade modal_hospital_bed_extra_long_1" id="hospital_bed_extra_long_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital Bed Extra Long</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                  <?php
                    $capped_copy = check_capped_copy_v2(282);
                    $capped_sub_equipment = get_capped_sub_equipment($capped_copy['equipmentID']);
                  ?>
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Rails <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Full Rails" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[0]['equipmentID']; ?>" >
                                        <i></i>Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Half Rails" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[1]['equipmentID']; ?>" >
                                        <i></i>Half Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="No Rails"  name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[2]['equipmentID']; ?>" >
                                        <i></i>No Rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal hi-low extra long -->
    <div class="modal fade modal_hi_low_full_electric_hospital_bed_extra_long_1" id="hi_low_full_electric_hospital_bed_extra_long_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hi-Low Full Electric Hospital Bed (Extra Long)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                  <?php
                    $capped_copy = check_capped_copy_v2(286);
                    $capped_sub_equipment = get_capped_sub_equipment($capped_copy['equipmentID']);
                  ?>
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Rails <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Full Rails" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[0]['equipmentID']; ?>" >
                                        <i></i>Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="Half Rails" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[1]['equipmentID']; ?>" >
                                        <i></i>Half Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label class="i-checks">
                                        <input type="radio" data-desc="Type" data-value="No Rails"  name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[2]['equipmentID']; ?>" >
                                        <i></i>No Rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Patient Lift Electric with Sling NON-CAPPED-->
    <div class="modal fade modal_patient_lift_electric_1" id="patient_lift_electric_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Patient Lift Electric with Sling</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                  <?php
                    $capped_copy = check_capped_copy_v2(353);
                    $capped_sub_equipment = get_capped_sub_equipment($capped_copy['equipmentID']);
                    // echo "<pre>";
                    // print_r($capped_sub_equipment);
                    // echo "</pre>";
                  ?>
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <label>Type of Sling <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Mesh Sling" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[0]['equipmentID']; ?>" >
                                            <i></i>Standard Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Mesh Sling" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[1]['equipmentID']; ?>" >
                                            <i></i>Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Mesh Sling" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[2]['equipmentID']; ?>" >
                                            <i></i>X-Large Mesh Sling
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Standard Sling/Commode Cutout" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[3]['equipmentID']; ?>" >
                                            <i></i>Standard Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="Large Sling/Commode Cutout" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[4]['equipmentID']; ?>" >
                                            <i></i>Large Sling/Commode Cutout
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="i-checks">
                                            <input type="radio" data-desc="Type of Sling" data-value="X-Large Sling/Commode Cutout" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][radio][]" id="optionsRadios1" value="<?php echo $capped_sub_equipment[5]['equipmentID']; ?>" >
                                            <i></i>X-Large Sling/Commode Cutout
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Floor Mat Capped -->
    <div class="modal fade modal_floor_mat_1" id="floor_mat_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Floor Mat</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                  <?php
                    $capped_copy = check_capped_copy_v2(14);
                    $capped_sub_equipment = get_capped_sub_equipment($capped_copy['equipmentID']);
                  ?>
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="block">Quantity of Floor Mat <span style="color:red;">*</span>
                                        <input type="number"  data-desc="Quantity" class="form-control grey_inner_shadow floor_mat_capped_quantity" placeholder="ex. 1" name="subequipment[<?php echo $capped_copy['equipmentID']; ?>][<?php echo $capped_sub_equipment[0]['equipmentID']; ?>]">
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order save_floor_mat_capped">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Floor Mat NONCAPPED -->
     <div class="modal fade modal_floor_mat_2" id="floor_mat_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Floor Mat</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="block">Quantity of Floor Mat <span style="color:red;">*</span>
                                        <input type="number" data-desc="Quantity" class="form-control grey_inner_shadow floor_mat_noncapped_quantity" placeholder="ex. 1" name="subequipment[14][279]">
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order save_floor_mat_noncapped">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Hospital Bed Rail Bumpers (Half Rails) Capped -->
    <div class="modal fade modal_hospital_bed_rail_bumper_half_rail_1" id="hospital_bed_rail_bumper_half_rail_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital Bed Rail Bumpers (Half Rail)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="block">Quantity of Hospital Bed Rail Bumpers (Half Rail) <span style="color:red;">*</span>
                                        <input type="number"  data-desc="Quantity" class="form-control grey_inner_shadow hospital_bed_rail_bumpers_half_capped_quantity" placeholder="ex. 1" name="equipments[437]" value=0>
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order save_hospital_bed_rail_bumpers_half_capped">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--  Hospital Bed Rail Bumpers (Half Rails) NONCAPPED -->
     <div class="modal fade modal_hospital_bed_rail_bumper_half_rail_2" id="hospital_bed_rail_bumper_half_rail_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital Bed Rail Bumpers (Half Rail)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="block">Quantity of Hospital Bed Rail Bumpers (Half Rail) <span style="color:red;">*</span>
                                        <input type="number" data-desc="Quantity" class="form-control grey_inner_shadow hospital_bed_rail_bumpers_half_noncapped_quantity" placeholder="ex. 1" name="equipments[294]" value=0>
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order save_hospital_bed_rail_bumpers_half_noncapped">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Hospital Bed Rail Bumpers (Full Rails) Capped -->
    <div class="modal fade modal_hospital_bed_rail_bumper_full_rail_1" id="hospital_bed_rail_bumper_full_rail_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital Bed Rail Bumpers (Full Rail)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="block">Quantity of Hospital Bed Rail Bumpers (Full Rail) <span style="color:red;">*</span>
                                        <input type="number"  data-desc="Quantity" class="form-control grey_inner_shadow hospital_bed_rail_bumpers_full_capped_quantity" placeholder="ex. 1" name="equipments[436]" value=0>
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order save_hospital_bed_rail_bumpers_full_capped">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--  Hospital Bed Rail Bumpers (Full Rails) NONCAPPED -->
     <div class="modal fade modal_hospital_bed_rail_bumper_full_rail_2" id="hospital_bed_rail_bumper_full_rail_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital Bed Rail Bumpers (Full Rail)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="block">Quantity of Hospital Bed Rail Bumpers (Full Rail) <span style="color:red;">*</span>
                                        <input type="number" data-desc="Quantity" class="form-control grey_inner_shadow hospital_bed_rail_bumpers_full_noncapped_quantity" placeholder="ex. 1" name="equipments[295]" value=0>
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order save_hospital_bed_rail_bumpers_full_noncapped">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->

    <!-- Modal for switching non-capped item to capped item -->
    <div class="modal fade" id="switch_to_capped_modal">
      <div class="modal-dialog" >
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
          <div class="modal-header" style="border-bottom:0px !important">
            <h4 class="modal-title mt20"><img src="<?php echo base_url()?>assets/img/caution_sign.png" style="width:70px;margin-left:44%" /></h4>
          </div>
          <div class="modal-body text-center">
            <p class="label label-danger" style="font-size:20px;">Item needs to be ordered under Capped</p><br /><br />
            <p class="" style="font-size:25px;">If approved, item will be Capped!</p>
          </div>
          <div class="modal-footer" style="border-top:0px !important">
            <button type="button" class="btn btn-info btn-approve-choice-switch" data-dismiss="modal">Approve</button>
            <button type="button" class="btn btn-danger btn-close-alert pull-left" data-dismiss="modal">Cancel</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

<!-- Modal -->
  <div class="modal fade" id="error-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top:90px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal" style="padding-bottom: 0px;">
                <div class="alert  fade in" role="alert">
                 <!-- <button type="button" class="close" data-dismiss="alert"><span class="sr-only">Close</span></button> -->
                  <h4><strong class="message-title"></strong></h4>
                  <strong><p class="message-body"></p></strong>
                </div>
            </div>
        </div>
    </div>
  </div>
<div class="auto-generated-modal"></div>

</form>


<script type="text/javascript">
  $(document).ready(function(){

    var hospice_id = $('.hospice_select').val();
    $.get('<?php echo base_url(); ?>order/get_hospice_assigned_equipment/'+hospice_id,function(response){
      $('.auto-generated-modal').replaceWith(response);
    });

    $('body .hover_item_photo').each(function(){
        var img = $('<img src="'+$(this).attr('data-img') + '" />');
        var _this = $(this);

        img.on('load', function(e){
          _this.attr('data-img-sign', 1);
        }).on('error', function(e) {
          _this.attr('data-img-sign', 0);
        });
    });

    $('body .hover_item_photo').popover({
      html: true,
      trigger: 'hover',
      content: function () {
        var img_sign = $(this).attr('data-img-sign');
          if (img_sign == 1) {
            return '<div style="width:170px;height:130px;"><img src="'+$(this).attr('data-img') + '" style="width:100%;height:100%;" /></div>';
          } else if (img_sign == 0){
            return '<div style="width:170px;height:130px;"><img src="'+base_url+'assets/img/item_photos/no_image_found.png" style="width:100%;height:100%;" /></div>';
          }
      }
    });

  });
</script>
