<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Clients.php  –  Updated to pass state/district names to the view.
 * All existing logic is UNCHANGED.  Only additions are:
 *   • Load Location_model
 *   • Resolve $state_name and $district_name
 *   • Pass them into $data_view
 */
class Clients extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
        $this->load->model(array('Clients_model', 'Location_model'));   // ← Location_model added
        $this->load->helper(array('form','url','common_helper'));
        $this->load->library(array('session','upload','form_validation'));
        $this->session_check->check_session();
        $this->perPage = 25;
    }

    public function index()
    {
        $user_id = $this->session->userdata('uuser_id');

        if (!$user_id) {
            redirect('user_login');
        }

        $driver = $this->Clients_model->getDriverById($user_id);

        if (!$driver) {
            redirect('user_login');
        }

        $created = new DateTime($driver['tr_created_at']);

        $valid = new DateTime($driver['tr_created_at']);
        $valid->modify('+1 years');

        $initials = '';
        $words = explode(' ', trim($driver['tr_full_name']));
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }

        // ── NEW: resolve human-readable state & district names ────────────
        $state_name    = '—';
        $district_name = '—';

        if (!empty($driver['tr_state'])) {
            $state = $this->Location_model->get_state_by_id($driver['tr_state']);
            if ($state) $state_name = $state['ts_state_name'] ?? '—';
        }
        if (!empty($driver['tr_district'])) {
            $district = $this->Location_model->get_district_by_id($driver['tr_district']);
            if ($district) $district_name = $district['tdt_district_name'] ?? '—';
        }
        // ─────────────────────────────────────────────────────────────────

        $data_view = array();
        $data_view['data'] = array(
            'title'   => 'TGTDA | ID Card',
            'content' => 'idcard_back_view',   // ← point to the new combined view
            'header1' => 'User',
            'header2' => 'ID Card',
        );

        $data_view['driver']        = $driver;
        $data_view['reg_id']        = $driver['tr_reg_ukey'];
        $data_view['aadhar']        = $driver['tr_aadhar_no'];
        $data_view['initials']      = $initials;
        $data_view['issue_date']    = $created->format('d M Y');
        $data_view['valid_until']   = $valid->format('d M Y');
        $data_view['state_name']    = $state_name;    // ← NEW
        $data_view['district_name'] = $district_name; // ← NEW

        $this->load->view('main_page', $data_view);
    }
}
