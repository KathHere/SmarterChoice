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

</style>

<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">Customer List</h1>
</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">
      Customer List
    </div>
    <div class="table-responsive">
      <table class="table table-striped m-b-none patient_list_datatable">
        <thead>
          <tr>
            <th  style="width:10%">Medical Record No.</th>
            <th  style="width:15%">Customer Last Name</th>
            <th  style="width:15%">Customer First Name</th>
            <th  style="width:15%">Customer Address</th>
            <th  style="width:10%">Next of Kin</th>
            <th  style="width:12%">Phone #</th>
            <th  style="width:12%">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            // if(!empty($patients)){
            //   foreach ($patients as $patient):
          ?> 
                <!-- <tr>
                  <td><?php echo $patient['medical_record_id'] ?></td>
                  <td><?php echo $patient['p_lname'] ?></td>
                  <td><?php echo $patient['p_fname'] ?></td>
                  <td><?php echo $patient['p_placenum'].", ".$patient['p_street'].", ".$patient['p_city'].", ".$patient['p_state'].", ".$patient['p_postalcode']." " ?></td>
                  <td><?php echo $patient['p_nextofkin'] ?></td>
                  <td><?php echo $patient['p_phonenum'] ?></td>
                  <td>
                    <button type="button" class="btn btn-danger delete_patient" data-patient-id="<?php echo $patient['patientID'] ?>" data-medical-id="<?php echo $patient['medical_record_id'] ?>">Delete</button>
                  </td>
                </tr> -->
          <?php 
            //   endforeach;
            // }else{
          ?>
              <!-- <tr>
                <td colspan="7" style="text-align: center;">No data.</td>
              </tr> -->
          <?php
            // }
          ?>
          <!-- End sa condition para sa dili empty nga array :) -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<script type="text/javascript">

	$(document).ready(function(){

		var datatable = $('.patient_list_datatable').DataTable({
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
          url: base_url+"main/get_patient_list"
      },
      "columns": [
          { "data": "medical_record_id" },
          { "data": "p_lname" },
          { "data": "p_fname" },
          { "data": "customer_address" },
          { "data": "p_nextofkin" },
          { "data": "p_phonenum" },
          { "data": "actions" }
      ],
      // "order": [[ 5, 'desc' ]],
      "columnDefs":[
          // {
          //     "targets": 0,
          //     "searchable": false,
          //     "orderable": false
          // },
          {
              "targets": 6,
              "searchable": false,
              "orderable": false
          }
      ]
    });
    $.fn.dataTable.ext.errMode = 'none';
	});

</script>