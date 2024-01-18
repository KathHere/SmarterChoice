<div class="bg-light lter b-b wrapper-md">

  <div class="pull-right header-filter">
    <div class="form-group">
      <?php if ($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor') :?>
        <label class="col-sm-2 hidden-xs control-label mt10 text-right">Sort:</label>
        <label class="col-sm-2 visible-xs-block control-label mt10 text-right"><i class="fa fa-filter"></i></label>
        <div class="col-sm-10 header-filter-option hidden-xs">
          <select name="hospice_sorting_id" class="form-control m-b select_sort_by_noorder select2-ready">
            <?php
              $hospices = account_list_by_status($this->session->userdata('user_location'), 1);
            ?>
            <option value="all">View All Customers</option>
            <?php
              if (!empty($hospices)) {
            ?>
                  <?php
                    foreach ($hospices as $hospice) :
                      if ($hospice['hospiceID'] != 13) {
                          ?>
                        <option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospice_selected == $hospice['hospiceID']) {
                              echo 'selected';
                          } ?> ><?php echo $hospice['hospice_name']; ?></option>
                  <?php
                      }
                  endforeach; ?>
            <?php
              }
            ?>
              <option disabled="disabled">----------------------------------------</option>
            <?php
              foreach ($hospices as $hospice) :
                if ($hospice['hospiceID'] == 13) {
                    ?>
                  <option value="<?php echo $hospice['hospiceID']; ?>" <?php if ($hospice_selected == $hospice['hospiceID']) {
                        echo 'selected';
                    } ?> ><?php echo $hospice['hospice_name']; ?></option>
            <?php
                }
              endforeach;
            ?>
          </select>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'hospice_user') :?>
    <div id="delete_button_wrapper" class="pull-right" style="display: none">
      <button id="delete_selected_draft_customer_button" class="btn btn-info btn-sm">(<span id="delete_selected_counter">0</span>) Delete Selected Draft Customers</button>
      &nbsp;&nbsp;
      <button id="delete_all_draft_customer_button" class="btn btn-primary btn-sm">Delete All Draft Customers</button>
    </div>
  <?php endif; ?>

  <?php if (count($orders) > 1) :?>
    <h1 class="m-n font-thin h3">View All Draft Customers (Customers: <?php echo $counting; ?>)</h1>
  <?php else:?>
    <h1 class="m-n font-thin h3">View Draft Customer (Customer: <?php echo $counting; ?>)</h1>
  <?php endif; ?>

</div>

<div class="wrapper-md">
  <?php
    if ($counting == 0) {
        ?>
      <img src="<?php echo base_url(); ?>assets/img/empty_folder.png" />
      <h4>No Records Found.</h4>
<?php
    }
?>
  <div class="row patient-list" id="patient_list_container">
    <?php
      if (!empty($orders)):
        foreach ($orders as $order):
    ?>
          <div class="col-xs-6 col-sm-4 col-md-3">
            <div class="panel wrapper">
              <div class="icon-container bg-info">
                  <a href="<?php echo base_url('draft_patient/patient_profile/'.$order['medical_record_id'].'/'.$order['ordered_by']); ?>" class="link-record">
                  <button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;">
                    <i class="icon-user" style="font-size:65px;"></i>
                  </button>
                  </a>
              </div>

              <h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">MR# <?php echo $order['medical_record_id']; ?></h4>
              <span class="patient-name"><?php echo $order['p_lname'].', '.$order['p_fname']; ?></span>
              <?php if ($this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'distribution_supervisor' && $this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'hospice_user') { ?>
              <div class="checkbox">
                <label class="i-checks">
                  <!-- <input type="checkbox" id="" data-patient-id="<?php echo $order['patientID']; ?>" class="checkboxes">
                  <i class="grey_inner_shadow"></i> -->
                </label>
                <ul style="padding-left: 20px; margin-top: -30px;">
                  <input type="checkbox" id="" data-patient-id="<?php echo $order['patientID']; ?>" class="delete_draft_customer_checkbox">
                  Delete Draft Customer
                </ul>
                
              </div>
              <?php } ?>
            </div>
          </div>
    <?php
        endforeach;
        if ($counting >= 100) {
            ?>
          <div class="text-center" id="load_more_cont">
            <button class="btn btn-info" id="load_more" style="text-align:center;">Load More</button>
          </div>
    <?php
        }
      endif;
    ?>
    <input type="hidden" id="total_pages" value="<?php echo $total_pages; ?>" />
  </div>
</div>

<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">TOTAL CUSTOMERS: <?php echo $counting; ?></h1>
</div>

<div class="bg-light lter wrapper-md">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<input type="hidden" id="location_name" value="<?php echo $location_details['user_city'].','.$location_details['user_state']; ?>">

