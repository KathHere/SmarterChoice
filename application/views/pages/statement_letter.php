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
            /* margin-top: -45px !important; */
        }
    }

    .location_container {
        /*position: absolute;*/
        /* margin-top: 50px; */
        margin-left: 40px;
        font-size: 20px;
        /*top: 0;*/
        left: 0;
        display: none;
    }

    .dot {
        height: 18px;
        min-width: 18px;
        margin-left: 7px;
        margin-right: 0px;
        background-color: #bbb;
        border-radius: 20px;
        display: inline-block;
        background-color: #23b7e5;
        color: white;
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
<div class="bg-light lter b-b wrapper-md hidden-print">
	<h1 class="m-n font-thin h3">
        Statement
    </h1>
</div>

<div class="wrapper-md statement_letter_container">
	<div class="panel panel-default">
    	<div class="panel-heading">
    		Account Statement
    	</div>
    	<div class="panel-body" style="padding: 0px;">
    		<table class="table table-hover bg-white b-a datatable_table_statement_draft">
    			<thead >
                    <!-- <th style="text-align: center;">Date</th> -->
                    <th style="text-align: center;">Sent Date</th>
    				<th style="text-align: center;">Account Name</th>
    				<th style="text-align: center;">Account Number</th>
    				<!-- <th style="text-align: center;">Invoice Date</th>
                    <th style="text-align: center;">Invoice Number</th> -->
                    <th class="hidden-print" style="text-align: center;">Statement</th>
                    <th class="hidden-print" style="text-align: center;">Notes</th>
                    <th class="hidden-print" style="text-align: center;">Action</th>
    			</thead>
    			<tbody>
                    <?php
                    foreach($statement_letters as $key => $statement_letter) {
                    ?>
                    <tr style="text-align: center">
                        <!-- <td><?php echo date('m/d/Y', strtotime($statement_letter['date_created'])); ?></td> -->
                        <td>
                            <?php
                                if ($statement_letters_email[$key] != null) {
                                    echo date('m/d/Y', strtotime($statement_letters_email[$key]['date_added']));
                                }
                            ?>
                        </td>
                        <td><?php echo $statement_letter['hospice_name']; ?></td>
                        <td><?php echo $statement_letter['hospice_account_number']; ?></td>
                        <!-- <td><?php echo date('m/d/Y', strtotime($statement_letter['invoice_date'])); ?></td>
                        <td>
                            <div >
                               <?php echo substr($statement_letter['invoice_no'],3, 10); ?>
                            </div>
                        </td> -->
                        <td class="hidden-print">
                            <span style="cursor: pointer" class="view_statement_letter_details" data-letter-id="" data-hospice-id="<?php echo $statement_letter['hospiceID']; ?>">
                                <i class="icon-envelope" style="font-size: 18px; margin-right: 10px;"></i>
                            </span>
                            
                        </td>
                        <td class="hidden-print">
                            <a href="javascript:void(0)" data-hospice-id="<?php echo $statement_letter['hospiceID'] ?>" style="text-decoration:none;cursor:pointer; text-align: center;" class="hidden-print view_comments notes_count_<?php echo $statement_letter['hospiceID'] ?>">
                                <?php
                                    $temp_background_color = '';
                                    if($statement_letters_note_count[$key] == 0) {
                                        $temp_background_color = 'background-color: #c3c2c2 !important;';
                                    }
                                ?>
                                <div class="row" style="margin: 0px !important">
                                    <div class="col-md-6" style="padding-right: 0px !important"><i class="icon-speech pull-right"></i></div>
                                    <div class="col-md-6" style="padding-left: 0px !important"><p class="dot pull-left" style="float: right;margin-top: -3px;margin-right: 11px; <?php echo $temp_background_color; ?>">
                                        <?php echo $statement_letters_note_count[$key]; ?>
                                    </div>
                                </div>
                            </a>
                        </td>
                        <td class="hidden-print">
                            <!-- <button class="cancel_statement_letter_btn btn btn-xs btn-danger" data-letter-id="<?php echo $statement_letter['acct_statement_letter_id']; ?>" data-hospice-id="<?php echo $statement_letter['hospiceID']; ?>"><Strong>Delete</Strong></button> -->
                            <button style="" class="btn btn-xs btn-info send_email_btn" data-hospice-id="<?php echo $statement_letter['hospiceID']; ?>">
                                Email
                            </button>
                        </td>
                    </tr>
                    <?php    
                    }
                    ?>
    			</tbody>
    		</table>
    	</div>
	</div>
</div>

<div class="modal fade" id="loader_modal" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 600px !important;">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);text-transform: uppercase;">
            <div class="modal-header">
                <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reminder</h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
                <div class="loading_content">
                    <div style="text-align: center; margin: 20px"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                    <div id="loading_more" style="text-align:center;font-size:18px; margin: 30px">Sending Statement Letter... </div>
                </div>
                <div class="error_content"></div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                <button type="button" class="btn btn-primary btn-order save_floor_mat_capped">Save changes</button>
            </div> -->
        </div>
    </div>
</div>

<div class="bg-light lter wrapper-md">
   <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
</div>

<input type="hidden" id="get_base_url" value="<?php echo base_url(); ?>">
<div id="iframe_container" style="visibility: hidden"></div>


<!-- REFERENCE to html2canvas utility https://github.com/niklasvh/html2canvas -->
<script src="<?php echo base_url(); ?>assets/js/html2canvas.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/jspdf.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/html2pdf.js-master/dist/html2pdf.bundle.min.js" type="text/javascript"></script>


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

    $('body').on('click','.send_email_btn',function(){
        // var invoice_id = $(this).attr("data-invoice-id");
        var data_hospice_id = $(this).attr("data-hospice-id");
        console.log('data_hospice_id', data_hospice_id);
        jConfirm("Send Statement Letter Via Email?","Reminder",function(response){
            if(response)
            {
                $('#loader_modal').modal('show');
                var baseurl = $('#get_base_url').val();
                // var url = baseurl+"billing_statement/statement_activity_details/10/13";
                // $('#invoice_page').attr('src', url);
                var ifrm = document.createElement("iframe");
                ifrm.setAttribute('id','statement_letter_page');
                ifrm.setAttribute("src", baseurl+"billing_statement/iframe_statement_letter_details/"+data_hospice_id);
                ifrm.style.width = "100%";
                ifrm.style.height = "100%";
                ifrm.style.background = "white";
                $('#iframe_container').html(ifrm);
                $('#statement_letter_page').on('load', function() {
                    try {
                        console.log($('#statement_letter_page')[0].contentWindow.document);
                        var iframe = $('#statement_letter_page')[0];
                        console.log('iframe:', iframe);
                        var elementToPrint = iframe.contentDocument.documentElement; //if source is iframe
                        console.log('nodeName:', elementToPrint.nodeName);
                        var string_elementToPrint = '<!DOCTYPE HTML>' + '\n' + elementToPrint.outerHTML;
                        console.log('iframe_string:', string_elementToPrint);
                        var opt = {
                            margin:       0.3,
                            filename:     'invoice.pdf',
                            image:        { type: 'jpeg', quality: 0.30 },
                            html2canvas:  { scale: 3, allowTaint: true },
                            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
                        };
                        // html2pdf().set(opt).from(string_elementToPrint).save();

                        // var datatable_table_statement_bill = $('#invoice_page').contents().find('.table_page_1')[0].outerHTML;
                        // var order_summary_count = $('#invoice_page').contents().find('#order_summary_count').val();
                        // var table_pages = $('#invoice_page').contents().find('#table_pages').val();

                        var doc = html2pdf().set(opt).from(string_elementToPrint).toPdf().then(function(generatedPDF) {
                            console.log('generatedPDF', generatedPDF);

                            doc.output('blob').then(function(blob) {
                                // console.log('base64', btoa(pdf)); // to Base64
                                // var blob = new Blob([pdf], {type: 'application/pdf'});
                                console.log('html2pdf page 1', blob);
                                var formData = new FormData();
                                formData.append('pdf', blob);
                                $.ajax(base_url+'billing_statement/upload_statement_letter_pdf/'+data_hospice_id,
                                {
                                    method: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(data){
                                        $('#loader_modal').modal('hide');
                                        
                                        var parse_data = JSON.parse(data);
                                        console.log('upload_statement_letter_pdf', parse_data);
                                        
                                        if (parse_data.error == 1) {
                                            me_message_v2({error:1,message: parse_data.message});
                                            setTimeout(function(){
                                                location.reload();
                                            },2000);
                                        } else {
                                            // $.post(base_url + 'billing_statement/insert_invoice_email_log/' + invoice_id, function(response){
                                                setTimeout(function(){
                                                    location.reload();
                                                },1000);    
                                            // });
                                        }
                                    },
                                    error: function(data){console.log(data)}
                                });
                            });
                        });
                        console.log('page_split');
                    } catch (e) {
                        console.log(e);
                        if (e.message.indexOf('Blocked a frame with origin') > -1 || e.message.indexOf('from accessing a cross-origin frame.') > -1) {
                            alert('Same origin Iframe error found!!!');
                            //Do fallback handling if you want here
                        }
                    }

                });
            } else {
                $('.invoice_status_'+invoice_id).val(previous_value);
                console.log('previous_invoice_status_value', previous_value);
            }
        });
    });

    // View statement bill Details
    $('body').on('click','.view_statement_letter_details',function(){
        var _this = $(this);
        var letter_id = $(this).attr("data-letter-id");
        var data_hospice_id = $(this).attr("data-hospice-id");

        // modalbox(base_url + 'billing_statement/statement_letter_details/'+invoice_id+'/'+data_hospice_id,{
        modalbox(base_url + 'billing_statement/statement_letter_details/'+data_hospice_id,{
            header:"Statement",
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

    $('body').on('click','.cancel_statement_letter_btn',function(){
        var data_hospice_id = $(this).attr("data-hospice-id");
        var acct_statement_letter_id = $(this).attr("data-letter-id");

        jConfirm("Delete statement letter?","Reminder",function(response){
            if(response)
            {
                $.get(base_url+'billing_statement/delete_statement_letter/'+acct_statement_letter_id+'/'+data_hospice_id, function(response){
                    var obj = $.parseJSON(response);

                    if(obj['error'] == 0)
                    {
                        me_message_v2({error:0,message:obj['message']});
                        setTimeout(function(){
                            location.reload();
                        },2000);
                    } else {
                        me_message_v2({error:1,message:"Error!"});
                    }
                });
            }
        });
    });

    // View Comments
    $('body').on('click','.view_comments',function(){
        var _this = $(this);
        var hospice_id = $(this).attr("data-hospice-id");
        modalbox(base_url + 'billing_reconciliation/get_statement_letter_notes/'+hospice_id,{
            header:"<div style='text-align:center'>NOTE</div>",
            button: false,
        });
    });

</script>