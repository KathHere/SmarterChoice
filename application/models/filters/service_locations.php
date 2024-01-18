<?php 

class Service_location extends CI_Model
{
	public function service_location_id($order='ASC')
	{
		$this->db->order_by("location.id",strtoupper($order));
	}

	public function service_location_name($order='ASC')
	{
		$this->db->order_by("location.service_location_name",strtoupper($order));
	}

	public function search_item_fields_service_location($search_value="")
	{
		$where = "location.user_city LIKE '%$search_value%' OR location.service_location_id LIKE '%$search_value%' OR location.service_location_name LIKE '%$search_value%')";
		$this->db->where($where);
	}
}