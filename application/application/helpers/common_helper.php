<?php 

/* ================== Helper Functions for Validation of Data ================== */

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
	
function activeitem($active_variable = '', $active_nav = '')
{
    return $active_variable == $active_nav ? 'class="active"' : '';
}

//get usertype based on session
function get_usertype()
{
	$ci =& get_instance();
	$response = $ci->session->userdata("account_type");
	return $response;
}
//get userid
function get_userid()
{
	$ci =& get_instance();
	$response = $ci->session->userdata("userID");
	return $response;
}

//get the hospice name base on hospiceID
function get_hospice_name($hospiceID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_hospice');
	$ci->db->where('hospiceID', $hospiceID);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = array(
			"hospice_name" => $row['hospice_name']
		);
	}

	return $data;
}

function check_user_account_type($user_id)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('users.account_type');
	$ci->db->from('dme_order as orders');
	$ci->db->join('dme_user as users','orders.ordered_by=users.userID');
	$ci->db->where('users.userID', $user_id);
	$query = $ci->db->get()->result_array();

	foreach($query as $row)
	{
		$array = array(
			'account_type' => $row['account_type']
		);
		
	}

	return $array['account_type'];

}

function get_all_enroute()
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('order_status','active');
	$ci->db->group_by('uniqueID');

	$query = $ci->db->get()->result_array();

	return $query;	
}

function get_hospice_phone($hospice_id)
{
	$ci =& get_instance();
	$ci->load->database();
	$ci->load->model('hospice_model');

	$results = $ci->hospice_model->get_contact_num($hospice_id);

	foreach($results as $result)
	{
		$array = array(
			'contact_num' => $result['contact_num']
		);
	}
	return json_encode($array);
}

function get_hospice_contact($hospice_id)
{
	$ci =& get_instance();
	$ci->load->database();
	$ci->load->model('hospice_model');

	$results = $ci->hospice_model->get_contact_num($hospice_id);

	foreach($results as $result)
	{
		$array = array(
			'contact_num' => $result['contact_num']
		);
	}
	return $array['contact_num'];
}

function get_person_mobile_num($user_id)
{
	$ci =& get_instance();
	$ci->load->database();

	$ci->db->select('mobile_num');
	$ci->db->from('dme_user');
	$ci->db->where('userID', $user_id);

	$results = $ci->db->get()->result_array();

	foreach($results as $result)
	{
		$array = array(
			'mobile_num' => $result['mobile_num']
		);
	}
	return $array['mobile_num'];
}


function new_ptmove_address($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_new_ptmove_address as pt','pat.patientID=pt.patientID');
	$ci->db->where('pat.patientID', $patientID);
	$ci->db->order_by('pt.ptmoveID','DESC');
	$query = $ci->db->get()->result_array();
	
	return $query;
}


function get_ptmove_address_inputted($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_sub_ptmove');
	$ci->db->where('patientID', $patientID);
	$ci->db->order_by('ptmoveID','DESC');

	$query = $ci->db->get()->result_array();

	return $query;

}

// function get_respite_address_inputted($patientID)
// {
// 	$ci = get_instance();

// 	$ci->load->database();

// 	$ci->db->select('*');
// 	$ci->db->from('dme_sub_respite');
// 	$ci->db->where('patientID', $patientID);
// 	$ci->db->order_by('ptmoveID','DESC');

// 	$query = $ci->db->get()->result_array();

// 	return $query;

// }


function get_new_patient_phone($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_new_ptmove_address as pt','pat.patientID=pt.patientID');
	$ci->db->where('pat.patientID', $patientID);
	$query = $ci->db->get()->result_array();

	foreach($query as $row)
	{
		$array = array(
			"ptmove_patient_phone" => $row['ptmove_patient_phone'],
		);
	}

	if(!empty($array))
	{
		$array;
	}
	else
	{
		$array = "";
	}
	return $array;
}

function get_new_patient_residence($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_sub_ptmove as pt','pat.patientID=pt.patientID');
	$ci->db->where('pat.patientID', $medical_id);
	$ci->db->order_by('pt.ptmoveID','DESC');
	$query = $ci->db->get()->result_array();

	foreach($query as $row)
	{
		$array = array(
			"ptmove_patient_residence" => $row['ptmove_patient_residence'],
		);
	}

	if(!empty($array))
	{
		$array;
	}
	else
	{
		$array = "";
	}
	return $array;
}

function get_new_nextofkin($medical_id)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_sub_ptmove as pt','pat.medical_record_id=pt.medical_record_id');
	$ci->db->where('pat.medical_record_id', $medical_id);
	$query = $ci->db->get()->result_array();

	foreach($query as $row)
	{
		$array = array(
			"ptmove_nextofkin" => $row['ptmove_nextofkin'],
		);
	}

	if(!empty($array))
	{
		$array;
	}
	else
	{
		$array = "";
	}
	return $array;
}

