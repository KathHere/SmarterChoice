<?php
    class inventory_model extends Ci_Model
    {
        public $tables;

        /*
        * save item information
        */
        public function __construct()
        {
            $this->tables = new stdClass();
            $this->tables->inventory = array(
                                            'name' => 'dme_inventory_item',
                                            'pk' => 'inventory_item_id',
                                        );
        }

        public function save_item_information($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_item', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function save_item_warehouse_location_information($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_item_warehouse_location', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function save_item_par_level_information($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_item_par_level', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function get_item_par_levels()
        {
            $this->db->select('item.item_id,item.company_item_no,item.item_par_level');
            $this->db->from('dme_item as item');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_item_warehouse_locations()
        {
            $this->db->select('item.item_id,item.company_item_no,item.item_warehouse_location');
            $this->db->from('dme_item as item');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_vendor_item_info($company_item_no, $item_vendor, $item_reorder_no)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->where('item.company_item_no', $company_item_no);
            $this->db->where('item.item_vendor', $item_vendor);
            $this->db->where('item.item_reorder_no', $item_reorder_no);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_equipment_with_same_item_no($company_item_no)
        {
            $this->db->select('equip.equipmentID');
            $this->db->from('dme_equipment as equip');
            $this->db->where('equip.equipment_company_item_no', $company_item_no);
            $query = $this->db->get();

            return $query->result_array('');
        }

        public function get_item_with_same_item_no($company_item_no)
        {
            $this->db->select('item.item_id');
            $this->db->from('dme_item as item');
            $this->db->where('item.company_item_no', $company_item_no);
            $query = $this->db->get();

            return $query->result_array('');
        }

        public function update_equipment_company_item_no($data, $equipmentID)
        {
            $this->db->where('equipmentID', $equipmentID);
            $response = $this->db->update('dme_equipment', $data);

            return $response;
        }

        public function update_item_company_item_no($data, $item_id)
        {
            $this->db->where('item_id', $item_id);
            $response = $this->db->update('dme_item', $data);

            return $response;
        }

        public function save_item_unit($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_item_cost', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function save_order_req($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_order_requisition', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function save_order_req_receiving($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_order_requisition_receiving', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function save_order_req_payment($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_order_requisition_payment', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function save_order_req_items($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_inventory_item', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function get_item_category_list()
        {
            $this->db->select('*');
            $this->db->from('dme_item_category');
            $this->db->order_by('item_category_name', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_item_unit_measure_value($item_unit_measure, $item_id)
        {
            $this->db->select('item_cost.item_unit_value');
            $this->db->from('dme_item_cost as item_cost');
            $this->db->where('item_cost.item_id', $item_id);
            $this->db->where('item_cost.item_unit_measure', $item_unit_measure);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_item_serial_asset_no($inventory_item_id)
        {
            $this->db->select('inventory_item.item_asset_no,inventory_item.item_serial_no');
            $this->db->from('dme_inventory_item as inventory_item');
            $this->db->where('inventory_item.inventory_item_id', $inventory_item_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_item_unit_measure_value_v2($item_id)
        {
            $this->db->select('item_cost.item_unit_value,item_cost.item_unit_measure,item_cost.item_vendor_cost,item_cost.item_company_cost');
            $this->db->from('dme_item_cost as item_cost');
            $this->db->where('item_cost.item_id', $item_id);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_item_serial_asset_nos($item_id, $po_number, $order_req_id, $item_batch_no)
        {
            $this->db->select('inventory_item.item_serial_no,inventory_item.item_asset_no,inventory_item.inventory_item_id');
            $this->db->from('dme_inventory_item as inventory_item');
            $this->db->where('inventory_item.item_id', $item_id);
            $this->db->where('inventory_item.purchase_order_no', $po_number);
            $this->db->where('inventory_item.order_req_id', $order_req_id);
            $this->db->where('inventory_item.item_batch_no', $item_batch_no);
            $query = $this->db->get();

            return $query->result_array();
        }

        // function get_all_items()
        // {
        //  $this->db->select('item.item_id,item.company_item_no');
        //  $this->db->from('dme_item as item');
        //  $query = $this->db->get();

        //  return $query->result_array();
        // }

        // function update_data_company_item_no($data,$item_id)
        // {
        //  $this->db->where('item_id', $item_id);
        //  $response = $this->db->update('dme_item_cost', $data);

        //  return $response;
        // }

        public function update_data_new_item_cost($data, $item_unit_measure, $company_item_no)
        {
            $this->db->where('item_unit_measure', $item_unit_measure);
            $this->db->where('company_item_no', $company_item_no);
            $response = $this->db->update('dme_item_cost', $data);

            return $response;
        }

        public function get_item_unit_measure_value_v3($item_id)
        {
            $this->db->select('item_cost.item_unit_value,item_cost.item_unit_measure,item_cost.item_vendor_cost,item_cost.item_company_cost');
            $this->db->from('dme_item_cost as item_cost');
            $this->db->join('dme_item as item', 'item_cost.item_id = item.item_id');
            $this->db->where('item_cost.item_id', $item_id);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_items_through_company_item_no($company_item_no)
        {
            $this->db->select('item.item_id');
            $this->db->from('dme_item as item');
            $this->db->where('item.company_item_no', $company_item_no);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_items_through_company_item_no_v2($company_item_no)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->where('item.company_item_no', $company_item_no);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_items_through_company_item_no_v3($company_item_no)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_vendor as vendor', 'vendor.vendor_id=item.item_vendor');
            $this->db->where('item.company_item_no', $company_item_no);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_purchase_item_graph_sort_date_type($vendor_id, $from, $to)
        {
            $this->db->select('item.item_id,item.item_description,inventory_item.item_unit_measure');

            $this->db->from('dme_item as item');
            $this->db->join('dme_inventory_item as inventory_item', 'inventory_item.item_id=item.item_id');
            $this->db->join('dme_order_requisition as order_req', 'order_req.purchase_order_no=inventory_item.purchase_order_no');

            if (!empty($vendor_id)) {
                $this->db->where('item.item_vendor', $vendor_id);
            }

            if (empty($from) && empty($to)) {
                $current_day = date('Y-m-d');
                $final_start = date('Y-m-d', strtotime($current_day.' -1 days'));
                $final_end = date('Y-m-d', strtotime($current_day.' +1 days'));
            } else {
                $final_start = date('Y-m-d', strtotime($from.' -1 days'));
                $final_end = date('Y-m-d', strtotime($to.' +1 days'));
            }

            $this->db->where('order_req.order_req_date > ', $final_start);
            $this->db->where('order_req.order_req_date < ', $final_end);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_purchase_item_graph_sort_date_type_v2($vendor_id, $from, $to, $location)
        {
            $this->db->select('item.item_id,item.item_description,inventory_item.item_unit_measure');
            $this->db->from('dme_item as item');
            $this->db->join('dme_inventory_item as inventory_item', 'inventory_item.item_id=item.item_id');
            $this->db->join('dme_order_requisition as order_req', 'order_req.purchase_order_no=inventory_item.purchase_order_no');

            if (!empty($vendor_id)) {
                $this->db->where('item.item_vendor', $vendor_id);
            }

            if (empty($from) && empty($to)) {
                $current_day = date('Y-m-d');
                $final_start = date('Y-m-d', strtotime($current_day.' -1 days'));
                $final_end = date('Y-m-d', strtotime($current_day.' +1 days'));
            } else {
                $final_start = date('Y-m-d', strtotime($from.' -1 days'));
                $final_end = date('Y-m-d', strtotime($to.' +1 days'));
            }

            $this->db->where('order_req.order_req_date > ', $final_start);
            $this->db->where('order_req.order_req_date < ', $final_end);

            if ($location != 0) {
                $this->db->where('order_req.location', $location);
            } else {
                $this->db->where('order_req.location !=', 0);
            }
            
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_purchase_item_graph_sort_date_type_second_date($vendor_id, $from, $to)
        {
            $this->db->select('item.item_id,item.item_description,inventory_item.item_unit_measure');
            $this->db->from('dme_item as item');
            $this->db->join('dme_inventory_item as inventory_item', 'inventory_item.item_id=item.item_id');
            $this->db->join('dme_order_requisition as order_req', 'order_req.purchase_order_no=inventory_item.purchase_order_no');

            if (!empty($vendor_id)) {
                $this->db->where('item.item_vendor', $vendor_id);
            }

            if (empty($from) && empty($to)) {
                $current_day = date('Y-m-d');
                $final_start = date('Y-m-d', strtotime($current_day.' -1 days'));
                $final_end = date('Y-m-d', strtotime($current_day.' +1 days'));
            } else {
                $final_start = date('Y-m-d', strtotime($from.' -1 days'));
                $final_end = date('Y-m-d', strtotime($to.' +1 days'));
            }

            $this->db->where('order_req.order_req_date > ', $final_start);
            $this->db->where('order_req.order_req_date < ', $final_end);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_purchase_item_graph_sort_date_type_second_date_v2($vendor_id, $from, $to, $location)
        {
            $this->db->select('item.item_id,item.item_description,inventory_item.item_unit_measure');
            $this->db->from('dme_item as item');
            $this->db->join('dme_inventory_item as inventory_item', 'inventory_item.item_id=item.item_id');
            $this->db->join('dme_order_requisition as order_req', 'order_req.purchase_order_no=inventory_item.purchase_order_no');

            if (!empty($vendor_id)) {
                $this->db->where('item.item_vendor', $vendor_id);
            }

            if (empty($from) && empty($to)) {
                $current_day = date('Y-m-d');
                $final_start = date('Y-m-d', strtotime($current_day.' -1 days'));
                $final_end = date('Y-m-d', strtotime($current_day.' +1 days'));
            } else {
                $final_start = date('Y-m-d', strtotime($from.' -1 days'));
                $final_end = date('Y-m-d', strtotime($to.' +1 days'));
            }

            $this->db->where('order_req.order_req_date > ', $final_start);
            $this->db->where('order_req.order_req_date < ', $final_end);
            $this->db->where('order_req.location', $location);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function search_item($vendor_id, $search_value, $returncount = false)
        {
            $this->db->select('*');
            $this->db->from('dme_item');
            $this->db->join('dme_item_category as category', 'category.item_category_id=dme_item.item_category');

            $where = "(company_item_no LIKE '%$search_value%' OR item_description LIKE '%$search_value%')";
            $this->db->where($where);
            if (!empty($vendor_id)) {
                $this->db->where('item_vendor', $vendor_id);
            }
            $this->db->where('item_deleted_sign', 0);
            $this->db->order_by('item_description', 'ASC');
            $this->db->group_by('company_item_no');
            $query = $this->db->get();

            if ($returncount) {
                $count = $query->num_rows();

                return $count;
            } else {
                return $query->result_array();
            }
        }

        public function search_item_with_location($vendor_id, $item_location, $search_value, $returncount = false)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item_wh_location.item_id=item.item_id');
            $this->db->join('dme_item_category as category', 'category.item_category_id=item.item_category');

            $where = "(item.company_item_no LIKE '%$search_value%' OR item.item_description LIKE '%$search_value%')";
            $this->db->where($where);
            if (!empty($vendor_id)) {
                $this->db->where('item.item_vendor', $vendor_id);
            }
            $this->db->where('item.item_deleted_sign', 0);
            $this->db->where('item_wh_location.item_location', $item_location);
            $this->db->order_by('item.item_description', 'ASC');
            $this->db->group_by('item.company_item_no');
            $query = $this->db->get();

            if ($returncount) {
                $count = $query->num_rows();

                return $count;
            } else {
                return $query->result_array();
            }
        }

        public function search_item_with_location_v2($vendor_id, $item_location, $search_value)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item_wh_location.item_id=item.item_id');
            $this->db->join('dme_item_category as category', 'category.item_category_id=item.item_category');

            $where = "(item.company_item_no LIKE '%$search_value%' OR item.item_description LIKE '%$search_value%')";
            $this->db->where($where);
            if (!empty($vendor_id)) {
                $this->db->where('item.item_vendor', $vendor_id);
            }
            $this->db->where('item.item_deleted_sign', 0);
            $this->db->where('item_wh_location.item_location', $item_location);
            $this->db->order_by('item.item_description', 'ASC');
            $this->db->group_by('item.company_item_no');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function search_inventory_item($vendor_id, $search_value, $returncount = false)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');

            $where = "(company_item_no LIKE '%$search_value%' OR item_description LIKE '%$search_value%' OR item_asset_no LIKE '%$search_value%' OR item_serial_no LIKE '%$search_value%' OR item_reorder_no LIKE '%$search_value%')";
            $this->db->where($where);
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');
            if (!empty($vendor_id)) {
                $this->db->where('item_vendor', $vendor_id);
            }
            $this->db->where('inventory_item.inventory_item_status', 0);
            $this->db->order_by('item_description', 'ASC');
            if (!$returncount) {
                $this->db->limit(5);
            }
            $query = $this->db->get();

            if ($returncount) {
                $count = $query->num_rows();

                return $count;
            } else {
                return $query->result_array();
            }
        }

        public function search_inventory_item_v2($vendor_id, $search_value)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');

            $where = "(company_item_no LIKE '%$search_value%' OR item_description LIKE '%$search_value%' OR item_asset_no LIKE '%$search_value%' OR item_serial_no LIKE '%$search_value%' OR item_reorder_no LIKE '%$search_value%')";
            $this->db->where($where);
            $this->db->where('inventory_item.item_serial_no !=', '');
            $this->db->where('inventory_item.item_asset_no !=', '');
            if (!empty($vendor_id)) {
                $this->db->where('item.item_vendor', $vendor_id);
            }
            $this->db->where('inventory_item.inventory_item_status', 0);
            $this->db->order_by('item.item_description', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function search_inventory_item_v3($vendor_id, $search_value, $returncount = false, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');

            $where = "(company_item_no LIKE '%$search_value%' OR item_description LIKE '%$search_value%' OR item_asset_no LIKE '%$search_value%' OR item_serial_no LIKE '%$search_value%' OR item_reorder_no LIKE '%$search_value%')";
            $this->db->where($where);
            $this->db->where('inventory_item.item_serial_no !=', '');
            $this->db->where('inventory_item.item_asset_no !=', '');
            if (!empty($vendor_id)) {
                $this->db->where('item.item_vendor', $vendor_id);
            }
            $this->db->where('inventory_item.inventory_item_status', 0);
            $this->db->where('inventory_item.item_location', $item_location);
            $this->db->order_by('item.item_description', 'ASC');

            if (!$returncount) {
                $this->db->limit(5);
            }
            $query = $this->db->get();

            if ($returncount) {
                $count = $query->num_rows();

                return $count;
            } else {
                return $query->result_array();
            }
        }

        public function search_inventory_item_v4($vendor_id, $search_value, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item_wh_location.item_id=item.item_id');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');

            $where = "(item.company_item_no LIKE '%$search_value%' OR item.item_description LIKE '%$search_value%' OR item_asset_no LIKE '%$search_value%' OR item_serial_no LIKE '%$search_value%' OR item_reorder_no LIKE '%$search_value%')";
            $this->db->where($where);
            $this->db->where('inventory_item.item_serial_no !=', '');
            $this->db->where('inventory_item.item_asset_no !=', '');
            if (!empty($vendor_id)) {
                $this->db->where('item.item_vendor', $vendor_id);
            }
            $this->db->where('inventory_item.inventory_item_status', 0);
            $this->db->where('inventory_item.item_location', $item_location);
            $this->db->where('item_wh_location.item_location', $item_location);
            $this->db->order_by('item.item_description', 'ASC');
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function search_item_v2($vendor_id, $search_value)
        {
            $this->db->select('*');
            $this->db->from('dme_item');
            $this->db->join('dme_item_category as category', 'category.item_category_id=dme_item.item_category');

            $where = "(company_item_no LIKE '%$search_value%' OR item_description LIKE '%$search_value%')";
            $this->db->where($where);
            if (!empty($vendor_id)) {
                $this->db->where('item_vendor', $vendor_id);
            }
            $this->db->where('item_deleted_sign', 0);
            $this->db->order_by('item_description', 'ASC');
            $this->db->group_by('company_item_no');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function search_item_v2_with_item_location($vendor_id, $search_value, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item.item_id=item_wh_location.item_id');
            $this->db->join('dme_item_category as category', 'category.item_category_id=item.item_category');

            $where = "(item.company_item_no LIKE '%$search_value%' OR item.item_description LIKE '%$search_value%')";
            $this->db->where($where);
            if (!empty($vendor_id)) {
                $this->db->where('item.item_vendor', $vendor_id);
            }
            $this->db->where('item.item_deleted_sign', 0);
            $this->db->where('item_wh_location.item_location', $item_location);
            $this->db->order_by('item.item_description', 'ASC');
            $this->db->group_by('item.company_item_no');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_searched_item($item_id)
        {
            $this->db->select('*');
            $this->db->from('dme_item');
            $this->db->where('item_id', $item_id);
            $this->db->where('item_deleted_sign', 0);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_searched_item_with_item_location($item_id, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item.item_id=item_wh_location.item_id');
            $this->db->where('item.item_id', $item_id);
            $this->db->where('item.item_deleted_sign', 0);
            $this->db->where('item_wh_location.item_location', $item_location);
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_searched_inventory_item($item_id, $inventory_item_id)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->where('item.item_id', $item_id);
            $this->db->where('inventory_item.inventory_item_id', $inventory_item_id);
            $this->db->where('inventory_item.inventory_item_status', 0);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_searched_inventory_item_with_item_location($item_id, $inventory_item_id, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item_wh_location.item_id=item.item_id');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->where('item.item_id', $item_id);
            $this->db->where('inventory_item.inventory_item_id', $inventory_item_id);
            $this->db->where('inventory_item.inventory_item_status', 0);
            $this->db->where('item_wh_location.item_location', $item_location);
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_searched_item_v2($item_vendor = array(), $company_item_no)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->where('item.company_item_no', $company_item_no);
            if (!empty($item_vendor)) {
                $this->db->where('item.item_vendor', $item_vendor);
            }
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_searched_item_v4($item_vendor = '', $company_item_no, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item.item_id=item_wh_location.item_id');
            $this->db->join('dme_item_par_level as item_par_level', 'item.item_id=item_par_level.item_id');
            $this->db->where('item.company_item_no', $company_item_no);
            $this->db->where('item_wh_location.item_location', $item_location);
            if (!empty($item_vendor)) {
                $this->db->where('item.item_vendor', $item_vendor);
            }
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function check_item_asset_no_value($item_asset_no)
        {
            $this->db->select('*');
            $this->db->from('dme_inventory_item');
            $this->db->where('item_asset_no', $item_asset_no);
            $this->db->where('inventory_item_status', 0);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_searched_item_v3($item_vendor, $item_description)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->where('item.item_description', $item_description);
            $this->db->where('item.item_vendor', $item_vendor);
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_item_cost($item_id)
        {
            $this->db->select('*');
            $this->db->from('dme_item_cost');
            $this->db->where('item_id', $item_id);
            $query = $this->db->get();

            return $query->result_array();
        }

        /*
        * save vendor information
        */
        public function save_vendor_information($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_vendor', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function get_vendor_list()
        {
            $this->db->select('*');
            $this->db->from('dme_vendor');
            $this->db->order_by('vendor_name', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_item_list()
        {
            $this->db->select('*');
            $this->db->from('dme_item');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_vendor_items($vendor_id)
        {
            $this->db->select('*');
            $this->db->from('dme_item_category as item_category');
            $this->db->join('dme_item as items', 'item_category.item_category_id=items.item_category');
            $this->db->where('items.item_vendor', $vendor_id);
            $this->db->order_by('items.item_description', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_vendor_items_v2($vendor_id)
        {
            $this->db->select('*');
            $this->db->from('dme_item_category as item_category');
            $this->db->join('dme_item as items', 'item_category.item_category_id=items.item_category');
            $this->db->join('dme_item_par_level as item_par_level', 'items.item_id=item_par_level.item_id');
            $this->db->where('items.item_vendor', $vendor_id);
            $this->db->where('items.item_deleted_sign', 0);
            $this->db->order_by('items.item_description', 'ASC');
            $this->db->group_by('items.item_id');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_vendor_items_v3($vendor_id)
        {
            $this->db->select('*');
            $this->db->from('dme_item_category as item_category');
            $this->db->join('dme_item as items', 'item_category.item_category_id=items.item_category');
            $this->db->where('items.item_vendor', $vendor_id);
            $this->db->where('items.item_deleted_sign', 0);
            $this->db->order_by('items.item_description', 'ASC');
            $this->db->group_by('items.company_item_no');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_vendor_items_v3_with_item_location($vendor_id, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_item_category as item_category');
            $this->db->join('dme_item as items', 'item_category.item_category_id=items.item_category');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item_wh_location.item_id=items.item_id');
            $this->db->where('items.item_vendor', $vendor_id);
            $this->db->where('items.item_deleted_sign', 0);
            $this->db->where('item_wh_location.item_location', $item_location);
            $this->db->order_by('items.item_description', 'ASC');
            $this->db->group_by('items.company_item_no');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_vendor_details($vendor_id)
        {
            $this->db->select('*');
            $this->db->from('dme_vendor');
            $this->db->where('vendor_id', $vendor_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_item_details($item_id)
        {
            $this->db->select('*');
            $this->db->from('dme_item_category as item_category');
            $this->db->join('dme_item as item', 'item_category.item_category_id=item.item_category');
            $this->db->join('dme_vendor as vendor', 'vendor.vendor_id=item.item_vendor');
            $this->db->where('item.item_id', $item_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_item_details_with_item_location($item_id, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_item_category as item_category');
            $this->db->join('dme_item as item', 'item_category.item_category_id=item.item_category');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item_wh_location.item_id=item.item_id');
            $this->db->join('dme_item_par_level as item_par_level', 'item.item_id=item_par_level.item_id');
            $this->db->join('dme_vendor as vendor', 'vendor.vendor_id=item.item_vendor');
            $this->db->where('item.item_id', $item_id);
            $this->db->where('item_wh_location.item_location', $item_location);
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_item_reorder_nos_with_company_item_no($company_item_no)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->where('item.company_item_no', $company_item_no);
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->result_array('array');
        }

        public function get_item_reorder_nos_with_company_item_no_v2($vendor_id, $company_item_no)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_vendor as vendor', 'vendor.vendor_id=item.item_vendor');
            $this->db->where('item.company_item_no', $company_item_no);
            $this->db->where('vendor.vendor_id', $vendor_id);
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->result_array('array');
        }

        public function inventory_item_details($inventory_item_id)
        {
            $this->db->select('*');
            $this->db->from('dme_item_category as item_category');
            $this->db->join('dme_item as item', 'item_category.item_category_id=item.item_category');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_vendor as vendor', 'vendor.vendor_id=item.item_vendor');
            $this->db->where('inventory_item.inventory_item_id', $inventory_item_id);
            $this->db->where('inventory_item.inventory_item_status !=', 1);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function inventory_item_details_with_location($inventory_item_id, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_item_category as item_category');
            $this->db->join('dme_item as item', 'item_category.item_category_id=item.item_category');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item_wh_location.item_id=item.item_id');
            $this->db->join('dme_item_par_level as item_par_level', 'item.item_id=item_par_level.item_id');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_vendor as vendor', 'vendor.vendor_id=item.item_vendor');
            $this->db->where('inventory_item.inventory_item_id', $inventory_item_id);
            $this->db->where('item_wh_location.item_location', $item_location);
            $this->db->where('inventory_item.inventory_item_status !=', 1);
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_received_order_inquiries()
        {
            $this->db->select('req.*,vendor.vendor_name,req_receive.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_receiving as req_receive', 'req.order_req_id=req_receive.order_req_id');
            $this->db->where('req.order_req_status', 'received');
            $this->db->order_by('req_receive.req_receiving_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_received_order_inquiries_v2($location)
        {
            $this->db->select('req.*,vendor.vendor_name,req_receive.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_receiving as req_receive', 'req.order_req_id=req_receive.order_req_id');
            $this->db->where('req.order_req_status', 'received');

            if ($location != 0) {
                $this->db->where('req.location', $location);
            } else {
                $this->db->where('req.location !=', 0);
            }
            
            $this->db->order_by('req_receive.req_receiving_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_purchase_order_look_up($filters = false, $location, $start = 0, $limit = 0)
        {
            $this->db->start_cache();
            if ($filters != false) {
                $this->load->library('orm/filters');
                $this->filters->detect('inventory_items', $filters);
            }

            $this->db->select('*');
            $this->db->from('purchase_order_look_up as po_look_up');

            if ($location != 0) {
                $this->db->where('po_look_up.location', $location);
            } else {
                $this->db->where('po_look_up.location !=', 0);
            }
            
            $this->db->group_by('po_look_up.req_receiving_batch_no');
            $this->db->order_by('po_look_up.req_received_date', 'DESC');

            if ($limit != -1) {
                $this->db->limit($limit, $start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = $this->db->get()->num_rows();

            $this->db->flush_cache();

            return $response;
        }

        public function get_received_order_inquiries_v3($filters = false, $location, $start = 0, $limit = 0)
        {
            $this->db->start_cache();
            if ($filters != false) {
                $this->load->library('orm/filters');
                $this->filters->detect('inventory_items', $filters);
            }

            $this->db->select('req.*,vendor.vendor_name,req_receive.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_receiving as req_receive', 'req.order_req_id=req_receive.order_req_id');
            $this->db->where('req.order_req_status', 'received');
            $this->db->where('req.location', $location);
            $this->db->group_by('req_receive.req_receiving_batch_no');
            $this->db->order_by('req_receive.req_received_date', 'DESC');

            if ($limit != -1) {
                $this->db->limit($limit, $start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = $this->db->get()->num_rows();

            $this->db->flush_cache();

            return $response;
        }

        public function get_received_equipment_transfer($filters = false, $location, $start = 0, $limit = 0, $status)
        {
            $this->db->start_cache();
            if ($filters != false) {
                $this->load->library('orm/filters');
                $this->filters->detect('inventory_items', $filters);
            }

            $this->db->select('transfer_req.*, location.*, transfer_req_receive.req_received_date');
            $this->db->from('dme_equip_transfer_requisition_receiving as transfer_req_receive');
            $this->db->join('dme_equip_transfer_requisition as transfer_req', 'transfer_req_receive.transfer_req_id=transfer_req.transfer_req_id');
            $this->db->join('dme_location as location', 'transfer_req.transferring_location=location.location_id');
            $this->db->where('transfer_req.transfer_req_status', $status);
            $this->db->where('transfer_req.receiving_location', $location);
            $this->db->order_by('transfer_req_receive.req_received_date', 'DESC');

            if ($limit != -1) {
                $this->db->limit($limit, $start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = $this->db->get()->num_rows();

            $this->db->flush_cache();

            return $response;
        }

        public function get_order_inquiries()
        {
            $this->db->select('req.*,vendor.vendor_name,req_receive.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_receiving as req_receive', 'req.order_req_id=req_receive.order_req_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->where('req.order_req_status !=', 'received');
            $this->db->order_by('req_receive.req_receiving_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_inquiries_v2($location)
        {
            $this->db->select('req.*,vendor.vendor_name,req_receive.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_receiving as req_receive', 'req.order_req_id=req_receive.order_req_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->where('req.order_req_status !=', 'received');
            $this->db->where('req.location', $location);
            $this->db->order_by('req_receive.req_receiving_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_inquiries_v3($location, $draft_order_sign)
        {
            $this->db->select('req.*,vendor.vendor_name,req_receive.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_receiving as req_receive', 'req.order_req_id=req_receive.order_req_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->where('req.order_req_status !=', 'received');

            if ($location != 0) {
                $this->db->where('req.location', $location);
            } else {
                $this->db->where('req.location !=', 0);
            }
            
            $this->db->where('req.draft_order', $draft_order_sign);
            $this->db->order_by('req_receive.req_receiving_id', 'DESC');

            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_inquiries_payment()
        {
            $this->db->select('req.*,vendor.vendor_name,req_payment.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_payment as req_payment', 'req.order_req_id=req_payment.order_req_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->where('req_payment.req_payment_status !=', 'paid-po');
            $this->db->order_by('req.order_req_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_inquiries_payment_v2($location)
        {
            $this->db->select('req.*,vendor.vendor_name,req_payment.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_payment as req_payment', 'req.order_req_id=req_payment.order_req_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->where('req_payment.req_payment_status !=', 'paid-po');
            $this->db->where('req.location', $location);
            $this->db->order_by('req.order_req_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_inquiries_payment_v3($location)
        {
            $this->db->select('req.*,vendor.vendor_name,req_payment.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_payment as req_payment', 'req.order_req_id=req_payment.order_req_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->where('req_payment.req_payment_status !=', 'paid-po');

            if ($location != 0) {
                $this->db->where('req.location', $location);
            } else {
                $this->db->where('req.location !=', 0);
            }
            
            $this->db->where('req.draft_order', 0);
            $this->db->order_by('req.order_req_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_inquiries_bills()
        {
            $this->db->select('req.*,vendor.vendor_name,vendor.vendor_credit,vendor.vendor_id,req_payment.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_payment as req_payment', 'req.order_req_id=req_payment.order_req_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->where('req_payment.req_payment_status_sign', 0);
            $this->db->order_by('req.order_req_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_inquiries_bills_v2($location)
        {
            $this->db->select('req.*,vendor.vendor_name,vendor.vendor_credit,vendor.vendor_id,req_payment.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_payment as req_payment', 'req.order_req_id=req_payment.order_req_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->where('req_payment.req_payment_status_sign', 0);

            if ($location != 0) {
                $this->db->where('req.location', $location);
            } else {
                $this->db->where('req.location !=', 0);
            }
            
            $this->db->order_by('req.order_req_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_inquiries_bills_specific_vendor($vendor_id)
        {
            $this->db->select('req.*,vendor.vendor_name,req_payment.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_payment as req_payment', 'req.order_req_id=req_payment.order_req_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->where('req_payment.req_payment_status_sign', 0);
            $this->db->where('vendor.vendor_id', $vendor_id);
            $this->db->order_by('req.order_req_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_inquiries_payment_done()
        {
            $this->db->select('req.*,vendor.vendor_name,req_payment.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_payment as req_payment', 'req.order_req_id=req_payment.order_req_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->where('req_payment.req_payment_status_sign', 1);
            $this->db->order_by('req.order_req_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_inquiries_payment_done_v2($location)
        {
            $this->db->select('req.*,vendor.vendor_name,req_payment.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_payment as req_payment', 'req.order_req_id=req_payment.order_req_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->where('req_payment.req_payment_status_sign', 1);

            if ($location != 0) {
                $this->db->where('req.location', $location);
            } else {
                $this->db->where('req.location !=', 0);
            }
           
            $this->db->order_by('req.order_req_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_cancelled_order_req()
        {
            $this->db->select('*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_receiving as req_receive', 'req.order_req_id=req_receive.order_req_id');
            $this->db->where('req.order_req_status', 'cancelled');
            $this->db->order_by('req.order_req_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_cancelled_order_req_v2($location)
        {
            $this->db->select('*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_receiving as req_receive', 'req.order_req_id=req_receive.order_req_id');
            $this->db->where('req.order_req_status', 'cancelled');

            if ($location != 0) {
                $this->db->where('req.location', $location);
            } else {
                $this->db->where('req.location !=', 0);
            }
            
            $this->db->order_by('req.order_req_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function insert_new_order_req_receiving($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $response = $this->db->insert_batch('dme_order_requisition_receiving', $data);
            }

            return $response;
        }

        public function insert_item_warehouse_location_batch($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $response = $this->db->insert_batch('dme_item_warehouse_location', $data);
            }

            return $response;
        }

        public function insert_item_par_level_batch($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $response = $this->db->insert_batch('dme_item_par_level', $data);
            }

            return $response;
        }

        public function get_order_req_details($purchase_order_no, $order_req_id)
        {
            $this->db->select('vendor.*,req.*,req_receive.req_receiving_batch_no');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_receiving req_receive', 'req.order_req_id=req_receive.order_req_id');
            $this->db->where('req.purchase_order_no', $purchase_order_no);
            $this->db->where('req.order_req_id', $order_req_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_order_req_details_v1($purchase_order_no, $order_req_id, $req_receive_batch_no)
        {
            $this->db->select('vendor.*,req.*,req_receive.req_receiving_batch_no,req_receive.req_received_date,req_staff_member_receiving');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_receiving req_receive', 'req.order_req_id=req_receive.order_req_id');
            $this->db->where('req.purchase_order_no', $purchase_order_no);
            $this->db->where('req_receive.req_receiving_batch_no', $req_receive_batch_no);
            $this->db->where('req.order_req_id', $order_req_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_order_req_payment_details($purchase_order_no, $order_req_id, $req_payment_batch_no)
        {
            $this->db->select('vendor.*,req.*,req_payment.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_payment req_payment', 'req.order_req_id=req_payment.order_req_id');
            $this->db->where('req.purchase_order_no', $purchase_order_no);
            $this->db->where('req_payment.req_payment_batch_no', $req_payment_batch_no);
            $this->db->where('req.order_req_id', $order_req_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_order_inquiries_payment_specific_order($purchase_order_no, $order_req_id)
        {
            $this->db->select('vendor.*,req.*,req_payment.*');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_order_requisition as req', 'vendor.vendor_id=req.vendor_id');
            $this->db->join('dme_order_requisition_payment req_payment', 'req.order_req_id=req_payment.order_req_id');
            $this->db->where('req.purchase_order_no', $purchase_order_no);
            $this->db->where('req.order_req_id', $order_req_id);
            $this->db->order_by('req_payment_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_dme_locations()
        {
            $this->db->select('location.location_id');
            $this->db->from('dme_location as location');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_inventory_item_details($purchase_order_no, $item_id)
        {
            $this->db->select('*');
            $this->db->from('dme_inventory_item as item');
            $this->db->where('item.purchase_order_no', $purchase_order_no);
            $this->db->where('item.item_id', $item_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function delete_vendor_item($item_id = array(), $data = array())
        {
            $this->db->where('item_id', $item_id);
            $response = $this->db->update('dme_item', $data);

            return $response;
        }

        public function update_item_details($data_new_item_details, $company_item_no)
        {
            $this->db->where('company_item_no', $company_item_no);
            $response = $this->db->update('dme_item', $data_new_item_details);

            return $response;
        }

        public function update_item_warehouse_location_details($data, $company_item_no, $item_location)
        {
            $this->db->where('company_item_no', $company_item_no);
            $this->db->where('item_location', $item_location);
            $response = $this->db->update('dme_item_warehouse_location', $data);

            return $response;
        }

        public function update_item_par_level_details($data, $company_item_no, $item_location)
        {
            $this->db->where('company_item_no', $company_item_no);
            $this->db->where('item_location', $item_location);
            $response = $this->db->update('dme_item_par_level', $data);

            return $response;
        }

        public function update_item_location_information($data, $item_id, $item_location)
        {
            $this->db->where('item_id', $item_id);
            $this->db->where('item_location', $item_location);
            $response = $this->db->update('dme_item_warehouse_location', $data);

            return $response;
        }

        public function update_item_par_level($data, $item_id, $item_location)
        {
            $this->db->where('item_id', $item_id);
            $this->db->where('item_location', $item_location);
            $response = $this->db->update('dme_item_par_level', $data);

            return $response;
        }

        public function update_item_location($data)
        {
            $response = $this->db->update('dme_inventory_item', $data);

            return $response;
        }

        public function update_req_status_sign($purchase_order_no, $order_req_id, $data)
        {
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->where('order_req_id', $order_req_id);
            $response = $this->db->update('dme_order_requisition_payment', $data);

            return $response;
        }

        public function confirm_order_req_payment($purchase_order_no, $order_req_id, $req_payment_batch_no, $data)
        {
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->where('order_req_id', $order_req_id);
            $this->db->where('req_payment_batch_no', $req_payment_batch_no);
            $response = $this->db->update('dme_order_requisition_payment', $data);

            return $response;
        }

        public function insert_new_inventory_item($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_inventory_item', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function insert_new_inventory_item_batch($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert_batch('dme_inventory_item', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function new_order_req_payment($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_order_requisition_payment', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function get_receiving_datails_list($purchase_order_no, $order_req_id)
        {
            $this->db->select('*');
            $this->db->from('dme_order_requisition_receiving');
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->where('order_req_id', $order_req_id);
            $this->db->order_by('req_receiving_id', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_req_payment_list($order_req_id, $purchase_order_no)
        {
            $this->db->select('req_payment_id,payment_amount,ending_balance');
            $this->db->from('dme_order_requisition_payment');
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->where('order_req_id', $order_req_id);
            $this->db->order_by('req_payment_id', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function update_order_req_payment_list_batch($data = array())
        {
            $response = $this->db->update_batch('dme_order_requisition_payment', $data, 'req_payment_id');

            return $response;
        }

        public function update_inventory_item($order_req_id, $purchase_order_no, $item_id, $data)
        {
            $this->db->where('item_id', $item_id);
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->where('order_req_id', $order_req_id);
            $response = $this->db->update('dme_inventory_item', $data);

            return $response;
        }

        public function update_order_req_receiving_quantity_ordered($order_req_id, $purchase_order_no, $item_id, $data)
        {
            $this->db->where('item_id', $item_id);
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->where('order_req_id', $order_req_id);
            $response = $this->db->update('dme_order_requisition_receiving', $data);

            return $response;
        }

        public function change_inventory_item_status($inventory_item_id, $data)
        {
            $this->db->where('inventory_item_id', $inventory_item_id);
            $response = $this->db->update('dme_inventory_item', $data);

            return $response;
        }

        public function submit_edit_inventory_item($data = array(), $inventory_item_id)
        {
            $response = false;

            if (!empty($data)) {
                $this->db->where('inventory_item_id', $inventory_item_id);
                $response = $this->db->update('dme_inventory_item', $data);
            }

            return $response;
        }

        public function submit_inventory_item_removal($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_inventory_item_removed', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function get_order_requisition_list()
        {
            $this->db->select('vendor.vendor_name,req.order_req_date,req.purchase_order_no,req.order_req_grand_total,req.purchase_order_no,req.order_req_id,req.order_req_confirmation_no');
            $this->db->from('dme_order_requisition as req');
            $this->db->join('dme_vendor as vendor', 'req.vendor_id=vendor.vendor_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            $this->db->order_by('req.order_req_date', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_requisition_list_v2($location)
        {
            $this->db->select('vendor.vendor_name,req.order_req_date,req.purchase_order_no,req.order_req_grand_total,req.purchase_order_no,req.order_req_id,req.order_req_confirmation_no');
            $this->db->from('dme_order_requisition as req');
            $this->db->join('dme_vendor as vendor', 'req.vendor_id=vendor.vendor_id');
            $this->db->where('req.order_req_status !=', 'cancelled');

            if ($location !=0) {
                $this->db->where('req.location', $location);
            } else {
                $this->db->where('req.location !=', 0);
            }
            
            $this->db->order_by('req.order_req_date', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_requisition_list_filtered($vendor_id = array(), $from_date = array(), $to_date = array())
        {
            $this->db->select('vendor.vendor_name,req.order_req_date,req.purchase_order_no,req.order_req_grand_total,req.purchase_order_no,req.order_req_id,req.order_req_confirmation_no');
            $this->db->from('dme_order_requisition as req');
            $this->db->join('dme_vendor as vendor', 'req.vendor_id=vendor.vendor_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            if (!empty($vendor_id)) {
                $this->db->where('vendor.vendor_id', $vendor_id);
            }
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where("DATE_FORMAT(req.order_req_date,'%Y-%m-%d') >=", $from_date);
                $this->db->where("DATE_FORMAT(req.order_req_date,'%Y-%m-%d') <=", $to_date);
            }
            $this->db->order_by('req.order_req_date', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_requisition_list_filtered_v2($vendor_id = array(), $from_date = array(), $to_date = array(), $location)
        {
            $this->db->select('vendor.vendor_name,req.order_req_date,req.purchase_order_no,req.order_req_grand_total,req.purchase_order_no,req.order_req_id,req.order_req_confirmation_no');
            $this->db->from('dme_order_requisition as req');
            $this->db->join('dme_vendor as vendor', 'req.vendor_id=vendor.vendor_id');
            $this->db->where('req.order_req_status !=', 'cancelled');
            if (!empty($vendor_id)) {
                $this->db->where('vendor.vendor_id', $vendor_id);
            }
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where("DATE_FORMAT(req.order_req_date,'%Y-%m-%d') >=", $from_date);
                $this->db->where("DATE_FORMAT(req.order_req_date,'%Y-%m-%d') <=", $to_date);
            }

            if ($location != 0) {
                $this->db->where('req.location', $location);
            } else {
                $this->db->where('req.location !=', 0);
            }
            
            $this->db->order_by('req.order_req_date', 'DESC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_removed_item_list_v2()
        {
            $this->db->select('item.item_description,item.company_item_no,removed_item.date_out_of_service,removed_item.removal_reason,inventory_item.item_asset_no,inventory_item.item_cost,inventory_item.item_serial_no,inventory_item.inventory_item_id,vendor.vendor_name');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_inventory_item_removed as removed_item', 'inventory_item.inventory_item_id=removed_item.inventory_item_id');
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');
            $this->db->where('inventory_item.inventory_item_status', 1);
            $this->db->order_by('item_description', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_removed_item_list_v3($item_location)
        {
            $this->db->select('item.item_description,item.company_item_no,removed_item.date_out_of_service,removed_item.removal_reason,inventory_item.item_asset_no,inventory_item.item_cost,inventory_item.item_serial_no,inventory_item.inventory_item_id,vendor.vendor_name');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_inventory_item_removed as removed_item', 'inventory_item.inventory_item_id=removed_item.inventory_item_id');
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');
            $this->db->where('inventory_item.inventory_item_status', 1);

            if ($item_location != 0) {
                $this->db->where('inventory_item.item_location', $item_location);
            } else {
                $this->db->where('inventory_item.item_location !=', 0);
            }
            
            $this->db->order_by('item_description', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_removed_item_list_filtered($reason = array(), $from_date = array(), $to_date = array())
        {
            $this->db->select('item.item_description,item.company_item_no,removed_item.date_out_of_service,removed_item.removal_reason,inventory_item.item_asset_no,inventory_item.item_cost,inventory_item.item_serial_no,inventory_item.inventory_item_id,vendor.vendor_name');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_inventory_item_removed as removed_item', 'inventory_item.inventory_item_id=removed_item.inventory_item_id');
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');
            $this->db->where('inventory_item.inventory_item_status', 1);
            if (!empty($reason)) {
                $this->db->where('removed_item.removal_reason', $reason);
            }
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where("DATE_FORMAT(removed_item.date_out_of_service,'%Y-%m-%d') >=", $from_date);
                $this->db->where("DATE_FORMAT(removed_item.date_out_of_service,'%Y-%m-%d') <=", $to_date);
            }
            $this->db->order_by('item_description', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_removed_item_list_filtered_v2($reason = array(), $from_date = array(), $to_date = array(), $item_location)
        {
            $this->db->select('item.item_description,item.company_item_no,removed_item.date_out_of_service,removed_item.removal_reason,inventory_item.item_asset_no,inventory_item.item_cost,inventory_item.item_serial_no,inventory_item.inventory_item_id,vendor.vendor_name');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_inventory_item_removed as removed_item', 'inventory_item.inventory_item_id=removed_item.inventory_item_id');
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');
            $this->db->where('inventory_item.inventory_item_status', 1);
            if (!empty($reason)) {
                $this->db->where('removed_item.removal_reason', $reason);
            }
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where("DATE_FORMAT(removed_item.date_out_of_service,'%Y-%m-%d') >=", $from_date);
                $this->db->where("DATE_FORMAT(removed_item.date_out_of_service,'%Y-%m-%d') <=", $to_date);
            }

            if ($item_location != 0) {
                $this->db->where('inventory_item.item_location', $item_location);
            } else {
                $this->db->where('inventory_item.item_location !=', 0);
            }
            
            $this->db->order_by('item_description', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_inventory_item_list_no_filter_v2()
        {
            $this->db->select('item.item_description,item.company_item_no,item.item_id,inventory_item.inventory_item_id,inventory_item.item_unit_measure,inventory_item.purchase_order_no,inventory_item.item_quantity,inventory_item.item_total_cost,vendor.vendor_name,req.order_req_date,req.order_req_id');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_order_requisition as req', 'req.order_req_id=inventory_item.order_req_id');
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');
            $this->db->order_by('req.purchase_order_no', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_inventory_item_list_no_filter_v3($location, $from_date=array(), $to_date=array())
        {
            $this->db->select('item.item_description,item.company_item_no,item.item_id,inventory_item.inventory_item_id,inventory_item.item_unit_measure,inventory_item.purchase_order_no,inventory_item.item_quantity,inventory_item.item_total_cost,vendor.vendor_name,req.order_req_date,req.order_req_id');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_order_requisition as req', 'req.order_req_id=inventory_item.order_req_id');
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');

            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where("DATE_FORMAT(req.order_req_date,'%Y-%m-%d') >=", $from_date);
                $this->db->where("DATE_FORMAT(req.order_req_date,'%Y-%m-%d') <=", $to_date);
            }
            
            if ($location != 0) {
                $this->db->where('req.location', $location);
            } else {
                $this->db->where('req.location', 0);
            }
            
            $this->db->order_by('req.purchase_order_no', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_inventory_item_list_no_filter_v4($item_location)
        {
            $this->db->select('item.item_description,item.company_item_no,item_wh_location.item_warehouse_location,item.item_reorder_no,category.item_category_name,inventory_item.item_asset_no,inventory_item.item_status_location,inventory_item.item_serial_no,inventory_item.inventory_item_id,vendor.vendor_name');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item.item_id=item_wh_location.item_id');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_item_category as category', 'item.item_category=category.item_category_id');
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');
            $this->db->where('inventory_item.inventory_item_status', 0);

            if ($item_location != 0) {
                $this->db->where('inventory_item.item_location', $item_location);
            } else {
                $this->db->where('inventory_item.item_location !=', 0);
            }
            
            $this->db->order_by('item.item_description', 'ASC');
            $this->db->group_by('inventory_item.inventory_item_id');
            $this->db->limit(10);

            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_inventory_list($item_location)
        {
            $this->db->select('item.item_description,item.company_item_no,item_wh_location.item_warehouse_location,item.item_reorder_no,category.item_category_name,vendor.vendor_name');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item.item_id=item_wh_location.item_id');
            $this->db->join('dme_item_category as category', 'item.item_category=category.item_category_id');

            if ($item_location != 0) {
                $this->db->where('item_wh_location.item_location', $item_location);
            } else {
                $this->db->where('item_wh_location.item_location !=', 0);
            }
            
            $this->db->order_by('item.item_description', 'ASC');
            $this->db->group_by('item.company_item_no');
            $this->db->limit(10);

            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_inventory_item_list_no_filter_v5_count($item_location)
        {
            $this->db->select('inventory_item.inventory_item_id');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item.item_id=item_wh_location.item_id');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_item_category as category', 'item.item_category=category.item_category_id');
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');
            $this->db->where('inventory_item.inventory_item_status', 0);
            $this->db->where('inventory_item.item_location', $item_location);
            $this->db->order_by('item.item_description', 'ASC');
            $this->db->group_by('inventory_item.inventory_item_id');

            $query = $this->db->get();
            $count = $query->num_rows();

            return $count;
        }

        public function get_inventory_item_list_v5($filters = false, $item_location, $start = 0, $limit = 0)
        {
            $this->db->start_cache();
            if ($filters != false) {
                $this->load->library('orm/filters');
                $this->filters->detect('inventory_items', $filters);
            }

            $this->db->select('item.item_description,item.company_item_no,item_wh_location.item_warehouse_location,item.item_reorder_no,category.item_category_name,inventory_item.item_asset_no,inventory_item.item_status_location,inventory_item.item_serial_no,inventory_item.inventory_item_id,vendor.vendor_name');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item.item_id=item_wh_location.item_id');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_item_category as category', 'item.item_category=category.item_category_id');
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');
            $this->db->where('inventory_item.inventory_item_status', 0);

            if ($item_location != 0) {
                $this->db->where('inventory_item.item_location', $item_location);
            } else {
                $this->db->where('inventory_item.item_location !=', 0);
            }
            
            $this->db->order_by('item.item_description', 'ASC');
            $this->db->group_by('inventory_item.inventory_item_id');

            if ($limit != -1) {
                $this->db->limit($limit, $start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = $this->db->get()->num_rows();

            $this->db->flush_cache();

            return $response;
        }

        public function get_inventory_run_item_list($filters = false, $item_location, $start = 0, $limit = 0)
        {
            $this->db->start_cache();
            if ($filters != false) {
                $this->load->library('orm/filters');
                $this->filters->detect('inventory_items', $filters);
            }

            $this->db->select('item.item_description,item.company_item_no,item_wh_location.item_warehouse_location,item.item_reorder_no,category.item_category_name,vendor.vendor_name');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_item_warehouse_location as item_wh_location', 'item.item_id=item_wh_location.item_id');
            $this->db->join('dme_item_category as category', 'item.item_category=category.item_category_id');

            if ($item_location != 0) {
                $this->db->where('item_wh_location.item_location', $item_location);
            } else {
                $this->db->where('item_wh_location.item_location != ', 0);
            }
            
            $this->db->where('item.item_deleted_sign', 0);
            $this->db->order_by('item.item_description', 'ASC');
            // $this->db->group_by('item.company_item_no');

            if ($limit != -1) {
                $this->db->limit($limit, $start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = $this->db->get()->num_rows();

            $this->db->flush_cache();

            return $response;
        }

        public function get_searched_item_details($item_id)
        {
            $this->db->select('item.item_id,item.item_description');
            $this->db->from('dme_item as item');
            $this->db->where('item.item_id', $item_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function filter_purchase_item($item_id = array(), $from_date = array(), $to_date = array())
        {
            $this->db->select('item.item_description,item.company_item_no,item.item_id,inventory_item.inventory_item_id,inventory_item.item_unit_measure,inventory_item.purchase_order_no,inventory_item.item_quantity,inventory_item.item_total_cost,vendor.vendor_name,req.order_req_date,req.order_req_id');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_order_requisition as req', 'req.order_req_id=inventory_item.order_req_id');
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');
            if (!empty($item_id)) {
                $this->db->where('inventory_item.item_id', $item_id);
            }
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where("DATE_FORMAT(req.order_req_date,'%Y-%m-%d') >=", $from_date);
                $this->db->where("DATE_FORMAT(req.order_req_date,'%Y-%m-%d') <=", $to_date);
            }
            $this->db->order_by('req.purchase_order_no', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function filter_purchase_item_v2($item_id = array(), $from_date = array(), $to_date = array(), $location)
        {
            $this->db->select('item.item_description,item.company_item_no,item.item_id,inventory_item.inventory_item_id,inventory_item.item_unit_measure,inventory_item.purchase_order_no,inventory_item.item_quantity,inventory_item.item_total_cost,vendor.vendor_name,req.order_req_date,req.order_req_id');
            $this->db->from('dme_vendor as vendor');
            $this->db->join('dme_item as item', 'vendor.vendor_id=item.item_vendor');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->join('dme_order_requisition as req', 'req.order_req_id=inventory_item.order_req_id');
            $this->db->where('item_serial_no !=', '');
            $this->db->where('item_asset_no !=', '');
            if (!empty($item_id)) {
                $this->db->where('inventory_item.item_id', $item_id);
            }
            if (!empty($from_date) && !empty($to_date)) {
                $this->db->where("DATE_FORMAT(req.order_req_date,'%Y-%m-%d') >=", $from_date);
                $this->db->where("DATE_FORMAT(req.order_req_date,'%Y-%m-%d') <=", $to_date);
            }

            if ($location != 0) {
                $this->db->where('req.location', $location);
            } else {
                $this->db->where('req.location !=', 0);
            }
            
            $this->db->order_by('req.purchase_order_no', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_inventory_item_list($order_req_id, $purchase_order_no)
        {
            $this->db->select('inventory_item_id,item_id,item_serial_no,item_asset_no');
            $this->db->from('dme_inventory_item');
            $this->db->where('order_req_id', $order_req_id);
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->order_by('inventory_item_id', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_inventory_specific_item_list($item_id, $purchase_order_no)
        {
            $this->db->select('inventory_item_id,item_id,item_serial_no,item_asset_no');
            $this->db->from('dme_inventory_item');
            $this->db->where('item_id', $item_id);
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->order_by('inventory_item_id', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_req_receiving_list($purchase_order_no, $order_req_id)
        {
            $this->db->select('item_id,req_item_quantity_received');
            $this->db->from('dme_order_requisition_receiving');
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->where('order_req_id', $order_req_id);
            $this->db->order_by('req_receiving_id', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function delete_created_order_req($order_req_id)
        {
            $this->db->where('order_req_id', $order_req_id);
            $this->db->delete('dme_order_requisition');
        }

        public function delete_created_order_req_payment($order_req_id)
        {
            $this->db->where('order_req_id', $order_req_id);
            $this->db->delete('dme_order_requisition_payment');
        }

        public function delete_inventory_item($inventory_item_id)
        {
            $this->db->where('inventory_item_id', $inventory_item_id);
            $this->db->delete('dme_inventory_item');
        }

        public function delete_item_unit_of_measure($item_id)
        {
            $this->db->where('item_id', $item_id);
            $this->db->delete('dme_item_cost');
        }

        public function put_item_to_inventory($inventory_item_id)
        {
            $this->db->where('inventory_item_id', $inventory_item_id);
            $this->db->delete('dme_inventory_item_removed');
        }

        public function update_order_req_receiving($data = array())
        {
            $response = $this->db->update_batch('dme_order_requisition_receiving', $data, 'req_receiving_id');

            return $response;
        }

        public function get_order_req_details_v2($purchase_order_no, $order_req_id)
        {
            $this->db->select('*');
            $this->db->from('dme_vendor');
            $this->db->join('dme_order_requisition as order_req', 'dme_vendor.vendor_id=order_req.vendor_id');
            $this->db->where('order_req.purchase_order_no', $purchase_order_no);
            $this->db->where('order_req.order_req_id', $order_req_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function update_inventory_items($data, $item_id, $purchase_order_no)
        {
            $this->db->where('item_id', $item_id);
            $this->db->where('purchase_order_no', $purchase_order_no);
            $response = $this->db->update('dme_inventory_item', $data);

            return $response;
        }

        public function change_order_req_status($data, $purchase_order_no, $order_req_id)
        {
            $this->db->where('order_req_id', $order_req_id);
            $this->db->where('purchase_order_no', $purchase_order_no);
            $response = $this->db->update('dme_order_requisition', $data);

            return $response;
        }

        public function revert_draft_order_inventory($order_req_id, $data)
        {
            $this->db->where('order_req_id', $order_req_id);
            $response = $this->db->update('dme_order_requisition', $data);

            return $response;
        }

        public function delete_draft_order_inventory($order_req_id)
        {
            $this->db->where('order_req_id', $order_req_id);
            $response = $this->db->delete('dme_order_requisition');

            return $response;
        }

        public function edit_item_reorder_no($item_reorder_no, $data)
        {
            $this->db->where('item_reorder_no', $item_reorder_no);
            $response = $this->db->update('dme_item', $data);

            return $response;
        }

        public function delete_item_reorder_no($item_id)
        {
            $this->db->where('item_id', $item_id);
            $response = $this->db->delete('dme_item');

            return $response;
        }

        public function confirm_order_req($data, $order_req_id)
        {
            $this->db->where('order_req_id', $order_req_id);
            $response = $this->db->update('dme_order_requisition', $data);

            return $response;
        }

        public function get_item_quantity_ordered($purchase_order_no, $item_id, $qty_ordered)
        {
            $this->db->select('inventory_item_id,item_quantity,item_serial_no,item_asset_no');
            $this->db->from('dme_inventory_item');
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->where('item_id', $item_id);
            $this->db->where('item_id', $item_id);
            $this->db->where('item_serial_no', '');
            $this->db->where('item_asset_no', '');
            $this->db->limit($qty_ordered);

            $query = $this->db->get();

            return $query->result_array('');
        }

        public function save_serial_asset_no($data)
        {
            $response = $this->db->update_batch('dme_inventory_item', $data, 'inventory_item_id');

            return $response;
        }

        public function update_inventory_item_status($purchase_order_no, $item_id, $data)
        {
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->where('item_id', $item_id);
            $response = $this->db->update('dme_order_requisition_receiving', $data);

            return $response;
        }

        public function update_inventory_item_data($data, $item_id, $item_batch_no)
        {
            $this->db->where('item_id', $item_id);
            $this->db->where('item_batch_no', $item_batch_no);
            $response = $this->db->update('dme_inventory_item', $data);

            return $response;
        }

        public function get_order_item_details($purchase_order_no, $item_id)
        {
            $this->db->select('req.*');
            $this->db->from('dme_order_requisition_receiving req');
            $this->db->where('req.purchase_order_no', $purchase_order_no);
            $this->db->where('req.item_id', $item_id);
            $this->db->group_by('req.item_id');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_order_req_items($order_req_id, $req_receiving_batch_no)
        {
            $this->db->select('req.*,inventory_item.*,item.company_item_no,item.item_description,item.item_id,item.item_reorder_no,item_par_level.item_par_level,item.item_active_sign');
            $this->db->from('dme_order_requisition_receiving req');
            $this->db->join('dme_inventory_item inventory_item', 'inventory_item.item_id=req.item_id');
            $this->db->join('dme_item item', 'item.item_id=req.item_id');
            $this->db->join('dme_item_par_level as item_par_level', 'item.item_id=item_par_level.item_id');
            $this->db->where('req.order_req_id', $order_req_id);
            $this->db->where('req.req_receiving_batch_no', $req_receiving_batch_no);
            $this->db->where('inventory_item.order_req_id', $order_req_id);
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_req_items_v3($order_req_id, $req_receiving_batch_no)
        {
            $this->db->select('req.*,item.*');
            $this->db->from('dme_order_requisition_receiving req');
            $this->db->join('dme_item item', 'req.item_id=item.item_id');
            $this->db->where('req.order_req_id', $order_req_id);
            $this->db->where('req.req_receiving_batch_no', $req_receiving_batch_no);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_req_items_v4($order_req_id, $req_receiving_batch_no)
        {
            $this->db->select('req.*,item.*,item_par_level.item_par_level');
            $this->db->from('dme_order_requisition_receiving req');
            $this->db->join('dme_item item', 'req.item_id=item.item_id');
            $this->db->join('dme_item_par_level as item_par_level', 'item.item_id=item_par_level.item_id');
            $this->db->where('req.order_req_id', $order_req_id);
            $this->db->where('req.req_receiving_batch_no', $req_receiving_batch_no);
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_order_req_items_v2($order_req_id)
        {
            $this->db->select('inventory_item.*,item.company_item_no,item.item_description,item.item_id,item.item_reorder_no,item_par_level.item_par_level,item.item_active_sign');
            $this->db->from('dme_inventory_item inventory_item');
            $this->db->join('dme_item item', 'inventory_item.item_id=item.item_id');
            $this->db->join('dme_item_par_level as item_par_level', 'item.item_id=item_par_level.item_id');
            $this->db->where('inventory_item.order_req_id', $order_req_id);
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_req_receiving_first_batch_no($purchase_order_no, $order_req_id)
        {
            $this->db->select('req_receiving.req_receiving_batch_no');
            $this->db->from('dme_order_requisition_receiving as req_receiving');
            $this->db->where('req_receiving.order_req_id', $order_req_id);
            $this->db->where('req_receiving.purchase_order_no', $purchase_order_no);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_req_receiving_first_batch($purchase_order_no, $order_req_id, $req_receiving_batch_no)
        {
            $this->db->select('req_receiving.*,item.company_item_no,item.item_description,item.item_id,item.item_reorder_no,item.item_active_sign,item_par_level.item_par_level');
            $this->db->from('dme_order_requisition_receiving as req_receiving');
            $this->db->join('dme_item as item', 'req_receiving.item_id = item.item_id');
            $this->db->join('dme_item_par_level as item_par_level', 'item.item_id=item_par_level.item_id');
            $this->db->where('req_receiving.order_req_id', $order_req_id);
            $this->db->where('req_receiving.purchase_order_no', $purchase_order_no);
            $this->db->where('req_receiving.req_receiving_batch_no', $req_receiving_batch_no);
            $this->db->group_by('item.item_id');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_all_purchase_order_requisitions($start, $limit)
        {
            $this->db->select('*');
            $this->db->from('dme_order_requisition as requisition');

            $this->db->limit($limit, $start);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_data_inventory_items($order_req_id, $purchase_order_no)
        {
            $this->db->select('*');
            $this->db->from('dme_inventory_item as inventory_item');
            $this->db->where('inventory_item.order_req_id', $order_req_id);
            $this->db->where('inventory_item.purchase_order_no', $purchase_order_no);
            $this->db->group_by('inventory_item.item_id');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function update_purchase_order_requisition_receiving($order_req_id, $purchase_order_no, $item_id, $data)
        {
            $this->db->where('order_req_id', $order_req_id);
            $this->db->where('purchase_order_no', $purchase_order_no);
            $this->db->where('item_id', $item_id);
            $response = $this->db->update('dme_order_requisition_receiving', $data);

            return $response;
        }

        public function change_vendor_activation($data, $vendor_id)
        {
            $this->db->where('vendor_id', $vendor_id);
            $response = $this->db->update('dme_vendor', $data);

            return $response;
        }

        public function change_item_activation($data, $item_id)
        {
            $this->db->where('item_id', $item_id);
            $response = $this->db->update('dme_item', $data);

            return $response;
        }

        public function update_vendor_information($data, $vendor_id)
        {
            $this->db->where('vendor_id', $vendor_id);
            $response = $this->db->update('dme_vendor', $data);

            return $response;
        }

        public function update_item_information($data, $item_id)
        {
            $this->db->where('item_id', $item_id);
            $response = $this->db->update('dme_item', $data);

            return $response;
        }

        public function update_inventory_item_information($data, $inventory_item_id)
        {
            $this->db->where('inventory_item_id', $inventory_item_id);
            $response = $this->db->update('dme_inventory_item', $data);

            return $response;
        }

        public function search_vendor($search_value, $returncount = false)
        {
            $this->db->select('*');
            $this->db->from('dme_vendor');

            $where = "(vendor_name LIKE '%$search_value%' OR vendor_acct_no LIKE '%$search_value%')";
            $this->db->where($where);
            $this->db->order_by('vendor_name', 'ASC');

            if (!$returncount) {
                $this->db->limit(5);
            }
            $query = $this->db->get();

            if ($returncount) {
                $count = $query->num_rows();

                return $count;
            } else {
                return $query->result_array();
            }
        }

        public function get_item_on_hand($item_id, $order_req_id_array = array())
        {
            $this->db->select('*');
            $this->db->from('dme_inventory_item as inventory_item');
            $this->db->where('inventory_item.item_id', $item_id);
            $this->db->where('inventory_item.inventory_item_status', 0);
            if (!empty($order_req_id_array)) {
                $this->db->where_not_in('inventory_item.order_req_id', $order_req_id_array);
            }
            $query = $this->db->get();

            $count = $query->num_rows();

            return $count;
        }

        public function get_item_on_hand_list($item_id)
        {
            $this->db->select('*');
            $this->db->from('dme_order_requisition_receiving as req_receive');
            $this->db->where('req_receive.item_id', $item_id);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function search_vendor_v2($search_value)
        {
            $this->db->select('*');
            $this->db->from('dme_vendor');

            $where = "(vendor_name LIKE '%$search_value%')";
            $this->db->where($where);
            $this->db->order_by('vendor_name', 'ASC');

            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_searched_vendor($vendor_id)
        {
            $this->db->select('*');
            $this->db->from('dme_vendor');
            $this->db->where('vendor_id', $vendor_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_searched_vendor_name($vendor_id = array())
        {
            $this->db->select('vendor_name');
            $this->db->from('dme_vendor');
            if (!empty($vendor_id)) {
                $this->db->where('vendor_id', $vendor_id);
            }
            $query = $this->db->get();

            return $query->first_row('array');
        }

        /*
         * @function : mUpdateBasic()
         * @desc : direct update on the fields of the table
         * @params : id,field,value
         * @response : boolean
         */
        public function mUpdateBasic($id = '', $field = '', $value = '')
        {
            if (empty($id) or empty($field)) {
                return false;
            }
            $tosave = array($field => $value);
            $this->db->where($this->tables->inventory['pk'], $id);

            return $this->db->update($this->tables->inventory['name'], $tosave);
        }

        public function mUpdateReorderNumber($id = '', $field = '', $value = '')
        {
            if (empty($id) or empty($field)) {
                return false;
            }
            $tosave = array($field => $value);
            $this->db->where('item_id', $id);

            return $this->db->update('dme_item', $tosave);
        }

        //@EQUIPMENT TRANSFER REQUISITION
        //Added by: Adrian
        //Added on: 11/19/18
        //----------------------------------------------

        public function get_location_list()
        {
            $this->db->select('*');
            $this->db->from('dme_location');
            $this->db->order_by('user_city', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_searched_item_equip_transfer_by_company_item_no($company_item_no, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_vendor as vendor', 'item.item_vendor = vendor.vendor_id');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->where('item.company_item_no', $company_item_no);
            $this->db->where('inventory_item.item_location', $item_location);
            $this->db->group_by('item.item_vendor');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_searched_item_equip_transfer_by_item_description($item_description, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->join('dme_vendor as vendor', 'item.item_vendor = vendor.vendor_id');
            $this->db->join('dme_inventory_item as inventory_item', 'item.item_id=inventory_item.item_id');
            $this->db->where('item.item_description', $item_description);
            $this->db->where('inventory_item.item_location', $item_location);
            $this->db->group_by('item.item_vendor');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_all_item_warehouse_location()
        {
            $this->db->select('*');
            $this->db->from('dme_item_warehouse_location');
            //$this->db->limit(4);
            $this->db->limit(100, 801);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_inventory_item_by_location($company_item_no, $item_location)
        {
            $this->db->select('*');
            $this->db->from('dme_inventory_item as inventory_item');
            $this->db->join('dme_item as item', 'inventory_item.item_id = item.item_id');
            $this->db->where('item.company_item_no', $company_item_no);
            $this->db->where('inventory_item.item_location', $item_location);
            $this->db->where('inventory_item.inventory_item_status', 0);

            $query = $this->db->get();

            return $query->result_array();
        }

        public function save_inventory_item_quantity($data = array())
        {
            $this->db->where('item_warehouse_location_id', $data['item_warehouse_location_id']);
            $response = $this->db->update('dme_item_warehouse_location', $data);

            return $response;
        }

        public function get_reorder_no_by_vendor($company_item_no, $vendor_id)
        {
            $this->db->select('*');
            $this->db->from('dme_item as item');
            $this->db->where('item.company_item_no', $company_item_no);
            $this->db->where('item.item_vendor', $vendor_id);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_item_unit_measurement_by_item_id($item_id)
        {
            $this->db->select('inventory_item.item_unit_measure');
            $this->db->from('dme_inventory_item as inventory_item');
            $this->db->where('inventory_item.item_id', $item_id);
            $this->db->where('inventory_item.inventory_item_status', 0);
            $this->db->group_by('inventory_item.item_unit_measure');

            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_serial_asset_no_by_item_id($item_id, $item_unit_measurement, $item_location)
        {
            $this->db->select('inventory_item.inventory_item_id, inventory_item.item_cost, inventory_item.item_unit_measure, inventory_item.item_serial_no, inventory_item.item_asset_no');
            $this->db->from('dme_inventory_item as inventory_item');
            $this->db->where('inventory_item.item_unit_measure', $item_unit_measurement);
            $this->db->where('inventory_item.item_id', $item_id);
            $this->db->where('inventory_item.inventory_item_status', 0);
            $this->db->where('inventory_item.onhold_status', 0);
            $this->db->where('inventory_item.item_location', $item_location);

            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_item_cost_by_item_id($company_item_no, $item_unit_measure, $item_id)
        {
            $this->db->select('item_vendor_cost');
            $this->db->from('dme_item_cost');
            $this->db->where('company_item_no', $company_item_no);
            $this->db->where('item_unit_measure', $item_unit_measure);
            $this->db->where('item_id', $item_id);

            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function save_equip_transfer_order($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_equip_transfer_requisition', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function update_inventory_item_status_etr($inventory_item_id, $data = array())
        {
            $this->db->where('inventory_item_id', $inventory_item_id);
            $response = $this->db->update('dme_inventory_item', $data);

            return $response;
        }

        public function get_inventory_item_by_id($inventory_item_id)
        {
            $this->db->select('*');
            $this->db->from('dme_inventory_item');
            $this->db->where('inventory_item_id', $inventory_item_id);

            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function save_inventory_item_etr($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_inventory_item', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        // public function get_all_equip_transfer_status()
        // {
        //     $this->db->select('*');
        //     $this->db->from('dme_equip_transfer_requisition');
        //     $this->db->order_by('transfer_req_id', 'DESC');
        //     $query = $this->db->get();

        //     return $query->first_row('array');
        // }

        public function get_equipment_transfer_receiving($location)
        {
            $this->db->select('transfer_req.*,transfer_req_receive.*');
            $this->db->from('dme_equip_transfer_requisition as transfer_req');
            $this->db->join('dme_equip_transfer_requisition_receiving as transfer_req_receive', 'transfer_req.transfer_req_id=transfer_req_receive.transfer_req_id');
            $this->db->where('transfer_req.transfer_req_status !=', 'cancelled');
            $this->db->where('transfer_req.transfer_req_status !=', 'received');
            $this->db->where('transfer_req.receiving_location', $location);
            $this->db->order_by('transfer_req.equip_transfer_date', 'ASC');

            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_equipment_transfer_receiving_v2($location)
        {
            $this->db->select('transfer_req.*,transfer_req_receive.*');
            $this->db->from('dme_equip_transfer_requisition as transfer_req');
            $this->db->join('dme_equip_transfer_requisition_receiving as transfer_req_receive', 'transfer_req.transfer_req_id=transfer_req_receive.transfer_req_id');
            $this->db->where('transfer_req.transfer_req_status', 'received');
            $this->db->where('transfer_req.receiving_location', $location);
            $this->db->order_by('transfer_req.equip_transfer_date', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_equipment_transfer_receiving_v3($location, $status)
        {
            $this->db->select('transfer_req.*, location.*');
            $this->db->from('dme_equip_transfer_requisition as transfer_req');
            $this->db->join('dme_location as location', 'transfer_req.transferring_location=location.location_id');
            $this->db->where('transfer_req.transfer_req_status', $status);

            if ($location != 0) {
                $this->db->where('transfer_req.receiving_location', $location);
            } else {
                $this->db->where('transfer_req.receiving_location !=', 0);
            }
            
            $this->db->order_by('transfer_req.equip_transfer_date', 'ASC');

            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_all_equip_transfer_status($location)
        {
            $this->db->select('transfer_req.*, location.*');
            $this->db->from('dme_equip_transfer_requisition as transfer_req');
            $this->db->join('dme_location as location', 'transfer_req.receiving_location=location.location_id');

            if ($location != 0) {
                $this->db->where('transfer_req.transferring_location', $location);
            } else {
                $this->db->where('transfer_req.transferring_location !=', 0);
            }
            
            $this->db->order_by('transfer_req.date_added', 'DESC');
            $this->db->limit(10);

            $query = $this->db->get();

            return $query->result_array();
        }

        public function save_equip_transfer_item($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_equip_transfer_requisition_receiving', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function get_equip_transfer_by_id($transfer_req_id)
        {
            $this->db->select('transfer_req.*');
            $this->db->from('dme_equip_transfer_requisition as transfer_req');
            $this->db->where('transfer_req.transfer_req_id', $transfer_req_id);

            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_equip_transfer_items_by_etr_no($transfer_req_id, $transfer_po_no, $item_id)
        {
            $this->db->select('inv_item.*, item.company_item_no, item.item_reorder_no, item.item_description, vendor.vendor_name');
            $this->db->from('dme_inventory_item as inv_item');
            $this->db->join('dme_item as item', 'inv_item.item_id=item.item_id');
            $this->db->join('dme_vendor as vendor', 'item.item_vendor=vendor.vendor_id');
            $this->db->where('inv_item.transfer_req_id', $transfer_req_id);
            $this->db->where('inv_item.purchase_order_no', $transfer_po_no);
            $this->db->where('inv_item.item_id', $item_id);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function get_equip_transfer_receiving_details_by_id($transfer_req_id)
        {
            $this->db->select('*');
            $this->db->from('dme_equip_transfer_requisition_receiving as etr_receiving');
            $this->db->where('transfer_req_id', $transfer_req_id);
            $query = $this->db->get();

            return $query->result_array();
        }

        public function delete_equip_transfer_req($transfer_req_id)
        {
            $this->db->where('transfer_req_id', $transfer_req_id);
            $response = $this->db->delete('dme_equip_transfer_requisition');

            return $response;
        }

        public function delete_equip_transfer_req_receiving($transfer_req_id)
        {
            $this->db->where('transfer_req_id', $transfer_req_id);
            $this->db->delete('dme_equip_transfer_requisition_receiving');
        }

        public function delete_equip_transfer_req_items($transfer_req_id)
        {
            $this->db->where('transfer_req_id', $transfer_req_id);
            $this->db->delete('dme_inventory_item');
        }

        public function cancel_equip_transfer_req_items($item_id, $item_asset_no, $data = array())
        {
            $this->db->where('item_id', $item_id);
            $this->db->where('item_asset_no', $item_asset_no);
            $response = $this->db->update('dme_inventory_item', $data);

            return $response;
        }

        public function update_equip_transfer_req($transfer_req_id, $data = array())
        {
            $this->db->where('transfer_req_id', $transfer_req_id);
            $response = $this->db->update('dme_equip_transfer_requisition', $data);

            return $response;
        }

        public function update_equip_transfer_req_receiving($transfer_req_receiving_id, $data = array())
        {
            $this->db->where('transfer_req_receiving_id', $transfer_req_receiving_id);
            $response = $this->db->update('dme_equip_transfer_requisition_receiving', $data);

            return $response;
        }

        public function update_inventory_item_status_etr_v2($asset_no, $item_no, $transferring_location, $data = array())
        {
            $this->db->where('item_asset_no', $asset_no);
            $this->db->where('item_id', $item_no);
            $this->db->where('item_location', $transferring_location);
            $response = $this->db->update('dme_inventory_item', $data);

            return $response;
        }

        public function update_inventory_item_status_etr_v3($inventory_item_id, $data = array())
        {
            $this->db->where('inventory_item_id', $inventory_item_id);
            $response = $this->db->update('dme_inventory_item', $data);

            return $response;
        }

        public function update_new_inventory_item_status_etr($transfer_req_id, $asset_no, $data = array())
        {
            $this->db->where('transfer_req_id', $transfer_req_id);
            $this->db->where('item_asset_no', $asset_no);
            $response = $this->db->update('dme_inventory_item', $data);

            return $response;
        }

        public function get_equip_transfer_list($filters = false, $location, $start = 0, $limit = 0)
        {
            $this->db->start_cache();
            if ($filters != false) {
                $this->load->library('orm/filters');
                $this->filters->detect('equip_transfer_requisition', $filters);
            }

            $this->db->select('transfer_req.*, location.*');
            $this->db->from('dme_equip_transfer_requisition as transfer_req');
            $this->db->join('dme_location as location', 'transfer_req.receiving_location=location.location_id');

            if ($location != 0) {
                $this->db->where('transfer_req.transferring_location', $location);
            } else {
                $this->db->where('transfer_req.transferring_location !=', 0);
            }
            
            $this->db->order_by('transfer_req.date_added', 'DESC');

            if ($limit != -1) {
                $this->db->limit($limit, $start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = $this->db->get()->num_rows();

            $this->db->flush_cache();

            return $response;
        }

        public function get_equip_transfer_item_no($item_batch_no, $item_asset_no, $item_id)
        {
            $this->db->select('inv_item.inventory_item_id');
            $this->db->from('dme_inventory_item as inv_item');
            $this->db->where('inv_item.item_batch_no', $item_batch_no);
            $this->db->where('inv_item.item_asset_no', $item_asset_no);
            $this->db->where('inv_item.item_id', $item_id);
            $this->db->where('inv_item.transfer_req_id IS NULL', null, false);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function get_all_inventory_items($filters = false, $item_location, $start = 0, $limit = 0)
        {
            $this->db->start_cache();
            if ($filters != false) {
                $this->load->library('orm/filters');
                $this->filters->detect('inventory_items', $filters);
            }

            $this->db->select('*');
            $this->db->from('dme_inventory_item as inventory_item');
            $this->db->join('dme_item as item', 'inventory_item.item_id=item.item_id');
            $this->db->join('dme_vendor as vendor', 'item.item_vendor = vendor.vendor_id');
            $this->db->where('inventory_item.inventory_item_status', 0);
            $this->db->where('inventory_item.onhold_status', 0);
            $this->db->where('inventory_item.item_location', $item_location);

            if ($limit != -1) {
                $this->db->limit($limit, $start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = $this->db->get()->num_rows();

            $this->db->flush_cache();

            return $response;
        }

        //----------------------------------------------
        //END OF EQUIPMENT TRANSFER REQUISITION


        // Add to hospice list 2021 ---- START
        public function get_dme_hospices_with_uniqueID($account_location)
        {
            $this->db->select('hospice.hospiceID, hospice.hospice_name, assigned_equip.uniqueID');
            $this->db->from('dme_hospice as hospice');
            $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.hospiceID = hospice.hospiceID', 'left');
            $this->db->where('hospice.account_location', $account_location);
            $this->db->group_by("hospice.hospiceID");
            $query = $this->db->get();

            return $query->result_array();
        }

        public function insert_new_equipment($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_equipment', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function insert_new_assigned_equipment($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_assigned_equipment', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        public function get_equipment_details($company_item_no)
        {
            $this->db->select('*');
            $this->db->from('dme_equipment as equipment');
            $this->db->where('equipment.equipment_company_item_no', $company_item_no);
            // $this->db->where('equipment.categoryID', 2);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        public function update_equipment_company_item_no_v2($data, $company_item_no)
        {
            $this->db->where('equipment_company_item_no', $company_item_no);
            $response = $this->db->update('dme_equipment', $data);

            return $response;
        }

        // Add to hospice list 2021 ---- END
    }
