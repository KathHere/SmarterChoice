<?php if (!empty($informations)) : ?>
<?php $information = $informations[0];?>
<style type="text/css">
  
  .status-count.status-count-bot{
  margin-left: 30%;
}
.status-count li{
  padding-right: 30px;
}
.patient-profile-photo{
    width:64px;
    height:64px;
    display: block;
    overflow: hidden;
    border-radius: 50%;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    background:#fff;
    border:3px solid rgba(200,200,200,.5);
    text-align: center;
    line-height: 64px;
}
</style>

<div class="bg-light lter b-b wrapper-md">
  <h1 class="m-n font-thin h3">Customer Profile</h1>
</div>

<div class="wrapper-md">
  <div class="patient-profile">
      <div class="panel panel-default">
        <div class="panel-body" style="padding-top:0;padding-bottom:0;">
          <div class="row">
              <?php if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user") :?>
              <div class="col-xs-12" style="background-color:#fafafa; padding:20px;">
                  <div class="media">
                    <div class="media-left pull-left" style="margin-right:20px;">
                      <!-- <a href="javascript:void(0)" class="edit_patient_profile data_tooltip patient-profile-photo" title="Edit Patient Profile" data-organization-id="<?php echo $information['organization_id'] ?>" data-id="<?php echo $information['medical_record_id'] ?>"> -->
                      <?php if(!empty($information['organization_id'])){?>
                      <a href="javascript:void(0)" class="edit_patient_profile data_tooltip patient-profile-photo" title="Edit Patient Profile" data-organization-id="<?php echo $information['organization_id'] ?>" data-id="<?php echo $information['medical_record_id'] ?>">
                        <i class=" icon-user fa fa-2x"></i>
                      </a>
                    <?php } else { ?>
                      <a href="javascript:void(0)" class="edit_patient_profile data_tooltip patient-profile-photo" title="Edit Patient Profile" data-organization-id="<?php echo $information['ordered_by'] ?>" data-id="<?php echo $information['medical_record_id'] ?>">
                        <i class=" icon-user fa fa-2x"></i>
                      </a>
                    <?php } ?>
                    </div>
                    <div class="media-right mt10">
                    <!-- <a href="javascript:void(0)" class="edit_patient_profile" data-organization-id="<?php echo $information['organization_id'] ?>" data-id="<?php echo $information['medical_record_id'] ?>"D><i class="icon-pencil"></i> Edit Info</a>&nbsp &nbsp &nbsp <br /> -->
                    <?php if(!empty($information['organization_id'])){?>
                    <a href="javascript:void(0)" class="edit_patient_profile" data-organization-id="<?php echo $information['organization_id'] ?>" data-id="<?php echo $information['medical_record_id'] ?>"D><i class="icon-pencil"></i> Edit Info</a>&nbsp &nbsp &nbsp <br />
                    <?php } else { ?>
                    <a href="javascript:void(0)" class="edit_patient_profile" data-organization-id="<?php echo $information['ordered_by'] ?>" data-id="<?php echo $information['medical_record_id'] ?>"D><i class="icon-pencil"></i> Edit Info</a>&nbsp &nbsp &nbsp <br />
                    <?php } ?>
                    <a href="javascript:void(0)" id="add_patient_notes" class="patient_notes_count" data-patient-id="<?php echo $information['patientID'] ?>" data-id="<?php echo $information['medical_record_id'] ?>" data-fname="<?php echo $information['p_fname'] ?>" data-lname="<?php echo $information['p_lname'] ?>" data-hospice="<?php echo $information['hospice_name'] ?>" title=""><i class="icon icon-speech"></i> <?php echo $note_counts ?> Customer Notes</a>
                    </div>
                  </div>
             </div> 
              <?php endif;?>
             <div class="col-xs-12">
                 <h4>Customer Medical Record # <?php echo $information['medical_record_id'] ?></h4>
                 <h5>Hospice Provider: <?php echo $information['hospice_name'] ?></h5>
              </div>
          </div>                 
         </div>
     </div>

     <div class="well m-t bg-light lt">
      <div class="row">
        <div class="col-xs-6">
    
          <strong>Customer Name</strong>
          <h4><?php echo $information['p_lname'] .", ". $information['p_fname'] ?></h4>
            
          <strong>Gender</strong>
            <p>
            <?php 
              if($information['relationship_gender'] == 1)
              {
                echo "Male";
              } 
              else
              {
                echo "Female";
              }
 
            ?>
            </p>

          <strong>Customer Address</strong>
          <?php 
            $ptmove = new_ptmove_address($information['patientID']);
            $ptmove_new_phone = get_new_patient_phone($information['patientID']);
            $ptmove_residence = get_new_patient_residence($information['patientID']);
            $ptmove_final = $ptmove[0];
          ?>
          <?php if(!empty($ptmove)) : ?>
            <p>
                  <?php echo $ptmove_final['ptmove_street'] ."<br/> ". $ptmove_final['ptmove_placenum'] ."<br/> ". $ptmove_final['ptmove_city'] .", ". $ptmove_final['ptmove_state'] .", ". $ptmove_final['ptmove_postal'] ?>
            </p>
            <strong>Height(IN) & Weight(lbs)</strong>
            <?php if($information['p_weight'] == 0 && $information['p_height'] == 0) :?>
              <p>NA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp NA</p>
            <?php else:?>
              <p><?php echo $information['p_height'] ?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <?php echo $information['p_weight'] ?></p>
            <?php endif;?>
            <strong>Phone Number</strong>
            <p><?php echo $ptmove_new_phone['ptmove_patient_phone'] ?></p>

          <?php else:?>
            <p>
             <?php echo $information['p_street'] ."<br/> ". $information['p_placenum'] ."<br/> ". $information['p_city'] .", ". $information['p_state'] .", ". $information['p_postalcode'] ?>
            </p>
            <strong>Height(IN) & Weight(lbs)</strong>
            <?php if($information['p_weight'] == 0 && $information['p_height'] == 0) :?>
              <p>NA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp NA</p>
            <?php else:?>
              <p><?php echo $information['p_height'] ?> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <?php echo $information['p_weight'] ?></p>
            <?php endif;?>
            <strong>Phone Number</strong>
            <p><?php echo $information['p_phonenum'] ?></p>
          <?php endif;?>
         

        </div>
        <div class="col-xs-6">
          
          <strong >Emergency Contact</strong><br/><br/>
            
          <strong>Next of Kin</strong>
            <p><?php echo $information['p_nextofkin'] ?></p>
      
          <strong>Relationship</strong>
            <?php if($information['p_relationship'] == '') :?>
              <p>N/A</p>
            <?php else:?>
              <p><?php echo $information['p_relationship'] ?></p>
            <?php endif;?>
            

          <strong>Next of Kin Phone Number</strong>
            <p><?php echo $information['p_nextofkinnum'] ?></p>
      
          <strong>Alt. Phone Number</strong>
           <?php if($information['p_altphonenum'] == '') :?>
              <p>N/A</p>
            <?php else:?>
              <p><?php echo $information['p_altphonenum'] ?></p>
            <?php endif;?>
            
  

           <strong>Residence</strong>
          <?php if($information['deliver_to_type'] == '') :?>
              <p>N/A</p>
            <?php else:?>
              <p><?php echo $information['deliver_to_type'] ?></p>
            <?php endif;?>

        </div>
      </div>
    </div>
 
<div class="line"></div>

