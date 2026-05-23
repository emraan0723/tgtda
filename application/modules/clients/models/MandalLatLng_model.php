<?php
/**
 * MandalLatLng_model
 * PHP 5.6 / 7.2 compatible
 * Place: application/modules/masters/models/MandalLatLng_model.php
 *
 * Handles all DB read/write for mandal & district lat/lng columns.
 * Runs a one-time auto-migration to add columns if missing.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class MandalLatLng_model extends CI_Model
{
    /* Columns we manage */
    const MANDAL_LAT   = 'tm_lat';
    const MANDAL_LNG   = 'tm_lng';
    const DISTRICT_LAT = 'tdt_lat';
    const DISTRICT_LNG = 'tdt_lng';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->_ensure_columns_exist();
    }

    /* ══════════════════════════════════════════════════
       AUTO-MIGRATION: add lat/lng columns if absent
       Runs silently on every page load — safe to repeat.
    ══════════════════════════════════════════════════ */
    private function _ensure_columns_exist()
    {
        /* Mandal table */
        $mandal_cols = $this->db->list_fields('tbl_mandal_masters');
        if ( ! in_array('tm_lat', $mandal_cols)) {
            $this->db->query(
                "ALTER TABLE `tbl_mandal_masters`
                 ADD COLUMN `tm_lat` DECIMAL(10,7) DEFAULT NULL
                     COMMENT 'Latitude for map pin' AFTER `tm_mandal`"
            );
        }
        if ( ! in_array('tm_lng', $mandal_cols)) {
            $this->db->query(
                "ALTER TABLE `tbl_mandal_masters`
                 ADD COLUMN `tm_lng` DECIMAL(10,7) DEFAULT NULL
                     COMMENT 'Longitude for map pin' AFTER `tm_lat`"
            );
        }

        /* District table */
        $district_cols = $this->db->list_fields('tbl_district_masters');
        if ( ! in_array('tdt_lat', $district_cols)) {
            $this->db->query(
                "ALTER TABLE `tbl_district_masters`
                 ADD COLUMN `tdt_lat` DECIMAL(10,7) DEFAULT NULL
                     COMMENT 'Latitude for district centre' AFTER `tdt_district_name`"
            );
        }
        if ( ! in_array('tdt_lng', $district_cols)) {
            $this->db->query(
                "ALTER TABLE `tbl_district_masters`
                 ADD COLUMN `tdt_lng` DECIMAL(10,7) DEFAULT NULL
                     COMMENT 'Longitude for district centre' AFTER `tdt_lat`"
            );
        }
    }

    /* ══════════════════════════════════════════════════
       COVERAGE STATS
    ══════════════════════════════════════════════════ */
    public function get_coverage_stats()
    {
        /* Mandal stats */
        $this->db->from('tbl_mandal_masters');
        $this->db->where('tm_mandal_status', 'ACTIVE');
        $total_mandals = $this->db->count_all_results();

        $this->db->from('tbl_mandal_masters');
        $this->db->where('tm_mandal_status', 'ACTIVE');
        $this->db->where('tm_lat IS NOT NULL', null, false);
        $this->db->where('tm_lng IS NOT NULL', null, false);
        $this->db->where('tm_lat !=', 0);
        $mandals_set = $this->db->count_all_results();

        /* District stats */
        $this->db->from('tbl_district_masters');
        $this->db->where('tdt_status', 'ACTIVE');
        $total_districts = $this->db->count_all_results();

        $this->db->from('tbl_district_masters');
        $this->db->where('tdt_status', 'ACTIVE');
        $this->db->where('tdt_lat IS NOT NULL', null, false);
        $this->db->where('tdt_lng IS NOT NULL', null, false);
        $this->db->where('tdt_lat !=', 0);
        $districts_set = $this->db->count_all_results();

        return array(
            'total_mandals'     => $total_mandals,
            'mandals_set'       => $mandals_set,
            'mandals_missing'   => $total_mandals - $mandals_set,
            'total_districts'   => $total_districts,
            'districts_set'     => $districts_set,
            'districts_missing' => $total_districts - $districts_set,
            'mandal_pct'        => $total_mandals > 0
                ? round(($mandals_set / $total_mandals) * 100) : 0,
            'district_pct'      => $total_districts > 0
                ? round(($districts_set / $total_districts) * 100) : 0,
        );
    }

    /* ══════════════════════════════════════════════════
       MANDAL LIST  (DataTables)
    ══════════════════════════════════════════════════ */
    private function _mandal_base_query($filters = array())
    {
        $this->db->select("
            mm.tm_mandal_ID,
            mm.tm_mandal,
            mm.tm_lat,
            mm.tm_lng,
            dm.tdt_district_ID,
            dm.tdt_district_name,
            sm.ts_state_ID,
            sm.ts_state_name
        ");
        $this->db->from('tbl_mandal_masters mm');
        $this->db->join('tbl_district_masters dm',
            'dm.tdt_district_ID = mm.tm_mandal_district_ID', 'left');
        $this->db->join('tbl_state_masters sm',
            'sm.ts_state_ID = mm.tm_mandal_state_ID', 'left');

        $this->db->where('mm.tm_mandal_status', 'ACTIVE');

        if ( ! empty($filters['district_id']) && $filters['district_id'] > 0)
            $this->db->where('mm.tm_mandal_district_ID', (int)$filters['district_id']);

        if ( ! empty($filters['state_id']) && $filters['state_id'] > 0)
            $this->db->where('mm.tm_mandal_state_ID', (int)$filters['state_id']);

        if ( ! empty($filters['has_latlng'])) {
            if ($filters['has_latlng'] === 'yes') {
                $this->db->where('mm.tm_lat IS NOT NULL', null, false);
                $this->db->where('mm.tm_lat !=', 0);
            } elseif ($filters['has_latlng'] === 'no') {
                $this->db->group_start();
                $this->db->where('mm.tm_lat IS NULL', null, false);
                $this->db->or_where('mm.tm_lat', 0);
                $this->db->group_end();
            }
        }

        if ( ! empty($filters['search'])) {
            $s = $filters['search'];
            $this->db->group_start();
            $this->db->like('mm.tm_mandal',        $s);
            $this->db->or_like('dm.tdt_district_name', $s);
            $this->db->or_like('sm.ts_state_name',     $s);
            $this->db->group_end();
        }
    }

    public function get_mandals_dt($filters = array())
    {
        $this->_mandal_base_query($filters);
        $this->db->order_by('sm.ts_state_name, dm.tdt_district_name, mm.tm_mandal', 'ASC');

        $len = isset($filters['length']) ? (int)$filters['length'] : 10;
        $st  = isset($filters['start'])  ? (int)$filters['start']  : 0;
        if ($len !== -1) $this->db->limit($len, $st);

        return $this->db->get()->result_array();
    }

    public function count_all()
    {
        $this->db->from('tbl_mandal_masters');
        $this->db->where('tm_mandal_status', 'ACTIVE');
        return $this->db->count_all_results();
    }

    public function count_filtered($filters = array())
    {
        $this->_mandal_base_query($filters);
        return $this->db->get()->num_rows();
    }

    /* ══════════════════════════════════════════════════
       DISTRICT LIST  (DataTables)
    ══════════════════════════════════════════════════ */
    private function _district_base_query($filters = array())
    {
        $this->db->select("
            dm.tdt_district_ID,
            dm.tdt_district_name,
            dm.tdt_lat,
            dm.tdt_lng,
            sm.ts_state_ID,
            sm.ts_state_name
        ");
        $this->db->from('tbl_district_masters dm');
        $this->db->join('tbl_state_masters sm',
            'sm.ts_state_ID = dm.tdt_state_ID', 'left');

        $this->db->where('dm.tdt_status', 'ACTIVE');

        if ( ! empty($filters['state_id']) && $filters['state_id'] > 0)
            $this->db->where('dm.tdt_state_ID', (int)$filters['state_id']);

        if ( ! empty($filters['has_latlng'])) {
            if ($filters['has_latlng'] === 'yes') {
                $this->db->where('dm.tdt_lat IS NOT NULL', null, false);
                $this->db->where('dm.tdt_lat !=', 0);
            } elseif ($filters['has_latlng'] === 'no') {
                $this->db->group_start();
                $this->db->where('dm.tdt_lat IS NULL', null, false);
                $this->db->or_where('dm.tdt_lat', 0);
                $this->db->group_end();
            }
        }

        if ( ! empty($filters['search'])) {
            $s = $filters['search'];
            $this->db->group_start();
            $this->db->like('dm.tdt_district_name', $s);
            $this->db->or_like('sm.ts_state_name',  $s);
            $this->db->group_end();
        }
    }

    public function get_districts_dt($filters = array())
    {
        $this->_district_base_query($filters);
        $this->db->order_by('sm.ts_state_name, dm.tdt_district_name', 'ASC');

        $len = isset($filters['length']) ? (int)$filters['length'] : 10;
        $st  = isset($filters['start'])  ? (int)$filters['start']  : 0;
        if ($len !== -1) $this->db->limit($len, $st);

        return $this->db->get()->result_array();
    }

    public function count_all_districts()
    {
        $this->db->from('tbl_district_masters');
        $this->db->where('tdt_status', 'ACTIVE');
        return $this->db->count_all_results();
    }

    public function count_filtered_districts($filters = array())
    {
        $this->_district_base_query($filters);
        return $this->db->get()->num_rows();
    }

    /* ══════════════════════════════════════════════════
       SINGLE RECORD FETCHERS
    ══════════════════════════════════════════════════ */
    public function get_mandal($mandal_id)
    {
        $this->db->select("
            mm.tm_mandal_ID AS id,
            mm.tm_mandal    AS name,
            mm.tm_lat       AS lat,
            mm.tm_lng       AS lng,
            dm.tdt_district_name AS district_name,
            sm.ts_state_name     AS state_name
        ");
        $this->db->from('tbl_mandal_masters mm');
        $this->db->join('tbl_district_masters dm',
            'dm.tdt_district_ID = mm.tm_mandal_district_ID', 'left');
        $this->db->join('tbl_state_masters sm',
            'sm.ts_state_ID = mm.tm_mandal_state_ID', 'left');
        $this->db->where('mm.tm_mandal_ID', (int)$mandal_id);
        $row = $this->db->get()->row_array();

        if ($row) {
            $row['lat']  = $row['lat']  ? (float)$row['lat']  : null;
            $row['lng']  = $row['lng']  ? (float)$row['lng']  : null;
            $row['type'] = 'mandal';
            /* Build geocode address for Nominatim suggestion */
            $row['geocode_hint'] = trim($row['name'] . ', ' .
                ($row['district_name'] ?? '') . ', ' .
                ($row['state_name']    ?? '') . ', India');
        }

        return $row ?: array();
    }

    public function get_district($district_id)
    {
        $this->db->select("
            dm.tdt_district_ID   AS id,
            dm.tdt_district_name AS name,
            dm.tdt_lat           AS lat,
            dm.tdt_lng           AS lng,
            sm.ts_state_name     AS state_name
        ");
        $this->db->from('tbl_district_masters dm');
        $this->db->join('tbl_state_masters sm',
            'sm.ts_state_ID = dm.tdt_state_ID', 'left');
        $this->db->where('dm.tdt_district_ID', (int)$district_id);
        $row = $this->db->get()->row_array();

        if ($row) {
            $row['lat']  = $row['lat']  ? (float)$row['lat']  : null;
            $row['lng']  = $row['lng']  ? (float)$row['lng']  : null;
            $row['type'] = 'district';
            $row['district_name'] = $row['name'];
            $row['geocode_hint']  = trim($row['name'] . ', ' .
                ($row['state_name'] ?? '') . ', India');
        }

        return $row ?: array();
    }

    /* ══════════════════════════════════════════════════
       SAVE LAT/LNG
    ══════════════════════════════════════════════════ */
    public function save_mandal_latlng($mandal_id, $lat, $lng)
    {
        $this->db->where('tm_mandal_ID', (int)$mandal_id);
        $this->db->update('tbl_mandal_masters', array(
            'tm_lat' => round($lat, 7),
            'tm_lng' => round($lng, 7),
        ));
        if ($this->db->affected_rows() >= 0) {
            return array(
                'status' => 'SUCCESS',
                'msg'    => 'Mandal coordinates saved successfully',
                'lat'    => round($lat, 7),
                'lng'    => round($lng, 7),
            );
        }
        return array('status' => 'ERROR', 'msg' => 'Database update failed');
    }

    public function save_district_latlng($district_id, $lat, $lng)
    {
        $this->db->where('tdt_district_ID', (int)$district_id);
        $this->db->update('tbl_district_masters', array(
            'tdt_lat' => round($lat, 7),
            'tdt_lng' => round($lng, 7),
        ));
        if ($this->db->affected_rows() >= 0) {
            return array(
                'status' => 'SUCCESS',
                'msg'    => 'District coordinates saved successfully',
                'lat'    => round($lat, 7),
                'lng'    => round($lng, 7),
            );
        }
        return array('status' => 'ERROR', 'msg' => 'Database update failed');
    }

    /* ══════════════════════════════════════════════════
       BULK AUTO-GEOCODE HELPERS
       Returns records that have no lat/lng set yet
    ══════════════════════════════════════════════════ */
    public function get_mandals_without_latlng($limit = 5)
    {
        $this->db->select("
            mm.tm_mandal_ID AS id,
            mm.tm_mandal    AS name,
            dm.tdt_district_name AS district_name,
            sm.ts_state_name     AS state_name,
            'mandal'             AS type
        ");
        $this->db->from('tbl_mandal_masters mm');
        $this->db->join('tbl_district_masters dm',
            'dm.tdt_district_ID = mm.tm_mandal_district_ID', 'left');
        $this->db->join('tbl_state_masters sm',
            'sm.ts_state_ID = mm.tm_mandal_state_ID', 'left');
        $this->db->where('mm.tm_mandal_status', 'ACTIVE');
        $this->db->group_start();
        $this->db->where('mm.tm_lat IS NULL', null, false);
        $this->db->or_where('mm.tm_lat', 0);
        $this->db->group_end();
        $this->db->order_by('sm.ts_state_name, dm.tdt_district_name, mm.tm_mandal', 'ASC');
        $this->db->limit((int)$limit);

        $rows = $this->db->get()->result_array();
        foreach ($rows as &$row) {
            $row['geocode_address'] = implode(', ', array_filter(array(
                $row['name'], $row['district_name'], $row['state_name'], 'India'
            )));
        }
        unset($row);
        return $rows;
    }

    public function get_districts_without_latlng($limit = 5)
    {
        $this->db->select("
            dm.tdt_district_ID   AS id,
            dm.tdt_district_name AS name,
            sm.ts_state_name     AS state_name,
            'district'           AS type
        ");
        $this->db->from('tbl_district_masters dm');
        $this->db->join('tbl_state_masters sm',
            'sm.ts_state_ID = dm.tdt_state_ID', 'left');
        $this->db->where('dm.tdt_status', 'ACTIVE');
        $this->db->group_start();
        $this->db->where('dm.tdt_lat IS NULL', null, false);
        $this->db->or_where('dm.tdt_lat', 0);
        $this->db->group_end();
        $this->db->order_by('sm.ts_state_name, dm.tdt_district_name', 'ASC');
        $this->db->limit((int)$limit);

        $rows = $this->db->get()->result_array();
        foreach ($rows as &$row) {
            $row['geocode_address'] = implode(', ', array_filter(array(
                $row['name'], $row['state_name'], 'India'
            )));
        }
        unset($row);
        return $rows;
    }
}
