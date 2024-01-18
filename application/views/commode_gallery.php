<!--style>
	#wrapper {
		background-color: #fff;
		width: 100%;
		height: 65%;
		min-height: 305px;
		margin: 0 0 0 0;
		position: relative;
		bottom: 0;
		left: 0;
		box-shadow: none !important;
		border-bottom:0px !important;
		border-top:0px !important;
	}
	#inner {
		width: 100%;
		height: 503px;
		position: relative;
		overflow: hidden;
	}

	#carousel{

	}
	#carousel div {
		border: 1px solid #999;
		background: #fff;
		width: 170px;
		height: 240px;
		float: left;
		padding: 10px;
		margin: 0 5px;
	}
	#carousel div img{
		width: 150px;
		margin-top: -8px;
	}
	#pager {
		text-align: center;
		margin-top: 20px;
		color: #666;
	}
	#pager a {
		color: #666;
		text-decoration: none;
		display: inline-block;
		padding: 5px 10px;
	}
	#pager a:hover {
		color: #333;
	}
	#pager a.selected {
		background-color: #333;
		color: #ccc;
	}
	#prev, #next {
		display: block;
		width: 50px;
		height: 80px;
		margin-top: -40px;
		position: absolute;
		top: 50%;
		z-index: 2;
	}
	#prev {
		background: url( ../assets/img/arrow-left.png ) no-repeat;
		left: 50%;
		margin-left: -520px;
		border-top:0px !important;
		border-bottom:0px !important;
		top:34% !important;
	}
	#next {
		background: url( ../assets/img/arrow-right.png ) no-repeat;
		right: 50%;
		margin-right: -520px;
		border-bottom:0px !important;
		border-top:0px !important;
		top:34% !important;
	}

	#next, #prev:hover{
		background-color: transparent !important;
	}

	.caroufredsel_wrapper{
		margin-top: 40px !important;
	}

	span{
		font-size: 12px !important;
		text-align: center !important;
	}
	
</style-->

<style type="text/css">
body {
margin:0px;
padding:0px;
width:100%;
overflow-x: hidden;
}
.mycanvas{
	display: none !important;
}
.elementTitle{
	font-family: 'OpenSans-Light' !important;
color: #83C677 !important;
}
</style>

<section class="form-container">
	<div class="row">
		<div class="container">
			<div class="col-md-12">

 
				<div class="inner-form-container" style="min-height: 530px;margin-top: 105px;">
							<h5 class="OpenSans-reg" style="text-align:center;margin-top: 40px;cursor:pointer">
								<a class="category-link beds">Hospital Beds</a>  &bull;  
								<a class="category-link oxygen" >Oxygen</a>   &bull;  
								<a class="category-link wheelchairs">Wheelchairs</a>  &bull;
								<a class="category-link respiratory">Respiratory</a>  &bull;
								<a class="category-link mattress">Mattress</a>  &bull;
								<a class="category-link ambulatory" >Ambulatory</a>  &bull;
								<a class="category-link hydraulic-lifts">Lifts and Slings</a> 
							</h5>
							<h3 class="OpenSans-reg" style="text-align:center;margin-top:30px;margin-bottom:50px;">Lifts and Slings</h1>
							<h4 class="OpenSans-reg" style="margin-left:20px;margin-bottom:-40px;"></h4>
							
							     <!-- Lifts -->
				          	  <div id="magic_carousel_white2">
					           		<div class="myloader"></div>
					                <!-- CONTENT -->
					                <ul class="magic_carousel_list">
					                    <li data-title="Hydraulic Lift" data-bottom-thumb="<?php echo base_url('assets/carousel_img/dakar-1.jpg') ;?>"  ><img src="<?php echo base_url('assets/carousel_img/dakar-11.jpg') ;?>" alt="" /></li>  
					                    <li data-title="Commode Sling" data-bottom-thumb="<?php echo base_url('assets/carousel_img/dakar-1.jpg') ;?>"  ><img src="<?php echo base_url('assets/carousel_img/dakar-12.jpg') ;?>" alt="" /></li>  
					                    <li data-title="Full Body Sling" data-bottom-thumb="<?php echo base_url('assets/carousel_img/dakar-1.jpg') ;?>"  ><img src="<?php echo base_url('assets/carousel_img/dakar-13.jpg') ;?>" alt="" /></li>  
					                
					                </ul>    
					                                       
					                           
					          </div>
					         
							 <a href="<?php echo base_url();?>">  <button class="btn btn-info OpenSans-reg" style="margin-left: 470px;margin-top: -30px;margin-bottom: 21px;height: 41px;">Go back to homepage</button></a>
							<!-- <div id="wrapper">
								<div id="inner">
									<div id="pager">
										
										<a href="#beds">Hospital Beds and Related Products</a> &bull;
										<a href="#mattress">Mattress/Support Surfaces</a> &bull;
										<a href="#lifts">Patient Lifts and Slings</a> &bull;
										<a href="#respiratory">Respiratory</a> &bull;
										<a href="#ambulatory">Ambulatory</a> &bull;
										<a href="#wheelchairs">Wheelchairs</a> 
									</div>
									
									<div id="carousel">
									<!-- hospital beds -->
