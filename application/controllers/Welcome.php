<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function debug()
	{
		$this->load->view('debug');
	}
	public function errorShow($error='') 
	{
            $this->load->view('errorShow');
        
    }
    // set set session function by BV
    function setSession($key, $value) 
    {
        if ($key != '' && $value != '') 
        {
            $_SESSION[$key] = $value;
            //$this->session->set_userdata($key, $value );
        }
    }

     public function sendSMTPMail()
    {

    

        require_once(APPPATH."third_party/phpmailer/PHPMailerAutoload.php");
        $mail = new PHPMailer;      
        $mail->IsSMTP(); // telling the class to use SMTP  
        $mail->SMTPDebug  = 1;  // enables SMTP debug information (for testing)
        //$mail->SMTPSecure = "tls";     

        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = "tls";                 // sets the prefix to the servier

        $mail->Host       = "smtp.office365.com"; // SMTP server
        $mail->Port       = 587;                   // set the SMTP port for the GMAIL server   
        $mail->Username   = "eyesmartsupport@lvpei.net";  // GMAIL username
        $mail->Password   = "EyE@#!SuP@rt";            // GMAIL password
        
        $mail->Subject = "bvvvvvv";
        $mail->MsgHTML("hello");        
        $mail->AddAddress("venkat@bewsoft.net");
       /* if($mail_cc)
        $mail->AddCC($mail_cc);
        if($mail_bcc)
        $mail->AddBCC($mail_bcc);*/
        
        if($mail->Send())
        {
            return 1; 
        }
        else
        {
            return 0; 
        }
    }
    
}
