<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /* ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);*/
class Authorization extends MX_Controller 
{
	function __construct() 
	{
	    parent::__construct();
	    $this->load->library('session');
	    $this->load->library('Session_check');
	    $this->load->library('errormsgs');
	     
        $this->session_check->check_session();
	}

	

	public function authorization()
	{
		$data_view['data'] = array('authentication' => 'authenticationError',
		//s'title' => ' eyeSmart Digital Payments',
		'msg'=> $this->errormsgs->authenticationerror);
		$this->load->view('main_page', $data_view);	
		exit;

	}

	
	
}
