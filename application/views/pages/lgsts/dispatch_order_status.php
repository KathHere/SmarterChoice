<style>
    .custom-pagination-btn {
    background-color: white;
    border-radius: 0; /* Remove border radius */
    border: -2px solid #ccc; /* Add border */
    color: #428bca;
    }
    .custom-pagination-btn:hover {
        background-color: #eaedee;
        color:#428bca;
    }
    .dataTables_paginate {
        display: flex;
        justify-content: flex-end;
    }
    .pagination > .active > .custom-pagination-btn {
    z-index: 2;
    color: #fff;
    cursor: default;
    background-color: #428bca;
    border-color: #428bca;
    }

    .paginate_button.disabled{
        background-color: #fff;
        color:#777 !important;
        cursor: not-allowed !important;
        
    }
    .container {
    max-width: 5000px; /* Set a maximum width for the container */
    margin: 0 auto; /* Center the container horizontally */ 
    }
</style>
     <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"> 
    
        <!-- Main content -->
        <div class="content">  <!-- Content Header (Page header) -->
        <div class="content-header px-0 pt-0">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12 pt-3 text-left">
                <div class="d-flex">
                    <div class="h3">
                    <i class="fas fa-box" style="opacity: 0.4;"></i>
                    </div>
                    <div class="col px-4">
                    <h3 class="m-0">Dispatch Order Status</h3>
                    </div>
                </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div> 
        
        <!-- /.content-header -->
        <div class="container-fluid">
            <div class="row">
            <div class="col-4">
                <div class="card rounded-0 bg-white">
                    <div class="card-body py-2">
                    <div class="d-flex align-items-center">
                        <div class="h4"><i class="fas fa-box text-warning"></i></div>
                        <div class="px-3">
                        <strong class="d-block h3">
                            <?php 
                                if (!empty($dispatch_order_status_info['order_counters'])) {
                                    echo $dispatch_order_status_info['order_counters']->pendingOrders;
                                }
                            ?>
                        </strong>
                        </div>
                    </div>
                    <label class="mb-0 font-weight-lighter d-block"><span class="text-sm">PENDING ORDER(S)</span> </label>
                    </div>
                </div>
                </div>
                <div class="col-4">
                <div class="card rounded-0 bg-white">
                    <div class="card-body py-2">
                    <div class="d-flex align-items-center">
                        <div class="h4"><i class="fas fa-box text-info  "></i></div>
                        <div class="px-3">
                        <strong class="d-block h3">
                            <?php 
                                if (!empty($dispatch_order_status_info['order_counters'])) {
                                    echo $dispatch_order_status_info['order_counters']->openOrders;
                                }
                            ?>
                        </strong>
                        </div>
                    </div>
                    <label class="mb-0 font-weight-lighter d-block"><span class="text-sm">OPEN ORDER(S)</span></label>
                    </div>
                </div>
                </div>
                <div class="col-4">
                <div class="card rounded-0 bg-white">
                    <div class="card-body py-2">
                    <div class="d-flex align-items-center">
                        <div class="h4"><i class="fas fa-box text-success  "></i></div>
                        <div class="px-3">
                        <strong class="d-block h3">
                            <?php 
                                if (!empty($dispatch_order_status_info['order_counters'])) {
                                    echo $dispatch_order_status_info['order_counters']->completedOrders;
                                }
                            ?>
                        </strong>
                        </div>
                    </div>
                    <label class="mb-0 font-weight-lighter d-block"><span class="text-sm">COMPLETED ORDER(S)</span> </label>
                    </div>
                </div>
                </div>
            </div>
        </div> 
        <div class="container-fluid pt-3 pb-2">
            <div class="row">
            <div class="col-4">
                <label class="mb-3 font-weight-lighter d-block">
                    <span class="text-sm">ON CALL SCREENER: </span>
                    <strong class="d-block">
                        <?php 
                            if (!empty($dispatch_order_status_info['on_call_screener'])) {
                                foreach($dispatch_order_status_info['on_call_screener'] as $on_call_screener) {
                                    print_me($on_call_screener);
                                }
                            } else {
                                echo '<button type="button" class="btn btn-flat btn-info" >
                                        <i class="fas fa-plus mr-2"></i> ADD
                                    </button>';
                            }
                        ?>
                    </strong>
                </label>
            </div>
            <div class="col-4">
                <label class="mb-3 font-weight-lighter d-block">
                    <span class="text-sm">ON CALL DRIVER:</span> 
                    <strong class="d-block">
                        <?php 
                            if (!empty($dispatch_order_status_info['on_call_driver'])) {
                                foreach($dispatch_order_status_info['on_call_driver'] as $on_call_driver) {
                                    print_me($on_call_driver);
                                }
                            } else {
                                echo '<button type="button" class="btn btn-flat btn-info" >
                                        <i class="fas fa-plus mr-2"></i> ADD
                                    </button>';
                            }
                        ?>
                    </strong>
                </label>
            </div>
            <div class="col-4">
                <label class="mb-3 font-weight-lighter d-block"><span class="text-sm">TOTAL STOPS:</span> 
                    <strong class="d-block">
                        <?php 
                            if (!empty($dispatch_order_status_info['order_counters'])) {
                                echo $dispatch_order_status_info['order_counters']->totalOrders;
                            }
                        ?>
                    </strong>
                </label>
            </div>
            </div>
        </div>
        <br/> 
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                </div>
                <div class="col-4">
                    <div class="input-group">
                        <input type="text" name="message" placeholder="Search..." id="search-dispatch-work-orders" class="bg-white rounded-0 border-white form-control">
                        <span class="input-group-append">
                            <button type="button" id="search-dispatch-work-orders-button" class="btn btn-info rounded-0 "><i class="fas fa-search"></i></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table--card table-hover" id="dispatch-work-orders-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 100px;">Provider/Work Order #</th> 
                                    <th style="min-width: 200px;">Customer/Address</th> 
                                    <th class="text-center" style="min-width: 150px;">Activity</th>
                                    <th class="text-right">Assign</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody id="dispatch-work-orders-table-tbody"> 
                                <?php 
                                    if (!empty($dispatch_order_status_list->orders)) { 
                                        foreach ($dispatch_order_status_list->orders as $work_orders) {
                                ?>
                                            <tr class="bg-white">
                                                <td <?php if($work_orders->isUrgent == 1){ echo "style='border-left: 7px solid #ffc107 !important;'"; } ?>>
                                                    <div class="d-flex">
                                                        <div class="pt-2 h5"><i class="fas fa-box" style="opacity: 0.4;"></i></div>
                                                        <div class="col px-3">
                                                            <a href="javascript:void(0)" class="lgsts_view_work_order_details" title="Click to View Order Details" style="cursor:pointer; color:#1f2d3d;" 
                                                            data-unique-id="<?php echo $work_orders->uniqueId; ?>"
                                                            data-medical-record-id="<?php echo $work_orders->medicalRecordId; ?>"
                                                            data-patient-id="<?php echo $work_orders->patientId; ?>"
                                                            data-hospice-id="<?php echo $work_orders->hospiceId; ?>"
                                                            data-original-activity-type-id="<?php echo $work_orders->originalActivityTypeId; ?>">
                                                                <strong><?php echo $work_orders->workOrder; ?></strong>
                                                            </a>
                                                            <div class="text-uppercase"><?php echo $work_orders->providerName; ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="col px-0">
                                                            <strong><?php echo $work_orders->customerLastName; ?>, <?php echo $work_orders->customerFirstName; ?></strong>
                                                            <div>
                                                                <?php echo $work_orders->streetAddress . ',' . $work_orders->placenumAddress . ',' . $work_orders->cityAddress . ',' . $work_orders->stateAddress . ',' . $work_orders->postalAddress; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <strong style="letter-spacing: .3px; " class=" px-3 py-1 rounded text-sm text-uppercase text-info bg-gray-light">
                                                        <?php echo $work_orders->activityType; ?>
                                                    </strong>
                                                </td>
                                                <td class="text-right">
                                                    <div class="dropdown">
                                                        <button style="min-width: 47px;" data-toggle="dropdown" class="btn btn-sm btn-outline-info rounded-0 nowrap">
                                                            <?php if($work_orders->driverAssignedId != '' && $work_orders->driverAssignedId != null) :?>
                                                                <?php echo strtoupper($work_orders->driverLName) . ", " . strtoupper($work_orders->driverFName); ?>
                                                            <?php else:?>
                                                                <i class="far fa-user"></i>
                                                            <?php endif;?>
                                                            <i class="fas fa-caret-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu bg-white py-0 dropdown-menu-right">
                                                            <?php 
                                                                if($work_orders->driverAssignedId == '' || $work_orders->driverAssignedId == null) { 
                                                            ?>
                                                                    <a href="javascript:void(0)" class="dropdown-item py-2">
                                                                        <span class="text-info"><i  class="far fa-user mr-2"></i> Pending</span> 
                                                                    </a>
                                                            <?php 
                                                                    if (!empty($dispatch_order_status_list->drivers)) {
                                                                        foreach ($dispatch_order_status_list->drivers as $drivers) { 
                                                            ?>
                                                                            <a href="javascript:void(0)" class="dropdown-item py-2 set_work_order_driver" 
                                                                            data-driver-id="<?php echo $drivers->id; ?>" 
                                                                            data-id="<?php echo $work_orders->statusId ?>"
                                                                            data-route-name="<?php echo $drivers->routeName ?>"
                                                                            data-unique-id="<?php echo $work_orders->uniqueId ?>">
                                                                                <i style="opacity:0.4;" class="fas fa-user mr-2"></i>
                                                                                <?php echo $drivers->lastName; ?>, <?php echo $drivers->firstName; ?>
                                                                            </a>
                                                            <?php 
                                                                        }
                                                                    }
                                                                } else {
                                                            ?>
                                                                    <a href="javascript:void(0)" class="dropdown-item py-2 set_work_order_driver"
                                                                    data-driver-id="0" 
                                                                    data-id="<?php echo $work_orders->statusId ?>"
                                                                    data-route-name="<?php echo $drivers->routeName ?>"
                                                                    data-unique-id="<?php echo $work_orders->uniqueId ?>">
                                                                        <i style="opacity:0.4;" class="far fa-user mr-2"></i> Pending
                                                                    </a>        
                                                            <?php
                                                                    if (!empty($dispatch_order_status_list->drivers)) {
                                                                        foreach ($dispatch_order_status_list->drivers as $drivers) {
                                                            ?>
                                                                            <?php if($drivers->id == $work_orders->driverAssignedId) :?>
                                                                                <a href="javascript:void(0)" class="dropdown-item py-2">
                                                                                    <span class="text-info"> <i style="" class="fas fa-check mr-2"></i>
                                                                                        <?php echo $drivers->lastName; ?>, <?php echo $drivers->firstName; ?>
                                                                                    </span>
                                                                                </a>
                                                                            <?php else:?>
                                                                                <a href="javascript:void(0)" class="dropdown-item py-2 set_work_order_driver" 
                                                                                data-driver-id="<?php echo $drivers->id; ?>" 
                                                                                data-id="<?php echo $work_orders->statusId ?>"
                                                                                data-route-name="<?php echo $drivers->routeName ?>"
                                                                                data-unique-id="<?php echo $work_orders->uniqueId ?>">
                                                                                    <i style="opacity:0.4;" class="fas fa-user mr-2"></i>
                                                                                    <?php echo $drivers->lastName; ?>, <?php echo $drivers->firstName; ?>
                                                                                </a>
                                                                            <?php endif;?>  
                                                            <?php
                                                                        }  
                                                                    }
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <div class="dropdown">
                                                        <button style="min-width: 47px;" data-toggle="dropdown" class="btn btn-sm btn-outline-info nowrap rounded-0">
                                                            <i   class="fas fa-ellipsis-v "></i> &nbsp;<i class="fas fa-caret-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu bg-white py-0 dropdown-menu-right">
                                                            <a href="javascript:void(0)" class="dropdown-item py-2 text-warning set_work_order_urgency"
                                                            data-id="<?php echo $work_orders->statusId ?>" 
                                                            data-unique-id="<?php echo $work_orders->uniqueId ?>"
                                                            data-isUrgent="<?php echo $work_orders->isUrgent ?>">
                                                                <span class="text-warning"><i style="opacity:0.4;" class="fas fa-exclamation-triangle mr-2"></i> URGENCY </span>
                                                            </a>
                                                            <?php if($work_orders->driverAssignedId != '') :?>
                                                                <a href="javascript:void(0)" class="dropdown-item py-2 text-info set_work_order_stop_number" data-stop-number="<?php echo $work_orders->stopNumber; ?>" data-id="<?php echo $work_orders->statusId ?>">
                                                                    <span class="text-info"><i style="opacity:0.4;" class="fas fa-hashtag mr-2"></i><?php echo $work_orders->stopNumber; ?> STOP NUMBER </span>
                                                                </a>
                                                            <?php endif;?> 
                                                            <a href="javascript:void(0)" class="dropdown-item py-2 send_work_order_to_cos" data-id="<?php echo $work_orders->statusId ?>">
                                                                <span class="text-info"><i style="opacity:0.4;" class="fas fa-info-circle mr-2"></i> SEND TO COS</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                <?php
                                        } 
                                    } else {
                                ?>
                                        <tr class="bg-white"> 
                                            <td colspan="5" class="text-center"> No work orders. </td>
                                        </tr>
                                <?php
                                    } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->

        <div class="container">
                <div class="row bootstrap-dt-container">
                    <div class="col-sm-12 col-md-6">
                    <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">
                        <?php
                        $start_entry = ($pagination['current_page'] - 1) * $pagination['results_per_page'] + 1;
                        $end_entry = min($pagination['current_page'] * $pagination['results_per_page'], $pagination['number_of_results']);
                        ?>
                        Showing <?php echo $start_entry; ?> to <?php echo $end_entry; ?> of <?php echo $pagination['number_of_results']; ?> entries
                    </div>

                    </div>
                    <div class="col-sm-12 col-md-6 justify-content-end d-flex">
                        <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                            <ul class="pagination">
                                <li class="paginate_button <?php echo ($pagination['current_page'] == 1) ? 'disabled' : ''; ?>" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_previous">
                                    <button type="button" class="btn btn-link custom-pagination-btn <?php echo ($pagination['current_page'] == 1) ? 'disabled' : ''; ?>" onclick="goToPage(<?php echo ($pagination['current_page'] > 1) ? $pagination['current_page'] - 1 : 1; ?>)">Previous</button>
                                </li>
                                <?php for ($i = 1; $i <= $pagination['number_of_pages']; $i++) { ?>
                                    <li class="paginate_button <?php echo ($pagination['current_page'] == $i) ? 'active' : ''; ?>" aria-controls="DataTables_Table_0" tabindex="0">
                                        <button type="button" class="btn btn-link custom-pagination-btn" onclick="goToPage(<?php echo $i; ?>)"><?php echo $i; ?></button>
                                    </li>
                                <?php } ?>
                                <li class="paginate_button <?php echo ($pagination['current_page'] == $pagination['number_of_pages']) ? 'disabled' : ''; ?>" aria-controls="DataTables_Table_0" tabindex="0" id="DataTables_Table_0_next">
                                    <button type="button" class="btn btn-link custom-pagination-btn <?php echo ($pagination['current_page'] == $pagination['number_of_pages']) ? 'disabled' : ''; ?>" onclick="goToPage(<?php echo ($pagination['current_page'] < $pagination['number_of_pages']) ? $pagination['current_page'] + 1 : $pagination['number_of_pages']; ?>)">Next</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        <br/><br/><br/>
        </div>
        <!-- /.content -->
    </div>
  <!-- /.content-wrapper -->

    <div class="driver-action py-3">
        <div class="container">
        <div class="d-flex align-items-center">
            <div class="ml-auto">
            <button class="btn btn-circle btn-info mx-2 px-4 rounded-0 py-2 text-capital">
                <i class='fas fa-file mr-1' style="font-size: 19px;"></i> Email <span> Receipt </span> 
            </button>
            </div>
        </div>
        </div>
    </div>

    <div class="modal fade modal_work_order_stop_number" id="work_order_stop_number" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Stop Number</h4>
                </div>
                <div class="modal-body OpenSans-Reg">
                    <div class="row">
                        <div class="col-md-12 mt-1 mb-1">
                            <div class="form-group">
                                <label>WO Stop Number <span style="color:red;">*</span></label>
                                <input type="number" value="" class="form-control" placeholder="ex. 1" name="wo_stop_number" id="wo_stop_number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn_save_work_order_stop_number">Save</button>
                    <button type="button" class="btn btn-default" id="btn_close_work_order_stop_number">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal_add_route_name" id="add_route_name_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
                <div class="modal-header">
                    <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Add Route Name</h4>
                </div>
                <div class="modal-body OpenSans-Reg">
                    <div class="row">
                        <div class="col-md-12 mt-1 mb-1">
                            <div class="form-group">
                                <label>Route Name <span style="color:red;">*</span></label>
                                <input type="text" value="" class="form-control" placeholder="" name="add_route_name" id="add_route_name">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn_save_add_route_name_modal">Save</button>
                    <button type="button" class="btn btn-default" id="btn_close_add_route_name_modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
  <!--  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
  <script>
    function goToPage(page) {
        // Add the 'clicked' class to the button
        event.target.classList.add('clicked');
        $('body').find('#dispatch-work-orders-table').find('#dispatch-work-orders-table-tbody').html("<tr class='bg-white'> <td colspan='5' class='text-center'> Retrieving Data... <i class='fa fa-spin fa-spinner'></i> </td></tr>");
        // Redirect to the specified page
        window.location.href = '<?php echo base_url("lgsts_dispatch_order_status/order_list"); ?>/' + page;
    }
</script>
