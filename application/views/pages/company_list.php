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
  <h1 class="m-n font-thin h3">Company List</h1>
</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">
      Company List
    </div>

    <div class="table-responsive">
      <table class="table table-striped m-b-none ">
        <thead>
          <tr>
              <th class="">Company ID</th>
              <th class="">Company Account #</th>
              <th class="">Company Name</th>
              <th class="">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if(!empty($companies)){
              foreach ($companies as $company):
          ?>
                <tr>
                  <td class=""><?php echo $company->hospiceID ?></td>
                  <td class=""><?php echo $company->hospice_account_number ?></td>
                  <td class=""><?php echo $company->hospice_name ?></td>
                  <td class="">
                    <button type="button" class="btn btn-info btn-xs create_random_account_number" data-toggle="modal" data-target="#edit_hospice<?php echo $company->hospiceID ?>"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
                    <button type="button" class="btn btn-danger btn-xs delete-hospice-btn" data-id="<?php echo $company->hospiceID ?>"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                    <a href="<?php echo base_url('equipment/list_equipments/'.get_code($company->hospiceID)) ?>">
                      <button type="button" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-file"></i> Assign Item</button>
                    </a>
                  </td>
                </tr>
          <?php
              endforeach;
            }else{
          ?>
              <tr>
                <td colspan="4" style="text-align: center;">No data.</td>
              </tr>
          <?php
            }
          ?> <!-- End sa condition para sa dili empty nga array :) -->
        </tbody>
      </table>
    </div>
  </div>
</div>





<?php if(!empty($companies)) : ?>
  <?php foreach ($companies as $company) : ?>

<div class="modal fade edit_hospice" id="edit_hospice<?php echo $company->hospiceID ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:90px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Edit Company Form</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
        <div class="row">
            <div class="">
          <div class="col-md-12">
        <div class="container">
          <div class="col-md-5" style="padding: 25px;margin-left: 3%;">

            <div class="form-container" style="padding: 15px;border-radius:4px;">
              <form action="<?php echo base_url('hospice/update_company/'.get_code($company->hospiceID)) ;?>" method="POST" id="">

                <div class="form-group">
                  <label for="exampleInputEmail1">Company Account Number</label>

                  <?php if($company->hospice_account_number != 0) :?>
                    <input type="text" name="hosp_acct_number" class="form-control" id="" placeholder="" value="<?php echo $company->hospice_account_number ?>" readonly>
                  <?php else:?>
                    <input type="text" name="hosp_acct_number" class="form-control edit_hospice_account_num" id="" placeholder="" readonly>
                  <?php endif;?>

                </div>

                <div class="form-group">
                  <label for="exampleInputEmail1">Company Name</label>
                  <textarea name="hospice_name" class="form-control" id="" placeholder=""><?php echo $company->hospice_name ?></textarea>
                </div>


                <div class="form-group">
                  <label for="exampleInputEmail1">Contact Number</label>
                  <input type="text" name="hospice_contact_num" class="form-control hosp_contact_num" id="" placeholder="" value="<?php echo $company->contact_num ?>">
                </div>


                 <div class="form-group">
                  <label for="exampleInputEmail1">Company Address</label>
                  <input type="text" name="hospice_address" class="form-control " id="" placeholder="" value="<?php echo $company->hospice_address ?>">
                </div>

                 <div class="form-group">
                  <label for="exampleInputEmail1">Company Fax Number</label>
                  <input type="text" name="hospice_fax_number" class="form-control hosp_contact_num" id="" placeholder="" value="<?php echo $company->hospice_fax_number ?>">
                </div>

                 <div class="form-group">
                  <label for="exampleInputEmail1">Company Email</label>
                  <input type="email" name="hospice_email" class="form-control " id="" placeholder="" value="<?php echo $company->hospice_email ?>" style="text-transform:none !important">
                </div>

                 <div class="form-group">
                  <label for="exampleInputEmail1">Company Website</label>
                  <input type="text" name="hospice_website" class="form-control " id="" placeholder="" value="<?php echo $company->hospice_website ?>" style="text-transform:none !important">
                </div>

                 <div class="form-group">
                  <label for="exampleInputEmail1">Title</label>
                  <input type="text" name="hospice_title" class="form-control " id="" placeholder="" value="<?php echo $company->hospice_title ?>" style="text-transform:none !important">
                </div>

                 <div class="form-group">
                  <label for="exampleInputEmail1">Contact Person</label>
                  <input type="text" name="hospice_cont_person" class="form-control " id="" placeholder="" value="<?php echo $company->hospice_contact_person ?>">
                </div>

                 <div class="form-group">
                  <label for="exampleInputEmail1">Date of Service</label>
                  <input type="text" name="date_of_service" class="form-control datepicker" id="" placeholder="" value="<?php echo $company->date_of_service ?>" style="margin-left:0px">
                </div>

               <hr />
            </div>
            </div>
        </div>
          </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-order" >Save Changes</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </form>
    </div>

  </div>
</div>


<?php endforeach; ?> <!-- End sa foreach adtu sa taas :) -->
<?php endif; ?> <!-- End sa condition para sa dili empty nga array :) -->