<!-- <div class="col-md-offset-4" style="padding-top: 130px;">
  <div class="container w-xxl w-auto-xs" style="background-color: rgba(255,255,255,0.3);box-shadow: 0px 0px 11px #BABABA;border-radius: 2px;" >
  <a href class="navbar-brand block m-t"><img src="<?php echo base_url(); ?>assets/img/smarterchoice.png" style="margin-left: 40px;padding-top: 30px;margin-top: 17px" class="img-responsive" ></a>
  <div class="m-b-lg">
    <div class="wrapper text-center">
      <strong></strong>
    </div>
    <form action="<?php echo base_url('main/process_login'); ?>" method="POST" id="user_login_form">
        <div class="text-danger wrapper text-center" ng-show="authError">
        </div>
        <div class="list-group list-group-sm">
          <div class="list-group-item">
            <input type="text" name="username" placeholder="Username" class="form-control no-border" required>
          </div>
          <div class="list-group-item">
             <input type="password" name="password" placeholder="Password" class="form-control no-border" required>
          </div>
        </div>
        <button type="submit" class="btn btn-lg btn-primary btn-block" style="opacity:0.8" >Log in</button> -->
        <!-- <div class="text-center m-t m-b"><a ui-sref="access.forgotpwd">Forgot password?</a></div>
        <div class="line line-dashed"></div>
        <p class="text-center"><small>Do not have an account?</small></p>
        <a class="btn btn-lg btn-default btn-block">Create an account</a> -->
    <!-- </form>
  </div>

</div>
</div> -->

<div class="login-container" style="padding-top: 130px;">
  <div class="container w-xxl w-auto-xs" style="background-color: #FCFDFD;box-shadow: 0px 0px 10px #BABABA;border-radius: 2px;" >
  <a href class="navbar-brand block m-t"><img src="<?php echo base_url(); ?>assets/img/smarterchoice.png" style="margin-left: 40px;padding-top: 30px;margin-top: 17px" class="img-responsive" ></a>
  <div class="m-b-lg">
    <div class="wrapper text-center">
      <strong></strong>
    </div>
    <form action="<?php echo base_url('main/process_login'); ?>" method="POST" id="user_login_form">
        <div class="text-danger wrapper text-center" ng-show="authError">

            <?php
                if ($failed == true) {
            ?>
                    <span class="fa fa-warning"></span>
                    Invalid Username or Password!
            <?php
                } else {
            ?>
                    &nbsp;
            <?php
                }

                if ($account_inactive == true) {
            ?>
                    <span class="fa fa-warning"></span>
                    Account Inactive!
            <?php
                }
            ?>

        </div>
        <div class="list-group list-group-sm">
          <div class="list-group-item" style="margin-bottom:15px;padding:0px 0px !important">
            <input type="text" name="username" id="login_uname" placeholder="Username" class="form-control no-border grey_inner_shadow" required style="text-transform:none !important;height:40px">
          </div>
          <div class="list-group-item" style="padding:0px 0px !important">
             <input type="password" name="password" id="login_pass" placeholder="Password" class="form-control no-border grey_inner_shadow" required style="text-transform:none !important;height:40px">
          </div>
        </div>
        <div style="margin-left:2px">
          <p>Need Help?</p>
          <p style="margin-top:-10px">Contact Us: <strong>(702) 248-0056</strong></p>
        </div>
        <button type="submit" class="btn btn-lg btn-primary btn-block" style="opacity:0.8" >Log in</button>
        <!-- <div class="text-center m-t m-b"><a ui-sref="access.forgotpwd">Forgot password?</a></div>
        <div class="line line-dashed"></div>
        <p class="text-center"><small>Do not have an account?</small></p>
        <a class="btn btn-lg btn-default btn-block">Create an account</a> -->
    </form>
  </div>

</div>
</div>