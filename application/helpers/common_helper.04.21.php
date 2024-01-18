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


function array_msort($array, $cols)
{
    $colarr = array();
    foreach ($cols as $col => $order) {
        $colarr[$col] = array();
        foreach ($array as $k => $row) { $colarr[$col]['_'.$k] = strtolower($row[$col]); }
    }
    $eval = 'array_multisort(';
    foreach ($cols as $col => $order) {
        $eval .= '$colarr[\''.$col.'\'],'.$order.',';
    }
    $eval = substr($eval,0,-1).');';
    eval($eval);
    $ret = array();
    foreach ($colarr as $col => $arr) {
        foreach ($arr as $k => $v) {
            $k = substr($k,1);
            if (!isset($ret[$k])) $ret[$k] = $array[$k];
            $ret[$k][$col] = $array[$k][$col];
        }
    }
    return $ret;

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

function insert_sample_function($data_to_save)
{
	$ci =& get_instance();
	$ci->load->database();

	$ci->db->insert('sample_table', $data_to_save);
}

//get th evalue of this equipment
function get_value_equipment($value_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('orderID', $value_id);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = array(
			"equipment_value" => $row['equipment_value']
		);
	}

	return $data;
}

function update_enroute_orders($data_to_save,$uniqueID,$patientID)
{
	$ci =& get_instance();
	$ci->load->database();

	$ci->db->where('uniqueID', $uniqueID);
	$ci->db->where('patientID', $patientID);
	$response = $ci->db->update('dme_order', $data_to_save);
}

function update_enroute_orders_statuses($data_to_save,$uniqueID,$patientID)
{
	$ci =& get_instance();
	$ci->load->database();

	$ci->db->where('order_uniqueID', $uniqueID);
	$ci->db->where('patientID', $patientID);
	$response = $ci->db->update('dme_order_status', $data_to_save);
}

function list_orders_scheduled_today($current_date)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('orders.uniqueID,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
    $ci->db->select('patient.patientID');

	$ci->db->from('dme_order_status as stats');
	$ci->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
	$ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$ci->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$ci->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$ci->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	$ci->db->where('orders.pickup_date',$current_date);
	$ci->db->where('orders.order_status !=', 'pending');
	$ci->db->where('orders.order_status !=', 'cancel');
	$ci->db->where('orders.order_status !=', 're-schedule');
	$ci->db->where('orders.order_status !=', 'active');

	$ci->db->group_by('stats.status_activity_typeid');
	$ci->db->group_by('stats.order_uniqueID');

	$query = $ci->db->get();
	return $query->result_array();
}

function list_order_status_rescheduled()
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('orders.uniqueID,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
    $ci->db->select('patient.patientID');

	$ci->db->from('dme_order_status as stats');
	$ci->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
	$ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$ci->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$ci->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$ci->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	$ci->db->where('orders.order_status', 're-schedule');

	$ci->db->group_by('stats.status_activity_typeid');
	$ci->db->group_by('stats.order_uniqueID');

	$query = $ci->db->get();
	return $query->result_array();
}

function list_order_status_enroute()
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('orders.uniqueID,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
	$ci->db->select('patient.patientID');

	$ci->db->from('dme_order_status as stats');
	$ci->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
	$ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$ci->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$ci->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$ci->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	$ci->db->where('orders.order_status', 'active');

	$ci->db->group_by('stats.status_activity_typeid');
	$ci->db->group_by('stats.order_uniqueID');

	$query = $ci->db->get();
	return $query->result_array();
}

function get_next_order_after_this($uniqueID,$orderID,$date_ordered)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('uniqueID !=', $uniqueID);
	$ci->db->where('orderID >', $orderID);
	$ci->db->where('date_ordered !=', $date_ordered);
	$ci->db->order_by('orderID',"ASC");
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_order_actual_order_date($uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('uniqueID', $uniqueID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_first_row_respite_address($addressID,$patientID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->join("dme_sub_respite as respite","orders.uniqueID=respite.order_uniqueID","left");
	$ci->db->where('orders.addressID', $addressID);
	$ci->db->where('orders.patientID', $patientID);
	$ci->db->order_by('orderID',"ASC");
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_unique_order_details($uniqueID,$date_choosen)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('uniqueID', $uniqueID);
	$ci->db->where('DATE_FORMAT(date_ordered,"%Y-%m-%d") <', $date_choosen);
	$query = $ci->db->get();

	return $query->result_array();
}

// Get all the item pending orders.
function get_item_orders($item_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('order_req.purchase_order_no');
	$ci->db->from('dme_inventory_item as inventory_item');
	$ci->db->join("dme_order_requisition as order_req","order_req.order_req_id=inventory_item.order_req_id");
	$ci->db->where('inventory_item.item_id', $item_id);
	$ci->db->where('order_req.order_req_status !=', "received");
	$ci->db->where('order_req.order_req_status !=', "cancelled");
	$query = $ci->db->get();

	return $query->result_array();
}

// get all the inventory item that went through order requisition. Result will be by Item Description
function get_all_inventory_item_po_item_desc_distinct()
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->distinct('inventory_item.item_id');
	$ci->db->select('item.item_description,item.item_id');
	$ci->db->from('dme_vendor as vendor');
	$ci->db->join("dme_item as item","vendor.vendor_id=item.item_vendor","left");
	$ci->db->join("dme_inventory_item as inventory_item","item.item_id=inventory_item.item_id","left");
	$ci->db->where('inventory_item.purchase_order_no !=', 0);
	$ci->db->where('inventory_item.item_serial_no !=', "");
	$ci->db->where('inventory_item.item_asset_no !=', "");
	$query = $ci->db->get();

	return $query->result_array();
}

// get all the inventory item that went through order requisition. Result will be by Item Number
function get_all_inventory_item_po_item_no_distinct()
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->distinct('inventory_item.item_id');
	$ci->db->select('item.company_item_no,item.item_id');
	$ci->db->from('dme_vendor as vendor');
	$ci->db->join("dme_item as item","vendor.vendor_id=item.item_vendor","left");
	$ci->db->join("dme_inventory_item as inventory_item","item.item_id=inventory_item.item_id","left");
	$ci->db->where('inventory_item.purchase_order_no !=', 0);
	$ci->db->where('inventory_item.item_serial_no !=', "");
	$ci->db->where('inventory_item.item_asset_no !=', "");
	$query = $ci->db->get();

	return $query->result_array();
}

//get value of equipment for controller
function get_value_of_equipment_controller($value, $patientID, $uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('equipmentID', $value);
	$ci->db->where('uniqueID', $uniqueID);
	$ci->db->where('patientID', $patientID);

	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = array(
			"equipment_value" => $row['equipment_value']
		);
	}

	return $data;
}

function print_me($value)
{
	echo "<pre>";
	print_r($value);
	echo "</pre>";
}

//get the value of parent id
function get_value_of_equipment($sub_equipmentID_parentID, $patientID, $item_uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('medical_record_id', $patientID);
	$ci->db->where('equipmentID', $sub_equipmentID_parentID);
	$ci->db->where('uniqueID', $item_uniqueID);

	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = array(
			"equipment_value" => $row['equipment_value']
		);
	}

	return $data;
}

