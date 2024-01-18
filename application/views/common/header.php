<style>
    .select2-selection__rendered {
        font-weight: normal !important;
    }
</style>
<div class="app app-header-fixed app-custom" id="app">

    <!-- navbar -->
    <div class="app-header navbar">

        <!-- navbar header -->
        <div class="navbar-header bg-dark">
            <button class="pull-right visible-xs dk" data-toggle="class:show" data-target=".navbar-collapse">
                <i class="glyphicon glyphicon-cog"></i>
            </button>
            <button class="pull-right visible-xs toggle_header_button" data-toggle="class:off-screen" data-target=".app-aside" ui-scroll="app">
                <i class="glyphicon glyphicon-align-justify"></i>
            </button>
            <a href="#/" class="navbar-brand text-lt">
                <img src="<?php echo base_url()?>assets/img/eyeglass.png" alt="." class="collapse-brand-img" style="margin-left:4px;margin-top: 15px;max-height: 18px;display:none;" >
                <img src="<?php echo base_url()?>assets/img/smarterchoice1.png" class="uncollapse-brand-img" alt="SmarterChoice" style="max-height:47px;margin-top:9px;margin-left:18px;"/>
            </a>
        </div>
        <!-- / navbar header -->

        <div class="collapse pos-rlt navbar-collapse box-shadow bg-white-only">

            <!-- buttons -->
            <div class="nav navbar-nav hidden-xs">
                <a href="#" class="btn no-shadow navbar-btn folded-portion" data-toggle="class:app-aside-folded" data-target=".app">
                    <i class="fa fa-dedent fa-fw text"></i>
                    <i class="fa fa-indent fa-fw text-active"></i>
                </a>
                <a href class="btn no-shadow navbar-btn" data-toggle="class:show" data-target="#aside-user">
                    <i class="icon-user fa-fw"></i>
                </a>
                <?php
                    if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'distribution_supervisor' || $this->session->userdata('account_type') == 'dispatch' || $this->session->userdata('account_type') == 'sales_rep'){
                ?>
                    <a href="<?php echo base_url() ?>order/work_order" target="_blank" class="btn">
                        <i class="fa fa-file-text-o"></i>
                    </a>
                <?php
                    }
                ?>
            </div>
            <!-- / buttons -->

            <div class="nav navbar-nav">
            <?php
                if($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'hospice_user' && $this->session->userdata('account_type') != 'hospice_admin') :
                    $service_locations = get_service_locations();
                    $current_service_location = get_current_service_location_details($this->session->userdata('user_location'));
            ?>

                        <div class="input-group m-l font-bold" style="width:300px;margin-top:8px;font-size:15px;">
                            <span class="input-group-addon font-bold" id="basic-addon1">Svc Loc:</span>

                            <select name="service_location_selection" class="form-control m-b service_location_selection select2-ready">
                                <?php
                                    // if ($this->session->userdata('userID') == 85) {
                                    if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service') {
                                        if ($this->session->userdata('default_location') == 1) {
                                ?>
                                    <option value="0"> All</option>
                                <?php
                                        }
                                    }
                                ?>
                                
                                <?php
                                    if (!empty($service_locations)) {
                                        foreach ($service_locations as $locations) :
                                ?>
                                            <option value="<?php echo $locations['location_id']; ?>" <?php if ($this->session->userdata('user_location') === $locations['location_id']) { echo 'selected'; } ?> >
                                                <?php echo $locations['service_location_id'].' - '.$locations['user_city'].', '.$locations['user_state']; ?>
                                            </option>
                                <?php
                                        endforeach;
                                    }
                                ?>
                            </select>
                        </div>

            <?php endif;?>
            </div>

            <!-- nabar right -->
            <ul class="nav navbar-nav navbar-right">

              <?php $lname = substr($this->session->userdata('lastname'),0,1); ?>
              <li class="dropdown">
                <a href="#" style=" padding-top: 18px; padding-bottom: 18px;" data-toggle="dropdown" class="dropdown-toggle clear" data-toggle="dropdown">

                  <span ><i class="fa fa-user-circle-o" style="opacity:0.3;margin-right:8px;"></i> <?php echo ucfirst($this->session->userdata('firstname')) ." ". $lname."."  ?></span> <b class="caret"></b>
                </a>

                <!-- dropdown -->
                <ul class="dropdown-menu animated fadeInRight w">

                    <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'hospice_admin' || $this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'dispatch' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') :?>
                        <li role="presentation" class="dropdown-header">Account Management</li>

                        <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'hospice_admin' || $this->session->userdata('account_type') == 'company_admin') :?>
                        <li>
                            <a href="<?php echo base_url('users') ?>">
                                <span>Manage Users</span>
                            </a>
                        </li>
                        
                        <li>
                            <a href="<?php echo base_url('users/register') ?>">Add New User</a>
                        </li>
                        <?php endif;?>


                        <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
                            <li>
                                <a href="<?php echo base_url('hospice') ?>">
                                    Register Account
                                </a>
                            </li>
                        <?php endif;?>

                        <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
                            <li>
                                <a href="<?php echo base_url('service_location') ?>">
                                    Create New Service Location
                                </a>
                            </li>
                        <?php endif;?>

                        <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'dispatch' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') :?>
                            <li>
                                <a href="<?php echo base_url('hospice/hospice_list/active') ?>">
                                    List of Accounts
                                </a>
                            </li>
                            <!-- <li>
                                <a href="<?php echo base_url('hospice/company-list') ?>">
                                    List of Company
                                </a>
                            </li> -->
                        <?php endif;?>
                        
                        <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
                            <li>
                                <a href="<?php echo base_url('service_location/service_location_list') ?>">
                                    List of Service Location
                                </a>
                            </li>
                        <?php endif;?>
                        
                        <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
                            <li>
                                <a href="<?php echo base_url('main/patient_list') ?>">
                                    List of Customers
                                </a>
                            </li>
                            <li class="divider"></li>
                        <?php endif;?>
                    <?php endif;?>
                    <li>
                        <a href="<?php echo base_url('main/logout') ?>">Logout</a>
                    </li>
                </ul>
                <!-- / dropdown -->




              </li>
            </ul>
            <!-- / navbar right -->

        </div>
    </div>
    <!-- / navbar -->
