<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Clients extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
        $this->load->model(array('Clients_model'));
        $this->load->helper(array('form','url','common_helper'));
        $this->load->library(array('session','upload','form_validation'));
        $this->session_check->check_session();
        //  $this->authorization->userauthorization('user','permissionset');
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

        $data_view = array();
        $data_view['data'] = array(
            'title'   => 'TGTDA | ID Card',
            'content' => 'dashboard',
            'header1' => 'User',
            'header2' => 'ID Card',
        );

        $data_view['driver']      = $driver;
        $data_view['reg_id']      = $driver['tr_reg_ukey'];
        $data_view['aadhar']      = $driver['tr_aadhar_no'];
        $data_view['initials']    = $initials;
        $data_view['issue_date']  = $created->format('d M Y');
        $data_view['valid_until'] = $valid->format('d M Y');
        $data_view['reg_key']     = $driver['tr_reg_key'];

        $this->load->view('main_page', $data_view);

    }


}
