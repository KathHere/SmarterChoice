<style type="text/css">

	.search_items_result_div
	{
		margin-top: 50px !important;
		padding-right: 30px; 
		padding-left: 30px;
	}

	.search_items_result_table
	{
		margin-top: 30px;
		margin-bottom: 30px;
	}

	.search_vendor_result_div
	{
		margin-top: 70px !important;
	}

	.search_item_td
	{
		text-align: center !important;
	}

	.search_item_th
	{
		text-align: center !important;
		line-height:37px;
	}

	@media (max-width: 1000px){
		.search_items_result_table
		{
			width: 850px !important;
			margin-left: -30px !important;
		}
		
	}

	@media (max-width: 480px){

		.vendor_boxes
		{
			width: 100% !important;
		}
	}
	
</style>

<?php 
    if ($search_type == 'item_search') {
        ?>
		<div class="form-group clearfix search-item-div" style="margin-top:150px !important;margin-bottom:100px !important;">
			<div class="col-sm-6 col-sm-offset-3" style="text-align:center;">
				<i class="icon-social-dropbox" style="font-size: 102px;"></i>
			    <p style="text-align:center;margin-top: 9px;">Item Search</p>
				<div class="input-group">
		 			<input type="text" class="form-control" id="search-item" name="" style="text-transform:none" autocomplete="off" value="" placeholder="Search by Company Item No., Item Description">
		 			<span class="input-group-btn">
				        <button class="btn btn-default btn-submit-item-search" type="button" title="Search"><i class="fa fa-search"></i></button>
				    </span>
				</div>
				<div id="suggestion_container_item_search" style="z-index:9999;position:absolute;border:0px solid black;width: calc(100% - 68px);padding-right:0px;max-height: 250px; overflow-y: auto;">
				</div>
			</div>
			<div class="col-sm-12 search_items_result_div" style="overflow-x: auto;">
			</div>
		</div>
<?php
    } elseif ($search_type == 'vendor_search') {
        ?>
		<div class="form-group clearfix search-vendor-div" style="margin-top:150px !important;margin-bottom:100px !important;">
			<div class="col-sm-6 col-sm-offset-3" style="text-align:center;">
				<i class="fa fa-fw fa-users text" style="font-size: 102px;"></i>
			    <p style="text-align:center;margin-top: 9px;">Vendor Search</p>
				<div class="input-group">
		 			<input type="text" class="form-control" id="search-vendor" name="" style="text-transform:none" autocomplete="off" value="" placeholder="Search by Vendor Name., Account No.">
		 			<span class="input-group-btn">
				        <button class="btn btn-default btn-submit-vendor-search" type="button" title="Search"><i class="fa fa-search"></i></button>
				    </span>
				</div>
				<div id="suggestion_container_vendor_search" style="z-index:9999;position:absolute;border:0px solid black;width: calc(100% - 68px);padding-right:0px;max-height: 250px; overflow-y: auto;">
				</div>
			</div>
			<div class="col-sm-12 search_vendor_result_div" style="overflow-x: auto;">
			</div>
		</div>
<?php
    }
?>
	
