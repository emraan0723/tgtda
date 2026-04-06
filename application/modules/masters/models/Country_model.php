<?php
class Country_model extends CI_model
{

  var $table = 'tbl_countries_masters';
  var $column_order = array(null, 'tc_country_ID','tc_country_name','tc_country_code','tc_country_status'); //set column field database for datatable orderable
  var $column_search = array('tc_country_name','tc_country_code','tc_country_status'); //set column field database for datatable searchable 
  var $order = array('tc_country_ID' => 'desc'); // default order 


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
      $this->db->where('tc_country_name', $this->input->post('country'));
    }
    if($this->input->post('ccode'))
    {
      $this->db->like('tc_country_code', $this->input->post('ccode'));
    }
    if($this->input->post('status'))
    {
      $this->db->like('tc_country_status', $this->input->post('status'));
    }

    $this->db->from($this->table);
    $i = 0;
  
    foreach ($this->column_search as $item) // loop column 
    {
      if($_POST['search']['value']) // if datatable send POST for search
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
    $this->db->from($this->table);
    return $this->db->count_all_results();
  }

  public function get_list_countries()
  {
    $this->db->select('tc_country_name AS country');
    $this->db->from($this->table);
    $this->db->order_by('tc_country_name','asc');
    $query = $this->db->get();
    $result = $query->result();

    $countries = array();
    foreach ($result as $row) 
    {
      $countries[] = $row->country;
    }
    return $countries;
  }


  #INSERT/UPDATE CHECKING COMMAN USEING
  public function getCountryList($params=array())
  {
     $country_name = isset($params['country_name']) ? $params['country_name'] : '';
     $country_code = isset($params['country_code']) ? $params['country_code'] : '';
     $country_id = isset($params['country_id']) ? $params['country_id'] : 0;
    $this->db->select("tc_country_ID AS country_id,tc_country_name AS country_name,tc_country_code");
    $this->db->from($this->table);
    if($country_name !='')
       $this->db->where("tc_country_name",$country_name);
    if($country_code !='')
        $this->db->where("tc_country_code",$country_code);
    if($country_id > 0)
        $this->db->where("tc_country_ID",$country_id); 

    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

 
  #INSERTING COUNTRY DATA
  public function saveCountry($params=array())
  {		
  	  $data['tc_country_name'] = isset($params['country_name']) ? $params['country_name'] : '';
      $data['tc_country_code'] = isset($params['country_code']) ? strtoupper($params['country_code']) : '';
  	  $data['tc_country_entry_date'] = date('Y-m-d');
      $data['tc_country_entry_time'] = date('h:i:s');
      $data['tc_country_entry_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #update  $country_id
      $country_id = isset($params['country_id']) ? $params['country_id'] : 0;
      #LOGIN USER ID & USER NAME
      $data['tc_country_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
      $data['tc_country_created_by_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
       #COUNTRY ALREADY IS EXISTS OR NOT CHEKING FUNCTION
       $isexits =  $this->getCountryList($params);
        if($country_id  > 0 && $isexits['isexists_insert'] == 0)
        {
            #UPDATE
            unset($data['tc_country_entry_date']);
            unset($data['tc_country_entry_time']);
            unset($data['tc_country_entry_ip_address']);

            $data['tc_country_update_date'] = date('Y-m-d');
            $data['tc_country_updated_time'] = date('h:i:s');
            $data['tc_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
            $this->db->where("tc_country_ID",$country_id);
            $this->db->UPDATE($this->table,$data);
            if(!($this->db->affected_rows())) 
              return 'COUNTRY_UPDATE_FAILED';
            else
              return 'COUNTRY_UPDATE_SUCCESS';
            exit;

        }
       else
       if(isset($isexits['isexists_insert']) && $isexits['isexists_insert'] == 0)
       {
          #INSERT
          $this->db->insert($this->table,$data);
          if(!($this->db->affected_rows())) 
            return 'COUNTRY_INSERT_FAILED';
          else
            return 'COUNTRY_INSERT_SUCCESS';
          exit;
       }
       else
       {
       		return 'ALREADY_EXITS_COUNTRY';
			    exit;
       }

	 

  }

  public function countyStatusUpdate($params=array())
  {
     $country_id = isset($params['country_id']) ? $params['country_id'] : 0;
     $data['tc_country_status'] = isset($params['status']) ? $params['status'] : '';
      $data['tc_country_update_date'] = date('Y-m-d');
      $data['tc_country_updated_time'] = date('h:i:s');
      $data['tc_updated_ip_address'] = $_SERVER['REMOTE_ADDR'];
      #LOGIN USER ID & USER NAME
      $data['tc_country_created_by'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
      $data['tc_country_created_by_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

     if($country_id > 0 && $data['tc_country_status'] !='')
     {
        $this->db->where("tc_country_ID",$country_id);
        $this->db->UPDATE($this->table,$data);
        if(!($this->db->affected_rows())) 
          return 'COUNTRY_UPDATE_FAILED';
        else
          return 'COUNTRY_UPDATE_SUCCESS';
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
