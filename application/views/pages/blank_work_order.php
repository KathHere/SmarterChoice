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

  .under-line {
    text-decoration: none;
    border-bottom: dashed 1px #0088cc;
    padding-left: 10px;
    padding-right: 10px;
  }

  .new-patient-icon{
    font-family: "Goudy Old Style", Garamond, "Big Caslon", "Times New Roman", serif;
    font-weight: bold;
    margin-left: 5px;
  }

  .print_label
  {
    font-weight: bold;
  }

  .activity_type_checkboxes
  {
    margin-left: 10%;
  }

  .print_content_label_div
  {
    margin-bottom: 5px;
  }

  .td_content
  {
    border-right:1px solid rgba(0, 0, 0, 0.50);
    border-left:1px solid rgba(0, 0, 0, 0.50);
    border-bottom:1px solid rgba(0, 0, 0, 0.50);
    border-top:1px solid rgba(0, 0, 0, 0.50) !important;
    padding-right: 0px !important;
  }

  .td_ordered_items
  {
    text-align: center;
    border-right:1px solid rgba(0, 0, 0, 0.50);
    border-left:1px solid rgba(0, 0, 0, 0.50);
    border-bottom:1px solid rgba(0, 0, 0, 0.50);
    border-top:1px solid rgba(0, 0, 0, 0.50) !important;
  }

  .td_ordered_items_empty
  {
    text-align: center;
    border-right:1px solid rgba(0, 0, 0, 0.50);
    border-left:1px solid rgba(0, 0, 0, 0.50);
    border-bottom:1px solid rgba(0, 0, 0, 0.50);
    border-top:1px solid rgba(0, 0, 0, 0.50) !important;
  }

  .td_ordered_items_blank
  {
    height:34px !important;
    padding-left: 5px !important;
    padding-right: 5px !important;
  }

  .ordered_items_table
  {
    border:1px solid rgba(0, 0, 0, 0.50) !important;
    margin-top:30px;
  }

  .td_delivery_instructions
  {
    border-right:1px solid rgba(0, 0, 0, 0.50) !important;
    border-left:1px solid rgba(0, 0, 0, 0.50) !important;
    border-bottom:1px solid rgba(0, 0, 0, 0.50) !important;
    border-top:1px solid rgba(0, 0, 0, 0.50) !important;
    padding-top: 4px !important;
    padding-bottom: 0px !important;
  }

  .print_content_label_div_first
  {
    margin-top:10px !important;
  }

  .print_content_label_div_second
  {
    margin-bottom:20px !important;
  }

  .header_input_blank
  {
    width:200px !important;
    border:0px;
    background-color: #f6f8f8;
    text-transform: none !important;
  }

  .table_input_blank
  {
    border:0px !important;
  }

  .scheduled_order_date_input_blank
  {
    width: 150px;
    border:0px;
  }

  /********************************************************************************************************
  * STYLES FOR PRINTING
  ********************************************************************************************************/
  @media print{

    @page { margin-bottom: 0; }

    .work_order_header_container
    {
      border-bottom:0px;
    }
    .work_order_content_container
    {
      border:0px;
      margin-top: -20px;
      padding-left: 0px;
      padding-right: 0px;
    }
    .logo_ahmslv
    {
      margin-top:-10px;
    }
    .logo_ahmslv_img
    {
      height: 50px !important;
      width: 60px !important;
    }

    .work_order_header_first
    {
      font-size:13px !important;
      margin-top: -2px !important;
    }

    .work_order_header
    {
      font-size:11px !important;
      margin-top: -14px !important;
    }

    .work_order_header_second
    {
      font-size:11px !important;
      margin-top: -10px !important;
    }

    .printed_by_html
    {
      font-size:10px !important;
      margin-left: 15px !important;
      margin-top: -50px !important;
      margin-bottom: -10px;
    }

    .print_label
    {
      font-weight: bold;
      font-size: 11px;
    }
    .print_content_label_div
    {
      font-size: 11px !important;
      margin-bottom: 0px;
    }
    .order_info_table
    {
      font-size: 11px !important;
    }
    .ordered_items_table
    {
      font-size: 11px !important;
      margin-top: -11px !important;
      margin-bottom:1px !important;
    }
    .print_content_label_div_first
    {
      margin-top:0px !important;
    }
    .print_content_label_div_second
    {
      margin-top:0px !important;
      margin-bottom: -13px !important;
    }
    .td_content
    {
      padding-top: 4px !important;
      padding-bottom:4px !important;
      padding-left: 4px !important;
    }
    .tr_ordered_items_empty
    {
      height:10px !important;
    }
    .td_ordered_items
    {
      padding-top: 3px !important;
      padding-bottom:3px !important;
      padding-left: 4px !important;
    }
    .last_row_order_info_table
    {
      padding-bottom:-24px !important;
    }
    .delivery_instructions_label
    {
      font-size: 11px !important;
    }
    .work_order_created_by_content
    {
      font-size: 11px !important;
      margin-top: 0px !important;
    }
    .representative_signature_content
    {
      font-size: 11px !important;
      margin-top: 5px !important;
    }
    .order_details_below
    {
      font-size: 11px !important;
    }
    .office_details
    {
      margin-top: -5px;
      font-size: 12px !important;
    }
    .panel-default
    {
      border: none;
    }
    .order_details_below_row_container
    {
      margin-top: 0px !important;
      font-size: 11px !important;
    }
    .order_details_below_row_container_second
    {
      margin-top: 10px !important;
    }
    .order_details_label_first
    {
      font-size: 11px !important;
      margin-top: -10px;
    }
    .order_details_label_second
    {
      font-size: 11px !important;
      margin-top: -10px;
    }
    .misc_item_description
    {
      font-size: 8px !important;
    }
    .first_hr
    {
      padding-left:0px;
      padding-right:0px;
    }
    .fourth_hr
    {
      padding-left:0px;
      padding-right:0px;
    }
    .work_order_header_second
    {
      margin-top:-12px !important;
      font-size: 12px !important;
      margin-bottom: 12px !important;
    }
    .work_order_header_third
    {
      margin-top:-16px !important;
      font-size: 12px !important;
    }
    .work_order_header_fourth
    {
      margin-top:-16px !important;
      font-size: 12px !important;
    }
    .customer_name_input_blank
    {
      width:430px !important;
    }
    .mrno_input_blank
    {
      width:80px !important;
    }
    .street_input_blank
    {
      width:157px !important;
    }
    .apt_input_blank
    {
      width:145px !important;
    }
    .city_input_blank
    {
      width:95px !important;
    }
    .state_input_blank
    {
      width:30px !important;
    }
    .zip_input_blank
    {
      width:95px !important;
    }
    .phone1_input_blank
    {
      width:148px !important;
    }
    .altphone1_input_blank
    {
      width:110px !important;
    }
    .sex_input_blank
    {
      width:90px !important;
    }
    .height_input_blank
    {
      width:27px !important;
    }
    .weight_input_blank
    {
      width:80px !important;
    }
    .nextofkin_input_blank
    {
      width:132px !important;
    }
    .relationship_input_blank
    {
      width:105px !important;
    }
    .nextofkinphone_input_blank
    {
      width:90px !important;
    }
    .residence_input_blank
    {
      width:150px !important;
    }
    .provider_input_blank
    {
      width:152px !important;
    }
    .phone2_input_blank
    {
      width:130px !important;
    }
    .orderedby_input_blank
    {
      width:81px !important;
    }
    .phone3_input_blank
    {
      width:160px !important;
    }
    .rx_input_blank
    {
      width:280px !important;
    }
    .nooflocations_input_blank
    {
      width:40px !important;
    }
    .confirmedby_input_blank
    {
      width:80px !important;
    }
    .td_ordered_items_blank
    {
      padding-top: 5px !important;
      padding-left: 0px !important;
      padding-right: 0px !important;
      height:25px !important;
    }
    .signature_details_second
    {
      font-size: 11px !important;
    }
    .print_name
    {
      width:200px !important;
    }
    .representative_signature
    {
      width:200px !important;
    }
    .relationship_below
    {
      width:190px !important;
    }
    .table_input_blank
    {
      font-size:11px !important;
    }
    .scheduled_order_date
    {
      font-size:11px !important;
      width:120px !important;
    }
    .customer_signature
    {
      width:200px !important;
    }
    .work_order_created_by
    {
      width:200px !important;
    }
    .header_input_blank
    {
      font-size:11px !important;
    }
    .first_td
    {
      width:180px !important;
    }
    .second_td
    {
      width:165px !important;
    }
    .third_td
    {
      width:130px !important;
    }
    .fourth_td
    {
      width:60px !important;
    }
    .fifth_td
    {
      width:120px !important;
    }
    .merge_first_second_td
    {
      width:300px !important;
    }
    .merge_fourth_fifth_td
    {
      width:200px !important;
    }
    .first_td_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:35px !important;
    }
    .second_td_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:35px !important;
    }
    .second_inserted_td_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:35px !important;
    }
    .second_v2_td_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:35px !important;
    }
    .third_td_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:280px !important;
    }
    .fourth_td_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:30px !important;
    }
    .fifth_td_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:120px !important;
    }
    .first_input_blank_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:20px !important;
    }
    .second_inserted_input_blank_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:20px !important;
    }
    .second_input_blank_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:40px !important;
    }
    .second_v2_input_blank_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:25px !important;
    }
    .third_input_blank_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:290px !important;
    }
    .fourth_input_blank_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:25px !important;
    }
    .fifth_input_blank_secondtbl
    {
      padding-left: 0px !important;
      padding-right: 0px !important;
      width:116px !important;
    }
    .td_delivery_instructions
    {
      width:650px !important;
      padding-top: 3px !important;
      padding-bottom:3px !important;
      padding-left: 4px !important;
    }
    .delivery_instructions_div
    {
      margin-bottom: 28px !important;
    }
    .delivery_instructions_input_blank
    {
      height:50px !important;
      font-size: 12px !important;
    }
    .rental_agreement_row
    {
      padding: 2px !important;
    }
  }

