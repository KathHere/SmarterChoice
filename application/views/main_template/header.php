<style type="text/css">
	.dropdown-menu > li > a:hover {
		  color: #262626;
		  text-decoration: none;
		  background-color: #333F5E !important;
	}
	.drpdwn-menu > li > a:hover{
		background-color: #333F5E !important;
	}
	
</style>


<section class="header noprint" style="position:fixed;z-index:99999999"> 
	<div class="row" >
		<div class="container">
		<nav class="navbar " role="navigation">
		      <!-- We use the fluid option here to avoid overriding the fixed width of a normal container within the narrow content columns. -->
		      <div class="container-fluid">
		        <div class="navbar-header">
		          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-7" style="margin-top: 28px;">
		            <span class="sr-only">Toggle navigation</span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		          </button>
		          <a class="navbar-brand"  style="margin-top: -2px;" href="<?php echo base_url() ;?>" target="_blank"><img src="<?php echo base_url('assets/img/logo.png') ;?>" class="img-responsive" ></a> 
		        </div>

		        <div class="collapse navbar-collapse " id="bs-example-navbar-collapse-7" style="margin-top: 23px;padding-right:0px;">
		          <div class="pull-right header-contents" style="margin-top: -10px;">
		          	<ul class="nav navbar-nav " style="margin-left: 32px;">
		            
					
					<li class="sys-features" >
					<div class="dropdown" style="margin-top: 16px;">
					  <a id="dLabel" class="dropdown-toggle OpenSans-reg" style="color:#fff;padding-right: 10px;cursor:pointer;" role="button" data-toggle="dropdown" data-target="#" >
						Orders <span class="caret"></span>
					  </a>
					  
					  <ul class="dropdown-menu drpdwn-menu" role="menu" aria-labelledby="dLabel">
					  <li role="presentation">
							<?php $id = $this->session->userdata('userID') ;?>
							<a role="menuitem" tabindex="-1" href="<?php echo base_url('client_order/order/' . get_code($id))  ;?>">Place New Order</a>
						</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url('client_order/list_orders') ;?>" >Patient Order Summary</a>
						</li>
						
							
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url('client_order/deleted_orders') ;?>" >View Deleted Orders</a>
						</li>
						
					  </ul>
					</div>
					</li>


					<li class="sys-features" >
					<div class="dropdown" style="margin-top: 16px;">
					  <a id="dLabel" class="dropdown-toggle OpenSans-reg" style="color:#fff;padding-right: 10px;cursor:pointer;" role="button" data-toggle="dropdown" data-target="#" >
						Patient Vault <span class="caret"></span>
					  </a>
					  
					  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url('client_order/confirmed_orders') ;?>" >Search Patient Records</a>
						</li>
					
					  </ul>
					</div>
					</li>


					<li class="sys-features" >
					<div class="dropdown" style="margin-top: 16px;">
					  <a id="dLabel" class="dropdown-toggle OpenSans-reg" style="color:#fff;padding-right: 10px;cursor:pointer;" role="button" data-toggle="dropdown" data-target="#" >
						Equipments <span class="caret"></span>
					  </a>
					  
					  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
						<!-- <li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url('admin/equipment') ;?>" >Add New Equipment</a>
						</li> -->
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url('admin/equipment') ;?>" >List of Equipments</a>
						</li>
					  </ul>
					</div>
					</li>

				  
				  
	               <li class="sys-features" ><a href="<?php echo base_url('guest_gallery/beds') ;?>" class="OpenSans-reg" style="padding-right: 20px;" target="_blank">Photo Gallery</a></li>
		           
				   
				   
				   <li class="sys-features" >
					<div class="dropdown" style="margin-top: 16px;">
					
					  <button id="" class="dropdown-toggle OpenSans-reg btn btn-primary" style="color:#fff;padding-right: 10px;cursor:pointer;margin-top: -5px;" role="button" data-toggle="dropdown" data-target="#" >
						 <?php echo $this->session->userdata('lastname') ;?>,  <?php echo $this->session->userdata('firstname') ;?>&nbsp<span class="caret"></span>
					  </button>
					  
					  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
					  
						<!--<li role="presentation">
							<a role="menuitem" tabindex="-1" href="" data-toggle="modal" data-target="#edit_account<?php echo $this->session->userdata('userID') ?>">Manage Account</a>
						</li>-->
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url('user/logout') ;?>" >Log out</a>
						</li>
						
						<li role="presentation" class="divider"></li>
						<li role="presentation" class="dropdown-header">Account Management</li>
						<li role="presentation">
							<a role="menuitem" tabindex="-1" href="<?php echo base_url('admin/users') ;?>" >Manage Users</a>
						</li>

						<?php if($this->session->userdata('account_type') == 'dme_admin') :?>
							<li role="presentation">
								<a role="menuitem" tabindex="-1" href="<?php echo base_url('user/register') ;?>" >Add New User</a>
							</li>
							<li role="presentation">
								<a role="menuitem" tabindex="-1" href="<?php echo base_url('admin/group_hospice') ;?>" >Create Hospice</a>
							</li>
							<li role="presentation">
								<a role="menuitem" tabindex="-1" href="<?php echo base_url('admin/group_hospice/hospice_list') ;?>" >List of Hospice</a>
							</li>
					    <?php endif;?>
						
						
						
					  </ul>
					</div>
					</li>
		            
		          </ul>
		          </div>
		        </div><!-- /.navbar-collapse -->
		      </div>
		    </nav>

		</div>
	</div>
