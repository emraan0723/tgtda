<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  /*   ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);*/
class State extends MX_Controller 
{
	function __construct() 
	{
		
	    parent::__construct();
	    $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
	     $this->load->model(array('State_model','settings/Comman_model'));
	    $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
        $this->authorization->userauthorization('masters','permissionset'); // checking User Privileges
    }
	public function index()
	{
		
	}
	public function stateStatus()
	{
		if($this->input->post())
		{	
			#checking User Privileges
		    $this->authorization->userauthorization('masters','edit');

			$params = array();
			$id = isset($_POST['state_id'])?$this->db->escape_str(trim($_POST['state_id'])):'';
			$params['status'] = isset($_POST['status'])?$this->db->escape_str(trim($_POST['status'])):'';
			$params['state_id'] = $this->encryption->decrypt("$id");
			$result =$this->State_model->stateStatusUpdate($params);
			//echo $this->db->last_query(); exit;


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
	public function editState()
	{
		if($this->input->post())
		{	
			#checking User Privileges
		    $this->authorization->userauthorization('masters','edit');

			$params = array();
			$view_data = array();
			#GET COUNTRY LIST
			$res=$this->Comman_model->getCountryList($params);
			if(isset($res['query']))
			{
				$view_data['country_list'] =$res['query']->result_array();
			}

			$state_id =isset($_POST['state_id'])?$this->db->escape_str(trim($_POST['state_id'])):''; 
			$params['state_id'] = $this->encryption->decrypt("$state_id");
			$data =$this->State_model->getStateList($params);
			if(isset($data['isexists_insert']) && $data['isexists_insert'] > 0)
				$view_data['getdata'] =$data['query']->row_array();

			$this->load->view('edit_state',$view_data);
		}
	}
	public function state()
	{
		#checking User Privileges
		$this->authorization->userauthorization('masters','view');

		#COMMAN ARRAY  PASSING VIEW FILE AND HEADERS AND TITLE , CONTENT MEANS -VIEW FILE NAME
		$params = array();
		$data_view['data'] = array(
		'title' => ' TGTDA |Masters ',
		'content' => 'state',
		'header1' => 'Masters',
		 'header2' => 'State',
		);
		#GET COUNTRY LIST
		$res=$this->Comman_model->getCountryList($params);
		if(isset($res['query']))
		{
			$data_view['country_list'] =$res['query']->result_array();
		}
		if($this->input->post())
		{
			#checking User Privileges
		    $this->authorization->userauthorization('masters','adding');

			#SEVER VALIDATIONS LIBRARY 
			$this->validationdigi->state();
			#VALIDTIONS SCUSSESS
			if($this->form_validation->run() == TRUE)
			{
				 #create new array
				$params['state_name'] = isset($_POST['state_name'])?$this->db->escape_str(trim($_POST['state_name'])):'';
				$params['country_id'] = isset($_POST['country_id'])? (int)$_POST['country_id']:'';
				$params['state_code'] = isset($_POST['state_code'])?$this->db->escape_str(trim($_POST['state_code'])):'';
				#UPDATE from edit country
				$id = isset($_POST['state_id'])?$this->db->escape_str(trim($_POST['state_id'])):'';
				$params['state_id'] = $this->encryption->decrypt("$id");
				$result  =$this->State_model->saveState($params);
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
	public function ajax_list()
	{
		#checking User Privileges
		$this->authorization->userauthorization('masters','view');

		$list = $this->State_model->get_datatables();
		//echo $this->db->last_query();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $state) 
		{
			if($state->status =='ACTIVE')
        		$html_status ='INACTIVE';
        	else
        		$html_status ='ACTIVE';
        	
        	$state_id = $this->encryption->encrypt($state->state_id);
        	$editonclick = "stateEdit('".$state_id."');";
        	$statusclick = "stateStatus('".$i."');";

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $state->country_name;
			$row[] = $state->state_name;
			$row[] = $state->state_code;
			$row[] = $state->status;
			$row[] ='
					<div class="btn-group">
					<button type="button" class="btn btn-dark dropdown-toggle"
					data-toggle="dropdown" aria-haspopup="true"
					aria-expanded="false">
					<i class="ti-settings"></i>
					</button>
					<div class="dropdown-menu animated slideInUp"
					x-placement="bottom-start"
					style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 35px, 0px);">

					<a class="dropdown-item" onclick="'.$editonclick.'"  href="javascript:void(0)"><i
					class="ti-pencil-alt"></i> Edit</a>
					<form id="status_frm_id_'.$i++.'" method="post" action="'.base_url().'masters/state/stateStatus">
					<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
					<input type="hidden" value="'.$state_id.'" name="state_id">
					<input type="hidden" value="'.$html_status.'" name="status">
					<a class="dropdown-item" onclick="'.$statusclick.'" href="javascript:void(0)" id="status_id" >'.$html_status.'</a>
					</form>


					</div>
					</div>';
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->State_model->count_all(),
						"recordsFiltered" => $this->State_model->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	
	

	
}
