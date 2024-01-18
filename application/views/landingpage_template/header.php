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
	          <a class="navbar-brand"  style="margin-top: -2px;" href="<?php echo base_url() ;?>"><img src="<?php echo base_url('assets/img/logo.png') ;?>" class="img-responsive"></a>
	        </div>

	        <div class="collapse navbar-collapse " id="bs-example-navbar-collapse-7" style="margin-top: 23px;padding-right:0px;">

	          <div class="pull-right header-contents" style="margin-top: -10px;"  id="single-page-nav">
					<ul class="nav navbar-nav " style="margin-left: 32px;">
					<!-- <li class="sys-features" ><a href="#" class="OpenSans-reg">Hospice DME Software</a></li> -->

					<li class="sys-features" >


						<div class="" style="margin-top: 16px;">

						  <a id="dLabel" class=" OpenSans-reg" style="color:#fff;padding-right: 8px;" role="button"  href="#about-us">
							About Us
						  </a>



						</div>
					<li class="sys-features" ><a href="#mission" class="OpenSans-reg" style="padding-right: 20px;">Mission</a></li>


					<li class="sys-features" >


						<div class="" style="margin-top: 16px;">

						  <a id="dLabel" class=" OpenSans-reg" style="color:#fff;padding-right: 10px;" role="button"  href="#request-quote">
							Contact Us
						  </a>


						</div>

					</li>

					<li class="sys-features" ><a href="#" class="OpenSans-reg" id="landingpage_gallery">Photo Gallery</a></li>
					<li style="width: 150px;" class="contact-button">
						<a href="">
							<button class="btn btn-success btn-block OpenSans-reg" style="margin-top: -7px;height: 38px;font-size: 16px;" id="login_btn" onclick="javascript:void(0)">Client Login</button>
						</a>
					</li>

				  </ul>
	          </div>
	        </div><!-- /.navbar-collapse -->
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