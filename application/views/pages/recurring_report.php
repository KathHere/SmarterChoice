<style>
  .table-heading {
    text-align: center;

    padding: 8px !important;
    border: 1px solid #dee5e7;
    color: #333;
    background-color: #f6f8f8;
    /* margin-right: -1px !important;
    margin-left: -1px !important; */
    /* border-radius: 5px 5px 0px 0px; */
  }
  
  .table-content {
    height: 100%;
    /* margin-right: -1px !important; */
    /* margin-left: -1px !important; */
    border-left: 1px solid #dee5e7;
    border-right: 1px solid #dee5e7;
    border-bottom: 1px solid #dee5e7;
    padding-top: 10px;
    height: 100%;
    max-height: 100%;
    overflow: auto;
  }
  
  .table-content-names {
    /* display: inline; */
    /* padding: .2rem .6rem .3rem; */
    padding: 10px;
    font-size: 80%;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25rem;
    background-color: #23b7e5;
    color: white;
    font-weight: bold;
    margin-bottom: 5px;
    cursor: pointer;
    white-space: normal !important;
  }

  .table-content-names:hover {
    background-color: #428bca;
  }

  .bootstrap-tagsinput {
    border: none !important;
    box-shadow: none !important;
  }
  @media (min-width: 768px){
    .seven-cols .col-md-1,
    .seven-cols .col-sm-1,
    .seven-cols .col-lg-1  {
      width: 100%;
      *width: 100%;
    }
  }

  @media (min-width: 992px) {
    .seven-cols .col-md-1,
    .seven-cols .col-sm-1,
    .seven-cols .col-lg-1 {
      width: 14.285714285714285714285714285714%;
      *width: 14.285714285714285714285714285714%;
    }
  }

  /**
  *  The following is not really needed in this case
  *  Only to demonstrate the usage of @media for large screens
  */    
  @media (min-width: 1200px) {
    .seven-cols .col-md-1,
    .seven-cols .col-sm-1,
    .seven-cols .col-lg-1 {
      width: 14.285714285714285714285714285714%;
      *width: 14.285714285714285714285714285714%;
    }
  }

  @media print {
		.row {
		  display: flex;
		  flex-direction: row;
		  flex-wrap: wrap;
		  width: 100%;
		}

		.col, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
		  display: flex;
		  flex-direction: column;
		  flex-basis: 100%;
		  flex: 1;
		}
  }
