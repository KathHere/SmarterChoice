// magic slider script
function isNumberKey(evt){
  var charCode = (evt.which) ? evt.which : event.keyCode;

  if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode > 57))){
      return false;
  }
  return true;
}

jQuery(function($) {
      //turn to inline mode
      $.fn.editable.defaults.mode = 'inline';
      $("body").find('[rel="popover"]').popover();
      jQuery('#magic_carousel_white6').magic_carousel({
        responsive:true,
        responsiveRelativeToBrowser:false,
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
            //jQuery.magic_carousel.continueAutoplay();
          }
        });
      });

    //append a request id
    jQuery(document).ajaxSend(function(e, jqxhr, settings) {
      if(!settings.url.match(/reqid/i))
      {
        var rand_ = randomString(8);
        if(settings.url.match(/\?/))
        {
          settings.url += "&reqid="+rand_;
        }
        else
        {
          settings.url += "?reqid="+rand_;
        }
      }
    });

    //generate random string
    function randomString(length, special)
    {
        var iteration = 0;
        var randomstr = "";
        var randomNumber;
        if(typeof special === undefined)
        {
            var special = false;
        }
        while(iteration < length){
            randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;
            if(!special){
                if ((randomNumber >=33) && (randomNumber <=47)) { continue; }
                if ((randomNumber >=58) && (randomNumber <=64)) { continue; }
                if ((randomNumber >=91) && (randomNumber <=96)) { continue; }
                if ((randomNumber >=123) && (randomNumber <=126)) { continue; }
            }
            iteration++;
            randomstr += String.fromCharCode(randomNumber);
        }
        return randomstr;
    }

    $('.editable-click.editable-text').editable({
          emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          validate: function(value) {
              if($.trim(value) == '') {
                  return 'This field is required';
              }
          },
          success:function(response,newValue){
            console.log(response);
             if(response.error==1) return response.message;
             else window.location.reload();
          }
    });
    $('.editable-click.editable-selectable-duration').editable({
          emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          validate: function(value) {
              if($.trim(value) == '') {
                  return 'This field is required';
              }
          },
          success:function(response,newValue){
             if(response.error==1) return response.message;
          },
          source:[{value:'CONT',text:'Continous'},{value:'PRN',text:'PRN'}]
    });
    $('.editable-click.editable-noreload').editable({
          emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          validate: function(value) {
              if($.trim(value) == '') {
                  return 'This field is required';
              }
          },
          success:function(response,newValue){
            console.log(response);
             if(response.error==1) return response.message;
          }
    });
     var currdate = new Date();
     var n = currdate.getFullYear();
    $('.editable-click.editable-combodate').editable({
          emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          mode:'popup',
          combodate:{
              minYear: 1990,
              maxYear: n+5,
              minuteStep: 1
          },
          validate: function(value) {
              if($.trim(value) == '') {
                  return 'This field is required';
              }
          },
          success:function(response,newValue){
             if(response.error==1) return response.message;
             else window.location.reload();
          }
    });
    $('.editable-click.editable-combodate-notrequired').editable({
          emptytext:'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
          mode:'popup',
          combodate:{
              minYear: 1990,
              maxYear: n+5,
              minuteStep: 1
          },
          success:function(response,newValue){
             if(response.error==1) return response.message;
             else window.location.reload();
          }
    });

    });
  // magic slider script

    // magic slider script

