<style type="text/css">
    @media print {
        @page {
            margin: 0mm 2mm 10mm 2mm;
        }
        
        #DataTables_Table_0_wrapper .bootstrap-dt-container {
            display: none !important;
        }

        .location_container {
            display: block !important;
        }

        .sample_div {
            margin-top: -45px !important;
        }
    }

    /*=======================================Printer Loader==============================*/
    .loader {
      border: 5px solid #f3f3f3;
      border-radius: 50%;
      border-top: 5px solid #3498db;
      width: 35px;
      height: 35px;
      -webkit-animation: spin 2s linear infinite; /* Safari */
      animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
      0% { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .printer_loader_icon {
        position: absolute;
        margin: auto;
        top: 0;
        width: 15px;
        height: 15px;
        bottom: 0;
        right: 0;
        left: 0;
        z-index: 9;
        font-size: 15px;
    }
    .loader-icon {
        position: relative;
        height: 35px;
        margin-top: 20px;
        /*line-height: 1;*/
        width: 35px;
    }

    .printer_loaded_icon {
        /*font-size: 20px;*/
    }

    .loaded-icon {
        border-radius: 50%;
        margin-top: 22px;
        display: none;
    }

    .popover {
        font-weight: bold;
        font-size: 10px !important;
    }
    /*=======================================Printer Loader==============================*/

    .location_container {
        /*position: absolute;*/
        /*margin-top: -10px;*/
        margin-left: 25px;
        font-size: 10px;
        /*top: 0;*/
        left: 0;
        display: none;
    }

    .dataTables_wrapper .dataTables_processing {
        background: #bfbfbff5 !important;
        background-color: #bfbfbff5 !important;
        color:#fff !important;
        height: 60px !important;
        z-index: 99999;
    }
</style>
<div class="sample_div"></div>
<div class="location_container">
    <strong>Date:</strong>  <?php echo date("m/d/Y"); ?>
    </br>
    <strong>Location:</strong>
    <?php
        $location = get_login_location($this->session->userdata('user_location'));
    ?>
    <span class="location_span">
    <?php
        echo $location['user_city'].', '.$location['user_state'];
    ?>
    </span>
</div>
<div class="statement_draft_container">
   <div class="bg-light lter b-b wrapper-md">
        <h1 class="m-n font-thin h3">
            Draft Statements
            <div class="pull-right">
                <button class="btn btn-info btn-sm create-invoice-btn" style="font-size:13px !important;" disabled>
                    Create Invoice
                </button>
            </div>
        </h1>
        
    </div>

    <div class="wrapper-md" style="min-height: 75vh">
        <div class="panel panel-default">
            <div class="panel-heading">
                Draft Statements
            </div>
            <div class="panel-body" style="padding: 0px; position: relative !important;">
                <table class="table table-hover bg-white b-a datatable_table_statement_draft">
                    <thead >
                        <th class="hidden-print" style="text-align: center; padding-bottom: 0px !important; padding-left: 18px !important;">
                            <!-- <label class="i-checks"><input type="checkbox" class="form-control all-create-invoice" value="" /><i></i></label> -->
                        </th>
                        <th style="text-align: center;">Account Name</th>
                        <th style="text-align: center;">Service Date</th>
                        <th style="text-align: center;">Statement Number</th>
                        <!-- <th style="text-align: center;">Balance Due</th> -->
                        <th class="hidden-print" style="text-align: center;">Actions</th>
                    </thead>
                    <tbody style="text-align: center !important">
                       
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-light lter wrapper-md">
       <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
    </div>
 
</div>

<div class="modal fade" id="loader_modal" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 600px !important;">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
            <div class="modal-header">
                <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reminder &nbsp;&nbsp; - &nbsp;&nbsp; <span style="font-weight: bold;" id="hospice_name_header"></span></h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
                <div style="text-align: center; margin: 20px"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                <div id="loading_more" style="text-align:center;font-size:18px; margin: 30px">Creating Invoice... </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default btn-create-invoice-close pull-left">Cancel</button>
                <button type="button" class="btn btn-primary btn-order save_floor_mat_capped">Save changes</button>
            </div> -->
        </div>
    </div>
</div>

<script type="text/javascript">
    
	$(document).ready(function(){
        var global_create_invoice = null;

        $('#loader_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#loader_modal').modal('hide');
		$('.datatable_table_statement_draft').DataTable( {
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
                url: base_url+"billing_statement/get_statement_draft"
            },
            "columns": [
                { "data": "checkbox" },
                { "data": "hospice_name" },
                { "data": "service_date" },
                { "data": "statement_no" },
                // { "data": "balance_due" },
                { "data": "action" }
            ],
	        "order": [[ 1, "asc" ]],
            "columnDefs":[
                {
                    "targets": 0,
                    "searchable": false,
                    "orderable": false
                },
                {
                    "targets": 4,
                    "searchable": false,
                    "orderable": false
                }
            ]
	    } );

        // View statement bill Details
        $('body').on('click','.view_statement_bill_details',function(){
            var _this = $(this);
            var data_draft_id = $(this).attr("data-draft-id");
            var data_hospice_id = $(this).attr("data-hospice-id");
            // var order_req_id = $(this).attr("data-order-req-id");
            // var req_receiving_batch_no = $(this).data("req-receive-batch-no");

            var header_logo = "Account Statement Details";

            //Printer Loader
            header_logo += '<div class="loader-icon-container">';
            header_logo += '<div class="loader-icon pull-right" style="margin-top: -26px !important; margin-right: 50px;">';
            header_logo += '<i class="printer_loader_icon fa fa-print"></i>';
            header_logo += '<div class="loader"></div>';
            header_logo += '</div>';
            header_logo += '<span class="retrieving_data pull-right" style="margin-top: -17px !important;" >Retrieving Data...</span>';
            header_logo += '</div>';
            header_logo += '<button class="btn btn-default loaded-icon pull-right" rel="popover" data-html="true" data-toggle="popover" data-trigger="hover" data-replacement="left" data-content="Print" onclick="window.print()" style="margin-top: -5px !important; margin-right: 50px;">';
            header_logo += '<i class="printer_loaded_icon fa fa-print"></i>';
            header_logo += '</button>';
            header_logo += '';
            modalbox(base_url + 'billing_statement/billing_statement_details/'+data_draft_id+'/'+data_hospice_id,{
                header:header_logo,
                button: false,
                width: 1200
            });
        });

        $('body').on('click','.cancel_draft_btn',function(){
            var data_hospice_id = $(this).attr("data-hospice-id");
            console.log('CANCEL DRAFT');
            jConfirm("Cancel draft statement?","Reminder",function(response){
                if(response)
                {
                    $.get(base_url+'billing_statement/merge_draft_statement/'+data_hospice_id, function(response){
                        var obj = $.parseJSON(response);

                        if(obj['error'] == 0)
                        {
                            me_message_v2({error:0,message:obj['message']});
                            setTimeout(function(){
                                location.reload();
                            },2000);
                        } else {
                            me_message_v2({error:1,message:"Error!"});
                            // setTimeout(function(){
                            //     location.reload();
                            // },2000);
                        }
                    });
                }
            });
        });

        //SELECT ALL CHECKBOXES
        $('.datatable_table_statement_draft').on('click','.all-create-invoice',function(){
            var statement_bill = $('.select_all_create_invoice');
            console.log("gwapogawpgoawpoawpgoawp");
            if($(this).is(':checked'))
            {
                statement_bill.each(function(){
                    $(this).prop('checked', true);
                    $("body").find(".create-invoice-btn").removeAttr('disabled');
                });
            }
            else
            {
                statement_bill.each(function(){
                    $(this).prop('checked', false);
                    $("body").find(".create-invoice-btn").attr('disabled', true);
                });
            }
        });

        $('.datatable_table_statement_draft').on('click','.create-invoice',function(){
            var this_element = $(this);
            var value = $(this).val();
            // var _this = $('.create-invoice:checked');

            if(this_element.is(':checked'))
            {
                $("body").find(".create-invoice-btn").removeAttr('disabled');
            }
            else
            {
                var _this = $('.create-invoice:checked');
                if (_this.length == 0) {
                    $("body").find(".create-invoice-btn").attr('disabled', true);
                }
            }

            var statement_bill = $('.select_all_create_invoice');

            statement_bill.each(function(){
                $(this).prop('checked', false);
            });

            this_element.prop('checked', true);
        });

        //create-invoice-action by action button
        $('.create-invoice-action').bind('click',function(){
            var draft_id = $(this).attr("data-draft-id");

            jConfirm("Are you sure you want to create an invoice?","Reminder",function(response){
                if(response)
                {
                    me_message_v2({error:2,message:"Saving data..."});
                    $.get(base_url+'billing_statement/create_invoice/'+draft_id, function(response){
                        console.log("resepposnse:", response);
                        var obj = $.parseJSON(response);
                        console.log("closecloseclose:", obj);
                        // $('.close').click();
                        
                        setTimeout(function(){
                            if(obj['error'] == 0)
                            {
                                me_message_v2({error:0,message:obj['message']});
                                setTimeout(function(){
                                    location.reload();
                                },2000);
                                console.log("closecloseclose_success");
                            } else {
                                me_message_v2({error:1,message:"Error!"});
                                // setTimeout(function(){
                                //     location.reload();
                                // },2000);
                                console.log("closecloseclose_error");
                            }
                        },1);
                        
                    });
                }
            });
        });

        // Cancel Creating of Invoice
        $('.btn-create-invoice-close').bind('click',function(){
            global_create_invoice.abort();
        });

        //create invoice by checkboxes
        $('.create-invoice-btn').bind('click',function(){
            // var draft_id = $(this).attr("data-draft-id");
            $("body").find(".create-invoice-btn").attr('disabled', true);
            jConfirm("Create Invoice?","Reminder",function(response){
                if(response)
                {
                    
                    // me_message_v2({error:2,message:"Saving data. Please wait..."});
                    $('#loader_modal').modal('show');
                    //hospice_name_header
                    var hospice_name = '';
                    var selected_invoices = $('.create-invoice');
                    var selected_inv = "";
                    var counter = 0;
                    var prev_inv = "";
                    selected_invoices.each(function(){
                        if($(this).is(':checked')) {
                            if(counter == 0) {
                                selected_inv = $(this).attr('data-draft-id');
                                hospice_name = $(this).attr('data-hospice-name');
                            } else {
                                if (prev_inv != $(this).attr('data-draft-id')) {
                                    selected_inv = selected_inv + "-" + $(this).attr('data-draft-id');
                                }
                            }
                            prev_inv = $(this).attr('data-draft-id');
                            counter++;
                        }
                    });
                    console.log('hospice_name_header', hospice_name);
                    $('#hospice_name_header').html(hospice_name);
                    console.log('selected_inv: ', selected_inv);
                    global_create_invoice = $.get(base_url+'billing_statement/create_invoice_v2/'+selected_inv, function(response){
                        console.log("resepposnse:", response);
                        var obj = $.parseJSON(response);
                        console.log("closecloseclose:", obj);
                        // $('.close').click();
                        
                        setTimeout(function(){
                            if(obj['error'] == 0)
                            {
                                // me_message_v2({error:0,message:obj['message']});
                                setTimeout(function(){
                                    location.reload();
                                },2000);
                                console.log("closecloseclose_success");
                            } else {
                                // me_message_v2({error:1,message:"Error!"});
                                jAlert(obj['message'],'Reminder', function() {
                                    if(response)
                                    {
                                        $('#loader_modal').modal('hide');
                                        setTimeout(function(){
                                            location.reload();
                                        },2000);
                                    }
                                });
                                
                                console.log("closecloseclose_error");
                            }
                        },1);
                        
                    });
                } else {
                    $("body").find(".create-invoice-btn").removeAttr('disabled');
                }
            });
        });
	});
    
</script>
