<?php 
/*****************/
/*** @Author B VENKAT *******/
/*** Date - 28/12/2018 *******/
/*** @Developer BV *******/
/*****************/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
			$this->load->library('session');
		//FUNCTION FOR WRITE TO FILE
		if ( ! function_exists('write_to_file'))
		{
			
			$pth = base_url().'debug.php';
			function write_to_file($data='',$dataprint) // For Local
			{
	             $on = isset($_SESSION['on_debug']) ? $_SESSION['on_debug'] : 'no'; //On or Off debug helper unctionalities..	
				if($on == 'yes')
				{
					$str = "";
					$str .= date(DATE_RFC822). "::";
					$str .= "Data -".$dataprint;
					$str .= chr(13).chr(10);

					$filepage = "filelog/debug_".date('d-m-y');
					$myfile = fopen($filepage, "w") or die("Unable to open file!");
					fwrite($myfile, $str);
					fclose($myfile);

					//file_put_contents('debug.php', $str, FILE_APPEND | LOCK_EX);
				}				
			}			
		}
		
		//FUNCTION FOR ECHO/PRINT_R
		if ( ! function_exists('echoing'))
		{		
			function echoing($contentData='', $arrayData)
			{				
				global $on;				
				if($on == 'yes')
				{
					echo '<pre>';
					echo $contentData;
					print_r($arrayData);
				}
			}			
		}
		
?>