function check_capped_copy($key_name)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_equipment');
	$ci->db->where("key_name", $key_name);
	$ci->db->where("categoryID", 1);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_equipment_order($equipmentID,$uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where("equipmentID", $equipmentID);
	$ci->db->where("uniqueID", $uniqueID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_equipment_order_v2($medical_record_id,$uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where("medical_record_id", $medical_record_id);
	$ci->db->where("uniqueID", $uniqueID);
	$ci->db->where("pickedup_uniqueID !=", $uniqueID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_equipment_details_on_order($equipmentID,$uniqueID,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where("medical_record_id", $medical_record_id);
	$ci->db->where("uniqueID", $uniqueID);
	$ci->db->where("equipmentID", $equipmentID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_item_unit_of_measure_cost($item_id,$unit_of_measure)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_item_cost');
	$ci->db->where("item_id", $item_id);
	$ci->db->where("item_unit_measure", $unit_of_measure);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_equipment_pickup_order($medical_record_id,$pickedup_uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where("medical_record_id", $medical_record_id);
	$ci->db->where("uniqueID", $pickedup_uniqueID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_los_current_date()
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(patient_total_los) as total_patient_los');
	$ci->db->from('dme_patient_total_los_per_hospice');
	$ci->db->where("date_saved", date('Y-m-d'));
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_los_current_date_v2($account_location)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(patient_total_los) as total_patient_los');
	$ci->db->from('dme_patient_total_los_per_hospice as total_los');
	$ci->db->join("dme_hospice as hospice","total_los.hospiceID=hospice.hospiceID","left");
	$ci->db->where("total_los.date_saved", date('Y-m-d'));
	$ci->db->where("hospice.account_location", $account_location);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_los_current_date_hopice($hospiceID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(patient_total_los) as total_patient_los');
	$ci->db->from('dme_patient_total_los_per_hospice');
	$ci->db->where("date_saved", date('Y-m-d'));
	$ci->db->where("hospiceID", $hospiceID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_los_current_date_hopice_v2($hospiceID=array(),$account_location)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(patient_total_los) as total_patient_los');
	$ci->db->from('dme_patient_total_los_per_hospice as total_los');
	$ci->db->join("dme_hospice as hospice","total_los.hospiceID=hospice.hospiceID","left");
	$ci->db->where("total_los.date_saved", date('Y-m-d'));
	if(!empty($hospiceID))
	{
		$ci->db->where("total_los.hospiceID", $hospiceID);
	}
	$ci->db->where("hospice.account_location", $account_location);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_los_specific_date($from,$to,$selected_hospiceID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(patient_total_los) as total_patient_los');
	$ci->db->from('dme_patient_total_los_per_hospice');
	$ci->db->where("date_saved >=", $from);
	$ci->db->where("date_saved <=", $to);
	if(!empty($selected_hospiceID))
	{
		$ci->db->where("hospiceID", $selected_hospiceID);
	}

	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_los_specific_date_v2($from,$to,$selected_hospiceID,$account_location)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(patient_total_los) as total_patient_los');
	$ci->db->from('dme_patient_total_los_per_hospice as total_los');
	$ci->db->join("dme_hospice as hospice","total_los.hospiceID=hospice.hospiceID","left");
	$ci->db->where("total_los.date_saved >=", $from);
	$ci->db->where("total_los.date_saved <=", $to);
	if(!empty($selected_hospiceID))
	{
		$ci->db->where("total_los.hospiceID", $selected_hospiceID);
	}
	$ci->db->where("hospice.account_location", $account_location);

	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_days_current_date()
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(total_patient_days) as total_patient_days');
	$ci->db->from('dme_total_patient_days_per_hospice');
	$ci->db->where("date_saved", date('Y-m-d'));
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_days_current_date_v2($account_location)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(total_patient_days) as total_patient_days');
	$ci->db->from('dme_total_patient_days_per_hospice as total_pt_days');
	$ci->db->join("dme_hospice as hospice","total_pt_days.hospiceID=hospice.hospiceID","left");
	$ci->db->where("total_pt_days.date_saved", date('Y-m-d'));
	$ci->db->where("hospice.account_location",$account_location);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_days_current_date_hospice($hospiceID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(total_patient_days) as total_patient_days');
	$ci->db->from('dme_total_patient_days_per_hospice');
	$ci->db->where("date_saved", date('Y-m-d'));
	$ci->db->where("hospiceID",$hospiceID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_days_current_date_hospice_v2($hospiceID=array(),$account_location)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(total_patient_days) as total_patient_days');
	$ci->db->from('dme_total_patient_days_per_hospice as total_pt_days');
	$ci->db->join("dme_hospice as hospice","total_pt_days.hospiceID=hospice.hospiceID","left");
	$ci->db->where("total_pt_days.date_saved", date('Y-m-d'));
	if(!empty($hospiceID))
	{
		$ci->db->where("total_pt_days.hospiceID",$hospiceID);
	}
	$ci->db->where("hospice.account_location",$account_location);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_days_specific_date($from,$to,$selected_hospiceID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(total_patient_days) as total_patient_days');
	$ci->db->from('dme_total_patient_days_per_hospice');
	$ci->db->where("date_saved >=", $from);
	$ci->db->where("date_saved <=", $to);
	if(!empty($selected_hospiceID))
	{
		$ci->db->where("hospiceID", $selected_hospiceID);
	}
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_total_patient_days_specific_date_v2($from,$to,$selected_hospiceID,$account_location)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('SUM(total_patient_days) as total_patient_days');
	$ci->db->from('dme_total_patient_days_per_hospice as total_pt_days');
	$ci->db->join("dme_hospice as hospice","total_pt_days.hospiceID=hospice.hospiceID","left");
	$ci->db->where("total_pt_days.date_saved >=", $from);
	$ci->db->where("total_pt_days.date_saved <=", $to);
	if(!empty($selected_hospiceID))
	{
		$ci->db->where("total_pt_days.hospiceID", $selected_hospiceID);
	}
	$ci->db->where("hospice.account_location",$account_location);

	$query = $ci->db->get();
	return $query->first_row('array');
}

function check_if_first_delivery($medical_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where("medical_record_id", $medical_id);
	$ci->db->order_by('orderID',"ASC");
	$query = $ci->db->get();

	return $query->first_row('array');
}

function update_patient_profile_date($data_update_profile_data,$patientID)
{
	$ci =& get_instance();
	$ci->load->database();

	$ci->db->where('patientID', $patientID);
	$response = $ci->db->update('dme_patient', $data_update_profile_data);
}

function get_original_activity_typeid($equipmentID, $medical_id, $uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('orderID,original_activity_typeid,uniqueID_reference');
	$ci->db->from('dme_order');
	$ci->db->where("equipmentID", $equipmentID);
	$ci->db->where("medical_record_id", $medical_id);
	$ci->db->where("uniqueID", $uniqueID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_uniqueID_through_orderID($new_orderID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('uniqueID');
	$ci->db->from('dme_order');
	$ci->db->where("orderID", $new_orderID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_orderID_through_uniqueID($uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('orderID');
	$ci->db->from('dme_order');
	$ci->db->where("uniqueID", $uniqueID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_orderID_through_uniqueID_v2($uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('orderID');
	$ci->db->from('dme_order');
	$ci->db->where("uniqueID_reference", $uniqueID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_capped_sub_equipment($equipmentID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_equipment');
	$ci->db->where("parentID", $equipmentID);
	$ci->db->where("categoryID", 1);
	$query = $ci->db->get();

	return $query->result_array();
}

function get_patient_order($patientID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where("patientID", $patientID);
	$query = $ci->db->get();

	return $query->result_array();
}

function check_capped_copy_v2($equipmentID)
{
	$ci =& get_instance();

	$ci->load->database();


	$ci->db->select('*');
	$ci->db->from('dme_equipment');
	$ci->db->where("noncapped_reference", $equipmentID);
	$ci->db->where("categoryID", 1);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function check_if_match($uniqueID,$patientID)
{
	$ci =& get_instance();

	$ci->load->database();


	$ci->db->select('*');
	$ci->db->from('dme_pickup_tbl');
	$ci->db->where("order_uniqueID", $uniqueID);
	$ci->db->where("patientID", $patientID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function check_if_assigned($equipmentID,$hospiceID)
{
	$ci =& get_instance();

	$ci->load->database();


	$ci->db->select('*');
	$ci->db->from('dme_assigned_equipment');
	$ci->db->where("equipmentID", $equipmentID);
	$ci->db->where("hospiceID", $hospiceID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_equipment_details($equipmentID)
{
	$ci =& get_instance();

	$ci->load->database();


	$ci->db->select('*');
	$ci->db->from('dme_equipment');
	$ci->db->where("equipmentID", $equipmentID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function check_assigned_capped_copy($equipmentID,$hospiceID)
{
	$ci =& get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_assigned_equipment');
	$ci->db->where("equipmentID", $equipmentID);
	$ci->db->where("hospiceID", $hospiceID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_serial_num_original($equipmentID,$uniqueID)
{
	$ci =& get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->where("orders.equipmentID", $equipmentID);
	$ci->db->where("orders.pickedup_uniqueID", $uniqueID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_non_capped_copy($equipmentID)
{
	$ci =& get_instance();
	$ci->load->database();

	$ci->db->select('noncapped_reference');
	$ci->db->from('dme_equipment');
	$ci->db->where("equipmentID", $equipmentID);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_misc_item_description($equipmentID,$uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from("dme_order as orders");
	$ci->db->join("dme_equipment as equipments","orders.equipmentID=equipments.equipmentID","left");
	$ci->db->where('equipments.parentID', $equipmentID);
	$ci->db->where('orders.uniqueID', $uniqueID);
	$ci->db->where_in('equipments.equipmentID',array(307,311,314));

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
			if($row['key_name'] == "item_description")
			{
				$array = array(
					'equipmentID' => $row['equipmentID'],
					'equipment_value' => $row['equipment_value'],
					'key_desc'		=> $row['key_desc']
				);
			}
		}
		return $array['equipment_value'];
	}
}

function get_equipment_delivery($equipmentID,$uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('orders.*');
	$ci->db->from("dme_order as orders");
	$ci->db->join("dme_equipment as equipments","orders.equipmentID=equipments.equipmentID","left");
	$ci->db->where('orders.equipmentID', $equipmentID);
	$ci->db->where('orders.pickedup_uniqueID', $uniqueID);

	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_equipment_serial_number($equipmentID,$uniqueID,$equipment_count)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from("dme_order as orders");
	$ci->db->join("dme_equipment as equipments","orders.equipmentID=equipments.equipmentID","left");
	$ci->db->where('orders.uniqueID !=', $uniqueID);
	$ci->db->where('orders.pickedup_uniqueID', $uniqueID);
	$ci->db->where('orders.equipmentID', $equipmentID);
	$ci->db->order_by('orders.orderID','ASC');

	$query = $ci->db->get();

	if($query->result_array() == null)
	{
		$array = "";
		return $array;
	}
	else
	{
		$count = 1;
		foreach($query->result_array() as $row)
		{
			if($count == $equipment_count)
			{
					$array = array(
						'equipmentID' => $row['equipmentID'],
						'serial_num' => $row['serial_num']
					);
				$count++;
			}
			else
			{
				$count++;
			}
		}
		return $array['serial_num'];
	}
	// return $query->result_array();
}

function get_misc_item_description_v2($equipmentID,$uniqueID,$item_description_count)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from("dme_order as orders");
	$ci->db->join("dme_equipment as equipments","orders.equipmentID=equipments.equipmentID","left");
	$ci->db->where('equipments.parentID', $equipmentID);
	$ci->db->where('orders.uniqueID', $uniqueID);
	$ci->db->where_in('equipments.equipmentID',array(307,311,314));
	$ci->db->order_by('orders.orderID','ASC');

	$query = $ci->db->get();

	if($query->result_array() == null)
	{
		$array = "";
		return $array;
	}
	else
	{
		$count = 1;
		foreach($query->result_array() as $row)
		{
			if($count == $item_description_count)
			{
				if($row['key_name'] == "item_description")
				{
					$array = array(
						'equipmentID' => $row['equipmentID'],
						'equipment_value' => $row['equipment_value'],
						'key_desc'		=> $row['key_desc']
					);
				}
				$count++;
			}
			else
			{
				$count++;
			}
		}
		return $array['equipment_value'];
	}
}

function get_original_serial_number_v2($equipmentID, $medical_record_id, $uniqueID,$item_description_count) //remove uniqueID if it will cause errors
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
		$ci->db->where('pickedup_uniqueID', $uniqueID);
	}
	else
	{
		$ci->db->where('uniqueID', $uniqueID);
		$ci->db->where('serial_num !=', 'pickup_order_only');
	}

	$ci->db->order_by('dme_order.orderID','ASC');
	$results = $ci->db->get()->result_array();

	$count = 1;
	foreach($results as $result)
	{
		if($count == $item_description_count)
		{
			$array = array(
				"serial_num" => $result['serial_num']
			);
			$count++;
		}
		else
		{
			$count++;
		}
	}
	return $array['serial_num'];
}

function get_original_uniqueID_v2($equipmentID, $medical_record_id, $uniqueID,$item_description_count) //remove uniqueID if it will cause errors
{
	$ci = get_instance();
	$ci->load->database();

	$original_act_type = check_original_act_type($equipmentID, $medical_record_id, $uniqueID);

	$ci->db->select('uniqueID');
	$ci->db->from('dme_order');
	$ci->db->where('equipmentID', $equipmentID);
	$ci->db->where('medical_record_id', $medical_record_id);

	if($original_act_type == 2)
	{
		$ci->db->where('uniqueID !=', $uniqueID);
		$ci->db->where('activity_reference', 2);
		$ci->db->where('serial_num !=', 'pickup_order_only');
		$ci->db->where('pickedup_uniqueID', $uniqueID);
	}
	else
	{
		$ci->db->where('uniqueID', $uniqueID);
		$ci->db->where('serial_num !=', 'pickup_order_only');
	}

	$ci->db->order_by('dme_order.orderID','ASC');
	$results = $ci->db->get()->result_array();

	$count = 1;
	foreach($results as $result)
	{
		if($count == $item_description_count)
		{
			$array = array(
				"uniqueID" => $result['uniqueID']
			);
			$count++;
		}
		else
		{
			$count++;
		}
	}
	return $array['uniqueID'];
}

function get_original_equipment_value_v2($equipmentID, $medical_record_id, $original_uniqueID , $uniqueID) //remove uniqueID if it will cause errors
{
	$ci = get_instance();
	$ci->load->database();

	$original_act_type = check_original_act_type($equipmentID, $medical_record_id, $uniqueID);

	$ci->db->select('equipment_value');
	$ci->db->from('dme_order');
	$ci->db->where('equipmentID', $equipmentID);
	$ci->db->where('medical_record_id', $medical_record_id);

	if($original_act_type == 2)
	{
		$ci->db->where('uniqueID', $original_uniqueID);
	}
	else
	{
		$ci->db->where('uniqueID', $uniqueID);
		$ci->db->where('serial_num !=', 'pickup_order_only');
	}

	$ci->db->order_by('dme_order.orderID','ASC');
	$results = $ci->db->get()->result_array();

	foreach($results as $result)
	{
		$array = array(
			"equipment_value" => $result['equipment_value']
		);
	}
	return $array['equipment_value'];
}

function get_misc_item_description_v3($equipmentID,$uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from("dme_order as orders");
	$ci->db->where('orders.equipmentID', $equipmentID);
	$ci->db->where('orders.uniqueID', $uniqueID);
	$ci->db->order_by('orders.orderID','ASC');

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
				'equipment_value' => $row['equipment_value']
			);
		}
		return $array['equipment_value'];
	}
}

function get_oxygen_conserving_device($equipmentID,$uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from("dme_order as orders");
	$ci->db->join("dme_equipment as equipments","orders.equipmentID=equipments.equipmentID","left");
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

			if($row['equipmentID'] == 199 || $row['equipmentID'] == 200 || $row['equipmentID'] == 197 || $row['equipmentID'] == 198)
			{
				$array = array(
					'equipmentID' => $row['equipmentID'],
					'equipment_value' => $row['equipment_value'],
					'key_desc'		=> $row['key_desc']
				);
			}
		}
		return $array['key_desc'];

	}
}

function get_hospital_bed_extra_long($equipmentID,$uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from("dme_order as orders");
	$ci->db->join("dme_equipment as equipments","orders.equipmentID=equipments.equipmentID","left");
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
		return $array['key_desc'];

	}
}

function get_status_id($uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order_status');
	$ci->db->where('order_uniqueID', $uniqueID);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_reschreschedule_onhold_date($statusID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_reschedule_onhold_dates');
	$ci->db->where('statusID', $statusID);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

//get the pickuped unique id
function get_different($uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('uniqueID != ', $uniqueID);
	$ci->db->where('pickedup_uniqueID', $uniqueID);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = array(
			"serial_num" => $row['serial_num'],
			"original_activity_typeid" => $row['original_activity_typeid']
		);
	}

	return $data;
}

function get_exchange_order($uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('uniqueID != ', $uniqueID);
	$ci->db->where('uniqueID_reference', $uniqueID);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = array(
			"serial_num" => $row['serial_num'],
			"order_status" => $row['order_status'],
			"original_activity_typeid" => $row['original_activity_typeid']
		);
	}

	return $data;
}

function check_if_new_patient($medical_record_id,$uniqueID,$organization_id,$getfirst=false)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('medical_record_id', $medical_record_id);
	$ci->db->where('organization_id', $organization_id);
	$ci->db->where('uniqueID', $uniqueID);
	$ci->db->where('order_status', "confirmed");
  if($getfirst){
    $ci->db->limit(1);
  }
	$query = $ci->db->get()->result_array();

	return $query;
}

function get_patients_first_order_uniqueID($medical_record_id,$organization_id,$getfirst=false)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('medical_record_id', $medical_record_id);
	$ci->db->where('organization_id', $organization_id);
	$ci->db->where('order_status !=', "cancel");
	$ci->db->order_by("orderID","ASC");
  if($getfirst){
    $ci->db->limit(1);
  }
	$query = $ci->db->get()->first_row('array');

	return $query;
}

//get th evalue of this equipment with patient id
function get_value_equip($patientID,$val)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('patientID', $patientID);
	$ci->db->where('equipmentID', $val);

	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = array(
			"equipment_value" => $row['equipment_value']
		);
	}

	return $data;
}

//get th evalue of this equipment with patient id
function get_value_equip_new($patientID,$val,$unique_ids)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('patientID', $patientID);
	$ci->db->where_in('uniqueID', $unique_ids);
	$ci->db->where('equipmentID', $val);

	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = array(
			"equipment_value" => $row['equipment_value']
		);
	}

	return $data;
}

//get th evalue of this equipment with patient id
function get_value_equip_new_v2($patientID,$val,$unique_ids)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('patientID', $patientID);
	$ci->db->where_in('uniqueID', $unique_ids);
	$ci->db->where('equipmentID', $val);
	$ci->db->where('activity_typeid !=', 2);

	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = array(
			"equipment_value" => $row['equipment_value']
		);
	}

	return $data;
}

function get_value_equip_liter_flow($patientID,$val,$orderID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('patientID', $patientID);
	$ci->db->where('equipmentID', $val);
	$ci->db->where('orderID', $orderID);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = array(
			"equipment_value" => $row['equipment_value']
		);
	}

	return $data;
}

//get liter flow
function get_liter_flow($organization_id,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('o.*,e.parentID');
	$ci->db->from('dme_order as o');

	$ci->db->join('dme_equipment as e','o.equipmentID = e.equipmentID',"left");
	$ci->db->where('o.medical_record_id', $medical_record_id);
	$ci->db->where('o.organization_id', $organization_id);
	$ci->db->where('o.activity_typeid !=', 2);
	$ci->db->where('o.order_status !=', 'cancel');
	$ci->db->where_in('o.equipmentID',array(77,100,191,189,188,190,240,201,317,326,335,344));

	$ci->db->order_by("date_ordered","DESC");
	$ci->db->limit(1);
	$query = $ci->db->get();
	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

//get the first e-refill oxygen cylinder per address
function get_first_e_refill_per_address($medical_record_id,$patientID,$organization_id,$addressID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('o.orderID');
	$ci->db->from('dme_order as o');
	$ci->db->where('o.medical_record_id', $medical_record_id);
	$ci->db->where('o.organization_id', $organization_id);
	$ci->db->where('o.patientID', $patientID);
	$ci->db->where('o.addressID', $addressID);
	$ci->db->where('o.equipmentID', 11);
	$ci->db->order_by("orderID","ASC");
	$ci->db->limit(1);
	$query = $ci->db->get();
	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

//get the first m6-refill oxygen cyliner per address
function get_first_m6_refill_per_address($medical_record_id,$patientID,$organization_id,$addressID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('o.orderID');
	$ci->db->from('dme_order as o');
	$ci->db->where('o.medical_record_id', $medical_record_id);
	$ci->db->where('o.organization_id', $organization_id);
	$ci->db->where('o.patientID', $patientID);
	$ci->db->where('o.addressID', $addressID);
	$ci->db->where('o.equipmentID', 170);
	$ci->db->order_by("orderID","ASC");
	$ci->db->limit(1);
	$query = $ci->db->get();
	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

//get parent ID
function get_parent_id($organization_id,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('o.*,e.parentID');
	$ci->db->from('dme_order as o');

	$ci->db->join('dme_equipment as e','o.equipmentID = e.equipmentID',"left");
	$ci->db->where('o.medical_record_id', $medical_record_id);
	$ci->db->where('o.organization_id', $organization_id);
	$ci->db->where('o.activity_typeid', 1);
	$ci->db->where('o.order_status !=', 'cancel');
	$ci->db->where_in('o.equipmentID',array(77,100,191,189,188,190,240,201,317,326,335,344));

	$ci->db->order_by("date_ordered","DESC");
	$ci->db->limit(1);
	$query = $ci->db->get();
	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

//get parent ID
function get_parent_id_duration($organization_id,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('o.*,e.parentID');
	$ci->db->from('dme_order as o');

	$ci->db->join('dme_equipment as e','o.equipmentID = e.equipmentID',"left");
	$ci->db->where('o.medical_record_id', $medical_record_id);
	$ci->db->where('o.organization_id', $organization_id);
	$ci->db->where('o.order_status !=', 'cancel');
	$ci->db->where('o.activity_typeid', 1);

	$ci->db->where_in('o.equipmentID',array(318,319,327,328,336,337,345,346));

	$ci->db->order_by("date_ordered","DESC");
	$ci->db->limit(1);
	$query = $ci->db->get();
	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

function get_latest_delivery_device($organization_id,$medical_record_id)
{
	$ci =& get_instance();
	$ci->load->database();

	$ci->db->select('o.*,e.*');
	$ci->db->from('dme_order as o');

	$ci->db->join('dme_equipment as e','o.equipmentID = e.equipmentID',"left");
	$ci->db->where('o.medical_record_id', $medical_record_id);
	$ci->db->where('o.organization_id', $organization_id);
	// $ci->db->where('o.activity_typeid', 1);

	$ci->db->where_in('o.equipmentID',array(82,83,105,106,204,205,320,321,329,330,338,339,347,348));

	$ci->db->order_by("uniqueID","DESC");
	$ci->db->limit(1);
	$query = $ci->db->get();
	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

function get_parent_id_duration_v2($organization_id,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('o.*,e.parentID');
	$ci->db->from('dme_order as o');

	$ci->db->join('dme_equipment as e','o.equipmentID = e.equipmentID',"left");
	$ci->db->where('o.medical_record_id', $medical_record_id);
	$ci->db->where('o.organization_id', $organization_id);
	$ci->db->where('o.order_status !=', 'cancel');
	$ci->db->where('o.activity_typeid !=', 2);

	$ci->db->where_in('o.equipmentID',array(318,319,327,328,336,337,345,346));

	$ci->db->order_by("date_ordered","DESC");
	$ci->db->limit(1);
	$query = $ci->db->get();
	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

//get parent ID MARJ v2
function get_parental_id($equipmentID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_equipment');
	$ci->db->where('parentID', $equipmentID);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

//get equipment options
function get_equipment_options($equipmentID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_equipment');
	$ci->db->where('parentID', $equipmentID);
	$query = $ci->db->get()->result_array();

	return $query;
}

//get serial num of parent id
function get_serial_num_parent($parentID, $uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('equipmentID', $parentID);
	$ci->db->where('pickedup_uniqueID', $uniqueID);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

//get serial num of parent id
function get_serial_num_parent_v2($parentID, $uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('equipmentID', $parentID);
	$ci->db->where('pickedup_uniqueID', $uniqueID);
	$ci->db->where('original_activity_typeid !=', 2);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

//get CNT
function get_duration_cnt($organization_id,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('organization_id', $organization_id);
	$ci->db->where('medical_record_id', $medical_record_id);
	$ci->db->where('equipmentID', 78);
	$ci->db->where('equipmentID', 103);
	$ci->db->where('activity_typeid', 1);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}
function get_duration_order($duration_lists=array(),$uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();
	if(empty($duration_lists))
	{
		return false;
	}
	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('order_status !=', 'cancel');
	$ci->db->where_in('equipmentID', $duration_lists);
	$ci->db->where('uniqueID', $uniqueID);
	$query = $ci->db->get();
	return $query->row_array();
}


//get CNT
function get_duration_prn($organization_id,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('organization_id', $organization_id);
	$ci->db->where('medical_record_id', $medical_record_id);
	$ci->db->where('equipmentID', 79);
	$ci->db->or_where('equipmentID', 104);
	$ci->db->where('activity_typeid', 1);
	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

//get equipment name
function get_equipment_name($equipmentID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_equipment');
	$ci->db->where('equipmentID', $equipmentID);
	$query = $ci->db->get();
	// echo $ci->db->last_query();
	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}

function get_equipment_serial_num($uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('uniqueID_reference', $uniqueID);
	$query = $ci->db->get();
	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}
//radio or options
//requires: parentID,optionID
function get_equipment_choices($parentID=0,$optionID=0,$type="radio")
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_equipment');
	$ci->db->where('parentID', $parentID);
	$ci->db->where('optionID', $optionID);
	$ci->db->where('input_type', $type);
	$query = $ci->db->get();
	// echo $ci->db->last_query();
	return $query->result_array();
}

function get_chosen_hospice($hospice_selected)
{
	$ci =& get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_hospice');
	$ci->db->where('hospiceID', $hospice_selected);
	$query = $ci->db->get();
	return $query->first_row('array');
}

//get IPAP
function get_ipap($organization_id,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('organization_id', $organization_id);
	$ci->db->where('medical_record_id', $medical_record_id);
	$ci->db->where('equipmentID', 109);
	$ci->db->where('activity_typeid', 1);
	$ci->db->where('order_status !=', 'cancel');
	$query = $ci->db->get();

	return $query->row_array();
}

//get EPAP
function get_epap($organization_id,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('organization_id', $organization_id);
	$ci->db->where('medical_record_id', $medical_record_id);
	$ci->db->where('equipmentID', 110);
	$ci->db->where('activity_typeid', 1);
	$ci->db->where('order_status !=', 'cancel');
	$query = $ci->db->get();

	return $query->row_array();
}

//get RATE
function get_rate($organization_id,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('organization_id', $organization_id);
	$ci->db->where('medical_record_id', $medical_record_id);
	$ci->db->where('equipmentID', 111);
	$ci->db->where('activity_typeid', 1);
	$ci->db->where('order_status !=', 'cancel');
	$query = $ci->db->get();

	return $query->row_array();
}

//get CIPAP
function get_cipap($organization_id,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('organization_id', $organization_id);
	$ci->db->where('medical_record_id', $medical_record_id);
	$ci->db->where('equipmentID', 114);
	$ci->db->where('activity_typeid', 1);
	$ci->db->where('order_status !=', 'cancel');
	$query = $ci->db->get();

	return $query->row_array();
}

//get O2LR
function get_o2lr($organization_id,$medical_record_id)
{
	$ci =& get_instance();

	$ci->load->database();
	$ci->db->select('o.*,e.parentID');
	$ci->db->from('dme_order as o');
	$ci->db->join('dme_equipment as e','o.equipmentID = e.equipmentID',"left");
	$ci->db->where('o.organization_id', $organization_id);
	$ci->db->where('o.medical_record_id', $medical_record_id);
	$ci->db->where('o.activity_typeid', 1);
	$ci->db->where('o.order_status !=', 'cancel');
	$ci->db->where_in('o.equipmentID',array(77,100,191,189,188,190,240,201));

	$ci->db->order_by("date_ordered","DESC");
	$ci->db->limit(1);
	$query = $ci->db->get();
	foreach($query->result_array() as $row)
	{
		$data = $row;
	}

	return $data;
}


//get the subequipment
function get_oxygen_subequipment($patientID, $uniqueID, $equip_id)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('uniqueID', $uniqueID);
	$ci->db->where('patientID', $patientID);
	$ci->db->where('equipmentID', $equip_id);

	$query = $ci->db->get();

	foreach($query->result_array() as $row)
	{
		$data = array(
			"equipment_value" => $row['equipment_value']
		);
	}

	return $data;
}

function get_value_of_liter_flow($equipmentID,$uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('equipment_value');
	$ci->db->from('dme_order');
	$ci->db->where('equipmentID', $equipmentID);
	$ci->db->where('uniqueID', $uniqueID);

	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_quantity_of_floor_mat($equipmentID,$uniqueID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('equipment_value');
	$ci->db->from('dme_order');
	$ci->db->where('equipmentID', $equipmentID);
	$ci->db->where('uniqueID', $uniqueID);

	$query = $ci->db->get()->first_row('array');

	return $query;
}

//get subequipments for order details in patient order summary
function get_subequipment_id($equipmentID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('equipmentID, key_desc, key_name');
	$ci->db->from('dme_equipment');
	$ci->db->where('parentID', $equipmentID);

	$query = $ci->db->get()->result_array();

	return $query;
}

//get subequipments for order details in patient order summary
function get_subequipment_id_v2($equipmentID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('equipmentID, key_desc, key_name, equipment_company_item_no');
	$ci->db->from('dme_equipment');
	$ci->db->where('parentID', $equipmentID);

	$query = $ci->db->get()->result_array();

	return $query;
}

function get_item_warehouse_location($account_location,$company_item_no)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('item_warehouse_location');
	$ci->db->from('dme_item_warehouse_location');
	$ci->db->where('item_location', $account_location);
	$ci->db->where('company_item_no', $company_item_no);
	$query = $ci->db->get()->result_array();

	return $query;
}

function get_oxygen_concentrator_sub_equipment($uniqueID,$organization_id,$equipmentID)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('dme_order.equipmentID,equipment_value,dme_order.orderID');
	$ci->db->from('dme_order');
	$ci->db->join('dme_equipment','dme_order.equipmentID = dme_equipment.equipmentID');
	$ci->db->where('dme_order.uniqueID', $uniqueID);
	$ci->db->where('dme_equipment.parentID', $equipmentID);
	$ci->db->where('dme_order.organization_id', $organization_id);
	$query = $ci->db->get()->result_array();

	return $query;
}

//compare the ids f true in order table
function get_equal_subequipment_order($equipmentID, $uniqueID)
{
	$ci =& get_instance();


	$ci->load->database();

	$ci->db->select('equipmentID');
	$ci->db->from('dme_order');
	$ci->db->where('uniqueID', $uniqueID);
	$ci->db->where('equipmentID', $equipmentID);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

//get equipment
function get_oxygen_equipment($order_oxygen)
{
	$ci =& get_instance();

	$ci->load->database();

	$ci->db->select('equipment_value');
	$ci->db->from('dme_order');
	$ci->db->where('orderID', $order_oxygen);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

//count the # of address in ptmove
function count_ptmove($patientID)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('*');
	$ci->db->from('dme_patient_address');
	$ci->db->where('patient_id', $patientID);
	$ci->db->where('type', 1);
	$query = $ci->db->get()->result_array();

	return $query;

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

function get_patient_id($mr_id)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('patientID');
	$ci->db->from('dme_patient as patient');
	$ci->db->where('patient.medical_record_id', $mr_id);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_patient_id_v2($mr_id,$hospice_id)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('patientID');
	$ci->db->from('dme_patient as patient');
	$ci->db->where('patient.medical_record_id', $mr_id);
	$ci->db->where('patient.ordered_by', $hospice_id);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_patient_move_addresses($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient_address as address');
	$ci->db->where('address.patient_id', $patientID);
	$ci->db->where('address.type', 1);
	$query = $ci->db->get()->result_array();

	return $query;
}

function get_respite_addresses($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient_address as address');
	$ci->db->where('address.patient_id', $patientID);
	$ci->db->where('address.type', 2);
	$query = $ci->db->get()->result_array();

	return $query;
}

function check_if_address_picked_up($addressID,$patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

	$ci->db->where('orders.patientID', $patientID);
	$ci->db->where('orders.addressID', $addressID);
    $ci->db->where('equipments.parentID', 0);
    $ci->db->where('orders.canceled_from_confirming !=', 1);
	$ci->db->where('orders.serial_num !=',"item_options_only");
	$ci->db->where('orders.serial_num !=', "pickup_order_only");
	$ci->db->where('equipments.categoryID !=', 3);
	$ci->db->where('orders.canceled_order !=', 1);
	$ci->db->where('orders.activity_typeid !=', 2);

	$query = $ci->db->get()->result_array();

	return $query;
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

function get_hospice_address($hospice_id)
{
	$ci =& get_instance();
	$ci->load->database();
	$ci->load->model('hospice_model');

	$results = $ci->hospice_model->get_address($hospice_id);

	foreach($results as $result)
	{
		$array = array(
			'hospice_address' => $result['hospice_address']
		);
	}
	return $array['hospice_address'];
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

function get_equipment_location($addressID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient_address as pat');
	$ci->db->where('pat.id', $addressID);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_latest_patient_move_info($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_sub_ptmove as pat');
	$ci->db->where('pat.patientID', $patientID);
	$ci->db->order_by('pat.ptmoveID',"DESC");
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_patient_first_order($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->where('orders.patientID', $patientID);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_ptmove_address_first_order($patientID,$addressID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->where('orders.patientID', $patientID);
	$ci->db->where('orders.addressID', $addressID);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_respite_address_first_order($patientID,$addressID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->where('orders.patientID', $patientID);
	$ci->db->where('orders.addressID', $addressID);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_order_first_row($medical_id,$uniqueID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->where('orders.medical_record_id', $medical_id);
	$ci->db->where('orders.uniqueID', $uniqueID);
	$ci->db->order_by("orderID","ASC");
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_latest_pickup_all($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->join('dme_pickup_tbl AS pickup','pickup.order_uniqueID = orders.uniqueID','left');
	$ci->db->where('orders.patientID', $patientID);
	$ci->db->where('orders.original_activity_typeid', 2);
	$ci->db->where('pickup.pickup_sub !=', "not needed");
	$ci->db->order_by("orderID","ASC");
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_first_order_after_deactivation($patientID,$orderID,$uniqueID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->where('orders.patientID', $patientID);
	$ci->db->where('orders.orderID > ', $orderID);
	$ci->db->where('orders.uniqueID != ', $uniqueID);
	$ci->db->where('orders.original_activity_typeid', 1);
	$ci->db->order_by("orderID","ASC");
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_first_order_after_deactivation_p2($patientID,$orderID,$uniqueID,$pickup_date)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->where('orders.patientID', $patientID);
	$ci->db->where('orders.orderID > ', $orderID);
	$ci->db->where("DATE_FORMAT(orders.date_ordered,'%Y-%m-%d') > ", $pickup_date);
	$ci->db->where('orders.uniqueID != ', $uniqueID);
	$ci->db->where('orders.original_activity_typeid', 1);
	$ci->db->order_by("orderID","ASC");
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_all_patient_pickup($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->join('dme_pickup_tbl AS pickup','pickup.order_uniqueID = orders.uniqueID','left');
	$ci->db->where('orders.patientID', $patientID);
	$ci->db->where('orders.original_activity_typeid', 2);
	$ci->db->where('orders.canceled_order', 0);
	$ci->db->where('orders.order_status !=', "cancel");
	$ci->db->group_by('orders.uniqueID');
	$query = $ci->db->get()->result_array();

	return $query;
}

function check_patient_deactivation_first_sub1($orderID,$patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('equipments.*, orders.*');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

	$ci->db->where('orders.orderID <', $orderID);
    $ci->db->where('orders.patientID', $patientID);

    $ci->db->where('equipments.parentID', 0);
    $ci->db->where('orders.canceled_from_confirming !=', 1);
	$ci->db->where('orders.serial_num !=',"item_options_only");
	$ci->db->where('orders.serial_num !=', "pickup_order_only");
	$ci->db->where('equipments.categoryID !=', 3);
	$ci->db->where('orders.canceled_order !=', 1);
	$ci->db->where('orders.activity_typeid !=', 2);

	$query = $ci->db->get();
	return $query->result_array();
}

function check_patient_deactivation_first_sub2($orderID,$patientID,$date_ordered)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('equipments.*, orders.*');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

	$ci->db->where('orders.orderID <', $orderID);
    $ci->db->where('orders.patientID', $patientID);

    $ci->db->where('equipments.parentID', 0);
    $ci->db->where('orders.canceled_from_confirming !=', 1);
	$ci->db->where('orders.serial_num !=',"item_options_only");
	$ci->db->where('orders.serial_num !=', "pickup_order_only");
	$ci->db->where('equipments.categoryID !=', 3);
	$ci->db->where('orders.canceled_order !=', 1);
	$ci->db->where('orders.date_ordered >', $date_ordered);

	$query = $ci->db->get();
	return $query->result_array();
}

function check_patient_deactivation_succeeding_sub1($orderID,$patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('equipments.*, orders.*');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

	$ci->db->where('orders.orderID <', $orderID);
	$ci->db->where('orders.patientID', $patientID);

    $ci->db->where('equipments.parentID', 0);
    $ci->db->where('orders.canceled_from_confirming !=', 1);
	$ci->db->where('orders.serial_num !=',"item_options_only");
	$ci->db->where('orders.serial_num !=', "pickup_order_only");
	$ci->db->where('equipments.categoryID !=', 3);
	$ci->db->where('orders.canceled_order !=', 1);
	$ci->db->where('orders.pickup_order ', 0);
	$ci->db->where_not_in('orders.activity_typeid', 2);

	$query = $ci->db->get();
	return $query->result_array();
}

function check_patient_deactivation_succeeding_sub2($orderID,$patientID,$date_ordered)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('equipments.*, orders.*');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

	$ci->db->where('orders.orderID <', $orderID);
	$ci->db->where('orders.patientID', $patientID);

    $ci->db->where('equipments.parentID', 0);
    $ci->db->where('orders.canceled_from_confirming !=', 1);
	$ci->db->where('orders.serial_num !=',"item_options_only");
	$ci->db->where('orders.serial_num !=', "pickup_order_only");
	$ci->db->where('equipments.categoryID !=', 3);
	$ci->db->where('orders.canceled_order !=', 1);
	$ci->db->where('orders.pickup_order ', 0);
	$ci->db->where('orders.date_ordered >', $date_ordered);
	$ci->db->order_by('orders.orderID',"ASC");

	$query = $ci->db->get();
	return $query->first_row('array');
}

function check_order_after_all_pickup($orderID, $uniqueID, $patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('equipments.*, orders.*');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

	$ci->db->where('orders.orderID >', $orderID);
	$ci->db->where('orders.patientID', $patientID);
	$ci->db->where('orders.uniqueID !=', $uniqueID);
    $ci->db->order_by('orders.orderID',"DESC");

	$query = $ci->db->get();
	return $query->first_row('array');
}

function check_order_after_all_pickup_v2($orderID, $uniqueID, $patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('equipments.*, orders.*');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

	$ci->db->where('orders.orderID >', $orderID);
	$ci->db->where('orders.patientID', $patientID);
	$ci->db->where('orders.uniqueID !=', $uniqueID);
    $ci->db->order_by('orders.orderID',"ASC");

	$query = $ci->db->get();
	return $query->first_row('array');
}

function get_patient_order_after_deactivation($current_orderID, $previous_orderID, $patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('equipments.*, orders.*');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

	$ci->db->where('orders.orderID >', $previous_orderID);
	$ci->db->where('orders.orderID <', $current_orderID);
	$ci->db->where('orders.patientID', $patientID);
    $ci->db->where('orders.original_activity_typeid !=', 2);
    $ci->db->order_by('orders.orderID',"ASC");

	$query = $ci->db->get();
	return $query->first_row('array');
}

function get_equipment_phone_number($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_sub_ptmove as pat');
	$ci->db->where('pat.patientID', $patientID);
	$ci->db->order_by('pat.ptmoveID',"DESC");
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_patient_pickup_order($patientID,$uniqueID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('patientID',$patientID);
	$ci->db->where('uniqueID',$uniqueID);
	$query = $ci->db->get()->result_array();

	return $query;
}

function get_hospice_type($hospiceID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('type');
	$ci->db->from('dme_hospice');
	$ci->db->where('hospiceID', $hospiceID);
	$query = $ci->db->get()->first_row('array');

	return $query;
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

function get_ptmove_address_through_address_name($patientID,$ptmove_street,$ptmove_city)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_sub_ptmove');
	$ci->db->where('patientID', $patientID);
	$ci->db->where('ptmove_street', $ptmove_street);
	$ci->db->where('ptmove_city', $ptmove_city);

	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_ptmove_address_through_address_name_v2($patientID,$street,$placenum,$city,$state,$postal_code)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_sub_ptmove');
	$ci->db->where('patientID', $patientID);
	$ci->db->where('ptmove_street', $street);
	$ci->db->where('ptmove_placenum', $placenum);
	$ci->db->where('ptmove_city', $city);
	$ci->db->where('ptmove_state', $state);
	$ci->db->where('ptmove_postal', $postal_code);

	$query = $ci->db->get()->first_row('array');

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

function get_old_address_new($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient_address');
	$ci->db->where('patient_id',$patientID);
	$ci->db->where('type',0);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

//count teh old address returned
function get_count_old_address($patientID, $addressID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('patientID',$patientID);
	$ci->db->where('addressID',$addressID);
	$ci->db->where('order_status', 'confirmed');
	$ci->db->where('original_activity_typeid',1);
	$ci->db->where('activity_typeid',1);
	$query = $ci->db->get()->result_array();

	return $query;
}

//count the number of items in this address ptmove
function count_ptmove_address_item($patientID, $addressID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('patientID',$patientID);
	$ci->db->where('addressID',$addressID);
	$ci->db->where('order_status', 'confirmed');
	$ci->db->where('original_activity_typeid',4);
	//$ci->db->where('activity_typeid',4);
	$ci->db->where('equipmentID !=',93);
	$ci->db->where('equipmentID !=',98);
	$query = $ci->db->get()->result_array();

	return $query;
}

//count the number of items in this address respite
function count_respite_address_item($patientID, $addressID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('patientID',$patientID);
	$ci->db->where('addressID',$addressID);
	$ci->db->where('order_status', 'confirmed');
	$ci->db->where('original_activity_typeid',5);
	//$ci->db->where('activity_typeid',5);
	$ci->db->where('equipmentID !=',93);
	$ci->db->where('equipmentID !=',98);
	$query = $ci->db->get()->result_array();

	return $query;
}

function check_ptmove_address_pickup($addressID,$patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where(array('patientID' => $patientID, 'pickup_order' => 1));
	$ci->db->where('order_status !=',"cancel");
	$ci->db->where('order_status !=',"confirmed");
	$ci->db->where('serial_num',"pickup_order_only");

	$query = $ci->db->get()->result_array();

	return $query;
}

function get_patient_move_address_new($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient_address');
	$ci->db->where(array('patient_id' => $patientID, 'type' => 1,'status' => 1));

	$query = $ci->db->get()->result_array();

	return $query;
}

function get_patient_move_address_new_v2($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient_address');
	$ci->db->where(array('patient_id' => $patientID, 'type' => 1));

	$query = $ci->db->get()->result_array();

	return $query;
}

function get_respite_address_new($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient_address');
	$ci->db->where(array('patient_id' => $patientID, 'type' => 2, 'status' => 1));

	$query = $ci->db->get()->result_array();

	return $query;
}

function get_respite_address_new_v2($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient_address');
	$ci->db->where(array('patient_id' => $patientID, 'type' => 2));

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

	// $ptmove_details = get_ptmove_address_v2($info['medical_record_id'], $info['uniqueID']);

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_sub_ptmove as pt','pat.patientID=pt.patientID');
	$ci->db->where('pat.patientID', $patientID);
	$ci->db->order_by('pt.ptmoveID','DESC');
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_new_patient_residence_v2($patientID,$uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_sub_ptmove as pt','pat.patientID=pt.patientID');
	$ci->db->where('pat.patientID', $patientID);
	$ci->db->where('pt.order_uniqueID !=', $uniqueID);
	$ci->db->order_by('pt.ptmoveID','DESC');
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_new_patient_residence_v3($patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_sub_ptmove as pt','pat.patientID=pt.patientID');
	$ci->db->where('pat.patientID', $patientID);
	$ci->db->order_by('pt.ptmoveID','DESC');
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function check_if_ptmove_confirmed($order_uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->where('orders.uniqueID', $order_uniqueID);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_patient_info($patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as patient');
	$ci->db->join('dme_order as orders','patient.patientID=orders.patientID');
	$ci->db->where('patient.patientID', $patientID);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_patient_info_v2($patientID,$uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as patient');
	$ci->db->join('dme_order as orders','patient.patientID=orders.patientID');
	$ci->db->where('patient.patientID', $patientID);
	$ci->db->where('orders.uniqueID', $uniqueID);
	$query = $ci->db->get()->first_row('array');

	return $query;
}

function get_dme_admin_list()
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_user as user');
	$ci->db->where('user.account_type', "dme_admin");
	$query = $ci->db->get()->result_array();

	return $query;
}

function get_dme_admin_list_v2($user_location)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_user as user');
	$ci->db->where('user.account_type', "dme_admin");
	$ci->db->where('user.user_location', $user_location);
	$query = $ci->db->get()->result_array();

	return $query;
}

function get_users_list($hospiceID="")
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_user as user');

	if(!empty($hospiceID))
	{
		$ci->db->where('user.group_id', $hospiceID);
	}
	$ci->db->order_by('user.group_id', "ASC");
	$query = $ci->db->get()->result_array();

	return $query;
}

function get_users_list_v2($hospiceID="",$user_location)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_user as user');

	if(!empty($hospiceID))
	{
		$ci->db->where('user.group_id', $hospiceID);
	}
	$ci->db->where('user.user_location', $user_location);

	$ci->db->order_by('user.group_id', "ASC");
	$query = $ci->db->get()->result_array();

	return $query;
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
	$ci->db->join('dme_sub_ptmove as pt','pat.medical_record_id = pt.medical_record_id');
	$ci->db->join('dme_order as orders','pt.medical_record_id = orders.medical_record_id');
	$ci->db->join('dme_patient_address as pat_add','orders.addressID = pat_add.id');
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
			"ptmove_uniqueID"	=> $row['order_uniqueID'],
			"pt_move_status"	=> $row['status']
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

function get_ptmove_address_v2($medical_id, $uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_patient as pat');
	$ci->db->join('dme_sub_ptmove as pt','pat.medical_record_id = pt.medical_record_id');
	$ci->db->join('dme_order as orders','pt.medical_record_id = orders.medical_record_id');
	$ci->db->join('dme_patient_address as pat_add','orders.addressID = pat_add.id');
	$ci->db->where('pat.medical_record_id', $medical_id);
	$ci->db->where('orders.uniqueID', $uniqueID);
	$ci->db->where('orders.order_status', "confirmed");
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
			"ptmove_uniqueID"	=> $row['order_uniqueID'],
			"pt_move_status"	=> $row['status']
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

function get_ptmove_address_v3($medical_id,$uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->join('dme_patient_address as pat_add','orders.addressID = pat_add.id');
	$ci->db->where('orders.medical_record_id', $medical_id);
	$ci->db->where('orders.uniqueID !=', $uniqueID);
	$ci->db->where('pat_add.type', 1);
	$ci->db->order_by('pat_add.id', "DESC");

	$query = $ci->db->get()->first_row('array');
	return $query;
}

function get_ptmove_details($uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_sub_ptmove as ptmove');
	$ci->db->where('ptmove.order_uniqueID', $uniqueID);

	$query = $ci->db->get()->first_row('array');
	return $query;
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

function get_address_type($delivery_address_id)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('type');
	$ci->db->from('dme_patient_address');
	$ci->db->where('id', $delivery_address_id);

	$result = $ci->db->get()->first_row('array');
	return $result;
}

function get_ptmove_addresses_ID($patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('id');
	$ci->db->from('dme_patient_address');
	$ci->db->where('patient_id', $patientID);
	$ci->db->where('type', 1);

	$result = $ci->db->get()->result_array();
	return $result;
}

function get_ptmove_addresses_ID_v2($patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->distinct('address.id');
	$ci->db->select('address.id');
	$ci->db->from('dme_patient_address as address');
	$ci->db->join('dme_order as orders','orders.addressID = address.id');
	$ci->db->where('address.patient_id', $patientID);
	$ci->db->where('orders.order_status !=', 'cancel');
	$ci->db->where('address.type', 1);

	$result = $ci->db->get()->result_array();
	return $result;
}

function get_respite_addresses_ID($patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('id');
	$ci->db->from('dme_patient_address');
	$ci->db->where('patient_id', $patientID);
	$ci->db->where('type', 2);

	$result = $ci->db->get()->result_array();
	return $result;
}

function get_respite_addresses_ID_v2($patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->distinct('address.id');
	$ci->db->select('address.id');
	$ci->db->from('dme_patient_address as address');
	$ci->db->join('dme_order as orders','orders.addressID = address.id');
	$ci->db->where('address.patient_id', $patientID);
	$ci->db->where('orders.order_status !=', 'cancel');
	$ci->db->where('address.type', 2);

	$result = $ci->db->get()->result_array();
	return $result;
}

function get_latest_respite_phone($medical_record_id,$uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('respite_phone_number');
	$ci->db->from('dme_sub_respite');
	$ci->db->where('medical_record_id', $medical_record_id);
	$ci->db->where('order_uniqueID', $uniqueID);

	$result = $ci->db->get()->first_row('array');
	return $result;
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

function get_patient_entry_date($patientID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('date_created');
	$ci->db->from('dme_patient');
	$ci->db->where('patientID', $patientID);

	$result = $ci->db->get()->first_row('array');
	return $result;
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
		$ci->db->where('pickedup_uniqueID', $uniqueID);
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

function get_pickup_order_specific_equipment($medical_id,$uniqueID,$summary_pickup_date,$key)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order');
	$ci->db->where('medical_record_id', $medical_id);
	$ci->db->where('uniqueID', $uniqueID);
	$ci->db->where('summary_pickup_date', $summary_pickup_date);
	$ci->db->where('equipmentID', $key);

	$result = $ci->db->get();

	return $result->result_array();
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

//get the name of the person who putted the notes in patient notes
function get_noted_by_order_note($commentID,$userID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('*');
	$ci->db->from('dme_order_comments as comments');
	$ci->db->join('dme_user as users','comments.userID=users.userID','left');
	$ci->db->where('comments.commentID', $commentID);
	$ci->db->where('comments.userID', $userID);

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

function get_item_parent_by_workorder($equipmentID, $uniqueID)
{
	$ci = get_instance();

	$ci->load->database();

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
	$ci->db->where('equip.equipmentID', $equipmentID);

	$result = $ci->db->get();

	return $result->first_row('array');
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

function get_patientID($id,$hospice_id)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('patientID');
	$ci->db->from('dme_order');
	$ci->db->where('medical_record_id', $id);
	$ci->db->where('organization_id', $hospice_id);
	$query = $ci->db->get();

	return $query->first_row('array');
}

function get_patient_addresses($patientID)
{
	$ci = get_instance();

	$ci->load->database();

	$ci->db->select('id');
	$ci->db->from('dme_patient_address');
	$ci->db->where('patient_id', $patientID);
	$ci->db->where('type !=', 0);
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

function get_noncapped_quantity_v2($equipmentID, $uniqueID, $passed_count) //remove uniqueID if it will cause errors
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('*');
	$ci->db->from('dme_order as orders');
	$ci->db->join('dme_equipment as equipments','orders.equipmentID=equipments.equipmentID','left');
	$ci->db->where('orders.equipmentID', $equipmentID);
	$ci->db->where('orders.uniqueID', $uniqueID);
	$ci->db->order_by("orders.orderID","ASC");

	$query = $ci->db->get();

	if($query->result_array() == null)
	{
		$array = "";
		return $array;
	}

	else
	{
		// foreach($query->result_array() as $row)
		// {
		// 	$array = array(
		// 		'equipmentID' => $row['equipmentID'],
		// 		'equipment_value' => $row['equipment_value'],
		// 		'key_desc'		=> $row['key_desc']
		// 	);
		// }

		$count = 1;
		foreach($query->result_array() as $row)
		{
			if($count == $passed_count)
			{
				$array = array(
					// 'equipmentID' => $row['equipmentID'],
					'equipment_value' => $row['equipment_value']
					// 'key_desc'		=> $row['key_desc']
				);
				$count++;
			}
			else
			{
				$count++;
			}
		}
		return $array['equipment_value'];
	}
}

//to get the quantity of noncapped items with options
function get_capped_quantity($equipmentID, $uniqueID) //remove uniqueID if it will cause errors
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
	$CI->db->where('dme_hospice.type', 0);
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

function get_hospices_v2($user_location)
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('*');
	$CI->db->from('dme_hospice');
	$CI->db->where('dme_hospice.account_location', $user_location);
	$CI->db->where('dme_hospice.type', 0);
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

function get_new_patient_list($current_date,$from,$to,$hospiceID)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("pat.*,orders.*");
    $CI->db->from("dme_patient as pat");
    $CI->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
    $CI->db->join("dme_order as orders","pat_address.id=orders.addressID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("pat_address.type",0);
    $CI->db->where("orders.original_activity_typeid",1);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
		$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_new_patient_list_v3($current_date,$from,$to,$hospiceID,$account_location)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("pat.*,orders.*");
	$CI->db->from("dme_hospice as hospice");
	$CI->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
    $CI->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
    $CI->db->join("dme_order as orders","pat_address.id=orders.addressID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("pat_address.type",0);
    $CI->db->where("orders.original_activity_typeid",1);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
		$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }
    $CI->db->where("hospice.account_location", $account_location);

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_new_patient_list_v2($current_date,$from,$to,$hospiceID)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("pat.*,orders.*");
    $CI->db->from("dme_patient as pat");
    $CI->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
    $CI->db->join("dme_order as orders","pat_address.id=orders.addressID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("pat_address.type",0);
    $CI->db->where("orders.original_activity_typeid",1);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');

		if($from <= $to)
		{
			// if(($from == $current_day && $to == $current_day) || ($from == $current_day && $to > $from))
			// {
	  //           $CI->db->where("orders.order_status", "active");
			// }
			if($to == $current_day || $to > $current_day)
			{
				$start = date("Y-m-d",strtotime($from." -1 days"));
				$end = date("Y-m-d",strtotime($current_day));
				$final_start = strtotime($start." 23:59:59");
				$final_end = strtotime($end." 00:00:00");

				$CI->db->where("orders.uniqueID > ",$final_start);
				$CI->db->where("orders.uniqueID < ",$final_end);
			}
			else if($to < $current_day)
			{
				$start = date("Y-m-d",strtotime($from." -1 days"));
				$end = date("Y-m-d",strtotime($to." +1 days"));
				$final_start = strtotime($start." 23:59:59");
				$final_end = strtotime($end." 00:00:00");

				$CI->db->where("orders.uniqueID > ",$final_start);
				$CI->db->where("orders.uniqueID < ",$final_end);
			}
		}
		else
		{
			$start = date("Y-m-d",strtotime($from." -1 days"));
			$end = date("Y-m-d",strtotime($to." +1 days"));
			$final_start = strtotime($start." 23:59:59");
			$final_end = strtotime($end." 00:00:00");

			$CI->db->where("orders.uniqueID > ",$final_start);
			$CI->db->where("orders.uniqueID < ",$final_end);
		}
    }

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_new_patient_list_v4($current_date,$from,$to,$hospiceID,$account_location)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("pat.*,orders.*");
	$CI->db->from("dme_hospice as hospice");
    $CI->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
    $CI->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
    $CI->db->join("dme_order as orders","pat_address.id=orders.addressID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("pat_address.type",0);
    $CI->db->where("orders.original_activity_typeid",1);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');

		if($from <= $to)
		{
			if($to == $current_day || $to > $current_day)
			{
				$start = date("Y-m-d",strtotime($from." -1 days"));
				$end = date("Y-m-d",strtotime($current_day));
				$final_start = strtotime($start." 23:59:59");
				$final_end = strtotime($end." 00:00:00");

				$CI->db->where("orders.uniqueID > ",$final_start);
				$CI->db->where("orders.uniqueID < ",$final_end);
			}
			else if($to < $current_day)
			{
				$start = date("Y-m-d",strtotime($from." -1 days"));
				$end = date("Y-m-d",strtotime($to." +1 days"));
				$final_start = strtotime($start." 23:59:59");
				$final_end = strtotime($end." 00:00:00");

				$CI->db->where("orders.uniqueID > ",$final_start);
				$CI->db->where("orders.uniqueID < ",$final_end);
			}
		}
		else
		{
			$start = date("Y-m-d",strtotime($from." -1 days"));
			$end = date("Y-m-d",strtotime($to." +1 days"));
			$final_start = strtotime($start." 23:59:59");
			$final_end = strtotime($end." 00:00:00");

			$CI->db->where("orders.uniqueID > ",$final_start);
			$CI->db->where("orders.uniqueID < ",$final_end);
		}
    }
    $CI->db->where("hospice.account_location", $account_location);

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_user_orders_made_today($userID,$current_date)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("pat.*,orders.*,act.*");
    $CI->db->from("dme_patient as pat");
    $CI->db->join("dme_order as orders","pat.patientID=orders.patientID");
    $CI->db->join("dme_activity_type as act","act.activity_id=orders.original_activity_typeid");

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("orders.ordered_by",$userID);

    if(!empty($current_date))
    {
    	$CI->db->where("DATE_FORMAT(orders.date_ordered,'%Y-%m-%d')",$current_date);
    }

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_ptmove_list($current_date,$from,$to,$hospiceID)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("pat.*,orders.*");
    $CI->db->from("dme_patient as pat");
    $CI->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
    $CI->db->join("dme_order as orders","pat_address.id=orders.addressID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("pat_address.type",1);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
		$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_ptmove_list_v2($current_date,$from,$to,$hospiceID,$account_location)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("pat.*,orders.*");
	$CI->db->from("dme_hospice as hospice");
    $CI->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
    $CI->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
    $CI->db->join("dme_order as orders","pat_address.id=orders.addressID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("pat_address.type",1);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
		$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }
    $CI->db->where("hospice.account_location", $account_location);

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_respite_list($current_date,$from,$to,$hospiceID)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("pat.*,orders.*");
    $CI->db->from("dme_patient as pat");
    $CI->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
    $CI->db->join("dme_order as orders","pat_address.id=orders.addressID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("pat_address.type",2);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
		$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_respite_list_v2($current_date,$from,$to,$hospiceID,$account_location)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("pat.*,orders.*");
	$CI->db->from("dme_hospice as hospice");
    $CI->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
    $CI->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
    $CI->db->join("dme_order as orders","pat_address.id=orders.addressID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("pat_address.type",2);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
		$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }
    $CI->db->where("hospice.account_location", $account_location);

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_patient_new_item_list($current_date,$from,$to,$hospiceID)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

    $CI->db->select("orders.*,equip.*,pat.p_fname,pat.p_lname");
    $CI->db->from("dme_patient as pat");
    $CI->db->join("dme_order as orders","pat.patientID=orders.patientID");
    $CI->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    $CI->db->where("orders.organization_id != ", 13);
    $CI->db->where("orders.original_activity_typeid", 1);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
    	$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }

    $CI->db->order_by('orders.orderID','DESC');
    $CI->db->stop_cache();
    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_patient_new_item_list_v2($current_date,$from,$to,$hospiceID,$account_location)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

    $CI->db->select("orders.*,equip.*,pat.p_fname,pat.p_lname");

    $CI->db->from("dme_hospice as hospice");
    $CI->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
    $CI->db->join("dme_order as orders","pat.patientID=orders.patientID");
    $CI->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    $CI->db->where("orders.organization_id != ", 13);
    $CI->db->where("orders.original_activity_typeid", 1);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
    	$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }
    $CI->db->where("hospice.account_location", $account_location);

    $CI->db->order_by('orders.orderID','DESC');
    $CI->db->stop_cache();
    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_patient_pickup_list($current_date,$from,$to,$hospiceID)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("*");
    $CI->db->from("dme_patient as pat");
    $CI->db->join("dme_order as orders","pat.patientID=orders.patientID");
    $CI->db->join("dme_pickup_tbl as pickup","orders.uniqueID=pickup.order_uniqueID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    $CI->db->where("orders.organization_id != ", 13);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
		$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_patient_pickup_list_v2($current_date,$from,$to,$hospiceID,$account_location)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("*");
	$CI->db->from("dme_hospice as hospice");
    $CI->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
    $CI->db->join("dme_order as orders","pat.patientID=orders.patientID");
    $CI->db->join("dme_pickup_tbl as pickup","orders.uniqueID=pickup.order_uniqueID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    $CI->db->where("orders.organization_id != ", 13);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
		$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }
    $CI->db->where("hospice.account_location", $account_location);

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_patient_exchange_list($current_date,$from,$to,$hospiceID)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("orders.*,equip.*, pat.p_fname, pat.p_lname");
    $CI->db->from("dme_patient as pat");
    $CI->db->join("dme_order as orders","pat.patientID=orders.patientID");
    $CI->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    $CI->db->where("orders.organization_id != ", 13);
    $CI->db->where("orders.original_activity_typeid",3);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
		$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }

    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();
    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_patient_exchange_list_v2($current_date,$from,$to,$hospiceID,$account_location)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("orders.*,equip.*, pat.p_fname, pat.p_lname");
	$CI->db->from("dme_hospice as hospice");
    $CI->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
    $CI->db->join("dme_order as orders","pat.patientID=orders.patientID");
    $CI->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    $CI->db->where("orders.organization_id != ", 13);
    $CI->db->where("orders.original_activity_typeid",3);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
		$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }
    $CI->db->where("hospice.account_location", $account_location);

    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();
    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_latest_patient_on_list()
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('*');
	$CI->db->from('dme_patient');
	$CI->db->order_by('patientID','DESC');
	$query = $CI->db->get()->first_row('array');

	return $query;
}

function get_latest_patient_on_list_v2($account_location)
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('*');
	$CI->db->from('dme_patient as patient');
	$CI->db->join('dme_hospice AS hospice', 'patient.ordered_by = hospice.hospiceID','left');
	$CI->db->where('hospice.account_location', $account_location);
	$CI->db->order_by('patient.patientID','DESC');
	$query = $CI->db->get()->first_row('array');

	return $query;
}

function get_first_patient_on_list()
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('*');
	$CI->db->from('dme_patient');
	$CI->db->order_by('patientID','ASC');
	$query = $CI->db->get()->first_row('array');

	return $query;
}

function list_active_patients_by_hospice_and_residence_v2($hospiceID,$residence_status_name)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type, orders.uniqueID');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$ci->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$ci->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$ci->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	$includes = array(1,2);

	if($ci->session->userdata('account_type') != 'dme_admin')
	{
		$ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
	}
	else if($hospiceID != 0)
	{
		$ci->db->where('orders.organization_id',$hospiceID);
	}

	//remove this two lines if it will cause errors -- newly added
	$ci->db->where('orders.organization_id !=', 13);
	$ci->db->where_not_in('orders.activity_typeid', 2);
	$ci->db->where('orders.serial_num !=', "item_options_only");
	$ci->db->where('orders.serial_num !=', "pickup_order_only");
	$ci->db->where('equipments.parentID', 0);
	$ci->db->where('orders.canceled_order !=', 1);
	$ci->db->where('equipments.categoryID !=', 3);
	//remove this two lines if it will cause errors -- newly added

	//Russel added codes. This is for filtering inactive patients.
	$ci->db->where('orders.pickup_order ', 0);
    $ci->db->where('equipments.categoryID !=', 3);
    $ci->db->where('orders.serial_num !=','item_options_only');
    $ci->db->where('orders.canceled_from_confirming !=', 1);
    $ci->db->where('orders.canceled_order !=', 1);
    $ci->db->where('orders.deliver_to_type', $residence_status_name);

	$ci->db->group_by('patient.medical_record_id');
	$ci->db->group_by('orders.organization_id');
	$ci->db->order_by('patient.p_lname', 'ASC');
	$query = $ci->db->get();

	return $query->result_array();
}

function list_active_patients_by_hospice_and_residence_v4($hospiceID,$residence_status_name,$account_location)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type, orders.uniqueID');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$ci->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$ci->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$ci->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	$includes = array(1,2);

	if($ci->session->userdata('account_type') != 'dme_admin')
	{
		$ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
	}
	else if($hospiceID != 0)
	{
		$ci->db->where('orders.organization_id',$hospiceID);
	}

	//remove this two lines if it will cause errors -- newly added
	$ci->db->where('orders.organization_id !=', 13);
	$ci->db->where_not_in('orders.activity_typeid', 2);
	$ci->db->where('orders.serial_num !=', "item_options_only");
	$ci->db->where('orders.serial_num !=', "pickup_order_only");
	$ci->db->where('equipments.parentID', 0);
	$ci->db->where('orders.canceled_order !=', 1);
	$ci->db->where('equipments.categoryID !=', 3);
	//remove this two lines if it will cause errors -- newly added

	//Russel added codes. This is for filtering inactive patients.
	$ci->db->where('orders.pickup_order ', 0);
    $ci->db->where('equipments.categoryID !=', 3);
    $ci->db->where('orders.serial_num !=','item_options_only');
    $ci->db->where('orders.canceled_from_confirming !=', 1);
    $ci->db->where('orders.canceled_order !=', 1);
    $ci->db->where('orders.deliver_to_type', $residence_status_name);
    $ci->db->where('hosp.account_location', $account_location);

	$ci->db->group_by('patient.medical_record_id');
	$ci->db->group_by('orders.organization_id');
	$ci->db->order_by('patient.p_lname', 'ASC');
	$query = $ci->db->get();

	return $query->result_array();
}

function list_patients_by_hospice($hospiceID)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type, orders.uniqueID');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$ci->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$ci->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	// $ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	// $ci->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	$includes = array(1,2);

	if($ci->session->userdata('account_type') != 'dme_admin')
	{
		$ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
	}
	else if($hospiceID != 0)
	{
		$ci->db->where('orders.organization_id',$hospiceID);
	}

	//remove this two lines if it will cause errors -- newly added
	$ci->db->where('orders.organization_id !=', 13);
	// $ci->db->where_not_in('orders.activity_typeid', 2);
	// $ci->db->where('orders.serial_num !=', "item_options_only");
	// $ci->db->where('orders.serial_num !=', "pickup_order_only");
	// $ci->db->where('equipments.parentID', 0);
	// $ci->db->where('orders.canceled_order !=', 1);
	// $ci->db->where('equipments.categoryID !=', 3);
	//remove this two lines if it will cause errors -- newly added

	//Russel added codes. This is for filtering inactive patients.
	// $ci->db->where('orders.pickup_order ', 0);
 //    $ci->db->where('equipments.categoryID !=', 3);
 //    $ci->db->where('orders.serial_num !=','item_options_only');
 //    $ci->db->where('orders.canceled_from_confirming !=', 1);
 //    $ci->db->where('orders.canceled_order !=', 1);

	$ci->db->group_by('patient.medical_record_id');
	$ci->db->group_by('orders.organization_id');
	$ci->db->order_by('patient.p_lname', 'ASC');
	$query = $ci->db->get();

	return $query->result_array();
}

function list_patients_by_hospice_v3($hospiceID,$account_location)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type, orders.uniqueID');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$ci->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$ci->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');

	$includes = array(1,2);

	if($ci->session->userdata('account_type') != 'dme_admin')
	{
		$ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
	}
	else if($hospiceID != 0)
	{
		$ci->db->where('orders.organization_id',$hospiceID);
	}

	//remove this two lines if it will cause errors -- newly added
	$ci->db->where('orders.organization_id !=', 13);
	$ci->db->where('hosp.account_location', $account_location);

	$ci->db->group_by('patient.medical_record_id');
	$ci->db->group_by('orders.organization_id');
	$ci->db->order_by('patient.p_lname', 'ASC');
	$query = $ci->db->get();

	return $query->result_array();
}

function list_patients_by_hospice_v2($border_bottom,$border_top,$hospiceID)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type, orders.uniqueID');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$ci->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$ci->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	// $ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	// $ci->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	$includes = array(1,2);

	if($ci->session->userdata('account_type') != 'dme_admin')
	{
		$ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
	}
	else if($hospiceID != 0)
	{
		$ci->db->where('orders.organization_id',$hospiceID);
	}

	//remove this two lines if it will cause errors -- newly added
	$ci->db->where('orders.organization_id !=', 13);
	// $ci->db->where_not_in('orders.activity_typeid', 2);
	// $ci->db->where('orders.serial_num !=', "item_options_only");
	// $ci->db->where('orders.serial_num !=', "pickup_order_only");
	// $ci->db->where('equipments.parentID', 0);
	// $ci->db->where('orders.canceled_order !=', 1);
	// $ci->db->where('equipments.categoryID !=', 3);
	//remove this two lines if it will cause errors -- newly added

	//Russel added codes. This is for filtering inactive patients.
	// $ci->db->where('orders.pickup_order ', 0);
 //    $ci->db->where('equipments.categoryID !=', 3);
 //    $ci->db->where('orders.serial_num !=','item_options_only');
 //    $ci->db->where('orders.canceled_from_confirming !=', 1);
 //    $ci->db->where('orders.canceled_order !=', 1);
    $ci->db->where('patient.patientID >', $border_bottom);
    if($border_top != "")
    {
    	$ci->db->where('patient.patientID <=', $border_top);
    }

	$ci->db->group_by('patient.medical_record_id');
	$ci->db->group_by('orders.organization_id');
	$ci->db->order_by('patient.p_lname', 'ASC');
	$query = $ci->db->get();

	return $query->result_array();
}

function list_active_patients_by_hospice_and_residence($border_bottom,$border_top,$hospiceID,$residence_status_name)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type, orders.uniqueID');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$ci->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$ci->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$ci->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	$includes = array(1,2);

	if($ci->session->userdata('account_type') != 'dme_admin')
	{
		$ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
	}
	else if($hospiceID != 0)
	{
		$ci->db->where('orders.organization_id',$hospiceID);
	}

	//remove this two lines if it will cause errors -- newly added
	$ci->db->where('orders.organization_id !=', 13);
	$ci->db->where_not_in('orders.activity_typeid', 2);
	$ci->db->where('orders.serial_num !=', "item_options_only");
	$ci->db->where('orders.serial_num !=', "pickup_order_only");
	$ci->db->where('equipments.parentID', 0);
	$ci->db->where('orders.canceled_order !=', 1);
	$ci->db->where('equipments.categoryID !=', 3);
	//remove this two lines if it will cause errors -- newly added

	//Russel added codes. This is for filtering inactive patients.
	$ci->db->where('orders.pickup_order ', 0);
    $ci->db->where('equipments.categoryID !=', 3);
    $ci->db->where('orders.serial_num !=','item_options_only');
    $ci->db->where('orders.canceled_from_confirming !=', 1);
    $ci->db->where('orders.canceled_order !=', 1);
    $ci->db->where('orders.deliver_to_type', $residence_status_name);
    $ci->db->where('patient.patientID >', $border_bottom);
    if($border_top != "")
    {
    	$ci->db->where('patient.patientID <=', $border_top);
    }

	$ci->db->group_by('patient.medical_record_id');
	$ci->db->group_by('orders.organization_id');
	$ci->db->order_by('patient.p_lname', 'ASC');
	$query = $ci->db->get();

	return $query->result_array();
}

function list_active_patients_by_hospice_and_residence_v3($border_bottom,$border_top,$hospiceID,$residence_status_name,$account_location)
{
	$ci = get_instance();

	$ci->load->database();
	$ci->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type, orders.uniqueID');
	$ci->db->from('dme_order AS orders');
	$ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$ci->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$ci->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$ci->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$ci->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	$includes = array(1,2);

	if($ci->session->userdata('account_type') != 'dme_admin')
	{
		$ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
	}
	else if($hospiceID != 0)
	{
		$ci->db->where('orders.organization_id',$hospiceID);
	}

	//remove this two lines if it will cause errors -- newly added
	$ci->db->where('orders.organization_id !=', 13);
	$ci->db->where_not_in('orders.activity_typeid', 2);
	$ci->db->where('orders.serial_num !=', "item_options_only");
	$ci->db->where('orders.serial_num !=', "pickup_order_only");
	$ci->db->where('equipments.parentID', 0);
	$ci->db->where('orders.canceled_order !=', 1);
	$ci->db->where('equipments.categoryID !=', 3);
	//remove this two lines if it will cause errors -- newly added

	//Russel added codes. This is for filtering inactive patients.
	$ci->db->where('orders.pickup_order ', 0);
    $ci->db->where('equipments.categoryID !=', 3);
    $ci->db->where('orders.serial_num !=','item_options_only');
    $ci->db->where('orders.canceled_from_confirming !=', 1);
    $ci->db->where('orders.canceled_order !=', 1);
    $ci->db->where('orders.deliver_to_type', $residence_status_name);
    $ci->db->where('patient.patientID >', $border_bottom);
    if($border_top != "")
    {
    	$ci->db->where('patient.patientID <=', $border_top);
    }
    $ci->db->where('hosp.account_location', $account_location);

	$ci->db->group_by('patient.medical_record_id');
	$ci->db->group_by('orders.organization_id');
	$ci->db->order_by('patient.p_lname', 'ASC');
	$query = $ci->db->get();

	return $query->result_array();
}

function get_new_patient_residence_list($current_date,$from,$to,$hospiceID)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select("pat.*,orders.*");
    $CI->db->from("dme_patient as pat");
    $CI->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
    $CI->db->join("dme_order as orders","pat_address.id=orders.addressID");

    if(!empty($hospiceID))
    {
    	$CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("pat_address.type",0);
    $CI->db->where("orders.original_activity_typeid",1);

    if(!empty($current_date))
    {
    	$CI->db->where("orders.order_status", "active");
    }
    if(!empty($from) && !empty($to))
    {
    	$current_day = date('Y-m-d');
		$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
		if($to == $current_day || $to > $current_day)
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') < ",$current_day);
		}
		else
		{
			$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
		}
    }

    $CI->db->group_by("orders.uniqueID");
    $CI->db->order_by('pat.p_fname','ASC');
    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_patient_residence_list($residence_status_name,$current_date,$from,$to,$hospiceID)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type');
	$CI->db->from('dme_order AS orders');
	$CI->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$CI->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$CI->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$CI->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$CI->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$CI->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	//remove this two lines if it will cause errors -- newly added
	$CI->db->where_not_in('orders.activity_typeid', 2);

	if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    $CI->db->where("orders.organization_id != ", 13);

	if(!empty($residence_status_name))
	{
		$CI->db->where("orders.deliver_to_type", $residence_status_name);
	}

	$CI->db->where('orders.serial_num !=', "item_options_only");
	$CI->db->where('orders.serial_num !=', "pickup_order_only");
	$CI->db->where('equipments.parentID', 0);
	$CI->db->where('orders.canceled_order !=', 1);
	$CI->db->where('equipments.categoryID !=', 3);
	$CI->db->where('orders.pickup_order ', 0);
    $CI->db->where('orders.canceled_from_confirming !=', 1);

    if(!empty($current_date))
    {
    	$CI->db->where('orders.date_ordered <=', $current_date);
    	$CI->db->where_not_in('orders.order_status', "cancel");
    }
    if(!empty($from) && !empty($to))
    {
    	$CI->db->where('orders.actual_order_date <=', $from);
    }

    $CI->db->group_by('patient.medical_record_id');
	$CI->db->group_by('orders.organization_id');
	$CI->db->order_by('patient.p_lname', 'ASC');

    $CI->db->stop_cache();
    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

// function get_patient_residence_list($residence_status_name,$current_date,$from,$to,$hospiceID)
// {
// 	$CI =& get_instance();

// 	$CI->load->database();

// 	//using database caching
//     $CI->db->start_cache();

// 	$CI->db->select("*");
//     $CI->db->from("dme_patient as pat");
//     $CI->db->join("dme_order as orders","pat.patientID=orders.patientID");

//     if(!empty($hospiceID))
//     {
//     	$CI->db->where("orders.organization_id",$hospiceID);
//     }
//     else if($CI->session->userdata('account_type')!= "dme_admin")
//     {
//     	$hospice = $CI->session->userdata('group_id');
//         $CI->db->where("orders.organization_id",$hospice);
//     }

//     $CI->db->where("orders.organization_id != ", 13);

// 	if(!empty($residence_status_name))
// 	{
// 		$CI->db->where("orders.deliver_to_type", $residence_status_name);
// 	}

//     if(!empty($current_date))
//     {
//     	$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d')",$current_date);
//     }
//     if(!empty($from) && !empty($to))
//     {
//     	$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') >= ",$from);
//     	$CI->db->where("DATE_FORMAT(orders.actual_order_date,'%Y-%m-%d') <= ",$to);
//     }

//     $CI->db->group_by("orders.patientID");
//     $CI->db->order_by('pat.p_fname','ASC');
//     $CI->db->stop_cache();
//     $query = $CI->db->get()->result_array();

//     //flashing cache to avoid getting it in another query
//     $CI->db->flush_cache();

//     return $query;
// }

function get_companies()
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('*');
	$CI->db->from('dme_hospice');
	$CI->db->where('dme_hospice.type', 1);
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

function get_companies_v2($user_location)
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('*');
	$CI->db->from('dme_hospice');
	$CI->db->where('dme_hospice.type', 1);
	$CI->db->where('dme_hospice.account_location', $user_location);
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
    //$ci->db->where('orders.order_status', "pending");
   	$ci->db->group_by('orders.orderID');
    $ci->db->order_by('orders.pickup_date', 'DESC');

    $data = $ci->db->get()->result_array();

    return $data;
}

function get_customer_ordered_disposable_items($patientID)
{
	$ci = get_instance();
	$ci->load->database();

    $ci->db->select('orders.activity_typeid, orders.orderID');
    $ci->db->from('dme_order as orders');
    $ci->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
    $ci->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left');
    $ci->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left');

    $ci->db->where('patients.patientID', $patientID);
    $ci->db->where('orders.pickup_order ', 0);
    $ci->db->where('equip.categoryID', 3);
    $ci->db->where('orders.serial_num !=','item_options_only');
    $ci->db->where('orders.canceled_from_confirming !=', 1);
    $ci->db->where('orders.canceled_order !=', 1);
    //$ci->db->where('orders.order_status', "pending");
   	$ci->db->group_by('orders.orderID');
    $ci->db->order_by('orders.pickup_date', 'DESC');

    $data = $ci->db->get()->result_array();

    return $data;
}

function get_customer_ordered_capped_non_capped_items($patientID)
{
	$ci = get_instance();
	$ci->load->database();

    $ci->db->select('orders.activity_typeid, orders.orderID');
    $ci->db->from('dme_order as orders');
    $ci->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
    $ci->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left');
    $ci->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left');

    $ci->db->where('patients.patientID', $patientID);
    $ci->db->where('equip.categoryID !=', 3);
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
	$ci->db->where("orders.canceled_order !=", 1);
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

function get_count_status_new()
{
	$ci = get_instance();
	$ci->load->database();

	$response = "";
	$statuses = array('pending', 'active', 're-schedule', 'on-hold');
	$queryies = array();
	$where = "";
	if($ci->session->userdata('account_type') != 'dme_admin')
	{
		// $ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
		$where = " AND orders.organization_id = ".$ci->session->userdata('group_id');
	}

	foreach($statuses as $val)
	{
		$queryies[] = "SELECT count(*) as total, concat('','{$val}') as status
						FROM ( SELECT DISTINCT `stats`.`status_activity_typeid`, `stats`.`order_uniqueID` FROM `dme_order_status` as stats
						LEFT JOIN `dme_order` as orders ON `stats`.`order_uniqueID`=`orders`.`uniqueID`
						WHERE `orders`.`order_status`='{$val}' {$where}) as dt";
	}

	$main_query = implode(" UNION ",$queryies);

	$result = $ci->db->query($main_query)->result_array();

	return $result;
}

function get_count_status_new_v2($account_location)
{
	$ci = get_instance();
	$ci->load->database();

	$response = "";
	$statuses = array('pending', 'active', 're-schedule', 'on-hold');
	$queryies = array();
	$where = "";
	if($ci->session->userdata('account_type') != 'dme_admin')
	{
		$where = " AND orders.organization_id = ".$ci->session->userdata('group_id'). " AND hospice.account_location = ".$account_location;
	}
	else
	{
		$where = " AND hospice.account_location = ".$account_location;
	}

	foreach($statuses as $val)
	{
		$queryies[] = "SELECT count(*) as total, concat('','{$val}') as status
						FROM ( SELECT DISTINCT `stats`.`status_activity_typeid`, `stats`.`order_uniqueID` FROM `dme_order_status` as stats
						LEFT JOIN `dme_order` as orders ON `stats`.`order_uniqueID`=`orders`.`uniqueID`
						LEFT JOIN `dme_hospice` as hospice ON `orders`.`organization_id`=`hospice`.`hospiceID`
						WHERE `orders`.`order_status`='{$val}' {$where}) as dt";
	}

	$main_query = implode(" UNION ",$queryies);

	$result = $ci->db->query($main_query)->result_array();
	return $result;
}

function get_count_status($status="pending",$additional = array())
{
	$ci = get_instance();
	$ci->load->database();

	$response = "";
	$ci->db->select('stats.*,orders.addressID');

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

function get_count_status_v2($status="pending",$additional = array(),$account_location)
{
	$ci = get_instance();
	$ci->load->database();

	$response = "";
	$ci->db->select('stats.*,orders.addressID');

	$ci->db->from('dme_order_status as stats');
	$ci->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
	$ci->db->join('dme_hospice as hospice', 'orders.organization_id=hospice.hospiceID','left');

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
	$ci->db->where('hospice.account_location',$account_location);

	$query = $ci->db->get();
	return $query->num_rows();
}

function get_status($status="pending",$additional = array())
{
	$ci = get_instance();
	$ci->load->database();

	$response = "";
	$ci->db->select('stats.*,orders.addressID');

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
	return $query->result_array();
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

    $ci->db->select('pat_add.*');
    $ci->db->from('dme_order as orders');
    $ci->db->join('dme_patient_address as pat_add', 'pat_add.id=orders.addressID');
    $ci->db->where(array('orders.patientID' => $patientID,'orders.order_status' => 'confirmed'));
    $ci->db->where(array('orders.original_activity_typeid' => 4 , "orders.activity_typeid" => 4, 'pat_add.status' => 1));

    $data = $ci->db->get()->first_row('array');

    return $data;
}

function get_latest_patient_move_address($patientID)
{
	$ci = get_instance();
	$ci->load->database();

    $ci->db->select('*');
    $ci->db->from('dme_patient_address as pat');
    $ci->db->where(array('type' => 1, 'status' => 1, "patient_id" => $patientID));
    $ci->db->order_by('id',"DESC");

    $data = $ci->db->get()->first_row('array');

    return $data;
}

/* Added by Russel */
function get_patient_move_first_row_pos($patientID)
{
	$ci = get_instance();
	$ci->load->database();

    $ci->db->select('orderID');
    $ci->db->from('dme_order as orders');
    $ci->db->where(array('patientID' => $patientID));
    $ci->db->where(array('original_activity_typeid' => 4 , "activity_typeid" => 4));

    $data = $ci->db->get()->first_row('array');

    return $data;
}

function get_pickup_order_info($pickedup_uniqueID)
{
	$ci = get_instance();
	$ci->load->database();

    $ci->db->select('uniqueID,original_activity_typeid,activity_typeid');
    $ci->db->from('dme_order as orders');
    $ci->db->where(array('uniqueID' => $pickedup_uniqueID));

    $data = $ci->db->get()->first_row('array');

    return $data;
}

function get_exchange_order_info($uniqueID_reference)
{
	$ci = get_instance();
	$ci->load->database();

    $ci->db->select('uniqueID,original_activity_typeid,activity_typeid');
    $ci->db->from('dme_order as orders');
    $ci->db->where(array('uniqueID' => $uniqueID_reference));

    $data = $ci->db->get()->first_row('array');

    return $data;
}

// function get_exchange_order_info_v2($uniqueID_reference,$equipmentID)
// {
// 	$ci = get_instance();
// 	$ci->load->database();

//     $ci->db->select('uniqueID,original_activity_typeid,activity_typeid,summary_pickup_date');
//     $ci->db->from('dme_order as orders');
//     $ci->db->where(array('uniqueID_reference' => $uniqueID_reference, 'equipmentID'=> $equipmentID, 'activity_typeid' => 2, 'original_activity_typeid' => 2));
//     $ci->db->order_by('orderID',"DESC");

//     $data = $ci->db->get()->first_row('array');

//     return $data;
// }

function get_respite_order_info($uniqueID_reference,$equipmentID)
{
	$ci = get_instance();
	$ci->load->database();

    $ci->db->select('uniqueID,original_activity_typeid,activity_typeid,summary_pickup_date');
    $ci->db->from('dme_order as orders');
    $ci->db->where(array('uniqueID' => $uniqueID_reference, 'equipmentID'=> $equipmentID));
    $ci->db->where(array('original_activity_typeid' => 2 , "activity_typeid" => 2));
    $ci->db->order_by('orderID',"DESC");

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
						"hospiceID" => 0,
						"from" => "",
						"to"   => ""
					);
	$hospiceID 	= 0;
	$from 	= "";
	$to 	= "";
	if(isset($_GET['hospiceID']) AND $_GET['hospiceID']!="")
	{
		$hospiceID = $_GET["hospiceID"];
	}
	if(isset($_GET['from']) AND $_GET['from']!="")
	{
		$from = $_GET["from"];
	}
	if(isset($_GET['to']) AND $_GET['to']!="")
	{
		$to = $_GET["to"];
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

	$response['hospiceID'] 	= $hospiceID;
	$response['from']	= $from;
	$response['to']		= $to;

	return $response;
}
function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') == $date;
}

function get_items_for_pickup($unique, $hospiceID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('address.*');
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
    $ci->db->join('dme_patient_address as address', 'address.id = orders.addressID', 'left');
    $ci->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
    $ci->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
    $ci->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left');
    $ci->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors

    if($unique != '')
    {
        $ci->db->where('orders.medical_record_id' , $unique);

        $ci->db->where('orders.organization_id', $hospiceID); //newly added 07/13/2015.
    }
    $ci->db->where('equip.parentID ', 0);
    $ci->db->where('orders.pickup_order ', 0);
    $ci->db->where('orders.canceled_order ', 0);
    $ci->db->where('orders.canceled_from_confirming ', 0);
    $ci->db->where('orders.activity_typeid !=', 2);
    $ci->db->where('orders.order_status !=', "cancel");
    $ci->db->where('address.type ', 0);

   	$ci->db->group_by('orders.orderID');
    $ci->db->order_by('orders.pickup_date', 'DESC');

    $data = $ci->db->get()->result_array();

    return $data;
}

function get_items_for_pickup_other_address($unique, $hospiceID, $addressID)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('address.*');
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
    $ci->db->join('dme_patient_address as address', 'address.id = orders.addressID', 'left');
    $ci->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
    $ci->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
    $ci->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left');
    $ci->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors

    if($unique != '')
    {
        $ci->db->where('orders.medical_record_id' , $unique);

        $ci->db->where('orders.organization_id', $hospiceID); //newly added 07/13/2015.
    }
    $ci->db->where('orders.pickup_order ', 0);
    $ci->db->where('orders.activity_typeid !=', 2);
    $ci->db->where('orders.order_status !=', "cancel");
    $ci->db->where('orders.addressID ', $addressID);

   	$ci->db->group_by('orders.orderID');
    $ci->db->order_by('orders.pickup_date', 'DESC');

    $data = $ci->db->get()->result_array();

    return $data;
}


function get_oxygen_count($organization_id,$patientID,$unique_id)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('orderID');
	$ci->db->from('dme_order as orders');
	$ci->db->where('orders.organization_id', $organization_id);
	$ci->db->where('orders.patientID', $patientID);
	$ci->db->where_in('orders.uniqueID', $unique_id);
	$ci->db->where('orders.equipmentID',29);
	$ci->db->or_where('orders.equipmentID',61);

	return $ci->db->get()->num_rows();
}

function get_first_oxygen_orderID($organization_id,$patientID,$unique_id,$oxygen_equipment_id)
{
	$ci = get_instance();
	$ci->load->database();

	$ci->db->select('orderID');
	$ci->db->from('dme_order as orders');
	$ci->db->where('orders.organization_id', $organization_id);
	$ci->db->where('orders.patientID', $patientID);
	$ci->db->where_in('orders.uniqueID', $unique_id);
	$ci->db->where('orders.equipmentID',$oxygen_equipment_id);

	$data = $ci->db->get()->first_row('array');

    return $data;
}

function get_item_usage_sort_date_type($item_category,$hospiceID,$from,$to)
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type, equipments.*');
	$CI->db->from('dme_order AS orders');
	$CI->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$CI->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$CI->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$CI->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$CI->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$CI->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    if(empty($from) && empty($to))
    {
    	$current_day = date('Y-m-d');
		$start = date("Y-m-d",strtotime($current_day." -1 days"));
		$end = date("Y-m-d",strtotime($current_day." +1 days"));
		$final_start = strtotime($start." 23:59:59");
		$final_end = strtotime($end." 00:00:00");
    }
    else
    {
		$start = date("Y-m-d",strtotime($from." -1 days"));
		$end = date("Y-m-d",strtotime($to." +1 days"));
		$final_start = strtotime($start." 23:59:59");
		$final_end = strtotime($end." 00:00:00");
    }

    $CI->db->where('cat.categoryID', $item_category);
    $CI->db->where('orders.original_activity_typeid !=', 2);
	$CI->db->where('orders.original_activity_typeid !=', 3);
	$CI->db->where("orders.organization_id != ", 13);
	$CI->db->where("orders.uniqueID > ",$final_start);
	$CI->db->where("orders.uniqueID < ",$final_end);
	$CI->db->order_by('equipments.key_desc', 'ASC');

    $query = $CI->db->get();

	return $query->result_array();
}

function get_item_usage_sort_date_type_v2($item_category,$hospiceID,$from,$to,$account_location)
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type, equipments.*');
	$CI->db->from('dme_order AS orders');
	$CI->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$CI->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$CI->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$CI->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$CI->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$CI->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    if(empty($from) && empty($to))
    {
    	$current_day = date('Y-m-d');
		$start = date("Y-m-d",strtotime($current_day." -1 days"));
		$end = date("Y-m-d",strtotime($current_day." +1 days"));
		$final_start = strtotime($start." 23:59:59");
		$final_end = strtotime($end." 00:00:00");
    }
    else
    {
		$start = date("Y-m-d",strtotime($from." -1 days"));
		$end = date("Y-m-d",strtotime($to." +1 days"));
		$final_start = strtotime($start." 23:59:59");
		$final_end = strtotime($end." 00:00:00");
    }

    $CI->db->where('cat.categoryID', $item_category);
    $CI->db->where('orders.original_activity_typeid !=', 2);
	$CI->db->where('orders.original_activity_typeid !=', 3);
	$CI->db->where("orders.organization_id != ", 13);
	$CI->db->where("orders.uniqueID > ",$final_start);
	$CI->db->where("orders.uniqueID < ",$final_end);
	$CI->db->where("hosp.account_location",$account_location);
	$CI->db->order_by('equipments.key_desc', 'ASC');

    $query = $CI->db->get();

	return $query->result_array();
}

function get_login_location($account_location)
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('*');
	$CI->db->from('dme_location AS location');
	$CI->db->where("location.location_id",$account_location);

	$query = $CI->db->get();

	return $query->first_row('array');
}

function get_item_usage_sort_date_type_second_date($item_category,$hospiceID,$from,$to)
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('equipments.parentID,equipments.equipmentID,equipments.key_desc,orders.patientID');
	$CI->db->from('dme_order AS orders');
	$CI->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$CI->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$CI->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$CI->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$CI->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$CI->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    if(empty($from) && empty($to))
    {
    	$current_day = date('Y-m-d');
		$start = date("Y-m-d",strtotime($current_day." -1 days"));
		$end = date("Y-m-d",strtotime($current_day." +1 days"));
		$final_start = strtotime($start." 23:59:59");
		$final_end = strtotime($end." 00:00:00");
    }
    else
    {
		$start = date("Y-m-d",strtotime($from." -1 days"));
		$end = date("Y-m-d",strtotime($to." +1 days"));
		$final_start = strtotime($start." 23:59:59");
		$final_end = strtotime($end." 00:00:00");
    }

    $CI->db->where('cat.categoryID', $item_category);
    $CI->db->where('orders.original_activity_typeid !=', 2);
	$CI->db->where('orders.original_activity_typeid !=', 3);
	$CI->db->where("orders.organization_id != ", 13);
	$CI->db->where("orders.uniqueID > ",$final_start);
	$CI->db->where("orders.uniqueID < ",$final_end);
	$CI->db->order_by('equipments.key_desc', 'ASC');

    $query = $CI->db->get();

	return $query->result_array();
}

function get_item_usage_sort_date_type_second_date_v2($item_category,$hospiceID,$from,$to,$account_location)
{
	$CI =& get_instance();

	$CI->load->database();

	$CI->db->select('equipments.parentID,equipments.equipmentID,equipments.key_desc,orders.patientID');
	$CI->db->from('dme_order AS orders');
	$CI->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$CI->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$CI->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$CI->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$CI->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$CI->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    if(empty($from) && empty($to))
    {
    	$current_day = date('Y-m-d');
		$start = date("Y-m-d",strtotime($current_day." -1 days"));
		$end = date("Y-m-d",strtotime($current_day." +1 days"));
		$final_start = strtotime($start." 23:59:59");
		$final_end = strtotime($end." 00:00:00");
    }
    else
    {
		$start = date("Y-m-d",strtotime($from." -1 days"));
		$end = date("Y-m-d",strtotime($to." +1 days"));
		$final_start = strtotime($start." 23:59:59");
		$final_end = strtotime($end." 00:00:00");
    }

    $CI->db->where('cat.categoryID', $item_category);
    $CI->db->where('orders.original_activity_typeid !=', 2);
	$CI->db->where('orders.original_activity_typeid !=', 3);
	$CI->db->where("orders.organization_id != ", 13);
	$CI->db->where("orders.uniqueID > ",$final_start);
	$CI->db->where("orders.uniqueID < ",$final_end);
	$CI->db->where("hosp.account_location",$account_location);
	$CI->db->order_by('equipments.key_desc', 'ASC');

    $query = $CI->db->get();

	return $query->result_array();
}

function get_item_usage_comparison_dates_type($item_category,$hospiceID,$date)
{
	$CI =& get_instance();

	$CI->load->database();

	//using database caching
    $CI->db->start_cache();

	$CI->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type, equipments.*');
	$CI->db->from('dme_order AS orders');
	$CI->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	$CI->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	$CI->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	$CI->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	$CI->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	$CI->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

	if(!empty($hospiceID))
    {
    	$CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin")
    {
    	$hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

	$start = date("Y-m-d",strtotime($date." -1 days"));
	$end = date("Y-m-d",strtotime($date." +1 days"));
	$final_start = strtotime($start." 23:59:59");
	$final_end = strtotime($end." 00:00:00");

	$CI->db->where("orders.uniqueID > ",$final_start);
	$CI->db->where("orders.uniqueID < ",$final_end);
	$CI->db->where('cat.categoryID', $item_category);
	$CI->db->where('orders.original_activity_typeid !=', 2);
	$CI->db->where('orders.original_activity_typeid !=', 3);
	$CI->db->where("orders.organization_id != ", 13);

	$CI->db->order_by('equipments.key_desc', 'ASC');

    $CI->db->stop_cache();
    $query = $CI->db->get()->result_array();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_equipment_attributes($equipmentID)
{
	$ci =& get_instance();
	$ci->load->model("equipment_model");

	return $ci->equipment_model->get_equipment_options($equipmentID);
}