function get_new_relationship($medical_id)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_sub_ptmove as pt','pat.medical_record_id=pt.medical_record_id');
	$ci->db->where('pat.medical_record_id', $medical_id);
	$query = $ci->db->get()->result_array();

	foreach($query as $row)
	{
		$array = array(
			"ptmove_nextofkinrelation" => $row['ptmove_nextofkinrelation'],
		);
	}

	if(!empty($array))
	{
		$array;
	}
	else
	{
		$array = "";
	}
	return $array;
}

function get_new_phonenum($medical_id)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_sub_ptmove as pt','pat.medical_record_id=pt.medical_record_id');
	$ci->db->where('pat.medical_record_id', $medical_id);
	$query = $ci->db->get()->result_array();

	foreach($query as $row)
	{
		$array = array(
			"ptmove_nextofkinphone" => $row['ptmove_nextofkinphone'],
		);
	}

	if(!empty($array))
	{
		$array;
	}
	else
	{
		$array = "";
	}
	return $array;
}



function get_ptmove_address($medical_id, $uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_sub_ptmove as pt','pat.medical_record_id=pt.medical_record_id');
	$ci->db->where('pat.medical_record_id', $medical_id);
	$query = $ci->db->get()->result_array();

	foreach($query as $row)
	{
		$array = array(
			"ptmove_medical_record_id" => $row['medical_record_id'],
			"ptmove_street"     => $row['ptmove_street'],
			"ptmove_placenum"	=> $row['ptmove_placenum'],
			"ptmove_city"		=> $row['ptmove_city'],
			"ptmove_state"	    => $row['ptmove_state'],
			"ptmove_postal" 	=> $row['ptmove_postal'],
			"ptmove_patient_phone" => $row['ptmove_patient_phone'],
			"ptmove_uniqueID"	=> $row['ptmove_uniqueID']
		);
	}

	if(!empty($array))
	{
		$array;
	}
	else
	{
		$array = "";
	}
	return $array;
}

function get_respite_address($medical_id, $uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_sub_respite as pt','pat.medical_record_id=pt.medical_record_id');
	$ci->db->where('pat.medical_record_id', $medical_id);
	$query = $ci->db->get()->result_array();

	foreach($query as $row)
	{
		$array = array(
			"respite_medical_record_id" => $row['medical_record_id'],
			"respite_address"     => $row['respite_address'],
			"respite_placenum"	=> $row['respite_placenum'],
			"respite_city"		=> $row['respite_city'],
			"respite_state"	    => $row['respite_state'],
			"respite_postal" 	=> $row['respite_postal'],
			"respite_patient_phone" => $row['respite_phone_number']
			// "respite_uniqueID"	=> $row['respite_uniqueID']
		);
	}

	if(!empty($array))
	{
		$array;
	}
	else
	{
		$array = "";
	}
	return $array;
}

/** Get the OLD ITEMS base on work order number **/
function get_old_item($unique_id)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('equip.*');
    $ci->db->select('orders.*');
    $ci->db->select('patients.*');
    $ci->db->select('hospices.*'); //newly added : remove if it will cause errors
	$ci->db->select('COALESCE((SELECT key_desc FROM dme_equipment WHERE dme_equipment.equipmentID=equip.parentID LIMIT 1),"") as parent_name',FALSE);
    $ci->db->select('COALESCE((SELECT option_description FROM dme_options WHERE dme_options.optionID=equip.optionID LIMIT 1),"") as option_description',FALSE);
    $ci->db->select('COALESCE((SELECT type FROM dme_equip_category WHERE dme_equip_category.categoryID=equip.categoryID LIMIT 1),"") as type',FALSE);
    $ci->db->select('COALESCE((SELECT activity_name FROM dme_activity_type WHERE dme_activity_type.activity_id=orders.activity_typeid LIMIT 1),"") as activity_name',FALSE);
    $ci->db->select('COALESCE((SELECT hospice_name FROM dme_hospice WHERE dme_hospice.hospiceID=orders.organization_id LIMIT 1),"") as hospice_name',FALSE); //newly added : remove if it will cause errors
	$ci->db->from('dme_order as orders');
    $ci->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
    $ci->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
    $ci->db->join('dme_patient as patients', 'orders.medical_record_id = patients.medical_record_id', 'left');
    $ci->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors

    $ci->db->where('equip.parentID', 0);
    $ci->db->where('orders.uniqueID_reference', $unique_id);
    $ci->db->group_by('orders.equipmentID');
    $ci->db->order_by('orders.date_ordered', 'ASC');
    $data = $ci->db->get()->result_array();
    return $data;
}


