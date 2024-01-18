
<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/jquery.js') ;?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js') ;?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/bootstrap/js/bootstrap.js') ;?>"></script>
<script src="<?php echo base_url('assets/js/jquery.singlePageNav.js') ;?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/jquery.carouFredSel-6.0.4-packed.js') ;?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/common.js') ;?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/jquery.validate.min.js') ;?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/custom_validation.js') ;?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/jquery.maskedinput.js') ;?>" type="text/javascript"></script>

<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js') ;?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.dataTables.min.js') ;?>"></script>

<!-- magic slider script -->
<script src="<?php echo base_url('assets/js/jquery.carouFredSel-6.1.0-packed.js') ;?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/magic_carousel.js') ;?>"></script>
<script src="<?php echo base_url('assets/js/jquery.prettyPhoto.js') ;?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/jquery.ui.touch-punch.min.js') ;?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/order_summary.js') ;?>"></script>
<script src="<?php echo base_url('assets/js/jquery.form.js') ;?>"></script>
<script src="<?php echo base_url('assets/js/jquery.sticky-kit.js') ;?>"></script>
<script src="<?php echo base_url('assets/js/global.modal/global.modal.js') ;?>"></script>
<script src="<?php echo base_url('assets/js/jquery-alerts/jquery.alerts.js') ;?>"></script>

<script src="<?php echo base_url('assets/js/equipments.js') ;?>"></script>


<!-- magic slider script -->


<script type="text/javascript">

 	//** alert after the action (client_order.php)
    var url="<?php echo base_url();?>";

   $('.delete-orders').bind('click',function(){
   		var id = $(this).attr("data-id");
   		var this_element = $(this);
    	jConfirm('Are you sure you want to delete this entry?','Warning!', function(response){
    		if(response)
    		{
    			$.post(url + "client_order/delete_order/" + id, function(response){
    					var obj = $.parseJSON(response);
    					jAlert(obj['message'],'Delete Response');

    					if(obj['error']==0)
    					{
    						this_element.parent().parent().fadeOut(500,function(){
    							$(this).remove();
    						});
    					}
    			});
    		}
    	});
   });

   $('.delete-confirmed-orders').bind('click',function(){
   		var id = $(this).attr("data-id");
   		var this_element = $(this);
    	jConfirm('Are you sure you want to delete this entry?','Warning!', function(response){
    		if(response)
    		{
    			$.post(url + "client_order/delete_confirmed_order/" + id, function(response){
    					var obj = $.parseJSON(response);
    					jAlert(obj['message'],'Delete Response');

    					if(obj['error']==0)
    					{
    						this_element.parent().parent().fadeOut(500,function(){
    							$(this).remove();
    						});
    					}
    			});
    		}
    	});
   });

    $('.retrieve-orders').bind('click',function(){
   		var id = $(this).attr("data-id");
   		var this_element = $(this);
    	jConfirm('Are you sure you want to restore this entry?','Note', function(response){
    		if(response)
    		{
    			$.post(url + "client_order/restore_order/" + id, function(response){

    					var obj = $.parseJSON(response);
    					jAlert(obj['message'],'Restore Response');

    					if(obj['error']==0)
    					{
    							this_element.parent().parent().fadeOut(500,function(){
    							$(this).remove();
    						});
    					}
    			});
    		}
    	});
   });


    $('.delete-trash').bind('click',function(){
   		var id = $(this).attr("data-id");
   		var this_element = $(this);
    	jConfirm('This entry will be deleted permanently. <br />You want to proceed?','Note', function(response){
    		if(response)
    		{
    			$.post(url + "client_order/delete_trash/" + id, function(response){

    					var obj = $.parseJSON(response);
    					jAlert(obj['message'],'Delete Response');

    					if(obj['error']==0)
    					{
    							this_element.parent().parent().fadeOut(500,function(){
    								$(this).remove();
								});
    					}
    			});
    		}
    	});
   });

    $('.delete-users').bind('click',function(){
   		var id = $(this).attr("data-id");
   		var this_element = $(this);
    	jConfirm('This entry will be deleted permanently. <br />You want to proceed?','Note', function(response){
    		if(response)
    		{
    			$.post(url + "user/delete_user/" + id, function(response){

    					var obj = $.parseJSON(response);
    					jAlert(obj['message'],'Delete Response');

    					if(obj['error']==0)
    					{
    							this_element.parent().parent().fadeOut(500,function(){
    								$(this).remove();
								});
    					}
    			});
    		}
    	});
   });

    //for the edit of the equipment
    $('.equip_update_btn').bind('click',function(){
    	var id = $(this).attr("data-id");
   		var modal_id = $('#edit_equip' + id);
   		var this_element = $(this);
   		var form_data = $('#edit-equip-form' + id).serialize();

    	jConfirm('Do you want to save changes?','Note', function(response){
	    	if(response)
	    	{
	    		console.log(id);
	    		$.post(url + "admin/equipment/edit/" + id, form_data ,function(response){

					var obj = $.parseJSON(response);
					jAlert(obj['message'],'Edit Response');

					if(obj['error']==0)
					{
						setTimeout(function(){
							modal_id.modal("hide");
							location.reload();
						},1000);

					}
	    		});
			}
		});
   });

    //for the delete of the equipment
    $('.delete-equip').bind('click',function(){
   		var id = $(this).attr("data-id");
   		var this_element = $(this);

    	jConfirm('This entry will be deleted permanently. <br />You want to proceed?','Note', function(response){
    		if(response)
    		{
    			$.post(url + "admin/equipment/delete_equipment/" + id, function(response){

					var obj = $.parseJSON(response);
					jAlert(obj['message'],'Delete Response');

					if(obj['error']==0)
					{
						this_element.parent().parent().fadeOut(500,function(){
							$(this).remove();
						});
					}
    			});
    		}
    	});
   });



   $('.btn_change_status').bind('click',function(){
   		var id = $(this).attr("data-id");
   		var status = $(this).attr("data-status");

   		var this_element = $(this);
    	jConfirm('Are you sure you want the change the status of this?','Alert', function(response){
    		if(response)
    		{
    			$.post(url + "client_order/change_order_status/" + id + "/" + status, function(response){
    					var obj = $.parseJSON(response);
    					jAlert(obj['message'],'Edit Response');

    					if(obj['error']==0)
    					{
    						if(status == 'pending')
    						{
    							this_element.attr("value","Change Status to Active");
    							location.reload();
    						}
    						else if(status == 'active')
    						{
    							this_element.attr("value","Change Status to Confirmed");
    							location.reload();
    						}
    						else
    						{
    							this_element.attr("");
    							location.reload();
    						}

    					}
    			});
    		}
    	});
   });

   //for the assigning of equipments
   $('.btn-save-equipment').bind('click',function(){
   		var id = $(this).attr('data-id');
   		var form_data = $('#assign_equip_form' + id).serialize();

   		var this_element = $(this);

   		jConfirm('Do you want to save changes now?', 'Reminder', function(response){
   			if(response)
   			{
   				$.post(url + 'admin/equipment/assign_equipment/' + id, form_data, function(response){
   					var obj = $.parseJSON(response);
   					jAlert(obj['message'],'Delete Response');
   					if(obj['error'] == 0)
   					{
   						setTimeout(function(){
   							location.reload();
   						},1000);
   					}
   				});
   			}
   		});
   });

