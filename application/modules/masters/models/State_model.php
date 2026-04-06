<?php
class State_model extends CI_model
{

  var $table = 'tbl_state_masters';
  var $column_order = array(null, 'ts_state_ID','tc_country_name','ts_state_name','ts_state_code','ts_status'); //set column field database for datatable orderable
  var $column_search = array('tc_country_name','ts_state_name','ts_state_code','ts_status'); //set column field database for datatable searchable 
  var $order = array('ts_state_ID' => 'desc'); // default order 


  public function __construct($value='')
  {
      parent::__construct();
      $this->load->database();
      $CI = &get_instance();
    
  }
  //add custom filter here
  private function _get_datatables_query()
  {
    
    if($this->input->post('country'))
    {
      $this->db->where('ts_country_id', $this->input->post('country'));
    }
    if($this->input->post('state'))
    {
      $this->db->like('ts_state_name', $this->input->post('state'));
    }
    if($this->input->post('scode'))
    {
      $this->db->like('ts_state_code', $this->input->post('scode'));
    }
     if($this->input->post('status'))
    {
      $this->db->where('ts_status', $this->input->post('status'));
    }

    $this->db->select("tc_country_name AS country_name,ts_state_name AS state_name,ts_state_code AS state_code,ts_status AS status,ts_state_ID AS state_id,ts_country_id AS country_id,tc_country_code AS country_code");
    $this->db->from('tbl_state_masters');
    $this->db->join('tbl_countries_masters','tc_country_ID =ts_country_id');
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
    $this->db->from('tbl_state_masters');
    return $this->db->count_all_results();
  }

 

  #INSERT/UPDATE CHECKING COMMAN USEING
  public function getStateList($params=array())
  {
     $country_id = isset($params['country_id']) ? $params['country_id'] : '';
     $state_name = isset($params['state_name']) ? $params['state_name'] : '';
     $state_code = isset($params['state_code']) ? $params['state_code'] : '';
     $state_id = isset($params['state_id']) ? $params['state_id'] : 0;
    $this->db->select("ts_state_ID AS state_id,ts_country_id AS country_id ,ts_state_name AS state_name,ts_state_code");
    $this->db->from('tbl_state_masters');
    if($state_name !='')
       $this->db->where("ts_state_name",$state_name);
    if($state_code !='')
        $this->db->where("ts_state_code",$state_code);
    if($state_id > 0)
        $this->db->where("ts_state_ID",$state_id);
    if($country_id > 0)
        $this->db->where("ts_country_id",$country_id);      

    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

 
  #INSERTING COUNTRY DATA
  public function saveState($params=array())
  {		
  	  $data['ts_state_name'] = isset($params['state_name']) ? $params['state_name'] : '';
      $data['ts_state_code'] = isset($params['state_code']) ? strtoupper($params['state_code']) : '';
      $data['ts_country_id'] = isset($params['country_id']) ? $params['country_id']: '';
  	  $data['ts_entry_date'] = date('Y-m-d');
      $data['ts_entry_time'] = date('h:i:s');
      $data['ts_entry_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #update  $state_id
      $state_id = isset($params['state_id']) ? $params['state_id'] : 0;
      #LOGIN USER ID & USER NAME
      $data['ts_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
      $data['ts_created_by_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
       #COUNTRY ALREADY IS EXISTS OR NOT CHEKING FUNCTION
       $isexits =  $this->getStateList($params);
        if($state_id  > 0 && $isexits['isexists_insert'] == 0)
        {
            #UPDATE
            unset($data['ts_entry_date']);
            unset($data['ts_entry_time']);
            unset($data['ts_entry_ip_address']);

            $data['ts_update_date'] = date('Y-m-d');
            $data['ts_updated_time'] = date('h:i:s');
            $data['ts_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
            $this->db->where("ts_state_ID",$state_id);
            $this->db->UPDATE('tbl_state_masters',$data);

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
          $this->db->insert('tbl_state_masters',$data);
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

  public function stateStatusUpdate($params=array())
  {
     $state_id = isset($params['state_id']) ? $params['state_id'] : 0;
     $data['ts_status'] = isset($params['status']) ? $params['status'] : '';
      $data['ts_update_date'] = date('Y-m-d');
      $data['ts_updated_time'] = date('h:i:s');
      $data['ts_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #LOGIN USER ID & USER NAME
      $data['ts_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
      $data['ts_created_by_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

     if($state_id > 0 && $data['ts_status'] !='')
     {
        $this->db->where("ts_state_ID",$state_id);
        $this->db->UPDATE('tbl_state_masters',$data);
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
