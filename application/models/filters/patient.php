<?php

class patient extends CI_Model
{
    public function order_by_medical_record_id($patient = 'ASC')
    {
        $this->db->order_by('medical_record_id', strtoupper($patient));
    }

    public function order_by_last_name($patient = 'ASC')
    {
        $this->db->order_by('p_lname', strtoupper($patient));
    }

    public function order_by_first_name($patient = 'ASC')
    {
        $this->db->order_by('p_fname', strtoupper($patient));
    }

    public function order_by_next_of_kin($patient = 'ASC')
    {
        $this->db->order_by('p_nextofkin', strtoupper($patient));
    }

    public function order_by_phonenum($patient = 'ASC')
    {
        $this->db->order_by('p_phonenum', strtoupper($patient));
    }

    public function search_item_fields_patient_lists($search_value = '')
    {
        $where = "(p_lname LIKE '%$search_value%' OR p_fname LIKE '%$search_value%' OR medical_record_id LIKE '%$search_value%')";
        $this->db->where($where);
    }
}
