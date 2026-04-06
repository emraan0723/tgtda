<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Authorization  
{

	public function userauthorization($module,$privileges)
	{
		//echo $module.'---'.$privileges;exit;
		if(isset($_SESSION['userprivileges']) && count($_SESSION['userprivileges']) > 0)
		{
			foreach ($_SESSION['userprivileges'] as $key => $value) 
			{
				if($key ==$module)
				{
					if($value[$privileges] ==0)
					{
						redirect(base_url().'authorization');
						exit;
					}
				}
				
			}
		}
		else
		{
			redirect(base_url().'authorization');
			exit;
		}	

		
	}	
		
		


	
}