</style>

<p class="visible-print-block printed_by_html" style="margin-top:-55px; margin-left:-2px;"> Printed by: <?php echo ucfirst($this->session->userdata('firstname')) ." ". $this->session->userdata('lastname') ?> </p>

<div class="bg-light lter b-b wrapper-md work_order_header_container">
  <p class="logo_ahmslv" style="margin-bottom:0px;text-align:center;">
    <img class="logo_ahmslv_img" src="<?php echo base_url('assets/img/smarterchoice_logo.png'); ?>" alt="" style="height:50px;width:58px;"/>
  </p>
  <?php
    $location = get_login_location($this->session->userdata('user_location'));
  ?>
  <h4 class="work_order_header_first" style="font-weight:bold;margin-top:0px;font-size:17px;text-align:center;margin-bottom:13px;"> Advantage Home Medical Services, Inc </h4>
  <h4 class="work_order_header work_order_header_second" style="text-align:center;font-weight:bold;margin-top:-6px;font-size:15px;"> <?php echo $location['user_street']; ?></h4>
  <div class="print_content_label_div" style="margin-top:-13px;">
    <span class="print_label">Entry Time:</span> <input type="text" class="header_input_blank">
  </div>
  <h4 class="work_order_header work_order_header_third" style="text-align:center;font-weight:bold;margin-top:-20px;font-size:15px;">  <?php echo $location['user_city']; ?>, <?php echo $location['user_state']; ?> <?php echo $location['user_postalcode']; ?> </h4>
  <div class="print_content_label_div" style="margin-top:-11px;">
    <span class="print_label">Work Order #:</span> <input type="text" class="header_input_blank">
  </div>
  <h4 class="work_order_header work_order_header_fourth" style="text-align:center;font-weight:bold;margin-top:-21px;font-size:15px;"> Phone: <?php echo $location['location_phone_no']; ?> &nbsp;  Fax: <?php echo $location['location_fax_no']; ?> </h4>

