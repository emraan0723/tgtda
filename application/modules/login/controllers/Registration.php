<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Registration extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library(array('session', 'Auth_verify', 'form_validation', 'upload'));
        $this->load->model('Registration_model');
        date_default_timezone_set("Asia/Calcutta");
        $this->load->helper(array('url', 'form'));
    }

    public function index()
    {
        $this->load->view("registration_view");
    }

    public function logout()
    {
        $array_items = array('role' => '', 'user_id' => '', 'user_status' => '', 'admin_name' => '', 'b_developer_tools' => '', 'admin_mail' => '', 'admin_mobile' => '', 'admn_access' => '');
        $this->session->unset_userdata($array_items);
        $this->session->sess_destroy();
        redirect(base_url(''));
        exit;
    }

    public function check_mobile()
    {
        $mobile = $this->input->post('mobile');
        $exists = $this->Registration_model->check_mobile($mobile);
        echo json_encode(array('exists' => $exists));
    }

    // AJAX: Send OTP
    public function send_otp()
    {
        $mobile = $this->input->post('mobile');
        $otp = rand(100000, 999999);
        $this->session->set_userdata('otp_' . $mobile, $otp);
        $this->session->set_userdata('otp_mobile', $mobile);
        // TODO: Integrate SMS gateway here
        echo json_encode(array('success' => true, 'otp' => $otp, 'message' => 'OTP sent successfully'));
    }

    // AJAX: Verify OTP
    public function verify_otp()
    {
        $mobile      = $this->input->post('mobile');
        $entered_otp = $this->input->post('otp');
        $stored_otp  = $this->session->userdata('otp_' . $mobile);

        if ($stored_otp && $stored_otp == $entered_otp)
        {
            $this->session->set_userdata('otp_verified', true);
            $this->session->set_userdata('verified_mobile', $mobile);
            echo json_encode(array('success' => true));
        }
        else
        {
            echo json_encode(array('success' => false, 'message' => 'Invalid OTP. Please try again.'));
        }
    }

    // AJAX: Check if Aadhar exists
    public function check_aadhar()
    {
        $aadhar       = $this->input->post('aadhar');
        $aadhar_clean = preg_replace('/\s+/', '', $aadhar);
        $exists       = $this->Registration_model->check_aadhar($aadhar_clean);
        echo json_encode(array('exists' => $exists));
    }

    // Final form submission
    public function submitReg()
    {
        if (!$this->session->userdata('otp_verified'))
        {
            echo json_encode(array('success' => false, 'message' => 'OTP not verified.'));
            return;
        }

        $data = array(
            'tr_mobile'            => $this->session->userdata('verified_mobile'),
            'tr_language'          => $this->input->post('language'),
            'tr_registration_type' => $this->input->post('registration_type'),
            'tr_aadhar_no'         => preg_replace('/\s+/', '', $this->input->post('aadhar_no')),
            'tr_terms_accepted'    => $this->input->post('terms_accepted') ? 1 : 0,
            'tr_created_at'        => date('Y-m-d H:i:s'),
            'tr_status'            => 'pending'
        );

        $uniue_id    = $this->GenerateGUID();
        $upload_path = FCPATH . "uploads/registration/{$uniue_id}/";
        if (!is_dir($upload_path)) mkdir($upload_path, 0755, true);

        $type = ($data['tr_registration_type'] == 'TRANSPORT') ? 'TR' : 'DR';
        $data['tr_reg_key'] = $uniue_id;

        // ── Document fields (support JPG, PNG, PDF) ──
        // Selfie: JPG/PNG only (captured or uploaded)
        $selfie_result = $this->_handleFileField('selfie', $upload_path, $uniue_id, array('jpg|jpeg|png'));
        if ($selfie_result['error'])
        {
            echo json_encode(array('success' => false, 'message' => $selfie_result['error']));
            return;
        }
        if ($selfie_result['file_name']) $data['tr_selfie'] = $selfie_result['file_name'];

        // Identity documents: JPG, PNG, PDF allowed
        $doc_fields = array('pan_copy', 'aadhar_front', 'aadhar_back', 'transport_front', 'transport_back');
        foreach ($doc_fields as $field)
        {
            $result = $this->_handleFileField($field, $upload_path, $uniue_id, array('jpg|jpeg|png|pdf'));
            if ($result['error'])
            {
                echo json_encode(array('success' => false, 'message' => $result['error']));
                return;
            }
            if ($result['file_name']) $data['tr_' . $field] = $result['file_name'];
        }

        $id = $this->Registration_model->save($data);
        if ($id)
        {
            $this->session->unset_userdata(array('otp_verified', 'verified_mobile', 'otp_mobile'));
            $data['tr_reg_ukey'] = "{$type}-" . str_pad($id, 5, '0', STR_PAD_LEFT);
            $this->Registration_model->updateWhere($id, $data);
            echo json_encode(array(
                'success' => true,
                'message' => 'Registration submitted successfully! Our team will review and contact you shortly.',
                'id'      => $data['tr_reg_ukey']
            ));
        }
        else
        {
            echo json_encode(array('success' => false, 'message' => 'Database error. Please try again.'));
        }
    }

    /**
     * Handle a single file field upload.
     * Supports:
     *   - Standard multipart file upload ($_FILES[$field])
     *   - Camera-captured image sent as a blob (also via $_FILES[$field])
     *
     * @param  string $field       Form field name
     * @param  string $upload_path Absolute upload directory path
     * @param  string $unique_id   Unique registration GUID (used for file naming)
     * @param  array  $allowed     Array of CodeIgniter allowed_types strings (e.g. ['jpg|jpeg|png|pdf'])
     * @return array  ['file_name' => string|null, 'error' => string|null]
     */
    private function _handleFileField($field, $upload_path, $unique_id, $allowed = array('jpg|jpeg|png|pdf'))
    {
        if (empty($_FILES[$field]['name'])) return array('file_name' => null, 'error' => null);

        $allowed_types = implode('|', $allowed);
        // Expand the combined string in case multiple pipes exist
        $allowed_types = str_replace('||', '|', $allowed_types);

        $config = array(
            'upload_path'   => $upload_path,
            'allowed_types' => $allowed_types,
            'max_size'      => 5120, // 5 MB
            'file_name'     => $field . '_' . $unique_id
        );

        $this->upload->initialize($config);

        if ($this->upload->do_upload($field))
        {
            return array('file_name' => $this->upload->data('file_name'), 'error' => null);
        }
        else
        {
            return array('file_name' => null, 'error' => 'Upload error for ' . $field . ': ' . $this->upload->display_errors('', ''));
        }
    }

    function GenerateGUID()
    {
        $ret_guid   = "";
        $randValue  = (PHP_VERSION > 7) ? random_int(1, 999999) : rand(1, 999999);
        $guid       = strtoupper(md5(uniqid($randValue, true)));
        $guid_split = preg_split('//', $guid, -1, PREG_SPLIT_NO_EMPTY);

        for ($i = 0; $i < count($guid_split); $i++)
        {
            if ($i == 7 || $i == 11 || $i == 15 || $i == 19)
            {
                $ret_guid .= $guid_split[$i] . "-";
            }
            else
            {
                $ret_guid .= $guid_split[$i];
            }
        }

        return $ret_guid;
    }
}
?>