</style>
<div class="wrapper-md" style="min-height: 86vh">
  <div class="panel panel-default">
    <div class="panel-heading">
      Recurring Report
    </div>
    <div class="panel-body">
      <div class="row seven-cols" style="margin-right: -1px !important; margin-left: -1px !important;">
        <div class="col-md-1 table-heading" style="border-right: none; border-radius: 3px 0px 0px 0px">SUNDAY</div>
        <div class="col-md-1 table-heading" style="border-right: none">MONDAY</div>
        <div class="col-md-1 table-heading" style="border-right: none">TUESDAY</div>
        <div class="col-md-1 table-heading" style="border-right: none">WEDNESDAY</div>
        <div class="col-md-1 table-heading" style="border-right: none">THURSDAY</div>
        <div class="col-md-1 table-heading" style="border-right: none">FRIDAY</div>
        <div class="col-md-1 table-heading" style="border-radius: 0px 3px 0px 0px">SATURDAY</div>
      </div>

      <div class="row seven-cols" style="margin-right: -1px !important; margin-left: 0px !important; height: 500px">
        <div class="col-md-1 table-content" style="border-right: none; margin-left: -1px !important" id="sun">
          <?php
          foreach($recurring_orders as $key => $value){
            $temp_sched = split("-", $value['recurring_schedule_days']);
            foreach($temp_sched as $sched_key => $sched_value) {
              if($sched_value == 0) {
          ?>
            <div onclick="window.open('<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>', '_blank')" class="table-content-names"><?php echo $value['p_lname'].', '.$value['p_fname']; ?></div> 
            <!-- <div onclick="location.href = '<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>'" target="_blank" class="table-content-names"><?php echo $value['p_fname'].' '.$value['p_lname']; ?></div> -->
          <?php
              }
            }
          }
          ?>
            
            <!-- <div class="table-content-names">John Doe</div>
            <div class="table-content-names">John Doe</div>
            <div class="table-content-names">John Doe</div>          -->
        </div>
        <div class="col-md-1 table-content" style="border-right: none; margin-left: 1px !important" id="mon">
          <?php
          foreach($recurring_orders as $key => $value){
            $temp_sched = split("-", $value['recurring_schedule_days']);
            foreach($temp_sched as $sched_key => $sched_value) {
              if($sched_value == 1) {
          ?>
            <div onclick="window.open('<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>', '_blank')" class="table-content-names"><?php echo $value['p_lname'].', '.$value['p_fname']; ?></div> 
            <!-- <div onclick="location.href = '<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>'" target="_blank" class="table-content-names"><?php echo $value['p_fname'].' '.$value['p_lname']; ?></div> -->
          <?php
              }
            }
          }
          ?>
        </div>
        <div class="col-md-1 table-content" style="border-right: none;" id="tue">
          <?php
          foreach($recurring_orders as $key => $value){
            $temp_sched = split("-", $value['recurring_schedule_days']);
            foreach($temp_sched as $sched_key => $sched_value) {
              if($sched_value == 2) {
          ?>
            <div onclick="window.open('<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>', '_blank')" class="table-content-names"><?php echo $value['p_lname'].', '.$value['p_fname']; ?></div> 
            <!-- <div onclick="location.href = '<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>'" target="_blank" class="table-content-names"><?php echo $value['p_fname'].' '.$value['p_lname']; ?></div> -->
          <?php
              }
            }
          }
          ?>
        </div>
        <div class="col-md-1 table-content" style="border-right: none;" id="wed">
          <?php
          foreach($recurring_orders as $key => $value){
            $temp_sched = split("-", $value['recurring_schedule_days']);
            foreach($temp_sched as $sched_key => $sched_value) {
              if($sched_value == 3) {
          ?>
            <div onclick="window.open('<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>', '_blank')" class="table-content-names"><?php echo $value['p_lname'].', '.$value['p_fname']; ?></div> 
            <!-- <div onclick="location.href = '<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>'" target="_blank" class="table-content-names"><?php echo $value['p_fname'].' '.$value['p_lname']; ?></div> -->
          <?php
              }
            }
          }
          ?>
        </div>
        <div class="col-md-1 table-content" style="border-right: none; margin-left: -1px !important;" id="thu">
          <?php
          foreach($recurring_orders as $key => $value){
            $temp_sched = split("-", $value['recurring_schedule_days']);
            foreach($temp_sched as $sched_key => $sched_value) {
              if($sched_value == 4) {
          ?>
            <div onclick="window.open('<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>', '_blank')" class="table-content-names"><?php echo $value['p_lname'].', '.$value['p_fname']; ?></div> 
            <!-- <div onclick="location.href = '<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>'" target="_blank" class="table-content-names"><?php echo $value['p_fname'].' '.$value['p_lname']; ?></div> -->
          <?php
              }
            }
          }
          ?>
        </div>
        <div class="col-md-1 table-content" style="border-right: none;" id="fri">
          <?php
          foreach($recurring_orders as $key => $value){
            $temp_sched = split("-", $value['recurring_schedule_days']);
            foreach($temp_sched as $sched_key => $sched_value) {
              if($sched_value == 5) {
          ?>
            <div onclick="window.open('<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>', '_blank')" class="table-content-names"><?php echo $value['p_lname'].', '.$value['p_fname']; ?></div>          
            <!-- <div onclick="location.href = '<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>' location.target=" target="_blank" class="table-content-names"><?php echo $value['p_fname'].' '.$value['p_lname']; ?></div> -->
          <?php
              }
            }
          }
          ?>
          </div>
        <div class="col-md-1 table-content" style="margin-left: 1px !important;" id="sat">
          <?php
          foreach($recurring_orders as $key => $value){
            $temp_sched = split("-", $value['recurring_schedule_days']);
            foreach($temp_sched as $sched_key => $sched_value) {
              if($sched_value == 6) {
          ?>
              <div onclick="window.open('<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>', '_blank')" class="table-content-names"><?php echo $value['p_lname'].', '.$value['p_fname']; ?></div>          
              <!-- <div onclick="location.href = '<?php echo base_url(); ?>order/patient_profile/<?php echo $value['medical_record_id'].'/'.$value['ordered_by']?>'" target="_blank" class="table-content-names"><?php echo $value['p_fname'].' '.$value['p_lname']; ?></div> -->
            <?php
              }
            }
          }
          ?>
        </div>
      </div>
    </div>
    
  </div>
</div>
<div class="bg-light lter wrapper-md hidden-print" >
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>