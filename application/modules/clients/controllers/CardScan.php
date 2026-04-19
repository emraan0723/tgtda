<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CardScan.php  –  PUBLIC controller (no login required)
 * URL:  /card_scan/{tr_reg_key}
 *
 * When the QR code on the ID card is scanned, this controller:
 *  1. Reads tr_reg_key from the URL segment
 *  2. Looks up the driver record (no session, no login)
 *  3. Resolves state / district names via Location_model
 *  4. Shows the public verification page  OR  an "Unauthorized Access" error
 *
 * Add to routes.php:
 *   $route['card_scan/(:any)'] = 'CardScan/index/$1';
 */
class CardScan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Clients_model');   // already exists – no change
        $this->load->model('Location_model');  // already exists – no change
    }

    public function index($reg_key = '')
    {
        // ── 1. Validate key present ──────────────────────────────────────
        if (empty($reg_key)) {
            $this->_show_error('Invalid or missing verification key.');
            return;
        }

        // ── 2. Lookup by tr_reg_key (the QR token, NOT the numeric ID) ──
        $driver = $this->_get_driver_by_regkey($reg_key);

        if (!$driver) {
            $this->_show_error('Member record not found. The QR code may be invalid or expired.');
            return;
        }

        // ── 3. Build ancillary display data ─────────────────────────────
        $created    = new DateTime($driver['tr_created_at']);
        $valid      = new DateTime($driver['tr_created_at']);
        $valid->modify('+1 years');

        $initials = '';
        foreach (explode(' ', trim($driver['tr_full_name'])) as $word) {
            if (!empty($word)) $initials .= strtoupper($word[0]);
        }

        // Resolve state & district names
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

        // ── 4. Pass to public view ───────────────────────────────────────
        $data = [
            'driver'        => $driver,
            'initials'      => $initials,
            'issue_date'    => $created->format('d M Y'),
            'valid_until'   => $valid->format('d M Y'),
            'state_name'    => $state_name,
            'district_name' => $district_name,
        ];

        // Public page – load WITHOUT the admin/user layout wrapper
        $this->load->view('card_scan_view', $data);
    }

    // ── Private helpers ──────────────────────────────────────────────────

    private function _get_driver_by_regkey($reg_key)
    {
        return $this->db
            ->where('tr_reg_key', $reg_key)
            ->get('tbl_registrations')
            ->row_array();
    }

    private function _show_error($message)
    {
        $this->load->view('card_scan_view', ['error' => $message]);
    }
}
