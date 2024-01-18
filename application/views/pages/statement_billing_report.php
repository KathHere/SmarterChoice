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

        .select2 {
            width: 100% !important;
        }

        .select2-selection__arrow {
            display: none !important;
        }

        .panel-default {
            border: 0 !important;
        }

        .select2-selection {
            border: 0 !important;
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
    <div class="bg-light lter b-b wrapper-md hidden-print">
        <h1 class="m-n font-thin h3">
            Service Date report
            <?php if ($this->session->userdata('account_type') == "dme_admin" || $this->session->userdata('account_type') == "dme_user" || $this->session->userdata('account_type') == "biller") { ?>
                <span class="pull-right">
                    <button class="btn btn-info btn-sm service_date_report_export_csv" style="font-size:13px !important;">Export as CSV</button>
                </span>
            <?php } ?>
        </h1>
    </div>

    <div class="wrapper-md">
        <div class="panel panel-default">
            <!-- <div class="panel-heading">
                Reports for Service Date
            </div> -->
            <div class="panel-body" style="padding: 10px;">
                <div class="col-xs-12 col-sm-12 col-md-12 hidden-print" style="margin-top:20px;">
                    <!-- <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6">
                    </div> -->
					<div class="col-xxs-12 col-xs-12 col-sm-12 col-md-12">
                        <div class="pull-right">
							<select id="monthly_filter" class="form-control get_billing_report" data-filter-type="monthly">
                                <option value="select_one" selected>Select Month</option>
								<option value="01" >January</option>
								<option value="02">February</option>
								<option value="03">March</option>
								<option value="04">April</option>
								<option value="05">May</option>
								<option value="06">June</option>
								<option value="07">July</option>
								<option value="08">August</option>
								<option value="09">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
							</select>
							<select id="quarterly_filter" class="form-control get_billing_report" data-filter-type="quarterly" style="display: none">
                                <option value="select_one">Select Quarter</option>
								<option value="first_quarter">First Quarter</option>
								<option value="second_quarter">Second Quarter</option>
								<option value="third_quarter">Third Quarter</option>
								<option value="fourth_quarter">Fourth Quarter</option>
							</select>
							<select id="yearly_filter" class="form-control get_billing_report" data-filter-type="yearly" style="display: none">
                                <option value="select_one">Select Year</option>
                                <?php
                                    $current_year = date('Y');

                                    for($yearly_index = 0; $yearly_index < 3; $yearly_index++) {
                                        if ($current_year == 2019) {
                                            break;
                                        }
                                ?>
                                    <option value="<?php echo $current_year; ?>"><?php echo $current_year; ?></option>
                                <?php
                                     $current_year--;
                                    }
                                ?>
							</select>

                            <div id="custom_filter" class="" data-filter-type="custom" style="display: none">
                                <div class="col-xxs-6 col-xs-6 col-sm-6 col-md-6" style="padding-right: 5px; padding-left: 0px">
                                    <input type="text" class="form-control choose_date filter_from" id="" placeholder="From" style="width: 93px;" >
                                </div>
                                <div class="col-xxs-6 col-xs-6 col-sm-6 col-md-6" style="padding-left: 5px; padding-right: 0px">
                                    <input type="text" class="form-control choose_date filter_to" id="" placeholder="To" style="width: 93px;" >
                                </div>
                            </div>
                        </div>
                        <div class="pull-right" style="margin-right: 10px">
                            <select id="filter_report_by" class="form-control">
                                <option value="Monthly" selected>Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                                <option value="Yearly">Yearly</option>
                                <option value="Custom">Custom</option>
                            </select>
                        </div>
                        <div class="pull-right" style="margin-right: 10px">
                            <span style="line-height: 2.5">Sort: </span>
                        </div>
                    </div>
                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="display: none">
                        <span class="pull-right">
                            From
                            <input type="text" class="form-control choose_date filter_from" id="" placeholder="" style="width: 110px;margin-top: -26px;margin-left: 40px;" >
                            <!-- value="<?php echo date("Y-m-d"); ?>" -->
                        </span>
                    </div>
                    <div class="col-xxs-12 col-xs-6 col-sm-6 col-md-6" style="display: none">
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
                                <select class="form-control pull-center filter_activity_status_details select2-ready hidden-print" name="filter_activity_status_details" style="margin-top: 6px;border: 0px;font-size: 16px;font-weight: 600;text-align-last:center;">
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
                        Service Date
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
                        
                        </span>
                    </div>
                    <div class="col-xxs-12 col-xs-4 col-sm-4 col-md-4" style="padding-left: 0px !important; padding-right: 0px !important; display: none">
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
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:30px; overflow-x: scroll;">
                <input type="hidden" name="activity_status_name" id="activity_status_name" value="<?php echo $activity_status_name_new_v2; ?>">

                <table class="table bg-white b-a col-md-12" id="" style="margin-top:0px;margin-left: 0px; text-align: center;">

                    <thead style="background-color:rgba(97, 101, 115, 0.05);">
                        <tr>
                        <th style="text-align: center; ">Sent Date</th>
                        <th style="text-align: center; ">Account Number</th>
                        <th style="text-align: center; min-width:300px;">Account</th>
                        <th style="text-align: center; ">Invoice Number</th>
                        <th style="text-align: center; ">Service Date</th>
                        <th style="text-align: center; ">Balance Due</th>
                        <th style="text-align: center; ">Incoming</th>
                        <th style="text-align: center; ">Payment Date</th>
                        <th style="text-align: center; ">CUS Days</th>
                        <th style="text-align: center; ">Capped Total</th>
                        <th style="text-align: center; ">Non-Capped Total</th>
                        <th style="text-align: center; ">Sale Item Total</th>
                        <th style="text-align: center; ">Credit</th>
                        <th style="text-align: center; ">Owe</th>
                        <th style="text-align: center; ">Billing ZIP Code</th>
                        <th style="text-align: center; ">Shipping ZIP Code</th>
                        </tr>
                    </thead>
                    <tbody class="invoice_list_tbody">
                        
                    </tbody>
                </table>
                
                </div>
                <div class="pagination-container hidden-print" style="display: none">
                    <nav aria-label="...">
                        <ul class="pager">
                            <li class="previous disabled"><a href="javascript:;"><span aria-hidden="true">&larr;</span> Previous</a></li>
                            <li class="next"><a href="javascript:;">Next <span aria-hidden="true">&rarr;</span></a></li>
                        </ul>
                    </nav>
                </div>

                <div id="billing_report_div" class="col-xs-12 col-sm-12 col-md-12" style="margin-top:10px;margin-bottom:10px;">
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
                    <div class="col-xxs-12 col-xs-4 col-sm-4 col-md-4" style="padding-left: 0px !important; padding-right: 0px !important; display: none">
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
                    </div>
                    
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

<input type="hidden" id="current_count" value="0">
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
    var totalcount = 0;
	var currenttotal = 0;
	var datatoexport = "";

    var get_billing_report_total_payment = function(filter_type, filter_by, hospiceID, current_count, limit, date_from="", date_to="") {
        if (date_from != 0 && date_from != "") {
            var temp_date_from = date_from.split('-');
            date_from = temp_date_from[2] + '-' + temp_date_from[0] + '-' + temp_date_from[1];
        }
        if (date_to != 0 && date_to != "") {
            var temp_date_to = date_to.split('-');
            date_to = temp_date_to[2] + '-' + temp_date_to[0] + '-' + temp_date_to[1];
        }

        totalcount = 0;

        $.post(base_url+"billing/get_billing_report_total_payment/" + filter_type + "/" + filter_by +"/"+ hospiceID +"/"+ date_from +"/"+ date_to,"", function(response){
			var obj = $.parseJSON(response);

            totalcount = obj.total_count;
			if (obj.total_payment_amount != null) {
				$('body').find('#total_payment_amount_invoice_list_queried').html(parseFloat(obj.total_payment_amount).toFixed(2));
			} else {
				$('body').find('#total_payment_amount_invoice_list_queried').html('');
            }
            
            get_billing_report_list(filter_type, filter_by, hospiceID, current_count, limit, date_from, date_to);
		});
    }
    var temp_filter_type = 'monthly';
    var get_billing_report_list = function(filter_type, filter_by, hospiceID, current_count, limit, date_from="", date_to="") {
        if (filter_by != "" || filter_type == "Custom") {
            var invoice_list_tbody = $("body").find(".invoice_list_tbody");
        
            $("body").find("#billing_report_div").append('<div id="loading_more" class="hidden-print" style="text-align:center;font-size:18px;">Retrieving Data... <i class="fa fa-spinner fa-spin fa-2x"></i></div>');
            $.post(base_url+"billing/filter_billing_report/" + filter_type +"/"+ filter_by +"/"+ hospiceID +"/"+ current_count+"/"+limit +"/"+ date_from +"/"+ date_to,"", function(response){
                var obj = $.parseJSON(response);

                $('body').find('#viewed_current_date').html(obj.service_date);
                $("#loading_more").remove();
                var temp_html = "";
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
                    // temp_html += '<td class="hidden-print">';
                    // temp_html += '<label class="i-checks data_tooltip" ><input type="checkbox" class="inquiry-checkbox" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" name=""/><i></i></label>';
                    // temp_html += '</td>';

                    // Status
                    // temp_html += '<td>';
                    // temp_html += '<input type="hidden" class="prev_invoice_status_'+obj.statement_invoices[i].acct_statement_invoice_id+'" value="'+obj.statement_invoices[i].invoice_status+'">';
                    // temp_html += '<select class="hidden-print form-control invoice-status invoice_status_'+obj.statement_invoices[i].acct_statement_invoice_id+'" value="'+obj.statement_invoices[i].invoice_status+'" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'">';
                    // if (obj.statement_invoices[i].invoice_status != 'email') {
                    //     temp_html += '<option value="pending" '+invoice_status_pending+'>Pending</option>';
                    // }
                    // temp_html += '<option value="email" '+invoice_status_email+'>Email</option>';
                    // temp_html += '<option value="email" '+invoice_status_us_mail+'>US Mail</option>';
                    // temp_html += '<option value="email" '+invoice_status_fax+'>Fax</option>';
                    // temp_html += '<div class="inquiry_status">';
                    // switch (obj.statement_invoices[i].invoice_status) {
                    //     case 'pending': temp_html += 'Pending'; break;
                    //     case 'email': temp_html += 'Email'; break;
                    //     case 'us_email': temp_html += 'US Mail'; break;
                    //     case 'fax': temp_html += 'Fax'; break;
                    // }
                    // temp_html += '</div>';
                    // temp_html += '</td>';

                    // Send Date
                    temp_html += '<td>';
                    var new_sent_date = '';
                    var format_new_sent_date = '';
                    if (obj.statement_invoices[i].email_sent_date != '' && obj.statement_invoices[i].email_sent_date != null && obj.statement_invoices[i].email_sent_date != '0000-00-00') {
                        var split_new_sent_date = obj.statement_invoices[i].email_sent_date.split('-');
                        format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                        temp_html += format_new_sent_date;
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

                    // Customer Days
                    temp_html += '<td>';
                    temp_html += obj.statement_invoices[i].customer_days;
                    temp_html += '</td>';

                    // Capped Total
                    temp_html += '<td>';
                    temp_html += parseFloat(obj.statement_invoices[i].total).toFixed(2);
                    temp_html += '</td>';

                    // Non-Capped Total
                    temp_html += '<td>';
                    temp_html += parseFloat(obj.statement_invoices[i].non_cap).toFixed(2);
                    temp_html += '</td>';

                    // Sale Item Total
                    temp_html += '<td>';
                    temp_html += parseFloat(obj.statement_invoices[i].purchase_item).toFixed(2);
                    temp_html += '</td>';

                    // Credit
                    temp_html += '<td>';
                    temp_html += parseFloat(obj.invoices_reconciliation[i].credit).toFixed(2);
                    temp_html += '</td>';

                    // Owe
                    temp_html += '<td>';
                    temp_html += parseFloat(obj.invoices_reconciliation[i].owe).toFixed(2);
                    temp_html += '</td>';

                    // Billing ZIP code
                    temp_html += '<td>';
                    temp_html += obj.statement_invoices[i].b_postalcode;
                    temp_html += '</td>';

                    // Shipping ZIP code
                    temp_html += '<td>';
                    temp_html += obj.statement_invoices[i].s_postalcode;
                    temp_html += '</td>';

                    // Actions
                    // temp_html += '<td class="hidden-print">';
                    // var disabled_button = '';
                    // var disable_style = '';
                    // var button_class_color = 'danger';
                    // var button_class_color_inv = 'info';
                    // if (obj.statement_invoices[i].payment_code == null || obj.statement_invoices[i].payment_code == '') {
                    //     if (obj.is_disabled_invoice_cancel[i] == 0) {
                    //         disabled_button = 'disabled';
                    //         disable_style = 'background-color: #f6f8f8;';
                    //         button_class_color = 'default';
                    //         button_class_color_inv = 'default';
                    //     }
                    //     // Cancel Button
                    //     temp_html += '<button class="cancel_invoice_btn btn btn-xs btn-'+button_class_color+'" style="'+disable_style+'" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_invoices[i].hospiceID+'" '+disabled_button+'><Strong>Cancel</Strong></button>';
                    // }
                    // temp_html += '&nbsp;';
                    // // Email Button
                    // temp_html += '<button class="send_email_btn send_email_btn_'+obj.statement_invoices[i].acct_statement_invoice_id+' btn btn-xs btn-info" data-invoice-id="'+obj.statement_invoices[i].acct_statement_invoice_id+'" data-hospice-id="'+obj.statement_invoices[i].hospiceID+'"><Strong>Email</Strong></button>';
                    // temp_html += '</td>';
                    
                    temp_html += '</tr>'; // End of TR
                }

                if (temp_html != "") {
                    $('.no_invoice_td').remove();
                }
                if(invoice_list_tbody.innerHTML == "") {
                    temp_html = '<td colspan="12" class="no_invoice_td" style="text-align:center; padding: 10px"> No Invoices. </td>';
                }
                invoice_list_tbody.append(temp_html);
                // total_invoice_list_queried.html(obj.pagination_details.total_records);

                current_count += obj.totalCount;
                $("body").find("#total_invoice_list_queried").html(current_count);
                if (current_count < obj.totalBillingReportCount) {
                    get_billing_report_list(filter_type, filter_by, hospiceID, current_count, limit, date_from, date_to);
                } else {
                    $("body").find(".filter_activity_status_details").removeAttr('disabled');
                    $("body").find(".get_billing_report").removeAttr('disabled');
                    $("body").find("#filter_report_by").removeAttr('disabled');
                    $("body").find(".filter_from").removeAttr('disabled');
                    $("body").find(".filter_to").removeAttr('disabled');
                    $("body").find(".service_date_report_export_csv").removeAttr('disabled');
                }
            });
        }
    }

    var exportAsCSV = function(csvData,filter_type,filter_by,filter_type_value,filter_from,filter_to) {
		var blob = new Blob(['\ufeff' + csvData], { type: 'text/csv;charset=utf-8;' });
        var dwldLink = document.createElement('a');
        var url = URL.createObjectURL(blob);
        var isSafariBrowser = navigator.userAgent.indexOf('Safari') !== -1 && navigator.userAgent.indexOf('Chrome') === -1;
        if (isSafariBrowser) {  // if Safari open in new window to save file with random filename.
            dwldLink.setAttribute('target', '_blank');
        }
        var file_name = '';
        if (filter_by == "custom" &&filter_from != 0 && filter_to != 0) {
			var date_from = filter_from.replaceAll('-', '');
			var date_to = filter_to.replaceAll('-', '');
          file_name = 'Service_Date_Report_' + date_from + '-' + date_to + '.csv';
        } else if (filter_by != "custom") {
            filter_type_value = filter_type_value.replaceAll(' ', '');
            file_name = 'Service_Date_Report_'+filter_type+'_'+filter_type_value+'.csv';
        } else {
          file_name = 'Service_Date_Report.csv';
        }
        dwldLink.setAttribute('href', url);
        dwldLink.setAttribute('download', file_name);
        dwldLink.style.visibility = 'hidden';
        document.body.appendChild(dwldLink);
        dwldLink.click();
        document.body.removeChild(dwldLink);
	}

    var export_service_date_report_recursion = function (filter_type, filter_by,hospiceID,current_count,limit,date_from="",date_to="") {
		if (currenttotal < totalcount) {
			$.post(base_url+"billing/filter_billing_report/" + filter_type +"/"+ filter_by +"/"+ hospiceID +"/"+ current_count+"/"+limit +"/"+ date_from +"/"+ date_to,"", function(response){
                var obj = $.parseJSON(response);

                current_count += obj.totalCount;
				currenttotal += obj.totalCount;
				for(var i = 0; i < obj.statement_invoices.length; i++) {
					var service_date_from = obj.statement_invoices[i].service_date_from.split('-');
                    var service_date_to = obj.statement_invoices[i].service_date_to.split('-');

                    var service_date = service_date_from[1]+"/"+service_date_from[2]+"/"+service_date_from[0]+"-"+service_date_to[1]+"/"+service_date_to[2]+"/"+service_date_to[0];

                    var total_balance_due = 0;
                    total_balance_due += parseFloat(obj.statement_invoices[i].total);
                    total_balance_due += parseFloat(obj.statement_invoices[i].non_cap);
                    total_balance_due += parseFloat(obj.statement_invoices[i].purchase_item);
                    total_balance_due -= parseFloat(obj.invoices_reconciliation[i].credit);
                    total_balance_due += parseFloat(obj.invoices_reconciliation[i].owe);

                    var new_sent_date = '';
                    var format_new_sent_date = '';
                    if (obj.statement_invoices[i].email_sent_date != '' && obj.statement_invoices[i].email_sent_date != null && obj.statement_invoices[i].email_sent_date != '0000-00-00') {
                        var split_new_sent_date = obj.statement_invoices[i].email_sent_date.split('-');
                        format_new_sent_date = split_new_sent_date[1] + '/' + split_new_sent_date[2] + '/' + split_new_sent_date[0];
                        datatoexport += format_new_sent_date+','; // Sent Date
                    } else {
						datatoexport += ',';
					}

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

                    if (obj.statement_invoices[i].customer_days != null && obj.statement_invoices[i].customer_days != undefined) {
						datatoexport += obj.statement_invoices[i].customer_days+','; // Customer Days
					} else {
						datatoexport += ',';
					}

                    if (obj.statement_invoices[i].total != null && obj.statement_invoices[i].total != undefined) {
						datatoexport += parseFloat(obj.statement_invoices[i].total).toFixed(2)+','; // Capped Total
					} else {
						datatoexport += ',';
					}

                    if (obj.statement_invoices[i].non_cap != null && obj.statement_invoices[i].non_cap != undefined) {
						datatoexport += parseFloat(obj.statement_invoices[i].non_cap).toFixed(2)+','; // Non-Capped Total
					} else {
						datatoexport += ',';
					}

                    if (obj.statement_invoices[i].purchase_item != null && obj.statement_invoices[i].purchase_item != undefined) {
						datatoexport += parseFloat(obj.statement_invoices[i].purchase_item).toFixed(2)+','; // Sale Item Total
					} else {
						datatoexport += ',';
					}

                    if (obj.invoices_reconciliation[i].credit != null && obj.invoices_reconciliation[i].credit != undefined) {
						datatoexport += parseFloat(obj.invoices_reconciliation[i].credit).toFixed(2)+','; // Credit
					} else {
						datatoexport += ',';
					}

                    if (obj.invoices_reconciliation[i].owe != null && obj.invoices_reconciliation[i].owe != undefined) {
						datatoexport += parseFloat(obj.invoices_reconciliation[i].owe).toFixed(2)+','; // Owe
					} else {
						datatoexport += ',';
					}

                    if (obj.statement_invoices[i].b_postalcode != null && obj.statement_invoices[i].b_postalcode != undefined) {
						datatoexport += obj.statement_invoices[i].b_postalcode+','; // Billing ZIP code
					} else {
						datatoexport += ',';
					}

                    if (obj.statement_invoices[i].s_postalcode != null && obj.statement_invoices[i].s_postalcode != undefined) {
						datatoexport += obj.statement_invoices[i].s_postalcode+','; // Shipping ZIP code
					} else {
						datatoexport += ',';
					}

					datatoexport += '\n';
				}

				export_service_date_report_recursion(filter_type, filter_by, hospiceID, current_count, limit, date_from, date_to);
			});	
		} else {
            var filter_type_value = '';

            switch (filter_type) {
                case 'Monthly': filter_type_value = $("#monthly_filter :selected").text(); break;
                case 'Quarterly': filter_type_value = $("#quarterly_filter :selected").text(); break;
                case 'Yearly': filter_type_value = $("#yearly_filter :selected").text(); break;
            }

            $("body").find(".filter_activity_status_details").removeAttr('disabled');
            $("body").find(".get_billing_report").removeAttr('disabled');
            $("body").find("#filter_report_by").removeAttr('disabled');
            $("body").find(".filter_from").removeAttr('disabled');
            $("body").find(".filter_to").removeAttr('disabled');
            $("body").find(".service_date_report_export_csv").removeAttr('disabled');

			exportAsCSV(datatoexport,filter_type,filter_by,filter_type_value,date_from,date_to);
		}
	}

	$(document).ready(function(){
        // Export as CSV
		$('body').on('click','.service_date_report_export_csv',function(){
			var filter_by = $("body").find("#"+temp_filter_type+"_filter").val();
            var filter_type = $('#filter_report_by').val();
            var hospiceID = $("body").find(".filter_activity_status_details").val();
            
            var current_count = 0;
            var limit = 10;

            var filter_from = $("body").find(".filter_from").val();
            var filter_to = $("body").find(".filter_to").val();

            if (filter_from != 0 && filter_from != "") {
                var temp_date_from = filter_from.split('-');
                filter_from = temp_date_from[2] + '-' + temp_date_from[0] + '-' + temp_date_from[1];
            } else if (filter_from == "") {
                filter_from = 0;
            }

            if (filter_to != 0 && filter_to != "") {
                var temp_date_to = filter_to.split('-');
                filter_to = temp_date_to[2] + '-' + temp_date_to[0] + '-' + temp_date_to[1];
            } else if(filter_to == "") {
                filter_to = 0;
            }

            if(filter_by == "") {
                filter_by = "custom";
            }

            datatoexport = 'Sent Date,Account Number,Acount,Invoice Number,Service Date,Balance Due,Incoming,Payment Date,CUS Days,Capped Total,Non-Capped Total,Sale Item Total,Credit,Owe,Billing ZIP Code,Shipping ZIP Code,\n';
			currenttotal = 0;
			if (totalcount > 0) {
                $("body").find(".filter_activity_status_details").attr('disabled', true);
                $("body").find(".get_billing_report").attr('disabled', true);
                $("body").find("#filter_report_by").attr('disabled', true);
                $("body").find(".filter_from").attr('disabled', true);
                $("body").find(".filter_to").attr('disabled', true);
                $("body").find(".service_date_report_export_csv").attr('disabled', true);

				export_service_date_report_recursion(filter_type, filter_by, hospiceID, current_count, limit, filter_from, filter_to);
			} else {
				//export empty csv
				exportAsCSV(datatoexport,filter_type,filter_by,'',filter_from,filter_to);
			}
			
		});

		$('.choose_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onClose: function (dateText, inst) {
                var filter_by = $("body").find("#"+temp_filter_type+"_filter").val();
                var filter_type = $('#filter_report_by').val();
                var hospiceID = $("body").find(".filter_activity_status_details").val();
                
                var current_count = 0;
                var limit = 10;

                var filter_from = $("body").find(".filter_from").val();
                var filter_to = $("body").find(".filter_to").val();
                if(filter_from == "")
                {
                    filter_from = 0;
                }
                if(filter_to == "")
                {
                    filter_to = 0;
                }
                if(filter_by == "") {
                    filter_by = "custom";
                }

                $("body").find(".filter_activity_status_details").attr('disabled', true);
                $("body").find(".get_billing_report").attr('disabled', true);
                $("body").find("#filter_report_by").attr('disabled', true);
                $("body").find(".filter_from").attr('disabled', true);
                $("body").find(".filter_to").attr('disabled', true);
                $("body").find(".service_date_report_export_csv").attr('disabled', true);
                get_billing_report_total_payment(filter_type, filter_by, hospiceID, current_count, limit, filter_from, filter_to);
            }
        });

		$('body').on('change','#filter_report_by',function(){
			var _this = $(this);

            $('#monthly_filter').val('select_one');
            $('#quarterly_filter').val('select_one');
            $('#yearly_filter').val('select_one');
            $("body").find(".filter_from").val('');
            $("body").find(".filter_to").val('');
            $("body").find(".invoice_list_tbody").html("");
			switch(_this.val()) {
				case 'Monthly':
                    temp_filter_type = 'monthly';
					$('#monthly_filter').show();
					$('#quarterly_filter').hide();
					$('#yearly_filter').hide();
                    $('#custom_filter').hide();
					break;

				case 'Quarterly':
                    temp_filter_type = 'quarterly';
					$('#monthly_filter').hide();
					$('#quarterly_filter').show();
					$('#yearly_filter').hide();
                    $('#custom_filter').hide();
					break;

				case 'Yearly':
                    temp_filter_type = 'yearly';
					$('#monthly_filter').hide();
					$('#quarterly_filter').hide();
					$('#yearly_filter').show();
                    $('#custom_filter').hide();
					break;
                
                case 'Custom':
                    temp_filter_type = 'custom';
                    $('#monthly_filter').hide();
					$('#quarterly_filter').hide();
					$('#yearly_filter').hide();
                    $('#custom_filter').show();
                    break;
			}
		});

        $('body').on('change','.get_billing_report',function(){
			var _this = $(this);
            var filter_type = $('#filter_report_by').val();
            var hospiceID = $("body").find(".filter_activity_status_details").val();

            temp_filter_type = $(this).attr('data-filter-type');
            var invoice_list_tbody = $("body").find(".invoice_list_tbody").html("");
            var current_count = 0;
            var limit = 10;
			
            $("body").find(".filter_activity_status_details").attr('disabled', true);
            $("body").find(".get_billing_report").attr('disabled', true);
            $("body").find("#filter_report_by").attr('disabled', true);
            get_billing_report_total_payment(filter_type, _this.val(), hospiceID, current_count, limit);
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


        $('select.filter_activity_status_details').on('change', function (e){
            // var invoice_status = $("#invoice_status_selected").val();
            // filter_activity_status_details_func(invoice_status);
            var filter_by = $("body").find("#"+temp_filter_type+"_filter").val();
            var filter_type = $('#filter_report_by').val();
            var hospiceID = $("body").find(".filter_activity_status_details").val();
            
            var invoice_list_tbody = $("body").find(".invoice_list_tbody").html("");
            var current_count = 0;
            var limit = 10;

            var filter_from = $("body").find(".filter_from").val();
            var filter_to = $("body").find(".filter_to").val();
            if(filter_from == "")
            {
                filter_from = 0;
            }
            if(filter_to == "")
            {
                filter_to = 0;
            }
            if(filter_by == "") {
                filter_by = "custom";
            }

            $("body").find(".filter_activity_status_details").attr('disabled', true);
            $("body").find(".get_billing_report").attr('disabled', true);
            $("body").find("#filter_report_by").attr('disabled', true);
            get_billing_report_total_payment(filter_type, filter_by, hospiceID, current_count, limit, filter_from, filter_to);
        });
    });

    // Add Comments
    $('body').on('click','.add_comments',function(){
        var invoice_id = $('.reconciliation_invoice_id').val();
        $('.invoice_id_input').val(invoice_id);
        $('#notes_popup_modal').modal("show");
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
</script>