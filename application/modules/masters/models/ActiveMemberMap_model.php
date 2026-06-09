<?php
/**
 * ActiveMemberMap_model — PHP 5.6 / 7.2 compatible
 * Place: application/modules/masters/models/ActiveMemberMap_model.php
 *
 * FIXES:
 *  - get_registered_by_location: broken raw WHERE removed, uses tr_mandal direct
 *  - No duplicates: registered members who are also active are excluded from reg list
 *  - Mobile shown for: registered members + RDO + District Officer + Mandal Officer
 *  - Mobile hidden for: PRESIDENT,VP,GS,JS,TREASURER,EXECUTIVE MEMBER
 *  - Email shown for everyone
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class ActiveMemberMap_model extends CI_Model
{
    public static $DESIGNATIONS = array(
        'PRESIDENT','VICE PRESIDENT','GENERAL SECRETARY','JOINT SECRETARY',
        'TREASURER','EXECUTIVE MEMBER','REGIONAL DISTRICT OFFICER',
        'DISTRICT OFFICER','MANDAL OFFICER',
    );

    /* Designations that SHOW mobile */
    public static $SHOW_MOBILE_DESIGS = array(
        'REGIONAL DISTRICT OFFICER','DISTRICT OFFICER','MANDAL OFFICER',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* ══════════════════════════════════════════════════════
       STATS
    ══════════════════════════════════════════════════════ */
    public function get_overall_stats()
    {
        $this->db->where('tr_status','active');
        $total_registered = $this->db->count_all_results('tbl_registrations');

        $this->db->where('tamm_status','ACTIVE');
        $total_members = $this->db->count_all_results('tbl_active_member_maping');

        $this->db->select('COUNT(DISTINCT tamm_mandal_id) AS cnt');
        $this->db->where('tamm_status','ACTIVE');
        $q = $this->db->get('tbl_active_member_maping')->row_array();
        $total_mandals = isset($q['cnt']) ? (int)$q['cnt'] : 0;

        $this->db->select('COUNT(DISTINCT tamm_district_id) AS cnt');
        $this->db->where('tamm_status','ACTIVE');
        $q = $this->db->get('tbl_active_member_maping')->row_array();
        $total_districts = isset($q['cnt']) ? (int)$q['cnt'] : 0;

        $this->db->select('COUNT(DISTINCT tamm_state_id) AS cnt');
        $this->db->where('tamm_status','ACTIVE');
        $q = $this->db->get('tbl_active_member_maping')->row_array();
        $total_states = isset($q['cnt']) ? (int)$q['cnt'] : 0;

        $this->db->select('tamm_designation AS designation, COUNT(*) AS cnt');
        $this->db->where('tamm_status','ACTIVE');
        $this->db->group_by('tamm_designation');
        $desig_rows = $this->db->get('tbl_active_member_maping')->result_array();

        $desig_map = array();
        foreach (self::$DESIGNATIONS as $d) { $desig_map[$d] = 0; }
        foreach ($desig_rows as $dr) { $desig_map[trim($dr['designation'])] = (int)$dr['cnt']; }

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
       PINK PINS — active members per mandal
    ══════════════════════════════════════════════════════ */
    public function get_mandal_pins($filters = array())
    {
        $this->db->select("
            mm.tm_mandal_ID          AS mandal_id,
            mm.tm_mandal             AS mandal_name,
            IFNULL(mm.tm_lat,0)      AS lat,
            IFNULL(mm.tm_lng,0)      AS lng,
            dm.tdt_district_ID       AS district_id,
            dm.tdt_district_name     AS district_name,
            sm.ts_state_ID           AS state_id,
            sm.ts_state_name         AS state_name,
            cm.tc_country_ID         AS country_id,
            cm.tc_country_name       AS country_name,
            COUNT(tamm.tamm_id)      AS member_count,
            GROUP_CONCAT(tamm.tamm_designation ORDER BY tamm.tamm_designation SEPARATOR '||') AS desig_list,
            GROUP_CONCAT(tr.tr_full_name       ORDER BY tr.tr_full_name       SEPARATOR '||') AS member_names
        ");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_mandal_masters mm',    'mm.tm_mandal_ID = tamm.tamm_mandal_id','inner');
        $this->db->join('tbl_district_masters dm',  'dm.tdt_district_ID = tamm.tamm_district_id','left');
        $this->db->join('tbl_state_masters sm',     'sm.ts_state_ID = tamm.tamm_state_id','left');
        $this->db->join('tbl_countries_masters cm', 'cm.tc_country_ID = tamm.tamm_country_id','left');
        $this->db->join('tbl_registrations tr',     'tr.tr_id = tamm.tamm_active_member_id','left');
        $this->db->where('tamm.tamm_status','ACTIVE');

        if (!empty($filters['country_id'])  && $filters['country_id']  > 0) $this->db->where('tamm.tamm_country_id',  (int)$filters['country_id']);
        if (!empty($filters['state_id'])    && $filters['state_id']    > 0) $this->db->where('tamm.tamm_state_id',    (int)$filters['state_id']);
        if (!empty($filters['district_id']) && $filters['district_id'] > 0) $this->db->where('tamm.tamm_district_id', (int)$filters['district_id']);
        if (!empty($filters['mandal_id'])   && $filters['mandal_id']   > 0) $this->db->where('tamm.tamm_mandal_id',   (int)$filters['mandal_id']);

        $this->db->group_by('tamm.tamm_mandal_id');
        $this->db->order_by('sm.ts_state_name, dm.tdt_district_name, mm.tm_mandal','ASC');
        $rows = $this->db->get()->result_array();

        foreach ($rows as &$row) {
            $row['member_count'] = (int)$row['member_count'];
            $row['active_count'] = (int)$row['member_count'];
            $row['lat']          = (float)$row['lat'];
            $row['lng']          = (float)$row['lng'];

            $dc = array();
            foreach (self::$DESIGNATIONS as $d) { $dc[$d] = 0; }
            if (!empty($row['desig_list'])) {
                foreach (explode('||', $row['desig_list']) as $item) {
                    $item = trim($item);
                    isset($dc[$item]) ? $dc[$item]++ : $dc[$item] = 1;
                }
            }
            $row['designation_counts'] = $dc;
            unset($row['desig_list']);

            $row['member_names_arr'] = !empty($row['member_names'])
                ? array_values(array_filter(array_map('trim', explode('||', $row['member_names']))))
                : array();
            unset($row['member_names']);

            $row['geocode_address'] = implode(', ', array_filter(array(
                $row['mandal_name'], $row['district_name'], $row['state_name'], $row['country_name']
            )));
        }
        unset($row);
        return $rows;
    }

    /* ══════════════════════════════════════════════════════
       GREEN PINS — registered members grouped by mandal/district
       Only members NOT already in active_member_maping
    ══════════════════════════════════════════════════════ */
    public function get_registered_pins($filters = array())
    {
        $this->db->select("
            tr.tr_state                            AS state_id,
            sm.ts_state_name                       AS state_name,
            tr.tr_district                         AS district_id,
            dm.tdt_district_name                   AS district_name,
            IFNULL(mm.tm_mandal_ID, 0)             AS mandal_id,
            IFNULL(mm.tm_mandal, tr.tr_mandal)     AS mandal_name,
            IFNULL(mm.tm_lat, 0)                   AS lat,
            IFNULL(mm.tm_lng, 0)                   AS lng,
            COUNT(tr.tr_id)                        AS reg_count,
            CONCAT_WS(', ',
                IFNULL(mm.tm_mandal, tr.tr_mandal),
                dm.tdt_district_name,
                sm.ts_state_name
            )                                      AS geocode_address
        ");
        $this->db->from('tbl_registrations tr');
        $this->db->join('tbl_state_masters sm',   'sm.ts_state_ID = tr.tr_state','left');
        $this->db->join('tbl_district_masters dm','dm.tdt_district_ID = tr.tr_district','left');
        $this->db->join('tbl_mandal_masters mm',
            'mm.tm_mandal_district_ID = tr.tr_district AND LOWER(TRIM(mm.tm_mandal)) = LOWER(TRIM(tr.tr_mandal))',
            'left');
        /* Exclude members who are already in active mapping */
        $this->db->where('tr.tr_status','active');
        $this->db->where("tr.tr_id NOT IN (SELECT tamm_active_member_id FROM tbl_active_member_maping WHERE tamm_status='ACTIVE')", null, false);

        if (!empty($filters['state_id'])    && $filters['state_id']    > 0) $this->db->where('tr.tr_state',    (int)$filters['state_id']);
        if (!empty($filters['district_id']) && $filters['district_id'] > 0) $this->db->where('tr.tr_district', (int)$filters['district_id']);

        $this->db->group_by('tr.tr_state, tr.tr_district, tr.tr_mandal');
        $this->db->order_by('sm.ts_state_name, dm.tdt_district_name, tr.tr_mandal','ASC');
        $this->db->limit(500);

        $rows = $this->db->get()->result_array();
        foreach ($rows as &$row) {
            $row['reg_count']     = (int)$row['reg_count'];
            $row['lat']           = (float)$row['lat'];
            $row['lng']           = (float)$row['lng'];
            $row['location_name'] = $row['mandal_name'];
        }
        unset($row);
        return $rows;
    }

    /* ══════════════════════════════════════════════════════
       MODAL: active members for a mandal (PINK pin)
       Mobile: shown only for RDO, District Officer, Mandal Officer
       Email: always shown
    ══════════════════════════════════════════════════════ */
    public function get_members_by_mandal($mandal_id = 0)
    {
        $mandal_id = (int)$mandal_id;
        if ($mandal_id <= 0) return array();

        $this->db->select("
            tamm.tamm_id, tamm.tamm_designation,
            tr.tr_id, tr.tr_full_name, tr.tr_mobile, tr.tr_email,
            tr.tr_selfie, tr.tr_reg_key,
            mm.tm_mandal         AS mandal_name,
            dm.tdt_district_name AS district_name,
            sm.ts_state_name     AS state_name
        ");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_registrations tr',   'tr.tr_id = tamm.tamm_active_member_id','left');
        $this->db->join('tbl_mandal_masters mm',  'mm.tm_mandal_ID = tamm.tamm_mandal_id','left');
        $this->db->join('tbl_district_masters dm','dm.tdt_district_ID = tamm.tamm_district_id','left');
        $this->db->join('tbl_state_masters sm',   'sm.ts_state_ID = tamm.tamm_state_id','left');
        $this->db->where('tamm.tamm_mandal_id', $mandal_id);
        $this->db->where('tamm.tamm_status','ACTIVE');
        $desig_order = implode("','", self::$DESIGNATIONS);
        $this->db->order_by("FIELD(tamm.tamm_designation,'".$desig_order."')",'',false);
        $this->db->order_by('tr.tr_full_name','ASC');
        return $this->db->get()->result_array();
    }

    /* ══════════════════════════════════════════════════════
       MODAL: registered members by location (GREEN pin)
       Only those NOT in active mapping — no duplicates
       Mobile: always shown | Email: always shown
    ══════════════════════════════════════════════════════ */
    public function get_registered_by_location($filters = array())
    {
        /* Step 1: resolve mandal name from mandal_id (separate clean query) */
        $mandal_name_filter = '';
        if (!empty($filters['mandal_id']) && (int)$filters['mandal_id'] > 0) {
            $mrow = $this->db
                ->select('tm_mandal')
                ->where('tm_mandal_ID', (int)$filters['mandal_id'])
                ->get('tbl_mandal_masters')
                ->row_array();
            if (!empty($mrow['tm_mandal'])) {
                $mandal_name_filter = strtolower(trim($mrow['tm_mandal']));
            }
        }

        /* Step 2: get active member IDs to exclude (separate clean query) */
        $active_ids = array();
        $active_rows = $this->db
            ->select('tamm_active_member_id')
            ->where('tamm_status', 'ACTIVE')
            ->get('tbl_active_member_maping')
            ->result_array();
        foreach ($active_rows as $ar) {
            $active_ids[] = (int)$ar['tamm_active_member_id'];
        }

        /* Step 3: main query — fresh chain */
        $this->db->select("
            tr.tr_id,
            tr.tr_full_name,
            tr.tr_mobile,
            tr.tr_email,
            tr.tr_selfie,
            tr.tr_reg_key,
            tr.tr_mandal,
            tr.tr_designation    AS tamm_designation,
            dm.tdt_district_name AS district_name,
            sm.ts_state_name     AS state_name
        ");
        $this->db->from('tbl_registrations tr');
        $this->db->join('tbl_state_masters sm',    'sm.ts_state_ID = tr.tr_state',          'left');
        $this->db->join('tbl_district_masters dm', 'dm.tdt_district_ID = tr.tr_district',   'left');

        $this->db->where('tr.tr_status', 'active');

        /* Exclude active members (no duplicates) */
        if (!empty($active_ids)) {
            $this->db->where_not_in('tr.tr_id', $active_ids);
        }

        /* Location filters */
        if (!empty($filters['state_id']) && (int)$filters['state_id'] > 0) {
            $this->db->where('tr.tr_state', (int)$filters['state_id']);
        }
        if (!empty($filters['district_id']) && (int)$filters['district_id'] > 0) {
            $this->db->where('tr.tr_district', (int)$filters['district_id']);
        }
        if ($mandal_name_filter !== '') {
            $this->db->where('LOWER(TRIM(tr.tr_mandal))', $mandal_name_filter);
        }

        $this->db->order_by('tr.tr_full_name', 'ASC');

        $result = $this->db->get();
        if (!$result) return array();
        return $result->result_array();
    }

    /* ══════════════════════════════════════════════════════
       RIGHT PANEL: Total Registered — state-wise
       Only non-active members. Mobile + Email shown.
    ══════════════════════════════════════════════════════ */
    public function get_registered_panel()
    {
        $this->db->select("
            tr.tr_full_name, tr.tr_mobile, tr.tr_email, tr.tr_selfie, tr.tr_reg_key,
            tr.tr_designation    AS tamm_designation,
            sm.ts_state_ID       AS state_id,
            sm.ts_state_name     AS state_name
        ");
        $this->db->from('tbl_registrations tr');
        $this->db->join('tbl_state_masters sm','sm.ts_state_ID = tr.tr_state','left');
        $this->db->where('tr.tr_status','active');
        $this->db->where("tr.tr_id NOT IN (SELECT tamm_active_member_id FROM tbl_active_member_maping WHERE tamm_status='ACTIVE')", null, false);
        $this->db->order_by('sm.ts_state_name ASC, tr.tr_full_name ASC');
        $rows = $this->db->get()->result_array();

        $grouped = array(); $order = array();
        foreach ($rows as $row) {
            $sid = $row['state_id'];
            if (!isset($grouped[$sid])) {
                $grouped[$sid] = array('state_name'=>$row['state_name'],'members'=>array());
                $order[] = $sid;
            }
            $grouped[$sid]['members'][] = array(
                'tr_full_name'     => $row['tr_full_name'],
                'tr_mobile'        => $row['tr_mobile'],
                'tr_email'         => $row['tr_email'],
                'tr_selfie'        => $row['tr_selfie'],
                'tr_reg_key'       => $row['tr_reg_key'],
                'tamm_designation' => $row['tamm_designation'],
            );
        }
        $result = array();
        foreach ($order as $sid) { $result[] = $grouped[$sid]; }
        return $result;
    }

    /* ══════════════════════════════════════════════════════
       RIGHT PANEL: Active Members — state-wise
       Mobile: RDO/DO/MO only. Email: always.
    ══════════════════════════════════════════════════════ */
    public function get_state_members_panel($filters = array())
    {
        $this->db->select("
            tamm.tamm_mandal_id  AS mandal_id,
            tamm.tamm_designation,
            tr.tr_full_name, tr.tr_mobile, tr.tr_email, tr.tr_selfie, tr.tr_reg_key,
            mm.tm_mandal         AS mandal_name,
            dm.tdt_district_name AS district_name,
            sm.ts_state_ID       AS state_id,
            sm.ts_state_name     AS state_name
        ");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_registrations tr',   'tr.tr_id = tamm.tamm_active_member_id','left');
        $this->db->join('tbl_mandal_masters mm',  'mm.tm_mandal_ID = tamm.tamm_mandal_id','left');
        $this->db->join('tbl_district_masters dm','dm.tdt_district_ID = tamm.tamm_district_id','left');
        $this->db->join('tbl_state_masters sm',   'sm.ts_state_ID = tamm.tamm_state_id','left');
        $this->db->where('tamm.tamm_status','ACTIVE');

        if (!empty($filters['state_id'])    && $filters['state_id']    > 0) $this->db->where('tamm.tamm_state_id',    (int)$filters['state_id']);
        if (!empty($filters['district_id']) && $filters['district_id'] > 0) $this->db->where('tamm.tamm_district_id', (int)$filters['district_id']);

        $this->db->order_by('sm.ts_state_name ASC, tr.tr_full_name ASC');
        $rows = $this->db->get()->result_array();

        $grouped = array(); $order = array();
        foreach ($rows as $row) {
            $sid = $row['state_id'];
            if (!isset($grouped[$sid])) {
                $grouped[$sid] = array('state_name'=>$row['state_name'],'members'=>array());
                $order[] = $sid;
            }
            $grouped[$sid]['members'][] = array(
                'tr_full_name'     => $row['tr_full_name'],
                'tr_mobile'        => $row['tr_mobile'],
                'tr_email'         => $row['tr_email'],
                'tr_selfie'        => $row['tr_selfie'],
                'tr_reg_key'       => $row['tr_reg_key'],
                'tamm_designation' => $row['tamm_designation'],
                'mandal_name'      => $row['mandal_name'],
                'district_name'    => $row['district_name'],
                'mandal_id'        => $row['mandal_id'],
            );
        }
        $result = array();
        foreach ($order as $sid) { $result[] = $grouped[$sid]; }
        return $result;
    }

    /* ══════════════════════════════════════════════════════
       STATES FULL PANEL
       registered (non-active) + active, grouped by state
    ══════════════════════════════════════════════════════ */
    public function get_states_full_panel()
    {
        $this->db->select("sm.ts_state_ID AS gid, sm.ts_state_name AS gname,
            tr.tr_full_name, tr.tr_mobile, tr.tr_email, tr.tr_selfie, tr.tr_reg_key,
            '' AS tamm_designation");
        $this->db->from('tbl_registrations tr');
        $this->db->join('tbl_state_masters sm','sm.ts_state_ID = tr.tr_state','left');
        $this->db->where('tr.tr_status','active');
        $this->db->where("tr.tr_id NOT IN (SELECT tamm_active_member_id FROM tbl_active_member_maping WHERE tamm_status='ACTIVE')", null, false);
        $this->db->order_by('sm.ts_state_name ASC, tr.tr_full_name ASC');
        $reg_rows = $this->db->get()->result_array();

        $this->db->select("sm.ts_state_ID AS gid, sm.ts_state_name AS gname,
            tr.tr_full_name, tr.tr_mobile, tr.tr_email, tr.tr_selfie, tr.tr_reg_key,
            tamm.tamm_designation");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_registrations tr','tr.tr_id = tamm.tamm_active_member_id','left');
        $this->db->join('tbl_state_masters sm','sm.ts_state_ID = tamm.tamm_state_id','left');
        $this->db->where('tamm.tamm_status','ACTIVE');
        $this->db->order_by('sm.ts_state_name ASC, tr.tr_full_name ASC');
        $act_rows = $this->db->get()->result_array();

        return $this->_merge_groups($reg_rows, $act_rows);
    }

    /* ══════════════════════════════════════════════════════
       DISTRICTS FULL PANEL
    ══════════════════════════════════════════════════════ */
    public function get_districts_full_panel()
    {
        $this->db->select("dm.tdt_district_ID AS gid, dm.tdt_district_name AS gname,
            tr.tr_full_name, tr.tr_mobile, tr.tr_email, tr.tr_selfie, tr.tr_reg_key,
            '' AS tamm_designation");
        $this->db->from('tbl_registrations tr');
        $this->db->join('tbl_district_masters dm','dm.tdt_district_ID = tr.tr_district','left');
        $this->db->where('tr.tr_status','active');
        $this->db->where("tr.tr_id NOT IN (SELECT tamm_active_member_id FROM tbl_active_member_maping WHERE tamm_status='ACTIVE')", null, false);
        $this->db->order_by('dm.tdt_district_name ASC, tr.tr_full_name ASC');
        $reg_rows = $this->db->get()->result_array();

        $this->db->select("dm.tdt_district_ID AS gid, dm.tdt_district_name AS gname,
            tr.tr_full_name, tr.tr_mobile, tr.tr_email, tr.tr_selfie, tr.tr_reg_key,
            tamm.tamm_designation");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_registrations tr',   'tr.tr_id = tamm.tamm_active_member_id','left');
        $this->db->join('tbl_district_masters dm','dm.tdt_district_ID = tamm.tamm_district_id','left');
        $this->db->where('tamm.tamm_status','ACTIVE');
        $this->db->order_by('dm.tdt_district_name ASC, tr.tr_full_name ASC');
        $act_rows = $this->db->get()->result_array();

        return $this->_merge_groups($reg_rows, $act_rows);
    }

    /* ══════════════════════════════════════════════════════
       MANDALS FULL PANEL
    ══════════════════════════════════════════════════════ */
    public function get_mandals_full_panel()
    {
        $this->db->select("
            IFNULL(mm.tm_mandal_ID,0)         AS gid,
            IFNULL(mm.tm_mandal,tr.tr_mandal) AS gname,
            tr.tr_full_name, tr.tr_mobile, tr.tr_email, tr.tr_selfie, tr.tr_reg_key,
            '' AS tamm_designation");
        $this->db->from('tbl_registrations tr');
        $this->db->join('tbl_mandal_masters mm',
            'mm.tm_mandal_district_ID = tr.tr_district AND LOWER(TRIM(mm.tm_mandal)) = LOWER(TRIM(tr.tr_mandal))',
            'left');
        $this->db->where('tr.tr_status','active');
        $this->db->where("tr.tr_id NOT IN (SELECT tamm_active_member_id FROM tbl_active_member_maping WHERE tamm_status='ACTIVE')", null, false);
        $this->db->order_by('gname ASC, tr.tr_full_name ASC');
        $reg_rows = $this->db->get()->result_array();

        $this->db->select("mm.tm_mandal_ID AS gid, mm.tm_mandal AS gname,
            tr.tr_full_name, tr.tr_mobile, tr.tr_email, tr.tr_selfie, tr.tr_reg_key,
            tamm.tamm_designation");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_registrations tr', 'tr.tr_id = tamm.tamm_active_member_id','left');
        $this->db->join('tbl_mandal_masters mm','mm.tm_mandal_ID = tamm.tamm_mandal_id','left');
        $this->db->where('tamm.tamm_status','ACTIVE');
        $this->db->order_by('mm.tm_mandal ASC, tr.tr_full_name ASC');
        $act_rows = $this->db->get()->result_array();

        return $this->_merge_groups($reg_rows, $act_rows);
    }

    /* ══════════════════════════════════════════════════════
       DESIGNATION MEMBERS — for pill click
       Mobile: shown if desig is RDO/DO/MO. Email: always.
    ══════════════════════════════════════════════════════ */
    public function get_designation_members($desig)
    {
        $this->db->select("
            tr.tr_full_name, tr.tr_mobile, tr.tr_email, tr.tr_selfie, tr.tr_reg_key,
            tamm.tamm_designation,
            mm.tm_mandal         AS mandal_name,
            dm.tdt_district_name AS district_name
        ");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_registrations tr',   'tr.tr_id = tamm.tamm_active_member_id','left');
        $this->db->join('tbl_mandal_masters mm',  'mm.tm_mandal_ID = tamm.tamm_mandal_id','left');
        $this->db->join('tbl_district_masters dm','dm.tdt_district_ID = tamm.tamm_district_id','left');
        $this->db->where('tamm.tamm_status','ACTIVE');
        $this->db->where('tamm.tamm_designation', $desig);
        $this->db->order_by('tr.tr_full_name ASC');
        return $this->db->get()->result_array();
    }

    /* ══════════════════════════════════════════════════════
       PRIVATE: merge reg + active into groups
    ══════════════════════════════════════════════════════ */
    private function _merge_groups($reg_rows, $act_rows)
    {
        $groups = array(); $order = array();
        foreach ($reg_rows as $row) {
            $gid = ($row['gid'] != 0) ? $row['gid'] : 'x_'.$row['gname'];
            if (!isset($groups[$gid])) {
                $groups[$gid] = array('name'=>$row['gname'],'registered'=>array(),'active'=>array());
                $order[] = $gid;
            }
            $groups[$gid]['registered'][] = array(
                'tr_full_name'=>$row['tr_full_name'], 'tr_mobile'=>$row['tr_mobile'],
                'tr_email'=>$row['tr_email'], 'tr_selfie'=>$row['tr_selfie'],
                'tr_reg_key'=>$row['tr_reg_key'], 'tamm_designation'=>'',
            );
        }
        foreach ($act_rows as $row) {
            $gid = ($row['gid'] != 0) ? $row['gid'] : 'x_'.$row['gname'];
            if (!isset($groups[$gid])) {
                $groups[$gid] = array('name'=>$row['gname'],'registered'=>array(),'active'=>array());
                $order[] = $gid;
            }
            $groups[$gid]['active'][] = array(
                'tr_full_name'=>$row['tr_full_name'], 'tr_mobile'=>$row['tr_mobile'],
                'tr_email'=>$row['tr_email'], 'tr_selfie'=>$row['tr_selfie'],
                'tr_reg_key'=>$row['tr_reg_key'], 'tamm_designation'=>$row['tamm_designation'],
            );
        }
        $result = array();
        foreach ($order as $gid) { $result[] = $groups[$gid]; }
        return $result;
    }

    public function get_states_panel()
    {
        $this->db->select("sm.ts_state_name AS state_name, COUNT(tamm.tamm_id) AS member_count");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_state_masters sm','sm.ts_state_ID = tamm.tamm_state_id','left');
        $this->db->where('tamm.tamm_status','ACTIVE');
        $this->db->group_by('tamm.tamm_state_id');
        $this->db->order_by('member_count DESC');
        return $this->db->get()->result_array();
    }

    public function get_district_summary($filters = array())
    {
        $this->db->select("
            dm.tdt_district_ID   AS district_id,
            dm.tdt_district_name AS district_name,
            sm.ts_state_name     AS state_name,
            COUNT(tamm.tamm_id)                 AS member_count,
            COUNT(DISTINCT tamm.tamm_mandal_id) AS mandal_count
        ");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_district_masters dm','dm.tdt_district_ID = tamm.tamm_district_id','left');
        $this->db->join('tbl_state_masters sm',   'sm.ts_state_ID = tamm.tamm_state_id','left');
        $this->db->where('tamm.tamm_status','ACTIVE');
        if (!empty($filters['state_id']) && $filters['state_id'] > 0)
            $this->db->where('tamm.tamm_state_id',(int)$filters['state_id']);
        $this->db->group_by('tamm.tamm_district_id');
        $this->db->order_by('member_count DESC');
        $rows = $this->db->get()->result_array();
        foreach ($rows as &$row) {
            $row['member_count'] = (int)$row['member_count'];
            $row['mandal_count'] = (int)$row['mandal_count'];
        }
        unset($row);
        return $rows;
    }

    public function get_district_designations($district_id = 0)
    {
        $district_id = (int)$district_id;
        if ($district_id <= 0) return array();
        $this->db->select("tamm.tamm_designation AS designation, COUNT(*) AS cnt");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->where('tamm.tamm_district_id', $district_id);
        $this->db->where('tamm.tamm_status','ACTIVE');
        $this->db->group_by('tamm.tamm_designation');
        $desig_order = implode("','", self::$DESIGNATIONS);
        $this->db->order_by("FIELD(tamm.tamm_designation,'".$desig_order."')",'',false);
        $rows = $this->db->get()->result_array();
        foreach ($rows as &$row) { $row['cnt'] = (int)$row['cnt']; }
        unset($row);
        return $rows;
    }
}