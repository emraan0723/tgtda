<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Validationdigi
{

	function __construct() 
	{
		$this->CI =& get_instance();
	}

	

	public $alpha= '/^([A-Za-z]+)$/';
	public $alpha_s=  '/^([A-Za-z\ ]+)$/';
	public $num= '/^([0-9]+)$/';
	public $num_s= '/^([0-9\ ]+)$/';
	public $decimal= '/^[0-9]+(\.[0-9]{2})?$/';
	public $alphanum=  '/^([A-Za-z0-9]+)$/';
	public $alphanum_s=  '/^([A-Za-z0-9\ ]+)$/';
	public $no_spaces=  '/^([^\ ]+)$/';
	public $mobile = '/^[0-9]{10}$/';
	public $email = '/([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{2,4}/';
	public $username = '/^([A-Za-z0-9\d=_]+)$/';
	public $password = '/^(?=.*[a-z])[A-Za-z0-9\d=!\-@._*]+$/';
	//public $datevalidation=  '/^([0-9]{2})([^A-Za-z0-9]{1})([0-9]{2})'.'([^A-Za-z0-9]{1})([0-9]{4})$/';


	public function admin()
	{
		if(isset($_POST['first_name']))
			$this->CI->form_validation->set_rules('first_name', 'First Name', "trim|required|min_length[2]|max_length[100]|regex_match[$this->alpha_s]"); 
		
		if(isset($_POST['last_name']))
			$this->CI->form_validation->set_rules('last_name', 'Last Name', "trim|required|min_length[2]|max_length[100]|regex_match[$this->alpha_s]");

		if(isset($_POST['gender']))
			$this->CI->form_validation->set_rules('gender', 'Gender', "trim|required|regex_match[$this->alpha]");

		if(isset($_POST['mobile']))
			$this->CI->form_validation->set_rules('mobile', 'Gender', "trim|required|regex_match[$this->mobile]");

		if(isset($_POST['email']))
			$this->CI->form_validation->set_rules('email', 'Email', "trim|required|min_length[8]|max_length[255]|regex_match[$this->email]");

		if(isset($_POST['address']))
			$this->CI->form_validation->set_rules('address', 'Address', "trim|required");

		if(isset($_POST['username']))
			$this->CI->form_validation->set_rules('username', 'Username', "trim|required|min_length[6]|max_length[200]|regex_match[$this->email]");

		if(isset($_POST['password']))
			$this->CI->form_validation->set_rules('password', 'Password', "trim|min_length[6]|max_length[300]|required|regex_match[$this->password]");

	}


	public function customer()
	{
		if(isset($_POST['customer_name']))
			$this->CI->form_validation->set_rules('customer_name', 'Customer Name', "trim|required|min_length[3]|max_length[200]|regex_match[$this->alpha_s]"); 
		if(isset($_POST['customer_code']))
			$this->CI->form_validation->set_rules('customer_code', 'Customer Code', "trim|required|min_length[2]|max_length[100]|regex_match[$this->alpha]");

		if(isset($_POST['gender']))
			$this->CI->form_validation->set_rules('gender', 'Gender', "trim|required|regex_match[$this->alpha]");

		if(isset($_POST['customer_mobile']))
			$this->CI->form_validation->set_rules('customer_mobile', 'Mobile', "trim|required|regex_match[$this->mobile]");

		if(isset($_POST['phone']))
			$this->CI->form_validation->set_rules('phone', 'Phone', "trim|required");

		if(isset($_POST['cus_email']))
			$this->CI->form_validation->set_rules('cus_email', 'Email', "trim|required|min_length[8]|max_length[255]|regex_match[$this->email]");

		if(isset($_POST['country_id']))
			$this->CI->form_validation->set_rules('country_id', 'Country', "trim|required"); 

		if(isset($_POST['currency_id']))
			$this->CI->form_validation->set_rules('currency_id', 'Currency', "trim|required"); 

		

		/*if(isset($_POST['state_id']))
			$this->CI->form_validation->set_rules('state_id', 'State', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['district_id']))
			$this->CI->form_validation->set_rules('district_id', 'District', "trim|required|regex_match[$this->num]"); */
			
		/*if(isset($_POST['city_id']))
			$this->CI->form_validation->set_rules('city_id', 'City', "trim|required|regex_match[$this->num]"); */

		

		if(isset($_POST['address']))
			$this->CI->form_validation->set_rules('address', 'Address', "trim|required");

		/*if(isset($_POST['customer_gst_nos']))
			$this->CI->form_validation->set_rules('customer_gst_nos', 'Customer GST No ', "trim|required");*/
		

	}	
		

	public function country()
	{
		if(isset($_POST['country_name']))
			$this->CI->form_validation->set_rules('country_name', 'Country Name', "trim|required|min_length[3]|max_length[100]|regex_match[$this->alpha_s]"); 
		/*if(isset($_POST['country_code']))
			$this->CI->form_validation->set_rules('country_code', 'Country Code', "trim|required|min_length[2]|max_length[5]|regex_match[$this->alpha]"); */
		
	}

	public function state()
	{
		if(isset($_POST['country_id']))
			$this->CI->form_validation->set_rules('country_id', 'Country', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['state_name']))
			$this->CI->form_validation->set_rules('state_name', 'State Name', "trim|required|min_length[3]|max_length[100]|regex_match[$this->alpha_s]"); 
		/*if(isset($_POST['state_code']))
			$this->CI->form_validation->set_rules('state_code', 'State Code', "trim|required|min_length[2]|max_length[5]|regex_match[$this->alpha]"); */
		
		
		
	}

	public function district()
	{
		if(isset($_POST['country_id']))
			$this->CI->form_validation->set_rules('country_id', 'Country', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['state_id']))
			$this->CI->form_validation->set_rules('country_id', 'State', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['district_name']))
			$this->CI->form_validation->set_rules('district_name', 'District Name', "trim|required|min_length[3]|max_length[100]|regex_match[$this->alpha_s]"); 
		/*if(isset($_POST['district_code']))
			$this->CI->form_validation->set_rules('district_code', 'District Code', "trim|required|min_length[2]|max_length[5]|regex_match[$this->alpha]"); */
		
		
		
	}

	public function currency()
	{
		/*if(isset($_POST['country_id']))
			$this->CI->form_validation->set_rules('country_id', 'Country', "trim|required|regex_match[$this->num]"); */
		if(isset($_POST['currency_name']))
			$this->CI->form_validation->set_rules('currency_name', 'Currency Name', "trim|required|min_length[3]|max_length[100]|regex_match[$this->alpha_s]");
		
	}

	public function product()
	{
		if(isset($_POST['product_name']))
			$this->CI->form_validation->set_rules('product_name', 'Product name', "trim|required|min_length[3]|max_length[200]|regex_match[$this->alpha_s]");
		if(isset($_POST['product_code']))
			$this->CI->form_validation->set_rules('product_code', 'Product Code', "trim|required|min_length[2]|max_length[5]|regex_match[$this->alpha]");
		if(isset($_POST['product_desc']))
			$this->CI->form_validation->set_rules('product_desc', 'Product Description', "trim|required");
		if(isset($_POST['product_code']))
			$this->CI->form_validation->set_rules('product_code', 'Installation Charges', "trim|required");
		/*if(isset($_POST['install_charge']))
			$this->CI->form_validation->set_rules('base_price', 'Base Price', "trim|required");*/
		
	}

	public function productAsign()
	{
		if(isset($_POST['customer_id']))
			$this->CI->form_validation->set_rules('customer_id', 'Customer', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['product_id']))
			$this->CI->form_validation->set_rules('product_id', 'Products', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['category_id']))
			$this->CI->form_validation->set_rules('category_id', 'Category', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['currency_id']))
			$this->CI->form_validation->set_rules('currency_id', 'Currency', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['payment_type']))
			$this->CI->form_validation->set_rules('payment_type', 'Payment Type', "trim|required");
		if(isset($_POST['product_amt']))
			$this->CI->form_validation->set_rules('product_amt', 'Product Amount', "trim|required");
		if(isset($_POST['service_start_date']))
			$this->CI->form_validation->set_rules('service_start_date', 'Start Date', "trim|required");
		
	}

	public function discounts()
	{
		if(isset($_POST['customer_id']))
			$this->CI->form_validation->set_rules('customer_id', 'Customer', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['product_id']))
			$this->CI->form_validation->set_rules('product_id', 'Products', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['discount_amount']))
			$this->CI->form_validation->set_rules('discount_amount', 'Dicount Amount', "trim|required");
		if(isset($_POST['discount_start_date']))
			$this->CI->form_validation->set_rules('discount_start_date', 'Discount Validity From date', "trim|required");
		if(isset($_POST['discount_expiry_date']))
			$this->CI->form_validation->set_rules('discount_expiry_date', 'Discount Validity To date', "trim|required");
			if(isset($_POST['discount_desc']))
			$this->CI->form_validation->set_rules('discount_desc', 'Discount Description', "trim|required"); 

		
	}		

	public function invoicesetup()
	{
		if(isset($_POST['customer_id']))
			$this->CI->form_validation->set_rules('customer_id', 'Customer', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['invoice_type']))
			$this->CI->form_validation->set_rules('invoice_type', 'Invoice type', "trim|required|min_length[3]|max_length[100]|regex_match[$this->alpha]");
		
	}


	public function miscellaneous()
	{
		if(isset($_POST['customer_name']))
			$this->CI->form_validation->set_rules('customer_name', 'Customer Name', "trim|required|min_length[3]|max_length[200]|regex_match[$this->alpha_s]"); 
		if(isset($_POST['customer_code']))
			$this->CI->form_validation->set_rules('customer_code', 'Customer Code', "trim|required|min_length[2]|max_length[100]|regex_match[$this->alpha]");
		if(isset($_POST['customer_id']))
			$this->CI->form_validation->set_rules('customer_id', 'Customer', "trim|required|regex_match[$this->num]"); 
		if(isset($_POST['title_name']))
			$this->CI->form_validation->set_rules('title_name', 'Title name', "trim|required");
		if(isset($_POST['description']))
			$this->CI->form_validation->set_rules('description', 'Description', "trim|required");
		if(isset($_POST['pamount']))
			$this->CI->form_validation->set_rules('pamount', 'Product Amount', "trim|required");
		if(isset($_POST['total_amt']))
			$this->CI->form_validation->set_rules('total_amt', 'Total Amount', "trim|required");

		
		if(isset($_POST['invoice_email_address']))
			$this->CI->form_validation->set_rules('invoice_email_address', 'Invoice Email-Address', "trim|required");

		if(isset($_POST['invoice_emailer_name']))
			$this->CI->form_validation->set_rules('invoice_emailer_name', 'Invoice Emailer Name', "trim|required");

		if(isset($_POST['key_id']))
			$this->CI->form_validation->set_rules('key_id', 'Not Send Email', "trim|required");


		
	}
					
}

