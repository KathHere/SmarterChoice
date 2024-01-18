<?php
Class order extends Ci_Controller
{
	var $response_code = 1;//false or error default
	var $response_message = "";
	var $response_data = array();

	public function __construct()
	{
		parent::__construct();
		is_logged_in();

		date_default_timezone_set("America/Los_Angeles");
		$this->load->model("hospice_model");
		$this->load->model("order_model");
		$this->load->library('encryption');
	}

	public function update_enroute_orders_statuses()
	{
		$result = list_order_status_enroute();
		$data['orders'] = ($result) ? $result : FALSE;

		if(!empty($data['orders']))
		{
			foreach ($data['orders'] as $key => $value) {
				$data_to_save = array(
					'order_status'	=> "tobe_confirmed"
				);
				update_enroute_orders($data_to_save,$value['uniqueID'],$value['patientID']);
				update_enroute_orders_statuses($data_to_save,$value['uniqueID'],$value['patientID']);
			}
		}
	}

	public function update_scheduled_orders_today()
	{
		$current_date = date("Y-m-d");
		$result = list_orders_scheduled_today($current_date);
		$data['orders'] = ($result) ? $result : FALSE;

		if(!empty($data['orders']))
		{
			foreach ($data['orders'] as $key => $value) {
				$data_to_save = array(
					'order_status'	=> "active"
				);
				update_enroute_orders($data_to_save,$value['uniqueID'],$value['patientID']);
				update_enroute_orders_statuses($data_to_save,$value['uniqueID'],$value['patientID']);
			}
		}

		$result = list_order_status_rescheduled();
		$data['orders_list'] = ($result) ? $result : FALSE;

		if(!empty($data['orders_list']))
		{
			foreach ($data['orders_list'] as $key => $value) {
				$returned_status_id = get_status_id($value['uniqueID']);
                $returned_date = get_reschreschedule_onhold_date($returned_status_id['statusID']);

                if($current_date == $returned_date['date'])
                {
                	$data_to_save = array(
						'order_status'	=> "active"
					);
					update_enroute_orders($data_to_save,$value['uniqueID'],$value['patientID']);
					update_enroute_orders_statuses($data_to_save,$value['uniqueID'],$value['patientID']);
                }
			}
		}
	}

	public function update_patient_los()
	{
		$patients = $this->order_model->get_all_patients_v2(0,100);
		$list_active_patients = $this->order_model->list_active_patients_scripts();

		$data_to_be_updated = array();
		$data_to_be_updated_patient_days = array();
		foreach ($patients as $looped_patient) {
			$patient_first_order = get_patient_first_order($looped_patient['patientID']);
          	$returned_data = get_all_patient_pickup($looped_patient['patientID']);

            $patient_los = 1;
            $patient_days = 1;
            if(empty($returned_data))
	        {
	            $current_date = date("Y-m-d h:i:s");
	            $answer = strtotime($current_date)-strtotime($looped_patient['date_created']);
	            $answer_2 = $answer/86400;
	            $patient_los = $patient_los+floor($answer_2);

	            $month_created = date("m", strtotime($looped_patient['date_created']));
	            $current_month = date("m");
	            if(date("Y", strtotime($looped_patient['date_created'])) == date("Y"))
	            {
	            	if($current_month == $month_created)
		            {
		            	if($patient_first_order['actual_order_date'] != "0000-00-00")
		            	{
		            		$patient_days = (date("d") - date("d", strtotime($patient_first_order['actual_order_date'])));
		            		$patient_days += 1;
		            	}
		            	else
		            	{
		            		$patient_days = (date("d") - date("d", strtotime($looped_patient['date_created'])));
		            		$patient_days += 1;
		            	}
		            }
		            else if($current_month > $month_created)
		            {
		            	$patient_days = date("d");
		            }
	            }
	            else if(date("Y") > date("Y", strtotime($looped_patient['date_created'])))
	            {
	            	$patient_days = date("d");
	            }
	        }
	        else if(count($returned_data) == 1)
	        {
	        	if($returned_data[0]['pickup_sub'] != "not needed")
		        {
		        	$returned_query = check_order_after_all_pickup($returned_data[0]['orderID'], $returned_data[0]['uniqueID'], $returned_data[0]['patientID']);
	                if(!empty($returned_query))
	                {
	                    if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $returned_data[0]['pickup_date'])
	                	{
	                		$current_date = date("Y-m-d h:i:s");
		                    $answer = strtotime($current_date)-strtotime($looped_patient['date_created']);
		                    $answer_2 = $answer/86400;
		                    $patient_los = $patient_los+floor($answer_2);

		                    $month_created = date("m", strtotime($looped_patient['date_created']));
				            $current_month = date("m");
				            if(date("Y", strtotime($looped_patient['date_created'])) == date("Y"))
				            {
				            	if($current_month == $month_created)
					            {
					            	$patient_days = (date("d") - date("d", strtotime($looped_patient['date_created'])));
					            	$patient_days += 1;
					            }
					            else if($current_month > $month_created)
					            {
					            	$patient_days = date("d");
					            }
				            }
				            else if(date("Y") > date("Y", strtotime($looped_patient['date_created'])))
				            {
				            	$patient_days = date("d");
				            }
	                	}
	                	else
	                	{
	                		$answer = strtotime($returned_data[0]['pickup_date'])-strtotime($looped_patient['date_created']);
			                $answer_2 = $answer/86400;
			                $patient_los = $patient_los+floor($answer_2);

			                $month_created = date("m", strtotime($looped_patient['date_created']));
			            	$patient_last_month = date("m", strtotime($returned_data[0]['pickup_date']));
			                if(date("Y", strtotime($looped_patient['date_created'])) == date("Y", strtotime($returned_data[0]['pickup_date'])))
		            		{
		            			if($patient_last_month == $month_created)
					            {
					            	$patient_days = (date("d", strtotime($returned_data[0]['pickup_date'])) - date("d", strtotime($looped_patient['date_created'])));
					            	$patient_days += 1;
					            }
					            else if($patient_last_month > $month_created)
					            {
					            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
					            }
		            		}
		            		else if(date("Y", strtotime($returned_data[0]['pickup_date'])) > date("Y", strtotime($looped_patient['date_created'])))
				            {
				            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
				            }
	                	}
	                }
	                else
	                {
	                	$answer = strtotime($returned_data[0]['pickup_date'])-strtotime($looped_patient['date_created']);
		                $answer_2 = $answer/86400;
		                $patient_los = $patient_los+floor($answer_2);

		                $month_created = date("m", strtotime($looped_patient['date_created']));
			            $patient_last_month = date("m", strtotime($returned_data[0]['pickup_date']));
			            if(date("Y", strtotime($looped_patient['date_created'])) == date("Y", strtotime($returned_data[0]['pickup_date'])))
	            		{
	            			if($patient_last_month == $month_created)
				            {
				            	$active_patient_sign = 0;
				            	foreach ($list_active_patients as $looped_active_patient) {
				            		if($looped_active_patient['patientID'] == $looped_patient['patientID'])
				            		{
				            			$active_patient_sign = 1;
				            		}
				            	}
				            	if($active_patient_sign == 1)
				            	{
				            		$patient_days = date("d");
				            	}
				            	else
				            	{
				            		$patient_days = (date("d", strtotime($returned_data[0]['pickup_date'])) - date("d", strtotime($looped_patient['date_created'])));
				            		$patient_days += 1;
				            	}
				            }
				            else if($patient_last_month > $month_created)
				            {
				            	$active_patient_sign = 0;
				            	foreach ($list_active_patients as $looped_active_patient) {
				            		if($looped_active_patient['patientID'] == $looped_patient['patientID'])
				            		{
				            			$active_patient_sign = 1;
				            		}
				            	}
				            	if($active_patient_sign == 1)
				            	{
				            		$patient_days = date("d");
				            	}
				            	else
				            	{
				            		$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
				            	}
				            }
	            		}
	            		else if(date("Y", strtotime($returned_data[0]['pickup_date'])) > date("Y", strtotime($looped_patient['date_created'])))
			            {
			            	$active_patient_sign = 0;
			            	foreach ($list_active_patients as $looped_active_patient) {
			            		if($looped_active_patient['patientID'] == $looped_patient['patientID'])
			            		{
			            			$active_patient_sign = 1;
			            		}
			            	}
			            	if($active_patient_sign == 1)
			            	{
			            		$patient_days = date("d");
			            	}
			            	else
			            	{
			            		$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
			            	}
			            }
	                }
		        }
		        else
		        {
		        	$current_date = date("Y-m-d h:i:s");
	                $answer = strtotime($current_date)-strtotime($looped_patient['date_created']);
	                $answer_2 = $answer/86400;
	                $patient_los = $patient_los+floor($answer_2);

	                $month_created = date("m", strtotime($looped_patient['date_created']));
		            $current_month = date("m");
		            if(date("Y", strtotime($looped_patient['date_created'])) == date("Y"))
	            	{
	            		if($current_month == $month_created)
			            {
			            	$patient_days = (date("d") - date("d", strtotime($looped_patient['date_created'])));
			            	$patient_days += 1;
			            }
			            else if($current_month > $month_created)
			            {
			            	$patient_days = date("d");
			            }
	            	}
	            	else if(date("Y") > date("Y", strtotime($looped_patient['date_created'])))
		            {
		            	$patient_days = date("d");
		            }
		        }
	        }
	        else
	        {
	        	$pickup_all_sign = 0;
	        	$pickup_all_count_sign = 0;
	        	foreach ($returned_data as $value_first_loop){
	        		if($value_first_loop['pickup_date'] >= $patient_first_order['actual_order_date'])
	        		{
	        			if($value_first_loop['pickup_sub'] != "not needed")
		        		{
		        			$pickup_all_sign = 1;
		        			$pickup_all_count_sign++;
		        		}
	        		}
	        	}

	        	if($pickup_all_sign == 0)
	        	{
	        		$current_date = date("Y-m-d h:i:s");
	                $answer = strtotime($current_date)-strtotime($looped_patient['date_created']);
	                $answer_2 = $answer/86400;
	                $patient_los = $patient_los+floor($answer_2);

	                $month_created = date("m", strtotime($looped_patient['date_created']));
		            $current_month = date("m");
		            if(date("Y", strtotime($looped_patient['date_created'])) == date("Y"))
		            {
		            	if($current_month == $month_created)
			            {
			            	$patient_days = (date("d") - date("d", strtotime($looped_patient['date_created'])));
			            	$patient_days += 1;
			            }
			            else if($current_month > $month_created)
			            {
			            	$patient_days = date("d");
			            }
		            }
		            else if(date("Y") > date("Y", strtotime($looped_patient['date_created'])))
		            {
		            	$patient_days = date("d");
		            }
	        	}
	        	// IF NAAY COMPLETE PICKUP
	        	else
	        	{
	        		$pickup_order_count = 1;
		            $previous_pickup_indications = 0; // 1 for selected item(s) pickup, 2 for complete pickup
		            foreach ($returned_data as $value){

		                if($pickup_order_count == 1)
		                {
		                	if($value['pickup_sub'] != "not needed")
			        		{
			        			if($pickup_all_count_sign == 1)
			        			{
			        				$returned_query_inside = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
			        				if(date("Y-m-d", strtotime($returned_query_inside['date_ordered'])) > $value['pickup_date'])
			        				{
			        					$previous_pickup_indications = 2;
					                    $previous_orderID = $value['orderID'];
					                    $previous_uniqueID = $value['uniqueID'];
					                    $previous_ordered_date = $value['pickup_date'];
					                    $previous_date_ordered = $value['date_ordered'];
					                    $partial_patient_los_first = 1; // Back to 1
					                    $partial_patient_days_first = 1;
			        				}
			        				else
			        				{
			        					$previous_pickup_indications = 1;
			        					$previous_orderID = $value['orderID'];
					                    $previous_uniqueID = $value['uniqueID'];
					                    $previous_ordered_date = $value['pickup_date'];
					                    $previous_date_ordered = $value['date_ordered'];
					                    $answer = strtotime($value['pickup_date'])-strtotime($patient_first_order['actual_order_date']);
					                    $answer_2 = $answer/86400;
					                    $partial_patient_los_first = $patient_los+floor($answer_2);
					                    $partial_patient_days_first = 1;
			        				}
			        			}
			        			else
			        			{
			        				$previous_pickup_indications = 2;
				                    $previous_orderID = $value['orderID'];
				                    $previous_uniqueID = $value['uniqueID'];
				                    $previous_ordered_date = $value['pickup_date'];
				                    $previous_date_ordered = $value['date_ordered'];
				                    $partial_patient_los_first = 1; // Back to 1
				                    $partial_patient_days_first = 1;
			        			}
			        		}
			        		else
			        		{
			        			$answer = strtotime($value['pickup_date'])-strtotime($patient_first_order['actual_order_date']);
			                    $answer_2 = $answer/86400;
			                    $partial_patient_los_first = $patient_los+floor($answer_2);
			                    $previous_pickup_indications = 1;
			                    $partial_patient_days_first = 1;
			                    $previous_orderID = $value['orderID'];
			                    $previous_uniqueID = $value['uniqueID'];
			                    $previous_ordered_date = $value['pickup_date'];
			                    $previous_date_ordered = $value['date_ordered'];
			        		}
		                }
		                else
		                {
		                	if($value['pickup_sub'] != "not needed")
			        		{
			        			$previous_pickup_indications = 2;
			        			if(count($returned_data) == $pickup_order_count)
				                {
				                	$returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
				                	if(!empty($returned_query))
				                	{
					                    if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $value['pickup_date'])
				                		{
			                				$current_date = date("Y-m-d h:i:s");
						                    $answer = strtotime($current_date)-strtotime($looped_patient['date_created']);
						                    $answer_2 = $answer/86400;
						                    $patient_los = $patient_los+floor($answer_2);

						                    $month_created = date("m", strtotime($looped_patient['date_created']));
								            $current_month = date("m");
								            if(date("Y", strtotime($looped_patient['date_created'])) == date("Y"))
								            {
								            	if($current_month == $month_created)
									            {
									            	$patient_days = (date("d") - date("d", strtotime($looped_patient['date_created'])));
									            	$patient_days += 1;
									            }
									            else if($current_month > $month_created)
									            {
									            	$patient_days = date("d");
									            }
								            }
								            else if(date("Y") > date("Y", strtotime($looped_patient['date_created'])))
								            {
								            	$patient_days = date("d");
								            }
				                		}
				                		else
				                		{
				                			$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
					                        $answer_2 = $answer/86400;
					                        $patient_los = $partial_patient_los_first+floor($answer_2);

					                        $month_created = date("m", strtotime($looped_patient['date_created']));
								            $patient_last_month = date("m", strtotime($value['pickup_date']));
								            if(date("Y", strtotime($looped_patient['date_created'])) == date("Y", strtotime($value['pickup_date'])))
						            		{
						            			if($patient_last_month == $month_created)
									            {
									            	$patient_days = (date("d", strtotime($value['pickup_date'])) - date("d", strtotime($looped_patient['date_created'])));
									            	$patient_days += 1;
									            }
									            else if($patient_last_month > $month_created)
									            {
									            	$patient_days = date("d", strtotime($value['pickup_date']));
									            }
						            		}
						            		else if(date("Y", strtotime($value['pickup_date'])) > date("Y", strtotime($looped_patient['date_created'])))
								            {
								            	$patient_days = date("d", strtotime($value['pickup_date']));
								            }
				                		}
				                	}
				                	else
				                	{
				                		$answer = strtotime($value['pickup_date'])-strtotime($looped_patient['date_created']);
						                $answer_2 = $answer/86400;
						                $patient_los = $patient_los+floor($answer_2);

						                $month_created = date("m", strtotime($looped_patient['date_created']));
							            $current_month = date("m");
							            if(date("Y", strtotime($looped_patient['date_created'])) == date("Y", strtotime($value['pickup_date'])))
							            {
							            	if($current_month == $month_created)
								            {
								            	$active_patient_sign = 0;
								            	foreach ($list_active_patients as $looped_active_patient) {
								            		if($looped_active_patient['patientID'] == $looped_patient['patientID'])
								            		{
								            			$active_patient_sign = 1;
								            		}
								            	}
								            	if($active_patient_sign == 1)
								            	{
								            		$patient_days = date("d");
								            	}
								            	else
								            	{
								            		$patient_days = (date("d", strtotime($value['pickup_date'])) - date("d", strtotime($looped_patient['date_created'])));
								            		$patient_days += 1;
								            	}
								            }
								            else if($current_month > $month_created)
								            {
								            	$active_patient_sign = 0;
								            	foreach ($list_active_patients as $looped_active_patient) {
								            		if($looped_active_patient['patientID'] == $looped_patient['patientID'])
								            		{
								            			$active_patient_sign = 1;
								            		}
								            	}
								            	if($active_patient_sign == 1)
								            	{
								            		$patient_days = date("d");
								            	}
								            	else
								            	{
								            		$patient_days = date("d", strtotime($value['pickup_date']));
								            	}
								            }
							            }
							            else if(date("Y", strtotime($value['pickup_date'])) > date("Y", strtotime($looped_patient['date_created'])))
							            {
							            	$active_patient_sign = 0;
							            	foreach ($list_active_patients as $looped_active_patient) {
							            		if($looped_active_patient['patientID'] == $looped_patient['patientID'])
							            		{
							            			$active_patient_sign = 1;
							            		}
							            	}
							            	if($active_patient_sign == 1)
							            	{
							            		$patient_days = date("d");
							            	}
							            	else
							            	{
							            		$patient_days = date("d", strtotime($value['pickup_date']));
							            	}
							            }
				                	}
				                }
				                else
				                {
				                	$returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
			                		if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $value['pickup_date'])
			                		{
			                			$partial_patient_los_first = 1;
			                			$previous_date_ordered = $value['date_ordered'];
			                		}
			                		else
			                		{
			                			$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
				                        $answer_2 = $answer/86400;
				                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
			                		}
			                		$partial_patient_days_first = 1;
				                }
			        		}
			        		else
			        		{
			        			$previous_pickup_indications = 1;
			        			if(count($returned_data) == $pickup_order_count)
				                {
				                	$current_date = date("Y-m-d h:i:s");
				                	if($value['pickup_date'] > $current_date)
				                	{
				                		$answer = strtotime($current_date)-strtotime($previous_ordered_date);
				                		$answer_2 = $answer/86400;
				                        $patient_los = $partial_patient_los_first+floor($answer_2);
				                	}
				                	else
				                	{
				                		$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
				                		$answer_2 = $answer/86400;
				                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);

				                        $answer_sub = strtotime($current_date)-strtotime($value['pickup_date']);
				                        $answer_2_sub = $answer_sub/86400;
				                        $patient_los = $partial_patient_los_first+floor($answer_2_sub);
				                	}
				                }
				                else
				                {
				                	if($previous_pickup_indications == 1)
				                	{
				                		$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
				                        $answer_2 = $answer/86400;
				                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
				                	}
				                	else
				                	{
				                		$answer = strtotime($value['pickup_date'])-strtotime($looped_patient['date_created']);
				                        $answer_2 = $answer/86400;
				                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
				                	}
				                	$previous_date_ordered = $value['date_ordered'];
				                }
				                $month_created = date("m", strtotime($looped_patient['date_created']));
					            $current_month = date("m");
					            if(date("Y", strtotime($looped_patient['date_created'])) == date("Y"))
					            {
					            	if($current_month == $month_created)
						            {
						            	$patient_days = (date("d") - date("d", strtotime($looped_patient['date_created'])));
						            	$patient_days += 1;
						            }
						            else if($current_month > $month_created)
						            {
						            	$patient_days = date("d");
						            }
					            }
					            else if(date("Y") > date("Y", strtotime($looped_patient['date_created'])))
					            {
					            	$patient_days = date("d");
					            }
			        		}
		              	}
		              	$pickup_order_count++;
		              	$previous_ordered_date = $value['pickup_date'];
		        	}
	        	}
	      	}

	      	if($patient_los < 1)
          	{
          		$patient_los = 1;
          	}
          	$data_per_patient = array(
          		'patientID'		 => $looped_patient['patientID'],
          		'length_of_stay' => $patient_los
          	);
          	$data_to_be_updated[] = $data_per_patient;

          	if($patient_days < 1)
          	{
          		$patient_days = 1;
          	}
          	$data_per_patient_pt_days = array(
          		'patientID'		 => $looped_patient['patientID'],
          		'patient_days' => $patient_days
          	);
          	$data_to_be_updated_patient_days[] = $data_per_patient_pt_days;
		}
		$this->order_model->update_patient_los($data_to_be_updated);
		$this->order_model->update_patient_los($data_to_be_updated_patient_days);

		$los_per_hospice = $this->order_model->get_total_patient_los_per_hospice();
		$data_to_be_inserted = array();
		$current_date = date("Y-m-d");

		$current_hospice = 0;
		$count_los_per_hospice = 0;
		$count = 0;
		$total_los_data = array();
		foreach ($los_per_hospice as $value){
			if($count == 0)
			{
				$total_los_data[$value['ordered_by']] = $value['total_patient_los'];
				$count++;
			}
			else
			{
				if($value['ordered_by'] != $current_hospice)
				{
					$total_los_data[$value['ordered_by']] = $value['total_patient_los'];
				}
				else
				{
					$total_los_data[$value['ordered_by']] += $value['total_patient_los'];
				}
			}
			$current_hospice = $value['ordered_by'];
		}

		foreach ($total_los_data as $key => $value) {
			$data = array(
				'date_saved'		=> $current_date,
				'hospiceID' 		=> $key,
				'patient_total_los'	=> $value
			);

			$data_to_be_inserted[] = $data;
		}
		$this->order_model->insert_patient_los_per_hospice($data_to_be_inserted);


		$patient_days_per_hospice = $this->order_model->get_total_patient_days_per_hospice();
		$data_to_be_inserted_patient_days = array();
		$current_date = date("Y-m-d");

		$current_hospice = 0;
		$count_patient_days_per_hospice = 0;
		$count = 0;
		$total_patient_days_data = array();
		foreach ($patient_days_per_hospice as $value){
			if($count == 0)
			{
				$total_patient_days_data[$value['ordered_by']] = $value['total_patient_days'];
				$count++;
			}
			else
			{
				if($value['ordered_by'] != $current_hospice)
				{
					$total_patient_days_data[$value['ordered_by']] = $value['total_patient_days'];
				}
				else
				{
					$total_patient_days_data[$value['ordered_by']] += $value['total_patient_days'];
				}
			}
			$current_hospice = $value['ordered_by'];
		}
		foreach ($total_patient_days_data as $key => $value) {
			$data = array(
				'date_saved'			=> $current_date,
				'hospiceID' 			=> $key,
				'total_patient_days'	=> $value
			);

			$data_to_be_inserted_patient_days[] = $data;
		}
		$this->order_model->insert_patient_days_per_hospice($data_to_be_inserted_patient_days);
	}

	// public function drop_table()
	// {
	// 	$this->order_model->drop_table();
	// }

	public function add_assigned_equipment()
	{
		$data = array(
			'1'	=> '1466718021',
			'2' => '1468364970',
			'4' => '1410910605',
			'8' => '1425987625',
			'9' => '1429070586',
			'10' => '1428516954',
			'12' => '1466718109',
			'13' => '1447902918',
			'14' => '1466718313',
			'15' => '1466718166',
			'16' => '1466718220',
			'17' => '1466718147',
			'18' => '1466718184',
			'19' => '1466718259',
			'20' => '1466718295',
			'21' => '1466718277',
			'22' => '1466718350',
			'23' => '1466718126',
			'24' => '1466718202',
			'25' => '1466718241',
			'26' => '1466718049',
			'27' => '1466718089',
			'28' => '1467320991',
			'30' => '1487919221',
			'31' => '1468862054',
			'32' => '1468983474',
			'33' => '1470334790',
			'34' => '1470416720',
			'35' => '1472493307',
			'36' => '1490654694',
			'37' => '1491514702',
			'38' => '1493325114',
			'39' => '1499812832',
			'40' => '1501480842',
			'41' => '1512551124',
			'42' => '1512551218',
			'43' => '1512551377',
			'44' => '1513357961',
			'45' => '1513358160',
			'46' => '1513358515',
			'47' => '1515714432',
			'48' => '1515803191',
			'49' => '1516314221'
		);

		$data_item_list = [485,486,487,488,489,490,491,492];
		foreach ($data_item_list as $value_here) {
			foreach ($data as $key => $value) {
				$data_tobe_inserted = array(
					'equipmentID' 	=> $value_here,
					'hospiceID'		=> $key,
					'uniqueID'		=> $value
				);
				$this->order_model->insert_assigned_item($data_tobe_inserted);
			}
		}
	}

	public function create_order($hospice_id=0)
	{
		$hospiceID = $this->session->userdata('group_id');
		$user_type = $this->session->userdata('account_type');

		$data['hospice_selected'] = $hospice_id;
		$data['hospice_phone']	 = json_decode(get_hospice_phone($hospice_id));
		$data['hospice_address'] = get_hospice_address($hospiceID);

		$categories = $this->order_model->get_equipment_category();

		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				if($hospice_id != 0)
				{
					$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospice_id);
				}
				else
				{
					$children = $this->order_model->get_equipment($cat['categoryID']);
				}

			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}

			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}

		$data['equipments'] = $equipment_array;
		$data['hospices']	= $this->hospice_model->list_group_v3($this->session->userdata('user_location'));

		$data['chosen_hospiceID'] = $hospice_id;
		$this->load->model("equipment_model");
		$data['capped_count']	= $this->equipment_model->assigned_equipment_capped($hospice_id);
		$data['active_nav']	= "create_order";

		$this->templating_library->set('title','Create New Order');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/order_form','pages/order_form', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function get_activity_type_name()
	{
		$order = $this->input->get();

        $address_type = get_address_type($order['addressID']);
        $address_sequence = 0;
        $address_count = 1;
        $order_activity_name = get_activity_name($order['status_activity_typeid']);

        if($order_activity_name == "Delivery")
        {
          if(($address_type['type']) == 0)
          {
            echo "Delivery";
          }
          else if($address_type['type'] == 1)
          {
            $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($order['patientID']);
            foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
              if($addresses_ID_row['id'] == $order['addressID'])
              {
                $address_sequence = $address_count;
                break;
              }
              $address_count++;
            }
            if($address_sequence == 1)
            {
              echo "Delivery (CUS Move)";
            }
            else
            {
              echo "Delivery (CUS Move ".$address_sequence.")";
            }
          }
          else
          {
            $respite_addresses_ID = get_respite_addresses_ID_v2($order['patientID']);
            foreach($respite_addresses_ID as $key => $addresses_ID_row) {
              if($addresses_ID_row['id'] == $order['addressID'])
              {
                $address_sequence = $address_count;
                break;
              }
              $address_count++;
            }
            if($address_sequence == 1)
            {
              echo "Delivery (Respite)";
            }
            else
            {
              echo "Delivery (Respite ".$address_sequence.")";
            }
          }
        }
        else if($order_activity_name == "Exchange")
        {
          if(($address_type['type']) == 0)
          {
            echo "Exchange";
          }
          else if($address_type['type'] == 1)
          {
            $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($order['patientID']);
            foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
              if($addresses_ID_row['id'] == $order['addressID'])
              {
                $address_sequence = $address_count;
                break;
              }
              $address_count++;
            }
            if($address_sequence == 1)
            {
              echo "Exchange (CUS Move)";
            }
            else
            {
              echo "Exchange (CUS Move ".$address_sequence.")";
            }
          }
          else
          {
            $respite_addresses_ID = get_respite_addresses_ID_v2($order['patientID']);
            foreach($respite_addresses_ID as $key => $addresses_ID_row) {
              if($addresses_ID_row['id'] == $order['addressID'])
              {
                $address_sequence = $address_count;
                break;
              }
              $address_count++;
            }
            if($address_sequence == 1)
            {
              echo "Exchange (Respite)";
            }
            else
            {
              echo "Exchange (Respite ".$address_sequence.")";
            }
          }
        }
        else if($order_activity_name == "CUS Move")
        {
          $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($order['patientID']);
          foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
            if($addresses_ID_row['id'] == $order['addressID'])
            {
              $address_sequence = $address_count;
              break;
            }
            $address_count++;
          }
          if($address_sequence == 1)
          {
            echo "CUS Move";
          }
          else
          {
            echo "CUS Move ".$address_sequence;
          }
        }
        else if($order_activity_name == "Respite")
        {
          $respite_addresses_ID = get_respite_addresses_ID_v2($order['patientID']);
          foreach($respite_addresses_ID as $key => $addresses_ID_row) {
            if($addresses_ID_row['id'] == $order['addressID'])
            {
              $address_sequence = $address_count;
              break;
            }
            $address_count++;
          }
          if($address_sequence == 1)
          {
            echo "Respite";
          }
          else
          {
            echo "Respite ".$address_sequence;
          }
        }
        else if($order_activity_name == "Pickup")
        {
          if(($address_type['type']) == 0)
          {
            echo "Pickup";
          }
          else if($address_type['type'] == 1)
          {
            $ptmove_addresses_ID = get_ptmove_addresses_ID_v2($order['patientID']);
            foreach($ptmove_addresses_ID as $key => $addresses_ID_row) {
              if($addresses_ID_row['id'] == $order['addressID'])
              {
                $address_sequence = $address_count;
                break;
              }
              $address_count++;
            }
            if($address_sequence == 1)
            {
              echo "Pickup (CUS Move)";
            }
            else
            {
              echo "Pickup (CUS Move ".$address_sequence.")";
            }
          }
          else
          {
            $respite_addresses_ID = get_respite_addresses_ID_v2($order['patientID']);
            foreach($respite_addresses_ID as $key => $addresses_ID_row) {
              if($addresses_ID_row['id'] == $order['addressID'])
              {
                $address_sequence = $address_count;
                break;
              }
              $address_count++;
            }
            if($address_sequence == 1)
            {
              echo "Pickup (Respite)";
            }
            else
            {
              echo "Pickup (Respite ".$address_sequence.")";
            }
          }
        }
	}

	public function get_order_list_status_select()
	{
		$data = $this->input->get();

		$this->templating_library->set_view('pages/order_list_select_order_status','pages/order_list_select_order_status', $data);
	}

	public function get_order_list_status_select_confirm()
	{
		$data = $this->input->get();

		$this->templating_library->set_view('pages/order_list_select_order_status_confirm','pages/order_list_select_order_status_confirm', $data);
	}

	public function get_cus_order_status_data_by_activity_type($selected_activity_status)
	{
		$response_data = array(
			"data" => array(),
			"draw" => 1,
			"recordsFiltered" => 0,
			"recordsTotal" => 0
		);

		if($this->input->is_ajax_request())
		{
			$datatable = $this->input->get();
			$start = $datatable['start'];
			$limit = $datatable['length'];
			$filters = array(
				"search_item_fields_customer_orders" => $datatable['search']['value']
			);

			$column = array(
				"order_by_order_date",
				"order_by_last_name",
				"order_by_first_name",
				"order_by_medical_record_no",
				"order_by_hospice_name"
			);
			$filters[$column[$datatable["order"][0]["column"]]] = $datatable["order"][0]["dir"];
			$result = $this->order_model->list_order_status_new_v3_by_activity_type($filters,$this->session->userdata('user_location'),$selected_activity_status,$start,$limit);

			if($result['totalCount']>0)
			{
				foreach ($result['result'] as $key => $value)
				{
					$queried_data = get_patients_first_order_uniqueID($value['medical_record_id'],$value['organization_id'],true);
				    if($queried_data['uniqueID'] == $value['uniqueID'])
				    {
				      $returned_result = check_if_new_patient($value['medical_record_id'],$queried_data['uniqueID'],$value['organization_id'],true);
				    }
				    else
				    {
				      $returned_result = $queried_data;
				    }

					$value['order_date_data'] = date("m/d/Y", strtotime($value['pickup_date']));
					$value['last_name_data'] = '<a class="text-bold"'.
												'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['organization_id']).'"'.
												'target="_blank">'.
												''.$value['p_lname'].''.
											'</a>';
					$value['first_name_data'] = '<a class="text-bold"'.
												'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['organization_id']).'"'.
												'target="_blank">'.
												''.$value['p_fname'].''.
											'</a>';
					$value['activity_type_spinner'] = '<i class="fa fa-spin fa-spinner"></i>';

					if(empty($returned_result))
					{
                  		$value['work_order_no_data'] = '<a href="javascript:void(0)"'.
													'class="view_order_details data_tooltip"'.
													'title="Click to View Work Order"'.
													'data-id="'.$value['medical_record_id'].'"'.
													'data-value="'.$value['hospiceID'].'"'.
													'data-unique-id="'.$value['uniqueID'].'"'.
													'data-act-id="'.$value['status_activity_typeid'].'"'.
													'data-equip-id="'.$value['equipmentID'].'"'.
													'data-patient-id="'.$value['patientID'].'">'.
                    									'<button class="btn btn-info" style="width:82px;">'.
                    										''.substr($value['uniqueID'],4,10).''.
                    										'<span class="new-patient-icon" style="font-weight:bolder;font-size:13px;font-family: Goudy Old Style, Garamond, Big Caslon, Times New Roman, serif !important;"> N</span>'.
                    									'</button>'.
                  								'</a>';
					}
					else
					{
						$value['work_order_no_data'] = '<a href="javascript:void(0)"'.
													'class="view_order_details data_tooltip"'.
													'title="Click to View Work Order"'.
													'data-id="'.$value['medical_record_id'].'"'.
													'data-value="'.$value['hospiceID'].'"'.
													'data-unique-id="'.$value['uniqueID'].'"'.
													'data-act-id="'.$value['status_activity_typeid'].'"'.
													'data-equip-id="'.$value['equipmentID'].'"'.
													'data-patient-id="'.$value['patientID'].'">'.
                    									'<button class="btn btn-info" style="width:82px;">'.
                    										''.substr($value['uniqueID'],4,10).''.
                    									'</button>'.
                  								'</a>';
					}
                  	$value['order_notes_data'] = '<a href="javascript:void(0)"'.
                  								'data-id="'.$value['uniqueID'].'"'.
                  								'name="comment-modal"'.
                  								'style="text-decoration:none;cursor:pointer"'.
                  								'class="comments_link">'.
                    								'<i class="icon-speech"></i>'.
                    								'<p style="float: right;margin-top: -3px;margin-right: 11px;">'.
                    									''.$value['comment_count'].''.
                    								'</p>'.
                  							'</a>';
                  	$value['order_status_spinner'] = '<i class="fa fa-spin fa-spinner"></i>';
					$response_data['data'][] = $value;
				}
			}
			else
			{
				$response_data['data'] = array();
			}

			$response_data['draw'] = $datatable['draw'];
			$response_data['recordsFiltered'] = $result['totalCount'];
			$response_data['recordsTotal'] = $result['totalCount'];
		}
		echo json_encode($response_data);
	}

	public function get_cus_order_status_data()
	{
		$response_data = array(
			"data" => array(),
			"draw" => 1,
			"recordsFiltered" => 0,
			"recordsTotal" => 0
		);

		if($this->input->is_ajax_request())
		{
			$datatable = $this->input->get();
			$start = $datatable['start'];
			$limit = $datatable['length'];
			$filters = array(
				"search_item_fields_customer_orders" => $datatable['search']['value']
			);

			$column = array(
				"order_by_order_date",
				"order_by_last_name",
				"order_by_first_name",
				"order_by_medical_record_no",
				"order_by_hospice_name"
			);
			$filters[$column[$datatable["order"][0]["column"]]] = $datatable["order"][0]["dir"];
			$result = $this->order_model->list_order_status_new_v3($filters,$this->session->userdata('user_location'),$start,$limit);

			if($result['totalCount']>0)
			{
				foreach ($result['result'] as $key => $value)
				{
					$queried_data = get_patients_first_order_uniqueID($value['medical_record_id'],$value['organization_id'],true,"uniqueID");
				    if($queried_data['uniqueID'] == $value['uniqueID'])
				    {
				      $returned_result = check_if_new_patient($value['medical_record_id'],$queried_data['uniqueID'],$value['organization_id'],true,"uniqueID");
				    }
				    else
				    {
				      $returned_result = $queried_data;
				    }

					$value['order_date_data'] = date("m/d/Y", strtotime($value['pickup_date']));
					$value['last_name_data'] = '<a class="text-bold"'.
												'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['organization_id']).'"'.
												'target="_blank">'.
												''.$value['p_lname'].''.
											'</a>';
					$value['first_name_data'] = '<a class="text-bold"'.
												'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['organization_id']).'"'.
												'target="_blank">'.
												''.$value['p_fname'].''.
											'</a>';
					$value['activity_type_spinner'] = '<i class="fa fa-spin fa-spinner"></i>';
					if(empty($returned_result))
					{
						$value['work_order_no_data'] = '<a href="javascript:void(0)"'.
													'class="view_order_details data_tooltip"'.
													'title="Click to View Work Order"'.
													'data-id="'.$value['medical_record_id'].'"'.
													'data-value="'.$value['hospiceID'].'"'.
													'data-unique-id="'.$value['uniqueID'].'"'.
													'data-act-id="'.$value['status_activity_typeid'].'"'.
													'data-equip-id="'.$value['equipmentID'].'"'.
													'data-patient-id="'.$value['patientID'].'">'.
                    									'<button class="btn btn-info" style="width:82px;">'.
                    										''.substr($value['uniqueID'],4,10).''.
                    										'<span class="new-patient-icon" style="font-weight:bolder;font-size:13px;font-family: Goudy Old Style, Garamond, Big Caslon, Times New Roman, serif !important;"> N</span>'.
                    									'</button>'.
                  								'</a>';
					}
					else
					{
						$value['work_order_no_data'] = '<a href="javascript:void(0)"'.
													'class="view_order_details data_tooltip"'.
													'title="Click to View Work Order"'.
													'data-id="'.$value['medical_record_id'].'"'.
													'data-value="'.$value['hospiceID'].'"'.
													'data-unique-id="'.$value['uniqueID'].'"'.
													'data-act-id="'.$value['status_activity_typeid'].'"'.
													'data-equip-id="'.$value['equipmentID'].'"'.
													'data-patient-id="'.$value['patientID'].'">'.
                    									'<button class="btn btn-info" style="width:82px;">'.
                    										''.substr($value['uniqueID'],4,10).''.
                    									'</button>'.
                  								'</a>';
					}
                  	$value['order_notes_data'] = '<a href="javascript:void(0)"'.
                  								'data-id="'.$value['uniqueID'].'"'.
                  								'name="comment-modal"'.
                  								'style="text-decoration:none;cursor:pointer"'.
                  								'class="comments_link">'.
                    								'<i class="icon-speech"></i>'.
                    								'<p style="float: right;margin-top: -3px;margin-right: 11px;">'.
                    									''.$value['comment_count'].''.
                    								'</p>'.
                  							'</a>';
                  	$value['order_status_spinner'] = '<i class="fa fa-spin fa-spinner"></i>';
					$response_data['data'][] = $value;
				}
			}
			else
			{
				$response_data['data'] = array();
			}

			$response_data['draw'] = $datatable['draw'];
			$response_data['recordsFiltered'] = $result['totalCount'];
			$response_data['recordsTotal'] = $result['totalCount'];
		}
		echo json_encode($response_data);
	}

	public function get_cus_order_status_data_confirm()
	{
		$response_data = array(
			"data" => array(),
			"draw" => 1,
			"recordsFiltered" => 0,
			"recordsTotal" => 0
		);

		if($this->input->is_ajax_request())
		{
			$datatable = $this->input->get();
			$start = $datatable['start'];
			$limit = $datatable['length'];
			$filters = array(
				"search_item_fields_customer_orders" => $datatable['search']['value']
			);

			$column = array(
				"order_by_order_date",
				"order_by_last_name",
				"order_by_first_name",
				"order_by_medical_record_no",
				"order_by_hospice_name"
			);
			$filters[$column[$datatable["order"][0]["column"]]] = $datatable["order"][0]["dir"];
			$result = $this->order_model->list_order_status_new_v3_confirm($filters,$this->session->userdata('user_location'),$start,$limit);

			if($result['totalCount']>0)
			{
				foreach ($result['result'] as $key => $value)
				{
					$queried_data = get_patients_first_order_uniqueID($value['medical_record_id'],$value['organization_id'],true,"uniqueID");
				    if($queried_data['uniqueID'] == $value['uniqueID'])
				    {
				      $returned_result = check_if_new_patient($value['medical_record_id'],$queried_data['uniqueID'],$value['organization_id'],true,"uniqueID");
				    }
				    else
				    {
				      $returned_result = $queried_data;
				    }

					$value['order_date_data'] = date("m/d/Y", strtotime($value['pickup_date']));
					$value['last_name_data'] = '<a class="text-bold"'.
												'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['organization_id']).'"'.
												'target="_blank">'.
												''.$value['p_lname'].''.
											'</a>';
					$value['first_name_data'] = '<a class="text-bold"'.
												'href="'.base_url('order/patient_profile/'.$value['medical_record_id']."/".$value['organization_id']).'"'.
												'target="_blank">'.
												''.$value['p_fname'].''.
											'</a>';
					$value['activity_type_spinner'] = '<i class="fa fa-spin fa-spinner"></i>';
					if(empty($returned_result))
					{
                  		$value['work_order_no_data'] = '<a href="javascript:void(0)"'.
													'class="view_order_details data_tooltip"'.
													'title="Click to View Work Order"'.
													'data-id="'.$value['medical_record_id'].'"'.
													'data-value="'.$value['hospiceID'].'"'.
													'data-unique-id="'.$value['uniqueID'].'"'.
													'data-act-id="'.$value['status_activity_typeid'].'"'.
													'data-equip-id="'.$value['equipmentID'].'"'.
													'data-patient-id="'.$value['patientID'].'">'.
                    									'<button class="btn btn-info" style="width:82px;">'.
                    										''.substr($value['uniqueID'],4,10).''.
                    										'<span class="new-patient-icon" style="font-weight:bolder;font-size:13px;font-family: Goudy Old Style, Garamond, Big Caslon, Times New Roman, serif !important;"> N</span>'.
                    									'</button>'.
                  								'</a>';
					}
					else
					{
						$value['work_order_no_data'] = '<a href="javascript:void(0)"'.
													'class="view_order_details data_tooltip"'.
													'title="Click to View Work Order"'.
													'data-id="'.$value['medical_record_id'].'"'.
													'data-value="'.$value['hospiceID'].'"'.
													'data-unique-id="'.$value['uniqueID'].'"'.
													'data-act-id="'.$value['status_activity_typeid'].'"'.
													'data-equip-id="'.$value['equipmentID'].'"'.
													'data-patient-id="'.$value['patientID'].'">'.
                    									'<button class="btn btn-info" style="width:82px;">'.
                    										''.substr($value['uniqueID'],4,10).''.
                    									'</button>'.
                  								'</a>';
					}
                  	$value['order_notes_data'] = '<a href="javascript:void(0)"'.
                  								'data-id="'.$value['uniqueID'].'"'.
                  								'name="comment-modal"'.
                  								'style="text-decoration:none;cursor:pointer"'.
                  								'class="comments_link">'.
                    								'<i class="icon-speech"></i>'.
                    								'<p style="float: right;margin-top: -3px;margin-right: 11px;">'.
                    									''.$value['comment_count'].''.
                    								'</p>'.
                  							'</a>';
                  	$value['order_status_spinner'] = '<i class="fa fa-spin fa-spinner"></i>';
					$response_data['data'][] = $value;
				}
			}
			else
			{
				$response_data['data'] = array();
			}

			$response_data['draw'] = $datatable['draw'];
			$response_data['recordsFiltered'] = $result['totalCount'];
			$response_data['recordsTotal'] = $result['totalCount'];
		}
		echo json_encode($response_data);
	}

	public function order_list($view_type='list-view')
	{
		$data['order_status_list'] = true;
		$get_data = $this->input->get();

		if($view_type == 'list-view')
		{
			// $result = $this->order_model->list_order_status_new_v2($this->session->userdata('user_location'));
			// $data['orders'] = ($result) ? $result : FALSE;

			$data['active_nav']	= "order_list";
			$this->templating_library->set('title','CUS Order Status');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav',$data);
			$this->templating_library->set_view('pages/patient_order_list','pages/patient_order_list', $data);
		}
		else
		{
			$hospiceID = $this->session->userdata('group_id');
			$data['hospice_selected'] = $hospiceID;
			$current_date = date("Y-m-d");

			$data['total_los_for_today'] = $this->order_model->get_all_total_patient_los_per_hospice_v2($current_date,$this->session->userdata('user_location'));
			if(empty($data['total_los_for_today']['patient_total_los']))
			{
				$data['total_los_for_today']['patient_total_los'] = 0;
			}

			$data['total_patient_days_for_today'] = $this->order_model->get_all_total_patient_days_per_hospice_v2($current_date,$this->session->userdata('user_location'));
			if(empty($data['total_patient_days_for_today']['total_patient_days']))
			{
				$data['total_patient_days_for_today']['total_patient_days'] = 0;
			}

			//list of active patients only
			$result = $this->order_model->list_active_patients_v3($this->session->userdata('user_location'));

			$data['counting'] = count($result);

			$arr2 = array_msort($result, array('p_lname' => SORT_ASC, 'p_fname' => SORT_ASC));

			$new_patients = array_chunk($arr2, 100);

			$data['total_pages'] = count($new_patients);

			$data['orders'] = array();

			if(!empty($new_patients))
			{
				$data['orders'] = $new_patients[0];
			}

			$count_here = 0;
			foreach ($data['orders'] as $result_inside)
			{
				$data['orders'][$count_here]['active_patient'] = 1;
				$count_here++;
			}

			$data['active_nav']	= "patient_menu";
			$this->templating_library->set('title','View All Customers');
			$this->templating_library->set_view('common/head','common/head');
			$this->templating_library->set_view('common/header','common/header');
			$this->templating_library->set_view('common/nav','common/nav',$data);
			$this->templating_library->set_view('pages/patient_order_grid','pages/patient_order_grid', $data);
		}
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	//list of patients with no order data
	//$noorder = $this->order_model->list_of_noorder();
	//$array = array_merge($result, $noorder);
	// $result = $this->order_model->list_orders();
	// $counts = count($this->order_model->list_orders());
	// $data['orders'] = ($result) ? $result : FALSE;
	// $data['counts'] = $counts;

	public function get_patient_order_status_list()
	{
		if($this->input->is_ajax_request())
		{
			$datatable = $this->input->get();
			$start = $datatable['start'];
			$limit = $datatable['length'];
			$search_value = $datatable['search']['value'];
			$sort = array(
				"key" => $datatable["order"][0]["column"],
				"direction" => $datatable["order"][0]["dir"]
			);

			$result = $this->order_model->list_order_status_v2($limit,$start,$sort,$search_value);

			$data = array();
			foreach ($result['result'] as $key => $value) {
				$value['order_details_field'] = "<a href='javascript:void(0)' class='view_order_details data_tooltip' title='Click to View Work Order' data-id='".$value['medical_record_id']."' data-value='".$value['hospiceID']."' data-unique-id='".$value['uniqueID']."' data-act-id='".$value['status_activity_typeid']."' data-equip-id='".$value['equipmentID']."' data-patient-id='".$value['patientID']."'> <button class='btn btn-info'>Work Order</button> </a>";
				$value['pickup_date'] = date("m/d/Y", strtotime($value['pickup_date']));
				$value['status_notes_field'] = "<a href='javascript:void(0)' name='comment-modal' style='text-decoration:none;cursor:pointer' class='comments_link' data-id='".$value['uniqueID']."'> <i class='icon-speech'></i> <p style='float: right;margin-top: -3px;margin-right: 11px;'>".$value['comment_count']."</p></a>";
				$value['patient_fname'] = "<a class='text-bold' href='".base_url("order/patient_profile/".$value['medical_record_id']."/".$value['organization_id'])."' target='_blank'>".$value['p_lname']."</a>";
				$value['patient_lname'] = "<a class='text-bold' href='".base_url("order/patient_profile/".$value['medical_record_id']."/".$value['organization_id'])."' target='_blank'>".$value['p_fname']."</a>";

				if($this->session->userdata('account_type') == 'dme_admin')
				{
					$value['order_status_field'] = "<select class='form-control change_order_status' data-id='".$value['medical_record_id']."' data-organization-id='".$value['organization_id']."' data-unique-id='".$value['uniqueID']."' data-act-id='".$value['status_activity_typeid']."' data-equipment-id='".$value['equipmentID']."' >";
					if($value['order_status'] == 'pending')
					{
						$value['order_status_field'] .= "<option value='cancel'>Cancel</option> <option value='active'>En route</option> <option value='tobe_confirmed'>Move to Confirm WO</option> <option value='on-hold'>On Hold</option> <option selected='true' value='pending'>Pending</option> <option value='re-schedule'>Rescheduled</option></select>";
					}
					else if($value['order_status'] == 'active')
					{
						$value['order_status_field'] .= "<option value='cancel'>Cancel</option> <option selected='true' value='active'>En route</option> <option value='tobe_confirmed'>Move to Confirm WO</option> <option value='on-hold'>On Hold</option> <option value='pending'>Pending</option> <option value='re-schedule'>Rescheduled</option></select>";
					}
					else if($value['order_status'] == 'on-hold')
					{
						$value['order_status_field'] .= "<option value='cancel'>Cancel</option> <option value='active'>En route</option> <option value='tobe_confirmed'>Move to Confirm WO</option> <option selected='true' value='on-hold'>On Hold</option> <option value='pending'>Pending</option> <option value='re-schedule'>Rescheduled</option></select>";
					}
					else if($value['order_status'] == 're-schedule')
					{
						$value['order_status_field'] .= "<option value='cancel'>Cancel</option> <option value='active'>En route</option> <option value='tobe_confirmed'>Move to Confirm WO</option> <option value='on-hold'>On Hold</option> <option value='pending'>Pending</option> <option selected='true' value='re-schedule'>Rescheduled</option></select>";
						$returned_status_id = get_status_id($value['uniqueID']);
		                $returned_date = get_reschreschedule_onhold_date($returned_status_id['statusID']);
		                if(!empty($returned_date))
		                {
		                	$value['order_status_field'] .= "<span class='resceduled_onhold_date_pos' style='margin-left:25px;'> <span data-id='".$value['uniqueID']."' class='set_date_reschedule_onhold' style='cursor:pointer;'><i class='fa fa-calendar'></i></span>&nbsp;&nbsp; <span class='resceduled_onhold_date_container' data-sign='1'>".date("m/d/Y", strtotime($returned_date['date']))."</span></span>";
		                }
					}
					else if($value['order_status'] == 'cancel')
					{
						$value['order_status_field'] .= "<option selected='true' value='cancel'>Cancel</option> <option value='active'>En route</option> <option value='tobe_confirmed'>Move to Confirm WO</option> <option value='on-hold'>On Hold</option> <option value='pending'>Pending</option> <option value='re-schedule'>Rescheduled</option></select>";
					}
					else
					{
						$value['order_status_field'] .= "<option value='cancel'>Cancel</option> <option value='active'>En route</option> <option selected='true' value='tobe_confirmed'>Move to Confirm WO</option> <option value='on-hold'>On Hold</option> <option value='pending'>Pending</option> <option value='re-schedule'>Rescheduled</option></select>";
					}
				}
				else
				{
					if($value['order_status'] == 'active')
					{
						$value['order_status_field'] = "<p class='fa fa-truck' style='float:left;margin-top:3px;font-size:25px;color:#f0ad4e'></p><p style='float:left;margin-left:5px;margin-top: 5px;'> En route</p>";
					}
					else
					{
						$value['order_status_field'] = ucfirst($value['order_status']);
					}
				}
				$data[] = $value;
			}

			$response_data = array(
				"data" => $data,
				"draw" => $datatable['draw'],
				"recordsFiltered" => $result['totalCount'],
				"recordsTotal" => $result['totalCount']
			);

			echo json_encode($response_data);
		}
	}

	public function get_list_tobe_confirmed()
	{
		if($this->input->is_ajax_request())
		{
			$datatable = $this->input->get();
			$start = $datatable['start'];
			$limit = $datatable['length'];
			$search_value = $datatable['search']['value'];
			$sort = array(
				"key" => $datatable["order"][0]["column"],
				"direction" => $datatable["order"][0]["dir"]
			);
			$result = $this->order_model->list_tobe_confirmed_v2($limit,$start,$sort,$search_value);

			$data = array();
			foreach ($result['result'] as $key => $value) {
				$value['order_details_field'] = "<a href='javascript:void(0)' class='view_order_details data_tooltip' title='Click to View Work Order' data-id='".$value['medical_record_id']."' data-value='".$value['hospiceID']."' data-unique-id='".$value['uniqueID']."' data-act-id='".$value['status_activity_typeid']."' data-equip-id='".$value['equipmentID']."' data-patient-id='".$value['patientID']."'> <button class='btn btn-info'>Work Order</button> </a>";
				$value['pickup_date'] = date("m/d/Y", strtotime($value['pickup_date']));
				$value['status_notes_field'] = "<a href='javascript:void(0)' name='comment-modal' style='text-decoration:none;cursor:pointer' class='comments_link' data-id='".$value['uniqueID']."'> <i class='icon-speech'></i> <p style='float: right;margin-top: -3px;margin-right: 11px;'>".$value['comment_count']."</p></a>";
				$value['patient_fname'] = "<a class='text-bold' href='".base_url("order/patient_profile/".$value['medical_record_id']."/".$value['organization_id'])."' target='_blank'>".$value['p_lname']."</a>";
				$value['patient_lname'] = "<a class='text-bold' href='".base_url("order/patient_profile/".$value['medical_record_id']."/".$value['organization_id'])."' target='_blank'>".$value['p_fname']."</a>";

				if($this->session->userdata('account_type') == 'dme_admin')
				{
					$value['order_status_field'] = "<select class='form-control change_order_status' data-id='".$value['medical_record_id']."' data-organization-id='".$value['organization_id']."' data-unique-id='".$value['uniqueID']."' data-act-id='".$value['status_activity_typeid']."' data-equipment-id='".$value['equipmentID']."' >";
					$get_different = get_different($value['uniqueID']);

					if($value['order_status'] == 'pending')
					{
						$value['order_status_field'] .= "<option value='cancel'>Cancel</option>";
						if(!empty($get_different))
                        {
                            if($get_different['serial_num'] == '' && $value['status_activity_typeid'] == 2)
                            {
                            	$value['order_status_field'] .= "<option value='not_confirmed'>Confirm</option>";
                            }
                            else
                            {
                            	$value['order_status_field'] .= "<option value='confirmed'>Confirm</option>";
                            }
                        }
                        else
                        {
                        	$value['order_status_field'] .= "<option value='confirmed'>Confirm</option>";
                        }
						$value['order_status_field'] .= "<option value='tobe_confirmed'>Moved to Confirm WO</option><option selected='true' value='pending'>Revert to POS</option></select>";
					}
					else if($value['order_status'] == 'tobe_confirmed')
					{
						$value['order_status_field'] .= "<option value='cancel'>Cancel</option>";
						if(!empty($get_different))
                        {
                            if($get_different['serial_num'] == '' && $value['status_activity_typeid'] == 2)
                            {
                            	$value['order_status_field'] .= "<option value='not_confirmed'>Confirm</option>";
                            }
                            else
                            {
                            	$value['order_status_field'] .= "<option value='confirmed'>Confirm</option>";
                            }
                        }
                        else
                        {
                        	$value['order_status_field'] .= "<option value='confirmed'>Confirm</option>";
                        }
						$value['order_status_field'] .= "<option selected='true' value='tobe_confirmed'>Moved to Confirm WO</option><option value='pending'>Revert to POS</option></select>";
					}
					else if($value['order_status'] == 'cancel')
					{
						$value['order_status_field'] .= "<option selected='true' value='cancel'>Cancel</option>";
						if(!empty($get_different))
                        {
                            if($get_different['serial_num'] == '' && $value['status_activity_typeid'] == 2)
                            {
                            	$value['order_status_field'] .= "<option value='not_confirmed'>Confirm</option>";
                            }
                            else
                            {
                            	$value['order_status_field'] .= "<option value='confirmed'>Confirm</option>";
                            }
                        }
                        else
                        {
                        	$value['order_status_field'] .= "<option value='confirmed'>Confirm</option>";
                        }
						$value['order_status_field'] .= "<option value='tobe_confirmed'>Moved to Confirm WO</option><option value='pending'>Revert to POS</option></select>";
					}
					else
					{
						$value['order_status_field'] .= "<option value='cancel'>Cancel</option>";
						if(!empty($get_different))
                        {
                            if($get_different['serial_num'] == '' && $value['status_activity_typeid'] == 2)
                            {
                            	$value['order_status_field'] .= "<option selected='true' value='not_confirmed'>Confirm</option>";
                            }
                            else
                            {
                            	$value['order_status_field'] .= "<option selected='true' value='confirmed'>Confirm</option>";
                            }
                        }
                        else
                        {
                        	$value['order_status_field'] .= "<option selected='true' value='confirmed'>Confirm</option>";
                        }
						$value['order_status_field'] .= "<option value='tobe_confirmed'>Moved to Confirm WO</option><option value='pending'>Revert to POS</option></select>";
					}
				}
				else
				{
					if($value['order_status'] == 'active')
					{
						$value['order_status_field'] = "<p class='fa fa-truck' style='float:left;margin-top:3px;font-size:25px;color:#f0ad4e'></p><p style='float:left;margin-left:5px;margin-top: 5px;'> En route</p>";
					}
					else
					{
						$value['order_status_field'] = ucfirst($value['order_status']);
					}
				}
				$data[] = $value;
			}
			$response_data = array(
				"data" => $data,
				"draw" => $datatable['draw'],
				"recordsFiltered" => $result['totalCount'],
				"recordsTotal" => $result['totalCount']
			);

			echo json_encode($response_data);
		}
	}

	public function get_pos_activity_counter()
	{
		$reponse = array();

		$pending = array();
		$pending  = get_count_status_v2("","",$this->session->userdata('user_location'));
		$enroute  = get_count_status_v2("active","",$this->session->userdata('user_location'));
		$resched  = get_count_status_v2("re-schedule","",$this->session->userdata('user_location'));
		$onhold   = get_count_status_v2("on-hold","",$this->session->userdata('user_location'));

      	$response = array(
      					'active' 	=> $enroute,
      					'onhold' 	=> $onhold,
      					'pending'	=> $pending,
      					'resched'	=> $resched
      	);

		echo json_encode($response);
	}

	public function patient_order_list($patientID = "")
	{
		$data['order_status_list'] = true;
		$get_data = $this->input->get();
		$patientID = $this->encryption->decode($patientID);

		// $result = $this->order_model->patient_list_order_status($patientID);
		// $data['orders'] = ($result) ? $result : FALSE;

		$data['active_nav']	= "order_list";
		$this->templating_library->set('title','CUS Order Status');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav',$data);
		$this->templating_library->set_view('pages/patient_order_list','pages/patient_order_list', $data);

		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function order_list_by_status($status = "")
	{
		if($status == "Enroute")
	    {
	      $new_status = "active";
	    }
	    else if($status == "OnHold")
	    {
	      $new_status = "on-hold";
	    }
	    else if($status == "Pending")
	    {
	      $new_status = "pending";
	    }
	    else
	    {
	      $new_status = "re-schedule";
	    }
		// $data['orders'] = $this->order_model->list_order_status_by_status($new_status,$this->session->userdata('user_location'));
		$data['status'] = $new_status;
		$this->templating_library->set_view('pages/order_extension/order_list_by_status','pages/order_extension/order_list_by_status', $data);
	}

	public function list_tobe_confirmed()
	{
		$result = $this->order_model->list_tobe_confirmed_new_v2($this->session->userdata('user_location'));
		$data['orders'] = ($result) ? $result : FALSE;
		$data['active_nav']	= "order_list";
		$this->templating_library->set('title','Confirm Work Order');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav',$data);
		$this->templating_library->set_view('pages/list_tobe_confirmed','pages/list_tobe_confirmed', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function patient_list_tobe_confirmed($patientID = "")
	{
		$patientID = $this->encryption->decode($patientID);

		// $result = $this->order_model->patient_list_tobe_confirmed($patientID);
		// $data['orders'] = ($result) ? $result : FALSE;

		$data['active_nav']	= "order_list";
		$this->templating_library->set('title','Confirm Work Order');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav',$data);
		$this->templating_library->set_view('pages/list_tobe_confirmed','pages/list_tobe_confirmed', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function sort_by_hospice($hospiceID)
	{
		$data['hospice_selected'] = $hospiceID;
		$result = $this->order_model->list_orders_sorted($uniqueID="",$hospiceID, $where="");

		$current_date = date("Y-m-d");
		$data['total_los_for_today'] = $this->order_model->get_all_total_patient_los_specific_hospice($current_date,$hospiceID);
		if(empty($data['total_los_for_today']))
		{
			$data['total_los_for_today']['patient_total_los'] = 0;
		}

		$data['total_patient_days_for_today'] = $this->order_model->get_all_total_patient_days_specific_hospice($current_date,$hospiceID);
		if(empty($data['total_patient_days_for_today']))
		{
			$data['total_patient_days_for_today']['total_patient_days'] = 0;
		}

		$data['counting'] = count($result);
		$arr2 = array_msort($result, array('p_lname' => SORT_ASC, 'p_fname' => SORT_ASC));
		$new_patients = array_chunk($arr2, 100);
		$data['total_pages'] = count($new_patients);
		$data['orders'] = array();

		if(!empty($new_patients))
		{
			$data['orders'] = $new_patients[0];
		}

		$count_here = 0;
		foreach ($data['orders'] as $result_inside)
		{
			$data['orders'][$count_here]['active_patient'] = 1;
			$count_here++;
		}

		$data['active_nav']	= "patient_menu";
		$this->templating_library->set('title','View All Patients');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav',$data);
		$this->templating_library->set_view('pages/patient_order_grid','pages/patient_order_grid', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	//for the load more
	public function load_more($page=1, $limit=40)
	{
		$reponse = array("data" => array());
		if($this->input->is_ajax_request())
		{
			//list of active patients only
			$result = $this->order_model->list_active_patients();
			//list of patients with no order data
			//$noorder = $this->order_model->list_of_noorder();

			//$array = array_merge($result, $noorder);
			$arr2 = array_msort($result, array('p_lname' => SORT_ASC,'p_fname' => SORT_ASC));

			$new_patients = array_chunk($arr2, $limit);

			if(!empty($new_patients))
			{
				$response['data'] = $new_patients[$page-1];
			}
		}
		echo json_encode($response);
	}

	//for the load more
	public function load_more_new($page=1, $limit=40, $hospiceID)
	{
		$reponse = array("data" => array());
		if($this->input->is_ajax_request())
		{
			//list of active patients only
			// $result = $this->order_model->list_active_patients_new($hospiceID);
			$result = $this->order_model->list_active_patients_new_v2($hospiceID,$this->session->userdata('user_location'));
			$arr2 = array_msort($result, array('p_lname' => SORT_ASC,'p_fname' => SORT_ASC));

			$new_patients = array_chunk($arr2, $limit);

			if(!empty($new_patients))
			{
				$response['data'] = $new_patients[$page-1];
			}
		}
		echo json_encode($response);
	}

	//for the load more - DME Item Tracking Section
	public function item_tracking_load_more_new($sign,$serial_num)
	{
		$reponse = array("data" => array());
		if($this->input->is_ajax_request())
		{
			$active_patients_list = $this->order_model->list_active_patients();
			$reponse['data'] = $this->order_model->item_tracking_load_more_new($serial_num);
			$count = 0;
			$patient_total_los = 0;
			$total_patient_days = 0;
			foreach ($reponse['data'] as $value_inside_loop) {
				if($value_inside_loop['equipmentID'] != 11 && $value_inside_loop['equipmentID'] != 170 && $value_inside_loop['equipmentID'] != 178 && $value_inside_loop['equipmentID'] != 413 && $value_inside_loop['equipmentID'] != 141 && $value_inside_loop['equipmentID'] != 179 && $value_inside_loop['equipmentID'] != 174 && $value_inside_loop['equipmentID'] != 176)
				{
					$reponse['data'][$count] = $value_inside_loop;
					$reponse['data'][$count]['active_patient'] = 0;
					$patient_total_los = $patient_total_los + $value_inside_loop['length_of_stay'];
					$total_patient_days = $total_patient_days + $value_inside_loop['patient_days'];

					foreach($active_patients_list as $loop_active_patient)
					{
						if($loop_active_patient['patientID'] == $value_inside_loop['patientID'])
						{
							// 0 = Inactive, 1 = Active
							$reponse['data'][$count]['active_patient'] = 1;
						}
					}
					$count++;
				}
			}
			$reponse['total_los_for_today']['patient_total_los'] = $patient_total_los;
			$reponse['total_patient_days_for_today']['total_patient_days'] = $total_patient_days;
		}
		echo json_encode($reponse);
	}

	//for the load more - Lot # Tracking Section
	public function lot_no_tracking_load_more_new($lot_num)
	{
		$reponse = array("data" => array());
		$patient_total_los = 0;
		$total_patient_days = 0;
		if($this->input->is_ajax_request())
		{
			$lot_num = array(
				'lotNo' => $lot_num,
			);
			$count = 0;
			$active_patients_list = $this->order_model->list_active_patients();
			$reponse['query'] = $this->order_model->list_patients_with_lot_number($lot_num);
			foreach ($reponse['query'] as $value_inside_loop) {
				if($value_inside_loop['equipmentID'] == 11 || $value_inside_loop['equipmentID'] == 170 || $value_inside_loop['equipmentID'] == 178 || $value_inside_loop['equipmentID'] == 413 || $value_inside_loop['equipmentID'] == 141 || $value_inside_loop['equipmentID'] == 179 || $value_inside_loop['equipmentID'] == 174 || $value_inside_loop['equipmentID'] == 176)
				{
					$reponse['data'][$count] = $value_inside_loop;
					$reponse['data'][$count]['active_patient'] = 0 ;
					$patient_total_los = $patient_total_los + $value_inside_loop['length_of_stay'];
					$total_patient_days = $total_patient_days + $value_inside_loop['patient_days'];

					foreach($active_patients_list as $loop_active_patient)
					{
						if($loop_active_patient['patientID'] == $value_inside_loop['patientID'])
						{
							// 0 = Inactive, 1 = Active
							$reponse['data'][$count]['active_patient'] = 1;
						}
					}
					$count++;
				}
			}
		}
		$reponse['total_los_for_today']['patient_total_los'] = $patient_total_los;
		$reponse['total_patient_days_for_today']['total_patient_days'] = $total_patient_days;
		echo json_encode($reponse);
	}

	public function patient_profile($uniqueID,$hospiceID='')
	{
		$user_type = $this->session->userdata('account_type');

		//if patient has orders
		// $returns = $this->order_model->list_orders($uniqueID);
		// $informations = $this->order_model->get_order_info($uniqueID,$hospiceID);

		//if patient has no orders
		$informations = $this->order_model->get_patient_noorder_info($uniqueID,$hospiceID);

		$data['comments'] = $this->order_model->get_all_comments($uniqueID);

		$datas = array();
		// $data['summarys'] = $returns;
		$data['informations'] = $informations;

		$data['note_counts'] = $this->order_model->count_patient_comments($uniqueID);
		// $patientID = get_patientID($uniqueID,$hospiceID);
		$data['notes_p2'] = 0;
		$data['note_counts'] = 0;

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $uniqueID;

        $response = $this->order_model->get_orders($id);

        // $order_summary = $this->order_model->get_orders($id,$hospiceID); //added hospiceID as 2nd parameter. This is added 07/13/2015. Remove it it will cause error.

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        // $data['summaries'] = $order_summary;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $uniqueID;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        $data['hospiceID'] = $hospiceID;
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id);
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }

		$this->templating_library->set('title','Customer Order Summary');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/order_summary','pages/order_summary',$data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function get_customer_ordered_items($medical_record_id,$hospiceID,$patientID)
	{
		$order_summary = $this->order_model->get_orders_v2($medical_record_id,$hospiceID);
		$data['summaries'] = $order_summary;
		$data['patientID'] = $patientID;

		$this->templating_library->set_view('pages/customer_ordered_items','pages/customer_ordered_items',$data);
	}

	public function patient_vault_records()
	{
		$result = $this->order_model->list_confirmed_orders();
		$data['orders'] = ($result) ? $result : FALSE;

		$this->templating_library->set('title','Customer Vault');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/patient_vault','pages/patient_vault', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}


	//save patient record as draft MARJORIE
	public function save_patient_record_draft()
	{
		$id = $this->session->userdata('userID');

		$this->form_validation->set_rules('hospice_provider','Hospice Provider','required');

		$hospice_provider 			= $this->input->post('hospice_provider');

		//$hospice_staff_firstname 	= $this->input->post('hospice_staff_firstname');
		//$hospice_staff_lastname 	= $this->input->post('hospice_staff_lastname');
		//$hospice_staff_email		= $this->input->post('hospice_staff_email');
		//$hospice_telephone			= $this->input->post('hospice_telephone');
		//$hospice_cellphone			= $this->input->post('hospice_cellphone');
		$patient_medical_record_id	= $this->input->post('patient_medical_record_id');
		$patient_lastname			= $this->input->post('patient_lastname');
		$patient_firstname			= $this->input->post('patient_firstname');
		$patient_gender				= $this->input->post('patient_gender');
		$patient_height 			= $this->input->post('patient_height');
		$patient_weight 			= $this->input->post('patient_weight');
		$patient_residence 			= $this->input->post('patient_residence');
		$patient_address			= $this->input->post('patient_address');
		$patient_placenum			= $this->input->post('patient_placenum');
		$patient_city				= $this->input->post('patient_city');
		$patient_state				= $this->input->post('patient_state');
		$patient_postal_code		= $this->input->post('patient_postal_code');
		$patient_phone_num 			= $this->input->post('patient_phone_num');
		$patient_alt_phone_num		= $this->input->post('patient_alt_phone_num');
		$patient_kin 				= $this->input->post('patient_kin');
		$patient_relationship		= $this->input->post('patient_relationship');
		$patient_kin_phonenum		= $this->input->post('patient_kin_phonenum');


		if(!empty($hospice_provider) && !empty($patient_medical_record_id) && !empty($patient_lastname)  && !empty($patient_firstname) && !empty($patient_gender) && !empty($patient_height) && !empty($patient_weight) && !empty($patient_residence) && !empty($patient_address) && !empty($patient_city) && !empty($patient_state) && !empty($patient_postal_code) && !empty($patient_phone_num) && !empty($patient_alt_phone_num) && !empty($patient_kin) && !empty($patient_relationship) &&!empty($patient_kin_phonenum))
		{
			$patient = array(
				'p_fname'				=> $patient_firstname,
				'p_lname'				=> $patient_lastname,
				'p_height'				=> $patient_height,
				'p_weight'				=> $patient_weight,
				'p_street'				=> $patient_address,
				'p_placenum'			=> $patient_placenum,
				'p_city'				=> $patient_city,
				'p_state'				=> $patient_state,
				'p_postalcode'			=> $patient_postal_code,
				'p_phonenum'			=> $patient_phone_num,
				'p_altphonenum'			=> $patient_alt_phone_num,
				'p_nextofkin'			=> $patient_kin,
				'p_relationship'		=> $patient_relationship,
				'p_nextofkinnum'		=> $patient_kin_phonenum,
				'medical_record_id'		=> $patient_medical_record_id,
				'ordered_by'			=> $hospice_provider
			);

			//print_r($patient);

			$patient_result = $this->order_model->save_patient_info($patient);

			if($patient_result)
			{
				$address = array(
					'patient_id'	=> $patient_result,
					'street'		=> $patient_address,
					'placenum'		=> $patient_placenum,
					'city'			=> $patient_city,
					'state'			=> $patient_state,
					'postal_code'	=> $patient_postal_code,
					'type'			=> 0,
					'status'		=> 0
				);

				$address_id = $this->order_model->save_address($address);

				$this->response_code = 0;
				$this->response_message = "Successfully save customer record.";
			} else {
				$this->response_message = "Failed.";
			}

		} else {
			// $this->response_message = validation_errors('<span></span>');
			$this->response_message = "Failed to save customer record. Please complete the customer form before submitting.";
		}
		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;
		//print_r($hospice_provider."=".$patient_lastname);exit();
	}


	public function greetings()
	{
		$this->templating_library->set('title','Thank you for trusting Us!');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/greeting','pages/greeting');
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function search_patient($searchString='',$hospiceID='')
	{
		$post_data = $this->input->post();
		$createdby = $this->session->userdata('group_id');
		$return = "";

		if($this->session->userdata('account_type') == 'dme_admin')
		{
			$searches = $this->order_model->search_patient($searchString);
		}
		else
		{
			$searches = $this->order_model->search_patient_hospice($searchString,$createdby);
		}
		if($searches)
		{
			foreach ($searches as $search)
			{
				echo "<tr style='cursor:pointer;' class='pmrn_result form-control' title='".$search['medical_record_id']."' data-id='".$search['medical_record_id']."'><td>Customer MRN: ".$search['medical_record_id']."</td></tr>";
			}
		}
		else
		{
			echo "<tr style='cursor:pointer;' class='form-control'><td>No Results Returned.</td></tr>";
		}
	}

	//** This function kay para sa auto suggest and auto populate sa Patient Info base sa Patient Medical Record Num **//
	function return_patient_info($patientmrn)
	{
		$results = $this->order_model->get_patient_info($patientmrn);
		if($results)
		{
			foreach($results as $result)
			{
				$data = array(
					'p_fname' => $result['p_fname'],
					'p_lname' => $result['p_lname'],
					'p_height' => $result['p_height'],
					'p_weight' => $result['p_weight'],
					'p_street' => $result['p_street'],
					'p_placenum' => $result['p_placenum'],
					'p_city' => $result['p_city'],
					'p_state' => $result['p_state'],
					'p_postalcode' => $result['p_postalcode'],
					'p_phonenum' => $result['p_phonenum'],
					'p_altphonenum' => $result['p_altphonenum'],
					'p_nextofkin' => $result['p_nextofkin'],
					'p_relationship' => $result['p_relationship'],
					'relationship_gender' => $result['relationship_gender'],
					'p_nextofkinnum' => $result['p_nextofkinnum'],
				);
			}
		}
		else
		{
			return FALSE;
		}
		$data_encoded = json_encode($data);
		echo $data_encoded;
	}


	public function add_order()
	{
		$id = $this->session->userdata('userID');
		$account_type_name = $this->session->userdata('account_type');
		$staff_member_fname = $this->session->userdata('firstname');
		$staff_member_lname = $this->session->userdata('lastname');

		if($this->input->post())
		{
			$person_who_ordered = $this->session->userdata('email');
			$data_post = $this->input->post();

			$this->form_validation->set_rules('organization_id','Hospice Provider','required');
			$this->form_validation->set_rules('equipments[]','Items','required');
			$this->form_validation->set_rules('delivery_date','Delivery Date','required');
			$this->form_validation->set_rules('dropdown_deliver_type','Customer Residence','required');
			$this->form_validation->set_rules('patient_mrn','Customer Medical Record Number','required');

			$this->form_validation->set_rules('patient_lname','Customer Last Name','required');
			$this->form_validation->set_rules('patient_fname','Customer First Name','required');
			$this->form_validation->set_rules('patient_phone_num','Customer Phone No.','required');
			$account_type_sign = $data_post['account_type_sign'];
			if($account_type_sign == 1)
			{
				$this->form_validation->set_rules('patient_placenum','Customer Room #','required');
			}
			$this->form_validation->set_rules('patient_city','Customer City','required');
			$this->form_validation->set_rules('patient_state','Customer State','required');
			$this->form_validation->set_rules('patient_postalcode','Customer Postal Code','required');
			$this->form_validation->set_rules('patient_nextofkin','Customer Next of Kin','required');
			$this->form_validation->set_rules('patient_relationship','Relationship to Patient','required');
			$this->form_validation->set_rules('relationship_gender','Gender','required');
			$this->form_validation->set_rules('patient_nextofkinphonenum','Customer Next of Kin Phone No.','required');
			$this->form_validation->set_rules('person_placing_order_lname','Hospice Staff Member Creating Order First Name' ,'required');
			$this->form_validation->set_rules('person_placing_order_fname','Hospice Staff Member Creating Order Last Name','required');
			$this->form_validation->set_rules('email','Email Address','required');
			$this->form_validation->set_rules('patient_height','Customer Height','required');
			$this->form_validation->set_rules('patient_weight','Customer Weight','required');

			if($this->form_validation->run()===TRUE)
			{
				$unique_id = strtotime(date('Y-m-d H:i:s'));
				$medical_id = $data_post['patient_mrn'];
				$order_current_status = "pending";
	            if($data_post['send_to_confirm_work_order_sign_new_patient'] == 1)
	            {
	              $order_current_status = "tobe_confirmed";
	            }

				$patient_info = array(
					'p_fname'				=> $data_post['patient_fname'],
					'p_lname'				=> $data_post['patient_lname'],
					'p_height'				=> $data_post['patient_height'],
					'p_weight'				=> $data_post['patient_weight'],
					'p_street'				=> $data_post['p_address'],
					'p_placenum'			=> $data_post['patient_placenum'],
					'p_city'				=> $data_post['patient_city'],
					'p_state'				=> $data_post['patient_state'],
					'p_postalcode'			=> $data_post['patient_postalcode'],
					'p_phonenum'			=> $data_post['patient_phone_num'],
					'p_altphonenum'			=> $data_post['patient_alt_phonenum'],
					'p_nextofkin'	 		=> $data_post['patient_nextofkin'],
					'p_relationship'	 	=> $data_post['patient_relationship'],
					'relationship_gender'	=> $data_post['relationship_gender'],
					'p_nextofkinnum' 		=> $data_post['patient_nextofkinphonenum'],
					'ordered_by' 	 		=> $data_post['organization_id'],
					'medical_record_id'		=> $data_post['patient_mrn']
				);

				$save_patient_info = $this->order_model->save_patientinfo($patient_info);

				$data_insert_patient_address = array(
					'patient_id' 	=> $save_patient_info,
					'street'		=> $data_post['p_address'],
					'placenum'		=> $data_post['patient_placenum'],
					'city'			=> $data_post['patient_city'],
					'state'			=> $data_post['patient_state'],
					'postal_code'	=> $data_post['patient_postalcode'],
					'type'			=> 0
				);
				$addressID = $this->order_model->insert_patient_address($data_insert_patient_address);

				if($save_patient_info!=FALSE)
				{
					/*
					* order information
					*/
					$pickup_date 		= date($data_post['delivery_date']);
					$ordered_by  		= $id;
					$organization_id 	= $data_post['organization_id'];
					$activity_type		= 1; //delivery

					//order items
					$orders = array();
					$capped_o2_count = 0;
					$non_capped_o2_count = 0;
					$used_addressID = 0;
					foreach($data_post['equipments'] as $key=>$value)
					{
						if($value != 61 && $value != 29)
						{
							$orders[] = array(
								"patientID"				=> $save_patient_info,
								"equipmentID"			=> $value,
								"equipment_value"		=> 1,
								"pickup_date"			=> $pickup_date,
								"activity_typeid"		=> $activity_type,
								"organization_id"		=> $organization_id,
								"ordered_by"			=> $id,
								"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
								"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
								"who_ordered_email"		=> $data_post['email'],
								"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
								"staff_member_fname"	=> $staff_member_fname,
								"staff_member_lname"	=> $staff_member_lname,
								"comment"				=> $data_post['comment'],
								"uniqueID"				=> $unique_id,
								"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
								'medical_record_id' 	=> $data_post['patient_mrn'],
								"order_status"	        => $order_current_status,
								"addressID"	        	=> $addressID
							);
							$used_addressID = $addressID;
							if($value == 316 || $value == 325)
							{
								$capped_o2_count++;
								if($value == 316)
								{
									$ordered_o2_type = 5;
								}
								else
								{
									$ordered_o2_type = 10;
								}
							}
							else if($value == 334 || $value == 343)
							{
								$non_capped_o2_count++;
								if($value == 334)
								{
									$ordered_o2_type = 5;
								}
								else
								{
									$ordered_o2_type = 10;
								}
							}
						}
					}

					/*
					* @sub equipments
					*
					*/
					foreach($data_post['subequipment'] as $key=>$value)
					{
						if(in_array($key,$data_post['equipments']))
						{
							foreach($value as $sub_key=>$sub_value)
							{
								if($sub_key=="radio")
								{
									foreach($sub_value as $radio_value)
									{
										if($radio_value == 78 || $radio_value == 79)
                              			{
                              				if($capped_o2_count == 2)
                              				{
                              					if($radio_value == 78)
                              					{
                              						$ordered_o2_duration = 318;
                              						$ordered_o2_duration_2 = 327;
                              					}
                              					else
                              					{
                              						$ordered_o2_duration = 319;
                              						$ordered_o2_duration_2 = 328;
                              					}
                              					$orders_duration = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_duration,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;

												$orders_duration_2 = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_duration_2,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
                              				}
                              				else
                              				{

                              					if($radio_value == 78)
                              					{
                              						if($ordered_o2_type == 5)
	                              					{
	                              						$ordered_o2_duration = 318;
	                              					}
	                              					else
	                              					{
	                              						$ordered_o2_duration = 327;
	                              					}
                              					}
                              					else
                              					{
                              						if($ordered_o2_type == 5)
	                              					{
	                              						$ordered_o2_duration = 319;
	                              					}
	                              					else
	                              					{
	                              						$ordered_o2_duration = 328;
	                              					}
                              					}
                              					$orders_duration = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_duration,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
                              				}
                              			}
                              			else if($radio_value == 103 || $radio_value == 104)
                              			{
                              				if($non_capped_o2_count == 2)
                              				{
                              					if($radio_value == 103)
                              					{
                              						$ordered_o2_duration = 336;
                              						$ordered_o2_duration_2 = 345;
                              					}
                              					else
                              					{
                              						$ordered_o2_duration = 337;
                              						$ordered_o2_duration_2 = 346;
                              					}
                              					$orders_duration = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_duration,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;

												$orders_duration_2 = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_duration_2,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
                              				}
                              				else
                              				{
                              					if($radio_value == 103)
                              					{
                              						if($ordered_o2_type == 5)
	                              					{
	                              						$ordered_o2_duration = 336;
	                              					}
	                              					else
	                              					{
	                              						$ordered_o2_duration = 345;
	                              					}
                              					}
                              					else
                              					{
                              						if($ordered_o2_type == 5)
	                              					{
	                              						$ordered_o2_duration = 337;
	                              					}
	                              					else
	                              					{
	                              						$ordered_o2_duration = 346;
	                              					}
                              					}
                              					$orders_duration = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_duration,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
                              				}
                              			}
			                            else if($radio_value == 82 || $radio_value == 83 || $radio_value == 280)
			                            {
			                            	if($capped_o2_count == 2)
                                      		{
                                      			if($radio_value == 82)
		                                        {
		                                          	$ordered_o2_delivery_device = 320;
		                                          	$ordered_o2_delivery_device_2 = 329;
		                                        }
		                                        else if($radio_value == 83)
		                                        {
		                                        	$ordered_o2_delivery_device = 321;
		                                          	$ordered_o2_delivery_device_2 = 330;
		                                        }
		                                        else
		                                        {
		                                          	$ordered_o2_delivery_device = 322;
		                                          	$ordered_o2_delivery_device_2 = 331;
		                                        }
				                            	$orders_delivery_device = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_delivery_device,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;

												$orders_delivery_device_2 = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_delivery_device_2,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
                                      		}
                                      		else
                                      		{
                                      			if($radio_value == 82)
		                                        {
		                                          	if($ordered_o2_type == 5)
		                                          	{
		                                            	$ordered_o2_delivery_device = 320;
		                                          	}
		                                          	else
		                                          	{
		                                            	$ordered_o2_delivery_device = 329;
		                                          	}
		                                        }
		                                        else if($radio_value == 83)
		                                        {
		                                        	if($ordered_o2_type == 5)
		                                          	{
		                                            	$ordered_o2_delivery_device = 321;
		                                          	}
		                                          	else
		                                          	{
		                                            	$ordered_o2_delivery_device = 330;
		                                          	}
		                                        }
		                                        else
		                                        {
		                                          	if($ordered_o2_type == 5)
		                                          	{
		                                            	$ordered_o2_delivery_device = 322;
		                                          	}
		                                          	else
		                                          	{
		                                            	$ordered_o2_delivery_device = 331;
		                                          	}
		                                        }
		                                        $orders_delivery_device = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_delivery_device,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
                                      		}
			                           	}
			                           	else if($radio_value == 105 || $radio_value == 106 || $radio_value == 281)
			                           	{
			                           		if($non_capped_o2_count == 2)
                                      		{
                                      			if($radio_value == 105)
		                                        {
		                                          	$ordered_o2_delivery_device = 338;
		                                          	$ordered_o2_delivery_device_2 = 347;
		                                        }
		                                        else if($radio_value == 106)
		                                        {
		                                        	$ordered_o2_delivery_device = 339;
		                                          	$ordered_o2_delivery_device_2 = 348;
		                                        }
		                                        else
		                                        {
		                                          	$ordered_o2_delivery_device = 340;
		                                          	$ordered_o2_delivery_device_2 = 349;
		                                        }
				                            	$orders_delivery_device = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_delivery_device,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;

												$orders_delivery_device_2 = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_delivery_device_2,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
                                      		}
                                      		else
                                      		{
                                      			if($radio_value == 105)
	                                            {
	                                                if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_delivery_device = 338;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_delivery_device = 347;
	                                                }
	                                            }
	                                            else if($radio_value == 106)
	                                            {
	                                              if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_delivery_device = 339;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_delivery_device = 348;
	                                                }
	                                            }
	                                            else
	                                            {
	                                                if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_delivery_device = 340;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_delivery_device = 349;
	                                                }
	                                            }
	                                            $orders_delivery_device = array(
	                                                "patientID"       		=> $save_patient_info,
	                                                "equipmentID"     		=> $ordered_o2_delivery_device,
	                                                "equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
	                                            );
	                                            $used_addressID = $addressID;
                                      		}
			                           	}
		                                else if($radio_value == 241 || $radio_value == 242)
		                                {
		                                	if($capped_o2_count == 2)
		                                	{
		                                		if($radio_value == 241)
	                                            {
	                                                $ordered_o2_e_portable = 323;
	                                                $ordered_o2_e_portable_2 = 332;
	                                            }
	                                            else
	                                            {
	                                                $ordered_o2_e_portable = 324;
	                                                $ordered_o2_e_portable_2 = 333;
	                                            }

			                                	$orders_e_portable = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_e_portable,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;

												$orders_e_portable_2 = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_e_portable_2,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
		                                	}
		                                	else
		                                	{
		                                		if($radio_value == 241)
	                                            {
	                                              if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_e_portable = 323;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_e_portable = 332;
	                                                }
	                                            }
	                                            else
	                                            {
	                                                if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_e_portable = 324;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_e_portable = 333;
	                                                }
	                                            }
	                                            $orders_e_portable = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_e_portable,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
		                                	}
		                                }
		                                else if($radio_value == 243 || $radio_value == 244)
		                                {
		                                	if($non_capped_o2_count == 2)
                                          	{
			                                	if($radio_value == 243)
	                                            {
	                                                $ordered_o2_e_portable = 341;
	                                                $ordered_o2_e_portable_2 = 350;
	                                            }
	                                            else
	                                            {
	                                                $ordered_o2_e_portable = 342;
	                                                $ordered_o2_e_portable_2 = 351;
	                                            }
	                                            $orders_e_portable = array(
	                                              	"patientID"       		=> $save_patient_info,
	                                              	"equipmentID"     		=> $ordered_o2_e_portable,
	                                              	"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
	                                            );
	                                            $used_addressID = $addressID;

	                                            $orders_e_portable_2 = array(
	                                              	"patientID"       		=> $save_patient_info,
	                                              	"equipmentID"     		=> $ordered_o2_e_portable_2,
	                                              	"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
	                                            );
	                                            $used_addressID = $addressID;
	                                        }
	                                        else
	                                        {
	                                        	if($radio_value == 243)
	                                            {
	                                              if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_e_portable = 341;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_e_portable = 350;
	                                                }
	                                            }
	                                            else
	                                            {
	                                                if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_e_portable = 342;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_e_portable = 351;
	                                                }
	                                            }
	                                            $orders_e_portable = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $ordered_o2_e_portable,
													"equipment_value"		=> 1,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
	                                        }
		                                }
		                                else
		                                {
		                                	$new_equipment_value = 1;
		                                	if($radio_value == 457 || $radio_value == 458)
		                                	{
		                                		$new_equipment_value = 2;
		                                	}

					                    	// for sub-equipment that are radio type and not related to oxygen concentrator
					                    	$orders[] = array(
												"patientID"				=> $save_patient_info,
												"equipmentID"			=> $radio_value,
												"equipment_value"		=> $new_equipment_value,
												"pickup_date"			=> $pickup_date,
												"activity_typeid"		=> $activity_type,
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
												"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
												"who_ordered_email"		=> $data_post['email'],
												"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
												"staff_member_fname"	=> $staff_member_fname,
												"staff_member_lname"	=> $staff_member_lname,
												"comment"				=> $data_post['comment'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
												'medical_record_id'     => $data_post['patient_mrn'],
												"order_status"	        => $order_current_status,
												"addressID"	        	=> $addressID
											);
											$used_addressID = $addressID;
										}
									}
								}
								else
								{
									//this changes is to separate the two oxygen concentrator in the DB
									if($capped_o2_count > 0 || $non_capped_o2_count > 0)
									{
										if($sub_key != 80 && $sub_key != 81 && $sub_key != 101 && $sub_key != 102)
										{
											//this is for CPAP = IPAP if the equipment is ordered with oxygen concentrator
											if($sub_key == 114)
											{
												$orders[] = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $sub_key,
													"equipment_value"		=> $sub_value,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
											}
											else
											//this is for the BIPAP = IPAP if the equipment is ordered with oxygen concentrator
											if($sub_key == 109)
											{
												$orders[] = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $sub_key,
													"equipment_value"		=> $sub_value,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
											}
											else
											//this is for the BIPAP = EPAP if the equipment is ordered with oxygen concentrator
											if($sub_key == 110)
											{
												$orders[] = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $sub_key,
													"equipment_value"		=> $sub_value,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
											}
											else
											//this is for the BIPAP = RATE if the equipment is ordered with oxygen concentrator
											if($sub_key == 111)
											{
												if(strlen($sub_value) > 0)
												{
													if($sub_value == 0)
													{
														$orders[] = array(
															"patientID"				=> $save_patient_info,
															"equipmentID"			=> $sub_key,
															"equipment_value"		=> 0,
															"pickup_date"			=> $pickup_date,
															"activity_typeid"		=> $activity_type,
															"organization_id"		=> $organization_id,
															"ordered_by"			=> $id,
															"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
															"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
															"who_ordered_email"		=> $data_post['email'],
															"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
															"staff_member_fname"	=> $staff_member_fname,
															"staff_member_lname"	=> $staff_member_lname,
															"comment"				=> $data_post['comment'],
															"uniqueID"				=> $unique_id,
															"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
															'medical_record_id'     => $data_post['patient_mrn'],
															"order_status"	        => $order_current_status,
															"addressID"	        	=> $addressID
														);
														$used_addressID = $addressID;
													}
													else
													{
														$orders[] = array(
															"patientID"				=> $save_patient_info,
															"equipmentID"			=> $sub_key,
															"equipment_value"		=> $sub_value,
															"pickup_date"			=> $pickup_date,
															"activity_typeid"		=> $activity_type,
															"organization_id"		=> $organization_id,
															"ordered_by"			=> $id,
															"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
															"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
															"who_ordered_email"		=> $data_post['email'],
															"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
															"staff_member_fname"	=> $staff_member_fname,
															"staff_member_lname"	=> $staff_member_lname,
															"comment"				=> $data_post['comment'],
															"uniqueID"				=> $unique_id,
															"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
															'medical_record_id'     => $data_post['patient_mrn'],
															"order_status"	        => $order_current_status,
															"addressID"	        	=> $addressID
														);
														$used_addressID = $addressID;
													}
												}
											}
											else if($sub_key == 77)
											{
												if($capped_o2_count == 2)
												{
		                                            $ordered_o2_liter_flow = 317;
		                                            $ordered_o2_liter_flow_2 = 326;

		                                            $orders_liter_flow = array(
														"patientID"				=> $save_patient_info,
														"equipmentID"			=> $ordered_o2_liter_flow,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $pickup_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
														"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
														"who_ordered_email"		=> $data_post['email'],
														"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
														"staff_member_fname"	=> $staff_member_fname,
														"staff_member_lname"	=> $staff_member_lname,
														"comment"				=> $data_post['comment'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
														'medical_record_id'     => $data_post['patient_mrn'],
														"order_status"	        => $order_current_status,
														"addressID"	        	=> $addressID
													);
													$used_addressID = $addressID;

													$orders_liter_flow_2 = array(
														"patientID"				=> $save_patient_info,
														"equipmentID"			=> $ordered_o2_liter_flow_2,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $pickup_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
														"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
														"who_ordered_email"		=> $data_post['email'],
														"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
														"staff_member_fname"	=> $staff_member_fname,
														"staff_member_lname"	=> $staff_member_lname,
														"comment"				=> $data_post['comment'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
														'medical_record_id'     => $data_post['patient_mrn'],
														"order_status"	        => $order_current_status,
														"addressID"	        	=> $addressID
													);
													$used_addressID = $addressID;
												}
												else
												{
													if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_liter_flow = 317;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_liter_flow = 326;
	                                                }

													$orders_liter_flow = array(
														"patientID"				=> $save_patient_info,
														"equipmentID"			=> $ordered_o2_liter_flow,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $pickup_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
														"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
														"who_ordered_email"		=> $data_post['email'],
														"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
														"staff_member_fname"	=> $staff_member_fname,
														"staff_member_lname"	=> $staff_member_lname,
														"comment"				=> $data_post['comment'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
														'medical_record_id'     => $data_post['patient_mrn'],
														"order_status"	        => $order_current_status,
														"addressID"	        	=> $addressID
													);
													$used_addressID = $addressID;
												}
											}
											else if($sub_key == 100)
											{
												if($non_capped_o2_count == 2)
												{
		                                            $ordered_o2_liter_flow = 335;
		                                            $ordered_o2_liter_flow_2 = 344;

		                                            $orders_liter_flow = array(
														"patientID"				=> $save_patient_info,
														"equipmentID"			=> $ordered_o2_liter_flow,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $pickup_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
														"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
														"who_ordered_email"		=> $data_post['email'],
														"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
														"staff_member_fname"	=> $staff_member_fname,
														"staff_member_lname"	=> $staff_member_lname,
														"comment"				=> $data_post['comment'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
														'medical_record_id'     => $data_post['patient_mrn'],
														"order_status"	        => $order_current_status,
														"addressID"	        	=> $addressID
													);
													$used_addressID = $addressID;

													$orders_liter_flow_2 = array(
														"patientID"				=> $save_patient_info,
														"equipmentID"			=> $ordered_o2_liter_flow_2,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $pickup_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
														"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
														"who_ordered_email"		=> $data_post['email'],
														"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
														"staff_member_fname"	=> $staff_member_fname,
														"staff_member_lname"	=> $staff_member_lname,
														"comment"				=> $data_post['comment'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
														'medical_record_id'     => $data_post['patient_mrn'],
														"order_status"	        => $order_current_status,
														"addressID"	        	=> $addressID
													);
													$used_addressID = $addressID;
												}
												else
												{
													if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_liter_flow = 335;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_liter_flow = 344;
	                                                }

													$orders_liter_flow = array(
														"patientID"				=> $save_patient_info,
														"equipmentID"			=> $ordered_o2_liter_flow,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $pickup_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
														"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
														"who_ordered_email"		=> $data_post['email'],
														"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
														"staff_member_fname"	=> $staff_member_fname,
														"staff_member_lname"	=> $staff_member_lname,
														"comment"				=> $data_post['comment'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
														'medical_record_id'     => $data_post['patient_mrn'],
														"order_status"	        => $order_current_status,
														"addressID"	        	=> $addressID
													);
													$used_addressID = $addressID;
												}
											}
											else
											{
												if(empty($sub_value))
												{
													$sub_value = 1;
												}
												if($sub_key == 457 || $sub_key == 458)
												{
													$sub_value = 2;
												}
												//this are for equipments ordered together with oxygen concentrator
												$orders[] = array(
													"patientID"				=> $save_patient_info,
													"equipmentID"			=> $sub_key,
													"equipment_value"		=> $sub_value,
													"pickup_date"			=> $pickup_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
													"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
													"who_ordered_email"		=> $data_post['email'],
													"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
													"staff_member_fname"	=> $staff_member_fname,
													"staff_member_lname"	=> $staff_member_lname,
													"comment"				=> $data_post['comment'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
													'medical_record_id'     => $data_post['patient_mrn'],
													"order_status"	        => $order_current_status,
													"addressID"	        	=> $addressID
												);
												$used_addressID = $addressID;
											}
										}
									}else{
										if($sub_key == 111)
										{
											if(strlen($sub_value) > 0)
											{
												if($sub_value == 0)
												{
													$orders[] = array(
														"patientID"				=> $save_patient_info,
														"equipmentID"			=> $sub_key,
														"equipment_value"		=> 0,
														"pickup_date"			=> $pickup_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
														"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
														"who_ordered_email"		=> $data_post['email'],
														"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
														"staff_member_fname"	=> $staff_member_fname,
														"staff_member_lname"	=> $staff_member_lname,
														"comment"				=> $data_post['comment'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
														'medical_record_id'     => $data_post['patient_mrn'],
														"order_status"	        => $order_current_status,
														"addressID"	        	=> $addressID
													);
													$used_addressID = $addressID;
												}
												else
												{
													$orders[] = array(
														"patientID"				=> $save_patient_info,
														"equipmentID"			=> $sub_key,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $pickup_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
														"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
														"who_ordered_email"		=> $data_post['email'],
														"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
														"staff_member_fname"	=> $staff_member_fname,
														"staff_member_lname"	=> $staff_member_lname,
														"comment"				=> $data_post['comment'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
														'medical_record_id'     => $data_post['patient_mrn'],
														"order_status"	        => $order_current_status,
														"addressID"	        	=> $addressID
													);
													$used_addressID = $addressID;
												}
											}
										}
										else
										{
											if(empty($sub_value))
											{
												$sub_value = 1;
											}
											if($sub_key == 457 || $sub_key == 458)
											{
												$sub_value = 2;
											}
											$orders[] = array(
												"patientID"				=> $save_patient_info,
												"equipmentID"			=> $sub_key,
												"equipment_value"		=> $sub_value,
												"pickup_date"			=> $pickup_date,
												"activity_typeid"		=> $activity_type,
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['person_placing_order_fname'],
												"who_ordered_lname"		=> $data_post['person_placing_order_lname'],
												"who_ordered_email"		=> $data_post['email'],
												"who_ordered_cpnum"	    => $data_post['who_ordered_cpnum'],
												"staff_member_fname"	=> $staff_member_fname,
												"staff_member_lname"	=> $staff_member_lname,
												"comment"				=> $data_post['comment'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['dropdown_deliver_type'],
												'medical_record_id'     => $data_post['patient_mrn'],
												"order_status"	        => $order_current_status,
												"addressID"	        	=> $addressID
											);
											$used_addressID = $addressID;
										}
									}
								}
							}
						}
					}

					if($capped_o2_count > 0 || $non_capped_o2_count > 0)
					{
						$result = array();
						if($capped_o2_count == 2 || $non_capped_o2_count == 2)
						{
							$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow);
							$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow_2);
							$result['duration'] = $this->order_model->save_oxygen_order($orders_duration);
							$result['duration'] = $this->order_model->save_oxygen_order($orders_duration_2);
			                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device);
			                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device_2);
			                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable);
			                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable_2);
						}
						else
						{
							$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow);
							$result['duration'] = $this->order_model->save_oxygen_order($orders_duration);
			                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device);
			                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable);
						}
						if($result)
						{
							$saveorder = $result;
						}

						$saveorder = $this->order_model->saveorder($orders);
					}
					else
					{
						$saveorder = $this->order_model->saveorder($orders);
					}

					if($saveorder)
					{
						$insert_to_status = array(
							'order_uniqueID'      		=> $unique_id,
							'medical_record_id'   		=> $data_post['patient_mrn'],
							'patientID'			  		=> $save_patient_info,
							'status_activity_typeid' 	=> $activity_type,
							'order_status'   	  		=> $order_current_status,
							'addressID'   	  			=> $used_addressID,
							'pickup_date'   	  		=> $pickup_date,
							'organization_id'   	  	=> $organization_id,
							'ordered_by'   	  			=> $id,
							'date_ordered'				=> date("Y-m-d h:i:s"),
							'original_activity_typeid'  => 1,
							'actual_order_date'   	  	=> '0000-00-00'
						);

						$this->order_model->insert_to_status($insert_to_status);

						$this->response_code = 0;
						$this->response_message = "Customer Created Successfully.";

						/*
						*	For email
						*/

						if($account_type_name != "dme_admin" && $account_type_name != "dme_user")
						{
							$email_form = $this->form_email_temp($unique_id,1,TRUE);
							$this->load->config('email');
							$config =   $this->config->item('me_email');
							$this->load->library('email', $config);

							$this->email->from('orders@smarterchoice.us','AHMSLV');
							$this->email->to('orders@ahmslv.com');
							$this->email->cc('russel@smartstart.us');
							$this->email->subject('AHMSLV | Order Summary');
							$this->email->message($email_form);
							$this->email->send();
						}
					}
				}
				else
				{
					$this->response_message = "Error saving customer information.";
				}
			}
			else
			{
				$this->response_message = validation_errors('<span></span>');
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}
	//2 pickup
	//4 PT
	//5 Respite
	// if($activity_type==2)
	// {
	// 	$pickup_type_sub = array(
	// 		'pickup_sub' => $data_post['pickup_sub'],
	// 		'date'	 	 => "{$date_post['pickup_date']}",
	// 		'patientID'	 => $save_patient_info,
	// 		'uniqueID'	 => $unique_id
	// 	);
	// 	$this->order_model->save($pickup_type_sub,'dme_pickup');
	// }
	// else if($activity_type==4)
	// {
	// 	$ptmove_type_sub = array(
	// 		'ptmove_street'   => $data_post['ptmove_address'],
	// 		'ptmove_placenum' => $data_post['ptmove_placenum'],
	// 		'ptmove_city'     => $data_post['ptmove_city'],
	// 		'ptmove_state'    => $data_post['ptmove_state'],
	// 		'ptmove_postal'   => $data_post['ptmove_postalcode'],
	// 		'patientID'	 	  => $save_patient_info
	// 	);
	// 	$this->order_model->save($ptmove_type_sub,'dme_sub_ptmove');
	// }
	// else if($activity_type==5)
	// {
	// 	$respite_type_sub = array(
	// 		'respite_pickup_date'   => $data_post['respite_delivery_date'],
	// 		'respite_address'   	=> $data_post['respite_address'],
	// 		'respite_placenum'		=> $data_post['respite_placenum'],
	// 		'respite_city'     		=> $data_post['respite_city'],
	// 		'respite_state'    		=> $data_post['respite_state'],
	// 		'respite_postal'   		=> $data_post['respite_postalcode'],
	// 		'parentID'	 			=> $save_patient_info
	// 	);
	// 	$this->order_model->save($respite_type_sub,'dme_sub_respite');
	// }

  //                   $email_form = $this->form_email_temp($unique_id, TRUE);

						// $this->load->config('email');
      //                   $config =   $this->config->item('me_email');
      //                   $this->load->library('email', $config);
      //                   $this->email->from('orders@ahmslv.com','AHMSLV');
      //                   // $this->email->to('john@smartstartlv.com');
						// // $this->email->cc('Rchinaadvantage@aol.com');
						// $this->email->to('saavedra.ted@gmail.com');
						// $this->email->cc('ivan.kirby.colina@gmail.com');
      //                   $this->email->subject('AHMSLV | Order Summary');
      //                   $this->email->message($email_form);
      //                  	$this->email->send();





	public function form_email_temp($uniqueID,$act_id,$return=false)
	{
			$data['act_type_id'] = $act_id;

			$informations = $this->order_model->get_order_info_for_email($uniqueID);

			if($act_id == 2)
			{
				$activity_type_data = $this->order_model->get_act_type_pickup($uniqueID);
			}

			if($act_id == 3)
			{
				$activity_type_data = $this->order_model->get_act_type_exchange($uniqueID);
			}

			if($act_id == 4)
			{
				$activity_type_data = $this->order_model->get_act_type_ptmove($uniqueID);
			}

			if($act_id == 5)
			{
				$activity_type_data = $this->order_model->get_act_type_respite($uniqueID);
			}

			$data['informations'] = $informations;
			$data['activity_fields'] = $activity_type_data;

			$id = $uniqueID;
		    $response = $this->order_model->get_orders_email($uniqueID);
	        $orders = array();
	        if(!empty($response))
	        {
	            foreach($response as $key=>$value)
	            {
	                $cat_ = $value['type'];
	                if($value['parentID']==0)
	                {
	                    $orders[$cat_][trim($value['key_desc'])][] = $value;
	                }
	                else
	                {
	                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
	                }
	            }
	        }
			$data['orders'] = $orders;
			$content = '';

			$this->templating_library->set('title','Email for Order Summary');

			$top = $this->templating_library->set_view('common/head','common/head', $data, TRUE);
			$header = $this->templating_library->set_view('common/header','common/header', $data , TRUE);
			$content = $this->templating_library->set_view('pages/email_template', 'pages/email_template', $data , TRUE);
			$footer  =	$this->templating_library->set_view('common/footer','common/footer', $data ,TRUE);
			$bottom = $this->templating_library->set_view('common/foot','common/foot', $data ,TRUE);

			if($return)
			{
				$final = $top.$content;
				return $final;
			}
			else
			{
				echo $top.$header.$content.$footer.$bottom;
			}
	}


	public function insert_order_comments()
	{
		$this->form_validation->set_rules('comment','Comment','required|trim');
		$post_data = $this->input->post();

		$array_data = array(
			'comment'		 => $post_data['comment'],
			'order_uniqueID' => $post_data['order_uniqueID'],
			'userID'		 => $post_data['commented_by'],
			'userName'       => $post_data['commented_by_name']
		);

		if($this->form_validation->run() == TRUE)
		{
			$this->order_model->insert_order_comments($array_data);

			//** For the response (include_bottom.php)
			$this->response_code 		= 0;
			$this->response_message		= "Comment inserted successfully.";
		}
		else
		{
			$this->response_code 		= 1;
			$this->response_message		= "Please put comment if necessary.";
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function check_saved_weight($medical_id,$patientID,$unique_id,$equipmentID)
	{
		$result = $this->order_model->check_saved_weight($medical_id,$patientID,$unique_id,$equipmentID);
		echo json_encode($result);
	}

	public function count_order_comments($uniqueID)
	{
		$result = $this->order_model->count_order_comments($uniqueID);
		echo json_encode($result);
	}

	public function get_comments($uniqueID)
	{
		$data['uniqueID'] = $uniqueID;
		$data['comments'] = $this->order_model->get_all_comments($uniqueID);
		$this->load->view('pages/comment_thread', $data);
	}

	public function set_date_reschedule_onhold($uniqueID)
	{
		$data['sign'] = 1;
		$data['uniqueID'] = $uniqueID;
		$returned_status_id = get_status_id($uniqueID);
        $data['returned_date'] = get_reschreschedule_onhold_date($returned_status_id['statusID']);

		$this->load->view('pages/reschedule_onhold_date', $data);
	}

	public function change_reschreschedule_onhold_date($uniqueID)
	{
		$post_data = $this->input->post();
		$result_id = $this->order_model->get_order_on_order_status($uniqueID);

		$data = array(
				'date'		=> $post_data['reschedule_onhold_date']
		);
		$result = $this->order_model->change_reschreschedule_onhold_date($data,$result_id['statusID']);
		$this->response_code = 0;
		$this->response_date = $post_data['reschedule_onhold_date'];

		echo json_encode(array(
			"error"			=> $this->response_code,
			"date_returned"	=> $this->response_date
		));
	}

	public function set_o2_concentrator_follow_up_date($follow_up_id)
	{
		$data['follow_up_date'] = $this->order_model->get_o2_concentrator_follow_up_date($follow_up_id);
		$data['modal_sign'] = 1;

		$this->load->view('pages/common_modals', $data);
	}

	public function update_o2_concentrator_follow_up_date($follow_up_id)
	{
		$post_data = $this->input->post();

		$data = array(
			'follow_up_date' => date("Y-m-d", strtotime($post_data['follow_up_date']))
		);
		$result = $this->order_model->update_oxygen_concentrator_follow_up($follow_up_id,$data);

		$this->response_code = 0;

		echo json_encode(array(
			"error"			=> $this->response_code
		));
	}

	public function edit_patient_entry_time($patientID)
	{
		$result = get_patient_entry_date($patientID);
		$data['patient_entry_date'] = $result['date_created'];
		$data['patientID'] = $patientID;
		$this->load->view('pages/patient_entry_time', $data);
	}

	public function save_changes_patient_entry_date($patientID)
	{
		$post_data = $this->input->post();

		$data = array(
			"date_created" => $post_data['patient_entry_date']
		);
		$result = $this->order_model->save_changes_patient_entry_date($data,$patientID);

		$patient_first_order = get_patient_first_order($patientID);
      	$returned_data = get_all_patient_pickup($patientID);
      	$patient_info = get_patient_info($patientID);

      	$patient_los = 1;
      	$patient_days = 1;
        if(empty($returned_data))
        {
            $current_date = date("Y-m-d h:i:s");
            $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
            $answer_2 = $answer/86400;
            $patient_los = $patient_los+floor($answer_2);

            $month_created = date("m", strtotime($patient_info['date_created']));
            $current_month = date("m");
            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
            {
            	if($current_month == $month_created)
	            {
	            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
	            	$patient_days += 1;
	            }
	            else if($current_month > $month_created)
	            {
	            	$patient_days = date("d");
	            }
            }
            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
            {
            	$patient_days = date("d");
            }
        }
        else if(count($returned_data) == 1)
        {
        	if($returned_data[0]['pickup_sub'] != "not needed")
	        {
	        	$returned_query = check_order_after_all_pickup($returned_data[0]['orderID'], $returned_data[0]['uniqueID'], $returned_data[0]['patientID']);
                if(!empty($returned_query))
                {
                	if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $returned_data[0]['pickup_date'])
                	{
                		$current_date = date("Y-m-d h:i:s");
	                    $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
	                    $answer_2 = $answer/86400;
	                    $patient_los = $patient_los+floor($answer_2);

	                    $month_created = date("m", strtotime($patient_info['date_created']));
			            $current_month = date("m");
			            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
			            {
			            	if($current_month == $month_created)
				            {
				            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
				            	$patient_days += 1;
				            }
				            else if($current_month > $month_created)
				            {
				            	$patient_days = date("d");
				            }
			            }
			            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
			            {
			            	$patient_days = date("d");
			            }
                	}
                	else
                	{
                		$answer = strtotime($returned_data[0]['pickup_date'])-strtotime($patient_info['date_created']);
		                $answer_2 = $answer/86400;
		                $patient_los = $patient_los+floor($answer_2);

		                $month_created = date("m", strtotime($patient_info['date_created']));
		            	$patient_last_month = date("m", strtotime($returned_data[0]['pickup_date']));
		                if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($returned_data[0]['pickup_date'])))
	            		{
	            			if($patient_last_month == $month_created)
				            {
				            	$patient_days = (date("d", strtotime($returned_data[0]['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
				            	$patient_days += 1;
				            }
				            else if($patient_last_month > $month_created)
				            {
				            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
				            }
	            		}
	            		else if(date("Y", strtotime($returned_data[0]['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
			            {
			            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
			            }
                	}
                }
                else
                {
                	$answer = strtotime($returned_data[0]['pickup_date'])-strtotime($patient_info['date_created']);
	                $answer_2 = $answer/86400;
	                $patient_los = $patient_los+floor($answer_2);

	                $month_created = date("m", strtotime($patient_info['date_created']));
		            $patient_last_month = date("m", strtotime($returned_data[0]['pickup_date']));
		            if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($returned_data[0]['pickup_date'])))
            		{
            			if($patient_last_month == $month_created)
			            {
			            	$patient_days = (date("d", strtotime($returned_data[0]['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
			            	$patient_days += 1;
			            }
			            else if($patient_last_month > $month_created)
			            {
			            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
			            }
            		}
            		else if(date("Y", strtotime($returned_data[0]['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
		            {
		            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
		            }
                }
	        }
	        else
	        {
	        	$current_date = date("Y-m-d h:i:s");
                $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
                $answer_2 = $answer/86400;
                $patient_los = $patient_los+floor($answer_2);

                $month_created = date("m", strtotime($patient_info['date_created']));
	            $current_month = date("m");
	            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
            	{
            		if($current_month == $month_created)
		            {
		            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
		            	$patient_days += 1;
		            }
		            else if($current_month > $month_created)
		            {
		            	$patient_days = date("d");
		            }
            	}
            	else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
	            {
	            	$patient_days = date("d");
	            }
	        }
        }
        else
        {
        	$pickup_all_sign = 0;
        	$pickup_all_count_sign = 0;
        	foreach ($returned_data as $value_first_loop){
        		if($value_first_loop['pickup_date'] >= $patient_first_order['actual_order_date'])
        		{
        			if($value_first_loop['pickup_sub'] != "not needed")
	        		{
	        			$pickup_all_sign = 1;
	        			$pickup_all_count_sign++;
	        		}
        		}
        	}

        	if($pickup_all_sign == 0)
        	{
        		$current_date = date("Y-m-d h:i:s");
                $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
                $answer_2 = $answer/86400;
                $patient_los = $patient_los+floor($answer_2);

                $month_created = date("m", strtotime($patient_info['date_created']));
	            $current_month = date("m");
	            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
	            {
	            	if($current_month == $month_created)
		            {
		            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
		            	$patient_days += 1;
		            }
		            else if($current_month > $month_created)
		            {
		            	$patient_days = date("d");
		            }
	            }
	            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
	            {
	            	$patient_days = date("d");
	            }
        	}
        	// IF NAAY COMPLETE PICKUP
        	else
        	{
        		$pickup_order_count = 1;
	            $previous_pickup_indications = 0; // 1 for selected item(s) pickup, 2 for complete pickup
	            foreach ($returned_data as $value){

	                if($pickup_order_count == 1)
	                {
	                	if($value['pickup_sub'] != "not needed")
		        		{
		        			if($pickup_all_count_sign == 1)
		        			{
		        				$returned_query_inside = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
		        				if(date("Y-m-d", strtotime($returned_query_inside['date_ordered'])) > $value['pickup_date'])
		        				{
		        					$previous_pickup_indications = 2;
				                    $previous_orderID = $value['orderID'];
				                    $previous_uniqueID = $value['uniqueID'];
				                    $previous_ordered_date = $value['pickup_date'];
				                    $previous_date_ordered = $value['date_ordered'];
				                    $partial_patient_los_first = 1; // Back to 1
				                    $partial_patient_days_first = 1;
		        				}
		        				else
		        				{
		        					$previous_pickup_indications = 1;
		        					$previous_orderID = $value['orderID'];
				                    $previous_uniqueID = $value['uniqueID'];
				                    $previous_ordered_date = $value['pickup_date'];
				                    $previous_date_ordered = $value['date_ordered'];
				                    $answer = strtotime($value['pickup_date'])-strtotime($patient_first_order['actual_order_date']);
				                    $answer_2 = $answer/86400;
				                    $partial_patient_los_first = $patient_los+floor($answer_2);
				                    $partial_patient_days_first = 1;
		        				}
		        			}
		        			else
		        			{
		        				$previous_pickup_indications = 2;
			                    $previous_orderID = $value['orderID'];
			                    $previous_uniqueID = $value['uniqueID'];
			                    $previous_ordered_date = $value['pickup_date'];
			                    $previous_date_ordered = $value['date_ordered'];
			                    $partial_patient_los_first = 1; // Back to 1
			                    $partial_patient_days_first = 1;
		        			}
		        		}
		        		else
		        		{
		        			$answer = strtotime($value['pickup_date'])-strtotime($patient_first_order['actual_order_date']);
		                    $answer_2 = $answer/86400;
		                    $partial_patient_los_first = $patient_los+floor($answer_2);
		                    $previous_pickup_indications = 1;
		                    $previous_orderID = $value['orderID'];
		                    $previous_uniqueID = $value['uniqueID'];
		                    $previous_ordered_date = $value['pickup_date'];
		                    $previous_date_ordered = $value['date_ordered'];
		                    $partial_patient_days_first = 1;
		        		}
	                }
	                else
	                {
	                	if($value['pickup_sub'] != "not needed")
		        		{
		        			$previous_pickup_indications = 2;
		        			if(count($returned_data) == $pickup_order_count)
			                {
			                	$returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
			                	if(!empty($returned_query))
			                	{
			                		if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $value['pickup_date'])
			                		{
		                				$current_date = date("Y-m-d h:i:s");
					                    $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
					                    $answer_2 = $answer/86400;
					                    $patient_los = $patient_los+floor($answer_2);

					                    $month_created = date("m", strtotime($patient_info['date_created']));
							            $current_month = date("m");
							            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
							            {
							            	if($current_month == $month_created)
								            {
								            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
								            	$patient_days += 1;
								            }
								            else if($current_month > $month_created)
								            {
								            	$patient_days = date("d");
								            }
							            }
							            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
							            {
							            	$patient_days = date("d");
							            }
			                		}
			                		else
			                		{
			                			$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
				                        $answer_2 = $answer/86400;
				                        $patient_los = $partial_patient_los_first+floor($answer_2);

				                        $month_created = date("m", strtotime($patient_info['date_created']));
							            $patient_last_month = date("m", strtotime($value['pickup_date']));
							            if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($value['pickup_date'])))
					            		{
					            			if($patient_last_month == $month_created)
								            {
								            	$patient_days = (date("d", strtotime($value['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
								            	$patient_days += 1;
								            }
								            else if($patient_last_month > $month_created)
								            {
								            	$patient_days = date("d", strtotime($value['pickup_date']));
								            }
					            		}
					            		else if(date("Y", strtotime($value['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
							            {
							            	$patient_days = date("d", strtotime($value['pickup_date']));
							            }
			                		}
			                	}
			                	else
			                	{
			                		$answer = strtotime($value['pickup_date'])-strtotime($patient_info['date_created']);
					                $answer_2 = $answer/86400;
					                $patient_los = $patient_los+floor($answer_2);

					                $month_created = date("m", strtotime($patient_info['date_created']));
						            $current_month = date("m");
						            if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($value['pickup_date'])))
						            {
						            	if($current_month == $month_created)
							            {
							            	$patient_days = (date("d", strtotime($value['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
							            	$patient_days += 1;
							            }
							            else if($current_month > $month_created)
							            {
							            	$patient_days = date("d", strtotime($value['pickup_date']));
							            }
						            }
						            else if(date("Y", strtotime($value['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
						            {
						            	$patient_days = date("d", strtotime($value['pickup_date']));
						            }
			                	}
			                }
			                else
			                {
			                	$returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
		                		if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $value['pickup_date'])
		                		{
		                			$partial_patient_los_first = 1;
		                			$previous_date_ordered = $value['date_ordered'];
		                		}
		                		else
		                		{
		                			$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
			                        $answer_2 = $answer/86400;
			                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
		                		}
		                		$partial_patient_days_first = 1;
			                }
		        		}
		        		else
		        		{
		        			$previous_pickup_indications = 1;
		        			if(count($returned_data) == $pickup_order_count)
			                {
			                	$current_date = date("Y-m-d h:i:s");
			                	if($value['pickup_date'] > $current_date)
			                	{
			                		$answer = strtotime($current_date)-strtotime($previous_ordered_date);
			                		$answer_2 = $answer/86400;
			                        $patient_los = $partial_patient_los_first+floor($answer_2);
			                	}
			                	else
			                	{
			                		$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
			                		$answer_2 = $answer/86400;
			                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);

			                        $answer_sub = strtotime($current_date)-strtotime($value['pickup_date']);
			                        $answer_2_sub = $answer_sub/86400;
			                        $patient_los = $partial_patient_los_first+floor($answer_2_sub);
			                	}
			                }
			                else
			                {
			                	if($previous_pickup_indications == 1)
			                	{
			                		$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
			                        $answer_2 = $answer/86400;
			                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
			                	}
			                	else
			                	{
			                		$answer = strtotime($value['pickup_date'])-strtotime($patient_info['date_created']);
			                        $answer_2 = $answer/86400;
			                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
			                	}
			                	$previous_date_ordered = $value['date_ordered'];
			                }
			                $month_created = date("m", strtotime($patient_info['date_created']));
				            $current_month = date("m");
				            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
				            {
				            	if($current_month == $month_created)
					            {
					            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
					            	$patient_days += 1;
					            }
					            else if($current_month > $month_created)
					            {
					            	$patient_days = date("d");
					            }
				            }
				            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
				            {
				            	$patient_days = date("d");
				            }
		        		}
	              	}
	              	$pickup_order_count++;
	              	$previous_ordered_date = $value['pickup_date'];
	        	}
        	}
      	}
        /********** INDIVIDUAL UPDATE **********/
      	$new_data = array(
      		'length_of_stay'	=> $patient_los
      	);
      	$this->order_model->update_patient_los_per_patient($new_data,$patientID);

      	$new_data_patient_days = array(
      		'patient_days'	=> $patient_days
      	);
      	$this->order_model->update_patient_days_per_patient($new_data_patient_days,$patientID);
      	/********** INDIVIDUAL UPDATE ENDS HERE **********/

      	/********** GROUP UPDATE **********/
      	$new_total_los_for_hospice = $this->order_model->get_total_patient_los_per_hospice_updated($patient_info['ordered_by']);
      	$new_data_for_hospice = array(
      		'patient_total_los'	=> $new_total_los_for_hospice['total_patient_los']
      	);
      	$current_date = date("Y-m-d");
      	$this->order_model->update_patient_los_for_hospice($new_data_for_hospice,$patient_info['ordered_by'],$current_date);

      	$new_total_patient_days_for_hospice = $this->order_model->get_total_patient_days_per_hospice_updated($patient_info['ordered_by']);
      	$new_data_for_hospice_p2 = array(
      		'total_patient_days'	=> $new_total_patient_days_for_hospice['total_patient_days']
      	);
      	$this->order_model->update_patient_days_for_hospice($new_data_for_hospice_p2,$patient_info['ordered_by'],$current_date);
      	/********** GROUP UPDATE ENDS HERE **********/

		$this->response_code = 0;
		$this->response_message = "Successfully Saved.";
		echo json_encode(array(
			"error"			=> $this->response_code,
			"message"   => $this->response_message
		));
	}

	public function edit_order_pickup_date($patientID,$uniqueID)
	{
		$result = get_patient_pickup_order($patientID,$uniqueID);
		$data['patient_pickup_date'] = $result[0]['pickup_date'];
		$data['patientID'] = $patientID;
		$data['sign'] = 1;
		$this->load->view('pages/pop_up_content', $data);
	}

	public function save_changes_order_pickup_date($patientID)
	{
		$post_data = $this->input->post();

		$data = array(
			"pickup_date" => $post_data['order_pickup_date']
		);
		$result = $this->order_model->save_changes_order_pickup_date($data,$patientID);

		$data_2 = array(
			"date_pickedup" => $post_data['order_pickup_date']
		);
		$result = $this->order_model->save_changes_order_pickup_date_2($data_2,$patientID);



		$patient_first_order = get_patient_first_order($patientID);
      	$returned_data = get_all_patient_pickup($patientID);
      	$patient_info = get_patient_info($patientID);

      	$patient_los = 1;
      	$patient_days = 1;
        if(empty($returned_data))
        {
            $current_date = date("Y-m-d h:i:s");
            $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
            $answer_2 = $answer/86400;
            $patient_los = $patient_los+floor($answer_2);

            $month_created = date("m", strtotime($patient_info['date_created']));
            $current_month = date("m");
            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
            {
            	if($current_month == $month_created)
	            {
	            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
	            	$patient_days += 1;
	            }
	            else if($current_month > $month_created)
	            {
	            	$patient_days = date("d");
	            }
            }
            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
            {
            	$patient_days = date("d");
            }
        }
        else if(count($returned_data) == 1)
        {
        	if($returned_data[0]['pickup_sub'] != "not needed")
	        {
	        	$returned_query = check_order_after_all_pickup($returned_data[0]['orderID'], $returned_data[0]['uniqueID'], $returned_data[0]['patientID']);
                if(!empty($returned_query))
                {
                	if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $returned_data[0]['pickup_date'])
                	{
                		$current_date = date("Y-m-d h:i:s");
	                    $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
	                    $answer_2 = $answer/86400;
	                    $patient_los = $patient_los+floor($answer_2);

	                    $month_created = date("m", strtotime($patient_info['date_created']));
			            $current_month = date("m");
			            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
			            {
			            	if($current_month == $month_created)
				            {
				            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
				            	$patient_days += 1;
				            }
				            else if($current_month > $month_created)
				            {
				            	$patient_days = date("d");
				            }
			            }
			            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
			            {
			            	$patient_days = date("d");
			            }
                	}
                	else
                	{
                		$answer = strtotime($returned_data[0]['pickup_date'])-strtotime($patient_info['date_created']);
		                $answer_2 = $answer/86400;
		                $patient_los = $patient_los+floor($answer_2);

		                $month_created = date("m", strtotime($patient_info['date_created']));
		            	$patient_last_month = date("m", strtotime($returned_data[0]['pickup_date']));
		                if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($returned_data[0]['pickup_date'])))
	            		{
	            			if($patient_last_month == $month_created)
				            {
				            	$patient_days = (date("d", strtotime($returned_data[0]['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
				            	$patient_days += 1;
				            }
				            else if($patient_last_month > $month_created)
				            {
				            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
				            }
	            		}
	            		else if(date("Y", strtotime($returned_data[0]['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
			            {
			            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
			            }
                	}
                }
                else
                {
                	$answer = strtotime($returned_data[0]['pickup_date'])-strtotime($patient_info['date_created']);
	                $answer_2 = $answer/86400;
	                $patient_los = $patient_los+floor($answer_2);

	                $month_created = date("m", strtotime($patient_info['date_created']));
		            $patient_last_month = date("m", strtotime($returned_data[0]['pickup_date']));
		            if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($returned_data[0]['pickup_date'])))
            		{
            			if($patient_last_month == $month_created)
			            {
			            	$patient_days = (date("d", strtotime($returned_data[0]['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
			            	$patient_days += 1;
			            }
			            else if($patient_last_month > $month_created)
			            {
			            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
			            }
            		}
            		else if(date("Y", strtotime($returned_data[0]['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
		            {
		            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
		            }
                }
	        }
	        else
	        {
	        	$current_date = date("Y-m-d h:i:s");
                $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
                $answer_2 = $answer/86400;
                $patient_los = $patient_los+floor($answer_2);

                $month_created = date("m", strtotime($patient_info['date_created']));
	            $current_month = date("m");
	            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
            	{
            		if($current_month == $month_created)
		            {
		            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
		            	$patient_days += 1;
		            }
		            else if($current_month > $month_created)
		            {
		            	$patient_days = date("d");
		            }
            	}
            	else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
	            {
	            	$patient_days = date("d");
	            }
	        }
        }
        else
        {
        	$pickup_all_sign = 0;
        	$pickup_all_count_sign = 0;
        	foreach ($returned_data as $value_first_loop){
        		if($value_first_loop['pickup_date'] >= $patient_first_order['actual_order_date'])
        		{
        			if($value_first_loop['pickup_sub'] != "not needed")
	        		{
	        			$pickup_all_sign = 1;
	        			$pickup_all_count_sign++;
	        		}
        		}
        	}
        	if($pickup_all_sign == 0)
        	{
        		$current_date = date("Y-m-d h:i:s");
                $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
                $answer_2 = $answer/86400;
                $patient_los = $patient_los+floor($answer_2);

                $month_created = date("m", strtotime($patient_info['date_created']));
	            $current_month = date("m");
	            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
	            {
	            	if($current_month == $month_created)
		            {
		            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
		            	$patient_days += 1;
		            }
		            else if($current_month > $month_created)
		            {
		            	$patient_days = date("d");
		            }
	            }
	            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
	            {
	            	$patient_days = date("d");
	            }
        	}
        	// IF NAAY COMPLETE PICKUP
        	else
        	{
        		$pickup_order_count = 1;
	            $previous_pickup_indications = 0; // 1 for selected item(s) pickup, 2 for complete pickup
	            foreach ($returned_data as $value){

	                if($pickup_order_count == 1)
	                {
	                	if($value['pickup_sub'] != "not needed")
		        		{
		        			if($pickup_all_count_sign == 1)
		        			{
		        				$returned_query_inside = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
		        				if(date("Y-m-d", strtotime($returned_query_inside['date_ordered'])) > $value['pickup_date'])
		        				{
		        					$previous_pickup_indications = 2;
				                    $previous_orderID = $value['orderID'];
				                    $previous_uniqueID = $value['uniqueID'];
				                    $previous_ordered_date = $value['pickup_date'];
				                    $previous_date_ordered = $value['date_ordered'];
				                    $partial_patient_los_first = 1; // Back to 1
				                    $partial_patient_days_first = 1;
		        				}
		        				else
		        				{
		        					$previous_pickup_indications = 1;
		        					$previous_orderID = $value['orderID'];
				                    $previous_uniqueID = $value['uniqueID'];
				                    $previous_ordered_date = $value['pickup_date'];
				                    $previous_date_ordered = $value['date_ordered'];
				                    $answer = strtotime($value['pickup_date'])-strtotime($patient_first_order['actual_order_date']);
				                    $answer_2 = $answer/86400;
				                    $partial_patient_los_first = $patient_los+floor($answer_2);
				                    $partial_patient_days_first = 1;
		        				}
		        			}
		        			else
		        			{
		        				$previous_pickup_indications = 2;
			                    $previous_orderID = $value['orderID'];
			                    $previous_uniqueID = $value['uniqueID'];
			                    $previous_ordered_date = $value['pickup_date'];
			                    $previous_date_ordered = $value['date_ordered'];
			                    $partial_patient_los_first = 1; // Back to 1
			                    $partial_patient_days_first = 1;
		        			}
		        		}
		        		else
		        		{
		        			$answer = strtotime($value['pickup_date'])-strtotime($patient_first_order['actual_order_date']);
		                    $answer_2 = $answer/86400;
		                    $partial_patient_los_first = $patient_los+floor($answer_2);
		                    $previous_pickup_indications = 1;
		                    $previous_orderID = $value['orderID'];
		                    $previous_uniqueID = $value['uniqueID'];
		                    $previous_ordered_date = $value['pickup_date'];
		                    $previous_date_ordered = $value['date_ordered'];
		                    $partial_patient_days_first = 1;
		        		}
	                }
	                else
	                {
	                	if($value['pickup_sub'] != "not needed")
		        		{
		        			$previous_pickup_indications = 2;
		        			if(count($returned_data) == $pickup_order_count)
			                {
			                	$returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
			                	if(!empty($returned_query))
			                	{
			                		if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $value['pickup_date'])
			                		{
		                				$current_date = date("Y-m-d h:i:s");
					                    $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
					                    $answer_2 = $answer/86400;
					                    $patient_los = $patient_los+floor($answer_2);

					                    $month_created = date("m", strtotime($patient_info['date_created']));
							            $current_month = date("m");
							            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
							            {
							            	if($current_month == $month_created)
								            {
								            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
								            	$patient_days += 1;
								            }
								            else if($current_month > $month_created)
								            {
								            	$patient_days = date("d");
								            }
							            }
							            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
							            {
							            	$patient_days = date("d");
							            }
			                		}
			                		else
			                		{
			                			$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
				                        $answer_2 = $answer/86400;
				                        $patient_los = $partial_patient_los_first+floor($answer_2);

				                        $month_created = date("m", strtotime($patient_info['date_created']));
							            $patient_last_month = date("m", strtotime($value['pickup_date']));
							            if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($value['pickup_date'])))
					            		{
					            			if($patient_last_month == $month_created)
								            {
								            	$patient_days = (date("d", strtotime($value['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
								            	$patient_days += 1;
								            }
								            else if($patient_last_month > $month_created)
								            {
								            	$patient_days = date("d", strtotime($value['pickup_date']));
								            }
					            		}
					            		else if(date("Y", strtotime($value['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
							            {
							            	$patient_days = date("d", strtotime($value['pickup_date']));
							            }
			                		}
			                	}
			                	else
			                	{
			                		$answer = strtotime($value['pickup_date'])-strtotime($patient_info['date_created']);
					                $answer_2 = $answer/86400;
					                $patient_los = $patient_los+floor($answer_2);

					                $month_created = date("m", strtotime($patient_info['date_created']));
						            $current_month = date("m");
						            if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($value['pickup_date'])))
						            {
						            	if($current_month == $month_created)
							            {
							            	$patient_days = (date("d", strtotime($value['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
							            	$patient_days += 1;
							            }
							            else if($current_month > $month_created)
							            {
							            	$patient_days = date("d", strtotime($value['pickup_date']));
							            }
						            }
						            else if(date("Y", strtotime($value['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
						            {
						            	$patient_days = date("d", strtotime($value['pickup_date']));
						            }
			                	}
			                }
			                else
			                {
			                	$returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
		                		if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $value['pickup_date'])
		                		{
		                			$partial_patient_los_first = 1;
		                			$previous_date_ordered = $value['date_ordered'];
		                		}
		                		else
		                		{
		                			$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
			                        $answer_2 = $answer/86400;
			                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
		                		}
		                		$partial_patient_days_first = 1;
			                }
		        		}
		        		else
		        		{
		        			$previous_pickup_indications = 1;
		        			if(count($returned_data) == $pickup_order_count)
			                {
			                	$current_date = date("Y-m-d h:i:s");
			                	if($value['pickup_date'] > $current_date)
			                	{
			                		$answer = strtotime($current_date)-strtotime($previous_ordered_date);
			                		$answer_2 = $answer/86400;
			                        $patient_los = $partial_patient_los_first+floor($answer_2);
			                	}
			                	else
			                	{
			                		$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
			                		$answer_2 = $answer/86400;
			                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);

			                        $answer_sub = strtotime($current_date)-strtotime($value['pickup_date']);
			                        $answer_2_sub = $answer_sub/86400;
			                        $patient_los = $partial_patient_los_first+floor($answer_2_sub);
			                	}
			                }
			                else
			                {
			                	if($previous_pickup_indications == 1)
			                	{
			                		$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
			                        $answer_2 = $answer/86400;
			                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
			                	}
			                	else
			                	{
			                		$answer = strtotime($value['pickup_date'])-strtotime($patient_info['date_created']);
			                        $answer_2 = $answer/86400;
			                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
			                	}
			                	$previous_date_ordered = $value['date_ordered'];
			                }
			                $month_created = date("m", strtotime($patient_info['date_created']));
				            $current_month = date("m");
				            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
				            {
				            	if($current_month == $month_created)
					            {
					            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
					            	$patient_days += 1;
					            }
					            else if($current_month > $month_created)
					            {
					            	$patient_days = date("d");
					            }
				            }
				            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
				            {
				            	$patient_days = date("d");
				            }
		        		}
	              	}
	              	$pickup_order_count++;
	              	$previous_ordered_date = $value['pickup_date'];
	        	}
        	}
      	}

      	$new_data = array(
      		'length_of_stay'	=> $patient_los
      	);
      	$this->order_model->update_patient_los_per_patient($new_data,$patientID);

      	$new_total_los_for_hospice = $this->order_model->get_total_patient_los_per_hospice_updated($patient_info['ordered_by']);
      	$new_data_for_hospice = array(
      		'patient_total_los'	=> $new_total_los_for_hospice['total_patient_los']
      	);
      	$current_date = date("Y-m-d");
      	$this->order_model->update_patient_los_for_hospice($new_data_for_hospice,$patient_info['ordered_by'],$current_date);

      	/* ///////  RESPITE ////////// */
	  	$new_data_patient_days = array(
			'patient_days'	=> $patient_days
	  	);
	  	$this->order_model->update_patient_days_per_patient($new_data_patient_days,$patientID);

	  	$new_total_patient_days_for_hospice = $this->order_model->get_total_patient_days_per_hospice_updated($patient_info['ordered_by']);
	  	$new_data_for_hospice_patient_days = array(
	  		'total_patient_days'	=> $new_total_patient_days_for_hospice['total_patient_days']
	  	);
	  	$current_date = date("Y-m-d");
	  	$this->order_model->update_patient_days_for_hospice($new_data_for_hospice_patient_days,$patient_info['ordered_by'],$current_date);

		$this->response_code = 0;
		$this->response_message = "Successfully Saved.";

		echo json_encode(array(
			"error"			=> $this->response_code,
			"message"   => $this->response_message
		));
	}

	public function ask_reschreschedule_onhold_date($uniqueID)
	{
		$data['sign'] = 0;
		$data['uniqueID'] = $uniqueID;
		$this->load->view('pages/reschedule_onhold_date', $data);
	}

	public function insert_reschreschedule_onhold_date($uniqueID)
	{
		$post_data = $this->input->post();
		$result = $this->order_model->get_order_on_order_status($uniqueID);

		$returned_status_id = get_status_id($uniqueID);
        $data['returned_date'] = get_reschreschedule_onhold_date($returned_status_id['statusID']);

        if(!empty($data['returned_date']))
        {
        	$data = array(
				'date'		=> $post_data['reschedule_onhold_date']
			);
			$result_here = $this->order_model->change_reschreschedule_onhold_date($data,$returned_status_id['statusID']);
        }
        else
        {
        	$data = array(
					'statusID'	=> $result['statusID'],
					'date'		=> $post_data['reschedule_onhold_date']
			);
			$returned_id = $this->order_model->insert_reschreschedule_onhold_date($data);
        }

		$this->response_code = 0;
		$this->response_date = $post_data['reschedule_onhold_date'];

		echo json_encode(array(
			"error"			=> $this->response_code,
			"date_returned"	=> $this->response_date
		));
	}

	public function delete_reschreschedule_onhold_date($uniqueID)
	{
		$returned_status_id = get_status_id($uniqueID);
		$this->order_model->delete_reschreschedule_onhold_date($returned_status_id['statusID']);
	}

	public function pickup($unique_id="")
    {
        if(!empty($unique_id))
        {
            if($this->input->post())
            {
                $activity_type = $_POST['activity_type'];
                if($activity_type==2)
                {
                    $this->form_validation->set_rules("pickup_date","Date",'required');
                    $this->form_validation->set_rules("equipments[]","Equipments",'required');
                    $this->form_validation->set_rules("pickup_sub_cat","Pickup reason",'required');
                    if($this->form_validation->run()===TRUE)
                    {
                        $data_post = $this->input->post();
                        $data = array(
                                        "pickup_sub"    => $data_post['pickup_sub_cat'],
                                        "date"          => "{$data_post['pickup_date']}",
                                        "equipments"    => json_encode($data_post['equipments']),
                                        "orderUniqueID" => $unique_id,
                                        "pickup_status" => "pending"
                                    );
                        $save = $this->order_model->save_pickup_data($data,$unique_id);
                        if($save)
                        {
                            $this->response_code = 0;
                            $this->response_message = "Successfully saved.";
                        }
                        else
                        {
                            $this->response_message = "Failed to add. Please contact website administrator.";
                        }
                    }
                    else
                    {
                        $this->response_message = validation_errors("<span></span>");
                    }
                }
                else
                {
                    $this->response_message = "No received data.";
                }
            }
        }
        else
        {
            $this->response_message = "Not allowed";
        }
        echo json_encode(array(
            "error"     => $this->response_code,
            "message"   => $this->response_message
        ));
    }


    public function delete_order($uniqueID)
	{
		$results = $this->order_model->list_orders($uniqueID);

		$array = array();
		foreach($results as $result)
		{
			$array[] = array(
				"patientID"			=> $result['patientID'],
				"equipmentID"		=> $result['equipmentID'],
				"equipment_value"	=> $result['equipment_value'],
				"pickup_date"		=> $result['pickup_date'],
				"activity_typeid"	=> $result['activity_typeid'],
				"organization_id"	=> $result['organization_id'],
				"ordered_by"		=> $result['ordered_by'],
				"comment"			=> $result['comment'],
				"date_ordered"		=> $result['date_ordered'],
				"uniqueID"			=> $result['uniqueID'],
				"order_status"		=> $result['order_status'],
			);
		}

		$from_where_table = "dme_order";
		$array_encoded = json_encode($array);

		$inserted_data = array(
			"from_where_table" => $from_where_table,
			"data_deleted"	   => $array_encoded
		);

		$delete_entry = $this->order_model->delete_order($uniqueID);
		$trash_added = $this->order_model->save_to_trash($inserted_data);

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Successfully deleted.";

		echo json_encode(array(
					"error"		=> $this->response_code,
					"message"	=> $this->response_message
		));
	}



	public function delete_confirmed_order($uniqueID)
	{
		$results = $this->order_model->list_confirmed_orders($uniqueID);

		$array = array();
		foreach($results as $result)
		{
			$array[] = array(
				"patientID"			=> $result['patientID'],
				"equipmentID"		=> $result['equipmentID'],
				"equipment_value"	=> $result['equipment_value'],
				"pickup_date"		=> $result['pickup_date'],
				"activity_typeid"	=> $result['activity_typeid'],
				"organization_id"	=> $result['organization_id'],
				"ordered_by"		=> $result['ordered_by'],
				"comment"			=> $result['comment'],
				"date_ordered"		=> $result['date_ordered'],
				"uniqueID"			=> $result['uniqueID'],
				"order_status"		=> $result['order_status'],
			);
		}

		$from_where_table = "dme_order";
		$array_encoded = json_encode($array);

		$inserted_data = array(
			"from_where_table" => $from_where_table,
			"data_deleted"	   => $array_encoded
		);

		$delete_entry = $this->order_model->delete_order($uniqueID);
		$trash_added = $this->order_model->save_to_trash($inserted_data);

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Successfully deleted.";

		echo json_encode(array(
					"error"		=> $this->response_code,
					"message"	=> $this->response_message
		));
	}

	public function delete_trash($trashID)
	{
		if($trashID != '')
		{
			$this->order_model->delete_from_trash($trashID);
		}

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Permanently Deleted.";
		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function delete_patient_records($patientID,$medical_record_id)
	{
		if($medical_record_id != '')
		{
			$deleted = $this->order_model->delete_patient_records($patientID, $medical_record_id);

			if($deleted)
			{
				$this->response_code 		= 0;
				$this->response_message		= "Customer Records Deleted.";
			}
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function update_summary_tbl($uniqueID, $equipmentID)
	{
		$this->form_validation->set_rules("item_num","Item Number","xss_clean");
		$this->form_validation->set_rules("serial_num","Serial Number","xss_clean");
		$this->form_validation->set_rules("lot_num", "Lot Number", "xss_clean");

		$qty = $this->input->post("qty");
		$item_num = $this->input->post("item_num");
		$serial_num = $this->input->post("serial_num");
		$lot_num = $this->input->post("lot_num");
		$pickup_date = date($this->input->post("pickup_date"));
		$order_date = date($this->input->post("order_date"));

		if($this->form_validation->run() == TRUE)
		{
			$array = array(
				'equipment_value'=> $qty,
				'item_num'		 => $item_num,
				'serial_num'	 => $serial_num,
				'lot_num'		 => $lot_num,
				'summary_pickup_date' => $pickup_date,
				'pickup_date' => $order_date
			);

			$insert = $this->order_model->update_order_summary($uniqueID, $equipmentID ,$array);
			if($insert)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Updated Entry.";
			}
			else
			{
				$this->response_message = "Failed to update Entry.";
			}
		}
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}
		echo json_encode(array(
				"error" 	=> $this->response_code,
				"message"	=> $this->response_message
			));
		exit;
	}

	public function add_additional_equipments($patientID)
	{
		$account_type_name = $this->session->userdata('account_type');
		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_cpnum','Staff Cellphone No.','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('equipments[]','Add new item(s)','required');
		$this->form_validation->set_rules('activity_type','Activity Type','required');
		$this->form_validation->set_rules('delivery_date','Delivery Date','required');

		$person_who_ordered = $this->session->userdata('email');

		$id = $this->session->userdata('userID');
		if($this->input->post())
		{
			$data_post = $this->input->post();
			if($this->form_validation->run()===TRUE)
			{
				$unique_id = strtotime(date('Y-m-d H:i:s'));
				$medical_id = $data_post['medical_record_id'];

				$checked_unique_id = $this->order_model->checked_unique_id($unique_id);
				if(!empty($checked_unique_id))
				{
					$unique_id += 1;
				}

				if($unique_id!="")
				{
					/*
					* order information
					*/
					$delivery_date 		= date($data_post['delivery_date']);
					$ordered_by  		= $id;
					$organization_id 	= $data_post['organization_id'];
					$activity_type		= $data_post['activity_type'];
					$order_current_status = "pending";
					if($data_post['send_to_confirm_work_order_sign'] == 1)
					{
						$order_current_status = "tobe_confirmed";
					}

					//order items
					$orders = array();
					$capped_o2_count = 0;
					$non_capped_o2_count = 0;
					$used_addressID = 0;
					foreach($data_post['equipments'] as $key=>$value)
					{
						if($value != 61 && $value != 29)
						{
							$orders[] = array(

											"patientID"				=> $patientID,
											"equipmentID"			=> $value,
											"equipment_value"		=> 1,
											"pickup_date"			=> $delivery_date,
											"activity_typeid"		=> $activity_type,
											"organization_id"		=> $organization_id,
											"ordered_by"			=> $id,
											"who_ordered_fname"		=> $data_post['who_ordered_fname'],
											"who_ordered_lname"		=> $data_post['who_ordered_lname'],
											"staff_member_fname"	=> $data_post['staff_member_fname'],
											"staff_member_lname"	=> $data_post['staff_member_lname'],
											"who_ordered_email"		=> $data_post['who_ordered_email'],
											"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
											"comment"				=> $data_post['new_order_notes'],
											"uniqueID"				=> $unique_id,
											"deliver_to_type"	    => $data_post['delivery_to_type'],
											'medical_record_id' 	=> $data_post['medical_record_id'],
											"order_status"	        => $order_current_status,
											"addressID"				=> $data_post['delivery_address']
										);
							$used_addressID = $data_post['delivery_address'];
							if($value == 316 || $value == 325)
							{
								$capped_o2_count++;
								if($value == 316)
								{
									$ordered_o2_type = 5;
								}
								else
								{
									$ordered_o2_type = 10;
								}
							}
							else if($value == 334 || $value == 343)
							{
								$non_capped_o2_count++;
								if($value == 334)
								{
									$ordered_o2_type = 5;
								}
								else
								{
									$ordered_o2_type = 10;
								}
							}
						}
					}

					// if($data_post['commode_pail_counter'] > 1)
					// {
					// 	for ($i=2; $i <= $data_post['commode_pail_counter']; $i++) {
					// 		$orders[] = array(
					// 				"patientID"				=> $patientID,
					// 				"equipmentID"			=> 7,
					// 				"equipment_value"		=> 1,
					// 				"pickup_date"			=> $delivery_date,
					// 				"activity_typeid"		=> $activity_type,
					// 				"organization_id"		=> $organization_id,
					// 				"ordered_by"			=> $id,
					// 				"who_ordered_fname"		=> $data_post['who_ordered_fname'],
					// 				"who_ordered_lname"		=> $data_post['who_ordered_lname'],
					// 				"staff_member_fname"	=> $data_post['staff_member_fname'],
					// 				"staff_member_lname"	=> $data_post['staff_member_lname'],
					// 				"who_ordered_email"		=> $data_post['who_ordered_email'],
					// 				"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
					// 				"comment"				=> $data_post['new_order_notes'],
					// 				"uniqueID"				=> $unique_id,
					// 				"deliver_to_type"	    => $data_post['delivery_to_type'],
					// 				'medical_record_id' 	=> $data_post['medical_record_id'],
					// 				"order_status"	        => $order_current_status,
					// 				"addressID"				=> $data_post['delivery_address']
					// 			);
					// 	}
					// }

					/*
					* @sub equipments
					*
					*/
					foreach($data_post['subequipment'] as $key=>$value)
					{
						if(in_array($key,$data_post['equipments']))
						{
							foreach($value as $sub_key=>$sub_value)
							{
								if($sub_key=="radio")
								{
									foreach($sub_value as $radio_value)
									{
										if($radio_value == 78 || $radio_value == 79)
                              			{
                              				if($capped_o2_count == 2)
                              				{
                              					if($radio_value == 78)
                              					{
                              						$ordered_o2_duration = 318;
                              						$ordered_o2_duration_2 = 327;
                              					}
                              					else
                              					{
                              						$ordered_o2_duration = 319;
                              						$ordered_o2_duration_2 = 328;
                              					}
                              					$orders_duration = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_duration,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];

												$orders_duration_2 = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_duration_2,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
                              				}
                              				else
                              				{

                              					if($radio_value == 78)
                              					{
                              						if($ordered_o2_type == 5)
	                              					{
	                              						$ordered_o2_duration = 318;
	                              					}
	                              					else
	                              					{
	                              						$ordered_o2_duration = 327;
	                              					}
                              					}
                              					else
                              					{
                              						if($ordered_o2_type == 5)
	                              					{
	                              						$ordered_o2_duration = 319;
	                              					}
	                              					else
	                              					{
	                              						$ordered_o2_duration = 328;
	                              					}
                              					}
                              					$orders_duration = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_duration,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
                              				}
                              			}
                              			else if($radio_value == 103 || $radio_value == 104)
                              			{
                              				if($non_capped_o2_count == 2)
                              				{
                              					if($radio_value == 103)
                              					{
                              						$ordered_o2_duration = 336;
                              						$ordered_o2_duration_2 = 345;
                              					}
                              					else
                              					{
                              						$ordered_o2_duration = 337;
                              						$ordered_o2_duration_2 = 346;
                              					}
                              					$orders_duration = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_duration,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
                              					$used_addressID = $data_post['delivery_address'];

												$orders_duration_2 = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_duration_2,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
                              				}
                              				else
                              				{
                              					if($radio_value == 103)
                              					{
                              						if($ordered_o2_type == 5)
	                              					{
	                              						$ordered_o2_duration = 336;
	                              					}
	                              					else
	                              					{
	                              						$ordered_o2_duration = 345;
	                              					}
                              					}
                              					else
                              					{
                              						if($ordered_o2_type == 5)
	                              					{
	                              						$ordered_o2_duration = 337;
	                              					}
	                              					else
	                              					{
	                              						$ordered_o2_duration = 346;
	                              					}
                              					}
                              					$orders_duration = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_duration,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
                              				}
                              			}
			                            else if($radio_value == 82 || $radio_value == 83 || $radio_value == 280)
			                            {
			                            	if($capped_o2_count == 2)
                                      		{
                                      			if($radio_value == 82)
		                                        {
		                                          	$ordered_o2_delivery_device = 320;
		                                          	$ordered_o2_delivery_device_2 = 329;
		                                        }
		                                        else if($radio_value == 83)
		                                        {
		                                        	$ordered_o2_delivery_device = 321;
		                                          	$ordered_o2_delivery_device_2 = 330;
		                                        }
		                                        else
		                                        {
		                                          	$ordered_o2_delivery_device = 322;
		                                          	$ordered_o2_delivery_device_2 = 331;
		                                        }
				                            	$orders_delivery_device = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_delivery_device,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];

												$orders_delivery_device_2 = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_delivery_device_2,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
                                      		}
                                      		else
                                      		{
                                      			if($radio_value == 82)
		                                        {
		                                          	if($ordered_o2_type == 5)
		                                          	{
		                                            	$ordered_o2_delivery_device = 320;
		                                          	}
		                                          	else
		                                          	{
		                                            	$ordered_o2_delivery_device = 329;
		                                          	}
		                                        }
		                                        else if($radio_value == 83)
		                                        {
		                                        	if($ordered_o2_type == 5)
		                                          	{
		                                            	$ordered_o2_delivery_device = 321;
		                                          	}
		                                          	else
		                                          	{
		                                            	$ordered_o2_delivery_device = 330;
		                                          	}
		                                        }
		                                        else
		                                        {
		                                          	if($ordered_o2_type == 5)
		                                          	{
		                                            	$ordered_o2_delivery_device = 322;
		                                          	}
		                                          	else
		                                          	{
		                                            	$ordered_o2_delivery_device = 331;
		                                          	}
		                                        }
		                                        $orders_delivery_device = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_delivery_device,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
                                      		}
			                           	}
			                           	else if($radio_value == 105 || $radio_value == 106 || $radio_value == 281)
			                           	{
			                           		if($non_capped_o2_count == 2)
                                      		{
                                      			if($radio_value == 105)
		                                        {
		                                          	$ordered_o2_delivery_device = 338;
		                                          	$ordered_o2_delivery_device_2 = 347;
		                                        }
		                                        else if($radio_value == 106)
		                                        {
		                                        	$ordered_o2_delivery_device = 339;
		                                          	$ordered_o2_delivery_device_2 = 348;
		                                        }
		                                        else
		                                        {
		                                          	$ordered_o2_delivery_device = 340;
		                                          	$ordered_o2_delivery_device_2 = 349;
		                                        }
				                            	$orders_delivery_device = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_delivery_device,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];

												$orders_delivery_device_2 = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_delivery_device_2,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
                                      		}
                                      		else
                                      		{
                                      			if($radio_value == 105)
	                                            {
	                                                if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_delivery_device = 338;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_delivery_device = 347;
	                                                }
	                                            }
	                                            else if($radio_value == 106)
	                                            {
	                                              if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_delivery_device = 339;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_delivery_device = 348;
	                                                }
	                                            }
	                                            else
	                                            {
	                                                if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_delivery_device = 340;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_delivery_device = 349;
	                                                }
	                                            }
	                                            $orders_delivery_device = array(
	                                                "patientID"       		=> $patientID,
	                                                "equipmentID"     		=> $ordered_o2_delivery_device,
	                                                "equipment_value"  		=> 1,
	                                                "pickup_date"     		=> $delivery_date,
	                                                "activity_typeid"   	=> $activity_type,
	                                                "organization_id"   	=> $organization_id,
	                                                "ordered_by"      		=> $id,
	                                                "who_ordered_fname"   	=> $data_post['who_ordered_fname'],
	                                                "who_ordered_lname"   	=> $data_post['who_ordered_lname'],
	                                                "staff_member_fname"  	=> $data_post['staff_member_fname'],
	                                                "staff_member_lname"  	=> $data_post['staff_member_lname'],
	                                                "who_ordered_email"   	=> $data_post['who_ordered_email'],
	                                                "who_ordered_cpnum"   	=> $data_post['who_ordered_cpnum'],
	                                                "comment"       		=> $data_post['new_order_notes'],
	                                                "uniqueID"        		=> $unique_id,
	                                                "deliver_to_type"     	=> $data_post['delivery_to_type'],
	                                                'medical_record_id'   	=> $data_post['medical_record_id'],
	                                                "order_status"          => $order_current_status,
	                                                "addressID"       		=> $data_post['delivery_address']
	                                            );
	                                            $used_addressID = $data_post['delivery_address'];
                                      		}
			                           	}
		                                else if($radio_value == 241 || $radio_value == 242)
		                                {
		                                	if($capped_o2_count == 2)
		                                	{
		                                		if($radio_value == 241)
	                                            {
	                                                $ordered_o2_e_portable = 323;
	                                                $ordered_o2_e_portable_2 = 332;
	                                            }
	                                            else
	                                            {
	                                                $ordered_o2_e_portable = 324;
	                                                $ordered_o2_e_portable_2 = 333;
	                                            }

			                                	$orders_e_portable = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_e_portable,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];

												$orders_e_portable_2 = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_e_portable_2,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
		                                	}
		                                	else
		                                	{
		                                		if($radio_value == 241)
	                                            {
	                                              if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_e_portable = 323;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_e_portable = 332;
	                                                }
	                                            }
	                                            else
	                                            {
	                                                if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_e_portable = 324;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_e_portable = 333;
	                                                }
	                                            }
	                                            $orders_e_portable = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_e_portable,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
		                                	}
		                                }
		                                else if($radio_value == 243 || $radio_value == 244)
		                                {
		                                	if($non_capped_o2_count == 2)
                                          	{
			                                	if($radio_value == 243)
	                                            {
	                                                $ordered_o2_e_portable = 341;
	                                                $ordered_o2_e_portable_2 = 350;
	                                            }
	                                            else
	                                            {
	                                                $ordered_o2_e_portable = 342;
	                                                $ordered_o2_e_portable_2 = 351;
	                                            }
	                                            $orders_e_portable = array(
	                                              	"patientID"       		=> $patientID,
	                                              	"equipmentID"     		=> $ordered_o2_e_portable,
	                                              	"equipment_value"   	=> 1,
	                                              	"pickup_date"     		=> $delivery_date,
	                                              	"activity_typeid"   	=> $activity_type,
	                                              	"organization_id"   	=> $organization_id,
	                                              	"ordered_by"      		=> $id,
	                                              	"who_ordered_fname"   	=> $data_post['who_ordered_fname'],
	                                              	"who_ordered_lname"   	=> $data_post['who_ordered_lname'],
	                                              	"staff_member_fname"  	=> $data_post['staff_member_fname'],
	                                              	"staff_member_lname"  	=> $data_post['staff_member_lname'],
	                                              	"who_ordered_email"   	=> $data_post['who_ordered_email'],
	                                              	"who_ordered_cpnum"   	=> $data_post['who_ordered_cpnum'],
	                                              	"comment"       		=> $data_post['new_order_notes'],
	                                              	"uniqueID"        		=> $unique_id,
	                                              	"deliver_to_type"     	=> $data_post['delivery_to_type'],
	                                              	'medical_record_id'   	=> $data_post['medical_record_id'],
	                                              	"order_status"          => $order_current_status,
	                                              	"addressID"       		=> $data_post['delivery_address']
	                                            );
	                                            $used_addressID = $data_post['delivery_address'];

	                                            $orders_e_portable_2 = array(
	                                              	"patientID"       		=> $patientID,
	                                              	"equipmentID"     		=> $ordered_o2_e_portable_2,
	                                              	"equipment_value"   	=> 1,
	                                              	"pickup_date"     		=> $delivery_date,
	                                              	"activity_typeid"   	=> $activity_type,
	                                              	"organization_id"   	=> $organization_id,
	                                              	"ordered_by"      		=> $id,
	                                              	"who_ordered_fname"  	=> $data_post['who_ordered_fname'],
	                                              	"who_ordered_lname"   	=> $data_post['who_ordered_lname'],
	                                              	"staff_member_fname"  	=> $data_post['staff_member_fname'],
	                                              	"staff_member_lname"  	=> $data_post['staff_member_lname'],
	                                              	"who_ordered_email"   	=> $data_post['who_ordered_email'],
	                                              	"who_ordered_cpnum"   	=> $data_post['who_ordered_cpnum'],
	                                              	"comment"       		=> $data_post['new_order_notes'],
	                                              	"uniqueID"        		=> $unique_id,
	                                              	"deliver_to_type"     	=> $data_post['delivery_to_type'],
	                                              	'medical_record_id'   	=> $data_post['medical_record_id'],
	                                              	"order_status"          => $order_current_status,
	                                              	"addressID"       		=> $data_post['delivery_address']
	                                            );
	                                            $used_addressID = $data_post['delivery_address'];
	                                        }
	                                        else
	                                        {
	                                        	if($radio_value == 243)
	                                            {
	                                              if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_e_portable = 341;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_e_portable = 350;
	                                                }
	                                            }
	                                            else
	                                            {
	                                                if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_e_portable = 342;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_e_portable = 351;
	                                                }
	                                            }
	                                            $orders_e_portable = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $ordered_o2_e_portable,
													"equipment_value"		=> 1,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
	                                        }
		                                }
		                                else
		                                {
		                                	$new_equipment_value = 1;
		                                	if($radio_value == 457 || $radio_value == 458)
		                                	{
		                                		$new_equipment_value = 2;
		                                	}
		                                	$orders[] = array(
												"patientID"				=> $patientID,
												"equipmentID"			=> $radio_value,
												"equipment_value"		=> $new_equipment_value,
												"pickup_date"			=> $delivery_date,
												"activity_typeid"		=> $activity_type,
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['who_ordered_fname'],
												"who_ordered_lname"		=> $data_post['who_ordered_lname'],
												"staff_member_fname"	=> $data_post['staff_member_fname'],
												"staff_member_lname"	=> $data_post['staff_member_lname'],
												"who_ordered_email"		=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
												"comment"				=> $data_post['new_order_notes'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['delivery_to_type'],
												'medical_record_id' 	=> $data_post['medical_record_id'],
												"order_status"	        => $order_current_status,
												"addressID"				=> $data_post['delivery_address']
											);
											$used_addressID = $data_post['delivery_address'];
										}
									}
								}
								else
								{
									//this changes is to separate the two oxygen concentrator in the DB
									if($capped_o2_count > 0 || $non_capped_o2_count > 0)
									{
										if($sub_key != 80 && $sub_key != 81 && $sub_key != 101 && $sub_key != 102)
										{
											//this is for CPAP = IPAP if the equipment is ordered with oxygen concentrator
											if($sub_key == 114)
											{
												$orders[] = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $sub_key,
													"equipment_value"		=> $sub_value,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
											}
											else
											//this is for the BIPAP = IPAP if the equipment is ordered with oxygen concentrator
											if($sub_key == 109)
											{
												$orders[] = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $sub_key,
													"equipment_value"		=> $sub_value,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
											}
											else
											//this is for the BIPAP = EPAP if the equipment is ordered with oxygen concentrator
											if($sub_key == 110)
											{
												$orders[] = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $sub_key,
													"equipment_value"		=> $sub_value,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
											}
											else
											//this is for the BIPAP = RATE if the equipment is ordered with oxygen concentrator
											if($sub_key == 111)
											{
												if(strlen($sub_value) > 0)
												{
													if($sub_value == 0)
													{
														$orders[] = array(
															"patientID"				=> $patientID,
															"equipmentID"			=> $sub_key,
															"equipment_value"		=> 0,
															"pickup_date"			=> $delivery_date,
															"activity_typeid"		=> $activity_type,
															"organization_id"		=> $organization_id,
															"ordered_by"			=> $id,
															"who_ordered_fname"		=> $data_post['who_ordered_fname'],
															"who_ordered_lname"		=> $data_post['who_ordered_lname'],
															"staff_member_fname"	=> $data_post['staff_member_fname'],
															"staff_member_lname"	=> $data_post['staff_member_lname'],
															"who_ordered_email"		=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
															"comment"				=> $data_post['new_order_notes'],
															"uniqueID"				=> $unique_id,
															"deliver_to_type"	    => $data_post['delivery_to_type'],
															'medical_record_id' 	=> $data_post['medical_record_id'],
															"order_status"	        => $order_current_status,
															"addressID"				=> $data_post['delivery_address']
														);
														$used_addressID = $data_post['delivery_address'];
													}
													else
													{
														$orders[] = array(
															"patientID"				=> $patientID,
															"equipmentID"			=> $sub_key,
															"equipment_value"		=> $sub_value,
															"pickup_date"			=> $delivery_date,
															"activity_typeid"		=> $activity_type,
															"organization_id"		=> $organization_id,
															"ordered_by"			=> $id,
															"who_ordered_fname"		=> $data_post['who_ordered_fname'],
															"who_ordered_lname"		=> $data_post['who_ordered_lname'],
															"staff_member_fname"	=> $data_post['staff_member_fname'],
															"staff_member_lname"	=> $data_post['staff_member_lname'],
															"who_ordered_email"		=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
															"comment"				=> $data_post['new_order_notes'],
															"uniqueID"				=> $unique_id,
															"deliver_to_type"	    => $data_post['delivery_to_type'],
															'medical_record_id' 	=> $data_post['medical_record_id'],
															"order_status"	        => $order_current_status,
															"addressID"				=> $data_post['delivery_address']
														);
														$used_addressID = $data_post['delivery_address'];
													}
												}
											}
											else if($sub_key == 77)
											{
												if($capped_o2_count == 2)
												{
		                                            $ordered_o2_liter_flow = 317;
		                                            $ordered_o2_liter_flow_2 = 326;

		                                            $orders_liter_flow = array(
														"patientID"				=> $patientID,
														"equipmentID"			=> $ordered_o2_liter_flow,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $delivery_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['who_ordered_fname'],
														"who_ordered_lname"		=> $data_post['who_ordered_lname'],
														"staff_member_fname"	=> $data_post['staff_member_fname'],
														"staff_member_lname"	=> $data_post['staff_member_lname'],
														"who_ordered_email"		=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
														"comment"				=> $data_post['new_order_notes'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['delivery_to_type'],
														'medical_record_id' 	=> $data_post['medical_record_id'],
														"order_status"	        => $order_current_status,
														"addressID"				=> $data_post['delivery_address']
													);
													$used_addressID = $data_post['delivery_address'];

													$orders_liter_flow_2 = array(
														"patientID"				=> $patientID,
														"equipmentID"			=> $ordered_o2_liter_flow_2,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $delivery_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['who_ordered_fname'],
														"who_ordered_lname"		=> $data_post['who_ordered_lname'],
														"staff_member_fname"	=> $data_post['staff_member_fname'],
														"staff_member_lname"	=> $data_post['staff_member_lname'],
														"who_ordered_email"		=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
														"comment"				=> $data_post['new_order_notes'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['delivery_to_type'],
														'medical_record_id' 	=> $data_post['medical_record_id'],
														"order_status"	        => $order_current_status,
														"addressID"				=> $data_post['delivery_address']
													);
													$used_addressID = $data_post['delivery_address'];
												}
												else
												{
													if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_liter_flow = 317;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_liter_flow = 326;
	                                                }

													$orders_liter_flow = array(
														"patientID"				=> $patientID,
														"equipmentID"			=> $ordered_o2_liter_flow,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $delivery_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['who_ordered_fname'],
														"who_ordered_lname"		=> $data_post['who_ordered_lname'],
														"staff_member_fname"	=> $data_post['staff_member_fname'],
														"staff_member_lname"	=> $data_post['staff_member_lname'],
														"who_ordered_email"		=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
														"comment"				=> $data_post['new_order_notes'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['delivery_to_type'],
														'medical_record_id' 	=> $data_post['medical_record_id'],
														"order_status"	        => $order_current_status,
														"addressID"				=> $data_post['delivery_address']
													);
													$used_addressID = $data_post['delivery_address'];
												}
											}
											else if($sub_key == 100)
											{
												if($non_capped_o2_count == 2)
												{
		                                            $ordered_o2_liter_flow = 335;
		                                            $ordered_o2_liter_flow_2 = 344;

		                                            $orders_liter_flow = array(
														"patientID"				=> $patientID,
														"equipmentID"			=> $ordered_o2_liter_flow,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $delivery_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['who_ordered_fname'],
														"who_ordered_lname"		=> $data_post['who_ordered_lname'],
														"staff_member_fname"	=> $data_post['staff_member_fname'],
														"staff_member_lname"	=> $data_post['staff_member_lname'],
														"who_ordered_email"		=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
														"comment"				=> $data_post['new_order_notes'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['delivery_to_type'],
														'medical_record_id' 	=> $data_post['medical_record_id'],
														"order_status"	        => $order_current_status,
														"addressID"				=> $data_post['delivery_address']
													);
													$used_addressID = $data_post['delivery_address'];

													$orders_liter_flow_2 = array(
														"patientID"				=> $patientID,
														"equipmentID"			=> $ordered_o2_liter_flow_2,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $delivery_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['who_ordered_fname'],
														"who_ordered_lname"		=> $data_post['who_ordered_lname'],
														"staff_member_fname"	=> $data_post['staff_member_fname'],
														"staff_member_lname"	=> $data_post['staff_member_lname'],
														"who_ordered_email"		=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
														"comment"				=> $data_post['new_order_notes'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['delivery_to_type'],
														'medical_record_id' 	=> $data_post['medical_record_id'],
														"order_status"	        => $order_current_status,
														"addressID"				=> $data_post['delivery_address']
													);
													$used_addressID = $data_post['delivery_address'];
												}
												else
												{
													if($ordered_o2_type == 5)
	                                                {
	                                                  $ordered_o2_liter_flow = 335;
	                                                }
	                                                else
	                                                {
	                                                  $ordered_o2_liter_flow = 344;
	                                                }

													$orders_liter_flow = array(
														"patientID"				=> $patientID,
														"equipmentID"			=> $ordered_o2_liter_flow,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $delivery_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['who_ordered_fname'],
														"who_ordered_lname"		=> $data_post['who_ordered_lname'],
														"staff_member_fname"	=> $data_post['staff_member_fname'],
														"staff_member_lname"	=> $data_post['staff_member_lname'],
														"who_ordered_email"		=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
														"comment"				=> $data_post['new_order_notes'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['delivery_to_type'],
														'medical_record_id' 	=> $data_post['medical_record_id'],
														"order_status"	        => $order_current_status,
														"addressID"				=> $data_post['delivery_address']
													);
													$used_addressID = $data_post['delivery_address'];
												}
											}
											else if($sub_key == 457 || $sub_key == 458)
											{
												$orders[] = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $sub_key,
													"equipment_value"		=> 2,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
											}
											else
											{
												if($sub_key != 121 && $sub_key != 194)
												{
													if(empty($sub_value))
													{
														$sub_value = 1;
													}
												}
												//this are for equipments ordered together with oxygen concentrator
												$orders[] = array(
													"patientID"				=> $patientID,
													"equipmentID"			=> $sub_key,
													"equipment_value"		=> $sub_value,
													"pickup_date"			=> $delivery_date,
													"activity_typeid"		=> $activity_type,
													"organization_id"		=> $organization_id,
													"ordered_by"			=> $id,
													"who_ordered_fname"		=> $data_post['who_ordered_fname'],
													"who_ordered_lname"		=> $data_post['who_ordered_lname'],
													"staff_member_fname"	=> $data_post['staff_member_fname'],
													"staff_member_lname"	=> $data_post['staff_member_lname'],
													"who_ordered_email"		=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
													"comment"				=> $data_post['new_order_notes'],
													"uniqueID"				=> $unique_id,
													"deliver_to_type"	    => $data_post['delivery_to_type'],
													'medical_record_id' 	=> $data_post['medical_record_id'],
													"order_status"	        => $order_current_status,
													"addressID"				=> $data_post['delivery_address']
												);
												$used_addressID = $data_post['delivery_address'];
											}
										}
									}
									else
									{
										if($sub_key == 111)
										{
											if(strlen($sub_value) > 0)
											{
												if($sub_value == 0)
												{
													$orders[] = array(
														"patientID"				=> $patientID,
														"equipmentID"			=> $sub_key,
														"equipment_value"		=> 0,
														"pickup_date"			=> $delivery_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['who_ordered_fname'],
														"who_ordered_lname"		=> $data_post['who_ordered_lname'],
														"staff_member_fname"	=> $data_post['staff_member_fname'],
														"staff_member_lname"	=> $data_post['staff_member_lname'],
														"who_ordered_email"		=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
														"comment"				=> $data_post['new_order_notes'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['delivery_to_type'],
														'medical_record_id' 	=> $data_post['medical_record_id'],
														"order_status"	        => $order_current_status,
														"addressID"				=> $data_post['delivery_address']
													);
													$used_addressID = $data_post['delivery_address'];
												}
												else
												{

													$orders[] = array(
														"patientID"				=> $patientID,
														"equipmentID"			=> $sub_key,
														"equipment_value"		=> $sub_value,
														"pickup_date"			=> $delivery_date,
														"activity_typeid"		=> $activity_type,
														"organization_id"		=> $organization_id,
														"ordered_by"			=> $id,
														"who_ordered_fname"		=> $data_post['who_ordered_fname'],
														"who_ordered_lname"		=> $data_post['who_ordered_lname'],
														"staff_member_fname"	=> $data_post['staff_member_fname'],
														"staff_member_lname"	=> $data_post['staff_member_lname'],
														"who_ordered_email"		=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
														"comment"				=> $data_post['new_order_notes'],
														"uniqueID"				=> $unique_id,
														"deliver_to_type"	    => $data_post['delivery_to_type'],
														'medical_record_id' 	=> $data_post['medical_record_id'],
														"order_status"	        => $order_current_status,
														"addressID"				=> $data_post['delivery_address']
													);
													$used_addressID = $data_post['delivery_address'];
												}
											}
										}
										else
										{
											if($sub_key != 121 && $sub_key != 194)
											{
												if(empty($sub_value))
												{
													$sub_value = 1;
												}
											}
											if($sub_key == 457 || $sub_key == 458)
											{
												$sub_value = 2;
											}
											$orders[] = array(
												"patientID"				=> $patientID,
												"equipmentID"			=> $sub_key,
												"equipment_value"		=> $sub_value,
												"pickup_date"			=> $delivery_date,
												"activity_typeid"		=> $activity_type,
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['who_ordered_fname'],
												"who_ordered_lname"		=> $data_post['who_ordered_lname'],
												"staff_member_fname"	=> $data_post['staff_member_fname'],
												"staff_member_lname"	=> $data_post['staff_member_lname'],
												"who_ordered_email"		=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
												"comment"				=> $data_post['new_order_notes'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['delivery_to_type'],
												'medical_record_id' 	=> $data_post['medical_record_id'],
												"order_status"	        => $order_current_status,
												"addressID"				=> $data_post['delivery_address']
											);
											$used_addressID = $data_post['delivery_address'];
										}
									}
								}
							}
						}
					}

					if($capped_o2_count > 0 || $non_capped_o2_count > 0)
					{
						$result = array();
						if($capped_o2_count == 2 || $non_capped_o2_count == 2)
						{
							$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow);
							$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow_2);
							$result['duration'] = $this->order_model->save_oxygen_order($orders_duration);
							$result['duration'] = $this->order_model->save_oxygen_order($orders_duration_2);
			                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device);
			                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device_2);
			                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable);
			                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable_2);
						}
						else
						{
							$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow);
							$result['duration'] = $this->order_model->save_oxygen_order($orders_duration);
			                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device);
			                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable);
						}
						if($result)
						{
							$saveorder = $result;
						}

						$saveorder = $this->order_model->saveorder($orders);
					}
					else
					{
						$saveorder = $this->order_model->saveorder($orders);
					}

					if($saveorder)
					{
						$insert_to_status = array(
							'order_uniqueID'      		=> $unique_id,
							'medical_record_id'   		=> $data_post['medical_record_id'],
							'patientID'			  		=> $patientID,
							'status_activity_typeid' 	=> $activity_type,
							'order_status'   	  		=> $order_current_status,
							'addressID'   	  			=> $used_addressID,
							'pickup_date'   	  		=> $delivery_date,
							'organization_id'   	  	=> $organization_id,
							'ordered_by'   	  			=> $id,
							'date_ordered'				=> date("Y-m-d h:i:s"),
							'original_activity_typeid'  => 1,
							'actual_order_date'   	  	=> '0000-00-00'
						);

						$this->order_model->insert_to_status($insert_to_status);

						//2 pickup
						//4 PT
						//5 Respite
						if($activity_type==2)
						{
							$pickup_type_sub = array(
								'pickup_sub'   => $data_post['pickup_sub_cat'],
								'pickup_date' => date($data_post['pickup_pickup_date']),
								'medical_record_id'	 => $data_post['medical_record_id'],
								'order_uniqueID'	 => $unique_id
							);
							$this->order_model->save($pickup_type_sub,'dme_pickup_tbl');
						}
						else if($activity_type==4)
						{
							$ptmove_type_sub = array(
								'ptmove_street'   		=> $data_post['ptmove_address'],
								'ptmove_placenum' 		=> $data_post['ptmove_placenum'],
								'ptmove_city'     		=> $data_post['ptmove_city'],
								'ptmove_state'    		=> $data_post['ptmove_state'],
								'ptmove_postal'   		=> $data_post['ptmove_postalcode'],
								'medical_record_id'	 	=> $data_post['medical_record_id']
							);
							$this->order_model->save($ptmove_type_sub,'dme_sub_ptmove');
						}
						else if($activity_type==3)
						{
							$exchange_type_sub = array(
								'exchange_date'		 => date($data_post['exchange_date']),
								'exchange_reason'	 => $data_post['exchange_reason'],
								'medical_record_id'	 => $data_post['medical_record_id']
							);
							$this->order_model->save($exchange_type_sub,'dme_sub_exchange');
						}
						else if($activity_type==5)
						{
							$respite_type_sub = array(
								'respite_delivery_date'   => date($data_post['respite_delivery_date']),
								'respite_pickup_date'   => date($data_post['respite_pickup_date']),
								'respite_address'   	=> $data_post['respite_address'],
								'respite_placenum'		=> $data_post['respite_placenum'],
								'respite_city'     		=> $data_post['respite_city'],
								'respite_state'    		=> $data_post['respite_state'],
								'respite_postal'   		=> $data_post['respite_postalcode'],
								'medical_record_id'	 	=> $data_post['medical_record_id']
							);
							$this->order_model->save($respite_type_sub,'dme_sub_respite');
						}
						$this->response_code = 0;
						$this->response_message = "Activity submitted successfully.";


						/*
						*	For email
						*/


						if($account_type_name != "dme_admin" && $account_type_name != "dme_user")
						{
							$email_form = $this->form_email_temp($unique_id,1,TRUE);
							$this->load->config('email');
							$config =   $this->config->item('me_email');
							$this->load->library('email', $config);

							$this->email->from('orders@smarterchoice.us','AHMSLV');
							$this->email->to('orders@ahmslv.com');
							$this->email->cc('russel@smartstart.us');
							$this->email->subject('AHMSLV | Order Summary');
							$this->email->message($email_form);
							$this->email->send();
						}
					}
				}
				else
				{
					$this->response_message = "Error saving information.";
				}
			}
			else
			{
				$this->response_message = validation_errors('<span></span>');
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}

	public function update_status_to_pickup($patientID)
	{
		$account_type_name = $this->session->userdata('account_type');

		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('pickup_sub_cat','Pickup Reason','required');
		$this->form_validation->set_rules('pickup_pickup_date','Pickup Date','required');
		$this->form_validation->set_rules('activity_type','Activity Type','required');

		$id = $this->session->userdata('userID');

		if($this->input->post())
		{
			$person_who_ordered = $this->session->userdata('email');
			$data_post = $this->input->post();

			if($data_post['pickup_sign'] == 0)
			{
				$this->form_validation->set_rules('pickup_equipments[]','Items','required');
				if($this->form_validation->run()===TRUE)
				{
					$unique_id = strtotime(date('Y-m-d H:i:s'));
					$post_uniqueID  = $data_post['unique_id'];

					if(empty($data_post['pickup_respite_address']))
					{
						$pickup_respite_address = "";
					}
					else
					{
						$pickup_respite_address = $data_post['pickup_respite_address'];
					}

					if($post_uniqueID!="")
					{
						/*
						* order information
						*/
						$pickup_date 		= date($data_post['pickup_pickup_date']);
						$ordered_by  		= $id;
						$organization_id 	= $data_post['organization_id'];
						$activity_type		= 2;
						$unique_ids			= "";
						$order_current_status = "pending";
			            if($data_post['send_to_confirm_work_order_sign'] == 1)
			            {
			              $order_current_status = "tobe_confirmed";
			            }

						$equipment_ids = $data_post['pickup_equipments'];

						if(isset($data_post['hdn_pickup_uniqueID']))
						{
							$unique_ids    = $data_post['hdn_pickup_uniqueID'];
						}

						if(isset($data_post['hdn_uniqueid_ptmove']))
						{
							$ptmove_old_uniqueID = $data_post['hdn_uniqueid_ptmove'];
						}

						if(isset($data_post['ptmove_old_address']))
						{
							$ptmove_pickup_address_id = $data_post['ptmove_old_address'];
						}

						if(empty($data_post['pickup_respite_address']))
						{
							// IF WE WILL NOT PICKUP A RESPITE ORDER THEN GO HERE

							//order items
							$orders = array();
							$used_addressID = 0;
							foreach($data_post['pickup_equipments'] as $key=>$value)
							{
								if($value == 61 || $value == 29)
								{
									$orders_61 = array(

													"patientID"					=> $patientID,
													"equipmentID"				=> $value,
													"equipment_value"			=> 1,
													"pickup_date"				=> $pickup_date,
													"activity_typeid"			=> $activity_type,
													"organization_id"			=> $organization_id,
													"ordered_by"				=> $id,
													"who_ordered_fname"			=> $data_post['who_ordered_fname'],
													"who_ordered_lname"			=> $data_post['who_ordered_lname'],
													"staff_member_fname"		=> $data_post['staff_member_fname'],
													"staff_member_lname"		=> $data_post['staff_member_lname'],
													"who_ordered_email"			=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
													"comment"					=> $data_post['new_pickup_notes'],
													"uniqueID"					=> $unique_id,
													"deliver_to_type"	    	=> $data_post['delivery_to_type'],
													'medical_record_id' 		=> $data_post['medical_record_id'],
													"serial_num"				=> "pickup_order_only",
													"order_status"	        	=> $order_current_status,
													"pickup_order"				=> 1,
													"initial_order"				=> 0,
													"original_activity_typeid" 	=> 2,
													"activity_reference"		=> 2, //remove if it will cause errors (newly added)
													"addressID"					=> $data_post['pickup_address']
												);
									$used_addressID = $data_post['pickup_address'];
								}else if($value == 11 || $value == 170 || $value == 30){
									$value_equipment = get_value_of_equipment_controller($value, $patientID, $unique_ids[0]);

									$orders[] = array(
												"patientID"					=> $patientID,
												"equipmentID"				=> $value,
												"equipment_value"			=> $value_equipment['equipment_value'],
												"pickup_date"				=> $pickup_date,
												"activity_typeid"			=> $activity_type,
												"organization_id"			=> $organization_id,
												"ordered_by"				=> $id,
												"who_ordered_fname"			=> $data_post['who_ordered_fname'],
												"who_ordered_lname"			=> $data_post['who_ordered_lname'],
												"staff_member_fname"		=> $data_post['staff_member_fname'],
												"staff_member_lname"		=> $data_post['staff_member_lname'],
												"who_ordered_email"			=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
												"comment"					=> $data_post['new_pickup_notes'],
												"uniqueID"					=> $unique_id,
												"deliver_to_type"	    	=> $data_post['delivery_to_type'],
												'medical_record_id' 		=> $data_post['medical_record_id'],
												"serial_num"				=> "pickup_order_only",
												"order_status"	        	=> $order_current_status,
												"pickup_order"				=> 1,
												"initial_order"				=> 0,
												"original_activity_typeid" 	=> 2,
												"activity_reference"		=> 2, //remove if it will cause errors (newly added)
												"addressID"					=> $data_post['pickup_address']
											);
									$used_addressID = $data_post['pickup_address'];
								} else {
									$new_value_equipment = 1;
									if($value == 457 || $value == 458)
									{
										$new_value_equipment = 2;
									}
									$orders[] = array(
										"patientID"					=> $patientID,
										"equipmentID"				=> $value,
										"equipment_value"			=> $new_value_equipment,
										"pickup_date"				=> $pickup_date,
										"activity_typeid"			=> $activity_type,
										"organization_id"			=> $organization_id,
										"ordered_by"				=> $id,
										"who_ordered_fname"			=> $data_post['who_ordered_fname'],
										"who_ordered_lname"			=> $data_post['who_ordered_lname'],
										"staff_member_fname"		=> $data_post['staff_member_fname'],
										"staff_member_lname"		=> $data_post['staff_member_lname'],
										"who_ordered_email"			=> $data_post['who_ordered_email'],
										"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
										"comment"					=> $data_post['new_pickup_notes'],
										"uniqueID"					=> $unique_id,
										"deliver_to_type"	    	=> $data_post['delivery_to_type'],
										'medical_record_id' 		=> $data_post['medical_record_id'],
										"serial_num"				=> "pickup_order_only",
										"order_status"	        	=> $order_current_status,
										"pickup_order"				=> 1,
										"initial_order"				=> 0,
										"original_activity_typeid" 	=> 2,
										"activity_reference"		=> 2, //remove if it will cause errors (newly added)
										"addressID"					=> $data_post['pickup_address']
									);
									$used_addressID = $data_post['pickup_address'];
								}
							}

							/*
							* @sub equipments
							*
							*/
							if(!empty($data_post['equip_options']))
							{
								$item_description_count = 0;
								$saved_count = 0;
								$misc_quantity_count = 0;
								$saved_count_misc = 0;
								foreach($data_post['equip_options'] as $key=>$value)
								{
									if(in_array($key,$data_post['pickup_equipments']))
									{
										foreach($value as $sub_key=>$sub_value)
		              					{
		              						foreach($sub_value as $val)
		              						{
												//this changes is to separate the two oxygen concentrator in the DB
												if(!empty($orders_61))
												{
													if($val == 77 || $val == 100)
													{
														$get_value = get_value_equip($patientID,$val);
														$orders_77 = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $val,
															"equipment_value"			=> $get_value['equipment_value'],
															"pickup_date"				=> $pickup_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_pickup_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"serial_num"				=> "pickup_order_only",
															"order_status"	        	=> $order_current_status,
															"pickup_order"				=> 1,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 2,
															"activity_reference"		=> 2, //remove if it will cause errors (newly added)
															"addressID"					=> $data_post['pickup_address']
														);
														$used_addressID = $data_post['pickup_address'];
													}
													if($val == 80 || $val == 101)
													{
														$get_value = get_value_equip($patientID,$val);
														$orders_80 = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $val,
															"equipment_value"			=> $get_value['equipment_value'],
															"pickup_date"				=> $pickup_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_pickup_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"serial_num"				=> "pickup_order_only",
															"order_status"	        	=> $order_current_status,
															"pickup_order"				=> 1,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 2,
															"activity_reference"		=> 2, //remove if it will cause errors (newly added)
															"addressID"					=> $data_post['pickup_address']
														);
														$used_addressID = $data_post['pickup_address'];
													}
													if($val == 81 || $val == 102)
													{
														$get_value = get_value_equip($patientID,$val);
														$orders_81 = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $val,
															"equipment_value"			=> $get_value['equipment_value'],
															"pickup_date"				=> $pickup_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_pickup_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"serial_num"				=> "pickup_order_only",
															"order_status"	        	=> $order_current_status,
															"pickup_order"				=> 1,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 2,
															"activity_reference"		=> 2, //remove if it will cause errors (newly added)
															"addressID"					=> $data_post['pickup_address']
														);
														$used_addressID = $data_post['pickup_address'];
													}
												}else{
													if($val == 240)
													{
														$get_value = get_value_equip($patientID,$val);
														$orders[] = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $val,
															"equipment_value"			=> $get_value['equipment_value'],
															"pickup_date"				=> $pickup_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_pickup_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"serial_num"				=> "pickup_order_only",
															"order_status"	        	=> $order_current_status,
															"pickup_order"				=> 1,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 2,
															"activity_reference"		=> 2, //remove if it will cause errors (newly added)
															"addressID"					=> $data_post['pickup_address']
														);
														$used_addressID = $data_post['pickup_address'];
													}
													else if($val == 311)
													{
														$samp_final = "";
														$internal_count = 1;
														foreach ($unique_ids as $row) {
															$samp =  get_misc_item_description_v3($val,$row);
															if(!empty($samp))
															{
																if($item_description_count == 0)
																{
																	$samp_final = $samp;
																	$item_description_count++;
																	$saved_count++;
																	break;
																}
																else
																{
																	$saved_counts_v2 = $saved_count+1;
																	if($saved_counts_v2 == $internal_count)
																	{
																		$samp_final = $samp;
																		$saved_count++;
																		break;
																	}
																}
																$internal_count++;
															}
														}

														$orders[] = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $val,
															"equipment_value"			=> $samp_final,
															"pickup_date"				=> $pickup_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_pickup_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"serial_num"				=> "pickup_order_only",
															"order_status"	        	=> $order_current_status,
															"pickup_order"				=> 1,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 2,
															"activity_reference"		=> 2, //remove if it will cause errors (newly added)
															"addressID"					=> $data_post['pickup_address']
														);
														$used_addressID = $data_post['pickup_address'];
													}
													else if($val == 312)
													{
														$samp_final_misc = "";
														$internal_count = 1;
														foreach ($unique_ids as $row) {
															$samp =  get_misc_item_description_v3($val,$row);
															if(!empty($samp))
															{
																if($misc_quantity_count == 0)
																{
																	$samp_final_misc = $samp;
																	$misc_quantity_count++;
																	$saved_count_misc++;
																	break;
																}
																else
																{
																	$saved_counts_v2 = $saved_count_misc+1;
																	if($saved_counts_v2 == $internal_count)
																	{
																		$samp_final_misc = $samp;
																		$saved_count_misc++;
																		break;
																	}
																}
																$internal_count++;
															}
														}

														$orders[] = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $val,
															"equipment_value"			=> $samp_final_misc,
															"pickup_date"				=> $pickup_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_pickup_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"serial_num"				=> "pickup_order_only",
															"order_status"	        	=> $order_current_status,
															"pickup_order"				=> 1,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 2,
															"activity_reference"		=> 2, //remove if it will cause errors (newly added)
															"addressID"					=> $data_post['pickup_address']
														);
														$used_addressID = $data_post['pickup_address'];
													}
													else
													{
														$get_value = get_value_equip_new($patientID,$val,$unique_ids);
				              							$orders[] = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $val,
															"equipment_value"			=> $get_value['equipment_value'],
															"pickup_date"				=> $pickup_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_pickup_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"serial_num"				=> "pickup_order_only",
															"order_status"	        	=> $order_current_status,
															"pickup_order"				=> 1,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 2,
															"activity_reference"		=> 2, //remove if it will cause errors (newly added)
															"addressID"					=> $data_post['pickup_address']
														);
														$used_addressID = $data_post['pickup_address'];
													}
												}
		              						}
										}
									}
								}
							}

							if(!empty($orders_61))
							{
								$result = array();
								if(!empty($orders_80))
								{
									$result['parent'] = $this->order_model->save_oxygen_order($orders_61);
									$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_77);
									$result['LPM']	= $this->order_model->save_oxygen_order($orders_80);
									if($result)
									{
										$saveorder = $result;
									}
								}
								if(!empty($orders_81))
								{
									$result['parent'] = $this->order_model->save_oxygen_order($orders_61);
									$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_77);
									$result['LPM']	= $this->order_model->save_oxygen_order($orders_81);
									if($result)
									{
										$saveorder = $result;
									}
								}

								$saveorder = $this->order_model->saveorder($orders);
							}
							else
							{
								$saveorder = $this->order_model->saveorder($orders);
							}

							$activity_type_todo = 2;

							$updating_data = array(
								"activity_typeid" 		=> $activity_type,
								"date_ordered"			=> date('Y-m-d H:i:s'),
								"activity_reference"	=> 2,
								"pickedup_uniqueID"	 	=> $unique_id
							);

							$updateorder = $this->order_model->updateorder_pickup($patientID, $updating_data, $equipment_ids, $unique_ids, $activity_type_todo, $ptmove_old_uniqueID);

							if($updateorder)
							{
								$insert_to_status = array(
									'order_uniqueID'      		=> $unique_id,
									'medical_record_id'   		=> $data_post['medical_record_id'],
									'patientID'			 		=> $patientID,
									'status_activity_typeid' 	=> $activity_type,
									'order_status'   	  		=> $order_current_status,
									'addressID'   	  			=> $used_addressID,
									'pickup_date'   	  		=> $pickup_date,
									'organization_id'   	  	=> $organization_id,
									'ordered_by'   	  			=> $id,
									'date_ordered'				=> date("Y-m-d h:i:s"),
									'original_activity_typeid'  => 2,
									'actual_order_date'   	  	=> '0000-00-00'
								);
								$this->order_model->insert_to_status($insert_to_status);

								$pickup_type_sub = array(
									'pickup_sub'  		 		=> $data_post['pickup_sub_cat'],
									'date_pickedup' 		 	=> date($data_post['pickup_pickup_date']),
									'medical_record_id'	 		=> $data_post['medical_record_id'],
									'patientID'			 		=> $patientID,
									'pickup_respite_address' 	=> $pickup_respite_address,
									'order_uniqueID'	 		=> $unique_id
								);
								$this->order_model->save($pickup_type_sub,'dme_pickup_tbl');

								if($ptmove_pickup_address_id == 0)
								{
									if(isset($data_post['ptmove_new_address']))
									{
										$ptmove_new_address_id = $data_post['ptmove_new_address'];
									}

									$update_ptmove_entry = array(
										'ptmove_old_address_pickedup' => 1
									);
									$this->order_model->updateptmove_entry($ptmove_new_address_id,$update_ptmove_entry);
								}

								else
								{
									$update_ptmove_entry = array(
										'ptmove_pickedup' => 1,
										'ptmove_old_address_pickedup' => 1
									);
									$this->order_model->updateptmove_entry($ptmove_pickup_address_id,$update_ptmove_entry);
								}
							}

							$this->response_code = 0;
							$this->response_message = "Activity submitted successfully.";

							/*
							*	For email
							*/

							if($account_type_name != "dme_admin" && $account_type_name != "dme_user")
							{
								$email_form = $this->form_email_temp($unique_id,2,TRUE);
								$this->load->config('email');
								$config =   $this->config->item('me_email');
								$this->load->library('email', $config);

								$this->email->from('orders@smarterchoice.us','AHMSLV');
								$this->email->to('orders@ahmslv.com');
								$this->email->cc('russel@smartstart.us');
								$this->email->subject('AHMSLV | Order Summary');
								$this->email->message($email_form);
								$this->email->send();
							}
						}
					}
					else
					{
						$this->response_message = "Error saving information.";
					}
				}
				else
				{
					$this->response_message = validation_errors('<span></span>');
				}
			}
			else
			{
				if($this->form_validation->run()===TRUE)
				{
                    $post_uniqueID  = $data_post['unique_id'];

                    if($post_uniqueID!="")
                    {
						/*
                        * order information general
                        */
                        $pickup_date        = date($data_post['pickup_pickup_date']);
                        $ordered_by         = $id;
                        $organization_id    = $data_post['organization_id'];
                        $activity_type      = 2;
                        $order_current_status = "pending";
			            if($data_post['send_to_confirm_work_order_sign'] == 1)
			            {
			              $order_current_status = "tobe_confirmed";
			            }

                        /*********************************************************************
							START FOR INTIAL ADDRESS
                        *********************************************************************/

                        $unique_id     = strtotime(date('Y-m-d H:i:s'));
                        $unique_ids    = $data_post['hdn_pickup_uniqueID_initial'];
                        $equipment_ids = $data_post['pickup_equipments_initial'];
                        $patient_initial_address = get_old_address_new($patientID);

                        $orders = array();
                        $used_addressID = 0;
                        foreach($data_post['pickup_equipments_initial'] as $key=>$value)
                        {
                        	$orders[] = array(
	                                        "patientID"             	=> $patientID,
	                                        "equipmentID"           	=> $value,
	                                        "equipment_value"       	=> 1,
	                                        "pickup_date"           	=> $pickup_date,
	                                        "activity_typeid"       	=> $activity_type,
	                                        "organization_id"       	=> $organization_id,
	                                        "ordered_by"            	=> $id,
	                                        "who_ordered_fname"     	=> $data_post['who_ordered_fname'],
	                                        "who_ordered_lname"     	=> $data_post['who_ordered_lname'],
	                                        "staff_member_fname"    	=> $data_post['staff_member_fname'],
	                                        "staff_member_lname"    	=> $data_post['staff_member_lname'],
	                                        "who_ordered_email"     	=> $data_post['who_ordered_email'],
	                                        "who_ordered_cpnum"     	=> $data_post['who_ordered_cpnum'],
	                                        "comment"               	=> $data_post['new_pickup_notes'],
	                                        "uniqueID"              	=> $unique_id,
	                                        "deliver_to_type"       	=> $data_post['delivery_to_type'],
	                                        'medical_record_id'     	=> $data_post['medical_record_id'],
	                                        "serial_num"            	=> "pickup_order_only",
	                                        "order_status"          	=> $order_current_status,
	                                        "pickup_order"          	=> 1,
	                                        "initial_order"         	=> 0,
	                                        "original_activity_typeid" 	=> 2,
	                                        "activity_reference"       	=> 2, //remove if it will cause errors (newly added)
	                                    	"addressID"				   	=> $patient_initial_address['id']
	                                    );
                        	$used_addressID = $patient_initial_address['id'];
                        }
                        /*
                        * @sub equipments
                        *
                        */
                        if(!empty($data_post['equip_options_initial']))
                        {
                            foreach($data_post['equip_options_initial'] as $key=>$value)
                            {
                                if(in_array($key,$data_post['pickup_equipments_initial']))
                                {
                                    foreach($value as $sub_key=>$sub_value)
                                    {
                                        foreach($sub_value as $val)
                                        {
                                        	$get_value = get_value_equip_new_v2($patientID,$val,$unique_ids);
                                            $orders[] = array(
                                                "patientID"             	=> $patientID,
                                                "equipmentID"           	=> $val,
                                                "equipment_value"       	=> $get_value['equipment_value'],
                                                "pickup_date"           	=> $pickup_date,
                                                "activity_typeid"       	=> $activity_type,
                                                "organization_id"       	=> $organization_id,
                                                "ordered_by"            	=> $id,
                                                "who_ordered_fname"     	=> $data_post['who_ordered_fname'],
                                                "who_ordered_lname"     	=> $data_post['who_ordered_lname'],
                                                "staff_member_fname"    	=> $data_post['staff_member_fname'],
                                                "staff_member_lname"    	=> $data_post['staff_member_lname'],
                                                "who_ordered_email"     	=> $data_post['who_ordered_email'],
                                                "who_ordered_cpnum"     	=> $data_post['who_ordered_cpnum'],
                                                "comment"               	=> $data_post['new_pickup_notes'],
                                                "uniqueID"              	=> $unique_id,
                                                "deliver_to_type"       	=> $data_post['delivery_to_type'],
                                                'medical_record_id'     	=> $data_post['medical_record_id'],
                                                "serial_num"            	=> "pickup_order_only",
                                                "order_status"          	=> $order_current_status,
                                                "pickup_order"          	=> 1,
                                                "initial_order"         	=> 0,
                                                "original_activity_typeid" 	=> 2,
                                                "activity_reference"       	=> 2, //remove if it will cause errors (newly added)
                                            	"addressID"				   	=> $patient_initial_address['id']
                                            );
                                            $used_addressID = $patient_initial_address['id'];
                                        }
                                    }
                                }
                            }
                        }

                        $create_new_order = $this->order_model->saveorder($orders);

                        $activity_type_todo = 2;

                        $updating_data = array(
                            "activity_typeid"       => $activity_type,
                            "date_ordered"          => date('Y-m-d H:i:s'),
                            "activity_reference"    => 2,
                            "pickedup_uniqueID"     => $unique_id
                        );
                        $updateorder = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids, $unique_ids, $activity_type_todo);

                        if($updateorder)
                        {
                            $insert_to_status = array(
                                'order_uniqueID'      		=> $unique_id,
                                'medical_record_id'   		=> $data_post['medical_record_id'],
                                'patientID'          		=> $patientID,
                                'status_activity_typeid' 	=> $activity_type,
                                'order_status'        		=> $order_current_status,
                                'addressID'   	  			=> $used_addressID,
								'pickup_date'   	  		=> $pickup_date,
								'organization_id'   	  	=> $organization_id
                            );
                            $this->order_model->insert_to_status($insert_to_status);

                            $pickup_respite_address = "";
                            $pickup_type_sub = array(
                                'pickup_sub'         		=> $data_post['pickup_sub_cat'],
                                'date_pickedup'      		=> date($data_post['pickup_pickup_date']),
                                'medical_record_id'  		=> $data_post['medical_record_id'],
                                'patientID'          		=> $patientID,
                                'pickup_respite_address' 	=> $pickup_respite_address,
                                'order_uniqueID'     		=> $unique_id
                            );
                            $this->order_model->save($pickup_type_sub,'dme_pickup_tbl');
                        }

                        /*
                        *  For email
                        */

                        if($account_type_name != "dme_admin" && $account_type_name != "dme_user")
                        {
                            $email_form = $this->form_email_temp($unique_id,2,TRUE);
                            $this->load->config('email');
                            $config =   $this->config->item('me_email');
                            $this->load->library('email', $config);

                            $this->email->from('orders@smarterchoice.us','AHMSLV');
                            $this->email->to('orders@ahmslv.com');
                            $this->email->cc('russel@smartstart.us');
                            $this->email->subject('AHMSLV | Order Summary');
                            $this->email->message($email_form);
                            $this->email->send();
                        }

                        /******************************************************************
							END FOR INTIAL ADDRESS
                        *******************************************************************/

						/******************************************************************
							START FOR CUSTOMER MOVE ADDRESS
                        *******************************************************************/
						$patient_ptmove_addresses = get_patient_move_address_new($patientID);

						if($patient_ptmove_addresses)
						{
							foreach ($patient_ptmove_addresses as $ptmove_address) {
								$unique_id_ptmove 			= strtotime(date('Y-m-d H:i:s'));
								$hdn_pickup_uniqueID_ptmove = "hdn_pickup_uniqueID_ptmove_".$ptmove_address['id'];
		                        $pickup_equipments_ptmove	= "pickup_equipments_ptmove_".$ptmove_address['id'];
		                        $equip_options_ptmove 		= "equip_options_ptmove_".$ptmove_address['id'];

		                        $unique_id_ptmove 			= $unique_id_ptmove+1;
		                        $unique_ids_ptmove    		= $data_post[$hdn_pickup_uniqueID_ptmove];
		                        $equipment_ids_ptmove 		= $data_post[$pickup_equipments_ptmove];

		                        $orders = array();
		                        $used_addressID = 0;
		                        foreach($data_post[$pickup_equipments_ptmove] as $key=>$value)
		                        {
		                        	$orders[] = array(
			                                        "patientID"             	=> $patientID,
			                                        "equipmentID"           	=> $value,
			                                        "equipment_value"       	=> 1,
			                                        "pickup_date"           	=> $pickup_date,
			                                        "activity_typeid"       	=> $activity_type,
			                                        "organization_id"       	=> $organization_id,
			                                        "ordered_by"            	=> $id,
			                                        "who_ordered_fname"     	=> $data_post['who_ordered_fname'],
			                                        "who_ordered_lname"     	=> $data_post['who_ordered_lname'],
			                                        "staff_member_fname"    	=> $data_post['staff_member_fname'],
			                                        "staff_member_lname"    	=> $data_post['staff_member_lname'],
			                                        "who_ordered_email"     	=> $data_post['who_ordered_email'],
			                                        "who_ordered_cpnum"     	=> $data_post['who_ordered_cpnum'],
			                                        "comment"               	=> $data_post['new_pickup_notes'],
			                                        "uniqueID"              	=> $unique_id_ptmove,
			                                        "deliver_to_type"       	=> $data_post['delivery_to_type'],
			                                        'medical_record_id'     	=> $data_post['medical_record_id'],
			                                        "serial_num"            	=> "pickup_order_only",
			                                        "order_status"          	=> $order_current_status,
			                                        "pickup_order"          	=> 1,
			                                        "initial_order"         	=> 0,
			                                        "original_activity_typeid" 	=> 2,
			                                        "activity_reference"       	=> 2, //remove if it will cause errors (newly added)
			                                    	"addressID"				   	=> $ptmove_address['id']
			                                    );
		                        	$used_addressID = $ptmove_address['id'];
		                        }
		                        /*
		                        * @sub equipments
		                        *
		                        */
		                        if(!empty($data_post[$equip_options_ptmove]))
		                        {
		                            foreach($data_post[$equip_options_ptmove] as $key=>$value)
		                            {
		                                if(in_array($key,$data_post[$pickup_equipments_ptmove]))
		                                {
		                                    foreach($value as $sub_key=>$sub_value)
		                                    {
		                                        foreach($sub_value as $val)
		                                        {
		                                        	$get_value = get_value_equip_new_v2($patientID,$val,$unique_ids);
		                                            $orders[] = array(
		                                                "patientID"             	=> $patientID,
		                                                "equipmentID"           	=> $val,
		                                                "equipment_value"       	=> $get_value['equipment_value'],
		                                                "pickup_date"           	=> $pickup_date,
		                                                "activity_typeid"       	=> $activity_type,
		                                                "organization_id"       	=> $organization_id,
		                                                "ordered_by"            	=> $id,
		                                                "who_ordered_fname"     	=> $data_post['who_ordered_fname'],
		                                                "who_ordered_lname"     	=> $data_post['who_ordered_lname'],
		                                                "staff_member_fname"    	=> $data_post['staff_member_fname'],
		                                                "staff_member_lname"    	=> $data_post['staff_member_lname'],
		                                                "who_ordered_email"     	=> $data_post['who_ordered_email'],
		                                                "who_ordered_cpnum"     	=> $data_post['who_ordered_cpnum'],
		                                                "comment"               	=> $data_post['new_pickup_notes'],
		                                                "uniqueID"              	=> $unique_id_ptmove,
		                                                "deliver_to_type"       	=> $data_post['delivery_to_type'],
		                                                'medical_record_id'     	=> $data_post['medical_record_id'],
		                                                "serial_num"            	=> "pickup_order_only",
		                                                "order_status"          	=> $order_current_status,
		                                                "pickup_order"          	=> 1,
		                                                "initial_order"         	=> 0,
		                                                "original_activity_typeid" 	=> 2,
		                                                "activity_reference"       	=> 2, //remove if it will cause errors (newly added)
		                                            	"addressID"				   	=> $ptmove_address['id']
		                                            );
		                                            $used_addressID = $ptmove_address['id'];
		                                        }
		                                    }
		                                }
		                            }
		                        }

		                        $create_new_order = $this->order_model->saveorder($orders);

								$activity_type_todo = 2;

								$updating_data = array(
									"activity_typeid" 		=> $activity_type,
									"date_ordered"			=> date('Y-m-d H:i:s'),
									"activity_reference"	=> 2,
									"pickedup_uniqueID"	 	=> $unique_id_ptmove
								);

								$updateorder = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids_ptmove, $unique_ids_ptmove, $activity_type_todo);

								if($updateorder)
		                        {
		                            $insert_to_status = array(
		                                'order_uniqueID'      		=> $unique_id_ptmove,
		                                'medical_record_id'   		=> $data_post['medical_record_id'],
		                                'patientID'          		=> $patientID,
		                                'status_activity_typeid' 	=> $activity_type,
		                                'order_status'        		=> $order_current_status,
		                                'addressID'   	  			=> $used_addressID,
										'pickup_date'   	  		=> $pickup_date,
										'organization_id'   	  	=> $organization_id
		                            );
		                            $this->order_model->insert_to_status($insert_to_status);

		                            $pickup_respite_address = "";
		                            $pickup_type_sub = array(
		                                'pickup_sub'         		=> $data_post['pickup_sub_cat'],
		                                'date_pickedup'      		=> date($data_post['pickup_pickup_date']),
		                                'medical_record_id'  		=> $data_post['medical_record_id'],
		                                'patientID'          		=> $patientID,
		                                'pickup_respite_address' 	=> $pickup_respite_address,
		                                'order_uniqueID'     		=> $unique_id_ptmove
		                            );
		                            $this->order_model->save($pickup_type_sub,'dme_pickup_tbl');

		                            $ptmove_pickup_address_id 	= get_ptmove_address_through_address_name($patientID,$ptmove_address['street'],$ptmove_address['city']);
		                            $update_ptmove_entry = array(
								        'ptmove_pickedup' => 1,
								        'ptmove_old_address_pickedup' => 1
								    );
								    $this->order_model->updateptmove_entry($ptmove_pickup_address_id['ptmoveID'],$update_ptmove_entry);
		                        }

		                        /*
		                        *  For email
		                        */

		                        if($account_type_name != "dme_admin" && $account_type_name != "dme_user")
		                        {
		                            $email_form = $this->form_email_temp($unique_id,2,TRUE);
		                            $this->load->config('email');
		                            $config =   $this->config->item('me_email');
		                            $this->load->library('email', $config);

		                            $this->email->from('orders@smarterchoice.us','AHMSLV');
		                            $this->email->to('orders@ahmslv.com');
		                            $this->email->cc('russel@smartstart.us');
		                            $this->email->subject('AHMSLV | Order Summary');
		                            $this->email->message($email_form);
		                            $this->email->send();
		                        }
							}
						}

						/******************************************************************
							END FOR CUSTOMER MOVE ADDRESS
                        *******************************************************************/

						/******************************************************************
							START FOR PATIENT RESPITE ADDRESS
                        *******************************************************************/
						$patient_respite_addresses = get_respite_address_new($patientID);

						if($patient_respite_addresses)
						{
							foreach ($patient_respite_addresses as $respite_address) {
								$unique_id_respite 				= strtotime(date('Y-m-d H:i:s'));
								$hdn_pickup_uniqueID_respite 	= "hdn_pickup_uniqueID_respite_".$respite_address['id'];
		                        $pickup_equipments_respite		= "pickup_equipments_respite_".$respite_address['id'];
		                        $equip_options_respite 			= "equip_options_respite_".$respite_address['id'];

		                        $unique_id_respite 		= $unique_id_respite+1;
		                        $unique_ids_respite    	= $data_post[$hdn_pickup_uniqueID_respite];
		                        $equipment_ids_respite 	= $data_post[$pickup_equipments_respite];

		                        $orders = array();
		                        $used_addressID = 0;
		                        foreach($data_post[$pickup_equipments_respite] as $key=>$value)
		                        {
		                        	$orders[] = array(
			                                        "patientID"             	=> $patientID,
			                                        "equipmentID"           	=> $value,
			                                        "equipment_value"       	=> 1,
			                                        "pickup_date"           	=> $pickup_date,
			                                        "activity_typeid"       	=> $activity_type,
			                                        "organization_id"       	=> $organization_id,
			                                        "ordered_by"            	=> $id,
			                                        "who_ordered_fname"     	=> $data_post['who_ordered_fname'],
			                                        "who_ordered_lname"     	=> $data_post['who_ordered_lname'],
			                                        "staff_member_fname"    	=> $data_post['staff_member_fname'],
			                                        "staff_member_lname"    	=> $data_post['staff_member_lname'],
			                                        "who_ordered_email"     	=> $data_post['who_ordered_email'],
			                                        "who_ordered_cpnum"     	=> $data_post['who_ordered_cpnum'],
			                                        "comment"               	=> $data_post['new_pickup_notes'],
			                                        "uniqueID"              	=> $unique_id_respite,
			                                        "deliver_to_type"       	=> $data_post['delivery_to_type'],
			                                        'medical_record_id'     	=> $data_post['medical_record_id'],
			                                        "serial_num"            	=> "pickup_order_only",
			                                        "order_status"          	=> $order_current_status,
			                                        "pickup_order"          	=> 1,
			                                        "initial_order"         	=> 0,
			                                        "pickedup_respite_order"	=> 1,
			                                        "original_activity_typeid" 	=> 2,
			                                        "activity_reference"       	=> 2, //remove if it will cause errors (newly added)
			                                    	"addressID"				  	=> $respite_address['id']
			                                    );
		                        	$used_addressID = $respite_address['id'];
		                        }
		                        /*
		                        * @sub equipments
		                        *
		                        */
		                        if(!empty($data_post[$equip_options_respite]))
		                        {
		                            foreach($data_post[$equip_options_respite] as $key=>$value)
		                            {
		                                if(in_array($key,$data_post[$pickup_equipments_respite]))
		                                {
		                                    foreach($value as $sub_key=>$sub_value)
		                                    {
		                                    	if($sub_key=="radio")
												{
													foreach($sub_value as $radio_value)
    												{
			                                            $orders[] = array(
						                                                "patientID"             	=> $patientID,
						                                                "equipmentID"           	=> $radio_value,
						                                                "equipment_value"       	=> 1,
						                                                "pickup_date"           	=> $pickup_date,
						                                                "activity_typeid"       	=> $activity_type,
						                                                "organization_id"       	=> $organization_id,
						                                                "ordered_by"            	=> $id,
						                                                "who_ordered_fname"     	=> $data_post['who_ordered_fname'],
						                                                "who_ordered_lname"     	=> $data_post['who_ordered_lname'],
						                                                "staff_member_fname"    	=> $data_post['staff_member_fname'],
						                                                "staff_member_lname"    	=> $data_post['staff_member_lname'],
						                                                "who_ordered_email"     	=> $data_post['who_ordered_email'],
						                                                "who_ordered_cpnum"     	=> $data_post['who_ordered_cpnum'],
						                                                "comment"               	=> $data_post['new_pickup_notes'],
						                                                "uniqueID"              	=> $unique_id_respite,
						                                                "deliver_to_type"       	=> $data_post['delivery_to_type'],
						                                                'medical_record_id'     	=> $data_post['medical_record_id'],
						                                                "serial_num"            	=> "pickup_order_only",
						                                                "order_status"          	=> $order_current_status,
						                                                "pickup_order"          	=> 1,
						                                                "initial_order"         	=> 0,
						                                               	"pickedup_respite_order" 	=> 1,
						                                                "original_activity_typeid" 	=> 2,
						                                                "activity_reference"       	=> 2, //remove if it will cause errors (newly added)
						                                            	"addressID"				   	=> $respite_address['id']
						                                            );
			                                            $used_addressID = $respite_address['id'];
			                                        }
			                                    }
												else
												{
													foreach($sub_value as $val)
		                                        	{
		                                        		$get_value = get_value_equip_new_v2($patientID,$val,$unique_ids);
		                                        		$orders[] = array(
					                                                "patientID"             	=> $patientID,
					                                                "equipmentID"           	=> $val,
					                                                "equipment_value"       	=> $get_value['equipment_value'],
					                                                "pickup_date"           	=> $pickup_date,
					                                                "activity_typeid"       	=> $activity_type,
					                                                "organization_id"       	=> $organization_id,
					                                                "ordered_by"            	=> $id,
					                                                "who_ordered_fname"     	=> $data_post['who_ordered_fname'],
					                                                "who_ordered_lname"     	=> $data_post['who_ordered_lname'],
					                                                "staff_member_fname"    	=> $data_post['staff_member_fname'],
					                                                "staff_member_lname"    	=> $data_post['staff_member_lname'],
					                                                "who_ordered_email"     	=> $data_post['who_ordered_email'],
					                                                "who_ordered_cpnum"     	=> $data_post['who_ordered_cpnum'],
					                                                "comment"               	=> $data_post['new_pickup_notes'],
					                                                "uniqueID"              	=> $unique_id_respite,
					                                                "deliver_to_type"       	=> $data_post['delivery_to_type'],
					                                                'medical_record_id'     	=> $data_post['medical_record_id'],
					                                                "serial_num"            	=> "pickup_order_only",
					                                                "order_status"          	=> $order_current_status,
					                                                "pickup_order"          	=> 1,
					                                                "initial_order"         	=> 0,
					                                               	"pickedup_respite_order" 	=> 1,
					                                                "original_activity_typeid" 	=> 2,
					                                                "activity_reference"       	=> 2, //remove if it will cause errors (newly added)
					                                            	"addressID"				   	=> $respite_address['id']
					                                            );
		                                        		$used_addressID = $respite_address['id'];
		                                        	}
												}
		                                    }
		                                }
		                            }
		                        }

		                        $create_new_order = $this->order_model->saveorder($orders);

								$activity_type_todo = 2;

								$updating_data = array(
									"activity_typeid" 		=> $activity_type,
									"date_ordered"			=> date('Y-m-d H:i:s'),
									"activity_reference"	=> 2,
									"pickedup_uniqueID"	 	=> $unique_id_respite
								);
								$updateorder = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids_respite, $unique_ids_respite, $activity_type_todo);

								if($updateorder)
		                        {
		                        	$insert_to_status = array(
								        'order_uniqueID'      		=> $unique_id_respite,
								        'medical_record_id'   		=> $data_post['medical_record_id'],
								        'patientID'           		=> $patientID,
								        'status_activity_typeid' 	=> $activity_type,
								        'order_status'        		=> $order_current_status,
								        'addressID'   	  			=> $used_addressID,
										'pickup_date'   	  		=> $pickup_date,
										'organization_id'   	  	=> $organization_id
								    );
								    $this->order_model->insert_to_status($insert_to_status);

								    $pickup_respite_address = $respite_address['respite_address'];
		                        	$pickup_type_sub = array(
								        'pickup_sub'         		=> $data_post['pickup_sub_cat'],
								        'date_pickedup'          	=> date($data_post['pickup_pickup_date']),
								        'medical_record_id'  		=> $data_post['medical_record_id'],
								        'patientID'          		=> $patientID,
								        'pickup_respite_address' 	=> $pickup_respite_address,
								        'order_uniqueID'     		=> $unique_id_respite
								    );
								    $inserted_pickup_tbl = $this->order_model->save($pickup_type_sub,'dme_pickup_tbl');

								    $activity_type_todo = 2;
    								$orig_act_type = !empty($data_post['hdn_orig_act_type_pickup']) ? $data_post['hdn_orig_act_type_pickup'] : "";

								    if($inserted_pickup_tbl)
    								{
    									if($orig_act_type == 3)
								        {
								            $updating_data = array(
								                "activity_typeid"       	=> 2,
								                "activity_reference"     	=> 2,
								                "pickedup_respite_order" 	=> 1
								            );
								        }
								        else
								        {
								            $updating_data = array(
								                "activity_reference"        => 2,
								                "pickedup_respite_order" 	=> 1,
								                "uniqueID_reference"     	=> $unique_id_respite,
								                "pickedup_uniqueID"     	=> $unique_id_respite
								            );
								        }
								        $updateorder = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids_respite, $unique_ids_respite, $activity_type_todo);

    									if($updateorder)
								        {
								            $respite_data = array(
								                "respite_pickedup" => 1
								            );
								            $this->order_model->update_order_respite($data_post['medical_record_id'], $respite_data);
								        }
    								}
		                        }
		                    }
						}

						/******************************************************************
							END FOR PATIENT RESPITE ADDRESS
                        *******************************************************************/

						$patient_first_order = get_patient_first_order($patientID);
				      	$returned_data = get_all_patient_pickup($patientID);
				      	$patient_info = get_patient_info($patientID);

				      	$patient_los = 1;
				        if(empty($returned_data))
				        {
				            $current_date = date("Y-m-d h:i:s");
				            $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
				            $answer_2 = $answer/86400;
				            $patient_los = $patient_los+floor($answer_2);
				        }
				        else if(count($returned_data) == 1)
				        {
				        	if($returned_data[0]['pickup_sub'] != "not needed")
					        {
					        	$returned_query = check_order_after_all_pickup($returned_data[0]['orderID'], $returned_data[0]['uniqueID'], $returned_data[0]['patientID']);
				                if(!empty($returned_query))
				                {
				                	$current_date = date("Y-m-d h:i:s");
				                    $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
				                    $answer_2 = $answer/86400;
				                    $patient_los = $patient_los+floor($answer_2);
				                }
				                else
				                {
				                	$answer = strtotime($returned_data[0]['pickup_date'])-strtotime($patient_info['date_created']);
					                $answer_2 = $answer/86400;
					                $patient_los = $patient_los+floor($answer_2);
				                }
					        }
					        else
					        {
					        	$current_date = date("Y-m-d h:i:s");
				                $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
				                $answer_2 = $answer/86400;
				                $patient_los = $patient_los+floor($answer_2);
					        }
				        }
				        else
				        {
				        	$pickup_all_sign = 0;
				        	foreach ($returned_data as $value_first_loop){
				        		if($value_first_loop['pickup_date'] >= $patient_first_order['actual_order_date'])
				        		{
				        			if($value_first_loop['pickup_sub'] != "not needed")
					        		{
					        			$pickup_all_sign = 1;
					        		}
				        		}
				        	}
				        	if($pickup_all_sign == 0)
				        	{
				        		$current_date = date("Y-m-d h:i:s");
				                $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
				                $answer_2 = $answer/86400;
				                $patient_los = $patient_los+floor($answer_2);
				        	}
				        	// IF NAAY COMPLETE PICKUP
				        	else
				        	{
				        		$pickup_order_count = 1;
					            $previous_pickup_indications = 0; // 1 for selected item(s) pickup, 2 for complete pickup
					            foreach ($returned_data as $value){

					                if($pickup_order_count == 1)
					                {
					                	if($value['pickup_sub'] != "not needed")
						        		{
						        			$previous_pickup_indications = 2;
						                    $previous_orderID = $value['orderID'];
						                    $previous_uniqueID = $value['uniqueID'];
						                    $previous_ordered_date = $value['pickup_date'];
						                    $previous_date_ordered = $value['date_ordered'];
						                    $partial_patient_los_first = 1; // Back to 1
						        		}
						        		else
						        		{
						        			$answer = strtotime($value['pickup_date'])-strtotime($patient_first_order['actual_order_date']);
						                    $answer_2 = $answer/86400;
						                    $partial_patient_los_first = $patient_los+floor($answer_2);
						                    $previous_pickup_indications = 1;
						                    $previous_orderID = $value['orderID'];
						                    $previous_uniqueID = $value['uniqueID'];
						                    $previous_ordered_date = $value['pickup_date'];
						                    $previous_date_ordered = $value['date_ordered'];
						        		}
					                }
					                else
					                {
					                	if($value['pickup_sub'] != "not needed")
						        		{
						        			$previous_pickup_indications = 2;
						        			if(count($returned_data) == $pickup_order_count)
							                {
							                	$returned_query = check_order_after_all_pickup($value['orderID'], $value['uniqueID'], $value['patientID']);
							                	if(!empty($returned_query))
							                	{
							                		$current_date = date("Y-m-d h:i:s");
								                    $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
								                    $answer_2 = $answer/86400;
								                    $patient_los = $patient_los+floor($answer_2);
							                	}
							                	else
							                	{
							                		$answer = strtotime($value['pickup_date'])-strtotime($patient_info['date_created']);
									                $answer_2 = $answer/86400;
									                $patient_los = $patient_los+floor($answer_2);
							                	}
							                }
							                else
							                {
							                	$partial_patient_los_first = 1;
							                	$previous_date_ordered = $value['date_ordered'];
							                }
						        		}
						        		else
						        		{
						        			$previous_pickup_indications = 1;
						        			if(count($returned_data) == $pickup_order_count)
							                {
							                	$current_date = date("Y-m-d h:i:s");
							                	if($value['pickup_date'] > $current_date)
							                	{
							                		$answer = strtotime($current_date)-strtotime($previous_ordered_date);
							                		$answer_2 = $answer/86400;
							                        $patient_los = $partial_patient_los_first+floor($answer_2);
							                	}
							                	else
							                	{
							                		$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
							                		$answer_2 = $answer/86400;
							                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);

							                        $answer_sub = strtotime($current_date)-strtotime($value['pickup_date']);
							                        $answer_2_sub = $answer_sub/86400;
							                        $patient_los = $partial_patient_los_first+floor($answer_2_sub);
							                	}
							                }
							                else
							                {
							                	if($previous_pickup_indications == 1)
							                	{
							                		$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
							                        $answer_2 = $answer/86400;
							                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
							                	}
							                	else
							                	{
							                		$answer = strtotime($value['pickup_date'])-strtotime($patient_info['date_created']);
							                        $answer_2 = $answer/86400;
							                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
							                	}
							                	$previous_date_ordered = $value['date_ordered'];
							                }
						        		}
					              	}
					              	$pickup_order_count++;
					        	}
				        	}
				      	}

				      	$new_data = array(
			      		'length_of_stay'	=> $patient_los
				      	);
				      	$this->order_model->update_patient_los_per_patient($new_data,$patientID);

				      	$new_total_los_for_hospice = $this->order_model->get_total_patient_los_per_hospice_updated($patient_info['ordered_by']);
				      	$new_data_for_hospice = array(
				      		'patient_total_los'	=> $new_total_los_for_hospice['total_patient_los']
				      	);
				      	$current_date = date("Y-m-d");
				      	$this->order_model->update_patient_los_for_hospice($new_data_for_hospice,$patient_info['ordered_by'],$current_date);

						/*
						For general success message
						*/
                        $this->response_code = 0;
                        $this->response_message = "Activity submitted successfully.";
                    }
                    else
                    {
                        $this->response_message = "Error saving information.";
                    }
				}
				else
				{
					$this->response_message = validation_errors('<span></span>');
				}
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}



	public function change_status_to_exchange($patientID)
	{
		$account_type_name = $this->session->userdata('account_type');
		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_cpnum','Staff Cellphone No.','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('exchange_reason','Reason for Exchange','required');
		$this->form_validation->set_rules('exchange_date','Exchange Delivery Date','required');
		$this->form_validation->set_rules('exchange_equipments[]','Items','required');
		$this->form_validation->set_rules('activity_type','Activity Type','required');

		$id = $this->session->userdata('userID');

		if($this->input->post())
		{
			$person_who_ordered = $this->session->userdata('email');
			$data_post = $this->input->post();

			$original_act_typeid = !empty($data_post['hdn_orig_act_type']) ? $data_post['hdn_orig_act_type'] : ""; //to determine kung respite items ba ang iyang i exchange


			if($original_act_typeid == 5)
			{
				$act_reference = 5;
			}
			else
			{
				$act_reference = 3;
			}

			if($this->form_validation->run()===TRUE)
			{
				$unique_id = strtotime(date('Y-m-d H:i:s'));
				$post_uniqueID  = $data_post['uniqueID'];

				$checked_unique_id = $this->order_model->checked_unique_id($unique_id);
				if(!empty($checked_unique_id))
				{
					$unique_id += 1;
				}

				if($post_uniqueID!="")
				{
					/*
					* order information
					*/
					$exchange_date 		= date($data_post['exchange_date']);
					$ordered_by  		= $id;
					$organization_id 	= $data_post['organization_id'];
					$activity_type		= 2;
					$order_current_status = "pending";
					if($data_post['send_to_confirm_work_order_sign'] == 1)
					{
					  $order_current_status = "tobe_confirmed";
					}

					//order items
					$orders = array();
					$oxygen_concentrator_count = 0;
					$oxygen_equipment_id = 0;
					$used_addressID = 0;
					foreach($data_post['exchange_equipments'] as $key=>$value)
					{
						if($value != 61 && $value != 29)
            			{
            				$equipment_value_new = 1;
            				if($value == 457 || $value == 458)
            				{
            					$equipment_value_new = 2;
            				}
							$orders[] = array(

								"patientID"				=> $patientID,
								"equipmentID"			=> $value,
								"equipment_value"		=> $equipment_value_new,
								"pickup_date"			=> $exchange_date,
								"activity_typeid"		=> 3,
								"organization_id"		=> $organization_id,
								"ordered_by"			=> $id,
								"who_ordered_fname"		=> $data_post['who_ordered_fname'],
								"who_ordered_lname"		=> $data_post['who_ordered_lname'],
								"staff_member_fname"	=> $data_post['staff_member_fname'],
								"staff_member_lname"	=> $data_post['staff_member_lname'],
								"who_ordered_email"		=> $data_post['who_ordered_email'],
								"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
								"comment"				=> $data_post['new_exchange_notes'],
								"uniqueID"				=> $unique_id,
								"deliver_to_type"	    => $data_post['delivery_to_type'],
								'medical_record_id' 	=> $data_post['medical_record_id'],
								"order_status"	        => $order_current_status,
								"activity_reference"	=> $act_reference,
								"initial_order"			=> 0,
								"original_activity_typeid" => 3,
								"addressID"				=> $data_post['exchange_address_id']
							);
							$used_addressID = $data_post['exchange_address_id'];
						}
					}
					/*
					* @sub equipments
					*
					*/
					if(!empty($data_post['equip_options_exchange']))
					{
						foreach($data_post['equip_options_exchange'] as $key=>$value)
						{
							if(in_array($key,$data_post['exchange_equipments']))
							{
								foreach($value as $sub_key=>$sub_value)
              					{
              						foreach($sub_value as $val)
              						{
										if($val == 201)
										{
											$get_value = get_value_equip($patientID,$val);
											$orders[] = array(
												"patientID"				=> $patientID,
												"equipmentID"			=> $val,
												"equipment_value"		=> $get_value['equipment_value'],
												"pickup_date"			=> $exchange_date,
												"activity_typeid"		=> 3,
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['who_ordered_fname'],
												"who_ordered_lname"		=> $data_post['who_ordered_lname'],
												"staff_member_fname"	=> $data_post['staff_member_fname'],
												"staff_member_lname"	=> $data_post['staff_member_lname'],
												"who_ordered_email"		=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
												"comment"				=> $data_post['new_exchange_notes'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['delivery_to_type'],
												'medical_record_id' 	=> $data_post['medical_record_id'],
												"order_status"	        => $order_current_status,
												"activity_reference"	=> $act_reference,
												"initial_order"			=> 0,
												"original_activity_typeid" => 3,
												"addressID"				=> $data_post['exchange_address_id']
											);
											$used_addressID = $data_post['exchange_address_id'];
										}
										else if($val == 457 || $val == 458)
										{
											$orders[] = array(
												"patientID"				=> $patientID,
												"equipmentID"			=> $val,
												"equipment_value"		=> 2,
												"pickup_date"			=> $exchange_date,
												"activity_typeid"		=> 3,
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['who_ordered_fname'],
												"who_ordered_lname"		=> $data_post['who_ordered_lname'],
												"staff_member_fname"	=> $data_post['staff_member_fname'],
												"staff_member_lname"	=> $data_post['staff_member_lname'],
												"who_ordered_email"		=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
												"comment"				=> $data_post['new_exchange_notes'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['delivery_to_type'],
												'medical_record_id' 	=> $data_post['medical_record_id'],
												"order_status"	        => $order_current_status,
												"activity_reference"	=> $act_reference,
												"initial_order"			=> 0,
												"original_activity_typeid" => 3,
												"addressID"				=> $data_post['exchange_address_id']
											);
											$used_addressID = $data_post['exchange_address_id'];
										}
										else
										{
											$get_value = get_value_equip($patientID,$val);
											$orders[] = array(
												"patientID"				=> $patientID,
												"equipmentID"			=> $val,
												"equipment_value"		=> $get_value['equipment_value'],
												"pickup_date"			=> $exchange_date,
												"activity_typeid"		=> 3,
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['who_ordered_fname'],
												"who_ordered_lname"		=> $data_post['who_ordered_lname'],
												"staff_member_fname"	=> $data_post['staff_member_fname'],
												"staff_member_lname"	=> $data_post['staff_member_lname'],
												"who_ordered_email"		=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
												"comment"				=> $data_post['new_exchange_notes'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['delivery_to_type'],
												'medical_record_id' 	=> $data_post['medical_record_id'],
												"order_status"	        => $order_current_status,
												"activity_reference"	=> $act_reference,
												"initial_order"			=> 0,
												"original_activity_typeid" => 3,
												"addressID"				=> $data_post['exchange_address_id']
											);
											$used_addressID = $data_post['exchange_address_id'];
										}
              						}
								}
							}
						}
					}
			 		//if equipment is not oxygen concentrator testing
			 		//print_r($orders);
			 		//if equipment is oxygen concentrator testing

					// echo "<pre>";
					// print_r($orders_61);
					// echo "</pre>";

					if(!empty($orders_61))
					{
						$result = array();
						if(!empty($orders_80) && !empty($orders_81))
						{
							$result['parent1'] = $this->order_model->save_oxygen_order($orders_61);
							$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_77);
							$result['LPM1']	= $this->order_model->save_oxygen_order($orders_80);
							$result['parent2'] = $this->order_model->save_oxygen_order($orders_61);
							$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_77);
							$result['LPM2']	= $this->order_model->save_oxygen_order($orders_81);
							$result['duration']	= $this->order_model->save_oxygen_order($orders_103_104_78_79);
							$result['delivery_device']	= $this->order_model->save_oxygen_order($orders_105_106_281_82_83_280);
							$result['e_portable_system']	= $this->order_model->save_oxygen_order($orders_243_244_241_242);
							$result['duration']	= $this->order_model->save_oxygen_order($orders_103_104_78_79);
							$result['delivery_device']	= $this->order_model->save_oxygen_order($orders_105_106_281_82_83_280);
							$result['e_portable_system']	= $this->order_model->save_oxygen_order($orders_243_244_241_242);
							if($result)
							{
								$create_new_order = $result;
							}
						}
						else
						{
							if(!empty($orders_80))
							{
								$result['parent'] = $this->order_model->save_oxygen_order($orders_61);
								$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_77);
								$result['LPM']	= $this->order_model->save_oxygen_order($orders_80);
								$result['duration']	= $this->order_model->save_oxygen_order($orders_103_104_78_79);
								$result['delivery_device']	= $this->order_model->save_oxygen_order($orders_105_106_281_82_83_280);
								$result['e_portable_system']	= $this->order_model->save_oxygen_order($orders_243_244_241_242);
								if($result)
								{
									$create_new_order = $result;
								}
							}
							else if(!empty($orders_81))
							{
								$result['parent'] = $this->order_model->save_oxygen_order($orders_61);
								$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_77);
								$result['LPM']	= $this->order_model->save_oxygen_order($orders_81);
								$result['duration']	= $this->order_model->save_oxygen_order($orders_103_104_78_79);
								$result['delivery_device']	= $this->order_model->save_oxygen_order($orders_105_106_281_82_83_280);
								$result['e_portable_system']	= $this->order_model->save_oxygen_order($orders_243_244_241_242);
								if($result)
								{
									$create_new_order = $result;
								}
							}
							else
							{
								$result['duration']	= $this->order_model->save_oxygen_order($orders);
								if($result)
								{
									$create_new_order = $result;
								}
							}
						}
						if($orders)
						{
							$create_new_order = $this->order_model->saveorder($orders);
						}
					}
					else
					{
						$create_new_order = $this->order_model->saveorder($orders);
					}

					if($create_new_order)
					{
						$activity_type_todo = 3;
						$equipment_ids = array();
						$equipment_ids_oxygen = array();
						foreach ($data_post['exchange_equipments'] as $value) {
							if($value != 61 && $value != 29)
							{
								$equipment_ids[] = $value;
							}
							else
							{
								$equipment_ids_oxygen[] = $value;
							}
						}
						$unique_ids = !empty($data_post['hdn_exchange_uniqueID']) ? $data_post['hdn_exchange_uniqueID'] : "";

						//para ma-consider if ang item nga i exchange kay gikan sa respite nga activity type
						if($original_act_typeid == 5)
						{
							$updating_data = array(
								"pickedup_respite_order"	=> 1,
								"activity_typeid" 			=> $activity_type,
								"deliver_to_type"			=> $data_post['delivery_to_type'],
								"date_ordered"				=> date('Y-m-d H:i:s'),
								"activity_reference"	    => 3,
								"uniqueID_reference"		=> $unique_id
							);
						}
						else
						{
							$updating_data = array(
								"activity_typeid" 			=> $activity_type,
								"deliver_to_type"			=> $data_post['delivery_to_type'],
								"date_ordered"				=> date('Y-m-d H:i:s'),
								"activity_reference"	    => 3,
								"uniqueID_reference"		=> $unique_id
							);
						}

						$updateorder = false;
						if(!empty($equipment_ids))
						{
							$updateorder = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids, $unique_ids, $activity_type_todo);
						}

						$updateorder_1 = false;
						if($oxygen_concentrator_count == 2)
						{
							$updateorder_1 = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids_oxygen, $unique_ids, $activity_type_todo);
						}
						else if($oxygen_concentrator_count == 1)
						{
							$oxygen_count = get_oxygen_count($organization_id,$patientID,$unique_ids);
							if($oxygen_count == 1)
							{
								$updateorder_1 = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids_oxygen, $unique_ids, $activity_type_todo);
							}
							else
							{
								$oxygen_orderID = get_first_oxygen_orderID($organization_id,$patientID,$unique_ids,$oxygen_equipment_id);
								$updateorder_1 = $this->order_model->updateorder($patientID, $updating_data, $equipment_ids_oxygen[0], $unique_ids, $activity_type_todo, "", $oxygen_orderID['orderID']);
							}
						}

						if($updateorder || $updateorder_1)
						{
							$insert_to_status = array(
								'order_uniqueID'      		=> $unique_id,
								'medical_record_id'   		=> $data_post['medical_record_id'],
								'patientID'			 		=> $patientID,
								'status_activity_typeid'   	=> 3,
								'order_status'   	  		=> $order_current_status,
								'addressID'   	  			=> $used_addressID,
								'pickup_date'   	  		=> $exchange_date,
								'organization_id'   	  	=> $organization_id,
								'ordered_by'   	  			=> $id,
								'date_ordered'				=> date("Y-m-d h:i:s"),
								'original_activity_typeid'  => 3,
								'actual_order_date'   	  	=> '0000-00-00'
							);
							$this->order_model->insert_to_status($insert_to_status);

							$exchange_type_sub = array(
								'exchange_date'		 => date($data_post['exchange_date']),
								'exchange_reason'	 => $data_post['exchange_reason'],
								'medical_record_id'	 => $data_post['medical_record_id'],
								'patientID'			 => $patientID,
								'order_uniqueID'	 => $unique_id
							);
							$this->order_model->save($exchange_type_sub,'dme_sub_exchange');
							$this->response_code = 0;
							$this->response_message = "Activity submitted successfully.";

							/*
							*	For email
							*/

							if($account_type_name != "dme_admin" && $account_type_name!="dme_user")
							{
								$email_form = $this->form_email_temp($unique_id,3,TRUE);

								$this->load->config('email');
								$config =   $this->config->item('me_email');
								$this->load->library('email', $config);

								$this->email->from('orders@smarterchoice.us','AHMSLV');
								$this->email->to('orders@ahmslv.com');
								$this->email->cc('russel@smartstart.us');
								$this->email->subject('AHMSLV | Order Summary');
								$this->email->message($email_form);
								$this->email->send();
							}
						}
					}
				}
				else
				{
					$this->response_message = "Error saving information.";
				}
			}
			else
			{
				$this->response_message = validation_errors('<span></span>');
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}

	public function change_status_to_ptmove($patientID)
	{
		$account_type_name = $this->session->userdata('account_type');
		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_cpnum','Staff Cellphone No.','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('ptmove_delivery_date','CUS Move Delivery Date','required');
		$this->form_validation->set_rules('ptmove_address','CUS Move Address','required');
		$this->form_validation->set_rules('ptmove_nextofkin','Next of Kin','required');
		$this->form_validation->set_rules('ptmove_nextofkinrelation','Next of Kin Relationship','required');
		$this->form_validation->set_rules('ptmove_nextofkinphone','Next of Kin Phone Number','required');
		$this->form_validation->set_rules('ptmove_patient_residence','Customer Residence','required');
		$this->form_validation->set_rules('ptmove_patient_phone','Customer Phone Number','required');
		$this->form_validation->set_rules('ptmove_alt_patient_phone','Customer Alt. Phone Number','required');

		$this->form_validation->set_rules('equipments[]','Items','required');
		$this->form_validation->set_rules('activity_type','Activity Type','required');
		$id = $this->session->userdata('userID');

		if($this->input->post())
		{
			$person_who_ordered = $this->session->userdata('email');
			$data_post = $this->input->post();

			if($this->form_validation->run()===TRUE)
			{
				$all_patient_move_address = get_patient_move_addresses($patientID);

				$patient_move_exist = 0;
				foreach ($all_patient_move_address as $key => $value) {
					if($value['street'] == $data_post['ptmove_address'] && $value['placenum'] == $data_post['ptmove_placenum'] && $value['city'] == $data_post['ptmove_city'] && $value['state'] == $data_post['ptmove_state'] && $value['postal_code'] == $data_post['ptmove_postalcode'])
					{
						$check_if_picked_up = check_if_address_picked_up($value['id'],$patientID);
						if(!empty($check_if_picked_up))
						{
							$patient_move_exist = 1;
						}
					}
				}

				if($patient_move_exist == 0)
				{
					$unique_id = strtotime(date('Y-m-d H:i:s'));
					$post_uniqueID  = $data_post['uniqueID'];

					$checked_unique_id = $this->order_model->checked_unique_id($unique_id);
					if(!empty($checked_unique_id))
					{
						$unique_id += 1;
					}

					if($post_uniqueID!="")
					{
						$data_insert_patient_address = array(
							'patient_id' 	=> $patientID,
							'street'		=> $data_post['ptmove_address'],
							'placenum'		=> $data_post['ptmove_placenum'],
							'city'			=> $data_post['ptmove_city'],
							'state'			=> $data_post['ptmove_state'],
							'postal_code'	=> $data_post['ptmove_postalcode'],
							'type'			=> 1,
							'status'		=> 0
						);
						$addressID = $this->order_model->insert_patient_address($data_insert_patient_address);

						/*
						* order information
						*/
						$ptmove_date 		= date($data_post['ptmove_delivery_date']);
						$ordered_by  		= $id;
						$organization_id 	= $data_post['organization_id'];
						$activity_type		= 2;
						$order_current_status = "pending";
			            if($data_post['send_to_confirm_work_order_sign'] == 1)
			            {
			              	$order_current_status = "tobe_confirmed";
			            }

						//order items
						$orders = array();
						$capped_o2_count = 0;
	          			$non_capped_o2_count = 0;
	          			$used_addressID = 0;
						foreach($data_post['equipments'] as $key=>$value)
						{
							if($value != 61 && $value != 29)
	            			{
								$orders[] = array(
									"patientID"				=> $patientID,
									"equipmentID"			=> $value,
									"equipment_value"		=> 1,
									"pickup_date"			=> $ptmove_date,
									"activity_typeid"		=> 4,
									"organization_id"		=> $organization_id,
									"ordered_by"			=> $id,
									"who_ordered_fname"		=> $data_post['who_ordered_fname'],
									"who_ordered_lname"		=> $data_post['who_ordered_lname'],
									"staff_member_fname"	=> $data_post['staff_member_fname'],
									"staff_member_lname"	=> $data_post['staff_member_lname'],
									"who_ordered_email"		=> $data_post['who_ordered_email'],
									"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
									"comment"				=> $data_post['new_order_notes'],
									"uniqueID"				=> $unique_id,
									"deliver_to_type"	    => $data_post['delivery_to_type'],
									'medical_record_id' 	=> $data_post['medical_record_id'],
									"order_status"	        => $order_current_status,
									"initial_order"			=> 0,
									"original_activity_typeid" => 4,
									'addressID'				=> $addressID
								);
								$used_addressID = $addressID;

								if($value == 316 || $value == 325)
				                {
					                $capped_o2_count++;
					                if($value == 316)
					                {
					                  $ordered_o2_type = 5;
					                }
					                else
					                {
					                  $ordered_o2_type = 10;
					                }
					            }
					            else if($value == 334 || $value == 343)
					            {
					                $non_capped_o2_count++;
					                if($value == 334)
					                {
					                  $ordered_o2_type = 5;
					                }
					                else
					                {
					                  $ordered_o2_type = 10;
					                }
					            }
							}
						}

						// if($data_post['commode_pail_counter'] > 1)
						// {
						// 	for ($i=2; $i <= $data_post['commode_pail_counter']; $i++) {
						// 		$orders[] = array(
						// 			"patientID"				=> $patientID,
						// 			"equipmentID"			=> 7,
						// 			"equipment_value"		=> 1,
						// 			"pickup_date"			=> $ptmove_date,
						// 			"activity_typeid"		=> 4,
						// 			"organization_id"		=> $organization_id,
						// 			"ordered_by"			=> $id,
						// 			"who_ordered_fname"		=> $data_post['who_ordered_fname'],
						// 			"who_ordered_lname"		=> $data_post['who_ordered_lname'],
						// 			"staff_member_fname"	=> $data_post['staff_member_fname'],
						// 			"staff_member_lname"	=> $data_post['staff_member_lname'],
						// 			"who_ordered_email"		=> $data_post['who_ordered_email'],
						// 			"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
						// 			"comment"				=> $data_post['new_order_notes'],
						// 			"uniqueID"				=> $unique_id,
						// 			"deliver_to_type"	    => $data_post['delivery_to_type'],
						// 			'medical_record_id' 	=> $data_post['medical_record_id'],
						// 			"order_status"	        => $order_current_status,
						// 			"initial_order"			=> 0,
						// 			"original_activity_typeid" => 4,
						// 			'addressID'				=> $addressID
						// 		);
						// 	}
						// }

						/*
						* @sub equipments
						*
						*/
						foreach($data_post['subequipment'] as $key=>$value)
						{
							if(in_array($key,$data_post['equipments']))
							{
								foreach($value as $sub_key=>$sub_value)
								{
									if($sub_key=="radio")
									{
										foreach($sub_value as $radio_value)
										{
											if($radio_value == 78 || $radio_value == 79)
	                                    	{
		                                      	if($capped_o2_count == 2)
		                                      	{
		                                        	if($radio_value == 78)
		                                        	{
		                                          		$ordered_o2_duration = 318;
		                                          		$ordered_o2_duration_2 = 327;
		                                        	}
		                                        	else
		                                        	{
		                                          		$ordered_o2_duration = 319;
		                                          		$ordered_o2_duration_2 = 328;
		                                        	}
	                                        		$orders_duration = array(
							                          	"patientID"       			=> $patientID,
							                          	"equipmentID"     			=> $ordered_o2_duration,
							                          	"equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
	                        						);
	                        						$used_addressID = $addressID;

							                        $orders_duration_2 = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_duration_2,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
							                        );
							                        $used_addressID = $addressID;
			                                    }
			                                    else
			                                    {
			                                        if($radio_value == 78)
			                                        {
			                                          	if($ordered_o2_type == 5)
			                                          	{
			                                            	$ordered_o2_duration = 318;
			                                          	}
			                                          	else
			                                          	{
			                                            	$ordered_o2_duration = 327;
			                                          	}
			                                        }
			                                        else
			                                        {
			                                          	if($ordered_o2_type == 5)
			                                          	{
		                                            		$ordered_o2_duration = 319;
			                                          	}
			                                          	else
			                                          	{
		                                            		$ordered_o2_duration = 328;
			                                          	}
			                                        }
	                                        		$orders_duration = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_duration,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
							                        );
							                        $used_addressID = $addressID;
	                                      		}
	                                    	}
	                                    	else if($radio_value == 103 || $radio_value == 104)
	                                    	{
		                                        if($non_capped_o2_count == 2)
		                                        {
			                                        if($radio_value == 103)
			                                        {
			                                          	$ordered_o2_duration = 336;
			                                          	$ordered_o2_duration_2 = 345;
			                                        }
			                                        else
			                                        {
			                                          	$ordered_o2_duration = 337;
			                                          	$ordered_o2_duration_2 = 346;
			                                        }
	                                        		$orders_duration = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_duration,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
							                        );
							                        $used_addressID = $addressID;

							                        $orders_duration_2 = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_duration_2,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
							                        );
							                        $used_addressID = $addressID;
		                                      	}
		                                      	else
		                                      	{
			                                        if($radio_value == 103)
			                                        {
			                                          	if($ordered_o2_type == 5)
			                                          	{
				                                            $ordered_o2_duration = 336;
			                                          	}
			                                          	else
			                                          	{
				                                            $ordered_o2_duration = 345;
			                                          	}
			                                        }
			                                        else
			                                        {
			                                          	if($ordered_o2_type == 5)
			                                          	{
				                                            $ordered_o2_duration = 337;
			                                          	}
			                                          	else
			                                          	{
				                                            $ordered_o2_duration = 346;
			                                          	}
			                                        }
	                                        		$orders_duration = array(
							                          	"patientID"       			=> $patientID,
							                          	"equipmentID"     			=> $ordered_o2_duration,
							                          	"equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
							                        );
							                        $used_addressID = $addressID;
	                                      		}
	                                    	}
		                                    else if($radio_value == 82 || $radio_value == 83 || $radio_value == 280)
		                                    {
	                                    		if($capped_o2_count == 2)
	                                          	{
		                                            if($radio_value == 82)
		                                            {
		                                                $ordered_o2_delivery_device = 320;
		                                                $ordered_o2_delivery_device_2 = 329;
		                                            }
		                                            else if($radio_value == 83)
		                                            {
		                                              $ordered_o2_delivery_device = 321;
		                                                $ordered_o2_delivery_device_2 = 330;
		                                            }
		                                            else
		                                            {
		                                                $ordered_o2_delivery_device = 322;
		                                                $ordered_o2_delivery_device_2 = 331;
		                                            }
	                                      			$orders_delivery_device = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_delivery_device,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
							                        );
							                        $used_addressID = $addressID;

							                        $orders_delivery_device_2 = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_delivery_device_2,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
							                        );
							                        $used_addressID = $addressID;
	                                          	}
	                                          	else
	                                          	{
		                                            if($radio_value == 82)
		                                            {
		                                                if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_delivery_device = 320;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_delivery_device = 329;
		                                                }
		                                            }
		                                            else if($radio_value == 83)
		                                            {
		                                              if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_delivery_device = 321;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_delivery_device = 330;
		                                                }
		                                            }
		                                            else
		                                            {
		                                                if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_delivery_device = 322;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_delivery_device = 331;
		                                                }
		                                            }
	                                            	$orders_delivery_device = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_delivery_device,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
							                        );
							                        $used_addressID = $addressID;
	                                          	}
	                                  		}
		                                  	else if($radio_value == 105 || $radio_value == 106 || $radio_value == 281)
		                                  	{
	                                    		if($non_capped_o2_count == 2)
	                                          	{
		                                            if($radio_value == 105)
		                                            {
		                                                $ordered_o2_delivery_device = 338;
		                                                $ordered_o2_delivery_device_2 = 347;
		                                            }
		                                            else if($radio_value == 106)
		                                            {
		                                              $ordered_o2_delivery_device = 339;
		                                                $ordered_o2_delivery_device_2 = 348;
		                                            }
		                                            else
		                                            {
		                                                $ordered_o2_delivery_device = 340;
		                                                $ordered_o2_delivery_device_2 = 349;
		                                            }
	                                      			$orders_delivery_device = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_delivery_device,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
							                        );
							                        $used_addressID = $addressID;

							                        $orders_delivery_device_2 = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_delivery_device_2,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
							                        );
							                        $used_addressID = $addressID;
	                                          	}
	                                          	else
	                                          	{
		                                            if($radio_value == 105)
	                                              	{
	                                                  	if($ordered_o2_type == 5)
	                                                  	{
		                                                    $ordered_o2_delivery_device = 338;
	                                                  	}
	                                                  	else
	                                                  	{
		                                                    $ordered_o2_delivery_device = 347;
	                                                  	}
	                                              	}
	                                              	else if($radio_value == 106)
	                                              	{
		                                                if($ordered_o2_type == 5)
	                                                  	{
		                                                    $ordered_o2_delivery_device = 339;
	                                                  	}
	                                                  	else
	                                                  	{
		                                                    $ordered_o2_delivery_device = 348;
	                                                  	}
	                                              	}
	                                              	else
	                                              	{
	                                                  	if($ordered_o2_type == 5)
	                                                  	{
		                                                    $ordered_o2_delivery_device = 340;
	                                                  	}
	                                                  	else
	                                                  	{
		                                                    $ordered_o2_delivery_device = 349;
	                                                  	}
	                                              	}
	                                              	$orders_delivery_device = array(
	                                                    "patientID"           		=> $patientID,
	                                                    "equipmentID"         		=> $ordered_o2_delivery_device,
	                                                    "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
	                                              	);
	                                              	$used_addressID = $addressID;
	                                          	}
	                                  		}
	                                    	else if($radio_value == 241 || $radio_value == 242)
	                                    	{
		                                      	if($capped_o2_count == 2)
		                                      	{
		                                        	if($radio_value == 241)
	                                              	{
	                                                  	$ordered_o2_e_portable = 323;
	                                                  	$ordered_o2_e_portable_2 = 332;
	                                              	}
	                                              	else
	                                              	{
	                                                  	$ordered_o2_e_portable = 324;
	                                                  	$ordered_o2_e_portable_2 = 333;
	                                              	}

	                                        		$orders_e_portable = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_e_portable,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
	                       							);
	                       							$used_addressID = $addressID;

								                    $orders_e_portable_2 = array(
							                          	"patientID"       			=> $patientID,
							                          	"equipmentID"     			=> $ordered_o2_e_portable_2,
							                          	"equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
	                        						);
	                        						$used_addressID = $addressID;
		                                      	}
		                                      	else
		                                      	{
	                                        		if($radio_value == 241)
	                                              	{
	                                                	if($ordered_o2_type == 5)
	                                                  	{
	                                                    	$ordered_o2_e_portable = 323;
	                                                  	}
	                                                  	else
	                                                  	{
	                                                    	$ordered_o2_e_portable = 332;
	                                                  	}
	                                              	}
	                                              	else
	                                              	{
	                                                  if($ordered_o2_type == 5)
	                                                  {
	                                                    $ordered_o2_e_portable = 324;
	                                                  }
	                                                  else
	                                                  {
	                                                    $ordered_o2_e_portable = 333;
	                                                  }
	                                              	}
	                                             	$orders_e_portable = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_e_portable,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
	                        						);
	                        						$used_addressID = $addressID;
			                                    }
			                                }
		                                    else if($radio_value == 243 || $radio_value == 244)
		                                    {
		                                      	if($non_capped_o2_count == 2)
	                                            {
			                                        if($radio_value == 243)
	                                          		{
	                                                  $ordered_o2_e_portable = 341;
	                                                  $ordered_o2_e_portable_2 = 350;
	                                          		}
	                                          		else
	                                          		{
	                                                  $ordered_o2_e_portable = 342;
	                                                  $ordered_o2_e_portable_2 = 351;
	                                          		}
	                                              	$orders_e_portable = array(
	                                                    "patientID"           		=> $patientID,
	                                                    "equipmentID"         		=> $ordered_o2_e_portable,
	                                                    "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
	                                                );
	                                                $used_addressID = $addressID;

	                                                $orders_e_portable_2 = array(
	                                                    "patientID"           		=> $patientID,
	                                                    "equipmentID"         		=> $ordered_o2_e_portable_2,
	                                                    "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
	                                                );
	                                                $used_addressID = $addressID;
	                                          	}
	                                          	else
	                                          	{
		                                            if($radio_value == 243)
	                                              	{
	                                                	if($ordered_o2_type == 5)
	                                                  	{
	                                                    	$ordered_o2_e_portable = 341;
	                                                  	}
	                                                  	else
	                                                  	{
	                                                    	$ordered_o2_e_portable = 350;
	                                                  	}
	                                                }
	                                                else
	                                                {
	                                                  if($ordered_o2_type == 5)
	                                                  {
	                                                    $ordered_o2_e_portable = 342;
	                                                  }
	                                                  else
	                                                  {
	                                                    $ordered_o2_e_portable = 351;
	                                                  }
	                                              	}
	                                              	$orders_e_portable = array(
							                            "patientID"       			=> $patientID,
							                            "equipmentID"     			=> $ordered_o2_e_portable,
							                            "equipment_value"   		=> 1,
									                    "pickup_date"     			=> $ptmove_date,
									                    "activity_typeid"   		=> 4,
									                    "organization_id"   		=> $organization_id,
									                    "ordered_by"      			=> $id,
									                    "who_ordered_fname"   		=> $data_post['who_ordered_fname'],
									                    "who_ordered_lname"   		=> $data_post['who_ordered_lname'],
									                    "staff_member_fname"  		=> $data_post['staff_member_fname'],
									                    "staff_member_lname"  		=> $data_post['staff_member_lname'],
									                    "who_ordered_email"   		=> $data_post['who_ordered_email'],
									                    "who_ordered_cpnum"   		=> $data_post['who_ordered_cpnum'],
									                    "comment"       			=> $data_post['new_order_notes'],
									                    "uniqueID"        			=> $unique_id,
									                    "deliver_to_type"     		=> $data_post['delivery_to_type'],
									                    'medical_record_id'   		=> $data_post['medical_record_id'],
									                    "order_status"          	=> $order_current_status,
									                    "initial_order"     		=> 0,
									                    "original_activity_typeid" 	=> 4,
									                    'addressID'       			=> $addressID
							                        );
							                        $used_addressID = $addressID;
	                                          	}
		                                    }
		                                    else
		                                    {
												$orders[] = array(
													"patientID"					=> $patientID,
													"equipmentID"				=> $radio_value,
													"equipment_value"			=> 1,
													"pickup_date"				=> $ptmove_date,
													"activity_typeid"			=> 4,
													"organization_id"			=> $organization_id,
													"ordered_by"				=> $id,
													"who_ordered_fname"			=> $data_post['who_ordered_fname'],
													"who_ordered_lname"			=> $data_post['who_ordered_lname'],
													"staff_member_fname"		=> $data_post['staff_member_fname'],
													"staff_member_lname"		=> $data_post['staff_member_lname'],
													"who_ordered_email"			=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
													"comment"					=> $data_post['new_order_notes'],
													"uniqueID"					=> $unique_id,
													"deliver_to_type"	    	=> $data_post['delivery_to_type'],
													'medical_record_id' 		=> $data_post['medical_record_id'],
													"order_status"	        	=> $order_current_status,
													"initial_order"				=> 0,
													"original_activity_typeid" 	=> 4,
													'addressID'					=> $addressID
												);
												$used_addressID = $addressID;
											}
										}
									}
									else
									{
										//this changes is to separate the two oxygen concentrator in the DB
										if($capped_o2_count > 0 || $non_capped_o2_count > 0)
										{
											if($sub_key != 80 && $sub_key != 81 && $sub_key != 101 && $sub_key != 102)
											{
												//this is for CPAP = IPAP if the equipment is ordered with oxygen concentrator
												if($sub_key == 114)
												{
													$orders[] = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $sub_key,
														"equipment_value"			=> $sub_value,
														"pickup_date"				=> $ptmove_date,
														"activity_typeid"			=> 4,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 4,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
												}
												else
												//this is for the BIPAP = IPAP if the equipment is ordered with oxygen concentrator
												if($sub_key == 109)
												{
													$orders[] = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $sub_key,
														"equipment_value"			=> $sub_value,
														"pickup_date"				=> $ptmove_date,
														"activity_typeid"			=> 4,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 4,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
												}
												else
												//this is for the BIPAP = EPAP if the equipment is ordered with oxygen concentrator
												if($sub_key == 110)
												{
													$orders[] = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $sub_key,
														"equipment_value"			=> $sub_value,
														"pickup_date"				=> $ptmove_date,
														"activity_typeid"			=> 4,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 4,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
												}
												else
												//this is for the BIPAP = RATE if the equipment is ordered with oxygen concentrator
												if($sub_key == 111)
												{
													$orders[] = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $sub_key,
														"equipment_value"			=> $sub_value,
														"pickup_date"				=> $ptmove_date,
														"activity_typeid"			=> 4,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 4,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
												}
												else if($sub_key == 77)
												{
													if($capped_o2_count == 2)
													{
			                                            $ordered_o2_liter_flow = 317;
			                                            $ordered_o2_liter_flow_2 = 326;

			                                            $orders_liter_flow = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $ptmove_date,
															"activity_typeid"			=> 4,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 4,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;

														$orders_liter_flow_2 = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow_2,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $ptmove_date,
															"activity_typeid"			=> 4,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 4,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;
													}
													else
													{
														if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_liter_flow = 317;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_liter_flow = 326;
		                                                }

														$orders_liter_flow = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $ptmove_date,
															"activity_typeid"			=> 4,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 4,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;
													}
												}
												else if($sub_key == 100)
												{
													if($non_capped_o2_count == 2)
													{
			                                            $ordered_o2_liter_flow = 335;
			                                            $ordered_o2_liter_flow_2 = 344;

			                                            $orders_liter_flow = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $ptmove_date,
															"activity_typeid"			=> 4,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 4,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;

														$orders_liter_flow_2 = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow_2,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $ptmove_date,
															"activity_typeid"			=> 4,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 4,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;
													}
													else
													{
														if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_liter_flow = 335;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_liter_flow = 344;
		                                                }

														$orders_liter_flow = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $ptmove_date,
															"activity_typeid"			=> 4,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 4,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;
													}
												}
												else
												{
													if($sub_key != 121 && $sub_key != 194)
													{
														if(empty($sub_value))
														{
															$sub_value = 1;
														}
													}
													//this are for equipments ordered together with oxygen concentrator
													$orders[] = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $sub_key,
														"equipment_value"			=> $sub_value,
														"pickup_date"				=> $ptmove_date,
														"activity_typeid"			=> 4,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 4,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
												}
											}
										}
										else
										{
											if($sub_key != 121 && $sub_key != 194)
											{
												if(empty($sub_value))
												{
													$sub_value = 1;
												}
											}
											$orders[] = array(
												"patientID"					=> $patientID,
												"equipmentID"				=> $sub_key,
												"equipment_value"			=> $sub_value,
												"pickup_date"				=> $ptmove_date,
												"activity_typeid"			=> 4,
												"organization_id"			=> $organization_id,
												"ordered_by"				=> $id,
												"who_ordered_fname"			=> $data_post['who_ordered_fname'],
												"who_ordered_lname"			=> $data_post['who_ordered_lname'],
												"staff_member_fname"		=> $data_post['staff_member_fname'],
												"staff_member_lname"		=> $data_post['staff_member_lname'],
												"who_ordered_email"			=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
												"comment"					=> $data_post['new_order_notes'],
												"uniqueID"					=> $unique_id,
												"deliver_to_type"	    	=> $data_post['delivery_to_type'],
												'medical_record_id' 		=> $data_post['medical_record_id'],
												"order_status"	        	=> $order_current_status,
												"initial_order"				=> 0,
												"original_activity_typeid" 	=> 4,
												'addressID'					=> $addressID
											);
											$used_addressID = $addressID;
										}
									}
								}
							}
						}

						if($capped_o2_count > 0 || $non_capped_o2_count > 0)
						{
							$result = array();
							if($capped_o2_count == 2 || $non_capped_o2_count == 2)
							{
								$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow);
								$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow_2);
								$result['duration'] = $this->order_model->save_oxygen_order($orders_duration);
								$result['duration'] = $this->order_model->save_oxygen_order($orders_duration_2);
				                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device);
				                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device_2);
				                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable);
				                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable_2);
							}
							else
							{
								$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow);
								$result['duration'] = $this->order_model->save_oxygen_order($orders_duration);
				                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device);
				                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable);
							}
							if($result)
							{
								$create_new_order = $result;
							}

							$create_new_order = $this->order_model->saveorder($orders);
						}
						else
						{
							$create_new_order = $this->order_model->saveorder($orders);
						}
						if($create_new_order)
						{
							$insert_to_status = array(
								'order_uniqueID'      		=> $unique_id,
								'medical_record_id'   		=> $data_post['medical_record_id'],
								'patientID'			 		=> $patientID,
								'status_activity_typeid'   	=> 4,
								'order_status'   	  		=> $order_current_status,
								'addressID'   	  			=> $used_addressID,
								'pickup_date'   	  		=> $ptmove_date,
								'organization_id'   	  	=> $organization_id,
								'ordered_by'   	  			=> $id,
								'date_ordered'				=> date("Y-m-d h:i:s"),
								'original_activity_typeid'  => 4,
								'actual_order_date'   	  	=> '0000-00-00'
							);

							$inserted_successful = $this->order_model->insert_to_status($insert_to_status);

							if($inserted_successful)
							{
								$ptmove_type_sub = array(
									'ptmove_delivery_date'	  	=> $data_post['ptmove_delivery_date'],
									'ptmove_street'   		  	=> $data_post['ptmove_address'],
									'ptmove_placenum' 		  	=> $data_post['ptmove_placenum'],
									'ptmove_city'     		  	=> $data_post['ptmove_city'],
									'ptmove_state'    		  	=> $data_post['ptmove_state'],
									'ptmove_postal'   		 	=> $data_post['ptmove_postalcode'],
									'ptmove_patient_residence'	=> $data_post['ptmove_patient_residence'],
									'ptmove_nextofkin'        	=> $data_post['ptmove_nextofkin'],
									'ptmove_nextofkinrelation'	=> $data_post['ptmove_nextofkinrelation'],
									'ptmove_nextofkinphone'   	=> $data_post['ptmove_nextofkinphone'],
									'ptmove_patient_phone'	  	=> $data_post['ptmove_patient_phone'],
									'ptmove_alt_patient_phone'	=> $data_post['ptmove_alt_patient_phone'],
									'medical_record_id'	 	  	=> $data_post['medical_record_id'],
									'patientID'			 	  	=> $patientID,
									'order_uniqueID'	 	  	=> $unique_id
								);
								$this->order_model->save($ptmove_type_sub,'dme_sub_ptmove');
								$this->response_code = 0;
								$this->response_message = "Activity submitted successfully.";

								/*
								*	For email
								*/

								if($account_type_name!= "dme_admin" && $account_type_name!="dme_user")
								{
									$email_form = $this->form_email_temp($unique_id,4,TRUE);
									$this->load->config('email');
									$config =   $this->config->item('me_email');
									$this->load->library('email', $config);

									$this->email->from('orders@smarterchoice.us','AHMSLV');
									$this->email->to('orders@ahmslv.com');
									$this->email->cc('russel@smartstart.us');
									$this->email->subject('AHMSLV | Order Summary');
									$this->email->message($email_form);
									$this->email->send();
								}
							}
						}
					}
					else
					{
						$this->response_message = "Error saving information.";
					}
				}
				else
				{
					$this->response_message = "Error saving information. Customer Move address is still active.";
				}
			}
			else
			{
				$this->response_message = validation_errors('<span></span>');
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}

	public function change_status_to_respite($patientID)
	{
		$account_type_name = $this->session->userdata('account_type');
		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_cpnum','Staff Cellphone No.','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('respite_delivery_date','Respite Delivery Date','required');
		$this->form_validation->set_rules('respite_pickup_date','Respite Pickup Date','required');
		$this->form_validation->set_rules('respite_phone_number','Respite Phone Number','required');
		$this->form_validation->set_rules('respite_address','Respite Address','required');
		$this->form_validation->set_rules('dropdown_deliver_type','Respite Customer Residence','required');


		$this->form_validation->set_rules('equipments[]','Items','required');
		$this->form_validation->set_rules('activity_type','Activity Type','required');

		$id = $this->session->userdata('userID');
		if($this->input->post())
		{
			$person_who_ordered = $this->session->userdata('email');
			$data_post = $this->input->post();

			if($this->form_validation->run()===TRUE)
			{
				$all_respite_address = get_respite_addresses($patientID);

				$respite_exist_sign = 0;
				foreach ($all_respite_address as $key => $value) {
					if($value['street'] == $data_post['respite_address'] && $value['placenum'] == $data_post['respite_placenum'] && $value['city'] == $data_post['respite_city'] && $value['state'] == $data_post['respite_state'] && $value['postal_code'] == $data_post['respite_postalcode'])
					{
						$check_if_picked_up = check_if_address_picked_up($value['id'],$patientID);
						if(!empty($check_if_picked_up))
						{
							$respite_exist_sign = 1;
						}
					}
				}

				if($respite_exist_sign == 0)
				{
					$unique_id = strtotime(date('Y-m-d H:i:s'));
					$checked_unique_id = $this->order_model->checked_unique_id($unique_id);
					if(!empty($checked_unique_id))
					{
						$unique_id += 1;
					}

					$data_insert_patient_address = array(
						'patient_id' 	=> $patientID,
						'street'		=> $data_post['respite_address'],
						'placenum'		=> $data_post['respite_placenum'],
						'city'			=> $data_post['respite_city'],
						'state'			=> $data_post['respite_state'],
						'postal_code'	=> $data_post['respite_postalcode'],
						'type'			=> 2
					);
					$addressID = $this->order_model->insert_patient_address($data_insert_patient_address);

					if($unique_id!="")
					{
						/*
						* order information
						*/
						$delivery_date 		= date($data_post['respite_delivery_date']);
						$ordered_by  		= $id;
						$organization_id 	= $data_post['organization_id'];
						$activity_type		= $data_post['activity_type'];
						$order_current_status = "pending";
			            if($data_post['send_to_confirm_work_order_sign'] == 1)
			            {
			              $order_current_status = "tobe_confirmed";
			            }

						//order items
						$orders = array();
						$capped_o2_count = 0;
						$non_capped_o2_count = 0;
						$used_addressID = 0;
						foreach($data_post['equipments'] as $key=>$value)
						{
							if($value != 61 && $value != 29)
							{
								$orders[] = array(
									"patientID"					=> $patientID,
									"equipmentID"				=> $value,
									"equipment_value"			=> 1,
									"pickup_date"				=> $delivery_date,
									"activity_typeid"			=> $activity_type,
									"organization_id"			=> $organization_id,
									"ordered_by"				=> $id,
									"who_ordered_fname"			=> $data_post['who_ordered_fname'],
									"who_ordered_lname"			=> $data_post['who_ordered_lname'],
									"staff_member_fname"		=> $data_post['staff_member_fname'],
									"staff_member_lname"		=> $data_post['staff_member_lname'],
									"who_ordered_email"			=> $data_post['who_ordered_email'],
									"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
									"comment"					=> $data_post['new_order_notes'],
									"uniqueID"					=> $unique_id,
									"deliver_to_type"	    	=> $data_post['delivery_to_type'],
									'medical_record_id' 		=> $data_post['medical_record_id'],
									"order_status"	        	=> $order_current_status,
									"initial_order"				=> 0,
									"original_activity_typeid" 	=> 5,
									'addressID'					=> $addressID
								);
								$used_addressID = $addressID;
								if($value == 316 || $value == 325)
								{
									$capped_o2_count++;
									if($value == 316)
									{
										$ordered_o2_type = 5;
									}
									else
									{
										$ordered_o2_type = 10;
									}
								}
								else if($value == 334 || $value == 343)
								{
									$non_capped_o2_count++;
									if($value == 334)
									{
										$ordered_o2_type = 5;
									}
									else
									{
										$ordered_o2_type = 10;
									}
								}
							}
						}

						// if($data_post['commode_pail_counter'] > 1)
						// {
						// 	for ($i=2; $i <= $data_post['commode_pail_counter']; $i++) {
						// 		$orders[] = array(
						// 			"patientID"					=> $patientID,
						// 			"equipmentID"				=> 7,
						// 			"equipment_value"			=> 1,
						// 			"pickup_date"				=> $delivery_date,
						// 			"activity_typeid"			=> $activity_type,
						// 			"organization_id"			=> $organization_id,
						// 			"ordered_by"				=> $id,
						// 			"who_ordered_fname"			=> $data_post['who_ordered_fname'],
						// 			"who_ordered_lname"			=> $data_post['who_ordered_lname'],
						// 			"staff_member_fname"		=> $data_post['staff_member_fname'],
						// 			"staff_member_lname"		=> $data_post['staff_member_lname'],
						// 			"who_ordered_email"			=> $data_post['who_ordered_email'],
						// 			"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
						// 			"comment"					=> $data_post['new_order_notes'],
						// 			"uniqueID"					=> $unique_id,
						// 			"deliver_to_type"	    	=> $data_post['delivery_to_type'],
						// 			'medical_record_id' 		=> $data_post['medical_record_id'],
						// 			"order_status"	        	=> $order_current_status,
						// 			"initial_order"				=> 0,
						// 			"original_activity_typeid" 	=> 5,
						// 			'addressID'					=> $addressID
						// 		);
						// 	}
						// }

						/*
						* @sub equipments
						*
						*/
						foreach($data_post['subequipment'] as $key=>$value)
						{

							if(in_array($key,$data_post['equipments']))
							{
								foreach($value as $sub_key=>$sub_value)
								{
									if($sub_key=="radio")
									{
										foreach($sub_value as $radio_value)
										{
											if($radio_value == 78 || $radio_value == 79)
	                              			{
	                              				if($capped_o2_count == 2)
	                              				{
	                              					if($radio_value == 78)
	                              					{
	                              						$ordered_o2_duration = 318;
	                              						$ordered_o2_duration_2 = 327;
	                              					}
	                              					else
	                              					{
	                              						$ordered_o2_duration = 319;
	                              						$ordered_o2_duration_2 = 328;
	                              					}
	                              					$orders_duration = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_duration,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;

													$orders_duration_2 = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_duration_2,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
	                              				}
	                              				else
	                              				{

	                              					if($radio_value == 78)
	                              					{
	                              						if($ordered_o2_type == 5)
		                              					{
		                              						$ordered_o2_duration = 318;
		                              					}
		                              					else
		                              					{
		                              						$ordered_o2_duration = 327;
		                              					}
	                              					}
	                              					else
	                              					{
	                              						if($ordered_o2_type == 5)
		                              					{
		                              						$ordered_o2_duration = 319;
		                              					}
		                              					else
		                              					{
		                              						$ordered_o2_duration = 328;
		                              					}
	                              					}
	                              					$orders_duration = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_duration,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
	                              				}
	                              			}
	                              			else if($radio_value == 103 || $radio_value == 104)
	                              			{
	                              				if($non_capped_o2_count == 2)
	                              				{
	                              					if($radio_value == 103)
	                              					{
	                              						$ordered_o2_duration = 336;
	                              						$ordered_o2_duration_2 = 345;
	                              					}
	                              					else
	                              					{
	                              						$ordered_o2_duration = 337;
	                              						$ordered_o2_duration_2 = 346;
	                              					}
	                              					$orders_duration = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_duration,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;

													$orders_duration_2 = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_duration_2,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
	                              				}
	                              				else
	                              				{
	                              					if($radio_value == 103)
	                              					{
	                              						if($ordered_o2_type == 5)
		                              					{
		                              						$ordered_o2_duration = 336;
		                              					}
		                              					else
		                              					{
		                              						$ordered_o2_duration = 345;
		                              					}
	                              					}
	                              					else
	                              					{
	                              						if($ordered_o2_type == 5)
		                              					{
		                              						$ordered_o2_duration = 337;
		                              					}
		                              					else
		                              					{
		                              						$ordered_o2_duration = 346;
		                              					}
	                              					}
	                              					$orders_duration = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_duration,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
	                              				}
	                              			}
				                            else if($radio_value == 82 || $radio_value == 83 || $radio_value == 280)
				                            {
				                            	if($capped_o2_count == 2)
	                                      		{
	                                      			if($radio_value == 82)
			                                        {
			                                          	$ordered_o2_delivery_device = 320;
			                                          	$ordered_o2_delivery_device_2 = 329;
			                                        }
			                                        else if($radio_value == 83)
			                                        {
			                                        	$ordered_o2_delivery_device = 321;
			                                          	$ordered_o2_delivery_device_2 = 330;
			                                        }
			                                        else
			                                        {
			                                          	$ordered_o2_delivery_device = 322;
			                                          	$ordered_o2_delivery_device_2 = 331;
			                                        }
					                            	$orders_delivery_device = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_delivery_device,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;

													$orders_delivery_device_2 = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_delivery_device_2,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid"	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
	                                      		}
	                                      		else
	                                      		{
	                                      			if($radio_value == 82)
			                                        {
			                                          	if($ordered_o2_type == 5)
			                                          	{
			                                            	$ordered_o2_delivery_device = 320;
			                                          	}
			                                          	else
			                                          	{
			                                            	$ordered_o2_delivery_device = 329;
			                                          	}
			                                        }
			                                        else if($radio_value == 83)
			                                        {
			                                        	if($ordered_o2_type == 5)
			                                          	{
			                                            	$ordered_o2_delivery_device = 321;
			                                          	}
			                                          	else
			                                          	{
			                                            	$ordered_o2_delivery_device = 330;
			                                          	}
			                                        }
			                                        else
			                                        {
			                                          	if($ordered_o2_type == 5)
			                                          	{
			                                            	$ordered_o2_delivery_device = 322;
			                                          	}
			                                          	else
			                                          	{
			                                            	$ordered_o2_delivery_device = 331;
			                                          	}
			                                        }
			                                        $orders_delivery_device = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_delivery_device,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
	                                      		}
				                           	}
				                           	else if($radio_value == 105 || $radio_value == 106 || $radio_value == 281)
				                           	{
				                           		if($non_capped_o2_count == 2)
	                                      		{
	                                      			if($radio_value == 105)
			                                        {
			                                          	$ordered_o2_delivery_device = 338;
			                                          	$ordered_o2_delivery_device_2 = 347;
			                                        }
			                                        else if($radio_value == 106)
			                                        {
			                                        	$ordered_o2_delivery_device = 339;
			                                          	$ordered_o2_delivery_device_2 = 348;
			                                        }
			                                        else
			                                        {
			                                          	$ordered_o2_delivery_device = 340;
			                                          	$ordered_o2_delivery_device_2 = 349;
			                                        }
					                            	$orders_delivery_device = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_delivery_device,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;

													$orders_delivery_device_2 = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_delivery_device_2,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
	                                      		}
	                                      		else
	                                      		{
	                                      			if($radio_value == 105)
		                                            {
		                                                if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_delivery_device = 338;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_delivery_device = 347;
		                                                }
		                                            }
		                                            else if($radio_value == 106)
		                                            {
		                                              if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_delivery_device = 339;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_delivery_device = 348;
		                                                }
		                                            }
		                                            else
		                                            {
		                                                if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_delivery_device = 340;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_delivery_device = 349;
		                                                }
		                                            }
		                                            $orders_delivery_device = array(
		                                                "patientID"       			=> $patientID,
		                                                "equipmentID"     			=> $ordered_o2_delivery_device,
		                                                "equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
		                                            );
		                                            $used_addressID = $addressID;
	                                      		}
				                           	}
			                                else if($radio_value == 241 || $radio_value == 242)
			                                {
			                                	if($capped_o2_count == 2)
			                                	{
			                                		if($radio_value == 241)
		                                            {
		                                                $ordered_o2_e_portable = 323;
		                                                $ordered_o2_e_portable_2 = 332;
		                                            }
		                                            else
		                                            {
		                                                $ordered_o2_e_portable = 324;
		                                                $ordered_o2_e_portable_2 = 333;
		                                            }

				                                	$orders_e_portable = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_e_portable,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;

													$orders_e_portable_2 = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_e_portable_2,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
			                                	}
			                                	else
			                                	{
			                                		if($radio_value == 241)
		                                            {
		                                              if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_e_portable = 323;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_e_portable = 332;
		                                                }
		                                            }
		                                            else
		                                            {
		                                                if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_e_portable = 324;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_e_portable = 333;
		                                                }
		                                            }
		                                            $orders_e_portable = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_e_portable,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
			                                	}
			                                }
			                                else if($radio_value == 243 || $radio_value == 244)
			                                {
			                                	if($non_capped_o2_count == 2)
	                                          	{
				                                	if($radio_value == 243)
		                                            {
		                                                $ordered_o2_e_portable = 341;
		                                                $ordered_o2_e_portable_2 = 350;
		                                            }
		                                            else
		                                            {
		                                                $ordered_o2_e_portable = 342;
		                                                $ordered_o2_e_portable_2 = 351;
		                                            }
		                                            $orders_e_portable = array(
		                                              	"patientID"       			=> $patientID,
		                                              	"equipmentID"     			=> $ordered_o2_e_portable,
		                                              	"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid"	=> 5,
														'addressID'					=> $addressID
		                                            );
		                                            $used_addressID = $addressID;

		                                            $orders_e_portable_2 = array(
		                                              	"patientID"       			=> $patientID,
		                                              	"equipmentID"     			=> $ordered_o2_e_portable_2,
		                                              	"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
		                                            );
		                                            $used_addressID = $addressID;
		                                        }
		                                        else
		                                        {
		                                        	if($radio_value == 243)
		                                            {
		                                              if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_e_portable = 341;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_e_portable = 350;
		                                                }
		                                            }
		                                            else
		                                            {
		                                                if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_e_portable = 342;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_e_portable = 351;
		                                                }
		                                            }
		                                            $orders_e_portable = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $ordered_o2_e_portable,
														"equipment_value"			=> 1,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
		                                        }
			                                }
			                                else
			                                {

												$orders[] = array(
													"patientID"					=> $patientID,
													"equipmentID"				=> $radio_value,
													"equipment_value"			=> 1,
													"pickup_date"				=> $delivery_date,
													"activity_typeid"			=> $activity_type,
													"organization_id"			=> $organization_id,
													"ordered_by"				=> $id,
													"who_ordered_fname"			=> $data_post['who_ordered_fname'],
													"who_ordered_lname"			=> $data_post['who_ordered_lname'],
													"staff_member_fname"		=> $data_post['staff_member_fname'],
													"staff_member_lname"		=> $data_post['staff_member_lname'],
													"who_ordered_email"			=> $data_post['who_ordered_email'],
													"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
													"comment"					=> $data_post['new_order_notes'],
													"uniqueID"					=> $unique_id,
													"deliver_to_type"	    	=> $data_post['delivery_to_type'],
													'medical_record_id' 		=> $data_post['medical_record_id'],
													"order_status"	        	=> $order_current_status,
													"initial_order"				=> 0,
													"original_activity_typeid" 	=> 5,
													'addressID'					=> $addressID
												);
												$used_addressID = $addressID;
											}
										}
									}
									else
									{
										//this changes is to separate the two oxygen concentrator in the DB
										if($capped_o2_count > 0 || $non_capped_o2_count > 0)
										{
											if($sub_key != 80 && $sub_key != 81 && $sub_key != 101 && $sub_key != 102)
											{

												//this is for CPAP = IPAP if the equipment is ordered with oxygen concentrator
												if($sub_key == 114)
												{
													$orders[] = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $sub_key,
														"equipment_value"			=> $sub_value,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
												}
												else
												//this is for the BIPAP = IPAP if the equipment is ordered with oxygen concentrator
												if($sub_key == 109)
												{
													$orders[] = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $sub_key,
														"equipment_value"			=> $sub_value,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
												}
												else
												//this is for the BIPAP = EPAP if the equipment is ordered with oxygen concentrator
												if($sub_key == 110)
												{
													$orders[] = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $sub_key,
														"equipment_value"			=> $sub_value,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
												}
												else
												//this is for the BIPAP = RATE if the equipment is ordered with oxygen concentrator
												if($sub_key == 111)
												{
													$orders[] = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $sub_key,
														"equipment_value"			=> $sub_value,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
												}
												else if($sub_key == 77)
												{
													if($capped_o2_count == 2)
													{
			                                            $ordered_o2_liter_flow = 317;
			                                            $ordered_o2_liter_flow_2 = 326;

			                                            $orders_liter_flow = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $delivery_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 5,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;

														$orders_liter_flow_2 = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow_2,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $delivery_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 5,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;
													}
													else
													{
														if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_liter_flow = 317;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_liter_flow = 326;
		                                                }

														$orders_liter_flow = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $delivery_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 5,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;
													}
												}
												else if($sub_key == 100)
												{
													if($non_capped_o2_count == 2)
													{
			                                            $ordered_o2_liter_flow = 335;
			                                            $ordered_o2_liter_flow_2 = 344;

			                                            $orders_liter_flow = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $delivery_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 5,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;

														$orders_liter_flow_2 = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow_2,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $delivery_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 5,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;
													}
													else
													{
														if($ordered_o2_type == 5)
		                                                {
		                                                  $ordered_o2_liter_flow = 335;
		                                                }
		                                                else
		                                                {
		                                                  $ordered_o2_liter_flow = 344;
		                                                }

														$orders_liter_flow = array(
															"patientID"					=> $patientID,
															"equipmentID"				=> $ordered_o2_liter_flow,
															"equipment_value"			=> $sub_value,
															"pickup_date"				=> $delivery_date,
															"activity_typeid"			=> $activity_type,
															"organization_id"			=> $organization_id,
															"ordered_by"				=> $id,
															"who_ordered_fname"			=> $data_post['who_ordered_fname'],
															"who_ordered_lname"			=> $data_post['who_ordered_lname'],
															"staff_member_fname"		=> $data_post['staff_member_fname'],
															"staff_member_lname"		=> $data_post['staff_member_lname'],
															"who_ordered_email"			=> $data_post['who_ordered_email'],
															"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
															"comment"					=> $data_post['new_order_notes'],
															"uniqueID"					=> $unique_id,
															"deliver_to_type"	    	=> $data_post['delivery_to_type'],
															'medical_record_id' 		=> $data_post['medical_record_id'],
															"order_status"	        	=> $order_current_status,
															"initial_order"				=> 0,
															"original_activity_typeid" 	=> 5,
															'addressID'					=> $addressID
														);
														$used_addressID = $addressID;
													}
												}
												else
												{
													if($sub_key != 121 && $sub_key != 194)
													{
														if(empty($sub_value))
														{
															$sub_value = 1;
														}
													}
													//this are for equipments ordered together with oxygen concentrator
													$orders[] = array(
														"patientID"					=> $patientID,
														"equipmentID"				=> $sub_key,
														"equipment_value"			=> $sub_value,
														"pickup_date"				=> $delivery_date,
														"activity_typeid"			=> $activity_type,
														"organization_id"			=> $organization_id,
														"ordered_by"				=> $id,
														"who_ordered_fname"			=> $data_post['who_ordered_fname'],
														"who_ordered_lname"			=> $data_post['who_ordered_lname'],
														"staff_member_fname"		=> $data_post['staff_member_fname'],
														"staff_member_lname"		=> $data_post['staff_member_lname'],
														"who_ordered_email"			=> $data_post['who_ordered_email'],
														"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
														"comment"					=> $data_post['new_order_notes'],
														"uniqueID"					=> $unique_id,
														"deliver_to_type"	    	=> $data_post['delivery_to_type'],
														'medical_record_id' 		=> $data_post['medical_record_id'],
														"order_status"	        	=> $order_current_status,
														"initial_order"				=> 0,
														"original_activity_typeid" 	=> 5,
														'addressID'					=> $addressID
													);
													$used_addressID = $addressID;
												}
											}
										}else{
											if($sub_key != 121 && $sub_key != 194)
											{
												if(empty($sub_value))
												{
													$sub_value = 1;
												}
											}
											$orders[] = array(
												"patientID"					=> $patientID,
												"equipmentID"				=> $sub_key,
												"equipment_value"			=> $sub_value,
												"pickup_date"				=> $delivery_date,
												"activity_typeid"			=> $activity_type,
												"organization_id"			=> $organization_id,
												"ordered_by"				=> $id,
												"who_ordered_fname"			=> $data_post['who_ordered_fname'],
												"who_ordered_lname"			=> $data_post['who_ordered_lname'],
												"staff_member_fname"		=> $data_post['staff_member_fname'],
												"staff_member_lname"		=> $data_post['staff_member_lname'],
												"who_ordered_email"			=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"			=> $data_post['who_ordered_cpnum'],
												"comment"					=> $data_post['new_order_notes'],
												"uniqueID"					=> $unique_id,
												"deliver_to_type"	    	=> $data_post['delivery_to_type'],
												'medical_record_id' 		=> $data_post['medical_record_id'],
												"order_status"	        	=> $order_current_status,
												"initial_order"				=> 0,
												"original_activity_typeid" 	=> 5,
												'addressID'					=> $addressID
											);
											$used_addressID = $addressID;
										}
									}
								}
							}
						}

						if($capped_o2_count > 0 || $non_capped_o2_count > 0)
						{
							$result = array();
							if($capped_o2_count == 2 || $non_capped_o2_count == 2)
							{
								$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow);
								$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow_2);
								$result['duration'] = $this->order_model->save_oxygen_order($orders_duration);
								$result['duration'] = $this->order_model->save_oxygen_order($orders_duration_2);
				                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device);
				                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device_2);
				                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable);
				                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable_2);
							}
							else
							{
								$result['liter_flow'] =	$this->order_model->save_oxygen_order($orders_liter_flow);
								$result['duration'] = $this->order_model->save_oxygen_order($orders_duration);
				                $result['delivery_device']  = $this->order_model->save_oxygen_order($orders_delivery_device);
				                $result['e_portable_system']  = $this->order_model->save_oxygen_order($orders_e_portable);
							}
							if($result)
							{
								$saveorder = $result;
							}

							$saveorder = $this->order_model->saveorder($orders);
						}
						else
						{
							$saveorder = $this->order_model->saveorder($orders);
						}

						if($saveorder)
						{
							$insert_to_status = array(
								'order_uniqueID'      		=> $unique_id,
								'medical_record_id'   		=> $data_post['medical_record_id'],
								'patientID'			 		=> $patientID,
								'status_activity_typeid'  	=> $activity_type,
								'order_status'   	  		=> $order_current_status,
								'addressID'   	  			=> $used_addressID,
								'pickup_date'   	  		=> $delivery_date,
								'organization_id'   	  	=> $organization_id,
								'ordered_by'   	  			=> $id,
								'date_ordered'				=> date("Y-m-d h:i:s"),
								'original_activity_typeid'  => 5,
								'actual_order_date'   	  	=> '0000-00-00'
							);
							$this->order_model->insert_to_status($insert_to_status);

							$respite_type_sub = array(
								'respite_delivery_date' 	=> date($data_post['respite_delivery_date']),
								'respite_pickup_date'   	=> date($data_post['respite_pickup_date']),
								'respite_address'   		=> $data_post['respite_address'],
								'respite_placenum'			=> $data_post['respite_placenum'],
								'respite_city'     			=> $data_post['respite_city'],
								'respite_state'    			=> $data_post['respite_state'],
								'respite_postal'   			=> $data_post['respite_postalcode'],
								'respite_deliver_to_type' 	=> $data_post['dropdown_deliver_type'],
								'medical_record_id'	 		=> $data_post['medical_record_id'],
								'patientID'			 		=> $patientID,
								'respite_phone_number'		=> $data_post['respite_phone_number'],
								'order_uniqueID'	 		=> $unique_id
							);
							$this->order_model->save($respite_type_sub,'dme_sub_respite');

							$this->response_code = 0;
							$this->response_message = "Activity submitted successfully.";
							/*
							*	For email
							*/

							if($account_type_name != "dme_admin" && $account_type_name!="dme_user")
							{
								$email_form = $this->form_email_temp($unique_id,5,TRUE);

								$this->load->config('email');
								$config =   $this->config->item('me_email');
								$this->load->library('email', $config);

								$this->email->from('orders@smarterchoice.us','AHMSLV');
								$this->email->to('orders@ahmslv.com');
								$this->email->cc('russel@smartstart.us');
								$this->email->subject('AHMSLV | Order Summary');
								$this->email->message($email_form);
								$this->email->send();
							}
						}
					}
					else
					{
						$this->response_message = "Error saving information. Respite address is still active.";
					}
				}
				else
				{
					$this->response_message = "Error saving information. The same respite address is still active.";
				}
			}
			else
			{
				$this->response_message = validation_errors('<span></span>');
			}
			echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
			exit;
		}
	}

	public function search()
	{
		$data_get = $this->input->get();

		$data['term'] = (isset($get_data['term'])) ? $get_data['term'] : '';
		$data['active_nav'] = "patient_menu";
		echo $data['term'];
		$this->templating_library->set('title','Search Customer');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/search_page','pages/search_page', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function search_patients($search_string='', $hospice_id='')
	{
		$post_data = $this->input->post();
		$created_by = $this->session->userdata('group_id');
		$search_string = "";
		if(isset($_GET['searchString']))
		{
			$search_string = $_GET['searchString'];
		}
		$count = 0;
		if($this->session->userdata('account_type') == 'dme_admin')
		{
			$searches = $this->order_model->search_all_patients_v2($this->session->userdata('user_location'),$search_string);
			$count 	  = $this->order_model->search_all_patients_v2($this->session->userdata('user_location'),$search_string,true);
		}
		else
		{
			$searches 	= $this->order_model->search_all_patients_hospice($search_string, $created_by);
			$count 		= $this->order_model->search_all_patients_hospice($search_string, $created_by,true);
		}
		if($searches)
		{
			foreach ($searches as $search)
			{
				$patient_order = get_patient_order($search['patientID']);
				if(!empty($patient_order))
				{
					$check_active_patient = $this->order_model->active_patient_check($search['patientID']);
					if(empty($check_active_patient))
					{
						echo "<tr style='color:#808080 !important;cursor:pointer;border:1px solid #f5f5f5' class='patient_results form-control' data-id='".$search['medical_record_id']."' data-value='".$search['medical_record_id']."' data-fname='".$search['p_fname']."' data-lname='".$search['p_lname']."' data-patient-id='".$search['patientID']."'><td>".$search['medical_record_id'].""." - "."".$search['p_lname']."".", "."".$search['p_fname']."</td></tr>";
					}
					else
					{
						echo "<tr style='color:#232121 !important;cursor:pointer;border:1px solid #f5f5f5' class='patient_results form-control' data-id='".$search['medical_record_id']."' data-value='".$search['medical_record_id']."' data-fname='".$search['p_fname']."' data-lname='".$search['p_lname']."' data-patient-id='".$search['patientID']."'><td>".$search['medical_record_id'].""." - "."".$search['p_lname']."".", "."".$search['p_fname']."</td></tr>";
					}
				}
			}
			if($count>5)
			{
				echo "<tr style='cursor:pointer;border:1px solid #f5f5f5;background-color: #58666e;color: white;'
						class='patient_results form-control'>
							<td align='center' style='font-style: italic;font-size: 12px;'>There are <strong>{$count}</strong> patients in this result. <a style='color:white;text-decoration:underline;' class='result-lists' href='javascript:;'>View all results</a></td></tr>";
			}
		}
		else
		{
			echo "<tr style='cursor:pointer;border:1px solid #f5f5f5' class='form-control'><td>No Results Found.</td></tr>";
		}
	}

	public function return_search($view_type = "")
	{
		$data_get = $this->input->get();

		$data['term'] = (isset($data_get['term'])) ? $data_get['term'] : '';
		$data['query'] = (isset($data_get['query'])) ? $data_get['query'] : '';
		$data['param'] = (isset($data_get['param'])) ? $data_get['param'] : '';
		$data['param2'] = (isset($data_get['param2'])) ? $data_get['param2'] : '';

		$where = array(
			'term' => str_replace(' ', '%', $data['term']),
			'query' => str_replace(' ', '%', $data['query']),
			'param' => str_replace(' ', '%', $data['param']),
			'param2' => str_replace(' ', '%', $data['param2'])
		);

		$active_patients_list = $this->order_model->list_active_patients();
		// $result = $this->order_model->list_orders_for_patient_searchv2($uniqueID="",$where);
		$result = $this->order_model->list_orders_for_patient_searchv3($this->session->userdata('user_location'),$uniqueID="",$where);
		$data['order_status_list'] = true;

		$arr2 = array_msort($result, array('p_lname' => SORT_ASC,'p_fname' => SORT_ASC));
		$data['orders'] = array();
		$patient_total_los = 0;
		$total_patient_days = 0;
		if(!empty($arr2))
		{
			$count_inside = 0;
			foreach ($arr2 as $result_inside)
			{
				$patient_order = get_patient_order($result_inside['patientID']);
				if(!empty($patient_order))
				{
					$data['orders'][$count_inside] = $result_inside;
					$data['orders'][$count_inside]['active_patient'] = 0;
					$patient_total_los = $patient_total_los + $result_inside['length_of_stay'];
					$total_patient_days = $total_patient_days + $result_inside['patient_days'];

					foreach($active_patients_list as $loop_active_patient)
					{
						if($loop_active_patient['patientID'] == $result_inside['patientID'])
						{
							// 0 = Inactive, 1 = Active
							$data['orders'][$count_inside]['active_patient'] = 1;
						}
					}
					$count_inside++;
				}
			}
			$data['counting'] = count($data['orders']);
		}
		else
		{
			$data['counting'] = 0;
		}
		$data['total_los_for_today']['patient_total_los'] = $patient_total_los;
		$data['total_patient_days_for_today']['total_patient_days'] = $total_patient_days;
		$data['search_type'] = "patient_search";

		$this->templating_library->set('title','Search Customer');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		if($view_type == 'list-view')
		{
			$this->templating_library->set_view('pages/patient_order_list','pages/patient_order_list', $data);
		}
		else
		{
			$this->templating_library->set_view('pages/patient_order_grid','pages/patient_order_grid', $data);
		}
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	// $data['counts'] = count($this->order_model->list_orders_for_patient_searchv2($uniqueID="",$where));

	public function get_lot_comments($equipmentID, $uniqueID)
	{
		// $data['comments'] = $this->order_model->get_comments($equipmentID,$uniqueID);
		$data['comments'] = "";
		// printA($data['comments']);
		// exit;
		$data['results'] = $this->order_model->return_lot_info($equipmentID, $uniqueID);
		$this->load->view('pages/lot_comments', $data);
	}


	public function add_lot_comment()
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('lot_comment','Lot # Comment','required');

		if($data_post)
		{
			if($this->form_validation->run())
			{
				$array = array(
					'userID' => $data_post['userID'] ,
					'equipmentID' => $data_post['equipmentID'],
					'uniqueID'	 => $data_post['uniqueID'],
					'lot_comment'	=> $data_post['lot_comment']
				);

				// $success = $this->order_model->add_lot_comment($array);

				$this->response_code = 0;
				$this->response_message = "Successfully Inserted Notes.";

			}
			else
			{
				$this->response_message = "Failed to insert notes.";
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}
		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;
	}

	public function new_equipment($uniqueID, $hospice_id)
	{
		$user_type = $this->session->userdata('account_type');

		//if patient has orders
		$returns = $this->order_model->list_orders($uniqueID);
		$informations = $this->order_model->get_order_info($uniqueID, $hospice_id); //newly added $hospice_id. 07/13/2015.
		//if patient has no orders
		$patient = $this->order_model->get_patient_noorder_info($uniqueID,$hospice_id);
		$data['comments'] = $this->order_model->get_all_comments($uniqueID);
		$hospiceID = $this->session->userdata('group_id');

		$datas = array();
		if(!empty($returns) && !empty($informations))
		{
			$data['summarys'] = $returns;
			$data['informations'] = $informations;
		}
		else if(empty($returns) && !empty($informations))
		{
			$data['summarys'] = "";
			$data['informations'] = $informations;
		}
		else{
			$data['summarys'] = "";
			$data['informations'] = $patient;
		}

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				if($hospice_id != 0)
				{
					$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospice_id);
				}
				else
				{
					$children = $this->order_model->get_equipment($cat['categoryID']);
				}

			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}

			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

		$id = $uniqueID;

	    $patientID = get_patientID($id,$hospice_id);
	    $patient_addresses = get_patient_addresses($patientID['patientID']);
	    $new_response = array();
	    $new_addresses_response = array();
	    $data['addressID'] = array();

	    foreach ($patient_addresses as $key=>$value) {
	    	$new_response_query = $this->order_model->get_items_for_pickup_other_address_v2($id,$hospice_id,$value['id']);
	    	if($new_response_query)
	    	{
	    		$new_response[] = $new_response_query;
	    		$data['addressID'][] = $value['id'];
	    	}
	    }
	    $new_orders = array();
	    $my_count = 0;
        if(!empty($new_response))
        {
        	foreach ($new_response as $new_response_loop) {
        		foreach($new_response_loop as $key=>$value)
	            {
	                $cat_ = $value['type'];

	                if($value['parentID']==0)
	                {
	                  $new_orders[$my_count][$cat_][trim($value['key_desc'])][] = $value;
	                  $new_addresses_response[$value['id']][$cat_][trim($value['key_desc'])][] = $value;
	                }
	                else
	                {
	                  $new_orders[$my_count][$cat_][trim($value['parent_name'])]['children'][] = $value;
	                  $new_addresses_response[$value['id']][$cat_][trim($value['parent_name'])]['children'][] = $value;
	                }
	            }
	            $my_count++;
        	}
        }
        $data['new_orders'] = $new_orders;
        $data['new_addresses_response'] = $new_addresses_response;

		$patient_id = get_patient_id_v2($id,$hospice_id);
        $data['patient_move_addresses_new'] = get_patient_move_addresses($patient_id['patientID']);
        $patient_move_response = array();
        $ptmove_addressID = array();
        foreach ($data['patient_move_addresses_new'] as $row) {
        	$patient_move_response[] = $this->order_model->get_items_for_pickup_other_address_v2($id,$hospice_id,$row['id']);
        	$ptmove_addressID[] = $row['id'];
        }
        $orders_patient_move = array();
        $my_count_ptmove = 0;
        if(!empty($patient_move_response))
        {
        	foreach ($patient_move_response as $row) {
        		foreach($row as $key=>$value)
	            {
	                $cat_ = $value['type'];

	                if($value['parentID']==0)
	                {
	                  $orders_patient_move[$ptmove_addressID[$my_count_ptmove]][$cat_][trim($value['key_desc'])][] = $value;
	                }
	                else
	                {
	                  $orders_patient_move[$ptmove_addressID[$my_count_ptmove]][$cat_][trim($value['parent_name'])]['children'][] = $value;
	                }
	            }
	            $my_count_ptmove++;
        	}
        }
        $data['orders_patient_move'] = $orders_patient_move;

        $data['respite_addresses_new'] = get_respite_addresses($patient_id['patientID']);
        $respite_response = array();
        $respite_addressID = array();
        foreach ($data['respite_addresses_new'] as $row) {
        	$respite_response[] = $this->order_model->get_items_for_pickup_other_address_v2($id,$hospice_id,$row['id']);
        	$respite_addressID[] = $row['id'];
        }
        $orders_respite = array();
        $my_count_respite = 0;
        if(!empty($respite_response))
        {
        	foreach ($respite_response as $row) {
        		foreach($row as $key=>$value)
	            {
	                $cat_ = $value['type'];

	                if($value['parentID']==0)
	                {
	                  $orders_respite[$respite_addressID[$my_count_respite]][$cat_][trim($value['key_desc'])][] = $value;
	                }
	                else
	                {
	                  $orders_respite[$respite_addressID[$my_count_respite]][$cat_][trim($value['parent_name'])]['children'][] = $value;
	                }
	            }
	            $my_count_respite++;
        	}
        }
        $data['orders_respite'] = $orders_respite;

        // $response = $this->order_model->get_orders_for_pickup_items($id, $hospice_id);
        $response = $this->order_model->get_items_for_pickup_v2($id,$hospice_id);
        $orders = array();
        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];

                if($value['parentID']==0)
                {
                  $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                  $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;

        //
        // EXCHANGE EQUIPMENT START
        //
        $response_exchange = $this->order_model->get_items_for_exchange_v2($id,$hospice_id);
        $orders_exchange = array();
        if(!empty($response_exchange))
        {
            foreach($response_exchange as $key=>$value)
            {
                $cat_ = $value['type'];

                if($value['parentID']==0)
                {
                  $orders_exchange[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                  $orders_exchange[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders_exchange'] = $orders_exchange;

        $patientID = get_patientID($id,$hospice_id);
	    $patient_addresses = get_patient_addresses($patientID['patientID']);
        $new_response_exchange = array();
	    $data['addressID_exchange'] = array();

	    foreach ($patient_addresses as $key=>$value) {
	    	$new_response_query = $this->order_model->get_items_for_pickup_other_address_exchange_v2($id,$hospice_id,$value['id']);
	    	if($new_response_query)
	    	{
	    		$new_response_exchange[] = $new_response_query;
	    		$data['addressID_exchange'][] = $value['id'];
	    	}
	    }
	    $new_orders_exchange = array();
	    $my_count = 0;
        if(!empty($new_response_exchange))
        {
        	foreach ($new_response_exchange as $new_response_loop) {
        		foreach($new_response_loop as $key=>$value)
	            {
	                $cat_ = $value['type'];

	                if($value['parentID']==0)
	                {
	                  $new_orders_exchange[$my_count][$cat_][trim($value['key_desc'])][] = $value;
	                }
	                else
	                {
	                  $new_orders_exchange[$my_count][$cat_][trim($value['parent_name'])]['children'][] = $value;
	                }
	            }
	            $my_count++;
        	}
        }
        $data['new_orders_exchange'] = $new_orders_exchange;
        //
        // EXCHANGE EQUIPEMT END
        //

        $data['addresses'] = $this->order_model->list_addresses($uniqueID, $hospice_id);
        $data['ptmove_address'] = $this->order_model->get_old_address_ptmove($uniqueID, $hospice_id); //comment if it will cause errors $hospice_id added on 07-13-2015
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $uniqueID;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id);
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }
        $data['hospice_id']	= $hospice_id;



		$this->templating_library->set('title','Activity Type');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/add_new_equipment','pages/add_new_equipment' , $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function edit_patient_info($medical_record_id, $hospice_id='')
	{
		$user_type = $this->session->userdata('account_type');

		$data['sign_noorder'] = 0;
		$returns = $this->order_model->list_orders($medical_record_id);
		$informations = $this->order_model->get_order_info($medical_record_id, $hospice_id);
		$data['comments'] = $this->order_model->get_all_comments($medical_record_id);

		$datas = array();
		$data['summarys'] = $returns;
		$data['informations'] = $informations;

		$data['note_counts'] = $this->order_model->count_patient_comments($medical_record_id);

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $medical_record_id;

        $response = $this->order_model->get_orders($id, $hospice_id);
        $order_summary = $this->order_model->get_orders($id, $hospice_id);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        $data['summaries'] = $order_summary;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_record_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id);
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }

		$data['infos'] = $this->order_model->get_patient_profile($medical_record_id, $hospice_id);

		if(empty($data['infos']))
		{
			$data['infos'] = $this->order_model->get_patient_noorder_info($medical_record_id, $hospice_id);
			$data['sign_noorder'] = 1;
		}

		$this->templating_library->set('title','Edit Customer Info');
		$this->templating_library->set_view('pages/edit_patient_profile','pages/edit_patient_profile', $data);
		$this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
	}
	public function confirmed_order_details($medical_record_id, $uniqueID, $act_id="", $hospiceID="")
	{
		$user_type = $this->session->userdata('account_type');

		$returns = $this->order_model->list_orders($medical_record_id);
		$informations = $this->order_model->get_order_info($medical_record_id, $hospiceID);
		$data['comments'] = $this->order_model->get_all_comments($medical_record_id);

		$datas = array();
		$data['summarys'] = $returns;
		$data['informations'] = $informations;

		$data['medical_record_num'] = $medical_record_id;
		$data['hospice_id'] = $hospiceID;
		$data['act_id'] = $act_id;

		$data['note_counts'] = $this->order_model->count_patient_comments($medical_record_id);

		$data['work_order_number'] = $uniqueID;

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $medical_record_id;

        $response = $this->order_model->get_orders($id);
        $order_summary = $this->order_model->get_orders_by_workorder($id, $uniqueID, $hospiceID);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        $data['summaries'] = $order_summary;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_record_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id);
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }
		$data['infos'] = $this->order_model->get_patient_profile($medical_record_id, $hospiceID);
		$data['work_order_number'] = $uniqueID;
		$this->templating_library->set('title','Edit Patient Info');
		$this->templating_library->set_view('pages/confirmed_modal-04-21-2018','pages/confirmed_modal-04-21-2018', $data);
		//$this->templating_library->set_view('common/foot','common/foot'); //put this back if it causes some errors.
		$this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
	}
	public function confirmed_order_detailsnew($medical_record_id,$uniqueID,$act_id="",$hospiceID="")
	{
		$patientinfo = array();
				$user_type = $this->session->userdata('account_type');
				$informations 		= $this->order_model->get_order_info($medical_record_id, $hospiceID);
				$patientinfo = $this->order_model->get_patient_profile($medical_record_id, $hospiceID);

				$datas = array();

				$data['user_type'] = $user_type;

				//basic patient details
				$data['medical_record_num'] = $medical_record_id;
				$data['hospice_id'] = $hospiceID;
				$data['act_id'] = $act_id;
				$data['note_counts'] = $this->order_model->count_patient_comments($medical_record_id);
				$data['work_order_number'] = $uniqueID;

				/** For Equipments **/
				// $categories = $this->order_model->get_equipment_category();
				// $equipment_array = array();
				//
				// foreach($categories as $cat)
				// {
				// 	if($user_type == 'dme_admin')
				// 	{
				// 		$children = $this->order_model->get_equipment($cat['categoryID']);
				// 	}
				// 	else
				// 	{
				// 		$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
				// 	}
				// 	$equipment_array[] = array(
				// 		'categoryID' => $cat['categoryID'],
				// 		'type'		 => $cat['type'],
				// 		'children'	 => $children,
				// 		'division'	 => floor(count($children)/2),
				// 		'last'	 	 => count($children)-1
				// 	);
				// }
				// $data['equipments'] = $equipment_array;

	    	$id = $medical_record_id;

        	$order_summary = $this->order_model->get_orders_by_workorder($id, $uniqueID, $hospiceID,0);

        	$repeating_equipment = array();
			$equipmentID = 0;
			$count_here = 1;
			$count_loop = 0;

			$summary_lists = "";
			$packaged_items_ids_list = [486,163,164,68,159,160,161,162,316,325,334,343,466,36,178,422,259,415,174,490,492,67,157];
		    $packaged_item_sign = 0;
		    $packaged_items_list = array();
		    $commode_pail_count = 0;
		    $item_description_count = 1;
		    $oxygen_cylinder_rack_count = 1;
		    $bed_chair_alarm_count = 1;
		    $scale_chair_count = 1;
		    $patient_lift_sling_count = 0;
		    $patient_lift_sling_count_equipment = 1;
		    $oxygen_cylinder_e_refill_count = 1;
		    $oxygen_cylinder_m6_refill_count = 1;
		    $sequence_count = 0;
		    $adding_weight_sign = 0;
		    $adding_weight_equipment = 0;
		    $last_equipmentID = 0;
		    $last_equipmentID_count = 1;
		    $o2_concentrator_follow_up_sign = 0;
		    $o2_concentrator_follow_up_equipmentID = 0;
		    $o2_concentrator_follow_up_uniqueID = 0;
		    $o2_concentrator_follow_up_uniqueID_old = 0;
				$data['count_parent_summaries'] = 0;

				$address_type 			 = array();
				$ptmove_addresses_ID = array();
				$respite_addresses_ID = array();

				if(!empty($patientinfo)){
					$temppatientinfo = $patientinfo[0];

					$address_type 				= get_address_type($temppatientinfo['addressID']);
					$temp_pt_add 	= get_ptmove_addresses_ID_v2($temppatientinfo['patientID']);
					foreach($temp_pt_add as $key=>$val){
						$val['sequence'] = $key + 1;
						$ptmove_addresses_ID[$val['id']] = $val;
					}
					$temp_rs_add = get_respite_addresses_ID_v2($temppatientinfo['patientID']);
					foreach($temp_rs_add as $key=>$val){
						$val['sequence'] = $key + 1;
						$respite_addresses_ID[$val['id']] = $val;
					}
				}

				//getting subequipments of the parent
				$parents = array();
				foreach($order_summary as $val){
					$parents[] = $val['equipmentID'];
					if($equipmentID == $val['equipmentID'] && $equipmentID != 0)
					{
						$count_here++;
						if($count_here > 1 && $count_here < 3)
						{
							$repeating_equipment[$count_loop] = $val['equipmentID'];
							$count_loop++;
						}
					}
					else
					{
						$equipmentID = $val['equipmentID'];
						$count_here = 1;
					}
				}

				$tempsubequipments = $this->order_model->get_orders_by_workorder($id, $uniqueID, $hospiceID,-1,$parents);
				$subequipments = array();
				foreach($tempsubequipments as $val){
					$subequipments[$val['parentID']][$val['equipmentID']] = $val;
				}

				$count_order_summary = count($order_summary);
				foreach ($order_summary as $key => $value) {
						//checking number of equiptments
						$tempdata = array();
						$tempdata['info'] = $value;
						$info = $value;
						$sequence_count++;

						$tempdata['counter'] = $count_order_summary;
						//equiptments
						$tempdata['subequipments'] = $subequipments;
						$tempdata['repeating_equipment'] = $repeating_equipment;
						//item description count
						$tempdata['packaged_items_ids_list'] 		= 			$packaged_items_ids_list;
						$tempdata['packaged_item_sign']  			= 		    $packaged_item_sign ;
						$tempdata['packaged_items_list'] 			= 		    $packaged_items_list;
						$tempdata['commode_pail_count'] 			= 		    $commode_pail_count;
						$tempdata['item_description_count'] 		= 		    $item_description_count;
						$tempdata['oxygen_cylinder_rack_count'] 	= 		    $oxygen_cylinder_rack_count;
						$tempdata['bed_chair_alarm_count']  		= 		    $bed_chair_alarm_count ;
						$tempdata['scale_chair_count'] 				= 		    $scale_chair_count;
						$tempdata['patient_lift_sling_count'] 		= 		    $patient_lift_sling_count;
						$tempdata['patient_lift_sling_count_equipment'] = 		$patient_lift_sling_count_equipment;
						$tempdata['oxygen_cylinder_e_refill_count'] = 		    $oxygen_cylinder_e_refill_count;
						$tempdata['oxygen_cylinder_m6_refill_count'] = 		    $oxygen_cylinder_m6_refill_count;
						$tempdata['sequence_count'] 				= 		    $sequence_count;
						$tempdata['adding_weight_sign'] 			= 		    $adding_weight_sign;
						$tempdata['adding_weight_equipment'] 		= 		    $adding_weight_equipment;
						$tempdata['last_equipmentID'] 				= 		    $last_equipmentID;
						$tempdata['last_equipmentID_count'] 		= 		    $last_equipmentID_count;
						$tempdata['o2_concentrator_follow_up_sign'] = 		    $o2_concentrator_follow_up_sign;
						$tempdata['o2_concentrator_follow_up_equipmentID'] = 	$o2_concentrator_follow_up_equipmentID;
						$tempdata['o2_concentrator_follow_up_uniqueID'] = 		$o2_concentrator_follow_up_uniqueID;
						$tempdata['o2_concentrator_follow_up_uniqueID_old'] = 	$o2_concentrator_follow_up_uniqueID_old;

						//serial checking
						if($info['canceled_order'] == 0){
                  if($info['serial_num'] == "pickup_order_only") {
                    //Miscellaneous CAPPED and NONCAPPED
                    if($info['equipmentID'] == 309 || $info['equipmentID'] == 306)
                    {
                      $item_description_count++;
                    // Oxygen Cylinder, E Refill - DISPOSABLES
                    }else if($info['equipmentID'] == 11){
                      $oxygen_cylinder_e_refill_count++;
                    }else if($info['equipmentID'] == 170){
                      $oxygen_cylinder_m6_refill_count++;
                    // Oxygen Cylinder Rack - NONCAPPED
                    }else if($info['equipmentID'] == 32){
                      $oxygen_cylinder_rack_count++;
                    // Bed and Chair Alarm - NONCAPPED
                    }else if($info['equipmentID'] == 296){
                      $bed_chair_alarm_count++;
                    // Scale Chair - NONCAPPED
                    }else if($info['equipmentID'] == 181){
                      $scale_chair_count++;
                    // Large Mesh Sling - NONCAPPED
                    }else if($info['equipmentID'] == 196){
                      $patient_lift_sling_count_equipment++;
                    }else{
                      $sign_repeated = 0;
                      foreach ($repeating_equipment as $loop_repeating_equipment) {
                        if($loop_repeating_equipment == $info['equipmentID'])
                        {
                          $sign_repeated = 1;
                        }
                      }
                      if($sign_repeated == 1)
                      {
                        if($last_equipmentID == $info['equipmentID'] && $last_equipmentID != 0)
                        {
                          $last_equipmentID_count++;
                        }
                        else
                        {
                          $last_equipmentID_count = 1;
                        }
                      }
                    }
                  }
              } //end of serial checking

						//addresses
						$tempdata['address_type'] 			 	= $address_type;
						$tempdata['ptmove_addresses_ID'] 	= $ptmove_addresses_ID;
						$tempdata['respite_addresses_ID'] = $respite_addresses_ID;

						$tempdata['hide_style'] = "";
						$tempdata['disable_cancel'] = "";
						if($value['parentID']==0){
							$data['count_parent_summaries']++;
							if($info['pickup_sub'] == "expired" || $info['pickup_sub'] == "discharged" || $info['pickup_sub'] == "revoked")
							{
								$result_here = check_if_match($info['uniqueID'],$info['patientID']);
								if(!empty($result_here))
								{
									$disable_cancel = "disabled";
								}
							}
						}
						else{
							$tempdata['hide_style'] = "display:none;";
						}
						//special product
						if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343)
						 {
							 $o2_concentrator_follow_up_sign = 1;
							 $o2_concentrator_follow_up_equipmentID = $info['equipmentID'];
							 $o2_concentrator_follow_up_uniqueID = $info['uniqueID'];
						 }
						 else if($info['equipmentID'] == 484)
						 {
							 $o2_concentrator_follow_up_sign = 2;
							 $o2_concentrator_follow_up_equipmentID = $info['equipmentID'];
							 $o2_concentrator_follow_up_uniqueID = $info['uniqueID'];
						 }

						 if($info['equipmentID'] == 181 || $info['equipmentID'] == 182)
						 {
							 if($info['canceled_order'] == 0)
							 {
								 $adding_weight_sign = 1;
								 $adding_weight_equipment = $info['equipmentID'];
							 }
						 }

						 if($info['original_activity_typeid'] == 1 && $info['activity_typeid'] == 2)
						 {
							 $tempdata['activity_name'] = "Delivery";
							 $tempdata['activity_typeid'] = 1;
						 }
						 else if($info['original_activity_typeid'] == 5 && $info['activity_typeid'] == 2)
						 {
							 $tempdata['activity_name'] = "Respite";
							 $tempdata['activity_typeid'] = 5;
						 }
						 else if($info['original_activity_typeid'] == 4 && $info['activity_typeid'] == 2)
						 {
							 $tempdata['activity_name'] = "CUS Move";
							 $info['activity_typeid'] = 4;
						 }
						 else if($info['original_activity_typeid'] == 2 && $info['activity_typeid'] == 2)
						 {
							 $adding_weight_sign = 0;
							 $adding_weight_equipment = 0;
						 }
						if(!in_array($value['equipmentID'], $packaged_items_ids_list))
						{
							$tempdata['item_quantity'] = $this->load->view("pages/confirmed_modal-list-tr-qty",$tempdata,TRUE);
	 						$tempdata['item_description'] = '';//$this->load->view("pages/confirmed_modal-list-tr-itemdescription",$tempdata,TRUE);
	 						$tempdata['item_serial'] = $this->load->view("pages/confirmed_modal-list-tr-serial",$tempdata,TRUE);
	 						$summary_lists .= $this->load->view("pages/confirmed_modal-list-tr",$tempdata,TRUE);
						}
						else{
							$packaged_item_sign = 1;
			        if($info['equipmentID'] == 486 || $info['equipmentID'] == 163 || $info['equipmentID'] == 164)
			        {
			          $packaged_items_list[0][] = $info;
			        }
			        else if($info['equipmentID'] == 68 || $info['equipmentID'] == 159 || $info['equipmentID'] == 160 || $info['equipmentID'] == 161 || $info['equipmentID'] ==162)
			        {
			          $packaged_items_list[1][] = $info;
			        }
			        else if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343 || $info['equipmentID'] == 466)
			        {
			          $packaged_items_list[2][] = $info;
			        }
			        else if($info['equipmentID'] == 36 || $info['equipmentID'] == 466 || $info['equipmentID'] == 178)
			        {
			          $packaged_items_list[3][] = $info;
			        }
			        else if($info['equipmentID'] == 422 || $info['equipmentID'] == 259)
			        {
			          $packaged_items_list[4][] = $info;
			        }
			        else if($info['equipmentID'] == 415 || $info['equipmentID'] == 259)
			        {
			          $packaged_items_list[5][] = $info;
			        }
			        else if($info['equipmentID'] == 174 || $info['equipmentID'] == 490 || $info['equipmentID'] ==492)
			        {
			          $packaged_items_list[6][] = $info;
			        }
			        else if($info['equipmentID'] == 67 || $info['equipmentID'] == 157)
			        {
			       	$packaged_items_list[7][] = $info;
							}

							if($info['equipmentID'] == 7)
			        {
			          $commode_pail_count++;
			        }
						}
		        $last_equipmentID = $info['equipmentID'];
				}//END OF ORDER SUMMARY
				foreach($packaged_items_list as $new_item_list)
      	{
	        foreach ($new_item_list as $value)
	        {
						//checking number of equiptments
						$tempdata = array();
						$tempdata['info'] = $value;
						$info = $value;
						$sequence_count++;

						$tempdata['counter'] = $count_order_summary;
						//equiptments
						$tempdata['subequipments'] = $subequipments;
						$tempdata['repeating_equipment'] = $repeating_equipment;
						//item description count
						$tempdata['packaged_items_ids_list'] 		= 			$packaged_items_ids_list;
						$tempdata['packaged_item_sign']  			= 		    $packaged_item_sign ;
						$tempdata['packaged_items_list'] 			= 		    $packaged_items_list;
						$tempdata['commode_pail_count'] 			= 		    $commode_pail_count;
						$tempdata['item_description_count'] 		= 		    $item_description_count;
						$tempdata['oxygen_cylinder_rack_count'] 	= 		    $oxygen_cylinder_rack_count;
						$tempdata['bed_chair_alarm_count']  		= 		    $bed_chair_alarm_count ;
						$tempdata['scale_chair_count'] 				= 		    $scale_chair_count;
						$tempdata['patient_lift_sling_count'] 		= 		    $patient_lift_sling_count;
						$tempdata['patient_lift_sling_count_equipment'] = 		$patient_lift_sling_count_equipment;
						$tempdata['oxygen_cylinder_e_refill_count'] = 		    $oxygen_cylinder_e_refill_count;
						$tempdata['oxygen_cylinder_m6_refill_count'] = 		    $oxygen_cylinder_m6_refill_count;
						$tempdata['sequence_count'] 				= 		    $sequence_count;
						$tempdata['adding_weight_sign'] 			= 		    $adding_weight_sign;
						$tempdata['adding_weight_equipment'] 		= 		    $adding_weight_equipment;
						$tempdata['last_equipmentID'] 				= 		    $last_equipmentID;
						$tempdata['last_equipmentID_count'] 		= 		    $last_equipmentID_count;
						$tempdata['o2_concentrator_follow_up_sign'] = 		    $o2_concentrator_follow_up_sign;
						$tempdata['o2_concentrator_follow_up_equipmentID'] = 	$o2_concentrator_follow_up_equipmentID;
						$tempdata['o2_concentrator_follow_up_uniqueID'] = 		$o2_concentrator_follow_up_uniqueID;
						$tempdata['o2_concentrator_follow_up_uniqueID_old'] = 	$o2_concentrator_follow_up_uniqueID_old;

						//serial checking
						if($info['canceled_order'] == 0){
                  if($info['serial_num'] == "pickup_order_only") {
                    //Miscellaneous CAPPED and NONCAPPED
                    if($info['equipmentID'] == 309 || $info['equipmentID'] == 306)
                    {
                      $item_description_count++;
                    // Oxygen Cylinder, E Refill - DISPOSABLES
                    }else if($info['equipmentID'] == 11){
                      $oxygen_cylinder_e_refill_count++;
                    }else if($info['equipmentID'] == 170){
                      $oxygen_cylinder_m6_refill_count++;
                    // Oxygen Cylinder Rack - NONCAPPED
                    }else if($info['equipmentID'] == 32){
                      $oxygen_cylinder_rack_count++;
                    // Bed and Chair Alarm - NONCAPPED
                    }else if($info['equipmentID'] == 296){
                      $bed_chair_alarm_count++;
                    // Scale Chair - NONCAPPED
                    }else if($info['equipmentID'] == 181){
                      $scale_chair_count++;
                    // Large Mesh Sling - NONCAPPED
                    }else if($info['equipmentID'] == 196){
                      $patient_lift_sling_count_equipment++;
                    }else{
                      $sign_repeated = 0;
                      foreach ($repeating_equipment as $loop_repeating_equipment) {
                        if($loop_repeating_equipment == $info['equipmentID'])
                        {
                          $sign_repeated = 1;
                        }
                      }
                      if($sign_repeated == 1)
                      {
                        if($last_equipmentID == $info['equipmentID'] && $last_equipmentID != 0)
                        {
                          $last_equipmentID_count++;
                        }
                        else
                        {
                          $last_equipmentID_count = 1;
                        }
                      }
                    }
                  }
              } //end of serial checking

						//addresses
						$tempdata['address_type'] 			 	= $address_type;
						$tempdata['ptmove_addresses_ID'] 	= $ptmove_addresses_ID;
						$tempdata['respite_addresses_ID'] = $respite_addresses_ID;

						$tempdata['hide_style'] = "";
						$tempdata['disable_cancel'] = "";
						if($value['parentID']==0){
							$data['count_parent_summaries']++;
							if($info['pickup_sub'] == "expired" || $info['pickup_sub'] == "discharged" || $info['pickup_sub'] == "revoked")
							{
								$result_here = check_if_match($info['uniqueID'],$info['patientID']);
								if(!empty($result_here))
								{
									$disable_cancel = "disabled";
								}
							}
						}
						else{
							$tempdata['hide_style'] = "display:none;";
						}
						//special product
						if($info['equipmentID'] == 316 || $info['equipmentID'] == 325 || $info['equipmentID'] == 334 || $info['equipmentID'] == 343)
						 {
							 $o2_concentrator_follow_up_sign = 1;
							 $o2_concentrator_follow_up_equipmentID = $info['equipmentID'];
							 $o2_concentrator_follow_up_uniqueID = $info['uniqueID'];
						 }
						 else if($info['equipmentID'] == 484)
						 {
							 $o2_concentrator_follow_up_sign = 2;
							 $o2_concentrator_follow_up_equipmentID = $info['equipmentID'];
							 $o2_concentrator_follow_up_uniqueID = $info['uniqueID'];
						 }

						 if($info['equipmentID'] == 181 || $info['equipmentID'] == 182)
						 {
							 if($info['canceled_order'] == 0)
							 {
								 $adding_weight_sign = 1;
								 $adding_weight_equipment = $info['equipmentID'];
							 }
						 }

						 if($info['original_activity_typeid'] == 1 && $info['activity_typeid'] == 2)
						 {
							 $tempdata['activity_name'] = "Delivery";
							 $tempdata['activity_typeid'] = 1;
						 }
						 else if($info['original_activity_typeid'] == 5 && $info['activity_typeid'] == 2)
						 {
							 $tempdata['activity_name'] = "Respite";
							 $tempdata['activity_typeid'] = 5;
						 }
						 else if($info['original_activity_typeid'] == 4 && $info['activity_typeid'] == 2)
						 {
							 $tempdata['activity_name'] = "CUS Move";
							 $info['activity_typeid'] = 4;
						 }
						 else if($info['original_activity_typeid'] == 2 && $info['activity_typeid'] == 2)
						 {
							 $adding_weight_sign = 0;
							 $adding_weight_equipment = 0;
						 }
							$tempdata['item_quantity'] = $this->load->view("pages/confirmed_modal-list-tr-qty",$tempdata,TRUE);
	 						$tempdata['item_description'] = '';//$this->load->view("pages/confirmed_modal-list-tr-itemdescription",$tempdata,TRUE);
	 						$tempdata['item_serial'] = $this->load->view("pages/confirmed_modal-list-tr-serial",$tempdata,TRUE);
	 						$summary_lists .= $this->load->view("pages/confirmed_modal-list-tr",$tempdata,TRUE);
		        	$last_equipmentID = $info['equipmentID'];
					}
					//end of PACKAGE ITEM SUB
				}
				//end of PACKAGE ITEM LIST main

        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_record_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id);
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }


		$data['work_order_number'] = $uniqueID;

		$info = $patientinfo;
		if(empty($info)){
			return redirect(base_url("order/list_tobe_confirmed"));
		}
		$info = $info[0];
		$data['info'] = $info;
		//patient addresses
		$data['ptmove']           		= new_ptmove_address($info['patientID']);
		$data['ptmove_new_phone'] 		= get_new_patient_phone($info['patientID']);
		$data['ptmove_residence'] 		= get_new_patient_residence($info['patientID']);
		$data['ptmove_nextofkin']     = get_new_nextofkin($medical_id);
		$data['ptmove_relationship']  = get_new_relationship($medical_id);
		$data['ptmove_phonenum']      = get_new_phonenum($medical_id);
		$data['ptmove_residence'] 		= get_new_patient_residence_v3($info['patientID']);
		if(!empty($data['ptmove_residence'])){
			$data['ptmove_residence_new'] 			= get_new_patient_residence_v2($info['patientID'], $data['ptmove_residence']['order_uniqueID']);
			$data['check_if_ptmove_confirmed'] 	= check_if_ptmove_confirmed($data['ptmove_residence']['order_uniqueID']);
		}
		$data['cequipment_phone_number'] = get_equipment_phone_number($info['patientID']);
		if($act_id==4){
			$data['ptmove_information'] = get_ptmove_address_inputted($info['patientID']);
		}

		$data['subequipments'] = $subequipments;
		$data['repeating_equipment'] = $repeating_equipment;
		//item description count
		$data['packaged_items_ids_list'] 		= 			$packaged_items_ids_list;
		$data['packaged_item_sign']  			= 		    $packaged_item_sign ;
		$data['packaged_items_list'] 			= 		    $packaged_items_list;
		$data['commode_pail_count'] 			= 		    $commode_pail_count;
		$data['item_description_count'] 		= 		    $item_description_count;
		$data['oxygen_cylinder_rack_count'] 	= 		    $oxygen_cylinder_rack_count;
		$data['bed_chair_alarm_count']  		= 		    $bed_chair_alarm_count ;
		$data['scale_chair_count'] 				= 		    $scale_chair_count;
		$data['patient_lift_sling_count'] 		= 		    $patient_lift_sling_count;
		$data['patient_lift_sling_count_equipment'] = 		$patient_lift_sling_count_equipment;
		$data['oxygen_cylinder_e_refill_count'] = 		    $oxygen_cylinder_e_refill_count;
		$data['oxygen_cylinder_m6_refill_count'] = 		    $oxygen_cylinder_m6_refill_count;
		$data['sequence_count'] 				= 		    $sequence_count;
		$data['adding_weight_sign'] 			= 		    $adding_weight_sign;
		$data['adding_weight_equipment'] 		= 		    $adding_weight_equipment;
		$data['last_equipmentID'] 				= 		    $last_equipmentID;
		$data['last_equipmentID_count'] 		= 		    $last_equipmentID_count;
		$data['o2_concentrator_follow_up_sign'] = 		    $o2_concentrator_follow_up_sign;
		$data['o2_concentrator_follow_up_equipmentID'] = 	$o2_concentrator_follow_up_equipmentID;
		$data['o2_concentrator_follow_up_uniqueID'] = 		$o2_concentrator_follow_up_uniqueID;
		$data['o2_concentrator_follow_up_uniqueID_old'] = 	$o2_concentrator_follow_up_uniqueID_old;
		$data['content_data'] = $summary_lists;

		$data['summaries'] = $order_summary;
		$data['patient_information_content'] = $this->load->view("pages/confirmed_modal_patient_details",$data,TRUE);


		$this->templating_library->set('title','Edit Customer Info');
		$this->templating_library->set_view('pages/confirmed_modal','pages/confirmed_modal', $data);
		//$this->templating_library->set_view('common/foot','common/foot'); //put this back if it causes some errors.
		$this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
	}

	public function confirmed_modal_itemdescription($medical_record_id="",$uniqueID="",$hospiceID=""){
			if($_GET){
				if(isset($_GET['info']) && $medical_record_id!="" && $uniqueID!="" && $hospiceID!=""){
					$info = array();
					try{
							$info = json_decode($_GET['info'],true);
					}catch(Exception $err){}

					if(!empty($info)){
						$parents[] = $info['equipmentID'];
						$tempsubequipments = $this->order_model->get_orders_by_workorder($medical_record_id, $uniqueID, $hospiceID,-1,$parents);
						$subequipments = array();
						foreach($tempsubequipments as $val){
							$subequipments[$val['parentID']][$val['equipmentID']] = $val;
						}
						$tempdata['info'] = $info;
						$tempdata['subequipments'] = $subequipments;
						$this->load->view("pages/confirmed_modal-list-tr-itemdescription",$tempdata);
					}
				}
			}
	}

	public function confirmed_order_details_exchange($medical_record_id, $uniqueID, $act_id="", $hospiceID="")
	{
		$user_type = $this->session->userdata('account_type');

		$returns = $this->order_model->list_orders($medical_record_id);
		$informations = $this->order_model->get_order_info($medical_record_id, $hospiceID);
		$data['comments'] = $this->order_model->get_all_comments($medical_record_id);

		$datas = array();
		$data['summarys'] = $returns;
		$data['informations'] = $informations;

		$data['act_id'] = $act_id;

		$data['note_counts'] = $this->order_model->count_patient_comments($medical_record_id);

		$data['work_order_number'] = $uniqueID;

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $medical_record_id;

        $response = $this->order_model->get_orders($id);
        $order_summary = $this->order_model->get_orders_by_workorder_exchange($id, $uniqueID, $hospiceID);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        $data['summaries'] = $order_summary;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_record_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id);
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }
		$data['infos'] = $this->order_model->get_patient_profile($medical_record_id, $hospiceID);
		$data['work_order_number'] = $uniqueID;
		$this->templating_library->set('title','Edit Customer Info');
		$this->templating_library->set_view('pages/confirmed_modal_exchange','pages/confirmed_modal_exchange', $data);
		$this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
	}
	//$this->templating_library->set_view('common/foot','common/foot');


	public function update_patient_profile($medical_record_id,$passed_addressID)
	{
		$data_post = $this->input->post();
		$act_id = $data_post['act_typeid'];
		$patient_id = $data_post['hdn_patient_id'];
		$edited_by = $this->session->userdata('userID');

		$this->form_validation->set_rules('organization_id', 'Hospice Provider', 'required');

		if($this->form_validation->run())
		{
			$order_data = array(
				//'patientID'			=> $data_post['update_patient_id'],
				'medical_record_id' => $data_post['medical_record_id'],
				'organization_id' => $data_post['organization_id'],
				'deliver_to_type' => $data_post['deliver_to_type'],
			);
			$saved_data = $this->order_model->update_order_profile_v2($patient_id, $order_data);

			if($saved_data)
			{
				$relationship_gender_value = $data_post['relationship_gender'];
				// $relationship_gender_value = "";
				// if(!empty($data_post['relationship_gender']))
				// {
				// 	$relationship_gender_value = $data_post['relationship_gender'];
				// }
				$patient_data = array(
					'p_fname' => $data_post['p_fname'],
					'p_lname' => $data_post['p_lname'],
					'p_street' => $data_post['pt_street'],
					'p_placenum' => $data_post['pt_placenum'],
					'p_city' => $data_post['pt_city'],
					'p_state' => $data_post['pt_state'],
					'p_postalcode' => $data_post['pt_postalcode'],
					'p_height' => $data_post['height'],
					'p_weight' => $data_post['weight'],
					'p_phonenum' => $data_post['phonenum'],
					'p_altphonenum' => $data_post['altphonenum'],
					'relationship_gender' => $relationship_gender_value,
					'p_nextofkin' => $data_post['nextofkin'],
					'p_relationship' => $data_post['relationship'],
					'p_nextofkinnum' => $data_post['nextofkinnum'],
					'medical_record_id' => $data_post['medical_record_id']
				);
				$original_patient_data = $this->order_model->get_original_patient_info($patient_id);
				// print_me($patient_data);
				$logs = array();
				foreach($original_patient_data as $key=>$value)
				{
					if(isset($patient_data[$key]))
					{
						if($patient_data[$key] != $value)
						{
							$logs[] = array(
								"patientID" => $patient_id,
								"old_value" => $value,
								"new_value" => $patient_data[$key],
								"edited_column_name" => $key,
								"edited_by"	=> $edited_by
							);
						}
					}
				}

				if(!empty($logs))
				{
					$this->order_model->insert_to_patient_logs($logs);
				}
				$saved_patient_info = $this->order_model->update_patient_profile_v2($patient_id, $patient_data);

				if($saved_patient_info)
				{
					$patient_addresses = $this->order_model->get_patient_addresses($patient_id);

					if(count($patient_addresses) == 1)
					{
						$edited_patient_address = array(
							'street' => $data_post['pt_street'],
							'placenum' => $data_post['pt_placenum'],
							'city' => $data_post['pt_city'],
							'state' => $data_post['pt_state'],
							'postal_code' => $data_post['pt_postalcode']
						);
						// print_me($edited_patient_address);
						$this->order_model->update_dme_patient_address($patient_id, $edited_patient_address);
					}
					else
					{
						// FOR THE dme_patient_address TABLE
						$edited_patient_address = array(
							'street' => $data_post['pt_street'],
							'placenum' => $data_post['pt_placenum'],
							'city' => $data_post['pt_city'],
							'state' => $data_post['pt_state'],
							'postal_code' => $data_post['pt_postalcode']
						);

						$latest_ptmove_address_id = 0;
						foreach ($patient_addresses as $address_loop) {
							if($address_loop['type'] == 1)
							{
								$latest_ptmove_address_id = $address_loop['id'];
							}
						}
						// print_me($edited_patient_address);
						$this->order_model->update_dme_patient_address_v2($latest_ptmove_address_id, $edited_patient_address);


						// FOR THE dme_sub_ptmove TABLE
						$edited_patient_move_address = array(
							'ptmove_street' => $data_post['pt_street'],
							'ptmove_placenum' => $data_post['pt_placenum'],
							'ptmove_city' => $data_post['pt_city'],
							'ptmove_state' => $data_post['pt_state'],
							'ptmove_postal' => $data_post['pt_postalcode'],
							'ptmove_patient_residence' => $data_post['deliver_to_type'],
							'ptmove_nextofkin' => $data_post['nextofkin'],
							'ptmove_nextofkinrelation' => $data_post['relationship'],
							'ptmove_nextofkinphone' => $data_post['nextofkinnum'],
							'ptmove_patient_phone' => $data_post['phonenum'],
							'ptmove_alt_patient_phone' => $data_post['altphonenum'],
							'medical_record_id' => $data_post['medical_record_id']
						);

						$ptmove_sub_addresses = $this->order_model->get_ptmove_sub_addresses($patient_id);
						$count_sub_ptmove = (count($ptmove_sub_addresses)-1);
						$latest_ptmove_sub_address_id = $ptmove_sub_addresses[$count_sub_ptmove]['ptmoveID'];

						// print_me($edited_patient_move_address);
						$this->order_model->update_dme_sub_ptmove($latest_ptmove_sub_address_id, $edited_patient_move_address);


						// FOR THE dme_new_ptmove_address TABLE
						$edited_new_patient_move_address = array(
							'ptmove_medical_record_id' => $data_post['medical_record_id'],
							'ptmove_street' => $data_post['pt_street'],
							'ptmove_placenum' => $data_post['pt_placenum'],
							'ptmove_city' => $data_post['pt_city'],
							'ptmove_state' => $data_post['pt_state'],
							'ptmove_postal' => $data_post['pt_postalcode'],
							'ptmove_patient_phone' => $data_post['phonenum'],
							'ptmove_patient_residence' => $data_post['deliver_to_type'],
							'ptmove_nextofkin' => $data_post['nextofkin'],
							'ptmove_relationship' => $data_post['relationship'],
							'ptmove_phonenum' => $data_post['nextofkinnum']
						);
						// print_me($edited_new_patient_move_address);
						$ptmove_new_addresses = $this->order_model->get_ptmove_new_addresses($patient_id);
						$count_new_ptmove = (count($ptmove_new_addresses)-1);
						$latest_ptmove_new_address_id = $ptmove_new_addresses[$count_new_ptmove]['ptmoveID'];

						$this->order_model->update_dme_new_ptmove($latest_ptmove_new_address_id, $edited_new_patient_move_address);
					}
					$this->response_code = 0;
					$this->response_message = "Successfully Updated Information.";
				}
				else
				{
					$this->response_message = "Failed to Update Information.";
				}
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}

		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;
	}

	public function update_patient_profile_confirmation_v2($medical_record_id)
	{
		$data_post = $this->input->post();
		$act_id = $data_post['act_typeid'];
		$patient_id = $data_post['hdn_patient_id'];
		$edited_by = $this->session->userdata('userID');

		$this->form_validation->set_rules('organization_id', 'Hospice Provider', 'required');

		if($this->form_validation->run())
		{
			$order_data = array(
				//'patientID'			=> $data_post['update_patient_id'],
				'medical_record_id' => $data_post['medical_record_id'],
				'organization_id' => $data_post['organization_id'],
				'deliver_to_type' => $data_post['deliver_to_type'],
			);
			$saved_data = $this->order_model->update_order_profile_v2($patient_id, $order_data);

			if($saved_data)
			{
				$patient_data = array(
					'p_fname' => $data_post['p_fname'],
					'p_lname' => $data_post['p_lname'],
					'p_street' => $data_post['p_street'],
					'p_placenum' => $data_post['p_placenum'],
					'p_city' => $data_post['p_city'],
					'p_state' => $data_post['p_state'],
					'p_postalcode' => $data_post['p_postalcode'],
					'p_height' => $data_post['height'],
					'p_weight' => $data_post['weight'],
					'p_phonenum' => $data_post['phonenum'],
					'p_altphonenum' => $data_post['altphonenum'],
					'p_nextofkin' => $data_post['nextofkin'],
					'p_relationship' => $data_post['relationship'],
					'p_nextofkinnum' => $data_post['nextofkinnum'],
					'relationship_gender' => $data_post['relationship_gender'],
					'medical_record_id' => $data_post['medical_record_id']
				);

				$original_patient_data = $this->order_model->get_original_patient_info($patient_id);

				$logs = array();
				foreach($original_patient_data as $key=>$value)
				{
					if(isset($patient_data[$key]))
					{
						if($patient_data[$key] != $value)
						{
							$logs[] = array(
								"patientID" => $patient_id,
								"old_value" => $value,
								"new_value" => $patient_data[$key],
								"edited_column_name" => $key,
								"edited_by"	=> $edited_by
							);
						}
					}
				}

				if(!empty($logs))
				{
					$this->order_model->insert_to_patient_logs($logs);
				}

				$saved_patient_info = $this->order_model->update_patient_profile_v2($patient_id, $patient_data);

				if($saved_patient_info)
				{
					// FOR THE dme_patient_address TABLE
					$patient_addresses = $this->order_model->get_patient_addresses_v2($patient_id);

					$edited_patient_address = array(
						'street' => $data_post['p_street'],
						'placenum' => $data_post['p_placenum'],
						'city' => $data_post['p_city'],
						'state' => $data_post['p_state'],
						'postal_code' => $data_post['p_postalcode']
					);
					if(count($patient_addresses) == 1)
					{
						$this->order_model->update_dme_patient_address($patient_id, $edited_patient_address);
					}
					else
					{
						$latest_ptmove_address_id = 0;
						foreach ($patient_addresses as $address_loop) {
							if($address_loop['type'] == 1)
							{
								$latest_ptmove_address_id = $address_loop['id'];
							}
						}
						$this->order_model->update_dme_patient_address_v2($latest_ptmove_address_id, $edited_patient_address);
					}
					$this->response_code = 0;
					$this->response_message = "Successfully Updated Information.";
				}
				else
				{
					$this->response_message = "Failed to Update Information.";
				}
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}

		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;
	}

	public function update_patient_profile_confirmation($medical_record_id,$passed_addressID)
	{
		$data_post = $this->input->post();
		$act_id = $data_post['act_typeid'];
		$patient_id = $data_post['hdn_patient_id'];
		$edited_by = $this->session->userdata('userID');

		$this->form_validation->set_rules('organization_id', 'Hospice Provider', 'required');

		if($this->form_validation->run())
		{
			$order_data = array(
				//'patientID'			=> $data_post['update_patient_id'],
				'medical_record_id' => $data_post['medical_record_id'],
				'organization_id' => $data_post['organization_id'],
				'deliver_to_type' => $data_post['deliver_to_type'],
			);
			$saved_data = $this->order_model->update_order_profile_v2($patient_id, $order_data);

			if($saved_data)
			{
				$patient_data = array(
					'p_fname' => $data_post['p_fname'],
					'p_lname' => $data_post['p_lname'],
					'p_street' => $data_post['p_street'],
					'p_placenum' => $data_post['p_placenum'],
					'p_city' => $data_post['p_city'],
					'p_state' => $data_post['p_state'],
					'p_postalcode' => $data_post['p_postalcode'],
					'p_height' => $data_post['height'],
					'p_weight' => $data_post['weight'],
					'p_phonenum' => $data_post['phonenum'],
					'p_altphonenum' => $data_post['altphonenum'],
					'p_nextofkin' => $data_post['nextofkin'],
					'p_relationship' => $data_post['relationship'],
					'p_nextofkinnum' => $data_post['nextofkinnum'],
					'relationship_gender' => $data_post['relationship_gender'],
					'medical_record_id' => $data_post['medical_record_id']
				);

				$original_patient_data = $this->order_model->get_original_patient_info($patient_id);

				$logs = array();
				foreach($original_patient_data as $key=>$value)
				{
					if(isset($patient_data[$key]))
					{
						if($patient_data[$key] != $value)
						{
							$logs[] = array(
								"patientID" => $patient_id,
								"old_value" => $value,
								"new_value" => $patient_data[$key],
								"edited_column_name" => $key,
								"edited_by"	=> $edited_by
							);
						}
					}
				}

				if(!empty($logs))
				{
					$this->order_model->insert_to_patient_logs($logs);
				}

				$saved_patient_info = $this->order_model->update_patient_profile_v2($patient_id, $patient_data);

				if($saved_patient_info)
				{
					$patient_addresses = $this->order_model->get_patient_addresses($patient_id);

					if(count($patient_addresses) == 1)
					{
						$edited_patient_address = array(
							'street' => $data_post['p_street'],
							'placenum' => $data_post['p_placenum'],
							'city' => $data_post['p_city'],
							'state' => $data_post['p_state'],
							'postal_code' => $data_post['p_postalcode']
						);
						$this->order_model->update_dme_patient_address($patient_id, $edited_patient_address);
					}
					else
					{
						// FOR THE dme_patient_address TABLE
						$edited_patient_address = array(
							'street' => $data_post['p_street'],
							'placenum' => $data_post['p_placenum'],
							'city' => $data_post['p_city'],
							'state' => $data_post['p_state'],
							'postal_code' => $data_post['p_postalcode']
						);

						$latest_ptmove_address_id = 0;
						foreach ($patient_addresses as $address_loop) {
							if($address_loop['type'] == 1)
							{
								$latest_ptmove_address_id = $address_loop['id'];
							}
						}
						$this->order_model->update_dme_patient_address_v2($passed_addressID, $edited_patient_address);
					}
					$this->response_code = 0;
					$this->response_message = "Successfully Updated Information.";
				}
				else
				{
					$this->response_message = "Failed to Update Information.";
				}
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}

		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;
	}

	public function update_patient_profile_confirmation_respite($medical_record_id,$passed_addressID)
	{
		$data_post = $this->input->post();
		$act_id = $data_post['act_typeid'];
		$patient_id = $data_post['hdn_patient_id'];
		$edited_by = $this->session->userdata('userID');

		$this->form_validation->set_rules('organization_id', 'Hospice Provider', 'required');

		if($this->form_validation->run())
		{
			$order_data = array(
				//'patientID'			=> $data_post['update_patient_id'],
				'medical_record_id' => $data_post['medical_record_id'],
				'organization_id' => $data_post['organization_id'],
				'deliver_to_type' => $data_post['deliver_to_type'],
			);
			$saved_data = $this->order_model->update_order_profile_v2($patient_id, $order_data);

			if($saved_data)
			{
				$patient_data = array(
					'p_fname' => $data_post['p_fname'],
					'p_lname' => $data_post['p_lname'],
					'p_height' => $data_post['height'],
					'p_weight' => $data_post['weight'],
					'p_phonenum' => $data_post['phonenum'],
					'p_altphonenum' => $data_post['altphonenum'],
					'p_nextofkin' => $data_post['nextofkin'],
					'p_relationship' => $data_post['relationship'],
					'p_nextofkinnum' => $data_post['nextofkinnum'],
					'relationship_gender' => $data_post['relationship_gender'],
					'medical_record_id' => $data_post['medical_record_id']
				);

				$original_patient_data = $this->order_model->get_original_patient_info($patient_id);

				$logs = array();
				foreach($original_patient_data as $key=>$value)
				{
					if(isset($patient_data[$key]))
					{
						if($patient_data[$key] != $value)
						{
							$logs[] = array(
								"patientID" => $patient_id,
								"old_value" => $value,
								"new_value" => $patient_data[$key],
								"edited_column_name" => $key,
								"edited_by"	=> $edited_by
							);
						}
					}
				}

				if(!empty($logs))
				{
					$this->order_model->insert_to_patient_logs($logs);
				}

				$saved_patient_info = $this->order_model->update_patient_profile_v2($patient_id, $patient_data);

				if($saved_patient_info)
				{
					$patient_addresses = $this->order_model->get_patient_addresses($patient_id);

					if(count($patient_addresses) == 1)
					{
						$edited_patient_address = array(
							'street' => $data_post['p_street'],
							'placenum' => $data_post['p_placenum'],
							'city' => $data_post['p_city'],
							'state' => $data_post['p_state'],
							'postal_code' => $data_post['p_postalcode']
						);
						$this->order_model->update_dme_patient_address($patient_id, $edited_patient_address);
					}
					else
					{
						// FOR THE dme_patient_address TABLE
						$edited_patient_address = array(
							'street' => $data_post['p_street'],
							'placenum' => $data_post['p_placenum'],
							'city' => $data_post['p_city'],
							'state' => $data_post['p_state'],
							'postal_code' => $data_post['p_postalcode']
						);

						$latest_ptmove_address_id = 0;
						foreach ($patient_addresses as $address_loop) {
							if($address_loop['type'] == 1)
							{
								$latest_ptmove_address_id = $address_loop['id'];
							}
						}
						$this->order_model->update_dme_patient_address_v2($passed_addressID, $edited_patient_address);
					}
					$this->response_code = 0;
					$this->response_message = "Successfully Updated Information.";
				}
				else
				{
					$this->response_message = "Failed to Update Information.";
				}
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}

		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;
	}

	public function update_patient_profile_toedit($medical_record_id)
	{
		$data_post = $this->input->post();


		$act_id = $data_post['act_typeid'];
		$edited_by = $this->session->userdata('userID');

		//$driver_name_post = $data_post['driver_name'];

		$this->form_validation->set_rules('organization_id', 'Hospice Provider', 'required');


		if($this->form_validation->run())
		{
			$order_data = array(
				'medical_record_id' => $data_post['medical_record_id'],
				'organization_id' => $data_post['organization_id'],
				'deliver_to_type' => $data_post['deliver_to_type'],
				//'driver_name' => $driver_name_post,
			);
			$saved_data = $this->order_model->update_order_profile($medical_record_id, $order_data);

			if($saved_data)
			{
				$patient_data = array(
					'p_fname' => $data_post['p_fname'],
					'p_lname' => $data_post['p_lname'],
					'p_street' => $data_post['p_street'],
					'p_placenum' => $data_post['p_placenum'],
					'p_city' => $data_post['p_city'],
					'p_state' => $data_post['p_state'],
					'p_postalcode' => $data_post['p_postalcode'],
					'p_height' => $data_post['height'],
					'p_weight' => $data_post['weight'],
					'p_phonenum' => $data_post['phonenum'],
					'p_altphonenum' => $data_post['altphonenum'],
					'p_nextofkin' => $data_post['nextofkin'],
					'p_relationship' => $data_post['relationship'],
					'p_nextofkinnum' => $data_post['nextofkinnum'],
					'medical_record_id' => $data_post['medical_record_id'],
					'edited_by'			=> $edited_by,
				);

				$saved_patient_info = $this->order_model->update_patient_profile_toedit($medical_record_id, $patient_data);

				if($saved_patient_info)
				{
					if($act_id == 4)
					{
						$ptmove_address = array(
							"ptmove_medical_record_id" => $data_post['medical_record_id'],
							"ptmove_street"     => $data_post['pt_street'],
							"ptmove_placenum"	=> $data_post['pt_placenum'],
							"ptmove_city"		=> $data_post['pt_city'],
							"ptmove_state"	    => $data_post['pt_state'],
							"ptmove_postal" 	=> $data_post['pt_postalcode'],
						);

						$this->order_model->insert_ptmove_table($ptmove_address);
					}

					$this->response_code = 0;
					$this->response_message = "Successfully Updated Information.";
				}
				else
				{
					$this->response_message = "Failed to Update Information.";
				}
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}

		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;

	}


	function show_patient_notes($medical_record_id, $p_fname, $p_lname, $hospice_name, $patientID)
	{
		$data['id']  = $medical_record_id;
		$data['notes'] = $this->order_model->retrieve_patient_notes($medical_record_id, $patientID);
		$data['notes_p2'] = $this->order_model->retrieve_patient_order_comments($patientID);
		$data['p_fname'] = $p_fname;
		$data['p_lname'] = $p_lname;
		$data['hospice_name'] = urldecode($hospice_name);
		$data['patientID']	 = $patientID;

		$this->templating_library->set('title','Customer Notes');
		$this->templating_library->set_view('pages/patient_notes','pages/patient_notes', $data);
		// $this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
	}

	function show_patient_notes_new($medical_record_id, $p_fname, $p_lname, $hospice_name, $patientID)
	{
		if($_POST)
		{
			$medical_record_id = $_POST['medical_record_id'];
			$hospice_name = $_POST['hospice_name'];
			$patientID = $_POST['patient_id'];

			$data['id']  = $medical_record_id;
			$data['notes'] = $this->order_model->retrieve_patient_notes($medical_record_id, $patientID);
			$data['notes_p2'] = $this->order_model->retrieve_patient_order_comments($patientID);
			$data['p_fname'] = $_POST['p_fname'];
			$data['p_lname'] = $_POST['p_lname'];
			$data['hospice_name'] = urldecode($hospice_name);
			$data['patientID']	 = $patientID;

			$this->templating_library->set('title','Customer Notes');
			$this->templating_library->set_view('pages/patient_notes','pages/patient_notes', $data);
			$this->templating_library->set_view('common/custom-scripts','common/custom-scripts');
		}
	}

	function save_patient_notes($medical_record_id)
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('patient_notes','Customer Notes','required|xss_clean');

		if($this->form_validation->run())
		{
			$array = array(
				'medical_record_id' => $data_post['medical_record_id'],
				'patientID'			=> $data_post['patientID'],
				'notes' => $data_post['patient_notes'],
				'noted_by' => $data_post['noted_by']
			);

			$inserted = $this->order_model->insert_patient_note($array);

			if($inserted)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Inserted Customer Note.";
			}
			else
			{
				$this->response_message = "Failed to Insert Customer Note.";
			}
		}
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}

		echo json_encode(array(
					"error" 	=> $this->response_code,
					"message"	=> $this->response_message
				));
		exit;
	}

	public function update_pickup_order($medical_record_id)
	{
		$data_post = $this->input->post();

		$this->form_validation->set_rules('who_ordered_fname','Hospice Staff First Name','required');
		$this->form_validation->set_rules('who_ordered_lname','Hospice Staff Last Name','required');
		$this->form_validation->set_rules('staff_member_fname','Staff First Name','required');
		$this->form_validation->set_rules('staff_member_lname','Staff Last Name','required');
		$this->form_validation->set_rules('who_ordered_email','Email Address','valid_email');
		$this->form_validation->set_rules('who_ordered_cpnum','Cellphone No.','integer');
		$this->form_validation->set_rules('equipments[]','Items','required');

		if($this->form_validation->run())
		{
					$pickup_date 		= date($data_post['pickup_date']);
					$ordered_by  		= $id;
					$organization_id 	= $data_post['organization_id'];
					$activity_type		= $data_post['activity_type'];

					//order items
					$orders = array();
					foreach($data_post['equipments'] as $key=>$value)
					{
							$orders[] = array(

											"patientID"				=> $patientID,
											"equipmentID"			=> $value,
											"equipment_value"		=> 1,
											"pickup_date"			=> $pickup_date,
											"activity_typeid"		=> $activity_type,
											"organization_id"		=> $organization_id,
											"ordered_by"			=> $id,
											"who_ordered_fname"		=> $data_post['who_ordered_fname'],
											"who_ordered_lname"		=> $data_post['who_ordered_lname'],
											"staff_member_fname"	=> $data_post['staff_member_fname'],
											"staff_member_lname"	=> $data_post['staff_member_lname'],
											"who_ordered_email"		=> $data_post['who_ordered_email'],
											"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
											"comment"				=> $data_post['new_order_notes'],
											"uniqueID"				=> $unique_id,
											"deliver_to_type"	    => $data_post['delivery_to_type'],
											'medical_record_id' 	=> $data_post['medical_record_id'],
											"order_status"	        => $data_post['order_status']
										);

					}
						/*
						* @sub equipments
						*
						*/
						foreach($data_post['subequipment'] as $key=>$value)
						{
								if(in_array($key,$data_post['equipments']))
								{
									foreach($value as $sub_key=>$sub_value)
									{
										if($sub_key=="radio")
										{
											foreach($sub_value as $radio_value)
											{
													$orders[] = array(
																	"patientID"				=> $patientID,
																	"equipmentID"			=> $value,
																	"equipment_value"		=> 1,
																	"pickup_date"			=> $pickup_date,
																	"activity_typeid"		=> $activity_type,
																	"organization_id"		=> $organization_id,
																	"ordered_by"			=> $id,
																	"who_ordered_fname"		=> $data_post['who_ordered_fname'],
																	"who_ordered_lname"		=> $data_post['who_ordered_lname'],
																	"staff_member_fname"	=> $data_post['staff_member_fname'],
																	"staff_member_lname"	=> $data_post['staff_member_lname'],
																	"who_ordered_email"		=> $data_post['who_ordered_email'],
																	"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
																	"comment"				=> $data_post['new_order_notes'],
																	"uniqueID"				=> $unique_id,
																	"deliver_to_type"	    => $data_post['delivery_to_type'],
																	'medical_record_id' 	=> $data_post['medical_record_id'],
																	"order_status"	        => $data_post['order_status']
																);
											}
										}
										else
										{
											$orders[] = array(
												"patientID"				=> $patientID,
												"equipmentID"			=> $value,
												"equipment_value"		=> 1,
												"pickup_date"			=> $pickup_date,
												"activity_typeid"		=> $activity_type,
												"organization_id"		=> $organization_id,
												"ordered_by"			=> $id,
												"who_ordered_fname"		=> $data_post['who_ordered_fname'],
												"who_ordered_lname"		=> $data_post['who_ordered_lname'],
												"staff_member_fname"	=> $data_post['staff_member_fname'],
												"staff_member_lname"	=> $data_post['staff_member_lname'],
												"who_ordered_email"		=> $data_post['who_ordered_email'],
												"who_ordered_cpnum"		=> $data_post['who_ordered_cpnum'],
												"comment"				=> $data_post['new_order_notes'],
												"uniqueID"				=> $unique_id,
												"deliver_to_type"	    => $data_post['delivery_to_type'],
												'medical_record_id' 	=> $data_post['medical_record_id'],
												"order_status"	        => $data_post['order_status']
											);
										}
									}
								}
						}

						$saveorder = $this->order_model->saveorder($orders);
		}
	}



	public function count_patient_notes($medical_record_id)
	{
		$result = $this->order_model->count_patient_comments($medical_record_id);
		echo json_encode($result);
	}


	public function change_order_status($medical_record_id,$status='', $uniqueID)
	{

		$array = array(
			"order_status" => $status
		);


		$this->order_model->change_order_status($medical_record_id, $uniqueID, $array);

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Order Status Successfully Updated.";
		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function move_enroute_orders($medical_record_ids,$status='', $unique_ids)
	{
		$array = array(
			"order_status" => $status
		);

		$this->order_model->move_enroute_orders($medical_record_id, $unique_ids, $array);

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Successfully moved to Confirm Work Orders.";
		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}
	public function move_enroute_ordersv2()
	{
		if($this->session->userdata('account_type') != 'dme_admin')
		{
			return false;
		}
		if($_POST)
		{
			$has_error 	= false;
			$data_ 		= 0;
			$data   = get_status("active");
			if(!empty($data))
			{
				foreach ($data as $key => $value)
				{
					$medical_record_id  = $value['medical_record_id'];
					$unique_ids 		= $value['order_uniqueID'];
					$status 			= "tobe_confirmed";
					$check = $this->order_model->move_enroute_orders($medical_record_id, $unique_ids, array("order_status" => $status));
					if(!$check)
					{
						$data_++;
					}
				}

				if($data_>0)
				{
					$this->common->code 	= 1;
					$this->common->message	= "There are {$data_} items have not successfully send. Please try again!";
				}
				else
				{
					$this->common->code = 0;
					$this->common->message = "Successfully sent to Confirm Work Orders.";
				}
			}
			else
			{
				$this->common->code = 1;
				$this->common->message = "No ENROUTES to send";
			}
		}
		$this->common->response(false);

	}

	// echo "<pre>";
	// print_r($results);
	// echo "</pre>";
	public function cancel_order($medical_record_id, $uniqueID , $equipmentID="")
	{
		$results = $this->order_model->list_orders_to_cancel($medical_record_id, $uniqueID);
		$old_activity_type = 0;
		$revert_sign = 0;
		$revert_pickedup_item_to_original = array();
		$order_original_activity_typeid = 0;
		foreach($results as $result)
		{
			$order_original_activity_typeid = $result['original_activity_typeid'];
			if($result['original_activity_typeid'] == 3)
			{
				$original_results = $this->order_model->original_list_orders_to_cancel_exchange($medical_record_id, $uniqueID);
				foreach($original_results as $result_inside)
				{
					if($result_inside['original_activity_typeid'] == 3)
					{
						$revert_pickedup_item_to_original = array(
							'activity_typeid'  			=> $result_inside['original_activity_typeid'],
							'activity_reference'		=> 3,
							'uniqueID_reference'		=> 0
						);
					}
					else if($result_inside['original_activity_typeid'] == 5)
					{
						$revert_pickedup_item_to_original = array(
							'activity_typeid'  			=> $result_inside['original_activity_typeid'],
							'activity_reference'		=> 1,
							'uniqueID_reference'		=> 0,
							'pickedup_respite_order'	=> 0
						);
					}
					else
					{
						$revert_pickedup_item_to_original = array(
							'activity_typeid'  			=> $result_inside['original_activity_typeid'],
							'activity_reference'		=> 1,
							'uniqueID_reference'		=> 0
						);
					}
					$reverted_item_activty_type = $this->order_model->revert_exchange_item_to_original($result_inside['equipmentID'],$uniqueID,$medical_record_id,$revert_pickedup_item_to_original);
				}
				$this->order_model->delete_order_dme_order($medical_record_id,$uniqueID);
				$this->order_model->delete_order_dme_order_status($medical_record_id,$uniqueID);

				$old_activity_type = 3;
				$revert_sign = 1;
				break;
			}
			else if(($result['original_activity_typeid'] == 4 && $result['activity_typeid'] == 4) || ($result['original_activity_typeid'] == 5 && $result['activity_typeid'] == 5))
			{
				$old_activity_type = 1;
				// $delete_address = $this->order_model->delete_address($result['addressID'],$result['patientID']);
				break;
			}
			else if($result['original_activity_typeid'] == 4 || $result['original_activity_typeid'] == 5)
			{
				if($result['activity_typeid'] == 2)
				{
					if($result['original_activity_typeid'] == 5)
					{
						$revert_pickedup_item_to_original = array(
							'pickedup_uniqueID'  		=> $result['uniqueID'],
							'activity_typeid'  			=> $result['original_activity_typeid'],
							'activity_reference'		=> 1,
							'uniqueID_reference'		=> 0,
							'pickedup_respite_order'	=> 0
						);
					}
					else
					{
						$revert_pickedup_item_to_original = array(
							'pickedup_uniqueID'  		=> $result['uniqueID'],
							'activity_typeid'  			=> $result['original_activity_typeid'],
							'activity_reference'		=> 1,
							'uniqueID_reference'		=> 0
						);
					}
					$reverted_item_activty_type = $this->order_model->revert_delivery_item_to_original($result['equipmentID'],$uniqueID,$medical_record_id,1,$revert_pickedup_item_to_original);
				}
				else if($result['activity_typeid'] == 3)
				{

				}
				$old_activity_type = 1;
			}
			else if($result['original_activity_typeid'] == 2)
			{
				$original_results = $this->order_model->original_list_orders_to_cancel_pickup($medical_record_id, $uniqueID);
				foreach($original_results as $result_inside)
				{
					if($result_inside['original_activity_typeid'] == 3)
					{
						$revert_pickedup_item_to_original = array(
							'pickedup_uniqueID'  		=> $result_inside['uniqueID'],
							'activity_reference'		=> 3,
							'activity_typeid'			=> $result_inside['original_activity_typeid']
						);
					}
					else if($result_inside['original_activity_typeid'] == 5)
					{
						$revert_pickedup_item_to_original = array(
							'pickedup_uniqueID'  		=> $result_inside['uniqueID'],
							'activity_reference'		=> 1,
							'activity_typeid'			=> $result_inside['original_activity_typeid'],
							'pickedup_respite_order'	=> 0
						);
					}
					else
					{
						$revert_pickedup_item_to_original = array(
							'pickedup_uniqueID'  		=> $result_inside['uniqueID'],
							'activity_reference'		=> 1,
							'activity_typeid'			=> $result_inside['original_activity_typeid']
						);
					}
					$reverted_item_activty_type = $this->order_model->revert_pickedup_item_to_original($result_inside['equipmentID'],$uniqueID,$medical_record_id,1,$revert_pickedup_item_to_original);
				}
				$revert_sign = 1;
				$this->order_model->delete_order_dme_order($medical_record_id,$uniqueID);
				$this->order_model->delete_order_dme_order_status($medical_record_id,$uniqueID);
				break;
			}
			else if($result['original_activity_typeid'] == 1)
			{
				if($result['activity_typeid'] == 2)
				{
					$revert_pickedup_item_to_original = array(
						'pickedup_uniqueID'  		=> $result['uniqueID'],
						'activity_reference'		=> 1,
						'activity_typeid'			=> $result['original_activity_typeid']
					);
					$reverted_item_activty_type = $this->order_model->revert_delivery_item_to_original($result['equipmentID'],$uniqueID,$medical_record_id,1,$revert_pickedup_item_to_original);
				}
			}
		}

		$array = array();
		foreach($results as $result)
		{
			$array[] = array(
				"patientID"					=> $result['patientID'],
				"medical_record_id" 		=> $result['medical_record_id'],
				"equipmentID"				=> $result['equipmentID'],
				"equipment_value"			=> $result['equipment_value'],
				"pickup_date"				=> $result['pickup_date'],
				"activity_typeid"			=> $result['activity_typeid'],
				"organization_id"			=> $result['organization_id'],
				"ordered_by"				=> $result['ordered_by'],
				"who_ordered_fname"			=> $result['who_ordered_fname'],
				"who_ordered_lname"			=> $result['who_ordered_lname'],
				"staff_member_fname"		=> $result['staff_member_fname'],
				"staff_member_lname"		=> $result['staff_member_lname'],
				"who_ordered_email"			=> $result['who_ordered_email'],
				"who_ordered_cpnum"			=> $result['who_ordered_cpnum'],
				"deliver_to_type"			=> $result['deliver_to_type'],
				"comment"					=> $result['comment'],
				"date_ordered"				=> $result['date_ordered'],
				"uniqueID"					=> $result['uniqueID'],
				"order_status"				=> $result['order_status'],
				"canceled_from_confirming" 	=> 1
			);
			if($result['original_activity_typeid'] == 1 || $result['original_activity_typeid'] == 4 || $result['original_activity_typeid'] == 5)
			{
				if($result['activity_typeid'] == 2)
				{
					$pickup_order_result = $this->order_model->get_pickup_order($medical_record_id,$result['pickedup_uniqueID']);
					$this->order_model->delete_equipment_pickup($result['equipmentID'],$medical_record_id,$result['pickedup_uniqueID']);
					$equipment_options = get_equipment_options($result['equipmentID']);
					if(!empty($equipment_options))
					{
						foreach ($equipment_options as $value_equip_options)
						{
							$this->order_model->delete_equipment_options_pickup($value_equip_options['equipmentID'],$medical_record_id,$result['pickedup_uniqueID']);
						}
					}
					if(count($pickup_order_result) == 1)
					{
						$this->order_model->delete_order_dme_order_status($medical_record_id,$result['pickedup_uniqueID']);
					}
				}
				else if($result['activity_typeid'] == 3)
				{

				}
			}
			else if($result['original_activity_typeid'] == 3)
			{
				if($result['activity_typeid'] == 2)
				{
					$pickup_order_result = $this->order_model->get_pickup_order($medical_record_id,$result['pickedup_uniqueID']);
					$this->order_model->delete_equipment_pickup($result['equipmentID'],$medical_record_id,$result['pickedup_uniqueID']);
					$equipment_options = get_equipment_options($result['equipmentID']);
					if(!empty($equipment_options))
					{
						foreach ($equipment_options as $value_equip_options)
						{
							$this->order_model->delete_equipment_options_pickup($value_equip_options['equipmentID'],$medical_record_id,$result['pickedup_uniqueID']);
						}
					}
					if(count($pickup_order_result) == 1)
					{
						$this->order_model->delete_order_dme_order_status($medical_record_id,$result['pickedup_uniqueID']);
					}
				}
			}
		}
		$from_where_table = "dme_order";
		$array_encoded = json_encode($array);

		$inserted_data = array(
			"from_where_table" => $from_where_table,
			"data_deleted"	   => $array_encoded,
			"deleted_medical_id" => $medical_record_id,
			"deleted_uniqueID"	=> $uniqueID
		);

		if($order_original_activity_typeid != 2)
		{
			$data_order_update = array(
				'order_status'	=> 'cancel',
				'canceled_from_confirming'	=> 1
			);
			$this->order_model->update_canceled_order_status($medical_record_id,$uniqueID,$data_order_update);
		}
		// $delete_entry = $this->order_model->delete_order($medical_record_id,$uniqueID);
		$trash_added = $this->order_model->save_to_trash($inserted_data);

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Successfully Canceled Order.";

		echo json_encode(array(
			"error"		=> $this->response_code,
			"message"	=> $this->response_message
		));
	}
	// $update_uniqueid_status = array(
	// 	'canceled_from_confirming' => 1
	// );
	// $updated_canceled_status = $this->order_model->update_canceled_status($medical_record_id,$uniqueID,$update_uniqueid_status);

	public function view_order_details($medical_id,$hospiceID="",$unique_id="",$act_id="",$equipID="", $patientID="", $activity_reference_id="")
	{
		$user_type = $this->session->userdata('account_type');

		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_pickup_order_details($medical_id, $unique_id, $act_id, $patientID,$activity_reference_id);

		if($activity_reference_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}

		if($activity_reference_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}

		if($activity_reference_id == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}

		if($activity_reference_id == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		$from_order_status = $this->order_model->get_from_order_status($unique_id,$equipID);
		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id, $hospiceID, $activity_reference_id);

		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['from_order_stats']	  = $from_order_status;

		$this->templating_library->set_view('pages/view_order_details','pages/view_order_details',$data);
	}

	public function view_pickup_order_details($medical_id,$hospiceID="",$unique_id="",$act_id="",$equipID="", $patientID="", $activity_reference_id="")
	{
		$user_type = $this->session->userdata('account_type');

		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_pickup_order_details_v1($medical_id, $unique_id, $act_id, $patientID,$activity_reference_id);

		if($activity_reference_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}

		if($activity_reference_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}

		if($activity_reference_id == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}

		if($activity_reference_id == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		$from_order_status = $this->order_model->get_from_order_status($unique_id,$equipID);
		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id, $hospiceID, $activity_reference_id);
		$data['equipments_ordered_workorder'] = $this->order_model->get_orders_by_workorder($medical_id, $unique_id, $hospiceID);
		$data['equipments_ordered_hidden'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, 3);

		$data['activity_reference_id'] = $activity_reference_id;
		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['from_order_stats']	  = $from_order_status;
		$data['work_order']			  = $unique_id;

		$data['repeating_equipment'] = array();
		$equipmentID = 0;
		$count_here = 1;
		$count_loop = 0;
		foreach ($data['equipments_ordered_workorder'] as $key => $value) {
			if($equipment['parentID'] == 0)
			{
				if($equipmentID == $value['equipmentID'] && $equipmentID != 0)
				{
					$count_here++;
					if($count_here > 1 && $count_here < 3)
					{
						$data['repeating_equipment'][$count_loop] = $value['equipmentID'];
						$count_loop++;
					}
				}
				else
				{
					$equipmentID = $value['equipmentID'];
					$count_here = 1;
				}
			}
		}

		$this->templating_library->set_view('pages/view_pickup_order_details','pages/view_order_details',$data);
	}

	public function view_initial_order_details($medical_id, $hospiceID="", $unique_id="", $act_id="", $equipID="",$patientID="")
	{
		$user_type = $this->session->userdata('account_type');

		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_order_details($medical_id, $unique_id, $act_id, $patientID);
		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered_original($medical_id, $unique_id, $act_id);

		if($act_id == 2 && $informations[0]['activity_typeid'] == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}
		else if($act_id == 2 && $informations[0]['activity_typeid'] == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}
		else if($act_id == 2 && $informations[0]['activity_typeid'] == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}
		else if($act_id == 2 && $informations[0]['activity_typeid'] == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		if($act_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}
		if($act_id == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}
		if($act_id == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['work_order']			  = $unique_id;

		$this->templating_library->set_view('pages/view_original_order_details','pages/view_original_order_details',$data);
	}

	public function view_exchange_order_details($medical_id, $hospiceID="", $unique_id="", $act_id="", $patientID="") //removed  $equipID="" (put back if this will cause errors)
	{
		$user_type = $this->session->userdata('account_type');

		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_order_details($medical_id, $unique_id, $act_id,$patientID);

		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id);

		if($act_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}

		if($act_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}
		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['work_order']			  = $unique_id;

		// echo $unique_id."--";
		// echo $act_id."--";

		$this->templating_library->set_view('pages/view_exchange_order_details','pages/view_exchange_order_details',$data);
	}

	public function view_exchange_pickup_order_details($medical_id, $hospiceID="", $unique_id="", $act_id="", $patientID="") //removed  $equipID="" (put back if this will cause errors)
	{
		$user_type = $this->session->userdata('account_type');

		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_pickup_order_details($medical_id, $unique_id, $act_id,$patientID);

		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id, $hospiceID);
		// $activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		// $activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		if($act_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}

		if($act_id == 3)
		{
			if($informations[0]['original_activity_typeid'] == 2)
			{
				$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
				$data['act_type_id'] = 2;
			}
			else
			{
				$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
			}

		}
		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['work_order']			  = $unique_id;

		$this->templating_library->set_view('pages/view_exchange_pickup_order_details','pages/view_exchange_order_details',$data);
	}

	public function view_order_status_details($medical_id, $hospiceID="", $unique_id="", $act_id="", $patientID="") //removed  $equipID="" (put back if this will cause errors)
	{
		$user_type = $this->session->userdata('account_type');

		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_order_details($medical_id, $unique_id, $act_id, $patientID);
		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id, $hospiceID);
		$data['equipments_ordered_workorder'] = $this->order_model->get_orders_by_workorder($medical_id, $unique_id, $hospiceID);

		$data['repeating_equipment'] = array();
		$equipmentID = 0;
		$count_here = 1;
		$count_loop = 0;
		foreach ($data['equipments_ordered_workorder'] as $key => $value) {
			if($equipment['parentID'] == 0)
			{
				if($equipmentID == $value['equipmentID'] && $equipmentID != 0)
				{
					$count_here++;
					if($count_here > 1 && $count_here < 3)
					{
						$data['repeating_equipment'][$count_loop] = $value['equipmentID'];
						$count_loop++;
					}
				}
				else
				{
					$equipmentID = $value['equipmentID'];
					$count_here = 1;
				}
			}
		}

		if($act_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}

		if($act_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}

		if($act_id == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}

		if($act_id == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['work_order']			  = $unique_id;
		$this->templating_library->set_view('pages/view_order_status_details','pages/view_order_status_details',$data);
	}

	public function canceled()
	{
		$trash = $this->order_model->get_canceled_v2($this->session->userdata('user_location'));
		$patients = get_patient();
		$newarray = array();

		foreach($trash as $key=>$first)
		{
			$merge_array  = $first;
			$data = json_decode($first['data_deleted'], true);
			//printA($data[0]);
			$patientID = $data[0]['patientID'];
			$patientMedID = $data[0]['medical_record_id'];
			$patient_info = array();

			if(isset($patients[$patientID]))
			{
				//consists of data about the user
				$patient_info = $patients[$patientID];
				$medID = $patient_info['medical_record_id'];
				$patient_info['patientID'] = $patientID;

				if($patientMedID == $medID)
				{
					$patient_info['equipmentID'] = $data[0]['equipmentID'];
					$patient_info['status_activity_typeid'] = $data[0]['activity_typeid'];
					$patient_info['uniqueID'] = $data[0]['uniqueID'];
					$patient_info['hospiceID'] = $data[0]['organization_id'];
				}
			}
			else
			{
				$patient_info['organization_id']= "";
				$patient_info['p_lname'] 		= "";
				$patient_info['p_firstname']	= "";
				$patient_info['p_street']		= "";
				$patient_info['p_placenum']		= "";
				$patient_info['p_city']			= "";
				$patient_info['p_state']		= "";
				$patient_info['p_postalcode']	= "";
				$patient_info['p_phonenum']		= "";
				$patient_info['p_altphonenum']  = "";
				$patient_info['p_nextofkin']	= "";
				$patient_info['p_nextofkinnum']	= "";
				$patient_info['med_rec_id']		= "";
				//$patient_info = $patients[$patientID];
			}

			$newarray[] = array_merge($merge_array,$patient_info);
		}
		//$this->order_model->add_order(json_decode($first['data_deleted']));
		$data['trashes'] = $newarray;
		$data['active_nav'] = "canceled";

		$this->templating_library->set('title','Canceled Orders');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/canceled_orders','pages/canceled_orders' , $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function restore_order($trashID, $deleted_uniqueID)
	{
		$trash = $this->order_model->get_spec_trash($trashID);

		$data_canceled_decoded = json_decode($trash[0]['data_deleted']);
		$array = array();

		foreach($data_canceled_decoded as $decoded)
		{
			 $array[] = array(
			 	"patientID"				=> $decoded->patientID,
			 	"medical_record_id"		=> $decoded->medical_record_id,
			 	"equipmentID"			=> $decoded->equipmentID,
			 	"equipment_value"		=> $decoded->equipment_value,
			 	"pickup_date"			=> $decoded->pickup_date,
			 	"activity_typeid"		=> $decoded->activity_typeid,
			 	"organization_id"		=> $decoded->organization_id,
			 	"ordered_by"			=> $decoded->ordered_by,
			 	"who_ordered_fname"		=> $decoded->who_ordered_fname,
				"who_ordered_lname"		=> $decoded->who_ordered_lname,
				"staff_member_fname"	=> $decoded->staff_member_fname,
				"staff_member_lname"	=> $decoded->staff_member_lname,
				"who_ordered_email"		=> $decoded->who_ordered_email,
				"who_ordered_cpnum"		=> $decoded->who_ordered_cpnum,
				"deliver_to_type"		=> $decoded->deliver_to_type,
			 	"comment"			    => $decoded->comment,
			 	"date_ordered"			=> $decoded->date_ordered,
			 	"uniqueID"			    => $decoded->uniqueID,
			 	"order_status"		    => $decoded->order_status,
			 	"canceled_from_confirming" => 0
			 );
		}

		$successful_transfer_to_order = $this->order_model->transfer_to_order($array,$deleted_uniqueID);
		if($successful_transfer_to_order)
		{
			$this->order_model->delete_from_trash($trashID);
		}
		else
		{
			return FALSE;
		}

		//** For the response (include_bottom.php)
		$this->response_code 		= 0;
		$this->response_message		= "Order Form Successfully Restored.";

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function get_equipment_options($equipmentID,$uniqueID,$differ)
	{
		$patient_weight = $this->order_model->get_patient_weight($equipmentID,$uniqueID);
		// $lot_number = $this->order_model->get_lot_number($equipmentID,$uniqueID);
		$options = $this->order_model->get_equipment_options($equipmentID,$uniqueID);
		$key_desc = "";
		$opt_desc = "";
		$equip_value = "";

		//differntiates the two oxygen concentrator
		if(!empty($differ))
		{
			$data['differ'] = $differ + 2;
		}

		$data['options'] = $options;
		$data['unique_id'] = $uniqueID;
		$data['parent_equipmentID'] = $equipmentID;
		$data['patient_weight'] = $patient_weight;
		$data['lotnumbers'] = "";

		if(!empty($data['options']))
		{
			$option_view = $this->templating_library->set_view('pages/equipment_options','pages/equipment_options', $data);
		}
		else
		{
			$option_view = "";
		}
		return $option_view;
	}

	public function change_to_confirmed($medical_id,$uniqueID,$activity_typeid="")
	{
		$post_data = $this->input->post();
		$new_post_data['order_summary'] = array();
		foreach($post_data['order_summary'] as $key=>$value)
		{
			if($value['key_desc'] != "Rate")
			{
				$new_post_data['order_summary'][$key] = $value;
				$new_post_data['order_summary'][$key]['actual_order_date'] = $post_data['actual_order_date'];
			}
			else
			{
				$new_post_data['order_summary'][$key]['person_confirming_order'] = $value['person_confirming_order'];
				$new_post_data['order_summary'][$key]['driver_name'] = $value['driver_name'];
				$new_post_data['order_summary'][$key]['uniqueID'] = $value['uniqueID'];
				$new_post_data['order_summary'][$key]['key_desc'] = $value['key_desc'];
				$new_post_data['order_summary'][$key]['activity_typeid'] = $value['activity_typeid'];
				$new_post_data['order_summary'][$key]['pickedup_respite_order'] = $value['pickedup_respite_order'];
				$new_post_data['order_summary'][$key]['order_date'] = $value['order_date'];
				$new_post_data['order_summary'][$key]['act_name'] = $value['act_name'];
				$new_post_data['order_summary'][$key]['item_num'] = $value['item_num'];
				$new_post_data['order_summary'][$key]['serial_num'] = $value['serial_num'];
				$new_post_data['order_summary'][$key]['orderID'] = $value['orderID'];
				$new_post_data['order_summary'][$key]['actual_order_date'] = $post_data['actual_order_date'];
			}
		}

		$addressID = $this->order_model->get_addressID($uniqueID);
		$address_status = array(
			'status' => 1
		);
		$this->order_model->update_addressID($addressID['addressID'],$address_status);

		if(!empty($new_post_data))
		{
			$count_inside_post = 1;
			foreach($new_post_data['order_summary'] as $key=>$value)
			{
				$this->form_validation->set_rules('order_summary[' . $key . '][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');

				if($activity_typeid == 2)
				{
					$this->form_validation->set_rules('order_summary[' . $key . '][pickedup_date]', ''.$value['key_desc'].' Picked Up Date', 'required');
				}
				if($count_inside_post == 1)
				{
					if(empty($post_data['actual_order_date']))
					{
						$this->form_validation->set_rules('order_summary[' . $key . '][actual_order_date]', 'Order Date', 'required');
					}

					$this->form_validation->set_rules('order_summary[' . $key . '][driver_name]', 'Driver Name', 'required');
					$count_inside_post++;
				}
			}
		}

		if($this->form_validation->run())
		{
			$first_delivery = check_if_first_delivery($medical_id);
			if(($uniqueID == $first_delivery['uniqueID']) && $first_delivery['original_activity_typeid'] == 1)
			{
				$data_update_profile_data = array(
					'date_created'	=> $post_data['actual_order_date']
				);
				update_patient_profile_date($data_update_profile_data,$first_delivery['patientID']);
			}

			$order_first_row = get_order_first_row($medical_id,$uniqueID);
			$latest_pickup_all = get_latest_pickup_all($order_first_row['patientID']);
			if(!empty($latest_pickup_all))
			{
				$first_order_after_deactivation = get_first_order_after_deactivation_p2($order_first_row['patientID'],$latest_pickup_all['orderID'],$latest_pickup_all['uniqueID'],$latest_pickup_all['pickup_date']);
				if($first_order_after_deactivation['uniqueID'] == $uniqueID)
				{
					$data_new_date_created = array(
						"date_created" => $post_data['actual_order_date']
					);
					update_patient_profile_date($data_new_date_created,$order_first_row['patientID']);
				}
			}

			$updated = $this->order_model->update_order_summary_confirm_fields($medical_id, $uniqueID, $new_post_data);

			$new_order_status_data = array(
				"actual_order_date" => $post_data['actual_order_date']
			);
			update_order_status_data($new_order_status_data,$order_first_row['patientID'],$uniqueID);

			$patientID = $first_delivery['patientID'];
			$patient_first_order = get_patient_first_order($patientID);
	      	$returned_data = get_all_patient_pickup($patientID);
	      	$patient_info = get_patient_info($patientID);

	      	$patient_los = 1;
	      	$patient_days = 1;
	        if(empty($returned_data))
	        {
	            $current_date = date("Y-m-d h:i:s");
	            $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
	            $answer_2 = $answer/86400;
	            $patient_los = $patient_los+floor($answer_2);

	            $month_created = date("m", strtotime($patient_info['date_created']));
	            $current_month = date("m");
	            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
	            {
	            	if($current_month == $month_created)
		            {
		            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
		            	$patient_days += 1;
		            }
		            else if($current_month > $month_created)
		            {
		            	$patient_days = date("d");
		            }
	            }
	            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
	            {
	            	$patient_days = date("d");
	            }
	        }
	        else if(count($returned_data) == 1)
	        {
	        	if($returned_data[0]['pickup_sub'] != "not needed")
		        {
		        	$returned_query = check_order_after_all_pickup($returned_data[0]['orderID'], $returned_data[0]['uniqueID'], $returned_data[0]['patientID']);
	                if(!empty($returned_query))
	                {
	                	if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $returned_data[0]['pickup_date'])
	                	{
	                		$current_date = date("Y-m-d h:i:s");
		                    $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
		                    $answer_2 = $answer/86400;
		                    $patient_los = $patient_los+floor($answer_2);

		                    $month_created = date("m", strtotime($patient_info['date_created']));
				            $current_month = date("m");
				            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
				            {
				            	if($current_month == $month_created)
					            {
					            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
					            	$patient_days += 1;
					            }
					            else if($current_month > $month_created)
					            {
					            	$patient_days = date("d");
					            }
				            }
				            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
				            {
				            	$patient_days = date("d");
				            }
	                	}
	                	else
	                	{
	                		$answer = strtotime($returned_data[0]['pickup_date'])-strtotime($patient_info['date_created']);
			                $answer_2 = $answer/86400;
			                $patient_los = $patient_los+floor($answer_2);

			                $month_created = date("m", strtotime($patient_info['date_created']));
			            	$patient_last_month = date("m", strtotime($returned_data[0]['pickup_date']));
			                if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($returned_data[0]['pickup_date'])))
		            		{
		            			if($patient_last_month == $month_created)
					            {
					            	$patient_days = (date("d", strtotime($returned_data[0]['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
					            	$patient_days += 1;
					            }
					            else if($patient_last_month > $month_created)
					            {
					            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
					            }
		            		}
		            		else if(date("Y", strtotime($returned_data[0]['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
				            {
				            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
				            }
	                	}
	                }
	                else
	                {
	                	$answer = strtotime($returned_data[0]['pickup_date'])-strtotime($patient_info['date_created']);
		                $answer_2 = $answer/86400;
		                $patient_los = $patient_los+floor($answer_2);

		                $month_created = date("m", strtotime($patient_info['date_created']));
			            $patient_last_month = date("m", strtotime($returned_data[0]['pickup_date']));
			            if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($returned_data[0]['pickup_date'])))
	            		{
	            			if($patient_last_month == $month_created)
				            {
				            	$patient_days = (date("d", strtotime($returned_data[0]['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
				            	$patient_days += 1;
				            }
				            else if($patient_last_month > $month_created)
				            {
				            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
				            }
	            		}
	            		else if(date("Y", strtotime($returned_data[0]['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
			            {
			            	$patient_days = date("d", strtotime($returned_data[0]['pickup_date']));
			            }
	                }
		        }
		        else
		        {
		        	$current_date = date("Y-m-d h:i:s");
	                $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
	                $answer_2 = $answer/86400;
	                $patient_los = $patient_los+floor($answer_2);

	                $month_created = date("m", strtotime($patient_info['date_created']));
		            $current_month = date("m");
		            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
	            	{
	            		if($current_month == $month_created)
			            {
			            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
			            	$patient_days += 1;
			            }
			            else if($current_month > $month_created)
			            {
			            	$patient_days = date("d");
			            }
	            	}
	            	else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
		            {
		            	$patient_days = date("d");
		            }
		        }
	        }
	        else
	        {
	        	$pickup_all_sign = 0;
	        	$pickup_all_count_sign = 0;
	        	foreach ($returned_data as $value_first_loop){
	        		if($value_first_loop['pickup_date'] >= $patient_first_order['actual_order_date'])
	        		{
	        			if($value_first_loop['pickup_sub'] != "not needed")
		        		{
		        			$pickup_all_sign = 1;
		        			$pickup_all_count_sign++;
		        		}
	        		}
	        	}
	        	if($pickup_all_sign == 0)
	        	{
	        		$current_date = date("Y-m-d h:i:s");
	                $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
	                $answer_2 = $answer/86400;
	                $patient_los = $patient_los+floor($answer_2);

	                $month_created = date("m", strtotime($patient_info['date_created']));
		            $current_month = date("m");
		            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
		            {
		            	if($current_month == $month_created)
			            {
			            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
			            	$patient_days += 1;
			            }
			            else if($current_month > $month_created)
			            {
			            	$patient_days = date("d");
			            }
		            }
		            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
		            {
		            	$patient_days = date("d");
		            }
	        	}
	        	// IF NAAY COMPLETE PICKUP
	        	else
	        	{
	        		$pickup_order_count = 1;
		            $previous_pickup_indications = 0; // 1 for selected item(s) pickup, 2 for complete pickup
		            foreach ($returned_data as $value){

		                if($pickup_order_count == 1)
		                {
		                	if($value['pickup_sub'] != "not needed")
			        		{
			        			if($pickup_all_count_sign == 1)
			        			{
			        				$returned_query_inside = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
			        				if(date("Y-m-d", strtotime($returned_query_inside['date_ordered'])) > $value['pickup_date'])
			        				{
			        					$previous_pickup_indications = 2;
					                    $previous_orderID = $value['orderID'];
					                    $previous_uniqueID = $value['uniqueID'];
					                    $previous_ordered_date = $value['pickup_date'];
					                    $previous_date_ordered = $value['date_ordered'];
					                    $partial_patient_los_first = 1; // Back to 1
					                    $partial_patient_days_first = 1;
			        				}
			        				else
			        				{
			        					$previous_pickup_indications = 1;
			        					$previous_orderID = $value['orderID'];
					                    $previous_uniqueID = $value['uniqueID'];
					                    $previous_ordered_date = $value['pickup_date'];
					                    $previous_date_ordered = $value['date_ordered'];
					                    $answer = strtotime($value['pickup_date'])-strtotime($patient_first_order['actual_order_date']);
					                    $answer_2 = $answer/86400;
					                    $partial_patient_los_first = $patient_los+floor($answer_2);
					                    $partial_patient_days_first = 1;
			        				}
			        			}
			        			else
			        			{
			        				$previous_pickup_indications = 2;
				                    $previous_orderID = $value['orderID'];
				                    $previous_uniqueID = $value['uniqueID'];
				                    $previous_ordered_date = $value['pickup_date'];
				                    $previous_date_ordered = $value['date_ordered'];
				                    $partial_patient_los_first = 1; // Back to 1
				                    $partial_patient_days_first = 1;
			        			}
			        		}
			        		else
			        		{
			        			$answer = strtotime($value['pickup_date'])-strtotime($patient_first_order['actual_order_date']);
			                    $answer_2 = $answer/86400;
			                    $partial_patient_los_first = $patient_los+floor($answer_2);
			                    $previous_pickup_indications = 1;
			                    $previous_orderID = $value['orderID'];
			                    $previous_uniqueID = $value['uniqueID'];
			                    $previous_ordered_date = $value['pickup_date'];
			                    $previous_date_ordered = $value['date_ordered'];
			                    $partial_patient_days_first = 1;
			        		}
		                }
		                else
		                {
		                	if($value['pickup_sub'] != "not needed")
			        		{
			        			$previous_pickup_indications = 2;
			        			if(count($returned_data) == $pickup_order_count)
				                {
				                	$returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
				                	if(!empty($returned_query))
				                	{
				                		if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $value['pickup_date'])
				                		{
			                				$current_date = date("Y-m-d h:i:s");
						                    $answer = strtotime($current_date)-strtotime($patient_info['date_created']);
						                    $answer_2 = $answer/86400;
						                    $patient_los = $patient_los+floor($answer_2);

						                    $month_created = date("m", strtotime($patient_info['date_created']));
								            $current_month = date("m");
								            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
								            {
								            	if($current_month == $month_created)
									            {
									            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
									            	$patient_days += 1;
									            }
									            else if($current_month > $month_created)
									            {
									            	$patient_days = date("d");
									            }
								            }
								            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
								            {
								            	$patient_days = date("d");
								            }
				                		}
				                		else
				                		{
				                			$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
					                        $answer_2 = $answer/86400;
					                        $patient_los = $partial_patient_los_first+floor($answer_2);

					                        $month_created = date("m", strtotime($patient_info['date_created']));
								            $patient_last_month = date("m", strtotime($value['pickup_date']));
								            if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($value['pickup_date'])))
						            		{
						            			if($patient_last_month == $month_created)
									            {
									            	$patient_days = (date("d", strtotime($value['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
									            	$patient_days += 1;
									            }
									            else if($patient_last_month > $month_created)
									            {
									            	$patient_days = date("d", strtotime($value['pickup_date']));
									            }
						            		}
						            		else if(date("Y", strtotime($value['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
								            {
								            	$patient_days = date("d", strtotime($value['pickup_date']));
								            }
				                		}
				                	}
				                	else
				                	{
				                		$answer = strtotime($value['pickup_date'])-strtotime($patient_info['date_created']);
						                $answer_2 = $answer/86400;
						                $patient_los = $patient_los+floor($answer_2);

						                $month_created = date("m", strtotime($patient_info['date_created']));
							            $current_month = date("m");
							            if(date("Y", strtotime($patient_info['date_created'])) == date("Y", strtotime($value['pickup_date'])))
							            {
							            	if($current_month == $month_created)
								            {
								            	$patient_days = (date("d", strtotime($value['pickup_date'])) - date("d", strtotime($patient_info['date_created'])));
								            	$patient_days += 1;
								            }
								            else if($current_month > $month_created)
								            {
								            	$patient_days = date("d", strtotime($value['pickup_date']));
								            }
							            }
							            else if(date("Y", strtotime($value['pickup_date'])) > date("Y", strtotime($patient_info['date_created'])))
							            {
							            	$patient_days = date("d", strtotime($value['pickup_date']));
							            }
				                	}
				                }
				                else
				                {
				                	$returned_query = check_order_after_all_pickup_v2($value['orderID'], $value['uniqueID'], $value['patientID']);
			                		if(date("Y-m-d", strtotime($returned_query['date_ordered'])) > $value['pickup_date'])
			                		{
			                			$partial_patient_los_first = 1;
			                			$previous_date_ordered = $value['date_ordered'];
			                		}
			                		else
			                		{
			                			$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
				                        $answer_2 = $answer/86400;
				                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
			                		}
			                		$partial_patient_days_first = 1;
				                }
			        		}
			        		else
			        		{
			        			$previous_pickup_indications = 1;
			        			if(count($returned_data) == $pickup_order_count)
				                {
				                	$current_date = date("Y-m-d h:i:s");
				                	if($value['pickup_date'] > $current_date)
				                	{
				                		$answer = strtotime($current_date)-strtotime($previous_ordered_date);
				                		$answer_2 = $answer/86400;
				                        $patient_los = $partial_patient_los_first+floor($answer_2);
				                	}
				                	else
				                	{
				                		$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
				                		$answer_2 = $answer/86400;
				                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);

				                        $answer_sub = strtotime($current_date)-strtotime($value['pickup_date']);
				                        $answer_2_sub = $answer_sub/86400;
				                        $patient_los = $partial_patient_los_first+floor($answer_2_sub);
				                	}
				                }
				                else
				                {
				                	if($previous_pickup_indications == 1)
				                	{
				                		$answer = strtotime($value['pickup_date'])-strtotime($previous_ordered_date);
				                        $answer_2 = $answer/86400;
				                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
				                	}
				                	else
				                	{
				                		$answer = strtotime($value['pickup_date'])-strtotime($patient_info['date_created']);
				                        $answer_2 = $answer/86400;
				                        $partial_patient_los_first = $partial_patient_los_first+floor($answer_2);
				                	}
				                	$previous_date_ordered = $value['date_ordered'];
				                }
				                $month_created = date("m", strtotime($patient_info['date_created']));
					            $current_month = date("m");
					            if(date("Y", strtotime($patient_info['date_created'])) == date("Y"))
					            {
					            	if($current_month == $month_created)
						            {
						            	$patient_days = (date("d") - date("d", strtotime($patient_info['date_created'])));
						            	$patient_days += 1;
						            }
						            else if($current_month > $month_created)
						            {
						            	$patient_days = date("d");
						            }
					            }
					            else if(date("Y") > date("Y", strtotime($patient_info['date_created'])))
					            {
					            	$patient_days = date("d");
					            }
			        		}
		              	}
		              	$pickup_order_count++;
		              	$previous_ordered_date = $value['pickup_date'];
		        	}
	        	}
	      	}

	      	$new_data = array(
      		'length_of_stay'	=> $patient_los
	      	);
	      	$this->order_model->update_patient_los_per_patient($new_data,$patientID);

	      	$new_total_los_for_hospice = $this->order_model->get_total_patient_los_per_hospice_updated($patient_info['ordered_by']);
	      	$new_data_for_hospice = array(
	      		'patient_total_los'	=> $new_total_los_for_hospice['total_patient_los']
	      	);
	      	$current_date = date("Y-m-d");
	      	$this->order_model->update_patient_los_for_hospice($new_data_for_hospice,$patient_info['ordered_by'],$current_date);

	      	/* ///////  RESPITE ////////// */
	      	$new_data_patient_days = array(
      		'patient_days'	=> $patient_days
	      	);
	      	$this->order_model->update_patient_days_per_patient($new_data_patient_days,$patientID);

	      	$new_total_patient_days_for_hospice = $this->order_model->get_total_patient_days_per_hospice_updated($patient_info['ordered_by']);
	      	$new_data_for_hospice_patient_days = array(
	      		'total_patient_days'	=> $new_total_patient_days_for_hospice['total_patient_days']
	      	);
	      	$current_date = date("Y-m-d");
	      	$this->order_model->update_patient_days_for_hospice($new_data_for_hospice_patient_days,$patient_info['ordered_by'],$current_date);

			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Changed Order Status";
			}
			else
			{
				$this->response_message = "Failed to change status.";
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function insert_oxygen_concentrator_follow_up($patientID,$equipmentID,$uniqueID,$o2_concentrator_follow_up_sign,$current_act_type,$uniqueID_old)
	{
		$datetime = date('Y-m-d h:i');
		$current_date = date('Y-m-d');
		$temp_follow_up_date = strtotime(date("Y-m-d", strtotime($current_date)) . " +6 month");

		if($o2_concentrator_follow_up_sign == 1)
		{
			if($current_act_type == 1 || $current_act_type == 4)
			{
				$data = array(
					'patientID'			=> $patientID,
					'equipmentID'		=> $equipmentID,
					'uniqueID'			=> $uniqueID,
					'follow_up_date'	=> date("Y-m-d",$temp_follow_up_date),
					'date_added'		=> $datetime
				);
				$this->order_model->insert_oxygen_concentrator_follow_up($data);
			}
			else if($current_act_type == 3)
			{
				$existing_follow_up_date = $this->order_model->get_existing_follow_up_date($patientID,$equipmentID,$uniqueID_old);

				if(!empty($existing_follow_up_date))
				{
					$new_data = array(
						'uniqueID'			=> $uniqueID,
						'follow_up_date'	=> date("Y-m-d",$temp_follow_up_date)
					);
					$this->order_model->update_oxygen_concentrator_follow_up($existing_follow_up_date['follow_up_id'],$new_data);
				}
				else
				{
					$data = array(
						'patientID'			=> $patientID,
						'equipmentID'		=> $equipmentID,
						'uniqueID'			=> $uniqueID,
						'follow_up_date'	=> date("Y-m-d",$temp_follow_up_date),
						'date_added'		=> $datetime
					);
					$this->order_model->insert_oxygen_concentrator_follow_up($data);
				}
			}
		}
		else if($o2_concentrator_follow_up_sign == 2)
		{
			$existing_follow_up_date = $this->order_model->get_existing_follow_up_date($patientID,$equipmentID);

			if(!empty($existing_follow_up_date))
			{
				$new_data = array(
					'uniqueID'			=> $uniqueID,
					'follow_up_date'	=> date("Y-m-d",$temp_follow_up_date)
				);
				$this->order_model->update_oxygen_concentrator_follow_up($existing_follow_up_date['follow_up_id'],$new_data);
			}
			else
			{
				$data = array(
					'patientID'			=> $patientID,
					'equipmentID'		=> $equipmentID,
					'uniqueID'			=> $uniqueID,
					'follow_up_date'	=> date("Y-m-d",$temp_follow_up_date),
					'date_added'		=> $datetime
				);
				$this->order_model->insert_oxygen_concentrator_follow_up($data);
			}
		}
	}

	public function change_to_confirmed_exchange($medical_id,$uniqueID)
	{
		$post_data = $this->input->post();

		$index = 0;
		$count_here = 1;
		if(!empty($post_data))
		{
			foreach($post_data['order_summary'] as $f_key=>$first_val)
			{
				$post_data['order_summary'][$f_key][$index]['actual_order_date'] = $post_data['actual_order_date'];
				foreach($first_val as $key=>$value)
				{
					if(empty($value['pickedup_date']))
					{
						if($value['serial_num'] != "item_options_only")
						{
							$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$key.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
						}
					}
					else
					{
						$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$key.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
						if($count_here == 1)
						{
							$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$key.'][driver_name]', 'Driver Name', 'required');
							if(empty($post_data['actual_order_date']))
							{
								$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$key.'][actual_order_date]', 'Order Date', 'required');
							}
						}
					}
				}
				$index++;
			}
		}

		if($this->form_validation->run())
		{
			$updated = $this->order_model->update_order_summary_confirm_fields_exchange($medical_id, $uniqueID, $post_data);

			$order_first_row = get_order_first_row($medical_id,$uniqueID);
			$new_order_status_data = array(
				"actual_order_date" => $post_data['actual_order_date']
			);
			update_order_status_data($new_order_status_data,$order_first_row['patientID'],$uniqueID);
			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Changed Order Status";
			}
			else
			{
				$this->response_message = "Failed to change status.";
			}
		}
		else
		{
			$this->response_message = validation_errors("<span></span>");
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	public function update_profile_order_summary($medical_id)
	{
		$post_data = $this->input->post();

		//$index = 0;

		// if(!empty($post_data))
		// {
		// 	foreach($post_data['order_summary'] as $f_key=>$first_val)
		// 	{
		// 		foreach($first_val as $key=>$value)
		// 		{
		// 			if(empty($value['pickedup_date']))
		// 			{
		// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
		// 			}
		// 			else
		// 			{
		// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
		// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][pickedup_date]', ''.$value['key_desc'].' Picked Up Date', 'required');
		// 			}

		// 			$index++;
		// 		}
		// 	}
		// }
		$index = 0;
		if(!empty($post_data))
		{
			//print_r($post_data);
			foreach($post_data['order_summary'] as $f_key=>$first_val)
			{
				foreach($first_val as $key=>$value)
				{
					if(empty($value['pickedup_date']))
					{
						$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
					}
					else
					{
						$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
						$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][driver_name]', 'Driver Name', 'required');
					}

				}
				$index++;
			}
		}




		$updated = $this->order_model->update_order_summary_fields($medical_id,$post_data);

		if($updated)
		{
			$this->response_code = 0;
			$this->response_message = "Successfully Changed Order Status";
		}
		else
		{
			$this->response_message = "Failed to change status.";
		}


		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));
	}

	/*
	| @func : update_data
	| @desc : update data base on fields supplied
	| @method : POST
	| @params :
	| 		name : field_name
	|		pk   : unique value
	| 		value : value send to server
	|
	| @date : 01.30.2016
	*/
	public function update_data($type="text",$primarykey="",$required=1)
	{
		if($_POST)
		{
			if($this->session->userdata('account_type') != 'dme_admin')
			{
				return $this->common->response(false);
			}
			$this->load->model("morder");
			$this->form_validation->set_rules("name","Target field",'required');
			$this->form_validation->set_rules("pk","id",'required');
			if($required=="1")
			{
				$this->form_validation->set_rules("value","value",'required');
			}

			if($this->form_validation->run())
			{
				$value 	= $this->input->post("value");
				$name  	= $this->input->post("name");
				$pk 	= $this->input->post("pk");
				if($primarykey=="")
				{
					$this->common->code = 1;
					$this->common->message = "Failed.";
				}
				else
				{
					if($type=="date")
					{
						$value = ($value!="")? date("Y-m-d",strtotime($value)) : "";
					}
					if($type=="workorder")
					{
						$value = (int)($value!="")? substr($pk,0,4).$value : "";
					}
					$options = array(
									"table" => "dme_order",
									"name"  => $primarykey,
									"pk" 	=> $pk
								);
					$data = array(
									$name => $value
								);
					$result = $this->morder->do_update($options,$data);
					if($result)
					{
						$this->common->code 	= 0;
						$this->common->message 	= "Successfully updated";
					}
					else
					{
						$this->common->message = "Failed to update.";
					}
				}
			}
			else
			{
				$this->common->message = validation_errors("<span>","</span>");
			}
		}
		$this->common->response(false);
	}
	/*
	| @func : update_quantity
	| @desc : similar to function update_data but this is customize
			  for quantity only since needed primary keys are equipmentID and uniqueID
	|
	*/
	public function update_quantity()
	{
		if($_POST)
		{
			$this->load->model("morder");
			$this->form_validation->set_rules("name","Target field",'required');
			$this->form_validation->set_rules("pk","id",'required');
			$this->form_validation->set_rules("value","value",'required');
			if($this->form_validation->run())
			{
				$value 	= $this->input->post("value");
				$name  	= $this->input->post("name");
				$pk 	= $this->input->post("pk");
				//<equipmentid>_SEPERATOR_<uniqueid>
				$get_pks = explode("_SEPERATOR_", $pk);
				$options = array(
								"equipmentID" 	=> $get_pks[0],
								"uniqueID"  	=> $get_pks[1]
							);
				$data = array(
								$name => $value
							);
				$result = $this->morder->do_update_quantity($options,$data,"dme_order");
				if($result)
				{
					$this->common->code 	= 0;
					$this->common->message 	= "Successfully updated";
				}
				else
				{
					$this->common->message = "Failed to update.";
				}
			}
			else
			{
				$this->common->message = validation_errors("<span>","</span>");
			}
		}
		$this->common->response(false);
	}

	public function edit_liter_flow($uniqueID, $equipmentID)
	{
		$original_liter_flow = $this->order_model->get_item_liter_flow($uniqueID, $equipmentID);

		$liter_flow_id = 0;

		if($equipmentID == 61)
  		{
  			$liter_flow_id = 77;
  		}
  		if($equipmentID == 62)
  		{
  			$liter_flow_id = 188;
  		}
  		if($equipmentID == 174)
  		{
  			$liter_flow_id = 189;
  		}
  		if($equipmentID == 29)
  		{
  			$liter_flow_id = 100;
  		}
  		if($equipmentID == 30)
  		{
  			$liter_flow_id = 240;
  		}
  		if($equipmentID == 31)
  		{
  			$liter_flow_id = 190;
  		}
  		if($equipmentID == 176)
  		{
  			$liter_flow_id = 191;
  		}
  		if($equipmentID == 36)
  		{
  			$liter_flow_id = 201;
  		}

  		$data['unique_id'] = $uniqueID;
  		$data['liter_flow_id'] = $liter_flow_id;
		$data['initial_liter_flow'] = $original_liter_flow[0]['equipment_value'];
		$this->templating_library->set('title','Edit Liter Flow');
		$this->templating_library->set_view('pages/change_liter_flow','pages/change_liter_flow', $data);
	}

	public function update_liter_flow_value($uniqueID, $equipmentID)
	{
		$this->form_validation->set_rules('liter_flow_qty','Liter Flow Quantity', 'required');

		if($this->form_validation->run())
		{
			$array = array(
				'equipment_value' => $this->input->post('liter_flow_qty')
			);

			$updated = $this->order_model->update_liter_flow_value($uniqueID, $equipmentID, $array);

			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Updated Liter Flow";
			}
			else
			{
				$this->response_message = "Failed.";
			}

		}
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function edit_bipap_option($uniqueID, $equipmentID)
	{

		$original_bipaps = $this->order_model->get_bipap_option($uniqueID, $equipmentID);

		$data['original_bipaps'] = $original_bipaps;
		$data['unique_id'] = $uniqueID;
		$data['parent_equipmentID'] = $equipmentID;
		$this->templating_library->set('title','Edit Liter Flow');
		$this->templating_library->set_view('pages/change_bipap_option','pages/change_bipap_option', $data);
		// $this->templating_library->set_view('common/foot','common/foot');
	}


	public function update_bipap_option($uniqueID, $equipmentID)
	{
		$data_post = $this->input->post();

		foreach($data_post['bipap'] as $key=>$value)
		{
			foreach($value as $f_key=>$f_val)
			{
				$array = array(
					//"equipmentID" => $f_key,
					"equipment_value" => $f_val[0]
				);
				$updated = $this->order_model->update_bipap_option($uniqueID, $f_key, $array);

				if($updated)
				{
					$this->response_code = 0;
					$this->response_message = "Successfully Updated Liter Flow";
				}

				else
				{
					$this->response_message = "Failed.";
				}
			}
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}


	public function edit_cpap_option($uniqueID, $equipmentID)
	{
		$original_cpaps = $this->order_model->get_cpap_option($uniqueID, $equipmentID);

		$data['original_cpaps'] = $original_cpaps;
		$data['unique_id'] = $uniqueID;
		$data['parent_equipmentID'] = $equipmentID;
		$this->templating_library->set('title','Edit Liter Flow');
		$this->templating_library->set_view('pages/change_cpap_option','pages/change_cpap_option', $data);
		$this->templating_library->set_view('common/foot','common/foot');
	}


	public function update_cpap_option($uniqueID, $equipmentID)
	{
		$this->form_validation->set_rules('cpap_value','IPAP value', 'required');

		if($this->form_validation->run())
		{
			$array = array(
				'equipment_value' => $this->input->post('cpap_value')
			);

			$updated = $this->order_model->update_cpap_option($uniqueID, $equipmentID, $array);

			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Updated IPAP.";
			}
			else
			{
				$this->response_message = "Failed.";
			}

		}
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function edit_patient_weight($uniqueID, $equipmentID, $medical_id, $patientID)
	{
		$patient_weights = $this->order_model->get_patient_weight_toedit($uniqueID, $equipmentID, $patientID);

		$data['patient_weights'] = $patient_weights;

		$data['unique_id'] = $uniqueID;
		$data['medical_id'] = $medical_id;
		$data['equipment_id'] = $equipmentID;
		$data['patientID']	= $patientID;

		$this->templating_library->set('title','Edit Liter Flow');
		$this->templating_library->set_view('pages/change_patient_weight','pages/change_patient_weight', $data);
		// $this->templating_library->set_view('common/foot','common/foot');
	}


	public function update_patient_weight($uniqueID, $weightID)
	{
		$this->form_validation->set_rules('patient_weight','Customer Weight', 'required');

		if($this->form_validation->run())
		{
			$array = array(
				'patient_weight' => $this->input->post('patient_weight')
			);

			$updated = $this->order_model->update_patient_weight($uniqueID, $weightID, $array);

			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Updated Weight.";
			}
			else
			{
				$this->response_message = "Failed.";
			}

		}
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}


	public function put_patient_weight($uniqueID)
	{
		$this->form_validation->set_rules('patient_weight','Customer Weight', 'required');

		if($this->form_validation->run())
		{
			$array = array(
				'medical_record_num' => $this->input->post('medical_id'),
				'ticket_uniqueID'=> $this->input->post('unique_id'),
				'equipmentID'	 => $this->input->post('equipment_id'),
				'patientID'		 => $this->input->post('patientID'),
				'patient_weight' => $this->input->post('patient_weight')
			);

			$updated = $this->order_model->put_patient_weight($array);

			if($updated)
			{
				$this->response_code = 0;
				$this->response_message = "Successfully Inserted Weight.";
			}
			else
			{
				$this->response_message = "Failed.";
			}

		}
		else
		{
			$this->response_message = validation_errors('<span></span>');
		}

		echo json_encode(array(
				"error"		=> $this->response_code,
				"message"	=> $this->response_message
		));

	}

	public function show_patient_edit_logs($patientID)
	{
		$data['logs'] = $this->order_model->get_patient_logs($patientID);
		$this->templating_library->set_view('pages/patient_editing_logs','pages/patient_editing_logs', $data);
	}


	public function tracking()
	{
		$this->templating_library->set('title','DME Tracking');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/dme_tracking','pages/dme_tracking');
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function lot_number_tracking()
	{
		$this->templating_library->set('title','DME Tracking');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/lot_number_tracking','pages/lot_number_tracking');
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}

	public function search_items_tracking($searchString)
	{
		$post_data = $this->input->post();
		$return = "";


		$search = $this->order_model->search_items_tracking($searchString);

		if($search)
		{
			if($search['activity_typeid'] == 2)
			{
				if($search['equipmentID'] != 11 && $search['equipmentID'] != 170 && $search['equipmentID'] != 178 && $search['equipmentID'] != 413 && $search['equipmentID'] != 141 && $search['equipmentID'] != 179 && $search['equipmentID'] != 174 && $search['equipmentID'] != 176)
				{
					$get_result = $this->order_model->get_item_pickup_row($search['pickedup_uniqueID']);
					if($get_result['order_status'] == "confirmed")
					{
						echo "<tr style='cursor:pointer;' class='form-control'><td>On Hand.</td></tr>";
					}
					else
					{
						echo "<tr style='cursor:pointer;' class='items_result form-control' title='".$search['serial_num']."' data-id='".$search['medical_record_id']."' data-value='".$search['serial_num']."'><td>Serial Number: ".$search['serial_num']."</td></tr>";
					}
				}
			}
			else
			{
				if($search['equipmentID'] != 11 && $search['equipmentID'] != 170 && $search['equipmentID'] != 178 && $search['equipmentID'] != 413 && $search['equipmentID'] != 141 && $search['equipmentID'] != 179 && $search['equipmentID'] != 174 && $search['equipmentID'] != 176)
				{
					echo "<tr style='cursor:pointer;' class='items_result form-control' title='".$search['serial_num']."' data-id='".$search['medical_record_id']."' data-value='".$search['serial_num']."'><td>Serial Number: ".$search['serial_num']."</td></tr>";
				}
			}
		}
		else
		{
			echo "<tr style='cursor:pointer;' class='form-control'><td>No Results Returned.</td></tr>";
		}
	}

	public function return_item_search()
	{
		$data_get = $this->input->get();

		$data['serial'] = (isset($data_get['serial'])) ? $data_get['serial'] : '';

		$where = array(
			'serial' => str_replace(' ', '%', $data['serial']),
		);
		$active_patients_list = $this->order_model->list_active_patients();
		$result = $this->order_model->list_patients_with_serial_numbers($uniqueID="",$where);
		$data['sign'] = 0;
		$patient_total_los = 0;
		$total_patient_days = 0;

		if(!empty($result))
		{
			$count = 0;
			foreach ($result as $loop_result) {
				if($count == 0)
				{
					if($loop_result['equipmentID'] != 11 && $loop_result['equipmentID'] != 170 && $loop_result['equipmentID'] != 178 && $loop_result['equipmentID'] != 413 && $loop_result['equipmentID'] != 141 && $loop_result['equipmentID'] != 179 && $loop_result['equipmentID'] != 174  && $loop_result['equipmentID'] != 176)
					{
						if($loop_result['activity_typeid'] == 2)
						{
							$get_result = $this->order_model->get_item_pickup_row($loop_result['pickedup_uniqueID']);
							if($get_result['order_status'] == "confirmed")
							{
								$data['sign'] = 1;
								$data['orders'] = "";
							}
							else
							{
								$data['sign'] = 2;
								$data['orders'][0] = $result[0];
								$data['orders'][0]['active_patient'] = 0;
								$patient_total_los = $result[0]['length_of_stay'];
								$total_patient_days = $result[0]['patient_days'];

								foreach($active_patients_list as $loop_active_patient)
								{
									if($loop_active_patient['patientID'] == $loop_result['patientID'])
									{
										// 0 = Inactive, 1 = Active
										$data['orders'][0]['active_patient'] = 1;
									}
								}
							}
						}
						else
						{
							$data['sign'] = 2;
							$data['orders'][0] = $result[0];
							$data['orders'][0]['active_patient'] = 0;
							$patient_total_los = $result[0]['length_of_stay'];
							$total_patient_days = $result[0]['patient_days'];

							foreach($active_patients_list as $loop_active_patient)
							{
								if($loop_active_patient['patientID'] == $loop_result['patientID'])
								{
									// 0 = Inactive, 1 = Active
									$data['orders'][0]['active_patient'] = 1;
								}
							}
						}
						$count++;
					}
				}
			}
		}
		else
		{
			$data['orders'] = "";
		}

		// echo "<pre>";
		// print_r($data['orders']);
		// echo "</pre>";

		$queried_data = $this->order_model->search_items_tracking($data['serial']);
		if(!empty($queried_data))
		{
			if($queried_data['activity_typeid'] == 2 && $data['sign'] == 1)
			{
				$data['counting'] = 0;
			}
			else
			{
				if($queried_data['equipmentID'] != 11 && $queried_data['equipmentID'] != 170 && $queried_data['equipmentID'] != 178 && $queried_data['equipmentID'] != 413 && $queried_data['equipmentID'] != 141 && $queried_data['equipmentID'] != 179 && $queried_data['equipmentID'] != 174  && $queried_data['equipmentID'] != 176)
				{
					$data['counting'] = 1;
				}
				else
				{
					$data['counting'] = 0;
				}
			}
		}
		else
		{
			$data['counting'] = 0;
		}

		$data['total_los_for_today']['patient_total_los'] = $patient_total_los;
		$data['total_patient_days_for_today']['total_patient_days'] = $total_patient_days;
		$data['headering'] = "Equipment Tracking Result(s)";
		$data['search_type'] = "dme_item";

		$this->templating_library->set('title','Customer Order Summary');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/patient_order_grid','pages/patient_order_grid', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	// $data['counting'] = count($this->order_model->list_orders_for_patient_search($uniqueID="",$where));

	public function search_lot_number($searchString)
	{
		$post_data = $this->input->post();
		$return = "";

		$search = $this->order_model->search_lot_number($searchString);
		if($search)
		{
			if($search['equipmentID'] == 11 || $search['equipmentID'] == 170 || $search['equipmentID'] == 178 || $search['equipmentID'] == 413 || $search['equipmentID'] == 141 || $search['equipmentID'] == 179 || $search['equipmentID'] == 174 || $search['equipmentID'] == 176)
			{
				echo "<tr style='cursor:pointer;' class='items_result form-control' title='".$search['serial_num']."' data-id='".$search['medical_record_id']."' data-value='".$search['serial_num']."'><td>Lot Number: ".$search['serial_num']."</td></tr>";
			}
		}
		else
		{
			echo "<tr style='cursor:pointer;' class='form-control'><td>No Results Returned.</td></tr>";
		}
	}

	public function return_lot_number_search()
	{
		$data_get = $this->input->get();

		$data['lotNo'] = (isset($data_get['lotNo'])) ? $data_get['lotNo'] : '';
		$patient_total_los = 0;
		$total_patient_days = 0;
		$where = array(
			'lotNo' => str_replace(' ', '%', $data['lotNo']),
		);

		$first_result = $this->order_model->list_active_patients_for_lot_number();
		$result = $this->order_model->list_patients_with_lot_number($where);

		$count = 0;
		foreach ($first_result as $value) {
			foreach ($result as $inside_value) {
				if($value['patientID'] == $inside_value['patientID'])
				{
					if($inside_value['equipmentID'] == 11 || $inside_value['equipmentID'] == 170 || $inside_value['equipmentID'] == 178 || $inside_value['equipmentID'] == 413 || $inside_value['equipmentID'] == 141 || $inside_value['equipmentID'] == 179 || $inside_value['equipmentID'] == 174 || $inside_value['equipmentID'] == 176)
					{
						$new_result[$count] = $value;
						$new_result[$count]['active_patient'] = 1;
						$count++;
						$patient_total_los = $patient_total_los + $value['length_of_stay'];
						$total_patient_days = $total_patient_days + $value['patient_days'];
					}
				}
			}
		}
		if(!empty($new_result))
		{
			$data['sign'] = 5;
		}
		else
		{
			$data['sign'] = 0;
		}
		$data['orders'] = ($new_result) ? $new_result : FALSE;
		$data['counting'] = count($new_result);
		$data['total_los_for_today']['patient_total_los'] = $patient_total_los;
		$data['total_patient_days_for_today']['total_patient_days'] = $total_patient_days;
		$data['headering'] = "Oxygen Lot# Tracking Result(s)";
		$data['search_type'] = "dme_lot";

		$this->templating_library->set('title','Customer Order Summary');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav');
		$this->templating_library->set_view('pages/patient_order_grid','pages/patient_order_grid', $data);
		$this->templating_library->set_view('common/footer','common/footer');
		$this->templating_library->set_view('common/foot','common/foot');
	}
	// $count = 0;
	// foreach ($result as $loop_result) {
	// 	if($loop_result['equipmentID'] == 11 || $loop_result['equipmentID'] == 170 || $loop_result['equipmentID'] == 178 || $loop_result['equipmentID'] == 413 || $loop_result['equipmentID'] == 141 || $loop_result['equipmentID'] == 179)
	// 	{
	// 		$new_result[$count] = $loop_result;
	// 		$count++;
	// 	}
	// }

	public function pickup_print_order_details($medical_id, $hospiceID="", $unique_id="", $act_id="", $patientID, $activity_reference_id="")
	{
		$user_type = $this->session->userdata('account_type');

		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_pickup_order_details($medical_id, $unique_id, $act_id, $patientID,$activity_reference_id);

		if($activity_reference_id == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}

		if($activity_reference_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}

		if($activity_reference_id == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}

		if($activity_reference_id == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		$from_order_status = $this->order_model->get_from_order_status($unique_id,$equipID);
		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id, $hospiceID, $activity_reference_id);
		$data['equipments_ordered_workorder'] = $this->order_model->get_orders_by_workorder($medical_id, $unique_id, $hospiceID);

		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['from_order_stats']	  = $from_order_status;

		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['work_order']			  = $unique_id;
		$data['activity_fields'] 	  = $activity_type_data;

		$data['repeating_equipment'] = array();
		$equipmentID = 0;
		$count_here = 1;
		$count_loop = 0;
		foreach ($data['equipments_ordered_workorder'] as $key => $value) {
			if($equipment['parentID'] == 0)
			{
				if($equipmentID == $value['equipmentID'] && $equipmentID != 0)
				{
					$count_here++;
					if($count_here > 1 && $count_here < 3)
					{
						$data['repeating_equipment'][$count_loop] = $value['equipmentID'];
						$count_loop++;
					}
				}
				else
				{
					$equipmentID = $value['equipmentID'];
					$count_here = 1;
				}
			}
		}

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $medical_id;

        $response = $this->order_model->get_orders($id);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id);
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }
        $data['activity_reference_id'] = $activity_reference_id;
        $this->templating_library->set('title','Print Work Order');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/print_pickup_order_details','pages/print_pickup_order_details', $data);
		$this->templating_library->set_view('common/footer','common/footer');
	}

	public function print_order_details($medical_id, $hospiceID="", $unique_id="", $act_id="", $patientID)
	{
		$user_type = $this->session->userdata('account_type');

		$data['act_type_id'] = $act_id;
		$informations = $this->order_model->get_order_details($medical_id, $unique_id, $act_id, $patientID);

		$data['equipments_ordered'] = $this->order_model->get_equipments_ordered($medical_id, $unique_id, $act_id);
		$data['equipments_ordered_workorder'] = $this->order_model->get_orders_by_workorder($medical_id, $unique_id, $hospiceID);

		$data['repeating_equipment'] = array();
		$equipmentID = 0;
		$count_here = 1;
		$count_loop = 0;
		foreach ($data['equipments_ordered_workorder'] as $key => $value) {
			if($equipment['parentID'] == 0)
			{
				if($equipmentID == $value['equipmentID'] && $equipmentID != 0)
				{
					$count_here++;
					if($count_here > 1 && $count_here < 3)
					{
						$data['repeating_equipment'][$count_loop] = $value['equipmentID'];
						$count_loop++;
					}
				}
				else
				{
					$equipmentID = $value['equipmentID'];
					$count_here = 1;
				}
			}
		}

		if($act_id == 2 && $informations[0]['activity_typeid'] == 2)
		{
			$activity_type_data = $this->order_model->get_act_type_pickup($unique_id);
		}
		else if($act_id == 2 && $informations[0]['activity_typeid'] == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}
		else if($act_id == 2 && $informations[0]['activity_typeid'] == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}
		else if($act_id == 2 && $informations[0]['activity_typeid'] == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}
		if($act_id == 3)
		{
			$activity_type_data = $this->order_model->get_act_type_exchange($unique_id);
		}
		if($act_id == 4)
		{
			$activity_type_data = $this->order_model->get_act_type_ptmove($unique_id);
		}
		if($act_id == 5)
		{
			$activity_type_data = $this->order_model->get_act_type_respite($unique_id);
		}

		$data['informations'] 		  = $informations;
		$data['activity_fields'] 	  = $activity_type_data;
		$data['work_order']			  = $unique_id;
		$data['activity_fields'] 	  = $activity_type_data;

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $medical_id;

        $response = $this->order_model->get_orders($id);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid'] == 2)
        {
            $pick_data = $this->order_model->get_pickup($id);
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }

        $this->templating_library->set('title','Print Work Order');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/print_order_details','pages/print_order_details', $data);
		$this->templating_library->set_view('common/footer','common/footer');
	}

	public function print_confirm_details($medical_record_id, $uniqueID, $act_id="", $hospiceID="")
	{
		$user_type = $this->session->userdata('account_type');

		$returns = $this->order_model->list_orders($medical_record_id);
		$informations = $this->order_model->get_order_info($medical_record_id, $hospiceID);
		$data['comments'] = $this->order_model->get_all_comments($medical_record_id);

		$datas = array();
		$data['summarys'] = $returns;
		$data['informations'] = $informations;
		$data['medical_record_num'] = $medical_record_id;
		$data['act_id'] = $act_id;
		$data['note_counts'] = $this->order_model->count_patient_comments($medical_record_id);
		$data['work_order_number'] = $uniqueID;

		/** For Equipments **/
		$categories = $this->order_model->get_equipment_category();
		$equipment_array = array();

		foreach($categories as $cat)
		{
			if($user_type == 'dme_admin')
			{
				$children = $this->order_model->get_equipment($cat['categoryID']);
			}
			else
			{
				$children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			}
			$equipment_array[] = array(
				'categoryID' => $cat['categoryID'],
				'type'		 => $cat['type'],
				'children'	 => $children,
				'division'	 => floor(count($children)/2),
				'last'	 	 => count($children)-1
			);
		}
		$data['equipments'] = $equipment_array;

	    $id = $medical_record_id;

        $response = $this->order_model->get_orders($id);
        $order_summary = $this->order_model->get_orders_by_workorder($id, $uniqueID, $hospiceID);

        $orders = array();

        if(!empty($response))
        {
            foreach($response as $key=>$value)
            {
                $cat_ = $value['type'];
                if($value['parentID']==0)
                {
                    $orders[$cat_][trim($value['key_desc'])][] = $value;
                }
                else
                {
                    $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                }
            }
        }
        $data['orders'] = $orders;
        $data['summaries'] = $order_summary;
        /*
         * @pickup
         */
        $data['pickup_sub'] = "";
        $data['unique_id']	= $medical_record_id;
        $data['date']       = "";
        $data['pickup_equipment'] = array();
        if($informations[0]['activity_typeid']==2)
        {
            $pick_data = $this->order_model->get_pickup($id);
            if(!empty($pick_data))
            {
                $data['pickup_sub'] = $pick_data['pickup_sub'];
                $data['date']       = $pick_data['date'];
                $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            }
        }
		$data['infos'] = $this->order_model->get_patient_profile($medical_record_id, $hospiceID);
		$data['work_order_number'] = $uniqueID;
		$this->templating_library->set('title','Print Confirmed Details');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/print_confirm_details','pages/print_confirm_details', $data);
		$this->templating_library->set_view('common/footer','common/footer');
	}


	///update address
	public function update_address()
	{
		if($this->input->post())
		{
			$this->load->library("form_validation");
			$this->form_validation->set_rules("street","Street","required");
			$this->form_validation->set_rules("city","City","required");
			$this->form_validation->set_rules("state","State","required");
			$this->form_validation->set_rules("postal_code","Postal","required");
			$this->form_validation->set_rules("addressID","Address reference","required");
			if($this->form_validation->run())
			{
				$addressID = $this->input->post("addressID");
				$equipment_location_info = get_equipment_location($addressID);

				$patientID = $this->input->post("patientID");
				$patient_info = get_patient_info($patientID);

				$data 	   = $_POST;
				unset($data['addressID']);
				$this->load->model("morder");
				$data_new = array(
					'street'	=> $data['street'],
					'placenum'	=> $data['placenum'],
					'city'	=> $data['city'],
					'state'	=> $data['state'],
					'postal_code' => $data['postal_code']
				);
				$result = $this->morder->update_address($addressID,$data_new);

				if(($equipment_location_info['street'] == $patient_info['p_street']) && ($equipment_location_info['placenum'] == $patient_info['p_placenum']) && ($equipment_location_info['city'] == $patient_info['p_city']) && ($equipment_location_info['state'] == $patient_info['p_state']) && ($equipment_location_info['postal_code'] == $patient_info['p_postalcode']))
				{
					$data_to_update = array(
						'p_street'		=> $data['street'],
						'p_placenum'	=> $data['placenum'],
						'p_city'		=> $data['city'],
						'p_state'		=> $data['state'],
						'p_postalcode'	=> $data['postal_code']
					);
					$this->morder->update_address_patient_profile($patientID,$data_to_update);
				}

				if($result)
				{
					$this->common->code 	= 0;
					$this->common->message 	= "Successfully updated.";
					$this->common->data 	= implode(" ", $data);
				}
				else
				{
					$this->common->code 	= 1;
					$this->common->message 	= "Failed to update.";
				}
			}
			else
			{
				$this->common->code 	= 1;
				$this->common->message = validation_errors("<span>","</span>");
			}
		}
		$this->common->response(false);
	}
	public function get_orders_statuses()
	{
		$this->templating_library->set_view('pages/order_extension/get_status','pages/order_extension/get_status');
	}

	public function work_order()
	{
		$data = array();
		$this->templating_library->set('title','Work Order');
		$this->templating_library->set_view('common/head','common/head');
		$this->templating_library->set_view('common/header','common/header');
		$this->templating_library->set_view('common/nav','common/nav', $data);
		$this->templating_library->set_view('pages/blank_work_order','pages/blank_work_order', $data);
		$this->templating_library->set_view('common/footer','common/footer');
	}

	public function get_hospice_assigned_equipment($hospice_id,$equipment_category=3)
	{

		$data = array();
		 //getting disposable equipments to generate dynamic modal
        $this->load->model("equipment_model");
        $data['disposal_equipments'] = $this->equipment_model->get_assigned_equipments($hospice_id,3);

        $this->load->view('pages/modals/generate-disposable-modal', $data);
	}

}


	/**
			FUNCTION NAME : view_initial_order_details
			For Equipments
	**/
	// $categories = $this->order_model->get_equipment_category();
	// $equipment_array = array();

	// foreach($categories as $cat)
	// {
		// if($user_type == 'dme_admin')
		// {
			// $children = $this->order_model->get_equipment($cat['categoryID']);
		// }
		// else
		// {
			// $children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
		// }
		// $equipment_array[] = array(
			// 'categoryID' => $cat['categoryID'],
			// 'type'		 => $cat['type'],
			// 'children'	 => $children,
			// 'division'	 => floor(count($children)/2),
			// 'last'	 	 => count($children)-1
		// );
	// }
	// $data['equipments'] = $equipment_array;

    // $id = $medical_id;

    // $response = $this->order_model->get_orders($id);

    // $orders = array();

    // if(!empty($response))
    // {
        // foreach($response as $key=>$value)
        // {
            // $cat_ = $value['type'];
            // if($value['parentID']==0)
            // {
                // $orders[$cat_][trim($value['key_desc'])][] = $value;
            // }
            // else
            // {
                // $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
            // }
        // }
    // }
    // $data['orders'] = $orders;
    /*
     * @pickup
     */
    // $data['pickup_sub'] = "";
    // $data['unique_id']	= $medical_id;
    // $data['date']       = "";
    // $data['pickup_equipment'] = array();
    // if($informations[0]['activity_typeid']==2)
    // {
        // $pick_data = $this->order_model->get_pickup($id);
        // if(!empty($pick_data))
        // {
            // $data['pickup_sub'] = $pick_data['pickup_sub'];
            // $data['date']       = $pick_data['date'];
            // $data['pickup_equipment'] = json_decode($pick_data['equipments']);
        // }
    // }
    //$this->templating_library->set_view('common/foot','common/foot');


    //$pickedup_date_info = $this->order_model->get_pickedup_date_info($medical_id);

	/**
		FUNCTION NAME : view_order_details
		For Equipments
	**/
		// $categories = $this->order_model->get_equipment_category();
		// $equipment_array = array();

		// foreach($categories as $cat)
		// {
			// if($user_type == 'dme_admin')
			// {
				// $children = $this->order_model->get_equipment($cat['categoryID']);
			// }
			// else
			// {
				// $children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
			// }
			// $equipment_array[] = array(
				// 'categoryID' => $cat['categoryID'],
				// 'type'		 => $cat['type'],
				// 'children'	 => $children,
				// 'division'	 => floor(count($children)/2),
				// 'last'	 	 => count($children)-1
			// );
		// }
		// $data['equipments'] = $equipment_array;

	    // $id = $medical_id;

        // $response = $this->order_model->get_orders($id);

        // $orders = array();

        // if(!empty($response))
        // {
            // foreach($response as $key=>$value)
            // {
                // $cat_ = $value['type'];
                // if($value['parentID']==0)
                // {
                    // $orders[$cat_][trim($value['key_desc'])][] = $value;
                // }
                // else
                // {
                    // $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
                // }
            // }
        // }
        // $data['orders'] = $orders;
        /*
         * @pickup
         */
        // $data['pickup_sub'] = "";
        // $data['unique_id']	= $medical_id;
        // $data['date']       = "";
        // $data['pickup_equipment'] = array();

        // if($informations[0]['activity_typeid']==2)
        // {
            // $pick_data = $this->order_model->get_pickup($id);
            // if(!empty($pick_data))
            // {
                // $data['pickup_sub'] = $pick_data['pickup_sub'];
                // $data['date']       = $pick_data['date'];
                // $data['pickup_equipment'] = json_decode($pick_data['equipments']);
            // }
        // }


        // printA($activity_type_data);
	// exit;

	/**
		FUNCTION NAME: view_exchange_order_details
		For Equipments
	**/
	// $categories = $this->order_model->get_equipment_category();
	// $equipment_array = array();

	// foreach($categories as $cat)
	// {
		// if($user_type == 'dme_admin')
		// {
			// $children = $this->order_model->get_equipment($cat['categoryID']);
		// }
		// else
		// {
			// $children = $this->order_model->get_equipments_assigned($cat['categoryID'], $hospiceID);
		// }
		// $equipment_array[] = array(
			// 'categoryID' => $cat['categoryID'],
			// 'type'		 => $cat['type'],
			// 'children'	 => $children,
			// 'division'	 => floor(count($children)/2),
			// 'last'	 	 => count($children)-1
		// );
	// }
	// $data['equipments'] = $equipment_array;

    // $id = $medical_id;

    // $response = $this->order_model->get_orders($id);

    // $orders = array();

    // if(!empty($response))
    // {
        // foreach($response as $key=>$value)
        // {
            // $cat_ = $value['type'];
            // if($value['parentID']==0)
            // {
                // $orders[$cat_][trim($value['key_desc'])][] = $value;
            // }
            // else
            // {
                // $orders[$cat_][trim($value['parent_name'])]['children'][] = $value;
            // }
        // }
    // }
    // $data['orders'] = $orders;
    /*
     * @pickup
     */
    // $data['pickup_sub'] = "";
    // $data['unique_id']	= $medical_id;
    // $data['date']       = "";
    // $data['pickup_equipment'] = array();
    // if($informations[0]['activity_typeid']==2)
    // {
        // $pick_data = $this->order_model->get_pickup($id);
        // if(!empty($pick_data))
        // {
            // $data['pickup_sub'] = $pick_data['pickup_sub'];
            // $data['date']       = $pick_data['date'];
            // $data['pickup_equipment'] = json_decode($pick_data['equipments']);
        // }
    // }
    //$this->templating_library->set_view('common/foot','common/foot');


/** FUNCTION NAME: change_to_confirmed

	//print_r($new_post_data);exit();
	commented because i reverted the code to the old one
			//$index = 0;
			// if(!empty($post_data))
			// {
			// 	foreach($post_data['order_summary'] as $f_key=>$first_val)
			// 	{
			// 		foreach($first_val as $key=>$value)
			// 		{
			// 			if(empty($value['pickedup_date']))
			// 			{
			// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
			// 			}
			// 			else
			// 			{
			// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
			// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][pickedup_date]', ''.$value['key_desc'].' Picked Up Date', 'required');
			// 			}

			// 		}
			// 		$index++;
			// 	}
			// }
		**/
    /** FUNCTION NAME: change_to_confirmed

	//print_r($new_post_data);exit();
	commented because i reverted the code to the old one
			//$index = 0;
			// if(!empty($post_data))
			// {
			// 	foreach($post_data['order_summary'] as $f_key=>$first_val)
			// 	{
			// 		foreach($first_val as $key=>$value)
			// 		{
			// 			if(empty($value['pickedup_date']))
			// 			{
			// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
			// 			}
			// 			else
			// 			{
			// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][serial_num]', ''.$value['key_desc'].' Serial Number', 'required');
			// 				$this->form_validation->set_rules('order_summary[' . $f_key . ']['.$index.'][pickedup_date]', ''.$value['key_desc'].' Picked Up Date', 'required');
			// 			}

			// 		}
			// 		$index++;
			// 	}
			// }
		**/
