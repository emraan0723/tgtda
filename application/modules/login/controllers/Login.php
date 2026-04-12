<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library(array('session','Auth_verify'));
		$this->load->model('Login_model');
		date_default_timezone_set("Asia/Calcutta");
	}

	public function index()
	{
			if($this->session->userdata('user_id') > 0)
			{
				redirect(base_url().'admin/dashboard');
			}
			else
			{
				$this->load->view("login_view");
			}

	        if($this->input->post())
	        {
				if(isset($_POST['username']) && isset($_POST['pass']))
				{
					$pwd = isset($_POST['pass']) ? $this->db->escape_str(trim($_POST['pass'])) : '';
					$this->auth_verify->username = $this->input->post('username');
				    $this->auth_verify->password = $this->input->post('pass');

				    //echo  password_hash($pwd, PASSWORD_BCRYPT); exit;

						if($this->auth_verify->login_user())
						{

							redirect(base_url().'admin/dashboard');
						}
						else
						{
							$this->session->set_flashdata('error_msg', 'Login failed');
						}


				}

	         }


    }


    public function UserLogin()
    {
        if($this->session->userdata('user_id') > 0)
        {
            redirect(base_url().'user_dashboard');
        }
        else
        {
            $this->load->view("user_login_view");
        }

        if($this->input->post())
        {

            if(isset($_POST['username']) && isset($_POST['pass']))
            {
                $pwd = isset($_POST['pass']) ? $this->db->escape_str(trim($_POST['pass'])) : '';
                $this->auth_verify->username = $this->input->post('username');
                $this->auth_verify->password = $this->input->post('pass');

                //echo  password_hash($pwd, PASSWORD_BCRYPT); exit;

                if($this->auth_verify->ulogin_user())
                {
                    redirect(base_url().'user_dashboard');
                }
                else
                {
                    $this->session->set_flashdata('error_msg', 'Login failed');
                }


            }

        }


    }


	public function logout()
	{
		$array_items = array('role' => '', 'user_id' => '','user_status' => '','admin_name' => '','b_developer_tools'=>'','admin_mail'=>'','admin_mobile'=>'','admn_access' => '');
		$this->session->unset_userdata($array_items);
		$this->session->sess_destroy();
		redirect(base_url(''));
		exit;
	}

    public function Userlogout()
    {
        $array_items = array('role' => '', 'uuser_id' => '','uuser_status' => '','user_name' => '','b_developer_tools'=>'','admin_mail'=>'','user_mobile'=>'','user_access' => '','uuser_name'=>'','uuser_id'=>'','uuser_status'=>'','uuser_mobile'=>'','uuser_address'=>'','uuser_selfie'=>'');
        $this->session->unset_userdata($array_items);
        $this->session->sess_destroy();
        redirect(base_url().'user_login');
        exit;
    }





}

    ?>
