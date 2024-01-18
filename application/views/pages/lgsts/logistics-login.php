<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - SmarterChoice Logistics</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/adminlte.min.css">
</head>
<style type="text/css">
  :root{
    --main-color:  #7266ba; 
  }
</style>
<body class="dark-mode hold-transition login-page">
<div class="login-box">
 <img src="https://images.unsplash.com/photo-1516733968668-dbdce39c4651?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" style="position:absolute;top:0;bottom:0;right:0;left:0;height:100vh;width:100vw;object-fit: cover;opacity: 0.1;z-index: 0;">
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
       <div class="login-logo pt-4 pb-1">
          <a href="ndex2.html">  
            <img height="100" src="<?php echo base_url(); ?>assets/img/smarterchoice-logistics-logo3.png" alt="SmarterChoice Logo" >
          </a>
        </div>
      <div class="text-center mb-3" >
        <?php
          if ($lgsts_login_failed == true) {
        ?>
            <span class="text-danger"> <span class="fas fa-exclamation-triangle"></span> Username or Password incorrect.</span>
        <?php
          }
        ?>
      </div>
      <form action="<?php echo base_url('main/process_lgsts_login'); ?>" method="POST" id="lgsts_login_form">
        <div class="input-group mb-4">
          <input style="height:45px;" type="text" class="form-control rounded-0" name="username" placeholder="Username">
          <div class="input-group-append ">
            <div class="input-group-text rounded-0">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-4">
          <input style="height:45px;" type="password" class="form-control rounded-0" name="password" placeholder="Password">
          <div class="input-group-append rounded-0">
            <div class="input-group-text rounded-0">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <p class="mb-0">Need Help? Contact Us:</p>
            <p><i class="fas fa-phone-alt mr-2"></i> <strong>(702) 248-0056 </strong></p>
          </div>
          <!-- /.col -->
        </div>
        <div class="social-auth-links text-center mb-3">
          <button type="submit" class="btn btn-block btn-flat btn-info" >
            <i class="fas fa-sign-in-alt mr-2"></i> LOGIN
          </button>
        </div>
      </form>
      <!-- /.social-auth-links -->
      <br/>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url(); ?>assets/js/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/js/adminlte.min.js"></script>
</body>
</html>