<?php 
  $patient_mr = $information['medical_record_id'];
  $patient_hospice_id = $information['hospiceID'];
  $patient_orders = check_if_all_pickups($patient_mr,$patient_hospice_id);
  //print_r($patient_orders);exit();
  if(!empty($patient_orders))
  {
    $has_delivery = in_multiarray(1, $patient_orders, "activity_typeid");
    $has_exchange = in_multiarray(3, $patient_orders, "activity_typeid");
    $has_ptmove   = in_multiarray(4, $patient_orders, "activity_typeid");
    $has_respite  = in_multiarray(5, $patient_orders, "activity_typeid");

    $disable_button = "";

    if($has_delivery)
    {
      $disable_button = "";
    }
    else if($has_exchange)
    {
      $disable_button = "";
    }
    else if($has_ptmove)
    {
      $disable_button = "";
    }
    else if($has_respite)
    {
      $disable_button = "";
    }
    else
    {
      $pickups = check_if_all_pickups_v2($patient_mr,$patient_hospice_id);
      //print_r($pickups);exit();
      if($pickups)
      {
        $disable_button = "";
      }
      else
      {
        $disable_button = "disabled";
      }
    }
  } else {
    $disable_button = "";
  }  
?>

<!-- <input type="hidden" name="patient_orders_value" value="<?php print_r($pickups); ?>"> -->
<div class="col-sm-12" style="margin-bottom:15px;">
  <div class="pull-right">
    <?php if(!empty($information['organization_id'])){?>
    <a class="btn btn-danger btn-xs pull-right additional_equip_button" data-toggle="popover" 
      href="<?php echo base_url('order/new_equipment/'.$information['medical_record_id']."/".$information['organization_id']) ?>" id="additional_equip_btn" data-value=""  
       <?php echo $disable_button ?> >
      <i class="fa fa-plus">
        <span class="font-bold data_tooltip" title="Click to Add New Activity"> Activity Type</span>
      </i>
    </a>
    <?php }else{ ?>
    <a class="btn btn-danger btn-xs pull-right additional_equip_button" data-toggle="popover" 
      href="<?php echo base_url('order/new_equipment/'.$information['medical_record_id']."/".$information['ordered_by']) ?>" id="additional_equip_btn" data-value=""  
       <?php echo $disable_button ?> >
      <i class="fa fa-plus">
        <span class="font-bold data_tooltip" title="Click to Add New Activity"> Activity Type</span>
      </i>
    </a>
    <?php } ?>
  </div>
  
  <div class="pull-right">
      <?php
          $activity_counts  = array();
          $label            = array(1=>"Delivery",3=>"Exchange",2=>"Pickup",4=>"CUS Move",5=>"Respite");
          for($i=1;$i<=5;$i++)
          {
              $activity_counts[] = get_count_status("",array(
                                        "stats.medical_record_id"       => $patient_mr,
                                        "stats.status_activity_typeid"  => $i,
                                        // "(orders.order_status != 'confirmed' AND  orders.order_status != 'cancel' AND orders.order_status != 'tobe_confirmed')"         => false
                                        "(orders.order_status != 'confirmed' AND  orders.order_status != 'cancel')"         => false
                                    ));
              $activities[] = get_status("",array(
                                        "stats.medical_record_id"       => $patient_mr,
                                        "stats.status_activity_typeid"  => $i,
                                        // "(orders.order_status != 'confirmed' AND  orders.order_status != 'cancel' AND orders.order_status != 'tobe_confirmed')"         => false
                                        "(orders.order_status != 'confirmed' AND  orders.order_status != 'cancel')"         => false
                                    ));
          }
          $index=0;
      ?>
      <ul class="status-count" style="list-style-type:none;">
        <?php 
          $count = 0;
          foreach($activity_counts as $key=>$value){
            $count_inside = 0;
            foreach ($activities as $act){
              $another_count = 0;
              if($count == $count_inside){
                if($value > 0){
                  if($value > 1){
                    $patientID = $this->encryption->encode($act[$another_count]['patientID']);
                    if($act[$another_count]['order_status'] != $act[$another_count+1]['order_status'])
                    {
                      if($act[$another_count]['order_status'] != "tobe_confirmed" && $act[$another_count+1]['order_status']!= "tobe_confirmed")
                      {
        ?>
                        <li class="pull-left">
                          <span><a href="<?php echo base_url("order/patient_order_list")."/".$patientID ?>" target="_blank"><?php echo $label[$key+1]; ?></a></span>&nbsp;
                          <span><strong><?php echo $value; ?></strong></span>
                        </li>
        <?php 
                      }else{
                        $pop_over_content_f1 = "<a href='".base_url("order/patient_order_list")."/".$patientID."' target='_blank'> Customer Order Status</a></span>";
                        $pop_over_content_f2 = "<a href='".base_url("order/patient_list_tobe_confirmed")."/".$patientID."' target='_blank'> Confirm Work Orders</a></span>";
                        $pop_over_content = $pop_over_content_f1."<br />".$pop_over_content_f2;
        ?>
                        <li class="pull-left">
                          <span>
                            <a
                              href="javascript:;"
                              rel="popover" 
                              data-html="true"
                              data-toggle="popover" 
                              data-trigger="focus"
                              data-placement="top"
                              data-content="<?php echo $pop_over_content; ?>" 
                            >
                              <?php echo $label[$key+1]; ?>
                            </a>
                          </span>
                          &nbsp;
                          <span><strong><?php echo $value; ?></strong></span>
                        </li>
        <?php 
                      }
                    }
                    else
                    {
                      if($act[0]['order_status'] == "pending" || $act[0]['order_status'] == "on-hold" || $act[0]['order_status'] == "active" || $act[0]['order_status'] == "re-schedule"){
        ?>              
                        <li class="pull-left">
                          <span><a href="<?php echo base_url("order/patient_order_list")."/".$patientID ?>" target="_blank"><?php echo $label[$key+1]; ?></a></span>&nbsp;
                          <span><strong><?php echo $value; ?></strong></span>
                        </li>
        <?php 
                      }else if($act[$another_count]['order_status'] == "tobe_confirmed"){
        ?>
                        <li class="pull-left">
                          <span><a href="<?php echo base_url("order/patient_list_tobe_confirmed")."/".$patientID ?>" target="_blank"><?php echo $label[$key+1]; ?></a></span>&nbsp;
                          <span><strong><?php echo $value; ?></strong></span>
                        </li> 
        <?php
                      }
                    }
                    $another_count++;
                  }
                  else
                  {
                    $patientID = $this->encryption->encode($act[0]['patientID']);
                    if($act[0]['order_status'] == "pending" || $act[0]['order_status'] == "on-hold" || $act[0]['order_status'] == "active" || $act[0]['order_status'] == "re-schedule"){
        ?>
                      <li class="pull-left">
                        <span><a href="<?php echo base_url("order/patient_order_list")."/".$patientID ?>" target="_blank"><?php echo $label[$key+1]; ?></a></span>&nbsp;
                        <span><strong><?php echo $value; ?></strong></span>
                      </li>
        <?php 
                    }
                    else if($act[0]['order_status'] == "tobe_confirmed")
                    {
        ?>
                      <li class="pull-left">
                        <span><a href="<?php echo base_url("order/patient_list_tobe_confirmed")."/".$patientID ?>" target="_blank"><?php echo $label[$key+1]; ?></a></span>&nbsp;
                        <span><strong><?php echo $value; ?></strong></span>
                      </li> 
        <?php 
                    }
                  }
                }
              }
              // else
              // {
              //   break;
              // }
              $count_inside++; 
            } 
            $index++;
            $count++;
          }
        ?>
      </ul>
  </div>
</div>

<?php if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user") :?>
<div class="col-sm-12">
    <a class="btn btn-success btn-xs pull-right activity_type_section_btn" data-toggle="popover" style="margin-bottom: 29px;margin-right: 1px;color:#fff;margin-top:-5px;display:none">
        <span class="font-bold data_tooltip"> Reactivate Customer</span>
    </a>
