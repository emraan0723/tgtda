<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users Controller — v6
 * Changes from v5:
 *  - datatableajax: removed Reg.Key column from grid; added District & Mandal columns
 *  - datatableajax: reg_ukey now shown as tooltip on mobile/name hover (via data-regkey)
 *  - save(): captures client IP into tr_ip_address on both insert and update
 *  - Search in model now also covers tr_mandal
 *  - All other logic unchanged.
 */
class Users extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library(array('Session_check','session','form_validation','validationdigi','errormsgs','encryption','authorization'));
        $this->load->model(array('User_model','login/Registration_model','Location_model'));
        $this->load->helper(array('form','url','common_helper'));
        $this->load->library(array('session','upload','form_validation','email_helper'));
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

        // ── Column map updated: Reg.Key removed, District & Mandal added ──
        // Grid col indices: 0=#, 1=Mobile/Name, 2=District, 3=Mandal, 4=Language, 5=Type, 6=Aadhar, 7=Status, 8=Date, 9=Actions
        $col_map = array(
            0 => 'tr_id',
            1 => 'tr_mobile',
            2 => 'district_name',
            3 => 'tr_mandal',
            4 => 'tr_language',
            5 => 'tr_registration_type',
            6 => 'tr_aadhar_no',
            7 => 'tr_status',
            8 => 'tr_created_at',
        );

        $col_idx   = isset($orders[0]['column']) ? (int)$orders[0]['column'] : 0;
        $order_col = isset($col_map[$col_idx]) ? $col_map[$col_idx] : 'tr_id';
        $order_dir = (isset($orders[0]['dir']) && strtoupper($orders[0]['dir']) === 'ASC') ? 'ASC' : 'DESC';

        $filter_status   = $this->input->post('filter_status')   ? $this->input->post('filter_status')   : '';
        $filter_type     = $this->input->post('filter_type')     ? $this->input->post('filter_type')     : '';
        $filter_district = $this->input->post('filter_district') ? $this->input->post('filter_district') : '';
        $filter_mandal   = $this->input->post('filter_mandal')   ? $this->input->post('filter_mandal')   : '';

        $params = array(
            'start'           => $start,
            'length'          => $length,
            'search'          => $search,
            'order_col'       => $order_col,
            'order_dir'       => $order_dir,
            'filter_status'   => $filter_status,
            'filter_type'     => $filter_type,
            'filter_district' => $filter_district,
            'filter_mandal'   => $filter_mandal,
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
            $district   = isset($r['district_name'])        ? $r['district_name']        : (isset($r['tr_district']) ? $r['tr_district'] : '');
            $mandal     = isset($r['tr_mandal'])            ? $r['tr_mandal']            : '';

            $addr_parts = array();
            if (!empty($r['tr_full_address'])) $addr_parts[] = $r['tr_full_address'];
            if (!empty($r['tr_village']))      $addr_parts[] = $r['tr_village'];
            if (!empty($r['tr_mandal']))       $addr_parts[] = $r['tr_mandal'];
            if (!empty($r['district_name']))   $addr_parts[] = $r['district_name'];
            if (!empty($r['state_name']))      $addr_parts[] = $r['state_name'];
            $address_tooltip = htmlspecialchars(implode(', ', array_filter($addr_parts)), ENT_QUOTES);

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
                'pending'  => 'badge-pending',
                'active'   => 'badge-active',
                'inactive' => 'badge-inactive',
                'approved' => 'badge-approved',
                'rejected' => 'badge-rejected',
            );
            $s_class      = isset($status_map[$status]) ? $status_map[$status] : 'badge-pending';
            $status_badge = '<span class="badge '.$s_class.'">'.strtoupper(htmlspecialchars($status)).'</span>';

            $selfie_attr  = $selfie_url      ? ' data-selfie="'.htmlspecialchars($selfie_url, ENT_QUOTES).'"'  : '';
            $addr_attr    = $address_tooltip ? ' data-address="'.$address_tooltip.'"'                          : '';
            // ── Reg.Key now surfaced as tooltip on hover ──
            $regkey_attr  = $reg_ukey        ? ' data-regkey="'.htmlspecialchars($reg_ukey, ENT_QUOTES).'"'    : '';

            $mobile_cell = '<div class="d-flex align-items-center gap-2 selfie-hover-trigger"'.$selfie_attr.$addr_attr.$regkey_attr.' style="cursor:default">
                <div>
                  <div class="fw-semibold" style="font-size:.85rem">'.htmlspecialchars($mobile).'</div>
                  '.($full_name ? '<div style="font-size:.72rem;color:#64748b">'.htmlspecialchars($full_name).'</div>' : '').'
                </div>
              </div>';

            // ── District cell ──
            $district_cell = $district
                ? '<span style="font-size:.8rem">'.htmlspecialchars($district).'</span>'
                : '<span class="text-muted">&mdash;</span>';

            // ── Mandal cell ──
            $mandal_cell = $mandal
                ? '<span style="font-size:.8rem">'.htmlspecialchars($mandal).'</span>'
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

            // ── 10 data columns (no standalone Reg.Key col; District & Mandal added) ──
            $data[] = array(
                '<span class="text-muted fw-semibold" style="font-size:.78rem">#'.$tr_id.'</span>',
                $mobile_cell,
                $district_cell,
                $mandal_cell,
                $lang_badge,
                $type_badge,
                $aadhar_display,
                $status_badge,
                $date_cell,
                $actions,
            );
        }

        echo json_encode(array(
            'draw'           => $draw,
            'recordsTotal'   => (int)$total,
            'recordsFiltered'=> (int)$filtered_total,
            'data'           => $data,
            'csrf_token'     => $this->security->get_csrf_hash(),
        ));
    }

    // ═══════════════════════════════════════════
    // GET SINGLE
    // ═══════════════════════════════════════════
    public function get_registration()
    {
        $this->getJson();
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

            $record['tr_state_id_val']    = (int)$record['tr_state'];
            $record['tr_district_id_val'] = (int)$record['tr_district'];

            if (!empty($record['tr_state']))
            {
                $state_row = $this->Location_model->get_state_by_id((int)$record['tr_state']);
                if ($state_row)
                {
                    $record['tr_state_name']     = $state_row['ts_state_name'];
                    $record['tr_country_id_val'] = (int)$state_row['ts_country_id'];
                }
            }
            if (!empty($record['tr_district']))
            {
                $dist_row = $this->Location_model->get_district_by_id((int)$record['tr_district']);
                if ($dist_row) $record['tr_district_name'] = $dist_row['tdt_district_name'];
            }

            echo json_encode(array(
                'status'     => 'success',
                'data'       => $record,
                'csrf_token' => $this->security->get_csrf_hash(),
            ));
        }
        else
        {
            echo json_encode(array(
                'status'     => 'error',
                'message'    => 'Record not found',
                'csrf_token' => $this->security->get_csrf_hash(),
            ));
        }
    }

    // ═══════════════════════════════════════════
    // SAVE
    // Added: tr_ip_address captured on insert AND update
    // ═══════════════════════════════════════════
    public function save()
    {
        $this->getJson();

        $tr_id = (int)$this->input->post('tr_id');

        $this->form_validation->set_rules('tr_mobile',            'Mobile No',   'required|numeric|min_length[10]|max_length[10]');
        $this->form_validation->set_rules('tr_aadhar_no',         'Aadhar No',   'required|min_length[12]|max_length[12]');
        $this->form_validation->set_rules('tr_pan_no',            'PAN No',      'required|min_length[10]|max_length[10]|regex_match[/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/]');
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
        $this->form_validation->set_rules('tr_email',             'Email',       'required|valid_email');

        if (!$this->form_validation->run())
        {
            echo json_encode(array(
                'status'     => 'error',
                'message'    => validation_errors('', ' | '),
                'csrf_token' => $this->security->get_csrf_hash(),
            ));
            return;
        }

        $aadhar = $this->input->post('tr_aadhar_no');
        $mobile = $this->input->post('tr_mobile');
        $email  = $this->input->post('tr_email');
        $status = $this->input->post('tr_status') ? $this->input->post('tr_status') : 'pending';

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

        $upload_fields = array('tr_selfie','tr_pan_copy','tr_aadhar_front','tr_aadhar_back','tr_transport_front','tr_transport_back');
        $label_map     = array(
            'tr_selfie'         => 'Selfie',
            'tr_pan_copy'       => 'PAN Copy',
            'tr_aadhar_front'   => 'Aadhar Front',
            'tr_aadhar_back'    => 'Aadhar Back',
            'tr_transport_front'=> 'Transport/DL Front',
            'tr_transport_back' => 'Transport/DL Back',
        );

        if (!$tr_id)
        {
            foreach ($upload_fields as $field)
            {
                if (empty($_FILES[$field]['name']))
                {
                    echo json_encode(array(
                        'status'     => 'error',
                        'message'    => $label_map[$field].' document is required.',
                        'csrf_token' => $this->security->get_csrf_hash(),
                    ));
                    return;
                }
            }
        }

        // ── Capture client IP address ──
        $ip_address = $this->input->ip_address();

        $data = array(
            'tr_mobile'            => $mobile,
            'tr_email'             => $email,
            'tr_pan_no'            => strtoupper($this->input->post('tr_pan_no')),
            'tr_language'          => $this->input->post('tr_language'),
            'tr_registration_type' => $this->input->post('tr_registration_type'),
            'tr_aadhar_no'         => $aadhar,
            'tr_full_name'         => $this->input->post('tr_full_name'),
            'tr_dob'               => $this->input->post('tr_dob') ? date('Y-m-d', strtotime($this->input->post('tr_dob'))) : NULL,
            'tr_full_address'      => $this->input->post('tr_full_address'),
            'tr_state'             => (int)$this->input->post('tr_state_id'),
            'tr_district'          => (int)$this->input->post('tr_district_id'),
            'tr_mandal'            => $this->input->post('tr_mandal'),
            'tr_village'           => $this->input->post('tr_village'),
            'tr_pincode'           => $this->input->post('tr_pincode'),
            'tr_status'            => $status,
            'tr_ip_address'        => $ip_address,   // ← IP captured here
            'tr_last_updated_at'   => date('Y-m-d H:i:s'),
        );

        $uniue_id = $this->GenerateGUID();
        $type     = ($data['tr_registration_type'] === 'TRANSPORT') ? 'TR' : 'DR';

        // Get old record before update (for email trigger + ukey fix)
        $old_status  = '';
        $old_email   = '';
        $existing    = array();
        if ($tr_id)
        {
            $existing    = $this->User_model->get_registration_by_id($tr_id);
            $upload_key  = (!empty($existing['tr_reg_key'])) ? $existing['tr_reg_key'] : $uniue_id;
            $old_status  = isset($existing['tr_status']) ? $existing['tr_status'] : '';
            $old_email   = isset($existing['tr_email'])  ? $existing['tr_email']  : '';
        }
        else
        {
            $upload_key         = $uniue_id;
            $data['tr_reg_key'] = $upload_key;
        }

        $upload_path = FCPATH.'uploads/registration/'.$upload_key.'/';
        if (!is_dir($upload_path)) mkdir($upload_path, 0755, true);

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
            // ── Fix tr_reg_ukey prefix when registration type changes ──
            $existing_ukey = isset($existing['tr_reg_ukey']) ? $existing['tr_reg_ukey'] : '';
            if (preg_match('/(\d+)$/', $existing_ukey, $m))
            {
                $ukey_number = $m[1];
            }
            else
            {
                $ukey_number = str_pad($tr_id, 5, '0', STR_PAD_LEFT);
            }
            $data['tr_reg_ukey'] = $type . '-' . $ukey_number;

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
            $msg   = 'Registration created successfully!';
            $ok    = $id;
            $tr_id = $id;
        }

        // ── Send email if status changed to active ──
        if ($ok && $status === 'active' && $old_status !== 'active' && !empty($email))
        {
            $plain_pwd = $this->_generate_plain_password();
            $hashed    = password_hash($plain_pwd, PASSWORD_BCRYPT);
            $this->User_model->update_password($tr_id, $hashed);
            $this->email_helper->send_password_email(
                $email,
                $data['tr_full_name'],
                $mobile,
                $plain_pwd,
                'active'
            );
        }

        echo json_encode(array(
            'status'     => $ok ? 'success' : 'error',
            'message'    => $ok ? $msg : 'Database error. Please try again.',
            'csrf_token' => $this->security->get_csrf_hash(),
        ));
    }

    // ═══════════════════════════════════════════
    // TOGGLE STATUS — sends email on status change
    // ═══════════════════════════════════════════
    public function toggle_status()
    {
        $this->getJson();
        $tr_id      = (int)$this->input->post('tr_id');
        $new_status = $this->input->post('status');
        $allowed    = array('active','inactive','pending','rejected');

        if (!in_array($new_status, $allowed))
        {
            echo json_encode(array('status'=>'error','message'=>'Invalid status value','csrf_token'=>$this->security->get_csrf_hash()));
            return;
        }

        $record = $this->User_model->get_registration_by_id($tr_id);
        $ok     = $this->User_model->update_status($tr_id, $new_status);

        if ($ok && $record)
        {
            $email      = isset($record['tr_email'])     ? $record['tr_email']     : '';
            $name       = isset($record['tr_full_name']) ? $record['tr_full_name'] : '';
            $mobile     = isset($record['tr_mobile'])    ? $record['tr_mobile']    : '';
            $old_status = isset($record['tr_status'])    ? $record['tr_status']    : '';

            if (!empty($email))
            {
                if ($new_status === 'active' && $old_status !== 'active')
                {
                    $plain_pwd = $this->_generate_plain_password();
                    $hashed    = password_hash($plain_pwd, PASSWORD_BCRYPT);
                    $this->User_model->update_password($tr_id, $hashed);
                    $this->email_helper->send_password_email($email, $name, $mobile, $plain_pwd, 'active');
                }
                else
                {
                    $this->email_helper->send_status_email($email, $name, $mobile, $new_status);
                }
            }
        }

        echo json_encode(array(
            'status'     => $ok ? 'success' : 'error',
            'message'    => $ok ? 'Status changed to '.strtoupper($new_status) : 'Failed to update status',
            'new_status' => $new_status,
            'csrf_token' => $this->security->get_csrf_hash(),
        ));
    }

    // ═══════════════════════════════════════════
    // CHANGE PASSWORD — sends email after change
    // ═══════════════════════════════════════════
    public function change_password()
    {
        $this->getJson();
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

        $record = $this->User_model->get_registration_by_id($tr_id);
        $ok     = $this->User_model->update_password($tr_id, password_hash($pwd, PASSWORD_BCRYPT));

        if ($ok && $record && !empty($record['tr_email']))
        {
            $this->email_helper->send_password_email(
                $record['tr_email'],
                $record['tr_full_name'],
                $record['tr_mobile'],
                $pwd,
                isset($record['tr_status']) ? $record['tr_status'] : 'active'
            );
        }

        echo json_encode(array(
            'status'     => $ok ? 'success' : 'error',
            'message'    => $ok ? 'Password changed and emailed successfully!' : 'Failed to update password',
            'csrf_token' => $this->security->get_csrf_hash(),
        ));
    }

    // ═══════════════════════════════════════════
    // CHECK MOBILE / AADHAR
    // ═══════════════════════════════════════════
    public function check_mobile()
    {
        $this->getJson();
        $mobile = $this->input->post('mobile');
        $tr_id  = (int)$this->input->post('tr_id');
        $exists = $this->User_model->is_mobile_duplicate($mobile, $tr_id ? $tr_id : null);
        echo json_encode(array('exists'=>(bool)$exists,'csrf_token'=>$this->security->get_csrf_hash()));
    }

    public function check_aadhar()
    {
        $this->getJson();
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
        $this->getJson();
        $states = $this->Location_model->get_states_by_country((int)$this->input->post('country_id'));
        echo json_encode(array('status'=>'success','data'=>$states,'csrf_token'=>$this->security->get_csrf_hash()));
    }

    public function get_districts()
    {
        $this->getJson();
        $districts = $this->Location_model->get_districts_by_state((int)$this->input->post('state_id'));
        echo json_encode(array('status'=>'success','data'=>$districts,'csrf_token'=>$this->security->get_csrf_hash()));
    }

    public function get_mandals()
    {
        $this->getJson();
        $mandals = $this->Location_model->get_mandals_by_district((int)$this->input->post('district_id'));
        echo json_encode(array('status'=>'success','data'=>$mandals,'csrf_token'=>$this->security->get_csrf_hash()));
    }

    // ═══════════════════════════════════════════
    // GET ALL DISTRICTS — for filter dropdown
    // ═══════════════════════════════════════════
    public function get_all_districts()
    {
        $this->getJson();
        $districts = $this->Location_model->get_all_districts();
        echo json_encode(array('status'=>'success','data'=>$districts,'csrf_token'=>$this->security->get_csrf_hash()));
    }

    // ═══════════════════════════════════════════
    // DELETE
    // ═══════════════════════════════════════════
    public function delete()
    {
        $this->getJson();
        $tr_id = (int)$this->input->post('tr_id');
        $ok    = $this->User_model->delete_registration($tr_id);
        echo json_encode(array(
            'status'     => $ok ? 'success' : 'error',
            'message'    => $ok ? 'Record deleted successfully!' : 'Failed to delete record',
            'csrf_token' => $this->security->get_csrf_hash(),
        ));
    }

    // ═══════════════════════════════════════════
    // PRIVATE HELPERS
    // ═══════════════════════════════════════════
    private function _json() { header('Content-Type: application/json'); }

    private function _do_upload($field, $path)
    {
        if (!is_dir($path)) mkdir($path, 0755, TRUE);
        $this->upload->initialize(array(
            'upload_path'   => $path,
            'allowed_types' => 'jpg|jpeg|png|pdf',
            'max_size'      => 2048,
            'encrypt_name'  => TRUE,
        ));
        if ($this->upload->do_upload($field))
            return array('status'=>'success','filename'=>$this->upload->data()['file_name']);

        return array('status'=>'error','message'=>$this->upload->display_errors('',''));
    }

    private function _generate_plain_password($length = 10)
    {
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789@#$!';
        $pwd   = '';
        $max   = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++)
            $pwd .= $chars[rand(0, $max)];
        return $pwd;
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

    public function save_latlng()
    {
        $this->getJson();

        // PHP 7.2 safe: use ternary instead of ?? null coalescing on post values
        $force       = (bool)(int)($this->input->post('force') ? $this->input->post('force') : 0);

        $state_id    = (int)($this->input->post('state_id')    ? $this->input->post('state_id')    : 0);
        $state_lat   = (float)($this->input->post('state_lat') ? $this->input->post('state_lat')   : 0);
        $state_lng   = (float)($this->input->post('state_lng') ? $this->input->post('state_lng')   : 0);

        $district_id = (int)($this->input->post('district_id') ? $this->input->post('district_id') : 0);
        $dist_lat    = (float)($this->input->post('dist_lat')  ? $this->input->post('dist_lat')    : 0);
        $dist_lng    = (float)($this->input->post('dist_lng')  ? $this->input->post('dist_lng')    : 0);

        $mandal_id   = (int)($this->input->post('mandal_id')   ? $this->input->post('mandal_id')   : 0);
        $mandal_lat  = (float)($this->input->post('mandal_lat')? $this->input->post('mandal_lat')  : 0);
        $mandal_lng  = (float)($this->input->post('mandal_lng')? $this->input->post('mandal_lng')  : 0);

        $saved = array();

        if ($state_id > 0 && $state_lat != 0.0 && $state_lng != 0.0) {
            $ok = $this->Location_model->save_state_latlng($state_id, $state_lat, $state_lng, $force);
            if ($ok) {
                $saved[] = 'state';
            }
        }

        if ($district_id > 0 && $dist_lat != 0.0 && $dist_lng != 0.0) {
            $ok = $this->Location_model->save_district_latlng($district_id, $dist_lat, $dist_lng, $force);
            if ($ok) {
                $saved[] = 'district';
            }
        }

        if ($mandal_id > 0 && $mandal_lat != 0.0 && $mandal_lng != 0.0) {
            $ok = $this->Location_model->save_mandal_latlng($mandal_id, $mandal_lat, $mandal_lng, $force);
            if ($ok) {
                $saved[] = 'mandal';
            }
        }

        echo json_encode(array(
            'status'     => 'success',
            'saved'      => $saved,
            'csrf_token' => $this->security->get_csrf_hash(),
        ));
    }

    public function get_latlng()
    {
        $this->getJson();

        $state_id    = (int)($this->input->post('state_id')    ? $this->input->post('state_id')    : 0);
        $district_id = (int)($this->input->post('district_id') ? $this->input->post('district_id') : 0);
        $mandal_id   = (int)($this->input->post('mandal_id')   ? $this->input->post('mandal_id')   : 0);

        $result = array(
            'state'    => array('lat' => null, 'lng' => null),
            'district' => array('lat' => null, 'lng' => null),
            'mandal'   => array('lat' => null, 'lng' => null),
        );

        if ($state_id > 0) {
            $result['state'] = $this->Location_model->get_state_latlng($state_id);
        }
        if ($district_id > 0) {
            $result['district'] = $this->Location_model->get_district_latlng($district_id);
        }
        if ($mandal_id > 0) {
            $result['mandal'] = $this->Location_model->get_mandal_latlng($mandal_id);
        }

        echo json_encode(array(
            'status'     => 'success',
            'data'       => $result,
            'csrf_token' => $this->security->get_csrf_hash(),
        ));
    }

    private function getJson() { header('Content-Type: application/json'); }
}
