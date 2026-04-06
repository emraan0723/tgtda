<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  /*   ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);*/
class Masters extends MX_Controller 
{
	function __construct() 
	{
		
	    parent::__construct();
	    $this->load->library(array('Session_check','session','pagination','form_validation','validationdigi','errormsgs','encryption'));
	     $this->load->model('Masters_model');
	    $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
	   
    }
	public function index()
	{
		
	}

	public function countryStatus()
	{
		if($this->input->post())
		{	
			$params = array();
			$id = isset($_POST['country_id'])?$this->db->escape_str(trim($_POST['country_id'])):'';
			$params['status'] = isset($_POST['status'])?$this->db->escape_str(trim($_POST['status'])):'';
			$params['country_id'] = $this->encryption->decrypt("$id");
			$result =$this->Masters_model->countyStatusUpdate($params);


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
			$params = array();
			$country_id =isset($_POST['country_id'])?$this->db->escape_str(trim($_POST['country_id'])):''; 
			$params['country_id'] = $this->encryption->decrypt("$country_id");
			$data =$this->Masters_model->getCountryList($params);
			$view_data = array();
			if(isset($data['isexists_insert']) && $data['isexists_insert'] > 0)
				$view_data['getdata'] =$data['query']->row_array();

			$this->load->view('edit_country',$view_data);
		}
	}
	

	public function viewCountrys()
	{  
		$params = array();
		$params['draw'] = intval($this->input->post("draw"));
        $params['start'] = intval($this->input->post("start"));
        $params['length'] = intval($this->input->post("length"));
        $params['order'] = $this->input->post("order");
        $search= $this->input->post("search");
        $params['search'] = $search['value'];
        $countryresult  =$this->Masters_model->viewCountrys($params);
        $data = array();
        $i=1;
        foreach($countryresult->result() as $rows)
        {
        	$sno = $params['start'] + $i;
        	if($rows->tc_country_status =='ACTIVE')
        		$html_status ='INACTIVE';
        	else
        		$html_status ='ACTIVE';
        	$country_id = $this->encryption->encrypt($rows->tc_country_ID);
        	$editonclick = "countryEdit('".$country_id."');";
        	$statusclick = "countryStatus('".$i."');";
            $data[]= array(
                '<td>'.$sno.'</td>',
                '<td>'.ucwords(strtolower($rows->tc_country_name)).'</td>',
                '<td>'.$rows->tc_country_code.'</td>',
                '<td>'.ucwords(strtolower($rows->tc_country_status)).'</td>',
				'<td>
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
					<form id="status_frm_id_'.$i++.'" method="post" action="'.base_url().'masters/masters/countryStatus">
					<input type="hidden" value="'.$country_id.'" name="country_id">
					<input type="hidden" value="'.$html_status.'" name="status">
					<a class="dropdown-item" onclick="'.$statusclick.'" href="javascript:void(0)" id="status_id" >'.$html_status.'</a>
					</form>


					</div>
					</div>
				</td>'

                
            );     
        }
        $total_employees = $this->Masters_model->totalCountrys($params);
        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_employees,
            "recordsFiltered" => $total_employees,
            "data" => $data
        );
        echo json_encode($output);

	}


	public function country()
	{
		#COMMAN ARRAY  PASSING VIEW FILE AND HEADERS AND TITLE , CONTENT MEANS -VIEW FILE NAME
		$data_view['data'] = array(
		'title' => ' eyeSmart Digital Payments |Masters ',
		'content' => 'country',
		'header1' => 'Masters',
         'header2' => 'Country',
		);

		if($this->input->post())
		{ 
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
				$result  =$this->Masters_model->saveCountry($params);
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

	public function state()
	{
		$data = array();
				$data_view['data'] = array(
				'title' => ' eyeSmart Digital Payments |Masters ',
				'content' => 'state',
				'header1' => 'Masters',
		         'header2' => 'State',
				);
		$this->load->view('main_page', $data_view);		

	}

	public function district()
	{
		$data = array();
				$data_view['data'] = array(
				'title' => ' eyeSmart Digital Payments |Masters ',
				'content' => 'district',
				'header1' => 'Masters',
		         'header2' => 'District',
				);
		$this->load->view('main_page', $data_view);		

	}
	
	public function city()
	{
		$data = array();
				$data_view['data'] = array(
				'title' => ' eyeSmart Digital Payments |Masters ',
				'content' => 'city',
				'header1' => 'Masters',
		         'header2' => 'City',
				);
		$this->load->view('main_page', $data_view);		

	}
	
	public function currency()
	{
		
		$this->load->view('main_page', $data_view);		

	}
	
	public function tax()
	{
		$data = array();
				$data_view['data'] = array(
				'title' => ' eyeSmart Digital Payments |Masters ',
				'content' => 'tax',
				'header1' => 'Masters',
		         'header2' => 'Tax',
				);
		$this->load->view('main_page', $data_view);		

	}
	

	
}