jQuery(function() {

        $('body').on('click','nav.navi > ul.nav > li > a',function(){
              var href_ = $(this).attr("href");
              var n     = href_.indexOf("http");
              if(n > -1)
              {
                  $('body').prepend("<div class='bg-info link-preloader'><i class='fa fa-circle-o-notch fa-spin'></i> retrieving data ...</div>");
                  // return false;
              }
        });
      jQuery('#magic_carousel_white1').magic_carousel({
        responsive:true,
        responsiveRelativeToBrowser:false,
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

  // magic slider script

jQuery(function() {

      jQuery('#magic_carousel_white2').magic_carousel({
        responsive:true,
        responsiveRelativeToBrowser:false,
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

  jQuery(function() {

      jQuery('#magic_carousel_white3').magic_carousel({
        responsive:true,
        responsiveRelativeToBrowser:false,
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

  // magic slider script

  jQuery(function() {

      jQuery('#magic_carousel_white4').magic_carousel({
        responsive:true,
        responsiveRelativeToBrowser:false,
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

    // magic slider script

  jQuery(function() {

      jQuery('#magic_carousel_white5').magic_carousel({
        responsive:true,
        responsiveRelativeToBrowser:false,
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
  // magic slider scripta





$(document).ready(function(){

$(function(){
  $('.navi > .nav').slimScroll({
    height: '100vh'
  });
  // alert("SAMPLE");
});

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();


  /** Turn off auto complete for all input **/
  $(document).on('focus', ':input', function() {
    $(this).attr('autocomplete', 'off');
  });

  $('.select2-ready').select2();

  $('.select2-ready-modal').select2({
    dropdownParent: $("#create-manual-reconciliation-modal")
  });

  //Auto Detect City/State base on zip code for Create new order
  var geocoder = new google.maps.Geocoder();
  $('#p_postal').bind('keyup', function(){
      var $this = $(this);
      if ($this.val().length == 5)
      {
          geocoder.geocode({ 'address': $this.val() }, function (result, status) {
              var state = "N/A";
              var city = "N/A";
              //start loop to get state from zip
              for (var component in result[0]['address_components']) {
                  for (var i in result[0]['address_components'][component]['types']) {
                      if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1")
                      {
                          //alert(result[0]['address_components'][1]['long_name']);
                          state = result[0]['address_components'][component]['short_name'];
                          // do stuff with the state here!
                          $('#p_state').val(state);
                          // get city name
                          city = result[0]['address_components'][1]['long_name'];
                          // Insert city name into some input box
                          $('#p_city').val(city);
                      }
                  }
              }
          });
      }

      if($this.val() == "")
      {
        $('#p_state').val("");
        $('#p_city').val("");
      }
  });

  //Auto Detect ZIP for editing hospice details
  $('.edit_hospice_b_postal').bind('keyup', function(){
      var $this = $(this);
      var hospice_id = $this.attr("data-hospice-id");
      if ($this.val().length == 5)
      {
          geocoder.geocode({ 'address': $this.val() }, function (result, status) {
              var state = "N/A";
              var city = "N/A";
              //start loop to get state from zip
              for (var component in result[0]['address_components']) {
                  for (var i in result[0]['address_components'][component]['types']) {
                      if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1")
                      {
                          //alert(result[0]['address_components'][1]['long_name']);
                          state = result[0]['address_components'][component]['short_name'];
                          // do stuff with the state here!
                          $('.edit_hospice_b_state_'+hospice_id).val(state);
                          // get city name
                          city = result[0]['address_components'][1]['long_name'];
                          // Insert city name into some input box
                          $('.edit_hospice_b_city_'+hospice_id).val(city);
                      }
                  }
              }
          });
      }

      if($this.val() == "")
      {
        $('.edit_hospice_b_state_'+hospice_id).val("");
        $('.edit_hospice_b_city_'+hospice_id).val("");
      }
  });

  //Auto Detect ZIP for editing hospice details
  $('.edit_hospice_s_postal').bind('keyup', function(){
      var $this = $(this);
      var hospice_id = $this.attr("data-hospice-id");
      if ($this.val().length == 5)
      {
          geocoder.geocode({ 'address': $this.val() }, function (result, status) {
              var state = "N/A";
              var city = "N/A";
              //start loop to get state from zip
              for (var component in result[0]['address_components']) {
                  for (var i in result[0]['address_components'][component]['types']) {
                      if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1")
                      {
                          //alert(result[0]['address_components'][1]['long_name']);
                          state = result[0]['address_components'][component]['short_name'];
                          // do stuff with the state here!
                          $('.edit_hospice_s_state_'+hospice_id).val(state);
                          // get city name
                          city = result[0]['address_components'][1]['long_name'];
                          // Insert city name into some input box
                          $('.edit_hospice_s_city_'+hospice_id).val(city);
                      }
                  }
              }
          });
      }

      if($this.val() == "")
      {
        $('.edit_hospice_s_state_'+hospice_id).val("");
        $('.edit_hospice_s_city_'+hospice_id).val("");
      }
  });

  //Auto Detect ZIP for ptmove
  $('#postalcode_ptmove').bind('keyup', function(){
      var $this = $(this);
      if ($this.val().length == 5)
      {
          geocoder.geocode({ 'address': $this.val() }, function (result, status) {
              var state = "N/A";
              var city = "N/A";
              //start loop to get state from zip
              for (var component in result[0]['address_components']) {
                  for (var i in result[0]['address_components'][component]['types']) {
                      if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1")
                      {
                        //alert(result[0]['address_components'][1]['long_name']);
                        state = result[0]['address_components'][component]['short_name'];
                        // do stuff with the state here!
                        $('#state_ptmove').val(state);
                        // get city name
                        city = result[0]['address_components'][1]['long_name'];
                        // Insert city name into some input box
                        $('#city_ptmove').val(city);
                      }
                  }
              }
          });
      }
      if($this.val() == "")
      {
        $('#state_ptmove').val("");
        $('#city_ptmove').val("");
      }
  });

  //Auto Detect ZIP for Respite
  $('#postalcode_respite').bind('keyup', function(){
      var $this = $(this);
      if ($this.val().length == 5)
      {
          geocoder.geocode({ 'address': $this.val() }, function (result, status) {
              var state = "N/A";
              var city = "N/A";
              //start loop to get state from zip
              for (var component in result[0]['address_components']) {
                  for (var i in result[0]['address_components'][component]['types']) {
                      if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1")
                      {
                        //alert(result[0]['address_components'][1]['long_name']);
                        state = result[0]['address_components'][component]['short_name'];
                        // do stuff with the state here!
                        $('#state_respite').val(state);
                        // get city name
                        city = result[0]['address_components'][1]['long_name'];
                        // Insert city name into some input box
                        $('#city_respite').val(city);
                      }
                  }
              }
          });
      }
      if($this.val() == "")
      {
        $('#state_respite').val("");
        $('#city_respite').val("");
      }
  });


 //Auto Detect ZIP for EDIT PROFILE
  $('#edit_postal').bind('keyup', function(){
      var $this = $(this);
      if ($this.val().length == 5)
      {
          geocoder.geocode({ 'address': $this.val() }, function (result, status) {
              var state = "N/A";
              var city = "N/A";
              //start loop to get state from zip
              for (var component in result[0]['address_components']) {
                  for (var i in result[0]['address_components'][component]['types']) {
                      if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1")
                      {
                        //alert(result[0]['address_components'][1]['long_name']);
                        state = result[0]['address_components'][component]['short_name'];
                        // do stuff with the state here!
                        $('#edit_state').val(state);
                        // get city name
                        city = result[0]['address_components'][1]['long_name'];
                        // Insert city name into some input box
                        $('#edit_city').val(city);
                      }
                  }
              }
          });
      }
      if($this.val() == "")
      {
        $('#edit_state').val("");
        $('#edit_city').val("");
      }
  });


//Auto Detect ZIP for CONFIRM
  $('#postal_confirm').bind('keyup', function(){
      var $this = $(this);
      if ($this.val().length == 5)
      {
          geocoder.geocode({ 'address': $this.val() }, function (result, status) {
              var state = "N/A";
              var city = "N/A";
              //start loop to get state from zip
              for (var component in result[0]['address_components']) {
                  for (var i in result[0]['address_components'][component]['types']) {
                      if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1")
                      {
                        //alert(result[0]['address_components'][1]['long_name']);
                        state = result[0]['address_components'][component]['short_name'];
                        // do stuff with the state here!
                        $('#state_confirm').val(state);
                        // get city name
                        city = result[0]['address_components'][1]['long_name'];
                        // Insert city name into some input box
                        $('#city_confirm').val(city);
                      }
                  }
              }
          });
      }
      if($this.val() == "")
      {
        $('#state_confirm').val("");
        $('#city_confirm').val("");
      }
  });

//Auto Detect NEW CUS MOVE for CONFIRM
 $('#postal_pt').bind('keyup', function(){
      var $this = $(this);
      if ($this.val().length == 5)
      {
          geocoder.geocode({ 'address': $this.val() }, function (result, status) {
              var state = "N/A";
              var city = "N/A";
              //start loop to get state from zip
              for (var component in result[0]['address_components']) {
                  for (var i in result[0]['address_components'][component]['types']) {
                      if (result[0]['address_components'][component]['types'][i] == "administrative_area_level_1")
                      {
                        //alert(result[0]['address_components'][1]['long_name']);
                        state = result[0]['address_components'][component]['short_name'];
                        // do stuff with the state here!
                        $('#state_pt').val(state);
                        // get city name
                        city = result[0]['address_components'][1]['long_name'];
                        // Insert city name into some input box
                        $('#city_pt').val(city);
                      }
                  }
              }
          });
      }
      if($this.val() == "")
      {
        $('#state_pt').val("");
        $('#city_pt').val("");
      }
  });

  //END

  /** CAPITALIZE ALL INPUT TYPE DATA **/
  $("input[type=text]").blur(function(){
    var samp = $(this).attr('data-uni');
    if(samp != 1)
    {
      $(this).val($(this).val().toUpperCase());
    }
  });

  $('#login_uname').blur(function(){
    $(this).val($(this).val().toLowerCase());
  });

  $('#username_field').blur(function(){
    $(this).val($(this).val().toLowerCase());
  });

  $('.datatable_table').DataTable({
     columnDefs: [{
      type: 'date-euro',
      targets: 0,

    }],
    "bDestroy": true,
    "order": [[ 0, "desc" ]]
  })
  .on('init.dt',function(){
  });

  // var sessioned_account_type = $("body").find(".sessioned_account_type").val();
  // if(sessioned_account_type == "dme_admin" || sessioned_account_type == "dme_user")
  // {
  //   $('.patient_order_list_table').DataTable({
  //     columnDefs: [{
  //       type: 'date-euro',
  //       targets: 0,
  //     },
  //     {
  //       className: "hide_on_print", "targets": [6,7],
  //       className: "comment-container", "targets": 7
  //     }],
  //     "bDestroy": true,
  //     "order": [[ 0, "desc" ]],
  //     "processing": false,
  //     "serverSide": true,
  //     "responsive": true,
  //     "deferRender": true,
  //     // "search": {
  //     //   "regex": true
  //     // },
  //     "ajax": {
  //       url: base_url+"order/get_patient_order_status_list"
  //     },
  //     "columns": [
  //       { "data": "pickup_date" },
  //       { "data": "patient_lname" },
  //       { "data": "patient_fname" },
  //       { "data": "medical_record_id" },
  //       { "data": "activity_name" },
  //       { "data": "hospice_name" },
  //       { "data": "order_details_field" },
  //       { "data": "status_notes_field" },
  //       { "data": "order_status_field" }
  //     ]
  //   });
  // }
  // else
  // {
  //   $('.patient_order_list_table').DataTable({
  //     columnDefs: [{
  //       type: 'date-euro',
  //       targets: 0,
  //     },
  //     {
  //       className: "hide_on_print", "targets": [6,7]
  //     },
  //     {
  //       className: "comment-container", "targets": 7
  //     }],
  //     "bDestroy": true,
  //     "order": [[ 0, "desc" ]],
  //     "processing": false,
  //     "serverSide": true,
  //     "responsive": true,
  //     "deferRender": true,
  //     "ajax": {
  //         url: base_url+"order/get_patient_order_status_list"
  //     },
  //     "columns": [
  //       { "data": "pickup_date" },
  //       { "data": "patient_lname" },
  //       { "data": "patient_fname" },
  //       { "data": "medical_record_id" },
  //       { "data": "activity_name" },
  //       { "data": "order_details_field" },
  //       { "data": "status_notes_field" },
  //       { "data": "order_status_field" }
  //     ]
  //   });
  // }

  // var sessioned_account_type_confirm = $("body").find(".sessioned_account_type_confirm").val();
  // if(sessioned_account_type_confirm == "dme_admin" || sessioned_account_type_confirm == "dme_user")
  // {
  //   $('.list_tobe_confirmed_table').DataTable({
  //     columnDefs: [{
  //       type: 'date-euro',
  //       targets: 0,
  //     },
  //     {
  //       className: "hide_on_print", "targets": [6,7],
  //       className: "comment-container", "targets": 7
  //     }],
  //     "bDestroy": true,
  //     "order": [[ 0, "desc" ]],
  //     "processing": false,
  //     "serverSide": true,
  //     "responsive": true,
  //     "deferRender": true,
  //     "ajax": {
  //       url: base_url+"order/get_list_tobe_confirmed"
  //     },
  //     "columns": [
  //       { "data": "pickup_date" },
  //       { "data": "patient_lname" },
  //       { "data": "patient_fname" },
  //       { "data": "medical_record_id" },
  //       { "data": "activity_name" },
  //       { "data": "hospice_name" },
  //       { "data": "order_details_field" },
  //       { "data": "status_notes_field" },
  //       { "data": "order_status_field" }
  //     ]
  //   });
  // }
  // else
  // {
  //   $('.list_tobe_confirmed_table').DataTable({
  //     columnDefs: [{
  //       type: 'date-euro',
  //       targets: 0,
  //     },
  //     {
  //       className: "hide_on_print", "targets": [6,7]
  //     },
  //     {
  //       className: "comment-container", "targets": 7
  //     }],
  //     "bDestroy": true,
  //     "order": [[ 0, "desc" ]],
  //     "processing": false,
  //     "serverSide": true,
  //     "responsive": true,
  //     "deferRender": true,
  //     "ajax": {
  //       url: base_url+"order/get_list_tobe_confirmed"
  //     },
  //     "columns": [
  //       { "data": "pickup_date" },
  //       { "data": "patient_lname" },
  //       { "data": "patient_fname" },
  //       { "data": "medical_record_id" },
  //       { "data": "activity_name" },
  //       { "data": "order_details_field" },
  //       { "data": "status_notes_field" },
  //       { "data": "order_status_field" }
  //     ]
  //   });
  // }

//*****************************************
// START FOR THE EDIT PATIENT MR NO.
//*****************************************

//prevent spacebar on the MR# field
$('.edit_patient_mr_number_field').keypress(function(e) {
  if(e.which === 32)
    return false;
});
//end

/** Auto detect of the patient that is to be input exists in our database already **/
var check_if_patient_exists_new = function()
{
  $('.edit_patient_mr_number_field').bind('keyup', function(e){
    var _this = $(this);
    var this_val = $(this).val();
    var hdn_hospice_id = $('.hdn_hospice_id').val(); //newly added. To identify which hospice na belong ang i-create nga patient.

    if(this_val === "")
    {
      $('.edit_patient_mr_number_field').popover("hide");
      _this.attr("data-content","");
    }

    if(this_val.length > 1)
    {
      if(this_val !== "")
      {
        var value = this_val.toUpperCase();
        var current_mr_no = $("body").find(".save_edit_changes").attr('data-id');
        delay(function(){
          $.ajax({
            type:"POST",
            url:base_url+"main/check_existing_patient_new/"+value+"/"+hdn_hospice_id+"/"+current_mr_no,
            success:function(response)
            {
              _this.attr("data-content",response);
              if(response!="")
              {
                $('.edit_patient_mr_number_field').popover("show");
                $("body").find(".grey_inner_shadow").each(function(){
                  if($(this).attr("data-id") != "edit_patient_mr_number_field")
                  {
                    $(this).attr('disabled', true);
                  }
                });
              }
              else
              {
                $('.edit_patient_mr_number_field').popover("hide");
                $("body").find(".grey_inner_shadow").each(function(){
                  $(this).removeAttr('disabled');
                });
              }
            },
            error:function(jqXHR, textStatus, errorThrown)
            {
              console.log(textStatus, errorThrown);
            }

          });
        }, 500 );
      }
    }
  });
};

var showPopover_new = function(){
  $(this).popover('show');
}
, hidePopover_new = function(){
    $(this).popover('hide');
};


$('.edit_patient_mr_number_field').popover({
    trigger:"manual",
    html: true,
    placement:"top",
    content: function()
    {
      check_if_patient_exists_new();
    }
})
.focus(showPopover_new)
.blur(hidePopover_new);

//*****************************************
// END FOR THE EDIT PATIENT MR NO.
//*****************************************

/** Show tooltip for equipment OPTIONS **/
// $(".equipment_options_tooltip").tooltip();
// $('.patient_weight_required').tooltip();
// $('.lot_number_required').tooltip();


//prevent spacebar on the MR# field
$('#patient_mrn').keypress(function(e) {
    if(e.which === 32)
        return false;
});
//end


/** Auto detect of the patient that is to be input exists in our database already **/
var check_if_patient_exists = function()
{
  $('#patient_mrn').bind('keyup', function(e){
    var _this = $(this);
    var this_val = $(this).val();
    var hdn_hospice_id = $('#hdn_hospice_id').val(); //newly added. To identify which hospice na belong ang i-create nga patient.

    if(this_val === "")
    {
      $('#patient_mrn').popover("hide");
      _this.attr("data-content","");
    }

    if(this_val.length > 1)
    {
      if(this_val !== "")
      {
        var value = this_val.toUpperCase();
        delay(function(){
          $.ajax({
            type:"POST",
            url:base_url+"main/check_existing_patient/"+value+"/"+hdn_hospice_id,
            success:function(response)
            {
              _this.attr("data-content",response);
              if(response!="")
              {
                $('#patient_mrn').popover("show");
                $("body").find(".grey_inner_shadow").each(function(){
                  if($(this).attr("id") != "patient_mrn")
                  {
                    $(this).attr('disabled', true);
                  }
                });
              }
              else
              {
                $('#patient_mrn').popover("hide");
                $("body").find(".grey_inner_shadow").each(function(){
                  $(this).removeAttr('disabled');
                });
              }
            },
            error:function(jqXHR, textStatus, errorThrown)
            {
              console.log(textStatus, errorThrown);
            }

          });
        }, 500 );
      }
    }

 });

};

var showPopover = function(){
  $(this).popover('show');
}
, hidePopover = function(){
    $(this).popover('hide');
};


$('#patient_mrn').popover({
    trigger:"manual",
    html: true,
    placement:"top",
    content: function()
    {
      check_if_patient_exists();
    }
})
.focus(showPopover)
.blur(hidePopover);
// .hover(showPopover,hidePopover);

//check
// $('body').on('change', '#check_me_too', function()
// {
//   var val = $(this).attr('id');
//   console.log(val);
//   if(val == 'check_me_too')
//   {
//     $('body').find('#check2_me_too').prop('checked', true);
//     $(this).attr('id', 'checked_me_too');
//   }
// });

// //unchecked
// $('body').on('change', '#checked_me_too', function()
// {
//   var val = $(this).attr('id');
//   console.log(val);
//   if(val == 'checked_me_too')
//   {

//     $('body').find('#check2_me_too').prop('checked', false);
//     $(this).attr('id', 'check_me_too');
//   }
// });

// //check
// $('body').on('change', '#check2_me_too', function()
// {
//   var val = $(this).attr('id');
//   console.log(val);
//   if(val == 'check2_me_too')
//   {
//     $('body').find('#check_me_too').prop('checked', true);
//     $(this).attr('id', 'checked2_me_too');
//   }
// });

// //unchecked
// $('body').on('change', '#checked2_me_too', function()
// {
//   var val = $(this).attr('id');
//   console.log(val);
//   if(val == 'checked2_me_too')
//   {

//     $('body').find('#check_me_too').prop('checked', false);
//     $(this).attr('id', 'check2_me_too');
//   }
// });

//-------

//check
// $('body').on('change', '#check3_me_too', function()
// {
//   var val = $(this).attr('id');
//   // if(val == 'check3_me_too')
//   // {
//   //   $('body').find('#check4_me_too').prop('checked', true);
//   //   $(this).attr('id', 'checked3_me_too');
//   // }
// });

// //unchecked
// $('body').on('change', '#checked3_me_too', function()
// {
//   var val = $(this).attr('id');
//   if(val == 'checked3_me_too')
//   {
//     $('body').find('#check4_me_too').prop('checked', false);
//     $(this).attr('id', 'check3_me_too');
//   }
// });

// //check
// $('body').on('change', '#check4_me_too', function()
// {
//   var val = $(this).attr('id');
//   if(val == 'check4_me_too')
//   {
//     $('body').find('#check3_me_too').prop('checked', true);
//     $(this).attr('id', 'checked4_me_too');
//   }
// });

// //unchecked
// $('body').on('change', '#checked4_me_too', function()
// {
//   var val = $(this).attr('id');
//   if(val == 'checked4_me_too')
//   {
//     $('body').find('#check3_me_too').prop('checked', false);
//     $(this).attr('id', 'check4_me_too');
//   }
// });




$('body').on('mouseover','.equipment_options_hover',function(){
  var id = $(this).attr("data-id");
  var unique_id = $(this).attr("data-uniqueid");
  var differs = $(this).attr('data-differ');
  var _this = $(this);
  var dump = "";

  if(differs != undefined)
  {
    var differ = differs;
  }else{
    var differ = dump;
  }
  if(_this.text() != "Floor Mat")
  {
     $.ajax({
        url : base_url+"order/get_equipment_options/"+id+"/"+unique_id+"/"+differ,
        success:function(response)
        {
          if(response != "" && response != null)
          {
            _this.attr("data-title","Options");
            _this.siblings(".popover").find(".popover-content").html(response);
            _this.siblings(".popover").find(".popover-title").html("options");
          }
          else
          {
            _this.siblings(".popover").remove();
          }

        }
    });
  }

});

$('body').on('click','.equip_edit_options',function(e){
  e.preventDefault();
  var id = $(this).attr("data-id");
  var unique_id = $(this).attr("data-uniqueid");
  var differs = $(this).attr('data-differ');
  var _this = $(this);
  var dump = "";

  if(differs != undefined)
  {
    var differ = differs;
  }else{
    var differ = dump;
  }
  var uri = "equipmentID="+id+"&uniqueID="+unique_id+"&orderID="+differ;
  modalbox(base_url + 'order_extend/update_equipment_option_order/?' + uri ,{
          header:"Update Options",
          button: false,
          buttons:
          [{
            text: "Update",
            type: "primary",
            click: function() {
                closeModalbox();
            }
          },
          {
            text: "Close",
            type: "danger",
            click: function() {
                closeModalbox();
            }
          }]
  });

});


//for viewing of patient editing logs
$('.view_editing_logs').popover({
  trigger:"click",
  html: true
});

$('.view_editing_logs').bind('click',function(){
  var id = $(this).attr("data-patient-id");
  var _this = $(this);

  $.ajax({
      url : base_url+"order/show_patient_edit_logs/"+id,
      success:function(response)
      {
        if(response != "" || response != null)
        {
          $(_this).attr("data-title","Logs");
          $(_this).attr("data-content",response);
        }

      }
  });

});

  $('body').on('click','.edit_item_options',function(){
    var id = $(this).attr("data-id");
    var unique_id = $(this).attr("data-uniqueid");
    var medical_id = $(this).attr("data-medical-id");
    var patient_id = $(this).attr("data-patient-id");
    var _this = $(this);


    if(id==61 || id==62 || id==174 || id==29 || id==30 || id==31 || id==176 || id==36)
    {
      modalbox(base_url + 'order/edit_liter_flow/' + unique_id + "/" + id,{
          header:"Edit Liter Flow",
          button: true,
          buttons:
          [{
            text: "Close",
            type: "danger",
            click: function() {
                closeModalbox();
            }
          }]
      });
    }
    if(id==4)
    {
      modalbox(base_url + 'order/edit_bipap_option/' + unique_id + "/" + id,{
          header:"Edit BIPAP Option",
          button: true,
          buttons:
          [{
            text: "Close",
            type: "danger",
            click: function() {
                closeModalbox();
            }
          }]
      });
    }
    if(id==9)
    {
      modalbox(base_url + 'order/edit_cpap_option/' + unique_id + "/" + id,{
          header:"Edit CPAP Option",
          button: true,
          buttons:
          [{
            text: "Close",
            type: "danger",
            click: function() {
                closeModalbox();
            }
          }]
      });
    }
  });

  $('body').on('click','.edit_patient_weight',function(){
    var id = $(this).attr("data-id");
    var unique_id = $(this).attr("data-uniqueid");
    var medical_id = $(this).attr("data-medical-id");
    var patient_id = $(this).attr("data-patient-id");
    var _this = $(this);

    console.log(id,unique_id,medical_id,patient_id);


    modalbox(base_url + 'order/edit_patient_weight/' + unique_id + "/" + id + "/" + medical_id + "/" + patient_id,{
      header:"Edit Customer Weight",
      button: true,
      buttons:
      [{
        text: "Close",
        type: "danger",
        click: function() {
            closeModalbox();
        }
      }]
    });
  });

    $('body').on('click','.save_liter_flow',function(){
      var equipment_id = $(this).attr('data-id');
      var unique_id = $(this).attr('data-unique-id');
      var form_data = $('#edit_liter_flow').serialize();

      jConfirm('Update Liter Flow Now?','Reminder', function(response){

        if(response)
        {
          $.post(base_url+"order/update_liter_flow_value/"+unique_id+"/"+equipment_id, form_data, function(response){
            var obj = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');
            $('.modal').modal("hide");
          });
        }
      });
    });

   $('.save_bipap_option').bind('click',function(){
      var equipment_id = $(this).attr('data-id');
      var unique_id = $(this).attr('data-unique-id');
      var form_data = $('#edit_bipap_option').serialize();

      jConfirm('Update BIPAP Option?','Reminder', function(response){

        if(response)
        {
          $.post(base_url+"order/update_bipap_option/"+unique_id+"/"+equipment_id, form_data, function(response){
            var obj = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');
            $('.modal').modal("hide");
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


   $('.save_cpap_option').bind('click',function(){
      var equipment_id = $(this).attr('data-id');
      var unique_id = $(this).attr('data-unique-id');
      var form_data = $('#edit_cpap_option').serialize();

      jConfirm('Update CPAP Option?','Reminder', function(response){

        if(response)
        {
          $.post(base_url+"order/update_cpap_option/"+unique_id+"/"+equipment_id, form_data, function(response){
            var obj = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');
            $('.modal').modal("hide");
          });
        }

      });
   });


    $('body').on('click','.save_updated_weight_btn',function(){
      var weight_id = $(this).attr('data-id');
      var unique_id = $(this).attr('data-unique-id');
      var form_data = $('#update_patient_weight').serialize();

      jConfirm('Update Customer Weight?','Reminder', function(response){

        if(response)
        {
            $.post(base_url+"order/update_patient_weight/"+unique_id+"/"+weight_id, form_data, function(response){
                var obj = $.parseJSON(response);
                jAlert(obj['message'],'Reminder');
                $('.modal').modal("hide");
            });
        }

      });
   });


  $('body').on('click','.put_patient_weight',function(){
    var unique_id = $(this).attr('data-unique-id');
    var form_data = $('#put_patient_weight').serialize();

    jConfirm('Save Customer Weight?','Reminder', function(response){
      if(response)
      {
        $.post(base_url+"order/put_patient_weight/"+unique_id, form_data, function(response){
            var obj = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');
            $('.modal').modal("hide");
        });
      }
    });
   });


  $('body').on('click','.save_edited_summary',function(){
    var _this = $(this);
    var id = $(this).attr('data-value');
    var btn_id = $(this).attr('data-id');
    var form_data = $('.update_summary_confirmed' + btn_id).serialize();

    jConfirm('Update this entry now?', 'Reminder', function(response){
        if(response)
        {
          $.post(base_url + 'order/update_summary_tbl/' + id + '/' + btn_id, form_data ,function(response){
            var obj = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');

            // if(obj['error'] == 0)
            // {
            //   setTimeout(function(){
            //     location.reload();
            //   },1000);
            // }

          });
        }
    });
  });

  /** Delete Equipment in Order Summary **/
  $('.delete_equipment_summary').on('click',function(){
      var _this_element = $(this);
      jConfirm('Delete this Item?', 'Reminder', function(response){
        if(response)
        {
          $(_this_element).parent().parent().fadeOut(500,function(){
            $(this).remove();
          });
        }
      });
  });


/** Delete equipment in confirming modal **/
$('.remove_item_in_confirm').on('click',function(){
  var _this_element = $(this);
  jConfirm('Are you sure you want to delete this Item?', 'Reminder', function(response){
    if(response)
    {
      $(_this_element).parent().parent().fadeOut(500,function(){
        $(this).remove();
      });
    }
  });
});


// /** Search Patient Records **/
// $('#search-patients').bind('keyup',function(){
//     var searchString = $(this).val();
//     var data_value = $('.patient_results').attr("data-value");
//     var hospice_group = $(this).attr("data-group");

//     $('#pfname').val($(this).val().toLowerCase());
//     $('#plname').val($(this).val().toLowerCase());

//     if($('#search-patients').val() !== "" || $('#search-patients').val() !== null)
//     {
//       $.ajax({
//            type:"POST",
//            url:base_url+'order/search_patients/string/' + hospice_group+"/?searchString="+searchString,
//            success:function(response)
//            {
//               $('#suggestion_container').show();
//               $('#suggestion_container').html(response);

//               $(".patient_results").bind('click', function(){
//                  var medical_record_num = $(this).attr('data-value');
//                  var id = $(this).attr('data-id');
//                  var p_fname = $(this).attr('data-fname');
//                  var p_lname = $(this).attr('data-lname');
//                  var patient_record = $("#search-patients");

//                  $('#pfname').val(p_fname.toLowerCase());
//                  $('#plname').val(p_lname.toLowerCase());
//                  $('#medicalid').val(id.toLowerCase());
//                  $('#suggestion_container').hide();
//                  contact_name = patient_record.val(medical_record_num + " - " + p_fname + " " + p_lname);
//                  $('#search_form').submit();

//               });
//               $('.result-lists').bind('click',function(){
//                    $('#search_form').submit();
//               });

//           },
//           error:function(jqXHR, textStatus, errorThrown)
//           {
//             console.log(textStatus, errorThrown);
//           }
//       });
//     }
//     else
//     {
//       $('#suggestion_container').hide();
//     }
// });

/** Search Patient Records **/
var globalTimeout = null;
$('#search-patients').bind('keyup',function(){
    var searchString = $(this).val();
    var data_value = $('.patient_results').attr("data-value");
    var hospice_group = $(this).attr("data-group");

    $('#pfname').val($(this).val().toLowerCase());
    $('#plname').val($(this).val().toLowerCase());

    if(globalTimeout != null) clearTimeout(globalTimeout);
    globalTimeout =setTimeout(getInfoFunc,1100);

    function getInfoFunc(){
      globalTimeout = null;
      if(searchString.length >= 3){
        $('#suggestion_container').html("<div style='text-align:center; padding-top:5px;margin-bottom:25px;font-size:17px !important; height: 50px !important; background-color: #fff !important;'><h4>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></h4></div>");
        $('body').find('.search-patients-button').attr("disabled","disabled");
        if($('#search-patients').val() !== "" || $('#search-patients').val() !== null)
        {
          $('#suggestion_container').show();
          $.ajax({
              type:"POST",
              url:base_url+'order/search_patients_v2/string/' + hospice_group+"/?searchString="+searchString,
              success:function(response)
              {

                  $('#suggestion_container').html(response);
                  $('body').find('.search-patients-button').removeAttr('disabled');

                  $(".patient_results").bind('click', function(){
                    var medical_record_num = $(this).attr('data-value');
                    var id = $(this).attr('data-id');
                    var p_fname = $(this).attr('data-fname');
                    var p_lname = $(this).attr('data-lname');
                    var patient_record = $("#search-patients");

                    $('#pfname').val(p_fname.toLowerCase());
                    $('#plname').val(p_lname.toLowerCase());
                    $('#medicalid').val(id.toLowerCase());
                    $('#suggestion_container').hide();
                    contact_name = patient_record.val(medical_record_num + " - " + p_fname + " " + p_lname);
                    $('#search_form').submit();

                  });
                  $('.result-lists').bind('click',function(){
                      $('#search_form').submit();
                  });

              },
              error:function(jqXHR, textStatus, errorThrown)
              {
                console.log(textStatus, errorThrown);
              }
          });
        }
        else
        {
          $('#suggestion_container').hide();
        }
      }
      else {
        $('#suggestion_container').hide();
      }
    }
});

/** Search ITEM TRACKING by SERIAL NUMBER **/
$('#item-tracking-search').bind('keyup',function(){
  var searchString = $(this).val();
  var data_value   = $('.item_results').attr('data-value');

  if($('#item-tracking-search').val() !== "" || $('#item-tracking-search').val() !== null)
  {
    $('#item_tracking_suggestions').html("<div style='text-align:center; padding-top:5px;margin-bottom:25px;font-size:17px !important; height: 50px !important; background-color: #fff !important;'><h4>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></h4></div>");
    $('#item_tracking_suggestions').show();
    $.ajax({
           type:"POST",
           url:base_url+'order/search_items_tracking/' + searchString,
           success:function(response)
           {

              $('#item_tracking_suggestions').html(response);

              $(".items_result").bind('click', function(){
                 var serial_number = $(this).attr('data-value');
                 var id = $(this).attr('data-id');
                 var item_record = $("#item-tracking-search");


                 $('#item_tracking_suggestions').hide();
                 contact_name = item_record.val(serial_number);
                 $('#item_tracking_search').submit();

              });

          },
          error:function(jqXHR, textStatus, errorThrown)
          {
            console.log(textStatus, errorThrown);
          }
      });
  }
  else
  {
    $('#item_tracking_suggestions').hide();
  }

});


/** Search ITEM TRACKING by OXYGEN LOT NUMBER **/
$('#lot-number-search').bind('keyup',function(){
  var searchString = $(this).val();
  var data_value   = $('.lot_number_results').attr('data-value');

  if($('#lot-number-search').val() !== "" || $('#lot-number-search').val() !== null)
  {
    $('#oxygen_lot_number_suggestions').html("<div style='text-align:center; padding-top:5px;margin-bottom:25px;font-size:17px !important; height: 50px !important; background-color: #fff !important;'><h4>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></h4></div>");
    $('#oxygen_lot_number_suggestions').show();
    $.ajax({
           type:"POST",
           url:base_url+'order/search_lot_number/' + searchString,
           success:function(response)
           {
              $('#oxygen_lot_number_suggestions').html(response);

              $(".items_result").bind('click', function(){
                 var lot_number = $(this).attr('data-value');
                 var id = $(this).attr('data-id');
                 var item_record = $("#lot-number-search");


                 $('#oxygen_lot_number_suggestions').hide();
                 contact_name = item_record.val(lot_number);
                 $('#lot_number_search').submit();

              });

          },
          error:function(jqXHR, textStatus, errorThrown)
          {
            console.log(textStatus, errorThrown);
          }
      });
  }
  else
  {
    $('#oxygen_lot_number_suggestions').hide();
  }

});

  //save as draft MARJORIE
  $('body').on('click', '.btn-save-draft', function(){
      //hospice details
      var hospice_provider        = $('body').find('.hospice_select').val();
      if(hospice_provider == undefined)
      {
        hospice_provider        = $('body').find('#organization_id_not_select').val();
      }
      //var hospice_staff_firstname = $('body').find('input[name=person_placing_order_fname]').val();
      //var hospice_staff_lastname  = $('body').find('input[name=person_placing_order_lname]').val();
      //var hospice_staff_email     = $('body').find('input[name=email]').val();
      //var hospice_telephone       = $('body').find('input[name=phone_num]').val();
      //var hospice_cellphone       = $('body').find('input[name=who_ordered_cpnum]').val();

      //patient profile
      var patient_medical_record_id = $('body').find('input[name=patient_mrn]').val();
      var patient_lastname          = $('body').find('input[name=patient_lname]').val();
      var patient_firstname         = $('body').find('input[name=patient_fname]').val();
      var patient_gender            = $('body').find('input[name=relationship_gender]').val();
      var patient_height            = $('body').find('input[name=patient_height]').val();
      var patient_weight            = $('body').find('input[name=patient_weight]').val();
      var patient_residence         = $('body').find('select[name=dropdown_deliver_type]').val();
      var patient_address           = $('body').find('input[name=p_address]').val();
      var patient_placenum          = $('body').find('input[name=patient_placenum]').val();
      var patient_city              = $('body').find('input[name=patient_city]').val();
      var patient_state             = $('body').find('input[name=patient_state]').val();
      var patient_postal_code       = $('body').find('input[name=patient_postalcode]').val();
      var patient_phone_num         = $('body').find('input[name=patient_phone_num]').val();
      var patient_alt_phone_num     = $('body').find('input[name=patient_alt_phonenum]').val();

      //emergency contact
      var patient_kin               = $('body').find('input[name=patient_nextofkin]').val();
      var patient_relationship      = $('body').find('input[name=patient_relationship]').val();
      var patient_kin_phonenum      = $('body').find('input[name=patient_nextofkinphonenum]').val();

      var data = {hospice_provider:hospice_provider, patient_medical_record_id:patient_medical_record_id, patient_lastname:patient_lastname, patient_firstname:patient_firstname, patient_gender:patient_gender,patient_height:patient_height, patient_weight:patient_weight, patient_residence:patient_residence, patient_address:patient_address,patient_placenum:patient_placenum, patient_city:patient_city, patient_state:patient_state, patient_postal_code:patient_postal_code,patient_phone_num:patient_phone_num, patient_alt_phone_num:patient_alt_phone_num, patient_kin:patient_kin, patient_relationship:patient_relationship, patient_kin_phonenum:patient_kin_phonenum};

      me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Saving Customer Record"});
      $.post(base_url+"order/save_patient_record_draft", data, function(response){
        //var obj  = $.parseJSON(response);
          if(response.error == 0)
          {
            me_message_v2(response);
            setTimeout(function(){
               location.reload();
            },1000);
          }else{
            me_message_v2(response);
          }
      },'json');
  });

  
  var billing_credit_prompt = function () {
    var actual_order_date = $('.input_actual_order_date ').val().split('-');
    var pickup_discharge_date = $('#pickup_discharge_date').val().split('-');
    var actual_order_month = parseInt(actual_order_date[0]);
    var actual_order_year = parseInt(actual_order_date[2]);
    var pickup_month = parseInt(pickup_discharge_date[1]);
    var pickup_year = parseInt(pickup_discharge_date[0]);

    console.log('billing_credit_prompt actual_order_date', actual_order_date);
    console.log('billing_credit_prompt pickup_discharge_date', pickup_discharge_date);
    if (pickup_month < actual_order_month && pickup_year <= actual_order_year) {
      jConfirm('Credit?','Response',function(response) { 
        if(response)
        {
          console.log('YES! ni work!');
          $('#billing_credit_modal').modal({
            backdrop: 'static',
            keyboard: false
          });
          $('#billing_credit_modal').modal('show');
        }
      });
      // if (confirm('Credit?')) {
      //   $('#billing_credit_modal').modal({
      //     backdrop: 'static',
      //     keyboard: false
      //   });
      //   $('#billing_credit_modal').modal('show');
      // }
    }
  }

  $('#popup_submit_billing_credit').bind('click',function(){
    var form_data = $('#billing_credit_form').serialize();
    var actual_order_date = $('.input_actual_order_date ').val();
    var uniqueID = $('#billing_credit_uniqueID').val();

    // Format Date
    // var temp_actual_order_date = '';
    // var explode_actual_order_date = actual_order_date.split('-');
    // temp_actual_order_date = explode_actual_order_date[2] + '-' + explode_actual_order_date[0] + '-' + explode_actual_order_date[1];

    $.post(base_url+"billing_credit/insert_billing_credit/"+actual_order_date+"/"+uniqueID, form_data, function(response){
      var obj = $.parseJSON(response);

      me_message_v2({error:obj['error'], message:obj['message']});

      $('#billing_credit_modal').modal('hide');
      closeModalbox();
      console.log(obj);
    });
  });

  $('#popup_cancel_billing_credit').bind('click',function(){
    var form_data = $('#billing_credit_form').serialize();
    var actual_order_date = $('.input_actual_order_date ').val();
    var uniqueID = $('#billing_credit_uniqueID').val();
    $.post(base_url+"billing_credit/insert_billing_credit/"+actual_order_date+"/"+uniqueID, form_data, function(response){
      var obj = $.parseJSON(response);

      me_message_v2({error:obj['error'], message:obj['message']});

      $('#globalModal, #billing_credit_modal').modal('hide');
      closeModalbox();
      console.log(obj);
    });
  });

  //Change Order Status to Confirm in CUS Order Status
  $('.btn-confirm-order').bind('click',function(){
    var medical_id = $(this).attr('data-medical-id');
    var unique_id  = $(this).attr('data-unique-id');
    var activity_type = $(this).attr('data-act-id');
    var form_data = $('.update_order_summary').serialize();

    var required_ptmove_style = $('.ptmove_required');
    //var required_ptmove_style2 = $('.ptmove_required2');
    var required_ptmove_style3 = $('.ptmove_required3');
    var required_ptmove_style4 = $('.ptmove_required4');
    var required_ptmove_style5 = $('.ptmove_required5');
    var required_ptmove_style6 = $('.ptmove_required6');
    var required_ptmove_field = $('.ptmove_required').val();

    jConfirm("Confirm Work Order?","Reminder",function(response){
      if(response)
      {
        if($('.ptmove_required').val() === '' || $('.ptmove_required3').val() === '' || $('.ptmove_required4').val() === '' || $('.ptmove_required5').val() === '' || $('.ptmove_required6').val() === '')
        {
          required_ptmove_style.css('border','1px solid red');
          required_ptmove_style3.css('border','1px solid red');
          required_ptmove_style4.css('border','1px solid red');
          required_ptmove_style5.css('border','1px solid red');
          required_ptmove_style6.css('border','1px solid red');

          jAlert("You need to input required data and press SAVE CHANGES to proceed.","Reminder");
        }
        else
        {
          // Check if there is a Scale Hoyer or a Scale Chair
          var adding_weight_sign = $("body").find("#adding_weight_sign").val();
          var adding_weight_equipment = $("body").find("#adding_weight_equipment").val();
          var patientID = $("body").find("#hdn_patient_id_confirmed_modal").val();

          if(adding_weight_sign == 1)
          {
            $.post(base_url+"order/check_saved_weight/"+medical_id+"/"+patientID+"/"+unique_id+"/"+adding_weight_equipment,"", function(response){
              var obj = $.parseJSON(response);
              if(obj.length > 0)
              {
                $.post(base_url+"order/change_to_confirmed/"+medical_id+"/"+unique_id+"/"+activity_type, form_data , function(response){
                  var obj = $.parseJSON(response);
                  jAlert(obj['message'],"Reminder");
                  if(obj['error'] == 0)
                  {
                    var current_act_type = $("body").find("#globalModal").find(".current_act_type").val();
                    var o2_concentrator_follow_up_sign = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").val();
                    var o2_concentrator_follow_up_equipmentID = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").attr("data-equipmentID");
                    var o2_concentrator_follow_up_uniqueID = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").attr("data-uniqueID");
                    var o2_concentrator_follow_up_uniqueID_old = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").attr("data-uniqueID_old");

                    if(o2_concentrator_follow_up_sign == 1 || o2_concentrator_follow_up_sign == 2)
                    {
                      if(current_act_type == 1 || current_act_type == 4)
                      {
                        $.post(base_url+"order/insert_oxygen_concentrator_follow_up/"+patientID+"/"+o2_concentrator_follow_up_equipmentID+"/"+o2_concentrator_follow_up_uniqueID+"/"+o2_concentrator_follow_up_sign+"/"+current_act_type+"/"+o2_concentrator_follow_up_uniqueID_old,"", function(response){});
                      }
                    }

                    // if ($('.cwo_list_div').find("#DataTables_Table_0_filter").find(".input-sm").val()) {}

                    $(".cwo_list_div").find('.list_tobe_confirmed_tr_'+unique_id).remove();
                    var show_entries = $('body').find(".cwo_list_div").find(".dataTables_info").text();
                    var exploded_show_entries = show_entries.split(" ");

                    if ((Number(exploded_show_entries[3])-1) != 0) {
                      var new_show_entries = exploded_show_entries[0]+" "+Number(exploded_show_entries[1])+" "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];

                      var pickup_sub = $("#billing_credit_pickupsub").val();
                      if (activity_type == 2 && pickup_sub != 'not needed') {
                        billing_credit_prompt();
                        // closeModalbox(); // Remove if uncomment billing_credit_prompt
                      } else {
                        closeModalbox();
                      }
                      
                    } else {
                      var new_show_entries = exploded_show_entries[0]+" 0 "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];

                      var pickup_sub = $("#billing_credit_pickupsub").val();
                      if (activity_type == 2  && pickup_sub != 'not needed') {
                        billing_credit_prompt();
                        // closeModalbox(); // Remove if uncomment billing_credit_prompt
                      } else {
                        closeModalbox();
                        location.reload();
                      }
                    }

                    
                    $('body').find(".cwo_list_div").find(".dataTables_info").html(new_show_entries);

                  }
                });
              }
              else
              {
                jAlert("You need to input the customer weight to proceed.","Reminder");
              }
            });
          }
          else
          {
            me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting changes..."});
            $.post(base_url+"order/change_to_confirmed/"+medical_id+"/"+unique_id+"/"+activity_type, form_data , function(response){
              var obj = $.parseJSON(response);
              me_message_v2(obj);

              var current_act_type = $("body").find("#globalModal").find(".current_act_type").val();
              var o2_concentrator_follow_up_sign = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").val();
              var o2_concentrator_follow_up_equipmentID = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").attr("data-equipmentID");
              var o2_concentrator_follow_up_uniqueID = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").attr("data-uniqueID");
              var o2_concentrator_follow_up_uniqueID_old = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").attr("data-uniqueID_old");

              if(o2_concentrator_follow_up_sign == 1 || o2_concentrator_follow_up_sign == 2)
              {
                if(current_act_type == 1 || current_act_type == 4)
                {
                  $.post(base_url+"order/insert_oxygen_concentrator_follow_up/"+patientID+"/"+o2_concentrator_follow_up_equipmentID+"/"+o2_concentrator_follow_up_uniqueID+"/"+o2_concentrator_follow_up_sign+"/"+current_act_type+"/"+o2_concentrator_follow_up_uniqueID_old,"", function(response){});
                }
              }

              // if ($('.cwo_list_div').find("#DataTables_Table_0_filter").find(".input-sm").val()) {}

              $(".cwo_list_div").find('.list_tobe_confirmed_tr_'+unique_id).remove();
              var show_entries = $('body').find(".cwo_list_div").find(".dataTables_info").text();
              var exploded_show_entries = show_entries.split(" ");

              if ((Number(exploded_show_entries[3])-1) != 0) {
                var new_show_entries = exploded_show_entries[0]+" "+Number(exploded_show_entries[1])+" "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];

                var pickup_sub = $("#billing_credit_pickupsub").val();
                if (activity_type == 2 && pickup_sub != 'not needed') {
                  billing_credit_prompt();
                  // closeModalbox(); // Remove if uncomment billing_credit_prompt
                } else {
                  closeModalbox();
                }
              } else {
                var new_show_entries = exploded_show_entries[0]+" 0 "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];

                var pickup_sub = $("#billing_credit_pickupsub").val();
                if (activity_type == 2 && pickup_sub != 'not needed') {
                  billing_credit_prompt();
                  // closeModalbox(); // Remove if uncomment billing_credit_prompt
                } else {
                  closeModalbox();
                  location.reload();
                }
              }
              $('body').find(".cwo_list_div").find(".dataTables_info").html(new_show_entries);
            });
          }
        }
      }
    });
  });

  //Change Order Status to Confirm in CUS Order Status
  $('.btn-confirm-exchange-order').bind('click',function(){
    var medical_id = $(this).attr('data-medical-id');
    var unique_id  = $(this).attr('data-unique-id');
    var form_data = $('.update_order_summary_exchange').serialize();
    var patientID = $("body").find("#hdn_patient_id_modal_exchange").val();

    jConfirm("Confirm Work Order?","Reminder",function(response){
      if(response)
      {
          $.post(base_url+"order/change_to_confirmed_exchange/"+medical_id+"/"+unique_id, form_data , function(response){
            var obj = $.parseJSON(response);
            jAlert(obj['message'],"Reminder");
            if(obj['error'] == 0)
            {

              var current_act_type = $("body").find("#globalModal").find(".current_act_type").val();
              var o2_concentrator_follow_up_sign = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").val();
              var o2_concentrator_follow_up_equipmentID = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").attr("data-equipmentID");
              var o2_concentrator_follow_up_uniqueID = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").attr("data-uniqueID");
              var o2_concentrator_follow_up_uniqueID_old = $("body").find("#globalModal").find(".o2_concentrator_follow_up_sign").attr("data-uniqueID_old");

              if(o2_concentrator_follow_up_sign == 1)
              {
                if(current_act_type == 3)
                {
                  $.post(base_url+"order/insert_oxygen_concentrator_follow_up/"+patientID+"/"+o2_concentrator_follow_up_equipmentID+"/"+o2_concentrator_follow_up_uniqueID+"/"+o2_concentrator_follow_up_sign+"/"+current_act_type+"/"+o2_concentrator_follow_up_uniqueID_old,"", function(response){});
                }
              }

              // if ($('.cwo_list_div').find("#DataTables_Table_0_filter").find(".input-sm").val()) {}

              $(".cwo_list_div").find('.list_tobe_confirmed_tr_'+unique_id).remove();
              var show_entries = $('body').find(".cwo_list_div").find(".dataTables_info").text();
              var exploded_show_entries = show_entries.split(" ");

              if ((Number(exploded_show_entries[3])-1) != 0) {
                var new_show_entries = exploded_show_entries[0]+" "+Number(exploded_show_entries[1])+" "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];
                closeModalbox();
              } else {
                var new_show_entries = exploded_show_entries[0]+" 0 "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];
                closeModalbox();
                location.reload();
              }
              $('body').find(".cwo_list_div").find(".dataTables_info").html(new_show_entries);
            }
          });
        }
    });
  });

  //to enable the confirm button when doing an exchange
  $('.disabled_pickedup_before_confirming').bind('click',function(){
    $('.btn-confirm-exchange-order').prop('disabled',false);
  });


   //Addtional equipment
  var show_after_hour_alert = function()
  {
      var dt = new Date();
      var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
      var show = true;
      //alert("day "+dt.getDay()+", hour "+dt.getHours()+", minutes "+dt.getMinutes());
      if(dt.getDay()!=0 && dt.getDay()!=6)
      {
          show = false;
          if(dt.getHours() >= 17 || dt.getHours()<=8)
          {
            show = true;
            if(dt.getHours()==8 && dt.getMinutes()>29)
            {
               show = false;
            }
          }
      }
      if(show)
      {
        $('.after_hour_alert_content').show();
        $('#after_hour_alert').modal("show");
      }
  };

  $('.save_additional_btn').bind('click',function(){
    var id = $(this).attr("data-id");
    var _this_btn = $(this);
    var sessioned_account_type = $(".activity_type_sessioned_account").val();

    if(sessioned_account_type == "dme_user")
    {
      jConfirm('<br />Submit Activity? <br /><br /> <label class="i-checks"><input type="checkbox" class="send_to_confirm_work_order_new" ><i></i> Send to Confirm Work Order</label>',
       'Reminder', function(response){
        if(response)
        {
          var form_data = $('#add_additional_equipment_form').serialize();
          $(_this_btn).prop('disabled',true);

          if($(location).attr('href') != base_url)
          {
            if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
            {
              show_after_hour_alert();
            }
          }

          me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting activity ..."});
          $.post(base_url + 'order/add_additional_equipments/' + id, form_data ,function(response){
            var obj = $.parseJSON(response);
            me_message_v2(obj);

            if(obj['error'] == 0)
            {
              //trigger notification
              $.post(base_url+"main/trigger_gcm",function(){});

              $(_this_btn).prop('disabled',true);
              $('#submit_order_loader').modal("hide");
              setTimeout(function(){
                location.reload();
              },1500);
            }

            setTimeout(function(){
              $(_this_btn).prop('disabled',false);
            },3500);

          });
        }
        else
        {
          $("body").find(".send_to_confirm_work_order_sign").val(0);
        }
      });
    }
    else
    {
      jConfirm('Submit Activity?','Reminder', function(response){
        if(response)
        {
          var form_data = $('#add_additional_equipment_form').serialize();
          $(_this_btn).prop('disabled',true);

          if($(location).attr('href') != base_url)
          {
            if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
            {
              show_after_hour_alert();
            }
          }

          me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting activity ..."});
          $.post(base_url + 'order/add_additional_equipments/' + id, form_data ,function(response){
            var obj = $.parseJSON(response);
            me_message_v2(obj);

            if(obj['error'] == 0)
            {
              //trigger notification
              $.post(base_url+"main/trigger_gcm",function(){});

              $(_this_btn).prop('disabled',true);
              $('#submit_order_loader').modal("hide");
              setTimeout(function(){
                location.reload();
              },1500);
            }

            setTimeout(function(){
              $(_this_btn).prop('disabled',false);
            },3500);

          });
        }
      });
    }
  });

  $('.save_additional_btn_recurring').bind('click',function(){
    var id = $(this).attr("data-id");
    var _this_btn = $(this);
    var sessioned_account_type = $(".activity_type_sessioned_account").val();

      jConfirm('Submit Activity?','Reminder', function(response){
        if(response)
        {
          var form_data = $('#add_additional_equipment_form').serialize();
          $(_this_btn).prop('disabled',true);

          if($(location).attr('href') != base_url)
          {
            if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
            {
              show_after_hour_alert();
            }
          }

          me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting activity ..."});
          $.post(base_url + 'order/add_recurring_order/' + id, form_data ,function(response){
            var obj = $.parseJSON(response);
            console.log('OBJ: ');
            console.log(obj);
            me_message_v2(obj);
            if(obj['error'] == 0)
            {
              //trigger notification
              $.post(base_url+"main/trigger_gcm",function(){});

              $(_this_btn).prop('disabled',true);
              $('#submit_order_loader').modal("hide");
              setTimeout(function(){
                location.reload();
              },1500);
            }

            setTimeout(function(){
              $(_this_btn).prop('disabled',false);
            },3500);

          });
        }
      });
  });

  $('body').on('click','.send_to_confirm_work_order_new',function(){

    if($(this).is(":checked"))
    {
      $("body").find(".send_to_confirm_work_order_sign").val(1);
    }
    else
    {
      $("body").find(".send_to_confirm_work_order_sign").val(0);
    }
  });

  var global_this_btn = $('.save_pickup_data');
  var global_id = 0;

  var savepickupdata = function (id, _this_btn, is_credit) {
    var form_data = $('#add_additional_equipment_form').serialize();
    $(_this_btn).prop('disabled',true);
    if($(location).attr('href') != base_url)
    {
      if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
      {
        show_after_hour_alert();
      }
    }
    me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting activity ..."});
    $.post(base_url + 'order/update_status_to_pickup/' + id, form_data ,function(response){
      var obj = $.parseJSON(response);
      me_message_v2({error:obj['error'], message:obj['message']});

      if(obj['error'] == 0)
      {
        //trigger GCM
        $.post(base_url+"main/trigger_gcm",function(){});

        $(_this_btn).prop('disabled',true);
        $('#submit_order_loader').modal("hide");

        if (is_credit) {
          var schedule_pickup_date = $('.datepicker_scheduled_order_date').val();
          $.post(base_url + 'billing_credit/insert_billing_credit/' + schedule_pickup_date + '/' + obj['uniqueID'], form_data ,function(response1){
            setTimeout(function(){
              location.reload();
            },1500);
          });
        } else {
          setTimeout(function(){
            location.reload();
          },1500);
        }
      }
      else
      {
        $('#submit_order_loader').modal("hide");
      }

      setTimeout(function(){
        $(_this_btn).prop('disabled',false);
      },3500);
    });
  }

  var savepickupdata_else = function (id, _this_btn, is_credit) {
    var form_data = $('#add_additional_equipment_form').serialize();
    $(_this_btn).prop('disabled',true);
    if($(location).attr('href') != base_url)
    {
      if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
      {
        show_after_hour_alert();
      }
    }
    me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting activity ..."});
    $.post(base_url + 'order/update_status_to_pickup/' + id, form_data ,function(response){
      var obj = $.parseJSON(response);
      me_message_v2({error:obj['error'], message:obj['message']});

      if(obj['error'] == 0)
      {
        //trigger GCM
        $.post(base_url+"main/trigger_gcm",function(){});

        $(_this_btn).prop('disabled',true);
        $('#submit_order_loader').modal("hide");

        if (is_credit) {
          var schedule_pickup_date = $('.datepicker_scheduled_order_date').val();
          $.post(base_url + 'billing_credit/insert_billing_credit/' + schedule_pickup_date + '/' + obj['uniqueID'], form_data ,function(response1){
            setTimeout(function(){
              location.reload();
            },1500);
          });
        } else {
          setTimeout(function(){
            location.reload();
          },1500);
        }
      }
      else
      {
        $('#submit_order_loader').modal("hide");
      }

      setTimeout(function(){
        $(_this_btn).prop('disabled',false);
      },3500);
    });
  }

  /** Save Pickup Order **/
  $('.save_pickup_data').bind('click',function(){
    var id = $(this).attr("data-id");
    var _this_btn = $(this);
    global_this_btn = _this_btn;
    global_id = id;
    var sessioned_account_type = $(".activity_type_sessioned_account").val();
    var pickreason = $(".select_pickup_reason").val();
    console.log('pickreason', pickreason);
    if (pickreason != 'not needed' && pickreason != '') {
      var discharge_date = $('.datepicker_date_discharge').val().split('-');
      var pickup_date = $('.datepicker_scheduled_pickup_date').val().split('-');
      var discharge_month = parseInt(discharge_date[1]);
      var discharge_year = parseInt(discharge_date[0]);
      var pickup_month = parseInt(pickup_date[1]);
      var pickup_year = parseInt(pickup_date[0]);
    }
    
    if(sessioned_account_type == "dme_user")
    {
      jConfirm('<br />Submit Activity? <br /><br /> <label class="i-checks"><input type="checkbox" class="send_to_confirm_work_order_new" ><i></i> Send to Confirm Work Order</label>',
       'Reminder', function(response){
        if(response)
        {
          savepickupdata_else(id, _this_btn, false);
          // if (pickreason != 'not needed' && pickreason != '') {
          //   console.log('datepicker_date_discharge  ', discharge_date, discharge_month);
          //   console.log('datepicker_scheduled_pickup_date ', pickup_date, pickup_month);

          //   if (discharge_month < pickup_month && discharge_year <= pickup_year) {
          //     console.log('ifcredit');
          //     if (confirm('Credit?')) {
          //       console.log('ififcredit');
          //       $('#us_mail_modal').modal('show');
          //       // savepickupdata(id, _this_btn, true);
          //     } else {
          //       console.log('ifelsecredit');
          //       savepickupdata(id, _this_btn, false);
          //     }
              
          //   } else {
          //     console.log('elsecredit');
          //     savepickupdata(id, _this_btn, false);
          //   }
          // } else {
          //   savepickupdata(id, _this_btn, false);
          // }
        }
      });
    }
    else
    {
      jConfirm('Submit Activity?', 'Reminder', function(response){
        if(response)
        {
          savepickupdata_else(id, _this_btn, false);
          // if (pickreason != 'not needed' && pickreason != '') {
          //   console.log('datepicker_date_discharge  ', discharge_date, discharge_month);
          //   console.log('datepicker_scheduled_pickup_date ', pickup_date, pickup_month);
            
          //   if (discharge_month < pickup_month && discharge_year <= pickup_year) {
          //     if (confirm('Credit?')) {
          //       console.log('ififcredit');
          //       $('#us_mail_modal').modal('show');
          //       // savepickupdata_else(id, _this_btn, true);
          //     } else {
          //       console.log('ifelsecredit');
          //       savepickupdata_else(id, _this_btn, false);
          //     }
          //   } else {
          //     savepickupdata_else(id, _this_btn, false);
          //   }
          // } else {
          //   savepickupdata_else(id, _this_btn, false);
          // }
        }
      });
    }
  });

  /** Save Exchange Order **/
  $('.save_exchange_data').bind('click',function(){
    var id = $(this).attr("data-id");
    var _this_btn = $(this);
    var sessioned_account_type = $(".activity_type_sessioned_account").val();

    if(sessioned_account_type == "dme_user")
    {
      jConfirm('<br />Submit Activity? <br /><br /> <label class="i-checks"><input type="checkbox" class="send_to_confirm_work_order_new" ><i></i> Send to Confirm Work Order</label>',
        'Reminder', function(response){
        if(response)
        {
          var form_data = $('#add_additional_equipment_form').serialize();
          $(_this_btn).prop('disabled',true);

          if($(location).attr('href') != base_url)
          {
            if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
            {
              show_after_hour_alert();
            }
          }

          //$('#submit_order_loader').modal("show");
          me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting activity ..."});
          $.post(base_url + 'order/change_status_to_exchange/' + id, form_data ,function(response){
            var obj = $.parseJSON(response);
            me_message_v2(obj);

            if(obj['error'] == 0)
            {
              //trigger notification
              $.post(base_url+"main/trigger_gcm",function(){});

              $(_this_btn).prop('disabled',true);
                $('#submit_order_loader').modal("hide");
                  setTimeout(function(){
                    location.reload();
                  },1500);
                }

              setTimeout(function(){
                $(_this_btn).prop('disabled',false);
              },3500);

            });
          }
      });
    }
    else
    {
      jConfirm('Submit Activity?', 'Reminder', function(response){
        if(response)
        {
          var form_data = $('#add_additional_equipment_form').serialize();
          $(_this_btn).prop('disabled',true);

          if($(location).attr('href') != base_url)
          {
            if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
            {
              show_after_hour_alert();
            }
          }

          //$('#submit_order_loader').modal("show");
          me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting activity ..."});
          $.post(base_url + 'order/change_status_to_exchange/' + id, form_data ,function(response){
            var obj = $.parseJSON(response);
            me_message_v2(obj);

            if(obj['error'] == 0)
            {
              //trigger notification
              $.post(base_url+"main/trigger_gcm",function(){});

              $(_this_btn).prop('disabled',true);
                $('#submit_order_loader').modal("hide");
                  setTimeout(function(){
                    location.reload();
                  },1500);
                }

              setTimeout(function(){
                $(_this_btn).prop('disabled',false);
              },3500);

            });
          }
      });
    }
  });

  /** Save CUS MOVE Order **/
  $('.save_additional_btn_ptmove').bind('click',function(){
    var id = $(this).attr("data-id");
    var _this_btn = $(this);
    var sessioned_account_type = $(".activity_type_sessioned_account").val();

    if(sessioned_account_type == "dme_user")
    {
      jConfirm('<br />Submit Activity? <br /><br /> <label class="i-checks"><input type="checkbox" class="send_to_confirm_work_order_new" ><i></i> Send to Confirm Work Order</label>',
        'Reminder', function(response){
        if(response)
        {
          var form_data = $('#add_additional_equipment_form').serialize();
          $(_this_btn).prop('disabled',true);
          if($(location).attr('href') != base_url)
          {
            if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
            {
              show_after_hour_alert();
            }
          }

          me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting activity ..."});
          $.post(base_url + 'order/change_status_to_ptmove/' + id, form_data ,function(response){
            var obj = $.parseJSON(response);
            me_message_v2(obj);

            if(obj['error'] == 0)
            {
              //trigger notification
              $.post(base_url+"main/trigger_gcm",function(){});

              $(_this_btn).prop('disabled',true);
                  $('#submit_order_loader').modal("hide");
                  setTimeout(function(){
                  location.reload();
                },1500);
              }

              setTimeout(function(){
                $(_this_btn).prop('disabled',false);
              },3500);

            });
          }
      });
    }
    else
    {
      jConfirm('Submit Activity?', 'Reminder', function(response){
        if(response)
        {
          var form_data = $('#add_additional_equipment_form').serialize();
          $(_this_btn).prop('disabled',true);
          if($(location).attr('href') != base_url)
          {
            if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
            {
              show_after_hour_alert();
            }
          }

          me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting activity ..."});
          $.post(base_url + 'order/change_status_to_ptmove/' + id, form_data ,function(response){
            var obj = $.parseJSON(response);
            me_message_v2(obj);

            if(obj['error'] == 0)
            {
              //trigger notification
              $.post(base_url+"main/trigger_gcm",function(){});

              $(_this_btn).prop('disabled',true);
                  $('#submit_order_loader').modal("hide");
                  setTimeout(function(){
                  location.reload();
                },1500);
              }

              setTimeout(function(){
                $(_this_btn).prop('disabled',false);
              },3500);

            });
          }
      });
    }


  });

  /** Save CUS MOVE Order **/
  $('.save_additional_btn_respite').bind('click',function(){
    var id = $(this).attr("data-id");
    var _this_btn = $(this);
    var sessioned_account_type = $(".activity_type_sessioned_account").val();

    if(sessioned_account_type == "dme_user")
    {
      jConfirm('<br />Submit Activity? <br /><br /> <label class="i-checks"><input type="checkbox" class="send_to_confirm_work_order_new" ><i></i> Send to Confirm Work Order</label>',
        'Reminder', function(response){
        if(response)
        {
          var form_data = $('#add_additional_equipment_form').serialize();
          $(_this_btn).prop('disabled',true);
          if($(location).attr('href') != base_url)
          {
            if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
            {
              show_after_hour_alert();
            }
          }
          me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting activity ..."});
            $.post(base_url + 'order/change_status_to_respite/' + id, form_data ,function(response){
              var obj = $.parseJSON(response);
              me_message_v2(obj);

              if(obj['error'] == 0)
              {
                //trigger notification
                $.post(base_url+"main/trigger_gcm",function(){});

                $(_this_btn).prop('disabled',true);
                $('#submit_order_loader').modal("hide");
                  setTimeout(function(){
                  location.reload();
                },1500);
              }

              setTimeout(function(){
                $(_this_btn).prop('disabled',false);
              },3500);

            });
          }
      });
    }
    else
    {
      jConfirm('Submit Activity?', 'Reminder', function(response){
        if(response)
        {
          var form_data = $('#add_additional_equipment_form').serialize();
          $(_this_btn).prop('disabled',true);
          if($(location).attr('href') != base_url)
          {
            if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
            {
              show_after_hour_alert();
            }
          }
          me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Submitting activity ..."});
            $.post(base_url + 'order/change_status_to_respite/' + id, form_data ,function(response){
              var obj = $.parseJSON(response);
              me_message_v2(obj);

              if(obj['error'] == 0)
              {
                //trigger notification
                $.post(base_url+"main/trigger_gcm",function(){});

                $(_this_btn).prop('disabled',true);
                $('#submit_order_loader').modal("hide");
                  setTimeout(function(){
                  location.reload();
                },1500);
              }

              setTimeout(function(){
                $(_this_btn).prop('disabled',false);
              },3500);

            });
          }
      });
    }

  });

  //Lot number comment
  $('body').on('click','.btn-add-lot-comment' ,function(){
    var form_data = $('.add_lot_notes_form').serialize();

    jConfirm('Save Note?', 'Reminder', function(response){
        if(response)
        {
           $.post(base_url + "order/add_lot_comment" , form_data, function(response){
              var obj = $.parseJSON(response);
              jAlert(obj['message'],'Reminder');

              if(obj['error'] == 0)
              {
                window.location.reload();
              }
           });
        }
    });
  });


  //for the edit info in PT Profile (save the fields like item no. serial no. etc.)
  $('.btn-save-order-fields').bind('click',function(){
    var medical_id = $(this).attr("data-medical-id");
    var form_data = $('.update_order_summary').serialize();

    jConfirm('Save changes?', 'Reminder', function(response){
      if(response)
      {
        me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Saving changes ..."});
        $.post(base_url+"order/update_profile_order_summary/" + medical_id, form_data, function(response){
          var obj  = $.parseJSON(response);
          me_message_v2(obj);
          location.reload();
        });
    }
  });
  });


  $('.delete-hospice-btn').bind('click',function(){
    var hospiceID = $(this).attr("data-id");

    jConfirm('Delete Hospice?','Warning', function(response){
      if(response)
      {
        $.post(base_url+"hospice/remove_hospice/" + hospiceID, function(response){

            var obj  = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');

            setTimeout(function(){
              location.reload();
            },3000);

        });
      }

    });

  });


  //END

  /** Snippet to detect enter key **/
  $.fn.pressEnter = function(fn) {
  return this.each(function() {
    $(this).off('enterPress');
    $(this).on('enterPress', fn);
    $(this).keypress(function(e) {
        if (e.keyCode == 13) {
          console.log('error');
          $(this).trigger("enterPress");
        }
      });
    });
  };


  /* For the full screen feature */
  $(document).ready(function(){
      $('.full-screen').on('click',function(){
          var target;
          this.target && ( target = $(this.target)[0] );
          screenfull.toggle(target);
          if(screenfull.isFullscreen)
          {
               $(".full-screen>a>i").removeClass("fa-expand").addClass("fa-compress");
            }
            else
            {
              $(".full-screen>a>i").removeClass("fa-compress").addClass("fa-expand");
            }
      });
    });
   /* End of fullscreen function */


   $('.select-view').bind('change',function(){
      var view_type = $(this).val();
      var query_string = location.search;

      $.ajax({
        type: "POST",
        url: base_url + "order/return_search/" + view_type,
        success:function(response)
        {

          window.location.href = base_url + "order/return_search/"+ view_type + query_string;

        },
        error:function(jqXHR, textStatus, errorThrown)
        {
          console.log(textStatus, errorThrown);
        }

      });
   });



   //** Auto Suggest Patient Medical Record No. **//
   // $('#patient_mrn').bind('keyup', function(){
   //    var searchString = $('#patient_mrn').val();
   //    var pmrn_id = $('.pmrn_result').attr('data-id');
   //    var hospice_id = $('.hospice_id').val();

      // if(searchString === "")
      // {
         // $('#p_lname').val("");
         // $('#p_fname').val("");
         // $('#p_height').val("");
         // $('#p_weight').val("");
         // $('#p_add').val("");
         // $('#p_placenum').val("");
         // $('#p_city').val("");
         // $('#p_state').val("");
         // $('#p_postal').val("");
         // $('#p_phone_number').val("");
         // $('#p_alt_phonenum').val("");
         // $('#p_nextofkin').val("");
         // $('#p_nextofkinphone').val("");
         // $('#patient_relationship').val("");
         // $('.p_gender').val("");
         // $('#suggestion_container').hide();
      // }

      // $.ajax({
         // type:"POST",
         // url:base_url+'order/search_patient/' + searchString + '/' + hospice_id,
         // success:function(response)
         // {
            // $('#suggestion_container').show();
            // $('#suggestion_container').html(response);

            // $(".pmrn_result").bind('click', function(){
               // var patient_mrnum = $(this).attr('data-id');
               // var patient_mrn_val = $("#patient_mrn");
               // var medical_record_num = '';

               // medical_record_num = patient_mrn_val.val(patient_mrnum);

               // $.post(base_url + "order/return_patient_info/" + medical_record_num.val(), function(data){
                  // var obj = $.parseJSON(data);

                  // $('#p_lname').val(obj['p_lname']);
                  // $('#p_fname').val(obj['p_fname']);
                  // $('#p_height').val(obj['p_height']);
                  // $('#p_weight').val(obj['p_weight']);
                  // $('#p_add').val(obj['p_street']);
                  // $('#p_placenum').val(obj['p_placenum']);
                  // $('#p_city').val(obj['p_city']);
                  // $('#p_state').val(obj['p_state']);
                  // $('#p_postal').val(obj['p_postalcode']);
                  // $('#p_phone_number').val(obj['p_phonenum']);
                  // $('#p_alt_phonenum').val(obj['p_altphonenum']);
                  // $('#p_nextofkin').val(obj['p_nextofkin']);
                  // $('#patient_relationship').val(obj['p_nextofkin']);
                  // $('.p_gender').val(obj['p_nextofkin']);
                  // $('#p_nextofkinphone').val(obj['p_nextofkinnum']);

               // });
               // $('#suggestion_container').hide();
            // });

         // },

         // error:function(jqXHR, textStatus, errorThrown)
         // {
            // console.log(textStatus, errorThrown);
         // }

      // });
   // });
   //** END //

   $('.select-all-pickup').bind('click',function(){
    var addressID = $(this).attr("data-id");
    var item_class = ".checked_pickup_item_other_address_"+addressID;
    var sub_item_class = ".sub_options_checkbox_other_address_"+addressID;
    var _wrapper = $('#hdn_pickup_unique_div');
    var _max_field = 20;
    var _start = 1;


    if($(this).is(":checked"))
    {
      $(item_class).prop('checked','checked');
      $(sub_item_class).prop('checked','checked');

      $.each($(item_class), function(){
        var unique_ids = $(this).attr("data-uniqueID");

        if(_start < _max_field)
        {
          $(_wrapper).append("<input id='hdn_pickup_"+unique_ids+"' type='hidden' name='hdn_pickup_uniqueID[]' value="+unique_ids+" />");
        }
        _start++;
      });
    }
    else
    {
      $(item_class).prop('checked',false);
      $(sub_item_class).prop('checked', false);
      _wrapper.html("");
    }
  });

  $('.select_all_from_old_address').bind('click',function(){
    var _wrapper = $('#hdn_pickup_unique_div');
    var _max_field = 20;
    var _start = 1;
    console.log('select_all_from_old_address');
    if($(this).is(":checked"))
    {
      var selected_pickup_reason = $('.select_pickup_reason').val();
      if(selected_pickup_reason == 'not needed') {
        var patientID = $('.select_pickup_reason').attr('data-patient-id');
        $.get(base_url + 'order/check_if_initial_workorder_confirmed/' + patientID, function(response){
          var obj = $.parseJSON(response);
          if(obj['is_confirmed']) {
            $('.checked_pickup_item_old_address').prop('checked','checked');
            $('.sub_options_checkbox_old_address').prop('checked','checked');

            $.each($(".checked_pickup_item_old_address"), function(){
              var unique_ids = $(this).attr("data-uniqueID");

              if(_start < _max_field)
              {
                $(_wrapper).append("<input id='hdn_pickup_"+unique_ids+"' type='hidden' name='hdn_pickup_uniqueID[]' value="+unique_ids+" />");
              }
              _start++;
            });
          } else {
            jAlert('Confirm initial work order first.','Response');
            $('.select_all_from_old_address').prop('checked',false);
            $('.checked_pickup_item_old_address').prop('checked',false);
            $('.sub_options_checkbox_old_address').prop('checked', false);
            _wrapper.html("");
          }
        });
      }
      
    }
    else
    {
      $('.checked_pickup_item_old_address').prop('checked',false);
      $('.sub_options_checkbox_old_address').prop('checked', false);
      _wrapper.html("");
    }
  });

  $('select#select_exchange_address').on('change', function (e)
  {
    var valueSelected = this.value;
    var addressID = "#exchange_div_"+valueSelected;

    $("body").find("#forexchange_categories3").find(".address_equipment_col_exchange").css("display","none");
    $("body").find("#forexchange_categories3").find(addressID).css("display","block");
    $("body").find("#exchange_address_id").val(valueSelected);

    $.each($(".checked_item"), function(){

      var unique_id = $(this).attr('data-uniqueID');
      $(this).prop('checked',false);
      $("#hdn_pickup_"+unique_id+"").remove();
    });
  });

  $('select#select_pickup_address').on('change', function (e)
  {
    var valueSelected = this.value;
    var addressID = "#pickup_div_"+valueSelected;

    $("body").find("#forpickup_categories3").find(".address_equipment_col").css("display","none");
    $("body").find("#forpickup_categories3").find(addressID).css("display","block");

    $.each($(".checked_pickup_item"), function(){

      var unique_id = $(this).attr('data-uniqueID');
      $(this).prop('checked',false);
      $("#hdn_pickup_"+unique_id+"").remove();
    });
  });

  $('select.select_pickup_reason').on('change', function (e)
  {
    var valueSelected = this.value;
    var _wrapper = $('#hdn_pickup_unique_div');
    var val = $(this).val();
    
    if(valueSelected == "expired" || valueSelected == "discharged" || valueSelected == "revoked")
    {
      var patientID = $(this).attr('data-patient-id');
      $.get(base_url + 'order/check_if_initial_workorder_confirmed/' + patientID, function(response){
        var obj = $.parseJSON(response);
        console.log('is_confirmed', obj);
        if(obj['is_confirmed'])
        {
          $('#pickup_reason_wrapper').removeClass('col-sm-12');
          $('#pickup_reason_wrapper').addClass('col-sm-6');
          var new_val = val.charAt(0).toUpperCase() + val.slice(1, val.length);
          console.log('pickup_discharge_disp', new_val);
          switch(val) {
            case 'expired': new_val = 'Expiration'; break;
            case 'discharged': new_val = 'Discharge'; break;
            case 'revoked': new_val = 'Revocation'; break;
          }
          $('.pickup_discharge_disp').html(new_val);
          $('.pickup_discharge_wrapper').show();
          $('.datepicker_date_discharge').click();
          $('.datepicker_date_discharge').datepicker('show');


          $("body").find("#address_to_pickup").css("display",'none');
          $("body").find(".address_equipment_col").css("display",'none');
          $("body").find("#pickup_all_div").css("display",'block');
          $("body").find("#pickup_sign").val(1);
          _wrapper.html("");
          $.each($(".pickup_equipments_initial"), function(){
            var unique_ids = $(this).attr("data-uniqueID");

            $(_wrapper).append("<input id='hdn_pickup_"+unique_ids+"' type='hidden' name='hdn_pickup_uniqueID_initial[]' value="+unique_ids+" />");
          });
          $.each($(".pickup_equipments_ptmove"), function(){
            var unique_ids = $(this).attr("data-uniqueID");
            var names = $(this).attr("name");
            var splitted_words = names.split("_");

            $(_wrapper).append("<input id='hdn_pickup_"+unique_ids+"' type='hidden' name='hdn_pickup_uniqueID_ptmove_"+splitted_words[3]+"' value="+unique_ids+" />");
          });
          $.each($(".pickup_equipments_respite"), function(){
            var unique_ids = $(this).attr("data-uniqueID");
            var names = $(this).attr("name");
            var splitted_words = names.split("_");

            $(_wrapper).append("<input id='hdn_pickup_"+unique_ids+"' type='hidden' name='hdn_pickup_uniqueID_respite_"+splitted_words[3]+"' value="+unique_ids+" />");
          });
        } else {
          jAlert('Confirm initial work order first.','Response');
          $('.select_pickup_reason').val('not needed');
          $('#pickup_reason_wrapper').removeClass('col-sm-6');
          $('#pickup_reason_wrapper').addClass('col-sm-12');
          $('.pickup_discharge_wrapper').hide();

          
          var one_active_address_id = $("body").find("#one_active_address_id").val();
          $("body").find("#address_to_pickup").css("display",'block');
          $("body").find("#pickup_all_div").css("display",'none');
          $("body").find("#pickup_sign").val(0);
          var element = document.getElementById("select_pickup_address");
          if(element)
          {
            $('#select_pickup_address').val('[-- Select Address --]');
          }
          else
          {

            $('#pickup_div_'+one_active_address_id).css("display","block");
          }
          _wrapper.html("");
        }
      });
      
    }
    else
    {
      $('.select_pickup_reason').val('not needed');
      $('#pickup_reason_wrapper').removeClass('col-sm-6');
      $('#pickup_reason_wrapper').addClass('col-sm-12');
      $('.pickup_discharge_wrapper').hide();

      var one_active_address_id = $("body").find("#one_active_address_id").val();
      $("body").find("#address_to_pickup").css("display",'block');
      $("body").find("#pickup_all_div").css("display",'none');
      $("body").find("#pickup_sign").val(0);
      var element = document.getElementById("select_pickup_address");
      if(element)
      {
        $('#select_pickup_address').val('[-- Select Address --]');
      }
      else
      {

        $('#pickup_div_'+one_active_address_id).css("display","block");
      }
      _wrapper.html("");
    }

    var count_for_ptmove_pickup_all = $("body").find("#count_for_ptmove_pickup_all").val();
    if(count_for_ptmove_pickup_all == 0)
    {
      $("body").find(".pickup_all_div_ptmove").css("display","none");
    }

    var count_for_respite_pickup_all = $("body").find("#count_for_respite_pickup_all").val();
    if(count_for_respite_pickup_all == 0)
    {
      $("body").find(".pickup_all_div_respite").css("display","none");
    }

    var count_for_initial_pickup_all = $("body").find("#count_for_initial_pickup_all").val();
    if(count_for_initial_pickup_all == 0)
    {
      $("body").find(".pickup_all_div_initial").css("display","none");
    }
  });

  $('#dc_radio').click(function(){
    $('#dc_input').prop('disabled','');
  });

  $('.dc_radios').click(function(){
    $('#dc_input').prop('disabled','disabled');
  });

  //** gamit para kuhaon ang value sa option nga gi dynamic **/
  $("#groupname_select").bind("change",function(){
        $("#hdnGroup_name").val(this.options[this.selectedIndex].text);
        //alert(this.options[this.selectedIndex].text);
  });

    //** gamit para kuhaon ang value sa option nga gi dynamic [for Company]**/
  $("#groupname_select_company").bind("change",function(){
        $("#hdnGroup_name_company").val(this.options[this.selectedIndex].text);
        //alert(this.options[this.selectedIndex].text);
  });

  $(".hospice_provider_select").bind("change",function(){
    $(".hdn_provider_name").val(this.options[this.selectedIndex].text);
    console.log('hospice_provider_select', this.options[this.selectedIndex]);
    //alert(this.options[this.selectedIndex].text);
  });

  //** gamit para kuhaon ang value sa option nga gi dynamic **/
  $(".edit_hospicename").bind("change",function(){
        console.log('nisud');
      $(".edit_hospice_name").val(this.options[this.selectedIndex].text);
        //alert(this.options[this.selectedIndex].text);
  });



   // $('#groupname_select').bind('change',function(){
   //    //alert($("#hdnGroup_name").val());
   //    $('#password').css('border','1px solid red');
   //    $('#username_field').val($("#hdnGroup_name").val().toLowerCase().replace(' ','').replace(' ','').replace(' ','').replace(' ',''));
   // });


    //for the activity type radio button
    $('.radio_act_type').on('click',function(){
      console.log('radio_act_type', $(this).val());
      if($(this).val() == '2')
      {
        var count = parseInt($('.pickup-counter').val());
        var new_count = parseInt($('.new-pickup-counter').val());
        if(count<1 && new_count<1)
        {
          $('#forpickup_categories3').find(".panel-body").html("<div class='col-xs-12'>No items to be picked up.</div>");
        }
        $('.equipment_section').hide();
        $('.btn-recurring').hide();
        $('.btn-not-recurring').show();
        $('#forrecurring_categories').hide();
        $('.equipment_section_recurring').hide();
        $('#default_order_btn_recurring').hide();
        $('.recurring-input-checkbox-month').prop('checked', false);
        $('.recurring-input-radio-month').prop('checked', false);
        $('.recurring-input-checkbox-week').prop('checked', false);
        $('.recurring-input-radio-week').prop('checked', false);
        $('#fordelivery_categories').hide();
        $('#fordelivery_categories2').hide();
        $('#forpickup_categories').show();
        $('#forpickup_categories2').show();
        $('#forpickup_categories3').show();
        $('#forpickup_categories4').show();
        $('#forpickup_categories5').show();
        $('#forpickup_categories6').show();
        $('#forptmove_categories').hide();
        $('#forptmove_categories2').hide();
        $('#forptmove_categories3').hide();
        $('#forptmove_categories4').hide();
        $('#forptmove_categories5').hide();
        $('.forptmove_emergency_contact').hide();
        $('#forrespite_categories').hide();
        $('#forrespite_categories2').hide();
        $('#forrespite_categories3').hide();
        $('#forrespite_categories4').hide();
        $('#forrespite_categories5').hide();
        $('#wrapper_equip_1').show();
        $('#forexchange_categories').hide();
        $('#forexchange_categories2').hide();
        $('#forexchange_categories3').hide();
        $('#forexchange_categories4').hide();
        $('#forexchange_categories5').hide();
        $('#hdn_submit_btn').show();
        $('#default_order_btn_ptmove').hide();
        $('#default_order_btn_respite').hide();
        $('#default_order_btn').hide();
        $(".o2_concentrator_follow_up_class").show();
      }
      else if($(this).val() == '3')
      {
        var count = parseInt($('.exchange-counter').val());
        var new_count = parseInt($('.new-exchange-counter').val());
        if(count<1 && new_count<1)
        {
          $('#forexchange_categories3').find(".panel-body").html("<div class='col-xs-12'>No items to be exchanged.</div>");
        }
        $('.equipment_section').hide();
        $('.btn-recurring').hide();
        $('.btn-not-recurring').show();
        $('#forrecurring_categories').hide();
        $('.equipment_section_recurring').hide();
        $('#default_order_btn_recurring').hide();
        $('.recurring-input-checkbox-month').prop('checked', false);
        $('.recurring-input-radio-month').prop('checked', false);
        $('.recurring-input-checkbox-week').prop('checked', false);
        $('.recurring-input-radio-week').prop('checked', false);
        $('#fordelivery_categories').hide();
        $('#fordelivery_categories2').hide();
        $('#forpickup_categories').hide();
        $('#forpickup_categories2').hide();
        $('#forpickup_categories3').hide();
        $('#forpickup_categories4').hide();
        $('#forpickup_categories5').hide();
        $('#forpickup_categories6').hide();
        $('#forptmove_categories').hide();
        $('#forptmove_categories2').hide();
        $('#forptmove_categories3').hide();
        $('#forptmove_categories4').hide();
        $('#forptmove_categories5').hide();
        $('.forptmove_emergency_contact').hide();
        $('#forrespite_categories').hide();
        $('#forrespite_categories2').hide();
        $('#forrespite_categories3').hide();
        $('#forrespite_categories4').hide();
        $('#forrespite_categories5').hide();
        $('#wrapper_equip_1').show();
        $('#forexchange_categories').show();
        $('#forexchange_categories2').show();
        $('#forexchange_categories3').show();
        $('#forexchange_categories4').show();
        $('#forexchange_categories5').show();
        $('#default_order_btn_ptmove').hide();
        $('#default_order_btn_respite').hide();
        $('#default_order_btn').hide();
        $(".o2_concentrator_follow_up_class").show();
        //$('#hdn_submit_btn').show();
      }
      else if($(this).val() == '4')
      {
        var count = parseInt($('.exchange-counter').val());
        if(count<1)
        {
          $('#forptmove_categories4').find(".panel-body").html("<div class='col-xs-12'>No items to be pickedup.</div>");
        }
        $('.equipment_section').show();
        $('.btn-recurring').hide();
        $('.btn-not-recurring').show();
        $('#forrecurring_categories').hide();
        $('.equipment_section_recurring').hide();
        $('#default_order_btn_recurring').hide();
        $('.recurring-input-checkbox-month').prop('checked', false);
        $('.recurring-input-radio-month').prop('checked', false);
        $('.recurring-input-checkbox-week').prop('checked', false);
        $('.recurring-input-radio-week').prop('checked', false);
        $('#fordelivery_categories').hide();
        $('#fordelivery_categories2').hide();
        $('#forpickup_categories').hide();
        $('#forpickup_categories2').hide();
        $('#forpickup_categories3').hide();
        $('#forpickup_categories4').hide();
        $('#forpickup_categories5').hide();
        $('#forpickup_categories6').hide();
        $('#forptmove_categories').show();
        $('#forptmove_categories2').show();
        $('#forptmove_categories3').show();
        $('#forptmove_categories4').show();
        $('#forptmove_categories5').show();
        $('.forptmove_emergency_contact').show();
        $('#forrespite_categories').hide();
        $('#forrespite_categories2').hide();
        $('#forrespite_categories3').hide();
        $('#forrespite_categories4').hide();
        $('#forrespite_categories5').hide();
        $('#wrapper_equip_1').show();
        $('#forexchange_categories').hide();
        $('#forexchange_categories2').hide();
        $('#forexchange_categories3').hide();
        $('#forexchange_categories4').hide();
        $('#forexchange_categories5').hide();
        $('#hdn_submit_btn').hide();
        $('#default_order_btn_ptmove').show();
        $('#default_order_btn_respite').hide();
        $('#default_order_btn').hide();
        $(".o2_concentrator_follow_up_class").show();
      }
      else if($(this).val() == '5')
      {
        $('.equipment_section').show();
        $('.btn-recurring').hide();
        $('.btn-not-recurring').show();
        $('#forrecurring_categories').hide();
        $('.equipment_section_recurring').hide();
        $('#default_order_btn_recurring').hide();
        $('.recurring-input-checkbox-month').prop('checked', false);
        $('.recurring-input-radio-month').prop('checked', false);
        $('.recurring-input-checkbox-week').prop('checked', false);
        $('.recurring-input-radio-week').prop('checked', false);
        $('#fordelivery_categories').hide();
        $('#fordelivery_categories2').hide();
        $('#forpickup_categories').hide();
        $('#forpickup_categories2').hide();
        $('#forpickup_categories3').hide();
        $('#forpickup_categories4').hide();
        $('#forpickup_categories5').hide();
        $('#forpickup_categories6').hide();
        $('#forptmove_categories').hide();
        $('#forptmove_categories2').hide();
        $('#forptmove_categories3').hide();
        $('#forptmove_categories4').hide();
        $('#forptmove_categories5').hide();
        $('.forptmove_emergency_contact').hide();
        $('#forrespite_categories').show();
        $('#forrespite_categories2').show();
        $('#forrespite_categories3').show();
        $('#forrespite_categories4').show();
        $('#forrespite_categories5').show();
        //$('#wrapper_equip_1').hide();
        $('#forexchange_categories').hide();
        $('#forexchange_categories2').hide();
        $('#forexchange_categories3').hide();
        $('#forexchange_categories4').hide();
        $('#forexchange_categories5').hide();
        $('#hdn_submit_btn').hide();
        $('#default_order_btn_ptmove').hide();
        $('#default_order_btn_respite').show();
        $('#default_order_btn').hide();
        $(".o2_concentrator_follow_up_class").hide();
      }
      else if($(this).val() == '6')
      {
        $('.equipment_section').hide();
        $('#forrecurring_categories').show();
        $('.equipment_section_recurring').show();
        $('#default_order_btn_recurring').show();
        $('.btn-recurring').show();
        $('.btn-not-recurring').hide();
        $('#fordelivery_categories').hide();
        $('#fordelivery_categories2').hide();
        $('#forpickup_categories').hide();
        $('#forpickup_categories2').hide();
        $('#forpickup_categories3').hide();
        $('#forpickup_categories4').hide();
        $('#forpickup_categories5').hide();
        $('#forpickup_categories6').hide();
        $('#forptmove_categories').hide();
        $('#forptmove_categories2').hide();
        $('#forptmove_categories3').hide();
        $('#forptmove_categories4').hide();
        $('#forptmove_categories5').hide();
        $('.forptmove_emergency_contact').hide();
        $('#forrespite_categories').hide();
        $('#forrespite_categories2').hide();
        $('#forrespite_categories3').hide();
        $('#forrespite_categories4').hide();
        $('#forrespite_categories5').hide();
        //$('#wrapper_equip_1').hide();
        $('#forexchange_categories').hide();
        $('#forexchange_categories2').hide();
        $('#forexchange_categories3').hide();
        $('#forexchange_categories4').hide();
        $('#forexchange_categories5').hide();
        $('#hdn_submit_btn').hide();
        $('#default_order_btn_ptmove').hide();
        $('#default_order_btn_respite').hide();
        $('#default_order_btn').hide();
        $(".o2_concentrator_follow_up_class").hide();
      }
      else
      {
        $('.equipment_section').show();
        $('.btn-recurring').hide();
        $('.btn-not-recurring').show();
        $('#forrecurring_categories').hide();
        $('.equipment_section_recurring').hide();
        $('.recurring-input-checkbox-month').prop('checked', false);
        $('.recurring-input-radio-month').prop('checked', false);
        $('.recurring-input-checkbox-week').prop('checked', false);
        $('.recurring-input-radio-week').prop('checked', false);
        $('#fordelivery_categories').show();
        $('#fordelivery_categories2').show();
        $('#forpickup_categories').hide();
        $('#forpickup_categories2').hide();
        $('#forpickup_categories3').hide();
        $('#forpickup_categories4').hide();
        $('#forpickup_categories5').hide();
        $('#forpickup_categories6').hide();
        $('#forptmove_categories').hide();
        $('#forptmove_categories2').hide();
        $('#forptmove_categories3').hide();
        $('#forptmove_categories4').hide();
        $('#forptmove_categories5').hide();
        $('.forptmove_emergency_contact').hide();
        $('#forrespite_categories').hide();
        $('#forrespite_categories2').hide();
        $('#forrespite_categories3').hide();
        $('#forrespite_categories4').hide();
        $('#forrespite_categories5').hide();
        $('#wrapper_equip_1').show();
        $('#forexchange_categories').hide();
        $('#forexchange_categories2').hide();
        $('#forexchange_categories3').hide();
        $('#forexchange_categories4').hide();
        $('#forexchange_categories5').hide();
        $('#hdn_submit_btn').hide();
        $('#default_order_btn_ptmove').hide();
        $('#default_order_btn_respite').hide();
        $('#default_order_btn').show();
        $(".o2_concentrator_follow_up_class").show();
      }
   });


/** To uncheck radio buttons in activity type **/
var allRadios = document.getElementsByName('activity_type');
var genderRadios = document.getElementsByName('relationship_gender');
var ptmove_old_address = document.getElementsByName('ptmove_old_address');
var weekRadios = document.getElementsByName('week');

var booRadio;
var x = 0;
for(x = 0; x < allRadios.length; x++){
    allRadios[x].onclick = function(){
      if(booRadio == this){
          $('#fordelivery_categories').hide();
          $('#fordelivery_categories2').hide();
          $('#forpickup_categories').hide();
          $('#forpickup_categories2').hide();
          $('#forpickup_categories3').hide();
          $('#forpickup_categories4').hide();
          $('#forpickup_categories5').hide();
          $('#forpickup_categories6').hide();
          $('#forptmove_categories').hide();
          $('#forptmove_categories2').hide();
          $('#forptmove_categories3').hide();
          $('#forptmove_categories4').hide();
          $('.forptmove_emergency_contact').hide();
          $('#forrespite_categories').hide();
          $('#forrespite_categories2').hide();
          $('#forrespite_categories3').hide();
          $('#forrespite_categories4').hide();
          $('#forrespite_categories5').hide();
          $('#wrapper_equip_1').show();
          $('#forexchange_categories').hide();
          $('#forexchange_categories2').hide();
          $('#forexchange_categories3').hide();
          $('#forexchange_categories4').hide();
          $('#forexchange_categories5').hide();
          this.checked = false;
          booRadio = null;
      }
      else
      {
      booRadio = this;
      }
  };
}

for(x = 0; x < genderRadios.length; x++){
    genderRadios[x].onclick = function(){
      if(booRadio == this){
          this.checked = false;
          booRadio = null;
      }
      else
      {
      booRadio = this;
      }
  };
}

//to uncheck the old address in cus move after picking up the items from that old address
for(x = 0; x < ptmove_old_address.length; x++){
    ptmove_old_address[x].onclick = function(){
      if(booRadio == this){
          this.checked = false;
          booRadio = null;
      }
      else
      {
      booRadio = this;
      }
  };
}

for(x = 0; x < weekRadios.length; x++){
    weekRadios[x].onclick = function(){
      var _this = this;
      var recurring_id = $(this).attr("data-recurring-id");
      if(booRadio == this){
        jConfirm('Are you sure you want to cancel Recurring Order?', 'Reminder', function(response){
          if(response)
          {
            _this.checked = false;
            booRadio = null;

            me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Cancelling Recurring Order ..."});
            $.post(base_url + 'order/cancel_recurring_order/' + recurring_id ,function(response){
              var obj = $.parseJSON(response);
              me_message_v2(obj);
              if(obj['error'] == 0)
              {
                //trigger notification
                $.post(base_url+"main/trigger_gcm",function(){});

                //$(_this_btn).prop('disabled',true);
                $('#submit_order_loader').modal("hide");
                setTimeout(function(){
                  location.reload();
                },1500);
              }

              // setTimeout(function(){
              //   $(_this_btn).prop('disabled',false);
              // },3500);

            });
          }
        });
      }
      else
      {
        booRadio = this;
      }


  };
}


//End



/** Read a page's GET URL variables and return them as an associative array. **/
function getParams()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

var param = getParams()["type"];
if(param == 2)
{
   $('#radio_pickup2').attr('checked',true);
   $('#forpickup_categories').show();
   $('#forpickup_categories2').show();
   $('#forptmove_categories').hide();
   $('#forrespite_categories').hide();
   $('#forrespite_categories2').hide();
   $('#forexchange_categories').hide();
}
else if(param == 3)
{
  $('#radio_pickup3').attr('checked',true);
  $('#forpickup_categories').hide();
  $('#forpickup_categories2').hide();
  $('#forptmove_categories').hide();
  $('#forrespite_categories').hide();
  $('#forrespite_categories2').hide();
  $('#forexchange_categories').show();
}
else if(param == 4)
{
   $('#radio_pickup4').attr('checked',true);
   $('#radio_pickup4').prop('checked', true)

   $('#forpickup_categories').hide();
   $('#forpickup_categories2').hide();
   $('#forptmove_categories').show();
   $('#forrespite_categories').hide();
   $('#forrespite_categories2').hide();
   $('#forexchange_categories').hide();
}
else if(param == 5)
{
   $('#radio_pickup5').attr('checked',true);
   $('#forpickup_categories').hide();
   $('#forpickup_categories2').hide();
   $('#forptmove_categories').hide();
   $('#forrespite_categories').show();
   $('#forrespite_categories2').show();
   $('#forexchange_categories').hide();
}
else
{
   $('#forpickup_categories').hide();
   $('#forpickup_categories2').hide();
   $('#forptmove_categories').hide();
   $('#forrespite_categories').hide();
   $('#forrespite_categories2').hide();
   $('#forexchange_categories').hide();
}
/** End **/


/** Animate Arrow that will assist user in order summary **/
// var animateArrow = function(targetElement, speed){
//   $(targetElement).css({
//     marginLeft:'10px'
//   });
//   $(targetElement).animate(
//       {
//         'margin-left': $(document).width() - 720
//       },
//       {
//       duration: speed,
//       complete: function(){
//           animateArrow(this, speed);
//           }
//       }
//   );
// };

// $('.change_activity').click(function(){
//   $('#directional-arrow').show();
//   animateArrow($('#directional-arrow'),4000);
// });

/** End **/



   //for pickup dropdown
   $('#pickup_select').on('change',function(){
      if($(this).val() == 'expired')
      {
         $('#p_date_expired').css('display','block');
      }
      else if($(this).val() == 'discharged'){
         $('#p_date_expired').css('display','block');
      }
      else if($(this).val() == 'revoked'){
         $('#p_date_expired').css('display','block');
      }
      else{
         $('#p_date_expired').css('display','none');
         $('#p_date_discharge').css('display','none');
         $('#p_date_revoked').css('display','none');
      }
   });

   //to show/hide capped and noncapped items
   $('#equip_1').on('click',function(){
      $(this).next().next('.equipment').slideToggle('fast');
      //$(this).next('.equipment').next('label').slideToggle('fast');
      $('#equip_2').next().next('.equipment').hide();
      $('#equip_3').next().next('.equipment').hide();
      $('#disposable_items_btn').next().next('.equipment').hide();

   });

   $('#equip_2').on('click',function(){
      $(this).next().next('.equipment').slideToggle('fast');
      $('#equip_1').next().next('.equipment').hide();
      $('#equip_3').next().next('.equipment').hide();
      $('#disposable_items_btn').next().next('.equipment').hide();
   });

   $('#equip_3').on('click',function(){
      $(this).next().next('.equipment').slideToggle('fast');
      $('#equip_1').next().next('.equipment').hide();
      $('#equip_2').next().next('.equipment').hide();
   });

   $('#disposable_items_btn').on('click',function(){
      $(this).next().next('p').next('.equipment').slideToggle('fast');
      $('#equip_1').next().next('.equipment').hide();
      $('#equip_2').next().next('.equipment').hide();

   });

   //to show/hide capped and noncapped items - RECURRING
 $('#equip_recurring_1').on('click',function(){
    $(this).next().next('.equipment').slideToggle('fast');
    //$(this).next('.equipment').next('label').slideToggle('fast');
    $('#equip_recurring_2').next().next('.equipment').hide();
    $('#equip_recurring_3').next().next('.equipment').hide();
    $('#disposable_items_btn').next().next('.equipment').hide();

 });

 $('#equip_recurring_2').on('click',function(){
    $(this).next().next('.equipment').slideToggle('fast');
    $('#equip_recurring_1').next().next('.equipment').hide();
    $('#equip_recurring_3').next().next('.equipment').hide();
    $('#disposable_items_btn').next().next('.equipment').hide();
 });

 $('#equip_recurring_3').on('click',function(){
    $(this).next().next('.equipment').slideToggle('fast');
    $('#equip_recurring_1').next().next('.equipment').hide();
    $('#equip_recurring_2').next().next('.equipment').hide();
 });

 //to show/hide recurring options
 $('#equip_recurring_option_1').on('click',function(){
  $('#recurring_option_1').slideToggle('fast');
  //$(this).next('.equipment').next('label').slideToggle('fast');
  $('.recurring-input-checkbox-month').prop('checked', false);
  $('.recurring-input-radio-month').prop('checked', false);
  $(this).addClass("active");
  $('#equip_recurring_option_2').removeClass("active");
  $('#recurring_option_2').hide();
 });

 $('#equip_recurring_option_2').on('click',function(){
  $('#recurring_option_2').slideToggle('fast');
  //$(this).next('.equipment').next('label').slideToggle('fast');
  $('.recurring-input-checkbox-week').prop('checked', false);
  $('.recurring-input-radio-week').prop('checked', false);
  $(this).addClass("active");
  $('#equip_recurring_option_1').removeClass("active");
  $('#recurring_option_1').hide();
 });

 //to show quantity modal for recurring
 $('.recurring_item').on('click',function(){
   var modal_title = $(this).attr("data-desc");
   var data_name = $(this).attr("data-name");
   var data_category_id = $(this).attr("data-category-id");
   var modal_id = $(this).val();
   $('#recurring_equipment_quantity').attr("data-desc", modal_title);
   $('#recurring_equipment_quantity').attr("data-name", data_name);
   $('#recurring_equipment_quantity').attr("data-category-id", data_category_id);
   $('#recur_equip_id').val(modal_id);
   $('#myModalLabelRecurring').text(modal_title);
   $('#modal_recurring_label').text("Quantity of "+modal_title);
 });

 $('.recur_sched_week').on('click',function(){
  var week = $(this).attr("data-radio");
  var monthRadio = document.getElementsByClassName('recurring-schedule-week');
  for (var i = 0; i < monthRadio.length; i++) {
    if(monthRadio[i].value == week) {
      monthRadio[i].checked = true;
    }
  }

  var days = $(this).attr("data-days");
  var temp_days = days.split("-");
  var daysCheckbox = document.getElementsByClassName('recurring-input-checkbox');
  if(week == "ew" || week == "ew2" || week == "ew3"){
    daysCheckbox = document.getElementsByClassName('recurring-input-week');
  } else {
    daysCheckbox = document.getElementsByClassName('recurring-input-month');
  }
  for(var j = 0; j < temp_days.length; j++) {
    for (var i = 0; i < daysCheckbox.length; i++) {
      if(daysCheckbox[i].value ==  temp_days[j]) {
        daysCheckbox[i].checked = true;
      }
    }
  }

  $.post(base_url + 'order/generate_recurring_orders/',function(response){
    var obj = $.parseJSON(response);
    console.log('obj', obj);

  });

 });

//to validate if the user want to change the recurring order if there is an existing recurrign order
  $('.recur_option_1').on('click',function(){
    var recurring_id = $(this).attr('data-recurring-id');
    jConfirm('Are you sure you want to cancel Recurring Order?', 'Reminder', function(response){
      if(response)
      {
        me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Cancelling Recurring Order ..."});
        $.post(base_url + 'order/cancel_recurring_order/' + recurring_id ,function(response){
          var obj = $.parseJSON(response);
          me_message_v2(obj);
          if(obj['error'] == 0)
          {
            //trigger notification
            $.post(base_url+"main/trigger_gcm",function(){});

            //$(_this_btn).prop('disabled',true);
            $('#submit_order_loader').modal("hide");
            setTimeout(function(){
              location.reload();
            },1500);
          }

          // setTimeout(function(){
          //   $(_this_btn).prop('disabled',false);
          // },3500);

        });
        // $('#recurring_option_1').slideToggle('fast');
        // //$(this).next('.equipment').next('label').slideToggle('fast');
        // $('.recurring-input-checkbox-month').prop('checked', false);
        // $('.recurring-input-radio-month').prop('checked', false);
        // $('.recur_option_1').addClass("active");
        // $('.recur_option_2').removeClass("active");
        // $('#recurring_option_2').hide();
      }
    });
  });

  $('.recur_option_2').on('click',function(){
    var recurring_id = $(this).attr('data-recurring-id');
    jConfirm('Are you sure you want to cancel Recurring Order?', 'Reminder', function(response){
      if(response)
      {
        me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Cancelling Recurring Order ..."});
        $.post(base_url + 'order/cancel_recurring_order/' + recurring_id ,function(response){
          var obj = $.parseJSON(response);
          me_message_v2(obj);
          if(obj['error'] == 0)
          {
            //trigger notification
            $.post(base_url+"main/trigger_gcm",function(){});

            //$(_this_btn).prop('disabled',true);
            $('#submit_order_loader').modal("hide");
            setTimeout(function(){
              location.reload();
            },1500);
          }

          // setTimeout(function(){
          //   $(_this_btn).prop('disabled',false);
          // },3500);

        });
        // $('#recurring_option_2').slideToggle('fast');
        // //$(this).next('.equipment').next('label').slideToggle('fast');
        // $('.recurring-input-checkbox-week').prop('checked', false);
        // $('.recurring-input-radio-week').prop('checked', false);
        // $('.recur_option_2').addClass("active");
        // $('.recur_option_1').removeClass("active");
        // $('#recurring_option_1').hide();
      }
    });
  });

   //for the auto populate of the phone number depending in each hospice

   var has_hospice_provider = function()
   {
      var hospice_provider = $('.hospice_select').val();

      if(hospice_provider != 0)
      {
        $('#patient_mrn').removeAttr('readonly');
      }
      else
      {
        $('#patient_mrn').prop('readonly',true);
      }
   }

   has_hospice_provider();

   $('.hospice_select').bind('change',function(){
      var hospice_id = $(this).val();
      var response_data = "";
      var contact_num_data = $('#person_num').val();

      //to detect the items base on hospice
      if(hospice_id == 0)
      {
        window.location.href = base_url + "order/create_order";
        // $.ajax({
        //   type: "POST",
        //   url: base_url + "hospice/get_hosp_contact/" + hospice_id,
        //   async: false,
        //   success:function(response)
        //   {
        //      response_data = $.parseJSON(response);
        //      $('.hosp_office_num').val(response_data['contact_num']);

        //   },
        //   error:function(jqXHR, textStatus, errorThrown)
        //   {
        //      console.log(textStatus, errorThrown);
        //   },
        // });
      }
      else
      {
        window.location.href = base_url + "order/create_order/" + hospice_id;
        // $.ajax({
        //   type: "POST",
        //   url: base_url + "hospice/get_hosp_contact/" + hospice_id,
        //   async: false,
        //   success:function(response)
        //   {
        //      response_data = $.parseJSON(response);
        //      $('.hosp_office_num').val(response_data['contact_num']);

        //   },
        //   error:function(jqXHR, textStatus, errorThrown)
        //   {
        //      console.log(textStatus, errorThrown);
        //   },
        // });
      }

   });

  //to get the id of the hospice selected in hospice provider
  var href = window.location.href;
  var final_href = href.substr(href.lastIndexOf('/') + 1);
  $('#hdn_hospice_id').val(final_href);




   //for the selecting of entries to be shown in patient profile
   $('.select_entries').bind('change',function(){
      var entries = $(this).val();
      var response_parsed = "";

      $.ajax({
         type:"POST",
         url: base_url + "client_order/gridview/" + entries,
         async: false,
         success:function(response)
         {
            window.location.href = base_url + "client_order/gridview/" + entries;

         },
         error:function(jqXHR, textStatus, errorThrown)
         {
            console.log(textStatus,errorThrown);
         }

      });
   });
   //End


   //for the changing of view according to the selected kind of view (patient profile)
      // $('.select_view').bind('change',function(){
      //    if($(this).val() == 'list-view')
      //    {
      //       window.location.href = base_url + "client_order/list_orders";
      //    }
      //    else
      //    {
      //       window.location.href = base_url + "client_order/gridview";
      //    }
      // });
   //

   //patients with order
   $('.select_sort_by').bind('change',function(){
    var hospice_id = $(this).val();

      if(hospice_id == "all")
      {
        window.location.href = base_url+"order/order_list/grid-view";
      }
      else
      {
        window.location.href = base_url+"order/sort_by_hospice/"+hospice_id;
      }
  });

   $('.select_sort_itemList_by').bind('change',function(){
    var hospice_id = $(this).val();
    console.log(hospice_id);
    window.location.href = base_url+"equipment/all_equipments_by_hospice/"+hospice_id;
  });

  //patients with no order
   $('.select_sort_by_noorder').bind('change',function(){
    var hospice_id = $(this).val();

      if(hospice_id == "all")
      {
        window.location.href = base_url+"draft_patient/customers";
      }
      else
      {
        window.location.href = base_url+"draft_patient/sort_by_hospice/"+hospice_id;
      }
  });




   // $('#delivery_date').on('click',function(){
   // $('#patient_mrn').css('border','1px solid red');
   // $('#patient_mrn').prop('disabled',false);

// });

// $('#patient_mrn').on('keyup',function(){
   // $('#patient_mrn').css('border','1px solid rgba(234, 234, 234, 1) !important');
   // $('.radio_act_type').prop('disabled',false);
// });

// $('.radio_act_type').bind('click',function(){
   // $('.patient_info').prop('disabled',false);
   //$('.pickup_sub').css('border','1px solid red');
// });


// $('.patient_info').bind('keyup',function(){
   //$('.pickup_sub').css('border','1px solid rgba(234, 234, 234, 1) !important');
   // $('.patient_info').prop('disabled',false);
   // $('.patient_info').css('border','1px solid rgba(234, 234, 234, 1) !important');
// });



  var globalTimeout = null;
  $('.liter_flow_field').bind('keyup',function(){
    var id = $(this).attr("data-id");
    var category_id = $(this).attr("data-category-id");
    var value_here = $(this).val();
    var pt_move_sign = 0;

    if($.isNumeric(value_here)) {
      if(value_here > 30) {
        jAlert("Cannot enter liter flow greater than 30.",'Reminder');
        $(this).val("");
      } else {

        if(globalTimeout != null) clearTimeout(globalTimeout);
          globalTimeout =setTimeout(SearchFunc,1500);

        function SearchFunc(){
          globalTimeout = null;

          if(id == 61)
          {
            var container = $("body").find(".oxygen_concentrator_div");
            var totalLiterFlow = $('.total_liter_flow').val();
            if(totalLiterFlow > 10) {
              if(totalLiterFlow > 15) {
                var temp = "<input type='hidden' class='temporary_class' id='noncapped_10l' value='343' name='equipments[]'>";
                var temp_2 = "<input type='hidden' class='temporary_class' id='capped_10l' value='325' name='equipments[]'>";
              } else {
                var temp = "<input type='hidden' class='temporary_class' id='capped_5l' value='316' name='equipments[]'>";
                var temp_2 = "<input type='hidden' class='temporary_class' id='capped_10l' value='325' name='equipments[]'>";
            }
            } else {
              var temp = "<input type='hidden' class='temporary_class' id='capped_5l' value='316' name='equipments[]'>";
              var temp_2 = "<input type='hidden' class='temporary_class' id='capped_10l' value='325' name='equipments[]'>";
            }
          }
          else
          {
            var container = $("body").find(".oxygen_concentrator_div");
            var temp = "<input type='hidden' class='temporary_class' id='noncapped_5l' value='334' name='equipments[]'>";
            var temp_2 = "<input type='hidden' class='temporary_class' id='noncapped_10l' value='343' name='equipments[]'>";
          }

          if(category_id == 1)
          {
            var five_liter_sign = 0;
            var ten_liter_sign = 0;
            var vals = $('.capped_items').map(function(){
              if($(this).val() == 316)
              {
                five_liter_sign = 1;
              }
              else if($(this).val() == 325)
              {
                ten_liter_sign = 1;
              }
            }).get();

            if($.isNumeric(value_here))
            {
              if($("body").find("#radio_pickup4").is(':checked'))
              {
                pt_move_sign = 1;
              }
              if(value_here < 5 || value_here == 5)
              {
                if(five_liter_sign == 1)
                {
                  $('#oxygen_concentrator_1').modal({
                       backdrop: 'static',
                       keyboard: false
                  });

                  if(pt_move_sign == 0)
                  {
                    $('#duplicate_capped_modal').modal({
                      backdrop: 'static',
                      keyboard: false
                    });
                    $('#duplicate_capped_modal').modal("show");
                      _checker = true;

                    $('#oxygen_concentrator_1').modal("hide");
                      _checker = true;
                  }
                  else
                  {
                    $('.5_ltr').prop('checked', true);
                    $('.10_ltr').prop('checked', false);
                    container.html("");
                    container.append(temp);
                  }
                }
                else
                {
                  $('.5_ltr').prop('checked', true);
                  $('.10_ltr').prop('checked', false);
                  container.html("");
                  container.append(temp);
                }
              }
              else if(value_here <= 10)
              {
                if(ten_liter_sign == 1)
                {
                  $('#oxygen_concentrator_1').modal({
                       backdrop: 'static',
                       keyboard: false
                  });
                  if(pt_move_sign == 0)
                  {
                    $('#duplicate_capped_modal').modal({
                         backdrop: 'static',
                         keyboard: false
                       });
                     $('#duplicate_capped_modal').modal("show");
                     _checker = true;

                     $('#oxygen_concentrator_1').modal("hide");
                    _checker = true;
                  }
                  else
                  {
                    $('.10_ltr').prop('checked', true);
                    $('.5_ltr').prop('checked', false);
                    container.html("");
                    container.append(temp_2);
                  }
                }
                else
                {
                  $('.10_ltr').prop('checked', true);
                  $('.5_ltr').prop('checked', false);
                  container.html("");
                  container.append(temp_2);
                }
              }
              else
              {
                if(five_liter_sign == 1 || ten_liter_sign == 1)
                {
                  $('#oxygen_concentrator_1').modal({
                       backdrop: 'static',
                       keyboard: false
                  });

                  if(pt_move_sign == 0)
                  {
                    $('#duplicate_capped_modal').modal({
                       backdrop: 'static',
                       keyboard: false
                     });
                   $('#duplicate_capped_modal').modal("show");
                   _checker = true;

                    $('#oxygen_concentrator_1').modal("hide");
                    _checker = true;
                  }
                  else
                  {
                    $('.5_ltr').prop('checked', true);
                    $('.10_ltr').prop('checked', true);
                    container.html("");
                    container.append(temp);
                    container.append(temp_2);
                  }
                }
                else
                {
                  $('.5_ltr').prop('checked', true);
                  $('.10_ltr').prop('checked', true);
                  container.html("");
                  container.append(temp);
                  container.append(temp_2);
                }
              }

              $(this).css('border','1px solid rgba(234, 234, 234, 1) !important');
            }
            else
            {
              $(this).val('');
              $(this).css('border','1px solid red');
            }
          }
          else
          {
            if($.isNumeric(value_here))
            {
              if(value_here < 5 || value_here == 5)
              {
                $('.liter_flow_field_hidden').val(value_here);
                $('.5_ltr').prop('checked', true);
                $('.10_ltr').prop('checked', false);
                container.html("");
                container.append(temp);
              }
              else if(value_here <= 10)
              {
                $('.liter_flow_field_hidden').val(value_here);
                $('.10_ltr').prop('checked', true);
                $('.5_ltr').prop('checked', false);
                container.html("");
                container.append(temp_2);
              }
              else
              {
                // $('.5_ltr').prop('checked', true);
                // $('.10_ltr').prop('checked', true);
                $('.liter_flow_field_hidden').val(value_here);
                // $('.c-oxygen_concentrator-2').click();
                // $('#oxygen_concentrator_2').modal('hide');
                container.html("");
                container.append(temp);
                container.append(temp_2);
                var tempvaluehere = parseInt(value_here) - 10;
                $('#liter_flow_field_2').val(value_here);
                if(value_here <= 15) {
                  $('.capdisp5').show();
                  $('.nondisp5').hide();
                  $('.capped_10liter').prop('checked', true);
                  $('.capped_5liter').prop('checked', true);
                  // $('.capdisp5').attr("style", "display:none");
                  // $('.nondisp5').attr("style", "display:block");
                  console.log("updated successfuly!");
                  $('.noncapped_5liter').prop('checked', false);
                  $('.noncapped_10liter').prop('checked', false);
                  // jAlert("The customer will get two oxygen concentrators. <br> <br> <strong>1 10L oxygen contrator with 10 Liter Flow</strong> under capped and <strong>1 5L oxygen concentrator with "+tempvaluehere+" Liter Flow</strong> under noncapped.",'Reminder');
                } else if(value_here <= 20) {
                  $('#liter_flow_field_2').val(value_here);
                  $('.c-oxygen_concentrator-2').click();
                  $('#oxygen_concentrator_2').modal('hide');
                  $('.capped_10liter').prop('checked', true);
                  $('.capped_5liter').prop('checked', false);
                  $('.noncapped_10liter').prop('checked', true);
                  $('.noncapped_5liter').prop('checked', false);
                  jAlert("10L Oxygen Concentrator (X2)",'Reminder');
                }
                else {
                  jAlert("Cannot enter more than 20!",'Reminder');
                  $('#liter_flow_field_2').val("");
                  $('.liter_flow_field_hidden').val("");
                  $('.total_liter_flow').val("");
                }
                // else if(value_here <= 25) {
                //   $('#liter_flow_field_2').val(value_here);
                //   $('.c-oxygen_concentrator-2').click();
                //   $('#oxygen_concentrator_2').modal('hide');
                //   $('.capped_10liter').prop('checked', true);
                //   $('.capped_5liter').prop('checked', true);
                //   $('.noncapped_10liter').prop('checked', true);
                //   $('.noncapped_5liter').prop('checked', true);
                //   jAlert("10L Oxygen Concentrator (X2) <br> 5L Oxygen Concentrator (X1)",'Reminder');
                // } else if(value_here <= 30) {
                //   $('#liter_flow_field_2').val(value_here);
                //   $('.c-oxygen_concentrator-2').click();
                //   $('#oxygen_concentrator_2').modal('hide');
                //   $('.capped_10liter').prop('checked', true);
                //   $('.capped_5liter').prop('checked', false);
                //   $('.noncapped_10liter').prop('checked', true);
                //   $('.noncapped_5liter').prop('checked', false);
                //   jAlert("10L Oxygen Concentrator (X3)",'Reminder');
                // }
              }

              $(this).css('border','1px solid rgba(234, 234, 234, 1) !important');
            }
            else
            {
              $(this).val('');
              $(this).css('border','1px solid red');
            }
          }
        }
      }
    }

  });

  $('.capped_5liter').bind('click',function(){
    if($(this).is(":checked"))
    {
      var container = $("body").find(".oxygen_concentrator_div");
      var temp = "<input type='hidden' class='temporary_class' id='capped_5l' value='316' name='equipments[]'>";
      container.append(temp);
    }
    else
    {
      $("body").find(".oxygen_concentrator_div").find("#capped_5l").remove();
    }
  });

  $('.capped_10liter').bind('click',function(){
    if($(this).is(":checked"))
    {
      var container = $("body").find(".oxygen_concentrator_div");
      var temp = "<input type='hidden' class='temporary_class' id='capped_10l' value='325' name='equipments[]'>";
      container.append(temp);
    }
    else
    {
      $("body").find(".oxygen_concentrator_div").find("#capped_10l").remove();
    }
  });

  $('.noncapped_5liter').bind('click',function(){
    if($(this).is(":checked"))
    {
      var container = $("body").find(".oxygen_concentrator_div");
      var temp = "<input type='hidden' class='temporary_class' id='noncapped_5l' value='334' name='equipments[]'>";
      container.append(temp);
    }
    else
    {
      $("body").find(".oxygen_concentrator_div").find("#noncapped_5l").remove();
    }
  });

  $('.noncapped_10liter').bind('click',function(){
    if($(this).is(":checked"))
    {
      var container = $("body").find(".oxygen_concentrator_div");
      var temp = "<input type='hidden' class='temporary_class' id='noncapped_10l' value='343' name='equipments[]'>";
      container.append(temp);
    }
    else
    {
      $("body").find(".oxygen_concentrator_div").find("#noncapped_10l").remove();
    }
  });

  //check all checkboxes in equipments assigning
  $('.chkAll_equipments').bind('click',function(){
      $('.cb_equipment').not(this).prop('checked', this.checked);
      $('.btn-save-equipment').removeAttr('disabled');

      //$('input:checkbox').not(this).prop('checked', this.checked);
      // if($('input:checkbox:checked').length <= 0)
      // {
      //    $('.btn-save-equipment').attr('disabled','disabled');
      // }
   })


   //check all equipments to be assigned
   $('.cb_equipment').bind('click',function(){
      $('.btn-save-equipment').removeAttr('disabled');

      //$('input:checkbox').not(this).prop('checked', this.checked);
      if($('input:checkbox:checked').length <= 0)
      {
         $('.btn-save-equipment').attr('disabled','disabled');
      }
   });


  //to put strike through in each td when checkbox is checked
  $('.cancel_item_checkbox').on('click',function(){
    var _this = $(this);
    var medical_id = $(this).attr('data-id');
    var p_fname = $(this).attr('data-fname');
    var p_lname = $(this).attr('data-lname');
    var hospice_name = $(this).attr('data-hospice');
    var equipment_id = $(this).attr('data-equipment-id');
    var uniqueID = $(this).attr('data-order-unique-id');
    var patient_id = $(this).attr('data-patient-id');
    var canceled_status = "";
    var form_data = $('#canceled-order-form').serialize();
    var workorder_activity_type = $(".workorder_activity_type").val();
    var sequence_order = $(this).attr('data-cancel-sequence-order');
    var sequence_name = "#order_sequence_"+sequence_order;
    var original_orderID = 0;
    var order_id = $(this).attr('data-order-id');

    if(_this.is(":checked"))
    {
      _this.parents("tr").css('text-decoration','line-through');

      // Version 1
      // $('.serial_num'+equipment_id).val("---");
      // $('.serial_num'+equipment_id).attr("readonly","readonly");

      // Version 2 => Updated 02/05/2021
      $('.serial_num_order_id'+order_id).val("---");
      $('.serial_num_order_id'+order_id).attr("readonly","readonly");

      if(equipment_id == 67 || equipment_id == 40)
      {
        _this.parents("tr").siblings("#confirm_tr_156").css('text-decoration','line-through');
        $('.serial_num156').val("---");
        $('.serial_num156').attr("readonly","readonly");
        $('.checkbox_156').prop("checked",true);
        $('.checkbox_156').attr("disabled","disabled");
      }

      $("body").find("#adding_weight_sign").val(0);
      $("body").find("#adding_weight_equipment").val(0);

      canceled_status = 0;
      me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Cancelling the item ..."});
      $.post(base_url+"equipment/cancel_equipment/"+equipment_id+"/"+medical_id+"/"+canceled_status+"/"+workorder_activity_type+"/"+uniqueID+"/"+order_id+"/"+original_orderID, form_data, function(response){
        var obj = $.parseJSON(response);
        me_message_v2(obj);
        $.ajax({
          url:base_url+"order/show_patient_notes/"+medical_id+"/"+p_fname+"/"+p_lname+"/"+hospice_name+"/"+patient_id,
          type:"POST",
          success:function(response)
          {
            $('#reason_for_cancel').modal("show");
            $('#reason_for_cancel').find(".modal-body").html(response);
            $(sequence_name).val(obj['response_orderID']);
          }
        });
      });
    }
    else
    {
      original_orderID = $(sequence_name).val();
      
      // Version 1
      // $('.serial_num'+equipment_id).val("");
      // $('.serial_num'+equipment_id).prop("readonly",false);

      // Version 2 => Updated 02/05/2021
      $('.serial_num_order_id'+order_id).val("");
      $('.serial_num_order_id'+order_id).prop("readonly",false);

      _this.parents("tr").css('text-decoration','none');

      if(equipment_id == 67 || equipment_id == 40)
      {
        _this.parents("tr").siblings("#confirm_tr_156").css('text-decoration','none');
        $('.serial_num156').val("");
        $('.serial_num156').prop("readonly",false);
        $('.checkbox_156').prop("checked",false);
        $('.checkbox_156').removeAttr("disabled");
      }
      canceled_status = 1;
      me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Reverting the item to the orders..."});
      $.post(base_url+"equipment/cancel_equipment/"+equipment_id+"/"+medical_id+"/"+canceled_status+"/"+workorder_activity_type+"/"+uniqueID+"/"+order_id+"/"+original_orderID, form_data, function(response){
        var obj = $.parseJSON(response);
        me_message_v2(obj);
      });
    }
  });
  // $('.action_data').hide();
  // $('.save_edited_summary').hide();
  // $('.action_data').show();
  // $('.save_edited_summary').show();

  //to put strike through in each td when checkbox is checked
  $('.cancel_item_checkbox_exchange').on('click',function(){
    var _this = $(this);
    var medical_id = $(this).attr('data-id');
    var p_fname = $(this).attr('data-fname');
    var p_lname = $(this).attr('data-lname');
    var hospice_name = $(this).attr('data-hospice');
    var equipment_id = $(this).attr('data-equipment-id');
    var uniqueID = $(this).attr('data-order-unique-id');
    var patient_id = $(this).attr('data-patient-id');
    var canceled_status = "";
    var form_data = $('#canceled-order-form').serialize();
    var workorder_activity_type = $(".workorder_activity_type").val();
    var sequence_order = $(this).attr('data-cancel-sequence-order');
    var sequence_name = "#order_sequence_"+sequence_order;
    var original_orderID = 0;

    if(_this.is(":checked"))
    {
      _this.parents("tr").css('text-decoration','line-through');
      _this.parents("tr").siblings("#confirm_exchange_tr_"+equipment_id).css('text-decoration','line-through');
      $('.serial_num'+equipment_id).val("---");
      $('.serial_num'+equipment_id).attr("readonly","readonly");
      $('.pickup_date_'+equipment_id).attr("disabled","disabled");
      $("body").find('.checkbox_'+equipment_id).prop("checked", true);
      canceled_status = 0;
      me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Cancelling the item ..."});
      $.post(base_url+"equipment/cancel_equipment_exchange/"+equipment_id+"/"+medical_id+"/"+canceled_status+"/"+workorder_activity_type+"/"+uniqueID+"/"+original_orderID, form_data, function(response){
        var obj = $.parseJSON(response);
        me_message_v2(obj);
        $.ajax({
          url:base_url+"order/show_patient_notes/"+medical_id+"/"+p_fname+"/"+p_lname+"/"+hospice_name+"/"+patient_id,
          type:"POST",
          success:function(response)
          {
            $('#reason_for_cancel').modal("show");
            $('#reason_for_cancel').find(".modal-body").html(response);
            $(sequence_name).val(obj['response_orderID']);
          }
        });
      });
    }
    else
    {
      original_orderID = $(sequence_name).val();
      $('.serial_num'+equipment_id).val("");
      $('.serial_num'+equipment_id).prop("readonly",false);
      _this.parents("tr").css('text-decoration','none');
      _this.parents("tr").siblings("#confirm_exchange_tr_"+equipment_id).css('text-decoration','none');
      $('.pickup_date_'+equipment_id).removeAttr("disabled");
      $("body").find('.checkbox_'+equipment_id).prop("checked", false);

      canceled_status = 1;
      me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Reverting the item to the orders..."});
      $.post(base_url+"equipment/cancel_equipment_exchange/"+equipment_id+"/"+medical_id+"/"+canceled_status+"/"+workorder_activity_type+"/"+uniqueID+"/"+original_orderID, form_data, function(response){
        var obj = $.parseJSON(response);
        me_message_v2(obj);
      });
    }
  });


//to update order date of the same work order number
$('.order_date').change(function(){
  var _this = $(this);
  var _thisAttr = $(this).attr('data-order-unique-id');

  var tableContent = $('.edit_patient_orders tbody tr td .hdn_unique_id');

  tableContent.each(function(n){
    if($(this).val() == _thisAttr)
    {
      $('.looped_order_date'+_thisAttr).val(_this.val());
    }
  });

});


$('.pickup_date').change(function(){
  var _this = $(this);
  var _thisAttr = $(this).attr('data-work-order');

  var tableContent = $('.edit_patient_orders tbody tr td .hdn_unique_id');

  tableContent.each(function(n){
    if($(this).val() == _thisAttr)
    {
      $('.auto_fillout_pickedup'+_thisAttr).val(_this.val());
      $(".input_actual_order_date").val(_this.val());
    }
  });
});

$('.pickup_date_exchange').change(function()
{
  var _this = $(this);
  $('.auto_fillout_pickedup').val(_this.val());
  $(".input_actual_order_date").val(_this.val());
});

  //to save the patient weight base
  $('.save_weight_btn').bind('click',function(response){
      var form_data = $('#insert_patient_weight').serialize();

      jConfirm('Save Customer Weight?', 'Reminder', function(response){

        $.post(base_url+"equipment/insert_patient_weight", form_data, function(response){
            var obj = $.parseJSON(response);

            jAlert(obj['message'],'Reminder');

            $('#patient_weight').modal("hide");

        });

      })
  });


  //to save the lot number for m6 and e cylinder items
  $('.save_lot_num_btn').bind('click',function(){
    var form_data = $('#insert_lot_numbers').serialize();

    jConfirm('Save Lot Number?', 'Reminder', function(response){

        $.post(base_url+"equipment/insert_lot_number", form_data, function(response){
            var obj = $.parseJSON(response);

            jAlert(obj['message'],'Reminder');

            $('#lot_numbers').modal("hide");

        });

      })
  });

  //automatically check the commode pail when commode 3in1 is selected
  $('.c-commode_3_in_1-2').bind('click',function(){
    var commode_pail_quantity = $("body").find('.commode_pail_quantity').val();
    if(commode_pail_quantity.length == 0)
    {
      commode_pail_quantity = 0;
    }

    if($(this).is(":checked"))
    {
      $('.c-commode_pail-3').prop('checked',true);

      var commode_pail_count = $("body").find(".commode_pail_counter").val();
      $("body").find(".commode_pail_counter").val(Number(commode_pail_count)+1);
      $("body").find('.commode_pail_quantity').val(Number(commode_pail_quantity)+1);
    }
    else
    {
      var commode_pail_count = $("body").find(".commode_pail_counter").val();
      var new_commode_pail_count = Number(commode_pail_count)-1;

      if((Number(commode_pail_quantity)-1) == 0)
      {
        $('.c-commode_pail-3').prop('checked',false);
      }

      $("body").find(".commode_pail_counter").val(Number(commode_pail_count)-1);
      $("body").find('.commode_pail_quantity').val(Number(commode_pail_quantity)-1);
    }
  });

  $('.c-commode_3_and_1-1').bind('click',function(){
    var commode_pail_quantity = $("body").find('.commode_pail_quantity').val();
    if(commode_pail_quantity.length == 0)
    {
      commode_pail_quantity = 0;
    }

    if($(this).is(":checked"))
    {
      $('.c-commode_pail-3').prop('checked',true);
      var commode_pail_count = $("body").find(".commode_pail_counter").val();

      $("body").find(".commode_pail_counter").val(Number(commode_pail_count)+1);
      $("body").find('.commode_pail_quantity').val(Number(commode_pail_quantity)+1);
    }
    else
    {
      var commode_pail_count = $("body").find(".commode_pail_counter").val();
      var new_commode_pail_count = Number(commode_pail_count)-1;

      if((Number(commode_pail_quantity)-1) == 0)
      {
        $('.c-commode_pail-3').prop('checked',false);
      }

      $("body").find(".commode_pail_counter").val(Number(commode_pail_count)-1);
      $("body").find('.commode_pail_quantity').val(Number(commode_pail_quantity)-1);
    }
  });

  $('.c-bariatric_commode-1').bind('click',function(){
    var commode_pail_quantity = $("body").find('.commode_pail_quantity').val();
    if(commode_pail_quantity.length == 0)
    {
      commode_pail_quantity = 0;
    }

    if($(this).is(":checked"))
    {
      $('.c-commode_pail-3').prop('checked',true);

      var commode_pail_count = $("body").find(".commode_pail_counter").val();
      $("body").find(".commode_pail_counter").val(Number(commode_pail_count)+1);
      $("body").find('.commode_pail_quantity').val(Number(commode_pail_quantity)+1);
    }
    else
    {
      var commode_pail_count = $("body").find(".commode_pail_counter").val();
      var new_commode_pail_count = Number(commode_pail_count)-1;

      if((Number(commode_pail_quantity)-1) == 0)
      {
        $('.c-commode_pail-3').prop('checked',false);
      }

      $("body").find(".commode_pail_counter").val(Number(commode_pail_count)-1);
      $("body").find('.commode_pail_quantity').val(Number(commode_pail_quantity)-1);
    }
  });

  $('.c-commode_bariatric-2').bind('click',function(){
    var commode_pail_quantity = $("body").find('.commode_pail_quantity').val();
    if(commode_pail_quantity.length == 0)
    {
      commode_pail_quantity = 0;
    }

    if($(this).is(":checked"))
    {
      $('.c-commode_pail-3').prop('checked',true);

      var commode_pail_count = $("body").find(".commode_pail_counter").val();
      $("body").find(".commode_pail_counter").val(Number(commode_pail_count)+1);
      $("body").find('.commode_pail_quantity').val(Number(commode_pail_quantity)+1);
    }
    else
    {
      var commode_pail_count = $("body").find(".commode_pail_counter").val();
      var new_commode_pail_count = Number(commode_pail_count)-1;

      if((Number(commode_pail_quantity)-1) == 0)
      {
        $('.c-commode_pail-3').prop('checked',false);
      }

      $("body").find(".commode_pail_counter").val(Number(commode_pail_count)-1);
      $("body").find('.commode_pail_quantity').val(Number(commode_pail_quantity)-1);
    }
  });

   $('.c-oxygen_liquid-2').bind('click',function(){
    if($(this).is(":checked"))
    {
      $('.c-oxygen_liquid_refill-3').prop('checked',true);
    }
    else
    {
      $('.c-oxygen_liquid_refill-3').prop('checked',false);
    }
  });


   $('.c-oxygen_liquid-2').bind('click',function(){
    if($(this).is(":checked"))
    {
      $('.c-oxygen_liquid_refill-3').prop('checked',true);
    }
    else
    {
      $('.c-oxygen_liquid_refill-3').prop('checked',false);
    }
  });

   $('.aero_mask_capped').click(function(response){
      if($(this).is(":checked"))
      {
        $('.c-adult_aerosol_mask-3').prop('checked',true);
        $('#adult_aerosol_mask_3').modal("show");
      }
      else
      {
        $('.c-adult_aerosol_mask-3').prop('checked',false);
        $('#adult_aerosol_mask_3').modal("hide");
      }
   });

   $('.aero_mask_noncapped').click(function(response){
      if($(this).is(":checked"))
      {
        $('.c-adult_aerosol_mask-3').prop('checked',true);
        $('#adult_aerosol_mask_3').modal("show");
      }
      else
      {
        $('.c-adult_aerosol_mask-3').prop('checked',false);
        $('#adult_aerosol_mask_3').modal("hide");
      }
   });

   $('.oxygen_mask_capped').click(function(response){
      if($(this).is(":checked"))
      {
        $('.c-o2_mask-3').prop('checked',true);
        $('#o2_mask_3').modal("show");
      }
      else
      {
        $('.c-o2_mask-3').prop('checked',false);
        $('#o2_mask_3').modal("hide");
      }
   });

  $('.oxygen_mask_noncapped').click(function(response){
      if($(this).is(":checked"))
      {
        $('.c-o2_mask-3').prop('checked',true);
        $('#o2_mask_3').modal("show");
      }
      else
      {
        $('.c-o2_mask-3').prop('checked',false);
        $('#o2_mask_3').modal("hide");
      }
   });

  $(".c-oxygen_concentrator_portable-2").click(function(){
    if($(this).is(":checked"))
    {
      $('.c-charger-2').prop("checked",true);
    }
    else
    {
      $('.c-charger-2').prop("checked",false);
    }

  });


   //for threaded comments
    var count = 0;
    var container = $('.comment-field').css({
        padding: '5px',  marginLeft: '8px', width: '98%', height:'auto',border: '0px dashed',
        borderTopColor: '#999', borderBottomColor: '#999',
        borderLeftColor: '#999', borderRightColor: '#999'
    });
   $('.reply-reply-btn').bind('click', function(){
       if (count <= 19) {
                count = count + 1;
                $(container).append('<div class="form-group OpenSans-Reg" id="comment-field' + count + '" ><textarea class="form-control" rows="3" name="comment"></textarea></div>');

                if (count == 1) {
                    $(this).hide();
                    $('.comment-reply-btn').show();
                }
                $('#main').after(container);
            }
            else {
                $(container).append('<label>Reached the limit</label>');
            }
   });
   //end of function


   //To trap invisible data tables when window size becomes small
      $('table thead tr th').removeClass('visible-lg');
      $('table tbody tr td').removeClass('visible-lg');
   //end of trapping


   //auto fillout of zipcode
   // $("#p_postal").keyup(function(){
   //   var el = $(this);
   //   if ((el.val().length == 5) && (is_integer(el.val())))
   //   {
   //    $.ajax({
   //       url: "http://zip.elevenbasetwo.com",
   //       cache: false,
   //       dataType: "json",
   //       type: "GET",
   //       data: "zip=" + el.val(),
   //       success: function(result, success){
   //          //console.log(result);
   //          $("#p_city").val(result.city); /* Fill the data */
   //          $("#p_state").val(result.state);
   //          $(".zip-error").hide(); /* In case they failed once before */
   //          $("#p_add").focus(); /* Put cursor where they need it */
   //       },
   //       error: function(result, success)
   //       {
   //          $(".zip-error").show(); /* Ruh row */
   //       }
   //    });
   //   }
   // });

   function is_integer(value){
     if ((parseFloat(value) == parseInt(value)) && !isNaN(value)) {
         return true;
     } else {
         return false;
     }
   }
   //end of function



   //for the counting of order_comments
   $('.comments_link').mouseover(function(){
      var _element = $(this);
      var uniqueID = $(this).attr('data-id');
      $.post(base_url + 'order/count_order_comments/' + uniqueID, function(response){
        var obj = $.parseJSON(response);

        if(obj == 1)
        {
          _element.prop('title', obj + ' comment');
        }

        if(obj != 0 && obj > 1)
        {
          _element.prop('title', obj + ' comments');
        }

        else
        {
          _element.prop('title', obj + ' comment');
        }
      });
   });

   //for the counting of patient notes
   //  $('.patient_notes_count').mouseover(function(){
   //    var _element = $(this);
   //    var medical_id = $(this).attr('data-id');
   //    var patient_name = $(this).attr('data-patient-name');
   //    var notes_count = $('.patient_notes_count');

   //    $.post(base_url + 'order/count_patient_notes/' + medical_id, function(response){
   //      var obj = $.parseJSON(response);

   //      //notes_count.text(obj);

   //      if(obj == 1)
   //      {
   //        _element.prop('title', obj + ' note');
   //      }

   //      if(obj != 0 && obj > 1)
   //      {
   //        _element.prop('title', obj + ' notes');
   //      }

   //      else
   //      {
   //        _element.prop('title', obj + ' note');
   //      }
   //    });
   // });

   //** alert after the action (client_order.php)

   $('.datatable_table').on('click','.delete-orders',function(){
      var id = $(this).attr("data-id");
      var this_element = $(this);
      jConfirm('Are you sure you want to delete this entry?','Warning!', function(response){
        if(response)
        {
          $.post(base_url + "order/delete_order/" + id, function(response){
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

   $('.datatable_table').on('click','.delete-confirmed-orders',function(){
      var id = $(this).attr("data-id");
      var this_element = $(this);
      jConfirm('Are you sure you want to delete this entry?','Warning!', function(response){
        if(response)
        {
          $.post(base_url + "order/delete_confirmed_order/" + id, function(response){
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

    $('.datatable_table').on('click','.retrieve-orders',function(){
      var id = $(this).attr("data-id");
      var unique_id = $(this).attr("data-unique-id");
      var this_element = $(this);

      jConfirm('Are you sure you want to restore this entry?','Note', function(response){
        if(response)
        {
          $.post(base_url + "order/restore_order/" + id + "/" + unique_id, function(response){
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


    $('.datatable_table').on('click','.delete-trash',function(){
      var id = $(this).attr("data-id");
      var this_element = $(this);
      jConfirm('This entry will be deleted permanently. <br />You want to proceed?','Note', function(response){
        if(response)
        {
          $.post(base_url + "order/delete_trash/" + id, function(response){

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

    $('.datatable_table').on('click','.delete-users',function(){
      var id = $(this).attr("data-id");
      var name = $(this).attr("data-name");
      var this_element = $(this);
      // jConfirm('This entry will be deleted permanently. <br />You want to proceed?','Note', function(response){
      jConfirm('User ' + name + ' will be deleted permanently. <br /><br />Do you want to proceed?','Note', function(response){
        if(response)
        {
          $.post(base_url + "users/delete_user/" + id, function(response){

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


    //delete users created by hospice admin
    $('.datatable_table').on('click','.delete-hosp-admin-users',function(){
      var id = $(this).attr("data-id");
      var this_element = $(this);
      jConfirm('This entry will be deleted permanently. <br />You want to proceed?','Note', function(response){
        if(response)
        {
          $.post(base_url + "users/delete_user/" + id, function(response){

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

  //delete patient records
  $('.patient_list_datatable').on('click','.delete_patient', function(){
    var medical_id = $(this).attr('data-medical-id');
    var patient_id = $(this).attr('data-patient-id');
    var _this = $(this);

    jConfirm('Are you sure you want to delete this patient and all of its records?','Warning', function(response){
      if(response)
      {
        $.post(base_url+"order/delete_patient_records/"+patient_id+"/"+medical_id, function(response){
            var obj  = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');

            if(obj['error'] == 0)
            {
              _this.parent().parent().fadeOut(500,function(){
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
          //console.log(id);
          $.post(base_url + "equipment/edit/" + id, form_data ,function(response){

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
          $.post(base_url + "equipment/delete_equipment/" + id, function(response){

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
      var unique_id = $(this).attr('data-unique-id');

      var this_element = $(this);
      jConfirm('Are you sure you want the change the status of this?','Alert', function(response){
        if(response)
        {
          $.post(base_url + "order/change_order_status/" + id + "/" + status, function(response){
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
                else if(status == 'confirmed')
                {
                  this_element.attr("value","Change Status to Active");
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


  //to change the status of pickup orders
   // $('.pickup_change_stat').bind('click',function(){
   //    var id = $(this).attr("data-id");
   //    var status = $(this).attr("data-status");
   //    var this_element = $(this);

   //    jConfirm('Are you sure you want the change the status of this?','Warning', function(response){
   //      if(response)
   //      {
   //        $.post(base_url + "order/change_order_status/" + id + "/" + status, function(response){
   //            var obj = $.parseJSON(response);
   //            jAlert(obj['message'],'Edit Response');

   //            if(obj['error']==0)
   //            {
   //              if(status == 'pending')
   //              {
   //                this_element.attr("value","Change Status to Active");
   //                location.reload();
   //              }
   //              else if(status == 'active')
   //              {
   //                this_element.attr("value","Change Status to Picked up");
   //                location.reload();
   //              }
   //              else if(status == 'picked_up')
   //              {
   //                this_element.attr("value","Change Status to Active");
   //                location.reload();
   //              }
   //              else
   //              {
   //                this_element.attr("");
   //                location.reload();
   //              }

   //            }
   //        });
   //      }
   //    });
   // });

  //change order status when choosing on the dropdown
  $('.datatable_table').on('change','.change_order_status',function(){
    var _value = $(this).closest("tr").find("td").find("select").val();
    var _data_id = $(this).closest("tr").find("td").find("select").attr('data-type');
    var _id = $(this).closest("tr").find("td").find("select").attr("data-id");
    var _uniqueID = $(this).closest("tr").find("td").find("select").attr("data-unique-id");
    var _act_id = $(this).closest("tr").find("td").find("select").attr("data-act-id");
    var _hospice_id = $(this).closest("tr").find("td").find("select").attr("data-organization-id");
    var _equipment_id = $(this).closest("tr").find("td").find("select").attr("data-equipment-id");
    var reschedule_onhold_date_container = $(this).closest("tr").find("td").find(".resceduled_onhold_date_pos").find(".resceduled_onhold_date_container");
    var reschedule_onhold_date_container_sign = $(this).closest("tr").find("td").find(".resceduled_onhold_date_pos").find(".resceduled_onhold_date_container").attr("data-sign");
    var temp_container = $(this).closest("tr").find("td").find("select");
    var counter = 0;
    

    if(_value === 'active' || _value === 'on-hold' || _value === 'pending' || _value === 're-schedule')
    {
      if(_value != 're-schedule')
      {
        $(this).closest("tr").find("td").find(".resceduled_onhold_date_pos").remove();
      }

      if(_value === 're-schedule')
      {
        modalbox(base_url + 'order/ask_reschreschedule_onhold_date/' + _uniqueID,{
            header:"Set Date",
            button: false
        });

        //for the inserting of rescheduled or onhold date
        $('body').on('click','.save-date-btn',function(){
          var form_data = $('#reschedule_onhold_date_form').serialize();

          $.post(base_url + 'order/insert_reschreschedule_onhold_date/' + _uniqueID,form_data, function(response){
            var obj = $.parseJSON(response);
            setTimeout(function(){
              closeModalbox();
              if(obj['error'] == 0)
              {
                var date = new Date(obj['date_returned']);
                var day = date.getDate();
                var month_here = date.getMonth();
                var year = date.getFullYear();

                month_here = Number(month_here+1);
                var date_returned = month_here+"/"+day+"/"+year;

                if(counter == 0)
                {
                  if(reschedule_onhold_date_container_sign == 1)
                  {
                    reschedule_onhold_date_container.html(date_returned);
                  }
                  else
                  {
                    var temp_html = "";

                    temp_html = '<span class="resceduled_onhold_date_pos" style="margin-left:25px;">'+
                                  '<span data-id="'+_uniqueID+'" class="set_date_reschedule_onhold" style="cursor:pointer;"><i class="fa fa-calendar"></i></span>&nbsp;&nbsp;' +
                                  '<span class="resceduled_onhold_date_container" data-sign="1">'+date_returned+'</span>'+
                                '</span>';
                    temp_container.parent("td").append(temp_html);
                  }
                }
                counter++;

                setTimeout(function(){
                  $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
                    var obj2 = $.parseJSON(response);
                    if(obj2['error'] == 0)
                    {
                      jAlert(obj2['message'],"Update Response");
                      $("body").find(".modal-dialog").find("#popup_panel").css("display","none");
                      $("body").find(".modal-dialog").find("#popup_message").css("height","65px");
                      setTimeout(function(){
                        $("#popup_container").remove();
                        $.alerts._overlay('hide');
                        $.alerts._maintainPosition(false);
                      },1000);

                      $.post(base_url+"order/get_pos_activity_counter/", function(response){
                        var temp = "";
                        var temp_second = "";
                        var container = $("body").find("#pos_activity_counter");
                        var container_second = $("body").find("#pos_activity_counter_second");
                        var obj = $.parseJSON(response);

                        var enroute = "";
                        if(obj['active'])
                        {
                          enroute = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="En route" style="cursor:pointer;">En route</span>&nbsp;'+
                                  '<span><strong>'+obj['active']+'</strong></span>'+
                                '</li>';
                        }
                        var onhold = "";
                        if(obj['onhold'])
                        {
                          onhold = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="On Hold" style="cursor:pointer;">On Hold</span>&nbsp;'+
                                  '<span><strong>'+obj['onhold']+'</strong></span>'+
                                '</li>';
                        }
                        var pending = "";
                        if(obj['pending'])
                        {
                          pending = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="Pending" style="cursor:pointer;">Pending</span>&nbsp;'+
                                  '<span><strong>'+obj['pending']+'</strong></span>'+
                                '</li>';
                        }
                        var resched = "";
                        if(obj['resched'])
                        {
                          resched = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="Rescheduled" style="cursor:pointer;">Rescheduled</span>&nbsp;'+
                                  '<span><strong>'+obj['resched']+'</strong></span>'+
                                '</li>';
                        }
                        temp = '<div class="pull-right" id="pos_activity_counter">'+
                                  '<ul class="status-count list-inline">'+
                                    enroute+
                                    onhold+
                                    pending+
                                    resched+
                                  '</ul>'+
                                '</div>';

                        container.html(temp);

                        temp_second = '<div class="clearfix text-center" id="pos_activity_counter_second">'+
                                        '<ul class="status-count status-count-bot list-inline" >'+
                                          enroute+
                                          onhold+
                                          pending+
                                          resched+
                                        '</ul>'+
                                      '</ul>';

                        container_second.html(temp);
                      });
                    }
                  });
                },1000);
              }
            },500);
          });

        });
      }
      else
      {
        $.post(base_url + 'order/delete_reschreschedule_onhold_date/' + _uniqueID,"", function(response){

        });

        $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
          var obj2 = $.parseJSON(response);
          if(obj2['error'] == 0)
          {
            jAlert(obj2['message'],"Update Response");
            $("body").find(".modal-dialog").find("#popup_panel").css("display","none");
            $("body").find(".modal-dialog").find("#popup_message").css("height","65px");
            setTimeout(function(){
              $("#popup_container").remove();
              $.alerts._overlay('hide');
              $.alerts._maintainPosition(false);
            },1000);

            $.post(base_url+"order/get_pos_activity_counter/", function(response){
              var temp = "";
              var temp_second = "";
              var container = $("body").find("#pos_activity_counter");
              var container_second = $("body").find("#pos_activity_counter_second");
              var obj = $.parseJSON(response);

              var enroute = "";
              if(obj['active'])
              {
                enroute = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="En route" style="cursor:pointer;">En route</span>&nbsp;'+
                        '<span><strong>'+obj['active']+'</strong></span>'+
                      '</li>';
              }
              var onhold = "";
              if(obj['onhold'])
              {
                onhold = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="On Hold" style="cursor:pointer;">On Hold</span>&nbsp;'+
                        '<span><strong>'+obj['onhold']+'</strong></span>'+
                      '</li>';
              }
              var pending = "";
              if(obj['pending'])
              {
                pending = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="Pending" style="cursor:pointer;">Pending</span>&nbsp;'+
                        '<span><strong>'+obj['pending']+'</strong></span>'+
                      '</li>';
              }
              var resched = "";
              if(obj['resched'])
              {
                resched = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="Rescheduled" style="cursor:pointer;">Rescheduled</span>&nbsp;'+
                        '<span><strong>'+obj['resched']+'</strong></span>'+
                      '</li>';
              }
              temp = '<div class="pull-right" id="pos_activity_counter">'+
                        '<ul class="status-count list-inline">'+
                          enroute+
                          onhold+
                          pending+
                          resched+
                        '</ul>'+
                      '</div>';

              container.html(temp);

              temp_second = '<div class="clearfix text-center" id="pos_activity_counter_second">'+
                              '<ul class="status-count status-count-bot list-inline" >'+
                                enroute+
                                onhold+
                                pending+
                                resched+
                              '</ul>'+
                            '</ul>';

              container_second.html(temp);
              // location.reload();
            });
          }
        });
      }
    }
    else
    {
      var jconfirmmessage = '';
      if (_value === "cancel") {
        var data_is_new_patient = $(this).closest("tr").find("td").find("select").attr('data-is-new-patient');
        console.log('data_is_new_patient', data_is_new_patient);
        if (data_is_new_patient == 0) {
          jconfirmmessage = 'Cancel Work Order? <br /><br /> This customer will be permanently deleted.';
        } else {
          jconfirmmessage = 'Cancel Order?';
        }
        
      } else {
        jconfirmmessage = 'Change Order Status?';
      }

      jConfirm(jconfirmmessage,"Warning", function(response){
        if(response)
        {
          if(_value === "cancel")
          {
            $.post(base_url+"order/cancel_order/" + _id + "/" + _uniqueID + "/" + _equipment_id, function(response){
                var cancel_obj = $.parseJSON(response);
                if(cancel_obj['error'] == 0)
                {
                  $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
                      var obj = $.parseJSON(response);

                      if(obj['error'] == 0)
                      {
                        jAlert(obj['message'],"Update Response");
                        location.reload();
                      }

                  });
                }
            });
          }
          else if(_value === "confirmed")
          {
            if(_act_id == 3)
            {

              modalbox(base_url + 'order/confirmed_order_details_exchange/'+ _id + "/" + _uniqueID + "/" + _act_id+"/"+_hospice_id,{
                header:"Confirm Work Order # " + _uniqueID.substring(4,10),
                button: false,
              });
            }
            else
            {
              modalbox(base_url + 'order/confirmed_order_details/'+ _id + "/" + _uniqueID + "/" + _act_id+"/"+_hospice_id,{
                header:"Confirm Work Order # " + _uniqueID.substring(4,10),
                button: false,
              });
            }
          }
          else if(_value === "tobe_confirmed")
          {
            $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
              var obj2 = $.parseJSON(response);
              if(obj2['error'] == 0)
              {
                jAlert(obj2['message'],"Update Response");
                window.location.reload();
              }
            });
          }
          else if(_value === "not_confirmed")
          {
            if(_act_id == 3)
            {
              if(_data_id == 0)
              {
                modalbox(base_url + 'order/confirmed_order_details_exchange/'+ _id + "/" + _uniqueID + "/" + _act_id+"/"+_hospice_id,{
                  header:"Confirm Work Order # " + _uniqueID.substring(4,10),
                  button: false,
                });
              }
              else
              {
                $("body").find(".warning_modal_content").html("Confirm Delivery.");
                $('#not_confirmed_modal').modal('show');
              }
            }
            else
            {
              if(_data_id == 3)
              {
                $("body").find(".warning_modal_content").html("Confirm Exchange.");
              }
              else if(_data_id == 1)
              {
                $("body").find(".warning_modal_content").html("Confirm Delivery.");
              }
              $('#not_confirmed_modal').modal('show');
            }
          }
        }
      });
    }
  });

  $('.patient_order_list_table').on('change','.change_order_status',function(){
    var _value = $(this).closest("tr").find("td").find("select").val();
    var _data_id = $(this).closest("tr").find("td").find("select").attr('data-type');
    var _id = $(this).closest("tr").find("td").find("select").attr("data-id");
    var _uniqueID = $(this).closest("tr").find("td").find("select").attr("data-unique-id");
    var _act_id = $(this).closest("tr").find("td").find("select").attr("data-act-id");
    var _hospice_id = $(this).closest("tr").find("td").find("select").attr("data-organization-id");
    var _equipment_id = $(this).closest("tr").find("td").find("select").attr("data-equipment-id");
    var reschedule_onhold_date_container = $(this).closest("tr").find("td").find(".resceduled_onhold_date_pos").find(".resceduled_onhold_date_container");
    var reschedule_onhold_date_container_sign = $(this).closest("tr").find("td").find(".resceduled_onhold_date_pos").find(".resceduled_onhold_date_container").attr("data-sign");
    var temp_container = $(this).closest("tr").find("td").find("select");
    var counter = 0;
    var _this = $(this);

    if(_value === 'active' || _value === 'on-hold' || _value === 'pending' || _value === 're-schedule')
    {
      if(_value != 're-schedule')
      {
        $(this).closest("tr").find("td").find(".resceduled_onhold_date_pos").remove();
      }

      if(_value === 're-schedule')
      {
        modalbox(base_url + 'order/ask_reschreschedule_onhold_date/' + _uniqueID,{
            header:"Set Date",
            button: false
        });

        //for the inserting of rescheduled or onhold date
        $('body').on('click','.save-date-btn',function(){
          var form_data = $('#reschedule_onhold_date_form').serialize();

          $.post(base_url + 'order/insert_reschreschedule_onhold_date/' + _uniqueID,form_data, function(response){
            var obj = $.parseJSON(response);
            setTimeout(function(){
              closeModalbox();
              if(obj['error'] == 0)
              {
                var date = new Date(obj['date_returned']);
                var day = date.getDate();
                var month_here = date.getMonth();
                var year = date.getFullYear();

                month_here = Number(month_here+1);
                var date_returned = month_here+"/"+day+"/"+year;

                if(counter == 0)
                {
                  if(reschedule_onhold_date_container_sign == 1)
                  {
                    reschedule_onhold_date_container.html(date_returned);
                  }
                  else
                  {
                    var temp_html = "";

                    temp_html = '<span class="resceduled_onhold_date_pos" style="margin-left:25px;">'+
                                  '<span data-id="'+_uniqueID+'" class="set_date_reschedule_onhold" style="cursor:pointer;"><i class="fa fa-calendar"></i></span>&nbsp;&nbsp;' +
                                  '<span class="resceduled_onhold_date_container" data-sign="1">'+date_returned+'</span>'+
                                '</span>';
                    temp_container.parent("td").append(temp_html);
                  }
                }
                counter++;

                setTimeout(function(){
                  $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
                    var obj2 = $.parseJSON(response);
                    if(obj2['error'] == 0)
                    {
                      jAlert(obj2['message'],"Update Response");
                      $("body").find(".modal-dialog").find("#popup_panel").css("display","none");
                      $("body").find(".modal-dialog").find("#popup_message").css("height","65px");
                      setTimeout(function(){
                        $("#popup_container").remove();
                        $.alerts._overlay('hide');
                        $.alerts._maintainPosition(false);
                      },1000);

                      $.post(base_url+"order/get_pos_activity_counter/", function(response){
                        var temp = "";
                        var temp_second = "";
                        var container = $("body").find("#pos_activity_counter");
                        var container_second = $("body").find("#pos_activity_counter_second");
                        var obj = $.parseJSON(response);

                        var enroute = "";
                        if(obj['active'])
                        {
                          enroute = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="En route" style="cursor:pointer;">En route</span>&nbsp;'+
                                  '<span><strong>'+obj['active']+'</strong></span>'+
                                '</li>';
                        }
                        var onhold = "";
                        if(obj['onhold'])
                        {
                          onhold = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="On Hold" style="cursor:pointer;">On Hold</span>&nbsp;'+
                                  '<span><strong>'+obj['onhold']+'</strong></span>'+
                                '</li>';
                        }
                        var pending = "";
                        if(obj['pending'])
                        {
                          pending = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="Pending" style="cursor:pointer;">Pending</span>&nbsp;'+
                                  '<span><strong>'+obj['pending']+'</strong></span>'+
                                '</li>';
                        }
                        var resched = "";
                        if(obj['resched'])
                        {
                          resched = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="Rescheduled" style="cursor:pointer;">Rescheduled</span>&nbsp;'+
                                  '<span><strong>'+obj['resched']+'</strong></span>'+
                                '</li>';
                        }
                        temp = '<div class="pull-right" id="pos_activity_counter">'+
                                  '<ul class="status-count list-inline">'+
                                    enroute+
                                    onhold+
                                    pending+
                                    resched+
                                  '</ul>'+
                                '</div>';

                        container.html(temp);

                        temp_second = '<div class="clearfix text-center" id="pos_activity_counter_second">'+
                                        '<ul class="status-count status-count-bot list-inline" >'+
                                          enroute+
                                          onhold+
                                          pending+
                                          resched+
                                        '</ul>'+
                                      '</ul>';

                        container_second.html(temp);
                      });
                    }
                  });
                },1000);
              }
            },500);
          });

        });
      }
      else
      {
        $.post(base_url + 'order/delete_reschreschedule_onhold_date/' + _uniqueID,"", function(response){

        });

        $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
          var obj2 = $.parseJSON(response);
          if(obj2['error'] == 0)
          {
            jAlert(obj2['message'],"Update Response");
            $("body").find(".modal-dialog").find("#popup_panel").css("display","none");
            $("body").find(".modal-dialog").find("#popup_message").css("height","65px");
            setTimeout(function(){
              $("#popup_container").remove();
              $.alerts._overlay('hide');
              $.alerts._maintainPosition(false);
            },1000);

            $.post(base_url+"order/get_pos_activity_counter/", function(response){
              var temp = "";
              var temp_second = "";
              var container = $("body").find("#pos_activity_counter");
              var container_second = $("body").find("#pos_activity_counter_second");
              var obj = $.parseJSON(response);

              var enroute = "";
              if(obj['active'])
              {
                enroute = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="En route" style="cursor:pointer;">En route</span>&nbsp;'+
                        '<span><strong>'+obj['active']+'</strong></span>'+
                      '</li>';
              }
              var onhold = "";
              if(obj['onhold'])
              {
                onhold = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="On Hold" style="cursor:pointer;">On Hold</span>&nbsp;'+
                        '<span><strong>'+obj['onhold']+'</strong></span>'+
                      '</li>';
              }
              var pending = "";
              if(obj['pending'])
              {
                pending = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="Pending" style="cursor:pointer;">Pending</span>&nbsp;'+
                        '<span><strong>'+obj['pending']+'</strong></span>'+
                      '</li>';
              }
              var resched = "";
              if(obj['resched'])
              {
                resched = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="Rescheduled" style="cursor:pointer;">Rescheduled</span>&nbsp;'+
                        '<span><strong>'+obj['resched']+'</strong></span>'+
                      '</li>';
              }
              temp = '<div class="pull-right" id="pos_activity_counter">'+
                        '<ul class="status-count list-inline">'+
                          enroute+
                          onhold+
                          pending+
                          resched+
                        '</ul>'+
                      '</div>';

              container.html(temp);

              temp_second = '<div class="clearfix text-center" id="pos_activity_counter_second">'+
                              '<ul class="status-count status-count-bot list-inline" >'+
                                enroute+
                                onhold+
                                pending+
                                resched+
                              '</ul>'+
                            '</ul>';

              container_second.html(temp);
              // location.reload();
            });
          }
        });
      }
    }
    else
    {
      var jconfirmmessage = '';
      if (_value === "cancel") {
        var data_is_new_patient = $(this).closest("tr").find("td").find("select").attr('data-is-new-patient');
        console.log('data_is_new_patient', data_is_new_patient);
        if (data_is_new_patient == 0) {
          jconfirmmessage = 'Cancel Work Order? <br /><br /> This customer will be permanently deleted.';
        } else {
          jconfirmmessage = 'Cancel Order?';
        }
      } else {
        jconfirmmessage = 'Change Order Status?';
      }

      jConfirm(jconfirmmessage,"Warning", function(response){
        if(response)
        {
          if(_value === "cancel")
          {
            $.post(base_url+"order/cancel_order/" + _id + "/" + _uniqueID + "/" + _equipment_id, function(response){
                var cancel_obj = $.parseJSON(response);
                if(cancel_obj['error'] == 0)
                {
                  $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
                      var obj = $.parseJSON(response);

                      if(obj['error'] == 0)
                      {
                        jAlert(obj['message'],"Update Response");

                        // if ($('.pos_list_div').find("#DataTables_Table_0_filter").find(".input-sm").val()) {}

                        _this.parent('td').parent('tr').remove();
                        var show_entries = $('body').find(".pos_list_div").find(".dataTables_info").text();
                        var exploded_show_entries = show_entries.split(" ");

                        if ((Number(exploded_show_entries[3])-1) != 0) {
                          var new_show_entries = exploded_show_entries[0]+" "+Number(exploded_show_entries[1])+" "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];
                        } else {
                          var new_show_entries = exploded_show_entries[0]+" 0 "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];
                          location.reload();
                        }
                        $('body').find(".pos_list_div").find(".dataTables_info").html(new_show_entries);
                      }
                  });
                }
            });
          }
          else if(_value === "confirmed")
          {
            if(_act_id == 3)
            {

              modalbox(base_url + 'order/confirmed_order_details_exchange/'+ _id + "/" + _uniqueID + "/" + _act_id+"/"+_hospice_id,{
                header:"Confirm Work Order # " + _uniqueID.substring(4,10),
                button: false,
              });
            }
            else
            {
              modalbox(base_url + 'order/confirmed_order_details/'+ _id + "/" + _uniqueID + "/" + _act_id+"/"+_hospice_id,{
                header:"Confirm Work Order # " + _uniqueID.substring(4,10),
                button: false,
              });
            }
          }
          else if(_value === "tobe_confirmed")
          {
            $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
              var obj2 = $.parseJSON(response);
              if(obj2['error'] == 0)
              {
                jAlert(obj2['message'],"Update Response");

                // if ($('.pos_list_div').find("#DataTables_Table_0_filter").find(".input-sm").val()) {}

                _this.parent('td').parent('tr').remove();
                var show_entries = $('body').find(".pos_list_div").find(".dataTables_info").text();
                var exploded_show_entries = show_entries.split(" ");

                if ((Number(exploded_show_entries[3])-1) != 0) {
                  var new_show_entries = exploded_show_entries[0]+" "+Number(exploded_show_entries[1])+" "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];
                } else {
                  var new_show_entries = exploded_show_entries[0]+" 0 "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];
                  location.reload();
                }
                $('body').find(".pos_list_div").find(".dataTables_info").html(new_show_entries);
              }
            });
          }
          else if(_value === "not_confirmed")
          {
            if(_act_id == 3)
            {
              if(_data_id == 0)
              {
                modalbox(base_url + 'order/confirmed_order_details_exchange/'+ _id + "/" + _uniqueID + "/" + _act_id+"/"+_hospice_id,{
                  header:"Confirm Work Order # " + _uniqueID.substring(4,10),
                  button: false,
                });
              }
              else
              {
                $("body").find(".warning_modal_content").html("Confirm Delivery.");
                $('#not_confirmed_modal').modal('show');
              }
            }
            else
            {
              if(_data_id == 3)
              {
                $("body").find(".warning_modal_content").html("Confirm Exchange.");
              }
              else if(_data_id == 1)
              {
                $("body").find(".warning_modal_content").html("Confirm Delivery.");
              }
              $('#not_confirmed_modal').modal('show');
            }
          }
        }
      });
    }
  });

  $('.list_tobe_confirmed_table').on('change','.change_order_status',function(){
    var _value = $(this).closest("tr").find("td").find("select").val();
    var _data_id = $(this).closest("tr").find("td").find("select").attr('data-type');
    var _id = $(this).closest("tr").find("td").find("select").attr("data-id");
    var _uniqueID = $(this).closest("tr").find("td").find("select").attr("data-unique-id");
    var _act_id = $(this).closest("tr").find("td").find("select").attr("data-act-id");
    var _hospice_id = $(this).closest("tr").find("td").find("select").attr("data-organization-id");
    var _equipment_id = $(this).closest("tr").find("td").find("select").attr("data-equipment-id");
    var reschedule_onhold_date_container = $(this).closest("tr").find("td").find(".resceduled_onhold_date_pos").find(".resceduled_onhold_date_container");
    var reschedule_onhold_date_container_sign = $(this).closest("tr").find("td").find(".resceduled_onhold_date_pos").find(".resceduled_onhold_date_container").attr("data-sign");
    var temp_container = $(this).closest("tr").find("td").find("select");
    var counter = 0;
    var _this = $(this);

    if(_value === 'active' || _value === 'on-hold' || _value === 're-schedule')
    {
      if(_value != 're-schedule')
      {
        $(this).closest("tr").find("td").find(".resceduled_onhold_date_pos").remove();
      }

      if(_value === 're-schedule')
      {
        modalbox(base_url + 'order/ask_reschreschedule_onhold_date/' + _uniqueID,{
            header:"Set Date",
            button: false
        });

        //for the inserting of rescheduled or onhold date
        $('body').on('click','.save-date-btn',function(){
          var form_data = $('#reschedule_onhold_date_form').serialize();

          $.post(base_url + 'order/insert_reschreschedule_onhold_date/' + _uniqueID,form_data, function(response){
            var obj = $.parseJSON(response);
            setTimeout(function(){
              closeModalbox();
              if(obj['error'] == 0)
              {
                var date = new Date(obj['date_returned']);
                var day = date.getDate();
                var month_here = date.getMonth();
                var year = date.getFullYear();

                month_here = Number(month_here+1);
                var date_returned = month_here+"/"+day+"/"+year;

                if(counter == 0)
                {
                  if(reschedule_onhold_date_container_sign == 1)
                  {
                    reschedule_onhold_date_container.html(date_returned);
                  }
                  else
                  {
                    var temp_html = "";

                    temp_html = '<span class="resceduled_onhold_date_pos" style="margin-left:25px;">'+
                                  '<span data-id="'+_uniqueID+'" class="set_date_reschedule_onhold" style="cursor:pointer;"><i class="fa fa-calendar"></i></span>&nbsp;&nbsp;' +
                                  '<span class="resceduled_onhold_date_container" data-sign="1">'+date_returned+'</span>'+
                                '</span>';
                    temp_container.parent("td").append(temp_html);
                  }
                }
                counter++;

                setTimeout(function(){
                  $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
                    var obj2 = $.parseJSON(response);
                    if(obj2['error'] == 0)
                    {
                      jAlert(obj2['message'],"Update Response");
                      $("body").find(".modal-dialog").find("#popup_panel").css("display","none");
                      $("body").find(".modal-dialog").find("#popup_message").css("height","65px");
                      setTimeout(function(){
                        $("#popup_container").remove();
                        $.alerts._overlay('hide');
                        $.alerts._maintainPosition(false);
                      },1000);

                      $.post(base_url+"order/get_pos_activity_counter/", function(response){
                        var temp = "";
                        var temp_second = "";
                        var container = $("body").find("#pos_activity_counter");
                        var container_second = $("body").find("#pos_activity_counter_second");
                        var obj = $.parseJSON(response);

                        var enroute = "";
                        if(obj['active'])
                        {
                          enroute = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="En route" style="cursor:pointer;">En route</span>&nbsp;'+
                                  '<span><strong>'+obj['active']+'</strong></span>'+
                                '</li>';
                        }
                        var onhold = "";
                        if(obj['onhold'])
                        {
                          onhold = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="On Hold" style="cursor:pointer;">On Hold</span>&nbsp;'+
                                  '<span><strong>'+obj['onhold']+'</strong></span>'+
                                '</li>';
                        }
                        var pending = "";
                        if(obj['pending'])
                        {
                          pending = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="Pending" style="cursor:pointer;">Pending</span>&nbsp;'+
                                  '<span><strong>'+obj['pending']+'</strong></span>'+
                                '</li>';
                        }
                        var resched = "";
                        if(obj['resched'])
                        {
                          resched = '<li class="pull-left">'+
                                  '<span class="pos_counter" data-id="Rescheduled" style="cursor:pointer;">Rescheduled</span>&nbsp;'+
                                  '<span><strong>'+obj['resched']+'</strong></span>'+
                                '</li>';
                        }
                        temp = '<div class="pull-right" id="pos_activity_counter">'+
                                  '<ul class="status-count list-inline">'+
                                    enroute+
                                    onhold+
                                    pending+
                                    resched+
                                  '</ul>'+
                                '</div>';

                        container.html(temp);

                        temp_second = '<div class="clearfix text-center" id="pos_activity_counter_second">'+
                                        '<ul class="status-count status-count-bot list-inline" >'+
                                          enroute+
                                          onhold+
                                          pending+
                                          resched+
                                        '</ul>'+
                                      '</ul>';

                        container_second.html(temp);
                      });
                    }
                  });
                },1000);
              }
            },500);
          });

        });
      }
      else
      {
        $.post(base_url + 'order/delete_reschreschedule_onhold_date/' + _uniqueID,"", function(response){

        });

        $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
          var obj2 = $.parseJSON(response);
          if(obj2['error'] == 0)
          {
            jAlert(obj2['message'],"Update Response");
            $("body").find(".modal-dialog").find("#popup_panel").css("display","none");
            $("body").find(".modal-dialog").find("#popup_message").css("height","65px");
            setTimeout(function(){
              $("#popup_container").remove();
              $.alerts._overlay('hide');
              $.alerts._maintainPosition(false);
            },1000);

            $.post(base_url+"order/get_pos_activity_counter/", function(response){
              var temp = "";
              var temp_second = "";
              var container = $("body").find("#pos_activity_counter");
              var container_second = $("body").find("#pos_activity_counter_second");
              var obj = $.parseJSON(response);

              var enroute = "";
              if(obj['active'])
              {
                enroute = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="En route" style="cursor:pointer;">En route</span>&nbsp;'+
                        '<span><strong>'+obj['active']+'</strong></span>'+
                      '</li>';
              }
              var onhold = "";
              if(obj['onhold'])
              {
                onhold = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="On Hold" style="cursor:pointer;">On Hold</span>&nbsp;'+
                        '<span><strong>'+obj['onhold']+'</strong></span>'+
                      '</li>';
              }
              var pending = "";
              if(obj['pending'])
              {
                pending = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="Pending" style="cursor:pointer;">Pending</span>&nbsp;'+
                        '<span><strong>'+obj['pending']+'</strong></span>'+
                      '</li>';
              }
              var resched = "";
              if(obj['resched'])
              {
                resched = '<li class="pull-left">'+
                        '<span class="pos_counter" data-id="Rescheduled" style="cursor:pointer;">Rescheduled</span>&nbsp;'+
                        '<span><strong>'+obj['resched']+'</strong></span>'+
                      '</li>';
              }
              temp = '<div class="pull-right" id="pos_activity_counter">'+
                        '<ul class="status-count list-inline">'+
                          enroute+
                          onhold+
                          pending+
                          resched+
                        '</ul>'+
                      '</div>';

              container.html(temp);

              temp_second = '<div class="clearfix text-center" id="pos_activity_counter_second">'+
                              '<ul class="status-count status-count-bot list-inline" >'+
                                enroute+
                                onhold+
                                pending+
                                resched+
                              '</ul>'+
                            '</ul>';

              container_second.html(temp);
              // location.reload();
            });
          }
        });
      }
    }
    else
    {
      var jconfirmmessage = '';
      if (_value === "cancel") {
        var data_is_new_patient = $(this).closest("tr").find("td").find("select").attr('data-is-new-patient');
        console.log('data_is_new_patient', data_is_new_patient);
        if (data_is_new_patient == 0) {
          jconfirmmessage = 'Cancel Work Order? <br /><br /> This customer will be permanently deleted.';
        } else {
          jconfirmmessage = 'Cancel Order?';
        }
      } else {
        jconfirmmessage = 'Change Order Status?';
      }

      jConfirm(jconfirmmessage,"Warning", function(response){
        if(response)
        {
          if(_value === "cancel")
          {
            $.post(base_url+"order/cancel_order/" + _id + "/" + _uniqueID + "/" + _equipment_id, function(response){
                var cancel_obj = $.parseJSON(response);
                if(cancel_obj['error'] == 0)
                {
                  $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
                      var obj = $.parseJSON(response);

                      if(obj['error'] == 0)
                      {
                        jAlert(obj['message'],"Update Response");


                        // if ($('.cwo_list_div').find("#DataTables_Table_0_filter").find(".input-sm").val()) {}

                        _this.parent('td').parent('tr').remove();
                        var show_entries = $('body').find(".cwo_list_div").find(".dataTables_info").text();
                        var exploded_show_entries = show_entries.split(" ");

                        if ((Number(exploded_show_entries[3])-1) != 0) {
                          var new_show_entries = exploded_show_entries[0]+" "+Number(exploded_show_entries[1])+" "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];
                        } else {
                          var new_show_entries = exploded_show_entries[0]+" 0 "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];
                          location.reload();
                        }
                        $('body').find(".cwo_list_div").find(".dataTables_info").html(new_show_entries);
                      }
                  });
                }
            });
          }
          else if(_value === "confirmed")
          {
            if(_act_id == 3)
            {

              modalbox(base_url + 'order/confirmed_order_details_exchange/'+ _id + "/" + _uniqueID + "/" + _act_id+"/"+_hospice_id,{
                header:"Confirm Work Order # " + _uniqueID.substring(4,10),
                button: false,
              });
            }
            else
            {
              modalbox(base_url + 'order/confirmed_order_details/'+ _id + "/" + _uniqueID + "/" + _act_id+"/"+_hospice_id,{
                header:"Confirm Work Order # " + _uniqueID.substring(4,10),
                button: false,
              });
            }
          }
          else if(_value === "tobe_confirmed")
          {
            $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
              var obj2 = $.parseJSON(response);
              if(obj2['error'] == 0)
              {
                jAlert(obj2['message'],"Update Response");
                window.location.reload();
              }
            });
          }
          else if(_value === "not_confirmed")
          {
            if(_act_id == 3)
            {
              if(_data_id == 0)
              {
                modalbox(base_url + 'order/confirmed_order_details_exchange/'+ _id + "/" + _uniqueID + "/" + _act_id+"/"+_hospice_id,{
                  header:"Confirm Work Order # " + _uniqueID.substring(4,10),
                  button: false,
                });
              }
              else
              {
                $("body").find(".warning_modal_content").html("Confirm Delivery.");
                $('#not_confirmed_modal').modal('show');
              }
            }
            else
            {
              if(_data_id == 3)
              {
                $("body").find(".warning_modal_content").html("Confirm Exchange.");
              }
              else if(_data_id == 1)
              {
                $("body").find(".warning_modal_content").html("Confirm Delivery.");
              }
              $('#not_confirmed_modal').modal('show');
            }
          }
          else if (_value === 'pending')
          {
            $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
              var obj2 = $.parseJSON(response);
              if(obj2['error'] == 0)
              {
                jAlert(obj2['message'],"Update Response");
                $("body").find(".modal-dialog").find("#popup_panel").css("display","none");
                $("body").find(".modal-dialog").find("#popup_message").css("height","65px");
                setTimeout(function(){
                  $("#popup_container").remove();
                  $.alerts._overlay('hide');
                  $.alerts._maintainPosition(false);
                },1000);

                $.post(base_url+"order/get_pos_activity_counter/", function(response){
                  var temp = "";
                  var temp_second = "";
                  var container = $("body").find("#pos_activity_counter");
                  var container_second = $("body").find("#pos_activity_counter_second");
                  var obj = $.parseJSON(response);

                  var enroute = "";
                  if(obj['active'])
                  {
                    enroute = '<li class="pull-left">'+
                            '<span class="pos_counter" data-id="En route" style="cursor:pointer;">En route</span>&nbsp;'+
                            '<span><strong>'+obj['active']+'</strong></span>'+
                          '</li>';
                  }
                  var onhold = "";
                  if(obj['onhold'])
                  {
                    onhold = '<li class="pull-left">'+
                            '<span class="pos_counter" data-id="On Hold" style="cursor:pointer;">On Hold</span>&nbsp;'+
                            '<span><strong>'+obj['onhold']+'</strong></span>'+
                          '</li>';
                  }
                  var pending = "";
                  if(obj['pending'])
                  {
                    pending = '<li class="pull-left">'+
                            '<span class="pos_counter" data-id="Pending" style="cursor:pointer;">Pending</span>&nbsp;'+
                            '<span><strong>'+obj['pending']+'</strong></span>'+
                          '</li>';
                  }
                  var resched = "";
                  if(obj['resched'])
                  {
                    resched = '<li class="pull-left">'+
                            '<span class="pos_counter" data-id="Rescheduled" style="cursor:pointer;">Rescheduled</span>&nbsp;'+
                            '<span><strong>'+obj['resched']+'</strong></span>'+
                          '</li>';
                  }
                  temp = '<div class="pull-right" id="pos_activity_counter">'+
                            '<ul class="status-count list-inline">'+
                              enroute+
                              onhold+
                              pending+
                              resched+
                            '</ul>'+
                          '</div>';

                  container.html(temp);

                  temp_second = '<div class="clearfix text-center" id="pos_activity_counter_second">'+
                                  '<ul class="status-count status-count-bot list-inline" >'+
                                    enroute+
                                    onhold+
                                    pending+
                                    resched+
                                  '</ul>'+
                                '</ul>';

                  container_second.html(temp);

                  // if ($('.cwo_list_div').find("#DataTables_Table_0_filter").find(".input-sm").val()) {}

                  _this.parent('td').parent('tr').remove();
                  var show_entries = $('body').find(".cwo_list_div").find(".dataTables_info").text();
                  var exploded_show_entries = show_entries.split(" ");

                  if ((Number(exploded_show_entries[3])-1) != 0) {
                    var new_show_entries = exploded_show_entries[0]+" "+Number(exploded_show_entries[1])+" "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];
                  } else {
                    var new_show_entries = exploded_show_entries[0]+" 0 "+exploded_show_entries[2]+" "+(Number(exploded_show_entries[3])-1)+" "+exploded_show_entries[4]+" "+(Number(exploded_show_entries[5])-1)+" "+exploded_show_entries[6];
                    location.reload();
                  }
                  $('body').find(".cwo_list_div").find(".dataTables_info").html(new_show_entries);
                });
              }
            });
          }
        }
      });
    }
  });

  $('body').on('click','.change_patient_entry_date',function(){
    var patientID = $(this).attr('data-id');

    modalbox(base_url + 'order/edit_patient_entry_time/' + patientID,{
        header:"Edit Customer Entry Date",
        button: false
    });

    //Saving the changes for the patient entry date
    $('body').on('click','.save-changes-entry-time-btn',function(){
      var form_data = $('#edit_patient_entry_time_form').serialize();

      $.post(base_url + 'order/save_changes_patient_entry_date/' + patientID,form_data, function(response){
        var obj = $.parseJSON(response);
        jAlert(obj['message'],'Response');
        if(obj['error'] == 0)
        {
          setTimeout(function(){
            location.reload();
          },1000);
        }
      });
    });
  });

  $('body').on('click','.change_order_pickup_date',function(){
    var patientID = $(this).attr('data-id');
    var uniqueID = $(this).attr('data-uniqueID');

    modalbox(base_url + 'order/edit_order_pickup_date/' + patientID+ "/" + uniqueID,{
        header:"Edit Order Pickup Date",
        button: false
    });

    //Saving the changes for the pickup date of the Pickup Order
    $('body').on('click','.save-changes-pickup-date-btn',function(){
      var form_data = $('#edit_order_pickup_date_form').serialize();

      $.post(base_url + 'order/save_changes_order_pickup_date/' + patientID,form_data, function(response){
        var obj = $.parseJSON(response);
        jAlert(obj['message'],'Response');
        if(obj['error'] == 0)
        {
          setTimeout(function(){
            location.reload();
          },1000);
        }
      });
    });

  });

  //for the inserting of threaded comments in modal
  $('body').on('click','.enter-comments-btn',function(){
    var id = $(this).attr('data-id');
    var form_data = $('#enter-comment-page').serialize();
    var this_element = $(this);

    jConfirm('Post this comment now?', 'Reminder', function(response){
      if(response)
      {
        $.post(base_url + 'order/insert_order_comments/' + id,form_data, function(response){
          var obj = $.parseJSON(response);
          jAlert(obj['message'],'Response');
          if(obj['error'] == 0)
          {
            var comment_count = $('body .work_order_notes_'+id).find('p').text();
            $('body .work_order_notes_'+id).find('p').html(Number(comment_count)+1);

            closeModalbox();
            // setTimeout(function(){
            //   location.reload();
            // },1000);
          }
        });
      }
    });
  });
   // //for the inserting of threaded comments in modal
   // $('body').on('click','.enter-comments-btn',function(){
   //    var id = $(this).attr('data-id');
   //    var form_data = $('#enter-comment-page').serialize();
   //    var this_element = $(this);

   //    jConfirm('Post this comment now?', 'Reminder', function(response){
   //      if(response)
   //      {
   //        $.post(base_url + 'order/insert_order_comments/' + id,form_data, function(response){
   //          var obj = $.parseJSON(response);
   //          jAlert(obj['message'],'Response');
   //          if(obj['error'] == 0)
   //          {
   //            setTimeout(function(){
   //              location.reload();
   //            },1000);
   //          }
   //        });
   //      }
   //    });
   // });

/*
$('.send_to_confirm_workorder').bind('click',function(){
  var medical_ids;
  var unique_ids;
  var status = "tobe_confirmed";

  if($(this).is(':checked'))
  {
    jConfirm("Send en route to Confirm WO#?","Reminder",function(response){
      if(response)
      {
        var data = [];
        $('.hdn_send_to_confirm_workorder').each(function(){
            unique_ids = $(this).attr('data-enroute-unique-id');
            medical_ids = $(this).attr('data-enroute-id');
            data.push({
                        "medical_record_id" : medical_ids,
                        "unique_id"         : unique_ids,
                        "order_status"      : status
                      });

        });
        if(data.length>0)
        {
            me_message_v2({error:2,message:"<i class='fa fa-spinner fa-spin'></i> Sending ENROUTES to Confirm Work Orders"});
             $.post(base_url + "order/move_enroute_ordersv2/",{data:data},function(response){
                me_message_v2(response);
                if(response.error==0)
                {
                    window.location.reload();
                }
            });
        }
        else
        {
            me_message_v2({error:1,message:"No ENROUTES to send"});
        }
        // $.post(base_url + "order/move_enroute_orders/" + medical_ids + "/" + status + "/" + unique_ids, function(response){
        //       var obj = $.parseJSON(response);
        //       if(obj['error'] == 0)
        //       {
        //         jAlert(obj['message'],"Update Response");
        //         location.reload();
        //       }
        //     });
      }
      else
      {
        $('.send_to_confirm_workorder').attr('checked', false);
      }
    });
  }

}); */
  $('.send_to_confirm_workorder').on('click',function(){
        var medical_ids;
        var unique_ids;
        var status = "tobe_confirmed";

        if($(this).is(':checked'))
        {
          jConfirm("Send en route to Confirm WO#?","Reminder",function(response){
            if(response)
            {
              me_message_v2({error:2,message:"<i class='fa fa-spinner fa-spin'></i> Sending ENROUTES to Confirm Work Orders"});
               $.post(base_url + "order/move_enroute_ordersv2/",{moveenroute:true},function(response){
                  me_message_v2(response);
                  if(response.error==0)
                  {
                      window.location.reload();
                  }
                  $('.send_to_confirm_workorder').attr('checked', false);
              });
            }
            else
            {
              $('.send_to_confirm_workorder').attr('checked', false);
            }
          });
        }
  });

   //for the assigning of equipments
   $('.btn-save-equipment').bind('click',function(){
      var id = $(this).attr('data-id');
      var form_data = $('#assign_equip_form' + id).serialize();

      var this_element = $(this);

      jConfirm('Do you want to save changes now?', 'Reminder', function(response){
        if(response)
        {
          $.post(base_url + 'equipment/assign_equipment/' + id, form_data, function(response){
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



   //for the inserting of threaded comments
   $('.comment-reply-btn').bind('click',function(){
      var id = $(this).attr('data-id');
      var form_data = $('#insert-comment').serialize();

      var this_element = $(this);

      jConfirm('Post this comment now?', 'Reminder', function(response){
        if(response)
        {
          $.post(base_url + 'order/insert_order_comments',form_data, function(response){
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

   //change status of the pickup orders
   $('.save_pickup_stat').bind('click',function(){
      var unique_id = $(this).attr("data-id");
      var pickup_stat = $("input[type='radio'][name='pickup_stat']:checked").val();

      if(pickup_stat !== undefined)
      {
        jConfirm('Do you want to change the status of this order?','Alert', function(response){
          if(response)
          {
            $.ajax({
                type:"POST",
                url: base_url + "order/change_pickup_status/" + unique_id + "/" + pickup_stat,
                success:function(response){
                  var obj = $.parseJSON(response);
                  jAlert(obj['message'],'Response');
                  if(obj['error'] == 0)
                  {
                    setTimeout(function(){
                      location.reload();
                    },1000);
                  }
                },
                error:function(jqXHR, textStatus, errorThrown){
                  console.log(textStatus,errorThrown);
                },
            });
          }
        });
      }

      else
      {
        alert('Error in changing the status.');
      }

   });

    $('.save_edit_changes').bind('click',function(){
      var medical_id = $(this).attr("data-id");
      var form_data = $('.edit_patient_profile_form').serialize();
      var new_medical_id = $('.medical_record_num').val();
      var hospice_id = $('.hdn_hospice_id').val();
      var noorder_sign = $(this).attr("data-sign-noorder");
      var new_hospice_id = $('.hospice_provider_select').val();
      
      jConfirm('Done Editing Information?', 'Reminder', function(response){
          if(response)
          {
            $('.btn-confirm-order').prop('disabled',false);
            if(noorder_sign == 0)
            {
              $.post(base_url + 'order/update_patient_profile_confirmation_v2/' + medical_id , form_data, function(response){
                  var obj = $.parseJSON(response);
                  jAlert(obj['message'],'Response');
                  if(obj['error'] == 0)
                  {
                    window.location.href = base_url + "order/patient_profile/" + new_medical_id + "/" + new_hospice_id;
                  }
              });
            }
            else
            {
              $.post(base_url + 'draft_patient/update_patient_profile_confirmation_v2/' + medical_id , form_data, function(response){
                  var obj = $.parseJSON(response);
                  jAlert(obj['message'],'Response');
                  if(obj['error'] == 0)
                  {
                    window.location.href = base_url + "draft_patient/patient_profile/" + new_medical_id + "/" + new_hospice_id;
                  }
              });
            }
          }
      });
    });

    $('.save_edit_changes_confirmed').bind('click',function(){
    var medical_id = $(this).attr("data-id");
    var addressID = $(this).attr("data-addressID");
    var form_data = $('.edit_patient_profile_form').serialize();
    var new_medical_id = $('.medical_record_num').val();

    var hospice_id = $('.hdn_hospice_id').val();


    jConfirm('Done Editing Information?', 'Reminder', function(response){
        if(response)
        {
          $('.btn-confirm-order').prop('disabled',false);
          $.post(base_url + 'order/update_patient_profile/' + medical_id +'/'+ addressID, form_data, function(response){
              var obj = $.parseJSON(response);
              jAlert(obj['message'],'Response');
              if(obj['error'] == 0)
              {
                //window.location.href = base_url + "order/patient_profile/" + new_medical_id + "/" + hospice_id;
              }
          });
        }
    });
  });

  $('.save_edit_changes_confirmation').bind('click',function(){
    var medical_id = $(this).attr("data-id");
    var addressID = $(this).attr("data-addressID");
    var form_data = $('.edit_patient_profile_form').serialize();
    var new_medical_id = $('.medical_record_num').val();

    var hospice_id = $('.hdn_hospice_id').val();


    jConfirm('Done Editing Information?', 'Reminder', function(response){
        if(response)
        {
          $('.btn-confirm-order').prop('disabled',false);
          $.post(base_url + 'order/update_patient_profile_confirmation/' + medical_id +'/'+ addressID , form_data, function(response){
              var obj = $.parseJSON(response);
              jAlert(obj['message'],'Response');
              if(obj['error'] == 0)
              {
                //window.location.href = base_url + "order/patient_profile/" + new_medical_id + "/" + hospice_id;
              }
          });
        }
    });
  });

  $('.save_edit_changes_confirmed_respite').bind('click',function(){
    var medical_id = $(this).attr("data-id");
    var addressID = $(this).attr("data-addressID");
    var form_data = $('.edit_patient_profile_form').serialize();
    var new_medical_id = $('.medical_record_num').val();

    var hospice_id = $('.hdn_hospice_id').val();


    jConfirm('Done Editing Information?', 'Reminder', function(response){
        if(response)
        {
          $('.btn-confirm-order').prop('disabled',false);
          $.post(base_url + 'order/update_patient_profile_confirmation_respite/' + medical_id +'/'+ addressID , form_data, function(response){
              var obj = $.parseJSON(response);
              jAlert(obj['message'],'Response');
              if(obj['error'] == 0)
              {
                //window.location.href = base_url + "order/patient_profile/" + new_medical_id + "/" + hospice_id;
              }
          });
        }
    });
  });

  $('body').on('click','.save_note_btn',function(){
  // $('.save_note_btn').bind('click',function(){
    var medical_id = $(this).attr("data-id");
    var form_data = $('#insert_patient_notes').serialize();
    var reload_sign = $(this).attr("data-reload-sign");

    jConfirm('Save Customer Note Now?', 'Alert', function(response){
        if(response)
        {
          $.post(base_url + 'order/save_patient_notes/' + medical_id , form_data, function(response){
              //alert(response);
              var obj = $.parseJSON(response);
              jAlert(obj['message'],'Response');
              if(obj['error'] == 0)
              {
                $('#reason_for_cancel').modal("hide");
                $('.note_modal').modal("hide");
                //$('#globalModal').modal("hide");
                //closeModalbox();
                if(reload_sign == 1) {
                  location.reload();
                }
              }
          });
        }
    });
  });


  $('.register_user_btn').bind('click',function(){
    var form_data = $('#register_form').serialize();

    jConfirm('Save User Now?','Alert', function(response){
      if(response)
      {
        $.post(base_url + 'users/user_add', form_data, function(response){
          var obj = $.parseJSON(response);
          jAlert(obj['message'], 'Response');
          if(obj['error'] == 0)
          {
            setTimeout(function(){
              location.reload();
            },2000);
          }
        });
      }
    });
  });

  var act_type_btn_is_checked = function()
  {
    var act_type_btn = $('.activity_type_section_btn');

    if($('.additional_equip_button').attr('disabled'))
    {
      $('.activity_type_section_btn').show();
    }

  }
  act_type_btn_is_checked();

  //to reactivate activity type button in order summary
  $('.activity_type_section_btn').bind('click',function(){
    jConfirm('Are you sure you want to reactivate customer?','Reminder', function(response){
      if(response)
      {
        $('.additional_equip_button').removeAttr('disabled',true);
        $('.activity_type_section_btn').hide();
      }
    });
  });


  //to put enter when user press enter
  function getCaret(el) {
        if (el.selectionStart) {
            return el.selectionStart;
        }
        else if (document.selection) {
            el.focus();

            var r = document.selection.createRange();
            if (r == null) {
                return 0;
            }

            var re = el.createTextRange(),
            rc = re.duplicate();
            re.moveToBookmark(r.getBookmark());
            rc.setEndPoint('EndToStart', re);

            return rc.text.length;
        }
        return 0;
    }

   $(function()
    {
      $('.lot_number_field').keyup(function (e){
          if(e.keyCode == 13)
          {
            var curr = getCaret(this);
            var val = $(this).val();
            var end = val.length;
            $(this).val(val.substr(0, curr) + '<br />' + val.substr(curr, end));
          }
      })
    });


  // IN ORDER TO COLLECT THE UNIQUE ID OF THE CHECKED ITEMS IN PICK UP

  var get_checked_items = function()
  {
    var _this = $('.checked_pickup_item:checked');
    var _this_uniqueid = $('.checked_pickup_item:checked').attr("data-uniqueID");
    var _wrapper = $('#hdn_pickup_unique_div');
    var _max_field = 20;
    var _start = 1;

    _this.each(function(){
      var unique_ids = $(this).attr("data-uniqueID");

      if(_start < _max_field)
      {
        $(_wrapper).append("<input id='hdn_pickup_"+unique_ids+"' type='hidden' name='hdn_pickup_uniqueID[]' value="+unique_ids+" />");
      }
      _start++;
    });
  }
  get_checked_items();

  $('.checked_pickup_item').bind('click',function(){
    var _max_field = 20;
    var _wrapper = $('#hdn_pickup_unique_div');
    var unique_id = $(this).attr('data-uniqueID');
    var _start = 1;
    var equip_id = $(this).attr('data-equip-id');
    var checked_equipment_id = $(this).attr('id');

    if($(this).is(":checked"))
    {
      if(checked_equipment_id == "check_me_too")
      {
        $('.sub_equip_checkbox'+equip_id+unique_id).each(function(){
          var samp = $(this).attr("data-id");
          if(samp != "111")
          {
            $(this).prop('checked','checked');
          }
        });
        if(_start < _max_field)
        {
          _start++;
          $(_wrapper).append("<input id='hdn_pickup_"+unique_id+"' type='hidden' name='hdn_pickup_uniqueID[]' value="+unique_id+" />");
        }
      }
      else if(checked_equipment_id == "check2_me_too")
      {
        $('.sub_equip_checkbox'+equip_id+unique_id).each(function(){
          var samp = $(this).attr("data-id");
          if(samp == "111")
          {
            $(this).prop('checked','checked');
          }
        });
        if(_start < _max_field)
        {
          _start++;
          $(_wrapper).append("<input id='hdn_pickup_"+unique_id+"' type='hidden' name='hdn_pickup_uniqueID[]' value="+unique_id+" />");
        }
      }
      else
      {
        $('.sub_equip_checkbox'+equip_id+unique_id).prop('checked','checked');

        if(_start < _max_field)
        {
          _start++;
          $(_wrapper).append("<input id='hdn_pickup_"+unique_id+"' type='hidden' name='hdn_pickup_uniqueID[]' value="+unique_id+" />");
        }
      }
    }
    else
    {
      $('.sub_equip_checkbox'+equip_id+unique_id).prop('checked',false);
      $("#hdn_pickup_"+unique_id+"").remove();
    }
  });
  // END of this function


  //pick up old items for CUS Move
  $('.checked_pickup_old_item').on('click',function(){
    var _max_field = 20;
    var _wrapper = $('#hdn_pickup_ptmove_unique_div');
    var unique_id = $(this).attr('data-uniqueID');
    var _start = 1;
    var equip_id = $(this).attr('data-equip-id');

    if($(this).is(":checked"))
    {
      // $('.sub_equip_ptmove_checkbox'+equip_id+unique_id).prop('checked','checked');
      if(_start < _max_field)
      {
        _start++;
        $(_wrapper).append("<input id='hdn_pickup_ptmove_"+unique_id+"' type='hidden' name='hdn_pickup_ptmove_uniqueID[]' value="+unique_id+" />");
      }
    }
    else
    {
      $('.sub_equip_ptmove_checkbox'+equip_id+unique_id).prop('checked',false);
      $("#hdn_pickup_ptmove_"+unique_id+"").remove();
    }
  });

  //to get the uniqueID of checked items in ptmove
  var get_old_uniqueids = function()
  {
    var start_loop = 0;
    var _max_field = 30;
    var checked_item = $('.checked_pickup_item:checked');
    var checked_item_length = $('.checked_pickup_item:checked').length;
    var checked_item_uniqueID = $('.checked_pickup_item:checked').attr('data-work-order');
    var _wrapper = $('.old_unique_ids');

    checked_item.each(function(){
      var old_unique_ids = $(this).attr("data-work-order");
      if(start_loop < _max_field)
      {
        $(_wrapper).append('<input type="hidden" id="hdn_old_uniqueid_'+old_unique_ids+'" class="ptmove_checked_items_uniqueid" name="hdn_uniqueid_ptmove[]" value='+old_unique_ids+' />');
      }
      start_loop++;
    });

  }
  get_old_uniqueids();


  //get unique ids of the old items to be picked up in CUS Move
  var get_old_item_uniqueids = function()
  {
    var start_loop = 0;
    var _max_field = 30;
    var checked_item = $('.checked_pickup_old_item:checked');
    var checked_item_length = $('.checked_pickup_old_item:checked').length;
    var checked_item_uniqueID = $('.checked_pickup_old_item:checked').attr('data-work-order');
    var _wrapper = $('#hdn_old_items_checked_unique_div');

    checked_item.each(function(){
      var old_unique_ids = $(this).attr("data-work-order");
      if(start_loop < _max_field)
      {
        $(_wrapper).append('<input type="hidden" id="hdn_old_items_uniqueid_'+old_unique_ids+'" class="ptmove_checked_old_items_uniqueid" name="hdn_uniqueid_ptmove_old_items[]" value='+old_unique_ids+' />');
      }
      start_loop++;
    });

  }
  get_old_item_uniqueids();

  // IN ORDER TO COLLECT THE UNIQUE ID OF THE CHECKED ITEMS IN PICK UP
  $('.checked_item').bind('click',function(){
    var _max_field = 20;
    var _wrapper = $('#hdn_original_act_id');
    var unique_id = $(this).attr('data-uniqueID');
    var _start = 1;
    var equip_id = $(this).attr('data-equip-id');
    var orig_act_id = $(this).attr('data-orig-act-id');
    var checked_equipment_id = $(this).attr('id');

    if($(this).is(":checked"))
    {
      if(checked_equipment_id == "check3_me_too")
      {
        $('.sub_equip_checkbox_exchange'+equip_id+unique_id).each(function(){
          var samp = $(this).attr("data-id");
          if(samp != "111")
          {
            $(this).prop('checked','checked');
          }
        });
        if(_start < _max_field)
        {
          _start++;
          $(_wrapper).append("<input id='hdn_orig_act_"+unique_id+"' type='hidden' name='hdn_orig_act_type' value="+orig_act_id+" />");
        }
      }
      else if(checked_equipment_id == "check4_me_too")
      {
        $('.sub_equip_checkbox_exchange'+equip_id+unique_id).each(function(){
          var samp = $(this).attr("data-id");
          if(samp == "111")
          {
            $(this).prop('checked','checked');
          }
        });
        if(_start < _max_field)
        {
          _start++;
          $(_wrapper).append("<input id='hdn_orig_act_"+unique_id+"' type='hidden' name='hdn_orig_act_type' value="+orig_act_id+" />");
        }
      }
      else
      {
        $('.sub_equip_checkbox_exchange'+equip_id+unique_id).prop('checked','checked');

        if(_start < _max_field)
        {
          _start++;
          $(_wrapper).append("<input id='hdn_orig_act_"+unique_id+"' type='hidden' name='hdn_orig_act_type' value="+orig_act_id+" />");
        }
      }
    }
    else
    {
      $('.sub_equip_checkbox_exchange'+equip_id+unique_id).prop('checked',false);
      $("#hdn_orig_act_"+unique_id+"").remove();
    }
  });
  // END of this function

  // IN ORDER TO COLLECT THE UNIQUE ID OF THE CHECKED ITEMS IN PICK UP
  $('.checked_item').bind('click',function(){
    var _max_field = 20;
    var _wrapper = $('#hdn_original_act_id_pickup');
    var liter_flow_wrapper = $('#pickup_oxygen_liter_flow_orderID');
    var unique_id = $(this).attr('data-uniqueID');
    var _start = 1;
    var equip_id = $(this).attr('data-equip-id');
    var orig_act_id = $(this).attr('data-orig-act-id');
    var liter_flow_orderID = $(this).parent(".i-checks").siblings(".liter_flow_orderID_"+equip_id).val();

    if($(this).is(":checked"))
    {
      if(liter_flow_orderID != 0)
      {
        $(liter_flow_wrapper).append("<input type='hidden' id='exchange_liter_flow_orderID_"+liter_flow_orderID+"' name='exchange_liter_flow_orderID["+equip_id+"]' value="+liter_flow_orderID+" />");
      }
      $('.sub_equip_checkbox'+equip_id).prop('checked','checked');
      if(_start < _max_field)
      {
        _start++;
        $(_wrapper).append("<input id='hdn_original_act_id_pickup_"+unique_id+"' type='hidden' name='hdn_orig_act_type_pickup' value="+orig_act_id+" />");
      }
    }
    else
    {
      $('.sub_equip_checkbox'+equip_id).prop('checked',false);
      $("#hdn_original_act_id_pickup_"+unique_id+"").remove();
      $("#exchange_liter_flow_orderID_"+liter_flow_orderID+"").remove();
    }
  });
  // END of this function

  // IN ORDER TO COLLECT THE UNIQUE ID OF THE CHECKED ITEMS IN EXCHANGE
  $('.checked_item').bind('click',function(){
    var _max_field = 20;
    var _wrapper = $('#hdn_exchange_unique_div');
    var unique_id = $(this).attr('data-uniqueID');
    var _start = 1;

    if($(this).is(":checked"))
    {
      if(_start < _max_field)
      {
        _start++;
        $(_wrapper).append("<input id='hdn_"+unique_id+"' type='hidden' name='hdn_exchange_uniqueID[]' value="+unique_id+" />");
      }
    }
    else
    {
      $("#hdn_"+unique_id+"").remove();
    }
  });
  // END of this function


  // IN ORDER TO COLLECT THE UNIQUE ID OF THE CHECKED ITEMS IN PICK UP
  var checked_item_orig_act_type = $('.checked_item').attr('data-orig-act-id');
  $('#hdn_original_act_id_pickup').append("<input id='checked_orig_item' type='hidden' name='hdn_orig_act_type_pickup' value="+checked_item_orig_act_type+" />"); //remove this line if it will cause errors

  //for the confirmation part, to save the drivers name on the <td> input value
  $('.driver_name_to_save').bind('keyup',function(){
    _this = $(this).val();

    $('.name_of_driver').val(_this);

  });

  // //for the confirmation part, to save the drivers name on the <td> input value
  // $('.datepicker_2').bind('click',function(){
  //   _this = $(this).val();

  //   $('.actual_order_date').val(_this);

  // });


  $('#assign_location_create_user').on('change',function(){
    if($(this).val() == '- Please choose -')
    {
      $("#account_type_dropdown").attr("data-isSelected", "0");
    }
    else
    {
      $('#account_type_dropdown').attr("data-isSelected", "1");
    }

  });

  $('#account_type_dropdown').on('change',function(){
    if($(this).val() == 'dme_admin')
    {
       $('#username').val('dme_admin');
       $('#password').css('border','1px solid red');
    }
    else if($(this).val() == 'dme_user')
    {
       $('#username').val('dme_user');
       $('#password').css('border','1px solid red');
    }
    else if($(this).val() == 'hospice_admin' || $(this).val() == 'hospice_user')
    {
      $('#group_div').css('display','block');
      $('#group_div_companies').css('display','none');
      if($(this).attr("data-isSelected") != 0) {
        $('#groupname_select_div').css('display','none');
        $('#groupname_select').css('display','block');
      }
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


  $('.edit_password').bind('click',function(){
    $(this).val("");
  });

   //masked input for the phone number
   //$('#person_num').mask("(999) 999-9999");
   $('#p_phone_number').mask("(999) 999-9999");
   $('#p_alt_phonenum').mask("(999) 999-9999");
   $('#p_nextofkinphone').mask("(999) 999-9999");
   $('.person_num').mask("(999) 999-9999");
   $('.hosp_contact_num').mask("(999) 999-9999");
   $('.company_contact_num').mask("(999) 999-9999");
   //$('.person_mobile_num').mask("(999) 999-9999");

   $('.data_tooltip').tooltip();
   $('.comment-container').tooltip();
   $('.notes_help').tooltip();
   $('.patient_notes_count').tooltip();

  //  $('.datepicker').datepicker({
  //     dateFormat: 'yy-mm-dd'
  //  });

  //  $('.datepicker_2').datepicker({
  //     dateFormat: 'yy-mm-dd'
  //  });

   const initial_actual_order_date_value = $('body').find('#initial_actual_order_date_value').val();
   if (initial_actual_order_date_value) {
       const exploded_initial_actual_order_date_value = initial_actual_order_date_value.split('-');
       const final_month_value = Number(exploded_initial_actual_order_date_value[1]) - 1;
       $('.datepicker_scheduled_order_date').datepicker({
         dateFormat: 'yy-mm-dd',
         minDate: new Date(exploded_initial_actual_order_date_value[0], final_month_value , exploded_initial_actual_order_date_value[2])
       });
       $('.datepicker_scheduled_exchange_order_date').datepicker({
         dateFormat: 'yy-mm-dd',
         minDate: new Date(exploded_initial_actual_order_date_value[0], final_month_value , exploded_initial_actual_order_date_value[2])
       });
       $('.datepicker_scheduled_pickup_date').datepicker({
         dateFormat: 'yy-mm-dd',
         minDate: new Date(exploded_initial_actual_order_date_value[0], final_month_value , exploded_initial_actual_order_date_value[2])
       });
       $('.datepicker_cus_move_scheduled_order_date').datepicker({
         dateFormat: 'yy-mm-dd',
         minDate: new Date(exploded_initial_actual_order_date_value[0], final_month_value , exploded_initial_actual_order_date_value[2])
       });
       $('.datepicker_respite_scheduled_order_date').datepicker({
         dateFormat: 'yy-mm-dd',
         minDate: new Date(exploded_initial_actual_order_date_value[0], final_month_value , exploded_initial_actual_order_date_value[2])
       });

       $('.datepicker_2').datepicker({
        dateFormat: 'mm-dd-yy',
        minDate: new Date(exploded_initial_actual_order_date_value[0], final_month_value , exploded_initial_actual_order_date_value[2])
       });

       $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: new Date(exploded_initial_actual_order_date_value[0], final_month_value , exploded_initial_actual_order_date_value[2])
       });

       $('.datepicker_confirm').datepicker({
        dateFormat: 'mm-dd-yy',
        minDate: new Date(exploded_initial_actual_order_date_value[0], final_month_value , exploded_initial_actual_order_date_value[2])
       });

       $('.datepicker_order_date_exchange').datepicker({
        dateFormat: 'mm-dd-yy',
        minDate: new Date(exploded_initial_actual_order_date_value[0], final_month_value , exploded_initial_actual_order_date_value[2])
       });

       $('.datepicker_exchange').datepicker({
        dateFormat: 'mm-dd-yy',
        minDate: new Date(exploded_initial_actual_order_date_value[0], final_month_value , exploded_initial_actual_order_date_value[2])
       });
   } else {
       $('.datepicker_scheduled_order_date').datepicker({
         dateFormat: 'yy-mm-dd'
       });
       $('.datepicker_scheduled_exchange_order_date').datepicker({
         dateFormat: 'yy-mm-dd'
       });
       $('.datepicker_scheduled_pickup_date').datepicker({
         dateFormat: 'yy-mm-dd'
       });
       $('.datepicker_cus_move_scheduled_order_date').datepicker({
         dateFormat: 'yy-mm-dd'
       });
       $('.datepicker_respite_scheduled_order_date').datepicker({
         dateFormat: 'yy-mm-dd'
       });

       $('.datepicker_2').datepicker({
        dateFormat: 'mm-dd-yy'
       });

       $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
       });

       $('.datepicker_confirm').datepicker({
        dateFormat: 'mm-dd-yy'
       });

       $('.datepicker_order_date_exchange').datepicker({
        dateFormat: 'mm-dd-yy'
       });

       $('.datepicker_exchange').datepicker({
        dateFormat: 'mm-dd-yy'
       });
   }
 
    $('.search_datepicker').datepicker({
       dateFormat: 'yy-mm-dd',
       maxDate: 0
    });
 
    //$('.modal').draggable();
 
    //added to solve the problem when opening two modals in the same page
     $('.modal').on('hidden.bs.modal', function (e) {
       if($('.modal').hasClass('in')) {
       $('body').addClass('modal-open');
       }
     });
 
 
 
    $('#assign_equip').dataTable({
        "aLengthMenu": [[25, 50, 75, 100,500], [25, 50, 75, 100, "All"]],
          "iDisplayLength": 500
     });
 
   $('#choose_company_account').bind('click',function(){
     $(this).attr('checked',true);
     $('#add_company_div').show();
     $('#add_hospice_div').hide();
   });
 
   $('#choose_hospice_account').bind('click',function(){
     $(this).attr('checked',true);
     $('#add_hospice_div').show();
     $('#add_company_div').hide();
   });
 
 
 
 
 
 
   //generate random account numbers for hospice
   var min = 10000;
   var max = 99999;
   var num = Math.floor(Math.random() * (max - min + 1)) + min;
 
   $('.hospice_account_num').val(num);
   $('.company_account_num').val(num);
 
   $('.create_random_account_number').bind('click',function(){
       var minimum = 10000;
       var maximum = 99999;
       var rand_num = Math.floor(Math.random() * (maximum - minimum + 1)) + minimum;
 
       $('.edit_hospice_account_num').val(rand_num);
   });
   //end of this funtion
 
 
 
   //** Disable the activity type button when all of the activity type in order summary is already pickup **//
   $('#equipment_summary_tbl tbody tr td[class=item_serial_number]').each(function(){
         var _this_serial = $(this).html();
         var arr = [];
         if(_this_serial != 'item_options_only')
         {
 
            $('#equipment_summary_tbl tbody tr td[class=activity_type_column]').each(function(){
               var _this_activity = $(this).html();
               var count = 0;
 
               arr.push(_this_activity);
 
           });
           // $.inArray("Pickup",arr) > -1 ||
           if($.inArray("Pickup",arr) > -1 || $.inArray("Delivery",arr) > -1 || $.inArray("Exchange",arr) > -1 || $.inArray("CUS Move",arr) > -1 || $.inArray("Respite",arr) > -1)
           {
             $('#additional_equip_btn').prop('disabled', false);
           }
           else
           {
             $('#additional_equip_btn').attr('disabled', 'disabled');
           }
         }
     });
 
 
 
   /** to show hippa policy when this is the first login of the user **/
   var _first_loggedin = $('#is_first_loggedin').val();
   var _changed_password = $('#is_changed_password').val();
 
   if(_first_loggedin == 1 || _changed_password == 1)
   {
     $('#modal_for_hippa_policy').modal({
       backdrop: 'static',
       keyboard: false
     });
     $('#modal_for_hippa_policy').modal("show");
   }
 
 
   /** Check the hippa policy **/
   $('#check_hippa_policy').bind('click',function(){
     if($(this).is(':checked'))
     {
       $('#hdn_first_loggedin').val(0);
       $('#hdn_changed_password').val(0);
       $('#agreed_to_hippa_policy').prop('disabled',false);
 
       $('#agreed_to_hippa_policy').bind('click',function(){
         var user_id = $(this).attr('data-user-id');
         var form_data = $('#update_user_hippa').serialize();
 
         $.post(base_url+"users/update_first_loggedin/"+user_id, form_data, function(response){
           var obj = $.parseJSON(response);
 
           jAlert(obj['message'],'Response');
           $('#modal_for_hippa_policy').modal("hide");
 
         });
 
 
       });
 
     }
     else
     {
       $('#hdn_first_loggedin').val(1);
       $('#hdn_changed_password').val(1);
       $('#agreed_to_hippa_policy').prop('disabled',true);
     }
 
   });
 
 
   var hide_misc_disposable = function()
   {
     var account_type = $('#account_type_id').val();
 
     if(account_type == "dme_admin" || account_type == "dme_user" || account_type == "biller" || account_type == "customer_service" || account_type == "rt" || account_type == "sales_rep" || account_type == "distribution_supervisor")
     {
       $('.c-miscellaneous-3').parent().parent().show();
     }
     else
     {
       $('.c-miscellaneous-3').parent().parent().hide();
     }
   }
   hide_misc_disposable();
 
 
 
   //make all input text,email,select,textarea field a gray field with inner shadow
   $('input[type=text]').addClass("grey_inner_shadow");
   $('input[type=email]').addClass("grey_inner_shadow");
   $('select').addClass("grey_inner_shadow");
   $('textarea').addClass("grey_inner_shadow");
   $('.checkbox .i-checks i').addClass("grey_inner_shadow");
   $('.radio .i-checks i').addClass("grey_inner_shadow");
 
   //to detect what platform
   var detect_platform = function(){
 
     var _platform = navigator.platform;
     var current_url = window.location.href;
 
     if(current_url == base_url+"menu")
     {
       if(_platform.toLowerCase() == "iphone" || _platform.toLowerCase() == "linux armv7l" || _platform.toLowerCase() == "ipod" || _platform.toLowerCase() == "android" || _platform.toLowerCase() == "blackberry")
       {
         if(navigator.userAgent.match(/iPad/i) == null)
         {
           window.location.href = base_url+"menu/mobile_menu";
         }
       }
     }
     else
     {
         $('.app').css('background-position','200px !important');
     }
   };
   detect_platform();
 });
 
 function me_message(target,title,message,status)
 {
     var status_message = new Array();
     status_message[0] = "alert-success";
     status_message[1] = "alert-danger";
     status_message[2] = "alert-info";
     status_message[3] = "alert-warning";
 
     if(status_message[status]!==undefined)
     {
         status = 2;
     }
     html_data = '<div class="alert '+status_message[status]+' fade in" role="alert">'+
                         '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>'+
                         '<h4>'+title+'</h4>'+
                         '<p>'+message+'</p>'+
                 '</div>';
     $(target).html(html_data);
 }
 
 
 
 function me_message(target,title,message,status)
 {
     var status_message = new Array();
     status_message[0] = "alert-success";
     status_message[1] = "alert-danger";
     status_message[2] = "alert-info";
     status_message[3] = "alert-warning";
 
     if(status_message[status]===undefined)
     {
         status = 2;
     }
     html_data = '<div class="alert '+status_message[status]+' fade in" role="alert">'+
                         '<button type="button" class="close" data-dismiss="alert"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span class="sr-only">Close</span></button>'+
                         '<h4>'+title+'</h4>'+
                         '<p>'+message+'</p>'+
                 '</div>';
     $(target).html(html_data);
 }
 
 function resizeIframe(obj)
 {
     obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
   }
 
 
 function print_forms()
 {
    window.print();
 }
 
