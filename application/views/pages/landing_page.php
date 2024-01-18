<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmarterChoice</title>
    <!-- Bootstrap -->
    <link href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/landing_page/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- MasterSlider -->
    <link href="<?php echo base_url();?>assets/landing_page/libs/masterslider/style/masterslider.css" rel="stylesheet">
    <link href="<?php echo base_url();?>assets/landing_page/libs/masterslider/skins/default/style.css" rel="stylesheet">
    <!-- <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,400i,700,700i" rel="stylesheet"> -->
    <link href='<?php echo base_url();?>assets/css/railway.css' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url();?>assets/landing_page/style.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div id="skrollr-body" class="page-container">
    <nav class="navbar navbar-nox navbar-fixed-top anime">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">
            <img class="logo-default img-responsive anime" src="<?php echo base_url();?>assets/landing_page/assets/smarterchoice-logo.png">
            <img class="logo-affix img-responsive anime" src="<?php echo base_url();?>assets/landing_page/assets/smarterchoice-logo-affix.png">
          </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Home</a></li>
            <li><a href="#getInTouch">Contact us</a></li>
            <li><a href="<?php echo base_url();?>main/login">Login</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div id="banner">

        <div class="bg-overlay-top"></div>
<!-- masterslider -->
  <div class="master-slider ms-skin-default" id="HeroSlider">

           <!-- new slide -->
    <div class="ms-slide">
         <div class="bg-overlay" style="z-index: 106;"></div>
        <!-- slide background -->
        <img src="assets/landing_page/libs/masterslider/style/blank.gif" data-src="assets/landing_page/assets/banner5.jpg" alt="superior quality service" />

                   <!-- slide img layer -->
        <img class="ms-layer hidden-xs"
               data-offset-x      = "0"
               data-offset-y      = "0"
               data-origin        = "mc"
               data-type          = "image"
               data-duration      = "1000"
               data-ease          = "easeOutQuart"
               data-parallax      = '-2'
               data-delay         = "0"
               src                ="assets/landing_page/libs/masterslider/style/blank.gif"
               data-src           ="assets/landing_page/assets/banner5.jpg"
               style="min-width: 100vw"
                >
          </img>

                  <!-- slide img layer -->
        <img class="ms-layer banner-img"
               data-offset-x      = "-30"
               data-offset-y      = "0"
               data-origin        = "br"
               data-type          = "image"
               data-effect        = "right(90)"
               data-duration      = "1000"
               data-ease          = "easeOutQuart"
               data-parallax      = '3'
               data-delay         = "0"
               src                ="assets/landing_page/libs/masterslider/style/blank.gif"
               data-src           ="assets/landing_page/assets/banner5-1.png"
               style="min-height:400px"
                >
          </img>


        <!-- slide text layer -->
          <div class="ms-layer"
               data-offset-x      = "0"
               data-offset-y      = "30"
               data-origin        = "ml"
               data-type          = "text"
               data-effect        = "left(90)"
               data-duration      = "1000"
               data-ease          = "easeOutQuart"
               data-parallax      = '-1'
               data-delay         = "10"
                >
              <div class="text-heading text-white container-sm">
                  <span class="banner-title text-uppercase mb15">Innovative Solutions For Your Hospice</span><br><br>
                  <small class="banner-desc">A cloud-based software to efficiently manage your staff &amp; customers — save time and save money!</small><br><br><br>
                  <a href="#" class="btn btn-outline mb30 hidden">LEARN MORE</a>
              </div>
          </div>

    </div>
    <!-- end of slide -->

  </div>
  <!-- end of masterslider -->
    </div>

    <!-- Start: section -->
    <section class="section fullBG pt50 pb50" style="background-image:url('<?php echo base_url();?>assets/landing_page/assets/bg1.jpg');">
        <div class="container">
            <div class="container-sm text-center mb30 hasIntro" data-vp-add-class="animated slideInUp">
              <h3 class="font-thin text-uppercase">Cloud-Based Healthcare Software For Your Hospice Needs</h3>
              <p>Manage and automate collaborative work with our innovative solutions</p>
            </div>
          <div class="container-sm hasIntro" data-vp-add-class="animated slideInUp">
                <img class="img-responsive" src="<?php echo base_url();?>assets/landing_page/assets/feature1.png">
            </div>
          <div class="container-sm">
              <div class="row ">
                <div class="col-xs-3 col-sm-3 hasIntro" data-vp-add-class="animated slideInUp">
                    <div class="text-center">
                          <div class="nox-circle nox-circle-sm"><div class="fa fa-vcard-o text-warning"></div></div>
                        <div class="font-sm text-muted letter-space1">Customer Management </div>
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3 hasIntro" data-vp-add-class="animated slideInUp">
                    <div class="text-center">
                          <div class="nox-circle nox-circle-sm"><div class="fa fa-calendar-check-o text-info"></div></div>
                        <div class="font-sm text-muted letter-space1">Order Status </div>
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3 hasIntro" data-vp-add-class="animated slideInUp">
                    <div class="text-center">
                          <div class="nox-circle nox-circle-sm"><div class="fa fa-bar-chart text-success"></div></div>
                        <div class="font-sm text-muted letter-space1">Reports</div>
                    </div>
                </div>
                <div class="col-xs-3 col-sm-3 hasIntro" data-vp-add-class="animated slideInUp">
                    <div class="text-center">
                          <div class="nox-circle nox-circle-sm"><div class="fa fa-bell-o text-danger"></div></div>
                        <div class="font-sm text-muted letter-space1">Instant Notification</div>
                    </div>
                </div>

              </div>
          </div>
       </div>
    </section>
    <!-- End: section -->

    <!-- Start: section -->
    <section class="section pt50" style="overflow: hidden;background-color: #fafafa">
        <div class="section-content container">
          <div class="row">
            <div class="col-sm-6 hasIntro position-rel mb30" data-vp-add-class="animated slideInUp">
              <div class="app-image" style="max-height:550px; overflow: hidden; position: absolute;">
                <img class="img-responsive" src="<?php echo base_url();?>assets/landing_page/assets/app1.png">
              </div>
            </div>
            <div class="col-sm-5 col-sm-offset-1X">
              <div class=" hasIntro" data-vp-add-class="animated slideInUp">
                <div class="font-xs text-uppercase letter-space2">Feature-rich Software</div>
                <h2 class="">Comprehensive Hospice Solution </h2>
                <p class="">Designed for hospice agencies to maximize operational efficiency. This cloud-based system enhances your staff to work more efficiently while providing accountability. </p>
              </div>
                <div class="icon-box mt30 hasIntro" data-vp-add-class="animated slideInUp">
                     <i class="icon-box-img text-nox-primary fa fa-puzzle-piece"></i>
                     <div class="icon-box-content">
                         <h4 class="icon-box-title">Automated &amp; Streamlined Workflow</h4>
                         <p class="icon-box-desc">Save time! We have simplified and remove tedious and complicated processes so you can focus more on what matter most. </p>
                     </div>
                </div>
                <div class="icon-box hasIntro" data-vp-add-class="animated slideInUp">
                     <i class="icon-box-img text-nox-primary fa fa-heart-o"></i>
                     <div class="icon-box-content">
                       <h4 class="icon-box-title">User-friendly &amp; Intuitive Design</h4>
                       <p class="icon-box-desc">Easy-to-use interface to boost your productivity — work smarter not harder. </p>
                     </div>
                </div>

                <div class="text-right">
                  <a href="#" class="btn btn-primary btn-outline hasIntro" data-vp-add-class="animated slideInRight"> Request A Demo</a>
                </div>
            </div>
          </div>
          </div>
    </section>
    <!-- End: section -->

    <!-- Start: section -->
    <section class="section pt50 pb50" style="">
        <div class="container">
          <div class="row">
            <div class="col-sm-6 col-sm-push-5 col-sm-offset-1 position-rel">
                <div class="position-abs position-abs-bc pr20 pb30 hasIntro" data-vp-add-class="animated slideInUp" style="max-width: 40%; margin:auto;">
                  <img class="img-responsive" src="<?php echo base_url();?>assets/landing_page/assets/img-profile2.png">
                </div>
                <div class="position-abs position-abs-mc hasIntro" data-vp-add-class="animated slideInUp" style="max-width: 30%; margin-left:20%; margin-top: 10%;">
                  <img class="img-responsive" src="<?php echo base_url();?>assets/landing_page/assets/img-profile.png">
                </div>
                <div class="text-center pt50" style="max-width: 80%; margin:auto;"><img class="img-responsive" src="<?php echo base_url();?>assets/landing_page/assets/patient-order-summary.png"></div>
            </div>
            <div class="col-sm-5 col-sm-pull-7 hasIntro pt50" data-vp-add-class="animated slideInLeft">
                <div class="font-xs text-uppercase letter-space2">Quality Customer Care</div>
                <h2 class="">Better Customer Management</h2>
                <p class="">Improves productivity and simplifies business process. Efficient tracking of customer information and order summaries in detailed view and allows you to organize customer notes. </p>
                <ul class="icon-list list-unstyled text-left">
                  <li>
                    <i class="fa fa-check-square-o text-success"></i> <div>Secure access to customer profile and order summaries</div></li>
                  <li>
                    <i class="fa fa-check-square-o text-success"></i> <div>Check schedule, delivery or pick-up</div>
                  </li>
                  <li>
                    <i class="fa fa-check-square-o text-success"></i> <div>Fast and easy equipment tracking</div>
                  </li>
                  <li>
                    <i class="fa fa-check-square-o text-success"></i> <div>Easy-to-use forms processing for faster transaction and orders</div>
                  </li>
                  <li>
                    <i class="fa fa-check-square-o text-success"></i> <div>Stay updated with instant notifications</div>
                  </li>
                  <li>
                    <i class="fa fa-check-square-o text-success"></i> <div>Accessible on computers, tablets and smart phones</div>
                  </li>
                </ul>
            </div>

          </div>
       </div>
    </section>
    <!-- End: section -->

     <!-- Start: section -->
    <section id="theEquipment" class="section parallax-container paraSlide"
    data-start="background-position: 50% 0%;"
    data-top-bottom="background-position: 50% 60%;"
    data-anchor-target="#theEquipment"
    style="background-color:#333; background-image:url('<?php echo base_url();?>assets/landing_page/assets/elderly-group.jpg'); padding:80px 0;"
    >
      <div class="bg-overlay darker"></div>
        <div class="section-content container text-white">

          <div id="equipmentSlider" class="carousel slide" data-ride="carousel">
            <!--
            <ol class="carousel-indicators">
              <li data-target="#equipmentSlider" data-slide-to="0" class="active"></li>
              <li data-target="#equipmentSlider" data-slide-to="1"></li>
              <li data-target="#equipmentSlider" data-slide-to="2"></li>
            </ol>
            -->

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="carousel-inner">
                      <div class="item active">

                         <div class="col-md-12 col-xs-12" style="cursor:pointer;">

                            <div class="col-md-3 col-xs-3">
                            <div class="carousel-img">
                              <img src="<?php echo base_url();?>assets/landing_page/assets/icons/beds.png" class="img-responsive">
                              <p class="carousel-caption"> Hospital Beds</p>
                            </div>
                            </div>
                            <div class="col-md-3 col-xs-3">
                          <div class="carousel-img">
                                <img src="<?php echo base_url();?>assets/landing_page/assets/icons/oxygen.png" class="img-responsive">
                                <p class="carousel-caption">Oxygen</p>
                          </div>
                            </div>
                            <div class="col-md-3 col-xs-3">
                           <div class="carousel-img">
                                <img src="<?php echo base_url();?>assets/landing_page/assets/icons/wheel.png" class="img-responsive">
                                <p class="carousel-caption">Wheelchairs</p>
                          </div>
                            </div>
                            <div class="col-md-3 col-xs-3">
                          <div class="carousel-img">
                                <img src="<?php echo base_url();?>assets/landing_page/assets/icons/suction.png" class="img-responsive">
                                <p  class="carousel-caption">Respiratory</p>
                          </div>
                            </div>

                         </div>
                      </div>
                      <div class="item">
                           <div class="col-md-12" style="cursor:pointer;">

                        <div class="col-md-3 col-xs-3">
                          <div class="carousel-img">
                                <img src="<?php echo base_url();?>assets/landing_page/assets/icons/walkaids.png" class="img-responsive">
                                <p class="carousel-caption">Walk Aids</p>
                          </div>
                            </div>
                            <div class="col-md-3 col-xs-3">
                          <div class="carousel-img">
                                <img src="<?php echo base_url();?>assets/landing_page/assets/icons/ambulatory.png" class="img-responsive">
                                <p  class="carousel-caption">Bath Aids</p>
                          </div>
                            </div>

                            <div class="col-md-3 col-xs-3">
                          <div class="carousel-img">
                                <img src="<?php echo base_url();?>assets/landing_page/assets/icons/lifts.png" class="img-responsive">
                                <p class="carousel-caption">Patient Lift</p>
                          </div>
                            </div>
                            <div class="col-md-3 col-xs-3">
                            <div class="carousel-img">
                                <img src="<?php echo base_url();?>assets/landing_page/assets/icons/sling.png" class="img-responsive">
                                <p class="carousel-caption"> Commode Sling</p>
                          </div>
                            </div>

                        </div>
                      </div>

                    </div>
            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#equipmentSlider" role="button" data-slide="prev">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <span class="sr-only">Prev</span>
            </a>
            <a class="right carousel-control" href="#equipmentSlider" role="button" data-slide="next">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              <span class="sr-only">Next</i></span>
            </a>
          </div>
       </div>
    </section>
    <!-- End: section -->


    <!-- Start: section -->
    <section id="theStat" class="section parallax-container paraSlide hidden"
    data-start="background-position: 50% 0%;"
    data-top-bottom="background-position: 50% 60%;"
    data-anchor-target="#theStat"
    style="background-color:#333; background-image:url('<?php echo base_url();?>assets/landing_page/assets/elderly-group.jpg'); padding:80px 0;"
    >
      <div class="bg-overlay darker"></div>
        <div class="section-content container text-white">
          <div class="table-grid vmiddle row text-center-sm">
            <div class="table-grid-cell col-sm-4 mb30 text-center hasIntro" data-vp-add-class="animated slideInUp">
                <span class="font-thin font-xxlg">People <span class="fa fa-heart-o text-danger ml15 mr15"></span></span>
                <img  style="max-height: 45px;margin-top:-15px" src="<?php echo base_url();?>assets/landing_page/assets/smarterchoice-logo.png">
            </div>
            <div class="table-grid-cell col-sm-6 col-sm-offset-2">
              <div class="col-sm-4 stat-count mb30">
                <div class="text-center">
                     <div class="nox-circle-icon"><img src="<?php echo base_url();?>assets/landing_page/assets/icon-hospice.png" class="img-responsive center-block" style="max-height: 40px;"></div>
                     <div class="font-xs font-thin text-muted letter-space3">HOSPICES</div>
                     <div class="nox-stat"><span id="stat-count1">23</span></div>
                </div>
              </div>
              <div class="col-sm-4 stat-count mb30">
                <div class="text-center">
                      <div class="nox-circle-icon"><img src="<?php echo base_url();?>assets/landing_page/assets/icon-patient.png" class="img-responsive center-block" style="max-height: 40px;"></div>
                    <div class="font-xs font-thin text-muted letter-space3">ACTIVE CUSTOMERS</div>
                     <div class="nox-stat"><span id="stat-count2">565</span></div>
                </div>
              </div>
              <div class="col-sm-4 stat-count mb30">
                <div class="text-center">
                     <div class="nox-circle-icon"><img src="<?php echo base_url();?>assets/landing_page/assets/icon-company.png" class="img-responsive center-block" style="max-height: 40px;"></div>
                    <div class="font-xs font-thin text-muted letter-space3">COMPANIES</div>
                     <div class="nox-stat"><span id="stat-count3">7</span></div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="text-center mt30 font-xs text-muted text-uppercase letter-space3">
                  * In Las Vegas alone
              </div>
            </div>
          </div>
          </div>
    </section>
    <!-- End: section -->




    <!-- Start: section -->
    <section class="section" style="background-color: #51c6ea">
        <div class="section-content container text-white">
          <div class="table-grid vmiddle row text-center-sm">
            <div class="table-grid-cell col-sm-12 text-center hasIntro mb15" data-vp-add-class="animated slideInLeft">
                <span class="font-thin font-xxlg" style="line-height: 1em">THE BEST SOLUTION FOR YOUR HOSPICE</span>
            </div>
          </div>
          </div>
    </section>
    <!-- End: section -->

    <!-- Start: section -->
    <section id="getInTouch" class="section parallax-container paraSlide"
    data-start="background-position: 50% 0%;"
    data-top-bottom="background-position: 50% 70%;"
    data-anchor-target="#getInTouch"
    style="background-color:#333; background-image:url('<?php echo base_url();?>assets/landing_page/assets/senior.jpg');"
    >
      <div class="bg-overlay darker" style="background-image: url('<?php echo base_url();?>assets/landing_page/assets/diagmonds.png'); opacity: .9"></div>
      <div class="container pt50 pb50">
            <div class="row">
              <div class="col-sm-4 mb50">
                  <h2 class="text-white text-center-sm">GET IN TOUCH</h2>
                  <div class="text-center-sm">We will get back to you as soon as possible</div>

                  <ul class="icon-list icon-list-lined mt30">
                      <li><div><i class="fa fa-phone text-nox-primary"></i> (702) 248-0056</div></li>
                      <li><div><i class="fa fa-envelope-o text-nox-primary"></i> support@smarterchoice.us</div></li>
                  </ul>
              </div>
              <div class="col-sm-3">
              </div>
              <div class="col-sm-5">
                <form class="form-cozy form-cozy-sunken contact-form">
                    <div class="form-group">
                      <div class="input-group">
                        <input type="text" name="name" class="form-control" placeholder="Name">
                        <div class="input-group-addon"><i class="fa fa-user"></i></div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <input type="email" name="email" class="form-control" placeholder="Email Address">
                        <div class="input-group-addon"><i class="fa fa-envelope-o"></i></div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group">
                        <textarea class="form-control" name="message" placeholder="Message"></textarea>
                        <div class="input-group-addon"><i class="fa fa-comment-o"></i></div>
                      </div>
                    </div>
                    <div class="text-right clearfix">
                      <button type="button" class="inquiry btn btn-primary"> SEND  <i class="fa fa-send-o"> </i></button>
                    </div>
                </form>

              </div>

            </div>
        </div>
    </section>

    <footer>
        <div class="footer-menu container pt50 pb50 hidden">
            <div class="row ">
              <div class="col-sm-3">
                content
              </div>
              <div class="col-sm-3">
                content
              </div>
              <div class="col-sm-3">
                content
              </div>
              <div class="col-sm-3">
                content
              </div>

            </div>
        </div>
        <div class="footer-bar">
          <div class="container">
              <div class="row">
                <div class="col-sm-6">
                    <div class="copyright text-center-sm">© 2016 SmarterChoice. All Rights Reserved.</div>
                </div>
                <div class="col-sm-6">
                    <div class="copyright text-right text-center-sm">Powered By: <a href="http://smartstart.us" target="_blank">SmartStart</a></div>
                </div>
              </div>
            </div>
        </div>
    </footer>
    </div><!-- END: page-container -->
    <!-- jQuery
    <script src="assets/js/jquery/jquery.min.js"></script>-->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
    <script src="<?php echo base_url() ?>assets/js/jquery/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/landing_page/libs/masterslider/masterslider.min.js"></script>
    <!--<script src="assets/js/countUp.min.js"></script>
     -->
    <script src="<?php echo base_url();?>assets/landing_page/libs/skrollr.min.js"></script>
    <script src="<?php echo base_url();?>assets/landing_page/libs/jquery.viewportchecker.min.js"></script>

    <link rel="stylesheet" href="<?php echo base_url();?>assets/js/preloader/preloader.css" />
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/preloader/preloader.js"></script>

  <script type="text/javascript">
    jQuery(function($){

      $('.inquiry').on('click',function(){
          me_message_v2({error:2,message:"Sending your message ..."});
          $.post("<?php echo base_url();?>contactus",$('.contact-form').serialize(),function(response){
            me_message_v2(response);
            if(response.error==0)
            {
              $('.contact-form')[0].reset();
            }
          });
      });

      //=== function to check if element existed
        jQuery.fn.exists = function() { return this.length > 0; };
        jQuery.fn.existNot = function() { return this.length == 0; };
        $(document).ready(function(){
          /*affix navbar-nox */
          $('.navbar-nox').affix();


          //skrollrSlider.refresh($('.paraSlide'));
            /*BANNER*/
            //if($('#HeroSlider').exists()){

              //*
          //initiate masterslider based on device/screensize
            var isMobile = false; //initiate as false
          // device detection
          if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
              || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) isMobile = true;

       var isSmallScreen  =parseInt($(window).width()) < 768;
       if(isMobile || isSmallScreen){
               var HeroSlider = new MasterSlider();
                  HeroSlider.setup('HeroSlider' , {
                      width:768,
                      height:512,
                      space:0,
                      speed:20,
                      loop:true,
                      layout:'autofill',//fullwidth
                      fullwidth:true,
                      parallaxMode:'mouse:x-only',
                      overPause:true,
                      swipe:false,
                      autoplay:false
                  });
                  //HeroSlider.control('arrows');
                  //HeroSlider.control('circletimer' , {color:"#FFFFFF" , stroke:2});
                  // add scroll parallax effect
                  MSScrollParallax.setup(HeroSlider,50,80,true);
               }else{
                   // Init Skrollr -parallax slider
                  var skrollrSlider = skrollr.init();

                  var HeroSlider = new MasterSlider();
                  HeroSlider.setup('HeroSlider' , {
                      width:1920,
                      height:800,
                      space:0,
                      speed:20,
                      loop:true,
                      layout:'fullwidth',//fullwidth
                      fullwidth:true,
                      parallaxMode:'mouse:x-only',
                      overPause:true,
                      swipe:false,
                      autoplay:false
                  });
                  //HeroSlider.control('arrows');
                  //HeroSlider.control('circletimer' , {color:"#FFFFFF" , stroke:2});
                  // add scroll parallax effect
                  MSScrollParallax.setup(HeroSlider,50,80,true);

               }
               //*/

            //}
           /*intro animation*/
           $('.hasIntro').viewportChecker();
           /*carousel*/
           $('#equipmentSlider').carousel()
          })
        $(window).load(function(){

            /*stat counts*/
            function startStatCount(){
              var countOptions = {
                useEasing : true,
                //prefix: '▴',
                suffix : ''
              };
              var countOptionsDown = {
                useEasing : true,
                //prefix: '▾',
                suffix : ''
              };
              var countStat1 = new CountUp("stat-count1", 0, 23, 0, 20, countOptions);
              countStat1.start();
              var countStat2 = new CountUp("stat-count2", 0, 565, 0, 25, countOptionsDown);
              countStat2.start();
              var countStat3 = new CountUp("stat-count3", 0, 7, 0, 15, countOptionsDown);
              countStat3.start();
            }

            $('.stat-count').viewportChecker({
                classToAdd: 'visible animated slideInUp', // Class to add to the elements when they are visible,
                callbackFunction: function(elem, action){
                   startStatCount();
                }, // Callback to do after a class was added to an element. Action will return "add" or "remove", depending if the class was added or removed
            });
        })

  // Resize events
  $(window).resize(function () {
      //$('.navbar-nox').affix('checkPosition');
  });
})
  </script>
  </body>
</html>