<style type="text/css">

input[type="search"]
{
  margin-left: 13px;
}

select.input-sm
{
  margin-left: 11px;
  margin-right: 11px;
}

</style>

<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">SmarterChoice Users</h1>
</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">
      List of Users
    </div>

    <div class="table-responsive">
      <table class="table table-striped m-b-none datatable_table">
        <thead>
          <tr>
            <th class="visible-lg">Firstname</th>
            <th class="visible-lg">Lastname</th>
            <th class="visible-lg">Hospice Name</th>
            <th class="visible-lg">Email</th>
            <th class="visible-lg">Date Created</th>
            <th class="visible-lg">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            if(!empty($users)){ 
              foreach ($users as $user): 
          ?>
                <tr>
                  <td class=""><?php echo $user->firstname ?></td>
                  <td class=""><?php echo $user->lastname ?></td>
                  <?php if(empty($user->group_name)) :?>
                      <td class="">AHMS</td>
                  <?php else:?>
                      <td class=""><?php echo $user->group_name ?></td>
                  <?php endif;?>
                  <td class=""><?php echo $user->email ?></td> 
                  <td class=""><?php echo date("m/d/Y h:ia", strtotime($user->date_created)) ?></td>  
                  
                  <td class="" style="text-align:center;">
                    <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'hospice_admin') :?>
                      <button type="button" class="btn btn-primary btn-xs edit_user_button" data-user-location="<?php echo $user->user_location ?>" data-user-group-id="<?php echo $user->group_id ?>"  data-toggle="modal" data-target="#edit_user<?php echo $user->userID ?>"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
                    <?php endif;?>
                    <a href="javascript:void(0)" class="delete-users" data-id="<?php echo $user->userID ?>" data-name="<?php echo $user->lastname.', '.$user->firstname; ?>"><button type="button" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</button></a>
                  </td>
                  
                </tr>
          <?php 
              endforeach;
            }else{
          ?>
              <tr>
                <td colspan="6" style="text-align: center;">No data.</td>
              </tr>
          <?php
            }
          ?> <!-- End sa condition para sa dili empty nga array :) -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<input type="hidden" id="account_user_type" value="<?php echo $this->session->userdata('account_type'); ?>">
<!--Modal for Editing of Users-->
<?php
  $disabled = '';
  if ($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user' && $this->session->userdata('account_type') != 'hospice_admin' && $this->session->userdata('account_type') != 'company_admin') {
    $disabled = 'disabled';
  }
  if(!empty($users)):
    foreach ($users as $user):
