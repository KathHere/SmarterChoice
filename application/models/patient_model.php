<?php

	Class patient_model extends Ci_Model
	{

		public function __construct()
		{
			$this->db->query('SET SQL_BIG_SELECTS=1');
		}

		function get_lastest_customer_move($patient_id)
		{
			$this->db->select('*');
			$this->db->from('dme_sub_ptmove');
			$this->db->where('patientID', $patient_id);
			$this->db->order_by("ptmoveID","DESC");
			$query = $this->db->get();

			return $query->first_row('array');
		}

		public function insert_data_customer_days_length_of_stay($data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$response = $this->db->insert('customer_days_length_of_stay', $data);
				
				return $this->db->insert_id();
			}
		}

		// SELECT * FROM smartfa9_alpha.dme_pickup_tbl 
		// WHERE pickup_sub != "not needed" 
		// AND date_pickedup > '2018-12-31'
		// AND date_pickedup < '2019-02-16';
		function get_pickup_with_params()
		{
			$this->db->select('*');
			$this->db->from('dme_pickup_tbl');
			$this->db->where('pickup_sub !=', 'not needed');
			$this->db->where('date_pickedup >', '2020-12-30');
			$this->db->where('date_pickedup <=', '2020-12-31');

			$data = $this->db->get()->result_array();
            return $data;
		}

		function get_order_status_above_2019($patientID)
		{
			$this->db->select('*');
			$this->db->from('dme_order_status as status');
			$this->db->where('status.patientID', $patientID);
			
			$where = "(status.actual_order_date > '2019-12-31' OR status.date_ordered > '2019-12-31' OR status.pickup_date > '2019-12-31')";
			$this->db->where($where);

			$data = $this->db->get()->result_array();
            return $data;
		}

		function get_order_status_above_2020($patientID)
		{
			$this->db->select('*');
			$this->db->from('dme_order_status as status');
			$this->db->where('status.patientID', $patientID);
			
			$where = "(status.actual_order_date > '2020-12-31' OR status.date_ordered > '2020-12-31' OR status.pickup_date > '2020-12-31')";
			$this->db->where($where);

			$data = $this->db->get()->result_array();
            return $data;
		}

		function check_if_customer_ative($patientID)
		{
			$this->db->select('patient.is_active, patient.ordered_by');
			$this->db->from('dme_patient as patient');
			$this->db->where('patient.patientID', $patientID);

			$query = $this->db->get();
			return $query->first_row('array');
		}
		
		function delete_customer_orders($patientID)
		{
    		$this->db->where('patientID', $patientID);
			$this->db->delete('dme_order'); 
		}

		function delete_customer_notes($patientID)
		{
    		$this->db->where('patientID', $patientID);
			$this->db->delete('dme_patient_notes'); 
		}

		function delete_customer_exchanges($patientID)
		{
    		$this->db->where('patientID', $patientID);
			$this->db->delete('dme_sub_exchange'); 
		}

		function delete_customer_ptmoves($patientID)
		{
    		$this->db->where('patientID', $patientID);
			$this->db->delete('dme_sub_ptmove'); 
		}

		function delete_customer_respites($patientID)
		{
    		$this->db->where('patientID', $patientID);
			$this->db->delete('dme_sub_respite'); 
		}

		function delete_customer_pickups($patientID)
		{
    		$this->db->where('patientID', $patientID);
			$this->db->delete('dme_pickup_tbl'); 
		}

		function delete_customer_addresses($patientID)
		{
    		$this->db->where('patient_id', $patientID);
			$this->db->delete('dme_patient_address'); 
		}

		function delete_customer_order_statuses($patientID)
		{
    		$this->db->where('patientID', $patientID);
			$this->db->delete('dme_order_status'); 
		}

		function delete_customer($patientID)
		{
    		$this->db->where('patientID', $patientID);
			$this->db->delete('dme_patient'); 
		}

	}
