<div class="bg-light lter b-b wrapper-md">

<?php
    if (empty($search_type)) {
?>
    <div class="pull-right header-filter">
        <div class="form-group">
        <?php
            if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'dispatch' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'rt' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor'):
        ?>
                <label class="col-sm-2 hidden-xs control-label mt10 text-right">Sort:</label>
                <label class="col-sm-2 visible-xs-block control-label mt10 text-right"><i class="fa fa-filter"></i></label>
                <div class="col-sm-10 header-filter-option hidden-xs">
                    <select name="hospice_sorting_id" class="form-control m-b select_sort_by select2-ready" id="select_sort_by_id">
                        <option value="all">View All Customers</option>
                        <?php
                            $hospices = account_list_all($this->session->userdata('user_location'));
                            if (!empty($hospices)) {
                        ?>
                            <?php
                                foreach ($hospices as $hospice) :
                                    if ($hospice['hospiceID'] != 13) {
                            ?>
                                        <option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospice_selected == $hospice['hospiceID']) { echo 'selected'; } ?> >
                                            <?php echo $hospice['hospice_name']; ?>
                                        </option>
                            <?php
                                    }
                                endforeach;
                            ?>
                        <?php
                            }
                        ?>
                            <option disabled="disabled">----------------------------------------</option>
                        <?php
                            foreach ($hospices as $hospice) :
                                if ($hospice['hospiceID'] == 13) {
                        ?>
                                    <option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospice_selected == $hospice['hospiceID']) { echo 'selected'; } ?> >
                                        <?php echo $hospice['hospice_name']; ?>
                                    </option>
                    <?php
                                }
                            endforeach;
                    ?>
                    </select>
                </div>
        <?php
            endif;
        ?>
        </div>
    </div>
<?php
  }
?>


<?php
    if (isset($headering)) {
?>
        <h1 class="m-n font-thin h3" id="top_total_entries"><?php echo $headering; ?> (Customers: <?php echo $counting; ?>)</h1>
<?php
    } else {
        if (count($orders) > 1) :
?>
            <h1 class="m-n font-thin h3">View All Customers (Customers: <?php echo $counting; ?>)</h1>
<?php
        else:
?>
            <h1 class="m-n font-thin h3">View Customer (Customer: <?php echo $counting; ?>)</h1>
<?php
        endif;
    }
?>

</div>

<div class="wrapper-md">
<?php
  if (empty($orders)) :
    if ($sign == 0) {
        ?>
      <img src="<?php echo base_url(); ?>assets/img/empty_folder.png" />
      <h4>No Records Found.</h4>
<?php
    } elseif ($sign == 1) {
        ?>
      <div class="col-xs-6 col-sm-4 col-md-3" id="onhold_notice_container">
        <img style="height:140px;width:125px;" src="<?php echo base_url(); ?>assets/img/empty_folder.png" />
        <h4 style="font-size:26px;">On Hand.</h4>
      </div>
<?php
    }
  endif;
