<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users Controller — v3
 * Fixed column mapping to actual tbl_registrations schema:
 *   tr_state    = int  (state ID)
 *   tr_district = int  (district ID)
 *   tr_mandal   = varchar (mandal name text)
 *   tr_village  = varchar (city/village text)
 * No tr_country_id, no tr_mandal_id columns in actual table.
 */
class Users extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
        $this->load->model(array('User_model','login/Registration_model','Location_model'));
        $this->load->helper(array('form','url','common_helper'));
        $this->load->library(array('session','upload','form_validation'));
        $this->session_check->check_session();
        $this->authorization->userauthorization('user','permissionset');
        $this->perPage = 25;
    }

    // ═══════════════════════════════════════════
    // INDEX
    // ═══════════════════════════════════════════
    public function index()
    {
        $data_view = array();
        $data_view['stats']     = $this->User_model->get_stats();
        $data_view['countries'] = $this->Location_model->get_countries();
        $data_view['data'] = array(
            'title'   => 'TGTDA|User Registration Process',
            'content' => 'user',
            'header1' => 'Users',
            'header2' => 'User Registration Process',
        );
        $this->load->view('main_page', $data_view);
    }

    // ═══════════════════════════════════════════
    // DATATABLE AJAX
    // ═══════════════════════════════════════════
    public function datatableajax()
    {
        $draw   = (int)($this->input->post('draw')   ? $this->input->post('draw')   : 1);
        $start  = (int)($this->input->post('start')  ? $this->input->post('start')  : 0);
        $length = (int)($this->input->post('length') ? $this->input->post('length') : 10);

        $searchArr = $this->input->post('search');
        $search    = (isset($searchArr['value']) && $searchArr['value'] != '') ? trim($searchArr['value']) : '';

        $orders = $this->input->post('order');
        $orders = $orders ? $orders : array();

        $col_map = array(
            0=>'tr_id', 1=>'tr_mobile', 2=>'tr_reg_ukey',
            3=>'tr_language', 4=>'tr_registration_type',
            5=>'tr_aadhar_no', 6=>'tr_status', 7=>'tr_created_at',
        );

        $col_idx   = isset($orders[0]['column']) ? (int)$orders[0]['column'] : 0;
        $order_col = isset($col_map[$col_idx]) ? $col_map[$col_idx] : 'tr_id';
        $order_dir = (isset($orders[0]['dir']) && strtoupper($orders[0]['dir']) === 'ASC') ? 'ASC' : 'DESC';

        $filter_status = $this->input->post('filter_status') ? $this->input->post('filter_status') : '';
        $filter_type   = $this->input->post('filter_type')   ? $this->input->post('filter_type')   : '';

        $params = array(
            'start'=>$start, 'length'=>$length, 'search'=>$search,
            'order_col'=>$order_col, 'order_dir'=>$order_dir,
            'filter_status'=>$filter_status, 'filter_type'=>$filter_type,
        );

        $rows           = $this->User_model->datatable_data($params);
        $total          = $this->User_model->datatable_total_count();
        $filtered_total = $this->User_model->datatable_filtered_count($params);

        $data = array();
        foreach ($rows as $r)
        {
            $status     = isset($r['tr_status'])            ? $r['tr_status']            : 'pending';
            $mobile     = isset($r['tr_mobile'])            ? $r['tr_mobile']            : '';
            $full_name  = isset($r['tr_full_name'])         ? $r['tr_full_name']         : '';
            $reg_ukey   = isset($r['tr_reg_ukey'])          ? $r['tr_reg_ukey']          : '';
            $language   = isset($r['tr_language'])          ? $r['tr_language']          : '';
            $reg_type   = isset($r['tr_registration_type']) ? $r['tr_registration_type'] : '';
            $aadhar     = isset($r['tr_aadhar_no'])         ? $r['tr_aadhar_no']         : '';
            $created_at = isset($r['tr_created_at'])        ? $r['tr_created_at']        : '';
            $tr_id      = isset($r['tr_id'])                ? (int)$r['tr_id']           : 0;

            // Address tooltip data
            $addr_parts = array();
            if (!empty($r['tr_full_address'])) $addr_parts[] = $r['tr_full_address'];
            if (!empty($r['tr_village']))      $addr_parts[] = $r['tr_village'];
            if (!empty($r['tr_mandal']))       $addr_parts[] = $r['tr_mandal'];
            if (!empty($r['district_name']))   $addr_parts[] = $r['district_name'];
            if (!empty($r['state_name']))      $addr_parts[] = $r['state_name'];
            $address_tooltip = htmlspecialchars(implode(', ', array_filter($addr_parts)), ENT_QUOTES);

            // Selfie URL
            $selfie_url = '';
            if (!empty($r['tr_selfie']) && !empty($r['tr_reg_key']))
                $selfie_url = base_url('uploads/registration/'.$r['tr_reg_key'].'/'.$r['tr_selfie']);

            $toggle_icon  = ($status === 'active')
                ? '<i class="bi bi-toggle-on text-success fs-5"></i>'
                : '<i class="bi bi-toggle-off text-secondary fs-5"></i>';

            $type_badge = ($reg_type === 'DRIVER')
                ? '<span class="badge badge-driver">&#x1F4C7; DRIVER</span>'
                : '<span class="badge badge-transport">&#x1F69B; TRANSPORT</span>';

            $status_map = array(
                'pending'=>'badge-pending','active'=>'badge-active',
                'inactive'=>'badge-inactive','approved'=>'badge-approved',
                'rejected'=>'badge-rejected',
            );
            $s_class      = isset($status_map[$status]) ? $status_map[$status] : 'badge-pending';
            $status_badge = '<span class="badge '.$s_class.'">'.strtoupper(htmlspecialchars($status)).'</span>';

            $initials     = strtoupper(substr($mobile, -2));
            $selfie_attr  = $selfie_url  ? ' data-selfie="'.htmlspecialchars($selfie_url, ENT_QUOTES).'"' : '';
            $addr_attr    = $address_tooltip ? ' data-address="'.$address_tooltip.'"' : '';

            $mobile_cell = '<div class="d-flex align-items-center gap-2 selfie-hover-trigger"'.$selfie_attr.$addr_attr.' style="cursor:default">
                <div>
                  <div class="fw-semibold" style="font-size:.85rem">'.htmlspecialchars($mobile).'</div>
                  '.($full_name ? '<div style="font-size:.72rem;color:#64748b">'.htmlspecialchars($full_name).'</div>' : '').'
                </div>
              </div>';

            $key_cell = $reg_ukey
                ? '<code style="font-size:.72rem;background:#f1f5f9;padding:2px 6px;border-radius:4px">'.htmlspecialchars($reg_ukey).'</code>'
                : '<span class="text-muted">&mdash;</span>';

            $aadhar_display = '<code style="font-family:monospace;font-size:.78rem">'.mask_aadhar($aadhar).'</code>';
            $lang_badge     = '<span class="badge" style="background:#f0f9ff;color:#0369a1">'.htmlspecialchars($language).'</span>';
            $date_cell      = '<span style="font-size:.78rem;color:#64748b">'.format_date($created_at).'</span>';

            $actions = '<div class="d-flex justify-content-center gap-1">
                <button class="btn-action edit" title="Edit / View" onclick="openModal('.$tr_id.')">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn-action pwd" title="Change Password" onclick="openPwdModal('.$tr_id.',\''.htmlspecialchars($mobile, ENT_QUOTES).'\')">
                    <i class="bi bi-key-fill"></i>
                </button>
                <button class="btn-action" title="Toggle Status" onclick="toggleStatus('.$tr_id.',\''.$status.'\')">
                    '.$toggle_icon.'
                </button>
                <button class="btn-action del" title="Delete" onclick="deleteRecord('.$tr_id.')">
                    <i class="bi bi-trash3"></i>
                </button>
              </div>';

            $data[] = array(
                '<span class="text-muted fw-semibold" style="font-size:.78rem">#'.$tr_id.'</span>',
                $mobile_cell, $key_cell, $lang_badge, $type_badge,
                $aadhar_display, $status_badge, $date_cell, $actions,
            );
        }

        echo json_encode(array(
            'draw'=>$draw, 'recordsTotal'=>(int)$total,
            'recordsFiltered'=>(int)$filtered_total,
            'data'=>$data, 'csrf_token'=>$this->security->get_csrf_hash(),
        ));
    }

    // ═══════════════════════════════════════════
    // GET SINGLE
    // ═══════════════════════════════════════════
    public function get_registration()
    {
        $this->_json();
        $tr_id  = (int)$this->input->post('tr_id');
        $record = $this->User_model->get_registration_by_id($tr_id);

        if ($record)
        {
            $doc_fields = array('tr_selfie','tr_pan_copy','tr_aadhar_front','tr_aadhar_back','tr_transport_front','tr_transport_back');
            foreach ($doc_fields as $df)
            {
                if (!empty($record[$df]) && !empty($record['tr_reg_key']))
                    $record[$df.'_url'] = base_url('uploads/registration/'.$record['tr_reg_key'].'/'.$record[$df]);
            }

            // Enrich with state/district names for cascading restore in JS
            // tr_state = state_id (int), tr_district = district_id (int)
            $record['tr_state_id_val']    = (int)$record['tr_state'];
            $record['tr_district_id_val'] = (int)$record['tr_district'];

            if (!empty($record['tr_state']))
            {
                $state_row = $this->Location_model->get_state_by_id((int)$record['tr_state']);
                if ($state_row)
                {
                    $record['tr_state_name']  = $state_row['ts_state_name'];
                    $record['tr_country_id_val'] = (int)$state_row['ts_country_id']; // ← for country dropdown
                }
            }
            if (!empty($record['tr_district']))
            {
                $dist_row = $this->Location_model->get_district_by_id((int)$record['tr_district']);
                if ($dist_row) $record['tr_district_name'] = $dist_row['tdt_district_name'];
            }

            echo json_encode(array(
                'status'=>'success','data'=>$record,'csrf_token'=>$this->security->get_csrf_hash(),
            ));
        }
        else
        {
            echo json_encode(array(
                'status'=>'error','message'=>'Record not found','csrf_token'=>$this->security->get_csrf_hash(),
            ));
        }
    }

    // ═══════════════════════════════════════════
    // SAVE
    // ═══════════════════════════════════════════
    public function save()
    {
        $this->_json();

        $tr_id = (int)$this->input->post('tr_id');

        // Server-side validation
        $this->form_validation->set_rules('tr_mobile',            'Mobile No',   'required|numeric|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('tr_aadhar_no',         'Aadhar No',   'required|min_length[12]|max_length[12]');
        $this->form_validation->set_rules('tr_language',          'Language',    'required');
        $this->form_validation->set_rules('tr_registration_type', 'Reg. Type',   'required');
        $this->form_validation->set_rules('tr_full_name',         'Full Name',   'required');
        $this->form_validation->set_rules('tr_dob',               'DOB',         'required');
        $this->form_validation->set_rules('tr_full_address',      'Address',     'required');
        $this->form_validation->set_rules('tr_state_id',          'State',       'required');
        $this->form_validation->set_rules('tr_district_id',       'District',    'required');
        $this->form_validation->set_rules('tr_mandal_id',         'Mandal',      'required');
        $this->form_validation->set_rules('tr_village',           'City/Village','required');
        $this->form_validation->set_rules('tr_pincode',           'PIN Code',    'required|numeric|min_length[6]|max_length[6]');

        if (!$this->form_validation->run())
        {
            echo json_encode(array(
                'status'=>'error','message'=>validation_errors('', ' | '),'csrf_token'=>$this->security->get_csrf_hash(),
            ));
            return;
        }

        $aadhar = $this->input->post('tr_aadhar_no');
        $mobile = $this->input->post('tr_mobile');

        if ($this->User_model->is_aadhar_duplicate($aadhar, $tr_id ? $tr_id : null))
        {
            echo json_encode(array('status'=>'error','message'=>'Aadhar number already exists!','csrf_token'=>$this->security->get_csrf_hash()));
            return;
        }
        if ($this->User_model->is_mobile_duplicate($mobile, $tr_id ? $tr_id : null))
        {
            echo json_encode(array('status'=>'error','message'=>'Mobile number already registered!','csrf_token'=>$this->security->get_csrf_hash()));
            return;
        }

        // Document required validation — ADD mode only (all 6 required)
        $upload_fields = array('tr_selfie','tr_pan_copy','tr_aadhar_front','tr_aadhar_back','tr_transport_front','tr_transport_back');
        $label_map     = array(
            'tr_selfie'=>'Selfie', 'tr_pan_copy'=>'PAN Copy',
            'tr_aadhar_front'=>'Aadhar Front', 'tr_aadhar_back'=>'Aadhar Back',
            'tr_transport_front'=>'Transport/DL Front', 'tr_transport_back'=>'Transport/DL Back',
        );

        if (!$tr_id)
        {
            foreach ($upload_fields as $field)
            {
                if (empty($_FILES[$field]['name']))
                {
                    echo json_encode(array(
                        'status'=>'error',
                        'message'=>$label_map[$field].' document is required.',
                        'csrf_token'=>$this->security->get_csrf_hash(),
                    ));
                    return;
                }
            }
        }

        // Map to actual DB columns
        // tr_state = int (state_id), tr_district = int (district_id)
        // tr_mandal = varchar (name), tr_village = varchar (name)
        $data = array(
            'tr_mobile'            => $mobile,
            'tr_language'          => $this->input->post('tr_language'),
            'tr_registration_type' => $this->input->post('tr_registration_type'),
            'tr_aadhar_no'         => $aadhar,
            'tr_full_name'         => $this->input->post('tr_full_name'),
            'tr_dob'               => $this->input->post('tr_dob') ? date('Y-m-d', strtotime($this->input->post('tr_dob'))) : NULL,
            'tr_full_address'      => $this->input->post('tr_full_address'),
            'tr_state'             => (int)$this->input->post('tr_state_id'),    // int col in DB
            'tr_district'          => (int)$this->input->post('tr_district_id'), // int col in DB
            'tr_mandal'            => $this->input->post('tr_mandal'),            // varchar — mandal name
            'tr_village'           => $this->input->post('tr_village'),           // varchar — city/village text
            'tr_pincode'           => $this->input->post('tr_pincode'),
            'tr_status'            => $this->input->post('tr_status') ? $this->input->post('tr_status') : 'pending',
            'tr_last_updated_at'   => date('Y-m-d H:i:s'),
        );

        // Determine upload key (folder)
        $uniue_id = $this->GenerateGUID();

        if ($tr_id)
        {
            $existing   = $this->User_model->get_registration_by_id($tr_id);
            $upload_key = (!empty($existing['tr_reg_key'])) ? $existing['tr_reg_key'] : $uniue_id;
        }
        else
        {
            $upload_key       = $uniue_id;
            $data['tr_reg_key'] = $upload_key;
        }

        $upload_path = FCPATH.'uploads/registration/'.$upload_key.'/';
        if (!is_dir($upload_path)) mkdir($upload_path, 0755, true);

        $type = ($data['tr_registration_type'] === 'TRANSPORT') ? 'TR' : 'DR';

        foreach ($upload_fields as $field)
        {
            if (!empty($_FILES[$field]['name']))
            {
                $res = $this->_do_upload($field, $upload_path);
                if ($res['status'] === 'success')
                    $data[$field] = $res['filename'];
                else
                {
                    echo json_encode(array('status'=>'error','message'=>$res['message'],'csrf_token'=>$this->security->get_csrf_hash()));
                    return;
                }
            }
        }

        if ($tr_id)
        {
            $ok  = $this->User_model->update_registration($tr_id, $data);
            $msg = 'Registration updated successfully!';
        }
        else
        {
            $data['tr_created_at']     = date('Y-m-d H:i:s');
            $data['tr_terms_accepted'] = 1;
            $id  = $this->User_model->insert_registration($data);
            $this->Registration_model->updateWhere($id, array(
                'tr_reg_ukey' => "{$type}-".str_pad($id, 5, '0', STR_PAD_LEFT)
            ));
            $msg = 'Registration created successfully!';
            $ok  = $id;
        }

        echo json_encode(array(
            'status'     => $ok ? 'success' : 'error',
            'message'    => $ok ? $msg : 'Database error. Please try again.',
            'csrf_token' => $this->security->get_csrf_hash(),
        ));
    }

    // ═══════════════════════════════════════════
    // TOGGLE STATUS
    // ═══════════════════════════════════════════
    public function toggle_status()
    {
        $this->_json();
        $tr_id      = (int)$this->input->post('tr_id');
        $new_status = $this->input->post('status');
        $allowed    = array('active','inactive','pending','rejected');
        if (!in_array($new_status, $allowed))
        {
            echo json_encode(array('status'=>'error','message'=>'Invalid status value','csrf_token'=>$this->security->get_csrf_hash()));
            return;
        }
        $ok = $this->User_model->update_status($tr_id, $new_status);
        echo json_encode(array(
            'status'=>$ok?'success':'error',
            'message'=>$ok?'Status changed to '.strtoupper($new_status):'Failed to update status',
            'new_status'=>$new_status, 'csrf_token'=>$this->security->get_csrf_hash(),
        ));
    }

    // ═══════════════════════════════════════════
    // CHANGE PASSWORD
    // ═══════════════════════════════════════════
    public function change_password()
    {
        $this->_json();
        $tr_id   = (int)$this->input->post('tr_id');
        $pwd     = $this->input->post('new_password');
        $confirm = $this->input->post('confirm_password');
        if (empty($pwd) || strlen($pwd) < 6)
        {
            echo json_encode(array('status'=>'error','message'=>'Password must be at least 6 characters','csrf_token'=>$this->security->get_csrf_hash()));
            return;
        }
        if ($pwd !== $confirm)
        {
            echo json_encode(array('status'=>'error','message'=>'Passwords do not match','csrf_token'=>$this->security->get_csrf_hash()));
            return;
        }
        $ok = $this->User_model->update_password($tr_id, password_hash($pwd, PASSWORD_BCRYPT));
        echo json_encode(array(
            'status'=>$ok?'success':'error',
            'message'=>$ok?'Password changed successfully!':'Failed to update password',
            'csrf_token'=>$this->security->get_csrf_hash(),
        ));
    }

    // ═══════════════════════════════════════════
    // DELETE
    // ═══════════════════════════════════════════
    public function delete()
    {
        $this->_json();
        $tr_id = (int)$this->input->post('tr_id');
        $ok    = $this->User_model->delete_registration($tr_id);
        echo json_encode(array(
            'status'=>$ok?'success':'error',
            'message'=>$ok?'Record deleted successfully!':'Failed to delete record',
            'csrf_token'=>$this->security->get_csrf_hash(),
        ));
    }

    // ═══════════════════════════════════════════
    // CHECK MOBILE / AADHAR
    // ═══════════════════════════════════════════
    public function check_mobile()
    {
        $this->_json();
        $mobile = $this->input->post('mobile');
        $tr_id  = (int)$this->input->post('tr_id');
        $exists = $this->User_model->is_mobile_duplicate($mobile, $tr_id ? $tr_id : null);
        echo json_encode(array('exists'=>(bool)$exists,'csrf_token'=>$this->security->get_csrf_hash()));
    }

    public function check_aadhar()
    {
        $this->_json();
        $aadhar = $this->input->post('aadhar');
        $tr_id  = (int)$this->input->post('tr_id');
        $exists = $this->User_model->is_aadhar_duplicate($aadhar, $tr_id ? $tr_id : null);
        echo json_encode(array('exists'=>(bool)$exists,'csrf_token'=>$this->security->get_csrf_hash()));
    }

    // ═══════════════════════════════════════════
    // LOCATION AJAX
    // ═══════════════════════════════════════════
    public function get_states()
    {
        $this->_json();
        $states = $this->Location_model->get_states_by_country((int)$this->input->post('country_id'));
        echo json_encode(array('status'=>'success','data'=>$states,'csrf_token'=>$this->security->get_csrf_hash()));
    }

    public function get_districts()
    {
        $this->_json();
        $districts = $this->Location_model->get_districts_by_state((int)$this->input->post('state_id'));
        echo json_encode(array('status'=>'success','data'=>$districts,'csrf_token'=>$this->security->get_csrf_hash()));
    }

    public function get_mandals()
    {
        $this->_json();
        $mandals = $this->Location_model->get_mandals_by_district((int)$this->input->post('district_id'));
        echo json_encode(array('status'=>'success','data'=>$mandals,'csrf_token'=>$this->security->get_csrf_hash()));
    }

    // ═══════════════════════════════════════════
    // PRIVATE HELPERS
    // ═══════════════════════════════════════════
    private function _json() { header('Content-Type: application/json'); }

    private function _do_upload($field, $path)
    {
        if (!is_dir($path)) mkdir($path, 0755, TRUE);
        $this->upload->initialize(array(
            'upload_path'=>$path, 'allowed_types'=>'jpg|jpeg|png|pdf',
            'max_size'=>2048, 'encrypt_name'=>TRUE,
        ));
        if ($this->upload->do_upload($field))
        {
            return array('status'=>'success','filename'=>$this->upload->data()['file_name']);
        }
        return array('status'=>'error','message'=>$this->upload->display_errors('',''));
    }

    function GenerateGUID()
    {
        $ret_guid  = '';
        $randValue = (PHP_VERSION > 7) ? random_int(1, 999999) : rand(1, 999999);
        $guid      = strtoupper(md5(uniqid($randValue, true)));
        $guid_split= preg_split('//', $guid, -1, PREG_SPLIT_NO_EMPTY);
        for ($i = 0; $i < count($guid_split); $i++)
        {
            $ret_guid .= $guid_split[$i];
            if ($i == 7 || $i == 11 || $i == 15 || $i == 19) $ret_guid .= '-';
        }
        return $ret_guid;
    }
}
