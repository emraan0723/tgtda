<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients_model extends CI_Model
{
    protected $table = 'tbl_registrations';
    protected $pk    = 'tr_id';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getDriverById($id)
    {

        $this->db->where($this->pk, (int)$id);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    public function getDriverByMobile($mobile)
    {
        $this->db->where('tr_mobile', $mobile);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }
}