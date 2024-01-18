<section class="header" style="position: fixed;z-index: 999999;" > 
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
			<div class="col-md-6 col-md-offset-3">  
				<a class="navbar-brand"  style="margin-top: -2px;margin-top: -2px;width: 450px;margin-bottom: 10px;" href="<?php echo base_url() ;?>"><img src="<?php echo base_url('assets/img/logo.png') ;?>" class="img-responsive"></a>
			</div>
	        </div>

	        
	      </div>
	    </nav>

	</div>
</div>
</section>



<!-- Modal for the certificate -->
<div class="modal fade" style="z-index: 999999;" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<center><h4 class="modal-title" id="myModalLabel">Login</h4></center>
			</div>
			<div class="modal-body">
		 		<input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
		 		<br /><br />
		 		<input type="password" class="form-control" id="pass" name="password" placeholder="Password">
			</div>
		</div>
	</div>
</div>