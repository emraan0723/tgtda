<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  /*   ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);*/
class Country extends MX_Controller 
{
	function __construct() 
	{
		//sleep(10);
	    parent::__construct();
	    $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
	     $this->load->model('Country_model');
	    $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
        $this->authorization->userauthorization('masters','permissionset'); // checking User Privileges
    }
	public function index()
	{
		
	}
	public function countryStatus()
	{

		if($this->input->post())
		{	
			#checking User Privileges
			$this->authorization->userauthorization('masters','edit');
			$params = array();
			$id = isset($_POST['country_id'])?$this->db->escape_str(trim($_POST['country_id'])):'';
			$params['status'] = isset($_POST['status'])?$this->db->escape_str(trim($_POST['status'])):'';
			$params['country_id'] = $this->encryption->decrypt("$id");
			$result =$this->Country_model->countyStatusUpdate($params);


			if($result =='COUNTRY_UPDATE_SUCCESS')	
			{
				$this->session->set_flashdata('sucess', $this->errormsgs->update_success);
				redirect($_SERVER['HTTP_REFERER']);
				exit;
			}
			else
			if($result =='COUNTRY_UPDATE_FAILED')	
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
	public function editCountry()
	{
		if($this->input->post())
		{	
			#checking User Privileges
			$this->authorization->userauthorization('masters','edit');

			$params = array();
			$country_id =isset($_POST['country_id'])?$this->db->escape_str(trim($_POST['country_id'])):''; 
			$params['country_id'] = $this->encryption->decrypt("$country_id");
			$data =$this->Country_model->getCountryList($params);
			$view_data = array();
			if(isset($data['isexists_insert']) && $data['isexists_insert'] > 0)
				$view_data['getdata'] =$data['query']->row_array();

			$this->load->view('edit_country',$view_data);
		}
	}
	public function country()
	{
		#checking User Privileges
		$this->authorization->userauthorization('masters','view');
		#COMMAN ARRAY  PASSING VIEW FILE AND HEADERS AND TITLE , CONTENT MEANS -VIEW FILE NAME
		$data_view['data'] = array(
		'title' => 'TGTDA|Masters ',
		'content' => 'country',
		'header1' => 'Masters',
         'header2' => 'Country',
		);
		#GET COUNTRIES LIST
		$countries = $this->Country_model->get_list_countries();
		if($this->input->post())
		{ 
			#checking User Privileges
			$this->authorization->userauthorization('masters','adding');

			#SEVER VALIDATIONS LIBRARY 
			$this->validationdigi->country();
			#VALIDTIONS SCUSSESS
			if($this->form_validation->run() == TRUE)
			{
				$params = array(); #create new array
				$params['country_name'] = isset($_POST['country_name'])?$this->db->escape_str(trim($_POST['country_name'])):'';
				$params['country_code'] = isset($_POST['country_code'])?$this->db->escape_str(trim($_POST['country_code'])):'';
				#UPDATE from edit country
				$id = isset($_POST['country_id'])?$this->db->escape_str(trim($_POST['country_id'])):'';
				$params['country_id'] = $this->encryption->decrypt("$id");
				$result  =$this->Country_model->saveCountry($params);
				if($result =='COUNTRY_INSERT_SUCCESS')
				{
					$this->session->set_flashdata('sucess', $this->errormsgs->add_suceess);
					redirect($_SERVER['HTTP_REFERER']);
					exit;
				}
				else
				if($result =='COUNTRY_INSERT_FAILED')	
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
				if($result =='COUNTRY_UPDATE_SUCCESS')	
				{
					$this->session->set_flashdata('sucess', $this->errormsgs->update_success);
					redirect($_SERVER['HTTP_REFERER']);
					exit;
				}
				else
				if($result =='COUNTRY_UPDATE_FAILED')	
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
		
		$list = $this->Country_model->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $country) {

			if($country->tc_country_status =='ACTIVE')
        		$html_status ='INACTIVE';
        	else
        		$html_status ='ACTIVE';
        	
        	$country_id = $this->encryption->encrypt("$country->tc_country_ID");
        	//echo $country_id;exit;
        	$editonclick = "countryEdit('".$country_id."');";
        	$statusclick = "countryStatus('".$i."');";

			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $country->tc_country_name;
			$row[] = $country->tc_country_code;
			$row[] = $country->tc_country_status;
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
					<form id="status_frm_id_'.$i++.'" method="post" action="'.base_url().'masters/country/countryStatus">
					<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
					<input type="hidden" value="'.$country_id.'" name="country_id">
					<input type="hidden" value="'.$html_status.'" name="status">
					<a class="dropdown-item" onclick="'.$statusclick.'" href="javascript:void(0)" id="status_id" >'.$html_status.'</a>
					</form>


					</div>
					</div>';
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Country_model->count_all(),
						"recordsFiltered" => $this->Country_model->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	
	

	
}