function get_serial_num($equipmentID, $medical_record_id)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('equipmentID', $equipmentID);
	$ci->db->where('medical_record_id', $medical_record_id);
	$ci->db->where('serial_num !=', 'pickup_order_only');

	$results = $ci->db->get()->result_array();

	foreach($results as $result)
	{
		$array = array(
			"serial_num" => $result['serial_num']
		);
	}

	return $array['serial_num'];
}

function get_original_order_date($equipmentID, $medical_record_id, $uniqueID) //remove uniqueID if it will cause errors
{
	$ci = get_instance();
	$ci->load->database();
	
	$original_act_type = check_original_act_type($equipmentID, $medical_record_id, $uniqueID);

	$ci->db->select('pickup_date');
	$ci->db->from('dme_order');
	$ci->db->where('equipmentID', $equipmentID);
	$ci->db->where('medical_record_id', $medical_record_id);


	if($original_act_type == 2)
	{
		$ci->db->where('uniqueID !=', $uniqueID);
		$ci->db->where('activity_reference', 2);
	}
	else
	{
		$ci->db->where('uniqueID', $uniqueID);
		$ci->db->where('serial_num !=', 'pickup_order_only');
	}
	

	$results = $ci->db->get()->result_array();

	foreach($results as $result)
	{
		$array = array(
			"pickup_date" => $result['pickup_date']
		);
	}

	return $array['pickup_date'];
}



function get_original_serial_number($equipmentID, $medical_record_id, $uniqueID) //remove uniqueID if it will cause errors
{
	$ci = get_instance();
	$ci->load->database();
	
	$original_act_type = check_original_act_type($equipmentID, $medical_record_id, $uniqueID);

	$ci->db->select('serial_num');
	$ci->db->from('dme_order');
	$ci->db->where('equipmentID', $equipmentID);
	$ci->db->where('medical_record_id', $medical_record_id);


	if($original_act_type == 2)
	{
		$ci->db->where('uniqueID !=', $uniqueID);
		$ci->db->where('activity_reference', 2);
		$ci->db->where('serial_num !=', 'pickup_order_only');
	}
	else
	{
		$ci->db->where('uniqueID', $uniqueID);
		$ci->db->where('serial_num !=', 'pickup_order_only');
	}
	

	$results = $ci->db->get()->result_array();

	foreach($results as $result)
	{
		$array = array(
			"serial_num" => $result['serial_num']
		);
	}

	return $array['serial_num'];
}


function check_original_act_type($equipmentID, $medical_record_id, $uniqueID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('equipmentID', $equipmentID);
	$ci->db->where('medical_record_id', $medical_record_id);
	$ci->db->where('uniqueID', $uniqueID);

	$results = $ci->db->get()->result_array();

	foreach($results as $result)
	{
		$array = array(
			"original_activity_typeid" => $result['original_activity_typeid']
		);
	}


	return $array['original_activity_typeid'];


}

//get the name of the person who putted the notes in patient notes
function get_noted_by_patient_note($noteID, $noted_by)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient_notes as notes');
	$ci->db->join('dme_user as users','notes.noted_by=users.userID','left');
	$ci->db->where('notes.noteID', $noteID);
	$ci->db->where('notes.noted_by', $noted_by);

	$results = $ci->db->get()->result_array();

	foreach($results as $result)
	{
		$array = array(
			"firstname" => $result['firstname'],
			"lastname" => $result['lastname'],
		);
	}

	return $array['firstname']." ".substr($array['lastname'],0,1).".";

}

function get_item_option_by_workorder($equipmentID, $uniqueID)
{
	$ci = get_instance();

	$ci->load->database();

	// $ci->db->select('*');
	// $ci->db->from('dme_order as orders');
	// $ci->db->join('dme_equipment as equipments','orders.equipmentID=equipments.equipmentID','left');
	// $ci->db->where('orders.uniqueID',$uniqueID);
	// $ci->db->where('equipments.parentID', $equipmentID);

	$ci->db->select('equip.*');
    $ci->db->select('orders.*');
    $ci->db->select('COALESCE((SELECT key_desc FROM dme_equipment WHERE dme_equipment.equipmentID=equip.parentID LIMIT 1),"") as parent_name',FALSE);
    $ci->db->select('COALESCE((SELECT option_description FROM dme_options WHERE dme_options.optionID=equip.optionID LIMIT 1),"") as option_description',FALSE);
    $ci->db->select('COALESCE((SELECT type FROM dme_equip_category WHERE dme_equip_category.categoryID=equip.categoryID LIMIT 1),"") as type',FALSE);
    $ci->db->select('COALESCE((SELECT activity_name FROM dme_activity_type WHERE dme_activity_type.activity_id=orders.activity_typeid LIMIT 1),"") as activity_name',FALSE);
    $ci->db->from('dme_order as orders');
    $ci->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
    $ci->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
    $ci->db->where('orders.uniqueID',$uniqueID);
	$ci->db->where('equip.parentID', $equipmentID);



	$result = $ci->db->get();

	return $result->result_array();
}

