
     <!-- Content Wrapper. Contains page content -->
     <div class="content-wrapper"> 
    
    <!-- Main content -->
    <div class="content">  
        <!-- Content Header (Page header) -->
        <div class="content-header px-0 pt-0">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12 pt-3 text-left">
                        <div class="d-flex">
                            <div class="h3">
                                <i class="fas fa-map" style="opacity: 0.4;"></i>
                            </div>
                            <div class="col px-4">
                                <h3 class="m-0">Route Sheet / O2 Logs</h3>
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
                    <label class="mb-3 font-weight-lighter d-block">
                        <span class="text-sm">DATE:</span><strong class="d-block"><?php echo $route_details->routeDetails->date; ?></strong>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="mb-3 font-weight-lighter d-block">
                        <span class="text-sm">EMPLOYEE:</span> 
                        <strong class="d-block">
                            <?php echo $route_details->routeDetails->driverLastName; ?>, <?php echo $route_details->routeDetails->driverFirstName; ?>
                        </strong>
                    </label>
                </div>
                <div class="col-4">
                    <label class="mb-3 font-weight-lighter d-block"> 
                        <span class="text-sm">ROUTE:</span> <strong class="d-block"><?php echo $route_details->routeDetails->routeName; ?></strong>
                    </label>
                </div>
                <div class="col-4">
                    <label class="mb-3 font-weight-lighter d-block">
                        <span class="text-sm">REGULAR HOURS:</span> 
                        <?php if ($route_details->routeDetails->isRegularHours) : ?>    
                            <strong class="d-block"><i class="fas fa-check-circle"></i> YES</strong>
                        <?php endif; ?>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="mb-3 font-weight-lighter d-block">
                        <span class="text-sm">START TIME:</span> <strong class="d-block"><?php echo $route_details->routeDetails->timeStart; ?></strong>
                    </label>
                </div>
                <div class="col-4">
                    <label class="mb-3 font-weight-lighter d-block">
                        <span class="text-sm">START MILEAGE:</span> <strong class="d-block"><?php echo $route_details->routeDetails->mileageStart; ?></strong>
                    </label>
                </div>
                <div class="col-4">
                    <label class="mb-3 font-weight-lighter d-block">
                        <span class="text-sm">TOTAL STOPS:</span> <strong class="d-block"><?php echo $route_details->routeDetails->totalStops; ?></strong>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="mb-3 font-weight-lighter d-block">
                        <span class="text-sm">END TIME:</span> <strong class="d-block"><?php echo $route_details->routeDetails->timeEnd; ?></strong>
                    </label>
                </div>
                <div class="col-4">
                    <label class="mb-3 font-weight-lighter d-block">
                        <span class="text-sm">END MILEAGE:</span> <strong class="d-block"><?php echo $route_details->routeDetails->mileageEnd; ?></strong>
                    </label>
                </div>
                <div class="col-4">
                    <label class="mb-3 font-weight-lighter d-block">
                        <span class="text-sm">ON CALL HOURS:</span> 
                        <?php if ($route_details->routeDetails->isRegularHours == false) : ?>    
                            <strong class="d-block"><i class="fas fa-check-circle"></i> YES</strong>
                        <?php endif; ?>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <label class="mb-3 font-weight-lighter d-block">
                        <span class="text-sm">TOTAL TIME:</span> <strong class="d-block"><?php echo $route_details->routeDetails->timeTotal; ?></strong>
                    </label>
                </div>
                <div class="col-4">
                    <label class="mb-3 font-weight-lighter d-block">
                        <span class="text-sm">TOTAL MILEAGE:</span> <strong class="d-block"><?php echo $route_details->routeDetails->mileageTotal; ?></strong>
                    </label>
                </div>
            </div>
        </div>
        <br/>
        <div class="container-fluid">
            <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                <table class="table table--card table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">STOP</th> 
                        <th class="text-center">WORK ORDER</th> 
                        <th class="text-left" style="min-width: 200px;">NAME</th>
                        <th class="text-left" style="min-width: 200px;">ADDRESS</th>
                        <th class="text-center">ARRIVE / DEPART</th>
                        <th class="text-center">ODOM</th>
                    </tr>
                    </thead>
                    <tbody> 
                        <?php 
                            if (!empty($route_details->orderStops)) { 
                                foreach ($route_details->orderStops as $order_stop) {
                        ?>
                                    <tr class="bg-white">
                                        <td class="text-center">
                                            <?php echo $order_stop->stopNumber; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $order_stop->workOrder; ?>
                                        </td>
                                        <td class="text-left">
                                            <?php echo $order_stop->cusLastName; ?>, <?php echo $order_stop->cusFirstName; ?>
                                        </td>
                                        <td class="text-left">
                                            <?php echo $order_stop->cusStreet . ',' . $order_stop->cusPlacenum . ',' . $order_stop->cusCity . ',' . $order_stop->cusState . ',' . $order_stop->cusPostalCode; ?>
                                        </td>
                                        <td class="text-center">
                                            <span> <?php echo $order_stop->timeStart; ?> </span> <br />
                                            <span> <?php echo $order_stop->timeStop; ?> </span>
                                        </td>
                                        <td class="text-center">
                                            <?php echo $order_stop->odom; ?>
                                        </td>
                                    </tr>
                        <?php
                                } 
                            } else {
                        ?>
                                <tr class="bg-white"> 
                                    <td colspan="6" class="text-center"> No stops. </td>
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

        <br/><br/>
        <!-- Content Header (Page header) -->
        <div class="content-header px-0 pt-0">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12 pt-3 text-left">
                        <div class="d-flex">
                            <div class="h3">
                                <i class="fas fa-map" style="opacity: 0.4;"></i>
                            </div>
                            <div class="col px-4">
                                <h3 class="m-0">Oxygen Cylinder Delivery</h3>
                            </div>
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div> 
        <!-- /.content-header -->
        
        <div class="container-fluid">
            <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                <table class="table table--card table-hover">
                    <thead>
                    <tr>
                        <th class="text-center">WORK ORDER</th> 
                        <th class="text-left" style="min-width: 200px;">NAME</th>
                        <th class="text-left" style="min-width: 250px;">ADDRESS</th>
                        <th class="text-center">QTY</th> 
                        <th class="text-center">CYLINDER SIZE</th>
                        <th class="text-center">LOT NO.</th>
                    </tr>
                    </thead>
                    <tbody> 
                        <?php 
                            if (!empty($route_details->o2Delivery)) { 
                                foreach ($route_details->o2Delivery as $o2_delivery) {
                                    if ($o2_delivery != null) {
                        ?>
                                        <tr class="bg-white">
                                            <td class="text-center">
                                                <?php echo $o2_delivery->workOrderNum; ?>
                                            </td>
                                            <td class="text-left">
                                                <?php echo $o2_delivery->cusLastName; ?>, <?php echo $o2_delivery->cusFirstName; ?>
                                            </td>
                                            <td class="text-left">
                                                <?php echo $o2_delivery->cusStreet . ',' . $o2_delivery->cusPlacenum . ',' . $o2_delivery->cusCity . ',' . $o2_delivery->cusState . ',' . $o2_delivery->cusPostalCode; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $o2_delivery->itemQty; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $o2_delivery->cylinderSize; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $o2_delivery->serialLotNum; ?>
                                            </td>
                                        </tr>
                        <?php
                                    }
                                } 
                            } else {
                        ?>
                                <tr class="bg-white"> 
                                    <td colspan="6" class="text-center"> No work orders. </td>
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

        <br/>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->