?>

  <div class="row patient-list" id="patient_list_container">
    <?php
      if (!empty($orders)) {
          foreach ($orders as $order) :
          $add_id = '';
          $add_class = '';
          if ($search_type == 'dme_item') {
              $add_id = 'first_load';
              $add_class = '';
          } elseif ($search_type == 'dme_lot') {
              $add_id = '';
              $add_class = 'first_batch_load';
          } ?>
          <div class="col-xs-6 col-sm-4 col-md-3 <?php echo $add_class; ?>" id="<?php echo $add_id; ?>">
            <div class="panel wrapper">
              <div class="icon-container bg-info">
                <?php if (!empty($order['organization_id'])) {
              ?>
                  <a href="<?php echo base_url('order/patient_profile/'.$order['medical_record_id'].'/'.$order['organization_id']); ?>" class="link-record">
                <?php
          } else {
              ?>
                  <a href="<?php echo base_url('order/patient_profile/'.$order['medical_record_id'].'/'.$order['ordered_by']); ?>" class="link-record">
                <?php
          }
          if ($order['active_patient'] == 0) {
              ?>
                        <button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;background-color: #7ccfe8;border-color: #7ccfe8;">
                <?php
          } else {
              ?>
                        <button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;">
                <?php
          } ?>

                      <i class="icon-user" style="font-size:65px;"></i>
                    </button>
                  </a>
              </div>

              <h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">MR# <?php echo $order['medical_record_id']; ?> <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:3px;"> LOS: <?php echo $order['length_of_stay']; ?><span> </h4>
              <span class="patient-name"><?php echo $order['p_lname'].', '.$order['p_fname']; ?> <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:1px;"> CUS Days: <?php echo $order['patient_days']; ?></span></span>

            </div>
          </div> <!-- .col-xs-6 col-sm-4 col-md-3 -->
      <?php
        endforeach;
          if ($sign == 2) {
      ?>
            <div class="text-center" id="load_more_content">
              <button class="btn btn-info" style="margin-top:90px;" id="load_more_patient" data-id="onpatient" style="text-align:center;">Load More</button>
            </div>
            <input type="hidden" class="item_tracking_serial_num" value="<?php echo $serial; ?>">
      <?php
          }
          if ($sign == 5) {
      ?>
            <div class="text-center" id="load_more_content">
              <button class="btn btn-info" style="margin-top:90px;" id="load_more_patient_lot_no" style="text-align:center;">Load More</button>
            </div>
            <input type="hidden" class="tracking_lot_no" value="<?php echo $lotNo; ?>">
      <?php
          }
          if ($counting >= 100) {
            if ($sign != 5 && $sign != 2 && $sign != 1) {
      ?>
              <div class="text-center" id="load_more_cont">
                <button class="btn btn-info" id="load_more" style="text-align:center;">Load More</button>
              </div>
    <?php
            }
          }
      } else {
          if ($sign == 1) {
              ?>
          <div class="text-center" id="load_more_content">
            <button class="btn btn-info" style="margin-top:30px;" id="load_more_patient" data-id="onhand" style="text-align:center;">Load More</button>
          </div>
          <input type="hidden" class="item_tracking_serial_num" value="<?php echo $serial; ?>">
    <?php
          }
      }
    ?>
    <input type="hidden" id="total_pages" value="<?php echo $total_pages; ?>" />
  </div> <!--.row patient-list -->

</div>

<div class="bg-light lter b-b wrapper-md">
  <h4 class="m-n font-thin h4" id="bottom_total_entries" style="font-size:16px;font-weight:normal;">Total Customers: <?php echo $counting; ?></h4>
  <?php
    if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'hospice_admin' || $this->session->userdata('account_type') == 'company_admin') {
        ?>
      <h4 class="m-n font-thin h4" id="bottom_patient_los" style="font-size:16px;font-weight:normal;margin-top:5px !important;">Length Of Stay - LOS: <?php echo $total_los_for_today['patient_total_los']; ?></h4>
      <h4 class="m-n font-thin h4" id="bottom_patient_days" style="font-size:16px;font-weight:normal;margin-top:5px !important;">Customer Days - CUS Days: <?php echo $total_patient_days_for_today['total_patient_days']; ?></h4>
  <?php
    }
  ?>
</div>

