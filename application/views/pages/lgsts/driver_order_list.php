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
                  <i class="fas fa-clipboard-list" style="opacity: 0.4;"></i>
                </div>
                <div class="col px-4">
                  <h3 class="m-0">Driver Order List</h3>
                </div>
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div> 
      <!-- /.content-header -->
  
      <div class="container-fluid ">
        <div class="row">
            <div class="col-4">
                <label class="mb-3 font-weight-lighter d-block">
                    <span class="text-sm">REG HRS: </span>
                    <strong class="d-block"><i style="font-size: 15px;" class="fas fa-check-circle mr-1"></i> Yes</strong>
                </label>
            </div>
            <div class="col-4">
                <label class="mb-3 font-weight-lighter d-block">
                    <span class="text-sm">ON CALL SCREENER: </span>
                    <strong class="d-block">
                    <?php 
                      if (!empty($driver_order_list_info['on_call_screener'])) {
                          foreach($driver_order_list_info['on_call_screener'] as $on_call_screener) {
                              print_me($on_call_screener);
                          }
                      }
                    ?>
                    </strong>
                </label>
            </div>
            <div class="col-4">
              <label class="mb-3 font-weight-lighter d-block"><span class="text-sm"> TOTAL STOPS: </span> 
                <div>
                  <?php echo count($driver_order_list_work_orders->stops) + count($driver_order_list_work_orders->orders); ?>
                </div>
              </label>
            </div>  
            <div class="col-4">
              <label class="mb-3 font-weight-lighter d-block">
                    <span class="text-sm">ON CALL HRS: </span><strong class="d-block"> &nbsp; </strong>
              </label>
            </div>
            <div class="col-4">
              <label class="mb-3 font-weight-lighter d-block">
                <span class="text-sm">ON CALL DRIVER:</span> 
                <strong class="d-block">
                  <?php 
                      if (!empty($driver_order_list_info['on_call_driver'])) {
                          foreach($driver_order_list_info['on_call_driver'] as $on_call_driver) {
                              print_me($on_call_driver);
                          }
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
          <div class="col-lg-12">
            <div class="table-responsive">
              <table class="table table--card table-hover">
                <thead>
                  <tr>
                    <th style="width: 30px;">STOP</th>
                    <th style="min-width: 250px;">Provider/Word Order #</th> 
                    <th style="min-width: 250px;">Customer/Address</th> 
                    <th class="text-center" style="width: 80px;" >ATTENTION</th>
                    <th style="min-width: 80px;">ETA</th>
                  </tr>
                </thead>
                <tbody> 
                  <?php
                    if (!empty($driver_order_list_work_orders->stops)) {
                      foreach($driver_order_list_work_orders->stops as $driver_stops) {
                  ?>
                      <tr class="bg-white" style="height: 70px;">
                        <td class="text-center">
                          <strong class="h4 text-info">
                            <?php echo $driver_stops->stopNumber; ?>
                          </strong>
                        </td>
                        <td colspan="5">
                          <strong><?php echo $driver_stops->stop; ?></strong>
                        </td>
                      </tr>
                  <?php
                      }
                    }
                    foreach($driver_order_list_work_orders->orders as $driver_work_orders) {
                  ?>
                      <tr class="bg-white">
                        <td class="text-center">
                          <strong class="h4 text-info"><?php echo $driver_work_orders->stopNumber; ?></strong>
                        </td>
                        <td>
                          <strong>
                            <?php echo $driver_work_orders->workOrder; ?>
                          </strong>
                          <div class="text-uppercase">
                            <?php echo $driver_work_orders->providerName; ?>
                          </div>
                        </td>
                        <td>
                          <div class="d-flex">
                            <div class="col px-0">
                              <strong class="text-uppercase">
                                <?php echo strtoupper($driver_work_orders->customerLastName) . ", " . strtoupper($driver_work_orders->customerFirstName); ?>
                              </strong>
                              <div>
                                <?php echo $driver_work_orders->streetAddress . ',' . $driver_work_orders->placenum . ',' . $driver_work_orders->cityAddress . ',' . $driver_work_orders->stateAddress . ',' . $driver_work_orders->postalAddress; ?>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td class='text-center'>
                          <?php if($driver_work_orders->isUrgent == 1){ ?>
                            <i style="font-size:20px;" class="fas fa-exclamation-triangle text-warning mr-2"></i>
                          <?php } ?>
                        </td>
                        <td>
                          <?php echo $driver_work_orders->eta; ?>
                        </td>
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
      </div> 

      <br/><br/><br/>
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->