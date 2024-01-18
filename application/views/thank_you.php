<section class="form-container">
	<div class="row">
		<div class="container">
			<div class="col-md-12">

			

				<div class="inner-form-container" style="margin-top: 105px;">
						<div class="" style="padding: 14px;text-align:center;padding-top:30px;padding-bottom:30px;">
						<img src="<?php echo base_url('assets/img/smileys.png') ;?>" class="img-responsive" style="margin-left: 32%;margin-top: 29px;margin-bottom: 40px;height: 320px;">
							
							<p class="OpenSans-reg col-md-8 col-md-offset-2" style="text-align:center"><strong>Your order has been sent.</strong></p>

							<p class="OpenSans-reg col-md-8 col-md-offset-2">Need to place another order? <a href="<?php echo base_url('client_order/order/' . get_code($this->session->userdata('userID')))  ;?>">Click here.</a></p>

							<a href="<?php echo base_url('user/logout') ;?>"><button class="btn btn-info btn-lg" style="height: 50px;width: 252px;margin-top:20px;margin-bottom:30px;">Go Back to Homepage</button></a>


						</div>
				</div>

				<div class="page-shadow">
					
				</div>
			</div>
		</div>
	</div>
</section>