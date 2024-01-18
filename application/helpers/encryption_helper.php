<?php 

	function get_hash($id){
		return substr(md5($id.'advantagehomemedicalservices'),0,5);
	}

	function get_code($id){
		return $id.'-'.get_hash($id);
	}

	function check_code($code){

		$temp = explode('-', $code);
		if(count($temp)!=2) return false;
		$lead_id = $temp[0];
		$checker = $temp[1];
		if(!is_numeric($lead_id)) return false;
		return (get_hash($lead_id) == $checker);
	}

	function get_id_from_code($code='')
	{
		$temp = explode('-', $code);
		return $temp[0];
	}

?>