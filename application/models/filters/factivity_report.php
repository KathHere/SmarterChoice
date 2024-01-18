<?php

class Factivity_report extends CI_Model
{
	//parameter accepts from and to
	// e.g $date = array(
	//                 "from" => "01-14-15",
	//				   "to"   => "02-15-2016"
	//				)
	//
	public function date_range($data=array())
	{
		$current_date = date('Y-m-d');

		if($data['from'] != "" && $data['to'] != "")
		{
			if($data['from'] <= $data['to'])
			{
				if(($data['from'] == $current_date && $data['to'] == $current_date) || ($data['from'] == $current_date && $data['to'] > $data['from']))
				{
					$this->db->where("DATE_FORMAT(orders.date_ordered,'%Y-%m-%d')",$current_date);
		            $this->db->where("orders.order_status", "active");
				}
				if($data['to'] == $current_date || $data['to'] > $current_date)
				{
					$this->db->where("(orders.actual_order_date>='{$data['from']}' AND orders.actual_order_date<'{$current_date}')",null,false);
				}
				else if($data['to'] < $current_date)
				{
					$this->db->where("(orders.actual_order_date>='{$data['from']}' AND orders.actual_order_date<='{$data['to']}')",null,false);
				}
			}
			else
			{
				$this->db->where("(orders.actual_order_date>='{$data['from']}' AND orders.actual_order_date<='{$data['to']}')",null,false);
			}
		}

		if(!empty($data['hospiceID']) && $data['hospiceID'] != 0)
		{
			$this->db->where("orders.organization_id",$data['hospiceID']);
		}
	}
	// $this->db->where("(actual_order_date>='{$data['from']}' AND actual_order_date<='{$data['to']}')",null,false);

	public function current_month()
	{
		// $current_date = date('Y-m-d');
		// $this->db->where("actual_order_date",$current_date);

		//SELECT * FROM `dme_patient` WHERE MONTH(date_created)=2 AND YEAR(date_created)=2016
	}
	public function activity_type($id="")
	{
		//check number of items ordered
		if($id==1){
			$this->db->select("count(DISTINCT equip.equipmentID, orders.uniqueID) as total",false);
			$this->db->from("dme_order as orders");
			$this->db->join("dme_hospice as hospice","orders.organization_id=hospice.hospiceID");
			$this->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");
			$this->db->where("orders.original_activity_typeid",$id);
			$this->db->where("equip.parentID",0);
		}
		else{
			$this->db->select("count(*) as total",false);
			$this->db->from("dme_order_status as orders");
			$this->db->join("dme_hospice as hospice","orders.organization_id=hospice.hospiceID");
			$this->db->where("orders.status_activity_typeid",$id);
		}

	}
	public function patienceresidence_type($type="")
	{
		$this->db->where("deliver_to_type",$type);
	}

	public function order_by_customer_name($order='ASC')
    {
        $this->db->order_by("patient.p_lname", strtoupper($order));
    }

    public function order_by_due_date($order='ASC')
    {
        $this->db->order_by("follow_up.follow_up_date", strtoupper($order));
    }

    public function search_item_fields_o2_follow_up($search_value="")
    {
        $where = "(patient.p_fname LIKE '%$search_value%' OR follow_up.follow_up_date LIKE '%$search_value%')";
        $this->db->where($where);
    }
}
