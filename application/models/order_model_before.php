<?php

	Class order_model extends Ci_Model

	{

		

		//** Function to auto suggest Patient Medical Record Numbers sa Order Form **//

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



		function transfer_to_order($data)

		{

			$this->db->insert_batch('dme_order', $data);

			return $this->db->insert_id();

		}



		function get_equipment_category()

		{

			$includes = array(1,2);

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



		//sample function 

		function get_equipments_assigned($category_id = '', $hospiceID='')

		{

			$equipment_ids = array();

			if(!empty($hospiceID))

			{

				$equipment_ids = $this->get_assigned_equipments($hospiceID);

			}

			$this->db->select('*');

			$this->db->from('dme_equipment as equip');

			if($category_id != '')

			{	

				$this->db->where_in('equipmentID', $equipment_ids);

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



		//save pickup

		function save($data=array(),$table='dme_pickup')

		{

			$response = false;

			if(!empty($data))

			{

				$response = $this->db->insert($table,$data);

			}

			return $response;

		}



		function list_orders($uniqueID="")

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

			//$this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

			$includes = array(1,2);

			



			// if($this->session->userdata('account_type') != 'dme_admin')

			// {

			// 	$this->db->where('orders.organization_id', $this->session->userdata('group_id'));

			// }

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



				$this->db->where('orders.uniqueID', $uniqueID);

			}

			else

			{

				$this->db->group_by('orders.uniqueID');

			}

 

			$this->db->where('orders.order_status !=', 'confirmed');

			$this->db->order_by('orders.date_ordered', 'DESC'); 

			

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

			if($uniqueID != "") 

			{



				$this->db->where('orders.uniqueID', $uniqueID);

			}

			else 

			{

				$this->db->group_by('orders.uniqueID');

			}



			$this->db->where('orders.order_status', 'confirmed');

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





		function get_order_info($uniqueID)

		{

			$this->db->select('orders.*');

			$this->db->select('hosp.*');

			$this->db->select('users.*');

			$this->db->select('pat.*');

			$this->db->select('act.*');

			$this->db->select('ptmove.*');

			$this->db->select('respite.*');

			$this->db->from('dme_order as orders');

			$this->db->join('dme_hospice as hosp' , 'orders.organization_id = hosp.hospiceID', 'left');

			$this->db->join('dme_user as users', 'users.userID = orders.ordered_by', 'left');

			$this->db->join('dme_patient as pat', 'pat.patientID = orders.patientID', 'left');

			$this->db->join('dme_activity_type act' , 'act.activity_id = orders.activity_typeid', 'left');

			$this->db->join('dme_sub_ptmove ptmove' , 'pat.patientID = ptmove.patientID', 'left');

			$this->db->join('dme_sub_respite respite' , 'pat.patientID = respite.parentID', 'left');

			

			if($this->session->userdata('account_type') != 'dme_admin')

			{

				$this->db->where('orders.organization_id', $this->session->userdata('group_id'));

			}

			

			$this->db->where('orders.uniqueID', $uniqueID);

			$this->db->group_by('orders.uniqueID');



			$query = $this->db->get();



			return $query->result_array();

		}



 

		public function get_orders($unique=0)

        {

                    $this->db->select('equip.*');

                    $this->db->select('orders.*');

                    $this->db->select('COALESCE((SELECT key_desc FROM dme_equipment WHERE dme_equipment.equipmentID=equip.parentID LIMIT 1),"") as parent_name',FALSE);

                    $this->db->select('COALESCE((SELECT option_description FROM dme_options WHERE dme_options.optionID=equip.optionID LIMIT 1),"") as option_description',FALSE);

                     $this->db->select('COALESCE((SELECT type FROM dme_equip_category WHERE dme_equip_category.categoryID=equip.categoryID LIMIT 1),"") as type',FALSE);

                    $this->db->from('dme_order as orders');

                    $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');

                    $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');

                    if($unique != '')

                    {

                        $this->db->where('orders.uniqueID' , $unique);

                    }

                    $this->db->group_by('orders.orderID');

                    $this->db->order_by('equip.categoryID', 'ASC');

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

                                    "order_status"            => "confirmed"

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



        public function delete_order($uniqueID)

        {

    		$this->db->where('uniqueID', $uniqueID);

			$this->db->delete('dme_order'); 

        }



        public function save_to_trash($data)

        {

        	$this->db->insert('trash_table', $data);

			return $this->db->insert_id();

        }



        public function get_trash()

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



        public function joined_decoded_data()

        {

        	$this->db->select('orders.*');

			$this->db->select('pat.*');

			$this->db->from('dme_order as orders');

			$this->db->join('dme_patient as pat', 'pat.patientID = orders.patientID', 'left');

			

			$query = $this->db->get();



			return $query->result_array();

        }

		

		public function change_order_status($uniqueID, $data)

        {

        	$this->db->where('uniqueID', $uniqueID);

		    $this->db->update('dme_order', $data);

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

		

		// public update_delivered_date($data)

		// {

			// $this->db->where('uniqueID', $uniqueID);

		    // $this->db->update('dme_order', $data);

		// }



	}

	