<script type="text/javascript"> 

	$(document).ready(function(){

		/** Search Vendor **/
		$('#search-vendor').on('keyup',function(){
		    var searchString = $(this).val();
				$('#suggestion_container_vendor_search').html("<div style='text-align:center; padding-top:25px;margin-bottom:25px;font-size:17px !important; height: 250px !important; background-color: #fff !important;'><h4 style='margin-top: 85px !important'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></h4></div>");
		    if(searchString.length > 0)
		    {
		    	$.ajax({
		           	type:"POST",
		           	url:base_url+"inventory/search_vendor_v2/?searchString="+searchString,
		           	success:function(response)
		           	{
		              	$('#suggestion_container_vendor_search').show();
		              	$('#suggestion_container_vendor_search').html(response);

		              	$(".vendor_results").bind('click', function(){ 
		              		var vendor_id = $(this).attr('data-id');

		              		$.post(base_url+"inventory/get_searched_vendor/" + vendor_id,"", function(response){
		              			var obj = $.parseJSON(response);
		              			var temp = "";

							 	if(obj.vendor_details.vendor_active_sign == 1)
							 	{
							 		temp = 	'<div class="col-12 col-xs-6 col-sm-4 col-md-3 vendor_boxes" id="'+obj.vendor_details.vendor_id+'">'+
					                        	'<div class="panel wrapper" style="min-height:342px !important;">'+
					                        		'<div class="icon-container bg-info">'+
							                        	'<a href="'+base_url+'inventory/vendor_details/'+obj.vendor_details.vendor_id+'">'+
								                        	'<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;">'+
								                        		'<i class="fa fa-fw fa-folder-open-o text" style="font-size:65px;"></i>'+
								                        	'</button>'+
							                        	'</a>'+
					                        		'</div>'+
					                        		'<h4 class="m-t-lg text-info-lter" style="margin-top: 13px;"> <a href="'+base_url+'inventory/vendor_details/'+obj.vendor_details.vendor_id+'" style="color:#3498b7 !important;">'+obj.vendor_details.vendor_name+' </a></h4>'+
					                        	'</div>'+
					                        '</div>';
							 	}
							 	else
							 	{
							 		temp = 	'<div class="col-12 col-xs-6 col-sm-4 col-md-3 vendor_boxes" id="'+obj.vendor_details.vendor_id+'">'+
					                        	'<div class="panel wrapper" style="min-height:342px !important;">'+
					                        		'<div class="icon-container bg-info">'+
							                        	'<a href="'+base_url+'inventory/vendor_details/'+obj.vendor_details.vendor_id+'">'+
								                        	'<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;background-color: #7ccfe8;border-color: #7ccfe8;">'+
								                        		'<i class="fa fa-fw fa-folder-open-o text" style="font-size:65px;"></i>'+
								                        	'</button>'+
							                        	'</a>'+
					                        		'</div>'+
					                        		'<h4 class="m-t-lg text-info-lter" style="margin-top: 13px;"> <a href="'+base_url+'inventory/vendor_details/'+obj.vendor_details.vendor_id+'" style="color:#3498b7 !important;">'+obj.vendor_details.vendor_name+' </a></h4>'+
					                        	'</div>'+
					                        '</div>';
							 	}

							 	$("body").find(".search_vendor_result_div").html(temp);
							});
		                 	$('#suggestion_container_vendor_search').hide();
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
		      	$('#suggestion_container_vendor_search').hide();
		    }
		});

		$('body').on('click','#search-vendor',function(){
			var final_content = $("body").find("#search-vendor").val();

			if(final_content.length > 0)
			{
				$('#suggestion_container_vendor_search').show();
			}
		});
		
		$('body').on('click','.vendor-result-lists',function(){
			var final_content = $("body").find("#search-vendor").val();

			if(final_content.length > 0)
			{
				$.post(base_url+"inventory/view_all_searched_vendors/"+final_content,"", function(response){
	      			var obj = $.parseJSON(response);
			        var temp = "";

	            	for(var val in obj.searched_vendors)
		  			{
		  				if(obj.searched_vendors[val].vendor_active_sign == 1)
					 	{
					 		temp += 	'<div class="col-12 col-xs-6 col-sm-4 col-md-3 vendor_boxes" id="'+obj.searched_vendors[val].vendor_id+'">'+
				                        	'<div class="panel wrapper" style="min-height:342px !important;">'+
				                        		'<div class="icon-container bg-info">'+
						                        	'<a href="'+base_url+'inventory/vendor_details/'+obj.searched_vendors[val].vendor_id+'">'+
							                        	'<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;">'+
							                        		'<i class="fa fa-fw fa-folder-open-o text" style="font-size:65px;"></i>'+
							                        	'</button>'+
						                        	'</a>'+
				                        		'</div>'+
				                        		'<h4 class="m-t-lg text-info-lter" style="margin-top: 13px;"> <a href="'+base_url+'inventory/vendor_details/'+obj.searched_vendors[val].vendor_id+'" style="color:#3498b7 !important;">'+obj.searched_vendors[val].vendor_name+' </a></h4>'+
				                        	'</div>'+
				                        '</div>';
					 	}
					 	else
					 	{
					 		temp += 	'<div class="col-12 col-xs-6 col-sm-4 col-md-3 vendor_boxes" id="'+obj.searched_vendors[val].vendor_id+'">'+
				                        	'<div class="panel wrapper" style="min-height:342px !important;">'+
				                        		'<div class="icon-container bg-info">'+
						                        	'<a href="'+base_url+'inventory/vendor_details/'+obj.searched_vendors[val].vendor_id+'">'+
							                        	'<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;background-color: #7ccfe8;border-color: #7ccfe8;">'+
							                        		'<i class="fa fa-fw fa-folder-open-o text" style="font-size:65px;"></i>'+
							                        	'</button>'+
						                        	'</a>'+
				                        		'</div>'+
				                        		'<h4 class="m-t-lg text-info-lter" style="margin-top: 13px;"> <a href="'+base_url+'inventory/vendor_details/'+obj.searched_vendors[val].vendor_id+'" style="color:#3498b7 !important;">'+obj.searched_vendors[val].vendor_name+' </a></h4>'+
				                        	'</div>'+
				                        '</div>';
					 	}
		  			}
		  			$("body").find(".search_vendor_result_div").html(temp);
				});
			}
			else
			{
				$("body").find(".search_vendor_result_div").html("");
			}
      		$('#suggestion_container_vendor_search').hide();
      	});

      	$('body').on('click','.btn-submit-vendor-search',function(){
			var final_content = $("body").find("#search-vendor").val();

			if(final_content.length > 0)
			{
				$.post(base_url+"inventory/view_all_searched_vendors/"+final_content,"", function(response){
	      			var obj = $.parseJSON(response);
			        var temp = "";

	            	for(var val in obj.searched_vendors)
		  			{
		  				if(obj.searched_vendors[val].vendor_active_sign == 1)
					 	{
					 		temp += 	'<div class="col-12 col-xs-6 col-sm-4 col-md-3 vendor_boxes" id="'+obj.searched_vendors[val].vendor_id+'">'+
				                        	'<div class="panel wrapper" style="min-height:342px !important;">'+
				                        		'<div class="icon-container bg-info">'+
						                        	'<a href="'+base_url+'inventory/vendor_details/'+obj.searched_vendors[val].vendor_id+'">'+
							                        	'<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;">'+
							                        		'<i class="fa fa-fw fa-folder-open-o text" style="font-size:65px;"></i>'+
							                        	'</button>'+
						                        	'</a>'+
				                        		'</div>'+
				                        		'<h4 class="m-t-lg text-info-lter" style="margin-top: 13px;"> <a href="'+base_url+'inventory/vendor_details/'+obj.searched_vendors[val].vendor_id+'" style="color:#3498b7 !important;">'+obj.searched_vendors[val].vendor_name+' </a></h4>'+
				                        	'</div>'+
				                        '</div>';
					 	}
					 	else
					 	{
					 		temp += 	'<div class="col-12 col-xs-6 col-sm-4 col-md-3 vendor_boxes" id="'+obj.searched_vendors[val].vendor_id+'">'+
				                        	'<div class="panel wrapper" style="min-height:342px !important;">'+
				                        		'<div class="icon-container bg-info">'+
						                        	'<a href="'+base_url+'inventory/vendor_details/'+obj.searched_vendors[val].vendor_id+'">'+
							                        	'<button class="btn btn-sm btn-icon btn-info" style="width: 100%;height: 230px;background-color: #7ccfe8;border-color: #7ccfe8;">'+
							                        		'<i class="fa fa-fw fa-folder-open-o text" style="font-size:65px;"></i>'+
							                        	'</button>'+
						                        	'</a>'+
				                        		'</div>'+
				                        		'<h4 class="m-t-lg text-info-lter" style="margin-top: 13px;"> <a href="'+base_url+'inventory/vendor_details/'+obj.searched_vendors[val].vendor_id+'" style="color:#3498b7 !important;">'+obj.searched_vendors[val].vendor_name+' </a></h4>'+
				                        	'</div>'+
				                        '</div>';
					 	}
		  			}
		  			$("body").find(".search_vendor_result_div").html(temp);
				});	
			}
			else
			{
				$("body").find(".search_vendor_result_div").html("");
			}
	      	$('#suggestion_container_vendor_search').hide();
      	});

        /** Search Items **/
		$('#search-item').on('keyup',function(){
		    var searchString = $(this).val();
		    var vendor_id = 0;
		    var item_unit_of_measures = "";
		    var item_unit_of_measures_select = "";
		    var company_cost = 0;
		    var each_company_cost = 0;
		    var final_company_cost = 0;
		    var count = 0;
				$('#suggestion_container_item_search').html("<div style='text-align:center; padding-top:25px;margin-bottom:25px;font-size:17px !important; height: 250px !important; background-color: #fff !important;'><h4 style='margin-top: 85px !important'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></h4></div>");
		    if(searchString.length > 0)
		    {
		      	$.ajax({
		           	type:"POST",
		           	url:base_url+"inventory/search_item_v2/"+vendor_id+"/?searchString="+searchString,
		           	success:function(response)
		           	{
		              	$('#suggestion_container_item_search').show();
		              	$('#suggestion_container_item_search').html(response);

		              	$(".item_results").bind('click', function(){ 
		              		var item_id = $(this).attr('data-id');

		              		$.post(base_url+"inventory/get_searched_item/" + item_id,"", function(response){
		              			var obj = $.parseJSON(response);
		              			var temp = "";
							 	var item_status = "";

							 	if(obj.item_details.item_active_sign == 1)
							 	{
							 		item_status = "Active";
							 	}
							 	else
							 	{
							 		item_status = "Inactive";
							 	}

							 	var current_measure = "";
							 	var selected_unit = "";
							 	for(var val in obj.item_unit_of_measures)
	  							{
	  								if(count == 0)
	  								{
	  									company_cost = obj.item_unit_of_measures[val].item_company_cost;
	  									count++;
	  								}
	  								if(obj.item_unit_of_measures[val].item_unit_measure == "each")
	  								{
	  									current_measure = "Each";
	  									selected_unit = "selected";
	  									each_company_cost = obj.item_unit_of_measures[val].item_company_cost;
	  								}
	  								else if(obj.item_unit_of_measures[val].item_unit_measure == "box")
	  								{
	  									current_measure = "Box";
	  									selected_unit = "";
	  								}
	  								else if(obj.item_unit_of_measures[val].item_unit_measure == "case")
	  								{
	  									current_measure = "Case";
	  									selected_unit = "";
	  								}
	  								else if(obj.item_unit_of_measures[val].item_unit_measure == "pair")
	  								{
	  									current_measure = "Pair";
	  									selected_unit = "";
	  								}
	  								else if(obj.item_unit_of_measures[val].item_unit_measure == "pack")
	  								{
	  									current_measure = "Pack";
	  									selected_unit = "";
	  								}
	  								else if(obj.item_unit_of_measures[val].item_unit_measure == "package")
	  								{
	  									current_measure = "Package";
	  									selected_unit = "";
	  								}
	  								else if(obj.item_unit_of_measures[val].item_unit_measure == "roll")
	  								{
	  									current_measure = "Roll";
	  									selected_unit = "";
	  								}
	  								item_unit_of_measures += 	'<option value="'+obj.item_unit_of_measures[val].item_unit_measure +'" data-item-company-cost="'+obj.item_unit_of_measures[val].item_company_cost+'" '+selected_unit+'>'+
	  																' '+current_measure+' - '+obj.item_unit_of_measures[val].item_unit_value+' '+
												 				'</option>';
	  							}
	  							item_unit_of_measures_select = '<select class="form-control select_item_unit_of_measure">'+
	  																' '+item_unit_of_measures+' '+
												 				'</select>';

								if(each_company_cost == 0)
								{
									final_company_cost = Number(company_cost);
								}
								else
								{
									final_company_cost = Number(each_company_cost);
								}
				                temp = 	'<table class="table bg-white b-a table-hover search_items_result_table">'+
				                        	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
				                        		'<tr>'+
				                        			'<th class="search_item_th" style="width:15%;">Company Item No.</th>'+
				                        			'<th class="search_item_th" style="width:25%;">Item Description</th>'+
				                        			'<th class="search_item_th" style="width:14%;">Unit of Measure</th>'+
				                        			'<th class="search_item_th" style="width:12%;">Company Cost</th>'+
				                        			'<th class="search_item_th" style="width:12%;">On Hand</th>'+
				                        			'<th class="search_item_th" style="width:10%;">WHSE</th>'+
				                        			'<th class="search_item_th" style="width:12%;">Status</th>'+
				                        		'</tr>'+
				                        	'</thead>'+
				                        	'<tbody class="">'+
				                        		'<tr>'+
				                        			'<td class="search_item_td">'+obj.item_details.company_item_no +'</td>'+
				                        			'<td class="search_item_td">'+obj.item_details.item_description +'</td>'+
				                        			'<td class="search_item_td">'+item_unit_of_measures_select+'</td>'+
				                        			'<td class="company_item_cost_td search_item_td">'+final_company_cost.toFixed(2)+'</td>'+
				                        			'<td class="search_item_td">'+obj.total_on_hand+'</td>'+
				                        			'<td class="search_item_td">'+obj.item_details.item_warehouse_location +'</td>'+
				                        			'<td class="search_item_td">'+item_status+'</td>'+
				                        		'</tr> '+
				                        	'</tbody>'+
				                      	'</table>';

							 	$("body").find(".search_items_result_div").html(temp);
							});
		                 	$('#suggestion_container_item_search').hide();
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
		      	$('#suggestion_container_item_search').hide();
		    }
		});

		$('body').on('change','.select_item_unit_of_measure',function(){
			var _this = $(this);
			var company_item_cost =  $('option:selected', this).attr('data-item-company-cost');
			company_item_cost = Number(company_item_cost);

			$(_this).parent("td").siblings(".company_item_cost_td").html(company_item_cost.toFixed(2));
		});

		$('body').on('click','#search-item',function(){
			var final_content = $("body").find("#search-item").val();

			if(final_content.length > 0)
			{
				$('#suggestion_container_item_search').show();
			}
		});

		$('body').on('click','.result-lists',function(){
			var final_content = $("body").find("#search-item").val();
			var vendor_id = 0;

      		$.post(base_url+"inventory/view_all_searched_items/"+vendor_id+"/"+final_content,"", function(response){
      			var obj = $.parseJSON(response);
      			var temp = "";
      			var another_temp = "";
			 	var item_status = "";

			 	temp = 	'<table class="table bg-white b-a table-hover search_items_result_table">'+
                        	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
                        		'<tr>'+
                        			'<th class="search_item_th" style="width:15%;">Company Item No.</th>'+
                        			'<th class="search_item_th" style="width:25%;">Item Description</th>'+
                        			'<th class="search_item_th" style="width:14%;">Unit of Measure</th>'+
                        			'<th class="search_item_th" style="width:12%;">Company Cost</th>'+
                        			'<th class="search_item_th" style="width:12%;">On Hand</th>'+
                        			'<th class="search_item_th" style="width:10%;">WHSE</th>'+
                        			'<th class="search_item_th" style="width:12%;">Status</th>'+
                        		'</tr>'+
                        	'</thead>'+
                        	'<tbody class="search_items_result_tbody">'+
                        	'</tbody>'+
                      	'</table>';

                $("body").find(".search_items_result_div").html(temp);

            	for(var val in obj.searched_items)
	  			{
	  				var count = 0;
	  				var current_measure = "";
				 	var selected_unit = "";
				 	var company_cost = 0;
				    var each_company_cost = 0;
				    var final_company_cost = 0;
				    var item_unit_of_measures = "";
		    		var item_unit_of_measures_select = "";

				 	for(var second_val in obj.item_unit_of_measures[obj.searched_items[val].item_id])
					{
						if(count == 0)
						{
							company_cost = obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_company_cost;
							count++;
						}
						if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "each")
						{
							current_measure = "Each";
							selected_unit = "selected";
							each_company_cost = obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_company_cost;
						}
						else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "box")
						{
							current_measure = "Box";
							selected_unit = "";
						}
						else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "case")
						{
							current_measure = "Case";
							selected_unit = "";
						}
						else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "pair")
						{
							current_measure = "Pair";
							selected_unit = "";
						}
						else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "pack")
						{
							current_measure = "Pack";
							selected_unit = "";
						}
						else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "package")
						{
							current_measure = "Package";
							selected_unit = "";
						}
						else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "roll")
						{
							current_measure = "Roll";
							selected_unit = "";
						}
						item_unit_of_measures += 	'<option value="'+ obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure +'" data-item-company-cost="'+obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_company_cost+'" '+selected_unit+'>'+
														' '+current_measure+' - '+obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_value+' '+
									 				'</option>';
					}
					item_unit_of_measures_select = '<select class="form-control select_item_unit_of_measure">'+
															' '+item_unit_of_measures+' '+
									 				'</select>';
					if(each_company_cost == 0)
					{
						final_company_cost = Number(company_cost);
					}
					else
					{
						final_company_cost = Number(each_company_cost);
					}

	  				if(obj.searched_items[val].item_active_sign == 1)
				 	{
				 		item_status = "Active";
				 	}
				 	else
				 	{
				 		item_status = "Inactive";
				 	}

				 	another_temp += '<tr>'+
	                        			'<td class="search_item_td">'+obj.searched_items[val].company_item_no +'</td>'+
	                        			'<td class="search_item_td">'+obj.searched_items[val].item_description +'</td>'+
	                        			'<td class="search_item_td">'+item_unit_of_measures_select+'</td>'+
				                        '<td class="search_item_td company_item_cost_td">'+final_company_cost.toFixed(2)+'</td>'+
	                        			'<td class="search_item_td">'+obj.total_on_hand[obj.searched_items[val].item_id]+'</td>'+
	                        			'<td class="search_item_td">'+obj.searched_items[val].item_warehouse_location +'</td>'+
	                        			'<td class="search_item_td">'+item_status+'</td>'+
	                        		'</tr> ';
	  			}
	  			$("body").find(".search_items_result_tbody").html(another_temp);
			});

      		$('#suggestion_container_item_search').hide();
      	});

      	$('.btn-submit-item-search').bind('click',function(){
      		var final_content = $("body").find("#search-item").val();
      		var vendor_id = 0;

      		if(final_content.length > 0)
      		{
      			$.post(base_url+"inventory/view_all_searched_items/"+vendor_id+"/"+final_content,"", function(response){
	      			var obj = $.parseJSON(response);
	      			var temp = "";
	      			var another_temp = "";
				 	var item_status = "";

				 	temp = 	'<table class="table bg-white b-a table-hover search_items_result_table">'+
	                        	'<thead style="background-color:rgba(97, 101, 115, 0.05);">'+
	                        		'<tr>'+
	                        			'<th class="search_item_th" style="width:15%;">Company Item No.</th>'+
	                        			'<th class="search_item_th" style="width:25%;">Item Description</th>'+
	                        			'<th class="search_item_th" style="width:14%;">Unit of Measure</th>'+
	                        			'<th class="search_item_th" style="width:12%;">Company Cost</th>'+
	                        			'<th class="search_item_th" style="width:12%;">On Hand</th>'+
	                        			'<th class="search_item_th" style="width:10%;">WHSE</th>'+
	                        			'<th class="search_item_th" style="width:12%;">Status</th>'+
	                        		'</tr>'+
	                        	'</thead>'+
	                        	'<tbody class="search_items_result_tbody">'+
	                        	'</tbody>'+
	                      	'</table>';

	                $("body").find(".search_items_result_div").html(temp);

	                if(obj.searched_items.length > 0)
	                {
	                	for(var val in obj.searched_items)
			  			{
			  				var count = 0;
			  				var current_measure = "";
						 	var selected_unit = "";
						 	var company_cost = 0;
						    var each_company_cost = 0;
						    var final_company_cost = 0;
						    var item_unit_of_measures = "";
				    		var item_unit_of_measures_select = "";

						 	for(var second_val in obj.item_unit_of_measures[obj.searched_items[val].item_id])
							{
								if(count == 0)
								{
									company_cost = obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_company_cost;
									count++;
								}
								if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "each")
								{
									current_measure = "Each";
									selected_unit = "selected";
									each_company_cost = obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_company_cost;
								}
								else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "box")
								{
									current_measure = "Box";
									selected_unit = "";
								}
								else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "case")
								{
									current_measure = "Case";
									selected_unit = "";
								}
								else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "pair")
								{
									current_measure = "Pair";
									selected_unit = "";
								}
								else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "pack")
								{
									current_measure = "Pack";
									selected_unit = "";
								}
								else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "package")
								{
									current_measure = "Package";
									selected_unit = "";
								}
								else if(obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure == "roll")
								{
									current_measure = "Roll";
									selected_unit = "";
								}
								item_unit_of_measures += 	'<option value="'+ obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_measure +'" data-item-company-cost="'+obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_company_cost+'" '+selected_unit+'>'+
																' '+current_measure+' - '+obj.item_unit_of_measures[obj.searched_items[val].item_id][second_val].item_unit_value+' '+
											 				'</option>';
							}
							item_unit_of_measures_select = '<select class="form-control select_item_unit_of_measure">'+
																	' '+item_unit_of_measures+' '+
											 				'</select>';
							if(each_company_cost == 0)
							{
								final_company_cost = Number(company_cost);
							}
							else
							{
								final_company_cost = Number(each_company_cost);
							}

			  				if(obj.searched_items[val].item_active_sign == 1)
						 	{
						 		item_status = "Active";
						 	}
						 	else
						 	{
						 		item_status = "Inactive";
						 	}

						 	another_temp += '<tr>'+
			                        			'<td class="search_item_td">'+obj.searched_items[val].company_item_no +'</td>'+
			                        			'<td class="search_item_td">'+obj.searched_items[val].item_description +'</td>'+
			                        			'<td class="search_item_td">'+item_unit_of_measures_select+'</td>'+
						                        '<td class="search_item_td company_item_cost_td">'+final_company_cost.toFixed(2)+'</td>'+
			                        			'<td class="search_item_td">'+obj.total_on_hand[obj.searched_items[val].item_id]+'</td>'+
			                        			'<td class="search_item_td">'+obj.searched_items[val].item_warehouse_location +'</td>'+
			                        			'<td class="search_item_td">'+item_status+'</td>'+
			                        		'</tr> ';
			  			}
			  			$("body").find(".search_items_result_tbody").html(another_temp);
	                }
	                else
	                {	
	                	another_temp += '<p style="text-align:center;font-size:17px !important;">'+
				                			'No Result Found.'+
				                		'</p>';
				       	$("body").find(".search_items_result_div").html(another_temp);
	                }
				});
      		}
      		else
      		{
      			$("body").find(".search_items_result_div").html("");
      		}

      		$('#suggestion_container_item_search').hide();
      	});

  	   	
	});

</script>



