$(document).ready(function(){

  $('#cat_select').bind('change',function(){
    $(".hdn_cat_name").val(this.options[this.selectedIndex].text);
  });

  $('#equip_name').bind('keyup',function(){
    $('.hdn_key_name').val($(this).val().toLowerCase().replace(' ','_').replace(' ','_').replace(' ','_').replace(' ','_').replace(' ','_').replace(' ','_').replace(' ','_'));
  });

  $('body').on('click','.datatable_table_equipment .assign_to_capped',function(){
    var equipmentID = $(this).attr('data-id');
    var hospice_id = $(this).attr('data-hospice');
    var category_id = $(this).attr('data-category');
    if($(this).is(":checked"))
    {
      $.post(base_url + 'equipment/assign_to_capped/'+equipmentID+'/'+hospice_id+'/'+category_id+'/','', function(response){
        var obj = $.parseJSON(response);
        if(obj['error'] == 0)
        {
          var capped_counter = $("#capped_counter_span").text();
          var new_count = Number(capped_counter)+1;
          $("#capped_counter_span").html(new_count);
        }
      });
    }
    else
    {
      $.post(base_url + 'equipment/remove_from_capped/'+equipmentID+'/'+hospice_id+'/','', function(response){
        var obj = $.parseJSON(response);
        if(obj['error'] == 0)
        {
          var capped_counter = $("#capped_counter_span").text();
          var new_count = Number(capped_counter-1);
          $("#capped_counter_span").html(new_count);
        }
      });
    }
  });

  $('body').on('click','.datatable_table_equipment .hide_item',function(){
    var equipmentID = $(this).attr('data-id');
    var hospice_id = $(this).attr('data-hospice');
    var category_id = $(this).attr('data-category');

    $.post(base_url + 'equipment/hide_item/'+equipmentID+'/'+hospice_id+'/','', function(response){
      var obj = $.parseJSON(response);
      if(obj['error'] == 0)
      {
        var temp = '#equipment_tr_'+equipmentID;
        var another_temp = '#button_col_'+equipmentID;
        var checkbox_temp = '#checkbox_col_'+equipmentID;
        setTimeout(function(){
          var replace = "<button type='button' class='btn btn-primary btn-xs show_item' style='height:25px;' data-hospice='"+hospice_id+"' data-category='"+category_id+"' data-id='"+equipmentID+"'> <i class='glyphicon glyphicon-eye-open'></i>&nbsp;&nbsp; Show Item </button>";
          $(".datatable_table_equipment").find("#assign_equipment_tbody").find(temp).find(another_temp).html(replace);
          $(".datatable_table_equipment").find("#assign_equipment_tbody").find(temp).css('background-color','rgba(138, 124, 124, 0.13)');
          $(".datatable_table_equipment").find("#assign_equipment_tbody").find(temp).find(checkbox_temp).find(".assign_to_capped").attr('disabled','disabled');
          $(".datatable_table_equipment").find("#assign_equipment_tbody").find(temp).find(checkbox_temp).find(".assign_to_capped").attr('checked',false);
        },300);

        if(category_id == 2)
        {
          var non_capped_counter = $("#non_capped_counter_span").text();
          var new_count = Number(non_capped_counter-1);
          $("#non_capped_counter_span").html(new_count);
        }
        else
        {
          var disposable_counter = $("#disposable_counter_span").text();
          var new_count = Number(disposable_counter-1);
          $("#disposable_counter_span").html(new_count);
        }

        if(obj['response_check_capped_assigned'] == 1)
        {
          var capped_counter = $("#capped_counter_span").text();
          var new_count = Number(capped_counter-1);
          $("#capped_counter_span").html(new_count);
        }
        var hidden_item_counter = $("#hidden_items_counter_span").text();
        var new_count = Number(hidden_item_counter)+1;
        $("#hidden_items_counter_span").html(new_count);
      }
    });
  });

  $('body').on('click','.datatable_table_equipment .show_item',function(){
    var equipmentID = $(this).attr('data-id');
    var hospice_id = $(this).attr('data-hospice');
    var category_id = $(this).attr('data-category');

    $.post(base_url + 'equipment/show_item/'+equipmentID+'/'+hospice_id+'/','', function(response){
      var obj = $.parseJSON(response);
      if(obj['error'] == 0)
      {
        var temp = '#equipment_tr_'+equipmentID;
        var another_temp = '#button_col_'+equipmentID;
        var checkbox_temp = '#checkbox_col_'+equipmentID;
        setTimeout(function(){
          var replace = "<button type='button' class='btn btn-info btn-xs hide_item' style='height:25px;' data-hospice='"+hospice_id+"' data-category='"+category_id+"' data-id='"+equipmentID+"'> <i class='glyphicon glyphicon-eye-close'></i>&nbsp;&nbsp; Hide Item </button>";
          $(".datatable_table_equipment").find("#assign_equipment_tbody").find(temp).find(another_temp).html(replace);
          $(".datatable_table_equipment").find("#assign_equipment_tbody").find(temp).css('background-color','#fff');
          $(".datatable_table_equipment").find("#assign_equipment_tbody").find(temp).find(checkbox_temp).find(".assign_to_capped").removeAttr('disabled');
        },300);

        if(category_id == 2)
        {
          var non_capped_counter = $("#non_capped_counter_span").text();
          var new_count = Number(non_capped_counter)+1;
          $("#non_capped_counter_span").html(new_count);
          console.log(non_capped_counter);
          console.log(new_count);
        }
        else
        {
          var disposable_counter = $("#disposable_counter_span").text();
          var new_count = Number(disposable_counter)+1;
          $("#disposable_counter_span").html(new_count);
        }
        var hidden_item_counter = $("#hidden_items_counter_span").text();
        var new_count = Number(hidden_item_counter-1);
        $("#hidden_items_counter_span").html(new_count);
      }
    });
  });

  //for the saving of new equipments
  $('body').on('click','.btn-add-equipment',function(){
    var form_data = $('#add-equip-form').serialize();

    var this_element = $(this);

    jConfirm('Do you want to save changes now?', 'Reminder', function(response){
      if(response)
      {
        $.post(base_url + 'equipment/add_equipment/', form_data, function(response){
          var obj = $.parseJSON(response);
          jAlert(obj['message'],'Reminder');
          if(obj['error'] == 0)
          {
            setTimeout(function(){
              location.reload();
            },1000);
          }
        });
      }
    });
  });

$('body').on('click','.datatable_table .set_date_reschedule_onhold',function(){
  var id = $(this).attr('data-id');
  var reschedule_onhold_date_container = $(this).siblings(".resceduled_onhold_date_container");
  modalbox(base_url + 'order/set_date_reschedule_onhold/' + id,{
    header:"Set Date",
    button: false
  });

  $('body').on('click','.save-changes-date-btn',function(){
    var form_data = $('#reschedule_onhold_date_form').serialize();
    var unique_id = $(this).attr('data-id');

    $.post(base_url + 'order/change_reschreschedule_onhold_date/' + unique_id,form_data, function(response){
      var obj = $.parseJSON(response);
      setTimeout(function(){
        closeModalbox();
        if(obj['error'] == 0)
        {
          var date = new Date(obj['date_returned']);
          var day = date.getDate();
          var month_here = date.getMonth();
          var year = date.getFullYear();
          month_here = Number(month_here+1);
          var date_returned = month_here+"/"+day+"/"+year;
          reschedule_onhold_date_container.html(date_returned);

          jAlert("Date Successfully Changed","Update Response");
          $("body").find(".modal-dialog").find("#popup_panel").css("display","none");
          $("body").find(".modal-dialog").find("#popup_message").css("height","65px");
          setTimeout(function(){
            $("#popup_container").remove();
            $.alerts._overlay('hide');
            $.alerts._maintainPosition(false);
          },1000);
        }
      },500);
    });

  });
});

$('body').on('click','.comments_link',function(){
  var id = $(this).attr('data-id');
  var page_type = $(this).attr('data-page-type');
  modalbox(base_url + 'order/get_comments/' + id + '/' + page_type,{
    header:"Status Notes",
    button: false
  });
  console.log('comments link', page_type);
});

$('body').on('click','.datatable_table .comments_link',function(){
  var id = $(this).attr('data-id');
  modalbox(base_url + 'order/get_comments/' + id,{
    header:"Status Notes",
    button: false
  });
  console.log('comments datatable_table', page_type);
});

$('body').on('click','.notes_help',function(){
  var equipment_id = $(this).attr('data-id');
  var unique_id = $(this).attr('data-value');
  var equipment_name = $(this).attr('data-ename');

  modalbox(base_url + 'order/get_lot_comments/' + equipment_id + "/" + unique_id,{
    header:equipment_name + " Lot Number",
    button: false
  });
});

$('body').on('click','.edit_patient_profile',function(){
  var medical_record_id = $(this).attr('data-id');
  var hospice_id = $(this).attr('data-organization-id');

  modalbox(base_url + 'order/edit_patient_info/' + medical_record_id+"/"+hospice_id,{
    header:"Edit Customer Profile",
    button: false,
  });
});

$('body').on('click','#add_patient_notes',function(){
  var medical_record_id = $(this).attr('data-id');
  var p_fname = $(this).attr('data-fname');
  var p_lname = $(this).attr('data-lname');
  var hospice_name = $(this).attr('data-hospice');
  var patient_id = $(this).attr('data-patient-id');

  $('#globalModal').addClass("note_modal");
  modalbox_post(base_url + 'order/show_patient_notes_new',{medical_record_id:medical_record_id,p_fname:p_fname,p_lname:p_lname,hospice_name:hospice_name,patient_id:patient_id},{
    header:"Customer Notes",
    button: false,
  });
});

//for the adding of patient weight for scale chair and scale hoyer
$('body').on('click','.add_patient_weight',function(){
  var equipment_id = $(this).attr('data-id');
  var unique_id = $(this).attr('data-uniqueid');
  var medical_id    = $(this).attr('data-medical-id');
  var patient_id = $(this).attr('data-patient-id');

  if(equipment_id == 181 || equipment_id == 182)
  {
    $('#patient_weight').modal("show");
    $('#patient_weight').find(".modal-body").html("<h4 class='text-center'><i class='fa fa-spin fa-spinner'></i></h4>");
    $.ajax({
      url:base_url+"equipment/view_add_patient_weight/"+medical_id+"/"+unique_id+"/"+equipment_id+"/"+patient_id,
      type:"POST",
      success:function(response)
      {
        $('#patient_weight').find(".modal-body").html(response);
      }

    });
  }
});

//for the adding of patient weight for scale chair and scale hoyer
$('body').on('click','.add_lot_number',function(){
  var equipment_id = $(this).attr('data-id');
  var unique_id = $(this).attr('data-uniqueid');
  var medical_id    = $(this).attr('data-medical-id');
  var patient_id = $(this).attr('data-patient-id');
  if(equipment_id == 11 || equipment_id == 170)
  {
    $('#lot_numbers').modal("show");
    $('#lot_numbers').find(".modal-body").html("<h4 class='text-center'><i class='fa fa-spin fa-spinner'></i></h4>");
    $.ajax({
      url:base_url+"equipment/view_add_lot_number/"+medical_id+"/"+unique_id+"/"+equipment_id+"/"+patient_id,
      type:"POST",
      success:function(response)
      {
        $('#lot_numbers').find(".modal-body").html(response);
      }
    });
  }
});

$('body').on('click','.datatable_table .view_order_details',function(){
  var medical_record_id = $(this).attr('data-id');
  var hospice_id        = $(this).attr('data-value');
  var unique_id         = $(this).attr('data-unique-id');
  var act_id            = $(this).attr('data-act-id');
  var patient_id        = $(this).attr('data-patient-id');

  if(act_id != 3)
  {
    modalbox(base_url + 'order/view_order_status_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id+ "/" + patient_id,{
        header:"Work Order",
        button: false,
    });
  }
  else
  {
    modalbox(base_url + 'order/view_exchange_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id+ "/" + patient_id,{
      header:"Work Order",
      button: false
    });
  }
  return false;
});

