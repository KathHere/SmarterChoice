<style type="text/css">

input[type="search"]
{
  margin-left: 13px;
}

select.input-sm
{
  margin-left: 11px;
  margin-right: 11px;
}

.selectAllCheckBoxHeader
{
	padding-bottom: 0px !important;
	padding-left: 13px !important;
}

.canceled_work_orders_list_div
{
  position: relative;
}

.dataTables_wrapper .dataTables_processing {
  background: #bfbfbff5 !important;
  background-color: #bfbfbff5 !important;
  color:#fff !important;
  height: 60px !important;
}

</style>

<div class="bg-light lter b-b wrapper-md">
	<h1 class="m-n font-thin h3">
		Canceled Orders
		<div class="pull-right">
			<a href="javascript:void(0)" class="delete-trash">
				<button class="btn btn-danger btn-sm delete-permanently-button" style="font-size:13px !important;" disabled>
					 <i class="glyphicon glyphicon-trash"></i> &nbsp; Delete Permanently
				</button>
			</a>
		</div>
	</h1>
</div>

<div class="wrapper-md">
  	<div class="panel panel-default">
	    <div class="panel-heading">
	  		Canceled Orders
	    </div>

	 	<div class="table-responsive canceled_work_orders_list_div">

			<table class="table table-hover canceled_orders_table">
				<thead>
					<tr>
						<th class="selectAllCheckBoxHeader"><h5> <label class="i-checks"><input type="checkbox" class="form-control all-delete-work-order-permanently" value="" /><i></i></label></h5></th>
						<th class=""><h5> Medical Record Number</h5></th>
						<th class=""><h5> Customer Last Name</h5></th>
						<th class=""><h5> Customer First Name</h5></th>
						<th class=""><h5> Canceled By</h5></th>
						<th class=""><h5> Date Deleted</h5></th>
						<th class=""><h5> Work Order/ WO#</h5></th>
					</tr>
				</thead>
				<tbody class="canceled_orders_tbody">
				</tbody>

			</table>
		</div>
  	</div>
</div>

<script type="text/javascript">

	$(document).ready(function(){

		var datatable = $('.canceled_orders_table').DataTable({
            "language": {
               "processing": "Retrieving data. Please wait..."
            },
            "lengthMenu": [10,25,50,75,100,200,300,500],
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "deferRender": true,
            "ajax": {
                url: base_url+"canceled_order/get_canceled_orders"
            },
            "columns": [
                { "data": "select_work_order" },
                { "data": "medical_record_id" },
                { "data": "customer_last_name" },
                { "data": "customer_first_name" },
                { "data": "customer_canceled_by" },
                { "data": "date_deleted" },
                { "data": "work_order" }
            ],
            "order": [[ 5, 'desc' ]],
            "columnDefs":[
                {
                    "targets": 0,
                    "searchable": false,
                    "orderable": false
                },
                {
                    "targets": 6,
                    "searchable": false,
                    "orderable": false
                }
            ]
        });
		$.fn.dataTable.ext.errMode = 'none';

		$('.canceled_orders_table').on('click','.delete-work-order-permanently',function(){
	      	var this_element = $(this);
	      	var value = $(this).val();
	      	var _this = $('.delete-work-order-permanently:checked');

	      	if(this_element.is(':checked'))
			{
				$("body").find(".delete-permanently-button").removeAttr('disabled');
			}
      		else
      		{
      			var _this = $('.delete-work-order-permanently:checked');
      			if (_this.length == 0) {
      				$("body").find(".delete-permanently-button").attr('disabled', true);
      			}
      		}
   		});

   		$('.canceled_orders_table').on('click','.all-delete-work-order-permanently',function(){
   			var work_orders = $('.delete-work-order-permanently');

   			if($(this).is(':checked'))
			{
				work_orders.each(function(){
					$(this).prop('checked', true);
					$("body").find(".delete-permanently-button").removeAttr('disabled');
			    });
			}
      		else
      		{
      			work_orders.each(function(){
      				$(this).prop('checked', false);
      				$("body").find(".delete-permanently-button").attr('disabled', true);
			    });
      		}
   		});

   		$('body').on('click','.delete-permanently-button',function(){
   			var _this = $('.delete-work-order-permanently:checked');

   			jConfirm('Delete selected work order(s) permanently. <br />You want to proceed?','Note', function(response){
		        if(response)
		        {
	          		var count  = 0;
		   			_this.each(function(){
				      	var inside_value = $(this).val();

			          	$.post(base_url + "order/delete_trash/" + inside_value, function(response){

			              	var obj = $.parseJSON(response);
			              	jAlert(obj['message'],'Delete Response');

			              	if(obj['error']==0)
			              	{
			              		count++;
			              		if (count == _this.length) {
			              			location.reload();
			              		}
			              	}
			          	});
				    });
		        }
	      	});
   		});

   		$('body').on('click','.canceled_orders_table .view_order_details',function(){
		  	var medical_record_id = $(this).attr('data-id');
		  	var hospice_id        = $(this).attr('data-value');
		  	var unique_id         = $(this).attr('data-unique-id');
		  	var act_id            = $(this).attr('data-act-id');
		  	var patient_id        = $(this).attr('data-patient-id');

		  	if(act_id != 3)
		  	{
			    modalbox(base_url + 'order/view_order_status_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id+ "/" + patient_id,{
			        header:"Work Order",
			        button: false,
			    });
		  	}
		  	else
		  	{
			    modalbox(base_url + 'order/view_exchange_order_details/' + medical_record_id + "/" + hospice_id + "/" + unique_id + "/" + act_id+ "/" + patient_id,{
			      header:"Work Order",
			      button: false
			    });
		  	}
		  	return false;
		});

	});

</script>

