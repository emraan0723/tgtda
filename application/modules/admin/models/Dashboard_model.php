<?php
class Dashboard_model extends CI_model
{
  public function __construct($value='')
  {
    parent::__construct();
    $this->load->database();
    $CI = &get_instance();
     # $this->db2 = $this->load->database('old_emr',TRUE);
    if(date("n") >= 4)
    {
      $this->year_start_date = date("Y")."-01-01";
      $this->year_last_date = (date("Y")+1)."-12-31";
    }
    else
    {
      $this->year_start_date = (date("Y")-1)."-01-01";
      $this->year_last_date = date("Y")."-12-31";
    }


    if(date('d') >='25' && date('d') <='31')
    {
      $this->privous_month =date("Y-m-25");
      $this->current_month =date("Y-m-d"); 
    }
    else
    {
      $this->privous_month =date("Y-m-25",strtotime("-1 month"));
      $this->current_month =date("Y-m-24"); 
    }

  }


  public function totalCustomers($params = array())
  {
   $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
   $this->db->select("tc_customer_ID");
   $this->db->from('tbl_customers');
   $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
   if($currency_id > 0)
     $this->db->where("tc_currency_id",$currency_id);
   else
     $this->db->where("tc_currency_id","45");



   $query = $this->db->get();
      //echo $this->db->last_query();exit;
   $result = 0; 
   if($query->num_rows() > 0)
   {
    $result  =$query->num_rows();
    return $result;
  }
  else
  {
    return $result;
  }  
}

public function totalDueCustomers($params = array())
{
 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 $this->db->select("GROUP_CONCAT(tid_customer_name,' - ',MONTHNAME(tid_entry_date) SEPARATOR '<br/>' ) AS cus_name");
 $this->db->where("tipd_bill_status","DUE");
 $this->db->from('tbl_invoices_dataset');
 $this->db->join('tbl_invoices_product_details','tipd_invoice_id=tid_invoice_ID');
 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 if($currency_id > 0)
   $this->db->where("tid_currency_id",$currency_id);
 else
   $this->db->where("tid_currency_id","45");


 $from_date_id = isset($params['from_date_id']) ? $params['from_date_id'] :'';
 $to_date_id = isset($params['to_date_id']) ? $params['to_date_id'] :'';

 if($from_date_id !='' && $to_date_id !='')
 {
  $this->year_start_date = $from_date_id;
  $this->year_last_date = $to_date_id;
  
}

$this->db->where('tid_entry_date BETWEEN "'.$this->year_start_date. '" and "'.$this->year_last_date.'"');

$query = $this->db->get();
      //echo $this->db->last_query();exit;
$result = 0; 
if($query->num_rows() > 0)
{
  $result  =$query->row_array();
  return $result;
}
else
{
  return $result;
}  
}

public function totalActiveCustomers($params = array())
{
 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 $this->db->select("tc_customer_ID");
 $this->db->where("tc_status","ACTIVE");
 $this->db->from('tbl_customers');
 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 if($currency_id > 0)
   $this->db->where("tc_currency_id",$currency_id);
 else
   $this->db->where("tc_currency_id","45");

 $query = $this->db->get();
      //echo $this->db->last_query();exit;
 $result = 0; 
 if($query->num_rows() > 0)
 {
  $result  =$query->num_rows();
  return $result;
}
else
{
  return $result;
}  
}

public function totalAmount($params = array())
{
 $current_month =date('m');
 $this->db->select("SUM(tipd_final_amount) AS product_discount_amount");
 $this->db->from('tbl_invoices_dataset');
 $this->db->join('tbl_invoices_product_details','tipd_invoice_id=tid_invoice_ID');


 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 if($currency_id > 0)
   $this->db->where("tid_currency_id",$currency_id);
 else
   $this->db->where("tid_currency_id","45");

 $from_date_id = isset($params['from_date_id']) ? $params['from_date_id'] :'';
 $to_date_id = isset($params['to_date_id']) ? $params['to_date_id'] :'';

 if($from_date_id !='' && $to_date_id !='')
 {
  $this->year_start_date = $from_date_id;
  $this->year_last_date = $to_date_id;
  
}
$this->db->where('tid_entry_date BETWEEN "'.$this->year_start_date. '" and "'.$this->year_last_date.'"');


$query = $this->db->get();
     //echo $this->db->last_query();exit;
$result = 0; 
if($query->num_rows() > 0)
{
  $result  =$query->num_rows();
  $res =$query->row_array();
  return number_format(round($res['product_discount_amount']),2);
}
else
{
  return $result;
}  

}

public function totalMonthAmount($params = array())
{



 $current_month =date('m');
 $this->db->select("SUM(tipd_final_amount) AS product_discount_amount");
 $this->db->from('tbl_invoices_dataset');
 $this->db->join('tbl_invoices_product_details','tipd_invoice_id=tid_invoice_ID');
 $this->db->where("tipd_created_date BETWEEN '{$this->privous_month}' AND '{$this->current_month}'");
     //$this->db->where("MONTH(tipd_created_date)",$current_month);

 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 if($currency_id > 0)
   $this->db->where("tid_currency_id",$currency_id);
 else
   $this->db->where("tid_currency_id","45");

 $query = $this->db->get();

     // echo $this->db->last_query();exit;
 $result = 0; 
 if($query->num_rows() > 0)
 {
  $result  =$query->num_rows();
  $res =$query->row_array();
  return number_format(round($res['product_discount_amount']),2);
}
else
{
  return $result;
}  

}

public function totalCurrentmonthDue($params = array())
{
 $current_month =date('m');
 $this->db->select("SUM(tipd_final_amount) AS product_discount_amount");
 $this->db->from('tbl_invoices_dataset');
 $this->db->join('tbl_invoices_product_details','tipd_invoice_id=tid_invoice_ID');
 $this->db->where("tipd_bill_status",'DUE');
 $this->db->where("tipd_created_date BETWEEN '{$this->privous_month}' AND '{$this->current_month}'");
     //$this->db->where("MONTH(tipd_created_date)",$current_month);
 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 if($currency_id > 0)
   $this->db->where("tid_currency_id",$currency_id);
 else
   $this->db->where("tid_currency_id","45");
 $query = $this->db->get();
     //echo $this->db->last_query();exit;
 $result = 0; 
 if($query->num_rows() > 0)
 {
  $result  =$query->num_rows();
  $res =$query->row_array();
  return number_format(round($res['product_discount_amount']),2);
}
else
{
  return $result;
}  

}
public function totalCurrentmonthPaid($params = array())
{
 $current_month =date('m');
 $this->db->select("SUM(tipd_final_amount) AS product_discount_amount");
 $this->db->from('tbl_invoices_dataset');
 $this->db->join('tbl_invoices_product_details','tipd_invoice_id=tid_invoice_ID');
 $this->db->where("tipd_bill_status",'PAID');
 $this->db->where("tipd_created_date BETWEEN '{$this->privous_month}' AND '{$this->current_month}'");
     //$this->db->where("MONTH(tipd_created_date)",$current_month);
 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 if($currency_id > 0)
   $this->db->where("tid_currency_id",$currency_id);
 else
   $this->db->where("tid_currency_id","45");
 $query = $this->db->get();
      //echo $this->db->last_query();exit;
 $result = 0; 
 if($query->num_rows() > 0)
 {
  $result  =$query->num_rows();
  $res =$query->row_array();
  return number_format(round($res['product_discount_amount']),2);
}
else
{
  return $result;
}  

}


public function totalDue($params = array())
{
 $current_month =date('m');
 $this->db->select("SUM(tipd_final_amount) AS product_discount_amount");
 $this->db->from('tbl_invoices_dataset');
 $this->db->join('tbl_invoices_product_details','tipd_invoice_id=tid_invoice_ID');
 $this->db->where("tipd_bill_status",'DUE');
 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 if($currency_id > 0)
   $this->db->where("tid_currency_id",$currency_id);
 else
   $this->db->where("tid_currency_id","45");


 $from_date_id = isset($params['from_date_id']) ? $params['from_date_id'] :'';
 $to_date_id = isset($params['to_date_id']) ? $params['to_date_id'] :'';

 if($from_date_id !='' && $to_date_id !='')
 {
  $this->year_start_date = $from_date_id;
  $this->year_last_date = $to_date_id;
  
}

$this->db->where('tid_entry_date BETWEEN "'.$this->year_start_date. '" and "'.$this->year_last_date.'"');


$query = $this->db->get();

      //echo $this->db->last_query();exit;
$result = 0; 
if($query->num_rows() > 0)
{
  $result  =$query->num_rows();
  $res =$query->row_array();
  return number_format(round($res['product_discount_amount']),2);
}
else
{
  return $result;
}  

}

public function totalPaid($params = array())
{
 $current_month =date('m');
 $this->db->select("SUM(tipd_final_amount) AS product_discount_amount");
 $this->db->from('tbl_invoices_dataset');
 $this->db->join('tbl_invoices_product_details','tipd_invoice_id=tid_invoice_ID');
 $this->db->where("tipd_bill_status",'PAID');

 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 if($currency_id > 0)
   $this->db->where("tid_currency_id",$currency_id);
 else
   $this->db->where("tid_currency_id","45");

 $from_date_id = isset($params['from_date_id']) ? $params['from_date_id'] :'';
 $to_date_id = isset($params['to_date_id']) ? $params['to_date_id'] :'';

 if($from_date_id !='' && $to_date_id !='')
 {
  $this->year_start_date = $from_date_id;
  $this->year_last_date = $to_date_id;
  
}
$this->db->where('tid_entry_date BETWEEN "'.$this->year_start_date. '" and "'.$this->year_last_date.'"');


$query = $this->db->get();

      //echo $this->db->last_query();exit;
$result = 0; 
if($query->num_rows() > 0)
{
  $result  =$query->num_rows();
  $res =$query->row_array();
  return number_format(round($res['product_discount_amount']),2);
}
else
{
  return $result;
}  

}


public function totalMonthWisePaid($params = array())
{
 $product_id = isset($params['product_id']) ? $params['product_id'] :0;
 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 $month_date = isset($params['month_date']) ? $params['month_date'] :'';

 $current_month =date('m');
 $this->db->select("ROUND(SUM(tipd_final_amount)) AS product_discount_amount,tipd_bill_status,DATE_FORMAT(tipd_created_date, '%b') AS month_name,");
 $this->db->from('tbl_invoices_dataset');
 $this->db->join('tbl_invoices_product_details','tipd_invoice_id=tid_invoice_ID');

 if($product_id > 0)
   $this->db->where("tipd_product_id",$product_id);

 if($currency_id > 0)
   $this->db->where("tid_currency_id",$currency_id);
 else
   $this->db->where("tid_currency_id","45");


 if($from_date_id !='' && $to_date_id !='')
 {
  $this->year_start_date = $from_date_id;
  $this->year_last_date = $to_date_id;

}

$this->db->where('tid_entry_date BETWEEN "'.$this->year_start_date. '" and "'.$this->year_last_date.'"');


if($month_date !='')
 $this->db->where("tipd_created_date",$month_date);



$this->db->where("tipd_bill_status",'PAID');
$this->db->group_by("DATE_FORMAT(tipd_created_date, '%m'),tipd_bill_status");
$this->db->order_by("DATE_FORMAT(tipd_created_date, '%m') ASC");
$query = $this->db->get();
    // echo $this->db->last_query(); exit;
$result = 0; 
if($query->num_rows() > 0)
{
  $result  =$query->row_array();
  return $result;
}
else
{
  return $result;
}  

}

public function getMonths($params=array())
{

  $params['product_id'] = isset($params['product_id']) ? $params['product_id'] :0;
  $params['currency_id'] = isset($params['currency_id']) ? $params['currency_id'] :0;

  $current_month =date('m');
  $this->db->select("DATE_FORMAT(tipd_created_date, '%b') AS month_name,tipd_created_date");
  $this->db->from('tbl_invoices_dataset');
  $this->db->join('tbl_invoices_product_details','tipd_invoice_id=tid_invoice_ID');

  $from_date_id = isset($params['from_date_id']) ? $params['from_date_id'] :'';
  $to_date_id = isset($params['to_date_id']) ? $params['to_date_id'] :'';

  if($from_date_id !='' && $to_date_id !='')
  {
    $this->year_start_date = $from_date_id;
    $this->year_last_date = $to_date_id;
    
  }

  $this->db->where('tid_entry_date BETWEEN "'.$this->year_start_date. '" and "'.$this->year_last_date.'"');



  $this->db->group_by("DATE_FORMAT(tipd_created_date, '%m')");
  $this->db->order_by("DATE_FORMAT(tipd_created_date, '%m') ASC");
  $query = $this->db->get();

      //echo $this->db->last_query(); exit;

  $data = array();
  $result = 0; 
  $i=0;
  if($query->num_rows() > 0)
  {
    $result  =$query->result_array();

    foreach ($result as $key => $value) 
    {
      $params['month_date'] = isset($value['tipd_created_date']) ? $value['tipd_created_date'] :0;

      $data[$value['month_name']]['PAID'] =$this->totalMonthWisePaid($params); 
      $data[$value['month_name']]['DUE'] =$this->totalMonthWiseDue($params); 

    }


    return $data;
  }
  else
  {
    return $result;
  }  
}


public function totalMonthWiseDue($params = array())
{
  $product_id = isset($params['product_id']) ? $params['product_id'] :0;
  $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
  $month_date = isset($params['month_date']) ? $params['month_date'] :'';

  $current_month =date('m');
  $this->db->select("ROUND(SUM(tipd_final_amount)) AS product_discount_amount,GROUP_CONCAT(tid_customer_name) AS cus_name,tipd_bill_status,DATE_FORMAT(tipd_created_date, '%b') AS month_name,");
  $this->db->from('tbl_invoices_dataset');
  $this->db->join('tbl_invoices_product_details','tipd_invoice_id=tid_invoice_ID');

  $this->db->where("tipd_bill_status",'DUE');

  $from_date_id = isset($params['from_date_id']) ? $params['from_date_id'] :'';
  $to_date_id = isset($params['to_date_id']) ? $params['to_date_id'] :'';

  if($from_date_id !='' && $to_date_id !='')
  {
    $this->year_start_date = $from_date_id;
    $this->year_last_date = $to_date_id;

  }

  $this->db->where('tid_entry_date BETWEEN "'.$this->year_start_date. '" and "'.$this->year_last_date.'"');


  if($product_id > 0)
   $this->db->where("tipd_product_id",$product_id);

 if($currency_id > 0)
   $this->db->where("tid_currency_id",$currency_id);
 else
   $this->db->where("tid_currency_id","45");

 if($month_date !='')
   $this->db->where("tipd_created_date",$month_date);

 $this->db->group_by("DATE_FORMAT(tipd_created_date, '%m')");
 $this->db->order_by("DATE_FORMAT(tipd_created_date, '%m') ASC");
 $query = $this->db->get();

     //echo $this->db->last_query(); exit;
 $result = 0; 
 if($query->num_rows() > 0)
 {
  $result  =$query->row_array();
  return $result;
}
else
{
  return $result;
}  

}

public function amcDashboard($params = array())
{
 $product_id = isset($params['product_id']) ? $params['product_id'] :0;
 $currency_id = isset($params['currency_id']) ? $params['currency_id'] :0;
 $month_date = isset($params['month_date']) ? $params['month_date'] :'';

 $current_month =date('m');
 $this->db->select("tas_amc_customer_name,tas_amc_customer_id,tas_amc_customer_code,tas_amc_paid_date,  tas_amc_next_due_date,tas_amc_service_date,tas_amc_amount");
 $this->db->from('tbl_amc_setup');

 if($product_id > 0)
   $this->db->where("tipd_product_id",$product_id);

 $this->db->where('tas_amc_next_due_date BETWEEN CURRENT_DATE() AND CURRENT_DATE() + INTERVAL 90 DAY');

 $query = $this->db->get();
    // echo $this->db->last_query(); exit;
 $result = 0; 
 if($query->num_rows() > 0)
 {
  $result  =$query->result_array();
  return $result;
}
else
{
  return $result;
}  

}



}
?>
