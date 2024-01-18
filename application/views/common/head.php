<!DOCTYPE html>
<html lang="en" data-ng-app="app">
<head>
  <meta charset="utf-8" />
  <title><?php echo !empty($title) ? $title : 'Advantage Home Medical Service v2'; ?></title>
  <meta name="description" content="app, web app, responsive, responsive layout, admin, admin panel, admin dashboard, flat, flat ui, ui kit, AngularJS, ui route, charts, widgets, components" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" type="text/css" />
  <link rel='shortcut icon' type='image/x-icon' href='<?php echo base_url(); ?>assets/img/favicon.png' />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/simple-line-icons.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/app.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/jquery/datatables/dataTables.bootstrap.css" type="text/css" />
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.min.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.theme.min.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.structure.min.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/global.modal/global.modal.css">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/jquery-alerts/jquery.alerts.css">

  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/morris/morris.css">

  <link rel="stylesheet" type="text/css" media="print" href="<?php echo base_url(); ?>assets/css/media_print.css">

  <script src="<?php echo base_url(); ?>assets/js/tagsinput.css"></script>
  <link rel='stylesheet' href='<?php echo base_url();?>assets/css/fullcalendar/fullcalendar.min.css' />
  <link rel='stylesheet' href='<?php echo base_url();?>assets/css/fullcalendar/fullcalendar.print.min.css' media='print' />

  <!-- magic Slider -->
  <link href="<?php echo base_url(); ?>assets/css/magic_carousel.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
  <!-- End of Magic Slider -->

  <script src="<?php echo base_url(); ?>assets/js/jquery/jquery.min.js"></script>

  <!-- preloader -->
  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/preloader/preloader.css">
  <script src="<?php echo base_url(); ?>assets/js/preloader/preloader.js"></script>

  <!--push notification -->
  <link rel="manifest" href="manifest.json">
  <script type="text/javascript">
      var base_url = "<?php echo base_url(); ?>";
  </script>
</head>
<body class="whole-body" ng-controller="AppCtrl">


<input type="hidden" id="account_type_id" value="<?php echo $this->session->userdata('account_type'); ?>" />
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
<!-- #m-container -->
