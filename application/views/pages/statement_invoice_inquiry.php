<style type="text/css">
    .disabled_tag {
        background-color: white !important;
    }

    .inquiry_status {
        display: none;
    }

    @media print {
        #DataTables_Table_0_wrapper .bootstrap-dt-container {
            display: none !important;
        }

        .inquiry_status {
            display: block !important;
        }

        @page {
            size: portrait;
            margin: 0mm 2mm 10mm 2mm;
        }

        .statement_invoice_inquiry_container {
            font-size: 10px !important;
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
<div class="statement_invoice_inquiry_container">
    <div class="bg-light lter b-b wrapper-md">
        <h1 class="m-n font-thin h3">
            Invoice Inquiry
            <div class="pull-right">
                <button class="btn btn-success btn-sm receive-payments-btn" style="font-size:13px !important;" disabled>
                    Receive Payment
                </button>
            </div>
            <div class="pull-right">
                <button class="btn btn-primary btn-sm attach-file-btn" style="font-size:13px !important;" disabled>
                    Attach File
                </button>
                &nbsp;&nbsp;
            </div>
            <div class="pull-right">
                <!-- <button class="btn btn-info btn-sm email-btn send-invoice-email-btn" style="font-size:13px !important;" disabled>
                    Email
                </button> -->
                <!-- <button class="btn btn-info btn-sm send-invoice-email-testing-btn" style="font-size:13px !important;">
                    Testing
                </button> -->
                &nbsp;&nbsp;
            </div>
        </h1>
    </div>

    <div class="wrapper-md">
        <div class="panel panel-default">
            <div class="panel-heading">
                Invoice Inquiry List
            </div>
            <div class="panel-body" style="padding: 0px;">
                <table class="table table-hover bg-white b-a datatable_table_statement_draft">
                    <thead >
                        <th class="hidden-print" style="text-align: center; padding-bottom: 0px !important; padding-left: 18px !important;"><label class="i-checks"><input type="checkbox" class="form-control all-inquiry-checkbox" value="" /><i></i></label></th>
                        <th style="text-align: center; ">Status</th>
                        <th style="text-align: center; ">Sent Date</th>
                        <th style="text-align: center; ">Account Number</th>
                        <th style="text-align: center; ">Account</th>
                        <th style="text-align: center; ">Invoice Number</th>
                        <th style="text-align: center; ">Service Date</th>
                        <th style="text-align: center; ">Balance Due</th>
                        <th style="text-align: center; ">Incoming</th>
                        <th style="text-align: center; ">Payment Date</th>
                        <th class="hidden-print" style="text-align: center; ">Action</th>
                    </thead>
                    <tbody>
                        <?php
                        foreach($statement_invoice_inquiry as $key => $value) {
                        ?>
                        <tr style="text-align: center">
                            <td class="hidden-print">
                                <label class="i-checks data_tooltip" >
                                    <input type="checkbox" class="inquiry-checkbox" data-invoice-id="<?php echo $value['acct_statement_invoice_id']; ?>" name=""/>
                                    <i></i>
                                </label>
                            </td>
                            <td>
                                <?php
                                    $invoice_status_pending = '';
                                    $invoice_status_email = '';
                                    $invoice_status_us_mail = '';
                                    $invoice_status_fax = '';

                                    if ($value['invoice_status'] == 'pending') {
                                        $invoice_status_pending = 'selected';
                                    }

                                    if ($value['invoice_status'] == 'email') {
                                        $invoice_status_email = 'selected';
                                    }

                                    if ($value['invoice_status'] == 'us_mail') {
                                        $invoice_status_us_mail = 'selected';
                                    }

                                    if ($value['invoice_status'] == 'fax') {
                                        $invoice_status_fax = 'selected';
                                    }
                                ?>
                                <input type="hidden" class="prev_invoice_status_<?php echo $value['acct_statement_invoice_id']; ?>" value="<?php echo $value['invoice_status']; ?>">
                                <select class="hidden-print form-control invoice-status invoice_status_<?php echo $value['acct_statement_invoice_id']; ?>" value="<?php echo $value['invoice_status']; ?>" data-invoice-id="<?php echo $value['acct_statement_invoice_id']; ?>">
                                    <?php if ($value['invoice_status'] != 'email') {
                                    ?>
                                        <option value="pending" <?php echo $invoice_status_pending; ?>>Pending</option>
                                    <?php
                                    }
                                    ?>
                                    
                                    <option value="email" <?php echo $invoice_status_email; ?>>Email</option>
                                    <option value="us_mail" <?php echo $invoice_status_us_mail; ?>>US Mail</option>
                                    <option value="fax" <?php echo $invoice_status_fax; ?>>Fax</option>
                                </select>
                                <div class="inquiry_status">
                                    <?php
                                        switch($value['invoice_status']) {
                                            case 'pending': echo 'Pending'; break;
                                            case 'email': echo 'Email'; break;
                                            case 'us_email': echo 'US Mail'; break;
                                            case 'fax': echo 'Fax'; break;
                                        }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <?php
                                    if ($value['invoice_status'] == 'us_mail' || $value['invoice_status'] == 'fax') {
                                        $new_sent_date = date('Y-m-d');
                                        if ($value['email_sent_date'] != '' && $value['email_sent_date'] != null && $value['email_sent_date'] != '0000-00-00') {
                                            $new_sent_date = $value['email_sent_date'];
                                        }
                                ?>
                                    <a href="javascript:;"
                                        id="email_sent_date"
                                        data-pk="<?php echo $value['acct_statement_invoice_id'] ?>"
                                        data-url="<?php echo base_url('billing_statement/update_invoice_sent_date/'.$value['acct_statement_invoice_id']); ?>"
                                        data-title="Enter Date"
                                        data-value="<?php echo date("Y-m-d", strtotime($new_sent_date)); ?>"
                                        data-type="combodate"
                                        data-maxYear="<?php echo date("Y") ?>"
                                        data-format="YYYY-MM-DD"
                                        data-viewformat="MM/DD/YYYY"
                                        data-template="MMM / D / YYYY"
                                        class="data_tooltip editable editable-click editable-combodate"
                                    >
                                        <?php echo date("m/d/Y", strtotime($new_sent_date)); ?>
                                    </a>
                                <?php
                                    } else {
                                        if($value['email_sent_date'] !== null && $value['email_sent_date'] !== '' && $value['email_sent_date'] !== '0000-00-00') {
                                            echo date("m/d/Y", strtotime($value['email_sent_date'])); 
                                        }
                                    }
                                ?>
                            </td>
                            <td><?php echo $value['hospice_account_number']; ?></td>
                            <td><?php echo $value['hospice_name']; ?></td>
                            <td>
                                <div style="cursor: pointer" class="view_invoice_details" data-invoice-id="<?php echo $value['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $value['hospiceID']; ?>">
                                    <?php echo substr($value['statement_no'],3, 10); ?>
                                </div>

                            </td>
                            <td>
                                <?php echo date('m/d/Y', strtotime($value['service_date_from'])).' - '.date('m/d/Y', strtotime($value['service_date_to']))?>
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
                            <td>
                                <?php
                                if($value['payment_code'] != null && $value['payment_code'] != "") {
                                    if($value['payment_amount'] != 0 && $value['payment_amount'] != "" && $value['payment_amount'] != null)
                                    {
                                        echo number_format((float)$value['payment_amount'], 2, '.', ''); 
                                    }
                                }
                                
                                ?>
                                    
                            </td>
                            <td>
                                <?php
                                if($value['payment_code'] != null && $value['payment_code'] != "") {
                                    if($value['payment_date'] != "0000-00-00" && $value['payment_date'] != "" && $value['payment_date'] != null && $value['payment_date'] != "0000-00-00 00:00:00")
                                    {
                                        echo date("m/d/y", strtotime($value['payment_date']));
                                    }
                                }
                                
                                ?>
                                    
                            </td>
                            <td class="hidden-print">

                                <?php if($value['payment_code'] == null && $value['payment_code'] == "") { 
                                    $disable_button = "";
                                    $disable_style = "";
                                    $button_class_color = "danger";
                                    $button_class_color_inv = "info";
                                    if($is_disabled_invoice_cancel[$key] == 0) {
                                        $disable_button = "disabled";
                                        $disable_style = "background-color: #f6f8f8;";
                                        $button_class_color = "default";
                                        $button_class_color_inv = "default";
                                    }
                                ?>
                                    <button class="cancel_invoice_btn btn btn-xs btn-<?php echo $button_class_color; ?>" style="<?php echo $disable_style; ?>" data-invoice-id="<?php echo $value['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $value['hospiceID']; ?>" <?php echo $disable_button; ?>><Strong>Cancel</Strong></button>
                                <?php } ?>
                                <button class="send_email_btn send_email_btn_<?php echo $value['acct_statement_invoice_id']; ?> btn btn-xs btn-info" data-invoice-id="<?php echo $value['acct_statement_invoice_id']; ?>" data-hospice-id="<?php echo $value['hospiceID']; ?>"><Strong>Email</Strong></button>
                                <!-- <button onclick="window.open('<?php echo base_url(); ?>billing_statement/iframe_statement_activity_details/<?php echo $value['acct_statement_invoice_id'].'/'.$value['hospiceID']?>', '_blank')" > Email</button> -->
                                
                            </td>
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
        <div class="modal fade" id="reconciliation_popup_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  style="z-index: 10010 !important">
            <div class="modal-dialog" style="">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title row" style="font-weight: bold"><div class="col-md-4" style="margin-right: 28px"><?php echo date('m/d/Y'); ?></div><div class="col-md-7" >Reconciliation</div></h4>
                    </div>
                    <!-- <form action="<?php echo base_url()?>billing_reconciliation/insert_reconciliation_v2" method="POST" id="reconciliation_receiving"> -->
                        <div class="modal-body">
                            <div class="statement_letter_label_wrapper">
                                <label class="statement_letter_label_tag">Account Name:</label>
                                <span>
                                    <input type="text" name="recon_account_name" class="form-control disabled_tag recon_account_name" id="" placeholder="" style="margin-left:0px" disabled>
                                </span>
                                <input type="hidden" class="recon_account_id" name="recon_account_id" value="">
                                <input type="hidden" class="reconciliation_invoice_id" name="reconciliation_invoice_id" value="">
                            </div>
                            <div class="statement_letter_label_wrapper">
                                <label class="statement_letter_label_tag">Invoice Date:</label><span><input type="text" name="recon_invoice_date" class="form-control disabled_tag recon_invoice_date" id="" placeholder="" style="margin-left:0px" disabled></span>
                            </div>
                            <div class="statement_letter_label_wrapper">
                                <label class="statement_letter_label_tag">Invoice Number:</label>
                                <span>
                                    <input type="text" name="recon_invoice_number" class="form-control disabled_tag sh_recon_invoice_number" id="" placeholder="" style="margin-left:0px" value="" disabled>
                                    <input type="hidden" name="recon_invoice_number" class="form-control disabled_tag recon_invoice_number" id="" placeholder="" style="margin-left:0px" value="" disabled>
                                </span>
                            </div>
                            <div class="statement_letter_label_wrapper">
                                <label class="statement_letter_label_tag">Balance Due:</label><span><input type="text" name="recon_balance_due" class="form-control disabled_tag recon_balance_due" id="" placeholder="" style="margin-left:0px" value="" disabled></span>
                            </div>
                            <div class="statement_letter_label_wrapper">
                                <label class="statement_letter_label_tag">Payment Amount:</label><span><input type="text" name="recon_payment_amount" class="form-control disabled_tag recon_payment_amount" id="" placeholder="" style="margin-left:0px" value="" disabled></span>
                            </div>
                            <div class="statement_letter_label_wrapper">
                                <label class="statement_letter_label_tag">Owe:</label><span><input type="text" name="recon_amount_owe" class="form-control disabled_tag recon_amount_owe" id="" placeholder="" style="margin-left:0px" value="" disabled></span>
                            </div>
                            <div class="statement_letter_label_wrapper">
                                <label class="statement_letter_label_tag">Credit:</label><span><input type="text" name="recon_credit" class="form-control disabled_tag recon_credit" id="" placeholder="" style="margin-left:0px" value="" disabled></span>
                            </div>
                            <div class="statement_letter_label_wrapper">
                                <label class="statement_letter_label_tag">Note: <span style="font-size: 12px; font-style: italic; color: #00000078;"> Restricted "#"$%&'()*+-/:;<=>?@[\]^_`{|}~" </span></label><span><textarea id="notes" name="recon_notes" class="form-control recon_notes" style="width: 100%; height: 51px; border: none; padding: 10px; resize: none" onkeypress="return (event.charCode == 8 || event.charCode == 13) ? null : ( event.charCode >= 48 && event.charCode <= 57 ) || event.charCode === 46 || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32) || (event.charCode == 33) || (event.charCode == 44) || (event.charCode == 63)" onpaste="return false" placeholder="Enter note..."></textarea></span>
                            </div>
                        </div>
                    
                        <div class="modal-footer" style="padding-left: 30px;padding-right: 30px;">
                            <!-- <button type="button" class="btn btn-default skip_serial_asset_no pull-left"> Skip </button>
                            <button type="button" class="btn btn-success save_serial_asset_no" disabled> Save Changes </button> -->
                            <button type="button" class="btn btn-danger add_comments" data-dismiss="modal"> No</button>
                            <button type="submit" class="btn btn-success create_reconciliation" data-dismiss="modal" style="margin-left: 50px"> Yes</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    <div class="modal fade" id="notes_popup_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"  style="z-index: 10010 !important">
        <div class="modal-dialog" style="">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" style="font-weight: bold">ACCOUNT INVOICE NOTE</h4>
                </div>
                <div class="modal-body">
                    <section class="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="inner-form-container" style="margin-top:0px !important;background-color:#edeff4;">
                                <?php echo form_open("",array("id"=>"enter-note-page")) ;?>
                                    <div class="" style="padding: 14px;text-align:center;padding-top:30px;padding-bottom:50px;">
                                        <p style='font-weight:bold;'> INV# - <span class="invoice_notes_invnumber"></span></p>
                                        <div class="comments-main-content">
                                        <?php if(!empty($comments)) :?>
                                            <?php foreach($comments as $comment) :?>
                                                <div class="comments-area">
                                                    <p class="comment-text"><?php echo $comment['comment'] ?></p>
                                                    <p class="comment-when-by" style="font-size:12px">Commented on <?php echo  date("m/d/Y h:ia", strtotime($comment['date_commented'])) ?> by <?php echo $comment['userName'] ?></p>
                                                </div>

                                            <?php endforeach ;?>
                                        <?php endif ;?>
                                        </div>

                                        <?php if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user') :?>
                                            <textarea class="form-control comment_content" name="comment" style="margin-bottom:15px" placeholder="Enter comment" required></textarea>
                                            <button type="button" class="btn btn-primary pull-right enter-notes-btn" data-id="<?php echo $acct_statement_invoice_id ?>">Comment</button>
                                        <?php endif ;?>
                                    </div>
                                    <input type="hidden" name="commented_by" value="<?php echo $this->session->userdata('userID') ?>" />
                                    <input type="hidden" name="commented_by_name" value="<?php echo $this->session->userdata('lastname') ?> <?php echo $this->session->userdata('firstname') ?>" />
                                    <input type="hidden" name="invoice_id" class="invoice_id_input" value="" />
                                </div>
                                <?php echo form_close() ;?>

                                <div class="page-shadow">

                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                
                <!-- <div class="modal-footer" style="padding-left: 30px;padding-right: 30px;">
                    <button type="button" class="btn btn-default skip_serial_asset_no pull-left"> Skip </button>
                    <button type="button" class="btn btn-success save_serial_asset_no" disabled> Save Changes </button>
                    <button type="button" class="btn btn-danger view_comments" data-dismiss="modal"> No</button>
                    <button type="button" class="btn btn-success create_reconciliation" data-dismiss="modal" style="margin-left: 50px"> Yes</button>
                </div> -->
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="bg-light lter wrapper-md">
       <button class="btn btn-default" style="" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
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
                    <div id="loading_more" style="text-align:center;font-size:18px; margin: 30px">Sending Invoice... </div>
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

<div class="modal fade" id="no_email_modal" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 600px !important;">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
        <!-- text-transform: uppercase; -->
            <div class="modal-header">
                <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reminder</h4>
            </div>
            <div class="modal-body OpenSans-Reg equipments_modal">
                <div class="no_email_content">
                    <div style="text-align: center; font-size: 18px; margin: 50px; font-weight: bold">List of Invoices that were not successfully sent.</div>
                    <div id="no_email" style="text-align:center;font-size:18px; margin: 30px">
                        <div>#INV 7856742 ● Demo Samp - 123456</div>
                        <div>#INV 7856742 ● Demo Samp - 123456</div>
                    </div>
                </div>
                <div class="error_content"></div>
            </div>
            <div class="modal-footer" id="popup_panel">
                <button type="button" class="btn btn-danger" id="popup_ok_no_email" style="color:#fff" autocomplete="off">
                    <span class="glyphicon glyphicon-ok"></span> &nbsp;OK&nbsp;
                </button>
                <!-- <button type="button" class="btn btn-default" id="popup_cancel">
                    <span class="glyphicon glyphicon-remove"></span> &nbsp;Cancel&nbsp;
                </button> -->
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                <button type="button" class="btn btn-primary btn-order save_floor_mat_capped">Save changes</button>
            </div> -->
        </div>
    </div>
</div>

<div class="modal fade" id="us_mail_modal" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 600px !important;">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
        <!-- text-transform: uppercase; -->
            <div class="modal-header">
                <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reminder</h4>
            </div>
            <form id="us_mail_form">
                <div class="modal-body OpenSans-Reg equipments_modal">
                    <div class="no_email_content">
                        <input type="hidden" id="us_mail_invoice_id" value="" name="us_mail_invoice_id">
                        <input type="hidden" id="us_mail_invoice_status" value="" name="us_mail_invoice_status">
                        <div id="" style="text-align:center; margin: 30px">
                            <div class="form-group" style="margin-right:0px;" id="">
                                <label style="margin-left:5px;"> Sent Date<span class="text-danger-dker">*</span></label>
                                <input type="text" class="form-control us_mail_sent_date" value="" placeholder="Date" name="us_mail_sent_date" style="">
                            </div>
                        </div>
                    </div>
                    <div class="error_content"></div>
                </div>
                <div class="modal-footer" id="popup_panel">
                    <button type="button" class="btn btn-success" id="popup_submit_us_mail_sent_date" style="color:#fff" autocomplete="off">
                        <span class="glyphicon glyphicon-ok"></span> &nbsp;Submit&nbsp;
                    </button>
                    <button type="button" class="btn btn-default" id="popup_cancel_us_mail_sent_date">
                        <span class="glyphicon glyphicon-remove"></span> &nbsp;Cancel&nbsp;
                    </button>
                </div>
            </form>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                <button type="button" class="btn btn-primary btn-order save_floor_mat_capped">Save changes</button>
            </div> -->
        </div>
    </div>
</div>

<input type="hidden" id="get_base_url" value="<?php echo base_url(); ?>">
<div id="iframe_container" style="visibility: hidden"></div>
<!-- visibility: hidden -->

<!-- REFERENCE to html2canvas utility https://github.com/niklasvh/html2canvas -->
<script src="<?php echo base_url(); ?>assets/js/html2canvas.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/jspdf.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/html2pdf.js-master/dist/html2pdf.bundle.min.js" type="text/javascript"></script>

<script type="text/javascript">
	$(document).ready(function(){
        var global_invoice_status = '';
        var global_invoice_id = 0;
        var global_opt = {
            margin:       0.3,
            filename:     'invoice.pdf',
            image:        { type: 'jpeg', quality: 0.30 },
            html2canvas:  { scale: 3, allowTaint: true },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        // var global_doc = html2pdf().set(global_opt).from($('.statement_invoice_inquiry_container')[0]).toPdf();
        // var global_doc_temp = global_doc;
        $('body').on('click','.send-invoice-email-testing-btn',function(){
            // var iframe = $('#invoice_page')[0];
            // var elementToPrint = iframe.contentDocument.documentElement; //if source is iframe
            // var opt = {
            //     margin:       0.3,
            //     filename:     'myfile.pdf',
            //     image:        { type: 'jpeg', quality: 0.98 },
            //     // html2canvas:  { scale: 2 },
            //     jsPDF:        { unit: 'mm', format: 'letter', orientation: 'portrait' }
            // };
            // html2pdf().set(opt).from(elementToPrint).save();
            // addStatementPage(global_doc, 2, 1);
        });
        function addStatementPage(document, table_pages, page, invoice_id, data_hospice_id) {
            console.log('addStatementPage', table_pages, page);
            $('#invoice_page').contents().find('.table_page_'+(page+1)).show();

            if ((parseInt(page)+1) == table_pages) {
                console.log('payment_due_footer show');
                $('#invoice_page').contents().find('#payment_due_footer').show();
            }
            
            setTimeout(function(){
                var temp_iframe = $('#invoice_page')[0];
                var temp_elementToPrint = temp_iframe.contentDocument.documentElement; //if source is iframe
                var temp_string_elementToPrint = '<!DOCTYPE HTML>' + '\n' + temp_elementToPrint.outerHTML;
                console.log('LOADED INVOICE', temp_string_elementToPrint);
                var temp_document = document.get('pdf').then(function() {
                    pdf => { pdf.addPage() }
                }).set(global_opt).from(temp_string_elementToPrint).toContainer().toCanvas().toPdf().then(function() {
                    
                    var temp_table_pages = parseInt(table_pages);
                    var temp_page = parseInt(page)+1;
                    if (temp_page < temp_table_pages) {
                        addStatementPage(temp_document, temp_table_pages, temp_page, invoice_id, data_hospice_id);
                    } else {
                        console.log('SAVE PAGE ', temp_page);
                        temp_document.output('blob').then(function(blob) {
                            // console.log('base64', btoa(pdf)); // to Base64
                            // var blob = new Blob([pdf], {type: 'application/pdf'});
                            console.log('html2pdf', blob);
                            var formData = new FormData();
                            formData.append('pdf', blob);
                            $.ajax(base_url+'billing_statement/upload_pdf/'+data_hospice_id+'/'+invoice_id,
                            {
                                method: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(data){
                                    $('#loader_modal').modal('hide');
                                    
                                    var parse_data = JSON.parse(data);
                                    console.log('upload_pdf', parse_data);
                                    
                                    if (parse_data.error == 1) {
                                        me_message_v2({error:1,message: parse_data.message});
                                        setTimeout(function(){
                                            location.reload();
                                        },2000);
                                    } else {
                                        $.post(base_url + 'billing_statement/insert_invoice_email_log/' + invoice_id, function(response){
                                            setTimeout(function(){
                                                location.reload();
                                            },1000);    
                                        });
                                    }
                                },
                                error: function(data){console.log(data)}
                            });
                        });
                        // temp_document.save();
                    }
                });
                // html2pdf().set(global_opt).from(temp_string_elementToPrint).output('blob').save();
            },1000);
        }

        $('body').on('click','.send_email_btn',function(){
            var invoice_id = $(this).attr("data-invoice-id");
            var data_hospice_id = $(this).attr("data-hospice-id");
            var previous_value = $('.prev_invoice_status_'+invoice_id).val();

            jConfirm("Send Invoice Via Email?","Reminder",function(response){
                if(response)
                {
                    $('#loader_modal').modal('show');
                    var wholebody = $('.statement_invoice_inquiry_container');
                    console.log('wholebody', wholebody[0]);
                    console.log('wholebody_nodeName:', wholebody[0].nodeName);
                    // html2pdf(wholebody[0]);
                    console.log('test baseurl:', $('#get_base_url').val());
                    var baseurl = $('#get_base_url').val();
                    // var url = baseurl+"billing_statement/statement_activity_details/10/13";
                    // $('#invoice_page').attr('src', url);
                    var ifrm = document.createElement("iframe");
                    ifrm.setAttribute('id','invoice_page');
                    ifrm.setAttribute("src", baseurl+"billing_statement/iframe_statement_activity_details/"+invoice_id+"/"+data_hospice_id);
                    ifrm.style.width = "100%";
                    ifrm.style.height = "100%";
                    ifrm.style.background = "white";
                    $('#iframe_container').html(ifrm);
                    $('#invoice_page').on('load', function() {
                        try {
                            console.log($('#invoice_page')[0].contentWindow.document);
                            var iframe = $('#invoice_page')[0];
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

                            var datatable_table_statement_bill = $('#invoice_page').contents().find('.table_page_1')[0].outerHTML;
                            var order_summary_count = $('#invoice_page').contents().find('#order_summary_count').val();
                            var table_pages = $('#invoice_page').contents().find('#table_pages').val();

                            console.log('datatable_table_statement_bill', datatable_table_statement_bill);
                            console.log('order_summary_count', order_summary_count);
                            console.log('table_pages', table_pages);

                            var doc = html2pdf().set(opt).from(string_elementToPrint).toPdf().then(function(generatedPDF) {
                                console.log('generatedPDF', generatedPDF);
                                if (table_pages > 1) {
                                    $('#invoice_page').contents().find('.header_container').remove();
                                    $('#invoice_page').contents().find('.statement_first_page').remove();
                                    $('#invoice_page').contents().find('.table_page_1').remove();
                                    $('#invoice_page').contents().find('#scissors_dashed_lines').remove();

                                    // global_doc = doc;
                                    // console.log('global_doc', global_doc);

                                    addStatementPage(doc, table_pages, 1, invoice_id, data_hospice_id);
                                } else {

                                    doc.output('blob').then(function(blob) {
                                        // console.log('base64', btoa(pdf)); // to Base64
                                        // var blob = new Blob([pdf], {type: 'application/pdf'});
                                        console.log('html2pdf page 1', blob);
                                        var formData = new FormData();
                                        formData.append('pdf', blob);
                                        $.ajax(base_url+'billing_statement/upload_pdf/'+data_hospice_id+'/'+invoice_id,
                                        {
                                            method: 'POST',
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            success: function(data){
                                                $('#loader_modal').modal('hide');
                                                
                                                var parse_data = JSON.parse(data);
                                                console.log('upload_pdf', parse_data);
                                                
                                                if (parse_data.error == 1) {
                                                    me_message_v2({error:1,message: parse_data.message});
                                                    setTimeout(function(){
                                                        location.reload();
                                                    },2000);
                                                } else {
                                                    $.post(base_url + 'billing_statement/insert_invoice_email_log/' + invoice_id, function(response){
                                                        setTimeout(function(){
                                                            location.reload();
                                                        },1000);    
                                                    });
                                                }
                                            },
                                            error: function(data){console.log(data)}
                                        });
                                    });
                                }
                                

                                // for (let j = 1; j < table_pages; j++) {
                                //     $('#invoice_page').contents().find('.table_page_'+(j+1)).show();
                                //     var temp_iframe = $('#invoice_page')[0];
                                //     var temp_elementToPrint = temp_iframe.contentDocument.documentElement; //if source is iframe
                                //     var temp_string_elementToPrint = '<!DOCTYPE HTML>' + '\n' + temp_elementToPrint.outerHTML;
                                //     console.log('temp_datatable_table_statement_bill', temp_string_elementToPrint);
                                //     doc = doc.get('pdf').then(
                                //         pdf => { pdf.addPage() }
                                //     ).from(temp_string_elementToPrint).toPdf();
                                // }
                                // doc.save();
                            });
                            
                            // ================= Version 1 - Dont Delete ====================== START
                            // html2pdf().set(opt).from(string_elementToPrint).output('blob').then(function(blob) {
                            //     // console.log('base64', btoa(pdf)); // to Base64
                            //     // var blob = new Blob([pdf], {type: 'application/pdf'});
                            //     console.log('html2pdf', blob);
                            //     var formData = new FormData();
                            //     formData.append('pdf', blob);
                            //     $.ajax(base_url+'billing_statement/upload_pdf/'+data_hospice_id,
                            //     {
                            //         method: 'POST',
                            //         data: formData,
                            //         processData: false,
                            //         contentType: false,
                            //         success: function(data){
                            //             $('#loader_modal').modal('hide');
                                        
                            //             var parse_data = JSON.parse(data);
                            //             console.log('upload_pdf', parse_data);
                                        
                            //             if (parse_data.error == 1) {
                            //                 me_message_v2({error:1,message: parse_data.message});
                            //                 setTimeout(function(){
                            //                     location.reload();
                            //                 },2000);
                            //             } else {
                            //                 $.post(base_url + 'billing_statement/insert_invoice_email_log/' + invoice_id, function(response){
                            //                     setTimeout(function(){
                            //                         location.reload();
                            //                     },1000);    
                            //                 });
                            //             }
                            //         },
                            //         error: function(data){console.log(data)}
                            //     });
                            // });
                             // ================= Version 1 - Dont Delete ====================== END

                            // var doc = new jsPDF('p', 'pt', 'letter');
                            // doc.internal.scaleFactor = 2.85;
                            // var options = {
                            //     pagesplit: true,
                            //     // margin: {
                            //     //     top: 10,
                            //     //     right: 10,
                            //     //     bottom: 10,
                            //     //     left: 10,
                            //     //     useFor: 'page'
                            //     // }
                            // };

                            // doc.addHTML(elementToPrint, 20, 20, options, function()
                            // {
                            //     // doc.save("test.pdf");
                            //     var blob = doc.output('blob');   
                            //     console.log('blob', blob);
                            //     var formData = new FormData();
                            //     formData.append('pdf', blob);
                            //     $.ajax(base_url+'billing_statement/upload_pdf',
                            //     {
                            //         method: 'POST',
                            //         data: formData,
                            //         processData: false,
                            //         contentType: false,
                            //         success: function(data){
                            //             $('#loader_modal').modal('hide');
                                        
                            //             $.post(base_url + 'billing_statement/insert_invoice_email_log/' + invoice_id, function(response){
                            //                 setTimeout(function(){
                            //                     location.reload();
                            //                 },1000);    
                            //             });
                                        
                            //         },
                            //         error: function(data){console.log(data)}
                            //     });
                            // });
                            console.log('page_split');
                            // html2canvas(elementToPrint, { scale: 3, allowTaint: true }).then(function (canvas) {
                            //     var b64Prefix = "data:image/png;base64,";
                            //     var imgBase64DataUri = canvas.toDataURL("image/png");
                            //     console.log('imgBase64DataUri', imgBase64DataUri);
                            //     var imgWidth = 80;
                            //     var imgHeight = canvas.height * imgWidth / canvas.width;  
                            //     var position = 0;
                            //     var doc = new jsPDF();
                            //     var dwidth = 210.0015555555555;
                            //     var dheight = 297.0000833333333;
                            //     doc.addImage(imgBase64DataUri, 'PNG', 0, position, dwidth, dheight, '', 'FAST');
                            //     doc.save('sample-file.pdf');
                            //     // var datapdf = doc.output();
                            //     // $.post(base_url+'billing_statement/upload_pdf/', { data: datapdf }, function (response) {
                            //     //     if (response != null) {
                            //     //         var obj = $.parseJSON(response);
                            //     //         console.log('pdfresponse', obj);
                            //     //         me_message_v2({error:obj['error'],message:obj['message']});
                            //     //     }
                            //     // });

                            //     var blob = doc.output('blob');
                            //     console.log('blob', blob);
                            //     var formData = new FormData();
                            //     formData.append('pdf', blob);
                                
                            //     $.ajax(base_url+'billing_statement/upload_pdf',
                            //     {
                            //         method: 'POST',
                            //         data: formData,
                            //         processData: false,
                            //         contentType: false,
                            //         success: function(data){
                            //             $('#loader_modal').modal('hide');
                            //         },
                            //         error: function(data){console.log(data)}
                            //     });

                            //     // var datapdf =doc.output(); //returns raw body of resulting PDF returned as a string as per the plugin documentation.
                            //     // var data = new FormData();
                            //     // data.append("data" , datapdf);
                            //     // var xhr = new XMLHttpRequest();
                            //     // xhr.open( 'post', base_url+'billing_statement/upload_pdf_v2', true ); //Post to php Script to save to server
                            //     // xhr.send(data);
                            //     // var b64PDFprefix = "data:application/pdf;base64,";
                            //     // var pdfBase64DataUri = doc.output("datauristring");
                            //     // var pdfBase64Content = pdfBase64DataUri.substring(b64PDFprefix.length, pdfBase64DataUri.length);
                            //     // // console.log('pdfBase64DataUri', pdfBase64Content);
                            //     // var imgBase64Content = imgBase64DataUri.substring(b64Prefix.length, imgBase64DataUri.length);
                            // });
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

        $('.us_mail_sent_date').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $('body').on('click','#popup_cancel_us_mail_sent_date',function(){
            $('.us_mail_sent_date').val('');
            $('.invoice_status_'+global_invoice_id).val(global_invoice_status);
            $('#us_mail_modal').modal('hide');
            console.log('previous_invoice_status_value', previous_value);
        });

        $('body').on('click','#popup_submit_us_mail_sent_date',function(){
            var form_data = $('#us_mail_form').serialize();
            jConfirm("Update invoice status?","Reminder",function(response){
                if (response) {
                    $.post(base_url + 'billing_statement/update_invoice_status_v2/', form_data, function(response){
                        var parse_data = JSON.parse(response);
                        console.log('parse_data', parse_data);

                        $('#us_mail_modal').modal('hide');
                        me_message_v2({error:parse_data.error, message: parse_data.message});
                        setTimeout(function(){
                            location.reload();
                        },1000);    
                    });
                } else {
                    $('.us_mail_sent_date').val('');
                    $('.invoice_status_'+global_invoice_id).val(global_invoice_status);
                    $('#us_mail_modal').modal('hide');
                    console.log('previous_invoice_status_value', previous_value);
                }
            });
        });

        $('body').on('change','.invoice-status',function(){
            var _this = $(this);
            var invoice_id = _this.attr('data-invoice-id');
            console.log('invoice-status', _this.val());
            var previous_value = $('.prev_invoice_status_'+invoice_id).val();
            global_invoice_status = previous_value;
            global_invoice_id = invoice_id;

            switch(_this.val()) {
                case 'email': $('.send_email_btn_'+invoice_id).click(); break;
                case 'us_mail':
                case 'fax':
                    $('#us_mail_invoice_id').val(invoice_id);
                    $('#us_mail_invoice_status').val(_this.val());
                    $('#us_mail_modal').modal('show'); break;
                    
                    // break;
                case 'pending':
                    jConfirm("Update invoice status?","Reminder",function(response){
                        if (response) {
                            $.post(base_url + 'billing_statement/update_invoice_status/' + invoice_id + '/' + _this.val(), function(response){
                                var parse_data = JSON.parse(response);
                                console.log('parse_data', parse_data);

                                me_message_v2({error:parse_data.error, message: parse_data.message});
                                setTimeout(function(){
                                    location.reload();
                                },1000);    
                            });
                        } else {
                            $('.invoice_status_'+invoice_id).val(previous_value);
                            console.log('previous_invoice_status_value', previous_value);
                        }
                        
                    });
                    break;
            }
        });

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

        //for the inserting of threaded comments in modal
        $('body').on('click','.enter-notes-btn',function(){
            var id = $(this).attr('data-id');
            var form_data = $('#enter-note-page').serialize();
            var this_element = $(this);

            jConfirm('Add Note ?', 'Reminder', function(response){
                if(response)
                {
                    $.post(base_url + 'billing_reconciliation/insert_invoice_comments/' + id,form_data, function(response){
                        var obj = $.parseJSON(response);
                        jAlert(obj['message'],'Response');
                        if(obj['error'] == 0)
                        {
                            var comment_count = $('body .notes_count_'+id).find('p').text();
                            $('body .notes_count_'+id).find('p').html(Number(comment_count)+1);

                            $('#notes_popup_modal').modal("hide");
                            // setTimeout(function(){
                            //   location.reload();
                            // },1000);
                        }
                        $('.comment_content').val('');
                    });
                }
            });
        });
	});

    $('.reconbutton').bind('click',function(){
        $('.reconciliation_receiving').submit();
    });

    $('.cancel_invoice_btn').bind('click',function(){
        // var draft_id = $(this).attr("data-draft-id");
        var _this = $(this);
        jConfirm("Are you sure you want to return statement activity to draft?","Reminder",function(response){
            if(response)
            {

                me_message_v2({error:2,message:"Returning statement activity to draft. Please wait..."});
                var selected_inv = _this.attr('data-invoice-id');

                console.log('selected_inv: ', selected_inv);
                $.get(base_url+'billing_statement/return_to_draft_statement_invoice_inquiry/'+selected_inv, function(response){
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

    //create_reconciliation
    $('body').on('click','.create_reconciliation',function(){
        var _this = $(this);
        var invoice_id = $('.reconciliation_invoice_id').val();
        var recon_credit = $('.recon_credit').val() == "" ? 0 : parseFloat($('.recon_credit').val());
        var recon_amount_owe = $('.recon_amount_owe').val() == "" ? 0 : parseFloat($('.recon_amount_owe').val());
        var recon_account_id = $('.recon_account_id').val();
        var recon_invoice_date = $('.recon_invoice_date').val();
        var recon_invoice_number = $('.recon_invoice_number').val();
        var recon_payment_amount = $('.recon_payment_amount').val();
        var recon_notes = $('.recon_notes').val();
        var recon_balance_due = $('.recon_balance_due').val();

        var token = "/";
        var newToken = "-";
        recon_invoice_date = recon_invoice_date.split(token).join(newToken);

        $.get(base_url+'billing_reconciliation/insert_reconciliation/'+invoice_id+'/'+recon_credit+'/'+recon_amount_owe+'/'+recon_account_id+'/'+recon_invoice_date+'/'+recon_invoice_number+'/'+recon_payment_amount+'/'+recon_notes+'/'+recon_balance_due, function(response){
            var obj = JSON.parse(response);             // $('.close').click();
            $('.recon_notes').val("");
            $('.recon_balance_due').val("");
            $('.recon_amount_owe').val("");
            $('.recon_credit').val("");
            $('.recon_invoice_date').val("");
            $('.recon_invoice_number').val("");
            $('.recon_payment_amount').val("");
            setTimeout(function(){
                if(obj['error'] == 0)
                {
                    me_message_v2({error:0,message:obj['message']});
                    // setTimeout(function(){
                    //     location.reload();
                    // },2000);
                } else {
                    me_message_v2({error:1,message:obj['message']});
                }
            },1);
            
        });
    });

    // Add Comments
    $('body').on('click','.add_comments',function(){
        var invoice_id = $('.reconciliation_invoice_id').val();
        $('.invoice_id_input').val(invoice_id);
        $('#notes_popup_modal').modal("show");
    });

    //receive-payments-btn
    $('body').on('click','.receive-payments-btn',function(){
        var _this = $(this);
        var invoice_id = $(this).attr("data-invoice-id");
        var data_hospice_id = $(this).attr("data-hospice-id");
        var selected_invoices = $('.inquiry-checkbox');
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
        modalbox(base_url + 'billing_reconciliation/receive_payments/'+selected_inv,{
            header:"<div style='padding-left: 10px; padding-right: 28px;'><div class='row' style=''><div class='col-md-5 inv-num' style=''><span style='margin-left: 10px'>Invoice Number</span> <span class='pull-right'><span class='selected_count' data-selected-count='0'>0</span> / <span class='selected_limit' data-selected-limit="+counter+">"+counter+"</span></span></div><div class='col-md-6 rec-pay' style='text-align: center; border-left: 1px solid #e5e5e5;'>Receive Payment</div></div></div>",
            button: false,
        });
    });

    $('.return-to-btn').bind('click',function(){
        // var draft_id = $(this).attr("data-draft-id");

        jConfirm("Are you sure you want to return statement activity to draft?","Reminder",function(response){
            if(response)
            {

                me_message_v2({error:2,message:"Returning statement activity to draft. Please wait..."});
                var selected_invoices = $('.inquiry-checkbox');
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

    // View statement bill Details
    $('body').on('click','.view_invoice_details',function(){
        var _this = $(this);
        var invoice_id = $(this).attr("data-invoice-id");
        var data_hospice_id = $(this).attr("data-hospice-id");
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

    //SELECT ALL CHECKBOXES
    $('.datatable_table_statement_draft').on('click','.all-inquiry-checkbox',function(){
        var statement_bill = $('.inquiry-checkbox');
        if($(this).is(':checked'))
        {
            statement_bill.each(function(){
                $(this).prop('checked', true);
                $("body").find(".receive-payments-btn").removeAttr('disabled');
                $("body").find(".attach-file-btn").removeAttr('disabled');
                $("body").find(".email-btn").removeAttr('disabled');
            });
        }
        else
        {
            statement_bill.each(function(){
                $(this).prop('checked', false);
                $("body").find(".receive-payments-btn").attr('disabled', true);
                $("body").find(".attach-file-btn").attr('disabled', true);
                $("body").find(".email-btn").attr('disabled', true);
            });
        }
    });

    //Check by one
    $('.datatable_table_statement_draft').on('click','.inquiry-checkbox',function(){
        var this_element = $(this);
        var value = $(this).val();
        // var _this = $('.create-invoice:checked');

        if(this_element.is(':checked'))
        {
            $("body").find(".receive-payments-btn").removeAttr('disabled');
                $("body").find(".attach-file-btn").removeAttr('disabled');
                $("body").find(".email-btn").removeAttr('disabled');
        }
        else
        {
            var _this = $('.inquiry-checkbox:checked');
            if (_this.length == 0) {
                $("body").find(".receive-payments-btn").attr('disabled', true);
                $("body").find(".attach-file-btn").attr('disabled', true);
                $("body").find(".email-btn").attr('disabled', true);
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
                var selected_invoices = $('.inquiry-checkbox');
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
                var selected_invoices = $('.inquiry-checkbox');
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

    $('.send-invoice-email-btn').bind('click',function(){
        // var draft_id = $(this).attr("data-draft-id");

        jConfirm("Send Invoice Via Email?","Reminder",function(response){
            if(response)
            {

                // me_message_v2({error:2,message:"Saving data. Please wait..."});
                $('#loader_modal').modal('show');
                var selected_invoices = $('.inquiry-checkbox');
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
                $.get(base_url+'billing_statement/send_invoice_via_email/'+selected_inv, function(response){
                    console.log("resepposnse:", response);
                    var obj = $.parseJSON(response);
                    console.log("closecloseclose:", obj);
                    // $('.close').click();
                    console.log(obj['no_email'].length, obj['no_email']);
                    if(obj['no_email'].length > 0) {
                        var html = "";
                        for(var i = 0; i < obj['no_email'].length; i++) {
                            var inv_details = obj['no_email'][i];
                            html += "<div>#INV " + inv_details.invoice_no.substr(4,6) + " ● " + inv_details.hospice_account_number + " - " + inv_details.hospice_name + "</div>";
                        }
                        $('#no_email').html(html);
                        $('#no_email_modal').modal('show');
                    }
                    setTimeout(function(){
                        me_message_v2({error:obj['error'],message:obj['message']});
                    },1);

                    $('#loader_modal').modal('hide');
                });
            }
        });
    });
    //popup_ok_no_email
    $('#popup_ok_no_email').bind('click',function(){
        $('#no_email_modal').modal('hide');
        location.reload();
    });
</script>