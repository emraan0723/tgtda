<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    protected $table = 'tbl_registrations';
    protected $pk    = 'tr_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ─────────────────────────────────────────────
    // DATATABLE DATA
    // ─────────────────────────────────────────────
    public function datatable_data($p = array()) {

        $this->_apply_filters($p);

        $order_col = isset($p['order_col']) ? $p['order_col'] : 'tr_id';
        $order_dir = isset($p['order_dir']) ? $p['order_dir'] : 'DESC';

        $this->db->order_by($order_col, $order_dir);

        if (isset($p['length']) && (int)$p['length'] > 0) {
            $start  = isset($p['start']) ? (int)$p['start'] : 0;
            $length = (int)$p['length'];
            $this->db->limit($length, $start);
        }

        return $this->db->get($this->table)->result_array();
    }

    // ─────────────────────────────────────────────
    public function datatable_total_count() {
        return $this->db->count_all($this->table);
    }

    // ─────────────────────────────────────────────
    public function datatable_filtered_count($p = array()) {
        $this->_apply_filters($p);
        return $this->db->count_all_results($this->table);
    }

    // ─────────────────────────────────────────────
    // ADDED: filter_district and filter_mandal support
    // ─────────────────────────────────────────────
    private function _apply_filters($p = array()) {

        if (isset($p['filter_status']) && $p['filter_status'] != '') {
            $this->db->where('tr_status', $p['filter_status']);
        }

        if (isset($p['filter_type']) && $p['filter_type'] != '') {
            $this->db->where('tr_registration_type', strtoupper($p['filter_type']));
        }

        // District filter — tr_district stores the district ID (integer)
        if (isset($p['filter_district']) && $p['filter_district'] != '') {
            $this->db->where('tr_district', (int)$p['filter_district']);
        }

        // Mandal filter — tr_mandal stores the mandal name (string)
        if (isset($p['filter_mandal']) && $p['filter_mandal'] != '') {
            $this->db->where('tr_mandal', $p['filter_mandal']);
        }

        if (isset($p['search']) && $p['search'] != '') {
            $s = $p['search'];
            $this->db->where("(tr_mobile LIKE '%".$this->db->escape_like_str($s)."%' 
                OR tr_aadhar_no LIKE '%".$this->db->escape_like_str($s)."%'
                OR tr_full_name LIKE '%".$this->db->escape_like_str($s)."%'
                OR tr_reg_ukey LIKE '%".$this->db->escape_like_str($s)."%'
                OR tr_district LIKE '%".$this->db->escape_like_str($s)."%')");
        }
    }

    // ─────────────────────────────────────────────
    // CRUD
    // ─────────────────────────────────────────────

    public function get_all_registrations($filters = array()) {

        if (isset($filters['status']) && $filters['status'] != '') {
            $this->db->where('tr_status', $filters['status']);
        }

        if (isset($filters['reg_type']) && $filters['reg_type'] != '') {
            $this->db->where('tr_registration_type', $filters['reg_type']);
        }

        $this->db->order_by($this->pk, 'DESC');

        return $this->db->get($this->table)->result_array();
    }

    // ─────────────────────────────────────────────
    public function get_registration_by_id($tr_id) {
        return $this->db->where($this->pk, $tr_id)->get($this->table)->row_array();
    }

    // ─────────────────────────────────────────────
    public function insert_registration($data = array()) {

        $next_seq = $this->db->count_all($this->table) + 1;

        // tr_reg_key is set by controller (full GUID) — only fallback if somehow missing
        if (empty($data['tr_reg_key'])) {
            $data['tr_reg_key'] = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        }

        // tr_reg_ukey is a temporary placeholder — controller updates it after getting insert_id
        if (empty($data['tr_reg_ukey'])) {
            $data['tr_reg_ukey'] = 'REG-' . str_pad($next_seq, 5, '0', STR_PAD_LEFT);
        }

        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    // ─────────────────────────────────────────────
    public function update_registration($tr_id, $data = array()) {
        $this->db->where($this->pk, $tr_id);
        return $this->db->update($this->table, $data);
    }

    // ─────────────────────────────────────────────
    public function update_status($tr_id, $status) {
        return $this->db->update(
            $this->table,
            array(
                'tr_status' => $status,
                'tr_last_updated_at' => date('Y-m-d H:i:s')
            ),
            array($this->pk => $tr_id)
        );
    }

    // ─────────────────────────────────────────────
    public function update_password($tr_id, $hashed) {
        return $this->db->update(
            $this->table,
            array(
                'tr_password' => $hashed,
                'tr_last_updated_at' => date('Y-m-d H:i:s')
            ),
            array($this->pk => $tr_id)
        );
    }

    // ─────────────────────────────────────────────
    public function delete_registration($tr_id) {
        return $this->db->delete($this->table, array($this->pk => $tr_id));
    }

    // ─────────────────────────────────────────────
    // DUPLICATE CHECKS
    // ─────────────────────────────────────────────

    public function is_aadhar_duplicate($aadhar, $exclude_id = null) {

        $this->db->where('tr_aadhar_no', $aadhar);

        if ($exclude_id) {
            $this->db->where($this->pk . ' !=', $exclude_id);
        }

        return $this->db->count_all_results($this->table) > 0;
    }

    public function is_mobile_duplicate($mobile, $exclude_id = null) {

        $this->db->where('tr_mobile', $mobile);

        if ($exclude_id) {
            $this->db->where($this->pk . ' !=', $exclude_id);
        }

        return $this->db->count_all_results($this->table) > 0;
    }

    // ─────────────────────────────────────────────
    // STATS
    // ─────────────────────────────────────────────
    public function get_stats() {

        $stats = array();
        $stats['total'] = $this->db->count_all($this->table);

        $statuses = array('pending','active','inactive','approved','rejected');

        foreach ($statuses as $s) {
            $stats[$s] = $this->db->where('tr_status', $s)->count_all_results($this->table);
        }

        $stats['driver'] = $this->db
            ->where('tr_registration_type','DRIVER')
            ->count_all_results($this->table);

        $stats['transport'] = $this->db
            ->where('tr_registration_type','TRANSPORT')
            ->count_all_results($this->table);

        return $stats;
    }
}
