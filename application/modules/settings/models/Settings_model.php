<?php
class Settings_model extends CI_model
{
  

  var $table = 'tbl_users';
  var $column_order = array(null, 'tu_first_name','tu_last_name','tu_mobile','tu_last_name'); //set column field database for datatable orderable
  var $column_search = array('tu_first_name','tu_last_name','tu_mobile'); //set column field database for datatable searchable 
  var $order = array('tu_ID' => 'asc'); // default order 


  public function __construct($value='')
  {
      parent::__construct();
      $this->load->database();
      $CI = &get_instance();
    
  }
  //add custom filter here
  private function _get_datatables_query()
  {
    
    if($this->input->post('admin_name'))
    {
        $this->db->group_start();
        $this->db->like('tu_first_name', $this->input->post('admin_name'));
        $this->db->or_like('tu_last_name', $this->input->post('admin_name'));
        $this->db->group_end();
    }
    if($this->input->post('mobile'))
    {
        $this->db->where('mobile', $this->input->post('mobile'));
    }
    $this->db->where('tu_status', 'ACTIVE');
    $this->db->where('tu_role !=', 'CA');
    #LOGIN USER ID
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
    $this->db->where('tu_ID !=', $user_id);
    $this->db->select("tu_ID AS admin_id,CONCAT(tu_first_name,' ',tu_last_name) AS admin_name,tu_status AS status,tu_mobile AS mobile");
    $this->db->from('tbl_users');
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

  public function getAccessList($user_id,$module)
  {
        $this->db->select("tup_permission_module,tup_adding,tup_edit,tup_view,tup_no_access,tup_full_access");
        $this->db->from('tbl_user_permissions');
        $this->db->where("tup_user_ID",$user_id);
        $this->db->where("tup_permission_module",$module);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        if($query->num_rows() > 0)
        { 
          return $query->row_array();
        }
        else
        {
          return array();
        }

  }
  public function userPrivilegesAccess($params=array())
  {
      $data['tup_user_ID'] = isset($params['admin_id']) ? $params['admin_id'] : 0;
      $data['tup_permission_module']= isset($params['acessmodule']) ? $params['acessmodule'] : '';
      $privilege = isset($params['privilege']) ? $params['privilege'] : '';
      $privilege_value = isset($params['privilege_value']) ? $params['privilege_value'] : 0;
      $full_access_remove = isset($params['fullacessremove']) ? $params['fullacessremove'] : 0;

      if($privilege=='adding')
      {
        $data['tup_adding'] = $privilege_value;
        $data['tup_no_access'] = 0;
      }
      if($privilege=='edit')
      {
        $data['tup_edit'] = $privilege_value;
        $data['tup_no_access'] = 0;
      }
      if($privilege=='view')
      {
        $data['tup_view'] = $privilege_value;
        $data['tup_no_access'] = 0;
      }
      if($privilege=='no_access' )
      {
        if($privilege_value==1)
        {
            $data['tup_adding'] = 0;
            $data['tup_edit'] = 0;
            $data['tup_view'] = 0;
            $data['tup_full_access'] = 0;
        }
       
        $data['tup_no_access'] = $privilege_value;
      }

      if($privilege=='full_access')
      {
        if($privilege_value==1)
        {
            $data['tup_no_access'] = 0;
            $data['tup_adding'] = 1;
            $data['tup_edit'] = 1;
            $data['tup_view'] = 1;
        }
        else
        {
            $data['tup_no_access'] = 0;
            $data['tup_adding'] = 0;
            $data['tup_edit'] = 0;
            $data['tup_view'] = 0;
        }


        $data['tup_full_access'] = $privilege_value;
       
      }


      if($full_access_remove==1)
      {
        $data['tup_full_access'] = 0;
      }

  
      $data['tup_entry_date'] = date('Y-m-d');
      $data['tup_entry_time'] = date('h:i:s');
      $data['tup_entry_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #LOGIN USER ID & USER NAME
      $data['tup_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
      $data['tup_created_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

     if($data['tup_user_ID'] > 0 && $privilege !='')
     {
        $this->db->select("tup_user_ID");
        $this->db->from('tbl_user_permissions');
        $this->db->where("tup_user_ID",$data['tup_user_ID']);
        $this->db->where("tup_permission_module",$data['tup_permission_module']);

        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        if($query->num_rows() > 0)
        { 
          unset($data['tup_entry_date']);
          unset($data['tup_entry_time']);
          unset($data['tup_entry_ip_address']);
          $data['tup_update_date'] = date('Y-m-d');
          $data['tup_updated_time'] = date('h:i:s');
          $data['tup_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
            #UPDATE
            $this->db->where("tup_user_ID",$data['tup_user_ID']);
             $this->db->where("tup_permission_module",$data['tup_permission_module']);
            $this->db->UPDATE('tbl_user_permissions',$data);
            if($this->db->affected_rows() >0) 
              return 'UPDATE_SUCCESS';
            else
               return 'UPDATE_FAILED';
            exit;
        }
        else
        {
          #INSERT
          $this->db->insert('tbl_user_permissions',$data);
          if($this->db->affected_rows() > 0) 
            return 'INSERT_SUCCESS';
          else
            return 'INSERT_FAILED';

          exit;
        }

      }
      else
      {
        return 'Error';
        exit;
      }
  
  }
    
   public function ManualUpadatePayments($params=array())
   {
        $customer_id = isset($params['customer_id']) ? $params['customer_id'] : 0;
        $customer_code = isset($params['customer_code']) ? $params['customer_code'] : '';
        $invoice_date = isset($params['invoice_date']) ? date('Y-m-d',strtotime($params['invoice_date'])) : '';

        $inv_data = array(); #UPDATE tbl_invoices_dataset
        $pay_data = array(); #UPDATE tbl_invoices_product_details
        $log_data = array(); # INSERT LOG tbl_manual_payments_update

        $this->db->select("tid_invoice_ID,tid_customer_ID,tid_customer_name,tid_customer_code,tid_entry_date,tid_bank_status");
        $this->db->from('tbl_invoices_dataset');
        $this->db->where("tid_customer_ID",$customer_id);
        $this->db->where("tid_customer_code",$customer_code);
        $this->db->where("tid_entry_date",$invoice_date);
        $this->db->order_by("tid_entry_date", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        if($query->num_rows() > 0)
        { 
          $row = $query->row_array();
          if($row['tid_bank_status'] !='Success')
          {

            $inv_date =  $row['tid_entry_date'];
            $inv_id =  $row['tid_invoice_ID'];
            $customer_name =  $row['tid_customer_name'];
            
            $current_date = date('Y-m-d');
            $curr_time = date('h:i:s') ;
            $ip_address = $_SERVER['REMOTE_ADDR'];
             #LOGIN USER ID & USER NAME
            $created_by = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
            $created_by_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 

            $inv_data['tid_update_date'] = $current_date;
            $inv_data['tid_bank_status'] = 'Success';
            $inv_data['tid_bank_status_code'] = '000';
            $inv_data['tid_invoice_status'] = 'CLOSE';
            $inv_data['tid_update_time'] = $curr_time;
            $inv_data['tid_update_ip_address'] = $ip_address;

            $this->db->where("tid_customer_ID",$customer_id);
            $this->db->where("tid_customer_code",$customer_code);
            $this->db->where("tid_entry_date",$inv_date);

            $this->db->UPDATE('tbl_invoices_dataset',$inv_data);


            if($this->db->affected_rows() > 0)
            {
              $pay_data['tipd_update_date'] = $current_date;
              $pay_data['tipd_bill_status'] = 'PAID';
              $pay_data['tipd_update_time'] = $curr_time;
              $pay_data['tipd_update_ip_address'] = $ip_address;
             
              $this->db->where("tipd_invoice_id",$inv_id);
              $this->db->UPDATE('tbl_invoices_product_details',$pay_data);

              if($this->db->affected_rows() > 0)
              {
                  $log_data['tmpu_customer_name'] = $customer_name;
                  $log_data['tmpu_customer_code'] = $customer_code;
                  $log_data['tmpu_customer_id'] = $customer_id;
                  $log_data['tmpu_invoice_id'] = $inv_id;
                  $log_data['tmpu_customer_invoice_date'] = $inv_date;

                  $log_data['tmpu_created_date'] = $current_date;
                  $log_data['tmpu_created_time'] = $curr_time;
                  $log_data['tmpu_created_ip_address'] = $ip_address;
                  $log_data['tmpu_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
                  $log_data['tmpu_created_by_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

                 $this->db->insert('tbl_manual_payments_update',$log_data);
                   
                    if($this->db->affected_rows() > 0) 
                      return 'UPDATE_SUCCESS';
                    else
                      return 'UPDATE_FAILED';

              }


             
            } 
           

          }
          else
          {
            return 'ALREADY_EXITS';
            exit;
          }
          
        }
        else
        {
           return 'UPDATE_FAILED';
        }
     
   }
 

 
  
}
?>
