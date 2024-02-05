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
                    success: function(response) {
                        // Parse the JSON response
                        var data = JSON.parse(response);
                        
                        $('#dispatch-work-orders-table-tbody').empty();
                    
                        // Check if there are orders to display
                        if (data && data.data && data.data.orders && data.data.orders.length > 0) {

                            $.each(data.data.orders, function(index, workOrder) {
                              
                                var providerWorkOrder = `${workOrder.workOrder}`;
                                var activityStyle = `style="letter-spacing: .3px;"`;
                                var dispatch_order_status_list = getDriverData();
                                
                                var dropdownMenu = '';

                                // If not assigned, add 'Pending' to the list of drivers
                                dropdownMenu += `
                                    <a href="javascript:void(0)" class="dropdown-item py-2 set_work_order_driver">
                                        <span class="text-info">
                                            ${(!workOrder.driverAssignedId || workOrder.driverAssignedId == '') ?
                                                `<i style="color: #17a2b8" class="far fa-user mr-2"></i>`
                                                : `<i style="opacity: 0.4; color: #000" class="far fa-user mr-2"></i>`
                                            }
                                            <span style="color: ${(!workOrder.driverAssignedId || workOrder.driverAssignedId == '') ? '#17a2b8' : '#000'}">Pending</span>
                                        </span>
                                    </a>`;
                                
                                // Append other drivers to the dropdown menu
                                dropdownMenu += dispatch_order_status_list.map(driver => {
                                    const isDriverAssigned = driver.id == workOrder.driverAssignedId;
                                    return `
                                        <a href="javascript:void(0)" class="dropdown-item py-2 set_work_order_driver" 
                                            data-driver-id="${driver.id}" 
                                            data-id="${workOrder.statusId}"
                                            data-route-name="${driver.routeName}"
                                            data-unique-id="${workOrder.uniqueId}">
                                            ${isDriverAssigned ? `<i style="color: #17a2b8" class="fas fa-check mr-2"></i>` : ''}
                                            ${isDriverAssigned ? '' : (!workOrder.driverAssignedId || workOrder.driverAssignedId == '' ? '<i style="opacity:0.4" class="fas fa-user mr-2"></i>' : '<i style="opacity:0.4" class="fas fa-user mr-2"></i>')}
                                            ${isDriverAssigned ? `<span style="color: #17a2b8">${driver.lastName}, ${driver.firstName}</span>` : `${driver.lastName}, ${driver.firstName}`}
                                        </a>`;
                                }).join('');

                                // Assign dropdown HTML
                                var assignDropdown = `<div class="dropdown">
                                    <button style="min-width: 47px;" data-toggle="dropdown" class="btn btn-sm btn-outline-info rounded-0 nowrap" aria-expanded="false">
                                        ${(!workOrder.driverAssignedId || workOrder.driverAssignedId === '') ?
                                            '<i class="far fa-user"></i> <i class="fas fa-caret-down"></i>'
                                            : `${workOrder.driverLName.toUpperCase()}${workOrder.driverFName ? `, ${workOrder.driverFName.toUpperCase()}` : ''}`
                                        }
                                    </button>
                                    <div class="dropdown-menu bg-white py-0 dropdown-menu-right">
                                        ${dropdownMenu}
                                    </div>
                                </div>`;

                                // Append a new row to the table
                                $('#dispatch-work-orders-table-tbody').append(`
                                    <tr class="bg-white">
                                        <td ${workOrder.isUrgent == 1 ? 'style="border-left: 7px solid #ffc107 !important;"' : ''}>
                                            <div class="d-flex">
                                                <div class="pt-2 h5"><i class="fas fa-box" style="opacity: 0.4;"></i></div>
                                                <div class="col px-3">
                                                    <a href="javascript:void(0)" class="lgsts_view_work_order_details" title="Click to View Order Details" style="cursor:pointer; color:#1f2d3d;" 
                                                        data-unique-id="${workOrder.uniqueId}"
                                                        data-medical-record-id="${workOrder.medicalRecordId}"
                                                        data-patient-id="${workOrder.patientId}"
                                                        data-hospice-id="${workOrder.hospiceId}"
                                                        data-original-activity-type-id="${workOrder.originalActivityTypeId}">
                                                        <strong>${providerWorkOrder}</strong>
                                                    </a>
                                                    <div class="text-uppercase">${workOrder.providerName}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>${workOrder.customerLastName}${workOrder.driverAssignedId == '' ? '' : ','} ${workOrder.customerFirstName}</strong>
                                            <div>${workOrder.streetAddress}, ${workOrder.placenumAddress}, ${workOrder.cityAddress}, ${workOrder.stateAddress}, ${workOrder.postalAddress}</div>
                                        </td>
                                        <td class="text-center">
                                            <strong ${activityStyle} class=" px-3 py-1 rounded text-sm text-uppercase text-info bg-gray-light">
                                                ${workOrder.activityType}
                                            </strong>
                                        </td>
                                        <td class="text-right">
                                            ${assignDropdown}
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <button style="min-width: 47px;" data-toggle="dropdown" class="btn btn-sm btn-outline-info rounded-0">
                                                    <i class="fas fa-ellipsis-v"></i> &nbsp;<i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu bg-white py-0 dropdown-menu-right">
                                                    <a href="javascript:void(0)" class="dropdown-item py-2 text-warning set_work_order_urgency"
                                                        data-id="${workOrder.statusId}" 
                                                        data-unique-id="${workOrder.uniqueId}"
                                                        data-isUrgent="${workOrder.isUrgent}">
                                                        <span class="text-warning">
                                                            <i style="opacity:0.4;" class="fas fa-exclamation-triangle mr-2"></i> URGENCY
                                                        </span>
                                                    </a>
                                                    ${workOrder.driverAssignedId != '' ?
                                                        `<a href="javascript:void(0)" class="dropdown-item py-2 text-info set_work_order_stop_number" data-stop-number="${workOrder.stopNumber}" data-id="${workOrder.statusId}">
                                                            <span class="text-info">
                                                                <i style="opacity:0.4;" class="fas fa-hashtag mr-2"></i>${workOrder.stopNumber} STOP NUMBER
                                                            </span>
                                                        </a>` : ''}
                                                    <a href="javascript:void(0)" class="dropdown-item py-2 send_work_order_to_cos" data-id="${workOrder.statusId}">
                                                        <span class="text-info">
                                                            <i style="opacity:0.4;" class="fas fa-info-circle mr-2"></i> SEND TO COS
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                `);
                            });
                            
                            function getDriverData() {
                                return [
                                    { id: 5, firstName: "Ernest", lastName: "Camello", userType: "driver", routeName: "Demo1" },
                                    { id: 6, firstName: "Rhys", lastName: "Barriga", userType: "screener_and_driver", routeName: null },
                                    { id: 75, firstName: "Khianna", lastName: "Buendia", userType: "driver", routeName: null },
                                    { id: 76, firstName: "Aamon", lastName: "Seares", userType: "driver", routeName: null },
                                    { id: 77, firstName: "Alyanna", lastName: "Llamas", userType: "driver", routeName: "TEST" },
                                    { id: 78, firstName: "Rory1", lastName: "Chin", userType: "driver", routeName: "Test%204" },
                                    { id: 83, firstName: "Amaya", lastName: "Ronda", userType: "driver", routeName: null },
                                    { id: 84, firstName: "Dyson", lastName: "Astor", userType: "driver", routeName: null }
                                ];
                            }

                        } else {
                            // Display a message if there are no orders
                            $('#dispatch-work-orders-table-tbody').html("<tr class='bg-white'> <td colspan='5' class='text-center'> No work orders. </td></tr>");
                        }
                    
                        // Enable the search button
                        $('#search-dispatch-work-orders-button').removeAttr('disabled');
                    },
                    
                    error:function(_jqXHR, textStatus, errorThrown)
                    {
                        console.log(textStatus, errorThrown);
                    }
                });
                
            } else {
                $('body').find('#search-dispatch-work-orders-button').removeAttr('disabled');
            }
        }
    });

    $('#dispatch-work-orders-table-tbody').on('click','.set_work_order_urgency', function(){
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

    $('#dispatch-work-orders-table-tbody').on('click','.send_work_order_to_cos',function(){
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

    $('#dispatch-work-orders-table-tbody').on('.set_work_order_stop_number',function(){
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
    
    $('#dispatch-work-orders-table-tbody').on('click','.set_work_order_driver',function (){
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
