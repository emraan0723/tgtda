<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    /* ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL);*/
class Comman extends MX_Controller 
{
	function __construct() 
	{
	    parent::__construct();
	    $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption'));
	    $this->load->model('Comman_model');
        $this->session_check->check_session();
	}

	public function index()
	{
		

	}

	//Get Assigned product customer deatils
    public function getAssignedCustomerList()
    {
    	$params = array();
    	$params['customer_name'] = isset($_POST['customer_name']) ? $_POST['customer_name'] : '';
    	$params['ajax'] = 1;
         if($params['customer_name'] !='')
         {
         	  $res = $this->Comman_model->getCustomerProductsList($params);

			if(isset($res['query']))
			{
				$result =$res['query']->result_array();
				$data = array();
				if($res['query']->num_rows() > 0 && count($result) > 0)
				{
					$html ='<ul id="autosearch_id">';
					foreach ($result as $value) 
					{


					$html .='<li onClick="selectCustomerName(\''.$value["customer_id"].'\',\''.$value['tc_customer_code'].'\',\''.$value['tc_customer_business_name'].'\',\''.$value['tcm_currency_short_name'].'\')">'.ucwords(strtolower($value["tc_customer_business_name"])).'</li>';

					}
					$html .='</ul>';
					echo $html;
				}
				else
				{
					echo 0;
				}


			}
      
	   }
	  

           
    }

    #USE CUSTOME VIEW AND INVOICE SET UP
	public function getProductCustomerList()
	{
		$params = array();
		$res = array();

		$customer_id =isset($_POST['customer_id'])?$this->db->escape_str(trim($_POST['customer_id'])):''; 
		//$params['customer_id'] = $this->encryption->decrypt("$customer_id");
		$params['customer_id'] = $customer_id;
		$res = $this->Comman_model->getCustomerProductsList($params);
		if(isset($res['query']))
		{
			$result =$res['query']->result_array();
			$data = array();
			if($res['query']->num_rows() > 0 && count($result) > 0)
			{
				$i=0;
				foreach ($result as $value) 
				{
					
					$data[$i]['product_id'] =$value['product_id'];
					$data[$i]['product_name'] =ucwords(strtolower($value['tp_product_name']));
					$data[$i]['product_code'] =$value['tp_product_code'];
					$data[$i]['product_description'] =ucwords(strtolower($value['tp_product_description']));
					$data[$i]['customer_email'] =ucwords(strtolower($value['customer_email']));
					
					$data[$i]['amount'] =$value['tpca_amount'];
					$i++;
				}
			}
			else
			{
				$data ='';
			}
		}		

		echo json_encode($data);

	}


	public function getStates()
	{
		$params = array();
		$res = array();
		$params['country_id'] = isset($_POST['country_id']) ? $_POST['country_id'] : 0;
		$res = $this->Comman_model->getStateList($params);

		if(isset($res['query']))
		{
			$result =$res['query']->result_array();


			$data = array();
			if($res['query']->num_rows() > 0 && count($result) > 0)
			{
				foreach ($result as $value) 
				{
					$data[$value['state_name']] =ucwords(strtolower($value['state_id']));
				  	
				}
			}
			else
			{
				$data = 0;
			}
		}	

		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($data);

	}

	public function getDisticts()
	{
		$params = array();
		$res = array();
		$params['state_id'] = isset($_POST['state_id']) ? $_POST['state_id'] : 0;
		$res = $this->Comman_model->getDistrictList($params);
		if(isset($res['query']))
		{
			$result =$res['query']->result_array();
			$data = array();
			if($res['query']->num_rows() > 0 && count($result) > 0)
			{
				foreach ($result as $value) 
				{
					$data[$value['district_name']] =ucwords(strtolower($value['district_id']));
				  	
				}
			}
			else
			{
				$data = 0;
			}
		}		

		echo json_encode($data);

		
	}

	public function getCitys()
	{
		$params = array();
		$res = array();
		$params['district_id'] = isset($_POST['district_id']) ? $_POST['district_id'] : 0;
		$params['country_id'] = isset($_POST['country_id']) ? $_POST['country_id'] : 0;
		$res = $this->Comman_model->getCitys($params);
		//echo $this->db->last_query(); exit;
		if(isset($res['query']))
		{
			$result =$res['query']->result_array();
			$data = array();
			if($res['query']->num_rows() > 0 && count($result) > 0)
			{
				foreach ($result as $value) 
				{
					$data[$value['city_name']] =ucwords(strtolower($value['city_id']));
				  	
				}
			}
			else
			{
				$data = 0;
			}
		}		

		echo json_encode($data);

		
	}