function check_editing_patient_logs($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_edit_patient_info_log');
	$query = $ci->db->get();

	return $query->result_array();

}	


/** Check if user logged in **/
function is_logged_in()
{
	$CI =& get_instance();
	
	$user_id = $CI->session->userdata('userID');
	
	if(empty($user_id))
	{
		redirect(base_url());
	}
}


function check_email_existence($email)
{
	$CI = get_instance();
	$CI->load->model('helper_model');

	$existence_number = $CI->helper_model->get_number_of_existence('is_users', 'email', $email);

	return (($existence_number > 0) ? TRUE : FALSE);
	//return $existence_number;
}

//to get the quantity of disposable items
function get_disposable_quantity($equipmentID,$uniqueID)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->join('dme_equipment as equipments','orders.equipmentID=equipments.equipmentID','left');
	$ci->db->where('equipments.parentID', $equipmentID);
	$ci->db->where('orders.uniqueID', $uniqueID);

	$query = $ci->db->get();

	if($query->result_array() == null)
	{
		$array = "";
		return $array;
	}

	else
	{
		foreach($query->result_array() as $row)
		{
			$array = array(
				'equipmentID' => $row['equipmentID'],
				'equipment_value' => $row['equipment_value'],
				'key_desc'		=> $row['key_desc']
			);
		}
		return $array['equipment_value'];
	}
}

//to get the quantity of disposable items
function get_misc_quantity($uniqueID)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->join('dme_equipment as equipments','orders.equipmentID=equipments.equipmentID','left');
	$ci->db->where('equipments.equipmentID', 308);
	$ci->db->where('orders.uniqueID', $uniqueID);

	$query = $ci->db->get();

	if($query->result_array() == null)
	{
		$array = "";
		return $array;
	}

	else
	{
		foreach($query->result_array() as $row)
		{
			$array = array(
				'equipmentID' => $row['equipmentID'],
				'equipment_value' => $row['equipment_value'],
				'key_desc'		=> $row['key_desc']
			);
		}
		return $array['equipment_value'];
	}
}


//to get the quantity of noncapped items with options
function get_noncapped_quantity($equipmentID, $uniqueID) //remove uniqueID if it will cause errors
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->join('dme_equipment as equipments','orders.equipmentID=equipments.equipmentID','left');
	$ci->db->where('equipments.parentID', $equipmentID);
	$ci->db->where('orders.uniqueID', $uniqueID);

	$query = $ci->db->get();

	if($query->result_array() == null)
	{
		$array = "";
		return $array;
	}

	else
	{
		foreach($query->result_array() as $row)
		{
			$array = array(
				'equipmentID' => $row['equipmentID'],
				'equipment_value' => $row['equipment_value'],
				'key_desc'		=> $row['key_desc']
			);
		}
		return $array['equipment_value'];
	}
}





//to get the activity name base on activityID
function get_activity_name($activityID)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('*');
	$ci->db->from('dme_activity_type');
	$ci->db->where('activity_id', $activityID);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$array = array(
			'activity_id' => $row['activity_id'],
			'activity_name' => $row['activity_name']
		);
	}
	return $array['activity_name'];

}


function get_person_name($userID)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('*');
	$ci->db->from('dme_user');
	$ci->db->where('userID', $userID);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$array = array(
			'firstname' => $row['firstname'],
			'lastname' => $row['lastname']
		);
	}
	return $array['lastname'].", ".$array['firstname'];

}



//to get the quantity of disposable items
function get_disposable_quantity_name($equipmentID)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->join('dme_equipment as equipments','orders.equipmentID=equipments.equipmentID','left');
	$ci->db->where('equipments.parentID', $equipmentID);
	
	
	$query = $ci->db->get();

	if($query->result_array() == null)
	{
		$array = "";
		return $array;
	}

	else
	{
		foreach($query->result_array() as $row)
		{
			$array = array(
				'equipmentID' => $row['equipmentID'],
				'equipment_value' => $row['equipment_value'],
				'key_desc'		=> $row['key_desc']
			);
		}
		return $array['key_desc'];
	}
}


//to get the quantity of disposable items
function get_noncapped_quantity_name($equipmentID)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->join('dme_equipment as equipments','orders.equipmentID=equipments.equipmentID','left');
	$ci->db->where('equipments.parentID', $equipmentID);

	$query = $ci->db->get();

	if($query->result_array() == null)
	{
		$array = "";
		return $array;
	}

	else
	{
		foreach($query->result_array() as $row)
		{
			$array = array(
				'equipmentID' => $row['equipmentID'],
				'equipment_value' => $row['equipment_value'],
				'key_desc'		=> $row['key_desc']
			);
		}
		return $array['key_desc'];
	}
}



