<?php
	Class billing_reconciliation_model extends Ci_Model
	{
		public function search_all_accounts($account_location, $search_value)
        {
            $response = array();
            $this->db->select('hospice.hospiceID, hospice.hospice_name, hospice.hospice_account_number');
            $this->db->from('dme_hospice as hospice');
            $where = "(hospice.hospice_name LIKE '%$search_value%' OR hospice.hospice_account_number LIKE '%$search_value%')";
            $this->db->where($where);

            if ($account_location != 0) { // Added 08/17/2021
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }
            
            $query = $this->db->get();

            if (empty($search_value)) {
                return $response;
            }

            return $query->result_array();
        }

        function list_for_account_search($account_location,$search_value, $hospice_id)
        {
            $response = array();
            $this->db->select('hospice.*');
            $this->db->from('dme_hospice as hospice');
            $where = "(hospice.hospice_name LIKE '%$search_value%' OR hospice.hospice_account_number LIKE '%$search_value%')";
            $this->db->where($where);

            if ($account_location != 0) { // Added 08/17/2021
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }
            
            if($hospice_id != 0) {
                $this->db->where("hospice.hospiceID",$hospice_id);
            }
            $this->db->order_by('hospice.hospice_name', 'ASC');
            $query = $this->db->get();

            if (empty($search_value)) {
                return $response;
            }

            return $query->result_array();
        }

        function list_for_account($account_location)
        {
            $this->db->select('hospice.*');
            $this->db->from('dme_hospice as hospice');

            if ($account_location != 0) { // Added 08/17/2021
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }
            
            $this->db->order_by('hospice.hospice_name', 'ASC');
            $query = $this->db->get();


            return $query->result_array();
        }

        function get_all_statement_bill_draft ($account_location) {
            // $this->db->select('statement_bill.*');
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_draft as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            // $this->db->where("statement_bill.hospiceID",$hospice_id);
            $this->db->order_by('hospice.hospice_name', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_statement_activity_details($acct_statement_invoice_id) {
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where("statement_bill.acct_statement_invoice_id",$acct_statement_invoice_id);
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_all_statement_letter(){
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_letter as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_all_paid_invoices($year=0){
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            if($year != 0) {
                $from = $year.'-01-01';
                $to = $year.'-12-31';
                $this->db->where("statement_bill.invoice_date >= '{$from}' AND statement_bill.invoice_date <= '{$to}'",null,false);
                // print_me($year);
            }
            $this->db->where("statement_bill.receive_status", 1);
            $query = $this->db->get();

            return $query->result_array();
        }

        // function get_all_paid_invoices_v2($hospiceID){
        //     $this->db->select('statement_bill.*, hospice.*');
        //     $this->db->from('dme_account_statement_invoice as statement_bill');
        //     $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
        //     if($year != 0) {
        //         $from = $year.'-01-01';
        //         $to = $year.'-12-31';
        //         $this->db->where("statement_bill.invoice_date >= '{$from}' AND statement_bill.invoice_date <= '{$to}'",null,false);
        //         // print_me($year);
        //     }
        //     $this->db->where("statement_bill.receive_status", 1);
        //     $query = $this->db->get();

        //     return $query->result_array();
        // }

        function insert_reconciliation($data=array()) {
            $response = false;

            if(!empty($data)) {
                $save_info = $this->db->insert('dme_account_statement_reconciliation', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }
            return $response;
        }

        function update_statement_bill_invoice($invoice_id, $data=array()) {
            $this->db->where('acct_statement_invoice_id', $invoice_id);
            $response = $this->db->update('dme_account_statement_invoice', $data);

            return $response;
        }

        function get_reconcile_details($recon_id){
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where("statement_bill.acct_statement_reconciliation_id", $recon_id);
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_hospice_details ($hospice_id) {
            $this->db->select('hospice.*');
            $this->db->from('dme_hospice as hospice');
            $this->db->where('hospice.hospiceID', $hospice_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_pending_payments_by_hospice ($hospice_id) {
            $this->db->select('SUM(statement_bill.payment_amount) as payment_amount, statement_bill.payment_date');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.payment_code IS NOT NULL');
            $this->db->group_by('statement_bill.payment_code'); 
            $this->db->order_by('statement_bill.payment_date', 'desc');
            $query = $this->db->get();

            return $query->result_array();
        }

        function insert_received_payment($data=array()) {
            $response = false;

            if(!empty($data)) {
                $save_info = $this->db->insert('dme_account_statement_received_payment', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }
            return $response;
        }

        function update_statement_bill_received_payment($received_payment_id, $data=array()) {
            $this->db->where('acct_statement_received_payment_id', $received_payment_id);
            $response = $this->db->update('dme_account_statement_received_payment', $data);

            return $response;
        }

        function get_received_payments() {
            $this->db->select('statement_bill.acct_statement_received_payment_id, statement_bill.receive_date, statement_bill.acct_statement_invoice_id, statement_bill.payment_type, statement_bill.payment_amount, statement_bill.check_number, statement_bill.received_by, statement_bill.is_reverted, statement_bill.reverted_date, statement_bill.reverted_by, statement_invoice.invoice_date, statement_invoice.due_date, statement_invoice.statement_no, statement_invoice.total, statement_invoice.non_cap, statement_invoice.purchase_item, hospice.*');
            $this->db->from('dme_account_statement_received_payment as statement_bill');
            $this->db->join('dme_account_statement_invoice as statement_invoice', 'statement_invoice.acct_statement_invoice_id = statement_bill.acct_statement_invoice_id', 'left');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_invoice.hospiceID', 'left');
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_next_invoice_by_hospice($hospice_id, $acct_statement_invoice_id) {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            $this->db->where('statement_bill.acct_statement_invoice_id >', $acct_statement_invoice_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_oldest_draft_by_hospice($hospice_id) {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_draft as statement_bill');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->order_by('statement_bill.service_date_from', 'ASC');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_draft_reconciliation_balance_and_owe($draft_id) {
            $this->db->select('SUM(statement_bill.credit) as credit, SUM(statement_bill.balance_owe) as owe');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            $this->db->where('statement_bill.draft_reference ', $draft_id);

            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_invoice_reconciliation_balance_and_owe($invoice_id) {
            $this->db->select('SUM(statement_bill.credit) as credit, SUM(statement_bill.balance_owe) as owe');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            $this->db->where('statement_bill.invoice_reference ', $invoice_id);
            
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_invoice_reconciliation_balance_and_owe_v2($invoice_id) {
            $this->db->select('SUM(statement_bill.credit) as credit, SUM(statement_bill.balance_owe) as owe');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            $this->db->where('statement_bill.invoice_reference ', $invoice_id);
            
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_current_reconciliation_balance_and_owe($hospiceID) {
            $this->db->select('SUM(statement_bill.credit) as credit, SUM(statement_bill.balance_owe) as owe');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            $this->db->where('statement_bill.invoice_reference IS NULL');
            $this->db->where('statement_bill.draft_reference IS NULL');
            $this->db->where('statement_bill.hospiceID ', $hospiceID);

            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_current_reconciliation_balance_and_owe_by_reference($statement_no) {
            $this->db->select('SUM(statement_bill.credit) as credit, SUM(statement_bill.balance_owe) as owe');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            // $this->db->where('statement_bill.invoice_reference IS NULL');
            // $this->db->where('statement_bill.draft_reference IS NULL');
            $this->db->where('statement_bill.draft_reference ', $statement_no);

            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_current_reconciliation_notes($hospiceID) {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            $this->db->where('statement_bill.invoice_reference IS NULL');
            $this->db->where('statement_bill.draft_reference IS NULL');
            $this->db->where('statement_bill.hospiceID ', $hospiceID);

            $query = $this->db->get();

            return $query->result_array();
        }

        function get_current_reconciliation_notes_by_reference($statement_no) {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            // $this->db->where('statement_bill.invoice_reference IS NULL');
            // $this->db->where('statement_bill.draft_reference IS NULL');
            $this->db->where('statement_bill.draft_reference ', $statement_no);

            $query = $this->db->get();

            return $query->result_array();
        }

        function get_current_reconciliation_notes_by_invoice_reference($statement_no) {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            // $this->db->where('statement_bill.invoice_reference IS NULL');
            // $this->db->where('statement_bill.draft_reference IS NULL');
            $this->db->where('statement_bill.invoice_reference ', $statement_no);

            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_all_comments($acct_statement_invoice_id)
        {
            $this->db->select('statement_bill.*, invoice.statement_no');
            $this->db->from('dme_account_statement_comments as statement_bill');
            $this->db->join('dme_account_statement_invoice as invoice', 'invoice.acct_statement_invoice_id = statement_bill.acct_statement_invoice_id', 'left');
            $this->db->where('statement_bill.acct_statement_invoice_id', $acct_statement_invoice_id);

            $query = $this->db->get();
            return $query->result_array();
        }

        public function insert_invoice_comments($data)
        {
            $this->db->insert('dme_account_statement_comments', $data);
            return $this->db->insert_id();
        }

        public function count_invoice_comments($acct_statement_invoice_id)
        {
            $this->db->where('acct_statement_invoice_id', $acct_statement_invoice_id);
            $this->db->from('dme_account_statement_comments');

            return $this->db->count_all_results();
        }

        public function get_statement_letter_notes($hospiceID)
        {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_letter_note as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospiceID);

            $query = $this->db->get();
            return $query->result_array();
        }

        public function insert_statement_letter_note($data)
        {
            $this->db->insert('dme_account_statement_letter_note', $data);
            return $this->db->insert_id();
        }

        public function count_statement_letter_notes($hospiceID)
        {
            $this->db->where('hospiceID', $hospiceID);
            $this->db->from('dme_account_statement_letter_note');

            return $this->db->count_all_results();
        }

        public function insert_statement_letter_email($data)
        {
            $this->db->insert('dme_account_statement_letter_email', $data);
            return $this->db->insert_id();
        }

        public function get_statement_letter_email($hospiceID)
        {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_letter_email as statement_bill');
            $this->db->where('statement_bill.hospiceID', $hospiceID);
            $this->db->order_by('statement_bill.acct_statement_letter_id', 'DESC');

            $query = $this->db->get();
            return $query->first_row('array');
        }

        public function delete_reconciliation($reconcile_id) {
            $this->db->where('acct_statement_reconciliation_id', $reconcile_id);
            $response = $this->db->delete('dme_account_statement_reconciliation');

            return $response;
        }
	}
?>