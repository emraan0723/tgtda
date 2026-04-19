<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Verify_model
 * ────────────
 * Read-only model used by the public QR scan verify page.
 * Fetches ONLY public-safe columns — Aadhar, PAN, password, IP are excluded.
 */
class Verify_model extends CI_Model
{
    protected $table = 'tbl_registrations';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Fetch a member by their tr_reg_key (the token embedded in the QR code).
     * Returns only columns safe to display publicly.
     *
     * @param  string $reg_key
     * @return array|null
     */
    public function getMemberByRegKey($reg_key)
    {
        // Select ONLY safe public fields — never expose aadhar, pan, password, ip
        $this->db->select(
            'tr_reg_ukey,tr_reg_key, tr_full_name, tr_mobile, tr_dob, tr_full_address,
             tr_registration_type, tr_selfie, tr_status,
             tr_created_at, tr_language'
        );
        $this->db->where('tr_reg_key', $reg_key);
        $query = $this->db->get($this->table);
        $row   = $query->row_array();
        return $row ?: null;
    }
}
