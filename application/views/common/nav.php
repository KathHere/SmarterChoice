<style>
  .dropbtn {
      background-color: #2e3344;
      color: white;
      padding: 16px;
      border: none;
      cursor: pointer;
  }

  .dropdown_reports {
      position: relative;
      display: inline-block;
  }

  .dropdown-content {
      display: none;
      position: fixed !important;
      background-color: #3a3f51;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      overflow:visible !important;
      margin-left:210px !important;
      /*margin-top: -41.5px;*/
  }

  .dropdown-content a {
      color: #adb2c4;
      padding: 10px 35px !important;
      text-decoration: none;
      display: block;
  }

  .dropdown-content a:hover {background-color: #2e3344; color:#fff !important;}

  .dropdown_reports:hover .dropdown-content, .dropdown_reports .dropdown-content:hover {
      display: block !important;
      z-index: 1000 !important;
      overflow:visible !important;
  }

  .dropdown_reports:hover .dropbtn {
      background-color: #3e8e41;
  }

  .navi ul.main-nav>li,
    .navi ul.nav-sub>li{
      position:static;
    }

    .navi ul.nav li a{
    white-space:wrap;
  }
  .app-header-fixed.app-aside-folded .navi ul.nav:not(.nav-sub) > li {
    width: 60px;
    overflow:hidden;
    white-space: nowrap;
  }
  .app-header-fixed.app-aside-folded .navi ul.nav:not(.nav-sub) > li a, .nav.main-nav{
    width: 210px !important;
    white-space: nowrap;
  }
  .app-header-fixed.app-aside-folded .navi ul.nav:not(.nav-sub) > li:not(:hover) a > span.font-bold{
    color:transparent;
  }
  .app-header-fixed.app-aside-folded .navi ul.nav:not(.nav-sub) > li:hover {
        width:210px;
       overflow:visible;
  }

  @media (max-width:770px){
    .app-aside.off-screen{
      z-index: 1020;
    }
  }

  .dot_nav {
    background-color: #23b7e5;
    color: white;
    border-radius: 50%;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    display: inline-block;
    /* font-weight: bold; */
    line-height: 24px;
    margin-right: 5px;
    text-align: center;
    width: 24px;
    min-width: 24px;
  }

  .dot_nav_v2 {
    background-color: #23b7e5;
    color: white;
    border-radius: 50%;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    display: inline-block;
    line-height: 24px;
    text-align: center;
    width: 24px;
    min-width: 24px;
  }
</style>

<!-- menu -->
<?php
  $hospice_id = $this->session->userdata("group_id");
  $user_type = $this->session->userdata("account_type");
?>

    <div class="app-aside hidden-xs bg-dark">
      <div class="aside-wrap">
        <div class="navi-wrap">
          <!-- user -->
          <div class="clearfix hidden-xs text-center hide" id="aside-user">
            <div class="dropdown wrapper">
              <a ui-sref="app.page.profile">
                <span class="thumb-lg w-auto-folded avatar m-t-sm">
                </span>
              </a>
              <span class="clear">
                  <span class="block m-t-sm">
                    <strong class="font-bold text-lt"><?php echo $this->session->userdata('lastname') .", ". $this->session->userdata("firstname")  ?></strong>
                  </span>
                <span class="text-muted text-xs block"><?php echo $this->session->userdata('group_name') ?></span>
              </span>
            </div>
            <div class="line dk hidden-folded"></div>
          </div>
          <!-- / user -->

          <!-- nav -->
          <nav ui-nav class="navi hidden-print" style="position:fixed; z-index:2000;">
            <ul class="nav main-nav">
              <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                <span translate="aside.nav.HEADER">Navigation</span>
              </li>


              <li  >
                <a href="<?php echo base_url() ?>menu" class="auto">
                  <i class=" icon-home icon text-info-lter"></i>
                  <span class="font-bold">Home</span>
                </a>
              </li>

        <?php if($user_type == "hospice_admin" || $user_type == "hospice_user") :?>
              <li  >
                <a href="<?php echo base_url("order/create_order")."/".$hospice_id ?>" class="auto">
                  <i class="icon icon-user text-info-lter"></i>
                  <span class="font-bold">Create New Customer</span>
                </a>
              </li>

        <?php elseif($user_type != "dispatch"):?>
         <li  >
                <a href="<?php echo base_url("order/create_order") ?>" class="auto">
                  <i class="icon icon-user text-info-lter"></i>
                  <span class="font-bold">Create New Customer</span>
                </a>
              </li>

        <?php endif;?>

        <li >
            <a href class="auto">
              <span class="pull-right text-muted">
                <i class="fa fa-fw fa-angle-right text"></i>
                <i class="fa fa-fw fa-angle-down text-active"></i>
              </span>
              <i class=" icon-notebook icon text-info-lter"></i>
              <span class="font-bold">Customer Menu</span>
            </a>
            <ul class="nav nav-sub dk">
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>order/search/">
                  <span>Customer Search</span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>order/order_list/grid-view">
                  <span>View All Customers</span>
                </a>
              </li>
            </ul>
        </li>

        <!--added March 14, 2016 view for showing all draft patient "MARJ"-->
        <?php if ($user_type != "dispatch" && $user_type != "rt") { ?>
         <!-- <li  >
            <a href="<?php echo base_url() ?>draft_patient/customers">
              <i class=" icon-notebook icon text-info-lter"></i>
              <span class="font-bold">Draft Customers</span>
            </a>
          </li> -->
        <?php } ?>
        <!-- END -->

        <li  >
          <a href="<?php echo base_url() ?>order/order_list/" class="auto">
            <i class=" icon-folder-alt icon text-info-lter"></i>
            <span class="font-bold">CUS Order Status </span>
            <span class="dot_nav_v2">
              <?php 
                $activity_counts = get_pending_orders_count($this->session->userdata('user_location'));
                echo $activity_counts[0]['total']; 
              ?>
            </span>
          </a>
        </li>

        <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'dispatch' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'distribution_supervisor') :?>
        <li >
            <a href class="auto">
              <span class="pull-right text-muted">
                <i class="fa fa-fw fa-angle-right text"></i>
                <i class="fa fa-fw fa-angle-down text-active"></i>
              </span>
              <i class=" icon-notebook icon text-info-lter"></i>
              <span class="font-bold">Item Tracking</span>
            </a>
            <ul class="nav nav-sub dk">
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>order/tracking">
                  <span>DME Item Tracking</span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>order/lot_number_tracking">
                  <span>Oxygen Lot# Tracking</span>
                </a>
              </li>
            </ul>
        </li>
        <?php endif ;?>

        <?php if ($user_type != "dispatch" && $user_type != "rt" && $user_type != "hospice_user") { ?>
        <li>
          <a <?php if($this->session->userdata('account_active_sign') == 2){ echo 'href="javascript:void(0)"'; } else { echo 'href="'.base_url().'equipment/all_equipments_by_hospice"'; } ?> class="auto">
            <i class=" icon-social-dropbox icon text-info-lter"></i>
            <span class="font-bold">Fee Schedule</span>
          </a>
        </li>
        <?php } ?>
        <?php
        // $this->session->userdata('userID') == 11 || $this->session->userdata('userID') == 63 || $this->session->userdata('userID') == 169 || $this->session->userdata('userID') == 85 || $this->session->userdata('userID') == 324
          if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller'){
        ?>
          <li >
            <a href class="auto">
              <span class="pull-right text-muted">
                <i class="fa fa-fw fa-angle-right text"></i>
                <i class="fa fa-fw fa-angle-down text-active"></i>
              </span>
              <i class="fa fa-money text-info-lter"></i>
              <span class="font-bold">Billing Menu</span>
            </a>
            <ul class="nav nav-sub dk">
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>billing/search/">
                  <span>Account Statement </br> Search</span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>billing_statement/billing_list/">
                  <span>View All Account </br> Statements</span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>billing_statement/statement_draft/">
                  <span>Draft Statements</span>
                </a>
              </li>
              <!-- <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>billing_statement/statement_activity/">
                  <span>Activity Statements</span>
                </a>
              </li> -->
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>billing_statement/statement_invoice_inquiry_from_to/">
                  <span>Invoice Inquiry List</span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>billing_statement/statement_letter/">
                  <span>Statement</span> &nbsp;&nbsp;
                  <span class="dot_nav">
                    <?php echo get_statement_letter_counter($this->session->userdata('user_location')); ?>
                  </span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>billing_reconciliation/payment_history/">
                  <span> History</span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>billing/collection_list/">
                  <span>Collections</span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>billing_reconciliation/reconciliation_history/">
                  <span>Reconcile</span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>billing_reconciliation/statement_archive/">
                  <span>Payment Archive</span>
                </a>
              </li>
              <li ui-sref-active="active" class="dropdown_reports">
                <a href="javascript:void(0)" class="dropbtn" style="">
                  <span>Reports</span>
                </a>
                <div class="dropdown-content" style="">
                  <a href="<?php echo base_url() ?>billing/statement_service_date_report"> Service Dates</a>
                </div>
              </li>
            </ul>
        </li>
      <?php
        }
      ?>

      <?php
            if(hasAcccessHospiceBilling($hospice_id)) {
          ?>
         <li >
            <li  >
                <a href="<?php echo base_url("billing_statement/statement_bill_by_hospice")."/".$hospice_id ?>" class="auto">
                  <i class="icon icon-user text-info-lter"></i>
                  <span class="font-bold">Billing</span>
                </a>
              </li>
        </li>
      <?php } ?>

          <?php
            if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'hospice_admin' || $this->session->userdata('account_type') == 'company_admin' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor'){
          ?>
           <li >
            <a href class="auto">
              <span class="pull-right text-muted">
                <i class="fa fa-fw fa-angle-right text"></i>
                <i class="fa fa-fw fa-angle-down text-active"></i>
              </span>
              <i class=" icon-notebook icon text-info-lter"></i>
              <span class="font-bold">Reports</span>
            </a>
            <ul class="nav nav-sub dk">
              <?php
                if($this->session->userdata('account_type') != 'rt') {
              ?>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>report">
                  <span>Daily Activity Status</span>
                </a>
              </li>
              <li ui-sref-active="active" class="dropdown_reports">
                <a href="javascript:void(0)" class="dropbtn" style="">
                  <span>Activity Status</span>
                </a>
                <div class="dropdown-content" style="">
                  <a href="<?php echo base_url() ?>report/activity_status_details/NewPT">New CUS</a>
                  <a href="<?php echo base_url() ?>report/activity_status_details/NewItem">New Item</a>
                  <a href="<?php echo base_url() ?>report/activity_status_details/Exchange">Exchange</a>
                  <a href="<?php echo base_url() ?>report/activity_status_details/Pickup">Pickup</a>
                  <a href="<?php echo base_url() ?>report/activity_status_details/PTMove">CUS Move</a>
                  <a href="<?php echo base_url() ?>report/activity_status_details/Respite">Respite</a>
                </div>
              </li>

              <li ui-sref-active="active" class="dropdown_reports">
                <a href="javascript:void(0)" class="dropbtn">
                  <span>Residence Status</span>
                </a>
                <div class="dropdown-content">
                  <a href="<?php echo base_url() ?>report/residence_status_details/AssistedLiving">Assisted Living</a>
                  <a href="<?php echo base_url() ?>report/residence_status_details/GroupHome">Group Home</a>
                  <a href="<?php echo base_url() ?>report/residence_status_details/HicHome">Hic Home</a>
                  <a href="<?php echo base_url() ?>report/residence_status_details/HomeCare">Home Care</a>
                  <a href="<?php echo base_url() ?>report/residence_status_details/SkilledNursingFacility">Skilled Nursing Facility</a>
                </div>
              </li>
              <li ui-sref-active="active" class="dropdown_reports">
                <a href="javascript:void(0)" class="dropbtn">
                  <span>Item Usage</span>
                </a>
                <div class="dropdown-content">
                  <a href="<?php echo base_url() ?>report/item_usage_details/capped_item_usage">Capped Items</a>
                  <a href="<?php echo base_url() ?>report/item_usage_details/noncapped_item_usage">Non-Capped Items</a>
                  <a href="<?php echo base_url() ?>report/item_usage_details/disposable_item_usage">Disposable Items</a>
                </div>
              </li>
              <li ui-sref-active="active">
                <a href="<?php echo base_url() ?>report/reports_by_user">
                  <span>Staff Entries</span>
                </a>
              </li>
              <?php
                }

              if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor' || $this->session->userdata('account_type') == 'rt'){
            ?>
              <li ui-sref-active="active">
                <a href="<?php echo base_url() ?>report/o2concentrator_follow_up">
                  <span>Oxygen Concentrator <br /> Follow Up Report</span>
                </a>
              </li>
              <?php
                if ($this->session->userdata('account_type') == 'dme_admin') {
              ?>
                <li ui-sref-active="active">
                  <a href="<?php echo base_url() ?>census/active_customers">
                    <span>Active Census</span>
                  </a>
                </li>
              <?php
                }
                if ($this->session->userdata('account_type') != 'rt') {
              ?>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>order/recurring_order_report">
                  <span>Recurring Order Status</span>
                </a>
              </li>
              <?php
                }
              }
              ?>
            </ul>
          </li>
          <?php
            }
            if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'dispatch' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor' || $this->session->userdata('account_type') == 'customer_service' )
            {
              if ($this->session->userdata('user_location') != 0 || $this->session->userdata('userID') == 85) {
          ?>
          <li >
            <a href class="auto">
              <span class="pull-right text-muted">
                <i class="fa fa-fw fa-angle-right text"></i>
                <i class="fa fa-fw fa-angle-down text-active"></i>
              </span>
              <i class="fa fa-barcode icon text-info-lter"></i>
              <span class="font-bold">Inventory</span>
            </a>
            <ul class="nav nav-sub dk">
              <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'distribution_supervisor') {?>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>inventory/purchase_order_inquiry">
                  <span>Purchase Order Inquiry</span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>inventory/purchase_order_look_up">
                  <span>Purchase Order Look up</span>
                </a>
              </li>
               <li ui-sref-active="active"  >
                <a href="<?php echo base_url(); ?>inventory/equipment_transfer_status">
                  <span>
                    Equipment Transfer
                    </br>
                    Status
                  </span>
                </a>
              </li>
              <li ui-sref-active="active" class="dropdown_reports">
                <a href="javascript:void(0)" class="dropbtn" style="">
                  <span>Reports</span>
                </a>
                <div class="dropdown-content" style="">
                  <a href="<?php echo base_url() ?>inventory/item_reconciliation"> Item Reconciliation</a>
                  <a href="<?php echo base_url() ?>inventory/purchase_item"> Purchase Item</a>
                  <a href="<?php echo base_url() ?>inventory/vendor_cost"> Vendor Cost</a>
                  <a href="<?php echo base_url() ?>inventory/purchase_item_graph"> Purchase Items Graph</a>
                  <a href="<?php echo base_url() ?>inventory/run_item_no"> Run Item Numbers</a>
                </div>
              </li>
              <?php } ?>
              <li ui-sref-active="active" class="dropdown_reports">
                <a href="javascript:void(0)" class="dropbtn" style="">
                  <span>Item</span>
                </a>
                <div class="dropdown-content" style="">
                  <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') { ?>
                  <a href="<?php echo base_url() ?>inventory/add_new_item">Add New Item</a>
                  <?php } ?>
                  <a href="<?php echo base_url() ?>inventory/item_search">Item Search</a>
                  <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'distribution_supervisor') { ?>
                  <a href="<?php echo base_url() ?>inventory/inventory_item_list">Inventory Item List</a>
                  <?php } ?>
                </div>
              </li>
              <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'distribution_supervisor') { ?>
              <li ui-sref-active="active" class="dropdown_reports">
                <a href="javascript:void(0)" class="dropbtn" style="">
                  <span>Vendor</span>
                </a>
                <div class="dropdown-content" style="">
                  <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') { ?>
                  <a href="<?php echo base_url() ?>inventory/add_new_vendor">Add New Vendor</a>
                  <?php } ?>
                  <a href="<?php echo base_url() ?>inventory/vendor_search">Vendor Search</a>
                  <a href="<?php echo base_url() ?>inventory/all_vendors">View All Vendors</a>
                </div>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>inventory/draft_orders">
                  <span>Draft Orders</span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>inventory/cancelled_order_req">
                  <span>Canceled Orders</span>
                </a>
              </li>
              <?php } ?>
            </ul>
          </li>
          <?php
              }
            }
          ?>
        <!-- <li>
          <a href="<?php echo base_url() ?>gallery/beds" class="auto">
            <i class=" icon-camera icon text-info-lter"></i>
            <span class="font-bold">Item Photo Gallery</span>
          </a>
        </li> -->

          <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller') :?>
          <li >
            <a href class="auto">
              <span class="pull-right text-muted">
                <i class="fa fa-fw fa-angle-right text"></i>
                <i class="fa fa-fw fa-angle-down text-active"></i>
              </span>
              <i class=" icon-notebook icon text-info-lter"></i>
              <span class="font-bold">Work Order Menu</span>
            </a>
            <ul class="nav nav-sub dk">

              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>order/list_tobe_confirmed">
                  <span>Confirm Work Orders</span>
                </a>
              </li>
              <li ui-sref-active="active"  >
                <a href="<?php echo base_url() ?>canceled_order/canceled">
                  <span>Canceled Orders</span>
                </a>
              </li>
            </ul>
        </li>

         <?php endif ;?>

        <!-- li class="line dk">

        </li> -->

        <li><img src="<?php echo base_url()?>assets/img/eyeglass.png" alt="." class="collapse-brand-img" style="margin-left:4px;margin-top: 80px;max-height: 18px;display:none;" ></li>
        <li id="dme_provider_text"><p class="provider_txt" style="margin-left: 20px;margin-top: 50px;color: #727581;display:block"><a href="http://www.ahmslv.com" target="_blank">DME PROVIDER: <br/><strong>Advantage Home <br/> Medical Services</strong></p></li></a>

        <?php /*
             <li>
                <?php $id = $this->session->userdata('userID') ;?>
                <a href class="auto">
                  <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                  </span>
                  <i class=" icon-notebook icon text-info-lter"></i>
                  <span class="font-bold">Orders</span>
                </a>
                <ul class="nav nav-sub dk">
                  <li ui-sref-active="active">
                    <a href="<?php echo base_url() ?>order/create-order/">
                      <span>Create New Order</span>
                    </a>
                  </li>
                  <li ui-sref-active="active">
                    <a href="<?php echo base_url() ?>order/search/">
                      <span>PT Order Summary</span>
                    </a>
                  </li>
                  <li ui-sref-active="active">
                    <a href="<?php echo base_url() ?>order/deleted_orders/">
                      <span>Deleted Orders</span>
                    </a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="<?php echo base_url() ?>order/patient-vault" class="auto">
                  <i class=" icon-drawer icon text-info-lter"></i>
                  <span class="font-bold">Patient Vault</span>
                </a>
              </li>
              <?php if($this->session->userdata('account_type') == 'dme_admin') :?>
                <li>
                  <a href="<?php echo base_url() ?>equipment/all_equipments" class="auto">
                    <i class=" icon-social-dropbox icon text-info-lter"></i>
                    <span class="font-bold">Item</span>
                  </a>
                </li>

              <?php endif ;?> */ ?>



              <!--<li class="line dk hidden-folded"></li>

              <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                <span translate="aside.nav.your_stuff.YOUR_STUFF">Your Stuff</span>
              </li>
              <li>
                <a ui-sref="app.page.profile">
                  <i class="icon-user icon text-success-lter"></i>
                  <b class="badge bg-success pull-right">30%</b>
                  <span translate="aside.nav.your_stuff.PROFILE">Profile</span>
                </a>
              </li>-->
            </ul>
          </nav>
          <!-- nav -->

          <!-- aside footer -->
          <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
      <?php /*
          <div class="wrapper m-t">
            <div class="text-center-folded">
              <span class="pull-right pull-none-folded">60%</span>
              <span class="hidden-folded" translate="aside.MILESTONE">Goal for the Month</span>
            </div>
            <div class="progress progress-xxs m-t-sm dk">
              <div class="progress-bar progress-bar-info" style="width: 60%;">
              </div>
            </div>
            <div class="text-center-folded">
              <span class="pull-right pull-none-folded">35%</span>
              <span class="hidden-folded" translate="aside.RELEASE">Paid Hospices</span>
            </div>
            <div class="progress progress-xxs m-t-sm dk">
              <div class="progress-bar progress-bar-primary" style="width: 35%;">
              </div>
            </div>
          </div>
      */?>
          <?php endif ;?>
          <!-- / aside footer -->
        </div>
      </div>
    </div>
    <!-- / menu -->


    <!-- content -->
    <div class="app-content">
      <div ui-butterbar></div>
      <a href class="off-screen-toggle hide" data-toggle="class:off-screen" data-target=".app-aside" ></a>
      <div class="app-content-body fade-in-up">

      <script type="text/javascript">

        $(document).ready(function(){

          $('.navi ul.nav-sub>li').on('mouseover', function() {
            var $menuItem = $(this),
                $submenuWrapper = $('> .dropdown-content', $menuItem);
            var menuItemPos = $menuItem.position();
            $submenuWrapper.css({top: menuItemPos.top+50});
          });

        });

      </script>

