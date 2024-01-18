<?php
	Class user_model extends Ci_Model
	{
		var $user_table = 'dme_user';

		function get($columns='*', $where_col='' , $where_val='')
		{
			$this->db->select($columns);
			$this->db->from('dme_user');

			$this->db->where($where_col , $where_val);
			$query = $this->db->get();

			return ($query->result()) ? $query->result() : FALSE;
			// return $this->db->last_query();
		}

		function get_users()
		{
			$this->db->select('*');
			$this->db->from('dme_user');

			$query = $this->db->get();
			return ($query->result()) ? $query->result() : FALSE;
		}

		function get_users_v2($user_location)
		{
			$this->db->select('*');
			$this->db->from('dme_user');

			if ($user_location != 0) {
				$this->db->where('user_location', $user_location);
			} else {
				$this->db->where('user_location !=', 0);
			}
			

			$query = $this->db->get();
			return ($query->result()) ? $query->result() : FALSE;
		}

		function get_hosp_admin_users($hospiceID='')
		{
			$this->db->select('*');
			$this->db->from('dme_user');
			$this->db->where('group_id', $hospiceID);

			$query = $this->db->get();
			return $query->result();
		}

		function user_add($data)
		{
			$response = false;
			if(!empty($data))
			{
				$response = $this->db->insert($this->user_table, $data);
			}
			return $response;
		}

		function update_user($userID, $data)
		{
			$this->db->where('userID', $userID);
		    $this->db->update('dme_user', $data);
		}

		function delete_user($userID)
		{
			$this->db->where('userID', $userID);
			$this->db->delete('dme_user');
		}

		function add_admin($common_data)
		{
			$query = $this->user_add($common_data);
			return $query;
		}

		function login($username, $password, $account_type)
		{
			$this->db->select('*');
	        $this->db->from('dme_user');
	        $this->db->where('username', $username);
	        $this->db->where('password', MD5($password));
	        $this->db->limit(1);

	        $query = $this->db->get();
	        if ($query->num_rows() == 1) {
	            return $query->result();
	        }else{
	            return false;
	        }
		}

		function get_user_location_info($location_id)
		{
			$this->db->select('*');
	        $this->db->from('dme_location');
	        $this->db->where('location_id', $location_id);

	        $data = $this->db->get()->first_row('array');

	        return $data;
		}

		function login_v2($username, $password, $account_type)
		{
			$this->db->select('*');
	        $this->db->from('dme_user AS user');
	        $this->db->join('dme_hospice AS hospice','hospice.hospiceID = user.group_id','left');
	        $this->db->where('user.username', $username);
	        $this->db->where('user.password', MD5($password));
	        $this->db->limit(1);

	        $query = $this->db->get();
	        if ($query->num_rows() == 1) {
	            return $query->result();
	        }else{
	            return false;
	        }
		}

		function list_patients($patient_ids=array())
		{
			$this->db->select('*');
	        $this->db->from('dme_patient');
	        if(!empty($patient_ids))
	        {
	        	$this->db->where_in("patientID",$patient_ids);
	        }
	        $query = $this->db->get();

	        return $query->result_array();

		}

		function list_patients_v2($patient_ids=array(),$account_location)
		{
			$this->db->select('patients.*');
	        $this->db->from('dme_patient as patients');
	        $this->db->join('dme_hospice AS hospice','patients.ordered_by=hospice.hospiceID','left');

	        $this->db->where('hospice.account_location', $account_location);
	        if(!empty($patient_ids))
	        {
	        	$this->db->where_in("patients.patientID",$patient_ids);
	        }
	        $query = $this->db->get();

	        return $query->result_array();

		}

		public function list_patients_v3($filters=false,$account_location,$start=0,$limit=0)
    	{
			$this->db->start_cache();
			if($filters!=false)
			{
				$this->load->library('orm/filters');
				$this->filters->detect('patient',$filters);
			}

			$this->db->select('patients.*');
	        $this->db->from('dme_patient as patients');
	        $this->db->join('dme_hospice AS hospice','patients.ordered_by=hospice.hospiceID','left');

			if ($account_location != 0) {
				$this->db->where('hospice.account_location', $account_location);
			} else {
				$this->db->where('hospice.account_location !=', 0);
			}
	        
	        if(!empty($patient_ids))
	        {
	        	$this->db->where_in("patients.patientID",$patient_ids);
	        }

			$response['totalCount'] = $this->db->count_all_results() ;
			if($limit!=-1)
			{
				$this->db->limit($limit,$start);
			}
			$response['limit'] = $limit;
			$response['start'] = $start;
			$response['result'] = $this->db->get()->result_array();
			$this->db->flush_cache();

			return $response;
    	}

		function search_existing_patient($searched_string, $hospice_id)
		{
			$this->db->select('*');
			$this->db->from('dme_patient AS pat');
			$this->db->join('dme_order AS orders','pat.medical_record_id=orders.medical_record_id','left');
			$this->db->where('pat.medical_record_id', $searched_string);

			$this->db->where('pat.ordered_by', $hospice_id);
			//$this->db->where('orders.organization_id', $hospice_id);

			$this->db->group_by('pat.medical_record_id');

			$query = $this->db->get();

			return $query->result_array();
		}

		function search_existing_patient_new($searched_string, $hospice_id, $current_mr_no)
		{
			$this->db->select('*');
			$this->db->from('dme_patient AS pat');
			$this->db->join('dme_order AS orders','pat.medical_record_id=orders.medical_record_id','left');
			$this->db->where('pat.medical_record_id', $searched_string);

			$this->db->where('pat.ordered_by', $hospice_id);
			$this->db->where('pat.medical_record_id != ', $current_mr_no);
			//$this->db->where('orders.organization_id', $hospice_id);

			$this->db->group_by('pat.medical_record_id');

			$query = $this->db->get();

			return $query->result_array();
		}

		function update_first_loggedin($user_id, $data)
		{
			$response = false;

			if(!empty($data))
			{
				$this->db->where('userID', $user_id);
		    	$response = $this->db->update('dme_user', $data);
			}

			return $response;

		}

		public function get_specific_customers($start = 0, $limit = 0)
        {
            $this->db->start_cache();

            $this->db->select('patient.patientID, hospice.account_location');
            $this->db->from('dme_patient as patient');
            $this->db->join('dme_hospice as hospice', 'patient.ordered_by=hospice.hospiceID');
            $this->db->order_by('patient.patientID', 'ASC');

            if ($limit != -1) {
                $this->db->limit($limit, $start);
            }

            $this->db->stop_cache();

            $query = $this->db->get();
            return $query->result_array();
        }

        public function update_customer_location($data = array())
        {
            $response = $this->db->update_batch('dme_patient', $data, 'patientID');

            return $response;
        }


	}