<input type="hidden" class="total_search_count" value="<?php echo $counting; ?>">
<input type="hidden" class="total_hidden_patient_los" value="<?php echo $total_los_for_today['patient_total_los']; ?>">
<input type="hidden" class="total_hidden_patient_days" value="<?php echo $total_patient_days_for_today['total_patient_days']; ?>">
<div class="bg-light lter wrapper-md">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<script type="text/javascript">
  var current_page = 1;
  var limit = 100;
  $('#patient_list_container').on('click', '#load_more', function(e)
  {
      var hospice_selected = document.getElementById("select_sort_by_id");
      var hospice_id = hospice_selected.options[hospice_selected.selectedIndex].value;
      e.preventDefault();
      var patient_list = $('#patient_list_container');
      patient_list.append('<p class="text-center" id="loading_more"><i class="fa fa-spinner fa-spin fa-2x"></i></p>');

      $(this).parent('#load_more_cont').remove();
      ++current_page;
      $.get(base_url+'order/load_more_new/'+current_page+"/"+limit+"/"+hospice_id, function(response){

        if(typeof response.data != "undefined")
        {
          var temp = "";
          for (i=0; i<response.data.length; i++)
          {
              temp += '<div class="col-xs-6 col-sm-4 col-md-3"><div class="panel wrapper"><div class="icon-container bg-info">';
              if(response.data[i].organization_id != "")
              {
                temp += '<a href="'+base_url+'order/patient_profile/'+response.data[i].medical_record_id+'/'+response.data[i].organization_id+'" class="link-record">';
              }else{
                temp += '<a href="'+base_url+'order/patient_profile/'+response.data[i].medical_record_id+'/'+response.data[i].ordered_by+'" class="link-record">';
              }
              temp += '<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;"><i class="icon-user" style="font-size:65px;"></i></button></a></div><h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">MR# '+response.data[i].medical_record_id+' <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:3px;"> LOS: '+response.data[i].length_of_stay+'<span> </h4><span class="patient-name">'+response.data[i].p_lname+ ', '+ response.data[i].p_fname+' <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:1px;"> CUS Days: '+response.data[i].patient_days+'</span></span></div></div>';
          }
          patient_list.find('#loading_more').remove();
          patient_list.append(temp);

          if(current_page < parseInt($('#total_pages').val()))
          {
            patient_list.append('<div class="text-center" id="load_more_cont"><button class="btn btn-info" id="load_more" style="text-align:center;">Load More</button></div>');
          }
        }
      },"json");

  });

  $('#patient_list_container').on('click', '#load_more_patient_lot_no', function(e)
  {
    var patient_list = $('#patient_list_container');
    var lot_no = $("body").find(".tracking_lot_no").val();
    var total_search_count = $("body").find(".total_search_count").val();

    $(this).parent('#load_more_content').remove();
    patient_list.append('<div id="load_more_new_patient_lot_no" class="col-xs-6 col-sm-4 col-md-3" style="text-align:center;margin-top:130px;font-size:18px;"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
    $.get(base_url+'order/lot_no_tracking_load_more_new/'+lot_no, function(response){
      if(typeof response.data != "undefined")
      {
        var temp = "";
        if(response.data.length != total_search_count)
        {
          for (i=0; i<response.data.length; i++)
          {
              temp += '<div class="col-xs-6 col-sm-4 col-md-3"><div class="panel wrapper"><div class="icon-container bg-info">';
              if(response.data[i].organization_id != "")
              {
                temp += '<a href="'+base_url+'order/patient_profile/'+response.data[i].medical_record_id+'/'+response.data[i].organization_id+'" class="link-record">';
              }else{
                temp += '<a href="'+base_url+'order/patient_profile/'+response.data[i].medical_record_id+'/'+response.data[i].ordered_by+'" class="link-record">';
              }
              if(response.data[i].active_patient == 1)
              {
                temp += '<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;"><i class="icon-user" style="font-size:65px;"></i></button></a></div><h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">MR# '+response.data[i].medical_record_id+' <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:3px;"> LOS: '+response.data[i].length_of_stay+'<span> </h4><span class="patient-name">'+response.data[i].p_lname+ ', '+ response.data[i].p_fname+' <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:1px;"> CUS Days: '+response.data[i].patient_days+'</span> </span> </div></div>';
              }
              else
              {
                temp += '<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;background-color: #7ccfe8;border-color: #7ccfe8;"><i class="icon-user" style="font-size:65px;"></i></button></a></div><h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">MR# '+response.data[i].medical_record_id+' <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:3px;"> LOS: '+response.data[i].length_of_stay+'<span> </h4><span class="patient-name">'+response.data[i].p_lname+ ', '+ response.data[i].p_fname+' <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:1px;"> CUS Days: '+response.data[i].patient_days+'</span> </span> </div></div>';
              }

          }
          patient_list.find('#load_more_new_patient_lot_no').remove();

          $("body").find(".first_batch_load").remove();

          $("body").find("#bottom_total_entries").html("Total Customers: "+response.data.length+"");
          $("body").find("#bottom_patient_los").html("Length Of Stay - LOS: "+response.total_los_for_today.patient_total_los+"");
          $("body").find("#bottom_patient_days").html("Customer Days - CUS Days: "+response.total_patient_days_for_today.total_patient_days+"");
          $("body").find("#top_total_entries").html("Oxygen Lot# Tracking Result(s) (Customers:"+response.data.length+")");
          patient_list.append(temp);
        }
        else
        {
          patient_list.find('#load_more_new_patient_lot_no').remove();
          temp = '<div class="col-xs-6 col-sm-4 col-md-3" style="text-align:center;margin-top:130px;font-size:18px;">No Other Results Found.</div>';
          patient_list.append(temp);
        }

        if(current_page < parseInt($('#total_pages').val()))
        {
          patient_list.append('<div class="text-center" id="load_more_cont"><button class="btn btn-info" id="load_more" style="text-align:center;">Load More</button></div>');
        }
      }
    },"json");
  });

  $('#patient_list_container').on('click', '#load_more_patient', function(e)
  {
    var sign = $(this).attr('data-id');
    var patient_list = $('#patient_list_container');
    var serial_no = $("body").find(".item_tracking_serial_num").val();
    var total_search_count = $("body").find(".total_search_count").val();
    var total_hidden_patient_los = $("body").find(".total_hidden_patient_los").val();
    var total_hidden_patient_days = $("body").find(".total_hidden_patient_days").val();

    if(sign == "onpatient")
    {
      patient_list.append('<div id="loading_more" class="col-xs-6 col-sm-4 col-md-3" style="text-align:center;margin-top:110px;font-size:18px;"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
    }
    else
    {
      patient_list.append('<div id="loading_more" class="col-xs-6 col-sm-4 col-md-3" style="text-align:center;margin-top:60px;font-size:18px;"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
    }
    $(this).parent('#load_more_content').remove();
    $.get(base_url+'order/item_tracking_load_more_new/'+sign+"/"+serial_no, function(response){
      if(typeof response.data != "undefined")
      {
        var temp = "";
        if(response.data.length != 0)
        {
          for (i=0; i<response.data.length; i++)
          {
              temp += '<div class="col-xs-6 col-sm-4 col-md-3"><div class="panel wrapper"><div class="icon-container bg-info">';
              if(response.data[i].organization_id != "")
              {
                temp += '<a href="'+base_url+'order/patient_profile/'+response.data[i].medical_record_id+'/'+response.data[i].organization_id+'" class="link-record">';
              }else{
                temp += '<a href="'+base_url+'order/patient_profile/'+response.data[i].medical_record_id+'/'+response.data[i].ordered_by+'" class="link-record">';
              }
              if(response.data[i].active_patient == 1)
              {
                temp += '<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;"><i class="icon-user" style="font-size:65px;"></i></button></a></div><h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">MR# '+response.data[i].medical_record_id+'  <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:3px;"> LOS: '+response.data[i].length_of_stay+'<span> </h4><span class="patient-name">'+response.data[i].p_lname+ ', '+ response.data[i].p_fname+' <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:1px;"> CUS Days: '+response.data[i].patient_days+'</span></span> </div></div>';
              }
              else
              {
                temp += '<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;background-color: #7ccfe8;border-color: #7ccfe8;"><i class="icon-user" style="font-size:65px;"></i></button></a></div><h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">MR# '+response.data[i].medical_record_id+'  <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:3px;"> LOS: '+response.data[i].length_of_stay+'<span> </h4><span class="patient-name">'+response.data[i].p_lname+ ', '+ response.data[i].p_fname+' <span class="pull-right" style="font-size:12.5px;color:rgba(99, 90, 90, 0.91) !important; margin-top:1px;"> CUS Days: '+response.data[i].patient_days+'</span></span> </div></div>';
              }
          }
          patient_list.find('#loading_more').remove();
          $("body").find("#first_load").remove();
          $("body").find("#onhold_notice_container").remove();

          var new_total_customers = Number(response.data.length) + Number(total_search_count);
          var new_total_customer_los = Number(response.total_los_for_today.patient_total_los) + Number(total_hidden_patient_los);
          var new_total_customer_days = Number(response.total_patient_days_for_today.total_patient_days) + Number(total_hidden_patient_days);

          $("body").find("#bottom_total_entries").html("Total Customers: "+ new_total_customers +"");
          $("body").find("#bottom_patient_los").html("Length Of Stay - LOS: "+ new_total_customer_los +"");
          $("body").find("#bottom_patient_days").html("Customer Days - CUS Days: "+ new_total_customer_days +"");
          $("body").find("#top_total_entries").html("Equipment Tracking Result(s) (Customers:"+ new_total_customers +")");
          patient_list.append(temp);
        }
        else
        {
          patient_list.find('#loading_more').remove();
          temp = '<div class="col-xs-6 col-sm-4 col-md-3" style="text-align:center;margin-top:130px;font-size:18px;">No Other Results Found.</div>';
          patient_list.append(temp);
        }

        if(current_page < parseInt($('#total_pages').val()))
        {
          patient_list.append('<div class="text-center" id="load_more_cont"><button class="btn btn-info" id="load_more" style="text-align:center;">Load More</button></div>');
        }
      }
    },"json");
  });

</script>



<!--  <div class="comment-container" style="float:right;">
  <a href="javascript:void(0)" name="comment-modal" style="text-decoration:none;cursor:pointer" class="comments_link" data-id="<?php echo $order['uniqueID']; ?>">
     <i style="float:left;margin-top: 4px;margin-right: 7px;" class=" icon-speech"></i>
     <p style="float:left;"><?php echo $order['comment_count']; ?></p>
  </a>
</div> -->
