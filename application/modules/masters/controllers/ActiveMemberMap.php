<?php
/**
 * ActiveMemberMap Controller
 * PHP 5.6 / 7.x compatible
 * Place: application/modules/masters/controllers/ActiveMemberMap.php
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class ActiveMemberMap extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('Session_check','session','encryption','authorization'));
        $this->load->model(array('ActiveMemberMap_model','user/Location_model'));
        $this->load->helper(array('form','url'));
        $this->session_check->check_session();
    }

    public function index()
    {
        $this->authorization->userauthorization('masters','view');
        $data_view['data'] = array(
            'title'   => 'TGTDA | Active Member Map',
            'content' => 'active_member_map',
            'header1' => 'Masters',
            'header2' => 'Active Member Map',
        );
        $data_view['country_list'] = $this->Location_model->get_countries();
        $this->load->view('main_page', $data_view);
    }

    /* Stats: total_registered, total_members, mandals, districts, states, designations */
    public function get_stats()
    {
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_overall_stats());
    }

    /* PINK pins — active mapped members */
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

    /* GREEN pins — all registered members grouped by mandal */
    public function get_registered_pins()
    {
        $filters = array(
            'country_id'  => (int)$this->input->post('country_id'),
            'state_id'    => (int)$this->input->post('state_id'),
            'district_id' => (int)$this->input->post('district_id'),
        );
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_registered_pins($filters));
    }

    /* Modal members for PINK pin (active) */
    public function get_mandal_members()
    {
        $mandal_id = (int)$this->input->post('mandal_id');
        if ($mandal_id <= 0) { header('Content-Type: application/json'); echo json_encode(array()); return; }
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_members_by_mandal($mandal_id));
    }

    /* Modal members for GREEN pin (registered) — called by view */
    public function get_registered_by_location()
    {
        $filters = array(
            'state_id'    => (int)$this->input->post('state_id'),
            'district_id' => (int)$this->input->post('district_id'),
            'mandal_id'   => (int)$this->input->post('mandal_id'),
        );
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_registered_by_location($filters));
    }

    /* Modal members for GREEN pin (registered) — legacy alias */
    public function get_registered_by_mandal()
    {
        $filters = array(
            'state_id'    => (int)$this->input->post('state_id'),
            'district_id' => (int)$this->input->post('district_id'),
            'mandal_id'   => (int)$this->input->post('mandal_id'),
        );
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_registered_by_location($filters));
    }

    /* Right panel: Total Registered — state-wise */
    public function get_registered_panel()
    {
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_registered_panel());
    }

    /* Right panel: Active Members — state-wise */
    public function get_state_members_panel()
    {
        $filters = array(
            'country_id'  => (int)$this->input->post('country_id'),
            'state_id'    => (int)$this->input->post('state_id'),
            'district_id' => (int)$this->input->post('district_id'),
        );
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_state_members_panel($filters));
    }

    /* Right panel: States — registered + active members full detail */
    public function get_states_full_panel()
    {
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_states_full_panel());
    }

    /* Right panel: Districts — registered + active members full detail */
    public function get_districts_full_panel()
    {
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_districts_full_panel());
    }

    /* Right panel: Mandals — registered + active members full detail */
    public function get_mandals_full_panel()
    {
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_mandals_full_panel());
    }

    /* Right panel: States list */
    public function get_states_panel()
    {
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_states_panel());
    }

    /* Right panel: Districts list */
    public function get_district_summary()
    {
        $filters = array(
            'country_id' => (int)$this->input->post('country_id'),
            'state_id'   => (int)$this->input->post('state_id'),
        );
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_district_summary($filters));
    }

    /* Right panel: Designation members */
    public function get_designation_members()
    {
        $desig = $this->input->post('designation');
        if (!$desig) { header('Content-Type: application/json'); echo json_encode(array()); return; }
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_designation_members($desig));
    }

    public function get_district_designations()
    {
        $district_id = (int)$this->input->post('district_id');
        if ($district_id <= 0) { header('Content-Type: application/json'); echo json_encode(array()); return; }
        header('Content-Type: application/json');
        echo json_encode($this->ActiveMemberMap_model->get_district_designations($district_id));
    }

    public function get_states()
    {
        $country_id = (int)$this->input->post('country_id');
        $states = ($country_id > 0) ? $this->Location_model->get_states_by_country($country_id) : array();
        header('Content-Type: application/json');
        echo json_encode($states);
    }

    public function get_districts()
    {
        $state_id = (int)$this->input->post('state_id');
        $districts = ($state_id > 0) ? $this->Location_model->get_districts_by_state($state_id) : array();
        header('Content-Type: application/json');
        echo json_encode($districts);
    }

    public function get_mandals()
    {
        $district_id = (int)$this->input->post('district_id');
        $mandals = ($district_id > 0) ? $this->Location_model->get_mandals_by_district($district_id) : array();
        header('Content-Type: application/json');
        echo json_encode($mandals);
    }

    /* Server-side geocoding proxy with DB cache */
    public function geocode_address()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(array('lat'=>null,'lng'=>null)); return; }

        $address = trim($this->input->post('address'));
        if ($address === '') { echo json_encode(array('lat'=>null,'lng'=>null)); return; }

        $table_exists = $this->db->table_exists('mandal_geocache');
        if ($table_exists) {
            $cached = $this->db->where('gc_address',$address)->get('mandal_geocache')->row();
            if ($cached) {
                echo json_encode(array(
                    'lat' => ($cached->gc_lat !== null) ? (float)$cached->gc_lat : null,
                    'lng' => ($cached->gc_lng !== null) ? (float)$cached->gc_lng : null,
                ));
                return;
            }
        }

        if (!function_exists('curl_init')) { echo json_encode(array('lat'=>null,'lng'=>null)); return; }

        $ch = curl_init('https://nominatim.openstreetmap.org/search?format=json&limit=1&q='.urlencode($address));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT,      'TGTDA-MemberMap/1.0 (admin@tgtda.com)');
        curl_setopt($ch, CURLOPT_TIMEOUT,        10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Accept-Language: en'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $raw = curl_exec($ch); $err = curl_error($ch); curl_close($ch);

        if ($err || !$raw) { echo json_encode(array('lat'=>null,'lng'=>null)); return; }

        $data = json_decode($raw, true);
        if (!empty($data[0]['lat'])) {
            $lat = (float)$data[0]['lat']; $lng = (float)$data[0]['lon'];
            if ($table_exists) $this->_cache_geocode($address, $lat, $lng);
            echo json_encode(array('lat'=>$lat,'lng'=>$lng));
        } else {
            if ($table_exists) $this->_cache_geocode($address, null, null);
            echo json_encode(array('lat'=>null,'lng'=>null));
        }
    }

    private function _cache_geocode($address, $lat, $lng)
    {
        $this->db->query(
            "INSERT IGNORE INTO mandal_geocache (gc_address,gc_lat,gc_lng,gc_created_at) VALUES (?,?,?,?)",
            array($address, $lat, $lng, date('Y-m-d H:i:s'))
        );
    }
}