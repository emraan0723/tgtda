<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ActiveMember_model extends CI_Model
{
    /* DataTables column mapping */
    var $column_order  = array(null,'tc_country_name','ts_state_name','tdt_district_name','tm_mandal','tam_designation','tam_member_name','tam_status');
    var $column_search = array('tc_country_name','ts_state_name','tdt_district_name','tm_mandal','tam_designation','tam_member_name','tam_status');
    var $order         = array('tam_ID' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* ─────────────────────────────────────────────
     * Fixed designations (adjust as needed)
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
     * Core DataTables query (with optional filters)
     * ───────────────────────────────────────────── */
    private function _get_datatables_query()
    {
        if($this->input->post('country'))
            $this->db->where('tc_country_ID', $this->input->post('country'));
        if($this->input->post('state'))
            $this->db->where('ts_state_ID', $this->input->post('state'));
        if($this->input->post('district'))
            $this->db->where('tdt_district_ID', $this->input->post('district'));
        if($this->input->post('mandal'))
            $this->db->where('tm_mandal_ID', $this->input->post('mandal'));
        if($this->input->post('designation'))
            $this->db->where('tam_designation', $this->input->post('designation'));
        if($this->input->post('status'))
            $this->db->where('tam_status', $this->input->post('status'));

        $this->db->select("
            tam_ID          AS member_id,
            tam_member_name AS member_name,
            tam_designation AS designation,
            tam_status      AS status,
            tam_mandal_ID   AS mandal_id,
            tm_mandal       AS mandal_name,
            tdt_district_ID AS district_id,
            tdt_district_name AS district_name,
            ts_state_ID     AS state_id,
            ts_state_name   AS state_name,
            tc_country_ID   AS country_id,
            tc_country_name AS country_name
        ");
        $this->db->from('tbl_active_member_masters');
        $this->db->join('tbl_mandal_masters',   'tm_mandal_ID   = tam_mandal_ID',   'left');
        $this->db->join('tbl_district_masters', 'tdt_district_ID = tam_district_ID','left');
        $this->db->join('tbl_state_masters',    'ts_state_ID     = tam_state_ID',   'left');
        $this->db->join('tbl_countries_masters','tc_country_ID   = tam_country_ID', 'left');

        $i = 0;
        foreach($this->column_search as $item)
        {
            if(isset($_POST['search']['value']) && $_POST['search']['value'])
            {
                if($i === 0)
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if(isset($_POST['order']))
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        elseif(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        $this->db->from('tbl_active_member_masters');
        return $this->db->count_all_results();
    }

    /* ─────────────────────────────────────────────
     * Fetch member list (for edit / duplicate check)
     * ───────────────────────────────────────────── */
    public function getActiveMemberList($params = array())
    {
        $member_id   = isset($params['member_id'])   ? $params['member_id']   : 0;
        $country_id  = isset($params['country_id'])  ? $params['country_id']  : 0;
        $state_id    = isset($params['state_id'])    ? $params['state_id']    : 0;
        $district_id = isset($params['district_id']) ? $params['district_id'] : 0;
        $mandal_id   = isset($params['mandal_id'])   ? $params['mandal_id']   : 0;
        $designation = isset($params['designation']) ? $params['designation'] : '';
        $member_name = isset($params['member_name']) ? $params['member_name'] : '';

        $this->db->select("
            tam_ID          AS member_id,
            tam_member_name AS member_name,
            tam_designation AS designation,
            tam_status      AS status,
            tam_mandal_ID   AS mandal_id,
            tm_mandal       AS mandal_name,
            tdt_district_ID AS district_id,
            tdt_district_name AS district_name,
            ts_state_ID     AS state_id,
            ts_state_name   AS state_name,
            tc_country_ID   AS country_id,
            tc_country_name AS country_name
        ");
        $this->db->from('tbl_active_member_masters');
        $this->db->join('tbl_mandal_masters',   'tm_mandal_ID    = tam_mandal_ID',   'left');
        $this->db->join('tbl_district_masters', 'tdt_district_ID = tam_district_ID', 'left');
        $this->db->join('tbl_state_masters',    'ts_state_ID     = tam_state_ID',    'left');
        $this->db->join('tbl_countries_masters','tc_country_ID   = tam_country_ID',  'left');

        if($member_id   > 0) $this->db->where('tam_ID',         $member_id);
        if($country_id  > 0) $this->db->where('tam_country_ID', $country_id);
        if($state_id    > 0) $this->db->where('tam_state_ID',   $state_id);
        if($district_id > 0) $this->db->where('tam_district_ID',$district_id);
        if($mandal_id   > 0) $this->db->where('tam_mandal_ID',  $mandal_id);
        if($designation != '') $this->db->where('tam_designation', $designation);
        if($member_name != '') $this->db->where('tam_member_name', $member_name);

        $query = $this->db->get();
        return array('query' => $query, 'isexists_insert' => $query->num_rows());
    }

    /* ─────────────────────────────────────────────
     * Insert / Update active member
     * ───────────────────────────────────────────── */
    public function saveActiveMember($params = array())
    {
        $data = array(
            'tam_country_ID'   => isset($params['country_id'])  ? $params['country_id']  : 0,
            'tam_state_ID'     => isset($params['state_id'])    ? $params['state_id']    : 0,
            'tam_district_ID'  => isset($params['district_id']) ? $params['district_id'] : 0,
            'tam_mandal_ID'    => isset($params['mandal_id'])   ? $params['mandal_id']   : 0,
            'tam_designation'  => isset($params['designation']) ? $params['designation'] : '',
            'tam_member_name'  => isset($params['member_name']) ? $params['member_name'] : '',
            'tam_entry_date'   => date('Y-m-d'),
            'tam_entry_time'   => date('H:i:s'),
            'tam_entry_ip'     => $_SERVER['REMOTE_ADDR'],
            'tam_created_by'   => isset($_SESSION['user_id'])   ? $_SESSION['user_id']   : '',
            'tam_created_name' => isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '',
        );

        $active_member_id = isset($params['active_member_id']) ? (int)$params['active_member_id'] : 0;

        /* duplicate check: same mandal + designation + member name */
        $check_params = array(
            'mandal_id'   => $data['tam_mandal_ID'],
            'designation' => $data['tam_designation'],
            'member_name' => $data['tam_member_name'],
        );
        $isexists = $this->getActiveMemberList($check_params);

        if($active_member_id > 0 && $isexists['isexists_insert'] == 0)
        {
            /* UPDATE */
            unset($data['tam_entry_date'], $data['tam_entry_time'], $data['tam_entry_ip']);
            $data['tam_update_date'] = date('Y-m-d');
            $data['tam_update_time'] = date('H:i:s');
            $data['tam_update_ip']   = $_SERVER['REMOTE_ADDR'];

            $this->db->where('tam_ID', $active_member_id);
            $this->db->update('tbl_active_member_masters', $data);

            return ($this->db->affected_rows() > 0) ? 'UPDATE_SUCCESS' : 'UPDATE_FAILED';
        }
        elseif($isexists['isexists_insert'] == 0)
        {
            /* INSERT */
            $this->db->insert('tbl_active_member_masters', $data);
            return ($this->db->affected_rows() > 0) ? 'INSERT_SUCCESS' : 'INSERT_FAILED';
        }
        else
        {
            return 'ALREADY_EXISTS';
        }
    }

    /* ─────────────────────────────────────────────
     * Status toggle
     * ───────────────────────────────────────────── */
    public function activeMemberStatusUpdate($params = array())
    {
        $member_id = isset($params['member_id']) ? (int)$params['member_id'] : 0;
        $data = array(
            'tam_status'       => isset($params['status'])    ? $params['status']    : '',
            'tam_update_date'  => date('Y-m-d'),
            'tam_update_time'  => date('H:i:s'),
            'tam_update_ip'    => $_SERVER['REMOTE_ADDR'],
            'tam_created_by'   => isset($_SESSION['user_id'])   ? $_SESSION['user_id']   : '',
            'tam_created_name' => isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '',
        );

        if($member_id > 0 && $data['tam_status'] != '')
        {
            $this->db->where('tam_ID', $member_id);
            $this->db->update('tbl_active_member_masters', $data);
            return ($this->db->affected_rows()) ? 'UPDATE_SUCCESS' : 'UPDATE_FAILED';
        }
        return 'Error';
    }
}
?>