?>
      <div class="modal fade edit_user" id="edit_user<?php echo $user->userID ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content" style="margin-top:90px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
            <form action="<?php echo base_url('users/update_user/'.get_code($user->userID)) ?>" method="POST">  
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Edit User Form</h4>
              </div>
              <div class="modal-body OpenSans-Reg">
                <div class="row">
                  <div class="">
                    <div class="col-md-12">
                      <input type="hidden" name="user_location_logged_in" value="<?php echo $this->session->userdata('user_location'); ?>">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="exampleInputPassword1">First Name</label>
                          <input type="text" name="firstname" class="form-control" id="" placeholder="First Name" value="<?php echo $user->firstname ?>" <?php echo $disabled; ?>>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">Last Name</label>
                          <input type="text" name="lastname" class="form-control" id="" placeholder="Last Name" value="<?php echo $user->lastname ?>" <?php echo $disabled; ?>>
                        </div>
                        <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
                          <div class="form-group">
                            <label for="exampleInputPassword1">Assign Location</label>
                            <div class="">
                              <div class="">

                                <!-- <label class="col-sm-6 hidden-xs control-label mt10 text-right">Hospice:</label> -->
                                <!-- <label class="col-sm-2 visible-xs-block control-label mt10 text-right"><i class="fa fa-filter"></i></label> -->
                                <div class="hidden-xs">
                                  <!-- assign_service_location -->
                                  <select name="user_location" class="form-control m-b select2-ready assignlocation_create_user" id="assign_location_create_user" value="<?php echo $user->user_location; ?>" data-user-group-id="<?php echo $user->group_id ?>" <?php echo $disabled; ?>>
                                    <option value="">- Please choose -</option>
                                    <?php
                                      $service_locations = get_service_location();
                                      foreach($service_locations as $value){
                                        $selected = '';
                                        if ($value['location_id'] == $user->user_location) {
                                          $selected = 'selected';
                                        }
                                    ?>
                                    <option value="<?php echo $value['location_id']; ?>" <?php echo $selected; ?>>
                                      <?php echo $value['location_name']; ?>, <?php echo $value['service_location_id']?>
                                    </option>
                                    <?php
                                    }
                                    ?>
                                  </select>
                                </div>
                              </div>
                            </div>
                          </div>
                        <?php endif; ?>
                        <div class="form-group">
                          <label for="exampleInputPassword1">User Type</label>
                          <!-- <input type="text" name="account_type" class="form-control" id="username" placeholder="" value="<?php echo $user->account_type ?>" readonly> -->
                          <select class="form-control select2-ready accounttype_dropdown" id="account_type_dropdown" placeholder="" name="account_type" tabindex="5" data-isSelected="0" value="<?php echo $user->account_type ?>" <?php echo $disabled; ?>>
                            <option value="">- Please choose -</option>
                            <?php
                            if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') {
                            ?>
                              <optgroup label="DME User">

                                <?php
                                if($this->session->userdata('account_type') == 'dme_admin') {
                                ?>

                                  <option value="dme_admin" <?php if ($user->account_type == 'dme_admin') { echo 'selected'; } ?>>Super Admin</option>
                                  <option value="dme_user" <?php if ($user->account_type == 'dme_user') { echo 'selected'; } ?>>Admin</option>
                                <?php
                                }
                                ?>
                                <option value="biller" <?php if ($user->account_type == 'biller') { echo 'selected'; } ?>>Biller</option>
                                <option value="customer_service" <?php if ($user->account_type == 'customer_service') { echo 'selected'; } ?>>Customer Service</option>
                                <option value="rt" <?php if ($user->account_type == 'rt') { echo 'selected'; } ?>>RT</option>
                                <option value="distribution_supervisor" <?php if ($user->account_type == 'distribution_supervisor') { echo 'selected'; } ?>>Distribution Supervisor</option>
                                <option value="dispatch" <?php if ($user->account_type == 'dispatch') { echo 'selected'; } ?>>Dispatch</option>
                                <option value="sales_rep" <?php if ($user->account_type == 'sales_rep') { echo 'selected'; } ?>>Sales Rep</option>
                              </optgroup>
                            <?php
                            }
                            if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'hospice_admin' || $this->session->userdata('account_type') == 'company_admin') {
                            ?>
                              <optgroup label="Account User">
                                <option value="hospice_admin" <?php if ($user->account_type == 'hospice_admin') { echo 'selected'; } ?>>Admin</option>
                                <option value="hospice_user" <?php if ($user->account_type == 'hospice_user') { echo 'selected'; } ?>>Staff</option>
                              </optgroup>
                            <?php
                            }
                            ?>
                          </select>
                        </div>
                        <div class="form-group" id="group_div" style="display:block">
                          <label for="exampleInputPassword1">Choose Group</label>
                    
                          <?php
                          $hide_select = '';
                          $hide_input = '';
                          if($user->account_type == 'hospice_admin' || $user->account_type == 'hospice_user') {
                            $hide_input = 'display: none';
                          } else {
                            $hide_select = 'display: none';
                          }
                          ?>
                          <select style="<?php echo $hide_select; ?>" class="form-control edit_hospicename" placeholder="" name="group_id" <?php echo $disabled; ?>>
                            <option value="<?php echo $user->group_id ?>"><?php echo $user->group_name ?></option>
                            <?php 
                              if(!empty($hospices)):
                                foreach($hospices as $hospice):
                                  echo  "<option value='".$hospice->hospiceID."'>".$hospice->hospice_name."</option>";
                                endforeach ;
                              endif;
                            ?>
                            <input type="hidden" name="group_name_select" class="form-control edit_hospice_name" id="" placeholder="This is for hospice users only" value="<?php echo $user->group_name ?>"> 
                          </select>
                          <input type="text" style="<?php echo $hide_input; ?>" name="group_name" class="form-control edit_hospicename_input" id="" placeholder="This is for hospice users only" value="<?php echo $user->hospice_name ?>" readonly> 
                        </div>
                      </div>
                      <div class="col-md-6">                           
                        <input type="hidden" name="balance" value="0.00" />
                        <div class="form-group">
                          <label for="exampleInputEmail1">Username</label>
                          <textarea name="username" class="form-control" id="username" placeholder="Auto Generated Username" style="height:35px" <?php echo $disabled; ?>><?php echo $user->username ?></textarea>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">Password</label>
                          <input type="password" name="password" class="form-control edit_password" id="" placeholder="Password" value="" <?php echo $disabled; ?>>  
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Email address</label>
                          <input type="email" name="email" class="form-control" id="email_add" placeholder="Email Address" value="<?php echo $user->email ?>" <?php echo $disabled; ?>>
                        </div> 
                        <div class="form-group">
                          <label for="exampleInputPassword1">Phone Number</label>
                          <input type="text" name="phone" class="form-control person_num" id="person_num" placeholder="Phone Number" value="<?php echo $user->phone_num ?>" <?php echo $disabled; ?>>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">Mobile Number</label>
                          <input type="text" name="mobile_num" class="form-control person_num" id="" placeholder="Mobile Number" value="<?php echo $user->mobile_num ?>" <?php echo $disabled; ?>>
                        </div>
                      </div>
                    </div> <!-- .col-md-12 -->
                  </div>
                </div> <!-- .row -->
              </div> <!-- .modal-body OpenSans-Reg-->
              <div class="modal-footer">
                <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'hospice_admin' || $this->session->userdata('account_type') == 'company_admin') { ?>
                  <button type="submit" class="btn btn-primary btn-order" >Save Changes</button>
                <?php } ?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </form> 
          </div>
        </div>
      </div>
