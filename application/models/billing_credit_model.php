<?php
	Class billing_credit_model extends Ci_Model
	{
        function insert_billing_credit($data=array()) {
            $response = false;
    
            if(!empty($data)) {
                $save_info = $this->db->insert('dme_account_statement_credit', $data);
                if ($save_info) {
                    $response = $this->db->insert_id();
                }
            }
            return $response;
        }

        function get_total_billing_credit($hospiceID, $service_date_from, $service_date_to) {
            $this->db->select('SUM(credit.credit) as total_billing_credit');
            $this->db->from('dme_account_statement_credit as credit');
            $this->db->where('credit.hospiceID', $hospiceID);
            $where = "(credit.service_date >= '".$service_date_from."' AND credit.service_date <= '".$service_date_to."')";
            $this->db->where($where);
			$query = $this->db->get();

			return $query->first_row('array');
        }

        function get_billing_credit_notes($hospiceID, $service_date_from, $service_date_to) {
            $this->db->select('credit.notes');
            $this->db->from('dme_account_statement_credit as credit');
            $this->db->where('credit.hospiceID', $hospiceID);
            $where = "(credit.service_date >= '".$service_date_from."' AND credit.service_date <= '".$service_date_to."')";
            $this->db->where($where);
			$query = $this->db->get();

			return $query->result_array();
        }

        function get_patient_info($patientID) {
            $this->db->select('patient.*, hospice.*');
            $this->db->from('dme_patient as patient');
            $this->db->join('dme_hospice as hospice', 'hospice.hospiceID = patient.ordered_by', 'left');
            $this->db->where('patient.patientID', $patientID);
			$query = $this->db->get();

			return $query->first_row('array');
        }

        function customer_order_list($uniqueID, $hospiceID, $patientID)
        {
            $this->db->start_cache();
            $confirmed = "confirmed";

            $this->db->select('equip.key_desc, equip.categoryID, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, equip.is_package as equip_is_package, assigned_equip.ID as assigned_equipmentID');
            $this->db->select('orders.patientID, orders.equipmentID, orders.equipment_value, orders.pickup_date, orders.actual_order_date, orders.equipment_quantity, orders.is_package, orders.summary_pickup_date, orders.pickup_discharge_date, orders.activity_reference, orders.uniqueID_reference, orders.uniqueID, orders.addressID, orders.original_activity_typeid');

            $this->db->from('dme_order as orders');
            $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
            $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
            $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left'); // change equip2.parentID to equip2.equipmentID 09/12/2019 change back if it will cause an error

            $this->db->query('SET SQL_BIG_SELECTS=1');

            $this->db->where('orders.uniqueID', $uniqueID); 
            $this->db->where('assigned_equip.hospiceID', $hospiceID);

            //New Update 11/15/2019 dont include Miscellaneous ======== START
            $this->db->where('equip.equipmentID !=', 306);
            $this->db->where('equip.equipmentID !=', 309);
            $this->db->where('equip.equipmentID !=', 313);
            $this->db->where('equip.equipmentID !=', 667);
            //New Update 11/15/2019 dont include Miscellaneous ======== END

            $this->db->where('equip.parentID', 0);
            $this->db->where('orders.pickup_order ', 1);
            $this->db->where('orders.canceled_order ', 0);
            $this->db->where("orders.order_status", $confirmed);
            $this->db->where('orders.patientID', $patientID);
            $this->db->where('orders.canceled_from_confirming', 0); // Newly added 09/18/2020 - Remove if it causes error

            
            $this->db->group_by('orders.orderID');
            $this->db->order_by('orders.addressID ASC, orders.actual_order_date, orders.orderID DESC');

            $this->db->stop_cache();

            $this->db->flush_cache();

            return $this->db->get()->result_array();
        }
    }
?>