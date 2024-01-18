<?php
Class Mreport extends CI_Model
{
    var $response = false;
    public function get_patient_count($filters=false,$page=false,$limitPerPage=false)
    {
        $current_date = date('Y-m-d');
        //using database caching
        $this->db->start_cache();
        //check if there is filter
        if($filters!=false)
        {
            $this->load->library('orm/filters');
            $this->filters->detect('fpatient_report',$filters);
        }

        if($filters['current_date']['sign'] == 1)
        {
            $this->db->select("pat.*,orders.*");
            $this->db->from("dme_patient as pat");
            $this->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
            $this->db->join("dme_order as orders","pat_address.id=orders.addressID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("pat.ordered_by != ", 13);
            $this->db->where("pat_address.type",0);
            $this->db->where("orders.original_activity_typeid",1);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }

            $this->db->group_by("orders.uniqueID");
            $this->db->order_by('pat.p_fname','ASC');
        }
        else if($filters['current_date']['sign'] == 2)
        {
            $this->db->select("orders.*,equip.*,pat.p_fname,pat.p_lname");
            $this->db->from("dme_patient as pat");
            $this->db->join("dme_order as orders","pat.patientID=orders.patientID");
            $this->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("orders.organization_id != ", 13);
            $this->db->where("orders.original_activity_typeid", 1);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }
            $this->db->order_by('orders.orderID','DESC');
        }
        else if($filters['current_date']['sign'] == 3)
        {
            $this->db->select("*");
            $this->db->from("dme_patient as pat");
            $this->db->join("dme_order as orders","pat.patientID=orders.patientID");
            $this->db->join("dme_pickup_tbl as pickup","orders.uniqueID=pickup.order_uniqueID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("orders.organization_id != ", 13);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }

            $this->db->group_by("orders.uniqueID");
            $this->db->order_by('pat.p_fname','ASC');
        }
        else if($filters['current_date']['sign'] == 4)
        {
            $this->db->select("orders.*,equip.*, pat.p_fname, pat.p_lname");
            $this->db->from("dme_patient as pat");
            $this->db->join("dme_order as orders","pat.patientID=orders.patientID");
            $this->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("orders.organization_id != ", 13);
            $this->db->where("orders.original_activity_typeid",3);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }

            $this->db->order_by('pat.p_fname','ASC');  
        }
        else if($filters['current_date']['sign'] == 5)
        {
            $this->db->select("pat.*,orders.*");
            $this->db->from("dme_patient as pat");
            $this->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
            $this->db->join("dme_order as orders","pat_address.id=orders.addressID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("pat.ordered_by != ", 13);
            $this->db->where("pat_address.type",1);

            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }

            $this->db->group_by("orders.uniqueID");
            $this->db->order_by('pat.p_fname','ASC');
        }
        else
        {   
            $this->db->select("pat.*,orders.*");
            $this->db->from("dme_patient as pat");
            $this->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
            $this->db->join("dme_order as orders","pat_address.id=orders.addressID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("pat.ordered_by != ", 13);
            $this->db->where("pat_address.type",2);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }

            $this->db->group_by("orders.uniqueID");
            $this->db->order_by('pat.p_fname','ASC');
        }

        $this->db->stop_cache();
        $all_entries      = $this->db->get()->result_array();
        $this->response = $all_entries;

        // echo $this->db->last_query();
        //flashing cache to avoid getting it in another query
        $this->db->flush_cache();

        return $this->response;
    }
    
    public function get_patient_count_v2($filters=false,$page=false,$limitPerPage=false,$account_location)
    {
        $current_date = date('Y-m-d');
        //using database caching
        $this->db->start_cache();
        //check if there is filter
        if($filters!=false)
        {
            $this->load->library('orm/filters');
            $this->filters->detect('fpatient_report',$filters);
        }

        if($filters['current_date']['sign'] == 1)
        {
            $this->db->select("pat.*,orders.*");

            $this->db->from("dme_hospice as hospice");
            $this->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
            $this->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
            $this->db->join("dme_order as orders","pat_address.id=orders.addressID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("pat.ordered_by != ", 13);
            $this->db->where("pat_address.type",0);
            $this->db->where("orders.original_activity_typeid",1);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }
            $this->db->where("hospice.account_location", $account_location);

            $this->db->group_by("orders.uniqueID");
            $this->db->order_by('pat.p_fname','ASC');
        }
        else if($filters['current_date']['sign'] == 2)
        {
            $this->db->select("orders.*,equip.*,pat.p_fname,pat.p_lname");
            $this->db->from("dme_hospice as hospice");
            $this->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
            $this->db->join("dme_order as orders","pat.patientID=orders.patientID");
            $this->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("orders.organization_id != ", 13);
            $this->db->where("orders.original_activity_typeid", 1);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }
            $this->db->where("hospice.account_location", $account_location);

            $this->db->order_by('orders.orderID','DESC');
        }
        else if($filters['current_date']['sign'] == 3)
        {
            $this->db->select("*");
            $this->db->from("dme_hospice as hospice");
            $this->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
            $this->db->join("dme_order as orders","pat.patientID=orders.patientID");
            $this->db->join("dme_pickup_tbl as pickup","orders.uniqueID=pickup.order_uniqueID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("orders.organization_id != ", 13);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }
            $this->db->where("hospice.account_location", $account_location);

            $this->db->group_by("orders.uniqueID");
            $this->db->order_by('pat.p_fname','ASC');
        }
        else if($filters['current_date']['sign'] == 4)
        {
            $this->db->select("orders.*,equip.*, pat.p_fname, pat.p_lname");
            $this->db->from("dme_hospice as hospice");
            $this->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
            $this->db->join("dme_order as orders","pat.patientID=orders.patientID");
            $this->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }
            $this->db->where("orders.organization_id != ", 13);
            $this->db->where("orders.original_activity_typeid",3);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }
            $this->db->where("hospice.account_location", $account_location);

            $this->db->order_by('pat.p_fname','ASC');  
        }
        else if($filters['current_date']['sign'] == 5)
        {
            $this->db->select("pat.*,orders.*");
            $this->db->from("dme_hospice as hospice");
            $this->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
            $this->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
            $this->db->join("dme_order as orders","pat_address.id=orders.addressID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("pat.ordered_by != ", 13);
            $this->db->where("pat_address.type",1);

            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }
            $this->db->where("hospice.account_location", $account_location);

            $this->db->group_by("orders.uniqueID");
            $this->db->order_by('pat.p_fname','ASC');
        }
        else
        {   
            $this->db->select("pat.*,orders.*");
            $this->db->from("dme_hospice as hospice");
            $this->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
            $this->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
            $this->db->join("dme_order as orders","pat_address.id=orders.addressID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("pat.ordered_by != ", 13);
            $this->db->where("pat_address.type",2);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }
            $this->db->where("hospice.account_location", $account_location);

            $this->db->group_by("orders.uniqueID");
            $this->db->order_by('pat.p_fname','ASC');
        }

        $this->db->stop_cache();
        $all_entries      = $this->db->get()->result_array();
        $this->response = $all_entries;

        // echo $this->db->last_query();
        //flashing cache to avoid getting it in another query
        $this->db->flush_cache();

        return $this->response;
    }

    public function get_activity_count($filters=false,$page=false,$limitPerPage=false)
    {

        //using database caching
        $this->db->start_cache();
        //check if there is filter
        if($filters!=false)
        {
            $this->load->library('orm/filters');
            $this->filters->detect('factivity_report',$filters);
        }

        $this->db->select("count(*) as total",false);
        $this->db->from("dme_order as orders");
        if($this->session->userdata("account_type")!="dme_admin")
        {
            $hospice = $this->session->userdata('group_id');
            $this->db->where("orders.organization_id",$hospice);
        }
        $this->db->where("orders.organization_id != ", 13);

        $this->db->group_by("orders.patientID");
        $this->db->stop_cache();

        $all_entries      = $this->db->get()->row()->total;
        // echo $this->db->last_query();
        $this->response = $all_entries;

        //flashing cache to avoid getting it in another query
        $this->db->flush_cache();

        return $this->response;
    }

    public function get_activity_count_v2($filters=false,$page=false,$limitPerPage=false,$account_location)
    {

        //using database caching
        $this->db->start_cache();
        //check if there is filter
        if($filters!=false)
        {
            $this->load->library('orm/filters');
            $this->filters->detect('factivity_report',$filters);
        }

        $this->db->select("count(*) as total",false);
        $this->db->from("dme_hospice as hospice");
        $this->db->join("dme_order as orders","hospice.hospiceID=orders.organization_id");

        if($this->session->userdata("account_type")!="dme_admin")
        {
            $hospice = $this->session->userdata('group_id');
            $this->db->where("orders.organization_id",$hospice);
        }
        $this->db->where("orders.organization_id != ", 13);
        $this->db->where("hospice.account_location", $account_location);

        $this->db->group_by("orders.patientID");
        $this->db->stop_cache();

        $all_entries      = $this->db->get()->row()->total;
        $this->response = $all_entries;

        //flashing cache to avoid getting it in another query
        $this->db->flush_cache();

        return $this->response;
    }

    public function get_o2_concentrator_follow_up_list($from_date,$to_date,$account_location)
    {
        $this->db->select('follow_up.*,patient.medical_record_id,patient.ordered_by,patient.p_fname,patient.p_lname');
        $this->db->from('dme_oxygen_concentrator_follow_up as follow_up');
        $this->db->join('dme_patient as patient' , 'follow_up.patientID = patient.patientID', 'left');
        $this->db->join('dme_hospice as hospice' , 'patient.ordered_by = hospice.hospiceID', 'left');
        $this->db->where("hospice.account_location", $account_location);
        if(!empty($from_date) && !empty($to_date))
        {
            $this->db->where("(follow_up.follow_up_date>='{$from_date}' AND follow_up.follow_up_date<='{$to_date}')",null,false);
        }
        $this->db->order_by('follow_up.follow_up_date', 'ASC');
        $query = $this->db->get()->result_array();

        return $query;
    }

    public function get_latest_pickup_activity($patientID)
    {
        $this->db->select('pickup.pickup_sub,pickup.order_uniqueID');
        $this->db->from('dme_pickup_tbl as pickup');
        $this->db->where("pickup.patientID", $patientID);
       
        $this->db->order_by('pickup.pickupID', 'DESC');
        $query = $this->db->get()->first_row('array');

        return $query;
    }

    public function get_item_pickup($patientID,$equipmentID,$uniqueID)
    {
        $this->db->select('orders.pickedup_uniqueID');
        $this->db->from('dme_order as orders');
        $this->db->where("orders.patientID", $patientID);
        $this->db->where("orders.equipmentID", $equipmentID);
        $this->db->where("orders.uniqueID", $uniqueID);
        $this->db->where("orders.pickedup_uniqueID !=", $uniqueID);
       
        $query = $this->db->get()->first_row('array');

        return $query;
    }

    public function check_item_confirmed_pickup($patientID,$equipmentID,$uniqueID)
    {
        $this->db->select('orders.order_status');
        $this->db->from('dme_order as orders');
        $this->db->where("orders.patientID", $patientID);
        $this->db->where("orders.equipmentID", $equipmentID);
        $this->db->where("orders.uniqueID", $uniqueID);
        $this->db->where("orders.order_status", "confirmed");
       
        $query = $this->db->get()->first_row('array');

        return $query;
    }

    public function get_latest_patient_order($patientID)
    {
        $this->db->select('orders.uniqueID');
        $this->db->from('dme_order as orders');
        $this->db->where("orders.patientID", $patientID);
       
        $this->db->order_by('orders.orderID', 'DESC');
        $query = $this->db->get()->first_row('array');

        return $query;
    }

}