<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SmarterChoice Logistics</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Style+Script&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/all.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/adminlte.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/design.css">

        <!-- Global Modal -->
        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10000;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"></h4>
                    </div>
                    <div class="modal-body ajax_modal clearfix"></div>
                    <div class="modal-footer"></div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </head>
    <body class="dark-mode hold-transition sidebar-mini layout-navbar-fixed logistics-body">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item">
                    <label class="d-block font-weight-lighter ml-2 mt-2">
                        <span class="text-sm">SVC LOC:</span>
                    </label>
                </li>
                <li class="nav-item">
                    <label class="d-block font-weight-lighter mb-0 ml-2 mt-1">
                        <div class="dropdown">
                            <?php
                                $service_locations = get_service_locations();
                                $lgsts_user = $this->session->userdata('lgsts_user');
                                $current_service_location = get_current_service_location_details($lgsts_user['account_location']);
                            ?>
                            <button style="width: 300px;" data-toggle="dropdown" class="text-left btn btn-sm bg-white nowrap rounded-0">
                                <?php echo $current_service_location['service_location_id'].' - '.$current_service_location['user_city'].', '.$current_service_location['user_state']; ?>&nbsp;
                                <i class="fas float-right mt-1 fa-caret-down"></i>
                            </button>
                            <div class="dropdown-menu bg-white py-0 rounded-0" style="width: 300px;">
                                <?php
                                    if (!empty($service_locations)) {
                                        foreach ($service_locations as $locations) :
                                ?>
                                            <a href="#" class="dropdown-item py-2">
                                              <?php echo $locations['service_location_id'].' - '.$locations['user_city'].', '.$locations['user_state']; ?>
                                            </a>
                                <?php
                                        endforeach;
                                    }
                                ?>
                            </div>
                        </div>  
                    </label>
                </li>
            </ul>

            <!-- Right navbar links -->
            <!-- Right navbar links -->
<ul class="navbar-nav ml-auto">
    <!-- Messages Dropdown Menu -->
    <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle text-uppercase" data-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle mr-2" style="opacity:0.4;"></i>
            <span class="nav-name"> <?php echo $lgsts_user['first_name']; ?> <?php echo substr($lgsts_user['last_name'],0,1); ?>.</span>
            <i class="fas fa-caret-down"></i>
        </a> 
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right ">
            <div class="pt-3 text-center px-4">
                <i class="fas fa-user-circle" style="opacity:0.4; font-size: 40px;"></i>
            </div>
            <div class="text-center pt-2 text-uppercase">
                <span class="nav-full-name"><?php echo $lgsts_user['first_name']; ?> <?php echo $lgsts_user['last_name']; ?></span> 
                <div style="letter-spacing: 0.3px;">
                    <?php 
                        if ($lgsts_user['user_type'] == 'super_admin') {
                            echo "SUPER ADMIN";
                        } else if ($lgsts_user['user_type'] == 'admin') {
                            echo "ADMIN";
                        } else if ($lgsts_user['user_type'] == 'dispatcher') {
                            echo "DISPATCHER";
                        } else if ($lgsts_user['user_type'] == 'screener') {
                            echo "SCREENER";
                        } else if ($lgsts_user['user_type'] == 'driver') {
                            echo "DRIVER";
                        } else if ($lgsts_user['user_type'] == 'screener_and_driver') {
                            echo "SCREENER & DRIVER";
                        }
                    ?>
                </div>
            </div>
            <span class="dropdown-item dropdown-header"></span>
            <div class="dropdown-divider"></div>
            <a href="<?php echo base_url('lgsts_users/profile') ?>" class="dropdown-item py-2">
                <i class="fas fa-user-circle"></i>
                <span style="margin-left: 3%;"></span>Profile
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?php echo base_url('lgsts_users/add_user') ?>" class="dropdown-item py-2">
                <i class="fas fa-user-plus mr-2"></i> 
                 <span style="margin-left: -1%;"></span>Add New User 
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?php echo base_url('main/lgsts_logout') ?>" class="dropdown-item py-2">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout 
            </a>
        </div>
    </li>
</ul>

        </nav>
        <!-- /.navbar -->
