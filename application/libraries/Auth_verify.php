<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auth_verify
{

    public $username;
    public $password;
    public $session_data = array();

    // Login user
    public function login_user()
    {
        //echo password_hash(trim($this->password), PASSWORD_BCRYPT); exit;

        $CI =& get_instance();
        $CI->db->select("CONCAT(tu_first_name,' ',tu_last_name) as user_name,tu_password AS password,tu_status AS status,tu_gender AS gender,tu_role AS role,tu_ID AS user_id,tu_email AS email,tu_mobile AS mobile,tu_address AS user_address,tupp_source, tupp_filename");
        $CI->db->from('tbl_users');
        $CI->db->join('tbl_upload_profile_pics', 'tupp_source_id=tu_ID AND tupp_source="admin"', 'left');
        $CI->db->where('tu_username', $this->username);
        $CI->db->where('tu_status', 'ACTIVE');
        $data = $CI->db->get()->row_array();
        $db_hash_pwd = $data['password'];

        if (password_verify($this->password, $db_hash_pwd))
        {
            $CI->db->select("tup_permission_module AS permission_module,
					tup_adding AS adding ,
					tup_edit AS edit,
					tup_view AS view ,
					tup_no_access AS no_access,
					tup_full_access AS full_access,
					(CASE
					WHEN tup_adding > 0 THEN 1
					WHEN tup_edit > 0 THEN 1
					WHEN tup_view > 0 THEN 1
					WHEN tup_full_access > 0 THEN 1
					ELSE 0 END ) AS permissionset ");
            $CI->db->from('tbl_user_permissions');
            $CI->db->where('tup_user_ID', $data['user_id']);
            $userdata = $CI->db->get()->result_array();
            $permistiondata = array();
            foreach ($userdata as $key => $value)
            {
                $permistiondata[$value['permission_module']] = $value;
            }


            $session_data = array(
                'role' => $data['role'],
                'user_name' => $data['user_name'],
                'user_id' => $data['user_id'],
                'user_status' => $data['status'],
                'user_mail' => $data['email'],
                'user_mobile' => $data['mobile'],
                'user_address' => $data['user_address'],
                'tupp_source' => $data['tupp_source'],
                'tupp_filename' => $data['tupp_filename'],
                'userprivileges' => $permistiondata,

            );

            $CI->session->set_userdata($session_data);
            return true;
        }
        else
        {
            return false;
            exit;
        }
    }


    public function logout_user()
    {
        $CI =& get_instance();
        $CI->session->unset_userdata('role');
        $CI->session->unset_userdata('user_name');
        $CI->session->unset_userdata('user_id');
        $CI->session->unset_userdata('user_status');
        $CI->session->unset_userdata('user_mail');
        $CI->session->unset_userdata('user_mobile');
        $CI->session->unset_userdata('user_address');
        $CI->session->unset_userdata('userprivileges');
        $CI->session->sess_destroy();
        return true;
    }


    public function ulogin_user()
    {
        $CI =& get_instance();
        $CI->db->select("tr_full_name as uuser_name,tr_password AS password,tr_status AS ustatus,tr_id AS uuser_id,tr_mobile AS umobile,tr_full_address AS uuser_address");
        $CI->db->from('tbl_registrations');
        $CI->db->where('tr_mobile', $this->username);
        $CI->db->where('tr_status', 'active');
        $data = $CI->db->get()->row_array();
        $db_hash_pwd = $data['password'];
        //print_r($db_hash_pwd);
        // echo $this->password."======================aaaaaaaaaaa".$db_hash_pwd."===================".password_verify($this->password , $db_hash_pwd);
        //echo $CI->db->last_query(); exit;
        if (password_verify($this->password, $db_hash_pwd))
        {
            $session_data = array(
                'uuser_name' => $data['uuser_name'],
                'uuser_id' => $data['uuser_id'],
                'uuser_status' => $data['ustatus'],
                'uuser_mobile' => $data['umobile'],
                'uuser_address' => $data['uuser_address'],
            );

            $CI->session->set_userdata($session_data);
            return true;
        }
        else
        {
            return false;
            exit;
        }
    }

    public function ulogout_user()
    {
        $CI =& get_instance();
        $CI->session->unset_userdata('uuser_name');
        $CI->session->unset_userdata('uuser_id');
        $CI->session->unset_userdata('uuser_status');
        $CI->session->unset_userdata('uuser_mobile');
        $CI->session->unset_userdata('uuser_address');
        $CI->session->sess_destroy();
        return true;
    }


}

?>