	public function getProductAmount()
	{
		$params = array();
		$res = array();
		$params['product_id'] = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
		$res = $this->Comman_model->getProductList($params);
		if(isset($res['query']))
		{
			$result =$res['query']->row_array();
			$data = array();
			if($res['query']->num_rows() > 0 && count($result) > 0)
			{
				$data=$result['total_amount'];
			}
			else
			{
				$data = 0;
			}
		}		

		echo json_encode($data);

		
	}

	 public function getCustomerCurrency()
	{
		$params = array();
		$res = array();
		$params['customer_id'] = isset($_POST['customer_id']) ? $_POST['customer_id'] : 0;
		$res = $this->Comman_model->getCustomerList($params);
		if(isset($res['query']))
		{
			$result =$res['query']->result_array();
			$data = array();
			$html_opton = '';
			$html_opton .= '<option value=""></option>';
			if($res['query']->num_rows() > 0 && count($result) > 0)
			{
				foreach ($result as $value) 
				{
					$html_opton .= '<option value="'.$value['tc_currency_id'].'">'.$value['tc_currency_short_name'].'('.$value['tc_currency_symbol'].')'.'</option>';

					
					

				  	
				}
			}
			else
			{
				$data = 0;
			}
		}		

		echo $html_opton;

		
	}

	public function getCurrency()
	{
		$params = array();
		$res = array();
		$params['country_id'] = isset($_POST['country_id']) ? $_POST['country_id'] : 0;
		$res = $this->Comman_model->getCurrencyList($params);

		if(isset($res['query']))
		{
			$result =$res['query']->result_array();
			$data = array();
			if($res['query']->num_rows() > 0 && count($result) > 0)
			{
				foreach ($result as $value) 
				{
					$data[$value['currency_id']] =ucwords(strtolower($value['currency_name']));
				  	
				}
			}
			else
			{
				$data = 0;
			}
		}		

		echo json_encode($data);

		
	}

