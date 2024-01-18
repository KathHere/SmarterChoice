<?php
Class Mcommon extends CI_Model
{
	var $response = false;
	public function do_subscribe($data=array())
	{
		if(!empty($data))
		{
			$this->db->where("endpoint",$data['endpoint']);
			$this->db->where("owner",$data['owner']);
			$query = $this->db->get("dme_subscription");
			if($query->num_rows()<1)
			{
				$this->response = $this->db->insert("dme_subscription",$data);
			}
		}
		return $this->response;
	}
	public function do_unsubscribe($endpoint="")
	{
		if(!empty($endpoint))
		{
			$this->db->where("endpoint",$endpoint);
			$this->response = $this->db->delete("dme_subscription");
		}
		return $this->response;
	}
	public function get_subscribers()
	{
		$query = $this->db->get("dme_subscription");
		return $query->result_array();
	}
}