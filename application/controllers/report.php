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
			$this->load->model("billing_statement_model");
			$this->load->model('user_model');
			$this->load->model('hospice_model');
			$this->load->model('mreport');
			$this->load->model('equipment_model');
			date_default_timezone_set('America/Los_Angeles');
		}

		// public function get_missing_customer()
		// {
		// 	$all_active_customer = $this->mreport->get_customer_residence_report_today($this->session->userdata('user_location'));
		// 	$deliver_to_type_assisted_all = array_filter($all_active_customer, function ($var) {
		// 	    return ($var['deliver_to_type'] != "Assisted Living" && $var['deliver_to_type'] != "Group Home" && $var['deliver_to_type'] != "Hic Home" && $var['deliver_to_type'] != "Home Care" && $var['deliver_to_type'] != "Skilled Nursing Facility");
		// 	});

		// 	print_me($deliver_to_type_assisted_all);
		// }

		public function update_customer_location()
		{
			$result = $this->user_model->get_specific_customers(3000,3500);

			$batched_patient_array = array();
			foreach ($result as $key => $value) {
				$data = array(
					'patientID'			=> $value['patientID'],
					'account_location' 	=> $value['account_location']
				);
				$batched_patient_array[] = $data;
			}
			$this->user_model->update_customer_location($batched_patient_array);
		}

		public function index()
		{
			$this->templating_library->set('title','Reports');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');

			// DME User Access/Restriction
			if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'hospice_user') {

				$this->load->model("order_model");
				$current_date = date("Y-m-d");

				$result = $this->order_model->list_active_patients_v3($this->session->userdata('user_location'));
				$data['total_patients'] = count($result);

				if($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor')
				{
					$total_los_today =  get_total_patient_los_current_date_hopice_v2($this->session->userdata('group_id'), $this->session->userdata('user_location'));
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
			}

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
			$newitem = 0;
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
	  			if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller'|| $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor')
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
				if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user' || $this->session->userdata('account_type') == 'biller' || $this->session->userdata('account_type') == 'sales_rep' || $this->session->userdata('account_type') == 'distribution_supervisor')
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

			/*********** New Customer AND New Item (Delivery) ************/

			$date_now = date('Y-m-d');
			$combined_delivery_data = array();
			$filter_from = (empty($pt_filter['date_range']['from']) || $pt_filter['date_range']['from'] == 0)? date('Y-m-d') : $pt_filter['date_range']['from'];
			$filter_to = (empty($pt_filter['date_range']['to']) || $pt_filter['date_range']['to'] == 0)? $filter_from." 23:59:59" : $pt_filter['date_range']['to']." 23:59:59";
			$returned_data = get_patient_new_item_list_v3($date_now,$filter_from,$filter_to,$date_fetch['hospiceID'],$this->session->userdata('user_location'),array());
			$combined_delivery_data = $returned_data['data'];

			$formatted_to = date("Y-m-d",strtotime($filter_to));
			if (($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now)) {
				$another_delivery_container = get_patient_new_item_list_v3($date_now,$date_now,$date_now,$date_fetch['hospiceID'],$this->session->userdata('user_location'),array());
				$combined_delivery_data = array_merge($combined_delivery_data,$another_delivery_container['data']);
			}

			$patient_first_orders = array(); // uniqueID => patientID
			foreach ($combined_delivery_data as $key => $value) {
				if (!in_array($value['patientID'], $patient_first_orders)) {
					$customer_first_order = get_patient_first_order_status_v2($value['patientID']);
					if (!empty($customer_first_order)) {
						$patient_first_orders[$customer_first_order['order_uniqueID']] = $value['patientID'];

						if ($customer_first_order['order_uniqueID'] == $value['uniqueID']) {
							$new_pt++;
						} else {
							$newitem++;
						}
					}
				} else {
					if ($patient_first_orders[$value['uniqueID']]) {
						$new_pt++;
					} else {
						$newitem++;
					}
				}
			}

			/*********** END of New Customer AND New Item (Delivery) ************/



	  		/*********** New Customer ************/

	  // 		$date_now = date('Y-m-d');
			// $filter_from = (empty($pt_filter['date_range']['from']) || $pt_filter['date_range']['from'] == 0)? date('Y-m-d') : $pt_filter['date_range']['from'];
			// $filter_to = (empty($pt_filter['date_range']['to']) || $pt_filter['date_range']['to'] == 0)? $filter_from." 23:59:59" : $pt_filter['date_range']['to']." 23:59:59";
	  // 		$new_pt = get_new_patient_list_v4($date_now,$filter_from,$filter_to,$date_fetch['hospiceID'],$this->session->userdata('user_location'),array(),true);

	  		/*********** END of New Patient ************/

			/*********** Delivery ************/

			// $date_now = date('Y-m-d');
			// $filter_from = (empty($pt_filter['date_range']['from']) || $pt_filter['date_range']['from'] == 0)? date('Y-m-d') : $pt_filter['date_range']['from'];
			// $filter_to = (empty($pt_filter['date_range']['to']) || $pt_filter['date_range']['to'] == 0)? $filter_from." 23:59:59" : $pt_filter['date_range']['to']." 23:59:59";
			// $returned_data = get_patient_new_item_list_v3($date_now,$filter_from,$filter_to,$date_fetch['hospiceID'],$this->session->userdata('user_location'),array(),true);
			// $newitem = $returned_data['total'];

			// $formatted_to = date("Y-m-d",strtotime($filter_to));
			// if (($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now)) {
			// 	$count_container = get_patient_new_item_list_v3($date_now,$date_now,$date_now,$date_fetch['hospiceID'],$this->session->userdata('user_location'),array(),true);
			// 	$newitem += $count_container['total'];
			// }

	  		/*********** End of Delivery ************/

			/*********** Exchange ************/

			$date_now = date('Y-m-d');
			$filter_from = (empty($pt_filter['date_range']['from']) || $pt_filter['date_range']['from'] == 0)? date('Y-m-d') : $pt_filter['date_range']['from'];
			$filter_to = (empty($pt_filter['date_range']['to']) || $pt_filter['date_range']['to'] == 0)? $filter_from." 23:59:59" : $pt_filter['date_range']['to']." 23:59:59";
			$returned_data = get_patient_exchange_list_v3($date_now,$filter_from,$filter_to,$date_fetch['hospiceID'],$this->session->userdata('user_location'),array(),true);
			$exchange = $returned_data['total'];

			$formatted_to = date("Y-m-d",strtotime($filter_to));
			if(($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now))
			{
				$count_container = get_patient_exchange_list_v3($date_now,$date_now,$date_now,$date_fetch['hospiceID'],$this->session->userdata('user_location'),array(),true);
				$exchange += $count_container['total'];
			}
  			/*********** END of Exchange ************/

	  		/*********** Pickup ************/

			$date_now = date('Y-m-d');
			$filter_from = (empty($pt_filter['date_range']['from']) || $pt_filter['date_range']['from'] == 0)? date('Y-m-d') : $pt_filter['date_range']['from'];
			$filter_to = (empty($pt_filter['date_range']['to']) || $pt_filter['date_range']['to'] == 0)? $filter_from." 23:59:59" : $pt_filter['date_range']['to']." 23:59:59";
			$returned_data = get_patient_pickup_list_v3($date_now,$filter_from,$filter_to,$date_fetch['hospiceID'],$this->session->userdata('user_location'),$pagination_details,true);
			$pickup = $returned_data['total'];

			$formatted_to = date("Y-m-d",strtotime($filter_to));
			if(($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now))
			{
				$count_container = get_patient_pickup_list_v3($date_now,$date_now,$date_now,$date_fetch['hospiceID'],$this->session->userdata('user_location'),$pagination_details,true);
				$pickup += $count_container['total'];
			}
			/*********** END of Pickup ************/

			/*********** Customer Move ************/

			$date_now = date('Y-m-d');
			$filter_from = (empty($pt_filter['date_range']['from']) || $pt_filter['date_range']['from'] == 0)? date('Y-m-d') : $pt_filter['date_range']['from'];
			$filter_to = (empty($pt_filter['date_range']['to']) || $pt_filter['date_range']['to'] == 0)? $filter_from." 23:59:59" : $pt_filter['date_range']['to']." 23:59:59";
			$returned_data = get_ptmove_list_v3($date_now,$filter_from,$filter_to,$date_fetch['hospiceID'],$this->session->userdata('user_location'),array(),true);
			$ptmove = $returned_data['total'];

	  		$formatted_to = date("Y-m-d",strtotime($filter_to));
			if(($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now))
			{
				$count_container = get_ptmove_list_v3($date_now,$date_now,$date_now,$date_fetch['hospiceID'],$this->session->userdata('user_location'),array(),true);
				$ptmove += $count_container['total'];
			}
	  		/*********** END of Customer Move ************/

			/*********** Respite ************/

			$date_now = date('Y-m-d');
			$filter_from = (empty($pt_filter['date_range']['from']) || $pt_filter['date_range']['from'] == 0)? date('Y-m-d') : $pt_filter['date_range']['from'];
			$filter_to = (empty($pt_filter['date_range']['to']) || $pt_filter['date_range']['to'] == 0)? $filter_from." 23:59:59" : $pt_filter['date_range']['to']." 23:59:59";
			$returned_data = get_respite_list_v3($date_now,$filter_from,$filter_to,$date_fetch['hospiceID'],$this->session->userdata('user_location'),array(),true);
			$respite = $returned_data['total'];

			$formatted_to = date("Y-m-d",strtotime($filter_to));
			if(($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now))
			{
				$count_container = get_respite_list_v3($date_now,$date_now,$date_now,$date_fetch['hospiceID'],$this->session->userdata('user_location'),array(),true);
				$respite += $count_container['total'];
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
								$pt_filter['date_range_newcustomer'] = array("from"=>$date,"to"=>$date,"sign_filtered"=>1);
								$pt_filter['current_date'] = array("sign"=>1,"sign_second"=>0,"hospiceID"=>$date_fetch['hospiceID']);
								$result_query = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
								$result_count = $result_query[0]['total'];
					  	}
					  	else
					  	{
					  		$pt_filter['date_range_newcustomer'] = array("from"=>$date,"to"=>$date,"sign_filtered"=>1);
								$pt_filter['current_date'] = array("sign"=>1,"sign_second"=>1,"hospiceID"=>$date_fetch['hospiceID']);
								$result_query = $this->mreport->get_patient_count_v2($pt_filter,"","",$this->session->userdata('user_location'));
								$result_count = $result_query[0]['total'];
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
				$pt_filter['date_range'] = "";
			}
			else
			{
				$pt_filter = array("date_range"=>$date_fetch);
			}
			$pt_filter['hospiceID'] = $date_fetch['hospiceID'];
			$types = array(
							"Assisted Living",
							"Group Home",
							"Hic Home",
							"Home Care",
							"Skilled Nursing Facility"
						);
			$graph = array();

			$filter_from = $date_fetch['from'];
			$filter_to = $date_fetch['to'];
			// print_me('sample russel here 1');
			$current_day = date('Y-m-d');
			if ((empty($filter_from) && empty($filter_to)) || ($filter_from == $current_day && $filter_to == $current_day)) {

				// print_me('sample russel here 2');
				foreach ($types as $key => $value)
				{
					$result = $this->mreport->get_customer_residence_report_today($this->session->userdata('user_location'), $value);
					$graph[] = array(
									"label" => $value,
									"value" => count($result)
								);

				}
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
				foreach ($types as $key => $value)
				{
					$pt_filter = array(
						"deliver_to_type" => $value,
						"date_range" => array(
							"from" => $date,
							"to" => ""
						)
					);
					$pt_filter['deliver_to_type'] = $value;
					$total = list_residence_status_new_approach_v2($value,$date_fetch['hospiceID'],$this->session->userdata('user_location'),$date,'',array(),true);
					$second_total = list_residence_status_count_cus_move($value,$date_fetch['hospiceID'],$this->session->userdata('user_location'),$date,'',array(),true);
					$total += $second_total;
					//$total = $this->mreport->patience_residence_report($date_fetch['hospiceID'],$this->session->userdata('user_location'),$pt_filter);

					$explode = explode(" ",$value);
					$temp[strtolower(implode("_",$explode))] = $total*1;
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

		public function view_each_activity_status($activity_status_name,$hospiceID,$date_from="",$date_to="")
		{
			$data = array();
			$data['activity_status_name'] = $activity_status_name;
			$data['hospiceID'] = $hospiceID;

			$current_day = date("Y-m-d");

			if($date_from==""){
				$date_from = $current_day;
			}
			$date_to = ($date_to=="")? $date_from." 23:59:59" : $date_to." 23:59:59";

			$data['date_from'] = $date_from;
			$data['date_to'] 	 = $date_to;

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

			// DME User Access/Restriction
			if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'hospice_user') {
				$this->templating_library->set_view('pages/activity_status_details','pages/activity_status_details', $data);
			}

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
			$data['filter_from'] = date('Y-m-d');
			$data['filter_to'] = date('Y-m-d');

			$this->templating_library->set('title','');

			// DME User Access/Restriction
			if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'hospice_user') {
				$this->templating_library->set_view('pages/residence_status_details','pages/residence_status_details', $data);
			}

			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}

		public function sort_activity_status_details($filter_from,$filter_to,$hospiceID,$status_name,$page=1,$limit=10)
		{
			if($limit > 10){
				$limit = 10;
			}
			$offset = ($page - 1) * $limit;
			$results_info = array(
								"total_records" => 0,
								"total_pages"   => 0,
								"current_page"  => $page
						 	);
			// $pagination_details = array();
			$pagination_details = array(
									"offset" => $offset,
									"limit"  => $limit
								);


			$current_day = date('Y-m-d');
			$current_date = $current_day;
			$data['patient_list'] = array();
    		$sign_here = 0;
    		$result = array();
			if($filter_from == 0 || empty($filter_from))
	  		{
	  			$current_date = date('Y-m-d');
				$filter_from = $current_date;
	  		}
	  		if($filter_to == 0 || empty($filter_to))
	  		{
	  			$filter_to = $current_date;
	  		}
	  		if($hospiceID == 0)
	  		{
	  			$hospiceID = "";
	  		}

			$filter_to = ($filter_to==0 || $filter_to=="")? $filter_from." 23:59:59" : $filter_to." 23:59:59";
			if($status_name == "new_pt")
			{

				// $date_now = date('Y-m-d');
				// $combined_delivery_data = array();
				// $returned_data = get_patient_new_item_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
				// $combined_delivery_data = $returned_data['data'];

				// $formatted_to = date("Y-m-d",strtotime($filter_to));
				// if (($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now)) {
				// 	$query_result_temp = get_patient_new_item_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
				// 	$combined_delivery_data = array_merge($combined_delivery_data,$query_result_temp['data']);
				// }

				// $patient_first_orders = array(); // uniqueID => patientID
				// foreach ($combined_delivery_data as $key => $value) {
				// 	if (!in_array($value['patientID'], $patient_first_orders)) {
				// 		$customer_first_order = get_patient_first_order_status_v2($value['patientID']);
				// 		if (!empty($customer_first_order)) {
				// 			$patient_first_orders[$customer_first_order['order_uniqueID']] = $value['patientID'];

				// 			if ($customer_first_order['order_uniqueID'] == $value['uniqueID']) {
				// 				$results_info['total_records']++;
				// 			}
				// 		}
				// 	} else {
				// 		if ($patient_first_orders[$value['uniqueID']]) {
				// 			$results_info['total_records']++;
				// 		}
				// 	}
				// }









				$data['sign_here'] = 1;
				$results_info['total_records'] = get_new_patient_list_v4($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true);
	  			$query_result = get_new_patient_list_v4($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
				$data['patient_list'] = $query_result;
			}
		  	else if($status_name == "new_item")
		  	{
		  		$data['sign_here'] = 3;
		  		$date_now = date('Y-m-d');
		  		$temp_container = get_patient_new_item_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
		  		$query_result = $temp_container['data'];
		  		$results_info['total_records'] = $temp_container['total'];

		  		$formatted_to = date("Y-m-d",strtotime($filter_to));
				if (($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now)) {
					$query_result_temp = get_patient_new_item_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					$query_result = array_merge($query_result,$query_result_temp['data']);
					$results_info['total_records'] += $query_result_temp['total'];
				}
















		  	}
		  	else if($status_name == "exchange")
		  	{
		  		$data['sign_here'] = 3;
		  		$date_now = date('Y-m-d');
		  		$temp_container = get_patient_exchange_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
		  		$query_result = $temp_container['data'];
		  		$results_info['total_records'] = $temp_container['total'];

		  		$formatted_to = date("Y-m-d",strtotime($filter_to));
				if (($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now)) {
					$query_result_temp = get_patient_exchange_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					$query_result = array_merge($query_result,$query_result_temp['data']);
					$results_info['total_records'] += $query_result_temp['total'];
				}

		  		// $data['sign_here'] = 3;
				// $results_info['total_records'] = get_patient_exchange_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true);
		  		// $query_result = get_patient_exchange_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);

		  	}
		  	else if($status_name == "pickup")
		  	{
		  		$data['sign_here'] = 2;
		  		$result_2 = array();
		  		$date_now = date('Y-m-d');
		  		$temp_container = get_patient_pickup_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
		  		$query_result = $temp_container['data'];
		  		$results_info['total_records'] = $temp_container['total'];

				$temp_not_needed = 0;
				$temp_expired = 0;
				$temp_revoked = 0;
				$temp_discharged = 0;

		  		$formatted_to = date("Y-m-d",strtotime($filter_to));
				if (($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now)) {
					$query_result_temp = get_patient_pickup_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					$query_result = array_merge($query_result,$query_result_temp['data']);
					$results_info['total_records'] += $query_result_temp['total'];

					// $temp_not_needed = get_patient_pickup_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"not needed")['total'];
					// $temp_expired = get_patient_pickup_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"expired")['total'];
					// $temp_revoked = get_patient_pickup_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"revoked")['total'];
					// $temp_discharged = get_patient_pickup_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"discharged")['total'];
					$temp_all_pickup_status = get_patient_pickup_list_v4($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					foreach($temp_all_pickup_status['data'] as $pickupstat) {
						switch($pickupstat['pickup_sub']) {
							case "not needed": $temp_not_needed++; break;
							case "expired": $temp_expired++; break;
							case "revoked": $temp_revoked++; break;
							case "discharged": $temp_discharged++; break;
						}
					}
				}

				$all_pickup_status = get_patient_pickup_list_v4($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);

				foreach($all_pickup_status['data'] as $pickupstat) {
					switch($pickupstat['pickup_sub']) {
						case "not needed": $temp_not_needed++; break;
						case "expired": $temp_expired++; break;
						case "revoked": $temp_revoked++; break;
						case "discharged": $temp_discharged++; break;
					}
				}

				$data['reasons_stats'] = array(
					"notneed" => $temp_not_needed,
					"expired" => $temp_expired,
					"revoked" => $temp_revoked,
					"discharged" => $temp_discharged,
				);

				// $not_needed = get_patient_pickup_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"not needed");
				// $expired = get_patient_pickup_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"expired");
				// $revoked = get_patient_pickup_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"revoked");
				// $discharged = get_patient_pickup_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"discharged");
				// $data['reasons_stats'] = array(
				// 							"notneed" => $not_needed['total'] + $temp_not_needed,
				// 							"expired" => $expired['total'] + $temp_expired,
				// 							"revoked" => $revoked['total'] + $temp_revoked,
				// 							"discharged" => $discharged['total'] + $temp_discharged,
				// 						);

		  // 		$result_2 = array();
		  // 		$data['sign_here'] = 2;
				// if(empty($filter_from)){
				// 	$filter_from = date("Y-m-d");
				// }
				// if(empty($filter_to)){
				// 	$filter_to = $filter_from." 23:59:59";
				// }
				// $results_info['total_records'] = get_patient_pickup_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true);
		  // 		$query_result = get_patient_pickup_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
				// $data['reasons_stats'] = array(
				// 							"notneed" => get_patient_pickup_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"not needed"),
				// 							"expired" => get_patient_pickup_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"expired"),
				// 							"revoked" => get_patient_pickup_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"revoked"),
				// 							"discharged" => get_patient_pickup_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"discharged"),
				// 						);

			}
		  	else if($status_name == "pt_move")
		  	{
		  		$data['sign_here'] = 1;
		  		$date_now = date('Y-m-d');
		  		$temp_container = get_ptmove_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
		  		$query_result = $temp_container['data'];
		  		$results_info['total_records'] = $temp_container['total'];

		  		$formatted_to = date("Y-m-d",strtotime($filter_to));
				if(($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now))
				{
					$query_result_temp = get_ptmove_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					$query_result = array_merge($query_result,$query_result_temp['data']);
					$results_info['total_records'] += $query_result_temp['total'];
				}

		  		// $data['sign_here'] = 1;
				// if(empty($filter_from)){
				// 	$filter_from = date("Y-m-d");
				// }
				// if(empty($filter_to)){
				// 	$filter_to = $filter_from." 23:59:59";
				// }
				// $results_info['total_records'] = get_ptmove_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true);
		  		// $query_result = get_ptmove_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);

		  	}
		  	else
		  	{
		  		$data['sign_here'] = 1;
		  		$date_now = date('Y-m-d');
		  		$temp_container = get_respite_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
		  		$query_result = $temp_container['data'];
		  		$results_info['total_records'] = $temp_container['total'];

		  		$formatted_to = date("Y-m-d",strtotime($filter_to));
				if(($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now))
				{
					$query_result_temp = get_respite_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					$query_result = array_merge($query_result,$query_result_temp['data']);
					$results_info['total_records'] += $query_result_temp['total'];
				}

		  		// $data['sign_here'] = 1;
				// if(empty($filter_from)){
				// 	$filter_from = date("Y-m-d");
				// }
				// if(empty($filter_to)){
				// 	$filter_to = $filter_from." 23:59:59";
				// }
				// $results_info['total_records'] = get_respite_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true);
		  		// $query_result = get_respite_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);

		  	}

		  	$data['query_result'] = $query_result;

			if(strtolower($status_name)=="respite" || strtolower($status_name)=="pt_move" || strtolower($status_name)=="pickup"){
				$data['patient_list'] = $query_result;
			}

			//results
			$results_info['total_records'] = $results_info['total_records']==null? 0 : $results_info['total_records'];
			$total_pages = ($results_info['total_records'] > 0)? ceil($results_info['total_records'] / $limit) : 0;
			$results_info['total_pages'] = $total_pages;
			$data['pagination_details'] = $results_info;
			$data['patient_list'] = $query_result;

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


		public function view_each_residence_status($residence_status_name,$hospiceID,$from,$to)
		{
			$data = array();
			$data['residence_status_name'] = $residence_status_name;
			$data['hospiceID'] = $hospiceID;

			if(!isset($from) || $from==""){
				$from = date("Y-m-d");
			}

			if(!isset($to) || $to==""){
				$to = date("Y-m-d");
			}
			$data['filter_from'] = $from;
			$data['filter_to'] = $to;
			$this->templating_library->set('title','');
			$this->templating_library->set_view('pages/view_residence_status_details','pages/view_residence_status_details', $data);
		}

		public function sort_residence_status_details($filter_from, $filter_to, $hospiceID,$status_name,$page=1,$limit=10)
		{
			if($limit > 10){
				$limit = 10;
			}
			$offset = ($page - 1) * $limit;
			$results_info = array(
												"total_records" => 0,
												"total_pages"   => 0,
												"current_page"  => $page
										 );
			// $pagination_details = array(
			// 												"offset" => $offset,
			// 												"limit"  => $limit
			// 										);
			$pagination_details = array(); // 07/23/2021
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
			$current_date = date('Y-m-d');

			$filter_from  = ($filter_from=="" || $filter_from==0)? $current_date : $filter_from;
			$filter_to = ($filter_to=="" || $filter_to==0)? $current_date : $filter_to;

			$results_info['total_records'] = list_residence_status_new_approach_v2($status_name_new,$hospiceID,$this->session->userdata('user_location'),$filter_from,$filter_to,$pagination_details,true);
			$query_results = list_residence_status_new_approach_v2($status_name_new,$hospiceID,$this->session->userdata('user_location'),$filter_from,$filter_to,$pagination_details);

			$second_total = list_residence_status_count_cus_move($status_name_new,$hospiceID,$this->session->userdata('user_location'),$filter_from,$filter_to,$pagination_details,true);
			$query_results_second = list_residence_status_count_cus_move($status_name_new,$hospiceID,$this->session->userdata('user_location'),$filter_from,$filter_to,$pagination_details);

			$results_info['total_records'] += $second_total;
			$final_list = array_merge($query_results,$query_results_second);
			$final_list1 = sortArray($final_list);

			$results_info['total_records'] = $results_info['total_records']==null? 0 : $results_info['total_records'];
			$total_pages = ($results_info['total_records'] > 0)? ceil($results_info['total_records'] / $limit) : 0;
			$results_info['total_pages'] = $total_pages;

			$data['patient_list'] 			= $final_list1;
			$data['pagination_details']	= $results_info;
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

		public function print_residence_status_details_v2($filter_from,$filter_to,$hospiceID,$status_name)
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

			$pagination_details = array();
	  		$filter_from  = ($filter_from=="" || $filter_from==0)? $current_date : $filter_from;
			$filter_to = ($filter_to=="" || $filter_to==0)? $current_date : $filter_to;

			$results_info['total_records'] = list_residence_status_new_approach_v2($status_name_new,$hospiceID,$this->session->userdata('user_location'),$filter_from,$filter_to,$pagination_details,true);
			$query_results = list_residence_status_new_approach_v2($status_name_new,$hospiceID,$this->session->userdata('user_location'),$filter_from,$filter_to,$pagination_details);

			$second_total = list_residence_status_count_cus_move($status_name_new,$hospiceID,$this->session->userdata('user_location'),$filter_from,$filter_to,$pagination_details,true);
			$query_results_second = list_residence_status_count_cus_move($status_name_new,$hospiceID,$this->session->userdata('user_location'),$filter_from,$filter_to,$pagination_details);

			$results_info['total_records'] += $second_total;
			$data['patient_list'] = array_merge($query_results,$query_results_second);

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

		public function print_activity_status_details_v2($filter_from,$filter_to,$hospiceID,$status_name)
		{
			$data['filter_from'] = $filter_from;
			$data['filter_to'] = $filter_to;
			$data['hospice_name'] = get_hospice_name($hospiceID);

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
	  		

	  		$pagination_details = array();

			$current_day = date('Y-m-d');
			$current_date = $current_day;
			$data['patient_list'] = array();
    		$sign_here = 0;
    		$result = array();
			if($filter_from == 0 || empty($filter_from))
	  		{
	  			$current_date = date('Y-m-d');
				$filter_from = $current_date;
	  		}
	  		if($filter_to == 0 || empty($filter_to))
	  		{
	  			$filter_to = $current_date;
	  		}
	  		if($hospiceID == 0)
	  		{
	  			$hospiceID = "";
	  		}

			$filter_to = ($filter_to==0 || $filter_to=="")? $filter_from." 23:59:59" : $filter_to." 23:59:59";
			if($status_name == "new_pt")
			{
				$data['sign_here'] = 1;
				$results_info['total_records'] = get_new_patient_list_v4($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true);
	  			$query_result = get_new_patient_list_v4($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
				$data['patient_list'] = $query_result;
			}
		  	else if($status_name == "new_item")
		  	{
		  		$data['sign_here'] = 3;
		  		$date_now = date('Y-m-d');
		  		$temp_container = get_patient_new_item_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
		  		$query_result = $temp_container['data'];
		  		$results_info['total_records'] = $temp_container['total'];

		  		$formatted_to = date("Y-m-d",strtotime($filter_to));
				if (($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now)) {
					$query_result_temp = get_patient_new_item_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					$query_result = array_merge($query_result,$query_result_temp['data']);
					$results_info['total_records'] += $query_result_temp['total'];
				}
		  	}
		  	else if($status_name == "exchange")
		  	{
		  		$data['sign_here'] = 3;
		  		$date_now = date('Y-m-d');
		  		$temp_container = get_patient_exchange_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
		  		$query_result = $temp_container['data'];
		  		$results_info['total_records'] = $temp_container['total'];

		  		$formatted_to = date("Y-m-d",strtotime($filter_to));
				if (($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now)) {
					$query_result_temp = get_patient_exchange_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					$query_result = array_merge($query_result,$query_result_temp['data']);
					$results_info['total_records'] += $query_result_temp['total'];
				}

		  		// $data['sign_here'] = 3;
				// $results_info['total_records'] = get_patient_exchange_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true);
		  		// $query_result = get_patient_exchange_list_v2($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);

		  	}
		  	else if($status_name == "pickup")
		  	{
		  		$data['sign_here'] = 2;
		  		$result_2 = array();
		  		$date_now = date('Y-m-d');
		  		$temp_container = get_patient_pickup_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
		  		$query_result = $temp_container['data'];
		  		$results_info['total_records'] = $temp_container['total'];

				$temp_not_needed = 0;
				$temp_expired = 0;
				$temp_revoked = 0;
				$temp_discharged = 0;

		  		$formatted_to = date("Y-m-d",strtotime($filter_to));
				if (($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now)) {
					$query_result_temp = get_patient_pickup_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					$query_result = array_merge($query_result,$query_result_temp['data']);
					$results_info['total_records'] += $query_result_temp['total'];

					// $temp_not_needed = get_patient_pickup_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"not needed")['total'];
					// $temp_expired = get_patient_pickup_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"expired")['total'];
					// $temp_revoked = get_patient_pickup_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"revoked")['total'];
					// $temp_discharged = get_patient_pickup_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details,true,"discharged")['total'];
					$temp_all_pickup_status = get_patient_pickup_list_v4($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					foreach($temp_all_pickup_status['data'] as $pickupstat) {
						switch($pickupstat['pickup_sub']) {
							case "not needed": $temp_not_needed++; break;
							case "expired": $temp_expired++; break;
							case "revoked": $temp_revoked++; break;
							case "discharged": $temp_discharged++; break;
						}
					}
				}

				$all_pickup_status = get_patient_pickup_list_v4($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);

				foreach($all_pickup_status['data'] as $pickupstat) {
					switch($pickupstat['pickup_sub']) {
						case "not needed": $temp_not_needed++; break;
						case "expired": $temp_expired++; break;
						case "revoked": $temp_revoked++; break;
						case "discharged": $temp_discharged++; break;
					}
				}

				$data['reasons_stats'] = array(
					"notneed" => $temp_not_needed,
					"expired" => $temp_expired,
					"revoked" => $temp_revoked,
					"discharged" => $temp_discharged,
				);
			}
		  	else if($status_name == "pt_move")
		  	{
		  		$data['sign_here'] = 1;
		  		$date_now = date('Y-m-d');
		  		$temp_container = get_ptmove_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
		  		$query_result = $temp_container['data'];
		  		$results_info['total_records'] = $temp_container['total'];

		  		$formatted_to = date("Y-m-d",strtotime($filter_to));
				if(($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now))
				{
					$query_result_temp = get_ptmove_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					$query_result = array_merge($query_result,$query_result_temp['data']);
					$results_info['total_records'] += $query_result_temp['total'];
				}
		  	}
		  	else
		  	{
		  		$data['sign_here'] = 1;
		  		$date_now = date('Y-m-d');
		  		$temp_container = get_respite_list_v3($current_date,$filter_from,$filter_to,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
		  		$query_result = $temp_container['data'];
		  		$results_info['total_records'] = $temp_container['total'];

		  		$formatted_to = date("Y-m-d",strtotime($filter_to));
				if(($filter_from < $date_now && $formatted_to == $date_now) || ($filter_from < $date_now && $formatted_to > $date_now))
				{
					$query_result_temp = get_respite_list_v3($date_now,$date_now,$date_now,$hospiceID,$this->session->userdata('user_location'),$pagination_details);
					$query_result = array_merge($query_result,$query_result_temp['data']);
					$results_info['total_records'] += $query_result_temp['total'];
				}
		  	}

		  	$data['query_result'] = $query_result;
			$data['patient_list'] = $query_result;
			// if(strtolower($status_name)=="respite" || strtolower($status_name)=="pt_move" || strtolower($status_name)=="pickup"){
			// 	$data['patient_list'] = $query_result;
			// }

			
		  	// $data['patient_list'] = array_msort($result, array('p_fname' => SORT_ASC,'p_lname' => SORT_ASC));
		  	// $data['query_result'] = $query_result;

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

			// DME User Access/Restriction
			if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'hospice_user') {
				$this->templating_library->set_view('pages/item_usage_details','pages/item_usage_details', $data);
			}

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

		// public function get_reports_by_user_filtered($date,$hospiceID="")
		// {
		// 	$this->templating_library->set('title','Reports');
		// 	$this->templating_library->set_view('common/head','common/head');
		// 	$this->templating_library->set_view('common/header','common/header');
		// 	$this->templating_library->set_view('common/nav','common/nav');

		// 	if($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user')
		// 	{
		// 		$data['users_list'] = get_users_list_v2($this->session->userdata('group_id'),$this->session->userdata('user_location'));
		// 		$data['admin_list'] = get_dme_admin_list_v2($this->session->userdata('user_location'));
		// 	}
		// 	else
		// 	{
		// 		if($hospiceID != 0)
		// 		{
		// 			$data['users_list'] = get_users_list_v2($hospiceID,$this->session->userdata('user_location'));
		// 			$data['admin_list'] = get_dme_admin_list_v2($this->session->userdata('user_location'));
		// 		}
		// 		else
		// 		{
		// 			$data['users_list'] = get_users_list_v2("",$this->session->userdata('user_location'));
		// 			$data['admin_list'] = "";
		// 		}
		// 	}
		// 	if($date == "0000-00-00")
		// 	{
		// 		$data['date_choosen'] = "";
		// 	}
		// 	else
		// 	{
		// 		$data['date_choosen'] = $date;
		// 	}

		// 	$data['hospice_selected'] = $hospiceID;

		// 	$this->templating_library->set('title','');
		// 	$this->templating_library->set_view('pages/view_reports_by_user','pages/view_reports_by_user', $data);
		// 	$this->templating_library->set_view('common/footer','common/footer');
		// 	$this->templating_library->set_view('common/foot','common/foot');
		// }

		// public function reports_by_user()
		// {
		// 	$this->templating_library->set('title','Reports');
		// 	$this->templating_library->set_view('common/head','common/head');
		// 	$this->templating_library->set_view('common/header','common/header');
		// 	$this->templating_library->set_view('common/nav','common/nav');

		// 	if($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user')
		// 	{
		// 		$data['users_list'] = get_users_list_v2($this->session->userdata('group_id'),$this->session->userdata('user_location'));
		// 		$data['admin_list'] = get_dme_admin_list_v2($this->session->userdata('user_location'));
		// 	}
		// 	else
		// 	{
		// 		$data['users_list'] = get_users_list_v2("",$this->session->userdata('user_location'));
		// 		$data['admin_list'] = "";
		// 	}

		// 	$data['hospice_selected'] = 0;

		// 	$this->templating_library->set('title','');
		// 	$this->templating_library->set_view('pages/view_reports_by_user','pages/view_reports_by_user', $data);
		// 	$this->templating_library->set_view('common/footer','common/footer');
		// 	$this->templating_library->set_view('common/foot','common/foot');
		// }

		public function get_reports_by_user_filtered($date,$hospiceID="")
		{
			$this->templating_library->set('title','Reports');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');

			// if($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user')
			// {
			// 	$data['users_list'] = get_users_list_v2($this->session->userdata('group_id'),$this->session->userdata('user_location'));
			// 	$data['admin_list'] = get_dme_admin_list_v2($this->session->userdata('user_location'));
			// }
			// else
			// {
			// 	if($hospiceID != 0)
			// 	{
			// 		$data['users_list'] = get_users_list_v2($hospiceID,$this->session->userdata('user_location'));
			// 		$data['admin_list'] = get_dme_admin_list_v2($this->session->userdata('user_location'));
			// 	}
			// 	else
			// 	{
			// 		$data['users_list'] = get_users_list_v2("",$this->session->userdata('user_location'));
			// 		$data['admin_list'] = "";
			// 	}
			// }
			// $data['users_list'] = get_dme_admin_list_v2($this->session->userdata('user_location'));

			if($date == "0000-00-00")
			{
				$data['date_choosen'] = "";
			}
			else
			{
				$data['date_choosen'] = $date;
			}

			$data['hospice_selected'] = $hospiceID;
			$current_date = $date;
			if($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user')
			{
				$data['users_list'] = get_users_list_with_created_orders_with_date($this->session->userdata('group_id'),$this->session->userdata('user_location'),$current_date);
			}
			else
			{
				if($hospiceID != 0)
				{
					$data['users_list'] = get_users_list_with_created_orders_with_date($hospiceID,$this->session->userdata('user_location'),$current_date);
					$data['admin_list'] = "";
				}
				else
				{
					$data['users_list'] = get_users_list_with_created_orders_with_date("",$this->session->userdata('user_location'),$current_date);
					$data['admin_list'] = "";
				}

			}
			$listed_userID = array();
			$staff_entries = array();
			$total_entry_counter = 1;
			$entry_counter = 0;
			foreach ($data['users_list'] as $key => $value) {
				if(!in_array($value['ordered_by'], $listed_userID))
				{
					$total_entry_counter = 1;
					$entry_counter = 0;
					$listed_userID[] = $value['ordered_by'];
					$staff_entries[$value['ordered_by']]['firstname'] = $value['firstname'];
					$staff_entries[$value['ordered_by']]['lastname'] = $value['lastname'];
					$staff_entries[$value['ordered_by']]['group_id'] = $value['group_id'];
					$staff_entries[$value['ordered_by']]['entry_count'] = $total_entry_counter;
					$staff_entries[$value['ordered_by']]['entries'][$entry_counter] = $value;
					$entry_counter++;
				}
				else
				{
					$total_entry_counter++;
					$staff_entries[$value['ordered_by']]['entry_count'] = $total_entry_counter;
					$staff_entries[$value['ordered_by']]['entries'][$entry_counter] = $value;
					$entry_counter++;
				}
			}
			$data['staff_entries'] = $staff_entries;

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

			// DME User Access/Restriction
			if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'rt' && $this->session->userdata('account_type') != 'hospice_user') {
				$current_date = date('Y-m-d');
				// $current_date = "2018-05-26";
				if($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type') != 'dme_user' && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor')
				{
					// $data['users_list'] = get_users_list_v2($this->session->userdata('group_id'),$this->session->userdata('user_location'));
					$data['users_list'] = get_users_list_with_created_orders_with_date($this->session->userdata('group_id'),$this->session->userdata('user_location'),$current_date);
					// $data['admin_list'] = get_dme_admin_list_v2($this->session->userdata('user_location'));
				}
				else
				{
					// $data['users_list'] = get_users_list_v2("",$this->session->userdata('user_location'));
					$data['users_list'] = get_users_list_with_created_orders_with_date("",$this->session->userdata('user_location'),$current_date);
					$data['admin_list'] = "";
				}
				$data['hospice_selected'] = 0;

				$listed_userID = array();
				$staff_entries = array();
				$total_entry_counter = 1;
				$entry_counter = 0;
				foreach ($data['users_list'] as $key => $value) {
					if(!in_array($value['ordered_by'], $listed_userID))
					{
						$total_entry_counter = 1;
						$entry_counter = 0;
						$listed_userID[] = $value['ordered_by'];
						$staff_entries[$value['ordered_by']]['firstname'] = $value['firstname'];
						$staff_entries[$value['ordered_by']]['lastname'] = $value['lastname'];
						$staff_entries[$value['ordered_by']]['group_id'] = $value['group_id'];
						$staff_entries[$value['ordered_by']]['entry_count'] = $total_entry_counter;
						$staff_entries[$value['ordered_by']]['entries'][$entry_counter] = $value;
						$entry_counter++;
					}
					else
					{
						$total_entry_counter++;
						$staff_entries[$value['ordered_by']]['entry_count'] = $total_entry_counter;
						$staff_entries[$value['ordered_by']]['entries'][$entry_counter] = $value;
						$entry_counter++;
					}
				}
				$data['staff_entries'] = $staff_entries;

				$this->templating_library->set('title','');
				$this->templating_library->set_view('pages/view_reports_by_user','pages/view_reports_by_user', $data);
			}

			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}

		public function o2concentrator_follow_up()
		{
			$this->templating_library->set('title','Reports');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav');

			// DME User Access/Restriction
			if ($this->session->userdata('account_type') != 'dispatch' && $this->session->userdata('account_type') != 'customer_service' && $this->session->userdata('account_type') != 'hospice_user') {
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
			}

			$this->templating_library->set_view('common/footer','common/footer');
			$this->templating_library->set_view('common/foot','common/foot');
		}

		public function get_o2_concentrator_follow_up_date($hospiceID,$from_date,$to_date)
		{
			$response_data = array(
				"data" => array(),
				"draw" => 1,
				"recordsFiltered" => 0,
				"recordsTotal" => 0
			);

			if(empty($from_date))
			{
				$from_date = date('Y-m-01');

			}
			if(empty($to_date))
			{
				$to_date = date('Y-m-t');
			}

			if($this->input->is_ajax_request())
			{
				$datatable = $this->input->get();
				$start = $datatable['start'];
				$limit = $datatable['length'];
				$filters = array(
					"search_item_fields_o2_follow_up" => $datatable['search']['value']
				);

				$column = array(
					"order_by_customer_name",
					"order_by_due_date"
				);
				$filters[$column[$datatable["order"][0]["column"]]] = $datatable["order"][0]["dir"];
				$result = $this->mreport->get_o2_concentrator_follow_up_list_v2($filters,$from_date,$to_date,$this->session->userdata('user_location'),$start,$limit);

				if($result['totalCount']>0)
				{
					foreach ($result['result'] as $key => $value)
					{
						$latest_pickup_activity = $this->mreport->get_latest_pickup_activity($value['patientID']);

						if(empty($latest_pickup_activity) || $latest_pickup_activity['pickup_sub'] == "not needed")
						{
							if(empty($latest_pickup_activity))
							{
								$value['customer_name_data'] = '<a class="active_patient hidden-print"'.
																	'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['ordered_by']).'"'.
												                    'target="_blank">'.
								        								''.$value['p_lname'].", ".$value['p_fname'].''.
																'</a>'.
																'<span class="active_patient visible-print-block">'.
																	''.$value['p_lname'].', '.$value['p_fname'].''.
																'</span>';
							    $value['due_date_data'] = '<span class="active_patient hidden-print">'.
						 										'<i class="fa fa-calendar follow_up_date_calendar"'.
											                    'data-follow-up-id="'.$value['follow_up_id'].'"'.
											                    'aria-hidden="true"></i> &nbsp;'.
							        								''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
															'</span>'.
															'<span class="active_patient visible-print-block">'.
																''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
															'</span>';
							}
							else
							{
								$patient_orders = check_if_all_pickups_v3($value['patientID']);

								$has_delivery = in_multiarray(1, $patient_orders, "activity_typeid");
								$has_exchange = in_multiarray(3, $patient_orders, "activity_typeid");
								$has_ptmove   = in_multiarray(4, $patient_orders, "activity_typeid");
								$has_respite  = in_multiarray(5, $patient_orders, "activity_typeid");

								$disable_button = "";

								if($has_delivery)
								{
									$value['customer_name_data'] = '<a class="active_patient hidden-print"'.
																		'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['ordered_by']).'"'.
																		'target="_blank">'.
																			''.$value['p_lname'].", ".$value['p_fname'].''.
																	'</a>'.
																	'<span class="active_patient visible-print-block">'.
																		''.$value['p_lname'].', '.$value['p_fname'].''.
																	'</span>';
									$value['due_date_data'] = '<span class="active_patient hidden-print">'.
																	'<i class="fa fa-calendar follow_up_date_calendar"'.
																	'data-follow-up-id="'.$value['follow_up_id'].'"'.
																	'aria-hidden="true"></i> &nbsp;'.
																		''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																'</span>'.
																'<span class="active_patient visible-print-block">'.
																	''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																'</span>';
								}
								else if($has_exchange)
								{
									$value['customer_name_data'] = '<a class="active_patient hidden-print"'.
																		'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['ordered_by']).'"'.
																		'target="_blank">'.
																			''.$value['p_lname'].', '.$value['p_fname'].''.
																	'</a>'.
																	'<span class="active_patient visible-print-block">'.
																		''.$value['p_lname'].', '.$value['p_fname'].''.
																	'</span>';
									$value['due_date_data'] = '<span class="active_patient hidden-print">'.
																	'<i class="fa fa-calendar follow_up_date_calendar"'.
																	'data-follow-up-id="'.$value['follow_up_id'].'"'.
																	'aria-hidden="true"></i> &nbsp;'.
																		''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																'</span>'.
																'<span class="active_patient visible-print-block">'.
																	''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																'</span>';
								}
								else if($has_ptmove)
								{
									$value['customer_name_data'] = '<a class="active_patient hidden-print"'.
																		'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['ordered_by']).'"'.
																		'target="_blank">'.
																			''.$value['p_lname'].', '.$value['p_fname'].''.
																	'</a>'.
																	'<span class="active_patient visible-print-block">'.
																		''.$value['p_lname'].', '.$value['p_fname'].''.
																	'</span>';
									$value['due_date_data'] = '<span class="active_patient hidden-print">'.
																	'<i class="fa fa-calendar follow_up_date_calendar"'.
																	'data-follow-up-id="'.$value['follow_up_id'].'"'.
																	'aria-hidden="true"></i> &nbsp;'.
																		''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																'</span>'.
																'<span class="active_patient visible-print-block">'.
																	''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																'</span>';
								}
								else if($has_respite)
								{
									$value['customer_name_data'] = '<a class="active_patient hidden-print"'.
																		'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['ordered_by']).'"'.
																		'target="_blank">'.
																			''.$value['p_lname'].', '.$value['p_fname'].''.
																	'</a>'.
																	'<span class="active_patient visible-print-block">'.
																		''.$value['p_lname'].', '.$value['p_fname'].''.
																	'</span>';
									$value['due_date_data'] = '<span class="active_patient hidden-print">'.
																	'<i class="fa fa-calendar follow_up_date_calendar"'.
																	'data-follow-up-id="'.$value['follow_up_id'].'"'.
																	'aria-hidden="true"></i> &nbsp;'.
																		''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																'</span>'.
																'<span class="active_patient visible-print-block">'.
																	''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																'</span>';
								}
								else
								{
									$patient_capped_noncapped_orders = get_customer_ordered_capped_non_capped_items($value['patientID']);
									if(empty($patient_capped_noncapped_orders))
									{
										$patient_disposable_orders = get_customer_ordered_disposable_items($value['patientID']);
										if(!empty($patient_disposable_orders))
										{
											$value['customer_name_data'] = '<a class="active_patient hidden-print"'.
																				'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['ordered_by']).'"'.
																				'target="_blank">'.
																					''.$value['p_lname'].', '.$value['p_fname'].''.
																			'</a>'.
																			'<span class="active_patient visible-print-block">'.
																				''.$value['p_lname'].', '.$value['p_fname'].''.
																			'</span>';
											$value['due_date_data'] = '<span class="active_patient hidden-print">'.
																			'<i class="fa fa-calendar follow_up_date_calendar"'.
																			'data-follow-up-id="'.$value['follow_up_id'].'"'.
																			'aria-hidden="true"></i> &nbsp;'.
																				''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																		'</span>'.
																		'<span class="active_patient visible-print-block">'.
																			''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																		'</span>';
										}
										else
										{
											$value['customer_name_data'] = '<a class="inactive_patient hidden-print"'.
																				'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['ordered_by']).'"'.
																				'target="_blank">'.
																					''.$value['p_lname'].', '.$value['p_fname'].''.
																			'</a>'.
																			'<span class="inactive_patient visible-print-block">'.
																				''.$value['p_lname'].', '.$value['p_fname'].''.
																			'</span>';
											$value['due_date_data'] = '<span class="inactive_patient hidden-print">'.
																			'<i class="fa fa-calendar follow_up_date_calendar_inactive"'.
																			'data-follow-up-id="'.$value['follow_up_id'].'"'.
																			'aria-hidden="true"></i> &nbsp;'.
																				''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																		'</span>'.
																		'<span class="inactive_patient visible-print-block">'.
																			''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																		'</span>';
										}
									}
									else
									{
										$value['customer_name_data'] = '<a class="inactive_patient hidden-print"'.
																			'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['ordered_by']).'"'.
																			'target="_blank">'.
																				''.$value['p_lname'].', '.$value['p_fname'].''.
																		'</a>'.
																		'<span class="inactive_patient visible-print-block">'.
																			''.$value['p_lname'].', '.$value['p_fname'].''.
																		'</span>';
										$value['due_date_data'] = '<span class="inactive_patient hidden-print">'.
																		'<i class="fa fa-calendar follow_up_date_calendar_inactive"'.
																		'data-follow-up-id="'.$value['follow_up_id'].'"'.
																		'aria-hidden="true"></i> &nbsp;'.
																			''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																	'</span>'.
																	'<span class="inactive_patient visible-print-block">'.
																		''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
																	'</span>';
									}
								}
							}
						}
						else
						{
							$latest_patient_order = $this->mreport->get_latest_patient_order_status($value['patientID']);
							if($latest_patient_order['order_uniqueID'] == $latest_pickup_activity['order_uniqueID'])
							{
								$value['customer_name_data'] = '<a class="inactive_patient hidden-print"'.
																	'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['ordered_by']).'"'.
												                    'target="_blank">'.
								        								''.$value['p_lname'].', '.$value['p_fname'].''.
																'</a>'.
																'<span class="inactive_patient visible-print-block">'.
																	''.$value['p_lname'].', '.$value['p_fname'].''.
																'</span>';
							    $value['due_date_data'] = '<span class="inactive_patient hidden-print">'.
						 										'<i class="fa fa-calendar follow_up_date_calendar_inactive"'.
											                    'data-follow-up-id="'.$value['follow_up_id'].'"'.
											                    'aria-hidden="true"></i> &nbsp;'.
							        								''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
															'</span>'.
															'<span class="inactive_patient visible-print-block">'.
																''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
															'</span>';
							}
							else
							{
								$value['customer_name_data'] = '<a class="active_patient hidden-print"'.
																	'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['ordered_by']).'"'.
												                    'target="_blank">'.
								        								''.$value['p_lname'].', '.$value['p_fname'].''.
																'</a>'.
																'<span class="active_patient visible-print-block">'.
																	''.$value['p_lname'].', '.$value['p_fname'].''.
																'</span>';
							    $value['due_date_data'] = '<span class="active_patient hidden-print">'.
						 										'<i class="fa fa-calendar follow_up_date_calendar"'.
											                    'data-follow-up-id="'.$value['follow_up_id'].'"'.
											                    'aria-hidden="true"></i> &nbsp;'.
							        								''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
															'</span>'.
															'<span class="active_patient visible-print-block">'.
																''.date("m/d/Y", strtotime($value['follow_up_date'])).''.
															'</span>';
							}
						}

						$response_data['data'][] = $value;
					}
				}

				$response_data['draw'] = $datatable['draw'];
				$response_data['recordsFiltered'] = $result['totalCount'];
				$response_data['recordsTotal'] = $result['totalCount'];
			}
			echo json_encode($response_data);
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

		public function high_cost_customers($hospice_id) {
			$wheelchair = array();
			$high_cost_customers = array();		
			// $service_date_from = "2020-07-01";
			// $service_date_to = "2020-07-31";
			$service_date_from = date('Y-m-01');
			$service_date_to = date('Y-m-t', strtotime($service_date_from));

			$patient_list = $this->mreport->get_all_customers_current_month($hospice_id, 0, -1, $service_date_from, $service_date_to);

			$least_value = array(
				'index' => -1,
				'value' => 0
			);
			foreach($patient_list['result'] as $value_outer) {
				$temp = $this->mreport->get_category_total_v2($value_outer['patientID'], $hospice_id, $service_date_from, $service_date_to);

				$total = 0;
				
				foreach($temp as $cus_value) {
					if($cus_value['equip_is_package'] == 0 && $cus_value['is_package'] == 1) {
						continue;
					}


					// if ($value_outer['patientID'] == 29157) {
					// 	print_me($value);
					// }


					

					if($cus_value['categoryID'] == 2) {
						
						$your_date = strtotime($cus_value['actual_order_date']);
						$your_date_v2 = new DateTime($cus_value['actual_order_date']);
						$new_pickupdate = strtotime(date("Y-m-t", strtotime($cus_value['actual_order_date'])));
						$new_pickupdate_v2 = new DateTime(date("Y-m-t", strtotime($cus_value['actual_order_date'])));
						$temporary_service_date_to = date("Y-m-t", strtotime($service_date_from));
						$isSummaryPickupDate = false;

						if($cus_value['summary_pickup_date'] == "0000-00-00") {
							// $now = time();
							$current_date = date('Y-m-d');
							$now_v2 = new DateTime($current_date);
						} else {
							if ($cus_value['pickup_discharge_date'] !== '0000-00-00' && $cus_value['pickup_discharge_date'] !== null) {
								$now = strtotime($cus_value['pickup_discharge_date']);
								$now_v2 = new DateTime($cus_value['pickup_discharge_date']);
							} else {
								$now = strtotime($cus_value['summary_pickup_date']);
								$now_v2 = new DateTime($cus_value['summary_pickup_date']);
							}
							$isSummaryPickupDate = true;
						}

						// ((date("Y", strtotime($temporary_service_date_to)) > date("Y", $your_date)) && $isSummaryPickupDate == false) ||
						if( (((date("Y", strtotime($temporary_service_date_to)) == date("Y", $your_date)) && (date("m", strtotime($temporary_service_date_to)) < date("m", $your_date))) && $isSummaryPickupDate == false) ) {
							$now = $new_pickupdate;
							$now_v2 = $new_pickupdate_v2;
							$datediff = $now_v2->diff($your_date_v2)->format('%a');
							// $datediff = $now - $your_date;
							// if ($value_outer['patientID'] == 29157) {
							// 	echo '--------'.$datediff;
							// }
						} else {
							if(date("m", strtotime($temporary_service_date_to)) == date("m", $your_date)) {
								$temponewdate = $your_date;
								$temponewdate_v2 = $your_date_v2;
								$datediff = $now_v2->diff($temponewdate_v2)->format('%a');
							} else {
								$temponewdate = strtotime(date("Y-m-01"));
								$temponewdate_v2 = new DateTime(date("Y-m-01"));
								$datediff = $now_v2->diff($temponewdate_v2)->format('%a') + 1;
							}
							// $datediff = $now - $temponewdate;
							// if ($value_outer['patientID'] == 29157) {
							// 	echo '+++++++++'.$datediff;
							// }
						}

						$rounddatediff = $datediff;
						// $rounddatediff = round($datediff / (60 * 60 * 24)) + 1;
						// if ($value_outer['patientID'] == 29157) {
						// 	print_me($datediff);
						// 	print_me($rounddatediff);
						// }
						
						if ($rounddatediff == 0) {
							$rounddatediff = 1;
						}

						if($cus_value['equipmentID'] != 49 && $cus_value['equipmentID'] != 64 && $cus_value['equipmentID'] != 32 && $cus_value['equipmentID'] != 29 && $cus_value['daily_rate'] != 344 && $cus_value['equipmentID'] == 343) {
							if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
								$rounddatediff = 1;
							}
						}
						// if($value['daily_rate'] == 0 || $value['daily_rate'] == null) {
						// 	$rounddatediff = 1;
						// }

						if($cus_value['equipmentID'] == 176) {
							$rounddatediff = 1;
						}

						$dailratetemporary = 0;
						if($cus_value['daily_rate'] == 0 || $cus_value['daily_rate'] == null) {
							$total += $cus_value['monthly_rate'];
						} else {
							$temptotaldailyrate = $rounddatediff * $cus_value['daily_rate'];
							if($temptotaldailyrate > $cus_value['monthly_rate']) {
								if($cus_value['monthly_rate'] == 0 || $cus_value['monthly_rate'] == null) {
									$total += $temptotaldailyrate;
								} else {
									$total += $cus_value['monthly_rate'];
								}
							} else {
								$total += $temptotaldailyrate;
							}
							// if ($value_outer['patientID'] == 29157) {
							// 	print_me($rounddatediff);
							// 	print_me($value['daily_rate']);
							// 	print_me($temptotaldailyrate);
							// }
						}

						// Get Full Description - Start
						$summary = $cus_value;
						$summary['item_description_data'] = "";
						if ($cus_value['equipmentID'] == 343 || $cus_value['equipmentID'] == 334 || $cus_value['equipmentID'] == 32 || $cus_value['equipmentID'] == 64 || $cus_value['equipmentID'] == 49) {
							if($this->session->userdata('account_type') == 'dme_admin' || $this->session->userdata('account_type') == 'dme_user')
							{
								if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								else
								{
									//*** DELIVERY not CONFIRMED yet dri mo display ang item ***//
									//check if naay sub equipment using equipment id, work uniqueId
									$subequipment_id = get_subequipment_id($summary['equipmentID']);

									//gets all the id's under the order
									if($subequipment_id)
									{
										$count = 0;
										$patient_lift_sling_count = 0;
										$high_low_full_electric_hospital_bed_count = 0;
										$equipment_count = 0;
										$my_count_sign = 0;
										$my_first_sign = 0;
										$my_second_sign = 0;

										foreach ($subequipment_id as $key) {
											$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);

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
												if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
												{
													$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
												}
												//Hi-Low Full Electric Hospital Bed equipment
												else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
												{
													$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
												}
												//Patient Lift with Sling
												else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
												{
													$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
												}
												//Patient Lift Electric with Sling
												else if($summary['equipmentID'] == 353)
												{
													$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
												}
												//Patient Lift Sling
												else if($summary['equipmentID'] == 196)
												{
													$summary['item_description_data'] = $key['key_desc'];
												}
												//(54 & 17) Geri Chair || (66 & 39) Shower Chair
												else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
												{
													$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
												}
												// Oxygen E Portable System && Oxygen Liquid Portable
												else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
												{
													$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
													$summary['item_description_data'] = $summary['key_desc'];
												break;
												}
												//Oxygen Cylinder Rack
												else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
												{
													$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
													break;
												}
												//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
												else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
												{
													if($my_count_sign == 0)
													{
														//wheelchair & wheelchair reclining
														if($count == 1)
														{
															$temp_item_description = "";
															$temp_item_description2 = "";

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
															$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
														}
														else
														{
															$temp_item_description2 = " With ".$key['key_desc'];
														}
														$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
													}
													else
													{
														$temp_item_description = $summary['key_desc'];
														if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
														{
															$temp_item_description2 = " With Elevating Legrests";
														}
														else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
														{
															$temp_item_description2 = " With Footrests";
														}
														$initial_temp_item_description = '20" Wide';
														$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
														break;
													}
												}
												else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
												{
													if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
													{
														$key['key_desc'] = '17" Narrow';
													}
													else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
													{
														$key['key_desc'] = '19" Standard';
													}
													$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
													$summary['item_description_data'] = $temp_item_description;
												}
												else if($summary['equipmentID'] == 30)
												{
													if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
													{
														if($count == 3)
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$temp_item_description = " With ".$key['key_desc'];
															$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
														}
													}
													else
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
													}
												}
												//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
												else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
												{
													$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
													if(strlen($samp) > 30)
													{
														$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
													}
													else
													{
														$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
													}

													$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
													$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
													break;
												}
												else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
												{
													$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
													if($count == 1)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
													}
												}
												else if($summary['equipmentID'] == 282)
												{

												}
												//equipments that has no subequipment but gets inside the $value if statement
												else if($summary['equipmentID'] == 14)
												{
													$summary['item_description_data'] = $summary['key_desc'];
												}
												else
												{
													if($summary['categoryID'] == 1)
													{
														$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
														if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'];
															break;
														}
														else if($non_capped_copy['noncapped_reference'] == 14)
														{
															$summary['item_description_data'] = $summary['key_desc'];
														}
														else if($non_capped_copy['noncapped_reference'] == 282)
														{
															$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
															break;
														}
														else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
														{
															$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
														}
														else if($non_capped_copy['noncapped_reference'] == 353)
														{
															$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
														}
														else
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'];
														}
													}
													else
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
													}
												}
											} //end of $value
											//for Oxygen E cylinder do not remove as it will cause errors
											else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
											{
												break;
											}
											else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
											{

											}
											else if($summary['equipmentID'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
											else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											}
											//equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
											else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
											//for equipments with subequipment but does not fall in $value
											else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
											{
												if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
												{
													$patient_lift_sling_count++;
													if($patient_lift_sling_count == 6)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
													}
												}
												else if($summary['equipmentID'] == 398)
												{
													if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
													{
														$high_low_full_electric_hospital_bed_count++;
														if($high_low_full_electric_hospital_bed_count == 2)
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'];
														}
													}
												}
												// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
												else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
												{
													$equipment_count++;
													if($equipment_count == 2)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
													}
												}
											}
											else
											{
												if($summary['categoryID'] == 1)
												{
													$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
													if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
														break;
													}
													else if($non_capped_copy['noncapped_reference'] == 14)
													{
														$summary['item_description_data'] = $summary['key_desc'];
													}
													else
													{

													}
												}
												else
												{
													$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
													$summary['item_description_data'] = $summary['key_desc'];
												}
											}
										}
									}
									else
									{
										if($summary['equipmentID'] == 181 || $summary['equipmentID'] == 182)
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
										else
										{
											$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
											$summary['item_description_data'] = $summary['key_desc'];
										}
									}
								}
							}
							else
							{
								$summary['item_description_data'] = "";
								if($summary['equipmentID'] == 316 || $summary['equipmentID'] == 325 || $summary['equipmentID'] == 334 || $summary['equipmentID'] == 343)
								{
									$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
									$summary['item_description_data'] = $summary['key_desc'];
								}
								else
								{
									//check if the item has sub-equipments using equipment id
									$subequipment_id = get_subequipment_id($summary['equipmentID']);
									//gets all the id's under the order
									if($subequipment_id)
									{
										$count = 0;
										$patient_lift_sling_count = 0;
										$high_low_full_electric_hospital_bed_count = 0;
										$equipment_count = 0;
										$my_count_sign = 0;
										$my_first_sign = 0;
										$my_second_sign = 0;
										foreach ($subequipment_id as $key) {
											$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);
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
												//equipment full electric hospital bed
												if($summary['equipmentID'] == 55 || $summary['equipmentID'] == 20)
												{
													$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
												}
												//Hi-Low Full Electric Hospital Bed equipment
												else if($summary['equipmentID'] == 19 || $summary['equipmentID'] == 398)
												{
													$summary['item_description_data'] = $summary['key_desc']." With ".$key['key_desc'];
												}
												//Patient Lift with Sling
												else if($summary['equipmentID'] == 56 || $summary['equipmentID'] == 21)
												{
													$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
												}
												//Patient Lift Electric with Sling
												else if($summary['equipmentID'] == 353)
												{
													$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
												}
												//Patient Lift Sling
												else if($summary['equipmentID'] == 196)
												{
													$summary['item_description_data'] = $key['key_desc'];
												}
												//(54 & 17) Geri Chair || (66 & 39) Shower Chair
												else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 39)
												{
													$summary['item_description_data'] = $summary['key_desc']." ".$key['key_desc'];
												}
												// Oxygen E Portable System && Oxygen Liquid Portable
												else if($summary['equipmentID'] == 174 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179)
												{
													$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
													$summary['item_description_data'] = $summary['key_desc'];
													break;
												}
												//Oxygen Cylinder Rack
												else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
												{
													$summary['item_description_data'] = "Oxygen ".$key['key_desc'];
													break;
												}
												//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
												else if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 64)
												{
													if($my_count_sign == 0)
													{
														//wheelchair & wheelchair reclining
														if($count == 1)
														{
															$temp_item_description = "";
															$temp_item_description2 = "";

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
															$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
														}
														else
														{
															$temp_item_description2 = " With ".$key['key_desc'];
														}
														$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
													}
													else
													{
														$temp_item_description = $summary['key_desc'];
														if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
														{
															$temp_item_description2 = " With Elevating Legrests";
														}
														else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
														{
															$temp_item_description2 = " With Footrests";
														}
														$initial_temp_item_description = '20" Wide';
														$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
														break;
													}
												}
												else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
												{
													if($key['equipmentID'] == 478 || $key['equipmentID'] == 480)
													{
														$key['key_desc'] = '17" Narrow';
													}
													else if($key['equipmentID'] == 479 || $key['equipmentID'] == 481)
													{
														$key['key_desc'] = '19" Standard';
													}
													$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
													$summary['item_description_data'] = $temp_item_description;
												}
												else if($summary['equipmentID'] == 30)
												{
													if(date("Y-m-d", $summary['uniqueID']) >= "2016-05-24")
													{
														if($count == 3)
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$temp_item_description = " With ".$key['key_desc'];
															$summary['item_description_data'] = $summary['key_desc']." ".$temp_item_description;
														}
													}
													else
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
													}
												}
												//equipments affected with the changes above that also has subequipments (added to fix problem in repetition and blank in item description)
												else if($summary['equipmentID'] == 306 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 32  || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 16 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 4 || $summary['equipmentID'] == 36)
												{
													$samp =  get_misc_item_description($summary['equipmentID'],$summary['uniqueID']);
													if(strlen($samp) > 30)
													{
														$temp_item_description = "<span style='font-weight:400;color:#000;'>".substr($samp,0,30)."...</span>";
													}
													else
													{
														$temp_item_description = "<span style='font-weight:400;color:#000;'>".$samp."</span>";
													}

													$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
													$summary['item_description_data'] = $summary['key_desc'].''.'<br />'.''.$temp_item_description;
													break;
												}
												else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
												{
													$samp_conserving_device =  get_oxygen_conserving_device($summary['equipmentID'],$summary['uniqueID']);
													if($count == 1)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc']." ".$samp_conserving_device;
													}
												}
												else if($summary['equipmentID'] == 282)
												{

												}
												//equipments that has no subequipment but gets inside the $value if statement
												else if($summary['equipmentID'] == 14)
												{
													$summary['item_description_data'] = $summary['key_desc'];
												}
												else
												{
													if($summary['categoryID'] == 1)
													{
														$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
														if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 286)
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'];
															break;
														}
														else if($non_capped_copy['noncapped_reference'] == 14)
														{
															$summary['item_description_data'] = $summary['key_desc'];
														}
														else if($non_capped_copy['noncapped_reference'] == 282)
														{
															$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
															break;
														}
														else if($non_capped_copy['noncapped_reference'] == 21 || $non_capped_copy['noncapped_reference'] == 56)
														{
															$summary['item_description_data'] = "Patient Lift With ".$key['key_desc'];
														}
														else if($non_capped_copy['noncapped_reference'] == 353)
														{
															$summary['item_description_data'] = "Patient Lift Electric With ".$key['key_desc'];
														}
														else
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'];
														}
													}
													else
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
													}
												}
											} //end of $value
											//for Oxygen E cylinder do not remove as it will cause errors
											else if($summary['equipmentID'] == 62 || $summary['equipmentID'] == 31)
											{
												break;
											}
											else if($summary['equipmentID'] == 32 || $summary['equipmentID'] == 393)
											{

											}
											else if($summary['equipmentID'] == 282)
											{
												$samp_hospital_bed_extra_long =  get_hospital_bed_extra_long($summary['equipmentID'],$summary['uniqueID']);
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc']." With ".$samp_hospital_bed_extra_long;
												break;
											}
											//equipments affected with the changes above that also has subequipments and is ordered together with oxygen concentrator (added to fix problem in repetition and blank in item description)
											else if ($summary['equipmentID'] == 10 || $summary['equipmentID'] == 36 || $summary['equipmentID'] == 31 || $summary['equipmentID'] == 32 || $summary['equipmentID'] == 393 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 286 || $summary['equipmentID'] == 62 || $summary['equipmentID'] == 313 || $summary['equipmentID'] == 309 || $summary['equipmentID'] == 306 || $summary['equipmentID'] == 4)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
												break;
											} //equipments affected with the changes above that has no subequipments (added to fix problem in repetition and blank in item description)
											else if($summary['equipmentID'] == 11 || $summary['equipmentID'] == 178 || $summary['equipmentID'] == 9 || $summary['equipmentID'] == 149)
											{
												$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
												$summary['item_description_data'] = $summary['key_desc'];
											}
											//for equipments with subequipment but does not fall in $value
											else if($summary['equipmentID'] == 54 || $summary['equipmentID'] == 17 || $summary['equipmentID'] == 174 || $summary['equipmentID'] == 398 || $summary['equipmentID'] == 282 || $summary['equipmentID'] == 196 || $summary['equipmentID'] == 353 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 176 || $summary['equipmentID'] == 179 ||  $summary['equipmentID'] == 30 || $summary['equipmentID'] == 40 || $summary['equipmentID'] == 67 || $summary['equipmentID'] == 39 || $summary['equipmentID'] == 66 || $summary['equipmentID'] == 19 || $summary['equipmentID'] == 269 || $summary['equipmentID'] == 49 || $summary['equipmentID'] == 20 || $summary['equipmentID'] == 55 || $summary['equipmentID'] == 71 || $summary['equipmentID'] == 69 || $summary['equipmentID'] == 48 || $summary['equipmentID'] == 64)
											{
												if($summary['equipmentID'] == 196 || $summary['equipmentID'] == 56 || $summary['equipmentID'] == 21 || $summary['equipmentID'] == 353)
												{
													$patient_lift_sling_count++;
													if($patient_lift_sling_count == 6)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
													}
												}
												else if($summary['equipmentID'] == 398)
												{
													if(date("Y-m-d", $summary['uniqueID']) <= "2016-06-21")
													{
														$high_low_full_electric_hospital_bed_count++;
														if($high_low_full_electric_hospital_bed_count == 2)
														{
															$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
															$summary['item_description_data'] = $summary['key_desc'];
														}
													}
												}
												// Display the Equipment Name without any equipment options. Can be used in other equipment with the same case.
												else if($summary['equipmentID'] == 69 || $summary['equipmentID'] == 48)
												{
													$equipment_count++;
													if($equipment_count == 2)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
													}
												}
											}
											else
											{
												if($summary['categoryID'] == 1)
												{
													$non_capped_copy = get_non_capped_copy($summary['equipmentID']);
													if($non_capped_copy['noncapped_reference'] == 4 || $non_capped_copy['noncapped_reference'] == 9 || $non_capped_copy['noncapped_reference'] == 14 || $non_capped_copy['noncapped_reference'] == 16 || $non_capped_copy['noncapped_reference'] == 30 || $non_capped_copy['noncapped_reference'] == 36 || $non_capped_copy['noncapped_reference'] == 179 || $non_capped_copy['noncapped_reference'] == 14)
													{
														$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
														$summary['item_description_data'] = $summary['key_desc'];
														break;
													}
													else if($non_capped_copy['noncapped_reference'] == 14)
													{
														$summary['item_description_data'] = $summary['key_desc'];
													}
													else
													{

													}
												}
												else
												{
													$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
													$summary['item_description_data'] = $summary['key_desc'];
												}
											}
										}
									}
									else
									{
										$temp_data_content = "<i class='fa fa-spin fa-circle-o-notch spinner-green'></i>";
										$summary['item_description_data'] = $summary['key_desc'];
									}
								}
							}
							$cus_value['key_desc'] = $summary['item_description_data'];
						}
						
						
						// Get Full Description - End

						if($cus_value['equipmentID'] == 343)
						{
							$getassignedequipmentid = $this->equipment_model->get_assigned_equipment_details($hospice_id, 29);
							$subequipdetails = array();
							$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "10_liters");

							if ($subequipdetails['daily_rate'] == 0 || $subequipdetails['daily_rate'] == null) {
								$total += $subequipdetails['monthly_rate'];
							} else {
								$temptotaldailyrate = $rounddatediff * $subequipdetails['daily_rate'];

								if($temptotaldailyrate > $subequipdetails['monthly_rate']) {
									if($subequipdetails['monthly_rate'] == 0 || $subequipdetails['monthly_rate'] == null) {
										$total += $temptotaldailyrate;
									} else {
										$total += $subequipdetails['monthly_rate'];
									}
								} else {
									$total += $temptotaldailyrate;
								}
							}
						} 
						else if ($cus_value['equipmentID'] == 334) 
						{
							$getassignedequipmentid = $this->equipment_model->get_assigned_equipment_details($hospice_id, 29);
							$subequipdetails = array();
							$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($getassignedequipmentid['ID'], "5_liters");
							
							if ($subequipdetails['daily_rate'] == 0 || $subequipdetails['daily_rate'] == null) {
								$total += $subequipdetails['monthly_rate'];
							} else {
								$temptotaldailyrate = $rounddatediff * $subequipdetails['daily_rate'];

								if($temptotaldailyrate > $subequipdetails['monthly_rate']) {
									if($subequipdetails['monthly_rate'] == 0 || $subequipdetails['monthly_rate'] == null) {
										$total += $temptotaldailyrate;
									} else {
										$total += $subequipdetails['monthly_rate'];
									}
								} else {
									$total += $temptotaldailyrate;
								}
							}
						} else if ($cus_value['equipmentID'] == 32) 
						{
							// $getassignedequipmentid = $this->equipment_model->get_assigned_equipment_details($hospice_id, 32);
							$subequipdetails = array();
							if(strpos($cus_value['key_desc'], "E Cylinder 6 Rack") !== false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "e_cylinder_6_rack");
							}
							if(strpos($cus_value['key_desc'], "E Cylinder 12 Rack") !== false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "e_cylinder_12_rack");
							}
							if(strpos($cus_value['key_desc'], "M6 Cylinder 6 Rack") != false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "m6_cylinder_6_rack");
							}
							if(strpos($cus_value['key_desc'], "M6 Cylinder 12 Rack") !== false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "m6_cylinder_12_rack");
							}
							
							if ($subequipdetails['daily_rate'] == 0 || $subequipdetails['daily_rate'] == null) {
								$total += $subequipdetails['monthly_rate'];
							} else {
								$temptotaldailyrate = $rounddatediff * $subequipdetails['daily_rate'];

								if($temptotaldailyrate > $subequipdetails['monthly_rate']) {
									if($subequipdetails['monthly_rate'] == 0 || $subequipdetails['monthly_rate'] == null) {
										$total += $temptotaldailyrate;
									} else {
										$total += $subequipdetails['monthly_rate'];
									}
								} else {
									$total += $temptotaldailyrate;
								}
							}
						} else if ($cus_value['equipmentID'] == 49) 
						{
							$subequipdetails = array();
							if(strpos($cus_value['key_desc'], "16") !== false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "16_inch");
							}
							if(strpos($cus_value['key_desc'], "18") !== false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "18_inch");
							}
							if(strpos($cus_value['key_desc'], "20") !== false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "20_inch");
							}
							if(strpos($cus_value['key_desc'], "22") !== false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "22_inch");
							}
							if(strpos($cus_value['key_desc'], "24") !== false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "24_inch");
							}

							if ($subequipdetails['daily_rate'] == 0 || $subequipdetails['daily_rate'] == null) {
								$total += $subequipdetails['monthly_rate'];
							} else {
								$temptotaldailyrate = $rounddatediff * $subequipdetails['daily_rate'];

								if($temptotaldailyrate > $subequipdetails['monthly_rate']) {
									if($subequipdetails['monthly_rate'] == 0 || $subequipdetails['monthly_rate'] == null) {
										$total += $temptotaldailyrate;
									} else {
										$total += $subequipdetails['monthly_rate'];
									}
								} else {
									$total += $temptotaldailyrate;
								}
							}
						} else if ($cus_value['equipmentID'] == 64) 
						{
							$subequipdetails = array();
							if(strpos($cus_value['key_desc'], "16") !== false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "16_inch");
							}
							if(strpos($cus_value['key_desc'], "18") !== false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "18_inch");
							}
							if(strpos($cus_value['key_desc'], "20") !== false){
								$subequipdetails = $this->equipment_model->get_sub_equipment_rates_by_keyname($cus_value['assigned_equipmentID'], "20_inch");
							}

							if ($subequipdetails['daily_rate'] == 0 || $subequipdetails['daily_rate'] == null) {
								$total += $subequipdetails['monthly_rate'];
							} else {
								$temptotaldailyrate = $rounddatediff * $subequipdetails['daily_rate'];

								if($temptotaldailyrate > $subequipdetails['monthly_rate']) {
									if($subequipdetails['monthly_rate'] == 0 || $subequipdetails['monthly_rate'] == null) {
										$total += $temptotaldailyrate;
									} else {
										$total += $subequipdetails['monthly_rate'];
									}
								} else {
									$total += $temptotaldailyrate;
								}
							}
						}
						// else if ($value['equipmentID'] == 49 || $value['equipmentID'] == 64 || $value['equipmentID'] == 32) 
						// {
						// 	// //check if naay sub equipment using equipment id, work uniqueId
						// 	// $subequipment_id = get_subequipment_id($summary['equipmentID']);

						// 	// //gets all the id's under the order
						// 	// if($subequipment_id)
						// 	// {
						// 	// 	$count = 0;
						// 	// 	$patient_lift_sling_count = 0;
						// 	// 	$high_low_full_electric_hospital_bed_count = 0;
						// 	// 	$equipment_count = 0;
						// 	// 	$my_count_sign = 0;
						// 	// 	$my_first_sign = 0;
						// 	// 	$my_second_sign = 0;

						// 	// 	foreach ($subequipment_id as $key) {
						// 	// 		$value = get_equal_subequipment_order($key['equipmentID'], $summary['uniqueID']);

						// 	// 		if($key['equipmentID'] == 84 || $key['equipmentID'] == 270)
						// 	// 		{
						// 	// 			if(empty($value))
						// 	// 			{
						// 	// 				$my_first_sign = 1;
						// 	// 			}
						// 	// 		}
						// 	// 		if($key['equipmentID'] == 85  || $key['equipmentID'] == 271)
						// 	// 		{
						// 	// 			if(empty($value))
						// 	// 			{
						// 	// 				$my_second_sign = 1;
						// 	// 			}
						// 	// 		}
						// 	// 		if($my_second_sign == 1 && $my_first_sign == 1)
						// 	// 		{
						// 	// 			$my_count_sign = 1;
						// 	// 		}
						// 	// 		if($value)
						// 	// 		{
						// 	// 			$count++;
									
						// 	// 			//(49 & 71) Wheelchair || (269 & 64) Wheelchair Reclining
						// 	// 			if($summary['equipmentID'] == 49 || $summary['equipmentID'] == 64)
						// 	// 			{
						// 	// 				if($my_count_sign == 0)
						// 	// 				{
						// 	// 					//wheelchair & wheelchair reclining
						// 	// 					if($count == 1)
						// 	// 					{
						// 	// 						$temp_item_description = "";
						// 	// 						$temp_item_description2 = "";

						// 	// 						if($key['equipmentID'] == 92 || $key['equipmentID'] == 124 || $key['equipmentID'] == 270 || $key['equipmentID'] == 84)
						// 	// 						{
						// 	// 							$value['item_description'] = '16" Narrow';
						// 	// 						}
						// 	// 						else if($key['equipmentID'] == 93 || $key['equipmentID'] == 125 || $key['equipmentID'] == 271 || $key['equipmentID'] == 85)
						// 	// 						{
						// 	// 							$value['item_description'] = '18" Standard';
						// 	// 						}
						// 	// 						else if($key['equipmentID'] == 94 || $key['equipmentID'] == 126 || $key['equipmentID'] == 391 || $key['equipmentID'] == 392)
						// 	// 						{
						// 	// 							$value['item_description'] = '20" Wide';
						// 	// 						}
						// 	// 						else if($key['equipmentID'] == 95 || $key['equipmentID'] == 127)
						// 	// 						{
						// 	// 							$value['item_description'] = '22" Extra Wide';
						// 	// 						}
						// 	// 						else if($key['equipmentID'] == 96 || $key['equipmentID'] == 128)
						// 	// 						{
						// 	// 							$value['item_description'] = '24" Bariatric';
						// 	// 						}
						// 	// 						$temp_item_description = $key['key_desc']." ".$summary['key_desc'];
						// 	// 					}
						// 	// 					else
						// 	// 					{
						// 	// 						$temp_item_description2 = " With ".$key['key_desc'];
						// 	// 					}
						// 	// 					$summary['item_description_data'] = $temp_item_description." ".$temp_item_description2;
						// 	// 				}
						// 	// 				else
						// 	// 				{
						// 	// 					$temp_item_description = $summary['key_desc'];
						// 	// 					if($key['equipmentID'] == 86 || $key['equipmentID'] == 272)
						// 	// 					{
						// 	// 						$temp_item_description2 = " With Elevating Legrests";
						// 	// 					}
						// 	// 					else if($key['equipmentID'] == 87 || $key['equipmentID'] == 273)
						// 	// 					{
						// 	// 						$temp_item_description2 = " With Footrests";
						// 	// 					}
						// 	// 					$initial_temp_item_description = '20" Wide';
						// 	// 					$summary['item_description_data'] = $initial_temp_item_description." ".$temp_item_description." ".$temp_item_description2;
						// 	// 					break;
						// 	// 				}
						// 	// 			}
						// 	// 		} //end of $value
						// 	// 		else if($summary['equipmentID'] == 32)
						// 	// 		{

						// 	// 		}
						// 	// 		//for equipments with subequipment but does not fall in $value
						// 	// 		else if($summary['equipmentID'] == 49 )
						// 	// 		{

						// 	// 		}
						// 	// 	}
						// 	// }
						// }

					} else if($cus_value['categoryID'] == 3) {
						$quan = $cus_value['equipment_value'];
						if($cus_value['equipment_quantity'] !== "" && $cus_value['equipment_quantity'] !== null) {
							$quan = $cus_value['equipment_quantity'];
						}
						$total += $cus_value['purchase_price']*$quan;

					} else {
						$total += $cus_value['purchase_price'];
					}
				}

				if (count($temp) > 0) {
					$total += ($value_outer['patient_days'] * $value_outer['daily_rate']);
				}

				if ($total != 0) {

					if ($least_value['index'] == -1) {
						$least_value = array(
							'index' => 0,
							'value' => $total
						);
					} else {
						if (count($high_cost_customers) < 10) {
							if ($least_value['value'] > $total) {
								$least_value = array(
									'index' => count($high_cost_customers),
									'value' => $total
								);	
							}
						}
					}

					// START for listing the 10 high cost customers
					if (count($high_cost_customers) < 10) {
						if ($total != 0) {
							$high_cost_customers[] = array(
								'patientID'	=> $value_outer['patientID'],
								'p_fname'	=> $value_outer['p_fname'],
								'p_lname'	=> $value_outer['p_lname'],
								'medical_record_id'	=> $value_outer['medical_record_id'],
								'hospiceID'	=> $value_outer['hospiceID'],
								'total'	=> $total
							);
						}
					} else {
						if ($total != 0) {
							
							if ($least_value['value'] < $total) {
								$high_cost_customers[$least_value['index']] = array(
									'patientID'	=> $value_outer['patientID'],
									'p_fname'	=> $value_outer['p_fname'],
									'p_lname'	=> $value_outer['p_lname'],
									'medical_record_id'	=> $value_outer['medical_record_id'],
									'total'	=> $total
								);

								$least_value = array(
									'index' => -1,
									'value' => 0
								);
								for ($loop_key = 0; $loop_key < count($high_cost_customers); $loop_key++) {
									if ($least_value['index'] !== -1) {
										if ($least_value['value'] > $high_cost_customers[$loop_key]['total']) {
											$least_value = array(
												'index' => $loop_key,
												'value' => $high_cost_customers[$loop_key]['total']
											);	
										}
									} else {
										$least_value = array(
											'index' => $loop_key,
											'value' => $high_cost_customers[$loop_key]['total']
										);
									}
								}
							}

						}
					}
					// END for listing the 10 high cost customers
				}
				

				

				
			}

			// print_me($high_cost_customers);

		
			$data = array(
				"high_cost_customers" => $high_cost_customers,
				"wheelchair" => $wheelchair
			);
			$this->common->code 	= 0;
			$this->common->message 	= "High Cost Customers";
			$this->common->data 	= $data;
			$this->common->response(false);
		}



	}
?>
