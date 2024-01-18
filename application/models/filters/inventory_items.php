<?php

class Inventory_items extends CI_Model
{
	public function order_by_item_no($order='ASC')
	{
		$this->db->order_by("company_item_no",strtoupper($order));
	}

	public function order_by_item_description($order='ASC')
	{
		$this->db->order_by("item.item_description",strtoupper($order));
	}

	public function order_by_vendor_name($order='ASC')
	{
		$this->db->order_by("vendor.vendor_name",strtoupper($order));
	}

	public function order_by_item_serial_no($order='ASC')
	{
		$this->db->order_by("inventory_item.item_serial_no",strtoupper($order));
	}

	public function order_by_item_asset_no($order='ASC')
	{
		$this->db->order_by("inventory_item.item_asset_no",strtoupper($order));
	}

	public function order_by_item_warehouse_location($order='ASC')
	{
		$this->db->order_by("item_wh_location.item_warehouse_location",strtoupper($order));
	}

	public function order_by_item_status_location($order='ASC')
	{
		$this->db->order_by("inventory_item.item_status_location",strtoupper($order));
	}

	public function order_by_item_category_name($order='ASC')
	{
		$this->db->order_by("category.item_category_name",strtoupper($order));
	}

	public function order_by_received_date($order='ASC')
	{
		$this->db->order_by("req_receive.req_received_date",strtoupper($order));
	}

	public function order_by_order_date($order='ASC')
	{
		$this->db->order_by("req.order_req_date",strtoupper($order));
	}

	public function order_by_po_number($order='ASC')
	{
		$this->db->order_by("req.purchase_order_no",strtoupper($order));
	}

	public function order_by_confirmation_no($order='ASC')
	{
		$this->db->order_by("req.order_req_confirmation_no",strtoupper($order));
	}

	public function search_item_fields_purchase_order_lookup($search_value="")
	{
		$where = "(DATE_FORMAT(req_receive.req_received_date,'%m/%d/%Y') LIKE '%$search_value%' OR DATE_FORMAT(req.order_req_date,'%m/%d/%Y') LIKE '%$search_value%' OR vendor.vendor_name LIKE '%$search_value%' OR req.purchase_order_no LIKE '%$search_value%' OR req.order_req_confirmation_no LIKE '%$search_value%')";
		$this->db->where($where);
	}

	public function order_by_req_received_date($order='ASC')
	{
		$this->db->order_by("po_look_up.req_received_date",strtoupper($order));
	}

	public function order_by_po_order_date($order='ASC')
	{
		$this->db->order_by("po_look_up.order_date",strtoupper($order));
	}

	public function order_by_po_no($order='ASC')
	{
		$this->db->order_by("po_look_up.po_no",strtoupper($order));
	}

	public function order_by_name($order='ASC')
	{
		$this->db->order_by("po_look_up.name",strtoupper($order));
	}

	public function order_by_order_req_confirmation_no($order='ASC')
	{
		$this->db->order_by("po_look_up.order_req_confirmation_no",strtoupper($order));
	}

	public function search_item_fields_purchase_order_lookup_v2($search_value="")
	{
		$where = "(DATE_FORMAT(po_look_up.req_received_date,'%m/%d/%Y') LIKE '%$search_value%' OR DATE_FORMAT(po_look_up.order_date,'%m/%d/%Y') LIKE '%$search_value%' OR po_look_up.name LIKE '%$search_value%' OR po_look_up.po_no LIKE '%$search_value%' OR po_look_up.order_req_confirmation_no LIKE '%$search_value%')";
		$this->db->where($where);
	}

	// public function order_by_transfer_received_date($order='ASC')
	// {
	// 	// $this->db->order_by("req_receive.req_received_date",strtoupper($order));
	// }

	// public function order_by_transfer_order_date($order='ASC')
	// {
	// 	// $this->db->order_by("req_receive.req_received_date",strtoupper($order));
	// }

	// public function order_by_transfer_po_number($order='ASC')
	// {
	// 	// $this->db->order_by("req_receive.req_received_date",strtoupper($order));
	// }

	// public function order_by_transferring_location($order='ASC')
	// {
	// 	// $this->db->order_by("req_receive.req_received_date",strtoupper($order));
	// }

	// public function order_by_confirmation_no($order='ASC')
	// {
	// 	// $this->db->order_by("req_receive.req_received_date",strtoupper($order));
	// }

	// public function search_item_fields_purchase_order_lookup_equipment_transfer($search_value="")
	// {
	// 	$where = "(DATE_FORMAT(transfer_req.transfer_received_date,'%m/%d/%Y') LIKE '%$search_value%' OR DATE_FORMAT(transfer_req.equip_transfer_date,'%m/%d/%Y') LIKE '%$search_value%' OR location.user_city LIKE '%$search_value%' OR location.user_state LIKE '%$search_value%' OR transfer_req.transfer_po_no LIKE '%$search_value%'";
	// 	$this->db->where($where);
	// }

	public function search_item_fields($search_value="")
	{
		$where = "(item.company_item_no LIKE '%$search_value%' OR item.item_description LIKE '%$search_value%' OR vendor.vendor_name LIKE '%$search_value%' OR inventory_item.item_serial_no LIKE '%$search_value%' OR inventory_item.item_asset_no LIKE '%$search_value%' OR item_wh_location.item_warehouse_location LIKE '%$search_value%' OR inventory_item.item_status_location LIKE '%$search_value%')";
		$this->db->where($where);
	}

	public function search_item_fields_all($search_value="")
	{
		$where = "(item.company_item_no LIKE '%$search_value%' OR item.item_description LIKE '%$search_value%' OR vendor.vendor_name LIKE '%$search_value%' OR inventory_item.item_serial_no LIKE '%$search_value%' OR inventory_item.item_asset_no LIKE '%$search_value%' OR inventory_item.item_status_location LIKE '%$search_value%')";
		$this->db->where($where);
	}

	public function search_item_fields_run_item_no($search_value="")
	{
		$where = "(item.company_item_no LIKE '%$search_value%' OR item.item_description LIKE '%$search_value%' OR vendor.vendor_name LIKE '%$search_value%' OR item_wh_location.item_warehouse_location LIKE '%$search_value%' OR category.item_category_name LIKE '%$search_value%')";
		$this->db->where($where);
	}
}
