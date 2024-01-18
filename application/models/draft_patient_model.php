<?php

	Class draft_patient_model extends Ci_Model
	{

		public function __construct()
		{
			$this->db->query('SET SQL_BIG_SELECTS=1');
		}

		//patient witout orders
		public function list_of_noorder_v3($account_location)
		{
			$this->db->select('patient.patientID,patient.medical_record_id,patient.ordered_by,patient.p_fname,patient.p_lname');
			$this->db->from('dme_hospice AS hospice');
			$this->db->join('dme_patient AS patient', 'patient.ordered_by = hospice.hospiceID','left');

			if($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor')
			{
				$this->db->where('patient.ordered_by', $this->session->userdata('group_id'));
			}
			
			if ($this->session->userdata('account_type') != 'hospice_user') {
				$this->db->where('patient.ordered_by !=', 13);
			}

			if ($account_location != 0) {
				$this->db->where('hospice.account_location', $account_location);
			} else {
				$this->db->where('hospice.account_location !=', 0);
			}
			
			$this->db->where('patient.patientID NOT IN (SELECT patientID FROM dme_order_status)',NULL,FALSE);

			$this->db->order_by('patient.p_lname', 'asc');
			$this->db->group_by('patient.patientID');
			$query = $this->db->get();

			return $query->result_array();
		}

		//patient witout orders by hospice
		public function list_of_noorder_byhospice_v3($hospiceID,$account_location)
		{
			$this->db->select('patient.patientID,patient.medical_record_id,patient.ordered_by,patient.p_fname,patient.p_lname');
			$this->db->from('dme_hospice AS hospice');
			$this->db->join('dme_patient AS patient', 'patient.ordered_by = hospice.hospiceID','left');

			$this->db->where('patient.ordered_by', $hospiceID);

			if ($account_location != 0) {
				$this->db->where('hospice.account_location', $account_location);
			} else {
				$this->db->where('hospice.account_location !=', 0);
			}
			
			$this->db->where('patient.patientID NOT IN (SELECT patientID FROM dme_order_status)',NULL,FALSE);

			$this->db->order_by('patient.p_lname', 'ASC');
			$this->db->group_by('patient.patientID');
			$query = $this->db->get();

			return $query->result_array();
		}

		public function delete_draft_patient_by_patientID($patientID) {
			$this->db->where('patientID', $patientID);
			$this->db->delete('dme_patient');
		}

		//patient witout orders
		public function list_of_noorder_for_delete_draft_customer($account_location, $hospiceID)
		{
			$this->db->select('patient.patientID,patient.medical_record_id,patient.ordered_by,patient.p_fname,patient.p_lname');
			$this->db->from('dme_hospice AS hospice');
			$this->db->join('dme_patient AS patient', 'patient.ordered_by = hospice.hospiceID','left');

			if($hospiceID != 0)
			{
				$this->db->where('patient.ordered_by', $hospiceID);
			} else {
				$this->db->where('hospice.account_location', $account_location);
			}

			if ($this->session->userdata('account_type') != 'hospice_user' && $hospiceID != 13) {
				$this->db->where('patient.ordered_by !=', 13);
			}
			
			$this->db->where('patient.patientID NOT IN (SELECT patientID FROM dme_order_status)',NULL,FALSE);

			$this->db->order_by('patient.p_lname', 'asc');
			$this->db->group_by('patient.patientID');
			$query = $this->db->get();

			return $query->result_array();
		}
		
		public function get_location_details($account_location) {
			$this->db->select('user_city, user_state');
			$this->db->from('dme_location');
			$this->db->where('location_id', $account_location);

			$query = $this->db->get();

			return $query->first_row('array');
		}
	}

?>
