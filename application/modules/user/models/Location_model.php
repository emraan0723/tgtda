<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Location_model — with lat/lng save methods
 *
 * NEW METHODS ADDED (add these to your existing Location_model.php):
 *
 *  save_mandal_latlng($mandal_id, $lat, $lng)
 *  save_district_latlng($district_id, $lat, $lng)
 *  save_state_latlng($state_id, $lat, $lng)
 *  get_mandal_latlng($mandal_id)
 *  get_district_latlng($district_id)
 *  get_state_latlng($state_id)
 *
 * DB columns required:
 *  tbl_mandal_masters   → tm_lat   DECIMAL(10,7), tm_lng DECIMAL(10,7)
 *  tbl_district_masters → tdt_lat  DECIMAL(10,7), tdt_lng DECIMAL(10,7)
 *  tbl_state_masters    → ts_lat   DECIMAL(10,7), ts_lng DECIMAL(10,7)
 *
 * SQL to add the columns (run once in phpMyAdmin):
 *
 *   ALTER TABLE tbl_mandal_masters
 *     ADD COLUMN tm_lat  DECIMAL(10,7) NULL DEFAULT NULL,
 *     ADD COLUMN tm_lng  DECIMAL(10,7) NULL DEFAULT NULL;
 *
 *   ALTER TABLE tbl_district_masters
 *     ADD COLUMN tdt_lat DECIMAL(10,7) NULL DEFAULT NULL,
 *     ADD COLUMN tdt_lng DECIMAL(10,7) NULL DEFAULT NULL;
 *
 *   ALTER TABLE tbl_state_masters
 *     ADD COLUMN ts_lat  DECIMAL(10,7) NULL DEFAULT NULL,
 *     ADD COLUMN ts_lng  DECIMAL(10,7) NULL DEFAULT NULL;
 */
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

    // ═════════════════════════════════════════════
    // ── NEW: LAT/LNG SAVE METHODS ────────────────
    // ═════════════════════════════════════════════

    /**
     * Save lat/lng to tbl_mandal_masters
     * Only updates if the row doesn't already have coordinates,
     * OR if force=true (always overwrite).
     */
    public function save_mandal_latlng($mandal_id, $lat, $lng, $force = false) {
        $mandal_id = (int)$mandal_id;
        $lat       = (float)$lat;
        $lng       = (float)$lng;

        // PHP 7.2 safe: cast to float first, then compare with != 0.0
        if ($mandal_id === 0 || $lat == 0.0 || $lng == 0.0) {
            return false;
        }

        if (!$force) {
            // Only save if not already set
            $existing = $this->db
                ->select('tm_lat, tm_lng')
                ->where('tm_mandal_ID', $mandal_id)
                ->get('tbl_mandal_masters')
                ->row_array();

            // Use isset + != 0 instead of empty() on float (empty(0.0)=true in PHP)
            if (!empty($existing)
                && isset($existing['tm_lat'])
                && isset($existing['tm_lng'])
                && (float)$existing['tm_lat'] != 0.0
                && (float)$existing['tm_lng'] != 0.0
            ) {
                return true; // already has coordinates, skip
            }
        }

        return $this->db->update(
            'tbl_mandal_masters',
            array('tm_lat' => $lat, 'tm_lng' => $lng),
            array('tm_mandal_ID' => $mandal_id)
        );
    }

    /**
     * Save lat/lng to tbl_district_masters
     */
    public function save_district_latlng($district_id, $lat, $lng, $force = false) {
        $district_id = (int)$district_id;
        $lat         = (float)$lat;
        $lng         = (float)$lng;

        if ($district_id === 0 || $lat == 0.0 || $lng == 0.0) {
            return false;
        }

        if (!$force) {
            $existing = $this->db
                ->select('tdt_lat, tdt_lng')
                ->where('tdt_district_ID', $district_id)
                ->get('tbl_district_masters')
                ->row_array();

            if (!empty($existing)
                && isset($existing['tdt_lat'])
                && isset($existing['tdt_lng'])
                && (float)$existing['tdt_lat'] != 0.0
                && (float)$existing['tdt_lng'] != 0.0
            ) {
                return true;
            }
        }

        return $this->db->update(
            'tbl_district_masters',
            array('tdt_lat' => $lat, 'tdt_lng' => $lng),
            array('tdt_district_ID' => $district_id)
        );
    }

    /**
     * Save lat/lng to tbl_state_masters
     */
    public function save_state_latlng($state_id, $lat, $lng, $force = false) {
        $state_id = (int)$state_id;
        $lat      = (float)$lat;
        $lng      = (float)$lng;

        if ($state_id === 0 || $lat == 0.0 || $lng == 0.0) {
            return false;
        }

        if (!$force) {
            $existing = $this->db
                ->select('ts_lat, ts_lng')
                ->where('ts_state_ID', $state_id)
                ->get('tbl_state_masters')
                ->row_array();

            if (!empty($existing)
                && isset($existing['ts_lat'])
                && isset($existing['ts_lng'])
                && (float)$existing['ts_lat'] != 0.0
                && (float)$existing['ts_lng'] != 0.0
            ) {
                return true;
            }
        }

        return $this->db->update(
            'tbl_state_masters',
            array('ts_lat' => $lat, 'ts_lng' => $lng),
            array('ts_state_ID' => $state_id)
        );
    }

    // ─────────────────────────────────────────────
    // Getters — return existing lat/lng (used by JS to
    // skip Nominatim calls when coordinates already exist)
    // PHP 7.2: ternary used instead of null coalescing chain
    // ─────────────────────────────────────────────

    public function get_mandal_latlng($mandal_id) {
        $row = $this->db
            ->select('tm_lat, tm_lng')
            ->where('tm_mandal_ID', (int)$mandal_id)
            ->get('tbl_mandal_masters')
            ->row_array();

        if (!empty($row)) {
            return array(
                'lat' => isset($row['tm_lat']) ? $row['tm_lat'] : null,
                'lng' => isset($row['tm_lng']) ? $row['tm_lng'] : null,
            );
        }
        return array('lat' => null, 'lng' => null);
    }

    public function get_district_latlng($district_id) {
        $row = $this->db
            ->select('tdt_lat, tdt_lng')
            ->where('tdt_district_ID', (int)$district_id)
            ->get('tbl_district_masters')
            ->row_array();

        if (!empty($row)) {
            return array(
                'lat' => isset($row['tdt_lat']) ? $row['tdt_lat'] : null,
                'lng' => isset($row['tdt_lng']) ? $row['tdt_lng'] : null,
            );
        }
        return array('lat' => null, 'lng' => null);
    }

    public function get_state_latlng($state_id) {
        $row = $this->db
            ->select('ts_lat, ts_lng')
            ->where('ts_state_ID', (int)$state_id)
            ->get('tbl_state_masters')
            ->row_array();

        if (!empty($row)) {
            return array(
                'lat' => isset($row['ts_lat']) ? $row['ts_lat'] : null,
                'lng' => isset($row['ts_lng']) ? $row['ts_lng'] : null,
            );
        }
        return array('lat' => null, 'lng' => null);
    }
}