</div>
<?php endif;?>

  <div class="col-md-12">
    <div class="row">
      <div class="panel panel-default">
        <div class="panel-heading font-bold">
          <h4>Customer Order Summary</h4>
        </div>
        <div class="panel-body">
        <?php if(!empty($summaries) && !empty($summarys)) { ?>
        <!-- deadstart -->

          <div class="table-responsive">
            <table class="table" id="equipment_summary_tbl">
              <thead>
                <tr>
                  <!-- <th style="width: 40px">WO#</th> -->
                  <!-- <th style="width: 40px">Date Ordered</th> -->
                  <th> Order Date</th>
                  <th> WO#</th>
                  <th> Activity Type</th>
                  <th class="hide_on_print"> Item #</th>
                  <th> Item Description</th>
                  <th> Qty.</th>
                  <th> Serial/Lot #</th>
                  <th> Picked Up Date</th>
                  <th> Type</th>
                  <th style="width:38px;"> <i class="fa fa-map-marker"></i></th>
                </tr> 
              </thead>
              <tbody>
                <?php 
                  if(!empty($summaries)) :
                    //if admin allow update
                    $enableclick = "";
                    if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == "dme_user")
                    {
                      $enableclick = "editable-click";
                    }
                    foreach($summaries as $summary) :
                
                      echo form_open("",array("id"=>"update_summary".$summary['equipmentID'])); 
                      if($summary['canceled_order'] == 0 && $summary['canceled_from_confirming'] == 0) :
                ?>
                        <tr data-id="Sample data-id" style="<?php if($summary['parentID'] != 0) echo 'display:none' ?>">
                          <input type="hidden" name="hdn_act_id_for_checking[]" id="act_id_checking" value="<?php echo $summary['activity_typeid'] ?>" />
                          <td style="width:auto">
                            <!-- order date of the items regardless of its activities -->
                            <?php
                              if($summary['pickup_date'] != '0000-00-00' && $summary['pickup_date'] != '')
                              {
                            ?>
                                <a 
                                  id="pickup_date"
                                  data-pk="<?php echo $summary['uniqueID'] ?>" 
                                  data-url="<?php echo base_url('order/update_data/date/uniqueID'); ?>" 
                                  data-title="Enter date"
                                  data-value="<?php echo date("Y-m-d", strtotime($summary['pickup_date'])) ?>"
                                  data-type="combodate" 
                                  data-maxYear="<?php echo date("Y"); ?>"
                                  data-format="YYYY-MM-DD" 
                                  data-viewformat="MM/DD/YYYY" 
                                  data-template="MMM / D / YYYY"
                                  href="javascript:void(0)" class="data_tooltip editable <?php echo $enableclick; ?> editable-combodate text-success text-bold" 
                                >
                                  <?php echo date("m/d/Y", strtotime($summary['pickup_date'])) ?>
                                </a>
                            <?php 
                              }
                            ?>
                          </td>
                          <td style="width:auto">

                            <?php 
                                //getting activity type id depending
                                // on the activity type
                                // cus move and exchange need to be an original activity
                                // type

                                $get_type = "activity_typeid";
                                $allowed_acttypes = array(3,4);
                                if(in_array($summary['original_activity_typeid'], $allowed_acttypes))
                                {
                                    $get_type = "original_activity_typeid";
                                }
                              ?>
                            <a 
                              href="javascript:;" 
                              class="view_original_order_information"
                              rel="popover" 
                              data-toggle="popover" 
                              title="" 
                              data-trigger="hover"
                              data-placement="top"
                              data-html="true"
                              data-container="body"
                              data-content="Click to view the details"
                              style="cursor:pointer" 
                              data-id="<?php echo $summary['medical_record_id'] ?>" 
                              data-value="<?php echo $summary['organization_id'] ?>" 
                              data-unique-id="<?php echo $summary['uniqueID'] ?>" 
                              data-act-id="<?php echo $summary[$get_type] ?>" 
                              data-patient-id="<?php echo $summary['patientID'] ?>"
                            > <?php echo substr($summary['uniqueID'],4,10); ?></a>
                          </td>
                          <td class="activity_type_column"><?php echo $summary['activity_name'] ?></td>
                          <td class="hide_on_print">
                           <a 
                              id="item_num"
                              data-pk="<?php echo $summary['orderID'] ?>" 
                              data-url="<?php echo base_url('order/update_data/text/orderID'); ?>" 
                              data-title="Enter Item Number"
                              data-value="<?php echo $summary['item_num'] ?>"
                              data-type="text" 
                              href="javascript:void(0)" class="data_tooltip editable <?php echo $enableclick; ?> editable-text text-info" 
                            >
                              <?php echo $summary['item_num'] ?>
                            </a>
                          </td>
                          <td style="width:auto">
                            <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == "dme_user") :?>
                              <strong><a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover edit_item_options show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>" data-patient-id="<?php echo $summary['patientID'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                            <?php else:?>
                              <strong><a href="javascript:void(0)" data-toggle="popover" class="equipment_options_tooltip equipment_options_hover show_on_print" data-uniqueid="<?php echo $summary['uniqueID'] ?>" data-id="<?php echo $summary['equipmentID'] ?>" data-medical-id="<?php echo $summary['medical_record_id'] ?>"><?php echo $summary['key_desc'] ?></a></strong>
                            <?php endif;?>
                          </td>
                          <?php 
                            //quantity base on the categories
                            //there are 3 categories
                            // capped,non-capped,disposable
                            $quantity = 1;
                            if($summary['categoryID']!=3) //cappped=1, noncapped=2
                            {
                              //if noncapped get children quantities
                              if($summary['categoryID']==2)
                              {
                                if($summary['parentID']==0 AND $summary['equipment_value']>1)
                                {
                                  $quantity = $summary['equipment_value'];
                                }
                                else
                                {
                                  $temp = get_noncapped_quantity($summary['equipmentID'], $summary['uniqueID']);
                                  $quantity = ($temp>0)? $temp : 1;
                                }
                              }
                              else //capped items
                              {
                                $quantity = ($summary['equipment_value']>0)? $summary['equipment_value'] : 1;
                              }
                            }
                            else //disposable items
                            {
                              if($summary['equipment_value'] > 1)
                              {
                                $quantity = $summary['equipment_value'];
                              }
                              else
                              {
                                $quantity = (get_disposable_quantity($summary['equipmentID'], $summary['uniqueID'])>0)? get_disposable_quantity($summary['equipmentID'], $summary['uniqueID']) : 1;
                              }
                            }
                            //seperator used
                            // <equipmentid>_SEPERATOR_<uniqueid>
                          ?>
                          <td>
                            <a 
                              class="editable <?php echo $enableclick; ?> editable-text" 
                              href="javascript:;"
                              id="equipment_value"
                              data-type="text" 
                              data-pk="<?php echo $summary['equipmentID'];?>_SEPERATOR_<?php echo $summary['uniqueID'];?>" 
                              data-url="<?php echo base_url('order/update_quantity'); ?>" 
                              data-title="Enter Quantity"
                              data-value="<?php echo $quantity;  ?>"
                            >
                              <?php echo $quantity;  ?> 
                            </a>ea
                          </td>
                          <td class="item_serial_number">
                            <?php if(combine_name(array($summary['serial_num'],$summary['lot_num']))!=""): ?>
                              <p>
                                <a 
                                  class="editable <?php echo $enableclick; ?> editable-text" 
                                  href="javascript:;"
                                  id="serial_num"
                                  data-type="text" 
                                  data-pk="<?php echo $summary['orderID'];?>" 
                                  data-url="<?php echo base_url('order/update_data/text/orderID'); ?>" 
                                  data-title="Enter Serial"
                                  data-value="<?php echo combine_name(array($summary['serial_num'],$summary['lot_num'])) ?>"
                                >
                                  <?php echo combine_name(array($summary['serial_num'],$summary['lot_num'])) ?>
                                </a> &nbsp;&nbsp;
                              </p>
                            <?php endif; ?>
                          </td>
                          <td>
                            <?php 
                              //pickup date functionalities
                              $pickdate = "";
                              $pickdate_val = "";
                              if($summary['summary_pickup_date'] != '0000-00-00' AND $summary['summary_pickup_date']!="")
                              {
                                $pickdate = date("m/d/Y", strtotime($summary['summary_pickup_date']));
                                $pickdate_val = date("Y-m-d", strtotime($pickdate));
                              }
                              else if($summary['summary_pickup_date'] != '0000-00-00' && $summary['activity_reference'] == 2 && $summary['original_activity_typeid'] == 5)
                              {
                                $order_info = get_respite_order_info($summary['uniqueID_reference'],$summary['equipmentID']);
                                $pickdate = date("m/d/Y", strtotime($order_info['summary_pickup_date']));
                                $pickdate_val = date("Y-m-d", strtotime($pickdate));
                              }
                              //getting activity type id depending
                              // on the activity type
                              // cus move and exchange need to be an original activity
                              // type

                              $get_type = "activity_typeid";
                              $allowed_acttypes = array(3,5);
                              if(in_array($summary['activity_reference'], $allowed_acttypes) OR in_array($summary['activity_typeid'], $allowed_acttypes))
                              {
                                $get_type = "activity_reference";
                              }
                            ?>
                            <a 
                              id="summary_pickup_date"
                              data-pk="<?php echo $summary['orderID'] ?>" 
                              data-url="<?php echo base_url('order/update_data/date/orderID/0'); ?>" 
                              data-title="Enter date"
                              data-value="<?php echo $pickdate_val; ?>"
                              data-type="combodate" 
                              data-maxYear="<?php echo date("Y"); ?>"
                              data-format="YYYY-MM-DD" 
                              data-viewformat="MM/DD/YYYY" 
                              data-template="MMM / D / YYYY"
                              href="javascript:void(0)" class="data_tooltip editable <?php echo $enableclick; ?> editable-combodate-notrequired text-danger text-bold" 
                            >
                              <?php echo $pickdate; ?>
                            </a>
                          </td>
                          <td>
                            <?php 
                              //setting styles for each category
                              /*
                              | @label-info = Capped Items
                              | @label-warning = Non Capped Items
                              | @label-success = Disposable
                              |
                              */
                              $types = array(
                                            "Capped Item"     => "label-info",
                                            "Non-Capped Item" => "label-warning"
                                        );
                              $capped_type = "";
                              $font_size = "";
                              if($summary['type'] == "Capped Item")
                              {
                                $capped_type = "C";
                                $style = "font-size:12px;display: block;width: 22px;height: 22px;padding-top: 5px;";
                              }
                              else if($summary['type'] == "Disposable Items")
                              {
                                $capped_type = "D";
                                $style = "font-size:12px;display: block;width: 22px;height: 22px;padding-top: 5px;";
                              }
                              else
                              {
                                $capped_type = "NC";
                                $style = "font-size: 11px;line-height: 2;text-align: center;padding: 0;display: block;height: 22px;width: 22px;";
                              }
                            ?>
                            <p 
                              style="<?php echo $style; ?>"
                              rel="popover" 
                              data-html="true"
                              data-toggle="popover" 
                              data-trigger="hover"
                              data-placement="left"
                              data-content="<?php echo "<b>".$summary['type']."</b>"; ?>" 
                              class="label <?php echo isset($types[$summary['type']])? $types[$summary['type']] : "label-success" ?>"
                              >
                                <?php echo $capped_type; ?>
                            </p>
                          </td>
                          <td>
                            <?php 
                              $equpment_location = get_equipment_location($summary['addressID']);
                              $cont = array(
                                            $equpment_location['street'],
                                            $equpment_location['placenum'],
                                            $equpment_location['city'],
                                            $equpment_location['state'],
                                            $equpment_location['postal']
                                          );
                            ?>
                            <a 
                              href="javascript:;"
                              rel="popover" 
                              data-toggle="popover" 
                              title="Equipment Location" 
                              data-trigger="hover"
                              data-placement="left"
                              data-content="<?php echo combine_name($cont,','); ?>"
                            > 
                              <i class="fa fa-map-marker text-danger"></i>
                            </a>
                          </td>
                          <input type="hidden" name="hdn_unique_id" value="<?php echo $summary['uniqueID'] ?>" class="hdn_unique_id" />
                          <input type="hidden" name="hdn_medical_record_id" value="<?php echo $summary['medical_record_id'] ?>" class="hdn_equip_id" />
                        </tr>
              <?php     
                      endif;
                    echo form_close();  
                  endforeach;
                endif;
              ?>
              </tbody>
            </table>    
          </div>
          <?php }else{ ?>
            <p class="text-center"> This customer has no orders.</p>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

<?php /*
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading font-bold">
            <h4>Order Summary (Item Detail)</h4>
          </div>
            <div class="panel-body">
               <?php
                  $categories_equip = array(1, 2, 3);
                  foreach ($orders as $key => $value) {
                      echo "<strong>" . $key . "</strong>";
                      echo "<ol>";

                      foreach ($value as $sub_key => $sub_value) {
                          if (in_array($sub_value[0]['categoryID'], $categories_equip)) {
                              if (isset($sub_value['children'])) {
                                  echo "<li>" . $sub_key . "<br/><ul>";
                                  foreach ($sub_value['children'] as $children) {
                                      if ($children['input_type'] == "radio") {
                                          echo "<li>" . $children['option_description'] . " : <span class='text-success'>" . trim($children['key_desc']);
                                          echo "</span></li>";
                                      } else if ($children['input_type'] == "text") {
                                          echo "<li>" . $children['key_desc'] . " : <span class='text-success'> " . trim($children['equipment_value']);
                                          echo "</span></li>";
                                      } else if ($children['input_type'] == "checkbox") {
                                          echo "<li>" . $children['option_description'] . " :<span class='text-success'> " . trim($children['key_desc']);
                                          echo "</span></li>";
                                      }
                                  }
                                  echo "</ul></li>";
                              } else {
                                  echo "<li>" . $sub_key . "</li>";
                              }
                          } else {
                              echo "<li>" . $sub_key . " : " . $sub_value[0]['equipment_value'] . "</li>";
                          }
                          //echo "<br />";
                      }
                      echo "</ol>";
                  }
              ?> 
            </div>
        </div>
      </div>
    </div>
*/?>


  </div>
</div>



<?php echo form_open("",array("id"=>"add_additional_equipment_form")) ;?>
<!-- Modal For Equipments -->
<div class="modal " id="equipments_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg" style="overflow:hidden !important">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New Equipment</h4>
      </div>
      <div class="modal-body" style="overflow-y:scroll;max-height:800px;">
          <div class="row">
              <div class="col-md-12" style="margin-bottom:5px">
           <div class="col-sm-3" style="margin-top:15px">
              <label>First Name<span class="text-danger-dker">*</span></label>
              <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_fname"  autocomplete="off" placeholder="Placing Order First Name">
          </div>
           <div class="col-sm-3" style="margin-top:15px">
              <label>Last Name<span class="text-danger-dker">*</span></label>
              <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_lname"  autocomplete="off" placeholder="Placing Order Last Name">
          </div>
      </div>
      <div class="col-md-12" style="margin-bottom:5px">
           <div class="col-sm-6">
              <label>Cellphone Number<span class="text-danger-dker">*</span></label>
              <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_cpnum"  autocomplete="off" placeholder="Cellphone No.">
          </div>
      </div>
      <div class="col-md-12" style="margin-bottom:5px">
           <div class="col-sm-6">
              <label>Email Address<span class="text-danger-dker">*</span></label>
              <input type="text" style="margin-top: 5px;" class="form-control ng-pristine ng-invalid ng-invalid-required ng-valid-validator" name="who_ordered_email"  autocomplete="off" placeholder="Email Address">
          </div>
      </div>

        <div class="col-md-12" style="min-height: 470px;margin-top:40px">
           <?php if (!empty($equipments)) : ?>
              <?php foreach ($equipments as $equipment) :?>
                <div class="form-group col-md-8 wrapper-equipment" data-value="<?php echo $equipment['categoryID'] ?>" id="wrapper_equip_<?php echo $equipment['categoryID'] ?>">
                  <label class="btn btn-default"  style="margin-bottom:20px;margin-top:20px;"  id="equip_<?php echo $equipment['categoryID'] ?>"><?php echo $equipment['type'] ?></label> <br>
                  ttt
                  <div class="equipment" style="display:none;">
                    
                      <label style="margin-top:10px;visibility:hidden"><?php echo $equipment['type'] ?> <span class="text-danger-dker">*</span></label>
                      <div class="col-md-6" style="padding-left:15px;">
                          <?php foreach ($equipment['children'] as $key => $child) : ?>
                            <div class="checkbox">
                              <label class="i-checks"> 
                                  <input type="checkbox" id="" value="<?php echo $child['equipmentID'] ?>" 
                                         name="equipments[]" 
                                         data-target="#<?php echo trim($child['key_name']) ?>_<?php echo $equipment['categoryID']; ?>" 
                                         data-name="<?php echo trim($child['key_name']); ?>" 
                                         data-desc="<?php echo trim($child['key_desc']); ?>" 
                                         data-value="<?php echo $child['key_desc']; ?>" 
                                         data-category="<?php echo $equipment['type']; ?>"
                                         data-category-id="<?php echo $equipment['categoryID']; ?>"
                                         class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID'] ?>">
                                  <i></i>
                                  <?php echo $child['key_desc'] ?>
                              </label>
                            </div>
                            <?php
                            if ($key == $equipment['division'] - 1) {
                                break;
                            }
                            ?>
                        <?php endforeach; ?>
                      </div>
                      <div class="col-md-6" style="padding-left:15px;margin-top:-38px;" id="">
                          <?php for ($i = $equipment['division']; $i <= $equipment['last']; $i++) : ?>
                              <?php
                              $child = $equipment['children'][$i];
                              ?>
                              <div class="checkbox">
                                <label class="i-checks">
                                    <input type="checkbox" id="" value="<?php echo $child['equipmentID'] ?>" 
                                           name="equipments[]" 
                                           data-target="#<?php echo trim($child['key_name']) ?>_<?php echo $equipment['categoryID']; ?>" 
                                           data-name="<?php echo trim($child['key_name']); ?>"
                                           data-desc="<?php echo trim($child['key_desc']); ?>"
                                           data-value="<?php echo $child['key_desc']; ?>"
                                           data-category="<?php echo $equipment['type']; ?>"
                                           data-category-id="<?php echo $equipment['categoryID']; ?>"
                                           class="checkboxes c-<?php echo trim($child['key_name']); ?>-<?php echo $equipment['categoryID'] ?>">
                                    <i></i>
                                    <?php echo $child['key_desc'] ?>
                                </label>
                              </div>
                             
                          <?php endfor; ?>
                      </div>
                  </div>
                </div>
            <?php endforeach; ?>
          <?php endif; ?>

          <div class="col-md-4 col-md-offset-7" style="position:absolute">
            <div class="panel panel-default" style="margin-top: 15px;margin-left: 65px;">
               <div class="panel-heading font-bold">Order Summary</div>
                <div class="panel-body order-cont" style="max-height: 390px !important;overflow-y:scroll;">
  
                </div>
            </div>  
        </div> 

        </div>

        <input type="hidden" value="<?php echo $information['pickup_date'] ?>" name="pickup_date" />
        <input type="hidden" value="<?php echo $information['activity_typeid'] ?>" name="activity_typeid" />
        <?php 
          if(!empty($information['organization_id']))
          {
        ?>
            <input type="hidden" value="<?php echo $information['organization_id'] ?>" name="organization_id" />
        <?php
          }
          else
          {
        ?>
            <input type="hidden" value="<?php echo $information['ordered_by'] ?>" name="organization_id" />
        <?php 
          }
        ?>
        <input type="hidden" value="<?php echo $information['ordered_by'] ?>" name="ordered_by" />
        <!-- <input type="hidden" value="<?php echo $information['who_ordered_fname'] ?>" name="who_ordered_fname" />
        <input type="hidden" value="<?php echo $information['who_ordered_lname'] ?>" name="who_ordered_lname" />
        <input type="hidden" value="<?php echo $information['who_ordered_email'] ?>" name="who_ordered_email" />
        <input type="hidden" value="<?php echo $information['who_ordered_cpnum'] ?>" name="who_ordered_cpnum" /> -->
        <input type="hidden" value="<?php echo $information['comment'] ?>" name="comment" />
        <input type="hidden" value="<?php echo $information['date_ordered'] ?>" name="date_ordered" />
        <input type="hidden" value="<?php echo $information['uniqueID'] ?>" name="uniqueID" />
        <input type="hidden" value="<?php echo $information['order_status'] ?>" name="order_status" />
        <input type="hidden" value="<?php echo $information['deliver_to_type'] ?>" name="delivery_to_type" />
        <input type="hidden" value="<?php echo $information['medical_record_id'] ?>" name="medical_record_id" />
        <?php $id = $this->session->userdata('userID'); ?>
        <input type="hidden" name="person_who_ordered" value="<?php echo $id; ?>" />


          </div>

      

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary save_additional_btn" data-id="<?php echo $info['patientID'] ?>">Save Order</button>
      </div>
    </div>
  </div>
</div>
<!-- END -->


 <!-- Modal for Viewing per Work Order Number -->
<?php foreach($orders as $key=>$value) :?>
    <?php foreach($value as $sub_key=>$sub_value) :?>
      <?php $info = $sub_value[0] ; ?>

<div class="modal " id="view_wo_modal<?php echo $info['uniqueID'] ?>" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
            <div class="modal-header">
              <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">WO# <?php echo $info['uniqueID'] ?></h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
                <div class="row">
                    <div class="">
                        <div class="col-md-6">
                            <strong>Work Order #</strong>
                            <p><?php echo $info['uniqueID'] ?></p>
                            
                            <strong>Date Ordered</strong>
                            <p><?php echo date("m/d/Y", strtotime($info['date_ordered'])) ?></p>

                            <strong>Staff Member</strong>
                            <?php if($info['staff_member_fname'] == '' && $info['staff_member_lname'] == '') :?>
                              <p>NA</p>
                            <?php else:?>
                              <p><?php echo $info['staff_member_fname']  ." ". $info['staff_member_lname'] ?></p>
                            <?php endif;?>

                            <strong>Hospice Staff</strong>
                            <?php if($info['who_ordered_lname'] == '' && $info['who_ordered_fname'] == '') :?>
                              <p>NA</p>
                            <?php else:?>
                              <p><?php echo $info['who_ordered_fname']  ." ". $info['who_ordered_lname'] ?></p>
                            <?php endif;?>

                           <strong>Cellphone Number</strong>
                           <?php if($info['who_ordered_cpnum'] == '') :?>
                            <p>N/A</p>
                          <?php else:?>
                            <p><?php echo $info['who_ordered_cpnum'] ?></p>
                          <?php endif;?>

                          <strong>Gender</strong>
                           <?php if($info['relationship_gender'] == 1) :?>
                            <p>Male</p>
                          <?php else:?>
                            <p>Female</p>
                          <?php endif;?>

                          <strong>Height(ft), Weight(lbs)</strong>
                            <p><?php echo $info['p_height'] ?> , <?php echo $info['p_weight'] ?></p>

                        </div>
                        <div class="col-md-6">
                           <strong>Email Address</strong>
                           <?php if($info['who_ordered_email'] == '') :?>
                              <p>N/A</p>
                           <?php else:?>
                              <p><?php echo $info['who_ordered_email'] ?></p>
                           <?php endif;?>

                            <strong>Delivery Date</strong>
                           <?php if($info['pickup_date'] == '') :?>
                              <p>N/A</p>
                           <?php else:?>
                              <p><?php echo $info['pickup_date'] ?></p>
                           <?php endif;?>

                           <strong>Customer Residence</strong>
                           <?php if($info['deliver_to_type'] == '') :?>
                              <p>N/A</p>
                           <?php else:?>
                              <p><?php echo $info['deliver_to_type'] ?></p>
                           <?php endif;?>

                           <strong>Notes</strong>
                           <?php if($info['comment'] == '') :?>
                              <p>N/A</p>
                           <?php else:?>
                              <p><?php echo $info['comment'] ?></p>
                           <?php endif;?>

                           <strong>Relationship</strong>
                           <?php if($info['p_relationship'] == '') :?>
                              <p>N/A</p>
                           <?php else:?>
                              <p><?php echo $info['p_relationship'] ?></p>
                           <?php endif;?>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-order-close">Close</button>
            </div>
        </div>
    </div>
</div>
  <?php endforeach ;?>
<?php endforeach ;?>

<!-- End -->



<?php endif; ?>

<div class="bg-light lter wrapper-md" style="margin-top:20px">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<!-- Modal for Oxygen concentrator -->
    <div class="modal fade modal_oxygen_concentrator_1" id="oxygen_concentrator_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[61][77]" class="form-control liter_flow_field" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"  class="5_ltr" data-desc="Oxygen Concentrator Type" data-value="5 LPM" name="subequipment[61][80]" id="optionsRadios1" value="5" >
                                            5 LPM
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="10_ltr" data-desc="Oxygen Concentrator Type" data-value="10 LPM"name="subequipment[61][81]" id="optionsRadios1" value="10" >
                                            10 LPM
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">


                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[61][radio][]" id="optionsRadios1" value="78" >
                                            CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[61][radio][]" id="optionsRadios1" value="79" >
                                            PRN
                                        </label>
                                    </div>
                                    <br /> <br/>
                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device" data-value="Nasal Canula" name="subequipment[61][radio][flt]" id="flowtype" value="82" >
                                            Nasal Canula
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[61][radio][flt]" id="optionsRadios1" value="83" >
                                            Oxygen Mask
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Oxygen conserving device -->
    <div class="modal fade modal_oxygen_conserving_device_1" id="oxygen_conserving_device_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Conserving Device</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[62][188]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Type<span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Type" data-value="With Bag" name="subequipment[62][radio][]" id="optionsRadios1" value="197">
                                            With Bag
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Type" data-value="Without Bag" name="subequipment[62][radio][]" id="optionsRadios1" value="198">
                                            Without Bag
                                        </label> 
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->


    <!-- Modal for Oxygen E Portable System CAPPED-->
    <div class="modal fade modal_oxygen_e_portable_system_1" id="oxygen_e_portable_system_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen E Portable System</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[174][189]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->


    <!-- Modal hi-low -->
    <div class="modal fade modal_hi-low_electric_hospital_bed_2" id="hi-low_electric_hospital_bed_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hi-Low Full Electric Hospital Bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Bed Type <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Full Rails" name="subequipment[55][radio][]" id="optionsRadios1" value="74" >
                                        Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="Half Rails" name="subequipment[55][radio][]" id="optionsRadios1" value="75" >
                                        Half Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Hi-Low Electric Bed Type" data-value="No Rails"  name="subequipment[55][radio][]" id="optionsRadios1" value="76" >
                                        No Rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal gastric-drainage -->
    <div class="modal fade modal_gastric_drainage_aspirator_2" id="gastric_drainage_aspirator_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Gastric Drainage Aspirator</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Gastric Drainage Type <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Gastric Drainage Type" data-value="Cont." name="subequipment[16][radio][]" id="optionsRadios1" value="122">
                                        Cont.
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Gastric Drainage Type" data-value="Intermittant" name="subequipment[16][radio][]" id="optionsRadios1" value="123">
                                        Intermittant
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Small volume nebulizer -->
    <div class="modal fade modal_small_volume_nebulizer_1" id="small_volume_nebulizer_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Small Volume Nebulizer</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Aerosol Mask <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Aerosol Mask"  data-value="Yes" name="subequipment[67][radio][]" id="optionsRadios1" value="90">
                                        Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Aerosol Mask"  data-value="No" name="subequipment[67][radio][]" id="optionsRadios1" value="91">
                                        No
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reclining wheelchair --> 
    <div class="modal fade modal_reclining_wheelchair_2" id="reclining_wheelchair_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reclining wheelchair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Reclining Wheelchair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Reclining Wheelchair" data-value='16"'  name="subequipment[64][radio][trw]" id="optionsRadios1" value="84">
                                        16"
                                    </label> 
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" 
                                               data-desc="Type of Reclining Wheelchair" data-value='18"'
                                               name="subequipment[64][radio][trw]" id="optionsRadios1" value="85">
                                        18"
                                    </label>
                                </div>

                                <label style="margin-top: 20px;">Type of Legrest (R) <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio"
                                               data-desc="Type of Legrest (R)" data-value='Elevating Legrests'
                                               name="subequipment[64][radio][tol]" id="optionsRadios1" value="86" >
                                        Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" 
                                               data-desc="Type of Legrest (R)" data-value='Footrests'
                                               name="subequipment[64][radio][tol]" id="optionsRadios1" value="87" >
                                        Footrests
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Shower chair -->
    <div class="modal fade modal_shower_chair_1" id="shower_chair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Shower Chair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Shower Chair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Shower Chair" data-value="With Back" name="subequipment[66][radio][]" id="optionsRadios1" value="88">
                                        With Back
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Shower Chair" data-value="Without Back" name="subequipment[66][radio][]" id="optionsRadios1" value="89">
                                        Without Back
                                    </label>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Geri chair -->
    <div class="modal fade modal_geri_chair_1" id="geri_chair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Geri Chair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Geri Chair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="With Tray" name="subequipment[54][radio][]" id="optionsRadios1" value="192">
                                        With Tray
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Geri Chair" data-value="Without Tray" name="subequipment[54][radio][]" id="optionsRadios1" value="193">
                                        Without Tray
                                    </label>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal wheelchair -->
    <div class="modal fade modal_wheelchair_1" id="wheelchair_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Wheelchair <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='16"' name="subequipment[71][radio][]" id="optionsRadios1" value="92" >
                                        16"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='18"' name="subequipment[71][radio][]" id="optionsRadios1" value="93" >
                                        18"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='20"' name="subequipment[71][radio][]" id="optionsRadios1" value="94" >
                                        20"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='22"' name="subequipment[71][radio][]" id="optionsRadios1" value="95" >
                                        22"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='24"' name="subequipment[71][radio][]" id="optionsRadios1" value="96" >
                                        24"
                                    </label>
                                </div>

                                <br>
                                <label>Type of Legrest <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Legrest" data-value='Elevating Legrests' name="subequipment[71][radio][2]" id="optionsRadios1" value="97" >
                                        Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Legrest" data-value='Footrests' name="subequipment[71][radio][2]" id="optionsRadios1" value="98" checked>
                                        Footrests
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal hospital bed -->
    <div class="modal fade modal_hospital_bed_1" id="hospital_bed_1" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Hospital Bed <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Full Electric" name="subequipment[55][radio][]" id="optionsRadios1" value="72" >
                                        Full Electric
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Semi Electric" name="subequipment[55][radio][]" id="optionsRadios1" value="73" >
                                        Semi Electric
                                    </label>
                                </div>

                                <br><br>


                                <label>Type of Rails<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="Full Rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="74" >
                                        Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="Half Rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="75" >
                                        Half Rails
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="No rails" name="subequipment[55][radio][2]" id="optionsRadios1" value="76" >
                                        No rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal BIPAP setup -->
    <div class="modal fade modal_bipap_2" id="bipap_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">BIPAP Settings</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>IPAP <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="IPAP" class="form-control" placeholder="e.g 14" name="subequipment[4][109]">
                                </div>

                                <div class="form-group">
                                    <label>EPAP <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="EPAP" class="form-control" placeholder="e.g5" name="subequipment[4][110]">
                                </div>

                                <div class="form-group">
                                    <label>Rate <i>(If applicable)</i> </label>
                                    <input type="text" data-desc="Rate" class="form-control" placeholder="" name="subequipment[4][111]">
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal CPAP setup -->
    <div class="modal fade modal_cpap_2" id="cpap_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg equipments_modal" id="myModalLabel">CPAP Settings</h4>
                </div>
                <div class="modal-body OpenSans-Reg">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>CMH20 <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="IPAP" class="form-control" placeholder="e.g 14" name="subequipment[9][114]">
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal E-cylinder -->
    <div class="modal fade modal_e-cylinder_2" id="e-cylinder_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">E-Cylinder</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of E-Cylinder <span style="color:red;">*</span></label>
                                    <input type="text"  data-desc="Quantity of E-cylinder" class="form-control" placeholder="ex. 1" name="subequipment[11][121]">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->


    <!-- Modal M6-Cylinder -->
    <div class="modal fade modal_cylinder_m6_2" id="cylinder_m6_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">M6 Cylinder</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of M6 Cylinder <span style="color:red;">*</span></label>
                                    <input type="text"  data-desc="Quantity of M6 Cylinder" class="form-control" placeholder="ex. 1" name="subequipment[170][194]">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal pressure-mattress -->
    <div class="modal fade modal_alternating_pressure_mattress_2" id="alternating_pressure_mattress_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Alternating Pressure Mattress</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Extended? NC <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Extended? NC" data-value="Yes" name="subequipment[2][radio][]" id="optionsRadios1" value="107" >
                                        Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Extended? NC" data-value="No" name="subequipment[2][radio][]" id="optionsRadios1" value="108" >
                                        No
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>







    <!-- Modal m6-cylinder -->
    <div class="modal fade modal_m6-cylinder_2" id="m6-cylinder_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">M6-Cylinder</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Quantity of M6-Cylinder NC <span style="color:red;">*</span></label>
                                    <input type="text" data-desc="Quantity of M6-cylinder NC" class="form-control" placeholder="ex. 1" name="subequipment[27][99]">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div> 

    <!-- additional -->
    <!-- Modal hospital bed -->
    <div class="modal fade modal_hospital_bed_2" id="hospital_bed_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Hospital bed</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Hospital Bed <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Full Electric" name="subequipment[20][radio][]" id="optionsRadios1" value="134" >
                                        Full Electric
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Hospital Bed" data-value="Semi Electric" name="subequipment[20][radio][]" id="optionsRadios1" value="135" >
                                        Semi Electric
                                    </label>
                                </div>




                                <label style="margin-top: 20px;">Type of Rails<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="Full Rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="136" >
                                        Full Rails
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="Half Rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="137" >
                                        Half Rails
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Rails" data-value="No rails" name="subequipment[20][radio][2]" id="optionsRadios1" value="138" >
                                        No rails
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Oxygen concentrator -->
    <div class="modal fade modal_oxygen_concentrator_2" id="oxygen_concentrator_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Concentrator</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[29][100]" class="form-control liter_flow_field" id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Oxygen Concentrator Type <span style="color:red;">*</span></label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="5_ltr" data-desc="Oxygen Concentrator Type" data-value="5 LPM" name="subequipment[29][101]" id="optionsRadios1" value="5" >
                                            5 LPM
                                        </label>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="10_ltr" data-desc="Oxygen Concentrator Type" data-value="10 LPM"name="subequipment[29][102]" id="optionsRadios1" value="10" >
                                            10 LPM
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">


                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[29][radio][]" id="optionsRadios1" value="103" >
                                            CONT
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[29][radio][]" id="optionsRadios1" value="104" >
                                            PRN
                                        </label>
                                    </div>
                                    <br /> <br/>
                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device" data-value="Nasal Canula" name="subequipment[29][radio][flt]" id="flowtype" value="105" >
                                            Nasal Canula
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[29][radio][flt]" id="optionsRadios1" value="106" >
                                            Oxygen Mask
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal for Oxygen Reservoir -->
    <div class="modal fade modal_oxygen_liquid_2" id="oxygen_liquid_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Liquid Reservoir</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[36][201]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Duration <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="CONT" name="subequipment[36][radio][]" id="optionsRadios1" value="202" >
                                            CONT
                                        </label>
                                    </div> 
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Duration" data-value="PRN" name="subequipment[36][radio][]" id="optionsRadios1" value="203" >
                                            PRN 
                                        </label>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <label>Delivery Device <span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device" data-value="Nasal Canula" name="subequipment[36][radio][flt]" id="" value="204" >
                                            Nasal Canula
                                        </label>
                                    </div>  

                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Delivery Device"  data-value="Oxygen Mask" name="subequipment[36][radio][flt]" id="optionsRadios1" value="205" >
                                            Oxygen Mask
                                        </label>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Oxygen conserving device NONCAPPED-->
    <div class="modal fade modal_oxygen_conserving_device_2" id="oxygen_conserving_device_2" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen Conserving Device</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[31][190]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>

                                    <label>Type<span style="color:red;">*</span></label>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Type" data-value="With Bag" name="subequipment[31][radio][]" id="optionsRadios1" value="199">
                                            With Bag
                                        </label>
                                    </div>

                                    <div class="radio">
                                        <label>
                                            <input type="radio" data-desc="Type" data-value="Without Bag" name="subequipment[31][radio][]" id="optionsRadios1" value="200">
                                            Without Bag
                                        </label> 
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->


    <!-- Modal for Oxygen E Portable System NONCAPPED-->
    <div class="modal fade modal_oxygen_e_portable_system_2" id="oxygen_e_portable_system_2" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Oxygen E Portable System</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Liter Flow <span style="color:red;">*</span></label>
                                        <input type="text" data-desc="Liter Flow" name="subequipment[176][191]" class="form-control " id="exampleInputEmail1" placeholder="Enter Liter Flow" style="margin-bottom:31px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End -->







    <!-- Modal Shower chair -->
    <div class="modal fade modal_shower_chair_2" id="shower_chair_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Shower chair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Shower chair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Shower chair" data-value="With Back" name="subequipment[39][radio][]" id="optionsRadios1" value="112">
                                        With Back
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Shower chair" data-value="Without Back" name="subequipment[39][radio][]" id="optionsRadios1" value="113">
                                        Without Back
                                    </label>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Small volume nebulizer -->
    <div class="modal fade modal_small_volume_nebulizer_2" id="small_volume_nebulizer_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Small Volume Nebulizer</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Aerosol Mask <span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Aerosol Mask"  data-value="Yes" name="subequipment[40][radio][]" id="optionsRadios1" value="115">
                                        Yes
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Aerosol Mask"  data-value="No" name="subequipment[40][radio][]" id="optionsRadios1" value="116">
                                        No
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal wheelchair -->
    <div class="modal fade modal_wheelchair_2" id="wheelchair_2" tabindex="-1" data-category="2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Wheelchair</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Type of Wheelchair<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='16"' name="subequipment[49][radio][]" id="optionsRadios1" value="124" >
                                        16"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='18"' name="subequipment[49][radio][]" id="optionsRadios1" value="125" >
                                        18"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='20"' name="subequipment[49][radio][]" id="optionsRadios1" value="126" >
                                        20"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='22"' name="subequipment[49][radio][]" id="optionsRadios1" value="127" >
                                        22"
                                    </label>
                                </div>

                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Wheelchair" data-value='24"' name="subequipment[49][radio][]" id="optionsRadios1" value="128" >
                                        24"
                                    </label>
                                </div>


                                <label>Type of Legrest<span style="color:red;">*</span></label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Legres" data-value='Elevating Legrests' name="subequipment[49][radio][2]" id="optionsRadios1" value="132" >
                                        Elevating Legrests
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" data-desc="Type of Legres" data-value='Footrests' name="subequipment[49][radio][2]" id="optionsRadios1" value="133" checked>
                                        Footrests
                                    </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- For the DISPOSABLE SUPPPLIES Modals -->

    <!-- Aerosol Mask -->
    <div class="modal fade modal_adult_aerosol_mask_3" id="adult_aerosol_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Adult Aerosol Mask</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[156][209]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Adult Nasal Cannnula -->
    <div class="modal fade modal_adult_nasal_cannula_3" id="adult_nasal_cannula_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Adult Nasal Cannula</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[143][210]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Anti Tippers -->
    <div class="modal fade modal_anti_tippers_3" id="anti_tippers_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Anti Tippers</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[168][211]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


   
     <!-- Circuit, Peep Valve, T Piece Adaptor -->
    <div class="modal fade modal_circuit_peep_valve_t_piece_adaptor_3" id="circuit_peep_valve_t_piece_adaptor_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Circuit, Peep Valve, T Piece Adaptor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[169][212]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Corrugated Tubing (7ft) -->
    <div class="modal fade modal_circuit_corrugated_tubing_7ft_3" id="corrugated_tubing_7ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Corrugated Tubing (7ft)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                 <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[163][213]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!-- E Cylinder Wrench -->
    <div class="modal fade modal_e_cylinder_wrench_3" id="e_cylinder_wrench_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">E Cylinder Wrench</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[141][214]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!-- Feeding Bags -->
    <div class="modal fade modal_feeding_bags_3" id="feeding_bags_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Feeding Bags</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[155][215]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--High Flow Nasal Cannula (6L & Higher) -->
    <div class="modal fade modal_high_flow_nasal_cannula_3" id="high_flow_nasal_cannula_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">High Flow Nasal Cannula (6L & Higher)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[144][216]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

     <!--High Flow O2 Humidifier Bottle -->
    <div class="modal fade modal_high_flow_o2_humidifier_bottle_3" id="high_flow_o2_humidifier_bottle_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">High Flow O2 Humidifier Bottle</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[145][217]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--Jet Nebulizers -->
    <div class="modal fade modal_jet_nebulizers_3" id="jet_nebulizers_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Jet Nebulizers</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[164][218]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


      <!--Nebulizer Kits (Mouthpiece) -->
    <div class="modal fade modal_nebulizer_kits_mouthpiece_3" id="nebulizer_kits_mouthpiece_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Nebulizer Kits (Mouthpiece)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[157][219]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--Non-Rebreather O2 Mask-->
    <div class="modal fade modal_non_rebreather_o2_mask_3" id="non_rebreather_o2_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Non-Rebreather O2 Mask</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[146][220]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--O2 Connector-->
    <div class="modal fade modal_o2_connector_3" id="o2_connector_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">O2 Connector</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[147][221]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--O2 Humidifier Bottle-->
    <div class="modal fade modal_o2_humidifier_bottle_3" id="o2_humidifier_bottle_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">O2 Humidifier Bottle</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[148][222]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!--O2 Mask-->
    <div class="modal fade modal_o2_mask_3" id="o2_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">O2 Mask</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[149][223]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--O2 Tubing 21FT-->
    <div class="modal fade modal_o2_tubing_21ft_3" id="o2_tubing_21ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">O2 Tubing 21FT</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[150][224]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--O2 Tubing 7FT-->
    <div class="modal fade modal_o2_tubing_7ft_3" id="o2_tubing_7ft_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">O2 Tubing 7FT</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[151][225]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Pediatric Aerosol Mask-->
    <div class="modal fade modal_pediatric_aerosol_mask_3" id="pediatric_aerosol_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Pediatric Aerosol Mask</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[158][226]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Pediatric Nasal Cannula-->
    <div class="modal fade modal_pediatric_nasal_cannula_3" id="pediatric_nasal_cannula_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Pediatric Nasal Cannula</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[152][227]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Pressure Line Adaptor-->
    <div class="modal fade modal_pressure_line_adaptor_3" id="pressure_line_adaptor_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Pressure Line Adaptor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[166][228]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



    <!--Seat Belt-->
    <div class="modal fade modal_seat_belt_3" id="seat_belt_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Seat Belt</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[167][229]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Suction Canister-->
    <div class="modal fade modal_suction_canister_3" id="suction_canister_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Suction Canister</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[159][230]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



     <!--Suction Tubing Long-->
    <div class="modal fade modal_suction_tubing_long_3" id="suction_tubing_long_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Suction Tubing Long</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[160][231]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Suction Tubing Short-->
    <div class="modal fade modal_suction_tubing_short_3" id="suction_tubing_short_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Suction Tubing Short</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[161][232]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


    <!--Trach Mask-->
    <div class="modal fade modal_trach_mask_3" id="trach_mask_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Trach Mask</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[165][233]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>



     <!--Venturi Mask (Vent)-->
    <div class="modal fade modal_venturi_mask_vent_3" id="venturi_mask_vent_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Venturi Mask (Vent)</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[153][234]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


     <!--X-Mas Tree Adaptor-->
    <div class="modal fade modal_x_mas_tree_adaptor_3" id="x_mas_tree_adaptor_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">X-Mas Tree Adaptor</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[154][235]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>


      <!--Y Connector-->
    <div class="modal fade modal_y_connector_3" id="y_connector_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Y Connector</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[142][236]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div> 
            </div>
        </div> 
    </div>   


      <!--Yankuer Suction Tubing-->
    <div class="modal fade modal_yankuer_suction_tubing_3" id="yankuer_suction_tubing_3" tabindex="-1" data-category="3" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                  <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Yankuer Suction Tubing</h4>
                </div>
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="row">
                        <div class="">
                            <div class="col-md-12">

                                <label>Quantity <span style="color:red;">*</span></label>
                                <div class="">
                                    <label>
                                        <input type="text" data-desc="Quantity" name="subequipment[162][237]" class="form-control " id="exampleInputEmail1" placeholder="Enter Quantity" style="margin-bottom:31px;">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-order-close">Cancel</button>
                    <button type="button" class="btn btn-primary btn-order">Save changes</button>
                </div>
            </div>
        </div>
    </div>

 
</form>
