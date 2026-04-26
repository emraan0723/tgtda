<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ActiveMember extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
        $this->load->model(array('ActiveMember_model','settings/Comman_model'));
        $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
        $this->authorization->userauthorization('masters','permissionset');
    }

    public function index()
    {
    }

    /* ─────────────────────────────────────────────
     * Toggle ACTIVE / INACTIVE status
     * ───────────────────────────────────────────── */
    public function activeMemberStatus()
    {
        if($this->input->post())
        {
            $this->authorization->userauthorization('masters','edit');
            $params = array();
            $id = isset($_POST['member_id']) ? $this->db->escape_str(trim($_POST['member_id'])) : '';
            $params['status']    = isset($_POST['status']) ? $this->db->escape_str(trim($_POST['status'])) : '';
            $params['member_id'] = $this->encryption->decrypt("$id");
            $result = $this->ActiveMember_model->activeMemberStatusUpdate($params);
            if($result == 'UPDATE_SUCCESS')
            {
                $this->session->set_flashdata('sucess', $this->errormsgs->update_success);
                redirect($_SERVER['HTTP_REFERER']); exit;
            }
            else
            {
                $this->session->set_flashdata('error', $this->errormsgs->update_error);
                redirect($_SERVER['HTTP_REFERER']); exit;
            }
        }
    }

    /* ─────────────────────────────────────────────
     * Load edit modal via AJAX
     * ───────────────────────────────────────────── */
    public function editActiveMember()
    {
        if($this->input->post())
        {
            $this->authorization->userauthorization('masters','edit');

            $params    = array();
            $view_data = array();

            /* country list */
            $res = $this->Comman_model->getCountryList($params);
            if(isset($res['query']))
                $view_data['country_list'] = $res['query']->result_array();

            /* member data */
            $member_id = isset($_POST['member_id']) ? $this->db->escape_str(trim($_POST['member_id'])) : '';
            $params['member_id'] = $this->encryption->decrypt("$member_id");
            $data = $this->ActiveMember_model->getActiveMemberList($params);
            if(isset($data['isexists_insert']) && $data['isexists_insert'] > 0)
                $view_data['getdata'] = $data['query']->row_array();

            /* state list for selected country */
            if(isset($view_data['getdata']['country_id']) && $view_data['getdata']['country_id'] > 0)
            {
                $c = array('country_id' => $view_data['getdata']['country_id']);
                $res = $this->Comman_model->getStateList($c);
                if(isset($res['query']))
                    $view_data['state_list'] = $res['query']->result_array();
            }

            /* district list for selected state */
            if(isset($view_data['getdata']['state_id']) && $view_data['getdata']['state_id'] > 0)
            {
                $s = array('state_id' => $view_data['getdata']['state_id']);
                $res = $this->Comman_model->getDistrictList($s);
                if(isset($res['query']))
                    $view_data['district_list'] = $res['query']->result_array();
            }

            /* mandal list for selected district */
            if(isset($view_data['getdata']['district_id']) && $view_data['getdata']['district_id'] > 0)
            {
                $d = array('district_id' => $view_data['getdata']['district_id']);
                $res = $this->Comman_model->getMandals($d);
                if(isset($res['query']))
                    $view_data['mandal_list'] = $res['query']->result_array();
            }

            /* designation list */
            $view_data['designation_list'] = $this->ActiveMember_model->getDesignationList();

            $this->load->view('edit_active_member', $view_data);
        }
    }

    /* ─────────────────────────────────────────────
     * Main page + save (insert / update)
     * ───────────────────────────────────────────── */
    public function activemember()
    {
        $this->authorization->userauthorization('masters','view');

        $data_view['data'] = array(
            'title'   => 'TGTDA | Masters',
            'content' => 'active_member',
            'header1' => 'Masters',
            'header2' => 'Active Member',
        );

        $params = array();

        /* country list */
        $res = $this->Comman_model->getCountryList($params);
        if(isset($res['query']))
            $data_view['country_list'] = $res['query']->result_array();

        /* designation list */
        $data_view['designation_list'] = $this->ActiveMember_model->getDesignationList();

        if($this->input->post())
        {
            $this->authorization->userauthorization('masters','adding');

            /* server-side validation */
            $this->form_validation->set_rules('country_id',    'Country',     'required');
            $this->form_validation->set_rules('state_id',      'State',       'required');
            $this->form_validation->set_rules('district_id',   'District',    'required');
            $this->form_validation->set_rules('mandal_id',     'Mandal',      'required');
            $this->form_validation->set_rules('designation',   'Designation', 'required');
            $this->form_validation->set_rules('member_name',   'Member Name', 'required|trim');

            if($this->form_validation->run() == TRUE)
            {
                $params['country_id']   = isset($_POST['country_id'])   ? (int)$_POST['country_id']   : 0;
                $params['state_id']     = isset($_POST['state_id'])     ? (int)$_POST['state_id']     : 0;
                $params['district_id']  = isset($_POST['district_id'])  ? (int)$_POST['district_id']  : 0;
                $params['mandal_id']    = isset($_POST['mandal_id'])    ? (int)$_POST['mandal_id']    : 0;
                $params['designation']  = isset($_POST['designation'])  ? $this->db->escape_str(trim($_POST['designation']))  : '';
                $params['member_name']  = isset($_POST['member_name'])  ? $this->db->escape_str(trim($_POST['member_name']))  : '';

                $id = isset($_POST['active_member_id']) ? $this->db->escape_str(trim($_POST['active_member_id'])) : '';
                $params['active_member_id'] = $this->encryption->decrypt("$id");

                $result = $this->ActiveMember_model->saveActiveMember($params);

                $flash_map = array(
                    'INSERT_SUCCESS'       => array('sucess', $this->errormsgs->add_suceess),
                    'INSERT_FAILED'        => array('error',  $this->errormsgs->add_error),
                    'UPDATE_SUCCESS'       => array('sucess', $this->errormsgs->update_success),
                    'UPDATE_FAILED'        => array('error',  $this->errormsgs->update_error),
                    'ALREADY_EXISTS'       => array('is_exits', $this->errormsgs->add_isexits),
                );
                if(isset($flash_map[$result]))
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

        $list  = $this->ActiveMember_model->get_datatables();
        $data  = array();
        $no    = $_POST['start'];
        $i     = 1;

        foreach($list as $member)
        {
            $html_status  = ($member->status == 'ACTIVE') ? 'INACTIVE' : 'ACTIVE';
            $member_id    = $this->encryption->encrypt($member->member_id);
            $editonclick  = "activeMemberEdit('".$member_id."');";
            $statusclick  = "activeMemberStatus('".$i."');";

            $no++;
            $row   = array();
            $row[] = $no;
            $row[] = $member->country_name;
            $row[] = $member->state_name;
            $row[] = $member->district_name;
            $row[] = $member->mandal_name;
            $row[] = $member->designation;
            $row[] = $member->member_name;
            $row[] = $member->status;
            $row[] = '
                <div class="btn-group">
                <button type="button" class="btn btn-dark dropdown-toggle"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ti-settings"></i>
                </button>
                <div class="dropdown-menu animated slideInUp"
                    style="position:absolute;will-change:transform;top:0px;left:0px;transform:translate3d(0px,35px,0px);">
                    <a class="dropdown-item" onclick="'.$editonclick.'" href="javascript:void(0)">
                        <i class="ti-pencil-alt"></i> Edit</a>
                    <form id="status_frm_id_'.$i.'" method="post" action="'.base_url().'masters/activemember/activeMemberStatus">
                        <input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
                        <input type="hidden" value="'.$member_id.'" name="member_id">
                        <input type="hidden" value="'.$html_status.'" name="status">
                        <a class="dropdown-item" onclick="'.$statusclick.'" href="javascript:void(0)">'.$html_status.'</a>
                    </form>
                </div>
                </div>';
            $row['bv'] = $i;
            $data[]    = $row;
            $i++;
        }

        $output = array(
            "draw"            => $_POST['draw'],
            "recordsTotal"    => $this->ActiveMember_model->count_all(),
            "recordsFiltered" => $this->ActiveMember_model->count_filtered(),
            "data"            => $data,
        );
        echo json_encode($output);
    }
}