</script>


<script type="text/javascript">
		$(document).ready(function(){
		    $('#user_table').dataTable();
		});

		$(document).ready(function(){
		    $('#assign_equip').dataTable({
		    	 "aLengthMenu": [[25, 50, 75, 100], [25, 50, 75, "All"]],
       			 "iDisplayLength": 100
		    });
		});


		$(document).ready(function(){
		    $('#order_table').dataTable({
				 "aaSorting": []
			});
		});

		$(document).ready(function(){
		    $('#confirmed_table').dataTable({
				 "aaSorting": []
			});
		});


			$(function() {
				$( ".datepicker" ).datepicker({
					dateFormat: 'yy-mm-dd',
					minDate: 0
				});
			  });

			$(function() {
				$( ".datepicker2" ).datepicker({
					dateFormat: 'yy-mm-dd'
				});
			  });


			$(function() {
				$('.carousels').each(function() {
					var $cfs = $(this);
					$cfs.carouFredSel({
						direction: 'up',
						circular: false,
						infinite: false,
						align: false,
						width: 275,
						height: 250,
						items: 1,
						auto: false,
						scroll: {
							queue: 'last'
						}
					});
					$cfs.hover(
						function() {
							$cfs.trigger('next');
						},
						function() {
							$cfs.trigger('prev');
						}
					);
				});
			});

			$("#sticky_item").stick_in_parent();
		</script>



