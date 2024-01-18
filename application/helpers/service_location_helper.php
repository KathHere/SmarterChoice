<?php

	/* ================== Helper Functions Related to Service Locations ================== */

	//check active sidebar nav
	if (!function_exists('active_link'))
	{
	    function active_link($controller)
	    {
	        $CI =& get_instance();

	        $class = $CI->router->fetch_class();
	        return ($class == $controller) ? 'active' : '';
	    }
	}

	function get_service_locations()
	{
		$CI =& get_instance();

		$CI->load->database();

		$CI->db->select('*');
		$CI->db->from('dme_location AS location');

		$query = $CI->db->get();

		return $query->result_array();
	}

	function get_current_service_location_details($location_id)
	{
		$CI =& get_instance();

		$CI->load->database();

		$CI->db->select('*');
		$CI->db->from('dme_location AS location');
		$CI->db->where('location.location_id', $location_id);

		$query = $CI->db->get();

		return $query->first_row('array');
	}

	function account_list_by_status($user_location=array(), $status)
	{
		$CI =& get_instance();

		$CI->load->database();

		$CI->db->select('*');
		$CI->db->from('dme_hospice AS hospice');

		if (!empty($status) || $status === 0) {
			$CI->db->where('hospice.account_active_sign', $status);
		}

		if ($user_location != 0) {
			$CI->db->where('hospice.account_location', $user_location);
		} else {
			$CI->db->where('hospice.account_location !=', 0);
		}
		
		$CI->db->order_by('hospice.hospice_name','ASC');

		$query = $CI->db->get();

		return $query->result_array();
	}

	function account_list_not_inactive($user_location=array())
	{
		$CI =& get_instance();

		$CI->load->database();

		$CI->db->select('*');
		$CI->db->from('dme_hospice AS hospice');

		$CI->db->where('hospice.account_active_sign != ', 0);
		$CI->db->where('hospice.account_location', $user_location);
		$CI->db->order_by('hospice.hospice_name','ASC');

		$query = $CI->db->get();

		return $query->result_array();
	}

	function account_list_all($user_location=array())
	{
		$CI =& get_instance();

		$CI->load->database();

		$CI->db->select('*');
		$CI->db->from('dme_hospice AS hospice');

		// $CI->db->where('hospice.account_active_sign != ', 0);

		if ($user_location != 0) {
			$CI->db->where('hospice.account_location', $user_location);
		} else {
			$CI->db->where('hospice.account_location !=', 0);
		}
		
		$CI->db->order_by('hospice.hospice_name','ASC');

		$query = $CI->db->get();

		return $query->result_array();
	}

?>
