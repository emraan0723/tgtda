<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration_model extends CI_Model {

    private $table = ' tbl_registrations';

    public function check_mobile($mobile) {
        $query = $this->db->get_where($this->table, ['tr_mobile' => $mobile]);
        return $query->num_rows() > 0;
    }

    public function check_aadhar($aadhar) {
        $query = $this->db->get_where($this->table, ['tr_aadhar_no' => $aadhar]);
        return $query->num_rows() > 0;
    }

    public function save($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_registration($id) {
        return $this->db->get_where($this->table, ['tr_id' => $id])->row_array();
    }

    public function updateWhere($id, $data) {
        $this->db->where('tr_id', $id);
        return $this->db->update($this->table, $data);
    }
}
