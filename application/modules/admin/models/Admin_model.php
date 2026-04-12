<?php
class Admin_model extends CI_model
{
  var $column_order = array(null,'tu_first_name','tu_last_name','tu_gender','tu_mobile','tu_status'); //set column field database for datatable orderable
  var $column_search = array('tu_first_name','tu_last_name','tu_gender','tu_mobile','tu_status'); //set column field database for datatable searchable 
  var $order = array('tu_ID' => 'desc'); // default order 


  public function __construct($value='')
  {
      parent::__construct();
      $this->load->database();
      $CI = &get_instance();
    
  }
  

  //add custom filter here
  private function _get_datatables_query()
  {
    
    $this->db->select("tu_ID AS admin_id,CONCAT(tu_first_name,' ',tu_last_name) AS admin_name,tu_first_name,tu_last_name,tu_gender,tu_mobile,tu_email,tu_address,tu_status,tu_username,tu_role,tupp_source, tupp_filename");
     $this->db->from('tbl_users');
     $this->db->join('tbl_upload_profile_pics','tupp_source_id=tu_ID AND tupp_source="admin"','left');
  
    $i = 0;
  
    foreach ($this->column_search as $item) // loop column 
    {
      if(isset($_POST['search']['value']) && $_POST['search']['value']) // if datatable send POST for search
      {
        
        if($i===0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        }
        else
        {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if(count($this->column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order))
    {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_datatables()
  {
    $this->_get_datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result();
  }

  public function count_filtered()
  {
    $this->_get_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all()
  {
    $this->db->from('tbl_users');
    return $this->db->count_all_results();
  }

  public function ProfileDeatails($params=array())
  {
    $admin_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $this->db->select("tu_ID AS admin_id,CONCAT(tu_first_name,' ',tu_last_name) AS admin_name,tu_first_name,tu_last_name,tu_gender,tu_mobile,tu_email,tu_address,tu_status,tupp_source, tupp_filename");
    if($admin_id > 0)
        $this->db->where("tu_ID",$admin_id); 
    $this->db->from('tbl_users');
    $this->db->join('tbl_upload_profile_pics','tupp_source_id=tu_ID AND tupp_source="admin"','left');
    $query = $this->db->get();
    if($query->num_rows() > 0)
    {
      return $query->row_array();
    }
    else
    {
      return array();
    }

  }

  #INSERT/UPDATE CHECKING COMMAN USEING
  public function getAdminList($params=array())
  { 
     $admin_id = isset($params['admin_id']) ? $params['admin_id']: 0;
     $mobile = isset($params['mobile']) ? $params['mobile'] : '';
     $email = isset($params['email']) ? $params['email'] : '';
     $username = isset($params['username']) ? $params['username'] : '';

     $this->db->select("tu_ID AS admin_id,CONCAT(tu_first_name,' ',tu_last_name) AS admin_name,tu_first_name,tu_last_name,tu_gender,tu_mobile,tu_email,tu_address,tu_status");
    $this->db->from('tbl_users');
    

    if($username !='')
       $this->db->where("tu_username",$username);
    if($email !='')
        $this->db->where("tu_email",$email);
    if($mobile !='')
        $this->db->where("tu_mobile",$mobile);
    if($admin_id > 0)
        $this->db->where("tu_ID",$admin_id);          
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

 
  #INSERTING COUNTRY DATA
  public function saveAdmin($params=array())
  {   

      $data['tu_first_name'] = isset($params['first_name']) ? $params['first_name'] : '';
      $data['tu_last_name'] = isset($params['last_name']) ? $params['last_nameget_datatables'] : '';
      $data['tu_gender'] = isset($params['gender']) ? $params['gender'] : '';
      $data['tu_mobile'] = isset($params['mobile']) ? $params['mobile'] : '';
      $data['tu_email'] = isset($params['email']) ? $params['email'] : '';
      $data['tu_address'] = isset($params['address']) ? $params['address'] : '';
      $data['tu_username'] = isset($params['username']) ? $params['username'] : '';
      $data['tu_password'] = isset($params['password']) ? password_hash(trim($params['password']), PASSWORD_BCRYPT)  : '';

      $data['tu_entry_date'] = date('Y-m-d');
      $data['tu_entry_time'] = date('h:i:s');
      $data['tu_entry_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #update  $state_id
      $admin_id = isset($params['admin_id']) ? $params['admin_id'] : 0;
      #LOGIN USER ID & USER NAME
      $data['tu_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
      $data['tu_created_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
       #COUNTRY ALREADY IS EXISTS OR NOT CHEKING FUNCTION

      if(!($admin_id  > 0) )
        $isexits =  $this->getAdminList($params);
    
        if($admin_id  > 0 )
        {
            #UPDATE
            unset($data['tu_entry_date']);
            unset($data['tu_entry_time ']);
            unset($data['tu_entry_ip_address']);
            unset($data['tu_username']);
            unset($data['tu_password']);

            $data['tu_update_date'] = date('Y-m-d');
            $data['tu_updated_time'] = date('h:i:s');
            $data['tu_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
            $this->db->where("tu_ID",$admin_id);
            $this->db->UPDATE('tbl_users',$data);

            if($this->db->affected_rows() > 0) 
               return 'UPDATE_SUCCESS';
            else
               return 'UPDATE_FAILED';
            exit;

        }
       else
       if(isset($isexits['isexists_insert']) && $isexits['isexists_insert'] == 0)
       {
          #INSERT
          $this->db->insert('tbl_users',$data);
          if($this->db->affected_rows() > 0) 
             return 'INSERT_SUCCESS';
          else
            return 'INSERT_FAILED';
           
          exit;
       }
       else
       {
          return 'ALREADY_EXITS';
          exit;
       }

   

  }

  public function adminStatusUpdate($params=array())
  {
      $admin_id = isset($params['admin_id']) ? $params['admin_id'] : 0;
      $data['tu_status'] = isset($params['status']) ? $params['status'] : '';
      $data['tu_update_date'] = date('Y-m-d');
      $data['tu_updated_time'] = date('h:i:s');
      $data['tu_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #LOGIN USER ID & USER NAME
      $data['tu_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
      $data['tu_created_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

     if($admin_id > 0 && $data['tu_status'] !='')
     {
        $this->db->where("tu_ID",$admin_id);
        $this->db->UPDATE('tbl_users',$data);
        if($this->db->affected_rows() > 0) 
          return 'UPDATE_SUCCESS';
        else
          return 'UPDATE_FAILED';
        exit;
      }
      else
      {
        return 'Error';
        exit;
      }

  }

  public function CheckingPassword($password)
  {
    $admin_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
      if($admin_id > 0 && $password !='')
      {
          $this->db->select('tu_password');
          $this->db->from('tbl_users');
          $this->db->where('tu_ID',$admin_id );
          $query = $this->db->get();
          if($query->num_rows() > 0)
          {
              $res =$query->row_array();

              $getpwd = $res['tu_password'];
              if(password_verify($password, $getpwd))
              {
                  return 'Valid';
                  exit;
              }
              else
              {
                  return 'NotValid';
                   exit;
              }

          }
          else
          {
            return 'INVALID_DATA';
             exit;
          }

         
         

      }
  } 

  public function ChangePassword($pwd)
  {

      $data['tu_password'] = isset($pwd) ? password_hash($pwd, PASSWORD_BCRYPT) : '';
      $admin_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
      $data['tu_update_date'] = date('Y-m-d');
      $data['tu_updated_time'] = date('h:i:s');
      $data['tu_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #LOGIN USER ID & USER NAME
      $data['tu_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
      $data['tu_created_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
      if($admin_id > 0 && $pwd !='')
      {
        $this->db->where("tu_ID",$admin_id);
        $this->db->UPDATE('tbl_users',$data);
        if($this->db->affected_rows() > 0) 
          return 'UPDATE_SUCCESS';
        else
          return 'UPDATE_FAILED';
          
        exit;
      }
      else
      {
        return 'Error';
        exit;
      }


  }


  public function Resetpassword($params=array())
  {
     $admin_id = isset($params['admin_id']) ? $params['admin_id'] : 0;
     if($admin_id > 0)
     {
       $this->db->select("tu_ID AS admin_id,CONCAT(tu_first_name,' ',tu_last_name) AS admin_name,tu_first_name,tu_last_name,tu_gender,tu_mobile,tu_email,tu_address,tu_status");
      $this->db->from('tbl_users');
      $this->db->where("tu_ID",$admin_id);
      $query = $this->db->get();
      $res = $query->row_array();
      $admin_name = ucwords(strtolower($res['admin_name']));
      $email = $res['tu_email'];

      $data['tu_update_date'] = date('Y-m-d');
      $data['tu_updated_time'] = date('h:i:s');
      $data['tu_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #LOGIN USER ID & USER NAME
      $data['tu_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
      $data['tu_created_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

      $rand_password = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghigklmnopqrstuvwxyz"), -8);

      $data['tu_password'] =  password_hash(trim($rand_password), PASSWORD_BCRYPT);


        $this->db->where("tu_ID",$admin_id);
        $this->db->UPDATE('tbl_users',$data);
        if($this->db->affected_rows() > 0) 
        {

            $msg ="<p>Dear $admin_name ,</p>";
            $msg .="<p>Your password is reset .</p>";
            $msg .="<p>Password : <b> $rand_password <b/></p>";

            $msg .="<br/><br/>
          <strong>
          Thanks  & Regards,<br/><br/></strong>

          TGTDA<br/>
         ";
            //echo $body; exit;
            $CI =& get_instance();
            $CI->load->library('Phpmailer');
            $mail = new PHPMailer();
            $changed_date = date('m/d/Y');
            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->SMTPAuth   = true;                  // enable SMTP authentication
            $mail->SMTPSecure = "tls";                 // sets the prefix to the servier
            $mail->Host       = "smtp.office365.com"; // SMTP server
            $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
            $mail->Host       = "smtp.office365.com"; // SMTP server
            $mail->Port       = 587;                   // set the SMTP port for the GMAIL server
          //  $mail->Username   = "";  // GMAIL username
            //$mail->Password   = "";            // GMAIL password
            $mail->Subject = 'Reset Password - TGTDA';
            $mail->WordWrap = 50;
            $mail->MsgHTML($msg);
            $mail->AddAddress($email);

            $mail->SMTPDebug  = 2;
            if($mail->Send())
            {
               return 'UPDATE_SUCCESS' ; 
            }
            else
            {
             
              return 'UPDATE_FAILED';
            }


           
        }
        else
        {

          return 'UPDATE_FAILED';
        }
       
      }
      else
      {
        return 'Error';
        exit;
      }


  }



  

 

  
}
?>
