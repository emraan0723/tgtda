<?php
/**
 * MandalLatLng Controller
 * PHP 5.6 / 7.2 compatible
 * Place: application/modules/masters/controllers/MandalLatLng.php
 * URL  : masters/mandallatlong/index
 *
 * Provides UI + AJAX endpoints to dynamically set/update
 * lat/lng coordinates for mandals and districts.
 * Uses OpenStreetMap Nominatim for auto-geocoding suggestions.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class MandalLatLng extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array(
            'Session_check', 'session', 'form_validation',
            'encryption', 'authorization', 'errormsgs'
        ));
        $this->load->model(array(
            'MandalLatLng_model',
            'Location_model',
        ));
        $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
        $this->authorization->userauthorization('masters', 'permissionset');
    }

    /* ─────────────────────────────────────────────
     * Main page — mandal lat/lng manager
     * ───────────────────────────────────────────── */
    public function index()
    {
        $this->authorization->userauthorization('masters', 'view');

        $data_view['data'] = array(
            'title'   => 'TGTDA | Mandal Coordinates',
            'content' => 'mandal_latlng',
            'header1' => 'Masters',
            'header2' => 'Mandal / District Coordinates',
        );

        $data_view['country_list'] = $this->Location_model->get_countries();

        $this->load->view('main_page', $data_view);
    }

    /* ─────────────────────────────────────────────
     * AJAX: DataTables list of mandals with lat/lng status
     * POST: standard DT params + filter_district, filter_state,
     *       filter_has_latlng ('yes'|'no'|'')
     * ───────────────────────────────────────────── */
    public function ajax_list()
    {
        $this->authorization->userauthorization('masters', 'view');

        $filters = array(
            'district_id'    => (int)$this->input->post('filter_district'),
            'state_id'       => (int)$this->input->post('filter_state'),
            'has_latlng'     => $this->input->post('filter_has_latlng'),
            'search'         => isset($_POST['search']['value'])
                                    ? trim($_POST['search']['value']) : '',
            'order_col'      => isset($_POST['order'][0]['column'])
                                    ? (int)$_POST['order'][0]['column'] : 0,
            'order_dir'      => isset($_POST['order'][0]['dir'])
                                    ? $_POST['order'][0]['dir'] : 'asc',
            'start'          => isset($_POST['start'])  ? (int)$_POST['start']  : 0,
            'length'         => isset($_POST['length']) ? (int)$_POST['length'] : 10,
            'draw'           => isset($_POST['draw'])   ? (int)$_POST['draw']   : 1,
        );

        $list     = $this->MandalLatLng_model->get_mandals_dt($filters);
        $total    = $this->MandalLatLng_model->count_all();
        $filtered = $this->MandalLatLng_model->count_filtered($filters);

        $data = array();
        $no   = $filters['start'];

        foreach ($list as $row)
        {
            $no++;

            $has_lat = ( ! empty($row['tm_lat'])  && $row['tm_lat']  != 0);
            $has_lng = ( ! empty($row['tm_lng'])  && $row['tm_lng']  != 0);
            $has_coords = ($has_lat && $has_lng);

            $status_badge = $has_coords
                ? '<span class="badge badge-success"><i class="ti-check mr-1"></i>Set</span>'
                : '<span class="badge badge-warning"><i class="ti-alert mr-1"></i>Not Set</span>';

            $lat_val = $has_lat ? number_format((float)$row['tm_lat'], 7) : '—';
            $lng_val = $has_lng ? number_format((float)$row['tm_lng'], 7) : '—';

            $enc_id   = $this->encryption->encrypt($row['tm_mandal_ID']);
            $map_link = $has_coords
                ? '<a href="https://www.openstreetmap.org/?mlat=' . $row['tm_lat'] .
                  '&mlon=' . $row['tm_lng'] . '&zoom=14" target="_blank" ' .
                  'class="btn btn-xs btn-outline-info ml-1" title="View on Map">' .
                  '<i class="ti-map-alt"></i></a>'
                : '';

            $action =
                '<button class="btn btn-sm btn-primary" ' .
                    'onclick="mllEdit(\'' . $enc_id . '\',\'mandal\')" ' .
                    'title="Set Coordinates">' .
                    '<i class="ti-pencil-alt"></i> Edit</button>' .
                $map_link;

            $data[] = array(
                $no,
                htmlspecialchars(ucwords(strtolower($row['tm_mandal']))),
                htmlspecialchars(ucwords(strtolower($row['tdt_district_name'] ?? '—'))),
                htmlspecialchars(ucwords(strtolower($row['ts_state_name']    ?? '—'))),
                $lat_val,
                $lng_val,
                $status_badge,
                $action,
            );
        }

        echo json_encode(array(
            'draw'            => $filters['draw'],
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ));
    }

    /* ─────────────────────────────────────────────
     * AJAX: DataTables list of districts
     * ───────────────────────────────────────────── */
    public function ajax_list_districts()
    {
        $this->authorization->userauthorization('masters', 'view');

        $filters = array(
            'state_id'   => (int)$this->input->post('filter_state'),
            'has_latlng' => $this->input->post('filter_has_latlng'),
            'search'     => isset($_POST['search']['value'])
                                ? trim($_POST['search']['value']) : '',
            'start'      => isset($_POST['start'])  ? (int)$_POST['start']  : 0,
            'length'     => isset($_POST['length']) ? (int)$_POST['length'] : 10,
            'draw'       => isset($_POST['draw'])   ? (int)$_POST['draw']   : 1,
        );

        $list     = $this->MandalLatLng_model->get_districts_dt($filters);
        $total    = $this->MandalLatLng_model->count_all_districts();
        $filtered = $this->MandalLatLng_model->count_filtered_districts($filters);

        $data = array();
        $no   = $filters['start'];

        foreach ($list as $row)
        {
            $no++;
            $has_coords = ( ! empty($row['tdt_lat']) && $row['tdt_lat'] != 0
                         && ! empty($row['tdt_lng']) && $row['tdt_lng'] != 0);

            $status_badge = $has_coords
                ? '<span class="badge badge-success"><i class="ti-check mr-1"></i>Set</span>'
                : '<span class="badge badge-warning"><i class="ti-alert mr-1"></i>Not Set</span>';

            $lat_val = $has_coords ? number_format((float)$row['tdt_lat'], 7) : '—';
            $lng_val = $has_coords ? number_format((float)$row['tdt_lng'], 7) : '—';

            $enc_id   = $this->encryption->encrypt($row['tdt_district_ID']);
            $map_link = $has_coords
                ? '<a href="https://www.openstreetmap.org/?mlat=' . $row['tdt_lat'] .
                  '&mlon=' . $row['tdt_lng'] . '&zoom=12" target="_blank" ' .
                  'class="btn btn-xs btn-outline-info ml-1">' .
                  '<i class="ti-map-alt"></i></a>'
                : '';

            $action =
                '<button class="btn btn-sm btn-primary" ' .
                    'onclick="mllEdit(\'' . $enc_id . '\',\'district\')" ' .
                    'title="Set Coordinates">' .
                    '<i class="ti-pencil-alt"></i> Edit</button>' .
                $map_link;

            $data[] = array(
                $no,
                htmlspecialchars(ucwords(strtolower($row['tdt_district_name']))),
                htmlspecialchars(ucwords(strtolower($row['ts_state_name'] ?? '—'))),
                $lat_val,
                $lng_val,
                $status_badge,
                $action,
            );
        }

        echo json_encode(array(
            'draw'            => $filters['draw'],
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $data,
        ));
    }

    /* ─────────────────────────────────────────────
     * AJAX: Get single record for edit modal
     * POST: enc_id, type ('mandal'|'district')
     * ───────────────────────────────────────────── */
    public function get_record()
    {
        if ( ! $this->input->post()) { echo json_encode(array()); return; }

        $type   = $this->input->post('type') === 'district' ? 'district' : 'mandal';
        $enc_id = $this->db->escape_str(trim($this->input->post('enc_id')));
        $id     = (int)$this->encryption->decrypt($enc_id);

        if ($id <= 0) { echo json_encode(array('error' => 'Invalid ID')); return; }

        if ($type === 'district') {
            $row = $this->MandalLatLng_model->get_district($id);
        } else {
            $row = $this->MandalLatLng_model->get_mandal($id);
        }

        header('Content-Type: application/json');
        echo json_encode($row);
    }

    /* ─────────────────────────────────────────────
     * AJAX: Save lat/lng for mandal or district
     * POST: enc_id, type, lat, lng
     * ───────────────────────────────────────────── */
    public function save_latlng()
    {
        if ( ! $this->input->post()) {
            echo json_encode(array('status' => 'ERROR', 'msg' => 'Invalid request'));
            return;
        }

        $this->authorization->userauthorization('masters', 'edit');

        $type   = $this->input->post('type') === 'district' ? 'district' : 'mandal';
        $enc_id = $this->db->escape_str(trim($this->input->post('enc_id')));
        $id     = (int)$this->encryption->decrypt($enc_id);
        $lat    = (float)$this->input->post('lat');
        $lng    = (float)$this->input->post('lng');

        if ($id <= 0) {
            echo json_encode(array('status' => 'ERROR', 'msg' => 'Invalid ID'));
            return;
        }

        /* Validate coordinate ranges */
        if ($lat < -90 || $lat > 90) {
            echo json_encode(array('status' => 'ERROR', 'msg' => 'Latitude must be between -90 and 90'));
            return;
        }
        if ($lng < -180 || $lng > 180) {
            echo json_encode(array('status' => 'ERROR', 'msg' => 'Longitude must be between -180 and 180'));
            return;
        }

        if ($type === 'district') {
            $result = $this->MandalLatLng_model->save_district_latlng($id, $lat, $lng);
        } else {
            $result = $this->MandalLatLng_model->save_mandal_latlng($id, $lat, $lng);
        }

        header('Content-Type: application/json');
        echo json_encode($result);
    }

    /* ─────────────────────────────────────────────
     * AJAX: Bulk auto-geocode all mandals/districts
     * that don't have lat/lng set yet.
     * POST: type ('mandal'|'district'), batch_size (1-10)
     * Returns: next batch of records needing geocoding
     * ───────────────────────────────────────────── */
    public function get_ungeocodeed()
    {
        if ( ! $this->input->post()) { echo json_encode(array()); return; }

        $type  = $this->input->post('type') === 'district' ? 'district' : 'mandal';
        $limit = min(10, max(1, (int)$this->input->post('batch_size')));

        if ($type === 'district') {
            $rows = $this->MandalLatLng_model->get_districts_without_latlng($limit);
        } else {
            $rows = $this->MandalLatLng_model->get_mandals_without_latlng($limit);
        }

        header('Content-Type: application/json');
        echo json_encode($rows);
    }

    /* ─────────────────────────────────────────────
     * AJAX: Save bulk geocoded results in one shot
     * POST: items = JSON array of {id, lat, lng, type}
     * ───────────────────────────────────────────── */
    public function save_bulk()
    {
        if ( ! $this->input->post()) {
            echo json_encode(array('status' => 'ERROR', 'saved' => 0));
            return;
        }

        $this->authorization->userauthorization('masters', 'edit');

        $items_raw = $this->input->post('items');
        $items     = json_decode($items_raw, true);

        if ( ! is_array($items) || empty($items)) {
            echo json_encode(array('status' => 'ERROR', 'saved' => 0));
            return;
        }

        $saved = 0;
        foreach ($items as $item) {
            $id   = isset($item['id'])   ? (int)$item['id']     : 0;
            $lat  = isset($item['lat'])  ? (float)$item['lat']  : 0;
            $lng  = isset($item['lng'])  ? (float)$item['lng']  : 0;
            $type = isset($item['type']) && $item['type'] === 'district' ? 'district' : 'mandal';

            if ($id <= 0 || $lat == 0 || $lng == 0) continue;
            if ($lat < -90 || $lat > 90)    continue;
            if ($lng < -180 || $lng > 180)  continue;

            if ($type === 'district') {
                $r = $this->MandalLatLng_model->save_district_latlng($id, $lat, $lng);
            } else {
                $r = $this->MandalLatLng_model->save_mandal_latlng($id, $lat, $lng);
            }
            if (isset($r['status']) && $r['status'] === 'SUCCESS') $saved++;
        }

        header('Content-Type: application/json');
        echo json_encode(array('status' => 'SUCCESS', 'saved' => $saved));
    }

    /* ─────────────────────────────────────────────
     * AJAX: Get coverage stats
     * ───────────────────────────────────────────── */
    public function get_stats()
    {
        header('Content-Type: application/json');
        echo json_encode($this->MandalLatLng_model->get_coverage_stats());
    }

    /* ─────────────────────────────────────────────
     * AJAX: States by country (for filter dropdowns)
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
     * AJAX: Districts by state
     * ───────────────────────────────────────────── */
    public function get_districts()
    {
        $state_id = (int)$this->input->post('state_id');
        $districts = ($state_id > 0)
            ? $this->Location_model->get_districts_by_state($state_id) : array();
        header('Content-Type: application/json');
        echo json_encode($districts);
    }
}