</section>



<div class="modal fade edit_account" id="edit_account<?php echo $this->session->userdata('userID') ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="margin-top:90px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Edit User Form</h4>
      </div>
      <div class="modal-body OpenSans-Reg">
       	<div class="row">
       			<div class="">
        	<div class="col-md-12">
					<form action="<?php echo base_url('user/update_user/'.get_code($this->session->userdata('userID'))) ?>" method="POST">  
        				<div class="col-md-6">
											  	<div class="form-group">
												    <label for="exampleInputEmail1">Email address</label>
												    <input type="email" name="email" class="form-control" id="email_add" placeholder="Email Address" value="<?php echo $this->session->userdata('email') ?>">
												  </div>
												 
												  <div class="form-group">
												    <label for="exampleInputPassword1">First Name</label>
												    <input type="text" name="firstname" class="form-control" id="" placeholder="Firstname" value="<?php echo $this->session->userdata('firstname') ?>">
												  </div>
												  <!--<div class="form-group">
												    <label for="exampleInputPassword1">Middlename</label>
												    <input type="text" name="middlename" class="form-control" id="" placeholder="Middlename" value="<?php echo $this->session->userdata('middlename') ?>">
												  </div>-->
												  <div class="form-group">
												    <label for="exampleInputPassword1">Last Name</label>
												    <input type="text" name="lastname" class="form-control" id="" placeholder="Lastname" value="<?php echo $this->session->userdata('lastname') ?>">
												  </div>
												  <!--<div class="form-group">
												    <label for="exampleInputPassword1">Address</label>
												    <input type="text" name="address" class="form-control" id="" placeholder="Address" value="<?php echo $this->session->userdata('address') ?>">
												</div>-->
												<div class="form-group">
												<label for="exampleInputPassword1">Office Number</label>
												<input type="text" name="phone" class="form-control" id="person_num" placeholder="Phone Number" value="<?php echo $this->session->userdata('phone_num') ?>">
											  </div>
												
												
										  </div>


										  <div class="col-md-6">
										  	
										  
										  <!--<div class="form-group">
										    <label for="exampleInputPassword1">Mobile Number</label>
										    <input type="text" name="mobile" class="form-control" id="" placeholder="Mobile Number" value="<?php echo $this->session->userdata('mobile_num') ?>">
										  </div>-->
										  <input type="hidden" name="balance" value="0.00" />
										 <!--  <div class="form-group">
										    <label for="exampleInputPassword1">Account Balance</label>
										    <input type="text" name="balance" class="form-control" id="" placeholder="Balance" readonly value="0.00">
										  </div> -->

										  <div class="form-group">
										    <label for="">Status</label>
										    <input type="text" name="status" class="form-control" id="username" placeholder="Status" value="<?php echo $this->session->userdata('status') ?>" readonly>
										  </div>

										  <div class="form-group">
										    <label for="exampleInputEmail1">Username</label>
										    <input type="text" name="username" class="form-control" id="username" placeholder="Auto Generated Username" value="<?php echo $this->session->userdata('username') ?>">
										  </div>
										   <div class="form-group">
										    <label for="exampleInputPassword1">Password</label>
										    <input type="password" name="password" class="form-control edit_password" id="" placeholder="Password" value="<?php echo $this->session->userdata('password') ?>">
										  </div>
										  
										  <div class="form-group">
										  	<label for="exampleInputPassword1">Account Type</label>
										     <input type="text" name="account_type" class="form-control" id="username" placeholder="" value="<?php echo $this->session->userdata('account_type') ?>" readonly>
										  </div>
										  
										   
								  </div>
						
        	</div>
       			</div>
       	</div>
      </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary btn-order" >Save Changes</button>
		  </div>
		  
		 </form> 
    </div>
	
  </div>
</div>