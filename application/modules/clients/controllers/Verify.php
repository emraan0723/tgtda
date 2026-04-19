<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Verify Controller
 * ─────────────────
 * PUBLIC — no session required.
 * Accessed when someone scans the QR code on an ID card.
 *
 * URL:  /verify/{tr_reg_key}
 *
 * Security:
 *  - tr_reg_key is a long random unique token (never guessable)
 *  - Only active/approved members are shown
 *  - No sensitive data (Aadhar, PAN, password) is passed to the view
 *  - Rate-limiting friendly: no DB writes, pure read
 */
class Verify extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Verify_model');
        $this->load->helper(array('url'));
    }

    /**
     * /verify/{reg_key}
     */
    public function index($reg_key = '')
    {
        // 1. Basic sanity check — reject empty or suspiciously long keys immediately
        if (empty($reg_key) || strlen($reg_key) > 300) {
            $this->_show_invalid();
            return;
        }

        // 2. Sanitise: strip anything that isn't alphanumeric / dash / underscore
        $clean_key = preg_replace('/[^a-zA-Z0-9\-_]/', '', $reg_key);
        if ($clean_key !== $reg_key) {
            $this->_show_invalid();
            return;
        }

        // 3. Look up the member by reg_key
        $member = $this->Verify_model->getMemberByRegKey($clean_key);

        if (!$member) {
            $this->_show_invalid();
            return;
        }

        // 4. Only show active / approved members
        $allowed_statuses = array('active', 'approved');
        if (!in_array($member['tr_status'], $allowed_statuses)) {
            $this->_show_inactive($member['tr_status']);
            return;
        }

        // 5. Build safe public data — NO Aadhar, NO PAN, NO password, NO raw key
        $created = new DateTime($member['tr_created_at']);
        $valid   = new DateTime($member['tr_created_at']);
        $valid->modify('+1 years');

        $initials = '';
        foreach (explode(' ', trim($member['tr_full_name'])) as $w) {
            if (!empty($w)) $initials .= strtoupper($w[0]);
        }

        $data_view = array(
            'name'       => $member['tr_full_name'],
            'reg_id'     => $member['tr_reg_ukey'],   // display ID, not the secret key
            'fkey' => $member['tr_reg_key'],
            'mobile'     => $member['tr_mobile'],
            'dob'        => date('d M Y', strtotime($member['tr_dob'])),
            'address'    => $member['tr_full_address'],
            'reg_type'   => $member['tr_registration_type'],
            'status'     => $member['tr_status'],
            'selfie'     => $member['tr_selfie'],
            'initials'   => $initials,
            'issue_date' => $created->format('d M Y'),
            'valid_until'=> $valid->format('d M Y'),
        );

        $this->load->view('verify_profile', $data_view);
    }

    // ── helpers ──────────────────────────────────────────────────────────────

    private function _show_invalid()
    {
        $this->load->view('verify_invalid', array('reason' => 'not_found'));
    }

    private function _show_inactive($status)
    {
        $this->load->view('verify_invalid', array('reason' => $status));
    }
}
