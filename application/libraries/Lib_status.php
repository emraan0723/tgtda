<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	class Lib_status
	{
		public function change_status_save($inputData = '')
		{
			$CI =& get_instance();

			$CI->db->trans_begin();
			/*echo "<pre>";
			print_r ($inputData);
			echo "</pre>"; exit;*/
			$data['req_status'] = $inputData['status'];
			$data['req_status_changed_by'] = $inputData['req_status_changed_by'];
			$data['req_status_changed_date'] = date('Y-m-d');
			$data['req_status_changed_time'] = date('H:i:s');
			if ($inputData['status'] == 'REVIEW') 
			{
				$data['emr_associate_id'] = $inputData['user_id'];
				$data['emr_associate_name'] = $inputData['req_status_changed_by'];
				$data['module_id'] = $inputData['module_id'];
				$data['module_name'] = $inputData['module_name'];
			}

			if (isset($inputData['developer']) && $inputData['developer'] != '') 
			{
				$data['developer'] = $inputData['developer'];
				$data['developer_name'] = $inputData['developer_name'];
			}

			if (isset($inputData['tester']) && $inputData['tester'] != '') 
			{
				$data['tester'] = $inputData['tester'];
				$data['tester_name'] = $inputData['tester_name'];
			}
			if (isset($inputData['expected_date_for_live']) && $inputData['expected_date_for_live'] != '') 
			{
				$data['expected_date_for_live'] = $inputData['expected_date_for_live'];
			}
			$CI->db->where('req_id', $inputData['req_id']);
			$CI->db->update('requirements', $data);

			$data_log['wreq_id'] = $inputData['req_id'];
			$data_log['wreq_status'] = $inputData['status'];
			if(isset($inputData['reason']))
			{
				$data_log['wreq_reason'] = $inputData['reason'];
			}
			$data_log['wuser_id'] = $inputData['user_id'];
			$data_log['wcreated_by'] = $inputData['req_status_changed_by'];
			$data_log['wentry_date'] = date('Y-m-d');
			$data_log['wentry_time'] = date('H:i:s');
			$data_log['wentry_ip_add'] = $inputData['ip_add'];
			$CI->db->insert('req_status_log', $data_log);

			if ($CI->db->trans_status() === FALSE)
			{
			    $CI->db->trans_rollback();
			    return 0;
			}
			else
			{
			    $CI->db->trans_commit();
			    return true;
			}
		}

		public function get_user_email($req_user_id)
		{
            $CI =& get_instance();
			$CI->db->select('email,name');
			$CI->db->where('user_id', $req_user_id );
			$result = $CI->db->get('users')->row_array();
			if($result)
			{
			return $result;
		    }
		    else
		    {
		    	return False;
		    }
		}

		public function get_user_name($req_user_id)
		{
            $CI =& get_instance();
			$CI->db->select('name');
			$CI->db->where('user_id', $req_user_id );
			$result = $CI->db->get('users')->row_array();
			if($result)
			{
			return $result['name'];
		    }
		    else
		    {
		    	return False;
		    }
		}
		  
	}
?>