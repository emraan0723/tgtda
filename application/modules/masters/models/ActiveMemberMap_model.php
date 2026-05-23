<?php
/**
 * ActiveMemberMap_model
 * PHP 5.6 / 7.2 compatible
 * Place: application/modules/masters/models/ActiveMemberMap_model.php
 *
 * !! ONE-TIME SQL — Add lat/lng to mandal master table !!
 * Run this once in phpMyAdmin or MySQL:
 *
 *   ALTER TABLE tbl_mandal_masters
 *     ADD COLUMN tm_lat  DECIMAL(10,7) DEFAULT NULL AFTER tm_mandal,
 *     ADD COLUMN tm_lng  DECIMAL(10,7) DEFAULT NULL AFTER tm_lat;
 *
 * Then populate via:
 *   UPDATE tbl_mandal_masters SET tm_lat=17.4563, tm_lng=78.5385
 *   WHERE tm_mandal_ID = 57;
 *
 * If lat/lng are NULL, the frontend uses OpenStreetMap Nominatim
 * geocoding as an automatic fallback (no API key required).
 *
 * Similarly for districts:
 *   ALTER TABLE tbl_district_masters
 *     ADD COLUMN tdt_lat DECIMAL(10,7) DEFAULT NULL AFTER tdt_district_name,
 *     ADD COLUMN tdt_lng DECIMAL(10,7) DEFAULT NULL AFTER tdt_lat;
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class ActiveMemberMap_model extends CI_Model
{
    /* All designation values (canonical order) */
    public static $DESIGNATIONS = array(
        'PRESIDENT',
        'VICE PRESIDENT',
        'GENERAL SECRETARY',
        'JOINT SECRETARY',
        'TREASURER',
        'EXECUTIVE MEMBER',
        'REGIONAL DISTRICT OFFICER',
        'DISTRICT OFFICER',
        'MANDAL OFFICER',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* ══════════════════════════════════════════════════════
       OVERALL STATISTICS  — top stat cards
    ══════════════════════════════════════════════════════ */
    public function get_overall_stats()
    {
        /* total active members */
        $this->db->where('tamm_status', 'ACTIVE');
        $total_members = $this->db->count_all_results('tbl_active_member_maping');

        /* distinct mandals */
        $this->db->select('COUNT(DISTINCT tamm_mandal_id) AS cnt');
        $this->db->where('tamm_status', 'ACTIVE');
        $q = $this->db->get('tbl_active_member_maping')->row_array();
        $total_mandals = isset($q['cnt']) ? (int)$q['cnt'] : 0;

        /* distinct districts */
        $this->db->select('COUNT(DISTINCT tamm_district_id) AS cnt');
        $this->db->where('tamm_status', 'ACTIVE');
        $q = $this->db->get('tbl_active_member_maping')->row_array();
        $total_districts = isset($q['cnt']) ? (int)$q['cnt'] : 0;

        /* distinct states */
        $this->db->select('COUNT(DISTINCT tamm_state_id) AS cnt');
        $this->db->where('tamm_status', 'ACTIVE');
        $q = $this->db->get('tbl_active_member_maping')->row_array();
        $total_states = isset($q['cnt']) ? (int)$q['cnt'] : 0;

        /* designation breakdown (ALL designations, zero-filled) */
        $this->db->select('tamm_designation AS designation, COUNT(*) AS cnt');
        $this->db->where('tamm_status', 'ACTIVE');
        $this->db->group_by('tamm_designation');
        $this->db->order_by('cnt', 'DESC');
        $desig_rows = $this->db->get('tbl_active_member_maping')->result_array();

        $desig_map = array();
        foreach (self::$DESIGNATIONS as $d) {
            $desig_map[$d] = 0;
        }
        foreach ($desig_rows as $dr) {
            $desig_map[$dr['designation']] = (int)$dr['cnt'];
        }

        return array(
            'total_members'   => $total_members,
            'total_mandals'   => $total_mandals,
            'total_districts' => $total_districts,
            'total_states'    => $total_states,
            'designations'    => $desig_map,
        );
    }

    /* ══════════════════════════════════════════════════════
       MANDAL-LEVEL MAP PINS
       One row per mandal with:
         - lat, lng (from tbl_mandal_masters; 0 if not set → frontend geocodes)
         - member_count (total)
         - designation_counts  (JSON object: {PRESIDENT:1, VICE PRESIDENT:2, ...})
         - geocode_address (for Nominatim fallback)
    ══════════════════════════════════════════════════════ */
    public function get_mandal_pins($filters = array())
    {
        $this->db->select("
            mm.tm_mandal_ID                  AS mandal_id,
            mm.tm_mandal                     AS mandal_name,
            IFNULL(mm.tm_lat, 0)             AS lat,
            IFNULL(mm.tm_lng, 0)             AS lng,
            dm.tdt_district_ID               AS district_id,
            dm.tdt_district_name             AS district_name,
            sm.ts_state_ID                   AS state_id,
            sm.ts_state_name                 AS state_name,
            cm.tc_country_ID                 AS country_id,
            cm.tc_country_name               AS country_name,
            COUNT(tamm.tamm_id)              AS member_count,
            GROUP_CONCAT(
                tamm.tamm_designation
                ORDER BY tamm.tamm_designation
                SEPARATOR '||'
            )                                AS desig_list,
            GROUP_CONCAT(
                tr.tr_full_name
                ORDER BY tamm.tamm_designation, tr.tr_full_name
                SEPARATOR '||'
            )                                AS member_names
        ");

        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_mandal_masters mm',
            'mm.tm_mandal_ID = tamm.tamm_mandal_id', 'inner');
        $this->db->join('tbl_district_masters dm',
            'dm.tdt_district_ID = tamm.tamm_district_id', 'left');
        $this->db->join('tbl_state_masters sm',
            'sm.ts_state_ID = tamm.tamm_state_id', 'left');
        $this->db->join('tbl_countries_masters cm',
            'cm.tc_country_ID = tamm.tamm_country_id', 'left');
        $this->db->join('tbl_registrations tr',
            'tr.tr_id = tamm.tamm_active_member_id', 'left');

        $this->db->where('tamm.tamm_status', 'ACTIVE');

        if ( ! empty($filters['country_id'])  && $filters['country_id']  > 0)
            $this->db->where('tamm.tamm_country_id',  (int)$filters['country_id']);
        if ( ! empty($filters['state_id'])    && $filters['state_id']    > 0)
            $this->db->where('tamm.tamm_state_id',    (int)$filters['state_id']);
        if ( ! empty($filters['district_id']) && $filters['district_id'] > 0)
            $this->db->where('tamm.tamm_district_id', (int)$filters['district_id']);
        if ( ! empty($filters['mandal_id'])   && $filters['mandal_id']   > 0)
            $this->db->where('tamm.tamm_mandal_id',   (int)$filters['mandal_id']);

        $this->db->group_by('tamm.tamm_mandal_id');
        $this->db->order_by(
            'cm.tc_country_name, sm.ts_state_name, dm.tdt_district_name, mm.tm_mandal',
            'ASC'
        );

        $rows = $this->db->get()->result_array();
       // echo $this->db->last_query(); exit;

        foreach ($rows as &$row) {
            $row['member_count'] = (int)$row['member_count'];
            $row['lat']          = (float)$row['lat'];
            $row['lng']          = (float)$row['lng'];

            /* Build designation_counts from pipe-separated desig_list */
            $desig_counts = array();
            foreach (self::$DESIGNATIONS as $d) {
                $desig_counts[$d] = 0;
            }
            if ( ! empty($row['desig_list'])) {
                $items = explode('||', $row['desig_list']);
                foreach ($items as $item) {
                    $item = trim($item);
                    if (isset($desig_counts[$item])) {
                        $desig_counts[$item]++;
                    } else {
                        $desig_counts[$item] = isset($desig_counts[$item])
                            ? $desig_counts[$item] + 1 : 1;
                    }
                }
            }
            $row['designation_counts'] = $desig_counts;
            unset($row['desig_list']);

            /* Member names as array (first 5 for popup preview) */
            $names = array();
            if ( ! empty($row['member_names'])) {
                $names = array_values(array_filter(
                    array_map('trim', explode('||', $row['member_names']))
                ));
            }
            $row['member_names_arr'] = $names;
            unset($row['member_names']);

            /* Geocoding address for Nominatim fallback */
            $row['geocode_address'] = implode(', ', array_filter(array(
                $row['mandal_name'],
                $row['district_name'],
                $row['state_name'],
                $row['country_name'],
            )));
        }
        unset($row);

        return $rows;
    }

    /* ══════════════════════════════════════════════════════
       DISTRICT-LEVEL SUMMARY  (side panel list)
       One row per district with member_count, mandal_count,
       and designation_counts breakdown
    ══════════════════════════════════════════════════════ */
    public function get_district_summary($filters = array())
    {
        $this->db->select("
            dm.tdt_district_ID                       AS district_id,
            dm.tdt_district_name                     AS district_name,
            IFNULL(dm.tdt_lat, 0)                    AS lat,
            IFNULL(dm.tdt_lng, 0)                    AS lng,
            sm.ts_state_name                         AS state_name,
            cm.tc_country_name                       AS country_name,
            COUNT(tamm.tamm_id)                      AS member_count,
            COUNT(DISTINCT tamm.tamm_mandal_id)      AS mandal_count,
            GROUP_CONCAT(
                tamm.tamm_designation
                ORDER BY tamm.tamm_designation
                SEPARATOR '||'
            )                                        AS desig_list
        ");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_district_masters dm',
            'dm.tdt_district_ID = tamm.tamm_district_id', 'left');
        $this->db->join('tbl_state_masters sm',
            'sm.ts_state_ID = tamm.tamm_state_id', 'left');
        $this->db->join('tbl_countries_masters cm',
            'cm.tc_country_ID = tamm.tamm_country_id', 'left');

        $this->db->where('tamm.tamm_status', 'ACTIVE');

        if ( ! empty($filters['country_id']) && $filters['country_id'] > 0)
            $this->db->where('tamm.tamm_country_id', (int)$filters['country_id']);
        if ( ! empty($filters['state_id'])   && $filters['state_id']   > 0)
            $this->db->where('tamm.tamm_state_id',   (int)$filters['state_id']);

        $this->db->group_by('tamm.tamm_district_id');
        $this->db->order_by('dm.tdt_district_name', 'ASC');

        $rows = $this->db->get()->result_array();

        foreach ($rows as &$row) {
            $row['member_count'] = (int)$row['member_count'];
            $row['mandal_count'] = (int)$row['mandal_count'];
            $row['lat']          = (float)$row['lat'];
            $row['lng']          = (float)$row['lng'];

            /* Designation counts */
            $desig_counts = array();
            foreach (self::$DESIGNATIONS as $d) { $desig_counts[$d] = 0; }
            if ( ! empty($row['desig_list'])) {
                $items = explode('||', $row['desig_list']);
                foreach ($items as $item) {
                    $item = trim($item);
                    if (isset($desig_counts[$item])) $desig_counts[$item]++;
                }
            }
            $row['designation_counts'] = $desig_counts;
            unset($row['desig_list']);
        }
        unset($row);

        return $rows;
    }

    /* ══════════════════════════════════════════════════════
       FULL MEMBER LIST FOR SIDEBAR
       All members in a mandal with complete registration details
    ══════════════════════════════════════════════════════ */
    public function get_members_by_mandal($mandal_id = 0)
    {
        $mandal_id = (int)$mandal_id;
        if ($mandal_id <= 0) return array();

        $this->db->select("
            tamm.tamm_id,
            tamm.tamm_designation,
            tamm.tamm_status,
            tamm.tamm_key,
            tamm.tamm_mandal_id,
            tamm.tamm_district_id,
            tamm.tamm_state_id,
            tamm.tamm_country_id,
            tr.tr_id,
            tr.tr_full_name,
            tr.tr_mobile,
            tr.tr_email,
            tr.tr_selfie,
            tr.tr_reg_key,
            tr.tr_reg_ukey,
            tr.tr_language,
            tr.tr_registration_type,
            tr.tr_aadhar_no,
            tr.tr_pan_no,
            tr.tr_full_address,
            tr.tr_dob,
            tr.tr_village,
            tr.tr_pincode,
            tr.tr_status        AS reg_status,
            mm.tm_mandal        AS mandal_name,
            dm.tdt_district_name AS district_name,
            sm.ts_state_name    AS state_name,
            cm.tc_country_name  AS country_name
        ");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_registrations tr',
            'tr.tr_id = tamm.tamm_active_member_id', 'left');
        $this->db->join('tbl_mandal_masters mm',
            'mm.tm_mandal_ID = tamm.tamm_mandal_id', 'left');
        $this->db->join('tbl_district_masters dm',
            'dm.tdt_district_ID = tamm.tamm_district_id', 'left');
        $this->db->join('tbl_state_masters sm',
            'sm.ts_state_ID = tamm.tamm_state_id', 'left');
        $this->db->join('tbl_countries_masters cm',
            'cm.tc_country_ID = tamm.tamm_country_id', 'left');

        $this->db->where('tamm.tamm_mandal_id', $mandal_id);
        $this->db->where('tamm.tamm_status', 'ACTIVE');

        /* Order by designation canonical order */
        $desig_order = implode("','", self::$DESIGNATIONS);
        $this->db->order_by(
            "FIELD(tamm.tamm_designation, '" . $desig_order . "')",
            '', false
        );
        $this->db->order_by('tr.tr_full_name', 'ASC');

        return $this->db->get()->result_array();
    }

    /* ══════════════════════════════════════════════════════
       DESIGNATION BREAKDOWN FOR A DISTRICT
    ══════════════════════════════════════════════════════ */
    public function get_district_designations($district_id = 0)
    {
        $district_id = (int)$district_id;
        if ($district_id <= 0) return array();

        $this->db->select("
            tamm.tamm_designation AS designation,
            COUNT(*)              AS cnt,
            GROUP_CONCAT(tr.tr_full_name ORDER BY tr.tr_full_name SEPARATOR ', ')
                                  AS member_names
        ");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_registrations tr',
            'tr.tr_id = tamm.tamm_active_member_id', 'left');
        $this->db->where('tamm.tamm_district_id', $district_id);
        $this->db->where('tamm.tamm_status', 'ACTIVE');
        $this->db->group_by('tamm.tamm_designation');

        $desig_order = implode("','", self::$DESIGNATIONS);
        $this->db->order_by(
            "FIELD(tamm.tamm_designation, '" . $desig_order . "')",
            '', false
        );

        $rows = $this->db->get()->result_array();
        foreach ($rows as &$row) {
            $row['cnt'] = (int)$row['cnt'];
        }
        unset($row);
        return $rows;
    }
}
