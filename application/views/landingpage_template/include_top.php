<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	
	<title><?php echo !empty($title) ? $title : 'Advantage Home Medical Services Inc.'; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css') ;?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap-theme.css') ;?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/bootstrap/css/bootstrap.css') ;?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/non-responsive.css') ;?>">
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.min.css') ;?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.theme.min.css') ;?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.structure.min.css') ;?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery.dataTables.min.css') ;?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/js/global.modal/global.modal.css') ;?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/js/jquery-alerts/jquery.alerts.css') ;?>">
	
<!-- magic Slider -->
 <link href="<?php echo base_url('assets/css/magic_carousel.css') ;?>" rel="stylesheet" type="text/css">
 <link rel="stylesheet" href="<?php echo base_url('assets/css/prettyPhoto.css') ;?>" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />


<!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places"></script> -->
	

	<style type="text/css">
	
	    /* Excluded parts for printing */
		@media print
		{
			.noprint{
				display:none;
				margin: 25mm 25mm 25mm 25mm;  
			}
		}
		@page
		{
			margin: 0;  
		}

		input{
			box-shadow: none!important;
			border-radius:0px !important;
			background-color: #f9f9f9 !important;
			/*border:	1px solid rgba(234, 234, 234, 1) !important;*/
		}

		textarea{
			box-shadow: none!important;
			border-radius:0px !important;
			background-color: #f9f9f9 !important;
			/*border:	1px solid rgba(234, 234, 234, 1) !important;*/
		}

		select{
			box-shadow: none!important;
			border-radius:0px !important;
			background-color: #f9f9f9 !important;
			/*border:	1px solid rgba(234, 234, 234, 1) !important;*/
		}

		.form-control{
			box-shadow: none!important;
			border-radius:0px !important;
			background-color: #f9f9f9 !important;
			/*border:	1px solid rgba(234, 234, 234, 1) !important;*/
		}

		#wrapper, #prev, #next {
				border-top: 1px solid #999;
				border-bottom: 1px solid #999;
				height: 170px;
				position: absolute;
				top: 25%;
				margin-top: 55px !important;
		}
		#wrapper {
			width: 90%;
			left: 5%;
			overflow: hidden;
			box-shadow: 0 0 10px #ccc;
		}

		#carousels img {
			margin: 10px 5px;
			border: 1px solid #D6D5D5;
			display: block;
			float: left;
		}
		
		#prev, #next {
			background: center center no-repeat #ccc;
			width: 3%;
		}
		#prev:hover, #next:hover {
			background-color: #bbb;
		}
		#prev {
			background-image: url( ../assets/carousel_img/gui-prev.png );
			left: 0;
		}
		#next {
			background-image: url( ../assets/carousel_img/gui-next.png );
			right: 0;
		}
		
		#donate-spacer {
			height: 100%;
			margin-bottom: 0px;
		}
		#donate {
			border-top: 0px solid #999;
			width: 750px;
			padding: 0px 0px;
			margin: 0 auto;
			overflow: hidden;
		}
		
		/*.error
		{
			border: 1px solid red !important;
		}
		*/
		
		
	
		.dropdown-menu > li > a:focus {
			  color: #262626;
			  text-decoration: none;
			  background-color: #333F5E !important;
		}

	</style>
	<script type="text/javascript">
		var base_url = "<?php echo base_url() ?>";

		// var placeSearch, autocomplete;
		// var componentForm = {
		//   street_number: 'short_name',
		//   locality: 'long_name',
		//   administrative_area_level_1: 'short_name',
		//   country: 'long_name',
		//   postal_code: 'short_name'
		// };

		// function initialize() {
		//   // Create the autocomplete object, restricting the search
		//   // to geographical location types.
		//   autocomplete = new google.maps.places.Autocomplete(
		//       /** @type {HTMLInputElement} */(document.getElementById('p_add')),
		//       { types: ['geocode'] });
		//   // When the user selects an address from the dropdown,
		//   // populate the address fields in the form.
		//   google.maps.event.addListener(autocomplete, 'place_changed', function() {
		//     fillInAddress();
		//   });
		// }

		// // [START region_fillform]
		// function fillInAddress() {
		//   // Get the place details from the autocomplete object.
		//   var place = autocomplete.getPlace();
		//    //console.log(place);
		//   for (var component in componentForm) {
		//     document.getElementById(component).value = '';
		//     document.getElementById(component).disabled = false;

		//   }

		//   // Get each component of the address from the place details
		//   // and fill the corresponding field on the form.
		//   for (var i = 0; i < place.address_components.length; i++) {
		//     var addressType = place.address_components[i].types[0];
		//     if (componentForm[addressType]) {
		//       var val = place.address_components[i][componentForm[addressType]];
		//       document.getElementById(addressType).value = val;
		    
		//     }
		//   }
		// }
		// // [END region_fillform]

		// // [START region_geolocation]
		// // Bias the autocomplete object to the user's geographical location,
		// // as supplied by the browser's 'navigator.geolocation' object.
		// function geolocate() {
		//   if (navigator.geolocation) {
		//     navigator.geolocation.getCurrentPosition(function(position) {
		//       var geolocation = new google.maps.LatLng(
		//           position.coords.latitude, position.coords.longitude);
		//       autocomplete.setBounds(new google.maps.LatLngBounds(geolocation,
		//           geolocation));
		//     });
		//   }
		// }


		// [END region_geolocation]
	</script>
	
</head>
<body>


<!-- Global Modal -->
    <div class="modal fade" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"></h4>
          </div>
          <div class="modal-body"></div>
          <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
<!-- #m-container -->