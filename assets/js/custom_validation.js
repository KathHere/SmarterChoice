var site = 'http://'+window.location.hostname+'/dme/';
 $(document).ready(function(){


 	// Custom Methods for Jquery Validation
 // 	$.validator.addMethod('filesize', function(value, element, param){
	// 	    return this.optional(element) || (element.files[0].size <= param) 
	// 	}, 
	// 	"Image limit exceeds."
	// );

			$("#register_form").validate({
	 		rules:
	 		{

	 			email:
	 			{
	 				required: true,
	 			},
	 			password:
	 			{
	 				required: true
	 			},
	 			firstname:
	 			{
	 				required: true,
	 			},
	 			middlename:
	 			{
	 				required: true,
	 				// number: true,
	 				// maxlength: 2,
	 			},
	 			lastname:
	 			{
	 				required: true,
	 				// number: true,
	 			},
	 			address:
	 			{
	 				required: true,
	 			},
	 			zip:
	 			{
	 				required: true,
	 				number: true,
	 			},
	 			country:
	 			{
	 				required: true,
	 			},
	 			phone:
	 			{
	 				required: true,
	 			},
	 			mobile:
	 			{
	 				required: true,
	 				number: true,
	 			},
				
				group_name:
	 			{
	 				required: true,
	 				
	 			},
	 		
	 			status:
	 			{
	 				required: true,
	 				
	 			},
	 			account_type:
	 			{
	 				required: true
	 			},
	 		

	 		},
	 			errorPlacement: function(error, element) {
				error.insertAfter(element);
				element.next('label').addClass('text-danger small-error');
				element.closest('div.form-group').addClass('has-error');
	 		}
 		});
	



			$("#admin_login_form").validate({
		 		rules:
		 		{

		 			email:
		 			{
		 				required: true,
		 				email: true
		 			},
		 			password:
		 			{
		 				required: true
		 			},
	 			},
		 			errorPlacement: function(error, element) {
					error.insertAfter(element);
					element.next('label').addClass('text-danger small-error');
					// element.closest('div.form-group').addClass('has-error');
	 			}
 			});


 			$("#user_login_form").validate({
		 		rules:
		 		{
		 		
		 			email:
		 			{
		 				required: true,
		 				email: true
		 			},
		 			password:
		 			{
		 				required: true
		 			},
	 			},
		 			errorPlacement: function(error, element) {
					error.insertAfter(element);
					element.next('label').addClass('text-danger small-error');
					// element.closest('div.form-group').addClass('has-error');
	 			}
 			});
			
			
			
			$("#hospice_create_form").validate({
		 		rules:
		 		{

		 			hospice_name:
		 			{
		 				required: true
		 			},
		 			
	 			},
		 			errorPlacement: function(error, element) {
					error.insertAfter(element);
					element.next('label').addClass('text-danger small-error');
					// element.closest('div.form-group').addClass('has-error');
	 			}
 			});

 			$("#company_create_form").validate({
		 		rules:
		 		{

		 			hospice_name:
		 			{
		 				required: true
		 			},
		 			
	 			},
		 			errorPlacement: function(error, element) {
					error.insertAfter(element);
					element.next('label').addClass('text-danger small-error');
					// element.closest('div.form-group').addClass('has-error');
	 			}
 			});
			
			
			// $("#order_form_validate").validate({
		 // 		rules:
		 // 		{

		 // 			// hospice_name:
		 // 			// {
		 // 				// required: true
		 // 			// },
			// 		delivery_date:
		 // 			{
		 // 				required: true
		 // 			},
			// 		// order_type:
		 // 			// {
		 // 				// required: true
		 // 			// },
			// 		date_today:
		 // 			{
		 // 				required: true
		 // 			},
			// 		// process_type:
		 // 			// {
		 // 				// required: true
		 // 			// },
			// 		patient_name:
		 // 			{
		 // 				required: true
		 // 			},
			// 		patient_mrn:
		 // 			{
		 // 				required: true,
			// 			number: true
		 // 			},
			// 		address:
		 // 			{
		 // 				required: true
		 // 			},
			// 		zipcode:
		 // 			{
		 // 				required: true,
			// 			number: true
		 // 			},
			// 		phone_number:
		 // 			{
		 // 				required: true,
			// 			number: true
		 // 			},
			// 		alt_phone_num:
		 // 			{
		 // 				required: true,
			// 			number: true
		 // 			},
			// 		faculty_name:
		 // 			{
		 // 				required: true
		 // 			},
			// 		room_number:
		 // 			{
		 // 				required: true,
			// 			number: true
		 // 			},
			// 		comments:
		 // 			{
		 // 				required: true
		 // 			},
			// 		nurse_name:
		 // 			{
		 // 				required: true
		 // 			},
			// 		person_placing_order:
		 // 			{
		 // 				required: true
		 // 			},
			// 		delivery_date:
		 // 			{
		 // 				required: true
		 // 			},
					
		 			
	 	// 		},
		 // 			errorPlacement: function(error, element) {
			// 		error.insertAfter(element);
			// 		element.next('label').addClass('text-danger small-error');
			// 		element.closest('div.form-group').addClass('has-error');
	 	// 		}
 		// 	});
			
			
			
			

	//for masking
    //for formatting phone numbers
	$.mask.definitions['9'] = '';
	$.mask.definitions['n'] = '[0-9]';
	$("input.phone").mask("nn-nnnn-nnn");
	$("input.telephone").mask("(nnn) nnnn-nnn");	

	

 }); //end of ready function
