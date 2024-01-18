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
  <h1 class="m-n font-thin h3">Deleted Records</h1>
</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">
      Deleted Records
    </div>


    <div class="table-responsive">
      <table class="table table-striped m-b-none datatable_table">
        <thead>
          <tr>
              <th><h5>Medical Record Number</h5></th>
              <th><h5>Patient Last Name</h5></th>
              <th><h5>Patient First Name</h5></th>
              <th><h5>Date Deleted</h5></th>
              <th><h5>Actions</h5></th>
          </tr>
        </thead>
        <tbody>
          <?php if(!empty($trashes)) : ?> 
              <?php foreach ($trashes as $trash) : ?>
              <tr>
                <td><?php echo $trash['medical_record_id'] ?></td>
                <td><?php echo $trash['p_lname'] ?></td>
                <td><?php echo $trash['p_fname'] ?></td>
                <td><?php echo date("m/d/Y h:ia", strtotime($trash['date_deleted'])) ?></td>
                <td>
                  <a href="javascript:void(0)" class="retrieve-orders" data-id="<?php echo $trash['trash_id'] ?>" style="text-decoration:none">
                    <button class="btn btn-primary btn-sm OpenSans-Reg">
                      <i class="glyphicon glyphicon-floppy-open OpenSans-Reg" style=""> Restore</i>
                    </button>
                  </a>
                  <a href="javascript:void(0)" class="delete-trash" data-id="<?php echo $trash['trash_id'] ?>">
                    <button class="btn btn-danger btn-sm OpenSans-Reg">
                      <i class="glyphicon glyphicon-trash OpenSans-Reg" style=""> Delete</i>
                    </button>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>


  </div>
</div>