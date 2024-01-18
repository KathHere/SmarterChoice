<table class="table table-striped m-b-none patient_order_list_table">
  <thead>
    <tr>
      <th  style="width:10%">Order Date</th>
      <th  style="width:15%">Patient Last Name</th>
      <th  style="width:15%">Patient First Name</th>
      <th  style="width:10%">Medical Record Number</th>
      <th  style="width:10%">Activity Type</th>
      <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?> 
        <th  style="width:12%">Provider</th>
      <?php endif ;?>
      
      <th  style="width:15%" class="hide_on_print">Work Order/ WO#</th>
      <th  style="width:10%" class="hide_on_print">Status Notes</th>
      <th  style="width:15%">Order Status</th>
    </tr>
  </thead>
  <tbody class="">

  </tbody>
</table>
<input type="hidden" class="sessioned_account_type" value="<?php echo $this->session->userdata('account_type'); ?>">
<input type="hidden" class="selected_activity_status" value="<?php echo $status; ?>">

<script type="text/javascript">
  $(document).ready(function()
  {
      var sessioned_account_type = $("body").find(".sessioned_account_type").val();
      var selected_activity_status = $("body").find(".selected_activity_status").val();
      if(sessioned_account_type == "dme_admin" || sessioned_account_type == 'dme_user')
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
              "lengthMenu": [10,25,50,75,100,200,300,500],
              "pageLength": 10,
              "processing": true,
              "serverSide": true,
              "responsive": true,
              "deferRender": true,
              "ajax": {
                  url: base_url+"order/get_cus_order_status_data_by_activity_type/"+ selected_activity_status
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
              "lengthMenu": [10,25,50,75,100,200,300,500],
              "pageLength": 10,
              "processing": true,
              "serverSide": true,
              "responsive": true,
              "deferRender": true,
              "ajax": {
                  url: base_url+"order/get_cus_order_status_data_by_activity_type/"+ selected_activity_status
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
  });
</script>

<script src="<?php echo base_url() ?>assets/js/common.js"></script>