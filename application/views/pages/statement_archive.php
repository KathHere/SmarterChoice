<style type="text/css">
    .address-style {
        font-weight: bold;
    }
    .statement_letter_label_tag {
        font-weight: bold;
        margin-right: 6px;
    }

    .year_header {
        font-weight: bold;
    }

    @media print {
        #DataTables_Table_0_wrapper .bootstrap-dt-container {
            display: none !important;
        }

        @page {
            size: portrait;
            margin: 0mm 2mm 10mm 2mm;
        }

        .archive_container {
            font-size: 7px !important;
        }

        .throw {
            padding: 0px !important;
        }

        .location_container {
            display: block !important;
        }

        .sample_div {
            margin-top: -45px !important;
        }
    }

    .location_container {
        /*position: absolute;*/
        /*margin-top: -10px;*/
        margin-left: 25px;
        font-size: 10px;
        /*top: 0;*/
        left: 0;
        display: none;
    }
</style>


<div class="archive_container">
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
    <div class="bg-light lter b-b wrapper-md hidden-print">
        <h1 class="m-n font-thin h3">
            Archive
        </h1>
    </div>

    <div class="wrapper-md pb0 hidden-print row">
        <div class="col-md-6">
            <div class="form-group clearfix">
            <div class="col-sm-6" style="padding-left:5px;">
                <strong class="purchase_order_inquiry_info" >Company</strong>: <span class="purchase_order_inquiry_info" > Advantage Home Medical Services</span>
            </div>
        </div>
        <div class="form-group clearfix" style="margin-bottom:5px !important;">
            <div class="col-sm-6" style="padding-left:5px;">
                <?php 
                    $location = get_login_location($this->session->userdata('user_location')); 
                ?>
                <strong class="purchase_order_inquiry_info" >Location</strong>: <span class="purchase_order_inquiry_info location_info" > <?php echo $location['user_city'].", ".$location['user_state']; ?> </span>
            </div>
        </div>
        </div>
        <div class="col-md-6">
            <?php 
                $date_today = date("Y-m-d");
                $year_counter = 1;
            ?>
            <select class="form-control pull-right select_year" style="width: 300px">
                <option value="<?php echo date("Y")?>"><?php echo date("Y")?></option>
                <option value="<?php echo date("Y", strtotime($date_today.' -'.$year_counter.' year')); ?>"><?php echo date("Y", strtotime($date_today.' -'.$year_counter.' year'));  $year_counter++;?></option>
                <option value="<?php echo date("Y", strtotime($date_today.' -'.$year_counter.' year')); ?>"><?php echo date("Y", strtotime($date_today.' -'.$year_counter.' year'));  $year_counter++;?></option>
                <option value="<?php echo date("Y", strtotime($date_today.' -'.$year_counter.' year')); ?>"><?php echo date("Y", strtotime($date_today.' -'.$year_counter.' year'));  $year_counter++;?></option>
                <option value="<?php echo date("Y", strtotime($date_today.' -'.$year_counter.' year')); ?>"><?php echo date("Y", strtotime($date_today.' -'.$year_counter.' year'));  $year_counter++;?></option>
            </select>
            <span class="pull-right" style="font-weight: bold; margin-right: 10px; margin-top: 5px">
                Year:
            </span>
                
        </div>
        
    </div>

        

    <div class="wrapper-md">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <span class="col-md-6">Payment History List</span>
                    <span class="col-md-6 year_header">2019</span>
                </div>
                
            </div>
            <div class="panel-body" style="padding: 0px; ">
                <table class="table table-hover bg-white b-a datatable_table_statement_archive">
                    <thead >
                        <th class="throw" style="text-align: center;">Invoice Date</th>
                        <th class="throw" style="text-align: center;">Due Date</th>
                        <th class="throw" style="text-align: center;">Invoice Number</th>
                        <th class="throw" style="text-align: center;">Account Number</th>
                        <th class="throw" style="text-align: center;">Account Name</th>
                        <th class="throw" style="text-align: center;">Balance Due</th>
                        <th class="throw" style="text-align: center;">Payment Amount</th>
                        <th class="throw" style="text-align: center;">Payment Type</th>
                        <th class="throw" style="text-align: center;">Check Number</th>
                        <th class="throw" style="text-align: center;">Receive Date</th>
                        <th class="throw" style="text-align: center;">Received By</th>
                    </thead>
                    <tbody class="tbody_archive">
                        <tr style="text-align: center">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><i class="fa fa-spin fa-spinner item_decription_spinner" style="padding: 100px; font-size: 50px; text-align: center;"></i></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>               
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="bg-light lter wrapper-md hidden-print">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<script type="text/javascript">
    var payment_archive_content = function(){
        var _this = $('.select_year');
        $('.year_header').html(_this.val());
        $.get(base_url+'billing_reconciliation/statement_archive_on_change_year/'+_this.val(), function(response){
            console.log("resepposnse:", response);
            var obj = $.parseJSON(response);
            console.log('year:', _this.val());
            setTimeout(function(){
                var temp_html = "";

                var et_datatable = $(".datatable_table_statement_archive").dataTable();
                et_datatable.fnDestroy();

                for(var i = 0; i < obj.statement_paid_invoices.length; i++) {
                    var date = new Date(obj.statement_paid_invoices[i].due_date);
                    var year = "";
                    var month = "";
                    year = date.getFullYear();
                    if(date.getMonth() < 10) {
                        month = "0"+date.getMonth();
                    } else {
                        month = date.getMonth();
                    }
                    var day = "";
                    if((date.getDate()) < 10) {
                        day = "0"+(date.getDate());
                    } else {
                        day = date.getDate();
                    }

                    var inv_date = new Date(obj.statement_paid_invoices[i].invoice_date);
                    var inv_year = "";
                    var inv_month = "";
                    inv_year = date.getFullYear();
                    if(inv_date.getMonth() < 10) {
                        inv_month = "0"+inv_date.getMonth();
                    } else {
                        inv_month = inv_date.getMonth();
                    }
                    var inv_day = "";
                    if((inv_date.getDate()) < 10) {
                        inv_day = "0"+(inv_date.getDate());
                    } else {
                        inv_day = inv_date.getDate();
                    }

                    var rec_date = new Date(obj.statement_paid_invoices[i].receive_date);
                    var rec_year = "";
                    var rec_month = "";
                    rec_year = date.getFullYear();
                    if(rec_date.getMonth() < 10) {
                        rec_month = "0"+rec_date.getMonth();
                    } else {
                        rec_month = rec_date.getMonth();
                    }
                    var rec_day = "";
                    if((rec_date.getDate()) < 10) {
                        rec_day = "0"+(rec_date.getDate());
                    } else {
                        rec_day = rec_date.getDate();
                    }

                    var new_date = month+"/"+day+"/"+year;
                    var new_inv_date = inv_month+"/"+inv_day+"/"+inv_year;
                    var new_rec_date = rec_month+"/"+rec_day+"/"+rec_year;

                    if(obj.statement_paid_invoices[i].invoice_date == "0000-00-00 00:00:00" || obj.statement_paid_invoices[i].invoice_date == null) {
                        new_inv_date = "";
                    }
                    if(obj.statement_paid_invoices[i].receive_date == "0000-00-00 00:00:00" || obj.statement_paid_invoices[i].receive_date == null) {
                        new_date = "";
                    }

                    var temp_inv_number = obj.statement_paid_invoices[i].statement_no;
                    var payment_type = "";  
                    if(obj.statement_paid_invoices[i].payment_type == "credit_card") {
                        payment_type = "Credit Card";
                    }
                    if(obj.statement_paid_invoices[i].payment_type == "check") {
                        payment_type = "Check";
                    }
                    temp_html += '<tr>';
                    temp_html += '<td>'+new_inv_date+'</td>';
                    temp_html += '<td>'+new_date+'</td>';
                    temp_html += '<td><div style="cursor: pointer" class="view_invoice_details" data-invoice-id="'+obj.statement_paid_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_paid_invoices[i].hospiceID+'">'+temp_inv_number.substr(3, 7)+'</div></td>';
                    temp_html += '<td>'+obj.statement_paid_invoices[i].hospice_account_number+'</td>';
                    temp_html += '<td>'+obj.statement_paid_invoices[i].hospice_name+'</td>';
                    temp_html += '<td>'+obj.statement_paid_invoices[i].total+'</td>';
                    temp_html += '<td>'+obj.statement_paid_invoices[i].payment_amount+'</td>';
                    temp_html += '<td>'+payment_type+'</td>';
                    temp_html += '<td>'+(obj.statement_paid_invoices[i].check_number == null ? "" : obj.statement_paid_invoices[i].check_number)+'</td>';
                    temp_html += '<td>'+new_rec_date+'</td>';
                    temp_html += '<td>'+obj.statement_paid_invoices[i].received_by+'</td>';
                    temp_html += '</tr>';
                }
                $('.tbody_archive').html(temp_html);

                
                $('.datatable_table_statement_archive').DataTable( {
                    "order": [[ 0, "asc" ]],
                    "columnDefs":[
                        {
                            // "targets": 0,
                            // "searchable": false,
                            // "orderable": false
                        }
                    ]
                } );
            },1);
            
        });
    };

	$(document).ready(function(){
		$('.datatable_table_statement_archive').DataTable( {
	        "order": [[ 1, "asc" ]],
            "columnDefs":[
                {
                    // "targets": 0,
                    // "searchable": false,
                    // "orderable": false
                }
            ]
	    } );
        payment_archive_content();
        //select_year
        $('body').on('change','.select_year',function(){
            payment_archive_content();
        });
	});

    // View statement bill Details
    $('body').on('click','.view_invoice_details',function(){
        var _this = $(this);
        var invoice_id = $(this).attr("data-invoice-id");
        var data_hospice_id = $(this).attr("data-hospice-id");

        modalbox(base_url + 'billing_statement/statement_activity_details/'+invoice_id+'/'+data_hospice_id,{
            header:"Invoice",
            button: false,
        });
    });



    //SELECT ALL CHECKBOXES
    $('.datatable_table_statement_draft').on('click','.all-statementLetter-checkbox',function(){
        var statement_bill = $('.statementLetter-checkbox');
        if($(this).is(':checked'))
        {
            statement_bill.each(function(){
                $(this).prop('checked', true);
                $("body").find(".create-statementLetter-btn").removeAttr('disabled');
                $("body").find(".return-to-btn").removeAttr('disabled');
            });
        }
        else
        {
            statement_bill.each(function(){
                $(this).prop('checked', false);
                $("body").find(".create-statementLetter-btn").attr('disabled', true);
                $("body").find(".return-to-btn").attr('disabled', true);
            });
        }
    });

    //Check by one
    $('.datatable_table_statement_draft').on('click','.statementLetter-checkbox',function(){
        var this_element = $(this);
        var value = $(this).val();
        // var _this = $('.create-invoice:checked');

        if(this_element.is(':checked'))
        {
            $("body").find(".create-statementLetter-btn").removeAttr('disabled');
            $("body").find(".return-to-btn").removeAttr('disabled');
        }
        else
        {
            var _this = $('.statementLetter-checkbox:checked');
            if (_this.length == 0) {
                $("body").find(".create-statementLetter-btn").attr('disabled', true);
                $("body").find(".return-to-btn").attr('disabled', true);
            }
        }
    });

    //return to draft by checkboxes
    $('.return-to-btn').bind('click',function(){
        // var draft_id = $(this).attr("data-draft-id");

        jConfirm("Are you sure you want to return statement activity to draft?","Reminder",function(response){
            if(response)
            {

                me_message_v2({error:2,message:"Returning statement activity to draft. Please wait..."});
                var selected_invoices = $('.statementLetter-checkbox');
                var selected_inv = "";
                var counter = 0;
                selected_invoices.each(function(){
                    if($(this).is(':checked')) {
                        if(counter == 0) {
                            selected_inv = $(this).attr('data-invoice-id');
                        } else {
                            selected_inv = selected_inv + "-" + $(this).attr('data-invoice-id');
                        }
                        counter++;
                    }
                });

                console.log('selected_inv: ', selected_inv);
                $.get(base_url+'billing_statement/return_to_draft_statement_activity/'+selected_inv, function(response){
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

</script>