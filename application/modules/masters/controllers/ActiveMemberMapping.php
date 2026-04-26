<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ActiveMemberMapping extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library(array(
            'Session_check','session','form_validation',
            'validationdigi','errormsgs','encryption','authorization'
        ));
        $this->load->model(array(
            'ActiveMemberMapping_model',
            'settings/Comman_model'
        ));
        $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
        $this->authorization->userauthorization('masters','permissionset');
    }

    public function index()
    {
        $this->activemembermapping();
    }

    /* ─────────────────────────────────────────────
     * AJAX: get members by mandal_id from tbl_registrations
     * POST: mandal_id  → JSON [{tr_id, tr_full_name}, ...]
     * ───────────────────────────────────────────── */
    public function getMembersByMandal()
    {
        if ($this->input->post())
        {
            $mandal_id = isset($_POST['mandal_id']) ? (int)$_POST['mandal_id'] : 0;
            $result    = $this->ActiveMemberMapping_model->getMembersByMandal($mandal_id);
            echo json_encode($result);
        }
    }

    /* ─────────────────────────────────────────────
     * Toggle ACTIVE / INACTIVE status
     * ───────────────────────────────────────────── */
    public function activeMemberMappingStatus()
    {
        if ($this->input->post())
        {
            $this->authorization->userauthorization('masters','edit');
            $id                = isset($_POST['mapping_id']) ? $this->db->escape_str(trim($_POST['mapping_id'])) : '';
            $params['status']  = isset($_POST['status'])     ? $this->db->escape_str(trim($_POST['status']))     : '';
            $params['mapping_id'] = $this->encryption->decrypt("$id");

            $result = $this->ActiveMemberMapping_model->updateMappingStatus($params);
            if ($result == 'UPDATE_SUCCESS')
                $this->session->set_flashdata('sucess', $this->errormsgs->update_success);
            else
                $this->session->set_flashdata('error',  $this->errormsgs->update_error);

            redirect($_SERVER['HTTP_REFERER']); exit;
        }
    }

    /* ─────────────────────────────────────────────
     * Load edit modal via AJAX
     * ───────────────────────────────────────────── */
    public function editActiveMemberMapping()
    {
        if ($this->input->post())
        {
            $this->authorization->userauthorization('masters','edit');

            $view_data = array();
            $params    = array();

            /* decrypt mapping id */
            $enc_id = isset($_POST['mapping_id']) ? $this->db->escape_str(trim($_POST['mapping_id'])) : '';
            $mapping_id = $this->encryption->decrypt("$enc_id");

            /* fetch existing mapping row */
            $row = $this->ActiveMemberMapping_model->getMappingById($mapping_id);
            if ($row)
            {
                $view_data['getdata'] = $row;

                /* district list for the saved state */
                if ($row['tamm_state_id'] > 0)
                {
                    $res = $this->Comman_model->getDistrictList(array('state_id' => $row['tamm_state_id']));
                    if (isset($res['query']))
                        $view_data['district_list'] = $res['query']->result_array();
                }

                /* mandal list for the saved district */
                if ($row['tamm_district_id'] > 0)
                {
                    $res = $this->Comman_model->getMandals(array('district_id' => $row['tamm_district_id']));
                    if (isset($res['query']))
                        $view_data['mandal_list'] = $res['query']->result_array();
                }

                /* member list for saved mandal */
                if ($row['tamm_mandal_id'] > 0)
                    $view_data['member_list'] = $this->ActiveMemberMapping_model->getMembersByMandal($row['tamm_mandal_id']);
            }

            $view_data['designation_list'] = $this->ActiveMemberMapping_model->getDesignationList();

            $this->load->view('edit_active_member_mapping', $view_data);
        }
    }

    /* ─────────────────────────────────────────────
     * Main page + save (insert / update)
     * ───────────────────────────────────────────── */
    public function activemembermapping()
    {
        $this->authorization->userauthorization('masters','view');

        $data_view['data'] = array(
            'title'   => 'TGTDA | Masters',
            'content' => 'active_member_mapping',
            'header1' => 'Masters',
            'header2' => 'Active Member Mapping',
        );

        $data_view['designation_list'] = $this->ActiveMemberMapping_model->getDesignationList();

        /* for filter dropdowns */
        $res = $this->Comman_model->getCountryList(array());
        if (isset($res['query']))
            $data_view['country_list'] = $res['query']->result_array();

        if ($this->input->post())
        {
            $this->authorization->userauthorization('masters','adding');

            /* server-side validation */
            $this->form_validation->set_rules('district_id',  'District',    'required');
            $this->form_validation->set_rules('mandal_id',    'Mandal',      'required');
            $this->form_validation->set_rules('designation',  'Designation', 'required');
            $this->form_validation->set_rules('member_id',    'Member',      'required');

            if ($this->form_validation->run() == TRUE)
            {
                $params = array();
                $params['district_id'] = isset($_POST['district_id']) ? (int)$_POST['district_id'] : 0;
                $params['mandal_id']   = isset($_POST['mandal_id'])   ? (int)$_POST['mandal_id']   : 0;
                $params['designation'] = isset($_POST['designation'])
                    ? $this->db->escape_str(trim($_POST['designation'])) : '';
                $params['member_id']   = isset($_POST['member_id'])   ? (int)$_POST['member_id']   : 0;

                $enc = isset($_POST['mapping_id']) ? $this->db->escape_str(trim($_POST['mapping_id'])) : '';
                $params['mapping_id'] = $enc ? (int)$this->encryption->decrypt("$enc") : 0;

                $result = $this->ActiveMemberMapping_model->saveMemberMapping($params);

                $flash_map = array(
                    'INSERT_SUCCESS' => array('sucess',   $this->errormsgs->add_suceess),
                    'INSERT_FAILED'  => array('error',    $this->errormsgs->add_error),
                    'UPDATE_SUCCESS' => array('sucess',   $this->errormsgs->update_success),
                    'UPDATE_FAILED'  => array('error',    $this->errormsgs->update_error),
                    'ALREADY_EXISTS' => array('is_exits', $this->errormsgs->add_isexits),
                );

                if (isset($flash_map[$result]))
                    $this->session->set_flashdata($flash_map[$result][0], $flash_map[$result][1]);
                else
                    $this->session->set_flashdata('error', $this->errormsgs->add_error);

                redirect($_SERVER['HTTP_REFERER']); exit;
            }
        }

        $this->load->view('main_page', $data_view);
    }

    /* ─────────────────────────────────────────────
     * DataTables server-side AJAX
     * ───────────────────────────────────────────── */
    public function ajax_list()
    {
        $this->authorization->userauthorization('masters','view');

        $list = $this->ActiveMemberMapping_model->get_datatables();
        $data = array();
        $no   = (int)$_POST['start'];
        $i    = 1;

        foreach ($list as $row)
        {
            $html_status = ($row->tamm_status == 'ACTIVE') ? 'INACTIVE' : 'ACTIVE';
            $enc_id      = $this->encryption->encrypt($row->tamm_id);
            $editclick   = "ammEdit('".$enc_id."');";
            $statclick   = "ammStatus('".$i."');";

            $no++;

            /* ── hover card HTML ── */
            $photo_url = base_url().'uploads/registration/'.$row->tr_reg_key.'/'.($row->tr_selfie ? $row->tr_selfie : 'default.png');
            $hover_html = '
            <div class="amm-hover-card">
                <img src="'.$photo_url.'" class="amm-selfie" onerror="this.src=\''.base_url().'assets/images/default-avatar.png\'">
                <table class="table table-sm mb-0">
                    <tr><td><b>Mobile</b></td><td>'.htmlspecialchars($row->tr_mobile).'</td></tr>
                    <tr><td><b>Email</b></td><td>'.htmlspecialchars($row->tr_email).'</td></tr>
                    <tr><td><b>Reg Key</b></td><td>'.htmlspecialchars($row->tr_reg_key).'</td></tr>
                    <tr><td><b>Unique Key</b></td><td>'.htmlspecialchars($row->tr_reg_ukey).'</td></tr>
                    <tr><td><b>Language</b></td><td>'.htmlspecialchars($row->tr_language).'</td></tr>
                    <tr><td><b>Reg Type</b></td><td>'.htmlspecialchars($row->tr_registration_type).'</td></tr>
                    <tr><td><b>Aadhaar</b></td><td>'.htmlspecialchars($row->tr_aadhar_no).'</td></tr>
                    <tr><td><b>PAN</b></td><td>'.htmlspecialchars($row->tr_pan_no).'</td></tr>
                    <tr><td><b>Address</b></td><td>'.htmlspecialchars($row->tr_full_address).'</td></tr>
                </table>
            </div>';

            $member_cell = '
            <div class="amm-member-wrap" style="position:relative;display:inline-block;">
                <span class="amm-member-name" data-toggle-card="card_'.$i.'" style="cursor:pointer;color:#1565c0;font-weight:600;">
                    <i class="ti-user mr-1"></i>'.htmlspecialchars(ucwords(strtolower($row->tr_full_name))).'
                </span>
                <div id="card_'.$i.'" class="amm-info-card" style="display:none;">
                    '.$hover_html.'
                </div>
            </div>';

            $status_badge = ($row->tamm_status == 'ACTIVE')
                ? '<span class="badge badge-success">ACTIVE</span>'
                : '<span class="badge badge-danger">INACTIVE</span>';

            $action = '
            <div class="btn-group">
                <button type="button" class="btn btn-dark btn-sm dropdown-toggle"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ti-settings"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right animated slideInUp">
                    <a class="dropdown-item" onclick="'.$editclick.'" href="javascript:void(0)">
                        <i class="ti-pencil-alt"></i> Edit</a>
                    <form id="amm_status_frm_'.$i.'" method="post"
                          action="'.base_url().'masters/activemembermapping/activeMemberMappingStatus">
                        <input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
                        <input type="hidden" name="mapping_id" value="'.$enc_id.'">
                        <input type="hidden" name="status"     value="'.$html_status.'">
                        <a class="dropdown-item text-'.($row->tamm_status == 'ACTIVE' ? 'danger' : 'success').'"
                           onclick="'.$statclick.'" href="javascript:void(0)">
                           <i class="ti-power-off"></i> '.$html_status.'</a>
                    </form>
                </div>
            </div>';

            $data_row   = array();
            $data_row[] = $no;
            $data_row[] = htmlspecialchars(ucwords(strtolower($row->tc_country_name)));
            $data_row[] = htmlspecialchars(ucwords(strtolower($row->ts_state_name)));
            $data_row[] = htmlspecialchars(ucwords(strtolower($row->tdt_district_name)));
            $data_row[] = htmlspecialchars(ucwords(strtolower($row->tm_mandal)));
            $data_row[] = htmlspecialchars($row->tamm_designation);
            $data_row[] = $member_cell;
            $data_row[] = $status_badge;
            $data_row[] = $action;

            $data[] = $data_row;
            $i++;
        }

        $output = array(
            "draw"            => isset($_POST['draw']) ? (int)$_POST['draw'] : 1,
            "recordsTotal"    => $this->ActiveMemberMapping_model->count_all(),
            "recordsFiltered" => $this->ActiveMemberMapping_model->count_filtered(),
            "data"            => $data,
        );
        echo json_encode($output);
    }
}
