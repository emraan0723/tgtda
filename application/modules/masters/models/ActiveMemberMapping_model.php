<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActiveMemberMapping_model extends CI_Model
{
    /* ── DataTables column map (index matches ajax_list $row[] order) ── */
    var $column_order = array(
        null,                    /* 0  SNO         – not sortable */
        'cm.tc_country_name',    /* 1  Country     */
        'sm.ts_state_name',      /* 2  State       */
        'dm.tdt_district_name',  /* 3  District    */
        'mm.tm_mandal',          /* 4  Mandal      */
        'tamm.tamm_designation', /* 5  Designation */
        'tr.tr_full_name',       /* 6  Member Name */
        'tamm.tamm_status',      /* 7  Status      */
        null,                    /* 8  Action      – not sortable */
    );

    var $column_search = array(
        'cm.tc_country_name',
        'sm.ts_state_name',
        'dm.tdt_district_name',
        'mm.tm_mandal',
        'tamm.tamm_designation',
        'tr.tr_full_name',
        'tamm.tamm_status',
    );

    var $order = array('tamm.tamm_id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* ─────────────────────────────────────────────
     * Fixed designation list
     * ───────────────────────────────────────────── */
    public function getDesignationList()
    {
        return array(
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
    }

    /* ─────────────────────────────────────────────
     * Get members from tbl_registrations by mandal_id
     *
     * tbl_registrations.tr_mandal   = mandal name  (VARCHAR)
     * tbl_registrations.tr_district = district ID  (INT)
     *
     * Returns [ ['tr_id'=>X, 'tr_full_name'=>'...', ...], ... ]
     * ───────────────────────────────────────────── */
    public function getMembersByMandal($mandal_id = 0)
    {
        $mandal_id = (int)$mandal_id;
        if ($mandal_id <= 0) return array();

        /* 1. Resolve mandal name + district_id from master */
        $this->db->select('tm_mandal, tm_mandal_district_ID');
        $this->db->from('tbl_mandal_masters');
        $this->db->where('tm_mandal_ID', $mandal_id);
        $mandal_row = $this->db->get()->row_array();

        if (empty($mandal_row)) return array();

        $mandal_name = $mandal_row['tm_mandal'];
        $district_id = (int)$mandal_row['tm_mandal_district_ID'];

        /* 2. Try with both mandal name + district id */
        $this->db->select('tr_id, tr_full_name, tr_reg_key, tr_reg_ukey');
        $this->db->from('tbl_registrations');
        $this->db->where('tr_mandal', $mandal_name);
        if ($district_id > 0)
            $this->db->where('tr_district', $district_id);
        $this->db->where('tr_status', 'active');
        $this->db->order_by('tr_full_name', 'ASC');
        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return $query->result_array();

        /* 3. Fallback: mandal name only (tr_district may be stored as name string) */
        $this->db->select('tr_id, tr_full_name, tr_reg_key, tr_reg_ukey');
        $this->db->from('tbl_registrations');
        $this->db->where('tr_mandal', $mandal_name);
        $this->db->where('tr_status', 'active');
        $this->db->order_by('tr_full_name', 'ASC');
        $query2 = $this->db->get();

        return ($query2->num_rows() > 0) ? $query2->result_array() : array();
    }

    /* ─────────────────────────────────────────────
     * Get single mapping row by tamm_id  (for edit modal)
     * Returns full row including tr_id, tr_full_name etc.
     * ───────────────────────────────────────────── */
    public function getMappingById($mapping_id = 0)
    {
        $mapping_id = (int)$mapping_id;
        if ($mapping_id <= 0) return array();

        $this->db->select("
            tamm.tamm_id,
            tamm.tamm_country_id,
            tamm.tamm_state_id,
            tamm.tamm_district_id,
            tamm.tamm_mandal_id,
            tamm.tamm_designation,
            tamm.tamm_active_member_id,
            tamm.tamm_key,
            tamm.tamm_status,
            tr.tr_id,
            tr.tr_full_name,
            tr.tr_mobile,
            tr.tr_email,
            tr.tr_selfie,
            tr.tr_reg_key,
            tr.tr_reg_ukey
        ");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_registrations tr',
            'tr.tr_id = tamm.tamm_active_member_id', 'left');
        $this->db->where('tamm.tamm_id', $mapping_id);
        $row = $this->db->get()->row_array();

        return $row ? $row : array();
    }

    /* ─────────────────────────────────────────────
     * INSERT  or  UPDATE  a mapping record
     *
     *  $params['member_id']  = tr_id from tbl_registrations
     *                          stored into tamm_active_member_id
     *  $params['mapping_id'] = 0 → INSERT  |  >0 → UPDATE
     * ───────────────────────────────────────────── */
    public function saveMemberMapping($params = array())
    {
        $district_id = isset($params['district_id']) ? (int)$params['district_id'] : 0;
        $mandal_id   = isset($params['mandal_id'])   ? (int)$params['mandal_id']   : 0;
        $designation = isset($params['designation']) ? trim($params['designation']) : '';
        $member_id   = isset($params['member_id'])   ? (int)$params['member_id']   : 0; /* = tr_id */
        $mapping_id  = isset($params['mapping_id'])  ? (int)$params['mapping_id']  : 0;

        if ($member_id <= 0 || $mandal_id <= 0 || $designation == '')
            return 'INSERT_FAILED';

        /* ── Step 1: Verify tr_id exists and get tr_reg_key ── */
        $this->db->select('tr_id, tr_reg_key, tr_reg_ukey, tr_full_name');
        $this->db->from('tbl_registrations');
        $this->db->where('tr_id', $member_id);
        $reg = $this->db->get()->row_array();

        if (empty($reg)) return 'INSERT_FAILED'; /* invalid tr_id */

        /* ── Step 2: Get country_id + state_id from mandal master ── */
        $this->db->select('tm_mandal_district_ID, tm_mandal_state_ID, tm_mandal_country_ID');
        $this->db->from('tbl_mandal_masters');
        $this->db->where('tm_mandal_ID', $mandal_id);
        $mandal_row = $this->db->get()->row_array();

        $country_id = isset($mandal_row['tm_mandal_country_ID']) ? (int)$mandal_row['tm_mandal_country_ID'] : 0;
        $state_id   = isset($mandal_row['tm_mandal_state_ID'])   ? (int)$mandal_row['tm_mandal_state_ID']   : 0;

        /* ── Step 3: Prepare data ── */
        $data = array(
            'tamm_country_id'           => $country_id,
            'tamm_state_id'             => $state_id,
            'tamm_district_id'          => $district_id,
            'tamm_mandal_id'            => $mandal_id,
            'tamm_designation'          => $designation,
            'tamm_active_member_id'     => $member_id,          /* tr_id stored here */
            'tamm_key'                  => $reg['tr_reg_key'],  /* tr_reg_key stored here */
            'tamm_ip_address'           => $_SERVER['REMOTE_ADDR'],
            'tamm_created_by'           => isset($_SESSION['user_id'])   ? $_SESSION['user_id']   : '',
            'tamm_created_name'         => isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '',
            'tamm_last_update_datetime' => date('Y-m-d H:i:s'),
        );

        /* ── Step 4: Duplicate check (same mandal + designation + member) ── */
        $this->db->where('tamm_mandal_id',       $mandal_id);
        $this->db->where('tamm_designation',      $designation);
        $this->db->where('tamm_active_member_id', $member_id);
        if ($mapping_id > 0)
            $this->db->where('tamm_id !=',        $mapping_id);
        $exists = $this->db->count_all_results('tbl_active_member_maping');

        if ($exists > 0) return 'ALREADY_EXISTS';

        /* ── Step 5a: UPDATE ── */
        if ($mapping_id > 0)
        {
            $this->db->where('tamm_id', $mapping_id);
            $this->db->update('tbl_active_member_maping', $data);

            /* Sync designation to tbl_registrations.tr_designation */
            $this->db->where('tr_id', $member_id);
            $this->db->update('tbl_registrations', array('tr_designation' => $designation));

            return 'UPDATE_SUCCESS';
        }

        /* ── Step 5b: INSERT ── */
        $data['tamm_created_date'] = date('Y-m-d');
        $data['tamm_created_time'] = date('H:i:s');
        $data['tamm_status']       = 'ACTIVE';

        $this->db->insert('tbl_active_member_maping', $data);

        if ($this->db->affected_rows() > 0)
        {
            /* Sync designation to tbl_registrations.tr_designation */
            $this->db->where('tr_id', $member_id);
            $this->db->update('tbl_registrations', array('tr_designation' => $designation));
            return 'INSERT_SUCCESS';
        }
        return 'INSERT_FAILED';
    }

    /* ─────────────────────────────────────────────
     * Status toggle  (ACTIVE ↔ INACTIVE)
     * ───────────────────────────────────────────── */
    public function updateMappingStatus($params = array())
    {
        $mapping_id = isset($params['mapping_id']) ? (int)$params['mapping_id'] : 0;
        $status     = isset($params['status'])     ? trim($params['status'])     : '';

        if ($mapping_id <= 0 || $status == '') return 'Error';

        $this->db->where('tamm_id', $mapping_id);
        $this->db->update('tbl_active_member_maping', array(
            'tamm_status'               => $status,
            'tamm_last_update_datetime' => date('Y-m-d H:i:s'),
            'tamm_ip_address'           => $_SERVER['REMOTE_ADDR'],
        ));
        return ($this->db->affected_rows() > 0) ? 'UPDATE_SUCCESS' : 'UPDATE_FAILED';
    }

    /* ─────────────────────────────────────────────
     * Core DataTables query  (shared by get / count)
     * ───────────────────────────────────────────── */
    private function _get_datatables_query()
    {
        /* ── optional filter bar values ── */
        $f_district    = $this->input->post('filter_district');
        $f_mandal      = $this->input->post('filter_mandal');
        $f_member      = $this->input->post('filter_member');
        $f_designation = $this->input->post('filter_designation');
        $f_status      = $this->input->post('filter_status');

        if ($f_district    && (int)$f_district    > 0) $this->db->where('tamm.tamm_district_id',     (int)$f_district);
        if ($f_mandal      && (int)$f_mandal      > 0) $this->db->where('tamm.tamm_mandal_id',        (int)$f_mandal);
        if ($f_member      && (int)$f_member      > 0) $this->db->where('tamm.tamm_active_member_id', (int)$f_member);
        if ($f_designation && $f_designation != '')     $this->db->where('tamm.tamm_designation',      $f_designation);
        if ($f_status      && $f_status      != '')     $this->db->where('tamm.tamm_status',           $f_status);

        /* ── SELECT ── */
        $this->db->select("
            tamm.tamm_id,
            tamm.tamm_active_member_id,
            tamm.tamm_key,
            tamm.tamm_designation,
            tamm.tamm_district_id,
            tamm.tamm_mandal_id,
            tamm.tamm_state_id,
            tamm.tamm_country_id,
            tamm.tamm_status,
            tr.tr_id,
            tr.tr_full_name,
            tr.tr_mobile,
            tr.tr_email,
            tr.tr_reg_key,
            tr.tr_reg_ukey,
            tr.tr_language,
            tr.tr_registration_type,
            tr.tr_aadhar_no,
            tr.tr_pan_no,
            tr.tr_full_address,
            tr.tr_selfie,
            mm.tm_mandal,
            dm.tdt_district_name,
            sm.ts_state_name,
            cm.tc_country_name
        ");
        $this->db->from('tbl_active_member_maping tamm');
        $this->db->join('tbl_registrations tr',
            'tr.tr_id = tamm.tamm_active_member_id',      'left');
        $this->db->join('tbl_mandal_masters mm',
            'mm.tm_mandal_ID = tamm.tamm_mandal_id',      'left');
        $this->db->join('tbl_district_masters dm',
            'dm.tdt_district_ID = tamm.tamm_district_id', 'left');
        $this->db->join('tbl_state_masters sm',
            'sm.ts_state_ID = tamm.tamm_state_id',        'left');
        $this->db->join('tbl_countries_masters cm',
            'cm.tc_country_ID = tamm.tamm_country_id',    'left');

        /* ── global search ── */
        $search_val = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';
        if ($search_val != '')
        {
            $this->db->group_start();
            $first = true;
            foreach ($this->column_search as $col)
            {
                if ($first) { $this->db->like($col, $search_val);    $first = false; }
                else        { $this->db->or_like($col, $search_val); }
            }
            $this->db->group_end();
        }

        /* ── ORDER ── */
        if (isset($_POST['order']))
        {
            $col_idx = (int)$_POST['order']['0']['column'];
            $dir     = $_POST['order']['0']['dir'];
            if (isset($this->column_order[$col_idx]) && $this->column_order[$col_idx] !== null)
                $this->db->order_by($this->column_order[$col_idx], $dir);
        }
        else
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    /* ── paged result ── */
    public function get_datatables()
    {
        $this->_get_datatables_query();
        $length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
        $start  = isset($_POST['start'])  ? (int)$_POST['start']  : 0;
        if ($length != -1)
            $this->db->limit($length, $start);
        return $this->db->get()->result();
    }

    /* ── filtered row count ── */
    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    /* ── total row count (no filters) ── */
    public function count_all()
    {
        $this->db->from('tbl_active_member_maping');
        return $this->db->count_all_results();
    }
}
?>
