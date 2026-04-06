<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /* ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);*/
    class Dashboard extends MX_Controller 
    {
    	function __construct() 
    	{
    		parent::__construct();
    		$this->load->library('Session_check');
    		$this->session_check->check_session();
    		$this->load->model(array('Dashboard_model','settings/Comman_model'));
    		$this->load->library('session');
    		$this->load->library('Auth_verify');
    		$this->load->library('pagination');
    		$this->perPage =25;
    	}

    	public function index()
    	{
    		$data_view['data'] = array(
    			'title' => 'TGTDA | Dashboard ',
    			'content' => 'dashboard',
    			'header1' => 'Dashboard',
    			'header2' => 'Dashboard',
    		);
    		$params = array();



		$this->load->view('main_page', $data_view);
	}






}