function check_group_existence($group)
{
	$CI = get_instance();
	$CI->load->model('helper_model');

	$existence_number = $CI->helper_model->get_number_of_existence('is_groups', 'group_name', $group);

	return (($existence_number > 0) ? TRUE : FALSE);
	//return $existence_number;
}

function check_email_of($email, $role)
{
	$CI = get_instance();
	$CI->load->model('helper_model');

	$existence_number = $CI->helper_model->get_number_of_existence_based_on_role('is_users', 'email', $email, $role);

	return (($existence_number > 0) ? TRUE : FALSE);
}

function is_valid_email($email){
    return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email);
}

function clean($string) {
   $string = str_replace('-', ' ', $string); // Replaces all hypens with spaces.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

/* ================== End ================== */

/* ================== Helper Functions for Getting important Data on DB ================== */

//function for getting the complete name trough id
function get_name($id)
{
	$CI = get_instance();
	$CI->load->model('user_model');

	$first_name  = $CI->user_model->get('firstname', 'userID', $id);
	$middle_name = $CI->user_model->get('middlename', 'userID', $id);
	$last_name   = $CI->user_model->get('lastname', 'userID', $id);

	$fname = !empty($first_name[0]->first_name) ? $first_name[0]->first_name : FALSE ;
	$mname = !empty($middle_name[0]->middle_name) ? $middle_name[0]->middle_name : FALSE ;
	$lname = !empty($last_name[0]->last_name) ? $last_name[0]->last_name : FALSE;

	$name =  $fname.' '.$mname.' '.$lname;

	return ucwords($name); 
}

//function for getting the complete name trough id
function get_first_name($id)
{
	$CI         = get_instance();
	$CI->load->model('user_model');
	
	$first_name = $CI->accounts_model->get('firstname', 'dme_user', '', 'userID', $id);
	$fname      = !empty($first_name[0]->first_name) ? $first_name[0]->first_name : FALSE ;

	return ucwords($fname);
}

//function for getting the email trough id
function get_email($id)
{
	$CI         = get_instance();
	$CI->load->model('user_model');
	
	$email = $CI->accounts_model->get('email', 'dme_user', '', 'userID', $id);
	$email_address      = !empty($email[0]->email) ? $email[0]->email : FALSE ;

	return $email_address;
}

//function for getting the name of the company of the personel


//function for getting the ID based on the column and value
function get_id($value, $column)
{
	$CI = get_instance();
	$CI->load->model('accounts_model');

	$id = $CI->accounts_model->get('id', 'is_users', '' , $column, $value);

	return (!empty($id[0]->id) ? $id[0]->id : FALSE);
}

//function for getting the ID based on the column and value
function get_role($id)
{
	$CI = get_instance();
	$CI->load->model('accounts_model');

	$id = $CI->accounts_model->get('role', 'is_users', '' , 'id', $id);

	return (!empty($id[0]->role) ? $id[0]->role : FALSE);
}

//function for get the Complete User Role
function get_fine_role($role)
{
	$fine_role = '';
	switch ($role) 
	{
		// Roles
		case 'admin':
			$fine_role = "Administrator";
			break;
		case 'student':
			$fine_role = "Student";
			break;
		case 'assessor':
			$fine_role = "Assessor";
			break;		
		case 'personel':
			$fine_role = "Personel";
			break;		
		default:
			# code...
			break;
	}

	return $fine_role;
}

//function for get the Complete User Type
function get_fine_type($type)
{
	$fine_type = '';
	switch ($type) 
	{
		// Roles
		case 'admin':
			$fine_type = "System Administrator";
			break;
		case 'ero':
			$fine_type = "External Relations Officer";
			break;
		case 'teacher':
			$fine_type = "Academic Instructor";
			break;		
		case 'educator':
			$fine_type = "Student Educator";
			break;
		case 'student':
			$fine_type = "Student";
			break;	
		case 'applicant':
			$fine_type = "Applicant";
			break;	
		case 'intern':
			$fine_type = "On Job Trainee";
			break;						
		default:
			# code...
			break;
	}

	return $fine_type;
}

//function for uploading profile picture
function upload_profile_pic($id, $file, $for)
{
	$CI = & get_instance();
	// $id = 24; // change to "get_user('id')"
	$base_upload_path = '';
	$success = '';
	if($for=="company")
	{
		$base_upload_path = './user_uploads/uploads/companies/';
	}
	else
	{
		$base_upload_path = './user_uploads/uploads/';
	}

	$unique_id = get_code($id);
	$path_for_upload = $base_upload_path.$unique_id;
	$maked_folder = $unique_id;
	if(!file_exists($base_upload_path.$unique_id))
	{
		$maked_folder = mkdir($path_for_upload);
	} 
	
	$config['upload_path']   = 	$path_for_upload;
	$config['file_name']     = 	$unique_id.'.jpg';
	$config['allowed_types'] = 	'gif|jpg|png';
	$config['max_size']      = 	'2048';
	// $config['max_width']  = 	'1024';
	// $config['max_height'] = 	'768';
	$config['overwrite']     = 	'true';
	
	$CI->load->library('upload', $config);

	if (!$CI->upload->do_upload($file))
	{
		$error = array('error' => $CI->upload->display_errors());
		// printA($error);
		$success = FALSE;	
	}
	else
	{
		$data       = array('upload_data' => $CI->upload->data($file));
		$image_path = $data['upload_data']['full_path'];

		$img     = imageCreateFromAny($image_path);
		$_width  = imagesx($img);
		$_height = imagesy($img);

	    $img_type = '';
	    $thumb_size = $_width;

	    if ($_width > $_height)
	    {
	        // wide image
	        $config['width'] = intval(($_width / $_height) * $_height);
	        if ($config['width'] % 2 != 0)
	        {
	            $config['width']++;
	        }
	        $config['height'] = $thumb_size;
	        $img_type = 'wide';
	    }
	    else if ($_width < $_height)
	    {
	        // landscape image
	        $config['width'] = $thumb_size;
	        $config['height'] = intval(($_height / $_width) * $_width);
	        if ($config['height'] % 2 != 0)
	        {
	            $config['height']++;
	        }
	        $img_type = 'landscape';
	    }
	    else
	    {
	        // square image
	        $config['width'] = $thumb_size;
	        $config['height'] = $thumb_size;
	        $img_type = 'square';
	    }

	    $CI->load->library('image_lib');
	    $CI->image_lib->initialize($config);
	    $CI->image_lib->resize();

	    // reconfigure the image lib for cropping
	    $conf_new = array(
	        'image_library' => 'gd2',
	        'source_image' => $image_path,
	        'create_thumb' => TRUE,
	        'maintain_ratio' => FALSE,
	        'width' => $thumb_size,
	        'height' => $thumb_size
	    );

	    if ($img_type == 'wide')
	    {
	        $conf_new['x_axis'] = ($config['width'] - $thumb_size) / 2 ;
	        $conf_new['y_axis'] = 0;
	    }
	    else if($img_type == 'landscape')
	    {
	        $conf_new['x_axis'] = 0;
	        $conf_new['y_axis'] = ($config['height'] - $thumb_size) / 2;
	    }
	    else
	    {
	        $conf_new['x_axis'] = 0;
	        $conf_new['y_axis'] = 0;
	    }



	    $CI->image_lib->initialize($conf_new);

	    $CI->image_lib->crop();

	    $CI->image_lib->clear();

	    $success = TRUE;	
	}

	return $success;
}

function imageCreateFromAny($filepath) { 
    $type = exif_imagetype($filepath); // [] if you don't have exif you could use getImageSize() 
    $allowedTypes = array( 
        1,  // [] gif 
        2,  // [] jpg 
        3,  // [] png 
        6   // [] bmp 
    ); 
    if (!in_array($type, $allowedTypes)) { 
        return false; 
    } 
    switch ($type) { 
        case 1 : 
            $im = imageCreateFromGif($filepath); 
        break; 
        case 2 : 
            $im = imageCreateFromJpeg($filepath); 
        break; 
        case 3 : 
            $im = imageCreateFromPng($filepath); 
        break; 
        case 6 : 
            $im = imageCreateFromBmp($filepath); 
        break; 
    }    
    return $im;  
} 

//function for generating picture link
function generate_file_link($path, $id, $extension, $thumb='')
{
	$code = get_code($id);
	$link = base_url('user_uploads/uploads').$path.'/'.$code.'/'.$code.$thumb.'.'.$extension; 
	$image = 'user_uploads/uploads'.$path.'/'.$code.'/'.$code.$thumb.'.'.$extension; 
	$default = base_url('user_uploads/user-profile-default.png');
	$exist = read_file($image);

	return ($exist) ? $link : $default;
}

/*
*
* get assigned equipments
*/
function get_assigned_equipment($hospiceID="")
{
	$ci =& get_instance();
	$ids = array();
	if(!empty($hospiceID))
	{
		$ci->db->where('hospiceID',$hospiceID);
	}
	$data = $ci->db->get('dme_assigned_equipment')->result_array();
	foreach($data as $value)
	{
		$ids[] = $value['equipmentID'];
	}
	return $ids;
}

function get_hospices()
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('*');
	$CI->db->from('dme_hospice');
	$CI->db->order_by('hospice_name','ASC');
	
	$query = $CI->db->get();

	$data_array = array();

	foreach($query->result_array() as $key=>$c)
	{
		$data_array[$c['hospiceID']] = array(
			'hospiceID'					=> $c['hospiceID'],
			'hospice_name'				=> $c['hospice_name'],
			'contact_num'				=> $c['contact_num'],
		);
	}

	return $data_array;
}

