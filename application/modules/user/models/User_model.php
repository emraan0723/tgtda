<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model — v6
 * Changes from v5:
 *  - datatable_data / _apply_filters: LEFT JOIN tbl_district_masters to get district_name;
 *    also LEFT JOIN tbl_state_masters to get state_name for tooltip
 *  - _apply_filters search: now also covers tr_mandal LIKE
 *  - All other logic unchanged.
 *
 * NOTE: Adjust join table/column names below to match your actual schema:
 *   tbl_district_masters  → tdt_district_ID, tdt_district_name
 *   tbl_state_masters     → ts_state_ID, ts_state_name
 */
class User_model extends CI_Model {

    protected $table = 'tbl_registrations';
    protected $pk    = 'tr_id';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ─────────────────────────────────────────────
    // DATATABLE DATA
    // Uses JOIN so district_name and state_name are available in every row.
    // ─────────────────────────────────────────────
    public function datatable_data($p = array()) {

        $this->_base_select();
        $this->_apply_filters($p);

        $order_col = isset($p['order_col']) ? $p['order_col'] : 'tr_id';
        $order_dir = isset($p['order_dir']) ? $p['order_dir'] : 'DESC';

        // Prefix ambiguous columns
        if ($order_col === 'district_name') {
            $order_col = 'tdt.tdt_district_name';
        } elseif (strpos($order_col, '.') === false) {
            $order_col = 'r.'.$order_col;
        }

        $this->db->order_by($order_col, $order_dir);

        if (isset($p['length']) && (int)$p['length'] > 0) {
            $start  = isset($p['start']) ? (int)$p['start'] : 0;
            $length = (int)$p['length'];
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result_array();
    }

    // ─────────────────────────────────────────────
    public function datatable_total_count() {
        return $this->db->count_all($this->table);
    }

    // ─────────────────────────────────────────────
    public function datatable_filtered_count($p = array()) {
        $this->_base_select(true); // count only
        $this->_apply_filters($p);
        return $this->db->count_all_results();
    }

    // ─────────────────────────────────────────────
    // Base SELECT with JOINs — shared by data + filtered_count
    // ─────────────────────────────────────────────
    private function _base_select($count_only = false) {

        if ($count_only) {
            $this->db->select('r.tr_id');
        } else {
            $this->db->select('
                r.*,
                tdt.tdt_district_name AS district_name,
                ts.ts_state_name      AS state_name
            ');
        }

        $this->db->from($this->table.' r');

        // LEFT JOIN districts
        $this->db->join(
            'tbl_district_masters tdt',
            'tdt.tdt_district_ID = r.tr_district',
            'left'
        );

        // LEFT JOIN states (for address tooltip in controller)
        $this->db->join(
            'tbl_state_masters ts',
            'ts.ts_state_ID = r.tr_state',
            'left'
        );
    }

    // ─────────────────────────────────────────────
    // Filters — applied after _base_select
    // ─────────────────────────────────────────────
    private function _apply_filters($p = array()) {

        if (isset($p['filter_status']) && $p['filter_status'] != '') {
            $this->db->where('r.tr_status', $p['filter_status']);
        }

        if (isset($p['filter_type']) && $p['filter_type'] != '') {
            $this->db->where('r.tr_registration_type', strtoupper($p['filter_type']));
        }

        // District filter — tr_district stores the district ID (integer)
        if (isset($p['filter_district']) && $p['filter_district'] != '') {
            $this->db->where('r.tr_district', (int)$p['filter_district']);
        }

        // Mandal filter — tr_mandal stores the mandal name (string)
        if (isset($p['filter_mandal']) && $p['filter_mandal'] != '') {
            $this->db->where('r.tr_mandal', $p['filter_mandal']);
        }

        if (isset($p['search']) && $p['search'] != '') {
            $s = $this->db->escape_like_str($p['search']);
            $this->db->where("(
                r.tr_mobile     LIKE '%{$s}%'
                OR r.tr_aadhar_no  LIKE '%{$s}%'
                OR r.tr_full_name  LIKE '%{$s}%'
                OR r.tr_reg_ukey   LIKE '%{$s}%'
                OR r.tr_mandal     LIKE '%{$s}%'
                OR tdt.tdt_district_name LIKE '%{$s}%'
            )");
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

        if (empty($data['tr_reg_key'])) {
            $data['tr_reg_key'] = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        }

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
                'tr_status'          => $status,
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
                'tr_password'        => $hashed,
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
