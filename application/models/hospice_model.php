<?php
	Class hospice_model extends Ci_Model
	{

		function create($data)
		{
			$this->db->insert('dme_hospice', $data);

			return $this->db->insert_id();
		}

		function list_group()
		{
			$this->db->select('*');
			$this->db->from('dme_hospice');
			$this->db->order_by('hospice_name','ASC');

			$query = $this->db->get();

			return $query->result();
		}

		function list_group_v2()
		{
			$this->db->select('*');
			$this->db->from('dme_hospice');
			$this->db->where('dme_hospice.type', 0);
			$this->db->order_by('hospice_name','ASC');

			$query = $this->db->get();

			return $query->result();
		}

		function list_group_v3($user_location=array())
		{
			$this->db->select('*');
			$this->db->from('dme_hospice');
			$this->db->where('dme_hospice.type', 0);
			$this->db->where('dme_hospice.account_location', $user_location);
			$this->db->order_by('hospice_name','ASC');

			$query = $this->db->get();

			return $query->result();
		}

		function list_group_v4($user_location=array())
		{
			$this->db->select('*');
			$this->db->from('dme_hospice');
			$this->db->where('dme_hospice.account_location', $user_location);
			$this->db->order_by('hospice_name','ASC');

			$query = $this->db->get();

			return $query->result();
		}

		function list_group_v5($user_location)
		{
			$this->db->select('hospiceID, hospice_name');
			$this->db->from('dme_hospice');
			$this->db->where('dme_hospice.account_location', $user_location);
			$this->db->order_by('hospice_name','ASC');

			$query = $this->db->get();

			return $query->result_array();
		}

		function account_list_by_status($user_location=array(), $status)
		{
			$this->db->select('*');
			$this->db->from('dme_hospice');

			if (!empty($status) || $status === 0) {
				$this->db->where('dme_hospice.account_active_sign', $status);
			}

			if ($user_location != 0) {
				$this->db->where('dme_hospice.account_location', $user_location);
			} else {
				$this->db->where('dme_hospice.account_location !=', 0);
			}
			
			$this->db->order_by('hospice_name','ASC');

			$query = $this->db->get();

			return $query->result();
		}

		function list_group_commercial()
		{
			$this->db->select('*');
			$this->db->from('dme_hospice');
			$this->db->where('dme_hospice.type', 1);
			$this->db->order_by('hospice_name','ASC');

			$query = $this->db->get();

			return $query->result();
		}

		function list_group_commercial_v2($account_location)
		{
			$this->db->select('*');
			$this->db->from('dme_hospice');
			$this->db->where('dme_hospice.type', 1);
			$this->db->where('dme_hospice.account_location', $account_location);
			$this->db->order_by('hospice_name','ASC');

			$query = $this->db->get();

			return $query->result();
		}

		function update_hospice($hospiceID, $data)
		{
			$response = false;

			$this->db->where('hospiceID', $hospiceID);
		   	$response =  $this->db->update('dme_hospice', $data);

		   	return $response;
		}

		function delete_hospice($hospiceID)
		{
			$response = false;

			if(!empty($hospiceID))
			{
				$this->db->where('hospiceID', $hospiceID);
				$response = $this->db->delete('dme_hospice');
			}
			return $response;

		}

		function get_contact_num($hospiceID)
		{
			$this->db->select('contact_num');
			$this->db->from('dme_hospice');
			$this->db->where('hospiceID', $hospiceID);

			return $this->db->get()->result_array();
		}

		function get_address($hospiceID)
		{
			$this->db->select('hospice_address');
			$this->db->from('dme_hospice');
			$this->db->where('hospiceID', $hospiceID);

			return $this->db->get()->result_array('array');
		}

		function get_account_work_orders($hospiceID, $status='')
		{
			$this->db->select('*');
			$this->db->from('dme_order_status as order_status');
			$this->db->where('order_status.organization_id', $hospiceID);

			if (!empty($status)) {
				$this->db->where('order_status.order_status', $status);
			}

			return $this->db->get()->result_array('array');
		}

		function get_account_unconfirmed_work_orders($hospiceID)
		{
			$this->db->select('*');
			$this->db->from('dme_order_status as order_status');
			$this->db->where('order_status.organization_id', $hospiceID);

			$this->db->where('order_status.order_status !=', 'confirmed');
			$this->db->where('order_status.order_status !=', 'cancel');

			return $this->db->get()->result_array('array');
		}

		function check_hospice_if_existing($hospice_name, $account_location) {
			$this->db->select('hospiceID');
			$this->db->from('dme_hospice');
			$this->db->where('hospice_name', $hospice_name);
			$this->db->where('account_location', $account_location);

			return $this->db->get()->first_row('array');
		}
	}

?>