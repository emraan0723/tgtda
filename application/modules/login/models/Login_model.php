<?php
class Login_model extends CI_model
{
  public function __construct($value='')
  {
      parent::__construct();
      $this->load->database();
      $CI = &get_instance();
     # $this->db2 = $this->load->database('old_emr',TRUE);
  }
  public function login_user($user_name,$user_password,$user_pin)
  {
    $array = array('username' => $user_name, 'auth_password' => $user_password, 'auth_code' => $user_pin);

    $this->db->select('*');
    $this->db->from('users');
    $this->db->where($array);
    if($query=$this->db->get())
    {

      return $query->row_array();
    }
    else
    {
      return false;
    }
  } // end of login_user()



}
?>
