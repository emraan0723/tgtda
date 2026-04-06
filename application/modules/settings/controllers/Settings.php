<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  /*   ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);*/
class Settings extends MX_Controller 
{
	function __construct() 
	{
	    parent::__construct();
	    $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
	    $this->load->model(array('Settings_model','settings/Comman_model'));
	    $this->load->helper(array('form', 'url'));
        $this->session_check->check_session();
        $this->authorization->userauthorization('settings','permissionset'); // checking User Privileges

	}


	#DATABSE ENUM BELOW ARRAY SAME NAME PROVIDED --->tup_permission_module
	public $privilleges_array = array('admin','customer','product','payments','masters','settings','userprivileges','discounts','user');

	public function index()
	{
		
	}

	public function AmcSetup()
	{
		#checking User Privileges
		$this->authorization->userauthorization('settings','adding');
	
		$data = array();
				$data_view['data'] = array(
				'title' => ' eyeSmart Digital Payments |AMC Setup ',
				'content' => 'amc_setup',
				'header1' => 'Settings',
		         'header2' => 'AMC Setup',
				);
		$params = array();
		if($this->input->post())
		{
			
			
			 #checking User Privileges
			 $this->authorization->userauthorization('settings','adding');

			 $this->load->model('cronjobs/Amc_cronjob_model');
			

			#create new array
			$params['customer_id'] = isset($_POST['customer_id'])?(int)(trim($_POST['customer_id'])):0;
			$params['customer_code'] = isset($_POST['customer_code'])? $this->db->escape_str(trim($_POST['customer_code'])):'';
			$params['customer_name'] = isset($_POST['customer_name'])? $this->db->escape_str(trim($_POST['customer_name'])):'';

			$params['amc_pecentage'] = isset($_POST['amc_pecentage'])? $this->db->escape_str(trim($_POST['amc_pecentage'])):'';
			$params['monthly_subscription'] = isset($_POST['monthly_subscription'])? $this->db->escape_str(trim($_POST['monthly_subscription'])):'';

			$params['service_start_date'] = isset($_POST['service_start_date']) ? date('Y-m-d',strtotime($_POST['service_start_date'])):'';

			$params['invoice_email'] = isset($_POST['invoice_email_address'])? $this->db->escape_str(trim($_POST['invoice_email_address'])):'';
			$params['invoice_emailer_name'] = isset($_POST['invoice_emailer_name'])? $this->db->escape_str(trim($_POST['invoice_emailer_name'])):array();


			#YEARLY CALUCULATIONS
			$params['yearly_subscription'] = ($params['monthly_subscription'] * 12) ;

			#AMC CALULATIONS
			$params['amc_amount']  = ($params['yearly_subscription'] * ($params['amc_pecentage'] / 100));

			#NEXT AMC YEAR
			$params['next_amc_year']=date('Y-m-d', strtotime('+1 year', strtotime($params['service_start_date'])));




			
				$result  =$this->Amc_cronjob_model->AmcSetup($params);

				//echo $this->db->last_query(); exit;
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
		$this->load->view('main_page', $data_view);		
				
		
	}
	

	public function userPrivileges()
	{
		#checking User Privileges
		$this->authorization->userauthorization('userprivileges','view');

		$data = array();
				$data_view['data'] = array(
				'title' => ' eyeSmart Digital Payments |Settings ',
				'content' => 'user_privileges',
				'header1' => 'Settings',
		         'header2' => 'User Privileges',
				);
		$this->load->view('main_page', $data_view);		

	}

	


	public function user_privilleges_ajax_list()
	{
		#checking User Privileges
		$this->authorization->userauthorization('userprivileges','view');

		#PERMITIONS 
		$disabled = isset($_SESSION['userprivileges']['userprivileges']['adding']) && $_SESSION['userprivileges']['userprivileges']['adding'] > 0 ? '' : 'disabled';

		$list = $this->Settings_model->get_datatables();
		//echo $this->db->last_query();
		$data = array();
		$no = $_POST['start'];
		$i=1;
		foreach ($this->privilleges_array as $value) 
		{	

			foreach ($list as $key => $admin) 
			{
				$adminAccess= $this->Settings_model->getAccessList($admin->admin_id,$value);

				$addingchecked = isset($adminAccess['tup_adding']) && $adminAccess['tup_adding']==1 ? 'checked' :'';
				$editchecked = isset($adminAccess['tup_edit']) && $adminAccess['tup_edit']==1 ? 'checked' :'';
				$viewchecked = isset($adminAccess['tup_view']) && $adminAccess['tup_view']==1 ? 'checked' :'';
				$no_acceschecked = isset($adminAccess['tup_no_access']) && $adminAccess['tup_no_access']==1 ? 'checked' :'';
				$full_accesschecked = isset($adminAccess['tup_full_access']) && $adminAccess['tup_full_access']==1 ? 'checked' :'';

					$adding = 'adding';
					$edit = 'edit';
					$view = 'view';
					$no_access = 'no_access';
					$full_access = 'full_access';
					$admin_id =$admin->admin_id; 
					$no++;
					$row = array();
					$row[] = $no;
					$row[] = ucwords($admin->admin_name);
					$row[] = ucwords($value);
					$row[] = "<div class='col-md-3'>
                                                <input type='checkbox' $disabled  $addingchecked onclick='PrivilegesStatus(\"$value\",\"$admin_id\",\"$i\",\"$adding\");' value='1'  id='".$value.$adding.$i."' class='material-inputs filled-in chk-col-light-green ".$value.'_'.$admin_id."'>
                                                <label for='".$value.$adding.$i."'></label>
                                                </div>";
					$row[] = "<div class='col-md-3'>
                                                <input type='checkbox' $disabled $editchecked onclick='PrivilegesStatus(\"$value\",\"$admin_id\",\"$i\",\"$edit\");' value='1'  id='".$value.$edit.$i."' class='material-inputs filled-in chk-col-light-green ".$value.'_'.$admin_id."'>
                                                <label for='".$value.$edit.$i."'></label>
                                                </div>";
					$row[] = "<div class='col-md-3'>
                                                <input type='checkbox' $disabled $viewchecked onclick='PrivilegesStatus(\"$value\",\"$admin_id\",\"$i\",\"$view\");' value='1'  id='".$value.$view.$i."' class='material-inputs filled-in chk-col-light-green ".$value.'_'.$admin_id."'>
                                                <label for='".$value.$view.$i."'></label>
                                                </div>";
					$row[] = "<div class='col-md-3'>
                                                <input type='checkbox' $disabled $no_acceschecked onclick='PrivilegesStatus(\"$value\",\"$admin_id\",\"$i\",\"$no_access\");' value='1'  id='".$value.$no_access.$i."' class='material-inputs filled-in chk-col-light-green ".$value.'_'.$admin_id."'>
                                                <label for='".$value.$no_access.$i."'></label>
                                                </div>";
					$row[] = "<div class='col-md-3'>
                                                <input type='checkbox' $disabled $full_accesschecked onclick='PrivilegesStatus(\"$value\",\"$admin_id\",\"$i\",\"$full_access\");' value='1'  id='".$value.$full_access.$i."' class='material-inputs filled-in chk-col-light-green ".$value.'_'.$admin_id."'>
                                                <label for='".$value.$full_access.$i."'></label>
                                                </div>";


					$data[] = $row;
					
			    $i++;
			}

		}
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Settings_model->count_all(),
						"recordsFiltered" => $this->Settings_model->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function userPrivilegesAccess()
	{
		if($this->input->post())
		{
			#checking User Privileges
		    $this->authorization->userauthorization('userprivileges','adding');

			$params['acessmodule'] = isset($_POST['acessmodule'])?$this->db->escape_str(trim($_POST['acessmodule'])):'';
			$params['admin_id'] = isset($_POST['id'])?(int)($_POST['id']):0;
			$params['privilege'] = isset($_POST['privilege'])?$this->db->escape_str(trim($_POST['privilege'])):'';
			$params['privilege_value'] = isset($_POST['values'])?(int)($_POST['values']):0; 
			$params['fullacessremove'] = isset($_POST['fullacessremove'])?(int)($_POST['fullacessremove']):0; 
			$res = $this->Settings_model->userPrivilegesAccess($params);
			//echo $this->db->last_query(); exit;
			echo json_encode($res);
		}

	}


	public function viewAmcSetup()
	{
		#checking User Privileges
		$this->authorization->userauthorization('userprivileges','view');
		$this->load->model('cronjobs/Amc_cronjob_model');
		$list = $this->Amc_cronjob_model->get_datatables();
		//echo $this->db->last_query();exit;
		$data = array();
		$no = $_POST['start'];
		$i=1;
		foreach ($list as $amcsetup) 
		{

			/*print"<pre>";
			print_r($amcsetup);
			exit;*/
			if($amcsetup->tas_amc_status =='ACTIVE')
        		$html_status ='INACTIVE';
        	else
        		$html_status ='ACTIVE';

        	$jsondata = json_encode($amcsetup,true);
        	
        	$amcsetup_id = $this->encryption->encrypt($amcsetup->tas_amc_id);
        	$editonclick = "amcsetupEdit('".$amcsetup_id."');";
        	$statusclick = "amcsetupStatus('".$i."');";
        	

        	$customer_id = $this->encryption->encrypt($amcsetup->tas_amc_customer_id);
        	
			$no++;
			$row = array();
			/*$row[] = $no;*/
			$row[] = ucwords($amcsetup->tas_amc_customer_code);
			$row[] = date('d-m-Y',strtotime($amcsetup->tas_amc_service_date));
			$row[] = date('d-m-Y',strtotime($amcsetup->tas_amc_next_due_date));
			if($amcsetup->tas_amc_paid_date !='0000-00-00')
				$row[] = date('d-m-Y',strtotime($amcsetup->tas_amc_paid_date));
			else
				$row[] ='--';
			$row[] = $amcsetup->tas_amc_percentage."%";
			$row[] = $amcsetup->tas_amc_monthly_subscription;
			$row[] = $amcsetup->tas_amc_yearly_subscription;
			$row[] = $amcsetup->tas_amc_amount;
			//$row[] = $amcsetup->tas_amc_final_amount;
			$row[] = $amcsetup->tas_amc_status;
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

					<a class="dropdown-item"  onclick="'.$editonclick.'"  href="javascript:void(0)"><i
					class="ti-pencil-alt"></i> Edit</a>
					<form id="status_frm_id_'.$i.'" method="post" action="'.base_url().'settings/Settings/amcSetupStatus">
					<input type="hidden" name="'.$this->security->get_csrf_token_name().'" value="'.$this->security->get_csrf_hash().'">
					<input type="hidden" value="'.$amcsetup_id.'" name="amcsetup_id">
					<input type="hidden" value="'.$html_status.'" name="status">
					<a class="dropdown-item" onclick="'.$statusclick.'" href="javascript:void(0)" id="status_id" >'.$html_status.'</a>
					</form>



					</div>
					</div>';

				$html = '<p align="left">';	
				$html .="<strong>Customer Name : </strong>  " ;
				$html .=ucwords($amcsetup->tas_amc_customer_name);
				$html .="<br/><strong>Total GST  : </strong>  " ;
				$html .=$amcsetup->tas_amc_total_taxt_pecentage."%";
				$html .="<br/><strong>GST Amount  : </strong>  " ;
				$html .=$amcsetup->tas_amc_total_tax_amount;
				$html .="<br/><strong>Less TDS Percentage  : </strong>  " ;
				$html .=$amcsetup->tas_amc_less_tds_percentage."%";
				$html .="<br/><strong>Less TDS Amount</strong>  " ;
				$html .=$amcsetup->tas_amc_less_tds_amount;
				$html .="<br/><strong>Total Amount  (With GST & TDS) : </strong>  " ;
				$html .=$amcsetup->tas_amc_final_amount;
				$html .="<br/><strong>AMC Invoice email Adrdress: </strong>  " ;
				$html .=$amcsetup->tas_amc_invoice_mail;
				$html .="<br/><strong>AMC Invoice Emailer Name: </strong>  " ;
				$html .=$amcsetup->tas_amc_invoice_mailer_name;
				$html .="</p>";
                                            
			$row['mouseover'] =$html;
			$data[] = $row;

			$i++;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Amc_cronjob_model->count_all(),
						"recordsFiltered" => $this->Amc_cronjob_model->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function edit_amcSetUp()
	{
		if($this->input->post())
		{	

			#checking User Privileges
		     $this->authorization->userauthorization('userprivileges','adding');
		     $this->load->model('cronjobs/Amc_cronjob_model');
			$params = array();
			$view_data = array();
			$admin_id =isset($_POST['amc_setup_id'])?$this->db->escape_str(trim($_POST['amc_setup_id'])):''; 
			$amc_setup_id = $this->encryption->decrypt("$admin_id");
			$data =$this->Amc_cronjob_model->getAmcsetupData($amc_setup_id);
			
			echo json_encode($data,TRUE);	
		}
	}


	public function amcSetupStatus()
	{

		if($this->input->post())
		{	
			#checking User Privileges
		    $this->authorization->userauthorization('userprivileges','adding');
		    $this->load->model('cronjobs/Amc_cronjob_model');
			$params = array();
			$id = isset($_POST['amcsetup_id'])?$this->db->escape_str(trim($_POST['amcsetup_id'])):'';
			$params['status'] = isset($_POST['status'])?$this->db->escape_str(trim($_POST['status'])):'';
			$params['amcsetup_id'] = $this->encryption->decrypt("$id");
			$result =$this->Amc_cronjob_model->amcSetupStatusUpdate($params);

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


	#manual_update create
	public function manual_update()
	{

		#checking User Privileges
		$this->authorization->userauthorization('userprivileges','adding');
		$data = array();
				$data_view['data'] = array(
				'title' => ' eyeSmart Digital Payments | Manual Update ',
				'content' => 'manual_payment_update',
				'header1' => 'Settings',
		         'header2' => 'Manual Update',
				);
		$params = array();
		/*print"<pre>";
		print_r($_POST);
		exit;*/
		if($this->input->post())
		{

			/*print"<pre>";
			print_r($_POST);*/
			 #checking User Privileges
			 $this->authorization->userauthorization('userprivileges','adding');
				 #create new array
			$params['customer_id'] = isset($_POST['customer_id']) ? (int)(trim($_POST['customer_id'])):0;
			$params['customer_code'] = isset($_POST['customer_code']) ? $this->db->escape_str(trim($_POST['customer_code'])) :'';
			$params['customer_name'] = isset($_POST['customer_name']) ? $this->db->escape_str(trim($_POST['customer_name'])) :'';
			$params['invoice_date'] = isset($_POST['invoice_date']) ? trim($_POST['invoice_date']) :'';


			

			if($params['customer_id'] > 0 && $params['customer_code'] !='' && $params['invoice_date'] !='')
			{
				$result  =$this->Settings_model->ManualUpadatePayments($params);
			}
		
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
		$this->load->view('main_page', $data_view);		
				
		

	}	




	

	

	

}