function check_if_we_can_do_another_ptmove($medical_record_id,$patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select("*");
	$ci->db->from("dme_order as orders");
	$ci->db->join("dme_equipment as equips","orders.equipmentID=equips.equipmentID");
	$ci->db->where("orders.medical_record_id", $medical_record_id);
	$ci->db->where("orders.patientID", $patientID);
	$ci->db->where("equips.categoryID !=", 3);

	$query = $ci->db->get()->result_array();

	return $query;
}

function check_intial_orders_to_confirm($medical_record_id,$patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select("*");
	$ci->db->from("dme_order as orders");
	$ci->db->join("dme_equipment as equips","orders.equipmentID=equips.equipmentID");
	$ci->db->where("orders.medical_record_id", $medical_record_id);
	$ci->db->where("orders.patientID", $patientID);
	$ci->db->where("orders.order_status !=", "confirmed");

	$query = $ci->db->get()->result_array();
	// echo "<pre>";
	// print_r($query);
	// echo "</pre>";

	return $query;
}

function check_if_all_pickups($medical_id, $hospiceID="")
{
	$ci = get_instance();
	$ci->load->database();

    $ci->db->select('orders.activity_typeid, orders.orderID');
    $ci->db->from('dme_order as orders');
    $ci->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
    $ci->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left'); 
    $ci->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); 
	
    if($medical_id != '')
    {
        $ci->db->where('orders.medical_record_id' , $medical_id);
        $ci->db->where('orders.organization_id', $hospiceID); 
    }
    $ci->db->where('orders.pickup_order ', 0);
    $ci->db->where('equip.categoryID !=', 3);
    $ci->db->where('orders.serial_num !=','item_options_only');
    $ci->db->where('orders.canceled_from_confirming !=', 1);
    $ci->db->where('orders.canceled_order !=', 1);
   	$ci->db->group_by('orders.orderID');
    $ci->db->order_by('orders.pickup_date', 'DESC');

    $data = $ci->db->get()->result_array();

    return $data;
}

