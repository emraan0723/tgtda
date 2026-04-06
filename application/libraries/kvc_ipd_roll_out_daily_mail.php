<?php

function sendMail($to,$subject,$message,$support="Support",$attachment,$files , $date_field, $cc)
{
	$mail = new PHPMailer();
	
	$body = $message;
	
	$mail->IsSMTP(); // telling the class to use SMTP
	//$mail->Host       = "smtp.gmail.com"; // SMTP server
	$mail->Host       = "172.16.154.9"; // SMTP server
	$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
											   // 1 = errors and messages
											   // 2 = messages only
	//$mail->SMTPAuth   = true;                  // enable SMTP authentication
	//$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	//$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Host       = "172.16.154.9";      // sets GMAIL as the SMTP server
	$mail->Port       = 25;                   // set the SMTP port for the GMAIL server
	//$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
	//$mail->Username   = "eyepep2010@gmail.com";  // GMAIL username
	//$mail->Password   = "#eyepep2010$";            // GMAIL password
	//$mail->Username   = "emrreports@lvpei.org";  // GMAIL username
	//$mail->Password   = "emrreports";            // GMAIL password
	
	//$mail->AddReplyTo('honavar@lvpei.org', 'honavar@lvpei.org');
	//$mail->SetFrom('honavar@lvpei.org', 'honavar@lvpei.org');
	
	$mail->Subject = $subject;

	$mail->MsgHTML($body);
	
	//$address = $to;
	//$mail->AddAddress($address);
	//$mail->AddAddress('pganesh@lvpei.org');
	//$mail->AddAddress('pganesh@lvpei.org');
	
	$find = strpos($to,',');
	if($find)
	{
		$ids = explode(',',$to);
		for($i=0;$i<count($ids);$i++)
		{
			$mail->AddAddress($ids[$i]);
		}
	}
	else
	{
		$mail->AddAddress($to);
	}
	
	if($cc != '')
	{
		$find = strpos($cc,',');
		if($find)
		{
			$ids = explode(',',$cc);
			for($i=0;$i<count($ids);$i++)
			{
				$mail->AddCC($ids[$i]);
			}
		}
		else
		{
			$mail->AddCC($cc);
		}
	}
	
	$date = date("d-m-Y" , strtotime($date_field));
	$kar_file = $path;
 	
	foreach($files as $k => $file_name)
	{
		if(file_exists($file_name))
			$mail->AddAttachment($file_name,$attachment, $encoding = "base64", $type = "application/octet-stream");
	}
 	if($mail->Send())
	{
		return 1;	
	}
	else
	{
		return 0;	
	}
}
?>