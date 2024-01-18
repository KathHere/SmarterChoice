<?php

/* ================== Helper Functions for Debugging ================== */

function printA($data){

	echo "<pre>";
	print_r($data); 
	echo "</pre>";

}

function get_patient($patientID='')
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('*');
	$CI->db->from('dme_patient');
	$query = $CI->db->get();

	$data_array = array();

	foreach($query->result_array() as $key=>$c)
	{
		$data_array[$c['patientID']] = array(

			'p_fname'				=> $c['p_fname'],
			'p_lname'				=> $c['p_lname'],
			'p_street'				=> $c['p_street'],
			'p_placenum'			=> $c['p_placenum'],
			'p_city'				=> $c['p_city'],
			'p_state'				=> $c['p_state'],
			'p_postalcode'			=> $c['p_postalcode'],
			'p_phonenum'			=> $c['p_phonenum'],
			'p_altphonenum'			=> $c['p_altphonenum'],
			'p_nextofkin'			=> $c['p_nextofkin'],
			'p_nextofkinnum'		=> $c['p_nextofkinnum'],
			'medical_record_id'		=> $c['medical_record_id']
		);
	}

	return $data_array;
}



function get_specific_patient($patientID='')
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('*');
	$CI->db->from('dme_patient');
	$CI->db->where('medical_record_id', $patientID);
	$query = $CI->db->get();

	$data_array = array();

	foreach($query->result_array() as $key=>$c)
	{
		$data_array[$c['patientID']] = array(

			'p_fname'				=> $c['p_fname'],
			'p_lname'				=> $c['p_lname'],
			'p_street'				=> $c['p_street'],
			'p_placenum'			=> $c['p_placenum'],
			'p_city'				=> $c['p_city'],
			'p_state'				=> $c['p_state'],
			'p_postalcode'			=> $c['p_postalcode'],
			'p_phonenum'			=> $c['p_phonenum'],
			'p_altphonenum'			=> $c['p_altphonenum'],
			'p_nextofkin'			=> $c['p_nextofkin'],
			'p_nextofkinnum'		=> $c['p_nextofkinnum'],
			'medical_record_id'		=> $c['medical_record_id']
		);
	}

	return $data_array;
}


/* ================== End ================== */