function check_if_all_pickups_v2($medical_id, $hospiceID="")
{
	$ci = get_instance();
	$ci->load->database();

    $ci->db->select('orders.activity_typeid, orders.orderID');
    $ci->db->from('dme_order as orders');
    $ci->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
    $ci->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left'); 
    $ci->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); 
	
    if($medical_id != '')
    {
        $ci->db->where('orders.medical_record_id' , $medical_id);
        $ci->db->where('orders.organization_id', $hospiceID); 
    }
    $ci->db->where('orders.pickup_order !=', 0);
    $ci->db->where('equip.categoryID !=', 3);
    $ci->db->where('orders.serial_num !=','item_options_only');
    $ci->db->where('orders.canceled_from_confirming !=', 1);
    $ci->db->where('orders.canceled_order !=', 1);
    $ci->db->where('orders.order_status !=', "confirmed");
    $ci->db->where('orders.order_status !=', "cancel");
   	$ci->db->group_by('orders.orderID');
    $ci->db->order_by('orders.pickup_date', 'DESC');

    $data = $ci->db->get()->result_array();

    return $data;
}

function check_patient_capped_items($medical_record_id,$patientID)
{
	$ci = get_instance();

	$cancel = "cancel";
	$ci->load->database();

	$ci->db->select("orders.equipmentID");
	$ci->db->from("dme_order as orders");
	$ci->db->join("dme_equipment as equips","orders.equipmentID=equips.equipmentID");
	$ci->db->where("orders.medical_record_id", $medical_record_id);
	$ci->db->where("orders.patientID", $patientID);
	$ci->db->where("equips.categoryID", 1);
	$ci->db->where("orders.activity_typeid !=", 2);
	$ci->db->where("orders.order_status !=", $cancel);

	$query = $ci->db->get()->result_array();

	// $data_array = array();

	// foreach($query->result_array() as $row)
	// {
	// 	foreach($row as $key=>$value)
	// 	{
	// 		$data_array[$key][] = $value; 
	// 	}
		
	// }

	return $query;
}

// Recursive function to check if the value exists on the array
function in_array_recursive($needle, $haystack, $strict=false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_recursive($needle, $item, $strict))) 
        {
            return true;
        }
    }
    return false;
}

// I used this recursive function so that I can also identify on what column i need to check
function in_multiarray($elem, $array,$field)
{
    $top = sizeof($array) - 1;
    $bottom = 0;
    while($bottom <= $top)
    {
        if($array[$bottom][$field] == $elem)
            return true;
        else 
            if(is_array($array[$bottom][$field]))
                if(in_multiarray($elem, ($array[$bottom][$field])))
                    return true;

        $bottom++;
    }        
    return false;
}

