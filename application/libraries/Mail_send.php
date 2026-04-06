<?php 

class Mail_Send
{
	// function for sending email 

	

	public function sendChangePasswordEmail($pwd,$admin_name,$admin_mail)
	{


		if($pwd !='' && $admin_name !='' && $admin_mail !='')
		{
				$CI =& get_instance();
				$CI->load->library('Phpmailer');
				$mail = new PHPMailer();
				$changed_date = date('m/d/Y');

		        $htmlContent = '<p>Dear '.$admin_name.',</p>';
				$htmlContent .='<p>';
				$htmlContent .='Your password has been changed successfully.</p>';
				//$htmlContent .='<p><b>New password :</b> '.$pwd.'</p>';
				$htmlContent .= '<p>Thank you <br/>';
				$htmlContent .= '<p>Team eyeSmart EMR</p>';			
				$body = $htmlContent;

				
				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->Host       = "220.227.249.163"; // SMTP server
				//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
				$mail->Host       = "220.227.249.163"; // SMTP server
				$mail->Port       = 25;                   // set the SMTP port for the GMAIL server
				//$mail->Username   = "emrreports@lvpei.org";  // GMAIL username
				//$mail->Password   = "emrreports";            // GMAIL password

				$mail->Subject = 'Change Password';

				$mail->MsgHTML($htmlContent);

				$mail->AddAddress($admin_mail);
				//$mail->AddCC($mail_cc);

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

	public function sendRegistartionDataEmail($params=array())
	{

		$from_date  = isset($params['trdl_from_date'])? $params['trdl_from_date']:'';
		$to_date  = isset($params['trdl_to_date'])? $params['trdl_to_date']:'';
		$school_name  = isset($params['trdl_school_name'])? $params['trdl_school_name']:'';
		$mandal  = isset($params['trdl_mandal'])? $params['trdl_mandal']:'';
		$village  = isset($params['trdl_village'])? $params['trdl_village']:'';
		$child_name  = isset($params['trdl_child_name'])? $params['trdl_child_name']:'';
		$father  = isset($params['trdl_father'])? $params['trdl_father']:'';
		$trdl_admin_name   = isset($_SESSION['admin_name'])? $_SESSION['admin_name']:'';
		$admin_mail   = isset($_SESSION['admin_mail'])? $_SESSION['admin_mail']:'';
		$admin_mobile   = isset($_SESSION['admin_mobile'])? $_SESSION['admin_mobile']:'';

		$All   = isset($params['trdl_all'])? $params['trdl_all']:'';



		$CI =& get_instance();

		$CI->db->select("email");
		$CI->db->from('users');
		$CI->db->where("role","CA");
		$query = $CI->db->get();
		$result = $query->row_array();
		$uemail = isset($result['email']) ? $result['email'] : '';

		//echo $admin_mail ; exit;


		$CI->load->library('Phpmailer');
		$mail = new PHPMailer();
		$changed_date = date('m/d/Y');

		

		  $htmlContent = '<p>Dear Chief admin,</p>';
	
				$htmlContent .='<p>';
				$htmlContent .='Data Export Has Been Initiated BY ';
				$htmlContent .='(<b>';
				$htmlContent .=$trdl_admin_name;
				$htmlContent .='</b>) From Screening Program ';



				

				if($All ==1)
				{
					$htmlContent .= '<p>Filters  :  No Filters Used </p>';
					$htmlContent .= '<p>All Data Export </p>';
				}
				else
				{
					$htmlContent .= '<p> <u>Filters : </u></p>';




				if($child_name !='')
				{
					$htmlContent .= '<p><b>Name Of The Child :</b>'.$child_name.'</p>';
				}

				if($father !='')
				{
					$htmlContent .= '<p> <b>Father Name :</b> '.$father.'</p>';
				}

				if($village !='')
				{
					$htmlContent .= '<p> <b>Village :</b>  '.$village.'</p>';
				}


				if($from_date !='')
				{
					$htmlContent .= '<p> <b>From Date:</b>  '.date('d-m-Y',strtotime($from_date)).'</p>';
				}

				if($to_date !='')
				{
					$htmlContent .= '<p> <b>To Date:</b>  '.date('d-m-Y',strtotime($to_date)).'</p>';
				}


				if($school_name !='')
				{
					$htmlContent .= '<p> <b>School Name:</b>  '.$school_name.'</p>';
				}


				if($mandal !='')
				{
					$htmlContent .= '<p> <b>Mandal  :</b>  '.$mandal.'</p>';
				}

			}
				 
				
				$htmlContent .= '<p>Thank you <br/>';

				$htmlContent .=$trdl_admin_name.'</p>' ;
				$htmlContent .='<p>'.$admin_mail.'</p>' ;
				$htmlContent .='<p>'.$admin_mobile.'</p>' ;
				$htmlContent .= '<p>Team eyeSmart EMR</p>';			
				$body = $htmlContent;

				
				$mail_cc = trim($uemail);	
				$mail->IsSMTP(); // telling the class to use SMTP
				$mail->Host       = "220.227.249.163"; // SMTP server
				//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
				$mail->Host       = "220.227.249.163"; // SMTP server
				$mail->Port       = 25;                   // set the SMTP port for the GMAIL server
				//$mail->Username   = "emrreports@lvpei.org";  // GMAIL username
				//$mail->Password   = "emrreports";            // GMAIL password

				$mail->Subject = 'Screening Program';

				$mail->MsgHTML($htmlContent);

				$mail->AddAddress($admin_mail);
				$mail->AddCC($mail_cc);

				if($mail->Send())
				{
					return 1;	
				}
				else
				{
					return 0;	
				}

	

	}

	public function sendScreenUserEmail($params=array())
    {
    	
    	
    	if(count($params) > 0)
    	{	
    		
				$screening_schedule_id = isset($params['screening_schedule_id']) ? $params['screening_schedule_id'] :0;
				$screening_user_id = isset($params['screening_user_id']) ? $params['screening_user_id'] :0;
				$creadted_by = isset($params['creadted_by']) ? $params['creadted_by'] :0;
				$entry_date = isset($params['entry_date']) ? $params['entry_date'] :0;
				$entry_time = isset($params['entry_time']) ? $params['entry_time'] :0;
				$delete_flag = isset($params['delete_flag']) ? $params['delete_flag'] :0;

				$CI =& get_instance();
				if($delete_flag > 0)
				{
					$CI->db->join('screening_auth_log SA', 'SA.screening_schedule_id = SH.id');
					$CI->db->group_by('SA.screening_schedule_id,SA.screening_user_id');
				}
				else
				{
					$CI->db->join('screening_auth SA', 'SA.screening_schedule_id = SH.id');
					$where_data=array('UPCOMING','CURRENT','PENDING');
					$CI->db->where_in("SH.status",$where_data);
				}
				$CI->db->select("SU.name AS su_name ,SU.screening_username AS su_uname ,SU.email AS su_email ,SU.mobile AS su_mobile,SC.school_name AS sc_sch_name,SC.school_code AS sc_code,SU.status AS ustatus,DATE_FORMAT(start_date,'%d-%m-%Y') AS start_date,DATE_FORMAT(end_date,'%d-%m-%Y') AS end_date,SH.screening_reason AS reason");
				$CI->db->from('screening_schedule SH');
				$CI->db->join('school_master SC', 'SC.id = SH.school_id');
				$CI->db->join('screening_users SU', 'SU.id = SA.screening_user_id'); 
				$CI->db->where("SA.screening_schedule_id",$screening_schedule_id);
				$CI->db->where("SA.screening_user_id",$screening_user_id);

				
				$query = $CI->db->get();
				//echo $CI->db->last_query();
				if($query->num_rows() > 0)
				{
					$result = $query->row_array();
					$name = isset($result['su_name']) ? $result['su_name'] : '';
					$uname = isset($result['su_uname']) ? $result['su_uname'] : '';
					$pwd = isset($result['su_pwd']) ? $result['su_pwd'] : '';
					$uemail = isset($result['su_email']) ? $result['su_email'] : '';
					$mobile = isset($result['su_mobile']) ? $result['su_mobile'] : '';
					$school_name = isset($result['sc_sch_name']) ? $result['sc_sch_name'] : '';
					$school_code = isset($result['sc_code']) ? $result['sc_code'] : '';
					$admin_name = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : '';
					$deactive = isset($result['ustatus']) ? $result['ustatus'] : '';
					$start_date = isset($result['start_date']) ? $result['start_date'] : '';
					$end_date = isset($result['ustatus']) ? $result['end_date'] : '';
					$reason = isset($result['reason']) ? $result['reason'] : '';

					

					$CI->load->library('Phpmailer');
					$mail = new PHPMailer();
			        $changed_date = date('m/d/Y');

					$htmlContent = '<p>Dear '.$name.' ,</p>';
					if($deactive =='ACTIVE')
					{

						$htmlContent .='<p>';
						$htmlContent .='Your School Screening Program ';
						$htmlContent .='(<b> ';
						$htmlContent .=$school_name.'-';
						$htmlContent .=$school_code;
						$htmlContent .='</b>) ';
						$htmlContent .='is Scheduled From ';
						$htmlContent .='<b>'.$start_date.'</b> To <b>';
						$htmlContent .=$end_date.'</b> </p> ';
						$htmlContent .= '<p><u>Your Credentials</u></p>';
						$htmlContent .= '<p>Username :<b>'.$uname.'</b></p>';
						$htmlContent .= '<p>Password :<b>Ssp@123</b></p>';

					}
					else
					if($deactive =='INACTIVE') 
					{
						
						$htmlContent .='<p>';
						$htmlContent .='Your School Screening Program ';
						$htmlContent .='(<b> ';
						$htmlContent .=$school_name.'-';
						$htmlContent .=$school_code;
						$htmlContent .='</b>) ';
						$htmlContent .='is Canceled </p>';
						$htmlContent .= '<p><b>Reason :</b>.'.$reason.'</p>';
						$htmlContent .= '<p>please contact your admin</p>';

						
					}

					 //echo $htmlContent;exit;

					  if($uemail !='')
					  {
					  	$mail_cc = 'bvproyals@gmail.com';
						$htmlContent .= '<p>Thank you <br/>';
						$htmlContent .=$admin_name.'</p>' ;
						$htmlContent .= '<p>Team eyeSmart EMR</p>';			
						$body = $htmlContent;
				        $mail->IsSMTP(); // telling the class to use SMTP
						$mail->Host       = "220.227.249.163"; // SMTP server
						//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
					    $mail->Host       = "220.227.249.163"; // SMTP server
						$mail->Port       = 25;                   // set the SMTP port for the GMAIL server
						//$mail->Username   = "emrreports@lvpei.org";  // GMAIL username
						//$mail->Password   = "emrreports";            // GMAIL password
						
						$mail->Subject = 'Screening Program';

						$mail->MsgHTML($htmlContent);
						
						$mail->AddAddress($uemail);
						$mail->AddCC($mail_cc);
						
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
    			
    			
		}
		
    }

}

?>