<script type="text/javascript">

		// magic slider script

		jQuery(function() {

		   jQuery('#magic_carousel_white').magic_carousel({
			width: 1100,
			height: 414,
			imageWidth:452,
			imageHeight:302,
			border:5,
			borderColorOFF:'#000000',
			borderColorON:'#FFFFFF',
			autoPlay: 7,
			autoHideBottomNav:false,
			showElementTitle:true,
			showPreviewThumbs:false,
			titleColor:'#333333',
			verticalAdjustment:50,
			numberOfVisibleItems:5,
			nextPrevMarginTop:5,
			playMovieMarginTop:0,
			bottomNavMarginBottom:-8
		   });

		   jQuery(document).ready(function(){
			jQuery("a[rel^='prettyPhoto']").prettyPhoto({
			 default_width: jQuery(window).width()/2,
			 default_height: jQuery(window).width()/2*9/16,
			 social_tools:false,
			 callback: function(){
			  jQuery.magic_carousel.continueAutoplay();
			 }
			});
		   });

		  });
		 // magic slider script



	  if ( ! window.console ) console = { log: function(){} };

        // The actual plugin
        $('#single-page-nav').singlePageNav({
		    offset: $('#single-page-nav').outerHeight() + 30,
            filter: ':not(.external)',
            updateHash: true,
            beforeStart: function() {
                console.log('begin scrolling');
            },
            onComplete: function() {
                console.log('done scrolling');
            }
        });

		 $('#single-page-nav2').singlePageNav({
		 offset: $('#single-page-nav2').outerHeight() + 10,
            filter: ':not(.external)',
            updateHash: true,
            beforeStart: function() {
                console.log('begin scrolling');
            },
            onComplete: function() {
                console.log('done scrolling');
            }
        });


		   $(function() {
				var _visible = 5;
				var $pagers = $('#pager a');
				var _onBefore = function() {
				 $(this).find('img').stop().fadeTo( 300, 1 );
				 $pagers.removeClass( 'selected' );
				};

				$('#carousel').carouFredSel({
				 items: _visible,
				 width: '100%',
				 auto: false,
				 scroll: {
				  duration: 750
				 },
				 prev: {
				  button: '#prev',
				  items: 2,
				  onBefore: _onBefore
				 },
				 next: {
				  button: '#next',
				  items: 2,
				  onBefore: _onBefore
				 },
				});

				$pagers.click(function( e ) {
				 e.preventDefault();

				 var group = $(this).attr( 'href' ).slice( 1 );
				 var slides = $('#carousel div.' + group);
				 var deviation = Math.floor( ( _visible - slides.length ) / 2 );
				 if ( deviation < 0 ) {
				  deviation = 0;
				 }

				 $('#carousel').trigger( 'slideTo', [ $('#' + group), -deviation ] );
				 $('#carousel div img').stop().fadeTo( 300, 0.3 );
				 slides.find('img').stop().fadeTo( 300, 1 );

				 $(this).addClass( 'selected' );
				});
		   });
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$('.dropdown-toggle').dropdown();
	});



	$("#login_btn").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "user/user_login";

	});

	$(".about-us-gallery").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + '#about-us';

	});

	$(".mission-gallery").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "#mission";

	});


	$(".contact-gallery").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "#request-quote";

	});

	$("#admin-order_btn").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "client_order";

	});





	$("#photo_gallery").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "photo_gallery/gallery";

	});

	$("#landingpage_gallery").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "ahmslv/guest_gallery/beds";

	});

	$("#gallery2").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "guest_gallery/beds";

	});

	$(".beds").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "guest_gallery/beds";

	});

	$(".oxygen").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "guest_gallery/oxygen";

	});

	$(".wheelchairs").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "guest_gallery/wheelchair";

	});

	$(".respiratory").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "guest_gallery/respiratory";

	});


	$(".mattress").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "guest_gallery/walkaids";

	});

	$(".ambulatory").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "guest_gallery/bath_aids";

	});

	$(".hydraulic-lifts").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "guest_gallery/lift_slings";

	});

	$(".commode-sling").on('click',function(){
		var base_url = "<?php echo base_url() ;?>";

		window.location.href = base_url + "guest_gallery/commode";

	});



	$('#account_type_dropdown').on('change',function(){
		if($(this).val() == 'hospice_admin' || $(this).val() == 'hospice_user')
		{
			$('#group_div').css('display','block');
			$('#group_div_companies').css('display','none');
		}
		else if($(this).val() == 'company_admin' || $(this).val() == 'company_user')
		{
			$('#group_div_companies').css('display','block');
			$('#group_div').css('display','none');
		}
		else
		{
			$('#group_div_companies').css('display','none');
			$('#group_div').css('display','none');
		}
	});


	//** gamit para kuhaon ang value sa option nga gi dynamic **/
	$("#groupname_select").bind("change",function(){
	      $("#hdnGroup_name").val(this.options[this.selectedIndex].text);
	      //alert(this.options[this.selectedIndex].text);
	});

	//** gamit para kuhaon ang value sa option nga gi dynamic **/
	$(".edit_hospicename").bind("change",function(){
	      //console.log('nisud');
		  $(".edit_hospice_name").val(this.options[this.selectedIndex].text);
	      //alert(this.options[this.selectedIndex].text);
	});

	$('.edit_password').bind('click',function(){
		$(this).val("");
	});



	var iframe_base_url = "<?php echo base_url() ;?>";
	var isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
		}
	};

	if(isMobile.any())
	{
		$('#video_iframe').attr('src', iframe_base_url + "assets/img/iframe_image.png");
		$('#video_container').css('margin-top', '0px');
		$('.videos-container').css('height', '800px');

	}
	else
	{
		//$('#video_iframe').attr('src', iframe_base_url + "assets/img/iframe_image.png");
		$('#video_iframe').attr('src', "//www.youtube.com/embed/pXSTgXXEYKI?autoplay=1&loop=1&playlist=pXSTgXXEYKI&modestbranding=1&autoplay=1&html5=1&wmode=opaque&hd=1&rel=0&autohide=1&showinfo=0");
	}



</script>






</body>
</html>


