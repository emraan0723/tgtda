<?php
/**
 * ActiveMemberMap Controller
 * PHP 5.6 / 7.2 compatible
 * Place: application/modules/masters/controllers/ActiveMemberMap.php
 * URL  : masters/activemembermap/index
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class ActiveMemberMap extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array(
            'Session_check', 'session', 'encryption', 'authorization'
        ));
        $this->load->model(array(
            'ActiveMemberMap_model',
            'Location_model',
        ));
        $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
        //$this->authorization->userauthorization('masters', 'permissionset');
    }

    /* ─────────────────────────────────────────────
     * Main map page
     * ───────────────────────────────────────────── */
    public function index()
    {
        //$this->authorization->userauthorization('masters', 'view');

        $data_view['data'] = array(
            'title'   => 'TGTDA | Active Member Map',
            'content' => 'active_member_map',
            'header1' => 'Masters',
            'header2' => 'Active Member Map',
        );

        $data_view['country_list'] = $this->Location_model->get_countries();
        $this->load->view('main_page', $data_view);
    }

    /* ─────────────────────────────────────────────
     * AJAX: Get overall stats for header cards
     * ───────────────────────────────────────────── */
    public function get_stats()
    {
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_overall_stats());
    }

    /* ─────────────────────────────────────────────
     * AJAX: Get mandal-level map pins
     * POST: country_id, state_id, district_id, mandal_id (all optional)
     * Returns: array of mandal rows with member counts per designation
     * ───────────────────────────────────────────── */
    public function get_map_pins()
    {
        if ( ! $this->input->post()) { echo json_encode(array()); return; }

        $filters = array(
            'country_id'  => (int)$this->input->post('country_id'),
            'state_id'    => (int)$this->input->post('state_id'),
            'district_id' => (int)$this->input->post('district_id'),
            'mandal_id'   => (int)$this->input->post('mandal_id'),
        );

        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_mandal_pins($filters));
    }

    /* ─────────────────────────────────────────────
     * AJAX: Get district summary for side panel
     * POST: country_id, state_id (optional)
     * ───────────────────────────────────────────── */
    public function get_district_summary()
    {
        if ( ! $this->input->post()) { echo json_encode(array()); return; }

        $filters = array(
            'country_id' => (int)$this->input->post('country_id'),
            'state_id'   => (int)$this->input->post('state_id'),
        );

        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_district_summary($filters));
    }

    /* ─────────────────────────────────────────────
     * AJAX: Get full member list for a mandal (sidebar)
     * POST: mandal_id
     * ───────────────────────────────────────────── */
    public function get_mandal_members()
    {
        if ( ! $this->input->post()) { echo json_encode(array()); return; }

        $mandal_id = (int)$this->input->post('mandal_id');
        if ($mandal_id <= 0) { echo json_encode(array()); return; }

        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_members_by_mandal($mandal_id));
    }

    /* ─────────────────────────────────────────────
     * AJAX: Get designation-wise breakdown for a district
     * POST: district_id
     * ───────────────────────────────────────────── */
    public function get_district_designations()
    {
        if ( ! $this->input->post()) { echo json_encode(array()); return; }

        $district_id = (int)$this->input->post('district_id');
        if ($district_id <= 0) { echo json_encode(array()); return; }

        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_district_designations($district_id));
    }

    /* ─────────────────────────────────────────────
     * AJAX: Cascading – states by country
     * ───────────────────────────────────────────── */
    public function get_states()
    {
        $country_id = (int)$this->input->post('country_id');
        $states = ($country_id > 0)
            ? $this->Location_model->get_states_by_country($country_id) : array();
        header('Content-Type: application/json');
        echo json_encode($states);
    }

    /* ─────────────────────────────────────────────
     * AJAX: Cascading – districts by state
     * ───────────────────────────────────────────── */
    public function get_districts()
    {
        $state_id = (int)$this->input->post('state_id');
        $districts = ($state_id > 0)
            ? $this->Location_model->get_districts_by_state($state_id) : array();
        header('Content-Type: application/json');
        echo json_encode($districts);
    }

    /* ─────────────────────────────────────────────
     * AJAX: Cascading – mandals by district
     * ───────────────────────────────────────────── */
    public function get_mandals()
    {
        $district_id = (int)$this->input->post('district_id');
        $mandals = ($district_id > 0)
            ? $this->Location_model->get_mandals_by_district($district_id) : array();
        header('Content-Type: application/json');
        echo json_encode($mandals);
    }
    
    public function geocode_address()
{
    // Basic CSRF / POST check
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') show_404();

    $address = trim($this->input->post('address'));
    if (!$address) {
        echo json_encode(['lat' => null, 'lng' => null]);
        return;
    }

    // ── 1. Check DB cache first ──────────────────────────────
    $cached = $this->db
        ->where('gc_address', $address)
        ->get('mandal_geocache')
        ->row();

    if ($cached) {
        echo json_encode([
            'lat' => (float)$cached->gc_lat,
            'lng' => (float)$cached->gc_lng,
        ]);
        return;
    }

    // ── 2. Call Nominatim server-side (no CORS) ──────────────
    $url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q='
           . urlencode($address);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT      => 'TGTDA-MemberMap/1.0 (admin@tgtda.com)',
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_HTTPHEADER     => ['Accept-Language: en'],
    ]);
    $raw  = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($raw, true);

    if (!empty($data[0]['lat'])) {
        $lat = (float)$data[0]['lat'];
        $lng = (float)$data[0]['lon'];

        // Save to cache so we never geocode this address again
        $this->db->insert('mandal_geocache', [
            'gc_address'    => $address,
            'gc_lat'        => $lat,
            'gc_lng'        => $lng,
            'gc_created_at' => date('Y-m-d H:i:s'),
        ]);

        echo json_encode(['lat' => $lat, 'lng' => $lng]);
    } else {
        // Cache the miss too — prevents hammering Nominatim for bad addresses
        $this->db->insert('mandal_geocache', [
            'gc_address'    => $address,
            'gc_lat'        => null,
            'gc_lng'        => null,
            'gc_created_at' => date('Y-m-d H:i:s'),
        ]);
        echo json_encode(['lat' => null, 'lng' => null]);
    }
}
}