<?php 
    endforeach;
  endif;
?> <!-- End sa condition para sa dili empty nga array :) -->

<script type="text/javascript">
  $(document).ready(function(){
    $('button.edit_user_button').on('click', function (e) {
      var _this = $(this); 
      var user_location = _this.attr('data-user-location');
      var user_type = $('#account_user_type').val();
      
      // if (user_type == 'dme_admin' || user_type == 'dme_user' || user_type == 'hospice_admin' || user_type == 'company_admin') {
        var user_group_id = _this.attr('data-user-group-id');
        $.get(base_url+'hospice/get_hospices_by_account_location/'+user_location+'/'+user_group_id, function(response){
            console.log("resepposnse:", response);
            var obj = $.parseJSON(response);
            var hospice_html = '';
            for(var i = 0; i < obj.length; i++) {
              var hospice_data = obj[i];
              var selected = '';
              if (user_group_id == hospice_data.hospiceID) {
                selected = 'selected';
              }

              hospice_html += '<option value="' + hospice_data.hospiceID + '" ' + selected + '>' + hospice_data.hospice_name + '</option>';
            }
            $('.edit_hospicename').html(hospice_html);
        });
      // }
      
    });
    
    $('select.accounttype_dropdown').on('change', function (e) {
      var _this = $(this); 
      console.log('accounttypedropdown', _this.val());
      if (_this.val() == 'hospice_admin' || _this.val() == 'hospice_user') {
        $('.edit_hospicename').show();
        $('.edit_hospicename_input').hide();
      } else {
        $('.edit_hospicename').hide();
        $('.edit_hospicename_input').show();
      }
    });

    $('select.assignlocation_create_user').on('change', function (e) {
      var _this = $(this); 
      var user_group_id = _this.attr('data-user-group-id');
      $.get(base_url+'hospice/get_hospices_by_account_location/'+_this.val()+'/'+user_group_id, function(response){
          console.log("resepposnse:", response);
          var obj = $.parseJSON(response);
          var hospice_html = '';
          for(var i = 0; i < obj.length; i++) {
            var hospice_data = obj[i];
            var selected = '';
            if (user_group_id == hospice_data.hospiceID) {
              selected = 'selected';
            }

            hospice_html += '<option value="' + hospice_data.hospiceID + '" ' + selected + '>' + hospice_data.hospice_name + '</option>';
          }
          $('.edit_hospicename').html(hospice_html);
      });
    });
    
  });
</script>
