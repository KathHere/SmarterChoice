<?php

	Class order_model extends Ci_Model
	{

		public function __construct()
		{
			$this->db->query('SET SQL_BIG_SELECTS=1');
		}

		//** Function to auto suggest Patient Medical Record Numbers sa Order Form **//


		// function drop_table()
		// {
		// 	$this->load->dbforge();
		// 	$this->dbforge->drop_table('dme_patient_total_los_per_hospice');
		// }

       	function search_patients($search_value,$returncount = false)
		{

			$count = 0;


			$response = array();
			$this->db->select('*');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_patient as patient','orders.patientID=patient.patientID');
			//$this->db->where('orders.organization_id', $hospice_id);
			$where = "(orders.medical_record_id LIKE '%$search_value%' OR patient.p_fname LIKE '%$search_value%' OR patient.p_lname LIKE '%$search_value%')";
			if(count($newval)>1)
			{
				$where = "(orders.medical_record_id LIKE '%$search_value%' OR (patient.p_fname LIKE '%".trim($newval[1])."%' AND patient.p_lname LIKE '%".trim($newval[0])."%'))";
			}
			$this->db->where($where);
			// $this->db->or_like('orders.medical_record_id', $search_value, 'both');
			// $this->db->or_like('patient.p_fname', $search_value, 'both');
			// $this->db->or_like('patient.p_lname', $search_value, 'both');
			$this->db->group_by('orders.medical_record_id');
			if(!$returncount)
			{
				$this->db->limit(5);
			}
			$query = $this->db->get();
			if($returncount)
			{
				$count = $query->num_rows();
				return $count;
			}
			if(empty($search_value))
			{
				return $response;
			}

			return $query->result_array();
		}

		//search for all patients
		function search_all_patients($search_value,$returncount = false)
		{
			$newval = explode(",", $search_value);
			$count = 0;

			$response = array();
			$this->db->select('*');
			$this->db->from('dme_patient');

			$where = "(medical_record_id LIKE '%$search_value%' OR p_fname LIKE '%$search_value%' OR p_lname LIKE '%$search_value%')";
			if(count($newval)>1)
			{
				$where = "(medical_record_id LIKE '%$search_value%' OR (p_fname LIKE '%".trim($newval[1])."%' AND p_lname LIKE '%".trim($newval[0])."%'))";
			}
			$this->db->where($where);
			if(!$returncount)
			{
				$this->db->limit(5);
			}
			$query = $this->db->get();
			if($returncount)
			{
				$count = $query->num_rows();
				return $count;
			}
			if(empty($search_value))
			{
				return $response;
			}

			return $query->result_array();
		}

		//search for all patients with account location
		function search_all_patients_v2($account_location,$search_value,$returncount = false)
		{
			$newval = explode(",", $search_value);
			$count = 0;

			$response = array();
			$this->db->select('*');
			$this->db->from('dme_patient as patient');
			$this->db->join('dme_hospice AS hospice', 'patient.ordered_by = hospice.hospiceID','left');

			$where = "(patient.medical_record_id LIKE '%$search_value%' OR patient.p_fname LIKE '%$search_value%' OR patient.p_lname LIKE '%$search_value%')";
			if(count($newval)>1)
			{
				$where = "(patient.medical_record_id LIKE '%$search_value%' OR (patient.p_fname LIKE '%".trim($newval[1])."%' AND patient.p_lname LIKE '%".trim($newval[0])."%'))";
			}
			$this->db->where($where);
			$this->db->where('hospice.account_location',$account_location);

			if(!$returncount)
			{
				$this->db->limit(5);
			}
			$query = $this->db->get();
			if($returncount)
			{
				$count = $query->num_rows();
				return $count;
			}
			if(empty($search_value))
			{
				return $response;
			}

			return $query->result_array();
		}

		function search_patients_hospice($search_value, $createdby,$returncount = false)
		{
			$newval = explode(",", $search_value);
			$response = array();
			$count = 0;

			$this->db->select('*');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_patient as patient','orders.patientID=patient.patientID');
			$where = "(orders.medical_record_id LIKE '%$search_value%' OR patient.p_fname LIKE '%$search_value%' OR patient.p_lname LIKE '%$search_value%')";
			if(count($newval)>1)
			{
				$where = "(orders.medical_record_id LIKE '%$search_value%' OR (patient.p_fname LIKE '%".trim($newval[1])."%' AND patient.p_lname LIKE '%".trim($newval[0])."%'))";
			}

			$this->db->where('patient.ordered_by', $createdby);
			$this->db->where($where);
			$this->db->group_by('orders.medical_record_id');

			if(!$returncount)
			{
				$this->db->limit(5);
			}
			$query = $this->db->get();

			if($returncount)
			{
				$count = $query->num_rows();
				return $count;
			}
			if(empty($search_value))
			{
				return $response;
			}
			return $query->result_array();
		}

		//search all by hospice
		function search_all_patients_hospice($search_value, $createdby,$returncount = false)
		{
			$newval = explode(",", $search_value);
			$response = array();
			$count = 0;

			$this->db->select('*');
			$this->db->from('dme_patient as patient');
			$where = "(medical_record_id LIKE '%$search_value%' OR p_fname LIKE '%$search_value%' OR p_lname LIKE '%$search_value%')";
			if(count($newval)>1)
			{
				$where = "(medical_record_id LIKE '%$search_value%' OR (p_fname LIKE '%".trim($newval[1])."%' AND p_lname LIKE '%".trim($newval[0])."%'))";
			}

			$this->db->where('ordered_by', $createdby);
			$this->db->where($where);
			$this->db->group_by('medical_record_id');

			if(!$returncount)
			{
				$this->db->limit(5);
			}
			$query = $this->db->get();

			if($returncount)
			{
				$count = $query->num_rows();
				return $count;
			}
			if(empty($search_value))
			{
				return $response;
			}
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

		function checked_unique_id($uniqueID)
		{
			$this->db->select('orderID');
			$this->db->from('dme_order');
			$this->db->where('uniqueID', $uniqueID);
			$query = $this->db->get();

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

		function get_order_on_order_status($uniqueID)
		{
			$this->db->select('*');
			$this->db->from('dme_order_status');
			$this->db->where('order_uniqueID', $uniqueID);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_all_patients()
		{
			$this->db->select('patientID,date_created');
			$this->db->from('dme_patient');
			$this->db->order_by('cast(ordered_by as SIGNED)', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		function get_all_patients_v2($start=0,$limit=0)
		{
			$this->db->select('patientID,date_created');
			$this->db->from('dme_patient');

			if($limit!=-1)
		    {
		        $this->db->limit($limit,$start);
		    }

			$this->db->order_by('cast(ordered_by as SIGNED)', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		function get_all_total_patient_los_per_hospice($current_date)
		{
			$this->db->select('SUM(patient_total_los) as patient_total_los');
			$this->db->from('dme_patient_total_los_per_hospice');

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('hospiceID', $this->session->userdata('group_id'));
			} else {
				$this->db->where('hospiceID !=', 13);
			}

			$this->db->where('date_saved',$current_date);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_all_total_patient_los_per_hospice_v2($current_date,$account_location)
		{
			$this->db->select('SUM(patient_total_los) as patient_total_los');
			$this->db->from('dme_patient_total_los_per_hospice as total_los');
			$this->db->join('dme_hospice AS hospice', 'total_los.hospiceID = hospice.hospiceID','left');

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('total_los.hospiceID', $this->session->userdata('group_id'));
			} else {
				$this->db->where('total_los.hospiceID !=', 13);
			}

			$this->db->where('total_los.date_saved',$current_date);
			$this->db->where('hospice.account_location',$account_location);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_all_total_patient_days_per_hospice($current_date)
		{
			$this->db->select('SUM(total_patient_days) as total_patient_days');
			$this->db->from('dme_total_patient_days_per_hospice');

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('hospiceID', $this->session->userdata('group_id'));
			} else {
				$this->db->where('hospiceID !=', 13);
			}

			$this->db->where('date_saved',$current_date);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_all_total_patient_days_per_hospice_v2($current_date,$account_location)
		{
			$this->db->select('SUM(total_patient_days) as total_patient_days');
			$this->db->from('dme_total_patient_days_per_hospice as patient_days');
			$this->db->join('dme_hospice AS hospice', 'patient_days.hospiceID = hospice.hospiceID','left');

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('patient_days.hospiceID', $this->session->userdata('group_id'));
			} else {
				$this->db->where('patient_days.hospiceID !=', 13);
			}

			$this->db->where('patient_days.date_saved',$current_date);
			$this->db->where('hospice.account_location',$account_location);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_all_total_patient_los_specific_hospice($current_date,$hospiceID)
		{
			$this->db->select('patient_total_los');
			$this->db->from('dme_patient_total_los_per_hospice');
			$this->db->where('date_saved',$current_date);
			$this->db->where('hospiceID',$hospiceID);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_all_total_patient_days_specific_hospice($current_date,$hospiceID)
		{
			$this->db->select('total_patient_days');
			$this->db->from('dme_total_patient_days_per_hospice');
			$this->db->where('date_saved',$current_date);
			$this->db->where('hospiceID',$hospiceID);
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_total_patient_los_per_hospice()
		{
			$this->db->select('patient.length_of_stay as total_patient_los, count(DISTINCT patient.patientID) as sample , count(*) as sample_2,patient.ordered_by,patient.patientID');
			$this->db->from('dme_order AS orders');

		    $this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
		    $this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
		    $this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
		    $this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
		    $this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
		    $this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

		    $includes = array(1,2);

	        /** Newly Added **/
	        $this->db->where_not_in('orders.activity_typeid', 2);
	        $this->db->where('orders.canceled_order !=' , 1);
	        $this->db->where('orders.serial_num !=', "item_options_only");
	        $this->db->where('orders.serial_num !=', "pickup_order_only");
	        $this->db->where('equipments.categoryID !=', 3);
      		/** End **/

      		/** Russel added codes. This is for filtering inactive patients. **/
        	$this->db->where('orders.pickup_order ', 0);
            $this->db->where('equipments.categoryID !=', 3);
            $this->db->where('orders.serial_num !=','item_options_only');
            $this->db->where('orders.canceled_from_confirming !=', 1);
            $this->db->where('orders.canceled_order !=', 1);
            /** End **/

			$this->db->group_by("patient.patientID");
			$this->db->order_by('cast(patient.ordered_by as SIGNED)', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		function get_total_patient_days_per_hospice()
		{
			$this->db->select('patient.patient_days as total_patient_days, count(DISTINCT patient.patientID) as sample , count(*) as sample_2,patient.ordered_by,patient.patientID');
			$this->db->from('dme_order AS orders');

		    $this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
		    $this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
		    $this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
		    $this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
		    $this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
		    $this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

		    $includes = array(1,2);

	        /** Newly Added **/
	        $this->db->where_not_in('orders.activity_typeid', 2);
	        $this->db->where('orders.canceled_order !=' , 1);
	        $this->db->where('orders.serial_num !=', "item_options_only");
	        $this->db->where('orders.serial_num !=', "pickup_order_only");
	        $this->db->where('equipments.categoryID !=', 3);
      		/** End **/

      		/** Russel added codes. This is for filtering inactive patients. **/
        	$this->db->where('orders.pickup_order ', 0);
            $this->db->where('equipments.categoryID !=', 3);
            $this->db->where('orders.serial_num !=','item_options_only');
            $this->db->where('orders.canceled_from_confirming !=', 1);
            $this->db->where('orders.canceled_order !=', 1);
            /** End **/

			$this->db->group_by("patient.patientID");
			$this->db->order_by('cast(patient.ordered_by as SIGNED)', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		function get_total_patient_los_per_hospice_sample()
		{
			$this->db->select('(SUM(patient.length_of_stay)*count(DISTINCT patient.patientID)/count(*)) as total_patient_los, count(DISTINCT patient.patientID) as sample , count(*) as sample_2,patient.ordered_by');
			$this->db->from('dme_order AS orders');

		    $this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
		    $this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
		    $this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
		    $this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
		    $this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
		    $this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

		    $includes = array(1,2);

	        /** Newly Added **/
	        $this->db->where_not_in('orders.activity_typeid', 2);
	        $this->db->where('orders.canceled_order !=' , 1);
	        $this->db->where('orders.serial_num !=', "item_options_only");
	        $this->db->where('orders.serial_num !=', "pickup_order_only");
	        $this->db->where('equipments.categoryID !=', 3);
      		/** End **/

      		/** Russel added codes. This is for filtering inactive patients. **/
        	$this->db->where('orders.pickup_order ', 0);
            $this->db->where('equipments.categoryID !=', 3);
            $this->db->where('orders.serial_num !=','item_options_only');
            $this->db->where('orders.canceled_from_confirming !=', 1);
            $this->db->where('orders.canceled_order !=', 1);
            /** End **/

			$this->db->group_by("patient.ordered_by");
			$this->db->order_by('cast(patient.ordered_by as SIGNED)', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		function get_total_patient_los_per_hospice_updated($hospiceID)
		{
			$this->db->select('SUM(length_of_stay) as total_patient_los, ordered_by');
			$this->db->from('dme_patient');
			$this->db->where('ordered_by',$hospiceID);
			$this->db->order_by('cast(ordered_by as SIGNED)', 'ASC');
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function get_total_patient_days_per_hospice_updated($hospiceID)
		{
			$this->db->select('SUM(patient_days) as total_patient_days, ordered_by');
			$this->db->from('dme_patient');
			$this->db->where('ordered_by',$hospiceID);
			$this->db->order_by('cast(ordered_by as SIGNED)', 'ASC');
			$query = $this->db->get();

			return $query->first_row('array');
		}

		function insert_patient_los_per_hospice($data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$response = $this->db->insert_batch('dme_patient_total_los_per_hospice',$data);
			}

			return $response;
		}

		function insert_patient_days_per_hospice($data=array())
		{
			$response = false;

			if(!empty($data))
			{
				$response = $this->db->insert_batch('dme_total_patient_days_per_hospice',$data);
			}

			return $response;
		}

		function update_patient_los($data)
		{
			$this->db->update_batch("dme_patient",$data,'patientID');
		}

		function insert_reschreschedule_onhold_date($data)
		{
			$this->db->insert('dme_reschedule_onhold_dates', $data);

			return $this->db->insert_id();
		}

		function insert_oxygen_concentrator_follow_up($data)
		{
			$this->db->insert('dme_oxygen_concentrator_follow_up', $data);

			return $this->db->insert_id();
		}

		function get_o2_concentrator_follow_up_date($follow_up_id)
		{
			$this->db->select('follow_up.*');
			$this->db->from('dme_oxygen_concentrator_follow_up as follow_up');
			$this->db->where('follow_up.follow_up_id', $follow_up_id);
			$query = $this->db->get()->first_row('array');

			return $query;
		}

		function update_oxygen_concentrator_follow_up($follow_up_id,$data)
		{
			$this->db->where('follow_up_id', $follow_up_id);
			$response = $this->db->update('dme_oxygen_concentrator_follow_up', $data);

			return $response;
		}

		function get_existing_follow_up_date($patientID,$equipmentID,$uniqueID="")
		{
			$this->db->select('follow_up.*');
			$this->db->from('dme_oxygen_concentrator_follow_up as follow_up');
			$this->db->where('follow_up.patientID', $patientID);
			$this->db->where('follow_up.equipmentID', $equipmentID);

			if(!empty($uniqueID) && $uniqueID != 0)
			{
				$this->db->where('follow_up.uniqueID', $uniqueID);
			}

			$query = $this->db->get()->first_row('array');

			return $query;
		}

		function insert_assigned_item($data)
		{
			$this->db->insert('dme_assigned_equipment', $data);

			return $this->db->insert_id();
		}

		function save_changes_order_pickup_date($data,$patientID)
		{
			$this->db->where('patientID', $patientID);
			$response = $this->db->update('dme_order', $data);

			return $response;
		}

		function update_patient_los_per_patient($data,$patientID)
		{
			$this->db->where('patientID', $patientID);
			$response = $this->db->update('dme_patient', $data);

			return $response;
		}

		function update_patient_days_per_patient($data,$patientID)
		{
			$this->db->where('patientID', $patientID);
			$response = $this->db->update('dme_patient', $data);

			return $response;
		}

		function update_patient_los_for_hospice($data,$hospiceID,$current_date)
		{
			$this->db->where('hospiceID', $hospiceID);
			$this->db->where('date_saved', $current_date);
			$response = $this->db->update('dme_patient_total_los_per_hospice', $data);

			return $response;
		}

		function update_patient_days_for_hospice($data,$hospiceID,$current_date)
		{
			$this->db->where('hospiceID', $hospiceID);
			$this->db->where('date_saved', $current_date);
			$response = $this->db->update('dme_total_patient_days_per_hospice', $data);

			return $response;
		}

		function save_changes_order_pickup_date_2($data,$patientID)
		{
			$this->db->where('patientID', $patientID);
			$response = $this->db->update('dme_pickup_tbl', $data);

			return $response;
		}

		function change_reschreschedule_onhold_date($data,$statusID)
		{
			$this->db->where('statusID', $statusID);
			$response = $this->db->update('dme_reschedule_onhold_dates', $data);

			return $response;
		}

		function save_changes_patient_entry_date($data,$patientID)
		{
			$this->db->where('patientID', $patientID);
			$response = $this->db->update('dme_patient', $data);

			return $response;
		}

		function delete_reschreschedule_onhold_date($statusID)
		{
			$this->db->where('statusID', $statusID);
			$this->db->delete('dme_reschedule_onhold_dates');
		}

		//** End **//

		//patient info with no order by hospice and ID
		public function get_patient_noorder_info($uniqueID,$hospiceID)
		{
			$this->db->select('patient.*');
			$this->db->select('hosp.*');
			$this->db->from('dme_patient as patient');
			$this->db->join('dme_hospice as hosp' , 'patient.ordered_by = hosp.hospiceID', 'left');
			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('patient.ordered_by', $this->session->userdata('group_id'));
			}
			$this->db->where('patient.medical_record_id', $uniqueID);
			$this->db->where('patient.ordered_by', $hospiceID);
			$query = $this->db->get()->result_array();

			return $query;
		}

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

		function save_oxygen_order($data)
		{
			$response = false;

			if(!empty($data))
			{
				$response = $this->db->insert('dme_order',$data);
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
		| @this is created due to CUS MOVE fixes
		|   it creates a confirmed after creating a pickups for a CUS Moves
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
		function updateorder($patientID="", $updating_data, $equipment_ids="", $unique_id="", $activity_type_todo="", $ptmove_old_uniqueID="",$oxygen_orderID="")
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
				//remove this one para mugana sad ang CUS Move nga part.

				if($oxygen_orderID != "")
				{
					$this->db->where('orderID', $oxygen_orderID);
					$this->db->where('equipmentID', $equipment_ids);
				}
				else
				{
					$this->db->where_in('equipmentID', $equipment_ids);
				}

				$response = $this->db->update('dme_order', $updating_data);
			}

			return $response;
		}
		//$this->db->where('original_activity_typeid', 1);

		// update status of the order base on activity type
		function updateorder_pickup($patientID="", $updating_data, $equipment_ids="", $unique_id="", $activity_type_todo="", $ptmove_old_uniqueID="")
		{
			$response = false;

			if(!empty($equipment_ids))
			{
				if($unique_id != '')
				{

					$this->db->where_in('uniqueID', $unique_id);
				}

				$this->db->where('patientID', $patientID);
				$this->db->where('uniqueID_reference', 0);
				$this->db->where('activity_reference !=', 2);
				$this->db->where('canceled_order !=', 1);
				$this->db->where('order_status !=', "cancel");

				if($activity_type_todo == 3)
				{
					$this->db->where_in('uniqueID', $unique_id); //newly added - please remove if it will cause errors
					//$this->db->or_where('activity_reference', 1);
				}

				if($ptmove_old_uniqueID != "")
				{
					$this->db->where_in('uniqueID', $ptmove_old_uniqueID);
				}

				//kani nga line kay para sa exchange nga part of fixes
				//remove this one para mugana sad ang CUS Move nga part.

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

		public function list_active_patients_specific_date($date,$passed_hospiceID)
		{
			$this->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			if($passed_hospiceID != 0)
			{
				$this->db->where('orders.organization_id', $passed_hospiceID);
			}
			else
			{
				if($this->session->userdata('account_type') != 'dme_admin')
				{
					$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
				}
				else
				{
					$this->db->where('orders.organization_id !=', 13);
				}
			}

			$this->db->where('orders.actual_order_date <=', $date);

			//remove this two lines if it will cause errors -- newly added
			$this->db->where_not_in('orders.activity_typeid', 2);
			$this->db->where('orders.serial_num !=', "item_options_only");
			$this->db->where('orders.serial_num !=', "pickup_order_only");
			$this->db->where('equipments.parentID', 0);
			$this->db->where('orders.canceled_order !=', 1);
			$this->db->where('equipments.categoryID !=', 3);
			$this->db->where('orders.pickup_order ', 0);
		    $this->db->where('orders.canceled_from_confirming !=', 1);

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		public function list_active_patients_specific_date_v2($date,$passed_hospiceID,$account_location)
		{
			$this->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			if($passed_hospiceID != 0)
			{
				$this->db->where('orders.organization_id', $passed_hospiceID);
			}
			else
			{
				if($this->session->userdata('account_type') != 'dme_admin')
				{
					$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
				}
				else
				{
					$this->db->where('orders.organization_id !=', 13);
				}
			}

			$this->db->where('orders.actual_order_date <=', $date);

			//remove this two lines if it will cause errors -- newly added
			$this->db->where_not_in('orders.activity_typeid', 2);
			$this->db->where('orders.serial_num !=', "item_options_only");
			$this->db->where('orders.serial_num !=', "pickup_order_only");
			$this->db->where('equipments.parentID', 0);
			$this->db->where('orders.canceled_order !=', 1);
			$this->db->where('equipments.categoryID !=', 3);
			$this->db->where('orders.pickup_order ', 0);
			$this->db->where('hosp.account_location', $account_location);
		    $this->db->where('orders.canceled_from_confirming !=', 1);

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		//active patients
		public function list_active_patients()
		{
			$this->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			//$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$includes = array(1,2);

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			} else {
				$this->db->where('orders.organization_id !=', 13);
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
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		//active patients
		public function list_active_patients_v3($account_location)
		{
			$this->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			//$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$includes = array(1,2);

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			} else {
				$this->db->where('orders.organization_id !=', 13);
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
		    $this->db->where('hosp.account_location', $account_location);

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		//check if patient is active
		public function active_patient_check($patientID)
		{
			$this->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			//$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$includes = array(1,2);

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			} else {
				$this->db->where('orders.organization_id !=', 13);
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
		    $this->db->where('patient.patientID', $patientID);

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		//active patients by hospice
		public function list_active_patients_v2($passed_hospiceID)
		{
			$current_day = date('Y-m-d');

			$this->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			if($passed_hospiceID != 0)
			{
				$this->db->where('orders.organization_id', $passed_hospiceID);
			}
			else
			{
				if($this->session->userdata('account_type') != 'dme_admin')
				{
					$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
				}
				else
				{
					$this->db->where('orders.organization_id !=', 13);
				}
			}

			//remove this two lines if it will cause errors -- newly added
			$this->db->where_not_in('orders.activity_typeid', 2);
			$this->db->where('orders.serial_num !=', "item_options_only");
			$this->db->where('orders.serial_num !=', "pickup_order_only");
			$this->db->where('equipments.parentID', 0);
			$this->db->where('orders.canceled_order !=', 1);
			$this->db->where('equipments.categoryID !=', 3);
			$this->db->where('orders.pickup_order ', 0);
		    $this->db->where('orders.canceled_from_confirming !=', 1);
		    $this->db->where('orders.date_ordered <=', $current_day);
		    $this->db->where_not_in('orders.order_status', "cancel");

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		//active patients by hospice with account location
		public function list_active_patients_v4($passed_hospiceID,$account_location)
		{
			$current_day = date('Y-m-d');

			$this->db->select('patient.*, orders.organization_id, orders.medical_record_id, orders.deliver_to_type');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			if($passed_hospiceID != 0)
			{
				$this->db->where('orders.organization_id', $passed_hospiceID);
			}
			else
			{
				if($this->session->userdata('account_type') != 'dme_admin')
				{
					$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
				}
				else
				{
					$this->db->where('orders.organization_id !=', 13);
				}
			}

			//remove this two lines if it will cause errors -- newly added
			$this->db->where_not_in('orders.activity_typeid', 2);
			$this->db->where('orders.serial_num !=', "item_options_only");
			$this->db->where('orders.serial_num !=', "pickup_order_only");
			$this->db->where('equipments.parentID', 0);
			$this->db->where('orders.canceled_order !=', 1);
			$this->db->where('equipments.categoryID !=', 3);
			$this->db->where('orders.pickup_order ', 0);
		    $this->db->where('orders.canceled_from_confirming !=', 1);
		    $this->db->where('orders.date_ordered <=', $current_day);
		    $this->db->where('hosp.account_location', $account_location);
		    $this->db->where_not_in('orders.order_status', "cancel");

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		//active patients
		public function list_active_patients_scripts()
		{
			$this->db->select('patient.patientID, orders.organization_id, orders.medical_record_id');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			//$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$includes = array(1,2);
			$this->db->where('orders.organization_id !=', 13);

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

		//active patients
		public function list_active_patients_test()
		{
			$this->db->select('patient.*, orders.organization_id, orders.medical_record_id');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			//$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$includes = array(1,2);

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
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

		//active patients
		public function list_active_patients_new($hospiceID)
		{
			$this->db->select('patient.*, orders.organization_id, orders.medical_record_id');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			//$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$includes = array(1,2);

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			} else {
				$this->db->where('orders.organization_id !=', 13);
			}

			if($hospiceID != "all")
			{
				$this->db->where('orders.organization_id', $hospiceID);
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

		//active patients
		public function list_active_patients_new_v2($hospiceID,$account_location)
		{
			$this->db->select('patient.*, orders.organization_id, orders.medical_record_id');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			//$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$includes = array(1,2);

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			} else {
				$this->db->where('orders.organization_id !=', 13);
			}

			if($hospiceID != "all")
			{
				$this->db->where('orders.organization_id', $hospiceID);
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
		    $this->db->where('hosp.account_location', $account_location);

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			//$this->db->where('orders.order_status !=', 'confirmed');
			//$this->db->order_by('orders.date_ordered', 'ASC');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();

		}

		//patient witout orders
		public function list_of_noorder()
		{
			$this->db->select('patient.*');
			$this->db->from('dme_patient AS patient');
			$this->db->join('dme_order AS orders', 'orders.patientID = patient.patientID','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('patient.ordered_by', $this->session->userdata('group_id'));
			}
			$this->db->where('orders.patientID', NULL);
			$this->db->where('patient.ordered_by !=', 13);

			$this->db->order_by('patient.p_lname', 'asc');
			$this->db->group_by('patient.patientID');
			$query = $this->db->get();

			return $query->result_array();
		}

		//patient witout orders
		public function list_of_noorder_v2()
		{
			$this->db->select('patient.medical_record_id,patient.ordered_by,patient.p_fname,patient.p_lname');
			$this->db->from('dme_patient AS patient');
			$this->db->join('dme_order AS orders', 'orders.patientID = patient.patientID','left');

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('patient.ordered_by', $this->session->userdata('group_id'));
			}
			$this->db->where('orders.patientID', NULL);
			$this->db->where('patient.ordered_by !=', 13);

			$this->db->order_by('patient.p_lname', 'asc');
			$this->db->group_by('patient.patientID');
			$query = $this->db->get();

			return $query->result_array();
		}

		//patient witout orders
		public function list_of_noorder_v3($account_location)
		{
			$this->db->select('patient.medical_record_id,patient.ordered_by,patient.p_fname,patient.p_lname');
			$this->db->from('dme_hospice AS hospice');
			$this->db->join('dme_patient AS patient', 'patient.ordered_by = hospice.hospiceID','left');

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('patient.ordered_by', $this->session->userdata('group_id'));
			}
			$this->db->where('hospice.account_location', $account_location);
			$this->db->where('patient.ordered_by !=', 13);
			$this->db->where('patient.patientID NOT IN (SELECT patientID FROM dme_order_status)',NULL,FALSE);

			$this->db->order_by('patient.p_lname', 'asc');
			$this->db->group_by('patient.patientID');
			$query = $this->db->get();

			return $query->result_array();
		}

		//patient witout orders by hospice
		public function list_of_noorder_byhospice($hospiceID)
		{
			$this->db->order_by('patient.p_lname', 'asc');
			$this->db->distinct('patient.patientID');
			$this->db->select('patient.*');
			$this->db->from('dme_patient AS patient');
			$this->db->join('dme_order_status AS orders', 'orders.patientID = patient.patientID','left');

			$this->db->where('orders.patientID', NULL);
			$this->db->where('patient.ordered_by', $hospiceID);
			$this->db->group_by('patient.patientID');
			$query = $this->db->get();

			return $query->result_array();
		}

		//patient witout orders by hospice
		public function list_of_noorder_byhospice_v2($hospiceID)
		{
			$this->db->select('patient.medical_record_id,patient.ordered_by,patient.p_fname,patient.p_lname');
			$this->db->from('dme_patient AS patient');
			$this->db->join('dme_order_status AS orders', 'orders.patientID = patient.patientID','left');

			$this->db->where('orders.patientID', NULL);
			$this->db->where('patient.ordered_by', $hospiceID);

			$this->db->order_by('patient.p_lname', 'ASC');
			$this->db->group_by('patient.patientID');
			$query = $this->db->get();

			return $query->result_array();
		}

		//patient witout orders by hospice
		public function list_of_noorder_byhospice_v3($hospiceID,$account_location)
		{
			$this->db->select('patient.medical_record_id,patient.ordered_by,patient.p_fname,patient.p_lname');
			$this->db->from('dme_hospice AS hospice');
			$this->db->join('dme_patient AS patient', 'patient.ordered_by = hospice.hospiceID','left');

			$this->db->where('patient.ordered_by', $hospiceID);
			$this->db->where('hospice.account_location', $account_location);
			$this->db->where('patient.patientID NOT IN (SELECT patientID FROM dme_order_status)',NULL,FALSE);

			$this->db->order_by('patient.p_lname', 'ASC');
			$this->db->group_by('patient.patientID');
			$query = $this->db->get();

			return $query->result_array();
		}

		public function check_saved_weight($medical_id,$patientID,$unique_id,$equipmentID)
		{
			$this->db->select('*');
			$this->db->from('dme_patient_weight AS patient_weight');
			$this->db->where('patient_weight.medical_record_num',$medical_id);
			$this->db->where('patient_weight.patientID',$patientID);
			$this->db->where('patient_weight.ticket_uniqueID',$unique_id);
			$this->db->where('patient_weight.equipmentID',$equipmentID);
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

		//returns the searched user v2
		function list_orders_for_patient_searchv2($uniqueID="",$where=array())
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

			$this->db->from('dme_patient AS patient');

			$this->db->join('dme_order_status AS orders', 'orders.patientID = patient.patientID','left');

			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');

			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');

			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');

			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			$includes = array(1,2);


			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('patient.ordered_by', $this->session->userdata('group_id'));
			}

			if($uniqueID != "" && empty($where['term']) && empty($where['query']) && empty($where['param']) && empty($where['param2']))
			{

				$this->db->where('patient.medical_record_id', $uniqueID);
			}

			if(!empty($where['term']) && !empty($where['query']) && !empty($where['param']) && !empty($where['param2']))
			{
				$this->db->where('patient.medical_record_id LIKE \'%'.$this->db->escape_str($where['param2']).'%\'', null, false);
				$this->db->where('concat(patient.p_lname) LIKE \'%'.$this->db->escape_str($where['param']).'%\'', null, false);
				$this->db->where('concat(patient.p_fname) LIKE \'%'.$this->db->escape_str($where['query']).'%\'', null, false);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('patient.ordered_by');
			}
			else
			{
				$search_string = $where['term'];
				$where = "(patient.medical_record_id LIKE '%$search_string%' OR patient.p_fname LIKE '%$search_string%' OR patient.p_lname LIKE '%$search_string%')";
				$this->db->where($where);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('patient.ordered_by');
			}
			//remove this two lines if it will cause errors -- newly added
			// $this->db->where_not_in('orders.activity_typeid', 2);
			// $this->db->where('orders.serial_num !=', "item_options_only");
			// $this->db->where('orders.serial_num !=', "item_options_only");
			// $this->db->where('equipments.categoryID !=', 3);

			//remove this two lines if it will cause errors -- newly added

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('patient.ordered_by');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		//returns the searched user v2
		function list_orders_for_patient_searchv3($account_location,$uniqueID="",$where=array())
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

			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.order_uniqueID),0) as comment_count',FALSE);

			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);

			$this->db->select('patient.*');

			$this->db->select('act.*');

			$this->db->select('hosp.*');

		//	$this->db->select('equipments.*');

			//$this->db->select('cat.*');

			$this->db->from('dme_patient AS patient');

			$this->db->join('dme_order_status AS orders', 'orders.patientID = patient.patientID','left');

			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.status_activity_typeid','left');

			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');

			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');

		//	$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

	//		$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			$includes = array(1,2);


			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('patient.ordered_by', $this->session->userdata('group_id'));
			}

			if($uniqueID != "" && empty($where['term']) && empty($where['query']) && empty($where['param']) && empty($where['param2']))
			{

				$this->db->where('patient.medical_record_id', $uniqueID);
			}

			if(!empty($where['term']) && !empty($where['query']) && !empty($where['param']) && !empty($where['param2']))
			{
				$this->db->where('patient.medical_record_id LIKE \'%'.$this->db->escape_str($where['param2']).'%\'', null, false);
				$this->db->where('concat(patient.p_lname) LIKE \'%'.$this->db->escape_str($where['param']).'%\'', null, false);
				$this->db->where('concat(patient.p_fname) LIKE \'%'.$this->db->escape_str($where['query']).'%\'', null, false);
				$this->db->where('hosp.account_location',$account_location);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('patient.ordered_by');
			}
			else
			{
				$search_string = $where['term'];
				$where = "(patient.medical_record_id LIKE '%$search_string%' OR patient.p_fname LIKE '%$search_string%' OR patient.p_lname LIKE '%$search_string%')";
				$this->db->where($where);
				$this->db->where('hosp.account_location',$account_location);
				$this->db->group_by('patient.medical_record_id');
				$this->db->group_by('patient.ordered_by');
			}
			//remove this two lines if it will cause errors -- newly added
			// $this->db->where_not_in('orders.activity_typeid', 2);
			// $this->db->where('orders.serial_num !=', "item_options_only");
			// $this->db->where('orders.serial_num !=', "item_options_only");
			// $this->db->where('equipments.categoryID !=', 3);

			//remove this two lines if it will cause errors -- newly added

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('patient.ordered_by');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		function list_order_status($uniqueID="")
		{
			$this->db->select('stats.*');
			$this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=stats.order_uniqueID),0) as comment_count',FALSE);
			$this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
			$this->db->select('patient.*');
			$this->db->select('act.*');
			$this->db->select('hosp.*');
			$this->db->select('equipments.*');
			$this->db->select('cat.*');

			$this->db->from('dme_order_status as stats');
		//	$this->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
			$this->db->join('dme_patient AS patient', 'patient.patientID = stats.patientID','left');
			$this->db->join('dme_user AS users',  'users.userID = stats.ordered_by','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = stats.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = stats.organization_id','left');
		//	$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
		//	$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			if($this->session->userdata('account_type') != 'hospice_admin' && $this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('stats.ordered_by', $this->session->userdata('userID'));
			}
			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('stats.organization_id', $this->session->userdata('group_id'));
			}
			if($uniqueID != "")
			{
				$this->db->where('stats.medical_record_id', $uniqueID);
			}

			$this->db->group_by('stats.status_activity_typeid');
			$this->db->group_by('stats.order_uniqueID');
			$this->db->where('stats.order_status !=', 'confirmed');
			$this->db->where('stats.order_status !=', 'cancel');
			$this->db->where('stats.order_status !=', 'tobe_confirmed');

			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_array();
		}

		function list_order_status_new($uniqueID="")
		{
			$this->db->select('orders.equipmentID,orders.pickup_date,orders.organization_id,orders.uniqueID,orders.order_status,orders.addressID,stats.*,patient.p_fname,patient.p_lname,act.*,hosp.hospiceID,hospice_name');
			$this->db->select('COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);

			$this->db->from('dme_order_status as stats');
			//$this->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');

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
			$this->db->where('orders.order_status !=', 'tobe_confirmed');

			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_array();
		}

		function list_order_status_new_v2($account_location)
		{
			$this->db->select('orders.equipmentID,orders.pickup_date,orders.organization_id,orders.uniqueID,orders.order_status,orders.addressID,stats.*,patient.p_fname,patient.p_lname,act.*,hosp.hospiceID,hospice_name');
			$this->db->select('COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);

			$this->db->from('dme_order_status as stats');
			$this->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');

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
			$this->db->where('orders.order_status !=', 'tobe_confirmed');
			$this->db->where('hosp.account_location', $account_location);

			$query = $this->db->get();
			return $query->result_array();
		}

		function list_order_status_new_v3($filters=false,$account_location,$start=0,$limit=0)
		{
			$this->db->start_cache();
			if($filters!=false)
	        {
	            $this->load->library('orm/filters');
	            $this->filters->detect('customer_orders',$filters);
	        }

		  	$this->db->select("addressID,medical_record_id,organization_id,pickup_date,uniqueID,hospiceID,status_activity_typeid,patientID,comment_count,p_fname,p_lname,hospice_name,order_status");
			$this->db->from('customer_order_status');

			if($this->session->userdata('account_type') != 'hospice_admin' && $this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('ordered_by', $this->session->userdata('userID'));
			}
			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('organization_id', $this->session->userdata('group_id'));
			}
			if($uniqueID != "")
			{
				$this->db->where('medical_record_id', $uniqueID);
			}


			$this->db->where('order_status !=', 'confirmed');
			$this->db->where('order_status !=', 'cancel');
			$this->db->where('order_status !=', 'tobe_confirmed');
			$this->db->where('account_location', $account_location);

		    $this->db->stop_cache();
				$response['totalCount'] = $this->db->count_all_results() ;
				if($limit!=-1)
		    {
		        $this->db->limit($limit,$start);
		    }
		    $response['limit'] = $limit;
    		$response['start'] = $start;
    		$response['result'] = $this->db->get()->result_array();
    		$this->db->flush_cache();

				//getting total
				// $where = "";
				// $where_array = array();
				// if(isset($filters['search_item_fields_customer_orders']) && $filters['search_item_fields_customer_orders']!=""){
				// 	$where_array[] = " (pickup_date LIKE '%$search_value%' OR p_lname LIKE '%$search_value%' OR p_fname LIKE '%$search_value%' OR medical_record_id LIKE '%$search_value%' OR hospice_name LIKE '%$search_value%')";
				// }
				// if($this->session->userdata('account_type') != 'hospice_admin' && $this->session->userdata('account_type') != 'dme_admin')
				// {
				//   $where_array[] = 'ordered_by = '.$this->session->userdata('userID');
				// }
				// if($this->session->userdata('account_type') != 'dme_admin')
				// {
				// 	$where_array[] =' organization_id = '.$this->session->userdata('group_id');
				// }
				// if($uniqueID != "")
				// {
				// 	$where_array[] = ' medical_record_id = '.$this->db->escape($uniqueID);
				// }
				// if(!empty($where_array)){
				// 	$where = " AND ".implode(" AND ",$where_array);
				// }
				// $response['totalCount'] = 0;
				// $query = "SELECT COUNT(*) as total FROM (SELECT distinct status_activity_typeid,order_uniqueID
				// 						FROM (`customer_order_status`)
				// 						WHERE  `order_status` != 'confirmed'
				// 						AND `order_status` != 'cancel'
				// 						AND `order_status` != 'tobe_confirmed'
				// 						AND `account_location` =  '1' {$where} ) te
				// 					";
				//$response['totalCount'] = $this->db->query($query)->row()->total;

			return $response;
		}

		function list_order_status_new_v3_confirm($filters=false,$account_location,$start=0,$limit=0)
		{
			$this->db->start_cache();
			if($filters!=false)
	        {
	            $this->load->library('orm/filters');
	            $this->filters->detect('customer_orders',$filters);
	        }

			$this->db->select('addressID,medical_record_id,organization_id,pickup_date,uniqueID,hospiceID,status_activity_typeid,patientID,comment_count,p_fname,p_lname,hospice_name,order_status');
		//	$this->db->select('COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);

			$this->db->from('confirm_work_orders');

			if($this->session->userdata('account_type') != 'hospice_admin' && $this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('ordered_by', $this->session->userdata('userID'));
			}
			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('organization_id', $this->session->userdata('group_id'));
			}
			if($uniqueID != "")
			{
				$this->db->where('medical_record_id', $uniqueID);
			}
            $this->db->where('order_status !=', 'confirmed');
            $this->db->where('order_status !=', 'cancel');
            $this->db->where('order_status', 'tobe_confirmed');
            $this->db->where('account_location', $account_location);
		    $this->db->stop_cache();
				$response['totalCount'] = $this->db->count_all_results() ;
				if($limit!=-1)
		    {
		        $this->db->limit($limit,$start);
		    }
		    $response['limit'] = $limit;
    		$response['start'] = $start;
    		$response['result'] = $this->db->get()->result_array();
			//	echo $this->db->last_query();
    		$this->db->flush_cache();
				//getting total
				// $where = "";
				// $where_array = array();
				// if(isset($filters['search_item_fields_customer_orders']) && $filters['search_item_fields_customer_orders']!=""){
				// 	$where_array[] = " (pickup_date LIKE '%$search_value%' OR p_lname LIKE '%$search_value%' OR p_fname LIKE '%$search_value%' OR medical_record_id LIKE '%$search_value%' OR hospice_name LIKE '%$search_value%')";
				// }
				// if($this->session->userdata('account_type') != 'hospice_admin' && $this->session->userdata('account_type') != 'dme_admin')
				// {
				//   $where_array[] = 'ordered_by = '.$this->session->userdata('userID');
				// }
				// if($this->session->userdata('account_type') != 'dme_admin')
				// {
				// 	$where_array[] =' organization_id = '.$this->session->userdata('group_id');
				// }
				// if($uniqueID != "")
				// {
				// 	$where_array[] = ' medical_record_id = '.$this->db->escape($uniqueID);
				// }
				// if(!empty($where_array)){
				// 	$where = " AND ".implode(" AND ",$where_array);
				// }
				// $response['totalCount'] = 0;
				// $query = "SELECT COUNT(*) as total FROM (SELECT distinct status_activity_typeid,order_uniqueID
				// 						FROM (`customer_order_status`)
				// 						WHERE  `order_status` != 'confirmed'
				// 						AND `order_status` != 'cancel'
				// 						AND `order_status` = 'tobe_confirmed'
				// 						AND `account_location` =  '1' {$where} ) te
				// 					";
				//$response['totalCount'] = $this->db->query($query)->row()->total;
			//	echo $this->db->last_query();
			return $response;
		}

		function list_order_status_new_v3_by_activity_type($filters=false,$account_location,$activity_status,$start=0,$limit=0)
		{
			$this->db->start_cache();
			if($filters!=false)
	        {
	            $this->load->library('orm/filters');
	            $this->filters->detect('customer_orders',$filters);
	        }

		$this->db->select('addressID,medical_record_id,organization_id,pickup_date,uniqueID,hospiceID,status_activity_typeid,patientID,comment_count,p_fname,p_lname,hospice_name,order_status');

			$this->db->from('customer_order_status');

			if($this->session->userdata('account_type') != 'hospice_admin' && $this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('ordered_by', $this->session->userdata('userID'));
			}
			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('organization_id', $this->session->userdata('group_id'));
			}
			if($uniqueID != "")
			{
				$this->db->where('medical_record_id', $uniqueID);
			}
			$this->db->group_by('status_activity_typeid');
			$this->db->group_by('order_uniqueID');
			$this->db->where('order_status', $activity_status);
			$this->db->where('account_location', $account_location);



		    $this->db->stop_cache();
				$response['totalCount'] = $this->db->get()->num_rows();
				if($limit!=-1)
			    {
			        $this->db->limit($limit,$start);
			    }
		    $response['limit'] = $limit;
    		$response['start'] = $start;
    		$response['result'] = $this->db->get()->result_array();


    		$this->db->flush_cache();

			return $response;
		}

		function list_order_status_v2($limit,$start,$sort,$search_value="")
		{
			$this->db->start_cache();

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

			$this->db->where('orders.order_status !=', 'confirmed');
			$this->db->where('orders.order_status !=', 'cancel');
			$this->db->where('orders.order_status !=', 'tobe_confirmed');
			if($search_value != "")
	      	{
	      		$this->db->where("(orders.order_status LIKE '%".$search_value."%' OR patient.p_fname LIKE '%".$search_value."%' OR patient.p_lname LIKE '%".$search_value."%' OR patient.medical_record_id LIKE '%".$search_value."%' OR act.activity_name LIKE '%".$search_value."%' OR hosp.hospice_name LIKE '%".$search_value."%' OR orders.pickup_date LIKE '%".$search_value."%')");
	      	}

			$this->db->group_by('stats.status_activity_typeid');
			$this->db->group_by('stats.order_uniqueID');
			if($sort['key'] == 0)
			{
				$this->db->order_by('orders.pickup_date',$sort['direction']);
			}
			else if($sort['key'] == 1)
			{
				$this->db->order_by('patient.p_fname',$sort['direction']);
			}
			else if($sort['key'] == 2)
			{
				$this->db->order_by('patient.p_lname',$sort['direction']);
			}
			else if($sort['key'] == 3)
			{
				$this->db->order_by('patient.medical_record_id',$sort['direction']);
			}
			else if($sort['key'] == 4)
			{
				$this->db->order_by('act.activity_name',$sort['direction']);
			}
			else if($sort['key'] == 5)
			{
				if($this->session->userdata('account_type') == 'dme_admin')
				{
					$this->db->order_by('hosp.hospice_name',$sort['direction']);
				}
			}
			else if($sort['key'] == 7)
			{
				if($this->session->userdata('account_type') != 'dme_admin')
				{
					$this->db->order_by('orders.order_status',$sort['direction']);
				}
			}
			else if($sort['key'] == 8)
			{
				if($this->session->userdata('account_type') == 'dme_admin')
				{
					$this->db->order_by('orders.order_status',$sort['direction']);
				}
			}

			if($limit!=-1)
	        {
	            $this->db->limit($limit,$start);
	        }
	        $this->db->stop_cache();

			$query = $this->db->get();
			$response['totalCount'] = $this->db->get()->num_rows();
			$response['result'] = $query->result_array();
			$this->db->flush_cache();

			return $response;
		}

		function patient_list_order_status($patientID="")
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
			$this->db->where('stats.patientID', $patientID);

			$query = $this->db->get();
			//echo $this->db->last_query();
			return $query->result_array();
		}

		function list_order_status_by_status($status="",$account_location)
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
			$this->db->where('orders.order_status', $status);
			$this->db->where('hosp.account_location', $account_location);

			$query = $this->db->get();
			return $query->result_array();
		}

		function list_tobe_confirmed_new($uniqueID="")
		{
			$this->db->select('orders.equipmentID,orders.pickup_date,orders.organization_id,orders.uniqueID,orders.order_status,orders.addressID,stats.*,patient.p_fname,patient.p_lname,act.*,hosp.hospiceID,hospice_name');
			$this->db->select('COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);

			$this->db->from('dme_order_status as stats');
			$this->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');

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

		function list_tobe_confirmed_new_v2($account_location)
		{
			$this->db->select('orders.equipmentID,orders.pickup_date,orders.organization_id,orders.uniqueID,orders.order_status,orders.addressID,stats.*,patient.p_fname,patient.p_lname,act.*,hosp.hospiceID,hospice_name');
			$this->db->select('COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);

			$this->db->from('dme_order_status as stats');
			$this->db->join('dme_order as orders', 'stats.order_uniqueID=orders.uniqueID','left');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');

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
			$this->db->where('hosp.account_location', $account_location);

			$query = $this->db->get();
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

		function list_tobe_confirmed_v2($limit,$start,$sort,$search_value="")
		{
			$this->db->start_cache();

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

			$this->db->where('orders.order_status !=', 'confirmed');
			$this->db->where('orders.order_status !=', 'cancel');
			$this->db->where('orders.order_status', 'tobe_confirmed');
			if($search_value != "")
	      	{
	      		$this->db->where("(orders.order_status LIKE '%".$search_value."%' OR patient.p_fname LIKE '%".$search_value."%' OR patient.p_lname LIKE '%".$search_value."%' OR patient.medical_record_id LIKE '%".$search_value."%' OR act.activity_name LIKE '%".$search_value."%' OR hosp.hospice_name LIKE '%".$search_value."%' OR orders.pickup_date LIKE '%".$search_value."%')");
	      	}
			$this->db->group_by('stats.status_activity_typeid');
			$this->db->group_by('stats.order_uniqueID');

			if($sort['key'] == 0)
		    {
		        $this->db->order_by('orders.pickup_date',$sort['direction']);
		    }
		    else if($sort['key'] == 1)
		    {
		        $this->db->order_by('patient.p_fname',$sort['direction']);
		    }
		    else if($sort['key'] == 2)
		    {
		        $this->db->order_by('patient.p_lname',$sort['direction']);
		    }
		    else if($sort['key'] == 3)
		    {
		        $this->db->order_by('patient.medical_record_id',$sort['direction']);
		    }
		    else if($sort['key'] == 4)
		    {
		        $this->db->order_by('act.activity_name',$sort['direction']);
		    }
		    else if($sort['key'] == 5)
		    {
		        if($this->session->userdata('account_type') == 'dme_admin')
		        {
		          	$this->db->order_by('hosp.hospice_name',$sort['direction']);
		        }
		    }
		    else if($sort['key'] == 7)
		    {
		        if($this->session->userdata('account_type') != 'dme_admin')
		        {
		          	$this->db->order_by('orders.order_status',$sort['direction']);
		        }
		    }
		    else if($sort['key'] == 8)
		    {
		        if($this->session->userdata('account_type') == 'dme_admin')
		        {
		          	$this->db->order_by('orders.order_status',$sort['direction']);
		        }
		    }

      		if($limit!=-1)
          	{
          	    $this->db->limit($limit,$start);
          	}
          	$this->db->stop_cache();
			$query = $this->db->get();
		    $response['totalCount'] = $this->db->get()->num_rows();
		    $response['result'] = $query->result_array();

		    // echo $this->db->last_query();
		    // echo "SAMPLE Gsdf";
		    // exit();
		    $this->db->flush_cache();

		    return $response;
		}

		function patient_list_tobe_confirmed($patientID="")
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
			$this->db->where('stats.patientID',$patientID);

			$query = $this->db->get();
			return $query->result_array();
		}



		function list_orders_to_cancel($medical_record_id="", $work_order="")
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

			$this->db->where('orders.medical_record_id', $medical_record_id);
			$this->db->where('uniqueID', $work_order);

			// $this->db->group_by('patient.medical_record_id');
			// $this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		function original_list_orders_to_cancel_exchange($medical_record_id="", $work_order="")
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

			$this->db->where('orders.medical_record_id', $medical_record_id);
			$this->db->where('uniqueID_reference', $work_order);
			$this->db->where('activity_reference', 3);

			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}

		function original_list_orders_to_cancel_pickup($medical_record_id="", $work_order="")
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

			$this->db->where('orders.medical_record_id', $medical_record_id);
			$this->db->where('pickedup_uniqueID', $work_order);
			$this->db->where('activity_reference', 2);

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

			/** Russel added codes. This is for filtering inactive patients. **/
	     	$this->db->where('orders.pickup_order ', 0);
	        $this->db->where('equipments.categoryID !=', 3);
	        $this->db->where('orders.serial_num !=','item_options_only');
	        $this->db->where('orders.canceled_from_confirming !=', 1);
	        $this->db->where('orders.canceled_order !=', 1);
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

		function get_patient_profile_temp($medical_record_id, $hospiceID)
		{
			$this->db->select('orders.*');
			$this->db->select('hosp.*');
			$this->db->select('pat.*');

			$this->db->from('dme_order as orders');
			$this->db->join('dme_hospice as hosp' , 'orders.organization_id = hosp.hospiceID', 'left');
			$this->db->join('dme_patient as pat', 'pat.patientID = orders.patientID', 'left');

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

			$this->db->from('dme_order as orders');
			$this->db->join('dme_hospice as hosp' , 'orders.organization_id = hosp.hospiceID', 'left');
			$this->db->join('dme_user as users', 'users.userID = orders.ordered_by', 'left');
			$this->db->join('dme_patient as pat', 'pat.patientID = orders.patientID', 'left'); //originally this is pat.medical_record_id=orders.medical_record_id
			$this->db->join('dme_activity_type act' , 'act.activity_id = orders.activity_typeid', 'left');

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
				//return if naay error
				//$this->db->where('orders.activity_typeid', $act_id);
			}

			$this->db->where('orders.medical_record_id', $medical_record_id);
			$this->db->where('orders.uniqueID', $uniqueID);
			$this->db->where('orders.patientID', $patientID);
			$this->db->group_by('orders.medical_record_id');

			$query = $this->db->get();

			return $query->result_array();
		}

		function get_pickup_order($medical_record_id,$pickedup_uniqueID)
		{
			$this->db->select('*');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');

			$this->db->where('orders.medical_record_id', $medical_record_id);
			$this->db->where('equip.parentID', 0);
			$this->db->where('orders.uniqueID', $pickedup_uniqueID);

			$query = $this->db->get();

			return $query->result_array();
		}

		function get_pickup_order_details($medical_record_id,$uniqueID,$act_id,$patientID,$activity_reference_id)
		{
			$this->db->select('orders.*');
			$this->db->select('hosp.*');
			$this->db->select('users.*');
			$this->db->select('pat.*');
			$this->db->select('act.*');

			$this->db->from('dme_order as orders');
			$this->db->join('dme_hospice as hosp' , 'orders.organization_id = hosp.hospiceID', 'left');
			$this->db->join('dme_user as users', 'users.userID = orders.ordered_by', 'left');
			$this->db->join('dme_patient as pat', 'pat.patientID = orders.patientID', 'left'); //originally this is pat.medical_record_id=orders.medical_record_id
			$this->db->join('dme_activity_type act' , 'act.activity_id = orders.activity_typeid', 'left');

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}
			if($act_id == 4)
			{
				if($activity_reference_id)
				{
					if($activity_reference_id == 2)
					{
						$this->db->where('orders.original_activity_typeid', $act_id);
					}
				}
			}

			$this->db->where('orders.medical_record_id', $medical_record_id);
			if($act_id == 2 || $act_id == 3 || $act_id == 5)
			{
				$this->db->where('orders.uniqueID', $uniqueID);
			}
			else if($act_id == 4)
			{
				if($activity_reference_id)
				{
					if($activity_reference_id == 2)
					{
						$this->db->where('orders.uniqueID_reference', $uniqueID);
						$this->db->or_where('orders.pickedup_uniqueID', $uniqueID);
					}
					else
					{
						$this->db->where('orders.uniqueID', $uniqueID);
					}
				}

			}
			$this->db->where('orders.patientID', $patientID);
			$this->db->group_by('orders.medical_record_id');

			$query = $this->db->get();

			return $query->result_array();
		}
		// $this->db->select('ptmove.*');
		// $this->db->select('respite.*');
		// $this->db->select('exchange.*');
		// $this->db->select('picks.*');
		// $this->db->join('dme_sub_ptmove ptmove' , 'pat.medical_record_id = ptmove.medical_record_id', 'left');
		// $this->db->join('dme_sub_respite respite' , 'pat.medical_record_id = respite.medical_record_id', 'left');
		// $this->db->join('dme_sub_exchange exchange' , 'pat.medical_record_id = exchange.medical_record_id', 'left');
		// $this->db->join('dme_pickup_tbl as picks' , 'pat.medical_record_id = picks.medical_record_id', 'left');

		function get_pickup_order_details_v1($medical_record_id,$uniqueID,$act_id,$patientID,$activity_reference_id)
		{
			$this->db->select('orders.*');
			$this->db->select('hosp.*');
			$this->db->select('users.*');
			$this->db->select('pat.*');
			$this->db->select('act.*');

			$this->db->from('dme_order as orders');
			$this->db->join('dme_hospice as hosp' , 'orders.organization_id = hosp.hospiceID', 'left');
			$this->db->join('dme_user as users', 'users.userID = orders.ordered_by', 'left');
			$this->db->join('dme_patient as pat', 'pat.patientID = orders.patientID', 'left'); //originally this is pat.medical_record_id=orders.medical_record_id
			$this->db->join('dme_activity_type act' , 'act.activity_id = orders.activity_typeid', 'left');

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			}
			if($act_id == 4)
			{
				if($activity_reference_id)
				{
					if($activity_reference_id == 2)
					{
						$this->db->where('orders.original_activity_typeid', $act_id);
					}
				}
			}

			$this->db->where('orders.medical_record_id', $medical_record_id);
			if($act_id == 2 || $act_id == 3 || $act_id == 5)
			{
				$this->db->where('orders.uniqueID', $uniqueID);
			}
			else if($act_id == 4)
			{
				if($activity_reference_id)
				{
					if($activity_reference_id == 2)
					{
						$this->db->where('orders.uniqueID_reference', $uniqueID);
						$this->db->or_where('orders.pickedup_uniqueID', $uniqueID);
					}
					else
					{
						$this->db->where('orders.uniqueID', $uniqueID);
					}
				}

			}
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
			$this->db->select('respite.respite_delivery_date,respite.respite_pickup_date,respite.respite_deliver_to_type,respite.respite_address,respite.respite_placenum,respite.respite_city,respite.respite_state,respite.respite_postal,respite.respite_phone_number');
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

            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where("orders.order_status !=", $cancel);

            //$this->db->where('orders.activity_typeid !=', 2);

           	$this->db->group_by('orders.orderID');
            $this->db->order_by('orders.pickup_date', 'DESC');

            //$this->db->order_by('equip.key_desc', 'ASC');
            $data = $this->db->get()->result_array();
            return $data;
        }

        public function get_orders_v2($unique, $hospiceID="")
        {
			$cancel = "cancel";

            $this->db->select('equip.*');
            $this->db->select('orders.*');
            $this->db->select('patients.*');
            $this->db->select('hospices.*'); //newly added : remove if it will cause errors
            $this->db->select('patient_address.*');

        	$this->db->select('COALESCE((SELECT key_desc FROM dme_equipment WHERE dme_equipment.equipmentID=equip.parentID LIMIT 1),"") as parent_name',FALSE);
           	$this->db->select('COALESCE((SELECT option_description FROM dme_options WHERE dme_options.optionID=equip.optionID LIMIT 1),"") as option_description',FALSE);
           	$this->db->select('COALESCE((SELECT type FROM dme_equip_category WHERE dme_equip_category.categoryID=equip.categoryID LIMIT 1),"") as type',FALSE);
           	$this->db->select('COALESCE((SELECT activity_name FROM dme_activity_type WHERE dme_activity_type.activity_id=orders.activity_typeid LIMIT 1),"") as activity_name',FALSE);
           	$this->db->select('COALESCE((SELECT hospice_name FROM dme_hospice WHERE dme_hospice.hospiceID=orders.organization_id LIMIT 1),"") as hospice_name',FALSE); //newly added : remove if it will cause errors
           	$this->db->select('patient_address.type as address_type');

            $this->db->from('dme_order as orders');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
            $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left'); //changed the medical_record_id to patientID
            $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors
            $this->db->join('dme_patient_address as patient_address', 'orders.addressID = patient_address.id', 'left');

			$this->db->query('SET SQL_BIG_SELECTS=1');  //newly added 09/18/2015
            if($unique != '')
            {
                $this->db->where('orders.medical_record_id' , $unique);

                $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
            }

            $this->db->where('equip.parentID', 0);
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
            $this->db->where('orders.order_status', "confirmed");

           	$this->db->group_by('orders.orderID');
            $this->db->order_by('orders.pickup_date', 'DESC');

            //$this->db->order_by('equip.key_desc', 'ASC');
            $data = $this->db->get()->result_array();

            return $data;
        }

        public function get_items_for_exchange($unique, $hospiceID)
        {
        	$this->db->select('address.*');
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
            $this->db->join('dme_patient_address as address', 'address.id = orders.addressID', 'left');
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
            $this->db->where('orders.order_status !=', "cancel");
            $this->db->where('address.type ', 0);

           	$this->db->group_by('orders.orderID');
            $this->db->order_by('orders.pickup_date', 'DESC');

            //$this->db->order_by('equip.key_desc', 'ASC');
            $data = $this->db->get()->result_array();

            return $data;
        }

        public function get_items_for_exchange_v2($unique, $hospiceID)
        {
        	$this->db->select('address.*');
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
            $this->db->join('dme_patient_address as address', 'address.id = orders.addressID', 'left');
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
            $this->db->where('orders.order_status !=', "cancel");
            $this->db->where('address.type ', 0);
            $this->db->where('equip.equipmentID !=', 484);

           	$this->db->group_by('orders.orderID');
            $this->db->order_by('orders.pickup_date', 'DESC');

            //$this->db->order_by('equip.key_desc', 'ASC');
            $data = $this->db->get()->result_array();

            return $data;
        }

        public function get_items_for_pickup($unique, $hospiceID)
        {
        	$this->db->select('address.*');
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
            $this->db->join('dme_patient_address as address', 'address.id = orders.addressID', 'left');
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
            $this->db->where('orders.order_status !=', "cancel");
            $this->db->where('address.type ', 0);

           	$this->db->group_by('orders.orderID');
            $this->db->order_by('orders.pickup_date', 'DESC');

            //$this->db->order_by('equip.key_desc', 'ASC');
            $data = $this->db->get()->result_array();

            return $data;
        }

        public function get_items_for_pickup_v2($unique, $hospiceID)
        {
        	$this->db->select('address.*');
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
            $this->db->join('dme_patient_address as address', 'address.id = orders.addressID', 'left');
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
            $this->db->where('orders.order_status !=', "cancel");
            $this->db->where('address.type ', 0);
            $this->db->where('equip.equipmentID !=', 484);

           	$this->db->group_by('orders.orderID');
            $this->db->order_by('orders.pickup_date', 'DESC');

            //$this->db->order_by('equip.key_desc', 'ASC');
            $data = $this->db->get()->result_array();

            return $data;
        }

        public function get_items_for_pickup_other_address($unique, $hospiceID, $addressID)
        {
        	$this->db->select('address.*');
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
            $this->db->join('dme_patient_address as address', 'address.id = orders.addressID', 'left');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
            $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left');
            $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors

            if($unique != '')
            {
                $this->db->where('orders.medical_record_id' , $unique);

                $this->db->where('orders.organization_id', $hospiceID); //newly added 07/13/2015.
            }
            // $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.activity_typeid !=', 2);
            $this->db->where('orders.order_status !=', "cancel");
            $this->db->where('orders.addressID ', $addressID);

           	$this->db->group_by('orders.orderID');
            $this->db->order_by('orders.pickup_date', 'DESC');

            //$this->db->order_by('equip.key_desc', 'ASC');
            $data = $this->db->get()->result_array();

            return $data;
        }

        public function get_items_for_pickup_other_address_v2($unique, $hospiceID, $addressID)
        {
        	$this->db->select('address.*');
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
            $this->db->join('dme_patient_address as address', 'address.id = orders.addressID', 'left');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
            $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left');
            $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors

            if($unique != '')
            {
                $this->db->where('orders.medical_record_id' , $unique);

                $this->db->where('orders.organization_id', $hospiceID); //newly added 07/13/2015.
            }
            // $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.activity_typeid !=', 2);
            $this->db->where('orders.order_status !=', "cancel");
            $this->db->where('orders.addressID ', $addressID);
            $this->db->where('equip.equipmentID !=', 484);

           	$this->db->group_by('orders.orderID');
            $this->db->order_by('orders.pickup_date', 'DESC');

            //$this->db->order_by('equip.key_desc', 'ASC');
            $data = $this->db->get()->result_array();

            return $data;
        }

        public function get_items_for_pickup_other_address_exchange($unique, $hospiceID, $addressID)
        {
        	$this->db->select('address.*');
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
            $this->db->join('dme_patient_address as address', 'address.id = orders.addressID', 'left');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
            $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left');
            $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors

            if($unique != '')
            {
                $this->db->where('orders.medical_record_id' , $unique);

                $this->db->where('orders.organization_id', $hospiceID); //newly added 07/13/2015.
            }
            // $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.activity_typeid !=', 2);
            $this->db->where('orders.order_status !=', "cancel");
            $this->db->where('orders.addressID ', $addressID);

           	$this->db->group_by('orders.orderID');
            $this->db->order_by('orders.pickup_date', 'DESC');

            //$this->db->order_by('equip.key_desc', 'ASC');
            $data = $this->db->get()->result_array();

            return $data;
        }

        public function get_items_for_pickup_other_address_exchange_v2($unique, $hospiceID, $addressID)
        {
        	$this->db->select('address.*');
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
            $this->db->join('dme_patient_address as address', 'address.id = orders.addressID', 'left');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
            $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left');
            $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors

            if($unique != '')
            {
                $this->db->where('orders.medical_record_id' , $unique);

                $this->db->where('orders.organization_id', $hospiceID); //newly added 07/13/2015.
            }
            // $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.activity_typeid !=', 2);
            $this->db->where('orders.order_status !=', "cancel");
            $this->db->where('orders.addressID ', $addressID);
            $this->db->where('equip.equipmentID !=', 484);

           	$this->db->group_by('orders.orderID');
            $this->db->order_by('orders.pickup_date', 'DESC');

            //$this->db->order_by('equip.key_desc', 'ASC');
            $data = $this->db->get()->result_array();

            return $data;
        }

				public function get_orders_by_workorder($id, $unique="", $hospiceID="",$parentID=-1,$parents=array())
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
						if($parentID > -1){
							$this->db->where('equip.parentID',$parentID);
						}
						if(!empty($parents)){
							$this->db->where_in('equip.parentID',$parents);
						}
            $this->db->where('orders.uniqueID',$unique);
            $this->db->group_by('orders.orderID');
            $this->db->order_by('orders.pickup_date', 'DESC');
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

            $this->db->where('orders.organization_id', $hospiceID); //07/13/2015
           	$this->db->where("orders.medical_record_id", $id);
           	$this->db->where("((`orders`.`activity_typeid` = 3) OR `orders`.`activity_typeid` = 2)");
           	$this->db->where("(`orders`.`order_status` != 'confirmed' AND `orders`.`uniqueID` = ".$uniqueID.") OR `orders`.`uniqueID_reference` = ".$uniqueID);
            $this->db->where("orders.summary_pickup_date", '0000-00-00');

            $this->db->group_by('orders.orderID');
            $this->db->order_by('orders.activity_typeid', 'DESC');
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
                        "activity_typeid"   => 2
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

        public function delete_order_dme_order($medical_record_id, $uniqueID)
        {
    		$this->db->where('medical_record_id', $medical_record_id);
    		$this->db->where('uniqueID', $uniqueID);
			$this->db->delete('dme_order');
        }

        public function delete_order_dme_order_status($medical_record_id, $uniqueID)
        {
    		$this->db->where('medical_record_id', $medical_record_id);
    		$this->db->where('order_uniqueID', $uniqueID);
			$this->db->delete('dme_order_status');
        }

        public function delete_equipment_pickup($equipmentID,$medical_record_id, $pickedup_uniqueID)
        {
    		$this->db->where('medical_record_id', $medical_record_id);
    		$this->db->where('equipmentID', $equipmentID);
    		$this->db->where('uniqueID', $pickedup_uniqueID);
			$this->db->delete('dme_order');
        }

        public function delete_equipment_options_pickup($equipmentID,$medical_record_id,$pickedup_uniqueID)
		{
			$this->db->where('medical_record_id', $medical_record_id);
			$this->db->where('equipmentID', $equipmentID);
			$this->db->where('uniqueID', $pickedup_uniqueID);
			$this->db->delete('dme_order');
		}

        public function delete_order($medical_record_id, $uniqueID)
        {
    		$this->db->where('medical_record_id', $medical_record_id);
    		$this->db->where('uniqueID', $uniqueID);
			$this->db->delete('dme_order');
        }

        public function update_canceled_order_status($medical_record_id,$uniqueID,$data)
        {
        	$this->db->where('medical_record_id', $medical_record_id);
        	$this->db->where('uniqueID', $uniqueID);
        	$this->db->update('dme_order', $data);
        }

        public function delete_address($addressID, $patientID)
        {
    		$this->db->where('patient_id', $patientID);
    		$this->db->where('id', $addressID);
			$this->db->delete('dme_patient_address');
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

        public function revert_pickedup_item_to_original($equipmentID,$uniqueID,$medical_record_id,$old_activity_type,$array=array())
        {
        	$this->db->where('medical_record_id', $medical_record_id);
        	if($old_activity_type == 3)
        	{
        		$this->db->where('uniqueID_reference', $uniqueID);
        	}
        	else
        	{
        		$this->db->where('pickedup_uniqueID', $uniqueID);
        	}
        	$this->db->where('equipmentID', $equipmentID);
        	$this->db->update('dme_order', $array);
        }

        public function revert_delivery_item_to_original($equipmentID,$uniqueID,$medical_record_id,$old_activity_type,$array=array())
        {
        	$this->db->where('medical_record_id', $medical_record_id);
        	$this->db->where('uniqueID', $uniqueID);
        	$this->db->where('equipmentID', $equipmentID);
        	$this->db->update('dme_order', $array);
        }

        public function revert_exchange_item_to_original($equipmentID,$uniqueID,$medical_record_id,$array=array())
        {
        	$this->db->where('medical_record_id', $medical_record_id);
        	$this->db->where('uniqueID_reference', $uniqueID);
        	$this->db->where('equipmentID', $equipmentID);
        	$this->db->update('dme_order', $array);
        }

        public function get_canceled()
        {
        	return $this->db->get('trash_table')->result_array();
        }

        public function get_canceled_v2($account_location)
        {
        	$this->db->select('trash.*');
        	$this->db->from('trash_table as trash');
        	$this->db->join('dme_patient as patient','trash.deleted_medical_id = patient.medical_record_id','left');
        	$this->db->join('dme_hospice as hospice','patient.ordered_by = hospice.hospiceID','left');
        	$this->db->where('hospice.account_location', $account_location);
        	$query = $this->db->get();

			return $query->result_array();
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

        //save patient as draft
		public function save_patient_info($patient)
		{
			$this->db->insert('dme_patient', $patient);
			return $this->db->insert_id();
		}

		//save to address table
		public function save_address($address)
		{
			$this->db->insert('dme_patient_address', $address);
			return $this->db->insert_id();
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


      	// function add_lot_comment($data)
      	// {
      	// 	$response = false;

      	// 	if(!empty($data))
      	// 	{
      	// 		$response = $this->db->insert('dme_lot_num_comments', $data);
      	// 	}
      	// 	return $response;
      	// }

      	// function get_comments($equipmentID, $uniqueID)
      	// {
      	// 	$this->db->select('lot_comments.*');
      	// 	$this->db->select('orders.*');
      	// 	$this->db->from('dme_lot_num_comments as lot_comments');
      	// 	$this->db->join('dme_order as orders','lot_comments.equipmentID = orders.equipmentID','left');

      	// 	$where = array(
      	// 		'orders.uniqueID' => $uniqueID,
      	// 		'orders.equipmentID' => $equipmentID
      	// 	);
      	// 	$this->db->where($where);
      	// 	$this->db->order_by('lot_comments.date_added','DESC');
      	// 	$query = $this->db->get();

      	// 	return $query->result_array();
      	// }

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
      		$this->db->select('orders.*,hosp.*,patient.*');

      		$this->db->from('dme_order AS orders');
      		$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
      		$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');

      		$this->db->where('patient.ordered_by' , $hospice_id);
      		$this->db->where('patient.medical_record_id' , $medical_record_id);
      		$this->db->group_by("patient.patientID");

      		$query = $this->db->get();

			return $query->result_array();
      		// $this->db->from('dme_patient as patients');
      		// $this->db->join('dme_order as orders','patients.patientID=orders.patientID','left');
      		// $this->db->join('dme_hospice as hospices','orders.organization_id=hospices.hospiceID', 'left');

      		// $this->db->from('dme_hospice AS hospices');
      		// $this->db->join('dme_patient AS patients','hospices.hospiceID=patients.ordered_by','left');
      		// $this->db->join('dme_order AS orders','patients.patientID=orders.patientID', 'left');


      		// $this->db->where('patient.ordered_by', $hospice_id);
      		// $this->db->where('patient.medical_record_id', $medical_record_id);
      		// $this->db->group_by('orders.patientID');
      		// $query = $this->db->get();

      		// return $query->result_array();
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

      	function get_patient_addresses($patient_id)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_patient_address');
      		$this->db->where('patient_id', $patient_id);
      		$this->db->order_by("id","ASC");
      		$query = $this->db->get();

      		return $query->result_array();
      	}

      	function get_patient_addresses_v2($patient_id)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_patient_address');
      		$this->db->where('patient_id', $patient_id);
      		$this->db->where('status !=', 0);
      		$this->db->order_by("id","ASC");
      		$query = $this->db->get();

      		return $query->result_array();
      	}

      	function get_ptmove_sub_addresses($patient_id)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_sub_ptmove');
      		$this->db->where('patientID', $patient_id);
      		$this->db->order_by("ptmoveID","ASC");
      		$query = $this->db->get();

      		return $query->result_array();
      	}

      	function get_ptmove_new_addresses($patient_id)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_new_ptmove_address');
      		$this->db->where('patientID', $patient_id);
      		$this->db->order_by("ptmoveID","ASC");
      		$query = $this->db->get();

      		return $query->result_array();
      	}

      	function update_dme_sub_ptmove($ptmoveID, $data='')
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$this->db->where('ptmoveID', $ptmoveID);
				$response = $this->db->update('dme_sub_ptmove', $data);
      		}
      		return $response;
      	}

      	function update_dme_new_ptmove($ptmoveID, $data='')
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$this->db->where('ptmoveID', $ptmoveID);
				$response = $this->db->update('dme_new_ptmove_address', $data);
      		}
      		return $response;
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

      	function update_order_profile_v2($patientID ,$data='')
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$this->db->where('patientID', $patientID);
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

      	function update_patient_profile_v2($patientID, $data='')
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$this->db->where('patientID', $patientID);
				$response = $this->db->update('dme_patient', $data);
      		}
      		return $response;
      	}

      	function update_dme_patient_address($patient_id, $data)
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$this->db->where('patient_id', $patient_id);
				$response = $this->db->update('dme_patient_address', $data);
      		}
      		return $response;
      	}

      	function update_dme_patient_address_v2($id, $data)
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$this->db->where('id', $id);
				$response = $this->db->update('dme_patient_address', $data);
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

      	function retrieve_patient_order_comments($patientID)
      	{
      		$this->db->distinct("comments.commentID");
      		$this->db->select("comments.*,orders.patientID");
      		$this->db->from("dme_order_comments as comments");
      		$this->db->join("dme_order_status as orders","comments.order_uniqueID=orders.order_uniqueID");

      		// $this->db->from("dme_order as orders");
      		// $this->db->join("dme_order_comments as comments","comments.order_uniqueID=orders.uniqueID","left");

      		$this->db->where('orders.patientID', $patientID);
      		$this->db->order_by('comments.commentID','ASC');
      		return $this->db->get()->result_array();
      	}

      	function retrieve_patient_notes($medical_record_id, $patientID="")
      	{
      		$this->db->select("*");
      		$this->db->from("dme_patient_notes");
      		$this->db->where('medical_record_id', $medical_record_id);
      		$this->db->where('patientID', $patientID);
      		$this->db->order_by('created_on','DESC');
      		return $this->db->get()->result_array();
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

      	// function get_lot_number($equipmentID, $uniqueID)
      	// {
      	// 	$this->db->select("*");
      	// 	$this->db->from("dme_lot_number");
      	// 	$this->db->where("equipmentID", $equipmentID);
      	// 	$this->db->where("ticket_uniqueID",$uniqueID);

      	// 	return $this->db->get()->result_array();
      	// }


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
	      				if($value['act_name'] == "Delivery" || $value['act_name'] == "CUS Move")
	      				{
	      					$value['pickedup_date'] = "";
	      					$new_pickedup_date = $value['pickedup_date'];
	      				}
	      				else
	      				{
	      					$new_pickedup_date = ($value['pickedup_date']!="")? date("Y-m-d", strtotime($value['pickedup_date'])) : '';
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

      	// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
   		// echo $uniqueID."----";
   		// echo $medical_id."----";
      // 	function update_order_summary_confirm_fields($medical_id, $uniqueID, $data)
      // 	{
      // 		$response = false;
      // 		if(!empty($data))
      // 		{
      // 			$array = array();
      // 			$qty_value = $data['order_summary'][306]['qty'];
      // 			$e_refill_quantity = 0;
      // 			$m6_quantity = 0;
      // 			foreach($data['order_summary'] as $key=>$value)
      // 			{
      // 				if($value['act_name'] == "Delivery" || $value['act_name'] == "CUS Move" || $value['act_name'] == "Respite")
      // 				{
      // 					if($key == 11)
      // 					{
      // 						if($value['qty'] == 0)
      // 						{
      // 							$e_refill_quantity = 0;
      // 						}
      // 						else
      // 						{
      // 							$e_refill_quantity = $value['qty'];
      // 						}
      // 					}
      // 					if($key == 170)
      // 					{
      // 						if($value['qty'] == 0)
      // 						{
      // 							$m6_quantity = 0;
      // 						}
      // 						else
      // 						{
      // 							$m6_quantity = $value['qty'];
      // 						}
      // 					}
      // 					$value['pickedup_date'] = '0000-00-00';
      // 					$new_pickedup_date = $value['pickedup_date'];

      // 					$this->db->where('uniqueID', $uniqueID); //comment if this will cause errors.
	     //  				if($key == 306)
	     //  				{
	     //  					$qty = 1;

	     //  					$array = array(
		  		// 				'order_status' => "confirmed",
		  		// 				'equipmentID' => $key,
						// 		'pickedup_uniqueID' => $value['uniqueID'],
						// 		'item_num' => $value['item_num'],
						// 		'equipment_value' => $qty,
						// 		'serial_num' => $value['serial_num'],
						// 		'summary_pickup_date' => $new_pickedup_date,
						// 		'driver_name'	=> $value['driver_name'],
						// 		'person_confirming_order' => $value['person_confirming_order'],
						// 		'actual_order_date'	=> $value['actual_order_date']
						// 	);
	     //  				} else if($key == 307) {

	     //  					$array = array(
		  		// 				'order_status' => "confirmed",
		  		// 				'equipmentID' => $key,
						// 		'pickedup_uniqueID' => $value['uniqueID'],
						// 		'item_num' => $value['item_num'],
						// 		'serial_num' => $value['serial_num'],
						// 		'summary_pickup_date' => $new_pickedup_date,
						// 		'driver_name'	=> $value['driver_name'],
						// 		'person_confirming_order' => $value['person_confirming_order'],
						// 		'actual_order_date'	=> $value['actual_order_date']
						// 	);
						// }else if($key == 308){
						// 	$array = array(
		  		// 				'order_status' => "confirmed",
		  		// 				'equipmentID' => $key,
						// 		'pickedup_uniqueID' => $value['uniqueID'],
						// 		'item_num' => $value['item_num'],
						// 		'equipment_value' => $qty_value,
						// 		'serial_num' => $value['serial_num'],
						// 		'summary_pickup_date' => $new_pickedup_date,
						// 		'driver_name'	=> $value['driver_name'],
						// 		'person_confirming_order' => $value['person_confirming_order'],
						// 		'actual_order_date'	=> $value['actual_order_date']
						// 	);
						// }else if($key == 111 || $key == 311){
						// 	$array = array(
		  		// 				'order_status' => "confirmed",
		  		// 				'equipmentID' => $key,
						// 		'pickedup_uniqueID' => $value['uniqueID'],
						// 		'item_num' => $value['item_num'],
						// 		'serial_num' => $value['serial_num'],
						// 		'summary_pickup_date' => $new_pickedup_date,
						// 		'driver_name'	=> $value['driver_name'],
						// 		'person_confirming_order' => $value['person_confirming_order'],
						// 		'actual_order_date'	=> $value['actual_order_date']
						// 	);
						// }else if($key == 121){
						// 	$array = array(
			  	// 					'order_status' => "confirmed",
			  	// 					'equipmentID' => $key,
						// 			'item_num' => $value['item_num'],
						// 			'equipment_value' => $e_refill_quantity,
						// 			'serial_num' => $value['serial_num'],
						// 			'summary_pickup_date' => $new_pickedup_date,
						// 			'driver_name'	=> $value['driver_name'],
						// 			'person_confirming_order' => $value['person_confirming_order'],
						// 			'actual_order_date'	=> $value['actual_order_date']
						// 		);
						// }else if($key == 11){
						// 	if($value['qty'] == 0)
      // 						{
      // 							$e_refill_quantity_v2 = 1;
      // 						}
      // 						else
      // 						{
      // 							$e_refill_quantity_v2 = $value['qty'];
      // 						}
						// 	$array = array(
			  	// 					'order_status' => "confirmed",
			  	// 					'equipmentID' => $key,
						// 			'item_num' => $value['item_num'],
						// 			'equipment_value' => $e_refill_quantity_v2,
						// 			'serial_num' => $value['serial_num'],
						// 			'summary_pickup_date' => $new_pickedup_date,
						// 			'driver_name'	=> $value['driver_name'],
						// 			'person_confirming_order' => $value['person_confirming_order'],
						// 			'actual_order_date'	=> $value['actual_order_date']
						// 		);
						// }else if($key == 194){
						// 	$array = array(
			  	// 					'order_status' => "confirmed",
			  	// 					'equipmentID' => $key,
						// 			'item_num' => $value['item_num'],
						// 			'equipment_value' => $m6_quantity,
						// 			'serial_num' => $value['serial_num'],
						// 			'summary_pickup_date' => $new_pickedup_date,
						// 			'driver_name'	=> $value['driver_name'],
						// 			'person_confirming_order' => $value['person_confirming_order'],
						// 			'actual_order_date'	=> $value['actual_order_date']
						// 		);
						// }else if($key == 170){
						// 	if($value['qty'] == 0)
      // 						{
      // 							$m6_quantity_v2 = 1;
      // 						}
      // 						else
      // 						{
      // 							$m6_quantity_v2 = $value['qty'];
      // 						}
						// 	$array = array(
			  	// 					'order_status' => "confirmed",
			  	// 					'equipmentID' => $key,
						// 			'item_num' => $value['item_num'],
						// 			'equipment_value' => $m6_quantity_v2,
						// 			'serial_num' => $value['serial_num'],
						// 			'summary_pickup_date' => $new_pickedup_date,
						// 			'driver_name'	=> $value['driver_name'],
						// 			'person_confirming_order' => $value['person_confirming_order'],
						// 			'actual_order_date'	=> $value['actual_order_date']
						// 		);
	     //  				} else {
						// 	$checked_pickup = $this->check_pickup_order_of_equipment($value['uniqueID'],$key);
						// 	if(!empty($checked_pickup))
						// 	{
						// 		$checked_parent = $this->check_parent_of_equipment($key);
						// 		if($checked_parent['parentID'] != 0)
						// 		{
						// 			$checked_pickup_v2 = $this->check_pickup_order_of_equipment_v2($value['uniqueID'],$checked_parent['parentID']);
						// 			if(!empty($checked_pickup_v2))
						// 			{
						// 				// echo "AAA--";
						// 				$array = array(
					 //  						'order_status' => "confirmed",
					 //  						'equipmentID' => $key,
						// 					'pickedup_uniqueID' => $value['uniqueID'],
						// 					'item_num' => $value['item_num'],
						// 					'equipment_value' => $value['qty'],
						// 					'serial_num' => $value['serial_num'],
						// 					'summary_pickup_date' => $new_pickedup_date,
						// 					'driver_name'	=> $value['driver_name'],
						// 					'person_confirming_order' => $value['person_confirming_order'],
						// 					'actual_order_date'	=> $value['actual_order_date']
						// 				);
						// 				$this->db->where('driver_name',''); // remove if magka problem
						// 			}
						// 			else
						// 			{
						// 				// echo "BBB--";
						// 				$array = array(
					 //  						'order_status' => "confirmed",
					 //  						'equipmentID' => $key,
						// 					'item_num' => $value['item_num'],
						// 					'equipment_value' => $value['qty'],
						// 					'serial_num' => $value['serial_num'],
						// 					'summary_pickup_date' => $new_pickedup_date,
						// 					'driver_name'	=> $value['driver_name'],
						// 					'person_confirming_order' => $value['person_confirming_order'],
						// 					'actual_order_date'	=> $value['actual_order_date']
						// 				);
						// 				$this->db->where('orderID', $value['orderID']);
						// 			}
						// 		}
						// 		else
						// 		{
						// 			$array = array(
				  // 						'order_status' => "confirmed",
				  // 						'equipmentID' => $key,
						// 				'pickedup_uniqueID' => $value['uniqueID'],
						// 				'item_num' => $value['item_num'],
						// 				'equipment_value' => $value['qty'],
						// 				'serial_num' => $value['serial_num'],
						// 				'summary_pickup_date' => $new_pickedup_date,
						// 				'driver_name'	=> $value['driver_name'],
						// 				'person_confirming_order' => $value['person_confirming_order'],
						// 				'actual_order_date'	=> $value['actual_order_date']
						// 			);
						// 			$this->db->where('driver_name',''); // remove if magka problem
						// 		}
						// 	}
						// 	else
						// 	{
						// 		$array = array(
			  	// 					'order_status' => "confirmed",
			  	// 					'equipmentID' => $key,
						// 			'item_num' => $value['item_num'],
						// 			'equipment_value' => $value['qty'],
						// 			'serial_num' => $value['serial_num'],
						// 			'summary_pickup_date' => $new_pickedup_date,
						// 			'driver_name'	=> $value['driver_name'],
						// 			'person_confirming_order' => $value['person_confirming_order'],
						// 			'actual_order_date'	=> $value['actual_order_date']
						// 		);
						// 		$this->db->where('orderID', $value['orderID']);
						// 	}
	     //  				}

	     //  				// print_me($new_post_data);
	  			// 		$this->db->where('medical_record_id', $medical_id);
	  			// 		$this->db->where('uniqueID', $uniqueID);
	  			// 		$this->db->where('summary_pickup_date', '0000-00-00');	//comment if this will cause errors.
						// $this->db->where('equipmentID', $key);

						// unset($array['equipmentID']);
						// $response = $this->db->update('dme_order', $array);
      // 				}
      // 				else
      // 				{
      // 					$new_pickedup_date = '0000-00-00';
      // 					if($value['pickedup_date'] != "")
      // 					{
      // 						$new_pickedup_date = date("Y-m-d", strtotime($value['pickedup_date']));
      // 					}
      // 					$summary_pickup_date_here = '0000-00-00';
						// $pickup_order_per_key = get_pickup_order_specific_equipment($medical_id,$uniqueID,$summary_pickup_date_here,$key);

						// // echo "<pre>";
						// // print_r($pickup_order_per_key);
						// // echo "</pre>";

						// $count_inside = 1;
						// foreach ($pickup_order_per_key as $loop_inside)
						// {
						// 	$this->db->where('uniqueID', $uniqueID); //comment if this will cause errors.
						// 	if($key == 306)
		    //   				{
		    //   					$qty = 1;

		    //   					$array = array(
			  	// 					'order_status' => "confirmed",
			  	// 					'equipmentID' => $key,
						// 			'pickedup_uniqueID' => $value['uniqueID'],
						// 			'item_num' => $value['item_num'],
						// 			'equipment_value' => $qty,
						// 			'serial_num' => $value['serial_num'],
						// 			'summary_pickup_date' => $new_pickedup_date,
						// 			'driver_name'	=> $value['driver_name'],
						// 			'person_confirming_order' => $value['person_confirming_order'],
						// 			'actual_order_date'	=> $value['actual_order_date']
						// 		);
		    //   				}else if($key == 307){

		    //   					$array = array(
			  	// 					'order_status' => "confirmed",
			  	// 					'equipmentID' => $key,
						// 			'pickedup_uniqueID' => $value['uniqueID'],
						// 			'item_num' => $value['item_num'],
						// 			'serial_num' => $value['serial_num'],
						// 			'summary_pickup_date' => $new_pickedup_date,
						// 			'driver_name'	=> $value['driver_name'],
						// 			'person_confirming_order' => $value['person_confirming_order'],
						// 			'actual_order_date'	=> $value['actual_order_date']
						// 		);
						// 	}else if($key == 308){
						// 		$array = array(
			  	// 					'order_status' => "confirmed",
			  	// 					'equipmentID' => $key,
						// 			'pickedup_uniqueID' => $value['uniqueID'],
						// 			'item_num' => $value['item_num'],
						// 			'equipment_value' => $qty_value,
						// 			'serial_num' => $value['serial_num'],
						// 			'summary_pickup_date' => $new_pickedup_date,
						// 			'driver_name'	=> $value['driver_name'],
						// 			'person_confirming_order' => $value['person_confirming_order'],
						// 			'actual_order_date'	=> $value['actual_order_date']
						// 		);
						// 	}else if($key == 111 || $key == 311){
						// 		$array = array(
			  	// 					'order_status' => "confirmed",
			  	// 					'equipmentID' => $key,
						// 			'pickedup_uniqueID' => $value['uniqueID'],
						// 			'item_num' => $value['item_num'],
						// 			'serial_num' => $value['serial_num'],
						// 			'summary_pickup_date' => $new_pickedup_date,
						// 			'driver_name'	=> $value['driver_name'],
						// 			'person_confirming_order' => $value['person_confirming_order'],
						// 			'actual_order_date'	=> $value['actual_order_date']
						// 		);
		    //   				}else{
		    //   					$result_inside = get_original_serial_number_v2($key, $loop_inside['medical_record_id'], $loop_inside['uniqueID'], $count_inside);
		    //   					if($key == 312 || $key == 309)
		    //   					{
		    //   						$result_uniqueID_inside = get_original_uniqueID_v2(309, $loop_inside['medical_record_id'], $loop_inside['uniqueID'], $count_inside);
		    //   						$result_equipment_value_inside = get_original_equipment_value_v2($key, $loop_inside['medical_record_id'], $result_uniqueID_inside, $loop_inside['uniqueID']);

		    //   						if($key == 312)
		    //   						{
		    //   							$array = array(
					 //  						'order_status' => "confirmed",
					 //  						'equipmentID' => $key,
						// 					'pickedup_uniqueID' => $value['uniqueID'],
						// 					'item_num' => $value['item_num'],
						// 					'equipment_value' => $result_equipment_value_inside,
						// 					'serial_num' => 'item_options_only',
						// 					'summary_pickup_date' => $new_pickedup_date,
						// 					'driver_name'	=> $value['driver_name'],
						// 					'person_confirming_order' => $value['person_confirming_order'],
						// 					'actual_order_date'	=> $value['actual_order_date']
						// 				);
		    //   						}
		    //   						else
		    //   						{
		    //   							$array = array(
					 //  						'order_status' => "confirmed",
					 //  						'equipmentID' => $key,
						// 					'pickedup_uniqueID' => $value['uniqueID'],
						// 					'item_num' => $value['item_num'],
						// 					'equipment_value' => $result_equipment_value_inside,
						// 					'serial_num' => $result_inside,
						// 					'summary_pickup_date' => $new_pickedup_date,
						// 					'driver_name'	=> $value['driver_name'],
						// 					'person_confirming_order' => $value['person_confirming_order'],
						// 					'actual_order_date'	=> $value['actual_order_date']
						// 				);
		    //   						}
		    //   					}
		    //   					else
		    //   					{
		    //   						$array = array(
				  // 						'order_status' => "confirmed",
				  // 						'equipmentID' => $key,
						// 				'pickedup_uniqueID' => $value['uniqueID'],
						// 				'item_num' => $value['item_num'],
						// 				'equipment_value' => $value['qty'],
						// 				'serial_num' => $result_inside,
						// 				'summary_pickup_date' => $new_pickedup_date,
						// 				'driver_name'	=> $value['driver_name'],
						// 				'person_confirming_order' => $value['person_confirming_order'],
						// 				'actual_order_date'	=> $value['actual_order_date']
						// 			);
		    //   					}
						// 		$count_inside++;
						// 		$this->db->where('orderID', $loop_inside['orderID']);
		    //   				}

		  		// 			$this->db->where('medical_record_id', $medical_id);
		  		// 			$this->db->where('uniqueID', $uniqueID);
		  		// 			$this->db->where('summary_pickup_date', '0000-00-00');	//comment if this will cause errors.
						// 	$this->db->where('equipmentID', $key);

						// 	unset($array['equipmentID']);
						// 	$response = $this->db->update('dme_order', $array);

						// 	$this->db->where('pickedup_uniqueID', $uniqueID);
						// 	$this->db->where('medical_record_id', $medical_id);
						// 	$this->db->where('equipmentID', $key);
						// 	$this->db->where('original_activity_typeid !=', 2);

						// 	$this->db->update('dme_order',array('summary_pickup_date' => $array['summary_pickup_date']));
						// }
      // 				}
      // 			}
      // 		}
      // 		return $response;
      // 	}

      	function update_order_summary_confirm_fields($medical_id, $uniqueID, $data)
      	{
      		$response = false;
      		$queries = array();
      		$second_queries = array();
      		if(!empty($data))
      		{
      			$array = array();
      			$qty_value = $data['order_summary'][306]['qty'];
      			$e_refill_quantity = 0;
      			$m6_quantity = 0;
      			$exclude_from_escape = array(
      				'summary_pickup_date',
      				'pickedup_date',
      				'act_name'
      			);
      			$temp_medical_id = $this->db->escape($medical_id);
      			foreach($data['order_summary'] as $key=>$value)
      			{
      				$temp = "";
      				$where_ = "";
      				foreach ($value as $inside_key => $inside_value) {
      					if(!in_array($inside_key, $exclude_from_escape))
      					{
      						$value[$inside_key] = $this->db->escape($inside_value);
      					}

      					if($inside_value == "")
      					{
      						$value[$inside_key] = null;
      					}
      				}
      				if($value['act_name'] == 'Delivery' || $value['act_name'] == 'CUS Move' || $value['act_name'] == 'Respite')
      				{
      					if($key == 11)
      					{
      						if($value['qty'] == 0)
      						{
      							$e_refill_quantity = 0;
      						}
      						else
      						{
      							$e_refill_quantity = $value['qty'];
      						}
      					}
      					if($key == 170)
      					{
      						if($value['qty'] == 0)
      						{
      							$m6_quantity = 0;
      						}
      						else
      						{
      							$m6_quantity = $value['qty'];
      						}
      					}
      					$value['pickedup_date'] = '0000-00-00';
      					$new_pickedup_date = $value['pickedup_date'];

      					// print_me($value);
      					// print_me($value['serial_num']);
      					// $this->db->where('uniqueID', $uniqueID); //comment if this will cause errors.
	      				if($key == 306)
	      				{
	      					$qty = 1;

							$temp = "UPDATE dme_order SET order_status = 'confirmed', pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}',equipment_value = {$qty}, serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
							// print_me($temp);
	      				}
	      				else if($key == 307)
	      				{
							$temp = "UPDATE dme_order SET order_status = 'confirmed',pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}',serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
							// print_me($temp);
						}
						else if($key == 308)
						{
							$temp = "UPDATE dme_order SET order_status = 'confirmed',pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}',equipment_value = {$qty_value}, serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
							// print_me($temp);
						}
						else if($key == 111 || $key == 311)
						{
							$temp = "UPDATE dme_order SET order_status = 'confirmed',pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}',serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
							// print_me($temp);
						}
						else if($key == 121)
						{
							$temp = "UPDATE dme_order SET order_status = 'confirmed',item_num = '{$value['item_num']},equipment_value = {$e_refill_quantity}', serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
							// print_me($temp);
						}
						else if($key == 11)
						{
							if($value['qty'] == 0)
      						{
      							$e_refill_quantity_v2 = 1;
      						}
      						else
      						{
      							$e_refill_quantity_v2 = $value['qty'];
      						}

							$temp = "UPDATE dme_order SET order_status = 'confirmed',item_num = '{$value['item_num']}',equipment_value = {$e_refill_quantity_v2}, serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
							// print_me($temp);
						}
						else if($key == 194)
						{
							$temp = "UPDATE dme_order SET order_status = 'confirmed',item_num = '{$value['item_num']}',equipment_value = {$m6_quantity}, serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
							// print_me($temp);
						}
						else if($key == 170)
						{
							if($value['qty'] == 0)
      						{
      							$m6_quantity_v2 = 1;
      						}
      						else
      						{
      							$m6_quantity_v2 = $value['qty'];
      						}

							$temp = "UPDATE dme_order SET order_status = 'confirmed',item_num = '{$value['item_num']}',equipment_value = {$m6_quantity_v2}, serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
							// print_me($temp);
	      				}
	      				else
	      				{
							$checked_pickup = $this->check_pickup_order_of_equipment($value['uniqueID'],$key);
							if(!empty($checked_pickup))
							{
								$checked_parent = $this->check_parent_of_equipment($key);
								if($checked_parent['parentID'] != 0)
								{
									$checked_pickup_v2 = $this->check_pickup_order_of_equipment_v2($value['uniqueID'],$checked_parent['parentID']);
									if(!empty($checked_pickup_v2))
									{
										$temp = "UPDATE dme_order SET order_status = 'confirmed',pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}',equipment_value = {$value['qty']}, serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";

										$where_ = " AND driver_name = '' ";
										// print_me($temp);
									}
									else
									{
										$temp = "UPDATE dme_order SET order_status = 'confirmed',item_num = '{$value['item_num']}',equipment_value = {$value['qty']}, serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";

										$where_ = " AND orderID = {$value['orderID']} ";
										// print_me($temp);
									}
								}
								else
								{
									$temp = "UPDATE dme_order SET order_status = 'confirmed',pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}',equipment_value = {$value['qty']}, serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";

									$where_ = " AND driver_name = '' ";
									// print_me($temp);
								}
							}
							else
							{
								$temp = "UPDATE dme_order SET order_status = 'confirmed',item_num = '{$value['item_num']}',equipment_value = {$value['qty']}, serial_num = {$value['serial_num']}, summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";

								$where_ = " AND orderID = {$value['orderID']} ";
								// print_me($temp);
							}
	      				}

	      				if(!empty($temp))
	      				{
	      					$queries[] = $temp." WHERE medical_record_id = {$temp_medical_id} AND uniqueID = {$uniqueID} AND summary_pickup_date = '0000-00-00' AND equipmentID = {$key} {$where_};";
	      				}
	      				// print_me($queries);
      				}
      				else
      				{
      					// print_me($value);
      					// print_me($value['act_name']);
      					// print_me($value['serial_num']);
      					$new_pickedup_date = '0000-00-00';
      					if($value['pickedup_date'] != "")
      					{
      						$new_pickedup_date = date("Y-m-d", strtotime($value['pickedup_date']));
      					}
      					$summary_pickup_date_here = '0000-00-00';
						$pickup_order_per_key = get_pickup_order_specific_equipment($medical_id,$uniqueID,$summary_pickup_date_here,$key);

						$count_inside = 1;
						foreach ($pickup_order_per_key as $loop_inside)
						{
							if($key == 306)
		      				{
		      					$qty = 1;

								$temp = "UPDATE dme_order SET order_status = 'confirmed',pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}',equipment_value = {$qty}, serial_num = '{$value['serial_num']}', summary_pickup_date = '$new_pickedup_date',driver_name = {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
								// print_me($temp);
		      				}
		      				else if($key == 307)
		      				{
								$temp = "UPDATE dme_order SET order_status = 'confirmed',pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}', serial_num = 'item_options_only', summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
								// print_me($temp);
							}
							else if($key == 308)
							{
								$temp = "UPDATE dme_order SET order_status = 'confirmed',pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}',equipment_value = {$qty_value}, serial_num = 'item_options_only', summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
								// print_me($temp);
							}
							else if($key == 111 || $key == 311)
							{
								$temp = "UPDATE dme_order SET order_status = 'confirmed',pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}', serial_num = 'item_options_only', summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
		      					// print_me($temp);
		      				}
		      				else
		      				{
		      					$result_inside = get_original_serial_number_v2($key, $loop_inside['medical_record_id'], $loop_inside['uniqueID'], $count_inside);
		      					if($key == 312 || $key == 309)
		      					{
		      						$result_uniqueID_inside = get_original_uniqueID_v2(309, $loop_inside['medical_record_id'], $loop_inside['uniqueID'], $count_inside);
		      						$result_equipment_value_inside = get_original_equipment_value_v2($key, $loop_inside['medical_record_id'], $result_uniqueID_inside, $loop_inside['uniqueID']);

		      						if($key == 312)
		      						{
										$temp = "UPDATE dme_order SET order_status = 'confirmed',pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}',equipment_value = '{$result_equipment_value_inside}', serial_num = 'item_options_only', summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
		      							// print_me($temp);
		      						}
		      						else
		      						{
										$temp = "UPDATE dme_order SET order_status = 'confirmed',pickedup_uniqueID = {$value['uniqueID']},item_num = '{$value['item_num']}',equipment_value = '{$result_equipment_value_inside}', serial_num = '{$result_inside}', summary_pickup_date = '$new_pickedup_date',driver_name	= {$value['driver_name']},person_confirming_order = {$value['person_confirming_order']},actual_order_date = {$value['actual_order_date']}";
										// print_me($temp);
		      						}
		      					}
		      					else
		      					{
		      						if(empty($result_inside))
		      						{
		      							$temp_equipment_value = 1;
		      							if(!empty($value['qty']))
		      							{
		      								$temp_equipment_value = $value['qty'];
		      							}
		      							$temp = "UPDATE dme_order SET order_status = 'confirmed', pickedup_uniqueID = {$value['uniqueID']}, item_num = '{$value['item_num']}', equipment_value = {$temp_equipment_value}, serial_num = 'item_options_only', summary_pickup_date = '$new_pickedup_date', driver_name	= {$value['driver_name']}, person_confirming_order = {$value['person_confirming_order']}, actual_order_date = {$value['actual_order_date']}";
		      						}
		      						else
		      						{
		      							$temp_equipment_value = 1;
		      							if(!empty($value['qty']))
		      							{
		      								$temp_equipment_value = $value['qty'];
		      							}
		      							$temp = "UPDATE dme_order SET order_status = 'confirmed', pickedup_uniqueID = {$value['uniqueID']}, item_num = '{$value['item_num']}', equipment_value = {$temp_equipment_value}, serial_num = '{$result_inside}', summary_pickup_date = '$new_pickedup_date', driver_name	= {$value['driver_name']}, person_confirming_order = {$value['person_confirming_order']}, actual_order_date = {$value['actual_order_date']}";
		      						}
									// print_me($temp);
		      					}
								$count_inside++;
								$where_ = " AND orderID = {$loop_inside['orderID']} ";
		      				}

							if(!empty($temp))
		      				{
		      					$queries[] = $temp." WHERE medical_record_id = {$temp_medical_id} AND uniqueID = {$uniqueID} AND summary_pickup_date = '0000-00-00' AND equipmentID = {$key} {$where_};";
		      					$second_queries[] = " UPDATE dme_order SET summary_pickup_date = '{$new_pickedup_date}' WHERE pickedup_uniqueID = {$uniqueID} AND medical_record_id = {$temp_medical_id} AND equipmentID = {$key} AND original_activity_typeid != 2;";
		      				}
		      				// print_me($queries);
						}
      				}
      			}

      			if(!empty($queries))
      			{
      				$this->db->trans_start();
					foreach($queries as $value)
					{
						$this->db->query($value);
					}
					$response = $this->db->trans_complete();

					if($response)
      				{
      					if(!empty($second_queries))
      					{
      						$this->db->trans_start();
							foreach($second_queries as $second_value)
							{
								$this->db->query($second_value);
							}
							$response = $this->db->trans_complete();
      					}
					}
      			}
      		}
      		return $response;
      	}

		function check_pickup_order_of_equipment($uniqueID,$equipmentID)
		{
			$this->db->select('orders.*');
			$this->db->from('dme_order as orders');
			$this->db->where('orders.uniqueID', $uniqueID); //remove this if it will cause errors
			$this->db->where('orders.equipmentID', $equipmentID);
	        $this->db->where('orders.pickedup_uniqueID', 0);

	        $data = $this->db->get()->result_array();

	        return $data;
		}

		function check_pickup_order_of_equipment_v2($uniqueID,$equipmentID)
		{
			$this->db->select('orders.*');
			$this->db->from('dme_order as orders');
			$this->db->where('orders.uniqueID', $uniqueID); //remove this if it will cause errors
			$this->db->where('orders.equipmentID', $equipmentID);
	        $where = "orders.pickedup_uniqueID = '0' OR orders.pickedup_uniqueID = '".$uniqueID."'";
			$this->db->where($where);

	        $data = $this->db->get()->result_array();

	        return $data;
		}

		function check_parent_of_equipment($equipmentID)
		{
			$this->db->select('equipment.parentID');
			$this->db->from('dme_equipment as equipment');
			$this->db->where('equipment.equipmentID', $equipmentID); //remove this if it will cause errors

	        $data = $this->db->get()->first_row('array');

	        return $data;
		}

      	function update_order_summary_confirm_fields_exchange($medical_id, $uniqueID, $data=array())
      	{
      		$response = false;
      		$queries = array();
      		if(!empty($data))
      		{
      			$exclude_from_escape = array(
                    'summary_pickup_date',
                    'pickedup_date',
                    'act_name',
                    'order_date',
                    'actual_order_date'
                );
                $temp_medical_id = $this->db->escape($medical_id);
      			foreach($data['order_summary'] as $f_key=>$first_val)
      			{
      				foreach($first_val as $key=>$value)
      				{
      					if(!empty($value['act_name']))
      					{
      						$temp = "";
		                    $where_first_ = "";
		                    $where_second_ = "";
		                    $temp_uniqueID = "";
		                    $temp_equipmentID = "";
		                    foreach ($value as $inside_key => $inside_value) {
		                        if(!in_array($inside_key, $exclude_from_escape))
		                        {
		                            $value[$inside_key] = $this->db->escape($inside_value);
		                        }

		                        if($inside_value == "")
		                        {
		                            $value[$inside_key] = null;
		                        }
		                    }

		      				if($value['act_name'] == "Delivery" || $value['act_name'] == "CUS Move" || $value['act_name'] == "Exchange")
		      				{
		      					$value['pickedup_date'] = '0000-00-00';
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
		      					$pickup_date = '0000-00-00';
		      				}

		      				if($value['serial_num'] == "---" && $value['uniqueID'] == $uniqueID)
		      				{
		      					$pickup_date_here = date("Y-m-d", strtotime($value['order_date']));
								$temp = "UPDATE dme_order SET order_status = 'confirmed', equipmentID = {$f_key}, activity_typeid = {$value['activity_typeid']}, uniqueID = {$value['uniqueID']}, pickup_date = '{$pickup_date_here}', item_num = '{$value['item_num']}', equipment_value = {$value['qty']}, serial_num = {$value['serial_num']}, summary_pickup_date = '{$pickup_date}', driver_name = {$value['driver_name']}, actual_order_date = '{$value['actual_order_date']}'";
								$temp_uniqueID = $value['uniqueID'];
		                    	$temp_equipmentID = $f_key;
		      				}
		      				else if($value['serial_num'] != "---")
		      				{
		      					$order_actual_order_date = get_order_actual_order_date($value['uniqueID']);

		      					if($order_actual_order_date['actual_order_date'] == "0000-00-00")
		      					{
		      						$pickup_date_here = date("Y-m-d", strtotime($value['order_date']));
									$temp = "UPDATE dme_order SET order_status = 'confirmed', equipmentID = {$f_key}, activity_typeid = {$value['activity_typeid']}, uniqueID = {$value['uniqueID']}, pickup_date = '{$pickup_date_here}', item_num = '{$value['item_num']}', equipment_value = {$value['qty']}, serial_num = {$value['serial_num']}, summary_pickup_date = '0000-00-00', driver_name = {$value['driver_name']}, actual_order_date = '{$value['actual_order_date']}'";
									$temp_uniqueID = $value['uniqueID'];
		                    		$temp_equipmentID = $f_key;
		      					}
		      					else
		      					{
		      						$pickup_date_here = date("Y-m-d", strtotime($value['order_date']));
		      						$temp = "UPDATE dme_order SET order_status = 'confirmed', equipmentID = {$f_key}, activity_typeid = {$value['activity_typeid']}, uniqueID = {$value['uniqueID']}, pickup_date = '{$pickup_date_here}', item_num = '{$value['item_num']}', equipment_value = {$value['qty']}, serial_num = {$value['serial_num']}, summary_pickup_date = '{$pickup_date}', driver_name = {$value['driver_name']}";
		      						$temp_uniqueID = $value['uniqueID'];
		                    		$temp_equipmentID = $f_key;
		      					}
		      				}

		      				$where_first_ = " WHERE medical_record_id = {$temp_medical_id} ";

		  					if($value['activity_typeid'] != 5)
		  					{
		  						$where_second_ = " AND activity_typeid = {$value['activity_typeid']} AND uniqueID IN ({$temp_uniqueID}) ";
		  					}
		  					else
		  					{
		  						$where_second_ = " AND activity_typeid = {$value['activity_typeid']} ";
		  					}

		  					$queries[] = $temp." {$where_first_} {$where_second_} AND equipmentID IN ({$temp_equipmentID}) ;";
      					}
					}
      			}
      			// print_me($queries);
      			if(!empty($queries))
                {
                    $this->db->trans_start();
                    foreach($queries as $value)
                    {
                        $this->db->query($value);
                    }
                    $response = $this->db->trans_complete();
                }
      		}
      		return $response;
      	}

      	function get_equipments_ordered_original($medical_id, $unique_id, $act_id, $hospiceID, $activity_reference_id)
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
	        $this->db->where('equip.parentID', 0);


	        if('orders.equipmentID' == 61)
	        {
	    		$this->db->where('equip.parentID', 'orders.equipmentID');
	        }
	        $this->db->where('orders.uniqueID', $unique_id); //remove this if it will cause errors

	        if($hospiceID)
	        {
	        	$this->db->where('orders.organization_id',$hospiceID);
	        }
	        $this->db->where('orders.serial_num !=', "item_options_only");
	        $this->db->group_by('orders.equipmentID');
	        $this->db->order_by('orders.date_ordered', 'ASC');

	        $data = $this->db->get()->result_array();

	        return $data;
      	}

      	function get_equipments_ordered($medical_id, $unique_id, $act_id, $hospiceID, $activity_reference_id)
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
	        $this->db->where('equip.parentID', 0);


	        if('orders.equipmentID' == 61)
	        {
	    		$this->db->where('equip.parentID', 'orders.equipmentID');
	        }

	        if($act_id != 2)
	        {

	        	if($activity_reference_id)
	        	{
	        		if($activity_reference_id == 3)
		        	{
		        		$this->db->where('orders.pickedup_uniqueID', $unique_id); //remove this if it will cause errors
		        		$this->db->or_where('orders.uniqueID_reference', $unique_id);
		        	}
		        	else
		        	{
		        		$this->db->where('orders.uniqueID', $unique_id); //remove this if it will cause errors
		        	}
	        	}
	        	else
	        	{
	        		$this->db->where('orders.uniqueID', $unique_id); //remove this if it will cause errors
	        	}
	        }
	        else
	        {
	        	$this->db->where('orders.uniqueID', $unique_id);
	        	$this->db->or_where('orders.uniqueID_reference', $unique_id);
	        }
	        if($hospiceID)
	        {
	        	$this->db->where('orders.organization_id',$hospiceID);
	        }
	        $this->db->where('orders.serial_num !=', "item_options_only");
	        $this->db->group_by('orders.equipmentID');
	        $this->db->order_by('orders.date_ordered', 'ASC');

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

      	public function insert_patient_address($data=array())
      	{
      		$response = false;

      		if(!empty($data))
      		{
      			$response = $this->db->insert('dme_patient_address', $data);
      		}
      		if($response)
			{
				$response = $this->db->insert_id();

			}
      		return $response;
      	}

      	public function get_from_order_status($unique_id,$equip_id)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_order as orders');
      		$this->db->where('orders.equipmentID',$equip_id);
      		$this->db->where('orders.activity_typeid !=', 1);
      		$this->db->where('orders.activity_typeid !=', 2);
      		$this->db->group_by('orders.uniqueID');

      		$query = $this->db->get()->result_array();

      		return $query;
      	}

      	public function get_addressID($uniqueID)
      	{
      		$this->db->select('addressID');
      		$this->db->from('dme_order as orders');
      		$this->db->where('orders.uniqueID', $uniqueID);
      		$query = $this->db->get()->first_row('array');

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

      	public function update_addressID($addressID,$address_status)
      	{
      		$this->db->where('id', $addressID);
  			$response = $this->db->update('dme_patient_address', $address_status);
      	}

      	public function get_bipap_option($uniqueID, $equipmentID)
      	{
      		$this->db->select('*');
      		$this->db->from('dme_order as orders');
      		$this->db->join('dme_equipment as equipments','orders.equipmentID=equipments.equipmentID','left');
      		$this->db->where('equipments.parentID', $equipmentID);
      		$this->db->where('orders.uniqueID', $uniqueID);
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

      		$query = $this->db->get()->first_row('array');

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
      		$this->db->select('*');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_patient as patient','orders.medical_record_id=patient.medical_record_id');
			$this->db->where("serial_num",$search_value);
			$this->db->where("original_activity_typeid !=", 2);
			$this->db->where("orders.organization_id !=", 13);
			$this->db->where("serial_num !=","item_options_only");
			$this->db->where("serial_num !=","pickup_order_only");
			$this->db->order_by("pickup_date","DESC");

			$query = $this->db->get();

			return $query->first_row('array');
      	}

      	public function get_item_pickup_row($pickedup_uniqueID)
      	{
      		$this->db->select('*');
			$this->db->from('dme_order as orders');
			$this->db->where("uniqueID", $pickedup_uniqueID);

			$query = $this->db->get();

			return $query->first_row('array');
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

			$this->db->where("orders.organization_id !=", 13);
			if($uniqueID != "" && empty($where['serial']))
			{
				$this->db->where('orders.medical_record_id', $uniqueID);
				$this->db->where('orders.serial_num', $where['serial']);
			}

			if(!empty($where['serial']))
			{
				$this->db->where("orders.serial_num",$where['serial']);
				$this->db->where("orders.original_activity_typeid !=",2);
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
			$this->db->order_by('orders.pickup_date', 'DESC');
			$query = $this->db->get();

			return $query->result_array();
      	}

      	public function item_tracking_load_more_new($serial_num)
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

			$this->db->where("orders.organization_id !=", 13);
			$this->db->where("orders.serial_num",$serial_num);
			$this->db->where("orders.original_activity_typeid !=", 2);

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('orders.pickup_date', 'DESC');
			$this->db->limit(10);
			$query = $this->db->get();

			return $query->result_array();
      	}
		// $this->db->where('orders.serial_num LIKE \'%'.$this->db->escape_str($where['serial']).'%\'', null, false);


		public function search_lot_number($search_value)
      	{
      		$this->db->select('*');
			$this->db->from('dme_order as orders');
			$this->db->join('dme_patient as patient','orders.medical_record_id=patient.medical_record_id');
			$this->db->where("serial_num !=","item_options_only");
			$this->db->where("serial_num !=","pickup_order_only");

			$another_where = "FIND_IN_SET(".$search_value.",orders.serial_num) <> 0";
			$this->db->where($another_where);

			$this->db->group_by('orders.medical_record_id');

			$query = $this->db->get();
			return $query->first_row('array');
      	}
      	// $this->db->where("orders.serial_num",$search_value);
      	// $where = "(orders.serial_num LIKE '%$search_value%')";
      	// $this->db->limit(5);
      	// $this->db->where('equipmentID', 11);
		// $this->db->where('equipmentID >=', 29);
		// $this->db->where('equipmentID <=', 32);
      	// $where = "(lot.lot_number_content LIKE '%$search_value%')";
   		// $this->db->select('*');
		// $this->db->from('dme_order as orders');
		// $this->db->join('dme_patient as patient','orders.medical_record_id=patient.medical_record_id','left');
		// $this->db->join('dme_lot_number as lot','orders.medical_record_id=lot.medical_record_num','left');
		// $this->db->where($where);
		// $this->db->where("serial_num !=","item_options_only");
		// $this->db->where("serial_num !=","pickup_order_only");
		// $this->db->group_by('orders.medical_record_id');

		// $this->db->limit(5);
		// $query = $this->db->get();

		// return $query->result_array();


      	public function list_patients_with_lot_number($where=array())
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

			$this->db->where('orders.organization_id !=', 13);
			$this->db->where('orders.original_activity_typeid !=', 2);

			$another_where = "FIND_IN_SET('".$where['lotNo']."',orders.serial_num) <> 0";
			$this->db->where($another_where);

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
      	}

      	//active patients
		public function list_active_patients_for_lot_number()
		{
			$this->db->select('patient.*, orders.organization_id, orders.medical_record_id');
			$this->db->from('dme_order AS orders');
			$this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
			$this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
			$this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
			$this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
			$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
			$this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');

			//$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$includes = array(1,2);

			if($this->session->userdata('account_type') != 'dme_admin')
			{
				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));
			} else {
				$this->db->where('orders.organization_id !=', 13);
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
		    $this->db->where('orders.serial_num !=','item_options_only');
		    $this->db->where('orders.canceled_from_confirming !=', 1);
		    $this->db->where('orders.canceled_order !=', 1);

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
		}
	}


	// $this->db->where('orders.serial_num LIKE \'%'.$this->db->escape_str($where['lotNo']).'%\'', null, false);
	// $this->db->where('equipments.categoryID !=', 3);
	//End

	// $this->db->select('orders.*,COALESCE((SELECT count(*) FROM dme_order_comments where dme_order_comments.order_uniqueID=orders.uniqueID),0) as comment_count',FALSE);
	// $this->db->select('concat(users.firstname," ", users.lastname) AS creator', FALSE);
	// $this->db->select('patient.*');
	// $this->db->select('act.*');
	// $this->db->select('hosp.*');
	// $this->db->select('equipments.*');
	// $this->db->select('cat.*');
	// $this->db->from('dme_order AS orders');
	// $this->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID','left');
	// $this->db->join('dme_activity_type AS act', 'act.activity_id = orders.activity_typeid','left');
	// $this->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id','left');
	// $this->db->join('dme_user AS users',  'users.userID = orders.ordered_by','left');
	// $this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');
	// $this->db->join('dme_equip_category AS cat',  'cat.categoryID = equipments.categoryID','left');
	// $this->db->join('dme_lot_number as lot','orders.medical_record_id=lot.medical_record_num','left');
	// $includes = array(1,2);


	// if($uniqueID != "" && empty($where['lotNo']))
	// {
	// 	$this->db->where('orders.medical_record_id', $uniqueID);
	// 	$this->db->where('lot.lot_number_content', $where['lotNo']);
	// }

	// if(!empty($where['lotNo']))
	// {
	// 	$this->db->where('lot.lot_number_content LIKE \'%'.$this->db->escape_str($where['lotNo']).'%\'', null, false);
	// 	$this->db->group_by('patient.medical_record_id');
	// 	$this->db->group_by('orders.organization_id');
	// }
	// else
	// {
	// 	$search_string = $where['lotNo'];
	// 	$where = "(lot.lot_number_content LIKE '%$search_string%')";
	// 	$this->db->where($where);
	// 	$this->db->group_by('patient.medical_record_id');
	// 	$this->db->group_by('orders.organization_id');
	// }

	// $this->db->group_by('patient.medical_record_id');
	// $this->db->group_by('orders.organization_id');
	// $this->db->order_by('patient.p_lname', 'ASC');
	// $query = $this->db->get();

	// return $query->result_array();
