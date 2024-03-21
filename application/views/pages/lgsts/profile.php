
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
                                    <i class="fas fa-user" style="opacity: 0.4;"></i>
                                </div>
                                <div class="col px-4">
                                    <h3 class="m-0">Profile</h3>
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
                        <div class="card bg-white rounded-0 ">
                       
                        <div class="card-body">
                            <div class="row">
                            <div class="col-6 col-sm-6">
                                <div class="form-group">
                                <label class="font-weight-light">Date of Entry</label>
                                <?php $current_date = date("m-d-Y"); ?>
                                <input type="text" class="form-control rounded-0 bg-white" name="date_of_entry" value="<?php echo $current_date; ?>" readonly>
                                </div>
                            </div>
                            <div class="col-6 col-sm-6">
                                <div class="form-group">
                                    <label class="font-weight-light">Assign Location</label>
                                    <div class="dropdown">
                                        <?php
                                            // Get the current user's location
                                            $lgsts_user = $this->session->userdata('lgsts_user');
                                            $current_service_location = get_current_service_location_details($lgsts_user['account_location']);
                                            
                                            $location_display = $current_service_location['user_city'].', '.$current_service_location['user_state'];
                                        ?>
                                        <button id="assignLocationDropdownBtn" style="width: 100%;" data-toggle="dropdown" class="btn btn-sm bg-white nowrap rounded-0 form-control text-left assign-location-dropdown"> <!-- Add assign-location-dropdown class -->
                                            <?php echo $location_display; ?>&nbsp;
                                            <i class="fas float-right mt-1 fa-caret-down"></i>
                                        </button>
                                        <div class="dropdown-menu bg-white py-0 rounded-0" style="width: 100%;" id="assignLocationDropdownMenu">
                                            <!-- Specify the options directly -->
                                            <a href="#" class="dropdown-item py-2" data-location="North Las Vegas, NV">North Las Vegas, NV</a>
                                            <a href="#" class="dropdown-item py-2" data-location="Downey, CA">Downey, CA</a>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <div class="col-6 col-sm-6">
                                <div class="form-group">
                                <label class="font-weight-light">First Name</label>
                                <input type="text" class="form-control rounded-0 bg-white" name="first_name" value="<?php echo isset($user_info) ? $user_info->first_name : ''; ?>">
                                </div>
                            </div>
                            <div class="col-6 col-sm-6">
                                <div class="form-group">
                                <label class="font-weight-light">Last Name</label>
                                <input type="text" class="form-control rounded-0 bg-white" name="last_name" value="<?php echo isset($user_info) ? $user_info->last_name : ''; ?>">

                                </div>
                            </div>
                            <div class="col-6 col-sm-6">
                                <div class="form-group">
                                <label class="font-weight-light">Username</label>
                                <input type="text" class="form-control rounded-0 bg-white" name="username" value="<?php echo isset($user_info) ? $user_info->username : ''; ?>">
                                </div>
                            </div>
                            <div class="col-6 col-sm-6">
                                <div class="form-group">
                                <label class="font-weight-light">Password</label>
                                <input type="password" class="form-control rounded-0 bg-white" name="password">
                                </div>
                            </div>
                            <div class="col-6 col-sm-6">
                                <div class="form-group">
                                    <label class="font-weight-light">Mobile Number</label>
                                    <input type="text" class="form-control rounded-0 bg-white" name="mobile_number" value="<?php echo isset($user_info) ? $user_info->mobile_number : ''; ?>">
                                </div>
                            </div>
                                <div class="col-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="font-weight-light">User Type</label>
                                        <!-- Dropdown styled select -->
                                        <div class="dropdown">
                                            <?php
                                                $lgsts_user = $this->session->userdata('lgsts_user');
                                                $user_type = $lgsts_user['user_type'];
                                                
                                                if ($user_type == 'super_admin') {
                                                    $user_type_display = 'Super Admin';
                                                } elseif ($user_type == 'admin') {
                                                    $user_type_display = 'Admin';
                                                } elseif ($user_type == 'dispatcher') {
                                                    $user_type_display = 'Dispatcher';
                                                } elseif ($user_type == 'screener') {
                                                    $user_type_display = 'Screener';
                                                } elseif ($user_type == 'driver') {
                                                    $user_type_display = 'Driver';
                                                } elseif ($user_type == 'screener_and_driver') {
                                                    $user_type_display = 'Screener & Driver';
                                                } else {
                                                    $user_type_display = ''; 
                                                }
                                            ?>
                                            <button id="userTypeDropdownBtn" style="width: 100%;" data-toggle="dropdown" class="btn btn-sm bg-white nowrap rounded-0 form-control text-left user-type-dropdown"> <!-- Add user-type-dropdown class -->
                                                <?php echo $user_type_display; ?>&nbsp;
                                                <i class="fas float-right mt-1 fa-caret-down"></i>
                                            </button>
                                            <div class="dropdown-menu bg-white py-0 rounded-0" style="width: 100%;" id="userTypeDropdownMenu">
                                                <!-- Specify the options directly -->
                                                <a href="#" class="dropdown-item py-2" data-user-type="super_admin">Super Admin</a>
                                                <a href="#" class="dropdown-item py-2" data-user-type="admin">Admin</a>
                                                <a href="#" class="dropdown-item py-2" data-user-type="dispatcher">Dispatcher</a>
                                                <a href="#" class="dropdown-item py-2" data-user-type="screener">Screener</a>
                                                <a href="#" class="dropdown-item py-2" data-user-type="driver">Driver</a>
                                                <a href="#" class="dropdown-item py-2" data-user-type="screener_and_driver">Screener & Driver</a>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="form-group">
                                        <label class="font-weight-light">On Call</label>
                                        <div class="d-flex">
                                            <div class="mr-5 pt-1">
                                                <div class="custom-control custom-radio bg-white">
                                                    <input class="custom-control-input" type="radio" id="customRadio1" name="onCall" value="yes" <?php echo isset($user_info) && $user_info->on_call ? 'checked' : ''; ?>>
                                                    <label for="customRadio1" class="custom-control-label">YES</label>
                                                </div>
                                            </div>
                                            <div class="pt-1">
                                                <div class="custom-control custom-radio bg-white">
                                                    <input class="custom-control-input" type="radio" id="customRadio2" name="onCall" value="no" <?php echo isset($user_info) && !$user_info->on_call ? 'checked' : ''; ?>>
                                                    <label for="customRadio2" class="custom-control-label">NO</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 text-center">
                                    <br/>
                                    <button id="saveChangesBtn" class="btn btn-info rounded-0 text-uppercase px-5">
                                        <i class='fas fa-save mr-2'></i> Save Changes
                                    </button>
                                    <br/><br/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <br/><br/><br/>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>

    document.addEventListener('DOMContentLoaded', function() {

        var assignLocationDropdownBtn = document.getElementById('assignLocationDropdownBtn');
        var assignLocationDropdownMenu = document.getElementById('assignLocationDropdownMenu');
        assignLocationDropdownMenu.addEventListener('click', function(event) {

            if (event.target.classList.contains('dropdown-item')) {

                var selectedLocation = event.target.getAttribute('data-location');

                assignLocationDropdownBtn.innerText = selectedLocation;
            }
        });

        var userTypeDropdownBtn = document.getElementById('userTypeDropdownBtn');
        var userTypeDropdownMenu = document.getElementById('userTypeDropdownMenu');
        userTypeDropdownMenu.addEventListener('click', function(event) {

            if (event.target.classList.contains('dropdown-item')) {

                var selectedUserType = event.target.getAttribute('data-user-type');
                
                userTypeDropdownBtn.innerText = event.target.innerText;
            }
        });

        $('#saveChangesBtn').click(function() {
            // Trigger the confirmation dialog
            jConfirm('Save Changes?', 'Reminder', function(response) {
                if (response) {
                    saveChanges();
                }
            });
        });

        // Function to handle saving changes
        function saveChanges() {
            $.ajax({
                url: 'lgsts_users/save_profile_changes', 
                type: 'POST',
                data: {},
                beforeSend: function() {
                    // Show loading spinner or other indication that changes are being saved
                },
                success: function(response) {

                    alert('Profile changes saved successfully');
                },
                error: function(xhr, status, error) {

                    console.error(xhr.responseText);
                }
            });
        }
    });

</script>