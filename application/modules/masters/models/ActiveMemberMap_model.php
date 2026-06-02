<?php
/**
 * ActiveMemberMap_model
 * PHP 5.6 / 7.2 compatible
 * Place: application/modules/masters/models/ActiveMemberMap_model.php
 *
 * TABLE NAMES (your actual schema):
 *   tbl_active_member_maping   — tamm_id, tamm_active_member_id, tamm_mandal_id,
 *                                tamm_district_id, tamm_state_id, tamm_country_id,
 *                                tamm_designation, tamm_status
 *   tbl_registrations          — tr_id, tr_full_name, tr_mobile, tr_email,
 *                                tr_selfie, tr_reg_key, tr_status
 *   tbl_mandal_masters         — tm_mandal_ID, tm_mandal, tm_lat, tm_lng
 *   tbl_district_masters       — tdt_district_ID, tdt_district_name, tdt_lat, tdt_lng
 *   tbl_state_masters          — ts_state_ID, ts_state_name
 *   tbl_countries_masters      — tc_country_ID, tc_country_name
 *
 * ONE-TIME SQL (if not already run):
 *   ALTER TABLE tbl_mandal_masters
 *     ADD COLUMN tm_lat  DECIMAL(10,7) DEFAULT NULL,
 *     ADD COLUMN tm_lng  DECIMAL(10,7) DEFAULT NULL;
 *
 *   ALTER TABLE tbl_district_masters
 *     ADD COLUMN tdt_lat DECIMAL(10,7) DEFAULT NULL,
 *     ADD COLUMN tdt_lng DECIMAL(10,7) DEFAULT NULL;
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class ActiveMemberMap_model extends CI_Model
{
    /* Canonical designation order */
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
       get_overall_stats()
       Returns:
         total_registered — all rows in tbl_registrations with status='active'
         total_members    — active mapped members (tamm_status = 'ACTIVE')
         total_mandals    — distinct mandals
         total_districts  — distinct districts
         total_states     — distinct states
         designations     — count per designation (zero-filled)
    ══════════════════════════════════════════════════════ */
    public function get_overall_stats()
    {
        /* Total Registered (green stat) — tbl_registrations status active */
        $this->db->where('tr_status', 'active');
        $total_registered = $this->db->count_all_results('tbl_registrations');

        /* Active mapped members (blue stat) */
        $this->db->where('tamm_status', 'ACTIVE');
        $total_members = $this->db->count_all_results('tbl_active_member_maping');

        /* Distinct mandals */
        $this->db->select('COUNT(DISTINCT tamm_mandal_id) AS cnt');
        $this->db->where('tamm_status', 'ACTIVE');
        $q = $this->db->get('tbl_active_member_maping')->row_array();
        $total_mandals = isset($q['cnt']) ? (int)$q['cnt'] : 0;

        /* Distinct districts */
        $this->db->select('COUNT(DISTINCT tamm_district_id) AS cnt');
        $this->db->where('tamm_status', 'ACTIVE');
        $q = $this->db->get('tbl_active_member_maping')->row_array();
        $total_districts = isset($q['cnt']) ? (int)$q['cnt'] : 0;

        /* Distinct states */
        $this->db->select('COUNT(DISTINCT tamm_state_id) AS cnt');
        $this->db->where('tamm_status', 'ACTIVE');
        $q = $this->db->get('tbl_active_member_maping')->row_array();
        $total_states = isset($q['cnt']) ? (int)$q['cnt'] : 0;

        /* Designation breakdown (zero-filled) */
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
            $key = trim($dr['designation']);
            $desig_map[$key] = (int)$dr['cnt'];
        }

        return array(
            'total_registered' => $total_registered,
            'total_members'    => $total_members,
            'total_mandals'    => $total_mandals,
            'total_districts'  => $total_districts,
            'total_states'     => $total_states,
            'designations'     => $desig_map,
        );
    }

    /* ══════════════════════════════════════════════════════
       get_mandal_pins($filters)
       One row per mandal that has active members.
       Includes lat/lng (0 if not set → JS geocodes via server proxy),
       member_count, designation_counts, member_names_arr,
       geocode_address fallback string.
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

        if (!empty($filters['country_id'])  && $filters['country_id']  > 0)
            $this->db->where('tamm.tamm_country_id',  (int)$filters['country_id']);
        if (!empty($filters['state_id'])    && $filters['state_id']    > 0)
            $this->db->where('tamm.tamm_state_id',    (int)$filters['state_id']);
        if (!empty($filters['district_id']) && $filters['district_id'] > 0)
            $this->db->where('tamm.tamm_district_id', (int)$filters['district_id']);
        if (!empty($filters['mandal_id'])   && $filters['mandal_id']   > 0)
            $this->db->where('tamm.tamm_mandal_id',   (int)$filters['mandal_id']);

        $this->db->group_by('tamm.tamm_mandal_id');
        $this->db->order_by(
            'cm.tc_country_name, sm.ts_state_name, dm.tdt_district_name, mm.tm_mandal',
            'ASC'
        );

        $rows = $this->db->get()->result_array();

        foreach ($rows as &$row) {
            $row['member_count'] = (int)$row['member_count'];
            $row['active_count'] = (int)$row['member_count']; /* all returned rows are active */
            $row['lat']          = (float)$row['lat'];
            $row['lng']          = (float)$row['lng'];

            /* Build designation_counts */
            $desig_counts = array();
            foreach (self::$DESIGNATIONS as $d) { $desig_counts[$d] = 0; }
            if (!empty($row['desig_list'])) {
                $items = explode('||', $row['desig_list']);
                foreach ($items as $item) {
                    $item = trim($item);
                    if (array_key_exists($item, $desig_counts)) {
                        $desig_counts[$item]++;
                    } else {
                        $desig_counts[$item] = 1;
                    }
                }
            }
            $row['designation_counts'] = $desig_counts;
            unset($row['desig_list']);

            /* Member names array (first 5 for reference) */
            $names = array();
            if (!empty($row['member_names'])) {
                $names = array_values(array_filter(
                    array_map('trim', explode('||', $row['member_names']))
                ));
            }
            $row['member_names_arr'] = $names;
            unset($row['member_names']);

            /* Geocoding address for server-side Nominatim fallback */
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
       get_members_by_mandal($mandal_id)
       Full member list for the modal popup.
       Returns: tr_full_name, tr_mobile, tr_selfie, tr_reg_key,
                tamm_designation, district_name, state_name etc.
       Ordered by canonical designation order then name.
    ══════════════════════════════════════════════════════ */
    public function get_members_by_mandal($mandal_id = 0)
    {
        $mandal_id = (int)$mandal_id;
        if ($mandal_id <= 0) return array();

        $this->db->select("
            tamm.tamm_id,
            tamm.tamm_designation,
            tamm.tamm_status,
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
            tr.tr_language,
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

        /* Order by canonical designation order */
        $desig_order = implode("','", self::$DESIGNATIONS);
        $this->db->order_by(
            "FIELD(tamm.tamm_designation, '" . $desig_order . "')",
            '', false
        );
        $this->db->order_by('tr.tr_full_name', 'ASC');

        return $this->db->get()->result_array();
    }

    /* ══════════════════════════════════════════════════════
       get_state_members_panel($filters)
       NEW: State-wise active member list for the right panel.
       Returns: [{state_name, members:[{tr_full_name, tr_mobile,
                  tr_selfie, tr_reg_key, tamm_designation,
                  district_name, mandal_id}]}]
    ══════════════════════════════════════════════════════ */
    public function get_state_members_panel($filters = array())
    {
        $this->db->select("
            tamm.tamm_mandal_id              AS mandal_id,
            tamm.tamm_designation,
            tr.tr_full_name,
            tr.tr_mobile,
            tr.tr_selfie,
            tr.tr_reg_key,
            dm.tdt_district_name             AS district_name,
            sm.ts_state_ID                   AS state_id,
            sm.ts_state_name                 AS state_name
        ");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_registrations tr',
            'tr.tr_id = tamm.tamm_active_member_id', 'left');
        $this->db->join('tbl_district_masters dm',
            'dm.tdt_district_ID = tamm.tamm_district_id', 'left');
        $this->db->join('tbl_state_masters sm',
            'sm.ts_state_ID = tamm.tamm_state_id', 'left');

        $this->db->where('tamm.tamm_status', 'ACTIVE');

        if (!empty($filters['country_id'])  && $filters['country_id']  > 0)
            $this->db->where('tamm.tamm_country_id',  (int)$filters['country_id']);
        if (!empty($filters['state_id'])    && $filters['state_id']    > 0)
            $this->db->where('tamm.tamm_state_id',    (int)$filters['state_id']);
        if (!empty($filters['district_id']) && $filters['district_id'] > 0)
            $this->db->where('tamm.tamm_district_id', (int)$filters['district_id']);

        $this->db->order_by('sm.ts_state_name ASC, tr.tr_full_name ASC');

        $rows = $this->db->get()->result_array();

        /* Group by state */
        $grouped    = array();
        $stateOrder = array();

        foreach ($rows as $row) {
            $sid = $row['state_id'];
            if (!isset($grouped[$sid])) {
                $grouped[$sid]  = array(
                    'state_name' => $row['state_name'],
                    'members'    => array(),
                );
                $stateOrder[] = $sid;
            }
            $grouped[$sid]['members'][] = array(
                'tr_full_name'     => $row['tr_full_name'],
                'tr_mobile'        => $row['tr_mobile'],
                'tr_selfie'        => $row['tr_selfie'],
                'tr_reg_key'       => $row['tr_reg_key'],
                'tamm_designation' => $row['tamm_designation'],
                'mandal_id'        => $row['mandal_id'],
                'district_name'    => $row['district_name'],
            );
        }

        $result = array();
        foreach ($stateOrder as $sid) {
            $result[] = $grouped[$sid];
        }
        return $result;
    }

    /* ══════════════════════════════════════════════════════
       get_district_summary($filters)
       (Kept for compatibility)
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

        if (!empty($filters['country_id']) && $filters['country_id'] > 0)
            $this->db->where('tamm.tamm_country_id', (int)$filters['country_id']);
        if (!empty($filters['state_id'])   && $filters['state_id']   > 0)
            $this->db->where('tamm.tamm_state_id',   (int)$filters['state_id']);

        $this->db->group_by('tamm.tamm_district_id');
        $this->db->order_by('dm.tdt_district_name', 'ASC');

        $rows = $this->db->get()->result_array();

        foreach ($rows as &$row) {
            $row['member_count'] = (int)$row['member_count'];
            $row['mandal_count'] = (int)$row['mandal_count'];
            $row['lat']          = (float)$row['lat'];
            $row['lng']          = (float)$row['lng'];

            $desig_counts = array();
            foreach (self::$DESIGNATIONS as $d) { $desig_counts[$d] = 0; }
            if (!empty($row['desig_list'])) {
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
       get_district_designations($district_id)
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