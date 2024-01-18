<?php

class Canceled_work_orders extends CI_Model
{
	public function canceled_work_order_by_medical_record_no($order='ASC')
	{
		$this->db->order_by("medical_record_id",strtoupper($order));
	}

	public function canceled_work_order_by_last_name($order='ASC')
	{
		$this->db->order_by("p_lname",strtoupper($order));
	}

	public function canceled_work_order_by_first_name($order='ASC')
	{
		$this->db->order_by("p_fname",strtoupper($order));
	}

	public function canceled_work_order_by_canceled_by($order='ASC')
	{
		$this->db->order_by("canceled_by",strtoupper($order));
	}

	public function canceled_work_order_by_date_deleted($order='ASC')
	{
		$this->db->order_by("date_deleted",strtoupper($order));
	}

	public function search_item_fields_canceled_work_orders($search_value="")
	{
		$where = "(date_deleted LIKE '%$search_value%' OR p_lname LIKE '%$search_value%' OR p_fname LIKE '%$search_value%' OR medical_record_id LIKE '%$search_value%' OR canceled_by LIKE '%$search_value%')";
		$this->db->where($where);
	}
}

?>
