<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

$data = (isset($data)) ? $data : array() ;

#authentication PAGE
$authentication = isset($data['authentication']) ? $data['authentication'] : '' ;
if($authentication =="authenticationError")
{
	 $this->load->view('includes/error_page', $data);
	exit;

}

#HEADER FROM  INCLUDE IN MAIN VIEW
 $this->load->view('includes/header',$data); 

#SIDEBAR FROM  INCLUDE IN MAIN VIEW
 $this->load->view('includes/sidebar', $data);

#VIEW CALING
$viewfile = isset($data['content']) ? $data['content'] : '' ;
if($viewfile !="")
{
	 $this->load->view($viewfile,$data);  

}

#content_footer FROM  INCLUDE IN MAIN VIEW
#COMMAN JS FILES
 $this->load->view('js/get_state_dis_city',$data); 

#footer FROM  INCLUDE IN MAIN VIEW
 $this->load->view('includes/footer',$data); 

