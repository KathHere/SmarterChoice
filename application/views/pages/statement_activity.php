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
<div class="statement_activity_container">
    <div class="bg-light lter b-b wrapper-md">
        <h1 class="m-n font-thin h3">
            Activity Statements
            <div class="pull-right">
                <button class="btn btn-danger btn-sm return-to-btn" style="font-size:13px !important;" disabled>
                    Return to draft
                </button>
            </div>
            <div class="pull-right">
                <button class="btn btn-info btn-sm create-statementLetter-btn" style="font-size:13px !important;" disabled>
                    Create Statement Letter
                </button>
                &nbsp;&nbsp;
            </div>
        </h1>
    </div>

    <div class="wrapper-md">
        <div class="panel panel-default">
            <div class="panel-heading">
                Activity Statements
            </div>
            <div class="panel-body" style="padding: 0px;">
                <table class="table table-hover bg-white b-a datatable_table_statement_draft">
                    <thead >
                        <th  class="hidden-print" style="text-align: center; padding-bottom: 0px !important; padding-left: 18px !important;"><label class="i-checks"><input type="checkbox" class="form-control all-statementLetter-checkbox" value="" /><i></i></label></th>
                        <th style="text-align: center;">Account Name</th>
                        <th style="text-align: center;">Invoice Date</th>
                        <th style="text-align: center;">Due Date</th>
                        <th style="text-align: center;">Invoice Number</th>
                        <th style="text-align: center;">Total Balance</th>
                        <!-- <th style="text-align: center;">Actions</th> -->
                    </thead>
                    <tbody>
                        <?php
                        foreach($statement_activity as $key => $value) {
                        ?>
                        <tr style="text-align: center">
                            <td class="hidden-print">
                                <label class="i-checks data_tooltip" >
                                    <input type="checkbox" class="statementLetter-checkbox" data-invoice-id="<?php echo $value['acct_statement_invoice_id']; ?>" name=""/>
                                    <i></i>
                                </label>
                            </td>
                            <td><?php echo $value['hospice_name']; ?></td>
                            <td><?php echo date('m/d/Y', strtotime($value['invoice_date'])); ?></td>
                            <td><?php echo date('m/d/Y', strtotime($value['due_date'])); ?></td>
                            <td>
                                <div style="cursor: pointer" class="view_invoice_details" data-invoice-id="<?php echo $value['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $value['hospiceID']; ?>">
                                    <?php echo substr($value['statement_no'],3, 10); ?>
                                </div>

                            </td>
                            <td>
                                <?php 
                                    $total_balance_due = 0;
                                    $total_balance_due += $value['total'];
                                    $total_balance_due += $value['non_cap'];
                                    $total_balance_due += $value['purchase_item'];
                                    $total_balance_due -= $invoices_reconciliation[$key]['credit'];     //Deduct
                                    $total_balance_due += $invoices_reconciliation[$key]['owe'];        //Add

                                    echo number_format((float)$total_balance_due, 2, '.', '');
                                ?>
                            </td>
                            <!-- <td>
                                <button class="btn btn-xs btn-info create-invoice-action" data-draft-id="<?php echo $value['acct_statement_draft_id']; ?>"><strong>Create Statement Letter</strong></button>
                                &nbsp;&nbsp;
                                <button class="cancel_draft_btn btn btn-xs btn-danger" style="<?php echo $hide_button; echo $disable_style; ?>" data-draft-id="<?php echo $value['acct_statement_draft_id']; ?>" data-hospice-id="<?php echo $value['hospiceID']; ?>" <?php echo $disable_button; ?>><Strong>Return to Draft</Strong></button>
                            </td> -->
                        </tr>
                        
                        <?php
                        }

                        ?>
                        <!-- <tr>
                            <td>
                                <label class="i-checks data_tooltip" >
                                    <input type="checkbox"name=""/>
                                    <i></i>
                                </label>
                            </td>
                            <td>24/7 Apollo Healthcare Inc</td>
                            <td>02/05/19</td>
                            <td>
                                <div style="cursor: pointer" class="" data-hospice-id="35">
                                    3257474
                                </div>

                            </td>
                            <td>1560.00</td>
                        </tr>
                        <tr>
                            <td>
                                <label class="i-checks data_tooltip" >
                                    <input type="checkbox"name=""/>
                                    <i></i>
                                </label>
                            </td>
                            <td>Alta Care Hospice & Palliative Care</td>
                            <td>12/15/19</td>
                            <td>
                                <div style="cursor: pointer" class="" data-hospice-id="35">
                                    9379301
                                </div>

                            </td>
                            <td>45001.00</td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-light lter wrapper-md">
       <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
    </div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
		$('.datatable_table_statement_draft').DataTable( {
	        "order": [[ 1, "asc" ]],
            "columnDefs":[
                {
                    "targets": 0,
                    "searchable": false,
                    "orderable": false
                }
            ]
	    } );
	});
    // View statement bill Details
    $('body').on('click','.view_invoice_details',function(){
        var _this = $(this);
        var invoice_id = $(this).attr("data-invoice-id");
        var data_hospice_id = $(this).attr("data-hospice-id");
        console.log('invoice_id',invoice_id);
        var header_logo = "";
        header_logo += '<div class="row" style="height: 85px !important;">';
        header_logo += '<div class="col-md-4">';
        header_logo += '<p class="logo_ahmslv" style="margin-bottom:0px; text-align: center">';
        header_logo += '<img class="logo_ahmslv_img" src="<?php echo base_url("assets/img/smarterchoice_logo.png"); ?>" alt="" style="height:50px;width:58px;"/>';
        header_logo += '</p>';
        header_logo += '<h4 class="work_order_header_first" style="font-weight:bold;margin-top:0px;font-size:17px; text-align: center"> Advantage Home Medical Services, Inc </h4>';
        header_logo += '</div>';

        //Printer Loader
        header_logo += '<div class="col-md-4 text-center">';
        header_logo += '<span style="line-height: 10rem; font-size: 25px !important;">Invoice</span>';
        header_logo += '</div>';
        header_logo += '<div class="col-md-4 text-center" style="padding-top: 10px;">';
        header_logo += '<div class="loader-icon" style="display: none !important">';
        header_logo += '<i class="printer_loader_icon fa fa-print"></i>';
        header_logo += '<div class="loader"></div>';
        header_logo += '</div>';
        header_logo += '<button class="btn btn-default loaded-icon" rel="popover" data-html="true" data-toggle="popover" data-trigger="hover" data-replacement="left" data-content="Print" onclick="window.print()" style="display:table-column;border-radius:50%;">';
        header_logo += '<i class="printer_loaded_icon fa fa-print"></i>';
        header_logo += '</button>';
        header_logo += '</div>';
        header_logo += '</div>';
        modalbox(base_url + 'billing_statement/statement_activity_details/'+invoice_id+'/'+data_hospice_id,{
            header:header_logo,
            button: false,
            width: 1200
        });
    });

    // <div class="col-md-6">
    //     <div class="loader-icon pull-right">
    //         <i class="printer_loader_icon fa fa-print"></i>
    //         <div class="loader"></div>
    //     </div>
    //     <button class="btn btn-default loaded-icon pull-right" rel="popover" data-html="true" data-toggle="popover" data-trigger="hover" data-replacement="left" data-content="Print" onclick="window.print()">
    //         <i class="printer_loaded_icon fa fa-print"></i>
    //     </button>
    // </div>
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

    //create statement letter by checkboxes
    $('.create-statementLetter-btn').bind('click',function(){
        // var draft_id = $(this).attr("data-draft-id");

        jConfirm("Are you sure you want to create statement letter?","Reminder",function(response){
            if(response)
            {

                me_message_v2({error:2,message:"Creating statement letter. Please wait..."});
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
                $.get(base_url+'billing_statement/create_statement_letter/'+selected_inv, function(response){
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