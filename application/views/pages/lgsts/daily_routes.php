
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
                <i class="fas fa-map" style="opacity: 0.4;"></i>
                </div>
                <div class="col px-4">
                <h3 class="m-0">Daily Routes</h3>
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
                <form action="<?php echo base_url('lgsts_daily_routes/submit_route_name_autosave/') ;?>" id="daily_routes_form" method="POST">
                    <table class="table table--card table-hover">
                        <thead>
                            <tr>
                                <th style="min-width: 200px;">Employee</th> 
                                <th style="min-width: 100px;">Route</th> 
                                <th class="text-center" style="min-width: 100px;">Total Stops</th>
                                <th class="text-center">Start Time</th>
                                <th class="text-center">End Time</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                if (!empty($daily_routes)) { 
                                    foreach ($daily_routes as $route) {
                            ?>
                                        <tr class="bg-white">
                                            <td>
                                                <?php echo $route->employeeLastName; ?>, <?php echo $route->employeeFirstName; ?>
                                            </td>
                                            <td>
                                                <input type="text" value="<?php echo $route->routeName; ?>" class="form-control bg-white daily_routes_route_name" name="route_name" route-id="<?php echo $route->routeId; ?>" placeholder="Add route name" tabindex="5">
                                            </td>
                                            <td class="text-center">
                                                <?php echo $route->totalStops; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $route->startTime; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $route->endTime; ?>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" onclick="location.href='<?php echo base_url('lgsts_driver_order_list/order_list/'.$route->routeId) ?>';" style="min-width: 47px;" class="bg-cyan btn btn-outline-info btn-sm nowrap rounded-0">
                                                    <i class="fas fa-clipboard"></i> &nbsp; Driver Order List
                                                </button>
                                                <button type="button" onclick="location.href='<?php echo base_url('lgsts_route_sheet/details/'.$route->routeId) ?>';" style="min-width: 47px;" class="bg-cyan btn btn-outline-info btn-sm nowrap rounded-0">
                                                    <i class="fas fa-map"></i> &nbsp; Route Sheet
                                                </button>
                                            </td>
                                        </tr>
                            <?php
                                    } 
                                } else {
                            ?>
                                    <tr class="bg-white"> 
                                        <td colspan="6" class="text-center"> No routes. </td>
                                    </tr>
                            <?php
                                } 
                            ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div> 

        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->


    <br/><br/><br/>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
