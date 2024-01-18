<?php
Class Canceled_order_model extends CI_Model
{
    public function __construct()
    {
        $this->db->query('SET SQL_BIG_SELECTS=1');
    }

    public function get_canceled_v2($filters=false,$account_location,$start=0,$limit=0)
    {
        $this->db->start_cache();
        if($filters!=false)
        {
            $this->load->library('orm/filters');
            $this->filters->detect('canceled_work_orders',$filters);
        }

        $this->db->select('trash.*,patient.p_fname,patient.p_lname,patient.patientID, patient.medical_record_id, hospice.hospiceID');
        $this->db->from('trash_table as trash');
        $this->db->join('dme_patient as patient','trash.patientID = patient.patientID','left');
        $this->db->join('dme_hospice as hospice','patient.ordered_by = hospice.hospiceID','left');

        if ($account_location != 0) {
            $this->db->where('hospice.account_location', $account_location);
        } else {
            $this->db->where('hospice.account_location !=', 0);
        }
        
        $this->db->where('hospice.account_active_sign', 1);

        $response['totalCount'] = $this->db->count_all_results() ;
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

}

?>