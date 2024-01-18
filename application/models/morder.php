<?php

Class morder extends CI_MODEL
{
	var $allowed_fields = array("uniqueID","pickup_date","orderID");
	var $response = false;
	public function do_update($param=array(),$data=array())
	{
		if(!empty($param))
		{
			if(in_array($param['name'], $this->allowed_fields))
			{
				$this->db->where($param['name'],$param['pk']);
				$this->response = $this->db->update($param['table'],$data);
			}
		}
		return $this->response;
	}
	public function do_update_quantity($conditions=array(),$data=array(),$table="")
	{
		if(!empty($conditions) AND !empty($data) AND !empty($table))
		{
			foreach ($conditions as $key => $value)
			{
				$this->db->where($key,$value);
			}
			$this->response = $this->db->update($table,$data);
		}
		return $this->response;
	}

	/*
	| @func 	: do_get_equip_options
	| @desc 	: getting the attributes of the equipment, this will map to parentID in dem_equipment table
	| @return 	: array or empty
	*/
	public function do_get_equip_options($parentID="")
	{
		if(!empty($parentID))
		{
			$this->db->where("parentID",$parentID);
			$this->db->order_by("optionID","asc");
			$this->response = $this->db->get("dme_equipment")->result_array();
		}
		return $this->response;
	}

	/*
	| @func : do_get_option_desc()
	| @desc : get description of the item
	| @return : array()
	*/
	public function do_get_option_desc()
	{
		return $this->db->get("dme_options")->result_array();
	}
	/*
	| @func : do_get_order_equipment()
	| @desc : get equipments in the order
	| @return : array()
	*/
	public function do_get_order_equipment($uniqueID="",$equipmentIDs=array())
	{
		if(!empty($uniqueID) AND !empty($equipmentIDs))
		{
			$this->db->select("o.equipmentID,o.equipment_value,e.optionID");
			$this->db->from("dme_order as o");
			$this->db->join("dme_equipment as e","o.equipmentID = e.equipmentID");
			$this->db->where("o.uniqueID",$uniqueID);
			$this->db->where_in("o.equipmentID",$equipmentIDs);
			$this->response = $this->db->get()->result_array();
		}
		return $this->response;
	}

	public function update_address($id,$data=array())
	{
		if(!empty($id) AND !empty($data))
		{
			$this->db->where("id",$id);
			$this->response = $this->db->update("dme_patient_address",$data);
		}
		return $this->response;
	}

	public function update_address_patient_profile($patientID,$data_to_update)
	{
		if(!empty($patientID) AND !empty($data_to_update))
		{
			$this->db->where("patientID",$patientID);
			$this->response = $this->db->update("dme_patient",$data_to_update);
		}
		return $this->response;
	}

	public function update_equipment_option_rc($data=array(),$uniqueID)//radio and checkbox
	{
		$response = false;
		if(!empty($data) AND !empty($uniqueID))
		{
			foreach($data as $value)
			{
				$this->db->where("uniqueID",$uniqueID);
				$this->db->where("equipmentID",$value['equipment']);
				$response = $this->db->update("dme_order",array("equipmentID" => $value['equipment_new']));
			}
		}
		return $response;
	}

	function update_order_summary_v2($uniqueID, $equipmentID, $patientID, $data='')
    {
   		$response = false;
		if(!empty($data))
		{
			$this->db->where('uniqueID', $uniqueID);
			$this->db->where('equipmentID', $equipmentID);
			$this->db->where('patientID', $patientID);
			$response = $this->db->update('dme_order', $data);
		}
		return $response;
    }

	function update_order_summary_v3($orderID, $data='')
    {
   		$response = false;
		if(!empty($data))
		{
			$this->db->where('orderID', $orderID);
			$response = $this->db->update('dme_order', $data);
		}
		return $response;
    }

    function get_work_order_items($uniqueID, $patientID)
    {
    	$this->db->select("*");
		$this->db->from("dme_order as ord");
		$this->db->join("dme_equipment as equip","ord.equipmentID = equip.equipmentID",'left');
		$this->db->where('ord.uniqueID', $uniqueID);
		$this->db->where('ord.patientID', $patientID);
		$this->db->where('equip.parentID', 0);
		$this->db->where('ord.canceled_order', 0);
		$this->response = $this->db->get()->result_array();

		return $this->response;
    }

    function get_current_patient_address($patientID) {
    	$this->db->select("*");
		$this->db->from("dme_patient_address as loc");
		$this->db->where('loc.patient_id', $patientID);
		$this->db->order_by('loc.id', 'DESC');
		$this->response = $this->db->get()->first_row('array');

		return $this->response;
    }

    function check_patient_cusmove_order($patientID, $uniqueID) {
    	$this->db->select("*");
		$this->db->from("dme_order as ord");
		$this->db->join("dme_equipment as equip","ord.equipmentID = equip.equipmentID",'left');
		$this->db->where('ord.patientID', $patientID);
		$this->db->where('ord.original_activity_typeid', 4);
		$this->db->where('ord.uniqueID !=', $uniqueID);
		$this->db->where('equip.parentID', 0);
		$this->db->where('ord.canceled_order', 0);
		$this->response = $this->db->get()->first_row('array');

		return $this->response;
    }
}