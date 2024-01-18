jQuery(document).ready(function(){
	setTimeout('window.location.reload();', 600000);

	var current_url = window.location.href;
	var monthly_act 	= false;
	var daily_act 		= false;
	var monthly_ptr 	= false;
	var daily_ptr 		= false;
	var setSearchDate = function(location,value){
		    var type = "from";
			if(typeof location!="undefined")
			{
				type = location;
			}
			$("#"+type).val(value);
	}
	var colors = ["#3c8dbc", "#f56954", "#00a65a","#321fff","#00ea23","#ff1334"];
	var hospice_activity = new Morris.Donut({
								element: 'hospice-activity-chart',
								resize: true,
								colors: colors,
								data: [{label:"",value:""}],
								hideHover: 'auto',
								parseTime:false
				  			});
	var colors2 = ["#00a65a", "#f56954", "#3c8dbc","#ff1334","#112eee"];
	var patient_residence = new Morris.Donut({
								element: 'patient-residence-chart',
								resize: true,
								colors:colors2,
								data: [{label:"",value:""}],
								hideHover: 'auto',
								parseTime:false
							});
	var patient_residence_line = new Morris.Line({
			        element: 'patient-residence-status-report',
			        parseTime: false,
			        hideHover:true,
			        data: [{ y: '', "assisted_living": 0, "group_home": 0 , "hic_home": 0 ,"home_care": 0 , "skilled_nursing_facility": 0 }],
			        xkey: 'y',
			        ykeys: ['assisted_living', 'group_home', 'hic_home', 'home_care', 'skilled_nursing_facility'],
			        labels: ['Assisted Living', 'Group Home', 'Hic Home','Home Care','Skilled Nursing Facility'],
			        lineColors: ["#00a65a", "#f56954", "#3c8dbc","#ff1334","#112eee"],
			        hoverCallback: function (index, options, content, row) {
					   return content.replace(row.y,row.y+"<br /><i style='font-size:10px;'>"+row.date+"</i>");
					}
	        });
	var activity_status_line = new Morris.Line({
										        element: 'activity-status-report',
										        parseTime: false,
										        hideHover:true,
										        data: [{ y: '', "newcus": 0, "newitem": 0 , "exhange": 0 , "pickup": 0 , "cusmove": 0 , "respite": 0,"date":""}],
										        xkey: 'y',
										        ykeys: ['newcus', 'newitem', 'exchange', 'pickup', 'cusmove', 'respite'],
										        labels: ['New CUS', 'New Item', 'Exchange', 'Pickup', 'CUS Move', 'Respite'],
										        lineColors: ["#3c8dbc", "#f56954", "#00a65a","#321fff","#00ea23","#ff1334"],
										        hoverCallback: function (index, options, content, row) {
												   return content.replace(row.y,row.y+"<br /><i style='font-size:10px;'>"+row.date+"</i>");
												}
										});
	var loader = function(id){
		parent_ = $("#"+id).parents("."+id+"-panel");
		parent_.children().hide();
		parent_.prepend("<h1 class='text-center loader text-success'><i class='fa fa-spin fa-spinner'></i></h1>");
	}	
	var hideloader = function(id){
		parent_ = $("#"+id).parents("."+id+"-panel");
		parent_.children().show();
		parent_.find(".loader").remove();
	}
	//monthly activity report
	var monthly_activityreport = function(hospiceID,from,to)
	{
		var filter = "";
		if(typeof hospiceID !="undefined")
		{
			filter = "hospiceID="+hospiceID;
		}
		if(typeof from !="undefined" && from!="")
		{
			filter += ((filter!="")? '&': '')+"from="+from;
		}
		if(typeof to !="undefined" && to!="")
		{
			filter += ((filter!="")? '&': '')+"to="+to;
		}
		var colors = ["#3c8dbc", "#f56954", "#00a65a","#321fff","#00ea23","#ff1334"];

		loader("hospice-activity-chart");
		$.get(base_url+"report/activity_report_month/?"+filter,function(response){
			hideloader("hospice-activity-chart");
			//Donut Chart
			hospice_activity.setData(response.data.graph);
			var total = 0;
			$('.activities-report').html("");
			var activity_status_name = "";
			for(var i=0;i<response.data.graph.length;i++)
			{
			  	if(response.data.graph[i].label == "New CUS")
			  	{
			  		activity_status_name = "new_pt";
			  	}
			  	else if(response.data.graph[i].label == "New Item")
			  	{
			  		activity_status_name = "new_item";
			  	}
			  	else if(response.data.graph[i].label == "Exchange")
			  	{
			  		activity_status_name = "exchange";
			  	}
			  	else if(response.data.graph[i].label == "Pickup")
			  	{
			  		activity_status_name = "pickup";
			  	}
			  	else if(response.data.graph[i].label == "CUS Move")
			  	{
			  		activity_status_name = "pt_move";
			  	}
			  	else
			  	{
			  		activity_status_name = "respite";
			  	}

		  		$('.activities-report').append('<li class="list-group-item" style="color:'+colors[i]+';"><span class="badge">'+response.data.graph[i].value+'</span><strong class="activity_status_link" data-name="'+activity_status_name+'">'+response.data.graph[i].label+'</strong></li>');
		  		total += parseInt(response.data.graph[i].value);
			}
			$('.activities-report').append('<li class="list-group-item" style="cursor:default;"><span class="badge">'+total+'</span>TOTAL</li>');
			$('.title-activity').text(response.data.title);

			//setting display date
			setSearchDate("search_from",response.data.date_range_from);
			setSearchDate("search_to",response.data.date_range_to);

			var account_logged_in = $("body").find(".account_logged_in").val();
			if(account_logged_in == 0)
			{
				if(response.data.hospiceID.hospice_name.length > 0)
				{
					$("body").find(".viewed_hospice_name").html(response.data.hospiceID.hospice_name);
				}
				else
				{
					$("body").find(".viewed_hospice_name").html("Advantage Home Medical Services");
				}
			}
				
			var new_patient_days = "";
			var new_patient_los = "";
			var new_total = "";
			if(hospiceID != 0)
			{
				new_patient_days = "CUS Days: "+response.data.patient_days;
				$("body").find(".viewed_patient_days").html(new_patient_days);
				new_patient_los = "LOS: "+response.data.patient_los;
				$("body").find(".viewed_patient_los").html(new_patient_los);
			}	
			else if(hospiceID == 0 && from !="" && from!="")
			{
				new_patient_days = "CUS Days: "+response.data.patient_days;
				$("body").find(".viewed_patient_days").html(new_patient_days);
				new_patient_los = "LOS: "+response.data.patient_los;
				$("body").find(".viewed_patient_los").html(new_patient_los);
			}	
		});
	}

	$('.search_datepicker').datepicker({
    	dateFormat: 'yy-mm-dd',
     	onClose: function (dateText, inst) {
     		var search_from = $("body").find("#search_from").val();
  			var search_to = $("body").find("#search_to").val();
  			var hospiceID = $("body").find(".filter_reports_by_hospice").val();
  			var dates_viewed = $("body").find(".dates_viewed");

  			if(search_from != "" && search_to != "")
  			{
  				monthly_activityreport(hospiceID,search_from,search_to);
				monthly_patienceresidence_report(hospiceID,search_from,search_to);	
				var new_viewed_date = "";
	  			var month_name = "";
	  			var separated_from = search_from.split(/\s*\-\s*/g); 
	  			var separated_to = search_to.split(/\s*\-\s*/g); 

	  			if(separated_from[1] == 1)
  				{
  					month_name = "January";
  				}
  				else if(separated_from[1] == 2)
  				{
  					month_name = "February";
  				}
  				else if(separated_from[1] == 3)
  				{
  					month_name = "March";
  				}
  				else if(separated_from[1] == 4)
  				{
  					month_name = "April";
  				}
  				else if(separated_from[1] == 5)
  				{
  					month_name = "May";
  				}
  				else if(separated_from[1] == 6)
  				{
  					month_name = "June";
  				}
  				else if(separated_from[1] == 7)
  				{
  					month_name = "July";
  				}
  				else if(separated_from[1] == 8)
  				{
  					month_name = "August";
  				}
  				else if(separated_from[1] == 9)
  				{
  					month_name = "September";
  				}
  				else if(separated_from[1] == 10)
  				{
  					month_name = "October";
  				}
  				else if(separated_from[1] == 11)
  				{
  					month_name = "November";
  				}
  				else if(separated_from[1] == 12)
  				{
  					month_name = "December";
  				}

  				if(separated_to[1] == 1)
  				{
  					month_name_to = "January";
  				}
  				else if(separated_to[1] == 2)
  				{
  					month_name_to = "February";
  				}
  				else if(separated_to[1] == 3)
  				{
  					month_name_to = "March";
  				}
  				else if(separated_to[1] == 4)
  				{
  					month_name_to = "April";
  				}
  				else if(separated_to[1] == 5)
  				{
  					month_name_to = "May";
  				}
  				else if(separated_to[1] == 6)
  				{
  					month_name_to = "June";
  				}
  				else if(separated_to[1] == 7)
  				{
  					month_name_to = "July";
  				}
  				else if(separated_to[1] == 8)
  				{
  					month_name_to = "August";
  				}
  				else if(separated_to[1] == 9)
  				{
  					month_name_to = "September";
  				}
  				else if(separated_to[1] == 10)
  				{
  					month_name_to = "October";
  				}
  				else if(separated_to[1] == 11)
  				{
  					month_name_to = "November";
  				}
  				else if(separated_to[1] == 12)
  				{
  					month_name_to = "December";
  				}

	  			if((separated_from[0] == separated_to[0]) && (separated_from[1] == separated_to[1]) && (separated_from[2] == separated_to[2]))
	  			{
	  				new_viewed_date = month_name+" "+separated_from[2]+", "+separated_from[0];
	  			}
	  			else if((separated_from[0] == separated_to[0]) && (separated_from[1] == separated_to[1]) && (separated_from[2] != separated_to[2]))
	  			{
	  				new_viewed_date = month_name+" "+separated_from[2]+" - "+separated_to[2]+", "+separated_from[0];
	  			}
	  			else 
	  			{
	  				new_viewed_date = month_name+" "+separated_from[2]+", "+separated_from[0]+" - "+month_name_to+" "+separated_to[2]+", "+separated_to[0];
	  			}
	  			// viewed_current_date.html(new_viewed_date);
				$("body").find(".general_report_initial_date").html(new_viewed_date);	
  			}
     	}
    });

    $('select.filter_reports_by_hospice').on('change', function (e) {
    	var hospiceID = this.value; 
    	var search_from = $("body").find("#search_from").val();
		var search_to = $("body").find("#search_to").val();
		var viewed_hospice_name = $("body").find(".viewed_hospice_name");

		monthly_activityreport(hospiceID,search_from,search_to);
		monthly_patienceresidence_report(hospiceID,search_from,search_to);
		daily_activityreport(hospiceID,"","");
		daily_patienceresidence_report(hospiceID,"","");
	});

	$('body').on('click','.activity_status_link',function(){
		var activity_status_name = $(this).attr("data-name");
		var viewed_hospiceID = $("body").find(".filter_reports_by_hospice").val();

		if(activity_status_name == "new_pt")
	  	{
	  		activity_status_name_new = "New Customer";
	  	}
	  	else if(activity_status_name == "new_item")
	  	{
	  		activity_status_name_new = "New Items";
	  	}
	  	else if(activity_status_name == "exchange")
	  	{
	  		activity_status_name_new = "Exchange";
	  	}
	  	else if(activity_status_name == "pickup")
	  	{
	  		activity_status_name_new = "Pickup";
	  	}
	  	else if(activity_status_name == "pt_move")
	  	{
	  		activity_status_name_new = "CUS Move";
	  	}
	  	else
	  	{
	  		activity_status_name_new = "Respite";
	  	}
	  	$("body").find(".modal-dialog").addClass("modal-lg");
		modalbox(base_url + 'report/view_each_activity_status/' + activity_status_name+'/'+viewed_hospiceID,{
	      	header:""+activity_status_name_new+" Activity Status",
	      	button: true,
	      	buttons: 
	        [{
		        text: "<i class='fa fa-print'></i> Print",
		        type: "default pull-left print_page_details",
		        click: function() {
		        	var filter_from = $("body").find(".filter_from").val();
		  			var filter_to = $("body").find(".filter_to").val();
		  			var hospiceID = $("body").find(".filter_activity_status_details").val(); 
		  			var status_name = $("body").find("#activity_status_name").val();

		  			if(filter_from == "")
			  		{
			  			filter_from = 0;
			  		}
			  		if(filter_to == "")
			  		{
			  			filter_to = 0;
			  		}
		  			
		          	window.open(base_url+'report/print_activity_status_details/'+filter_from+'/'+filter_to+'/'+hospiceID+'/'+status_name);
	        	}
	        },{
		        text: "Close",
		        type: "danger",
		        click: function() {
		            closeModalbox();
	        	}
	        }]
	    });
	});

	//daily activity report
	var daily_activityreport  = function(hospiceID,from,to)
	{
		var filter = "";
		if(typeof hospiceID !="undefined")
		{
			filter = "hospiceID="+hospiceID;
		}
		if(typeof from !="undefined" && from!="")
		{
			filter += ((filter!="")? '&': '')+"from="+from;
		}
		if(typeof to !="undefined" && to!="")
		{
			filter += ((filter!="")? '&': '')+"to="+to;
		}

		loader("activity-status-report");
		$.get(base_url+"report/activity_report_day/?"+filter,function(response){	
				hideloader("activity-status-report");
				var graph = response.data.graph;
				var title = response.data.title;
				activity_status_line.setData(graph);
		});
	}

	//monthly patient residence status reports
	var monthly_patienceresidence_report = function(hospiceID,from,to)
	{
		var filter = "";
		if(typeof hospiceID !="undefined")
		{
			filter = "hospiceID="+hospiceID;
		}
		if(typeof from !="undefined" && from!="")
		{
			filter += ((filter!="")? '&': '')+"from="+from;
		}
		if(typeof to !="undefined" && to!="")
		{
			filter += ((filter!="")? '&': '')+"to="+to;
		}

		loader("patient-residence-chart");
		$.get(base_url+"report/patienceresidence_report_month/?"+filter,function(response){	
			hideloader("patient-residence-chart");
			var graph = response.data.graph;
			patient_residence.setData(graph);
			var total = 0;
			$('.patienceresidence-report').html("");
			var activity_status_name = "";
			for(var i=0;i<graph.length;i++)
			{
			  	if(graph[i].label == "Assisted Living")
			  	{
			  		activity_status_name = "assisted_living";
			  	}
			  	else if(graph[i].label == "Group Home")
			  	{
			  		activity_status_name = "group_home";
			  	}
			  	else if(graph[i].label == "Hic Home")
			  	{
			  		activity_status_name = "hic_home";
			  	}
			  	else if(response.data.graph[i].label == "Home Care")
			  	{
			  		activity_status_name = "home_care";
			  	}
			  	else
			  	{
			  		activity_status_name = "skilled_nursing_facility";
			  	}

			  	$('.patienceresidence-report').append('<li class="list-group-item" style="color:'+colors2[i]+';"><span class="badge">'+graph[i].value+'</span><strong class="residence_status_link" data-name="'+activity_status_name+'">'+graph[i].label+'</strong></li>');
			  	total += parseInt(graph[i].value);
		  	}
			$('.patienceresidence-report').append('<li class="list-group-item" style="cursor:default !important;" ><span class="badge">'+total+'</span>TOTAL</li>');
			$("body").find(".viewed_total_entries").html("Customers: "+response.data.entries);
		});
	}

	$('body').on('click','.residence_status_link',function(){
		var residence_status_name = $(this).attr("data-name");
		var viewed_hospiceID = $("body").find(".filter_reports_by_hospice").val();

		if(residence_status_name == "assisted_living")
	  	{
	  		residence_status_name_new = "Assisted Living";
	  	}
	  	else if(residence_status_name == "group_home")
	  	{
	  		residence_status_name_new = "Group Home";
	  	}
	  	else if(residence_status_name == "hic_home")
	  	{
	  		residence_status_name_new = "Hic Home";
	  	}
	  	else if(residence_status_name == "home_care")
	  	{
	  		residence_status_name_new = "Home Care";
	  	}
	  	else
	  	{
	  		residence_status_name_new = "Skilled Nursing Facility";
	  	}

	  	$("body").find(".modal-dialog").addClass("modal-lg");
		modalbox(base_url + 'report/view_each_residence_status/' + residence_status_name +'/'+ viewed_hospiceID,{
	      	header:""+residence_status_name_new+" Residence Status",
	      	button: true,
	      	buttons: 
	        [{
		        text: "<i class='fa fa-print'></i> Print",
		        type: "default pull-left print_page_details",
		        click: function() {
		        	var filter_from_residence = $("body").find(".filter_from_residence").val();
		  			var filter_to_residence = $("body").find(".filter_to_residence").val();
		  			var hospiceID = $("body").find(".filter_residence_status_details").val(); 
		  			var status_name = $("body").find("#residence_status_name").val();

		  			if(filter_from_residence == "")
			  		{
			  			filter_from_residence = 0;
			  		}
			  		if(filter_to_residence == "")
			  		{
			  			filter_to_residence = 0;
			  		}

		          	window.open(base_url+'report/print_residence_status_details/'+filter_from_residence+'/'+filter_to_residence+'/'+hospiceID+'/'+status_name);
	        	}
	        },{
		        text: "Close",
		        type: "danger",
		        click: function() {
		            closeModalbox();
	        	}
	        }]
	    });
	});

	//daily patience residence status reports
	var daily_patienceresidence_report = function(hospiceID,from,to)
	{
		var filter = "";
		if(typeof hospiceID !="undefined")
		{
			filter = "hospiceID="+hospiceID;
		}
		if(typeof from !="undefined" && from!="")
		{
			filter += ((filter!="")? '&': '')+"from="+from;
		}
		if(typeof to !="undefined" && to!="")
		{
			filter += ((filter!="")? '&': '')+"to="+to;
		}

		loader("patient-residence-status-report");
		$.get(base_url+"report/patienceresidence_report_day/?"+filter,function(response){
				hideloader("patient-residence-status-report");	
				var graph = response.data.graph;
				var title = response.data.title;
				patient_residence_line.setData(graph);
		});
	
	}

	var show_chart_on_reports = function(type1,from1,to1)
	{
		var hospiceID = 0;
		var from = "";
		var to   = "";
		
		if(current_url == base_url+"report")
		{
			monthly_activityreport(hospiceID,from,to);
			daily_activityreport(hospiceID,from,to);
			monthly_patienceresidence_report(hospiceID,from,to);
			daily_patienceresidence_report(hospiceID,from,to);
		}
	}
	
	show_chart_on_reports();

	$('.btn-search').on('click',function(){
		var hospiceID = $('.filter_reports_by_hospice').val();
		var from = $('#search_from').val();
		var to   = $("#search_to").val();
		show_chart_on_reports(hospiceID,from,to);
	});

	$('body').on('mouseover','.activity_status_link',function(){
		var data_name = $(this).attr("data-name");
		var temp = "";
		var table_content = "";
		var current_time = $("body").find(".current_time_value").val();
		var current_date = $("body").find(".current_date_value").val();
		var date_from = $("body").find("#search_from").val();
		var date_to = $("body").find("#search_to").val();
		var selected_hospice = $("body").find(".filter_reports_by_hospice").val();
		var new_viewed_date = "";

		if(date_from.length > 0 && date_to.length > 0)
		{
			var new_viewed_date = "";
			var month_name = "";
			var separated_from = date_from.split(/\s*\-\s*/g); 
			var separated_to = date_to.split(/\s*\-\s*/g); 

			if(separated_from[1] == 1)
			{
				month_name = "January";
			}
			else if(separated_from[1] == 2)
			{
				month_name = "February";
			}
			else if(separated_from[1] == 3)
			{
				month_name = "March";
			}
			else if(separated_from[1] == 4)
			{
				month_name = "April";
			}
			else if(separated_from[1] == 5)
			{
				month_name = "May";
			}
			else if(separated_from[1] == 6)
			{
				month_name = "June";
			}
			else if(separated_from[1] == 7)
			{
				month_name = "July";
			}
			else if(separated_from[1] == 8)
			{
				month_name = "August";
			}
			else if(separated_from[1] == 9)
			{
				month_name = "September";
			}
			else if(separated_from[1] == 10)
			{
				month_name = "October";
			}
			else if(separated_from[1] == 11)
			{
				month_name = "November";
			}
			else if(separated_from[1] == 12)
			{
				month_name = "December";
			}

			if(separated_to[1] == 1)
			{
				month_name_to = "January";
			}
			else if(separated_to[1] == 2)
			{
				month_name_to = "February";
			}
			else if(separated_to[1] == 3)
			{
				month_name_to = "March";
			}
			else if(separated_to[1] == 4)
			{
				month_name_to = "April";
			}
			else if(separated_to[1] == 5)
			{
				month_name_to = "May";
			}
			else if(separated_to[1] == 6)
			{
				month_name_to = "June";
			}
			else if(separated_to[1] == 7)
			{
				month_name_to = "July";
			}
			else if(separated_to[1] == 8)
			{
				month_name_to = "August";
			}
			else if(separated_to[1] == 9)
			{
				month_name_to = "September";
			}
			else if(separated_to[1] == 10)
			{
				month_name_to = "October";
			}
			else if(separated_to[1] == 11)
			{
				month_name_to = "November";
			}
			else if(separated_to[1] == 12)
			{
				month_name_to = "December";
			}

			if((separated_from[0] == separated_to[0]) && (separated_from[1] == separated_to[1]) && (separated_from[2] == separated_to[2]))
			{
				new_viewed_date = month_name+" "+separated_from[2]+", "+separated_from[0];
			}
			else if((separated_from[0] == separated_to[0]) && (separated_from[1] == separated_to[1]) && (separated_from[2] != separated_to[2]))
			{
				new_viewed_date = month_name+" "+separated_from[2]+" - "+separated_to[2]+", "+separated_from[0];
			}
			else 
			{
				new_viewed_date = month_name+" "+separated_from[2]+", "+separated_from[0]+" - "+month_name_to+" "+separated_to[2]+", "+separated_to[0];
			}
		}
		else
		{
			new_viewed_date = current_date;
		}
			
		if(data_name == "new_pt")
	  	{
	  		temp += '<div class="hover_sample_div" style="background-color:#fff; width:300px; color:#58666e !important; position: absolute; z-index: 999; margin-top: -80px; margin-right: -290px; margin-left: 110px; border: 2px solid rgba(33, 32, 32, 0.207843); ">'+
						'<div style="font-size:17px; font-weight:bold; color:rgba(88, 103, 110, 0.49); margin-top:3px !important; margin-bottom:-2px !important;"> PREVIEW</div>'+
						'<div style=""> New Customer</div>'+
						'<div style=""> '+new_viewed_date+'</div>'+
						'<div style="width:282px;margin-left:7px; margin-top:3px !important;">'+
							'<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">'+
								'<thead style="background-color:rgba(97, 101, 115, 0.05);height:10px !important;">'+
									'<tr style="font-size:12px;padding:0px !important;">'+
										'<th style="width: 65%; padding-top:3px !important;padding-bottom:3px !important;">Customer Name</th>'+
										'<th style="width: 35%; padding-top:3px !important;padding-bottom:3px !important;">MR#</th>'+
									'</tr>'+
								'</thead>'+
				    			'<tbody class="hover_tbody"></tbody>'+
							'</table>'+
						'</div>'+
						'<div> <span class="pull-left" style="margin-left:70px; margin-top:-16px !important; margin-bottom:4px !important; font-size:18px !important; font-weight:bold !important; color:rgba(88, 103, 110, 0.49);"> CLICK FOR DETAILS </span></span></div>'+
					'</div>';
	  		$(this).parent(".list-group-item").append(temp);

			$("body").find(".hover_tbody").html('<tr><td colspan="2" style="text-align:center;"> <i class="fa fa-spin fa-spinner"></i></td></tr>');
	  		$.post(base_url+"report/get_activity_status_sample_data/" + data_name +"/"+ date_from +"/"+ date_to +"/"+ selected_hospice,"", function(response){
	  			var obj = $.parseJSON(response);
	  			if(obj.patient_list.length > 0)
	  			{
	  				for(var val in obj.patient_list)
		  			{
		  				table_content += 	'<tr style="text-align:left;font-size:12px !important;">'+
		  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+'</td>'+
		  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].medical_record_id+'</td>'+
		  									'</tr>';
		  			}
	  			}
	  			else
	  			{
	  				table_content += 	'<tr style="text-align:center;font-size:12px !important;">'+
		  										'<td colspan="2" style="padding-top:4px !important;padding-bottom:4px !important;"> No Customer </td>'+
		  								'</tr>';
	  			}
	  			$("body").find(".hover_tbody").html(table_content);
	  			$("body").find(".total_patient_sample_count").html(obj.patient_list.length);
			});
	  	}
	  	else if(data_name == "new_item")
	  	{
	  		temp += '<div class="hover_sample_div" style="background-color:#fff; width:300px; color:#58666e !important; position: absolute; z-index: 999; margin-top: -80px; margin-right: -290px; margin-left: 110px; border: 2px solid rgba(33, 32, 32, 0.207843); ">'+
						'<div style="font-size:17px; font-weight:bold; color:rgba(88, 103, 110, 0.49); margin-top:3px !important; margin-bottom:-2px !important;"> PREVIEW</div>'+
						'<div style=""> New Items</div>'+
						'<div style=""> '+new_viewed_date+'</div>'+
						'<div style="width:282px;margin-left:7px; margin-top:3px !important;">'+
							'<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">'+
								'<thead style="background-color:rgba(97, 101, 115, 0.05);height:10px !important;">'+
									'<tr style="font-size:12px;padding:0px !important;">'+
										'<th style="width: 65%; padding-top:3px !important;padding-bottom:3px !important;">Customer Name</th>'+
										'<th style="width: 35%; padding-top:3px !important;padding-bottom:3px !important;">MR#</th>'+
									'</tr>'+
								'</thead>'+
				    			'<tbody class="hover_tbody"></tbody>'+
							'</table>'+
						'</div>'+
						'<div> <span class="pull-left" style="margin-left:70px; margin-top:-16px !important; margin-bottom:4px !important; font-size:18px !important; font-weight:bold !important; color:rgba(88, 103, 110, 0.44);"> CLICK FOR DETAILS </span> </span></div>'+
					'</div>';
	  		$(this).parent(".list-group-item").append(temp);

			$("body").find(".hover_tbody").html('<tr><td colspan="2" style="text-align:center;"> <i class="fa fa-spin fa-spinner"></i></td></tr>');
	  		$.post(base_url+"report/get_activity_status_sample_data/" + data_name +"/"+ date_from +"/"+ date_to +"/"+ selected_hospice,"", function(response){
	  			var obj = $.parseJSON(response);
	  			var count = 0;

  				for(var val in obj.patient_list)
	  			{
	  				table_content += 	'<tr style="text-align:left;font-size:12px !important;">'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+'</td>'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].medical_record_id+'</td>'+
	  									'</tr>';
	  				count++;
	  			}
	  			if(count == 0)
	  			{
	  				table_content = "";
	  				table_content += 	'<tr style="text-align:center;font-size:12px !important;">'+
		  										'<td colspan="2" style="padding-top:4px !important;padding-bottom:4px !important;"> No Customer </td>'+
		  								'</tr>';
	  			}
	  			
	  			$("body").find(".hover_tbody").html(table_content);
	  			$("body").find(".total_patient_sample_count").html(count);
			});
	  	}
	  	else if(data_name == "exchange")
	  	{
	  		temp += '<div class="hover_sample_div" style="background-color:#fff; width:300px; color:#58666e !important; position: absolute; z-index: 999; margin-top: -80px; margin-right: -290px; margin-left: 110px; border: 2px solid rgba(33, 32, 32, 0.207843); ">'+
						'<div style="font-size:17px; font-weight:bold; color:rgba(88, 103, 110, 0.49); margin-top:3px !important; margin-bottom:-2px !important;"> PREVIEW</div>'+
						'<div style=""> Exchange</div>'+
						'<div style=""> '+new_viewed_date+'</div>'+
						'<div style="width:282px;margin-left:7px; margin-top:3px !important;">'+
							'<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">'+
								'<thead style="background-color:rgba(97, 101, 115, 0.05);height:10px !important;">'+
									'<tr style="font-size:12px;padding:0px !important;">'+
										'<th style="width: 65%; padding-top:3px !important;padding-bottom:3px !important;">Customer Name</th>'+
										'<th style="width: 35%; padding-top:3px !important;padding-bottom:3px !important;">MR#</th>'+
									'</tr>'+
								'</thead>'+
				    			'<tbody class="hover_tbody"></tbody>'+
							'</table>'+
						'</div>'+
						'<div> <span class="pull-left" style="margin-left:70px; margin-top:-16px !important; margin-bottom:4px !important; font-size:18px !important; font-weight:bold !important; color:rgba(88, 103, 110, 0.49);"> CLICK FOR DETAILS </span> </span></div>'+
					'</div>';
	  		$(this).parent(".list-group-item").append(temp);

			$("body").find(".hover_tbody").html('<tr><td colspan="2" style="text-align:center;"> <i class="fa fa-spin fa-spinner"></i></td></tr>');
	  		$.post(base_url+"report/get_activity_status_sample_data/" + data_name +"/"+ date_from +"/"+ date_to +"/"+ selected_hospice,"", function(response){
	  			var obj = $.parseJSON(response);
	  			
	  			if(obj.patient_list.length > 0)
	  			{
	  				for(var val in obj.patient_list)
		  			{
		  				table_content += 	'<tr style="text-align:left;font-size:12px !important;">'+
		  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+'</td>'+
		  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].medical_record_id+'</td>'+
		  									'</tr>';
		  			}
	  			}
	  			else
	  			{
	  				table_content += 	'<tr style="text-align:center;font-size:12px !important;">'+
		  										'<td colspan="2" style="padding-top:4px !important;padding-bottom:4px !important;"> No Customer </td>'+
		  								'</tr>';
	  			}
	  			$("body").find(".hover_tbody").html(table_content);
	  			$("body").find(".total_patient_sample_count").html(obj.patient_list.length);
			});
	  	}
	  	else if(data_name == "pickup")
	  	{
	  		temp += '<div class="hover_sample_div" style="background-color:#fff; width:300px; color:#58666e !important; position: absolute; z-index: 999; margin-top: -80px; margin-right: -290px; margin-left: 110px; border: 2px solid rgba(33, 32, 32, 0.207843); ">'+
						'<div style="font-size:17px; font-weight:bold; color:rgba(88, 103, 110, 0.49); margin-top:3px !important; margin-bottom:-2px !important;"> PREVIEW</div>'+
						'<div style=""> Pickup</div>'+
						'<div style=""> '+new_viewed_date+'</div>'+
						'<div style="width:282px;margin-left:7px; margin-top:3px !important;">'+
							'<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">'+
								'<thead style="background-color:rgba(97, 101, 115, 0.05);height:10px !important;">'+
									'<tr style="font-size:12px;padding:0px !important;">'+
										'<th style="width: 65%; padding-top:3px !important;padding-bottom:3px !important;">Customer Name</th>'+
										'<th style="width: 35%; padding-top:3px !important;padding-bottom:3px !important;">MR#</th>'+
									'</tr>'+
								'</thead>'+
				    			'<tbody class="hover_tbody"></tbody>'+
							'</table>'+
						'</div>'+
						'<div> <span class="pull-left" style="margin-left:70px; margin-top:-16px !important; margin-bottom:4px !important; font-size:18px !important; font-weight:bold !important; color:rgba(88, 103, 110, 0.49);"> CLICK FOR DETAILS </span> </span></div>'+
					'</div>';
	  		$(this).parent(".list-group-item").append(temp);

			$("body").find(".hover_tbody").html('<tr><td colspan="2" style="text-align:center;"> <i class="fa fa-spin fa-spinner"></i></td></tr>');
	  		$.post(base_url+"report/get_activity_status_sample_data/" + data_name +"/"+ date_from +"/"+ date_to +"/"+ selected_hospice,"", function(response){
	  			var obj = $.parseJSON(response);
	  			
	  			if(obj.patient_list.length > 0)
	  			{
	  				for(var val in obj.patient_list)
		  			{
		  				table_content += 	'<tr style="text-align:left;font-size:12px !important;">'+
		  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+'</td>'+
		  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].medical_record_id+'</td>'+
		  									'</tr>';
		  			}
	  			}
	  			else
	  			{
	  				table_content += 	'<tr style="text-align:center;font-size:12px !important;">'+
		  										'<td colspan="2" style="padding-top:4px !important;padding-bottom:4px !important;"> No Customer </td>'+
		  								'</tr>';
	  			}
	  			$("body").find(".hover_tbody").html(table_content);
	  			$("body").find(".total_patient_sample_count").html(obj.patient_list.length);
			});
	  	}
	  	else if(data_name == "pt_move")
	  	{
	  		temp += '<div class="hover_sample_div" style="background-color:#fff; width:300px; color:#58666e !important; position: absolute; z-index: 999; margin-top: -80px; margin-right: -290px; margin-left: 110px; border: 2px solid rgba(33, 32, 32, 0.207843); ">'+
						'<div style="font-size:17px; font-weight:bold; color:rgba(88, 103, 110, 0.49); margin-top:3px !important; margin-bottom:-2px !important;"> PREVIEW</div>'+
						'<div style=""> CUS Move</div>'+
						'<div style=""> '+new_viewed_date+'</div>'+
						'<div style="width:282px;margin-left:7px; margin-top:3px !important;">'+
							'<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">'+
								'<thead style="background-color:rgba(97, 101, 115, 0.05);height:10px !important;">'+
									'<tr style="font-size:12px;padding:0px !important;">'+
										'<th style="width: 65%; padding-top:3px !important;padding-bottom:3px !important;">Customer Name</th>'+
										'<th style="width: 35%; padding-top:3px !important;padding-bottom:3px !important;">MR#</th>'+
									'</tr>'+
								'</thead>'+
				    			'<tbody class="hover_tbody"></tbody>'+
							'</table>'+
						'</div>'+
						'<div> <span class="pull-left" style="margin-left:70px; margin-top:-16px !important; margin-bottom:4px !important; font-size:18px !important; font-weight:bold !important; color:rgba(88, 103, 110, 0.49);"> CLICK FOR DETAILS </span> </span></div>'+
					'</div>';
	  		$(this).parent(".list-group-item").append(temp);

			$("body").find(".hover_tbody").html('<tr><td colspan="2" style="text-align:center;"> <i class="fa fa-spin fa-spinner"></i></td></tr>');
	  		$.post(base_url+"report/get_activity_status_sample_data/" + data_name +"/"+ date_from +"/"+ date_to +"/"+ selected_hospice,"", function(response){
	  			var obj = $.parseJSON(response);
	  			if(obj.patient_list.length > 0)
	  			{
	  				for(var val in obj.patient_list)
		  			{
		  				table_content += 	'<tr style="text-align:left;font-size:12px !important;">'+
		  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+'</td>'+
		  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].medical_record_id+'</td>'+
		  									'</tr>';
		  			}
	  			}
	  			else
	  			{
	  				table_content += 	'<tr style="text-align:center;font-size:12px !important;">'+
		  										'<td colspan="2" style="padding-top:4px !important;padding-bottom:4px !important;"> No Customer </td>'+
		  								'</tr>';
	  			}
	  			$("body").find(".hover_tbody").html(table_content);
	  			$("body").find(".total_patient_sample_count").html(obj.patient_list.length);
			});
	  	}
	  	else
	  	{
	  		temp += '<div class="hover_sample_div" style="background-color:#fff; width:300px; color:#58666e !important; position: absolute; z-index: 999; margin-top: -80px; margin-right: -290px; margin-left: 110px; border: 2px solid rgba(33, 32, 32, 0.207843); ">'+
						'<div style="font-size:17px; font-weight:bold; color:rgba(88, 103, 110, 0.49); margin-top:3px !important; margin-bottom:-2px !important;"> PREVIEW</div>'+
						'<div style=""> Respite</div>'+
						'<div style=""> '+new_viewed_date+'</div>'+
						'<div style="width:282px;margin-left:7px; margin-top:3px !important;">'+
							'<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">'+
								'<thead style="background-color:rgba(97, 101, 115, 0.05);height:10px !important;">'+
									'<tr style="font-size:12px;padding:0px !important;">'+
										'<th style="width: 65%; padding-top:3px !important;padding-bottom:3px !important;">Customer Name</th>'+
										'<th style="width: 35%; padding-top:3px !important;padding-bottom:3px !important;">MR#</th>'+
									'</tr>'+
								'</thead>'+
				    			'<tbody class="hover_tbody"></tbody>'+
							'</table>'+
						'</div>'+
						'<div> <span class="pull-left" style="margin-left:70px; margin-top:-16px !important; margin-bottom:4px !important; font-size:18px !important; font-weight:bold !important; color:rgba(88, 103, 110, 0.49);"> CLICK FOR DETAILS </span> \</span></div>'+
					'</div>';
	  		$(this).parent(".list-group-item").append(temp);

			$("body").find(".hover_tbody").html('<tr><td colspan="2" style="text-align:center;"> <i class="fa fa-spin fa-spinner"></i></td></tr>');
	  		$.post(base_url+"report/get_activity_status_sample_data/" + data_name +"/"+ date_from +"/"+ date_to +"/"+ selected_hospice,"", function(response){
	  			var obj = $.parseJSON(response);
	  			if(obj.patient_list.length > 0)
	  			{
	  				for(var val in obj.patient_list)
		  			{
		  				table_content += 	'<tr style="text-align:left;font-size:12px !important;">'+
		  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+'</td>'+
		  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].medical_record_id+'</td>'+
		  									'</tr>';
		  			}
	  			}
	  			else
	  			{
	  				table_content += 	'<tr style="text-align:center;font-size:12px !important;">'+
		  										'<td colspan="2" style="padding-top:4px !important;padding-bottom:4px !important;"> No Customer </td>'+
		  								'</tr>';
	  			}
	  			$("body").find(".hover_tbody").html(table_content);
	  			$("body").find(".total_patient_sample_count").html(obj.patient_list.length);
			});
	  	}
	});

	$('body').on('mouseout','.activity_status_link',function(){
  		$("body").find(".hover_sample_div").remove();
	});

	$('body').on('mouseover','.residence_status_link',function(){
		var data_name = $(this).attr("data-name");
		var temp = "";
		var table_content = "";
		var current_time = $("body").find(".current_time_value").val();
		var current_date = $("body").find(".current_date_value").val();
		var date_from = $("body").find("#search_from").val();
		var date_to = $("body").find("#search_to").val();
		var selected_hospice = $("body").find(".filter_reports_by_hospice").val();
		var new_viewed_date = "";

		if(date_from.length > 0 && date_to.length > 0)
		{
			var new_viewed_date = "";
			var month_name = "";
			var separated_from = date_from.split(/\s*\-\s*/g); 
			var separated_to = date_to.split(/\s*\-\s*/g); 

			if(separated_from[1] == 1)
			{
				month_name = "January";
			}
			else if(separated_from[1] == 2)
			{
				month_name = "February";
			}
			else if(separated_from[1] == 3)
			{
				month_name = "March";
			}
			else if(separated_from[1] == 4)
			{
				month_name = "April";
			}
			else if(separated_from[1] == 5)
			{
				month_name = "May";
			}
			else if(separated_from[1] == 6)
			{
				month_name = "June";
			}
			else if(separated_from[1] == 7)
			{
				month_name = "July";
			}
			else if(separated_from[1] == 8)
			{
				month_name = "August";
			}
			else if(separated_from[1] == 9)
			{
				month_name = "September";
			}
			else if(separated_from[1] == 10)
			{
				month_name = "October";
			}
			else if(separated_from[1] == 11)
			{
				month_name = "November";
			}
			else if(separated_from[1] == 12)
			{
				month_name = "December";
			}

			if(separated_to[1] == 1)
			{
				month_name_to = "January";
			}
			else if(separated_to[1] == 2)
			{
				month_name_to = "February";
			}
			else if(separated_to[1] == 3)
			{
				month_name_to = "March";
			}
			else if(separated_to[1] == 4)
			{
				month_name_to = "April";
			}
			else if(separated_to[1] == 5)
			{
				month_name_to = "May";
			}
			else if(separated_to[1] == 6)
			{
				month_name_to = "June";
			}
			else if(separated_to[1] == 7)
			{
				month_name_to = "July";
			}
			else if(separated_to[1] == 8)
			{
				month_name_to = "August";
			}
			else if(separated_to[1] == 9)
			{
				month_name_to = "September";
			}
			else if(separated_to[1] == 10)
			{
				month_name_to = "October";
			}
			else if(separated_to[1] == 11)
			{
				month_name_to = "November";
			}
			else if(separated_to[1] == 12)
			{
				month_name_to = "December";
			}

			if((separated_from[0] == separated_to[0]) && (separated_from[1] == separated_to[1]) && (separated_from[2] == separated_to[2]))
			{
				new_viewed_date = month_name+" "+separated_from[2]+", "+separated_from[0];
			}
			else if((separated_from[0] == separated_to[0]) && (separated_from[1] == separated_to[1]) && (separated_from[2] != separated_to[2]))
			{
				new_viewed_date = month_name+" "+separated_from[2]+" - "+separated_to[2]+", "+separated_from[0];
			}
			else 
			{
				new_viewed_date = month_name+" "+separated_from[2]+", "+separated_from[0]+" - "+month_name_to+" "+separated_to[2]+", "+separated_to[0];
			}
		}
		else
		{
			new_viewed_date = current_date;
		}

		if(data_name == "assisted_living")
	  	{
	  		temp += '<div class="hover_sample_div" style="background-color:#fff; width:300px; color:#58666e !important; position: absolute; z-index: 999; margin-top: -80px; margin-right: -290px; margin-left: 110px; border: 2px solid rgba(33, 32, 32, 0.207843); ">'+
						'<div style="font-size:17px; font-weight:bold; color:rgba(88, 103, 110, 0.49); margin-top:3px !important; margin-bottom:-2px !important;"> PREVIEW</div>'+
						'<div style=""> Assisted Living</div>'+
						'<div style=""> '+new_viewed_date+'</div>'+
						'<div style="width:282px;margin-left:7px; margin-top:3px !important;">'+
							'<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">'+
								'<thead style="background-color:rgba(97, 101, 115, 0.05);height:10px !important;">'+
									'<tr style="font-size:12px;padding:0px !important;">'+
										'<th style="width: 65%; padding-top:3px !important;padding-bottom:3px !important;">Customer Name</th>'+
										'<th style="width: 35%; padding-top:3px !important;padding-bottom:3px !important;">MR#</th>'+
									'</tr>'+
								'</thead>'+
				    			'<tbody class="hover_tbody"></tbody>'+
							'</table>'+
						'</div>'+
						'<div> <span class="pull-left" style="margin-left:70px; margin-top:-16px !important; margin-bottom:4px !important; font-size:18px !important; font-weight:bold !important; color:rgba(88, 103, 110, 0.49);"> CLICK FOR DETAILS </span> </span></div>'+
					'</div>';
	  		$(this).parent(".list-group-item").append(temp);

			$("body").find(".hover_sample_div").find(".hover_tbody").html('<tr><td colspan="2" style="text-align:center;"> <i class="fa fa-spin fa-spinner"></i></td></tr>');
	  		$.post(base_url+"report/get_residence_status_sample_data/" + data_name +"/"+ date_from +"/"+ date_to +"/"+ selected_hospice,"", function(response){
	  			var obj = $.parseJSON(response);
	  			var count = 0;

  				for(var val in obj.patient_list)
	  			{
	  				table_content += 	'<tr style="text-align:left;font-size:12px !important;">'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+'</td>'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].medical_record_id+'</td>'+
	  									'</tr>';
	  				count++;
	  			}
	  			if(count == 0)
	  			{
	  				table_content = "";
	  				table_content += 	'<tr style="text-align:center;font-size:12px !important;">'+
		  										'<td colspan="2" style="padding-top:4px !important;padding-bottom:4px !important;"> No Customer </td>'+
		  								'</tr>';
	  			}
	  			$("body").find(".hover_tbody").html(table_content);
	  			$("body").find(".total_patient_sample_count").html(count);
			});
	  	}
	  	else if(data_name == "group_home")
	  	{
	  		temp += '<div class="hover_sample_div" style="background-color:#fff; width:300px; color:#58666e !important; position: absolute; z-index: 999; margin-top: -80px; margin-right: -290px; margin-left: 110px; border: 2px solid rgba(33, 32, 32, 0.207843); ">'+
						'<div style="font-size:17px; font-weight:bold; color:rgba(88, 103, 110, 0.49); margin-top:3px !important; margin-bottom:-2px !important;"> PREVIEW</div>'+
						'<div style=""> Group Home</div>'+
						'<div style=""> '+new_viewed_date+'</div>'+
						'<div style="width:282px;margin-left:7px; margin-top:3px !important;">'+
							'<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">'+
								'<thead style="background-color:rgba(97, 101, 115, 0.05);height:10px !important;">'+
									'<tr style="font-size:12px;padding:0px !important;">'+
										'<th style="width: 65%; padding-top:3px !important;padding-bottom:3px !important;">Customer Name</th>'+
										'<th style="width: 35%; padding-top:3px !important;padding-bottom:3px !important;">MR#</th>'+
									'</tr>'+
								'</thead>'+
				    			'<tbody class="hover_tbody"></tbody>'+
							'</table>'+
						'</div>'+
						'<div> <span class="pull-left" style="margin-left:70px; margin-top:-16px !important; margin-bottom:4px !important; font-size:18px !important; font-weight:bold !important; color:rgba(88, 103, 110, 0.49);"> CLICK FOR DETAILS </span> </span></div>'+
					'</div>';
	  		$(this).parent(".list-group-item").append(temp);

			$("body").find(".hover_sample_div").find(".hover_tbody").html('<tr><td colspan="2" style="text-align:center;"> <i class="fa fa-spin fa-spinner"></i></td></tr>');
	  		$.post(base_url+"report/get_residence_status_sample_data/" + data_name +"/"+ date_from +"/"+ date_to +"/"+ selected_hospice,"", function(response){
	  			var obj = $.parseJSON(response);
	  			var count = 0;

  				for(var val in obj.patient_list)
	  			{
	  				table_content += 	'<tr style="text-align:left;font-size:12px !important;">'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+'</td>'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].medical_record_id+'</td>'+
	  									'</tr>';
	  				count++;
	  			}
	  			if(count == 0)
	  			{
	  				table_content = "";
	  				table_content += 	'<tr style="text-align:center;font-size:12px !important;">'+
		  										'<td colspan="2" style="padding-top:4px !important;padding-bottom:4px !important;"> No Customer </td>'+
		  								'</tr>';
	  			}
	  			$("body").find(".hover_tbody").html(table_content);
	  			$("body").find(".total_patient_sample_count").html(count);
			});
	  	}
	  	else if(data_name == "hic_home")
	  	{
	  		temp += '<div class="hover_sample_div" style="background-color:#fff; width:300px; color:#58666e !important; position: absolute; z-index: 999; margin-top: -80px; margin-right: -290px; margin-left: 110px; border: 2px solid rgba(33, 32, 32, 0.207843); ">'+
						'<div style="font-size:17px; font-weight:bold; color:rgba(88, 103, 110, 0.49); margin-top:3px !important; margin-bottom:-2px !important;"> PREVIEW</div>'+
						'<div style=""> Hic Home</div>'+
						'<div style=""> '+new_viewed_date+'</div>'+
						'<div style="width:282px;margin-left:7px; margin-top:3px !important;">'+
							'<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">'+
								'<thead style="background-color:rgba(97, 101, 115, 0.05);height:10px !important;">'+
									'<tr style="font-size:12px;padding:0px !important;">'+
										'<th style="width: 65%; padding-top:3px !important;padding-bottom:3px !important;">Customer Name</th>'+
										'<th style="width: 35%; padding-top:3px !important;padding-bottom:3px !important;">MR#</th>'+
									'</tr>'+
								'</thead>'+
				    			'<tbody class="hover_tbody"></tbody>'+
							'</table>'+
						'</div>'+
						'<div> <span class="pull-left" style="margin-left:70px; margin-top:-16px !important; margin-bottom:4px !important; font-size:18px !important; font-weight:bold !important; color:rgba(88, 103, 110, 0.49);"> CLICK FOR DETAILS </span> </span></div>'+
					'</div>';
	  		$(this).parent(".list-group-item").append(temp);

			$("body").find(".hover_sample_div").find(".hover_tbody").html('<tr><td colspan="2" style="text-align:center;"> <i class="fa fa-spin fa-spinner"></i></td></tr>');
	  		$.post(base_url+"report/get_residence_status_sample_data/" + data_name +"/"+ date_from +"/"+ date_to +"/"+ selected_hospice,"", function(response){
	  			var obj = $.parseJSON(response);
	  			var count = 0;

  				for(var val in obj.patient_list)
	  			{
	  				table_content += 	'<tr style="text-align:left;font-size:12px !important;">'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+'</td>'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].medical_record_id+'</td>'+
	  									'</tr>';
	  				count++;
	  			}
	  			if(count == 0)
	  			{
	  				table_content = "";
	  				table_content += 	'<tr style="text-align:center;font-size:12px !important;">'+
		  										'<td colspan="2" style="padding-top:4px !important;padding-bottom:4px !important;"> No Customer </td>'+
		  								'</tr>';
	  			}
	  			$("body").find(".hover_tbody").html(table_content);
	  			$("body").find(".total_patient_sample_count").html(count);
			});
	  	}
	  	else if(data_name == "home_care")
	  	{
	  		temp += '<div class="hover_sample_div" style="background-color:#fff; width:300px; color:#58666e !important; position: absolute; z-index: 999; margin-top: -80px; margin-right: -290px; margin-left: 110px; border: 2px solid rgba(33, 32, 32, 0.207843); ">'+
						'<div style="font-size:17px; font-weight:bold; color:rgba(88, 103, 110, 0.49); margin-top:3px !important; margin-bottom:-2px !important;"> PREVIEW</div>'+
						'<div style=""> Home Care</div>'+
						'<div style=""> '+new_viewed_date+'</div>'+
						'<div style="width:282px;margin-left:7px; margin-top:3px !important;">'+
							'<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">'+
								'<thead style="background-color:rgba(97, 101, 115, 0.05);height:10px !important;">'+
									'<tr style="font-size:12px;padding:0px !important;">'+
										'<th style="width: 65%; padding-top:3px !important;padding-bottom:3px !important;">Customer Name</th>'+
										'<th style="width: 35%; padding-top:3px !important;padding-bottom:3px !important;">MR#</th>'+
									'</tr>'+
								'</thead>'+
				    			'<tbody class="hover_tbody"></tbody>'+
							'</table>'+
						'</div>'+
						'<div> <span class="pull-left" style="margin-left:70px; margin-top:-16px !important; margin-bottom:4px !important; font-size:18px !important; font-weight:bold !important; color:rgba(88, 103, 110, 0.49);"> CLICK FOR DETAILS </span> </span></div>'+
					'</div>';
	  		$(this).parent(".list-group-item").append(temp);

			$("body").find(".hover_sample_div").find(".hover_tbody").html('<tr><td colspan="2" style="text-align:center;"> <i class="fa fa-spin fa-spinner"></i></td></tr>');
	  		$.post(base_url+"report/get_residence_status_sample_data/" + data_name +"/"+ date_from +"/"+ date_to +"/"+ selected_hospice,"", function(response){
	  			var obj = $.parseJSON(response);
	  			var count = 0;

  				for(var val in obj.patient_list)
	  			{
	  				table_content += 	'<tr style="text-align:left;font-size:12px !important;">'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+'</td>'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].medical_record_id+'</td>'+
	  									'</tr>';
	  				count++;
	  			}
	  			if(count == 0)
	  			{
	  				table_content = "";
	  				table_content += 	'<tr style="text-align:center;font-size:12px !important;">'+
		  										'<td colspan="2" style="padding-top:4px !important;padding-bottom:4px !important;"> No Customer </td>'+
		  								'</tr>';
	  			}
	  			$("body").find(".hover_tbody").html(table_content);
	  			$("body").find(".total_patient_sample_count").html(count);
			});
	  	}
	  	else
	  	{
	  		temp += '<div class="hover_sample_div" style="background-color:#fff; width:300px; color:#58666e !important; position: absolute; z-index: 999; margin-top: -80px; margin-right: -290px; margin-left: 110px; border: 2px solid rgba(33, 32, 32, 0.207843); ">'+
						'<div style="font-size:17px; font-weight:bold; color:rgba(88, 103, 110, 0.49); margin-top:3px !important; margin-bottom:-2px !important;"> PREVIEW</div>'+
						'<div style=""> Skilled Nursing Facility</div>'+
						'<div style=""> '+new_viewed_date+'</div>'+
						'<div style="width:282px;margin-left:7px; margin-top:3px !important;">'+
							'<table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px;">'+
								'<thead style="background-color:rgba(97, 101, 115, 0.05);height:10px !important;">'+
									'<tr style="font-size:12px;padding:0px !important;">'+
										'<th style="width: 65%; padding-top:3px !important;padding-bottom:3px !important;">Customer Name</th>'+
										'<th style="width: 35%; padding-top:3px !important;padding-bottom:3px !important;">MR#</th>'+
									'</tr>'+
								'</thead>'+
				    			'<tbody class="hover_tbody"></tbody>'+
							'</table>'+
						'</div>'+
						'<div> <span class="pull-left" style="margin-left:70px; margin-top:-16px !important; margin-bottom:4px !important; font-size:18px !important; font-weight:bold !important; color:rgba(88, 103, 110, 0.49);"> CLICK FOR DETAILS </span> </span></div>'+
					'</div>';
	  		$(this).parent(".list-group-item").append(temp);

			$("body").find(".hover_sample_div").find(".hover_tbody").html('<tr><td colspan="2" style="text-align:center;"> <i class="fa fa-spin fa-spinner"></i></td></tr>');
	  		$.post(base_url+"report/get_residence_status_sample_data/" + data_name +"/"+ date_from +"/"+ date_to +"/"+ selected_hospice,"", function(response){
	  			var obj = $.parseJSON(response);
	  			var count = 0;

  				for(var val in obj.patient_list)
	  			{
	  				table_content += 	'<tr style="text-align:left;font-size:12px !important;">'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].p_fname+' '+obj.patient_list[val].p_lname+'</td>'+
	  										'<td style="padding-top:4px !important;padding-bottom:4px !important;">'+obj.patient_list[val].medical_record_id+'</td>'+
	  									'</tr>';
	  				count++;
	  			}
	  			if(count == 0)
	  			{
	  				table_content = "";
	  				table_content += 	'<tr style="text-align:center;font-size:12px !important;">'+
		  										'<td colspan="2" style="padding-top:4px !important;padding-bottom:4px !important;"> No Customer </td>'+
		  								'</tr>';
	  			}
	  			$("body").find(".hover_tbody").html(table_content);
	  			$("body").find(".total_patient_sample_count").html(count);
			});
	  	}
	});

	$('body').on('mouseout','.residence_status_link',function(){
  		$("body").find(".hover_sample_div").remove();
	});

});