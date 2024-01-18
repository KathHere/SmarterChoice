$(document).ready(function(){

    var route_name = '';
    var route_id = 0;

    $(".daily_routes_route_name").focusout(function(){
        var _this = $(this);
        route_name = _this.val();
        route_id = _this.attr('route-id');

        if (route_name == '' || route_name.length == 0 || route_name == null) {
            jAlert('Add Route Name', 'Error');
        } else {
            var post_url = $('#daily_routes_form').attr('action');
            $.post(post_url+"/" + route_id +"/"+ route_name,"", function(response){
                var obj = $.parseJSON(response);
                if(obj['error'] == 0)
                {
                    jAlert(obj['message'], 'Success');
                } else {
                    jAlert(obj['message'], 'Error');
                }
            });
        }
    });

});
