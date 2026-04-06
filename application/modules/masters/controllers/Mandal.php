<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  /*   ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);*/
class Mandal extends MX_Controller
{
	function __construct() 
	{
		
	    parent::__construct();
	    $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
	     $this->load->model(array('Mandal_model','settings/Comman_model'));
	    $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
        $this->authorization->userauthorization('masters','permissionset'); // checking User Privileges
    }
	public function index()
	{
		
	}
	public function mandalStatus()
	{
		if($this->input->post())
		{	
			#checking User Privileges
			$this->authorization->userauthorization('masters','edit');
			$params = array();
			$id = isset($_POST['mandal_id'])?$this->db->escape_str(trim($_POST['mandal_id'])):'';
			$params['status'] = isset($_POST['status'])?$this->db->escape_str(trim($_POST['status'])):'';
			$params['mandal_id'] = $this->encryption->decrypt("$id");
			$result =$this->Mandal_model->mandalStatusUpdate($params);
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
	public function editMandal()
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

			$mandal_id =isset($_POST['mandal_id'])?$this->db->escape_str(trim($_POST['mandal_id'])):'';
			$params['mandal_id'] = $this->encryption->decrypt("$mandal_id");
			$data =$this->Mandal_model->getMandalList($params);
			if(isset($data['isexists_insert']) && $data['isexists_insert'] > 0)
				$view_data['getdata'] =$data['query']->row_array();

			if(isset($view_data['getdata']['country_id']) && ($view_data['getdata']['country_id']) > 0)
			{ 	
			    $country =array();
			    $country['country_id'] = $view_data['getdata']['country_id'];
				$res = $this->Comman_model->getStateList($country);
				if(isset($res['query']))
				{
					$view_data['state_list'] =$res['query']->result_array();
				}
			}

			if(isset($view_data['getdata']['state_id']) && ($view_data['getdata']['state_id']) > 0)
			{ 	
			    $country =array();
			    $country['state_id'] = $view_data['getdata']['state_id'];
				$res = $this->Comman_model->getDistrictList($country);
				if(isset($res['query']))
				{
					$view_data['district_list'] =$res['query']->result_array();
				}
			}


		
			$this->load->view('edit_mandal',$view_data);
		}
	}
	public function mandal()
	{
		#checking User Privileges
		$this->authorization->userauthorization('masters','view');

		#COMMAN ARRAY  PASSING VIEW FILE AND HEADERS AND TITLE , CONTENT MEANS -VIEW FILE NAME
		$data_view['data'] = array(
		'title' => ' eyeSmart Digital Payments |Masters ',
		'content' => 'mandal',
		'header1' => 'Masters',
		 'header2' => 'Mandal',
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
			$this->validationdigi->district();
			#VALIDTIONS SCUSSESS
			if($this->form_validation->run() == TRUE)
			{
			
				 #create new array
				$params['country_id'] = isset($_POST['country_id'])? (int)$_POST['country_id']:'';
				$params['state_id'] = isset($_POST['state_id'])? (int)$_POST['state_id']:'';
				$params['district_id'] = isset($_POST['district_id'])? (int)$_POST['district_id']:'';
				$params['mandal_name'] = isset($_POST['mandal_name'])?$this->db->escape_str(trim($_POST['mandal_name'])):'';
				
				#UPDATE from edit country
				$id = isset($_POST['mandal_id'])?$this->db->escape_str(trim($_POST['mandal_id'])):'';
				$params['mandal_id'] = $this->encryption->decrypt("$id");
				$result  =$this->Mandal_model->saveMandal($params);
				//echo $this->db->last_query();exit;
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
		
		$list = $this->Mandal_model->get_datatables();
		//echo $this->db->last_query();exit;
		$data = array();
		$no = $_POST['start'];
		$i=1;
		foreach ($list as $mandal)
		{

			if($mandal->status =='ACTIVE')
        		$html_status ='INACTIVE';
        	else
        		$html_status ='ACTIVE';
        	
        	$mandal_id = $this->encryption->encrypt($mandal->mandal_id);
        	$editonclick = "mandalEdit('".$mandal_id."');";
        	$statusclick = "mandalStatus('".$i."');";

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $mandal->country_name;
			$row[] = $mandal->state_name;
			$row[] = $mandal->district_name;
			$row[] = $mandal->mandal_name;
			$row[] = $mandal->status;
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
					<form id="status_frm_id_'.$i.'" method="post" action="'.base_url().'masters/mandal/mandalStatus">
					<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
					<input type="hidden" value="'.$mandal_id.'" name="mandal_id">
					<input type="hidden" value="'.$html_status.'" name="status">
					<a class="dropdown-item" onclick="'.$statusclick.'" href="javascript:void(0)" id="status_id" >'.$html_status.'</a>
					</form>
					</div>
					</div>';
			$row['bv'] =$i;			
			$data[] = $row;
			$i++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Mandal_model->count_all(),
						"recordsFiltered" => $this->Mandal_model->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	
	

	
}
