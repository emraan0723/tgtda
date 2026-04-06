<?php
class Comman_model extends CI_model
{
  public function __construct($value='')
  {
      parent::__construct();
      $this->load->database();
      $CI = &get_instance();
    
  }

   #Get Categorys 
  public function getCategoryList($params=array())
  {
    $customer_code = isset($params['customer_code']) ? $params['customer_code'] : '';
    $category_id = isset($params['category_id']) ? $params['category_id'] : 0;
    $this->db->select("category_id AS category_id,
    (CASE 
    WHEN category_max_range > 0 
    THEN
    CONCAT('< ',category_max_range,' ',category_name)
    ELSE category_name
    END) AS category_name
    ,category_status");
    $this->db->from('tbl_category_master');
    $this->db->where("category_status",'ACTIVE'); 
    if($category_id > 0)
        $this->db->where("category_id",$category_id); 
    $this->db->order_by("category_max_range", "ASC");  
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

  #GET TAX DETAILS GST,AMC
   public function getTaxList($params=array())
  {
    $tax_type = isset($params['tax_type']) ? $params['tax_type'] : '';
    $tax_id = isset($params['tax_id']) ? $params['tax_id'] : 0;

    $this->db->select("tt_tax_ID,tax_type,tt_tax_percentage");
    $this->db->from("tbl_tax_master");
    $this->db->where("tt_status",'ACTIVE'); 
    if($tax_id > 0)
        $this->db->where("tt_tax_ID",$tax_id); 
    if($tax_type !='')
        $this->db->where("tax_type",$tax_type);   
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

  


   #Get  Customers 
  public function getCustomerList($params=array())
  {
    $customer_code = isset($params['customer_code']) ? $params['customer_code'] : '';
    $customer_id = isset($params['customer_id']) ? $params['customer_id'] : 0;
    $this->db->select("tc_customer_ID AS customer_id,tc_customer_business_name AS customer_name,tc_customer_code AS customer_code,tc_currency_id,tc_currency_short_name,tc_currency_symbol,tc_currency_name");
    $this->db->from('tbl_customers');
    $this->db->where("tc_status",'ACTIVE'); 
    if($customer_code !='')
       $this->db->where("tc_customer_code",$customer_code);
    if($customer_id > 0)
        $this->db->where("tc_customer_ID",$customer_id); 
    $this->db->order_by("tc_customer_business_name", "ASC");  
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

  #getCustomerProductList
  public function getCustomerProductsList($params=array())
  {
    $customer_id = isset($params['customer_id']) ? $params['customer_id'] : 0;
    $product_id = isset($params['product_id']) ? $params['product_id'] : 0;
    $customer_name = isset($params['customer_name']) ? $params['customer_name'] : 0;
    $this->db->select("tpca_customer_ID AS customer_id,tpca_product_ID AS product_id,tpca_currency_id AS currency_id,tpca_ID AS product_assign_id,tc_customer_business_name,tc_customer_code,tp_product_name,tp_product_code,tp_product_description,tpca_amount,tpca_payment_type,DATE_FORMAT(tpca_invoice_date, '%d-%m-%Y') AS tpca_invoice_date,tpca_status,tcm_currency_name,tcm_currency_symbol,tcm_currency_short_name,DATE_FORMAT(tpca_expiry_date, '%d-%m-%Y') AS expiry_date ,tc_email AS customer_email");
     $this->db->from('tbl_products_customer_assign');
     $this->db->join('tbl_customers','tc_customer_ID=tpca_customer_ID');
     $this->db->join('tbl_product','tp_product_ID=tpca_product_ID');
      $this->db->join('tbl_currency_master','tcm_currency_ID=tpca_currency_id');
    $this->db->where("tpca_status",'ACTIVE'); 
   if($customer_id > 0)
        $this->db->where("tpca_customer_ID",$customer_id); 
   if($product_id > 0)
        $this->db->where("tpca_product_ID",$product_id);

    #Customer Name
    if($customer_name !='')
    {
        $this->db->like('tc_customer_business_name',$customer_name);
        $this->db->group_by("tpca_customer_ID");

        if($ajax > 0)
        {
          $this->db->limit(10);
        }
    }

    $this->db->order_by("tp_product_name", "ASC");  
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }



   #Get  Products 
  public function getProductList($params=array())
  {
    $product_code = isset($params['product_code']) ? $params['product_code'] : '';
    $product_id = isset($params['product_id']) ? $params['product_id'] : 0;
    $this->db->select("tp_product_ID AS product_id,tp_product_name AS product_name,tp_product_code AS product_code,tp_Installation_charges,tp_base_price,tp_base_price AS total_amount");
    $this->db->from('tbl_product');
    $this->db->where("tp_status",'ACTIVE'); 
    if($product_code !='')
       $this->db->where("tp_product_code",$product_code);
    if($product_id > 0)
        $this->db->where("tp_product_ID",$product_id); 
    $this->db->order_by("tp_product_name", "ASC");    
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

   #Get  Products 
  public function getCurrencyList($params=array())
  {
   
    $currency_id = isset($params['currency_id']) ? $params['currency_id'] : 0;
    $this->db->select("tcm_currency_ID AS currency_id,tcm_currency_short_name AS currency_short_name, tcm_currency_name AS currency_name,tcm_currency_symbol AS symbol");
    $this->db->from('tbl_currency_master');
    $currency = array('INR','USD','EUR');
    $this->db->where_in("tcm_currency_short_name",$currency); 
    $this->db->where("tcm_status",'ACTIVE'); 
    if($currency_id > 0)
        $this->db->where("tcm_currency_ID",$currency_id);
    $this->db->order_by("tcm_currency_name", "ASC");           
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }
 
 
  #Get  Countries 
  public function getCountryList($params=array())
  {

    $country_name = isset($params['country_name']) ? $params['country_name'] : '';
    $country_code = isset($params['country_code']) ? $params['country_code'] : '';
    $country_id = isset($params['country_id']) ? $params['country_id'] : 0;
    $this->db->select("tc_country_ID AS country_id,tc_country_name AS country_name,tc_country_code");
    $this->db->from('tbl_countries_masters');
    $this->db->where("tc_country_status",'ACTIVE'); 
    if($country_name !='')
       $this->db->where("tc_country_name",$country_name);
    if($country_code !='')
        $this->db->where("tc_country_code",$country_code);
    if($country_id > 0)
        $this->db->where("tc_country_ID",$country_id);

     $this->db->order_by("tc_country_name", "ASC");      
    $query = $this->db->get();
    //echo $this->db->last_query(); exit;
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }


   public function getStateList($params=array())
  {
    //Write_to_file("bv111111111111",'mytesting');
    $state_name = isset($params['state_name']) ? $params['state_name'] : '';
    $code = isset($params['code']) ? $params['code'] : '';
    $state_id = isset($params['state_id']) ? $params['state_id'] : 0;
    $country_id = isset($params['country_id']) ? $params['country_id'] : 0;
    $this->db->select("ts_state_ID AS state_id,ts_state_name AS state_name,ts_state_code AS code");
    $this->db->from('tbl_state_masters');
    $this->db->where("ts_status",'ACTIVE'); 
    if($state_name !='')
       $this->db->where("ts_state_name",$state_name);
    if($code !='')
        $this->db->where("ts_state_code",$code);
    if($state_id > 0)
        $this->db->where("ts_state_ID",$state_id); 
    if($country_id > 0)
       $this->db->where("ts_country_id",$country_id);

    $this->db->order_by("ts_state_name", "ASC");      
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

   public function getDistrictList($params=array())
  {
    $district_name = isset($params['district_name']) ? $params['district_name'] : '';
    $code = isset($params['district_code']) ? $params['district_code'] : '';
    $state_id = isset($params['state_id']) ? $params['state_id'] : 0;
    $country_id = isset($params['country_id']) ? $params['country_id'] : 0;
    $district_id = isset($params['district_id']) ? $params['district_id'] : 0;
    $this->db->select("tdt_district_ID AS district_id,tdt_district_name AS district_name,tdt_district_code AS code");
    $this->db->from('tbl_district_masters');
    $this->db->where("tdt_status",'ACTIVE'); 
    if($district_name !='')
       $this->db->where("tdt_district_name",$district_name);
    if($code !='')
        $this->db->where("tdt_district_code",$code);
    if($district_id > 0)
        $this->db->where("tdt_district_ID",$district_id); 
    if($state_id > 0)
        $this->db->where("tdt_state_ID",$state_id);   
    if($country_id > 0)
       $this->db->where("tdt_country_ID",$country_id);

    $this->db->order_by("tdt_district_name", "ASC");  
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

  public function getCitys($params=array())
  {
    $city_name = isset($params['tc_city_name']) ? $params['tc_city_name'] : '';
    $city_id = isset($params['state_id']) ? $params['state_id'] : 0;
    $state_id = isset($params['state_id']) ? $params['state_id'] : 0;
    $country_id = isset($params['country_id']) ? $params['country_id'] : 0;
    $district_id = isset($params['district_id']) ? $params['district_id'] : 0;
    $this->db->select("tc_city_ID AS city_id,tc_city_name AS city_name,tc_city_status AS city_status");
    $this->db->from('tbl_city_masters');
    $this->db->where("tc_city_status",'ACTIVE'); 
    if($city_name !='')
       $this->db->where("tc_city_name",$district_name);
    if($city_id > 0)
        $this->db->where("tc_city_ID",$city_id);  
    if($district_id > 0)
        $this->db->where("tc_city_district_ID",$district_id); 
    if($state_id > 0)
        $this->db->where("tc_city_state_ID",$state_id);   
    if($country_id > 0)
       $this->db->where("tc_city_country_ID",$country_id);

    $this->db->order_by("city_name", "ASC");  
    $query = $this->db->get();
    $data['query'] = $query;
    $data['isexists_insert'] = $query->num_rows();
    return $data;
  }

   public function getCategoryProductAmount($params=array())
   {

      $payment_type = isset($params['payment_type']) ? trim($params['payment_type']) : '';
      $product_id = isset($params['product_id']) ? $params['product_id'] : 0;
      $category_id = isset($params['category_id']) ? $params['category_id'] : 0;
      $currency_id = isset($params['currency_id']) ? $params['currency_id'] : 0;

      $sql = '';
      if($payment_type =='Monthly')
      {
        $sql .=",ROUND(tsm_monthely_cost) AS amount";
      }
      else
      if($payment_type =='Quarterly')
      {
        $sql .=",ROUND(tsm_quarterly_cost) AS amount";
      }
      else
      if($payment_type =='Half-Yearly')
      {
        $sql .=",ROUND(tsm_halfyearly_cost) AS amount";
      } 
      else
      if($payment_type =='Yearly')
      {
        $sql .=",ROUND(tsm_yearly_cost) AS amount";
      }
      
    
      $this->db->select("tsm_currency_id AS currency_id,
        tsm_currency_name AS currency_name,
        tsm_currency_short_name AS currency_short_name,
        tsm_tsm_currency_symbol AS currency_symbol,
        tsm_category_id AS category_id,
        tsm_category_name AS category_name,
        tsm_category_value AS category_value,
        category_min_range ,
        ROUND(tsm_monthely_cost) AS per_month_product_cost,
        tsm_footfalls_per_month AS footfalls_per_month,
        tsm_cost_per_patient AS cost_per_patient,
         $sql
        ");
      $this->db->from('tbl_subscription_master');
      $this->db->join('tbl_category_master','category_id=tsm_category_id AND category_status ="ACTIVE"');
      $this->db->join('tbl_product_category_maping','tpcm_category_id=category_id AND tpcm_status ="ACTIVE"');
      $this->db->where("tsm_status",'ACTIVE'); 
      $this->db->where("tpcm_product_id",$product_id);  
      $this->db->where("tsm_category_id",$category_id); 
      $this->db->where("tsm_currency_id",$currency_id);   
      $query = $this->db->get();
      $data['query'] = $query;
      $data['isexists_insert'] = $query->num_rows();
       return $data;
    }


  public function UploadImagePic($params= array())
  {
      $data['tupp_source_id'] =isset($params['pic_id']) ? $params['pic_id'] : 0;
      $data['tupp_source'] =isset($params['from_source']) ? $params['from_source'] : '';
      $data['tupp_created_date']= date('Y-m-d');
      $data['tupp_created_time'] = date('h:i A');
      $data['tupp_ip_address']= $_SERVER['REMOTE_ADDR'];
      $data['tupp_filename']= isset($params['filename']) ? $params['filename'] : '';

      if($data['tupp_source_id'] > 0 && $data['tupp_source'] !='')
      {

        $this->db->select("tupp_source_id");
        $this->db->from('tbl_upload_profile_pics');
        $this->db->where("tupp_source_id",$data['tupp_source_id']);
        $this->db->where("tupp_source",$data['tupp_source']);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        { 
          $this->db->where("tupp_source_id",$data['tupp_source_id']);
          $this->db->where("tupp_source",$data['tupp_source']);
          $this->db->UPDATE('tbl_upload_profile_pics',$data);

        }
        else
        {
           $this->db->insert('tbl_upload_profile_pics', $data); 
        }
       // echo $this->db->last_query();
        if(!$this->db->affected_rows()) 
        {
          return 'INSERT_FAILED';
          exit;

        }

        else
        {
          return 'INSERT_SUCCESSFULLY';
            exit;
        }

      }
      else
      {
        return 'INVALID_DATA';
        exit;
      }

       
    }

    public function PasswordValidation($params=array())
    {
       $admin_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
       $password = isset($params['password']) ?  $params['password'] :'';

       //echo  $password; exit;
      if($admin_id > 0 && $password !='')
      {
          $this->db->select('tu_password');
          $this->db->from('tbl_users');
          $this->db->where('tu_ID',$admin_id);
           $this->db->where('tu_status','ACTIVE');
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

    public function ActiveInactiveResonsSave($params=array())
    {
      $data['tiar_admin_id'] =isset($params['admin_id']) ? $params['admin_id'] : 0;
      $data['tiar_customer_id'] =isset($params['customer_id']) ? $params['customer_id'] : 0;
      $data['tiar_product_id'] =isset($params['product_id']) ? $params['product_id'] : 0;
      $data['tiar_assign_product_id'] =isset($params['product_assign_id']) ? $params['product_assign_id'] : 0;
      $data['tiar_discounts_id'] =isset($params['discounts_id']) ? $params['discounts_id'] : 0;
      $data['tiar_category_master_id'] =isset($params['category_id']) ? $params['category_id'] : 0;
      $data['tiar_category_product_maping_id'] =isset($params['category_prodcut_id']) ? $params['category_prodcut_id'] : 0;
      $data['tiar_status'] =isset($params['status']) ? $params['status'] :'';
      $data['tiar_reason'] =isset($params['reason']) ? $params['reason'] : '';
      $data['tiar_created_by'] =isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
      $data['tiar_created_by_name'] =isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
      $data['tiar_created_date']= date('Y-m-d');
      $data['tiar_created_time'] = date('h:i A');
      $data['tiar_ip_address']= $_SERVER['REMOTE_ADDR'];

      $this->db->insert('tbl_inactive_active_resons', $data); 
      if($this->db->affected_rows() > 0) 
      {
        return 'INSERT_SUCCESSFULLY';
        exit;

      }
      else
      {
         return 'INSERT_FAILED';
         exit;
      }

    
        
    }

  
}
?>
