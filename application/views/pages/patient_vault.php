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
  <h1 class="m-n font-thin h3">Patient Records</h1>
</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">
      Order Summary
      <!-- <div class="pull-right">
        <select class="form-control select-view" style="margin: -7px;margin-right: -1px;width: 130px;">
          <option value="grid-view">Grid View</option>
          <option value="list-view" selected="">List View</option>
        </select>
      </div> -->
    </div>

    <div class="table-responsive">
      <table class="table table-striped datatable_table">
        <thead>
          <tr>
            <th>Date Ordered</th>
            <th>Work Order</th>
            <th>Patient Last Name</th>
            <th>Patient First Name</th>
            <th>Medical Record Number</th>
            <th>Activity Type</th>
            <th>Organization Name</th>
            <th>Status</th>
            <th>Details</th>
            <th>Notes</th>
            <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
              <th>Actions</th>
            <?php endif ;?>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($orders)) : ?>  
            <?php foreach ($orders as $order) :?>
              <tr>
                <td><?php echo $order['date_ordered'] ?></td>
                <td>WO#<?php echo substr($order['uniqueID'],4,10) ?></td>
                <td><?php echo $order['p_lname'] ?></td>
                <td ><?php echo $order['p_fname'] ?></td>
                <td><?php echo $order['medical_record_id'] ?></td>
                <td><?php echo $order['activity_name'] ?></td>
                <td ><?php echo $order['hospice_name'] ?></td>
                <td><?php echo $order['order_status'] ?></td>
                <td ><a href="<?php echo base_url('order/patient_profile/'.$order['medical_record_id']) ?>"><button class="btn btn-info">Details</button></a></td>
                <td><a href="javascript:void(0)" name="comment-modal" style="text-decoration:none;cursor:pointer" class="comments_link" data-id="<?php echo $order['uniqueID'] ?>"><i class="icon-speech"></i><p style="float: right;margin-top: -3px;margin-right: 11px;"><?php echo $order['comment_count'] ?></p></a></td>
                <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
                  <td><a href="javascript:void(0)" class="delete-confirmed-orders" data-id="<?php echo $order['uniqueID'] ?>"><button class="btn btn-danger">Delete</button></a></td>
                <?php endif;?>
              </tr>
               <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>