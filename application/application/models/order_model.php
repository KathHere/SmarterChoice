<?php

	Class order_model extends Ci_Model

	{

		

		//** Function to auto suggest Patient Medical Record Numbers sa Order Form **//



       function search_patients($search_value)
		{
			$this->db->select('*');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_patient as patient','orders.patientID=patient.patientID');
			//$this->db->where('orders.organization_id', $hospice_id);
			$where = "(orders.medical_record_id LIKE '%$search_value%' OR patient.p_fname LIKE '%$search_value%' OR patient.p_lname LIKE '%$search_value%')";
			$this->db->where($where);
			// $this->db->or_like('orders.medical_record_id', $search_value, 'both');
			// $this->db->or_like('patient.p_fname', $search_value, 'both');
			// $this->db->or_like('patient.p_lname', $search_value, 'both');
			$this->db->group_by('orders.medical_record_id');

			$this->db->limit(5);
			$query = $this->db->get();

			return $query->result_array();
		}

		function search_patients_hospice($search_value, $createdby)
		{
			$this->db->select('*');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_patient as patient','orders.patientID=patient.patientID');

			
			// $this->db->like('orders.medical_record_id', $search_value, 'both');
			// $this->db->or_like('patient.p_fname', $search_value, 'both');
			// $this->db->or_like('patient.p_lname', $search_value, 'both');
			$where = "(orders.medical_record_id LIKE '%$search_value%' OR patient.p_fname LIKE '%$search_value%' OR patient.p_lname LIKE '%$search_value%')";
			
			$this->db->where('patient.ordered_by', $createdby);
			$this->db->where($where);
			$this->db->group_by('orders.medical_record_id');

			$this->db->limit(5);
			$query = $this->db->get();
			// $this->db->like('medical_record_id', $search_value, 'both');
			// $this->db->where('ordered_by', $createdby);
			// $this->db->group_by('medical_record_id');
			// $query = $this->db->get('dme_patient', 5);

			//return $this->db->last_query();
			return $query->result_array();
		}

		function search_patient($search_val)

		{

			$this->db->like('medical_record_id', $search_val, 'both');

			$this->db->group_by('medical_record_id');

			$query = $this->db->get('dme_patient', 5);



			return $query->result_array();

		}



		function search_patient_hospice($search_val, $createdby)

		{

			$this->db->like('medical_record_id', $search_val, 'both');

			$this->db->where('ordered_by', $createdby);

			$this->db->group_by('medical_record_id');

			$query = $this->db->get('dme_patient', 5);



			return $query->result_array();

		}



		function get_patient_info($prmnnum)

		{

			$this->db->select('*');

			$this->db->from('dme_patient');

			$this->db->where('medical_record_id', $prmnnum);



			$query = $this->db->get();



			return $query->result_array();

		}

		//** End **//



		

		function add_order($data)

		{

			$this->db->insert('dme_order', $data);

			return $this->db->insert_id();

		}



		function transfer_to_order($data=array(), $uniqueID)

		{

			$this->db->update_batch('dme_order', $data, "uniqueID");
			return TRUE;

		}



		function get_equipment_category()

		{

			$includes = array(1,2,3);

			$this->db->where_in('categoryID',$includes);

			return $this->db->get("dme_equip_category")->result_array();

		}



		function get_equipment($category_id = '')

		{

			if($category_id != '')

			{

				$this->db->where('categoryID' , $category_id);

			}

			$this->db->where('parentID', 0);

			$this->db->order_by('key_desc', 'ASC');

			return $this->db->get('dme_equipment')->result_array();

		}



		function get_equipments_assigned($category_id = '', $hospiceID='')

		{

			$equipment_ids = array();

			if(!empty($hospiceID))

			{

				$equipment_ids = $this->get_assigned_equipments($hospiceID);

			}
			

			$this->db->select('*');

			$this->db->from('dme_equipment');

			if($category_id != '')

			{	

				if(!empty($equipment_ids))
				{
					$this->db->where_in('equipmentID', $equipment_ids);
				}

				$this->db->where('categoryID', $category_id);

			}  

			$this->db->where('parentID', 0);

			$this->db->order_by('key_desc', 'ASC');

			$query = $this->db->get();

			return $query->result_array();

		}


		/*

		*

		* get assigned equipments

		*/

		public function get_assigned_equipments($hospiceID="")

		{

			$ids = array();

			if(!empty($hospiceID))

			{ 

				$this->db->where('hospiceID',$hospiceID);

			}

			$data = $this->db->get('dme_assigned_equipment')->result_array();

			foreach($data as $value)

			{

				$ids[] = $value['equipmentID'];

			}

			return $ids;

		}



		public function get_noncapped()

		{

			$this->db->where('categoryID', 2);

			$this->db->where('parentID', 0);

			$this->db->order_by('key_desc', 'ASC');

			return $this->db->get('dme_equipment')->result_array();

		}

		//end of function



		function add_patient_info($patient_info)

		{

			$query = $this->db->insert('dme_patient', $spec_data);

			return $query;

		}

	



		function get_all_hospice()

		{

			$this->db->select('*');

			$this->db->from('dme_hospice');



			$query = $this->db->get();

			

			return ($query->result()) ? $query->result() : FALSE;

		}



		function get_spec_hospice($userID)

		{

			$this->db->select('*');

			$this->db->from('dme_user');

			$this->db->where('userID', $userID);

			

			$query = $this->db->get();

			

			return ($query->result()) ? $query->result() : FALSE;

		}

	

		function list_order($personID)

		{

			$this->db->select('*');

			$this->db->from('dme_order');

			$this->db->where('person_who_ordered', $personID);

			

			$query = $this->db->get();

			return ($query->result()) ? $query->result() : FALSE;

		}





		/*

		* save patient

		*/

		function save_patientinfo($data = array())

		{

			$response = false;

			if(!empty($data))

			{

					$save_info = $this->db->insert("dme_patient",$data);

					if($save_info)

					{

						$response = $this->db->insert_id();

					}

			}

			return $response;

		}

		/** Save patient info to be edited **/
		function save_patientinfo_to_edit($data = array())

		{

			$response = false;

			if(!empty($data))

			{

					$save_info = $this->db->insert("dme_edited_patient_info",$data);

					if($save_info)

					{

						$response = $this->db->insert_id();

					}

			}

			return $response;

		}



		//save order

		function saveorder($data=array())

		{

			$response = false;

			if(!empty($data))

			{

				$response = $this->db->insert_batch('dme_order',$data);

			}

			return $response;

		}
		/*
		| @this is created due to PT MOVE fixes
		|   it creates a confirmed after creating a pickups for a PT Moves
		| @author : JR
		| @date : 01.15.2016
		*/
		function set_order_status($data=array())
		{
			$response = false;
			if(!empty($data))
			{
				$response = $this->db->insert("dme_order_status",$data);
			}
			return $response;
		}

		// update status of the order base on activity type
		function updateorder($patientID="", $updating_data, $equipment_ids="", $unique_id="", $activity_type_todo="", $ptmove_old_uniqueID="")
		{
			$response = false;


			if(!empty($equipment_ids))
			{
				if($unique_id != '')
				{
					$this->db->where_in('uniqueID', $unique_id);
				}

				$this->db->where('patientID', $patientID);
				

				if($activity_type_todo == 3)
				{
					$this->db->where_in('uniqueID', $unique_id); //newly added - please remove if it will cause errors
					//$this->db->or_where('activity_reference', 1);
				}

				if($ptmove_old_uniqueID != "")
				{
					$this->db->where_in('uniqueID', $ptmove_old_uniqueID);
				}
				// else
				// {
				// 	$this->db->where('activity_reference !=', 1);
				// }
				
				//kani nga line kay para sa exchange nga part of fixes 
				//remove this one para mugana sad ang PT Move nga part.


				//$this->db->where('original_activity_typeid', 1);
				$this->db->where_in('equipmentID', $equipment_ids);

				$response = $this->db->update('dme_order', $updating_data);
			}

			return $response;
		}

		// update status of the order base on activity type
		function updateorder_ptmove($patientID="", $updating_data, $uniqueID)
		{
			$response = false;

			if(!empty($patientID))
			{
				//$this->db->where_in('equipmentID', $equipment_ids);
				//$this->db->where('uniqueID', $uniqueID);
				$this->db->where('patientID', $patientID);
				$response = $this->db->update('dme_order', $updating_data);
			}
			return $response;
		}


		function update_order_respite($medical_record_id="", $updating_data)
		{
			$response = false;

			if(!empty($medical_record_id))
			{
				//$this->db->where_in('equipmentID', $equipment_ids);
				//$this->db->where('uniqueID', $uniqueID);
				$this->db->where('medical_record_id', $medical_record_id);
				$response = $this->db->update('dme_sub_respite', $updating_data);
			}
			return $response;
		}

		function list_addresses($medical_record_id, $hospice_id)
		{
			$this->db->select('*');
			$this->db->from('dme_patient as patients');
			$this->db->join('dme_order as orders','patients.patientID=orders.patientID');
			$this->db->join('dme_sub_respite as respites','orders.medical_record_id=respites.medical_record_id');
			
			$this->db->where('orders.organization_id', $hospice_id); //07-13-2015
			$this->db->where('respites.medical_record_id', $medical_record_id);
			$this->db->where('respites.respite_pickedup !=', 1);
			$this->db->group_by('orders.medical_record_id');
			//$this->db->where('orders.pickedup_respite_order', 1);
			$query = $this->db->get();

			return $query->result_array();
		}

		function get_old_address_ptmove($medical_record_id, $hospice_id)
		{
			$this->db->select('*');
			$this->db->from('dme_patient as patients');
			$this->db->join('dme_order as orders','patients.patientID=orders.patientID','left');
			$this->db->join('dme_sub_ptmove as ptmove','orders.medical_record_id=ptmove.medical_record_id','left');
			
			$this->db->where('orders.organization_id', $hospice_id); //07-13-2015
			$this->db->where('ptmove.medical_record_id', $medical_record_id);
			$this->db->where('ptmove.ptmove_pickedup !=', 1);
			//$this->db->where('ptmove.ptmove_old_address_pickedup', 1);
			$this->db->order_by('ptmove.ptmoveID','ASC');
			$this->db->limit(1); //comment if this will affect others
			//$this->db->group_by('ptmove.medical_record_id'); //uncomment if this will affect others
			//$this->db->where('orders.pickedup_respite_order', 1);
			$query = $this->db->get();

			return $query->result_array();
		}

		function updateptmove_entry($ptmove_id, $updating_data)
		{
			$response = false;

			if(!empty($ptmove_id))
			{
				$this->db->where('ptmoveID', $ptmove_id);
				$response = $this->db->update('dme_sub_ptmove', $updating_data);
			}
			return $response;
		}


		function get_old_address($medical_record_id)
		{
			$this->db->select('*');
			$this->db->from('dme_patient as patients');
			$this->db->join('dme_order as orders','patients.medical_record_id=orders.medical_record_id');
			$this->db->join('dme_sub_respite as respites','orders.medical_record_id=respites.medical_record_id');
			
			$this->db->where('respites.medical_record_id', $medical_record_id);
			$this->db->group_by('orders.medical_record_id');
			//$this->db->where('orders.pickedup_respite_order', 1);
			$query = $this->db->get();

			return $query->result_array();
		}

		//save pickup
		function save($data=array(),$table='dme_pickup_tbl')

		{

			$response = false;

			if(!empty($data))

			{

				$response = $this->db->insert($table,$data);

			}

			return $response;

		}



		function list_orders($uniqueID="",$where=array())
		{
			/** To fix the undefined index when going to the other pages **/
			if(empty($where))
			{
				$where['term'] = "";
				$where['query'] = "";
				$where['param'] = "";
				$where['param2'] = "";
			}
			else
			{
				$where['term'] = $where['term'];
				$where['query'] = $where['query'];
				$where['param'] = $where['param'];
				$where['param2'] = $where['param2'];
			}
			//End

			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
			$this->db->select('patient.*');
			$this->db->select('act.*');
			$this->db->select('hosp.*');
			$this->db->select('equipments.*');
			$this->db->select('cat.*');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			//$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$includes = array(1,2);
			// if($this->session->userdata('account_type') != 'dme_admin')

			// {

			// 	$this->db->where('orders.organization_id', $this->session->userdata('group_id'));

			// }


			// if(!empty($where['patientID']))
			// {	
			// 	printA($where['patientID']);
			// 	$this->db->where('concat(patient.medical_record_id) LIKE \'%'.$this->db->escape_str($where['patientID']).'%\'', null, false);	
				
			// }


			// if($this->session->userdata('account_type') != 'hospice_admin' && $this->session->userdata('account_type') != 'dme_admin')
			// {
			// 	$this->db->where('orders.ordered_by', $this->session->userdata('userID'));
			// }

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}

			if($uniqueID != "" && empty($where['term']) && empty($where['query']) && empty($where['param']) && empty($where['param2'])) 
			{
				$this->db->where('orders.medical_record_id', $uniqueID);
			}

			// else
			// {
			// 	//$this->db->where('orders.medical_record_id', $where['patientID']);
			// 	//$this->db->group_by('orders.uniqueID');
			// 	$this->db->where('orders.medical_record_id LIKE \'%'.$this->db->escape_str($where['param2']).'%\'', null, false);
			// 	$this->db->where('concat(patient.p_lname) LIKE \'%'.$this->db->escape_str($where['param']).'%\'', null, false);
			// 	$this->db->or_where('concat(patient.p_fname) LIKE \'%'.$this->db->escape_str($where['query']).'%\'', null, false);
			// 	$this->db->group_by('patient.medical_record_id');
			// }

			if(!empty($where['term']) && !empty($where['query']) && !empty($where['param']) && !empty($where['param2']))
			{
				$this->db->where('orders.medical_record_id LIKE \'%'.$this->db->escape_str($where['param2']).'%\'', null, false);
				$this->db->where('concat(patient.p_lname) LIKE \'%'.$this->db->escape_str($where['param']).'%\'', null, false);
				$this->db->where('concat(patient.p_fname) LIKE \'%'.$this->db->escape_str($where['query']).'%\'', null, false);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('orders.organization_id');
			}
			else
			{
				$search_string = $where['term'];
				$where = "(orders.medical_record_id LIKE '%$search_string%' OR patient.p_fname LIKE '%$search_string%' OR patient.p_lname LIKE '%$search_string%')";
				$this->db->where($where);
				//$this->db->where('orders.medical_record_id LIKE \'%'.$this->db->escape_str($where['param2']).'%\'', null, false);
				// $this->db->where('concat(patient.p_lname) LIKE \'%'.$this->db->escape_str($where['param']).'%\'', null, false);
				// $this->db->or_where('concat(patient.p_fname) LIKE \'%'.$this->db->escape_str($where['query']).'%\'', null, false);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('orders.organization_id');
			}


			//remove this two lines if it will cause errors -- newly added
			$this->db->where_not_in('orders.activity_typeid', 2);
			$this->db->where('orders.serial_num !=', "item_options_only");
			$this->db->where('orders.serial_num !=', "pickup_order_only");
			$this->db->where('equipments.parentID', 0);
			$this->db->where('orders.canceled_order !=', 1);
			$this->db->where('equipments.categoryID !=', 3);
			//remove this two lines if it will cause errors -- newly added

			//Russel added codes. This is for filtering inactive patients.
			$this->db->where('orders.pickup_order ', 0);
		    $this->db->where('equipments.categoryID !=', 3);
		    $this->db->where('orders.serial_num !=','item_options_only');
		    $this->db->where('orders.canceled_from_confirming !=', 1);
		    $this->db->where('orders.canceled_order !=', 1);

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			//$this->db->where('orders.order_status !=', 'confirmed');
			//$this->db->order_by('orders.date_ordered', 'ASC'); 
			$this->db->order_by('patient.p_lname', 'ASC'); 
			$query = $this->db->get();
		
			return $query->result_array();

		}  
		
		
		function list_orders_for_patient_search($uniqueID="",$where=array())
		{
			/** To fix the undefined index when going to the other pages **/
			if(empty($where))
			{
				$where['term'] = "";
				$where['query'] = "";
				$where['param'] = "";
				$where['param2'] = "";
			}
			else
			{
				$where['term'] = $where['term'];
				$where['query'] = $where['query'];
				$where['param'] = $where['param'];
				$where['param2'] = $where['param2'];
			}
			//End

			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);

			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);

			$this->db->select('patient.*');

			$this->db->select('act.*');

			$this->db->select('hosp.*');

			$this->db->select('equipments.*');

			$this->db->select('cat.*');

			$this->db->from('dme_order AS orders');

			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');

			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');

			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');

			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');

			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			$includes = array(1,2);
			

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}

			if($uniqueID != "" && empty($where['term']) && empty($where['query']) && empty($where['param']) && empty($where['param2'])) 
			{
			
				$this->db->where('orders.medical_record_id', $uniqueID);
			}

			if(!empty($where['term']) && !empty($where['query']) && !empty($where['param']) && !empty($where['param2']))
			{
				$this->db->where('orders.medical_record_id LIKE \'%'.$this->db->escape_str($where['param2']).'%\'', null, false);
				$this->db->where('concat(patient.p_lname) LIKE \'%'.$this->db->escape_str($where['param']).'%\'', null, false);
				$this->db->where('concat(patient.p_fname) LIKE \'%'.$this->db->escape_str($where['query']).'%\'', null, false);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('orders.organization_id');
			}
			else
			{
				$search_string = $where['term'];
				$where = "(orders.medical_record_id LIKE '%$search_string%' OR patient.p_fname LIKE '%$search_string%' OR patient.p_lname LIKE '%$search_string%')";
				$this->db->where($where);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('orders.organization_id');
			}
			//remove this two lines if it will cause errors -- newly added
			// $this->db->where_not_in('orders.activity_typeid', 2);
			// $this->db->where('orders.serial_num !=', "item_options_only");
			// $this->db->where('orders.serial_num !=', "item_options_only");
			// $this->db->where('equipments.categoryID !=', 3);
			
			//remove this two lines if it will cause errors -- newly added

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC'); 
			$query = $this->db->get();
		
			return $query->result_array();
		}  
		
		
		

		function list_order_status($uniqueID="")
		{
			$this->db->select('stats.*');
			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
			$this->db->select('patient.*');
			$this->db->select('act.*');
			$this->db->select('hosp.*');
			$this->db->select('equipments.*');
			$this->db->select('cat.*');

			$this->db->from('dme_order_status as stats');
			$this->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			// $this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
			// $this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
			// $this->db->select('patient.*');
			// $this->db->select('act.*');
			// $this->db->select('hosp.*');
			// $this->db->select('equipments.*');
			// $this->db->select('cat.*');
			// $this->db->select('stats.*');
			// $this->db->from('dme_order AS orders');
			// $this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			// $this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			// $this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			// $this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			// $this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			// $this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');
			// $this->db->join('dme_order_status AS stats',  'orders.uniqueID = stats.order_uniqueID','left');
			

			if($this->session->userdata('account_type') != 'hospice_admin' && $this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.ordered_by', $this->session->userdata('userID'));
			}
			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}
			if($uniqueID != "") 
			{
				$this->db->where('orders.medical_record_id', $uniqueID);
			}
			
			$this->db->group_by('stats.status_activity_typeid');
			$this->db->group_by('stats.order_uniqueID');
			// // $this->db->where('stats.order_status !=', 'confirmed');
			// $this->db->where('stats.order_status !=', 'cancel');
			$this->db->where('orders.order_status !=', 'confirmed');
			$this->db->where('orders.order_status !=', 'cancel');
			$this->db->where('orders.order_status !=', 'tobe_confirmed');

			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_array();
		}  


		function list_tobe_confirmed($uniqueID="")
		{
			$this->db->select('stats.*');
			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
			$this->db->select('patient.*');
			$this->db->select('act.*');
			$this->db->select('hosp.*');
			$this->db->select('equipments.*');
			$this->db->select('cat.*');

			$this->db->from('dme_order_status as stats');
			$this->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			if($this->session->userdata('account_type') != 'hospice_admin' && $this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.ordered_by', $this->session->userdata('userID'));
			}
			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}
			if($uniqueID != "") 
			{
				$this->db->where('orders.medical_record_id', $uniqueID);
			}
			
			$this->db->group_by('stats.status_activity_typeid');
			$this->db->group_by('stats.order_uniqueID');
			$this->db->where('orders.order_status !=', 'confirmed');
			$this->db->where('orders.order_status !=', 'cancel');
			$this->db->where('orders.order_status', 'tobe_confirmed');

			$query = $this->db->get();
			return $query->result_array();
		}



		function list_orders_to_cancel($uniqueID="", $work_order="")
		{

			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);

			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);

			$this->db->select('patient.*');

			$this->db->select('act.*');

			$this->db->select('hosp.*');

			$this->db->select('equipments.*');

			$this->db->select('cat.*');

			$this->db->from('dme_order AS orders');

			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');

			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');

			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');

			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');

			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');


			$includes = array(1,2);

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}

			
			$this->db->where('orders.medical_record_id', $uniqueID);
			$this->db->where('uniqueID', $work_order);

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC'); 
			$query = $this->db->get();
		
			return $query->result_array();

		}  


		function list_orders_sorted($uniqueID="",$hospiceID="",$where=array())
		{

			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);

			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);

			$this->db->select('patient.*');

			$this->db->select('act.*');

			$this->db->select('hosp.*');

			$this->db->select('equipments.*');

			$this->db->select('cat.*');

			$this->db->from('dme_order AS orders');

			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');

			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');

			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');

			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');

			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			$includes = array(1,2);

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}

			if($hospiceID != "" && $hospiceID != "all")
			{
				$this->db->where('orders.organization_id', $hospiceID);
			}

			/** Newly Added **/
			$this->db->where_not_in('orders.activity_typeid', 2);
			$this->db->where('orders.canceled_order !=' , 1);
			$this->db->where('orders.serial_num !=', "item_options_only");
			$this->db->where('orders.serial_num !=', "pickup_order_only");
			$this->db->where('equipments.categoryID !=', 3);
			/** End **/

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC'); 
			$query = $this->db->get();
		
			return $query->result_array();

		}  



		function gridview_list($entries='')
		{
			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
			$this->db->select('patient.*');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');

			$this->db->where('orders.order_status !=', 'confirmed');
			$this->db->order_by('orders.date_ordered', 'DESC'); 
			$this->db->group_by('patient.medical_record_id');

			if($entries=='20')
			{
				$this->db->limit(20);
			}
			elseif($entries=='40')
			{
				$this->db->limit(40);
			}
			elseif($entries=='60')
			{
				$this->db->limit(60);
			}
			elseif($entries=='80')
			{
				$this->db->limit(80);
			}
			else
			{
				$this->db->limit(100);
			}

			//$this->db->limit(20);
			$query = $this->db->get();
			return $query->result_array();

		}

		 

		function list_confirmed_orders($uniqueID="")

		{



			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);

			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);

			$this->db->select('patient.*');

			$this->db->select('act.*');

			$this->db->select('hosp.*');

			$this->db->select('equipments.*');

			$this->db->select('cat.*');

			//$this->db->select('pickups.*');

			$this->db->from('dme_order AS orders');

			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');

			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');

			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');

			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');

			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			//$this->db->join('dme_pickup AS pickups',  'pickups.orderUniqueID = orders.uniqueID','left');
			//$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$includes = array(1,2);

			



			if($this->session->userdata('account_type') != 'dme_admin')

			{

					$this->db->where('orders.organization_id', $this->session->userdata('group_id'));

			}

			if($uniqueID != "") 

			{



				$this->db->where('orders.uniqueID', $uniqueID);

			}

			else 

			{

				$this->db->group_by('orders.uniqueID');

			}



			$this->db->where('orders.order_status', 'confirmed');
			//$this->db->where('orders.activity_typeid !=', '2');
			$this->db->order_by('orders.date_ordered', 'DESC'); 

			$query = $this->db->get();



			return $query->result_array();

		}





		function get_equipment_summary($category_id = '', $uniqueID = '')

		{

			$this->db->select('equip.*');

			$this->db->select('orders.*');

			$this->db->from('dme_order as orders');

			$this->db->join('dme_equipment as equip', 'orders.equipmentID = equip.equipmentID', 'left');



			if($category_id != '')

			{

				$this->db->where('equip.categoryID' , $category_id);

				$this->db->where('orders.uniqueID' , $uniqueID);

			}



			$this->db->where('equip.parentID', 0);

			$this->db->order_by('equip.key_desc', 'ASC');



			$query = $this->db->get();

			

			

			return $query->result_array();

		}





		function get_order_info($medical_record_id, $hospiceID)

		{

			$this->db->select('orders.*');

			$this->db->select('hosp.*');

			$this->db->select('users.*');

			$this->db->select('pat.*');

			$this->db->select('act.*');

			// $this->db->select('ptmove.*');

			// $this->db->select('respite.*');

			// $this->db->select('pickups.*');

			$this->db->from('dme_order as orders');

			$this->db->join('dme_hospice as hosp' , 'orders.organization_id = hosp.hospiceID', 'left');

			$this->db->join('dme_user as users', 'users.userID = orders.ordered_by', 'left');

			$this->db->join('dme_patient as pat', 'pat.patientID = orders.patientID', 'left'); //originally this is pat.medical_record_id=orders.medical_record_id

			$this->db->join('dme_activity_type act' , 'act.activity_id = orders.activity_typeid', 'left');

			// $this->db->join('dme_sub_ptmove ptmove' , 'pat.medical_record_id = ptmove.medical_record_id', 'left');

			// $this->db->join('dme_sub_respite respite' , 'pat.medical_record_id = respite.medical_record_id', 'left');

			// $this->db->join('dme_pickup as pickups' , 'pickups.orderUniqueID = orders.uniqueID', 'left');

			

			if($this->session->userdata('account_type') != 'dme_admin')

			{

				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));

			}
			
			$this->db->where('orders.organization_id', $hospiceID);
			$this->db->where('orders.medical_record_id', $medical_record_id);
			$this->db->group_by('orders.medical_record_id');



			$query = $this->db->get();

			return $query->result_array();
		}


		




		function get_order_details($medical_record_id,$uniqueID,$act_id,$patientID)

		{
			
			$this->db->select('orders.*');

			$this->db->select('hosp.*');

			$this->db->select('users.*');

			$this->db->select('pat.*');

			$this->db->select('act.*');

			// $this->db->select('ptmove.*');

			// $this->db->select('respite.*');

			// $this->db->select('exchange.*');

			// $this->db->select('picks.*');

			$this->db->from('dme_order as orders');

			$this->db->join('dme_hospice as hosp' , 'orders.organization_id = hosp.hospiceID', 'left');

			$this->db->join('dme_user as users', 'users.userID = orders.ordered_by', 'left');

			$this->db->join('dme_patient as pat', 'pat.patientID = orders.patientID', 'left'); //originally this is pat.medical_record_id=orders.medical_record_id

			$this->db->join('dme_activity_type act' , 'act.activity_id = orders.activity_typeid', 'left');

			// $this->db->join('dme_sub_ptmove ptmove' , 'pat.medical_record_id = ptmove.medical_record_id', 'left');

			// $this->db->join('dme_sub_respite respite' , 'pat.medical_record_id = respite.medical_record_id', 'left');

			// $this->db->join('dme_sub_exchange exchange' , 'pat.medical_record_id = exchange.medical_record_id', 'left');

			// $this->db->join('dme_pickup_tbl as picks' , 'pat.medical_record_id = picks.medical_record_id', 'left');


			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}


			if($act_id == 3)
			{
				$this->db->where('orders.original_activity_typeid', $act_id);
			}
			else if($act_id == 4)
			{
				$this->db->where('orders.original_activity_typeid', $act_id);
			}
			else
			{
				$this->db->where('orders.activity_typeid', $act_id);
			}

			$this->db->where('orders.medical_record_id', $medical_record_id);
			$this->db->where('orders.uniqueID', $uniqueID);
			$this->db->where('orders.patientID', $patientID);
			$this->db->group_by('orders.medical_record_id');

			$query = $this->db->get();

			return $query->result_array();

		}



		function get_order_info_for_email($uniqueID)

		{

			$this->db->select('orders.*');

			$this->db->select('hosp.*');

			$this->db->select('users.*');

			$this->db->select('pat.*');

			$this->db->select('act.*');

			// $this->db->select('ptmove.*');

			// $this->db->select('respite.*');

			//$this->db->select('pickups.*');

			$this->db->from('dme_order as orders');

			$this->db->join('dme_hospice as hosp' , 'orders.organization_id = hosp.hospiceID', 'left');

			$this->db->join('dme_user as users', 'users.userID = orders.ordered_by', 'left');

			$this->db->join('dme_patient as pat', 'pat.patientID = orders.patientID', 'left'); //originally this is pat.medical_record_id=orders.medical_record_id

			$this->db->join('dme_activity_type act' , 'act.activity_id = orders.activity_typeid', 'left');

			// $this->db->join('dme_sub_ptmove ptmove' , 'pat.medical_record_id = ptmove.medical_record_id', 'left');

			// $this->db->join('dme_sub_respite respite' , 'pat.medical_record_id = respite.medical_record_id', 'left');

			// $this->db->join('dme_pickup as pickups' , 'pickups.orderUniqueID = orders.uniqueID', 'left');

			

			if($this->session->userdata('account_type') != 'dme_admin')

			{

				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));

			}

			

			$this->db->where('orders.uniqueID', $uniqueID);

			$this->db->group_by('orders.medical_record_id');



			$query = $this->db->get();

			return $query->result_array();

		}



		// function get_act_type_info($uniqueID)
		// {
		// 	$this->db->select('*');
		// 	$this->db->from('dme_order as orders');
		// 	$this->db->join('dme_patient as pat', 'pat.medical_record_id = orders.medical_record_id', 'left');
		// 	$this->db->join('dme_activity_type act' , 'act.activity_id = orders.activity_typeid', 'left');
		// 	$this->db->join('dme_sub_exchange exchange' , 'orders.medical_record_id = exchange.medical_record_id', 'left');
		// 	$this->db->join('dme_sub_ptmove ptmove' , 'orders.medical_record_id = ptmove.medical_record_id', 'left');
		// 	$this->db->join('dme_sub_respite respite' , 'orders.medical_record_id = respite.medical_record_id', 'left');
		// 	$this->db->join('dme_pickup_tbl as pickups' , 'pickups.medical_record_id = orders.medical_record_id', 'left');

		// 	//$this->db->where('orders.medical_record_id', $medical_record_id);

		// 	$this->db->where('pickups.order_uniqueID', $uniqueID);
			
			
		// 	$this->db->group_by('orders.medical_record_id');

		// 	$query = $this->db->get();
		// 	return $query->result_array();
		// }

		function get_act_type_pickup($uniqueID)
		{
			$this->db->select('orders.pickup_date');
			$this->db->select('pickups.date_pickedup,pickups.pickup_sub');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_pickup_tbl as pickups' , 'pickups.patientID = orders.patientID', 'left');
			
			$this->db->query('SET SQL_BIG_SELECTS=1'); 
			$this->db->where('pickups.order_uniqueID', $uniqueID);

			$this->db->group_by('orders.medical_record_id');

			$query = $this->db->get();
			return $query->result_array();
		}

		function get_act_type_exchange($uniqueID)
		{
			$this->db->select('orders.pickup_date');
			$this->db->select('exchange.exchange_date,exchange.exchange_reason');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_sub_exchange exchange' , 'orders.patientID = exchange.patientID', 'left');
			
			$this->db->query('SET SQL_BIG_SELECTS=1'); 
			$this->db->where('exchange.order_uniqueID', $uniqueID);

			$this->db->group_by('orders.medical_record_id');

			$query = $this->db->get();
			return $query->result_array();
		}

		function get_act_type_ptmove($uniqueID)
		{
			$this->db->select('orders.pickup_date');
			$this->db->select('ptmove.ptmove_delivery_date,ptmove.ptmove_street,ptmove.ptmove_placenum,ptmove.ptmove_city,ptmove.ptmove_state,ptmove.ptmove_postal');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_sub_ptmove ptmove' , 'orders.patientID = ptmove.patientID', 'left');
			
			$this->db->query('SET SQL_BIG_SELECTS=1'); 
			$this->db->where('ptmove.order_uniqueID', $uniqueID);

			$this->db->group_by('orders.medical_record_id');

			$query = $this->db->get();
			return $query->result_array();
		}

		function get_act_type_respite($uniqueID)
		{
			$this->db->select('orders.pickup_date');
			$this->db->select('respite.respite_delivery_date,respite.respite_pickup_date,respite.respite_deliver_to_type,respite.respite_address,respite.respite_placenum,respite.respite_city,respite.respite_state,respite.respite_postal');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_sub_respite respite' , 'orders.medical_record_id = respite.medical_record_id', 'left');
			
			$this->db->query('SET SQL_BIG_SELECTS=1'); 
			$this->db->where('respite.order_uniqueID', $uniqueID);

			$this->db->group_by('orders.medical_record_id');

			$query = $this->db->get();
			return $query->result_array();
		}

		// function get_act_type_info($uniqueID, $act_id="")
		// {
		// 	$this->db->select('orders.pickup_date');
		// 	$this->db->select('pickups.date_pickedup,pickups.pickup_sub');
		// 	$this->db->select('exchange.exchange_date,exchange.exchange_reason');
		// 	$this->db->select('ptmove.ptmove_delivery_date,ptmove.ptmove_street,ptmove.ptmove_placenum,ptmove.ptmove_city,ptmove.ptmove_state,ptmove.ptmove_postal');
		// 	$this->db->select('respite.respite_delivery_date,respite.respite_pickup_date,respite.respite_deliver_to_type,respite.respite_address,respite.respite_placenum,respite.respite_city,respite.respite_state,respite.respite_postal');

		// 	$this->db->from('dme_order as orders');
		// 	$this->db->join('dme_patient as pat', 'pat.patientID = orders.patientID', 'left'); //originally this is pat.medical_record_id=orders.medical_record_id
		// 	$this->db->join('dme_activity_type act' , 'act.activity_id = orders.activity_typeid', 'left');
		// 	$this->db->join('dme_sub_exchange exchange' , 'orders.medical_record_id = exchange.medical_record_id', 'left');
		// 	$this->db->join('dme_sub_ptmove ptmove' , 'orders.medical_record_id = ptmove.medical_record_id', 'left');
		// 	$this->db->join('dme_sub_respite respite' , 'orders.medical_record_id = respite.medical_record_id', 'left');
		// 	$this->db->join('dme_pickup_tbl as pickups' , 'pickups.medical_record_id = orders.medical_record_id', 'left');

		// 	//$this->db->where('orders.medical_record_id', $medical_record_id);

		// 	$this->db->query('SET SQL_BIG_SELECTS=1'); 
	
		// 	if($act_id == 2)
		// 	{
				
		// 		$this->db->where('pickups.order_uniqueID', $uniqueID);
		// 	}

		// 	if($act_id == 3)
		// 	{
		// 		$this->db->where('exchange.order_uniqueID', $uniqueID);
		// 	}

		// 	if($act_id == 4)
		// 	{
		// 		$this->db->where('ptmove.order_uniqueID', $uniqueID);
		// 	}

		// 	if($act_id == 5)
		// 	{
		// 		$this->db->where('respite.order_uniqueID', $uniqueID);
		// 	}
			
		// 	$this->db->group_by('orders.medical_record_id');

		// 	$query = $this->db->get();
		// 	return $query->result_array();
		// }
	

		// function get_pickedup_date_info($medical_id)
		// {
		// 	$this->db->select('*');
		// 	$this->db->from('dme_order as orders');
		// 	$this->db->join('dme_patient as pat', 'pat.medical_record_id = orders.medical_record_id', 'left');
		// 	$this->db->join('dme_activity_type act' , 'act.activity_id = orders.activity_typeid', 'left');
		// 	$this->db->join('dme_sub_exchange exchange' , 'orders.medical_record_id = exchange.medical_record_id', 'left');
		// 	$this->db->join('dme_sub_ptmove ptmove' , 'orders.medical_record_id = ptmove.medical_record_id', 'left');
		// 	$this->db->join('dme_sub_respite respite' , 'orders.medical_record_id = respite.medical_record_id', 'left');
		// 	$this->db->join('dme_pickup_tbl as pickups' , 'pickups.medical_record_id = orders.medical_record_id', 'left');
		
		// 	$this->db->where('pickups.medical_record_id', $medical_id);

		// 	$this->db->group_by('orders.medical_record_id');

		// 	$query = $this->db->get();
		// 	return $query->result_array();
		// }


		public function get_orders($unique, $hospiceID="")
        {
        			$cancel = "cancel";
        			
                    $this->db->select('equip.*');
                    $this->db->select('orders.*');
                    $this->db->select('patients.*');
                    $this->db->select('hospices.*'); //newly added : remove if it will cause errors
                    $this->db->select('COALESCE((SELECT key_desc FROM dme_equipment WHERE dme_equipment.equipmentID=equip.parentID LIMIT 1),"") as parent_name',FALSE);
                    $this->db->select('COALESCE((SELECT option_description FROM dme_options WHERE dme_options.optionID=equip.optionID LIMIT 1),"") as option_description',FALSE);
                    $this->db->select('COALESCE((SELECT type FROM dme_equip_category WHERE dme_equip_category.categoryID=equip.categoryID LIMIT 1),"") as type',FALSE);
                    $this->db->select('COALESCE((SELECT activity_name FROM dme_activity_type WHERE dme_activity_type.activity_id=orders.activity_typeid LIMIT 1),"") as activity_name',FALSE);
                    $this->db->select('COALESCE((SELECT hospice_name FROM dme_hospice WHERE dme_hospice.hospiceID=orders.organization_id LIMIT 1),"") as hospice_name',FALSE); //newly added : remove if it will cause errors
                    $this->db->from('dme_order as orders');
                    $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
                    $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
                    $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left'); //changed the medical_record_id to patientID
                    $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors
					
					$this->db->query('SET SQL_BIG_SELECTS=1');  //newly added 09/18/2015
                    if($unique != '')
                    {
                        $this->db->where('orders.medical_record_id' , $unique);

                        $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
                    }
                    //$this->db->where('equip.parentID', 0);
                    $this->db->where('orders.pickup_order ', 0);
                    $this->db->where("orders.order_status !=", $cancel);

                    //$this->db->where('orders.activity_typeid !=', 2);

                   	$this->db->group_by('orders.orderID');
                    $this->db->order_by('orders.pickup_date', 'DESC');

                    //$this->db->order_by('equip.key_desc', 'ASC');
                    $data = $this->db->get()->result_array();
                    return $data;
        }

        public function get_orders_for_pickup_items($unique, $hospiceID)
        {
                    $this->db->select('equip.*');
                    $this->db->select('orders.*');
                    $this->db->select('patients.*');
                    $this->db->select('hospices.*'); //newly added : remove if it will cause errors
                    $this->db->select('COALESCE((SELECT key_desc FROM dme_equipment WHERE dme_equipment.equipmentID=equip.parentID LIMIT 1),"") as parent_name',FALSE);
                    $this->db->select('COALESCE((SELECT option_description FROM dme_options WHERE dme_options.optionID=equip.optionID LIMIT 1),"") as option_description',FALSE);
                    $this->db->select('COALESCE((SELECT type FROM dme_equip_category WHERE dme_equip_category.categoryID=equip.categoryID LIMIT 1),"") as type',FALSE);
                    $this->db->select('COALESCE((SELECT activity_name FROM dme_activity_type WHERE dme_activity_type.activity_id=orders.activity_typeid LIMIT 1),"") as activity_name',FALSE);
                    $this->db->select('COALESCE((SELECT hospice_name FROM dme_hospice WHERE dme_hospice.hospiceID=orders.organization_id LIMIT 1),"") as hospice_name',FALSE); //newly added : remove if it will cause errors
                    $this->db->from('dme_order as orders');
                    $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
                    $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
                    $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left');
                    $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors


                    if($unique != '')
                    {
                        $this->db->where('orders.medical_record_id' , $unique);

                        $this->db->where('orders.organization_id', $hospiceID); //newly added 07/13/2015.
                    }
                    //$this->db->where('equip.parentID', 0);
                    $this->db->where('orders.pickup_order ', 0);
                    $this->db->where('orders.activity_typeid !=', 2);

                   	$this->db->group_by('orders.orderID');
                    $this->db->order_by('orders.pickup_date', 'DESC');

                    //$this->db->order_by('equip.key_desc', 'ASC');
                    $data = $this->db->get()->result_array();
                   
                    return $data;
        }

        public function get_orders_by_workorder($id, $unique="", $hospiceID="")
        {	
                    $this->db->select('equip.*');
                    $this->db->select('orders.*');
                    $this->db->select('patients.*');
                    $this->db->select('hospices.*'); //newly added : remove if it will cause errors
                    $this->db->select('COALESCE((SELECT key_desc FROM dme_equipment WHERE dme_equipment.equipmentID=equip.parentID LIMIT 1),"") as parent_name',FALSE);
                    $this->db->select('COALESCE((SELECT option_description FROM dme_options WHERE dme_options.optionID=equip.optionID LIMIT 1),"") as option_description',FALSE);
                    $this->db->select('COALESCE((SELECT type FROM dme_equip_category WHERE dme_equip_category.categoryID=equip.categoryID LIMIT 1),"") as type',FALSE);
                    $this->db->select('COALESCE((SELECT activity_name FROM dme_activity_type WHERE dme_activity_type.activity_id=orders.activity_typeid LIMIT 1),"") as activity_name',FALSE);
                    
                    $this->db->select('COALESCE((SELECT pickup_sub FROM dme_pickup_tbl WHERE dme_pickup_tbl.patientID=orders.patientID LIMIT 1),"") as pickup_sub',FALSE); //newly added : remove if it will cause errors

                    $this->db->select('COALESCE((SELECT hospice_name FROM dme_hospice WHERE dme_hospice.hospiceID=orders.organization_id LIMIT 1),"") as hospice_name',FALSE); //newly added : remove if it will cause errors
                    $this->db->from('dme_order as orders');
                    $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
                    $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
                    $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left');
                    $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors

                    if($unique != '')
                    {
                        $this->db->where('orders.medical_record_id' , $id);

                        $this->db->where('orders.organization_id', $hospiceID); //07/13/2015
                    }
                    //$this->db->where('equip.parentID', 0);
                    //$this->db->where('orders.pickup_order ', 0);
                    $this->db->where('orders.uniqueID',$unique);
                    $this->db->group_by('orders.orderID');
                    $this->db->order_by('orders.pickup_date', 'DESC');
                    //$this->db->order_by('equip.key_desc', 'ASC');
                    $data = $this->db->get()->result_array();
                    return $data;
        }


        public function get_orders_by_workorder_exchange($id, $uniqueID="", $hospiceID="")
        {
                    $this->db->select('equip.*');
                    $this->db->select('orders.*');
                    $this->db->select('patients.*');
                    $this->db->select('hospices.*'); //newly added : remove if it will cause errors
                    $this->db->select('COALESCE((SELECT key_desc FROM dme_equipment WHERE dme_equipment.equipmentID=equip.parentID LIMIT 1),"") as parent_name',FALSE);
                    $this->db->select('COALESCE((SELECT option_description FROM dme_options WHERE dme_options.optionID=equip.optionID LIMIT 1),"") as option_description',FALSE);
                    $this->db->select('COALESCE((SELECT type FROM dme_equip_category WHERE dme_equip_category.categoryID=equip.categoryID LIMIT 1),"") as type',FALSE);
                    $this->db->select('COALESCE((SELECT activity_name FROM dme_activity_type WHERE dme_activity_type.activity_id=orders.activity_typeid LIMIT 1),"") as activity_name',FALSE);
                    $this->db->select('COALESCE((SELECT hospice_name FROM dme_hospice WHERE dme_hospice.hospiceID=orders.organization_id LIMIT 1),"") as hospice_name',FALSE); //newly added : remove if it will cause errors
                    $this->db->from('dme_order as orders');
                    $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
                    $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
                    $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left');
                    $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors

                    //$where = array("orders.medical_record_id"=>$id,"orders.uniqueID_reference"=>$uniqueID, "orders.activity_typeid"=>3);
                   
                    $this->db->where('orders.organization_id', $hospiceID); //07/13/2015
                   	$this->db->where("orders.medical_record_id", $id);
                   	$this->db->where("orders.activity_typeid", 3);
                   	$this->db->where("orders.order_status !=", "confirmed");
                   	$this->db->or_where("orders.uniqueID_reference", $uniqueID);
                    //$this->db->where($where);
                    //$this->db->where('equip.parentID', 0);
                    //$this->db->where('orders.pickup_order ', 0);
                    //$this->db->where('orders.uniqueID',$unique);
                    $this->db->group_by('orders.orderID');
                    $this->db->order_by('orders.activity_typeid', 'DESC');
                    //$this->db->order_by('orders.pickup_date', 'DESC');
                    //$this->db->order_by('equip.key_desc', 'ASC');
                    $data = $this->db->get()->result_array();
                    return $data;
        }



        public function get_orders_email($unique)

        {

                    $this->db->select('equip.*');

                    $this->db->select('orders.*');

                    $this->db->select('patients.*');

                    $this->db->select('COALESCE((SELECT key_desc FROM dme_equipment WHERE dme_equipment.equipmentID=equip.parentID LIMIT 1),"") as parent_name',FALSE);

                    $this->db->select('COALESCE((SELECT option_description FROM dme_options WHERE dme_options.optionID=equip.optionID LIMIT 1),"") as option_description',FALSE);

                    $this->db->select('COALESCE((SELECT type FROM dme_equip_category WHERE dme_equip_category.categoryID=equip.categoryID LIMIT 1),"") as type',FALSE);

                    $this->db->select('COALESCE((SELECT activity_name FROM dme_activity_type WHERE dme_activity_type.activity_id=orders.activity_typeid LIMIT 1),"") as activity_name',FALSE);

                    $this->db->from('dme_order as orders');

                    $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');

                    $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');

                     $this->db->join('dme_patient as patients', 'orders.medical_record_id = patients.medical_record_id', 'left');

                    if($unique != '')

                    {

                        $this->db->where('orders.uniqueID' , $unique);

                    }
                    //$this->db->where('equip.parentID', 0);
                    $this->db->group_by('orders.orderID');
                    $this->db->order_by('orders.date_ordered', 'DESC');
                    $this->db->order_by('equip.option_order', 'ASC');

                    $data = $this->db->get()->result_array();
                    return $data;

        }

        public function get_orders_info($unique)

        {

                    $this->db->select('equip.*');

                    $this->db->select('orders.*');

                    $this->db->select('patients.*');

                    $this->db->select('COALESCE((SELECT key_desc FROM dme_equipment WHERE dme_equipment.equipmentID=equip.parentID LIMIT 1),"") as parent_name',FALSE);

                    $this->db->select('COALESCE((SELECT option_description FROM dme_options WHERE dme_options.optionID=equip.optionID LIMIT 1),"") as option_description',FALSE);

                    $this->db->select('COALESCE((SELECT type FROM dme_equip_category WHERE dme_equip_category.categoryID=equip.categoryID LIMIT 1),"") as type',FALSE);

                    $this->db->select('COALESCE((SELECT activity_name FROM dme_activity_type WHERE dme_activity_type.activity_id=orders.activity_typeid LIMIT 1),"") as activity_name',FALSE);

                    $this->db->from('dme_order as orders');

                    $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');

                    $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');

                     $this->db->join('dme_patient as patients', 'orders.medical_record_id = patients.medical_record_id', 'left');

                    if($unique != '')

                    {

                        $this->db->where('orders.medical_record_id' , $unique);

                    }

                    $this->db->group_by('orders.medical_record_id');
                    $this->db->order_by('orders.date_ordered', 'DESC');

                    $this->db->order_by('equip.key_desc', 'ASC');

                    $data = $this->db->get()->result_array();

                    return $data;

        }



        public function save_pickup_data($data=array(),$unique_id)

        {

            $response = false;

            if(!empty($data))

            {

                $save = $this->db->insert('dme_pickup',$data);

                if($save)

                {

                    $update = array(

                                    "activity_typeid"   => 2,

                                    //"order_status"            => "confirmed"

                                );

                    $this->db->where('uniqueID',$unique_id);

                    $response = $this->db->update("dme_order",$update);

                }

            }

            return $response;

        }



  		public function get_pickup($uniqueID=0)

        {

            $this->db->where('orderUniqueID',$uniqueID);

            return $this->db->get('dme_pickup')->row_array();

        }



        public function delete_order($medical_record_id, $uniqueID)

        {

    		$this->db->where('medical_record_id', $medical_record_id);
    		$this->db->where('uniqueID', $uniqueID);

			$this->db->delete('dme_order'); 

        }



        public function save_to_trash($data)

        {

        	$this->db->insert('trash_table', $data);

			return $this->db->insert_id();

        }

        public function update_canceled_status($medical_id, $uniqueID, $array=array())
        {	
        	$this->db->where('medical_record_id', $medical_id);
        	$this->db->where('uniqueID', $uniqueID);
        	$this->db->update('dme_order', $array);
        }

        public function revert_pickedup_item_to_delivery($equipmentID,$medical_record_id,$array=array())
        {
        	$this->db->where('medical_record_id', $medical_record_id);
        	$this->db->where('equipmentID', $equipmentID);
        	$this->db->update('dme_order', $array);
        }


        public function get_canceled()
        {
        	return $this->db->get('trash_table')->result_array();
        }



        public function get_spec_trash($trashID)

        {

        	$this->db->select('*');

        	$this->db->from('trash_table');

        	$this->db->where('trash_id', $trashID);



        	$query = $this->db->get();



			return $query->result_array();



        }



        public function delete_from_trash($trashID)

        {

        	$this->db->where('trash_id', $trashID);

			$this->db->delete('trash_table'); 

        }

        public function delete_patient_records($patientID,$medical_record_id)
        {
        	$response = false;

        	if(!empty($medical_record_id))
        	{
        		$this->db->where('patientID', $patientID);
        		$this->db->where('medical_record_id', $medical_record_id);
				$response = $this->db->delete('dme_patient');
        	}

        	return $response;
        }



        public function joined_decoded_data()

        {

        	$this->db->select('orders.*');

			$this->db->select('pat.*');

			$this->db->from('dme_order as orders');

			$this->db->join('dme_patient as pat', 'pat.patientID = orders.patientID', 'left');

			

			$query = $this->db->get();



			return $query->result_array();

        }

		
        public function insert_order_comments($data)

        {

        	$this->db->insert('dme_order_comments', $data);

			return $this->db->insert_id();

        }



        public function get_all_comments($uniqueID)

        {
        	
        	$this->db->where('order_uniqueID', $uniqueID);

        	return $this->db->get('dme_order_comments')->result_array();

        }



        public function count_order_comments($uniqueID)

        {



        	$this->db->where('order_uniqueID', $uniqueID);

			$this->db->from('dme_order_comments');

			return $this->db->count_all_results();

        }

         public function count_patient_comments($medical_record_id)

        {

        	$this->db->where('medical_record_id', $medical_record_id);

			$this->db->from('dme_patient_notes');

			return $this->db->count_all_results();

        }

        public function show_pickup_orders($uniqueID='')
        {
        	$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
			$this->db->select('patient.*');
			$this->db->select('act.*');
			$this->db->select('hosp.*');
			$this->db->select('equipments.*');
			$this->db->select('cat.*');
			$this->db->select('pickups.*');

        	$this->db->from('dme_order AS orders');
        	$this->db->join('dme_user as  users','orders.ordered_by = users.userID','left');
        	$this->db->join('dme_patient as  patient','orders.patientID = patient.patientID','left');
        	$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
        	$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
        	$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');
			$this->db->join('dme_pickup as pickups','pickups.orderUniqueID = orders.uniqueID','left');

        	if($this->session->userdata('account_type') != 'dme_admin')

			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}

			if($uniqueID != "") 
			{
				$this->db->where('orders.uniqueID', $uniqueID);

			}
			else 
			{
				$this->db->group_by('orders.uniqueID');

			}
	
        	$this->db->where('orders.activity_typeid','2'); 
			$this->db->order_by('orders.date_ordered', 'DESC');
        	return $this->db->get()->result_array();
        }

       function change_order_status($medical_record_id, $uniqueID, $data)
       {
      	 	$this->db->where('medical_record_id', $medical_record_id);
      	 	$this->db->where('uniqueID', $uniqueID);
		    $this->db->update('dme_order', $data);
       }

       function move_enroute_orders($medical_record_ids, $unique_ids, $data=array())
       {
       		$this->db->where_in('medical_record_id', $medical_record_ids);
      	 	$this->db->where_in('uniqueID', $unique_ids);
		    return $this->db->update('dme_order', $data);
       }

       function update_order_summary($uniqueID, $equipmentID, $data='')
       {
       		$response = false;
			if(!empty($data))
			{
				$this->db->where('uniqueID', $uniqueID);
				$this->db->where('equipmentID', $equipmentID);
				$response = $this->db->update('dme_order', $data);
			}
			return $response;
       }


       function add_additional_order($data)
       {
       		$this->db->insert('dme_order', $data);
			return $this->db->insert_id();
       }


      	function add_lot_comment($data)
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$response = $this->db->insert('dme_lot_num_comments', $data);
      		}
      		return $response;
      	}

      	function get_comments($equipmentID, $uniqueID)
      	{
      		$this->db->select('lot_comments.*');
      		$this->db->select('orders.*');
      		$this->db->from('dme_lot_num_comments as lot_comments');
      		$this->db->join('dme_order as orders','lot_comments.equipmentID = orders.equipmentID','left');
      		
      		$where = array(
      			'orders.uniqueID' => $uniqueID,
      			'orders.equipmentID' => $equipmentID
      		);
      		$this->db->where($where);
      		$this->db->order_by('lot_comments.date_added','DESC');
      		$query = $this->db->get();

      		return $query->result_array();
      	}

      	function return_lot_info($equipmentID, $uniqueID)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_order');
      		$where = array(
      			'uniqueID' => $uniqueID,
      			'equipmentID' => $equipmentID
      		);
      		$this->db->where($where);

      		$query = $this->db->get();

      		return $query->result_array();
      	}

      	function get_patient_profile($medical_record_id, $hospice_id)
      	{
      		$this->db->select('orders.*');
      		$this->db->select('patients.*');
      		$this->db->select('hospices.*');
      		$this->db->from('dme_patient as patients');
      		$this->db->join('dme_order as orders','patients.patientID=orders.patientID','left');
      		$this->db->join('dme_hospice as hospices','orders.organization_id=hospices.hospiceID', 'left');
      		$this->db->where('orders.organization_id', $hospice_id);
      		$this->db->where('patients.medical_record_id', $medical_record_id);
      		$this->db->group_by('orders.medical_record_id');
      		$query = $this->db->get();

      		return $query->result_array();
      	}

      	function get_patient_profile_toedit($medical_record_id)
      	{
      		$this->db->select('orders.*');
      		$this->db->select('edits.*');
      		$this->db->select('hospices.*');
      		$this->db->from('dme_edited_patient_info as edits');
      		$this->db->join('dme_order as orders','edits.medical_record_id=orders.medical_record_id','left');
      		$this->db->join('dme_hospice as hospices','orders.organization_id=hospices.hospiceID', 'left');
      		$this->db->where('edits.medical_record_id', $medical_record_id);
      		$this->db->group_by('orders.medical_record_id');
      		$query = $this->db->get();

      		return $query->result_array();
      	}

      	function update_order_profile($medical_record_id, $data='')
      	{
      		$response = false; 

      		if(!empty($data))
      		{
      			$this->db->where('medical_record_id', $medical_record_id);
				$response = $this->db->update('dme_order', $data);
      		}
      		return $response;
      	}

      	function update_patient_profile($medical_record_id, $data='')
      	{
      		$response = false; 

      		if(!empty($data))
      		{
      			$this->db->where('medical_record_id', $medical_record_id);
				$response = $this->db->update('dme_patient', $data);
      		}
      		return $response;
      	}

      	function update_patient_profile_toedit($medical_record_id, $data='')
      	{
      		$response = false; 

      		if(!empty($data))
      		{
      			$this->db->where('medical_record_id', $medical_record_id);
				$response = $this->db->update('dme_edited_patient_info', $data);
      		}
      		return $response;
      	}

      	function insert_patient_note($data='')
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$response = $this->db->insert('dme_patient_notes', $data);
      		}

      		return $response;
      	}

      	function retrieve_patient_notes($medical_record_id, $patientID="")
      	{
      		$this->db->where('medical_record_id', $medical_record_id);
      		$this->db->where('patientID', $patientID);
      		$this->db->order_by('created_on','DESC');
      		return $this->db->get("dme_patient_notes")->result_array();
      	}

      	function get_equipment_options($equipmentID,$uniqueID)
      	{
      		$this->db->select("*");
      		$this->db->from("dme_order as orders");
      		$this->db->join("dme_equipment as equipments","orders.equipmentID=equipments.equipmentID","left");
      		$this->db->join("dme_options as options","equipments.optionID=options.optionID","left");
      		if($equipmentID == 181 || $equipmentID == 182)
			{
				$this->db->where("equipments.parentID !=", $equipmentID); //put back if it will cause problems
			}
			else
			{
				$this->db->where("equipments.parentID", $equipmentID); //put back if it will cause problems
			}
      		$this->db->where("orders.uniqueID",$uniqueID);

      		if($equipmentID == 11 || $equipmentID == 170  || $equipmentID == 306)
      		{
      			$this->db->where("equipments.categoryID",3);
      		}
      		else
      		{
      			$this->db->where("equipments.categoryID !=",3);
      		}
      		
      		$this->db->order_by("equipments.option_order","ASC");

      		return $this->db->get()->result_array();
      	}

      	function get_patient_weight($equipmentID, $uniqueID)
      	{
      		$this->db->select("*");
      		$this->db->from("dme_patient_weight");
      		$this->db->where("equipmentID", $equipmentID);
      		$this->db->where("ticket_uniqueID",$uniqueID);

      		return $this->db->get()->result_array();
      	}

      	function get_lot_number($equipmentID, $uniqueID)
      	{
      		$this->db->select("*");
      		$this->db->from("dme_lot_number");
      		$this->db->where("equipmentID", $equipmentID);
      		$this->db->where("ticket_uniqueID",$uniqueID);

      		return $this->db->get()->result_array();
      	}


      	function change_status_to_confirmed($medical_record_id,$uniqueID, $data='')
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$this->db->where('medical_record_id', $medical_record_id);
      			$this->db->where('uniqueID', $uniqueID);
      			$response = $this->db->update('dme_order', $data);      		
      		}

      		return $response;

      	}

      	function update_order_summary_fields($medical_id, $data=array())
      	{
      		$response = false;
      		
      		if(!empty($data))
      		{
      			foreach($data['order_summary'] as $f_key=>$first_val)
      			{
      				foreach($first_val as $key=>$value)
      				{
	      				if($value['act_name'] == "Delivery" || $value['act_name'] == "PT Move")
	      				{
	      					$value['pickedup_date'] = "";
	      					$new_pickedup_date = $value['pickedup_date'];
	      				}
	      				else
	      				{
	      					$new_pickedup_date = date("Y-m-d", strtotime($value['pickedup_date']));
	      				}

	  					$array = array(
	  						//'order_status' => "confirmed",
	  						'equipmentID' => $f_key,
	  						'activity_typeid' => $value['activity_typeid'],
							'uniqueID' => $value['uniqueID'],
							'pickup_date' => date("Y-m-d", strtotime($value['order_date'])),
							'item_num' => $value['item_num'],
							'equipment_value' => $value['qty'],
							'serial_num' => $value['serial_num'],
							'summary_pickup_date' => $new_pickedup_date,
						);


	  					$this->db->where('medical_record_id', $medical_id);
						

	  					if($value['activity_typeid'] != 5)
	  					{
	  						$this->db->where('activity_typeid', $value['activity_typeid']);
	  					}
	  					else
	  					{
	  						$this->db->where('activity_typeid', $value['activity_typeid']);
	  					}

	  					$this->db->where_in('uniqueID', $array['uniqueID']);
						$this->db->where_in('equipmentID', $array['equipmentID']);

						
						$response = $this->db->update('dme_order', $array);
					}
      			}
      		}
      		return $response;
      	}

      	function update_order_summary_confirm_fields($medical_id, $uniqueID, $data=array())
      	{
      		$response = false;
      		
     //  		if(!empty($data))
     //  		{
     //  			foreach($data['order_summary'] as $f_key=>$first_val)
     //  			{
     //  				foreach($first_val as $key=>$value)
     //  				{
	    //   				if($value['act_name'] == "Delivery")
	    //   				{
	    //   					$value['pickedup_date'] = "";
	    //   				}

	  		// 			$array = array(
	  		// 				'order_status' => "confirmed",
	  		// 				'equipmentID' => $f_key,
	  		// 				'activity_typeid' => $value['activity_typeid'],
					// 		'uniqueID' => $value['uniqueID'],
					// 		'pickup_date' => date($value['order_date']),
					// 		'item_num' => $value['item_num'],
					// 		'equipment_value' => $value['qty'],
					// 		'serial_num' => $value['serial_num'],
					// 		'summary_pickup_date' => date($value['pickedup_date'])
					// 	);

	  		// 			$this->db->where('medical_record_id', $medical_id);
						

	  		// 			if($value['activity_typeid'] != 5)
	  		// 			{
	  		// 				$this->db->where('activity_typeid', $value['activity_typeid']);
	  		// 			}

					// 	$this->db->where_in('equipmentID', $array['equipmentID']);
						
					// 	$response = $this->db->update('dme_order', $array);
					// }
     //  			}
     //  		}
      		if(!empty($data))
      		{
      			foreach($data['order_summary'] as $key=>$value)
      			{
      				
      				if($value['act_name'] == "Delivery" || $value['act_name'] == "PT Move" || $value['act_name'] == "Respite")
      				{
      					$value['pickedup_date'] = "";
      					$new_pickedup_date = $value['pickedup_date'];
      				}
      				else
      				{
      					$new_pickedup_date = date("Y-m-d", strtotime($value['pickedup_date']));
      				}

  					$array = array(
  						'order_status' => "confirmed",
  						'equipmentID' => $key,
						'pickedup_uniqueID' => $value['uniqueID'],
						'pickup_date' => date("Y-m-d", strtotime($value['order_date'])),
						'item_num' => $value['item_num'],
						'equipment_value' => $value['qty'],
						'serial_num' => $value['serial_num'],
						'summary_pickup_date' => $new_pickedup_date,
						'driver_name'	=> $value['driver_name'],
						'person_confirming_order' => $value['person_confirming_order']
					);
  					
  					if($value['activity_typeid'] != 2)
  					{
  						$this->db->where('uniqueID', $uniqueID); //comment if this will cause errors.
  						$this->db->where('driver_name',''); // remove if magka problem
  					}

  					//* Put this back if it will cause errors to other functions *
  					else
  					{
  						$this->db->where('uniqueID', $uniqueID); //comment if this will cause errors.
  						$this->db->or_where('pickedup_uniqueID', $uniqueID); //remove if this will cause errors.
  						// $this->db->or_where('uniqueID_reference', $uniqueID); //remove if this will cause errors.
  						//$this->db->or_where('original_activity_typeid', 5); //remove if this will cause errors.
  					}
  					/** END **/
  					
  					$this->db->where('medical_record_id', $medical_id);	
  					$this->db->where('summary_pickup_date', '0000-00-00');	//comment if this will cause errors.
					
					$this->db->where_in('equipmentID', $array['equipmentID']);

					$response = $this->db->update('dme_order', $array);
      			}
      		}
      		return $response;
      	}



      	function update_order_summary_confirm_fields_exchange($medical_id, $uniqueID, $data=array())
      	{
      		$response = false;
      		
      		if(!empty($data))
      		{
      			foreach($data['order_summary'] as $f_key=>$first_val)
      			{
      				foreach($first_val as $key=>$value)
      				{
	      				if($value['act_name'] == "Delivery" || $value['act_name'] == "PT Move" || $value['act_name'] == "Exchange")
	      				{
	      					$value['pickedup_date'] = "";
	      					$new_pickedup_date = $value['pickedup_date'];
	      				}
	      				else
	      				{
	      					$new_pickedup_date = date("Y-m-d", strtotime($value['pickedup_date']));
	      				}

	      				if(isset($new_pickedup_date))
	      				{
	      					$pickup_date = $new_pickedup_date;
	      				}
	      				else
	      				{
	      					$pickup_date = "";
	      				}

	  					$array = array(
	  						'order_status' => "confirmed",
	  						'equipmentID' => $f_key,
	  						'activity_typeid' => $value['activity_typeid'],
							'uniqueID' => $value['uniqueID'],
							'pickup_date' => date("Y-m-d", strtotime($value['order_date'])),
							'item_num' => $value['item_num'],
							'equipment_value' => $value['qty'],
							'serial_num' => $value['serial_num'],
							'summary_pickup_date' => $pickup_date,
							'driver_name'	=> $value['driver_name']
						);

	  					$this->db->where('medical_record_id', $medical_id);
						

	  					if($value['activity_typeid'] != 5)
	  					{
	  						$this->db->where('activity_typeid', $value['activity_typeid']);
	  						$this->db->where_in('uniqueID', $array['uniqueID']);
	  					}
	  					else
	  					{
	  						$this->db->where('activity_typeid', $value['activity_typeid']);
	  					}

	  					//$this->db->where('driver_name !=',''); // remove if magka problem
						$this->db->where_in('equipmentID', $array['equipmentID']);

						
						$response = $this->db->update('dme_order', $array);
					}
      			}
      		}
      		
      		return $response;
      	}



      	function get_equipments_ordered($medical_id, $unique_id, $act_id, $initial_order="")
      	{
      		$this->db->select('equip.*');
	        $this->db->select('orders.*');
	        $this->db->select('patients.*');
	        $this->db->select('hospices.*'); //newly added : remove if it will cause errors
	        $this->db->select('COALESCE((SELECT key_desc FROM dme_equipment WHERE dme_equipment.equipmentID=equip.parentID LIMIT 1),"") as parent_name',FALSE);
	        $this->db->select('COALESCE((SELECT option_description FROM dme_options WHERE dme_options.optionID=equip.optionID LIMIT 1),"") as option_description',FALSE);
	        $this->db->select('COALESCE((SELECT type FROM dme_equip_category WHERE dme_equip_category.categoryID=equip.categoryID LIMIT 1),"") as type',FALSE);
	        $this->db->select('COALESCE((SELECT activity_name FROM dme_activity_type WHERE dme_activity_type.activity_id=orders.activity_typeid LIMIT 1),"") as activity_name',FALSE);
	        $this->db->select('COALESCE((SELECT hospice_name FROM dme_hospice WHERE dme_hospice.hospiceID=orders.organization_id LIMIT 1),"") as hospice_name',FALSE); //newly added : remove if it will cause errors
	        $this->db->from('dme_order as orders');
	        $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
	        $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
	        $this->db->join('dme_patient as patients', 'orders.medical_record_id = patients.medical_record_id', 'left');
	        $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors
	
	
			$this->db->query('SET SQL_BIG_SELECTS=1');  //newly added 09/18/2015
	        if($medical_id != '')
	        {
	            $this->db->where('orders.medical_record_id' , $medical_id);
	        }

	        // if($initial_order == 1)
	        // {
	        // 	$this->db->where('orders.initial_order', 1);
	        // }
	        // else
	        // {
	        // 	$this->db->where('orders.activity_typeid', $act_id);
	        // }

	        $this->db->where('equip.parentID', 0);
	        //$this->db->where('orders.activity_typeid', $act_id);

	        if($act_id != 2)
	        {
	        	$this->db->where('orders.uniqueID', $unique_id); //remove this if it will cause errors
	        }
	        else
	        {
	        	$this->db->where('orders.uniqueID', $unique_id); 
	        	$this->db->or_where('orders.pickedup_uniqueID', $unique_id); //remove this if it will cause errors
	        	//$this->db->where('orders.original_activity_typeid', 2);
	        }
	        $this->db->where('orders.serial_num !=', "item_options_only"); 
	        //$this->db->group_by('orders.orderID');
	        $this->db->group_by('orders.equipmentID');
	        $this->db->order_by('orders.date_ordered', 'ASC');
	        //$this->db->order_by('equip.key_desc', 'ASC');
	        $data = $this->db->get()->result_array();
	     
	        return $data;
      	}


      	public function insert_to_status($data=array())
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$response = $this->db->insert('dme_order_status', $data);
      		}
      		return $response;
      	}

      	public function get_from_order_status($unique_id,$equip_id)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_order as orders');
      		$this->db->join('dme_order_status as stats','orders.uniqueID=stats.order_uniqueID');
      		//$this->db->where('orders.uniqueID',$unique_id);
      		$this->db->where('orders.equipmentID',$equip_id);
      		$this->db->where('orders.activity_typeid !=', 1);
      		$this->db->where('orders.activity_typeid !=', 2);
      		$this->db->group_by('stats.order_uniqueID');

      		$query = $this->db->get()->result_array();

      		return $query;
      	}

      	public function insert_ptmove_table($data=array())
      	{
      		$this->db->insert('dme_new_ptmove_address', $data);
      	}

      	public function get_item_liter_flow($uniqueID, $equipmentID)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_order as orders');
      		$this->db->join('dme_equipment as equipments','orders.equipmentID=equipments.equipmentID','left');
      		$this->db->where('equipments.parentID', $equipmentID);
      		$this->db->where('orders.uniqueID', $uniqueID);

      	
      		if($equipmentID == 61)
      		{
      			$this->db->where('equipments.equipmentID', 77);
      		}
      		if($equipmentID == 62)
      		{
      			$this->db->where('equipments.equipmentID', 188);
      		}
      		if($equipmentID == 174)
      		{
      			$this->db->where('equipments.equipmentID', 189);
      		}
      		if($equipmentID == 29)
      		{
      			$this->db->where('equipments.equipmentID', 100);
      		}
      		if($equipmentID == 30)
      		{
      			$this->db->where('equipments.equipmentID', 240);
      		}
      		if($equipmentID == 31)
      		{
      			$this->db->where('equipments.equipmentID', 190);
      		}
      		if($equipmentID == 176)
      		{
      			$this->db->where('equipments.equipmentID', 191);
      		}
      		if($equipmentID == 36)
      		{
      			$this->db->where('equipments.equipmentID', 201);
      		}

      		$this->db->group_by('orders.uniqueID');

      		$query = $this->db->get()->result_array();


      		return $query;
      	}

      	public function update_liter_flow_value($uniqueID, $equipmentID, $data=array())
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$this->db->where('uniqueID', $uniqueID);
      			$this->db->where('equipmentID', $equipmentID);
      			$response = $this->db->update('dme_order', $data);
      		}

      		return $response;

      	}

      	public function get_bipap_option($uniqueID, $equipmentID)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_order as orders');
      		$this->db->join('dme_equipment as equipments','orders.equipmentID=equipments.equipmentID','left');
      		$this->db->where('equipments.parentID', $equipmentID);
      		$this->db->where('orders.uniqueID', $uniqueID);

      		// $this->db->where('equipments.equipmentID', 109);
      		// $this->db->where('equipments.equipmentID', 110);
      		// $this->db->where('equipments.equipmentID', 111);

      		//$this->db->group_by('orders.uniqueID');
      		$query = $this->db->get()->result_array();

      		return $query;
      	}

      	public function update_bipap_option($uniqueID, $equipmentID, $data=array())
      	{
      		$response = false;
      		
      		if(!empty($data))
      		{
      			$this->db->where('uniqueID', $uniqueID);
      			$this->db->where_in('equipmentID', $equipmentID);
      			$response = $this->db->update('dme_order', $data);
      		}

      		return $response;

      	}


      	public function get_cpap_option($uniqueID, $equipmentID)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_order as orders');
      		$this->db->join('dme_equipment as equipments','orders.equipmentID=equipments.equipmentID','left');
      		$this->db->where('equipments.parentID', $equipmentID);
      		$this->db->where('orders.uniqueID', $uniqueID);

      		// $this->db->where('equipments.equipmentID', 109);
      		// $this->db->where('equipments.equipmentID', 110);
      		// $this->db->where('equipments.equipmentID', 111);

      		//$this->db->group_by('orders.uniqueID');
      		$query = $this->db->get()->result_array();

      		return $query;
      	}


      	public function update_cpap_option($uniqueID, $equipmentID, $data=array())
      	{
      		$response = false;
      		
      		if(!empty($data))
      		{
      			$this->db->where('uniqueID', $uniqueID);
      			$this->db->where('equipmentID', 114);
      			$response = $this->db->update('dme_order', $data);
      		}

      		return $response;

      	}

      	public function get_patient_weight_toedit($uniqueID, $equipmentID, $patientID)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_order as orders');
      		$this->db->join('dme_patient_weight as w','orders.uniqueID=w.ticket_uniqueID','left');
      		$this->db->where('w.equipmentID', $equipmentID);
      		$this->db->where('w.ticket_uniqueID', $uniqueID);
      		$this->db->where('w.patientID', $patientID);

      		$query = $this->db->get()->result_array();

      		return $query;
      	}


      	public function update_patient_weight($uniqueID, $weightID, $data=array())
      	{
      		$response = false;
      		
      		if(!empty($data))
      		{
      			$this->db->where('ticket_uniqueID', $uniqueID);
      			$this->db->where('weightID', $weightID);
      			$response = $this->db->update('dme_patient_weight', $data);
      		}

      		return $response;

      	}

      	public function put_patient_weight($data=array())
      	{
      		$response = false;
      		
      		if(!empty($data))
      		{
      			$response = $this->db->insert('dme_patient_weight', $data);
      		}

      		return $response;

      	}

      	

      	public function get_original_patient_info($patient_id)
      	{
      		$this->db->where('patientID', $patient_id);
      		$this->db->group_by('patientID');
      		$query = $this->db->get('dme_patient')->row_array();

      		return $query;

      	}

      	public function insert_to_patient_logs($array=array())
      	{
  			$response = $this->db->insert_batch('dme_edit_patient_info_log', $array);
      	}

      	public function get_patient_logs($patientID)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_edit_patient_info_log');
      		$this->db->where('patientID', $patientID);
      		$this->db->order_by('date_edited','DESC');

      		$query = $this->db->get()->result_array();

      		return $query;
      	}

      	public function search_items_tracking($search_value)
      	{
      		$where = "(orders.serial_num LIKE '%$search_value%')";
      		$this->db->select('*');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_patient as patient','orders.medical_record_id=patient.medical_record_id');
			$this->db->where($where);
			$this->db->where("serial_num !=","item_options_only");
			$this->db->where("serial_num !=","pickup_order_only");
			$this->db->group_by('orders.medical_record_id');

			$this->db->limit(5);
			$query = $this->db->get();

			return $query->result_array();
      	}

      	public function list_patients_with_serial_numbers($uniqueID="",$where=array())
      	{
      		/** To fix the undefined index when going to the other pages **/
			if(empty($where))
			{
				$where['serial'] = "";
			}
			else
			{
				$where['serial'] = $where['serial'];
			}
			//End

			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
			$this->db->select('patient.*');
			$this->db->select('act.*');
			$this->db->select('hosp.*');
			$this->db->select('equipments.*');
			$this->db->select('cat.*');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');
			$includes = array(1,2);

		
			if($uniqueID != "" && empty($where['serial'])) 
			{
				$this->db->where('orders.medical_record_id', $uniqueID);
				$this->db->where('orders.serial_num', $where['serial']);
			}

			if(!empty($where['serial']))
			{
				$this->db->where('orders.serial_num LIKE \'%'.$this->db->escape_str($where['serial']).'%\'', null, false);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('orders.organization_id');
			}
			else
			{
				$search_string = $where['serial'];
				$where = "(orders.serial_num LIKE '%$search_value%')";
				$this->db->where($where);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('orders.organization_id');
			}

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC'); 
			$query = $this->db->get();
		
			return $query->result_array();
      	}
		
		
		
		public function search_lot_number($search_value)
      	{
      		$where = "(lot.lot_number_content LIKE '%$search_value%')";
      		$this->db->select('*');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_patient as patient','orders.medical_record_id=patient.medical_record_id','left');
			$this->db->join('dme_lot_number as lot','orders.medical_record_id=lot.medical_record_num','left');
			$this->db->where($where);
			$this->db->where("serial_num !=","item_options_only");
			$this->db->where("serial_num !=","pickup_order_only");
			$this->db->group_by('orders.medical_record_id');

			$this->db->limit(5);
			$query = $this->db->get();

			return $query->result_array();
      	}



      	public function list_patients_with_lot_number($uniqueID="",$where=array())
      	{
      		/** To fix the undefined index when going to the other pages **/
			if(empty($where))
			{
				$where['lotNo'] = "";
			}
			else
			{
				$where['lotNo'] = $where['lotNo'];
			}
			//End

			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
			$this->db->select('patient.*');
			$this->db->select('act.*');
			$this->db->select('hosp.*');
			$this->db->select('equipments.*');
			$this->db->select('cat.*');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');
			$this->db->join('dme_lot_number as lot','orders.medical_record_id=lot.medical_record_num','left');
			$includes = array(1,2);

		
			if($uniqueID != "" && empty($where['lotNo'])) 
			{
				$this->db->where('orders.medical_record_id', $uniqueID);
				$this->db->where('lot.lot_number_content', $where['lotNo']);
			}

			if(!empty($where['lotNo']))
			{
				$this->db->where('lot.lot_number_content LIKE \'%'.$this->db->escape_str($where['lotNo']).'%\'', null, false);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('orders.organization_id');
			}
			else
			{
				$search_string = $where['lotNo'];
				$where = "(lot.lot_number_content LIKE '%$search_string%')";
				$this->db->where($where);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('orders.organization_id');
			}

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC'); 
			$query = $this->db->get();
		
			return $query->result_array();
      	}
		
		
		
		
	}

	