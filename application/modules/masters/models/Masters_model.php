<?php
class Masters_model extends CI_model
{
  public function __construct($value='')
  {
      parent::__construct();
      $this->load->database();
      $CI = &get_instance();
    
  }
  #GRID DISPALY COUNT 
  public function totalCountrys($params=array())
  {
      $search = isset($params['search']) ? $params['search'] : '';
       $col = 0;
        $valid_columns = array(
            0=>'tc_country_ID',
            1=>'tc_country_name',
            2=>'tc_country_code',
            3=>'tc_country_status',
        );
        if(!empty($search))
        {
            $x=0;
            foreach($valid_columns as $sterm)
            {
                if($x==0)
                {
                    $this->db->like($sterm,$search);
                }
                else
                {
                    $this->db->or_like($sterm,$search);
                }
                $x++;
            }                 
        }
        $this->db->select("COUNT(tc_country_ID) as num");
        $query = $this->db->get("tbl_countries_masters");
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
  }
  #GRID DISPALY DATATABLE
  public function viewCountrys($params=array())
  {
     $draw = isset($params['draw']) ? $params['draw'] : 0;
     $start = isset($params['start']) ? $params['start'] : 0;
     $length = isset($params['length']) ? $params['length'] :0;
     $order = isset($params['order']) ? $params['order'] : '';
     $search = isset($params['search']) ? $params['search'] : '';

     $col = 0;
        $dir = "";
        if(!empty($order))
        {
            foreach($order as $o)
            {
                $col = $o['column'];
                $dir= $o['dir'];
            }
        }

        if($dir != "asc" && $dir != "desc")
        {
            $dir = "desc";
        }
        $valid_columns = array(
            0=>'tc_country_ID',
            1=>'tc_country_name',
            2=>'tc_country_code',
            3=>'tc_country_status',
        );
        if(!isset($valid_columns[$col]))
        {
            $order = null;
        }
        else
        {
            $order = $valid_columns[$col];
        }
        if($order !=null)
        {
            $this->db->order_by($order, $dir);
        }
        
        if(!empty($search))
        {
            $x=0;
            foreach($valid_columns as $sterm)
            {
                if($x==0)
                {
                    $this->db->like($sterm,$search);
                }
                else
                {
                    $this->db->or_like($sterm,$search);
                }
                $x++;
            }                 
        }
        $this->db->limit($length,$start);
        $result = $this->db->get("tbl_countries_masters");
        return $result;
    
  }

  #INSERT/UPDATE CHECKING COMMAN USEING
  public function getCountryList($params=array())
  {
  	 $country_name = isset($params['country_name']) ? $params['country_name'] : '';
  	 $country_code = isset($params['country_code']) ? $params['country_code'] : '';
     $country_id = isset($params['country_id']) ? $params['country_id'] : 0;


  	$this->db->select("tc_country_ID AS country_id,tc_country_name AS country_name,tc_country_code");
  	$this->db->from('tbl_countries_masters');
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
            $this->db->UPDATE('tbl_countries_masters',$data);
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
          $this->db->insert('tbl_countries_masters',$data);
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
        $this->db->UPDATE('tbl_countries_masters',$data);
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
