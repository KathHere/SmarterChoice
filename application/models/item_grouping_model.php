<?php
	Class item_grouping_model extends Ci_Model
	{
		/*
        * Get all item groups sorted by ascending group name
        */
		public function get_all_item_groups()
        {
            $this->db->select('*');
            $this->db->from('dme_item_group as item_group');
            $this->db->order_by('item_group.item_group_name', 'ASC');

            $query = $this->db->get();

            return $query->result_array();
        }

        /*
        * Get specific item group details
        */
		public function get_item_group($item_group_id)
        {
            $this->db->select('*');
            $this->db->from('dme_item_group as item_group');
            $this->db->where('item_group.item_group_id', $item_group_id);

            $query = $this->db->get();

            return $query->first_row('array');
        }

        /*
        * Insert/add new item group
        */
        public function add_item_group($data = array())
        {
            $response = false;

            if (!empty($data)) {
                $save_info = $this->db->insert('dme_item_group', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }

            return $response;
        }

        /*
        * Update item group details
        */
        public function update_item_group_details($data, $item_group_id)
        {
            $this->db->where('item_group_id', $item_group_id);
            $response = $this->db->update('dme_item_group', $data);

            return $response;
        }

        /*
        * Delete item group permanently
        */
        public function delete_item_group($item_group_id)
        {
            $this->db->where('item_group_id', $item_group_id);
            $response = $this->db->delete('dme_item_group');

            return $response;
        }
	}

