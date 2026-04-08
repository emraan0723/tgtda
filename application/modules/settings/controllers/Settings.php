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

	

	public function userPrivileges()
	{
		#checking User Privileges
		$this->authorization->userauthorization('userprivileges','view');

		$data = array();
				$data_view['data'] = array(
				'title' => ' TGTDA |Settings ',
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


}
