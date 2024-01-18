<?php 

class Statement_draft extends CI_Model
{
	public function hospice_name($order='ASC')
	{
		$this->db->order_by("hospice.hospice_name",strtoupper($order));
	}

	public function search_item_fields_statement_draft($search_value="")
	{
		$where = "(hospice.hospice_name LIKE '%$search_value%' OR statement_bill.statement_no LIKE '%$search_value%')";
		$this->db->where($where);
	}
}