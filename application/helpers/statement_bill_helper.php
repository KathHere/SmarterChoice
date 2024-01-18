<?php

	/* ================== Helper Functions Related to Statement Billing ================== */

	function get_reconciliation_list($from,$to,$hospiceID,$account_location,$pagination_details=array())
    {
        $CI =& get_instance();
        $CI->load->database();

        //using database caching
        $CI->db->start_cache();

        $subquery_where = "";
        if(!empty($from) && !empty($to))
        {
            $temp_date = date('Y-m-d');
            $current_date = date('Y-m-d', strtotime($temp_date.' +1day'));
            $formatted_to = date("Y-m-d",strtotime($to.' +1day'));

            if($from <= $formatted_to)
            {
                $CI->db->where("(statement_bill.date_created >='{$from}' AND statement_bill.date_created <='{$to}')",null,false);
                $CI->db->where("statement_bill.reconcile_status", "1");
                $checkers = 28;
                // if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
                // {
                //     $CI->db->where("statement_bill.reconcile_status", "1");
                // }
                // if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
                // {
                //     $CI->db->where("(statement_bill.date_created >='{$from}' AND statement_bill.date_created <'{$current_date}')",null,false);
                //     $CI->db->where("statement_bill.reconcile_status", "1");
                // }
                // else if($formatted_to < $current_date)
                // {
                //     $CI->db->where("(statement_bill.date_created >='{$from}' AND statement_bill.date_created <='{$to}')",null,false);
                //     $CI->db->where("statement_bill.reconcile_status", "1");
                // }
            }
            else
            {
                $CI->db->where("(statement_bill.date_created >='{$current_date}' AND statement_bill.date_created <='{$current_date}')",null,false);
                $CI->db->where("statement_bill.reconcile_status", "1");
            }
        } 
        $CI->db->select("statement_bill.*,statement_bill.invoice_no as inv_no,hospice.*, statement_invoice.*, statement_bill.balance_owe as b_owe, statement_bill.credit as b_credit, statement_bill.payment_amount as pay_amount",false);

        $CI->db->from("dme_account_statement_reconciliation as statement_bill");
        $CI->db->join("dme_account_statement_invoice as statement_invoice", "statement_invoice.acct_statement_invoice_id = statement_bill.acct_statement_invoice_id", "left");
        $CI->db->join("dme_hospice as hospice","statement_bill.hospiceID=hospice.hospiceID");
    	   
        if(!empty($hospiceID))
        {
            $CI->db->where("statement_bill.hospiceID",$hospiceID);
        }
        else if($CI->session->userdata('account_type')!= "dme_admin" && $CI->session->userdata('account_type')!= "dme_user" && $CI->session->userdata('account_type') != "biller")
        {
            $hospice = $CI->session->userdata('group_id');
            $CI->db->where("statement_bill.hospiceID",$hospice);
        }

        // $CI->db->where("statement_bill.hospiceID != ", 13);

        if ($account_location != 0) { // Added 08/18/2021
            $CI->db->where("hospice.account_location", $account_location);
        } else {
            $CI->db->where("hospice.account_location !=", 0);
        }
        

        $CI->db->stop_cache();

        $num_results = $CI->db->get()->num_rows();
        if(isset($pagination_details['offset']) && isset($pagination_details['limit'])) {
            $CI->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
        $query['data'] = $CI->db->get()->result_array();
        $query['total'] = $num_results;
        $query['total_reconcile'] = count($query['data']);
        $query['test'] = $checkers;
        //flashing cache to avoid getting it in another query
        $CI->db->flush_cache();

        return $query;
    }

    function get_archive_list($from,$to,$hospiceID,$account_location,$pagination_details=array())
    {
        $CI =& get_instance();
        $CI->load->database();

        //using database caching
        $CI->db->start_cache();

        $subquery_where = "";
        if(!empty($from) && !empty($to))
        {
            $temp_date = date('Y-m-d');
            $current_date = date('Y-m-d', strtotime($temp_date.' +1day'));
            $formatted_to = date("Y-m-d",strtotime($to. ' +1day'));

            if($from <= $formatted_to)
            {
                if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
                {
                    $CI->db->where("statement_bill.receive_status", "1");
                }
                if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
                {
                    $CI->db->where("(statement_bill.invoice_date >='{$from}' AND statement_bill.invoice_date <'{$current_date}')",null,false);
                    $CI->db->where("statement_bill.receive_status", "1");
                }
                else if($formatted_to < $current_date)
                {
                    $CI->db->where("(statement_bill.invoice_date >='{$from}' AND statement_bill.invoice_date <='{$to}')",null,false);
                    $CI->db->where("statement_bill.receive_status", "1");
                }
            }
            else
            {
                $CI->db->where("(statement_bill.invoice_date >='{$current_date}' AND statement_bill.invoice_date <='{$current_date}')",null,false);
                $CI->db->where("statement_bill.receive_status", "1");
            }
        } 
        $CI->db->select("statement_bill.*,hospice.*",false);

        $CI->db->from("dme_account_statement_invoice as statement_bill");
        $CI->db->join("dme_hospice as hospice","statement_bill.hospiceID=hospice.hospiceID");
           
        if(!empty($hospiceID))
        {
            $CI->db->where("statement_bill.hospiceID",$hospiceID);
        }
        else if($CI->session->userdata('account_type')!= "dme_admin" && $CI->session->userdata('account_type')!= "dme_user" && $CI->session->userdata('account_type') != "biller")
        {
            $hospice = $CI->session->userdata('group_id');
            $CI->db->where("statement_bill.hospiceID",$hospice);
        }

        $CI->db->where("statement_bill.hospiceID != ", 13);
        
        if ($account_location != 0) { // Added 08/18/2021
            $CI->db->where("hospice.account_location", $account_location);
        } else {
            $CI->db->where("hospice.account_location !=", 0);
        }
        

        $CI->db->stop_cache();

        $num_results = $CI->db->get()->num_rows();
        if(isset($pagination_details['offset']) && isset($pagination_details['limit'])) {
            $CI->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
        $query['data'] = $CI->db->get()->result_array();
        $query['total'] = $num_results;
        $query['total_reconcile'] = count($query['data']);
        //flashing cache to avoid getting it in another query
        $CI->db->flush_cache();

        return $query;

        // $this->db->select('statement_bill.*, hospice.*');
        // $this->db->from('dme_account_statement_invoice as statement_bill');
        // $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
        // if($year != 0) {
        //     $from = $year.'-01-01';
        //     $to = $year.'-12-31';
        //     $this->db->where("statement_bill.invoice_date >= '{$from}' AND statement_bill.invoice_date <= '{$to}'",null,false);
        //     // print_me($year);
        // }
        // $this->db->where("statement_bill.receive_status", 1);
        // $query = $this->db->get();

        // return $query->result_array();
    }

    function get_payment_history_list($from,$to,$hospiceID,$account_location,$pagination_details=array(),$is_hospice=false)
    {
        $CI =& get_instance();
        $CI->load->database();

        //using database caching
        $CI->db->start_cache();

        $subquery_where = "";
        if(!empty($from) && !empty($to))
        {
            $temp_date = date('Y-m-d');
            $current_date = date('Y-m-d', strtotime($temp_date.' +1day'));
            $formatted_to = date("Y-m-d",strtotime($to. ' +1day'));

            if($from <= $formatted_to)
            {
                $CI->db->where("(statement_bill.receive_date >='{$from}' AND statement_bill.receive_date <='{$to}')",null,false);
                if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
                {
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 1;
                }
                if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
                {
                    // $CI->db->where("(statement_bill.receive_date >='{$from}' AND statement_bill.receive_date <'{$current_date}')",null,false);
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 2;
                }
                else if($formatted_to < $current_date)
                {
                    // $CI->db->where("(statement_bill.receive_date >='{$from}' AND statement_bill.receive_date <='{$to}')",null,false);
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 3;
                }
            }
            else
            {
                $CI->db->where("(statement_bill.receive_date >='{$current_date}' AND statement_bill.receive_date <='{$current_date}')",null,false);
                // $CI->db->where("statement_bill.receive_status", "1");
                $checkers = 4;
            }
        } 
        $CI->db->select("statement_bill.acct_statement_received_payment_id, statement_bill.receive_date, statement_bill.acct_statement_invoice_id, statement_bill.payment_type, statement_bill.payment_amount, statement_bill.check_number, statement_bill.received_by, statement_bill.is_reverted, statement_bill.reverted_date, statement_bill.reverted_by, statement_invoice.invoice_date, statement_invoice.due_date, statement_invoice.statement_no, statement_invoice.total, statement_invoice.non_cap, statement_invoice.purchase_item, statement_invoice.service_date_from, statement_invoice.service_date_to, hospice.*",false);

        $CI->db->from("dme_account_statement_received_payment as statement_bill");
        $CI->db->join("dme_account_statement_invoice as statement_invoice", "statement_invoice.acct_statement_invoice_id = statement_bill.acct_statement_invoice_id", "left");
        $CI->db->join("dme_hospice as hospice","statement_invoice.hospiceID=hospice.hospiceID");
           
        if(!empty($hospiceID))
        {
            $CI->db->where("statement_invoice.hospiceID",$hospiceID);
        }
        else if($CI->session->userdata('account_type') != "dme_admin" && $CI->session->userdata('account_type') != "dme_user" && $CI->session->userdata('account_type') != "biller")
        {
            $hospice = $CI->session->userdata('group_id');
            $CI->db->where("statement_invoice.hospiceID",$hospice);
        }

        if ($is_hospice) {
            $CI->db->where("(statement_bill.is_reverted is null OR statement_bill.is_reverted = 0)",null,false);
        }

        // $CI->db->where("statement_invoice.hospiceID != ", 13);
        
        if ($account_location != 0) { // Added 08/12/2021
            $CI->db->where("hospice.account_location", $account_location);
        } else {
            $CI->db->where("hospice.account_location !=", 0);
        }

        $CI->db->stop_cache();

        $num_results = $CI->db->get()->num_rows();
        if(isset($pagination_details['offset']) && isset($pagination_details['limit'])) {
            $CI->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
        $query['data'] = $CI->db->get()->result_array();
        $query['total'] = $num_results;
        $query['total_reconcile'] = count($query['data']);
        $query['test'] = $checkers;
        //flashing cache to avoid getting it in another query
        $CI->db->flush_cache();

        return $query;

        // $this->db->select('statement_bill.acct_statement_received_payment_id, statement_bill.receive_date, statement_bill.acct_statement_invoice_id, statement_bill.payment_type, statement_bill.payment_amount, statement_bill.check_number, statement_bill.received_by, statement_bill.is_reverted, statement_bill.reverted_date, statement_bill.reverted_by, statement_invoice.invoice_date, statement_invoice.due_date, statement_invoice.statement_no, statement_invoice.total, hospice.*');
        // $this->db->from('dme_account_statement_received_payment as statement_bill');
        // $this->db->join('dme_account_statement_invoice as statement_invoice', 'statement_invoice.acct_statement_invoice_id = statement_bill.acct_statement_invoice_id', 'left');
        // $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_invoice.hospiceID', 'left');
        // $query = $this->db->get();

        // return $query->result_array();
    }

    function get_total_payment_amount_history_list($from,$to,$hospiceID,$account_location)
    {
        $CI =& get_instance();
        $CI->load->database();

        //using database caching
        $CI->db->start_cache();

        $subquery_where = "";
        if(!empty($from) && !empty($to))
        {
            $temp_date = date('Y-m-d');
            $current_date = date('Y-m-d', strtotime($temp_date.' +1day'));
            $formatted_to = date("Y-m-d",strtotime($to. ' +1day'));

            if($from <= $formatted_to)
            {
                $CI->db->where("(statement_bill.receive_date >='{$from}' AND statement_bill.receive_date <='{$to}')",null,false);
                if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
                {
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 1;
                }
                if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
                {
                    // $CI->db->where("(statement_bill.receive_date >='{$from}' AND statement_bill.receive_date <'{$current_date}')",null,false);
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 2;
                }
                else if($formatted_to < $current_date)
                {
                    // $CI->db->where("(statement_bill.receive_date >='{$from}' AND statement_bill.receive_date <='{$to}')",null,false);
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 3;
                }
            }
            else
            {
                $CI->db->where("(statement_bill.receive_date >='{$current_date}' AND statement_bill.receive_date <='{$current_date}')",null,false);
                // $CI->db->where("statement_bill.receive_status", "1");
                $checkers = 4;
            }
        } 
        $CI->db->select("SUM(statement_invoice.payment_amount) as total_payment_amount",false);

        $CI->db->from("dme_account_statement_received_payment as statement_bill");
        $CI->db->join("dme_account_statement_invoice as statement_invoice", "statement_invoice.acct_statement_invoice_id = statement_bill.acct_statement_invoice_id", "left");
        $CI->db->join("dme_hospice as hospice","statement_invoice.hospiceID=hospice.hospiceID");
           
        if(!empty($hospiceID))
        {
            $CI->db->where("statement_invoice.hospiceID",$hospiceID);
        }
        else if($CI->session->userdata('account_type') != "dme_admin" && $CI->session->userdata('account_type') != "dme_user" && $CI->session->userdata('account_type') != "biller")
        {
            $hospice = $CI->session->userdata('group_id');
            $CI->db->where("statement_invoice.hospiceID",$hospice);
        }

        // $CI->db->where("statement_invoice.hospiceID != ", 13);
        
        if ($account_location != 0) { // Added 08/12/2021
            $CI->db->where("hospice.account_location", $account_location);
        } else {
            $CI->db->where("hospice.account_location !=", 0);
        }

        $CI->db->stop_cache();

        //flashing cache to avoid getting it in another query
        $CI->db->flush_cache();

        return $CI->db->get()->first_row('array');
    }


    function get_invoice_inquiry_list($from,$to,$hospiceID,$account_location,$pagination_details=array(), $invoice_status, $is_collection)
    {
        $CI =& get_instance();
        $CI->load->database();

        //using database caching
        $CI->db->start_cache();

        $subquery_where = "";
        if(!empty($from) && !empty($to))
        {
            $temp_date = date('Y-m-d');
            $current_date = date('Y-m-d', strtotime($temp_date.' +1day'));
            $formatted_to = date("Y-m-d",strtotime($to. ' +1day'));

            if($from <= $formatted_to)
            {
                $CI->db->where("(statement_bill.email_sent_date >='{$from}' AND statement_bill.email_sent_date <='{$to}')",null,false);
                if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
                {
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 1;
                }
                if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
                {
                    // $CI->db->where("(statement_bill.invoice_date >='{$from}' AND statement_bill.invoice_date <'{$current_date}')",null,false);
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 2;
                }
                else if($formatted_to < $current_date)
                {
                    // $CI->db->where("(statement_bill.invoice_date >='{$from}' AND statement_bill.invoice_date <='{$to}')",null,false);
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 3;
                }
            }
            else
            {
                $CI->db->where("(statement_bill.email_sent_date >='{$current_date}' AND statement_bill.email_sent_date <='{$current_date}')",null,false);
                // $CI->db->where("statement_bill.receive_status", "1");
                $checkers = 4;
            }
        } 
        $CI->db->select("statement_bill.*, hospice.*",false);

        $CI->db->from("dme_account_statement_invoice as statement_bill");
        $CI->db->join("dme_hospice as hospice","statement_bill.hospiceID=hospice.hospiceID");
           
        if(!empty($hospiceID))
        {
            $CI->db->where("statement_bill.hospiceID",$hospiceID);
        }
        else if($CI->session->userdata('account_type') != "dme_admin" && $CI->session->userdata('account_type') != "dme_user" && $CI->session->userdata('account_type') != "biller")
        {
            $hospice = $CI->session->userdata('group_id');
            $CI->db->where("statement_bill.hospiceID",$hospice);
        }

        if ($invoice_status != '' && $invoice_status != 'no_selected') {
            $CI->db->where("statement_bill.invoice_status", $invoice_status);
        }
        // $CI->db->where("statement_invoice.hospiceID != ", 13);

        if ($account_location != 0) { // Added 08/12/2021
            $CI->db->where("hospice.account_location", $account_location);
        } else {
            $CI->db->where("hospice.account_location !=", 0);
        }
        
        $CI->db->where("statement_bill.receive_status", 0);
        $CI->db->where("statement_bill.is_collection", $is_collection);
        $CI->db->order_by('hospice.hospice_name', 'ASC');
        
        $CI->db->stop_cache();

        $num_results = $CI->db->get()->num_rows();
        if(isset($pagination_details['offset']) && isset($pagination_details['limit'])) {
            $CI->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
        $query['data'] = $CI->db->get()->result_array();
        $query['total'] = $num_results;
        $query['total_invoice'] = count($query['data']);
        $query['test'] = $checkers;
        //flashing cache to avoid getting it in another query
        $CI->db->flush_cache();

        return $query;
    }

    function get_total_payment_amount_invoice_list($from,$to,$hospiceID,$account_location,$invoice_status,$is_collection)
    {
        $CI =& get_instance();
        $CI->load->database();

        //using database caching
        $CI->db->start_cache();

        $subquery_where = "";
        if(!empty($from) && !empty($to))
        {
            $temp_date = date('Y-m-d');
            $current_date = date('Y-m-d', strtotime($temp_date.' +1day'));
            $formatted_to = date("Y-m-d",strtotime($to. ' +1day'));

            if($from <= $formatted_to)
            {
                $CI->db->where("(statement_bill.email_sent_date >='{$from}' AND statement_bill.email_sent_date <='{$to}')",null,false);
                if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
                {
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 1;
                }
                if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
                {
                    // $CI->db->where("(statement_bill.receive_date >='{$from}' AND statement_bill.receive_date <'{$current_date}')",null,false);
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 2;
                }
                else if($formatted_to < $current_date)
                {
                    // $CI->db->where("(statement_bill.receive_date >='{$from}' AND statement_bill.receive_date <='{$to}')",null,false);
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 3;
                }
            }
            else
            {
                $CI->db->where("(statement_bill.email_sent_date >='{$current_date}' AND statement_bill.email_sent_date <='{$current_date}')",null,false);
                // $CI->db->where("statement_bill.receive_status", "1");
                $checkers = 4;
            }
        } 
        $CI->db->select("statement_bill.total, statement_bill.non_cap, statement_bill.purchase_item, statement_bill.acct_statement_invoice_id, statement_bill.statement_no",false);

        $CI->db->from("dme_account_statement_invoice as statement_bill");
        $CI->db->join("dme_hospice as hospice","statement_bill.hospiceID=hospice.hospiceID");
           
        if(!empty($hospiceID))
        {
            $CI->db->where("statement_bill.hospiceID",$hospiceID);
        }
        else if($CI->session->userdata('account_type') != "dme_admin" && $CI->session->userdata('account_type') != "dme_user" && $CI->session->userdata('account_type') != "biller")
        {
            $hospice = $CI->session->userdata('group_id');
            $CI->db->where("statement_bill.hospiceID",$hospice);
        }

        if ($invoice_status != '' && $invoice_status != 'no_selected') {
            $CI->db->where("statement_bill.invoice_status", $invoice_status);
        }
        // $CI->db->where("statement_invoice.hospiceID != ", 13);

        if ($account_location != 0) { // Added 08/12/2021
            $CI->db->where("hospice.account_location", $account_location);
        } else {
            $CI->db->where("hospice.account_location !=", 0);
        }

        $CI->db->where("statement_bill.receive_status", 0);
        $CI->db->where("statement_bill.is_collection", $is_collection);
        
        $CI->db->stop_cache();

        //flashing cache to avoid getting it in another query
        $CI->db->flush_cache();

        return $CI->db->get()->result_array();
    }

    function get_status_count_amount_invoice_list($from,$to,$hospiceID,$account_location, $status)
    {
        $CI =& get_instance();
        $CI->load->database();

        //using database caching
        $CI->db->start_cache();

        $subquery_where = "";
        if(!empty($from) && !empty($to))
        {
            $temp_date = date('Y-m-d');
            $current_date = date('Y-m-d', strtotime($temp_date.' +1day'));
            $formatted_to = date("Y-m-d",strtotime($to. ' +1day'));

            if($from <= $formatted_to)
            {
                $CI->db->where("(statement_bill.email_sent_date >='{$from}' AND statement_bill.email_sent_date <='{$to}')",null,false);
                if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
                {
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 1;
                }
                if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
                {
                    // $CI->db->where("(statement_bill.receive_date >='{$from}' AND statement_bill.receive_date <'{$current_date}')",null,false);
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 2;
                }
                else if($formatted_to < $current_date)
                {
                    // $CI->db->where("(statement_bill.receive_date >='{$from}' AND statement_bill.receive_date <='{$to}')",null,false);
                    // $CI->db->where("statement_bill.receive_status", "1");
                    $checkers = 3;
                }
            }
            else
            {
                $CI->db->where("(statement_bill.email_sent_date >='{$current_date}' AND statement_bill.email_sent_date <='{$current_date}')",null,false);
                // $CI->db->where("statement_bill.receive_status", "1");
                $checkers = 4;
            }
        } 
        $CI->db->select("COUNT(statement_bill.acct_statement_invoice_id) as total_count",false);

        $CI->db->from("dme_account_statement_invoice as statement_bill");
        $CI->db->join("dme_hospice as hospice","statement_bill.hospiceID=hospice.hospiceID");
           
        if(!empty($hospiceID))
        {
            $CI->db->where("statement_bill.hospiceID",$hospiceID);
        }
        else if($CI->session->userdata('account_type') != "dme_admin" && $CI->session->userdata('account_type') != "dme_user" && $CI->session->userdata('account_type') != "biller")
        {
            $hospice = $CI->session->userdata('group_id');
            $CI->db->where("statement_bill.hospiceID",$hospice);
        }

        // $CI->db->where("statement_invoice.hospiceID != ", 13);

        if ($account_location != 0) { // Added 08/12/2021
            $CI->db->where("hospice.account_location", $account_location);
        } else {
            $CI->db->where("hospice.account_location !=", 0);
        }

        $CI->db->where("statement_bill.receive_status", 0);
        $CI->db->where("statement_bill.is_collection", 0);
        $CI->db->where("statement_bill.invoice_status", $status);
        
        
        $CI->db->stop_cache();

        //flashing cache to avoid getting it in another query
        $CI->db->flush_cache();

        return $CI->db->get()->first_row();
    }

    function get_statement_letter_hospices($account_location, $hospiceID = null) {
        $CI =& get_instance();
        $CI->load->database();
        $date_today = date('Y-m-d');

        $CI->db->select("statement_bill.hospiceID, statement_bill.due_date",false);
        $CI->db->from("dme_account_statement_invoice as statement_bill");
        $CI->db->join("dme_hospice as hospice","statement_bill.hospiceID=hospice.hospiceID");
        $CI->db->where("statement_bill.receive_status",0);

        if ($account_location != 0) { // Added 08/12/2021
            $CI->db->where("hospice.account_location", $account_location);
        } else {
            $CI->db->where("hospice.account_location !=", 0);
        }

        $CI->db->where('statement_bill.due_date <=', $date_today);

        if ($hospiceID == null) {
            $CI->db->group_by("hospice.hospiceID");
            $CI->db->order_by("hospice.hospice_name", 'ASC');
            return $CI->db->get()->result_array();
        } else {
            $CI->db->where("statement_bill.hospiceID", $hospiceID);
            $CI->db->order_by("statement_bill.due_date", 'desc');
            return $CI->db->get()->first_row('array');
        }
    }

    function get_statement_letter_email($hospiceID) {
        $CI =& get_instance();
        $CI->load->database();

        $CI->db->select("statement_bill.date_added",false);
        $CI->db->from("dme_account_statement_letter_email as statement_bill");
        $CI->db->where("statement_bill.hospiceID", $hospiceID);
        $CI->db->order_by("statement_bill.date_added", 'DESC');
        
        return $CI->db->get()->first_row('array');
    }

    function get_statement_letter_counter($account_location) {
        $hospices = get_statement_letter_hospices($account_location, null);
        $counter = 0;

        foreach($hospices as $hospice) {
            $email = get_statement_letter_email($hospice['hospiceID']);
            $date_today_m = date("m");
            $date_today = date("Y-m-d");

            if (!empty($email)) {
                if (date("m", strtotime($email['date_added'])) < $date_today_m) {
                    $counter++;
                } else {
                    $latest_due_invoice = get_statement_letter_hospices($account_location, $hospice['hospiceID']);

                    if ($latest_due_invoice['due_date'] <= $date_today) {
                        if (date("m", strtotime($email['date_added'])) < date("m", strtotime($latest_due_invoice['due_date']))) {
                            $counter++;
                        }
                    }
                    
                }
            } else {
                $counter++;
            }
        }

        return $counter;
    }

    function hasAcccessHospiceBilling($hospiceID) {
        $CI =& get_instance();
        $CI->load->database();
        if ($CI->session->userdata('account_type') == 'hospice_admin') {
            return true; // 06/30/2021 so new hospices can be able to access billing immediately

            // Las Vegas Hospices
            // if ($hospiceID == 13 || // Demo Hospice
            //     $hospiceID == 21 || // Omni Hospice
            //     $hospiceID == 124 || // Comfort Life Hospice
            //     $hospiceID == 15 || // Harmony Hospice
            //     $hospiceID == 2 || // Compassioncare Hospice
            //     $hospiceID == 145 || // Pebble Creek Hospice
            //     $hospiceID == 208 || // Sunset Hospice
            //     $hospiceID == 34 || // Centennial Hospice
            //     $hospiceID == 190 || // Comfort Hospice Care
            //     $hospiceID == 48 || // Amber Care Hospice, Inc
            //     $hospiceID == 214 || // Perpetual Help Hospice of Nevada, LLC
            //     $hospiceID == 157 || // Advanced Healthcare Home Health & Hospice of Las Vegas
            //     $hospiceID == 55 || // Red Rock Hospice
            //     $hospiceID == 367 || // First Choice Hospice Care
            //     $hospiceID == 341 || // Care Pro Hospice
            //     $hospiceID == 319 || // One Care Hospice
            //     $hospiceID == 27 || // Covenant Hospice Care
            //     $hospiceID == 244 || // River Valley Home Health and Hospice
            //     $hospiceID == 24 || // Hospice Services of Nevada
            //     $hospiceID == 53 || // Christian Hospice
            //     $hospiceID == 52 || // Alta Care Hospice & Palliative Care
            //     $hospiceID == 36 || // Liberty Creek Hospice
            //     $hospiceID == 20 || // Renaissance Hospice
            //     $hospiceID == 35 || // Affectionate Hospice Care
            //     $hospiceID == 297 || // Absolute Palliative Care
            //     $hospiceID == 23 || // Divine Mercy Hospice
            //     $hospiceID == 19 || // Nevada Hospice Care
            //     $hospiceID == 18 || // Hospice Del Sol
            //     $hospiceID == 175 || // Dedicated Hospice Care
            //     $hospiceID == 17 || // Galaxy Hospice
            //     $hospiceID == 14 || // Rhythm Of Life Hospice
            //     $hospiceID == 109 || // Caring Professionals Hospice
            //     $hospiceID == 16 || // Jireh Healthcare Services, LLC
            //     $hospiceID == 39 || // Patient Care Hospice
            //     $hospiceID == 365 || // At Home Care Hospice
            //     $hospiceID == 37 || // Center For Dignified Care
            //     $hospiceID == 361 || // A Caring Heart Healthcare Services
            //     $hospiceID == 359 || // Guardian Hospice, LLC dba: Arizona Life Hospice Services
            //     $hospiceID == 355 || // Critical Care Hospice
            //     $hospiceID == 353 || // Family First Hospice Care
            //     $hospiceID == 351 || // Precious Hearts Healthcare Providers, LLC
            //     $hospiceID == 343 || // American Hospice Corporation
            //     $hospiceID == 57 || // Harbor Hospice of Las Vegas
            //     $hospiceID == 339 || // Eldercare Hospice
            //     $hospiceID == 337 || // Valley Hospice of Nevada
            //     $hospiceID == 335 || // Harmony Care Hospice, Inc.
            //     $hospiceID == 333 || // Atrium Hospice of Nevada
            //     $hospiceID == 326 || // John Paul David Hospice Care
            //     $hospiceID == 323 || // VNN Hospice Services, Inc.
            //     $hospiceID == 305 || // Physicians Choice Hospice
            //     $hospiceID == 30 || // Canyon Vista Post Acute
            //     $hospiceID == 238 || // Thema Hospice Inc
            //     $hospiceID == 295 || // Concierge Hospice Care
            //     $hospiceID == 293 || // Emerald Hospice and Life
            //     $hospiceID == 285 || // Blue Heights Hospice
            //     $hospiceID == 281 || // Compassionate Hospice of Las Vegas
            //     $hospiceID == 253 || // More Than Family Care, LLC
            //     $hospiceID == 226 || // Good Shepard Hospice Care
            //     $hospiceID == 217 || // Grace Hospice
            //     $hospiceID == 184 || // Genesis Hospice
            //     $hospiceID == 181 || // Comprehensive Hospice Care, LLC
            //     $hospiceID == 118 || // 24/7 Apollo Healthcare Inc
            //     $hospiceID == 311 || // Silver State Hospice, Inc
            //     $hospiceID == 273 || // Serenity Hospice
            //     $hospiceID == 265 // Silver State Hospice Care, LLC
            //     ) {
            //     return true;
            // }

            // California Hospices
            // if ($hospiceID == 54 || // Global Home Hospice Services
            //     $hospiceID == 97 || // Compassionate Care Hospice
            //     $hospiceID == 375 || // Vida Hospice Services, Inc.
            //     $hospiceID == 73 || // Steward Hospice Care Inc
            //     $hospiceID == 89 || // Medplus Hospice Care
            //     $hospiceID == 65 || // Golden Angel Hospice
            //     $hospiceID == 51 || // Compassionate Hearts Hospice
            //     $hospiceID == 148 || // Starline Hospice
            //     $hospiceID == 223 || // Green Valley Palliative & Hospice Care
            //     $hospiceID == 44 || // Ivy Hospice Care Inc.
            //     $hospiceID == 130 || // Lifelink Hospice
            //     $hospiceID == 133 || // Salute Hospice
            //     $hospiceID == 139 || // Agape Hospice & Palliative Care
            //     $hospiceID == 277 || // Eminent Hospice
            //     $hospiceID == 47 || // Victoria One Hospice, Inc
            //     $hospiceID == 46 || // Care Unlimited Hospice Services, Inc.
            //     $hospiceID == 385 || // Paloma Hospice
            //     $hospiceID == 383 || // Beacon Hospice Care
            //     $hospiceID == 379 || // United Care Hospice LLC
            //     $hospiceID == 377 || // Hearten Hospice Care, Inc.
            //     $hospiceID == 373 || // H&A Hospice, Inc.
            //     $hospiceID == 371 || // Tender Touch Hospice Care
            //     $hospiceID == 369 || // Affirm Hospice Care, Inc.
            //     $hospiceID == 357 || // Love and Faith Hospice Services, Inc
            //     $hospiceID == 347 || // St Martin High Desert
            //     $hospiceID == 315 || // Arbor Hospice
            //     $hospiceID == 299 || // JCH Medcare Services
            //     $hospiceID == 232 || // Transitions Hospice Inc
            //     $hospiceID == 163 || // Allied Care Hospice
            //     $hospiceID == 196 || // Elite Hospice Care
            //     $hospiceID == 395 // Revive Hospice Care, LLC dba Cambridge Hospice
            //     ) {
            //     return true;
            // }
        }
        return false;
    }
?>