function get_count_status($status="pending",$additional = array())
{
	$ci = get_instance();
	$ci->load->database();

	$response = "";
	$ci->db->select('stats.*');

	$ci->db->from('dme_order_status as stats');
	$ci->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
	if($ci->session->userdata('account_type') != 'dme_admin')
	{
		$ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
	}
	$ci->db->group_by('stats.status_activity_typeid');
	$ci->db->group_by('stats.order_uniqueID');
	if(!empty($status))
	{
		$ci->db->where('orders.order_status',$status);
	}
	
	if(!empty($additional))
	{
		foreach($additional as $key=>$value)
		{
			if(!$value)
			{
				$ci->db->where($key,null,false);
			}
			else
			{
				$ci->db->where($key,$value);	
			}
		}
	}
	$query = $ci->db->get();
	return $query->num_rows();
}
function get_count_activity($status="pending",$additional = array())
{
	$ci = get_instance();
	$ci->load->database();

	$response = "";
	$ci->db->select('stats.*');

	$ci->db->from('dme_order_status as stats');
	$ci->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
	if($ci->session->userdata('account_type') != 'dme_admin')
	{
		$ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
	}
	$ci->db->group_by('stats.status_activity_typeid');
	$ci->db->group_by('stats.order_uniqueID');
	if(!empty($status))
	{
		$ci->db->where('orders.order_status',$status);
	}
	
	if(!empty($additional))
	{
		foreach($additional as $key=>$value)
		{
			$ci->db->where($key,$value);
		}
	}
	$query = $ci->db->get();
	echo $ci->db->last_query();
	return $query->num_rows();
}
/* ================== End ================== */


/* Added by Russel */
function get_patient_move_first_row($patientID)
{
	$ci = get_instance();
	$ci->load->database();

    $ci->db->select('orderID');
    $ci->db->from('dme_order as orders');
    $ci->db->where(array('patientID' => $patientID,'order_status' => 'confirmed'));
    $ci->db->where(array('original_activity_typeid' => 4 , "activity_typeid" => 4));

    $data = $ci->db->get()->first_row('array');

    return $data;
}



/* ================== End ================== */

/*
| @func_name : combine_name
| @desc 	 : combine data
| @param  : array,seperator(default is /)
*/
function combine_name($data=array(),$seperator="/")
{
	$final = array();
	foreach($data as $key=>$value)
	{
		if($value!="")
		{
			$final[] = $value;
		}
	}
	return implode(" ".$seperator." ",$final);
}

/*
| @func : fetch_report_date()
| @description : catch the descriptions
| @date : 02.05.2016
*/
function fetch_report_date()
{
	$response = array(
						"type" => 0,
						"from" => "",
						"to"   => ""
					);
	$type 	= 0;
	$from 	= "";
	$to 	= "";
	if(isset($_GET['type']) AND $_GET['type']!="")
	{
		$type = $_GET["type"];
	}
	if(isset($_GET['from']) AND $_GET['from']!="")
	{
		$from = $_GET["from"];
	}
	if(isset($_GET['to']) AND $_GET['to']!="")
	{
		$to = $_GET["to"];
	}
	//check type
	// if type = 0 //return the current month
	// if type = 1 //return all records
	// if type = 2 //return date only yesterday, date_from and date_to is the same
	// if type = 3 //return last 7 days, date to = yesterday and date from = yesterday - 7
	// if type = 4 //return last 15 days, date to = yesterday and date from = yesterday - 15
	// if type = 5 //return last 30 days, date to = yesterday and date from = yesterday - 30 
	if($type==1)
	{
		$from 	= "";
		$to 	= "";
	}
	else if($type==2)
	{
		$from 	= date('Y-m-d',strtotime("-1 days"));
		$to 	= $from;
	}
	elseif ($type==3) 
	{
		$from 	= date('Y-m-d',strtotime("-7 days"));
		$to 	= date('Y-m-d',strtotime("-1 days"));
	}
	else if($type==4)
	{
		$from 	= date('Y-m-d',strtotime("-15 days"));
		$to 	= date('Y-m-d',strtotime("-1 days"));
	}
	else if($type==5)
	{
		$from 	= date('Y-m-d',strtotime("-30 days"));
		$to 	= date('Y-m-d',strtotime("-1 days"));
	}
	//checking the validation of the dates
	if(!validateDate($from))
	{
		$from = "";
	}
	if(!validateDate($to))
	{
		$to = "";
	}

	//if to is empty automatically inherit the from value
	if(empty($to))
	{
		$to = $from;
	}
	//interchange from and to if from is greater than to
	if(strtotime($from) > strtotime($to))
	{
		$temp_from = $to;
		$temp_to   = $from;

		//assigning now
		$to 	= $temp_to;
		$from 	= $temp_from;
	}

	$response['type'] 	= $type;
	$response['from']	= $from;
	$response['to']		= $to;

	return $response;
}
function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') == $date;
}













