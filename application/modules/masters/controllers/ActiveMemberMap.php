<?php
/**
 * ActiveMemberMap Controller
 * PHP 5.6 / 7.x compatible — NO short array syntax []
 * Place: application/modules/masters/controllers/ActiveMemberMap.php
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
            'user/Location_model',
        ));
        $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
    }

    /* ─────────────────────────────────────────────
     * Main map page
     * ───────────────────────────────────────────── */
    public function index()
    {
        $this->authorization->userauthorization('masters', 'view');

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
     * AJAX: Overall stats
     * ───────────────────────────────────────────── */
    public function get_stats()
    {
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_overall_stats());
    }

    /* ─────────────────────────────────────────────
     * AJAX: Map pins
     * ───────────────────────────────────────────── */
    public function get_map_pins()
    {
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
     * AJAX: District summary panel
     * ───────────────────────────────────────────── */
    public function get_district_summary()
    {
        $filters = array(
            'country_id' => (int)$this->input->post('country_id'),
            'state_id'   => (int)$this->input->post('state_id'),
        );

        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_district_summary($filters));
    }

    /* ─────────────────────────────────────────────
     * AJAX: Members for a mandal (sidebar)
     * ───────────────────────────────────────────── */
    public function get_mandal_members()
    {
        $mandal_id = (int)$this->input->post('mandal_id');
        if ($mandal_id <= 0) {
            header('Content-Type: application/json');
            echo json_encode(array());
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_members_by_mandal($mandal_id));
    }

    /* ─────────────────────────────────────────────
     * AJAX: Designation breakdown for a district
     * ───────────────────────────────────────────── */
    public function get_district_designations()
    {
        $district_id = (int)$this->input->post('district_id');
        if ($district_id <= 0) {
            header('Content-Type: application/json');
            echo json_encode(array());
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_district_designations($district_id));
    }

    /* ─────────────────────────────────────────────
     * AJAX: States by country
     * ───────────────────────────────────────────── */
    public function get_states()
    {
        $country_id = (int)$this->input->post('country_id');
        $states = ($country_id > 0)
            ? $this->Location_model->get_states_by_country($country_id)
            : array();

        header('Content-Type: application/json');
        echo json_encode($states);
    }

    /* ─────────────────────────────────────────────
     * AJAX: Districts by state
     * ───────────────────────────────────────────── */
    public function get_districts()
    {
        $state_id = (int)$this->input->post('state_id');
        $districts = ($state_id > 0)
            ? $this->Location_model->get_districts_by_state($state_id)
            : array();

        header('Content-Type: application/json');
        echo json_encode($districts);
    }

    /* ─────────────────────────────────────────────
     * AJAX: Mandals by district
     * ───────────────────────────────────────────── */
    public function get_mandals()
    {
        $district_id = (int)$this->input->post('district_id');
        $mandals = ($district_id > 0)
            ? $this->Location_model->get_mandals_by_district($district_id)
            : array();

        header('Content-Type: application/json');
        echo json_encode($mandals);
    }

    /* ─────────────────────────────────────────────
     * AJAX: Server-side geocoding proxy
     * Fixes CORS block when calling Nominatim from browser
     * Results cached in mandal_geocache table forever
     * ───────────────────────────────────────────── */
   public function geocode_address()
{
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(array('lat' => null, 'lng' => null));
        return;
    }

    $address = trim($this->input->post('address'));
    if ($address === '') {
        echo json_encode(array('lat' => null, 'lng' => null));
        return;
    }

    // ── Safety: check table exists before querying ────────
    $table_exists = $this->db->table_exists('mandal_geocache');

    // ── 1. Return from cache if table exists ──────────────
    if ($table_exists) {
        $cached = $this->db
            ->where('gc_address', $address)
            ->get('mandal_geocache')
            ->row();

        if ($cached) {
            $lat = ($cached->gc_lat !== null) ? (float)$cached->gc_lat : null;
            $lng = ($cached->gc_lng !== null) ? (float)$cached->gc_lng : null;
            echo json_encode(array('lat' => $lat, 'lng' => $lng));
            return;
        }
    }

    // ── 2. cURL not available ─────────────────────────────
    if (!function_exists('curl_init')) {
        echo json_encode(array('lat' => null, 'lng' => null));
        return;
    }

    // ── 3. Call Nominatim via server-side cURL ────────────
    $url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&q='
           . urlencode($address);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT,      'TGTDA-MemberMap/1.0 (admin@tgtda.com)');
    curl_setopt($ch, CURLOPT_TIMEOUT,        10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Accept-Language: en'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $raw      = curl_exec($ch);
    $curl_err = curl_error($ch);
    curl_close($ch);

    // curl network error — don't cache, just return null
    if ($curl_err || !$raw) {
        echo json_encode(array('lat' => null, 'lng' => null));
        return;
    }

    $data = json_decode($raw, true);

    if (!empty($data[0]['lat']) && !empty($data[0]['lon'])) {
        $lat = (float)$data[0]['lat'];
        $lng = (float)$data[0]['lon'];
        if ($table_exists) {
            $this->_cache_geocode($address, $lat, $lng);
        }
        echo json_encode(array('lat' => $lat, 'lng' => $lng));
    } else {
        // Address not found — cache the miss to avoid repeat calls
        if ($table_exists) {
            $this->_cache_geocode($address, null, null);
        }
        echo json_encode(array('lat' => null, 'lng' => null));
    }
}

/* ─────────────────────────────────────────────
 * Private: Insert geocode result into cache
 * INSERT IGNORE handles duplicate key safely
 * ───────────────────────────────────────────── */
private function _cache_geocode($address, $lat, $lng)
{
    $sql = "INSERT IGNORE INTO mandal_geocache
                (gc_address, gc_lat, gc_lng, gc_created_at)
            VALUES (?, ?, ?, ?)";

    $this->db->query($sql, array(
        $address,
        $lat,
        $lng,
        date('Y-m-d H:i:s'),
    ));
}
}