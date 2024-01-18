<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/jquery/bootstrap.js"></script>
<script src="<?php echo base_url(); ?>assets/js/libs/screenfull.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/jquery.maskedinput.js'); ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-ui.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.form.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/lib/moment-js/moment.min.js"></script>
<link href="<?php echo base_url(); ?>assets/lib/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="<?php echo base_url(); ?>assets/lib/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

<!-- Common Scripts -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/global.modal/global.modal.css" />
<script src="<?php echo base_url(); ?>assets/js/global.modal/global.modal.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery-alerts/jquery.alerts.js"></script>
<script src="<?php echo base_url(); ?>assets/js/common.js?_=<?php echo date(“YmdH”); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/equipments.js?_=<?php echo date(“YmdH”); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/order_summary.js?_=<?php echo date(“YmdH”); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/billing.js?_=<?php echo date(“YmdH”); ?>"></script>
<script src="<?php echo base_url(); ?>assets/js/hospice.js?_=<?php echo date(“YmdH”); ?>"></script>

<!-- push notification scripts -->
<script src="<?php echo base_url(); ?>pushnotification.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/tagsinput.js"></script>
<script src="<?php echo base_url(); ?>assets/js/service_location.js"></script>
<script src="<?php echo base_url(); ?>assets/js/residence-status.js"></script>
<script src="<?php echo base_url(); ?>assets/js/order.js"></script>

<!-- magic slider script -->
<script src="<?php echo base_url('assets/js/jquery.carouFredSel-6.1.0-packed.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/magic_carousel.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/jquery.prettyPhoto.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/js/jquery.ui.touch-punch.min.js'); ?>" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/mindmup-editabletable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/numeric-input-example.js"></script>

<!-- Datatable Plugin for Date Sorting -->
<script src="<?php echo base_url(); ?>assets/js/date-eu.js"></script>

<!-- GOOGLE API for the auto detect of zip code -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlDUpWlwpKC5bwsXilUPAQd9uGan5jnaM"></script>
<script src="<?php echo base_url(); ?>assets/js/morris/raphael-min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/morris/morris.min.js" type="text/javascript"></script>
<script src='<?php echo base_url();?>assets/js/fullcalendar/fullcalendar.min.js'></script>

<script type="text/javascript">
  var base_url = "<?php echo base_url(); ?>";

  +function ($) {
    $(function(){
      // class
      $(document).on('click', '[data-toggle^="class"]', function(e){
        e && e.preventDefault();
        console.log('abc');
        var $this = $(e.target), $class , $target, $tmp, $classes, $targets;
        !$this.data('toggle') && ($this = $this.closest('[data-toggle^="class"]'));
        $class = $this.data()['toggle'];
        $target = $this.data('target') || $this.attr('href');
        $class && ($tmp = $class.split(':')[1]) && ($classes = $tmp.split(','));
        $target && ($targets = $target.split(','));
        $classes && $classes.length && $.each($targets, function( index, value ) {
          if ( $classes[index].indexOf( '*' ) !== -1 ) {
            var patt = new RegExp( '\\s' +
                $classes[index].
                  replace( /\*/g, '[A-Za-z0-9-_]+' ).
                  split( ' ' ).
                  join( '\\s|\\s' ) +
                '\\s', 'g' );
            $($this).each( function ( i, it ) {
              var cn = ' ' + it.className + ' ';
              while ( patt.test( cn ) ) {
                cn = cn.replace( patt, ' ' );
              }
              it.className = $.trim( cn );
            });
          }
          ($targets[index] !='#') && $($targets[index]).toggleClass($classes[index]) || $this.toggleClass($classes[index]);
        });
        $this.toggleClass('active');
      });

      // collapse nav
      $(document).on('click', 'nav a', function (e) {
        var $this = $(e.target), $active;
        $this.is('a') || ($this = $this.closest('a'));

        $active = $this.parent().siblings( ".active" );
        $active && $active.toggleClass('active').find('> ul:visible').slideUp(200);

        ($this.parent().hasClass('active') && $this.next().slideUp(200)) || $this.next().slideDown(200);
        $this.parent().toggleClass('active');

        $this.next().is('ul') && e.preventDefault();

        setTimeout(function(){ $(document).trigger('updateNav'); }, 300);
      });
    });
  }(jQuery);
</script>

<script type="text/javascript">

  $(document).ready(function(){
    $('.folded-portion').click(function(){
        if($(this).hasClass("active"))
        {
          $('.nav').css('width',"200px");
        }
        else
        {
          $('.nav').css('width','');
        }

        if($('#app').hasClass('app-aside-folded'))
        {
           $('#app').css('backgroundPosition','200px');
           $('.collapse-brand-img').css('display','none');
           $('.uncollapse-brand-img').css('display','block');
           $('.provider_txt').show();
        }
        else
        {
           $('#app').css('backgroundPosition','60px 50%');
           $('.collapse-brand-img').css('display','block');
           $('.uncollapse-brand-img').css('display','none');
           $('.provider_txt').hide();
           $(".navi ul.main-nav>li").removeClass("active");

        }
    });
  });

</script>

</body>
</html>