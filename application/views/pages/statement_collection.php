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

    .select_invoice_status {
        cursor: pointer;
    }

    .delete_invoice_number_span {
        font-weight: bold;
    }
    
    #popup_delete_invoice:disabled {
        opacity: 0.5
    }

    .isa_error{
        border-radius: 2px;
        text-align: center;
        color: #D8000C;
        background-color: #FFD2D2;
        margin: 10px 0px;
        padding:12px;
    }

    .isa_error i {
        /* margin:10px 22px; */
        margin-right: 10px;
        font-size:2em;
        vertical-align:middle;
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
            Collections
            <div class="pull-right">
                <button class="btn btn-primary btn-sm send-to-invoice-btn" style="font-size:13px !important;" disabled>
                    Send to Invoice Inquiry
                </button>
                &nbsp;&nbsp;
            </div>
        </h1>
    </div>

    <div class="wrapper-md">
        <div class="panel panel-default">
            <div class="panel-heading">
                Collection List
            </div>
            <div class="panel-body" style="padding: 0px;">
                <div class="col-xs-12 col-sm-12 col-md-12 hidden-print" style="margin-top:20px;">
                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
                        <span class="pull-right">
                            From
                            <input type="text" class="form-control choose_date filter_from" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 40px;" >
                            <!-- value="<?php echo date("Y-m-d"); ?>" -->
                        </span>
                    </div>
                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
                        <span class="pull-left">
                            To
                            <input type="text" class="form-control choose_date filter_to" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 25px;" >
                            <!-- value="<?php echo date("Y-m-d"); ?>" -->
                        </span>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:8px;">
                    <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3" style="margin-top:14px;">
                        <?php
                            echo date('h:i A');
                        ?>
                    </div>
                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6 select_hospice_container" style="text-align:center;">
                        <?php
                            if($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "biller")
                            {
                                $selected = "";
                                $hospices = get_hospices_v2($this->session->userdata('user_location'));
                                $companies = get_companies_v2($this->session->userdata('user_location'));
                        ?>
                                <select class="form-control pull-center filter_activity_status_details select2-ready" name="filter_activity_status_details" style="margin-top: 6px;border: 0px;font-size: 16px;font-weight: 600;text-align-last:center;">
                                    <option value="0">Advantage Home Medical Services</option>
                                    <optgroup label="Hospices">
                                    <?php
                                        foreach($hospices as $hospice) :
                                            // if($hospice['hospiceID'] != 13) {
                                    ?>
                                                <option value="<?php echo $hospice['hospiceID'] ?>" <?php if($hospiceID == $hospice['hospiceID']){ echo "selected"; } ?> ><?php echo $hospice['hospice_name'] ?></option>
                                    <?php
                                            // }
                                        endforeach;
                                    ?>
                                    </optgroup>
                                    <?php
                                        if(!empty($companies))
                                        {
                                    ?>
                                            <optgroup label="Commercial Account">
                                            <?php
                                                foreach($companies as $company) :
                                                    if($company['hospiceID'] != 13) {
                                                        if($hospiceID == $company['hospiceID'])
                                                        {
                                                            $selected = "selected";
                                                        }
                                            ?>
                                                        <option value="<?php echo $company['hospiceID'] ?>" <?php if($hospiceID == $hospice['hospiceID']){ echo "selected"; } ?> ><?php echo $company['hospice_name'] ?></option>
                                            <?php
                                                    }
                                                endforeach;
                                            ?>
                                            </optgroup>
                                    <?php
                                        }
                                    ?>
                                </select>
                        <?php
                            }else{
                                $hospice = $this->session->userdata('group_id');
                                $hospice_info = get_hospice_name($hospice);
                        ?>
                                <div style="font-weight:600;font-size:16px;margin-top:13px;"> <?php echo $hospice_info['hospice_name']; ?> </div>
                        <?php
                            }
                        ?>
                    </div>
                    <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

                    </div>

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:2px;">
                    <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">
                        <?php
                            $current_date = date('Y-m-d');
                            echo date("m/d/Y", strtotime($current_date))
                        ?>
                    </div>
                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="text-align:center;margin-top:7px;">
                        Collections
                    </div>
                    <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3">

                    </div>

                </div>
                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:4px;">
                    <div class="col-xxs-12 col-xs-4 col-sm-4 col-md-4">
                    TOTAL PAYMENT AMOUNT: <span id="total_payment_amount_invoice_list_queried"></span>
                    </div>
                    <div class="col-xxs-12 col-xs-4 col-sm-4 col-md-4" style="text-align:center;">
                        <span id="viewed_current_date">
                        <?php
                            $current_date = date('Y-m-d');
                            echo date("F d, Y", strtotime($current_date))
                        ?>
                        </span>
                    </div>
                    <!-- <div class="col-xxs-12 col-xs-4 col-sm-4 col-md-4" style="padding-left: 0px !important; padding-right: 0px !important">
                        <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 select_invoice_status select_invoice_status_pending">
                            Pending: <span class="pending_counter">0</span>
                        </div>
                        <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 select_invoice_status select_invoice_status_email">
                            Email: <span class="email_counter">0</span>
                        </div>
                        <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 select_invoice_status select_invoice_status_us_mail">
                            US Mail: <span class="us_mail_counter">0</span>
                        </div>
                        <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 select_invoice_status select_invoice_status_fax">
                            Fax: <span class="fax_counter">0</span>
                        </div>
                    </div> -->
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:30px;">
                <input type="hidden" name="activity_status_name" id="activity_status_name" value="<?php echo $activity_status_name_new_v2; ?>">

                <table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px; text-align: center;">

                    <thead style="background-color:rgba(97, 101, 115, 0.05);">
                        <tr>
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
                        </tr>
                    </thead>
                    <tbody class="invoice_list_tbody">
                    </tbody>
                </table>
                
                </div>
                <div class="pagination-container hidden-print">
                    <nav aria-label="...">
                        <ul class="pager">
                            <li class="previous disabled"><a href="javascript:;"><span aria-hidden="true">&larr;</span> Previous</a></li>
                            <li class="next"><a href="javascript:;">Next <span aria-hidden="true">&rarr;</span></a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:10px;margin-bottom:10px;">
                    <!-- <div class="col-xxs-12 col-xs-10 col-sm-10 col-md-10" style="padding-left: 95px">
                        TOTAL PAYMENT AMOUNT: <span id="total_payment_amount_invoice_list_queried"></span>
                    </div> -->
                    <div class="col-xxs-12 col-xs-2 col-sm-2 col-md-2">
                        TOTAL INVOICES: <span id="total_invoice_list_queried"></span>
                    </div>
                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">

                    </div>
                    <!-- <div class="col-xxs-12 col-xs-4 col-sm-4 col-md-4" style="padding-left: 0px !important; padding-right: 0px !important">
                        <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 select_invoice_status select_invoice_status_pending">
                            Pending: <span class="pending_counter">0</span>
                        </div>
                        <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 select_invoice_status select_invoice_status_email">
                            Email: <span class="email_counter">0</span>
                        </div>
                        <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 select_invoice_status select_invoice_status_us_mail">
                            US Mail: <span class="us_mail_counter">0</span>
                        </div>
                        <div class="col-xxs-12 col-xs-3 col-sm-3 col-md-3 select_invoice_status select_invoice_status_fax">
                            Fax: <span class="fax_counter">0</span>
                        </div>
                    </div> -->
                    
                </div>
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
                        <button type="button" class="btn btn-danger add_comments"  style="display: none"> No</button>
                        <button type="button" class="btn btn-danger close_reconciliation"> No</button>
                        <button type="submit" class="btn btn-success create_reconciliation" style="margin-left: 50px"> Yes</button>
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

<div class="modal fade" id="reconciliation_yes" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 10011 !important">
    <div class="modal-dialog" style="width: 438px !important;">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
            <div class="modal-header">
                <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reminder</h4>
            </div>
            <div class="modal-body OpenSans-Reg">
                Credit or owe will be applied to the current account statement.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger reconciliation_yes_decline">Decline</button>
                <button type="button" class="btn btn-success reconciliation_yes_accept">Accept</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reconciliation_no" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 10011 !important">
    <div class="modal-dialog" style="width: 438px !important;">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
            <div class="modal-header">
                <!--<button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reminder</h4>
            </div>
            <div class="modal-body OpenSans-Reg">
                Are you sure you want to decline credit or owe?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger reconciliation_no_decline">Decline</button>
                <button type="button" class="btn btn-success reconciliation_no_accept">Accept</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_invoice_modal" tabindex="-1" data-category="1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 450px !important;">
        <div class="modal-content" style="margin-top:150px;border-radius:2px;-webkit-box-shadow: 0 3px 1px rgba(0, 0, 0, .5);">
        <!-- text-transform: uppercase; -->
            <div class="modal-header">
                <button type="button" class="close btn-close-x" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title OpenSans-Reg" id="myModalLabel">Reminder</h4>
            </div>
            <form id="delete_invoice_form">
                <div class="modal-body OpenSans-Reg"style="padding-bottom: 0px;">
                    <div class="no_email_content">
                        <input type="hidden" id="delete_invoice_id" value="" name="delete_invoice_id">
                        <input type="hidden" id="delete_invoice_number" value="" name="delete_invoice_number">
                        <div id="" style="margin: 1em 1em 0em 1em !important;">
                            This will be permanently deleted INV#<span class="delete_invoice_number_span delete_invoice_number_span1"></span> Service Date: <span class="delete_invoice_number_span delete_service_date_span"></span>.
                            <br><br>
                            <div class="form-group" style="margin-right:0px; text-align:center" id="">
                                <label style="margin-left:5px;"> Please type <span class="delete_invoice_number_span delete_invoice_number_span2"></span> to confirm.</label>
                                <input type="text" class="form-control invoice_number_input" value="" placeholder="" name="invoice_number_input" style="">
                            </div>
                        </div>
                    </div>
                    <div class="error_content"></div>
                </div>
                <div class="modal-footer" id="popup_panel" style="margin: 1em 1em 0em 1em !important;">
                    <div>
                        <button type="button" class="btn btn-danger" id="popup_delete_invoice" style="width: 100%" autocomplete="off" disabled>
                            Delete Invoice
                        </button>
                    </div>
                    <div style="margin-bottom: 20px">
                        <button type="button" class="btn btn-danger" id="popup_deleting_invoice" style="width: 100%; cursor: default !important; opacity: 1; display: none" autocomplete="off" disabled>
                            <i class='fa fa-spin fa-spinner'></i> Deleting Invoice
                        </button>
                    </div>
                    <div style="padding-top: 5px; margin-bottom: 15px" id="delete_invoice_error_message_wrapper" style="display: none">
                        <div class="isa_error">
                            <i class="fa fa-times-circle"></i>
                            <span id="delete_invoice_error_message"></span>
                        </div>
                    </div>
                    <!-- <button type="button" class="btn btn-default" id="popup_cancel_delete_invoice" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove"></span> &nbsp;Cancel&nbsp;
                    </button> -->
                </div>
            </form>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default btn-order-close pull-left">Cancel</button>
                <button type="button" class="btn btn-primary btn-order save_floor_mat_capped">Save changes</button>
            </div> -->
        </div>
    </div>
</div>

<input type="hidden" id="invoice_status_selected" value="no_selected">
<input type="hidden" id="temp_date_today" value="<?php echo date('Y-m-d'); ?>">
<input type="hidden" id="get_base_url" value="<?php echo base_url(); ?>">
<div id="iframe_container" style="visibility: hidden"></div>
<!-- visibility: hidden -->

<!-- REFERENCE to html2canvas utility https://github.com/niklasvh/html2canvas -->
<script src="<?php echo base_url(); ?>assets/js/html2canvas.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/jspdf.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/html2pdf.js-master/dist/html2pdf.bundle.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $('body').on('click','.attach-file-btn',function(){
        jConfirm('Credit?','Response',function(response) { 
            if(response)
            {
                console.log('YES! ni work!');
            }
        });
    });

    var totalcount = 0;
	var currenttotal = 0;
	var datatoexport = "";
    var export_global_invoice_status = "no_selected";

    var invoice_list_content = function(page, invoice_status=''){  
        export_global_invoice_status = invoice_status;
        var filter_from = $("body").find(".filter_from").val();
        var filter_to = $("body").find(".filter_to").val();
        var hospiceID = $("body").find(".filter_activity_status_details").val();
        var invoice_list_tbody = $("body").find(".invoice_list_tbody");
        var total_invoice_list_queried = $("body").find("#total_invoice_list_queried");
        var viewed_current_date = $("body").find("#viewed_current_date");
        var temp_date_today = $("body").find("#temp_date_today").val();
        var baseurl = $('#get_base_url').val();

        if(filter_from == "")
        {
            filter_from = 0;
        } else {
            var temp_from = filter_from.split(/\s*\-\s*/g);
            filter_from = temp_from[2] + '-' + temp_from[0] + '-' + temp_from[1]
        }

        if(filter_to == "")
        {
            filter_to = 0;
        } else {
            var temp_to = filter_to.split(/\s*\-\s*/g);
            filter_to = temp_to[2] + '-' + temp_to[0] + '-' + temp_to[1]
        }

		if(filter_from != 0 && filter_to != 0)
		{
			invoice_list_tbody.html("<tr><td colspan='11' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");

			var new_viewed_date = "";
			var month_name = "";
			var separated_from = filter_from.split(/\s*\-\s*/g);
			var separated_to = filter_to.split(/\s*\-\s*/g);

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
			viewed_current_date.html(new_viewed_date);

			var temp_html = "";
			var pagenum = 1;
			if(typeof(page)!="undefined"){
				pagenum = page*1;
			}
			console.log('nasud');
			$.post(base_url+"billing_statement/load_more_invoice_inquiry/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ pagenum+"/"+invoice_status+"/1","", function(response){
                var obj = $.parseJSON(response);

                totalcount = obj.pagination_details.total_records;
                console.log(obj);
                if(obj.pagination_details.total_pages < 2){
                    $('.pagination-container').addClass("hidden");
                }
                else{
                    $('.pagination-container').removeClass("hidden");
                    var current_page = obj.pagination_details.current_page*1;
                    if(current_page-1==0){
                        $('.pagination-container').find('.previous').addClass("disabled");
                    }
                    else{
                        $('.pagination-container').find('.previous').removeClass("disabled");
                        $('.pagination-container').find('.previous').attr("data-page",(current_page-1));
                    }
                    if(current_page+1>obj.pagination_details.total_pages){
                        $('.pagination-container').find('.next').addClass("disabled");
                    }
                    else{
                        $('.pagination-container').find('.next').removeClass("disabled");
                        $('.pagination-container').find('.next').attr("data-page",(current_page+1));
                    }
                }
                for(var i = 0; i < obj.statement_invoices.length; i++) {
                    var service_date_from = obj.statement_invoices[i].service_date_from.split('-');
                    var service_date_to = obj.statement_invoices[i].service_date_to.split('-');

                    var service_date = service_date_from[1]+"/"+service_date_from[2]+"/"+service_date_from[0]+"-"+service_date_to[1]+"/"+service_date_to[2]+"/"+service_date_to[0];

                    var invoice_status_pending = '';
                    var invoice_status_email = '';
                    var invoice_status_us_mail = '';
                    var invoice_status_fax = '';

                    if(obj.statement_invoices[i].invoice_status == 'pending') {
                        invoice_status_pending = 'selected';
                    }

                    if(obj.statement_invoices[i].invoice_status == 'email') {
                        invoice_status_email = 'selected';
                    }

                    if(obj.statement_invoices[i].invoice_status == 'us_mail') {
                        invoice_status_us_mail = 'selected';
                    }

                    if(obj.statement_invoices[i].invoice_status == 'fax') {
                        invoice_status_fax = 'selected';
                    }


                    temp_html += '<tr>'; // Start of TR

                    // Checkboxes
                    temp_html += '<td class="hidden-print">';
                    temp_html += '<label class="i-checks data_tooltip" ><input type="checkbox" class="inquiry-checkbox" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" name=""/><i></i></label>';
                    temp_html += '</td>';

                    // Status
                    temp_html += '<td>';
                    temp_html += '<div class="">';
                    switch (obj.statement_invoices[i].invoice_status) {
                        case 'pending': temp_html += 'Pending'; break;
                        case 'email': temp_html += 'Email'; break;
                        case 'us_mail': temp_html += 'US Mail'; break;
                        case 'fax': temp_html += 'Fax'; break;
                    }
                    temp_html += '</div>';
                    temp_html += '</td>';

                    // Send Date
                    temp_html += '<td>';
                    var new_sent_date = '';
                    var format_new_sent_date = '';
                    if (obj.statement_invoices[i].invoice_status == 'us_mail' || obj.statement_invoices[i].invoice_status == 'fax') {
                        new_sent_date = temp_date_today;
                        if (obj.statement_invoices[i].email_sent_date != '' && obj.statement_invoices[i].email_sent_date != null && obj.statement_invoices[i].email_sent_date != '0000-00-00') {
                            new_sent_date = obj.statement_invoices[i].email_sent_date;
                        }
                        temp_html += '<a href="javascript:;" id="email_sent_date" data-pk="'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-url="'+baseurl+'billing_statement/update_invoice_sent_date/'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-title="Enter Date" data-value="'+new_sent_date+'" data-type="combodate" data-maxYear="'+new_sent_date.split('-')[0]+'" data-format="YYYY-MM-DD" data-viewformat="MM/DD/YYYY" data-template="MMM / D / YYYY" class="data_tooltip editable editable-click editable-combodate">';
                        var split_new_sent_date = new_sent_date.split('-');
                        format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                        temp_html += format_new_sent_date;
                        temp_html += '</a>';
                    } else {
                        if (obj.statement_invoices[i].email_sent_date != '' && obj.statement_invoices[i].email_sent_date != null && obj.statement_invoices[i].email_sent_date != '0000-00-00') {
                            var split_new_sent_date = obj.statement_invoices[i].email_sent_date.split('-');
                            format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                            temp_html += format_new_sent_date;
                        }
                    }
                    temp_html += '</td>';

                    // Hospice Account Number
                    temp_html += '<td>';
                    temp_html += obj.statement_invoices[i].hospice_account_number;
                    temp_html += '</td>';
                    
                    // Hospice Name
                    temp_html += '<td>';
                    temp_html += obj.statement_invoices[i].hospice_name;
                    temp_html += '</td>';

                    // Invoice Number
                    temp_html += '<td>';
                    temp_html += '<div style="cursor: pointer" class="view_invoice_details" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_invoices[i].hospiceID+'">'+obj.statement_invoices[i].statement_no.substr(3, 10)+'</div>';
                    temp_html += '</td>';

                    // Service Date
                    temp_html += '<td>';
                    temp_html += service_date;
                    temp_html += '</td>';

                    // Total Balance Due
                    temp_html += '<td>';
                    var total_balance_due = 0;
                    total_balance_due += parseFloat(obj.statement_invoices[i].total);
                    total_balance_due += parseFloat(obj.statement_invoices[i].non_cap);
                    total_balance_due += parseFloat(obj.statement_invoices[i].purchase_item);
                    total_balance_due -= parseFloat(obj.invoices_reconciliation[i].credit);
                    total_balance_due += parseFloat(obj.invoices_reconciliation[i].owe);
                    temp_html += total_balance_due.toFixed(2);
                    temp_html += '</td>';

                    // Payment Code
                    temp_html += '<td>';
                    if (obj.statement_invoices[i].payment_code != null && obj.statement_invoices[i].payment_code != '') {
                        if (obj.statement_invoices[i].payment_amount != null && obj.statement_invoices[i].payment_amount != '' && obj.statement_invoices[i].payment_amount != 0) {
                            temp_html += obj.statement_invoices[i].payment_amount.toFixed(2);
                        }
                    }
                    temp_html += '</td>';

                    // Payment Date
                    temp_html += '<td>';
                    if (obj.statement_invoices[i].payment_code != null && obj.statement_invoices[i].payment_code != '') {
                        if (obj.statement_invoices[i].payment_date != null && obj.statement_invoices[i].payment_date != '' && obj.statement_invoices[i].payment_date != '0000-00-00 00:00:00') {
                            var split_new_sent_date = obj.statement_invoices[i].payment_date.split('-');
                            format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                            temp_html += format_new_sent_date;
                        }
                    }
                    temp_html += '</td>';

                    // Actions
                    temp_html += '<td class="hidden-print">';
                    // Delete
                    temp_html += '<button class="delete_invoice_btn delete_collection_btn_'+obj.statement_invoices[i].acct_statement_invoice_id+' btn btn-xs btn-danger" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_invoices[i].hospiceID+'" data-invoice-number="'+obj.statement_invoices[i].statement_no+'" data-service-date="'+service_date+'"><Strong>Delete</Strong></button>';
                    temp_html += '</td>';
                    
                    temp_html += '</tr>'; // End of TR
                }
                if(temp_html == "") {
                    temp_html = '<td colspan="12" style="text-align:center; padding: 10px"> No Invoices. </td>';
                }
                invoice_list_tbody.html(temp_html);
                total_invoice_list_queried.html(obj.pagination_details.total_records);

                get_total_payment_amount();			
            });
		} else {
            invoice_list_tbody.html("<tr><td colspan='11' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");
            
            var temp_html = "";
			var pagenum = 1;
			if(typeof(page)!="undefined"){
				pagenum = page*1;
			}
			console.log('nasud sa else');
			$.post(base_url+"billing_statement/load_more_invoice_inquiry/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ pagenum + "/" + invoice_status+"/1","", function(response){
                var obj = $.parseJSON(response);

                totalcount = obj.pagination_details.total_records;
                console.log(obj);
                if(obj.pagination_details.total_pages < 2){
                    $('.pagination-container').addClass("hidden");
                }
                else{
                    $('.pagination-container').removeClass("hidden");
                    var current_page = obj.pagination_details.current_page*1;
                    if(current_page-1==0){
                        $('.pagination-container').find('.previous').addClass("disabled");
                    }
                    else{
                        $('.pagination-container').find('.previous').removeClass("disabled");
                        $('.pagination-container').find('.previous').attr("data-page",(current_page-1));
                    }
                    if(current_page+1>obj.pagination_details.total_pages){
                        $('.pagination-container').find('.next').addClass("disabled");
                    }
                    else{
                        $('.pagination-container').find('.next').removeClass("disabled");
                        $('.pagination-container').find('.next').attr("data-page",(current_page+1));
                    }
                }
                for(var i = 0; i < obj.statement_invoices.length; i++) {
                    var service_date_from = obj.statement_invoices[i].service_date_from.split('-');
                    var service_date_to = obj.statement_invoices[i].service_date_to.split('-');

                    var service_date = service_date_from[1]+"/"+service_date_from[2]+"/"+service_date_from[0]+"-"+service_date_to[1]+"/"+service_date_to[2]+"/"+service_date_to[0];

                    var invoice_status_pending = '';
                    var invoice_status_email = '';
                    var invoice_status_us_mail = '';
                    var invoice_status_fax = '';

                    if(obj.statement_invoices[i].invoice_status == 'pending') {
                        invoice_status_pending = 'selected';
                    }

                    if(obj.statement_invoices[i].invoice_status == 'email') {
                        invoice_status_email = 'selected';
                    }

                    if(obj.statement_invoices[i].invoice_status == 'us_mail') {
                        invoice_status_us_mail = 'selected';
                    }

                    if(obj.statement_invoices[i].invoice_status == 'fax') {
                        invoice_status_fax = 'selected';
                    }


                    temp_html += '<tr>'; // Start of TR

                    // Checkboxes
                    temp_html += '<td class="hidden-print">';
                    temp_html += '<label class="i-checks data_tooltip" ><input type="checkbox" class="inquiry-checkbox" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" name=""/><i></i></label>';
                    temp_html += '</td>';

                    // Status
                    temp_html += '<td>';
                    temp_html += '<div class="">';
                    switch (obj.statement_invoices[i].invoice_status) {
                        case 'pending': temp_html += 'Pending'; break;
                        case 'email': temp_html += 'Email'; break;
                        case 'us_mail': temp_html += 'US Mail'; break;
                        case 'fax': temp_html += 'Fax'; break;
                    }
                    temp_html += '</div>';
                    temp_html += '</td>';

                    // Send Date
                    temp_html += '<td>';
                    var new_sent_date = '';
                    var format_new_sent_date = '';
                    if (obj.statement_invoices[i].invoice_status == 'us_mail' || obj.statement_invoices[i].invoice_status == 'fax') {
                        new_sent_date = temp_date_today;
                        if (obj.statement_invoices[i].email_sent_date != '' && obj.statement_invoices[i].email_sent_date != null && obj.statement_invoices[i].email_sent_date != '0000-00-00') {
                            new_sent_date = obj.statement_invoices[i].email_sent_date;
                        }
                        temp_html += '<a href="javascript:;" id="email_sent_date" data-pk="'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-url="'+baseurl+'billing_statement/update_invoice_sent_date/'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-title="Enter Date" data-value="'+new_sent_date+'" data-type="combodate" data-maxYear="'+new_sent_date.split('-')[0]+'" data-format="YYYY-MM-DD" data-viewformat="MM/DD/YYYY" data-template="MMM / D / YYYY" class="data_tooltip editable editable-click editable-combodate">';
                        var split_new_sent_date = new_sent_date.split('-');
                        format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                        temp_html += format_new_sent_date;
                        temp_html += '</a>';
                    } else {
                        if (obj.statement_invoices[i].email_sent_date != '' && obj.statement_invoices[i].email_sent_date != null && obj.statement_invoices[i].email_sent_date != '0000-00-00') {
                            var split_new_sent_date = obj.statement_invoices[i].email_sent_date.split('-');
                            format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                            temp_html += format_new_sent_date;
                        }
                    }
                    temp_html += '</td>';

                    // Hospice Account Number
                    temp_html += '<td>';
                    temp_html += obj.statement_invoices[i].hospice_account_number;
                    temp_html += '</td>';
                    
                    // Hospice Name
                    temp_html += '<td>';
                    temp_html += obj.statement_invoices[i].hospice_name;
                    temp_html += '</td>';

                    // Invoice Number
                    temp_html += '<td>';
                    temp_html += '<div style="cursor: pointer" class="view_invoice_details" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_invoices[i].hospiceID+'">'+obj.statement_invoices[i].statement_no.substr(3, 10)+'</div>';
                    temp_html += '</td>';

                    // Service Date
                    temp_html += '<td>';
                    temp_html += service_date;
                    temp_html += '</td>';

                    // Total Balance Due
                    temp_html += '<td>';
                    var total_balance_due = 0;
                    total_balance_due += parseFloat(obj.statement_invoices[i].total);
                    total_balance_due += parseFloat(obj.statement_invoices[i].non_cap);
                    total_balance_due += parseFloat(obj.statement_invoices[i].purchase_item);
                    total_balance_due -= parseFloat(obj.invoices_reconciliation[i].credit);
                    total_balance_due += parseFloat(obj.invoices_reconciliation[i].owe);
                    temp_html += total_balance_due.toFixed(2);
                    temp_html += '</td>';

                    // Payment Code
                    temp_html += '<td>';
                    if (obj.statement_invoices[i].payment_code != null && obj.statement_invoices[i].payment_code != '') {
                        if (obj.statement_invoices[i].payment_amount != null && obj.statement_invoices[i].payment_amount != '' && obj.statement_invoices[i].payment_amount != 0) {
                            temp_html += obj.statement_invoices[i].payment_amount.toFixed(2);
                        }
                    }
                    temp_html += '</td>';

                    // Payment Date
                    temp_html += '<td>';
                    if (obj.statement_invoices[i].payment_code != null && obj.statement_invoices[i].payment_code != '') {
                        if (obj.statement_invoices[i].payment_date != null && obj.statement_invoices[i].payment_date != '' && obj.statement_invoices[i].payment_date != '0000-00-00 00:00:00') {
                            var split_new_sent_date = obj.statement_invoices[i].payment_date.split('-');
                            format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                            temp_html += format_new_sent_date;
                        }
                    }
                    temp_html += '</td>';

                    // Actions
                    temp_html += '<td class="hidden-print">';
                    // Delete
                    temp_html += '<button class="delete_invoice_btn delete_collection_btn_'+obj.statement_invoices[i].acct_statement_invoice_id+' btn btn-xs btn-danger" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_invoices[i].hospiceID+'" data-invoice-number="'+obj.statement_invoices[i].statement_no+'" data-service-date="'+service_date+'"><Strong>Delete</Strong></button>';
                    temp_html += '</td>';
                    
                    temp_html += '</tr>'; // End of TR
                }
                if(temp_html == "") {
                    temp_html = '<td colspan="12" style="text-align:center; padding: 10px"> No Invoices. </td>';
                }
                invoice_list_tbody.html(temp_html);
                total_invoice_list_queried.html(obj.pagination_details.total_records);	
			
                get_total_payment_amount();
            });
        }

    };

    var exportAsCSV = function(csvData,filter_from,filter_to) {
		var blob = new Blob(['\ufeff' + csvData], { type: 'text/csv;charset=utf-8;' });
        var dwldLink = document.createElement('a');
        var url = URL.createObjectURL(blob);
        var isSafariBrowser = navigator.userAgent.indexOf('Safari') !== -1 && navigator.userAgent.indexOf('Chrome') === -1;
        if (isSafariBrowser) {  // if Safari open in new window to save file with random filename.
            dwldLink.setAttribute('target', '_blank');
        }
        var file_name = '';
        if (filter_from != 0 && filter_to != 0) {
			var date_from = filter_from.replaceAll('-', '');
			var date_to = filter_to.replaceAll('-', '');
          file_name = 'Invoice_Inquiry_' + date_from + '-' + date_to + '.csv';
        } else {
          file_name = 'Invoice_Inquiry.csv';
        }
        dwldLink.setAttribute('href', url);
        dwldLink.setAttribute('download', file_name);
        dwldLink.style.visibility = 'hidden';
        document.body.appendChild(dwldLink);
        dwldLink.click();
        document.body.removeChild(dwldLink);
	}

	var export_invoice_inquiry_recursion = function (filter_from,filter_to,hospiceID,pagenum,invoice_status) {
		if (currenttotal < totalcount) {
			$.post(base_url+"billing_statement/load_more_invoice_inquiry/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ pagenum + "/" + invoice_status+"/1","", function(response){
				var obj = $.parseJSON(response);
				console.log('export obj', obj);

				currenttotal += obj.statement_invoices.length;
				pagenum++;
				for(var i = 0; i < obj.statement_invoices.length; i++) {
					var service_date_from = obj.statement_invoices[i].service_date_from.split('-');
                    var service_date_to = obj.statement_invoices[i].service_date_to.split('-');

                    var service_date = service_date_from[1]+"/"+service_date_from[2]+"/"+service_date_from[0]+"-"+service_date_to[1]+"/"+service_date_to[2]+"/"+service_date_to[0];

                    if (obj.statement_invoices[i].email_sent_date != '' && obj.statement_invoices[i].email_sent_date != null && obj.statement_invoices[i].email_sent_date != '0000-00-00') {
                        var split_new_sent_date = obj.statement_invoices[i].email_sent_date.split('-');
                        format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                    }

                    var total_balance_due = 0;
                    total_balance_due += parseFloat(obj.statement_invoices[i].total);
                    total_balance_due += parseFloat(obj.statement_invoices[i].non_cap);
                    total_balance_due += parseFloat(obj.statement_invoices[i].purchase_item);
                    total_balance_due -= parseFloat(obj.invoices_reconciliation[i].credit);
                    total_balance_due += parseFloat(obj.invoices_reconciliation[i].owe);

                    if (obj.statement_invoices[i].invoice_status != null && obj.statement_invoices[i].invoice_status != undefined) {
						datatoexport += obj.statement_invoices[i].invoice_status+','; // Status
					} else {
						datatoexport += ',';
					}

					datatoexport += format_new_sent_date+','; // Sent Date

                    if (obj.statement_invoices[i].hospice_account_number != null && obj.statement_invoices[i].hospice_account_number != undefined) {
						datatoexport += obj.statement_invoices[i].hospice_account_number+','; // Account Number
					} else {
						datatoexport += ',';
					}

                    if (obj.statement_invoices[i].hospice_name != null && obj.statement_invoices[i].hospice_name != undefined) {
						datatoexport += '"'+obj.statement_invoices[i].hospice_name+'",'; // Account Name
					} else {
						datatoexport += ',';
					}

                    datatoexport += obj.statement_invoices[i].statement_no.substr(3, 10)+','; // Invoice Number
					datatoexport += service_date+','; // Service Date

					if (total_balance_due != null && total_balance_due != undefined) {
						datatoexport += parseFloat(total_balance_due).toFixed(2)+','; // Balance Due
					} else {
						datatoexport += ',';
					}
					
					
					// Payment Code
                    if (obj.statement_invoices[i].payment_code != null && obj.statement_invoices[i].payment_code != '') {
                        if (obj.statement_invoices[i].payment_amount != null && obj.statement_invoices[i].payment_amount != '' && obj.statement_invoices[i].payment_amount != 0) {
                            datatoexport += obj.statement_invoices[i].payment_amount.toFixed(2)+',';
                        } else {
						    datatoexport += ',';
					    }
                    } else {
						datatoexport += ',';
					}

                    // Payment Date
                    if (obj.statement_invoices[i].payment_code != null && obj.statement_invoices[i].payment_code != '') {
                        if (obj.statement_invoices[i].payment_date != null && obj.statement_invoices[i].payment_date != '' && obj.statement_invoices[i].payment_date != '0000-00-00 00:00:00') {
                            var split_new_sent_date = obj.statement_invoices[i].payment_date.split('-');
                            format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                            datatoexport += format_new_sent_date+',';
                        } else {
						    datatoexport += ',';
					    }
                    } else {
                        datatoexport += ',';
                    }

					datatoexport += '\n';
				}

				export_invoice_inquiry_recursion(filter_from,filter_to,hospiceID,pagenum,invoice_status);
			});	
		} else {
			exportAsCSV(datatoexport,filter_from,filter_to);
		}
	}

	$(document).ready(function(){
        $('#delete_invoice_modal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#delete_invoice_modal').modal('hide');
        
        $('body').on('click','.delete_invoice_btn',function(){
            var _this = $(this);
            var invoice_number = _this.attr('data-invoice-number');
            var invoice_id = _this.attr('data-invoice-id');
            var service_date = _this.attr('data-service-date');
            $('#delete_invoice_number').val(invoice_number);
            $('#delete_invoice_id').val(invoice_id);
            $('.delete_service_date_span').html(service_date);
            $('.delete_invoice_number_span1').html(invoice_number.substr(3, 10));
            $('.delete_invoice_number_span2').html(invoice_number.substr(3, 10));
            $('.invoice_number_input').val('');
            $('#delete_invoice_error_message_wrapper').hide();
            $('#delete_invoice_modal').modal('show');
        });
        
        
        $('body').on('click','#popup_delete_invoice',function(){
            var form_data = $('#delete_invoice_form').serialize();

            $('#popup_delete_invoice').hide();
            $('#popup_deleting_invoice').show();
            $('.btn-close-x').hide();
            $('#delete_invoice_error_message_wrapper').hide();

            $.post(base_url + 'billing/delete_collection_invoice/', form_data, function(response){
                var parse_data = JSON.parse(response);
                console.log('parse_data', parse_data);

                if (parse_data.error == 0) {
                    me_message_v2({error:parse_data.error, message: parse_data.message});
                    var invoice_status = $("#invoice_status_selected").val();
                    invoice_list_content(1, invoice_status); 
                } else {
                    $('#popup_delete_invoice').show();
                    $('#popup_deleting_invoice').hide();
                    $('.btn-close-x').show();
                    $('#delete_invoice_error_message').html(parse_data.message);
                    $('#delete_invoice_error_message_wrapper').show();
                }
                   
            });
        });

        $('body').on('keyup','.invoice_number_input',function(){
            var _this = $(this);
            var invoice_number = $('#delete_invoice_number').val().substr(3, 10);
            
            console.log('invoice_number_input', _this.val());
            console.log('invoice_number', invoice_number);
            if (_this.val() === invoice_number) {
                $("body").find('#popup_delete_invoice').removeAttr("disabled");
            } else {
                $("body").find("#popup_delete_invoice").attr('disabled', true);
            }
        });

        $('body').on('click','.export_csv',function(){
			var filter_from = $("body").find(".filter_from").val();
			var filter_to = $("body").find(".filter_to").val();
			var hospiceID = $("body").find(".filter_activity_status_details").val();
            var invoice_status = $("#invoice_status_selected").val();

			datatoexport = 'Status,Sent Date,Account Number,Account,Invoice Number,Service Date,Balance Due,Incoming,Payment Date,\n';
			currenttotal = 0;

			if(filter_from == "")
            {
                filter_from = 0;
            } else {
                var temp_from = filter_from.split(/\s*\-\s*/g);
                filter_from = temp_from[2] + '-' + temp_from[0] + '-' + temp_from[1]
            }

            if(filter_to == "")
            {
                filter_to = 0;
            } else {
                var temp_to = filter_to.split(/\s*\-\s*/g);
                filter_to = temp_to[2] + '-' + temp_to[0] + '-' + temp_to[1]
            }

			if (totalcount > 0) {
				export_invoice_inquiry_recursion(filter_from,filter_to,hospiceID,1,export_global_invoice_status);
			} else {
				//export empty csv
				exportAsCSV(datatoexport,filter_from,filter_to);
			}
			
		});

        $('#reconciliation_yes').modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#reconciliation_yes").modal("hide");

        $('#reconciliation_no').modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#reconciliation_no").modal("hide");

        invoice_list_content(1, 'no_selected');

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
                            });
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
                case 'us_mail': case 'fax':
                    $('#us_mail_invoice_id').val(invoice_id);
                    $('#us_mail_invoice_status').val(_this.val());
                    $('#us_mail_modal').modal('show');
                    break;
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

        $('.pagination-container').on('click','.pager > li',function(){
        if(!$(this).hasClass("disabled")){
            var page = $(this).attr("data-page")*1;
            var invoice_status = $("#invoice_status_selected").val();
            invoice_list_content(page, invoice_status);
        }
    });

    $('.choose_date').datepicker({
        dateFormat: 'mm-dd-yy',
        onClose: function (dateText, inst) {
            var invoice_status = $("#invoice_status_selected").val();
            invoice_list_content(1, invoice_status);
        }
    });
	});

    $('.reconbutton').bind('click',function(){
        $('.reconciliation_receiving').submit();
    });

    $('body').on('click','.cancel_invoice_btn',function(){
        // var draft_id = $(this).attr("data-draft-id");
        var _this = $(this);
        jConfirm("Are you sure you want to return statement activity to draft?","Reminder",function(response){
            if(response)
            {
                me_message_v2({error:2,message:"Returning statement activity to draft. Please wait..."});
                var selected_inv = _this.attr("data-invoice-id");
                console.log('selected_inv_this: ', $(this));
                console.log('selected_inv: ', selected_inv);
                console.log('_this: ', _this.attr("data-hospice-id"));
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

    $('body').on('click','.reconciliation_yes_decline',function(){
        $("#reconciliation_yes").modal("hide");
    });

    $('body').on('click','.reconciliation_yes_accept',function(){
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
        // recon_invoice_date = recon_invoice_date.split(token).join(newToken);

        var temp_recon_invoice_date = recon_invoice_date.split(token);
        recon_invoice_date = temp_recon_invoice_date['2'] + temp_recon_invoice_date['0'] + temp_recon_invoice_date['1'];

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

                $('#reconciliation_popup_modal').modal("hide");
                $("#reconciliation_yes").modal("hide");
            },1);
            
        });

        
    });

    //create_reconciliation
    $('body').on('click','.create_reconciliation',function(){
        var _this = $(this);

        $("#reconciliation_yes").modal("show");
    });

    $('body').on('click','.reconciliation_no_decline',function(){
        $("#reconciliation_no").modal("hide");
    });

    $('body').on('click','.reconciliation_no_accept',function(){
        $('.recon_notes').val("");
        $('.recon_balance_due').val("");
        $('.recon_amount_owe').val("");
        $('.recon_credit').val("");
        $('.recon_invoice_date').val("");
        $('.recon_invoice_number').val("");
        $('.recon_payment_amount').val("");

        setTimeout(function(){
            $('#reconciliation_popup_modal').modal("hide");
            $("#reconciliation_no").modal("hide");
            // $('.add_comments').click();
        },100);
    });

    //close_reconciliation
    $('body').on('click','.close_reconciliation',function(){
        var _this = $(this);

        $("#reconciliation_no").modal("show");
    });

    // Add Comments
    $('body').on('click','.add_comments',function(){
        console.log('Add Comments');
        var invoice_id = $('.reconciliation_invoice_id').val();
        $('.invoice_id_input').val(invoice_id);
        $('#notes_popup_modal').modal("show");
    });

    //send-to-collection-btn
    $('body').on('click','.send-to-collection-btn',function(){
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
        
        jConfirm("Send to collection?","Reminder",function(response){
            if(response)
            {
                $.get(base_url+'billing/send_to_collection/'+selected_inv, function(response){
                    var obj = JSON.parse(response);
                    
                    me_message_v2({error:obj['error'],message:obj['message']});
                    if (obj['error'] == 0) {
                        var invoice_status = $("#invoice_status_selected").val();
                        invoice_list_content(1, invoice_status);

                        $("body").find(".receive-payments-btn").attr('disabled', true);
                        $("body").find(".send-to-collection-btn").attr('disabled', true);
                        $("body").find(".send-to-invoice-btn").attr('disabled', true);
                    }
                });
            }
        });
    });

    //send-to-collection-btn
    $('body').on('click','.send-to-invoice-btn',function(){
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
        
        jConfirm("Send to invoice inquiry?","Reminder",function(response){
            if(response)
            {
                $.get(base_url+'billing/send_to_invoice/'+selected_inv, function(response){
                    var obj = JSON.parse(response);
                    
                    me_message_v2({error:obj['error'],message:obj['message']});
                    if (obj['error'] == 0) {
                        var invoice_status = $("#invoice_status_selected").val();
                        invoice_list_content(1, invoice_status);

                        $("body").find(".receive-payments-btn").attr('disabled', true);
                        $("body").find(".send-to-collection-btn").attr('disabled', true);
                        $("body").find(".send-to-invoice-btn").attr('disabled', true);
                    }
                });
            }
        });
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
    $('body').on('click','.all-inquiry-checkbox',function(){
        var statement_bill = $('.inquiry-checkbox');
        if($(this).is(':checked'))
        {
            statement_bill.each(function(){
                $(this).prop('checked', true);
                $("body").find(".receive-payments-btn").removeAttr('disabled');
                $("body").find(".send-to-collection-btn").removeAttr('disabled');
                $("body").find(".send-to-invoice-btn").removeAttr('disabled');
                $("body").find(".attach-file-btn").removeAttr('disabled');
                $("body").find(".email-btn").removeAttr('disabled');
            });
        }
        else
        {
            statement_bill.each(function(){
                $(this).prop('checked', false);
                $("body").find(".receive-payments-btn").attr('disabled', true);
                $("body").find(".send-to-collection-btn").attr('disabled', true);
                $("body").find(".send-to-invoice-btn").attr('disabled', true);
                $("body").find(".attach-file-btn").attr('disabled', true);
                $("body").find(".email-btn").attr('disabled', true);
            });
        }
    });

    //Check by one
    $('body').on('click','.inquiry-checkbox',function(){
        var this_element = $(this);
        var value = $(this).val();
        // var _this = $('.create-invoice:checked');

        if(this_element.is(':checked'))
        {
            $("body").find(".receive-payments-btn").removeAttr('disabled');
            $("body").find(".send-to-collection-btn").removeAttr('disabled');
            $("body").find(".send-to-invoice-btn").removeAttr('disabled');
            $("body").find(".attach-file-btn").removeAttr('disabled');
            $("body").find(".email-btn").removeAttr('disabled');
        }
        else
        {
            var _this = $('.inquiry-checkbox:checked');
            if (_this.length == 0) {
                $("body").find(".receive-payments-btn").attr('disabled', true);
                $("body").find(".send-to-collection-btn").attr('disabled', true);
                $("body").find(".send-to-invoice-btn").attr('disabled', true);
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
    
    $('select.filter_activity_status_details').on('change', function (e)
    {
        var invoice_status = $("#invoice_status_selected").val();
        filter_activity_status_details_func(invoice_status);
    });

    var filter_activity_status_details_func = function(invoice_status='') {
        export_global_invoice_status = invoice_status;
        console.log('nakasulod');
        var filter_from = $("body").find(".filter_from").val();
        var filter_to = $("body").find(".filter_to").val();
        var hospiceID = $("body").find(".filter_activity_status_details").val();
        var invoice_list_tbody = $("body").find(".invoice_list_tbody");
        var total_invoice_list_queried = $("body").find("#total_invoice_list_queried");
        var viewed_current_date = $("body").find("#viewed_current_date");
        var temp_date_today = $("body").find("#temp_date_today").val();
        var baseurl = $('#get_base_url').val();

        if(filter_from == "")
            {
                filter_from = 0;
            } else {
                var temp_from = filter_from.split(/\s*\-\s*/g);
                filter_from = temp_from[2] + '-' + temp_from[0] + '-' + temp_from[1]
            }

            if(filter_to == "")
            {
                filter_to = 0;
            } else {
                var temp_to = filter_to.split(/\s*\-\s*/g);
                filter_to = temp_to[2] + '-' + temp_to[0] + '-' + temp_to[1]
            }

        // if(filter_from != 0 && filter_to != 0)
        // {
            invoice_list_tbody.html("<tr><td colspan='11' style='text-align:center;'>Retrieving Data... <i class='fa fa-spin fa-spinner'></i></td></tr>");

            var temp_html = "";
            var pagenum = 1;
            if(typeof(page)!="undefined"){
                pagenum = page*1;
            }
            console.log('nasud');
            $.post(base_url+"billing_statement/load_more_invoice_inquiry/" + filter_from +"/"+ filter_to +"/"+ hospiceID +"/"+ pagenum+"/"+invoice_status+"/1","", function(response){
                var obj = $.parseJSON(response);

                totalcount = obj.pagination_details.total_records;
                console.log(obj);
                if(obj.pagination_details.total_pages < 2){
                    $('.pagination-container').addClass("hidden");
                }
                else{
                    $('.pagination-container').removeClass("hidden");
                    var current_page = obj.pagination_details.current_page*1;
                    if(current_page-1==0){
                        $('.pagination-container').find('.previous').addClass("disabled");
                    }
                    else{
                        $('.pagination-container').find('.previous').removeClass("disabled");
                        $('.pagination-container').find('.previous').attr("data-page",(current_page-1));
                    }
                    if(current_page+1>obj.pagination_details.total_pages){
                        $('.pagination-container').find('.next').addClass("disabled");
                    }
                    else{
                        $('.pagination-container').find('.next').removeClass("disabled");
                        $('.pagination-container').find('.next').attr("data-page",(current_page+1));
                    }
                }
                for(var i = 0; i < obj.statement_invoices.length; i++) {
                    var service_date_from = obj.statement_invoices[i].service_date_from.split('-');
                    var service_date_to = obj.statement_invoices[i].service_date_to.split('-');

                    var service_date = service_date_from[1]+"/"+service_date_from[2]+"/"+service_date_from[0]+"-"+service_date_to[1]+"/"+service_date_to[2]+"/"+service_date_to[0];

                    var invoice_status_pending = '';
                    var invoice_status_email = '';
                    var invoice_status_us_mail = '';
                    var invoice_status_fax = '';

                    if(obj.statement_invoices[i].invoice_status == 'pending') {
                        invoice_status_pending = 'selected';
                    }

                    if(obj.statement_invoices[i].invoice_status == 'email') {
                        invoice_status_email = 'selected';
                    }

                    if(obj.statement_invoices[i].invoice_status == 'us_mail') {
                        invoice_status_us_mail = 'selected';
                    }

                    if(obj.statement_invoices[i].invoice_status == 'fax') {
                        invoice_status_fax = 'selected';
                    }


                    temp_html += '<tr>'; // Start of TR

                    // Checkboxes
                    temp_html += '<td class="hidden-print">';
                    temp_html += '<label class="i-checks data_tooltip" ><input type="checkbox" class="inquiry-checkbox" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" name=""/><i></i></label>';
                    temp_html += '</td>';

                    // Status
                    temp_html += '<td>';
                    temp_html += '<div class="">';
                    switch (obj.statement_invoices[i].invoice_status) {
                        case 'pending': temp_html += 'Pending'; break;
                        case 'email': temp_html += 'Email'; break;
                        case 'us_mail': temp_html += 'US Mail'; break;
                        case 'fax': temp_html += 'Fax'; break;
                    }
                    temp_html += '</div>';
                    temp_html += '</td>';

                    // Send Date
                    temp_html += '<td>';
                    var new_sent_date = '';
                    var format_new_sent_date = '';
                    if (obj.statement_invoices[i].invoice_status == 'us_mail' || obj.statement_invoices[i].invoice_status == 'fax') {
                        new_sent_date = temp_date_today;
                        if (obj.statement_invoices[i].email_sent_date != '' && obj.statement_invoices[i].email_sent_date != null && obj.statement_invoices[i].email_sent_date != '0000-00-00') {
                            new_sent_date = obj.statement_invoices[i].email_sent_date;
                        }
                        temp_html += '<a href="javascript:;" id="email_sent_date" data-pk="'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-url="'+baseurl+'billing_statement/update_invoice_sent_date/'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-title="Enter Date" data-value="'+new_sent_date+'" data-type="combodate" data-maxYear="'+new_sent_date.split('-')[0]+'" data-format="YYYY-MM-DD" data-viewformat="MM/DD/YYYY" data-template="MMM / D / YYYY" class="data_tooltip editable editable-click editable-combodate">';
                        var split_new_sent_date = new_sent_date.split('-');
                        format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                        temp_html += format_new_sent_date;
                        temp_html += '</a>';
                    } else {
                        if (obj.statement_invoices[i].email_sent_date != '' && obj.statement_invoices[i].email_sent_date != null && obj.statement_invoices[i].email_sent_date != '0000-00-00') {
                            var split_new_sent_date = obj.statement_invoices[i].email_sent_date.split('-');
                            format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                            temp_html += format_new_sent_date;
                        }
                    }
                    temp_html += '</td>';

                    // Hospice Account Number
                    temp_html += '<td>';
                    temp_html += obj.statement_invoices[i].hospice_account_number;
                    temp_html += '</td>';
                    
                    // Hospice Name
                    temp_html += '<td>';
                    temp_html += obj.statement_invoices[i].hospice_name;
                    temp_html += '</td>';

                    // Invoice Number
                    temp_html += '<td>';
                    temp_html += '<div style="cursor: pointer" class="view_invoice_details" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_invoices[i].hospiceID+'">'+obj.statement_invoices[i].statement_no.substr(3, 10)+'</div>';
                    temp_html += '</td>';

                    // Service Date
                    temp_html += '<td>';
                    temp_html += service_date;
                    temp_html += '</td>';

                    // Total Balance Due
                    temp_html += '<td>';
                    var total_balance_due = 0;
                    total_balance_due += parseFloat(obj.statement_invoices[i].total);
                    total_balance_due += parseFloat(obj.statement_invoices[i].non_cap);
                    total_balance_due += parseFloat(obj.statement_invoices[i].purchase_item);
                    total_balance_due -= parseFloat(obj.invoices_reconciliation[i].credit);
                    total_balance_due += parseFloat(obj.invoices_reconciliation[i].owe);
                    temp_html += total_balance_due.toFixed(2);
                    temp_html += '</td>';

                    // Payment Code
                    temp_html += '<td>';
                    if (obj.statement_invoices[i].payment_code != null && obj.statement_invoices[i].payment_code != '') {
                        if (obj.statement_invoices[i].payment_amount != null && obj.statement_invoices[i].payment_amount != '' && obj.statement_invoices[i].payment_amount != 0) {
                            temp_html += obj.statement_invoices[i].payment_amount.toFixed(2);
                        }
                    }
                    temp_html += '</td>';

                    // Payment Date
                    temp_html += '<td>';
                    if (obj.statement_invoices[i].payment_code != null && obj.statement_invoices[i].payment_code != '') {
                        if (obj.statement_invoices[i].payment_date != null && obj.statement_invoices[i].payment_date != '' && obj.statement_invoices[i].payment_date != '0000-00-00 00:00:00') {
                            var split_new_sent_date = obj.statement_invoices[i].payment_date.split('-');
                            format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                            temp_html += format_new_sent_date;
                        }
                    }
                    temp_html += '</td>';

                    // Actions
                    temp_html += '<td class="hidden-print">';
                    // Delete
                    temp_html += '<button class="delete_invoice_btn delete_collection_btn_'+obj.statement_invoices[i].acct_statement_invoice_id+' btn btn-xs btn-danger" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_invoices[i].hospiceID+'" data-invoice-number="'+obj.statement_invoices[i].statement_no+'" data-service-date="'+service_date+'"><Strong>Delete</Strong></button>';
                    temp_html += '</td>';
                    
                    temp_html += '</tr>'; // End of TR
                }
                if(temp_html == "") {
                    temp_html = '<td colspan="12" style="text-align:center; padding: 10px"> No Invoices. </td>';
                }
                invoice_list_tbody.html(temp_html);
                total_invoice_list_queried.html(obj.pagination_details.total_records);				
            });
            
            get_total_payment_amount();
        // }
    }

    var get_total_payment_amount = function() {
		var filter_from = $("body").find(".filter_from").val();
		var filter_to = $("body").find(".filter_to").val();
        var hospiceID = $("body").find(".filter_activity_status_details").val();
        var invoice_status = $("#invoice_status_selected").val();
        
        if(filter_from == "")
        {
            filter_from = 0;
        } else {
            var temp_from = filter_from.split(/\s*\-\s*/g);
            filter_from = temp_from[2] + '-' + temp_from[0] + '-' + temp_from[1]
        }

        if(filter_to == "")
        {
            filter_to = 0;
        } else {
            var temp_to = filter_to.split(/\s*\-\s*/g);
            filter_to = temp_to[2] + '-' + temp_to[0] + '-' + temp_to[1]
        }

        // Hide Delete Modal
        $('#delete_invoice_modal').modal('hide');
        $('#popup_delete_invoice').show();
        $('#popup_deleting_invoice').hide();
        $('.btn-close-x').show();

		$.post(base_url+"billing_statement/get_total_payment_amount_invoice/" + invoice_status + "/" + filter_from +"/"+ filter_to +"/1/"+ hospiceID,"", function(response){
			var obj = $.parseJSON(response);

			console.log('date_total_payment_amount', obj);
			if (obj.total_payment_amount != null) {
				$('body').find('#total_payment_amount_invoice_list_queried').html(parseFloat(obj.total_payment_amount).toFixed(2));
			} else {
				$('body').find('#total_payment_amount_invoice_list_queried').html('');
            }
            
            // get_status_total_count();
		});
    }
    
    var get_status_total_count = function() {
		var filter_from = $("body").find(".filter_from").val();
		var filter_to = $("body").find(".filter_to").val();
        var hospiceID = $("body").find(".filter_activity_status_details").val();

        if(filter_from == "")
        {
            filter_from = 0;
        } else {
            var temp_from = filter_from.split(/\s*\-\s*/g);
            filter_from = temp_from[2] + '-' + temp_from[0] + '-' + temp_from[1]
        }

        if(filter_to == "")
        {
            filter_to = 0;
        } else {
            var temp_to = filter_to.split(/\s*\-\s*/g);
            filter_to = temp_to[2] + '-' + temp_to[0] + '-' + temp_to[1]
        }
        
		$.post(base_url+"billing_statement/get_status_total_count_invoice/" + filter_from +"/"+ filter_to +"/"+ hospiceID,"", function(response){
			var obj = $.parseJSON(response);

            console.log('get_status_total_count', obj);
            if (obj.pending_count !== null) {
                $('.pending_counter').html(obj.pending_count.total_count);
                $('.pending_counter_bottom').html(obj.pending_count.total_count);
            }
            if (obj.email_count !== null) {
                $('.email_counter').html(obj.email_count.total_count);
                $('.email_counter_bottom').html(obj.email_count.total_count);
            }
            if (obj.us_mail_count !== null) {
                $('.us_mail_counter').html(obj.us_mail_count.total_count);
                $('.us_mail_counter_bottom').html(obj.us_mail_count.total_count);
            }
            if (obj.fax_count !== null) {
                $('.fax_counter').html(obj.fax_count.total_count);
                $('.fax_counter_bottom').html(obj.fax_count.total_count);
            }
			// if (obj.total_payment_amount != null) {
			// 	$('body').find('#total_payment_amount_invoice_list_queried').html(parseFloat(obj.total_payment_amount).toFixed(2));
			// } else {
			// 	$('body').find('#total_payment_amount_invoice_list_queried').html('');
			// }
		});
    }
    
    $('body').on('click','.select_invoice_status_pending',function(){
        var invoice_status = $("#invoice_status_selected").val();
        if (invoice_status != 'pending') {
            invoice_status = 'pending';
            $("#invoice_status_selected").val('pending');
            $(".select_invoice_status_pending").css("text-decoration", "underline");
            $(".select_invoice_status_pending").css("font-weight", "bold");
            $(".select_invoice_status_email").css("text-decoration", "none");
            $(".select_invoice_status_email").css("font-weight", "normal");
            $(".select_invoice_status_us_mail").css("text-decoration", "none");
            $(".select_invoice_status_us_mail").css("font-weight", "normal");
            $(".select_invoice_status_fax").css("text-decoration", "none");
            $(".select_invoice_status_fax").css("font-weight", "normal");
        } else {
            invoice_status = 'no_selected';
            $("#invoice_status_selected").val('no_selected');
            $(".select_invoice_status_pending").css("text-decoration", "none");
            $(".select_invoice_status_pending").css("font-weight", "normal");
        }
        
        invoice_list_content(1, invoice_status);
    });
    $('body').on('click','.select_invoice_status_email',function(){
        var invoice_status = $("#invoice_status_selected").val();
        if (invoice_status != 'email') {
            invoice_status = 'email';
            $("#invoice_status_selected").val('email');
            $(".select_invoice_status_pending").css("text-decoration", "none");
            $(".select_invoice_status_pending").css("font-weight", "normal");
            $(".select_invoice_status_email").css("text-decoration", "underline");
            $(".select_invoice_status_email").css("font-weight", "bold");
            $(".select_invoice_status_us_mail").css("text-decoration", "none");
            $(".select_invoice_status_us_mail").css("font-weight", "normal");
            $(".select_invoice_status_fax").css("text-decoration", "none");
            $(".select_invoice_status_fax").css("font-weight", "normal");
        } else {
            invoice_status = 'no_selected';
            $("#invoice_status_selected").val('no_selected');
            $(".select_invoice_status_email").css("text-decoration", "none");
            $(".select_invoice_status_email").css("font-weight", "normal");
        }

        invoice_list_content(1, invoice_status);
    });
    $('body').on('click','.select_invoice_status_us_mail',function(){
        var invoice_status = $("#invoice_status_selected").val();
        if (invoice_status != 'us_mail') {
            invoice_status = 'us_mail';
            $("#invoice_status_selected").val('us_mail');
            $(".select_invoice_status_pending").css("text-decoration", "none");
            $(".select_invoice_status_pending").css("font-weight", "normal");
            $(".select_invoice_status_email").css("text-decoration", "none");
            $(".select_invoice_status_email").css("font-weight", "normal");
            $(".select_invoice_status_us_mail").css("text-decoration", "underline");
            $(".select_invoice_status_us_mail").css("font-weight", "bold");
            $(".select_invoice_status_fax").css("text-decoration", "none");
            $(".select_invoice_status_fax").css("font-weight", "normal");
        } else {
            invoice_status = 'no_selected';
            $("#invoice_status_selected").val('no_selected');
            $(".select_invoice_status_us_mail").css("text-decoration", "none");
            $(".select_invoice_status_us_mail").css("font-weight", "normal");
        }

        console.log('select_invoice_status_us_mail');
        invoice_list_content(1, invoice_status);
    });
    $('body').on('click','.select_invoice_status_fax',function(){
        var invoice_status = $("#invoice_status_selected").val();
        if (invoice_status != 'fax') {
            invoice_status = 'fax';
            $("#invoice_status_selected").val('fax');
            $(".select_invoice_status_pending").css("text-decoration", "none");
            $(".select_invoice_status_pending").css("font-weight", "normal");
            $(".select_invoice_status_email").css("text-decoration", "none");
            $(".select_invoice_status_email").css("font-weight", "normal");
            $(".select_invoice_status_us_mail").css("text-decoration", "none");
            $(".select_invoice_status_us_mail").css("font-weight", "normal");
            $(".select_invoice_status_fax").css("text-decoration", "underline");
            $(".select_invoice_status_fax").css("font-weight", "bold");
        } else {
            invoice_status = 'no_selected';
            $("#invoice_status_selected").val('no_selected');
            $(".select_invoice_status_fax").css("text-decoration", "none");
            $(".select_invoice_status_fax").css("font-weight", "normal");
        }

        invoice_list_content(1, invoice_status);
    });
</script>