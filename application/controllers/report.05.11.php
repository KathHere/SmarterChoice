<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ;?>
<?php
	Class report extends Ci_Controller
	{
		var $include_top = 'landingpage_template/include_top';
		var $header = 'landingpage_template/header';
		var $client_header = 'client_header';
		var $register = 'register_form';
		var $login = 'login';
		var $footer = 'landingpage_template/footer';
		var $include_bottom = 'landingpage_template/include_bottom';
		var $login_header = 'login_header';
		var $main_header = 'main_template/header';
		
		var $response_code = 1;//false or error default
		var $response_message = "";
		var $response_data = array();
		var $records = array(
							"General",
							"All Records",
							"Yesterday",
							"Last 7 days",
							"Last 15 days",
							"Last 30 days"
						);
		var $data = array();
		public function __construct()
		{
			parent::__construct();
			is_logged_in();
			$this->load->model('user_model');
			$this->load->model('hospice_model');
			$this->load->model('mreport');
			date_default_timezone_set('America/Los_Angeles');
		}

		public function index()
		{
			$this->templating_library->set('title','Reports');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');
			
			$this->load->model("order_model");
			$result = $this->order_model->list_active_patients();
			$data['total_patients'] = count($result);

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$total_los_today =  get_total_patient_los_current_date_hopice_v2($this->session->userdata('group_id'),$this->session->userdata('user_location'));
				$total_patient_days_today =  get_total_patient_days_current_date_hospice_v2($this->session->userdata('group_id'),$this->session->userdata('user_location'));
			}
			else
			{
				$total_los_today =  get_total_patient_los_current_date_v2($this->session->userdata('user_location'));
				$total_patient_days_today =  get_total_patient_days_current_date_v2($this->session->userdata('user_location'));
			}
			if(empty($total_los_today['total_patient_los']))
			{	
				$data['total_patient_los'] = 0;
			}
			else
			{
				$data['total_patient_los'] = $total_los_today['total_patient_los'];
			}
			if(empty($total_patient_days_today['total_patient_days']))
			{	
				$data['total_patient_days'] = 0;
			}
			else
			{
				$data['total_patient_days'] = $total_patient_days_today['total_patient_days'];
			}
			
			$this->templating_library->set_view('pages/report','pages/report', $data);
			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}
		/*
		| @func : ACTIVITY STATUS REPORT
		| @desc : activity status report monthly and daily
		|
		*/
		public function activity_report_month()
		{
			$pt_filter 	=  array();
			$date_fetch = fetch_report_date();

			if($date_fetch['from']=="" && $date_fetch['to']=="")
			{
				$pt_filter['current_date'] = "";
			}
			else
			{
				if(isset($pt_filter['current_date']))
				{
					unset($pt_filter['current_date']);
				}
				$pt_filter = array("date_range"=>$date_fetch);
			}

			$new_pt = 0;
	  		$exchange = 0;
	  		$pickup = 0;
	  		$ptmove = 0;
	  		$respite = 0;
	  		$patient_days = 0;
	  		$patient_los = 0;

	  		/* 
	  		*** THIS IS FOR THE CUSTOMER DAYS AND PATIENT LENGTH OF STAY 
	  		*/
	  		if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to']))
	  		{
	  			if($this->session->userdata('account_type') == 'dme_admin')
				{	
					if(!empty($pt_filter['date_range']['hospiceID']))
					{
						$selected_hospiceID = $pt_filter['date_range']['hospiceID'];
					}
					else
					{
						$selected_hospiceID = "";
					}
					$total_los_today =  get_total_patient_los_specific_date_v2($pt_filter['date_range']['from'],$pt_filter['date_range']['to'],$selected_hospiceID,$this->session->userdata('user_location'));
					if(empty($total_los_today['total_patient_los']))
					{	
						$patient_los = 0;
					}
					else
					{
						$patient_los = $total_los_today['total_patient_los'];
					}
					
					$total_patient_days_today =  get_total_patient_days_specific_date_v2($pt_filter['date_range']['from'],$pt_filter['date_range']['to'],$selected_hospiceID,$this->session->userdata('user_location'));

					if(empty($total_patient_days_today['total_patient_days']))
					{	
						$patient_days = 0;
					}
					else
					{
						$patient_days = $total_patient_days_today['total_patient_days'];
					}	
				}
				else
				{
					$total_los_today =  get_total_patient_los_specific_date_v2($pt_filter['date_range']['from'],$pt_filter['date_range']['to'],$this->session->userdata('group_id'),$this->session->userdata('user_location'));
					if(empty($total_los_today['total_patient_los']))
					{	
						$patient_los = 0;
					}
					else
					{
						$patient_los = $total_los_today['total_patient_los'];
					}
					
					$total_patient_days_today =  get_total_patient_days_specific_date_v2($pt_filter['date_range']['from'],$pt_filter['date_range']['to'],$this->session->userdata('group_id'),$this->session->userdata('user_location'));
					if(empty($total_patient_days_today['total_patient_days']))
					{	
						$patient_days = 0;
					}
					else
					{
						$patient_days = $total_patient_days_today['total_patient_days'];
					}	
				}	
	  		}
			else
			{
				if($this->session->userdata('account_type') == 'dme_admin')
				{
					if($date_fetch['hospiceID'] == 0)
					{
						$total_los_today =  get_total_patient_los_current_date_v2($this->session->userdata('user_location'));
						$total_patient_days_today =  get_total_patient_days_current_date_v2($this->session->userdata('user_location'));	

						if(empty($total_los_today['total_patient_los']))
						{	
							$patient_los = 0;
						}
						else
						{
							$patient_los = $total_los_today['total_patient_los'];
						}
						if(empty($total_patient_days_today['total_patient_days']))
						{	
							$patient_days = 0;
						}
						else
						{
							$patient_days = $total_patient_days_today['total_patient_days'];
						}
					}
					else
					{
						$total_los_today =  get_total_patient_los_current_date_hopice_v2($date_fetch['hospiceID'],$this->session->userdata('user_location'));
						$total_patient_days_today =  get_total_patient_days_current_date_hospice_v2($date_fetch['hospiceID'],$this->session->userdata('user_location'));

						if(empty($total_los_today['total_patient_los']))
						{	
							$patient_los = 0;
						}
						else
						{
							$patient_los = $total_los_today['total_patient_los'];
						}
						if(empty($total_patient_days_today['total_patient_days']))
						{	
							$patient_days = 0;
						}
						else
						{
							$patient_days = $total_patient_days_today['total_patient_days'];
						}		
					}
				}	
			}

	  		/*********** New Customer ************/
			if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to']))
			{
				$pt_filter['current_date'] = array("sign"=>1,"sign_second"=>1,"hospiceID"=>$date_fetch['hospiceID']);
				$pt_filter['date_range_v4'] = array("hospiceID"=>$date_fetch['hospiceID'],"from"=>$pt_filter['date_range']['from'],"to"=>$pt_filter['date_range']['to']);
				$pt_filter['date_range'] = array();
				$query_result_new_pt = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
		  		foreach ($query_result_new_pt as $value) {
		  			$query_result = get_patient_first_order($value['patientID']);
		  			if($query_result['uniqueID'] == $value['uniqueID'])
		  			{
		  				$new_pt++;
		  			}
		  		}
			}
			else
			{
				$pt_filter['current_date'] = array("sign"=>1,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
				$pt_filter['date_range_v4'] = array("hospiceID"=>$date_fetch['hospiceID'],"from"=>"","to"=>"");
				$pt_filter['date_range'] = array();
				$query_result_new_pt = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
		  		foreach ($query_result_new_pt as $value) {
		  			$query_result = get_patient_first_order($value['patientID']);
		  			if($query_result['order_status'] == "active")
		  			{
		  				$new_pt++;
		  			}
		  		}
			}

			$pt_filter = array("date_range"=>$date_fetch);
	  		if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to']))
			{
				$current_day = date('Y-m-d');
				if(($pt_filter['date_range']['from'] == $current_day && $pt_filter['date_range']['to'] > $current_day) || ($pt_filter['date_range']['from'] == $current_day && $pt_filter['date_range']['to'] == $current_day) || ($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] == $current_day) || ($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] > $current_day))
				{
            		$pt_filter['date_range_v4'] = array("hospiceID"=>$date_fetch['hospiceID'],"from"=>"","to"=>"");
                 	$pt_filter['current_date'] = array("sign"=>1,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
                 	$pt_filter['date_range'] = array();
                 	$query_result_new_pt_inside = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
			  		foreach ($query_result_new_pt_inside as $value) {
			  			$query_result = get_patient_first_order($value['patientID']);
			  			if($query_result['order_status'] == "active")
			  			{
			  				$new_pt++;
			  			}
			  		}
			  		$pt_filter['date_range'] = $date_fetch;
				}
			}
			$pt_filter['date_range_v4'] = array();
			$pt_filter = array("date_range"=>$date_fetch);
	  		/*********** END of New Patient ************/

			/*********** Delivery ************/
			if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to']))
			{
				$pt_filter['current_date'] = array("sign"=>2,"sign_second"=>1,"hospiceID"=>$date_fetch['hospiceID']);
			}
			else
			{
				$pt_filter['current_date'] = array("sign"=>2,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
			}
			$pt_filter['activity_type'] = 1;
			$query_result_new_item = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
	  		$count_inside = 0;
            $last_patientID = 0;
            $last_uniqueID = 0;
            $sign_add_count_inside = 0;

            $real_loop_count = 0;
            $newitem = 0;

	  		$query_result_count = 0;
	  		foreach($query_result_new_item as $key => $value)
	  		{
	  			if($value['parentID'] == 0)
	  			{
	  				$query_result_count++;
	  			}
	  		}

	  		foreach ($query_result_new_item as $key => $value) {
  				if($newitem != 0)
  				{
  					
  					if($last_patientID != $value['patientID'])
                    {
                        if($value['parentID'] == 0)
                        {
                            $check_first_order = get_patient_first_order($value['patientID']);
                            if($check_first_order['uniqueID'] != $value['uniqueID'])
                            {
                                if($sign_add_count_inside == 0)
                                {
                                    $newitem++;
                                }
                                $last_patientID = $value['patientID'];
                                $last_uniqueID = $value['uniqueID'];
                                $real_loop_count++;
                                $sign_add_count_inside = 0;
                            }
                            else
                            {
                                $real_loop_count++;
                            }
                        }
                    }
                    else
                    {
                        if($value['parentID'] == 0)
                        {
                            if($value['uniqueID'] == $last_uniqueID)
                            {
                                $last_patientID = $value['patientID'];
                                $last_uniqueID = $value['uniqueID'];
                                $real_loop_count++;
                            }
                            else
                            {
                                $check_first_order = get_patient_first_order($value['patientID']);
                                if($check_first_order['uniqueID'] != $value['uniqueID'])
                                {
                                    if($sign_add_count_inside == 0)
                                    {
                                        $newitem++;
                                    }
                                    $last_patientID = $value['patientID'];
                                    $last_uniqueID = $value['uniqueID'];
                                    $real_loop_count++;
                                    $sign_add_count_inside = 0;
                                }
                                else
                                {
                                    $real_loop_count++;
                                }
                            }
                        }
                    }
  				}
  				else
  				{
  					if($value['parentID'] == 0)
		  			{
	  					if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
                      	{
                            $check_first_order = get_patient_first_order($value['patientID']);
                            if($check_first_order['uniqueID'] != $value['uniqueID'])
                            {
                                $last_patientID = $value['patientID'];
                                $last_uniqueID = $value['uniqueID'];
                                $real_loop_count++;
                                if($real_loop_count < $query_result_count)
                                {
                                    $newitem++;
                                    $sign_add_count_inside = 1;
                                }
                            }
                            else
                            {
                                $real_loop_count++;
                            }
                        }       
		  			}
  				}
	  		}

	  		if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to']))
			{
				$current_day = date('Y-m-d');
				if(($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] == $current_day) || ($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] > $current_day))
				{
            		$pt_filter['date_range'] = array("hospiceID"=>$date_fetch['hospiceID'],"from"=>"","to"=>"");
                 	$pt_filter['current_date'] = array("sign"=>2,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
                 	$pt_filter['activity_type'] = 1;

                 	$query_result_new_item = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
			  		$query_result_count = 0;
		            $last_patientID = 0;
		            $last_uniqueID = 0;
		            $item_count = 0;
		            $sign_add_count_inside = 0;
		            $real_loop_count = 0;

			  		foreach($query_result_new_item as $key => $value)
			  		{
			  			if($value['parentID'] == 0)
			  			{
			  				$query_result_count++;
			  			}
			  		}
			  		foreach ($query_result_new_item as $key => $value) {
		  				if($newitem != 0)
		  				{
		  					if($last_patientID != $value['patientID'])
		                    {
		                        if($value['parentID'] == 0)
		                        {
		                            $check_first_order = get_patient_first_order($value['patientID']);
		                            if($check_first_order['uniqueID'] != $value['uniqueID'])
		                            {
		                                if($sign_add_count_inside == 0)
		                                {
		                                    $newitem++;
		                                }
		                                $last_patientID = $value['patientID'];
		                                $last_uniqueID = $value['uniqueID'];
		                                $real_loop_count++;
		                                $item_count = 1;
		                                $sign_add_count_inside = 0;
		                            }
		                            else
		                            {
		                                $real_loop_count++;
		                            }
		                        }
		                    }
		                    else
		                    {
		                        if($value['parentID'] == 0)
		                        {
		                            if($value['uniqueID'] == $last_uniqueID)
		                            {
		                                $item_count++;
		                                $last_patientID = $value['patientID'];
		                                $last_uniqueID = $value['uniqueID'];
		                                $real_loop_count++;
		                            }
		                            else
		                            {
		                                $check_first_order = get_patient_first_order($value['patientID']);
		                                if($check_first_order['uniqueID'] != $value['uniqueID'])
		                                {
		                                    if($sign_add_count_inside == 0)
		                                    {
		                                        $newitem++;
		                                    }
		                                    $last_patientID = $value['patientID'];
		                                    $last_uniqueID = $value['uniqueID'];
		                                    $real_loop_count++;
		                                    $item_count = 1;
		                                    $sign_add_count_inside = 0;
		                                }
		                                else
		                                {
		                                    $real_loop_count++;
		                                }
		                            }
		                        }
		                    }
		  				}
		  				else
		  				{
		  					if($value['parentID'] == 0)
				  			{
			  					if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
		                      	{
		                            $check_first_order = get_patient_first_order($value['patientID']);
		                            if($check_first_order['uniqueID'] != $value['uniqueID'])
		                            {
		                                $last_patientID = $value['patientID'];
		                                $last_uniqueID = $value['uniqueID'];
		                                $real_loop_count++;
		                                if($real_loop_count < $query_result_count)
		                                {
		                                    $newitem++;
		                                    $item_count = 1;
		                                    $sign_add_count_inside = 1;
		                                }
		                            }
		                            else
		                            {
		                                $real_loop_count++;
		                            }
		                        }       
				  			}
		  				}
			  		}
			  		$pt_filter['date_range'] = $date_fetch;
				}
			}
			if($newitem > 0)
			{
				$newitem += 1;
			}
	  		/*********** End of Delivery ************/

			/*********** Exchange ************/
			$pt_filter['activity_type'] = 3;
			if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to']))
			{
				$pt_filter['current_date'] = array("sign"=>4,"sign_second"=>1,"hospiceID"=>$date_fetch['hospiceID']);
			}
			else
			{
				$pt_filter['current_date'] = array("sign"=>4,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
			}
    			
			$query_result_exchange = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
			$count_inside = 0;
            $last_patientID = 0;
            $last_uniqueID = 0;
            $item_count = 0;
            $sign_add_count_inside = 0;
            $real_loop_count = 0;
            $query_result_count = 0;

	  		foreach($query_result_exchange as $key => $value)
	  		{
	  			if($value['parentID'] == 0)
	  			{
	  				$query_result_count++;
	  			}
	  		}
	  		foreach ($query_result_exchange as $key => $value) {
  				if($exchange != 0)
  				{
					if($last_patientID != $value['patientID'])
                    {
                        if($value['parentID'] == 0)
                        {
                            $check_first_order = get_patient_first_order($value['patientID']);
                            if($check_first_order['uniqueID'] != $value['uniqueID'])
                            {
                                if($sign_add_count_inside == 0)
                                {
                                    $exchange++;
                                }
                                $last_patientID = $value['patientID'];
                                $last_uniqueID = $value['uniqueID'];
                                $real_loop_count++;
                                $sign_add_count_inside = 0;
                            }
                            else
                            {
                                $real_loop_count++;
                            }
                        }
                    }
                    else
                    {
                        if($value['parentID'] == 0)
                        {
                            if($value['uniqueID'] == $last_uniqueID)
                            {
                                $last_patientID = $value['patientID'];
                                $last_uniqueID = $value['uniqueID'];
                                $real_loop_count++;
                            }
                            else
                            {
                                $check_first_order = get_patient_first_order($value['patientID']);
                                if($check_first_order['uniqueID'] != $value['uniqueID'])
                                {
                                    if($sign_add_count_inside == 0)
                                    {
                                        $exchange++;
                                    }
                                    $last_patientID = $value['patientID'];
                                    $last_uniqueID = $value['uniqueID'];
                                    $real_loop_count++;
                                    $sign_add_count_inside = 0;
                                }
                                else
                                {
                                    $real_loop_count++;
                                }
                            }
                        }
                    }
  				}
  				else
  				{
  					if($value['parentID'] == 0)
                    {
                        if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
                        {
                            $check_first_order = get_patient_first_order($value['patientID']);
                            if($check_first_order['uniqueID'] != $value['uniqueID'])
                            {
                                $last_patientID = $value['patientID'];
                                $last_uniqueID = $value['uniqueID'];
                                $real_loop_count++;
                                if($real_loop_count < $query_result_count)
                                {
                                    $exchange++;
                                    $sign_add_count_inside = 1;
                                }
                            }
                            else
                            {
                                $real_loop_count++;
                            }
                        }                                   
                    }
  				}
  			}
  			
  			if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to']))
			{
				$current_day = date('Y-m-d');
				if(($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] == $current_day) || ($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] > $current_day))
				{
            		$pt_filter['date_range'] = array("hospiceID"=>$date_fetch['hospiceID'],"from"=>"","to"=>"");
                 	$pt_filter['current_date'] = array("sign"=>4,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
			  		$query_result_exchange = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
			  		$last_patientID = 0;
	                $last_uniqueID = 0;
	                $item_count = 0;
	                $sign_add_count_inside = 0;
	                $real_loop_count = 0;
	                $query_result_count = 0;

			  		foreach($query_result_exchange as $key => $value)
			  		{
			  			if($value['parentID'] == 0)
			  			{
			  				$query_result_count++;
			  			}
			  		}
			  		foreach ($query_result_exchange as $key => $value) {
		  				if($exchange != 0)
		  				{
							if($last_patientID != $value['patientID'])
		                    {
		                        if($value['parentID'] == 0)
		                        {
		                            $check_first_order = get_patient_first_order($value['patientID']);
		                            if($check_first_order['uniqueID'] != $value['uniqueID'])
		                            {
		                                if($sign_add_count_inside == 0)
		                                {
		                                    $exchange++;
		                                }
		                                $last_patientID = $value['patientID'];
		                                $last_uniqueID = $value['uniqueID'];
		                                $real_loop_count++;
		                                $sign_add_count_inside = 0;
		                            }
		                            else
		                            {
		                                $real_loop_count++;
		                            }
		                        }
		                    }
		                    else
		                    {
		                        if($value['parentID'] == 0)
		                        {
		                            if($value['uniqueID'] == $last_uniqueID)
		                            {
		                                $last_patientID = $value['patientID'];
		                                $last_uniqueID = $value['uniqueID'];
		                                $real_loop_count++;
		                            }
		                            else
		                            {
		                                $check_first_order = get_patient_first_order($value['patientID']);
		                                if($check_first_order['uniqueID'] != $value['uniqueID'])
		                                {
		                                    if($sign_add_count_inside == 0)
		                                    {
		                                        $exchange++;
		                                    }
		                                    $last_patientID = $value['patientID'];
		                                    $last_uniqueID = $value['uniqueID'];
		                                    $real_loop_count++;
		                                    $sign_add_count_inside = 0;
		                                }
		                                else
		                                {
		                                    $real_loop_count++;
		                                }
		                            }
		                        }
		                    }
		  				}
		  				else
		  				{
		  					if($value['parentID'] == 0)
		                    {
		                        if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
		                        {
		                            $check_first_order = get_patient_first_order($value['patientID']);
		                            if($check_first_order['uniqueID'] != $value['uniqueID'])
		                            {
		                                $last_patientID = $value['patientID'];
		                                $last_uniqueID = $value['uniqueID'];
		                                $real_loop_count++;
		                                if($real_loop_count < $query_result_count)
		                                {
		                                    $exchange++;
		                                    $sign_add_count_inside = 1;
		                                }
		                            }
		                            else
		                            {
		                                $real_loop_count++;
		                            }
		                        }                                   
		                    }
		  				}
		  			}
			  		$pt_filter['date_range'] = $date_fetch;
				}
			}
			if($exchange > 0)
			{
				$exchange += 1;
			}
  			/*********** END of Exchange ************/

	  		/*********** Pickup ************/
	  		if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to'])){
				$pt_filter['current_date'] = array("sign"=>3,"sign_second"=>1,"hospiceID"=>$date_fetch['hospiceID']);
			}
			else
			{
				$pt_filter['current_date'] = array("sign"=>3,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
			}
			
			$pt_filter['activity_type'] = 2;
			$result_pickup = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
			$pickup = count($result_pickup);
			if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to']))
			{
				$current_day = date('Y-m-d');
				if(($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] == $current_day) || ($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] > $current_day))
				{
            		$pt_filter['date_range'] = array("hospiceID"=>$date_fetch['hospiceID'],"from"=>"","to"=>"");
                 	$pt_filter['current_date'] = array("sign"=>3,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
                 	$pt_filter['activity_type'] = 2;
					$result_pickup_inside 	= $this->mreport->get_patient_count($pt_filter);
					$pickup += count($result_pickup_inside);
			  		$pt_filter['date_range'] = $date_fetch;
				}
			}

			/*********** END of Pickup ************/

			/*********** Customer Move ************/
			$pt_filter['activity_type'] = 4;
			if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to'])){
				$pt_filter['current_date'] = array("sign"=>5,"sign_second"=>1,"hospiceID"=>$date_fetch['hospiceID']);
			}
			else
			{
				$pt_filter['current_date'] = array("sign"=>5,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
			}
			
			$new_result_here_ptmove = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
			$result_here_pt_move = array_msort($new_result_here_ptmove, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
	  		foreach ($result_here_pt_move as $value) {
	  			$query_result = get_ptmove_address_first_order($value['patientID'],$value['addressID']);
	  			if($query_result['uniqueID'] == $value['uniqueID'])
	  			{
	  				$ptmove++;
	  			}
	  		}
	  		if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to']))
			{
				$current_day = date('Y-m-d');
				if(($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] == $current_day) || ($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] > $current_day))
				{
            		$pt_filter['date_range'] = array("hospiceID"=>$date_fetch['hospiceID'],"from"=>"","to"=>"");
                 	$pt_filter['current_date'] = array("sign"=>5,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
                 	$new_result_here_ptmove 	= $this->mreport->get_patient_count($pt_filter);
					$result_here_pt_move_inside = array_msort($new_result_here_ptmove, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
			  		foreach ($result_here_pt_move_inside as $value) {
			  			$query_result = get_ptmove_address_first_order($value['patientID'],$value['addressID']);
			  			if($query_result['uniqueID'] == $value['uniqueID'])
			  			{
			  				$ptmove++;
			  			}
			  		}
			  		$pt_filter['date_range'] = $date_fetch;
				}
			}
	  		/*********** END of Customer Move ************/

			/*********** Respite ************/
			$pt_filter['activity_type'] = 5;
			if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to'])){
				$pt_filter['current_date'] = array("sign"=>6,"sign_second"=>1,"hospiceID"=>$date_fetch['hospiceID']);
			}
			else
			{
				$pt_filter['current_date'] = array("sign"=>6,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
			}
			
			$new_result_here_respite = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
			$result_here_respite = array_msort($new_result_here_respite, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
	  		foreach ($result_here_respite as $value) {
	  			$query_result = get_respite_address_first_order($value['patientID'],$value['addressID']);
	  			if($query_result['uniqueID'] == $value['uniqueID'])
	  			{
	  				$respite++;
	  			}
	  		}
	  		if(!empty($pt_filter['date_range']['from']) && !empty($pt_filter['date_range']['to']))
			{
				$current_day = date('Y-m-d');
				if(($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] == $current_day) || ($pt_filter['date_range']['from'] < $current_day && $pt_filter['date_range']['to'] > $current_day))
				{
            		$pt_filter['date_range'] = array("hospiceID"=>$date_fetch['hospiceID'],"from"=>"","to"=>"");
                 	$pt_filter['current_date'] = array("sign"=>6,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
                 	$new_result_here_respite 	= $this->mreport->get_patient_count($pt_filter);
					$result_here_respite = array_msort($new_result_here_respite, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
			  		foreach ($result_here_respite as $value) {
			  			$query_result = get_respite_address_first_order($value['patientID'],$value['addressID']);
			  			if($query_result['uniqueID'] == $value['uniqueID'])
			  			{
			  				$respite++;
			  			}
			  		}
			  		$pt_filter['date_range'] = $date_fetch;
				}
			}
	  		/*********** End of Respite ************/

			$graph = array(
						array("label" => "New CUS", "value" 	=> $new_pt),
						array("label" => "New Item", "value" 	=> $newitem),
						array("label" => "Exchange", "value" 	=> $exchange),
						array("label" => "Pickup", "value" 		=> $pickup),
						array("label" => "CUS Move", "value" 	=> $ptmove),
						array("label" => "Respite", "value" 	=> $respite),
					 );

			$title  = "FOR THE MONTH OF ".date("F, Y");
			if($date_fetch['type']==0 && $date_fetch['from']!="" && $date_fetch['to']!="")
			{
				$title = date("F d,Y",strtotime($date_fetch['from']))." - ".date("F d,Y",strtotime($date_fetch['to']));
			}
			if($date_fetch['type']!=0 AND isset($this->records[$date_fetch['type']]))
			{
				$title = ucwords($this->records[$date_fetch['type']]);
			}
			$from 	= $date_fetch['from'];
			$to 	= $date_fetch['to'];
			if($date_fetch['hospiceID'] != 0)
			{
				$data['hospice_info'] = get_hospice_name($date_fetch['hospiceID']);
			}
			else
			{
				$data['hospice_info']['hospice_name'] = "Advantage Home Medical Services";
			}
			
			$data = array(
							"title" 			=> ucwords($title),
							"date_range_from"	=> $from,
							"date_range_to"		=> $to,
							"graph"				=> $graph,
							"hospiceID"			=> $data['hospice_info'],
							"patient_days"		=> $patient_days,
							"patient_los"		=> $patient_los
						);

			$this->common->code 	= 0;
			$this->common->message 	= "Monthly activity report";
			$this->common->data 	= $data;

			
			$this->common->response(false);
		}
		// 		if(empty($pt_filter['date_range']['from']) && empty($pt_filter['date_range']['to']))
	  // 		{
	  // 			$filter_from = "";
	  // 			$filter_to = "";
	  // 			$current_date = date('Y-m-d');
	  // 		}
	  // 		else
	  // 		{
	  // 			$filter_from = $pt_filter['date_range']['from'];
	  // 			$filter_to = $pt_filter['date_range']['to'];
	  // 			$current_date = "";
	  // 		}
	  // 		$hospiceID = $date_fetch['hospiceID'];
	  // 		$current_day = date('Y-m-d');
	  		
			// if($current_date != "")
	  // 		{
	  // 			$latest_patient_on_list = get_latest_patient_on_list();
	  //   		$border_bottom = $latest_patient_on_list['patientID'] - 300;
	  //   		$border_top = "";

	  // 			$list_active_patients_result = list_patients_by_hospice_v2($border_bottom,$border_top,$hospiceID);
	  // 			if(!empty($list_active_patients_result))
	  // 			{
			//   		foreach($list_active_patients_result as $value) 
		 //    		{
		 //    			$query_result = get_patient_first_order($value['patientID']);
		 //    			if($query_result['order_status'] == "active")
	  //   				{
	  //   					$new_pt++;
	  //   					$added_patientIDs[$count] = $value['patientID'];
  	// 						$count++;
	  //   				}
		 //    		}
		 //    	}
	  // 		}
	  // 		else
	  // 		{
	  // 			$list_active_patients_result = list_patients_by_hospice($hospiceID);
	  // 			if(!empty($list_active_patients_result))
	  // 			{
	  // 				if($filter_from == $current_day && $filter_to == $current_day || $filter_from == $current_day && $filter_to > $current_day)
	  // 				{
	  // 					if(($filter_from <= $current_day))
		 //                {
		 //                    if($filter_from <= $filter_to)
		 //                    {
		 //                        if($filter_to == $current_day || $filter_to > $current_day)
		 //                        {
		 //                        	foreach($list_active_patients_result as $value) 
			// 			    		{
			// 			    			$query_result_new = get_patient_first_order($value['patientID']);
			// 			    			if($query_result_new['order_status'] == "active")
			// 			    			{
			// 			    				if(!in_array($value['patientID'], $added_patientIDs))
			// 			    				{
			// 			    					$new_pt++;
			// 			    					$added_patientIDs[$count] = $value['patientID'];
			// 		  							$count++;
			// 			    				}
			// 			    			}
			// 			    		}
		 //                        }
		 //                    }
		 //                }
	  // 				}
	  // 				else
	  // 				{
	  // 					foreach($list_active_patients_result as $value) 
			//     		{
			//     			$query_result = get_patient_first_order($value['patientID']);
			    			
			//     			if($filter_to == $current_day || $filter_to > $current_day)
			//     			{
			//     				if($query_result['actual_order_date'] >= $filter_from && $query_result['actual_order_date'] < $filter_to)
			// 	    			{
			// 	    				$new_pt++;
			// 	    				$added_patientIDs[$count] = $value['patientID'];
			// 	  					$count++;
			// 	    			}
			//     			}
			//     			else
			//     			{
			//     				if($query_result['actual_order_date'] >= $filter_from && $query_result['actual_order_date'] <= $filter_to)
			// 	    			{
			// 	    				$new_pt++;
			// 	    				$added_patientIDs[$count] = $value['patientID'];
			// 	  					$count++;
			// 	    			}
			//     			}
			//     		}

			//     		if(($filter_from <= $current_day))
		 //                {
		 //                    if($filter_from <= $filter_to)
		 //                    {
		 //                        if($filter_to == $current_day || $filter_to > $current_day)
		 //                        {
		 //                        	foreach($list_active_patients_result as $value) 
			// 			    		{
			// 			    			$query_result_new = get_patient_first_order($value['patientID']);
			// 			    			if($query_result_new['order_status'] == "active")
			// 			    			{
			// 			    				if(!in_array($value['patientID'], $added_patientIDs))
			// 			    				{
			// 			    					$new_pt++;
			// 			    					$added_patientIDs[$count] = $value['patientID'];
			// 		  							$count++;
			// 			    				}
			// 			    			}
			// 			    		}
		 //                        }
		 //                    }
		 //                }
	  // 				}
	  // 			}
	  // 		}

		public function activity_report_day()
		{
			$date_fetch = fetch_report_date();

			$hospiceID = $date_fetch['hospiceID'];
			//getting date
			$date_from 	=  date("Y-m-d",strtotime("-6 days"));//6 days ago
			$date_to   	=  date("Y-m-d"); //current date
			$current_day = date('Y-m-d');
			$loop_count = 0;

			$begin 		= new DateTime( $date_from );
			$end 		= new DateTime( $date_to );
			$end 		= $end->modify( '+1 day' ); 

			$interval 	= new DateInterval('P1D');
			$daterange 	= new DatePeriod($begin, $interval ,$end);

			//new cus filter default
			$graph 		=  array();
			$types 		= array(0=>"New CUS",1=>"New Item",3=>"Exchange",2=>"Pickup",4=>"CUS Move",5=>"Respite");
			//print_r($daterange);exit;
			foreach($daterange as $date)
			{
				$temp = array();
				$date = $date->format("Y-m-d");
				$temp['y']		= strtoupper(date("D",strtotime($date)));
				//echo $date;
				//continue;
				foreach ($types as $key => $value) 
				{
					$result_query = array();
					$result_count = 0;
					$pt_filter 	=  array();
					$result = 0;
					if($key==0)
					{
						if($current_day == $date)
						{
							$pt_filter['date_range_v3'] = array("from"=>$date,"to"=>$date,"sign_filtered"=>1);
							$pt_filter['current_date'] = array("sign"=>1,"sign_second"=>1,"hospiceID"=>$date_fetch['hospiceID']);
		
							$result_query = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));

							foreach ($result_query as $value_inside_here) {
					  			$query_result = get_patient_first_order($value_inside_here['patientID']);
					  			if($query_result['order_status'] == "active")
					  			{
					  				$result_count++;
					  			}
					  		}
					  	}
					  	else
					  	{
					  		$pt_filter['date_range_v3'] = array("from"=>$date,"to"=>$date,"sign_filtered"=>1);
							$pt_filter['current_date'] = array("sign"=>1,"sign_second"=>1,"hospiceID"=>$date_fetch['hospiceID']);
							
							$result_query = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
							
							foreach ($result_query as $value_inside_here) {
					  			$query_result = get_patient_first_order($value_inside_here['patientID']);
					  			if($query_result['uniqueID'] == $value_inside_here['uniqueID'])
					  			{
					  				$result_count++;
					  			}
					  		}
					  	}
					}
					else
					{
						$pt_filter['activity_type'] = $key; 
						$pt_filter['date_range_v2'] = array("from"=>$date,"to"=>$date,"sign_filtered"=>1); 
						$pt_filter['date_range'] 	= array("from"=>$date,"to"=>$date);
						$result_count 				= $this->mreport->get_activity_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
					}
					$explode = explode(" ",$value);
					$temp[strtolower(implode("",$explode))] = $result_count;
				}
				$temp['date'] 	= date("M d Y",strtotime($date));
				$graph[] 		= $temp;
				$loop_count++;
			}
			
			$title  = "";
			$from 	= $date_from;
			$to 	= $date_to;
			$data = array(
							"title" 			=> ucwords($title),
							"date_range_from"	=> $from,
							"date_range_to"		=> $to,
							"graph"				=> $graph

						);
			$this->common->code 	= 0;
			$this->common->message 	= "Weekly activity report";
			$this->common->data 	= $data;

			
			$this->common->response(false);
		}
		// $count = 0;
		// if($current_day == $date)
		// {
		// 	$latest_patient_on_list = get_latest_patient_on_list();
  //   		$border_bottom = $latest_patient_on_list['patientID'] - 100;
  //   		$border_top = "";

		// 	$list_active_patients_result = list_patients_by_hospice_v2($border_bottom,$border_top,$hospiceID);
  // 			if(!empty($list_active_patients_result))
  // 			{
		//   		foreach($list_active_patients_result as $active_patients_looped_value) 
	 //    		{
	 //    			$query_result = get_patient_first_order($active_patients_looped_value['patientID']);
	 //    			if($query_result['order_status'] == "active")
  //   				{
  //   					$result_count++;
  //   					$added_patientIDs[$count] = $active_patients_looped_value['patientID'];
	// 						$count++;
  //   				}
	 //    		}
	 //    	}
		// }
		// else
		// {
		// 	$latest_patient_on_list = get_latest_patient_on_list();
		// 	$deduction_count = 50*$loop_count;
		// 	$final_deduction = 300-$deduction_count;
  //   		$border_bottom = $latest_patient_on_list['patientID'] - $final_deduction;
  //   		$border_top = $latest_patient_on_list['patientID'];

		// 	$list_active_patients_result = list_patients_by_hospice_v2($border_bottom,$border_top,$hospiceID);
		// 	if(!empty($list_active_patients_result))
  // 			{
  // 				foreach($list_active_patients_result as $active_patients_looped_value) 
	 //    		{
	 //    			$query_result_new = get_patient_first_order($active_patients_looped_value['patientID']);
	 //    			if($query_result_new['actual_order_date'] >= $date && $query_result_new['actual_order_date'] <= $date)
	 //    			{
	 //    				$result_count++;
	 //    				$added_patientIDs[$count] = $active_patients_looped_value['patientID'];
	 //  					$count++;
	 //    			}
	 //    		}
  // 			}
		// }

		/*
		| @func : patienceresidence_report_month
		| @desc : patience residence report
		| @date : 02.04.2016
		|
		*/
		public function patienceresidence_report_month()
		{
			//new cus filter default
			$pt_filter 	=  array();
			$date_fetch = fetch_report_date();

			if($date_fetch['from']=="" && $date_fetch['to']=="")
			{
				$pt_filter['current_month'] = "";
			}
			else
			{
				if(isset($pt_filter['current_month']))
				{
					unset($pt_filter['current_month']);
				}
				$pt_filter = array("date_range"=>$date_fetch);
			}

			$this->load->model("order_model");
			$total_entries = 0;
			if($date_fetch['from']!="" && $date_fetch['to']!="")
			{
				$current_day = date('Y-m-d');
				$patient_list = array();
                if(($date_fetch['from'] <= $current_day))
                {
                    if($date_fetch['from'] <= $date_fetch['to'])
                    {
                        if($date_fetch['to'] == $current_day || $date_fetch['to'] > $current_day)
                        {
                        	if($date_fetch['hospiceID'] != 0)
                        	{
                        		$total_entries_today = $this->order_model->list_active_patients_v4($date_fetch['hospiceID'],$this->session->userdata('user_location'));
								$total_entries = count($total_entries_today);
								$patient_list = $total_entries_today;
                        	}
                        	else
                        	{
                        		$total_entries_today = $this->order_model->list_active_patients_v4(0,$this->session->userdata('user_location'));
								$total_entries = count($total_entries_today);
								$patient_list = $total_entries_today;
                        	}
                        }
                    }
                }

                if(($date_fetch['from'] != $date_fetch['to']))
                {
                	if($date_fetch['from'] <= $date_fetch['to'])
	                {
                		$passed_hospiceID = $date_fetch['hospiceID'];

	                	$begin      = new DateTime($date_fetch['from']);
			            $end        = new DateTime($date_fetch['to']);
			            $end        = $end->modify( '+1 day' ); 
			            $interval   = new DateInterval('P1D');
			            $daterange  = new DatePeriod($begin, $interval ,$end);
			            foreach($daterange as $date_passed)
			            {
			            	$date_passed = $date_passed->format("Y-m-d");
			            	if($date_passed != $current_day && $date_passed < $current_day)
			            	{
			            		$patients_specific_date = $this->order_model->list_active_patients_specific_date_v2($date_passed,$passed_hospiceID,$this->session->userdata('user_location'));
				                $total_entries += count($patients_specific_date);
				                $patient_list = array_merge($patient_list,$patients_specific_date);
			            	}
			            }	
	                }	
                }
                else if($date_fetch['from'] != $current_day && $date_fetch['from'] < $current_day)
                {
                	if($date_fetch['from'] <= $date_fetch['to'])
	                {
	            		$passed_hospiceID = $date_fetch['hospiceID'];
	                	$patients_specific_date = $this->order_model->list_active_patients_specific_date_v2($date_fetch['from'],$passed_hospiceID,$this->session->userdata('user_location'));
		                $total_entries += count($patients_specific_date);
		                $patient_list = array_merge($patient_list,$patients_specific_date);
	                }	
                }
			}
			else if($date_fetch['hospiceID'] != 0)
			{
				$total_entries_today = $this->order_model->list_active_patients_v4($date_fetch['hospiceID'],$this->session->userdata('user_location'));
				$total_entries = count($total_entries_today);
				$patient_list = $total_entries_today;
			}
			else
			{
				$total_entries_today = $this->order_model->list_active_patients_v4(0,$this->session->userdata('user_location'));
				$total_entries = count($total_entries_today);
				$patient_list = $total_entries_today;
			}

			$types = array(
							"Assisted Living",
							"Group Home",
							"Hic Home",
							"Home Care",
							"Skilled Nursing Facility"
						);
			$grouped_patient_list = array(
				0,
				0,
				0,
				0,
				0
			);
			foreach ($patient_list as $key => $value) {
				if($value['deliver_to_type'] == $types[0])
				{
					$grouped_patient_list[0] += 1;
				}
				else if($value['deliver_to_type'] == $types[1])
				{
					$grouped_patient_list[1] += 1;
				}
				else if($value['deliver_to_type'] == $types[2])
				{
					$grouped_patient_list[2] += 1;
				}
				else if($value['deliver_to_type'] == $types[3])
				{
					$grouped_patient_list[3] += 1;
				}
				else if($value['deliver_to_type'] == $types[4])
				{
					$grouped_patient_list[4] += 1;
				}
			}

			$graph = array();
			foreach ($types as $key => $value) 
			{
				$graph[] = array(
								"label" => $value,
								"value" => $grouped_patient_list[$key]
							);
			}
			
			$from 	= $date_fetch['from'];
			$to 	= $date_fetch['to'];
			$data = array(
							"date_range_from"	=> $from,
							"date_range_to"		=> $to,
							"graph"				=> $graph,
							"entries"			=> $total_entries
						);
			$this->common->code 	= 0;
			$this->common->message 	= "Monthly patience residence report";
			$this->common->data 	= $data;
			$this->common->response(false);
		}

		public function patienceresidence_report_day()
		{
			$date_fetch = fetch_report_date();
			//getting date
			$date_from 	=  date("Y-m-d",strtotime("-6 days"));//6 days ago
			$date_to   	=  date("Y-m-d"); //current date

			$begin 		= new DateTime( $date_from );
			$end 		= new DateTime( $date_to );
			$end 		= $end->modify( '+1 day' ); 

			$interval 	= new DateInterval('P1D');
			$daterange 	= new DatePeriod($begin, $interval ,$end);
			
			$types = array(
							"Assisted Living",
							"Group Home",
							"Hic Home",
							"Home Care",
							"Skilled Nursing Facility"
						);
			
			$this->load->model("order_model");
			$graph = array();
			$current_day = date('Y-m-d');
			
			foreach($daterange as $date)
			{
				$temp = array();
				$date = $date->format("Y-m-d");
				if($date == $current_day)
				{
					if($date_fetch['hospiceID'] != 0)
                	{
                		$total_entries_today = $this->order_model->list_active_patients_v4($date_fetch['hospiceID'],$this->session->userdata('user_location'));
						$patient_list = $total_entries_today;
                	}
                	else
                	{
                		$total_entries_today = $this->order_model->list_active_patients_v4(0,$this->session->userdata('user_location'));
						$patient_list = $total_entries_today;
                	}
				}
				else
				{
					$passed_hospiceID = $date_fetch['hospiceID'];
                	$patients_specific_date = $this->order_model->list_active_patients_specific_date_V2($date,$passed_hospiceID,$this->session->userdata('user_location'));
	                $patient_list = $patients_specific_date;
				}

				$grouped_patient_list = array(
					0,
					0,
					0,
					0,
					0
				);
				foreach ($patient_list as $key => $value) {
					if($value['deliver_to_type'] == $types[0])
					{
						$grouped_patient_list[0] += 1;
					}
					else if($value['deliver_to_type'] == $types[1])
					{
						$grouped_patient_list[1] += 1;
					}
					else if($value['deliver_to_type'] == $types[2])
					{
						$grouped_patient_list[2] += 1;
					}
					else if($value['deliver_to_type'] == $types[3])
					{
						$grouped_patient_list[3] += 1;
					}
					else if($value['deliver_to_type'] == $types[4])
					{
						$grouped_patient_list[4] += 1;
					}
				}

				foreach ($types as $key => $value) 
				{
					$explode = explode(" ",$value);
					$temp[strtolower(implode("_",$explode))] = $grouped_patient_list[$key];
				}

				$temp['date'] 	= date("M d Y",strtotime($date));
				$temp['y']		= strtoupper(date("D",strtotime($date)));
				$graph[] 		= $temp;
			}
		
			$title  = "";
			$from 	= $date_from;
			$to 	= $date_to;
			$data = array(
							"title" 			=> $title,
							"date_range_from"	=> $from,
							"date_range_to"		=> $to,
							"graph"				=> $graph

						);
			$this->common->code 	= 0;
			$this->common->message 	= "Daily patience residence report";
			$this->common->data 	= $data;

			
			$this->common->response(false);
		}

		public function view_each_activity_status($activity_status_name,$hospiceID)
		{
			$data = array();
			$data['activity_status_name'] = $activity_status_name;
			$data['hospiceID'] = $hospiceID;

			$this->templating_library->set('title','');
			$this->templating_library->set_view('pages/view_activity_status_details','pages/view_activity_status_details', $data);
		}

		public function activity_status_details($activity_status_name)
		{
			$this->templating_library->set('title','Reports');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');

			$data = array();
			$data['activity_status_name'] = $activity_status_name;

			$this->templating_library->set('title','');
			$this->templating_library->set_view('pages/activity_status_details','pages/activity_status_details', $data);
			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}

		public function residence_status_details($activity_status_name)
		{
			$this->templating_library->set('title','Reports');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');

			$data = array();
			$data['activity_status_name'] = $activity_status_name;

			$this->templating_library->set('title','');
			$this->templating_library->set_view('pages/residence_status_details','pages/residence_status_details', $data);
			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}

		public function sort_activity_status_details($filter_from,$filter_to,$hospiceID,$status_name)
		{
			$current_day = date('Y-m-d');
			$current_date = "";
			$data['patient_list'] = array();
    		$sign_here = 0;
    		$result = array();
			if($filter_from == 0 || $filter_to == 0)
	  		{
	  			$filter_from = "";
	  			$filter_to = "";
	  			$current_date = date('Y-m-d');
	  		}
	  		if($hospiceID == 0)
	  		{
	  			$hospiceID = "";
	  		}

			if($status_name == "new_pt")
		  	{
		  		$data['sign_here'] = 1;
		  		$count = 0;
		  		$added_patientIDs = array();

		  		if($current_date != "")
		  		{
		  			$result_here = get_new_patient_list_v3($current_date,"","",$hospiceID,$this->session->userdata('user_location'));
			  		$count = 0;
			  		foreach ($result_here as $value) {
			  			$query_result = get_patient_first_order($value['patientID']);
			  			if($query_result['order_status'] == "active")
			  			{
			  				$result[$count] = $value;
			  				$count++;
			  			}
			  		}
		  		}
		  		else
		  		{
		  			$result_here = get_new_patient_list_v4($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
			  		$count = 0;
			  		foreach ($result_here as $value) {
			  			$query_result = get_patient_first_order($value['patientID']);
			  			if($query_result['uniqueID'] == $value['uniqueID'])
			  			{
			  				$result[$count] = $value;
			  				$count++;
			  			}
			  		}

			  		if(($filter_from == $current_day && $filter_to > $current_day) || ($filter_from == $current_day && $filter_to == $current_day) || ($filter_from < $current_day && $filter_to == $current_day) || ($filter_from < $current_day && $filter_to > $current_day))
			  		{
			  			$result_here_second = get_new_patient_list_v3($current_day,"","",$hospiceID,$this->session->userdata('user_location'));
			  			foreach ($result_here_second as $value) {
				  			$query_result = get_patient_first_order($value['patientID']);
				  			if($query_result['order_status'] == "active")
				  			{
				  				$result[$count] = $value;
				  				$count++;
				  			}
				  		}
			  		}
		  		}
		  	}
		  	else if($status_name == "new_item")
		  	{
		  		$data['sign_here'] = 3;
		  		$query_result = get_patient_new_item_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
		  		$count_inside = 0;
		  		$last_patientID = 0;
		  		$last_uniqueID = 0;
		  		$item_count = 0;
		  		$sign_add_count_inside = 0;
		  		$real_loop_count = 0;

		  		$query_result_count = 0;
		  		foreach($query_result as $key => $value)
		  		{
		  			if($value['parentID'] == 0)
		  			{
		  				$query_result_count++;
		  			}
		  		}

		  		foreach ($query_result as $key => $value) {
	  				if($count_inside != 0)
	  				{
	  					if($last_patientID != $value['patientID'])
	  					{
	  						if($value['parentID'] == 0)
	  						{
	  							$check_first_order = get_patient_first_order($value['patientID']);
					  			if($check_first_order['uniqueID'] != $value['uniqueID'])
					  			{
					  				if($sign_add_count_inside == 0)
					  				{
					  					$count_inside++;
					  				}
				  					$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
					  				$result[$count_inside] = $patient_details;
					  				$result[$count_inside]['item_count'] = 1;
					  				$last_patientID = $value['patientID'];
					  				$last_uniqueID = $value['uniqueID'];
					  				$real_loop_count++;
					  				$item_count = 1;
					  				$sign_add_count_inside = 0;
		  						}
		  						else
					  			{
					  				$real_loop_count++;
					  			}
	  						}
			  			}
			  			else
			  			{
			  				if($value['parentID'] == 0)
				  			{
				  				if($value['uniqueID'] == $last_uniqueID)
				  				{
				  					$item_count++;
				  					if($sign_add_count_inside == 1)
				  					{
				  						$result[0]['item_count'] = $item_count;
				  					}
				  					else
				  					{
				  						$result[$count_inside]['item_count'] = $item_count;
				  					}
					  				$last_patientID = $value['patientID'];
					  				$last_uniqueID = $value['uniqueID'];
					  				$real_loop_count++;
				  				}
				  				else
				  				{
				  					$check_first_order = get_patient_first_order($value['patientID']);
						  			if($check_first_order['uniqueID'] != $value['uniqueID'])
						  			{
						  				if($sign_add_count_inside == 0)
						  				{
						  					$count_inside++;
						  				}
					  					$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
						  				$result[$count_inside] = $patient_details;
						  				$result[$count_inside]['item_count'] = 1;
						  				$last_patientID = $value['patientID'];
						  				$last_uniqueID = $value['uniqueID'];
						  				$real_loop_count++;
					  					$item_count = 1;
					  					$sign_add_count_inside = 0;
						  			}
						  			else
						  			{
						  				$real_loop_count++;
						  			}
				  				}
				  			}
			  			}
	  				}
	  				else
	  				{
	  					if($value['parentID'] == 0)
			  			{
		  					if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
		  					{
		  						$check_first_order = get_patient_first_order($value['patientID']);
					  			if($check_first_order['uniqueID'] != $value['uniqueID'])
					  			{
					  				$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
					  				$result[$count_inside] = $patient_details;
					  				$result[$count_inside]['item_count'] = 1;
					  				$last_patientID = $value['patientID'];
					  				$last_uniqueID = $value['uniqueID'];
					  				$real_loop_count++;
					  				if($real_loop_count < $query_result_count)
					  				{
					  					$count_inside++;
					  					$item_count = 1;
					  					$sign_add_count_inside = 1;
					  				}
					  			}
					  			else
					  			{
					  				$real_loop_count++;
					  			}
		  					}		  				
			  			}
	  				}
		  		}
		  		if($filter_from != "" && $filter_to != "")
		  		{
		  			$current_date = date('Y-m-d');
		  			if(($filter_from <= $current_date))
	                {
	                    if($filter_from <= $filter_to)
	                    {
	                        if($filter_to == $current_date || $filter_to > $current_date)
	                        {
			  					$query_result_final_here = get_patient_new_item_list_v2($current_date,"","",$hospiceID,$this->session->userdata('user_location'));
						  		$last_patientID = 0;
						  		$last_uniqueID = 0;
						  		$item_count = 0;
						  		$last_patientID_sign = 0;

						  		$query_result_count = 0;
						  		foreach($query_result_final_here as $key => $value)
						  		{
						  			if($value['parentID'] == 0)
						  			{
						  				$query_result_count++;
						  			}
						  		}

						  		foreach ($query_result_final_here as $key => $value) {
					  				if($count_inside != 0)
					  				{
					  					if($last_patientID != $value['patientID'])
					  					{
					  						if($value['parentID'] == 0)
					  						{
					  							$check_first_order = get_patient_first_order($value['patientID']);
									  			if($check_first_order['uniqueID'] != $value['uniqueID'])
									  			{
									  				if($sign_add_count_inside == 0)
									  				{
									  					$count_inside++;
									  				}
								  					$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
									  				$result[$count_inside] = $patient_details;
									  				$result[$count_inside]['item_count'] = 1;
									  				$last_patientID = $value['patientID'];
									  				$last_uniqueID = $value['uniqueID'];
									  				$real_loop_count++;
									  				$item_count = 1;
									  				$sign_add_count_inside = 0;
						  						}
						  						else
									  			{
									  				$real_loop_count++;
									  			}
					  						}
							  			}
							  			else
							  			{
							  				if($value['parentID'] == 0)
								  			{
								  				if($value['uniqueID'] == $last_uniqueID)
								  				{
								  					$item_count++;
								  					if($sign_add_count_inside == 1)
								  					{
								  						$result[0]['item_count'] = $item_count;
								  					}
								  					else
								  					{
								  						$result[$count_inside]['item_count'] = $item_count;
								  					}
									  				$last_patientID = $value['patientID'];
									  				$last_uniqueID = $value['uniqueID'];
									  				$real_loop_count++;
								  				}
								  				else
								  				{
								  					$check_first_order = get_patient_first_order($value['patientID']);
										  			if($check_first_order['uniqueID'] != $value['uniqueID'])
										  			{
										  				if($sign_add_count_inside == 0)
										  				{
										  					$count_inside++;
										  				}
									  					$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
										  				$result[$count_inside] = $patient_details;
										  				$result[$count_inside]['item_count'] = 1;
										  				$last_patientID = $value['patientID'];
										  				$last_uniqueID = $value['uniqueID'];
										  				$real_loop_count++;
									  					$item_count = 1;
									  					$sign_add_count_inside = 0;
										  			}
										  			else
										  			{
										  				$real_loop_count++;
										  			}
								  				}
								  			}
							  			}
					  				}
					  				else
					  				{
					  					if($value['parentID'] == 0)
							  			{
						  					if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
						  					{
						  						$check_first_order = get_patient_first_order($value['patientID']);
									  			if($check_first_order['uniqueID'] != $value['uniqueID'])
									  			{
									  				$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
									  				$result[$count_inside] = $patient_details;
									  				$result[$count_inside]['item_count'] = 1;
									  				$last_patientID = $value['patientID'];
									  				$last_uniqueID = $value['uniqueID'];
									  				$real_loop_count++;
									  				if($real_loop_count < $query_result_count)
									  				{
									  					$count_inside++;
									  					$item_count = 1;
									  					$sign_add_count_inside = 1;
									  				}
									  			}
									  			else
									  			{
									  				$real_loop_count++;
									  			}
						  					}		  				
							  			}
					  				}
						  		}
						  		$current_date = "";
						  		if($filter_from == $current_date && $filter_to == $current_date)
						  		{
						  			$query_result = $query_result_final_here;
						  		}
						  		else
						  		{
						  			$query_result = array_merge($query_result,$query_result_final_here);
						  		}
						  		
						  	}
					  	}
		  			}		  			
		  		}
		  	}
		  	else if($status_name == "exchange")
		  	{
		  		$data['sign_here'] = 3;
		  		$query_result = get_patient_exchange_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
		  		$count_inside = 0;
                $last_patientID = 0;
                $last_uniqueID = 0;
                $item_count = 0;
                $sign_add_count_inside = 0;
                $real_loop_count = 0;
                $query_result_count = 0;

		  		foreach($query_result as $key => $value)
		  		{
		  			if($value['parentID'] == 0)
		  			{
		  				$query_result_count++;
		  			}
		  		}
		  		foreach ($query_result as $key => $value) {
	  				if($count_inside != 0)
                    {
                        if($last_patientID != $value['patientID'])
                        {
                            if($value['parentID'] == 0)
                            {
                                $check_first_order = get_patient_first_order($value['patientID']);
                                if($check_first_order['uniqueID'] != $value['uniqueID'])
                                {
                                    if($sign_add_count_inside == 0)
                                    {
                                        $count_inside++;
                                    }
                                    $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
                                    $result[$count_inside] = $patient_details;
                                    $result[$count_inside]['item_count'] = 1;
                                    $last_patientID = $value['patientID'];
                                    $last_uniqueID = $value['uniqueID'];
                                    $real_loop_count++;
                                    $item_count = 1;
                                    $sign_add_count_inside = 0;
                                }
                                else
                                {
                                    $real_loop_count++;
                                }
                            }
                        }
                        else
                        {
                            if($value['parentID'] == 0)
                            {
                                if($value['uniqueID'] == $last_uniqueID)
                                {
                                    $item_count++;
                                    if($sign_add_count_inside == 1)
                                    {
                                        $result[0]['item_count'] = $item_count;
                                    }
                                    else
                                    {
                                        $result[$count_inside]['item_count'] = $item_count;
                                    }
                                    $last_patientID = $value['patientID'];
                                    $last_uniqueID = $value['uniqueID'];
                                    $real_loop_count++;
                                }
                                else
                                {
                                    $check_first_order = get_patient_first_order($value['patientID']);
                                    if($check_first_order['uniqueID'] != $value['uniqueID'])
                                    {
                                        if($sign_add_count_inside == 0)
                                        {
                                              $count_inside++;
                                        }
                                        $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
                                        $result[$count_inside] = $patient_details;
                                        $result[$count_inside]['item_count'] = 1;
                                        $last_patientID = $value['patientID'];
                                        $last_uniqueID = $value['uniqueID'];
                                        $real_loop_count++;
                                        $item_count = 1;
                                        $sign_add_count_inside = 0;
                                    }
                                    else
                                    {
                                        $real_loop_count++;
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        if($value['parentID'] == 0)
                        {
                            if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
                            {
                                $check_first_order = get_patient_first_order($value['patientID']);
                                if($check_first_order['uniqueID'] != $value['uniqueID'])
                                {
                                    $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
                                    $result[$count_inside] = $patient_details;
                                    $result[$count_inside]['item_count'] = 1;
                                    $last_patientID = $value['patientID'];
                                    $last_uniqueID = $value['uniqueID'];
                                    $real_loop_count++;
                                    if($real_loop_count < $query_result_count)
                                    {
	                                    $count_inside++;
	                                    $item_count = 1;
	                                    $sign_add_count_inside = 1;
                                    }
                                }
                                else
                                {
                                    $real_loop_count++;
                                }
                            }                                   
                        }
                    }
	  			}

	  			if($filter_from != "" && $filter_to != "")
		  		{
		  			$current_date = date('Y-m-d');
		  			if(($filter_from <= $current_date))
	                {
	                    if($filter_from <= $filter_to)
	                    {
	                        if($filter_to == $current_date || $filter_to > $current_date)
	                        {
			  					$query_result_final_here = get_patient_exchange_list_v2($current_date,"","",$hospiceID,$this->session->userdata('user_location'));
		                        $last_patientID = 0;
		                        $last_uniqueID = 0;
		                        $item_count = 0;
		                        $sign_add_count_inside = 0;
		                        $real_loop_count = 0;
		                        $query_result_count = 0;

						  		foreach($query_result_final_here as $key => $value)
						  		{
						  			if($value['parentID'] == 0)
						  			{
						  				$query_result_count++;
						  			}
						  		}
						  		foreach ($query_result_final_here as $key => $value) {
					  				if($count_inside != 0)
					                {
					                    if($last_patientID != $value['patientID'])
					                    {
					                        if($value['parentID'] == 0)
					                        {
					                            $check_first_order = get_patient_first_order($value['patientID']);
					                            if($check_first_order['uniqueID'] != $value['uniqueID'])
					                            {
					                                if($sign_add_count_inside == 0)
					                                {
					                                    $count_inside++;
					                                }
					                                $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
					                                $result[$count_inside] = $patient_details;
					                                $result[$count_inside]['item_count'] = 1;
					                                $last_patientID = $value['patientID'];
					                                $last_uniqueID = $value['uniqueID'];
					                                $real_loop_count++;
					                                $item_count = 1;
					                                $sign_add_count_inside = 0;
					                            }
					                            else
					                            {
					                                $real_loop_count++;
					                            }
					                        }
					                    }
					                    else
					                    {
					                        if($value['parentID'] == 0)
					                        {
					                            if($value['uniqueID'] == $last_uniqueID)
					                            {
					                                $item_count++;
					                                if($sign_add_count_inside == 1)
					                                {
					                                    $result[0]['item_count'] = $item_count;
					                                }
					                                else
					                                {
					                                    $result[$count_inside]['item_count'] = $item_count;
					                                }
					                                $last_patientID = $value['patientID'];
					                                $last_uniqueID = $value['uniqueID'];
					                                $real_loop_count++;
					                            }
					                            else
					                            {
					                                $check_first_order = get_patient_first_order($value['patientID']);
					                                if($check_first_order['uniqueID'] != $value['uniqueID'])
					                                {
					                                    if($sign_add_count_inside == 0)
					                                    {
					                                          $count_inside++;
					                                    }
					                                    $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
					                                    $result[$count_inside] = $patient_details;
					                                    $result[$count_inside]['item_count'] = 1;
					                                    $last_patientID = $value['patientID'];
					                                    $last_uniqueID = $value['uniqueID'];
					                                    $real_loop_count++;
					                                    $item_count = 1;
					                                    $sign_add_count_inside = 0;
					                                }
					                                else
					                                {
					                                    $real_loop_count++;
					                                }
					                            }
					                        }
					                    }
					                }
					                else
					                {
					                    if($value['parentID'] == 0)
					                    {
					                        if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
					                        {
					                            $check_first_order = get_patient_first_order($value['patientID']);
					                            if($check_first_order['uniqueID'] != $value['uniqueID'])
					                            {
					                                $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
					                                $result[$count_inside] = $patient_details;
					                                $result[$count_inside]['item_count'] = 1;
					                                $last_patientID = $value['patientID'];
					                                $last_uniqueID = $value['uniqueID'];
					                                $real_loop_count++;
					                                if($real_loop_count < $query_result_count)
					                                {
					                                    $count_inside++;
					                                    $item_count = 1;
					                                    $sign_add_count_inside = 1;
					                                }
					                            }
					                            else
					                            {
					                                $real_loop_count++;
					                            }
					                        }                                   
					                    }
					                }
					  			}
						  		$current_date = "";
						  		$query_result = array_merge($query_result,$query_result_final_here);
						  	}
					  	}
		  			}		  			
		  		}
		  	}
		  	else if($status_name == "pickup")
		  	{
		  		$result_2 = array();
		  		$data['sign_here'] = 2;
		  		$query_result = get_patient_pickup_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
		  		$result_1 = array_msort($query_result, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
		  		if($filter_from != "" && $filter_to != "")
		  		{
		  			$current_date = date('Y-m-d');
		  			if(($filter_from <= $current_date))
	                {
	                    if($filter_from <= $filter_to)
	                    {
	                        if($filter_to == $current_date || $filter_to > $current_date)
	                        {
				  				$filter_from_new = "";
			  					$filter_to_new = "";
			  					$query_result = get_patient_pickup_list_v2($current_date,$filter_from_new,$filter_to_new,$hospiceID,$this->session->userdata('user_location'));
		  						$result_2 = array_msort($query_result, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
		  						
						  		$current_date = "";
						  	}
					  	}
		  			}		  			
		  		}
		  		$result = array_merge($result_1,$result_2);
		  	}
		  	else if($status_name == "pt_move")
		  	{
		  		$data['sign_here'] = 1;
		  		$new_result_here = get_ptmove_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
		  		$result_here = array_msort($new_result_here, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));

		  		$count = 0;
		  		foreach ($result_here as $value) {
		  			$query_result = get_ptmove_address_first_order($value['patientID'],$value['addressID']);
		  			if($query_result['uniqueID'] == $value['uniqueID'])
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
		  		}
		  		if($filter_from != "" && $filter_to != "")
		  		{
		  			$current_date = date('Y-m-d');
		  			if(($filter_from <= $current_date))
	                {
	                    if($filter_from <= $filter_to)
	                    {
	                        if($filter_to == $current_date || $filter_to > $current_date)
	                        {
				  				$filter_from_new = "";
			  					$filter_to_new = "";
			  					$new_result_here_new = get_ptmove_list_v2($current_date,$filter_from_new,$filter_to_new,$hospiceID,$this->session->userdata('user_location'));
						  		$result_here_new = array_msort($new_result_here_new, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));

						  		foreach ($result_here_new as $value) {
						  			$query_result = get_ptmove_address_first_order($value['patientID'],$value['addressID']);
						  			if($query_result['uniqueID'] == $value['uniqueID'])
						  			{
						  				$result[$count] = $value;
						  				$count++;
						  			}
						  		}
						  		$current_date = "";
						  	}
					  	}
		  			}		  			
		  		}
		  	}
		  	else
		  	{
		  		$data['sign_here'] = 1;
		  		$new_result_here = get_respite_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
		  		$result_here = array_msort($new_result_here, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));

		  		$count = 0;
		  		foreach ($result_here as $value) {
		  			$query_result = get_respite_address_first_order($value['patientID'],$value['addressID']);
		  			if($query_result['uniqueID'] == $value['uniqueID'])
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
		  		}
		  		if($filter_from != "" && $filter_to != "")
		  		{
		  			$current_date = date('Y-m-d');
		  			if(($filter_from <= $current_date))
	                {
	                    if($filter_from <= $filter_to)
	                    {
	                        if($filter_to == $current_date || $filter_to > $current_date)
	                        {
				  				$filter_from_new = "";
			  					$filter_to_new = "";
			  					$new_result_here_new = get_respite_list_v2($current_date,$filter_from_new,$filter_to_new,$hospiceID,$this->session->userdata('user_location'));
						  		$result_here_new = array_msort($new_result_here_new, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));

						  		foreach ($result_here_new as $value) {
						  			$query_result = get_respite_address_first_order($value['patientID'],$value['addressID']);
						  			if($query_result['uniqueID'] == $value['uniqueID'])
						  			{
						  				$result[$count] = $value;
						  				$count++;
						  			}
						  		}
						  		$current_date = "";
						  	}
					  	}
		  			}		  			
		  		}
		  	}
		  	$data['patient_list_temp'] = array_msort($result, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
		  	
		  	$count_loop = 0; 
		  	foreach($data['patient_list_temp'] as $loop_key => $loop_value)
		  	{
		  		$data['patient_list'][$count_loop] = $loop_value;
		  		$count_loop++;
		  	}
		  	$data['query_result'] = $query_result;

		  	if($data['sign_here'] == 3)
		  	{
		  		if(!empty($data['patient_list'])){
		  			$another_count = 0;
		  			$data['item_names'] = array();
		    		foreach($data['patient_list'] as $row){
				  		$count_here = 1;
		    			foreach ($query_result as $key_inside => $value_inside) {
		    				if($row['patientID'] == $value_inside['patientID'] && $row['uniqueID'] == $value_inside['uniqueID']){
		    					if($value_inside['parentID'] == 0){

		    						// Patient Lift Sling, Oxygen Concentrators, Oxygen E Portable System, Oxygen Liquid Portable
		    						if($value_inside['equipmentID'] == 316 || $value_inside['equipmentID'] == 325 || $value_inside['equipmentID'] == 334 || $value_inside['equipmentID'] == 343 || $value_inside['equipmentID'] == 174 || $value_inside['equipmentID'] == 176 || $value_inside['equipmentID'] == 179)
		    						{
		    		 					$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
		    		 				}
		    		 				else
		    		 				{
		    		 					$subequipment_id = get_subequipment_id($value_inside['equipmentID']);
		    		 					if($subequipment_id)
		                              	{
		                                    $count = 0;
		                                    $patient_lift_sling_count = 0;
		                                    $high_low_full_electric_hospital_bed_count = 0;
		                                    $my_count_sign = 0;
		                                    $my_first_sign = 0;
		                                    $my_second_sign = 0;
		                                
		                                	foreach ($subequipment_id as $key) {
		                                  		$value = get_equal_subequipment_order($key['equipmentID'], $value_inside['uniqueID']);
		                                  		if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
		                                  		{
			                                        if(empty($value))
			                                        {
			                                          $my_first_sign = 1;
			                                        }
		                                  		}
		                                  		if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
		                                  		{
			                                        if(empty($value))
			                                        {
			                                          $my_second_sign = 1;
			                                        }
		                                  		}
		                                  		if($my_second_sign == 1 && $my_first_sign == 1)
		                                  		{		
		                                    		$my_count_sign = 1;
		                                  		}
		                                  		if($value)
		                                  		{
		                                    		$count++;
		                                        	//full electric hospital bed equipment
		                    						if($value_inside['equipmentID'] == 55 || $value_inside['equipmentID'] == 20){
		                								$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." "."Full Electric ".$value_inside['key_desc']." With ".$key['key_desc']." ";
		    		 								} 
			                                        //Hi-Low Full Electric Hospital Bed equipment
			                                        else if($value_inside['equipmentID'] == 19 || $value_inside['equipmentID'] == 398)
			                                        {
			                                        	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." With ".$key['key_desc']." ";
			                                        }
			                                        //Patient Lift with Sling
			                                        else if($value_inside['equipmentID'] == 56 || $value_inside['equipmentID'] == 21)
			                                        {
			                                        	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." "."Patient Lift With ".$key['key_desc']." ";
			                                        }
			                                        //Patient Lift Electric with Sling
			                                        else if($value_inside['equipmentID'] == 353)
			                                        {
			                                        	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." "."Patient Lift Electric With ".$key['key_desc']." ";
			                                        }
			                                        //Patient Lift Sling
			                                        else if($value_inside['equipmentID'] == 196)
			                                        {
			                                        	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$key['key_desc']." ";
			                                        }
			                                        //(54 & 17) Geri Chair || (66 & 39) Shower Chair
			                                        else if($value_inside['equipmentID'] == 54 || $value_inside['equipmentID'] == 17 || $value_inside['equipmentID'] == 66 || $value_inside['equipmentID'] == 39)
			                                        { 
			                                        	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ".$key['key_desc']." ";
			                                        }
			                                        //Oxygen Cylinder Rack
			                                        else if($value_inside['equipmentID'] == 32 || $value_inside['equipmentID'] == 393)
			                                        {
			                                        	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." "."Oxygen ".$key['key_desc']." ";
			                                        	break;
			                                        }
		                                        	//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining 
			                                        else if($value_inside['equipmentID'] == 49 || $value_inside['equipmentID'] == 71 || $value_inside['equipmentID'] == 269 || $value_inside['equipmentID'] == 64) 
			                                        {         
		                                          		if($my_count_sign == 0)
			                                          	{    
				                                            //wheelchair & wheelchair reclining
				                                            if($count == 1)
				                                            { 
				                                            	if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
				                                              	{
					                                                $key['key_desc'] = '16" Narrow';
				                                              	} 
				                                              	else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85) 
				                                              	{
					                                                $key['key_desc'] = '18" Standard';
				                                              	} 
				                                              	else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392) 
				                                              	{
					                                                $key['key_desc'] = '20" Wide';
				                                              	} 
				                                              	else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127) 
				                                              	{
					                                                $key['key_desc'] = '22" Extra Wide';
				                                              	} 
				                                              	else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128) 
				                                              	{
					                                                $key['key_desc'] = '24" Bariatric';
				                                              	}
				                                              	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$key['key_desc']." ".$value_inside['key_desc']." ";
			                                              	} 
				                                            else 
				                                            {
				                                              	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." "." With ".$key['key_desc']." ";
				                                            }
				                                        }
				                                        else
				                                        {
				                                        	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".' 20" Wide '.$value_inside['key_desc'];
				                                            if($key['equipmentID'] == 86 || $key['equipmentID'] == 272) 
				                                            {
				                                                $data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." "." With Elevating Legrests ";
				                                                break;
				                                            } 
				                                            else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273) 
				                                            {
				                                                $data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." "." With Footrests ";
				                                                break;
				                                            }
			                                            }
			                                        } 
			                                        else if($value_inside['equipmentID'] == 30)
			                                        {
			                                          	if(strtotime($value_inside['date_ordered']) >= 1464073200 && strtotime($value_inside['pickup_date']) >= 1464073200)
			                                          	{
			                                            	if($count == 3)
			                                            	{
			                                            		$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." With ".$key['key_desc']." ";
			                                            	}
			                                          	}
			                                          	else
			                                          	{
			                                          		$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
			                                          	}
			                                        }
			                                        //equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
			                                        else if($value_inside['equipmentID'] == 306 || $value_inside['equipmentID'] == 309 || $value_inside['equipmentID'] == 313 || $value_inside['equipmentID'] == 40 || $value_inside['equipmentID'] == 32  || $value_inside['equipmentID'] == 393 || $value_inside['equipmentID'] == 16 || $value_inside['equipmentID'] == 67 || $value_inside['equipmentID'] == 4 || $value_inside['equipmentID'] == 36)
			                                        { 
			                                        	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc'];
			                                        	$samp =  get_misc_item_description($value_inside['equipmentID'],$value_inside['uniqueID']);
		                                            	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." "." (".$samp.") ";
		                  							
		                  								break;
			                                        } 
			                                        else if($value_inside['equipmentID'] == 62 || $value_inside['equipmentID'] == 31)
			                                        {
			                                            $samp_conserving_device =  get_oxygen_conserving_device($value_inside['equipmentID'],$value_inside['uniqueID']);
			                                            if($count == 1)
			                                            {
			                                            	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ".$samp_conserving_device." ";
			                                            }
					                                }
					                                else if($value_inside['equipmentID'] == 282)
					                                {
					                                  
					                                }
					                                //equipments that has no subequipment but gets inside the $value if statement
					                                else if($value_inside['equipmentID'] == 14)
					                                {
					                                	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
					                                } 
			                                        else 
			                                        {
		                                          		if($value_inside['categoryID'] == 1)
			                                          	{
				                                            $non_capped_copy = get_non_capped_copy($value_inside['equipmentID']);
				                                            if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
				                                            {
				                                            	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
				                                            	break;
				                                            }
				                                            else if($non_capped_copy['noncapped_reference'] == 14)
				                                            {
				                                            	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
				                                            }
				                                            else if($non_capped_copy['noncapped_reference'] == 282)
				                                            {
				                                              $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($value_inside['equipmentID'],$value_inside['uniqueID']);
				                                              $data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." With ".$samp_hospital_bed_extra_long." ";
				                                              break;
				                                            }
				                                            else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
				                                            {
				                                            	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." "."Patient Lift With ".$key['key_desc']." ";
				                                            }
				                                            else if($non_capped_copy['noncapped_reference'] == 353)
				                                            {
				                                            	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." "."Patient Lift Electric With ".$key['key_desc']." ";
				                                            }
				                                            else
				                                            {
				                                            	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
				                                            }
			                                            }
			                                            else
			                                            {
			                                            	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
			                                            }     
			                                        }
			                                    } //end of $value
			                                    // for Oxygen E cylinder do not remove as it will cause errors
			                                    else if($value_inside['equipmentID'] == 62 || $value_inside['equipmentID'] == 31)
			                                    {
			                                        break;
			                                    }
			                                    else if($value_inside['equipmentID'] == 32 || $value_inside['equipmentID'] == 393)
			                                    {

			                                    }
			                                    else if($value_inside['equipmentID'] == 282)
			                                    {
			                                        $samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($value_inside['equipmentID'],$value_inside['uniqueID']);
			                                        $data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." With ".$samp_hospital_bed_extra_long." ";
			                                    	break;
			                                    }
			                                    //equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
			                                    else if ($value_inside['equipmentID'] == 10 || $value_inside['equipmentID'] == 36 || $value_inside['equipmentID'] == 31 || $value_inside['equipmentID'] == 32 || $value_inside['equipmentID'] == 393 || $value_inside['equipmentID'] == 282 || $value_inside['equipmentID'] == 286 || $value_inside['equipmentID'] == 62 || $value_inside['equipmentID'] == 313 || $value_inside['equipmentID'] == 309 || $value_inside['equipmentID'] == 306 || $value_inside['equipmentID'] == 4)
			                                    {
			                                    	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
			                                    	break;
			                                    } //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
			                                    else if($value_inside['equipmentID'] == 11 || $value_inside['equipmentID'] == 178 || $value_inside['equipmentID'] == 9 || $value_inside['equipmentID'] == 149) 
			                                    { 
			                                    	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
			                                   	} 
		                                        //for equipments with subequipment but does not fall in $value 
		                                        else if($value_inside['equipmentID'] == 54 || $value_inside['equipmentID'] == 17 || $value_inside['equipmentID'] == 174 || $value_inside['equipmentID'] == 398 || $value_inside['equipmentID'] == 282 || $value_inside['equipmentID'] == 196 || $value_inside['equipmentID'] == 353 || $value_inside['equipmentID'] == 56 || $value_inside['equipmentID'] == 21 || $value_inside['equipmentID'] == 176 || $value_inside['equipmentID'] == 179 ||  $value_inside['equipmentID'] == 30 || $value_inside['equipmentID'] == 40 || $value_inside['equipmentID'] == 67 || $value_inside['equipmentID'] == 39 || $value_inside['equipmentID'] == 66 || $value_inside['equipmentID'] == 19 || $value_inside['equipmentID'] == 269 || $value_inside['equipmentID'] == 49 || $value_inside['equipmentID'] == 20 || $value_inside['equipmentID'] == 55 || $value_inside['equipmentID'] == 71 || $value_inside['equipmentID'] == 64)   
		                                        { 
		                                        	if($value_inside['equipmentID'] == 196 || $value_inside['equipmentID'] == 56 || $value_inside['equipmentID'] == 21 || $value_inside['equipmentID'] == 353)
		                                        	{
		                                      			$patient_lift_sling_count++;
		                                      			if($patient_lift_sling_count == 6)
		                                      			{
		                                      				$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
		                                      			}
		                							} 
		                							else if($value_inside['equipmentID'] == 398)
		                							{
		                  								$high_low_full_electric_hospital_bed_count++;
		                  								if($high_low_full_electric_hospital_bed_count == 2){
		                  									$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
		                  								}
		                                    		} 
		                                  		} 
		                                  		else 
		                                  		{ 
			                                        if($value_inside['categoryID'] == 1)
			                                        {
		                                      			$non_capped_copy = get_non_capped_copy($value_inside['equipmentID']);
		                                      			if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
		                                      			{
		                                      				$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
		                                      				break;
				                                        }
				                                        else if($non_capped_copy['noncapped_reference'] == 14)
				                                        {
				                                        	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
				                                        }
				                                        else
				                                        {

				                                        }
				                                    }
				                                    else
				                                    {
				                                    	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
				                                    }
			                                    }
			                                }
			                            } 
			                            else 
			                            { 
		                                	$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$value_inside['key_desc']." ";
				    					}
				    				}

				    				//quantity base on the categories
		                            //there are 3 categories
		                            // capped,non-capped,disposable
		                            $quantity = 1;
		                            if($value_inside['categoryID']!=3) //cappped=1, noncapped=2
		                            {
		                              //if noncapped get children quantities
		                              if($value_inside['categoryID']==2)
		                              {
		                                if($value_inside['parentID']==0 AND $value_inside['equipment_value']>1)
		                                {
		                                  $quantity = $value_inside['equipment_value'];
		                                }
		                                else
		                                {
		                                  if($value_inside['equipmentID'] == 4 || $value_inside['equipmentID'] == 9 || $value_inside['equipmentID'] == 176 || $value_inside['equipmentID'] == 30)
		                                  {
		                                    if(empty($value_inside['equipment_value']))
		                                    {
		                                      $temp = get_noncapped_quantity($value_inside['equipmentID'], $value_inside['uniqueID']);
		                                      $quantity = ($temp>0)? $temp : 1;
		                                    }
		                                    else
		                                    {
		                                      $quantity = $value_inside['equipment_value'];
		                                    }
		                                  } else {
		                                    $temp = get_noncapped_quantity($value_inside['equipmentID'], $value_inside['uniqueID']);
		                                    $quantity = ($temp>0)? $temp : 1;
		                                  }
		                                }
		                              }
		                              else //capped items
		                              {
		                                $non_capped_copy = get_non_capped_copy($value_inside['equipmentID']);
		                                //if the equipment is miscellaneous capped item
		                                if($value_inside['equipmentID'] == 313 || $value_inside['equipmentID'] == 206)
		                                {
		                                  $temp = get_noncapped_quantity($value_inside['equipmentID'], $value_inside['uniqueID']);
		                                  $quantity = ($temp>0)? $temp : 1;
		                                }else if($non_capped_copy['noncapped_reference'] == 14){
		                                  $temp = get_noncapped_quantity($value_inside['equipmentID'], $value_inside['uniqueID']);
		                                  $quantity = ($temp>0)? $temp : 1;
		                                }else {
		                                  $quantity = ($value_inside['equipment_value']>0)? $value_inside['equipment_value'] : 1;
		                                }
		                              }
		                            }
		                            else //disposable items
		                            {
		                              if($value_inside['equipment_value'] > 1)
		                              {
		                                $quantity = $value_inside['equipment_value'];
		                              }
		                              else
		                              {
		                                $quantity = (get_disposable_quantity($value_inside['equipmentID'], $value_inside['uniqueID'])>0)? get_disposable_quantity($value_inside['equipmentID'], $value_inside['uniqueID']) : 0;
		                                if($value_inside['equipment_value'] == 0)
		                                {
		                                  $quantity = 0;
		                                }

		                                if(empty($value_inside['equipment_value']))
		                                {
		                                  $quantity = get_disposable_quantity($value_inside['equipmentID'],$value_inside['uniqueID']);
		                                  if(empty($quantity))
		                                  {
		                                    $quantity = 1;
		                                  }
		                                }
		                              }
		                            }

		                            $data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." ".$quantity."ea ";
				    				if($count_here < $row['item_count'])
				    				{
				    					$data['item_names'][$row['uniqueID']] = $data['item_names'][$row['uniqueID']]." "."<br />";
				    					$count_here++;
				    				}
				    				// $last_looped_uniqueID = $row['uniqueID'];
				    			}
				    		}
				    	}
				    	$another_count++;
				    }
				}
		  	}	
			echo json_encode($data);
		}

		// $list_active_patients_result = list_patients_by_hospice($hospiceID);
			// if(!empty($list_active_patients_result))
			// {
	  	// 	foreach($list_active_patients_result as $value) 
    // 		{
    // 			$query_result = get_patient_first_order($value['patientID']);
    // 			if($query_result['order_status'] == "active")
	// 			{
	// 				$result[$count] = $value;
	// 				$added_patientIDs[$count] = $value['patientID'];
			// 			$count++;
	// 			}
    // 		}
    // 	}

	// $list_active_patients_result = list_patients_by_hospice($hospiceID);
	  			// if(!empty($list_active_patients_result))
	  			// {
	  			// 	if($filter_from == $current_day && $filter_to == $current_day || $filter_from == $current_day && $filter_to > $current_day)
	  			// 	{
	  			// 		if(($filter_from <= $current_day))
		    //             {
		    //                 if($filter_from <= $filter_to)
		    //                 {
		    //                     if($filter_to == $current_day || $filter_to > $current_day)
		    //                     {
		    //                     	foreach($list_active_patients_result as $value) 
						//     		{
						//     			$query_result_new = get_patient_first_order($value['patientID']);
						//     			if($query_result_new['order_status'] == "active")
						//     			{
						//     				if(!in_array($value['patientID'], $added_patientIDs))
						//     				{
						//     					$result[$count] = $value;
						//     					$added_patientIDs[$count] = $value['patientID'];
					 //  							$count++;
						//     				}
						//     			}
						//     		}
		    //                     }
		    //                 }
		    //             }
	  			// 	}
	  			// 	else
	  			// 	{
	  			// 		foreach($list_active_patients_result as $value) 
			   //  		{
			   //  			$query_result = get_patient_first_order($value['patientID']);
			    			
			   //  			if($filter_to == $current_day || $filter_to > $current_day)
			   //  			{
			   //  				if($query_result['actual_order_date'] >= $filter_from && $query_result['actual_order_date'] < $filter_to)
				  //   			{
				  //   				$result[$count] = $value;
				  //   				$added_patientIDs[$count] = $value['patientID'];
				  // 					$count++;
				  //   			}
			   //  			}
			   //  			else
			   //  			{
			   //  				if($query_result['actual_order_date'] >= $filter_from && $query_result['actual_order_date'] <= $filter_to)
				  //   			{
				  //   				$result[$count] = $value;
				  //   				$added_patientIDs[$count] = $value['patientID'];
				  // 					$count++;
				  //   			}
			   //  			}
			   //  		}

			   //  		if(($filter_from <= $current_day))
		    //             {
		    //                 if($filter_from <= $filter_to)
		    //                 {
		    //                     if($filter_to == $current_day || $filter_to > $current_day)
		    //                     {
		    //                     	foreach($list_active_patients_result as $value) 
						//     		{
						//     			$query_result_new = get_patient_first_order($value['patientID']);
						//     			if($query_result_new['order_status'] == "active")
						//     			{
						//     				if(!in_array($value['patientID'], $added_patientIDs))
						//     				{
						//     					$result[$count] = $value;
						//     					$added_patientIDs[$count] = $value['patientID'];
					 //  							$count++;
						//     				}
						//     			}
						//     		}
		    //                     }
		    //                 }
		    //             }
	  			// 	}
	  			// }

		// $result_here = get_new_patient_list($current_date,$filter_from,$filter_to,$hospiceID);
  		// $new_result_here = array_msort($result_here, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
  		// $count = 0;
  		// foreach ($new_result_here as $value) {
  		// 	$query_result = get_patient_first_order($value['patientID']);
  		// 	if($query_result['uniqueID'] == $value['uniqueID'])
  		// 	{
  		// 		$result[$count] = $value;
  		// 		$count++;
  		// 	}
  		// }
  		// if($filter_from != "" && $filter_to != "")
  		// {
  		// 	$current_date = date('Y-m-d');
  		// 	if(($filter_from <= $current_date))
 //            {
 //                if($filter_from <= $filter_to)
 //                {
 //                    if($filter_to == $current_date || $filter_to > $current_date)
 //                    {
		  // 				$filter_from_new = "";
	  	// 				$filter_to_new = "";
	  	// 				$result_here = get_new_patient_list($current_date,$filter_from_new,$filter_to_new,$hospiceID);
				//   		$new_result_here = array_msort($result_here, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
				//   		foreach ($new_result_here as $value) {
				//   			$query_result = get_patient_first_order($value['patientID']);
				//   			if($query_result['uniqueID'] == $value['uniqueID'])
				//   			{
				//   				$result[$count] = $value;
				//   				$count++;
				//   			}
				//   		}
				//   		$current_date = "";
				//   	}
			 //  	}
  		// 	}		  			
  		// }

	
		public function view_each_residence_status($residence_status_name,$hospiceID)
		{
			$data = array();
			$data['residence_status_name'] = $residence_status_name;
			$data['hospiceID'] = $hospiceID;

			$this->templating_library->set('title','');
			$this->templating_library->set_view('pages/view_residence_status_details','pages/view_residence_status_details', $data);
		}

		public function sort_residence_status_details($filter_from,$filter_to,$hospiceID,$status_name)
		{
			$current_date = "";
    		$data['patient_list'] = array();
    		$result = array();
    		$count = 0;

	  		if($status_name == "assisted_living")
		  	{
		  		$status_name_new = "Assisted Living";
		  	}
		  	else if($status_name == "group_home")
		  	{
		  		$status_name_new = "Group Home";
		  	}
		  	else if($status_name == "hic_home")
		  	{
		  		$status_name_new = "Hic Home";
		  	}
		  	else if($status_name == "home_care")
		  	{
		  		$status_name_new = "Home Care";
		  	}
		  	else
		  	{
		  		$status_name_new = "Skilled Nursing Facility";
		  	}

		  	if($filter_from == 0 || $filter_to == 0)
	  		{
	  			$current_date = date('Y-m-d');

	  			$latest_patient_on_list = get_latest_patient_on_list_v2($this->session->userdata('user_location'));
	    		$border_bottom = $latest_patient_on_list['patientID'] - 500;
	    		$border_top = "";

	    		$list_active_patients_result = list_active_patients_by_hospice_and_residence_v3($border_bottom,$border_top,$hospiceID,$residence_status_name_new,$this->session->userdata('user_location'));
	    		foreach($list_active_patients_result as $value) 
	    		{
	    			$query_result = get_patient_first_order($value['patientID']);
		  			if(date("Y-m-d", $query_result['uniqueID']) == $current_date)
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
	    		}
	  		}
	  		else
	  		{
	  			$list_active_patients_result = list_active_patients_by_hospice_and_residence_v4($hospiceID,$status_name_new,$this->session->userdata('user_location'));
	    		foreach($list_active_patients_result as $value) 
	    		{
	    			$query_result = get_patient_first_order($value['patientID']);
	    			if(date("Y-m-d", $query_result['uniqueID']) >= $filter_from && date("Y-m-d", $query_result['uniqueID']) <= $filter_to)
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
	    		}
	  		}

	  		$data['patient_list_temp'] = array_msort($result, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));

	  		$count_loop = 0; 
		  	foreach($data['patient_list_temp'] as $loop_key => $loop_value)
		  	{
		  		$data['patient_list'][$count_loop] = $loop_value;
		  		$count_loop++;
		  	}
		  	
	  		echo json_encode($data);
		}

		public function print_residence_status_details($filter_from,$filter_to,$hospiceID,$status_name)
		{
			$data['filter_from'] = $filter_from;
			$data['filter_to'] = $filter_to;
			$data['hospice_name'] = get_hospice_name($hospiceID);

			$current_date = "";
    		$data['patient_list'] = array();
    		$result = array();
    		$count = 0;

	  		if($status_name == "assisted_living")
		  	{
		  		$status_name_new = "Assisted Living";
		  	}
		  	else if($status_name == "group_home")
		  	{
		  		$status_name_new = "Group Home";
		  	}
		  	else if($status_name == "hic_home")
		  	{
		  		$status_name_new = "Hic Home";
		  	}
		  	else if($status_name == "home_care")
		  	{
		  		$status_name_new = "Home Care";
		  	}
		  	else
		  	{
		  		$status_name_new = "Skilled Nursing Facility";
		  	}
		  	$data['status_name'] = $status_name_new;

	  		if($filter_from == 0 || $filter_to == 0)
	  		{
	  			$current_date = date('Y-m-d');

	  			$latest_patient_on_list = get_latest_patient_on_list_v2($this->session->userdata('user_location'));
	    		$border_bottom = $latest_patient_on_list['patientID'] - 500;
	    		$border_top = "";

	    		$list_active_patients_result = list_active_patients_by_hospice_and_residence_v3($border_bottom,$border_top,$hospiceID,$status_name_new,$this->session->userdata('user_location'));
	    		foreach($list_active_patients_result as $value) 
	    		{
	    			$query_result = get_patient_first_order($value['patientID']);
		  			if(date("Y-m-d", $query_result['uniqueID']) == $current_date)
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
	    		}
	  		}
	  		else
	  		{
	  			$list_active_patients_result = list_active_patients_by_hospice_and_residence_v4($hospiceID,$status_name_new,$this->session->userdata('user_location'));
	    		foreach($list_active_patients_result as $value) 
	    		{
	    			$query_result = get_patient_first_order($value['patientID']);
	    			if(date("Y-m-d", $query_result['uniqueID']) >= $filter_from && date("Y-m-d", $query_result['uniqueID']) <= $filter_to)
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
	    		}
	  		}
	  		$data['patient_list'] = array_msort($result, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));

	  		$this->templating_library->set('title','Residence Status');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav', $data);
	  		$this->templating_library->set_view('pages/print_residence_status_details','pages/print_residence_status_details',$data);
			$this->templating_library->set_view('common/footer','common/footer');
		}

		public function print_activity_status_details($filter_from,$filter_to,$hospiceID,$status_name)
		{
			$data['filter_from'] = $filter_from;
			$data['filter_to'] = $filter_to;
			$data['hospice_name'] = get_hospice_name($hospiceID);

			$current_day = date('Y-m-d');
			$current_date = "";
			$data['patient_list'] = array();
    		$sign_here = 0;
    		$result = array();
    		if($filter_from == 0 || $filter_to == 0)
	  		{
	  			$filter_from = "";
	  			$filter_to = "";
	  			$current_date = date('Y-m-d');
	  		}
	  		if($hospiceID == 0)
	  		{
	  			$hospiceID = "";
	  		}

	  		if($status_name == "new_pt")
		  	{
		  		$status_name_new = "New Customer";
		  	}
		  	else if($status_name == "new_item")
		  	{
		  		$status_name_new = "New Items";
		  	}
		  	else if($status_name == "exchange")
		  	{
		  		$status_name_new = "Exchange";
		  	}
		  	else if($status_name == "pickup")
		  	{
		  		$status_name_new = "Pickup";
		  	}
		  	else if($status_name == "pt_move")
		  	{
		  		$status_name_new = "CUS Move";
		  	}
		  	else
		  	{
		  		$status_name_new = "Respite";
		  	}

		  	$data['status_name'] = $status_name_new;

	  		if($status_name == "new_pt")
		  	{
		  		$data['sign_here'] = 1;

		  		$count = 0;
		  		$added_patientIDs = array();

		  		if($current_date != "")
		  		{
		  			$result_here = get_new_patient_list_v3($current_date,"","",$hospiceID,$this->session->userdata('user_location'));
			  		$count = 0;
			  		foreach ($result_here as $value) {
			  			$query_result = get_patient_first_order($value['patientID']);
			  			if($query_result['order_status'] == "active")
			  			{
			  				$result[$count] = $value;
			  				$count++;
			  			}
			  		}
		  		}
		  		else
		  		{
		  			$result_here = get_new_patient_list_v4($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
			  		$count = 0;
			  		foreach ($result_here as $value) {
			  			$query_result = get_patient_first_order($value['patientID']);
			  			if($query_result['uniqueID'] == $value['uniqueID'])
			  			{
			  				$result[$count] = $value;
			  				$count++;
			  			}
			  		}

			  		if(($filter_from == $current_day && $filter_to > $current_day) || ($filter_from == $current_day && $filter_to == $current_day) || ($filter_from < $current_day && $filter_to == $current_day) || ($filter_from < $current_day && $filter_to > $current_day))
			  		{
			  			$result_here_second = get_new_patient_list_v3($current_day,"","",$hospiceID,$this->session->userdata('user_location'));
			  			foreach ($result_here_second as $value) {
				  			$query_result = get_patient_first_order($value['patientID']);
				  			if($query_result['order_status'] == "active")
				  			{
				  				$result[$count] = $value;
				  				$count++;
				  			}
				  		}
			  		}
		  		}
		  	}
		  	else if($status_name == "new_item")
		  	{
		  		$data['sign_here'] = 3;
		  		$query_result = get_patient_new_item_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
		  		$count_inside = 0;
		  		$last_patientID = 0;
		  		$last_uniqueID = 0;
		  		$item_count = 0;
		  		$sign_add_count_inside = 0;
		  		$real_loop_count = 0;

		  		$query_result_count = 0;
		  		foreach($query_result as $key => $value)
		  		{
		  			if($value['parentID'] == 0)
		  			{
		  				$query_result_count++;
		  			}
		  		}

		  		foreach ($query_result as $key => $value) {
	  				if($count_inside != 0)
	  				{
	  					if($last_patientID != $value['patientID'])
	  					{
	  						if($value['parentID'] == 0)
	  						{
	  							$check_first_order = get_patient_first_order($value['patientID']);
					  			if($check_first_order['uniqueID'] != $value['uniqueID'])
					  			{
					  				if($sign_add_count_inside == 0)
					  				{
					  					$count_inside++;
					  				}
				  					$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
					  				$result[$count_inside] = $patient_details;
					  				$result[$count_inside]['item_count'] = 1;
					  				$last_patientID = $value['patientID'];
					  				$last_uniqueID = $value['uniqueID'];
					  				$real_loop_count++;
					  				$item_count = 1;
					  				$sign_add_count_inside = 0;
		  						}
		  						else
					  			{
					  				$real_loop_count++;
					  			}
	  						}
			  			}
			  			else
			  			{
			  				if($value['parentID'] == 0)
				  			{
				  				if($value['uniqueID'] == $last_uniqueID)
				  				{
				  					$item_count++;
				  					if($sign_add_count_inside == 1)
				  					{
				  						$result[0]['item_count'] = $item_count;
				  					}
				  					else
				  					{
				  						$result[$count_inside]['item_count'] = $item_count;
				  					}
					  				$last_patientID = $value['patientID'];
					  				$last_uniqueID = $value['uniqueID'];
					  				$real_loop_count++;
				  				}
				  				else
				  				{
				  					$check_first_order = get_patient_first_order($value['patientID']);
						  			if($check_first_order['uniqueID'] != $value['uniqueID'])
						  			{
						  				if($sign_add_count_inside == 0)
						  				{
						  					$count_inside++;
						  				}
					  					$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
						  				$result[$count_inside] = $patient_details;
						  				$result[$count_inside]['item_count'] = 1;
						  				$last_patientID = $value['patientID'];
						  				$last_uniqueID = $value['uniqueID'];
						  				$real_loop_count++;
					  					$item_count = 1;
					  					$sign_add_count_inside = 0;
						  			}
						  			else
						  			{
						  				$real_loop_count++;
						  			}
				  				}
				  			}
			  			}
	  				}
	  				else
	  				{
	  					if($value['parentID'] == 0)
			  			{
		  					if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
		  					{
		  						$check_first_order = get_patient_first_order($value['patientID']);
					  			if($check_first_order['uniqueID'] != $value['uniqueID'])
					  			{
					  				$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
					  				$result[$count_inside] = $patient_details;
					  				$result[$count_inside]['item_count'] = 1;
					  				$last_patientID = $value['patientID'];
					  				$last_uniqueID = $value['uniqueID'];
					  				$real_loop_count++;
					  				if($real_loop_count < $query_result_count)
					  				{
					  					$count_inside++;
					  					$item_count = 1;
					  					$sign_add_count_inside = 1;
					  				}
					  			}
					  			else
					  			{
					  				$real_loop_count++;
					  			}
		  					}		  				
			  			}
	  				}
		  		}
		  		if($filter_from != "" && $filter_to != "")
		  		{
		  			$current_date = date('Y-m-d');
		  			if(($filter_from <= $current_date))
	                {
	                    if($filter_from <= $filter_to)
	                    {
	                        if($filter_to == $current_date || $filter_to > $current_date)
	                        {
			  					$query_result_final_here = get_patient_new_item_list_v2($current_date,"","",$hospiceID,$this->session->userdata('user_location'));
						  		$last_patientID = 0;
						  		$last_uniqueID = 0;
						  		$item_count = 0;
						  		$last_patientID_sign = 0;

						  		$query_result_count = 0;
						  		foreach($query_result_final_here as $key => $value)
						  		{
						  			if($value['parentID'] == 0)
						  			{
						  				$query_result_count++;
						  			}
						  		}

						  		foreach ($query_result_final_here as $key => $value) {
					  				if($count_inside != 0)
					  				{
					  					if($last_patientID != $value['patientID'])
					  					{
					  						if($value['parentID'] == 0)
					  						{
					  							$check_first_order = get_patient_first_order($value['patientID']);
									  			if($check_first_order['uniqueID'] != $value['uniqueID'])
									  			{
									  				if($sign_add_count_inside == 0)
									  				{
									  					$count_inside++;
									  				}
								  					$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
									  				$result[$count_inside] = $patient_details;
									  				$result[$count_inside]['item_count'] = 1;
									  				$last_patientID = $value['patientID'];
									  				$last_uniqueID = $value['uniqueID'];
									  				$real_loop_count++;
									  				$item_count = 1;
									  				$sign_add_count_inside = 0;
						  						}
						  						else
									  			{
									  				$real_loop_count++;
									  			}
					  						}
							  			}
							  			else
							  			{
							  				if($value['parentID'] == 0)
								  			{
								  				if($value['uniqueID'] == $last_uniqueID)
								  				{
								  					$item_count++;
								  					if($sign_add_count_inside == 1)
								  					{
								  						$result[0]['item_count'] = $item_count;
								  					}
								  					else
								  					{
								  						$result[$count_inside]['item_count'] = $item_count;
								  					}
									  				$last_patientID = $value['patientID'];
									  				$last_uniqueID = $value['uniqueID'];
									  				$real_loop_count++;
								  				}
								  				else
								  				{
								  					$check_first_order = get_patient_first_order($value['patientID']);
										  			if($check_first_order['uniqueID'] != $value['uniqueID'])
										  			{
										  				if($sign_add_count_inside == 0)
										  				{
										  					$count_inside++;
										  				}
									  					$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
										  				$result[$count_inside] = $patient_details;
										  				$result[$count_inside]['item_count'] = 1;
										  				$last_patientID = $value['patientID'];
										  				$last_uniqueID = $value['uniqueID'];
										  				$real_loop_count++;
									  					$item_count = 1;
									  					$sign_add_count_inside = 0;
										  			}
										  			else
										  			{
										  				$real_loop_count++;
										  			}
								  				}
								  			}
							  			}
					  				}
					  				else
					  				{
					  					if($value['parentID'] == 0)
							  			{
						  					if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
						  					{
						  						$check_first_order = get_patient_first_order($value['patientID']);
									  			if($check_first_order['uniqueID'] != $value['uniqueID'])
									  			{
									  				$patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
									  				$result[$count_inside] = $patient_details;
									  				$result[$count_inside]['item_count'] = 1;
									  				$last_patientID = $value['patientID'];
									  				$last_uniqueID = $value['uniqueID'];
									  				$real_loop_count++;
									  				if($real_loop_count < $query_result_count)
									  				{
									  					$count_inside++;
									  					$item_count = 1;
									  					$sign_add_count_inside = 1;
									  				}
									  			}
									  			else
									  			{
									  				$real_loop_count++;
									  			}
						  					}		  				
							  			}
					  				}
						  		}
						  		$current_date = "";
						  		if($filter_from == $current_date && $filter_to == $current_date)
						  		{
						  			$query_result = $query_result_final_here;
						  		}
						  		else
						  		{
						  			$query_result = array_merge($query_result,$query_result_final_here);
						  		}
						  		
						  	}
					  	}
		  			}		  			
		  		}
		  	}
		  	else if($status_name == "exchange")
		  	{
		  		$data['sign_here'] = 3;
		  		$query_result = get_patient_exchange_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));

		  		$count_inside = 0;
                $last_patientID = 0;
                $last_uniqueID = 0;
                $item_count = 0;
                $sign_add_count_inside = 0;
                $real_loop_count = 0;
                $query_result_count = 0;

		  		foreach($query_result as $key => $value)
		  		{
		  			if($value['parentID'] == 0)
		  			{
		  				$query_result_count++;
		  			}
		  		}

		  		foreach ($query_result as $key => $value) {
	  				if($count_inside != 0)
                    {
                        if($last_patientID != $value['patientID'])
                        {
                            if($value['parentID'] == 0)
                            {
                                $check_first_order = get_patient_first_order($value['patientID']);
                                if($check_first_order['uniqueID'] != $value['uniqueID'])
                                {
                                    if($sign_add_count_inside == 0)
                                    {
                                        $count_inside++;
                                    }
                                    $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
                                    $result[$count_inside] = $patient_details;
                                    $result[$count_inside]['item_count'] = 1;
                                    $last_patientID = $value['patientID'];
                                    $last_uniqueID = $value['uniqueID'];
                                    $real_loop_count++;
                                    $item_count = 1;
                                    $sign_add_count_inside = 0;
                                }
                                else
                                {
                                    $real_loop_count++;
                                }
                            }
                        }
                        else
                        {
                            if($value['parentID'] == 0)
                            {
                                if($value['uniqueID'] == $last_uniqueID)
                                {
                                    $item_count++;
                                    if($sign_add_count_inside == 1)
                                    {
                                        $result[0]['item_count'] = $item_count;
                                    }
                                    else
                                    {
                                        $result[$count_inside]['item_count'] = $item_count;
                                    }
                                    $last_patientID = $value['patientID'];
                                    $last_uniqueID = $value['uniqueID'];
                                    $real_loop_count++;
                                }
                                else
                                {
                                    $check_first_order = get_patient_first_order($value['patientID']);
                                    if($check_first_order['uniqueID'] != $value['uniqueID'])
                                    {
                                        if($sign_add_count_inside == 0)
                                        {
                                              $count_inside++;
                                        }
                                        $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
                                        $result[$count_inside] = $patient_details;
                                        $result[$count_inside]['item_count'] = 1;
                                        $last_patientID = $value['patientID'];
                                        $last_uniqueID = $value['uniqueID'];
                                        $real_loop_count++;
                                        $item_count = 1;
                                        $sign_add_count_inside = 0;
                                    }
                                    else
                                    {
                                        $real_loop_count++;
                                    }
                                }
                            }
                        }
                    }
                    else
                    {
                        if($value['parentID'] == 0)
                        {
                            if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
                            {
                                $check_first_order = get_patient_first_order($value['patientID']);
                                if($check_first_order['uniqueID'] != $value['uniqueID'])
                                {
                                    $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
                                    $result[$count_inside] = $patient_details;
                                    $result[$count_inside]['item_count'] = 1;
                                    $last_patientID = $value['patientID'];
                                    $last_uniqueID = $value['uniqueID'];
                                    $real_loop_count++;
                                    if($real_loop_count < $query_result_count)
                                    {
	                                    $count_inside++;
	                                    $item_count = 1;
	                                    $sign_add_count_inside = 1;
                                    }
                                }
                                else
                                {
                                    $real_loop_count++;
                                }
                            }                                   
                        }
                    }
	  			}

	  			if($filter_from != "" && $filter_to != "")
                {
                    $current_date = date('Y-m-d');
                    if(($filter_from <= $current_date))
                    {
                        if($filter_from <= $filter_to)
                        {
                            if($filter_to == $current_date || $filter_to > $current_date)
                            {
                                $query_result_final_here = get_patient_exchange_list_v2($current_date,"","",$hospiceID,$this->session->userdata('user_location'));
                                $last_patientID = 0;
				                $last_uniqueID = 0;
				                $item_count = 0;
				                $sign_add_count_inside = 0;
				                $real_loop_count = 0;
				                $query_result_count = 0;

                                foreach($query_result_final_here as $key => $value)
                                {
                                      if($value['parentID'] == 0)
                                      {
                                            $query_result_count++;
                                      }
                                }
                                foreach ($query_result_final_here as $key => $value) {
                                    if($count_inside != 0)
				                    {
				                        if($last_patientID != $value['patientID'])
				                        {
				                            if($value['parentID'] == 0)
				                            {
				                                $check_first_order = get_patient_first_order($value['patientID']);
				                                if($check_first_order['uniqueID'] != $value['uniqueID'])
				                                {
				                                    if($sign_add_count_inside == 0)
				                                    {
				                                        $count_inside++;
				                                    }
				                                    $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
				                                    $result[$count_inside] = $patient_details;
				                                    $result[$count_inside]['item_count'] = 1;
				                                    $last_patientID = $value['patientID'];
				                                    $last_uniqueID = $value['uniqueID'];
				                                    $real_loop_count++;
				                                    $item_count = 1;
				                                    $sign_add_count_inside = 0;
				                                }
				                                else
				                                {
				                                    $real_loop_count++;
				                                }
				                            }
				                        }
				                        else
				                        {
				                            if($value['parentID'] == 0)
				                            {
				                                if($value['uniqueID'] == $last_uniqueID)
				                                {
				                                    $item_count++;
				                                    if($sign_add_count_inside == 1)
				                                    {
				                                        $result[0]['item_count'] = $item_count;
				                                    }
				                                    else
				                                    {
				                                        $result[$count_inside]['item_count'] = $item_count;
				                                    }
				                                    $last_patientID = $value['patientID'];
				                                    $last_uniqueID = $value['uniqueID'];
				                                    $real_loop_count++;
				                                }
				                                else
				                                {
				                                    $check_first_order = get_patient_first_order($value['patientID']);
				                                    if($check_first_order['uniqueID'] != $value['uniqueID'])
				                                    {
				                                        if($sign_add_count_inside == 0)
				                                        {
				                                              $count_inside++;
				                                        }
				                                        $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
				                                        $result[$count_inside] = $patient_details;
				                                        $result[$count_inside]['item_count'] = 1;
				                                        $last_patientID = $value['patientID'];
				                                        $last_uniqueID = $value['uniqueID'];
				                                        $real_loop_count++;
				                                        $item_count = 1;
				                                        $sign_add_count_inside = 0;
				                                    }
				                                    else
				                                    {
				                                        $real_loop_count++;
				                                    }
				                                }
				                            }
				                        }
				                    }
				                    else
				                    {
				                        if($value['parentID'] == 0)
				                        {
				                            if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
				                            {
				                                $check_first_order = get_patient_first_order($value['patientID']);
				                                if($check_first_order['uniqueID'] != $value['uniqueID'])
				                                {
				                                    $patient_details = get_patient_info_v2($value['patientID'],$value['uniqueID']);
				                                    $result[$count_inside] = $patient_details;
				                                    $result[$count_inside]['item_count'] = 1;
				                                    $last_patientID = $value['patientID'];
				                                    $last_uniqueID = $value['uniqueID'];
				                                    $real_loop_count++;
				                                    if($real_loop_count < $query_result_count)
				                                    {
					                                    $count_inside++;
					                                    $item_count = 1;
					                                    $sign_add_count_inside = 1;
				                                    }
				                                }
				                                else
				                                {
				                                    $real_loop_count++;
				                                }
				                            }                                   
				                        }
				                    }
                                }
                                $current_date = "";
                                $query_result = array_merge($query_result,$query_result_final_here);
                          	}
                        }
                  	}                             
                }
		  	}
		  	else if($status_name == "pickup")
		  	{
		  		$result_2 = array();
		  		$data['sign_here'] = 2;
		  		$query_result = get_patient_pickup_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
		  		$result_1 = array_msort($query_result, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
		  		if($filter_from != "" && $filter_to != "")
                {
                    $current_date = date('Y-m-d');
                    if(($filter_from <= $current_date))
                    {
                        if($filter_from <= $filter_to)
                        {         
                            if($filter_to == $current_date || $filter_to > $current_date)
                            {
                                $filter_from_new = "";
                                $filter_to_new = "";
                                $query_result = get_patient_pickup_list_v2($current_date,$filter_from_new,$filter_to_new,$hospiceID,$this->session->userdata('user_location'));
                                $result_2 = array_msort($query_result, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
                                $current_date = "";
                            }
                        }
                    }                             
                }
                $result = array_merge($result_1,$result_2);
		  	}
		  	else if($status_name == "pt_move")
		  	{
		  		$data['sign_here'] = 1;
		  		$new_result_here = get_ptmove_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
		  		$result_here = array_msort($new_result_here, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));

		  		$count = 0;
		  		foreach ($result_here as $value) {
		  			$query_result = get_ptmove_address_first_order($value['patientID'],$value['addressID']);
		  			if($query_result['uniqueID'] == $value['uniqueID'])
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
		  		}
		  		if($filter_from != "" && $filter_to != "")
                {
                    $current_date = date('Y-m-d');
                    if(($filter_from <= $current_date))
                    {
                        if($filter_from <= $filter_to)
                        {
                            if($filter_to == $current_date || $filter_to > $current_date)
                            {
                                $filter_from_new = "";
                                $filter_to_new = "";
                                $new_result_here_new = get_ptmove_list_v2($current_date,$filter_from_new,$filter_to_new,$hospiceID,$this->session->userdata('user_location'));
                                $result_here_new = array_msort($new_result_here_new, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));

                                foreach ($result_here_new as $value) {
                                    $query_result = get_ptmove_address_first_order($value['patientID'],$value['addressID']);
                                    if($query_result['uniqueID'] == $value['uniqueID'])
                                    {
                                        $result[$count] = $value;
                                        $count++;
                                    }
                                }
                                $current_date = "";
                            }
                        }
                    }                             
                }
		  	}
		  	else
		  	{
		  		$data['sign_here'] = 1;
		  		$new_result_here = get_respite_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'));
		  		$result_here = array_msort($new_result_here, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));

		  		$count = 0;
		  		foreach ($result_here as $value) {
		  			$query_result = get_respite_address_first_order($value['patientID'],$value['addressID']);
		  			if($query_result['uniqueID'] == $value['uniqueID'])
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
		  		}
		  		if($filter_from != "" && $filter_to != "")
                {
                      $current_date = date('Y-m-d');
                      if(($filter_from <= $current_date))
                      {
                            if($filter_from <= $filter_to)
                            {
                                  if($filter_to == $current_date || $filter_to > $current_date)
                                  {
                                        $filter_from_new = "";
                                        $filter_to_new = "";
                                        $new_result_here_new = get_respite_list_v2($current_date,$filter_from_new,$filter_to_new,$hospiceID,$this->session->userdata('user_location'));
                                        $result_here_new = array_msort($new_result_here_new, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));

                                        foreach ($result_here_new as $value) {
                                              $query_result = get_respite_address_first_order($value['patientID'],$value['addressID']);
                                              if($query_result['uniqueID'] == $value['uniqueID'])
                                              {
                                                    $result[$count] = $value;
                                                    $count++;
                                              }
                                        }
                                        $current_date = "";
                                  }
                            }
                      }                             
                }
		  	}
		  	$data['patient_list'] = array_msort($result, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
		  	$data['query_result'] = $query_result;

	  		$this->templating_library->set('title','Activity Status');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav', $data);
	  		$this->templating_library->set_view('pages/print_activity_status_details','pages/print_activity_status_details',$data);
			$this->templating_library->set_view('common/footer','common/footer');
		}
		// $list_active_patients_result = list_patients_by_hospice($hospiceID);
		  			// if(!empty($list_active_patients_result))
		  			// {
		  			// 	if($filter_from == $current_day && $filter_to == $current_day || $filter_from == $current_day && $filter_to > $current_day)
		  			// 	{
		  			// 		if(($filter_from <= $current_day))
			    //             {
			    //                 if($filter_from <= $filter_to)
			    //                 {
			    //                     if($filter_to == $current_day || $filter_to > $current_day)
			    //                     {
			    //                     	foreach($list_active_patients_result as $value) 
							//     		{
							//     			$query_result_new = get_patient_first_order($value['patientID']);
							//     			if($query_result_new['order_status'] == "active")
							//     			{
							//     				if(!in_array($value['patientID'], $added_patientIDs))
							//     				{
							//     					$result[$count] = $value;
							//     					$added_patientIDs[$count] = $value['patientID'];
						 //  							$count++;
							//     				}
							//     			}
							//     		}
			    //                     }
			    //                 }
			    //             }
		  			// 	}
		  			// 	else
		  			// 	{
		  			// 		foreach($list_active_patients_result as $value) 
				   //  		{
				   //  			$query_result = get_patient_first_order($value['patientID']);
				    			
				   //  			if($filter_to == $current_day || $filter_to > $current_day)
				   //  			{
				   //  				if($query_result['actual_order_date'] >= $filter_from && $query_result['actual_order_date'] < $filter_to)
					  //   			{
					  //   				$result[$count] = $value;
					  //   				$added_patientIDs[$count] = $value['patientID'];
					  // 					$count++;
					  //   			}
				   //  			}
				   //  			else
				   //  			{
				   //  				if($query_result['actual_order_date'] >= $filter_from && $query_result['actual_order_date'] <= $filter_to)
					  //   			{
					  //   				$result[$count] = $value;
					  //   				$added_patientIDs[$count] = $value['patientID'];
					  // 					$count++;
					  //   			}
				   //  			}
				   //  		}

				   //  		if(($filter_from <= $current_day))
			    //             {
			    //                 if($filter_from <= $filter_to)
			    //                 {
			    //                     if($filter_to == $current_day || $filter_to > $current_day)
			    //                     {
			    //                     	foreach($list_active_patients_result as $value) 
							//     		{
							//     			$query_result_new = get_patient_first_order($value['patientID']);
							//     			if($query_result_new['order_status'] == "active")
							//     			{
							//     				if(!in_array($value['patientID'], $added_patientIDs))
							//     				{
							//     					$result[$count] = $value;
							//     					$added_patientIDs[$count] = $value['patientID'];
						 //  							$count++;
							//     				}
							//     			}
							//     		}
			    //                     }
			    //                 }
			    //             }
		  			// 	}
		  			// }

		  		// $result_here = get_new_patient_list($current_date,$filter_from,$filter_to,$hospiceID);
		  		// $new_result_here = array_msort($result_here, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
		  		// $count = 0;
		  		// foreach ($new_result_here as $value) {
		  		// 	$query_result = get_patient_first_order($value['patientID']);
		  		// 	if($query_result['uniqueID'] == $value['uniqueID'])
		  		// 	{
		  		// 		$result[$count] = $value;
		  		// 		$count++;
		  		// 	}
		  		// }
		  		// if($filter_from != "" && $filter_to != "")
      //           {
      //                 $current_date = date('Y-m-d');
      //                 if(($filter_from <= $current_date))
      //                 {
      //                       if($filter_from <= $filter_to)
      //                       {
      //                             if($filter_to == $current_date || $filter_to > $current_date)
      //                             {
      //                                   $filter_from_new = "";
      //                                   $filter_to_new = "";
      //                                   $result_here = get_new_patient_list($current_date,$filter_from_new,$filter_to_new,$hospiceID);
      //                                   $new_result_here = array_msort($result_here, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
      //                                   foreach ($new_result_here as $value) {
      //                                         $query_result = get_patient_first_order($value['patientID']);
      //                                         if($query_result['uniqueID'] == $value['uniqueID'])
      //                                         {
      //                                               $result[$count] = $value;
      //                                               $count++;
      //                                         }
      //                                   }
      //                                   $current_date = "";
      //                             }
      //                       }
      //                 }                             
      //           }

		public function item_usage_details($type)
		{
			$this->templating_library->set('title','Reports');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');

			$data = array();
			$data['item_category'] = $type;

			$this->templating_library->set('title','');
			$this->templating_library->set_view('pages/item_usage_details','pages/item_usage_details', $data);
			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}

		public function get_item_usage_today_default_standard($item_category_num)
		{
			$hospiceID = "";
			$from_date = date('Y-m');
			$from = $from_date."-01";
			$to = date('Y-m-d');
			$equipment_array = array();
			$graph_data = array();
			$graph = array();
			$data = array();

			$query_result = get_item_usage_sort_date_type_v2($item_category_num,$hospiceID,$from,$to,$this->session->userdata('user_location'));
			if(!empty($query_result))
			{
				foreach ($query_result as $key => $value) {
					if($value['parentID'] == 0)
					{
						if(!in_array($value['equipmentID'], $equipment_array))
						{
							$equipment_array[] = $value['equipmentID'];
							$graph_data[$value['equipmentID']] = array(
																	"equipment_name" => $value['key_desc'],
																	"count" => 1
																);
						}
						else
						{
							$graph_data[$value['equipmentID']]['count'] += 1;
						}
						$graph_data[$value['equipmentID']]['patients'][] = $value['patientID']; 
					}
				}
				foreach ($graph_data as $key => $value) {
					$unique = array_unique($value['patients']);
					$graph[] = array("label" => $value['equipment_name'],"value" => $value['count'],"patients" => $unique);
				}
			}

			$data = array(
				"date_range_from"	=> $from,
				"graph"				=> $graph,
				"date_range_to"		=> $to
			);

			$this->common->code 	= 0;    
			$this->common->message 	= "Item Usage Today";
			$this->common->data 	= $data;

			$this->common->response(false);
		}

		public function get_item_usage_today_default_comparison($item_category_num)
		{
			$hospiceID = "";
			$from_date = date('Y-m');
			$from = $from_date."-01";
			$to = date('Y-m-d');
			$equipment_array = array();
			$graph_data = array();
			$graph = array();
			$data = array();

			$query_result = get_item_usage_sort_date_type_v2($item_category_num,$hospiceID,$from,$to,$this->session->userdata('user_location'));
			if(!empty($query_result))
			{
				foreach ($query_result as $key => $value) {
					if($value['parentID'] == 0)
					{
						if(!in_array($value['equipmentID'], $equipment_array))
						{
							$equipment_array[] = $value['equipmentID'];
							$graph_data[$value['equipmentID']] = array(
																	"equipment_name" 	=> $value['key_desc'],
																	"count" 			=> 1,
																	"count_second" 		=> 0
																);
						}
						else
						{
							$graph_data[$value['equipmentID']]['count'] += 1;
						}
						$graph_data[$value['equipmentID']]['patients'][] = $value['patientID']; 
					}
				}
			}

			$current_month = date('m');
			$current_year = date('Y');
            $from_month = $current_month;
            $from_year = $current_year;
			if($current_month == 12)
            {
                $from_month = 01;
            }
            else
            {
                $from_month += 1;
                $from_year -= 1;
            }
            if($from_month > 9)
            {
                $from_date = $from_year."-".$from_month."-01";
            }
            else
            {
                $from_date = $from_year."-0".$from_month."-01";
            }

            if($current_month < 7)
            {
                $to_month = ($current_month-6)+12;
                $to_year = $current_year - 1;
            }
            else
            {
                $to_month = $current_month-6;
                $to_year = $current_year;
            }
            if($to_month > 9)
            {
                $temp_date = $to_year."-".$to_month;
                $to_day = date("t", strtotime($temp_date));
                if($to_day > 9)
                {
                    $to_date = $to_year."-".$to_month."-".$to_day;
                }
                else
                {
                    $to_date = $to_year."-".$to_month."-0".$to_day;
                }
            }
            else
            {
                $temp_date = $to_year."-0".$to_month;
                $to_day = date("t", strtotime($temp_date));
                if($to_day > 9)
                {
                    $to_date = $to_year."-0".$to_month."-".$to_day;
                }
                else
                {
                    $to_date = $to_year."-0".$to_month."-0".$to_day;
                }
            }
            if($current_month < 6)
            {
                $to_month = ($current_month-5)+12;
                $to_year = $current_year - 1;
            }
            else
            {
                $to_month = $current_month-5;
                $to_year = $current_year;
            }
            if($to_month > 9)
            {
                $temp_date = $to_year."-".$to_month;
                $to_day = date("t", strtotime($temp_date));
                if($to_day > 9)
                {
                    $second_from_date = $to_year."-".$to_month."-01";
                }
                else
                {
                    $second_from_date = $to_year."-".$to_month."-01";
                }
            }
            else
            {
                $temp_date = $to_year."-0".$to_month;
                $to_day = date("t", strtotime($temp_date));
                if($to_day > 9)
                {
                    $second_from_date = $to_year."-0".$to_month."-01";
                }
                else
                {
                    $second_from_date = $to_year."-0".$to_month."-01";
                }
            }
            $second_to_date = date('Y-m-d');

			$second_date_first_result = get_item_usage_sort_date_type_second_date_v2($item_category_num,$hospiceID,$from_date,$to_date,$this->session->userdata('user_location'));
			if(!empty($second_date_first_result))
			{
				foreach ($second_date_first_result as $key => $value) {
					if($value['parentID'] == 0)
					{
						if(!in_array($value['equipmentID'], $equipment_array))
						{
							$equipment_array[] = $value['equipmentID'];
							$graph_data[$value['equipmentID']] = array(
																	"equipment_name" 	=> $value['key_desc'],
																	"count" 			=> 0,
																	"count_second" 		=> 1
																);
						}
						else
						{
							$graph_data[$value['equipmentID']]['count_second'] += 1;
						}
						$graph_data[$value['equipmentID']]['patients'][] = $value['patientID']; 
					}
				}
			}

			$second_date_second_result = get_item_usage_sort_date_type_second_date_v2($item_category_num,$hospiceID,$second_from_date,$second_to_date,$this->session->userdata('user_location'));
			if(!empty($second_date_second_result))
			{
				foreach ($second_date_second_result as $key => $value) {
					if($value['parentID'] == 0)
					{
						if(!in_array($value['equipmentID'], $equipment_array))
						{
							$equipment_array[] = $value['equipmentID'];
							$graph_data[$value['equipmentID']] = array(
																	"equipment_name" 	=> $value['key_desc'],
																	"count" 			=> 0,
																	"count_second" 		=> 1
																);
						}
						else
						{
							$graph_data[$value['equipmentID']]['count_second'] += 1;
						}
						$graph_data[$value['equipmentID']]['patients'][] = $value['patientID']; 
					}
				}
			}

			foreach ($graph_data as $key => $value) {
				$unique = array_unique($value['patients']);
				$graph[] = array("label" => $value['equipment_name'],"value" => $value['count'],"second_value" => $value['count_second'],"patients" => $unique);
			}

			$data = array(
				"date_range_from"	=> $from,
				"graph"				=> $graph,
				"date_range_to"		=> $to
			);

			$this->common->code 	= 0;    
			$this->common->message 	= "Item Usage This Month to Date";
			$this->common->data 	= $data;

			$this->common->response(false);
		}

		public function filter_item_usage_comparison($filter_from,$filter_to,$hospiceID,$item_category_num,$sort_dates,$filter_type)
		{
			$equipment_array = array();
			$graph_data = array();
			$graph = array();
			$data = array();

			if($filter_type == 1 || $filter_type == 3)
			{
				if($filter_from != 0 && $filter_to != 0)
				{
					$from_date = $filter_from;
					$to_date = $filter_to;
				}
				else
				{
					$from_date = "";
					$to_date = "";
				}
			}
			else if($filter_type == 2)
			{
				$second_from_date = "";
				$second_to_date = "";
				if($sort_dates == 0)
				{
					if($filter_from != 0 && $filter_to != 0)
					{
						$from_date = $filter_from;
						$to_date = $filter_to;
					}
					else
					{
						$from_date = "";
						$to_date = "";
					}
				}
				else if($sort_dates == 1)
				{
					$from_date = date('Y-m-d');
					$to_date = $from_date;
				}
				else if($sort_dates == 2)
				{
					$from_date = (new DateTime('last Sunday'))->format("Y-m-d");
					$to_date = (new DateTime('next Saturday'))->format("Y-m-d");
				}
				else if($sort_dates == 3)
				{
					$from_date = (new DateTime('last Sunday'))->format("Y-m-d");
					$to_date = date('Y-m-d');
				}
				else if($sort_dates == 4)
				{
					$from_date = date('Y-m')."-01";
					$to_date = date('Y-m-t');
				}
				else if($sort_dates == 5)
				{
					$from_date = date('Y-m');
					$from_date = $from_date."-01";
					$to_date = date('Y-m-d');
				}
				else if($sort_dates == 6)
				{
					$from_month = date('m');
					$from_year = date('Y');
					if($from_month == 2)
					{
						$from_initial_date = 12;
						$from_year -= 1; 
					}
					else if($from_month == 1)
					{
						$from_initial_date = 11;
						$from_year -= 1; 
					}
					else
					{
						$from_initial_date = date('m')-2;
					}
					if($from_initial_date > 9)
					{
						$from_date = $from_year."-".$from_initial_date."-01";
					}
					else
					{
						$from_date = $from_year."-0".$from_initial_date."-01";
					}
					$to_date = date('Y-m-t');
				}	
				else if($sort_dates == 7)
				{
					$from_month = date('m');
					$from_year = date('Y');
					if($from_month == 2)
					{
						$from_initial_date = 12;
						$from_year -= 1; 
					}
					else if($from_month == 1)
					{
						$from_initial_date = 11;
						$from_year -= 1; 
					}
					else
					{
						$from_initial_date = date('m')-2;
					}
					if($from_initial_date > 9)
					{
						$from_date = $from_year."-".$from_initial_date."-01";
					}
					else
					{
						$from_date = $from_year."-0".$from_initial_date."-01";
					}
					$to_date = date('Y-m-d');
				}
				else if($sort_dates == 8)
				{
					$from_year = date('Y');
					$from_month = date('m');
					$from_month_final = $from_month;
					$from_year_final = $from_year;
					if($from_month == 12)
					{
						$from_month_final = 01;
					}
					else
					{
						$from_month_final += 1;
						$from_year_final -= 1;
					}
					if($from_month_final > 9)
					{
						$from_date = $from_year_final."-".$from_month_final."-01";
					}
					else
					{
						$from_date = $from_year_final."-0".$from_month_final."-01";
					}
					
					if($from_month < 7)
					{
						$to_month = ($from_month-6)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-6;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
					}
					
					if($from_month < 6)
					{
						$to_month = ($from_month-5)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-5;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
					}
					$second_to_date = date('Y-m-t');
				}
				else if($sort_dates == 9)
				{
					$from_year = date('Y');
					$from_month = date('m');
					$from_month_final = $from_month;
					$from_year_final = $from_year;
					if($from_month == 12)
					{
						$from_month_final = 01;
					}
					else
					{
						$from_month_final += 1;
						$from_year_final -= 1;
					}
					if($from_month_final > 9)
					{	
						$from_date = $from_year_final."-".$from_month_final."-01";
					}
					else
					{	
						$from_date = $from_year_final."-0".$from_month_final."-01";
					}

					if($from_month < 7)
					{
						$to_month = ($from_month-6)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-6;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
					}
					
					if($from_month < 6)
					{
						$to_month = ($from_month-5)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-5;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
					}
					$second_to_date = date('Y-m-d');
				}
				else if($sort_dates == 10)
				{
					$from_date = date('Y-m-d',strtotime("-1 days"));
					$to_date = date('Y-m-d',strtotime("-1 days"));
				}
				else if($sort_dates == 11)
				{
					$from_date = date("Y-m-d", strtotime("last week Monday -1 day"));
					$to_date = date("Y-m-d", strtotime("last week Saturday"));
				}
				else if($sort_dates == 12)
				{
					$from_date = date("Y-m-d", strtotime("last week Monday -1 day"));
					$to_date = date("Y-m-d",strtotime("-7 days"));
				}
				else if($sort_dates == 13)
				{
					$from_date = date("Y-m-d", strtotime("first day of previous month"));
					$to_date = date("Y-m-d", strtotime("last day of previous month"));
				}
				else if($sort_dates == 14)
				{
					$from_date = date("Y-m-d", strtotime("first day of previous month"));
					$to_date = date('Y-m-d');
				}
				else if($sort_dates == 15)
				{
					$current_month = date('m');
					$current_year = date('Y');
					$from_year = $current_year;
					$to_year = $current_year;
					if($current_month < 6)
					{
						$from_month = ($current_month-5)+12;
						$from_year = $current_year - 1;
					}
					else
					{
						$from_month = $current_month-5;
					}

					if($current_month < 4)
					{
						$to_month = ($current_month-3)+12;
						$to_year = $current_year - 1;
					}
					else
					{
						$to_month = $current_month-3;
					}

					if($from_month > 9)
					{	
						$from_date = $from_year."-".$from_month."-01";
					}
					else
					{	
						$from_date = $from_year."-0".$from_month."-01";
					}
					
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{	
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{	
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
					}
				}
				else if($sort_dates == 16)
				{
					$current_month = date('m');
					$current_year = date('Y');
					$from_year = $current_year;
					$to_year = $current_year;
					if($current_month < 6)
					{
						$from_month = ($current_month-5)+12;
						$from_year = $current_year - 1;
					}
					else
					{
						$from_month = $current_month-5;
					}

					if($current_month < 4)
					{
						$to_month = ($current_month-3)+12;
						$to_year = $current_year - 1;
					}
					else
					{
						$to_month = $current_month-3;
					}

					if($from_month > 9)
					{	
						$from_date = $from_year."-".$from_month."-01";
					}
					else
					{	
						$from_date = $from_year."-0".$from_month."-01";
					}
					
					if($to_month > 9)
					{
						$to_day = date('d');
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{	
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$to_day = date('d');
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{	
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
						
					}
				}
				else if($sort_dates == 17)
				{
					$from_year = date('Y')-1;
					$from_month = date('m');
					$from_month_final = $from_month;
					$from_year_final = $from_year;
					if($from_month == 12)
					{
						$from_month_final = 01;
					}
					else
					{
						$from_month_final += 1;
						$from_year_final -= 1;
					}
					if($from_month_final > 9)
					{
						$from_date = $from_year_final."-".$from_month_final."-01";
					}
					else
					{
						$from_date = $from_year_final."-0".$from_month_final."-01";
					}
					
					if($from_month < 7)
					{
						$to_month = ($from_month-6)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-6;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
					}
					
					if($from_month < 6)
					{
						$to_month = ($from_month-5)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-5;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
					}
					$second_to_year = date('Y')-1;
					$second_to_month = date('m');
					if($second_to_month > 9)
					{
						$second_to_day = date('t');	
						if($second_to_day > 9)
						{
							$second_to_date = $second_to_year."-".$second_to_month."-".$second_to_day;
						}
						else
						{
							$second_to_date = $second_to_year."-".$second_to_month."-0".$second_to_day;
						}
					}
					else
					{
						$second_to_day = date('t');	
						if($second_to_day > 9)
						{
							$second_to_date = $second_to_year."-".$second_to_month."-".$second_to_day;
						}
						else
						{
							$second_to_date = $second_to_year."-0".$second_to_month."-0".$second_to_day;
						}
					}
				}
				else if($sort_dates == 18)
				{
					$from_year = date('Y')-1;
					$from_month = date('m');
					$from_month_final = $from_month;
					$from_year_final = $from_year;
					if($from_month == 12)
					{
						$from_month_final = 01;
					}
					else
					{
						$from_month_final += 1;
						$from_year_final -= 1;
					}
					if($from_month_final > 9)
					{
						$from_date = $from_year_final."-".$from_month_final."-01";
					}
					else
					{
						$from_date = $from_year_final."-0".$from_month_final."-01";
					}
					
					if($from_month < 7)
					{
						$to_month = ($from_month-6)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-6;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
					}
					
					if($from_month < 6)
					{
						$to_month = ($from_month-5)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-5;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
					}
					$second_to_year = date('Y')-1;
					$second_to_month = date('m');
					if($second_to_month > 9)
					{
						$second_to_day = date('d');	
						if($second_to_day > 9)
						{
							$second_to_date = $second_to_year."-".$second_to_month."-".$second_to_day;
						}
						else
						{
							$second_to_date = $second_to_year."-".$second_to_month."-0".$second_to_day;
						}
					}
					else
					{
						$second_to_day = date('d');	
						if($second_to_day > 9)
						{
							$second_to_date = $second_to_year."-".$second_to_month."-".$second_to_day;
						}
						else
						{
							$second_to_date = $second_to_year."-0".$second_to_month."-0".$second_to_day;
						}
					}
				}
			}

			if($hospiceID == 0)
			{
				$hospiceID = "";
			}

			$query_result = get_item_usage_sort_date_type_v2($item_category_num,$hospiceID,$from_date,$to_date,$this->session->userdata('user_location'));
			if(!empty($query_result))
			{
				foreach ($query_result as $key => $value) {
					if($value['parentID'] == 0)
					{
						if(!in_array($value['equipmentID'], $equipment_array))
						{
							$equipment_array[] = $value['equipmentID'];
							$graph_data[$value['equipmentID']] = array(
																	"equipment_name" 	=> $value['key_desc'],
																	"count" 			=> 1,
																	"count_second" 		=> 0
																);
						}
						else
						{
							$graph_data[$value['equipmentID']]['count'] += 1;
						}
						$graph_data[$value['equipmentID']]['patients'][] = $value['patientID']; 
					}
				}
			}

			if($second_from_date != "" && $second_to_date != "")
			{
				$second_query_result = get_item_usage_sort_date_type_second_date_v2($item_category_num,$hospiceID,$second_from_date,$second_to_date,$this->session->userdata('user_location'));
				if(!empty($second_query_result))
				{
					foreach ($second_query_result as $key => $value) {
						if($value['parentID'] == 0)
						{
							if(!in_array($value['equipmentID'], $equipment_array))
							{
								$equipment_array[] = $value['equipmentID'];
								$graph_data[$value['equipmentID']] = array(
																		"equipment_name" 	=> $value['key_desc'],
																		"count" 			=> 1,
																		"count_second" 		=> 0
																	);
							}
							else
							{
								$graph_data[$value['equipmentID']]['count'] += 1;
							}
							$graph_data[$value['equipmentID']]['patients'][] = $value['patientID']; 
						}
					}
				}
			}

			if($sort_dates == 0 || $sort_dates == 1 || $sort_dates == 3 || $sort_dates == 5 || $sort_dates == 7 || $sort_dates == 9 || $sort_dates == 14)
			{
				$current_month = date('m');
				$current_year = date('Y');
	            $from_month = $current_month;
	            $from_year = $current_year;
				if($current_month == 12)
	            {
	                $from_month = 01;
	            }
	            else
	            {
	                $from_month += 1;
	                $from_year -= 1;
	            }
	            if($from_month > 9)
	            {
	                $ytd_from_date = $from_year."-".$from_month."-01";
	            }
	            else
	            {
	                $ytd_from_date = $from_year."-0".$from_month."-01";
	            }

	            if($current_month < 7)
	            {
	                $to_month = ($current_month-6)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-6;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-0".$to_day;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-0".$to_day;
	                }
	            }
	            if($current_month < 6)
	            {
	                $to_month = ($current_month-5)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-5;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	            }
	            $ytd_second_to_date = date('Y-m-d');
			}
			else if($sort_dates == 2)
			{
				$current_month = date('m');
				$current_year = date('Y');
	            $from_month = $current_month;
	            $from_year = $current_year;
				if($current_month == 12)
	            {
	                $from_month = 01;
	            }
	            else
	            {
	                $from_month += 1;
	                $from_year -= 1;
	            }
	            if($from_month > 9)
	            {
	                $ytd_from_date = $from_year."-".$from_month."-01";
	            }
	            else
	            {
	                $ytd_from_date = $from_year."-0".$from_month."-01";
	            }

	            if($current_month < 7)
	            {
	                $to_month = ($current_month-6)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-6;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-0".$to_day;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-0".$to_day;
	                }
	            }
	            if($current_month < 6)
	            {
	                $to_month = ($current_month-5)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-5;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	            }
	            $ytd_second_to_date = (new DateTime('next Saturday'))->format("Y-m-d");
			}
			else if($sort_dates == 4 || $sort_dates == 6 || $sort_dates == 8)
			{
				$current_month = date('m');
				$current_year = date('Y');
	            $from_month = $current_month;
	            $from_year = $current_year;
				if($current_month == 12)
	            {
	                $from_month = 01;
	            }
	            else
	            {
	                $from_month += 1;
	                $from_year -= 1;
	            }
	            if($from_month > 9)
	            {
	                $ytd_from_date = $from_year."-".$from_month;
	            }
	            else
	            {
	                $ytd_from_date = $from_year."-0".$from_month;
	            }

	            if($current_month < 7)
	            {
	                $to_month = ($current_month-6)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-6;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-".$to_month;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-".$to_month;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-0".$to_month;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-0".$to_month;
	                }
	            }
	            if($current_month < 6)
	            {
	                $to_month = ($current_month-5)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-5;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month;
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month;
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month;
	                }
	            }
	            $ytd_second_to_date = date("Y-m");
			}
			else if($sort_dates == 10)
			{
				$current_month = date('m');
				$current_year = date('Y');
	            $from_month = $current_month;
	            $from_year = $current_year;
				if($current_month == 12)
	            {
	                $from_month = 01;
	            }
	            else
	            {
	                $from_month += 1;
	                $from_year -= 1;
	            }
	            if($from_month > 9)
	            {
	                $ytd_from_date = $from_year."-".$from_month."-01";
	            }
	            else
	            {
	                $ytd_from_date = $from_year."-0".$from_month."-01";
	            }

	            if($current_month < 7)
	            {
	                $to_month = ($current_month-6)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-6;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-0".$to_day;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-0".$to_day;
	                }
	            }
	            if($current_month < 6)
	            {
	                $to_month = ($current_month-5)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-5;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	            }
	            $ytd_second_to_date = date('Y-m-d',strtotime("-1 days"));
	        }
	        else if($sort_dates == 11)
			{
				$current_month = date('m');
				$current_year = date('Y');
	            $from_month = $current_month;
	            $from_year = $current_year;
				if($current_month == 12)
	            {
	                $from_month = 01;
	            }
	            else
	            {
	                $from_month += 1;
	                $from_year -= 1;
	            }
	            if($from_month > 9)
	            {
	                $ytd_from_date = $from_year."-".$from_month."-01";
	            }
	            else
	            {
	                $ytd_from_date = $from_year."-0".$from_month."-01";
	            }

	            if($current_month < 7)
	            {
	                $to_month = ($current_month-6)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-6;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-0".$to_day;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-0".$to_day;
	                }
	            }
	            if($current_month < 6)
	            {
	                $to_month = ($current_month-5)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-5;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	            }
	            $ytd_second_to_date = date("Y-m-d", strtotime("last week Saturday"));
	        }
	        else if($sort_dates == 12)
			{
				$current_month = date('m');
				$current_year = date('Y');
	            $from_month = $current_month;
	            $from_year = $current_year;
				if($current_month == 12)
	            {
	                $from_month = 01;
	            }
	            else
	            {
	                $from_month += 1;
	                $from_year -= 1;
	            }
	            if($from_month > 9)
	            {
	                $ytd_from_date = $from_year."-".$from_month."-01";
	            }
	            else
	            {
	                $ytd_from_date = $from_year."-0".$from_month."-01";
	            }

	            if($current_month < 7)
	            {
	                $to_month = ($current_month-6)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-6;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-0".$to_day;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-0".$to_day;
	                }
	            }
	            if($current_month < 6)
	            {
	                $to_month = ($current_month-5)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-5;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	            }
	            $ytd_second_to_date = date('Y-m-d');
	        }
	        else if($sort_dates == 13)
	        {
	        	$current_month = date('m');
				$current_year = date('Y');
	            $from_month = $current_month;
	            $from_year = $current_year;
				if($current_month == 12)
	            {
	                $from_month = 01;
	            }
	            else
	            {
	                $from_month += 1;
	                $from_year -= 1;
	            }
	            if($from_month > 9)
	            {
	                $ytd_from_date = $from_year."-".$from_month;
	            }
	            else
	            {
	                $ytd_from_date = $from_year."-0".$from_month;
	            }

	            if($current_month < 7)
	            {
	                $to_month = ($current_month-6)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-6;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-".$to_month;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-".$to_month;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-0".$to_month;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-0".$to_month;
	                }
	            }
	            if($current_month < 6)
	            {
	                $to_month = ($current_month-5)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-5;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month;
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month;
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month;
	                }
	            }
	            $ytd_second_to_date = date("Y-m", strtotime("previous month"));
	        }
	        else if($sort_dates == 15)
	        {
	        	$current_month = date('m');
				$current_year = date('Y');
	            $from_month = $current_month;
	            $from_year = $current_year;
				if($current_month == 12)
	            {
	                $from_month = 01;
	            }
	            else
	            {
	                $from_month += 1;
	                $from_year -= 1;
	            }
	            if($from_month > 9)
	            {
	                $ytd_from_date = $from_year."-".$from_month;
	            }
	            else
	            {
	                $ytd_from_date = $from_year."-0".$from_month;
	            }

	            if($current_month < 7)
	            {
	                $to_month = ($current_month-6)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-6;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-".$to_month;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-".$to_month;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-0".$to_month;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-0".$to_month;
	                }
	            }
	            if($current_month < 6)
	            {
	                $to_month = ($current_month-5)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-5;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month;
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month;
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month;
	                }
	            }
	            $ytd_second_to_date = date("Y-m", strtotime("-3 months"));
	        }
	        else if($sort_dates == 16)
	        {
	        	$current_month = date('m');
				$current_year = date('Y');
	            $from_month = $current_month;
	            $from_year = $current_year;
				if($current_month == 12)
	            {
	                $from_month = 01;
	            }
	            else
	            {
	                $from_month += 1;
	                $from_year -= 1;
	            }
	            if($from_month > 9)
	            {
	                $ytd_from_date = $from_year."-".$from_month."-01";
	            }
	            else
	            {
	                $ytd_from_date = $from_year."-0".$from_month."-01";
	            }

	            if($current_month < 7)
	            {
	                $to_month = ($current_month-6)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-6;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-0".$to_day;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-0".$to_day;
	                }
	            }
	            if($current_month < 6)
	            {
	                $to_month = ($current_month-5)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-5;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	            }
	            $ytd_second_to_date_initial = date("Y-m", strtotime("-3 months"));
	            $ytd_second_to_date_day = date("d");
	            if($ytd_second_to_date_day > 9)
	            {
	            	$ytd_second_to_date = $ytd_second_to_date_initial."-".$ytd_second_to_date_day;
	            }
	            else
	            {
	            	$ytd_second_to_date = $ytd_second_to_date_initial."-0".$ytd_second_to_date_day;
	            }
	        }
	        else if($sort_dates == 17)
	        {
	        	$current_month = date('m');
				$current_year = date('Y')-1;
	            $from_month = $current_month;
	            $from_year = $current_year;
				if($current_month == 12)
	            {
	                $from_month = 01;
	            }
	            else
	            {
	                $from_month += 1;
	                $from_year -= 1;
	            }
	            if($from_month > 9)
	            {
	                $ytd_from_date = $from_year."-".$from_month;
	            }
	            else
	            {
	                $ytd_from_date = $from_year."-0".$from_month;
	            }

	            if($current_month < 7)
	            {
	                $to_month = ($current_month-6)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-6;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-".$to_month;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-".$to_month;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-0".$to_month;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-0".$to_month;
	                }
	            }
	            if($current_month < 6)
	            {
	                $to_month = ($current_month-5)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-5;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month;
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month;
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month;
	                }
	            }
	            $ytd_second_to_year = date("Y")-1;
	            $ytd_second_to_month = date("m");
	            $ytd_second_to_date = $ytd_second_to_year."-".$ytd_second_to_month;
	        }
	        else if($sort_dates == 18)
	        {
	        	$current_month = date('m');
				$current_year = date('Y')-1;
	            $from_month = $current_month;
	            $from_year = $current_year;
				if($current_month == 12)
	            {
	                $from_month = 01;
	            }
	            else
	            {
	                $from_month += 1;
	                $from_year -= 1;
	            }
	            if($from_month > 9)
	            {
	                $ytd_from_date = $from_year."-".$from_month."-01";
	            }
	            else
	            {
	                $ytd_from_date = $from_year."-0".$from_month."-01";
	            }

	            if($current_month < 7)
	            {
	                $to_month = ($current_month-6)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-6;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-".$to_month."-0".$to_day;
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-".$to_day;
	                }
	                else
	                {
	                    $ytd_to_date = $to_year."-0".$to_month."-0".$to_day;
	                }
	            }
	            if($current_month < 6)
	            {
	                $to_month = ($current_month-5)+12;
	                $to_year = $current_year - 1;
	            }
	            else
	            {
	                $to_month = $current_month-5;
	                $to_year = $current_year;
	            }
	            if($to_month > 9)
	            {
	                $temp_date = $to_year."-".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-".$to_month."-01";
	                }
	            }
	            else
	            {
	                $temp_date = $to_year."-0".$to_month;
	                $to_day = date("t", strtotime($temp_date));
	                if($to_day > 9)
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	                else
	                {
	                    $ytd_second_from_date = $to_year."-0".$to_month."-01";
	                }
	            }
	            $ytd_second_to_year = date("Y")-1;
	            $ytd_second_to_date = $ytd_second_to_year."-".date("m-d");
	        }

			$second_date_first_result = get_item_usage_sort_date_type_second_date_v2($item_category_num,$hospiceID,$ytd_from_date,$ytd_to_date,$this->session->userdata('user_location'));
			if(!empty($second_date_first_result))
			{
				foreach ($second_date_first_result as $key => $value) {
					if($value['parentID'] == 0)
					{
						if(!in_array($value['equipmentID'], $equipment_array))
						{
							$equipment_array[] = $value['equipmentID'];
							$graph_data[$value['equipmentID']] = array(
																	"equipment_name" 	=> $value['key_desc'],
																	"count" 			=> 0,
																	"count_second" 		=> 1
																);
						}
						else
						{
							$graph_data[$value['equipmentID']]['count_second'] += 1;
						}
						$graph_data[$value['equipmentID']]['patients'][] = $value['patientID'];
					}
				}
			}

			$second_date_second_result = get_item_usage_sort_date_type_second_date_v2($item_category_num,$hospiceID,$ytd_second_from_date,$ytd_second_to_date,$this->session->userdata('user_location'));
			if(!empty($second_date_second_result))
			{
				foreach ($second_date_second_result as $key => $value) {
					if($value['parentID'] == 0)
					{
						if(!in_array($value['equipmentID'], $equipment_array))
						{
							$equipment_array[] = $value['equipmentID'];
							$graph_data[$value['equipmentID']] = array(
																	"equipment_name" 	=> $value['key_desc'],
																	"count" 			=> 0,
																	"count_second" 		=> 1
																);
						}
						else
						{
							$graph_data[$value['equipmentID']]['count_second'] += 1;
						}
						$graph_data[$value['equipmentID']]['patients'][] = $value['patientID'];
					}
				}
			}

			foreach ($graph_data as $key => $value) { 
				$unique = array_unique($value['patients']);
				$graph[] = array("label" => $value['equipment_name'],"value" => $value['count'],"second_value" => $value['count_second'],"patients" => $unique);
			}

			if($second_from_date != "" && $second_to_date != "")
			{
				$to_date = $second_to_date;
			}

			$data = array(
				"date_range_from"	=> $from_date,
				"graph"				=> $graph,
				"date_range_to"		=> $to_date,
				"ytd_from"			=> $ytd_from_date,
				"ytd_to"			=> $ytd_second_to_date
			);

			$this->common->code 	= 0;    
			$this->common->message 	= "Item Usage Comparison";
			$this->common->data 	= $data;

			$this->common->response(false);
		}

		public function filter_item_usage_standard($filter_from,$filter_to,$hospiceID,$item_category_num,$sort_dates,$filter_type)
		{
			$equipment_array = array();
			$graph_data = array();
			$graph = array();
			$data = array();

			if($filter_type == 1 || $filter_type == 3)
			{
				if($filter_from != 0 && $filter_to != 0)
				{
					$from_date = $filter_from;
					$to_date = $filter_to;
				}
				else
				{
					$from_date = "";
					$to_date = "";
				}
			}
			else if($filter_type == 2)
			{
				$second_from_date = "";
				$second_to_date = "";
				if($sort_dates == 0)
				{
					if($filter_from != 0 && $filter_to != 0)
					{
						$from_date = $filter_from;
						$to_date = $filter_to;
					}
					else
					{
						$from_date = "";
						$to_date = "";
					}
				}
				else if($sort_dates == 1)
				{
					$from_date = date('Y-m-d');
					$to_date = $from_date;
				}
				else if($sort_dates == 2)
				{
					$from_date = (new DateTime('last Sunday'))->format("Y-m-d");
					$to_date = (new DateTime('next Saturday'))->format("Y-m-d");
				}
				else if($sort_dates == 3)
				{
					$from_date = (new DateTime('last Sunday'))->format("Y-m-d");
					$to_date = date('Y-m-d');
				}
				else if($sort_dates == 4)
				{
					$from_date = date('Y-m')."-01";
					$to_date = date('Y-m-t');
				}
				else if($sort_dates == 5)
				{
					$from_date = date('Y-m');
					$from_date = $from_date."-01";
					$to_date = date('Y-m-d');
				}
				else if($sort_dates == 6)
				{
					$from_month = date('m');
					$from_year = date('Y');
					if($from_month == 2)
					{
						$from_initial_date = 12;
						$from_year -= 1; 
					}
					else if($from_month == 1)
					{
						$from_initial_date = 11;
						$from_year -= 1; 
					}
					else
					{
						$from_initial_date = date('m')-2;
					}
					if($from_initial_date > 9)
					{
						$from_date = $from_year."-".$from_initial_date."-01";
					}
					else
					{
						$from_date = $from_year."-0".$from_initial_date."-01";
					}
					$to_date = date('Y-m-t');
				}	
				else if($sort_dates == 7)
				{
					$from_month = date('m');
					$from_year = date('Y');
					if($from_month == 2)
					{
						$from_initial_date = 12;
						$from_year -= 1; 
					}
					else if($from_month == 1)
					{
						$from_initial_date = 11;
						$from_year -= 1; 
					}
					else
					{
						$from_initial_date = date('m')-2;
					}
					if($from_initial_date > 9)
					{
						$from_date = $from_year."-".$from_initial_date."-01";
					}
					else
					{
						$from_date = $from_year."-0".$from_initial_date."-01";
					}
					$to_date = date('Y-m-d');
				}
				else if($sort_dates == 8)
				{
					$from_year = date('Y');
					$from_month = date('m');
					$from_month_final = $from_month;
					$from_year_final = $from_year;
					if($from_month == 12)
					{
						$from_month_final = 01;
					}
					else
					{
						$from_month_final += 1;
						$from_year_final -= 1;
					}
					if($from_month_final > 9)
					{
						$from_date = $from_year_final."-".$from_month_final."-01";
					}
					else
					{
						$from_date = $from_year_final."-0".$from_month_final."-01";
					}
					
					if($from_month < 7)
					{
						$to_month = ($from_month-6)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-6;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
					}
					
					if($from_month < 6)
					{
						$to_month = ($from_month-5)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-5;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
					}
					$second_to_date = date('Y-m-t');
				}
				else if($sort_dates == 9)
				{
					$from_year = date('Y');
					$from_month = date('m');
					$from_month_final = $from_month;
					$from_year_final = $from_year;
					if($from_month == 12)
					{
						$from_month_final = 01;
					}
					else
					{
						$from_month_final += 1;
						$from_year_final -= 1;
					}
					if($from_month_final > 9)
					{	
						$from_date = $from_year_final."-".$from_month_final."-01";
					}
					else
					{	
						$from_date = $from_year_final."-0".$from_month_final."-01";
					}

					if($from_month < 7)
					{
						$to_month = ($from_month-6)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-6;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
					}
					
					if($from_month < 6)
					{
						$to_month = ($from_month-5)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-5;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
					}
					$second_to_date = date('Y-m-d');
				}
				else if($sort_dates == 10)
				{
					$from_date = date('Y-m-d',strtotime("-1 days"));
					$to_date = date('Y-m-d',strtotime("-1 days"));
				}
				else if($sort_dates == 11)
				{
					$from_date = date("Y-m-d", strtotime("last week Monday -1 day"));
					$to_date = date("Y-m-d", strtotime("last week Saturday"));
				}
				else if($sort_dates == 12)
				{
					$from_date = date("Y-m-d", strtotime("last week Monday -1 day"));
					$to_date 	=  date("Y-m-d",strtotime("-7 days"));
				}
				else if($sort_dates == 13)
				{
					$from_date = date("Y-m-d", strtotime("first day of previous month"));
					$to_date = date("Y-m-d", strtotime("last day of previous month"));
				}
				else if($sort_dates == 14)
				{
					$from_date = date("Y-m-d", strtotime("first day of previous month"));
					$to_date = date('Y-m-d');
				}
				else if($sort_dates == 15)
				{
					$current_month = date('m');
					$current_year = date('Y');
					$from_year = $current_year;
					$to_year = $current_year;
					if($current_month < 6)
					{
						$from_month = ($current_month-5)+12;
						$from_year = $current_year - 1;
					}
					else
					{
						$from_month = $current_month-5;
					}

					if($current_month < 4)
					{
						$to_month = ($current_month-3)+12;
						$to_year = $current_year - 1;
					}
					else
					{
						$to_month = $current_month-3;
					}

					if($from_month > 9)
					{	
						$from_date = $from_year."-".$from_month."-01";
					}
					else
					{	
						$from_date = $from_year."-0".$from_month."-01";
					}
					
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{	
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{	
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
					}
				}
				else if($sort_dates == 16)
				{
					$current_month = date('m');
					$current_year = date('Y');
					$from_year = $current_year;
					$to_year = $current_year;
					if($current_month < 6)
					{
						$from_month = ($current_month-5)+12;
						$from_year = $current_year - 1;
					}
					else
					{
						$from_month = $current_month-5;
					}

					if($current_month < 4)
					{
						$to_month = ($current_month-3)+12;
						$to_year = $current_year - 1;
					}
					else
					{
						$to_month = $current_month-3;
					}

					if($from_month > 9)
					{	
						$from_date = $from_year."-".$from_month."-01";
					}
					else
					{	
						$from_date = $from_year."-0".$from_month."-01";
					}
					
					if($to_month > 9)
					{
						$to_day = date('d');
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{	
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$to_day = date('d');
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{	
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
						
					}
				}
				else if($sort_dates == 17)
				{
					$from_year = date('Y')-1;
					$from_month = date('m');
					$from_month_final = $from_month;
					$from_year_final = $from_year;
					if($from_month == 12)
					{
						$from_month_final = 01;
					}
					else
					{
						$from_month_final += 1;
						$from_year_final -= 1;
					}
					if($from_month_final > 9)
					{
						$from_date = $from_year_final."-".$from_month_final."-01";
					}
					else
					{
						$from_date = $from_year_final."-0".$from_month_final."-01";
					}
					
					if($from_month < 7)
					{
						$to_month = ($from_month-6)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-6;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
					}
					
					if($from_month < 6)
					{
						$to_month = ($from_month-5)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-5;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
					}
					$second_to_year = date('Y')-1;
					$second_to_month = date('m');
					if($second_to_month > 9)
					{
						$second_to_day = date('t');	
						if($second_to_day > 9)
						{
							$second_to_date = $second_to_year."-".$second_to_month."-".$second_to_day;
						}
						else
						{
							$second_to_date = $second_to_year."-".$second_to_month."-0".$second_to_day;
						}
					}
					else
					{
						$second_to_day = date('t');	
						if($second_to_day > 9)
						{
							$second_to_date = $second_to_year."-".$second_to_month."-".$second_to_day;
						}
						else
						{
							$second_to_date = $second_to_year."-0".$second_to_month."-0".$second_to_day;
						}
					}
				}
				else if($sort_dates == 18)
				{
					$from_year = date('Y')-1;
					$from_month = date('m');
					$from_month_final = $from_month;
					$from_year_final = $from_year;
					if($from_month == 12)
					{
						$from_month_final = 01;
					}
					else
					{
						$from_month_final += 1;
						$from_year_final -= 1;
					}
					if($from_month_final > 9)
					{
						$from_date = $from_year_final."-".$from_month_final."-01";
					}
					else
					{
						$from_date = $from_year_final."-0".$from_month_final."-01";
					}
					
					if($from_month < 7)
					{
						$to_month = ($from_month-6)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-6;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-".$to_month."-0".$to_day;
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$to_date = $to_year."-0".$to_month."-".$to_day;
						}
						else
						{
							$to_date = $to_year."-0".$to_month."-0".$to_day;
						}
					}
					
					if($from_month < 6)
					{
						$to_month = ($from_month-5)+12;
						$to_year = $from_year - 1;
					}
					else
					{
						$to_month = $current_month-5;
						$to_year = $from_year;
					}
					if($to_month > 9)
					{
						$temp_date = $to_year."-".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-".$to_month."-01";
						}
					}
					else
					{
						$temp_date = $to_year."-0".$to_month;
						$to_day = date("t", strtotime($temp_date));
						if($to_day > 9)
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
						else
						{
							$second_from_date = $to_year."-0".$to_month."-01";
						}
					}
					$second_to_year = date('Y')-1;
					$second_to_month = date('m');
					if($second_to_month > 9)
					{
						$second_to_day = date('d');	
						if($second_to_day > 9)
						{
							$second_to_date = $second_to_year."-".$second_to_month."-".$second_to_day;
						}
						else
						{
							$second_to_date = $second_to_year."-".$second_to_month."-0".$second_to_day;
						}
					}
					else
					{
						$second_to_day = date('d');	
						if($second_to_day > 9)
						{
							$second_to_date = $second_to_year."-".$second_to_month."-".$second_to_day;
						}
						else
						{
							$second_to_date = $second_to_year."-0".$second_to_month."-0".$second_to_day;
						}
					}
				}
			}

			if($hospiceID == 0)
			{
				$hospiceID = "";
			}

			$query_result = get_item_usage_sort_date_type_v2($item_category_num,$hospiceID,$from_date,$to_date,$this->session->userdata('user_location'));
			if(!empty($query_result))
			{ 
				foreach ($query_result as $key => $value) {
					if($value['parentID'] == 0)
					{
						if(!in_array($value['equipmentID'], $equipment_array))
						{
							$equipment_array[] = $value['equipmentID'];
							$graph_data[$value['equipmentID']] = array(
																	"equipment_name" => $value['key_desc'],
																	"count" => 1
																);
						}
						else
						{
							$graph_data[$value['equipmentID']]['count'] += 1;
						}
						$graph_data[$value['equipmentID']]['patients'][] = $value['patientID']; 
					}
				}
				if($second_from_date == "" && $second_to_date == "")
				{
					foreach ($graph_data as $key => $value) {
						$unique = array_unique($value['patients']);
						$graph[] = array("label" => $value['equipment_name'],"value" => $value['count'],"patients" => $unique);
					}
				}
			}

			if($second_from_date != "" && $second_to_date != "")
			{
				$second_query_result = get_item_usage_sort_date_type_second_date_v2($item_category_num,$hospiceID,$second_from_date,$second_to_date,$this->session->userdata('user_location'));
				if(!empty($second_query_result))
				{
					foreach ($second_query_result as $key => $value) {
						if($value['parentID'] == 0)
						{
							if(!in_array($value['equipmentID'], $equipment_array))
							{
								$equipment_array[] = $value['equipmentID'];
								$graph_data[$value['equipmentID']] = array(
																		"equipment_name" => $value['key_desc'],
																		"count" => 1
																	);
							}
							else
							{
								$graph_data[$value['equipmentID']]['count'] += 1;
							}
							$graph_data[$value['equipmentID']]['patients'][] = $value['patientID']; 
						}
					}
					foreach ($graph_data as $key => $value) {
						$unique = array_unique($value['patients']);
						$graph[] = array("label" => $value['equipment_name'],"value" => $value['count'],"patients" => $unique);
					}
				}
			}

			if($second_from_date != "" && $second_to_date != "")
			{
				$to_date = $second_to_date;
			}
			$data = array(
				"date_range_from"	=> $from_date,
				"graph"				=> $graph,
				"date_range_to"		=> $to_date
			);
			
			$this->common->code 	= 0;    
			$this->common->message 	= "Item Usage";
			$this->common->data 	= $data;

			$this->common->response(false);
		}

		public function get_activity_status_sample_data($activity_status_name,$date_from,$date_to,$hospiceID)
		{
			$current_date = date('Y-m-d');
    		$data['patient_list'] = array();
    		$sign_here = 0;
    		$result = array();
			if($activity_status_name == "new_pt")
		  	{
		  		$sign_here = 1;
		  		$count = 0;
		  		if($hospiceID == 0)
		  		{
		  			$hospiceID = "";
		  		}
		  		if(!empty($date_from) && !empty($date_to))
		  		{
		  			$current_date = "";
		  		}

		  		if($current_date != "")
		  		{
		  			$list_active_patients_result = list_patients_by_hospice_v3($hospiceID,$this->session->userdata('user_location'));
		  			if(!empty($list_active_patients_result))
		  			{
				  		foreach($list_active_patients_result as $value) 
			    		{
			    			$query_result = get_patient_first_order($value['patientID']);
			    			if($query_result['order_status'] == "active")
		    				{
		    					$result[$count] = $value;
		    					$added_patientIDs[$count] = $value['patientID'];
	  							$count++;
		    				}
			    		}
			    	}
		  		}
		  		else
		  		{
		  			$list_active_patients_result = list_patients_by_hospice_v3($hospiceID,$this->session->userdata('user_location'));
		  			if(!empty($list_active_patients_result))
		  			{
		  				if($date_from == $current_day && $date_to == $current_day || $date_from == $current_day && $date_to > $current_day)
		  				{
		  					if(($date_from <= $current_day))
			                {
			                    if($date_from <= $date_to)
			                    {
			                        if($date_to == $current_day || $date_to > $current_day)
			                        {
			                        	foreach($list_active_patients_result as $value) 
							    		{
							    			$query_result_new = get_patient_first_order($value['patientID']);
							    			if($query_result_new['order_status'] == "active")
							    			{
							    				if(!in_array($value['patientID'], $added_patientIDs))
							    				{
							    					$result[$count] = $value;
							    					$added_patientIDs[$count] = $value['patientID'];
						  							$count++;
							    				}
							    			}
							    		}
			                        }
			                    }
			                }
		  				}
		  				else
		  				{
		  					foreach($list_active_patients_result as $value) 
				    		{
				    			$query_result = get_patient_first_order($value['patientID']);
				    			
				    			if($date_to == $current_day || $date_to > $current_day)
				    			{
				    				if($query_result['actual_order_date'] >= $date_from && $query_result['actual_order_date'] < $date_to)
					    			{
					    				$result[$count] = $value;
					    				$added_patientIDs[$count] = $value['patientID'];
					  					$count++;
					    			}
				    			}
				    			else
				    			{
				    				if($query_result['actual_order_date'] >= $date_from && $query_result['actual_order_date'] <= $date_to)
					    			{
					    				$result[$count] = $value;
					    				$added_patientIDs[$count] = $value['patientID'];
					  					$count++;
					    			}
				    			}
				    		}

				    		if(($date_from <= $current_day))
			                {
			                    if($date_from <= $date_to)
			                    {
			                        if($date_to == $current_day || $date_to > $current_day)
			                        {
			                        	foreach($list_active_patients_result as $value) 
							    		{
							    			$query_result_new = get_patient_first_order($value['patientID']);
							    			if($query_result_new['order_status'] == "active")
							    			{
							    				if(!in_array($value['patientID'], $added_patientIDs))
							    				{
							    					$result[$count] = $value;
							    					$added_patientIDs[$count] = $value['patientID'];
						  							$count++;
							    				}
							    			}
							    		}
			                        }
			                    }
			                }
		  				}
		  			}
		  		}		  		
		  	}
		  	else if($activity_status_name == "new_item")
		  	{
		  		$sign_here = 3;
		  		if($hospiceID == 0)
		  		{
		  			$hospiceID = "";
		  		}
		  		if(!empty($date_from) && !empty($date_to))
		  		{
		  			$current_date = "";
		  		}
		  		$query_result = get_patient_new_item_list_v2($current_date,$date_from,$date_to,$hospiceID,$this->session->userdata('user_location'));
		  		$count_inside = 0;
		  		$last_patientID = 0;
		  		$last_uniqueID = 0;
		  		$item_count = 0;

		  		$query_result_count = 0;
		  		foreach($query_result as $key => $value)
		  		{
		  			if($value['parentID'] == 0)
		  			{
		  				$query_result_count++;
		  			}
		  		}

		  		foreach ($query_result as $key => $value) {
	  				if($count_inside != 0)
	  				{
	  					if($last_patientID != $value['patientID'])
	  					{
	  						if($value['parentID'] == 0)
	  						{
	  							$check_first_order = get_patient_first_order($value['patientID']);
					  			if($check_first_order['uniqueID'] != $value['uniqueID'])
					  			{
		  							$count_inside++;
				  					$patient_details = get_patient_info($value['patientID']);
					  				$result[$count_inside] = $patient_details;
					  				$result[$count_inside]['item_count'] = 1;
					  				$last_patientID = $value['patientID'];
					  				$last_uniqueID = $value['uniqueID'];
		  						}
	  						}
			  			}
			  			else
			  			{
			  				if($value['parentID'] == 0)
				  			{
				  				$item_count++;
				  				$result[$count_inside]['item_count'] = $item_count;
				  				$last_patientID = $value['patientID'];
				  				$last_uniqueID = $value['uniqueID'];
				  			}
			  			}
	  				}
	  				else
	  				{
	  					if($value['parentID'] == 0)
			  			{
		  					if(($last_patientID != $value['patientID']) && ($last_patientID != 0))
		  					{
		  						$check_first_order = get_patient_first_order($last_patientID);
					  			if($check_first_order['uniqueID'] != $last_uniqueID)
					  			{
		  							$patient_details = get_patient_info($last_patientID);
					  				$result[$count_inside] = $patient_details;
					  				$result[$count_inside]['item_count'] = $item_count;
					  				$count_inside++;
					  				$item_count = 1;
		  						}

		  						$check_first_order_v2 = get_patient_first_order($value['patientID']);
					  			if($check_first_order_v2['uniqueID'] != $value['uniqueID'])
					  			{
		  							$patient_details = get_patient_info($value['patientID']);
					  				$result[$count_inside] = $patient_details;
					  				$result[$count_inside]['item_count'] = 1;
					  				$last_patientID = $value['patientID'];
					  				$last_uniqueID = $value['uniqueID'];
		  						}
		  					}
		  					else if(($last_patientID == $value['patientID']) && ($last_patientID != 0))
		  					{
		  						$new_query_result_count = $query_result_count-1;
		  						if($new_query_result_count == $item_count)
		  						{
		  							$check_first_order = get_patient_first_order($value['patientID']);
						  			if($check_first_order['uniqueID'] != $value['uniqueID'])
						  			{
			  							$patient_details = get_patient_info($value['patientID']);
						  				$result[$count_inside] = $patient_details;
						  				$result[$count_inside]['item_count'] = $item_count+1;
						  				$last_patientID = $value['patientID'];
						  				$last_uniqueID = $value['uniqueID'];
			  						}
		  						}
		  						else
		  						{
		  							$item_count++;
		  						}
		  					}
			  				
			  				if($last_patientID != $value['patientID'])
			  				{
			  					$last_patientID = $value['patientID'];
			  					$last_uniqueID = $value['uniqueID'];
			  					$item_count++;
			  				}
			  				
			  			}
	  				}
		  		}

		  		if($date_from != "" && $date_to != "")
		  		{
		  			$current_date = date('Y-m-d');
		  			if(($date_from <= $current_date))
	                {
	                    if($date_from <= $date_to)
	                    {
	                        if($date_to == $current_date || $date_to > $current_date)
	                        {
	                        	$new_query_result = get_patient_new_item_list_v2($current_date,"","",$hospiceID,$this->session->userdata('user_location'));
	                        	$last_patientID = 0;
						  		$last_uniqueID = 0;
						  		$item_count = 0;

						  		$query_result_count = 0;
						  		foreach($new_query_result as $key => $value)
						  		{
						  			if($value['parentID'] == 0)
						  			{
						  				$query_result_count++;
						  			}
						  		}

						  		foreach ($new_query_result as $key => $value) {
					  				if($count_inside != 0)
					  				{
					  					if($last_patientID != $value['patientID'])
					  					{
					  						if($value['parentID'] == 0)
					  						{
					  							$check_first_order = get_patient_first_order($value['patientID']);
									  			if($check_first_order['uniqueID'] != $value['uniqueID'])
									  			{
						  							$count_inside++;
								  					$patient_details = get_patient_info($value['patientID']);
									  				$result[$count_inside] = $patient_details;
									  				$result[$count_inside]['item_count'] = 1;
									  				$last_patientID = $value['patientID'];
									  				$last_uniqueID = $value['uniqueID'];
						  						}
					  						}
							  			}
							  			else
							  			{
							  				if($value['parentID'] == 0)
								  			{
								  				$item_count++;
								  				$result[$count_inside]['item_count'] = $item_count;
								  				$last_patientID = $value['patientID'];
								  				$last_uniqueID = $value['uniqueID'];
								  			}
							  			}
					  				}
					  				else
					  				{
					  					if($value['parentID'] == 0)
							  			{
						  					if(($last_patientID != $value['patientID']) && ($last_patientID != 0))
						  					{
						  						$check_first_order = get_patient_first_order($last_patientID);
									  			if($check_first_order['uniqueID'] != $last_uniqueID)
									  			{
						  							$patient_details = get_patient_info($last_patientID);
									  				$result[$count_inside] = $patient_details;
									  				$result[$count_inside]['item_count'] = $item_count;
									  				$count_inside++;
									  				$item_count = 1;
						  						}

						  						$check_first_order_v2 = get_patient_first_order($value['patientID']);
									  			if($check_first_order_v2['uniqueID'] != $value['uniqueID'])
									  			{
						  							$patient_details = get_patient_info($value['patientID']);
									  				$result[$count_inside] = $patient_details;
									  				$result[$count_inside]['item_count'] = 1;
									  				$last_patientID = $value['patientID'];
									  				$last_uniqueID = $value['uniqueID'];
						  						}
						  					}
						  					else if(($last_patientID == $value['patientID']) && ($last_patientID != 0))
						  					{
						  						$new_query_result_count = $query_result_count-1;
						  						if($new_query_result_count == $item_count)
						  						{
						  							$check_first_order = get_patient_first_order($value['patientID']);
										  			if($check_first_order['uniqueID'] != $value['uniqueID'])
										  			{
							  							$patient_details = get_patient_info($value['patientID']);
										  				$result[$count_inside] = $patient_details;
										  				$result[$count_inside]['item_count'] = $item_count+1;
										  				$last_patientID = $value['patientID'];
										  				$last_uniqueID = $value['uniqueID'];
							  						}
						  						}
						  						else
						  						{
						  							$item_count++;
						  						}
						  					}
							  				
							  				if($last_patientID != $value['patientID'])
							  				{
							  					$last_patientID = $value['patientID'];
							  					$last_uniqueID = $value['uniqueID'];
							  					$item_count++;
							  				}
							  				
							  			}
					  				}
						  		}
	                        }
	                    }
	                }
	            }
		  	}
		  	else if($activity_status_name == "exchange")
		  	{
		  		$sign_here = 3;
		  		if($hospiceID == 0)
		  		{
		  			$hospiceID = "";
		  		}
		  		if(!empty($date_from) && !empty($date_to))
		  		{
		  			$current_date = "";
		  		}
		  		$query_result = get_patient_exchange_list_v2($current_date,$date_from,$date_to,$hospiceID,$this->session->userdata('user_location'));
		  		$count_inside = 0;
		  		$last_patientID = 0;
		  		$item_count = 0;
		  		$query_result_count = 0;
		  		foreach($query_result as $key => $value)
		  		{
		  			if($value['parentID'] == 0)
		  			{
		  				$query_result_count++;
		  			}
		  		}

		  		foreach ($query_result as $key => $value) {
	  				if($count_inside != 0)
	  				{
	  					if($last_patientID != $value['patientID'])
	  					{
	  						$count_inside++;
		  					$patient_details = get_patient_info($value['patientID']);
			  				$result[$count_inside] = $patient_details;
			  				$result[$count_inside]['item_count'] = 0;
			  				$last_patientID = $value['patientID'];
			  				
			  				if($value['parentID'] == 0)
				  			{
			  					$item_count = 1;
			  				}
			  			}
			  			else
			  			{
			  				if($value['parentID'] == 0)
				  			{
				  				$item_count++;
				  				$result[$count_inside]['item_count'] = $item_count;
				  				$last_patientID = $value['patientID'];
				  			}
			  			}
	  				}
	  				else
	  				{
	  					if($value['parentID'] == 0)
			  			{
			  				$item_count++;

			  				if(($last_patientID != $value['patientID']) && ($last_patientID != 0))
		  					{
		  						$patient_details = get_patient_info($last_patientID);
				  				$result[$count_inside] = $patient_details;
				  				$result[$count_inside]['item_count'] = $item_count;
				  				$count_inside++;
				  				$item_count = 0;

				  				$patient_details = get_patient_info($value['patientID']);
				  				$result[$count_inside] = $patient_details;
				  				$result[$count_inside]['item_count'] = 0;
				  				$last_patientID = $value['patientID'];
		  					}
		  					else if(($last_patientID == $value['patientID']) && ($last_patientID != 0))
		  					{
		  						if($query_result_count == $item_count)
		  						{
		  							$patient_details = get_patient_info($value['patientID']);
					  				$result[$count_inside] = $patient_details;
					  				$result[$count_inside]['item_count'] = $item_count;
					  				$last_patientID = $value['patientID'];
		  						}
		  					}
		  					else if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
		  					{
		  						if($query_result_count == 1)
		  						{
		  							$patient_details = get_patient_info($value['patientID']);
					  				$result[$count_inside] = $patient_details;
					  				$result[$count_inside]['item_count'] = $item_count;
					  				$last_patientID = $value['patientID'];
		  						}
		  					}
			  				$last_patientID = $value['patientID'];
			  			}
			  			else if($query_result_count == 1)
			  			{
			  				if($query_result_count == $item_count)
	  						{
	  							$patient_details = get_patient_info($value['patientID']);
				  				$result[$count_inside] = $patient_details;
				  				$result[$count_inside]['item_count'] = $item_count;
				  				$last_patientID = $value['patientID'];
	  						}
			  			}
	  				}
	  			}
	  			if($date_from != "" && $date_to != "")
		  		{
		  			$current_date = date('Y-m-d');
		  			if(($date_from <= $current_date))
	                {
	                    if($date_from <= $date_to)
	                    {
	                        if($date_to == $current_date || $date_to > $current_date)
	                        {
	                        	$new_query_result = get_patient_exchange_list_v2($current_date,"","",$hospiceID,$this->session->userdata('user_location'));

	                        	$last_patientID = 0;
						  		$item_count = 0;
						  		$query_result_count = 0;
						  		foreach($new_query_result as $key => $value)
						  		{
						  			if($value['parentID'] == 0)
						  			{
						  				$query_result_count++;
						  			}
						  		}
						  		foreach ($new_query_result as $key => $value) {
					  				if($count_inside != 0)
					  				{
					  					if($last_patientID != $value['patientID'])
					  					{
					  						$count_inside++;
						  					$patient_details = get_patient_info($value['patientID']);
							  				$result[$count_inside] = $patient_details;
							  				$result[$count_inside]['item_count'] = 0;
							  				$last_patientID = $value['patientID'];
							  				
							  				if($value['parentID'] == 0)
								  			{
							  					$item_count = 1;
							  				}
							  			}
							  			else
							  			{
							  				if($value['parentID'] == 0)
								  			{
								  				$item_count++;
								  				$result[$count_inside]['item_count'] = $item_count;
								  				$last_patientID = $value['patientID'];
								  			}
							  			}
					  				}
					  				else
					  				{
					  					if($value['parentID'] == 0)
							  			{
							  				$item_count++;

							  				if(($last_patientID != $value['patientID']) && ($last_patientID != 0))
						  					{
						  						$patient_details = get_patient_info($last_patientID);
								  				$result[$count_inside] = $patient_details;
								  				$result[$count_inside]['item_count'] = $item_count;
								  				$count_inside++;
								  				$item_count = 0;

								  				$patient_details = get_patient_info($value['patientID']);
								  				$result[$count_inside] = $patient_details;
								  				$result[$count_inside]['item_count'] = 0;
								  				$last_patientID = $value['patientID'];
						  					}
						  					else if(($last_patientID == $value['patientID']) && ($last_patientID != 0))
						  					{
						  						if($query_result_count == $item_count)
						  						{
						  							$patient_details = get_patient_info($value['patientID']);
									  				$result[$count_inside] = $patient_details;
									  				$result[$count_inside]['item_count'] = $item_count;
									  				$last_patientID = $value['patientID'];
						  						}
						  					}
						  					else if(($last_patientID != $value['patientID']) && ($last_patientID == 0))
						  					{
						  						if($query_result_count == 1)
						  						{
						  							$patient_details = get_patient_info($value['patientID']);
									  				$result[$count_inside] = $patient_details;
									  				$result[$count_inside]['item_count'] = $item_count;
									  				$last_patientID = $value['patientID'];
						  						}
						  					}
							  				$last_patientID = $value['patientID'];
							  			}
							  			else if($query_result_count == 1)
							  			{
							  				if($query_result_count == $item_count)
					  						{
					  							$patient_details = get_patient_info($value['patientID']);
								  				$result[$count_inside] = $patient_details;
								  				$result[$count_inside]['item_count'] = $item_count;
								  				$last_patientID = $value['patientID'];
					  						}
							  			}
					  				}
					  			}
	                        }
	                    }
	                }
	            }
		  	}
		  	else if($activity_status_name == "pickup")
		  	{
		  		$sign_here = 2;
		  		if($hospiceID == 0)
		  		{
		  			$hospiceID = "";
		  		}
		  		if(!empty($date_from) && !empty($date_to))
		  		{
		  			$current_date = "";
		  		}
		  		$result_1 = get_patient_pickup_list_v2($current_date,$date_from,$date_to,$hospiceID,$this->session->userdata('user_location'));

		  		$result_2 = array();
		  		if($date_from != "" && $date_to != "")
		  		{
		  			$current_date = date('Y-m-d');
		  			if(($date_from <= $current_date))
	                {
	                    if($date_from <= $date_to)
	                    {
	                        if($date_to == $current_date || $date_to > $current_date)
	                        {
	                     		$result_2 = get_patient_pickup_list_v2($current_date,"","",$hospiceID,$this->session->userdata('user_location'));
	                        }
	                    }
	                }
	            }
	            $result = array_merge($result_1,$result_2);
		  	}
		  	else if($activity_status_name == "pt_move")
		  	{
		  		$sign_here = 1;
		  		if($hospiceID == 0)
		  		{
		  			$hospiceID = "";
		  		}
		  		if(!empty($date_from) && !empty($date_to))
		  		{
		  			$current_date = "";
		  		}
		  		$result_here = get_ptmove_list_v2($current_date,$date_from,$date_to,$hospiceID,$this->session->userdata('user_location'));

		  		$count = 0;
		  		foreach ($result_here as $value) {
		  			$query_result = get_ptmove_address_first_order($value['patientID'],$value['addressID']);
		  			if($query_result['uniqueID'] == $value['uniqueID'])
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
		  		}
		  		if($date_from != "" && $date_to != "")
		  		{
		  			$current_date = date('Y-m-d');
		  			if(($date_from <= $current_date))
	                {
	                    if($date_from <= $date_to)
	                    {
	                        if($date_to == $current_date || $date_to > $current_date)
	                        {
                        		$new_result_here = get_ptmove_list_v2($current_date,"","",$hospiceID,$this->session->userdata('user_location'));
						  		foreach ($new_result_here as $value) {
						  			$query_result = get_ptmove_address_first_order($value['patientID'],$value['addressID']);
						  			if($query_result['uniqueID'] == $value['uniqueID'])
						  			{
						  				$result[$count] = $value;
						  				$count++;
						  			}
						  		}
	                        }
	                    }
	                }
	            }
		  	}
		  	else
		  	{
		  		$sign_here = 1;
		  		if($hospiceID == 0)
		  		{
		  			$hospiceID = "";
		  		}
		  		if(!empty($date_from) && !empty($date_to))
		  		{
		  			$current_date = "";
		  		}
		  		$result_here = get_respite_list_v2($current_date,$date_from,$date_to,$hospiceID,$this->session->userdata('user_location'));

		  		$count = 0;
		  		foreach ($result_here as $value) {
		  			$query_result = get_respite_address_first_order($value['patientID'],$value['addressID']);
		  			if($query_result['uniqueID'] == $value['uniqueID'])
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
		  		}
		  		if($date_from != "" && $date_to != "")
		  		{
		  			$current_date = date('Y-m-d');
		  			if(($date_from <= $current_date))
	                {
	                    if($date_from <= $date_to)
	                    {
	                        if($date_to == $current_date || $date_to > $current_date)
	                        {
	                        	$new_result_here = get_respite_list_v2($current_date,"","",$hospiceID,$this->session->userdata('user_location'));

						  		foreach ($new_result_here as $value) {
						  			$query_result = get_respite_address_first_order($value['patientID'],$value['addressID']);
						  			if($query_result['uniqueID'] == $value['uniqueID'])
						  			{
						  				$result[$count] = $value;
						  				$count++;
						  			}
						  		}
	                        }
	                    }
	                }
	            }
		  	}

		  	$data['patient_list_temp'] = array_msort($result, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));

		  	$count_for_sample_data = 0;
		  	$data['patient_list'] = array();
		  	foreach ($data['patient_list_temp'] as $value) {
		  		if($count_for_sample_data < 3)
		  		{
		  			$data['patient_list'][$count_for_sample_data] = $value;
		  			$count_for_sample_data++;
		  		}
		  	}

		  	echo json_encode($data);
		}

		public function get_residence_status_sample_data($residence_status_name,$date_from,$date_to,$hospiceID)
		{
			$current_date = date('Y-m-d');
    		$data['patient_list'] = array();
    		$data['patient_list_temp'] = array();
    		$result = array();
    		$count = 0;

    		if($residence_status_name == "assisted_living")
		  	{
		  		$status_name_new = "Assisted Living";
		  	}
		  	else if($residence_status_name == "group_home")
		  	{
		  		$status_name_new = "Group Home";
		  	}
		  	else if($residence_status_name == "hic_home")
		  	{
		  		$status_name_new = "Hic Home";
		  	}
		  	else if($residence_status_name == "home_care")
		  	{
		  		$status_name_new = "Home Care";
		  	}
		  	else
		  	{
		  		$status_name_new = "Skilled Nursing Facility";
		  	}

		  	if($date_from == 0 || $date_to == 0)
	  		{
	  			$current_date = date('Y-m-d');

	  			$latest_patient_on_list = get_latest_patient_on_list_v2($this->session->userdata('user_location'));
	    		$border_bottom = $latest_patient_on_list['patientID'] - 500;
	    		$border_top = "";

	    		$list_active_patients_result = list_active_patients_by_hospice_and_residence_v3($border_bottom,$border_top,$hospiceID,$status_name_new,$this->session->userdata('user_location'));
	    		foreach($list_active_patients_result as $value) 
	    		{
	    			$query_result = get_patient_first_order($value['patientID']);
		  			if(date("Y-m-d", $query_result['uniqueID']) == $current_date)
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
	    		}
	  		}
	  		else
	  		{
	  			$list_active_patients_result = list_active_patients_by_hospice_and_residence_v4($hospiceID,$status_name_new,$this->session->userdata('user_location'));
	    		foreach($list_active_patients_result as $value) 
	    		{
	    			$query_result = get_patient_first_order($value['patientID']);
	    			if(date("Y-m-d", $query_result['uniqueID']) >= $date_from && date("Y-m-d", $query_result['uniqueID']) <= $date_to)
		  			{
		  				$result[$count] = $value;
		  				$count++;
		  			}
	    		}
	  		}

	  		$data['patient_list_temp'] = array_msort($result, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
	  		$count = 0;
	  		foreach ($data['patient_list_temp'] as $key => $value) {
	  			if($count < 3)
	  			{
	  				$data['patient_list'][$count] = $value;
	  				$count++;
	  			}
	  		}

	  		echo json_encode($data);
		}

		public function get_reports_by_user_filtered($date,$hospiceID="")
		{
			$this->templating_library->set('title','Reports');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$data['users_list'] = get_users_list_v2($this->session->userdata('group_id'),$this->session->userdata('user_location'));
				$data['admin_list'] = get_dme_admin_list_v2($this->session->userdata('user_location'));
			}
			else
			{
				if($hospiceID != 0)
				{
					$data['users_list'] = get_users_list_v2($hospiceID,$this->session->userdata('user_location'));
					$data['admin_list'] = get_dme_admin_list_v2($this->session->userdata('user_location'));
				}
				else
				{
					$data['users_list'] = get_users_list_v2("",$this->session->userdata('user_location'));
					$data['admin_list'] = "";
				}
			}
			if($date == "0000-00-00")
			{
				$data['date_choosen'] = "";
			}	
			else
			{
				$data['date_choosen'] = $date;
			}
			
			$data['hospice_selected'] = $hospiceID;

			$this->templating_library->set('title','');
			$this->templating_library->set_view('pages/view_reports_by_user','pages/view_reports_by_user', $data);
			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}

		public function reports_by_user()
		{
			$this->templating_library->set('title','Reports');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$data['users_list'] = get_users_list_v2($this->session->userdata('group_id'),$this->session->userdata('user_location'));
				$data['admin_list'] = get_dme_admin_list_v2($this->session->userdata('user_location'));
			}
			else
			{
				$data['users_list'] = get_users_list_v2("",$this->session->userdata('user_location'));
				$data['admin_list'] = "";
			}

			$data['hospice_selected'] = 0;

			$this->templating_library->set('title','');
			$this->templating_library->set_view('pages/view_reports_by_user','pages/view_reports_by_user', $data);
			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}

		public function o2concentrator_follow_up()
		{
			$this->templating_library->set('title','Reports');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');

			$from_date_selected = date('Y-m-01');
			$to_date_selected = date('Y-m-t');
			$hospiceID = "";

			$data['follow_up_list'] = $this->mreport->get_o2_concentrator_follow_up_list($from_date_selected,$to_date_selected,$this->session->userdata('user_location'));

			$count = 0;
			foreach ($data['follow_up_list'] as $key => $value) {
				$latest_pickup_activity = $this->mreport->get_latest_pickup_activity($value['patientID']);

				if(empty($latest_pickup_activity) || $latest_pickup_activity['pickup_sub'] == "not needed")
				{
					if(empty($latest_pickup_activity))
					{
						$data['follow_up_list'][$count]['patient_active_sign'] = 1;
					}
					else
					{
						$item_pickup = $this->mreport->get_item_pickup($value['patientID'],$value['equipmentID'],$value['uniqueID']);
						if(empty($item_pickup))
						{
							$data['follow_up_list'][$count]['patient_active_sign'] = 1;
						}
						else
						{
							$check_item_confirmed_pickup = $this->mreport->check_item_confirmed_pickup($value['patientID'],$value['equipmentID'],$item_pickup['pickedup_uniqueID']);
							if(empty($check_item_confirmed_pickup))
							{
								$data['follow_up_list'][$count]['patient_active_sign'] = 1;
							}
							else
							{
								$data['follow_up_list'][$count]['patient_active_sign'] = 0;
							}
						}
					}	
				}
				else
				{
					$latest_patient_order = $this->mreport->get_latest_patient_order($value['patientID']);
					if($latest_patient_order['uniqueID'] == $latest_pickup_activity['order_uniqueID'])
					{
						$data['follow_up_list'][$count]['patient_active_sign'] = 0;	
					}
					else
					{
						$data['follow_up_list'][$count]['patient_active_sign'] = 1;
					}
				}
				$count++;
			}
			
			$this->templating_library->set('title','');
			$this->templating_library->set_view('pages/view_o2concentrator_follow_up','pages/view_o2concentrator_follow_up', $data);
			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}

		public function sort_o2_concentrator_follow_up_date($hospiceID,$from_date,$to_date)
		{
			$data = array();

			if($from_date == 0 || $to_date == 0)
			{	
				$from_date = "";
				$to_date = "";
			}

			$data['follow_up_list'] = $this->mreport->get_o2_concentrator_follow_up_list($from_date,$to_date,$this->session->userdata('user_location'));

			$count = 0;
			foreach ($data['follow_up_list'] as $key => $value) {
				$latest_pickup_activity = $this->mreport->get_latest_pickup_activity($value['patientID']);

				if(empty($latest_pickup_activity) || $latest_pickup_activity['pickup_sub'] == "not needed")
				{
					if(empty($latest_pickup_activity))
					{
						$data['follow_up_list'][$count]['patient_active_sign'] = 1;
					}
					else
					{
						$item_pickup = $this->mreport->get_item_pickup($value['patientID'],$value['equipmentID'],$value['uniqueID']);
						if(empty($item_pickup))
						{
							$data['follow_up_list'][$count]['patient_active_sign'] = 1;
						}
						else
						{
							$check_item_confirmed_pickup = $this->mreport->check_item_confirmed_pickup($value['patientID'],$value['equipmentID'],$item_pickup['pickedup_uniqueID']);
							if(empty($check_item_confirmed_pickup))
							{
								$data['follow_up_list'][$count]['patient_active_sign'] = 1;
							}
							else
							{
								$data['follow_up_list'][$count]['patient_active_sign'] = 0;
							}
						}
					}	
				}
				else
				{
					$latest_patient_order = $this->mreport->get_latest_patient_order($value['patientID']);
					if($latest_patient_order['uniqueID'] == $latest_pickup_activity['order_uniqueID'])
					{
						$data['follow_up_list'][$count]['patient_active_sign'] = 0;	
					}
					else
					{
						$data['follow_up_list'][$count]['patient_active_sign'] = 1;
					}
				}
				$count++;
			}

			echo json_encode($data);
		}

		public function patient_lists()
		{
			$patients = $_GET['patients'];
			if(!empty($patients))
			{
				$patientsdecoded = array();
				try
				{
					$patientsdecoded = json_decode($patients,TRUE); 
				}catch(Exception $error)
				{

				}

				if(!empty($patientsdecoded))
				{
					$this->load->model("user_model");
					$patients = $this->user_model->list_patients($patientsdecoded);

					$this->data['patients'] = $patients;
				}
			}
			$this->load->view("pages/modals/report-patients",$this->data);
		}
	}









?>