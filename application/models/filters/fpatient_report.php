<?php

class Fpatient_report extends CI_Model
{
	//parameter accepts from and to
	// e.g $date = array(
	//                 "from" => "01-14-15",
	//				   "to"   => "02-15-2016"
	//				)
	//
	public function date_range($data=array())
	{
		$current_date = date('Y-m-d');

		if(($data['from'] != "" && $data['to'] != ""))
		{
			$data['from']	= date("Y-m-d",strtotime($data['from']));
			$data['to'] 	= date("Y-m-d",strtotime($data['to']));

			if($data['from'] <= $data['to'])
			{
				if(($data['from'] == $current_date && $data['to'] == $current_date) || ($data['from'] == $current_date && $data['to'] > $data['from']))
				{
					// $this->db->where("DATE_FORMAT(orders.date_ordered,'%Y-%m-%d')",$current_date);
		            $this->db->where("orders.order_status", "active");
				}
				else if($data['to'] == $current_date || $data['to'] > $current_date)
				{
					$this->db->where("(orders.actual_order_date>='{$data['from']}' AND orders.actual_order_date<'{$current_date}')",null,false);
				}
				else if($data['to'] < $current_date)
				{
					$this->db->where("(orders.actual_order_date>='{$data['from']}' AND orders.actual_order_date<='{$data['to']}')",null,false);
				}
			}
			else
			{
				$this->db->where("(orders.actual_order_date>='{$data['from']}' AND orders.actual_order_date<='{$data['to']}')",null,false);
			}
		}

		if(!empty($data['hospiceID']) && $data['hospiceID'] != 0)
		{
			$this->db->where("orders.organization_id",$data['hospiceID']);
		}
	}

	public function date_range_v2($data=array())
	{
		$current_date = date('Y-m-d');

		if(($data['from'] != "" && $data['to'] != ""))
		{
			$data['from']	= date("Y-m-d",strtotime($data['from']));
			$data['to'] 	= date("Y-m-d",strtotime($data['to']));

			if($data['from'] == $current_date)
			{
				$this->db->where("orders.order_status", "active");
			}
			else
			{
				$this->db->where("orders.actual_order_date",$data['from']);
			}
		}

		if(!empty($data['hospiceID']) && $data['hospiceID'] != 0)
		{
			$this->db->where("orders.organization_id",$data['hospiceID']);
		}
	}

	public function date_range_v3($data=array())
	{
		$current_date = date('Y-m-d');

		if(($data['from'] != "" && $data['to'] != ""))
		{
			$data['from']	= date("Y-m-d",strtotime($data['from']));
			$data['to'] 	= date("Y-m-d",strtotime($data['to']));

			if($data['from'] == $current_date)
			{
				$this->db->where("orders.order_status", "active");
			}
			else
			{
				$start = date("Y-m-d",strtotime($data['from']." -1 days"));
				$end = date("Y-m-d",strtotime($data['from']." +1 days"));
				$final_start = strtotime($start." 23:59:59");
				$final_end = strtotime($end." 00:00:00");

				$this->db->where("orders.order_uniqueID > ",$final_start);
				$this->db->where("orders.order_uniqueID < ",$final_end);
			}
		}

		if(!empty($data['hospiceID']) && $data['hospiceID'] != 0)
		{
			$this->db->where("orders.organization_id",$data['hospiceID']);
		}
	}

