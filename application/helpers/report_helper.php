<?php

/* ================== This is the helper for reports ================== */

function get_total_patient_los_current_date_v3($account_location)
{
 $ci =& get_instance();

 $ci->load->database();

 $ci->db->select('SUM(patient_total_los) as patient_total_los');
 $ci->db->from('dme_patient_total_los_per_hospice as total_los');
 $ci->db->join("dme_hospice as hospice","total_los.hospiceID=hospice.hospiceID","left");
 $ci->db->where("total_los.date_saved", date('Y-m-d'));
 if ($account_location != 0) {
    $ci->db->where("hospice.account_location", $account_location);
 } else {
    $ci->db->where("hospice.account_location !=", 0);
 }
 $query = $ci->db->get();

 return $query->first_row('array');
}

function get_new_patient_list_v5($current_date,$from,$to,$hospiceID,$account_location,$pagination_details=array(),$getcount=false)
{
 $CI =& get_instance();
 $CI->load->database();

 //using database caching
    $CI->db->start_cache();
    if($getcount){
        $CI->db->select("count(*) as total",false);
    } else {
         $CI->db->select("pat.*");
         if(isset($pagination_details['offset']) && isset($pagination_details['limit'])) {
         $CI->db->limit($pagination_details['limit'],$pagination_details['offset']);
     }
    }

 $CI->db->from("dme_patient as pat");
     $CI->db->join("dme_hospice as hospice","pat.ordered_by=hospice.hospiceID");

    if(!empty($hospiceID))
    {
     $CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin" && $CI->session->userdata('account_type')!= "dme_user")
    {
     $hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }
    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("(pat.date_created >='{$from}' && pat.date_created <='{$to}')",null,false);

    if ($account_location != 0) {
        $CI->db->where("hospice.account_location", $account_location);
    } else {
        $CI->db->where("hospice.account_location !=", 0);
    }
    
    if(!$getcount){
         $CI->db->order_by('pat.p_fname','ASC');
    }

    $CI->db->stop_cache();

    $query = $CI->db->get()->result_array();
    if($getcount) {
         $query = $CI->db->get()->row()->total;
    }

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_patient_new_item_list_v3($current_date,$from,$to,$hospiceID,$account_location,$pagination_details=array(),$getcount=false)
{
    $CI =& get_instance();
    $CI->load->database();

    //using database caching
    $CI->db->start_cache();
    if(!empty($from) && !empty($to))
    {
        $formatted_to = date("Y-m-d",strtotime($to));

        if($from <= $formatted_to)
        {
            if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
            {
                $CI->db->where("order_status.order_status", "active");
            }
            if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
            {
                $CI->db->where("(order_status.actual_order_date >='{$from}' AND order_status.actual_order_date <'{$current_date}')",null,false);
            }
            else if($formatted_to < $current_date)
            {
                $CI->db->where("(order_status.actual_order_date >='{$from}' AND order_status.actual_order_date <='{$formatted_to}')",null,false);
            }
        }
        else
        {
            $CI->db->where("(order_status.actual_order_date >='{$from}' AND order_status.actual_order_date <='{$formatted_to}')",null,false);
        }
    }

    if($getcount) {
        $CI->db->select("count(DISTINCT orders.uniqueID) as total",false);
    } else {
        $CI->db->select("orders.orderID,orders.patientID,orders.equipmentID,orders.uniqueID,orders.medical_record_id,orders.organization_id,equip.equipmentID,pat.p_fname,pat.p_lname,count(equip.equipmentID) as total_items",false);
    }

    $CI->db->from("dme_order as orders");
    $CI->db->join("dme_order_status as order_status","orders.uniqueID=order_status.order_uniqueID");
    $CI->db->join("dme_hospice as hospice","orders.organization_id=hospice.hospiceID");
    $CI->db->join("dme_patient as pat","orders.patientID=pat.patientID");
    $CI->db->join("dme_equipment as equip","orders.equipmentID=equip.equipmentID");

    if(!empty($hospiceID))
    {
        $CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin" && $CI->session->userdata('account_type')!= "dme_user" && $CI->session->userdata('account_type')!= "biller" && $CI->session->userdata('account_type')!= "sales_rep" && $CI->session->userdata('account_type')!= "distribution_supervisor")
    {
        $hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    $CI->db->where("equip.parentID",0);
    $CI->db->where("orders.organization_id != ", 13);
    $CI->db->where("orders.original_activity_typeid", 1);

    if ($account_location != 0) {
        $CI->db->where("hospice.account_location", $account_location);
    } else {
        $CI->db->where("hospice.account_location !=", 0);
    }
    

    if(!$getcount) {
        $CI->db->order_by('pat.p_lname','ASC');
        $CI->db->group_by('orders.uniqueID');
    }

    $CI->db->stop_cache();
    if($getcount) {
        $query['total'] = $CI->db->get()->row()->total;
    } else {
        $num_results = $CI->db->get()->num_rows();
        if(isset($pagination_details['offset']) && isset($pagination_details['limit'])) {
            $CI->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
        $query['data'] = $CI->db->get()->result_array();
        $query['total'] = $num_results;
    }

    // echo $CI->db->last_query();

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_patient_exchange_list_v3($current_date,$from,$to,$hospiceID,$account_location,$pagination_details=array(),$getcount=false)
{
    $CI =& get_instance();
    $CI->load->database();

    //using database caching
    $CI->db->start_cache();

    $subquery_where = "";
    if(!empty($from) && !empty($to))
    {
        $current_day = date('Y-m-d');
        $formatted_to = date("Y-m-d",strtotime($to));

        if($from <= $formatted_to)
        {
            if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
            {
                $CI->db->where("orders.order_status", "active");
            }
            if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
            {
                $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <'{$current_date}')",null,false);
            }
            else if($formatted_to < $current_date)
            {
                $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <='{$formatted_to}')",null,false);
            }
        }
        else
        {
            $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <='{$formatted_to}')",null,false);
        }
    }

    if ($getcount) {
        $CI->db->select("COUNT(DISTINCT orders.order_uniqueID) as total",false);
    } else {
        $CI->db->select("orders.*,pat.p_fname, pat.p_lname",false);
    }

    $CI->db->from("dme_order_status as orders");
    $CI->db->join("dme_patient as pat","orders.patientID=pat.patientID");
    $CI->db->join("dme_hospice as hospice","orders.organization_id=hospice.hospiceID");

    if(!empty($hospiceID))
    {
        $CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin" && $CI->session->userdata('account_type')!= "dme_user" && $CI->session->userdata('account_type')!= "biller" && $CI->session->userdata('account_type')!= "sales_rep" && $CI->session->userdata('account_type')!= "distribution_supervisor")
    {
        $hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }

    $CI->db->where("orders.organization_id != ", 13);
    $CI->db->where("orders.original_activity_typeid",3);

    if ($account_location != 0) {
        $CI->db->where("hospice.account_location", $account_location);
    } else {
        $CI->db->where("hospice.account_location !=", 0);
    }
    
    if(!$getcount) {
        $CI->db->order_by('pat.p_lname','ASC');
        $CI->db->group_by('orders.order_uniqueID');
    }

    $CI->db->stop_cache();
    if ($getcount) {
        $query['total'] = $CI->db->get()->row()->total;
    } else {
        $num_results = $CI->db->get()->num_rows();
        if(isset($pagination_details['offset']) && isset($pagination_details['limit'])){
            $CI->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
        $query = $CI->db->get()->result_array();
        $temp_query = array();
        foreach($query as $key=>$value) {
            $q = "SELECT count(DISTINCT equip.equipmentID) as total from dme_order ord JOIN dme_equipment equip ON ord.equipmentID = equip.equipmentID WHERE ord.uniqueID=".$value['order_uniqueID'];
            $value['total_items'] = $CI->db->query($q)->row()->total;
            $temp_query[] = $value;
        }
        $query['data'] = $temp_query;
        $query['total'] = $num_results;
    }

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();
    return $query;
}

function get_patient_pickup_list_v3($current_date,$from,$to,$hospiceID,$account_location,$pagination_details=array(),$getcount=false,$reason="")
{
    $CI =& get_instance();

    $CI->load->database();

    //using database caching
    $CI->db->start_cache();
    if($getcount) {
    //   $CI->db->select("COUNT(DISTINCT orders.patientID,ps.pickup_sub) as total",false);
        $CI->db->select("orders.statusID",false);
    } else {
      $CI->db->select("*,ps.pickup_sub",false);
    }
    $CI->db->from("dme_order_status as orders");
    $CI->db->join("dme_patient as pat","orders.patientID=pat.patientID");
    $CI->db->join("dme_pickup_tbl as ps","orders.order_uniqueID=ps.order_uniqueID");
    $CI->db->join("dme_hospice as hospice","orders.organization_id=hospice.hospiceID");
    if(!empty($hospiceID))
    {
        $CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin" && $CI->session->userdata('account_type')!= "dme_user" && $CI->session->userdata('account_type')!= "biller" && $CI->session->userdata('account_type')!= "sales_rep" && $CI->session->userdata('account_type')!= "distribution_supervisor")
    {
        $hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }
    if($reason!=""){
      $CI->db->where("ps.pickup_sub",$reason);
    }
    $CI->db->where("orders.organization_id != ", 13);

    $new_format_current_date = date('Y-m-d',strtotime($current_date));
    $new_format_to = date('Y-m-d',strtotime($to));

    if(!empty($from) && !empty($to))
    {
        $formatted_to = date("Y-m-d",strtotime($to));

        if($from <= $formatted_to)
        {
            if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
            {
                // $CI->db->where("DATE_FORMAT(orders.date_ordered,'%Y-%m-%d')",$current_date);
                $CI->db->where("orders.order_status", "active");
            }
            if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
            {
                $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <'{$current_date}')",null,false);
            }
            else if($formatted_to < $current_date)
            {
                $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <='{$formatted_to}')",null,false);
            }
        }
        else
        {
            $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <='{$formatted_to}')",null,false);
        }
    }

    if ($account_location != 0) {
        $CI->db->where("hospice.account_location", $account_location);
    } else {
        $CI->db->where("hospice.account_location !=", 0);
    }
    

    // if(!$getcount){
      $CI->db->group_by("orders.patientID");
      $CI->db->order_by('pat.p_lname','ASC');
    // }

    $CI->db->stop_cache();

    if($getcount){
    //   $query['total'] = $CI->db->get()->row()->total;
        $query['total'] = $CI->db->get()->num_rows();
    } else {
        $num_results = $CI->db->get()->num_rows();
        if(isset($pagination_details['offset']) && isset($pagination_details['limit'])){
            $CI->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
        $query['data'] = $CI->db->get()->result_array();
        $query['total'] = $num_results;
    }
    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_patient_pickup_list_v4($current_date,$from,$to,$hospiceID,$account_location,$pagination_details=array(),$getcount=false,$reason="")
{
    $CI =& get_instance();

    $CI->load->database();

    //using database caching
    $CI->db->start_cache();
    if($getcount) {
    //   $CI->db->select("COUNT(DISTINCT orders.patientID,ps.pickup_sub) as total",false);
        $CI->db->select("orders.statusID",false);
    } else {
      $CI->db->select("ps.pickup_sub",false);
    }
    $CI->db->from("dme_order_status as orders");
    $CI->db->join("dme_patient as pat","orders.patientID=pat.patientID");
    $CI->db->join("dme_pickup_tbl as ps","orders.order_uniqueID=ps.order_uniqueID");
    $CI->db->join("dme_hospice as hospice","orders.organization_id=hospice.hospiceID");
    if(!empty($hospiceID))
    {
        $CI->db->where("orders.organization_id",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin" && $CI->session->userdata('account_type')!= "dme_user" && $CI->session->userdata('account_type')!= "biller" && $CI->session->userdata('account_type')!= "sales_rep" && $CI->session->userdata('account_type')!= "distribution_supervisor")
    {
        $hospice = $CI->session->userdata('group_id');
        $CI->db->where("orders.organization_id",$hospice);
    }
    if($reason!=""){
      $CI->db->where("ps.pickup_sub",$reason);
    }
    $CI->db->where("orders.organization_id != ", 13);

    $new_format_current_date = date('Y-m-d',strtotime($current_date));
    $new_format_to = date('Y-m-d',strtotime($to));

    if(!empty($from) && !empty($to))
    {
        $formatted_to = date("Y-m-d",strtotime($to));

        if($from <= $formatted_to)
        {
            if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
            {
                // $CI->db->where("DATE_FORMAT(orders.date_ordered,'%Y-%m-%d')",$current_date);
                $CI->db->where("orders.order_status", "active");
            }
            if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
            {
                $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <'{$current_date}')",null,false);
            }
            else if($formatted_to < $current_date)
            {
                $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <='{$formatted_to}')",null,false);
            }
        }
        else
        {
            $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <='{$formatted_to}')",null,false);
        }
    }

    if ($account_location != 0) {
        $CI->db->where("hospice.account_location", $account_location);
    } else {
        $CI->db->where("hospice.account_location !=", 0);
    }
    

    // if(!$getcount){
      $CI->db->group_by("orders.patientID");
      $CI->db->order_by('pat.p_fname','ASC');
    // }

    $CI->db->stop_cache();

    if($getcount){
    //   $query['total'] = $CI->db->get()->row()->total;
        $query['total'] = $CI->db->get()->num_rows();
    } else {
        // $num_results = $CI->db->get()->num_rows();
        // if(isset($pagination_details['offset']) && isset($pagination_details['limit'])){
        //     $CI->db->limit($pagination_details['limit'],$pagination_details['offset']);
        // }
        $query['data'] = $CI->db->get()->result_array();
        // $query['total'] = $num_results;
    }
    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}


function get_ptmove_list_v3($current_date,$from,$to,$hospiceID,$account_location,$pagination_details=array(),$getcount=false)
{
    $CI =& get_instance();

    $CI->load->database();

    //using database caching
    $CI->db->start_cache();
    if($getcount){
        $CI->db->select("COUNT(DISTINCT orders.order_uniqueID) as total",false);
    } else {
        $CI->db->select("pat.*,orders.*");
    }

    $CI->db->from("dme_order_status as orders");
    $CI->db->join("dme_hospice as hospice","orders.organization_id=hospice.hospiceID");
    $CI->db->join("dme_patient as pat","orders.patientID=pat.patientID");
    $CI->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");

    if(!empty($hospiceID))
    {
        $CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin" && $CI->session->userdata('account_type')!= "dme_user" && $CI->session->userdata('account_type')!= "biller" && $CI->session->userdata('account_type')!= "sales_rep" && $CI->session->userdata('account_type')!= "distribution_supervisor")
    {
        $hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("pat_address.type",1);
    $CI->db->where("orders.status_activity_typeid", 4);

    $new_format_current_date = date('Y-m-d',strtotime($current_date));
    $new_format_to = date('Y-m-d',strtotime($to));

    if(!empty($from) && !empty($to))
    {
        $formatted_to = date("Y-m-d",strtotime($to));

        if($from <= $formatted_to)
        {
            if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
            {
                // $CI->db->where("DATE_FORMAT(orders.date_ordered,'%Y-%m-%d')",$current_date);
                $CI->db->where("orders.order_status", "active");
            }
            if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
            {
                $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <'{$current_date}')",null,false);
            }
            else if($formatted_to < $current_date)
            {
                $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <='{$formatted_to}')",null,false);
            }
        }
        else
        {
            $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <='{$formatted_to}')",null,false);
        }
    }


    if ($account_location != 0) {
        $CI->db->where("hospice.account_location", $account_location);
    } else {
        $CI->db->where("hospice.account_location !=", 0);
    }
    
    if(!$getcount){
      $CI->db->group_by("orders.order_uniqueID");
      $CI->db->order_by('pat.p_lname','ASC');
    }

    $CI->db->stop_cache();

    if($getcount) {
        $query['total'] = $CI->db->get()->row()->total;
    } else {
        $num_results = $CI->db->get()->num_rows();
        if(isset($pagination_details['offset']) && isset($pagination_details['limit'])){
            $CI->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
        $query['data'] = $CI->db->get()->result_array();
        $query['total'] = $num_results;
    }
    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_respite_list_v3($current_date,$from,$to,$hospiceID,$account_location,$pagination_details=array(),$getcount=false)
{
    $CI =& get_instance();

    $CI->load->database();

    //using database caching
    $CI->db->start_cache();
    if($getcount){
       $CI->db->select("COUNT(DISTINCT orders.order_uniqueID) as total",false);
    } else {
       $CI->db->select("pat.*,orders.*");
    }

    $CI->db->from("dme_order_status as orders");
    $CI->db->join("dme_hospice as hospice","orders.organization_id=hospice.hospiceID");
    $CI->db->join("dme_patient as pat","orders.patientID=pat.patientID");
    $CI->db->join("dme_patient_address as pat_address","pat.patientID=pat_address.patient_id");

    if(!empty($hospiceID))
    {
        $CI->db->where("pat.ordered_by",$hospiceID);
    }
    else if($CI->session->userdata('account_type')!= "dme_admin" && $CI->session->userdata('account_type')!= "dme_user" && $CI->session->userdata('account_type')!= "biller" && $CI->session->userdata('account_type')!= "sales_rep" && $CI->session->userdata('account_type')!= "distribution_supervisor")
    {
        $hospice = $CI->session->userdata('group_id');
        $CI->db->where("pat.ordered_by",$hospice);
    }

    $CI->db->where("pat.ordered_by != ", 13);
    $CI->db->where("pat_address.type",2);
    $CI->db->where("orders.status_activity_typeid",5);

    $new_format_current_date = date('Y-m-d',strtotime($current_date));
    $new_format_to = date('Y-m-d',strtotime($to));

    if(!empty($from) && !empty($to))
    {
        $formatted_to = date("Y-m-d",strtotime($to));

        if($from <= $formatted_to)
        {
            if(($from == $current_date && $formatted_to == $current_date) || ($from == $current_date && $formatted_to > $from))
            {
                $CI->db->where("orders.order_status", "active");
            }
            if(($formatted_to == $current_date && $from < $current_date) || $formatted_to > $current_date)
            {
                $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <'{$current_date}')",null,false);
            }
            else if($formatted_to < $current_date)
            {
                $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <='{$formatted_to}')",null,false);
            }
        }
        else
        {
            $CI->db->where("(orders.actual_order_date >='{$from}' AND orders.actual_order_date <='{$formatted_to}')",null,false);
        }
    }

    if ($account_location != 0) {
        $CI->db->where("hospice.account_location", $account_location);
    } else {
        $CI->db->where("hospice.account_location !=", 0);
    }
    

    if(!$getcount){
      $CI->db->group_by("orders.order_uniqueID");
      $CI->db->order_by('pat.p_lname','ASC');
    }

    $CI->db->stop_cache();

    if($getcount){
      $query['total'] = $CI->db->get()->row()->total;
    } else {
        $num_results = $CI->db->get()->num_rows();
        if(isset($pagination_details['offset']) && isset($pagination_details['limit'])){
            $CI->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
        $query['data'] = $CI->db->get()->result_array();
        $query['total'] = $num_results;
    }

    //flashing cache to avoid getting it in another query
    $CI->db->flush_cache();

    return $query;
}

function get_patient_first_order_status_v2($patientID)
{
    $ci = get_instance();

    $ci->load->database();

    $ci->db->select('order_status.order_uniqueID,order_status.patientID');
    $ci->db->from('dme_order_status as order_status');
    $ci->db->where('order_status.patientID', $patientID);
    $query = $ci->db->get()->first_row('array');

    return $query;
}

function get_pending_orders_count($account_location)
{
	$ci = get_instance();
	$ci->load->database();

	$response = "";
	$statuses = array('pending');
	$queryies = array();
	$where = "";
	$where_account_location = "";
	if ($account_location != 0) {
		$where_account_location = "hospice.account_location = ".$account_location;
	} else {
		$where_account_location = "hospice.account_location != 0";
	}
	
	if($ci->session->userdata('account_type') != 'dme_admin' && $ci->session->userdata('account_type') != "dme_user" && $ci->session->userdata('account_type') != 'dispatch' && $ci->session->userdata('account_type') != 'sales_rep' && $ci->session->userdata('account_type') != 'biller' && $ci->session->userdata('account_type') != 'customer_service' && $ci->session->userdata('account_type') != 'rt' && $ci->session->userdata('account_type') != 'distribution_supervisor')
	{
		$where = " AND stats.organization_id = ".$ci->session->userdata('group_id'). " AND ".$where_account_location;
	}
	else
	{
		$where = " AND ".$where_account_location;
	}

	foreach($statuses as $val)
	{
		$queryies[] = "SELECT count(*) as total, concat('','{$val}') as status
						FROM ( SELECT DISTINCT `stats`.`status_activity_typeid`, `stats`.`order_uniqueID` FROM `dme_order_status` as stats
						LEFT JOIN `dme_hospice` as hospice ON `stats`.`organization_id`=`hospice`.`hospiceID`
						WHERE `stats`.`order_status`='{$val}' {$where}) as dt";
	}

	$main_query = implode(" UNION ",$queryies);

	$result = $ci->db->query($main_query)->result_array();
	return $result;
}
