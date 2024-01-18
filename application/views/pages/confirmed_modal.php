<style type="text/css">
  .modal
  {
    left: -689px;
    top: -71px;
  }
  #globalModal .modal-content
  {
    width:1300px;
  }

  /*@media (max-width: 1265px) AND (min-width: 992px){*/
  @media (max-width: 1265px){

    #globalModal
    {
      left:0 !important;
    }

    .modal-dialog{
      width:100% !important;
      overflow-x:scroll !important;
    }
  }

  @media (max-width: 770px){
    #globalModal
    {
      top:0 !important;
      right:10px !important;
    }
  }

</style>

<?php
  echo $patient_information_content;
?>

<?php $fname = $this->session->userdata('firstname'); ?>
<?php $lname_complete = $this->session->userdata('lastname'); ?>
<?php $lname = substr($this->session->userdata('lastname'),0,1); ?>


<div class="panel panel-default">
<div class="panel-heading font-bold">
<h4>Customer Order Summary</h4>
</div>
<div class="panel-body">

<?php echo form_open("",array("class"=>"update_order_summary")) ;?>


<div class="col-md-6">
  <div class="col-md-6" style="margin-left:-30px">
    <label>DME Staff Member Confirming Work Order <span class="text-danger-dker">*</span></label>
    <input type="text"  class="form-control confirmed_by" id="exampleInputEmail1" placeholder="<?php echo $fname." ".$lname."." ?>" name="" style="margin-bottom:20px" value="<?php echo $fname." ".$lname_complete ?>" readonly>
  </div>
</div>

<table class="table table-striped bg-white b-a col-md-12 edit_patient_orders" id="confirm_info_table" style="margin-top:0px;margin-left: 0px;">
  <thead>
    <tr>
      <th style="width: 40px">WO#</th>
      <th style="width: 60px">Scheduled Order Date</th>
      <th style="width: 90px">Activity Type</th>
      <th style="width: 60px">Item #</th>
      <th style="width: 90px">Item Description</th>
      <th style="width: 60px">Qty.</th>
      <th style="width: 90px">Serial/Lot #</th>
      <th style="width: 90px">Picked Up Date</th>
      <th style="width: 90px">Capped Type</th>
      <?php
            if($info['activity_typeid'] != 3 && $count_parent_summaries > 1){
      ?>
            <th style="width: 1px" class="action_data">Cancel Item(s)</th>
      <?php
          }
      ?>
    </tr>
  </thead>
  <tbody class="work-order-items">
    <?php echo $content_data;?>
  </tbody>
</table>

<input type="hidden" name="adding_weight_sign" id="adding_weight_sign" value="<?php echo $adding_weight_sign; ?>">
<input type="hidden" name="adding_weight_equipment" id="adding_weight_equipment" value="<?php echo $adding_weight_equipment; ?>">
<input type="hidden" name="commode_pail_count" id="commode_pail_count" value="<?php echo $commode_pail_count; ?>">

<div class="col-md-12" style="padding:0px;">
  <div class="pull-right" style="margin-left: 20px; width:17%; margin-right:6px;">
    <label style="margin-left:5px;">Delivery Date<span class="text-danger-dker">*</span></label>
    <input type="text" class="form-control datepicker_2 input_actual_order_date" name="actual_order_date" placeholder="Click to choose date" style="margin-bottom:20px" value="">
  </div>
</div>

<div class="pull-right" style="margin-left: 20px;">
  <label>DME Staff Member Delivered Order<span class="text-danger-dker">*</span></label>
  <input type="text"  class="form-control driver_name_to_save" id="exampleInputEmail1" placeholder="Delivered by" name="" style="margin-bottom:20px" value="">
</div>

<div class="pull-right" style="  margin-top: 70px !important;margin-right: -225px !important;">
  <button type="button" class="btn btn-danger pull-right data_tooltip" onclick="closeModalbox()">Close</button>

  <input type="hidden" name="" class="current_act_type" value="<?php echo $act_id; ?>">
  <input type="hidden" name="" class="o2_concentrator_follow_up_sign" value="<?php echo $o2_concentrator_follow_up_sign; ?>" data-equipmentID="<?php echo $o2_concentrator_follow_up_equipmentID; ?>"  data-uniqueID="<?php echo $o2_concentrator_follow_up_uniqueID; ?>" data-uniqueID_old="<?php echo $o2_concentrator_follow_up_uniqueID_old; ?>">
  <?php if($act_id == 4) :?>
    <div class="dummy-wrapper data_tooltip pull-right" title="Click save changes button first">
      <button type="button" class="btn btn-success pull-right btn-confirm-order " data-act-id="<?php echo $info['activity_typeid'] ?>" data-medical-id="<?php echo $medical_id ?>" data-unique-id="<?php echo $work_order_number ?>" style="margin-right:10px" disabled >Confirm Order</button>&nbsp
    </div>
  <?php else: ?>
    <button type="button" class="btn btn-success pull-right btn-confirm-order" data-act-id="<?php echo $info['activity_typeid'] ?>" data-medical-id="<?php echo $medical_id ?>" data-unique-id="<?php echo $work_order_number ?>" style="margin-right:10px">Confirm Order</button>&nbsp
  <?php endif;?>
<div>
<?php echo form_close() ;?>
</div>
</div>


<div class="col-sm-12" style="padding-left:0px;">
<div class="pull-left">
    <a href="<?php echo base_url("order/print_confirm_details/".$medical_record_num."/".$work_order_number."/".$act_id."/".$hospice_id) ?>" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> Print</a>
</div>
</div>

<script>
  $(document).ready(function(){
      $('.work-order-items').find('.item-description').each(function(){
        $(this).html("<i class='fa fa-spin fa-spinner'></i>");
        var this_ = $(this);
        var info = this_.attr("data-info");
        $.get('<?php echo base_url('order/confirmed_modal_itemdescription/'.$medical_record_num.'/'.$work_order_number.'/'.$hospice_id); ?>',{info:info},function(response){
          this_.html(response);
        });
      });
  });
</script>
