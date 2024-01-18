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
            else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
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
            else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
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
            else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
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
            else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
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
            else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
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
            else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
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

    public function get_new_customer(){

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
            $this->db->select("count(*) as total",false);
            $this->db->from("dme_patient as pat");
            $this->db->join("dme_hospice as hospice","pat.ordered_by = hospice.hospiceID");
            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("pat.ordered_by",$hospice);
            }

            $this->db->where("pat.ordered_by != ", 13);
            $this->db->where("hospice.account_location", $account_location);
        }
        else if($filters['current_date']['sign'] == 2)
        {

            $this->db->select("count(DISTINCT equip.equipmentID, orders.uniqueID) as total",false);
                $this->db->from("dme_order as orders");
                $this->db->join("dme_hospice as hospice","orders.organization_id=hospice.hospiceID");
                $this->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");
                $this->db->where("equip.parentID",0);

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("orders.organization_id",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
            {
                $hospice = $this->session->userdata('group_id');
                $this->db->where("orders.organization_id",$hospice);
            }

            $this->db->where("orders.organization_id != ", 13);
            $this->db->where("orders.original_activity_typeid", 1);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }
            $this->db->where("hospice.account_location", $account_location);
        }
        else if($filters['current_date']['sign'] == 3)
        {
            $this->db->select("*");
            $this->db->from("dme_hospice as hospice");
            $this->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
            $this->db->join("dme_order_status as orders","pat.patientID=orders.patientID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
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

            $this->db->group_by("orders.order_uniqueID");
            $this->db->order_by('pat.p_fname','ASC');
        }
        else if($filters['current_date']['sign'] == 4)
        {
          $this->db->select("count(DISTINCT equip.equipmentID, orders.uniqueID) as total",false);
          $this->db->from("dme_order as orders");
          $this->db->join("dme_hospice as hospice","orders.organization_id=hospice.hospiceID");
          $this->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");
          $this->db->where("equip.parentID",0);

          if($filters['current_date']['hospiceID'] != 0)
          {
              $this->db->where("orders.organization_id",$filters['current_date']['hospiceID']);
          }
          else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
          {
              $hospice = $this->session->userdata('group_id');
              $this->db->where("orders.organization_id",$hospice);
          }
            $this->db->where("orders.organization_id != ", 13);
            $this->db->where("orders.original_activity_typeid",3);
            if($filters['current_date']['sign_second'] == 0)
            {
                $this->db->where("orders.order_status", "active");
            }
            $this->db->where("hospice.account_location", $account_location);
        }
        else if($filters['current_date']['sign'] == 5)
        {
            $this->db->select("pat.*,orders.*");
            $this->db->from("dme_hospice as hospice");
            $this->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
            $this->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
            $this->db->join("dme_order_status as orders","pat_address.id=orders.addressID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
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

            $this->db->group_by("orders.order_uniqueID");
            $this->db->order_by('pat.p_fname','ASC');
        }
        else
        {
            $this->db->select("pat.*,orders.*");
            $this->db->from("dme_hospice as hospice");
            $this->db->join("dme_patient as pat","hospice.hospiceID=pat.ordered_by");
            $this->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");
            $this->db->join("dme_order_status as orders","pat_address.id=orders.addressID");

            if($filters['current_date']['hospiceID'] != 0)
            {
                $this->db->where("pat.ordered_by",$filters['current_date']['hospiceID']);
            }
            else if($this->session->userdata('account_type')!= "dme_admin" && $this->session->userdata('account_type')!= "dme_user")
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

            $this->db->group_by("orders.order_uniqueID");
            $this->db->order_by('pat.p_fname','ASC');
        }

        $this->db->stop_cache();
        $all_entries      = $this->db->get()->result_array();
        $this->response = $all_entries;
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
        if($this->session->userdata("account_type")!="dme_admin" && $this->session->userdata('account_type')!= "dme_user")
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
        if($this->session->userdata("account_type")!="dme_admin" && $this->session->userdata('account_type')!= "dme_user")
        {
            $hospice = $this->session->userdata('group_id');
            $this->db->where("orders.organization_id",$hospice);
        }
        $this->db->where("orders.organization_id != ", 13);
        $this->db->where("hospice.account_location", $account_location);
        $this->db->stop_cache();

        $all_entries      = $this->db->get()->row()->total;
        $this->response = ($all_entries==null)? 0 : $all_entries;

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

        if ($account_location != 0) {
            $this->db->where("hospice.account_location", $account_location);
        } else {
            $this->db->where("hospice.account_location !=", 0);
        }
        
        if(!empty($from_date) && !empty($to_date))
        {
            $this->db->where("(follow_up.follow_up_date>='{$from_date}' AND follow_up.follow_up_date<='{$to_date}')",null,false);
        }
        $this->db->order_by('follow_up.follow_up_date', 'ASC');
        $query = $this->db->get()->result_array();

        return $query;
    }

    public function get_o2_concentrator_follow_up_list_v2($filters=false,$from_date,$to_date,$account_location,$start=0,$limit=0)
    {
        $this->db->start_cache();
        if($filters!=false)
        {
            $this->load->library('orm/filters');
            $this->filters->detect('factivity_report',$filters);
        }

        $this->db->select('follow_up.*,patient.medical_record_id,patient.ordered_by,patient.p_fname,patient.p_lname');
        $this->db->from('dme_oxygen_concentrator_follow_up as follow_up');
        $this->db->join('dme_patient as patient' , 'follow_up.patientID = patient.patientID', 'left');
        $this->db->join('dme_hospice as hospice' , 'patient.ordered_by = hospice.hospiceID', 'left');
        
        if ($account_location != 0) {
            $this->db->where("hospice.account_location", $account_location);
        } else {
            $this->db->where("hospice.account_location !=", 0);
        }
        
        if(!empty($from_date) && !empty($to_date))
        {
            $this->db->where("(follow_up.follow_up_date>='{$from_date}' AND follow_up.follow_up_date<='{$to_date}')",null,false);
        }
        $this->db->order_by('follow_up.follow_up_date', 'ASC');
        $this->db->order_by('patient.p_lname', 'ASC');

        if($limit!=-1)
        {
            $this->db->limit($limit,$start);
        }

        $this->db->stop_cache();

        $response['limit'] = $limit;
        $response['start'] = $start;
        $response['result'] = $this->db->get()->result_array();
        $response['totalCount'] = $this->db->get()->num_rows();

        $this->db->flush_cache();

        return $response;
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

    public function check_item_confirmed_pickup_order_status($patientID,$uniqueID)
    {
        $this->db->select('orders.order_status');
        $this->db->from('dme_order_status as orders');
        $this->db->where("orders.patientID", $patientID);
        $this->db->where("orders.order_uniqueID", $uniqueID);
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

    public function get_latest_patient_order_status($patientID)
    {
        $this->db->select('orders.order_uniqueID');
        $this->db->from('dme_order_status as orders');
        $this->db->where("orders.patientID", $patientID);

        $this->db->order_by('orders.statusID', 'DESC');
        $query = $this->db->get()->first_row('array');

        return $query;
    }
    public function patience_residence_report($passed_hospiceID,$account_location="",$filters=array())
    {
        $where = " WHERE ";
        $session_hospiceid = $this->session->userdata('group_id');

        //determining hospice id
        if($passed_hospiceID != 0) {
             $where .= " orders.organization_id = {$passed_hospiceID} ";
        }
        else {
            if($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type')!= "dme_user") {
                $where .= " orders.organization_id = {$session_hospiceid} ";
            }
            else {
                $where .= " orders.organization_id != 13 ";
            }
        }
        //hospice location
        $where .= " AND hosp.account_location='{$account_location}' ";
        $where .= " AND orders.pickup_order=0 ";
        $where .= " AND orders.cancelled_order!=1 ";
        $where .= " AND orders.canceled_from_confirming!=1 ";
        $where .= " AND orders.status_activity_typeid!=2 ";

        //determining accepted filters
        if(!empty($filters)){
            if(isset($filters['deliver_to_type'])){
                $filters['deliver_to_type'] = addslashes($filters['deliver_to_type']);
                $where .= " AND orders.deliver_to_type = '{$filters['deliver_to_type']}' ";
            }
            if(isset($filters['date_range']) && $filters['date_range']!=""){
                $daterange = $filters['date_range'];
                $from = (isset($daterange['from']) && $daterange['from']!="")? date("Y-m-d",strtotime($daterange['from'])) : date("Y-m-d");
                $to     = (isset($daterange['to']) && $daterange['to']!="")? date("Y-m-d",strtotime($daterange['to']))." 23:59:59" : $from." 23:59:59";
                if(isset($daterange['to']) && $daterange['to']!=""){
                    //$where .= " AND (orders.date_ordered >= '{$from}' && orders.date_ordered <= '{$to}') ";
                      $where .= " AND (orders.date_ordered <= '$to') ";
                }
                else{
                    $from = $from." 23:59:59";
                    $where .= " AND (orders.date_ordered <= '$from') ";
                }
                 $where .= " AND patient.is_active=1 ";
            }
            else{
                 //no time range so get the total
                $where .= " AND patient.is_active=1 ";
            }
        }
        else{
            $where .= " AND patient.is_active=1 ";
        }
        $query = "SELECT count(DISTINCT orders.patientID) as total FROM dme_order_status orders
                  LEFT JOIN dme_patient patient ON orders.patientID = patient.patientID
                  LEFT JOIN dme_hospice hosp ON orders.organization_id = hosp.hospiceID
                {$where}";
        return $this->db->query($query)->row()->total;

    }

    public function get_customer_residence_report_today($account_location, $deliver_to_type="")
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

        if($this->session->userdata('account_type') != 'dme_admin' && $this->session->userdata('account_type')!= "dme_user" && $this->session->userdata('account_type') != 'biller' && $this->session->userdata('account_type') != 'sales_rep' && $this->session->userdata('account_type') != 'distribution_supervisor')
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

        if ($account_location != 0) {
            $this->db->where('hosp.account_location', $account_location);
        } else {
            $this->db->where('hosp.account_location !=', 0);
        }
        

        if (!empty($deliver_to_type)) {
            $this->db->where('orders.deliver_to_type', $deliver_to_type);
        }

        $this->db->group_by('patient.medical_record_id');
        $this->db->group_by('orders.organization_id');
        $this->db->order_by('patient.p_lname', 'ASC');
        $query = $this->db->get();

        return $query->result_array();
    }

    function get_all_customers_current_month($hospiceID, $start=0, $limit=10, $service_date_from="", $service_date_to="") {
        $this->db->start_cache();

        $confirmed = "confirmed";

        $this->db->select('patient.patientID, patient.ordered_by, patient.p_lname, patient.p_fname, patient.medical_record_id, patient.patient_days, hospice.daily_rate, hospice.hospiceID');
        $this->db->from('dme_hospice as hospice');
        $this->db->join('dme_patient as patient', 'patient.ordered_by = hospice.hospiceID', 'left');
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

        //Version 3
        // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";
        
        //Version 4
        // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

        //Version 5
        $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR ((orders.summary_pickup_date= '0000-00-00' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

        $this->db->where($where);
        $this->db->group_by("patient.patientID");
        $this->db->order_by('patient.p_lname', 'ASC');
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

    function get_category_total_v2($patientID, $hospiceID, $service_date_from="", $service_date_to="")
    {
        $this->db->start_cache();

        $cancel = "cancel";
        $confirmed = "confirmed";

        $this->db->select('orders.is_package, orders.actual_order_date, equip.categoryID, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, assigned_equip.ID as assigned_equipmentID, orders.summary_pickup_date, orders.pickup_date, orders.equipment_value, orders.equipment_quantity, equip.is_package as equip_is_package, equip.equipmentID, orders.pickup_discharge_date, equip.key_desc, orders.uniqueID');

        // $this->db->select('equip.*, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, equip.is_package as equip_is_package, assigned_equip.ID as assigned_equipmentID');
        // $this->db->select('orders.*');

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
        $this->db->where('orders.canceled_from_confirming', 0); // Newly added 09/18/2020 - Remove if it causes error

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

    function get_all_customers_current_month_v2($hospiceID, $start=0, $limit=10, $service_date_from="", $service_date_to="") {
        $this->db->start_cache();

        $this->db->select('patient.patientID, patient.ordered_by, patient.p_lname, patient.p_fname, patient.medical_record_id, orders.is_package, orders.actual_order_date, equip.categoryID, assigned_equip.purchase_price, assigned_equip.monthly_rate, assigned_equip.daily_rate, orders.summary_pickup_date, orders.pickup_date, orders.equipment_value, orders.equipment_quantity, equip.is_package as equip_is_package');
        $this->db->from('dme_patient as patient');
        $this->db->join('dme_order as orders', 'orders.patientID = patient.patientID', 'left');
        $this->db->join('dme_equipment as equip', 'equip.equipmentID = orders.equipmentID', 'left');
        $this->db->join('dme_assigned_equipment as assigned_equip', 'assigned_equip.equipmentID = equip.equipmentID', 'left');
        $this->db->join('dme_equipment as equip2', 'equip2.parentID = orders.equipmentID', 'left');

        $this->db->query('SET SQL_BIG_SELECTS=1');

        // $this->db->where("patient.ordered_by",$hospiceID);
        $this->db->where('orders.organization_id', $hospiceID); // This is added due to the changes in the same MR# but different hospice. Kindly remove if it will cause errors. Added 07/13/2015.
        $this->db->where('assigned_equip.hospiceID', $hospiceID);

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

        //Version 3
        // $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' AND orders.summary_pickup_date <= '".$service_date_from."'))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

        //Version 4
        $where = "((equip.categoryID = 1 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 2 AND ((orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."') OR (orders.summary_pickup_date= '0000-00-00' OR (orders.summary_pickup_date >= '".$service_date_from."' AND orders.summary_pickup_date <= '".$service_date_to."')))) OR (equip.categoryID = 3 AND (orders.actual_order_date >= '".$service_date_from."' AND orders.actual_order_date <= '".$service_date_to."')))";

        $this->db->where($where);
        $this->db->where('equip.categoryID !=', 1);

        $this->db->group_by('orders.orderID');
        $this->db->order_by('orders.addressID ASC, orders.actual_order_date, orders.orderID DESC');

        $this->db->stop_cache();

        $this->db->flush_cache();

        return $this->db->get()->result_array();
    }

}




