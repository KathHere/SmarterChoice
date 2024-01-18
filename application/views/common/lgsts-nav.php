<!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
    
        <a href="#" class="brand-link text-center border-0" style="background-color: #3a3e51 !important;">
        <img src="<?php echo base_url(); ?>assets/img/smarterchoice-logistics-logo3.png" alt="AdminLTE Logo" height="83" >
        </a>
        <!-- Sidebar -->
        <div class="sidebar">
        <!-- <div class="brand-text pt-3  d-block text-center font-weight-light">SmarterChoice Logistics</div> -->
        <br/>
        <!-- Sidebar Menu -->
        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                <li class="nav-item text-sm mb-3">
                    <span class='px-4' style="letter-spacing: 0.3;opacity: 0.4;">NAVIGATION</span>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>lgsts_dashboard/main" class="nav-link <?php echo $active_nav == 'dashboard' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Show User(s)
                        </p>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>lgsts_daily_routes/route_list" class="nav-link <?php echo $active_nav == 'daily_routes' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-map"></i>
                        <p>
                            Daily Routes
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>lgsts_dispatch_order_status/order_list" class="nav-link <?php echo $active_nav == 'dispatch_order_status' ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Dispatch Orders
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Reports
                        </p>
                    </a>
                </li> 
            </ul>
        </nav>
        <div class="mt-5 pt-5 text-left px-5" >
            <div>DME PROVIDER:</div>
            <div><strong> Advantage Home Medical Services</strong></div>
        </div>
        <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
