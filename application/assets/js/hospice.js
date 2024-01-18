//Billing Address
var geocoder = new google.maps.Geocoder();
$("#b_postal").bind("keyup", function() {
  var $this = $(this);
  if ($this.val().length == 5) {
    geocoder.geocode({ address: $this.val() }, function(result, status) {
      var state = "N/A";
      var city = "N/A";
      //start loop to get state from zip
      for (var component in result[0]["address_components"]) {
        for (var i in result[0]["address_components"][component]["types"]) {
          if (
            result[0]["address_components"][component]["types"][i] ==
            "administrative_area_level_1"
          ) {
            //alert(result[0]['address_components'][1]['long_name']);
            state = result[0]["address_components"][component]["short_name"];
            // do stuff with the state here!
            $("#b_state").val(state);
            // get city name
            city = result[0]["address_components"][1]["long_name"];
            // Insert city name into some input box
            $("#b_city").val(city);
          }
        }
      }
    });
  }

  if ($this.val() == "") {
    $("#b_state").val("");
    $("#b_city").val("");
  }
});

//Shipping Addres
$("#s_postal").bind("keyup", function() {
  var $this = $(this);
  console.log("this: ", $this);
  if ($this.val().length == 5) {
    geocoder.geocode({ address: $this.val() }, function(result, status) {
      var state = "N/A";
      var city = "N/A";
      //start loop to get state from zip
      for (var component in result[0]["address_components"]) {
        for (var i in result[0]["address_components"][component]["types"]) {
          if (
            result[0]["address_components"][component]["types"][i] ==
            "administrative_area_level_1"
          ) {
            //alert(result[0]['address_components'][1]['long_name']);
            state = result[0]["address_components"][component]["short_name"];
            // do stuff with the state here!
            $("#s_state").val(state);
            // get city name
            city = result[0]["address_components"][1]["long_name"];
            // Insert city name into some input box
            $("#s_city").val(city);
          }
        }
      }
    });
  }

  if ($this.val() == "") {
    $("#s_state").val("");
    $("#s_city").val("");
  }
});
