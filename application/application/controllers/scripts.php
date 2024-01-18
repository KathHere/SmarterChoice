<?php 

/*
| @desc : all scripts or commandline related that connects to CI
|		  restricted to run on web browser
| @date : 02.04.2016
|
*/
class Scripts extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//disable access from any browser client
		if(isset($_SERVER['HTTP_USER_AGENT']) AND $_SERVER['HTTP_USER_AGENT']!="")
		{
			echo "not found.";
			exit;
		}
	}

	/*
	| @func : updatept_creationdate
	| @desc : update existing patient that doesn't have date of creation
	| @date : 02.04.2016
	|
	|
	*/
	public function updatept_creationdate()
	{
		$this->db->select("patientID,organization_id,date_ordered");
		$this->db->from("dme_order");
		$this->db->order_by("date_ordered","ASC");
		$this->db->group_by("patientID");
		$query = $this->db->get();

		$response = array();

		foreach($query->result_array() as $key=>$value)
		{
			$response[] = array(
							"patientID" 	=> $value['patientID'],
							"date_created" 	=> $value["date_ordered"],
							"hospice_id" 	=> $value["organization_id"]
						  );
		}
		//check if response is not empty then do update
		if(!empty($response))
		{
			//do update batch
			$do_update = $this->db->update_batch("dme_patient",$response,'patientID');
			if($do_update)
			{
				$this->common->code    = 0;
				$this->common->message = "Successfully updated.";
			}
			else
			{
				$this->common->message = "Failed to update";
			}
		}
		$this->common->response(false);
	}
}