<!-- 
										<div class="beds" id="beds">
											<img src="<?php echo base_url('assets/carousel_img/dakar-1.jpg') ;?>" width="140" height="200" />
											<span  class="OpenSans-reg">Semi Electric Hospital Bed </span>
										</div>
										<div class="beds" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-2.jpg') ;?>" width="140" height="200" />
											<span  class="OpenSans-reg"> Full electric Hospital Bed </span>
										</div>
										<div class="beds" id="jackets">
											<img src="<?php echo base_url('assets/carousel_img/dakar-3.jpg') ;?>" width="140" height="200" />
											<span  class="OpenSans-reg">Full Electric Hi-Lo Hospital Bed  </span>
										</div>
										<div class="beds" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-4.jpg') ;?>" width="140" height="200" />
											<span  class="OpenSans-reg">Manual Low Bed</span>
										</div>
										<div class="beds" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-5.jpg') ;?>" width="140" height="200" />
											<span  class="OpenSans-reg">Bariatric Electric Hospital Bed </span>
										</div>
 -->
									<!-- 	<div class="beds" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-6.jpg') ;?>" width="140" height="200" />
											<em>•	Trapeze</em>
										</div>
 -->
										<!-- <div class="beds" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-6.jpg') ;?>" width="140" height="200" />
											<span  class="OpenSans-reg">Over Bed Table </span>
										</div>

										<div class="beds" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-7.jpg') ;?>" width="140" height="200" />
											<span  class="OpenSans-reg">Floor Standing Trapeze </span>
										</div> -->

										<!-- mattress-->

										<!-- <div class="mattress" id="mattress">
											<img src="<?php echo base_url('assets/carousel_img/dakar-8.jpg') ;?>" width="140" height="200" />
											<span>Alternating Pressure Pump & Pad</span>
										</div>
										<div class="mattress" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-9.jpg') ;?>" width="140" height="200" />
											<span>Low Air Loss Mattress </span>
										</div>
										<div class="mattress" >
											<img src="<?php echo base_url('assets/carousel_img/dakar-10.jpg') ;?>" width="140" height="200" />
											<span>Gel OverLay Mattress </span>
										</div>

										<!-- lifts-->
										<!-- <div class="lifts" id="lifts">
											<img src="<?php echo base_url('assets/carousel_img/dakar-11.jpg') ;?>" width="140" height="200" />
											<span>Hydraulic Lift</span>
										</div>
										<div class="lifts" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-12.jpg') ;?>" width="140" height="200" />
											<span>Commode Sling</span>
										</div>
										<div class="lifts" >
											<img src="<?php echo base_url('assets/carousel_img/dakar-13.jpg') ;?>" width="140" height="200" />
											<span>Full Body Sling </span>
										</div> -->
 

										<!-- respiratory-->
										<!-- <div class="respiratory" id="respiratory">
											<img src="<?php echo base_url('assets/carousel_img/dakar-14.jpg') ;?>" width="140" height="200" />
											<span>Oxygen Concentrators </span>
										</div>
										<div class="respiratory" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-15.jpg') ;?>" width="140" height="200" />
											<span>Oxygen Cylinders </span>
										</div>
										<div class="respiratory" >
											<img src="<?php echo base_url('assets/carousel_img/dakar-16.jpg') ;?>" width="140" height="200" />
											<span>Oxygen Conserving Devices</span>
										</div>
										<div class="respiratory" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-17.jpg') ;?>" width="140" height="200" />
											<span>Liquid Oxygen </span>
										</div>
										<div class="respiratory" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-18.jpg') ;?>" width="140" height="200" />
											<span>Nebulizer Therapy</span>
										</div>
										<div class="respiratory" >
											<img src="<?php echo base_url('assets/carousel_img/dakar-19.jpg') ;?>" width="140" height="200" />
											<span>Suction </span>
										</div> -->


										<!-- wheelchairs-->
										<!-- <div class="wheelchairs" id="wheelchairs">
											<img src="<?php echo base_url('assets/carousel_img/dakar-20.jpg') ;?>" width="140" height="200" />
											<span>16”-24” Wheelchair </span>
										</div>
										<div class="wheelchairs" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-21.jpg') ;?>" width="140" height="200" />
											<span>High Back Reclining Wheelchair </span>
										</div>
										<div class="wheelchairs" >
											<img src="<?php echo base_url('assets/carousel_img/dakar-22.jpg') ;?>" width="140" height="200" />
											<span>Transport Chair </span>
										</div> -->

										<!-- Ambulatory-->
										<!-- <div class="ambulatory" id="ambulatory">
											<img src="<?php echo base_url('assets/carousel_img/dakar-23.jpg') ;?>" width="140" height="200" />
											<span>3in1 Bed Side Commode </span>
										</div>
										<div class="ambulatory" id="">
											<img src="<?php echo base_url('assets/carousel_img/dakar-24.jpg') ;?>" width="140" height="200" />
											<span>Shower chair </span>
										</div>
										<div class="ambulatory" >
											<img src="<?php echo base_url('assets/carousel_img/dakar-25.jpg') ;?>" width="140" height="200" />
											<span>Shower chair with Wheels </span>
										</div>

										<div class="ambulatory" >
											<img src="<?php echo base_url('assets/carousel_img/dakar-26.jpg') ;?>" width="140" height="200" />
											<span>Walker with Wheels </span>
										</div>

										<div class="ambulatory" >
											<img src="<?php echo base_url('assets/carousel_img/dakar-27.jpg') ;?>" width="140" height="200" />
											<span>•Rollater</span>
										</div>
 -->
										
									<!-- </div>

									<a href="#" id="prev"></a>
									<a href="#" id="next"></a>
								</div>
							</div>  -->



					</div>

					<div class="page-shadow">
						
					</div>
			</div>
		</div>
	</div>
</section>


