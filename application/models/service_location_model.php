<?php
	Class service_location_model extends Ci_Model
	{
		
		function create($data)
		{
			$this->db->insert('dme_location', $data);

			return $this->db->insert_id();
		}

		function get_all_service_location() {
        	$this->db->select('*');
        	$this->db->from('dme_location');
        	$query = $this->db->get();

        	return $query->result_array();
        }

        public function get_service_location_list($filters = false, $start = 0, $limit = 0)
        {
            $this->db->start_cache();
            if ($filters != false) {
                $this->load->library('orm/filters');
                $this->filters->detect('Service_location', $filters);
            }

            $this->db->select('*');
        	$this->db->from('dme_location as location');

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

        public function update_service_location($location_id, $data=array()) {
			$this->db->where('location_id', $location_id);
		    return $this->db->update('dme_location', $data); 
        }
	}
?>