$('body').on('click','.view_order_information',function(){
  var medical_record_id       = $(this).attr('data-id');
  var hospice_id              = $(this).attr('data-value');
  var unique_id               = $(this).attr('data-unique-id');
  var act_id                  = $(this).attr('data-act-id');
  var equip_id                = $(this).attr('data-equip-id');
  var patient_id              = $(this).attr('data-patient-id');
  var activity_reference_id   = $(this).attr('data-activity-reference-id');

  if(act_id != 3)
  {
    modalbox(base_url + 'order/view_pickup_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id + "/" + equip_id+ "/" + patient_id+ "/" + activity_reference_id,{
      header:"Work Order",
      button: true,
      buttons:
      [{
        text: "<i class='fa fa-print'></i> Print",
        type: "default pull-left print_page_details",
        click: function() {
          window.open(base_url+'order/pickup_print_order_details/'+medical_record_id+'/'+hospice_id+'/'+unique_id+'/'+act_id+'/'+patient_id+'/'+ activity_reference_id);
        }
      },{
        text: "Close",
        type: "danger",
        click: function() {
            closeModalbox();
        }
      }]
    });
  }
  else
  {
    modalbox(base_url + 'order/view_exchange_pickup_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id+ "/" + patient_id,{
        header:"Work Order",
        button: true,
        buttons:
        [{
          text: "<i class='fa fa-print'></i> Print",
          type: "default pull-left print_page_details",
          click: function() {
            window.open(base_url+'order/pickup_print_order_details/'+medical_record_id+'/'+hospice_id+'/'+unique_id+'/'+act_id+'/'+patient_id+'/'+ activity_reference_id);
          }
        },{
          text: "Close",
          type: "danger",
          click: function() {
              closeModalbox();
          }
        }]
    });

  }
  return false;
});