<script type="text/javascript">
  var current_page = 1;
  var limit = 100;
  $('#patient_list_container').on('click', '#load_more', function(e)
  {
      e.preventDefault();
      var patient_list = $('#patient_list_container');
      patient_list.append('<p class="text-center" id="loading_more"><i class="fa fa-spinner fa-spin fa-2x"></i></p>');
      //$(this).fadeOut();
      $(this).parent('#load_more_cont').remove();
      ++current_page;
      $.get(base_url+'draft_patient/load_more/'+current_page+"/"+limit, function(response){

        console.log(response.data);
        if(typeof response.data != "undefined")
        {
          //alert('hello');
          var temp = "";
          for (i=0; i<response.data.length; i++)
          {
              temp += '<div class="col-xs-6 col-sm-4 col-md-3"><div class="panel wrapper"><div class="icon-container bg-info">';
              if(response.data[i].organization_id != "")
              {
                temp += '<a href="'+base_url+'draft_patient/patient_profile/'+response.data[i].medical_record_id+'/'+response.data[i].organization_id+'" class="link-record">';
              }else{
                temp += '<a href="'+base_url+'draft_patient/patient_profile/'+response.data[i].medical_record_id+'/'+response.data[i].ordered_by+'" class="link-record">';
              }
              temp += '<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;"><i class="icon-user" style="font-size:65px;"></i></button></a></div><h4 class="m-t-lg text-info-lter" style="margin-top: 13px;">MR# '+response.data[i].medical_record_id+'</h4><span class="patient-name">'+response.data[i].p_lname+ ', '+ response.data[i].p_fname+' </span></div></div>';
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

  $("#patient_list_container").on('click', '.delete_draft_customer_checkbox', function(e) {
    var _this = $(this);
    var deleteSelectedCounter = parseInt($('#delete_selected_counter').html());
    console.log('deleteSelectedCounter', deleteSelectedCounter);
    if (_this.is(":checked")) {
      console.log('Checked');
      deleteSelectedCounter++;
    } else {
      console.log('Unchecked');
      deleteSelectedCounter--;
    }

    $('#delete_selected_counter').html(deleteSelectedCounter);

    if (deleteSelectedCounter > 0 ) {
      $('#delete_button_wrapper').show();
    } else {
      $('#delete_button_wrapper').hide();
    }
  });

  $("#delete_button_wrapper").on('click', '#delete_selected_draft_customer_button', function(e) {
    var deleteSelectedCounter = parseInt($('#delete_selected_counter').html());

    // jConfirm("Do you want to delete ("+deleteSelectedCounter+") draft customers?","Warning", function(response){
    jConfirm("Are you sure you want to delete this customer?","Warning", function(response){
      if(response)
      {
        var selected_draft_patients = $('.delete_draft_customer_checkbox');
        var selected_dp = "";
        var counter = 0;
        selected_draft_patients.each(function(){
            if($(this).is(':checked')) {
                if(counter == 0) {
                  selected_dp = $(this).attr('data-patient-id');
                } else {
                  selected_dp = selected_dp + "-" + $(this).attr('data-patient-id');
                }
                counter++;
            }
        });

        console.log('selected_draft_patients', selected_dp);
        $.get(base_url+'draft_patient/delete_selected_draft_customers/'+selected_dp, function(response){
          console.log("resepposnse:", response);
          var obj = $.parseJSON(response);
          
          setTimeout(function(){
            if(obj['error'] == 0)
            {
              me_message_v2({error:0,message:obj['message']});
              setTimeout(function(){
                  location.reload();
              },2000);
            } else {
              me_message_v2({error:1,message:"Error!"});
            }
          },1);
        });
      }
    });
  });

  $("#delete_button_wrapper").on('click', '#delete_all_draft_customer_button', function(e) {
    var hospiceID = $('.select_sort_by_noorder').val();
    var hospiceName = $(".select_sort_by_noorder :selected").html();
    var locationName = $("#location_name").val();
    
    if (hospiceID == "all") {
      hospiceID = 0;
      hospiceName = locationName;
    }
    
    console.log('hospiceName', hospiceName);
    // jConfirm("Do you want to delete all draft customers under "+hospiceName+"?","Warning", function(response){
    jConfirm("Are you sure you want to delete all the customers?","Warning", function(response){
      if(response)
      {
        $.get(base_url+'draft_patient/delete_all_draft_customers/'+hospiceID, function(response){
          console.log("resepposnse:", response);
          var obj = $.parseJSON(response);
          
          setTimeout(function(){
            if(obj['error'] == 0)
            {
              me_message_v2({error:0,message:obj['message']});
              setTimeout(function(){
                  location.reload();
              },2000);
            } else {
              me_message_v2({error:1,message:"Error!"});
            }
          },1);
        });
      }
    });
  });

</script>