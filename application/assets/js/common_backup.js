// magic slider script

jQuery(function() {

      jQuery('#magic_carousel_white6').magic_carousel({
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
      
    });
  // magic slider script

    // magic slider script

jQuery(function() {

      jQuery('#magic_carousel_white1').magic_carousel({
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





$(document).ready(function(){


  /** Turn off auto complete for all input **/
  $(document).on('focus', ':input', function() {
    $(this).attr('autocomplete', 'off');
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

//Auto Detect NEW PT MOVE for CONFIRM
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
    $(this).val($(this).val().toUpperCase());
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
  });
  



/** Show tooltip for equipment OPTIONS **/
$(".equipment_options_tooltip").tooltip();
$('.patient_weight_required').tooltip();
$('.lot_number_required').tooltip();


/** Auto detect of the patient that is to be input exists in our database already **/
var check_if_patient_exists = function()
{
  $('#patient_mrn').bind('keyup', function(){
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
        $.ajax({
          type:"POST",
          url:base_url+"main/check_existing_patient/"+this_val+"/"+hdn_hospice_id,
          success:function(response)
          {
            $('#patient_mrn').popover("show");
            _this.attr("data-content",response);
          },
          error:function(jqXHR, textStatus, errorThrown)
          {
            console.log(textStatus, errorThrown);
          }

        });
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



$('.equipment_options_hover').mouseover(function(){
  var id = $(this).attr("data-id");
  var unique_id = $(this).attr("data-uniqueid");
  var _this = $(this);

  $('.equipment_options_hover').popover({
    trigger:"hover",
    html: true
  }).hover(showPopover, hidePopover);

  $.ajax({
      url : base_url+"order/get_equipment_options/"+id+"/"+unique_id,
      success:function(response)
      {
        if(response != "" || response != null)
        {
          $(_this).attr("data-title","Options");
          $(_this).attr("data-content",response);
        }
        
      }
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


   $('.edit_item_options').bind('click',function(){
    var id = $(this).attr("data-id");
    var unique_id = $(this).attr("data-uniqueid");
    var medical_id = $(this).attr("data-medical-id");
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
    if(id==181 || id==182)
    {
      modalbox(base_url + 'order/edit_patient_weight/' + unique_id + "/" + id + "/" + medical_id,{
          header:"Edit Patient Weight",
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

   $('.save_liter_flow').bind('click',function(){
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


   $('.save_updated_weight_btn').bind('click',function(){
      var weight_id = $(this).attr('data-id');
      var unique_id = $(this).attr('data-unique-id');
      var form_data = $('#update_patient_weight').serialize();

      jConfirm('Update Patient Weight?','Reminder', function(response){
        
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


    $('.put_patient_weight').bind('click',function(){
      var unique_id = $(this).attr('data-unique-id');
      var form_data = $('#put_patient_weight').serialize();

      jConfirm('Save Patient Weight?','Reminder', function(response){
        
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



  $('.save_edited_summary').bind('click',function(){
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


/** Search Patient Records **/
$('#search-patients').bind('keyup',function(){
    var searchString = $(this).val();
    var data_value = $('.patient_results').attr("data-value");
    var hospice_group = $(this).attr("data-group");

    $('#pfname').val($(this).val().toLowerCase());
    $('#plname').val($(this).val().toLowerCase());

    if($('#search-patients').val() !== "" || $('#search-patients').val() !== null)
    {
      $.ajax({
           type:"POST",
           url:base_url+'order/search_patients/' + searchString + '/' + hospice_group,
           success:function(response)
           {
              $('#suggestion_container').show();
              $('#suggestion_container').html(response);

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
});


/** Search ITEM TRACKING by SERIAL NUMBER **/
$('#item-tracking-search').bind('keyup',function(){
  var searchString = $(this).val();
  var data_value   = $('.item_results').attr('data-value');

  if($('#item-tracking-search').val() !== "" || $('#item-tracking-search').val() !== null)
  {
    $.ajax({
           type:"POST",
           url:base_url+'order/search_items_tracking/' + searchString,
           success:function(response)
           {
              $('#item_tracking_suggestions').show();
              $('#item_tracking_suggestions').html(response);

              $(".items_result").bind('click', function(){ 
                 var serial_number = $(this).attr('data-value');
                 var id = $(this).attr('data-id');
                 var item_record = $("#item-tracking-search");

                 
                 $('#suggestion_container').hide();
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
    $.ajax({
           type:"POST",
           url:base_url+'order/search_lot_number/' + searchString,
           success:function(response)
           {
              $('#oxygen_lot_number_suggestions').show();
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




  //Change Order Status to Confirm in PT Order Status
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
          $.post(base_url+"order/change_to_confirmed/"+medical_id+"/"+unique_id+"/"+activity_type, form_data , function(response){
            var obj = $.parseJSON(response); 
            jAlert(obj['message'],"Reminder");
            if(obj['error'] == 0)
            {
              location.reload();
            }
          });
        }
      }
  });
});


  //Change Order Status to Confirm in PT Order Status
  $('.btn-confirm-exchange-order').bind('click',function(){
    var medical_id = $(this).attr('data-medical-id');
    var unique_id  = $(this).attr('data-unique-id'); 
    var form_data = $('.update_order_summary_exchange').serialize();

    jConfirm("Confirm Work Order?","Reminder",function(response){
      if(response)
      {
          $.post(base_url+"order/change_to_confirmed_exchange/"+medical_id+"/"+unique_id, form_data , function(response){
            var obj = $.parseJSON(response);
            jAlert(obj['message'],"Reminder");
            if(obj['error'] == 0)
            {
              location.reload();
            }
          });
        }
    });
  });


  //Addtional equipment
  var show_after_hour_alert = function()
	{
	    var dt = new Date();
	    var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
	    if(dt.getHours() == 17)
	    {
	      $('.after_hour_alert_content').show();
	      $('#after_hour_alert').modal("show");
	    }
	};
  
  
  $('.save_additional_btn').bind('click',function(){
    var id = $(this).attr("data-id");
    var form_data = $('#add_additional_equipment_form').serialize();
    jConfirm('Submit Activity?', 'Reminder', function(response){
        if(response)
        {
		  if($(location).attr('href') != base_url)
			{
				if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
				{
				  show_after_hour_alert();
				}
			}
		
          //$('#submit_order_loader').modal("show");

          $.post(base_url + 'order/add_additional_equipments/' + id, form_data ,function(response){
            //alert(response);
            var obj = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');

            if(obj['error'] == 0)
            {
                $('#submit_order_loader').modal("hide");
                setTimeout(function(){
                  location.reload();
                },1500);
            }
            
          });
        }
    });
  });

  /** Save Pickup Order **/
  $('.save_pickup_data').bind('click',function(){
    var id = $(this).attr("data-id");
    var form_data = $('#add_additional_equipment_form').serialize();

    jConfirm('Submit Activity?', 'Reminder', function(response){
        if(response)
        {
		 if($(location).attr('href') != base_url)
				{
				    if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
				    {
				      show_after_hour_alert();
				    }
				}
          //$('#submit_order_loader').modal("show");
          $.post(base_url + 'order/update_status_to_pickup/' + id, form_data ,function(response){
            var obj = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');

            if(obj['error'] == 0)
            {
              $('#submit_order_loader').modal("hide");
              setTimeout(function(){
                location.reload();
              },1500);
            }
            else
            {
              $('#submit_order_loader').modal("hide");
            }
            
          });
        }
    });
  });


  /** Save Exchange Order **/
  $('.save_exchange_data').bind('click',function(){
    var id = $(this).attr("data-id");
    var form_data = $('#add_additional_equipment_form').serialize();

    jConfirm('Submit Activity?', 'Reminder', function(response){
        if(response)
        {
			if($(location).attr('href') != base_url)
				{
				    if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
				    {
				      show_after_hour_alert();
				    }
				}
			
          //$('#submit_order_loader').modal("show");
          $.post(base_url + 'order/change_status_to_exchange/' + id, form_data ,function(response){
            var obj = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');

            if(obj['error'] == 0)
            {
              $('#submit_order_loader').modal("hide");
              setTimeout(function(){
                location.reload();
              },1500);
            }
            
          });
        }
    });
  });


  /** Save PT MOVE Order **/
  $('.save_additional_btn_ptmove').bind('click',function(){
    var id = $(this).attr("data-id");
    var form_data = $('#add_additional_equipment_form').serialize();

    jConfirm('Submit Activity?', 'Reminder', function(response){
        if(response)
        {
			if($(location).attr('href') != base_url)
				{
				    if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
				    {
				      show_after_hour_alert();
				    }
				}
			
          //$('#submit_order_loader').modal("show");
          $.post(base_url + 'order/change_status_to_ptmove/' + id, form_data ,function(response){
            var obj = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');

            if(obj['error'] == 0)
            {
                $('#submit_order_loader').modal("hide");
                setTimeout(function(){
                location.reload();
              },1500);
            }
            
          });
        }
    });
  });

  /** Save PT MOVE Order **/
  $('.save_additional_btn_respite').bind('click',function(){
    var id = $(this).attr("data-id");
    var form_data = $('#add_additional_equipment_form').serialize();

    jConfirm('Submit Activity?', 'Reminder', function(response){
        if(response)
        {
		if($(location).attr('href') != base_url)
				{
				    if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
				    {
				      show_after_hour_alert();
				    }
				}
          //$('#submit_order_loader').modal("show");
          $.post(base_url + 'order/change_status_to_respite/' + id, form_data ,function(response){
            var obj = $.parseJSON(response);
            jAlert(obj['message'],'Reminder');

            if(obj['error'] == 0)
            {
              $('#submit_order_loader').modal("hide");
                setTimeout(function(){
                location.reload();
              },1500);
            }
            
          });
        }
    });
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

    jConfirm('Save Note?', 'Reminder', function(response){
      if(response)
      {
        $.post(base_url+"order/update_profile_order_summary/" + medical_id, form_data, function(response){
          var obj  = $.parseJSON(response);
          jAlert(obj['message'],'Reminder');
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



   $('#dc_radio').click(function(){
      $('#dc_input').prop('disabled','');
   });
   

   $('.dc_radios').click(function(){
      $('#dc_input').prop('disabled','disabled');
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
   });



  //** gamit para kuhaon ang value sa option nga gi dynamic **/
  $("#groupname_select").bind("change",function(){
        $("#hdnGroup_name").val(this.options[this.selectedIndex].text);
        //alert(this.options[this.selectedIndex].text);
  });

  $(".hospice_provider_select").bind("change",function(){
    $(".hdn_provider_name").val(this.options[this.selectedIndex].text);
    //alert(this.options[this.selectedIndex].text);
  });
  
  //** gamit para kuhaon ang value sa option nga gi dynamic **/
  $(".edit_hospicename").bind("change",function(){
        //console.log('nisud');
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
      if($(this).val() == '2')
      { 
         $('.equipment_section').hide();
         $('#fordelivery_categories').hide();
         $('#forpickup_categories').show();
         $('#forpickup_categories2').show();
         $('#forpickup_categories3').show();
         $('#forpickup_categories4').show();
         $('#forpickup_categories5').show();
         $('#forpickup_categories6').show();
         $('#forptmove_categories').hide();
         $('#forptmove_categories2').hide();
         $('#forptmove_categories3').hide();
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
         $('#hdn_submit_btn').show();
         $('#default_order_btn_ptmove').hide();
         $('#default_order_btn_respite').hide();
         $('#default_order_btn').hide();
         

      }
      else if($(this).val() == '3')
      {
         $('.equipment_section').hide();
         $('#fordelivery_categories').hide();
         $('#forpickup_categories').hide();
         $('#forpickup_categories2').hide();
         $('#forpickup_categories3').hide();
         $('#forpickup_categories4').hide();
         $('#forpickup_categories5').hide();
         $('#forpickup_categories6').hide();
         $('#forptmove_categories').hide();
         $('#forptmove_categories2').hide();
         $('#forptmove_categories3').hide();
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
         $('#default_order_btn_ptmove').hide();
         $('#default_order_btn_respite').hide();
          $('#default_order_btn').hide();
         //$('#hdn_submit_btn').show();
      }
      else if($(this).val() == '4')
      {
        $('.equipment_section').show();
        $('#fordelivery_categories').hide();
         $('#forpickup_categories').hide();
          $('#forpickup_categories2').hide();
          $('#forpickup_categories3').hide();
          $('#forpickup_categories4').hide();
          $('#forpickup_categories5').hide();
          $('#forpickup_categories6').hide();
         $('#forptmove_categories').show();
         $('#forptmove_categories2').show();
         $('#forptmove_categories3').show();
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
         $('#hdn_submit_btn').hide();
         $('#default_order_btn_ptmove').show();
         $('#default_order_btn_respite').hide();
          $('#default_order_btn').hide();
      }
      else if($(this).val() == '5')
      {
        $('.equipment_section').show();
        $('#fordelivery_categories').hide();
         $('#forpickup_categories').hide();
          $('#forpickup_categories2').hide();
          $('#forpickup_categories3').hide();
          $('#forpickup_categories4').hide();
          $('#forpickup_categories5').hide();
          $('#forpickup_categories6').hide();
         $('#forptmove_categories').hide();
         $('#forptmove_categories2').hide();
         $('#forptmove_categories3').hide();
         $('.forptmove_emergency_contact').hide();
         $('#forrespite_categories').show();
         $('#forrespite_categories2').show();
         $('#forrespite_categories3').show();
         $('#forrespite_categories4').show();
          $('#forrespite_categories5').show();
         $('#wrapper_equip_1').hide();
         $('#forexchange_categories').hide();
         $('#forexchange_categories2').hide();
         $('#forexchange_categories3').hide();
         $('#forexchange_categories4').hide();
         $('#hdn_submit_btn').hide();
         $('#default_order_btn_ptmove').hide();
         $('#default_order_btn_respite').show();
        $('#default_order_btn').hide();
      }
      else
      {
         $('.equipment_section').show();
         $('#fordelivery_categories').show();
         $('#forpickup_categories').hide();
         $('#forpickup_categories2').hide();
         $('#forpickup_categories3').hide();
         $('#forpickup_categories4').hide();
         $('#forpickup_categories5').hide();
         $('#forpickup_categories6').hide();
         $('#forptmove_categories').hide();
         $('#forptmove_categories2').hide();
         $('#forptmove_categories3').hide();
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
         $('#hdn_submit_btn').hide();
         $('#default_order_btn_ptmove').hide();
         $('#default_order_btn_respite').hide();
          $('#default_order_btn').show();
      }  
   });


/** To uncheck radio buttons in activity type **/
var allRadios = document.getElementsByName('activity_type');
var genderRadios = document.getElementsByName('relationship_gender');
var ptmove_old_address = document.getElementsByName('ptmove_old_address');

var booRadio;
var x = 0;
for(x = 0; x < allRadios.length; x++){
    allRadios[x].onclick = function(){
      if(booRadio == this){
          $('#fordelivery_categories').hide();
          $('#forpickup_categories').hide();
          $('#forpickup_categories2').hide();
          $('#forpickup_categories3').hide();
          $('#forpickup_categories4').hide();
           $('#forpickup_categories5').hide();
           $('#forpickup_categories6').hide();
          $('#forptmove_categories').hide();
          $('#forptmove_categories2').hide();
          $('#forptmove_categories3').hide();
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

//to uncheck the old address in pt move after picking up the items from that old address
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
         // $('#p_date_discharge').css('display','none');
         // $('#p_date_revoked').css('display','none');
      }
      else if($(this).val() == 'discharged'){
         $('#p_date_expired').css('display','block');
         // $('#p_date_expired').css('display','none');
         // $('#p_date_revoked').css('display','none');
      }
      else if($(this).val() == 'revoked'){
         $('#p_date_expired').css('display','block');
         // $('#p_date_discharge').css('display','none');
         // $('#p_date_expired').css('display','none');
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

   
   //for the auto populate of the phone number depending in each hospice
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


$('.liter_flow_field').bind('keyup',function(){
   if($.isNumeric($(this).val()))
   {
      if($(this).val() < 5 || $(this).val() == 5)
      {
         $('.5_ltr').prop('checked', true);
         $('.10_ltr').prop('checked', false);
      }
      else if($(this).val() <= 10)
      {
         $('.10_ltr').prop('checked', true);
         $('.5_ltr').prop('checked', false);
      }
      else
      {
         $('.5_ltr').prop('checked', true);
         $('.10_ltr').prop('checked', true);
      } 
      
      $(this).css('border','1px solid rgba(234, 234, 234, 1) !important');
         
   }
   else
   {
      $(this).val(''); 
      $(this).css('border','1px solid red');  
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
      var canceled_status = "";
      var form_data = $('#canceled-order-form').serialize();

    
      if(_this.is(":checked"))
      {
        _this.parents("tr").css('text-decoration','line-through');
        $('.serial_num'+equipment_id).val("---");
        $('.serial_num'+equipment_id).attr("readonly","readonly");
        // $('.action_data').hide();
        // $('.save_edited_summary').hide();
        canceled_status = 0;

        $.post(base_url+"equipment/cancel_equipment/"+equipment_id+"/"+medical_id+"/"+canceled_status, form_data, function(response){
            var obj = $.parseJSON(response);
            
            $.ajax({
              url:base_url+"order/show_patient_notes/"+medical_id+"/"+p_fname+"/"+p_lname+"/"+hospice_name,
              type:"POST",
              success:function(response)
              {
                $('#reason_for_cancel').modal("show");
                $('#reason_for_cancel').find(".modal-body").html(response);
              }
            });
        });

      }
      else
      {
	    $('.serial_num'+equipment_id).val("");
        $('.serial_num'+equipment_id).prop("readonly",false);
		
        canceled_status = 1;

        $.post(base_url+"equipment/cancel_equipment/"+equipment_id+"/"+medical_id+"/"+canceled_status, form_data, function(response){
          var obj = $.parseJSON(response);
        });

        _this.parents("tr").css('text-decoration','none');
        // $('.action_data').show();
        // $('.save_edited_summary').show();
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
    }
  });

});



  //to save the patient weight base
  $('.save_weight_btn').bind('click',function(response){
      var form_data = $('#insert_patient_weight').serialize();

      jConfirm('Save Patient Weight?', 'Reminder', function(response){
        
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
    if($(this).is(":checked"))
    {
      $('.c-commode_pail-3').prop('checked',true);
    }
    else
    {
      $('.c-commode_pail-3').prop('checked',false);
    }

  });

  $('.c-commode_3_and_1-1').bind('click',function(){
    if($(this).is(":checked"))
    {
      $('.c-commode_pail-3').prop('checked',true);
    }
    else
    {
      $('.c-commode_pail-3').prop('checked',false);
    }

  });


  $('.c-bariatric_commode-1').bind('click',function(){
    if($(this).is(":checked"))
    {
      $('.c-commode_pail-3').prop('checked',true);
    }
    else
    {
      $('.c-commode_pail-3').prop('checked',false);
    }

  });


  $('.c-commode_bariatric-2').bind('click',function(){
    if($(this).is(":checked"))
    {
      $('.c-commode_pail-3').prop('checked',true);
    }
    else
    {
      $('.c-commode_pail-3').prop('checked',false);
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
    $('.patient_notes_count').mouseover(function(){
      var _element = $(this);
      var medical_id = $(this).attr('data-id');
      var patient_name = $(this).attr('data-patient-name');
      var notes_count = $('.patient_notes_count');

      $.post(base_url + 'order/count_patient_notes/' + medical_id, function(response){
        var obj = $.parseJSON(response);
        
        //notes_count.text(obj);

        if(obj == 1)
        {
          _element.prop('title', obj + ' note');
        }
        
        if(obj != 0 && obj > 1)
        {
          _element.prop('title', obj + ' notes');
        }
    
        else
        {
          _element.prop('title', obj + ' note');
        }
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
              setTimeout(function(){
                location.reload();
              },1000);
            }
          });
        }
      });
   });


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
  $('.datatable_table').on('click','.delete_patient', function(){
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
  var _id = $(this).closest("tr").find("td").find("select").attr("data-id");
  var _uniqueID = $(this).closest("tr").find("td").find("select").attr("data-unique-id");
  var _act_id = $(this).closest("tr").find("td").find("select").attr("data-act-id");
  var _hospice_id = $(this).closest("tr").find("td").find("select").attr("data-organization-id");
  var _equipment_id = $(this).closest("tr").find("td").find("select").attr("data-equipment-id");

  jConfirm("Change Order Status?","Warning", function(response){
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
        
        else
        {
          $.post(base_url + "order/change_order_status/" + _id + "/" + _value + "/" + _uniqueID, function(response){
            var obj2 = $.parseJSON(response);
            if(obj2['error'] == 0)
            {
              jAlert(obj2['message'],"Update Response");
              location.reload();
            }
          });
            
        }
      }
  });
}); 


$('.send_to_confirm_workorder').bind('click',function(){
  var medical_ids;
  var unique_ids;
  var status = "tobe_confirmed";

  if($(this).is(':checked'))
  {
    jConfirm("Send en route to Confirm WO#?","Reminder",function(response){
      if(response)
      {
        $('.hdn_send_to_confirm_workorder').each(function(){
            unique_ids = $(this).attr('data-enroute-unique-id');
            medical_ids = $(this).attr('data-enroute-id');
            
            $.post(base_url + "order/move_enroute_orders/" + medical_ids + "/" + status + "/" + unique_ids, function(response){
              var obj = $.parseJSON(response);
              if(obj['error'] == 0)
              {
                jAlert(obj['message'],"Update Response");
                location.reload();
              }
            });
        });

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


    jConfirm('Done Editing Information?', 'Reminder', function(response){
        if(response)
        {
          $.post(base_url + 'order/update_patient_profile/' + medical_id , form_data, function(response){
              var obj = $.parseJSON(response);
              jAlert(obj['message'],'Response');
              if(obj['error'] == 0)
              {
                 //location.reload();
                //window.location.href = base_url + "order/patient_profile/" + new_medical_id;
              }
          });
        }
    });
  });


  $('.save_note_btn').bind('click',function(){
    var medical_id = $(this).attr("data-id");
    var form_data = $('#insert_patient_notes').serialize();

    jConfirm('Save Patient Note Now?', 'Alert', function(response){
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
                //location.reload();
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
          //alert(response);
          var obj = $.parseJSON(response);
          jAlert(obj['message'], 'Response');
          if(obj['error'] == 0)
          {
            location.reload();
          }

        });
      
      }

    });
  });

  $(function(){
    var start_loop = 0;
    var checked_item = $('.checked_pickup_item:checked');
    var checked_item_length = $('.checked_pickup_item:checked').length;
    var checked_elem = $('.checked_pickup_item');
    var checked_item_uniqueID = $('.checked_pickup_item:checked').attr('data-work-order');

    $('.ptmove_checked_items_uniqueid').val(checked_item_uniqueID);

  });

  //IN ORDER TO COLLECT THE UNIQUE ID OF THE CHECKED ITEMS IN EXCHANGE
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
  //END of this function

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
  $('.checked_pickup_item').bind('click',function(){
    var _max_field = 20;
    var _wrapper = $('#hdn_pickup_unique_div');
    var unique_id = $(this).attr('data-uniqueID');
    var _start = 1;
    var equip_id = $(this).attr('data-equip-id');


    if($(this).is(":checked"))
    {
      $('.sub_equip_checkbox'+equip_id+unique_id).prop('checked','checked');

      if(_start < _max_field)
      {
        _start++;
        $(_wrapper).append("<input id='hdn_pickup_"+unique_id+"' type='hidden' name='hdn_pickup_uniqueID[]' value="+unique_id+" />");
      }
    }
    else
    {
      $('.sub_equip_checkbox'+equip_id+unique_id).prop('checked',false);
      $("#hdn_pickup_"+unique_id+"").remove();
    }
  });
  // END of this function


  // IN ORDER TO COLLECT THE UNIQUE ID OF THE CHECKED ITEMS IN PICK UP
  $('.checked_item').bind('click',function(){
    var _max_field = 20;
    var _wrapper = $('#hdn_original_act_id');
    var unique_id = $(this).attr('data-uniqueID');
    var _start = 1;
    var equip_id = $(this).attr('data-equip-id');
    var orig_act_id = $(this).attr('data-orig-act-id');

    if($(this).is(":checked"))
    {
      $('.sub_equip_checkbox_exchange'+equip_id+unique_id).prop('checked','checked');

      if(_start < _max_field)
      {
        _start++;
        $(_wrapper).append("<input id='hdn_orig_act_"+unique_id+"' type='hidden' name='hdn_orig_act_type' value="+orig_act_id+" />");
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

  var checked_item_orig_act_type = $('.checked_item').attr('data-orig-act-id');
  $('#hdn_original_act_id_pickup').append("<input id='checked_orig_item' type='hidden' name='hdn_orig_act_type_pickup' value="+checked_item_orig_act_type+" />"); //remove this line if it will cause errors
 

  $('.checked_item').bind('click',function(){
    var _max_field = 20;
    var _wrapper = $('#hdn_original_act_id_pickup');
    var unique_id = $(this).attr('data-uniqueID');
    var _start = 1;
    var equip_id = $(this).attr('data-equip-id');
    var orig_act_id = $(this).attr('data-orig-act-id');

    if($(this).is(":checked"))
    {
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
    }
  });
  // END of this function



  //for the confirmation part, to save the drivers name on the <td> input value
  $('.driver_name_to_save').bind('keyup',function(){
    _this = $(this).val();

    $('.name_of_driver').val(_this);

  });




  $('#account_type_dropdown').on('change',function(){
    if($(this).val() == 'hospice_admin' || $(this).val() == 'hospice_user')
    {
      $('#group_div').css('display','block');
    }
    else{
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
   //$('.person_mobile_num').mask("(999) 999-9999");

   $('.data_tooltip').tooltip();
   $('.comment-container').tooltip();
   $('.notes_help').tooltip();
   $('.patient_notes_count').tooltip();
   $('.datepicker').datepicker({
      dateFormat: 'yy-mm-dd'
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


  //generate random account numbers for hospice
  var min = 10000;
  var max = 99999;
  var num = Math.floor(Math.random() * (max - min + 1)) + min;
  
  $('.hospice_account_num').val(num);

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
          if($.inArray("Delivery",arr) > -1 || $.inArray("Exchange",arr) > -1 || $.inArray("PT Move",arr) > -1 || $.inArray("Respite",arr) > -1)
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



  // $('#equipment_summary_tbl tbody tr td[class=activity_type_column]').each(function(){
  //   var _this_activity = $(this).html();
  //     $('#equipment_summary_tbl tbody tr td[class=item_serial_number]').each(function(){
  //         var _this_serial = $(this).html();

  //         if(_this_serial != 'item_options_only' && _this_activity != 'Delivery')
  //         {
  //           $('#activity_type_btn').prop('disabled', true);
  //         }

  //     });
  // });

  
  //make all input text,email,select,textarea field a gray field with inner shadow
  $('input[type=text]').addClass("grey_inner_shadow");
  $('input[type=email]').addClass("grey_inner_shadow");
  $('select').addClass("grey_inner_shadow");
  $('textarea').addClass("grey_inner_shadow");
  $('.checkbox .i-checks i').addClass("grey_inner_shadow");
  $('.radio .i-checks i').addClass("grey_inner_shadow");

  //to get the local time of your pc
  // var show_after_hour_alert = function()
  // {
    // var dt = new Date();
    // var time = dt.getHours() + ":" + dt.getMinutes() + ":" + dt.getSeconds();
    // if(dt.getHours() == 17)
    // {
      // $('.after_hour_alert_content').show();
      // $('#after_hour_alert').modal("show");
    // }
  // };

  // if($(location).attr('href') != base_url)
  // {
    // if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
    // {
      // show_after_hour_alert();
    // }
  // }
  //end of function



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
