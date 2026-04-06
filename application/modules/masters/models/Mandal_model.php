<?php
class Mandal_model extends CI_model
{
  var $column_order = array(null,'tc_country_name','ts_state_name','tdt_district_name','tm_mandal','tm_mandal_status'); //set column field database for datatable orderable
  var $column_search = array('tc_country_name','ts_state_name','tdt_district_name','tm_mandal','tm_mandal_status'); //set column field database for datatable searchable
  var $order = array('tm_mandal_ID' => 'desc'); // default order


  public function __construct($value='')
  {
      parent::__construct();
      $this->load->database();
      $CI = &get_instance();
    
  }
  //add custom filter here
  private function _get_datatables_query()
  {
    
    if($this->input->post('state'))
    {
      $this->db->where('ts_state_ID', $this->input->post('state'));
    }
    if($this->input->post('district'))
    {
      $this->db->like('tdt_district_name', $this->input->post('district'));
    }
    if($this->input->post('mandal'))
    {
      $this->db->like('tm_mandal', $this->input->post('mandal'));
    }
     if($this->input->post('status'))
    {
      $this->db->where('tm_mandal_status', $this->input->post('tdt_status'));
    }
    if($this->input->post('country'))
    {
      $this->db->where('tc_country_name', $this->input->post('country'));
    }

    $this->db->select("tdt_district_name AS district_name,tdt_district_code AS district_code,ts_state_name AS state_name,ts_state_code AS state_code,tm_mandal_ID AS mandal_id ,tm_mandal AS mandal_name,tm_mandal_status AS status,tm_mandal_state_ID AS state_id,tdt_district_ID AS district_id,tc_country_name AS country_name");
     $this->db->from('tbl_mandal_masters');
    $this->db->join('tbl_district_masters','tdt_district_ID =tm_mandal_district_ID','left');
    $this->db->join('tbl_state_masters','ts_state_ID =tm_mandal_state_ID','left');
    $this->db->join('tbl_countries_masters','tc_country_ID =tm_mandal_country_ID');
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
    $this->db->from('tbl_mandal_masters');
    return $this->db->count_all_results();
  }

 

  #INSERT/UPDATE CHECKING COMMAN USEING
  public function getMandalList($params=array())
  { 
     $mandal_id = isset($params['mandal_id']) ? $params['mandal_id']: '';
     $country_id = isset($params['country_id']) ? $params['country_id']: '';
     $district_id = isset($params['district_id']) ? $params['district_id'] : '';
     $mandal_name = isset($params['mandal_name']) ? $params['mandal_name'] : '';
     $state_id = isset($params['state_id']) ? $params['state_id'] : 0;

     $this->db->select("tdt_district_name AS district_name,tdt_district_ID AS district_id,tm_mandal_state_ID AS state_id,ts_state_name AS state_name,tm_mandal_ID AS mandal_id ,tm_mandal AS mandal_name,tm_mandal_status AS status,tc_country_ID AS country_id,tc_country_name AS country_name");
    $this->db->from('tbl_mandal_masters');
    $this->db->join('tbl_district_masters','tdt_district_ID =tm_mandal_district_ID','left');
    $this->db->join('tbl_state_masters','ts_state_ID =tm_mandal_state_ID','left');
    $this->db->join('tbl_countries_masters','tc_country_ID =tm_mandal_country_ID');

    if($mandal_name !='')
       $this->db->where("tm_mandal",$mandal_name);
    if($mandal_id > 0)
        $this->db->where("tm_mandal_ID",$mandal_id);
    if($district_id > 0)
        $this->db->where("tdt_district_ID",$district_id);
    if($state_id > 0)
        $this->db->where("ts_state_ID",$state_id);
    if($country_id > 0)
        $this->db->where("tm_mandal_country_ID",$country_id);
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

 
  #INSERTING COUNTRY DATA
  public function saveMandal($params=array())
  {		

  	  $data['tm_mandal'] = isset($params['mandal_name']) ? $params['mandal_name'] : '';
      $data['tm_mandal_state_ID'] = isset($params['state_id']) ? $params['state_id']: '';
      $data['tm_mandal_country_ID'] = isset($params['country_id']) ? $params['country_id']: '';
      $data['tm_mandal_district_ID'] = isset($params['district_id']) ? $params['district_id']: '';
  	  $data['tm_mandal_entry_date'] = date('Y-m-d');
      $data['tm_mandal_entry_time'] = date('h:i:s');
      $data['tm_mandal_entry_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #update  $state_id
      $mandal_id = isset($params['mandal_id']) ? $params['mandal_id'] : 0;
      #LOGIN USER ID & USER NAME
      $data['tm_mandal_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
      $data['tm_mandal_created_by_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
       #COUNTRY ALREADY IS EXISTS OR NOT CHEKING FUNCTION
       $isexits =  $this->getMandalList($params);
       //echo $this->db->last_query();exit;
        if($mandal_id  > 0 && $isexits['isexists_insert'] == 0)
        {
            #UPDATE
            unset($data['tm_mandal_entry_date']);
            unset($data['tm_mandal_entry_time ']);
            unset($data['tm_mandal_entry_ip_address']);

            $data['tm_mandal_update_date'] = date('Y-m-d');
            $data['tm_mandal_updated_time'] = date('h:i:s');
            $data['tm_mandal_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
            $this->db->where("tm_mandal_ID",$mandal_id);
            $this->db->UPDATE('tbl_mandal_masters',$data);

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
          $this->db->insert('tbl_mandal_masters',$data);
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

  public function mandalStatusUpdate($params=array())
  {
      $mandal_id = isset($params['mandal_id']) ? $params['mandal_id'] : 0;
      $data['tm_mandal_status'] = isset($params['status']) ? $params['status'] : '';
      $data['tm_mandal_update_date'] = date('Y-m-d');
      $data['tm_mandal_updated_time'] = date('h:i:s');
      $data['tm_mandal_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #LOGIN USER ID & USER NAME
      $data['tm_mandal_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
      $data['tm_mandal_created_by_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

     if($mandal_id > 0 && $data['tm_mandal_status'] !='')
     {
        $this->db->where("tm_mandal_ID",$mandal_id);
        $this->db->UPDATE('tbl_mandal_masters',$data);
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
