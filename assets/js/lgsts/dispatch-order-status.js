$(document).ready(function(){
    $('#search-dispatch-work-orders').bind('keyup',function(){
        $(this).css("text-transform","uppercase");

        if($(this).val() == '')
        {
            $(this).css("text-transform","none");
        }
    });

    var globalTimeout = null;
    $('#search-dispatch-work-orders').bind('keyup',function(){
        var searchString = $(this).val();

        if(globalTimeout != null) clearTimeout(globalTimeout);
        globalTimeout =setTimeout(getInfoFunc,1100);

        function getInfoFunc(){
            globalTimeout = null;
            if(searchString.length >= 3){
                $('body').find('#search-dispatch-work-orders-button').attr("disabled","disabled");
                $('body').find('#dispatch-work-orders-table').find('#dispatch-work-orders-table-tbody').html("<tr class='bg-white'> <td colspan='5' class='text-center'> Retrieving Data... <i class='fa fa-spin fa-spinner'></i> </td></tr>");
                
                $.ajax({
                    type:"POST",
                    url:base_url+'lgsts_dispatch_order_status/search_dispatch_work_orders/?searchString='+searchString,
                    success:function(response)
                    {
                        console.log('============', response);
                        // $('#suggestion_container').html(response);
                        // $('body').find('.search-patients-button').removeAttr('disabled');

                        // $(".patient_results").bind('click', function(){
                        //     var medical_record_num = $(this).attr('data-value');
                        //     var id = $(this).attr('data-id');
                        //     var p_fname = $(this).attr('data-fname');
                        //     var p_lname = $(this).attr('data-lname');
                        //     var patient_record = $("#search-patients");

                        //     $('#pfname').val(p_fname.toLowerCase());
                        //     $('#plname').val(p_lname.toLowerCase());
                        //     $('#medicalid').val(id.toLowerCase());
                        //     $('#suggestion_container').hide();
                        //     contact_name = patient_record.val(medical_record_num + " - " + p_fname + " " + p_lname);
                        //     $('#search_form').submit();

                        // });
                        // $('.result-lists').bind('click',function(){
                        //     $('#search_form').submit();
                        // });

                    },
                    error:function(jqXHR, textStatus, errorThrown)
                    {
                        console.log(textStatus, errorThrown);
                    }
                });
                
            } else {
                $('body').find('#search-dispatch-work-orders-button').removeAttr('disabled');
            }
        }
    });

    $('.set_work_order_urgency').bind('click',function(){
        var status_id = $(this).attr("data-id");
        var unique_id = $(this).attr("data-unique-id");
        var is_urgent = $(this).attr("data-isUrgent");
        console.log('status_id', status_id);
        console.log('unique_id', unique_id);
        console.log('is_urgent', is_urgent);
    
        jConfirm('Are you sure you want to set urgency for this work order?', 'Reminder', function(response){
            if(response)
            {              
                $.post(base_url + 'lgsts_dispatch_order_status/update_work_order_urgency/' + status_id + "/" + unique_id + "/" + is_urgent, function(response){
                    console.log('response', response);
                    var obj = $.parseJSON(response);
                    if(obj['error'] == 0)
                    {
                        jAlert(obj['message'], 'Success');
                        setTimeout(function(){
                            location.reload();
                        },800);
                    } else {
                        jAlert(obj['message'], 'Error');
                    }
                });
            }
        });
    });

    $('.send_work_order_to_cos').bind('click',function(){
        var status_id = $(this).attr("data-id");
    
        jConfirm('Are you sure you want to send work order back to COS?', 'Reminder', function(response){
            if(response)
            {              
                $.post(base_url + 'lgsts_dispatch_order_status/send_work_order_to_COS/' + status_id, function(response){
                    var obj = $.parseJSON(response);
                    if(obj['error'] == 0)
                    {
                        jAlert(obj['message'], 'Success');
                        setTimeout(function(){
                            location.reload();
                        },800);
                    } else {
                        jAlert(obj['message'], 'Error');
                    }
                }); 
            }
        });
    });

    $('.set_work_order_stop_number').bind('click',function(){
        var status_id = $(this).attr("data-id");
        var existing_stop_number = $(this).attr("data-stop-number");
        
        $('#work_order_stop_number').find(".modal-body").find('#wo_stop_number').val('');
        if (existing_stop_number != '') {
            $('#work_order_stop_number').find(".modal-body").find('#wo_stop_number').val(existing_stop_number);
        }
        $('#work_order_stop_number').modal('show');

        $('#btn_save_work_order_stop_number').bind('click',function(){
            var wo_stop_number = $(this).parent('.modal-footer').siblings(".modal-body").find('#wo_stop_number').val();

            if (wo_stop_number !='' && wo_stop_number >= 0) {
                $.post(base_url + 'lgsts_dispatch_order_status/set_work_order_stop_number/' + status_id + "/" + wo_stop_number, function(response){
                    console.log('response', response);
                    var obj = $.parseJSON(response);
                    if(obj['error'] == 0)
                    {
                        jAlert(obj['message'], 'Success');
                        setTimeout(function(){
                            location.reload();
                        },800);
                    } else {
                        jAlert(obj['message'], 'Error');
                    }
                });
            } else {
                jAlert("Stop Number required.", 'Error');
            }
        });
    });

    $('#btn_close_work_order_stop_number').bind('click',function(){
        $('#work_order_stop_number').modal('hide');
    });
    
    $('.set_work_order_driver').bind('click',function(){
        var status_id = $(this).attr("data-id");
        var driver_id = $(this).attr("data-driver-id");
        var unique_id = $(this).attr("data-unique-id");
        var routeName = $(this).attr("data-route-name");

        if (routeName == '' || routeName == null || routeName.length == 0) {
            $('#add_route_name_modal').modal('show');

            $('#btn_save_add_route_name_modal').bind('click',function(){
                var added_route_name = $(this).parent('.modal-footer').siblings(".modal-body").find('#add_route_name').val();
    
                if (added_route_name !='' && added_route_name.length >= 0) {

                    $.post(base_url + 'lgsts_dispatch_order_status/assign_work_order_to_driver/' + status_id + "/" + unique_id + "/" + driver_id + "/" + routeName, function(response){
                        var obj = $.parseJSON(response);
                        console.log('obj=====', obj);
                        if(obj['error'] == 0)
                        {
                            $.post(base_url + 'lgsts_daily_routes/submit_route_name_autosave/' + obj['data']['affectedRows'][0]['driverTripId'] + "/" + added_route_name, function(response){
                                console.log('response', response);
                                var obj = $.parseJSON(response);
                                if(obj['error'] == 0)
                                {
                                    jAlert('Route name added and work order successfully assigned.', 'Success');
                                    setTimeout(function(){
                                        location.reload();
                                    },800);
                                } else {
                                    jAlert(obj['message'], 'Error');
                                }
                            });
                        } else {
                            var error_message = JSON.stringify(obj['errors']);
                            jAlert(error_message, 'Error');
                        }
                    });
                } else {
                    jAlert("Route name required.", 'Error');
                }
            });
        } else {
            jConfirm('Are you sure you want to assign work order to this driver?', 'Reminder', function(response){
                if(response)
                {              
                    $.post(base_url + 'lgsts_dispatch_order_status/assign_work_order_to_driver/' + status_id + "/" + unique_id + "/" + driver_id + "/" + routeName, function(response){
                        var obj = $.parseJSON(response);
                        if(obj['error'] == 0)
                        {
                            jAlert(obj['message'], 'Success');
                            setTimeout(function(){
                                location.reload();
                            },800);
                        } else {
                            var error_message = JSON.stringify(obj['errors']);
                            jAlert(error_message, 'Error');
                        }
                    }); 
                }
            });
        }
    });

    $('#btn_close_add_route_name_modal').bind('click',function(){
        $('#add_route_name_modal').modal('hide');
    });

    $('body').on('click','.lgsts_view_work_order_details',function(){
        var medical_record_id = $(this).attr('data-medical-record-id');
        var hospice_id        = $(this).attr('data-hospice-id');
        var unique_id         = $(this).attr('data-unique-id');
        var act_id            = $(this).attr('data-original-activity-type-id');
        var patient_id        = $(this).attr('data-patient-id');
      
        if(act_id != 3)
        {
          modalbox(base_url + 'lgsts_dispatch_order_status/view_logistics_work_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id + "/" + patient_id,{
            header:"Work Order",
            button: true,
            buttons:
            [
            // {
            //   text: "<i class='fa fa-print'></i> Print",
            //   type: "default pull-left print_page_details",
            //   click: function() {
            //     window.open(base_url+'order/print_order_details/'+medical_record_id+'/'+hospice_id+'/'+unique_id+'/'+act_id+'/'+patient_id);
            //   }
            // },
            {
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
});
