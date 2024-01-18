$(function(){

	//Recurring
	var updateRecurringOrderSummary = function(){
		var category = new Array();
		var category_temp = new Array();
		var category_val = new Array();
		$(".wrapper-recurring-equipment").each(function(){
			var catey = $(this).attr("data-value");
			var iterator = 0;
			category_val[catey] = [];
			$(this).find(".recurring_item").each(function(i){

				if($(this).is(':checked'))
				{
					var cat_ = $(this).attr("data-category-id");
					var cat_name  = $(this).attr("data-category");
					var sub_name  = $(this).attr("data-name");
					var sub_desc  = $(this).attr("data-desc");
					var categories = {
						"category_id" 	: cat_,
						"category_name"	: cat_name
					};
					var val_ = $(this).attr("data-value");
					if(inArray(cat_,category_temp)===false)
					{
						category.push(categories);
						category_temp.push(cat_);
					}
					var valval = {
						"key_name":sub_name,
						"value":val_,
						"children":[]
					};

					$('.recur_items').each(function(){
						if($(this).hasClass('modal-'+sub_name+'-'+cat_))
						{
							var subval_desc = "";
							var subval_value = "";
							subval_value = $(this).val();
							subval_desc = $(this).attr("data-desc");
							var subval_text = {
								"subval_desc"   : subval_desc,
								"subval_value"  : subval_value
							};

							valval['children'].push(subval_text);
							return false;
						}
					});
					category_val[cat_][iterator] = valval;

					iterator++;
					console.log(valval);
				}
			});
		});
		$(".recurring-order-cont").html("");
		if(category.length>0)
		{
			var category_html = "";
			for(var i=0;i<category.length;i++)
			{
				var ct_id = category[i]['category_id'];
				var category_values = "";
				for(var j=0;j<category_val[ct_id].length;j++)
				{
					var subvalues_ = "";
					for(x=0;x<category_val[ct_id][j]['children'].length;x++)
					{
						subvalues_ += '<li>'+
							'<label>'+category_val[ct_id][j]['children'][x]['subval_desc']+': &nbsp;</label>'+
							'<span>'+category_val[ct_id][j]['children'][x]['subval_value']+'</span>'+
							'</li>';
					}
					category_values += '<li>'+
						'<p class="selected-item">'+category_val[ct_id][j]['value']+'</p>'+
						'<ul class="OpenSans-Reg selected-item-list sub_'+category_val[ct_id][j]['key_name']+"_"+ct_id+'" style="list-style-type:none;margin-left: -20px;">'+
						subvalues_+
						'</ul>'+
						'</li>';
				}

				//

				category_html += '<div class="col-md-12" style="padding-left:0px;">'+
									'<label>'+category[i]['category_name']+'</label>'+
									'<ol class="OpenSans-Reg" style=";margin-left: -20px;">';
				category_html += category_values;
				category_html += "</ol></div><div class='clearfix'></div><hr>";

			}
			$(".recurring-order-cont").html(category_html);
		}
	};

	$('.recur_sched_week').on('click',function(){

		updateRecurringOrderSummary();
	});

	counter = 0;
	$('.recurring_item').on('click',function(){
		var data_noncapped_reference = $(this).attr("data-reference-id");
		var _this = $(this);
		var selected_activity_type_val = $('#selected_activity_type').val();
		var capped_item_modal = $(this).attr('data-target');
		if(capped_item_modal == "#geri_chair_1")
		{
			var capped_item_modal_noncapped = "#geri_chair_3_position_with_tray_2";
		}
		else
		{
			var capped_item_modal_noncapped = $(this).attr('data-target').replace("1","2");
		}
		$('.modal').attr("data-backdrop",'static'); //disabled closing of modal when you click it outside
		$('.modal').attr("data-keyboard",'false'); //disabled closing of modal when you click escape
		if($(this).is(':checked'))
		{
			counter++;
			var target = $(this).attr("data-target");
			if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
			{
				$(target).modal('show');
			}

			//added to get the value of the checked item for duplicate capped item checking
			var ids = [];
			var this_val = $(this).val();

			ids.push(this_val);
			$('#selected').val(ids);

			if(selected_activity_type_val == 1 || selected_activity_type_val == 5)
			{
				check_capped_items();
				if(check_capped_items())
				{
					if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
					{
						$(capped_item_modal).modal("hide");
					}
					$("body").css("overflow-y","scroll");
				}
			}

			new_counter = 0;
			$('.btn-close-alert').on('click',function(){
				new_counter++;
				if(new_counter == counter)
				{
					_this.prop('checked', false);
					updateRecurringOrderSummary();
				}
			});
			//new_counter_here = 0;
			// $('.btn-approve-choice').on('click',function(){
			// 	new_counter_here++;
			// 	if(new_counter_here == counter)
			// 	{
			// 		_this.prop('checked', false);
			// 		$("input[type=checkbox][value="+data_noncapped_reference+"]").prop("checked",true);
			// 		if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
			// 		{
			// 			$(capped_item_modal_noncapped).modal("show");
			// 		}
			// 	}
			// 	$("body").css("overflow-y","scroll");

			// 	updateRecurringOrderSummary();
			// });
		} else {
			var cat_ = $(this).attr("data-category-id");
			var cat_name = $(this).attr("data-category");
			var sub_name = $(this).attr("data-name");
			var sub_desc = $(this).attr("data-desc");
			$('.recur_items').each(function(){
				if($(this).hasClass('modal-'+sub_name+'-'+cat_))
				{
					$(this).remove();
					return false;
				}
			});
		}

		updateRecurringOrderSummary();
	});

	var updateOrderSummary = function(){
		var category = new Array();
		var category_temp = new Array();
		var category_val = new Array();
		$(".wrapper-equipment").each(function(){
			var catey = $(this).attr("data-value");
			var iterator = 0;
			category_val[catey] = [];
			$(this).find(".checkboxes").each(function(i){

				if($(this).is(':checked'))
				{
					var cat_ = $(this).attr("data-category-id");
					var cat_name  = $(this).attr("data-category");
                    var sub_name    = $(this).attr("data-name");
                    var sub_desc    = $(this).attr("data-desc");
					var categories = {
										"category_id" 	: cat_,
										"category_name"	: cat_name
									};
					var val_ = $(this).attr("data-value");
					if(inArray(cat_,category_temp)===false)
					{
						category.push(categories);
						category_temp.push(cat_);
					}
					var valval = {
						"key_name":sub_name,
						"value":val_,
						"children":[]
					};

					$('.modal').each(function(){
						if($('.modal').hasClass('modal_'+sub_name+'_'+cat_))
						{
							$('.modal_'+sub_name+'_'+cat_).children(".modal-dialog")
								.children(".modal-content")
								.children('.modal-body')
								.find('INPUT').each(function(){
									var subval_desc = "";
									var subval_value = "";
									var subval_oxy = "";
									var attrtype = $(this).attr("type");
									subval_desc = $(this).attr("data-desc") ;
									if(attrtype==="text")
									{

										if($(this).val()!=="")
										{
											if(sub_name==="oxygen_concentrator")
											{
												subval_value     = $(this).val()+" LPM";
											}
											else
											{
												subval_value     = $(this).val();
											}
										}
										if($(this).attr('data-desc') == "Liter Flow disp") {
											subval_value = ""
										}
									}
									else if(attrtype==="checkbox")
									{
										if($(this).is(':checked'))
										{
											var data = $(this).attr("data-value");
											//this is to separate the two oxygen concentrator in the cart
											if(data == '5 LPM' || data == '10 LPM')
											{
												subval_value = $(this).attr("data-value");
											}
											else
											{
												subval_value = $(this).attr("data-value");
											}
										}
									}
									else
									{
										if($(this).is(':checked'))
										{
											subval_value = $(this).attr("data-value");
										}
									}
									if(subval_value!=="")
									{
										var subval_text = {
											"subval_desc"   : subval_desc,
											"subval_value"  : subval_value
										};
										valval['children'].push(subval_text);
									}
							});
							return false;
						}
					});
					category_val[cat_][iterator] = valval;
					iterator++;
				}
			});
		});
		$(".order-cont").html("");
		if(category.length>0)
		{
			var category_html = "";
			for(var i=0;i<category.length;i++)
			{
				var ct_id = category[i]['category_id'];
				var category_values = "";
				for(var j=0;j<category_val[ct_id].length;j++)
				{
					var subvalues_ = "";
					var suboxygen_5 = "";
					var suboxygen_10 = "";
					for(x=0;x<category_val[ct_id][j]['children'].length;x++)
					{
						if(category_val[ct_id][j]['children'][x]['subval_value'] == '5 LPM')
						{
							suboxygen_5 += '<li>'+
															'<label>'+category_val[ct_id][j]['children'][x]['subval_desc']+': &nbsp;</label>'+
															'<span>'+category_val[ct_id][j]['children'][x]['subval_value']+'</span>'+
														'</li>';
						}
						else if(category_val[ct_id][j]['children'][x]['subval_value'] == '10 LPM')
						{
							suboxygen_10 += '<li>'+
															'<label>'+category_val[ct_id][j]['children'][x]['subval_desc']+': &nbsp;</label>'+
															'<span>'+category_val[ct_id][j]['children'][x]['subval_value']+'</span>'+
														'</li>';
						}
						else
						{
							if(category_val[ct_id][j]['children'][x]['subval_desc'] == "Item Description")
							{
								if(category_val[ct_id][j]['children'][x]['subval_value'].length > 21)
								{
									subvalues_ += '<li>'+
																		'<label>'+category_val[ct_id][j]['children'][x]['subval_desc']+': &nbsp;</label>'+
																		'<span>'+category_val[ct_id][j]['children'][x]['subval_value'].substr(0,20)+'...</span>'+
																	'</li>';
								}
								else
								{
									subvalues_ += '<li>'+
																		'<label>'+category_val[ct_id][j]['children'][x]['subval_desc']+': &nbsp;</label>'+
																		'<span>'+category_val[ct_id][j]['children'][x]['subval_value']+'</span>'+
																	'</li>';
								}
							}
							else if(category_val[ct_id][j]['children'][x]['subval_desc'] == "Item Desc.")
							{
								if(category_val[ct_id][j]['children'][x]['subval_value'].length > 21)
								{
									subvalues_ += '<li>'+
																		'<label>'+category_val[ct_id][j]['children'][x]['subval_desc']+': &nbsp;</label>'+
																		'<span>'+category_val[ct_id][j]['children'][x]['subval_value'].substr(0,20)+'...</span>'+
																	'</li>';
								}
								else
								{
									subvalues_ += '<li>'+
																		'<label>'+category_val[ct_id][j]['children'][x]['subval_desc']+': &nbsp;</label>'+
																		'<span>'+category_val[ct_id][j]['children'][x]['subval_value']+'</span>'+
																	'</li>';
								}
							}
							else
							{
								subvalues_ += '<li>'+
																	'<label>'+category_val[ct_id][j]['children'][x]['subval_desc']+': &nbsp;</label>'+
																	'<span>'+category_val[ct_id][j]['children'][x]['subval_value']+'</span>'+
																'</li>';
							}
						}
					}
					if(suboxygen_5 !== "" || suboxygen_10 !== "")
					{
						if(suboxygen_5 !== "")
						{
							category_values += '<li>'+
	                        '<p class="selected-item">'+category_val[ct_id][j]['value']+'</p>'+
	                        '<ul class="OpenSans-Reg selected-item-list sub_'+category_val[ct_id][j]['key_name']+"_"+ct_id+'" style="list-style-type:none;margin-left: -20px;">'+
	                        suboxygen_5+''+subvalues_+
	                        '</ul>'+
	               			 '</li>';
						}
						if(suboxygen_10 !== "")
						{
							category_values += '<li>'+
	                        '<p class="selected-item">'+category_val[ct_id][j]['value']+'</p>'+
	                        '<ul class="OpenSans-Reg selected-item-list sub_'+category_val[ct_id][j]['key_name']+"_"+ct_id+'" style="list-style-type:none;margin-left: -20px;">'+
	                        suboxygen_10+''+subvalues_+
	                        '</ul>'+
	               			 '</li>';
						}
					}else{
						if(category_val[ct_id][j]['key_name'] == "hospital_bed")
						{
							category_values += '<li>'+
									'<p class="selected-item"> '+category_val[ct_id][j]['value']+'</p>'+
									'<ul class="OpenSans-Reg selected-item-list sub_'+category_val[ct_id][j]['key_name']+"_"+ct_id+'" style="list-style-type:none;margin-left: -20px;">'+
									subvalues_+
									'</ul>'+
								'</li>';
						}
						else if(category_val[ct_id][j]['key_name'] == "commode_pail")
						{
							var commode_pail_count = $("body").find(".commode_pail_counter").val();

							if(commode_pail_count > 1)
							{
								for (var r = 1; r <= commode_pail_count; r++) {
									category_values += '<li>'+
												'<p class="selected-item">'+category_val[ct_id][j]['value']+'</p>'+
												'<ul class="OpenSans-Reg selected-item-list sub_'+category_val[ct_id][j]['key_name']+"_"+ct_id+'" style="list-style-type:none;margin-left: -20px;">'+
												subvalues_+
												'</ul>'+
										'</li>';
								}
							}
							else
							{
								category_values += '<li>'+
											'<p class="selected-item">'+category_val[ct_id][j]['value']+'</p>'+
											'<ul class="OpenSans-Reg selected-item-list sub_'+category_val[ct_id][j]['key_name']+"_"+ct_id+'" style="list-style-type:none;margin-left: -20px;">'+
											subvalues_+
											'</ul>'+
									'</li>';
							}
						}
						else if (category_val[ct_id][j]['key_name'] == "floor_mat" && ct_id == 2)
						{
							var floor_mat_count = $('body').find('.floor_mat_noncapped_quantity').val();

							for (var r = 1; r <= floor_mat_count; r++) {
								category_values += '<li>'+
										'<p class="selected-item">'+category_val[ct_id][j]['value']+'</p>'+
										'<ul class="OpenSans-Reg selected-item-list sub_'+category_val[ct_id][j]['key_name']+"_"+ct_id+'" style="list-style-type:none;margin-left: -20px;">'+
										subvalues_+
										'</ul>'+
									'</li>';
							}
						}
						else if ((category_val[ct_id][j]['key_name'] == "hospital_bed_rail_bumper_full_rail" && ct_id == 2) || (category_val[ct_id][j]['key_name'] == "hospital_bed_rail_bumper_half_rail" && ct_id == 2))
						{
							var hospital_bed_rail_bumper_full_rail_count = $('body').find('.hospital_bed_rail_bumpers_full_noncapped_quantity').val();
							var hospital_bed_rail_bumper_half_rail_count = $('body').find('.hospital_bed_rail_bumpers_half_noncapped_quantity').val();

							if (category_val[ct_id][j]['key_name'] == 'hospital_bed_rail_bumper_full_rail') {
								if (hospital_bed_rail_bumper_full_rail_count > 0) {
									for (var r = 1; r <= hospital_bed_rail_bumper_full_rail_count; r++) {
										category_values += '<li>'+
												'<p class="selected-item">'+category_val[ct_id][j]['value']+'</p>'+
												'<ul class="OpenSans-Reg selected-item-list sub_'+category_val[ct_id][j]['key_name']+"_"+ct_id+'" style="list-style-type:none;margin-left: -20px;">'+
												subvalues_+
												'</ul>'+
											'</li>';
									}
								}
							}
							if (category_val[ct_id][j]['key_name'] == 'hospital_bed_rail_bumper_half_rail') {
								if (hospital_bed_rail_bumper_half_rail_count > 0) {
									for (var r = 1; r <= hospital_bed_rail_bumper_half_rail_count; r++) {
										category_values += '<li>'+
												'<p class="selected-item">'+category_val[ct_id][j]['value']+'</p>'+
												'<ul class="OpenSans-Reg selected-item-list sub_'+category_val[ct_id][j]['key_name']+"_"+ct_id+'" style="list-style-type:none;margin-left: -20px;">'+
												subvalues_+
												'</ul>'+
											'</li>';
									}
								}
							}
						}
						else
						{
							category_values += '<li>'+
									'<p class="selected-item">'+category_val[ct_id][j]['value']+'</p>'+
									'<ul class="OpenSans-Reg selected-item-list sub_'+category_val[ct_id][j]['key_name']+"_"+ct_id+'" style="list-style-type:none;margin-left: -20px;">'+
									subvalues_+
									'</ul>'+
							'</li>';
						}
					}
				}

				category_html += '<div class="col-md-12" style="padding-left:0px;">'+
									'<label>'+category[i]['category_name']+'</label>'+
									'<ol class="OpenSans-Reg" style=";margin-left: -20px;">';
				category_html += category_values;
				category_html += "</ol></div><div class='clearfix'></div><hr>";
			}
			$(".order-cont").html(category_html);
		}
	};

	counter = 0;
	$('.checkboxes').on('click',function(){
		var _this = $(this);

		var category_id = _this.attr("data-category-id");
		var equipment_id = _this.val();
		var patient_id = $('body').find('.save_additional_btn_ptmove').attr('data-id');
		var customer_hospice_id = $('body').find('.customer_hospice_id').val();

		var data_noncapped_reference = _this.attr("data-reference-id");
		var selected_activity_type_val = $('#selected_activity_type').val();
		var capped_item_modal = $(this).attr('data-target');
		var cusmoveradiobutton = $('body').find('#radio_pickup4').is(':checked');
		console.log('cusmoveradiobutton', cusmoveradiobutton);

		if(capped_item_modal == "#geri_chair_1")
		{
			var capped_item_modal_noncapped = "#geri_chair_3_position_with_tray_2";
		}
		else
		{
			var capped_item_modal_noncapped = $(this).attr('data-target').replace("1","2");
		}
		$('.modal').attr("data-backdrop",'static'); //disabled closing of modal when you click it outside
		$('.modal').attr("data-keyboard",'false'); //disabled closing of modal when you click escape
		if($(this).is(':checked'))
		{
			counter++;

			if (category_id == 2) {

				$.get(base_url+'equipment/get_customer_capped_item/'+patient_id+'/'+equipment_id+'/'+customer_hospice_id,function(response){
					var parsed_result = JSON.parse(response);
					console.log('parsed_result');
					if (parsed_result.customer_ordered_capped_item.length == 0 && parsed_result.hospice_capped_item.length == undefined) {

						if (cusmoveradiobutton) {
							const temp_capped_item_value = $('body').find('.equipment_section').find('.c-'+ parsed_result.hospice_capped_item.key_name +'-1').is(":checked");
							console.log('VALUE ++', temp_capped_item_value);

							if (!temp_capped_item_value) {
								$('#capped_suggestion_modal').modal({
									backdrop: 'static',
									keyboard: false
								});
								$('#capped_suggestion_modal').modal("show");

								new_counter = 0;
								$('.btn-close-duplicate-capped-modal').on('click',function(){
									new_counter++;
									console.log('new_counter====', new_counter);
									console.log('counter====', counter);
									console.log('counter final====', $('#selected').val().length);

									if(new_counter == counter)
									{
										console.log('_this===', _this);
										_this.prop('checked', false);
										updateOrderSummary();
									}
								});
								updateOrderSummary();

							} else {

								var target = _this.attr("data-target");
								if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
								{
									$(target).modal('show');
								}

								//added to get the value of the checked item for duplicate capped item checking
								var ids = [];
								var this_val = _this.val();

								ids.push(this_val);
								$('#selected').val(ids);

								if(selected_activity_type_val == 1 || selected_activity_type_val == 5)
								{
									check_capped_items();
									if(check_capped_items())
									{
										if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
										{
											$(capped_item_modal).modal("hide");
										}
										$("body").css("overflow-y","scroll");
									}
								}

								new_counter = 0;
								$('.btn-close-alert').on('click',function(){
									new_counter++;
									if(new_counter == counter)
									{
										_this.prop('checked', false);
										updateOrderSummary();
									}
								});
								new_counter_here = 0;
								$('.btn-approve-choice').on('click',function(){
									new_counter_here++;
									if(new_counter_here == counter)
									{
										_this.prop('checked', false);
										$("input[type=checkbox][value="+data_noncapped_reference+"]").prop("checked",true);
										if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
										{
											$(capped_item_modal_noncapped).modal("show");
										}
									}
									$("body").css("overflow-y","scroll");

									updateOrderSummary();
								});
								updateOrderSummary();

							}
						} else {
							var target = _this.attr("data-target");
							if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
							{
								$(target).modal('show');
							}

							//added to get the value of the checked item for duplicate capped item checking
							var ids = [];
							var this_val = _this.val();

							ids.push(this_val);
							$('#selected').val(ids);

							if(selected_activity_type_val == 1 || selected_activity_type_val == 5)
							{
								check_capped_items();
								if(check_capped_items())
								{
									if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
									{
										$(capped_item_modal).modal("hide");
									}
									$("body").css("overflow-y","scroll");
								}
							}

							new_counter = 0;
							$('.btn-close-alert').on('click',function(){
								new_counter++;
								if(new_counter == counter)
								{
									_this.prop('checked', false);
									updateOrderSummary();
								}
							});
							new_counter_here = 0;
							$('.btn-approve-choice').on('click',function(){
								new_counter_here++;
								if(new_counter_here == counter)
								{
									_this.prop('checked', false);
									$("input[type=checkbox][value="+data_noncapped_reference+"]").prop("checked",true);
									if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
									{
										$(capped_item_modal_noncapped).modal("show");
									}
								}
								$("body").css("overflow-y","scroll");

								updateOrderSummary();
							});
							updateOrderSummary();
						}

					} else {
						var target = _this.attr("data-target");
						if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
						{
							$(target).modal('show');
						}

						//added to get the value of the checked item for duplicate capped item checking
						var ids = [];
						var this_val = _this.val();

						ids.push(this_val);
						$('#selected').val(ids);

						if(selected_activity_type_val == 1 || selected_activity_type_val == 5)
						{
							check_capped_items();
							if(check_capped_items())
							{
								if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
								{
									$(capped_item_modal).modal("hide");
								}
								$("body").css("overflow-y","scroll");
							}
						}

						new_counter = 0;
						$('.btn-close-alert').on('click',function(){
							new_counter++;
							if(new_counter == counter)
							{
								_this.prop('checked', false);
								updateOrderSummary();
							}
						});
						new_counter_here = 0;
						$('.btn-approve-choice').on('click',function(){
							new_counter_here++;
							if(new_counter_here == counter)
							{
								_this.prop('checked', false);
								$("input[type=checkbox][value="+data_noncapped_reference+"]").prop("checked",true);
								if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
								{
									$(capped_item_modal_noncapped).modal("show");
								}
							}
							$("body").css("overflow-y","scroll");

							updateOrderSummary();
						});
						updateOrderSummary();
					}
				});

			} else {

				var target = $(this).attr("data-target");
				if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
				{
					$(target).modal('show');
				}

				//added to get the value of the checked item for duplicate capped item checking
				var ids = [];
				var this_val = $(this).val();

				ids.push(this_val);
				$('#selected').val(ids);

				if(selected_activity_type_val == 1 || selected_activity_type_val == 5)
				{
					check_capped_items();
					if(check_capped_items())
					{
						if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
						{
							$(capped_item_modal).modal("hide");
						}
						$("body").css("overflow-y","scroll");
					}
				}

				new_counter = 0;
				$('.btn-close-alert').on('click',function(){
					new_counter++;
					if(new_counter == counter)
					{
						_this.prop('checked', false);
						updateOrderSummary();
					}
				});
				new_counter_here = 0;
				$('.btn-approve-choice').on('click',function(){
					new_counter_here++;
					if(new_counter_here == counter)
					{
						_this.prop('checked', false);
						$("input[type=checkbox][value="+data_noncapped_reference+"]").prop("checked",true);
						if(target != "#power_scooter_w/battery_charger_1" && target != "#power_scooter_w/battery_charger_2")
						{
							$(capped_item_modal_noncapped).modal("show");
						}
					}
					$("body").css("overflow-y","scroll");

					updateOrderSummary();
				});
			}
		}
		updateOrderSummary();
	});
	// $('.btn-approve-choice').on('click',function(){
	// 	_this.prop('checked', false);
	// 	$("input[type=checkbox][value="+data_noncapped_reference+"]").prop("checked",true);
	// 	$(capped_item_modal_noncapped).modal("show");
	// 	$("body").css("overflow-y","scroll");
	// 	updateOrderSummary();
	// });

	var inArray = function (needle, haystack) {
	    var length = haystack.length;
	    for(var i = 0; i < length; i++) {
	        if(haystack[i] == needle) return true;
	    }
	    return false;
	}
	updateOrderSummary();

	var check_capped_items = function()
	{
		var checked_item_capped = $('#selected').val();
		var _checker = false;

		if(checked_item_capped != 313)
		{
			if(checked_item_capped == 61)
			{

			}
			else
			{
				var vals = $('.capped_items').map(function(){
					if($(this).val() === checked_item_capped)
					{
						$('#duplicate_capped_modal').modal({
					      backdrop: 'static',
					      keyboard: false
					    });
						$('#duplicate_capped_modal').modal("show");
						_checker = true;
					}
				}).get();
				return _checker;
			}
		}
	}

	var check_checked_activity_type = function()
	{
		var checked_act_type = $('#selected_activity_type');

		$('input[name=activity_type]').bind('click',function(){
			if($(this).is(':checked'))
			{
				checked_act_type.val($(this).val());
				if(checked_act_type === 1)
				{
					return checked_act_type.val();
				}
			}
		});
	}
	check_checked_activity_type();

	$('.oxygen_supply_kit_device').on('click',function(){
		var totalLiterFlow = $('.total_liter_flow').val();
  		if(totalLiterFlow > 15) {
  			$('.oxygen_supply_kit_device_2').click();
  		}
	});

	$('.oxymask_device').on('click',function(){
		var totalLiterFlow = $('.total_liter_flow').val();
  		if(totalLiterFlow > 15) {
  			$('.oxymask_device_2').click();
  		}
	});

	$('.none_device').on('click',function(){
		var totalLiterFlow = $('.total_liter_flow').val();
  		if(totalLiterFlow > 15) {
  			$('.none_device_2').click();
  		}
	});

	$('.oxygen_concentrator_duration_1').on('click',function(){
		if($(this).val() == 78)
		{
			var totalLiterFlow = $('.total_liter_flow').val();
	  		if(totalLiterFlow > 15) {
	  			$("body").find('.cont_duration').prop("checked", true);
	  			$("body").find('.prn_duration').prop("checked", false);
	  			$("body").find(".oxygen_e_portable_system_cont_2").prop("checked",true);
				$("body").find(".oxygen_e_portable_system_prn_2").prop("checked",false);
	  		}
			$("body").find(".oxygen_e_portable_system_cont").prop("checked",true);
			$("body").find(".oxygen_e_portable_system_prn").prop("checked",false);
		}
		else
		{
			var totalLiterFlow = $('.total_liter_flow').val();
	  		if(totalLiterFlow > 15) {
	  			$("body").find('.cont_duration').prop("checked", false);
	  			$("body").find('.prn_duration').prop("checked", true);
	  			$("body").find(".oxygen_e_portable_system_cont_2").prop("checked",false);
				$("body").find(".oxygen_e_portable_system_prn_2").prop("checked",true);
	  		}
			$("body").find(".oxygen_e_portable_system_cont").prop("checked",false);
			$("body").find(".oxygen_e_portable_system_prn").prop("checked",true);
		}
	});

	$('.cancel_oxygen_concentrator').on('click',function(){
		var container = $("body").find(".oxygen_concentrator_div");
		container.html("");
		$("body").find(".oxygen_e_portable_system_cont").removeAttr("checked");
		$("body").find(".oxygen_e_portable_system_prn").removeAttr("checked");
		$(".order_form_panel_body .c-oxygen_supply_kit-3").prop('checked',false);
	});

	$('.cancel-oxygen-liquid-reservoir').on('click',function(){
		$(".order_form_panel_body .c-oxygen_supply_kit-3").prop('checked',false);
		$('.order_form_panel_body .c-oxygen_liquid_refill-3').prop('checked',false);
	});

	$('.cancel-cpap-item').on('click',function(){
		$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked',false);
	});

	$('.cancel-cpap-item_2').on('click',function(){
		$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked',false);
	});

	$('.cancel-bipap-item').on('click',function(){
		$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked',false);
	});

	$('.cancel-bipap-item_2').on('click',function(){
		$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked',false);
	});

	$('.cancel-small-volume-nebulizer-item').on('click',function(){
		$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").prop('checked',false);
	});

	$('.cancel-small-volume-nebulizer-item-2').on('click',function(){
		$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").prop('checked',false);
	});

	$('.oxygen_concentrator_duration_2').on('click',function(){
		if($(this).val() == 103)
		{
			$("body").find(".oxygen_e_portable_system_cont_2").prop("checked",true);
			$("body").find(".oxygen_e_portable_system_prn_2").prop("checked",false);
		}
		else
		{
			$("body").find(".oxygen_e_portable_system_cont_2").prop("checked",false);
			$("body").find(".oxygen_e_portable_system_prn_2").prop("checked",true);
		}
	});

	$('.cancel_oxygen_concentrator_2').on('click',function(){
		var container = $("body").find(".oxygen_concentrator_div");
		container.html("");
		$("body").find(".oxygen_e_portable_system_cont").removeAttr("checked");
		$("body").find(".oxygen_e_portable_system_prn").removeAttr("checked");
	});

	$("body").on("click","#e_portable_no_2",function(){
		$("body").find(".e_portable_qty_2").val("");
		$("body").find(".c-oxygen_e_portable_system-2").removeAttr("checked");
		$("body").find(".e_portable_qty_1").val("");
		$("body").find(".c-oxygen_e_portable_system-1").removeAttr("checked");
		$(".order_form_panel_body .c-oxygen_e_regulator-1").removeAttr("checked");
		$(".order_form_panel_body .c-oxygen_e_cart-1").removeAttr("checked");
		updateOrderSummary();
	});

	/*
	*
	* submit form
	*/

	//to get the local time of your pc
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

  	function promptPackageItem(_this_save_btn) {
		jConfirm('Some package items are missing. Proceed?', 'Reminder', function(response){
			if(response) {
				saveOrderBtn(_this_save_btn);
			}
		});
	}


	function saveOrderBtn(_this_save_btn) {
		var dataval = $('#patient_mrn').val();
		var hdn_hospice_id = $('#hdn_hospice_id').val();
		var sessioned_account_type = $(".activity_type_sessioned_account_new_patient").val();

		if(sessioned_account_type == "dme_user")
    	{
    		$.ajax({
		      	type:"POST",
		      	url:base_url+"main/check_existing_patient/"+dataval+"/"+hdn_hospice_id,
		      	success:function(response)
		      	{
			        $('#patient_mrn').attr("data-content",response);
			        if(response!="")
			        {
			          	$('#patient_mrn').popover("show");
			          	$('html, body').animate({
						   	scrollTop: $('#patient-profile-container').offset().top
						}, 'slow');
			        }
			        else
			        {
			          	$('#patient_mrn').popover("hide");
			          	jConfirm('<br />Create New Customer? <br /><br /> <label class="i-checks"><input type="checkbox" class="send_to_confirm_work_order_new_patient" ><i></i> Send to Confirm Work Order</label>', 'Reminder', function(response){
							if(response)
							{
								//disable submit button until the order is process
								$(_this_save_btn).prop('disabled',true);

								if($(location).attr('href') != base_url)
								{
								    if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
								    {
								      	show_after_hour_alert();
								    }
								}

								$("#order_form_validate").ajaxSubmit({
									beforeSend:function(){
										me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Creating customer and submitting customer order ..."});
									},
									success:function(response)
									{
										var _userid = $('.sessionID').val();
										var user_type = $('.user_account_type').val();

							            $('#error-modal .alert').removeClass('alert-danger');
							            $('#error-modal .alert').removeClass('alert-info');
							            $('#error-modal .alert').removeClass('alert-success');

										try
				            			{
							                var obj = $.parseJSON(response);
							                me_message_v2(obj);
							                if(obj['error']==0)
							                {
							                	$.post(base_url+"main/trigger_gcm",function(){});
							                	window.location.reload();
							                }
							                else
							                {
							                	$(_this_save_btn).prop('disabled',false);
							                }
							            }
							            catch (err)
							            {
							            	me_message_v2({error:1,message:"Failed to submit an order."});
							            	$(_this_save_btn).prop('disabled',false);
							            }
									}
								});
							}
							else
							{
								$("body").find(".send_to_confirm_work_order_sign_new_patient").val(0);
							}
						});
			        }
		      	},
		      	error:function(jqXHR, textStatus, errorThrown)
		      	{
		        	console.log(textStatus, errorThrown);
		      	}
		    });
    	}
    	else
    	{
    		$.ajax({
		      	type:"POST",
		      	url:base_url+"main/check_existing_patient/"+dataval+"/"+hdn_hospice_id,
		      	success:function(response)
		      	{
		        	$('#patient_mrn').attr("data-content",response);
		        	if(response!="")
		        	{
		          		$('#patient_mrn').popover("show");
		          		$('html, body').animate({
					   		scrollTop: $('#patient-profile-container').offset().top
						}, 'slow');
			        }
			        else
			        {
			          	$('#patient_mrn').popover("hide");
			          	jConfirm('Create New Customer?', 'Reminder', function(response){
							if(response)
							{
								//disable submit button until the order is process
								$(_this_save_btn).prop('disabled',true);

								if($(location).attr('href') != base_url)
								{
								    if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
								    {
								      	show_after_hour_alert();
								    }
								}

								$("#order_form_validate").ajaxSubmit({
									beforeSend:function(){
										me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Creating customer and submitting customer order ..."});
									},
									success:function(response)
									{
										var _userid = $('.sessionID').val();
										var user_type = $('.user_account_type').val();

							            $('#error-modal .alert').removeClass('alert-danger');
							            $('#error-modal .alert').removeClass('alert-info');
							            $('#error-modal .alert').removeClass('alert-success');

										try
							            {
							                var obj = $.parseJSON(response);
							                me_message_v2(obj);
							                if(obj['error']==0)
							                {
							                	$.post(base_url+"main/trigger_gcm",function(){});
							                	window.location.reload();
							                }
							                else
							                {
							                	$(_this_save_btn).prop('disabled',false);
							                }
							            }
							            catch (err)
							            {
							            	me_message_v2({error:1,message:"Failed to submit an order."});
							            	$(_this_save_btn).prop('disabled',false);
							            }
									},
							      	error:function(jqXHR, textStatus, errorThrown)
							      	{
							        	console.log(textStatus, errorThrown);
							        	$(_this_save_btn).prop('disabled',false);
							      	}
								});
							}
						});
			        }
		      	},
		      	error:function(jqXHR, textStatus, errorThrown)
		      	{
		        	console.log(textStatus, errorThrown);
		      	}
		    });
    	}
	}

	$('.btn-save-order').click(function(e){
		e.preventDefault();
		var _this_save_btn = $(this);
		var dataval = $('#patient_mrn').val();
		var hdn_hospice_id = $('#hdn_hospice_id').val();
		var sessioned_account_type = $(".activity_type_sessioned_account_new_patient").val();
		var isSubmit = false;
		var isAlertPackage = false;

		if(sessioned_account_type == "dme_user")
    	{
		    // Suction Machine
			if ($('.order_form_panel_body .c-suction_machine-1').is(":checked")) {

				if (!$(".order_form_panel_body .c-suction_tubing_long-3").is(":checked")) {
					isAlertPackage = true;
				}

				if (!$(".order_form_panel_body .c-suction_tubing_short-3").is(":checked")) {
					isAlertPackage = true;
				}

				if (!$(".order_form_panel_body .c-yankuer_suction_tubing-3").is(":checked")) {
					isAlertPackage = true;
				}

				if (!$(".order_form_panel_body .c-suction_canister-3").is(":checked")) {
					isAlertPackage = true;
				}


			}

			//Small Volume Nebulizer
			if ($('.order_form_panel_body .c-small_volume_nebulizer-1').is(":checked")) {
				if (!$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").is(":checked")) {
					isAlertPackage = true;
				}
			}

			//Large Volume Nebulizer
			if ($('.order_form_panel_body .c-50_psi_compressor-1').is(":checked")) {
				if (!$(".order_form_panel_body .c-corrugated_tubing_7ft-3").is(":checked")) {
					isAlertPackage = true;
				}

				if (!$(".order_form_panel_body .c-jet_nebulizers-3").is(":checked")) {
					isAlertPackage = true;
				}
			}

			//Oxygen E Portable System
			if ($('.order_form_panel_body c-oxygen_e_portable_system-1').is(":checked")) {
				if (!$(".order_form_panel_body .c-oxygen_e_regulator-1").is(":checked")) {
					isAlertPackage = true;
				}

				if (!$(".order_form_panel_body .c-oxygen_e_cart-1").is(":checked")) {
					isAlertPackage = true;
				}
			}

			//Oxygen Concentrator
			if ($('.order_form_panel_body c-oxygen_concentrator-1').is(":checked")) {
				if (!$(".order_form_panel_body .c-oxygen_supply_kit-3").is(":checked")) {
					isAlertPackage = true;
				}
			}

			if (isAlertPackage) {
				promptPackageItem(_this_save_btn);
			} else {
				isSubmit = true;
			}

			console.log('isSubmit', isSubmit);
    		if (isSubmit) {
    			$.ajax({
			      	type:"POST",
			      	url:base_url+"main/check_existing_patient/"+dataval+"/"+hdn_hospice_id,
			      	success:function(response)
			      	{
				        $('#patient_mrn').attr("data-content",response);
				        if(response!="")
				        {
				          	$('#patient_mrn').popover("show");
				          	$('html, body').animate({
							   	scrollTop: $('#patient-profile-container').offset().top
							}, 'slow');
				        }
				        else
				        {
				          	$('#patient_mrn').popover("hide");
				          	jConfirm('<br />Create New Customer? <br /><br /> <label class="i-checks"><input type="checkbox" class="send_to_confirm_work_order_new_patient" ><i></i> Send to Confirm Work Order</label>', 'Reminder', function(response){
								if(response)
								{
									//disable submit button until the order is process
									$(_this_save_btn).prop('disabled',true);

									if($(location).attr('href') != base_url)
									{
									    if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
									    {
									      	show_after_hour_alert();
									    }
									}

									$("#order_form_validate").ajaxSubmit({
										beforeSend:function(){
											me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Creating customer and submitting customer order ..."});
										},
										success:function(response)
										{
											var _userid = $('.sessionID').val();
											var user_type = $('.user_account_type').val();

								            $('#error-modal .alert').removeClass('alert-danger');
								            $('#error-modal .alert').removeClass('alert-info');
								            $('#error-modal .alert').removeClass('alert-success');

											try
					            			{
								                var obj = $.parseJSON(response);
								                me_message_v2(obj);
								                if(obj['error']==0)
								                {
								                	$.post(base_url+"main/trigger_gcm",function(){});
								                	window.location.reload();
								                }
								                else
								                {
								                	$(_this_save_btn).prop('disabled',false);
								                }
								            }
								            catch (err)
								            {
								            	me_message_v2({error:1,message:"Failed to submit an order."});
								            	$(_this_save_btn).prop('disabled',false);
								            }
										}
									});
								}
								else
								{
									$("body").find(".send_to_confirm_work_order_sign_new_patient").val(0);
								}
							});
				        }
			      	},
			      	error:function(jqXHR, textStatus, errorThrown)
			      	{
			        	console.log(textStatus, errorThrown);
			      	}
			    });
    		}
	    }
	    else
	    {
	    	if (sessioned_account_type == "dme_admin" || sessioned_account_type == "dme_user") {
	    		// Suction Machine
				if ($('.order_form_panel_body .c-suction_machine-1').is(":checked")) {

					if (!$(".order_form_panel_body .c-suction_tubing_long-3").is(":checked")) {
						isAlertPackage = true;
					}

					if (!$(".order_form_panel_body .c-suction_tubing_short-3").is(":checked")) {
						isAlertPackage = true;
					}

					if (!$(".order_form_panel_body .c-yankuer_suction_tubing-3").is(":checked")) {
						isAlertPackage = true;
					}

					if (!$(".order_form_panel_body .c-suction_canister-3").is(":checked")) {
						isAlertPackage = true;
					}


				}

				//Small Volume Nebulizer
				if ($('.order_form_panel_body .c-small_volume_nebulizer-1').is(":checked")) {
					if (!$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").is(":checked")) {
						isAlertPackage = true;
					}
				}

				//Large Volume Nebulizer
				if ($('.order_form_panel_body .c-50_psi_compressor-1').is(":checked")) {
					if (!$(".order_form_panel_body .c-corrugated_tubing_7ft-3").is(":checked")) {
						isAlertPackage = true;
					}

					if (!$(".order_form_panel_body .c-jet_nebulizers-3").is(":checked")) {
						isAlertPackage = true;
					}
				}

				//Oxygen E Portable System
				if ($('.order_form_panel_body c-oxygen_e_portable_system-1').is(":checked")) {
					if (!$(".order_form_panel_body .c-oxygen_e_regulator-1").is(":checked")) {
						isAlertPackage = true;
					}

					if (!$(".order_form_panel_body .c-oxygen_e_cart-1").is(":checked")) {
						isAlertPackage = true;
					}
				}

				//Oxygen Concentrator
				if ($('.order_form_panel_body c-oxygen_concentrator-1').is(":checked")) {
					if (!$(".order_form_panel_body .c-oxygen_supply_kit-3").is(":checked")) {
						isAlertPackage = true;
					}
				}

				if (isAlertPackage) {
					promptPackageItem(_this_save_btn);
				} else {
					isSubmit = true;
				}

	    		if (isSubmit) {
	    			$.ajax({
				      	type:"POST",
				      	url:base_url+"main/check_existing_patient/"+dataval+"/"+hdn_hospice_id,
				      	success:function(response)
				      	{
				        	$('#patient_mrn').attr("data-content",response);
				        	if(response!="")
				        	{
				          		$('#patient_mrn').popover("show");
				          		$('html, body').animate({
							   		scrollTop: $('#patient-profile-container').offset().top
								}, 'slow');
					        }
					        else
					        {
					          	$('#patient_mrn').popover("hide");
					          	jConfirm('Create New Customer?', 'Reminder', function(response){
									if(response)
									{
										//disable submit button until the order is process
										$(_this_save_btn).prop('disabled',true);

										if($(location).attr('href') != base_url)
										{
										    if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
										    {
										      	show_after_hour_alert();
										    }
										}

										$("#order_form_validate").ajaxSubmit({
											beforeSend:function(){
												me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Creating customer and submitting customer order ..."});
											},
											success:function(response)
											{
												var _userid = $('.sessionID').val();
												var user_type = $('.user_account_type').val();

									            $('#error-modal .alert').removeClass('alert-danger');
									            $('#error-modal .alert').removeClass('alert-info');
									            $('#error-modal .alert').removeClass('alert-success');

												try
									            {
									                var obj = $.parseJSON(response);
									                me_message_v2(obj);
									                if(obj['error']==0)
									                {
									                	$.post(base_url+"main/trigger_gcm",function(){});
									                	window.location.reload();
									                }
									                else
									                {
									                	$(_this_save_btn).prop('disabled',false);
									                }
									            }
									            catch (err)
									            {
									            	me_message_v2({error:1,message:"Failed to submit an order."});
									            	$(_this_save_btn).prop('disabled',false);
									            }
											},
									      	error:function(jqXHR, textStatus, errorThrown)
									      	{
									        	console.log(textStatus, errorThrown);
									        	$(_this_save_btn).prop('disabled',false);
									      	}
										});
									}
								});
					        }
				      	},
				      	error:function(jqXHR, textStatus, errorThrown)
				      	{
				        	console.log(textStatus, errorThrown);
				      	}
				    });
	    		}
	    	} else {
	    		$.ajax({
			      	type:"POST",
			      	url:base_url+"main/check_existing_patient/"+dataval+"/"+hdn_hospice_id,
			      	success:function(response)
			      	{
			        	$('#patient_mrn').attr("data-content",response);
			        	if(response!="")
			        	{
			          		$('#patient_mrn').popover("show");
			          		$('html, body').animate({
						   		scrollTop: $('#patient-profile-container').offset().top
							}, 'slow');
				        }
				        else
				        {
				          	$('#patient_mrn').popover("hide");
				          	jConfirm('Create New Customer?', 'Reminder', function(response){
								if(response)
								{
									//disable submit button until the order is process
									$(_this_save_btn).prop('disabled',true);

									if($(location).attr('href') != base_url)
									{
									    if($('#account_type_id').val() == "hospice_admin" || $('#account_type_id').val() == "hospice_user")
									    {
									      	show_after_hour_alert();
									    }
									}

									$("#order_form_validate").ajaxSubmit({
										beforeSend:function(){
											me_message_v2({error:2,message:"<i class='fa fa-spin fa-spinner'></i> Creating customer and submitting customer order ..."});
										},
										success:function(response)
										{
											var _userid = $('.sessionID').val();
											var user_type = $('.user_account_type').val();

								            $('#error-modal .alert').removeClass('alert-danger');
								            $('#error-modal .alert').removeClass('alert-info');
								            $('#error-modal .alert').removeClass('alert-success');

											try
								            {
								                var obj = $.parseJSON(response);
								                me_message_v2(obj);
								                if(obj['error']==0)
								                {
								                	$.post(base_url+"main/trigger_gcm",function(){});
								                	window.location.reload();
								                }
								                else
								                {
								                	$(_this_save_btn).prop('disabled',false);
								                }
								            }
								            catch (err)
								            {
								            	me_message_v2({error:1,message:"Failed to submit an order."});
								            	$(_this_save_btn).prop('disabled',false);
								            }
										},
								      	error:function(jqXHR, textStatus, errorThrown)
								      	{
								        	console.log(textStatus, errorThrown);
								        	$(_this_save_btn).prop('disabled',false);
								      	}
									});
								}
							});
				        }
			      	},
			      	error:function(jqXHR, textStatus, errorThrown)
			      	{
			        	console.log(textStatus, errorThrown);
			      	}
			    });
	    	}
    	}
	});

	$('body').on('click','.send_to_confirm_work_order_new_patient',function(){

    if($(this).is(":checked"))
    {
      $("body").find(".send_to_confirm_work_order_sign_new_patient").val(1);
    }
    else
    {
      $("body").find(".send_to_confirm_work_order_sign_new_patient").val(0);
    }
  });

	/*
	*
	* submit form
	*/
	$("#order_summary_form_validate").ajaxForm({


		success:function(response){
						var _userid = $('.sessionID').val();
                        $('#error-modal .alert').removeClass('alert-danger');
                        $('#error-modal .alert').removeClass('alert-info');
                        $('#error-modal .alert').removeClass('alert-success');
			try
                        {
                            var obj = $.parseJSON(response);

                            $('#error-modal').find('.message-body').html(obj['message']);

                            var status = (obj['error']==0)? "alert-success" : "alert-danger";
                           $('#error-modal .alert').addClass(status);
                            if(obj['error']==0)
                            {
                                window.location.href = base_url + 'order/greetings/';
                            }
                        }
                        catch (err)
                        {
                            $('#error-modal').find('.message-body').html(obj['message']);
                            $('#error-modal .alert').addClass("alert-danger");
                        }
                        $('#error-modal').modal("show");
		}
	});

	/********************************************************
	START. This codes are for making these Items a package. On initial order only, these items must print out on a separate line on the work order. - They need to choose the item first.
	*********************************************************/

	// $('.order_form_panel_body .c-oxygen_supply_kit-3').bind('click',function(){
	//     if($(this).is(":checked"))
	//     {
	//      	$(".order_form_panel_body .c-oxygen_liquid-2").prop('checked','checked');
	//      	$('.order_form_panel_body .c-oxygen_liquid_refill-3').prop('checked',true);
	//      	$('#oxygen_supply_kit_3').modal("hide");
	//      	$('#oxygen_liquid_2').modal("show");
	//     }
	//     else
	//     {
	//       	$(".order_form_panel_body .c-oxygen_liquid-2").prop('checked',false);
	//       	$('.order_form_panel_body .c-oxygen_liquid_refill-3').prop('checked',false);
	//       	$('#oxygen_liquid_2').modal("hide");
	//       	updateOrderSummary();
	//     }
 	// });

  	$('.order_form_panel_body .c-oxygen_liquid-2').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-oxygen_supply_kit-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-oxygen_supply_kit-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-oxygen_concentrator-1').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-oxygen_supply_kit-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-oxygen_supply_kit-3").prop('checked',false);
	      	$(".order_form_panel_body .c-oxygen_e_regulator-1").prop('checked',false);
      		$(".order_form_panel_body .c-oxygen_e_cart-1").prop('checked',false);
      		$(".order_form_panel_body .c-oxygen_e_portable_system-1").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-suction_machine-1').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-suction_tubing_long-3").prop('checked','checked');
	     	$(".order_form_panel_body .c-suction_tubing_short-3").prop('checked','checked');
	     	$(".order_form_panel_body .c-yankuer_suction_tubing-3").prop('checked','checked');
	     	$(".order_form_panel_body .c-suction_canister-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-suction_tubing_long-3").prop('checked',false);
	     	$(".order_form_panel_body .c-suction_tubing_short-3").prop('checked',false);
	     	$(".order_form_panel_body .c-yankuer_suction_tubing-3").prop('checked',false);
	     	$(".order_form_panel_body .c-suction_canister-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-suction_machine-2').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-suction_tubing_long-3").prop('checked','checked');
	     	$(".order_form_panel_body .c-suction_tubing_short-3").prop('checked','checked');
	     	$(".order_form_panel_body .c-yankuer_suction_tubing-3").prop('checked','checked');
	     	$(".order_form_panel_body .c-suction_canister-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-suction_tubing_long-3").prop('checked',false);
	     	$(".order_form_panel_body .c-suction_tubing_short-3").prop('checked',false);
	     	$(".order_form_panel_body .c-yankuer_suction_tubing-3").prop('checked',false);
	     	$(".order_form_panel_body .c-suction_canister-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-suction_machine_portable-2').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-suction_tubing_long-3").prop('checked','checked');
	     	$(".order_form_panel_body .c-suction_tubing_short-3").prop('checked','checked');
	     	$(".order_form_panel_body .c-yankuer_suction_tubing-3").prop('checked','checked');
	     	$(".order_form_panel_body .c-suction_canister-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-suction_tubing_long-3").prop('checked',false);
	     	$(".order_form_panel_body .c-suction_tubing_short-3").prop('checked',false);
	     	$(".order_form_panel_body .c-yankuer_suction_tubing-3").prop('checked',false);
	     	$(".order_form_panel_body .c-suction_canister-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-cpap-1').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-cpap-2').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-bipap-1').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-bipap-2').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-cpap_tubing_7ft-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-50_psi_compressor-1').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-corrugated_tubing_7ft-3").prop('checked','checked');
	     	$(".order_form_panel_body .c-jet_nebulizers-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-corrugated_tubing_7ft-3").prop('checked',false);
	      	$(".order_form_panel_body .c-jet_nebulizers-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-50_psi_compressor-2').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-corrugated_tubing_7ft-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-corrugated_tubing_7ft-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-small_volume_nebulizer-1').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").prop('checked',false);
	      	$(".order_form_panel_body .c-adult_aerosol_mask-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-small_volume_nebulizer-2').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").prop('checked',false);
	      	$(".order_form_panel_body .c-adult_aerosol_mask-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-nebulizer_compressor-1').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-nebulizer_compressor-2').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-nebulizer_kits_mouthpiece-3").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-oxygen_e_portable_system-1').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-oxygen_e_regulator-1").prop('checked','checked');
	     	$(".order_form_panel_body .c-oxygen_e_cart-1").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-oxygen_e_regulator-1").prop('checked',false);
	      	$(".order_form_panel_body .c-oxygen_e_cart-1").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_panel_body .c-oxygen_e_portable_system-2').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_panel_body .c-oxygen_e_regulator-2").prop('checked','checked');
	     	$(".order_form_panel_body .c-oxygen_e_cart-2").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_panel_body .c-oxygen_e_regulator-2").prop('checked',false);
	      	$(".order_form_panel_body .c-oxygen_e_cart-2").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

		// *********** ITEM PACKAGES WHEN ORDERED FROM ADD NEW EQUIPMENT SECTION ***********//

		// $('.order_form_acttype .c-suction_machine-1').bind('click',function(){
	  //   if($(this).is(":checked"))
	  //   {
	  //    	$(".order_form_acttype .c-suction_tubing_long-3").prop('checked','checked');
	  //    	$(".order_form_acttype .c-suction_tubing_short-3").prop('checked','checked');
	  //    	$(".order_form_acttype .c-yankuer_suction_tubing-3").prop('checked','checked');
	  //    	$(".order_form_acttype .c-suction_canister-3").prop('checked','checked');
	  //    	updateOrderSummary();
	  //   }
	  //   else
	  //   {
	  //     $(".order_form_acttype .c-suction_tubing_long-3").prop('checked',false);
	  //    	$(".order_form_acttype .c-suction_tubing_short-3").prop('checked',false);
	  //    	$(".order_form_acttype .c-yankuer_suction_tubing-3").prop('checked',false);
	  //    	$(".order_form_acttype .c-suction_canister-3").prop('checked',false);
	  //     	updateOrderSummary();
	  //   }
  	// });

  	// $('.order_form_acttype .c-suction_machine-2').bind('click',function(){
	  //   if($(this).is(":checked"))
	  //   {
	  //    	$(".order_form_acttype .c-suction_tubing_long-3").prop('checked','checked');
	  //    	$(".order_form_acttype .c-suction_tubing_short-3").prop('checked','checked');
	  //    	$(".order_form_acttype .c-yankuer_suction_tubing-3").prop('checked','checked');
	  //    	$(".order_form_acttype .c-suction_canister-3").prop('checked','checked');
	  //    	updateOrderSummary();
	  //   }
	  //   else
	  //   {
	  //     $(".order_form_acttype .c-suction_tubing_long-3").prop('checked',false);
	  //    	$(".order_form_acttype .c-suction_tubing_short-3").prop('checked',false);
	  //    	$(".order_form_acttype .c-yankuer_suction_tubing-3").prop('checked',false);
	  //    	$(".order_form_acttype .c-suction_canister-3").prop('checked',false);
	  //     	updateOrderSummary();
	  //   }
  	// });

  	$('.order_form_acttype .c-oxygen_e_portable_system-2').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_acttype .c-oxygen_e_regulator-2").prop('checked','checked');
	     	$(".order_form_acttype .c-oxygen_e_cart-2").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_acttype .c-oxygen_e_regulator-2").prop('checked',false);
	      	$(".order_form_acttype .c-oxygen_e_cart-2").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	$('.order_form_acttype .c-oxygen_e_portable_system-1').bind('click',function(){
	    if($(this).is(":checked"))
	    {
	     	$(".order_form_acttype .c-oxygen_e_regulator-1").prop('checked','checked');
	     	$(".order_form_acttype .c-oxygen_e_cart-1").prop('checked','checked');
	     	updateOrderSummary();
	    }
	    else
	    {
	      	$(".order_form_acttype .c-oxygen_e_regulator-1").prop('checked',false);
	      	$(".order_form_acttype .c-oxygen_e_cart-1").prop('checked',false);
	      	updateOrderSummary();
	    }
  	});

  	// $('.e_portable_system_option_yes').bind('click',function(){
    //  	$(".order_form_panel_body .c-oxygen_e_regulator-1").prop('checked','checked');
    //  	$(".order_form_panel_body .c-oxygen_e_cart-1").prop('checked','checked');
    //  	updateOrderSummary();
  	// });

  	// $('.e_portable_system_option_no').bind('click',function(){
    //   	$(".order_form_panel_body .c-oxygen_e_regulator-1").prop('checked',false);
    //   	$(".order_form_panel_body .c-oxygen_e_cart-1").prop('checked',false);
    //   	$(".order_form_panel_body .c-oxygen_e_portable_system-1").prop('checked',false);
    //   	updateOrderSummary();
  	// });

  	/********************************************************
	END. This codes are for making these Items a package. On initial order only, these items must print out on a separate line on the work order. - They need to choose the item first.
	*********************************************************/


    /*
     * popup sub categories update order summary
     */

    //for recurring equipments
	$('.btn-recurring-save').on('click',function(){
		var quantity = $('#recurring_equipment_quantity').val();
		var equipment_id = $('#recur_equip_id').val();
		var data_desc = $('#recurring_equipment_quantity').attr("data-desc");
		var data_name = $('#recurring_equipment_quantity').attr("data-name");
		var category = $('#recurring_equipment_quantity').attr("data-category-id");
		if(!quantity) {
			$("#modal_recurring_equipments"+" .modal-body > .message").remove();
			$("#modal_recurring_equipments"+" .modal-body").prepend("<div class='message'></div>");
			var final_error_message = data_desc+" is required.<br />";

			me_message($("#modal_recurring_equipments"+" .modal-body > .message"),'Error message',final_error_message,1);
			setTimeout(function(){
				$("#modal_recurring_equipments"+" .modal-body > .message").fadeOut(1000,function(){
					$(this).remove();
				});
			},4000);
		} else {
			var temp = "<input type='hidden' class='form-control recur_items modal-"+data_name+"-"+category+"' data-desc='Quantity' name='subrecurringequipment[" + equipment_id + "]' value='" +quantity+ "'>";
			console.log(temp);
			var modal_recurring_equipment = $('#sub_recurring_equipments');
			modal_recurring_equipment.append(temp);
			$('#recurring_equipment_quantity').val("");
			$("#modal_recurring_equipments").modal("hide");
			updateRecurringOrderSummary();
		}
	});

     //checks if all fields are all checked or field up
    $('body').on('click','.btn-order',function(){
		var newArray = new Array();
		var this_modalbody = $(this).parent().siblings(".equipments_modal");
		var iterator = 0;
		var error = new Array();
		var checkbox = new Array();
		var modal_parent = $(this).parent().parent().parent().parent().attr("id");
		var cylinder_id = $(this).parent().siblings(".cylinder_classes").val();

		this_modalbody.find('INPUT').each(function(){
			var name_ = $(this).attr("name");
			var desc  = $(this).attr("data-desc");
			var type = $(this).attr("type");
			var value_ = $("input[name='"+name_+"']").val();

			if(desc != "Liter Flow disp") {
				if(type=="radio")
				{
					if(!$("input[name='"+name_+"']:checked").val())
					{
						value_ = false;
					}
					else
					{
						value_ = true;
					}
				}
				else if(type=="checkbox")
				{
					if(!$("input[name='"+name_+"']:checked").val())
					{
							value_ = false;
					}
					else
					{
						value_ = true;
					}

					if(modal_parent.indexOf("oxygen_concentrator") > -1)
					{
						var fiveltr 	= this_modalbody.find('.5_ltr').is(":checked");
						var tenltr 	= this_modalbody.find('.10_ltr').is(":checked");
						if(fiveltr==false && tenltr==false)
						{
							value_ = false;
						}
						else
						{
							value_ = true;
						}
					}
				}
				if(cylinder_id != 11 && cylinder_id != 170 && desc != "Rate")
				{
					if(value_=="" || value_==false)
					{
						if(!inArray(desc,newArray))
						{
							newArray[iterator] = desc;
							error[iterator] = desc+" is required.<br />";
							iterator++;
						}
					}
				}
			}

		});

		if(error.length<1)
		{
			updateOrderSummary();
			$("#"+modal_parent).modal("hide");
		}
		else
		{
			$("#"+modal_parent+" .modal-body > .message").remove();
			$("#"+modal_parent+" .modal-body").prepend("<div class='message'></div>");
			var final_error_message = "";
			for(var i=0;i<error.length;i++)
			{
				final_error_message += error[i];
			}
			me_message($("#"+modal_parent+" .modal-body > .message"),'Error message',final_error_message,1);
			setTimeout(function(){
				$("#"+modal_parent+" .modal-body > .message").fadeOut(1000,function(){
					$(this).remove();
				});
			},4000);
		}
		setTimeout(function(){
           	$("body").removeClass("modal-open");
        },1000);
    });

		//Modal for disposable items
		$('.btn-disposable-order').on('click',function(){
			var btn_save = $(this);
			var newArray = new Array();
			var this_modalbody = $('.ajax_modal');
			var iterator = 0;
			var error = new Array();
			var checkbox = new Array();
			var modal_parent = $(this).parent().parent().parent().parent().parent().parent().parent().attr("id");

			this_modalbody.find('INPUT').each(function(){
				var desc = $(this).attr("data-desc");
				var value_ = $(this).val();
				var name_ = $(this).attr("name");
				var type = $(this).attr("type");

			});

			if(error.length<1)
			{
				updateOrderSummary();
				$("#"+modal_parent).modal("hide");
			}
		});


		$("body").on('click','.btn-order-close',function(){
			var this_modalbody = $(this).parent().siblings(".modal-body");
			var modal_parent = $(this).parent().parent().parent().parent().attr("id");
			var category = $(this).parent().parent().parent().parent().attr("data-category");
			var splitted = modal_parent.split("_"+category);
			//class of parent checkbox
			//c-<name>-<datacategory>
			if(splitted[0]!=undefined)
			{
				$(".c-"+splitted[0]+"-"+category).removeAttr("checked");
			}
			this_modalbody.find('INPUT').each(function(){
				var type = $(this).attr("type");

				if(type=="text")
				{
					$(this).val("");
				}
				else
				{
					$(this).removeAttr("checked");
				}
			});
			$("#"+modal_parent).modal("hide");
			updateOrderSummary();
		});


		/** Auto select of E portable system **/
		$('#e_portable_yes_1').click(function(){
			$('.c-oxygen_e_portable_system-1').prop('checked',true);
			$(".order_form_panel_body .c-oxygen_e_regulator-1").prop('checked',true);
     		$(".order_form_panel_body .c-oxygen_e_cart-1").prop('checked',true);
     		$(".order_form_acttype .c-oxygen_e_regulator-1").prop('checked','checked');
     		$(".order_form_acttype .c-oxygen_e_cart-1").prop('checked','checked');
		    $('#oxygen_e_portable_system_1').modal('show');
				$('.e_portable_qty_1').val($('.liter_flow_field').val());
				updateOrderSummary();
	  	});

	  	$('#e_portable_yes_2').click(function(){
	  		$(".order_form_acttype .c-oxygen_e_regulator-2").prop('checked','checked');
     		$(".order_form_acttype .c-oxygen_e_cart-2").prop('checked','checked');
		    $('.c-oxygen_e_portable_system-2').prop('checked',true);
		    $('#oxygen_e_portable_system_2').modal('show');
		    $('.e_portable_qty_2').val($('#liter_flow_field_2').val());
	  	});


        /*
         * pickup
         */
        $('.activity-type').bind('change',function(){
            var value_ = $(this).val();
            if(value_== "2")
            {
                $(".pickup-container").show();
				$("#forrespite_categories").hide();
				$("#forptmove_categories").hide();
				$("#forrespite_categories2").hide();
            }
            else if(value_== "4")
            {
            	$(".pickup-container").hide();
            	$("#forrespite_categories").hide();
				$("#forrespite_categories2").hide();
				$("#forptmove_categories").show();
            }
            else if(value_== "5")
            {
				$(".pickup-container").hide();
				$("#forptmove_categories").hide();
				$("#forrespite_categories").show();
				$("#forrespite_categories2").show();
            }
            else
            {
                $(".pickup-container").hide();
				$("#forptmove_categories").hide();
				$("#forrespite_categories").hide();
				$("#forrespite_categories2").hide();
            }
        });

         $('.select-all-exchange').bind('click',function(){

        	if($(this).is(":checked"))
        	{
        		$('.checked_item').prop('checked','checked');
        	}
        	else
        	{
        		$('.checked_item').prop('checked',false);
        	}
            //$('input:checkbox').not(this).prop('checked', this.checked);
        });

        $('.select-all-old-items').bind('click',function(){

        	if($(this).is(":checked"))
        	{
        		$('.checked_pickup_old_item').prop('checked','checked');
        		$('.sub_ptmove_options_checkbox').prop('checked','checked');

        	}
        	else
        	{
        		$('.checked_pickup_old_item').prop('checked',false);
        		$('.sub_ptmove_options_checkbox').prop('checked', false);
        	}
            //$('input:checkbox').not(this).prop('checked', this.checked);
        });



          /*
           * pickup form
           */
          $('.pickup-form').ajaxForm({
              success:function(response){
                  var obj = $.parseJSON(response);
                  jAlert(obj['message'],'Pickup',function(){
                      if(obj['error']==0)
                      {
                          window.parent.location.reload();
                      }
                  });
              }
					});

	// Clicking the save button for Floor Mat quantity modal
	$('.save_floor_mat_capped').click(function(){
		var _this = $(this);
		var modal_body = _this.parent().siblings(".equipments_modal");
		var floor_mat_quantity = modal_body.find('.floor_mat_capped_quantity').val();

		if (floor_mat_quantity > 1) {
			$('body').find('.floor_mat_noncapped_quantity').val(floor_mat_quantity-1);
			$('body').find('.c-floor_mat-2').prop("checked",true);
		}
	});

	// Clicking the save button for Hospital Bed Rail Bumpers (Half Rail) quantity modal
	$('.save_hospital_bed_rail_bumpers_half_capped').click(function(){
		var _this = $(this);
		var modal_body = _this.parent().siblings(".equipments_modal");
		var hospital_bed_rail_bumpers_half_capped_quantity = modal_body.find('.hospital_bed_rail_bumpers_half_capped_quantity').val();

		if (hospital_bed_rail_bumpers_half_capped_quantity > 1) {
			$('body').find('.hospital_bed_rail_bumpers_half_noncapped_quantity').val(hospital_bed_rail_bumpers_half_capped_quantity-1);
			$('body').find('.c-hospital_bed_rail_bumper_half_rail-2').prop("checked",true);
		}
	});

	// Clicking the save button for Hospital Bed Rail Bumpers (Full Rail) quantity modal
	$('.save_hospital_bed_rail_bumpers_full_capped').click(function(){
		var _this = $(this);
		var modal_body = _this.parent().siblings(".equipments_modal");
		var hospital_bed_rail_bumpers_full_capped_quantity = modal_body.find('.hospital_bed_rail_bumpers_full_capped_quantity').val();

		if (hospital_bed_rail_bumpers_full_capped_quantity > 1) {
			$('body').find('.hospital_bed_rail_bumpers_full_noncapped_quantity').val(hospital_bed_rail_bumpers_full_capped_quantity-1);
			$('body').find('.c-hospital_bed_rail_bumper_full_rail-2').prop("checked",true);
		}
	});

	$('body .item_serial_numbers_confirmation').bind('keyup',function(){
		const orderID = $(this).attr('data-orderID');
		const value = $(this).val();
		$('body').find('.item_orderID'+ orderID).val(value);
	});

});