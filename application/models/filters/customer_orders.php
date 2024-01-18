<?php

class Customer_orders extends CI_Model
{
	public function order_by_order_date($order='ASC')
	{
		$this->db->order_by("pickup_date",strtoupper($order));
	}

	public function order_by_last_name($order='ASC')
	{
		$this->db->order_by("p_lname",strtoupper($order));
	}

	public function order_by_first_name($order='ASC')
	{
		$this->db->order_by("p_fname",strtoupper($order));
	}

	public function order_by_medical_record_no($order='ASC')
	{
		$this->db->order_by("medical_record_id",strtoupper($order));
	}

	public function order_by_hospice_name($order='ASC')
	{
		$this->db->order_by("hospice_name",strtoupper($order));
	}

	public function search_item_fields_customer_orders($search_value="")
	{
		$where = "(pickup_date LIKE '%$search_value%' OR p_lname LIKE '%$search_value%' OR p_fname LIKE '%$search_value%' OR medical_record_id LIKE '%$search_value%' OR hospice_name LIKE '%$search_value%')";
		$this->db->where($where);
	}
}
