<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Session_check
{
	public function check_session()
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_id') > 0 || $CI->session->userdata('uuser_id') > 0)
		{
			return true;
		}
		else
		{
			redirect(base_url(''));
			exit; 
		}			
	}
}