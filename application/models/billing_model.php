<?php
	Class billing_model extends Ci_Model
	{
		public function search_all_accounts($account_location, $search_value)
        {
            $response = array();
            $this->db->select('hospice.hospiceID, hospice.hospice_name, hospice.hospice_account_number');
            $this->db->from('dme_hospice as hospice');
            $where = "(hospice.hospice_name LIKE '%$search_value%' OR hospice.hospice_account_number LIKE '%$search_value%')";
            $this->db->where($where);

            if ($account_location != 0) { // Added 08/12/2021
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }
            
            $this->db->where("hospice.account_active_sign", 1); // Added 12/01/2020
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

            if ($account_location != 0) { // Added 08/12/2021
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

            if ($account_location != 0) { // Added 08/12/2021
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

        function get_billing_report($date_from, $date_to, $hospiceID, $start, $limit, $account_location) {
            $this->db->start_cache();

            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');

            if ($account_location != 0) {
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }
            

            if ($hospiceID != 0) {
                $this->db->where("statement_bill.hospiceID",$hospiceID);
            }

            $where = "(statement_bill.service_date_from >= '".$date_from."' AND statement_bill.service_date_to <= '".$date_to."')";
            $this->db->where($where);
            
            $this->db->order_by('hospice.hospice_name', 'ASC');

            $response['totalBillingReportCount'] = count($this->db->get()->result_array());
            if($limit!=-1)
            {
                $this->db->limit($limit,$start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['statement_invoices'] = $this->db->get()->result_array();
            $response['totalCount'] = count($response['statement_invoices']);

            $this->db->flush_cache();

            return $response;
        }

        function get_billing_report_total_payment($date_from, $date_to, $hospiceID, $account_location) {
            $this->db->select('statement_bill.total, statement_bill.non_cap, statement_bill.purchase_item, statement_bill.acct_statement_invoice_id, statement_bill.statement_no');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');

            if ($account_location != 0) { // Added 08/17/2021
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }
            

            if ($hospiceID != 0) {
                $this->db->where("statement_bill.hospiceID",$hospiceID);
            }

            $where = "(statement_bill.service_date_from >= '".$date_from."' AND statement_bill.service_date_to <= '".$date_to."')";
            $this->db->where($where);
            $query = $this->db->get();
            $data['total_count'] = $query->num_rows();
            $data['result'] = $query->result_array();
            

            return $data;
        }

        function update_collection_where_in($acct_statement_invoice_ids, $data) {
            $this->db->where_in('acct_statement_invoice_id',$acct_statement_invoice_ids);
            $response = $this->db->update('dme_account_statement_invoice', $data);

            return $response;
        }

        function delete_invoice_order_summary($acct_statement_invoice_id) {
            $response = false;

        	if(!empty($acct_statement_invoice_id))
        	{
        		$this->db->where('acct_statement_invoice_id', $acct_statement_invoice_id);
				$response = $this->db->delete('dme_account_statement_order_summary');
        	}
        	return $response;
        }

        function delete_invoice_details($acct_statement_invoice_id) {
            $response = false;

        	if(!empty($acct_statement_invoice_id))
        	{
        		$this->db->where('acct_statement_invoice_id', $acct_statement_invoice_id);
				$response = $this->db->delete('dme_account_statement_invoice');
        	}
        	return $response;
        }
	}
?>