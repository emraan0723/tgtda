<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /* ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);*/
class Admin extends MX_Controller 
{
	function __construct() 
	{
	    parent::__construct();

	    $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
	    $this->load->model(array('Admin_model'));
	    $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
        $this->authorization->userauthorization('admin','permissionset'); // checking User Privileges
	    $this->perPage =25;

	    
	}

	public function index()
	{
		
	}

	

	public function resetPassword()
	{

		if($this->input->post())
		{	

			#checking User Privileges
		    $this->authorization->userauthorization('admin','edit');

			$params = array();
			$id = isset($_POST['admin_id'])?$this->db->escape_str(trim($_POST['admin_id'])):'';
			$params['status'] = isset($_POST['status'])?$this->db->escape_str(trim($_POST['status'])):'';
			$params['admin_id'] = $this->encryption->decrypt("$id");
			$result =$this->Admin_model->Resetpassword($params);

			//echo $result; exit;
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
			else
			{
				$this->session->set_flashdata('error', $this->errormsgs->update_error);
				redirect($_SERVER['HTTP_REFERER']);
				exit;
			}

			
		}

	}

	public function adminStatus()
	{

		if($this->input->post())
		{	
			#checking User Privileges
		    $this->authorization->userauthorization('admin','edit');
			$params = array();
			$id = isset($_POST['admin_id'])?$this->db->escape_str(trim($_POST['admin_id'])):'';
			$params['status'] = isset($_POST['status'])?$this->db->escape_str(trim($_POST['status'])):'';
			$params['admin_id'] = $this->encryption->decrypt("$id");
			$result =$this->Admin_model->adminStatusUpdate($params);

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
			else
			{
				$this->session->set_flashdata('error', $this->errormsgs->update_error);
				redirect($_SERVER['HTTP_REFERER']);
				exit;
			}
		}
	}
	#Admin create/edit
	public function CreateAdmins()
	{
		#checking User Privileges
		$this->authorization->userauthorization('admin','adding');

		$data = array();
				$data_view['data'] = array(
				'title' => 'TGTDA |Admin ',
				'content' => 'admin',
				'header1' => 'Admin',
		         'header2' => 'Add Admin',
				);

		if($this->input->post())
		{
			 #checking User Privileges
			 $this->authorization->userauthorization('admin','adding');

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
				$params['username'] = isset($_POST['username'])? $this->db->escape_str(trim($_POST['username'])):'';
				$params['password'] = isset($_POST['password'])? $this->db->escape_str(trim($_POST['password'])):'';
				
				#UPDATE from edit form
				$id = isset($_POST['admin_id'])?$this->db->escape_str(trim($_POST['admin_id'])):0;
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
				if($result =='ALREADY_EXITS')	
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
			else
			if($this->form_validation->run() == false)	
			{	
				$this->session->set_flashdata('error', $this->errormsgs->serversideerror);
				redirect($_SERVER['HTTP_REFERER']);
				exit;
					
					
			}	
			

		}
			


		$this->load->view('main_page', $data_view);		
				
		

	}

	public function viewAdmin()
	{
		 #checking User Privileges
		 $this->authorization->userauthorization('admin','view');
		 $data = array();
				$data_view['data'] = array(
				'title' => 'TGTDA |Admin ',
				'content' => 'admin_view',
				'header1' => 'Admin',
		         'header2' => 'View Admins',
				);
		$this->load->view('main_page', $data_view);		

	}

	public function edit_admin()
	{
		if($this->input->post())
		{	
			#checking User Privileges
		    $this->authorization->userauthorization('admin','edit');

			$params = array();
			$view_data = array();
			$admin_id =isset($_POST['admin_id'])?$this->db->escape_str(trim($_POST['admin_id'])):''; 
			$params['admin_id'] = $this->encryption->decrypt("$admin_id");
			$data =$this->Admin_model->getAdminList($params);
			if(isset($data['isexists_insert']) && $data['isexists_insert'] > 0)
				$view_data['getdata'] =$data['query']->row_array();

			$this->load->view('edit_admin',$view_data);
		}

		
	}