$('body').on('click','.view_original_order_information',function(){
  var medical_record_id = $(this).attr('data-id');
  var hospice_id        = $(this).attr('data-value');
  var unique_id         = $(this).attr('data-unique-id');
  var act_id            = $(this).attr('data-act-id');
  var equip_id          = $(this).attr('data-equip-id');
  var initial_order     = $(this).attr('data-initial-order');
  var patient_id        = $(this).attr('data-patient-id');

  if(act_id != 3)
  {
    modalbox(base_url + 'order/view_initial_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id + "/" + equip_id+ "/" + patient_id,{
      header:"Work Order",
      button: true,
      buttons:
      [{
        text: "<i class='fa fa-print'></i> Print",
        type: "default pull-left print_page_details",
        click: function() {
          window.open(base_url+'order/print_order_details/'+medical_record_id+'/'+hospice_id+'/'+unique_id+'/'+act_id+'/'+patient_id);
        }
      },{
        text: "Close",
        type: "danger",
        click: function() {
            closeModalbox();
        }
      }]
    });
  }
  else
  {
    modalbox(base_url + 'order/view_exchange_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id + "/" + patient_id,{
      header:"Work Order",
      button: false
    });
  }

  return false;
});

  // $('body .hover_item_photo').each(function(){
  //   console.log("JEYE");
  //   $($(this)).popover({
  //     container: 'body',
  //     html: true,
  //     placement: 'auto',
  //     trigger: 'hover',
  //     content: function() {
  //       console.log('SSSSSS',$(this).attr('data-img'));
  //       // get the url for the full size img
  //       return '<img src="'+$(this).data('img')+'" />';
  //     }
  //   });
  // });

  // $('a[rel=popover]').popover({
  // // $('body .hover_item_photo').popover({
  //   html: true,
  //   trigger: 'hover',
  //   content: function () {
  //     return '<img src="'+$(this).attr('data-img') + '" />';
  //   }
  // });

  // var image = 'https://smarterchoice.us/assets/img/shopping_cart.png';
  // $('#popover').popover({placement: 'bottom', content: image, html: true});



}); //end of ready function


