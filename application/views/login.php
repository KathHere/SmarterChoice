<section class="form-container">

	<div class="row">
		<div class="container">
			<div class="col-md-6 col-md-offset-3">
				<div class="inner-form-container" style="margin-top:125px;">
						<div class="row">
						
							<?php
								if (!empty($failed) && $failed== 'true') : ?>
								<div class="alert alert-dismissable alert-danger alert-dashboard fade in" style="margin-left:15px">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
									<strong>Error logging in</strong>. Username and/or Password Error.
								</div>
							<?php endif; ?>
							
						
						
							<div class="container">
									<div style="margin-left: 5%;">
										<h1 class="OpenSans-Lig" >Login</h1>
								<!-- 	<h5 class="OpenSans-Reg">Proin eget tortor risus. Curabitur non nulla sit amet nisl tempus convallis</h5> -->
									</div>
								<div class="col-md-5" style="padding: 25px;margin-left: 3%;">

									<div class="form-container" style="padding: 15px;border-radius:4px;">
										<form action="<?php echo base_url('user/process_login') ;?>" method="POST" id="user_login_form">
										  
										  <div class="form-group">
										    <label for="exampleInputEmail1">Username</label>
										    <input type="text" name="username" class="form-control" id="" placeholder="Username" autocomplete="off">
										  </div>
										  <div class="form-group">
										    <label for="exampleInputPassword1">Password</label>
										    <input type="password" name="password" class="form-control" id="" placeholder="Password" autocomplete="off">
										  </div>
										 
										  <button type="submit" class="btn btn-success btn-block" style="height: 43px;">Login</button>

									</form>
									</div>
									
									<a href="<?php echo base_url();?>">  <button class="btn btn-info OpenSans-reg" style="margin-left: 130px;margin-top: 30px;height: 41px;">Return to Homepage</button></a>
									
							    </div>
							</div>
						</div>
				</div>

				<div class="page-shadow" style="width: 102.8%;">
					
				</div>
			</div>
		</div>
	</div>
</section>