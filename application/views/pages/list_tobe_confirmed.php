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

#disable_confirmation_modal
{
  top:100px;
  position:fixed;
}

.medical_record_id_col
{
  width: 15% !important;
}

.dataTables_wrapper .dataTables_processing {
  background: #bfbfbff5 !important;
  background-color: #bfbfbff5 !important;
  color:#fff !important;
  height: 60px !important;
}

</style>

<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">Confirm Work Orders</h1>
</div>

<div class="row" style="">
</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">
      Order Status
    </div>
    <div class="table-responsive cwo_list_div" style="position: relative;">
      <table class="table table-striped m-b-none list_tobe_confirmed_table">
        <thead>
          <tr>
            <th style="width:10%">Order Date</th>
            <th style="width:15%">Customer Last Name</th>
            <th style="width:15%">Customer First Name</th>
            <th style="width:10%">Medical Record Number</th>
            <th style="width:10%">Activity Type</th>
            <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller') :?>
              <th  style="width:12%">Provider</th>
            <?php endif ;?>
            <th style="width:15%" class="hide_on_print">Work Order/ WO#</th>
            <th style="width:10%" class="hide_on_print">Status Notes</th>
            <th style="width:15%">Order Status</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
        <input type="hidden" id="selected_patientID_confirm" value="<?php echo $selected_patientID; ?>">

      </table>
      <input type="hidden" class="sessioned_account_type_confirm" value="<?php echo $this->session->userdata('account_type'); ?>">
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

<div class="modal fade" id="patient_weight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10010;">
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

<div class="modal fade" id="lot_numbers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10010;">
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

<div class="modal fade " id="not_confirmed_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index:10010;">
  <div class="modal-dialog disable_confirmation_modal" id="popup_dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Warning</h4>
      </div>
      <div class="modal-body">
        <p class="bold warning_modal_content" style="font-size:17px;"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style type="text/css">

.disable_confirmation_modal
{
  top:100px;
  left:0;
  right: 0;
  margin:auto;
}

</style>

<script type="text/javascript">
  $(document).ready(function(){

    var sessioned_account_type = $("body").find(".sessioned_account_type_confirm").val();
    var selected_patientID = $("body").find(".list_tobe_confirmed_table").find("#selected_patientID_confirm").val();
    if(sessioned_account_type == "dme_admin" || sessioned_account_type == 'dme_user' || sessioned_account_type == "biller")
    {
        var datatable = $('.list_tobe_confirmed_table').DataTable({
            'createdRow': function( row, data, dataIndex ) {
                var order = JSON.stringify(data);
                $(row).attr('class', 'order_list_tr list_tobe_confirmed_tr_'+data.uniqueID);

                $.get(base_url+"order/get_activity_type_name/",{patientID:data.patientID,addressID:data.addressID,status_activity_typeid:data.status_activity_typeid,isRecurring:data.is_recurring},function(response){
                    $(row).find(".order_list_td_activity_type").text(response);
                });
                $.get(base_url+"order/get_order_list_status_select_confirm/",{order:data},function(response){
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
                url: base_url+"order/get_cus_order_status_data_confirm/"+ selected_patientID
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
    $.fn.dataTable.ext.errMode = 'none';

    $('body').on('click','.list_tobe_confirmed_table .view_order_details',function(){
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

