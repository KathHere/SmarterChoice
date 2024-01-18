$(function(){

	var updateOrderSummary = function(){
		var category = new Array();
		var category_temp = new Array();
		var category_val = new Array();
		$(".wrapper-equipment").each(function(){
				var catey = $(this).attr("data-value");
				var iterator = 0;
				category_val[catey] = [];
				$(this).find(".checkboxes").each(function(i){
					// if($(this).attr("data-category-id") != 3)
					// {

					
						if($(this).is(':checked'))
						{
							var cat_ = $(this).attr("data-category-id");
							var cat_name    = $(this).attr("data-category");
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
	                                                                        var attrtype = $(this).attr("type");
	                                                                        subval_desc         = $(this).attr("data-desc") ;
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
	                                                                        }
	                                                                        else if(attrtype==="checkbox")
	                                                                        {
	                                                                            if($(this).is(':checked'))
	                                                                            {
	                                                                                subval_value = $(this).attr("data-value");
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
							//console.log(val_+" ==== "+cat_);
							iterator++;
						// }
					}
				});
		});
		$(".order-cont").html("");
		if(category.length>0)
		{
			var category_html = "";
			for(var i=0;i<category.length;i++)
			{
					var  ct_id = category[i]['category_id'];
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

					category_html += '<div class="col-md-12" style="padding-left:0px;">'+
										'<label>'+category[i]['category_name']+'</label>'+
										'<ol class="OpenSans-Reg" style=";margin-left: -20px;">';
					category_html += category_values;
					category_html += "</ol></div><div class='clearfix'></div><hr>";

			}
			$(".order-cont").html(category_html);
		}
		else
		{
			//console.log("wala sud");
		}
	};
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


	$('.checkboxes').on('click',function(){
		var data_noncapped_reference = $(this).attr("data-reference-id");
		var _this = $(this);
		var selected_activity_type_val = $('#selected_activity_type').val();
		var capped_item_modal = $(this).attr('data-target');
		var capped_item_modal_noncapped = $(this).attr('data-target').replace("1","2");

		if($(this).is(':checked'))
		{
			var target = $(this).attr("data-target");
			$(target).modal('show');

			//added to get the value of the checked item for duplicate capped item checking
			var ids = [];
			var this_val = $(this).val();
			
			ids.push(this_val);
			$('#selected').val(ids);
			
			if(selected_activity_type_val == 1)
			{
				//$(capped_item_modal).modal("hide");
				check_capped_items();
				if(check_capped_items())
				{
					$(capped_item_modal).modal("hide");
					$("body").css("overflow-y","scroll");
				}
			}
			
			
			$('.btn-close-alert').on('click',function(){
				_this.prop('checked', false);
				updateOrderSummary();
			});

			$('.btn-approve-choice').on('click',function(){
				_this.prop('checked', false);
				$("input[type=checkbox][value="+data_noncapped_reference+"]").prop("checked",true);
				$(capped_item_modal_noncapped).modal("show");
				$("body").css("overflow-y","scroll");
				updateOrderSummary();
			});
		}

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
	    if(dt.getHours() == 17)
	    {
	      $('.after_hour_alert_content').show();
	      $('#after_hour_alert').modal("show");
	    }
	};

	$('.btn-save-order').click(function(e){
		e.preventDefault();

		jConfirm('Create New Patient?', 'Reminder', function(response){
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

				$("#order_form_validate").ajaxSubmit({
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

			                $('#error-modal').find('.message-body').html(obj['message']);
			                
			                var status = (obj['error']==0)? "alert-success" : "alert-danger";
			                $('#error-modal .alert').addClass(status);
			                if(obj['error']==0)
			                {
			                	$('#submit_order_loader').modal("hide");
			                	
			                	setTimeout(function(){
					            	$('#error-modal').modal("hide");
					            	location.reload();
					            },2500);
			                }
			            }
			            catch (err)
			            {
			                $('#error-modal').find('.message-body').html(obj['message']);
			                $('#error-modal .alert').addClass("alert-danger");
			            }
			            $('#error-modal').modal("show");
			            
			            setTimeout(function(){
			            	$('#submit_order_loader').modal("hide");
			            },1500);

			            setTimeout(function(){
			            	$('#error-modal').modal("hide");
			            },5000);
					}
				});
			}
		});
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

        
        /*
         * popup sub categories update order summary
         */
        $('.btn-order').on('click',function(){
			var newArray = new Array();
			var this_modalbody = $(this).parent().siblings(".equipments_modal");
			var iterator = 0;
			var error = new Array();
			var checkbox = new Array();
			var modal_parent = $(this).parent().parent().parent().parent().attr("id");
			
			this_modalbody.find('INPUT').each(function(){
				var name_ = $(this).attr("name");
				var desc  = $(this).attr("data-desc");
				var type = $(this).attr("type");
				var value_ = $("input[name='"+name_+"']").val();
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
				
				if(value_=="" || value_==false)
				{ 
					if(!inArray(desc,newArray))
					{
						newArray[iterator] = desc;
						error[iterator] = desc+" is required.<br />";
						iterator++;
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
	

		$(".btn-order-close").bind('click',function(){
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
		    $('#oxygen_e_portable_system_1').modal('show');
		    $('.e_portable_qty_1').val($('.liter_flow_field').val());
	  	});

	  	$('#e_portable_yes_2').click(function(){
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
      
        $('.select-all-pickup').bind('click',function(){

        	if($(this).is(":checked"))
        	{
        		$('.checked_pickup_item').prop('checked','checked');
        		$('.sub_options_checkbox').prop('checked','checked');
        		
        	}
        	else
        	{
        		$('.checked_pickup_item').prop('checked',false);
        		$('.sub_options_checkbox').prop('checked', false);
        	}
            //$('input:checkbox').not(this).prop('checked', this.checked);
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
});