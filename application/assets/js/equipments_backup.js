$(document).ready(function(){

	$('#cat_select').bind('change',function(){
		$(".hdn_cat_name").val(this.options[this.selectedIndex].text);
	});

  // $('.category_dropdown').bind('change',function(){
  //   if($(this).val() == 3)
  //   {
      
  //   }
  //   else
  //   {
      
  //   }
  //   alert($(this).val());
  // });


	$('#equip_name').bind('keyup',function(){
		$('.hdn_key_name').val($(this).val().toLowerCase().replace(' ','_').replace(' ','_').replace(' ','_').replace(' ','_').replace(' ','_').replace(' ','_').replace(' ','_'));
	});

	//for the saving of new equipments
    $('.btn-add-equipment').bind('click',function(){
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



$('.datatable_table').on('click','.comments_link', function(){
        var id = $(this).attr('data-id');
        modalbox(base_url + 'order/get_comments/' + id,{
            header:"Status Notes",
            button: false
            // buttons: [
            // {
            //       text: "Close",
            //       type: "danger",
            //       click: function() {
            //           closeModalbox();
            //       }
            //   }
            // ]
        });
    });

$('.comments_link').on('click', function(){
        var id = $(this).attr('data-id');
        modalbox(base_url + 'order/get_comments/' + id,{
            header:"Status Notes",
            button: false
            // buttons: [
            // {
            //       text: "Close",
            //       type: "danger",
            //       click: function() {
            //           closeModalbox();
            //       }
            //   }
            // ]
        });
    });


$('.notes_help').on('click', function(){
        var equipment_id = $(this).attr('data-id');
        var unique_id = $(this).attr('data-value');
        var equipment_name = $(this).attr('data-ename');

        modalbox(base_url + 'order/get_lot_comments/' + equipment_id + "/" + unique_id,{
            header:equipment_name + " Lot Number",
            button: false
            // buttons: [
            // {
            //       text: "Close",
            //       type: "danger",
            //       click: function() {
            //           closeModalbox();
            //       }
            //   }
            // ]
        });
    });

$('.edit_patient_profile').on('click', function(){
        var medical_record_id = $(this).attr('data-id');
        var hospice_id = $(this).attr('data-organization-id');

        modalbox(base_url + 'order/edit_patient_info/' + medical_record_id+"/"+hospice_id,{
            header:"Edit Patient Profile",
            button: false,
            // buttons: 
            // [{
            //       text: "Close",
            //       type: "danger",
            //       click: function() {
            //           closeModalbox();
            //           location.reload();
            //       }
            // }]
        });
    });

$('#add_patient_notes').on('click', function(){
        var medical_record_id = $(this).attr('data-id');
        var p_fname = $(this).attr('data-fname');
        var p_lname = $(this).attr('data-lname');
        var hospice_name = $(this).attr('data-hospice');
        $('#globalModal').addClass("note_modal");
        modalbox(base_url + 'order/show_patient_notes/' + medical_record_id + "/" + p_fname + "/" + p_lname + "/" + hospice_name,{
            header:"Patient Notes",
            button: false,
            // buttons: 
            // [{
            //       text: "Close",
            //       type: "danger",
            //       click: function() {
            //           closeModalbox();
            //       }
            // }]
        });
    });


//for the adding of patient weight for scale chair and scale hoyer
$('.add_patient_weight').bind('click',function(response){
  var equipment_id = $(this).attr('data-id');
  var unique_id = $(this).attr('data-uniqueid');
  var medical_id    = $(this).attr('data-medical-id');

  if(equipment_id == 181 || equipment_id == 182)
  {
    $.ajax({
      url:base_url+"equipment/view_add_patient_weight/"+medical_id+"/"+unique_id+"/"+equipment_id,
      type:"POST",
      success:function(response)
      {
        $('#patient_weight').modal("show");
        $('#patient_weight').find(".modal-body").html(response);
      }

    });
  }
});


//for the adding of patient weight for scale chair and scale hoyer
$('.add_lot_number').bind('click',function(response){
  var equipment_id = $(this).attr('data-id');
  var unique_id = $(this).attr('data-uniqueid');
  var medical_id    = $(this).attr('data-medical-id');

  if(equipment_id == 11 || equipment_id == 170)
  {
    $.ajax({
      url:base_url+"equipment/view_add_lot_number/"+medical_id+"/"+unique_id+"/"+equipment_id,
      type:"POST",
      success:function(response)
      {
        $('#lot_numbers').modal("show");
        $('#lot_numbers').find(".modal-body").html(response);
      }
    });
  }
});




$('.datatable_table').on('click', '.view_order_details' ,function(){
        var medical_record_id = $(this).attr('data-id');
        var hospice_id        = $(this).attr('data-value');
        var unique_id         = $(this).attr('data-unique-id');
        var act_id            = $(this).attr('data-act-id');

      if(act_id != 3)
      {
        modalbox(base_url + 'order/view_order_status_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id,{
            header:"Order Details",
            button: true,
            buttons: 
            [{
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
         modalbox(base_url + 'order/view_exchange_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id,{
            header:"Order Details",
            button: true,
            buttons: 
            [{
                  text: "Close",
                  type: "danger",
                  click: function() {
                      closeModalbox();
                  }
            }]
        });
      }

    });


$('.view_order_information').on('click',function(){
        var medical_record_id = $(this).attr('data-id');
        var hospice_id        = $(this).attr('data-value');
        var unique_id         = $(this).attr('data-unique-id');
        var act_id            = $(this).attr('data-act-id');
        var equip_id          = $(this).attr('data-equip-id');

      if(act_id != 3)
      {    
        modalbox(base_url + 'order/view_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id + "/" + equip_id,{
          header:"Order Details",
          button: true,
          buttons: 
          [{
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
        modalbox(base_url + 'order/view_exchange_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id + "/" + equip_id,{
            header:"Order Details",
            button: true,
            buttons: 
            [{
              text: "Close",
              type: "danger",
              click: function() {
                  closeModalbox();
              }
            }]
        });

        }
    });

$('.view_original_order_information').on('click',function(){
    var medical_record_id = $(this).attr('data-id');
    var hospice_id        = $(this).attr('data-value');
    var unique_id         = $(this).attr('data-unique-id');
    var act_id            = $(this).attr('data-act-id');
    var equip_id          = $(this).attr('data-equip-id');
    var initial_order     = $(this).attr('data-initial-order');

    if(act_id != 3)
    {    
        modalbox(base_url + 'order/view_initial_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id + "/" + equip_id,{
          header:"Order Details",
          button: true,
          buttons: 
          [{
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
       modalbox(base_url + 'order/view_exchange_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id + "/" + equip_id,{
          header:"Order Details",
          button: true,
          buttons: 
          [{
            text: "Close",
            type: "danger",
            click: function() {
                closeModalbox();
            }
          }]
        });
    } 

        // modalbox(base_url + 'order/view_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id + "/" + equip_id,{
        //   header:"Order Details",
        //   button: true,
        //   buttons: 
        //   [{
        //     text: "Close",
        //     type: "danger",
        //     click: function() {
        //         closeModalbox();
        //     }
        //   }]
        // });

});

// $('.checkbox_modal').on('click',function(){
//   var item_name = $(this).attr("data-name");
//   var item_description = $(this).attr("data-desc");
//   var equipment_id = $(this).val();


//   if($(".checkbox_modal").is(":checked"))
//   {
//       modalbox(base_url + 'equipment/view_disposable_modal/' + item_name + "/" + equipment_id,{
//             header:item_description,
//             button: false,
//       });
//   }
//   else
//   {
//     $(this).prop('checked',false);
//   }

// });

// $('.close').click(function(){
//   $('.checkbox_modal').prop('checked',false);
// });



}); //end of ready function