	public function ajax_list()
	{
		#checking User Privileges
		$this->authorization->userauthorization('admin','view');
		$list = $this->Admin_model->get_datatables();
		//echo $this->db->last_query();exit;
		$data = array();
		$no = $_POST['start'];
		$i=1;
		foreach ($list as $admin) 
		{
			if($admin->tu_status =='ACTIVE')
        		$html_status ='INACTIVE';
        	else
        		$html_status ='ACTIVE';
        	
        	$admin_id = $this->encryption->encrypt($admin->admin_id);
        	$editonclick = "adminEdit('".$admin_id."');";
        	$statusclick = "adminStatus('".$i."');";
        	$resetclick = "ResetPassword('".$i."');";

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = ucwords($admin->admin_name);
			$row[] = ucwords($admin->tu_gender);
			$row[] = $admin->tu_mobile;
			$row[] = $admin->tu_status;
			$row[] ='
					<div class="btn-group">
					<button type="button" class="btn btn-dark dropdown-toggle"
					data-toggle="dropdown" aria-haspopup="true"
					aria-expanded="false">
					<i class="ti-settings"></i>
					</button>
					<div class="dropdown-menu animated slideInUp"
					x-placement="bottom-start"
					style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);margin-left: -60px;">

					<a class="dropdown-item" onclick="'.$editonclick.'"  href="javascript:void(0)"><i
					class="ti-pencil-alt"></i> Edit</a>
					<form id="status_frm_id_'.$i.'" method="post" action="'.base_url().'admin/admin/adminStatus">
					<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
					<input type="hidden" value="'.$admin_id.'" name="admin_id">
					<input type="hidden" value="'.$html_status.'" name="status">
					<a class="dropdown-item" onclick="'.$statusclick.'" href="javascript:void(0)" id="status_id" >'.$html_status.'</a>
					</form>

					<form id="reset_password_from_'.$i++.'" method="post" action="'.base_url().'admin/admin/resetPassword">
					<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
					<input type="hidden" value="'.$admin_id.'" name="admin_id">
					<input type="hidden" value="'.$html_status.'" name="status">
					<a class="dropdown-item" onclick="'.$resetclick.'" href="javascript:void(0)" id="reset_pwd_id" >Reset Password</a>
					</form>



					</div>
					</div>';

				$html = '<p align="left">';	
                if(isset($admin->tupp_source) && $admin->tupp_source  ='admin' && isset($admin->tupp_filename) && $admin->tupp_filename !='')
                {
                    $imagehtml = base_url().'profile_images/admin/'.$admin->tupp_filename;
                    $html .="<strong><img src='".$imagehtml."' alt='user' width='30' class='profile-pic rounded-circle'/></strong><br/>" ;

                }

					
				$address = $admin->tu_address;	
				$address = str_replace('\\r\\n','<br/>', $address);
				$address = str_replace('\r\n','<br/>', $address);
				$address = str_replace('\\R\\N','<br/>', $address);
				$address = str_replace('\R\N','<br/>', $address);
				$address = str_replace('/\r\\n','<br/>', $address);
				$address = str_replace('/r/n','<br/>', $address);
				$address = str_replace('/\R\\N','<br/>', $address);
				$address = str_replace('/R/N','<br/>', $address);

				$address = stripslashes($address);
				

				$html .="<strong>Username  : </strong>  " ;
				$html .=ucwords($admin->tu_username);
				$html .="<br/><strong>E-mail  : </strong>  " ;
				$html .=$admin->tu_email;
				$html .="<br/><strong>  Address: </strong>  " ;
				$html .=$address;
				$html .="</p>";
                                            
			$row['mouseover'] =$html;
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Admin_model->count_all(),
						"recordsFiltered" => $this->Admin_model->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}



	
	


}
