<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /* ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);*/
class Profile extends MX_Controller 
{
	function __construct() 
	{
	    parent::__construct();

	    $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
	    $this->load->model(array('Admin_model'));
	    $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
        //$this->authorization->userauthorization('user','permissionset');
	    $this->perPage =25;

	}

	public function index()
	{
		
	}

	public function  changePassword()
	{
		
		$data = array();
				$data_view['data'] = array(
				'title' => ' eyeSmart Digital Payments |Profile ',
				'content' => 'user_profile',
				'header1' => 'Profile',
		         'header2' => 'User Profile',
				);

		if($this->input->post())
		{

			 #SEVER VALIDATIONS LIBRARY 
			 $this->validationdigi->admin();
			if($this->form_validation->run() == TRUE)
			{
				$pwd = isset($_POST['password'])? $this->db->escape_str(trim($_POST['password'])):'';
				
				$result = $this->Admin_model->ChangePassword($pwd);
				if($result =='UPDATE_SUCCESS')	
				{
					$this->session->set_flashdata('sucess', $this->errormsgs->update_success);
					redirect($_SERVER['HTTP_REFERER']);
					exit;
				}
				else
				if($result =='UPDATE_FAILED')	
				{
					$this->session->set_flashdata('error', $this->errormsgs->update_error);
					redirect($_SERVER['HTTP_REFERER']);
					exit;
				}
			
			}


		}			
	}

	public function userProfile()
	{
		$data = array();
				$data_view['data'] = array(
				'title' => ' eyeSmart Digital Payments |Profile ',
				'content' => 'user_profile',
				'header1' => 'Profile',
		         'header2' => 'User Profile',
				);
		$data_view['userdeatils'] = $this->Admin_model->ProfileDeatails();			
		

		if($this->input->post())
		{
			
		
			 #SEVER VALIDATIONS LIBRARY 
			 $this->validationdigi->admin();

			if($this->form_validation->run() == TRUE)
			{
				 #create new array
				$params['first_name'] = isset($_POST['first_name'])? $this->db->escape_str(trim($_POST['first_name'])):'';
				$params['last_name'] = isset($_POST['last_name'])? $this->db->escape_str(trim($_POST['last_name'])):'';
				$params['gender'] = isset($_POST['gender'])? $this->db->escape_str(trim($_POST['gender'])):'';
				$params['mobile'] = isset($_POST['mobile'])? $this->db->escape_str(trim($_POST['mobile'])):'';
				$params['email'] = isset($_POST['email'])? $this->db->escape_str(trim($_POST['email'])):'';
				$params['address'] = isset($_POST['address'])? $this->db->escape_str(trim($_POST['address'])):'';
			
				
				#UPDATE from edit form
				$id = isset($_POST['admin_id'])?$this->db->escape_str(trim($_POST['admin_id'])):'';
				$params['admin_id'] = $this->encryption->decrypt("$id");
				$result  =$this->Admin_model->saveAdmin($params);
				if($result =='INSERT_SUCCESS')
				{
					$this->session->set_flashdata('sucess', $this->errormsgs->add_suceess);
					redirect($_SERVER['HTTP_REFERER']);
					exit;
				}
				else
				if($result =='INSERT_FAILED')	
				{
					$this->session->set_flashdata('error', $this->errormsgs->add_error);
					redirect($_SERVER['HTTP_REFERER']);
					exit;
				}
				else
				if($result =='ALREADY_EXITS_COUNTRY')	
				{
					$this->session->set_flashdata('is_exits', $this->errormsgs->add_isexits);
					redirect($_SERVER['HTTP_REFERER']);
					exit;
				}
				else
				if($result =='UPDATE_SUCCESS')	
				{
					$this->session->set_flashdata('sucess', $this->errormsgs->update_success);
					redirect($_SERVER['HTTP_REFERER']);
					exit;
				}
				else
				if($result =='UPDATE_FAILED')	
				{
					$this->session->set_flashdata('error', $this->errormsgs->update_error);
					redirect($_SERVER['HTTP_REFERER']);
					exit;
				}
					
				
				
			}
			
			

		}
			
	$this->load->view('main_page', $data_view);	

	}

	 public function ChangePasswordChecking()
    {
    	   $pwd = isset($_POST['userp']) ? $_POST['userp'] : ''; 
			if($pwd !='' )
			{
				$res = $this->Admin_model->CheckingPassword($this->db->escape_str($pwd));
				echo $res; 
				exit;
			}
			else
			{
				echo 'No_data';
			}
	
	}


	

	


	
	


}
