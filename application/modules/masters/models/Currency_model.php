<?php
class Currency_model extends CI_model
{
  var $column_order = array(null,'tcm_currency_name','tcm_currency_short_name','tcm_status'); //set column field database for datatable orderable
  var $column_search = array('tcm_currency_name','tcm_currency_short_name','tcm_status'); //set column field database for datatable searchable 
  var $order = array('tcm_currency_name' => 'ASC'); // default order 


  public function __construct($value='')
  {
      parent::__construct();
      $this->load->database();
      $CI = &get_instance();
    
  }
  //add custom filter here
  private function _get_datatables_query()
  {
     
    
    $this->db->select("tcm_currency_ID AS currency_id,tcm_currency_name AS currency_name,tcm_currency_symbol AS currency_symbol,tcm_currency_short_name,tcm_status AS status");
     $this->db->from('tbl_currency_master');
  
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
    $this->db->from('tbl_currency_master');
    return $this->db->count_all_results();
  }

 

  #INSERT/UPDATE CHECKING COMMAN USEING
  public function getCurrencyList($params=array())
  { 
      $currency_short_name = isset($params['currency_short_name']) ? $params['currency_short_name'] :'';
      $currency_id = isset($params['currency_id']) ? $params['currency_id'] : 0;
      $currency_name = isset($params['currency_name']) ? $params['currency_name'] : '';

      $this->db->select("tcm_currency_ID AS currency_id,tcm_currency_short_name,tcm_currency_name AS currency_name,tcm_currency_symbol AS currency_symbol,tcm_status AS status");
     $this->db->from('tbl_currency_master');

    if($currency_name !='')
        $this->db->where("tcm_currency_name",$currency_name);
    if($currency_id > 0)
        $this->db->where("tcm_currency_ID",$currency_id);
   if($currency_short_name !='')
        $this->db->where("tcm_currency_short_name",$currency_short_name);
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

 
  #INSERTING COUNTRY DATA
  public function saveCurrency($params=array())
  {		
   
      $data['tcm_currency_short_name'] = isset($params['currency_short_name']) ? strtoupper($params['currency_short_name']) : '';
  	  $data['tcm_currency_name'] = isset($params['currency_name']) ? $params['currency_name'] : '';
      $data['tcm_currency_symbol'] = isset($params['currency_symbol']) ? $params['currency_symbol']: '';
  	  $data['tcm_entry_date'] = date('Y-m-d');
      $data['tcm_entry_time'] = date('h:i:s');
      $data['tcm_entry_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #update  $currency_id
      $currency_id = isset($params['currency_id']) ? $params['currency_id'] : 0;
      #LOGIN USER ID & USER NAME
      $data['tcm_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
      $data['tcm_created_by_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
       #COUNTRY ALREADY IS EXISTS OR NOT CHEKING FUNCTION
       $isexits =  $this->getCurrencyList($params);
       // echo $this->db->last_query();exit;
        if($currency_id  > 0 && $isexits['isexists_insert'] == 0)
        {
            #UPDATE
            unset($data['tcm_entry_date']);
            unset($data['tcm_entry_time ']);
            unset($data['tcm_entry_ip_address']);

            $data['tcm_update_date'] = date('Y-m-d');
            $data['tcm_updated_time'] = date('h:i:s');
            $data['tcm_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
            $this->db->where("tcm_currency_ID",$currency_id);
            $this->db->UPDATE('tbl_currency_master',$data);

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
          $this->db->insert('tbl_currency_master',$data);
          if($this->db->affected_rows() > 0) 
             return 'INSERT_SUCCESS';
          else
            return 'INSERT_FAILED';
           
          exit;
       }
       else
       {
       		return 'ALREADY_EXITS_COUNTRY';
			    exit;
       }

	 

  }

  public function currencyStatusUpdate($params=array())
  {
      $currency_id = isset($params['currency_id']) ? $params['currency_id'] : 0;
      $data['tcm_status'] = isset($params['status']) ? $params['status'] : '';
      $data['tcm_update_date'] = date('Y-m-d');
      $data['tcm_updated_time'] = date('h:i:s');
      $data['tcm_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #LOGIN USER ID & USER NAME
      $data['tcm_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
      $data['tcm_created_by_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

     if($currency_id > 0 && $data['tcm_status'] !='')
     {
        $this->db->where("tcm_currency_ID",$currency_id);
        $this->db->UPDATE('tbl_currency_master',$data);
        if(!($this->db->affected_rows())) 
          return 'UPDATE_FAILED';
        else
          return 'UPDATE_SUCCESS';
        exit;
      }
      else
      {
        return 'Error';
        exit;
      }

  }

  

 

  
}
?>