</div>

<div class="wrapper-md work_order_content_container">
  <div class="panel panel-default">
    <div class="panel-body">

      <div class="print_content_label_div print_content_label_div_first">
        <span class="print_label">Activity Type:

        <?php
        /****** DELIVERY ******/
        ?>
        <span style="margin-left:1%;" class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="1"> Delivery </span>

        <?php
        /****** EXCHANGE ******/
        ?>
        <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="3"> Exchange </span>

        <?php
        /****** PICK UP ******/
        ?>
        <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="2"> Pick Up </span>


        <?php
        /****** CUS MOVE ******/
        ?>
        <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="4">  CUS Move </span>

        <?php
        /****** RESPITE ******/
        ?>
        <span class="activity_type_checkboxes"> <input style="font-size:11px;" type="checkbox" name="activity_type" value="5">  Respite </span>

        </span>
      </div>
      <div class="print_content_label_div print_content_label_div_second">
        <span class="print_label">Scheduled Order Date:</span> <input type="text" class="scheduled_order_date_input_blank scheduled_order_date">
      </div>

      <table class="table order_info_table" style="border:1px solid rgba(0, 0, 0, 0.55) !important; margin-top:15px;">
        <tbody style="border-top:1px solid;border-top-color:rgba(0, 0, 0, 0.55);">
          <tr>
            <td class="td_content" colspan="4">
              <span class="print_label" >Customer name: </span> <input type="text" class="table_input_blank customer_name_input_blank" style="width:700px;">
            </td>
            <td class="td_content">
              <span class="print_label">MR#: </span> <input type="text" class="table_input_blank mrno_input_blank" style="width:120px;">
            </td>
          </tr>
          <tr>
            <td class="td_content first_td" style="width:29%;">
              <span class="print_label">Street: </span> <input type="text" class="table_input_blank street_input_blank" style="width:270px;">
            </td>
            <td class="td_content second_td" style="width:25%;">
              <span class="print_label">Apt.: </span> <input type="text" class="table_input_blank apt_input_blank" style="width:230px;">
            </td>
            <td class="td_content third_td">
              <span class="print_label">City: </span> <input type="text" class="table_input_blank city_input_blank" style="width:140px;text-transform:none;">
            </td>
            <td class="td_content fourth_td" style="width:12%;">
              <span class="print_label">State: </span> <input type="text" class="table_input_blank state_input_blank" style="width:77px;">
            </td>
            <td class="td_content fifth_td" style="width:15%;">
              <span class="print_label">Zip: </span>  <input type="text" class="table_input_blank zip_input_blank" style="width:125px;">
            </td>
          </tr>
          <tr>
            <td class="td_content first_td" style="width:29%;">
              <span class="print_label">Phone #: </span> <input type="text" class="table_input_blank phone1_input_blank" style="width:260px;">
            </td>
            <td class="td_content second_td" style="width:25%;">
              <span class="print_label">Alt. Phone #: </span> <input type="text" class="table_input_blank altphone1_input_blank" style="width:182px;">
            </td>
            <td class="td_content third_td">
              <span class="print_label">Sex: </span> <input type="text" class="table_input_blank sex_input_blank" style="width:140px;text-transform:none;">
            </td>
            <td class="td_content fourth_td" style="width:12%;">
              <span class="print_label">Height: </span> <input type="text" class="table_input_blank height_input_blank" style="width:70px;">
            </td>
            <td class="td_content fifth_td" style="width:15%;">
              <span class="print_label">Weight: </span> <input type="text" class="table_input_blank weight_input_blank" style="width:105px;">
            </td>
          </tr>
          <tr>
            <td class="td_content first_td">
              <span class="print_label">Next of Kin: </span> <input type="text" class="table_input_blank nextofkin_input_blank" style="width:240px;">
            </td>
            <td class="td_content second_td">
              <span class="print_label ">Relationship: </span><input type="text" class="table_input_blank relationship_input_blank" style="width:175px;">
            </td>
            <td class="td_content third_td">
              <span class="print_label ">Phone: </span> <input type="text" class="table_input_blank nextofkinphone_input_blank" style="width:160px;">
            </td>
            <td class="td_content merge_fourth_fifth_td" colspan="2">
              <span class="print_label">Residence: </span><input type="text" class="table_input_blank residence_input_blank" style="width:217px;text-transform:none;">
            </td>
          </tr>
          <tr>
            <td class="td_content first_td">
              <span class="print_label">Provider: </span><input type="text" class="table_input_blank provider_input_blank" style="width:258px;text-transform:none;">
            </td>
            <td class="td_content second_td">
              <span class="print_label">Phone: </span><input type="text" class="table_input_blank phone2_input_blank" style="width:215px;">
            </td>
            <td class="td_content third_td">
              <span class="print_label">Ordered By: </span><input type="text" class="table_input_blank orderedby_input_blank" style="width:130px;">
            </td>
            <td class="td_content merge_fourth_fifth_td" colspan="2">
              <span class="print_label">Phone: </span><input type="text" class="table_input_blank phone3_input_blank" style="width:220px;">
            </td>
          </tr>
          <tr>
            <td class="td_content last_row_order_info_table merge_first_second_td" colspan="2">
              <span class="print_label">RX: </span> <input type="text" class="table_input_blank rx_input_blank" style="width:554px;text-transform:none;">
            </td>
            <td class="td_content last_row_order_info_table third_td">
              <span class="print_label">No. of Location(s): </span><input type="text" class="table_input_blank nooflocations_input_blank" style="width:90px;">
            </td>
            <td class="td_content last_row_order_info_table merge_fourth_fifth_td" colspan="2">
              <span class="print_label">Work Order Confirmed By: </span> <input type="text" class="table_input_blank confirmedby_input_blank" style="width:125px;">
            </td>
          </tr>
        </tbody>
      </table>

      <table class="table ordered_items_table">
        <tbody style="border-top:1px solid;border-top-color:rgba(0, 0, 0, 0.55);">
          <tr>
            <td class="td_ordered_items first_td_secondtbl" style="width:7%;">
              <span class="print_label">Del/PU/Ex </span>
            </td>
            <td class="td_ordered_items second_inserted_td_secondtbl" style="width:8%;">
              <span class="print_label">Sale/Rental </span>
            </td>
            <td class="td_ordered_items second_td_secondtbl" style="width:8%;">
              <span class="print_label">Item# </span>
            </td>
            <td class="td_ordered_items second_v2_td_secondtbl" style="width:6%;">
              <span class="print_label"> WHSE </span>
            </td>
            <td class="td_ordered_items third_td_secondtbl" style="width:45%;">
              <span class="print_label">Item Description </span>
            </td>
            <td class="td_ordered_items fourth_td_secondtbl" style="width:6%;">
              <span class="print_label">Qty </span>
            </td>
            <td class="td_ordered_items fifth_td_secondtbl" style="width:20%;">
              <span class="print_label">Serial / Lot Number </span>
            </td>
          </tr>
          <?php
            for($j = 0; $j < 12; $j++){
          ?>
              <tr>
                <td class="td_ordered_items td_ordered_items_blank first_td_secondtbl">
                  <input type="text" class="table_input_blank first_input_blank_secondtbl" style="width:60px;text-align:center;">
                </td>
                <td class="td_ordered_items td_ordered_items_blank second_inserted_td_secondtbl">
                  <input type="text" class="table_input_blank second_inserted_input_blank_secondtbl" style="width:60px;text-align:center;text-transform:none;">
                </td>
                <td class="td_ordered_items td_ordered_items_blank second_td_secondtbl">
                  <input type="text" class="table_input_blank second_input_blank_secondtbl" style="width:75px;text-align:center;text-transform:none;">
                </td>
                <td class="td_ordered_items td_ordered_items_blank second_v2_td_secondtbl">
                  <input type="text" class="table_input_blank second_v2_input_blank_secondtbl" style="width:60px;text-align:center;text-transform:none;">
                </td>
                <td class="td_ordered_items td_ordered_items_blank third_td_secondtbl">
                  <input type="text" class="table_input_blank third_input_blank_secondtbl" style="width:525px;text-align:center;text-transform:none;">
                </td>
                <td class="td_ordered_items td_ordered_items_blank fourth_td_secondtbl">
                  <input type="text" class="table_input_blank fourth_input_blank_secondtbl" style="width:60px;text-align:center;text-transform:none;">
                </td>
                <td class="td_ordered_items td_ordered_items_blank fifth_td_secondtbl">
                  <input type="text" class="table_input_blank fifth_input_blank_secondtbl" style="width:190px;text-align:center;">
                </td>
              </tr>
          <?php
            }
          ?>
          <tr class="tr_ordered_items_empty" style="height:35px;">
            <td class="td_ordered_items_empty rental_agreement_row" colspan="7">
              <strong> I understand that the above equipment is a rental and will be picked up once I'm off of Hospice. Initial _______ </strong>
            </td>
          </tr>
        </tbody>
      </table>

      <strong><span class="delivery_instructions_label">Delivery Instructions</span></strong>
      <div class="col-xs-12 col-sm-12 col-md-12 delivery_instructions_div" style="border:1px solid #2b2727 !important; margin-top:5px;height:75px; padding:0;">
        <textarea class="table_input_blank delivery_instructions_input_blank" style="min-height:72px !important;width:100%;resize:none;"></textarea>
      </div>

      <div class="col-xs-12 col-sm-12">
        <div class="row order_details_below_row_container" style="text-align:center;margin-top:45px;">
          <div class="col-xs-4 col-sm-4 first_hr">
            <input type="text" class="table_input_blank customer_signature" style="width:260px;text-align:center;">
            <hr style="width:90%;border:1px solid rgba(0, 0, 0, 0.55);margin-top:5px !important;" />
          </div>
          <div class="col-xs-4 col-sm-4">
            <input type="text" class="table_input_blank scheduled_order_date" style="width:190px;text-align:center;">
            <hr style="width:60%;border:1px solid rgba(0, 0, 0, 0.55);margin-top:5px !important;" />
          </div>
          <div class="col-xs-4 col-sm-4 work_order_created_by_content" style="display:block; margin-bottom:-18px;">
            <input type="text" class="table_input_blank work_order_created_by" style="width:240px;text-align:center;">
            <hr style="width:90%;border:1px solid rgba(0, 0, 0, 0.55);margin-top:5px !important;" />
          </div>
        </div>
        <div class="row " style="text-align:center;">
          <div class="col-xs-4 col-sm-4 order_details_below order_details_label_first">
            Customer Signature or Auhorized Individual
          </div>
          <div class="col-xs-4 col-sm-4 order_details_below order_details_label_first">
            Date
          </div>
          <div class="col-xs-4 col-sm-4 order_details_below order_details_label_first">
            Work Order Created By
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-12" style="margin-bottom:50px;">
        <div class="row order_details_below_row_container_second" style="text-align:center;margin-top:25px;">
          <div class="col-xs-4 col-sm-4 fourth_hr">
            <input type="text" class="table_input_blank signature_details_second print_name" style="width:260px;text-align:center;">
            <hr style="width:90%;border:1px solid rgba(0, 0, 0, 0.55);margin-top:5px !important;" />
          </div>
          <div class="col-xs-4 col-sm-4">
            <input type="text" class="table_input_blank signature_details_second relationship_below" style="width:210px;text-align:center;text-transform:none;">
            <hr style="width:60%;border:1px solid rgba(0, 0, 0, 0.55);margin-top:5px !important;" />
          </div>
          <div class="col-xs-4 col-sm-4">
            <input type="text" class="table_input_blank signature_details_second representative_signature" style="width:240px;text-align:center;text-transform:none;">
            <hr style="width:90%;border:1px solid rgba(0, 0, 0, 0.55);margin-top:5px !important;" />
          </div>
        </div>
        <div class="row" style="text-align:center;">
          <div class="col-xs-4 col-sm-4 order_details_below order_details_label_second">
            Print Name
          </div>
          <div class="col-xs-4 col-sm-4 order_details_below order_details_label_second">
            Relationship
          </div>
          <div class="col-xs-4 col-sm-4 order_details_below order_details_label_second">
            Representative Signature
          </div>
        </div>
      </div>

    </div> <!-- End of panel-body -->
  </div>  <!-- End of panel-default -->
</div> <!-- End of wrapper-md -->

<script src="<?php echo base_url() ?>assets/js/jquery/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js') ;?>"></script>
<script src="<?php echo base_url('assets/js/jquery.form.js') ;?>"></script>

<div class="bg-light lter wrapper-md">
  <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<script type="text/javascript">

  $(document).ready(function(){
    $('.scheduled_order_date').datepicker({
      dateFormat: 'mm/dd/yy'
    });
  });

</script>






