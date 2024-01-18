$(document).ready(function(){

	$('.service_location_selection').bind('change',function(){
	    var location_id = $(this).val();

	    $.post(base_url+"service_location/select_service_location/"+location_id, '', function(response){
	        location.reload();
	    });
  	});

});
