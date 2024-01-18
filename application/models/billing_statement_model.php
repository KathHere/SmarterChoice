<?php
	Class billing_statement_model extends Ci_Model
	{
		

        function list_for_account($account_location)
        {
            $this->db->select('hospice.*');
            $this->db->from('dme_hospice as hospice');

            if ($account_location != 0) { // Added 08/17/2021
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }
            
            $this->db->order_by('hospice.hospice_name', 'ASC');
            $query = $this->db->get();


            return $query->result_array();
        }

        function list_for_account_v2($account_location, $start=0, $limit=10)
        {
            $this->db->start_cache();
            $this->db->select('hospice.*');
            $this->db->from('dme_hospice as hospice');

            if ($account_location != 0) { // Added 08/12/2021
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }
           
            $this->db->where("hospice.account_active_sign", 1); // Added 12/01/2020
            $this->db->order_by('hospice.hospice_name', 'ASC');
            $response['totalAccountCount'] = count($this->db->get()->result_array());
            
            if($limit!=-1)
            {
                $this->db->limit($limit,$start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = count($response['result']);

            $this->db->flush_cache();

            return $response;
        }

        function all_list_for_account()
        {
            $this->db->select('hospice.*');
            $this->db->from('dme_hospice as hospice');
            // $this->db->where("hospice.account_location",$account_location);
            $this->db->order_by('hospice.hospice_name', 'ASC');
            $query = $this->db->get();


            return $query->result_array();
        }

        function get_account($hospice_id) {
            $this->db->select('hospice.*');
            $this->db->from('dme_hospice as hospice');
            $this->db->where("hospice.hospiceID",$hospice_id);
            $query = $this->db->get();


            return $query->first_row('array');
        }

        function get_customer_days($hospice_id) {
            $this->db->select('SUM(patient.patient_days) as cus_days');
            $this->db->from('dme_patient as patient');
            $this->db->where("patient.ordered_by",$hospice_id);
            $query = $this->db->get();


            return $query->result_array();
        }

        function get_customer_days_v2($hospice_id, $service_date_from="", $service_date_to="") {
            $confirmed = "confirmed";
            $this->db->select('SUM(patient.patient_days) as cus_days, patient.patientID');
            $this->db->from('dme_patient as patient');
            // $this->db->where("patient.ordered_by",$hospice_id);
            $this->db->join('dme_order as orders', 'orders.patientID = patient.patientID', 'left');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->where("patient.ordered_by",$hospice_id);
            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.canceled_order ', 0);
            $this->db->where("orders.order_status", $confirmed);
            //New Update 11/15/2019 dont include Miscellaneous ======== START
            $this->db->where('equip.equipmentID !=', 306);
            $this->db->where('equip.equipmentID !=', 309);
            $this->db->where('equip.equipmentID !=', 313);
            $this->db->where('equip.equipmentID !=', 667);
            //New Update 11/15/2019 dont include Miscellaneous ======== END
            // $where = "((equip.categoryID = 1 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR orders.summary_pickup_date= '0000-00-00')) OR (equip.categoryID = 2 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR orders.summary_pickup_date= '0000-00-00')) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";
            $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";
            $this->db->where($where);
            $this->db->group_by("patient.patientID");
            $this->db->order_by('patient.p_lname', 'ASC');
            $query = $this->db->get();


            return $query->result_array();
        }
        
        function get_customer_days_length_of_stay($patientID, $service_date_to, $service_date_from) {
            $this->db->select('*');
            $this->db->from('customer_days_length_of_stay');
            $this->db->where("patientID",$patientID);
            $where = "service_date = '".$service_date_from."' OR service_date = '".$service_date_to."'";
            $this->db->where($where);
            $query = $this->db->get();

            return $query->first_row('array');
        }
        
        function get_all_customer($hospiceID, $start=0, $limit=10, $service_date_from="", $service_date_to="") {
            $this->db->start_cache();

            $confirmed = "confirmed";

            $this->db->select('patient.*');
            $this->db->from('dme_patient as patient');
            $this->db->join('dme_order as orders', 'orders.patientID = patient.patientID', 'left');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            
            $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left'); // change equip2.parentID to equip2.equipmentID 09/12/2019 change back if it will cause an error
            $this->db->where("patient.ordered_by",$hospiceID);
            // $where = "((equip.categoryID = 1 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR orders.summary_pickup_date= '0000-00-00')) OR (equip.categoryID = 2 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR orders.summary_pickup_date= '0000-00-00')) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";
            // $where = "((equip.categoryID = 1 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 1
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 2
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 3
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            $this->db->query('SET SQL_BIG_SELECTS=1');

            $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
            $this->db->where('assigned_equip.hospiceID', $hospiceID);

            $tobeconfirmed = "tobe_confirmed";

            //New Update 11/15/2019 dont include Miscellaneous ======== START
            $this->db->where('equip.equipmentID !=', 306);
            $this->db->where('equip.equipmentID !=', 309);
            $this->db->where('equip.equipmentID !=', 313);
            $this->db->where('equip.equipmentID !=', 667);
            //New Update 11/15/2019 dont include Miscellaneous ======== END
            
            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.canceled_order ', 0);
            $this->db->where("orders.order_status", $confirmed);
            $this->db->where('orders.canceled_from_confirming', 0); // Newly added 09/18/2020 - Remove if it causes error

            //Version 4
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 5
            $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";
            
            $this->db->where($where);
            $this->db->group_by("patient.patientID");
            $this->db->order_by('patient.p_lname ASC, patient.p_fname ASC');
            $response['totalCustomerCount'] = count($this->db->get()->result_array());
            if($limit!=-1)
            {
                $this->db->limit($limit,$start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = count($response['result']);

            $this->db->flush_cache();

            return $response;
        }

        function get_all_customer_for_draft_statement($hospiceID, $start=0, $limit=10, $service_date_from="", $service_date_to="") {
            $this->db->start_cache();

            $confirmed = "confirmed";

            $this->db->select('patient.*');
            $this->db->from('dme_patient as patient');
            $this->db->join('dme_order as orders', 'orders.patientID = patient.patientID', 'left');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            
            $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left'); // change equip2.parentID to equip2.equipmentID 09/12/2019 change back if it will cause an error
            $this->db->where("patient.ordered_by",$hospiceID);

            $this->db->query('SET SQL_BIG_SELECTS=1');

            $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
            $this->db->where('assigned_equip.hospiceID', $hospiceID);

            $tobeconfirmed = "tobe_confirmed";

            //New Update 11/15/2019 dont include Miscellaneous ======== START
            $this->db->where('equip.equipmentID !=', 306);
            $this->db->where('equip.equipmentID !=', 309);
            $this->db->where('equip.equipmentID !=', 313);
            $this->db->where('equip.equipmentID !=', 667);
            //New Update 11/15/2019 dont include Miscellaneous ======== END
            
            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.canceled_order ', 0);
            $this->db->where("orders.order_status", $confirmed);
            $this->db->where('orders.canceled_from_confirming', 0); // Newly added 09/18/2020 - Remove if it causes error

            $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND  orders.actual_order_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND  orders.actual_order_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";
            
            $this->db->where($where);
            $this->db->group_by("patient.patientID");
            $this->db->order_by('patient.p_lname ASC, patient.p_fname ASC');
            $response['totalCustomerCount'] = count($this->db->get()->result_array());
            if($limit!=-1)
            {
                $this->db->limit($limit,$start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = count($response['result']);

            $this->db->flush_cache();

            return $response;
        }

        function customer_order_list($hospiceID, $patientID, $service_date_from="", $service_date_to="")
        {
            $this->db->start_cache();

            $cancel = "cancel";
            $confirmed = "confirmed";

            $this->db->select('equip.key_desc, equip.categoryID, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, equip.is_package as equip_is_package, assigned_equip.ID as assigned_equipmentID');
            $this->db->select('orders.patientID, orders.equipmentID, orders.equipment_value, orders.pickup_date, orders.actual_order_date, orders.equipment_quantity, orders.is_package, orders.summary_pickup_date, orders.pickup_discharge_date, orders.activity_reference, orders.uniqueID_reference, orders.uniqueID, orders.addressID, orders.original_activity_typeid');

            $this->db->from('dme_order as orders');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left'); // change equip2.parentID to equip2.equipmentID 09/12/2019 change back if it will cause an error
            // $this->db->join('dme_order as orders2', 'orders2.equipmentID = equip2.equipmentID', 'left');
            // $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left'); //changed the medical_record_id to patientID
            // $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors
            // $this->db->join('dme_patient_address as patient_address', 'orders.addressID = patient_address.id', 'left');

            $this->db->query('SET SQL_BIG_SELECTS=1');

            $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
            $this->db->where('assigned_equip.hospiceID', $hospiceID);

            $tobeconfirmed = "tobe_confirmed";

            //New Update 11/15/2019 dont include Miscellaneous ======== START
            $this->db->where('equip.equipmentID !=', 306);
            $this->db->where('equip.equipmentID !=', 309);
            $this->db->where('equip.equipmentID !=', 313);
            $this->db->where('equip.equipmentID !=', 667);
            //New Update 11/15/2019 dont include Miscellaneous ======== END

            //New Update 11/25/2019 dont include Oxygen E Cart and E Regulator, and original_activity_typeid = 3 or Exchange Work Orders ======== START
            // $this->db->where('equip.equipmentID !=', 490);
            // $this->db->where('equip.equipmentID !=', 491);
            // $this->db->where('equip.equipmentID !=', 492);
            // $this->db->where('equip.equipmentID !=', 493);
            // $this->db->where('orders.original_activity_typeid !=', 3);
            //New Update 11/25/2019 dont include Oxygen E Cart and E Regulator ======== END

            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.canceled_order ', 0);
            $this->db->where("orders.order_status", $confirmed);
            // $this->db->where("orders.order_status", $tobeconfirmed);
            $this->db->where('orders.patientID', $patientID);
            $this->db->where('orders.canceled_from_confirming', 0); // Newly added 09/18/2020 - Remove if it causes error


            // $where = "((equip.categoryID = 1 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR orders.summary_pickup_date= '0000-00-00')) OR (equip.categoryID = 2 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR orders.summary_pickup_date= '0000-00-00')) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            // $where = "((equip.categoryID = 1 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 1
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";
            
            //Version 2
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 3
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 4
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 5
            $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            $this->db->where($where);

            // if($service_date_from != "") {
            //     $this->db->where('orders.pickup_date >=', $service_date_from);
            // }
            // if($service_date_to != "") {
            //     $this->db->where('orders.pickup_date <=', $service_date_to);
            // }
            // $this->db->where("DATE_FORMAT(orders.summary_pickup_date,'%Y-%m')",$current_month);
            // New Update 07/19/2019 remove if the 0 purchase price, monthly rate, daily rate ======== START ===>UNCOMMENT on DEPLOYING
            // $this->db->where('assigned_equip.monthly_rate !=', 0); 
            // $this->db->where('assigned_equip.daily_rate !=', 0);
            // $this->db->where('assigned_equip.purchase_price !=', 0);
            // New Update 07/19/2019 remove if the 0 purchase price, monthly rate, daily rate ======== END
            
            $this->db->group_by('orders.orderID');
            $this->db->order_by('orders.addressID ASC, orders.actual_order_date, orders.orderID DESC');

            $this->db->stop_cache();

            $this->db->flush_cache();

            return $this->db->get()->result_array();
            // return $response;
        }

        function customer_order_list_for_draft_statement($hospiceID, $patientID, $service_date_from="", $service_date_to="")
        {
            $this->db->start_cache();

            $cancel = "cancel";
            $confirmed = "confirmed";

            // $this->db->select('equip.*, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, equip.is_package as equip_is_package, assigned_equip.ID as assigned_equipmentID');
            // $this->db->select('orders.*');
            $this->db->select('equip.key_desc, equip.categoryID, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, equip.is_package as equip_is_package, assigned_equip.ID as assigned_equipmentID');
            $this->db->select('orders.patientID, orders.equipmentID, orders.equipment_value, orders.pickup_date, orders.actual_order_date, orders.equipment_quantity, orders.is_package, orders.summary_pickup_date, orders.pickup_discharge_date, orders.activity_reference, orders.uniqueID_reference, orders.uniqueID, orders.addressID, orders.original_activity_typeid');

            $this->db->from('dme_order as orders');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left'); // change equip2.parentID to equip2.equipmentID 09/12/2019 change back if it will cause an error

            $this->db->query('SET SQL_BIG_SELECTS=1');

            $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
            $this->db->where('assigned_equip.hospiceID', $hospiceID);

            $tobeconfirmed = "tobe_confirmed";

            //New Update 11/15/2019 dont include Miscellaneous ======== START
            $this->db->where('equip.equipmentID !=', 306);
            $this->db->where('equip.equipmentID !=', 309);
            $this->db->where('equip.equipmentID !=', 313);
            $this->db->where('equip.equipmentID !=', 667);
            //New Update 11/15/2019 dont include Miscellaneous ======== END

            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.canceled_order ', 0);
            $this->db->where("orders.order_status", $confirmed);
            // $this->db->where("orders.order_status", $tobeconfirmed);
            $this->db->where('orders.patientID', $patientID);
            $this->db->where('orders.canceled_from_confirming', 0); // Newly added 09/18/2020 - Remove if it causes error

            $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND  orders.actual_order_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND  orders.actual_order_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            $this->db->where($where);
            
            $this->db->group_by('orders.orderID');
            $this->db->order_by('orders.addressID ASC, orders.actual_order_date, orders.orderID DESC');

            $this->db->stop_cache();

            $this->db->flush_cache();

            return $this->db->get()->result_array();
            // return $response;
        }

        function get_category_total_v2_highcost_patients($hospiceID, $patientID, $service_date_from="", $service_date_to="")
        {
            $this->db->start_cache();

            $cancel = "cancel";
            $confirmed = "confirmed";

            $this->db->select('assigned_equip.equipmentID, orders.is_package, orders.actual_order_date, equip.categoryID, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, orders.summary_pickup_date, orders.pickup_date, orders.equipment_value, orders.equipment_quantity, equip.is_package as equip_is_package');

            $this->db->from('dme_order as orders');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');

            $this->db->query('SET SQL_BIG_SELECTS=1');

            $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
            $this->db->where('assigned_equip.hospiceID', $hospiceID);

            $tobeconfirmed = "tobe_confirmed";

            //New Update 11/15/2019 dont include Miscellaneous ======== START
            $this->db->where('equip.equipmentID !=', 306);
            $this->db->where('equip.equipmentID !=', 309);
            $this->db->where('equip.equipmentID !=', 313);
            $this->db->where('equip.equipmentID !=', 667);
            //New Update 11/15/2019 dont include Miscellaneous ======== END

            //New Update 11/25/2019 dont include Oxygen E Cart and E Regulator, and original_activity_typeid = 3 or Exchange Work Orders ======== START
            // $this->db->where('orders.original_activity_typeid !=', 3);
            //New Update 11/25/2019 dont include Oxygen E Cart and E Regulator ======== END

            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.canceled_order ', 0);
            $this->db->where("orders.order_status", $confirmed);
            $this->db->where('orders.patientID', $patientID);

            //Version 3
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 4
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 5
            $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            $this->db->where($where);
            $this->db->where('equip.categoryID !=', 1);
            $this->db->group_by('orders.orderID');
            $this->db->order_by('orders.addressID ASC, orders.actual_order_date, orders.orderID DESC');

            $this->db->stop_cache();

            $this->db->flush_cache();

            return $this->db->get()->result_array();
        }

        // function get_category_total($hospiceID, $categoryID, $current_month = "")
        // {
        //     $cancel = "cancel";

        //     $this->db->select('SUM(assigned_equip.purchase_price) as test');
            
        //     $this->db->from('dme_order as orders');
        //     $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
        //     $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
        //     $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');

        //     $this->db->query('SET SQL_BIG_SELECTS=1');

        //     $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.

        //     $this->db->where('equip.parentID', 0);
        //     $this->db->where('orders.pickup_order ', 0);
        //     $this->db->where('orders.canceled_order ', 0);
        //     $this->db->where("orders.order_status !=", $cancel);
        //     // $this->db->where('orders.pickup_date >=', $current_month);
        //     // $this->db->where("DATE_FORMAT(orders.summary_pickup_date,'%Y-%m')",$current_month);
        //     // $this->db->where('patients.patientID', $patientID);
        //     $this->db->where('equip.categoryID', $categoryID);
        //     $this->db->group_by('orders.orderID');
        //     $this->db->order_by('orders.pickup_date', 'DESC');

        //     return $this->db->get()->result_array();
        //     // return $response;
        // }

        function get_category_total($hospiceID, $categoryID, $service_date_from="", $service_date_to="")
        {
            $this->db->start_cache();

            $cancel = "cancel";
            $confirmed = "confirmed";

            // $this->db->select('orders.is_package, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, orders.summary_pickup_date, orders.pickup_date, orders.equipment_value, orders.equipment_quantity, equip.is_package as equip_is_package');

            // $this->db->select('equip.*, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, equip.is_package as equip_is_package, assigned_equip.ID as assigned_equipmentID');
            // $this->db->select('orders.*');

            $this->db->select('equip.key_desc, equip.categoryID, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, equip.is_package as equip_is_package, assigned_equip.ID as assigned_equipmentID');
            $this->db->select('orders.patientID, orders.equipmentID, orders.equipment_value, orders.pickup_date, orders.actual_order_date, orders.equipment_quantity, orders.is_package, orders.summary_pickup_date, orders.pickup_discharge_date, orders.activity_reference, orders.uniqueID_reference, orders.uniqueID, orders.addressID, orders.original_activity_typeid');

            $this->db->from('dme_order as orders');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
            // $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left'); //changed the medical_record_id to patientID
            // $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors
            // $this->db->join('dme_patient_address as patient_address', 'orders.addressID = patient_address.id', 'left');

            $this->db->query('SET SQL_BIG_SELECTS=1');

            $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
            $this->db->where('assigned_equip.hospiceID', $hospiceID);

            $tobeconfirmed = "tobe_confirmed";

            //New Update 11/15/2019 dont include Miscellaneous ======== START
            $this->db->where('equip.equipmentID !=', 306);
            $this->db->where('equip.equipmentID !=', 309);
            $this->db->where('equip.equipmentID !=', 313);
            $this->db->where('equip.equipmentID !=', 667);
            //New Update 11/15/2019 dont include Miscellaneous ======== END

            //New Update 11/25/2019 dont include Oxygen E Cart and E Regulator, and original_activity_typeid = 3 or Exchange Work Orders ======== START
            // $this->db->where('equip.equipmentID !=', 490);
            // $this->db->where('equip.equipmentID !=', 491);
            // $this->db->where('equip.equipmentID !=', 492);
            // $this->db->where('equip.equipmentID !=', 493);
            // $this->db->where('orders.original_activity_typeid !=', 3);
            //New Update 11/25/2019 dont include Oxygen E Cart and E Regulator ======== END


            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.canceled_order ', 0);
            // $this->db->where("orders.order_status !=", $cancel);
            $this->db->where("orders.order_status", $confirmed);
            $this->db->where('orders.canceled_from_confirming', 0); // Newly added 09/18/2020 - Remove if it causes error
            // $this->db->where("orders.order_status", $tobeconfirmed);
            // $where = "((equip.categoryID = 1 AND (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')) OR (equip.categoryID = 2 AND (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')) OR (equip.categoryID = 3 AND (orders.pickup_date >= '".$service_date_from."' AND orders.pickup_date <= '".$service_date_to."')))";


            // $where = "((equip.categoryID = 1 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR orders.summary_pickup_date= '0000-00-00')) OR (equip.categoryID = 2 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR orders.summary_pickup_date= '0000-00-00')) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            // $where = "((equip.categoryID = 1 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 1
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";
            
            //Version 2
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 3
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 4
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 5
            $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            $this->db->where($where);
            // if($service_date_from != "") {
            //     $this->db->where('orders.pickup_date >=', $service_date_from);
            // }
            // if($service_date_to != "") {
            //     $this->db->where('orders.pickup_date <=', $service_date_to);
            // }
            // $this->db->where("DATE_FORMAT(orders.summary_pickup_date,'%Y-%m')",$current_month);
            $this->db->where('equip.categoryID', $categoryID);
            $this->db->group_by('orders.orderID');
            $this->db->order_by('orders.addressID ASC, orders.actual_order_date, orders.orderID DESC');

            $this->db->stop_cache();

            $this->db->flush_cache();

            return $this->db->get()->result_array();
            // return $response;
        }

        function get_category_total_for_draft_statement($hospiceID, $categoryID, $service_date_from="", $service_date_to="")
        {
            $this->db->start_cache();

            $cancel = "cancel";
            $confirmed = "confirmed";

            // $this->db->select('orders.is_package, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, orders.summary_pickup_date, orders.pickup_date, orders.equipment_value, orders.equipment_quantity, equip.is_package as equip_is_package');

            // $this->db->select('equip.*, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, equip.is_package as equip_is_package, assigned_equip.ID as assigned_equipmentID');
            // $this->db->select('orders.*');

            $this->db->select('equip.key_desc, equip.categoryID, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, equip.is_package as equip_is_package, assigned_equip.ID as assigned_equipmentID');
            $this->db->select('orders.patientID, orders.equipmentID, orders.equipment_value, orders.pickup_date, orders.actual_order_date, orders.equipment_quantity, orders.is_package, orders.summary_pickup_date, orders.pickup_discharge_date, orders.activity_reference, orders.uniqueID_reference, orders.uniqueID, orders.addressID, orders.original_activity_typeid');

            $this->db->from('dme_order as orders');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
            // $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left'); //changed the medical_record_id to patientID
            // $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors
            // $this->db->join('dme_patient_address as patient_address', 'orders.addressID = patient_address.id', 'left');

            $this->db->query('SET SQL_BIG_SELECTS=1');

            $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
            $this->db->where('assigned_equip.hospiceID', $hospiceID);

            $tobeconfirmed = "tobe_confirmed";

            //New Update 11/15/2019 dont include Miscellaneous ======== START
            $this->db->where('equip.equipmentID !=', 306);
            $this->db->where('equip.equipmentID !=', 309);
            $this->db->where('equip.equipmentID !=', 313);
            $this->db->where('equip.equipmentID !=', 667);
            //New Update 11/15/2019 dont include Miscellaneous ======== END

            //New Update 11/25/2019 dont include Oxygen E Cart and E Regulator, and original_activity_typeid = 3 or Exchange Work Orders ======== START
            // $this->db->where('equip.equipmentID !=', 490);
            // $this->db->where('equip.equipmentID !=', 491);
            // $this->db->where('equip.equipmentID !=', 492);
            // $this->db->where('equip.equipmentID !=', 493);
            // $this->db->where('orders.original_activity_typeid !=', 3);
            //New Update 11/25/2019 dont include Oxygen E Cart and E Regulator ======== END


            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.canceled_order ', 0);
            // $this->db->where("orders.order_status !=", $cancel);
            $this->db->where("orders.order_status", $confirmed);
            $this->db->where('orders.canceled_from_confirming', 0); // Newly added 09/18/2020 - Remove if it causes error

            $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND  orders.actual_order_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND  orders.actual_order_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            $this->db->where($where);
            $this->db->where('equip.categoryID', $categoryID);
            $this->db->group_by('orders.orderID');
            $this->db->order_by('orders.addressID ASC, orders.actual_order_date, orders.orderID DESC');

            $this->db->stop_cache();

            $this->db->flush_cache();

            return $this->db->get()->result_array();
            // return $response;
        }

        function get_category_total_invoice($hospiceID, $service_date_from="", $service_date_to="")
        {
            $this->db->start_cache();

            $cancel = "cancel";
            $confirmed = "confirmed";

            $this->db->select('orders.is_package, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, orders.summary_pickup_date, orders.pickup_date, orders.actual_order_date, orders.equipment_value, orders.equipment_quantity, equip.is_package as equip_is_package, patients.*');

            $this->db->from('dme_order as orders');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');
            $this->db->join('dme_patient as patients', 'orders.patientID = patients.patientID', 'left'); //changed the medical_record_id to patientID
            // $this->db->join('dme_hospice as hospices', 'orders.organization_id = hospices.hospiceID', 'left'); //newly added : remove if it will cause errors
            // $this->db->join('dme_patient_address as patient_address', 'orders.addressID = patient_address.id', 'left');

            $this->db->query('SET SQL_BIG_SELECTS=1');

            $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
            $this->db->where('assigned_equip.hospiceID', $hospiceID);

            $tobeconfirmed = "tobe_confirmed";

            //New Update 11/15/2019 dont include Miscellaneous ======== START
            $this->db->where('equip.equipmentID !=', 306);
            $this->db->where('equip.equipmentID !=', 309);
            $this->db->where('equip.equipmentID !=', 313);
            $this->db->where('equip.equipmentID !=', 667);
            //New Update 11/15/2019 dont include Miscellaneous ======== END

            //New Update 11/25/2019 dont include Oxygen E Cart and E Regulator, and original_activity_typeid = 3 or Exchange Work Orders ======== START
            // $this->db->where('equip.equipmentID !=', 490);
            // $this->db->where('equip.equipmentID !=', 491);
            // $this->db->where('equip.equipmentID !=', 492);
            // $this->db->where('equip.equipmentID !=', 493);
            // $this->db->where('orders.original_activity_typeid !=', 3);
            //New Update 11/25/2019 dont include Oxygen E Cart and E Regulator ======== END


            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.canceled_order ', 0);
            // $this->db->where("orders.order_status !=", $cancel);
            $this->db->where("orders.order_status", $confirmed);
            $this->db->where('orders.canceled_from_confirming', 0); // Newly added 09/18/2020 - Remove if it causes error
            // $this->db->where("orders.order_status", $tobeconfirmed);

            //Version 2
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.actual_order_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 3
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 4
            // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            //Version 5
            $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

            $this->db->where($where);

            $this->db->group_by('orders.orderID');
            // $this->db->order_by('orders.pickup_date', 'DESC');
            $this->db->order_by('orders.addressID ASC, orders.actual_order_date, orders.orderID DESC, patients.p_lname');

            // if($limit!=-1)
            // {
            //     $this->db->limit($limit,$start);
            // }

            $this->db->stop_cache();

            // $response['limit'] = $limit;
            // $response['start'] = $start;
            // $response['result'] = $this->db->get()->result_array();
            // $response['totalCount'] = $this->db->get()->num_rows();

            $this->db->flush_cache();

            return $this->db->get()->result_array();
            // return $response;
        }

        function has_customer_for_draft_statement($hospiceID, $service_date_from="", $service_date_to="") {
            $confirmed = "confirmed";

            $this->db->select('patient.*');
            $this->db->from('dme_patient as patient');
            $this->db->join('dme_order as orders', 'orders.patientID = patient.patientID', 'left');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            
            $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left'); // change equip2.parentID to equip2.equipmentID 09/12/2019 change back if it will cause an error
            $this->db->where("patient.ordered_by",$hospiceID);

            $this->db->query('SET SQL_BIG_SELECTS=1');

            $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
            $this->db->where('assigned_equip.hospiceID', $hospiceID);

            $tobeconfirmed = "tobe_confirmed";

            //New Update 11/15/2019 dont include Miscellaneous ======== START
            $this->db->where('equip.equipmentID !=', 306);
            $this->db->where('equip.equipmentID !=', 309);
            $this->db->where('equip.equipmentID !=', 313);
            $this->db->where('equip.equipmentID !=', 667);
            //New Update 11/15/2019 dont include Miscellaneous ======== END
            
            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 0);
            $this->db->where('orders.canceled_order ', 0);
            $this->db->where("orders.order_status", $confirmed);
            $this->db->where('orders.canceled_from_confirming', 0); // Newly added 09/18/2020 - Remove if it causes error

            $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND  orders.actual_order_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND  orders.actual_order_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";
            
            $this->db->where($where);
            $this->db->group_by("patient.patientID");
            $this->db->order_by('patient.p_lname ASC, patient.p_fname ASC');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function insert_statement_bill($data=array()) {
            $response = false;

            if(!empty($data)) {
                $save_info = $this->db->insert('dme_account_statement_bill', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }
            return $response;
        }

        function remove_statement_bill() {
            $this->db->truncate('dme_account_statement_bill');
        }

        function get_statement_bill_with_hospice_details() {
            $this->db->select('hospice.*, statement_bill.*, hospice.hospiceID as accountID');
            $this->db->from('dme_hospice as hospice');
            $this->db->join('dme_account_statement_bill as statement_bill', 'statement_bill.hospiceID = hospice.hospiceID', 'left');
            $this->db->where("hospice.account_active_sign", 1); // Active Hospice
            // $this->db->where("hospice.account_active_sign !=", 2); // Suspenden Hospice
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_statement_bill_by_hospice($hospice_id) {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_bill as statement_bill');
            $this->db->where("statement_bill.hospiceID",$hospice_id);
            // $this->db->order_by('statement_bill.service_date_from', 'DESC');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_statement_bill_draft_by_hospice($hospice_id, $order_by) {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_draft as statement_bill');
            $this->db->where("statement_bill.hospiceID",$hospice_id);
            $this->db->order_by('statement_bill.service_date_to', $order_by);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_statement_bill_invoice_by_hospice($hospice_id, $order_by) {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->where("statement_bill.hospiceID",$hospice_id);
            $this->db->where("statement_bill.receive_status",0);
            $this->db->order_by('statement_bill.service_date_to', $order_by);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_all_statement_bill_draft_by_hospice($hospice_id, $order_by) {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_draft as statement_bill');
            $this->db->where("statement_bill.hospiceID",$hospice_id);
            $this->db->order_by('statement_bill.service_date_to', $order_by);
            $query = $this->db->get();

            return $query->result_array();
        }

        function update_statement_bill($bill_id, $data=array()) {
            $this->db->where('acct_statement_bill_id', $bill_id);
            $response = $this->db->update('dme_account_statement_bill', $data);

            return $response;
        }

        function update_statement_bill_draft($draft_id, $data=array()) {
            $this->db->where('acct_statement_draft_id', $draft_id);
            $response = $this->db->update('dme_account_statement_draft', $data);

            return $response;
        }

        function remove_statement_bill_draft($draft_id) {
            $this->db->where('acct_statement_draft_id', $draft_id);
            $this->db->delete('dme_account_statement_draft');
        }

        function insert_statement_draft($data=array()) {
            $response = false;

            if(!empty($data)) {
                $save_info = $this->db->insert('dme_account_statement_draft', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }
            return $response;
        }

        function get_all_statement_bill_draft ($account_location) {
            // $this->db->select('statement_bill.*');
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_draft as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            // $this->db->where("statement_bill.hospiceID",$hospice_id);

            if ($account_location != 0) { // Added 08/17/2021
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }

            $this->db->order_by('hospice.hospice_name', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_all_statement_bill_draft_by_hospice_v2($filters = false, $account_location, $start = 0, $limit = 0) {
            $this->db->start_cache();
            if ($filters != false) {
                $this->load->library('orm/filters');
                $this->filters->detect('statement_draft', $filters);
            }
            
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_draft as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            // $this->db->where("statement_bill.hospiceID",15);

            if ($account_location != 0) { // Added 08/12/2021
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }
            
            $this->db->order_by('hospice.hospice_name', 'ASC');
            $this->db->order_by('statement_bill.service_date_from', 'ASC');

            if ($limit != -1) {
                $this->db->limit($limit, $start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = $this->db->get()->num_rows();

            // echo $this->db->last_query();

            $this->db->flush_cache();

            return $response;
        }

        function get_draft_details ($draft_id) {
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_draft as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where("statement_bill.acct_statement_draft_id",$draft_id);
            $this->db->order_by('hospice.hospice_name', 'ASC');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function is_all_order_confirmed ($hospiceID, $current_month = "") {
            $cancel = "cancel";
            $confirmed = "confirmed";
            $this->db->select('orders.orderID, orders.order_status');
            $this->db->from('dme_order as orders');
            $this->db->where('orders.organization_id', $hospiceID);
            $this->db->where("orders.order_status !=", $cancel);
            $this->db->where("orders.order_status !=", $confirmed);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function is_all_order_confirmed_v2 ($hospiceID, $service_date_from, $service_date_to) {
            $cancel = "cancel";
            $confirmed = "confirmed";
            $tobeconfirmed = "tobe_confirmed";
            $this->db->select('orders.orderID, orders.order_status, orders.actual_order_date');
            $this->db->from('dme_order as orders');
            $this->db->where('orders.organization_id', $hospiceID);
            $this->db->where("orders.order_status !=", $cancel);
            // $this->db->where("orders.order_status !=", $confirmed);
            $this->db->where("orders.order_status =", $tobeconfirmed);

            //Version 2
            $where = "orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."'";
            $this->db->where($where);
            
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function insert_statement_invoice($data=array()) {
            $response = false;

            if(!empty($data)) {
                $save_info = $this->db->insert('dme_account_statement_invoice', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }
            return $response;
        }

        function get_current_statement_invoice($hospiceID, $statement_no, $service_date_from, $service_date_to) {
            $this->db->select('statement_bill.acct_statement_invoice_id');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->where("statement_bill.hospiceID", $hospiceID);
            $this->db->where("statement_bill.statement_no", $statement_no);
            $this->db->where("statement_bill.service_date_from", $service_date_from);
            $this->db->where("statement_bill.service_date_to", $service_date_to);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_all_statement_activity($account_location) {
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            // $this->db->where("statement_bill.acct_statement_draft_id",$draft_id);
            $this->db->where("statement_bill.receive_status",0);

            if ($account_location != 0) { // Added 08/17/2021
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }
            
            $this->db->order_by('hospice.hospice_name', 'ASC');
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_all_statement_letter_list($account_location, $hospiceID=null) {

            $date_from = date('Y-m-d', strtotime('-30 days'));
            $date_today = date('Y-m-d');

            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where("statement_bill.receive_status",0);

            if ($account_location != 0) {
                $this->db->where("hospice.account_location",$account_location);
            } else {
                $this->db->where("hospice.account_location !=",0);
            }

            if (!empty($hospiceID) && $hospiceID != null) {
                $this->db->where("statement_bill.hospiceID",$hospiceID);
                $this->db->where('statement_bill.due_date <=', $date_today);
            } else {
                $this->db->where('statement_bill.due_date <=', $date_today);

                $this->db->group_by('hospice.hospiceID');
                $this->db->order_by('hospice.hospice_name', 'ASC');
            }

            // $this->db->where('statement_bill.due_date <', $date_from);
            
            $query = $this->db->get();

            return $query->result_array();
        }

        function insert_statement_order($data=array()) {
            $response = false;

            if(!empty($data)) {
                $save_info = $this->db->insert('dme_account_statement_order_summary', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }
            return $response;
        }

        function get_all_orderSummary_statement_activity($acct_statement_invoice_id, $hospice_id, $start=0, $limit=50) {
            $this->db->start_cache();

            $this->db->select('statement_bill.*, hospice.*, patient.*');
            $this->db->from('dme_account_statement_order_summary as statement_bill');
            $this->db->join('dme_account_statement_invoice as invoice', 'invoice.acct_statement_invoice_id = statement_bill.acct_statement_invoice_id', 'left');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = invoice.hospiceID', 'left');
            $this->db->join('dme_patient as patient', 'patient.patientID = statement_bill.patientID', 'left');
            $this->db->where('invoice.acct_statement_invoice_id', $acct_statement_invoice_id);
            $this->db->where('invoice.hospiceID', $hospice_id);
            $response['totalCustomerCount'] = count($this->db->get()->result_array());
            if($limit!=-1)
            {
                $this->db->limit($limit,$start);
            }

            $this->db->stop_cache();

            $response['limit'] = $limit;
            $response['start'] = $start;
            $response['result'] = $this->db->get()->result_array();
            $response['totalCount'] = count($response['result']);

            $this->db->flush_cache();

            return $response;
        }

        function get_statement_activity_details($acct_statement_invoice_id) {
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where("statement_bill.acct_statement_invoice_id",$acct_statement_invoice_id);
            $query = $this->db->get();

            return $query->result_array();
        }

        function remove_statement_activity_orderSummary($acct_statement_invoice_id) {
            $this->db->where('acct_statement_invoice_id', $acct_statement_invoice_id);
            $this->db->delete('dme_account_statement_order_summary');
        }

        function remove_statement_activity($acct_statement_invoice_id) {
            $this->db->where('acct_statement_invoice_id', $acct_statement_invoice_id);
            $this->db->delete('dme_account_statement_invoice');
        }

        function remove_all_statement_activity_orderSummary() {
            $this->db->empty_table('dme_account_statement_order_summary');
        }

        function insert_statement_letter($data=array()) {
            $response = false;

            if(!empty($data)) {
                $save_info = $this->db->insert('dme_account_statement_letter', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }
            return $response;
        }

        function get_all_statement_letter(){
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_letter as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_statement_letter_details($acct_statement_letter_id){
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_letter as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.acct_statement_letter_id', $acct_statement_letter_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function delete_statement_letter($acct_statement_letter_id, $hospiceID) {
            $response = false;

        	if(!empty($acct_statement_letter_id) && !empty($hospiceID))
        	{
        		$this->db->where('acct_statement_letter_id', $acct_statement_letter_id);
        		$this->db->where('hospiceID', $hospiceID);
				$response = $this->db->delete('dme_account_statement_letter');
        	}
        	return $response;
        }

        function get_past_due_invoice ($hospice_id) {
            $date_today = date("Y-m-d");
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.due_date <', $date_today);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_past_due_invoice_v2 ($hospice_id) {
            $date_today = date("Y-m-d");
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.due_date <', $date_today);
            $this->db->where('statement_bill.receive_status', 0);
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_past_due_invoice_v3($hospice_id, $acct_statement_invoice_id) {

            $date_today = date("Y-m-d");
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.due_date <', $date_today);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            if($acct_statement_invoice_id != null) {
                $this->db->where('statement_bill.acct_statement_invoice_id !=', $acct_statement_invoice_id);
            }
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_past_due_invoice_v4($hospice_id, $acct_statement_invoice_id) {

            $date_today = date("Y-m-d");
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.due_date <', $date_today);
            // $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            if($acct_statement_invoice_id != null) {
                $this->db->where('statement_bill.acct_statement_invoice_id !=', $acct_statement_invoice_id);
            }
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_payment_due_invoice ($hospice_id) {
            $date_today = date("Y-m-d");
            $this->db->select('statement_bill.*, hospice.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.due_date >=', $date_today);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_hospice_details ($hospice_id) {
            $this->db->select('hospice.*');
            $this->db->from('dme_hospice as hospice');
            $this->db->where('hospice.hospiceID', $hospice_id);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_last_payment_by_hospice ($hospice_id) {
            $this->db->select('SUM(statement_bill.payment_amount) as payment_amount, statement_bill.payment_date');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            // $this->db->where('statement_bill.payment_code IS NOT NULL');
            $this->db->where("statement_bill.receive_status", 1);
            $this->db->group_by('statement_bill.payment_code'); 
            $this->db->order_by('statement_bill.payment_date', 'desc');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_last_payment_by_current ($hospice_id) {
            // $this->db->select('SUM(statement_bill.payment_amount) as payment_amount, statement_bill.payment_date');
            $this->db->select('statement_bill.payment_amount, statement_bill.receive_date as payment_date');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            // $this->db->where('statement_bill.payment_code IS NOT NULL');
            $this->db->where("statement_bill.receive_status", 1);
            // $this->db->group_by('statement_bill.payment_code'); 
            $this->db->order_by('statement_bill.acct_statement_invoice_id', 'desc');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_last_payment_by_current_v2 ($hospice_id) {
            // $this->db->select('SUM(statement_bill.payment_amount) as payment_amount, statement_bill.payment_date');
            $this->db->select('SUM(statement_bill.payment_amount) as payment_amount, statement_bill.receive_date as payment_date');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            // $this->db->where('statement_bill.payment_code IS NOT NULL');
            $this->db->where("statement_bill.receive_status", 1);
            $this->db->group_by('statement_bill.receive_date');
            $this->db->order_by('statement_bill.acct_statement_invoice_id', 'desc');
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function update_statement_bill_invoice($invoice_id, $data=array()) {
            $this->db->where('acct_statement_invoice_id', $invoice_id);
            $response = $this->db->update('dme_account_statement_invoice', $data);

            return $response;
        }

        function get_pending_payments_by_hospice ($hospice_id) {
            $this->db->select('SUM(statement_bill.payment_amount) as payment_amount, statement_bill.payment_date');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.payment_code IS NOT NULL');
            $this->db->where('statement_bill.receive_status', 0);
            
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_total_payment_due_by_hospice ($hospice_id) {
            // $this->db->group_by('statement_bill.payment_code'); 
            // $this->db->order_by('statement_bill.payment_date', 'desc');
            $date_today = date("Y-m-d");
            $this->db->select('SUM(statement_bill.total) as totaltotal, SUM(statement_bill.non_cap) as totalnoncap, SUM(statement_bill.purchase_item) as totalpurchaseitem');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.due_date >=', $date_today);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_all_payment_due_by_hospice ($hospice_id) {
            $date_today = date("Y-m-d");
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.due_date >=', $date_today);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_total_balance_due_by_hospice ($hospice_id) {
            $date_today = date("Y-m-d");
            $this->db->select('SUM(statement_bill.total) as total_balance_due');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.due_date <', $date_today);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_total_balance_due_by_hospice_v2 ($hospice_id) {
            $date_today = date("Y-m-d");
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.due_date <', $date_today);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_all_balance_due_by_hospice ($hospice_id) {
            $date_today = date("Y-m-d");
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.due_date <', $date_today);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_past_due_amount($hospice_id, $acct_statement_invoice_id) {

            $date_today = date("Y-m-d");
            $this->db->select('SUM(statement_bill.total) as totaltotal, SUM(statement_bill.non_cap) as totalnoncap, SUM(statement_bill.purchase_item) as totalpurchaseitem');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            // $this->db->where('statement_bill.due_date <', $date_today);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            if($acct_statement_invoice_id != null) {
                $this->db->where('statement_bill.acct_statement_invoice_id !=', $acct_statement_invoice_id);
            }
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_past_due_amount_v2($hospice_id, $past_due) {
            switch($past_due) {
                case 'current':
                    $date_to = date("Y-m-d");
                case '30':
                    $date_to = date("Y-m-d");
                    $date_from = date('Y-m-d', strtotime('-30 days'));
                break;
                case '60':
                    $date_to = date('Y-m-d', strtotime('-30 days'));
                    $date_from = date('Y-m-d', strtotime('-60 days'));
                break;
                case '90':
                    $date_to = date('Y-m-d', strtotime('-60 days'));
                    $date_from = date('Y-m-d', strtotime('-90 days'));
                break;
                case '91':
                    $date_from = date('Y-m-d', strtotime('-90 days'));
                break;
            };
            
            if ($past_due == 'current') {
                $this->db->select('statement_bill.total as totaltotal, statement_bill.non_cap as totalnoncap, statement_bill.purchase_item as totalpurchaseitem');
            } else {
                $this->db->select('SUM(statement_bill.total) as totaltotal, SUM(statement_bill.non_cap) as totalnoncap, SUM(statement_bill.purchase_item) as totalpurchaseitem');
            }
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            if ($past_due == 'current') {
                $this->db->where('statement_bill.due_date >=', $date_to);
            } else if ($past_due != '91') {
                $this->db->where('statement_bill.due_date <', $date_to);
                $this->db->where('statement_bill.due_date >', $date_from);
            } 
            else {
                $this->db->where('statement_bill.due_date <', $date_from);
            }
            $this->db->where('statement_bill.payment_code is NULL', NULL, TRUE);
            $this->db->where('statement_bill.receive_status', 0);
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_current_invoice_by_hospice($hospice_id) {
            $date_today = date("Y-m-d");
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.due_date >=', $date_today);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_all_invoice_by_hospice($hospice_id) {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_invoice as statement_bill');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = statement_bill.hospiceID', 'left');
            $this->db->where('statement_bill.hospiceID', $hospice_id);
            $this->db->where('statement_bill.payment_code IS NULL');
            $this->db->where('statement_bill.receive_status', 0);
            $query = $this->db->get();

            return $query->result_array();
        }

        function get_all_current_reconciliation_balance_and_owe($hospice_id) {
            $this->db->select('statement_bill.*');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            $where = "(statement_bill.invoice_reference = 0 OR statement_bill.invoice_reference IS NULL)";
            $this->db->where($where);
            // $this->db->where('statement_bill.invoice_reference IS NULL');
            // $this->db->where('statement_bill.invoice_reference', 0);
            $this->db->where('statement_bill.draft_reference IS NULL');
            $this->db->where('statement_bill.hospiceID ', $hospice_id);

            $query = $this->db->get();

            return $query->result_array();
        }

        function update_statement_reconciliation($acct_statement_reconciliation_id, $data=array()) {
            $this->db->where('acct_statement_reconciliation_id', $acct_statement_reconciliation_id);
            $response = $this->db->update('dme_account_statement_reconciliation', $data);

            return $response;
        }

        function update_statement_reconciliation_by_draft($draft_id, $data=array()) {
            $this->db->where('draft_reference', $draft_id);
            $response = $this->db->update('dme_account_statement_reconciliation', $data);

            return $response;
        }

        function update_statement_reconciliation_by_draft_reference($draft_reference, $data=array()) {
            $this->db->where('draft_reference', $draft_reference);
            $response = $this->db->update('dme_account_statement_reconciliation', $data);

            return $response;
        }

        function remove_invoice_reference($invoice_reference, $data) {
            $this->db->where('invoice_reference', $invoice_reference);
            $response = $this->db->update('dme_account_statement_reconciliation', $data);

            return $response;
        }

        function get_invoice_reconciliation_balance_and_owe_by_draft($draft_id) {
            $this->db->select('SUM(statement_bill.credit) as credit, SUM(statement_bill.balance_owe) as owe');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            $this->db->where('statement_bill.draft_reference ', $draft_id);
            
            $query = $this->db->get();

            return $query->first_row('array');
        }

        function get_invoice_reconciliation_balance_and_owe_by_invoice($invoice_id) {
            $this->db->select('SUM(statement_bill.credit) as credit, SUM(statement_bill.balance_owe) as owe');
            $this->db->from('dme_account_statement_reconciliation as statement_bill');
            $this->db->where('statement_bill.invoice_reference ', $invoice_id);
            
            $query = $this->db->get();

            return $query->first_row('array');
        }

        /** ================= START ===================
         * For Customer Days Script Draft Statement
         * Author: Adrian
         * Date Added: 09/08/2020
         **/
        function get_all_patient_pickup($patientID, $service_date_from="", $service_date_to="")
        {
            $confirmed = "confirmed";
            $this->db->select('*');

            $this->db->from('dme_order as orders');
            $this->db->join('dme_pickup_tbl as pickup', 'pickup.order_uniqueID = orders.uniqueID','left');

            $this->db->where('orders.canceled_order ', 0);
            $this->db->where("orders.order_status", $confirmed);
            $this->db->where('orders.patientID', $patientID);
            $this->db->where('orders.original_activity_typeid', 2);
            $where = "orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."'";
            $this->db->where($where);
            
            $this->db->group_by('orders.uniqueID');
            $this->db->order_by('orders.orderID DESC');

            return $this->db->get()->result_array();
        }

        function check_order_after_all_pickup($orderID, $uniqueID, $patientID, $service_date_from="", $service_date_to="")
        {
            $this->db->select('equipments.*, orders.*');

            $this->db->from('dme_order as orders');
            $this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

            $this->db->where('orders.orderID >', $orderID);
            $this->db->where('orders.patientID', $patientID);
            $this->db->where('orders.uniqueID !=', $uniqueID);
            $where = "orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."'";
            $this->db->where($where);
            
            $this->db->order_by('orders.orderID DESC');

            $query = $this->db->get();

            return $query->first_row('array');
        }

        function check_order_after_all_pickup_v2($orderID, $uniqueID, $patientID, $service_date_from="", $service_date_to="")
        {
            $this->db->select('equipments.*, orders.*');

            $this->db->from('dme_order as orders');
            $this->db->join('dme_equipment AS equipments',  'equipments.equipmentID = orders.equipmentID','left');

            $this->db->where('orders.orderID >', $orderID);
            $this->db->where('orders.patientID', $patientID);
            $this->db->where('orders.uniqueID !=', $uniqueID);
            $where = "orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."'";
            $this->db->where($where);
            
            $this->db->order_by('orders.orderID ASC');

            $query = $this->db->get();

            return $query->first_row('array');
        }

        function insert_customer_length_of_stay($data=array()) {
            $response = false;

            if(!empty($data)) {
                $response = $this->db->insert_batch('customer_days_length_of_stay',$data);
                // $save_info = $this->db->insert('customer_days_length_of_stay', $data);
            }
            return $response;
        }

        function get_all_patients_for_script($hospiceID)
		{
			$this->db->select('patientID,date_created,ordered_by,patient_days');
            $this->db->from('dme_patient');
            $this->db->where('ordered_by', $hospiceID);
			// $this->db->order_by('cast(ordered_by as SIGNED)', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
        }
        
        public function list_active_patients_scripts_for_script($hospiceID)
		{
			$this->db->select('patient.patientID, orders.organization_id, orders.medical_record_id, patient.ordered_by');
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
            $this->db->where('patient.ordered_by', $hospiceID);

			$this->db->group_by('patient.medical_record_id');
			$this->db->group_by('orders.organization_id');
			//$this->db->where('orders.order_status !=', 'confirmed');
			//$this->db->order_by('orders.date_ordered', 'ASC');
			$this->db->order_by('patient.p_lname', 'ASC');
			$query = $this->db->get();

			return $query->result_array();

		}
        /**
         * For Customer Days Script Draft Statement
         * Author: Adrian
         * Date Added: 09/08/2020
         *  ================= END ===================
         **/


        function insert_invoice_statement_logs($data=array()) {
            $response = false;

            if(!empty($data)) {
                $save_info = $this->db->insert('dme_account_statement_invoice_email_logs', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }
            return $response;
        }

        function save_customer_days_batch($data=array())
		{
			$response = false;

			if(!empty($data)) {
                $response = $this->db->insert_batch('customer_days_length_of_stay',$data);
			}
			return $response;
        }
        
        function save_customer_days($data=array())
		{
			if(!empty($data)) {
                $save_info = $this->db->insert('customer_days_length_of_stay', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }
            return $response;
        }
        
        function fix_customer_days_length_of_stay_fix($hospiceID, $data) {
            $this->db->where("hospiceID", $hospiceID);
            $this->db->where("service_date", "2020-10-01");
            $response = $this->db->update('customer_days_length_of_stay', $data);

            return $response;
        }

        function get_all_patients_for_script_inactive($hospiceID)
		{
			$this->db->select('patientID,date_created,ordered_by,patient_days');
            $this->db->from('dme_patient');
            $this->db->where('ordered_by', $hospiceID);
            $this->db->where('is_active', 0);
			// $this->db->order_by('cast(ordered_by as SIGNED)', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
        }

        function get_pickuporder($patientID, $service_date_from, $service_date_to) {
            $this->db->select('pickup_discharge_date');
            $this->db->from('dme_order');
            $this->db->where('patientID', $patientID);
            $this->db->where('pickup_order ', 1);
            $this->db->where('canceled_order ', 0);
            $this->db->where("order_status", 'confirmed');
            $where = "actual_order_date >='".$service_date_from."' AND actual_order_date <= '".$service_date_to."'";
            $this->db->where($where);
			$this->db->order_by('orderID', 'desc');
			$query = $this->db->get();

			return $query->first_row('array');
        }

        function update_customer_days_length_of_stay($cus_days_los_id, $data) {
            $this->db->where("cus_days_los_id", $cus_days_los_id);
            $response = $this->db->update('customer_days_length_of_stay', $data);

            return $response;
        }

        function get_all_patient_for_draft_statement() {
            $this->db->select('patientID,date_created,ordered_by,patient_days');
			$this->db->from('dme_patient');
			// $this->db->order_by('cast(ordered_by as SIGNED)', 'ASC');
			$query = $this->db->get();

			return $query->result_array();
        }

        function change_cus_days_draft_statement($data, $patientID, $service_date) {
            $this->db->where("patientID", $patientID);
            $this->db->where("service_date", $service_date);
            $response = $this->db->update('customer_days_length_of_stay', $data);

            return $response;
        }

        function get_patients_info($patientID)
		{
			$this->db->select('patientID,ordered_by');
            $this->db->from('dme_patient');
            $this->db->where('patientID', $patientID);
			// $this->db->order_by('cast(ordered_by as SIGNED)', 'ASC');
			$query = $this->db->get();

			return $query->first_row('array');
        }
	}
?>