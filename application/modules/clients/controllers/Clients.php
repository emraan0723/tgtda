<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class Clients extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
        $this->load->model(array('Location_model'));
        $this->load->helper(array('form','url','common_helper'));
        $this->load->library(array('session','upload','form_validation'));
        $this->session_check->check_session();
      //  $this->authorization->userauthorization('user','permissionset');
        $this->perPage = 25;
    }

    public function index()
    {
        $data_view = array();
        $data_view['data'] = array(
            'title'   => 'TGTDA|Dashboard',
            'content' => 'dashboard',
            'header1' => 'User',
            'header2' => 'Dashboard',
        );
        $this->load->view('main_page', $data_view);
    }


}
