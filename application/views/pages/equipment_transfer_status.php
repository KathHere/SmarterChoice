<script src="<?php echo base_url(); ?>assets/js/inventory.js"></script>

<style type="text/css">
	.purchase_order_inquiry_info
	{
		font-size: 15px !important;
	}
	.panel-body
	{
		padding-left: 0px!important;
		padding-right: 0px!important;
	}
	.first_table_td
	{
		padding-left: 25px !important;
	}
	.view_transfer_order
	{
		cursor: pointer;
	}
	.change_order_req_status
	{
		width:145px !important;
	}
	.equipment_status_td
	{
		text-align:center;
	}

</style>

<div class="bg-light lter b-b wrapper-md hidden-print">
  <h1 class="m-n font-thin h3">Equipment Transfer Status</h1>
</div>

<div class="wrapper-md hidden-print">
  	<div class="panel panel-default">
    	<div class="panel-heading">
        Equipment Status
    	</div>
    	<div class="panel-body" style="overflow-x:auto;">
    		<?php
                if (!empty($equip_transfer_inquiries)) {
                    ?>
    				<table class="table m-b-none datatable_table_equip_transfer_inquiry text-center" style="min-width: 900px !important;">
    		<?php
                } else {
                    ?>
    				<table class="table m-b-none text-center" style="min-width: 900px !important;">
    		<?php
                }
            ?>
		       	<thead>
		          	<tr>
                  <th style="text-align:center;">Order Date</th>
                  <th style="text-align:center;">Receiving Branch</th>
			            <th style="text-align:center;">Transfer PO No.</th>
			            <th style="text-align:center;">Order Status</th>
			            <th style="text-align:center;">Receive Date</th>
			            <th style="text-align:center;">Actions</th>
		          	</tr>
		        </thead>
		        <tbody class="">

		        </tbody>
		    </table>
    	</div>
    </div>

</div>

<script type="text/javascript">

	$(document).ready(function(){

		$('.datatable_table_equip_transfer_inquiry').DataTable( {
			"lengthMenu": [10,25,50,75,100,200,300,500],
		    "pageLength": 10,
		    "processing": true,
		    "serverSide": true,
		    "responsive": true,
		    "deferRender": true,
		    "ajax": {
		        url: base_url+"inventory/get_equip_transfer_status_list"
		    },
		    "columns": [
		        { "data": "order_date" },
		        { "data": "receiving_branch" },
		        { "data": "transfer_no" },
		        { "data": "order_status" },
		        { "data": "receive_date" },
		        { "data": "cancel_button" }
		    ],
	        "order": [[ 0, "desc" ]]
	    } );

		// View Order Requisition Details
		$('body').on('click','.view_transfer_order',function(){
			var _this = $(this);
			var transfer_order_no = $(this).attr("data-transfer-order-no");
			var transfer_req_id = $(this).attr("data-transfer-req-id");

			
			modalbox(base_url + 'inventory/equipment_transfer_requisition_details/'+ transfer_req_id + "/"+ transfer_order_no,{
		        header:"Equipment Transfer Requisition",
		        button: false,
		    });
		    
		});

		//cancel_transfer_req
		$('body').on('click','.cancel_transfer_req',function(){
			var _this = $(this);
			var transfer_order_no = $(this).attr("data-transfer-order-no");
			var transfer_req_id = $(this).attr("data-transfer-req-id");

			jConfirm("Cancel Equipment Transfer Requisition?","Reminder",function(response){
				if(response) {
					$(this).prop("disabled","true");
					$.post(base_url+"inventory/cancel_equipment_transfer/" + transfer_req_id + "/"+ transfer_order_no, function(response){

						var obj = $.parseJSON(response);
						jAlert(obj['message'],"Reminder");
						if(obj['error'] == 0)
						{
								setTimeout(function(){
								location.reload();
							},2000);
						}
					});
				}
			});

		});

		// View Order Requisition Receiving Details
		$('body').on('click','.view_req_receiving_info',function(){
			var _this = $(this);
			var purchase_order_no = $(this).attr("data-purchase-order-no");
			var order_req_id = $(this).attr("data-order-req-id");
			var req_receiving_batch_no = $(this).data("req-receive-batch-no");

			modalbox(base_url + 'inventory/order_req_receiving_details/'+ purchase_order_no + "/"+ order_req_id +"/"+ req_receiving_batch_no,{
	            header:"Purchase Order Requisition Receiving Details",
	            button: false,
	        });
		});

		//change purchase order requisition receive status
	  	$('.datatable_table_purchase_order_inquiry').on('change','.change_order_req_status',function(){
	  		var _value = $(this).closest("tr").find("td").find("select").val();
	  		var purchase_order_no = $(this).closest("tr").find("td").find("select").attr("data-purchase-order-no");
	  		var order_req_id = $(this).closest("tr").find("td").find("select").data("order-req-id");

  			jConfirm("Change Purchase Order Requisition Status?","Warning", function(response){
		        if(response)
		        {
		        	$.post(base_url+"inventory/change_order_req_status/"+ _value + "/"+ purchase_order_no +"/"+ order_req_id +"/",'', function(response){
		                var obj = $.parseJSON(response);
		                if(obj)
		                {
		                	me_message_v2({error:0,message:"Order Requisition Status Updated."});
		                	if(_value == "cancelled")
		                	{
		                		setTimeout(function(){
			                        location.reload();
			                    },1200);
		                	}
		                }
		                else
		                {
		                	me_message_v2({error:0,message:"Error Updating Order Requisition Status."});
		                }
		            });
		        }
	        });
	  	});

	  	// Receive Order Requisition
	  	$('body').on('click','.receive_order_req',function(){
	  		var _this = $(this);
	  		var purchase_order_no = $(this).attr("data-purchase-order-no");
			var order_req_id = $(this).attr("data-order-req-id");
			var req_receive_batch_no = $(this).data('req-receive-batch-no');

			jConfirm("Receive Order Requisition?","Warning", function(response){
		        if(response)
		        {
		        	modalbox(base_url + 'inventory/purchase_order_requisition_receiving/'+ purchase_order_no +"/"+ order_req_id +"/"+ req_receive_batch_no,{
		                header:"Purchase Order Requisition Receiving",
		                button: false
		            });

		            $('body').on('click','.close_order_req_receiving',function(){
		            	_this.prop('checked', false);
		            });
		        }
		        else
		        {
		        	_this.prop('checked', false);
		        }
	        });
	  	});

	});

</script>


