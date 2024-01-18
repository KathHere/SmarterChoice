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

.status-count li{
  padding-left: 15px;
  padding-right: 15px;
}

.medical_record_id_col
{
  width: 15% !important;
}

.pos_list_div
{
  position: relative;
}

.dataTables_wrapper .dataTables_processing {
  background: #bfbfbff5 !important;
  background-color: #bfbfbff5 !important;
  color:#fff !important;
  height: 60px !important;
}

@media (max-width: 545px){
  /*.status-count-li{
    width:100%;
  }*/
}

</style>

<?php
  // $all_enroutes = get_all_enroute();

?>

<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">Customer Order Status</h1>
</div>

<?php if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'customer_service' || $this->session->userdata('account_type') == 'distribution_supervisor') :?>
<div class="wrapper-md pb0 rowX">
  <div class="col-xs-12X clearfix">
    <div class="checkbox pull-right">

      <label class="i-checks">
        <input type="checkbox" class="send_to_confirm_workorder" name="move_enroute[]" value="" /><i></i> Send <span class="fa fa-truck" style="font-size:25px;color:#f0ad4e;margin-left:0px"></span> to Confirm WO#
        <?php /* if(!empty($all_enroutes)) :?>
          <?php foreach($all_enroutes as $enroute) :?>
            <input type="hidden" class="hdn_send_to_confirm_workorder" name="hdn_move_enroute[]" data-enroute-id="<?php echo $enroute['medical_record_id'] ?>" data-enroute-unique-id="<?php echo $enroute['uniqueID'] ?>" value="<?php echo $enroute['uniqueID'] ?>" />
          <?php endforeach;?>
        <?php endif; */?>
      </label>

    </div>
  </div>
</div>
<?php endif;?>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">
      Order Status
      <?php
          $activity_counts = get_count_status_new_v2($this->session->userdata('user_location'));
          $status_counts = array(
                    "En route"    => $activity_counts[1]['total'],
                    "On Hold"     => $activity_counts[3]['total'],
                    "Pending"     => $activity_counts[0]['total'],
                    "Rescheduled" => $activity_counts[2]['total']
                  );
          $label = array("btn-primary","btn-warning","btn-success","btn-danger");
          $index = 0;

          if (empty($selected_patientID)) {
      ?>
              <div class="pull-right" id="pos_activity_counter">
                <ul class="status-count list-inline">
                <?php
                  foreach($status_counts as $key=>$value):
                    if($value>0):
                ?>
                      <li class="pull-left">
                        <span class="pos_counter" data-id="<?php echo $key; ?>" style="cursor:pointer;"><?php echo $key; ?></span>&nbsp;
                        <span><strong><?php echo $value; ?></strong></span>
                      </li>
                <?php
                    endif;
                  $index++;
                  endforeach;
                ?>
                </ul>
              </div>
      <?php
          }
      ?>

      <?php if($order_status_list != 'true') :?>
        <div class="pull-right">
          <select class="form-control select-view" style="margin: -7px;margin-right: -1px;width: 130px;">
            <option value="grid-view">Grid View</option>
            <option value="list-view" selected="">List View</option>
          </select>
        </div>
      <?php endif;?>
    </div>

    <div class="table-responsive pos_list_div">
      <table class="table table-striped m-b-none patient_order_list_table">
        <thead>
            <th style="width:10%">Order Date</th>
            <th style="width:15%">Customer Last Name</th>
            <th style="width:15%">Customer First Name</th>
            <th style="width:10%">Medical Record Number</th>
            <th style="width:10%">Activity Type</th>
            <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "biller" || $this->session->userdata('account_type') == "customer_service" || $this->session->userdata('account_type') == "distribution_supervisor") :?>
              <th  style="width:12%">Provider</th>
            <?php endif ;?>

            <th style="width:15%" class="hide_on_print">Work Order/ WO#</th>
            <th style="width:10%" class="hide_on_print">Status Notes</th>
            <th style="width:15%">Order Status</th>
        </thead>
        <tbody class="">

        </tbody>
        <input type="hidden" id="selected_patientID" value="<?php echo $selected_patientID; ?>">

      </table>
    </div>
    <input type="hidden" class="sessioned_account_type" value="<?php echo $this->session->userdata('account_type'); ?>">
    <div class="panel-footer clearfix">
      <?php
          if (empty($selected_patientID)) {
      ?>
              <div class="clearfix text-center pull-right" id="pos_activity_counter_second"></div>
      <?php
        }
      ?>
    </div>
  </div>
