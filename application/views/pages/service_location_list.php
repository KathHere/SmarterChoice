<style type="text/css">

.service_location_container input[type="search"]
{
  margin-left: 13px;
}

select.input-sm
{
  margin-left: 11px;
  margin-right: 11px;
}

.modal-dialog {
  width: 900px !important;
}

</style>
<?php echo $test ?>
<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">Service Locations</h1>
</div>

<div class="wrapper-md">
  <div class="panel panel-default">
    <div class="panel-heading">Service Location List
    </div>

    <div class="table-responsive service_location_container">
        <?php
                if (!empty($service_location_list)) {
                    ?>
            <table class="table m-b-none datatable_table_service_location_list text-center" style="min-width: 900px !important;">
        <?php
                } else {
                    ?>
            <table class="table m-b-none text-center" style="min-width: 900px !important;">
        <?php
                }
            ?>
        <thead>
          <tr>
              <th class="" style="text-align: center;">Service Location ID</th>
              <th class="" style="text-align: center;">Service Location Name</th>
              <th class="" style="text-align: center;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- <?php
            if(!empty($hospices)){
              foreach ($hospices as $hospice):
          ?>
                <tr>
                  <td class=""><?php echo $hospice->hospiceID ?></td>
                  <td class=""><?php echo $hospice->hospice_account_number ?></td>
                  <td class=""><?php echo $hospice->hospice_name ?></td>
                  <td class="">
                    <button type="button" class="btn btn-info btn-xs create_random_account_number" data-toggle="modal" data-target="#edit_hospice<?php echo $hospice->hospiceID ?>"><i class="glyphicon glyphicon-pencil"></i> Edit</button>
                    <button type="button" class="btn btn-danger btn-xs delete-hospice-btn" data-id="<?php echo $hospice->hospiceID ?>"><i class="glyphicon glyphicon-trash"></i> Delete</button>
                    <a href="<?php echo base_url('equipment/list_equipments/'.get_code($hospice->hospiceID)) ?>">
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
          <!-- <tr>
            <td>LV01</td>
            <td>Advantage Home Medical Services Las Vegas</td>
            <td>
              <button type="button" class="btn btn-info btn-xs create_random_account_number" data-toggle="modal" data-target="#edit_hospice<?php echo $hospice->hospiceID ?>" style="margin-right: 10px"><i class="glyphicon glyphicon-pencil"></i> Edit
              </button>
              <label class="i-checks data_tooltip" title="Active">
                <input
                    type="checkbox"
                    name=""
                />
                <i></i> Make Inactive
              </label>
            </td>
          </tr>
          <tr>
            <td>DY02</td>
            <td>Advantage Home Medical Services Downey</td>
            <td>
              <button type="button" class="btn btn-info btn-xs create_random_account_number" data-toggle="modal" data-target="#edit_hospice<?php echo $hospice->hospiceID ?>" style="margin-right: 10px"><i class="glyphicon glyphicon-pencil"></i> Edit
              </button>
              <label class="i-checks data_tooltip" title="Active">
                <input
                    type="checkbox"
                    name=""
                />
                <i></i> Make Inactive
              </label>
            </td>
          </tr>
          <tr>
            <td>DY03</td>
            <td>Advantage Home Medical Services SaltLake</td>
            <td>
              <button type="button" class="btn btn-info btn-xs create_random_account_number" data-toggle="modal" data-target="#edit_hospice<?php echo $hospice->hospiceID ?>" style="margin-right: 10px"><i class="glyphicon glyphicon-pencil"></i> Edit
              </button>
              <label class="i-checks data_tooltip" title="Inactive">
                <input
                    type="checkbox"
                    name=""
                    checked
                />
                <i></i> Make Active
              </label>
            </td>
          </tr> -->
        </tbody>
      </table>
    </div>
  </div>
</div>





<?php if(!empty($service_location_list)) : ?>

  <?php foreach ($service_location_list as $key => $value) : ?>

<div class="modal fade edit_hospice" id="edit_service_location_<?php echo $value['location_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:90px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <form action="<?php echo base_url('service_location/update_service_location/') ;?>" method="POST" id="">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Edit Service Location Form</h4>
        </div>

        <div class="modal-body OpenSans-Reg row">

            <div class="col-md-6">
              <input type="hidden" name="choose_account_type_value" value="0">
              <input type="hidden" name="location_id" value="<?php echo $value['location_id']; ?>">
              <div class="form-group">
                <label for="exampleInputEmail1">Date of New Service Location</label>
                <input style="margin-left:0px;" type="text" class="form-control datepicker" name="service_location_date" value="<?php echo date('Y-m-d', strtotime($value['date_added'])) ?>" />
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Service Location Name</label>
                <input type="text" name="service_location_name" class="form-control" id="" placeholder="" value="<?php echo $value['location_name']; ?>">
              </div>
              <div class="form-group" style="margin-top:35px; margin-bottom: 39px">
                <label for="exampleInputEmail1">Service Location Address<span class="text-danger-dker">*</span></label>
                  <input type="text" class="form-control" id="p_add" placeholder="Enter Address" name="p_address" style="margin-bottom:20px;" tabindex="19" value="<?php echo $value['user_street']; ?>">
              </div>
                  <div class="form-group pull-in clearfix" style="margin-bottom: 45px">
                      <div class="col-sm-6">
                          <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required" id="p_city" placeholder="City" name="service_city" tabindex="22" value="<?php echo $value['user_city']; ?>">

                      </div>
                      <div class="col-sm-6">
                          <input type="text" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" id="p_state" placeholder="State / Province" name="service_state" tabindex="23" value="<?php echo $value['user_state']; ?>">
                      </div>
                  </div>
                  <div class="form-group">
                      <input type="number" class="form-control grey_inner_shadow" id="p_postal" placeholder="Postal Code" name="service_postalcode" tabindex="24" value="<?php echo $value['user_postalcode']; ?>">
                  </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Service Location Contact Person</label>
                <input type="text" name="service_location_contact_person" class="form-control" id="" placeholder="" value="<?php echo $value['location_contact_person']; ?>">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Service Location ID No.</label>
                <input type="text" name="service_location_id" class="form-control " id="" placeholder="" value="<?php echo $value['service_location_id']; ?>">
              </div>
              <div class="form-group" style="margin-top:35px">
                <label for="exampleInputEmail1">Service Location Phone No.</label>
                <input type="text" name="service_location_phone_no" class="form-control hosp_contact_num" id="" placeholder="" value="<?php echo $value['location_phone_no']; ?>">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Service Location Fax Number</label>
                <input type="text" name="service_location_fax_no" class="form-control " id="" placeholder="" style="" value="<?php echo $value['location_fax_no']; ?>">
              </div>
              <div class="form-group" style="margin-top:20px">
                <label for="exampleInputEmail1">Contact Person Title</label>
                <input type="text" name="service_location_contact_person_title" class="form-control " id="" placeholder="" style="text-transform:none !important" value="<?php echo $value['contact_person_title']; ?>">
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


<script type="text/javascript">

  $(document).ready(function(){

    $('.datatable_table_service_location_list').DataTable( {
      "createdRow": function( row, data, dataIndex ) {
        var order = JSON.stringify(data);
        $(row).attr('class', 'location_td service_location_list_'+dataIndex);
        $(row).attr('data-row-id', dataIndex);
      },
      "lengthMenu": [10,25,50,75,100,200,300,500],
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "deferRender": true,
      "ajax": {
          url: base_url+"service_location/get_service_location_list"
      },
      "columns": [
          { "data": "service_location_id" },
          { "data": "location_name" },
          { "data": "actions" }
      ],
      "order": [[ 0, "desc" ]]
    });

    setTimeout ( function () {
      $.post(base_url+"service_location/get_all_service_location/", function(response){

        var obj = $.parseJSON(response);
        console.log(obj);

        for (var i = 0; i < obj.service_location_list.length; i++) {
          if(obj.service_location_list[i].status == 1) {
            $('.service_location_list_'+i).css({"background-color":"rgba(93, 87, 87, 0.07)"});
          }
        }

      })
    }, 1500);

    $('body').on('click','.update_status', function () {
      var _this = $(this);
      var location_id = _this.attr('data-location-id');
      var status = _this.attr('data-status');
      var row_id = _this.attr('data-row-id');

      // jConfirm("Are you sure you want to update Service Location?","Reminder",function(response){
      //   if(response)
      //     {

            if(status == 1){
              status = 0;
            } else {
              status = 1;
            }

            $.post(base_url+"service_location/update_status/" + location_id +"/"+ status, function(response){

              var obj = $.parseJSON(response);

              if(status == 1){
                me_message_v2({error:0,message:"Service Location Deactivated."});
                $('.edit_button_location_'+location_id).attr("style", "margin-right: 10px; margin-left: -10px;");
                $('.service_location_list_'+row_id).css({"background-color":"rgba(93, 87, 87, 0.07)"});
                $('.locate_status_'+location_id).html("Make Active");
                if(_this.is(':checked')) {
                  $('.location_status_'+location_id).prop('checked', false);
                } else {
                  $('.location_status_'+location_id).prop('checked', true);
                }
              } else {
                me_message_v2({error:0,message:"Service Location Activated."});
                $('.edit_button_location_'+location_id).attr("style", "margin-right: 10px;");
                $('.service_location_list_'+row_id).css({"background-color": "white"});
                $('.locate_status_'+location_id).html("Make Inactive");
                if(_this.is(':checked')) {
                  $('.location_status_'+location_id).prop('checked', false);
                } else {
                  $('.location_status_'+location_id).prop('checked', true);
                }
              }

            });


            if(_this.is(':checked')) {
              $('.location_status_'+location_id).prop('checked', false);
              _this.attr('data-status', 1);
            } else {
              $('.location_status_'+location_id).prop('checked', true);
              _this.attr('data-status', 0);
            }
      //     }
      // });
    });
  });

</script>