	public function UploadProfilePic()
	{
		
		$id = isset($_POST['pic_id'])?$this->db->escape_str(trim($_POST['pic_id'])):'';
		$pic_id = $this->encryption->decrypt("$id");

		#PIC ID NOT auto-increment id Ex:admin_id,
		if(isset($pic_id) && $pic_id > 0 && isset($_FILES['uploaded_file']['name']) &&  $_FILES['uploaded_file']['name'] !='')
		{

			
			$target_dir = 'profile_images/'.$_POST['from_source'].'/';
			//echo $_FILES["uploaded_file"]["name"].time();exit;
			$fileData = pathinfo(basename($_FILES["uploaded_file"]["name"]));
			$filename = uniqid().'_'.$pic_id.'.'. $fileData['extension'];
			$target_file = $target_dir.basename($filename);
			$uploadOk = 1;
			$imageFileType = strtolower(pathinfo($_FILES["uploaded_file"]["name"],PATHINFO_EXTENSION));
			
			// Check if file already exists

			if (file_exists($target_file)) 
			{
				$uploadOk = 0;
				$this->session->set_flashdata('error', "Unable to process the request, Pleae try again.");
				redirect($_SERVER['HTTP_REFERER']);
				exit;
			}
			// Check file size
			
			if ($_FILES["uploaded_file"]["size"] > '1048576') 
			{
				
				$uploadOk = 0;
				$this->session->set_flashdata('error', "Sorry, your file is too large ( 1MB max file size )");
				redirect($_SERVER['HTTP_REFERER']);
				exit;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
			) 
			{
				
				$uploadOk = 0;
				$this->session->set_flashdata('error', "Sorry, only JPG, JPEG, PNG  files are allowed.");
				redirect($_SERVER['HTTP_REFERER']);
				exit;

			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) 
			{
				
				$this->session->set_flashdata('error', "Sorry, only JPG, JPEG, PNG  files are allowed.");
				redirect($_SERVER['HTTP_REFERER']);
				exit;
				
				
			} 
			else 
			{
				if (move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $target_file)) 
				{

					#Database Saveing
					$params = array();
					$params['pic_id'] = isset($pic_id) ? $pic_id : 0;
					$params['from_source'] = isset($_POST['from_source']) ? $_POST['from_source'] : '';
					$params['filename'] = isset($filename) ? $filename : '';
					$res = $this->Comman_model->UploadImagePic($params);

					if($res =='INSERT_SUCCESSFULLY')
					{
						if($_POST['from_source'] =='admin')
						{
							
							$this->session->unset_userdata('tupp_filename');
							$this->session->unset_userdata('tupp_source');
							$session_data = array('tupp_filename'  => $filename,'tupp_source'  => $_POST['from_source']);
							$this->session->set_userdata($session_data);
							
						}
						$this->session->set_flashdata('sucess', "successfully uploaded.");
						redirect($_SERVER['HTTP_REFERER']);
						exit;
					}
					else
					if($res =='INSERT_FAILED')
					{
						$this->session->set_flashdata('error', "Unable to process the request, Pleae try again.");
						redirect($_SERVER['HTTP_REFERER']);
						exit;	

					}
					else
					if($res =='INVALID_DATA')
					{
						$this->session->set_flashdata('error', "Unable to process the request, Pleae try again.");
						redirect($_SERVER['HTTP_REFERER']);
						exit;	
					}	

					
				
					

				} 
				else 
				{
					
					$this->session->set_flashdata('error', "Unable to process the request, Pleae try again.");
					redirect($_SERVER['HTTP_REFERER']);
					exit;


				}
			}

			
		}
		else
		{
			$this->session->set_flashdata('error', "Unable to process the request, Pleae try again.");
			redirect($_SERVER['HTTP_REFERER']);
			exit;
		}


	
	}


	public function ActiveInactiveResons()
	{
		$params= array();

		$params['admin_id'] = isset($_POST['admin_id']) ? $this->db->escape_str(trim($this->encryption->decrypt($_POST['admin_id']))) : 0;
		$params['customer_id'] = isset($_POST['customer_id']) ? $this->db->escape_str(trim($this->encryption->decrypt($_POST['customer_id']))) : 0;
		$params['product_id'] = isset($_POST['product_id']) ? $this->db->escape_str(trim($this->encryption->decrypt($_POST['product_id']))) : 0;
		$params['product_assign_id'] = isset($_POST['product_assign_id']) ? $this->encryption->decrypt($_POST['product_assign_id']) : 0;
		$params['discounts_id'] = isset($_POST['discounts_id']) ? $this->db->escape_str(trim($this->encryption->decrypt($_POST['discounts_id']))) : 0;
		$params['category_id'] = isset($_POST['category_id']) ? $this->db->escape_str(trim($this->encryption->decrypt($_POST['category_id']))) : 0;
		$params['category_prodcut_id'] = isset($_POST['category_prodcut_id']) ? $this->db->escape_str(trim($this->encryption->decrypt($_POST['category_prodcut_id']))) : 0;

		
		$params['status'] = isset($_POST['status']) ? $this->db->escape_str(trim($_POST['status'])) : '';
		$params['password'] = isset($_POST['password']) ? $this->db->escape_str(trim($_POST['password'])) : '';
		$params['reason'] = isset($_POST['reason']) ? $this->db->escape_str(trim($_POST['reason'])) : '';

		$pwdres = $this->Comman_model->PasswordValidation($params);
		//echo $this->db->last_query();
		if($pwdres =='Valid')
		{
			$res = $this->Comman_model->ActiveInactiveResonsSave($params);
			echo json_encode($pwdres);
		}
		else
		{
			echo json_encode($pwdres);
		}

	
	}


	public function getCategoryProductAmount()
	{


	
		$params = array();
		$res = array();
		$params['payment_type'] = isset($_POST['payment_type']) ? $_POST['payment_type'] : 0;
		$params['product_id'] = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
		$params['category_id'] = isset($_POST['category_id']) ? $_POST['category_id'] : 0;
		$params['currency_id'] = isset($_POST['currency_id']) ? $_POST['currency_id'] : 0;
		   
		$res = $this->Comman_model->getCategoryProductAmount($params);
		//echo $this->db->last_query(); exit;
	
		if(isset($res['query']))
		{
			
			$data = array();
			if($res['query']->num_rows() > 0)
			{
				$data =$res['query']->row_array();
				
			}
			else
			{
				$data = 0;
			}
		}		
		
			#GET TAX LIST
			$taxres=$this->Comman_model->getTaxList();
			$taxresult = array();
			if(isset($taxres['query']))
			{
				$taxresult =$taxres['query']->result_array();
			}

			$tax = array("IGST","CGST","SGST","TDS",'AMC');
			$igst=0;
			$cgst=0;
			$sgst=0;
			$tds=0;
			$amc =0;
			$taxdata= array();
			if(count($taxresult) > 0)
			{	
				foreach ($taxresult as $key => $value) 
				{	
					$taxdata[$value['tax_type']] = $value['tt_tax_percentage'];
					
				}
			}

			$gst_cal = 0;
			if(isset($data['currency_short_name']) && $data['currency_short_name']=="INR")
			{
				$cgst_cal = $taxdata['CGST'] / 100 ;
				$sgst_cal = $taxdata['SGST'] / 100 ;
				$gst_cal = $cgst_cal + $sgst_cal;


			}
			else
			{
				$gst_cal = $taxdata['IGST'] / 100;
			}
			
        	$amc_cal = $taxdata['AMC'] / 100 ;
        	$data['gst_cal']= $gst_cal ;
        	$data['amc_cal']= $amc_cal ;
        	$data['payment_type']= $params['payment_type'] ;
        	

		echo json_encode($data);

	}

	

	
}
