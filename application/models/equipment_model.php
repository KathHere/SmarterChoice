<?php
	Class equipment_model extends Ci_Model
	{
		var $response = false;
		public function __construct()
		{
			$this->db->query('SET SQL_BIG_SELECTS=1');
		}

		function list_equipment()
		{
			$this->db->select('*');
			$this->db->from('dme_equipment AS equip');
			$this->db->join('dme_equip_category AS cat','equip.categoryID = cat.categoryID', 'left');
			// $this->db->join('dme_options AS opt','equip.optionID = opt.optionID');
			//$this->db->group_by('equip.categoryID');
			//$this->db->where('equip.optionID','0');
			$this->db->where('equip.parentID','0');
			$query = $this->db->get();

			return $query->result_array();
		}

		function tobe_assign_equipment()
		{
			$this->db->select('*');
			$this->db->from('dme_equipment AS equip');
			$this->db->join('dme_equip_category AS cat','equip.categoryID = cat.categoryID', 'left');
			//$this->db->join('dme_options AS opt','equip.optionID = opt.optionID');
			//$this->db->group_by('equip.categoryID');
			//$this->db->where('equip.optionID','0');
			$this->db->where('equip.parentID','0');
			$this->db->order_by('cat.type', 'ASC');
			//$this->db->where('cat.categoryID','1');
			$query = $this->db->get();

			return $query->result_array();
		}

		function tobe_assign_equipment_v2()
		{
			$this->db->select('*');
			$this->db->from('dme_equipment AS equip');
			$this->db->join('dme_equip_category AS cat','equip.categoryID = cat.categoryID', 'left');
			$this->db->where('equip.categoryID !=', 1);
			$this->db->where('equip.parentID','0');
			$this->db->order_by('cat.type', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		function tobe_assign_equipment_v3($hospiceID)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment AS equip');
			$this->db->join('dme_equip_category AS cat','equip.categoryID = cat.categoryID', 'left');
			$this->db->join('dme_assigned_equipment AS assign','equip.equipmentID = assign.equipmentID', 'left');
			$this->db->where('assign.hospiceID', $hospiceID);
			$this->db->where('equip.categoryID !=', 1);
			$this->db->where('equip.parentID','0');
			$this->db->order_by('cat.type', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		function tobe_assign_equipment_v4($hospiceID)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment AS equip');
			$this->db->join('dme_equip_category AS cat','equip.categoryID = cat.categoryID', 'left');
			$this->db->join('dme_assigned_equipment AS assign','equip.equipmentID = assign.equipmentID', 'left');
			$this->db->where('assign.hospiceID', $hospiceID);
			$this->db->where('equip.categoryID !=', 1);
			$this->db->where('equip.parentID','0');
			$this->db->order_by('cat.type', 'ASC');
			$this->db->where('equip.is_deleted','0');
			$this->db->where('equip.is_deactivated','0');
			$this->db->where('equip.is_add_to_hospice_item_list','1');
			$query = $this->db->get();

			return $query->result_array();
		}

		function assigned_equipment_capped($hospiceID)
		{
			$this->db->select('*');
			$this->db->from('dme_equip_category AS cat');
			$this->db->join('dme_equipment AS equip','cat.categoryID = equip.categoryID', 'left');
			$this->db->join('dme_assigned_equipment AS assign','equip.equipmentID = assign.equipmentID', 'left');
			$this->db->where('assign.hospiceID', $hospiceID);
			$this->db->where('equip.categoryID', 1);
			$this->db->where('equip.parentID','0');
			$query = $this->db->get();

			return $query->result_array();
		}

		function assigned_equipment_capped_v2($hospiceID)
		{
			$this->db->select('*');
			$this->db->from('dme_equip_category AS cat');
			$this->db->join('dme_equipment AS equip','cat.categoryID = equip.categoryID', 'left');
			$this->db->join('dme_assigned_equipment AS assign','equip.equipmentID = assign.equipmentID', 'left');
			$this->db->where('assign.hospiceID', $hospiceID);
			$this->db->where('equip.categoryID', 1);
			$this->db->where('equip.parentID','0');
			$this->db->where('assign.is_hidden','0');
			$query = $this->db->get();

			return $query->result_array();
		}

		function assigned_equipment_non_capped($hospiceID)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment AS equip');
			$this->db->join('dme_assigned_equipment AS cat','equip.equipmentID = cat.equipmentID', 'left');
			$this->db->where('cat.hospiceID', $hospiceID);
			$this->db->where('equip.categoryID', 2);
			$this->db->where('equip.parentID','0');
			$query = $this->db->get();

			return $query->result_array();
		}

		function assigned_equipment_non_capped_v2($hospiceID)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment AS equip');
			$this->db->join('dme_assigned_equipment AS cat','equip.equipmentID = cat.equipmentID', 'left');
			$this->db->where('cat.hospiceID', $hospiceID);
			$this->db->where('equip.categoryID', 2);
			$this->db->where('equip.parentID','0');
			$this->db->where('equip.is_deleted',0);
			$this->db->where('equip.is_deactivated',0);
			$this->db->where('equip.is_add_to_hospice_item_list',1);
			$query = $this->db->get();

			return $query->result_array();
		}

		function assigned_equipment_disposable_v2($hospiceID)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment AS equip');
			$this->db->join('dme_assigned_equipment AS cat','equip.equipmentID = cat.equipmentID', 'left');
			$this->db->where('cat.hospiceID', $hospiceID);
			$this->db->where('equip.categoryID', 3);
			$this->db->where('equip.parentID','0');
			$this->db->where('equip.is_deleted',0);
			$this->db->where('equip.is_deactivated',0);
			$this->db->where('equip.is_add_to_hospice_item_list',1);
			$query = $this->db->get();

			return $query->result_array();
		}

		function assigned_equipment_disposable($hospiceID)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment AS equip');
			$this->db->join('dme_assigned_equipment AS cat','equip.equipmentID = cat.equipmentID', 'left');
			$this->db->where('cat.hospiceID', $hospiceID);
			$this->db->where('equip.categoryID', 3);
			$this->db->where('equip.parentID','0');
			$query = $this->db->get();

			return $query->result_array();
		}

		function original_equipment_to_cancel_pickup($medical_record_id="", $work_order="", $equipmentID)
		{
			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
			$this->db->select('patient.*');
			$this->db->select('act.*');
			$this->db->select('hosp.*');
			$this->db->select('equipments.*');
			$this->db->select('cat.*');

			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			$includes = array(1,2);

			if($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user' && $this->session->userdata('account_type') != 'biller')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}

			$this->db->where('orders.medical_record_id', $medical_record_id);
			$this->db->where('pickedup_uniqueID', $work_order);
			$this->db->where('activity_reference', 2);
			$this->db->where('orders.equipmentID', $equipmentID);

			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function original_equipment_to_cancel_exchange($medical_record_id="", $work_order="", $equipmentID)
		{
			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
			$this->db->select('patient.*');
			$this->db->select('act.*');
			$this->db->select('hosp.*');
			$this->db->select('equipments.*');
			$this->db->select('cat.*');

			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			$includes = array(1,2);

			if($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}

			$this->db->where('orders.medical_record_id', $medical_record_id);
			$this->db->where('uniqueID_reference', $work_order);
			$this->db->where('activity_reference', 3);
			$this->db->where('orders.equipmentID', $equipmentID);

			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->first_row('array');
		}

		public function get_equipment_before_exchange($medical_id, $uniqueID, $equipmentID)
		{
			$this->db->select('*');
			$this->db->from('dme_order');
			$this->db->where('medical_record_id', $medical_id);
			$this->db->where('uniqueID_reference', $uniqueID);
			$this->db->where('equipmentID', $equipmentID);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		public function original_equipment_details($original_orderID)
		{
			$this->db->select('*');
			$this->db->from('dme_order');
			$this->db->where('orderID', $original_orderID);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		public function revert_to_pickup($original_orderID,$array=array())
		{
			$this->db->where('orderID', $original_orderID);
        	$this->db->update('dme_order', $array);
		}

		public function revert_to_exchange($original_orderID,$array=array())
		{
			$this->db->where('orderID', $original_orderID);
        	$this->db->update('dme_order', $array);
		}

		public function revert_pickedup_item_to_original($equipmentID,$uniqueID,$medical_record_id,$array=array())
        {
        	$this->db->where('medical_record_id', $medical_record_id);
    		$this->db->where('pickedup_uniqueID', $uniqueID);
        	$this->db->where('equipmentID', $equipmentID);
        	$this->db->update('dme_order', $array);
        }

        public function revert_pickedup_item_to_original_v2($equipmentID,$uniqueID,$medical_record_id,$array=array())
        {
        	$this->db->where('medical_record_id', $medical_record_id);
    		$this->db->where('uniqueID', $uniqueID);
        	$this->db->where('equipmentID', $equipmentID);
        	$this->db->update('dme_order', $array);
        }

        public function revert_exchange_item_to_original($equipmentID,$uniqueID,$medical_record_id,$array=array())
        {
        	$this->db->where('medical_record_id', $medical_record_id);
    		$this->db->where('uniqueID', $uniqueID);
        	$this->db->where('equipmentID', $equipmentID);
        	$this->db->update('dme_order', $array);
        }

		function get_hospice($hospiceID)
		{
			$this->db->select('*');
			$this->db->from('dme_hospice');
			$this->db->where('hospiceID', $hospiceID);
			$query = $this->db->get();

			return $query->result_array();

		}

		function insert_equipments($data=array(),$hospiceID)
		{
			$response = false;
			if(!empty($data))
			{
				//delete existing
				if(!empty($hospiceID))
				{
					$this->db->where('hospiceID',$hospiceID);
				}
				$delete = $this->db->delete('dme_assigned_equipment');
				if($delete)
				{
					$response = $this->db->insert_batch('dme_assigned_equipment', $data);
				}
			}
			return $response;

		}

		function count_results()
		{

			$this->db->select('*');
			$this->db->from('dme_equipment AS equip');
			$this->db->join('dme_equip_category AS cat','equip.categoryID = cat.categoryID', 'left');
			// $this->db->join('dme_options AS opt','equip.optionID = opt.optionID');
			//$this->db->group_by('equip.categoryID');
			$this->db->where('equip.optionID','0');
			$this->db->where('cat.categoryID','1');

			$count =  $this->db->count_all_results();
			//$query = $this->db->get();
			return $count;
		}

		function update_equip_name($equipID , $data)
		{
			$this->db->where('equipmentID', $equipID);
		    $this->db->update('dme_equipment', $data);
		}

		function update_equip_v2($hospiceID, $equipID , $data)
		{
			$this->db->where('equipmentID', $equipID);
			$this->db->where('hospiceID', $hospiceID);
		    $this->db->update('dme_assigned_equipment', $data);
		}

		function delete_equipment($equipID)
		{
			$this->db->where('equipmentID', $equipID);
			$this->db->delete('dme_equipment');
		}

		function delete_equipment_pickup($equipmentID,$uniqueID)
		{
			$this->db->where('equipmentID', $equipmentID);
			$this->db->where('uniqueID', $uniqueID);
			$this->db->delete('dme_order');
		}

		function delete_equipment_options_pickup($equipmentID,$uniqueID)
		{
			$this->db->where('equipmentID', $equipmentID);
			$this->db->where('uniqueID', $uniqueID);
			$this->db->delete('dme_order');
		}

		function get_equipment_cat()
		{
			return $this->db->get('dme_equip_category')->result_array();
		}

		function add_equipment($data=array())
		{
			$response = false;
			if(!empty($data))
			{
				$save_info = $this->db->insert("dme_equipment",$data);
				if($save_info)
				{
					$response = $this->db->insert_id();
				}
			}
			return $response;

		}

		function add_disposable_quantity($data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$response = $this->db->insert('dme_equipment', $data);
			}
			return $response;
		}

		function remove_assigned_equipment($equipmentID,$hospice_id)
		{
			$response = false;

			if(!empty($equipmentID) && !empty($hospice_id))
			{
				$this->db->where('hospiceID',$hospice_id);
				$this->db->where('equipmentID',$equipmentID);
				$response = $this->db->delete('dme_assigned_equipment');
			}
			return $response;
		}

		function remove_assigned_equipment_v2($equipmentID,$hospice_id, $data)
		{
			$response = false;

			if(!empty($equipmentID) && !empty($hospice_id))
			{
				$this->db->where('hospiceID',$hospice_id);
				$this->db->where('equipmentID',$equipmentID);
				$response = $this->db->update('dme_assigned_equipment', $data);
			}
			return $response;
		}

		function query_sub_equipment_id($parent_itemID)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment');
			$this->db->where("parentID", $parent_itemID);
			$this->db->where("categoryID", 3);
			$query = $this->db->get();

			return $query->result_array();
		}

		function get_equipment_details($equipmentID)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment');
			$this->db->where("equipmentID", $equipmentID);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_sub_equipment_details($equipmentID)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment');
			$this->db->where("parentID", $equipmentID);
			$query = $this->db->get();

			return $query->result_array();
		}

		function check_capped_copy($key_name)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment');
			$this->db->where("key_name", $key_name);
			$this->db->where("categoryID", 1);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_assigned_capped_copy($equipmentID,$hospice_id)
		{
			$this->db->select('*');
			$this->db->from('dme_assigned_equipment');
			$this->db->where("equipmentID", $equipmentID);
			$this->db->where("hospiceID", $hospice_id);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function check_capped_copy_v2($equipmentID)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment');
			$this->db->where("noncapped_reference", $equipmentID);
			$this->db->where("categoryID", 1);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_non_capped_copy($key_name)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment');
			$this->db->where("key_name", $key_name);
			$this->db->where("categoryID", 2);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_non_capped_copy_v2($equipmentID)
		{
			$this->db->select('*');
			$this->db->from('dme_equipment');
			$this->db->where("equipmentID", $equipmentID);
			$this->db->where("categoryID", 2);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_hospice_uniqueID($hospice_id)
		{
			$this->db->select('uniqueID');
			$this->db->from('dme_assigned_equipment');
			$this->db->where("hospiceID", $hospice_id);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function insert_assigned_equipment($data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$response = $this->db->insert('dme_assigned_equipment', $data);
			}

			return $response;
		}

		function insert_assigned_equipment_v2($equipmentID, $hospice_id, $data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$this->db->where("hospiceID", $hospice_id);
				$this->db->where("equipmentID", $equipmentID);
				$response = $this->db->update('dme_assigned_equipment', $data);
			}

			return $response;
		}

		function insert_new_equipment_parent($data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$save_info = $this->db->insert('dme_equipment', $data);
				if($save_info)
				{
					$response = $this->db->insert_id();
				}
			}
			return $response;
		}

		function insert_new_equipment_sub($data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$response = $this->db->insert('dme_equipment', $data);
			}

			return $response;
		}

		function insert_new_row_pickup($data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$response = $this->db->insert('dme_order', $data);
			}

			return $response;
		}

		function cancel_equipment($equipmentID,$medical_id,$uniqueID,$data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$this->db->where('equipmentID', $equipmentID);
				$this->db->where('medical_record_id', $medical_id);
				$this->db->where('uniqueID', $uniqueID);
				$response = $this->db->update('dme_order',$data);
			}
			return $response;
		}

		function cancel_equipment_V2($orderID,$data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$this->db->where('orderID', $orderID);
				$response = $this->db->update('dme_order',$data);
			}
			return $response;
		}


		function insert_patient_weight($data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$response = $this->db->insert('dme_patient_weight', $data);
			}

			return $response;
		}

		// function insert_lot_number($data=array())
		// {
		// 	$response = false;

		// 	if(!empty($data))
		// 	{
		// 		$response = $this->db->insert('dme_lot_number', $data);
		// 	}

		// 	return $response;
		// }

		/*
			@f_name : get_assigned_equipments(param1,param2,param3)
			@desc : get equipments that has been assigned to the hospice
			@params :
						1. hospice ID <int|required>,
						2. equipment category <int|required|default = 3>
						3. additional <array>
			@date : 09-18-2017
			@added : JR
		*/
		public function get_assigned_equipments($hospiceID="",$equipmentCat=3,$additional=array())
		{
			if(!empty($hospiceID))
			{
				$hospiceID 			= (int)$hospiceID;
				$equipmentCat 		= (int)$equipmentCat;

				$additional_where = "";
				if(!empty($additional))
				{
					foreach ($additional as $key => $value) {
						$additional_where .= " AND {$key}='{$value}'";
					}
				}
				$query = "SELECT ae.*,e.categoryID,e.parentID,e.key_name,e.key_desc
								FROM dme_assigned_equipment ae
								LEFT JOIN dme_equipment e ON ae.equipmentID=e.equipmentID
								WHERE ae.hospiceID = {$hospiceID} AND e.categoryID={$equipmentCat} ".$additional_where;

				$this->response = $this->db->query($query)->result_array();
			}
			return $this->response;
		}
		// <------ end of get_assigned_equipments()
		/*
			@f_name : get_equipment_options(param1)
			@desc : get equipment options or attributes
			@params :
						1. parentID <int|required>
			@date : 09-18-2017
			@added : JR
		*/
		public function get_equipment_options($parentID="")
		{
			if(!empty($parentID))
			{
				$parentID = (int)$parentID;
				$query = " SELECT equipmentID, key_name, key_desc, input_type FROM dme_equipment WHERE parentID={$parentID}";
				$this->response = $this->db->query($query)->result_array();
			}
			return $this->response;
		}
		// <------ end of get_equipment_options()

			/*
			@f_name : get_sub_equipment_rates_by_keyname(param1,param2)
			@desc : get sub equipment rates of an equipment
			@params :
						1. assigned_equipmentID <int|required>
						2. key_name <string|required>
			@date : 03-27-2020
			@added : Adrian
		*/
		function get_sub_equipment_rates_by_keyname($assigned_equipmentID, $key_name)
		{
			$this->db->select('*');
			$this->db->from('dme_sub_equipment');
			$this->db->where("assigned_equipmentID", $assigned_equipmentID);
			$this->db->where("key_name", $key_name);
			$query = $this->db->get();

			return $query->first_row('array');
		}
		// <------ end of get_sub_equipment_rates()

		/*
			@f_name : get_sub_equipment_rates_by_keyname(param1,param2)
			@desc : get sub equipment rates of an equipment
			@params :
						1. assigned_equipmentID <int|required>
						2. key_name <string|required>
			@date : 03-27-2020
			@added : Adrian
		*/
		function get_sub_equipment_rates($assigned_equipmentID)
		{
			$this->db->select('*');
			$this->db->from('dme_sub_equipment');
			$this->db->where("assigned_equipmentID", $assigned_equipmentID);
			$query = $this->db->get();

			return $query->result_array();
		}
		// <------ end of get_sub_equipment_rates()

		/*
			@f_name : insert_sub_equipment_rates(param1)
			@desc : insert sub equipment rates of an equipment
			@params :
						1. data <array|required>
			@date : 03-27-2020
			@added : Adrian
		*/
		function insert_sub_equipment_rates($data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$response = $this->db->insert('dme_sub_equipment', $data);
			}

			return $response;
		}
		// <------ end of insert_sub_equipment_rates()

		/*
			@f_name : update_sub_equipment_rates(param1,param2)
			@desc : insert sub equipment rates of an equipment
			@params :
						1. subID <int|required>
						2. data <array|required>
			@date : 03-27-2020
			@added : Adrian
		*/
		function update_sub_equipment_rates($subID , $data)
		{
			$this->db->where('ID', $subID);
			$this->db->update('dme_sub_equipment', $data);
		}
		// <------ end of update_sub_equipment_rates()

		function get_assigned_equipment_details($hospiceID, $equipmentID){
			$this->db->select('*');
			$this->db->from('dme_assigned_equipment');
			$this->db->where("hospiceID", $hospiceID);
			$this->db->where("equipmentID", $equipmentID);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		public function get_customer_ordered_capped_item($patientID,$equipmentID)
		{
			$this->db->select('orderID, uniqueID, ');
			$this->db->from('dme_order');
			$this->db->where("patientID", $patientID);
			$this->db->where("equipmentID", $equipmentID);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		public function check_if_hospice_assigned_item($equipmentID,$hospiceID)
		{
			$this->db->select('*');
			$this->db->from('dme_assigned_equipment as assigned');
			$this->db->join("dme_equipment as equipments","assigned.equipmentID = equipments.equipmentID","inner");
			$this->db->where("assigned.equipmentID", $equipmentID);
			$this->db->where("assigned.hospiceID", $hospiceID);
			$this->db->where("is_hidden", 0);
			$query = $this->db->get();

			return $query->first_row('array');
		}
		
		public function get_items_for_change_item_category_confirmed_workorders_delivery($equipment_id, $date_from, $hospiceID) {
			$status = "confirmed";

			$this->db->select('orders.*');
			$this->db->from('dme_order as orders');

			// $where = '(orders.actual_order_date >= "'.$date_from.'")';
			// $this->db->where($where);

			$this->db->where("orders.equipmentID", $equipment_id);
			$this->db->where("orders.order_status", $status);
			$this->db->where("orders.canceled_order", 0);
			$this->db->where("orders.canceled_from_confirming", 0);
			$this->db->where("orders.organization_id", $hospiceID);
			$where_in = [208, 2, 157, 52, 48, 15, 34];
			// $where_hospiceID = '(orders.organization_id = 208 OR orders.organization_id = 2 OR orders.organization_id = 157 OR orders.organization_id = 52 OR orders.organization_id = 48 OR orders.organization_id = 15 OR orders.organization_id = 34 OR orders.organization_id = 51 OR orders.organization_id = 13)';
			// $this->db->where($where_hospiceID);
			// $this->db->where("orders.organization_id", 13);

			$where_activity_type = '(orders.activity_typeid = 1 OR orders.activity_typeid = 4 OR orders.activity_typeid = 5)';
			$this->db->where($where_activity_type);
			$query = $this->db->get();

			return $query->result_array();
		}

		public function get_items_for_change_item_category_pending_workorders_delivery($equipment_id, $date_from, $hospiceID) {
			$status = "pending";

			$this->db->select('orders.*');
			$this->db->from('dme_order as orders');

			$this->db->where("orders.equipmentID", $equipment_id);
			$this->db->where("orders.order_status", $status);
			$this->db->where("orders.organization_id", $hospiceID);
			$where_in = [208, 2, 157, 52, 48, 15, 34];
			// $where_hospiceID = '(orders.organization_id = 208 OR orders.organization_id = 2 OR orders.organization_id = 157 OR orders.organization_id = 52 OR orders.organization_id = 48 OR orders.organization_id = 15 OR orders.organization_id = 34 OR orders.organization_id = 51 OR orders.organization_id = 13)';
			// $this->db->where($where_hospiceID);
			// $this->db->where("orders.organization_id", 13);

			$where_activity_type = '(orders.activity_typeid = 1 OR orders.activity_typeid = 4 OR orders.activity_typeid = 5)';
			$this->db->where($where_activity_type);
			$query = $this->db->get();

			return $query->result_array();
		}

		public function get_items_for_change_item_category_workorders_pickup($equipment_id, $date_from, $hospiceID) {
			$status = "canceled";

			$this->db->select('orders.*');
			$this->db->from('dme_order as orders');

			$where = '(orders.actual_order_date >= "'.$date_from.'")';
			$this->db->where($where);
			
			$this->db->where("orders.equipmentID", $equipment_id);
			$this->db->where("orders.canceled_order", 0);
			$this->db->where("orders.canceled_from_confirming", 0);
			$this->db->where("orders.uniqueID_reference", 0);
			$this->db->where("orders.organization_id", $hospiceID);
			$where_in = [208, 2, 157, 52, 48, 15, 34];
			// $where_hospiceID = '(orders.organization_id = 208 OR orders.organization_id = 2 OR orders.organization_id = 157 OR orders.organization_id = 52 OR orders.organization_id = 48 OR orders.organization_id = 15 OR orders.organization_id = 34 OR orders.organization_id = 51 OR orders.organization_id = 13)';
			// $this->db->where($where_hospiceID);
			// $this->db->where("orders.organization_id", 13);

			$where_order_status = '(orders.order_status = "tobe_confirmed" OR orders.order_status = "pending" OR orders.order_status = "active" OR orders.order_status = "on-hold" OR orders.order_status = "re-schedule" OR orders.order_status = "confirmed")';
			$this->db->where($where_order_status);

			$this->db->where("orders.activity_typeid", 2);
			$this->db->where("orders.activity_reference", 2);
			$this->db->where("orders.original_activity_typeid", 1);
			$query = $this->db->get();

			return $query->result_array();
		}

		public function get_items_for_change_item_category_workorders_pickup_new_data($pickedup_uniqueID, $patientID, $equipmentID) {
			$status = "confirmed";

			$this->db->select('orders.*');
			$this->db->from('dme_order as orders');
			
			$this->db->where("orders.uniqueID", $pickedup_uniqueID);
			$this->db->where("orders.equipmentID", $equipmentID);
			$this->db->where("orders.patientID", $patientID);
			$this->db->where("orders.canceled_order", 0);
			$this->db->where("orders.canceled_from_confirming", 0);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		public function get_items_for_change_item_category_confirmed_workorders_exchange($equipment_id, $date_from, $hospiceID) {
			$status = "confirmed";

			$this->db->select('orders.*');
			$this->db->from('dme_order as orders');

			// $where = '(orders.actual_order_date >= "'.$date_from.'")';
			// $this->db->where($where);
			
			$this->db->where("orders.equipmentID", $equipment_id);
			$this->db->where("orders.canceled_order", 0);
			$this->db->where("orders.canceled_from_confirming", 0);
			$this->db->where("orders.order_status", $status);
			$this->db->where("orders.organization_id", $hospiceID);
			$where_in = [208, 2, 157, 52, 48, 15, 34];
			// $where_hospiceID = '(orders.organization_id = 208 OR orders.organization_id = 2 OR orders.organization_id = 157 OR orders.organization_id = 52 OR orders.organization_id = 48 OR orders.organization_id = 15 OR orders.organization_id = 34 OR orders.organization_id = 51 OR orders.organization_id = 13)';
			// $this->db->where($where_hospiceID);
			// $this->db->where("orders.organization_id", 13);

			$this->db->where("orders.activity_typeid", 3);
			$this->db->where("orders.activity_reference", 3);
			$this->db->where("orders.uniqueID_reference", 0);
			$query = $this->db->get();

			return $query->result_array();
		}

		public function get_items_for_change_item_category_pending_workorders_exchange($equipment_id, $date_from, $hospiceID) {
			$status = "pending";

			$this->db->select('orders.*');
			$this->db->from('dme_order as orders');

			// $where = '(orders.actual_order_date >= "'.$date_from.'")';
			// $this->db->where($where);
			
			$this->db->where("orders.equipmentID", $equipment_id);
			$this->db->where("orders.canceled_order", 0);
			$this->db->where("orders.canceled_from_confirming", 0);
			// $this->db->where("orders.order_status", $status);
			$this->db->where("orders.organization_id", $hospiceID);
			$where_in = [208, 2, 157, 52, 48, 15, 34];
			// $where_hospiceID = '(orders.organization_id = 208 OR orders.organization_id = 2 OR orders.organization_id = 157 OR orders.organization_id = 52 OR orders.organization_id = 48 OR orders.organization_id = 15 OR orders.organization_id = 34 OR orders.organization_id = 51 OR orders.organization_id = 13)';
			// $this->db->where($where_hospiceID);
			// $this->db->where("orders.organization_id", 13);

			$where_order_status = '(orders.order_status = "tobe_confirmed" OR orders.order_status = "pending" OR orders.order_status = "active" OR orders.order_status = "on-hold" OR orders.order_status = "re-schedule")';
			$this->db->where($where_order_status);

			$this->db->where("orders.activity_typeid", 3);
			$this->db->where("orders.activity_reference", 3);
			$this->db->where("orders.uniqueID_reference", 0);
			$query = $this->db->get();

			return $query->result_array();
		}

		public function get_items_for_change_item_category_pending_workorders_exchange_old($uniqueID, $equipment_id) {
			$this->db->select('orders.*');
			$this->db->from('dme_order as orders');

			$this->db->where("orders.uniqueID_reference", $uniqueID);
			$this->db->where("orders.equipmentID", $equipment_id);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		public function get_order_subequipments_exchange_old($uniqueID, $equipment_id) {
			$this->db->select('orders.orderID, orders.uniqueID, orders.actual_order_date, orders.organization_id, orders.equipmentID, equip.categoryID, equip.key_desc');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
			
			$this->db->where("orders.uniqueID", $uniqueID);
			$this->db->where("orders.equipmentID", $equipment_id);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		public function get_order_subequipments_pickup_new($pickedup_uniqueID, $equipment_id) {
			$this->db->select('orders.orderID, orders.uniqueID, orders.actual_order_date, orders.organization_id, orders.equipmentID, equip.categoryID, equip.key_desc');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
			
			$this->db->where("orders.uniqueID", $pickedup_uniqueID);
			$this->db->where("orders.equipmentID", $equipment_id);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function change_item_category_for_new_process($orderID, $data)
    	{
      	 	$this->db->where('orderID', $orderID);
			// $this->db->where('organization_id', 13); // Remove after testing
		    $this->db->update('dme_order', $data);
    	}

		function get_capped_subequipment($equipmentID, $key_desc) {
			$this->db->select('equipmentID, categoryID, key_desc, parentID');
			$this->db->from('dme_equipment');
			$this->db->where('parentID', $equipmentID);
			$this->db->where('key_desc', $key_desc);
			$this->db->where('categoryID', 1);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_order_subequipments($uniqueID, $equipmentID) {
			$this->db->select('orders.orderID, orders.uniqueID, orders.actual_order_date, orders.organization_id, orders.equipmentID, equip.categoryID, equip.key_desc');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
			$this->db->where('orders.uniqueID', $uniqueID);
			$this->db->where('equip.parentID', $equipmentID);
			$query = $this->db->get();

			return $query->result_array();
		}
	}
?>