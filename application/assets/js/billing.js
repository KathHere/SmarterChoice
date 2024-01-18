$(document).ready(function(){

	$('#search-account-statements').bind('keyup',function(){
	    var searchString = $(this).val();
		var form_data = $('#search_form').serialize();
	    $('#pfname').val($(this).val().toLowerCase());
	    $('#plname').val($(this).val().toLowerCase());
	    if(searchString.length >= 2){
	      $('#suggestion_container').html("<div style='text-align:center; padding-top:5px;margin-bottom:25px;font-size:17px !important; height: 50px !important; background-color: #fff !important;'><h4>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></h4></div>");
	      if($('#search-account-statements').val() !== "" || $('#search-account-statements').val() !== null)
	      {
	        // console.log('gwapos', form_data);
	        $.post(base_url+"billing/search_accounts/", form_data, function(response){
	        	$('#suggestion_container').show();
	            $('#suggestion_container').html(response);
	            // console.log('suggestion_container', response);
	            $(".account_results").bind('click', function(){
                  var data_hospice_id = $(this).attr('data-hospice-id');

                  $('#hospice_id').val(data_hospice_id);
                  $('#suggestion_container').hide();
                  $('#search_form').submit();
                });
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
	});
});