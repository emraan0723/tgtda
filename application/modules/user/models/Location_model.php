<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ─────────────────────────────────────────────
    // Get all ACTIVE countries
    // ─────────────────────────────────────────────
    public function get_countries() {
        return $this->db
            ->where('tc_country_status', 'ACTIVE')
            ->order_by('tc_country_name', 'ASC')
            ->get('tbl_countries_masters')
            ->result_array();
    }

    // ─────────────────────────────────────────────
    // Get states by country ID
    // ─────────────────────────────────────────────
    public function get_states_by_country($country_id) {
        return $this->db
            ->where('ts_country_id', (int)$country_id)
            ->where('ts_status', 'ACTIVE')
            ->order_by('ts_state_name', 'ASC')
            ->get('tbl_state_masters')
            ->result_array();
    }

    // ─────────────────────────────────────────────
    // Get districts by state ID
    // ─────────────────────────────────────────────
    public function get_districts_by_state($state_id) {
        return $this->db
            ->where('tdt_state_ID', (int)$state_id)
            ->where('tdt_status', 'ACTIVE')
            ->order_by('tdt_district_name', 'ASC')
            ->get('tbl_district_masters')
            ->result_array();
    }

    // ─────────────────────────────────────────────
    // Get ALL active districts (for table filter dropdown)
    // ─────────────────────────────────────────────
    public function get_all_districts() {
        return $this->db
            ->where('tdt_status', 'ACTIVE')
            ->order_by('tdt_district_name', 'ASC')
            ->get('tbl_district_masters')
            ->result_array();
    }

    // ─────────────────────────────────────────────
    // Get cities by district ID
    // ─────────────────────────────────────────────
    public function get_cities_by_district($district_id) {
        return $this->db
            ->where('tc_city_district_ID', (int)$district_id)
            ->where('tc_city_status', 'ACTIVE')
            ->order_by('tc_city_name', 'ASC')
            ->get('tbl_city_masters')
            ->result_array();
    }

    // ─────────────────────────────────────────────
    // Get single state by ID — returns ts_country_id too for country restore
    // ─────────────────────────────────────────────
    public function get_state_by_id($state_id) {
        return $this->db
            ->where('ts_state_ID', (int)$state_id)
            ->get('tbl_state_masters')
            ->row_array();
    }

    // ─────────────────────────────────────────────
    // Get single district by ID (for edit form name restore)
    // ─────────────────────────────────────────────
    public function get_district_by_id($district_id) {
        return $this->db
            ->where('tdt_district_ID', (int)$district_id)
            ->get('tbl_district_masters')
            ->row_array();
    }

    // ─────────────────────────────────────────────
    // Get mandals by district ID
    // ─────────────────────────────────────────────
    public function get_mandals_by_district($district_id) {
        return $this->db
            ->where('tm_mandal_district_ID', (int)$district_id)
            ->where('tm_mandal_status', 'ACTIVE')
            ->order_by('tm_mandal', 'ASC')
            ->get('tbl_mandal_masters')
            ->result_array();
    }
}