</div>


<div class="bg-light lter wrapper-md">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<div class="modal fade" id="reason_for_cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10010;">
  <div class="modal-dialog" style="top: 100px;left: 345px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Reason for Cancel</h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="patient_weight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="top: 100px;left: 345px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Customer Weight</h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="lot_numbers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="top: 100px;left: 345px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Lot Number</h4>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<input type="hidden" class="reload_cos_sign" value="1">

<script type="text/javascript">
  $(document).ready(function(){

    function first_()
    {
      setTimeout(function(){
        var current_sign = $("body").find(".reload_cos_sign").val();
        if(current_sign == 1)
        {
          setTimeout('window.location.reload();', 210000);
        }
        else
        {
          setTimeout('window.location.reload();', 1800000);
        }
        second_();
      },90000);
    }

    function second_()
    {
      setTimeout(function(){
        var current_sign = $("body").find(".reload_cos_sign").val();
        if(current_sign == 1)
        {
          setTimeout('window.location.reload();', 210000);
        }
        else
        {
          setTimeout('window.location.reload();', 1800000);
        }
        first_();
      },90000);
    }

    setTimeout(function(){
      first_();
    },500);

    $('body').on('click','.comments_link',function(){
      $("body").find(".reload_cos_sign").val(0);
    });

    $('body').on('click','.view_order_details',function(){
      $("body").find(".reload_cos_sign").val(0);
    });

    $('body').on('click','.modal-body .btn-danger',function(){
      $("body").find(".reload_cos_sign").val(1);
    });

    $('body').on('click','#globalModal .close',function(){
      $("body").find(".reload_cos_sign").val(1);
    });

    //Customer order status counts
    var get_pos_count_coantainer_length = $('body').find('.pos_statuses_counter_new').length;

    $.get(base_url+"order/get_orders_statuses",function(response){
        $('#pos_activity_counter').html(response);
        $("#pos_activity_counter_second").html(response);
    })

    $('body').on('click','.pos_counter',function(){
      var status = $(this).attr('data-id');
      var new_status = "";
      if(status == "En route")
      {
        new_status = "Enroute";
      }
      else if(status == "On Hold")
      {
        new_status = "OnHold";
      }
      else if(status == "Pending")
      {
        new_status = "Pending";
      }
      else
      {
        new_status = "Rescheduled";
      }
      $('.pos_list_div').html("<div style='text-align:center;margin-top:25px;margin-bottom:25px;font-size:17px !important;'><h4>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></h4></div>");
      $.post(base_url+"order/order_list_by_status/"+ new_status, function(response){
        $('.pos_list_div').html(response);
      });
    });

    var sessioned_account_type = $("body").find(".sessioned_account_type").val();
    var selected_patientID = $("body").find(".patient_order_list_table").find("#selected_patientID").val();
    if(sessioned_account_type == "dme_admin" || sessioned_account_type == "dme_user" || sessioned_account_type == "biller" || sessioned_account_type == "customer_service" || sessioned_account_type == "distribution_supervisor")
    {
        var datatable = $('.patient_order_list_table').DataTable({
            'createdRow': function( row, data, dataIndex ) {
                var order = JSON.stringify(data);
                $(row).attr('class', 'order_list_tr');

                $.get(base_url+"order/get_activity_type_name/",{patientID:data.patientID,addressID:data.addressID,status_activity_typeid:data.status_activity_typeid,isRecurring:data.is_recurring},function(response){
                    $(row).find(".order_list_td_activity_type").text(response);
                });
                $.get(base_url+"order/get_order_list_status_select/",{order:data},function(response){
                    $(row).find(".order_list_td_select_order_status").html(response);
                });
            },
            "language": {
               "processing": "Retrieving data. Please wait..."
            },
            "lengthMenu": [10,25,50,75,100,200,300,500],
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "deferRender": true,
            "ajax": {
                url: base_url+"order/get_cus_order_status_data/"+ selected_patientID
            },
            "columns": [
                { "data": "order_date_data" },
                { "data": "last_name_data" },
                { "data": "first_name_data" },
                { "data": "medical_record_id" },
                { "data": "activity_type_spinner" },
                { "data": "hospice_name" },
                { "data": "work_order_no_data" },
                { "data": "order_notes_data" },
                { "data": "order_status_spinner" }
            ],
            "order": [[ 0, 'desc' ]],
            "columnDefs":[
                {
                    "targets": 3,
                    "className": "medical_record_id_col"
                },
                {
                    "targets": 4,
                    "className": "order_list_td_activity_type",
                    "searchable": false,
                    "orderable": false
                },
                {
                    "targets": 6,
                    "className": "hide_on_print",
                    "orderable": false
                },
                {
                    "targets": 7,
                    "className": "comment-container hide_on_print",
                    "searchable": false,
                    "orderable": false
                },
                {
                    "targets": 8,
                    "className": "order_list_td_select_order_status",
                    "searchable": false,
                    "orderable": false
                }
            ]
        });
    }
    else
    {
        var datatable = $('.patient_order_list_table').DataTable({
            'createdRow': function( row, data, dataIndex ) {
                var order = JSON.stringify(data);
                $(row).attr('class', 'order_list_tr');

                $.get(base_url+"order/get_activity_type_name/",{patientID:data.patientID,addressID:data.addressID,status_activity_typeid:data.status_activity_typeid,isRecurring:data.is_recurring},function(response){
                    $(row).find(".order_list_td_activity_type").text(response);
                });
                $.get(base_url+"order/get_order_list_status_select/",{order:data},function(response){
                    $(row).find(".order_list_td_select_order_status").html(response);
                });
            },
            "language": {
               "processing": "Retrieving data. Please wait..."
            },
            "lengthMenu": [10,25,50,75,100,200,300,500],
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "deferRender": true,
            "ajax": {
                url: base_url+"order/get_cus_order_status_data/"+ selected_patientID
            },
            "columns": [
                { "data": "order_date_data" },
                { "data": "last_name_data" },
                { "data": "first_name_data" },
                { "data": "medical_record_id" },
                { "data": "activity_type_spinner" },
                { "data": "work_order_no_data" },
                { "data": "order_notes_data" },
                { "data": "order_status_spinner" }
            ],
            "order": [[ 0, 'desc' ]],
            "columnDefs":[
                {
                    "targets": 3,
                    "className": "medical_record_id_col"
                },
                {
                    "targets": 4,
                    "className": "order_list_td_activity_type",
                    "searchable": false,
                    "orderable": false
                },
                {
                    "targets": 5,
                    "className": "hide_on_print",
                    "orderable": false
                },
                {
                    "targets": 6,
                    "className": "comment-container hide_on_print",
                    "searchable": false,
                    "orderable": false
                },
                {
                    "targets": 7,
                    "className": "order_list_td_select_order_status",
                    "searchable": false,
                    "orderable": false
                }
            ]
        });
    }
    $.fn.dataTable.ext.errMode = 'none';

    $('body').on('click','.patient_order_list_table .view_order_details',function(){
        var medical_record_id = $(this).attr('data-id');
        var hospice_id        = $(this).attr('data-value');
        var unique_id         = $(this).attr('data-unique-id');
        var act_id            = $(this).attr('data-act-id');
        var patient_id        = $(this).attr('data-patient-id');

        if(act_id != 3)
        {
            modalbox(base_url + 'order/view_order_status_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id+ "/" + patient_id,{
                header:"Work Order",
                button: false,
            });
        }
        else
        {
            modalbox(base_url + 'order/view_exchange_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id+ "/" + patient_id,{
              header:"Work Order",
              button: false
            });
        }
        return false;
    });


  });
</script>