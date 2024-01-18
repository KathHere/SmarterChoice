<?php

if (!function_exists('process_patient_deactivation'))
{
    /*
    * all 3 parameters are required
    *    $patient_id - patient id
    *    $patient_mr - patient medical record number
    *    $patient_hospice_id - hospise identification
    */
    function process_patient_deactivation($patient_id="",$patient_mr="",$patient_hospice_id="")
    {
          if($patient_id=="" || $patient_mr=="" || $patient_hospice_id==""){
            return false;
          }

          $CI =& get_instance();

          $patient_orders = check_if_all_pickups($patient_mr,$patient_hospice_id);
          $has_delivery = in_multiarray(1, $patient_orders, "activity_typeid");
          $has_exchange = in_multiarray(3, $patient_orders, "activity_typeid");
          $has_ptmove   = in_multiarray(4, $patient_orders, "activity_typeid");
          $has_respite  = in_multiarray(5, $patient_orders, "activity_typeid");

          $isactive = true;

          if($has_delivery || $has_exchange || $has_ptmove || $has_respite)
          {
            $isactive = true;
          }
          else
          {
            $patient_capped_noncapped_orders = get_customer_ordered_capped_non_capped_items($patient_id);
            if(empty($patient_capped_noncapped_orders))
            {
              $patient_disposable_orders = get_customer_ordered_disposable_items($patient_id);
              if(empty($patient_disposable_orders))
              {
                $isactive = false;
              }
            }
          }
          //if not active update the dme patient using patient id
          if(!$isactive){
            $toupdate = array(
                          "is_active" => 0
                        );
             $CI->db->where("patientID",$patient_id);
             $CI->db->where("medical_record_id",$patient_mr);
             $CI->db->where("ordered_by",$patient_hospice_id);
             $update = $CI->db->update("dme_patient",$toupdate);
          }
          return $isactive;
    }
}
if (!function_exists('create_process_activation_checking'))
{
  function create_process_activation_checking($patient_id="",$patient_mr="",$patient_hospice_id=""){
      if($patient_id=="" || $patient_id=="" || $patient_hospice_id==""){
        return false;
      }
      $CI =& get_instance();
      return $CI->db->insert("dme_patient_processing",array(
                                                            "patientID"         => $patient_id,
                                                            "medical_record_id" => $patient_mr,
                                                            "hospice_id"        => $patient_hospice_id
                                                          ));
  }
}

