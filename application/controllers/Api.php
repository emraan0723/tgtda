<?php
defined('BASEPATH') OR exit('No direct script access allowed');
  ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);
require(APPPATH.'/libraries/REST_Controller.php');
class Api extends REST_Controller
{
       public function __construct() {
               parent::__construct();
               //$this->load->model('Admin_model');
       } 


    function user_post()
    {
      $customer = isset($_POST['customer']) ? $_POST['customer'] : '';
      $customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : '';
      $massage=array('customer' => $customer, 
        'customer_id' => $customer_id,

        );
      //$this->set_response($massage);
       $this->response($massage);
      

        // respond with information about a user
    }
     
    function users_get()
    {
       // echo "skjhaskhdk";
       $massage=array('customer' => 'BV', 
        'customer_id' => '10001',
        );
        $this->response($massage); 
      //$this->set_response($massage,REST_Controller::HTTP_OK);
    }
	
      
      /* public function user_put(){
           $id = $this->uri->segment(3);
           $data = array('name' => $this->input->get('name'),
           'pass' => $this->input->get('pass'),
           'type' => $this->input->get('type')
           );
            $r = $this->Admin_model->update($id,$data);
               $this->response($r); 
       }
       public function user_post(){
           $data = array('name' => $this->input->post('name'),
           'pass' => $this->input->post('pass'),
           'type' => $this->input->post('type')
           );
           $r = $this->Admin_model->insert($data);
           $this->response($r); 
       }
       public function user_delete(){
           $id = $this->uri->segment(3);
           $r = $this->Admin_model->delete($id);
           $this->response($r); 
       }
    */
}