	public function date_range_v4($data=array())
	{
		$current_date = date('Y-m-d');

		if(($data['from'] != "" && $data['to'] != ""))
		{
			$data['from']	= date("Y-m-d",strtotime($data['from']));
			$data['to'] 	= date("Y-m-d",strtotime($data['to']));

			if($data['from'] <= $data['to'])
			{
				// if(($data['from'] == $current_date && $data['to'] == $current_date) || ($data['from'] == $current_date && $data['to'] > $data['from']))
				// {
		  //           $this->db->where("orders.order_status", "active");
				// }
				if($data['to'] == $current_date || $data['to'] > $current_date)
				{
					$start = date("Y-m-d",strtotime($data['from']." -1 days"));
					$end = date("Y-m-d",strtotime($current_date));
					$final_start = strtotime($start." 23:59:59");
					$final_end = strtotime($end." 00:00:00");

					$this->db->where("orders.order_uniqueID > ",$final_start);
					$this->db->where("orders.order_uniqueID < ",$final_end);

					// $this->db->where("(actual_order_date>='{$data['from']}' AND actual_order_date<'{$current_date}')",null,false);
				}
				else if($data['to'] < $current_date)
				{
					$start = date("Y-m-d",strtotime($data['from']." -1 days"));
					$end = date("Y-m-d",strtotime($data['to']." +1 days"));
					$final_start = strtotime($start." 23:59:59");
					$final_end = strtotime($end." 00:00:00");

					$this->db->where("orders.order_uniqueID > ",$final_start);
					$this->db->where("orders.order_uniqueID < ",$final_end);

					// $this->db->where("(actual_order_date>='{$data['from']}' AND actual_order_date<='{$data['to']}')",null,false);
				}
			}
			else
			{
				$start = date("Y-m-d",strtotime($data['from']." -1 days"));
				$end = date("Y-m-d",strtotime($data['to']." +1 days"));
				$final_start = strtotime($start." 23:59:59");
				$final_end = strtotime($end." 00:00:00");

				$this->db->where("orders.order_uniqueID >= ",$final_start);
				$this->db->where("orders.order_uniqueID <= ",$final_end);

				// $this->db->where("(actual_order_date>='{$data['from']}' AND actual_order_date<='{$data['to']}')",null,false);
			}
		}

		if(!empty($data['hospiceID']) && $data['hospiceID'] != 0)
		{
			$this->db->where("orders.organization_id",$data['hospiceID']);
		}
	}


	// $this->db->where("(actual_order_date>'{$from}' AND actual_order_date<'{$to}')",null,false);
	// $from 	= date("Y-m-d",strtotime($data['from']." -1 days"));
	// $to 	= date("Y-m-d",strtotime($data['to']." +1 day"));
	// $this->db->where("(pickup_date BETWEEN '{$from}' AND '$to' AND pickup_date not in ($from, $to))",null,false);
	// $this->db->where("(actual_order_date BETWEEN '{$from}' AND '$to')",null,false);

	public function current_date($data=array())
	{
		// $current_date = date('Y-m-d');


		// $month = date("n");
		// $year  = date("Y");
		// $this->db->where("(MONTH(date_created)={$month} AND YEAR(date_created)={$year})",null,false);
		//SELECT * FROM `dme_patient` WHERE MONTH(date_created)=2 AND YEAR(date_created)=2016
	}
	public function date_range_newcustomer($data=array())
	{
		$current_date = date('Y-m-d');

		if(($data['from'] != "" && $data['to'] != ""))
		{
			$data['from']	= date("Y-m-d",strtotime($data['from']));
			$data['to'] 	= date("Y-m-d",strtotime($data['to']));

				$start = date("Y-m-d",strtotime($data['from']." -1 days"));
				$end = date("Y-m-d",strtotime($data['from']." +1 days"));
				$final_start = strtotime($start." 23:59:59");
				$final_end = strtotime($end." 00:00:00");

				$this->db->where("(pat.date_created > '{$start}' && pat.date_created < '{$end}')",null,false);
		}
		else{
			$data['from']	= date("Y-m-d",strtotime($current_date));
			$data['to'] 	= date("Y-m-d",strtotime($current_date));
			$start = date("Y-m-d",strtotime($data['from']." -1 days"));
			$end = date("Y-m-d",strtotime($data['from']." +1 days"));
			$this->db->where("(pat.date_created > '{$start}' && pat.date_created < '{$end}')",null,false);
		}

		if(!empty($data['hospiceID']) && $data['hospiceID'] != 0)
		{
			$this->db->where("pat.ordered_by",$data['hospiceID']);
		}
	}
}