if (!function_exists('list_residence_status_new_approach'))
{
  function list_residence_status_new_approach_v2($residence_status_name="",$hospiceID,$account_location,$from,$to,$pagination_details=array(),$getcount=false)
  {
      $result = array();
      if($residence_status_name=="")
      {
        return $result;
      }

      $ci = get_instance();

      $ci->load->database();
      if($getcount)
      {
        $ci->db->select('COUNT(DISTINCT patient.medical_record_id,orders.organization_id,orders.deliver_to_type) as total',false);
      }
      else
      {
        $ci->db->select('patient.p_fname,patient.p_lname, orders.organization_id, orders.medical_record_id, orders.deliver_to_type');
        if(isset($pagination_details['offset']) && isset($pagination_details['limit']))
        {
          $ci->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
      }

      $from_formatted = date("Y-m-d",strtotime($from));
      $ci->db->from('dme_order_status AS orders');
      $ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID');
      $ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id');

      $includes = array(1,2);

      if($ci->session->userdata('account_type') != 'dme_admin' && $ci->session->userdata('account_type') != 'dme_user' && $ci->session->userdata('account_type')!= "biller" && $ci->session->userdata('account_type')!= "sales_rep" && $ci->session->userdata('account_type')!= "distribution_supervisor")
      {
        $ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
      }
      else if($hospiceID != 0)
      {
        $ci->db->where('orders.organization_id',$hospiceID);
      }

      //remove this two lines if it will cause errors -- newly added
      $ci->db->where('orders.organization_id !=', 13);
      $ci->db->where('orders.deliver_to_type', $residence_status_name);

      if ($account_location != 0) {
        $ci->db->where('hosp.account_location', $account_location);
      } else {
        $ci->db->where('hosp.account_location !=', 0);
      }
      

      if(!empty($from) && !empty($to))
      {
        if(($to > $from) || ($to == $from))
        {
          $ci->db->where("DATE_FORMAT(patient.date_created,'%Y-%m-%d') >= ",$from);
          $ci->db->where("DATE_FORMAT(patient.date_created,'%Y-%m-%d') <= ",$to);
        }
      } else if (!empty($from)){
        $date = date("Y-m-d",strtotime($from));
        $date1 = $date." 00:00:00";
        $date2 = $date." 23:59:59";
        $ci->db->where("patient.date_created >= '{$date1}'",null,false);
        $ci->db->where("patient.date_created <= '{$date2}'",null,false);
      } else {
        $date = date("Y-m-d");
        $date1 = $date." 00:00:00";
        $date2 = $date." 23:59:59";
        $ci->db->where("patient.date_created >= '{$date1}'",null,false);
        $ci->db->where("patient.date_created <= '{$date2}'",null,false);
      }

      if(!$getcount)
      {
        $ci->db->group_by('patient.medical_record_id');
        $ci->db->group_by('orders.organization_id');
        $ci->db->group_by('orders.deliver_to_type');
        $ci->db->order_by('patient.p_lname', 'ASC');
      }

      $query = $ci->db->get();
      if($getcount)
      {
        return $query->row()->total;
      }
      else
      {
        return $query->result_array();
      }
  }
  // $ci->db->where('(CASE WHEN COALESCE((SELECT is_active FROM dme_patient_activation pa WHERE pa.patientID = patient.patientID AND DATE_FORMAT(pa.date_added,"%Y-%m-%d")="'.$from_formatted.'" LIMIT 1),NULL) IS NULL THEN patient.is_active=1 ELSE COALESCE((SELECT is_active FROM dme_patient_activation pa WHERE pa.patientID = patient.patientID AND DATE_FORMAT(pa.date_added,"%Y-%m-%d")="'.$from_formatted.'" LIMIT 1),NULL)=1 END)',null,false);

  // FOR CUSTOMER MOVE COUNT
  function list_residence_status_count_cus_move($residence_status_name="",$hospiceID,$account_location,$from,$to,$pagination_details=array(),$getcount=false)
  {
      $result = array();
      if($residence_status_name=="")
      {
        return $result;
      }

      $ci = get_instance();

      $ci->load->database();
      if($getcount)
      {
        $ci->db->select('COUNT(DISTINCT patient.medical_record_id,orders.organization_id,orders.deliver_to_type) as total',false);
      }
      else
      {
        $ci->db->select('patient.p_fname,patient.p_lname, orders.organization_id, orders.medical_record_id, orders.deliver_to_type');
        if(isset($pagination_details['offset']) && isset($pagination_details['limit']))
        {
          $ci->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
      }

      $from_formatted = date("Y-m-d",strtotime($from));
      $ci->db->from('dme_order_status AS orders');
      $ci->db->join('dme_sub_ptmove AS pt_move', 'pt_move.patientID = orders.patientID');
      $ci->db->join('dme_patient AS patient', 'patient.patientID = pt_move.patientID');
      $ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id');

      $includes = array(1,2);

      if($ci->session->userdata('account_type') != 'dme_admin' && $ci->session->userdata('account_type') != 'dme_user')
      {
        $ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
      }
      else if($hospiceID != 0)
      {
        $ci->db->where('orders.organization_id',$hospiceID);
      }

      //remove this two lines if it will cause errors -- newly added
      $ci->db->where('orders.organization_id !=', 13);
      $ci->db->where('orders.deliver_to_type', $residence_status_name);

      if ($account_location != 0) {
        $ci->db->where('hosp.account_location', $account_location);
      } else {
        $ci->db->where('hosp.account_location !=', 0);
      }
      

      if(!empty($from) && !empty($to))
      {
        if(($to > $from) || ($to == $from))
        {
          $start = date("Y-m-d",strtotime($from." -1 days"));
          $end = date("Y-m-d",strtotime($to." +1 days"));
          $final_start = strtotime($start." 23:59:59");
          $final_end = strtotime($end." 00:00:00");

          $ci->db->where("pt_move.order_uniqueID >= ",$final_start);
          $ci->db->where("pt_move.order_uniqueID <= ",$final_end);
        }
      } else if (!empty($from)){
        $date = date("Y-m-d",strtotime($from));
        $start = date("Y-m-d",strtotime($date." -1 days"));
        $end = date("Y-m-d",strtotime($date." +1 days"));
        $final_start = strtotime($start." 23:59:59");
        $final_end = strtotime($end." 00:00:00");

        $ci->db->where("pt_move.order_uniqueID >= ",$final_start);
        $ci->db->where("pt_move.order_uniqueID <= ",$final_end);
      } else {
        $date = date("Y-m-d");
        $start = date("Y-m-d",strtotime($date." -1 days"));
        $end = date("Y-m-d",strtotime($date." +1 days"));
        $final_start = strtotime($start." 23:59:59");
        $final_end = strtotime($end." 00:00:00");

        $ci->db->where("pt_move.order_uniqueID >= ",$final_start);
        $ci->db->where("pt_move.order_uniqueID <= ",$final_end);
      }

      if(!$getcount)
      {
        $ci->db->group_by('patient.medical_record_id');
        $ci->db->group_by('orders.organization_id');
        $ci->db->group_by('orders.deliver_to_type');
        $ci->db->order_by('patient.p_lname', 'ASC');
      }

      $query = $ci->db->get();
      if($getcount)
      {
        return $query->row()->total;
      }
      else
      {
        return $query->result_array();
      }
  }

  function list_residence_status_new_approach($residence_status_name="",$hospiceID,$account_location,$from,$pagination_details=array(),$getcount=false)
  {
      $result = array();
      if($residence_status_name==""){
        return $result;
      }
      $ci = get_instance();

      $ci->load->database();
      if($getcount){
        $ci->db->select('COUNT(DISTINCT patient.medical_record_id,orders.organization_id,orders.deliver_to_type) as total',false);
      }
      else{
        $ci->db->select('patient.p_fname,patient.p_lname, orders.organization_id, orders.medical_record_id, orders.deliver_to_type');
        if(isset($pagination_details['offset']) && isset($pagination_details['limit'])){
          $ci->db->limit($pagination_details['limit'],$pagination_details['offset']);
        }
      }

      $from_formatted = date("Y-m-d",strtotime($from));
      $ci->db->from('dme_order_status AS orders');
      $ci->db->join('dme_patient AS patient', 'patient.patientID = orders.patientID');
      $ci->db->join('dme_hospice AS hosp', 'hosp.hospiceID = orders.organization_id');

      $includes = array(1,2);

      if($ci->session->userdata('account_type') != 'dme_admin' && $ci->session->userdata('account_type') != 'dme_user')
      {
        $ci->db->where('orders.organization_id', $ci->session->userdata('group_id'));
      }
      else if($hospiceID != 0)
      {
        $ci->db->where('orders.organization_id',$hospiceID);
      }

      //remove this two lines if it will cause errors -- newly added
      $ci->db->where('orders.organization_id !=', 13);

      //Russel added codes. This is for filtering inactive patients.
      $ci->db->where('orders.deliver_to_type', $residence_status_name);
      $ci->db->where('hosp.account_location', $account_location);
      $ci->db->where('(CASE WHEN COALESCE((SELECT is_active FROM dme_patient_activation pa WHERE pa.patientID = patient.patientID AND DATE_FORMAT(pa.date_added,"%Y-%m-%d")="'.$from_formatted.'" LIMIT 1),NULL) IS NULL THEN patient.is_active=1 ELSE COALESCE((SELECT is_active FROM dme_patient_activation pa WHERE pa.patientID = patient.patientID AND DATE_FORMAT(pa.date_added,"%Y-%m-%d")="'.$from_formatted.'" LIMIT 1),NULL)=1 END)',null,false);

      $datecreated = $from_formatted." 23:59:59";
      $ci->db->where("patient.date_created <= '{$datecreated}'",null,false);
      if(!$getcount){
        $ci->db->group_by('patient.medical_record_id');
        $ci->db->group_by('orders.organization_id');
        $ci->db->group_by('orders.deliver_to_type');
        $ci->db->order_by('patient.p_lname', 'ASC');
      }
      $query = $ci->db->get();
      if($getcount){
        return $query->row()->total;
      }
      else{
        return $query->result_array();
      }
  }

  function get_order_first_row_v2($medical_id,$uniqueID)
  {
    $ci = get_instance();

    $ci->load->database();

    $ci->db->select('*');
    $ci->db->from('dme_order_status as order_status');
    $ci->db->where('order_status.medical_record_id', $medical_id);
    $ci->db->where('order_status.order_uniqueID', $uniqueID);
    $ci->db->order_by("statusID","ASC");
    $query = $ci->db->get()->first_row('array');

    return $query;
  }


  function get_latest_pickup_all_v2($patientID)
  {
    $ci = get_instance();

    $ci->load->database();

    $ci->db->select('*');
    $ci->db->from('dme_order_status as order_status');
    $ci->db->join('dme_pickup_tbl AS pickup','pickup.order_uniqueID = order_status.order_uniqueID','left');
    $ci->db->where('order_status.patientID', $patientID);
    $ci->db->where('order_status.original_activity_typeid', 2);
    $ci->db->where('pickup.pickup_sub !=', "not needed");
    $ci->db->order_by("statusID","ASC");
    $query = $ci->db->get()->first_row('array');

    return $query;
  }

  function get_first_order_after_deactivation_v3($patientID,$statusID,$uniqueID,$pickup_date)
  {
    $ci = get_instance();

    $ci->load->database();

    $ci->db->select('*');
    $ci->db->from('dme_order_status as order_status');
    $ci->db->where('order_status.patientID', $patientID);
    $ci->db->where('order_status.statusID > ', $statusID);
    $ci->db->where("DATE_FORMAT(order_status.date_ordered,'%Y-%m-%d') > ", $pickup_date);
    $ci->db->where('order_status.order_uniqueID != ', $uniqueID);
    $ci->db->where('order_status.original_activity_typeid', 1);
    $ci->db->order_by("statusID","ASC");
    $query = $ci->db->get()->first_row('array');

    return $query;
  }

  function get_patient_first_order_status_latest($patientID)
  {
    $ci = get_instance();

    $ci->load->database();

    $ci->db->select('*');
    $ci->db->from('dme_order_status as order_status');
    $ci->db->where('order_status.patientID', $patientID);
    $ci->db->where('order_status !=', "cancel");
	$ci->db->order_by("statusID","ASC");
    $query = $ci->db->get()->first_row('array');

    return $query;
  }

  function check_if_customer_first_delivery($patientID)
  {
    $ci =& get_instance();

    $ci->load->database();

    $ci->db->select('*');
    $ci->db->from('dme_order');
    $ci->db->where("patientID", $patientID);
    $ci->db->order_by('orderID',"ASC");
    $query = $ci->db->get();

    return $query->first_row('array');
  }

  function update_customer_days_los($data,$patientID)
  {
    $ci =& get_instance();
    $ci->load->database();

    $ci->db->where('patientID', $patientID);
    $response = $ci->db->update('customer_days_length_of_stay', $data);
  }
}
